<?php
/**
 * YouTuneAI Theme Functions
 * AI-Controlled Voice-Responsive WordPress Theme
 * 
 * Copyright (c) 2025 Boss Man J (3000Studios)
 * All rights reserved. Proprietary voice-controlled AI website technology.
 * 
 * This theme contains proprietary algorithms for:
 * - Real-time AI command processing via WordPress
 * - Voice-controlled content management system
 * - Automated deployment and file management
 * - AI-powered website modification engine
 * 
 * COMMERCIAL USE REQUIRES PAID LICENSE - Contact: mr.jwswain@gmail.com
 * Unauthorized use subject to legal action and monetary damages.
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Theme setup
function youtuneai_theme_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('html5', array('comment-list', 'comment-form', 'search-form', 'gallery', 'caption'));
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'youtuneai'),
        'footer' => __('Footer Menu', 'youtuneai'),
    ));
}
add_action('after_setup_theme', 'youtuneai_theme_setup');

// Enqueue scripts and styles
function youtuneai_scripts() {
    // Theme stylesheet
    wp_enqueue_style('youtuneai-style', get_stylesheet_uri(), array(), '1.0.0');
    
    // Google Fonts
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&display=swap', array(), null);
    
    // AOS Animation Library
    wp_enqueue_style('aos-css', 'https://unpkg.com/aos@2.3.1/dist/aos.css', array(), '2.3.1');
    wp_enqueue_script('aos-js', 'https://unpkg.com/aos@2.3.1/dist/aos.js', array(), '2.3.1', true);
    
    // jQuery (WordPress includes it)
    wp_enqueue_script('jquery');
    
    // Main theme JavaScript
    wp_enqueue_script('youtuneai-main', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), '1.0.0', true);
    
    // Localize script for AJAX
    wp_localize_script('youtuneai-main', 'youtuneai_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('youtuneai_nonce'),
        'site_url' => home_url()
    ));
}
add_action('wp_enqueue_scripts', 'youtuneai_scripts');

// 🧠 AI COMMAND PROCESSING - THE HEART OF THE SYSTEM
function process_ai_command() {
    // Verify nonce for security
    if (!wp_verify_nonce($_POST['nonce'] ?? '', 'youtuneai_nonce')) {
        wp_die('Security check failed');
    }
    
    $command = sanitize_text_field($_POST['command'] ?? '');
    
    if (empty($command)) {
        wp_send_json_error('No command provided');
    }
    
    // OpenAI API Key (stored securely)
    $openai_key = get_option('youtuneai_openai_key', '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');
    
    try {
        // Process command with AI
        $result = process_command_with_ai($command, $openai_key);
        
        if ($result['success']) {
            // Execute the command
            $execution_result = execute_ai_command($result['action'], $result['parameters']);
            
            if ($execution_result['success']) {
                wp_send_json_success(array(
                    'message' => $execution_result['message'],
                    'reload' => $execution_result['reload'] ?? false
                ));
            } else {
                wp_send_json_error($execution_result['message']);
            }
        } else {
            wp_send_json_error($result['message']);
        }
        
    } catch (Exception $e) {
        wp_send_json_error('AI processing failed: ' . $e->getMessage());
    }
}
add_action('wp_ajax_process_ai_command', 'process_ai_command');
add_action('wp_ajax_nopriv_process_ai_command', 'process_ai_command');

// AI Command Processor
function process_command_with_ai($command, $api_key) {
    $prompt = "
    You are an AI assistant that processes website update commands. 
    Convert the following user command into a structured action:
    
    Command: '$command'
    
    Available actions:
    - change_background_video: Change the homepage background video
    - update_content: Update page content
    - add_product: Add a new product to the shop
    - change_colors: Update theme colors
    - update_nav: Update navigation menu
    - add_page: Create a new page
    
    Respond with JSON only:
    {
        \"action\": \"action_name\",
        \"parameters\": {
            \"key\": \"value\"
        }
    }
    ";
    
    $data = array(
        'model' => 'gpt-3.5-turbo',
        'messages' => array(
            array(
                'role' => 'system',
                'content' => 'You are a website command processor. Respond with JSON only.'
            ),
            array(
                'role' => 'user',
                'content' => $prompt
            )
        ),
        'max_tokens' => 500,
        'temperature' => 0.3
    );
    
    $response = wp_remote_post('https://api.openai.com/v1/chat/completions', array(
        'headers' => array(
            'Authorization' => 'Bearer ' . $api_key,
            'Content-Type' => 'application/json'
        ),
        'body' => json_encode($data),
        'timeout' => 30
    ));
    
    if (is_wp_error($response)) {
        return array('success' => false, 'message' => 'API request failed');
    }
    
    $body = wp_remote_retrieve_body($response);
    $decoded = json_decode($body, true);
    
    if (!isset($decoded['choices'][0]['message']['content'])) {
        return array('success' => false, 'message' => 'Invalid API response');
    }
    
    $ai_response = json_decode($decoded['choices'][0]['message']['content'], true);
    
    if (!$ai_response) {
        return array('success' => false, 'message' => 'Could not parse AI response');
    }
    
    return array(
        'success' => true,
        'action' => $ai_response['action'],
        'parameters' => $ai_response['parameters']
    );
}

// Execute AI Commands
function execute_ai_command($action, $parameters) {
    switch ($action) {
        case 'change_background_video':
            return change_background_video($parameters);
            
        case 'update_content':
            return update_page_content($parameters);
            
        case 'add_product':
            return add_new_product($parameters);
            
        case 'change_colors':
            return update_theme_colors($parameters);
            
        case 'update_nav':
            return update_navigation($parameters);
            
        case 'add_page':
            return create_new_page($parameters);
            
        default:
            return array('success' => false, 'message' => 'Unknown action: ' . $action);
    }
}

// Action: Change Background Video
function change_background_video($params) {
    $video_query = $params['query'] ?? $params['video'] ?? 'space';
    
    // Get video URL from AI or use predefined videos
    $video_urls = array(
        'space' => 'https://www.learningcontainer.com/wp-content/uploads/2020/05/sample-mp4-file.mp4',
        'ocean' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_1280x720_1mb.mp4',
        'city' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_1280x720_2mb.mp4',
        'nature' => 'https://sample-videos.com/zip/10/mp4/SampleVideo_1280x720_5mb.mp4'
    );
    
    $video_url = $video_urls[$video_query] ?? $video_urls['space'];
    
    // Update the custom option
    update_option('youtuneai_bg_video', $video_url);
    
    return array(
        'success' => true,
        'message' => "Background video changed to $video_query theme",
        'reload' => true
    );
}

// Action: Update Page Content
function update_page_content($params) {
    $page_id = $params['page_id'] ?? get_option('page_on_front');
    $content = $params['content'] ?? 'Updated content';
    
    $result = wp_update_post(array(
        'ID' => $page_id,
        'post_content' => $content
    ));
    
    if ($result) {
        return array(
            'success' => true,
            'message' => 'Page content updated successfully',
            'reload' => true
        );
    } else {
        return array('success' => false, 'message' => 'Failed to update content');
    }
}

// Action: Add New Product
function add_new_product($params) {
    $title = $params['title'] ?? 'New Product';
    $price = $params['price'] ?? '9.99';
    $description = $params['description'] ?? 'Product description';
    
    $post_id = wp_insert_post(array(
        'post_title' => $title,
        'post_content' => $description,
        'post_status' => 'publish',
        'post_type' => 'product',
        'meta_input' => array(
            'product_price' => $price,
            'product_type' => 'digital'
        )
    ));
    
    if ($post_id) {
        return array(
            'success' => true,
            'message' => "Product '$title' added successfully for $$price"
        );
    } else {
        return array('success' => false, 'message' => 'Failed to add product');
    }
}

// Action: Update Theme Colors
function update_theme_colors($params) {
    $primary_color = $params['primary'] ?? '#00d4ff';
    $secondary_color = $params['secondary'] ?? '#ff00ff';
    
    update_option('youtuneai_primary_color', $primary_color);
    update_option('youtuneai_secondary_color', $secondary_color);
    
    return array(
        'success' => true,
        'message' => 'Theme colors updated',
        'reload' => true
    );
}

// Action: Create New Page
function create_new_page($params) {
    $title = $params['title'] ?? 'New Page';
    $content = $params['content'] ?? 'Page content';
    
    $post_id = wp_insert_post(array(
        'post_title' => $title,
        'post_content' => $content,
        'post_status' => 'publish',
        'post_type' => 'page'
    ));
    
    if ($post_id) {
        return array(
            'success' => true,
            'message' => "Page '$title' created successfully"
        );
    } else {
        return array('success' => false, 'message' => 'Failed to create page');
    }
}

// Get dynamic background video
function get_background_video_url() {
    return get_option('youtuneai_bg_video', get_template_directory_uri() . '/assets/video/background.mp4');
}

// Get dynamic theme colors
function get_theme_colors() {
    return array(
        'primary' => get_option('youtuneai_primary_color', '#00d4ff'),
        'secondary' => get_option('youtuneai_secondary_color', '#ff00ff')
    );
}

// Custom Post Type: Products
function create_product_post_type() {
    register_post_type('product', array(
        'labels' => array(
            'name' => 'Products',
            'singular_name' => 'Product'
        ),
        'public' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'menu_icon' => 'dashicons-cart'
    ));
}
add_action('init', 'create_product_post_type');

// 💰 MONETIZATION SETUP
// PayPal Integration
function setup_paypal_integration() {
    // PayPal configuration
    $paypal_config = array(
        'client_id' => get_option('youtuneai_paypal_client_id', 'your-paypal-client-id'),
        'client_secret' => get_option('youtuneai_paypal_secret', 'your-paypal-secret'),
        'mode' => get_option('youtuneai_paypal_mode', 'sandbox') // sandbox or live
    );
    
    return $paypal_config;
}

// Stripe Integration
function setup_stripe_integration() {
    // Stripe configuration
    $stripe_config = array(
        'publishable_key' => get_option('youtuneai_stripe_publishable', 'pk_test_your-key'),
        'secret_key' => get_option('youtuneai_stripe_secret', 'sk_test_your-key')
    );
    
    return $stripe_config;
}

// Process Payment
function process_payment() {
    // Payment processing logic
    $payment_method = $_POST['payment_method'] ?? 'paypal';
    $amount = floatval($_POST['amount'] ?? 0);
    $product_id = intval($_POST['product_id'] ?? 0);
    
    if ($amount <= 0) {
        wp_send_json_error('Invalid amount');
    }
    
    switch($payment_method) {
        case 'paypal':
            return process_paypal_payment($amount, $product_id);
        case 'stripe':
            return process_stripe_payment($amount, $product_id);
        default:
            wp_send_json_error('Invalid payment method');
    }
}
add_action('wp_ajax_process_payment', 'process_payment');
add_action('wp_ajax_nopriv_process_payment', 'process_payment');

// Admin Settings Page
function youtuneai_admin_menu() {
    add_theme_page(
        'YouTuneAI Settings',
        'AI Settings',
        'manage_options',
        'youtuneai-settings',
        'youtuneai_settings_page'
    );
}
add_action('admin_menu', 'youtuneai_admin_menu');

function youtuneai_settings_page() {
    if (isset($_POST['submit'])) {
        update_option('youtuneai_openai_key', sanitize_text_field($_POST['openai_key']));
        update_option('youtuneai_paypal_client_id', sanitize_text_field($_POST['paypal_client_id']));
        update_option('youtuneai_paypal_secret', sanitize_text_field($_POST['paypal_secret']));
        update_option('youtuneai_stripe_publishable', sanitize_text_field($_POST['stripe_publishable']));
        update_option('youtuneai_stripe_secret', sanitize_text_field($_POST['stripe_secret']));
        echo '<div class="notice notice-success"><p>Settings saved!</p></div>';
    }
    
    $openai_key = get_option('youtuneai_openai_key', '');
    $paypal_client_id = get_option('youtuneai_paypal_client_id', '');
    $paypal_secret = get_option('youtuneai_paypal_secret', '');
    $stripe_publishable = get_option('youtuneai_stripe_publishable', '');
    $stripe_secret = get_option('youtuneai_stripe_secret', '');
    
    ?>
    <div class="wrap">
        <h1>YouTuneAI Settings</h1>
        <form method="post">
            <table class="form-table">
                <tr>
                    <th scope="row">OpenAI API Key</th>
                    <td><input type="text" name="openai_key" value="<?php echo esc_attr($openai_key); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row">PayPal Client ID</th>
                    <td><input type="text" name="paypal_client_id" value="<?php echo esc_attr($paypal_client_id); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row">PayPal Secret</th>
                    <td><input type="password" name="paypal_secret" value="<?php echo esc_attr($paypal_secret); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row">Stripe Publishable Key</th>
                    <td><input type="text" name="stripe_publishable" value="<?php echo esc_attr($stripe_publishable); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th scope="row">Stripe Secret Key</th>
                    <td><input type="password" name="stripe_secret" value="<?php echo esc_attr($stripe_secret); ?>" class="regular-text" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// 🎵 Background Music Rotation
function get_background_music_playlist() {
    $playlist = array(
        get_template_directory_uri() . '/assets/audio/corporate-1.mp3',
        get_template_directory_uri() . '/assets/audio/corporate-2.mp3',
        get_template_directory_uri() . '/assets/audio/corporate-3.mp3',
        // Add more tracks as needed
    );
    
    return $playlist;
}

// Dynamic CSS for theme colors
function youtuneai_dynamic_css() {
    $colors = get_theme_colors();
    ?>
    <style>
    :root {
        --primary-color: <?php echo $colors['primary']; ?>;
        --secondary-color: <?php echo $colors['secondary']; ?>;
    }
    </style>
    <?php
}
add_action('wp_head', 'youtuneai_dynamic_css');

// Security: Sanitize all inputs
function youtuneai_sanitize_input($input) {
    return sanitize_text_field(trim($input));
}

// Logging for AI commands
function log_ai_command($command, $result) {
    $log_entry = array(
        'timestamp' => current_time('mysql'),
        'command' => $command,
        'result' => $result,
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
    );
    
    $logs = get_option('youtuneai_ai_logs', array());
    $logs[] = $log_entry;
    
    // Keep only last 100 entries
    if (count($logs) > 100) {
        $logs = array_slice($logs, -100);
    }
    
    update_option('youtuneai_ai_logs', $logs);
}

// Get AI command logs
function get_ai_command_logs() {
    return get_option('youtuneai_ai_logs', array());
}

?>
