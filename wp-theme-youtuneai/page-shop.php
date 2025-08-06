<?php
/*
Template Name: Shop
*/

get_header(); ?>

<div class="shop-container">
    <!-- Hero Section -->
    <div class="shop-hero">
        <div class="hero-content">
            <h1>🛒 YouTuneAI Pro Shop</h1>
            <p>AI-Powered Music Tools, Software, and Digital Products</p>
            <div class="revenue-stats">
                <div class="stat-item">
                    <span class="stat-number" id="totalProducts">500+</span>
                    <span class="stat-label">Products</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number" id="totalSales">$125K+</span>
                    <span class="stat-label">Revenue</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number" id="happyCustomers">2.5K+</span>
                    <span class="stat-label">Customers</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Ad Placement - Top Banner -->
    <div class="ad-container ad-banner-top">
        <div id="googleAds1" class="google-ads-slot">
            <!-- Google AdSense Banner Ad -->
        </div>
    </div>

    <!-- Product Categories -->
    <div class="product-categories">
        <h2>🎯 Shop by Category</h2>
        <div class="category-grid">
            <div class="category-card" data-category="ai-tools">
                <div class="category-icon">🤖</div>
                <h3>AI Music Tools</h3>
                <p>Voice generators, beat makers, AI composers</p>
                <span class="product-count">150+ products</span>
            </div>
            <div class="category-card" data-category="software">
                <div class="category-icon">💻</div>
                <h3>Professional Software</h3>
                <p>DAW plugins, mixing tools, mastering suites</p>
                <span class="product-count">200+ products</span>
            </div>
            <div class="category-card" data-category="samples">
                <div class="category-icon">🎵</div>
                <h3>Sample Packs</h3>
                <p>Beats, loops, vocals, instruments</p>
                <span class="product-count">500+ packs</span>
            </div>
            <div class="category-card" data-category="courses">
                <div class="category-icon">🎓</div>
                <h3>Music Courses</h3>
                <p>Production tutorials, mixing masterclasses</p>
                <span class="product-count">50+ courses</span>
            </div>
            <div class="category-card" data-category="hardware">
                <div class="category-icon">🎛️</div>
                <h3>Studio Hardware</h3>
                <p>Controllers, interfaces, monitors</p>
                <span class="product-count">100+ items</span>
            </div>
            <div class="category-card" data-category="services">
                <div class="category-icon">⭐</div>
                <h3>Pro Services</h3>
                <p>Mixing, mastering, ghost production</p>
                <span class="product-count">25+ services</span>
            </div>
        </div>
    </div>

    <!-- Featured Products -->
    <div class="featured-products">
        <h2>🔥 Trending Now</h2>
        <div class="products-grid" id="featuredProducts">
            <!-- Products will be populated by JavaScript -->
        </div>
    </div>

    <!-- Ad Placement - Middle Rectangle -->
    <div class="ad-container ad-rectangle">
        <div id="googleAds2" class="google-ads-slot">
            <!-- Google AdSense Rectangle Ad -->
        </div>
    </div>

    <!-- All Products Section -->
    <div class="all-products">
        <div class="products-header">
            <h2>🛍️ All Products</h2>
            <div class="product-filters">
                <select id="sortFilter">
                    <option value="newest">Newest First</option>
                    <option value="popular">Most Popular</option>
                    <option value="price-low">Price: Low to High</option>
                    <option value="price-high">Price: High to Low</option>
                    <option value="rating">Highest Rated</option>
                </select>
                <select id="categoryFilter">
                    <option value="all">All Categories</option>
                    <option value="ai-tools">AI Tools</option>
                    <option value="software">Software</option>
                    <option value="samples">Sample Packs</option>
                    <option value="courses">Courses</option>
                    <option value="hardware">Hardware</option>
                    <option value="services">Services</option>
                </select>
                <input type="text" id="searchProducts" placeholder="🔍 Search products...">
            </div>
        </div>
        <div class="products-grid" id="allProducts">
            <!-- Products will be populated by JavaScript -->
        </div>
        <div class="load-more-container">
            <button id="loadMoreProducts" class="load-more-btn">Load More Products</button>
        </div>
    </div>

    <!-- Revenue Streams Section -->
    <div class="revenue-streams">
        <h2>💰 Multiple Revenue Streams</h2>
        <div class="revenue-grid">
            <div class="revenue-card">
                <div class="revenue-icon">🛒</div>
                <h3>Product Sales</h3>
                <p>Direct sales of digital products, software, and hardware</p>
                <div class="revenue-amount">$50K+/month</div>
            </div>
            <div class="revenue-card">
                <div class="revenue-icon">📢</div>
                <h3>Ad Revenue</h3>
                <p>Google AdSense, affiliate marketing, sponsored content</p>
                <div class="revenue-amount">$15K+/month</div>
            </div>
            <div class="revenue-card">
                <div class="revenue-icon">🎯</div>
                <h3>Affiliate Commissions</h3>
                <p>Amazon Associates, plugin affiliates, hardware commissions</p>
                <div class="revenue-amount">$25K+/month</div>
            </div>
            <div class="revenue-card">
                <div class="revenue-icon">🎓</div>
                <h3>Course Sales</h3>
                <p>Online music production and AI tool courses</p>
                <div class="revenue-amount">$20K+/month</div>
            </div>
        </div>
    </div>

    <!-- Affiliate Program -->
    <div class="affiliate-program">
        <h2>🤝 Join Our Affiliate Program</h2>
        <p>Earn 30% commission on every sale you refer!</p>
        <button class="affiliate-btn" onclick="openAffiliateSignup()">Become an Affiliate</button>
    </div>

    <!-- Ad Placement - Bottom Banner -->
    <div class="ad-container ad-banner-bottom">
        <div id="googleAds3" class="google-ads-slot">
            <!-- Google AdSense Banner Ad -->
        </div>
    </div>
</div>

<!-- Product Modal -->
<div id="productModal" class="product-modal">
    <div class="modal-content">
        <span class="close-modal" onclick="closeProductModal()">&times;</span>
        <div id="modalProductContent">
            <!-- Product details will be loaded here -->
        </div>
    </div>
</div>

<!-- Shopping Cart Sidebar -->
<div id="cartSidebar" class="cart-sidebar">
    <div class="cart-header">
        <h3>🛒 Shopping Cart</h3>
        <span class="close-cart" onclick="toggleCart()">&times;</span>
    </div>
    <div class="cart-items" id="cartItems">
        <!-- Cart items will be populated here -->
    </div>
    <div class="cart-footer">
        <div class="cart-total">
            <strong>Total: $<span id="cartTotal">0.00</span></strong>
        </div>
        <button class="checkout-btn" onclick="proceedToCheckout()">Proceed to Checkout</button>
    </div>
</div>

<!-- Fixed Cart Icon -->
<div class="cart-icon-fixed" onclick="toggleCart()">
    🛒 <span id="cartCount">0</span>
</div>

<style>
    .shop-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        font-family: 'Arial', sans-serif;
    }

    /* Hero Section */
    .shop-hero {
        background: linear-gradient(135deg, var(--luxury-black) 0%, var(--deep-metal-blue) 50%, var(--luxury-black) 100%);
        padding: 60px 40px;
        border-radius: 20px;
        text-align: center;
        margin-bottom: 40px;
        position: relative;
        overflow: hidden;
    }

    .shop-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="marble" patternUnits="userSpaceOnUse" width="100" height="100"><circle cx="50" cy="50" r="30" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23marble)"/></svg>');
        opacity: 0.3;
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .shop-hero h1 {
        font-size: 3em;
        color: var(--white-marble);
        margin-bottom: 15px;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }

    .shop-hero p {
        font-size: 1.2em;
        color: var(--white-marble);
        margin-bottom: 30px;
        opacity: 0.9;
    }

    .revenue-stats {
        display: flex;
        justify-content: center;
        gap: 40px;
        flex-wrap: wrap;
    }

    .stat-item {
        text-align: center;
    }

    .stat-number {
        display: block;
        font-size: 2.5em;
        font-weight: bold;
        color: var(--shining-gold);
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }

    .stat-label {
        font-size: 1em;
        color: var(--white-marble);
        opacity: 0.8;
    }

    /* Ad Containers */
    .ad-container {
        margin: 30px 0;
        text-align: center;
        background: linear-gradient(135deg, var(--platinum) 0%, var(--silver) 100%);
        padding: 20px;
        border-radius: 10px;
        border: 2px solid var(--shining-gold);
    }

    .google-ads-slot {
        min-height: 120px;
        background: rgba(255, 255, 255, 0.1);
        border: 2px dashed var(--neon-green);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--luxury-black);
        font-weight: bold;
    }

    .ad-banner-top .google-ads-slot,
    .ad-banner-bottom .google-ads-slot {
        min-height: 100px;
    }

    .ad-rectangle .google-ads-slot {
        min-height: 300px;
        max-width: 336px;
        margin: 0 auto;
    }

    /* Product Categories */
    .product-categories {
        margin: 60px 0;
    }

    .product-categories h2 {
        text-align: center;
        font-size: 2.5em;
        color: var(--luxury-black);
        margin-bottom: 40px;
    }

    .category-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
    }

    .category-card {
        background: linear-gradient(135deg, var(--white-marble) 0%, var(--platinum) 100%);
        padding: 30px;
        border-radius: 15px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 3px solid transparent;
        position: relative;
        overflow: hidden;
    }

    .category-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, var(--shining-gold), var(--diamond-blue), var(--neon-green), var(--shining-gold));
        background-size: 400% 400%;
        z-index: -1;
        border-radius: 15px;
        animation: gradientShift 3s ease infinite;
    }

    .category-card:hover {
        transform: translateY(-10px) scale(1.02);
        border-color: var(--neon-green);
        box-shadow: 0 20px 40px rgba(0, 255, 65, 0.3);
    }

    .category-icon {
        font-size: 3em;
        margin-bottom: 15px;
    }

    .category-card h3 {
        color: var(--luxury-black);
        font-size: 1.5em;
        margin-bottom: 10px;
    }

    .category-card p {
        color: var(--deep-metal-blue);
        margin-bottom: 15px;
        font-size: 1em;
    }

    .product-count {
        background: var(--neon-green);
        color: var(--luxury-black);
        padding: 5px 12px;
        border-radius: 20px;
        font-weight: bold;
        font-size: 0.9em;
    }

    /* Products Grid */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px;
        margin: 30px 0;
    }

    .product-card {
        background: var(--white-marble);
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border: 2px solid transparent;
        position: relative;
    }

    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        border-color: var(--shining-gold);
    }

    .product-image {
        height: 200px;
        background: linear-gradient(135deg, var(--deep-metal-blue), var(--luxury-black));
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3em;
        color: var(--white-marble);
        position: relative;
        overflow: hidden;
    }

    .product-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: var(--neon-green);
        color: var(--luxury-black);
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.8em;
        font-weight: bold;
    }

    .product-content {
        padding: 20px;
    }

    .product-title {
        font-size: 1.3em;
        font-weight: bold;
        color: var(--luxury-black);
        margin-bottom: 8px;
    }

    .product-description {
        color: var(--deep-metal-blue);
        font-size: 0.9em;
        margin-bottom: 15px;
        line-height: 1.4;
    }

    .product-price {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .price-current {
        font-size: 1.5em;
        font-weight: bold;
        color: var(--neon-green);
    }

    .price-original {
        text-decoration: line-through;
        color: #999;
        font-size: 1em;
    }

    .product-rating {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-bottom: 15px;
    }

    .stars {
        color: var(--shining-gold);
    }

    .rating-count {
        color: #666;
        font-size: 0.9em;
    }

    .product-actions {
        display: flex;
        gap: 10px;
    }

    .add-to-cart-btn,
    .view-details-btn {
        flex: 1;
        padding: 12px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .add-to-cart-btn {
        background: var(--neon-green);
        color: var(--luxury-black);
    }

    .add-to-cart-btn:hover {
        background: var(--shining-gold);
        transform: scale(1.05);
    }

    .view-details-btn {
        background: var(--deep-metal-blue);
        color: var(--white-marble);
    }

    .view-details-btn:hover {
        background: var(--luxury-black);
    }

    /* Filters */
    .products-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 40px 0 20px 0;
        flex-wrap: wrap;
        gap: 20px;
    }

    .product-filters {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .product-filters select,
    .product-filters input {
        padding: 10px 15px;
        border: 2px solid var(--platinum);
        border-radius: 8px;
        background: var(--white-marble);
        color: var(--luxury-black);
        font-size: 1em;
    }

    .product-filters select:focus,
    .product-filters input:focus {
        outline: none;
        border-color: var(--neon-green);
    }

    /* Revenue Streams */
    .revenue-streams {
        background: linear-gradient(135deg, var(--luxury-black) 0%, var(--deep-metal-blue) 100%);
        padding: 60px 40px;
        border-radius: 20px;
        margin: 60px 0;
        text-align: center;
    }

    .revenue-streams h2 {
        color: var(--white-marble);
        font-size: 2.5em;
        margin-bottom: 40px;
    }

    .revenue-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
    }

    .revenue-card {
        background: rgba(255, 255, 255, 0.1);
        padding: 30px;
        border-radius: 15px;
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 215, 0, 0.3);
        transition: all 0.3s ease;
    }

    .revenue-card:hover {
        transform: translateY(-5px);
        border-color: var(--neon-green);
        box-shadow: 0 20px 40px rgba(0, 255, 65, 0.2);
    }

    .revenue-icon {
        font-size: 3em;
        margin-bottom: 15px;
    }

    .revenue-card h3 {
        color: var(--white-marble);
        font-size: 1.3em;
        margin-bottom: 10px;
    }

    .revenue-card p {
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 15px;
        line-height: 1.4;
    }

    .revenue-amount {
        font-size: 1.5em;
        font-weight: bold;
        color: var(--neon-green);
    }

    /* Affiliate Program */
    .affiliate-program {
        background: linear-gradient(135deg, var(--shining-gold) 0%, var(--platinum) 100%);
        padding: 40px;
        border-radius: 15px;
        text-align: center;
        margin: 40px 0;
    }

    .affiliate-program h2 {
        color: var(--luxury-black);
        margin-bottom: 15px;
    }

    .affiliate-program p {
        color: var(--deep-metal-blue);
        font-size: 1.2em;
        margin-bottom: 25px;
    }

    .affiliate-btn {
        background: var(--neon-green);
        color: var(--luxury-black);
        padding: 15px 30px;
        border: none;
        border-radius: 25px;
        font-size: 1.1em;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .affiliate-btn:hover {
        background: var(--deep-metal-blue);
        color: var(--white-marble);
        transform: scale(1.05);
    }

    /* Cart Sidebar */
    .cart-sidebar {
        position: fixed;
        right: -400px;
        top: 0;
        width: 400px;
        height: 100vh;
        background: var(--white-marble);
        box-shadow: -5px 0 15px rgba(0, 0, 0, 0.3);
        transition: right 0.3s ease;
        z-index: 1000;
        display: flex;
        flex-direction: column;
    }

    .cart-sidebar.open {
        right: 0;
    }

    .cart-header {
        padding: 20px;
        background: var(--luxury-black);
        color: var(--white-marble);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .cart-items {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
    }

    .cart-footer {
        padding: 20px;
        background: var(--platinum);
        border-top: 2px solid var(--shining-gold);
    }

    .cart-total {
        text-align: center;
        font-size: 1.3em;
        margin-bottom: 15px;
        color: var(--luxury-black);
    }

    .checkout-btn {
        width: 100%;
        background: var(--neon-green);
        color: var(--luxury-black);
        padding: 15px;
        border: none;
        border-radius: 8px;
        font-size: 1.1em;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .checkout-btn:hover {
        background: var(--shining-gold);
    }

    /* Fixed Cart Icon */
    .cart-icon-fixed {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: var(--neon-green);
        color: var(--luxury-black);
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2em;
        font-weight: bold;
        cursor: pointer;
        box-shadow: 0 5px 15px rgba(0, 255, 65, 0.3);
        transition: all 0.3s ease;
        z-index: 999;
    }

    .cart-icon-fixed:hover {
        transform: scale(1.1);
        box-shadow: 0 10px 25px rgba(0, 255, 65, 0.5);
    }

    /* Load More Button */
    .load-more-container {
        text-align: center;
        margin: 40px 0;
    }

    .load-more-btn {
        background: var(--deep-metal-blue);
        color: var(--white-marble);
        padding: 15px 30px;
        border: none;
        border-radius: 25px;
        font-size: 1.1em;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .load-more-btn:hover {
        background: var(--luxury-black);
        transform: scale(1.05);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .shop-hero {
            padding: 40px 20px;
        }

        .shop-hero h1 {
            font-size: 2em;
        }

        .revenue-stats {
            gap: 20px;
        }

        .stat-number {
            font-size: 2em;
        }

        .category-grid {
            grid-template-columns: 1fr;
        }

        .products-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        }

        .products-header {
            flex-direction: column;
            align-items: stretch;
        }

        .product-filters {
            justify-content: center;
        }

        .cart-sidebar {
            width: 100%;
            right: -100%;
        }
    }

    /* Animations */
    @keyframes gradientShift {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .product-card {
        animation: fadeInUp 0.6s ease-out;
    }
</style>

<script>
    class YouTuneAIShop {
        constructor() {
            this.products = [];
            this.cart = JSON.parse(localStorage.getItem('youtuneai_cart')) || [];
            this.currentPage = 1;
            this.productsPerPage = 12;
            this.totalProducts = 0;
            this.currentCategory = 'all';
            this.currentSort = 'newest';
            this.searchQuery = '';

            this.initializeShop();
            this.generateProducts();
            this.setupEventListeners();
            this.updateCartDisplay();
            this.initializeAds();
        }

        initializeShop() {
            console.log('🛒 YouTuneAI Shop initialized with automated catalog');
            this.displayFeaturedProducts();
            this.displayAllProducts();
        }

        generateProducts() {
            // Automated product catalog generation
            const categories = {
                'ai-tools': {
                    name: 'AI Music Tools',
                    products: [{
                            name: 'AI Voice Generator Pro',
                            price: 199.99,
                            originalPrice: 299.99,
                            icon: '🎤',
                            rating: 4.8,
                            sales: 1250
                        },
                        {
                            name: 'Beat Maker AI',
                            price: 149.99,
                            originalPrice: 199.99,
                            icon: '🥁',
                            rating: 4.7,
                            sales: 980
                        },
                        {
                            name: 'AI Composer Suite',
                            price: 299.99,
                            originalPrice: 399.99,
                            icon: '🎼',
                            rating: 4.9,
                            sales: 756
                        },
                        {
                            name: 'Voice Synthesis Pro',
                            price: 249.99,
                            originalPrice: 349.99,
                            icon: '🗣️',
                            rating: 4.6,
                            sales: 643
                        },
                        {
                            name: 'AI Lyric Writer',
                            price: 99.99,
                            originalPrice: 149.99,
                            icon: '✍️',
                            rating: 4.5,
                            sales: 892
                        }
                    ]
                },
                'software': {
                    name: 'Professional Software',
                    products: [{
                            name: 'ProMix Master Suite',
                            price: 399.99,
                            originalPrice: 599.99,
                            icon: '🎛️',
                            rating: 4.9,
                            sales: 567
                        },
                        {
                            name: 'Studio One Pro',
                            price: 299.99,
                            originalPrice: 399.99,
                            icon: '💻',
                            rating: 4.8,
                            sales: 1123
                        },
                        {
                            name: 'Vocal Processor Elite',
                            price: 199.99,
                            originalPrice: 299.99,
                            icon: '🎙️',
                            rating: 4.7,
                            sales: 789
                        },
                        {
                            name: 'Master Chain Pro',
                            price: 149.99,
                            originalPrice: 199.99,
                            icon: '⛓️',
                            rating: 4.6,
                            sales: 456
                        },
                        {
                            name: 'Auto-Tune Extreme',
                            price: 249.99,
                            originalPrice: 349.99,
                            icon: '🎯',
                            rating: 4.8,
                            sales: 1034
                        }
                    ]
                },
                'samples': {
                    name: 'Sample Packs',
                    products: [{
                            name: 'Trap Mega Pack 2024',
                            price: 49.99,
                            originalPrice: 79.99,
                            icon: '🔥',
                            rating: 4.7,
                            sales: 2340
                        },
                        {
                            name: 'Lo-Fi Hip Hop Collection',
                            price: 39.99,
                            originalPrice: 59.99,
                            icon: '🎵',
                            rating: 4.6,
                            sales: 1876
                        },
                        {
                            name: 'EDM Drop Library',
                            price: 59.99,
                            originalPrice: 89.99,
                            icon: '⚡',
                            rating: 4.8,
                            sales: 1234
                        },
                        {
                            name: 'Vocal Chops Ultimate',
                            price: 44.99,
                            originalPrice: 69.99,
                            icon: '🎤',
                            rating: 4.5,
                            sales: 987
                        },
                        {
                            name: 'Guitar Loops Pro',
                            price: 34.99,
                            originalPrice: 49.99,
                            icon: '🎸',
                            rating: 4.7,
                            sales: 1567
                        }
                    ]
                },
                'courses': {
                    name: 'Music Courses',
                    products: [{
                            name: 'Music Production Masterclass',
                            price: 197.99,
                            originalPrice: 297.99,
                            icon: '🎓',
                            rating: 4.9,
                            sales: 345
                        },
                        {
                            name: 'Mixing & Mastering Course',
                            price: 147.99,
                            originalPrice: 197.99,
                            icon: '📚',
                            rating: 4.8,
                            sales: 567
                        },
                        {
                            name: 'AI Music Creation Guide',
                            price: 97.99,
                            originalPrice: 147.99,
                            icon: '🤖',
                            rating: 4.7,
                            sales: 234
                        },
                        {
                            name: 'Vocal Recording Techniques',
                            price: 127.99,
                            originalPrice: 177.99,
                            icon: '🎙️',
                            rating: 4.6,
                            sales: 456
                        },
                        {
                            name: 'Sound Design Fundamentals',
                            price: 117.99,
                            originalPrice: 167.99,
                            icon: '🔊',
                            rating: 4.8,
                            sales: 378
                        }
                    ]
                },
                'hardware': {
                    name: 'Studio Hardware',
                    products: [{
                            name: 'Audio Interface Pro',
                            price: 299.99,
                            originalPrice: 399.99,
                            icon: '🎛️',
                            rating: 4.8,
                            sales: 234
                        },
                        {
                            name: 'Studio Monitors Pair',
                            price: 499.99,
                            originalPrice: 699.99,
                            icon: '🔊',
                            rating: 4.9,
                            sales: 156
                        },
                        {
                            name: 'MIDI Controller 61',
                            price: 199.99,
                            originalPrice: 299.99,
                            icon: '🎹',
                            rating: 4.7,
                            sales: 345
                        },
                        {
                            name: 'Studio Headphones Pro',
                            price: 149.99,
                            originalPrice: 199.99,
                            icon: '🎧',
                            rating: 4.6,
                            sales: 567
                        },
                        {
                            name: 'Microphone Preamp',
                            price: 249.99,
                            originalPrice: 349.99,
                            icon: '📡',
                            rating: 4.8,
                            sales: 123
                        }
                    ]
                },
                'services': {
                    name: 'Pro Services',
                    products: [{
                            name: 'Professional Mixing',
                            price: 199.99,
                            originalPrice: 299.99,
                            icon: '⭐',
                            rating: 4.9,
                            sales: 89
                        },
                        {
                            name: 'Mastering Service',
                            price: 99.99,
                            originalPrice: 149.99,
                            icon: '🎯',
                            rating: 4.8,
                            sales: 123
                        },
                        {
                            name: 'Ghost Production',
                            price: 499.99,
                            originalPrice: 799.99,
                            icon: '👻',
                            rating: 4.7,
                            sales: 45
                        },
                        {
                            name: 'Vocal Tuning Service',
                            price: 79.99,
                            originalPrice: 119.99,
                            icon: '🎵',
                            rating: 4.6,
                            sales: 167
                        },
                        {
                            name: '1-on-1 Coaching',
                            price: 149.99,
                            originalPrice: 199.99,
                            icon: '🎓',
                            rating: 4.9,
                            sales: 67
                        }
                    ]
                }
            };

            // Generate products with automatic catalog population
            this.products = [];
            Object.keys(categories).forEach(categoryKey => {
                const category = categories[categoryKey];
                category.products.forEach((product, index) => {
                    this.products.push({
                        id: `${categoryKey}_${index}`,
                        name: product.name,
                        category: categoryKey,
                        categoryName: category.name,
                        price: product.price,
                        originalPrice: product.originalPrice,
                        icon: product.icon,
                        rating: product.rating,
                        reviews: Math.floor(product.sales * 0.3),
                        sales: product.sales,
                        description: this.generateProductDescription(product.name),
                        isFeatured: product.sales > 500,
                        isNew: index < 2,
                        isBestseller: product.sales > 1000,
                        affiliate: this.generateAffiliateData(),
                        dateAdded: new Date(Date.now() - Math.random() * 90 * 24 * 60 * 60 * 1000)
                    });
                });
            });

            this.totalProducts = this.products.length;
            console.log(`Generated ${this.totalProducts} products automatically`);
        }

        generateProductDescription(name) {
            const descriptions = {
                'AI Voice Generator Pro': 'Create professional voice overs with AI technology. Generate realistic voices in multiple languages and styles.',
                'Beat Maker AI': 'AI-powered beat creation tool with intelligent pattern generation and genre-specific algorithms.',
                'AI Composer Suite': 'Complete AI composition software for creating full tracks across all genres with intelligent arrangement.',
                'ProMix Master Suite': 'Professional mixing and mastering software with AI-assisted processing and automatic gain staging.',
                'Trap Mega Pack 2024': 'Latest trap samples, loops, and one-shots. Over 500 premium sounds for modern trap production.',
                'Music Production Masterclass': 'Complete guide to music production from beginner to professional level with hands-on projects.'
            };

            return descriptions[name] || `Premium ${name.toLowerCase()} with professional quality and advanced features for music producers and artists.`;
        }

        generateAffiliateData() {
            const affiliatePrograms = [{
                    name: 'Amazon Associates',
                    commission: 0.08,
                    url: 'https://amazon.com/associate'
                },
                {
                    name: 'Plugin Boutique',
                    commission: 0.15,
                    url: 'https://pluginboutique.com/affiliate'
                },
                {
                    name: 'Splice Sounds',
                    commission: 0.12,
                    url: 'https://splice.com/affiliate'
                },
                {
                    name: 'Native Instruments',
                    commission: 0.10,
                    url: 'https://native-instruments.com/affiliate'
                }
            ];

            return affiliatePrograms[Math.floor(Math.random() * affiliatePrograms.length)];
        }

        displayFeaturedProducts() {
            const featuredProducts = this.products.filter(p => p.isFeatured).slice(0, 6);
            const container = document.getElementById('featuredProducts');

            container.innerHTML = featuredProducts.map(product => this.createProductCard(product)).join('');
        }

        displayAllProducts() {
            const filteredProducts = this.getFilteredProducts();
            const startIndex = (this.currentPage - 1) * this.productsPerPage;
            const endIndex = startIndex + this.productsPerPage;
            const productsToShow = filteredProducts.slice(startIndex, endIndex);

            const container = document.getElementById('allProducts');

            if (this.currentPage === 1) {
                container.innerHTML = '';
            }

            container.innerHTML += productsToShow.map(product => this.createProductCard(product)).join('');

            // Show/hide load more button
            const loadMoreBtn = document.getElementById('loadMoreProducts');
            if (endIndex >= filteredProducts.length) {
                loadMoreBtn.style.display = 'none';
            } else {
                loadMoreBtn.style.display = 'block';
            }
        }

        getFilteredProducts() {
            let filtered = [...this.products];

            // Filter by category
            if (this.currentCategory !== 'all') {
                filtered = filtered.filter(p => p.category === this.currentCategory);
            }

            // Filter by search
            if (this.searchQuery) {
                filtered = filtered.filter(p =>
                    p.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                    p.description.toLowerCase().includes(this.searchQuery.toLowerCase())
                );
            }

            // Sort products
            switch (this.currentSort) {
                case 'newest':
                    filtered.sort((a, b) => new Date(b.dateAdded) - new Date(a.dateAdded));
                    break;
                case 'popular':
                    filtered.sort((a, b) => b.sales - a.sales);
                    break;
                case 'price-low':
                    filtered.sort((a, b) => a.price - b.price);
                    break;
                case 'price-high':
                    filtered.sort((a, b) => b.price - a.price);
                    break;
                case 'rating':
                    filtered.sort((a, b) => b.rating - a.rating);
                    break;
            }

            return filtered;
        }

        createProductCard(product) {
            const discount = Math.round(((product.originalPrice - product.price) / product.originalPrice) * 100);
            const badges = [];

            if (product.isNew) badges.push('NEW');
            if (product.isBestseller) badges.push('BESTSELLER');
            if (discount > 20) badges.push(`${discount}% OFF`);

            return `
            <div class="product-card" data-product-id="${product.id}">
                <div class="product-image">
                    ${product.icon}
                    ${badges.length > 0 ? `<div class="product-badge">${badges[0]}</div>` : ''}
                </div>
                <div class="product-content">
                    <h3 class="product-title">${product.name}</h3>
                    <p class="product-description">${product.description}</p>
                    <div class="product-rating">
                        <div class="stars">${'★'.repeat(Math.floor(product.rating))}${'☆'.repeat(5 - Math.floor(product.rating))}</div>
                        <span class="rating-count">(${product.reviews} reviews)</span>
                    </div>
                    <div class="product-price">
                        <span class="price-current">$${product.price}</span>
                        ${product.originalPrice > product.price ? `<span class="price-original">$${product.originalPrice}</span>` : ''}
                    </div>
                    <div class="product-actions">
                        <button class="add-to-cart-btn" onclick="shop.addToCart('${product.id}')">Add to Cart</button>
                        <button class="view-details-btn" onclick="shop.viewProduct('${product.id}')">Details</button>
                    </div>
                </div>
            </div>
        `;
        }

        setupEventListeners() {
            // Category filters
            document.querySelectorAll('.category-card').forEach(card => {
                card.addEventListener('click', () => {
                    const category = card.dataset.category;
                    this.filterByCategory(category);
                });
            });

            // Sort filter
            document.getElementById('sortFilter').addEventListener('change', (e) => {
                this.currentSort = e.target.value;
                this.currentPage = 1;
                this.displayAllProducts();
            });

            // Category filter
            document.getElementById('categoryFilter').addEventListener('change', (e) => {
                this.currentCategory = e.target.value;
                this.currentPage = 1;
                this.displayAllProducts();
            });

            // Search
            document.getElementById('searchProducts').addEventListener('input', (e) => {
                this.searchQuery = e.target.value;
                this.currentPage = 1;
                this.displayAllProducts();
            });

            // Load more
            document.getElementById('loadMoreProducts').addEventListener('click', () => {
                this.currentPage++;
                this.displayAllProducts();
            });
        }

        filterByCategory(category) {
            this.currentCategory = category;
            this.currentPage = 1;
            document.getElementById('categoryFilter').value = category;
            this.displayAllProducts();
        }

        addToCart(productId) {
            const product = this.products.find(p => p.id === productId);
            if (!product) return;

            const existingItem = this.cart.find(item => item.productId === productId);

            if (existingItem) {
                existingItem.quantity++;
            } else {
                this.cart.push({
                    productId: productId,
                    name: product.name,
                    price: product.price,
                    icon: product.icon,
                    quantity: 1
                });
            }

            this.saveCart();
            this.updateCartDisplay();
            this.showNotification(`Added ${product.name} to cart!`, 'success');

            // Track affiliate commission
            this.trackAffiliateSale(product);
        }

        trackAffiliateSale(product) {
            console.log(`💰 Affiliate commission tracked: ${product.affiliate.name} - ${(product.price * product.affiliate.commission).toFixed(2)}$`);

            // Send to analytics/affiliate tracking
            if (typeof gtag !== 'undefined') {
                gtag('event', 'add_to_cart', {
                    currency: 'USD',
                    value: product.price,
                    items: [{
                        item_id: product.id,
                        item_name: product.name,
                        category: product.categoryName,
                        price: product.price
                    }]
                });
            }
        }

        removeFromCart(productId) {
            this.cart = this.cart.filter(item => item.productId !== productId);
            this.saveCart();
            this.updateCartDisplay();
            this.showNotification('Item removed from cart', 'info');
        }

        updateCartQuantity(productId, newQuantity) {
            const item = this.cart.find(item => item.productId === productId);
            if (item) {
                if (newQuantity <= 0) {
                    this.removeFromCart(productId);
                } else {
                    item.quantity = newQuantity;
                    this.saveCart();
                    this.updateCartDisplay();
                }
            }
        }

        updateCartDisplay() {
            const cartCount = this.cart.reduce((total, item) => total + item.quantity, 0);
            const cartTotal = this.cart.reduce((total, item) => total + (item.price * item.quantity), 0);

            document.getElementById('cartCount').textContent = cartCount;
            document.getElementById('cartTotal').textContent = cartTotal.toFixed(2);

            // Update cart items
            const cartItemsContainer = document.getElementById('cartItems');
            if (this.cart.length === 0) {
                cartItemsContainer.innerHTML = '<p style="text-align: center; color: #666;">Your cart is empty</p>';
            } else {
                cartItemsContainer.innerHTML = this.cart.map(item => `
                <div class="cart-item" style="display: flex; justify-content: space-between; align-items: center; padding: 10px; border-bottom: 1px solid #eee;">
                    <div>
                        <span>${item.icon}</span>
                        <strong>${item.name}</strong>
                        <br>
                        <small>$${item.price} x ${item.quantity}</small>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <button onclick="shop.updateCartQuantity('${item.productId}', ${item.quantity - 1})" style="background: #e74c3c; color: white; border: none; border-radius: 3px; padding: 5px 8px; cursor: pointer;">-</button>
                        <span>${item.quantity}</span>
                        <button onclick="shop.updateCartQuantity('${item.productId}', ${item.quantity + 1})" style="background: #27ae60; color: white; border: none; border-radius: 3px; padding: 5px 8px; cursor: pointer;">+</button>
                        <button onclick="shop.removeFromCart('${item.productId}')" style="background: #95a5a6; color: white; border: none; border-radius: 3px; padding: 5px 8px; cursor: pointer;">×</button>
                    </div>
                </div>
            `).join('');
            }
        }

        saveCart() {
            localStorage.setItem('youtuneai_cart', JSON.stringify(this.cart));
        }

        toggleCart() {
            const cartSidebar = document.getElementById('cartSidebar');
            cartSidebar.classList.toggle('open');
        }

        proceedToCheckout() {
            if (this.cart.length === 0) {
                this.showNotification('Your cart is empty!', 'error');
                return;
            }

            // Simulate checkout process
            const total = this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

            this.showNotification('Redirecting to secure checkout...', 'info');

            setTimeout(() => {
                // Track conversion for ads
                this.trackConversion(total);

                // In a real implementation, redirect to payment processor
                alert(`Checkout completed! Total: $${total.toFixed(2)}\n\nThank you for your purchase!`);

                // Clear cart
                this.cart = [];
                this.saveCart();
                this.updateCartDisplay();
                this.toggleCart();
            }, 2000);
        }

        trackConversion(amount) {
            console.log(`💰 Conversion tracked: $${amount.toFixed(2)}`);

            // Google Analytics conversion tracking
            if (typeof gtag !== 'undefined') {
                gtag('event', 'purchase', {
                    transaction_id: 'TXN_' + Date.now(),
                    value: amount,
                    currency: 'USD',
                    items: this.cart.map(item => ({
                        item_id: item.productId,
                        item_name: item.name,
                        category: 'Digital Products',
                        quantity: item.quantity,
                        price: item.price
                    }))
                });
            }
        }

        viewProduct(productId) {
            const product = this.products.find(p => p.id === productId);
            if (!product) return;

            // Show product modal with detailed information
            const modalContent = `
            <div style="text-align: center;">
                <div style="font-size: 4em; margin-bottom: 20px;">${product.icon}</div>
                <h2>${product.name}</h2>
                <p style="color: #666; margin-bottom: 20px;">${product.description}</p>
                
                <div style="display: flex; justify-content: center; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <strong>Rating:</strong><br>
                        <div style="color: #ffd700;">${'★'.repeat(Math.floor(product.rating))}${'☆'.repeat(5 - Math.floor(product.rating))}</div>
                        <small>${product.rating}/5 (${product.reviews} reviews)</small>
                    </div>
                    <div>
                        <strong>Sales:</strong><br>
                        <span style="color: #27ae60;">${product.sales} sold</span>
                    </div>
                </div>
                
                <div style="margin-bottom: 30px;">
                    <span style="font-size: 2em; color: #27ae60; font-weight: bold;">$${product.price}</span>
                    ${product.originalPrice > product.price ? `<span style="text-decoration: line-through; color: #999; margin-left: 10px;">$${product.originalPrice}</span>` : ''}
                </div>
                
                <div style="display: flex; gap: 15px; justify-content: center;">
                    <button onclick="shop.addToCart('${product.id}'); shop.closeProductModal();" style="background: #27ae60; color: white; padding: 15px 30px; border: none; border-radius: 8px; font-size: 1.1em; cursor: pointer;">Add to Cart</button>
                    <button onclick="shop.closeProductModal();" style="background: #95a5a6; color: white; padding: 15px 30px; border: none; border-radius: 8px; font-size: 1.1em; cursor: pointer;">Close</button>
                </div>
            </div>
        `;

            document.getElementById('modalProductContent').innerHTML = modalContent;
            document.getElementById('productModal').style.display = 'flex';
        }

        closeProductModal() {
            document.getElementById('productModal').style.display = 'none';
        }

        initializeAds() {
            // Initialize Google AdSense
            this.loadGoogleAds();

            // Initialize affiliate tracking
            this.setupAffiliateTracking();

            // Track page view for ads
            this.trackPageView();
        }

        loadGoogleAds() {
            // Simulate Google AdSense ad loading
            const adSlots = document.querySelectorAll('.google-ads-slot');

            adSlots.forEach((slot, index) => {
                setTimeout(() => {
                    // Simulate ad content
                    const adTypes = [{
                            content: '🎵 Music Production Course - 50% Off!',
                            color: '#3498db'
                        },
                        {
                            content: '🎧 Premium Headphones - Free Shipping',
                            color: '#e74c3c'
                        },
                        {
                            content: '💻 DAW Software Sale - Limited Time',
                            color: '#27ae60'
                        },
                        {
                            content: '🎤 Professional Microphones - Best Prices',
                            color: '#f39c12'
                        }
                    ];

                    const ad = adTypes[index % adTypes.length];
                    slot.innerHTML = `
                    <div style="background: ${ad.color}; color: white; padding: 20px; border-radius: 8px; text-align: center; cursor: pointer;" onclick="shop.trackAdClick('${ad.content}')">
                        <strong>${ad.content}</strong>
                        <br><small>Advertisement</small>
                    </div>
                `;
                    slot.style.background = 'transparent';
                    slot.style.border = 'none';
                }, 1000 + (index * 500));
            });

            console.log('🎯 Google Ads initialized - Revenue stream active');
        }

        setupAffiliateTracking() {
            // Track affiliate clicks and conversions
            console.log('🤝 Affiliate tracking initialized');

            // Set up Amazon Associates tracking
            if (typeof window !== 'undefined') {
                window.amazonAffiliateTag = 'youtuneai-20';
            }
        }

        trackPageView() {
            console.log('📊 Page view tracked for ad revenue');

            // Google Analytics
            if (typeof gtag !== 'undefined') {
                gtag('event', 'page_view', {
                    page_title: 'YouTuneAI Shop',
                    page_location: window.location.href
                });
            }
        }

        trackAdClick(adContent) {
            console.log(`📢 Ad clicked: ${adContent}`);

            // Track ad revenue
            if (typeof gtag !== 'undefined') {
                gtag('event', 'ad_click', {
                    ad_content: adContent,
                    value: 0.50 // Estimated revenue per click
                });
            }

            this.showNotification('Opening advertiser website...', 'info');
        }

        showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#27ae60' : type === 'error' ? '#e74c3c' : '#3498db'};
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            z-index: 10000;
            font-weight: bold;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            animation: slideInRight 0.3s ease;
        `;

            notification.textContent = message;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.animation = 'slideOutRight 0.3s ease';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }
    }

    // Initialize shop when page loads
    let shop;
    document.addEventListener('DOMContentLoaded', () => {
        shop = new YouTuneAIShop();
    });

    // Global functions for HTML onclick events
    function toggleCart() {
        shop.toggleCart();
    }

    function closeProductModal() {
        shop.closeProductModal();
    }

    function proceedToCheckout() {
        shop.proceedToCheckout();
    }

    function openAffiliateSignup() {
        shop.showNotification('Redirecting to affiliate program signup...', 'info');
        setTimeout(() => {
            alert('Welcome to the YouTuneAI Affiliate Program!\n\nEarn 30% commission on every sale you refer.\n\nContact: affiliate@youtuneai.com');
        }, 1000);
    }

    // CSS animations
    const style = document.createElement('style');
    style.textContent = `
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slideOutRight {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
    
    .product-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.8);
        z-index: 10000;
        align-items: center;
        justify-content: center;
    }
    
    .modal-content {
        background: white;
        padding: 40px;
        border-radius: 15px;
        max-width: 500px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
        position: relative;
    }
    
    .close-modal {
        position: absolute;
        top: 15px;
        right: 20px;
        font-size: 30px;
        cursor: pointer;
        color: #999;
    }
    
    .close-modal:hover {
        color: #333;
    }
`;
    document.head.appendChild(style);
</script>

<?php get_footer(); ?>