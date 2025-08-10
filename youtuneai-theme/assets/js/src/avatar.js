/**
 * 3D Avatar System
 * 
 * @package YouTuneAI
 */

(function() {
    'use strict';

    class YouTuneAIAvatar {
        constructor(container, options = {}) {
            this.container = container;
            this.options = {
                avatarId: options.avatarId || 1,
                interactive: options.interactive !== false,
                voice: options.voice !== false,
                modelPath: options.modelPath || 'assets/models/default-avatar.glb',
                ...options
            };

            this.scene = null;
            this.camera = null;
            this.renderer = null;
            this.avatar = null;
            this.mixer = null;
            this.clock = new THREE.Clock();
            this.isListening = false;
            this.audioContext = null;
            this.mediaRecorder = null;
            
            this.init();
        }

        async init() {
            try {
                this.updateStatus('Initializing 3D engine...');
                await this.setupScene();
                await this.loadAvatar();
                await this.setupLighting();
                await this.setupControls();
                await this.startRenderLoop();
                
                if (this.options.interactive) {
                    this.setupInteraction();
                }
                
                this.updateStatus('Ready', 'success');
                console.log('YouTuneAI Avatar initialized successfully');
            } catch (error) {
                console.error('Avatar initialization failed:', error);
                this.updateStatus('Failed to initialize', 'error');
            }
        }

        setupScene() {
            const canvas = this.container.querySelector('.avatar-canvas');
            const width = canvas.width || 300;
            const height = canvas.height || 400;

            // Scene
            this.scene = new THREE.Scene();
            this.scene.background = new THREE.Color(0x050510);

            // Camera
            this.camera = new THREE.PerspectiveCamera(75, width / height, 0.1, 1000);
            this.camera.position.set(0, 1.6, 2);

            // Renderer
            this.renderer = new THREE.WebGLRenderer({
                canvas: canvas,
                antialias: true,
                alpha: true
            });
            this.renderer.setSize(width, height);
            this.renderer.shadowMap.enabled = true;
            this.renderer.shadowMap.type = THREE.PCFSoftShadowMap;
            this.renderer.toneMapping = THREE.ACESFilmicToneMapping;
            this.renderer.toneMappingExposure = 1;
        }

        async loadAvatar() {
            this.updateStatus('Loading 3D model...');

            return new Promise((resolve, reject) => {
                const loader = new THREE.GLTFLoader();
                
                loader.load(
                    this.options.modelPath,
                    (gltf) => {
                        this.avatar = gltf.scene;
                        this.avatar.scale.setScalar(1);
                        this.avatar.position.set(0, 0, 0);
                        
                        // Enable shadows
                        this.avatar.traverse((child) => {
                            if (child.isMesh) {
                                child.castShadow = true;
                                child.receiveShadow = true;
                            }
                        });

                        this.scene.add(this.avatar);

                        // Setup animations
                        if (gltf.animations.length > 0) {
                            this.mixer = new THREE.AnimationMixer(this.avatar);
                            
                            gltf.animations.forEach((clip) => {
                                const action = this.mixer.clipAction(clip);
                                if (clip.name.includes('idle')) {
                                    action.play();
                                }
                            });
                        }

                        resolve();
                    },
                    (progress) => {
                        const percent = Math.round((progress.loaded / progress.total) * 100);
                        this.updateStatus(`Loading model... ${percent}%`);
                    },
                    (error) => {
                        console.error('Error loading avatar model:', error);
                        this.loadFallbackAvatar().then(resolve).catch(reject);
                    }
                );
            });
        }

        loadFallbackAvatar() {
            // Create a simple cube avatar as fallback
            return new Promise((resolve) => {
                const geometry = new THREE.BoxGeometry(0.5, 0.8, 0.3);
                const material = new THREE.MeshLambertMaterial({ color: 0x9d00ff });
                this.avatar = new THREE.Mesh(geometry, material);
                this.avatar.position.set(0, 0.4, 0);
                this.avatar.castShadow = true;
                this.scene.add(this.avatar);
                resolve();
            });
        }

        setupLighting() {
            // Ambient light
            const ambientLight = new THREE.AmbientLight(0xffffff, 0.6);
            this.scene.add(ambientLight);

            // Main directional light
            const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
            directionalLight.position.set(5, 10, 5);
            directionalLight.castShadow = true;
            directionalLight.shadow.camera.near = 0.1;
            directionalLight.shadow.camera.far = 50;
            directionalLight.shadow.camera.left = -5;
            directionalLight.shadow.camera.right = 5;
            directionalLight.shadow.camera.top = 5;
            directionalLight.shadow.camera.bottom = -5;
            directionalLight.shadow.mapSize.width = 2048;
            directionalLight.shadow.mapSize.height = 2048;
            this.scene.add(directionalLight);

            // Rim light
            const rimLight = new THREE.DirectionalLight(0x00e5ff, 0.3);
            rimLight.position.set(-5, 2, -5);
            this.scene.add(rimLight);

            // Point light for face illumination
            const faceLight = new THREE.PointLight(0xffffff, 0.5, 10);
            faceLight.position.set(0, 2, 1);
            this.scene.add(faceLight);
        }

        setupControls() {
            let isDragging = false;
            let previousMousePosition = { x: 0, y: 0 };

            const canvas = this.container.querySelector('.avatar-canvas');

            canvas.addEventListener('mousedown', (e) => {
                isDragging = true;
                previousMousePosition.x = e.clientX;
                previousMousePosition.y = e.clientY;
            });

            canvas.addEventListener('mousemove', (e) => {
                if (!isDragging) return;

                const deltaMove = {
                    x: e.clientX - previousMousePosition.x,
                    y: e.clientY - previousMousePosition.y
                };

                if (this.avatar) {
                    this.avatar.rotation.y += deltaMove.x * 0.01;
                    this.avatar.rotation.x += deltaMove.y * 0.01;
                }

                previousMousePosition.x = e.clientX;
                previousMousePosition.y = e.clientY;
            });

            document.addEventListener('mouseup', () => {
                isDragging = false;
            });

            // Zoom with mouse wheel
            canvas.addEventListener('wheel', (e) => {
                e.preventDefault();
                const zoomSpeed = 0.1;
                this.camera.position.z += e.deltaY * zoomSpeed * 0.01;
                this.camera.position.z = Math.max(1, Math.min(5, this.camera.position.z));
            });
        }

        startRenderLoop() {
            const animate = () => {
                requestAnimationFrame(animate);

                const delta = this.clock.getDelta();

                // Update animations
                if (this.mixer) {
                    this.mixer.update(delta);
                }

                // Idle rotation
                if (this.avatar && !this.isDragging) {
                    this.avatar.rotation.y += 0.005;
                }

                this.renderer.render(this.scene, this.camera);
            };

            animate();
        }

        setupInteraction() {
            if (this.options.voice) {
                this.setupVoiceRecognition();
            }

            this.setupChatInterface();
        }

        setupVoiceRecognition() {
            const micBtn = this.container.querySelector('.avatar-mic-btn');
            if (!micBtn) return;

            micBtn.addEventListener('click', () => {
                if (this.isListening) {
                    this.stopListening();
                } else {
                    this.startListening();
                }
            });
        }

        async startListening() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                this.audioContext = new (window.AudioContext || window.webkitAudioContext)();
                
                this.mediaRecorder = new MediaRecorder(stream);
                const audioChunks = [];

                this.mediaRecorder.ondataavailable = (event) => {
                    audioChunks.push(event.data);
                };

                this.mediaRecorder.onstop = async () => {
                    const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
                    await this.processAudioInput(audioBlob);
                };

                this.mediaRecorder.start();
                this.isListening = true;
                this.updateMicButton(true);
                this.updateStatus('Listening...');

                // Auto-stop after 10 seconds
                setTimeout(() => {
                    if (this.isListening) {
                        this.stopListening();
                    }
                }, 10000);

            } catch (error) {
                console.error('Error accessing microphone:', error);
                this.updateStatus('Microphone access denied', 'error');
            }
        }

        stopListening() {
            if (this.mediaRecorder && this.isListening) {
                this.mediaRecorder.stop();
                this.isListening = false;
                this.updateMicButton(false);
                this.updateStatus('Processing...');
            }
        }

        async processAudioInput(audioBlob) {
            try {
                // In a real implementation, this would send to speech-to-text service
                // For demo, we'll simulate processing
                await new Promise(resolve => setTimeout(resolve, 1000));
                
                const mockTranscript = "Hello, how are you today?";
                await this.handleChatMessage(mockTranscript);
                
            } catch (error) {
                console.error('Error processing audio:', error);
                this.updateStatus('Error processing audio', 'error');
            }
        }

        setupChatInterface() {
            const input = this.container.querySelector('.avatar-input');
            const sendBtn = this.container.querySelector('.avatar-send-btn');

            if (!input || !sendBtn) return;

            const sendMessage = async () => {
                const message = input.value.trim();
                if (message) {
                    input.value = '';
                    await this.handleChatMessage(message);
                }
            };

            sendBtn.addEventListener('click', sendMessage);
            input.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    sendMessage();
                }
            });
        }

        async handleChatMessage(message) {
            try {
                this.updateStatus('Thinking...');
                
                // Call the avatar chat API
                const response = await fetch('/wp-json/youtuneai/v1/avatar/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        message: message,
                        avatar_id: this.options.avatarId
                    })
                });

                const data = await response.json();
                
                if (data.message) {
                    await this.speakResponse(data.message);
                    this.animateEmotion(data.emotion || 'neutral');
                }

                this.updateStatus('Ready', 'success');

            } catch (error) {
                console.error('Error handling chat message:', error);
                this.updateStatus('Error communicating', 'error');
            }
        }

        async speakResponse(text) {
            if ('speechSynthesis' in window) {
                const utterance = new SpeechSynthesisUtterance(text);
                utterance.rate = 0.8;
                utterance.pitch = 1.1;
                utterance.volume = 0.8;

                // Add lip sync animation
                utterance.onstart = () => {
                    this.startLipSync();
                };

                utterance.onend = () => {
                    this.stopLipSync();
                };

                speechSynthesis.speak(utterance);
            }
        }

        startLipSync() {
            // Simple mouth animation
            if (this.avatar) {
                const animate = () => {
                    if (speechSynthesis.speaking) {
                        // Animate mouth/jaw (if model supports it)
                        // This would typically modify blend shapes or bone rotations
                        requestAnimationFrame(animate);
                    }
                };
                animate();
            }
        }

        stopLipSync() {
            // Reset mouth position
        }

        animateEmotion(emotion) {
            // Play emotion-specific animation
            if (this.mixer && this.avatar) {
                const emotionClips = {
                    'happy': 'happy_idle',
                    'excited': 'excited_gesture',
                    'neutral': 'neutral_idle'
                };

                const clipName = emotionClips[emotion] || 'neutral_idle';
                
                // Find and play the emotion clip
                this.mixer._actions.forEach((action) => {
                    if (action._clip.name === clipName) {
                        action.reset().fadeIn(0.5).play();
                    }
                });
            }
        }

        updateStatus(message, type = 'info') {
            const statusText = this.container.querySelector('.status-text');
            const statusIndicator = this.container.querySelector('.status-indicator');

            if (statusText) {
                statusText.textContent = message;
            }

            if (statusIndicator) {
                statusIndicator.className = `status-indicator ${type}`;
            }
        }

        updateMicButton(isListening) {
            const micBtn = this.container.querySelector('.avatar-mic-btn');
            if (micBtn) {
                micBtn.classList.toggle('listening', isListening);
                const icon = micBtn.querySelector('i');
                if (icon) {
                    icon.className = isListening ? 'bx bx-stop' : 'bx bx-microphone';
                }
            }
        }

        resize(width, height) {
            if (this.camera && this.renderer) {
                this.camera.aspect = width / height;
                this.camera.updateProjectionMatrix();
                this.renderer.setSize(width, height);
            }
        }

        destroy() {
            if (this.renderer) {
                this.renderer.dispose();
            }
            if (this.scene) {
                this.scene.clear();
            }
        }
    }

    // Initialize avatars when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        const avatarContainers = document.querySelectorAll('.youtuneai-avatar-container');
        
        avatarContainers.forEach(container => {
            const options = {
                avatarId: container.dataset.avatarId,
                interactive: container.dataset.interactive === 'true',
                voice: container.dataset.voice === 'true'
            };

            new YouTuneAIAvatar(container, options);
        });
    });

    // Export for global access
    window.YouTuneAIAvatar = YouTuneAIAvatar;

})();