/**
 * Authentication Middleware
 * JWT token validation with BlackVault security integration
 */

const jwt = require('jsonwebtoken');
const BlackVaultService = require('../services/BlackVaultService');
const logger = require('../utils/logger');

/**
 * Verify JWT token and authenticate user
 */
async function authenticate(req, res, next) {
  try {
    const token = extractToken(req);
    
    if (!token) {
      return res.status(401).json({
        error: 'Authentication required',
        message: 'No authentication token provided'
      });
    }

    // Verify JWT token
    const decoded = jwt.verify(token, process.env.JWT_SECRET);
    
    if (!decoded || !decoded.userId) {
      return res.status(401).json({
        error: 'Invalid token',
        message: 'Authentication token is invalid'
      });
    }

    // Additional security check with BlackVault if available
    const securityScan = await BlackVaultService.performSecurityScan(decoded.userId, req);
    
    if (securityScan.threatLevel === 'high' || !securityScan.clean) {
      logger.warn(`Security threat detected for user ${decoded.userId}:`, securityScan);
      return res.status(403).json({
        error: 'Security check failed',
        message: 'Request blocked due to security concerns'
      });
    }

    // Add user info to request object
    req.user = {
      id: decoded.userId,
      email: decoded.email,
      role: decoded.role,
      subscriptionTier: decoded.subscriptionTier
    };

    // Log authentication event
    await BlackVaultService.logSecurityEvent('authentication_success', decoded.userId, {
      ip: req.ip,
      userAgent: req.get('User-Agent'),
      endpoint: req.path
    });

    next();
  } catch (error) {
    logger.error('Authentication failed:', error.message);

    if (error.name === 'TokenExpiredError') {
      return res.status(401).json({
        error: 'Token expired',
        message: 'Authentication token has expired'
      });
    } else if (error.name === 'JsonWebTokenError') {
      return res.status(401).json({
        error: 'Invalid token',
        message: 'Authentication token is malformed'
      });
    }

    return res.status(401).json({
      error: 'Authentication failed',
      message: 'Could not authenticate user'
    });
  }
}

/**
 * Extract token from request headers
 */
function extractToken(req) {
  const authHeader = req.get('Authorization');
  
  if (authHeader && authHeader.startsWith('Bearer ')) {
    return authHeader.substring(7);
  }
  
  // Also check for token in cookies
  if (req.cookies && req.cookies.auth_token) {
    return req.cookies.auth_token;
  }
  
  return null;
}

/**
 * Check if user has required role
 */
function requireRole(role) {
  return (req, res, next) => {
    if (!req.user) {
      return res.status(401).json({
        error: 'Authentication required',
        message: 'User not authenticated'
      });
    }

    if (req.user.role !== role && req.user.role !== 'admin') {
      return res.status(403).json({
        error: 'Insufficient permissions',
        message: `${role} role required`
      });
    }

    next();
  };
}

/**
 * Check if user has required subscription tier
 */
function requireSubscription(tier) {
  const tierLevels = {
    'free': 0,
    'starter': 1,
    'pro': 2,
    'enterprise': 3
  };

  return (req, res, next) => {
    if (!req.user) {
      return res.status(401).json({
        error: 'Authentication required',
        message: 'User not authenticated'
      });
    }

    const userTierLevel = tierLevels[req.user.subscriptionTier] || 0;
    const requiredTierLevel = tierLevels[tier] || 999;

    if (userTierLevel < requiredTierLevel) {
      return res.status(403).json({
        error: 'Subscription upgrade required',
        message: `${tier} subscription required for this feature`,
        currentTier: req.user.subscriptionTier,
        requiredTier: tier
      });
    }

    next();
  };
}

/**
 * Optional authentication - doesn't fail if no token
 */
async function optionalAuth(req, res, next) {
  try {
    const token = extractToken(req);
    
    if (token) {
      const decoded = jwt.verify(token, process.env.JWT_SECRET);
      if (decoded && decoded.userId) {
        req.user = {
          id: decoded.userId,
          email: decoded.email,
          role: decoded.role,
          subscriptionTier: decoded.subscriptionTier
        };
      }
    }
  } catch (error) {
    // Ignore authentication errors for optional auth
    logger.debug('Optional auth failed:', error.message);
  }
  
  next();
}

module.exports = {
  authenticate,
  requireRole,
  requireSubscription,
  optionalAuth
};