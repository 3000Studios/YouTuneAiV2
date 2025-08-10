<?php
/**
 * Helper Functions
 * 
 * @package YouTuneAI
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get theme option with default
 */
function youtuneai_get_option($option, $default = '') {
    return get_theme_mod('youtuneai_' . $option, $default);
}

/**
 * Set theme option
 */
function youtuneai_set_option($option, $value) {
    set_theme_mod('youtuneai_' . $option, $value);
}

/**
 * Get current live stream
 */
function youtuneai_get_current_stream() {
    $args = array(
        'post_type' => 'stream',
        'post_status' => 'publish',
        'posts_per_page' => 1,
        'meta_query' => array(
            array(
                'key' => 'is_live',
                'value' => '1',
                'compare' => '='
            )
        )
    );
    
    $streams = get_posts($args);
    return !empty($streams) ? $streams[0] : null;
}

/**
 * Check if stream is currently live
 */
function youtuneai_is_stream_live($stream_id = null) {
    if (!$stream_id) {
        $stream = youtuneai_get_current_stream();
        if (!$stream) return false;
        $stream_id = $stream->ID;
    }
    
    $is_live = get_post_meta($stream_id, 'is_live', true);
    $schedule = get_post_meta($stream_id, 'schedule', true);
    
    // Check if scheduled time matches current time
    if ($schedule && !empty($schedule['start']) && !empty($schedule['end'])) {
        $now = current_time('timestamp');
        $start = strtotime($schedule['start']);
        $end = strtotime($schedule['end']);
        
        return $is_live && ($now >= $start && $now <= $end);
    }
    
    return $is_live;
}

/**
 * Get featured games
 */
function youtuneai_get_featured_games($count = 6) {
    $args = array(
        'post_type' => 'game',
        'post_status' => 'publish',
        'posts_per_page' => $count,
        'meta_query' => array(
            array(
                'key' => 'featured',
                'value' => '1',
                'compare' => '='
            )
        )
    );
    
    $games = get_posts($args);
    
    // If not enough featured games, fill with regular games
    if (count($games) < $count) {
        $regular_args = array(
            'post_type' => 'game',
            'post_status' => 'publish',
            'posts_per_page' => $count - count($games),
            'post__not_in' => wp_list_pluck($games, 'ID')
        );
        
        $regular_games = get_posts($regular_args);
        $games = array_merge($games, $regular_games);
    }
    
    return $games;
}

/**
 * Get garage part by type
 */
function youtuneai_get_garage_parts_by_type($type) {
    $args = array(
        'post_type' => 'garage_part',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'part_type',
                'field' => 'slug',
                'terms' => $type
            )
        )
    );
    
    return get_posts($args);
}

/**
 * Calculate garage configuration price
 */
function youtuneai_calculate_garage_price($part_ids) {
    $total = 0;
    
    foreach ($part_ids as $part_id) {
        $price = get_post_meta($part_id, 'price', true);
        $total += floatval($price);
    }
    
    return $total;
}

/**
 * Get VR room by slug
 */
function youtuneai_get_vr_room($slug) {
    $room = get_page_by_path($slug, OBJECT, 'vr_room');
    return $room;
}

/**
 * Format file size
 */
function youtuneai_format_bytes($size, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    
    for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
        $size /= 1024;
    }
    
    return round($size, $precision) . ' ' . $units[$i];
}

/**
 * Generate placeholder image URL
 */
function youtuneai_placeholder_image($width = 400, $height = 300, $text = 'YouTuneAI') {
    return "https://via.placeholder.com/{$width}x{$height}/9d00ff/ffffff?text=" . urlencode($text);
}

/**
 * Get performance score
 */
function youtuneai_get_performance_score() {
    // Mock performance score - in real implementation, this would fetch from Lighthouse CI
    return array(
        'performance' => 95,
        'accessibility' => 98,
        'best_practices' => 92,
        'seo' => 96,
        'timestamp' => current_time('timestamp')
    );
}

/**
 * Log performance metric
 */
function youtuneai_log_performance($metric, $value) {
    $log_entry = array(
        'metric' => $metric,
        'value' => $value,
        'timestamp' => current_time('timestamp'),
        'url' => $_SERVER['REQUEST_URI'] ?? 'unknown'
    );
    
    $logs = get_option('youtuneai_performance_logs', array());
    $logs[] = $log_entry;
    
    // Keep only last 100 entries
    if (count($logs) > 100) {
        $logs = array_slice($logs, -100);
    }
    
    update_option('youtuneai_performance_logs', $logs);
}

/**
 * Get system status
 */
function youtuneai_get_system_status() {
    return array(
        'theme_version' => YOUTUNEAI_VERSION,
        'wp_version' => get_bloginfo('version'),
        'php_version' => PHP_VERSION,
        'memory_limit' => ini_get('memory_limit'),
        'upload_max_filesize' => ini_get('upload_max_filesize'),
        'woocommerce_active' => class_exists('WooCommerce'),
        'cache_plugin' => youtuneai_detect_cache_plugin(),
        'ssl_enabled' => is_ssl(),
        'debug_mode' => WP_DEBUG,
    );
}

/**
 * Detect cache plugin
 */
function youtuneai_detect_cache_plugin() {
    if (function_exists('w3tc_flush_all')) {
        return 'W3 Total Cache';
    } elseif (function_exists('wp_cache_clear_cache')) {
        return 'WP Super Cache';
    } elseif (class_exists('LiteSpeed_Cache')) {
        return 'LiteSpeed Cache';
    } elseif (function_exists('rocket_clean_domain')) {
        return 'WP Rocket';
    }
    
    return 'None detected';
}

/**
 * Optimize image URL
 */
function youtuneai_optimize_image($url, $width = null, $height = null, $quality = 85) {
    // Basic implementation - in production, integrate with image optimization service
    if ($width || $height) {
        $params = array();
        if ($width) $params[] = "w={$width}";
        if ($height) $params[] = "h={$height}";
        if ($quality !== 85) $params[] = "q={$quality}";
        
        if (!empty($params)) {
            $url .= (strpos($url, '?') !== false ? '&' : '?') . implode('&', $params);
        }
    }
    
    return $url;
}

/**
 * Get monetization stats
 */
function youtuneai_get_monetization_stats() {
    // Mock data - integrate with actual analytics and ad networks
    return array(
        'adsense' => array(
            'revenue' => 127.45,
            'impressions' => 15230,
            'clicks' => 892,
            'ctr' => 5.86
        ),
        'affiliate' => array(
            'commissions' => 89.32,
            'clicks' => 432,
            'conversions' => 23
        ),
        'woocommerce' => array(
            'sales' => 1250.00,
            'orders' => 15,
            'products_sold' => 28
        ),
        'period' => 'Last 30 days'
    );
}

/**
 * Check if user is on mobile VR device
 */
function youtuneai_is_vr_device() {
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    
    // Check for common VR browsers/devices
    $vr_patterns = array(
        'OculusBrowser',
        'Quest',
        'Pico',
        'VR',
        'WebXR'
    );
    
    foreach ($vr_patterns as $pattern) {
        if (strpos($user_agent, $pattern) !== false) {
            return true;
        }
    }
    
    return false;
}

/**
 * Generate critical CSS
 */
function youtuneai_get_critical_css() {
    // This would be generated during build process
    $critical_css = get_option('youtuneai_critical_css', '');
    
    if (empty($critical_css)) {
        // Fallback critical CSS
        $critical_css = '
            body { margin: 0; font-family: system-ui, -apple-system, sans-serif; }
            .hero { min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        ';
    }
    
    return $critical_css;
}

/**
 * Inline critical CSS
 */
function youtuneai_inline_critical_css() {
    $critical_css = youtuneai_get_critical_css();
    if (!empty($critical_css)) {
        echo '<style id="youtuneai-critical-css">' . $critical_css . '</style>';
    }
}
add_action('wp_head', 'youtuneai_inline_critical_css', 1);

/**
 * Preload important resources
 */
function youtuneai_preload_resources() {
    ?>
    <link rel="preload" href="<?php echo YOUTUNEAI_THEME_URL; ?>/assets/css/dist/main.css" as="style">
    <link rel="preload" href="<?php echo YOUTUNEAI_THEME_URL; ?>/assets/js/dist/main.js" as="script">
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;900&display=swap" as="style">
    <?php
}
add_action('wp_head', 'youtuneai_preload_resources', 1);