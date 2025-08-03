<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>

    <!-- PATENT PENDING NOTICE - LEGAL PROTECTION -->
    <meta name="patent-notice" content="Patent Pending Technology - Voice-Controlled Website Modification System invented by Mr. Swain (3000Studios) - Filed August 1, 2025">
    <meta name="inventor" content="Mr. Swain, 3000Studios">
    <meta name="patent-status" content="Patent Pending - All Rights Reserved">
    <meta name="licensing-contact" content="mr.jwswain@gmail.com">
    <meta name="patent-filing-date" content="2025-08-01">
    <meta name="technology-description" content="First-ever voice-controlled real-time website modification system">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet">

    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

    <!-- PATENT PENDING NOTICE BANNER -->
    <div id="patent-notice-banner" style="background: linear-gradient(90deg, #ff0000, #ff4444); color: white; padding: 8px; text-align: center; font-weight: bold; position: fixed; top: 0; width: 100%; z-index: 10000; box-shadow: 0 2px 10px rgba(0,0,0,0.3); font-size: 14px;">
        🚨 PATENT PENDING TECHNOLOGY 🚨 Voice-Controlled Website System - FIRST-EVER INVENTION by Mr. Swain (3000Studios) - Filed Aug 1, 2025 -
        <a href="mailto:mr.jwswain@gmail.com" style="color: yellow; text-decoration: underline;">Contact for Licensing</a> - ALL RIGHTS RESERVED
        <button onclick="this.parentElement.style.display='none'" style="float: right; background: none; border: none; color: white; font-size: 16px; cursor: pointer; padding: 0 5px;">×</button>
    </div>
        <div style="height: 50px;"></div> <!-- Spacer for fixed patent banner -->

    <!-- Navigation -->
    <nav class="nav-container">
        <div class="nav-menu">
            <div class="logo">
                <a href="<?php echo home_url(); ?>">
                    <span class="logo-text">YouTuneAI</span>
                    <div class="logo-glow"></div>
                </a>
            </div>

            <!-- Mobile Menu Toggle -->
            <div class="mobile-menu-toggle" onclick="toggleMobileMenu()">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <!-- Desktop Navigation -->
            <ul class="nav-links desktop-nav">
                <li><a href="<?php echo home_url(); ?>">🏠 Home</a></li>
                <li><a href="<?php echo home_url('/shop'); ?>">🛒 Shop</a></li>
                <li><a href="<?php echo home_url('/streaming'); ?>">📺 Streaming</a></li>
                <li><a href="<?php echo home_url('/music'); ?>">🎵 Music</a></li>
                <li><a href="<?php echo home_url('/ai-tools'); ?>">🧠 AI Tools</a></li>
                <li><a href="<?php echo home_url('/create-avatar'); ?>">👤 Avatar</a></li>
                <li><a href="<?php echo home_url('/revenue'); ?>">💰 Revenue</a></li>
                <li><a href="<?php echo home_url('/about'); ?>">ℹ️ About</a></li>
                <li><a href="<?php echo home_url('/contact'); ?>">📞 Contact</a></li>
                <li class="admin-link"><a href="<?php echo home_url('/admin-dashboard'); ?>">⚡ Admin</a></li>
            </ul>

            <!-- Mobile Dropdown Navigation -->
            <div class="mobile-nav-dropdown" id="mobileNavDropdown">
                <div class="mobile-nav-header">
                    <div class="mobile-logo">YouTuneAI</div>
                    <button class="mobile-close" onclick="toggleMobileMenu()">×</button>
                </div>
                <ul class="mobile-nav-links">
                    <li><a href="<?php echo home_url(); ?>" onclick="toggleMobileMenu()">🏠 Home</a></li>
                    <li><a href="<?php echo home_url('/shop'); ?>" onclick="toggleMobileMenu()">🛒 Shop</a></li>
                    <li><a href="<?php echo home_url('/streaming'); ?>" onclick="toggleMobileMenu()">📺 Streaming</a></li>
                    <li><a href="<?php echo home_url('/music'); ?>" onclick="toggleMobileMenu()">🎵 Music</a></li>
                    <li><a href="<?php echo home_url('/ai-tools'); ?>" onclick="toggleMobileMenu()">🧠 AI Tools</a></li>
                    <li><a href="<?php echo home_url('/create-avatar'); ?>" onclick="toggleMobileMenu()">👤 Avatar</a></li>
                    <li><a href="<?php echo home_url('/revenue'); ?>" onclick="toggleMobileMenu()">💰 Revenue</a></li>
                    <li><a href="<?php echo home_url('/about'); ?>" onclick="toggleMobileMenu()">ℹ️ About</a></li>
                    <li><a href="<?php echo home_url('/contact'); ?>" onclick="toggleMobileMenu()">📞 Contact</a></li>
                    <li><a href="<?php echo home_url('/admin-dashboard'); ?>" onclick="toggleMobileMenu()">⚡ Admin</a></li>
                </ul>
            </div>

            <div class="nav-actions">
                <button class="cart-btn" onclick="openCart()">
                    🛒 <span id="cartCount">0</span>
                </button>
                <button class="voice-trigger-nav" onclick="toggleVoiceCommand()">🎤</button>
            </div>
        </div>
    </nav>

    <div id="main-content">
        <!-- Main content starts here -->

    <!-- Auto-playing Background Music -->
    <audio id="backgroundMusic" loop preload="auto" style="display: none;">
        <source src="https://cdn.pixabay.com/audio/2022/05/27/audio_1808fbf07a.mp3" type="audio/mpeg">
        <source src="https://cdn.pixabay.com/audio/2022/03/10/audio_4621d6a1f8.mp3" type="audio/mpeg">
    </audio>

    <style>
        /* New Luxury Navigation Styles */
        .nav-container {
            position: fixed;
            top: 50px;
            left: 0;
            right: 0;
            z-index: var(--z-nav);
            background: linear-gradient(135deg, rgba(26, 26, 26, 0.95) 0%, rgba(0, 0, 0, 0.9) 100%);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 215, 0, 0.3);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.5);
        }

        .nav-video-underlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.15;
            z-index: -1;
        }

        .nav-menu {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .logo {
            position: relative;
        }

        .logo-text {
            font-family: 'Orbitron', monospace;
            font-size: 28px;
            font-weight: 900;
            background: linear-gradient(45deg, var(--gold-color), var(--platinum-color), var(--diamond-color));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: var(--gold-glow);
            position: relative;
            z-index: 2;
        }

        .logo-glow {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle, rgba(255, 215, 0, 0.3) 0%, transparent 70%);
            border-radius: 50%;
            z-index: 1;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 25px;
            margin: 0;
            padding: 0;
        }

        .nav-links a {
            color: var(--text-primary);
            text-decoration: none;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 25px;
            background: linear-gradient(135deg, rgba(26, 26, 26, 0.7) 0%, rgba(30, 58, 138, 0.3) 100%);
            border: 1px solid rgba(229, 228, 226, 0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .nav-links a::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 255, 65, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .nav-links a:hover::before {
            left: 100%;
        }

        .nav-links a:hover {
            transform: translateY(-2px);
            box-shadow: var(--neon-glow);
            border-color: var(--accent-color);
        }

        .admin-link a {
            background: linear-gradient(45deg, var(--gold-color), var(--platinum-color)) !important;
            color: var(--background-dark) !important;
            box-shadow: var(--gold-glow) !important;
            font-weight: 900;
        }

        /* Mobile Navigation */
        .mobile-menu-toggle {
            display: none;
            flex-direction: column;
            cursor: pointer;
            gap: 4px;
        }

        .mobile-menu-toggle span {
            width: 25px;
            height: 3px;
            background: linear-gradient(45deg, var(--gold-color), var(--accent-color));
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .mobile-nav-dropdown {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.98) 0%, rgba(26, 26, 26, 0.95) 100%);
            z-index: 10000;
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
        }

        .mobile-nav-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 30px;
            border-bottom: 1px solid rgba(255, 215, 0, 0.3);
        }

        .mobile-logo {
            font-family: 'Orbitron', monospace;
            font-size: 24px;
            font-weight: 900;
            background: linear-gradient(45deg, var(--gold-color), var(--platinum-color));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .mobile-close {
            background: none;
            border: none;
            color: var(--accent-color);
            font-size: 30px;
            cursor: pointer;
            padding: 0;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .mobile-nav-links {
            list-style: none;
            padding: 30px;
            margin: 0;
        }

        .mobile-nav-links li {
            margin-bottom: 20px;
        }

        .mobile-nav-links a {
            display: block;
            color: var(--text-primary);
            text-decoration: none;
            font-size: 18px;
            font-weight: 600;
            padding: 15px 25px;
            border-radius: 15px;
            background: linear-gradient(135deg, rgba(26, 26, 26, 0.8) 0%, rgba(30, 58, 138, 0.4) 100%);
            border: 1px solid rgba(229, 228, 226, 0.2);
            transition: all 0.3s ease;
        }

        .mobile-nav-links a:hover {
            transform: translateX(10px);
            box-shadow: var(--neon-glow);
            border-color: var(--accent-color);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .desktop-nav {
                display: none;
            }

            .mobile-menu-toggle {
                display: flex;
            }

            .nav-menu {
                padding: 15px 20px;
            }

            .logo-text {
                font-size: 22px;
            }
        }

        /* Additional Navigation Styles */
        .nav-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .cart-btn,
        .voice-trigger-nav {
            background: linear-gradient(135deg, rgba(26, 26, 26, 0.9) 0%, rgba(30, 58, 138, 0.6) 100%);
            border: 1px solid rgba(255, 215, 0, 0.3);
            color: white;
            padding: 12px 18px;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .cart-btn:hover,
        .voice-trigger-nav:hover {
            background: linear-gradient(135deg, var(--accent-color) 0%, var(--metal-blue) 100%);
            box-shadow: var(--neon-glow);
            transform: translateY(-2px);
        }

        @media (max-width: 480px) {
            .nav-actions {
                gap: 8px;
            }

            .cart-btn,
            .voice-trigger-nav {
                padding: 8px 12px;
                font-size: 14px;
            }
        }

        /* Product Modal Styles */
        .product-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 2001;
            backdrop-filter: blur(10px);
        }

        .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: var(--glass-bg);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 30px;
            max-width: 500px;
            width: 90%;
            backdrop-filter: blur(20px);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding-bottom: 15px;
        }

        .close-modal {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .close-modal:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .modal-body ul {
            list-style: none;
            padding: 0;
        }

        .modal-body li {
            padding: 8px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .modal-body li:before {
            content: "✓ ";
            color: var(--accent-color);
            font-weight: bold;
        }

        .price-display {
            font-size: 2rem;
            color: var(--accent-color);
            text-align: center;
            margin: 20px 0;
            font-weight: bold;
        }

        .buy-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(45deg, #00d4ff, #ff00ff);
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .buy-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 212, 255, 0.4);
        }

        /* Admin Hub Additional Styles */
        .close-btn {
            position: absolute;
            top: 15px;
            right: 20px;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .close-btn:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .command-buttons {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        .execute-btn.secondary {
            background: linear-gradient(45deg, #8000ff, #ff0080);
        }

        .command-status {
            min-height: 40px;
            padding: 10px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Gallery additional styles */
        .price {
            color: var(--accent-color);
            font-weight: bold;
            font-size: 1.2rem;
            float: right;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            color: var(--accent-color);
            margin-bottom: 20px;
        }

        /* Stream card styles */
        .stream-icon {
            font-size: 3rem;
            margin-bottom: 15px;
        }

        .stream-btn {
            background: var(--accent-color);
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 15px;
        }

        .stream-btn:hover {
            background: #ff00ff;
            transform: translateY(-2px);
        }

        /* Hero buttons */
        .hero-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .cta-btn {
            padding: 15px 30px;
            background: linear-gradient(45deg, var(--accent-color), #ff00ff);
            border: none;
            border-radius: 25px;
            color: white;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .cta-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(0, 212, 255, 0.4);
        }

        .cta-btn.secondary {
            background: linear-gradient(45deg, #8000ff, #ff0080);
        }

        /* Avatar customization button */
        .customize-btn {
            margin-top: 30px;
            padding: 15px 40px;
            background: linear-gradient(45deg, var(--accent-color), #ff00ff);
            border: none;
            border-radius: 25px;
            color: white;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .customize-btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--neon-glow);
        }

        /* Avatar carousel positioning */
        .avatar-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }
    </style>

    <script>
        // Cart functionality
        let cart = [];

        function openCart() {
            // Cart modal implementation
            alert('Cart functionality coming soon!');
        }

        function addToCart(productId, product) {
            cart.push({
                id: productId,
                ...product
            });
            updateCartCount();

            // Show success message
            showNotification(`${product.title} added to cart!`, 'success');
            closeProductModal();
        }

        function updateCartCount() {
            document.getElementById('cartCount').textContent = cart.length;
        }

        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.textContent = message;
            notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#00ff00' : '#ff4444'};
        color: white;
        padding: 15px 25px;
        border-radius: 10px;
        z-index: 3000;
        opacity: 0;
        transition: opacity 0.3s ease;
    `;

            document.body.appendChild(notification);

            setTimeout(() => notification.style.opacity = '1', 100);
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => document.body.removeChild(notification), 300);
            }, 3000);
        }

        // Mobile Navigation Functions
        function toggleMobileMenu() {
            const dropdown = document.getElementById('mobileNavDropdown');
            const toggle = document.querySelector('.mobile-menu-toggle');

            if (dropdown.style.display === 'block') {
                dropdown.style.display = 'none';
                toggle.classList.remove('active');
            } else {
                dropdown.style.display = 'block';
                toggle.classList.add('active');
            }
        }

        // Auto-play Background Music
        let musicEnabled = localStorage.getItem('backgroundMusic') !== 'false';
        let currentMusic = null;

        function initializeMusic() {
            const music = document.getElementById('backgroundMusic');
            if (music && musicEnabled) {
                music.volume = 0.3; // Set to 30% volume

                // Try to play music
                const playPromise = music.play();
                if (playPromise !== undefined) {
                    playPromise.then(() => {
                        currentMusic = music;
                        console.log('Background music started');
                    }).catch(error => {
                        console.log('Music autoplay prevented by browser:', error);
                        // Add user interaction to enable music
                        document.addEventListener('click', enableMusicOnFirstClick, {
                            once: true
                        });
                    });
                }
            }
        }

        function enableMusicOnFirstClick() {
            const music = document.getElementById('backgroundMusic');
            if (music && musicEnabled) {
                music.play().then(() => {
                    currentMusic = music;
                    console.log('Background music enabled after user interaction');
                });
            }
        }

        function toggleBackgroundMusic() {
            const music = document.getElementById('backgroundMusic');
            if (!music) return;

            if (music.paused) {
                music.play();
                musicEnabled = true;
                localStorage.setItem('backgroundMusic', 'true');
                showNotification('🎵 Background music enabled', 'success');
            } else {
                music.pause();
                musicEnabled = false;
                localStorage.setItem('backgroundMusic', 'false');
                showNotification('🔇 Background music disabled', 'info');
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            initializeMusic();

            // Add music control button to nav actions
            const navActions = document.querySelector('.nav-actions');
            if (navActions) {
                const musicBtn = document.createElement('button');
                musicBtn.className = 'music-toggle-btn';
                musicBtn.innerHTML = musicEnabled ? '🎵' : '🔇';
                musicBtn.onclick = toggleBackgroundMusic;
                musicBtn.style.cssText = `
                    background: linear-gradient(135deg, rgba(26, 26, 26, 0.9) 0%, rgba(30, 58, 138, 0.6) 100%);
                    border: 1px solid rgba(255, 215, 0, 0.3);
                    color: white;
                    padding: 12px 18px;
                    border-radius: 25px;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    font-weight: 600;
                `;
                navActions.appendChild(musicBtn);
            }
        });

        // Mobile Menu Toggle Animation
        document.addEventListener('DOMContentLoaded', function() {
            const style = document.createElement('style');
            style.textContent = `
                .mobile-menu-toggle.active span:nth-child(1) {
                    transform: rotate(45deg) translate(5px, 5px);
                }
                .mobile-menu-toggle.active span:nth-child(2) {
                    opacity: 0;
                }
                .mobile-menu-toggle.active span:nth-child(3) {
                    transform: rotate(-45deg) translate(7px, -6px);
                }
            `;
            document.head.appendChild(style);
        });
    </script>