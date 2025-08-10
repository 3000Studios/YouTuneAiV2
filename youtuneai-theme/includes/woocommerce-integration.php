<?php
/**
 * WooCommerce Integration
 * 
 * @package YouTuneAI
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initialize WooCommerce integration
 */
function youtuneai_woocommerce_init() {
    if (!class_exists('WooCommerce')) {
        add_action('admin_notices', 'youtuneai_woocommerce_missing_notice');
        return;
    }
    
    // Theme support
    add_theme_support('woocommerce', array(
        'thumbnail_image_width' => 400,
        'single_image_width' => 600,
        'product_grid' => array(
            'default_rows' => 3,
            'min_rows' => 1,
            'default_columns' => 4,
            'min_columns' => 1,
            'max_columns' => 6,
        ),
    ));
    
    // Remove WooCommerce styling
    add_filter('woocommerce_enqueue_styles', '__return_empty_array');
    
    // Custom hooks
    add_action('init', 'youtuneai_create_composite_products');
    add_action('woocommerce_before_shop_loop', 'youtuneai_shop_custom_header');
    add_filter('woocommerce_product_add_to_cart_text', 'youtuneai_custom_add_to_cart_text', 10, 2);
    add_action('woocommerce_after_single_product_summary', 'youtuneai_product_3d_viewer', 15);
    add_filter('woocommerce_checkout_fields', 'youtuneai_custom_checkout_fields');
    
    // Garage configurator integration
    add_action('wp_ajax_youtuneai_create_garage_product', 'youtuneai_create_garage_product');
    add_action('wp_ajax_nopriv_youtuneai_create_garage_product', 'youtuneai_create_garage_product');
}
add_action('after_setup_theme', 'youtuneai_woocommerce_init');

/**
 * WooCommerce missing notice
 */
function youtuneai_woocommerce_missing_notice() {
    ?>
    <div class="notice notice-warning">
        <p><?php _e('YouTuneAI Theme requires WooCommerce to be installed and active for full e-commerce functionality.', 'youtuneai'); ?></p>
    </div>
    <?php
}

/**
 * Create composite products for garage configurations
 */
function youtuneai_create_composite_products() {
    if (!class_exists('WC_Product_Composite')) {
        return;
    }
    
    // Check if base garage product exists
    $garage_product_id = get_option('youtuneai_garage_base_product');
    
    if (!$garage_product_id || !get_post($garage_product_id)) {
        $garage_product_id = youtuneai_create_base_garage_product();
        update_option('youtuneai_garage_base_product', $garage_product_id);
    }
}

/**
 * Create base garage product
 */
function youtuneai_create_base_garage_product() {
    $product = new WC_Product_Variable();
    
    $product->set_name('Custom Vehicle Configuration');
    $product->set_slug('custom-vehicle-config');
    $product->set_description('Build your dream vehicle with our 3D configurator. Choose from thousands of parts and see your creation in real-time.');
    $product->set_short_description('Customize your perfect ride with real-time 3D visualization.');
    $product->set_status('publish');
    $product->set_catalog_visibility('visible');
    $product->set_price(25000);
    $product->set_regular_price(25000);
    $product->set_manage_stock(false);
    $product->set_stock_status('instock');
    $product->set_virtual(true);
    
    // Add custom fields
    $product->add_meta_data('_youtuneai_product_type', 'garage_configuration');
    $product->add_meta_data('_youtuneai_3d_model', 'base-vehicle.glb');
    
    $product_id = $product->save();
    
    // Create default variations
    youtuneai_create_garage_variations($product_id);
    
    return $product_id;
}

/**
 * Create variations for garage product
 */
function youtuneai_create_garage_variations($product_id) {
    // Body type attribute
    $body_attribute = new WC_Product_Attribute();
    $body_attribute->set_id(0);
    $body_attribute->set_name('Body Type');
    $body_attribute->set_options(array('Sport', 'Luxury', 'Off-Road'));
    $body_attribute->set_visible(true);
    $body_attribute->set_variation(true);
    
    // Engine attribute
    $engine_attribute = new WC_Product_Attribute();
    $engine_attribute->set_id(0);
    $engine_attribute->set_name('Engine');
    $engine_attribute->set_options(array('V6 Turbo', 'V8', 'Electric'));
    $engine_attribute->set_visible(true);
    $engine_attribute->set_variation(true);
    
    $product = wc_get_product($product_id);
    $product->set_attributes(array($body_attribute, $engine_attribute));
    $product->save();
    
    // Create variations
    $variations = array(
        array('Body Type' => 'Sport', 'Engine' => 'V6 Turbo', 'price' => 35000),
        array('Body Type' => 'Sport', 'Engine' => 'V8', 'price' => 42000),
        array('Body Type' => 'Luxury', 'Engine' => 'V8', 'price' => 55000),
        array('Body Type' => 'Luxury', 'Engine' => 'Electric', 'price' => 65000),
        array('Body Type' => 'Off-Road', 'Engine' => 'V6 Turbo', 'price' => 38000),
        array('Body Type' => 'Off-Road', 'Engine' => 'Electric', 'price' => 48000),
    );
    
    foreach ($variations as $variation_data) {
        $variation = new WC_Product_Variation();
        $variation->set_parent_id($product_id);
        $variation->set_attributes(array(
            'Body Type' => $variation_data['Body Type'],
            'Engine' => $variation_data['Engine']
        ));
        $variation->set_price($variation_data['price']);
        $variation->set_regular_price($variation_data['price']);
        $variation->set_manage_stock(false);
        $variation->set_stock_status('instock');
        $variation->save();
    }
}

/**
 * Custom shop header
 */
function youtuneai_shop_custom_header() {
    if (is_shop() || is_product_category() || is_product_tag()) {
        ?>
        <div class="youtuneai-shop-header card-3d p-8 mb-8 bg-gradient-to-r from-primary/10 to-secondary/10 border border-primary/20">
            <div class="text-center">
                <h1 class="text-4xl font-orbitron font-bold text-primary glow-text mb-4">
                    <?php _e('Premium Store', 'youtuneai'); ?>
                </h1>
                <p class="text-xl text-platinum/80 mb-6">
                    <?php _e('Discover exclusive digital products, game assets, VR experiences, and custom vehicle parts.', 'youtuneai'); ?>
                </p>
                
                <div class="flex justify-center space-x-6 text-sm text-platinum/60">
                    <div class="flex items-center">
                        <i class="bx bx-shield-check mr-2 text-accent"></i>
                        <?php _e('Secure Checkout', 'youtuneai'); ?>
                    </div>
                    <div class="flex items-center">
                        <i class="bx bx-world mr-2 text-accent"></i>
                        <?php _e('Worldwide Shipping', 'youtuneai'); ?>
                    </div>
                    <div class="flex items-center">
                        <i class="bx bx-support mr-2 text-accent"></i>
                        <?php _e('24/7 Support', 'youtuneai'); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}

/**
 * Custom add to cart text
 */
function youtuneai_custom_add_to_cart_text($text, $product) {
    if ($product->get_meta('_youtuneai_product_type') === 'garage_configuration') {
        return __('Configure & Buy', 'youtuneai');
    }
    
    if ($product->is_type('variable')) {
        return __('Select Options', 'youtuneai');
    }
    
    return __('Add to Cart', 'youtuneai');
}

/**
 * Add 3D viewer to product pages
 */
function youtuneai_product_3d_viewer() {
    global $product;
    
    $model_path = $product->get_meta('_youtuneai_3d_model');
    
    if (!$model_path) {
        return;
    }
    
    ?>
    <div class="youtuneai-3d-viewer card-3d p-6 mb-8">
        <h3 class="text-xl font-orbitron font-bold text-primary mb-4">
            <i class="bx bx-cube mr-2"></i>
            <?php _e('3D Product Viewer', 'youtuneai'); ?>
        </h3>
        
        <div class="bg-black rounded-lg overflow-hidden" style="height: 400px;">
            <canvas id="product-3d-canvas" class="w-full h-full" data-model="<?php echo esc_attr($model_path); ?>"></canvas>
            
            <div class="absolute bottom-4 right-4 space-x-2">
                <button class="bg-primary hover:bg-secondary text-white px-3 py-2 rounded text-sm" id="product-3d-fullscreen">
                    <i class="bx bx-fullscreen mr-1"></i>
                    <?php _e('Fullscreen', 'youtuneai'); ?>
                </button>
                <button class="bg-primary hover:bg-secondary text-white px-3 py-2 rounded text-sm" id="product-3d-ar">
                    <i class="bx bx-mobile mr-1"></i>
                    <?php _e('AR View', 'youtuneai'); ?>
                </button>
            </div>
        </div>
        
        <div class="mt-4 text-center text-platinum/60 text-sm">
            <?php _e('Drag to rotate • Scroll to zoom • Double-click for fullscreen', 'youtuneai'); ?>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize 3D product viewer
            if (document.getElementById('product-3d-canvas')) {
                // Load 3D viewer script
                const script = document.createElement('script');
                script.src = '<?php echo YOUTUNEAI_THEME_URL; ?>/assets/js/dist/product-3d-viewer.js';
                document.head.appendChild(script);
            }
        });
    </script>
    <?php
}

/**
 * Custom checkout fields
 */
function youtuneai_custom_checkout_fields($fields) {
    // Add special instructions field
    $fields['order']['youtuneai_special_instructions'] = array(
        'type' => 'textarea',
        'label' => __('Special Instructions', 'youtuneai'),
        'placeholder' => __('Any special requirements or customizations...', 'youtuneai'),
        'required' => false,
        'class' => array('form-row-wide'),
        'priority' => 10,
    );
    
    // Add installation service option for garage configurations
    $fields['order']['youtuneai_installation_service'] = array(
        'type' => 'checkbox',
        'label' => __('Professional Installation Service (+$500)', 'youtuneai'),
        'required' => false,
        'class' => array('form-row-wide'),
        'priority' => 20,
    );
    
    return $fields;
}

/**
 * Handle garage configuration creation
 */
function youtuneai_create_garage_product() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'youtuneai_nonce')) {
        wp_send_json_error(array('message' => 'Security check failed'));
        return;
    }
    
    $parts = $_POST['parts'] ?? array();
    $color = sanitize_text_field($_POST['color'] ?? '#FF0000');
    $material = sanitize_text_field($_POST['material'] ?? 'metallic');
    $total_price = floatval($_POST['total_price'] ?? 0);
    
    if (empty($parts) || $total_price <= 0) {
        wp_send_json_error(array('message' => 'Invalid configuration'));
        return;
    }
    
    // Create custom product variation
    $base_product_id = get_option('youtuneai_garage_base_product');
    $configuration_id = youtuneai_save_garage_configuration($parts, $color, $material, $total_price);
    
    // Generate cart URL
    $cart_url = add_query_arg(array(
        'add-to-cart' => $base_product_id,
        'youtuneai_config' => $configuration_id
    ), wc_get_cart_url());
    
    wp_send_json_success(array(
        'product_url' => $cart_url,
        'configuration_id' => $configuration_id,
        'message' => 'Configuration saved successfully!'
    ));
}

/**
 * Save garage configuration
 */
function youtuneai_save_garage_configuration($parts, $color, $material, $total_price) {
    $configuration = array(
        'parts' => $parts,
        'color' => $color,
        'material' => $material,
        'total_price' => $total_price,
        'created' => current_time('timestamp')
    );
    
    $config_id = wp_insert_post(array(
        'post_type' => 'garage_configuration',
        'post_title' => 'Custom Vehicle Configuration ' . date('Y-m-d H:i:s'),
        'post_status' => 'publish',
        'meta_input' => array(
            '_configuration_data' => $configuration
        )
    ));
    
    return $config_id;
}

/**
 * Register garage configuration post type
 */
function youtuneai_register_garage_configuration_post_type() {
    register_post_type('garage_configuration', array(
        'labels' => array(
            'name' => __('Garage Configurations', 'youtuneai'),
            'singular_name' => __('Garage Configuration', 'youtuneai'),
        ),
        'public' => false,
        'show_ui' => true,
        'show_in_admin_bar' => false,
        'capability_type' => 'post',
        'hierarchical' => false,
        'supports' => array('title'),
        'menu_icon' => 'dashicons-car',
    ));
}
add_action('init', 'youtuneai_register_garage_configuration_post_type');

/**
 * Add garage configuration to cart
 */
function youtuneai_add_garage_config_to_cart() {
    if (isset($_GET['youtuneai_config']) && isset($_GET['add-to-cart'])) {
        $config_id = intval($_GET['youtuneai_config']);
        $product_id = intval($_GET['add-to-cart']);
        
        $config = get_post_meta($config_id, '_configuration_data', true);
        
        if ($config) {
            // Add configuration data to cart item
            WC()->cart->add_to_cart($product_id, 1, 0, array(), array(
                'youtuneai_config' => $config,
                'youtuneai_config_id' => $config_id
            ));
            
            wp_redirect(wc_get_cart_url());
            exit;
        }
    }
}
add_action('wp_loaded', 'youtuneai_add_garage_config_to_cart');

/**
 * Display configuration in cart
 */
function youtuneai_display_cart_item_config($item_data, $cart_item) {
    if (isset($cart_item['youtuneai_config'])) {
        $config = $cart_item['youtuneai_config'];
        
        $item_data[] = array(
            'key' => __('Configuration', 'youtuneai'),
            'value' => sprintf(
                __('Color: %s | Material: %s | Parts: %d', 'youtuneai'),
                $config['color'],
                ucfirst($config['material']),
                count($config['parts'])
            )
        );
    }
    
    return $item_data;
}
add_filter('woocommerce_get_item_data', 'youtuneai_display_cart_item_config', 10, 2);

/**
 * Save configuration to order
 */
function youtuneai_save_config_to_order($item, $cart_item_key, $values, $order) {
    if (isset($values['youtuneai_config'])) {
        $item->add_meta_data('_youtuneai_config', $values['youtuneai_config']);
        $item->add_meta_data('_youtuneai_config_id', $values['youtuneai_config_id']);
    }
}
add_action('woocommerce_checkout_create_order_line_item', 'youtuneai_save_config_to_order', 10, 4);

/**
 * Custom product categories
 */
function youtuneai_create_product_categories() {
    $categories = array(
        array(
            'name' => 'Vehicle Parts',
            'slug' => 'vehicle-parts',
            'description' => 'Custom vehicle parts and accessories'
        ),
        array(
            'name' => 'Digital Products',
            'slug' => 'digital-products',
            'description' => 'Digital downloads and VR experiences'
        ),
        array(
            'name' => 'Game Assets',
            'slug' => 'game-assets',
            'description' => 'Premium game content and assets'
        ),
        array(
            'name' => '3D Models',
            'slug' => '3d-models',
            'description' => 'High-quality 3D models and textures'
        )
    );
    
    foreach ($categories as $category) {
        if (!term_exists($category['slug'], 'product_cat')) {
            wp_insert_term($category['name'], 'product_cat', array(
                'slug' => $category['slug'],
                'description' => $category['description']
            ));
        }
    }
}
add_action('init', 'youtuneai_create_product_categories');

/**
 * Enhanced product search
 */
function youtuneai_product_search_filter($query) {
    if (!is_admin() && $query->is_main_query() && is_search() && isset($_GET['product_cat'])) {
        $query->set('post_type', 'product');
        $query->set('tax_query', array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => sanitize_text_field($_GET['product_cat'])
            )
        ));
    }
}
add_action('pre_get_posts', 'youtuneai_product_search_filter');

/**
 * Product review enhancements
 */
function youtuneai_enhance_product_reviews() {
    // Add rating display enhancements
    add_filter('comment_form_default_fields', 'youtuneai_custom_review_fields');
}
add_action('init', 'youtuneai_enhance_product_reviews');

/**
 * Custom review fields
 */
function youtuneai_custom_review_fields($fields) {
    if (is_product()) {
        $fields['experience'] = '<p class="comment-form-experience">
            <label for="experience">' . __('Overall Experience', 'youtuneai') . '</label>
            <select id="experience" name="experience" class="form-control">
                <option value="excellent">' . __('Excellent', 'youtuneai') . '</option>
                <option value="good">' . __('Good', 'youtuneai') . '</option>
                <option value="average">' . __('Average', 'youtuneai') . '</option>
                <option value="poor">' . __('Poor', 'youtuneai') . '</option>
            </select>
        </p>';
        
        $fields['recommend'] = '<p class="comment-form-recommend">
            <label for="recommend">
                <input id="recommend" name="recommend" type="checkbox" value="1" />
                ' . __('I would recommend this product', 'youtuneai') . '
            </label>
        </p>';
    }
    
    return $fields;
}

/**
 * Payment gateway customizations
 */
function youtuneai_payment_gateway_customizations() {
    // Add custom styling to payment forms
    add_action('wp_enqueue_scripts', 'youtuneai_payment_styles');
}
add_action('init', 'youtuneai_payment_gateway_customizations');

/**
 * Payment form styles
 */
function youtuneai_payment_styles() {
    if (is_checkout()) {
        wp_add_inline_style('youtuneai-tailwind', '
            .wc_payment_methods {
                background: rgba(20, 20, 40, 0.95);
                border-radius: 12px;
                border: 1px solid rgba(229, 228, 226, 0.2);
                padding: 1.5rem;
            }
            
            .wc_payment_method {
                border: 1px solid rgba(157, 0, 255, 0.3);
                border-radius: 8px;
                padding: 1rem;
                margin-bottom: 1rem;
            }
            
            .wc_payment_method input[type="radio"]:checked + label {
                color: #9d00ff;
            }
            
            .payment_box {
                background: rgba(0, 0, 0, 0.3);
                border-radius: 8px;
                padding: 1rem;
                margin-top: 1rem;
            }
        ');
    }
}