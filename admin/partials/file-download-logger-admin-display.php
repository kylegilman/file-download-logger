<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.kylegilman.net/
 * @since      1.0.0
 *
 * @package    File_Download_Logger
 * @subpackage File_Download_Logger/admin/partials
 */
?>

<div class="wrap">
<div id="icon-tools" class="icon32"><br /></div>
<h1><?php _e('File Download Log', 'file-download-log') ?></h1>
<p></p>
<form method="post" action="tools.php?page=file_download_log">
<?php wp_nonce_field('video-embed-thumbnail-generator-nonce','video-embed-thumbnail-generator-nonce'); ?>

<table class="widefat" id="kgvid_encode_queue_table">
	<thead>
		<td><?php _e('File', 'file-download-logger') ?></td>
		<td><?php _e('File', 'file-download-logger') ?></td>
	</thead>
	<tfoot>
		<?php echo kgvid_generate_queue_table_header(); ?>
	</tfoot>
	<tbody class="rows">
		<?php echo kgvid_generate_queue_table(); ?>
	</tbody>
</table>

<?php
