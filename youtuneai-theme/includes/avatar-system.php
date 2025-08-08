<?php
/**
 * Avatar System
 * 
 * @package YouTuneAI
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initialize avatar system
 */
function youtuneai_init_avatar_system() {
    // Enqueue avatar-specific scripts
    wp_enqueue_script(
        'youtuneai-avatar',
        YOUTUNEAI_THEME_URL . '/assets/js/dist/avatar.js',
        array('three-js'),
        YOUTUNEAI_VERSION,
        true
    );
    
    // Localize avatar settings
    wp_localize_script('youtuneai-avatar', 'youtuneai_avatar', array(
        'api_url' => home_url('/wp-json/youtuneai/v1/'),
        'models_url' => YOUTUNEAI_THEME_URL . '/assets/models/',
        'default_avatar' => get_option('youtuneai_default_avatar', 1),
    ));
}
add_action('wp_enqueue_scripts', 'youtuneai_init_avatar_system');

/**
 * Avatar shortcode
 */
function youtuneai_avatar_shortcode($atts) {
    $atts = shortcode_atts(array(
        'id' => get_option('youtuneai_default_avatar', 1),
        'width' => '300',
        'height' => '400',
        'interactive' => 'true',
        'voice' => 'true',
    ), $atts, 'youtuneai_avatar');
    
    $avatar_id = intval($atts['id']);
    $width = intval($atts['width']);
    $height = intval($atts['height']);
    $interactive = $atts['interactive'] === 'true';
    $voice = $atts['voice'] === 'true';
    
    $avatar = get_post($avatar_id);
    if (!$avatar || $avatar->post_type !== 'avatar') {
        return '<div class="avatar-error">Avatar not found</div>';
    }
    
    ob_start();
    ?>
    <div class="youtuneai-avatar-container" 
         data-avatar-id="<?php echo $avatar_id; ?>" 
         data-interactive="<?php echo $interactive ? 'true' : 'false'; ?>"
         data-voice="<?php echo $voice ? 'true' : 'false'; ?>"
         style="width: <?php echo $width; ?>px; height: <?php echo $height; ?>px;">
        
        <canvas class="avatar-canvas" width="<?php echo $width; ?>" height="<?php echo $height; ?>"></canvas>
        
        <?php if ($interactive): ?>
        <div class="avatar-controls">
            <?php if ($voice): ?>
            <button class="avatar-mic-btn" id="avatar-mic-<?php echo $avatar_id; ?>">
                <i class="bx bx-microphone"></i>
            </button>
            <?php endif; ?>
            
            <div class="avatar-chat">
                <input type="text" class="avatar-input" placeholder="Type a message..." />
                <button class="avatar-send-btn">
                    <i class="bx bx-send"></i>
                </button>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="avatar-status">
            <span class="status-indicator"></span>
            <span class="status-text">Initializing...</span>
        </div>
        
        <?php if ($interactive): ?>
        <div class="avatar-customizer" style="display: none;">
            <h4>Customize Avatar</h4>
            <div class="customizer-option">
                <label>Voice:</label>
                <select class="voice-selector">
                    <option value="default">Default</option>
                    <option value="female1">Female 1</option>
                    <option value="male1">Male 1</option>
                </select>
            </div>
            <div class="customizer-option">
                <label>Emotion:</label>
                <select class="emotion-selector">
                    <option value="neutral">Neutral</option>
                    <option value="happy">Happy</option>
                    <option value="excited">Excited</option>
                </select>
            </div>
            <button class="save-customization">Save</button>
        </div>
        <?php endif; ?>
    </div>
    <?php
    
    return ob_get_clean();
}
add_shortcode('youtuneai_avatar', 'youtuneai_avatar_shortcode');

/**
 * Add avatar customization to avatar posts
 */
function youtuneai_avatar_meta_boxes() {
    add_meta_box(
        'youtuneai_avatar_settings',
        __('Avatar Settings', 'youtuneai'),
        'youtuneai_avatar_settings_callback',
        'avatar',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'youtuneai_avatar_meta_boxes');

/**
 * Avatar settings meta box callback
 */
function youtuneai_avatar_settings_callback($post) {
    wp_nonce_field('youtuneai_avatar_settings', 'youtuneai_avatar_settings_nonce');
    
    $model_path = get_post_meta($post->ID, 'model_path', true);
    $voice_id = get_post_meta($post->ID, 'voice_id', true);
    $lip_sync_profile = get_post_meta($post->ID, 'lip_sync_profile', true);
    $colorway = get_post_meta($post->ID, 'colorway', true);
    $settings = get_post_meta($post->ID, 'settings', true);
    
    if (!is_array($settings)) {
        $settings = array();
    }
    ?>
    
    <table class="form-table">
        <tr>
            <th><label for="model_path"><?php _e('Model Path', 'youtuneai'); ?></label></th>
            <td>
                <input type="text" 
                       id="model_path" 
                       name="model_path" 
                       value="<?php echo esc_attr($model_path); ?>" 
                       class="regular-text" 
                       placeholder="assets/models/avatar.glb" />
                <p class="description"><?php _e('Path to the 3D model file (GLB format)', 'youtuneai'); ?></p>
            </td>
        </tr>
        
        <tr>
            <th><label for="voice_id"><?php _e('Voice ID', 'youtuneai'); ?></label></th>
            <td>
                <select id="voice_id" name="voice_id">
                    <option value="default" <?php selected($voice_id, 'default'); ?>>Default</option>
                    <option value="female1" <?php selected($voice_id, 'female1'); ?>>Female 1</option>
                    <option value="male1" <?php selected($voice_id, 'male1'); ?>>Male 1</option>
                </select>
            </td>
        </tr>
        
        <tr>
            <th><label for="lip_sync_profile"><?php _e('Lip Sync Profile', 'youtuneai'); ?></label></th>
            <td>
                <select id="lip_sync_profile" name="lip_sync_profile">
                    <option value="standard" <?php selected($lip_sync_profile, 'standard'); ?>>Standard</option>
                    <option value="detailed" <?php selected($lip_sync_profile, 'detailed'); ?>>Detailed</option>
                    <option value="minimal" <?php selected($lip_sync_profile, 'minimal'); ?>>Minimal</option>
                </select>
            </td>
        </tr>
        
        <tr>
            <th><label for="colorway"><?php _e('Colorway', 'youtuneai'); ?></label></th>
            <td>
                <input type="text" 
                       id="colorway" 
                       name="colorway" 
                       value="<?php echo esc_attr($colorway); ?>" 
                       class="color-picker" />
                <p class="description"><?php _e('Primary color scheme', 'youtuneai'); ?></p>
            </td>
        </tr>
        
        <tr>
            <th><label><?php _e('Advanced Settings', 'youtuneai'); ?></label></th>
            <td>
                <label>
                    <input type="checkbox" 
                           name="settings[idle_animations]" 
                           value="1" 
                           <?php checked(isset($settings['idle_animations']) ? $settings['idle_animations'] : 0, 1); ?> />
                    <?php _e('Enable idle animations', 'youtuneai'); ?>
                </label><br>
                
                <label>
                    <input type="checkbox" 
                           name="settings[auto_lip_sync]" 
                           value="1" 
                           <?php checked(isset($settings['auto_lip_sync']) ? $settings['auto_lip_sync'] : 0, 1); ?> />
                    <?php _e('Auto lip sync', 'youtuneai'); ?>
                </label><br>
                
                <label>
                    <input type="checkbox" 
                           name="settings[emotion_blending]" 
                           value="1" 
                           <?php checked(isset($settings['emotion_blending']) ? $settings['emotion_blending'] : 0, 1); ?> />
                    <?php _e('Emotion blending', 'youtuneai'); ?>
                </label>
            </td>
        </tr>
    </table>
    
    <script>
        jQuery(document).ready(function($) {
            $('.color-picker').wpColorPicker();
        });
    </script>
    <?php
}

/**
 * Save avatar settings
 */
function youtuneai_save_avatar_settings($post_id) {
    // Check nonce
    if (!isset($_POST['youtuneai_avatar_settings_nonce']) || 
        !wp_verify_nonce($_POST['youtuneai_avatar_settings_nonce'], 'youtuneai_avatar_settings')) {
        return;
    }
    
    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Save meta fields
    $fields = array('model_path', 'voice_id', 'lip_sync_profile', 'colorway');
    
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }
    
    // Save settings array
    if (isset($_POST['settings'])) {
        $settings = array();
        foreach ($_POST['settings'] as $key => $value) {
            $settings[sanitize_key($key)] = sanitize_text_field($value);
        }
        update_post_meta($post_id, 'settings', $settings);
    }
}
add_action('save_post', 'youtuneai_save_avatar_settings');

/**
 * Default avatar creation
 */
function youtuneai_create_default_avatar() {
    // Check if default avatar exists
    $existing = get_option('youtuneai_default_avatar');
    if ($existing && get_post($existing)) {
        return;
    }
    
    // Create default avatar
    $avatar_id = wp_insert_post(array(
        'post_title' => 'Default YouTuneAI Avatar',
        'post_type' => 'avatar',
        'post_status' => 'publish',
        'post_content' => 'The default YouTuneAI 3D avatar with AI capabilities.',
    ));
    
    if ($avatar_id && !is_wp_error($avatar_id)) {
        // Set default meta
        update_post_meta($avatar_id, 'model_path', 'assets/models/default-avatar.glb');
        update_post_meta($avatar_id, 'voice_id', 'default');
        update_post_meta($avatar_id, 'lip_sync_profile', 'standard');
        update_post_meta($avatar_id, 'colorway', '#9d00ff');
        update_post_meta($avatar_id, 'settings', array(
            'idle_animations' => 1,
            'auto_lip_sync' => 1,
            'emotion_blending' => 1,
        ));
        
        // Save as default
        update_option('youtuneai_default_avatar', $avatar_id);
    }
}
add_action('after_switch_theme', 'youtuneai_create_default_avatar');