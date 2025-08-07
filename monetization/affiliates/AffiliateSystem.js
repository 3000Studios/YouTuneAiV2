/**
 * Affiliate Marketing System
 * Boss Man's infinite revenue affiliate network
 */

const Database = require('../../backend/database/Database')
const StripeProcessor = require('../stripe/StripeProcessor')
const logger = require('../../backend/utils/logger')

class AffiliateSystem {
  constructor() {
    this.defaultCommissionRate = 0.15 // 15% default commission
    this.tiers = {
      bronze: { minSales: 0, commissionRate: 0.15, bonusThreshold: 1000 },
      silver: { minSales: 5000, commissionRate: 0.20, bonusThreshold: 5000 },
      gold: { minSales: 25000, commissionRate: 0.25, bonusThreshold: 10000 },
      platinum: { minSales: 100000, commissionRate: 0.30, bonusThreshold: 25000 },
      diamond: { minSales: 500000, commissionRate: 0.35, bonusThreshold: 50000 },
      bossmans_inner_circle: { minSales: 1000000, commissionRate: 0.40, bonusThreshold: 100000 }
    }
  }

  /**
   * Create new affiliate account
   */
  async createAffiliate(userId, referralSource = null) {
    try {
      const affiliateCode = this.generateAffiliateCode(userId)
      
      const result = await Database.query(`
        INSERT INTO affiliates (
          user_id, 
          affiliate_code, 
          commission_rate, 
          status,
          metadata
        ) VALUES ($1, $2, $3, $4, $5)
        RETURNING *
      `, [
        userId,
        affiliateCode,
        this.defaultCommissionRate,
        'active',
        JSON.stringify({
          tier: 'bronze',
          joined_date: new Date().toISOString(),
          referral_source: referralSource,
          boss_mans_network: true
        })
      ])

      const affiliate = result.rows[0]

      logger.info(`New affiliate created: ${affiliateCode} for user ${userId}`)

      return {
        id: affiliate.id,
        userId: affiliate.user_id,
        affiliateCode: affiliate.affiliate_code,
        commissionRate: affiliate.commission_rate,
        tier: 'bronze',
        trackingUrl: `https://youtuneai.com/ref/${affiliateCode}`,
        dashboardUrl: `https://youtuneai.com/affiliate/dashboard`,
        status: affiliate.status
      }
    } catch (error) {
      logger.error('Failed to create affiliate:', error.message)
      throw error
    }
  }

  /**
   * Generate unique affiliate code
   */
  generateAffiliateCode(userId) {
    const prefix = 'YT2'
    const timestamp = Date.now().toString().slice(-6)
    const userHash = userId.toString().padStart(4, '0')
    return `${prefix}${userHash}${timestamp}`.toUpperCase()
  }

  /**
   * Track affiliate referral and conversion
   */
  async trackReferral(affiliateCode, visitorData) {
    try {
      const affiliate = await this.getAffiliateByCode(affiliateCode)
      if (!affiliate) {
        logger.warn(`Invalid affiliate code: ${affiliateCode}`)
        return null
      }

      // Store referral tracking
      const trackingData = {
        affiliate_id: affiliate.id,
        visitor_ip: visitorData.ip,
        user_agent: visitorData.userAgent,
        referrer: visitorData.referrer,
        landing_page: visitorData.landingPage,
        timestamp: new Date().toISOString(),
        session_id: visitorData.sessionId,
        utm_source: visitorData.utmSource,
        utm_campaign: visitorData.utmCampaign
      }

      await Database.query(`
        INSERT INTO analytics (
          user_id,
          event_type,
          event_data,
          platform,
          timestamp,
          session_id,
          ip_address
        ) VALUES ($1, $2, $3, $4, $5, $6, $7)
      `, [
        affiliate.user_id,
        'affiliate_referral',
        JSON.stringify(trackingData),
        'youtuneai-affiliate',
        new Date(),
        visitorData.sessionId,
        visitorData.ip
      ])

      logger.info(`Affiliate referral tracked: ${affiliateCode}`)

      return {
        affiliateId: affiliate.id,
        trackingId: trackingData.session_id,
        commissionRate: affiliate.commission_rate
      }
    } catch (error) {
      logger.error('Failed to track referral:', error.message)
      return null
    }
  }

  /**
   * Process affiliate commission when sale is made
   */
  async processCommission(saleData) {
    try {
      const {
        affiliateCode,
        saleAmount,
        customerId,
        productType,
        subscriptionId = null,
        paymentIntentId = null
      } = saleData

      const affiliate = await this.getAffiliateByCode(affiliateCode)
      if (!affiliate) {
        logger.warn(`Commission processing failed - invalid affiliate: ${affiliateCode}`)
        return null
      }

      // Calculate commission based on affiliate tier
      const tierInfo = this.getTierInfo(affiliate.metadata)
      const commissionRate = tierInfo.commissionRate
      const commissionAmount = saleAmount * commissionRate

      // Store commission record
      const commissionResult = await Database.query(`
        INSERT INTO affiliate_commissions (
          affiliate_id,
          sale_amount,
          commission_rate,
          commission_amount,
          customer_id,
          product_type,
          subscription_id,
          payment_intent_id,
          status,
          metadata
        ) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10)
        RETURNING *
      `, [
        affiliate.id,
        saleAmount,
        commissionRate,
        commissionAmount,
        customerId,
        productType,
        subscriptionId,
        paymentIntentId,
        'pending',
        JSON.stringify({
          processed_date: new Date().toISOString(),
          tier_at_sale: tierInfo.tier,
          boss_mans_cut: saleAmount * 0.05 // Boss Man always gets 5%
        })
      ])

      // Update affiliate stats
      await this.updateAffiliateStats(affiliate.id, saleAmount, commissionAmount)

      // Check for tier upgrade
      await this.checkTierUpgrade(affiliate.id)

      // Schedule payment (would normally be processed in batches)
      await this.scheduleCommissionPayment(commissionResult.rows[0])

      logger.info(`Commission processed: $${commissionAmount} for affiliate ${affiliateCode} on $${saleAmount} sale`)

      return {
        commissionId: commissionResult.rows[0].id,
        affiliateId: affiliate.id,
        commissionAmount: commissionAmount,
        commissionRate: commissionRate,
        status: 'pending',
        estimatedPayoutDate: this.getNextPayoutDate()
      }
    } catch (error) {
      logger.error('Failed to process commission:', error.message)
      throw error
    }
  }

  /**
   * Get affiliate by code
   */
  async getAffiliateByCode(affiliateCode) {
    try {
      const result = await Database.query(
        'SELECT * FROM affiliates WHERE affiliate_code = $1 AND status = $2',
        [affiliateCode, 'active']
      )
      
      return result.rows[0] || null
    } catch (error) {
      logger.error('Failed to get affiliate by code:', error.message)
      return null
    }
  }

  /**
   * Update affiliate statistics
   */
  async updateAffiliateStats(affiliateId, saleAmount, commissionAmount) {
    try {
      await Database.query(`
        UPDATE affiliates 
        SET 
          total_earnings = total_earnings + $1,
          total_referrals = total_referrals + 1,
          total_sales = COALESCE(total_sales, 0) + $2,
          updated_at = CURRENT_TIMESTAMP
        WHERE id = $3
      `, [commissionAmount, saleAmount, affiliateId])

      logger.debug(`Updated affiliate stats: ID ${affiliateId}`)
    } catch (error) {
      logger.error('Failed to update affiliate stats:', error.message)
    }
  }

  /**
   * Check and upgrade affiliate tier based on performance
   */
  async checkTierUpgrade(affiliateId) {
    try {
      const affiliate = await Database.query(
        'SELECT * FROM affiliates WHERE id = $1',
        [affiliateId]
      )

      if (affiliate.rows.length === 0) return

      const affiliateData = affiliate.rows[0]
      const totalSales = affiliateData.total_sales || 0
      const currentTier = affiliateData.metadata?.tier || 'bronze'

      // Determine new tier
      let newTier = 'bronze'
      for (const [tierName, tierData] of Object.entries(this.tiers)) {
        if (totalSales >= tierData.minSales) {
          newTier = tierName
        }
      }

      // Update if tier changed
      if (newTier !== currentTier) {
        const newTierInfo = this.tiers[newTier]
        const updatedMetadata = {
          ...affiliateData.metadata,
          tier: newTier,
          tier_upgrade_date: new Date().toISOString(),
          previous_tier: currentTier
        }

        await Database.query(`
          UPDATE affiliates 
          SET 
            commission_rate = $1,
            metadata = $2,
            updated_at = CURRENT_TIMESTAMP
          WHERE id = $3
        `, [newTierInfo.commissionRate, JSON.stringify(updatedMetadata), affiliateId])

        logger.info(`Affiliate ${affiliateId} upgraded from ${currentTier} to ${newTier} tier`)

        // Send tier upgrade notification (would integrate with email service)
        await this.notifyTierUpgrade(affiliateId, newTier, newTierInfo)
      }
    } catch (error) {
      logger.error('Failed to check tier upgrade:', error.message)
    }
  }

  /**
   * Get tier information
   */
  getTierInfo(metadata) {
    const tier = metadata?.tier || 'bronze'
    return {
      tier,
      ...this.tiers[tier]
    }
  }

  /**
   * Schedule commission payment
   */
  async scheduleCommissionPayment(commission) {
    try {
      // In production, this would add to a payment queue
      logger.info(`Commission payment scheduled: ID ${commission.id} - Amount: $${commission.commission_amount}`)
      
      // For demo purposes, mark as scheduled
      await Database.query(
        'UPDATE affiliate_commissions SET status = $1 WHERE id = $2',
        ['scheduled', commission.id]
      )
    } catch (error) {
      logger.error('Failed to schedule commission payment:', error.message)
    }
  }

  /**
   * Process batch commission payments
   */
  async processBatchPayments() {
    try {
      // Get all scheduled commissions
      const result = await Database.query(`
        SELECT 
          ac.*,
          a.user_id,
          a.affiliate_code,
          u.email,
          u.name
        FROM affiliate_commissions ac
        JOIN affiliates a ON ac.affiliate_id = a.id
        JOIN users u ON a.user_id = u.id
        WHERE ac.status = 'scheduled'
        AND ac.created_at <= NOW() - INTERVAL '24 hours'
        ORDER BY ac.created_at ASC
        LIMIT 100
      `)

      const commissions = result.rows
      if (commissions.length === 0) {
        logger.info('No commissions ready for payment')
        return { processed: 0, total: 0 }
      }

      // Group by affiliate for batch processing
      const groupedCommissions = commissions.reduce((groups, commission) => {
        const affiliateId = commission.affiliate_id
        if (!groups[affiliateId]) {
          groups[affiliateId] = []
        }
        groups[affiliateId].push(commission)
        return groups
      }, {})

      let totalProcessed = 0
      let totalAmount = 0

      for (const [affiliateId, affiliateCommissions] of Object.entries(groupedCommissions)) {
        try {
          const totalCommission = affiliateCommissions.reduce((sum, c) => sum + parseFloat(c.commission_amount), 0)
          
          // Process payment via Stripe (would need affiliate's connected account)
          // For now, mark as paid
          const commissionIds = affiliateCommissions.map(c => c.id)
          
          await Database.query(
            `UPDATE affiliate_commissions 
             SET status = 'paid', paid_at = CURRENT_TIMESTAMP 
             WHERE id = ANY($1)`,
            [commissionIds]
          )

          totalProcessed += affiliateCommissions.length
          totalAmount += totalCommission

          logger.info(`Paid $${totalCommission} to affiliate ${affiliateId} (${affiliateCommissions.length} commissions)`)
        } catch (error) {
          logger.error(`Failed to pay affiliate ${affiliateId}:`, error.message)
        }
      }

      logger.info(`Batch payment processing complete: ${totalProcessed} commissions, $${totalAmount} total`)

      return {
        processed: totalProcessed,
        total: totalAmount,
        affiliates: Object.keys(groupedCommissions).length
      }
    } catch (error) {
      logger.error('Failed to process batch payments:', error.message)
      throw error
    }
  }

  /**
   * Get affiliate dashboard data
   */
  async getAffiliateDashboard(userId) {
    try {
      const affiliate = await Database.query(
        'SELECT * FROM affiliates WHERE user_id = $1',
        [userId]
      )

      if (affiliate.rows.length === 0) {
        return null
      }

      const affiliateData = affiliate.rows[0]

      // Get commission statistics
      const commissionStats = await Database.query(`
        SELECT 
          COUNT(*) as total_commissions,
          SUM(commission_amount) as total_earned,
          SUM(CASE WHEN status = 'paid' THEN commission_amount ELSE 0 END) as total_paid,
          SUM(CASE WHEN status IN ('pending', 'scheduled') THEN commission_amount ELSE 0 END) as pending_earnings
        FROM affiliate_commissions 
        WHERE affiliate_id = $1
      `, [affiliateData.id])

      const stats = commissionStats.rows[0]
      const tierInfo = this.getTierInfo(affiliateData.metadata)

      return {
        affiliateCode: affiliateData.affiliate_code,
        commissionRate: affiliateData.commission_rate,
        tier: tierInfo.tier,
        nextTier: this.getNextTier(tierInfo.tier),
        trackingUrl: `https://youtuneai.com/ref/${affiliateData.affiliate_code}`,
        stats: {
          totalCommissions: parseInt(stats.total_commissions) || 0,
          totalEarned: parseFloat(stats.total_earned) || 0,
          totalPaid: parseFloat(stats.total_paid) || 0,
          pendingEarnings: parseFloat(stats.pending_earnings) || 0,
          totalReferrals: affiliateData.total_referrals || 0,
          totalSales: affiliateData.total_sales || 0
        },
        tierProgression: {
          current: tierInfo.tier,
          currentSales: affiliateData.total_sales || 0,
          nextTierRequirement: this.getNextTierRequirement(tierInfo.tier),
          progressPercentage: this.calculateTierProgress(affiliateData.total_sales || 0, tierInfo.tier)
        }
      }
    } catch (error) {
      logger.error('Failed to get affiliate dashboard:', error.message)
      throw error
    }
  }

  /**
   * Get next tier name
   */
  getNextTier(currentTier) {
    const tiers = Object.keys(this.tiers)
    const currentIndex = tiers.indexOf(currentTier)
    return currentIndex < tiers.length - 1 ? tiers[currentIndex + 1] : null
  }

  /**
   * Get next tier requirement
   */
  getNextTierRequirement(currentTier) {
    const nextTier = this.getNextTier(currentTier)
    return nextTier ? this.tiers[nextTier].minSales : null
  }

  /**
   * Calculate tier progress percentage
   */
  calculateTierProgress(currentSales, currentTier) {
    const nextTierRequirement = this.getNextTierRequirement(currentTier)
    if (!nextTierRequirement) return 100
    
    const currentTierRequirement = this.tiers[currentTier].minSales
    const progress = (currentSales - currentTierRequirement) / (nextTierRequirement - currentTierRequirement)
    return Math.min(Math.max(progress * 100, 0), 100)
  }

  /**
   * Get next payout date
   */
  getNextPayoutDate() {
    const now = new Date()
    const nextMonth = new Date(now.getFullYear(), now.getMonth() + 1, 1)
    return nextMonth.toISOString()
  }

  /**
   * Notify tier upgrade
   */
  async notifyTierUpgrade(affiliateId, newTier, tierInfo) {
    // Would integrate with email service
    logger.info(`Tier upgrade notification for affiliate ${affiliateId}: ${newTier} tier (${tierInfo.commissionRate * 100}% commission)`)
  }
}

module.exports = new AffiliateSystem()