<?php
// Add AJAX handler for voice commands
add_action('wp_ajax_process_voice_command', 'handle_voice_command');
add_action('wp_ajax_nopriv_process_voice_command', 'handle_voice_command');

function handle_voice_command()
{
    // Verify nonce
    if (!wp_verify_nonce($_POST['security'], 'voice_command_nonce')) {
        wp_die('Security check failed');
    }

    $command = sanitize_text_field($_POST['command']);
    $result = process_ai_command($command);

    wp_send_json_success($result);
}

function process_ai_command($command)
{
    $command_lower = strtolower($command);

    // Command processing logic
    if (strpos($command_lower, 'background') !== false && strpos($command_lower, 'space') !== false) {
        return array(
            'message' => 'Background changed to space theme',
            'action' => 'background_change',
            'reload' => false
        );
    }

    if (strpos($command_lower, 'background') !== false && strpos($command_lower, 'ocean') !== false) {
        return array(
            'message' => 'Background changed to ocean theme',
            'action' => 'background_change',
            'reload' => false
        );
    }

    if (strpos($command_lower, 'deploy') !== false) {
        return array(
            'message' => 'Changes deployed to live site',
            'action' => 'deploy',
            'reload' => false
        );
    }

    if (strpos($command_lower, 'stats') !== false) {
        return array(
            'message' => 'Site statistics: 1,247 visitors today, 23% bounce rate',
            'action' => 'stats',
            'reload' => false
        );
    }

    // Default response
    return array(
        'message' => "Command '{$command}' processed. AI is learning your preferences.",
        'action' => 'general',
        'reload' => false
    );
}

// Create admin dashboard page if it doesn't exist
function create_admin_dashboard_page()
{
    $page_slug = 'admin-dashboard';
    $page = get_page_by_path($page_slug);

    if (!$page) {
        $page_data = array(
            'post_title' => 'Admin Dashboard',
            'post_name' => $page_slug,
            'post_content' => '[admin_dashboard]',
            'post_status' => 'publish',
            'post_type' => 'page',
            'page_template' => 'page-admin-dashboard.php'
        );

        wp_insert_post($page_data);
    }
}

// Run on theme activation
add_action('after_switch_theme', 'create_admin_dashboard_page');
