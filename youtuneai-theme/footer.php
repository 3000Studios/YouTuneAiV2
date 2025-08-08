<footer class="bg-gunmetal/90 backdrop-blur-lg border-t border-platinum/20 mt-16">
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Brand Column -->
            <div class="space-y-4">
                <div class="flex items-center space-x-3">
                    <?php if (has_custom_logo()) : ?>
                        <?php the_custom_logo(); ?>
                    <?php else : ?>
                        <h3 class="text-xl font-orbitron font-bold text-primary glow-text">
                            <?php bloginfo('name'); ?>
                        </h3>
                    <?php endif; ?>
                </div>
                
                <p class="text-platinum/80 text-sm">
                    <?php echo get_bloginfo('description') ?: __('Revolutionary AI-powered WordPress theme with 3D/VR capabilities, streaming, gaming, and comprehensive monetization features.', 'youtuneai'); ?>
                </p>
                
                <!-- Social Links -->
                <div class="flex space-x-4">
                    <?php
                    $social_links = array(
                        'youtube' => youtuneai_get_option('youtube_url'),
                        'twitch' => youtuneai_get_option('twitch_url'),
                        'twitter' => youtuneai_get_option('twitter_url'),
                        'discord' => youtuneai_get_option('discord_url'),
                        'github' => youtuneai_get_option('github_url'),
                    );
                    
                    foreach ($social_links as $platform => $url) :
                        if ($url) :
                    ?>
                        <a href="<?php echo esc_url($url); ?>" 
                           class="text-platinum hover:text-primary transition-colors p-2 rounded-lg hover:bg-primary/10"
                           target="_blank"
                           rel="noopener noreferrer"
                           aria-label="<?php printf(__('Follow us on %s', 'youtuneai'), ucfirst($platform)); ?>">
                            <i class="bx bxl-<?php echo $platform; ?> text-xl"></i>
                        </a>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="space-y-4">
                <h4 class="text-lg font-orbitron font-semibold text-primary">
                    <?php _e('Explore', 'youtuneai'); ?>
                </h4>
                <nav class="space-y-2">
                    <a href="<?php echo home_url('/games'); ?>" class="block text-platinum/80 hover:text-primary transition-colors">
                        <?php _e('Games', 'youtuneai'); ?>
                    </a>
                    <a href="<?php echo home_url('/streams'); ?>" class="block text-platinum/80 hover:text-primary transition-colors">
                        <?php _e('Live Streams', 'youtuneai'); ?>
                    </a>
                    <a href="<?php echo home_url('/garage'); ?>" class="block text-platinum/80 hover:text-primary transition-colors">
                        <?php _e('YouTune Garage', 'youtuneai'); ?>
                    </a>
                    <a href="<?php echo home_url('/vr-room'); ?>" class="block text-platinum/80 hover:text-primary transition-colors">
                        <?php _e('VR Room', 'youtuneai'); ?>
                    </a>
                    <a href="<?php echo home_url('/shop'); ?>" class="block text-platinum/80 hover:text-primary transition-colors">
                        <?php _e('Shop', 'youtuneai'); ?>
                    </a>
                </nav>
            </div>

            <!-- Support -->
            <div class="space-y-4">
                <h4 class="text-lg font-orbitron font-semibold text-primary">
                    <?php _e('Support', 'youtuneai'); ?>
                </h4>
                <nav class="space-y-2">
                    <a href="<?php echo home_url('/about'); ?>" class="block text-platinum/80 hover:text-primary transition-colors">
                        <?php _e('About Us', 'youtuneai'); ?>
                    </a>
                    <a href="<?php echo home_url('/contact'); ?>" class="block text-platinum/80 hover:text-primary transition-colors">
                        <?php _e('Contact', 'youtuneai'); ?>
                    </a>
                    <a href="<?php echo home_url('/privacy-policy'); ?>" class="block text-platinum/80 hover:text-primary transition-colors">
                        <?php _e('Privacy Policy', 'youtuneai'); ?>
                    </a>
                    <a href="<?php echo home_url('/terms-of-service'); ?>" class="block text-platinum/80 hover:text-primary transition-colors">
                        <?php _e('Terms of Service', 'youtuneai'); ?>
                    </a>
                    <a href="<?php echo home_url('/help'); ?>" class="block text-platinum/80 hover:text-primary transition-colors">
                        <?php _e('Help Center', 'youtuneai'); ?>
                    </a>
                </nav>
            </div>

            <!-- Newsletter & Performance -->
            <div class="space-y-4">
                <h4 class="text-lg font-orbitron font-semibold text-primary">
                    <?php _e('Stay Connected', 'youtuneai'); ?>
                </h4>
                
                <!-- Newsletter Signup -->
                <form class="space-y-3" id="newsletter-form">
                    <input type="email" 
                           placeholder="<?php _e('Your email address', 'youtuneai'); ?>" 
                           class="w-full p-3 bg-background-dark border border-platinum/30 rounded-lg text-platinum placeholder-platinum/50 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors text-sm">
                    <button type="submit" class="btn-primary w-full text-sm">
                        <?php _e('Subscribe', 'youtuneai'); ?>
                    </button>
                </form>
                
                <!-- Performance Badge -->
                <div class="card-3d p-3">
                    <div class="flex items-center space-x-2 mb-2">
                        <i class="bx bx-tachometer text-accent"></i>
                        <span class="text-sm font-semibold text-platinum"><?php _e('Performance', 'youtuneai'); ?></span>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <?php $performance = youtuneai_get_performance_score(); ?>
                        <div class="text-center">
                            <div class="text-accent font-bold"><?php echo $performance['performance']; ?></div>
                            <div class="text-platinum/60">Performance</div>
                        </div>
                        <div class="text-center">
                            <div class="text-accent font-bold"><?php echo $performance['accessibility']; ?></div>
                            <div class="text-platinum/60">A11y</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-platinum/20 mt-8 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div class="text-platinum/60 text-sm">
                    <?php
                    printf(
                        __('© %1$s %2$s. All rights reserved. Built with %3$s', 'youtuneai'),
                        date('Y'),
                        get_bloginfo('name'),
                        '<span class="text-primary font-semibold">YouTuneAI Pro</span>'
                    );
                    ?>
                </div>
                
                <div class="flex items-center space-x-4 text-platinum/60 text-sm">
                    <!-- Theme version -->
                    <span><?php printf(__('v%s', 'youtuneai'), YOUTUNEAI_VERSION); ?></span>
                    
                    <!-- System status indicator -->
                    <div class="flex items-center space-x-1" title="<?php _e('All systems operational', 'youtuneai'); ?>">
                        <div class="w-2 h-2 bg-accent rounded-full animate-pulse"></div>
                        <span><?php _e('Online', 'youtuneai'); ?></span>
                    </div>
                    
                    <!-- Load time -->
                    <span id="load-time"></span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scroll to top button -->
    <button id="scroll-to-top" 
            class="fixed bottom-8 right-8 bg-primary hover:bg-secondary text-white p-3 rounded-full shadow-lg hover:shadow-xl transform translate-y-16 transition-all duration-300 z-40"
            aria-label="<?php _e('Scroll to top', 'youtuneai'); ?>">
        <i class="bx bx-chevron-up text-xl"></i>
    </button>
</footer>

<!-- Floating chat bubble (desktop) -->
<div class="fixed bottom-8 left-8 hidden lg:block z-40">
    <button class="bg-gradient-to-r from-primary to-secondary text-white p-4 rounded-full shadow-lg hover:shadow-xl animate-pulse-glow transition-all duration-300 transform hover:scale-110"
            data-modal-open="avatar-modal"
            aria-label="<?php _e('Open AI chat', 'youtuneai'); ?>">
        <i class="bx bx-chat text-xl"></i>
    </button>
</div>

<!-- Performance tracking -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show scroll to top button
    const scrollToTopBtn = document.getElementById('scroll-to-top');
    const showScrollTop = () => {
        if (window.pageYOffset > 300) {
            scrollToTopBtn.classList.remove('translate-y-16');
        } else {
            scrollToTopBtn.classList.add('translate-y-16');
        }
    };
    
    window.addEventListener('scroll', youtuneai_utils?.throttle(showScrollTop, 100) || showScrollTop);
    
    scrollToTopBtn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
    
    // Display load time
    window.addEventListener('load', function() {
        const loadTime = performance.timing.loadEventEnd - performance.timing.navigationStart;
        const loadTimeElement = document.getElementById('load-time');
        if (loadTimeElement) {
            loadTimeElement.textContent = `${(loadTime / 1000).toFixed(2)}s`;
        }
    });
    
    // Newsletter form
    const newsletterForm = document.getElementById('newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            // Handle newsletter signup
            const email = this.querySelector('input[type="email"]').value;
            console.log('Newsletter signup:', email);
            // TODO: Implement newsletter signup
        });
    }
});
</script>

<?php wp_footer(); ?>

</body>
</html>