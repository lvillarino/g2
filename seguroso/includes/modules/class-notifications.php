<?php

class G2_NOTIFICATIONS {
	public static function add( $module, $desc, $time = NULL ) {
		if ( $time == NULL ) $time = time();
		global $wpdb;
		$wpdb->insert( $wpdb->prefix . 'g2_noti', array(
			'module' => $module,
			'desc' => $desc,
			'timestamp' => time()
		) );
		if ( $wpdb->insert_id > 0 ) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	public static function total() {
		global $wpdb;
		$sql = "Select count(*) from {$wpdb->prefix}g2_noti";
		
		return $wpdb->get_var( $sql );
	}
	
	public static function get( $perPage = 20, $offset = 0 ) {
		global $wpdb;
		$sql = "Select * from {$wpdb->prefix}g2_noti limit $perPage,$offset";
		
		return $wpdb->get_results( $sql );
	}
	
	public static function getOne( $id ) {
		global $wpdb;
		$sql = "Select * from {$wpdb->prefix}g2_noti where id= $id";
		
		return $wpdb->get_row( $sql );
	}
}