/**
 * YouTune Garage 3D Configurator
 */
(function() {
    'use strict';

    class YouTuneGarage {
        constructor() {
            this.scene = null;
            this.camera = null;
            this.renderer = null;
            this.controls = null;
            this.vehicle = null;
            this.selectedParts = new Map();
            this.availableParts = new Map();
            this.isLoading = false;
            this.clock = new THREE.Clock();
            
            // Configuration
            this.basePrice = 25000;
            this.laborRate = 150;
            this.currentMaterial = 'metallic';
            this.currentColor = '#FF0000';
            
            this.init();
        }

        async init() {
            console.log('Initializing YouTune Garage 3D Configurator');
            
            await this.setupThreeJS();
            await this.loadBasicVehicle();
            await this.loadAvailableParts();
            this.setupUI();
            this.startRenderLoop();
            
            console.log('YouTune Garage initialized successfully');
        }

        async setupThreeJS() {
            const canvas = document.getElementById('garage-3d-canvas');
            if (!canvas) return;

            // Scene
            this.scene = new THREE.Scene();
            this.scene.background = new THREE.Color(0x1a1a1a);
            this.scene.fog = new THREE.Fog(0x1a1a1a, 20, 100);

            // Camera
            this.camera = new THREE.PerspectiveCamera(75, canvas.offsetWidth / canvas.offsetHeight, 0.1, 1000);
            this.camera.position.set(5, 3, 5);

            // Renderer
            this.renderer = new THREE.WebGLRenderer({ canvas: canvas, antialias: true });
            this.renderer.setPixelRatio(window.devicePixelRatio);
            this.renderer.setSize(canvas.offsetWidth, canvas.offsetHeight);
            this.renderer.shadowMap.enabled = true;
            this.renderer.shadowMap.type = THREE.PCFSoftShadowMap;
            this.renderer.toneMapping = THREE.ACESFilmicToneMapping;
            this.renderer.toneMappingExposure = 1;

            // Controls
            this.controls = new THREE.OrbitControls(this.camera, canvas);
            this.controls.target.set(0, 1, 0);
            this.controls.maxPolarAngle = Math.PI * 0.8;
            this.controls.minDistance = 3;
            this.controls.maxDistance = 20;
            this.controls.enableDamping = true;
            this.controls.dampingFactor = 0.05;

            this.setupLighting();
            this.setupEnvironment();
            
            // Hide loading screen
            setTimeout(() => {
                const loading = document.getElementById('garage-loading');
                if (loading) loading.style.display = 'none';
            }, 1000);
        }

        setupLighting() {
            // Ambient light
            const ambientLight = new THREE.AmbientLight(0xffffff, 0.4);
            this.scene.add(ambientLight);

            // Main directional light
            const mainLight = new THREE.DirectionalLight(0xffffff, 0.8);
            mainLight.position.set(10, 10, 5);
            mainLight.castShadow = true;
            mainLight.shadow.camera.near = 0.1;
            mainLight.shadow.camera.far = 50;
            mainLight.shadow.camera.left = -10;
            mainLight.shadow.camera.right = 10;
            mainLight.shadow.camera.top = 10;
            mainLight.shadow.camera.bottom = -10;
            mainLight.shadow.mapSize.width = 2048;
            mainLight.shadow.mapSize.height = 2048;
            this.scene.add(mainLight);

            // Fill light
            const fillLight = new THREE.DirectionalLight(0x4a90e2, 0.3);
            fillLight.position.set(-5, 5, -5);
            this.scene.add(fillLight);

            // Rim light
            const rimLight = new THREE.DirectionalLight(0xff6b6b, 0.2);
            rimLight.position.set(-10, 0, 10);
            this.scene.add(rimLight);

            // Studio lighting setup
            const light1 = new THREE.PointLight(0xffffff, 0.5, 50);
            light1.position.set(0, 10, 0);
            this.scene.add(light1);

            const light2 = new THREE.PointLight(0x4a90e2, 0.3, 30);
            light2.position.set(10, 5, 10);
            this.scene.add(light2);
        }

        setupEnvironment() {
            // Ground plane
            const groundGeometry = new THREE.PlaneGeometry(50, 50);
            const groundMaterial = new THREE.MeshLambertMaterial({ 
                color: 0x2a2a2a,
                transparent: true,
                opacity: 0.8
            });
            const ground = new THREE.Mesh(groundGeometry, groundMaterial);
            ground.rotation.x = -Math.PI / 2;
            ground.receiveShadow = true;
            this.scene.add(ground);

            // Grid
            const gridHelper = new THREE.GridHelper(50, 50, 0x444444, 0x444444);
            gridHelper.material.transparent = true;
            gridHelper.material.opacity = 0.3;
            this.scene.add(gridHelper);

            // Studio backdrop
            const backdropGeometry = new THREE.PlaneGeometry(30, 15);
            const backdropMaterial = new THREE.MeshLambertMaterial({ 
                color: 0x333333 
            });
            const backdrop = new THREE.Mesh(backdropGeometry, backdropMaterial);
            backdrop.position.set(0, 7.5, -10);
            backdrop.receiveShadow = true;
            this.scene.add(backdrop);
        }

        async loadBasicVehicle() {
            // Create a basic car shape as placeholder
            this.vehicle = new THREE.Group();
            
            // Car body
            const bodyGeometry = new THREE.BoxGeometry(4, 1.2, 2);
            const bodyMaterial = new THREE.MeshPhongMaterial({ 
                color: 0xff0000,
                shininess: 100,
                transparent: false
            });
            const body = new THREE.Mesh(bodyGeometry, bodyMaterial);
            body.position.y = 1;
            body.castShadow = true;
            body.userData = { part: 'body', name: 'Car Body' };
            this.vehicle.add(body);

            // Car roof
            const roofGeometry = new THREE.BoxGeometry(3.5, 1, 1.8);
            const roofMaterial = new THREE.MeshPhongMaterial({ 
                color: 0xff0000,
                shininess: 100
            });
            const roof = new THREE.Mesh(roofGeometry, roofMaterial);
            roof.position.y = 2.1;
            roof.castShadow = true;
            roof.userData = { part: 'body', name: 'Car Roof' };
            this.vehicle.add(roof);

            // Wheels
            this.addWheels();

            this.vehicle.position.y = 0.5;
            this.scene.add(this.vehicle);

            // Update UI
            this.updatePricing();
        }

        addWheels() {
            const wheelPositions = [
                { x: -1.5, z: 1.2 },   // Front left
                { x: 1.5, z: 1.2 },    // Front right
                { x: -1.5, z: -1.2 },  // Rear left
                { x: 1.5, z: -1.2 }    // Rear right
            ];

            wheelPositions.forEach((pos, index) => {
                const wheelGroup = new THREE.Group();
                
                // Tire
                const tireGeometry = new THREE.TorusGeometry(0.4, 0.15, 8, 16);
                const tireMaterial = new THREE.MeshLambertMaterial({ color: 0x1a1a1a });
                const tire = new THREE.Mesh(tireGeometry, tireMaterial);
                tire.rotation.x = Math.PI / 2;
                tire.castShadow = true;
                wheelGroup.add(tire);

                // Rim
                const rimGeometry = new THREE.CylinderGeometry(0.3, 0.3, 0.1, 8);
                const rimMaterial = new THREE.MeshPhongMaterial({ 
                    color: 0xcccccc,
                    shininess: 100
                });
                const rim = new THREE.Mesh(rimGeometry, rimMaterial);
                rim.rotation.x = Math.PI / 2;
                rim.castShadow = true;
                wheelGroup.add(rim);

                wheelGroup.position.set(pos.x, 0.4, pos.z);
                wheelGroup.userData = { part: 'wheels', name: `Wheel ${index + 1}` };
                this.vehicle.add(wheelGroup);
            });
        }

        async loadAvailableParts() {
            // Simulate loading parts from API
            const mockParts = {
                body: [
                    { id: 1, name: 'Sport Body Kit', price: 2500, model: 'sport_body.glb' },
                    { id: 2, name: 'Racing Body Kit', price: 3500, model: 'racing_body.glb' },
                    { id: 3, name: 'Off-Road Body Kit', price: 2800, model: 'offroad_body.glb' }
                ],
                wheels: [
                    { id: 4, name: '18" Alloy Wheels', price: 1200, model: 'alloy_18.glb' },
                    { id: 5, name: '20" Racing Wheels', price: 2400, model: 'racing_20.glb' },
                    { id: 6, name: '22" Chrome Wheels', price: 3600, model: 'chrome_22.glb' }
                ],
                engine: [
                    { id: 7, name: 'V6 Turbo Engine', price: 8500, model: 'v6_turbo.glb' },
                    { id: 8, name: 'V8 Engine', price: 12000, model: 'v8_engine.glb' },
                    { id: 9, name: 'Electric Motor', price: 15000, model: 'electric.glb' }
                ],
                interior: [
                    { id: 10, name: 'Leather Seats', price: 2800, model: 'leather_seats.glb' },
                    { id: 11, name: 'Racing Seats', price: 3500, model: 'racing_seats.glb' },
                    { id: 12, name: 'Premium Interior', price: 5200, model: 'premium_interior.glb' }
                ],
                accessories: [
                    { id: 13, name: 'Spoiler', price: 850, model: 'spoiler.glb' },
                    { id: 14, name: 'Side Skirts', price: 650, model: 'side_skirts.glb' },
                    { id: 15, name: 'LED Light Kit', price: 1200, model: 'led_lights.glb' }
                ]
            };

            this.availableParts = new Map(Object.entries(mockParts));
        }

        setupUI() {
            // Category buttons
            document.querySelectorAll('.part-category').forEach(button => {
                button.addEventListener('click', (e) => {
                    this.selectCategory(e.target.dataset.category);
                });
            });

            // View controls
            document.querySelectorAll('[data-view]').forEach(button => {
                button.addEventListener('click', (e) => {
                    this.setView(e.target.dataset.view);
                });
            });

            // Color swatches
            document.querySelectorAll('.color-swatch').forEach(swatch => {
                swatch.addEventListener('click', (e) => {
                    this.changeColor(e.target.dataset.color);
                });
            });

            // Material buttons
            document.querySelectorAll('.material-btn').forEach(button => {
                button.addEventListener('click', (e) => {
                    this.changeMaterial(e.target.dataset.material);
                });
            });

            // Control buttons
            document.getElementById('reset-view')?.addEventListener('click', () => {
                this.resetView();
            });

            document.getElementById('toggle-wireframe')?.addEventListener('click', () => {
                this.toggleWireframe();
            });

            document.getElementById('auto-rotate')?.addEventListener('change', (e) => {
                this.controls.autoRotate = e.target.checked;
            });

            document.getElementById('take-screenshot')?.addEventListener('click', () => {
                this.takeScreenshot();
            });

            // Configuration actions
            document.getElementById('add-to-cart')?.addEventListener('click', () => {
                this.addToCart();
            });

            document.getElementById('save-config')?.addEventListener('click', () => {
                this.saveConfiguration();
            });

            document.getElementById('share-config')?.addEventListener('click', () => {
                this.shareConfiguration();
            });

            // Preset configurations
            document.querySelectorAll('.preset-config').forEach(button => {
                button.addEventListener('click', (e) => {
                    this.loadPreset(e.target.dataset.preset);
                });
            });

            // Search
            const searchInput = document.getElementById('parts-search');
            if (searchInput) {
                searchInput.addEventListener('input', (e) => {
                    this.searchParts(e.target.value);
                });
            }

            // Window resize
            window.addEventListener('resize', () => {
                this.resize();
            });
        }

        selectCategory(category) {
            // Update active category button
            document.querySelectorAll('.part-category').forEach(btn => {
                btn.classList.remove('bg-primary/20', 'border-primary/30', 'text-primary');
                btn.classList.add('bg-background-card', 'border-platinum/20', 'text-platinum', 'hover:bg-secondary/20', 'hover:border-secondary/30', 'hover:text-secondary');
            });

            document.querySelector(`[data-category="${category}"]`).classList.remove('bg-background-card', 'border-platinum/20', 'text-platinum', 'hover:bg-secondary/20', 'hover:border-secondary/30', 'hover:text-secondary');
            document.querySelector(`[data-category="${category}"]`).classList.add('bg-primary/20', 'border-primary/30', 'text-primary');

            // Load parts for category
            this.loadPartsForCategory(category);
        }

        loadPartsForCategory(category) {
            const partsList = document.getElementById('parts-list');
            const parts = this.availableParts.get(category) || [];

            partsList.innerHTML = '';

            if (parts.length === 0) {
                partsList.innerHTML = `
                    <div class="text-platinum/60 text-sm text-center py-4">
                        No parts available for this category
                    </div>
                `;
                return;
            }

            parts.forEach(part => {
                const partElement = document.createElement('div');
                partElement.className = 'part-item p-3 rounded-lg bg-background-card border border-platinum/20 hover:bg-primary/10 hover:border-primary/30 cursor-pointer transition-all';
                partElement.innerHTML = `
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="font-semibold text-platinum text-sm">${part.name}</div>
                            <div class="text-accent text-sm">$${part.price.toLocaleString()}</div>
                        </div>
                        <button class="add-part-btn bg-primary text-white px-3 py-1 rounded text-xs hover:bg-secondary transition-colors" data-part-id="${part.id}">
                            Add
                        </button>
                    </div>
                `;

                partElement.querySelector('.add-part-btn').addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.addPart(part);
                });

                partsList.appendChild(partElement);
            });
        }

        addPart(part) {
            this.selectedParts.set(part.id, part);
            this.updateSelectedPartsUI();
            this.updatePricing();
            this.updateVehicleModel(part);

            // Enable add to cart if parts are selected
            const addToCartBtn = document.getElementById('add-to-cart');
            if (addToCartBtn && this.selectedParts.size > 0) {
                addToCartBtn.disabled = false;
                addToCartBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }

        updateSelectedPartsUI() {
            const selectedPartsContainer = document.getElementById('selected-parts');
            
            if (this.selectedParts.size === 0) {
                selectedPartsContainer.innerHTML = `
                    <div class="text-platinum/60 text-sm text-center py-8">
                        <i class="bx bx-package text-3xl mb-2"></i>
                        <div>No parts selected</div>
                    </div>
                `;
                return;
            }

            selectedPartsContainer.innerHTML = '';

            this.selectedParts.forEach(part => {
                const partElement = document.createElement('div');
                partElement.className = 'flex items-center justify-between p-2 bg-background-dark rounded border border-platinum/20';
                partElement.innerHTML = `
                    <div class="flex-1">
                        <div class="text-platinum text-sm font-semibold">${part.name}</div>
                        <div class="text-accent text-xs">$${part.price.toLocaleString()}</div>
                    </div>
                    <button class="remove-part-btn text-red-400 hover:text-red-300 p-1" data-part-id="${part.id}">
                        <i class="bx bx-trash text-sm"></i>
                    </button>
                `;

                partElement.querySelector('.remove-part-btn').addEventListener('click', () => {
                    this.removePart(part.id);
                });

                selectedPartsContainer.appendChild(partElement);
            });
        }

        removePart(partId) {
            this.selectedParts.delete(partId);
            this.updateSelectedPartsUI();
            this.updatePricing();
            // TODO: Remove part from 3D model
        }

        updatePricing() {
            const basePrice = this.basePrice;
            let partsPrice = 0;
            
            this.selectedParts.forEach(part => {
                partsPrice += part.price;
            });

            const laborHours = Math.ceil(this.selectedParts.size * 2);
            const laborPrice = laborHours * this.laborRate;
            const totalPrice = basePrice + partsPrice + laborPrice;

            document.getElementById('base-price').textContent = `$${basePrice.toLocaleString()}`;
            document.getElementById('parts-price').textContent = `$${partsPrice.toLocaleString()}`;
            document.getElementById('labor-price').textContent = `$${laborPrice.toLocaleString()}`;
            document.getElementById('total-price').textContent = `$${totalPrice.toLocaleString()}`;
        }

        updateVehicleModel(part) {
            // In a real implementation, this would load the actual 3D model
            // For now, just change the color or add a simple visual indicator
            console.log('Adding part to 3D model:', part.name);
        }

        setView(viewType) {
            if (!this.controls) return;

            const positions = {
                front: { x: 0, y: 2, z: 8 },
                side: { x: 8, y: 2, z: 0 },
                rear: { x: 0, y: 2, z: -8 },
                '360': null // Will enable auto-rotate
            };

            if (viewType === '360') {
                this.controls.autoRotate = true;
                this.controls.autoRotateSpeed = 2;
            } else {
                this.controls.autoRotate = false;
                const pos = positions[viewType];
                if (pos) {
                    this.camera.position.set(pos.x, pos.y, pos.z);
                    this.controls.update();
                }
            }
        }

        changeColor(color) {
            this.currentColor = color;
            
            // Update vehicle color
            if (this.vehicle) {
                this.vehicle.traverse((child) => {
                    if (child.isMesh && child.userData.part === 'body') {
                        child.material.color.setHex(color.replace('#', '0x'));
                    }
                });
            }
        }

        changeMaterial(material) {
            this.currentMaterial = material;
            
            // Update active material button
            document.querySelectorAll('.material-btn').forEach(btn => {
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-secondary');
            });
            
            document.querySelector(`[data-material="${material}"]`).classList.remove('btn-secondary');
            document.querySelector(`[data-material="${material}"]`).classList.add('btn-primary');

            // Update vehicle material
            if (this.vehicle) {
                const materialProps = {
                    matte: { shininess: 0, roughness: 0.9 },
                    metallic: { shininess: 100, roughness: 0.1 },
                    chrome: { shininess: 200, roughness: 0.05 }
                };

                const props = materialProps[material];
                
                this.vehicle.traverse((child) => {
                    if (child.isMesh && child.material) {
                        if (child.material.shininess !== undefined) {
                            child.material.shininess = props.shininess;
                        }
                        if (child.material.roughness !== undefined) {
                            child.material.roughness = props.roughness;
                        }
                        child.material.needsUpdate = true;
                    }
                });
            }
        }

        resetView() {
            if (this.controls) {
                this.camera.position.set(5, 3, 5);
                this.controls.target.set(0, 1, 0);
                this.controls.autoRotate = false;
                this.controls.update();
            }
        }

        toggleWireframe() {
            if (this.vehicle) {
                this.vehicle.traverse((child) => {
                    if (child.isMesh) {
                        child.material.wireframe = !child.material.wireframe;
                    }
                });
            }
        }

        takeScreenshot() {
            const canvas = this.renderer.domElement;
            const link = document.createElement('a');
            link.download = 'youtune-garage-config.png';
            link.href = canvas.toDataURL();
            link.click();
        }

        async addToCart() {
            const configuration = {
                parts: Array.from(this.selectedParts.values()),
                color: this.currentColor,
                material: this.currentMaterial,
                totalPrice: this.calculateTotalPrice()
            };

            try {
                const response = await fetch('/wp-json/youtuneai/v1/garage/configure', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(configuration)
                });

                const result = await response.json();
                
                if (result.product_url) {
                    window.location.href = result.product_url;
                } else {
                    alert('Configuration saved! Redirecting to checkout...');
                }
            } catch (error) {
                console.error('Failed to add to cart:', error);
                alert('Failed to save configuration. Please try again.');
            }
        }

        calculateTotalPrice() {
            let total = this.basePrice;
            this.selectedParts.forEach(part => {
                total += part.price;
            });
            total += Math.ceil(this.selectedParts.size * 2) * this.laborRate;
            return total;
        }

        saveConfiguration() {
            const config = {
                parts: Array.from(this.selectedParts.values()),
                color: this.currentColor,
                material: this.currentMaterial,
                timestamp: Date.now()
            };

            localStorage.setItem('youtuneGarageConfig', JSON.stringify(config));
            alert('Configuration saved locally!');
        }

        shareConfiguration() {
            const config = {
                parts: Array.from(this.selectedParts.keys()),
                color: this.currentColor,
                material: this.currentMaterial
            };

            const encoded = btoa(JSON.stringify(config));
            const shareUrl = `${window.location.origin}${window.location.pathname}?config=${encoded}`;
            
            if (navigator.share) {
                navigator.share({
                    title: 'My YouTune Garage Configuration',
                    url: shareUrl
                });
            } else {
                navigator.clipboard.writeText(shareUrl);
                alert('Configuration URL copied to clipboard!');
            }
        }

        loadPreset(preset) {
            const presets = {
                sport: {
                    parts: [1, 5, 7, 11, 13], // Sport body, racing wheels, v6 turbo, racing seats, spoiler
                    color: '#FF0000',
                    material: 'metallic'
                },
                luxury: {
                    parts: [2, 6, 8, 12, 15], // Racing body, chrome wheels, v8 engine, premium interior, LED lights
                    color: '#000000',
                    material: 'chrome'
                },
                offroad: {
                    parts: [3, 4, 9, 10, 14], // Off-road body, alloy wheels, electric motor, leather seats, side skirts
                    color: '#8B4513',
                    material: 'matte'
                }
            };

            const presetConfig = presets[preset];
            if (!presetConfig) return;

            // Clear current selection
            this.selectedParts.clear();

            // Add preset parts
            presetConfig.parts.forEach(partId => {
                // Find the part in available parts
                for (const [category, parts] of this.availableParts.entries()) {
                    const part = parts.find(p => p.id === partId);
                    if (part) {
                        this.selectedParts.set(partId, part);
                        break;
                    }
                }
            });

            // Apply color and material
            this.changeColor(presetConfig.color);
            this.changeMaterial(presetConfig.material);

            // Update UI
            this.updateSelectedPartsUI();
            this.updatePricing();

            // Update color swatch selection
            document.querySelectorAll('.color-swatch').forEach(swatch => {
                swatch.classList.remove('ring-2', 'ring-primary');
                if (swatch.dataset.color === presetConfig.color) {
                    swatch.classList.add('ring-2', 'ring-primary');
                }
            });
        }

        searchParts(query) {
            if (!query.trim()) {
                // Show current category parts
                const activeCategory = document.querySelector('.part-category.bg-primary\\/20');
                if (activeCategory) {
                    this.loadPartsForCategory(activeCategory.dataset.category);
                }
                return;
            }

            const partsList = document.getElementById('parts-list');
            partsList.innerHTML = '';

            let foundParts = [];
            
            // Search all categories
            for (const [category, parts] of this.availableParts.entries()) {
                const matchingParts = parts.filter(part => 
                    part.name.toLowerCase().includes(query.toLowerCase())
                );
                foundParts = foundParts.concat(matchingParts);
            }

            if (foundParts.length === 0) {
                partsList.innerHTML = `
                    <div class="text-platinum/60 text-sm text-center py-4">
                        No parts found for "${query}"
                    </div>
                `;
                return;
            }

            foundParts.forEach(part => {
                const partElement = document.createElement('div');
                partElement.className = 'part-item p-3 rounded-lg bg-background-card border border-platinum/20 hover:bg-primary/10 hover:border-primary/30 cursor-pointer transition-all';
                partElement.innerHTML = `
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="font-semibold text-platinum text-sm">${part.name}</div>
                            <div class="text-accent text-sm">$${part.price.toLocaleString()}</div>
                        </div>
                        <button class="add-part-btn bg-primary text-white px-3 py-1 rounded text-xs hover:bg-secondary transition-colors" data-part-id="${part.id}">
                            Add
                        </button>
                    </div>
                `;

                partElement.querySelector('.add-part-btn').addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.addPart(part);
                });

                partsList.appendChild(partElement);
            });
        }

        startRenderLoop() {
            const animate = () => {
                requestAnimationFrame(animate);
                
                if (this.controls) {
                    this.controls.update();
                }

                // Update performance stats
                this.updateStats();

                this.renderer.render(this.scene, this.camera);
            };

            animate();
        }

        updateStats() {
            const fpsElement = document.getElementById('garage-fps');
            const trianglesElement = document.getElementById('garage-triangles');

            if (fpsElement) {
                // Simple FPS calculation
                const fps = Math.round(1 / this.clock.getDelta());
                fpsElement.textContent = fps;
            }

            if (trianglesElement && this.renderer.info) {
                trianglesElement.textContent = this.renderer.info.render.triangles.toLocaleString();
            }
        }

        resize() {
            const canvas = document.getElementById('garage-3d-canvas');
            if (!canvas || !this.camera || !this.renderer) return;

            const width = canvas.offsetWidth;
            const height = canvas.offsetHeight;

            this.camera.aspect = width / height;
            this.camera.updateProjectionMatrix();
            this.renderer.setSize(width, height);
        }

        destroy() {
            if (this.renderer) {
                this.renderer.dispose();
            }
            if (this.controls) {
                this.controls.dispose();
            }
        }
    }

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', () => {
        if (document.getElementById('garage-3d-canvas')) {
            window.YouTuneGarage = new YouTuneGarage();
        }
    });

    // Handle page unload
    window.addEventListener('beforeunload', () => {
        if (window.YouTuneGarage) {
            window.YouTuneGarage.destroy();
        }
    });

})();