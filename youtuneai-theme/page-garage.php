<?php
/**
 * YouTune Garage Page Template
 * 
 * Template Name: YouTune Garage
 * 
 * @package YouTuneAI
 */

get_header(); ?>

<main id="main" class="min-h-screen bg-background-dark">
    <!-- Garage Header -->
    <section class="pt-24 pb-8">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-5xl md:text-6xl font-orbitron font-black text-primary glow-text mb-4">
                YouTune Garage
                <span class="block text-2xl md:text-3xl text-secondary font-raleway font-light">
                    <?php _e('Build Your Dream Ride', 'youtuneai'); ?>
                </span>
            </h1>
            <p class="text-xl text-platinum/80 mb-8 max-w-3xl mx-auto">
                <?php _e('Design and customize your perfect vehicle with our advanced 3D configurator. See every detail in real-time and purchase parts instantly.', 'youtuneai'); ?>
            </p>
        </div>
    </section>

    <!-- Main Garage Interface -->
    <section class="pb-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 xl:grid-cols-4 gap-8">
                <!-- Parts Selector (Left Sidebar) -->
                <div class="xl:col-span-1 order-2 xl:order-1">
                    <div class="card-3d p-6 sticky top-24">
                        <h2 class="text-2xl font-orbitron font-bold text-primary mb-6 flex items-center">
                            <i class="bx bx-wrench mr-3"></i>
                            <?php _e('Parts', 'youtuneai'); ?>
                        </h2>
                        
                        <!-- Part Categories -->
                        <div class="space-y-4 mb-6">
                            <button class="part-category w-full text-left p-3 rounded-lg bg-primary/20 border border-primary/30 text-primary font-semibold" data-category="body">
                                <i class="bx bx-car mr-2"></i>
                                <?php _e('Body', 'youtuneai'); ?>
                            </button>
                            <button class="part-category w-full text-left p-3 rounded-lg bg-background-card border border-platinum/20 text-platinum hover:bg-secondary/20 hover:border-secondary/30 hover:text-secondary transition-all" data-category="wheels">
                                <i class="bx bx-circle mr-2"></i>
                                <?php _e('Wheels', 'youtuneai'); ?>
                            </button>
                            <button class="part-category w-full text-left p-3 rounded-lg bg-background-card border border-platinum/20 text-platinum hover:bg-secondary/20 hover:border-secondary/30 hover:text-secondary transition-all" data-category="engine">
                                <i class="bx bx-cog mr-2"></i>
                                <?php _e('Engine', 'youtuneai'); ?>
                            </button>
                            <button class="part-category w-full text-left p-3 rounded-lg bg-background-card border border-platinum/20 text-platinum hover:bg-secondary/20 hover:border-secondary/30 hover:text-secondary transition-all" data-category="interior">
                                <i class="bx bx-home mr-2"></i>
                                <?php _e('Interior', 'youtuneai'); ?>
                            </button>
                            <button class="part-category w-full text-left p-3 rounded-lg bg-background-card border border-platinum/20 text-platinum hover:bg-secondary/20 hover:border-secondary/30 hover:text-secondary transition-all" data-category="accessories">
                                <i class="bx bx-paint mr-2"></i>
                                <?php _e('Accessories', 'youtuneai'); ?>
                            </button>
                        </div>

                        <!-- Parts List -->
                        <div id="parts-list" class="space-y-3 max-h-64 overflow-y-auto">
                            <div class="text-platinum/60 text-sm text-center py-4">
                                <?php _e('Select a category to view parts', 'youtuneai'); ?>
                            </div>
                        </div>

                        <!-- Search Parts -->
                        <div class="mt-6">
                            <input type="text" 
                                   id="parts-search" 
                                   placeholder="<?php _e('Search parts...', 'youtuneai'); ?>" 
                                   class="w-full p-3 bg-background-dark border border-platinum/30 rounded-lg text-platinum placeholder-platinum/50 focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors text-sm">
                        </div>
                    </div>
                </div>

                <!-- 3D Configurator (Center) -->
                <div class="xl:col-span-2 order-1 xl:order-2">
                    <div class="card-3d p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-orbitron font-bold text-primary">
                                <?php _e('3D Configurator', 'youtuneai'); ?>
                            </h2>
                            
                            <!-- View Controls -->
                            <div class="flex space-x-2">
                                <button id="view-front" class="btn-secondary text-sm px-3 py-2" data-view="front">
                                    <?php _e('Front', 'youtuneai'); ?>
                                </button>
                                <button id="view-side" class="btn-secondary text-sm px-3 py-2" data-view="side">
                                    <?php _e('Side', 'youtuneai'); ?>
                                </button>
                                <button id="view-rear" class="btn-secondary text-sm px-3 py-2" data-view="rear">
                                    <?php _e('Rear', 'youtuneai'); ?>
                                </button>
                                <button id="view-360" class="btn-primary text-sm px-3 py-2" data-view="360">
                                    <i class="bx bx-rotate-right mr-1"></i>
                                    360°
                                </button>
                            </div>
                        </div>

                        <!-- 3D Viewport -->
                        <div class="relative bg-gradient-to-br from-gray-900 to-black rounded-lg overflow-hidden" style="height: 500px;">
                            <canvas id="garage-3d-canvas" class="w-full h-full"></canvas>
                            
                            <!-- Loading Overlay -->
                            <div id="garage-loading" class="absolute inset-0 bg-black/80 flex items-center justify-center">
                                <div class="text-center">
                                    <div class="animate-spin w-12 h-12 border-4 border-primary border-t-transparent rounded-full mb-4 mx-auto"></div>
                                    <p class="text-platinum"><?php _e('Loading 3D model...', 'youtuneai'); ?></p>
                                </div>
                            </div>

                            <!-- Controls Overlay -->
                            <div class="absolute bottom-4 left-4 right-4 flex justify-between items-center">
                                <div class="flex space-x-2">
                                    <button id="reset-view" class="bg-black/60 hover:bg-black/80 text-white p-2 rounded transition-colors" title="<?php _e('Reset View', 'youtuneai'); ?>">
                                        <i class="bx bx-refresh"></i>
                                    </button>
                                    <button id="toggle-wireframe" class="bg-black/60 hover:bg-black/80 text-white p-2 rounded transition-colors" title="<?php _e('Wireframe', 'youtuneai'); ?>">
                                        <i class="bx bx-shape-polygon"></i>
                                    </button>
                                    <button id="toggle-lighting" class="bg-black/60 hover:bg-black/80 text-white p-2 rounded transition-colors" title="<?php _e('Lighting', 'youtuneai'); ?>">
                                        <i class="bx bx-sun"></i>
                                    </button>
                                </div>
                                
                                <div class="bg-black/60 text-white px-3 py-1 rounded text-sm">
                                    <?php _e('Drag to rotate • Scroll to zoom', 'youtuneai'); ?>
                                </div>
                            </div>

                            <!-- Performance Stats -->
                            <div class="absolute top-4 left-4 bg-black/60 text-white px-3 py-2 rounded text-xs font-mono">
                                <div>FPS: <span id="garage-fps">60</span></div>
                                <div>Triangles: <span id="garage-triangles">0</span></div>
                            </div>
                        </div>

                        <!-- View Options -->
                        <div class="mt-4 flex justify-between items-center">
                            <div class="flex space-x-4">
                                <label class="flex items-center text-platinum text-sm">
                                    <input type="checkbox" id="show-grid" class="mr-2" checked>
                                    <?php _e('Show Grid', 'youtuneai'); ?>
                                </label>
                                <label class="flex items-center text-platinum text-sm">
                                    <input type="checkbox" id="auto-rotate" class="mr-2">
                                    <?php _e('Auto Rotate', 'youtuneai'); ?>
                                </label>
                                <label class="flex items-center text-platinum text-sm">
                                    <input type="checkbox" id="show-stats" class="mr-2" checked>
                                    <?php _e('Show Stats', 'youtuneai'); ?>
                                </label>
                            </div>
                            
                            <button id="take-screenshot" class="btn-secondary text-sm">
                                <i class="bx bx-camera mr-2"></i>
                                <?php _e('Screenshot', 'youtuneai'); ?>
                            </button>
                        </div>
                    </div>

                    <!-- Color & Material Selector -->
                    <div class="card-3d p-6 mt-6">
                        <h3 class="text-lg font-orbitron font-bold text-primary mb-4">
                            <?php _e('Colors & Materials', 'youtuneai'); ?>
                        </h3>
                        
                        <div class="grid grid-cols-8 md:grid-cols-12 gap-2">
                            <?php
                            $colors = array(
                                '#FF0000' => __('Red', 'youtuneai'),
                                '#00FF00' => __('Green', 'youtuneai'),
                                '#0000FF' => __('Blue', 'youtuneai'),
                                '#FFFF00' => __('Yellow', 'youtuneai'),
                                '#FF00FF' => __('Magenta', 'youtuneai'),
                                '#00FFFF' => __('Cyan', 'youtuneai'),
                                '#FFA500' => __('Orange', 'youtuneai'),
                                '#800080' => __('Purple', 'youtuneai'),
                                '#FFFFFF' => __('White', 'youtuneai'),
                                '#000000' => __('Black', 'youtuneai'),
                                '#C0C0C0' => __('Silver', 'youtuneai'),
                                '#FFD700' => __('Gold', 'youtuneai'),
                            );
                            
                            foreach ($colors as $hex => $name) :
                            ?>
                                <button class="color-swatch w-8 h-8 rounded-lg border-2 border-platinum/30 hover:border-primary transition-all duration-200" 
                                        style="background-color: <?php echo $hex; ?>"
                                        data-color="<?php echo $hex; ?>"
                                        title="<?php echo esc_attr($name); ?>">
                                </button>
                            <?php endforeach; ?>
                        </div>

                        <div class="mt-4">
                            <label class="block text-platinum text-sm mb-2"><?php _e('Material Finish', 'youtuneai'); ?></label>
                            <div class="grid grid-cols-3 gap-2">
                                <button class="material-btn btn-secondary text-sm" data-material="matte">
                                    <?php _e('Matte', 'youtuneai'); ?>
                                </button>
                                <button class="material-btn btn-primary text-sm" data-material="metallic">
                                    <?php _e('Metallic', 'youtuneai'); ?>
                                </button>
                                <button class="material-btn btn-secondary text-sm" data-material="chrome">
                                    <?php _e('Chrome', 'youtuneai'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Configuration Summary (Right Sidebar) -->
                <div class="xl:col-span-1 order-3">
                    <div class="card-3d p-6 sticky top-24">
                        <h2 class="text-xl font-orbitron font-bold text-primary mb-6 flex items-center">
                            <i class="bx bx-list-ul mr-3"></i>
                            <?php _e('Configuration', 'youtuneai'); ?>
                        </h2>

                        <!-- Selected Parts -->
                        <div id="selected-parts" class="space-y-3 mb-6">
                            <div class="text-platinum/60 text-sm text-center py-8">
                                <i class="bx bx-package text-3xl mb-2"></i>
                                <div><?php _e('No parts selected', 'youtuneai'); ?></div>
                            </div>
                        </div>

                        <!-- Price Summary -->
                        <div class="border-t border-platinum/20 pt-4 mb-6">
                            <div class="flex justify-between items-center text-platinum mb-2">
                                <span><?php _e('Base Model:', 'youtuneai'); ?></span>
                                <span id="base-price">$0</span>
                            </div>
                            <div class="flex justify-between items-center text-platinum mb-2">
                                <span><?php _e('Parts:', 'youtuneai'); ?></span>
                                <span id="parts-price">$0</span>
                            </div>
                            <div class="flex justify-between items-center text-platinum mb-2">
                                <span><?php _e('Labor:', 'youtuneai'); ?></span>
                                <span id="labor-price">$0</span>
                            </div>
                            <hr class="border-platinum/20 my-2">
                            <div class="flex justify-between items-center text-xl font-bold text-primary">
                                <span><?php _e('Total:', 'youtuneai'); ?></span>
                                <span id="total-price">$0</span>
                            </div>
                        </div>

                        <!-- Configuration Actions -->
                        <div class="space-y-3">
                            <button id="save-config" class="btn-secondary w-full">
                                <i class="bx bx-save mr-2"></i>
                                <?php _e('Save Configuration', 'youtuneai'); ?>
                            </button>
                            
                            <button id="share-config" class="btn-secondary w-full">
                                <i class="bx bx-share mr-2"></i>
                                <?php _e('Share', 'youtuneai'); ?>
                            </button>
                            
                            <button id="add-to-cart" class="btn-primary w-full text-lg py-3" disabled>
                                <i class="bx bx-cart mr-2"></i>
                                <?php _e('Add to Cart', 'youtuneai'); ?>
                            </button>
                        </div>

                        <!-- Preset Configurations -->
                        <div class="mt-8">
                            <h3 class="text-lg font-orbitron font-bold text-primary mb-4">
                                <?php _e('Presets', 'youtuneai'); ?>
                            </h3>
                            
                            <div class="space-y-2">
                                <button class="preset-config w-full text-left p-3 rounded-lg bg-background-card border border-platinum/20 text-platinum hover:bg-primary/20 hover:border-primary/30 hover:text-primary transition-all text-sm" data-preset="sport">
                                    <div class="font-semibold"><?php _e('Sport Package', 'youtuneai'); ?></div>
                                    <div class="text-xs opacity-60">$15,999</div>
                                </button>
                                
                                <button class="preset-config w-full text-left p-3 rounded-lg bg-background-card border border-platinum/20 text-platinum hover:bg-primary/20 hover:border-primary/30 hover:text-primary transition-all text-sm" data-preset="luxury">
                                    <div class="font-semibold"><?php _e('Luxury Package', 'youtuneai'); ?></div>
                                    <div class="text-xs opacity-60">$24,999</div>
                                </button>
                                
                                <button class="preset-config w-full text-left p-3 rounded-lg bg-background-card border border-platinum/20 text-platinum hover:bg-primary/20 hover:border-primary/30 hover:text-primary transition-all text-sm" data-preset="offroad">
                                    <div class="font-semibold"><?php _e('Off-Road Package', 'youtuneai'); ?></div>
                                    <div class="text-xs opacity-60">$18,499</div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-gradient-to-r from-primary/10 to-secondary/10">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-orbitron font-bold text-primary mb-12">
                <?php _e('Why Choose YouTune Garage?', 'youtuneai'); ?>
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <i class="bx bx-cube-alt text-6xl text-primary mb-4"></i>
                    <h3 class="text-xl font-orbitron font-bold text-primary mb-3">
                        <?php _e('Real-time 3D', 'youtuneai'); ?>
                    </h3>
                    <p class="text-platinum/80">
                        <?php _e('See every change instantly in photorealistic 3D rendering with real-time ray tracing.', 'youtuneai'); ?>
                    </p>
                </div>
                
                <div class="text-center">
                    <i class="bx bx-wrench text-6xl text-secondary mb-4"></i>
                    <h3 class="text-xl font-orbitron font-bold text-primary mb-3">
                        <?php _e('1000+ Parts', 'youtuneai'); ?>
                    </h3>
                    <p class="text-platinum/80">
                        <?php _e('Massive inventory of authentic parts from top manufacturers worldwide.', 'youtuneai'); ?>
                    </p>
                </div>
                
                <div class="text-center">
                    <i class="bx bx-credit-card text-6xl text-accent mb-4"></i>
                    <h3 class="text-xl font-orbitron font-bold text-primary mb-3">
                        <?php _e('Instant Purchase', 'youtuneai'); ?>
                    </h3>
                    <p class="text-platinum/80">
                        <?php _e('Buy what you build with secure checkout and worldwide shipping.', 'youtuneai'); ?>
                    </p>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
// Load Garage 3D System
document.addEventListener('DOMContentLoaded', function() {
    const script = document.createElement('script');
    script.src = '<?php echo YOUTUNEAI_THEME_URL; ?>/assets/js/dist/garage.js';
    document.head.appendChild(script);
});
</script>

<?php get_footer(); ?>