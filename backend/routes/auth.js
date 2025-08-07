/**
 * Authentication Routes
 * User registration, login, and session management
 */

const express = require('express');
const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');
const { body, validationResult } = require('express-validator');
const rateLimit = require('express-rate-limit');

const Database = require('../database/Database');
const BlackVaultService = require('../services/BlackVaultService');
const PaymentService = require('../services/PaymentService');
const EmailService = require('../services/EmailService');
const logger = require('../utils/logger');

const router = express.Router();

// Rate limiting for auth endpoints
const authLimiter = rateLimit({
  windowMs: 15 * 60 * 1000, // 15 minutes
  max: 5, // Max 5 attempts per window
  message: {
    error: 'Too many authentication attempts',
    message: 'Please try again later'
  },
  standardHeaders: true,
  legacyHeaders: false
});

/**
 * User Registration
 */
router.post('/register', 
  authLimiter,
  [
    body('email').isEmail().normalizeEmail(),
    body('password').isLength({ min: 8 }).matches(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/),
    body('name').trim().isLength({ min: 2, max: 100 })
  ],
  async (req, res) => {
    try {
      // Validate input
      const errors = validationResult(req);
      if (!errors.isEmpty()) {
        return res.status(400).json({
          error: 'Validation failed',
          details: errors.array()
        });
      }

      const { email, password, name } = req.body;

      // Check if user already exists
      const existingUser = await Database.query(
        'SELECT id FROM users WHERE email = $1',
        [email]
      );

      if (existingUser.rows.length > 0) {
        return res.status(409).json({
          error: 'User already exists',
          message: 'An account with this email already exists'
        });
      }

      // Hash password
      const saltRounds = 12;
      const passwordHash = await bcrypt.hash(password, saltRounds);

      // Create Stripe customer
      let stripeCustomerId = null;
      try {
        const stripeCustomer = await PaymentService.createCustomer(email, name, {
          source: 'registration'
        });
        stripeCustomerId = stripeCustomer.id;
      } catch (error) {
        logger.warn('Failed to create Stripe customer during registration:', error.message);
      }

      // Create user in database
      const result = await Database.query(`
        INSERT INTO users (email, password_hash, name, stripe_customer_id, metadata)
        VALUES ($1, $2, $3, $4, $5)
        RETURNING id, email, name, role, subscription_tier, created_at
      `, [
        email,
        passwordHash,
        name,
        stripeCustomerId,
        JSON.stringify({
          registration_ip: req.ip,
          registration_user_agent: req.get('User-Agent'),
          email_verified: false
        })
      ]);

      const user = result.rows[0];

      // Create secure session
      const session = await BlackVaultService.createSecureSession(
        user.id,
        req.get('User-Agent'),
        req.ip
      );

      // Generate JWT token
      const token = jwt.sign(
        {
          userId: user.id,
          email: user.email,
          role: user.role,
          subscriptionTier: user.subscription_tier
        },
        process.env.JWT_SECRET,
        { expiresIn: '24h' }
      );

      // Store session in database
      await Database.query(`
        INSERT INTO sessions (user_id, session_token, blackvault_session_id, expires_at, user_agent, ip_address)
        VALUES ($1, $2, $3, $4, $5, $6)
      `, [
        user.id,
        token,
        session.sessionId,
        new Date(Date.now() + 24 * 60 * 60 * 1000), // 24 hours
        req.get('User-Agent'),
        req.ip
      ]);

      // Log security event
      await BlackVaultService.logSecurityEvent('user_registration', user.id, {
        email: email,
        ip: req.ip,
        userAgent: req.get('User-Agent')
      });

      // Send welcome email (if email service is available)
      try {
        await EmailService.sendWelcomeEmail(email, name);
      } catch (error) {
        logger.warn('Failed to send welcome email:', error.message);
      }

      logger.info(`New user registered: ${email} (ID: ${user.id})`);

      res.status(201).json({
        message: 'Registration successful',
        user: {
          id: user.id,
          email: user.email,
          name: user.name,
          role: user.role,
          subscriptionTier: user.subscription_tier,
          createdAt: user.created_at
        },
        token: token,
        expiresAt: new Date(Date.now() + 24 * 60 * 60 * 1000).toISOString()
      });

    } catch (error) {
      logger.error('Registration failed:', error.message);
      res.status(500).json({
        error: 'Registration failed',
        message: 'Internal server error during registration'
      });
    }
  }
);

/**
 * User Login
 */
router.post('/login',
  authLimiter,
  [
    body('email').isEmail().normalizeEmail(),
    body('password').isLength({ min: 1 })
  ],
  async (req, res) => {
    try {
      // Validate input
      const errors = validationResult(req);
      if (!errors.isEmpty()) {
        return res.status(400).json({
          error: 'Validation failed',
          details: errors.array()
        });
      }

      const { email, password } = req.body;

      // Get user from database
      const result = await Database.query(`
        SELECT id, email, password_hash, name, role, subscription_tier, is_active
        FROM users WHERE email = $1
      `, [email]);

      if (result.rows.length === 0) {
        return res.status(401).json({
          error: 'Invalid credentials',
          message: 'Email or password is incorrect'
        });
      }

      const user = result.rows[0];

      if (!user.is_active) {
        return res.status(401).json({
          error: 'Account disabled',
          message: 'Your account has been disabled'
        });
      }

      // Verify password
      const isValidPassword = await bcrypt.compare(password, user.password_hash);
      if (!isValidPassword) {
        await BlackVaultService.logSecurityEvent('login_failed', user.id, {
          reason: 'invalid_password',
          ip: req.ip,
          userAgent: req.get('User-Agent')
        });

        return res.status(401).json({
          error: 'Invalid credentials',
          message: 'Email or password is incorrect'
        });
      }

      // Create secure session
      const session = await BlackVaultService.createSecureSession(
        user.id,
        req.get('User-Agent'),
        req.ip
      );

      // Generate JWT token
      const token = jwt.sign(
        {
          userId: user.id,
          email: user.email,
          role: user.role,
          subscriptionTier: user.subscription_tier
        },
        process.env.JWT_SECRET,
        { expiresIn: '24h' }
      );

      // Store session in database
      await Database.query(`
        INSERT INTO sessions (user_id, session_token, blackvault_session_id, expires_at, user_agent, ip_address)
        VALUES ($1, $2, $3, $4, $5, $6)
      `, [
        user.id,
        token,
        session.sessionId,
        new Date(Date.now() + 24 * 60 * 60 * 1000),
        req.get('User-Agent'),
        req.ip
      ]);

      // Update last login
      await Database.query(
        'UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = $1',
        [user.id]
      );

      // Log successful login
      await BlackVaultService.logSecurityEvent('login_success', user.id, {
        ip: req.ip,
        userAgent: req.get('User-Agent')
      });

      logger.info(`User logged in: ${email} (ID: ${user.id})`);

      res.json({
        message: 'Login successful',
        user: {
          id: user.id,
          email: user.email,
          name: user.name,
          role: user.role,
          subscriptionTier: user.subscription_tier
        },
        token: token,
        expiresAt: new Date(Date.now() + 24 * 60 * 60 * 1000).toISOString()
      });

    } catch (error) {
      logger.error('Login failed:', error.message);
      res.status(500).json({
        error: 'Login failed',
        message: 'Internal server error during login'
      });
    }
  }
);

/**
 * Token Refresh
 */
router.post('/refresh', async (req, res) => {
  try {
    const { refreshToken } = req.body;

    if (!refreshToken) {
      return res.status(400).json({
        error: 'Refresh token required',
        message: 'No refresh token provided'
      });
    }

    // Verify refresh token
    const decoded = jwt.verify(refreshToken, process.env.JWT_REFRESH_SECRET);
    
    // Get user from database
    const result = await Database.query(`
      SELECT id, email, role, subscription_tier, is_active
      FROM users WHERE id = $1
    `, [decoded.userId]);

    if (result.rows.length === 0 || !result.rows[0].is_active) {
      return res.status(401).json({
        error: 'Invalid refresh token',
        message: 'User not found or inactive'
      });
    }

    const user = result.rows[0];

    // Generate new access token
    const newToken = jwt.sign(
      {
        userId: user.id,
        email: user.email,
        role: user.role,
        subscriptionTier: user.subscription_tier
      },
      process.env.JWT_SECRET,
      { expiresIn: '24h' }
    );

    res.json({
      token: newToken,
      expiresAt: new Date(Date.now() + 24 * 60 * 60 * 1000).toISOString()
    });

  } catch (error) {
    logger.error('Token refresh failed:', error.message);
    res.status(401).json({
      error: 'Token refresh failed',
      message: 'Invalid or expired refresh token'
    });
  }
});

/**
 * Logout
 */
router.post('/logout', async (req, res) => {
  try {
    const token = req.get('Authorization')?.substring(7);
    
    if (token) {
      // Remove session from database
      await Database.query(
        'DELETE FROM sessions WHERE session_token = $1',
        [token]
      );
    }

    res.json({ message: 'Logout successful' });

  } catch (error) {
    logger.error('Logout failed:', error.message);
    res.status(500).json({
      error: 'Logout failed',
      message: 'Internal server error during logout'
    });
  }
});

module.exports = router;