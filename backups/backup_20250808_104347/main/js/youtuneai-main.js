/**
 * YouTuneAI Pro - Main JavaScript File
 * Handles all interactive features, voice control, animations, and AI integration
 */

class YouTuneAI {
  constructor() {
    this.voiceRecognition = null;
    this.isListening = false;
    this.backgroundMusic = null;
    this.currentTheme = 'space';
    this.apiKey = null;

    this.init();
  }

  init() {
    document.addEventListener('DOMContentLoaded', () => {
      this.initializeComponents();
      this.setupEventListeners();
      this.startBackgroundSystems();
      this.loadingScreen();
    });
  }

  initializeComponents() {
    // Initialize all major components
    this.initVoiceControl();
    this.initBackgroundMusic();
    this.initScrollAnimations();
    this.initGalleryHovers();
    this.initBlackHoleNav();
    this.initLoadingScreen();
    this.initSalesPopups();
    this.initPaymentSystems();
  }

  setupEventListeners() {
    // Admin hub trigger
    const adminTrigger = document.querySelector('.admin-trigger');
    if (adminTrigger) {
      adminTrigger.addEventListener('click', this.openAdminHub.bind(this));
    }

    // Voice toggle button
    const voiceToggle = document.getElementById('voiceToggle');
    if (voiceToggle) {
      voiceToggle.addEventListener('click', this.toggleVoiceControl.bind(this));
    }

    // Click sounds
    document.addEventListener('click', this.playClickSound.bind(this));

    // Scroll sync for video
    window.addEventListener('scroll', this.syncVideoWithScroll.bind(this));

    // Payment buttons
    this.initPaymentButtons();
  }

  initVoiceControl() {
    if ('webkitSpeechRecognition' in window) {
      this.voiceRecognition = new webkitSpeechRecognition();
      this.voiceRecognition.continuous = true;
      this.voiceRecognition.interimResults = true;
      this.voiceRecognition.lang = 'en-US';

      this.voiceRecognition.onresult = event => {
        const lastResult = event.results[event.results.length - 1];
        if (lastResult.isFinal) {
          const command = lastResult[0].transcript.toLowerCase().trim();
          this.processVoiceCommand(command);
        }
      };

      this.voiceRecognition.onerror = error => {
        console.error('Voice recognition error:', error);
        this.showNotification('Voice recognition error. Please try again.', 'error');
      };

      this.voiceRecognition.onend = () => {
        if (this.isListening) {
          this.voiceRecognition.start(); // Restart if still supposed to be listening
        }
      };
    } else {
      console.warn('Speech recognition not supported');
      this.showNotification('Voice control not supported in this browser', 'warning');
    }
  }

  toggleVoiceControl() {
    if (!this.voiceRecognition) return;

    if (this.isListening) {
      this.voiceRecognition.stop();
      this.isListening = false;
      this.updateVoiceStatus('Voice control stopped');
      this.playClickSound();
    } else {
      this.voiceRecognition.start();
      this.isListening = true;
      this.updateVoiceStatus('Listening for commands...');
      this.showNotification(
        'Voice control activated! Try saying "add product" or "change background"',
        'success'
      );
    }
  }

  processVoiceCommand(command) {
    console.log('Processing command:', command);
    this.updateVoiceStatus(`Processing: "${command}"`);

    // Send command to WordPress AI controller
    this.sendAICommand(command)
      .then(response => {
        if (response.success) {
          this.showNotification(response.message, 'success');
          this.handleCommandSuccess(command, response);
        } else {
          this.showNotification(response.error || 'Command failed', 'error');
        }
      })
      .catch(error => {
        console.error('Command processing error:', error);
        this.showNotification('Failed to process command', 'error');
      });
  }

  async sendAICommand(command) {
    try {
      const response = await fetch('/wp-json/youtuneai/v1/command', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Authorization: 'Basic ' + btoa('Mr.jwswain@gmail.com:pppp'),
        },
        body: JSON.stringify({ command: command }),
      });

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      return await response.json();
    } catch (error) {
      console.error('API request failed:', error);
      return { success: false, error: 'Failed to connect to AI controller' };
    }
  }

  handleCommandSuccess(command, response) {
    // Handle specific command responses
    if (command.includes('background') && response.video_url) {
      this.changeBackgroundVideo(response.video_url);
    }

    if (command.includes('product') && response.product_id) {
      this.showProductCreated(response.product_id);
    }

    if (command.includes('color') && response.primary_color) {
      this.updateThemeColors(response.primary_color);
    }
  }

  initBackgroundMusic() {
    this.backgroundMusic = document.getElementById('backgroundMusic');
    if (this.backgroundMusic) {
      this.backgroundMusic.volume = 0.3;

      // Auto-play background music (with user interaction)
      document.addEventListener(
        'click',
        () => {
          if (this.backgroundMusic.paused) {
            this.backgroundMusic.play().catch(console.error);
          }
        },
        { once: true }
      );

      // Cycle through different ambient tracks
      this.backgroundMusic.addEventListener('ended', () => {
        this.loadNextAmbientTrack();
      });
    }
  }

  loadNextAmbientTrack() {
    const tracks = [
      'ambient-tech.mp3',
      'cyber-ambience.mp3',
      'space-meditation.mp3',
      'neural-waves.mp3',
    ];

    const currentSrc = this.backgroundMusic.src;
    const currentTrack = currentSrc.split('/').pop();
    const currentIndex = tracks.indexOf(currentTrack);
    const nextIndex = (currentIndex + 1) % tracks.length;

    this.backgroundMusic.src = `/wp-content/themes/youtuneai/assets/music/${tracks[nextIndex]}`;
    this.backgroundMusic.play().catch(console.error);
  }

  initScrollAnimations() {
    // GSAP scroll animations
    if (typeof gsap !== 'undefined') {
      gsap.registerPlugin(ScrollTrigger);

      // Fade in animations
      gsap.utils.toArray('.fade-in').forEach(element => {
        gsap.fromTo(
          element,
          { opacity: 0, y: 50 },
          {
            opacity: 1,
            y: 0,
            duration: 1,
            ease: 'power2.out',
            scrollTrigger: {
              trigger: element,
              start: 'top 80%',
              end: 'bottom 20%',
              toggleActions: 'play none none reverse',
            },
          }
        );
      });

      // Gallery item animations
      gsap.utils.toArray('.gallery-item').forEach((item, index) => {
        gsap.fromTo(
          item,
          { opacity: 0, scale: 0.8, rotationY: 45 },
          {
            opacity: 1,
            scale: 1,
            rotationY: 0,
            duration: 0.8,
            delay: index * 0.1,
            ease: 'back.out(1.7)',
            scrollTrigger: {
              trigger: item,
              start: 'top 85%',
            },
          }
        );
      });
    }
  }

  initGalleryHovers() {
    const galleryItems = document.querySelectorAll('.gallery-item');

    galleryItems.forEach(item => {
      item.addEventListener('mouseenter', () => {
        this.playHoverSound();
        item.style.transform = 'translateY(-10px) scale(1.02)';
        item.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
      });

      item.addEventListener('mouseleave', () => {
        item.style.transform = 'translateY(0) scale(1)';
      });
    });
  }

  initBlackHoleNav() {
    const navContainer = document.querySelector('.nav-black-hole');
    if (!navContainer) return;

    // Create black hole canvas
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    navContainer.appendChild(canvas);

    const resizeCanvas = () => {
      canvas.width = navContainer.offsetWidth;
      canvas.height = navContainer.offsetHeight;
    };

    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);

    // Black hole animation
    let particles = [];
    const particleCount = 50;

    for (let i = 0; i < particleCount; i++) {
      particles.push({
        x: Math.random() * canvas.width,
        y: Math.random() * canvas.height,
        size: Math.random() * 2 + 1,
        speed: Math.random() * 0.5 + 0.1,
        angle: Math.random() * Math.PI * 2,
      });
    }

    const animate = () => {
      ctx.clearRect(0, 0, canvas.width, canvas.height);

      const centerX = canvas.width / 2;
      const centerY = canvas.height / 2;

      particles.forEach(particle => {
        // Move particle in spiral toward center
        const dx = centerX - particle.x;
        const dy = centerY - particle.y;
        const distance = Math.sqrt(dx * dx + dy * dy);

        if (distance > 5) {
          particle.x += (dx / distance) * particle.speed;
          particle.y += (dy / distance) * particle.speed;
        } else {
          // Reset particle to edge
          particle.x = Math.random() * canvas.width;
          particle.y = Math.random() * canvas.height;
        }

        // Draw particle
        ctx.beginPath();
        ctx.arc(particle.x, particle.y, particle.size, 0, Math.PI * 2);
        ctx.fillStyle = `rgba(157, 0, 255, ${1 - distance / 200})`;
        ctx.fill();
      });

      requestAnimationFrame(animate);
    };

    animate();
  }

  initLoadingScreen() {
    const loadingScreen = document.getElementById('loadingScreen');
    if (loadingScreen) {
      // Animate AI bot
      this.animateAIBot();

      // Hide loading screen after delay
      setTimeout(() => {
        loadingScreen.classList.add('hidden');
      }, 3000);
    }
  }

  animateAIBot() {
    const aiBot = document.querySelector('.ai-bot');
    if (!aiBot) return;

    // CSS animation for the AI bot
    const style = document.createElement('style');
    style.textContent = `
            .ai-bot {
                animation: aiPulse 2s ease-in-out infinite;
            }

            @keyframes aiPulse {
                0%, 100% { transform: scale(1) rotate(0deg); }
                50% { transform: scale(1.1) rotate(5deg); }
            }

            .ai-bot .eyes {
                width: 20px;
                height: 20px;
                background: var(--cyber-green);
                border-radius: 50%;
                animation: eyeBlink 3s infinite;
            }

            @keyframes eyeBlink {
                0%, 90%, 100% { height: 20px; }
                95% { height: 2px; }
            }
        `;
    document.head.appendChild(style);
  }

  initSalesPopups() {
    // Show sales popup periodically
    setTimeout(() => {
      this.showSalesPopup();
    }, 30000); // Show after 30 seconds

    // Show popup when user tries to leave
    document.addEventListener('mouseleave', () => {
      this.showSalesPopup();
    });
  }

  showSalesPopup() {
    const popup = document.getElementById('salesPopup');
    if (popup && popup.classList.contains('hidden')) {
      popup.classList.remove('hidden');
      this.animatePopupEntrance(popup);
    }
  }

  animatePopupEntrance(popup) {
    if (typeof gsap !== 'undefined') {
      gsap.fromTo(
        popup,
        { opacity: 0, scale: 0.5, rotationY: 90 },
        { opacity: 1, scale: 1, rotationY: 0, duration: 0.8, ease: 'back.out(1.7)' }
      );
    }
  }

  closeSalesPopup() {
    const popup = document.getElementById('salesPopup');
    if (popup) {
      popup.classList.add('hidden');
    }
  }

  initPaymentSystems() {
    // Initialize PayPal
    this.initPayPal();

    // Initialize Cash App
    this.initCashApp();

    // Initialize Crypto payments
    this.initCrypto();
  }

  initPayPal() {
    // PayPal integration
    if (typeof paypal !== 'undefined') {
      paypal
        .Buttons({
          createOrder: (data, actions) => {
            return actions.order.create({
              purchase_units: [
                {
                  amount: {
                    value: '39.99',
                  },
                },
              ],
            });
          },
          onApprove: (data, actions) => {
            return actions.order.capture().then(details => {
              this.handlePaymentSuccess('paypal', details);
            });
          },
        })
        .render('#paypal-button-container');
    }
  }

  initCashApp() {
    // Cash App Pay integration
    const cashAppButton = document.querySelector('.cashapp-btn');
    if (cashAppButton) {
      cashAppButton.addEventListener('click', () => {
        // Redirect to Cash App payment
        window.open('https://cash.app/$youtuneai', '_blank');
      });
    }
  }

  initCrypto() {
    // Basic crypto payment setup
    const cryptoButton = document.querySelector('.crypto-btn');
    if (cryptoButton) {
      cryptoButton.addEventListener('click', () => {
        this.showCryptoPayment();
      });
    }
  }

  initPaymentButtons() {
    // Payment method buttons
    document.addEventListener('click', e => {
      if (e.target.matches('.paypal-btn')) {
        this.processPayPalPayment();
      } else if (e.target.matches('.cashapp-btn')) {
        this.processCashAppPayment();
      } else if (e.target.matches('.crypto-btn')) {
        this.processCryptoPayment();
      }
    });
  }

  processPayPalPayment() {
    this.showNotification('Redirecting to PayPal...', 'info');
    // PayPal payment processing
    window.open('https://paypal.me/youtuneai', '_blank');
  }

  processCashAppPayment() {
    this.showNotification('Opening Cash App...', 'info');
    // Cash App payment processing
    window.open('https://cash.app/$youtuneai', '_blank');
  }

  processCryptoPayment() {
    this.showNotification('Crypto payment options coming soon!', 'info');
    // Crypto payment modal
    this.showCryptoModal();
  }

  showCryptoModal() {
    const modal = document.createElement('div');
    modal.className = 'crypto-modal';
    modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h3>💰 Crypto Payment</h3>
                    <button class="close-btn" onclick="this.closest('.crypto-modal').remove()">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="crypto-options">
                        <div class="crypto-option">
                            <strong>Bitcoin (BTC)</strong>
                            <code>bc1qxy2kgdygjrsqtzq2n0yrf2493p83kkfjhx0wlh</code>
                        </div>
                        <div class="crypto-option">
                            <strong>Ethereum (ETH)</strong>
                            <code>0x742d35Cc6634C0532925a3b8D9C9C0B4C0F4E8B9</code>
                        </div>
                        <div class="crypto-option">
                            <strong>Dogecoin (DOGE)</strong>
                            <code>DQj9Z9qjqjqjqjqjqjqjqjqjqjqjqjqjqj</code>
                        </div>
                    </div>
                </div>
            </div>
        `;
    document.body.appendChild(modal);
  }

  syncVideoWithScroll() {
    const video = document.querySelector('.background-video');
    if (!video) return;

    const scrollPercent = window.pageYOffset / (document.body.offsetHeight - window.innerHeight);
    const videoDuration = video.duration;

    if (videoDuration) {
      video.currentTime = scrollPercent * videoDuration;
    }
  }

  changeBackgroundVideo(videoUrl) {
    const video = document.querySelector('.background-video');
    if (video) {
      video.src = videoUrl;
      video.load();
      this.showNotification('Background video updated!', 'success');
    }
  }

  updateThemeColors(primaryColor) {
    document.documentElement.style.setProperty('--primary-color', primaryColor);
    this.showNotification('Theme colors updated!', 'success');
  }

  playClickSound() {
    // Create audio context if not exists
    if (!this.audioContext) {
      this.audioContext = new (window.AudioContext || window.webkitAudioContext)();
    }

    // Generate click sound
    const oscillator = this.audioContext.createOscillator();
    const gainNode = this.audioContext.createGain();

    oscillator.connect(gainNode);
    gainNode.connect(this.audioContext.destination);

    oscillator.frequency.setValueAtTime(800, this.audioContext.currentTime);
    gainNode.gain.setValueAtTime(0.1, this.audioContext.currentTime);
    gainNode.gain.exponentialRampToValueAtTime(0.01, this.audioContext.currentTime + 0.1);

    oscillator.start(this.audioContext.currentTime);
    oscillator.stop(this.audioContext.currentTime + 0.1);
  }

  playHoverSound() {
    if (!this.audioContext) {
      this.audioContext = new (window.AudioContext || window.webkitAudioContext)();
    }

    const oscillator = this.audioContext.createOscillator();
    const gainNode = this.audioContext.createGain();

    oscillator.connect(gainNode);
    gainNode.connect(this.audioContext.destination);

    oscillator.frequency.setValueAtTime(1200, this.audioContext.currentTime);
    gainNode.gain.setValueAtTime(0.05, this.audioContext.currentTime);
    gainNode.gain.exponentialRampToValueAtTime(0.01, this.audioContext.currentTime + 0.05);

    oscillator.start(this.audioContext.currentTime);
    oscillator.stop(this.audioContext.currentTime + 0.05);
  }

  showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
            <div class="notification-content">
                <span class="notification-icon">${this.getNotificationIcon(type)}</span>
                <span class="notification-message">${message}</span>
                <button class="notification-close" onclick="this.closest('.notification').remove()">&times;</button>
            </div>
        `;

    // Add styles
    notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10000;
            background: rgba(20, 20, 40, 0.95);
            border: 2px solid var(--primary-color);
            border-radius: 10px;
            padding: 1rem;
            color: white;
            font-family: var(--font-primary);
            backdrop-filter: blur(10px);
            animation: slideInNotification 0.3s ease-out;
            max-width: 400px;
        `;

    document.body.appendChild(notification);

    // Auto-remove after 5 seconds
    setTimeout(() => {
      if (notification.parentNode) {
        notification.remove();
      }
    }, 5000);
  }

  getNotificationIcon(type) {
    const icons = {
      success: '✅',
      error: '❌',
      warning: '⚠️',
      info: 'ℹ️',
    };
    return icons[type] || icons.info;
  }

  updateVoiceStatus(status) {
    const statusElement = document.querySelector('.voice-status');
    if (statusElement) {
      statusElement.textContent = status;
    }
  }

  openAdminHub() {
    window.location.href = '/admin-dashboard/';
  }

  startBackgroundSystems() {
    // Start periodic background updates
    setInterval(() => {
      this.updateDynamicContent();
    }, 30000);

    // Monitor system health
    setInterval(() => {
      this.checkSystemHealth();
    }, 60000);
  }

  updateDynamicContent() {
    // Update viewer counts, like counts, etc.
    const viewerElements = document.querySelectorAll('[id*="Count"]');
    viewerElements.forEach(element => {
      if (element.id.includes('viewer')) {
        const current = parseInt(element.textContent.replace(/,/g, ''));
        const change = Math.floor(Math.random() * 20) - 10;
        const newCount = Math.max(100, current + change);
        element.textContent = newCount.toLocaleString();
      }
    });
  }

  checkSystemHealth() {
    // Check if voice recognition is still working
    if (this.isListening && this.voiceRecognition) {
      // System is healthy
      console.log('✅ YouTuneAI systems operational');
    }
  }

  loadingScreen() {
    // Enhanced loading screen with progress
    const loadingElement = document.getElementById('loadingScreen');
    if (!loadingElement) return;

    let progress = 0;
    const interval = setInterval(() => {
      progress += Math.random() * 15;
      if (progress >= 100) {
        progress = 100;
        clearInterval(interval);
        setTimeout(() => {
          loadingElement.classList.add('hidden');
        }, 500);
      }

      const progressBar = loadingElement.querySelector('.progress-bar');
      if (progressBar) {
        progressBar.style.width = progress + '%';
      }
    }, 200);
  }
}

// Global utility functions
window.toggleVoicePanel = function () {
  const panel = document.getElementById('voicePanel');
  if (panel) {
    panel.classList.toggle('hidden');
  }
};

window.purchasePremium = function () {
  youtuneAI.showNotification('Redirecting to premium checkout...', 'info');
  window.location.href = '/checkout/?plan=premium';
};

window.closeSalesPopup = function () {
  youtuneAI.closeSalesPopup();
};

// Initialize YouTuneAI system
const youtuneAI = new YouTuneAI();

// Add notification styles to head
const notificationStyles = document.createElement('style');
notificationStyles.textContent = `
    @keyframes slideInNotification {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .notification-content {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .notification-close {
        background: none;
        border: none;
        color: white;
        font-size: 1.2rem;
        cursor: pointer;
        margin-left: auto;
    }

    .notification.success {
        border-color: var(--cyber-green);
    }

    .notification.error {
        border-color: #ff4444;
    }

    .notification.warning {
        border-color: #ffaa00;
    }

    .crypto-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.8);
        z-index: 10000;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .crypto-modal .modal-content {
        background: rgba(20, 20, 40, 0.95);
        border: 2px solid var(--primary-color);
        border-radius: 15px;
        padding: 2rem;
        max-width: 500px;
        width: 90%;
    }

    .crypto-option {
        margin: 1rem 0;
        padding: 1rem;
        background: rgba(40, 40, 80, 0.5);
        border-radius: 8px;
    }

    .crypto-option code {
        display: block;
        background: rgba(0, 0, 0, 0.3);
        padding: 0.5rem;
        border-radius: 4px;
        margin-top: 0.5rem;
        font-family: monospace;
        font-size: 0.8rem;
        word-break: break-all;
    }
`;
document.head.appendChild(notificationStyles);
