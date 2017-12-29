<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://ezosc.com
 * @since      1.0.0
 *
 * @package    Seguroso
 * @subpackage Seguroso/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Seguroso
 * @subpackage Seguroso/admin
 * @author     ezosc team <suport@gmail.com.com>
 */
class Seguroso_Admin {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;
	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;
	
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of this plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		new G2_Duplicate();
		//
		add_filter( 'manage_posts_columns', array( $this, 'notice_columns_head' ) );
		add_action( 'manage_posts_custom_column', array( $this, 'notice_columns_content' ), 10, 2 );
	}
	
	// ADD NEW COLUMN
	function notice_columns_head( $defaults ) {
		$defaults[''] = 'Featured Image';
		
		return $defaults;
	}

        // SHOW THE FEATURED IMAGE
	function notice_columns_content( $column_name, $post_ID ) {
		if ( $column_name == 'featured_image' ) {
		}
	}
	
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Seguroso_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Seguroso_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/seguroso-admin.css', array(), $this->version, 'all' );
	}
	
	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Seguroso_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Seguroso_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/seguroso-admin.js', array( 'jquery' ), $this->version, FALSE );
	}
	
	function add_menus() {
		add_menu_page( __( 'G2 Security', 'ldomain' ), __( 'G2 Security', 'ldomain' ), 'manage_options', 'g2-security-dashboard', '', plugin_dir_url( __FILE__ ) . 'images/shield.png', 55 );//
		add_submenu_page( 'g2-security-dashboard', __( 'Dashboard', 'ldomain' ), __( 'Dashboard', 'ldomain' ), 'manage_options', 'g2-security-dashboard', array( $this, 'get_menu_dashboard' ) );
		add_submenu_page( 'g2-security-dashboard', __( 'Settings', 'ldomain' ), __( 'Settings', 'ldomain' ), 'manage_options', 'g2-security-settings', array( $this, 'get_menu_settings' ) );
		add_submenu_page( 'g2-security-dashboard', __( 'G2 Goolge  Recaptcha', 'ldomain' ), __( 'Goolge Recaptcha', 'ldomain' ), 'manage_options', 'g2-security-google-recaptcha', array( $this, 'get_menu_googlerecaptcha' ) );
		add_submenu_page( 'g2-security-dashboard', __( 'Vulnerability Alerts', 'ldomain' ), __( 'Vulnerability Alerts', 'ldomain' ), 'manage_options', 'g2-vulnerability-alerts', array( $this, 'get_menu_vulnerabilityalerts' ) );
		add_submenu_page( 'g2-security-dashboard', __( 'Notifications', 'ldomain' ), __( 'Notifications', 'ldomain' ), 'manage_options', 'g2-security-notifications', array( $this, 'get_notifications' ) );
		// register notifictaions
	}
	
	// Menus Links
	function get_menu_settings() { include_once plugin_dir_path( __FILE__ ) . '/pages/settings.php'; }
	
	function get_notifications() { include_once plugin_dir_path( __FILE__ ) . '/pages/notifications.php'; }
	
	function get_menu_dashboard() {
		G2_Vulnerability_Alerts::$report = G2_Vulnerability_Alerts::get_vulnerability_alerts();
		include_once plugin_dir_path( __FILE__ ) . '/pages/dashboard.php';
	}
	
	function get_menu_googlerecaptcha() { G2_Google_Recaptcha::settings_page(); }
	
	function get_menu_vulnerabilityalerts() {    // Suppports during WP Cron		
		G2_Vulnerability_Alerts::$report = G2_Vulnerability_Alerts::get_vulnerability_alerts();
		include_once plugin_dir_path( __FILE__ ) . '/pages/vulnerabilityalerts.php';
	}
	
	function get_menu_help() { include_once plugin_dir_path( __FILE__ ) . '/pages/help.php'; }
}
