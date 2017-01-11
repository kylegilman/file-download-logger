<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.kylegilman.net/
 * @since             0.1.0
 * @package           File_Download_Logger
 *
 * @wordpress-plugin
 * Plugin Name:       File Download Logger
 * Plugin URI:        https://www.kylegilman.net/file-download-logger
 * Description:       Logs file downloads.
 * Version:           0.1.0
 * Author:            Kyle Gilman
 * Author URI:        https://www.kylegilman.net/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       file-download-logger
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-file-download-logger-activator.php
 */
function activate_file_download_logger() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-file-download-logger-activator.php';
	File_Download_Logger_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-file-download-logger-deactivator.php
 */
function deactivate_file_download_logger() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-file-download-logger-deactivator.php';
	File_Download_Logger_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_file_download_logger' );
register_deactivation_hook( __FILE__, 'deactivate_file_download_logger' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-file-download-logger.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_file_download_logger() {

	$plugin = new File_Download_Logger();
	$plugin->run();

}
run_file_download_logger();
