# YouTune Admin Control Center

## Overview

The YouTune Admin Control Center is a WordPress must-use (mu-plugin) that provides a centralized administration hub for managing the YouTune AI platform. This plugin offers a streamlined interface for common administrative tasks and system operations.

## Features

### Admin Dashboard Integration
- Single admin page under Dashboard menu: "YouTune Admin Control Center"
- Clean, responsive interface with action cards
- Real-time AJAX operations with success/failure feedback
- Proper WordPress security with nonce validation

### Available Actions

1. **Deploy Now** - Trigger immediate deployment to production
2. **Seed Content** - Initialize database with sample content
3. **Optimize Media** - Compress and optimize media files
4. **Flush Caches** - Clear all caching layers
5. **Setup Stream** - Configure streaming services
6. **Avatar Tune** - Access avatar customization interface
7. **Ads & Analytics Check** - Verify advertising and analytics integrations
8. **Run Full Test** - Execute comprehensive system test suite

## File Structure

```
mu-plugins/
├── youtune-admin-control-center.php    # Main plugin file
├── includes/
│   ├── class-youtune-admin-ajax.php    # AJAX handlers
│   └── class-youtune-admin-settings.php # Settings management
├── assets/
│   └── js/
│       └── admin.js                    # Admin JavaScript
└── README-youtune-admin.md             # This documentation
```

## Extension Points

### Action Hooks
- `youtune_before_deploy` - Fires before deployment starts
- `youtune_after_deploy` - Fires after deployment completes
- `youtune_before_seed_content` - Fires before content seeding
- `youtune_after_seed_content` - Fires after content seeding
- `youtune_before_media_optimization` - Fires before media optimization
- `youtune_after_media_optimization` - Fires after media optimization

### Filter Hooks
- `youtune_deployment_config` - Filter deployment configuration
- `youtube_seeder_content` - Filter content to be seeded
- `youtube_media_optimization_settings` - Filter media optimization settings
- `youtune_cache_flush_targets` - Filter cache flush targets

### REST API Endpoints
Future development will include REST API endpoints for:
- Deployment management
- Content management
- Media optimization
- Analytics data

## Security Features

- WordPress nonce verification for all AJAX requests
- Capability checks (`manage_options` required)
- Sanitized input/output
- Secure AJAX handling

## Development TODOs

### GitHub Actions Integration
- Connect Deploy Now action to GitHub Actions API
- Implement workflow triggering and status monitoring
- Add deployment rollback capabilities

### WP-CLI Integration
- Connect Seed Content to WP-CLI commands
- Implement content import/export functionality
- Add database management commands

### Avatar Customizer
- Build avatar editor interface
- Integrate AI personality settings
- Connect voice synthesis configuration

### Analytics & Advertising
- Google Analytics API integration
- Google Ads platform connectivity
- YouTube monetization status checking
- AdSense configuration validation

### Media Optimization
- ImageKit/Cloudinary API integration
- WebP generation and serving
- Lazy loading implementation
- CDN integration

### Streaming Services
- YouTube Live API setup
- Twitch integration
- OBS/streaming software connectivity
- Stream key management

### Caching Systems
- Redis/Memcached integration
- CDN cache purging (Cloudflare, etc.)
- Plugin-specific cache clearing
- Performance optimization

### Testing Suite
- Database connectivity tests
- API endpoint validation
- Plugin compatibility checks
- Performance benchmarks
- Security vulnerability scans

## Installation

As a must-use plugin, this will be automatically loaded by WordPress when placed in the `wp-content/mu-plugins/` directory. No activation required.

## Requirements

- WordPress 5.0+
- PHP 7.4+
- Administrator privileges for access

## Usage

1. Navigate to Dashboard → YouTune Admin Control Center
2. Click any action button to execute the corresponding operation
3. Monitor progress through AJAX feedback messages
4. Use keyboard shortcuts for quick access:
   - Ctrl+D: Deploy Now
   - Ctrl+R: Run Full Test
   - Ctrl+F: Flush Caches

## Extending the Plugin

### Adding New Actions

1. Add new action to the `$actions` array in the main plugin file
2. Implement corresponding AJAX handler in `class-youtune-admin-ajax.php`
3. Add JavaScript handling if needed in `admin.js`

### Custom Settings

Use the `YouTune_Admin_Settings` class to manage configuration:

```php
// Get setting
$value = YouTune_Admin_Settings::get_setting('my_setting');

// Update setting  
YouTune_Admin_Settings::update_setting('my_setting', $new_value);
```

### Hook Examples

```php
// Before deployment
add_action('youtune_before_deploy', function() {
    // Custom pre-deployment logic
});

// Filter deployment config
add_filter('youtune_deployment_config', function($config) {
    $config['custom_option'] = 'value';
    return $config;
});
```

## Support

For issues, feature requests, or contributions, please refer to the main YouTune AI project repository.