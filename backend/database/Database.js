/**
 * Database Connection Handler
 * PostgreSQL connection with connection pooling
 */

const { Pool } = require('pg');
const logger = require('../utils/logger');

class Database {
  constructor() {
    this.pool = null;
    this.isConnected = false;
  }

  /**
   * Connect to PostgreSQL database
   */
  async connect() {
    try {
      if (!process.env.DATABASE_URL) {
        logger.warn('Database URL not configured - using in-memory fallback');
        this.isConnected = false;
        return;
      }

      this.pool = new Pool({
        connectionString: process.env.DATABASE_URL,
        ssl: process.env.NODE_ENV === 'production' ? { rejectUnauthorized: false } : false,
        max: 20, // Maximum connections in pool
        idleTimeoutMillis: 30000,
        connectionTimeoutMillis: 2000,
      });

      // Test connection
      const client = await this.pool.connect();
      await client.query('SELECT NOW()');
      client.release();

      // Create tables if they don't exist
      await this.initializeTables();

      this.isConnected = true;
      logger.info('Database connected successfully');
    } catch (error) {
      logger.error('Database connection failed:', error.message);
      this.isConnected = false;
    }
  }

  /**
   * Disconnect from database
   */
  async disconnect() {
    try {
      if (this.pool) {
        await this.pool.end();
        logger.info('Database disconnected');
      }
    } catch (error) {
      logger.error('Database disconnect error:', error.message);
    }
  }

  /**
   * Initialize database tables
   */
  async initializeTables() {
    const tables = [
      // Users table
      `CREATE TABLE IF NOT EXISTS users (
        id SERIAL PRIMARY KEY,
        email VARCHAR(255) UNIQUE NOT NULL,
        password_hash VARCHAR(255) NOT NULL,
        name VARCHAR(255),
        role VARCHAR(50) DEFAULT 'user',
        subscription_tier VARCHAR(50) DEFAULT 'free',
        stripe_customer_id VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        last_login TIMESTAMP,
        is_active BOOLEAN DEFAULT true,
        metadata JSONB DEFAULT '{}'::jsonb
      )`,

      // Sessions table
      `CREATE TABLE IF NOT EXISTS sessions (
        id SERIAL PRIMARY KEY,
        user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
        session_token VARCHAR(255) UNIQUE NOT NULL,
        blackvault_session_id VARCHAR(255),
        expires_at TIMESTAMP NOT NULL,
        user_agent TEXT,
        ip_address INET,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        last_accessed TIMESTAMP DEFAULT CURRENT_TIMESTAMP
      )`,

      // Payments table
      `CREATE TABLE IF NOT EXISTS payments (
        id SERIAL PRIMARY KEY,
        user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
        stripe_payment_id VARCHAR(255) UNIQUE NOT NULL,
        amount DECIMAL(10,2) NOT NULL,
        currency VARCHAR(3) DEFAULT 'USD',
        status VARCHAR(50) NOT NULL,
        type VARCHAR(50) NOT NULL, -- 'one_time', 'subscription', 'commission'
        description TEXT,
        metadata JSONB DEFAULT '{}'::jsonb,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        processed_at TIMESTAMP
      )`,

      // Content table
      `CREATE TABLE IF NOT EXISTS content (
        id SERIAL PRIMARY KEY,
        user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
        title VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        content_type VARCHAR(50) NOT NULL, -- 'article', 'social', 'video', 'email'
        platform VARCHAR(50), -- 'twitter', 'facebook', 'blog', etc.
        status VARCHAR(50) DEFAULT 'draft', -- 'draft', 'published', 'scheduled'
        keywords TEXT[],
        seo_score INTEGER,
        ai_generated BOOLEAN DEFAULT false,
        performance_metrics JSONB DEFAULT '{}'::jsonb,
        scheduled_at TIMESTAMP,
        published_at TIMESTAMP,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
      )`,

      // Analytics table
      `CREATE TABLE IF NOT EXISTS analytics (
        id SERIAL PRIMARY KEY,
        user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
        event_type VARCHAR(50) NOT NULL,
        event_data JSONB NOT NULL,
        platform VARCHAR(50),
        content_id INTEGER REFERENCES content(id) ON DELETE SET NULL,
        timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        session_id VARCHAR(255),
        ip_address INET
      )`,

      // Affiliates table
      `CREATE TABLE IF NOT EXISTS affiliates (
        id SERIAL PRIMARY KEY,
        user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
        affiliate_code VARCHAR(50) UNIQUE NOT NULL,
        commission_rate DECIMAL(5,4) DEFAULT 0.15,
        total_earnings DECIMAL(10,2) DEFAULT 0,
        total_referrals INTEGER DEFAULT 0,
        status VARCHAR(50) DEFAULT 'active',
        payment_info JSONB DEFAULT '{}'::jsonb,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
      )`,

      // Social Media Accounts table
      `CREATE TABLE IF NOT EXISTS social_accounts (
        id SERIAL PRIMARY KEY,
        user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
        platform VARCHAR(50) NOT NULL,
        account_id VARCHAR(255) NOT NULL,
        access_token TEXT,
        refresh_token TEXT,
        token_expires_at TIMESTAMP,
        account_info JSONB DEFAULT '{}'::jsonb,
        is_active BOOLEAN DEFAULT true,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE(user_id, platform, account_id)
      )`
    ];

    for (const tableSQL of tables) {
      try {
        await this.query(tableSQL);
      } catch (error) {
        logger.error('Failed to create table:', error.message);
      }
    }

    logger.info('Database tables initialized successfully');
  }

  /**
   * Execute a query
   */
  async query(text, params = []) {
    if (!this.isConnected) {
      throw new Error('Database not connected');
    }

    const start = Date.now();
    try {
      const res = await this.pool.query(text, params);
      const duration = Date.now() - start;
      
      if (duration > 1000) {
        logger.warn(`Slow query detected (${duration}ms):`, text.substring(0, 100));
      }
      
      return res;
    } catch (error) {
      logger.error('Database query error:', {
        query: text.substring(0, 100),
        error: error.message,
        params: params?.length || 0
      });
      throw error;
    }
  }

  /**
   * Get a client from the pool
   */
  async getClient() {
    if (!this.isConnected) {
      throw new Error('Database not connected');
    }
    return this.pool.connect();
  }

  /**
   * Check database health
   */
  async healthCheck() {
    try {
      if (!this.isConnected) {
        return { status: 'disconnected', error: 'No database connection' };
      }

      const result = await this.query('SELECT COUNT(*) as user_count FROM users');
      const userCount = result.rows[0].user_count;

      return {
        status: 'connected',
        userCount: parseInt(userCount),
        poolSize: this.pool.totalCount,
        idleClients: this.pool.idleCount,
        waitingClients: this.pool.waitingCount
      };
    } catch (error) {
      return {
        status: 'error',
        error: error.message
      };
    }
  }
}

module.exports = new Database();