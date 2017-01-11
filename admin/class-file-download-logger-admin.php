<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.kylegilman.net/
 * @since      1.0.0
 *
 * @package    File_Download_Logger
 * @subpackage File_Download_Logger/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    File_Download_Logger
 * @subpackage File_Download_Logger/admin
 * @author     Kyle Gilman <kylegilman@gmail.com>
 */
class File_Download_Logger_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	public function start_download() {

		global $post;
		$user = wp_get_current_user();

		if ( $_SERVER && is_array($_SERVER) && array_key_exists('REMOTE_ADDR', $_SERVER) ) {

			$ip = $_SERVER['REMOTE_ADDR'];

		}
		else {

			$ip = __('Not logged', 'file-download-logger');

		}

		if ( $user->ID != 0 && $post ) {

			$download_log = array(
				'user'   => $user->ID,
				'time'   => time(),
				'ip'     => $ip,
				'action' => 'start'
			);

			add_post_meta($post->ID, '_file_download_log', $download_log, false);

		}

	}

	public function create_log_menu() {

		add_management_page(
			_x('File Download Log', 'Tools page title', 'file-download-logger'),
			_x('File Download Log', 'Title in admin sidebar', 'file-download-logger'),
			'edit_others_posts',
			'file-download-log',
			array($this, 'log_display')
		);

	}

	public function log_display() {

		echo '<div class="wrap">';
		echo '<h1>'. __("File Download Logger", "file-download-logger") .'</h1>';

		$wp_list_table = new File_Download_Logger_List_Table();
		$wp_list_table->prepare_items();
		$wp_list_table->display();

		echo '</div>';

	}

}
