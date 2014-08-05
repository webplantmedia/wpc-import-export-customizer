<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   WPC_Import_Export_Customizer
 * @author    Chris Baldelomar <chris@webplantmedia.com>
 * @license   GPL-2.0+
 * @link      http://webplantmedia.com
 * @copyright 2014 Chris Baldelomar
 */

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
