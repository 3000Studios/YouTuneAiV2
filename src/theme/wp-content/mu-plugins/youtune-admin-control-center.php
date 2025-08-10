<?php
/**
 * Plugin Name: YouTune Admin Control Center
 * Plugin URI: https://youtuneai.com
 * Description: Central administration hub for YouTune AI platform management and operations.
 * Version: 1.0.0
 * Author: 3000Studios
 * Author URI: https://youtuneai.com
 * License: GPL v2 or later
 * Text Domain: youtune-admin-control-center
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants
define( 'YOUTUNE_ADMIN_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'YOUTUNE_ADMIN_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'YOUTUNE_ADMIN_VERSION', '1.0.0' );

/**
 * Main YouTune Admin Control Center class
 */
class YouTune_Admin_Control_Center {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Initialize the plugin
	 */
	public function init() {
		// Load required files
		$this->load_dependencies();
		
		// Initialize hooks
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		
		// Initialize AJAX handlers
		YouTune_Admin_AJAX::get_instance();
	}

	/**
	 * Load required files
	 */
	private function load_dependencies() {
		require_once YOUTUNE_ADMIN_PLUGIN_PATH . 'includes/class-youtune-admin-ajax.php';
		require_once YOUTUNE_ADMIN_PLUGIN_PATH . 'includes/class-youtune-admin-settings.php';
	}

	/**
	 * Add admin menu page
	 */
	public function add_admin_menu() {
		add_dashboard_page(
			__( 'YouTune Admin Control Center', 'youtune-admin-control-center' ),
			__( 'YouTune Admin Control Center', 'youtune-admin-control-center' ),
			'manage_options',
			'youtune-admin-control-center',
			array( $this, 'admin_page_callback' )
		);
	}

	/**
	 * Admin page callback
	 */
	public function admin_page_callback() {
		$nonce = wp_create_nonce( 'youtune_admin_nonce' );
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'YouTune Admin Control Center', 'youtune-admin-control-center' ); ?></h1>
			<div id="youtune-admin-messages"></div>
			
			<div class="youtune-admin-grid">
				<?php $this->render_action_cards( $nonce ); ?>
			</div>
		</div>

		<style>
		.youtune-admin-grid {
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
			gap: 20px;
			margin-top: 30px;
		}
		.youtube-admin-card {
			background: #fff;
			border: 1px solid #c3c4c7;
			border-radius: 4px;
			padding: 20px;
			box-shadow: 0 1px 1px rgba(0,0,0,0.04);
		}
		.youtube-admin-card h3 {
			margin-top: 0;
			margin-bottom: 15px;
			color: #1d2327;
		}
		.youtube-admin-card p {
			margin-bottom: 15px;
			color: #646970;
		}
		.youtube-admin-button {
			width: 100%;
			padding: 10px 15px;
			font-size: 14px;
			background: #2271b1;
			color: #fff;
			border: 1px solid #2271b1;
			border-radius: 3px;
			cursor: pointer;
			transition: background-color 0.2s;
		}
		.youtube-admin-button:hover {
			background: #135e96;
		}
		.youtube-admin-button:disabled {
			background: #c3c4c7;
			border-color: #c3c4c7;
			cursor: not-allowed;
		}
		.youtune-message {
			padding: 10px 15px;
			margin: 15px 0;
			border-left: 4px solid #00a32a;
			background: #f0f6fc;
			border-radius: 3px;
		}
		.youtune-message.error {
			border-left-color: #d63638;
			background: #fcf0f1;
		}
		.loading {
			opacity: 0.7;
		}
		</style>
		<?php
	}

	/**
	 * Render action cards
	 */
	private function render_action_cards( $nonce ) {
		$actions = array(
			'deploy_now' => array(
				'title' => __( 'Deploy Now', 'youtune-admin-control-center' ),
				'description' => __( 'Trigger immediate deployment of changes to production environment.', 'youtune-admin-control-center' ),
				'button_text' => __( 'Deploy Now', 'youtune-admin-control-center' ),
			),
			'seed_content' => array(
				'title' => __( 'Seed Content', 'youtune-admin-control-center' ),
				'description' => __( 'Initialize database with sample content and default configurations.', 'youtune-admin-control-center' ),
				'button_text' => __( 'Seed Content', 'youtune-admin-control-center' ),
			),
			'optimize_media' => array(
				'title' => __( 'Optimize Media', 'youtune-admin-control-center' ),
				'description' => __( 'Compress and optimize all media files for better performance.', 'youtune-admin-control-center' ),
				'button_text' => __( 'Optimize Media', 'youtune-admin-control-center' ),
			),
			'flush_caches' => array(
				'title' => __( 'Flush Caches', 'youtune-admin-control-center' ),
				'description' => __( 'Clear all caching layers including object cache, page cache, and CDN.', 'youtune-admin-control-center' ),
				'button_text' => __( 'Flush Caches', 'youtune-admin-control-center' ),
			),
			'setup_stream' => array(
				'title' => __( 'Setup Stream', 'youtune-admin-control-center' ),
				'description' => __( 'Configure streaming services and initialize broadcast settings.', 'youtune-admin-control-center' ),
				'button_text' => __( 'Setup Stream', 'youtune-admin-control-center' ),
			),
			'avatar_tune' => array(
				'title' => __( 'Avatar Tune', 'youtune-admin-control-center' ),
				'description' => __( 'Access avatar customization and AI personality tuning interface.', 'youtune-admin-control-center' ),
				'button_text' => __( 'Avatar Tune', 'youtune-admin-control-center' ),
			),
			'ads_analytics_check' => array(
				'title' => __( 'Ads & Analytics Check', 'youtune-admin-control-center' ),
				'description' => __( 'Verify advertising integrations and analytics tracking status.', 'youtune-admin-control-center' ),
				'button_text' => __( 'Check Status', 'youtune-admin-control-center' ),
			),
			'run_full_test' => array(
				'title' => __( 'Run Full Test', 'youtune-admin-control-center' ),
				'description' => __( 'Execute comprehensive system test suite across all components.', 'youtune-admin-control-center' ),
				'button_text' => __( 'Run Full Test', 'youtune-admin-control-center' ),
			),
		);

		foreach ( $actions as $action_key => $action ) {
			?>
			<div class="youtube-admin-card">
				<h3><?php echo esc_html( $action['title'] ); ?></h3>
				<p><?php echo esc_html( $action['description'] ); ?></p>
				<button 
					class="youtube-admin-button" 
					data-action="<?php echo esc_attr( $action_key ); ?>"
					data-nonce="<?php echo esc_attr( $nonce ); ?>"
					onclick="youtuneAdminAction('<?php echo esc_js( $action_key ); ?>', '<?php echo esc_js( $nonce ); ?>')">
					<?php echo esc_html( $action['button_text'] ); ?>
				</button>
			</div>
			<?php
		}
	}

	/**
	 * Enqueue admin scripts
	 */
	public function enqueue_admin_scripts( $hook ) {
		// Only load on our admin page
		if ( 'dashboard_page_youtune-admin-control-center' !== $hook ) {
			return;
		}

		wp_enqueue_script(
			'youtune-admin-js',
			YOUTUNE_ADMIN_PLUGIN_URL . 'assets/js/admin.js',
			array( 'jquery' ),
			YOUTUNE_ADMIN_VERSION,
			true
		);

		wp_localize_script( 
			'youtune-admin-js', 
			'youtuneAdmin', 
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'youtune_admin_nonce' ),
			)
		);
	}
}

// Initialize the plugin
new YouTune_Admin_Control_Center();