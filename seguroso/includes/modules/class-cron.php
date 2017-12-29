<?php
add_action( 'plugins_loaded', array( G2_SCAN_CRON::get_instance(), 'plugin_setup' ) );

class G2_SCAN_CRON {
	protected static $instance = NULL;
	
	public static function get_instance() {
		if ( NULL === self::$instance ) {
			self::$instance = new self;
		}
		
		return self::$instance;
	}
	
	public function plugin_setup() {
		add_action( 'g2_scan_files', array( $this, 'scan_files' ) );
		add_action( 'g2_remove_old_data', array( $this, 'remove_data' ) );
	}
	
	public function __construct() {
		//empty
	}
	
	public static function activated() {
		$config = get_option( 'g2Security' );
		wp_schedule_event( time(), 'daily', 'g2_remove_old_data' );
		wp_schedule_event( time(), $config['file_check_interval'], 'g2_scan_files' );
	}
	
	public static function deactivated() {
		wp_clear_scheduled_hook( 'g2_scan_files' );
		wp_clear_scheduled_hook( 'g2_remove_old_data' );
	}
	
	public function scan_files() {
		G2_File_Scan::scan();
	}
	
	public function remove_data() {
		global $wpdb;
		$time = strtotime( '-30 days' );
		$sql = "delete from {$wpdb->prefix}g2_noti where timestamp <= $time";
		$wpdb->query( $sql );
	}
}