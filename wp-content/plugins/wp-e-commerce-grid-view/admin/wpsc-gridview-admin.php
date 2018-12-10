<?php
/**
 * Call this function when plugin is deactivated
 */

function wpsc_gridview_install(){
	update_option('a3rev_wpsc_gridview_version', '2.0.0');
	delete_transient("a3rev_wpsc_gridview_update_info");
	
	update_option('a3rev_wpsc_gridview_pro_just_installed', true);
}

update_option('a3rev_wpsc_gridview_plugin', 'wp-e-commerce-grid-view' );

/**
 * Load languages file
 */
function wpsc_gridview_init() {
	wpsc_gridview_plugin_textdomain();;
}

// Add language
add_action('init', 'wpsc_gridview_init');

// Add text on right of Visit the plugin on Plugin manager page
add_filter( 'plugin_row_meta', array('WPSC_GridView_Hook_Filter', 'plugin_extra_links'), 10, 2 );

add_action( 'get_header', array('WPSC_GridView_Hook_Filter','wpsc_grid_view_styles') );
add_action( 'wp_head', array('WPSC_GridView_Hook_Filter','wpsc_grid_custom_styles'), 9 );
add_filter( 'body_class', array( 'WPSC_GridView_Hook_Filter', 'browser_body_class'), 10, 2 );

add_action( 'wpsc_product_category_add_form_fields',  array('WPSC_GridView_Hook_Filter', 'wpsc_admin_category_forms_add') ); // After left-col

add_filter('wpsc_product_image', array('WPSC_GridView_Hook_Filter', 'wpsc_product_image') );

add_action('wpsc_top_of_products_page', array('WPSC_GridView_Hook_Filter', 'wpsc_category_image'), 1);

if ( !function_exists( 'product_display_list' ) ){
	function product_display_list( $product_list, $group_type, $group_sql = '', $search_sql = '' ) {
		WPSC_GridView_Hook_Filter::product_display_list( $product_list, $group_type, $group_sql , $search_sql );
	}
}

if ( !function_exists( 'product_display_grid' ) ){
	function product_display_grid( $product_list, $group_type, $group_sql = '', $search_sql = '' ) {
		WPSC_GridView_Hook_Filter::product_display_grid( $product_list, $group_type, $group_sql , $search_sql );
	}
}

update_option('a3rev_wpsc_gridview_version', '2.0.0');

?>