<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <!-- Performance optimizations -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="//cdnjs.cloudflare.com">
    
    <!-- Security headers -->
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-Frame-Options" content="DENY">
    <meta http-equiv="X-XSS-Protection" content="1; mode=block">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a href="#main" class="skip-to-content sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 z-50 bg-primary text-white px-4 py-2 rounded">
    <?php _e('Skip to main content', 'youtuneai'); ?>
</a>

<header class="fixed top-0 left-0 right-0 z-40 glass-morphism border-b border-platinum/20 transition-all duration-300" id="main-header">
    <nav class="container mx-auto px-4 py-4" role="navigation" aria-label="<?php _e('Main navigation', 'youtuneai'); ?>">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center space-x-4">
                <?php if (has_custom_logo()) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <a href="<?php echo home_url(); ?>" class="text-2xl font-orbitron font-bold text-primary glow-text">
                        <?php bloginfo('name'); ?>
                    </a>
                <?php endif; ?>
                
                <!-- Live indicator -->
                <?php if (youtuneai_is_stream_live()) : ?>
                    <div class="flex items-center space-x-2 bg-red-600 px-3 py-1 rounded-full animate-pulse">
                        <div class="w-2 h-2 bg-white rounded-full"></div>
                        <span class="text-white text-sm font-semibold"><?php _e('LIVE', 'youtuneai'); ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden lg:flex items-center space-x-8">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_class' => 'flex items-center space-x-6',
                    'link_class' => 'text-platinum hover:text-primary transition-colors font-raleway font-medium',
                    'fallback_cb' => 'youtuneai_default_menu',
                ));
                ?>
                
                <!-- Search -->
                <button class="text-platinum hover:text-primary transition-colors p-2" 
                        data-modal-open="search-modal" 
                        aria-label="<?php _e('Open search', 'youtuneai'); ?>">
                    <i class="bx bx-search text-xl"></i>
                </button>
                
                <!-- Avatar Chat -->
                <button class="btn-primary" data-modal-open="avatar-modal">
                    <i class="bx bx-chat mr-2"></i>
                    <?php _e('Chat', 'youtuneai'); ?>
                </button>
            </div>

            <!-- Mobile Menu Toggle -->
            <button class="lg:hidden mobile-menu-toggle p-2 text-platinum hover:text-primary transition-colors" 
                    aria-label="<?php _e('Toggle mobile menu', 'youtuneai'); ?>" 
                    aria-expanded="false">
                <i class="bx bx-menu text-2xl"></i>
            </button>
        </div>

        <!-- Mobile Navigation -->
        <div class="mobile-menu lg:hidden fixed inset-0 bg-background-dark/95 backdrop-blur-lg z-50 transform -translate-x-full transition-transform duration-300">
            <div class="p-6">
                <div class="flex justify-between items-center mb-8">
                    <span class="text-2xl font-orbitron font-bold text-primary">Menu</span>
                    <button class="mobile-menu-toggle p-2 text-platinum hover:text-primary transition-colors">
                        <i class="bx bx-x text-2xl"></i>
                    </button>
                </div>
                
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_class' => 'space-y-4',
                    'link_class' => 'block text-lg text-platinum hover:text-primary transition-colors font-raleway',
                    'fallback_cb' => 'youtuneai_default_menu',
                ));
                ?>
                
                <div class="mt-8 space-y-4">
                    <button class="btn-primary w-full" data-modal-open="avatar-modal">
                        <i class="bx bx-chat mr-2"></i>
                        <?php _e('Chat with AI', 'youtuneai'); ?>
                    </button>
                    
                    <button class="btn-secondary w-full" data-modal-open="search-modal">
                        <i class="bx bx-search mr-2"></i>
                        <?php _e('Search', 'youtuneai'); ?>
                    </button>
                </div>
            </div>
        </div>
    </nav>
</header>

<!-- Search Modal -->
<div id="search-modal" class="modal fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="modal-content card-3d max-w-lg w-full p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-orbitron font-bold"><?php _e('Search', 'youtuneai'); ?></h2>
            <button data-modal-close class="text-platinum hover:text-primary transition-colors">
                <i class="bx bx-x text-2xl"></i>
            </button>
        </div>
        
        <form role="search" method="get" action="<?php echo home_url(); ?>" class="space-y-4">
            <input type="search" 
                   name="s" 
                   placeholder="<?php _e('Search...', 'youtuneai'); ?>" 
                   class="w-full p-3 bg-background-dark border border-platinum/30 rounded-lg text-platinum placeholder-platinum/50 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
            <button type="submit" class="btn-primary w-full">
                <i class="bx bx-search mr-2"></i>
                <?php _e('Search', 'youtuneai'); ?>
            </button>
        </form>
    </div>
</div>

<!-- Avatar Chat Modal -->
<div id="avatar-modal" class="modal fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="modal-content card-3d max-w-2xl w-full p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-orbitron font-bold"><?php _e('AI Assistant', 'youtuneai'); ?></h2>
            <button data-modal-close class="text-platinum hover:text-primary transition-colors">
                <i class="bx bx-x text-2xl"></i>
            </button>
        </div>
        
        <div class="flex flex-col lg:flex-row space-y-6 lg:space-y-0 lg:space-x-6">
            <!-- Avatar Display -->
            <div class="flex-shrink-0">
                <?php echo do_shortcode('[youtuneai_avatar width="250" height="300" interactive="true" voice="true"]'); ?>
            </div>
            
            <!-- Chat Interface -->
            <div class="flex-1">
                <div id="chat-history" class="h-40 overflow-y-auto bg-background-dark/50 rounded-lg p-4 mb-4 space-y-2">
                    <div class="text-platinum/60 text-sm">
                        <?php _e('Start a conversation with the AI assistant...', 'youtuneai'); ?>
                    </div>
                </div>
                
                <div class="flex space-x-2">
                    <input type="text" 
                           id="chat-input" 
                           placeholder="<?php _e('Type your message...', 'youtuneai'); ?>" 
                           class="flex-1 p-3 bg-background-dark border border-platinum/30 rounded-lg text-platinum placeholder-platinum/50 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
                    <button id="send-chat" class="btn-primary px-4">
                        <i class="bx bx-send"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Default menu fallback function
<?php if (!function_exists('youtuneai_default_menu')) : ?>
function youtuneai_default_menu() {
    echo '<div class="flex items-center space-x-6">';
    echo '<a href="' . home_url() . '" class="text-platinum hover:text-primary transition-colors">Home</a>';
    echo '<a href="' . home_url('/games') . '" class="text-platinum hover:text-primary transition-colors">Games</a>';
    echo '<a href="' . home_url('/streams') . '" class="text-platinum hover:text-primary transition-colors">Live</a>';
    echo '<a href="' . home_url('/garage') . '" class="text-platinum hover:text-primary transition-colors">Garage</a>';
    echo '<a href="' . home_url('/vr-room') . '" class="text-platinum hover:text-primary transition-colors">VR Room</a>';
    echo '<a href="' . home_url('/shop') . '" class="text-platinum hover:text-primary transition-colors">Shop</a>';
    echo '</div>';
}
<?php endif; ?>
</script>