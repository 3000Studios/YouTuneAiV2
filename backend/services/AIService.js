/**
 * AI Service - OpenAI and Multi-Provider Integration
 * Real production AI services for content generation, voice processing, and automation
 * Infinite content creation for infinite revenue
 */

const OpenAI = require('openai');
const logger = require('../utils/logger');

class AIService {
  constructor() {
    this.openai = null;
    this.isInitialized = false;
    this.models = {
      chat: 'gpt-4-turbo-preview',
      content: 'gpt-4-turbo-preview',
      image: 'dall-e-3',
      audio: 'whisper-1',
      tts: 'tts-1-hd'
    };
  }

  /**
   * Initialize AI services
   */
  async initialize() {
    try {
      if (!process.env.OPENAI_API_KEY) {
        throw new Error('OpenAI API key not configured');
      }

      this.openai = new OpenAI({
        apiKey: process.env.OPENAI_API_KEY,
        organization: process.env.OPENAI_ORG_ID
      });

      // Test API connection
      await this.validateConnection();
      
      this.isInitialized = true;
      logger.info('AI service initialized successfully with OpenAI GPT-4');
    } catch (error) {
      logger.error('Failed to initialize AI service:', error.message);
      throw error;
    }
  }

  /**
   * Validate OpenAI API connection
   */
  async validateConnection() {
    try {
      const response = await this.openai.chat.completions.create({
        model: 'gpt-3.5-turbo',
        messages: [{ role: 'user', content: 'Hello' }],
        max_tokens: 10
      });
      
      return response.choices.length > 0;
    } catch (error) {
      logger.error('OpenAI API validation failed:', error.message);
      throw error;
    }
  }

  /**
   * Generate content using AI
   */
  async generateContent(prompt, contentType = 'article', options = {}) {
    try {
      if (!this.isInitialized) {
        throw new Error('AI service not initialized');
      }

      const systemPrompts = {
        article: "You are an expert content writer creating engaging, SEO-optimized articles for YouTuneAi platform. Create high-quality, informative content that drives engagement and revenue.",
        social: "You are a social media expert creating viral content for YouTuneAi. Create engaging posts that drive clicks, shares, and conversions.",
        email: "You are an email marketing expert creating compelling email content for YouTuneAi subscribers. Focus on value, engagement, and conversion.",
        product: "You are a product description expert creating compelling copy that sells. Focus on benefits, features, and urgency.",
        video: "You are a video script expert creating engaging YouTube video scripts for YouTuneAi. Focus on hooks, value delivery, and calls to action."
      };

      const messages = [
        {
          role: 'system',
          content: systemPrompts[contentType] || systemPrompts.article
        },
        {
          role: 'user',
          content: prompt
        }
      ];

      const response = await this.openai.chat.completions.create({
        model: this.models.content,
        messages: messages,
        max_tokens: options.maxTokens || 2000,
        temperature: options.temperature || 0.7,
        presence_penalty: options.presencePenalty || 0.1,
        frequency_penalty: options.frequencyPenalty || 0.1
      });

      const generatedContent = response.choices[0].message.content;

      logger.info(`AI content generated: ${contentType} - ${generatedContent.length} characters`);

      return {
        content: generatedContent,
        type: contentType,
        tokens: response.usage.total_tokens,
        model: this.models.content,
        createdAt: new Date().toISOString()
      };
    } catch (error) {
      logger.error('Failed to generate content:', error.message);
      throw new Error('Content generation failed');
    }
  }

  /**
   * Generate social media content for multiple platforms
   */
  async generateSocialMediaContent(topic, platforms = ['twitter', 'facebook', 'instagram', 'linkedin']) {
    try {
      const results = {};

      for (const platform of platforms) {
        const platformPrompts = {
          twitter: `Create a compelling Twitter post about "${topic}" that drives engagement and includes relevant hashtags. Keep it under 280 characters.`,
          facebook: `Create an engaging Facebook post about "${topic}" that encourages shares and comments. Include a call to action.`,
          instagram: `Create an Instagram post about "${topic}" with engaging caption and relevant hashtags. Focus on visual appeal and engagement.`,
          linkedin: `Create a professional LinkedIn post about "${topic}" that provides value and encourages professional engagement.`
        };

        const content = await this.generateContent(
          platformPrompts[platform],
          'social',
          { maxTokens: 300, temperature: 0.8 }
        );

        results[platform] = content.content;
      }

      logger.info(`Social media content generated for ${platforms.length} platforms`);
      return results;
    } catch (error) {
      logger.error('Failed to generate social media content:', error.message);
      return {};
    }
  }

  /**
   * Generate AI images
   */
  async generateImage(prompt, options = {}) {
    try {
      if (!this.isInitialized) {
        throw new Error('AI service not initialized');
      }

      const response = await this.openai.images.generate({
        model: this.models.image,
        prompt: `${prompt} - Professional, high-quality, modern style for YouTuneAi platform`,
        size: options.size || '1024x1024',
        quality: options.quality || 'hd',
        n: options.count || 1
      });

      logger.info(`AI image generated: ${prompt}`);

      return {
        images: response.data.map(img => ({
          url: img.url,
          revisedPrompt: img.revised_prompt
        })),
        prompt: prompt,
        createdAt: new Date().toISOString()
      };
    } catch (error) {
      logger.error('Failed to generate image:', error.message);
      throw new Error('Image generation failed');
    }
  }

  /**
   * Convert text to speech
   */
  async textToSpeech(text, voice = 'nova', options = {}) {
    try {
      if (!this.isInitialized) {
        throw new Error('AI service not initialized');
      }

      const response = await this.openai.audio.speech.create({
        model: this.models.tts,
        voice: voice, // alloy, echo, fable, onyx, nova, shimmer
        input: text,
        response_format: options.format || 'mp3',
        speed: options.speed || 1.0
      });

      logger.info(`Text-to-speech generated: ${text.length} characters`);

      return {
        audio: response,
        text: text,
        voice: voice,
        format: options.format || 'mp3',
        createdAt: new Date().toISOString()
      };
    } catch (error) {
      logger.error('Failed to generate speech:', error.message);
      throw new Error('Text-to-speech failed');
    }
  }

  /**
   * Transcribe audio to text
   */
  async transcribeAudio(audioBuffer, options = {}) {
    try {
      if (!this.isInitialized) {
        throw new Error('AI service not initialized');
      }

      const response = await this.openai.audio.transcriptions.create({
        file: audioBuffer,
        model: this.models.audio,
        language: options.language || 'en',
        response_format: options.format || 'text'
      });

      logger.info('Audio transcription completed');

      return {
        text: response.text || response,
        language: options.language || 'en',
        createdAt: new Date().toISOString()
      };
    } catch (error) {
      logger.error('Failed to transcribe audio:', error.message);
      throw new Error('Audio transcription failed');
    }
  }

  /**
   * Generate SEO-optimized content
   */
  async generateSEOContent(keyword, contentType = 'article', targetLength = 1000) {
    try {
      const seoPrompt = `
        Create SEO-optimized ${contentType} content for the keyword "${keyword}".
        
        Requirements:
        - Target length: ${targetLength} words
        - Include the keyword naturally throughout the content
        - Use header tags (H2, H3) for structure
        - Include related keywords and semantic variations
        - Write for both users and search engines
        - Include a compelling introduction and conclusion
        - Focus on providing real value to readers
        - Make it engaging and shareable
        
        Topic: ${keyword}
      `;

      const content = await this.generateContent(seoPrompt, contentType, {
        maxTokens: Math.max(targetLength * 1.5, 2000),
        temperature: 0.6
      });

      // Generate meta description
      const metaPrompt = `Create a compelling meta description (under 160 characters) for content about "${keyword}" that encourages clicks from search results.`;
      
      const metaDescription = await this.generateContent(metaPrompt, 'article', {
        maxTokens: 100,
        temperature: 0.7
      });

      logger.info(`SEO content generated for keyword: ${keyword}`);

      return {
        content: content.content,
        metaDescription: metaDescription.content,
        keyword: keyword,
        targetLength: targetLength,
        actualLength: content.content.split(' ').length,
        createdAt: new Date().toISOString()
      };
    } catch (error) {
      logger.error('Failed to generate SEO content:', error.message);
      throw new Error('SEO content generation failed');
    }
  }

  /**
   * Generate video script
   */
  async generateVideoScript(topic, duration = '10-15 minutes', style = 'educational') {
    try {
      const scriptPrompt = `
        Create a compelling YouTube video script for "${topic}".
        
        Requirements:
        - Duration: ${duration}
        - Style: ${style}
        - Include strong hook in first 15 seconds
        - Clear structure with introduction, main content, and conclusion
        - Include engagement points (questions, calls to action)
        - Add suggested timestamps for editing
        - Include call-to-action for YouTuneAi platform
        - Make it valuable and engaging throughout
        
        Format the script with:
        - [HOOK] - Opening hook
        - [INTRO] - Introduction
        - [MAIN CONTENT] - Core content with sections
        - [CONCLUSION] - Wrap up and CTA
        - [END SCREEN] - End screen suggestions
      `;

      const script = await this.generateContent(scriptPrompt, 'video', {
        maxTokens: 3000,
        temperature: 0.7
      });

      logger.info(`Video script generated for topic: ${topic}`);

      return {
        script: script.content,
        topic: topic,
        duration: duration,
        style: style,
        createdAt: new Date().toISOString()
      };
    } catch (error) {
      logger.error('Failed to generate video script:', error.message);
      throw new Error('Video script generation failed');
    }
  }

  /**
   * Analyze content performance and optimize
   */
  async analyzeAndOptimizeContent(content, metrics = {}) {
    try {
      const analysisPrompt = `
        Analyze this content and provide optimization recommendations:
        
        Content: "${content.substring(0, 1000)}..."
        
        Current metrics:
        - Views: ${metrics.views || 0}
        - Engagement rate: ${metrics.engagement || 0}%
        - Click-through rate: ${metrics.ctr || 0}%
        - Time on page: ${metrics.timeOnPage || 0} seconds
        
        Provide specific recommendations to improve:
        1. Engagement
        2. SEO performance
        3. Conversion rate
        4. Shareability
        
        Format your response with clear sections and actionable advice.
      `;

      const analysis = await this.generateContent(analysisPrompt, 'article', {
        maxTokens: 1000,
        temperature: 0.6
      });

      logger.info('Content analysis and optimization completed');

      return {
        originalContent: content.substring(0, 200) + '...',
        analysis: analysis.content,
        metrics: metrics,
        createdAt: new Date().toISOString()
      };
    } catch (error) {
      logger.error('Failed to analyze content:', error.message);
      throw new Error('Content analysis failed');
    }
  }

  /**
   * Get AI service statistics
   */
  getStats() {
    return {
      isInitialized: this.isInitialized,
      models: this.models,
      provider: 'OpenAI',
      features: [
        'Content Generation',
        'Image Generation', 
        'Text-to-Speech',
        'Speech-to-Text',
        'SEO Optimization',
        'Video Scripts',
        'Social Media Content',
        'Content Analysis'
      ],
      status: this.isInitialized ? 'operational' : 'offline'
    };
  }
}

module.exports = new AIService();