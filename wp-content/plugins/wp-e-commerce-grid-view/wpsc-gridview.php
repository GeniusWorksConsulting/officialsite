<?php
/*
Plugin Name: WP e-Commerce Grid View PRO
Description: WP e-Commerce Grid View Pro automatically activates the WP e-Commerce grid view feature. It also scales all product thumbnail images in grid view for a flawless product category page presentation.
Version: 2.0.0
Author: a3rev Software
Author URI: https://a3rev.com/
Text Domain: wp-e-commerce-grid-view
Domain Path: /languages
License: GPLv2 or later

	WP e-Commerce Grid View. Plugin for the WP e-Commerce PRO plugin.
	Copyright © 2011 A3 Revolution Software Development team
	
	A3 Revolution Software Development team
	admin@a3rev.com
	PO Box 1170
	Gympie 4570
	QLD Australia
*/
?>
<?php
define( 'WPSC_GRID_VIEW_FILE_PATH', dirname(__FILE__) );
define( 'WPSC_GRID_VIEW_DIR_NAME', basename(WPSC_GRID_VIEW_FILE_PATH) );
define( 'WPSC_GRID_VIEW_FOLDER', dirname(plugin_basename(__FILE__)) );
define( 'WPSC_GRID_VIEW_NAME', plugin_basename(__FILE__) );
define( 'WPSC_GRID_VIEW_URL', untrailingslashit( plugins_url( '/', __FILE__ ) ) );
define( 'WPSC_GRID_VIEW_DIR', WP_PLUGIN_DIR.'/'.WPSC_GRID_VIEW_FOLDER );

/**
 * Load Localisation files.
 *
 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
 *
 * Locales found in:
 * 		- WP_LANG_DIR/wp-e-commerce-grid-view/wp-e-commerce-grid-view-LOCALE.mo
 * 	 	- WP_LANG_DIR/plugins/wp-e-commerce-grid-view-LOCALE.mo
 * 	 	- /wp-content/plugins/wp-e-commerce-grid-view/languages/wp-e-commerce-grid-view-LOCALE.mo (which if not found falls back to)
 */
function wpsc_gridview_plugin_textdomain() {
	$locale = apply_filters( 'plugin_locale', get_locale(), 'wp-e-commerce-grid-view' );

	load_textdomain( 'wp-e-commerce-grid-view', WP_LANG_DIR . '/wp-e-commerce-grid-view/wp-e-commerce-grid-view-' . $locale . '.mo' );
	load_plugin_textdomain( 'wp-e-commerce-grid-view', false, WPSC_GRID_VIEW_FOLDER.'/languages' );
}

include 'classes/class-wpsc-gridview-hook-filter.php';
include 'admin/wpsc-gridview-admin.php';

/**
* Call when the plugin is activated
*/
register_activation_hook(__FILE__,'wpsc_gridview_install');

?>