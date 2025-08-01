<?php
/*
Template Name: Home Page
*/
get_header(); ?>

<!-- Loading Screen -->
<div class="loading-screen" id="loadingScreen">
    <div class="loading-animation"></div>
    <p style="margin-top: 20px; color: var(--accent-color);">Initializing YouTuneAI...</p>
</div>

<!-- Video Background -->
<video class="video-background" autoplay muted loop id="bgVideo">
    <source src="<?php echo get_template_directory_uri(); ?>/assets/video/background.mp4" type="video/mp4">
    <!-- Fallback cosmic background -->
    <source src="https://www.learningcontainer.com/wp-content/uploads/2020/05/sample-mp4-file.mp4" type="video/mp4">
</video>
<div class="video-overlay"></div>

<!-- Hero Section -->
<section class="hero-section" id="home">
    <h1 class="hero-title" data-aos="fade-up">YouTuneAI</h1>
    <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="200">
        AI-Powered Content Creation & Streaming Platform
    </p>
    <div class="hero-buttons" data-aos="fade-up" data-aos-delay="400">
        <button class="cta-btn" onclick="openAdminHub()">🎙️ Voice Command</button>
        <button class="cta-btn secondary" onclick="scrollToSection('gallery')">🛒 Shop</button>
    </div>
</section>

<!-- Gallery Shop Section -->
<section class="gallery-shop" id="gallery">
    <h2 class="section-title" data-aos="fade-up">Premium Digital Assets</h2>
    <div class="gallery-grid" id="galleryGrid">
        <!-- Dynamic gallery items will be loaded here -->
        <div class="gallery-item" data-aos="zoom-in" onclick="openProductModal('ai-avatars')">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/avatar-pack.jpg" alt="AI Avatars">
            <div class="gallery-item-content">
                <h3>AI Avatar Collection</h3>
                <p>Premium animated avatars for streaming</p>
                <span class="price">$29.99</span>
            </div>
        </div>

        <div class="gallery-item" data-aos="zoom-in" data-aos-delay="100" onclick="openProductModal('stream-overlays')">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/overlay-pack.jpg" alt="Stream Overlays">
            <div class="gallery-item-content">
                <h3>Stream Overlay Pack</h3>
                <p>Professional gaming overlays</p>
                <span class="price">$19.99</span>
            </div>
        </div>

        <div class="gallery-item" data-aos="zoom-in" data-aos-delay="200" onclick="openProductModal('music-packs')">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/music-pack.jpg" alt="Music Packs">
            <div class="gallery-item-content">
                <h3>Royalty-Free Music</h3>
                <p>Background music for content</p>
                <span class="price">$39.99</span>
            </div>
        </div>
    </div>
</section>

<!-- Streaming & Gaming Cards -->
<section class="streaming-section" id="streaming">
    <h2 class="section-title" data-aos="fade-up">Live Content</h2>
    <div class="streaming-cards">
        <div class="stream-card" data-aos="flip-left" onclick="goToStreaming()">
            <div class="stream-icon">🎮</div>
            <h3>Gaming Streams</h3>
            <p>Watch live gaming content and tutorials</p>
            <button class="stream-btn">Watch Now</button>
        </div>

        <div class="stream-card" data-aos="flip-left" data-aos-delay="100" onclick="goToMusic()">
            <div class="stream-icon">🎵</div>
            <h3>Music Production</h3>
            <p>AI-assisted music creation streams</p>
            <button class="stream-btn">Tune In</button>
        </div>

        <div class="stream-card" data-aos="flip-left" data-aos-delay="200" onclick="goToTutorials()">
            <div class="stream-icon">📚</div>
            <h3>AI Tutorials</h3>
            <p>Learn AI tools and techniques</p>
            <button class="stream-btn">Learn Now</button>
        </div>
    </div>
</section>

<!-- Avatar Carousel -->
<section class="avatar-carousel" id="avatars">
    <h2 class="section-title" data-aos="fade-up">Choose Your Avatar</h2>
    <div class="carousel-container" data-aos="zoom-in">
        <div class="avatar-item active" data-avatar="cyber" style="transform: rotate(0deg) translateX(120px) rotate(0deg);">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/avatar-cyber.png" alt="Cyber Avatar">
        </div>
        <div class="avatar-item" data-avatar="gaming" style="transform: rotate(72deg) translateX(120px) rotate(-72deg);">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/avatar-gaming.png" alt="Gaming Avatar">
        </div>
        <div class="avatar-item" data-avatar="music" style="transform: rotate(144deg) translateX(120px) rotate(-144deg);">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/avatar-music.png" alt="Music Avatar">
        </div>
        <div class="avatar-item" data-avatar="ai" style="transform: rotate(216deg) translateX(120px) rotate(-216deg);">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/avatar-ai.png" alt="AI Avatar">
        </div>
        <div class="avatar-item" data-avatar="creative" style="transform: rotate(288deg) translateX(120px) rotate(-288deg);">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/avatar-creative.png" alt="Creative Avatar">
        </div>
    </div>
    <button class="customize-btn" onclick="goToAvatarCreator()">Customize Avatar</button>
</section>

<!-- Voice Command Button -->
<button class="voice-command-btn" id="voiceBtn" onclick="toggleVoiceCommand()">
    <svg width="40" height="40" viewBox="0 0 24 24" fill="white">
        <path d="M12 14c1.66 0 3-1.34 3-3V5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3z" />
        <path d="M17 11c0 2.76-2.24 5-5 5s-5-2.24-5-5H5c0 3.53 2.61 6.43 6 6.92V21h2v-3.08c3.39-.49 6-3.39 6-6.92h-2z" />
    </svg>
</button>

<!-- Admin Hub Modal -->
<div class="admin-hub" id="adminHub">
    <h2>🧠 AI Command Center</h2>
    <p>Speak or type your command to update the website instantly:</p>

    <textarea
        class="command-input"
        id="commandInput"
        placeholder="Example: 'Change background video to space theme' or 'Add new product called Gaming Headset for $99'"></textarea>

    <div class="command-buttons">
        <button class="execute-btn" onclick="executeCommand()">🚀 Execute Command</button>
        <button class="execute-btn secondary" onclick="startVoiceInput()">🎤 Voice Input</button>
    </div>

    <div class="command-status" id="commandStatus"></div>

    <button class="close-btn" onclick="closeAdminHub()">✕</button>
</div>

<!-- Product Modal -->
<div class="product-modal" id="productModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Product Details</h3>
            <button class="close-modal" onclick="closeProductModal()">✕</button>
        </div>
        <div class="modal-body" id="modalBody">
            <!-- Product details will be loaded here -->
        </div>
        <div class="modal-footer">
            <button class="buy-btn" id="buyBtn">Add to Cart - $0.00</button>
        </div>
    </div>
</div>

<script>
    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
        // Hide loading screen after 2 seconds
        setTimeout(() => {
            document.getElementById('loadingScreen').classList.add('hidden');
        }, 2000);

        // Initialize AOS animations
        if (typeof AOS !== 'undefined') {
            AOS.init({
                duration: 1000,
                once: true
            });
        }

        // Initialize audio
        initializeBackgroundMusic();

        // Start avatar carousel rotation
        startAvatarCarousel();
    });

    // Voice command functionality
    let isListening = false;
    let recognition;

    function toggleVoiceCommand() {
        if (!isListening) {
            startVoiceRecognition();
        } else {
            stopVoiceRecognition();
        }
    }

    function startVoiceRecognition() {
        if ('webkitSpeechRecognition' in window) {
            recognition = new webkitSpeechRecognition();
            recognition.continuous = false;
            recognition.interimResults = false;
            recognition.lang = 'en-US';

            recognition.onstart = function() {
                isListening = true;
                document.getElementById('voiceBtn').classList.add('listening');
            };

            recognition.onresult = function(event) {
                const command = event.results[0][0].transcript;
                document.getElementById('commandInput').value = command;
                executeCommand();
            };

            recognition.onend = function() {
                isListening = false;
                document.getElementById('voiceBtn').classList.remove('listening');
            };

            recognition.start();
        } else {
            alert('Voice recognition not supported in this browser');
        }
    }

    function stopVoiceRecognition() {
        if (recognition) {
            recognition.stop();
        }
    }

    // Admin hub functions
    function openAdminHub() {
        document.getElementById('adminHub').classList.add('active');
    }

    function closeAdminHub() {
        document.getElementById('adminHub').classList.remove('active');
    }

    function executeCommand() {
        const command = document.getElementById('commandInput').value;
        const status = document.getElementById('commandStatus');

        if (!command.trim()) {
            status.innerHTML = '<p style="color: #ff4444;">Please enter a command</p>';
            return;
        }

        status.innerHTML = '<p style="color: #00d4ff;">🧠 Processing command...</p>';

        // Send command to AI processing endpoint
        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=process_ai_command&command=${encodeURIComponent(command)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    status.innerHTML = `<p style="color: #00ff00;">✅ ${data.message}</p>`;
                    if (data.reload) {
                        setTimeout(() => location.reload(), 2000);
                    }
                } else {
                    status.innerHTML = `<p style="color: #ff4444;">❌ ${data.message}</p>`;
                }
            })
            .catch(error => {
                status.innerHTML = '<p style="color: #ff4444;">❌ Error processing command</p>';
            });
    }

    // Navigation functions
    function scrollToSection(sectionId) {
        document.getElementById(sectionId).scrollIntoView({
            behavior: 'smooth'
        });
    }

    function goToStreaming() {
        window.location.href = '<?php echo home_url('/streaming'); ?>';
    }

    function goToMusic() {
        window.location.href = '<?php echo home_url('/music'); ?>';
    }

    function goToTutorials() {
        window.location.href = '<?php echo home_url('/tutorials'); ?>';
    }

    function goToAvatarCreator() {
        window.location.href = '<?php echo home_url('/create-avatar'); ?>';
    }

    // Product modal functions
    function openProductModal(productId) {
        const modal = document.getElementById('productModal');
        const title = document.getElementById('modalTitle');
        const body = document.getElementById('modalBody');
        const buyBtn = document.getElementById('buyBtn');

        // Product data (this would normally come from database)
        const products = {
            'ai-avatars': {
                title: 'AI Avatar Collection',
                description: 'Premium animated avatars perfect for streaming and content creation.',
                price: 29.99,
                features: ['10 Unique Avatars', 'Facial Animation', 'Voice Sync', 'Custom Colors']
            },
            'stream-overlays': {
                title: 'Stream Overlay Pack',
                description: 'Professional gaming overlays to enhance your streams.',
                price: 19.99,
                features: ['15 Overlay Designs', 'Animated Elements', 'OBS Compatible', 'HD Quality']
            },
            'music-packs': {
                title: 'Royalty-Free Music',
                description: 'High-quality background music for all your content.',
                price: 39.99,
                features: ['50 Music Tracks', 'Multiple Genres', 'Commercial License', 'Loop Ready']
            }
        };

        const product = products[productId];
        if (product) {
            title.textContent = product.title;
            body.innerHTML = `
            <p>${product.description}</p>
            <h4>Features:</h4>
            <ul>
                ${product.features.map(feature => `<li>${feature}</li>`).join('')}
            </ul>
            <div class="price-display">$${product.price}</div>
        `;
            buyBtn.textContent = `Add to Cart - $${product.price}`;
            buyBtn.onclick = () => addToCart(productId, product);

            modal.style.display = 'block';
        }
    }

    function closeProductModal() {
        document.getElementById('productModal').style.display = 'none';
    }

    function addToCart(productId, product) {
        // Integration with payment system will be added
        alert(`${product.title} added to cart!`);
        closeProductModal();
    }

    // Avatar carousel
    function startAvatarCarousel() {
        const avatars = document.querySelectorAll('.avatar-item');
        let currentIndex = 0;

        setInterval(() => {
            avatars[currentIndex].classList.remove('active');
            currentIndex = (currentIndex + 1) % avatars.length;
            avatars[currentIndex].classList.add('active');
        }, 3000);
    }

    // Background music
    function initializeBackgroundMusic() {
        const audio = new Audio('<?php echo get_template_directory_uri(); ?>/assets/audio/background-music.mp3');
        audio.loop = true;
        audio.volume = 0.3;

        // Auto-play with user interaction
        document.addEventListener('click', function() {
            audio.play().catch(e => console.log('Audio autoplay prevented'));
        }, {
            once: true
        });
    }
</script>

<?php get_footer(); ?>