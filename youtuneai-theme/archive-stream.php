<?php
/**
 * Streams Archive Template
 * 
 * @package YouTuneAI
 */

get_header(); ?>

<main id="main" class="min-h-screen bg-background-dark pt-24">
    <div class="container mx-auto px-4 py-8">
        <!-- Hero Section -->
        <section class="text-center mb-12">
            <?php if (youtuneai_is_stream_live()) : ?>
                <div class="inline-flex items-center bg-red-600 text-white px-6 py-3 rounded-full mb-6 animate-pulse">
                    <div class="w-3 h-3 bg-white rounded-full animate-ping mr-3"></div>
                    <span class="font-semibold"><?php _e('LIVE NOW', 'youtuneai'); ?></span>
                </div>
            <?php endif; ?>
            
            <h1 class="text-5xl font-orbitron font-bold text-primary glow-text mb-4 animate-fade-in">
                <?php _e('Live Streams', 'youtuneai'); ?>
            </h1>
            <p class="text-xl text-platinum/80 mb-8 max-w-2xl mx-auto">
                <?php _e('Join our live streams for gaming, tech talks, and interactive experiences. Never miss a moment with notifications and VOD replays.', 'youtuneai'); ?>
            </p>
        </section>

        <!-- Current Live Stream -->
        <?php 
        $current_stream = youtuneai_get_current_stream();
        if ($current_stream && youtuneai_is_stream_live($current_stream->ID)) : 
            $embed_url = get_post_meta($current_stream->ID, 'embed_url', true);
            $platform = get_post_meta($current_stream->ID, 'platform', true);
        ?>
        <section class="mb-16">
            <div class="card-3d p-8 bg-gradient-to-r from-red-900/20 to-red-600/20 border border-red-500/30">
                <div class="flex flex-col lg:flex-row items-center space-y-6 lg:space-y-0 lg:space-x-8">
                    <div class="flex-1">
                        <div class="flex items-center mb-4">
                            <div class="w-4 h-4 bg-red-500 rounded-full animate-pulse mr-3"></div>
                            <span class="text-red-400 font-semibold uppercase tracking-wider">Live Now</span>
                        </div>
                        
                        <h2 class="text-3xl font-orbitron font-bold text-primary mb-4">
                            <?php echo get_the_title($current_stream->ID); ?>
                        </h2>
                        
                        <p class="text-platinum/80 mb-6">
                            <?php echo get_the_excerpt($current_stream->ID); ?>
                        </p>
                        
                        <div class="flex items-center space-x-6 text-sm text-platinum/60 mb-6">
                            <span class="flex items-center">
                                <i class="bx bxl-<?php echo esc_attr($platform); ?> mr-2 text-primary"></i>
                                <?php echo ucfirst($platform); ?>
                            </span>
                            <span class="flex items-center">
                                <i class="bx bx-time mr-2"></i>
                                Started <?php echo human_time_diff(get_the_time('U', $current_stream->ID), current_time('timestamp')); ?> ago
                            </span>
                            <span class="flex items-center">
                                <i class="bx bx-user mr-2"></i>
                                <span id="viewer-count">Loading...</span>
                            </span>
                        </div>
                        
                        <div class="flex space-x-4">
                            <button class="btn-primary" data-stream-watch="<?php echo $current_stream->ID; ?>">
                                <i class="bx bx-play mr-2"></i>
                                <?php _e('Watch Live', 'youtuneai'); ?>
                            </button>
                            <button class="btn-secondary" data-stream-chat="<?php echo $current_stream->ID; ?>">
                                <i class="bx bx-chat mr-2"></i>
                                <?php _e('Join Chat', 'youtuneai'); ?>
                            </button>
                            <button class="btn-secondary" data-stream-notify="<?php echo $current_stream->ID; ?>">
                                <i class="bx bx-bell mr-2"></i>
                                <?php _e('Notify', 'youtuneai'); ?>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Live Stream Preview -->
                    <div class="w-full lg:w-96">
                        <div class="aspect-video bg-black rounded-lg overflow-hidden relative group">
                            <?php if ($embed_url) : ?>
                                <iframe src="<?php echo esc_url($embed_url); ?>" 
                                        class="w-full h-full"
                                        frameborder="0" 
                                        allowfullscreen>
                                </iframe>
                            <?php else : ?>
                                <div class="flex items-center justify-center h-full">
                                    <div class="text-center">
                                        <i class="bx bx-video text-6xl text-primary/50 mb-4"></i>
                                        <p class="text-platinum/60"><?php _e('Stream starting soon...', 'youtuneai'); ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Live indicator overlay -->
                            <div class="absolute top-4 left-4 bg-red-600 text-white px-3 py-1 rounded-full text-xs font-semibold animate-pulse">
                                LIVE
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- Upcoming Streams -->
        <?php
        $upcoming_streams = get_posts(array(
            'post_type' => 'stream',
            'post_status' => 'publish',
            'posts_per_page' => 6,
            'meta_query' => array(
                array(
                    'key' => 'is_live',
                    'value' => '0',
                    'compare' => '='
                )
            ),
            'meta_key' => 'scheduled_time',
            'orderby' => 'meta_value',
            'order' => 'ASC'
        ));

        if (!empty($upcoming_streams)) :
        ?>
        <section class="mb-16">
            <h2 class="text-3xl font-orbitron font-bold text-primary mb-8 text-center">
                <?php _e('Upcoming Streams', 'youtuneai'); ?>
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($upcoming_streams as $stream) : 
                    $platform = get_post_meta($stream->ID, 'platform', true);
                    $scheduled_time = get_post_meta($stream->ID, 'scheduled_time', true);
                ?>
                    <article class="card-3d p-6 group hover:scale-105 transition-all duration-300">
                        <div class="relative mb-4 overflow-hidden rounded-lg">
                            <?php if (has_post_thumbnail($stream->ID)) : ?>
                                <?php echo get_the_post_thumbnail($stream->ID, 'medium', array('class' => 'w-full h-32 object-cover group-hover:scale-110 transition-transform duration-300')); ?>
                            <?php else : ?>
                                <div class="w-full h-32 bg-gradient-to-br from-secondary/20 to-primary/20 flex items-center justify-center">
                                    <i class="bx bx-video text-4xl text-secondary opacity-50"></i>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Platform badge -->
                            <span class="absolute top-2 right-2 bg-secondary/90 text-white px-2 py-1 rounded-full text-xs font-semibold">
                                <?php echo ucfirst($platform); ?>
                            </span>
                        </div>
                        
                        <div class="space-y-3">
                            <h3 class="text-lg font-orbitron font-bold text-primary group-hover:text-secondary transition-colors">
                                <?php echo get_the_title($stream->ID); ?>
                            </h3>
                            
                            <p class="text-platinum/80 text-sm line-clamp-2">
                                <?php echo get_the_excerpt($stream->ID); ?>
                            </p>
                            
                            <!-- Schedule info -->
                            <div class="flex items-center justify-between text-xs text-platinum/60">
                                <span class="flex items-center">
                                    <i class="bx bx-calendar mr-1"></i>
                                    <?php echo $scheduled_time ? date('M j, Y', strtotime($scheduled_time)) : __('TBD', 'youtuneai'); ?>
                                </span>
                                <span class="flex items-center">
                                    <i class="bx bx-time mr-1"></i>
                                    <?php echo $scheduled_time ? date('g:i A', strtotime($scheduled_time)) : __('TBD', 'youtuneai'); ?>
                                </span>
                            </div>
                            
                            <!-- Action buttons -->
                            <div class="flex space-x-2 pt-2">
                                <button class="btn-secondary flex-1 text-sm" data-stream-remind="<?php echo $stream->ID; ?>">
                                    <i class="bx bx-bell mr-1"></i>
                                    <?php _e('Remind Me', 'youtuneai'); ?>
                                </button>
                                <button class="btn-secondary text-sm px-4" data-stream-share="<?php echo $stream->ID; ?>">
                                    <i class="bx bx-share"></i>
                                </button>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- Stream Archive -->
        <section class="mb-12">
            <h2 class="text-3xl font-orbitron font-bold text-primary mb-8 text-center">
                <?php _e('Stream Archive', 'youtuneai'); ?>
            </h2>
            
            <?php
            $archived_streams = get_posts(array(
                'post_type' => 'stream',
                'post_status' => 'publish',
                'posts_per_page' => 9,
                'meta_query' => array(
                    array(
                        'key' => 'has_vod',
                        'value' => '1',
                        'compare' => '='
                    )
                ),
                'orderby' => 'date',
                'order' => 'DESC'
            ));

            if (!empty($archived_streams)) :
            ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($archived_streams as $stream) : 
                        $vod_url = get_post_meta($stream->ID, 'vod_url', true);
                        $duration = get_post_meta($stream->ID, 'duration', true);
                        $views = get_post_meta($stream->ID, 'view_count', true);
                    ?>
                        <article class="card-3d p-4 group hover:scale-105 transition-all duration-300">
                            <div class="relative mb-3 overflow-hidden rounded-lg">
                                <?php if (has_post_thumbnail($stream->ID)) : ?>
                                    <?php echo get_the_post_thumbnail($stream->ID, 'medium', array('class' => 'w-full h-28 object-cover group-hover:scale-110 transition-transform duration-300')); ?>
                                <?php endif; ?>
                                
                                <!-- Play overlay -->
                                <div class="absolute inset-0 bg-black/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <button class="bg-white text-black rounded-full p-3" data-vod-play="<?php echo $stream->ID; ?>">
                                        <i class="bx bx-play text-2xl"></i>
                                    </button>
                                </div>
                                
                                <!-- Duration badge -->
                                <?php if ($duration) : ?>
                                    <span class="absolute bottom-2 right-2 bg-black/80 text-white px-2 py-1 rounded text-xs">
                                        <?php echo $duration; ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <h3 class="text-sm font-semibold text-platinum mb-2 line-clamp-2">
                                <?php echo get_the_title($stream->ID); ?>
                            </h3>
                            
                            <div class="flex items-center justify-between text-xs text-platinum/60">
                                <span><?php echo get_the_date('M j, Y', $stream->ID); ?></span>
                                <?php if ($views) : ?>
                                    <span><?php echo number_format($views); ?> views</span>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
                
                <div class="text-center mt-8">
                    <a href="<?php echo home_url('/stream-archive'); ?>" class="btn-secondary">
                        <?php _e('View All Archives', 'youtuneai'); ?>
                    </a>
                </div>
            <?php else : ?>
                <div class="text-center py-12">
                    <i class="bx bx-video-off text-6xl text-primary/30 mb-4"></i>
                    <p class="text-platinum/60"><?php _e('No archived streams yet. Check back after our first live stream!', 'youtuneai'); ?></p>
                </div>
            <?php endif; ?>
        </section>

        <!-- Notification Settings -->
        <section class="card-3d p-8 text-center bg-gradient-to-r from-secondary/10 to-primary/10 border border-secondary/20">
            <h3 class="text-2xl font-orbitron font-bold text-primary mb-4">
                <?php _e('Never Miss a Stream', 'youtuneai'); ?>
            </h3>
            <p class="text-platinum/80 mb-6">
                <?php _e('Get notified when we go live and join the community for the best streaming experience.', 'youtuneai'); ?>
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center max-w-md mx-auto">
                <input type="email" 
                       placeholder="<?php _e('Your email address', 'youtuneai'); ?>" 
                       class="flex-1 p-3 bg-background-dark border border-platinum/30 rounded-lg text-platinum placeholder-platinum/50 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
                <button class="btn-primary px-6">
                    <i class="bx bx-bell mr-2"></i>
                    <?php _e('Notify Me', 'youtuneai'); ?>
                </button>
            </div>
        </section>
    </div>
</main>

<!-- Stream Modal -->
<div id="stream-modal" class="modal fixed inset-0 bg-black/95 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="modal-content bg-background-dark border border-platinum/20 rounded-xl max-w-6xl w-full h-full max-h-[90vh] p-6 flex flex-col">
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center space-x-4">
                <h2 id="stream-title" class="text-2xl font-orbitron font-bold text-primary"></h2>
                <div class="flex items-center bg-red-600 text-white px-3 py-1 rounded-full text-sm animate-pulse">
                    <div class="w-2 h-2 bg-white rounded-full animate-ping mr-2"></div>
                    LIVE
                </div>
            </div>
            <button data-modal-close class="text-platinum hover:text-primary transition-colors p-2">
                <i class="bx bx-x text-2xl"></i>
            </button>
        </div>
        
        <div class="flex-1 grid grid-cols-1 lg:grid-cols-4 gap-4">
            <!-- Video Player -->
            <div class="lg:col-span-3 bg-black rounded-lg overflow-hidden">
                <div id="stream-player" class="w-full h-full min-h-[400px]">
                    <div class="flex items-center justify-center h-full">
                        <div class="text-center">
                            <div class="animate-spin w-12 h-12 border-4 border-primary border-t-transparent rounded-full mb-4 mx-auto"></div>
                            <p class="text-platinum">Loading stream...</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Chat Sidebar -->
            <div class="bg-background-card rounded-lg p-4 flex flex-col">
                <h3 class="font-orbitron font-bold text-primary mb-4 flex items-center">
                    <i class="bx bx-chat mr-2"></i>
                    Chat
                </h3>
                
                <div id="chat-messages" class="flex-1 overflow-y-auto space-y-2 mb-4 min-h-[200px]">
                    <!-- Chat messages will be populated here -->
                </div>
                
                <div class="flex space-x-2">
                    <input type="text" 
                           id="chat-input" 
                           placeholder="<?php _e('Type a message...', 'youtuneai'); ?>" 
                           class="flex-1 p-2 bg-background-dark border border-platinum/30 rounded text-platinum placeholder-platinum/50 text-sm">
                    <button id="send-chat" class="bg-primary text-white p-2 rounded hover:bg-secondary transition-colors">
                        <i class="bx bx-send"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>