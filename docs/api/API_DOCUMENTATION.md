# YouTubeAiV2 API Documentation

Boss Man's Complete API Reference for Infinite Revenue Generation

## Base URL
- **Development**: `http://localhost:3001/api/v1`
- **Production**: `https://youtuneai.com/api/v1`

## Authentication

All API requests require authentication via JWT tokens:

```bash
Authorization: Bearer YOUR_JWT_TOKEN
```

### Get Access Token

**POST** `/auth/login`

```json
{
  "email": "user@example.com",
  "password": "securepassword"
}
```

**Response**:
```json
{
  "message": "Login successful",
  "user": {
    "id": 1,
    "email": "user@example.com",
    "name": "John Doe",
    "role": "user",
    "subscriptionTier": "pro"
  },
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
  "expiresAt": "2024-01-15T12:00:00.000Z"
}
```

## User Management

### Register New User

**POST** `/auth/register`

```json
{
  "email": "user@example.com",
  "password": "securepassword123!",
  "name": "John Doe"
}
```

### Get User Profile

**GET** `/users/profile`

**Response**:
```json
{
  "id": 1,
  "email": "user@example.com",
  "name": "John Doe",
  "role": "user",
  "subscriptionTier": "pro",
  "createdAt": "2024-01-01T12:00:00.000Z",
  "stats": {
    "contentGenerated": 150,
    "revenueGenerated": 5420.50,
    "socialMediaPosts": 450
  }
}
```

## AI Content Generation

### Generate Content

**POST** `/ai/generate`

```json
{
  "contentType": "blog_post",
  "topic": "AI-powered social media automation",
  "targetAudience": "small business owners",
  "keywords": ["AI", "social media", "automation", "business"],
  "tone": "professional",
  "customInstructions": "Focus on ROI and practical implementation"
}
```

**Response**:
```json
{
  "id": 123,
  "content": "# AI-Powered Social Media Automation: Your Path to Infinite Revenue...",
  "title": "AI-Powered Social Media Automation: Transform Your Business in 2024",
  "type": "blog_post",
  "seo": {
    "metaDescription": "Discover how AI-powered social media automation...",
    "seoTitle": "AI Social Media Automation for Business Growth",
    "focusKeyword": "AI social media automation",
    "seoScore": 85
  },
  "wordCount": 1547,
  "readingTime": 8,
  "createdAt": "2024-01-15T10:30:00.000Z"
}
```

### Generate Content Series

**POST** `/ai/generate-series`

```json
{
  "seriesType": "course",
  "mainTopic": "Building Profitable AI Businesses",
  "numberOfPieces": 7,
  "contentTypes": ["blog_post", "video_script"],
  "targetAudience": "entrepreneurs"
}
```

### Voice Processing

**POST** `/ai/voice-process`

Content-Type: `multipart/form-data`

```
audio: [audio file]
userId: 123
```

**Response**:
```json
{
  "success": true,
  "transcription": "Hello, show me my revenue dashboard",
  "response": "Hello! Your current revenue is $5,420 this month with a 23% increase from last month. Your top performing content generated $1,200 in affiliate commissions!",
  "audioResponse": "[base64 encoded audio response]",
  "confidence": 0.92,
  "processingTime": 1250
}
```

## Payment Processing

### Create Payment Intent

**POST** `/payments/create-intent`

```json
{
  "amount": 29.99,
  "currency": "usd",
  "metadata": {
    "productType": "subscription",
    "tier": "starter"
  }
}
```

**Response**:
```json
{
  "paymentIntentId": "pi_1234567890",
  "clientSecret": "pi_1234567890_secret_abcdef",
  "amount": 29.99,
  "platformFee": 1.50,
  "netRevenue": 28.49
}
```

### Create Subscription

**POST** `/subscriptions/create`

```json
{
  "tier": "pro",
  "paymentMethodId": "pm_1234567890"
}
```

### Get Payment Statistics

**GET** `/payments/stats`

Query Parameters:
- `startDate`: ISO date string
- `endDate`: ISO date string

**Response**:
```json
{
  "totalRevenue": 15420.50,
  "totalTransactions": 342,
  "totalSubscriptions": 89,
  "monthlyRecurringRevenue": 4250.00,
  "averageOrderValue": 45.12,
  "infiniteRevenueMultiplier": 153000.00,
  "bossMansROI": 154.21
}
```

## Social Media Automation

### Create Social Campaign

**POST** `/social/create-campaign`

```json
{
  "topic": "AI Content Marketing Strategies",
  "targetAudience": "digital marketers",
  "platforms": ["twitter", "facebook", "linkedin"],
  "duration": 30,
  "postsPerDay": 3,
  "contentMix": "balanced"
}
```

**Response**:
```json
{
  "campaignId": 456,
  "topic": "AI Content Marketing Strategies",
  "platforms": ["twitter", "facebook", "linkedin"],
  "duration": 30,
  "totalPosts": 90,
  "estimatedReach": 45000,
  "estimatedEngagement": 0.03,
  "createdAt": "2024-01-15T10:30:00.000Z"
}
```

### Auto-Post Content

**POST** `/social/auto-post`

```json
{
  "content": "🚀 Ready to transform your content strategy with AI? YouTubeAiV2 makes it effortless! #AIContent #InfiniteRevenue #BossMan",
  "platform": "twitter",
  "scheduledTime": "2024-01-15T14:00:00.000Z"
}
```

## Affiliate Marketing

### Get Affiliate Dashboard

**GET** `/affiliates/dashboard`

**Response**:
```json
{
  "affiliateCode": "YT200012024",
  "commissionRate": 0.25,
  "tier": "gold",
  "nextTier": "platinum",
  "trackingUrl": "https://youtuneai.com/ref/YT200012024",
  "stats": {
    "totalCommissions": 45,
    "totalEarned": 2340.50,
    "totalPaid": 1890.25,
    "pendingEarnings": 450.25,
    "totalReferrals": 78,
    "totalSales": 15620.00
  },
  "tierProgression": {
    "current": "gold",
    "currentSales": 15620.00,
    "nextTierRequirement": 25000.00,
    "progressPercentage": 62.48
  }
}
```

### Track Referral

**POST** `/affiliates/track-referral`

```json
{
  "affiliateCode": "YT200012024",
  "visitorData": {
    "ip": "192.168.1.1",
    "userAgent": "Mozilla/5.0...",
    "referrer": "https://google.com",
    "landingPage": "/pricing",
    "sessionId": "sess_123456"
  }
}
```

## Analytics & Reporting

### Get Dashboard Analytics

**GET** `/analytics/dashboard`

Query Parameters:
- `startDate`: ISO date string
- `endDate`: ISO date string
- `metrics`: comma-separated list

**Response**:
```json
{
  "overview": {
    "totalRevenue": 15420.50,
    "totalUsers": 342,
    "contentGenerated": 1250,
    "socialMediaPosts": 3420
  },
  "revenueMetrics": {
    "monthlyRecurringRevenue": 4250.00,
    "oneTimeRevenue": 2340.50,
    "affiliateCommissions": 890.25,
    "growthRate": 23.5
  },
  "contentMetrics": {
    "totalPieces": 1250,
    "averageWordCount": 847,
    "mostPopularType": "blog_post",
    "topPerformingContent": [...]
  },
  "socialMetrics": {
    "totalPosts": 3420,
    "averageEngagement": 0.045,
    "topPerformingPlatform": "twitter",
    "followerGrowth": 234
  }
}
```

### Get Content Analytics

**GET** `/analytics/content`

**Response**:
```json
{
  "totalContent": 1250,
  "totalViews": 45230,
  "totalEngagement": 2341,
  "averageEngagementRate": "5.18",
  "contentByType": [
    {
      "type": "blog_post",
      "count": 450,
      "avgLength": 1247,
      "publishedCount": 423,
      "views": 23450,
      "engagement": 1234
    }
  ]
}
```

## Webhooks

### Stripe Webhooks

**POST** `/webhooks/stripe`

Handles all Stripe webhook events including:
- `payment_intent.succeeded`
- `customer.subscription.created`
- `invoice.payment_succeeded`
- `customer.subscription.deleted`

### Social Media Webhooks

**POST** `/webhooks/social`

Handles social media platform webhooks for:
- Post performance updates
- Engagement notifications
- Platform policy changes

## Error Handling

All API endpoints return consistent error responses:

```json
{
  "error": "Validation Error",
  "message": "Email is required",
  "code": "VALIDATION_FAILED",
  "details": [
    {
      "field": "email",
      "message": "Email is required"
    }
  ]
}
```

### Common HTTP Status Codes

- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `429` - Too Many Requests
- `500` - Internal Server Error

## Rate Limiting

API requests are rate-limited based on subscription tier:

- **Free**: 100 requests per 15 minutes
- **Starter**: 500 requests per 15 minutes
- **Pro**: 2000 requests per 15 minutes
- **Enterprise**: 10000 requests per 15 minutes
- **Boss Ultimate**: Unlimited

Rate limit headers are included in responses:
- `X-RateLimit-Limit`: Request limit per window
- `X-RateLimit-Remaining`: Requests remaining
- `X-RateLimit-Reset`: Window reset time

## SDKs and Libraries

### JavaScript/Node.js

```bash
npm install @youtuneai/sdk
```

```javascript
import { YouTuneAiClient } from '@youtuneai/sdk'

const client = new YouTuneAiClient({
  apiKey: 'your-jwt-token',
  environment: 'production' // or 'development'
})

const content = await client.ai.generateContent({
  contentType: 'blog_post',
  topic: 'AI automation for businesses'
})
```

### Python

```bash
pip install youtuneai-python
```

```python
from youtuneai import YouTuneAiClient

client = YouTuneAiClient(
    api_key='your-jwt-token',
    environment='production'
)

content = client.ai.generate_content(
    content_type='blog_post',
    topic='AI automation for businesses'
)
```

## Support and Community

- **Documentation**: https://docs.youtuneai.com
- **API Status**: https://status.youtuneai.com
- **Community Forum**: https://community.youtuneai.com
- **Support Email**: support@youtuneai.com
- **Enterprise Support**: enterprise@youtuneai.com

---

**Boss Man's Infinite Revenue API - Built for Scale, Designed for Success**

*This API documentation is continuously updated. For the latest changes, check our changelog or subscribe to our developer newsletter.*