<?php
/**
 * Fired during plugin deactivation
 *
 * @link       http://ezosc.com
 * @since      1.0.0
 *
 * @package    Seguroso
 * @subpackage Seguroso/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Seguroso
 * @subpackage Seguroso/includes
 * @author     ezosc team <suport@gmail.com.com>
 */
class Seguroso_Deactivator {
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		delete_option( 'g2Dismissed' );
		wp_clear_scheduled_hook( 'g2_notify_admin' );
		G2_SCAN_CRON::deactivated();
	}
}
