/**
 * Redis Client Configuration
 * Session storage and caching
 */

const redis = require('redis');
const logger = require('../utils/logger');

class RedisClient {
  constructor() {
    this.client = null;
    this.isConnected = false;
  }

  async connect() {
    try {
      if (!process.env.REDIS_URL) {
        logger.warn('Redis URL not configured - caching disabled');
        return;
      }

      this.client = redis.createClient({
        url: process.env.REDIS_URL
      });

      await this.client.connect();
      this.isConnected = true;
      logger.info('Redis connected successfully');
    } catch (error) {
      logger.warn('Redis connection failed:', error.message);
      this.isConnected = false;
    }
  }

  async disconnect() {
    if (this.client) {
      await this.client.disconnect();
      logger.info('Redis disconnected');
    }
  }

  async get(key) {
    if (!this.isConnected) return null;
    try {
      return await this.client.get(key);
    } catch (error) {
      logger.error('Redis get failed:', error.message);
      return null;
    }
  }

  async set(key, value, expiration = 3600) {
    if (!this.isConnected) return false;
    try {
      await this.client.setEx(key, expiration, value);
      return true;
    } catch (error) {
      logger.error('Redis set failed:', error.message);
      return false;
    }
  }
}

module.exports = new RedisClient();