<?php
/**
 * VR Room Page Template
 * 
 * Template Name: VR Room
 * 
 * @package YouTuneAI
 */

get_header(); ?>

<main id="main" class="min-h-screen bg-background-dark">
    <!-- VR Entry Portal -->
    <section class="min-h-screen flex items-center justify-center relative overflow-hidden">
        <!-- Background Effects -->
        <div class="absolute inset-0 bg-gradient-to-br from-purple-900/30 via-blue-900/30 to-purple-900/30"></div>
        <canvas id="vr-background-canvas" class="absolute inset-0 w-full h-full opacity-30"></canvas>

        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto">
            <!-- VR Headset Detection -->
            <div id="vr-device-status" class="mb-8">
                <div class="card-3d p-4 inline-block">
                    <div id="vr-scanning" class="flex items-center space-x-3">
                        <div class="animate-spin w-6 h-6 border-2 border-primary border-t-transparent rounded-full"></div>
                        <span class="text-platinum"><?php _e('Scanning for VR devices...', 'youtuneai'); ?></span>
                    </div>
                    
                    <div id="vr-detected" class="hidden text-accent">
                        <i class="bx bx-glasses-alt mr-2"></i>
                        <span><?php _e('VR device detected!', 'youtuneai'); ?></span>
                    </div>
                    
                    <div id="vr-not-detected" class="hidden text-platinum/60">
                        <i class="bx bx-desktop mr-2"></i>
                        <span><?php _e('Desktop mode available', 'youtuneai'); ?></span>
                    </div>
                </div>
            </div>

            <h1 class="text-6xl md:text-8xl font-orbitron font-black text-primary glow-text mb-6">
                VR Room
                <span class="block text-2xl md:text-3xl text-secondary font-raleway font-light">
                    <?php _e('Enter the Metaverse', 'youtuneai'); ?>
                </span>
            </h1>

            <p class="text-xl md:text-2xl text-platinum/80 mb-8 leading-relaxed">
                <?php _e('Immerse yourself in cutting-edge virtual reality experiences. Optimized for Quest 3 and all WebXR compatible devices.', 'youtuneai'); ?>
            </p>

            <!-- VR Room Selection -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                <?php
                $vr_rooms = get_posts(array(
                    'post_type' => 'vr_room',
                    'posts_per_page' => 6,
                    'orderby' => 'menu_order',
                    'order' => 'ASC'
                ));

                $default_rooms = array(
                    array(
                        'title' => __('Cinema Hall', 'youtuneai'),
                        'description' => __('Watch movies in a virtual cinema with 3D spatial audio', 'youtuneai'),
                        'icon' => 'bx-movie',
                        'scene' => 'cinema',
                        'color' => 'from-red-500/20 to-pink-500/20'
                    ),
                    array(
                        'title' => __('Gaming Arena', 'youtuneai'),
                        'description' => __('Multiplayer VR gaming space with spectator mode', 'youtuneai'),
                        'icon' => 'bx-joystick',
                        'scene' => 'gaming',
                        'color' => 'from-blue-500/20 to-cyan-500/20'
                    ),
                    array(
                        'title' => __('Art Gallery', 'youtuneai'),
                        'description' => __('Explore 3D art installations and digital sculptures', 'youtuneai'),
                        'icon' => 'bx-palette',
                        'scene' => 'gallery',
                        'color' => 'from-purple-500/20 to-indigo-500/20'
                    ),
                    array(
                        'title' => __('Music Venue', 'youtuneai'),
                        'description' => __('Virtual concerts and live music performances', 'youtuneai'),
                        'icon' => 'bx-music',
                        'scene' => 'music',
                        'color' => 'from-green-500/20 to-teal-500/20'
                    ),
                    array(
                        'title' => __('Space Station', 'youtuneai'),
                        'description' => __('Explore the cosmos in zero gravity environment', 'youtuneai'),
                        'icon' => 'bx-planet',
                        'scene' => 'space',
                        'color' => 'from-yellow-500/20 to-orange-500/20'
                    ),
                    array(
                        'title' => __('Meeting Room', 'youtuneai'),
                        'description' => __('Professional VR meetings and presentations', 'youtuneai'),
                        'icon' => 'bx-group',
                        'scene' => 'meeting',
                        'color' => 'from-gray-500/20 to-slate-500/20'
                    )
                );

                $rooms_to_display = !empty($vr_rooms) ? $vr_rooms : $default_rooms;

                foreach ($rooms_to_display as $index => $room) :
                    if (is_object($room)) {
                        $title = get_the_title($room->ID);
                        $description = get_the_excerpt($room->ID);
                        $scene_config = get_post_meta($room->ID, 'scene_config', true);
                        $room_id = $room->ID;
                    } else {
                        $title = $room['title'];
                        $description = $room['description'];
                        $room_id = 'default-' . $room['scene'];
                    }
                ?>
                    <div class="card-3d p-6 group hover:scale-105 transition-all duration-300 bg-gradient-to-br <?php echo $room['color'] ?? 'from-primary/10 to-secondary/10'; ?>">
                        <div class="mb-4">
                            <i class="<?php echo $room['icon'] ?? 'bx-cube'; ?> text-5xl text-primary group-hover:text-secondary transition-colors"></i>
                        </div>
                        <h3 class="text-xl font-orbitron font-bold text-primary mb-3 group-hover:text-secondary transition-colors">
                            <?php echo esc_html($title); ?>
                        </h3>
                        <p class="text-platinum/80 text-sm mb-4 line-clamp-2">
                            <?php echo esc_html($description); ?>
                        </p>
                        <button class="btn-primary w-full" data-vr-enter="<?php echo esc_attr($room_id); ?>">
                            <i class="bx bx-log-in-circle mr-2"></i>
                            <?php _e('Enter Room', 'youtuneai'); ?>
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- VR Controls Info -->
            <div class="card-3d p-6 mb-8 bg-gradient-to-r from-primary/10 to-secondary/10 border border-primary/20">
                <h3 class="text-xl font-orbitron font-bold text-primary mb-4">
                    <?php _e('VR Controls', 'youtuneai'); ?>
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-platinum/80">
                    <div class="text-center">
                        <i class="bx bx-move text-2xl text-accent mb-2"></i>
                        <div class="font-semibold"><?php _e('Movement', 'youtuneai'); ?></div>
                        <div><?php _e('Teleport or smooth locomotion', 'youtuneai'); ?></div>
                    </div>
                    <div class="text-center">
                        <i class="bx bx-hand-up text-2xl text-accent mb-2"></i>
                        <div class="font-semibold"><?php _e('Interaction', 'youtuneai'); ?></div>
                        <div><?php _e('Point and select with controllers', 'youtuneai'); ?></div>
                    </div>
                    <div class="text-center">
                        <i class="bx bx-microphone text-2xl text-accent mb-2"></i>
                        <div class="font-semibold"><?php _e('Voice Chat', 'youtuneai'); ?></div>
                        <div><?php _e('Spatial audio communication', 'youtuneai'); ?></div>
                    </div>
                </div>
            </div>

            <!-- System Requirements -->
            <div class="text-center">
                <details class="card-3d p-4 text-left">
                    <summary class="cursor-pointer text-center font-orbitron font-bold text-primary mb-4">
                        <?php _e('System Requirements & Compatibility', 'youtuneai'); ?>
                    </summary>
                    <div class="space-y-4 text-sm text-platinum/70">
                        <div>
                            <h4 class="font-semibold text-primary mb-2"><?php _e('VR Headsets', 'youtuneai'); ?></h4>
                            <ul class="list-disc list-inside space-y-1">
                                <li><?php _e('Meta Quest 2/3/Pro (Recommended)', 'youtuneai'); ?></li>
                                <li><?php _e('HTC Vive / Vive Pro', 'youtuneai'); ?></li>
                                <li><?php _e('Valve Index', 'youtuneai'); ?></li>
                                <li><?php _e('Windows Mixed Reality', 'youtuneai'); ?></li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-semibold text-primary mb-2"><?php _e('Browser Requirements', 'youtuneai'); ?></h4>
                            <ul class="list-disc list-inside space-y-1">
                                <li><?php _e('Chrome 79+ (WebXR support)', 'youtuneai'); ?></li>
                                <li><?php _e('Firefox 98+ (WebXR enabled)', 'youtuneai'); ?></li>
                                <li><?php _e('Edge 79+ (Chromium-based)', 'youtuneai'); ?></li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-semibold text-primary mb-2"><?php _e('Performance', 'youtuneai'); ?></h4>
                            <ul class="list-disc list-inside space-y-1">
                                <li><?php _e('Stable 72-90 FPS target', 'youtuneai'); ?></li>
                                <li><?php _e('Automatic quality adjustment', 'youtuneai'); ?></li>
                                <li><?php _e('Memory-optimized rendering', 'youtuneai'); ?></li>
                            </ul>
                        </div>
                    </div>
                </details>
            </div>
        </div>
    </section>
</main>

<!-- VR Session Modal -->
<div id="vr-session-modal" class="vr-overlay hidden">
    <div id="vr-session-ui" class="absolute top-8 left-8 right-8 z-50">
        <div class="flex justify-between items-center">
            <div class="card-3d p-4 bg-black/80 backdrop-blur-lg">
                <div class="flex items-center space-x-4">
                    <div id="vr-status-indicator" class="w-3 h-3 bg-accent rounded-full animate-pulse"></div>
                    <div class="text-platinum">
                        <div class="font-semibold" id="vr-room-name">VR Room</div>
                        <div class="text-sm opacity-60" id="vr-session-time">00:00</div>
                    </div>
                </div>
            </div>
            
            <div class="flex space-x-2">
                <button id="vr-settings-btn" class="card-3d p-3 bg-black/80 backdrop-blur-lg text-platinum hover:text-primary transition-colors">
                    <i class="bx bx-cog text-xl"></i>
                </button>
                <button id="vr-exit-btn" class="card-3d p-3 bg-black/80 backdrop-blur-lg text-platinum hover:text-red-400 transition-colors">
                    <i class="bx bx-exit text-xl"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- VR Canvas -->
    <canvas id="vr-canvas" class="w-full h-full"></canvas>
    
    <!-- Loading Screen -->
    <div id="vr-loading" class="absolute inset-0 bg-background-dark flex items-center justify-center">
        <div class="text-center">
            <div class="animate-spin w-16 h-16 border-4 border-primary border-t-transparent rounded-full mb-6 mx-auto"></div>
            <h2 class="text-2xl font-orbitron font-bold text-primary mb-4">
                <?php _e('Loading VR Environment', 'youtuneai'); ?>
            </h2>
            <div id="vr-loading-progress" class="w-64 bg-background-card rounded-full h-2 mx-auto mb-4">
                <div id="vr-loading-bar" class="bg-primary h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
            </div>
            <p id="vr-loading-text" class="text-platinum/60"><?php _e('Initializing...', 'youtuneai'); ?></p>
        </div>
    </div>
    
    <!-- VR Menu -->
    <div id="vr-menu" class="absolute bottom-8 left-1/2 transform -translate-x-1/2 hidden">
        <div class="card-3d p-4 bg-black/90 backdrop-blur-lg flex space-x-4">
            <button class="p-3 text-platinum hover:text-primary transition-colors" title="<?php _e('Teleport Mode', 'youtuneai'); ?>">
                <i class="bx bx-move text-xl"></i>
            </button>
            <button class="p-3 text-platinum hover:text-primary transition-colors" title="<?php _e('Voice Chat', 'youtuneai'); ?>">
                <i class="bx bx-microphone text-xl"></i>
            </button>
            <button class="p-3 text-platinum hover:text-primary transition-colors" title="<?php _e('Hand Tracking', 'youtuneai'); ?>">
                <i class="bx bx-hand-up text-xl"></i>
            </button>
            <button class="p-3 text-platinum hover:text-primary transition-colors" title="<?php _e('Screenshot', 'youtuneai'); ?>">
                <i class="bx bx-camera text-xl"></i>
            </button>
        </div>
    </div>
</div>

<script>
// VR Room JavaScript
class YouTuneAIVR {
    constructor() {
        this.isVRSupported = false;
        this.currentSession = null;
        this.vrDisplay = null;
        
        this.init();
    }
    
    async init() {
        await this.checkVRSupport();
        this.setupEventListeners();
        this.createBackgroundAnimation();
    }
    
    async checkVRSupport() {
        const scanning = document.getElementById('vr-scanning');
        const detected = document.getElementById('vr-detected');
        const notDetected = document.getElementById('vr-not-detected');
        
        try {
            if ('xr' in navigator && 'isSessionSupported' in navigator.xr) {
                const isSupported = await navigator.xr.isSessionSupported('immersive-vr');
                this.isVRSupported = isSupported;
                
                scanning.classList.add('hidden');
                if (isSupported) {
                    detected.classList.remove('hidden');
                } else {
                    notDetected.classList.remove('hidden');
                }
            } else {
                scanning.classList.add('hidden');
                notDetected.classList.remove('hidden');
            }
        } catch (error) {
            console.log('VR check failed:', error);
            scanning.classList.add('hidden');
            notDetected.classList.remove('hidden');
        }
    }
    
    setupEventListeners() {
        // VR room entry buttons
        document.querySelectorAll('[data-vr-enter]').forEach(button => {
            button.addEventListener('click', (e) => {
                const roomId = e.target.dataset.vrEnter;
                this.enterVRRoom(roomId);
            });
        });
        
        // VR exit button
        document.getElementById('vr-exit-btn').addEventListener('click', () => {
            this.exitVR();
        });
    }
    
    async enterVRRoom(roomId) {
        const modal = document.getElementById('vr-session-modal');
        const loading = document.getElementById('vr-loading');
        const canvas = document.getElementById('vr-canvas');
        
        modal.classList.remove('hidden');
        loading.classList.remove('hidden');
        
        try {
            await this.loadVRRoom(roomId);
            
            if (this.isVRSupported) {
                await this.startVRSession(canvas);
            } else {
                this.startDesktopMode(canvas);
            }
            
            loading.classList.add('hidden');
            document.getElementById('vr-menu').classList.remove('hidden');
            
        } catch (error) {
            console.error('Failed to start VR session:', error);
            this.showError('Failed to load VR room. Please try again.');
        }
    }
    
    async loadVRRoom(roomId) {
        const loadingText = document.getElementById('vr-loading-text');
        const loadingBar = document.getElementById('vr-loading-bar');
        
        const steps = [
            'Loading 3D environment...',
            'Initializing physics engine...',
            'Setting up lighting...',
            'Loading textures...',
            'Optimizing performance...',
            'Ready to enter!'
        ];
        
        for (let i = 0; i < steps.length; i++) {
            loadingText.textContent = steps[i];
            loadingBar.style.width = ((i + 1) / steps.length) * 100 + '%';
            await new Promise(resolve => setTimeout(resolve, 500));
        }
    }
    
    async startVRSession(canvas) {
        // Initialize Three.js VR scene
        // This would contain the full VR implementation
        console.log('Starting VR session...');
    }
    
    startDesktopMode(canvas) {
        // Initialize Three.js desktop scene with mouse controls
        console.log('Starting desktop VR mode...');
    }
    
    exitVR() {
        const modal = document.getElementById('vr-session-modal');
        modal.classList.add('hidden');
        
        if (this.currentSession) {
            this.currentSession.end();
            this.currentSession = null;
        }
    }
    
    createBackgroundAnimation() {
        const canvas = document.getElementById('vr-background-canvas');
        const ctx = canvas.getContext('2d');
        
        const resizeCanvas = () => {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        };
        
        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);
        
        // Simple particle animation
        const particles = Array.from({ length: 50 }, () => ({
            x: Math.random() * canvas.width,
            y: Math.random() * canvas.height,
            vx: (Math.random() - 0.5) * 0.5,
            vy: (Math.random() - 0.5) * 0.5,
            size: Math.random() * 2 + 1
        }));
        
        const animate = () => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.fillStyle = 'rgba(157, 0, 255, 0.6)';
            
            particles.forEach(particle => {
                particle.x += particle.vx;
                particle.y += particle.vy;
                
                if (particle.x < 0 || particle.x > canvas.width) particle.vx *= -1;
                if (particle.y < 0 || particle.y > canvas.height) particle.vy *= -1;
                
                ctx.beginPath();
                ctx.arc(particle.x, particle.y, particle.size, 0, Math.PI * 2);
                ctx.fill();
            });
            
            requestAnimationFrame(animate);
        };
        
        animate();
    }
    
    showError(message) {
        // Show error message to user
        console.error(message);
    }
}

// Initialize VR system when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new YouTuneAIVR();
});
</script>

<?php get_footer(); ?>