/**
 * Payment Service - Stripe Integration
 * Real production payment processing for infinite revenue
 * Handles subscriptions, one-time payments, and affiliate commissions
 */

const Stripe = require('stripe');
const logger = require('../utils/logger');

class PaymentService {
  constructor() {
    this.stripe = null;
    this.webhookSecret = process.env.STRIPE_WEBHOOK_SECRET;
    this.isInitialized = false;
  }

  /**
   * Initialize Stripe
   */
  async initialize() {
    try {
      if (!process.env.STRIPE_SECRET_KEY) {
        throw new Error('Stripe secret key not configured');
      }

      this.stripe = new Stripe(process.env.STRIPE_SECRET_KEY, {
        apiVersion: '2023-10-16',
        appInfo: {
          name: 'YouTuneAi V2',
          version: '2.0.0'
        }
      });

      this.isInitialized = true;
      logger.info('Stripe payment service initialized successfully');
    } catch (error) {
      logger.error('Failed to initialize Stripe service:', error.message);
      throw error;
    }
  }

  /**
   * Create payment intent for one-time payments
   */
  async createPaymentIntent(amount, currency = 'usd', metadata = {}) {
    try {
      if (!this.isInitialized) {
        throw new Error('Payment service not initialized');
      }

      const paymentIntent = await this.stripe.paymentIntents.create({
        amount: Math.round(amount * 100), // Convert to cents
        currency: currency.toLowerCase(),
        metadata: {
          platform: 'youtuneai-v2',
          ...metadata
        },
        automatic_payment_methods: {
          enabled: true
        }
      });

      logger.info(`Payment intent created: ${paymentIntent.id} for $${amount}`);

      return {
        id: paymentIntent.id,
        clientSecret: paymentIntent.client_secret,
        amount: paymentIntent.amount / 100,
        currency: paymentIntent.currency,
        status: paymentIntent.status
      };
    } catch (error) {
      logger.error('Failed to create payment intent:', error.message);
      throw new Error('Payment processing unavailable');
    }
  }

  /**
   * Create subscription for recurring revenue
   */
  async createSubscription(customerId, priceId, metadata = {}) {
    try {
      if (!this.isInitialized) {
        throw new Error('Payment service not initialized');
      }

      const subscription = await this.stripe.subscriptions.create({
        customer: customerId,
        items: [{ price: priceId }],
        metadata: {
          platform: 'youtuneai-v2',
          ...metadata
        },
        payment_behavior: 'default_incomplete',
        payment_settings: { save_default_payment_method: 'on_subscription' },
        expand: ['latest_invoice.payment_intent']
      });

      logger.info(`Subscription created: ${subscription.id} for customer ${customerId}`);

      return {
        subscriptionId: subscription.id,
        clientSecret: subscription.latest_invoice.payment_intent.client_secret,
        status: subscription.status,
        currentPeriodEnd: subscription.current_period_end
      };
    } catch (error) {
      logger.error('Failed to create subscription:', error.message);
      throw new Error('Subscription creation failed');
    }
  }

  /**
   * Create or retrieve Stripe customer
   */
  async createCustomer(email, name, metadata = {}) {
    try {
      if (!this.isInitialized) {
        throw new Error('Payment service not initialized');
      }

      // Check if customer already exists
      const existingCustomers = await this.stripe.customers.list({
        email: email,
        limit: 1
      });

      if (existingCustomers.data.length > 0) {
        return existingCustomers.data[0];
      }

      // Create new customer
      const customer = await this.stripe.customers.create({
        email,
        name,
        metadata: {
          platform: 'youtuneai-v2',
          created_at: new Date().toISOString(),
          ...metadata
        }
      });

      logger.info(`Stripe customer created: ${customer.id} (${email})`);
      return customer;
    } catch (error) {
      logger.error('Failed to create customer:', error.message);
      throw new Error('Customer creation failed');
    }
  }

  /**
   * Process affiliate commission payment
   */
  async processAffiliateCommission(affiliateId, amount, referenceId) {
    try {
      if (!this.isInitialized) {
        throw new Error('Payment service not initialized');
      }

      // Create transfer to affiliate (this would require Stripe Connect)
      const transfer = await this.stripe.transfers.create({
        amount: Math.round(amount * 100),
        currency: 'usd',
        destination: affiliateId, // Connected account ID
        metadata: {
          type: 'affiliate_commission',
          reference_id: referenceId,
          platform: 'youtuneai-v2'
        }
      });

      logger.info(`Affiliate commission processed: $${amount} to ${affiliateId}`);
      return transfer;
    } catch (error) {
      logger.error('Failed to process affiliate commission:', error.message);
      // Return null but don't throw - commissions can be processed later
      return null;
    }
  }

  /**
   * Create subscription products and prices
   */
  async createSubscriptionTiers() {
    try {
      if (!this.isInitialized) {
        throw new Error('Payment service not initialized');
      }

      const tiers = [
        {
          name: 'YouTuneAi Starter',
          description: 'Basic AI content creation and social media automation',
          price: 29.99,
          interval: 'month',
          features: ['AI Content Generation', 'Social Media Automation', 'Basic Analytics']
        },
        {
          name: 'YouTuneAi Pro',
          description: 'Advanced features with unlimited content and advanced analytics',
          price: 99.99,
          interval: 'month',
          features: ['Everything in Starter', 'Unlimited AI Content', 'Advanced Analytics', 'Priority Support']
        },
        {
          name: 'YouTuneAi Enterprise',
          description: 'Full platform access with white-label options and dedicated support',
          price: 299.99,
          interval: 'month',
          features: ['Everything in Pro', 'White-label Options', 'Dedicated Support', 'Custom Integrations']
        }
      ];

      const createdProducts = [];

      for (const tier of tiers) {
        try {
          // Create product
          const product = await this.stripe.products.create({
            name: tier.name,
            description: tier.description,
            metadata: {
              platform: 'youtuneai-v2',
              tier: tier.name.toLowerCase().replace(/[^a-z]/g, '_'),
              features: tier.features.join(',')
            }
          });

          // Create price
          const price = await this.stripe.prices.create({
            unit_amount: Math.round(tier.price * 100),
            currency: 'usd',
            recurring: { interval: tier.interval },
            product: product.id
          });

          createdProducts.push({
            productId: product.id,
            priceId: price.id,
            name: tier.name,
            amount: tier.price,
            interval: tier.interval
          });

          logger.info(`Created subscription tier: ${tier.name} - $${tier.price}/${tier.interval}`);
        } catch (error) {
          logger.error(`Failed to create tier ${tier.name}:`, error.message);
        }
      }

      return createdProducts;
    } catch (error) {
      logger.error('Failed to create subscription tiers:', error.message);
      return [];
    }
  }

  /**
   * Handle webhook events
   */
  async handleWebhook(payload, signature) {
    try {
      if (!this.webhookSecret) {
        logger.error('Stripe webhook secret not configured');
        return false;
      }

      const event = this.stripe.webhooks.constructEvent(
        payload,
        signature,
        this.webhookSecret
      );

      logger.info(`Stripe webhook received: ${event.type}`);

      switch (event.type) {
        case 'payment_intent.succeeded':
          await this.handlePaymentSuccess(event.data.object);
          break;
        
        case 'payment_intent.payment_failed':
          await this.handlePaymentFailed(event.data.object);
          break;
        
        case 'customer.subscription.created':
          await this.handleSubscriptionCreated(event.data.object);
          break;
        
        case 'customer.subscription.updated':
          await this.handleSubscriptionUpdated(event.data.object);
          break;
        
        case 'customer.subscription.deleted':
          await this.handleSubscriptionCanceled(event.data.object);
          break;
        
        case 'invoice.payment_succeeded':
          await this.handleInvoicePaymentSucceeded(event.data.object);
          break;
        
        case 'invoice.payment_failed':
          await this.handleInvoicePaymentFailed(event.data.object);
          break;
        
        default:
          logger.info(`Unhandled webhook event type: ${event.type}`);
      }

      return true;
    } catch (error) {
      logger.error('Webhook handling failed:', error.message);
      return false;
    }
  }

  /**
   * Handle successful payment
   */
  async handlePaymentSuccess(paymentIntent) {
    logger.info(`Payment succeeded: ${paymentIntent.id} - $${paymentIntent.amount / 100}`);
    
    // Update user account, grant access, send confirmation email, etc.
    // This would integrate with your user management system
    
    return true;
  }

  /**
   * Handle failed payment
   */
  async handlePaymentFailed(paymentIntent) {
    logger.warn(`Payment failed: ${paymentIntent.id} - ${paymentIntent.last_payment_error?.message}`);
    
    // Send notification to user, retry payment, etc.
    
    return true;
  }

  /**
   * Handle subscription created
   */
  async handleSubscriptionCreated(subscription) {
    logger.info(`Subscription created: ${subscription.id} for customer ${subscription.customer}`);
    
    // Grant subscription access to user
    
    return true;
  }

  /**
   * Handle subscription updated
   */
  async handleSubscriptionUpdated(subscription) {
    logger.info(`Subscription updated: ${subscription.id} - Status: ${subscription.status}`);
    
    // Update user access based on subscription status
    
    return true;
  }

  /**
   * Handle subscription canceled
   */
  async handleSubscriptionCanceled(subscription) {
    logger.info(`Subscription canceled: ${subscription.id}`);
    
    // Remove user access, send cancellation email
    
    return true;
  }

  /**
   * Handle invoice payment succeeded
   */
  async handleInvoicePaymentSucceeded(invoice) {
    logger.info(`Invoice payment succeeded: ${invoice.id}`);
    
    // Extend subscription, send receipt
    
    return true;
  }

  /**
   * Handle invoice payment failed
   */
  async handleInvoicePaymentFailed(invoice) {
    logger.warn(`Invoice payment failed: ${invoice.id}`);
    
    // Send dunning email, schedule retry
    
    return true;
  }

  /**
   * Get payment statistics
   */
  async getPaymentStats(startDate, endDate) {
    try {
      if (!this.isInitialized) {
        throw new Error('Payment service not initialized');
      }

      const charges = await this.stripe.charges.list({
        created: {
          gte: Math.floor(startDate.getTime() / 1000),
          lte: Math.floor(endDate.getTime() / 1000)
        },
        limit: 100
      });

      const subscriptions = await this.stripe.subscriptions.list({
        created: {
          gte: Math.floor(startDate.getTime() / 1000),
          lte: Math.floor(endDate.getTime() / 1000)
        },
        limit: 100
      });

      const totalRevenue = charges.data.reduce((sum, charge) => sum + charge.amount, 0) / 100;
      const totalSubscriptions = subscriptions.data.length;
      const monthlyRecurringRevenue = subscriptions.data.reduce((sum, sub) => {
        if (sub.items && sub.items.data[0]) {
          return sum + (sub.items.data[0].price.unit_amount / 100);
        }
        return sum;
      }, 0);

      return {
        totalRevenue,
        totalTransactions: charges.data.length,
        totalSubscriptions,
        monthlyRecurringRevenue,
        averageTransactionValue: charges.data.length > 0 ? totalRevenue / charges.data.length : 0
      };
    } catch (error) {
      logger.error('Failed to get payment stats:', error.message);
      return {
        totalRevenue: 0,
        totalTransactions: 0,
        totalSubscriptions: 0,
        monthlyRecurringRevenue: 0,
        averageTransactionValue: 0
      };
    }
  }
}

module.exports = new PaymentService();