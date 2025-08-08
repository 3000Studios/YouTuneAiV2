<?php
/**
 * Performance Optimization Module
 * 
 * @package YouTuneAI
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initialize performance optimizations
 */
function youtuneai_performance_init() {
    // Asset optimization
    add_action('wp_enqueue_scripts', 'youtuneai_optimize_assets', 999);
    add_filter('style_loader_tag', 'youtuneai_optimize_css_loading', 10, 2);
    add_filter('script_loader_tag', 'youtuneai_optimize_js_loading', 10, 2);
    
    // Image optimization
    add_filter('wp_get_attachment_image_attributes', 'youtuneai_optimize_images', 10, 3);
    add_action('wp_head', 'youtuneai_preload_critical_resources', 1);
    
    // Lazy loading
    add_filter('the_content', 'youtuneai_lazy_load_content');
    add_filter('post_thumbnail_html', 'youtuneai_lazy_load_thumbnails');
    
    // Compression and minification
    add_action('init', 'youtuneai_enable_compression');
    
    // Database optimization
    add_action('wp', 'youtuneai_optimize_queries');
    
    // Caching enhancements
    add_action('init', 'youtuneai_setup_caching');
    
    // Performance monitoring
    add_action('wp_footer', 'youtuneai_performance_monitoring_script');
    add_action('wp_ajax_youtuneai_log_performance', 'youtuneai_handle_performance_log');
    add_action('wp_ajax_nopriv_youtuneai_log_performance', 'youtuneai_handle_performance_log');
}
add_action('after_setup_theme', 'youtuneai_performance_init');

/**
 * Optimize asset loading
 */
function youtuneai_optimize_assets() {
    // Remove unnecessary scripts on pages where they're not needed
    if (!is_page_template('page-garage.php')) {
        wp_dequeue_script('youtuneai-garage');
    }
    
    if (!is_page_template('page-vr-room.php')) {
        wp_dequeue_script('youtuneai-vr');
    }
    
    if (!is_post_type_archive('game') && !is_singular('game')) {
        wp_dequeue_script('youtuneai-games');
    }
    
    // Combine and minify CSS (in production)
    if (!WP_DEBUG) {
        youtuneai_combine_css_files();
    }
}

/**
 * Optimize CSS loading with critical CSS inlining
 */
function youtuneai_optimize_css_loading($html, $handle) {
    // Critical CSS files should be inlined
    $critical_handles = array('youtuneai-critical', 'youtuneai-above-fold');
    
    if (in_array($handle, $critical_handles)) {
        $css_path = str_replace(get_site_url(), ABSPATH, wp_styles()->registered[$handle]->src);
        
        if (file_exists($css_path)) {
            $css_content = file_get_contents($css_path);
            return '<style id="' . $handle . '-inline">' . $css_content . '</style>';
        }
    }
    
    // Add media="print" and switch to screen after load for non-critical CSS
    $non_critical_handles = array('youtuneai-style', 'youtuneai-tailwind');
    
    if (in_array($handle, $non_critical_handles)) {
        $html = str_replace("media='all'", "media='print' onload=\"this.media='all'\"", $html);
        $html .= '<noscript>' . str_replace("media='print' onload=\"this.media='all'\"", "media='all'", $html) . '</noscript>';
    }
    
    return $html;
}

/**
 * Optimize JavaScript loading
 */
function youtuneai_optimize_js_loading($html, $handle) {
    // Add async/defer attributes to specific scripts
    $async_scripts = array('youtuneai-analytics', 'google-analytics');
    $defer_scripts = array('youtuneai-main', 'youtuneai-avatar', 'youtuneai-games');
    
    if (in_array($handle, $async_scripts)) {
        $html = str_replace('<script ', '<script async ', $html);
    } elseif (in_array($handle, $defer_scripts)) {
        $html = str_replace('<script ', '<script defer ', $html);
    }
    
    // Add module type for modern JavaScript
    if (strpos($handle, 'youtuneai-') === 0 && !WP_DEBUG) {
        $html = str_replace('<script ', '<script type="module" ', $html);
    }
    
    return $html;
}

/**
 * Optimize images
 */
function youtuneai_optimize_images($attr, $attachment, $size) {
    // Add loading="lazy" to images below the fold
    if (!isset($attr['loading'])) {
        $attr['loading'] = 'lazy';
    }
    
    // Add decoding="async"
    $attr['decoding'] = 'async';
    
    // Add srcset for responsive images
    $image_meta = wp_get_attachment_metadata($attachment->ID);
    if ($image_meta && isset($image_meta['sizes'])) {
        $srcset = wp_get_attachment_image_srcset($attachment->ID, $size);
        if ($srcset) {
            $attr['srcset'] = $srcset;
            $attr['sizes'] = wp_get_attachment_image_sizes($attachment->ID, $size);
        }
    }
    
    return $attr;
}

/**
 * Preload critical resources
 */
function youtuneai_preload_critical_resources() {
    // Preload critical fonts
    echo '<link rel="preload" href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;900&display=swap" as="style" crossorigin>' . "\n";
    echo '<link rel="preload" href="https://fonts.gstatic.com/s/orbitron/v29/yMJMMIlzdpvBhQQL_SC3X9yhF25-T1nyGy6BoWgz.woff2" as="font" type="font/woff2" crossorigin>' . "\n";
    
    // Preload critical JavaScript
    echo '<link rel="preload" href="' . YOUTUNEAI_THEME_URL . '/assets/js/dist/main.js" as="script">' . "\n";
    
    // Preload critical images
    $hero_image = youtuneai_get_option('hero_image');
    if ($hero_image) {
        echo '<link rel="preload" href="' . esc_url($hero_image) . '" as="image">' . "\n";
    }
    
    // Preload Three.js for 3D pages
    if (is_page_template('page-garage.php') || is_page_template('page-vr-room.php') || is_front_page()) {
        echo '<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/three.js/r157/three.min.js" as="script" crossorigin>' . "\n";
    }
    
    // DNS prefetch for external resources
    echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">' . "\n";
    echo '<link rel="dns-prefetch" href="//fonts.gstatic.com">' . "\n";
    echo '<link rel="dns-prefetch" href="//cdnjs.cloudflare.com">' . "\n";
    echo '<link rel="dns-prefetch" href="//www.google-analytics.com">' . "\n";
    echo '<link rel="dns-prefetch" href="//pagead2.googlesyndication.com">' . "\n";
}

/**
 * Lazy load content
 */
function youtuneai_lazy_load_content($content) {
    // Lazy load iframes (YouTube, Twitch embeds)
    $content = preg_replace(
        '/<iframe(.+?)src="(.+?)"(.+?)><\/iframe>/i',
        '<iframe$1data-src="$2" src="data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 1 1\'%3E%3C/svg%3E"$3 loading="lazy"></iframe>',
        $content
    );
    
    // Add lazy loading to images in content
    $content = preg_replace(
        '/<img(.+?)src="(.+?)"(.+?)>/i',
        '<img$1data-src="$2" src="data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 1 1\'%3E%3C/svg%3E"$3 loading="lazy">',
        $content
    );
    
    return $content;
}

/**
 * Lazy load thumbnails
 */
function youtuneai_lazy_load_thumbnails($html) {
    // Only lazy load thumbnails below the fold
    static $thumbnail_count = 0;
    $thumbnail_count++;
    
    // First 3 thumbnails load normally (above the fold)
    if ($thumbnail_count <= 3) {
        return $html;
    }
    
    return str_replace('src="', 'loading="lazy" src="', $html);
}

/**
 * Enable compression
 */
function youtuneai_enable_compression() {
    if (!WP_DEBUG && function_exists('ob_gzhandler')) {
        ob_start('ob_gzhandler');
    }
}

/**
 * Optimize database queries
 */
function youtuneai_optimize_queries() {
    // Remove unnecessary queries
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    
    // Disable emoji scripts and styles
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    
    // Optimize REST API
    if (!is_user_logged_in()) {
        remove_action('wp_head', 'rest_output_link_wp_head');
        remove_action('template_redirect', 'rest_output_link_header', 11);
    }
}

/**
 * Setup caching
 */
function youtuneai_setup_caching() {
    // Add cache headers for static assets
    add_action('wp_loaded', function() {
        if (strpos($_SERVER['REQUEST_URI'], '/assets/') !== false) {
            header('Cache-Control: public, max-age=31536000'); // 1 year
            header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
        }
    });
    
    // Object caching for expensive queries
    add_filter('pre_get_posts', 'youtuneai_cache_expensive_queries');
}

/**
 * Cache expensive queries
 */
function youtuneai_cache_expensive_queries($query) {
    if (!$query->is_main_query() || is_admin()) {
        return;
    }
    
    // Cache featured games query
    if ($query->get('post_type') === 'game' && $query->get('meta_key') === 'featured') {
        $cache_key = 'youtuneai_featured_games_' . md5(serialize($query->query_vars));
        $cached_posts = wp_cache_get($cache_key);
        
        if ($cached_posts === false) {
            // Query will run normally, but we'll cache the results
            add_action('the_posts', function($posts) use ($cache_key) {
                wp_cache_set($cache_key, $posts, '', 3600); // Cache for 1 hour
                return $posts;
            });
        } else {
            $query->posts = $cached_posts;
            $query->post_count = count($cached_posts);
            $query->found_posts = count($cached_posts);
        }
    }
}

/**
 * Performance monitoring script
 */
function youtuneai_performance_monitoring_script() {
    if (WP_DEBUG || !youtuneai_get_option('performance_monitoring', true)) {
        return;
    }
    
    ?>
    <script>
    // Performance monitoring
    (function() {
        // Core Web Vitals
        function sendMetric(metric) {
            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                method: 'POST',
                body: new URLSearchParams({
                    action: 'youtuneai_log_performance',
                    metric: metric.name,
                    value: metric.value,
                    url: location.pathname,
                    nonce: '<?php echo wp_create_nonce('youtuneai_performance'); ?>'
                })
            }).catch(err => console.warn('Performance logging failed:', err));
        }
        
        // Web Vitals tracking
        function trackWebVitals() {
            // LCP - Largest Contentful Paint
            new PerformanceObserver((entryList) => {
                const entries = entryList.getEntries();
                const lastEntry = entries[entries.length - 1];
                sendMetric({
                    name: 'LCP',
                    value: lastEntry.startTime
                });
            }).observe({entryTypes: ['largest-contentful-paint']});
            
            // FID - First Input Delay
            new PerformanceObserver((entryList) => {
                for (const entry of entryList.getEntries()) {
                    sendMetric({
                        name: 'FID',
                        value: entry.processingStart - entry.startTime
                    });
                }
            }).observe({entryTypes: ['first-input']});
            
            // CLS - Cumulative Layout Shift
            let clsValue = 0;
            let clsEntries = [];
            
            new PerformanceObserver((entryList) => {
                for (const entry of entryList.getEntries()) {
                    if (!entry.hadRecentInput) {
                        const firstSessionEntry = clsEntries[0];
                        const lastSessionEntry = clsEntries[clsEntries.length - 1];
                        
                        if (!firstSessionEntry || 
                            entry.startTime - lastSessionEntry.startTime > 1000 ||
                            entry.startTime - firstSessionEntry.startTime > 5000) {
                            clsValue = entry.value;
                            clsEntries = [entry];
                        } else {
                            clsValue += entry.value;
                            clsEntries.push(entry);
                        }
                    }
                }
                
                sendMetric({
                    name: 'CLS',
                    value: clsValue
                });
            }).observe({entryTypes: ['layout-shift']});
        }
        
        // 3D Performance tracking
        function track3DPerformance() {
            if (window.performance && performance.mark) {
                // Track 3D model loading time
                const modelLoadStart = performance.now();
                
                document.addEventListener('youtuneai-3d-loaded', function() {
                    const loadTime = performance.now() - modelLoadStart;
                    sendMetric({
                        name: '3D_LOAD_TIME',
                        value: loadTime
                    });
                });
                
                // Track FPS
                let frameCount = 0;
                let lastTime = performance.now();
                
                function trackFPS() {
                    frameCount++;
                    const currentTime = performance.now();
                    
                    if (currentTime - lastTime >= 5000) { // Every 5 seconds
                        const fps = frameCount / 5;
                        sendMetric({
                            name: '3D_FPS',
                            value: fps
                        });
                        
                        frameCount = 0;
                        lastTime = currentTime;
                    }
                    
                    if (document.querySelector('#garage-3d-canvas, #vr-canvas')) {
                        requestAnimationFrame(trackFPS);
                    }
                }
                
                if (document.querySelector('#garage-3d-canvas, #vr-canvas')) {
                    trackFPS();
                }
            }
        }
        
        // Memory usage tracking
        function trackMemoryUsage() {
            if (performance.memory) {
                setInterval(() => {
                    sendMetric({
                        name: 'MEMORY_USAGE',
                        value: performance.memory.usedJSHeapSize
                    });
                }, 30000); // Every 30 seconds
            }
        }
        
        // Initialize tracking
        if (window.PerformanceObserver) {
            trackWebVitals();
        }
        
        track3DPerformance();
        trackMemoryUsage();
        
        // Page load metrics
        window.addEventListener('load', function() {
            setTimeout(() => {
                const timing = performance.timing;
                const loadTime = timing.loadEventEnd - timing.navigationStart;
                
                sendMetric({
                    name: 'PAGE_LOAD_TIME',
                    value: loadTime
                });
                
                // DOM Content Loaded
                const domContentLoaded = timing.domContentLoadedEventEnd - timing.navigationStart;
                sendMetric({
                    name: 'DOM_CONTENT_LOADED',
                    value: domContentLoaded
                });
                
                // Time to First Byte
                const ttfb = timing.responseStart - timing.requestStart;
                sendMetric({
                    name: 'TTFB',
                    value: ttfb
                });
                
            }, 0);
        });
    })();
    </script>
    <?php
}

/**
 * Handle performance logging
 */
function youtuneai_handle_performance_log() {
    if (!wp_verify_nonce($_POST['nonce'], 'youtuneai_performance')) {
        wp_die('Security check failed');
    }
    
    $metric = sanitize_text_field($_POST['metric']);
    $value = floatval($_POST['value']);
    $url = sanitize_text_field($_POST['url']);
    
    youtuneai_log_performance($metric, $value, array('url' => $url));
    
    wp_die();
}

/**
 * Combine CSS files for production
 */
function youtuneai_combine_css_files() {
    $css_files = array(
        YOUTUNEAI_THEME_DIR . '/assets/css/dist/main.css',
        YOUTUNEAI_THEME_DIR . '/style.css'
    );
    
    $combined_css = '';
    $newest_time = 0;
    
    foreach ($css_files as $file) {
        if (file_exists($file)) {
            $combined_css .= file_get_contents($file);
            $newest_time = max($newest_time, filemtime($file));
        }
    }
    
    $combined_file = YOUTUNEAI_THEME_DIR . '/assets/css/dist/combined.css';
    
    // Only regenerate if source files are newer
    if (!file_exists($combined_file) || filemtime($combined_file) < $newest_time) {
        // Minify CSS
        $combined_css = youtuneai_minify_css($combined_css);
        file_put_contents($combined_file, $combined_css);
    }
}

/**
 * Minify CSS
 */
function youtuneai_minify_css($css) {
    // Remove comments
    $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
    
    // Remove whitespace
    $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
    
    // Remove unnecessary spaces
    $css = preg_replace('/\s*{\s*/', '{', $css);
    $css = preg_replace('/;\s*}/', '}', $css);
    $css = preg_replace('/\s*;\s*/', ';', $css);
    $css = preg_replace('/\s*:\s*/', ':', $css);
    $css = preg_replace('/\s*,\s*/', ',', $css);
    
    return trim($css);
}

/**
 * Resource hints
 */
function youtuneai_add_resource_hints() {
    // Preconnect to external domains
    echo '<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>' . "\n";
    echo '<link rel="preconnect" href="https://www.google-analytics.com" crossorigin>' . "\n";
    echo '<link rel="preconnect" href="https://pagead2.googlesyndication.com" crossorigin>' . "\n";
    
    // Prefetch next likely page
    if (is_front_page()) {
        echo '<link rel="prefetch" href="' . home_url('/games') . '">' . "\n";
        echo '<link rel="prefetch" href="' . home_url('/garage') . '">' . "\n";
    }
}
add_action('wp_head', 'youtuneai_add_resource_hints', 1);