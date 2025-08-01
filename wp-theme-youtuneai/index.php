<?php
/**
 * Main template file
 * This is the most generic template file in a WordPress theme
 */

get_header(); ?>

<main id="main" class="site-main">
    <?php if (is_home() || is_front_page()) : ?>
        <!-- Include the home page template -->
        <?php get_template_part('page', 'home'); ?>
    <?php else : ?>
        <!-- Default content for other pages -->
        <div class="content-area">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <header class="entry-header">
                            <h1 class="entry-title"><?php the_title(); ?></h1>
                        </header>
                        
                        <div class="entry-content">
                            <?php the_content(); ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            <?php else : ?>
                <p>No content found.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
