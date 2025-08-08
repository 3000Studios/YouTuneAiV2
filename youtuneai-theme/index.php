<?php
/**
 * Main template file
 * 
 * @package YouTuneAI
 */

get_header(); ?>

<main class="min-h-screen bg-background-dark">
    <div class="container mx-auto px-4 py-8">
        <?php if (have_posts()) : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php while (have_posts()) : the_post(); ?>
                    <article class="card-3d p-6">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="mb-4">
                                <?php the_post_thumbnail('medium', array('class' => 'w-full h-48 object-cover rounded-lg')); ?>
                            </div>
                        <?php endif; ?>
                        
                        <h2 class="text-xl font-orbitron font-bold mb-3 glow-text">
                            <a href="<?php the_permalink(); ?>" class="text-primary hover:text-secondary transition-colors">
                                <?php the_title(); ?>
                            </a>
                        </h2>
                        
                        <div class="text-platinum/80 mb-4">
                            <?php the_excerpt(); ?>
                        </div>
                        
                        <a href="<?php the_permalink(); ?>" class="btn-primary inline-block">
                            <?php _e('Read More', 'youtuneai'); ?>
                        </a>
                    </article>
                <?php endwhile; ?>
            </div>
            
            <div class="mt-8">
                <?php the_posts_pagination(array(
                    'prev_text' => __('← Previous', 'youtuneai'),
                    'next_text' => __('Next →', 'youtuneai'),
                    'class' => 'flex justify-center space-x-4'
                )); ?>
            </div>
        <?php else : ?>
            <div class="text-center py-16">
                <h1 class="text-4xl font-orbitron font-bold mb-4 glow-text">
                    <?php _e('No content found', 'youtuneai'); ?>
                </h1>
                <p class="text-platinum/80 mb-6">
                    <?php _e('It looks like nothing was found at this location.', 'youtuneai'); ?>
                </p>
                <a href="<?php echo home_url(); ?>" class="btn-primary">
                    <?php _e('Go Home', 'youtuneai'); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>