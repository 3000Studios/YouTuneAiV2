<?php
/**
 * PHPUnit bootstrap file
 */

// Define testing environment
define('WP_TESTS_CONFIG_FILE_PATH', dirname(__FILE__) . '/wp-config-test.php');

// Include WordPress test framework
if (file_exists('/tmp/wordpress-tests-lib/includes/functions.php')) {
    require_once '/tmp/wordpress-tests-lib/includes/functions.php';
} else {
    // Fallback for environments without WordPress test suite
    define('ABSPATH', '/tmp/wordpress/');
    define('WP_DEBUG', true);
    define('WP_DEBUG_LOG', true);
    
    // Mock WordPress functions for testing
    if (!function_exists('wp_create_nonce')) {
        function wp_create_nonce($action) {
            return md5($action . time());
        }
    }
    
    if (!function_exists('wp_verify_nonce')) {
        function wp_verify_nonce($nonce, $action) {
            return true; // For testing purposes
        }
    }
    
    if (!function_exists('sanitize_text_field')) {
        function sanitize_text_field($str) {
            return filter_var($str, FILTER_SANITIZE_STRING);
        }
    }
    
    if (!function_exists('get_option')) {
        function get_option($option, $default = false) {
            return $default;
        }
    }
    
    if (!function_exists('update_option')) {
        function update_option($option, $value) {
            return true;
        }
    }
    
    if (!function_exists('current_time')) {
        function current_time($type, $gmt = 0) {
            return $gmt ? gmdate('Y-m-d H:i:s') : date('Y-m-d H:i:s');
        }
    }
    
    if (!function_exists('home_url')) {
        function home_url($path = '') {
            return 'https://youtuneai.test' . $path;
        }
    }
    
    if (!function_exists('get_theme_file_uri')) {
        function get_theme_file_uri($file = '') {
            return home_url('/wp-content/themes/youtuneai-theme' . $file);
        }
    }
    
    if (!function_exists('esc_attr')) {
        function esc_attr($text) {
            return htmlspecialchars($text, ENT_QUOTES);
        }
    }
    
    if (!function_exists('esc_url')) {
        function esc_url($url) {
            return filter_var($url, FILTER_SANITIZE_URL);
        }
    }
    
    if (!function_exists('__')) {
        function __($text, $domain = 'default') {
            return $text;
        }
    }
    
    if (!function_exists('_e')) {
        function _e($text, $domain = 'default') {
            echo $text;
        }
    }
}

// Set up theme directory
define('YOUTUNEAI_THEME_DIR', dirname(__DIR__));
define('YOUTUNEAI_THEME_URL', 'https://youtuneai.test/wp-content/themes/youtuneai-theme');
define('YOUTUNEAI_VERSION', '1.0.0');

// Include theme functions for testing
require_once YOUTUNEAI_THEME_DIR . '/includes/helpers.php';