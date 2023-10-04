<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://2bytecode.com/
 * @since      1.0.0
 *
 * @package    Digital_Asset_Manager
 * @subpackage Digital_Asset_Manager/public/partials
 */

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php

/**
 * Add the file for markup.
 */

$theme_file = locate_template( array( 'single-asset.php' ) );
if ( $theme_file ) {
	require_once $theme_file;
} else {
	require_once DAM_PUBLIC_PATH . 'templates/single-asset.php';
}
