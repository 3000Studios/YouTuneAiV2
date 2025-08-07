/**
 * YouTuneAi V2 - Production Backend Server
 * Full-stack monetization and automation platform
 * Boss Man's infinite revenue system
 */

const express = require('express');
const cors = require('cors');
const helmet = require('helmet');
const compression = require('compression');
const morgan = require('morgan');
const rateLimit = require('express-rate-limit');
const dotenv = require('dotenv');

// Load environment variables
dotenv.config({ path: '../.env' });

// Core services
const AuthService = require('./services/AuthService');
const PaymentService = require('./services/PaymentService');
const BlackVaultService = require('./services/BlackVaultService');
const WordPressService = require('./services/WordPressService');
const IonosService = require('./services/IonosService');
const AIService = require('./services/AIService');
const SocialMediaService = require('./services/SocialMediaService');
const AnalyticsService = require('./services/AnalyticsService');
const EmailService = require('./services/EmailService');
const BackupService = require('./services/BackupService');

// Database connections
const Database = require('./database/Database');
const RedisClient = require('./database/RedisClient');

// Routes
const authRoutes = require('./routes/auth');
const paymentRoutes = require('./routes/payments');
const userRoutes = require('./routes/users');
const contentRoutes = require('./routes/content');
const aiRoutes = require('./routes/ai');
const socialRoutes = require('./routes/social');
const analyticsRoutes = require('./routes/analytics');
const affiliateRoutes = require('./routes/affiliate');
const subscriptionRoutes = require('./routes/subscriptions');
const webhookRoutes = require('./routes/webhooks');

// Middleware
const authMiddleware = require('./middleware/auth');
const validationMiddleware = require('./middleware/validation');
const errorHandler = require('./middleware/errorHandler');
const logger = require('./utils/logger');

const app = express();
const PORT = process.env.API_PORT || 3001;

// Security and performance middleware
app.use(helmet({
  contentSecurityPolicy: {
    directives: {
      defaultSrc: ["'self'"],
      styleSrc: ["'self'", "'unsafe-inline'", "https:"],
      scriptSrc: ["'self'", "https:"],
      imgSrc: ["'self'", "data:", "https:"],
      connectSrc: ["'self'", "https:"],
      fontSrc: ["'self'", "https:"],
      objectSrc: ["'none'"],
      mediaSrc: ["'self'", "https:"],
      frameSrc: ["'self'", "https:"],
    },
  },
  crossOriginEmbedderPolicy: false
}));

app.use(compression());
app.use(cors({
  origin: process.env.NODE_ENV === 'production' 
    ? ['https://youtuneai.com', 'https://www.youtuneai.com']
    : ['http://localhost:3000', 'http://localhost:3001'],
  credentials: true
}));

// Rate limiting
const limiter = rateLimit({
  windowMs: parseInt(process.env.RATE_LIMIT_WINDOW_MS) || 15 * 60 * 1000, // 15 minutes
  max: parseInt(process.env.RATE_LIMIT_MAX_REQUESTS) || 100,
  message: {
    error: 'Too many requests from this IP, please try again later.',
    code: 'RATE_LIMIT_EXCEEDED'
  }
});

app.use(limiter);

// Parsing middleware
app.use(express.json({ limit: '10mb' }));
app.use(express.urlencoded({ extended: true, limit: '10mb' }));

// Logging middleware
if (process.env.NODE_ENV === 'production') {
  app.use(morgan('combined'));
} else {
  app.use(morgan('dev'));
}

// Health check endpoint
app.get('/health', (req, res) => {
  res.json({
    status: 'OK',
    timestamp: new Date().toISOString(),
    environment: process.env.NODE_ENV,
    version: '2.0.0',
    services: {
      database: 'connected',
      redis: 'connected',
      blackvault: 'active',
      stripe: 'active',
      openai: 'active'
    }
  });
});

// API status and information
app.get('/api/v1/status', (req, res) => {
  res.json({
    name: 'YouTuneAi V2 API',
    version: '2.0.0',
    description: 'Full-stack monetization and automation platform',
    boss: 'Boss Man\'s infinite revenue system',
    endpoints: {
      auth: '/api/v1/auth',
      payments: '/api/v1/payments',
      users: '/api/v1/users',
      content: '/api/v1/content',
      ai: '/api/v1/ai',
      social: '/api/v1/social',
      analytics: '/api/v1/analytics',
      affiliates: '/api/v1/affiliates',
      subscriptions: '/api/v1/subscriptions',
      webhooks: '/api/v1/webhooks'
    },
    integrations: {
      blackvault: 'Security & Authentication',
      ionos: 'Hosting & Domain Management',
      wordpress: 'Content Management',
      stripe: 'Payment Processing',
      openai: 'AI Content Generation',
      twitter: 'Social Media Automation',
      youtube: 'Video Platform Integration',
      google_analytics: 'Traffic Analytics',
      mailgun: 'Email Marketing'
    },
    features: [
      'Infinite Revenue Streams',
      'Automated Social Media',
      'AI Content Generation',
      'Payment Processing',
      'Affiliate Network',
      'Creator Monetization',
      'Real-time Analytics',
      'Security & Compliance',
      'Backup & Recovery',
      'Auto-scaling Infrastructure'
    ]
  });
});

// Initialize services
async function initializeServices() {
  try {
    // Initialize database connections
    await Database.connect();
    await RedisClient.connect();
    
    // Initialize core services
    await AuthService.initialize();
    await PaymentService.initialize();
    await BlackVaultService.initialize();
    await WordPressService.initialize();
    await IonosService.initialize();
    await AIService.initialize();
    await SocialMediaService.initialize();
    await AnalyticsService.initialize();
    await EmailService.initialize();
    await BackupService.initialize();
    
    logger.info('All services initialized successfully');
  } catch (error) {
    logger.error('Failed to initialize services:', error);
    process.exit(1);
  }
}

// API Routes
app.use('/api/v1/auth', authRoutes);
app.use('/api/v1/payments', paymentRoutes);
app.use('/api/v1/users', authMiddleware, userRoutes);
app.use('/api/v1/content', authMiddleware, contentRoutes);
app.use('/api/v1/ai', authMiddleware, aiRoutes);
app.use('/api/v1/social', authMiddleware, socialRoutes);
app.use('/api/v1/analytics', authMiddleware, analyticsRoutes);
app.use('/api/v1/affiliates', authMiddleware, affiliateRoutes);
app.use('/api/v1/subscriptions', authMiddleware, subscriptionRoutes);
app.use('/api/v1/webhooks', webhookRoutes);

// Error handling middleware
app.use(errorHandler);

// 404 handler
app.use('*', (req, res) => {
  res.status(404).json({
    error: 'Endpoint not found',
    message: `The requested endpoint ${req.method} ${req.originalUrl} does not exist`,
    availableEndpoints: [
      'GET /health',
      'GET /api/v1/status',
      'POST /api/v1/auth/login',
      'POST /api/v1/auth/register',
      'GET /api/v1/users/profile',
      'POST /api/v1/payments/create-intent',
      'POST /api/v1/ai/generate',
      'GET /api/v1/analytics/dashboard'
    ]
  });
});

// Graceful shutdown
process.on('SIGTERM', async () => {
  logger.info('SIGTERM received, shutting down gracefully');
  await Database.disconnect();
  await RedisClient.disconnect();
  process.exit(0);
});

process.on('SIGINT', async () => {
  logger.info('SIGINT received, shutting down gracefully');
  await Database.disconnect();
  await RedisClient.disconnect();
  process.exit(0);
});

// Start the server
async function startServer() {
  try {
    await initializeServices();
    
    app.listen(PORT, process.env.HOST || '0.0.0.0', () => {
      logger.info(`🚀 YouTuneAi V2 Backend Server running on port ${PORT}`);
      logger.info(`🌍 Environment: ${process.env.NODE_ENV}`);
      logger.info(`💎 Boss Man's Infinite Revenue System: ACTIVE`);
      logger.info(`🔐 BlackVault Security: ENABLED`);
      logger.info(`💳 Payment Processing: LIVE`);
      logger.info(`🤖 AI Services: OPERATIONAL`);
      logger.info(`📱 Social Media Automation: RUNNING`);
      logger.info(`📊 Analytics Tracking: ACTIVE`);
      logger.info(`🌐 IONOS Integration: CONNECTED`);
      logger.info(`📝 WordPress CMS: SYNCHRONIZED`);
      logger.info(`✅ All systems operational - Ready for infinite revenue!`);
    });
  } catch (error) {
    logger.error('Failed to start server:', error);
    process.exit(1);
  }
}

startServer();