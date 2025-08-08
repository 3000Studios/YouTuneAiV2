<?php
/**
 * Games Archive Template
 * 
 * @package YouTuneAI
 */

get_header(); ?>

<main id="main" class="min-h-screen bg-background-dark pt-24">
    <div class="container mx-auto px-4 py-8">
        <!-- Hero Section -->
        <section class="text-center mb-12">
            <h1 class="text-5xl font-orbitron font-bold text-primary glow-text mb-4 animate-fade-in">
                <?php _e('Games Collection', 'youtuneai'); ?>
            </h1>
            <p class="text-xl text-platinum/80 mb-8 max-w-2xl mx-auto">
                <?php _e('Experience cutting-edge browser games with stunning graphics and immersive gameplay. All games are optimized for desktop and mobile.', 'youtuneai'); ?>
            </p>
            
            <!-- Game Stats -->
            <div class="flex justify-center space-x-8 mb-8">
                <div class="text-center">
                    <div class="text-3xl font-orbitron font-bold text-accent">6</div>
                    <div class="text-platinum/60"><?php _e('Games', 'youtuneai'); ?></div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-orbitron font-bold text-secondary">∞</div>
                    <div class="text-platinum/60"><?php _e('Fun', 'youtuneai'); ?></div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-orbitron font-bold text-primary">HD</div>
                    <div class="text-platinum/60"><?php _e('Quality', 'youtuneai'); ?></div>
                </div>
            </div>
        </section>

        <!-- Games Grid -->
        <section class="games-grid">
            <?php
            $featured_games = youtuneai_get_featured_games(6);
            
            if (!empty($featured_games)) :
            ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                    <?php foreach ($featured_games as $game) : ?>
                        <article class="game-card card-3d p-6 group hover:scale-105 transition-all duration-300">
                            <div class="relative mb-4 overflow-hidden rounded-lg">
                                <?php if (has_post_thumbnail($game->ID)) : ?>
                                    <?php echo get_the_post_thumbnail($game->ID, 'medium', array('class' => 'w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300')); ?>
                                <?php else : ?>
                                    <div class="w-full h-48 bg-gradient-to-br from-primary/20 to-secondary/20 flex items-center justify-center">
                                        <i class="bx bx-game text-6xl text-primary opacity-50"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Play overlay -->
                                <div class="absolute inset-0 bg-black/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <button class="btn-primary" data-game-play="<?php echo $game->ID; ?>">
                                        <i class="bx bx-play mr-2"></i>
                                        <?php _e('Play Now', 'youtuneai'); ?>
                                    </button>
                                </div>
                                
                                <!-- Genre badge -->
                                <?php 
                                $genres = get_the_terms($game->ID, 'game_genre');
                                if ($genres && !is_wp_error($genres)) : 
                                ?>
                                    <span class="absolute top-2 right-2 bg-primary/90 text-white px-2 py-1 rounded-full text-xs font-semibold">
                                        <?php echo esc_html($genres[0]->name); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="space-y-3">
                                <h2 class="text-xl font-orbitron font-bold text-primary group-hover:text-secondary transition-colors">
                                    <?php echo get_the_title($game->ID); ?>
                                </h2>
                                
                                <p class="text-platinum/80 text-sm line-clamp-2">
                                    <?php echo get_the_excerpt($game->ID); ?>
                                </p>
                                
                                <!-- Game metadata -->
                                <div class="flex items-center justify-between text-xs text-platinum/60">
                                    <span><?php echo get_post_meta($game->ID, 'platform', true) ?: 'WebGL'; ?></span>
                                    <span class="flex items-center">
                                        <i class="bx bx-time mr-1"></i>
                                        <?php echo human_time_diff(get_the_time('U', $game->ID), current_time('timestamp')); ?> ago
                                    </span>
                                </div>
                                
                                <!-- Action buttons -->
                                <div class="flex space-x-2 pt-2">
                                    <button class="btn-primary flex-1 text-sm" data-game-play="<?php echo $game->ID; ?>">
                                        <i class="bx bx-play mr-1"></i>
                                        <?php _e('Play', 'youtuneai'); ?>
                                    </button>
                                    <button class="btn-secondary text-sm px-4" data-game-info="<?php echo $game->ID; ?>">
                                        <i class="bx bx-info-circle"></i>
                                    </button>
                                    <button class="btn-secondary text-sm px-4" data-game-favorite="<?php echo $game->ID; ?>">
                                        <i class="bx bx-heart"></i>
                                    </button>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <div class="text-center py-16">
                    <i class="bx bx-game text-8xl text-primary/30 mb-4"></i>
                    <h2 class="text-2xl font-orbitron font-bold text-platinum mb-4">
                        <?php _e('Games Coming Soon!', 'youtuneai'); ?>
                    </h2>
                    <p class="text-platinum/60 mb-6">
                        <?php _e('We\'re working hard to bring you amazing games. Check back soon!', 'youtuneai'); ?>
                    </p>
                    <button class="btn-primary" id="notify-games">
                        <?php _e('Notify Me', 'youtuneai'); ?>
                    </button>
                </div>
            <?php endif; ?>
        </section>

        <!-- Game Categories -->
        <section class="mb-12">
            <h2 class="text-3xl font-orbitron font-bold text-primary mb-8 text-center">
                <?php _e('Browse by Genre', 'youtuneai'); ?>
            </h2>
            
            <div class="flex flex-wrap justify-center gap-4">
                <?php
                $genres = get_terms(array(
                    'taxonomy' => 'game_genre',
                    'hide_empty' => false,
                ));
                
                if (!is_wp_error($genres) && !empty($genres)) :
                    foreach ($genres as $genre) :
                ?>
                    <a href="<?php echo get_term_link($genre); ?>" 
                       class="bg-background-card hover:bg-primary/20 border border-platinum/20 hover:border-primary px-6 py-3 rounded-lg transition-all duration-300 text-platinum hover:text-primary">
                        <?php echo esc_html($genre->name); ?>
                        <span class="ml-2 text-sm opacity-60">(<?php echo $genre->count; ?>)</span>
                    </a>
                <?php 
                    endforeach;
                else :
                ?>
                    <p class="text-platinum/60"><?php _e('No game genres available yet.', 'youtuneai'); ?></p>
                <?php endif; ?>
            </div>
        </section>

        <!-- Performance Notice -->
        <section class="card-3d p-6 text-center bg-gradient-to-r from-primary/10 to-secondary/10 border border-primary/20">
            <h3 class="text-xl font-orbitron font-bold text-primary mb-3">
                <?php _e('Optimized Gaming Experience', 'youtuneai'); ?>
            </h3>
            <p class="text-platinum/80 mb-4">
                <?php _e('All games are optimized for web performance with memory management and 60fps gameplay.', 'youtuneai'); ?>
            </p>
            <div class="flex justify-center items-center space-x-8 text-sm">
                <div class="flex items-center text-accent">
                    <i class="bx bx-check-circle mr-2"></i>
                    <?php _e('60 FPS', 'youtuneai'); ?>
                </div>
                <div class="flex items-center text-accent">
                    <i class="bx bx-check-circle mr-2"></i>
                    <?php _e('Mobile Optimized', 'youtuneai'); ?>
                </div>
                <div class="flex items-center text-accent">
                    <i class="bx bx-check-circle mr-2"></i>
                    <?php _e('WebGL Powered', 'youtuneai'); ?>
                </div>
            </div>
        </section>
    </div>
</main>

<!-- Game Modal -->
<div id="game-modal" class="modal fixed inset-0 bg-black/90 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="modal-content bg-background-dark border border-platinum/20 rounded-xl max-w-6xl w-full h-full max-h-[80vh] p-6 flex flex-col">
        <div class="flex justify-between items-center mb-4">
            <h2 id="game-title" class="text-2xl font-orbitron font-bold text-primary"></h2>
            <button data-modal-close class="text-platinum hover:text-primary transition-colors p-2">
                <i class="bx bx-x text-2xl"></i>
            </button>
        </div>
        
        <div class="flex-1 bg-black rounded-lg overflow-hidden">
            <canvas id="game-canvas" class="w-full h-full"></canvas>
            <div id="game-loading" class="flex items-center justify-center h-full">
                <div class="text-center">
                    <div class="animate-spin w-12 h-12 border-4 border-primary border-t-transparent rounded-full mb-4 mx-auto"></div>
                    <p class="text-platinum">Loading game...</p>
                </div>
            </div>
        </div>
        
        <div class="flex justify-between items-center mt-4">
            <div class="flex space-x-4">
                <button id="game-fullscreen" class="btn-secondary">
                    <i class="bx bx-fullscreen mr-2"></i>
                    <?php _e('Fullscreen', 'youtuneai'); ?>
                </button>
                <button id="game-settings" class="btn-secondary">
                    <i class="bx bx-cog mr-2"></i>
                    <?php _e('Settings', 'youtuneai'); ?>
                </button>
            </div>
            
            <div class="flex items-center space-x-4 text-sm text-platinum/60">
                <span id="game-fps">FPS: --</span>
                <span id="game-memory">Memory: --</span>
            </div>
        </div>
    </div>
</div>

<script>
// Initialize games system
document.addEventListener('DOMContentLoaded', function() {
    // Load games JavaScript
    const script = document.createElement('script');
    script.src = '<?php echo YOUTUNEAI_THEME_URL; ?>/assets/js/dist/games.js';
    document.head.appendChild(script);
});
</script>

<?php get_footer(); ?>