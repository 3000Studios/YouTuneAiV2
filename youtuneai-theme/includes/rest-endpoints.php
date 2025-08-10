<?php
/**
 * REST API Endpoints
 * 
 * @package YouTuneAI
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register REST API routes
 */
function youtuneai_register_rest_routes() {
    // Avatar API endpoints
    register_rest_route('youtuneai/v1', '/avatar/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'youtuneai_get_avatar',
        'permission_callback' => '__return_true',
        'args' => array(
            'id' => array(
                'validate_callback' => function($param, $request, $key) {
                    return is_numeric($param);
                }
            ),
        ),
    ));
    
    register_rest_route('youtuneai/v1', '/avatar/chat', array(
        'methods' => 'POST',
        'callback' => 'youtuneai_avatar_chat',
        'permission_callback' => '__return_true',
    ));
    
    // Games API endpoints
    register_rest_route('youtuneai/v1', '/games', array(
        'methods' => 'GET',
        'callback' => 'youtuneai_get_games',
        'permission_callback' => '__return_true',
    ));
    
    // Streams API endpoints
    register_rest_route('youtuneai/v1', '/streams/live', array(
        'methods' => 'GET',
        'callback' => 'youtuneai_get_live_streams',
        'permission_callback' => '__return_true',
    ));
    
    // Garage API endpoints
    register_rest_route('youtuneai/v1', '/garage/parts', array(
        'methods' => 'GET',
        'callback' => 'youtuneai_get_garage_parts',
        'permission_callback' => '__return_true',
    ));
    
    register_rest_route('youtuneai/v1', '/garage/configure', array(
        'methods' => 'POST',
        'callback' => 'youtuneai_configure_garage_product',
        'permission_callback' => 'youtuneai_check_auth',
    ));
    
    // VR Room API endpoints
    register_rest_route('youtuneai/v1', '/vr/rooms', array(
        'methods' => 'GET',
        'callback' => 'youtuneai_get_vr_rooms',
        'permission_callback' => '__return_true',
    ));
}
add_action('rest_api_init', 'youtuneai_register_rest_routes');

/**
 * Get avatar data
 */
function youtuneai_get_avatar($request) {
    $avatar_id = $request['id'];
    $avatar = get_post($avatar_id);
    
    if (!$avatar || $avatar->post_type !== 'avatar') {
        return new WP_Error('avatar_not_found', 'Avatar not found', array('status' => 404));
    }
    
    $avatar_data = array(
        'id' => $avatar->ID,
        'title' => $avatar->post_title,
        'model_path' => get_post_meta($avatar->ID, 'model_path', true),
        'voice_id' => get_post_meta($avatar->ID, 'voice_id', true),
        'lip_sync_profile' => get_post_meta($avatar->ID, 'lip_sync_profile', true),
        'colorway' => get_post_meta($avatar->ID, 'colorway', true),
        'settings' => get_post_meta($avatar->ID, 'settings', true),
    );
    
    return rest_ensure_response($avatar_data);
}

/**
 * Handle avatar chat
 */
function youtuneai_avatar_chat($request) {
    $message = sanitize_textarea_field($request->get_param('message'));
    $avatar_id = intval($request->get_param('avatar_id'));
    
    if (empty($message)) {
        return new WP_Error('empty_message', 'Message cannot be empty', array('status' => 400));
    }
    
    // TODO: Integrate with AI service (OpenAI, etc.)
    // For now, return a mock response
    $response = array(
        'message' => 'I understand you said: "' . $message . '". How can I help you today?',
        'emotion' => 'neutral',
        'visemes' => array(), // Lip-sync data would go here
        'timestamp' => current_time('timestamp'),
    );
    
    return rest_ensure_response($response);
}

/**
 * Get games
 */
function youtuneai_get_games($request) {
    $args = array(
        'post_type' => 'game',
        'post_status' => 'publish',
        'posts_per_page' => 12,
    );
    
    $games_query = new WP_Query($args);
    $games = array();
    
    while ($games_query->have_posts()) {
        $games_query->the_post();
        $game_id = get_the_ID();
        
        $games[] = array(
            'id' => $game_id,
            'title' => get_the_title(),
            'excerpt' => get_the_excerpt(),
            'thumbnail' => get_the_post_thumbnail_url($game_id, 'medium'),
            'platform' => get_post_meta($game_id, 'platform', true),
            'build_path' => get_post_meta($game_id, 'build_path', true),
            'play_url' => get_post_meta($game_id, 'play_url', true),
            'screenshots' => get_post_meta($game_id, 'screenshots', true),
            'genres' => wp_get_post_terms($game_id, 'game_genre', array('fields' => 'names')),
        );
    }
    
    wp_reset_postdata();
    
    return rest_ensure_response($games);
}

/**
 * Get live streams
 */
function youtuneai_get_live_streams($request) {
    $args = array(
        'post_type' => 'stream',
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => 'is_live',
                'value' => '1',
                'compare' => '='
            )
        )
    );
    
    $streams_query = new WP_Query($args);
    $streams = array();
    
    while ($streams_query->have_posts()) {
        $streams_query->the_post();
        $stream_id = get_the_ID();
        
        $streams[] = array(
            'id' => $stream_id,
            'title' => get_the_title(),
            'platform' => get_post_meta($stream_id, 'platform', true),
            'embed_url' => get_post_meta($stream_id, 'embed_url', true),
            'stream_key' => get_post_meta($stream_id, 'stream_key', true),
            'schedule' => get_post_meta($stream_id, 'schedule', true),
            'thumbnail' => get_the_post_thumbnail_url($stream_id, 'medium'),
        );
    }
    
    wp_reset_postdata();
    
    return rest_ensure_response($streams);
}

/**
 * Get garage parts
 */
function youtuneai_get_garage_parts($request) {
    $args = array(
        'post_type' => 'garage_part',
        'post_status' => 'publish',
        'posts_per_page' => -1,
    );
    
    $parts_query = new WP_Query($args);
    $parts = array();
    
    while ($parts_query->have_posts()) {
        $parts_query->the_post();
        $part_id = get_the_ID();
        
        $parts[] = array(
            'id' => $part_id,
            'title' => get_the_title(),
            'description' => get_the_excerpt(),
            'type' => get_post_meta($part_id, 'part_type', true),
            'model_path' => get_post_meta($part_id, 'model_path', true),
            'metadata' => get_post_meta($part_id, 'metadata', true),
            'price' => get_post_meta($part_id, 'price', true),
            'compatibility' => get_post_meta($part_id, 'compatibility', true),
            'thumbnail' => get_the_post_thumbnail_url($part_id, 'medium'),
            'categories' => wp_get_post_terms($part_id, 'part_type', array('fields' => 'names')),
        );
    }
    
    wp_reset_postdata();
    
    return rest_ensure_response($parts);
}

/**
 * Configure garage product
 */
function youtuneai_configure_garage_product($request) {
    $selected_parts = $request->get_param('parts');
    $configuration = $request->get_param('configuration');
    
    if (empty($selected_parts)) {
        return new WP_Error('no_parts', 'No parts selected', array('status' => 400));
    }
    
    // Calculate total price
    $total_price = 0;
    $parts_data = array();
    
    foreach ($selected_parts as $part_id) {
        $part = get_post($part_id);
        if ($part && $part->post_type === 'garage_part') {
            $price = floatval(get_post_meta($part_id, 'price', true));
            $total_price += $price;
            
            $parts_data[] = array(
                'id' => $part_id,
                'title' => $part->post_title,
                'price' => $price,
                'model_path' => get_post_meta($part_id, 'model_path', true),
            );
        }
    }
    
    // TODO: Create or update WooCommerce composite product
    // For now, return configuration data
    $response = array(
        'parts' => $parts_data,
        'total_price' => $total_price,
        'configuration' => $configuration,
        'product_url' => home_url('/garage-configured-product/'),
    );
    
    return rest_ensure_response($response);
}

/**
 * Get VR rooms
 */
function youtuneai_get_vr_rooms($request) {
    $args = array(
        'post_type' => 'vr_room',
        'post_status' => 'publish',
        'posts_per_page' => -1,
    );
    
    $rooms_query = new WP_Query($args);
    $rooms = array();
    
    while ($rooms_query->have_posts()) {
        $rooms_query->the_post();
        $room_id = get_the_ID();
        
        $rooms[] = array(
            'id' => $room_id,
            'title' => get_the_title(),
            'description' => get_the_excerpt(),
            'scene_config' => get_post_meta($room_id, 'scene_config', true),
            'media_playlist' => get_post_meta($room_id, 'media_playlist', true),
            'ad_zones' => get_post_meta($room_id, 'ad_zones', true),
            'thumbnail' => get_the_post_thumbnail_url($room_id, 'medium'),
            'entry_url' => get_permalink($room_id),
        );
    }
    
    wp_reset_postdata();
    
    return rest_ensure_response($rooms);
}

/**
 * Check authentication for protected endpoints
 */
function youtuneai_check_auth($request) {
    return is_user_logged_in();
}