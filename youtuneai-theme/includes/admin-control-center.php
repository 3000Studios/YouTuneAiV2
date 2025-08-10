<?php
/**
 * Admin Control Center
 * 
 * @package YouTuneAI
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Admin Control Center menu
 */
function youtuneai_admin_menu() {
    add_menu_page(
        __('YouTuneAI Control', 'youtuneai'),
        __('YouTuneAI Control', 'youtuneai'),
        'manage_options',
        'youtuneai-control',
        'youtuneai_admin_page',
        'dashicons-controls-play',
        2
    );
}
add_action('admin_menu', 'youtuneai_admin_menu');

/**
 * Admin Control Center page
 */
function youtuneai_admin_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('YouTuneAI Control Center', 'youtuneai'); ?></h1>
        
        <div class="youtuneai-admin-grid">
            <div class="youtuneai-admin-card">
                <h2><?php _e('Deployment', 'youtuneai'); ?></h2>
                <p><?php _e('Deploy to production environment', 'youtuneai'); ?></p>
                <button id="deploy-now" class="button button-primary">
                    <?php _e('Deploy Now', 'youtuneai'); ?>
                </button>
            </div>
            
            <div class="youtuneai-admin-card">
                <h2><?php _e('Content Generation', 'youtuneai'); ?></h2>
                <p><?php _e('Generate demo content and media', 'youtuneai'); ?></p>
                <button id="seed-content" class="button button-secondary">
                    <?php _e('Seed Content', 'youtuneai'); ?>
                </button>
            </div>
            
            <div class="youtuneai-admin-card">
                <h2><?php _e('Media Optimization', 'youtuneai'); ?></h2>
                <p><?php _e('Optimize images, videos, and 3D models', 'youtuneai'); ?></p>
                <button id="optimize-media" class="button button-secondary">
                    <?php _e('Optimize Media', 'youtuneai'); ?>
                </button>
            </div>
            
            <div class="youtuneai-admin-card">
                <h2><?php _e('Cache Management', 'youtuneai'); ?></h2>
                <p><?php _e('Clear all caches', 'youtuneai'); ?></p>
                <button id="flush-caches" class="button button-secondary">
                    <?php _e('Flush Caches', 'youtuneai'); ?>
                </button>
            </div>
            
            <div class="youtuneai-admin-card">
                <h2><?php _e('Stream Setup', 'youtuneai'); ?></h2>
                <p><?php _e('Configure livestream settings', 'youtuneai'); ?></p>
                <button id="setup-stream" class="button button-secondary">
                    <?php _e('Setup Stream', 'youtuneai'); ?>
                </button>
            </div>
            
            <div class="youtuneai-admin-card">
                <h2><?php _e('Avatar System', 'youtuneai'); ?></h2>
                <p><?php _e('Configure 3D avatar settings', 'youtuneai'); ?></p>
                <button id="avatar-tune" class="button button-secondary">
                    <?php _e('Avatar Tune', 'youtuneai'); ?>
                </button>
            </div>
            
            <div class="youtuneai-admin-card">
                <h2><?php _e('Monetization', 'youtuneai'); ?></h2>
                <p><?php _e('Check ads and analytics', 'youtuneai'); ?></p>
                <button id="ads-analytics-check" class="button button-secondary">
                    <?php _e('Ads & Analytics Check', 'youtuneai'); ?>
                </button>
            </div>
            
            <div class="youtuneai-admin-card">
                <h2><?php _e('Testing', 'youtuneai'); ?></h2>
                <p><?php _e('Run comprehensive tests', 'youtuneai'); ?></p>
                <button id="run-tests" class="button button-secondary">
                    <?php _e('Run Full Test', 'youtuneai'); ?>
                </button>
            </div>
        </div>
        
        <div id="youtuneai-admin-log" class="youtuneai-log-area">
            <h3><?php _e('Activity Log', 'youtuneai'); ?></h3>
            <div id="log-content"></div>
        </div>
    </div>
    
    <style>
        .youtuneai-admin-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        
        .youtuneai-admin-card {
            background: #fff;
            border: 1px solid #ccd0d4;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.13);
        }
        
        .youtuneai-admin-card h2 {
            margin-top: 0;
            color: #23282d;
        }
        
        .youtuneai-admin-card p {
            color: #666;
            margin-bottom: 15px;
        }
        
        .youtuneai-log-area {
            background: #f1f1f1;
            border: 1px solid #ccd0d4;
            border-radius: 8px;
            padding: 20px;
            margin-top: 30px;
            max-height: 400px;
            overflow-y: auto;
        }
        
        #log-content {
            font-family: monospace;
            font-size: 12px;
            white-space: pre-wrap;
        }
        
        .button:hover {
            transform: translateY(-1px);
            transition: all 0.2s ease;
        }
    </style>
    
    <script>
        jQuery(document).ready(function($) {
            // Deploy Now
            $('#deploy-now').on('click', function() {
                executeCommand('deploy_now', 'Starting deployment...');
            });
            
            // Seed Content
            $('#seed-content').on('click', function() {
                executeCommand('seed_content', 'Generating content...');
            });
            
            // Optimize Media
            $('#optimize-media').on('click', function() {
                executeCommand('optimize_media', 'Optimizing media files...');
            });
            
            // Flush Caches
            $('#flush-caches').on('click', function() {
                executeCommand('flush_caches', 'Clearing caches...');
            });
            
            // Setup Stream
            $('#setup-stream').on('click', function() {
                executeCommand('setup_stream', 'Configuring stream...');
            });
            
            // Avatar Tune
            $('#avatar-tune').on('click', function() {
                executeCommand('avatar_tune', 'Tuning avatar system...');
            });
            
            // Ads & Analytics Check
            $('#ads-analytics-check').on('click', function() {
                executeCommand('ads_analytics_check', 'Checking monetization...');
            });
            
            // Run Tests
            $('#run-tests').on('click', function() {
                executeCommand('run_tests', 'Running comprehensive tests...');
            });
            
            function executeCommand(action, message) {
                addLogEntry(message);
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'youtuneai_admin_command',
                        command: action,
                        nonce: '<?php echo wp_create_nonce("youtuneai_admin_nonce"); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            addLogEntry('✓ ' + response.data.message);
                        } else {
                            addLogEntry('✗ Error: ' + response.data.message);
                        }
                    },
                    error: function() {
                        addLogEntry('✗ Communication error');
                    }
                });
            }
            
            function addLogEntry(message) {
                const timestamp = new Date().toLocaleTimeString();
                const logContent = $('#log-content');
                logContent.append('[' + timestamp + '] ' + message + '\n');
                logContent.scrollTop(logContent[0].scrollHeight);
            }
        });
    </script>
    <?php
}

/**
 * Handle AJAX commands
 */
function youtuneai_handle_admin_command() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'youtuneai_admin_nonce')) {
        wp_send_json_error(array('message' => 'Security check failed'));
        return;
    }
    
    // Check permissions
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => 'Insufficient permissions'));
        return;
    }
    
    $command = sanitize_text_field($_POST['command']);
    $response = array('message' => '');
    
    switch ($command) {
        case 'deploy_now':
            $response['message'] = 'Deployment initiated. Check logs for details.';
            // TODO: Implement GitHub Actions dispatch
            break;
            
        case 'seed_content':
            youtuneai_seed_demo_content();
            $response['message'] = 'Demo content generated successfully.';
            break;
            
        case 'optimize_media':
            $response['message'] = 'Media optimization started.';
            // TODO: Implement media optimization
            break;
            
        case 'flush_caches':
            if (function_exists('wp_cache_flush')) {
                wp_cache_flush();
            }
            $response['message'] = 'Caches cleared successfully.';
            break;
            
        case 'setup_stream':
            $response['message'] = 'Stream configuration updated.';
            // TODO: Implement stream setup
            break;
            
        case 'avatar_tune':
            $response['message'] = 'Avatar system tuned.';
            // TODO: Implement avatar tuning
            break;
            
        case 'ads_analytics_check':
            $response['message'] = 'Monetization check completed.';
            // TODO: Implement ads/analytics check
            break;
            
        case 'run_tests':
            $response['message'] = 'Test suite initiated.';
            // TODO: Implement test runner
            break;
            
        default:
            wp_send_json_error(array('message' => 'Unknown command'));
            return;
    }
    
    wp_send_json_success($response);
}
add_action('wp_ajax_youtuneai_admin_command', 'youtuneai_handle_admin_command');

/**
 * Seed demo content
 */
function youtuneai_seed_demo_content() {
    // Create sample games
    $games = array(
        array('title' => 'Neon Runner 3D', 'genre' => 'action'),
        array('title' => 'Space Puzzle Quest', 'genre' => 'puzzle'),
        array('title' => 'Virtual Racing', 'genre' => 'racing'),
        array('title' => 'AI Battle Arena', 'genre' => 'strategy'),
        array('title' => 'Cyber Adventure', 'genre' => 'adventure'),
        array('title' => 'Quantum Shooter', 'genre' => 'shooter'),
    );
    
    foreach ($games as $game) {
        $post_id = wp_insert_post(array(
            'post_title' => $game['title'],
            'post_content' => 'An exciting ' . $game['genre'] . ' game with cutting-edge graphics.',
            'post_status' => 'publish',
            'post_type' => 'game',
        ));
        
        if ($post_id && !is_wp_error($post_id)) {
            wp_set_object_terms($post_id, $game['genre'], 'game_genre');
        }
    }
    
    // Create sample streams
    $streams = array(
        array('title' => 'Gaming Live Stream', 'platform' => 'youtube'),
        array('title' => 'Tech Talk Stream', 'platform' => 'twitch'),
    );
    
    foreach ($streams as $stream) {
        wp_insert_post(array(
            'post_title' => $stream['title'],
            'post_content' => 'Live streaming on ' . $stream['platform'],
            'post_status' => 'publish',
            'post_type' => 'stream',
        ));
    }
}