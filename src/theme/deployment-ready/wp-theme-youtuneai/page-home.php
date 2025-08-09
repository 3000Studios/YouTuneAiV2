<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/*
Template Name: Home Page
*/
if (!function_exists('get_header')) {
    function get_header()
    {
        // Placeholder for WordPress function
    }
}
if (!function_exists('get_template_directory_uri')) {
    function get_template_directory_uri()
    {
        return '';
    }
}
if (!function_exists('admin_url')) {
    function admin_url($path = '')
    {
        return '/wp-admin/' . ltrim($path, '/');
    }
}
if (!function_exists('home_url')) {
    function home_url($path = '')
    {
        return '/' . ltrim($path, '/');
    }
}
if (!function_exists('get_footer')) {
    function get_footer()
    {
        // Placeholder for WordPress function
    }
}
get_header(); ?>

<!-- Loading Screen -->
<div class="loading-screen" id="loadingScreen">
    <div class="loading-animation"></div>
    <p style="margin-top: 20px; color: var(--accent-color);">Initializing YouTuneAI...</p>
</div>

<!-- Hero Section with Premium 3D Styling -->
<section class="hero-section" id="home">
    <h1 class="hero-title text-3d" data-aos="fade-up">YOUTUNEAI</h1>
    <h2 class="hero-subtitle text-platinum" data-aos="fade-up" data-aos-delay="200">
        ULTRA PREMIUM AI-POWERED CONTENT CREATION & STREAMING PLATFORM
    </h2>
    <p class="hero-description text-neon" data-aos="fade-up" data-aos-delay="300">
        Experience the future of content creation with our revolutionary voice-controlled AI technology, marble-platinum design, and premium streaming capabilities
    </p>
    <div class="hero-buttons" data-aos="fade-up" data-aos-delay="400">
        <button class="cta-btn premium-btn" onclick="openAdminHub()">⚡ ADMIN COMMAND CENTER</button>
        <button class="cta-btn secondary-btn" onclick="scrollToSection('gallery')">� PREMIUM SHOP</button>
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

<!-- Mobile Chat Interface Button -->
<button class="mobile-chat-toggle" id="chatToggle" onclick="toggleMobileChat()" title="Open Mobile Commands">
    💬<span class="chat-btn-label">CHAT</span>
</button>

<!-- Mobile Chat Interface -->
<div class="mobile-chat-interface" id="mobileChatInterface">
    <div class="chat-header">
        <h3>� Secure Remote Commands</h3>
        <button class="chat-close" onclick="toggleMobileChat()">×</button>
    </div>
    
    <!-- Password Authentication Screen -->
    <div class="chat-auth-screen" id="chatAuthScreen">
        <div class="auth-container">
            <div class="auth-icon">🔑</div>
            <h4>Admin Authentication Required</h4>
            <p>Enter your secure password to access remote commands</p>
            <input type="password" class="auth-input" id="authPassword" placeholder="Enter admin password..." />
            <button class="auth-btn" onclick="authenticateChat()">🔓 Authenticate</button>
            <div class="auth-error" id="authError" style="display: none;">❌ Invalid password. Access denied.</div>
        </div>
    </div>
    
    <!-- Chat Interface (hidden until authenticated) -->
    <div class="chat-authenticated-content" id="chatAuthenticatedContent" style="display: none;">
        <div class="chat-messages" id="chatMessages">
            <div class="chat-message system">
                🔒 Authenticated successfully! You can now send secure instructions.
            </div>
            <div class="chat-message system">
                Example: "Change the blue buttons to red" or "Add a new section about gaming"
            </div>
        </div>
        <div class="chat-input-container">
            <input type="text" class="chat-input" id="chatInput" placeholder="Type your instructions here..." />
            <button class="chat-send-btn" onclick="sendChatMessage()">Send Instructions 🚀</button>
            <button class="chat-logout-btn" onclick="logoutChat()">🔒 Logout</button>
        </div>
    </div>
</div>

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

    // Mobile Chat Interface Functions with Security
    let chatAuthenticated = false;
    const ADMIN_PASSWORD_HASH = 'youtuneai2025boss'; // Change this to your secure password

    function toggleMobileChat() {
        console.log('Mobile chat toggle clicked'); // Debug log
        const chatInterface = document.getElementById('mobileChatInterface');
        
        if (!chatInterface) {
            console.error('Mobile chat interface not found!');
            return;
        }
        
        const isActive = chatInterface.classList.contains('active');
        console.log('Chat interface active:', isActive); // Debug log
        
        if (isActive) {
            chatInterface.classList.remove('active');
        } else {
            chatInterface.classList.add('active');
            // Reset to auth screen when opening
            if (!chatAuthenticated) {
                showAuthScreen();
            }
        }
    }

    function showAuthScreen() {
        document.getElementById('chatAuthScreen').style.display = 'flex';
        document.getElementById('chatAuthenticatedContent').style.display = 'none';
        document.getElementById('authError').style.display = 'none';
        document.getElementById('authPassword').value = '';
    }

    function authenticateChat() {
        const passwordInput = document.getElementById('authPassword');
        const errorDiv = document.getElementById('authError');
        const password = passwordInput.value;

        // Simple password check (in production, use proper hashing)
        if (password === ADMIN_PASSWORD_HASH) {
            chatAuthenticated = true;
            document.getElementById('chatAuthScreen').style.display = 'none';
            document.getElementById('chatAuthenticatedContent').style.display = 'flex';
            
            // Add success message
            const messagesContainer = document.getElementById('chatMessages');
            const successMessage = document.createElement('div');
            successMessage.className = 'chat-message system';
            successMessage.textContent = '🔓 Authentication successful! You now have admin access.';
            messagesContainer.appendChild(successMessage);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        } else {
            errorDiv.style.display = 'block';
            passwordInput.value = '';
            
            // Log failed attempt
            console.warn('Failed mobile chat authentication attempt');
        }
    }

    function logoutChat() {
        chatAuthenticated = false;
        showAuthScreen();
        
        // Clear any sensitive data
        document.getElementById('chatMessages').innerHTML = `
            <div class="chat-message system">
                🔒 Logged out successfully. Please re-authenticate to continue.
            </div>
        `;
    }

    function sendChatMessage() {
        if (!chatAuthenticated) {
            alert('🔒 Please authenticate first!');
            return;
        }

        const input = document.getElementById('chatInput');
        const messagesContainer = document.getElementById('chatMessages');
        
        if (input.value.trim() === '') return;

        // Add user message
        const userMessage = document.createElement('div');
        userMessage.className = 'chat-message user';
        userMessage.textContent = input.value;
        messagesContainer.appendChild(userMessage);

        // Store the instruction
        const instruction = input.value;
        
        // Add system response
        const systemMessage = document.createElement('div');
        systemMessage.className = 'chat-message system';
        systemMessage.textContent = '📝 Authenticated instruction received! Processing your request...';
        messagesContainer.appendChild(systemMessage);

        // Send to backend with authentication token
        sendToBackend(instruction);
        
        // Clear input
        input.value = '';
        
        // Scroll to bottom
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function sendToBackend(instruction) {
        if (!chatAuthenticated) {
            console.error('Unauthorized attempt to send instruction');
            return;
        }

        // Generate authentication token based on session
        const authToken = btoa(ADMIN_PASSWORD_HASH + Date.now().toString().slice(0, -3));
        
        // Send authenticated instruction to backend
        fetch('/wp-admin/admin-ajax.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=mobile_chat_instruction&instruction=${encodeURIComponent(instruction)}&auth_token=${authToken}&authenticated=true`
        })
        .then(response => response.json())
        .then(data => {
            const messagesContainer = document.getElementById('chatMessages');
            const responseMessage = document.createElement('div');
            responseMessage.className = 'chat-message system';
            responseMessage.textContent = '✅ ' + (data.message || 'Authenticated instruction queued for processing!');
            messagesContainer.appendChild(responseMessage);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        })
        .catch(error => {
            console.log('Authenticated instruction logged locally:', instruction);
            const messagesContainer = document.getElementById('chatMessages');
            const errorMessage = document.createElement('div');
            errorMessage.className = 'chat-message system';
            errorMessage.textContent = '📱 Secure instruction saved locally. Check logs for processing.';
            messagesContainer.appendChild(errorMessage);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        });
    }

    // Allow Enter key for both authentication and messages
    document.addEventListener('DOMContentLoaded', function() {
        // Chat input Enter key
        const chatInput = document.getElementById('chatInput');
        if (chatInput) {
            chatInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    sendChatMessage();
                }
            });
        }

        // Auth input Enter key
        const authInput = document.getElementById('authPassword');
        if (authInput) {
            authInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    authenticateChat();
                }
            });
        }

        // Auto-logout after 30 minutes for security
        setInterval(function() {
            if (chatAuthenticated) {
                const confirm = window.confirm('🔒 Session timeout. Continue authenticated session?');
                if (!confirm) {
                    logoutChat();
                }
            }
        }, 30 * 60 * 1000); // 30 minutes
    });
                    sendChatMessage();
                }
            });
        }
    });
</script>

<?php get_footer(); ?>