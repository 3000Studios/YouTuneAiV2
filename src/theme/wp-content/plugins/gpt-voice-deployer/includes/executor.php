<?php

/**
 * GPT Voice Deployer - Command Executor
 * Boss Man J Edition - Advanced WordPress Command Processor
 * Version: 2.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class GPT_Voice_Command_Executor
{

    private $safe_commands;
    private $moderate_commands;
    private $high_risk_commands;

    public function __construct()
    {
        $this->safe_commands = [
            'css_inject',
            'post_create',
            'page_create',
            'menu_update',
            'widget_add',
            'option_update'
        ];

        $this->moderate_commands = [
            'theme_update',
            'js_inject',
            'plugin_activate',
            'user_create',
            'content_modify'
        ];

        $this->high_risk_commands = [
            'file_delete',
            'database_modify',
            'plugin_install',
            'core_update',
            'user_delete'
        ];
    }

    /**
     * Execute a voice command with safety checks
     */
    public function execute_command($command_data)
    {
        if (!$this->validate_command($command_data)) {
            return $this->error_response('Invalid command structure');
        }

        $action = $command_data['action'] ?? 'unknown';
        $safety_level = $command_data['safety_level'] ?? 'moderate';

        // Safety check
        if (!$this->is_command_allowed($action, $safety_level)) {
            return $this->error_response('Command not allowed: ' . $action);
        }

        // Log the execution attempt
        $this->log_execution_attempt($command_data);

        // Route to appropriate executor
        switch ($action) {
            case 'css_inject':
                return $this->execute_css_injection($command_data);

            case 'js_inject':
                return $this->execute_js_injection($command_data);

            case 'theme_update':
                return $this->execute_theme_update($command_data);

            case 'post_create':
                return $this->execute_post_creation($command_data);

            case 'page_create':
                return $this->execute_page_creation($command_data);

            case 'menu_update':
                return $this->execute_menu_update($command_data);

            case 'widget_add':
                return $this->execute_widget_addition($command_data);

            case 'option_update':
                return $this->execute_option_update($command_data);

            case 'content_modify':
                return $this->execute_content_modification($command_data);

            case 'plugin_modify':
                return $this->execute_plugin_modification($command_data);

            case 'raw_code':
                return $this->execute_raw_code($command_data);

            default:
                return $this->execute_generic_command($command_data);
        }
    }

    /**
     * Inject CSS into the site
     */
    private function execute_css_injection($command_data)
    {
        $css = $command_data['code'] ?? '';
        $target = $command_data['target'] ?? 'custom_css';

        if (empty($css)) {
            return $this->error_response('No CSS code provided');
        }

        // Sanitize CSS
        $css = $this->sanitize_css($css);

        // Add timestamp comment
        $css = "/* GPT Voice Command - " . current_time('mysql') . " */\n" . $css;

        switch ($target) {
            case 'custom_css':
                $existing_css = get_option('gpt_voice_custom_css', '');
                $new_css = $existing_css . "\n\n" . $css;
                update_option('gpt_voice_custom_css', $new_css);
                break;

            case 'theme_style':
                $style_file = get_stylesheet_directory() . '/style.css';
                if (file_exists($style_file)) {
                    $this->backup_file($style_file);
                    file_put_contents($style_file, "\n\n" . $css, FILE_APPEND);
                }
                break;

            case 'additional_css':
                $customizer_css = wp_get_custom_css();
                wp_update_custom_css_post($customizer_css . "\n\n" . $css);
                break;
        }

        return $this->success_response('CSS injected successfully', [
            'target' => $target,
            'css_length' => strlen($css),
            'preview_url' => home_url()
        ]);
    }

    /**
     * Inject JavaScript into the site
     */
    private function execute_js_injection($command_data)
    {
        $js = $command_data['code'] ?? '';
        $target = $command_data['target'] ?? 'custom_js';

        if (empty($js)) {
            return $this->error_response('No JavaScript code provided');
        }

        // Sanitize JavaScript (basic check)
        if ($this->contains_dangerous_js($js)) {
            return $this->error_response('JavaScript contains potentially dangerous code');
        }

        // Add timestamp comment
        $js = "// GPT Voice Command - " . current_time('mysql') . "\n" . $js;

        switch ($target) {
            case 'custom_js':
                $existing_js = get_option('gpt_voice_custom_js', '');
                $new_js = $existing_js . "\n\n" . $js;
                update_option('gpt_voice_custom_js', $new_js);
                break;

            case 'footer_js':
                $existing_footer_js = get_option('gpt_voice_footer_js', '');
                update_option('gpt_voice_footer_js', $existing_footer_js . "\n\n" . $js);
                break;

            case 'theme_js':
                $js_file = get_stylesheet_directory() . '/js/custom.js';
                if (!file_exists(dirname($js_file))) {
                    wp_mkdir_p(dirname($js_file));
                }
                file_put_contents($js_file, "\n\n" . $js, FILE_APPEND);
                break;
        }

        return $this->success_response('JavaScript injected successfully', [
            'target' => $target,
            'js_length' => strlen($js)
        ]);
    }

    /**
     * Update theme files
     */
    private function execute_theme_update($command_data)
    {
        $code = $command_data['code'] ?? '';
        $file_target = $command_data['file_target'] ?? 'functions.php';
        $operation = $command_data['operation'] ?? 'append';

        if (empty($code)) {
            return $this->error_response('No code provided for theme update');
        }

        $theme_path = get_stylesheet_directory();
        $target_file = $theme_path . '/' . $file_target;

        // Security check
        if (!$this->is_safe_theme_file($file_target)) {
            return $this->error_response('File not allowed for modification: ' . $file_target);
        }

        // Create backup
        if (file_exists($target_file)) {
            $backup_result = $this->backup_file($target_file);
            if (!$backup_result) {
                return $this->error_response('Failed to create backup');
            }
        }

        // Execute operation
        switch ($operation) {
            case 'append':
                $comment = "\n\n// GPT Voice Command Addition - " . current_time('mysql') . "\n";
                file_put_contents($target_file, $comment . $code . "\n", FILE_APPEND);
                break;

            case 'prepend':
                $existing_content = file_exists($target_file) ? file_get_contents($target_file) : '';
                $comment = "<?php\n// GPT Voice Command Addition - " . current_time('mysql') . "\n";
                file_put_contents($target_file, $comment . $code . "\n\n" . $existing_content);
                break;

            case 'replace':
                $comment = "<?php\n// GPT Voice Command - " . current_time('mysql') . "\n";
                file_put_contents($target_file, $comment . $code);
                break;
        }

        return $this->success_response('Theme file updated successfully', [
            'file' => $file_target,
            'operation' => $operation,
            'backup_created' => true,
            'code_length' => strlen($code)
        ]);
    }

    /**
     * Create a new post
     */
    private function execute_post_creation($command_data)
    {
        $title = $command_data['title'] ?? 'Voice Generated Post';
        $content = $command_data['content'] ?? $command_data['code'] ?? '';
        $status = $command_data['status'] ?? 'draft';
        $post_type = $command_data['post_type'] ?? 'post';
        $category = $command_data['category'] ?? '';
        $tags = $command_data['tags'] ?? '';

        $post_data = [
            'post_title' => sanitize_text_field($title),
            'post_content' => wp_kses_post($content),
            'post_status' => sanitize_text_field($status),
            'post_type' => sanitize_text_field($post_type),
            'post_author' => get_current_user_id(),
            'meta_input' => [
                'gpt_voice_generated' => true,
                'gpt_voice_timestamp' => current_time('mysql')
            ]
        ];

        $post_id = wp_insert_post($post_data);

        if (is_wp_error($post_id)) {
            return $this->error_response('Failed to create post: ' . $post_id->get_error_message());
        }

        // Add category if specified
        if (!empty($category)) {
            $cat_id = $this->get_or_create_category($category);
            if ($cat_id) {
                wp_set_post_categories($post_id, [$cat_id]);
            }
        }

        // Add tags if specified
        if (!empty($tags)) {
            wp_set_post_tags($post_id, $tags);
        }

        return $this->success_response('Post created successfully', [
            'post_id' => $post_id,
            'post_title' => $title,
            'edit_url' => admin_url("post.php?post={$post_id}&action=edit"),
            'view_url' => get_permalink($post_id)
        ]);
    }

    /**
     * Create a new page
     */
    private function execute_page_creation($command_data)
    {
        $command_data['post_type'] = 'page';
        $command_data['status'] = $command_data['status'] ?? 'draft';

        return $this->execute_post_creation($command_data);
    }

    /**
     * Update navigation menu
     */
    private function execute_menu_update($command_data)
    {
        $menu_name = $command_data['menu_name'] ?? 'primary';
        $action = $command_data['menu_action'] ?? 'add_item';
        $item_data = $command_data['item_data'] ?? [];

        $menu = wp_get_nav_menu_object($menu_name);
        if (!$menu) {
            // Create menu if it doesn't exist
            $menu_id = wp_create_nav_menu($menu_name);
            if (is_wp_error($menu_id)) {
                return $this->error_response('Failed to create menu: ' . $menu_id->get_error_message());
            }
            $menu = wp_get_nav_menu_object($menu_id);
        }

        switch ($action) {
            case 'add_item':
                $item_id = wp_update_nav_menu_item($menu->term_id, 0, [
                    'menu-item-title' => sanitize_text_field($item_data['title'] ?? 'New Item'),
                    'menu-item-url' => esc_url($item_data['url'] ?? '#'),
                    'menu-item-status' => 'publish'
                ]);

                if (is_wp_error($item_id)) {
                    return $this->error_response('Failed to add menu item');
                }

                return $this->success_response('Menu item added successfully', [
                    'menu_name' => $menu_name,
                    'item_id' => $item_id,
                    'item_title' => $item_data['title'] ?? 'New Item'
                ]);
        }

        return $this->error_response('Unknown menu action: ' . $action);
    }

    /**
     * Add widget to sidebar
     */
    private function execute_widget_addition($command_data)
    {
        $widget_type = $command_data['widget_type'] ?? 'text';
        $sidebar = $command_data['sidebar'] ?? 'sidebar-1';
        $widget_data = $command_data['widget_data'] ?? [];

        // Get current sidebar widgets
        $sidebars_widgets = get_option('sidebars_widgets', []);

        if (!isset($sidebars_widgets[$sidebar])) {
            $sidebars_widgets[$sidebar] = [];
        }

        // Generate widget instance
        $widget_instance = $widget_type . '-' . time();
        $sidebars_widgets[$sidebar][] = $widget_instance;

        // Update sidebars
        update_option('sidebars_widgets', $sidebars_widgets);

        // Set widget options
        $widget_options = get_option('widget_' . $widget_type, []);
        $widget_options[time()] = array_merge([
            'title' => 'Voice Generated Widget',
            'text' => 'Generated by voice command'
        ], $widget_data);

        update_option('widget_' . $widget_type, $widget_options);

        return $this->success_response('Widget added successfully', [
            'widget_type' => $widget_type,
            'sidebar' => $sidebar,
            'widget_instance' => $widget_instance
        ]);
    }

    /**
     * Update WordPress option
     */
    private function execute_option_update($command_data)
    {
        $option_name = $command_data['option_name'] ?? '';
        $option_value = $command_data['option_value'] ?? '';
        $operation = $command_data['operation'] ?? 'update';

        if (empty($option_name)) {
            return $this->error_response('Option name required');
        }

        // Security check - only allow safe options
        if (!$this->is_safe_option($option_name)) {
            return $this->error_response('Option not allowed for modification: ' . $option_name);
        }

        switch ($operation) {
            case 'update':
                $result = update_option($option_name, $option_value);
                break;

            case 'delete':
                $result = delete_option($option_name);
                break;

            case 'add':
                $result = add_option($option_name, $option_value);
                break;

            default:
                return $this->error_response('Unknown operation: ' . $operation);
        }

        return $this->success_response('Option updated successfully', [
            'option_name' => $option_name,
            'operation' => $operation,
            'success' => $result
        ]);
    }

    /**
     * Modify existing content
     */
    private function execute_content_modification($command_data)
    {
        $post_id = $command_data['post_id'] ?? 0;
        $content_type = $command_data['content_type'] ?? 'post_content';
        $new_content = $command_data['content'] ?? $command_data['code'] ?? '';
        $operation = $command_data['operation'] ?? 'replace';

        if (!$post_id || !get_post($post_id)) {
            return $this->error_response('Invalid post ID');
        }

        $post = get_post($post_id);

        // Create backup
        update_post_meta($post_id, '_gpt_voice_backup_' . time(), [
            'content' => $post->post_content,
            'title' => $post->post_title,
            'excerpt' => $post->post_excerpt
        ]);

        switch ($content_type) {
            case 'post_content':
                $current_content = $post->post_content;
                break;
            case 'post_title':
                $current_content = $post->post_title;
                break;
            case 'post_excerpt':
                $current_content = $post->post_excerpt;
                break;
            default:
                return $this->error_response('Invalid content type');
        }

        switch ($operation) {
            case 'replace':
                $final_content = $new_content;
                break;
            case 'append':
                $final_content = $current_content . "\n\n" . $new_content;
                break;
            case 'prepend':
                $final_content = $new_content . "\n\n" . $current_content;
                break;
            default:
                return $this->error_response('Unknown operation');
        }

        $update_data = [
            'ID' => $post_id,
            $content_type => $final_content
        ];

        $result = wp_update_post($update_data);

        if (is_wp_error($result)) {
            return $this->error_response('Failed to update content: ' . $result->get_error_message());
        }

        return $this->success_response('Content updated successfully', [
            'post_id' => $post_id,
            'content_type' => $content_type,
            'operation' => $operation,
            'backup_created' => true
        ]);
    }

    /**
     * Execute raw PHP code (with extreme caution)
     */
    private function execute_raw_code($command_data)
    {
        $code = $command_data['code'] ?? '';
        $safety_level = $command_data['safety_level'] ?? 'high_risk';

        if ($safety_level === 'high_risk') {
            return $this->error_response('Raw code execution is disabled for security');
        }

        // For now, just store the code for manual review
        $temp_file = get_stylesheet_directory() . '/gpt-temp-code-' . time() . '.php';
        file_put_contents($temp_file, "<?php\n// GPT Voice Generated Code\n// " . current_time('mysql') . "\n\n" . $code);

        return $this->success_response('Code stored for manual review', [
            'file_path' => $temp_file,
            'code_length' => strlen($code),
            'manual_review_required' => true
        ]);
    }

    /**
     * Execute generic commands
     */
    private function execute_generic_command($command_data)
    {
        $action = $command_data['action'] ?? 'unknown';
        $code = $command_data['code'] ?? '';

        // Try to interpret the command
        if (strpos($code, 'wp_insert_post') !== false) {
            return $this->execute_post_creation($command_data);
        }

        if (strpos($code, 'update_option') !== false) {
            return $this->execute_option_update($command_data);
        }

        // Default to storing for review
        return $this->execute_raw_code($command_data);
    }

    // ===== UTILITY METHODS =====

    private function validate_command($command_data)
    {
        return is_array($command_data) && !empty($command_data);
    }

    private function is_command_allowed($action, $safety_level)
    {
        switch ($safety_level) {
            case 'safe':
                return in_array($action, $this->safe_commands);
            case 'moderate':
                return in_array($action, array_merge($this->safe_commands, $this->moderate_commands));
            case 'high_risk':
                return false; // High risk commands are disabled
            default:
                return false;
        }
    }

    private function sanitize_css($css)
    {
        // Remove potentially dangerous CSS
        $dangerous_patterns = [
            '/javascript:/i',
            '/expression\s*\(/i',
            '/behavior\s*:/i',
            '/@import/i'
        ];

        foreach ($dangerous_patterns as $pattern) {
            $css = preg_replace($pattern, '', $css);
        }

        return $css;
    }

    private function contains_dangerous_js($js)
    {
        $dangerous_patterns = [
            '/eval\s*\(/i',
            '/document\.write/i',
            '/innerHTML\s*=/i',
            '/\.createElement\s*\(/i'
        ];

        foreach ($dangerous_patterns as $pattern) {
            if (preg_match($pattern, $js)) {
                return true;
            }
        }

        return false;
    }

    private function is_safe_theme_file($filename)
    {
        $safe_files = [
            'functions.php',
            'style.css',
            'header.php',
            'footer.php',
            'index.php',
            'page.php',
            'single.php',
            'archive.php'
        ];

        return in_array($filename, $safe_files);
    }

    private function is_safe_option($option_name)
    {
        $safe_options = [
            'blogname',
            'blogdescription',
            'users_can_register',
            'default_comment_status',
            'default_ping_status',
            'posts_per_page',
            'date_format',
            'time_format',
            'start_of_week'
        ];

        // Allow custom GPT voice options
        if (strpos($option_name, 'gpt_voice_') === 0) {
            return true;
        }

        return in_array($option_name, $safe_options);
    }

    private function backup_file($file_path)
    {
        $backup_dir = dirname($file_path) . '/gpt-backups/';
        if (!is_dir($backup_dir)) {
            wp_mkdir_p($backup_dir);
        }

        $backup_file = $backup_dir . basename($file_path) . '.backup.' . time();
        return copy($file_path, $backup_file);
    }

    private function get_or_create_category($category_name)
    {
        $category = get_category_by_slug(sanitize_title($category_name));

        if ($category) {
            return $category->term_id;
        }

        $result = wp_insert_category([
            'cat_name' => $category_name,
            'category_nicename' => sanitize_title($category_name)
        ]);

        return is_wp_error($result) ? false : $result;
    }

    private function log_execution_attempt($command_data)
    {
        $log_entry = [
            'timestamp' => current_time('mysql'),
            'user_id' => get_current_user_id(),
            'action' => $command_data['action'] ?? 'unknown',
            'safety_level' => $command_data['safety_level'] ?? 'unknown',
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ];

        $execution_log = get_option('gpt_voice_execution_log', []);
        $execution_log[] = $log_entry;

        // Keep only last 1000 entries
        if (count($execution_log) > 1000) {
            $execution_log = array_slice($execution_log, -1000);
        }

        update_option('gpt_voice_execution_log', $execution_log);
    }

    private function success_response($message, $data = [])
    {
        return [
            'status' => 'success',
            'message' => $message,
            'data' => $data,
            'timestamp' => current_time('mysql')
        ];
    }

    private function error_response($message, $data = [])
    {
        return [
            'status' => 'error',
            'message' => $message,
            'data' => $data,
            'timestamp' => current_time('mysql')
        ];
    }
}
