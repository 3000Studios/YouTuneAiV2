<?php

/**
 * Theme activation functions
 * Handles theme setup and requirements check
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme activation hook
 */
function youtuneai_theme_activation()
{
    // Check WordPress version
    if (version_compare(get_bloginfo('version'), '5.0', '<')) {
        deactivate_theme(get_stylesheet());
        wp_die('YouTuneAI Theme requires WordPress 5.0 or higher.');
    }

    // Check PHP version
    if (version_compare(phpversion(), '7.4', '<')) {
        deactivate_theme(get_stylesheet());
        wp_die('YouTuneAI Theme requires PHP 7.4 or higher.');
    }

    // Create necessary database tables
    youtuneai_create_tables();

    // Set default theme options
    youtuneai_set_default_options();

    // Create upload directories
    youtuneai_create_upload_dirs();

    // Flush rewrite rules
    flush_rewrite_rules();
}

/**
 * Create custom database tables
 */
function youtuneai_create_tables()
{
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    // Voice commands log table
    $table_name = $wpdb->prefix . 'youtuneai_voice_commands';
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        timestamp datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        user_id bigint(20) UNSIGNED NOT NULL,
        command text NOT NULL,
        response text,
        status varchar(20) DEFAULT 'pending',
        PRIMARY KEY (id),
        KEY user_id (user_id),
        KEY timestamp (timestamp)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // AI analytics table
    $table_name = $wpdb->prefix . 'youtuneai_analytics';
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        timestamp datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        action varchar(100) NOT NULL,
        data longtext,
        ip_address varchar(45),
        user_agent text,
        PRIMARY KEY (id),
        KEY timestamp (timestamp),
        KEY action (action)
    ) $charset_collate;";

    dbDelta($sql);
}

/**
 * Set default theme options
 */
function youtuneai_set_default_options()
{
    $defaults = array(
        'youtuneai_voice_enabled' => true,
        'youtuneai_ai_powered' => true,
        'youtuneai_streaming_enabled' => true,
        'youtuneai_admin_protection' => true,
        'youtuneai_theme_color' => '#1a1a1a',
        'youtuneai_accent_color' => '#00d4ff',
        'youtuneai_background_video' => '',
        'youtuneai_logo_url' => '',
        'youtuneai_contact_email' => get_option('admin_email'),
        'youtuneai_analytics_enabled' => true,
        'youtuneai_debug_mode' => false
    );

    foreach ($defaults as $option => $value) {
        if (get_option($option) === false) {
            add_option($option, $value);
        }
    }
}

/**
 * Create upload directories
 */
function youtuneai_create_upload_dirs()
{
    $upload_dir = wp_upload_dir();
    $base_dir = $upload_dir['basedir'];

    $dirs = array(
        '/youtuneai',
        '/youtuneai/voice-recordings',
        '/youtuneai/ai-generated',
        '/youtuneai/streaming',
        '/youtuneai/logs'
    );

    foreach ($dirs as $dir) {
        $full_path = $base_dir . $dir;
        if (!file_exists($full_path)) {
            wp_mkdir_p($full_path);

            // Create .htaccess for security
            $htaccess_content = "Options -Indexes\nDeny from all";
            file_put_contents($full_path . '/.htaccess', $htaccess_content);
        }
    }
}

/**
 * Theme deactivation hook
 */
function youtuneai_theme_deactivation()
{
    // Clean up temporary files
    youtuneai_cleanup_temp_files();

    // Flush rewrite rules
    flush_rewrite_rules();
}

/**
 * Clean up temporary files
 */
function youtuneai_cleanup_temp_files()
{
    $upload_dir = wp_upload_dir();
    $temp_dir = $upload_dir['basedir'] . '/youtuneai/temp/';

    if (file_exists($temp_dir)) {
        $files = glob($temp_dir . '*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
}

/**
 * Check theme requirements
 */
function youtuneai_check_requirements()
{
    $requirements = array();

    // Check required plugins
    $required_plugins = array(
        'woocommerce/woocommerce.php' => 'WooCommerce',
        'advanced-custom-fields/acf.php' => 'Advanced Custom Fields',
        'wp-rest-api-controller/wp-rest-api-controller.php' => 'WP REST API Controller',
        'wp-webhooks/wp-webhooks.php' => 'WP Webhooks',
        'custom-post-type-ui/custom-post-type-ui.php' => 'Custom Post Type UI'
    );

    foreach ($required_plugins as $plugin => $name) {
        if (!is_plugin_active($plugin)) {
            $requirements[] = "Required plugin missing: $name";
        }
    }

    // Check PHP extensions
    $required_extensions = array('curl', 'json', 'mbstring');
    foreach ($required_extensions as $ext) {
        if (!extension_loaded($ext)) {
            $requirements[] = "Required PHP extension missing: $ext";
        }
    }

    // Check file permissions
    $upload_dir = wp_upload_dir();
    if (!is_writable($upload_dir['basedir'])) {
        $requirements[] = "Upload directory not writable";
    }

    return $requirements;
}

/**
 * Display admin notices for missing requirements
 */
function youtuneai_admin_notices()
{
    $requirements = youtuneai_check_requirements();

    if (!empty($requirements)) {
        echo '<div class="notice notice-error"><p>';
        echo '<strong>YouTuneAI Theme Requirements:</strong><br>';
        echo implode('<br>', $requirements);
        echo '</p></div>';
    }
}
add_action('admin_notices', 'youtuneai_admin_notices');

// Register activation/deactivation hooks
add_action('after_switch_theme', 'youtuneai_theme_activation');
add_action('switch_theme', 'youtuneai_theme_deactivation');
