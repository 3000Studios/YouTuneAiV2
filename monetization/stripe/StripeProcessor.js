/**
 * Stripe Payment Integration
 * Real production payment processing with Boss Man's infinite revenue logic
 */

const stripe = require('stripe')(process.env.STRIPE_SECRET_KEY)
const logger = require('../../backend/utils/logger')

class StripePaymentProcessor {
  constructor() {
    this.commission_rate = parseFloat(process.env.CREATOR_COMMISSION_RATE) || 0.15
  }

  /**
   * Create payment intent for one-time purchases
   */
  async createPaymentIntent(amount, currency = 'usd', metadata = {}) {
    try {
      const paymentIntent = await stripe.paymentIntents.create({
        amount: Math.round(amount * 100), // Convert to cents
        currency: currency.toLowerCase(),
        automatic_payment_methods: {
          enabled: true
        },
        metadata: {
          platform: 'youtuneai-v2',
          boss_mans_system: 'true',
          infinite_revenue: 'active',
          ...metadata
        }
      })

      // Calculate Boss Man's cut (platform fee)
      const platformFee = Math.round(amount * 0.05 * 100) // 5% platform fee
      const affiliateCommission = metadata.affiliate_id 
        ? Math.round(amount * this.commission_rate * 100)
        : 0

      logger.info(`Payment intent created: ${paymentIntent.id} - Amount: $${amount}`, {
        platformFee: platformFee / 100,
        affiliateCommission: affiliateCommission / 100,
        netRevenue: (amount * 100 - platformFee - affiliateCommission) / 100
      })

      return {
        paymentIntentId: paymentIntent.id,
        clientSecret: paymentIntent.client_secret,
        amount: amount,
        platformFee: platformFee / 100,
        affiliateCommission: affiliateCommission / 100,
        netRevenue: (amount * 100 - platformFee - affiliateCommission) / 100
      }
    } catch (error) {
      logger.error('Failed to create payment intent:', error.message)
      throw error
    }
  }

  /**
   * Create subscription with multiple tiers for infinite revenue scaling
   */
  async createSubscription(customerId, tier = 'starter', metadata = {}) {
    try {
      const subscriptionTiers = {
        starter: {
          priceId: 'price_starter_monthly',
          amount: 29.99,
          features: ['AI Content Generation', 'Social Media Automation', 'Basic Analytics']
        },
        pro: {
          priceId: 'price_pro_monthly', 
          amount: 99.99,
          features: ['Everything in Starter', 'Unlimited Content', 'Advanced Analytics', 'Priority Support']
        },
        enterprise: {
          priceId: 'price_enterprise_monthly',
          amount: 299.99,
          features: ['Everything in Pro', 'White-label Options', 'Dedicated Support', 'Custom Integrations']
        },
        boss_ultimate: {
          priceId: 'price_boss_ultimate_monthly',
          amount: 999.99,
          features: ['Everything in Enterprise', 'Personal AI Assistant', 'Revenue Optimization', 'Boss Man Direct Access']
        }
      }

      const selectedTier = subscriptionTiers[tier] || subscriptionTiers.starter

      const subscription = await stripe.subscriptions.create({
        customer: customerId,
        items: [{ price: selectedTier.priceId }],
        metadata: {
          platform: 'youtuneai-v2',
          tier: tier,
          boss_mans_system: 'true',
          infinite_revenue: 'active',
          monthly_value: selectedTier.amount,
          ...metadata
        },
        payment_behavior: 'default_incomplete',
        payment_settings: { save_default_payment_method: 'on_subscription' },
        expand: ['latest_invoice.payment_intent']
      })

      // Calculate lifetime value projections
      const projectedLTV = selectedTier.amount * 12 * 3 // 3 year average
      const platformRevenue = projectedLTV * 0.05 // 5% platform cut

      logger.info(`Subscription created: ${subscription.id} - Tier: ${tier}`, {
        monthlyAmount: selectedTier.amount,
        projectedLTV: projectedLTV,
        platformRevenue: platformRevenue,
        tier: tier
      })

      return {
        subscriptionId: subscription.id,
        clientSecret: subscription.latest_invoice.payment_intent.client_secret,
        tier: tier,
        monthlyAmount: selectedTier.amount,
        projectedLTV: projectedLTV,
        features: selectedTier.features,
        status: subscription.status
      }
    } catch (error) {
      logger.error('Failed to create subscription:', error.message)
      throw error
    }
  }

  /**
   * Process affiliate commission payments (infinite revenue sharing)
   */
  async processAffiliateCommissions(affiliatePayouts) {
    try {
      const results = []
      let totalCommissionsPaid = 0

      for (const payout of affiliatePayouts) {
        try {
          // Create transfer to affiliate's connected account
          const transfer = await stripe.transfers.create({
            amount: Math.round(payout.amount * 100),
            currency: 'usd',
            destination: payout.stripeAccountId,
            metadata: {
              affiliate_id: payout.affiliateId,
              commission_rate: this.commission_rate,
              reference_sales: payout.referenceSales.join(','),
              boss_mans_cut: 'processed'
            }
          })

          totalCommissionsPaid += payout.amount

          results.push({
            affiliateId: payout.affiliateId,
            amount: payout.amount,
            transferId: transfer.id,
            status: 'success'
          })

          logger.info(`Commission paid: $${payout.amount} to affiliate ${payout.affiliateId}`)
        } catch (error) {
          logger.error(`Failed to pay commission to affiliate ${payout.affiliateId}:`, error.message)
          results.push({
            affiliateId: payout.affiliateId,
            amount: payout.amount,
            status: 'failed',
            error: error.message
          })
        }
      }

      logger.info(`Affiliate commission batch processed: ${results.length} payouts, $${totalCommissionsPaid} total`)

      return {
        processed: results.length,
        totalPaid: totalCommissionsPaid,
        results: results
      }
    } catch (error) {
      logger.error('Affiliate commission processing failed:', error.message)
      throw error
    }
  }

  /**
   * Create Boss Man's premium products for maximum revenue
   */
  async createPremiumProducts() {
    try {
      const products = [
        {
          name: 'Boss Man\'s AI Content Mastery Course',
          description: 'Learn how to generate infinite content that converts to infinite revenue',
          price: 497.00,
          type: 'course',
          features: ['50+ Video Modules', 'AI Prompt Templates', 'Revenue Blueprints', 'Direct Boss Man Access']
        },
        {
          name: 'YouTuneAi V2 White-Label License', 
          description: 'Complete white-label solution for agencies and entrepreneurs',
          price: 2997.00,
          type: 'software',
          features: ['Full Source Code', 'Branding Rights', 'Client Management', '90-Day Support']
        },
        {
          name: 'Infinite Revenue Accelerator Program',
          description: 'Boss Man\'s personal mentorship for building million-dollar AI businesses',
          price: 9997.00,
          type: 'coaching',
          features: ['12 Weeks Mentorship', 'Weekly Group Calls', 'Custom AI Setup', 'Revenue Guarantees']
        },
        {
          name: 'Boss Man\'s Inner Circle Mastermind',
          description: 'Exclusive access to Boss Man and top entrepreneurs building AI empires',
          price: 25000.00,
          type: 'mastermind',
          features: ['Monthly In-Person Events', 'Direct Boss Man Access', 'Joint Ventures', 'Profit Sharing Deals']
        }
      ]

      const createdProducts = []

      for (const product of products) {
        const stripeProduct = await stripe.products.create({
          name: product.name,
          description: product.description,
          metadata: {
            type: product.type,
            boss_mans_system: 'true',
            premium_tier: 'true',
            features: product.features.join('|')
          }
        })

        const price = await stripe.prices.create({
          unit_amount: Math.round(product.price * 100),
          currency: 'usd',
          product: stripeProduct.id
        })

        createdProducts.push({
          productId: stripeProduct.id,
          priceId: price.id,
          name: product.name,
          price: product.price,
          type: product.type,
          features: product.features
        })

        logger.info(`Premium product created: ${product.name} - $${product.price}`)
      }

      return createdProducts
    } catch (error) {
      logger.error('Failed to create premium products:', error.message)
      throw error
    }
  }

  /**
   * Revenue analytics and optimization insights
   */
  async getRevenueAnalytics(startDate, endDate) {
    try {
      // Get payment data
      const charges = await stripe.charges.list({
        created: {
          gte: Math.floor(startDate.getTime() / 1000),
          lte: Math.floor(endDate.getTime() / 1000)
        },
        limit: 100
      })

      // Get subscription data
      const subscriptions = await stripe.subscriptions.list({
        created: {
          gte: Math.floor(startDate.getTime() / 1000),
          lte: Math.floor(endDate.getTime() / 1000)
        },
        limit: 100
      })

      // Calculate key metrics
      const totalRevenue = charges.data.reduce((sum, charge) => sum + charge.amount, 0) / 100
      const subscriptionRevenue = subscriptions.data.reduce((sum, sub) => {
        if (sub.items && sub.items.data[0]) {
          return sum + (sub.items.data[0].price.unit_amount / 100)
        }
        return sum
      }, 0)

      const averageOrderValue = charges.data.length > 0 ? totalRevenue / charges.data.length : 0
      const monthlyRecurringRevenue = subscriptionRevenue
      const churnRate = this.calculateChurnRate(subscriptions.data)

      // Boss Man's infinite revenue metrics
      const infiniteRevenueMultiplier = monthlyRecurringRevenue * 12 * 3 // 3-year LTV
      const scalingPotential = totalRevenue * 2.5 // Conservative growth projection
      const bossMansROI = (totalRevenue / 10000) * 100 // ROI based on initial investment

      return {
        totalRevenue,
        subscriptionRevenue,
        averageOrderValue,
        monthlyRecurringRevenue,
        totalTransactions: charges.data.length,
        totalSubscriptions: subscriptions.data.length,
        churnRate,
        infiniteRevenueMultiplier,
        scalingPotential,
        bossMansROI,
        growthRate: this.calculateGrowthRate(charges.data),
        revenueOptimizationScore: this.calculateOptimizationScore({
          totalRevenue,
          subscriptionRevenue,
          averageOrderValue,
          churnRate
        })
      }
    } catch (error) {
      logger.error('Failed to get revenue analytics:', error.message)
      throw error
    }
  }

  /**
   * Calculate churn rate for subscriptions
   */
  calculateChurnRate(subscriptions) {
    const canceledSubs = subscriptions.filter(sub => sub.status === 'canceled').length
    const totalSubs = subscriptions.length
    return totalSubs > 0 ? (canceledSubs / totalSubs) * 100 : 0
  }

  /**
   * Calculate growth rate
   */
  calculateGrowthRate(charges) {
    // Simplified growth calculation - would be more complex in production
    if (charges.length < 2) return 0
    
    const recent = charges.slice(0, Math.floor(charges.length / 2))
    const older = charges.slice(Math.floor(charges.length / 2))
    
    const recentRevenue = recent.reduce((sum, charge) => sum + charge.amount, 0)
    const olderRevenue = older.reduce((sum, charge) => sum + charge.amount, 0)
    
    return olderRevenue > 0 ? ((recentRevenue - olderRevenue) / olderRevenue) * 100 : 0
  }

  /**
   * Calculate revenue optimization score (Boss Man's secret formula)
   */
  calculateOptimizationScore(metrics) {
    const {
      totalRevenue,
      subscriptionRevenue,
      averageOrderValue,
      churnRate
    } = metrics

    // Boss Man's proprietary optimization formula
    const subscriptionWeight = (subscriptionRevenue / (totalRevenue + 1)) * 40
    const aovWeight = Math.min(averageOrderValue / 100, 30) // Max 30 points
    const churnWeight = Math.max(30 - churnRate, 0) // Lower churn = higher score
    
    const score = subscriptionWeight + aovWeight + churnWeight
    return Math.min(Math.round(score), 100)
  }
}

module.exports = new StripePaymentProcessor()