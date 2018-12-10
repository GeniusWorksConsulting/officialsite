<?php 
function register_js() {
	if (!is_admin()) {
		wp_deregister_script('jquery');
		wp_register_script('jquery', get_template_directory_uri() . '/js/libs/jquery-1.6.2.min.js');
		wp_register_script('modernzr', get_template_directory_uri() . '/js/libs/modernizr-2.0.6.min.js', 'jquery');
		wp_register_script('jquery-ui', get_template_directory_uri() . '/js/libs/jquery-ui-1.8.16.min.js', 'jquery');
		wp_register_script('slides', get_template_directory_uri() . '/js/plugins/slides.min.jquery.js', 'jquery');
		wp_register_script('prettyphoto', get_template_directory_uri() . '/js/plugins/jquery.prettyPhoto.js', 'jquery');
		wp_register_script('jplayer', get_template_directory_uri() . '/js/plugins/jquery.jplayer.min.js', 'jquery');
		wp_register_script('quicksand', get_template_directory_uri() . '/js/plugins/jquery.quicksand.js', 'jquery');
		wp_register_script('gmap', get_template_directory_uri() . '/js/plugins/jquery.gmap.min.js', 'jquery');
		wp_enqueue_script('jquery');
		wp_enqueue_script('modernzr');
		wp_enqueue_script('jquery-ui');
		wp_enqueue_script('slides');
		wp_enqueue_script('prettyphoto');
		wp_enqueue_script('jplayer');
		wp_enqueue_script('quicksand');
	}
}
add_action('init', 'register_js');

if( get_option_tree ('enable_gmap', '') == "Active" ){
	function theme_add_gmap_script(){
		echo "\n<script type='text/javascript' src='http://maps.google.com/maps/api/js?sensor=false'></script>\n";
		wp_print_scripts( 'gmap');
	}
	add_filter('wp_head','theme_add_gmap_script');
}
?>