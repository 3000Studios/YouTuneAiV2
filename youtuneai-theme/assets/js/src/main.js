/**
 * YouTuneAI Main JavaScript
 * 
 * @package YouTuneAI
 */

(function($) {
    'use strict';

    // Initialize when DOM is ready
    $(document).ready(function() {
        YouTuneAI.init();
    });

    // Main YouTuneAI object
    window.YouTuneAI = {
        // Configuration
        config: {
            apiUrl: youtuneai_ajax?.ajax_url || '/wp-admin/admin-ajax.php',
            nonce: youtuneai_ajax?.nonce || '',
            themeUrl: youtuneai_ajax?.theme_url || '',
        },

        // Initialize all components
        init: function() {
            this.initNavigation();
            this.initScrollEffects();
            this.initAnimations();
            this.initModals();
            this.initPerformanceTracking();
            this.initAccessibility();
            
            console.log('YouTuneAI theme initialized');
        },

        // Navigation functionality
        initNavigation: function() {
            // Mobile menu toggle
            $('.mobile-menu-toggle').on('click', function() {
                $('.mobile-menu').toggleClass('active');
                $(this).toggleClass('active');
            });

            // Smooth scrolling for anchor links
            $('a[href^="#"]').on('click', function(e) {
                const target = $(this.getAttribute('href'));
                if (target.length) {
                    e.preventDefault();
                    $('html, body').animate({
                        scrollTop: target.offset().top - 80
                    }, 800);
                }
            });

            // Header scroll effects
            let lastScrollTop = 0;
            $(window).scroll(function() {
                const currentScroll = $(this).scrollTop();
                
                if (currentScroll > 100) {
                    $('header').addClass('scrolled');
                } else {
                    $('header').removeClass('scrolled');
                }

                // Hide/show header on scroll
                if (currentScroll > lastScrollTop && currentScroll > 200) {
                    $('header').addClass('hide');
                } else {
                    $('header').removeClass('hide');
                }
                
                lastScrollTop = currentScroll;
            });
        },

        // Scroll-triggered animations
        initScrollEffects: function() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-in');
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });

            // Observe all elements with animation classes
            document.querySelectorAll('.animate-fade-up, .animate-fade-in, .animate-slide-in').forEach(el => {
                observer.observe(el);
            });
        },

        // Animation utilities
        initAnimations: function() {
            // Floating elements
            $('.floating-element').each(function(index) {
                const element = $(this);
                const delay = index * 200;
                
                setTimeout(() => {
                    element.addClass('animate-float');
                }, delay);
            });

            // Glowing text effects
            $('.glow-text').each(function() {
                $(this).on('mouseenter', function() {
                    $(this).addClass('animate-glow');
                }).on('mouseleave', function() {
                    $(this).removeClass('animate-glow');
                });
            });

            // Parallax scrolling
            $(window).scroll(function() {
                const scrolled = $(this).scrollTop();
                $('.parallax').each(function() {
                    const rate = scrolled * -0.5;
                    $(this).css('transform', `translate3d(0, ${rate}px, 0)`);
                });
            });
        },

        // Modal functionality
        initModals: function() {
            // Open modal
            $('[data-modal-open]').on('click', function(e) {
                e.preventDefault();
                const modalId = $(this).data('modal-open');
                YouTuneAI.openModal(modalId);
            });

            // Close modal
            $('[data-modal-close]').on('click', function() {
                YouTuneAI.closeModal();
            });

            // Close on overlay click
            $('.modal-overlay').on('click', function(e) {
                if (e.target === this) {
                    YouTuneAI.closeModal();
                }
            });

            // Close on ESC key
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape') {
                    YouTuneAI.closeModal();
                }
            });
        },

        // Open modal
        openModal: function(modalId) {
            const modal = $('#' + modalId);
            if (modal.length) {
                modal.addClass('active');
                $('body').addClass('modal-open');
                
                // Focus first focusable element
                setTimeout(() => {
                    modal.find('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])').first().focus();
                }, 100);
            }
        },

        // Close modal
        closeModal: function() {
            $('.modal.active').removeClass('active');
            $('body').removeClass('modal-open');
        },

        // Performance tracking
        initPerformanceTracking: function() {
            // Track page load time
            window.addEventListener('load', function() {
                setTimeout(() => {
                    const loadTime = performance.timing.loadEventEnd - performance.timing.navigationStart;
                    YouTuneAI.trackMetric('page_load_time', loadTime);
                }, 0);
            });

            // Track Core Web Vitals
            if ('web-vital' in window) {
                // This would integrate with web-vitals library
                // getCLS(YouTuneAI.trackMetric.bind(null, 'cls'));
                // getFCP(YouTuneAI.trackMetric.bind(null, 'fcp'));
                // getLCP(YouTuneAI.trackMetric.bind(null, 'lcp'));
            }
        },

        // Track performance metric
        trackMetric: function(name, value) {
            if (!this.config.nonce) return;

            $.ajax({
                url: this.config.apiUrl,
                type: 'POST',
                data: {
                    action: 'youtuneai_track_metric',
                    metric: name,
                    value: value,
                    nonce: this.config.nonce
                }
            });
        },

        // Accessibility enhancements
        initAccessibility: function() {
            // Skip to content link
            $('body').prepend('<a href="#main" class="skip-to-content">Skip to main content</a>');

            // Focus management for dynamic content
            $(document).on('show.bs.tab', '[data-toggle="tab"]', function() {
                setTimeout(() => {
                    $($(this).attr('href')).focus();
                }, 150);
            });

            // Enhanced keyboard navigation
            $('.btn, a, button').on('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    if (e.key === ' ') e.preventDefault();
                    $(this).trigger('click');
                }
            });
        },

        // Utility functions
        utils: {
            // Debounce function
            debounce: function(func, wait, immediate) {
                let timeout;
                return function executedFunction() {
                    const context = this;
                    const args = arguments;
                    const later = function() {
                        timeout = null;
                        if (!immediate) func.apply(context, args);
                    };
                    const callNow = immediate && !timeout;
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                    if (callNow) func.apply(context, args);
                };
            },

            // Throttle function
            throttle: function(func, limit) {
                let inThrottle;
                return function() {
                    const args = arguments;
                    const context = this;
                    if (!inThrottle) {
                        func.apply(context, args);
                        inThrottle = true;
                        setTimeout(() => inThrottle = false, limit);
                    }
                };
            },

            // Check if element is in viewport
            isInViewport: function(element) {
                const rect = element.getBoundingClientRect();
                return (
                    rect.top >= 0 &&
                    rect.left >= 0 &&
                    rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                    rect.right <= (window.innerWidth || document.documentElement.clientWidth)
                );
            },

            // Format bytes
            formatBytes: function(bytes, decimals = 2) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const dm = decimals < 0 ? 0 : decimals;
                const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
            }
        },

        // API helper functions
        api: {
            // Generic API call
            call: function(endpoint, data = {}, method = 'GET') {
                return $.ajax({
                    url: YouTuneAI.config.apiUrl + endpoint,
                    type: method,
                    data: data,
                    headers: {
                        'X-WP-Nonce': YouTuneAI.config.nonce
                    }
                });
            },

            // Get games
            getGames: function() {
                return this.call('youtuneai/v1/games');
            },

            // Get live streams
            getLiveStreams: function() {
                return this.call('youtuneai/v1/streams/live');
            },

            // Get VR rooms
            getVRRooms: function() {
                return this.call('youtuneai/v1/vr/rooms');
            }
        }
    };

    // Global utility functions
    window.youtuneai_utils = YouTuneAI.utils;

})(jQuery);