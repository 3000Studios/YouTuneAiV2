<?php
/**
 * YouTuneAI Theme Functions
 * 
 * @package YouTuneAI
 * @version 1.0.0
 * @author 3000Studios
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Theme version
define('YOUTUNEAI_VERSION', '1.0.0');
define('YOUTUNEAI_THEME_DIR', get_template_directory());
define('YOUTUNEAI_THEME_URL', get_template_directory_uri());

/**
 * Theme setup
 */
function youtuneai_theme_setup() {
    // Add theme support for FSE
    add_theme_support('block-templates');
    add_theme_support('block-template-parts');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('html5', array(
        'comment-list', 
        'comment-form', 
        'search-form', 
        'gallery', 
        'caption',
        'style',
        'script'
    ));
    add_theme_support('custom-header');
    add_theme_support('custom-background');
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    // Load text domain
    load_theme_textdomain('youtuneai', YOUTUNEAI_THEME_DIR . '/languages');
}
add_action('after_setup_theme', 'youtuneai_theme_setup');

/**
 * Enqueue scripts and styles
 */
function youtuneai_enqueue_assets() {
    // Main theme stylesheet
    wp_enqueue_style(
        'youtuneai-style',
        get_stylesheet_uri(),
        array(),
        YOUTUNEAI_VERSION
    );

    // Tailwind CSS
    wp_enqueue_style(
        'youtuneai-tailwind',
        YOUTUNEAI_THEME_URL . '/assets/css/dist/main.css',
        array(),
        YOUTUNEAI_VERSION
    );

    // Google Fonts
    wp_enqueue_style(
        'youtuneai-fonts',
        'https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;900&family=Raleway:wght@400;500;600;700;800&family=Martian+Mono:wght@100..800&display=swap',
        array(),
        null
    );

    // Boxicons
    wp_enqueue_style(
        'boxicons',
        'https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css',
        array(),
        '2.1.4'
    );

    // Main JavaScript
    wp_enqueue_script(
        'youtuneai-main',
        YOUTUNEAI_THEME_URL . '/assets/js/dist/main.js',
        array('jquery'),
        YOUTUNEAI_VERSION,
        true
    );

    // Three.js for 3D features
    wp_enqueue_script(
        'three-js',
        'https://cdnjs.cloudflare.com/ajax/libs/three.js/r157/three.min.js',
        array(),
        '157',
        true
    );

    // Localize script for AJAX
    wp_localize_script('youtuneai-main', 'youtuneai_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('youtuneai_nonce'),
        'theme_url' => YOUTUNEAI_THEME_URL
    ));
}
add_action('wp_enqueue_scripts', 'youtuneai_enqueue_assets');

/**
 * Register custom post types
 */
function youtuneai_register_post_types() {
    // Games post type
    register_post_type('game', array(
        'labels' => array(
            'name' => __('Games', 'youtuneai'),
            'singular_name' => __('Game', 'youtuneai'),
            'add_new_item' => __('Add New Game', 'youtuneai'),
            'edit_item' => __('Edit Game', 'youtuneai'),
            'new_item' => __('New Game', 'youtuneai'),
            'view_item' => __('View Game', 'youtuneai'),
            'search_items' => __('Search Games', 'youtuneai'),
            'not_found' => __('No games found', 'youtuneai'),
        ),
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'menu_icon' => 'dashicons-games',
        'rewrite' => array('slug' => 'games'),
    ));

    // Streams post type
    register_post_type('stream', array(
        'labels' => array(
            'name' => __('Streams', 'youtuneai'),
            'singular_name' => __('Stream', 'youtuneai'),
            'add_new_item' => __('Add New Stream', 'youtuneai'),
            'edit_item' => __('Edit Stream', 'youtuneai'),
            'new_item' => __('New Stream', 'youtuneai'),
            'view_item' => __('View Stream', 'youtuneai'),
        ),
        'public' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'menu_icon' => 'dashicons-video-alt3',
        'rewrite' => array('slug' => 'streams'),
    ));

    // Avatar post type
    register_post_type('avatar', array(
        'labels' => array(
            'name' => __('Avatars', 'youtuneai'),
            'singular_name' => __('Avatar', 'youtuneai'),
            'add_new_item' => __('Add New Avatar', 'youtuneai'),
            'edit_item' => __('Edit Avatar', 'youtuneai'),
        ),
        'public' => true,
        'show_in_rest' => true,
        'supports' => array('title', 'thumbnail', 'custom-fields'),
        'menu_icon' => 'dashicons-admin-users',
        'rewrite' => array('slug' => 'avatars'),
    ));

    // VR Room post type
    register_post_type('vr_room', array(
        'labels' => array(
            'name' => __('VR Rooms', 'youtuneai'),
            'singular_name' => __('VR Room', 'youtuneai'),
            'add_new_item' => __('Add New VR Room', 'youtuneai'),
            'edit_item' => __('Edit VR Room', 'youtuneai'),
        ),
        'public' => true,
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'menu_icon' => 'dashicons-visibility',
        'rewrite' => array('slug' => 'vr-rooms'),
    ));

    // Garage Parts post type
    register_post_type('garage_part', array(
        'labels' => array(
            'name' => __('Garage Parts', 'youtuneai'),
            'singular_name' => __('Garage Part', 'youtuneai'),
            'add_new_item' => __('Add New Garage Part', 'youtuneai'),
            'edit_item' => __('Edit Garage Part', 'youtuneai'),
        ),
        'public' => true,
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'menu_icon' => 'dashicons-hammer',
        'rewrite' => array('slug' => 'garage-parts'),
    ));
}
add_action('init', 'youtuneai_register_post_types');

/**
 * Register taxonomies
 */
function youtuneai_register_taxonomies() {
    // Game genres
    register_taxonomy('game_genre', 'game', array(
        'labels' => array(
            'name' => __('Game Genres', 'youtuneai'),
            'singular_name' => __('Game Genre', 'youtuneai'),
        ),
        'public' => true,
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'game-genres'),
    ));

    // Part types
    register_taxonomy('part_type', 'garage_part', array(
        'labels' => array(
            'name' => __('Part Types', 'youtuneai'),
            'singular_name' => __('Part Type', 'youtuneai'),
        ),
        'public' => true,
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'part-types'),
    ));
}
add_action('init', 'youtuneai_register_taxonomies');

/**
 * Include additional files
 */
require_once YOUTUNEAI_THEME_DIR . '/includes/admin-control-center.php';
require_once YOUTUNEAI_THEME_DIR . '/includes/rest-endpoints.php';
require_once YOUTUNEAI_THEME_DIR . '/includes/avatar-system.php';
require_once YOUTUNEAI_THEME_DIR . '/includes/helpers.php';
require_once YOUTUNEAI_THEME_DIR . '/includes/woocommerce-integration.php';
require_once YOUTUNEAI_THEME_DIR . '/includes/seo-analytics.php';

/**
 * Block patterns
 */
function youtuneai_register_block_patterns() {
    // Register pattern category
    register_block_pattern_category(
        'youtuneai',
        array('label' => __('YouTuneAI', 'youtuneai'))
    );
}
add_action('init', 'youtuneai_register_block_patterns');

/**
 * Add editor styles
 */
function youtuneai_add_editor_styles() {
    add_theme_support('editor-styles');
    add_editor_style('assets/css/dist/main.css');
}
add_action('after_setup_theme', 'youtuneai_add_editor_styles');