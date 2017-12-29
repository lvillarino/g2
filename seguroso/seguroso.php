<?php
/**
 * Plugin Name: G2 Security
 * Plugin URI: http://ezosc.com/
 * Description: A WordPress security plugin provides free security, protecting your website from hacks and malware.
 * Author: ezOSC
 * Author URI: http://www.ezosc.com
 * Version: 1.3.0
 * Text Domain: seguroso
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*
 * File Scan Module
 */
$uploads = wp_upload_dir();
$uploads['basedir'] = str_replace( array( '\\', '/' ), DIRECTORY_SEPARATOR, $uploads['basedir'] );
define( 'G2SECURITY_PLUGIN_FOLDER', dirname( SC_WPFMP_PLUGIN_FILE ) );
define( 'G2SECURITY_DATA_FOLDER', $uploads['basedir'] . DIRECTORY_SEPARATOR . 'G2SECURITY_DATA' . DIRECTORY_SEPARATOR );
define( 'G2SECURITY_DATA_FOLDER_OLD', G2SECURITY_PLUGIN_FOLDER . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR );
define( 'G2SECURITY_FILE_SCAN_DATA', G2SECURITY_DATA_FOLDER . '.g2security_scan_data' );
define( 'G2SECURITY_FILE_ALERT_CONTENT', G2SECURITY_DATA_FOLDER . '.g2security_admin_alert_content' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-seguroso-activator.php
 */
function activate_seguroso() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-seguroso-activator.php';
	Seguroso_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-seguroso-deactivator.php
 */
function deactivate_seguroso() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-seguroso-deactivator.php';
	Seguroso_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_seguroso' );
register_deactivation_hook( __FILE__, 'deactivate_seguroso' );


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-seguroso.php';

/**
 * Include Util Class
 */
require plugin_dir_path( __FILE__ ) . 'includes/class/class-mail.php';

/**
 * Include Modules
 */
require plugin_dir_path( __FILE__ ) . 'includes/modules/class-cron.php';
require plugin_dir_path( __FILE__ ) . 'includes/modules/class-noti-table.php';
require plugin_dir_path( __FILE__ ) . 'includes/modules/class-general.php';
require plugin_dir_path( __FILE__ ) . 'includes/modules/class-google-recaptcha.php';
require plugin_dir_path( __FILE__ ) . 'includes/modules/class-duplicate.php';
require plugin_dir_path( __FILE__ ) . 'includes/modules/class-vulnerability-alerts.php';
require plugin_dir_path( __FILE__ ) . 'includes/modules/class-google-safe-browsing.php';
require plugin_dir_path( __FILE__ ) . 'includes/modules/class-wp-updates-alerts.php';


require plugin_dir_path( __FILE__ ) . 'includes/modules/class-files-scan.php';
require plugin_dir_path( __FILE__ ) . 'includes/modules/class-notifications.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_seguroso() {
	$plugin = new Seguroso();
	G2_File_Scan::init();
	$plugin->run();
}
run_seguroso();

/*
 * Add Extra Link in Plugin Page
*/
function seguroso_extra_links( $links, $file ) {
	if ( $file == plugin_basename( __FILE__ ) ) {
		$links[] = '<a target="_blank" href="http://ezosc.om" title="We provide WP Support" style="padding:1px 5px;color:#fff;background:#feba12;border-radius:1px;">We provide WP Support</a>';
	}
	
	return $links;
}
add_filter( 'plugin_row_meta', 'seguroso_extra_links', 10, 2 );