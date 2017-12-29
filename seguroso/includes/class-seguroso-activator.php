<?php
/**
 * Fired during plugin activation
 *
 * @link       http://ezosc.com
 * @since      1.0.0
 *
 * @package    Seguroso
 * @subpackage Seguroso/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Seguroso
 * @subpackage Seguroso/includes
 * @author     ezosc team <suport@gmail.com.com>
 */
class Seguroso_Activator {
	public static $config;
	
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		//Seguroso_Activator::createTables();
		$config = NULL;
		$settings = array(
			'gg_rpc' => 'Disable XML­RPC ',
			'gg_login_hint' => 'Disable login hints ',
			'gg_block_query' => 'Block Bad Queries ',
			'gg_remove_header' => 'Removes Header ',
			'gg_disable_file_editor' => 'Disable File Editor ',
			'gg_disable_pingback' => 'Disable XML­RPC Pingback and remove header',
			'notify_by_email' => '',
		);
		foreach ( $settings as $k => $v ) {
			$config[ $k ] = 1;
		}//
		if ( $config['file_extensions'] == '' ) {
			$config['file_extensions'] = 'jpg|jpeg|jpe|gif|png|bmp|tif|tiff|ico';
		}
		if ( $config['site_root'] == '' ) {
			$config['site_root'] = rtrim( ABSPATH, '/' );
		}
		if ( $config['file_check_method'] == '' ) {
			$config['file_check_method']['md5'] = 1;
			$config['file_check_method']['modified'] = 1;
			$config['file_check_method']['size'] = 1;
		}
		if ( $config['file_check_interval'] == '' ) {
			$config['file_check_interval'] = 'daily';
		}
		if ( $config['email'] == '' ) {
			$config['email'] = get_option( 'admin_email' );;
		}
		if ( trim( $config['virus'] ) == '' ) {
			$config['virus'] = '';
		}
		update_option( 'g2Security', $config );
		update_option( 'g2_vulnerability_check', time() );
		
                // Check that data files exist
		if ( file_exists( G2SECURITY_FILE_SCAN_DATA ) && file_exists( G2SECURITY_FILE_ALERT_CONTENT ) ) {
		}
		// Check dir exists, if not make it.
		if ( ! is_dir( G2SECURITY_DATA_FOLDER ) )
			mkdir( G2SECURITY_DATA_FOLDER );
                
		/* Register cron */
		G2_SCAN_CRON::activated();
		self::createTables();
	}
	
	public static function createTables() {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		global $wpdb;
		$tableName = $wpdb->prefix . 'g2_noti';
		$sql = "CREATE TABLE $tableName   ( `id` INT NOT NULL AUTO_INCREMENT , `timestamp` BIGINT NOT NULL , `module` VARCHAR(255) NOT NULL , `desc` TEXT NOT NULL , PRIMARY KEY (`id`)) ";
		dbDelta( $sql );
	}
}
