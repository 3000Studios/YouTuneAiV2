<?php
/*
Plugin Name: GPT Voice Deployer - Boss Man J Edition
Plugin URI: https://youtuneai.com
Description: 🎙️ ULTIMATE Voice Command System - Control your WordPress site with voice commands powered by GPT-4. Speak changes into existence like a digital god!
Version: 2.0.0
Author: Boss Man J (3000Studios)
Author URI: https://youtuneai.com
License: GPL v2 or later
Text Domain: gpt-voice-deployer
*/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('GPT_VOICE_PLUGIN_URL', plugin_dir_url(__FILE__));
define('GPT_VOICE_PLUGIN_PATH', plugin_dir_path(__FILE__));

class GPT_Voice_Deployer
{

    private $api_key;
    private $command_history;

    public function __construct()
    {
        $this->api_key = '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';
        $this->command_history = get_option('gpt_voice_command_history', []);

        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('rest_api_init', [$this, 'register_rest_routes']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_scripts']);
        add_action('wp_footer', [$this, 'add_floating_voice_button']);
        add_action('admin_bar_menu', [$this, 'add_admin_bar_button'], 100);

        // Activation hook
        register_activation_hook(__FILE__, [$this, 'activate_plugin']);
    }

    public function activate_plugin()
    {
        // Create necessary database tables or options
        add_option('gpt_voice_command_history', []);
        add_option('gpt_voice_settings', [
            'enabled' => true,
            'frontend_enabled' => true,
            'auto_execute' => false,
            'voice_language' => 'en-US'
        ]);

        error_log('🎙️ GPT Voice Deployer activated - Boss Man J is ready to command!');
    }

    public function add_admin_menu()
    {
        add_menu_page(
            'Voice Deployer',
            'Voice Commander',
            'manage_options',
            'voice-deployer',
            [$this, 'admin_page'],
            'dashicons-microphone',
            30
        );

        add_submenu_page(
            'voice-deployer',
            'Command History',
            'Command History',
            'manage_options',
            'voice-history',
            [$this, 'history_page']
        );

        add_submenu_page(
            'voice-deployer',
            'Voice Settings',
            'Settings',
            'manage_options',
            'voice-settings',
            [$this, 'settings_page']
        );
    }

    public function enqueue_scripts($hook)
    {
        // Only load on our plugin pages or when needed
        if (strpos($hook, 'voice-deployer') === false && $hook !== 'index.php') {
            return;
        }

        wp_enqueue_script(
            'gpt-voice-deployer',
            GPT_VOICE_PLUGIN_URL . 'js/voice.js',
            ['jquery'],
            '2.0.0',
            true
        );

        wp_enqueue_style(
            'gpt-voice-deployer-admin',
            GPT_VOICE_PLUGIN_URL . 'css/admin-style.css',
            [],
            '2.0.0'
        );

        wp_localize_script('gpt-voice-deployer', 'gptVoice', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'restUrl' => rest_url('gpt/v1/'),
            'nonce' => wp_create_nonce('gpt_voice_nonce'),
            'settings' => get_option('gpt_voice_settings', [])
        ]);
    }

    public function enqueue_frontend_scripts()
    {
        $settings = get_option('gpt_voice_settings', []);
        if (!isset($settings['frontend_enabled']) || !$settings['frontend_enabled']) {
            return;
        }

        wp_enqueue_script(
            'gpt-voice-frontend',
            GPT_VOICE_PLUGIN_URL . 'js/voice-frontend.js',
            ['jquery'],
            '2.0.0',
            true
        );

        wp_enqueue_style(
            'gpt-voice-frontend',
            GPT_VOICE_PLUGIN_URL . 'css/frontend-style.css',
            [],
            '2.0.0'
        );

        wp_localize_script('gpt-voice-frontend', 'gptVoiceFrontend', [
            'restUrl' => rest_url('gpt/v1/'),
            'nonce' => wp_create_nonce('gpt_voice_nonce')
        ]);
    }

    public function add_floating_voice_button()
    {
        $settings = get_option('gpt_voice_settings', []);
        if (!isset($settings['frontend_enabled']) || !$settings['frontend_enabled']) {
            return;
        }
?>
        <div id="gpt-voice-float-button" class="gpt-voice-float">
            🎙️
            <div class="gpt-voice-tooltip">Click to give voice commands</div>
        </div>
        <div id="gpt-voice-modal" class="gpt-voice-modal">
            <div class="gpt-voice-modal-content">
                <span class="gpt-voice-close">&times;</span>
                <h2>🎙️ Voice Command Center</h2>
                <div class="gpt-voice-status" id="voiceStatus">Ready to listen...</div>
                <button id="startVoiceCommand" class="gpt-voice-btn">🎤 Start Listening</button>
                <div id="voiceResult" class="gpt-voice-result"></div>
                <div class="gpt-voice-examples">
                    <h4>💡 Example Commands:</h4>
                    <ul>
                        <li>"Change the background color to dark blue"</li>
                        <li>"Add a new blog post about AI technology"</li>
                        <li>"Update the homepage title"</li>
                        <li>"Create a contact form"</li>
                        <li>"Change font size to larger"</li>
                    </ul>
                </div>
            </div>
        </div>
        <style>
            .gpt-voice-float {
                position: fixed;
                bottom: 80px;
                right: 20px;
                width: 60px;
                height: 60px;
                background: linear-gradient(45deg, #ff0080, #8000ff);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 24px;
                cursor: pointer;
                z-index: 9999;
                box-shadow: 0 4px 20px rgba(255, 0, 128, 0.5);
                transition: all 0.3s ease;
            }

            .gpt-voice-float:hover {
                transform: scale(1.1);
                box-shadow: 0 6px 30px rgba(255, 0, 128, 0.7);
            }

            .gpt-voice-modal {
                display: none;
                position: fixed;
                z-index: 10000;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.8);
            }

            .gpt-voice-modal-content {
                background: linear-gradient(135deg, #1a1a1a, #2d2d2d);
                margin: 5% auto;
                padding: 30px;
                border-radius: 15px;
                width: 80%;
                max-width: 600px;
                color: white;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
            }

            .gpt-voice-close {
                color: #aaa;
                float: right;
                font-size: 28px;
                font-weight: bold;
                cursor: pointer;
            }

            .gpt-voice-btn {
                background: linear-gradient(45deg, #00ff41, #00d4ff);
                color: #1a1a1a;
                border: none;
                padding: 15px 30px;
                border-radius: 25px;
                font-size: 16px;
                font-weight: bold;
                cursor: pointer;
                margin: 10px 0;
                transition: all 0.3s ease;
            }

            .gpt-voice-btn:hover {
                transform: scale(1.05);
                box-shadow: 0 5px 20px rgba(0, 255, 65, 0.3);
            }

            .gpt-voice-status {
                background: rgba(255, 255, 255, 0.1);
                padding: 15px;
                border-radius: 8px;
                margin: 15px 0;
                border-left: 4px solid #00ff41;
            }

            .gpt-voice-result {
                background: rgba(0, 0, 0, 0.3);
                padding: 15px;
                border-radius: 8px;
                margin: 15px 0;
                min-height: 100px;
                font-family: monospace;
                border: 1px solid #333;
            }

            .gpt-voice-examples {
                margin-top: 20px;
                padding: 15px;
                background: rgba(255, 215, 0, 0.1);
                border-radius: 8px;
                border-left: 4px solid #ffd700;
            }

            .gpt-voice-examples ul {
                list-style: none;
                padding-left: 0;
            }

            .gpt-voice-examples li {
                margin: 8px 0;
                padding-left: 20px;
                position: relative;
            }

            .gpt-voice-examples li:before {
                content: "🗣️";
                position: absolute;
                left: 0;
            }
        </style>
    <?php
    }

    public function add_admin_bar_button($wp_admin_bar)
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        $wp_admin_bar->add_node([
            'id' => 'gpt-voice-command',
            'title' => '🎙️ Voice Command',
            'href' => '#',
            'meta' => [
                'onclick' => 'openVoiceModal(); return false;',
                'title' => 'Open Voice Command Center'
            ]
        ]);
    }

    public function register_rest_routes()
    {
        register_rest_route('gpt/v1', '/command', [
            'methods' => 'POST',
            'callback' => [$this, 'handle_voice_command'],
            'permission_callback' => [$this, 'check_permissions']
        ]);

        register_rest_route('gpt/v1', '/history', [
            'methods' => 'GET',
            'callback' => [$this, 'get_command_history'],
            'permission_callback' => [$this, 'check_permissions']
        ]);

        register_rest_route('gpt/v1', '/execute', [
            'methods' => 'POST',
            'callback' => [$this, 'execute_generated_code'],
            'permission_callback' => [$this, 'check_permissions']
        ]);
    }

    public function check_permissions()
    {
        return current_user_can('manage_options');
    }

    public function handle_voice_command($request)
    {
        $command = sanitize_text_field($request['command']);

        if (empty($command)) {
            return new WP_Error('empty_command', 'No command provided', ['status' => 400]);
        }

        // Log the command
        $this->log_command($command);

        // Get GPT response
        $gpt_response = $this->query_gpt($command);

        if (is_wp_error($gpt_response)) {
            return $gpt_response;
        }

        // Parse and potentially execute the response
        $result = $this->process_gpt_response($command, $gpt_response);

        return [
            'status' => 'success',
            'command' => $command,
            'gpt_response' => $gpt_response,
            'result' => $result,
            'timestamp' => current_time('mysql')
        ];
    }

    private function query_gpt($command)
    {
        $context = $this->get_site_context();

        $prompt = "You are a WordPress development assistant for Boss Man J's YouTuneAI website. 
        
        SITE CONTEXT:
        - WordPress site with custom theme
        - Current theme: YouTuneAI Pro
        - Site URL: https://youtuneai.com
        - Main colors: Black (#1a1a1a), Gold (#ffd700), Neon Green (#00ff41), Blue (#1e3a8a)
        
        USER COMMAND: {$command}
        
        INSTRUCTIONS:
        1. Analyze the command and determine what needs to be changed
        2. Generate the appropriate WordPress/PHP/CSS/JavaScript code
        3. Format the response as a JSON object with these fields:
           - 'action': type of action (theme_update, post_create, plugin_modify, etc.)
           - 'code': the actual code to implement
           - 'instructions': human-readable explanation
           - 'file_target': which file(s) to modify
           - 'safety_level': 'safe', 'moderate', 'high_risk'
        
        Be specific and practical. Return only valid JSON.";

        $data = [
            'model' => 'gpt-4',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a WordPress development expert specializing in voice-commanded website modifications.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'max_tokens' => 2000,
            'temperature' => 0.7
        ];

        $ch = curl_init('https://api.openai.com/v1/chat/completions');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->api_key,
                'Content-Type: application/json'
            ],
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_TIMEOUT => 30
        ]);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code !== 200) {
            return new WP_Error('gpt_api_error', 'GPT API request failed', ['status' => 500]);
        }

        $decoded = json_decode($response, true);

        if (!isset($decoded['choices'][0]['message']['content'])) {
            return new WP_Error('gpt_response_error', 'Invalid GPT response', ['status' => 500]);
        }

        return $decoded['choices'][0]['message']['content'];
    }

    private function get_site_context()
    {
        return [
            'site_url' => get_site_url(),
            'theme' => get_template(),
            'active_plugins' => get_option('active_plugins'),
            'wp_version' => get_bloginfo('version')
        ];
    }

    private function process_gpt_response($command, $gpt_response)
    {
        // Try to parse JSON response
        $parsed = json_decode($gpt_response, true);

        if (!$parsed) {
            // If not JSON, treat as raw code
            $parsed = [
                'action' => 'raw_code',
                'code' => $gpt_response,
                'instructions' => 'Raw code execution',
                'safety_level' => 'moderate'
            ];
        }

        // Store the generated code for review
        $code_id = $this->store_generated_code($command, $parsed);

        return [
            'parsed_response' => $parsed,
            'code_id' => $code_id,
            'preview_available' => true,
            'auto_executed' => false
        ];
    }

    private function store_generated_code($command, $parsed_response)
    {
        $code_id = 'gpt_' . time() . '_' . wp_generate_password(8, false);

        $code_data = [
            'id' => $code_id,
            'command' => $command,
            'response' => $parsed_response,
            'created' => current_time('mysql'),
            'executed' => false,
            'status' => 'pending'
        ];

        $stored_codes = get_option('gpt_voice_generated_codes', []);
        $stored_codes[$code_id] = $code_data;
        update_option('gpt_voice_generated_codes', $stored_codes);

        return $code_id;
    }

    public function execute_generated_code($request)
    {
        $code_id = sanitize_text_field($request['code_id']);
        $stored_codes = get_option('gpt_voice_generated_codes', []);

        if (!isset($stored_codes[$code_id])) {
            return new WP_Error('invalid_code_id', 'Code ID not found', ['status' => 404]);
        }

        $code_data = $stored_codes[$code_id];
        $parsed_response = $code_data['response'];

        // Execute based on action type
        $result = $this->execute_by_action_type($parsed_response);

        // Mark as executed
        $stored_codes[$code_id]['executed'] = true;
        $stored_codes[$code_id]['execution_result'] = $result;
        $stored_codes[$code_id]['executed_at'] = current_time('mysql');
        update_option('gpt_voice_generated_codes', $stored_codes);

        return [
            'status' => 'executed',
            'result' => $result,
            'code_id' => $code_id
        ];
    }

    private function execute_by_action_type($parsed_response)
    {
        $action = $parsed_response['action'] ?? 'raw_code';
        $code = $parsed_response['code'] ?? '';

        switch ($action) {
            case 'theme_update':
                return $this->update_theme_files($code, $parsed_response);

            case 'post_create':
                return $this->create_post($parsed_response);

            case 'css_inject':
                return $this->inject_css($code);

            case 'js_inject':
                return $this->inject_javascript($code);

            case 'plugin_modify':
                return $this->modify_plugin($code, $parsed_response);

            default:
                return $this->execute_raw_code($code);
        }
    }

    private function update_theme_files($code, $response)
    {
        $file_target = $response['file_target'] ?? 'functions.php';
        $theme_path = get_stylesheet_directory();

        // Create backup first
        $backup_dir = $theme_path . '/gpt-backups/';
        if (!is_dir($backup_dir)) {
            wp_mkdir_p($backup_dir);
        }

        $target_file = $theme_path . '/' . $file_target;

        if (file_exists($target_file)) {
            copy($target_file, $backup_dir . $file_target . '.backup.' . time());
        }

        // Write new code
        if ($file_target === 'functions.php') {
            // Append to functions.php
            $current_content = file_get_contents($target_file);
            $new_content = $current_content . "\n\n// GPT Voice Command Addition\n" . $code . "\n";
            file_put_contents($target_file, $new_content);
        } else {
            // Create new file or overwrite
            file_put_contents($target_file, $code);
        }

        return [
            'status' => 'success',
            'message' => "Updated {$file_target}",
            'backup_created' => true
        ];
    }

    private function inject_css($css)
    {
        $custom_css = get_option('gpt_voice_custom_css', '');
        $custom_css .= "\n\n/* GPT Voice Command CSS */\n" . $css . "\n";
        update_option('gpt_voice_custom_css', $custom_css);

        // Add to wp_head if not already added
        if (!has_action('wp_head', [$this, 'output_custom_css'])) {
            add_action('wp_head', [$this, 'output_custom_css']);
        }

        return ['status' => 'success', 'message' => 'CSS injected successfully'];
    }

    public function output_custom_css()
    {
        $custom_css = get_option('gpt_voice_custom_css', '');
        if ($custom_css) {
            echo "<style id='gpt-voice-custom-css'>\n" . $custom_css . "\n</style>\n";
        }
    }

    private function inject_javascript($js)
    {
        $custom_js = get_option('gpt_voice_custom_js', '');
        $custom_js .= "\n\n// GPT Voice Command JS\n" . $js . "\n";
        update_option('gpt_voice_custom_js', $custom_js);

        // Add to wp_footer if not already added
        if (!has_action('wp_footer', [$this, 'output_custom_js'])) {
            add_action('wp_footer', [$this, 'output_custom_js']);
        }

        return ['status' => 'success', 'message' => 'JavaScript injected successfully'];
    }

    public function output_custom_js()
    {
        $custom_js = get_option('gpt_voice_custom_js', '');
        if ($custom_js) {
            echo "<script id='gpt-voice-custom-js'>\n" . $custom_js . "\n</script>\n";
        }
    }

    private function create_post($response)
    {
        $post_data = [
            'post_title' => $response['title'] ?? 'Voice Generated Post',
            'post_content' => $response['content'] ?? $response['code'] ?? '',
            'post_status' => 'draft',
            'post_type' => $response['post_type'] ?? 'post'
        ];

        $post_id = wp_insert_post($post_data);

        if (is_wp_error($post_id)) {
            return ['status' => 'error', 'message' => $post_id->get_error_message()];
        }

        return [
            'status' => 'success',
            'message' => 'Post created successfully',
            'post_id' => $post_id,
            'edit_link' => admin_url("post.php?post={$post_id}&action=edit")
        ];
    }

    private function execute_raw_code($code)
    {
        // For safety, we'll store raw code execution in a temporary file
        $temp_file = get_stylesheet_directory() . '/gpt-temp-execution.php';
        file_put_contents($temp_file, "<?php\n" . $code);

        return [
            'status' => 'stored',
            'message' => 'Code stored for manual review',
            'file' => $temp_file
        ];
    }

    private function log_command($command)
    {
        $this->command_history[] = [
            'command' => $command,
            'timestamp' => current_time('mysql'),
            'user_id' => get_current_user_id(),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ];

        // Keep only last 100 commands
        if (count($this->command_history) > 100) {
            $this->command_history = array_slice($this->command_history, -100);
        }

        update_option('gpt_voice_command_history', $this->command_history);
    }

    public function get_command_history()
    {
        return [
            'history' => $this->command_history,
            'total' => count($this->command_history)
        ];
    }

    public function admin_page()
    {
    ?>
        <div class="wrap gpt-voice-admin">
            <h1>🎙️ GPT Voice Deployer - Boss Man J Edition</h1>
            <div class="gpt-voice-dashboard">
                <div class="gpt-voice-card">
                    <h2>Voice Command Center</h2>
                    <div class="voice-controls">
                        <button id="startVoiceBtn" class="button button-primary gpt-voice-btn">
                            🎤 Start Voice Command
                        </button>
                        <div id="voiceStatus" class="voice-status">Ready to listen...</div>
                        <div id="voiceResult" class="voice-result"></div>
                    </div>
                </div>

                <div class="gpt-voice-card">
                    <h2>Recent Commands</h2>
                    <div id="recentCommands" class="recent-commands">
                        <?php $this->display_recent_commands(); ?>
                    </div>
                </div>

                <div class="gpt-voice-card">
                    <h2>Generated Code Review</h2>
                    <div id="codeReview" class="code-review">
                        <?php $this->display_pending_codes(); ?>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .gpt-voice-admin {
                background: linear-gradient(135deg, #1a1a1a, #2d2d2d);
                color: white;
                padding: 20px;
                border-radius: 15px;
                margin: 20px;
            }

            .gpt-voice-dashboard {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
                gap: 20px;
                margin-top: 20px;
            }

            .gpt-voice-card {
                background: rgba(255, 255, 255, 0.1);
                padding: 20px;
                border-radius: 10px;
                border: 1px solid rgba(255, 215, 0, 0.3);
            }

            .gpt-voice-btn {
                background: linear-gradient(45deg, #00ff41, #00d4ff) !important;
                color: #1a1a1a !important;
                border: none !important;
                padding: 15px 30px !important;
                border-radius: 25px !important;
                font-weight: bold !important;
                font-size: 16px !important;
            }

            .voice-status {
                background: rgba(0, 255, 65, 0.1);
                padding: 15px;
                border-radius: 8px;
                margin: 15px 0;
                border-left: 4px solid #00ff41;
            }

            .voice-result {
                background: rgba(0, 0, 0, 0.3);
                padding: 15px;
                border-radius: 8px;
                min-height: 100px;
                font-family: monospace;
                border: 1px solid #333;
                white-space: pre-wrap;
            }
        </style>
    <?php
    }

    private function display_recent_commands()
    {
        $recent = array_slice($this->command_history, -5);
        if (empty($recent)) {
            echo '<p>No commands yet. Start speaking!</p>';
            return;
        }

        foreach (array_reverse($recent) as $cmd) {
            echo '<div class="command-item">';
            echo '<strong>' . esc_html($cmd['timestamp']) . '</strong><br>';
            echo esc_html($cmd['command']);
            echo '</div>';
        }
    }

    private function display_pending_codes()
    {
        $stored_codes = get_option('gpt_voice_generated_codes', []);
        $pending = array_filter($stored_codes, function ($code) {
            return !$code['executed'];
        });

        if (empty($pending)) {
            echo '<p>No pending code to review.</p>';
            return;
        }

        foreach ($pending as $code_id => $code_data) {
            echo '<div class="code-item" data-code-id="' . esc_attr($code_id) . '">';
            echo '<h4>' . esc_html($code_data['command']) . '</h4>';
            echo '<pre>' . esc_html(json_encode($code_data['response'], JSON_PRETTY_PRINT)) . '</pre>';
            echo '<button class="button execute-code" data-code-id="' . esc_attr($code_id) . '">Execute Code</button>';
            echo '</div>';
        }
    }

    public function history_page()
    {
    ?>
        <div class="wrap">
            <h1>Command History</h1>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>Command</th>
                        <th>User</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_reverse($this->command_history) as $cmd): ?>
                        <tr>
                            <td><?php echo esc_html($cmd['timestamp']); ?></td>
                            <td><?php echo esc_html($cmd['command']); ?></td>
                            <td><?php echo esc_html(get_userdata($cmd['user_id'])->display_name ?? 'Unknown'); ?></td>
                            <td><?php echo esc_html($cmd['ip']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php
    }

    public function settings_page()
    {
        if (isset($_POST['submit'])) {
            $settings = [
                'enabled' => isset($_POST['enabled']),
                'frontend_enabled' => isset($_POST['frontend_enabled']),
                'auto_execute' => isset($_POST['auto_execute']),
                'voice_language' => sanitize_text_field($_POST['voice_language'])
            ];
            update_option('gpt_voice_settings', $settings);
            echo '<div class="notice notice-success"><p>Settings saved!</p></div>';
        }

        $settings = get_option('gpt_voice_settings', []);
    ?>
        <div class="wrap">
            <h1>Voice Deployer Settings</h1>
            <form method="post">
                <table class="form-table">
                    <tr>
                        <th>Enable Voice Commands</th>
                        <td><input type="checkbox" name="enabled" <?php checked($settings['enabled'] ?? true); ?>></td>
                    </tr>
                    <tr>
                        <th>Enable Frontend Voice Button</th>
                        <td><input type="checkbox" name="frontend_enabled" <?php checked($settings['frontend_enabled'] ?? true); ?>></td>
                    </tr>
                    <tr>
                        <th>Auto-Execute Safe Commands</th>
                        <td><input type="checkbox" name="auto_execute" <?php checked($settings['auto_execute'] ?? false); ?>></td>
                    </tr>
                    <tr>
                        <th>Voice Language</th>
                        <td>
                            <select name="voice_language">
                                <option value="en-US" <?php selected($settings['voice_language'] ?? 'en-US', 'en-US'); ?>>English (US)</option>
                                <option value="en-GB" <?php selected($settings['voice_language'] ?? 'en-US', 'en-GB'); ?>>English (UK)</option>
                            </select>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
<?php
    }
}

// Initialize the plugin
new GPT_Voice_Deployer();

// Add CSS output hook
add_action('wp_head', function () {
    $plugin = new GPT_Voice_Deployer();
    $plugin->output_custom_css();
});

// Add JS output hook  
add_action('wp_footer', function () {
    $plugin = new GPT_Voice_Deployer();
    $plugin->output_custom_js();
});
?>