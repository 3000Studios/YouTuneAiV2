/**
 * VR System JavaScript
 */
(function() {
    'use strict';

    class YouTuneAIVR {
        constructor() {
            this.isVRSupported = false;
            this.currentSession = null;
            this.scene = null;
            this.camera = null;
            this.renderer = null;
            this.vrButton = null;
            
            this.init();
        }

        async init() {
            console.log('Initializing YouTuneAI VR System');
            await this.checkVRSupport();
            this.setupThreeJS();
        }

        async checkVRSupport() {
            try {
                if ('xr' in navigator && 'isSessionSupported' in navigator.xr) {
                    this.isVRSupported = await navigator.xr.isSessionSupported('immersive-vr');
                    console.log('VR Support:', this.isVRSupported);
                } else {
                    console.log('WebXR not supported');
                }
            } catch (error) {
                console.warn('VR support check failed:', error);
                this.isVRSupported = false;
            }
        }

        setupThreeJS() {
            // Scene setup
            this.scene = new THREE.Scene();
            this.scene.background = new THREE.Color(0x050510);

            // Camera
            this.camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
            this.camera.position.set(0, 1.6, 3);

            // Renderer with VR support
            this.renderer = new THREE.WebGLRenderer({ antialias: true });
            this.renderer.setPixelRatio(window.devicePixelRatio);
            this.renderer.setSize(window.innerWidth, window.innerHeight);
            this.renderer.xr.enabled = true;
            this.renderer.shadowMap.enabled = true;
            this.renderer.shadowMap.type = THREE.PCFSoftShadowMap;

            // VR Button
            if (this.isVRSupported) {
                this.vrButton = this.createVRButton();
                document.body.appendChild(this.vrButton);
            }

            this.setupDefaultScene();
            this.startRenderLoop();
        }

        createVRButton() {
            const button = document.createElement('button');
            button.style.position = 'fixed';
            button.style.bottom = '20px';
            button.style.right = '20px';
            button.style.padding = '12px 20px';
            button.style.border = 'none';
            button.style.borderRadius = '6px';
            button.style.background = '#9d00ff';
            button.style.color = 'white';
            button.style.fontSize = '16px';
            button.style.cursor = 'pointer';
            button.style.zIndex = '10000';
            button.textContent = 'Enter VR';

            button.onclick = () => {
                if (this.currentSession === null) {
                    this.startVRSession();
                } else {
                    this.endVRSession();
                }
            };

            return button;
        }

        async startVRSession() {
            try {
                this.currentSession = await navigator.xr.requestSession('immersive-vr');
                await this.renderer.xr.setSession(this.currentSession);

                this.currentSession.addEventListener('end', () => {
                    this.currentSession = null;
                    this.vrButton.textContent = 'Enter VR';
                });

                this.vrButton.textContent = 'Exit VR';
            } catch (error) {
                console.error('Failed to start VR session:', error);
            }
        }

        endVRSession() {
            if (this.currentSession) {
                this.currentSession.end();
            }
        }

        setupDefaultScene() {
            // Lighting
            const ambientLight = new THREE.AmbientLight(0xffffff, 0.6);
            this.scene.add(ambientLight);

            const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
            directionalLight.position.set(10, 10, 5);
            directionalLight.castShadow = true;
            this.scene.add(directionalLight);

            // Ground
            const groundGeometry = new THREE.PlaneGeometry(20, 20);
            const groundMaterial = new THREE.MeshLambertMaterial({ color: 0x2a3439 });
            const ground = new THREE.Mesh(groundGeometry, groundMaterial);
            ground.rotation.x = -Math.PI / 2;
            ground.receiveShadow = true;
            this.scene.add(ground);

            // Sample objects
            this.addSampleObjects();
        }

        addSampleObjects() {
            // Floating cubes
            for (let i = 0; i < 5; i++) {
                const geometry = new THREE.BoxGeometry(0.5, 0.5, 0.5);
                const material = new THREE.MeshPhongMaterial({ 
                    color: Math.random() * 0xffffff 
                });
                const cube = new THREE.Mesh(geometry, material);
                
                cube.position.set(
                    (Math.random() - 0.5) * 10,
                    Math.random() * 3 + 1,
                    (Math.random() - 0.5) * 10
                );
                
                cube.castShadow = true;
                this.scene.add(cube);
            }

            // Central platform
            const platformGeometry = new THREE.CylinderGeometry(2, 2, 0.2, 16);
            const platformMaterial = new THREE.MeshPhongMaterial({ color: 0x9d00ff });
            const platform = new THREE.Mesh(platformGeometry, platformMaterial);
            platform.position.y = 0.1;
            platform.receiveShadow = true;
            this.scene.add(platform);
        }

        startRenderLoop() {
            this.renderer.setAnimationLoop(() => {
                // Animate objects
                this.scene.traverse((child) => {
                    if (child.isMesh && child.geometry.type === 'BoxGeometry') {
                        child.rotation.x += 0.01;
                        child.rotation.y += 0.01;
                    }
                });

                this.renderer.render(this.scene, this.camera);
            });
        }

        // Room-specific setups
        setupCinemaRoom() {
            // Clear scene
            while(this.scene.children.length > 0) {
                this.scene.remove(this.scene.children[0]);
            }

            // Cinema setup
            const seats = this.createCinemaSeats();
            const screen = this.createCinemaScreen();
            
            this.scene.add(seats);
            this.scene.add(screen);
            
            // Dark cinema lighting
            const ambientLight = new THREE.AmbientLight(0x404040, 0.2);
            this.scene.add(ambientLight);
        }

        createCinemaSeats() {
            const group = new THREE.Group();
            
            for (let row = 0; row < 5; row++) {
                for (let seat = 0; seat < 8; seat++) {
                    const seatGeometry = new THREE.BoxGeometry(0.8, 0.8, 0.8);
                    const seatMaterial = new THREE.MeshLambertMaterial({ color: 0x8b0000 });
                    const seatMesh = new THREE.Mesh(seatGeometry, seatMaterial);
                    
                    seatMesh.position.set(
                        (seat - 3.5) * 1.2,
                        0.4,
                        (row - 2) * 1.5
                    );
                    
                    group.add(seatMesh);
                }
            }
            
            return group;
        }

        createCinemaScreen() {
            const screenGeometry = new THREE.PlaneGeometry(8, 4.5);
            const screenMaterial = new THREE.MeshBasicMaterial({ 
                color: 0xffffff,
                map: new THREE.VideoTexture(this.createVideoElement())
            });
            const screen = new THREE.Mesh(screenGeometry, screenMaterial);
            screen.position.set(0, 3, -8);
            
            return screen;
        }

        createVideoElement() {
            const video = document.createElement('video');
            video.src = 'path/to/sample-video.mp4';
            video.crossOrigin = 'anonymous';
            video.loop = true;
            video.muted = true;
            video.play();
            
            return video;
        }

        // Utility methods
        resize() {
            if (this.camera && this.renderer) {
                this.camera.aspect = window.innerWidth / window.innerHeight;
                this.camera.updateProjectionMatrix();
                this.renderer.setSize(window.innerWidth, window.innerHeight);
            }
        }

        destroy() {
            if (this.renderer) {
                this.renderer.dispose();
            }
            if (this.vrButton && this.vrButton.parentNode) {
                this.vrButton.parentNode.removeChild(this.vrButton);
            }
        }
    }

    // Auto-initialize on compatible pages
    if (document.querySelector('#vr-canvas') || document.querySelector('[data-vr-enter]')) {
        document.addEventListener('DOMContentLoaded', () => {
            window.YouTuneAIVR = new YouTuneAIVR();
        });
    }

    // Handle page resize
    window.addEventListener('resize', () => {
        if (window.YouTuneAIVR) {
            window.YouTuneAIVR.resize();
        }
    });

    // Cleanup on page unload
    window.addEventListener('beforeunload', () => {
        if (window.YouTuneAIVR) {
            window.YouTuneAIVR.destroy();
        }
    });

})();