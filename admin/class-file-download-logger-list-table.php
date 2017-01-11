<?php

/**
 * Creates the log table.
 *
 * @link       https://www.kylegilman.net/
 * @since      1.0.0
 *
 * @package    File_Download_Logger
 * @subpackage File_Download_Logger/admin
 */

/**
 * Creates the log table.
 *
 * @package    File_Download_Logger
 * @subpackage File_Download_Logger/admin
 * @author     Kyle Gilman <kylegilman@gmail.com>
 */
class File_Download_Logger_List_Table extends WP_List_Table {

	function __construct() {
       parent::__construct( array(
      'singular'=> 'kg_log', //Singular label
      'plural' => 'kg_logs', //plural label, also this well be one of the table css class
      'ajax'   => true
      ) );
    }

    function get_table_classes() {
		return array( 'widefat', 'striped', $this->_args['plural'] );
	}

	function get_columns() {

		return $columns = array(
		  'col_title'=>__('Title', 'file-download-logger'),
		  'col_thumbnail'=>__('Thumbnail', 'file-download-logger'),
		  'col_user'=>__('User', 'file-download-logger'),
		  'col_ip'=>__('IP', 'file-download-logger'),
		  'col_time'=>__('Time', 'file-download-logger')
	   );

	}

	/**
	 * Decide which columns to activate the sorting functionality on
	 * @return array $sortable, the array of columns that can be sorted by the user
	 */
	public function get_sortable_columns() {
	   return $sortable = array();
	}

	/**
	 * Prepare the table with different parameters, pagination, columns and table elements
	 */
	function prepare_items() {

	   global $wpdb;
	   global $_wp_column_headers;
	   $screen = get_current_screen();



	   /* -- Ordering parameters -- */
		   //Parameters that are going to be used to order the result
		   $orderby = !empty($_GET["orderby"]) ? mysql_real_escape_string($_GET["orderby"]) : 'ASC';
		   $order = !empty($_GET["order"]) ? mysql_real_escape_string($_GET["order"]) : '';

		/* -- Preparing your query -- */
			/* $query_args = array(
				'meta_key' => '_file_download_log',
				'orderby'  => $orderby,
				'order'    => $order,
				'post_type' => 'attachment',
				'posts_per_page' => -1
			); */

			$query = "
				SELECT *
				FROM $wpdb->postmeta
				WHERE meta_key LIKE '_file_download_log'
				ORDER BY meta_id DESC
			";

	   /* -- Pagination parameters -- */
			//Number of elements in your table?
			$logs = $wpdb->get_results( $query );
			$totalitems = count($logs);
			//How many to display per page?
			$perpage = 20;
			//Which page is this?
			$paged = !empty($_GET["paged"]) ? esc_sql($_GET["paged"]) : '';
			//Page Number
			if(empty($paged) || !is_numeric($paged) || $paged<=0 ){ $paged=1; } //How many pages do we have in total?
			$totalpages = ceil($totalitems/$perpage); //adjust the query to take pagination into account
			if(!empty($paged) && !empty($perpage)){
				$offset=($paged-1)*$perpage;
				$query .= ' LIMIT '.(int)$offset.','.(int)$perpage;
				//$query_args['paged'] = $paged;
				//$query_args['posts_per_page'] = $perpage;
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
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);

	   /* -- Fetch the items -- */

	   	$result = $wpdb->get_results( $query );

	   	if ( $result ) {

	   		foreach ( $result as $result_key => $record ) {

	   			$unserialized_meta_value = unserialize($record->meta_value);

	   			foreach ( $unserialized_meta_value as $meta_key => $value ) {
	   				$result[$result_key]->$meta_key = $value;
	   			}

	   		}

	   	}

		$this->items = $result;
	}

	/**
	 * Display the rows of records in the table
	 * @return string, echo the markup of the rows
	 */
	function display_rows() {

	   //Get the records registered in the prepare_items method
	   $records = $this->items;

	   //Get the columns registered in the get_columns and get_sortable_columns methods
	   list( $columns, $hidden ) = $this->get_column_info();

	   //Loop for each record
		if ( !empty($records) ){

		foreach( $records as $rec ) {

			  //Open the line
			  echo '<tr id="record_'.$rec->meta_id.'">';
			  foreach ( $columns as $column_name => $column_display_name ) {

				 //Style attributes for each col
				 $class = "class='$column_name column-$column_name'";
				 $style = "";
				 if ( in_array( $column_name, $hidden ) ) $style = ' style="display:none;"';
				 $attributes = $class . $style;

				 //Display the cell
				 switch ( $column_name ) {
					case "col_title":
						$post = get_post($rec->post_id);
						echo '<td '.$attributes.'><a href="'.get_edit_post_link($rec->post_id).'">'.$post->post_title.'</a></td>';
						break;
					case "col_thumbnail":
						$featured_image_id = get_post_thumbnail_id($rec->post_id);
						if ( $featured_image_id) {
							$featured_image = '<img src="'.wp_get_attachment_url($featured_image_id).'" width="100">';
						}
						else {
							$featured_image = '';
						}
						echo '<td '.$attributes.'>'.$featured_image.'</td>';
						break;
					case "col_user":
						$userdata = get_userdata($rec->user);
						echo '<td '.$attributes.'>'.$userdata->user_login.' - <a href="mailto:'.$userdata->user_email.'">'.$userdata->user_email.'</a></td>';
						break;
					case "col_ip":
						echo '<td '.$attributes.'>'.$rec->ip.'</td>';
						break;
					case "col_time":
						$datetime = new DateTime( "@{$rec->time}", new DateTimeZone( 'UTC' ) );
						$datetime->setTimezone( new DateTimeZone( $this->get_timezone_string() ) );
						$localtime = $rec->time + $datetime->getOffset();
						echo '<td '.$attributes.'>'.date_i18n(get_option( 'date_format' ), $localtime).' '.date_i18n(get_option( 'time_format' ), $localtime).'</td>';
						break;
				 }
			  }

			  //Close the line
			  echo'</tr>';
		   }
	   }
	}

	/**
	 * Returns the timezone string for a site, even if it's set to a UTC offset
	 *
	 * Adapted from http://www.php.net/manual/en/function.timezone-name-from-abbr.php#89155
	 *
	 * @return string valid PHP timezone string
	 */
	private function get_timezone_string() {

		// if site timezone string exists, return it
		if ( $timezone = get_option( 'timezone_string' ) )
			return $timezone;

		// get UTC offset, if it isn't set then return UTC
		if ( 0 === ( $utc_offset = get_option( 'gmt_offset', 0 ) ) )
			return 'UTC';

		// adjust UTC offset from hours to seconds
		$utc_offset *= 3600;

		// attempt to guess the timezone string from the UTC offset
		if ( $timezone = timezone_name_from_abbr( '', $utc_offset, 0 ) ) {
			return $timezone;
		}

		// last try, guess timezone string manually
		$is_dst = date( 'I' );

		foreach ( timezone_abbreviations_list() as $abbr ) {
			foreach ( $abbr as $city ) {
				if ( $city['dst'] == $is_dst && $city['offset'] == $utc_offset )
					return $city['timezone_id'];
			}
		}

		// fallback to UTC
		return 'UTC';
	}

}
