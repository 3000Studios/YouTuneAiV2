<?php
/**
 * SEO & Analytics Integration
 * 
 * @package YouTuneAI
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initialize SEO and Analytics
 */
function youtuneai_seo_analytics_init() {
    // SEO optimizations
    add_action('wp_head', 'youtuneai_seo_meta_tags');
    add_action('wp_head', 'youtuneai_structured_data');
    add_action('init', 'youtuneai_create_sitemap');
    
    // Analytics
    add_action('wp_head', 'youtuneai_analytics_head');
    add_action('wp_footer', 'youtuneai_analytics_footer');
    
    // Performance monitoring
    add_action('wp_footer', 'youtuneai_performance_monitoring');
    
    // AdSense integration
    add_action('wp_head', 'youtuneai_adsense_head');
    add_action('youtuneai_ad_slot', 'youtuneai_display_ad_slot');
    
    // Social media integration
    add_action('wp_head', 'youtuneai_social_meta_tags');
}
add_action('init', 'youtuneai_seo_analytics_init');

/**
 * Advanced SEO meta tags
 */
function youtuneai_seo_meta_tags() {
    global $post;
    
    // Basic meta tags
    echo '<meta name="generator" content="YouTuneAI Theme ' . YOUTUNEAI_VERSION . '">' . "\n";
    echo '<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">' . "\n";
    
    // Canonical URL
    $canonical = get_permalink();
    if (is_home() || is_front_page()) {
        $canonical = home_url();
    } elseif (is_category()) {
        $canonical = get_category_link(get_queried_object_id());
    } elseif (is_tag()) {
        $canonical = get_tag_link(get_queried_object_id());
    }
    echo '<link rel="canonical" href="' . esc_url($canonical) . '">' . "\n";
    
    // Performance hints
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    echo '<link rel="dns-prefetch" href="//cdnjs.cloudflare.com">' . "\n";
    
    // Page-specific meta
    if (is_singular()) {
        $description = get_the_excerpt($post->ID);
        if (empty($description)) {
            $description = wp_trim_words(strip_tags($post->post_content), 25);
        }
        
        echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
        echo '<meta name="keywords" content="' . esc_attr(youtuneai_get_post_keywords($post->ID)) . '">' . "\n";
        
        // Author meta
        $author = get_the_author_meta('display_name', $post->post_author);
        echo '<meta name="author" content="' . esc_attr($author) . '">' . "\n";
        
        // Publication dates
        echo '<meta name="article:published_time" content="' . get_the_date('c', $post->ID) . '">' . "\n";
        echo '<meta name="article:modified_time" content="' . get_the_modified_date('c', $post->ID) . '">' . "\n";
    }
    
    // Home page specific
    if (is_home() || is_front_page()) {
        $description = get_bloginfo('description') ?: 'Revolutionary AI-powered WordPress theme with 3D/VR capabilities, streaming, gaming, and comprehensive monetization features.';
        echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
        echo '<meta name="keywords" content="3D, VR, AI, gaming, streaming, WordPress theme, WebGL, Three.js">' . "\n";
    }
    
    // Viewport optimization
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">' . "\n";
    
    // Theme color for mobile browsers
    echo '<meta name="theme-color" content="#9d00ff">' . "\n";
    echo '<meta name="msapplication-TileColor" content="#9d00ff">' . "\n";
}

/**
 * Generate structured data (JSON-LD)
 */
function youtuneai_structured_data() {
    $schema = array();
    
    // Organization schema
    $schema[] = array(
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => get_bloginfo('name'),
        'url' => home_url(),
        'logo' => array(
            '@type' => 'ImageObject',
            'url' => get_theme_file_uri('/assets/img/logo.png'),
        ),
        'sameAs' => youtuneai_get_social_links(),
        'contactPoint' => array(
            '@type' => 'ContactPoint',
            'telephone' => youtuneai_get_option('phone_number'),
            'contactType' => 'Customer Service',
            'availableLanguage' => 'English'
        )
    );
    
    // Website schema
    $schema[] = array(
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => get_bloginfo('name'),
        'url' => home_url(),
        'potentialAction' => array(
            '@type' => 'SearchAction',
            'target' => array(
                '@type' => 'EntryPoint',
                'urlTemplate' => home_url('?s={search_term_string}')
            ),
            'query-input' => 'required name=search_term_string'
        )
    );
    
    // Page-specific schema
    if (is_singular()) {
        global $post;
        
        if ($post->post_type === 'game') {
            $schema[] = youtuneai_get_game_schema($post);
        } elseif ($post->post_type === 'stream') {
            $schema[] = youtuneai_get_stream_schema($post);
        } elseif (is_single()) {
            $schema[] = youtuneai_get_article_schema($post);
        }
    }
    
    // Product schema for WooCommerce
    if (function_exists('is_product') && is_product()) {
        global $product;
        $schema[] = youtuneai_get_product_schema($product);
    }
    
    // Output schema
    foreach ($schema as $schema_item) {
        echo '<script type="application/ld+json">' . wp_json_encode($schema_item, JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
    }
}

/**
 * Get game schema
 */
function youtuneai_get_game_schema($post) {
    $platform = get_post_meta($post->ID, 'platform', true);
    $screenshots = get_post_meta($post->ID, 'screenshots', true);
    
    return array(
        '@context' => 'https://schema.org',
        '@type' => 'VideoGame',
        'name' => get_the_title($post->ID),
        'description' => get_the_excerpt($post->ID),
        'url' => get_permalink($post->ID),
        'image' => get_the_post_thumbnail_url($post->ID, 'large'),
        'gamePlatform' => $platform ?: 'Web Browser',
        'genre' => wp_get_post_terms($post->ID, 'game_genre', array('fields' => 'names')),
        'datePublished' => get_the_date('c', $post->ID),
        'author' => array(
            '@type' => 'Organization',
            'name' => get_bloginfo('name')
        )
    );
}

/**
 * Get stream schema
 */
function youtuneai_get_stream_schema($post) {
    $platform = get_post_meta($post->ID, 'platform', true);
    $is_live = get_post_meta($post->ID, 'is_live', true);
    
    return array(
        '@context' => 'https://schema.org',
        '@type' => 'VideoObject',
        'name' => get_the_title($post->ID),
        'description' => get_the_excerpt($post->ID),
        'url' => get_permalink($post->ID),
        'thumbnailUrl' => get_the_post_thumbnail_url($post->ID, 'large'),
        'uploadDate' => get_the_date('c', $post->ID),
        'publisher' => array(
            '@type' => 'Organization',
            'name' => get_bloginfo('name')
        ),
        'isLiveBroadcast' => $is_live === '1'
    );
}

/**
 * Get article schema
 */
function youtuneai_get_article_schema($post) {
    return array(
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => get_the_title($post->ID),
        'description' => get_the_excerpt($post->ID),
        'url' => get_permalink($post->ID),
        'image' => get_the_post_thumbnail_url($post->ID, 'large'),
        'datePublished' => get_the_date('c', $post->ID),
        'dateModified' => get_the_modified_date('c', $post->ID),
        'author' => array(
            '@type' => 'Person',
            'name' => get_the_author_meta('display_name', $post->post_author)
        ),
        'publisher' => array(
            '@type' => 'Organization',
            'name' => get_bloginfo('name'),
            'logo' => array(
                '@type' => 'ImageObject',
                'url' => get_theme_file_uri('/assets/img/logo.png')
            )
        )
    );
}

/**
 * Get product schema
 */
function youtuneai_get_product_schema($product) {
    return array(
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $product->get_name(),
        'description' => wp_strip_all_tags($product->get_description()),
        'url' => get_permalink($product->get_id()),
        'image' => wp_get_attachment_url($product->get_image_id()),
        'sku' => $product->get_sku(),
        'offers' => array(
            '@type' => 'Offer',
            'price' => $product->get_price(),
            'priceCurrency' => get_woocommerce_currency(),
            'availability' => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            'seller' => array(
                '@type' => 'Organization',
                'name' => get_bloginfo('name')
            )
        ),
        'aggregateRating' => youtuneai_get_product_rating_schema($product)
    );
}

/**
 * Analytics head code
 */
function youtuneai_analytics_head() {
    $ga_id = youtuneai_get_option('google_analytics_id');
    
    if ($ga_id) {
        ?>
        <!-- Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr($ga_id); ?>"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '<?php echo esc_js($ga_id); ?>', {
                page_title: '<?php echo esc_js(wp_get_document_title()); ?>',
                page_location: '<?php echo esc_js(home_url(add_query_arg(array(), $_SERVER['REQUEST_URI']))); ?>'
            });
        </script>
        <?php
    }
    
    // Google Tag Manager
    $gtm_id = youtuneai_get_option('google_tag_manager_id');
    if ($gtm_id) {
        ?>
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','<?php echo esc_js($gtm_id); ?>');</script>
        <!-- End Google Tag Manager -->
        <?php
    }
}

/**
 * Analytics footer code
 */
function youtuneai_analytics_footer() {
    $gtm_id = youtuneai_get_option('google_tag_manager_id');
    
    if ($gtm_id) {
        ?>
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr($gtm_id); ?>"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
        <?php
    }
    
    // Custom event tracking
    ?>
    <script>
    // YouTuneAI Analytics Events
    document.addEventListener('DOMContentLoaded', function() {
        // Track 3D model interactions
        document.addEventListener('click', function(e) {
            if (e.target.closest('.youtuneai-avatar-container')) {
                gtag && gtag('event', '3d_avatar_interaction', {
                    event_category: '3D Content',
                    event_label: 'Avatar Interaction'
                });
            }
            
            if (e.target.closest('#garage-3d-canvas')) {
                gtag && gtag('event', '3d_garage_interaction', {
                    event_category: '3D Content',
                    event_label: 'Garage Configurator'
                });
            }
        });
        
        // Track VR mode activation
        document.addEventListener('click', function(e) {
            if (e.target.matches('[data-vr-enter]')) {
                gtag && gtag('event', 'vr_mode_enter', {
                    event_category: 'VR Experience',
                    event_label: e.target.dataset.vrEnter
                });
            }
        });
        
        // Track game plays
        document.addEventListener('click', function(e) {
            if (e.target.matches('[data-game-play]')) {
                gtag && gtag('event', 'game_play', {
                    event_category: 'Gaming',
                    event_label: 'Game Launch',
                    value: parseInt(e.target.dataset.gamePlay)
                });
            }
        });
        
        // Track outbound links
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (link && link.hostname !== window.location.hostname) {
                gtag && gtag('event', 'click', {
                    event_category: 'Outbound Link',
                    event_label: link.href,
                    transport_type: 'beacon'
                });
            }
        });
    });
    </script>
    <?php
}

/**
 * Performance monitoring
 */
function youtuneai_performance_monitoring() {
    ?>
    <script>
    // Web Vitals monitoring
    function sendToAnalytics(metric) {
        gtag && gtag('event', metric.name, {
            event_category: 'Web Vitals',
            value: Math.round(metric.name === 'CLS' ? metric.value * 1000 : metric.value),
            custom_parameter_1: metric.id,
            non_interaction: true,
        });
    }
    
    // Load web-vitals library and track metrics
    if (window.webVitals) {
        webVitals.getCLS(sendToAnalytics);
        webVitals.getFID(sendToAnalytics);
        webVitals.getFCP(sendToAnalytics);
        webVitals.getLCP(sendToAnalytics);
        webVitals.getTTFB(sendToAnalytics);
    }
    
    // Performance observer for 3D/VR content
    if ('PerformanceObserver' in window) {
        const observer = new PerformanceObserver((list) => {
            for (const entry of list.getEntries()) {
                if (entry.name.includes('three.js') || entry.name.includes('webxr')) {
                    gtag && gtag('event', '3d_performance', {
                        event_category: '3D Performance',
                        event_label: entry.name,
                        value: Math.round(entry.duration)
                    });
                }
            }
        });
        
        observer.observe({entryTypes: ['resource']});
    }
    </script>
    <?php
}

/**
 * AdSense integration
 */
function youtuneai_adsense_head() {
    $adsense_client = youtuneai_get_option('adsense_client_id');
    
    if ($adsense_client) {
        ?>
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=<?php echo esc_attr($adsense_client); ?>"
                crossorigin="anonymous"></script>
        <?php
    }
}

/**
 * Display ad slot
 */
function youtuneai_display_ad_slot($slot_name, $size = 'responsive') {
    $adsense_client = youtuneai_get_option('adsense_client_id');
    $ad_slots = youtuneai_get_option('adsense_slots', array());
    
    if (!$adsense_client || !isset($ad_slots[$slot_name])) {
        return;
    }
    
    $slot_id = $ad_slots[$slot_name];
    
    ?>
    <div class="youtuneai-ad-container mb-8">
        <div class="text-xs text-platinum/40 mb-2 text-center"><?php _e('Advertisement', 'youtuneai'); ?></div>
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="<?php echo esc_attr($adsense_client); ?>"
             data-ad-slot="<?php echo esc_attr($slot_id); ?>"
             data-ad-format="<?php echo esc_attr($size); ?>"></ins>
        <script>
             (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
    </div>
    <?php
}

/**
 * Social media meta tags
 */
function youtuneai_social_meta_tags() {
    global $post;
    
    $title = wp_get_document_title();
    $description = '';
    $image = '';
    $url = '';
    
    if (is_singular()) {
        $description = get_the_excerpt($post->ID);
        $image = get_the_post_thumbnail_url($post->ID, 'large');
        $url = get_permalink($post->ID);
    } else {
        $description = get_bloginfo('description');
        $image = get_theme_file_uri('/assets/img/social-share.jpg');
        $url = home_url();
    }
    
    // Open Graph tags
    echo '<meta property="og:title" content="' . esc_attr($title) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";
    echo '<meta property="og:image" content="' . esc_url($image) . '">' . "\n";
    echo '<meta property="og:url" content="' . esc_url($url) . '">' . "\n";
    echo '<meta property="og:type" content="' . (is_singular() ? 'article' : 'website') . '">' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
    
    // Twitter Card tags
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr($title) . '">' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr($description) . '">' . "\n";
    echo '<meta name="twitter:image" content="' . esc_url($image) . '">' . "\n";
    
    $twitter_username = youtuneai_get_option('twitter_username');
    if ($twitter_username) {
        echo '<meta name="twitter:site" content="@' . esc_attr($twitter_username) . '">' . "\n";
    }
}

/**
 * Generate XML sitemap
 */
function youtuneai_create_sitemap() {
    if (isset($_GET['youtuneai_sitemap']) && $_GET['youtuneai_sitemap'] === 'xml') {
        header('Content-Type: application/xml; charset=utf-8');
        
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        // Homepage
        $sitemap .= '<url>';
        $sitemap .= '<loc>' . home_url() . '</loc>';
        $sitemap .= '<changefreq>daily</changefreq>';
        $sitemap .= '<priority>1.0</priority>';
        $sitemap .= '</url>' . "\n";
        
        // Posts and pages
        $posts = get_posts(array(
            'post_type' => array('post', 'page', 'game', 'stream', 'product'),
            'post_status' => 'publish',
            'numberposts' => -1
        ));
        
        foreach ($posts as $post) {
            $sitemap .= '<url>';
            $sitemap .= '<loc>' . get_permalink($post->ID) . '</loc>';
            $sitemap .= '<lastmod>' . get_the_modified_date('c', $post->ID) . '</lastmod>';
            $sitemap .= '<changefreq>' . youtuneai_get_changefreq($post->post_type) . '</changefreq>';
            $sitemap .= '<priority>' . youtuneai_get_priority($post->post_type) . '</priority>';
            $sitemap .= '</url>' . "\n";
        }
        
        $sitemap .= '</urlset>';
        
        echo $sitemap;
        exit;
    }
}

/**
 * Helper functions
 */
function youtuneai_get_post_keywords($post_id) {
    $keywords = array();
    
    // Get tags
    $tags = get_the_tags($post_id);
    if ($tags) {
        foreach ($tags as $tag) {
            $keywords[] = $tag->name;
        }
    }
    
    // Get categories
    $categories = get_the_category($post_id);
    if ($categories) {
        foreach ($categories as $category) {
            $keywords[] = $category->name;
        }
    }
    
    // Add default keywords
    $keywords[] = '3D';
    $keywords[] = 'VR';
    $keywords[] = 'AI';
    $keywords[] = 'Gaming';
    
    return implode(', ', array_unique($keywords));
}

function youtuneai_get_social_links() {
    return array_filter(array(
        youtuneai_get_option('youtube_url'),
        youtuneai_get_option('twitter_url'),
        youtuneai_get_option('twitch_url'),
        youtuneai_get_option('discord_url'),
        youtuneai_get_option('github_url'),
    ));
}

function youtuneai_get_changefreq($post_type) {
    $frequencies = array(
        'post' => 'weekly',
        'page' => 'monthly',
        'game' => 'monthly',
        'stream' => 'weekly',
        'product' => 'weekly',
        'default' => 'monthly'
    );
    
    return $frequencies[$post_type] ?? $frequencies['default'];
}

function youtuneai_get_priority($post_type) {
    $priorities = array(
        'post' => '0.8',
        'page' => '0.6',
        'game' => '0.9',
        'stream' => '0.8',
        'product' => '0.9',
        'default' => '0.5'
    );
    
    return $priorities[$post_type] ?? $priorities['default'];
}

function youtuneai_get_product_rating_schema($product) {
    $rating = $product->get_average_rating();
    $review_count = $product->get_review_count();
    
    if ($rating && $review_count) {
        return array(
            '@type' => 'AggregateRating',
            'ratingValue' => $rating,
            'reviewCount' => $review_count,
            'bestRating' => '5',
            'worstRating' => '1'
        );
    }
    
    return null;
}

/**
 * Add sitemap to robots.txt
 */
function youtuneai_robots_txt($output) {
    $output .= "\nSitemap: " . home_url('?youtuneai_sitemap=xml');
    return $output;
}
add_filter('robots_txt', 'youtuneai_robots_txt');