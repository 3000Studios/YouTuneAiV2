# YouTuneAI Pro WordPress Theme Documentation

**Version:** 1.0.0  
**Author:** 3000Studios  
**License:** Commercial  

## Overview

YouTuneAI Pro is a revolutionary WordPress FSE (Full Site Editing) theme that combines cutting-edge web technologies to deliver an immersive, interactive experience featuring 3D graphics, VR capabilities, AI-powered avatars, gaming platforms, and comprehensive e-commerce integration.

## Features

### 🎮 Core Features
- **3D Avatar System**: Interactive AI-powered 3D avatars with voice recognition, lip-sync, and emotional responses
- **VR Room Experience**: WebXR-enabled virtual reality rooms optimized for Quest 3 and compatible headsets
- **Gaming Platform**: WebGL-powered games with performance monitoring and leaderboards
- **YouTune Garage**: 3D vehicle configurator with real-time customization and WooCommerce integration
- **Live Streaming**: Multi-platform streaming integration (YouTube, Twitch, custom RTMP)
- **E-commerce Platform**: Complete WooCommerce integration with custom product types

### 🚀 Technical Features
- **FSE Architecture**: Block-first design with theme.json configuration
- **Performance Optimized**: Lighthouse scores ≥90, Web Vitals monitoring, lazy loading
- **SEO Optimized**: Structured data, meta tags, XML sitemap, social media integration
- **Analytics Integration**: Google Analytics 4, Tag Manager, performance tracking
- **Progressive Enhancement**: Works without JavaScript, enhanced with it
- **Responsive Design**: Mobile-first approach, supports 360px to 4K displays

## System Requirements

### Minimum Requirements
- **WordPress:** 6.0+
- **PHP:** 8.0+
- **MySQL:** 5.7+ or MariaDB 10.3+
- **Memory:** 256MB+
- **Storage:** 100MB+

### Recommended Requirements
- **WordPress:** 6.4+
- **PHP:** 8.2+
- **MySQL:** 8.0+ or MariaDB 10.6+
- **Memory:** 512MB+
- **Storage:** 500MB+
- **SSL Certificate:** Required for WebXR and microphone access

### Browser Support
- **Chrome:** 79+ (WebXR support)
- **Firefox:** 98+ (WebXR enabled)
- **Edge:** 79+ (Chromium-based)
- **Safari:** 14+ (limited VR support)
- **Mobile:** iOS 13+, Android 8+

### VR Headset Support
- Meta Quest 2/3/Pro ✅ (Recommended)
- HTC Vive/Vive Pro ✅
- Valve Index ✅
- Windows Mixed Reality ✅
- Pico 4/4 Enterprise ✅

## Installation

### Method 1: WordPress Admin (Recommended)
1. Download the theme ZIP file
2. Go to **Appearance > Themes** in WordPress admin
3. Click **Add New > Upload Theme**
4. Select the ZIP file and click **Install Now**
5. Click **Activate**

### Method 2: FTP Upload
1. Extract the theme ZIP file
2. Upload the `youtuneai-theme` folder to `/wp-content/themes/`
3. Go to **Appearance > Themes** and activate the theme

### Method 3: WP-CLI
```bash
wp theme install youtuneai-theme.zip --activate
```

## Required Plugins

### Essential Plugins
1. **WooCommerce** (required for e-commerce features)
2. **Advanced Custom Fields** (recommended for content management)

### Recommended Plugins
- **LiteSpeed Cache** or **WP Rocket** (performance)
- **WP Mail SMTP** (email delivery)
- **Yoast SEO** or **RankMath** (SEO enhancement)
- **WebP Converter** (image optimization)

## Configuration

### Initial Setup

1. **Install Required Plugins**
   ```bash
   wp plugin install woocommerce advanced-custom-fields --activate
   ```

2. **Configure Theme Settings**
   - Go to **Appearance > Customize**
   - Configure theme colors, fonts, and layout options
   - Set up your logo and favicon

3. **Set Up Content Types**
   - The theme automatically creates custom post types on activation
   - Go to **Games**, **Streams**, **Avatars**, **VR Rooms** to add content

4. **Configure Admin Control Center**
   - Go to **YouTuneAI Control** in the admin menu
   - Test each system component
   - Configure API keys and integrations

### Environment Variables

Create a `.env` file in your WordPress root:

```env
# Development/Production Mode
WP_ENV=development
WP_DEBUG=true

# Database Configuration
DB_NAME=youtuneai_db
DB_USER=youtuneai_user
DB_PASSWORD=secure_password
DB_HOST=localhost

# API Keys
GOOGLE_ANALYTICS_ID=GA_MEASUREMENT_ID
GOOGLE_TAG_MANAGER_ID=GTM-XXXXXXX
ADSENSE_CLIENT_ID=ca-pub-xxxxxxxxx

# Social Media
YOUTUBE_API_KEY=your_youtube_api_key
TWITCH_CLIENT_ID=your_twitch_client_id

# Payment Gateways
STRIPE_PUBLIC_KEY=pk_test_xxxx
STRIPE_SECRET_KEY=sk_test_xxxx
PAYPAL_CLIENT_ID=your_paypal_client_id

# SMTP Configuration
SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_USERNAME=your_email@gmail.com
SMTP_PASSWORD=your_app_password
```

### WooCommerce Configuration

1. **Basic Setup**
   - Complete WooCommerce setup wizard
   - Configure currency, location, and shipping
   - Set up payment methods (Stripe, PayPal)

2. **YouTune Garage Integration**
   - The theme automatically creates a base vehicle product
   - Configure parts in **Garage Parts** custom post type
   - Set pricing and availability

3. **Product Categories**
   - Vehicle Parts
   - Digital Products
   - Game Assets
   - 3D Models

### Analytics Setup

1. **Google Analytics 4**
   ```php
   // Add to wp-config.php or use Customizer
   define('YOUTUNEAI_GA_ID', 'G-XXXXXXXXXX');
   ```

2. **Google Tag Manager**
   ```php
   define('YOUTUNEAI_GTM_ID', 'GTM-XXXXXXX');
   ```

3. **Performance Monitoring**
   - Web Vitals tracking is enabled automatically
   - Core metrics: LCP, FID, CLS, TTFB
   - Custom 3D performance metrics

## Development

### Development Environment Setup

1. **Prerequisites**
   ```bash
   node -v  # 18+
   npm -v   # 8+
   php -v   # 8.0+
   composer -v  # 2.0+
   ```

2. **Clone and Setup**
   ```bash
   git clone [repository-url]
   cd youtuneai-theme
   npm install
   composer install  # if using Composer for PHP dependencies
   ```

3. **Development Commands**
   ```bash
   npm run dev          # Development build with watching
   npm run build        # Production build
   npm run test         # Run all tests
   npm run lint         # Lint JavaScript and CSS
   npm run lighthouse   # Performance audit
   ```

### File Structure

```
youtuneai-theme/
├── assets/
│   ├── css/
│   │   ├── src/main.css      # Source Tailwind CSS
│   │   └── dist/main.css     # Compiled CSS
│   ├── js/
│   │   ├── src/              # Source JavaScript
│   │   │   ├── main.js       # Core functionality
│   │   │   ├── avatar.js     # 3D Avatar system
│   │   │   ├── games.js      # Gaming platform
│   │   │   ├── garage.js     # 3D Configurator
│   │   │   └── vr.js         # VR system
│   │   └── dist/             # Compiled JavaScript
│   ├── img/                  # Static images
│   └── models/               # 3D models (GLB/GLTF)
├── blocks/                   # Custom Gutenberg blocks
├── includes/                 # PHP functionality
│   ├── admin-control-center.php
│   ├── avatar-system.php
│   ├── helpers.php
│   ├── performance-optimization.php
│   ├── rest-endpoints.php
│   ├── seo-analytics.php
│   └── woocommerce-integration.php
├── parts/                    # Template parts
├── templates/                # Page templates
├── tests/                    # Test files
├── functions.php             # Theme functions
├── style.css                 # Theme header
├── theme.json                # FSE configuration
└── README.md                 # This file
```

### Custom Post Types

#### Games (`game`)
- **Fields:** Platform, build path, screenshots, play URL
- **Taxonomy:** Game genres
- **Templates:** `archive-game.php`, `single-game.php`

#### Streams (`stream`)
- **Fields:** Platform, stream key, schedule, embed URL, is_live
- **Templates:** `archive-stream.php`, `single-stream.php`

#### Avatars (`avatar`)
- **Fields:** Model path, voice ID, lip-sync profile, colorway, settings
- **Usage:** 3D avatar system integration

#### VR Rooms (`vr_room`)
- **Fields:** Scene config, media playlist, ad zones
- **Templates:** `single-vr_room.php`

#### Garage Parts (`garage_part`)
- **Fields:** Part type, model path, metadata, price, compatibility
- **Taxonomy:** Part types
- **Usage:** YouTune Garage configurator

### REST API Endpoints

All endpoints are under `/wp-json/youtuneai/v1/`

- **GET** `/avatar/{id}` - Get avatar data
- **POST** `/avatar/chat` - Chat with avatar
- **GET** `/games` - List games
- **GET** `/streams/live` - Get live streams
- **GET** `/garage/parts` - List garage parts
- **POST** `/garage/configure` - Configure garage product
- **GET** `/vr/rooms` - List VR rooms

### JavaScript APIs

#### YouTuneAI Main Object
```javascript
// Core functionality
YouTuneAI.init()
YouTuneAI.openModal(modalId)
YouTuneAI.closeModal()

// Utilities
YouTuneAI.utils.debounce(func, wait)
YouTuneAI.utils.throttle(func, limit)
YouTuneAI.utils.formatBytes(bytes)

// API calls
YouTuneAI.api.getGames()
YouTuneAI.api.getLiveStreams()
YouTuneAI.api.getVRRooms()
```

#### 3D Avatar System
```javascript
// Initialize avatar
const avatar = new YouTuneAIAvatar(container, {
    avatarId: 1,
    interactive: true,
    voice: true
});

// Events
document.addEventListener('youtuneai-avatar-ready', handler);
document.addEventListener('youtuneai-avatar-speaking', handler);
```

#### Garage Configurator
```javascript
// Initialize garage
const garage = new YouTuneGarage();

// Add parts
garage.addPart(partData);
garage.removePart(partId);

// Customize
garage.changeColor('#ff0000');
garage.changeMaterial('metallic');
```

## Testing

### Running Tests

```bash
# PHP Tests
composer test
# or
vendor/bin/phpunit

# JavaScript Tests
npm test
npm run test:watch  # Watch mode

# Lighthouse Performance Tests
npm run lighthouse

# Full Test Suite
npm run test:all
```

### Test Categories

1. **Unit Tests**
   - PHP helper functions
   - JavaScript utilities
   - API endpoints

2. **Integration Tests**
   - WooCommerce integration
   - 3D system loading
   - REST API responses

3. **Performance Tests**
   - Lighthouse audits
   - Web Vitals monitoring
   - Memory usage tracking

4. **Accessibility Tests**
   - WCAG 2.1 AA compliance
   - Keyboard navigation
   - Screen reader compatibility

### Performance Benchmarks

Target performance metrics:
- **Lighthouse Performance:** ≥90
- **LCP:** ≤2.5s
- **FID:** ≤100ms
- **CLS:** ≤0.1
- **Bundle Size:** ≤200KB (gzipped)

## Deployment

### Production Deployment

1. **Build Assets**
   ```bash
   npm run build
   NODE_ENV=production npm run optimize
   ```

2. **Upload Theme**
   - Use FTP/SFTP to upload to `/wp-content/themes/`
   - Or use GitHub Actions for automated deployment

3. **Environment Configuration**
   - Set `WP_ENV=production`
   - Disable `WP_DEBUG`
   - Configure caching
   - Set up SSL certificate

### CI/CD Pipeline

The theme includes GitHub Actions workflow (`.github/workflows/deploy.yml`):

- **Lint & Test:** Code quality checks
- **Build:** Asset compilation and optimization  
- **Deploy:** SFTP deployment to staging/production
- **Performance Audit:** Lighthouse CI integration
- **Notifications:** Success/failure alerts

### Staging Environment

1. **Setup Staging**
   ```bash
   wp config set WP_ENV staging
   wp config set WP_DEBUG false
   ```

2. **Database Sync**
   ```bash
   wp db export production.sql
   wp db import production.sql --env=staging
   ```

## Troubleshooting

### Common Issues

#### 3D Models Not Loading
- **Cause:** CORS issues or file permissions
- **Solution:** 
  ```apache
  # Add to .htaccess
  <FilesMatch "\.(glb|gltf)$">
    Header set Access-Control-Allow-Origin "*"
  </FilesMatch>
  ```

#### VR Mode Not Working
- **Cause:** HTTPS required for WebXR
- **Solution:** Enable SSL certificate
- **Check:** Browser compatibility and permissions

#### Performance Issues
- **Cause:** Large 3D models or unoptimized assets
- **Solution:**
  ```bash
  # Optimize 3D models
  gltf-pipeline -i model.gltf -o model.glb --draco
  
  # Enable compression
  wp config set WP_CACHE true
  ```

#### Payment Gateway Errors
- **Cause:** Missing API keys or configuration
- **Solution:** Verify credentials in WooCommerce settings

### Debug Mode

Enable debug mode for development:
```php
// wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('YOUTUNEAI_DEBUG', true);
```

### Performance Monitoring

View performance logs:
```bash
wp option get youtuneai_performance_logs
```

## Support & Contributing

### Support Channels
- **Documentation:** This README and inline code comments
- **GitHub Issues:** Bug reports and feature requests
- **Email Support:** For commercial license holders

### Contributing
1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Submit a pull request

### Code Standards
- **PHP:** WordPress Coding Standards
- **JavaScript:** ESLint + Prettier
- **CSS:** Stylelint
- **Commit Messages:** Conventional Commits

## License

This theme is licensed under a commercial license. See LICENSE file for details.

### Usage Rights
- ✅ Use on unlimited client websites
- ✅ Modify and customize
- ✅ Create child themes
- ❌ Redistribute or resell
- ❌ Use on template marketplaces

## Changelog

### Version 1.0.0 (2024-08-08)
- Initial release
- 3D Avatar system with Three.js
- VR Room with WebXR support
- YouTune Garage configurator
- WooCommerce integration
- Performance optimization
- SEO and analytics integration
- Comprehensive testing suite
- CI/CD pipeline

## Credits

### Third-Party Libraries
- **Three.js:** 3D graphics library
- **Tailwind CSS:** Utility-first CSS framework
- **WordPress:** Content management system
- **WooCommerce:** E-commerce platform

### Assets
- **Fonts:** Google Fonts (Orbitron, Raleway, Martian Mono)
- **Icons:** Boxicons
- **3D Models:** Custom created or licensed

---

**YouTuneAI Pro Theme v1.0.0**  
© 2024 3000Studios. All rights reserved.