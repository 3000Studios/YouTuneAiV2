<?php

/**
 * Template Name: Avatar Creator
 * Page for creating custom AI avatars
 */
get_header(); ?>

<!-- Cyberpunk Background Animation -->
<div class="cyber-background-container">
    <canvas id="cyberCanvas"></canvas>
    <div class="data-streams"></div>
    <div class="cyber-overlay"></div>
</div>

<div class="content-wrapper">

    <!-- Avatar Creator Hero -->
    <section class="avatar-hero">
        <div class="hero-content">
            <h1 class="cyber-title">AI Avatar Creator</h1>
            <p class="cyber-subtitle">Design Your Digital Identity • Voice Synthesis • Neural Networks</p>

            <div class="avatar-stats">
                <div class="stat-item">
                    <span class="stat-number">50K+</span>
                    <span class="stat-label">Avatars Created</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">∞</span>
                    <span class="stat-label">Combinations</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">AI</span>
                    <span class="stat-label">Powered</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Avatar Carousel -->
    <section class="avatar-carousel-section">
        <div class="cosmos-background">
            <div class="stars-container"></div>
        </div>

        <div class="carousel-header">
            <h2>Interdimensional Avatar Selection</h2>
            <p>Choose from our collection of AI-generated personas</p>
        </div>

        <div class="carousel-container">
            <div class="carousel" id="avatarCarousel">

                <!-- Avatar Card 1 -->
                <div class="avatar-card" data-avatar-id="1">
                    <div class="card-inner">
                        <div class="card-front">
                            <div class="avatar-preview">
                                <img src="https://images.unsplash.com/photo-1494790108755-2616b612b17c?w=300" alt="Avatar 1">
                            </div>
                            <div class="card-content">
                                <div class="avatar-id">AVATAR: A-137</div>
                                <h3>Cyber Sage</h3>
                                <p>Wise digital entity with neural voice synthesis</p>
                                <div class="avatar-traits">
                                    <span class="trait">🧠 Analytical</span>
                                    <span class="trait">🎭 Expressive</span>
                                    <span class="trait">🔮 Mystical</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-back">
                            <h4>Voice Samples</h4>
                            <div class="voice-samples">
                                <button class="sample-btn" onclick="playVoiceSample('sage-1')">
                                    ▶️ Greeting
                                </button>
                                <button class="sample-btn" onclick="playVoiceSample('sage-2')">
                                    ▶️ Explanation
                                </button>
                                <button class="sample-btn" onclick="playVoiceSample('sage-3')">
                                    ▶️ Casual
                                </button>
                            </div>
                            <button class="select-avatar-btn" onclick="selectAvatar('cyber-sage')">
                                Select Avatar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Avatar Card 2 -->
                <div class="avatar-card" data-avatar-id="2">
                    <div class="card-inner">
                        <div class="card-front">
                            <div class="avatar-preview">
                                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=300" alt="Avatar 2">
                            </div>
                            <div class="card-content">
                                <div class="avatar-id">AVATAR: B-742</div>
                                <h3>Tech Guardian</h3>
                                <p>Protective AI with authoritative voice patterns</p>
                                <div class="avatar-traits">
                                    <span class="trait">🛡️ Protective</span>
                                    <span class="trait">⚡ Energetic</span>
                                    <span class="trait">🎯 Focused</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-back">
                            <h4>Voice Samples</h4>
                            <div class="voice-samples">
                                <button class="sample-btn" onclick="playVoiceSample('guardian-1')">
                                    ▶️ Command
                                </button>
                                <button class="sample-btn" onclick="playVoiceSample('guardian-2')">
                                    ▶️ Warning
                                </button>
                                <button class="sample-btn" onclick="playVoiceSample('guardian-3')">
                                    ▶️ Friendly
                                </button>
                            </div>
                            <button class="select-avatar-btn" onclick="selectAvatar('tech-guardian')">
                                Select Avatar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Avatar Card 3 -->
                <div class="avatar-card" data-avatar-id="3">
                    <div class="card-inner">
                        <div class="card-front">
                            <div class="avatar-preview">
                                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=300" alt="Avatar 3">
                            </div>
                            <div class="card-content">
                                <div class="avatar-id">AVATAR: C-999</div>
                                <h3>Neural Artist</h3>
                                <p>Creative AI with melodic voice synthesis</p>
                                <div class="avatar-traits">
                                    <span class="trait">🎨 Creative</span>
                                    <span class="trait">🎵 Musical</span>
                                    <span class="trait">✨ Inspiring</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-back">
                            <h4>Voice Samples</h4>
                            <div class="voice-samples">
                                <button class="sample-btn" onclick="playVoiceSample('artist-1')">
                                    ▶️ Poetry
                                </button>
                                <button class="sample-btn" onclick="playVoiceSample('artist-2')">
                                    ▶️ Singing
                                </button>
                                <button class="sample-btn" onclick="playVoiceSample('artist-3')">
                                    ▶️ Teaching
                                </button>
                            </div>
                            <button class="select-avatar-btn" onclick="selectAvatar('neural-artist')">
                                Select Avatar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Avatar Card 4 -->
                <div class="avatar-card" data-avatar-id="4">
                    <div class="card-inner">
                        <div class="card-front">
                            <div class="avatar-preview">
                                <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=300" alt="Avatar 4">
                            </div>
                            <div class="card-content">
                                <div class="avatar-id">AVATAR: D-356</div>
                                <h3>Quantum Explorer</h3>
                                <p>Adventurous AI with dynamic voice modulation</p>
                                <div class="avatar-traits">
                                    <span class="trait">🚀 Adventurous</span>
                                    <span class="trait">🔬 Scientific</span>
                                    <span class="trait">🌟 Optimistic</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-back">
                            <h4>Voice Samples</h4>
                            <div class="voice-samples">
                                <button class="sample-btn" onclick="playVoiceSample('explorer-1')">
                                    ▶️ Discovery
                                </button>
                                <button class="sample-btn" onclick="playVoiceSample('explorer-2')">
                                    ▶️ Excitement
                                </button>
                                <button class="sample-btn" onclick="playVoiceSample('explorer-3')">
                                    ▶️ Wonder
                                </button>
                            </div>
                            <button class="select-avatar-btn" onclick="selectAvatar('quantum-explorer')">
                                Select Avatar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Custom Avatar Card -->
                <div class="avatar-card create-custom" data-avatar-id="custom">
                    <div class="card-inner">
                        <div class="card-front">
                            <div class="custom-avatar-icon">
                                <i class='bx bx-plus-circle'></i>
                            </div>
                            <div class="card-content">
                                <div class="avatar-id">CUSTOM</div>
                                <h3>Create Your Own</h3>
                                <p>Design a unique avatar with personalized voice</p>
                                <div class="custom-features">
                                    <span class="feature">🎨 Custom Appearance</span>
                                    <span class="feature">🎤 Voice Training</span>
                                    <span class="feature">🧬 Personality AI</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Carousel Controls -->
            <div class="carousel-controls">
                <button id="prevBtn" class="carousel-btn">
                    <i class='bx bx-chevron-left'></i>
                </button>
                <div class="carousel-indicators" id="indicators"></div>
                <button id="nextBtn" class="carousel-btn">
                    <i class='bx bx-chevron-right'></i>
                </button>
            </div>
        </div>
    </section>

    <!-- Avatar Customization Studio -->
    <section class="customization-studio hidden" id="customStudio">
        <div class="studio-header">
            <h2>🎨 Avatar Customization Studio</h2>
            <p>Fine-tune your digital persona</p>
        </div>

        <div class="studio-workspace">

            <!-- Avatar Preview -->
            <div class="avatar-preview-panel">
                <div class="preview-container">
                    <div class="avatar-display" id="avatarDisplay">
                        <img src="https://images.unsplash.com/photo-1494790108755-2616b612b17c?w=400" alt="Avatar Preview">
                    </div>
                    <div class="preview-controls">
                        <button class="preview-btn" onclick="rotateAvatar()">
                            <i class='bx bx-rotate-right'></i>
                            Rotate
                        </button>
                        <button class="preview-btn" onclick="animateAvatar()">
                            <i class='bx bx-play'></i>
                            Animate
                        </button>
                        <button class="preview-btn" onclick="testVoice()">
                            <i class='bx bx-microphone'></i>
                            Test Voice
                        </button>
                    </div>
                </div>
            </div>

            <!-- Customization Panels -->
            <div class="customization-panels">

                <!-- Appearance Panel -->
                <div class="custom-panel">
                    <h3>👤 Appearance</h3>
                    <div class="option-group">
                        <label>Face Shape</label>
                        <div class="option-buttons">
                            <button class="option-btn active" data-option="oval">Oval</button>
                            <button class="option-btn" data-option="round">Round</button>
                            <button class="option-btn" data-option="square">Square</button>
                        </div>
                    </div>
                    <div class="option-group">
                        <label>Hair Style</label>
                        <div class="option-buttons">
                            <button class="option-btn active" data-option="short">Short</button>
                            <button class="option-btn" data-option="medium">Medium</button>
                            <button class="option-btn" data-option="long">Long</button>
                        </div>
                    </div>
                    <div class="option-group">
                        <label>Eye Color</label>
                        <div class="color-picker">
                            <div class="color-option" style="background: #8B4513" data-color="brown"></div>
                            <div class="color-option" style="background: #0066CC" data-color="blue"></div>
                            <div class="color-option" style="background: #228B22" data-color="green"></div>
                            <div class="color-option" style="background: #9400D3" data-color="violet"></div>
                        </div>
                    </div>
                </div>

                <!-- Voice Panel -->
                <div class="custom-panel">
                    <h3>🎤 Voice Settings</h3>
                    <div class="option-group">
                        <label>Voice Type</label>
                        <div class="option-buttons">
                            <button class="option-btn active" data-voice="natural">Natural</button>
                            <button class="option-btn" data-voice="robotic">Robotic</button>
                            <button class="option-btn" data-voice="ethereal">Ethereal</button>
                        </div>
                    </div>
                    <div class="slider-group">
                        <label>Pitch: <span id="pitchValue">50</span>%</label>
                        <input type="range" class="voice-slider" id="pitchSlider" min="0" max="100" value="50">
                    </div>
                    <div class="slider-group">
                        <label>Speed: <span id="speedValue">50</span>%</label>
                        <input type="range" class="voice-slider" id="speedSlider" min="0" max="100" value="50">
                    </div>
                    <div class="slider-group">
                        <label>Emotion: <span id="emotionValue">50</span>%</label>
                        <input type="range" class="voice-slider" id="emotionSlider" min="0" max="100" value="50">
                    </div>
                </div>

                <!-- Personality Panel -->
                <div class="custom-panel">
                    <h3>🧠 Personality</h3>
                    <div class="personality-traits">
                        <div class="trait-item">
                            <label>Friendliness</label>
                            <input type="range" class="trait-slider" min="0" max="100" value="75">
                        </div>
                        <div class="trait-item">
                            <label>Humor</label>
                            <input type="range" class="trait-slider" min="0" max="100" value="60">
                        </div>
                        <div class="trait-item">
                            <label>Intelligence</label>
                            <input type="range" class="trait-slider" min="0" max="100" value="85">
                        </div>
                        <div class="trait-item">
                            <label>Creativity</label>
                            <input type="range" class="trait-slider" min="0" max="100" value="70">
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Save Avatar -->
        <div class="studio-actions">
            <button class="btn btn-secondary" onclick="previewAvatar()">
                <i class='bx bx-show'></i>
                Preview
            </button>
            <button class="btn btn-primary" onclick="saveAvatar()">
                <i class='bx bx-save'></i>
                Save Avatar
            </button>
            <button class="btn btn-accent" onclick="publishAvatar()">
                <i class='bx bx-upload'></i>
                Publish & Deploy
            </button>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="avatar-pricing">
        <div class="pricing-header">
            <h2>💎 Avatar Packages</h2>
            <p>Choose your subscription level</p>
        </div>

        <div class="pricing-cards">

            <!-- Basic Plan -->
            <div class="pricing-card">
                <div class="card-header">
                    <h3>Basic Avatar</h3>
                    <div class="price">$9.99<span>/month</span></div>
                </div>
                <ul class="features">
                    <li>✅ 1 Custom Avatar</li>
                    <li>✅ Basic Voice Options</li>
                    <li>✅ Standard Animations</li>
                    <li>✅ Email Support</li>
                    <li>❌ Voice Training</li>
                    <li>❌ Advanced AI</li>
                </ul>
                <button class="btn btn-secondary" onclick="selectPlan('basic')">Choose Basic</button>
            </div>

            <!-- Pro Plan -->
            <div class="pricing-card featured">
                <div class="card-header">
                    <h3>Pro Avatar</h3>
                    <div class="price">$29.99<span>/month</span></div>
                    <div class="badge">Most Popular</div>
                </div>
                <ul class="features">
                    <li>✅ 5 Custom Avatars</li>
                    <li>✅ Advanced Voice Synthesis</li>
                    <li>✅ Custom Animations</li>
                    <li>✅ Priority Support</li>
                    <li>✅ Voice Training AI</li>
                    <li>✅ Emotion Recognition</li>
                </ul>
                <button class="btn btn-primary" onclick="selectPlan('pro')">Choose Pro</button>
            </div>

            <!-- Enterprise Plan -->
            <div class="pricing-card">
                <div class="card-header">
                    <h3>Enterprise</h3>
                    <div class="price">$99.99<span>/month</span></div>
                </div>
                <ul class="features">
                    <li>✅ Unlimited Avatars</li>
                    <li>✅ Custom Voice Models</li>
                    <li>✅ API Access</li>
                    <li>✅ 24/7 Support</li>
                    <li>✅ White Label Options</li>
                    <li>✅ Neural Network Training</li>
                </ul>
                <button class="btn btn-accent" onclick="selectPlan('enterprise')">Choose Enterprise</button>
            </div>

        </div>
    </section>

</div>

<!-- Voice Sample Audio Elements -->
<audio id="voiceSamplePlayer" preload="none"></audio>

<script>
    // Avatar Creator Functionality
    document.addEventListener('DOMContentLoaded', function() {
        initializeAvatarCarousel();
        initializeCyberBackground();
        initializeCustomizationStudio();
    });

    function initializeAvatarCarousel() {
        const carousel = document.getElementById('avatarCarousel');
        const cards = carousel.querySelectorAll('.avatar-card');
        const totalCards = cards.length;
        let currentIndex = 0;
        let radius = window.innerWidth <= 768 ? 250 : 400;

        function arrangeCards() {
            cards.forEach((card, index) => {
                const angle = (360 / totalCards) * index;
                const x = Math.cos((angle * Math.PI) / 180) * radius;
                const z = Math.sin((angle * Math.PI) / 180) * radius;

                card.style.transform = `translate3d(${x}px, 0, ${z}px) rotateY(${-angle}deg)`;
            });
        }

        function rotateCarousel(direction) {
            currentIndex += direction;
            const angle = (360 / totalCards) * currentIndex;
            carousel.style.transform = `rotateY(${angle}deg)`;
        }

        // Initialize carousel
        arrangeCards();

        // Carousel controls
        document.getElementById('prevBtn').addEventListener('click', () => rotateCarousel(-1));
        document.getElementById('nextBtn').addEventListener('click', () => rotateCarousel(1));

        // Auto-rotate
        setInterval(() => rotateCarousel(1), 8000);
    }

    function initializeCyberBackground() {
        const canvas = document.getElementById('cyberCanvas');
        const ctx = canvas.getContext('2d');

        function resizeCanvas() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }

        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);

        // Create data streams
        const streams = [];
        for (let i = 0; i < 20; i++) {
            streams.push({
                x: Math.random() * canvas.width,
                y: Math.random() * canvas.height,
                length: Math.random() * 100 + 50,
                speed: Math.random() * 2 + 1,
                opacity: Math.random() * 0.5 + 0.3
            });
        }

        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            streams.forEach(stream => {
                ctx.strokeStyle = `rgba(0, 255, 136, ${stream.opacity})`;
                ctx.lineWidth = 2;
                ctx.beginPath();
                ctx.moveTo(stream.x, stream.y);
                ctx.lineTo(stream.x, stream.y + stream.length);
                ctx.stroke();

                stream.y += stream.speed;
                if (stream.y > canvas.height + stream.length) {
                    stream.y = -stream.length;
                    stream.x = Math.random() * canvas.width;
                }
            });

            requestAnimationFrame(animate);
        }

        animate();
    }

    function initializeCustomizationStudio() {
        // Voice sliders
        document.getElementById('pitchSlider').addEventListener('input', function() {
            document.getElementById('pitchValue').textContent = this.value;
        });

        document.getElementById('speedSlider').addEventListener('input', function() {
            document.getElementById('speedValue').textContent = this.value;
        });

        document.getElementById('emotionSlider').addEventListener('input', function() {
            document.getElementById('emotionValue').textContent = this.value;
        });
    }

    function selectAvatar(avatarType) {
        if (avatarType === 'custom' || avatarType.includes('custom')) {
            document.getElementById('customStudio').classList.remove('hidden');
            document.querySelector('.avatar-carousel-section').style.display = 'none';
        } else {
            alert(`✨ ${avatarType} avatar selected! Configuring AI personality...`);
            // Process avatar selection
        }
    }

    function playVoiceSample(sampleId) {
        // In a real implementation, you would load actual voice samples
        const audio = document.getElementById('voiceSamplePlayer');
        audio.src = `<?php echo get_template_directory_uri(); ?>/assets/voice-samples/${sampleId}.mp3`;
        audio.play().catch(() => {
            // Fallback for browsers that block autoplay
            alert(`🔊 Playing voice sample: ${sampleId}`);
        });
    }

    function saveAvatar() {
        alert('💾 Avatar saved successfully! Your AI persona is ready to deploy.');
        // Send avatar data to server
    }

    function publishAvatar() {
        alert('🚀 Avatar published! Your AI is now live and ready for voice control.');
        // Deploy avatar to voice control system
    }

    function selectPlan(plan) {
        alert(`🎯 ${plan.toUpperCase()} plan selected! Redirecting to checkout...`);
        // Integrate with payment processing
    }

    // Voice interaction for avatar creator
    if ('webkitSpeechRecognition' in window) {
        const recognition = new webkitSpeechRecognition();
        recognition.continuous = true;
        recognition.interimResults = true;

        recognition.onresult = function(event) {
            const command = event.results[event.results.length - 1][0].transcript.toLowerCase();

            if (command.includes('create avatar')) {
                document.getElementById('customStudio').classList.remove('hidden');
            } else if (command.includes('select') && command.includes('avatar')) {
                // Voice avatar selection logic
                const avatarTypes = ['cyber sage', 'tech guardian', 'neural artist', 'quantum explorer'];
                const selectedAvatar = avatarTypes.find(type => command.includes(type));
                if (selectedAvatar) {
                    selectAvatar(selectedAvatar.replace(' ', '-'));
                }
            }
        };

        // Auto-start voice recognition on page load
        setTimeout(() => {
            recognition.start();
        }, 2000);
    }
</script>

<style>
    /* Avatar Creator Specific Styles */
    .cyber-background-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        z-index: -1;
        background: linear-gradient(45deg, #000428, #004e92);
        overflow: hidden;
    }

    .cyber-background-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background:
            radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
            radial-gradient(circle at 40% 40%, rgba(120, 200, 255, 0.2) 0%, transparent 50%);
    }

    #cyberCanvas {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
    }

    .avatar-carousel-section {
        padding: 4rem 2rem;
        perspective: 1000px;
    }

    .carousel-container {
        position: relative;
        width: 100%;
        height: 600px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .carousel {
        position: relative;
        width: 300px;
        height: 400px;
        transform-style: preserve-3d;
        transition: transform 1s;
    }

    .avatar-card {
        position: absolute;
        width: 280px;
        height: 380px;
        background: rgba(20, 20, 40, 0.9);
        border: 2px solid var(--primary-color);
        border-radius: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .avatar-card:hover {
        transform: scale(1.05);
        box-shadow: 0 20px 40px rgba(157, 0, 255, 0.4);
    }

    .card-inner {
        position: relative;
        width: 100%;
        height: 100%;
        transform-style: preserve-3d;
        transition: transform 0.6s;
    }

    .avatar-card:hover .card-inner {
        transform: rotateY(180deg);
    }

    .card-front,
    .card-back {
        position: absolute;
        width: 100%;
        height: 100%;
        backface-visibility: hidden;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .card-back {
        transform: rotateY(180deg);
        background: rgba(40, 40, 80, 0.9);
    }

    .avatar-preview img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--secondary-color);
        margin-bottom: 1rem;
    }

    .avatar-traits,
    .custom-features {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .trait,
    .feature {
        background: rgba(157, 0, 255, 0.2);
        padding: 0.25rem 0.5rem;
        border-radius: 15px;
        font-size: 0.8rem;
        border: 1px solid var(--primary-color);
    }

    .customization-studio {
        padding: 4rem 2rem;
        background: rgba(5, 5, 16, 0.9);
        backdrop-filter: blur(20px);
    }

    .studio-workspace {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 3rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    .avatar-preview-panel {
        background: rgba(20, 20, 40, 0.8);
        border-radius: 15px;
        padding: 2rem;
        border: 2px solid var(--primary-color);
    }

    .avatar-display img {
        width: 100%;
        max-width: 300px;
        border-radius: 15px;
        border: 3px solid var(--secondary-color);
    }

    .customization-panels {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .custom-panel {
        background: rgba(20, 20, 40, 0.8);
        border-radius: 15px;
        padding: 1.5rem;
        border: 1px solid var(--primary-color);
    }

    .option-buttons {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }

    .option-btn {
        padding: 0.5rem 1rem;
        border: 2px solid var(--secondary-color);
        background: transparent;
        color: var(--text-primary);
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .option-btn.active,
    .option-btn:hover {
        background: var(--secondary-color);
        color: var(--background-dark);
    }

    .voice-slider,
    .trait-slider {
        width: 100%;
        margin-top: 0.5rem;
        accent-color: var(--primary-color);
    }

    .color-picker {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }

    .color-option {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .color-option:hover {
        border-color: var(--text-primary);
        transform: scale(1.1);
    }

    .avatar-pricing {
        padding: 4rem 2rem;
        background: rgba(5, 5, 16, 0.95);
    }

    .pricing-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .pricing-card {
        background: rgba(20, 20, 40, 0.9);
        border: 2px solid var(--primary-color);
        border-radius: 15px;
        padding: 2rem;
        text-align: center;
        position: relative;
        transition: all 0.3s ease;
    }

    .pricing-card.featured {
        border-color: var(--accent-color);
        transform: scale(1.05);
        box-shadow: 0 20px 40px rgba(255, 0, 229, 0.3);
    }

    .pricing-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(157, 0, 255, 0.4);
    }

    .price {
        font-size: 2.5rem;
        font-weight: 900;
        color: var(--primary-color);
        margin: 1rem 0;
    }

    .price span {
        font-size: 1rem;
        color: var(--text-secondary);
    }

    .badge {
        position: absolute;
        top: -10px;
        right: 20px;
        background: var(--accent-color);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .features {
        list-style: none;
        text-align: left;
        margin: 2rem 0;
    }

    .features li {
        padding: 0.5rem 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .hidden {
        display: none !important;
    }

    @media (max-width: 768px) {
        .studio-workspace {
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .carousel {
            width: 250px;
            height: 350px;
        }

        .avatar-card {
            width: 230px;
            height: 320px;
        }
    }
</style>

<?php get_footer(); ?>