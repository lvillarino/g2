<?php
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class G2_NOTI_TABLE extends WP_List_Table {
	/** Class constructor */
	public function __construct() {
		parent::__construct( [
			'singular' => __( 'Notification', 'sp' ), //singular name of the listed records
			'plural' => __( 'Notifications', 'sp' ), //plural name of the listed records
			'ajax' => FALSE //should this table support ajax?
		] );
	}
	
	function get_columns() {
		return array(
			'cb' => __( 'ID' ),
			'id' => __( 'ID' ),
			'module' => __( 'Module' ),
			'timestamp' => __( 'Time' ),
			'detail' => __( 'Details' )
		);
	}
	
	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="g2noti[]" value="' . $item->id . '" />',
			/*$1%s*/
			$this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
			/*$2%s*/
			$item->id                //The value of the checkbox should be the record's id
		);
	}
	
	/**
	 * Prepare the table with different parameters, pagination, columns and table elements
	 */
	function prepare_items() {
		$this->process_bulk_action();
		global $wpdb, $_wp_column_headers;
		$this->_column_headers = array( $this->get_columns() );
		$screen = get_current_screen();
		/* -- Preparing your query -- */
		$query = "SELECT * FROM {$wpdb->prefix}g2_noti order by id desc";
		/* -- Pagination parameters -- */
		//Number of elements in your table?
		$totalitems = $wpdb->query( $query ); //return the total number of affected rows
		//How many to display per page?
		$perpage = 20;
		//Which page is this?
		$paged = ! empty( $_GET["paged"] ) ? $_GET["paged"] : '';
		//Page Number
		if ( empty( $paged ) || ! is_numeric( $paged ) || $paged <= 0 ) {
			$paged = 1;
		}
		//How many pages do we have in total?
		$totalpages = ceil( $totalitems / $perpage );
		//adjust the query to take pagination into account
		if ( ! empty( $paged ) && ! empty( $perpage ) ) {
			$offset = ( $paged - 1 ) * $perpage;
			$query .= ' LIMIT ' . (int) $offset . ',' . (int) $perpage;
		}
		/* -- Register the pagination -- */
		$this->set_pagination_args( array(
			"total_items" => $totalitems,
			"total_pages" => $totalpages,
			"per_page" => $perpage,
		) );
		//The pagination links are automatically built according to those parameters
		/* -- Register the Columns -- */
		$columns = $this->get_columns();
		$_wp_column_headers[ $screen->id ] = $columns;
		/* -- Fetch the items -- */
		$this->items = $wpdb->get_results( $query );
		//
	}
	
	function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'timestamp':
				return date( 'd F M H:i:s', $item->timestamp );
				break;
			case 'detail':
				?>
				<a href="<?php echo admin_url( 'admin.php?page=g2-security-notifications&&id=' . $item->id ); ?>">View Details</a>
				<?php
				break;
			default:
				return $item->{$column_name}; //Show the whole array for troubleshooting purposes
		}
	}
	
	function process_bulk_action() {
		//Detect when a bulk action is being triggered...
		if ( 'delete' === $this->current_action() ) {
			$ids = $_GET['g2noti'];
			global $wpdb;
			$deletedCount = 0;
			if ( count( array_filter( $ids ) ) > 0 ) {
				foreach ( $ids as $id ) {
					$rows = $wpdb->delete( $wpdb->prefix . 'g2_noti', array( 'id' => $id ) );
					if ( $rows > 0 ) {
						$deletedCount ++;
					}
				}
			}
			if ( $deletedCount > 0 )
				echo "<p style='padding: 10px; background-color: #42bd5e;color: #ffffff;'>$deletedCount Notifications Deleted</p>";
		}
	}
	
	function get_bulk_actions() {
		$actions = array(
			'delete' => 'Delete'
		);
		
		return $actions;
	}
}