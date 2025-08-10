<?php
/**
 * Home Page Template
 * 
 * @package YouTuneAI
 */

get_header(); ?>

<main id="main" class="bg-background-dark">
    <!-- Hero Section -->
    <section class="min-h-screen flex items-center justify-center relative overflow-hidden">
        <!-- Animated Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-background-dark via-gunmetal/30 to-background-dark"></div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGcgZmlsbD0ibm9uZSIgZmlsbC1ydWxlPSJldmVub2RkIj4KPGcgZmlsbD0iIzlkMDBmZiIgZmlsbC1vcGFjaXR5PSIwLjAzIj4KPGNpcmNsZSBjeD0iMzAiIGN5PSIzMCIgcj0iNCIvPgo8L2c+CjwvZz4KPC9zdmc+')] opacity-30"></div>

        <div class="relative z-10 text-center px-4">
            <!-- 3D Avatar Display -->
            <div class="mb-8">
                <?php echo do_shortcode('[youtuneai_avatar width="300" height="400" interactive="true" voice="true"]'); ?>
            </div>

            <h1 class="text-6xl md:text-8xl font-orbitron font-black text-primary glow-text mb-6 animate-fade-in">
                YouTuneAI
                <span class="block text-3xl md:text-5xl text-secondary font-raleway font-light">
                    The Future is Now
                </span>
            </h1>

            <p class="text-xl md:text-2xl text-platinum/80 mb-8 max-w-3xl mx-auto leading-relaxed">
                <?php _e('Experience the next generation of AI-powered entertainment with 3D avatars, immersive games, live streaming, and VR experiences.', 'youtuneai'); ?>
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
                <button class="btn-primary text-lg px-8 py-4" data-modal-open="avatar-modal">
                    <i class="bx bx-chat mr-3"></i>
                    <?php _e('Chat with AI', 'youtuneai'); ?>
                </button>
                <a href="<?php echo home_url('/games'); ?>" class="btn-secondary text-lg px-8 py-4">
                    <i class="bx bx-game mr-3"></i>
                    <?php _e('Play Games', 'youtuneai'); ?>
                </a>
                <a href="<?php echo home_url('/vr-room'); ?>" class="btn-secondary text-lg px-8 py-4">
                    <i class="bx bx-glasses-alt mr-3"></i>
                    <?php _e('Enter VR', 'youtuneai'); ?>
                </a>
            </div>

            <!-- Live Status -->
            <?php if (youtuneai_is_stream_live()) : ?>
                <div class="inline-flex items-center bg-red-600 text-white px-6 py-3 rounded-full animate-pulse mb-8">
                    <div class="w-3 h-3 bg-white rounded-full animate-ping mr-3"></div>
                    <span class="font-semibold mr-4"><?php _e('STREAMING LIVE NOW', 'youtuneai'); ?></span>
                    <a href="<?php echo home_url('/streams'); ?>" class="underline hover:no-underline">
                        <?php _e('Watch →', 'youtuneai'); ?>
                    </a>
                </div>
            <?php endif; ?>

            <!-- Scroll indicator -->
            <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2">
                <div class="animate-bounce">
                    <i class="bx bx-chevron-down text-3xl text-primary/60"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 px-4">
        <div class="container mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-orbitron font-bold text-primary mb-6 glow-text">
                    <?php _e('Revolutionary Features', 'youtuneai'); ?>
                </h2>
                <p class="text-xl text-platinum/70 max-w-3xl mx-auto">
                    <?php _e('Discover cutting-edge technology that brings the future of entertainment to your browser today.', 'youtuneai'); ?>
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- 3D Avatar -->
                <div class="card-3d p-8 text-center group hover:scale-105 transition-all duration-300">
                    <div class="mb-6">
                        <i class="bx bx-user-voice text-6xl text-primary group-hover:text-secondary transition-colors"></i>
                    </div>
                    <h3 class="text-2xl font-orbitron font-bold text-primary mb-4">
                        <?php _e('3D AI Avatar', 'youtuneai'); ?>
                    </h3>
                    <p class="text-platinum/80 mb-6">
                        <?php _e('Interact with our advanced 3D avatar powered by AI. Features voice recognition, lip-sync, and emotional responses.', 'youtuneai'); ?>
                    </p>
                    <button class="btn-primary" data-modal-open="avatar-modal">
                        <?php _e('Try Now', 'youtuneai'); ?>
                    </button>
                </div>

                <!-- Games -->
                <div class="card-3d p-8 text-center group hover:scale-105 transition-all duration-300">
                    <div class="mb-6">
                        <i class="bx bx-game text-6xl text-secondary group-hover:text-accent transition-colors"></i>
                    </div>
                    <h3 class="text-2xl font-orbitron font-bold text-primary mb-4">
                        <?php _e('WebGL Games', 'youtuneai'); ?>
                    </h3>
                    <p class="text-platinum/80 mb-6">
                        <?php _e('Play high-performance WebGL games directly in your browser. No downloads, just instant gaming action.', 'youtuneai'); ?>
                    </p>
                    <a href="<?php echo home_url('/games'); ?>" class="btn-primary">
                        <?php _e('Play Games', 'youtuneai'); ?>
                    </a>
                </div>

                <!-- VR Experience -->
                <div class="card-3d p-8 text-center group hover:scale-105 transition-all duration-300">
                    <div class="mb-6">
                        <i class="bx bx-glasses-alt text-6xl text-accent group-hover:text-primary transition-colors"></i>
                    </div>
                    <h3 class="text-2xl font-orbitron font-bold text-primary mb-4">
                        <?php _e('VR Room', 'youtuneai'); ?>
                    </h3>
                    <p class="text-platinum/80 mb-6">
                        <?php _e('Step into immersive virtual reality experiences. Optimized for Quest 3 and WebXR compatible devices.', 'youtuneai'); ?>
                    </p>
                    <a href="<?php echo home_url('/vr-room'); ?>" class="btn-primary">
                        <?php _e('Enter VR', 'youtuneai'); ?>
                    </a>
                </div>

                <!-- Live Streaming -->
                <div class="card-3d p-8 text-center group hover:scale-105 transition-all duration-300">
                    <div class="mb-6">
                        <i class="bx bx-broadcast text-6xl text-primary group-hover:text-secondary transition-colors"></i>
                    </div>
                    <h3 class="text-2xl font-orbitron font-bold text-primary mb-4">
                        <?php _e('Live Streaming', 'youtuneai'); ?>
                    </h3>
                    <p class="text-platinum/80 mb-6">
                        <?php _e('Join our live streams for gaming sessions, tech talks, and interactive community events.', 'youtuneai'); ?>
                    </p>
                    <a href="<?php echo home_url('/streams'); ?>" class="btn-primary">
                        <?php _e('Watch Live', 'youtuneai'); ?>
                    </a>
                </div>

                <!-- YouTune Garage -->
                <div class="card-3d p-8 text-center group hover:scale-105 transition-all duration-300">
                    <div class="mb-6">
                        <i class="bx bx-car text-6xl text-secondary group-hover:text-accent transition-colors"></i>
                    </div>
                    <h3 class="text-2xl font-orbitron font-bold text-primary mb-4">
                        <?php _e('YouTune Garage', 'youtuneai'); ?>
                    </h3>
                    <p class="text-platinum/80 mb-6">
                        <?php _e('Customize your dream ride with our 3D configurator. Build, visualize, and purchase parts in real-time.', 'youtuneai'); ?>
                    </p>
                    <a href="<?php echo home_url('/garage'); ?>" class="btn-primary">
                        <?php _e('Build Now', 'youtuneai'); ?>
                    </a>
                </div>

                <!-- E-Commerce -->
                <div class="card-3d p-8 text-center group hover:scale-105 transition-all duration-300">
                    <div class="mb-6">
                        <i class="bx bx-store text-6xl text-accent group-hover:text-primary transition-colors"></i>
                    </div>
                    <h3 class="text-2xl font-orbitron font-bold text-primary mb-4">
                        <?php _e('Premium Store', 'youtuneai'); ?>
                    </h3>
                    <p class="text-platinum/80 mb-6">
                        <?php _e('Shop exclusive digital products, game assets, VR experiences, and premium features.', 'youtuneai'); ?>
                    </p>
                    <a href="<?php echo home_url('/shop'); ?>" class="btn-primary">
                        <?php _e('Shop Now', 'youtuneai'); ?>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Performance Stats -->
    <section class="py-16 px-4 bg-gradient-to-r from-primary/10 to-secondary/10">
        <div class="container mx-auto text-center">
            <h2 class="text-3xl font-orbitron font-bold text-primary mb-12">
                <?php _e('Performance Excellence', 'youtuneai'); ?>
            </h2>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <?php $performance = youtuneai_get_performance_score(); ?>
                <div class="text-center">
                    <div class="text-4xl font-orbitron font-bold text-accent mb-2"><?php echo $performance['performance']; ?></div>
                    <div class="text-platinum/60"><?php _e('Performance', 'youtuneai'); ?></div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-orbitron font-bold text-accent mb-2"><?php echo $performance['accessibility']; ?></div>
                    <div class="text-platinum/60"><?php _e('Accessibility', 'youtuneai'); ?></div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-orbitron font-bold text-accent mb-2"><?php echo $performance['seo']; ?></div>
                    <div class="text-platinum/60"><?php _e('SEO Score', 'youtuneai'); ?></div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-orbitron font-bold text-accent mb-2">A+</div>
                    <div class="text-platinum/60"><?php _e('Security', 'youtuneai'); ?></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Recent Content -->
    <section class="py-20 px-4">
        <div class="container mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-orbitron font-bold text-primary mb-6">
                    <?php _e('Latest Updates', 'youtuneai'); ?>
                </h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Latest Games -->
                <div>
                    <h3 class="text-2xl font-orbitron font-bold text-secondary mb-6">
                        <?php _e('New Games', 'youtuneai'); ?>
                    </h3>
                    <?php
                    $recent_games = get_posts(array(
                        'post_type' => 'game',
                        'posts_per_page' => 3,
                        'orderby' => 'date',
                        'order' => 'DESC'
                    ));
                    
                    if ($recent_games) :
                    ?>
                        <div class="space-y-4">
                            <?php foreach ($recent_games as $game) : ?>
                                <div class="card-3d p-4 flex items-center space-x-4 group hover:scale-102 transition-all duration-300">
                                    <div class="w-16 h-16 bg-primary/20 rounded-lg flex items-center justify-center">
                                        <i class="bx bx-game text-2xl text-primary"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-orbitron font-bold text-primary group-hover:text-secondary transition-colors">
                                            <?php echo get_the_title($game->ID); ?>
                                        </h4>
                                        <p class="text-platinum/60 text-sm">
                                            <?php echo wp_trim_words(get_the_excerpt($game->ID), 15); ?>
                                        </p>
                                    </div>
                                    <button class="btn-primary text-sm" data-game-play="<?php echo $game->ID; ?>">
                                        <?php _e('Play', 'youtuneai'); ?>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Latest Streams -->
                <div>
                    <h3 class="text-2xl font-orbitron font-bold text-secondary mb-6">
                        <?php _e('Recent Streams', 'youtuneai'); ?>
                    </h3>
                    <?php
                    $recent_streams = get_posts(array(
                        'post_type' => 'stream',
                        'posts_per_page' => 3,
                        'orderby' => 'date',
                        'order' => 'DESC'
                    ));
                    
                    if ($recent_streams) :
                    ?>
                        <div class="space-y-4">
                            <?php foreach ($recent_streams as $stream) : ?>
                                <div class="card-3d p-4 flex items-center space-x-4 group hover:scale-102 transition-all duration-300">
                                    <div class="w-16 h-16 bg-secondary/20 rounded-lg flex items-center justify-center">
                                        <i class="bx bx-video text-2xl text-secondary"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-orbitron font-bold text-primary group-hover:text-secondary transition-colors">
                                            <?php echo get_the_title($stream->ID); ?>
                                        </h4>
                                        <p class="text-platinum/60 text-sm">
                                            <?php echo get_the_date('M j, Y', $stream->ID); ?>
                                        </p>
                                    </div>
                                    <a href="<?php echo get_permalink($stream->ID); ?>" class="btn-primary text-sm">
                                        <?php _e('Watch', 'youtuneai'); ?>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>