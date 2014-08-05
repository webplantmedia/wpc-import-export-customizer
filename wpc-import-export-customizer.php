<?php
/*
Plugin Name: WP Canvas - Import Export Customizer
Plugin URI: http://webplantmedia.com/starter-themes/wordpresscanvas/features/plugins/wpc-import-export-customizer/
Description: Import and export customizer settings through the WordPress dashboard.
Author: Chris Baldelomar
Author URI: http://webplantmedia.com/
Version: 1.0
License: GPLv2 or later
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

if ( is_admin() ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-admin.php' );

	add_action( 'plugins_loaded', array( 'WPC_Import_Export_Customizer_Admin', 'get_instance' ) );
}
