/**
 * YouTuneAI Main JavaScript
 * Handles all interactive features, voice commands, and AI integration
 * 
 * Copyright (c) 2025 Boss Man J (3000Studios)
 * All rights reserved. Proprietary voice-controlled AI website technology.
 * 
 * This JavaScript contains proprietary algorithms for:
 * - Client-side voice recognition and processing
 * - Real-time AI command interface
 * - Dynamic website interaction and modification
 * - Voice-to-action automation system
 * 
 * COMMERCIAL USE REQUIRES PAID LICENSE - Contact: mr.jwswain@gmail.com
 * Unauthorized use subject to legal action and monetary damages.
 */

(function($) {
    'use strict';

    // Initialize everything when DOM is ready
    jQuery(document).ready(function() {
        initializeYouTuneAI();
    });

    function initializeYouTuneAI() {
        // Initialize AOS animations
        if (typeof AOS !== 'undefined') {
            AOS.init({
                duration: 1000,
                once: true,
                offset: 100
            });
        }

        // Initialize voice recognition
        initializeVoiceRecognition();
        
        // Initialize background music
        initializeBackgroundMusic();
        
        // Initialize smooth scrolling
        initializeSmoothScrolling();
        
        // Initialize loading screen
        hideLoadingScreen();
        
        // Initialize click sounds
        initializeClickSounds();
        
        // Initialize dynamic features
        initializeDynamicFeatures();
        
        console.log('🚀 YouTuneAI initialized successfully!');
    }

    // 🎤 VOICE RECOGNITION SYSTEM
    let recognition;
    let isListening = false;

    function initializeVoiceRecognition() {
        if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            recognition = new SpeechRecognition();
            
            recognition.continuous = false;
            recognition.interimResults = false;
            recognition.lang = 'en-US';
            
            recognition.onstart = function() {
                isListening = true;
                $('#voiceBtn').addClass('listening');
                showNotification('🎤 Listening for your command...', 'info');
            };
            
            recognition.onresult = function(event) {
                const command = event.results[0][0].transcript;
                $('#commandInput').val(command);
                showNotification(`Heard: "${command}"`, 'success');
                
                // Auto-execute if admin hub is open
                if ($('#adminHub').hasClass('active')) {
                    executeCommand();
                } else {
                    openAdminHub();
                    setTimeout(() => executeCommand(), 500);
                }
            };
            
            recognition.onerror = function(event) {
                showNotification('Voice recognition error: ' + event.error, 'error');
                stopVoiceRecognition();
            };
            
            recognition.onend = function() {
                stopVoiceRecognition();
            };
        }
    }

    window.toggleVoiceCommand = function() {
        if (!isListening) {
            startVoiceRecognition();
        } else {
            stopVoiceRecognition();
        }
    };

    function startVoiceRecognition() {
        if (recognition) {
            try {
                recognition.start();
            } catch (e) {
                showNotification('Could not start voice recognition', 'error');
            }
        } else {
            showNotification('Voice recognition not supported in this browser', 'error');
        }
    }

    function stopVoiceRecognition() {
        isListening = false;
        $('#voiceBtn').removeClass('listening');
        if (recognition) {
            recognition.stop();
        }
    }

    // 🧠 AI COMMAND EXECUTION
    window.executeCommand = function() {
        const command = $('#commandInput').val().trim();
        const statusDiv = $('#commandStatus');
        
        if (!command) {
            statusDiv.html('<p style="color: #ff4444;">Please enter a command</p>');
            return;
        }
        
        statusDiv.html('<p style="color: #00d4ff;">🧠 Processing your command...</p>');
        
        // Add loading animation
        statusDiv.append('<div class="command-loading"></div>');
        
        $.ajax({
            url: youtuneai_ajax.ajax_url,
            method: 'POST',
            data: {
                action: 'process_ai_command',
                command: command,
                nonce: youtuneai_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    statusDiv.html(`<p style="color: #00ff00;">✅ ${response.data.message}</p>`);
                    showNotification(response.data.message, 'success');
                    
                    // Clear command input
                    $('#commandInput').val('');
                    
                    // Reload page if needed
                    if (response.data.reload) {
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    }
                } else {
                    statusDiv.html(`<p style="color: #ff4444;">❌ ${response.data}</p>`);
                    showNotification(response.data, 'error');
                }
            },
            error: function() {
                statusDiv.html('<p style="color: #ff4444;">❌ Network error. Please try again.</p>');
                showNotification('Network error occurred', 'error');
            }
        });
    };

    // Allow Enter key to execute command
    $(document).on('keypress', '#commandInput', function(e) {
        if (e.which === 13) { // Enter key
            executeCommand();
        }
    });

    // 🎵 BACKGROUND MUSIC SYSTEM
    let currentTrack = 0;
    let musicTracks = [];
    let audioElement;

    function initializeBackgroundMusic() {
        // Music playlist (these would be actual files in production)
        musicTracks = [
            youtuneai_ajax.site_url + '/wp-content/themes/youtuneai/assets/audio/track1.mp3',
            youtuneai_ajax.site_url + '/wp-content/themes/youtuneai/assets/audio/track2.mp3',
            youtuneai_ajax.site_url + '/wp-content/themes/youtuneai/assets/audio/track3.mp3'
        ];

        audioElement = document.getElementById('backgroundMusic');
        
        if (audioElement) {
            audioElement.volume = 0.3;
            
            // Auto-play on first user interaction
            $(document).one('click', function() {
                playBackgroundMusic();
            });

            // Handle track ended
            audioElement.addEventListener('ended', function() {
                nextTrack();
            });
        }
    }

    function playBackgroundMusic() {
        if (audioElement && musicTracks.length > 0) {
            audioElement.src = musicTracks[currentTrack];
            audioElement.play().catch(e => console.log('Music autoplay prevented'));
        }
    }

    function nextTrack() {
        currentTrack = (currentTrack + 1) % musicTracks.length;
        playBackgroundMusic();
    }

    // 🔊 CLICK SOUNDS
    function initializeClickSounds() {
        $(document).on('click', 'button, a, .clickable', function(e) {
            playClickSound();
        });
    }

    function playClickSound() {
        const clickSound = document.getElementById('clickSound');
        if (clickSound) {
            clickSound.currentTime = 0;
            clickSound.volume = 0.2;
            clickSound.play().catch(e => console.log('Click sound failed'));
        }
    }

    // 📱 NOTIFICATION SYSTEM
    function showNotification(message, type = 'info') {
        const notification = $(`
            <div class="youtuneai-notification ${type}">
                <div class="notification-content">
                    <span class="notification-message">${message}</span>
                    <button class="notification-close">&times;</button>
                </div>
            </div>
        `);

        $('body').append(notification);

        // Animate in
        setTimeout(() => notification.addClass('show'), 100);

        // Auto-remove after 5 seconds
        setTimeout(() => {
            notification.removeClass('show');
            setTimeout(() => notification.remove(), 300);
        }, 5000);

        // Manual close
        notification.find('.notification-close').click(function() {
            notification.removeClass('show');
            setTimeout(() => notification.remove(), 300);
        });
    }

    // 🖱️ SMOOTH SCROLLING
    function initializeSmoothScrolling() {
        $(document).on('click', 'a[href^="#"]', function(e) {
            e.preventDefault();
            
            const target = $(this.getAttribute('href'));
            
            if (target.length) {
                $('html, body').animate({
                    scrollTop: target.offset().top - 100
                }, 1000, 'easeInOutCubic');
            }
        });
    }

    // 🔄 AVATAR CAROUSEL
    window.startAvatarCarousel = function() {
        const avatars = $('.avatar-item');
        let currentIndex = 0;
        
        if (avatars.length === 0) return;

        setInterval(function() {
            avatars.removeClass('active');
            currentIndex = (currentIndex + 1) % avatars.length;
            avatars.eq(currentIndex).addClass('active');
        }, 3000);
    };

    // 📋 MODAL MANAGEMENT
    window.openAdminHub = function() {
        $('#adminHub').addClass('active');
        $('#commandInput').focus();
    };

    window.closeAdminHub = function() {
        $('#adminHub').removeClass('active');
    };

    window.openProductModal = function(productId) {
        // Product data would normally come from server
        const productData = getProductData(productId);
        
        if (productData) {
            populateProductModal(productData);
            $('#productModal').show();
        }
    };

    window.closeProductModal = function() {
        $('#productModal').hide();
    };

    function getProductData(productId) {
        const products = {
            'ai-avatars': {
                title: 'AI Avatar Collection',
                description: 'Premium animated avatars perfect for streaming and content creation.',
                price: 29.99,
                features: ['10 Unique Avatars', 'Facial Animation', 'Voice Sync', 'Custom Colors'],
                image: youtuneai_ajax.site_url + '/wp-content/themes/youtuneai/assets/images/avatar-pack.jpg'
            },
            'stream-overlays': {
                title: 'Stream Overlay Pack',
                description: 'Professional gaming overlays to enhance your streams.',
                price: 19.99,
                features: ['15 Overlay Designs', 'Animated Elements', 'OBS Compatible', 'HD Quality'],
                image: youtuneai_ajax.site_url + '/wp-content/themes/youtuneai/assets/images/overlay-pack.jpg'
            },
            'music-packs': {
                title: 'Royalty-Free Music',
                description: 'High-quality background music for all your content.',
                price: 39.99,
                features: ['50 Music Tracks', 'Multiple Genres', 'Commercial License', 'Loop Ready'],
                image: youtuneai_ajax.site_url + '/wp-content/themes/youtuneai/assets/images/music-pack.jpg'
            }
        };
        
        return products[productId] || null;
    }

    function populateProductModal(product) {
        $('#modalTitle').text(product.title);
        $('#modalBody').html(`
            <div class="product-image">
                <img src="${product.image}" alt="${product.title}" style="width: 100%; margin-bottom: 20px; border-radius: 10px;">
            </div>
            <p>${product.description}</p>
            <h4>Features:</h4>
            <ul>
                ${product.features.map(feature => `<li>${feature}</li>`).join('')}
            </ul>
            <div class="price-display">$${product.price}</div>
        `);
        
        $('#buyBtn').text(`Add to Cart - $${product.price}`);
        $('#buyBtn').off('click').on('click', function() {
            addToCart(product);
        });
    }

    // 🛒 SHOPPING CART
    let cart = JSON.parse(localStorage.getItem('youtuneai_cart') || '[]');

    function addToCart(product) {
        cart.push({
            id: Date.now(),
            title: product.title,
            price: product.price,
            quantity: 1
        });
        
        localStorage.setItem('youtuneai_cart', JSON.stringify(cart));
        updateCartCount();
        showNotification(`${product.title} added to cart!`, 'success');
        closeProductModal();
    }

    function updateCartCount() {
        $('#cartCount').text(cart.length);
    }

    window.openCart = function() {
        if (cart.length === 0) {
            showNotification('Your cart is empty', 'info');
            return;
        }
        
        // This would open a cart modal
        showNotification('Cart feature coming soon!', 'info');
    };

    // 🎬 LOADING SCREEN
    function hideLoadingScreen() {
        setTimeout(() => {
            $('#loadingScreen').addClass('hidden');
        }, 2000);
    }

    // 🚀 NAVIGATION FUNCTIONS
    window.scrollToSection = function(sectionId) {
        const target = $('#' + sectionId);
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top - 100
            }, 1000);
        }
    };

    window.goToStreaming = function() {
        window.location.href = youtuneai_ajax.site_url + '/streaming';
    };

    window.goToMusic = function() {
        window.location.href = youtuneai_ajax.site_url + '/music';
    };

    window.goToTutorials = function() {
        window.location.href = youtuneai_ajax.site_url + '/tutorials';
    };

    window.goToAvatarCreator = function() {
        window.location.href = youtuneai_ajax.site_url + '/create-avatar';
    };

    // 🎨 DYNAMIC FEATURES
    function initializeDynamicFeatures() {
        // Update cart count on load
        updateCartCount();
        
        // Initialize tooltips
        initializeTooltips();
        
        // Initialize particle effects
        initializeParticleEffects();
        
        // Initialize hover animations
        initializeHoverAnimations();
    }

    function initializeTooltips() {
        $('[data-tooltip]').hover(
            function() {
                const tooltip = $('<div class="tooltip">' + $(this).data('tooltip') + '</div>');
                $('body').append(tooltip);
                
                const pos = $(this).offset();
                tooltip.css({
                    top: pos.top - tooltip.outerHeight() - 5,
                    left: pos.left + ($(this).outerWidth() / 2) - (tooltip.outerWidth() / 2)
                }).fadeIn(200);
            },
            function() {
                $('.tooltip').fadeOut(200, function() {
                    $(this).remove();
                });
            }
        );
    }

    function initializeParticleEffects() {
        // Add subtle particle animation to hero section
        const hero = $('.hero-section');
        if (hero.length) {
            for (let i = 0; i < 50; i++) {
                const particle = $('<div class="particle"></div>');
                particle.css({
                    left: Math.random() * 100 + '%',
                    animationDelay: Math.random() * 10 + 's',
                    animationDuration: (Math.random() * 20 + 10) + 's'
                });
                hero.append(particle);
            }
        }
    }

    function initializeHoverAnimations() {
        $('.gallery-item, .stream-card').hover(
            function() {
                $(this).find('img').css('transform', 'scale(1.1)');
            },
            function() {
                $(this).find('img').css('transform', 'scale(1)');
            }
        );
    }

    // 📱 MOBILE RESPONSIVENESS
    function handleMobileFeatures() {
        if (window.innerWidth <= 768) {
            // Adjust features for mobile
            $('.nav-links').addClass('mobile-nav');
            $('.voice-command-btn').css('bottom', '80px');
        }
    }

    $(window).resize(handleMobileFeatures);
    handleMobileFeatures(); // Initial call

})(jQuery);
