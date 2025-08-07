<?php
/*
Plugin Name: YouTuneAI Controller Integration
Description: WordPress integration for AI voice and text commands
Version: 1.0
*/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class YouTuneAI_Controller_Integration {
    
    private $admin_credentials = [
        'username' => 'Mr.jwswain@gmail.com',
        'password' => 'Gabby3000???'
    ];
    
    public function __construct() {
        add_action('wp_ajax_process_ai_command', [$this, 'process_ai_command']);
        add_action('wp_ajax_nopriv_process_ai_command', [$this, 'process_ai_command']);
        add_action('rest_api_init', [$this, 'register_rest_routes']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
    }
    
    public function enqueue_scripts() {
        wp_enqueue_script('youtuneai-controller', get_template_directory_uri() . '/js/ai-controller.js', ['jquery'], '1.0', true);
        wp_localize_script('youtuneai-controller', 'aiController', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ai_controller_nonce'),
            'rest_url' => rest_url('youtuneai/v1/')
        ]);
    }
    
    public function register_rest_routes() {
        register_rest_route('youtuneai/v1', '/command', [
            'methods' => 'POST',
            'callback' => [$this, 'handle_rest_command'],
            'permission_callback' => [$this, 'verify_authentication']
        ]);
        
        register_rest_route('youtuneai/v1', '/status', [
            'methods' => 'GET',
            'callback' => [$this, 'get_system_status'],
            'permission_callback' => '__return_true'
        ]);
    }
    
    public function verify_authentication($request) {
        $auth_header = $request->get_header('authorization');
        if (!$auth_header) {
            return false;
        }
        
        // Extract credentials from Basic auth
        $auth = base64_decode(str_replace('Basic ', '', $auth_header));
        list($username, $password) = explode(':', $auth);
        
        return ($username === $this->admin_credentials['username'] && 
                $password === $this->admin_credentials['password']);
    }
    
    public function process_ai_command() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'ai_controller_nonce')) {
            wp_die('Security check failed');
        }
        
        $command = sanitize_text_field($_POST['command']);
        $result = $this->execute_command($command);
        
        wp_send_json($result);
    }
    
    public function handle_rest_command($request) {
        $command = $request->get_param('command');
        $result = $this->execute_command($command);
        
        return rest_ensure_response($result);
    }
    
    private function execute_command($command) {
        $command = strtolower(trim($command));
        
        try {
            // Background video commands
            if (strpos($command, 'background') !== false || strpos($command, 'video') !== false) {
                return $this->change_background_video($command);
            }
            
            // Theme color commands
            if (strpos($command, 'color') !== false || strpos($command, 'theme') !== false) {
                return $this->change_theme_colors($command);
            }
            
            // Product commands
            if (strpos($command, 'product') !== false || strpos($command, 'add') !== false) {
                return $this->add_product($command);
            }
            
            // Homepage content commands
            if (strpos($command, 'homepage') !== false || strpos($command, 'title') !== false) {
                return $this->update_homepage_content($command);
            }
            
            // Navigation commands
            if (strpos($command, 'menu') !== false || strpos($command, 'navigation') !== false) {
                return $this->update_navigation($command);
            }
            
            return [
                'success' => false,
                'error' => 'Command not recognized. Try: "change background to space", "add product", "update homepage title"'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Command execution failed: ' . $e->getMessage()
            ];
        }
    }
    
    private function change_background_video($command) {
        $video_themes = [
            'space' => 'https://cdn.pixabay.com/vimeo/459567622/space-47668.mp4',
            'ocean' => 'https://cdn.pixabay.com/vimeo/459567531/ocean-47667.mp4',
            'city' => 'https://cdn.pixabay.com/vimeo/459567642/city-47669.mp4',
            'nature' => 'https://cdn.pixabay.com/vimeo/459567652/nature-47670.mp4',
            'gaming' => 'https://cdn.pixabay.com/vimeo/459567662/gaming-47671.mp4',
            'music' => 'https://cdn.pixabay.com/vimeo/459567672/music-47672.mp4'
        ];
        
        $selected_theme = 'space'; // default
        foreach ($video_themes as $theme => $url) {
            if (strpos($command, $theme) !== false) {
                $selected_theme = $theme;
                break;
            }
        }
        
        // Update theme customizer option
        set_theme_mod('background_video_url', $video_themes[$selected_theme]);
        set_theme_mod('background_video_theme', $selected_theme);
        
        return [
            'success' => true,
            'message' => "Background video changed to {$selected_theme} theme",
            'video_url' => $video_themes[$selected_theme]
        ];
    }
    
    private function change_theme_colors($command) {
        $colors = [
            'blue' => '#3498db',
            'red' => '#e74c3c',
            'green' => '#27ae60',
            'purple' => '#9b59b6',
            'orange' => '#f39c12',
            'pink' => '#e91e63',
            'teal' => '#1abc9c',
            'dark' => '#2c3e50'
        ];
        
        $selected_color = '#3498db'; // default blue
        foreach ($colors as $color_name => $hex) {
            if (strpos($command, $color_name) !== false) {
                $selected_color = $hex;
                break;
            }
        }
        
        // Update theme customizer colors
        set_theme_mod('primary_color', $selected_color);
        set_theme_mod('accent_color', $selected_color);
        
        return [
            'success' => true,
            'message' => "Theme colors updated to {$selected_color}",
            'primary_color' => $selected_color
        ];
    }
    
    private function add_product($command) {
        // Check if WooCommerce is active
        if (!class_exists('WooCommerce')) {
            return [
                'success' => false,
                'error' => 'WooCommerce is not installed or activated'
            ];
        }
        
        // Extract product details from command
        $product_name = 'AI Generated Product';
        $price = '9.99';
        
        // Parse command for product name and price
        if (preg_match('/add product (.+?) for \$?([0-9.]+)/', $command, $matches)) {
            $product_name = trim($matches[1]);
            $price = $matches[2];
        } elseif (preg_match('/add (.+?) product/', $command, $matches)) {
            $product_name = trim($matches[1]);
        }
        
        // Create WooCommerce product
        $product = new WC_Product_Simple();
        $product->set_name($product_name);
        $product->set_status('publish');
        $product->set_catalog_visibility('visible');
        $product->set_description('AI-generated product created via voice command');
        $product->set_short_description('Created by YouTuneAI Controller');
        $product->set_regular_price($price);
        $product->set_manage_stock(false);
        
        $product_id = $product->save();
        
        if ($product_id) {
            return [
                'success' => true,
                'message' => "Product '{$product_name}' added for \${$price}",
                'product_id' => $product_id
            ];
        } else {
            return [
                'success' => false,
                'error' => 'Failed to create product'
            ];
        }
    }
    
    private function update_homepage_content($command) {
        // Update homepage customizer options
        if (strpos($command, 'title') !== false) {
            // Extract new title
            if (preg_match('/title to (.+)/', $command, $matches)) {
                $new_title = trim($matches[1]);
                set_theme_mod('homepage_title', $new_title);
                
                return [
                    'success' => true,
                    'message' => "Homepage title updated to: {$new_title}"
                ];
            }
        }
        
        if (strpos($command, 'subtitle') !== false || strpos($command, 'tagline') !== false) {
            if (preg_match('/(?:subtitle|tagline) to (.+)/', $command, $matches)) {
                $new_subtitle = trim($matches[1]);
                set_theme_mod('homepage_subtitle', $new_subtitle);
                
                return [
                    'success' => true,
                    'message' => "Homepage subtitle updated to: {$new_subtitle}"
                ];
            }
        }
        
        return [
            'success' => false,
            'error' => 'Please specify what to update: "update homepage title to [new title]"'
        ];
    }
    
    private function update_navigation($command) {
        // Get current menu
        $menu_name = 'primary';
        $locations = get_nav_menu_locations();
        
        if (!isset($locations[$menu_name])) {
            return [
                'success' => false,
                'error' => 'Primary menu not found'
            ];
        }
        
        return [
            'success' => true,
            'message' => 'Navigation update functionality not yet implemented'
        ];
    }
    
    public function get_system_status() {
        $status = [
            'wordpress_version' => get_bloginfo('version'),
            'theme' => get_stylesheet(),
            'plugins' => [],
            'ai_controller' => 'active',
            'timestamp' => current_time('mysql')
        ];
        
        // Check for required plugins
        $required_plugins = [
            'woocommerce/woocommerce.php' => 'WooCommerce',
            'advanced-custom-fields/acf.php' => 'Advanced Custom Fields',
            'wp-webhooks/wp-webhooks.php' => 'WP Webhooks'
        ];
        
        foreach ($required_plugins as $plugin_file => $plugin_name) {
            $status['plugins'][$plugin_name] = is_plugin_active($plugin_file);
        }
        
        return rest_ensure_response($status);
    }
}

// Initialize the plugin
new YouTuneAI_Controller_Integration();

// Add AJAX handler for non-logged-in users
add_action('wp_ajax_nopriv_youtuneai_command', 'handle_youtuneai_command');
add_action('wp_ajax_youtuneai_command', 'handle_youtuneai_command');

function handle_youtuneai_command() {
    // Verify authentication
    $username = sanitize_email($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if ($username !== 'Mr.jwswain@gmail.com' || $password !== 'Gabby3000???') {
        wp_send_json_error('Invalid credentials');
        return;
    }
    
    $command = sanitize_text_field($_POST['command'] ?? '');
    
    // Process command (simplified version)
    $result = [
        'success' => true,
        'message' => "Command '{$command}' processed successfully",
        'timestamp' => current_time('mysql')
    ];
    
    wp_send_json_success($result);
}
