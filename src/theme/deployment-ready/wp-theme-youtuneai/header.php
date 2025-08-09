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

    <!-- PREMIUM ANIMATED BACKGROUND -->
    <div class="premium-background">
        <div class="bg-layer bg-layer-1"></div>
        <div class="bg-layer bg-layer-2"></div>
        <div class="bg-layer bg-layer-3"></div>
    </div>

    <!-- Navigation -->
    <nav class="nav-container">
        <div class="nav-menu">
            <div class="logo text-neon">
                <a href="<?php echo home_url(); ?>">YOUTUNEAI</a>
            </div>

            <ul class="nav-links">
                <li><a href="<?php echo home_url(); ?>">🏠 Home</a></li>
                <li><a href="<?php echo home_url('/shop'); ?>">🛒 Shop</a></li>
                <li><a href="<?php echo home_url('/streaming'); ?>">🎮 Streaming</a></li>
                <li><a href="<?php echo home_url('/music'); ?>">🎵 Music</a></li>
                <li><a href="<?php echo home_url('/ai-tools'); ?>">🧠 AI Tools</a></li>
                <li><a href="<?php echo home_url('/create-avatar'); ?>">👤 Avatar</a></li>
                <li class="admin-link"><a href="#" onclick="openAdminHub()">⚡ ADMIN</a></li>
            </ul>

            <!-- Mobile Navigation Toggle -->
            <div class="mobile-nav-toggle" onclick="toggleMobileNav()">
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
            </div>
        </div>
    </nav>

    <!-- Mobile Navigation Menu -->
    <div class="mobile-nav-overlay" onclick="closeMobileNav()"></div>
    <div class="mobile-nav-menu">
        <div class="mobile-nav-item"><a href="<?php echo home_url(); ?>" onclick="closeMobileNav()">🏠 Home</a></div>
        <div class="mobile-nav-item"><a href="<?php echo home_url('/shop'); ?>" onclick="closeMobileNav()">🛒 Shop</a></div>
        <div class="mobile-nav-item"><a href="<?php echo home_url('/streaming'); ?>" onclick="closeMobileNav()">🎮 Streaming</a></div>
        <div class="mobile-nav-item"><a href="<?php echo home_url('/music'); ?>" onclick="closeMobileNav()">🎵 Music</a></div>
        <div class="mobile-nav-item"><a href="<?php echo home_url('/ai-tools'); ?>" onclick="closeMobileNav()">🧠 AI Tools</a></div>
        <div class="mobile-nav-item"><a href="<?php echo home_url('/create-avatar'); ?>" onclick="closeMobileNav()">👤 Avatar</a></div>
        <div class="mobile-nav-item"><a href="#" onclick="openAdminHub(); closeMobileNav();">⚡ ADMIN</a></div>
    </div>

    <script>
        // ULTRA PREMIUM ADMIN HUB JAVASCRIPT
        let isListening = false;
        let recognition = null;

        // Initialize Speech Recognition
        if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            recognition = new SpeechRecognition();
            recognition.continuous = false;
            recognition.interimResults = false;
            recognition.lang = 'en-US';
        }

        // Open Admin Hub
        function openAdminHub() {
            document.getElementById('adminHubOverlay').classList.add('active');
            document.getElementById('adminPasswordScreen').style.display = 'flex';
            document.getElementById('adminMainInterface').style.display = 'none';
            document.getElementById('adminPasswordInput').focus();
        }

        // Close Admin Hub
        function closeAdminHub() {
            document.getElementById('adminHubOverlay').classList.remove('active');
            document.getElementById('adminPasswordInput').value = '';
            document.getElementById('passwordError').textContent = '';
        }

        // Authenticate Admin
        function authenticateAdmin() {
            const password = document.getElementById('adminPasswordInput').value;
            const errorDiv = document.getElementById('passwordError');
            
            // Replace with your secure password
            if (password === 'YouTuneAI2025!') {
                document.getElementById('adminPasswordScreen').style.display = 'none';
                document.getElementById('adminMainInterface').style.display = 'block';
                errorDiv.textContent = '';
                
                // Add success status
                updateStatus('🔐 Authentication Successful - Welcome to Command Center', 'success');
            } else {
                errorDiv.textContent = '❌ Invalid Password - Access Denied';
                document.getElementById('adminPasswordInput').value = '';
                setTimeout(() => errorDiv.textContent = '', 3000);
            }
        }

        // Admin Menu Navigation
        document.addEventListener('DOMContentLoaded', function() {
            const menuItems = document.querySelectorAll('.admin-menu-item');
            const sections = document.querySelectorAll('.admin-section');
            
            menuItems.forEach(item => {
                item.addEventListener('click', function() {
                    // Remove active class from all items
                    menuItems.forEach(mi => mi.classList.remove('active'));
                    // Add active class to clicked item
                    this.classList.add('active');
                    
                    // Hide all sections
                    sections.forEach(section => section.style.display = 'none');
                    // Show selected section
                    const sectionId = this.getAttribute('data-section');
                    document.getElementById(sectionId).style.display = 'block';
                });
            });
        });

        // Start Voice Command
        function startVoiceCommand() {
            if (!recognition) {
                updateStatus('❌ Speech recognition not supported in this browser', 'error');
                return;
            }

            const voiceBtn = document.getElementById('voiceCommandBtn');
            
            if (!isListening) {
                isListening = true;
                voiceBtn.classList.add('listening');
                voiceBtn.innerHTML = '<i class="bx bx-stop"></i><br>LISTENING...';
                
                recognition.start();
                updateStatus('🎙️ Listening for your command...', 'success');
                
                recognition.onresult = function(event) {
                    const transcript = event.results[0][0].transcript;
                    document.getElementById('commandInput').value = transcript;
                    updateStatus('✅ Voice command captured: ' + transcript, 'success');
                };
                
                recognition.onerror = function(event) {
                    updateStatus('❌ Speech recognition error: ' + event.error, 'error');
                    stopVoiceCommand();
                };
                
                recognition.onend = function() {
                    stopVoiceCommand();
                };
                
                // Auto-stop after 10 seconds
                setTimeout(() => {
                    if (isListening) {
                        recognition.stop();
                    }
                }, 10000);
            } else {
                recognition.stop();
            }
        }

        // Stop Voice Command
        function stopVoiceCommand() {
            isListening = false;
            const voiceBtn = document.getElementById('voiceCommandBtn');
            voiceBtn.classList.remove('listening');
            voiceBtn.innerHTML = '<i class="bx bx-microphone"></i><br>SPEAK COMMAND';
        }

        // Focus Text Command
        function focusTextCommand() {
            document.getElementById('commandInput').focus();
            updateStatus('⌨️ Text input focused - Type your command', 'success');
        }

        // Execute Command
        function executeCommand() {
            const command = document.getElementById('commandInput').value.trim();
            if (!command) {
                updateStatus('❌ Please enter a command first', 'error');
                return;
            }
            
            const executeBtn = document.querySelector('.execute-command');
            executeBtn.classList.add('processing');
            executeBtn.textContent = '🔄 PROCESSING COMMAND...';
            
            updateStatus('⚡ Executing command: ' + command, 'success');
            
            // Send command to server for processing
            fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=execute_admin_command&command=' + encodeURIComponent(command) + '&nonce=<?php echo wp_create_nonce("admin_command_nonce"); ?>'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateStatus('✅ Command executed successfully: ' + data.data.message, 'success');
                    if (data.data.reload) {
                        updateStatus('🔄 Reloading page to apply changes...', 'success');
                        setTimeout(() => location.reload(), 2000);
                    }
                } else {
                    updateStatus('❌ Command failed: ' + data.data.message, 'error');
                }
            })
            .catch(error => {
                updateStatus('❌ Network error: ' + error.message, 'error');
            })
            .finally(() => {
                executeBtn.classList.remove('processing');
                executeBtn.textContent = '🚀 EXECUTE COMMAND';
                document.getElementById('commandInput').value = '';
            });
        }

        // Update Status Display
        function updateStatus(message, type = 'info') {
            const statusDisplay = document.getElementById('statusDisplay');
            const timestamp = new Date().toLocaleTimeString();
            const statusLine = document.createElement('div');
            statusLine.className = 'status-line ' + type;
            statusLine.textContent = `[${timestamp}] ${message}`;
            
            // Add to top of status display
            statusDisplay.insertBefore(statusLine, statusDisplay.firstChild);
            
            // Keep only last 20 messages
            const lines = statusDisplay.querySelectorAll('.status-line');
            if (lines.length > 20) {
                lines[lines.length - 1].remove();
            }
        }

        // Password input enter key
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('adminPasswordInput');
            if (passwordInput) {
                passwordInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        authenticateAdmin();
                    }
                });
            }
        });

        // Mobile Navigation Functions
        function toggleMobileNav() {
            const toggle = document.querySelector('.mobile-nav-toggle');
            const menu = document.querySelector('.mobile-nav-menu');
            const overlay = document.querySelector('.mobile-nav-overlay');
            
            toggle.classList.toggle('active');
            menu.classList.toggle('active');
            overlay.classList.toggle('active');
        }

        function openMobileNav() {
            document.querySelector('.mobile-nav-toggle').classList.add('active');
            document.querySelector('.mobile-nav-menu').classList.add('active');
            document.querySelector('.mobile-nav-overlay').classList.add('active');
        }

        function closeMobileNav() {
            document.querySelector('.mobile-nav-toggle').classList.remove('active');
            document.querySelector('.mobile-nav-menu').classList.remove('active');
            document.querySelector('.mobile-nav-overlay').classList.remove('active');
        }

        // Close admin hub with escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAdminHub();
                closeMobileNav();
            }
        });
    </script>

    <!-- Mobile Navigation Menu -->
    <div class="mobile-nav-overlay" onclick="closeMobileNav()"></div>
    <div class="mobile-nav-menu">
        <div class="mobile-nav-item"><a href="<?php echo home_url(); ?>" onclick="closeMobileNav()">🏠 Home</a></div>
        <div class="mobile-nav-item"><a href="<?php echo home_url('/shop'); ?>" onclick="closeMobileNav()">🛒 Shop</a></div>
        <div class="mobile-nav-item"><a href="<?php echo home_url('/streaming'); ?>" onclick="closeMobileNav()">🎮 Streaming</a></div>
        <div class="mobile-nav-item"><a href="<?php echo home_url('/music'); ?>" onclick="closeMobileNav()">🎵 Music</a></div>
        <div class="mobile-nav-item"><a href="<?php echo home_url('/ai-tools'); ?>" onclick="closeMobileNav()">🧠 AI Tools</a></div>
        <div class="mobile-nav-item"><a href="<?php echo home_url('/create-avatar'); ?>" onclick="closeMobileNav()">👤 Avatar</a></div>
        <div class="mobile-nav-item"><a href="#" onclick="openAdminHub(); closeMobileNav();">⚡ ADMIN</a></div>
    </div>

    <!-- ULTRA PREMIUM ADMIN HUB -->
    <div class="admin-hub-overlay" id="adminHubOverlay">
        <div class="admin-hub">
            <!-- Admin Password Screen -->
            <div class="admin-password-screen" id="adminPasswordScreen">
                <div class="password-container">
                    <div class="password-title">ADMIN HUB</div>
                    <div class="password-subtitle">Ultra Premium Control Center</div>
                    <input type="password" class="password-input" id="adminPasswordInput" placeholder="Enter Master Password" />
                    <button class="password-btn" onclick="authenticateAdmin()">ACCESS CONTROL CENTER</button>
                    <div class="password-error" id="passwordError"></div>
                </div>
            </div>

            <!-- Admin Main Interface -->
            <div class="admin-main-interface" id="adminMainInterface" style="display: none;">
                <!-- Admin Header -->
                <div class="admin-header">
                    <div class="admin-logo text-neon">YOUTUNEAI COMMAND CENTER</div>
                    <button class="admin-close" onclick="closeAdminHub()">×</button>
                </div>

                <!-- Admin Content -->
                <div class="admin-content">
                    <!-- Sidebar Menu -->
                    <div class="admin-sidebar">
                        <ul class="admin-menu">
                            <li class="admin-menu-item active" data-section="command-center">
                                <i class="bx bx-microphone"></i>Voice & Text Commands
                            </li>
                            <li class="admin-menu-item" data-section="site-settings">
                                <i class="bx bx-cog"></i>Site Settings
                            </li>
                            <li class="admin-menu-item" data-section="content-manager">
                                <i class="bx bx-edit"></i>Content Manager
                            </li>
                            <li class="admin-menu-item" data-section="file-manager">
                                <i class="bx bx-folder"></i>File Manager
                            </li>
                            <li class="admin-menu-item" data-section="analytics">
                                <i class="bx bx-line-chart"></i>Analytics
                            </li>
                            <li class="admin-menu-item" data-section="security">
                                <i class="bx bx-shield"></i>Security
                            </li>
                        </ul>
                    </div>

                    <!-- Main Content Area -->
                    <div class="admin-main">
                        <!-- Voice & Text Command Center -->
                        <div class="admin-section" id="command-center">
                            <div class="command-center">
                                <div class="command-title">VOICE & TEXT COMMANDS</div>
                                <div class="command-buttons">
                                    <button class="voice-btn" id="voiceCommandBtn" onclick="startVoiceCommand()">
                                        <i class="bx bx-microphone"></i><br>
                                        SPEAK COMMAND
                                    </button>
                                    <button class="text-btn" onclick="focusTextCommand()">
                                        <i class="bx bx-edit"></i><br>
                                        TEXT COMMAND
                                    </button>
                                </div>
                                <textarea 
                                    class="command-input-area" 
                                    id="commandInput" 
                                    placeholder="Speak or type your website command here...&#10;&#10;Examples:&#10;- Change the homepage background to blue&#10;- Add a new product to the shop&#10;- Update the contact information&#10;- Modify the color scheme&#10;- Add new pages or content&#10;- Update styling and animations"
                                ></textarea>
                                <button class="execute-command" onclick="executeCommand()">
                                    🚀 EXECUTE COMMAND
                                </button>
                                <div class="status-display" id="statusDisplay">
                                    <div class="status-line">✅ Admin Hub Ready - Awaiting Your Commands</div>
                                    <div class="status-line">🎙️ Voice Recognition: Ready</div>
                                    <div class="status-line">⚡ Real-time Updates: Enabled</div>
                                    <div class="status-line">🔐 Security: Maximum Protection Active</div>
                                </div>
                            </div>
                        </div>

                        <!-- Other Admin Sections (Hidden by default) -->
                        <div class="admin-section" id="site-settings" style="display: none;">
                            <h2 class="text-platinum">Site Settings</h2>
                            <p>Global site configuration panel coming soon...</p>
                        </div>

                        <div class="admin-section" id="content-manager" style="display: none;">
                            <h2 class="text-platinum">Content Manager</h2>
                            <p>Advanced content management system coming soon...</p>
                        </div>

                        <div class="admin-section" id="file-manager" style="display: none;">
                            <h2 class="text-platinum">File Manager</h2>
                            <p>Comprehensive file management system coming soon...</p>
                        </div>

                        <div class="admin-section" id="analytics" style="display: none;">
                            <h2 class="text-platinum">Analytics</h2>
                            <p>Real-time analytics dashboard coming soon...</p>
                        </div>

                        <div class="admin-section" id="security" style="display: none;">
                            <h2 class="text-platinum">Security</h2>
                            <p>Advanced security monitoring panel coming soon...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

            <!-- Mobile Navigation Toggle -->
            <button class="mobile-nav-toggle" id="mobileNavToggle" onclick="toggleMobileNav()">
                <div class="hamburger-line"></div>
                <div class="hamburger-line"></div>
                <div class="hamburger-line"></div>
            </button>

            <div class="nav-actions">
                <button class="cart-btn" onclick="openCart()">
                    🛒 <span id="cartCount">0</span>
                </button>
                <button class="voice-trigger-nav" onclick="toggleVoiceCommand()">🎤</button>
            </div>
        </div>
    </nav>

    <!-- Mobile Navigation Menu -->
    <div class="mobile-nav-overlay" id="mobileNavOverlay" onclick="closeMobileNav()"></div>
    <div class="mobile-nav-menu" id="mobileNavMenu">
        <div class="mobile-nav-item">
            <a href="<?php echo home_url(); ?>">🏠 Home</a>
        </div>
        <div class="mobile-nav-item">
            <a href="<?php echo home_url('/shop'); ?>">🛒 Shop</a>
        </div>
        <div class="mobile-nav-item">
            <a href="<?php echo home_url('/streaming'); ?>">🎮 Streaming</a>
        </div>
        <div class="mobile-nav-item">
            <a href="<?php echo home_url('/music'); ?>">🎵 Music</a>
        </div>
        <div class="mobile-nav-item">
            <a href="<?php echo home_url('/ai-tools'); ?>">🧠 AI Tools</a>
        </div>
        <div class="mobile-nav-item">
            <a href="<?php echo home_url('/create-avatar'); ?>">👤 Avatar</a>
        </div>
        <div class="mobile-nav-item">
            <a href="#" onclick="openAdminHub(); closeMobileNav();">⚡ Admin</a>
        </div>
    </div>

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

        // Mobile Navigation Functions
        function toggleMobileNav() {
            const toggle = document.getElementById('mobileNavToggle');
            const menu = document.getElementById('mobileNavMenu');
            const overlay = document.getElementById('mobileNavOverlay');
            
            const isActive = toggle.classList.contains('active');
            
            if (isActive) {
                closeMobileNav();
            } else {
                openMobileNav();
            }
        }

        function openMobileNav() {
            const toggle = document.getElementById('mobileNavToggle');
            const menu = document.getElementById('mobileNavMenu');
            const overlay = document.getElementById('mobileNavOverlay');
            
            toggle.classList.add('active');
            menu.classList.add('active');
            overlay.classList.add('active');
            
            // Prevent body scroll when menu is open
            document.body.style.overflow = 'hidden';
        }

        function closeMobileNav() {
            const toggle = document.getElementById('mobileNavToggle');
            const menu = document.getElementById('mobileNavMenu');
            const overlay = document.getElementById('mobileNavOverlay');
            
            toggle.classList.remove('active');
            menu.classList.remove('active');
            overlay.classList.remove('active');
            
            // Restore body scroll
            document.body.style.overflow = '';
        }

        // Close mobile nav when clicking on menu items
        document.addEventListener('DOMContentLoaded', function() {
            const menuItems = document.querySelectorAll('.mobile-nav-item a');
            menuItems.forEach(item => {
                item.addEventListener('click', () => {
                    setTimeout(closeMobileNav, 200); // Small delay for smooth UX
                });
            });
        });
    </script>