<?php

/**
 * Main template file for YouTuneAI Pro Theme
 * Revolutionary AI-powered WordPress theme with voice control
 */
get_header(); ?>

<!-- Loading Screen -->
<div class="loading-screen" id="loadingScreen">
    <div class="ai-bot">
        <div class="head">
            <div class="face">
                <div class="eyes"></div>
                <div class="mouth"></div>
            </div>
        </div>
    </div>
</div>

<!-- Background Video System -->
<div class="background-video-container">
    <video class="background-video" autoplay muted loop>
        <source src="http://youtuneai.com/wp-content/uploads/2025/08/20250401_0314_Welcome-to-YouTuneAi_simple_compose_01jqr375jxeghs0d29k2azxf4r.mp4" type="video/mp4">
    </video>
    <div class="background-overlay"></div>
</div>

<!-- Animated Background for Avatar Page -->
<canvas id="cyberBackground" class="cyber-background"></canvas>

<div class="content-wrapper">

    <!-- Hero Section -->
    <section class="hero-section fade-in">
        <div class="hero-content">
            <h1 class="hero-title"><?php echo get_theme_mod('homepage_title', 'YouTuneAI Pro'); ?></h1>
            <p class="hero-subtitle"><?php echo get_theme_mod('homepage_subtitle', 'Revolutionary AI-Powered Voice Control Website'); ?></p>

            <div class="cta-buttons">
                <a href="/streaming/" class="btn btn-primary">
                    <i class='bx bx-play-circle'></i>
                    Start Streaming
                </a>
                <a href="/gaming/" class="btn btn-secondary">
                    <i class='bx bx-game'></i>
                    Gaming Hub
                </a>
                <a href="/shop/" class="btn btn-secondary">
                    <i class='bx bx-shopping-bag'></i>
                    Shop Now
                </a>
            </div>
        </div>
    </section>

    <!-- Feature Gallery with Hover Effects -->
    <section class="gallery-section fade-in">
        <div class="gallery-grid">

            <!-- Streaming Card -->
            <div class="gallery-item" onclick="location.href='/streaming/'">
                <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?w=500" alt="Live Streaming" class="gallery-image">
                <div class="gallery-overlay">
                    <h3 class="gallery-title">Live Streaming</h3>
                    <p class="gallery-description">Professional streaming setup with AI-powered scene management</p>
                </div>
            </div>

            <!-- Gaming Card -->
            <div class="gallery-item" onclick="location.href='/gaming/'">
                <img src="https://images.unsplash.com/photo-1511512578047-dfb367046420?w=500" alt="Gaming Hub" class="gallery-image">
                <div class="gallery-overlay">
                    <h3 class="gallery-title">Gaming Hub</h3>
                    <p class="gallery-description">Latest games, reviews, and gaming community</p>
                </div>
            </div>

            <!-- AI Avatar Creator -->
            <div class="gallery-item" onclick="location.href='/avatar-creator/'">
                <img src="https://images.unsplash.com/photo-1531482615713-2afd69097998?w=500" alt="Avatar Creator" class="gallery-image">
                <div class="gallery-overlay">
                    <h3 class="gallery-title">Avatar Creator</h3>
                    <p class="gallery-description">Create your custom AI avatar with voice synthesis</p>
                </div>
            </div>

            <!-- Shop -->
            <div class="gallery-item" onclick="location.href='/shop/'">
                <img src="https://images.unsplash.com/photo-1472851294608-062f824d29cc?w=500" alt="Online Shop" class="gallery-image">
                <div class="gallery-overlay">
                    <h3 class="gallery-title">Premium Shop</h3>
                    <p class="gallery-description">Curated products and digital downloads</p>
                </div>
            </div>

            <!-- Music Studio -->
            <div class="gallery-item" onclick="location.href='/music/'">
                <img src="https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=500" alt="Music Studio" class="gallery-image">
                <div class="gallery-overlay">
                    <h3 class="gallery-title">Music Studio</h3>
                    <p class="gallery-description">AI-generated music and audio experiences</p>
                </div>
            </div>

            <!-- Voice Control -->
            <div class="gallery-item" onclick="toggleVoicePanel()">
                <img src="https://images.unsplash.com/photo-1589254065878-42c9da997008?w=500" alt="Voice Control" class="gallery-image">
                <div class="gallery-overlay">
                    <h3 class="gallery-title">Voice Control</h3>
                    <p class="gallery-description">Control your entire website with voice commands</p>
                </div>
            </div>

        </div>
    </section>

    <!-- WordPress Posts/Content -->
    <?php if (have_posts()) : ?>
        <section class="content-section fade-in">
            <div class="container">
                <?php while (have_posts()) : the_post(); ?>
                    <article class="post-card">
                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <div class="post-meta">
                            <span class="post-date"><?php the_date(); ?></span>
                            <span class="post-author">by <?php the_author(); ?></span>
                        </div>
                        <div class="post-excerpt">
                            <?php the_excerpt(); ?>
                        </div>
                        <a href="<?php the_permalink(); ?>" class="btn btn-primary">Read More</a>
                    </article>
                <?php endwhile; ?>
            </div>
        </section>
    <?php endif; ?>

</div>

<!-- Admin Hub Trigger -->
<div class="admin-hub">
    <button class="admin-trigger" onclick="location.href='/admin-dashboard/'">
        A
    </button>
</div>

<?php get_footer(); ?>