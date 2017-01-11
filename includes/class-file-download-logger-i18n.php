<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.kylegilman.net/
 * @since      1.0.0
 *
 * @package    File_Download_Logger
 * @subpackage File_Download_Logger/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    File_Download_Logger
 * @subpackage File_Download_Logger/includes
 * @author     Kyle Gilman <kylegilman@gmail.com>
 */
class File_Download_Logger_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'file-download-logger',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
