<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet">

    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

    <!-- Navigation -->
    <nav class="nav-container">
        <!-- Navigation Video Underlay -->
        <video class="nav-video-underlay" autoplay muted loop>
            <source src="<?php echo get_template_directory_uri(); ?>/assets/video/nav-background.mp4" type="video/mp4">
        </video>

        <div class="nav-menu">
            <div class="logo">
                <a href="<?php echo home_url(); ?>">YouTuneAI</a>
            </div>

            <ul class="nav-links">
                <li><a href="<?php echo home_url(); ?>">🏠 Home</a></li>
                <li><a href="<?php echo home_url('/shop'); ?>">🛒 Shop</a></li>
                <li><a href="<?php echo home_url('/streaming'); ?>">🎮 Streaming</a></li>
                <li><a href="<?php echo home_url('/music'); ?>">🎵 Music</a></li>
                <li><a href="<?php echo home_url('/ai-tools'); ?>">🧠 AI Tools</a></li>
                <li><a href="<?php echo home_url('/create-avatar'); ?>">👤 Avatar</a></li>
                <li class="admin-link"><a href="#" onclick="openAdminHub()">⚡ Admin</a></li>
            </ul>

            <div class="nav-actions">
                <button class="cart-btn" onclick="openCart()">
                    🛒 <span id="cartCount">0</span>
                </button>
                <button class="voice-trigger-nav" onclick="toggleVoiceCommand()">🎤</button>
            </div>
        </div>
    </nav>

    <style>
        /* Additional Navigation Styles */
        .nav-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .cart-btn,
        .voice-trigger-nav {
            background: var(--glass-bg);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 10px 15px;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .cart-btn:hover,
        .voice-trigger-nav:hover {
            background: var(--accent-color);
            box-shadow: var(--neon-glow);
        }

        .admin-link a {
            background: linear-gradient(45deg, #ff0080, #8000ff) !important;
            box-shadow: 0 0 15px rgba(255, 0, 128, 0.5);
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
    </script>