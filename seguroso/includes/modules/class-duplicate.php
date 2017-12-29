<?php

/*
 * Check if Wp has enabled anothers that doing the same as G2
 */

class G2_Duplicate {
	private $duplicatePlugins = array();
	private $installedPlugins = array(
		'Disable XML-RPC Pingback',
		'Vulnerability Alerts',
		'Vulnerable Plugin Checker',
		'WP Antivirus Site Protection (by SiteGuarding.com)',
		'6Scan Security',
		'SecureMoz Security Audit'
	);
	
	function __construct() {
		//Testing
		//update_option( 'g2Dismissed', FALSE );
		add_action( 'wp_ajax_g2_dismiss', array( $this, 'dismissNotice' ) );
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$plugins = get_plugins();
		foreach ( $plugins as $plugin ) {
			if ( in_array( $plugin['Name'], $this->installedPlugins ) ) {
				$this->duplicatePlugins[] = $plugin['Name'];
			}
		}
		if ( count( array_filter( $this->duplicatePlugins ) ) > 0 && get_option( 'g2Dismissed' ) != TRUE ) {
			add_action( 'admin_notices', array( $this, 'my_acf_admin_notice' ) );
		}
	}
	
	function dismissNotice() {
		update_option( 'g2Dismissed', TRUE );
		echo TRUE;
		die();
	}
	
	function my_acf_admin_notice() {
		?>
		<div class="notice error my-acf-notice  " id="g2-notice">
			<p><?php _e( '<b>G2 Security</b> recommend to uninstall the plugins : ' . implode( ',', $this->duplicatePlugins ), 'my-text-domain' ); ?>
				<a style="float: right;margin-top: -3px;" class="dismiss-g2-notice button button-primary" href="">Dismiss</a>
			</p>
		</div>
		<?php
	}
}