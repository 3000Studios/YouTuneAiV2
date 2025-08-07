/**
 * AI Content Generation Engine
 * Infinite content creation for infinite revenue
 * Boss Man's content automation system
 */

const OpenAI = require('openai')
const Database = require('../../backend/database/Database')
const logger = require('../../backend/utils/logger')

class ContentGenerator {
  constructor() {
    this.openai = new OpenAI({
      apiKey: process.env.OPENAI_API_KEY
    })
    this.contentTemplates = {
      blog_post: {
        systemPrompt: "You are an expert blog writer creating SEO-optimized, engaging content that drives traffic and conversions for YouTuneAi V2 platform users.",
        structure: ["hook", "introduction", "main_content", "call_to_action"],
        targetLength: 1500
      },
      social_post: {
        systemPrompt: "You are a social media expert creating viral, engaging posts that drive clicks, shares, and conversions.",
        platforms: ["twitter", "facebook", "instagram", "linkedin"],
        targetLength: 280
      },
      video_script: {
        systemPrompt: "You are a YouTube script expert creating engaging video content that retains viewers and drives subscriptions.",
        structure: ["hook", "introduction", "content_sections", "conclusion", "call_to_action"],
        targetLength: 2000
      },
      email_campaign: {
        systemPrompt: "You are an email marketing expert creating compelling campaigns that achieve high open rates and conversions.",
        types: ["welcome", "nurture", "promotional", "abandoned_cart"],
        targetLength: 800
      },
      product_description: {
        systemPrompt: "You are a copywriting expert creating product descriptions that sell by focusing on benefits and urgency.",
        structure: ["headline", "benefits", "features", "social_proof", "urgency"],
        targetLength: 300
      },
      sales_page: {
        systemPrompt: "You are a sales page expert creating high-converting landing pages using proven psychological triggers.",
        structure: ["headline", "problem", "solution", "benefits", "social_proof", "pricing", "guarantee", "urgency"],
        targetLength: 3000
      }
    }
    this.seoKeywords = []
    this.brandVoice = "professional yet approachable, emphasizing results and transformation"
  }

  /**
   * Generate content based on type and parameters
   */
  async generateContent(contentType, parameters) {
    try {
      const {
        topic,
        targetAudience,
        keywords = [],
        tone = 'professional',
        platform = null,
        customInstructions = '',
        userId = null
      } = parameters

      if (!this.contentTemplates[contentType]) {
        throw new Error(`Unsupported content type: ${contentType}`)
      }

      const template = this.contentTemplates[contentType]
      const content = await this.createContent(contentType, template, parameters)

      // Store generated content
      const contentRecord = await this.storeContent(content, contentType, parameters, userId)

      // Generate SEO metadata
      const seoData = await this.generateSEOMetadata(content.content, keywords)

      logger.info(`Content generated: ${contentType} for topic "${topic}"${userId ? ` by user ${userId}` : ''}`)

      return {
        id: contentRecord.id,
        content: content.content,
        title: content.title,
        type: contentType,
        seo: seoData,
        wordCount: this.countWords(content.content),
        readingTime: this.calculateReadingTime(content.content),
        keywords: keywords,
        createdAt: new Date().toISOString()
      }
    } catch (error) {
      logger.error('Content generation failed:', error.message)
      throw error
    }
  }

  /**
   * Create content using AI
   */
  async createContent(contentType, template, parameters) {
    try {
      const {
        topic,
        targetAudience,
        keywords = [],
        tone = 'professional',
        platform = null,
        customInstructions = ''
      } = parameters

      const systemPrompt = `${template.systemPrompt}

BRAND: YouTuneAi V2 - Boss Man's infinite revenue AI platform
VOICE: ${this.brandVoice}
TARGET LENGTH: ~${template.targetLength} words

Key Platform Features to Highlight:
- AI content generation and automation
- Social media automation across all platforms
- Revenue optimization and affiliate marketing
- Multiple income streams (subscriptions, products, commissions)
- Advanced analytics and performance tracking
- Voice processing and AI assistants
- White-label solutions and enterprise features

Always focus on results, transformation, and revenue generation potential.`

      const userPrompt = `Create ${contentType.replace('_', ' ')} content about: ${topic}

Target Audience: ${targetAudience}
Tone: ${tone}
${platform ? `Platform: ${platform}` : ''}
${keywords.length > 0 ? `SEO Keywords to include: ${keywords.join(', ')}` : ''}
${customInstructions ? `Special Instructions: ${customInstructions}` : ''}

${template.structure ? `Structure to follow: ${template.structure.join(' → ')}` : ''}

Make it compelling, actionable, and focused on driving results for YouTuneAi V2 users.`

      const response = await this.openai.chat.completions.create({
        model: 'gpt-4-turbo-preview',
        messages: [
          { role: 'system', content: systemPrompt },
          { role: 'user', content: userPrompt }
        ],
        max_tokens: Math.max(template.targetLength * 1.5, 1000),
        temperature: 0.7,
        presence_penalty: 0.1,
        frequency_penalty: 0.1
      })

      const content = response.choices[0].message.content

      // Generate title if not included
      const title = await this.generateTitle(content, topic, contentType)

      return {
        content: content,
        title: title,
        tokensUsed: response.usage.total_tokens
      }
    } catch (error) {
      logger.error('AI content creation failed:', error.message)
      throw error
    }
  }

  /**
   * Generate compelling title for content
   */
  async generateTitle(content, topic, contentType) {
    try {
      const titlePrompts = {
        blog_post: "Create a compelling, SEO-optimized blog post title that promises value and drives clicks",
        social_post: "Create a hook-driven social media post title that stops the scroll and demands attention",
        video_script: "Create a YouTube video title that promises value, creates curiosity, and drives clicks",
        email_campaign: "Create an email subject line with high open rate potential that creates urgency or curiosity",
        product_description: "Create a product headline that emphasizes benefits and creates buying urgency",
        sales_page: "Create a sales page headline that identifies the problem and promises the solution"
      }

      const prompt = `${titlePrompts[contentType] || 'Create a compelling title'} for content about: ${topic}

Content preview: ${content.substring(0, 300)}...

Requirements:
- Maximum 60 characters for SEO optimization
- Include power words that drive action
- Create emotional connection or curiosity
- Focus on benefits and results
- Suitable for YouTuneAi V2 platform content

Generate 1 perfect title:`

      const response = await this.openai.chat.completions.create({
        model: 'gpt-4-turbo-preview',
        messages: [
          { role: 'user', content: prompt }
        ],
        max_tokens: 100,
        temperature: 0.8
      })

      return response.choices[0].message.content.replace(/^["']|["']$/g, '').trim()
    } catch (error) {
      logger.error('Title generation failed:', error.message)
      return `${topic} - ${contentType.replace('_', ' ')}`
    }
  }

  /**
   * Generate SEO metadata
   */
  async generateSEOMetadata(content, keywords) {
    try {
      const prompt = `Analyze this content and generate SEO metadata:

Content: ${content.substring(0, 1000)}...

Target Keywords: ${keywords.join(', ')}

Generate:
1. Meta description (150-160 characters)
2. SEO title (50-60 characters) 
3. Focus keyword
4. Related keywords (5-8 keywords)
5. Content category
6. SEO score estimate (1-100)

Format as JSON object.`

      const response = await this.openai.chat.completions.create({
        model: 'gpt-4-turbo-preview',
        messages: [
          { role: 'user', content: prompt }
        ],
        max_tokens: 500,
        temperature: 0.3
      })

      try {
        const seoData = JSON.parse(response.choices[0].message.content)
        return seoData
      } catch (parseError) {
        // Fallback SEO data
        return {
          metaDescription: content.substring(0, 155) + '...',
          seoTitle: keywords[0] || 'YouTuneAi V2 Content',
          focusKeyword: keywords[0] || 'ai content',
          relatedKeywords: keywords.slice(0, 8),
          category: 'general',
          seoScore: 75
        }
      }
    } catch (error) {
      logger.error('SEO metadata generation failed:', error.message)
      return {
        metaDescription: '',
        seoTitle: '',
        focusKeyword: '',
        relatedKeywords: [],
        category: 'general',
        seoScore: 0
      }
    }
  }

  /**
   * Generate content series/campaign
   */
  async generateContentSeries(seriesType, parameters) {
    try {
      const {
        mainTopic,
        numberOfPieces = 5,
        contentTypes = ['blog_post'],
        targetAudience,
        keywords = [],
        userId = null
      } = parameters

      const seriesPrompts = {
        course: "Create a comprehensive course series that takes users from beginner to advanced",
        nurture_sequence: "Create an email nurture sequence that builds trust and drives conversions",
        social_campaign: "Create a social media campaign that builds awareness and engagement",
        blog_series: "Create a blog post series that establishes thought leadership and drives traffic",
        video_series: "Create a video series that educates, entertains, and converts viewers"
      }

      // Generate series outline
      const outline = await this.generateSeriesOutline(seriesType, mainTopic, numberOfPieces, seriesPrompts[seriesType] || seriesPrompts.course)

      const generatedContent = []

      // Generate each piece of content
      for (let i = 0; i < outline.topics.length; i++) {
        const contentType = contentTypes[i % contentTypes.length]
        const topic = outline.topics[i]

        try {
          const content = await this.generateContent(contentType, {
            topic: topic.title,
            targetAudience,
            keywords: [...keywords, ...topic.keywords],
            customInstructions: `This is part ${i + 1} of ${numberOfPieces} in a series about ${mainTopic}. ${topic.description}`,
            userId
          })

          generatedContent.push({
            ...content,
            seriesPosition: i + 1,
            seriesTitle: topic.title,
            seriesDescription: topic.description
          })

          // Add delay to avoid rate limiting
          if (i < outline.topics.length - 1) {
            await new Promise(resolve => setTimeout(resolve, 2000))
          }
        } catch (error) {
          logger.error(`Failed to generate content piece ${i + 1}:`, error.message)
        }
      }

      logger.info(`Content series generated: ${seriesType} with ${generatedContent.length} pieces`)

      return {
        seriesType,
        mainTopic,
        totalPieces: generatedContent.length,
        content: generatedContent,
        outline: outline,
        createdAt: new Date().toISOString()
      }
    } catch (error) {
      logger.error('Content series generation failed:', error.message)
      throw error
    }
  }

  /**
   * Generate series outline
   */
  async generateSeriesOutline(seriesType, mainTopic, numberOfPieces, seriesPrompt) {
    try {
      const prompt = `${seriesPrompt}

Main Topic: ${mainTopic}
Number of Pieces: ${numberOfPieces}
Platform: YouTuneAi V2

Create a detailed outline with:
- Series title
- Series description 
- Individual topics with titles, descriptions, and relevant keywords
- Logical progression from basic to advanced concepts
- Each piece should build upon the previous ones

Focus on practical, actionable content that helps users generate revenue with AI.

Format as JSON object with: { seriesTitle, seriesDescription, topics: [{ title, description, keywords, learningObjectives }] }`

      const response = await this.openai.chat.completions.create({
        model: 'gpt-4-turbo-preview',
        messages: [
          { role: 'user', content: prompt }
        ],
        max_tokens: 1500,
        temperature: 0.6
      })

      try {
        return JSON.parse(response.choices[0].message.content)
      } catch (parseError) {
        // Fallback outline
        const topics = []
        for (let i = 1; i <= numberOfPieces; i++) {
          topics.push({
            title: `${mainTopic} - Part ${i}`,
            description: `Part ${i} of the ${mainTopic} series`,
            keywords: [mainTopic.toLowerCase().replace(/\s+/g, '-')],
            learningObjectives: [`Understand ${mainTopic} concept ${i}`]
          })
        }

        return {
          seriesTitle: `${mainTopic} Mastery Series`,
          seriesDescription: `Complete ${numberOfPieces}-part series on ${mainTopic}`,
          topics
        }
      }
    } catch (error) {
      logger.error('Series outline generation failed:', error.message)
      throw error
    }
  }

  /**
   * Store generated content in database
   */
  async storeContent(content, contentType, parameters, userId) {
    try {
      const result = await Database.query(`
        INSERT INTO content (
          user_id,
          title,
          content,
          content_type,
          keywords,
          ai_generated,
          metadata,
          status
        ) VALUES ($1, $2, $3, $4, $5, $6, $7, $8)
        RETURNING id, created_at
      `, [
        userId,
        content.title,
        content.content,
        contentType,
        parameters.keywords || [],
        true,
        JSON.stringify({
          topic: parameters.topic,
          targetAudience: parameters.targetAudience,
          tone: parameters.tone,
          platform: parameters.platform,
          tokensUsed: content.tokensUsed,
          generatedAt: new Date().toISOString()
        }),
        'draft'
      ])

      return result.rows[0]
    } catch (error) {
      logger.error('Failed to store content:', error.message)
      return { id: null, created_at: new Date().toISOString() }
    }
  }

  /**
   * Count words in content
   */
  countWords(content) {
    return content.trim().split(/\s+/).length
  }

  /**
   * Calculate reading time
   */
  calculateReadingTime(content) {
    const wordsPerMinute = 200
    const wordCount = this.countWords(content)
    return Math.ceil(wordCount / wordsPerMinute)
  }

  /**
   * Get content analytics
   */
  async getContentAnalytics(userId, startDate, endDate) {
    try {
      const result = await Database.query(`
        SELECT 
          content_type,
          COUNT(*) as total_pieces,
          AVG(LENGTH(content)) as avg_length,
          SUM(CASE WHEN status = 'published' THEN 1 ELSE 0 END) as published_count,
          SUM(CAST(performance_metrics->>'views' AS INTEGER)) as total_views,
          SUM(CAST(performance_metrics->>'engagement' AS INTEGER)) as total_engagement
        FROM content 
        WHERE user_id = $1 
        AND ai_generated = true
        AND created_at BETWEEN $2 AND $3
        GROUP BY content_type
        ORDER BY total_pieces DESC
      `, [userId, startDate, endDate])

      const totalContent = result.rows.reduce((sum, row) => sum + parseInt(row.total_pieces), 0)
      const totalViews = result.rows.reduce((sum, row) => sum + (parseInt(row.total_views) || 0), 0)
      const totalEngagement = result.rows.reduce((sum, row) => sum + (parseInt(row.total_engagement) || 0), 0)

      return {
        totalContent,
        totalViews,
        totalEngagement,
        averageEngagementRate: totalViews > 0 ? (totalEngagement / totalViews * 100).toFixed(2) : 0,
        contentByType: result.rows.map(row => ({
          type: row.content_type,
          count: parseInt(row.total_pieces),
          avgLength: parseInt(row.avg_length),
          publishedCount: parseInt(row.published_count),
          views: parseInt(row.total_views) || 0,
          engagement: parseInt(row.total_engagement) || 0
        }))
      }
    } catch (error) {
      logger.error('Failed to get content analytics:', error.message)
      return {
        totalContent: 0,
        totalViews: 0,
        totalEngagement: 0,
        averageEngagementRate: 0,
        contentByType: []
      }
    }
  }
}

module.exports = new ContentGenerator()