<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package YouTuneAI_Theme
 */

?>

<footer id="colophon" class="site-footer">
    <div class="site-info">
        <a href="<?php echo esc_url(__('https://wordpress.org/', 'youtuneai-theme')); ?>">
            <?php
            /* translators: %s: CMS name, i.e. WordPress. */
            printf(esc_html__('Proudly powered by %s', 'youtuneai-theme'), 'WordPress');
            ?>
        </a>
        <span class="sep"> | </span>
        <?php
        /* translators: 1: Theme name, 2: Theme author. */
        printf(esc_html__('Theme: %1$s by %2$s.', 'youtuneai-theme'), 'youtuneai-theme', '<a href="https://3000studios.com/">3000Studios</a>');
        ?>
    </div><!-- .site-info -->
</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>

</html>