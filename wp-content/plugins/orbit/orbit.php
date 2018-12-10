<?php
/*
Plugin Name: Orbit
Plugin URI: http://www.truethemes.net
Description: A Visual Composer Add-on by TrueThemes. Includes stunning Elements such as Animated Circle Loaders, Progress Bars, Pricing Boxes, Vector Icons and many more.
Version: 1.6
Author: TrueThemes
Author URI: http://www.truethemes.net
License: GPLv2 or later
*/

// Don't load directly
if (!defined('ABSPATH')){die('-1');}


 // Make plugin available for translation
function orbit_load_textdomain() {
  load_plugin_textdomain( 'tt_orbit', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
}
add_action( 'plugins_loaded', 'orbit_load_textdomain' );

/*
* @since version 1.6 
* define plugin version number to be shown on reduxframework option panel header.
*/
define('ORBIT_PLUGIN_VERSION_NUMBER','1.6');


/*
* @since version 1.6 
* load reduxframework
*
* IMPORTANT NOTE
* in orbit/admin/redux-framework/ReduxCore/framework.php line 67
* the welcome.php was commented out to remove reduxframework welcome page.
* need to redo this if you update reduxframework.
*/
if ( !class_exists( 'ReduxFramework' ) && file_exists( dirname( __FILE__ ) . '/admin/redux-framework/ReduxCore/framework.php' ) ) {
    require_once( dirname( __FILE__ ) . '/admin/redux-framework/ReduxCore/framework.php' );
}


/*
* @since version 1.6 
* load orbit admin settings
*/
if ( !isset( $redux_demo ) && file_exists( dirname( __FILE__ ) . '/admin/orbit-redux-config.php' ) ) {
    require_once( dirname( __FILE__ ) . '/admin/orbit-redux-config.php' );
}


/*
* @since version 1.6 
* as per this doc. https://docs.reduxframework.com/core/the-basics/removing-demo-mode-and-notices/
*/
function orbit_remove_redux_demo_mode_link() { // Be sure to rename this function to something more unique
    if ( class_exists('ReduxFrameworkPlugin') ) {
        remove_filter( 'plugin_row_meta', array( ReduxFrameworkPlugin::get_instance(), 'plugin_metalinks'), null, 2 );
    }
    if ( class_exists('ReduxFrameworkPlugin') ) {
        remove_action('admin_notices', array( ReduxFrameworkPlugin::get_instance(), 'admin_notices' ) );    
    }
}
add_action('init', 'orbit_remove_redux_demo_mode_link');


/*
* @since version 1.6 
* load wp-updates-plugin
*/
require_once( dirname( __FILE__ ) . '/admin/wp-updates-plugin.php' );
$orbit_option = get_option('orbit_option');
$license_key = $orbit_option['item-purchase-code'];
if($license_key != ''){
new WPUpdatesPluginUpdater_1304( 'http://wp-updates.com/api/2/plugin', plugin_basename(__FILE__),$license_key );
}

/*
* Add parameter orbit_note
* @since 1.2
*/
if(function_exists('vc_add_shortcode_param')): //added by denzel to prevent fatal error if jscomposer is not activated!.
	function orbit_add_param_settings_field($settings, $value) {
	   $dependency = vc_generate_dependencies_attributes($settings);
	   return '<div class="vision-notification  tip"><p style="font-size:12px;"><strong>Tip!</strong> '.$value.'</p></div>';
	}
	vc_add_shortcode_param('orbit_note', 'orbit_add_param_settings_field');
endif;



//Load Google Font
function orbit_montserrat_font_url() {
	$font_url = '';
	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Montserrat, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Montserrat font: on or off', 'tt_orbit' ) ) {
		$font_url = add_query_arg( 'family', urlencode( 'Montserrat:400,500,700' ), "//fonts.googleapis.com/css" );
		$font_url = esc_url($font_url);//escaped to prevent XSS
	}

	return $font_url;
}


//Load Google Font
function orbit_open_sans_font_url() {
	$font_url = '';
	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Open Sans, translate this to 'off'. Do not translate into your own language.
	 *
	 * Do not add a plus sign to open sans, it should be a blank space, the add_query_arg will add the plus sign.
	 */
	if ( 'off' !== _x( 'on', 'Open Sans font: on or off', 'tt_orbit' ) ) {
		$font_url = add_query_arg( 'family', urlencode( 'Open Sans:400,300' ), "//fonts.googleapis.com/css" );
		$font_url = esc_url($font_url);//escaped to prevent XSS
	}

	return $font_url;
}


//Enqueue Google Fonts
function orbit_truethemes_fonts() {
	wp_enqueue_style( 'orbit-montserrat-font', orbit_montserrat_font_url(), array(), null );
	wp_enqueue_style( 'orbit-open-sans-font', orbit_open_sans_font_url(), array(), null );

}
add_action( 'wp_enqueue_scripts', 'orbit_truethemes_fonts' );


// Create Thumbnails for Testimonial Sliders
add_theme_support('post-thumbnails' );
add_image_size( 'testimonial-user', 71, 71, true );
add_image_size( 'testimonial-user-2', 36, 36, true );


// Function to generate random ID for usage in tabs shortcode
function orbit_truethemes_random() {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 8; $i++) {
        $n = mt_rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}


// Load CSS into backend for "live preview" in WP Editor
function orbit_wp_admin_style() {
	wp_enqueue_style( 'font-awesome',  plugins_url('css/font-awesome.min.css', __FILE__) );
	wp_enqueue_style( 'orbit-backend', plugins_url('css/orbit-backend-editor.css', __FILE__) );
}
add_action( 'admin_enqueue_scripts', 'orbit_wp_admin_style' );



/**
 *
 * Do not move or remove this variable,
 * it is a global variable and needs to be outside any function or class
 * for use with Dynamic CSS Function.
 *
 * @since Orbit 1.0
 */
if( !isset( $orbit_css_array ) ) {
$orbit_css_array = array();
}


// Extend Visual Composer
class OrbitVCExtendAddonClass {
    function __construct() {
        // Safely integrate with VC using this hook
        add_action( 'init', array( $this, 'integrateWithVC' ) );
 
        // Add the new shortcodes
 		add_shortcode( 'orbit_accordion'             , array( $this, 'render_orbit_accordion' ) );
 		add_shortcode( 'orbit_accordion_panel'       , array( $this, 'render_orbit_accordion_panel' ) );
 		add_shortcode( 'orbit_alert'                 , array( $this, 'render_orbit_alert' ) );
 		add_shortcode( 'orbit_button'                , array( $this, 'render_orbit_button' ) );
 		add_shortcode( 'orbit_content_box'           , array( $this, 'render_orbit_content_box' ) );
 		add_shortcode( 'orbit_circle_loader'         , array( $this, 'render_orbit_circle_loader' ) );
 		add_shortcode( 'orbit_circle_loader_icon'    , array( $this, 'render_orbit_circle_loader_icon' ) );
 		add_shortcode( 'orbit_dropcap'               , array( $this, 'render_orbit_dropcap' ) );
 		add_shortcode( 'orbit_features'              , array( $this, 'render_orbit_features' ) );
 		add_shortcode( 'orbit_heading'               , array( $this, 'render_orbit_heading' ) );
 		add_shortcode( 'orbit_icon_box'              , array( $this, 'render_orbit_icon_box' ) );
 		add_shortcode( 'orbit_icon_content'          , array( $this, 'render_orbit_icon_content' ) );
 		add_shortcode( 'orbit_icon_png'              , array( $this, 'render_orbit_icon_png' ) );
 		add_shortcode( 'orbit_imagebox_1'            , array( $this, 'render_orbit_imagebox_1' ) );
 		add_shortcode( 'orbit_imagebox_2'            , array( $this, 'render_orbit_imagebox_2' ) );
 		add_shortcode( 'orbit_number_counter'        , array( $this, 'render_orbit_number_counter' ) );
 		add_shortcode( 'orbit_pricing_box'           , array( $this, 'render_orbit_pricing_box_1' ) );
 		add_shortcode( 'orbit_progress_bar'          , array( $this, 'render_orbit_progress_bar' ) );
 		add_shortcode( 'orbit_progress_bar_vertical' , array( $this, 'render_orbit_progress_bar_vertical' ) );
 		add_shortcode( 'orbit_services'              , array( $this, 'render_orbit_services' ) );
 		add_shortcode( 'orbit_social'                , array( $this, 'render_orbit_social' ) );
 		add_shortcode( 'orbit_tab_1'                 , array( $this, 'render_orbit_tab_1' ) );
 		add_shortcode( 'orbit_tab_1_content'         , array( $this, 'render_orbit_tab_1_content' ) );
 		add_shortcode( 'orbit_tab_2'                 , array( $this, 'render_orbit_tab_2' ) );
 		add_shortcode( 'orbit_tab_2_content'         , array( $this, 'render_orbit_tab_2_content' ) );
 		add_shortcode( 'orbit_tab_3'                 , array( $this, 'render_orbit_tab_3' ) );
 		add_shortcode( 'orbit_tab_3_content'         , array( $this, 'render_orbit_tab_3_content' ) );
 		add_shortcode( 'orbit_testimonial_1'         , array( $this, 'render_orbit_testimonial_1' ) );
 		add_shortcode( 'orbit_testimonial_1_slide'   , array( $this, 'render_orbit_testimonial_1_slide' ) );
 		add_shortcode( 'orbit_testimonial_2'         , array( $this, 'render_orbit_testimonial_2' ) );
 		add_shortcode( 'orbit_testimonial_2_slide'   , array( $this, 'render_orbit_testimonial_2_slide' ) );
 		/*
 		 * Disable elements below
 		 * until further development
 		 *
 		 * add_shortcode( 'orbit_google_map'            , array( $this, 'render_orbit_google_map' ) );
 		 * add_shortcode( 'orbit_grow_boxes'            , array( $this, 'render_orbit_grow_boxes' ) );
 		 * add_shortcode( 'orbit_single_grow_box'       , array( $this, 'render_orbit_single_grow_box' ) );
 		 *
 		 * add_action( 'admin_enqueue_scripts', array( $this, 'orbit_enqueue_admin_script' ) );
 		 */
            
        // Register CSS and JS (these are Enqueued near bottom of this file)
        add_action( 'wp_enqueue_scripts', array( $this, 'orbit_enqueue_script' ) );

        // Print dynamic CSS code in footer
        add_action( 'wp_footer', array( $this, 'orbit_dynamic_hook_embed_css' ) );
    }

/**
 * Dynamic CSS Function
 *
 * Prints dynamic css so that styles are not nested inline
 * and site remains HTML 5 compatible.
 *
 * @since Orbit 1.0
 */

// Prepares CSS into global array for printing in footer
// Removes duplicated set of style codes
public function orbit_dynamic_embed_css($style_code){
global $orbit_css_array;
    if(!in_array($style_code,$orbit_css_array)){
		array_push($orbit_css_array,$style_code);
    }
}
// Generate the CSS
public function orbit_dynamic_hook_embed_css(){
global $orbit_css_array;
    if(!empty($orbit_css_array)){
	    $code ="\n<!--dynamic styles generated by orbit plugin-->";
	    ///scoped attribute is needed to be html 5 valid, I do not know what it means..
	    $code .= "<style type='text/css' scoped>";
       foreach($orbit_css_array as $style_code){
        $code .= $style_code."\n";
       }
	    $code .="</style>\n";
	    echo $code;
    }
} // END Dynamic CSS Function


public function integrateWithVC() {
	// Check for VC
	if ( ! defined( 'WPB_VC_VERSION' ) ) {
	    // Alert VC is required
	    add_action('admin_notices', array( $this, 'showVcVersionNotice' ));
	    return;
}

/*
* The vc_icon.php and vc_empty_space.php in existing js_composer has unnecessary carriage return, causing truethemes_formatter's wpautop of karma theme to break it!
* so we had to copy the original and place it into karma_builder plugin for overwriting, removing the unnecessary empty spacing! 
* we fix only the shortcode output template and nothing else...
*/
$karma_vc_icon_map = array (
	'html_template' => dirname(__FILE__) . '/vc_templates/vc_icon.php'
);
vc_map_update('vc_icon', $karma_vc_icon_map);

$karma_vc_empty_space_map = array (
	'html_template' => dirname(__FILE__) . '/vc_templates/vc_empty_space.php'
);
vc_map_update('vc_empty_space', $karma_vc_empty_space_map);

$karma_vc_text_separator_map = array (
	'html_template' => dirname(__FILE__) . '/vc_templates/vc_text_separator.php'
);
vc_map_update('vc_text_separator', $karma_vc_text_separator_map);

$karma_vc_toggle_map = array (
	'html_template' => dirname(__FILE__) . '/vc_templates/vc_toggle.php'
);
vc_map_update('vc_toggle', $karma_vc_toggle_map);

$karma_vc_image_carousel_map = array (
	'html_template' => dirname(__FILE__) . '/vc_templates/vc_image_carousel.php'
);
vc_map_update('vc_images_carousel', $karma_vc_image_carousel_map);

$karma_vc_pie_map = array (
	'html_template' => dirname(__FILE__) . '/vc_templates/vc_pie.php'
);
vc_map_update('vc_pie',$karma_vc_pie_map);

$karma_vc_cta_map = array (
	'html_template' => dirname(__FILE__) . '/vc_templates/vc_cta.php'
);
vc_map_update('vc_cta',$karma_vc_cta_map);

$karma_vc_basic_grid_map = array (
	'html_template' => dirname(__FILE__) . '/vc_templates/vc_basic_grid.php'
);
vc_map_update('vc_basic_grid',$karma_vc_basic_grid_map);//uses the same template
vc_map_update('vc_media_grid',$karma_vc_basic_grid_map);//uses the same template
vc_map_update('vc_masonry_grid',$karma_vc_basic_grid_map);//uses the same template
vc_map_update('vc_masonry_media_grid',$karma_vc_basic_grid_map);//uses the same template

/* 
$karma_vc_icon_map = array (
	'html_template' => dirname(__FILE__) . '/vc_templates/vc_icon.php'
);
vc_map_update('vc_icon', $karma_vc_icon_map);

$karma_vc_empty_space_map = array (
	'html_template' => dirname(__FILE__) . '/vc_templates/vc_empty_space.php'
);
vc_map_update('vc_empty_space', $karma_vc_empty_space_map);

$karma_vc_text_separator_map = array (
	'html_template' => dirname(__FILE__) . '/vc_templates/vc_text_separator.php'
);
vc_map_update('vc_text_separator', $karma_vc_text_separator_map);

$karma_vc_toggle_map = array (
	'html_template' => dirname(__FILE__) . '/vc_templates/vc_toggle.php'
);
vc_map_update('vc_toggle', $karma_vc_toggle_map);

$karma_vc_image_carousel_map = array (
	'html_template' => dirname(__FILE__) . '/vc_templates/vc_image_carousel.php'
);
vc_map_update('vc_images_carousel', $karma_vc_image_carousel_map);

$karma_vc_cta_map = array (
	'html_template' => dirname(__FILE__) . '/vc_templates/vc_cta.php'
);
vc_map_update('vc_cta',$karma_vc_cta_map);

$karma_vc_basic_grid_map = array (
	'html_template' => dirname(__FILE__) . '/vc_templates/vc_basic_grid.php'
);
vc_map_update('vc_basic_grid',$karma_vc_basic_grid_map);//uses the same template
vc_map_update('vc_media_grid',$karma_vc_basic_grid_map);//uses the same template
vc_map_update('vc_masonry_grid',$karma_vc_basic_grid_map);//uses the same template
vc_map_update('vc_masonry_media_grid',$karma_vc_basic_grid_map);//uses the same template
*/

/**
 * Map the Orbit Shortcodes
 *
 * Lets call vc_map function to "register" our custom shortcode
 * within Visual Composer interface.
 *
 * More info: http://kb.wpbakery.com/index.php?title=Vc_map
 *
 * @since Orbit 1.0
 */
/*--------------------------------------------------------------
Orbit - Accordion
--------------------------------------------------------------*/
vc_map( array(
	'category'                => __('Orbit', 'tt_orbit'),
	'name'                    => __("Accordion", 'tt_orbit'),
	'description'             => __("Collapsible content panels", 'tt_orbit'),
	'base'                    => "orbit_accordion",
	'controls'                => 'full',
	'class'                   => 'orbit-accordion',
	'show_settings_on_create' => true,
	'content_element'         => true,
	'js_view'                 => 'VcColumnView',
    'icon'        => plugins_url('images/backend-editor/orbit-menu-accordion.png', __FILE__),
    "as_parent"   => array('only' => 'orbit_accordion_panel'),         
    "params"      => array(
				        array(
			              	  'group'         => __('Design', 'tt_orbit'),
			              	  'heading'       => __("Padding", 'tt_orbit'),
			                  'type'          => 'textfield',
			                  'holder'        => 'div',
			                  'param_name'    => "panel_padding",
			                  'value'         => "20px",
			                  'description'   => __('The vertical padding within each section title', 'tt_orbit')
			              ),			              
				        array(
			              	  'group'         => __('Design', 'tt_orbit'),
			              	  'heading'       => __("Gradient Color (top)", 'tt_orbit'),
			                  'type'          => 'colorpicker',
			                  'holder'        => 'div',
			                  'param_name'    => "gradient_top",
			                  'value'         => "#fff",
			                  'description'   => __('The top gradient color of each section title', 'tt_orbit')
			              ),
				        array(
			              	  'group'         => __('Design', 'tt_orbit'),
			              	  'heading'       => __("Gradient Color (bottom)", 'tt_orbit'),
			                  'type'          => 'colorpicker',
			                  'holder'        => 'div',
			                  'param_name'    => "gradient_bottom",
			                  'value'         => "#efefef",
			                  'description'   => __('The bottom gradient color of each section title', 'tt_orbit')
			              ),
				        array(
			              	  'group'         => __('Design', 'tt_orbit'),
			              	  'heading'       => __("Border Color", 'tt_orbit'),
			                  'type'          => 'colorpicker',
			                  'holder'        => 'div',
			                  'param_name'    => "panel_border",
			                  'value'         => "#e1e1e1",
			                  'description'   => __('The 1px border around each section title', 'tt_orbit')
			              ),
				        array(
			              	  'group'         => __('Design', 'tt_orbit'),
			              	  'heading'       => __("Section Title Color", 'tt_orbit'),
			                  'type'          => 'colorpicker',
			                  'holder'        => 'div',
			                  'param_name'    => "title_color",
			                  'value'         => "#666"
			              ),
				        array(
			              	  'group'         => __('Design', 'tt_orbit'),
			              	  'heading'       => __("Section Title Color (active state)", 'tt_orbit'),
			                  'type'          => 'colorpicker',
			                  'holder'        => 'div',
			                  'param_name'    => "title_color_active",
			                  'value'         => "#88BBC8"
			              ),
			              array(
			               	  'group'       => __('CSS Class', 'tt_orbit'),
			                  'type'        => 'textfield',
			                  'holder'      => 'div',
			                  'heading'     => __('CSS class name', 'tt_orbit'),
			                  'param_name'  => 'custom_css_class',
			                  'description' => __('Give this element an extra CSS class name if you wish to refer to it in a CSS file. (optional)', 'tt_orbit')
			              ),
				    ),
		) 
);// END vc_map

// Map the accordion content
   vc_map( array(
   		'category'                => __('Orbit', 'tt_orbit'),
   		'name'                    => __("Accordion Panel", 'tt_orbit'),
   		'description'             => __('Add an accordion panel', 'tt_orbit'),
   		'base'                    => "orbit_accordion_panel",
   		'controls'                => 'full',
   		'content_element'         => true,
   		'show_settings_on_create' => true,
        'icon'        => plugins_url('images/backend-editor/orbit-menu-accordion.png', __FILE__),
        "as_child"    => array('only' => 'orbit_accordion'),          
        "params" => array(
			              array(
			              		'heading'       => __("Title", 'tt_orbit'),
					            'type'          => "textfield",
					            'holder'        => 'div',
					            'param_name'    => "title",
					            'description'   => __('This title is shown before the user clicks the sliding panel', 'tt_orbit')
			              ),	  
			              array(
								'heading'    => __('Content', 'tt_orbit'),
								'type'       => 'textarea_html',
								'param_name' => 'content',
								'value'      => __("<h3>Heading</h3><p>Lorem ipsum dolor ante venenatis dapibus posuere.</p>", 'tt_orbit')
							),
							array(
        				  		'heading'       => __("Open by Default?", 'tt_orbit'),
					            'type'          => 'dropdown',
					            'param_name'    => "panel_active",
					            'value'         => array(
											'False'  => 'false',
											'True' => 'true',
											),
							    'save_always' => true,
					            'description' => __("If True this panel will be open by default", 'tt_orbit')
					      ),					            				              						              						           
        		)
			) 
	);// END vc_map

/*--------------------------------------------------------------
Orbit - Alert Box
--------------------------------------------------------------*/
vc_map( array(
		/**
		 * important:
		 *
		 * 'admin_enqueue_css/js' added to this vc_map to load
		 * custom CSS file for backend-editor styling.
		 * only needs to be loaded this one time.
		 *
		 * @since Orbit 1.0
		 */
		'admin_enqueue_css' => plugins_url('css/orbit.css', __FILE__), //main stylesheet
		'admin_enqueue_js'  => plugins_url('js/orbit-backend-editor.js', __FILE__), //custom backend JS
		'category'    => __('Orbit', 'tt_orbit'),
        'name'        => __("Alert Box", 'tt_orbit'),
        'description' => __("Stylish notification message", 'tt_orbit'),
        'base'        => "orbit_alert",
        'controls'    => 'full',
        'class'       => 'orbit-alert-box',
        'js_view'     => 'OrbitAlertBox',
        'icon'        => plugins_url('images/backend-editor/orbit-menu-alert-box.png', __FILE__),
        "params"      => array(
			               array(
			                  'type'       => 'dropdown',
			                  'holder'     => 'div',
			                  'heading'    => __("Design style", 'tt_orbit'),
			                  'param_name' => "style",
			                  'value'      => array(
											'Success' => 'success',
											'Error'   => 'error',
											'Warning' => 'warning',
											'Tip'     => 'tip',
											'Neutral' => 'neutral',
											),
							   'save_always' => true,
			              ),
			              array(
			                  'type'       => "textfield",
			                  'holder'     => 'div',
			                  'heading'    => __("Font size", 'tt_orbit'),
			                  'param_name' => "font_size",
			                  'value'      => "12px",
			              ),
			              array(
			                  'type'          => "textarea_html",
			                  'holder'        => 'div',
			                  'heading'       => __("Alert text", 'tt_orbit'),
			                  'param_name'    => 'content',
			                  'value'         => "Edit this text with a custom message.",
			              ),
			               array(
			                  'type'       => 'dropdown',
			                  'holder'     => 'div',
			                  'heading'    => __("Closeable?", 'tt_orbit'),
			                  'param_name' => "closeable",
			                  'value'      => array(
											'True'  => 'true',
											'False' => 'false',
											),
							  'save_always' => true,
			                  'description' => __('Select True to make this box closeable by the user.', 'tt_orbit')
			              ),
			               array(
			               	  'group'       => __('CSS Class', 'tt_orbit'),
			                  'type'        => 'textfield',
			                  'holder'      => 'div',
			                  'heading'     => __('CSS class name', 'tt_orbit'),
			                  'param_name'  => 'custom_css_class',
			                  'description' => __('Give this element an extra CSS class name if you wish to refer to it in a CSS file. (optional)', 'tt_orbit')
			              ),
			          )
			) 
	);// END vc_map

/*--------------------------------------------------------------
Orbit - Button
--------------------------------------------------------------*/
vc_map(      
	array(
        'name'        => __("Button", 'tt_orbit'),
        'description' => __("Eye catching button", 'tt_orbit'),
        'base'        => "orbit_button",
        'controls'    => 'full',
        'class'       => 'orbit-button',
        'icon'        => plugins_url('images/backend-editor/orbit-menu-button.png', __FILE__),
        'category'    => __('Orbit', 'tt_orbit'),
        'js_view'     => 'OrbitButtonView',
        'custom_markup' => 'Button',
        "params"      => array(
			              array(
			                  'type'       => "textfield",
			                  'group'      => __('General', 'tt_orbit'),
			                  'holder'     => 'a',
			                  'heading'    => __("Text on the button", 'tt_orbit'),
			                  'param_name' => 'content',
			                  'value'      => "Text on the button",
			                  'description' => __("Text on the button", 'tt_orbit')
			              ),
			               array(
			                  'type'       => 'dropdown',
			                  'group'      => __('General', 'tt_orbit'),
			                  'holder'     => 'div',
			                  'heading'    => __("Color", 'tt_orbit'),
			                  'param_name' => "color",
			                  'value' => array(
									'Autumn'     => 'autumn',
									'Black'      => 'black',
									'Black 2'    => 'black-2',
									'Blue'       => 'blue',
									'Blue Grey'  => 'blue-grey',
									'Cool Blue'  => 'cool-blue',
									'Coffee'     => 'coffee',
									'Fire'       => 'fire',
									'Golden'     => 'golden',
									'Green'      => 'green',
									'Green 2'    => 'green-2',
									'Grey'       => 'grey',
									'Lime Green' => 'lime-green',
									'Navy'       => 'navy',
									'Orange'     => 'orange',
									'Periwinkle' => 'periwinkle',
									'Pink'       => 'pink',
									'Purple'     => 'purple',
									'Purple 2'   => 'purple-2',
									'Red'        => 'red',
									'Red 2'      => 'red-2',
									'Royal Blue' => 'royal-blue',
									'Silver'     => 'silver',
									'Sky Blue'   => 'sky-blue',
									'Teal Grey'  => 'teal-grey',
									'Teal'       => 'teal',
									'Teal 2'     => 'teal-2',
									'White'      => 'white',
									),
									'save_always' => true,
			                  'description' => __('<a href="http://s3.truethemes.net/plugin-assets/shortcode-style-guide/style-guide.html" target="_blank">View available colors &rarr;</a>', 'tt_orbit')
			              ),
							array(
			                  'type'       => 'dropdown',
			                  'group'      => __('General', 'tt_orbit'),
			                  'holder'     => 'div',
			                  'heading'    => __("Size", 'tt_orbit'),
			                  'param_name' => "size",
			                  'value' => array('Small'=> 'small','Large'=> 'large','Jumbo'=> 'jumbo'),
			                  'save_always' => true,
			              ),
							array(
							  'group'       => __('URL (link)', 'tt_orbit'),
			                  'type'        => 'vc_link',
			                  'holder'      => 'div',
			                  //'heading'   => ** this is empty for cleaner user-interface
			                  'param_name'  => 'url',
			                  'description' => __('Click "Select URL" to link this element. (optional)', 'tt_orbit')
			              ),
			              array(
			                  'type'        => 'textfield',
			                  'group'       => __('Lightbox', 'tt_orbit'),
			                  'holder'      => 'div',
			                  'heading'     => __('Lightbox', 'tt_orbit'),
			                  'param_name'  => "lightbox_content",
			                  'description' => __('Display content inside a lightbox by entering the URL here. This will override any URL (link) settings on the previous tab. <a href="https://s3.amazonaws.com/Plugin-Vision/lightbox-samples.html" target="_blank">Lightbox content samples &rarr;</a>', 'tt_orbit')
			              ),  
			              array(
			                  'type'        => 'textfield',
			                  'group'       => __('Lightbox', 'tt_orbit'),
			                  'holder'      => 'div',
			                  'heading'     => __("Lightbox text", 'tt_orbit'),
			                  'param_name'  => "lightbox_description",
			                  'description' => __('This text is displayed within the lightbox (optional)', 'tt_orbit')
			              ),
			              array(
			               	  'group'       => __('CSS Class', 'tt_orbit'),
			                  'type'        => 'textfield',
			                  'holder'      => 'div',
			                  'heading'     => __('CSS class name', 'tt_orbit'),
			                  'param_name'  => 'custom_css_class',
			                  'description' => __('Give this element an extra CSS class name if you wish to refer to it in a CSS file. (optional)', 'tt_orbit')
			              ),
			           )
	) 
);// END vc_map

/*--------------------------------------------------------------
Orbit - Content Box
--------------------------------------------------------------*/
vc_map( array(
        'name'        => __("Content Box", 'tt_orbit'),
        'description' => __("Stylish text box", 'tt_orbit'),
        'base'        => "orbit_content_box",
        'controls'    => 'full',
        'class'       => 'orbit-content-box',
        'icon'        => plugins_url('images/backend-editor/orbit-menu-content-box.png', __FILE__),
        'category'    => __('Orbit', 'tt_orbit'),
        'js_view'     => 'OrbitContentBox',
        "params"      => array(
			               array(
			                  'type'       => 'dropdown',
			                  'holder'     => 'div',
			                  'heading'    => __("Color", 'tt_orbit'),
			                  'param_name' => "style",
			                  'value'      => array(
											'Autumn'     => 'autumn',
											'Black'      => 'black',
											'Black 2'    => 'black-2',
											'Blue'       => 'blue',
											'Blue Grey'  => 'blue-grey',
											'Cool Blue'  => 'cool-blue',
											'Coffee'     => 'coffee',
											'Fire'       => 'fire',
											'Golden'     => 'golden',
											'Green'      => 'green',
											'Green 2'    => 'green-2',
											'Grey'       => 'grey',
											'Lime Green' => 'lime-green',
											'Navy'       => 'navy',
											'Orange'     => 'orange',
											'Periwinkle' => 'periwinkle',
											'Pink'       => 'pink',
											'Purple'     => 'purple',
											'Purple 2'   => 'purple-2',
											'Red'        => 'red',
											'Red 2'      => 'red-2',
											'Royal Blue' => 'royal-blue',
											'Silver'     => 'silver',
											'Sky Blue'   => 'sky-blue',
											'Teal Grey'  => 'teal-grey',
											'Teal'       => 'teal',
											'Teal 2'     => 'teal-2',
											),
											'save_always' => true,
			                  'description' => __('<a href="http://s3.truethemes.net/plugin-assets/shortcode-style-guide/style-guide.html" target="_blank">View available colors &rarr;</a>', 'tt_orbit')
			              ),

        		        		//START icons
		        		array(
							'group'   => __('Icon', 'tt_orbit'),		        		
							'type'    => 'dropdown',
							'heading' => __( 'Icon library', 'tt_orbit' ),
							'value'   => array(
									__( 'Font Awesome', 'tt_orbit' )        => 'fontawesome',
									__( 'Open Iconic', 'tt_orbit' )         => 'openiconic',
									__( 'Typicons', 'tt_orbit' )            => 'typicons',
									__( 'Entypo', 'tt_orbit' )              => 'entypo',
									__( 'Linecons', 'tt_orbit' )            => 'linecons',
									__( 'Do not display icon', 'tt_orbit' ) => '',									
								),
							'admin_label' => true,
							'param_name'  => 'type',
							'description' => __( 'Select icon library.', 'tt_orbit' ),
							'std'         => array(__( 'Font Awesome', 'tt_orbit' ) => 'fontawesome'),
						),
						//fontawesome
						array(
							'group'        => __('Icon', 'tt_orbit'),					
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_fontawesome',
							'value'        => 'fa fa-adjust',
							'settings'     => array(
							'emptyIcon'    => false,
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'fontawesome',
							),
							'description'  => __( 'Select icon from library.', 'tt_orbit' ),
						),
						//openiconic
						array(
							'group'        => __('Icon', 'tt_orbit'),					
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_openiconic',
							'value'        => 'vc-oi vc-oi-dial',
							'settings'     => array(
							'emptyIcon'    => false,
							'type'         => 'openiconic',
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'openiconic',
							),
							'description'  => __( 'Select icon from library.', 'tt_orbit' ),
						),
						//typicons
						array(
							'group'        => __('Icon', 'tt_orbit'),					
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_typicons',
							'value'        => 'typcn typcn-adjust-brightness',
							'settings'     => array(
							'emptyIcon'    => false,
							'type'         => 'typicons',
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'typicons',
							),
							'description'  => __( 'Select icon from library.', 'tt_orbit' ),
						),
						//entypo
						array(
							'group'        => __('Icon', 'tt_orbit'),					
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_entypo',
							'value'        => 'entypo-icon entypo-icon-note',
							'settings'     => array(
							'emptyIcon'    => false,
							'type'         => 'entypo',
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'entypo',
							),
						),
						//linecons
						array(
							'group'        => __('Icon', 'tt_orbit'),
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_linecons',
							'value'        => 'vc_li vc_li-heart',
							'settings'     => array(
							'emptyIcon'    => false,
							'type'         => 'linecons',
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'linecons',
							),
							'description'  => __( 'Select icon from library.', 'tt_orbit' ),
						),
						//END icons

 							array(
								'group'        => __('Icon', 'tt_orbit'),
								'type'        => 'textarea_raw_html',
								'heading'     => __('Custom Icon', 'tt_orbit'),
								'description' => __('Display your own custom icon by entering it\'s HTML code here. Give this HTML element an additional CSS class name of "orbit-custom-icon" for proper positioning.', 'tt_orbit'),
								'param_name'  => 'custom_icon',
								'value'=>'',
							),
			               array(
			                  'group'        => __('Icon', 'tt_orbit'),
			                  'type'          => "attach_image",
			                  'holder'        => 'div',
			                  'heading'       => __("Custom Icon Upload", 'tt_orbit'),
			                  'param_name'    => "custom_icon_upload",
			                  'description'   => __('Upload a custom icon, this overwrites the Custom Icon and Icon settings.', 'tt_orbit')
			              ),													
			              array(
			                  'type'          => "textfield",
			                  'holder'        => 'div',
			                  'heading'       => __("Title", 'tt_orbit'),
			                  'param_name'    => "title",
			                  'value'         => "Content Box",
			                  'description' => __('This title is displayed in the top color section.', 'tt_orbit')
			              ),
			              array(
			                  'type'          => "textarea_html",
			                  'holder'        => 'div',
			                  'heading'       => __('Content', 'tt_orbit'),
			                  'param_name'    => 'content',
			                  'value'         => __("Edit this text with custom content.", 'tt_orbit')
			              ),
			              array(
			               	  'group'       => __('CSS Class', 'tt_orbit'),
			                  'type'        => 'textfield',
			                  'holder'      => 'div',
			                  'heading'     => __('CSS class name', 'tt_orbit'),
			                  'param_name'  => 'custom_css_class',
			                  'description' => __('Give this element an extra CSS class name if you wish to refer to it in a CSS file. (optional)', 'tt_orbit')
			              ),					              
        				)
			) 
);// END vc_map

/*--------------------------------------------------------------
Orbit - Circle Loader
--------------------------------------------------------------*/
   vc_map( array(
        'name'        => __("Circle Loader", 'tt_orbit'),
        'description' => __("Animated circle loader", 'tt_orbit'),
        'base'        => "orbit_circle_loader",
        'controls'    => 'full',
        'icon'        => plugins_url('images/backend-editor/orbit-menu-circle-loader.png', __FILE__),
        'category'    => __('Orbit', 'tt_orbit'),
        "params"      => array(
        					array(
        						'group'      => 'Content',
								'value'      => '50',
								'type'       => 'textfield',
								'heading'    => __('Number', 'tt_orbit'),
								'param_name' => 'number',
							),
							array(
								'group'      => 'Content',
								'value'      => '%',
								'type'       => 'textfield',
								'heading'    => __('Symbol', 'tt_orbit'),
								'param_name' => 'symbol',
							),
							array(
								'group'      => 'Content',
								'type'       => 'textarea_html',
								'heading'    => __('Content', 'tt_orbit'),
								'param_name' => 'content',
								'value'      => __("<h3>Heading</h3><p>This content is displayed below the circle loader.</p>", 'tt_orbit')
							),
							array(
								'group'      => __('Design', 'tt_orbit'),
								'value'      => '#000',
								'type'       => 'colorpicker',
								'heading'    => __('Number Color', 'tt_orbit'),
								'param_name' => 'number_color',
							),
							array(
								'group'      => __('Design', 'tt_orbit'),
								'value'      => '#eee',
								'type'       => 'colorpicker',
								'heading'      => __('Track Color', 'tt_orbit'),
								'param_name' => 'track_color',
							),
							array(
								'group'      => __('Design', 'tt_orbit'),
								'value'      => '#a0dbe1',
								'type'       => 'colorpicker',
								'heading'    => __('Bar Color', 'tt_orbit'),
								'param_name' => 'bar_color',
							),
							array(
								'group'      => __('Design', 'tt_orbit'),
								'type'       => 'dropdown',
								'heading'    => __('Bar Style', 'tt_orbit'),
								'value'      => array(
										'Square'     => 'square',
										'Round'      => 'round'
								),
								'param_name' => 'style',
							),
							array(
								'group'      => __('Design', 'tt_orbit'),
								'value'      => '10',
								'type'       => 'textfield',
								'heading'    => __('Bar Width', 'tt_orbit'),
								'param_name' => 'bar_width',
							),
							array(
			               	  'group'       => __('CSS Class', 'tt_orbit'),
			                  'type'        => 'textfield',
			                  'holder'      => 'div',
			                  'heading'     => __('CSS class name', 'tt_orbit'),
			                  'param_name'  => 'custom_css_class',
			                  'description' => __('Give this element an extra CSS class name if you wish to refer to it in a CSS file. (optional)', 'tt_orbit')
			              ),		
					    ),
     	   		) 
	);// END vc_map

/*--------------------------------------------------------------
Orbit - Circle Loader (icon)
--------------------------------------------------------------*/
   vc_map( array(
        'name'        => __("Circle Loader (icon)", 'tt_orbit'),
        'description' => __("Animated circle loader and icon", 'tt_orbit'),
        'base'        => "orbit_circle_loader_icon",
        'controls'    => 'full',
        'icon'        => plugins_url('images/backend-editor/orbit-menu-circle-loader-icon.png', __FILE__),
        'category'    => __('Orbit', 'tt_orbit'),
        'show_settings_on_create' => true,
        "params"      => array(
					        array(
        						'group'       => 'Content',
        						'heading'     => __('Number', 'tt_orbit'),
								'value'       => '50',
								'type'        => 'textfield',
								'description' => __('Percentage of the loader bar.', 'tt_orbit'),
								'param_name'  => 'number',
							),
						//START icons
		        		array(
							'group'   => __('Icon', 'tt_orbit'),		        		
							'type'    => 'dropdown',
							'heading' => __( 'Icon library', 'tt_orbit' ),
							'value'   => array(
									__( 'Font Awesome', 'tt_orbit' )        => 'fontawesome',
									__( 'Open Iconic', 'tt_orbit' )         => 'openiconic',
									__( 'Typicons', 'tt_orbit' )            => 'typicons',
									__( 'Entypo', 'tt_orbit' )              => 'entypo',
									__( 'Linecons', 'tt_orbit' )            => 'linecons',
									__( 'Do not display icon', 'tt_orbit' ) => '',									
								),
							'admin_label' => true,
							'param_name'  => 'type',
							'description' => __( 'Select icon library.', 'tt_orbit' ),
							'std'         => array(__( 'Font Awesome', 'tt_orbit' ) => 'fontawesome'),
						),
						//fontawesome
						array(
							'group'        => __('Icon', 'tt_orbit'),					
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_fontawesome',
							'value'        => 'fa fa-adjust',
							'settings'     => array(
							'emptyIcon'    => false,
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'fontawesome',
							),
							'description'  => __( 'Select icon from library.', 'tt_orbit' ),
						),
						//openiconic
						array(
							'group'        => __('Icon', 'tt_orbit'),					
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_openiconic',
							'value'        => 'vc-oi vc-oi-dial',
							'settings'     => array(
							'emptyIcon'    => false,
							'type'         => 'openiconic',
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'openiconic',
							),
							'description'  => __( 'Select icon from library.', 'tt_orbit' ),
						),
						//typicons
						array(
							'group'        => __('Icon', 'tt_orbit'),					
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_typicons',
							'value'        => 'typcn typcn-adjust-brightness',
							'settings'     => array(
							'emptyIcon'    => false,
							'type'         => 'typicons',
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'typicons',
							),
							'description'  => __( 'Select icon from library.', 'tt_orbit' ),
						),
						//entypo
						array(
							'group'        => __('Icon', 'tt_orbit'),					
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_entypo',
							'value'        => 'entypo-icon entypo-icon-note',
							'settings'     => array(
							'emptyIcon'    => false,
							'type'         => 'entypo',
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'entypo',
							),
						),
						//linecons
						array(
							'group'        => __('Icon', 'tt_orbit'),
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_linecons',
							'value'        => 'vc_li vc_li-heart',
							'settings'     => array(
							'emptyIcon'    => false,
							'type'         => 'linecons',
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'linecons',
							),
							'description'  => __( 'Select icon from library.', 'tt_orbit' ),
						),
						//END icons

 							array(
								'group'        => __('Icon', 'tt_orbit'),
								'type'        => 'textarea_raw_html',
								'heading'     => __('Custom Icon', 'tt_orbit'),
								'description' => __('Display your own custom icon by entering it\'s HTML code here. Give this HTML element an additional CSS class name of "orbit-custom-icon" for proper positioning.', 'tt_orbit'),
								'param_name'  => 'custom_icon',
								'value'       => '',

							),	
			               array(
			                  'group'        => __('Icon', 'tt_orbit'),
			                  'type'          => "attach_image",
			                  'holder'        => 'div',
			                  'heading'       => __("Custom Icon Upload", 'tt_orbit'),
			                  'param_name'    => "custom_icon_upload",
			                  'description'   => __('Upload a custom icon, this overwrites the Custom Icon and Icon settings.', 'tt_orbit')
			               ),														
							array(
								'group'      => 'Content',
								'heading'    => __('Content', 'tt_orbit'),
								'type'       => 'textarea_html',
								'param_name' => 'content',
								'value'      => __("<h3>Heading</h3><p>This content is displayed below the circle icon loader.</p>", 'tt_orbit')
							),
							array(
								'value'       => '#d3565a',
								'group'      => __('Design', 'tt_orbit'),
								'type'        => 'colorpicker',
								'heading'     => __('Icon Color', 'tt_orbit'),
								'param_name'  => 'icon_color',
							),
							array(
								'value'      => '#eee',
								'group'      => __('Design', 'tt_orbit'),
								'type'       => 'colorpicker',
								'heading'      => __('Track Color', 'tt_orbit'),
								'param_name' => 'track_color',
							),
							array(
								'value'      => '#a0dbe1',
								'group'      => __('Design', 'tt_orbit'),
								'type'       => 'colorpicker',
								'heading'    => __('Bar Color', 'tt_orbit'),
								'param_name' => 'bar_color',
							),
							array(
								'type'       => 'dropdown',
								'group'      => __('Design', 'tt_orbit'),
								'heading'    => __('Bar Style', 'tt_orbit'),
								'value'      => array(
								'Square'     => 'square',
								'Round'      => 'round'
								),
								'param_name' => 'style',
								'save_always' => true,
							),
							array(
								'value'      => '10',
								'group'      => __('Design', 'tt_orbit'),
								'type'       => 'textfield',
								'heading'    => __('Bar Width', 'tt_orbit'),
								'param_name' => 'bar_width',
							),
							array(
			               	  'group'       => __('CSS Class', 'tt_orbit'),
			                  'type'        => 'textfield',
			                  'holder'      => 'div',
			                  'heading'     => __('CSS class name', 'tt_orbit'),
			                  'param_name'  => 'custom_css_class',
			                  'description' => __('Give this element an extra CSS class name if you wish to refer to it in a CSS file. (optional)', 'tt_orbit')
			              ),
					    ),
     	   		) 
);// END vc_map

/*--------------------------------------------------------------
Orbit - Dropcap
--------------------------------------------------------------*/
vc_map(      
	array(
		'category'    => __('Orbit', 'tt_orbit'),
        'name'        => __('Dropcap', 'tt_orbit'),
        'description' => __("Stylish dropcap element", 'tt_orbit'),
        'base'        => 'orbit_dropcap',
        'controls'    => 'full',
        'class'       => 'orbit-dropcap',
        'js_view'     => 'OrbitDropcap',
        'icon'        => plugins_url('images/backend-editor/orbit-menu-dropcap.png', __FILE__),
        'params'      => array(
			               array(
			                  'type'       => 'dropdown',
			                  'holder'     => 'div',
			                  'heading'    => __("Color", 'tt_orbit'),
			                  'param_name' => 'color',
			                  'value' => array(
									'Autumn'     => 'autumn',
									'Black'      => 'black',
									'Black 2'    => 'black-2',
									'Blue'       => 'blue',
									'Blue Grey'  => 'blue-grey',
									'Cool Blue'  => 'cool-blue',
									'Coffee'     => 'coffee',
									'Fire'       => 'fire',
									'Golden'     => 'golden',
									'Green'      => 'green',
									'Green 2'    => 'green-2',
									'Grey'       => 'grey',
									'Lime Green' => 'lime-green',
									'Navy'       => 'navy',
									'Orange'     => 'orange',
									'Periwinkle' => 'periwinkle',
									'Pink'       => 'pink',
									'Purple'     => 'purple',
									'Purple 2'   => 'purple-2',
									'Red'        => 'red',
									'Red 2'      => 'red-2',
									'Royal Blue' => 'royal-blue',
									'Silver'     => 'silver',
									'Sky Blue'   => 'sky-blue',
									'Teal Grey'  => 'teal-grey',
									'Teal'       => 'teal',
									'Teal 2'     => 'teal-2',
									'White'      => 'white',
									),
							  'save_always' => true,
			                  'description' => __('Select a background color for this dropcap. <a href="http://s3.truethemes.net/plugin-assets/shortcode-style-guide/style-guide.html" target="_blank">View available colors &rarr;</a>', 'tt_orbit')
			              ),
							array(
				                  'type'       => 'dropdown',
				                  'holder'     => 'div',
				                  'heading'    => __("Style", 'tt_orbit'),
				                  'param_name' => 'style',
				                  'value' => array('Round'=> 'round','Square'=> 'square','Text'=> 'text'),
				                  'save_always' => true,
				              ),
							array(
								  'heading'     => __("Dropcap", 'tt_orbit'),
				                  'type'        => "textfield",
				                  'holder'      => 'div',
				                  'value'       => 'O',
				                  'param_name'  => 'dropcap',
				                  'description' => __('The single character to be "drop-capped".', 'tt_orbit')
				              ),
							array(
							 'heading'       => __('Content', 'tt_orbit'),
				    		 'type'          => "textarea_html",
				    		 'holder'        => 'div',
				    		 'param_name'    => 'content',
				    		 'value'         => __("<p>This text displayed next to the dropcap.</p>", 'tt_orbit')
			              ),
							array(
			               	  'group'       => __('CSS Class', 'tt_orbit'),
			                  'type'        => 'textfield',
			                  'holder'      => 'div',
			                  'heading'     => __('CSS class name', 'tt_orbit'),
			                  'param_name'  => 'custom_css_class',
			                  'description' => __('Give this element an extra CSS class name if you wish to refer to it in a CSS file. (optional)', 'tt_orbit')
			              ),
			           )
	) 
);// END vc_map

/*--------------------------------------------------------------
Orbit - Feature List Item
--------------------------------------------------------------*/
   vc_map( array(
        'name'        => __("Feature List Item", 'tt_orbit'),
        'description' => __("Animated features list", 'tt_orbit'),
        'base'        => "orbit_features",
        'controls'    => 'full',
        'icon'        => plugins_url('images/backend-editor/orbit-menu-feature-list.png', __FILE__),
        'category'    => __('Orbit', 'tt_orbit'),
        'class'       => 'orbit-feature-list',
        'js_view'     => 'OrbitFeatureListItem',
        'show_settings_on_create' => true,
        "params"      => array(
					      array(
					      		'group'         => __('General', 'tt_orbit'),
								'type'          => 'dropdown',
								'heading'       => __("Animation", 'tt_orbit'),
								'param_name'    => "animate",
								'value'         => array(
									'fly-in from center' => 'in_from_center',
									'fly-in from top'    => 'in_from_top',
									'fly-in from right'  => 'in_from_right',
									'fly-in from bottom' => 'in_from_bottom',
									'fly-in from left'   => 'in_from_left',
									'no animation'       => 'animate_none',
									
								),
								'save_always' => true,
					        ),
        		        		//START icons
		        		array(
							'group'   => __('Icon', 'tt_orbit'),		        		
							'type'    => 'dropdown',
							'heading' => __( 'Icon library', 'tt_orbit' ),
							'value'   => array(
									__( 'Font Awesome', 'tt_orbit' )        => 'fontawesome',
									__( 'Open Iconic', 'tt_orbit' )         => 'openiconic',
									__( 'Typicons', 'tt_orbit' )            => 'typicons',
									__( 'Entypo', 'tt_orbit' )              => 'entypo',
									__( 'Linecons', 'tt_orbit' )            => 'linecons',
									__( 'Do not display icon', 'tt_orbit' ) => '',									
								),
							'admin_label' => true,
							'param_name'  => 'type',
							'description' => __( 'Select icon library.', 'tt_orbit' ),
							'std'         => array(__( 'Font Awesome', 'tt_orbit' ) => 'fontawesome'),
						),
						//fontawesome
						array(
							'group'        => __('Icon', 'tt_orbit'),					
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_fontawesome',
							'value'        => 'fa fa-adjust',
							'settings'     => array(
							'emptyIcon'    => false,
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'fontawesome',
							),
							'description'  => __( 'Select icon from library.', 'tt_orbit' ),
						),
						//openiconic
						array(
							'group'        => __('Icon', 'tt_orbit'),					
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_openiconic',
							'value'        => 'vc-oi vc-oi-dial',
							'settings'     => array(
							'emptyIcon'    => false,
							'type'         => 'openiconic',
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'openiconic',
							),
							'description'  => __( 'Select icon from library.', 'tt_orbit' ),
						),
						//typicons
						array(
							'group'        => __('Icon', 'tt_orbit'),					
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_typicons',
							'value'        => 'typcn typcn-adjust-brightness',
							'settings'     => array(
							'emptyIcon'    => false,
							'type'         => 'typicons',
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'typicons',
							),
							'description'  => __( 'Select icon from library.', 'tt_orbit' ),
						),
						//entypo
						array(
							'group'        => __('Icon', 'tt_orbit'),					
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_entypo',
							'value'        => 'entypo-icon entypo-icon-note',
							'settings'     => array(
							'emptyIcon'    => false,
							'type'         => 'entypo',
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'entypo',
							),
						),
						//linecons
						array(
							'group'        => __('Icon', 'tt_orbit'),
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_linecons',
							'value'        => 'vc_li vc_li-heart',
							'settings'     => array(
							'emptyIcon'    => false,
							'type'         => 'linecons',
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'linecons',
							),
							'description'  => __( 'Select icon from library.', 'tt_orbit' ),
						),
						//END icons
					      array(
					      		'group'       => __('Icon', 'tt_orbit'),
								'type'        => 'textarea_raw_html',
								'heading'     => __('Custom Icon', 'tt_orbit'),
								'description' => __('Display your own custom icon by entering it\'s HTML code here. Give this HTML element an additional CSS class name of "orbit-custom-icon" for proper positioning.', 'tt_orbit'),
								'param_name'  => 'custom_icon',
								'value' => '',
							),
			               array(
					      	  'group'       => __('Icon', 'tt_orbit'),			               
			                  'type'          => "attach_image",
			                  'holder'        => 'div',
			                  'heading'       => __("Custom Icon Upload", 'tt_orbit'),
			                  'param_name'    => "custom_icon_upload",
			                  'description'   => __('Upload a custom icon, this overwrites the Custom Icon and Icon settings.', 'tt_orbit')
			              ),							
			              array(
			              	'group'         => __('General', 'tt_orbit'),
				    		 'type'          => "textarea_html",
				    		 'holder'        => 'div',
				    		 'heading'       => __('Content', 'tt_orbit'),
				    		 'param_name'    => 'content',
				    		 'value'      => __("<h3>Feature #1</h3><p>Lorem ipsum dolor sit amet.</p>", 'tt_orbit')
			              ),	
					        array(
					        	'group'      => __('Design', 'tt_orbit'),
								'value'       => '#d3565a',
								'type'        => 'colorpicker',
								'heading'     => __('Icon Color', 'tt_orbit'),
								'param_name'  => 'icon_color',
							),
					        array(
					        	'group'         => __('Design', 'tt_orbit'),
								'type'          => 'colorpicker',
								'heading'       => __('Icon Color (hover)', 'tt_orbit'),
								'param_name'    => "icon_color_hover",
								'value'         => "#ffffff",
								'description'   => __("The color of the icon when hovered.", 'tt_orbit')
					        ),	
				            array(
				            	'group'         => __('Design', 'tt_orbit'),
								'type'          => "textfield",
								'holder'        => 'div',
								'heading'       => __("Border Width", 'tt_orbit'),
								'param_name'    => "border_width",
								'value'         => "2px",
								'description'   => __("The width of the circle border.", 'tt_orbit')
				              ),
					        array(
					        	'group'         => __('Design', 'tt_orbit'),
								'type'          => 'colorpicker',
								'heading'       => __("Border Color", 'tt_orbit'),
								'param_name'    => "border_color",
								'value'         => "#a2dce2",
								'description'   => __("The color of the circle border.", 'tt_orbit')
					        ),	
					        array(
					        	'group'         => __('Design', 'tt_orbit'),
								'type'          => 'colorpicker',
								'heading'       => __("Background Color", 'tt_orbit'),
								'param_name'    => "bg_color",
								'value'         => "#fff",
								'description'   => __("The color of the circle.", 'tt_orbit')
					        ),
					        array(
					        	'group'         => __('Design', 'tt_orbit'),
								'type'          => 'colorpicker',
								'heading'       => __("Background Color (hover)", 'tt_orbit'),
								'param_name'    => "bg_color_hover",
								'value'         => "#a2dce2",
								'description'   => __("The color of the circle when hovered.", 'tt_orbit')
					        ),								        
							array(
							  'group'       => __('URL (link)', 'tt_orbit'),
			                  'type'        => 'vc_link',
			                  'holder'      => 'div',
			                  //'heading'   => ** this is empty for cleaner user-interface
			                  'param_name'  => 'url',
			                  'description' => __('Click "Select URL" to link this element. (optional)', 'tt_orbit')
			              ),
			              	array(
			              	  'group'       => __('Lightbox', 'tt_orbit'),
			                  'type'        => 'textfield',
			                  'holder'      => 'div',
			                  'heading'     => __('Lightbox', 'tt_orbit'),
			                  'param_name'  => "lightbox_content",
			                  'description' => __('Display content inside a lightbox by entering the URL here. This will override any URL (link) settings on the previous tab. <a href="https://s3.amazonaws.com/Plugin-Vision/lightbox-samples.html" target="_blank">Lightbox content samples &rarr;</a>', 'tt_orbit')
			              ),  
			              	array(
			              	  'group'       => __('Lightbox', 'tt_orbit'),
			                  'type'        => 'textfield',
			                  'holder'      => 'div',
			                  'heading'     => __("Lightbox text", 'tt_orbit'),
			                  'param_name'  => "lightbox_description",
			                  'description' => __('This text is displayed within the lightbox (optional)', 'tt_orbit')
			              ),
			              array(
			               	  'group'       => __('CSS Class', 'tt_orbit'),
			                  'type'        => 'textfield',
			                  'holder'      => 'div',
			                  'heading'     => __('CSS class name', 'tt_orbit'),
			                  'param_name'  => 'custom_css_class',
			                  'description' => __('Give this element an extra CSS class name if you wish to refer to it in a CSS file. (optional)', 'tt_orbit')
			              ),							
					),
     	   		) 
	);// END vc_map

/*--------------------------------------------------------------
Orbit - Heading
--------------------------------------------------------------*/
vc_map( array(
        'name'        => __("Heading", 'tt_orbit'),
        'category'    => __('Orbit', 'tt_orbit'),
        'description' => __("A stylish heading with subheading (H1-H6)", 'tt_orbit'),
        'base'        => "orbit_heading",
        'controls'    => 'full',
        'class'       => 'orbit-heading',
        'icon'        => plugins_url('images/backend-editor/orbit-menu-heading.png', __FILE__),
        //'js_view'     => 'OrbitIconText',
        "params"      => array(
        					array(
        						'group'       => __('Content', 'tt_orbit'),
        						'heading'     => __('Heading Text', 'tt_orbit'),
								'value'       => 'Hello',
								'type'        => 'textfield',
								'param_name'  => 'heading_text',
							),
							array(
			               	  'group'      => __('Content', 'tt_orbit'),
			               	  'heading'    => __('Sub-Heading Text', 'tt_orbit'),
			                  'type'       => 'textarea',
			                  'holder'     => 'div',
			                  'param_name' => 'sub_heading_text',
			                  'value'      => __("Lorem ipsum dolor nibh ultricies vehicula ut id elit.", 'tt_orbit')
			              ),
							array(
			               	  'group'      => __('Content', 'tt_orbit'),
			               	  'heading'    => __('Heading Color', 'tt_orbit'),
			                  'type'          => 'colorpicker',
			                  'holder'        => 'div',
			                  'param_name'    => "heading_color",
			                  'value'         => '#363636'
			              ),
							array(
			               	  'group'      => __('Content', 'tt_orbit'),
			               	  'heading'    => __('Sub-Heading Color', 'tt_orbit'),
			                  'type'          => 'colorpicker',
			                  'holder'        => 'div',
			                  'param_name'    => "sub_heading_color",
			                  'value'         => '#555'
			              ),
							array(
        						'group'       => __('Heading', 'tt_orbit'),
        						'heading'     => __('Heading Size', 'tt_orbit'),
								'value'       => '30px',
								'type'        => 'textfield',
								'param_name'  => 'heading_size',
							),
							array(
			               	  'group'      => __('Heading', 'tt_orbit'),
			               	  'heading'    => __('Heading Type', 'tt_orbit'),
			                  'type'       => 'dropdown',
			                  'holder'     => 'div',
			                  'param_name' => 'heading_type',
			                  'value' => array(
									'H3'  => 'h3',
									'H1'  => 'h1',
									'H2'  => 'h2',
									'H4'  => 'h4',
									'H5'  => 'h5',
									'H6'  => 'h6'
								),
								'save_always' => true,
			              ),
							array(
			               	  'group'      => __('Heading', 'tt_orbit'),
			               	  'heading'    => __('Heading Font', 'tt_orbit'),
			                  'type'       => 'dropdown',
			                  'holder'     => 'div',
			                  'param_name' => 'heading_font',
			                  'value' => array(
									'Open Sans'   => 'tt-orbit-opensans',
									'Montserrat'  => 'tt-orbit-montserrat'
								),
								'save_always' => true,
			              ),
							array(
			               	  'group'      => __('Heading', 'tt_orbit'),
			               	  'heading'    => __('Heading Weight', 'tt_orbit'),
			                  'type'       => 'dropdown',
			                  'holder'     => 'div',
			                  'param_name' => 'heading_weight',
			                  'value' => array(
									'Light'  => 'tt-heading-light',
									'Normal' => 'tt-heading-normal'
								),
								'save_always' => true,
			              ),
							array(
			               	  'group'      => __('Heading', 'tt_orbit'),
			               	  'heading'    => __('Heading Style', 'tt_orbit'),
			                  'type'       => 'dropdown',
			                  'holder'     => 'div',
			                  'param_name' => 'heading_style',
			                  'value' => array(
									'Normal'    => 'none',
									'UPPERCASE' => 'uppercase'
								),
								'save_always' => true,
			              ), 
			               array(
        						'group'       => __('Sub-Heading', 'tt_orbit'),
        						'heading'     => __('Sub-Heading Size', 'tt_orbit'),
								'value'       => '16px',
								'type'        => 'textfield',
								'param_name'  => 'sub_heading_size',
							),
			               array(
			               	  'group'      => __('Sub-Heading', 'tt_orbit'),
			               	  'heading'    => __('Sub-Heading Weight', 'tt_orbit'),
			                  'type'       => 'dropdown',
			                  'holder'     => 'div',
			                  'param_name' => 'sub_heading_weight',
			                  'value' => array(
			                  		'Light'  => 'tt-subheading-light',
			                  		'Normal' => 'tt-subheading-normal'
								),
								'save_always' => true,
			              ), 
			               array(
			               	  'group'       => __('CSS Class', 'tt_orbit'),
			               	  'heading'     => __('CSS class name', 'tt_orbit'),
			                  'type'        => 'textfield',
			                  'holder'      => 'div',
			                  'param_name'  => 'custom_css_class',
			                  'description' => __('Give this element an extra CSS class name if you wish to refer to it in a CSS file. (optional)', 'tt_orbit')
			              ),
        				)
			) 
);// END vc_map

/*--------------------------------------------------------------
Orbit - Icon Box
--------------------------------------------------------------*/
   vc_map( array(
        'name'        => __("Icon Box", 'tt_orbit'),
        'description' => __("Stylish vector icon callout box", 'tt_orbit'),
        'base'        => "orbit_icon_box",
        'controls'    => 'full',
        'class'       => 'orbit-icon-box',
        'icon'        => plugins_url('images/backend-editor/orbit-menu-icon-box.png', __FILE__),
        'category'    => __('Orbit', 'tt_orbit'),
        'js_view'     => 'OrbitIconBox',
        "params"      => array(
        			    array(
				    		 'type'          => "textarea_html",
				    		 'group'         => __('General', 'tt_orbit'),
				    		 'holder'        => 'div',
				    		 'heading'       => __('Content', 'tt_orbit'),
				    		 'param_name'    => 'content',
				    		 'value'      => __("<h3>Heading</h3><p>Lorem ipsum dolor sit amet.</p>", 'tt_orbit')
			              ),
        		        		//START icons
		        		array(
							'group'   => __('Icon', 'tt_orbit'),		        		
							'type'    => 'dropdown',
							'heading' => __( 'Icon library', 'tt_orbit' ),
							'value'   => array(
									__( 'Font Awesome', 'tt_orbit' )        => 'fontawesome',
									__( 'Open Iconic', 'tt_orbit' )         => 'openiconic',
									__( 'Typicons', 'tt_orbit' )            => 'typicons',
									__( 'Entypo', 'tt_orbit' )              => 'entypo',
									__( 'Linecons', 'tt_orbit' )            => 'linecons',
									__( 'Do not display icon', 'tt_orbit' ) => '',									
								),
							'admin_label' => true,
							'param_name'  => 'type',
							'description' => __( 'Select icon library.', 'tt_orbit' ),
							'std'         => array(__( 'Font Awesome', 'tt_orbit' ) => 'fontawesome'),
						),
						//fontawesome
						array(
							'group'        => __('Icon', 'tt_orbit'),					
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_fontawesome',
							'value'        => 'fa fa-adjust',
							'settings'     => array(
							'emptyIcon'    => false,
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'fontawesome',
							),
							'description'  => __( 'Select icon from library.', 'tt_orbit' ),
						),
						//openiconic
						array(
							'group'        => __('Icon', 'tt_orbit'),					
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_openiconic',
							'value'        => 'vc-oi vc-oi-dial',
							'settings'     => array(
							'emptyIcon'    => false,
							'type'         => 'openiconic',
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'openiconic',
							),
							'description'  => __( 'Select icon from library.', 'tt_orbit' ),
						),
						//typicons
						array(
							'group'        => __('Icon', 'tt_orbit'),					
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_typicons',
							'value'        => 'typcn typcn-adjust-brightness',
							'settings'     => array(
							'emptyIcon'    => false,
							'type'         => 'typicons',
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'typicons',
							),
							'description'  => __( 'Select icon from library.', 'tt_orbit' ),
						),
						//entypo
						array(
							'group'        => __('Icon', 'tt_orbit'),					
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_entypo',
							'value'        => 'entypo-icon entypo-icon-note',
							'settings'     => array(
							'emptyIcon'    => false,
							'type'         => 'entypo',
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'entypo',
							),
						),
						//linecons
						array(
							'group'        => __('Icon', 'tt_orbit'),
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_linecons',
							'value'        => 'vc_li vc_li-heart',
							'settings'     => array(
							'emptyIcon'    => false,
							'type'         => 'linecons',
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'linecons',
							),
							'description'  => __( 'Select icon from library.', 'tt_orbit' ),
						),
						//END icons

					      array(
					      		'group'        => __('Icon', 'tt_orbit'),
								'type'        => 'textarea_raw_html',
								'heading'     => __('Custom Icon', 'tt_orbit'),
								'description' => __('Display your own custom icon by entering it\'s HTML code here. Give this HTML element an additional CSS class name of "orbit-custom-icon" for proper positioning.', 'tt_orbit'),
								'param_name'  => 'custom_icon',
								'value' => '',
							),	
			               array(
					      	  'group'        => __('Icon', 'tt_orbit'),			               
			                  'type'          => "attach_image",
			                  'holder'        => 'div',
			                  'heading'       => __("Custom Icon Upload", 'tt_orbit'),
			                  'param_name'    => "custom_icon_upload",
			                  'description'   => __('Upload a custom icon, this overwrites the Custom Icon and Icon settings.', 'tt_orbit')
			              ),													
							array(
			               	  'group'        => __('Icon', 'tt_orbit'),
			               	  'heading'    => __('Icon Size', 'tt_orbit'),
			                  'type'       => 'dropdown',
			                  'holder'     => 'div',
			                  'param_name' => 'icon_size',
			                  'description' => __('Small: fa-3x , Medium: fa-4x , Large: fa-5x', 'tt_orbit'),
			                  'value' => array(
									'Small'  => 'fa-3x',
									'Medium'  => 'fa-4x',
									'Large'  => 'fa-5x'
								),
								'save_always' => true,
			              ),
			               array(
			               	  'group'         => __('Design', 'tt_orbit'),
			               	  'heading'       => __("Box BG color", 'tt_orbit'),
			                  'type'          => 'colorpicker',
			                  'holder'        => 'div',
			                  'param_name'    => 'box_bg_color',
			                  'value'         => '#fff',
			                  'description' => __('The main background color of the box', 'tt_orbit')
			              ),
			               array(
			               	  'group'         => __('Design', 'tt_orbit'),
			                  'type'          => 'colorpicker',
			                  'holder'        => 'div',
			                  'heading'       => __("Icon color", 'tt_orbit'),
			                  'param_name'    => 'icon_color',
			                  'value'         => '#fff'
			              ),
			               array(
			               	  'group'         => __('Design', 'tt_orbit'),
			                  'type'          => 'colorpicker',
			                  'holder'        => 'div',
			                  'heading'       => __("Icon BG color", 'tt_orbit'),
			                  'param_name'    => 'icon_bg_color',
			                  'value'         => '#87C442',
			                  'description' => __('The colored circle behind the icon', 'tt_orbit')
			              ),
			               array(
							  'group'       => __('URL (link)', 'tt_orbit'),
			                  'type'        => 'vc_link',
			                  'holder'      => 'div',
			                  //'heading'   => ** this is empty for cleaner user-interface
			                  'param_name'  => 'url',
			                  'description' => __('Click "Select URL" to link this element. (optional)', 'tt_orbit')
			              ),
			              	array(
			              	  'group'       => __('Lightbox', 'tt_orbit'),
			                  'type'        => 'textfield',
			                  'holder'      => 'div',
			                  'heading'     => __('Lightbox', 'tt_orbit'),
			                  'param_name'  => "lightbox_content",
			                  'description' => __('Display content inside a lightbox by entering the URL here. This will override any URL (link) settings on the previous tab. <a href="https://s3.amazonaws.com/Plugin-Vision/lightbox-samples.html" target="_blank">Lightbox content samples &rarr;</a>', 'tt_orbit')
			              ),  
			              	array(
			              	  'group'       => __('Lightbox', 'tt_orbit'),
			                  'type'        => 'textfield',
			                  'holder'      => 'div',
			                  'heading'     => __("Lightbox text", 'tt_orbit'),
			                  'param_name'  => "lightbox_description",
			                  'description' => __('This text is displayed within the lightbox (optional)', 'tt_orbit')
			              ),
			               array(
			               	  'group'       => __('CSS Class', 'tt_orbit'),
			                  'type'        => 'textfield',
			                  'holder'      => 'div',
			                  'heading'     => __('CSS class name', 'tt_orbit'),
			                  'param_name'  => 'custom_css_class',
			                  'description' => __('Give this element an extra CSS class name if you wish to refer to it in a CSS file. (optional)', 'tt_orbit')
			              ),
			              						              						              						           
        				)
			) 
);// END vc_map

/*--------------------------------------------------------------
Orbit - Icon + Content
--------------------------------------------------------------*/
   vc_map( array(
        'name'        => __("Icon + Text", 'tt_orbit'),
        'description' => __("Round vector icon with content", 'tt_orbit'),
        'base'        => "orbit_icon_content",
        'controls'    => 'full',
        'class'       => 'orbit-icon-text',
        'icon'        => plugins_url('images/backend-editor/orbit-menu-icon-text.png', __FILE__),
        'category'    => __('Orbit', 'tt_orbit'),
        'js_view'     => 'OrbitIconText',
        "params"      => array(	
        			      array(
				    		 'type'          => "textarea_html",
				    		 'group'         => __('General', 'tt_orbit'),
				    		 'holder'        => 'div',
				    		 'heading'       => __('Content', 'tt_orbit'),
				    		 'param_name'    => 'content',
				    		 'value'      => __("<p><strong>Heading</strong></p><p>Lorem ipsum dolor sit amet.</p>", 'tt_orbit')
			              ),					
		        		//START icons
		        		array(
							'group'   => __('Icon', 'tt_orbit'),		        		
							'type'    => 'dropdown',
							'heading' => __( 'Icon library', 'tt_orbit' ),
							'value'   => array(
									__( 'Font Awesome', 'tt_orbit' )        => 'fontawesome',
									__( 'Open Iconic', 'tt_orbit' )         => 'openiconic',
									__( 'Typicons', 'tt_orbit' )            => 'typicons',
									__( 'Entypo', 'tt_orbit' )              => 'entypo',
									__( 'Linecons', 'tt_orbit' )            => 'linecons',
									__( 'Do not display icon', 'tt_orbit' ) => '',									
								),
							'admin_label' => true,
							'param_name'  => 'type',
							'description' => __( 'Select icon library.', 'tt_orbit' ),
							'std'         => array(__( 'Font Awesome', 'tt_orbit' ) => 'fontawesome'),
						),
						//fontawesome
						array(
							'group'        => __('Icon', 'tt_orbit'),					
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_fontawesome',
							'value'        => 'fa fa-adjust',
							'settings'     => array(
							'emptyIcon'    => false,
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'fontawesome',
							),
							'description'  => __( 'Select icon from library.', 'tt_orbit' ),
						),
						//openiconic
						array(
							'group'        => __('Icon', 'tt_orbit'),					
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_openiconic',
							'value'        => 'vc-oi vc-oi-dial',
							'settings'     => array(
							'emptyIcon'    => false,
							'type'         => 'openiconic',
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'openiconic',
							),
							'description'  => __( 'Select icon from library.', 'tt_orbit' ),
						),
						//typicons
						array(
							'group'        => __('Icon', 'tt_orbit'),					
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_typicons',
							'value'        => 'typcn typcn-adjust-brightness',
							'settings'     => array(
							'emptyIcon'    => false,
							'type'         => 'typicons',
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'typicons',
							),
							'description'  => __( 'Select icon from library.', 'tt_orbit' ),
						),
						//entypo
						array(
							'group'        => __('Icon', 'tt_orbit'),					
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_entypo',
							'value'        => 'entypo-icon entypo-icon-note',
							'settings'     => array(
							'emptyIcon'    => false,
							'type'         => 'entypo',
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'entypo',
							),
						),
						//linecons
						array(
							'group'        => __('Icon', 'tt_orbit'),
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_linecons',
							'value'        => 'vc_li vc_li-heart',
							'settings'     => array(
							'emptyIcon'    => false,
							'type'         => 'linecons',
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'linecons',
							),
							'description'  => __( 'Select icon from library.', 'tt_orbit' ),
						),
					      array(
					      		'group'        => __('Icon', 'tt_orbit'),
								'type'        => 'textarea_raw_html',
								'heading'     => __('Custom Icon', 'tt_orbit'),
								'description' => __('Display your own custom icon by entering it\'s HTML code here. Give this HTML element an additional CSS class name of "orbit-custom-icon" for proper positioning.', 'tt_orbit'),
								'param_name'  => 'custom_icon',
								'value'=>'',
							),	
			               array(
					      	  'group'        => __('Icon', 'tt_orbit'),			               
			                  'type'          => "attach_image",
			                  'holder'        => 'div',
			                  'heading'       => __("Custom Icon Upload", 'tt_orbit'),
			                  'param_name'    => "custom_icon_upload",
			                  'description'   => __('Upload a custom icon, this overwrites the Custom Icon and Icon settings.', 'tt_orbit')
			               ),							
						//END icons													
							array(
			                  'type'          => 'colorpicker',
			                  'group'         => __('Icon', 'tt_orbit'),
			                  'holder'        => 'div',
			                  'heading'       => __("Icon color", 'tt_orbit'),
			                  'param_name'    => 'icon_color',
			                  'value'         => '#fff'
			              ),
			               array(
			                  'type'          => 'colorpicker',
			                  'group'         => __('Icon', 'tt_orbit'),
			                  'holder'        => 'div',
			                  'heading'       => __("Icon BG color", 'tt_orbit'),
			                  'param_name'    => "icon_bg_color",
			                  'value'         => '#3b86c4',
			                  'description' => __('The colored circle behind the icon.', 'tt_orbit')
			              ),
			               array(
			               	  'group'       => __('CSS Class', 'tt_orbit'),
			                  'type'        => 'textfield',
			                  'holder'      => 'div',
			                  'heading'     => __('CSS class name', 'tt_orbit'),
			                  'param_name'  => 'custom_css_class',
			                  'description' => __('Give this element an extra CSS class name if you wish to refer to it in a CSS file. (optional)', 'tt_orbit')
			              ),
			              						              						              						           
        				)
			) 
);// END vc_map

/*--------------------------------------------------------------
Orbit - Icon PNG
--------------------------------------------------------------*/
vc_map(      
	array(
		'category'    => __('Orbit', 'tt_orbit'),
        'name'        => __("Icon PNG", 'tt_orbit'),
        'description' => __("65 Stylish PNG icons", 'tt_orbit'),
        'base'        => 'orbit_icon_png',
        'controls'    => 'full',
        'class'       => 'orbit-icon-png',
        'js_view'     => 'OrbitIconImage',
        'icon'        => plugins_url('images/backend-editor/orbit-menu-icon-png.png', __FILE__),
        "params"      => array(
			               array(
			               	  'group'      => __('General', 'tt_orbit'),
			                  'type'       => 'dropdown',
			                  'holder'     => 'div',
			                  'heading'    => __("Select an Icon", 'tt_orbit'),
			                  'param_name' => "icon",
			                  'value' => array(
									'Alarm'                  => 'icon-alarm',
									'Arrow Down'             => 'icon-arrow-down-a',
									'Arrow Down 2'           => 'icon-arrow-down-b',
									'Arrow Up'               => 'icon-arrow-up-a',
									'Arrow Up 2'             => 'icon-arrow-up-b',
									'Calculator'             => 'icon-calculator',
									'Calendar - Day'         => 'icon-calendar-day',
									'Calendar - Month'       => 'icon-calendar-month',
									'Camera'                 => 'icon-camera',
									'Cart - Ecommerce'       => 'icon-cart-add',
									'Caution'                => 'icon-caution',
									'Cell Phone'             => 'icon-cellphone',
									'Chart'                  => 'icon-chart',
									'Chat (speech bubble)'   => 'icon-chat',
									'Chat 2 (speech bubble)' => 'icon-chat-2',
									'Checklist'              => 'icon-checklist',
									'Checkmark'              => 'icon-checkmark',
									'Clipboard'              => 'icon-clipboard',
									'Clock'                  => 'icon-clock',
									'Cog (sprocket)'         => 'icon-gear',
									'Contacts'               => 'icon-contacts',
									'Crate (wooden box)'     => 'icon-crate',
									'Database'               => 'icon-database',
									'Document edit'          => 'icon-document-edit',
									'DVD'                    => 'icon-dvd',
									'Email'                  => 'icon-email-send',
									'Flag'                   => 'icon-flag',
									'Games'                  => 'icon-games',
									'Globe'                  => 'icon-globe',
									'Globe - download'       => 'icon-globe-download',
									'Globe - upload'         => 'icon-globe-upload',
									'Hard Drive (HDD)'       => 'icon-drive',
									'HDTV'                   => 'icon-hdtv',
									'Heart'                  => 'icon-heart',
									'History'                => 'icon-history',
									'Home'                   => 'icon-home',
									'Info'                   => 'icon-info',
									'Laptop'                 => 'icon-laptop',
									'Lightbulb'              => 'icon-light-on',
									'Lock'                   => 'icon-lock-closed',
									'Magnifying Glass'       => 'icon-magnify',
									'Megaphone'              => 'icon-megaphone',
									'Money'                  => 'icon-money',
									'Movie'                  => 'icon-movie',
									'MP3 Player'             => 'icon-mp3',
									'MS Word Document'       => 'icon-ms-word',
									'Music'                  => 'icon-music',
									'Network'                => 'icon-network',
									'News'                   => 'icon-news',
									'Notebook'               => 'icon-notebook',
									'PDF Document'           => 'icon-pdf',
									'Photos'                 => 'icon-photos',
									'Notebook'               => 'icon-notebook',
									'Refresh'                => 'icon-refresh',
									'RSS'                    => 'icon-rss',
									'Shield (blue)'          => 'icon-shield-blue',
									'Shield (green)'         => 'icon-shield-green',
									'Smartphone'             => 'icon-smart-phone',
									'Star'                   => 'icon-star',
									'Support'                => 'icon-support',
									'Tools'                  => 'icon-tools',
									'Users'                  => 'icon-user-group',
									'vCard'                  => 'icon-vcard',
									'Video Camera'           => 'icon-video-camera',
									'X'                      => 'icon-x'
								),
								'save_always' => true,
			              ),
							array(
									 'group'      => __('General', 'tt_orbit'),
									 'heading'       => __('Content', 'tt_orbit'),
						    		 'type'          => "textarea_html",
						    		 'holder'        => 'div',
						    		 'param_name'    => 'content',
						    		 'value'      => __("<p>This text is displayed next to the icon.</p>", 'tt_orbit')
			              	),
							array(
							  'group'       => __('URL (link)', 'tt_orbit'),
			                  'type'        => 'vc_link',
			                  'holder'      => 'div',
			                  //'heading'   => ** this is empty for cleaner user-interface
			                  'param_name'  => 'url',
			                  'description' => __('Click "Select URL" to link this element. (optional)', 'tt_orbit')
			              ),
			              array(
			              	  'group'       => __('Lightbox', 'tt_orbit'),
			                  'type'        => 'textfield',
			                  'holder'      => 'div',
			                  'heading'     => __('Lightbox', 'tt_orbit'),
			                  'param_name'  => "lightbox_content",
			                  'description' => __('Display content inside a lightbox by entering the URL here. This will override any URL (link) settings on the previous tab. <a href="https://s3.amazonaws.com/Plugin-Vision/lightbox-samples.html" target="_blank">Lightbox content samples &rarr;</a>', 'tt_orbit')
			              ),  
			              array(
			              	  'group'       => __('Lightbox', 'tt_orbit'),
			                  'type'        => 'textfield',
			                  'holder'      => 'div',
			                  'heading'     => __('Lightbox text', 'tt_orbit'),
			                  'param_name'  => 'lightbox_description',
			                  'description' => __('This text is displayed within the lightbox (optional)', 'tt_orbit')
			              ),
			              array(
			               	  'group'       => __('CSS Class', 'tt_orbit'),
			                  'type'        => 'textfield',
			                  'holder'      => 'div',
			                  'heading'     => __('CSS class name', 'tt_orbit'),
			                  'param_name'  => 'custom_css_class',
			                  'description' => __('Give this element an extra CSS class name if you wish to refer to it in a CSS file. (optional)', 'tt_orbit')
			              ),
			           )
	) 
);// END vc_map

/*--------------------------------------------------------------
Orbit - Image Box 1
--------------------------------------------------------------*/
   vc_map( array(
        'name'        => __("Image Box - square", 'tt_orbit'),
        'description' => __("A callout box with image and text", 'tt_orbit'),
        'base'        => "orbit_imagebox_1",
        'controls'    => 'full',
        'icon'        => plugins_url('images/backend-editor/orbit-menu-image-box-1.png', __FILE__),
        'js_view'     => 'OrbitImage_1',
        'category'    => __('Orbit', 'tt_orbit'),
        "params"      => array(
			               array(
			               	  'group'         => __('General', 'tt_orbit'),
			                  'type'          => "attach_image",
			                  'heading'       => __("Image", 'tt_orbit'),
			                  'param_name'    => "attachment_id",
			              ),
			               array(
			               	  'group'         => __('General', 'tt_orbit'),
			                  'type'          => "textfield",
			                  'heading'       => __("Main Title", 'tt_orbit'),
			                  'value'         => __("Main Title", 'tt_orbit'),
			                  'param_name'    => "main_title",
			                  'description' => __('This is the larger more prominent title.', 'tt_orbit')
			              ),
			               array(
			               	  'group'         => __('General', 'tt_orbit'),
			                  'type'          => "textfield",
			                  'heading'       => __("Sub Title", 'tt_orbit'),
			                  'value'         => __("Sub Title", 'tt_orbit'),
			                  'param_name'    => "sub_title"
			              ),
			              array(
			              	 'group'         => __('General', 'tt_orbit'),
				    		 'type'          => "textarea_html",
				    		 'heading'       => __('Content', 'tt_orbit'),
				    		 'param_name'    => 'content',
				    		 'value'      => __("<p>Edit this text with custom content.</p>", 'tt_orbit')
			              ),
			              array(
			               	  'group'         => __('Design', 'tt_orbit'),
			               	  'heading'       => __("Box BG color", 'tt_orbit'),
			                  'type'          => 'colorpicker',
			                  'param_name'    => 'box_bg_color',
			                  'value'         => '#fff',
			                  'description' => __('The main background color of the box', 'tt_orbit')
			              ),
			              array(
			              	  'group'         => __('Design', 'tt_orbit'),
			              	  'heading'       => __("Image Border Width", 'tt_orbit'),
			                  'type'          => 'textfield',
			                  'param_name'    => "img_border_width",
			                  'value'         => "8px",
			                  'description' => __('The colored border below the image', 'tt_orbit')
			              ),
			              array(
			              	  'group'         => __('Design', 'tt_orbit'),
			              	  'heading'       => __("Image Border Color", 'tt_orbit'),
			                  'type'          => 'colorpicker',
			                  'param_name'    => "img_border_color",
			                  'value'         => "#cf6e6e"
			              ),
			              array(
			              	  'group'         => __('Design', 'tt_orbit'),
			              	  'heading'       => __("Main Title Color", 'tt_orbit'),
			                  'type'          => 'colorpicker',
			                  'param_name'    => "main_title_color",
			                  'value'         => "#cf6e6e"
			              ),
			              array(
							  'group'       => __('URL (link)', 'tt_orbit'),
			                  'type'        => 'vc_link',
			                  //'heading'   => ** this is empty for cleaner user-interface
			                  'param_name'  => 'url',
			                  'description' => __('Click "Select URL" to link this element. (optional)', 'tt_orbit')
			              ),
			              array(
			              	  'group'       => __('Lightbox', 'tt_orbit'),
			                  'type'        => 'textfield',
			                  'heading'     => __('Lightbox', 'tt_orbit'),
			                  'param_name'  => "lightbox_content",
			                  'description' => __('Display content inside a lightbox by entering the URL here. This will override any URL (link) settings on the previous tab. <a href="https://s3.amazonaws.com/Plugin-Vision/lightbox-samples.html" target="_blank">Lightbox content samples &rarr;</a>', 'tt_orbit')
			              ),  
			              array(
			              	  'group'       => __('Lightbox', 'tt_orbit'),
			                  'type'        => 'textfield',
			                  'heading'     => __('Lightbox text', 'tt_orbit'),
			                  'param_name'  => 'lightbox_description',
			                  'description' => __('This text is displayed within the lightbox (optional)', 'tt_orbit')
			              ),
			              array(
			               	  'group'       => __('CSS Class', 'tt_orbit'),
			                  'type'        => 'textfield',
			                  'heading'     => __('CSS class name', 'tt_orbit'),
			                  'param_name'  => 'custom_css_class',
			                  'description' => __('Give this element an extra CSS class name if you wish to refer to it in a CSS file. (optional)', 'tt_orbit')
			              ),					              
			              						              						              						           
        				)
			) 
);// END vc_map

/*--------------------------------------------------------------
Orbit - Image Box 2
--------------------------------------------------------------*/
   vc_map( array(
        'name'        => __("Image Box - round", 'tt_orbit'),
        'description' => __("A callout box with image, text and icon", 'tt_orbit'),
        'base'        => "orbit_imagebox_2",
        'controls'    => 'full',
        'js_view'     => 'OrbitImage_2',
        'icon'        => plugins_url('images/backend-editor/orbit-menu-image-box-2.png', __FILE__),
        'category'    => __('Orbit', 'tt_orbit'),
        "params"      => array(
														
			               array(
			               	  'group'         => __('General', 'tt_orbit'),
			                  'type'          => "attach_image",
			                  'heading'       => __("Image", 'tt_orbit'),
			                  'param_name'    => "attachment_id"
			              ),
			               array(
			               	 'group'         => __('General', 'tt_orbit'),
				    		 'type'          => "textarea_html",
				    		 'heading'       => __('Content', 'tt_orbit'),
				    		 'param_name'    => 'content',
				    		 'value'         => __("<h2>Heading</h2><p>Edit this text with custom content.</p>", 'tt_orbit')
			              ),
		        		//START icons
		        		array(
							'group'   => __('Icon', 'tt_orbit'),		        		
							'type'    => 'dropdown',
							'heading' => __( 'Icon library', 'tt_orbit' ),
							'value'   => array(
									__( 'Font Awesome', 'tt_orbit' )        => 'fontawesome',
									__( 'Open Iconic', 'tt_orbit' )         => 'openiconic',
									__( 'Typicons', 'tt_orbit' )            => 'typicons',
									__( 'Entypo', 'tt_orbit' )              => 'entypo',
									__( 'Linecons', 'tt_orbit' )            => 'linecons',
									__( 'Do not display icon', 'tt_orbit' ) => '',									
								),
							'admin_label' => true,
							'param_name'  => 'type',
							'description' => __( 'Select icon library.', 'tt_orbit' ),
							'std'         => array(__( 'Font Awesome', 'tt_orbit' ) => 'fontawesome'),
						),
						//fontawesome
						array(
							'group'        => __('Icon', 'tt_orbit'),					
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_fontawesome',
							'value'        => 'fa fa-adjust',
							'settings'     => array(
							'emptyIcon'    => false,
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'fontawesome',
							),
							'description'  => __( 'Select icon from library.', 'tt_orbit' ),
						),
						//openiconic
						array(
							'group'        => __('Icon', 'tt_orbit'),					
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_openiconic',
							'value'        => 'vc-oi vc-oi-dial',
							'settings'     => array(
							'emptyIcon'    => false,
							'type'         => 'openiconic',
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'openiconic',
							),
							'description'  => __( 'Select icon from library.', 'tt_orbit' ),
						),
						//typicons
						array(
							'group'        => __('Icon', 'tt_orbit'),					
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_typicons',
							'value'        => 'typcn typcn-adjust-brightness',
							'settings'     => array(
							'emptyIcon'    => false,
							'type'         => 'typicons',
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'typicons',
							),
							'description'  => __( 'Select icon from library.', 'tt_orbit' ),
						),
						//entypo
						array(
							'group'        => __('Icon', 'tt_orbit'),					
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_entypo',
							'value'        => 'entypo-icon entypo-icon-note',
							'settings'     => array(
							'emptyIcon'    => false,
							'type'         => 'entypo',
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'entypo',
							),
						),
						//linecons
						array(
							'group'        => __('Icon', 'tt_orbit'),
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_linecons',
							'value'        => 'vc_li vc_li-heart',
							'settings'     => array(
							'emptyIcon'    => false,
							'type'         => 'linecons',
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'linecons',
							),
							'description'  => __( 'Select icon from library.', 'tt_orbit' ),
						),
						//END icons

					      array(
					      		'group'       => __('Icon', 'tt_orbit'),
								'type'        => 'textarea_raw_html',
								'heading'     => __('Custom Icon', 'tt_orbit'),
								'description' => __('Display your own custom icon by entering it\'s HTML code here. Give this HTML element an additional CSS class name of "orbit-custom-icon" for proper positioning.', 'tt_orbit'),
								'param_name'  => 'custom_icon',
								'value'=>'',
							),							
			               array(
			               	  'group'         => __('Icon', 'tt_orbit'),			               
			                  'type'          => "attach_image",
			                  'holder'        => 'div',
			                  'heading'       => __("Custom Icon Upload", 'tt_orbit'),
			                  'param_name'    => "custom_icon_upload",
			                  'description'   => __('Upload a custom icon, this overwrites the Custom Icon and Icon settings.', 'tt_orbit')
			              ),				              
			               array(
			               	  'group'         => __('Design', 'tt_orbit'),
			               	  'heading'       => __("Box BG color", 'tt_orbit'),
			                  'type'          => 'colorpicker',
			                  'param_name'    => 'box_bg_color',
			                  'value'         => '#fff',
			                  'description' => __('The main background color of the box', 'tt_orbit')
			              ),
			               array(
			               	  'group'         => __('Design', 'tt_orbit'),
			                  'type'          => 'colorpicker',
			                  'heading'       => __("Icon BG color", 'tt_orbit'),
			                  'param_name'    => 'icon_bg_color',
			                  'value'         => '#87C442',
			                  'description' => __('The circle behind the vector icon', 'tt_orbit')
			              ),
			               array(
			               	  'group'         => __('Design', 'tt_orbit'),
			                  'type'          => 'colorpicker',
			                  'heading'       => __("Icon color", 'tt_orbit'),
			                  'param_name'    => 'icon_color',
			                  'value'         => '#fff',
			                  'description' => __('The vector icon', 'tt_orbit')
			              ),
			               array(
			               	  'group'         => __('Design', 'tt_orbit'),
			                  'type'          => 'colorpicker',
			                  'heading'       => __("Link color", 'tt_orbit'),
			                  'param_name'    => 'link_color',
			                  'value'         => '#3b86c4',
			                  'description' => __('The link color (optional)', 'tt_orbit')
			              ),
			              array(
							  'group'       => __('URL (link)', 'tt_orbit'),
			                  'type'        => 'vc_link',
			                  //'heading'   => ** this is empty for cleaner user-interface
			                  'param_name'  => 'url',
			                  'description' => __('Click "Select URL" to link this element. (optional)', 'tt_orbit')
			              ),
			              array(
			               	  'group'       => __('URL (link)', 'tt_orbit'),
			               	  'heading'     => __('Link text', 'tt_orbit'),
			                  'type'        => 'textfield',
			                  'param_name'  => 'link_text',
			                  'description' => __('This text is displayed near the bottom of the box. (optional)', 'tt_orbit')
			              ),
			              array(
			              	  'group'       => __('Lightbox', 'tt_orbit'),
			                  'type'        => 'textfield',
			                  'heading'     => __('Lightbox', 'tt_orbit'),
			                  'param_name'  => "lightbox_content",
			                  'description' => __('Display content inside a lightbox by entering the URL here. This will override any URL (link) settings on the previous tab. <a href="https://s3.amazonaws.com/Plugin-Vision/lightbox-samples.html" target="_blank">Lightbox content samples &rarr;</a>', 'tt_orbit')
			              ),  
			              array(
			              	  'group'       => __('Lightbox', 'tt_orbit'),
			                  'type'        => 'textfield',
			                  'heading'     => __('Lightbox text', 'tt_orbit'),
			                  'param_name'  => 'lightbox_description',
			                  'description' => __('This text is displayed within the lightbox (optional)', 'tt_orbit')
			              ),
			              array(
			               	  'group'       => __('CSS Class', 'tt_orbit'),
			                  'type'        => 'textfield',
			                  'heading'     => __('CSS class name', 'tt_orbit'),
			                  'param_name'  => 'custom_css_class',
			                  'description' => __('Give this element an extra CSS class name if you wish to refer to it in a CSS file. (optional)', 'tt_orbit')
			              ),				              
			              						              						              						           
        				)
			) 
);// END vc_map

/*--------------------------------------------------------------
Orbit - Number Counter
--------------------------------------------------------------*/
   vc_map( array(
        'name'        => __("Number Counter", 'tt_orbit'),
        'description' => __("Animated number counter", 'tt_orbit'),
        'base'        => "orbit_number_counter",  
        'controls'    => 'full',
        'class'       => 'orbit-number-counter',
        'icon'        => plugins_url('images/backend-editor/orbit-menu-number-counter.png', __FILE__),
        'category'    => __('Orbit', 'tt_orbit'),
        'js_view'     => 'OrbitNumberCounter',
        'show_settings_on_create' => true,
							"params"     => array(
					        // add params same as with any other content element
						array(
							'value'      => '125',
							'group'      => __('General', 'tt_orbit'),
							'type'       => 'textfield',
							'heading'    => __('Number', 'tt_orbit'),
							'param_name' => 'number',
						),
						array(
							'group'      => __('General', 'tt_orbit'),
							'heading'    => __('Divider Height', 'tt_orbit'),
							'value'      => '4px',
							'type'       => 'textfield',
							'param_name' => 'divider_height',
							'description' => __("This divider is displayed between the number and title.", 'tt_orbit')
						),
						array(
							'value'      => 'Lorem Ipsum',
							'group'      => __('General', 'tt_orbit'),
							'type'       => 'textfield',
							'heading'    => __('Title', 'tt_orbit'),
							'param_name' => 'title',
							'description' => __("This text is displayed below the divider.", 'tt_orbit')
						),
						array(
							'value'      => '#000',
							'group'      => __('Design', 'tt_orbit'),
							'type'       => 'colorpicker',
							'heading'    => __('Number Color', 'tt_orbit'),
							'param_name' => 'number_color',
						),
						array(
							'value'      => '#e1e1e1',
							'group'      => __('Design', 'tt_orbit'),
							'type'       => 'colorpicker',
							'heading'    => __('Divider Color', 'tt_orbit'),
							'param_name' => 'divider_color',
							'description' => __("This divider is displayed between the number and title.", 'tt_orbit')
						),
						array(
							'value'      => '#000',
							'group'      => __('Design', 'tt_orbit'),
							'type'       => 'colorpicker',
							'heading'    => __('Title Color','tt_orbit'),
							'param_name' => 'title_color',
						),
						array(
			               	  'group'       => __('CSS Class', 'tt_orbit'),
			                  'type'        => 'textfield',
			                  'holder'      => 'div',
			                  'heading'     => __('CSS class name', 'tt_orbit'),
			                  'param_name'  => 'custom_css_class',
			                  'description' => __('Give this element an extra CSS class name if you wish to refer to it in a CSS file. (optional)', 'tt_orbit')
			              ),								

					),
     	   		) 
);// END vc_map

/*--------------------------------------------------------------
Orbit - Pricing Box
--------------------------------------------------------------*/
vc_map(      
	array(
		'category'    => __('Orbit', 'tt_orbit'),
        'name'        => __("Pricing Box", 'tt_orbit'),
        'description' => __("Stylish pricing box", 'tt_orbit'),
        'base'        => "orbit_pricing_box",
        'controls'    => 'full',
        'class'       => 'orbit-pricing-box',
        'js_view'     => 'OrbitPricingBox',
        'icon'        => plugins_url('images/backend-editor/orbit-menu-pricing-box.png', __FILE__),
        "params"      => array(
			              array(
			              	  'group'      => __('Design', 'tt_orbit'),
			              	  'heading'    => __('Layout style', 'tt_orbit'),
			                  'type'       => 'dropdown',
			                  'holder'     => 'div',
			                  'param_name' => 'style',
			                  'value' => array('Style 1'=> 'style-1','Style-2'=> 'style-2'),
			                  'description' => __('<a href="http://s3.truethemes.net/plugin-assets/vision-pricing-samples.png" target="_blank">View available layouts &rarr;</a>', 'tt_orbit'),
			                  'save_always' => true,
			              ),
			               array(
			               	  'group'      => __('Design', 'tt_orbit'),
			               	  'heading'    => __("Color scheme", 'tt_orbit'),
			                  'type'       => 'dropdown',
			                  'holder'     => 'div',
			                  'param_name' => "color",
			                  'value' => array(
									'Autumn'     => 'autumn',
									'Black'      => 'black',
									'Black 2'    => 'black-2',
									'Blue'       => 'blue',
									'Blue Grey'  => 'blue-grey',
									'Cool Blue'  => 'cool-blue',
									'Coffee'     => 'coffee',
									'Fire'       => 'fire',
									'Golden'     => 'golden',
									'Green'      => 'green',
									'Green 2'    => 'green-2',
									'Grey'       => 'grey',
									'Lime Green' => 'lime-green',
									'Navy'       => 'navy',
									'Orange'     => 'orange',
									'Periwinkle' => 'periwinkle',
									'Pink'       => 'pink',
									'Purple'     => 'purple',
									'Purple 2'   => 'purple-2',
									'Red'        => 'red',
									'Red 2'      => 'red-2',
									'Royal Blue' => 'royal-blue',
									'Silver'     => 'silver',
									'Sky Blue'   => 'sky-blue',
									'Teal Grey'  => 'teal-grey',
									'Teal'       => 'teal',
									'Teal 2'     => 'teal-2',
									'White'      => 'white',
									),
			                  'description' => __('<a href="http://s3.truethemes.net/plugin-assets/shortcode-style-guide/style-guide.html" target="_blank">View available colors &rarr;</a>', 'tt_orbit'),
			                  'save_always' => true,
			              ),
							array(
							  'group'       => 'Content',
							  'heading'     => __('Price', 'tt_orbit'),
			                  'type'        => 'textfield',
			                  'holder'      => 'div',
			                  'param_name'  => 'price',
			                  'value'       => '39',
			              ),
							array(
							  'group'       => 'Content',
							  'heading'     => __('Currency symbol', 'tt_orbit'),
			                  'type'        => 'textfield',
			                  'holder'      => 'div',
			                  'param_name'  => 'currency',
			                  'value'       => '$',
			                  'description' => __('ie. $, &euro;', 'tt_orbit')
			              ),
							array(
							  'group'       => 'Content',
							  'heading'     => __('Plan name', 'tt_orbit'),
			                  'type'        => 'textfield',
			                  'holder'      => 'div',
			                  'param_name'  => 'plan',
			                  'value'       => 'Pro',
			                  'description' => __('ie. Basic, Pro, Premium', 'tt_orbit')
			              ),
							array(
							  'group'       => 'Content',
							  'heading'     => __('Term', 'tt_orbit'),
			                  'type'        => 'textfield',
			                  'holder'      => 'div',
			                  'param_name'  => 'term',
			                  'value'       => 'per month',
			                  'description' => __('ie. per month, per year', 'tt_orbit')
			              ),
							array(
							  'group'       => 'Content',
							  'heading'     => __('Details', 'tt_orbit'),
			                  'type'        => 'textarea_html',
			                  'holder'      => 'div',
			                  'param_name'  => 'content',
			                  'value'       => __("<ul><li><strong>Full</strong> Email Support</li><li><strong>25GB</strong> of Storage</li><li><strong>5</strong> Domains</li><li><strong>10</strong> Email Addresses</li></ul>", 'tt_orbit')
			              ),
							array(
							  'group'      => 'Button (URL)',
			                  'type'       => "textfield",
			                  'holder'     => 'div',
			                  'heading'    => __("Button text", 'tt_orbit'),
			                  'param_name' => 'button_label',
			                  'value'      => 'Sign Up',
			                  'description' => __('ie. Sign up, Purchase, Register', 'tt_orbit')
			              ),
							array(
			               	  'group'      => 'Button (URL)',
			               	  'heading'    => __("Button color", 'tt_orbit'),
			                  'type'       => 'dropdown',
			                  'holder'     => 'div',
			                  'param_name' => "button_color",
			                  'value' => array(
									'Autumn'     => 'autumn',
									'Black'      => 'black',
									'Black 2'    => 'black-2',
									'Blue'       => 'blue',
									'Blue Grey'  => 'blue-grey',
									'Cool Blue'  => 'cool-blue',
									'Coffee'     => 'coffee',
									'Fire'       => 'fire',
									'Golden'     => 'golden',
									'Green'      => 'green',
									'Green 2'    => 'green-2',
									'Grey'       => 'grey',
									'Lime Green' => 'lime-green',
									'Navy'       => 'navy',
									'Orange'     => 'orange',
									'Periwinkle' => 'periwinkle',
									'Pink'       => 'pink',
									'Purple'     => 'purple',
									'Purple 2'   => 'purple-2',
									'Red'        => 'red',
									'Red 2'      => 'red-2',
									'Royal Blue' => 'royal-blue',
									'Silver'     => 'silver',
									'Sky Blue'   => 'sky-blue',
									'Teal Grey'  => 'teal-grey',
									'Teal'       => 'teal',
									'Teal 2'     => 'teal-2',
									'White'      => 'white',
									),
			                  'description' => __('<a href="http://s3.truethemes.net/plugin-assets/shortcode-style-guide/style-guide.html" target="_blank">View available colors &rarr;</a>', 'tt_orbit'),
			                  'save_always' => true,
			              ),
							array(
							  'group'      => 'Button (URL)',
							  'heading'    => __('Button Size', 'tt_orbit'),
			                  'type'       => 'dropdown',
			                  'holder'     => 'div',
			                  'param_name' => "button_size",
			                  'value' => array('Small'=> 'small','Large'=> 'large','Jumbo'=> 'jumbo'),
			                  'save_always' => true,
			              ),
							array(
							  'group'       => 'Button (URL)',
							  //'heading'   => ** this is empty for cleaner user-interface
			                  'type'        => 'vc_link',
			                  'holder'      => 'div',
			                  'param_name'  => 'url',
			                  'description' => __('Click "Select URL" to link this button.', 'tt_orbit')
			              ),
							array(
			               	  'group'       => __('CSS Class', 'tt_orbit'),
			                  'type'        => 'textfield',
			                  'holder'      => 'div',
			                  'heading'     => __('CSS class name', 'tt_orbit'),
			                  'param_name'  => 'custom_css_class',
			                  'description' => __('Give this element an extra CSS class name if you wish to refer to it in a CSS file. (optional)', 'tt_orbit')
			              ),
		)
	) 
);// END vc_map

/*--------------------------------------------------------------
Orbit - Progress Bar
--------------------------------------------------------------*/
   vc_map( array(
        'name'        => __("Progress Bar", 'tt_orbit'),
        'description' => __("Animated progress bar", 'tt_orbit'),
        'base'        => "orbit_progress_bar",
        'controls'    => 'full',
        'class'       => 'orbit-progress-bar',
        'icon'        => plugins_url('images/backend-editor/orbit-menu-progress-bar.png', __FILE__),
        'category'    => __('Orbit', 'tt_orbit'),
        'js_view'     => 'OrbitProgressBar',
        'show_settings_on_create' => true,
        "params"      => array(
								array(
									'group'      => __('General', 'tt_orbit'),
									'type'       => 'textfield',
									'heading'    => __('Title', 'tt_orbit'),
									'value'      => 'Lorem Ipsum',
									'param_name' => 'title',
								),
								array(
									'group'      => __('General', 'tt_orbit'),
									'type'       => 'textfield',
									'heading'    => __('Number', 'tt_orbit'),
									'value'      => '50',
									'param_name' => 'number',
								),
								array(
									'group'      => __('General', 'tt_orbit'),
									'type'       => 'textfield',
									'heading'    => __('Symbol', 'tt_orbit'),
									'param_name' => 'symbol',
									'value'      => '%',
								),
								array(
									'group'      => __('Design', 'tt_orbit'),
									'heading'      => __('Title Color', 'tt_orbit'),
									'value'      => '#000',
									'type'       => 'colorpicker',
									'param_name' => 'title_color'
								),
								array(
									'group'      => __('Design', 'tt_orbit'),
									'heading'      => __('Number Color', 'tt_orbit'),
									'value'      => '#000',
									'type'       => 'colorpicker',
									'param_name' => 'number_color'
								),
								array(
									'group'      => __('Design', 'tt_orbit'),
									'heading'      => __('Bar Color', 'tt_orbit'),
									'value'      => '#a2dce2',
									'type'       => 'colorpicker',
									'param_name' => 'bar_color',
								),
								array(
									'group'      => __('Design', 'tt_orbit'),
									'heading'    => __('Track Color', 'tt_orbit'),
									'value'      => '#e1e1e1',
									'type'       => 'colorpicker',
									'param_name' => 'track_color',
								),
								array(
			               	  'group'       => __('CSS Class', 'tt_orbit'),
			                  'type'        => 'textfield',
			                  'holder'      => 'div',
			                  'heading'     => __('CSS class name', 'tt_orbit'),
			                  'param_name'  => 'custom_css_class',
			                  'description' => __('Give this element an extra CSS class name if you wish to refer to it in a CSS file. (optional)', 'tt_orbit')
			              ),									
					    ),
     	   		) 
	);// END vc_map

/*--------------------------------------------------------------
Orbit - Progress Bar (vertical)
--------------------------------------------------------------*/
   vc_map( array(
        'name'        => __("Progress Bar 2", 'tt_orbit'),
        'description' => __("Animated progress bar (vertical)", 'tt_orbit'),
        'base'        => "orbit_progress_bar_vertical",
        'controls'    => 'full',
        'class'       => 'orbit-progress-bar',
        'icon'        => plugins_url('images/backend-editor/orbit-menu-progress-bar-vertical.png', __FILE__),
        'category'    => __('Orbit', 'tt_orbit'),
        'js_view'     => 'OrbitProgressBar2',
        'show_settings_on_create' => true,
        "params"      => array(
					        array(
									'group'      => __('General', 'tt_orbit'),
									'type'       => 'textfield',
									'heading'    => __('Title', 'tt_orbit'),
									'value'      => 'Lorem Ipsum',
									'param_name' => 'title',
								),
								array(
									'group'      => __('General', 'tt_orbit'),
									'type'       => 'textfield',
									'heading'    => __('Number', 'tt_orbit'),
									'value'      => '50',
									'param_name' => 'number',
								),
								array(
									'group'      => __('General', 'tt_orbit'),
									'type'       => 'textfield',
									'heading'    => __('Symbol', 'tt_orbit'),
									'param_name' => 'symbol',
									'value'      => '%',
								),
								array(
									'group'      => __('Design', 'tt_orbit'),
									'heading'      => __('Title Color', 'tt_orbit'),
									'value'      => '#000',
									'type'       => 'colorpicker',
									'param_name' => 'title_color'
								),
								array(
									'group'      => __('Design', 'tt_orbit'),
									'heading'      => __('Number Color', 'tt_orbit'),
									'value'      => '#000',
									'type'       => 'colorpicker',
									'param_name' => 'number_color'
								),
								array(
									'group'      => __('Design', 'tt_orbit'),
									'heading'      => __('Bar Color', 'tt_orbit'),
									'value'      => '#a2dce2',
									'type'       => 'colorpicker',
									'param_name' => 'bar_color',
								),
								array(
									'group'      => __('Design', 'tt_orbit'),
									'heading'    => __('Track Color', 'tt_orbit'),
									'value'      => '#e1e1e1',
									'type'       => 'colorpicker',
									'param_name' => 'track_color',
								),
								array(
			               	  'group'       => __('CSS Class', 'tt_orbit'),
			                  'type'        => 'textfield',
			                  'holder'      => 'div',
			                  'heading'     => __('CSS class name', 'tt_orbit'),
			                  'param_name'  => 'custom_css_class',
			                  'description' => __('Give this element an extra CSS class name if you wish to refer to it in a CSS file. (optional)', 'tt_orbit')
			              ),
					    ),
     	   		) 
	);// END vc_map

/*--------------------------------------------------------------
Orbit - Service List Item
--------------------------------------------------------------*/
   vc_map( array(
        'name'        => __("Service List Item", 'tt_orbit'),
        'description' => __("Animated services list", 'tt_orbit'),
        'base'        => "orbit_services",  
        'controls'    => 'full',
        'class'       => 'orbit-service-list',
        'icon'        => plugins_url('images/backend-editor/orbit-menu-feature-list.png', __FILE__),
        'category'    => __('Orbit', 'tt_orbit'),
        'js_view'     => 'OrbitServiceListItem',
        'show_settings_on_create' => true,
        "params"      => array(
        			              array(
			              	'group'         => __('General', 'tt_orbit'),
				    		 'type'          => "textarea_html",
				    		 'holder'        => 'div',
				    		 'heading'       => __('Content', 'tt_orbit'),
				    		 'param_name'    => 'content',
				    		 'value'      => __("<h4>Heading</h4><p>Lorem ipsum dolor sit amet.</p>", 'tt_orbit')
			              ),	
					        array(
					      		'group'         => __('General', 'tt_orbit'),
								'type'          => 'dropdown',
								'heading'       => __("Animation", 'tt_orbit'),
								'param_name'    => "animate",
								'value'         => array(
									'fly-in from center' => 'in_from_center',
									'fly-in from top'    => 'in_from_top',
									'fly-in from right'  => 'in_from_right',
									'fly-in from bottom' => 'in_from_bottom',
									'fly-in from left'   => 'in_from_left',
									'no animation'       => 'animate_none',
								),
								'save_always' => true,
					        ),
		        		//START icons
		        		array(
							'group'   => __('Icon', 'tt_orbit'),		        		
							'type'    => 'dropdown',
							'heading' => __( 'Icon library', 'tt_orbit' ),
							'value'   => array(
									__( 'Font Awesome', 'tt_orbit' )        => 'fontawesome',
									__( 'Open Iconic', 'tt_orbit' )         => 'openiconic',
									__( 'Typicons', 'tt_orbit' )            => 'typicons',
									__( 'Entypo', 'tt_orbit' )              => 'entypo',
									__( 'Linecons', 'tt_orbit' )            => 'linecons',
									__( 'Do not display icon', 'tt_orbit' ) => '',									
								),
							'admin_label' => true,
							'param_name'  => 'type',
							'description' => __( 'Select icon library.', 'tt_orbit' ),
							'std'         => array(__( 'Font Awesome', 'tt_orbit' ) => 'fontawesome'),
						),
						//fontawesome
						array(
							'group'        => __('Icon', 'tt_orbit'),					
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_fontawesome',
							'value'        => 'fa fa-adjust',
							'settings'     => array(
							'emptyIcon'    => false,
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'fontawesome',
							),
							'description'  => __( 'Select icon from library.', 'tt_orbit' ),
						),
						//openiconic
						array(
							'group'        => __('Icon', 'tt_orbit'),					
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_openiconic',
							'value'        => 'vc-oi vc-oi-dial',
							'settings'     => array(
							'emptyIcon'    => false,
							'type'         => 'openiconic',
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'openiconic',
							),
							'description'  => __( 'Select icon from library.', 'tt_orbit' ),
						),
						//typicons
						array(
							'group'        => __('Icon', 'tt_orbit'),					
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_typicons',
							'value'        => 'typcn typcn-adjust-brightness',
							'settings'     => array(
							'emptyIcon'    => false,
							'type'         => 'typicons',
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'typicons',
							),
							'description'  => __( 'Select icon from library.', 'tt_orbit' ),
						),
						//entypo
						array(
							'group'        => __('Icon', 'tt_orbit'),					
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_entypo',
							'value'        => 'entypo-icon entypo-icon-note',
							'settings'     => array(
							'emptyIcon'    => false,
							'type'         => 'entypo',
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'entypo',
							),
						),
						//linecons
						array(
							'group'        => __('Icon', 'tt_orbit'),
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_linecons',
							'value'        => 'vc_li vc_li-heart',
							'settings'     => array(
							'emptyIcon'    => false,
							'type'         => 'linecons',
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'linecons',
							),
							'description'  => __( 'Select icon from library.', 'tt_orbit' ),
						),
						//END icons

					      array(
					      		'group'       => __('Icon', 'tt_orbit'),
								'type'        => 'textarea_raw_html',
								'heading'     => __('Custom Icon', 'tt_orbit'),
								'description' => __('Display your own custom icon by entering it\'s HTML code here. Give this HTML element an additional CSS class name of "orbit-custom-icon" for proper positioning.', 'tt_orbit'),
								'param_name'  => 'custom_icon',
								'value'=>'',
							),
			               array(
			              	'group'         => __('Icon', 'tt_orbit'),			               
			                  'type'          => "attach_image",
			                  'holder'        => 'div',
			                  'heading'       => __("Custom Icon Upload", 'tt_orbit'),
			                  'param_name'    => "custom_icon_upload",
			                  'description'   => __('Upload a custom icon, this overwrites the Custom Icon and Icon settings.', 'tt_orbit')
			              ),									

					        array(
					        	'group'      => __('Design', 'tt_orbit'),
								'value'       => '#d3565a',
								'type'        => 'colorpicker',
								'heading'     => __('Icon Color', 'tt_orbit'),
								'param_name'  => 'icon_color',
							),
					        array(
					        	'group'         => __('Design', 'tt_orbit'),
								'type'          => 'colorpicker',
								'heading'       => __('Icon Color (hover)', 'tt_orbit'),
								'param_name'    => "icon_color_hover",
								'value'         => "#ffffff",
								'description'   => __("The color of the icon when hovered.", 'tt_orbit')
					        ),	
				            array(
				            	'group'         => __('Design', 'tt_orbit'),
								'type'          => "textfield",
								'holder'        => 'div',
								'heading'       => __("Border Width", 'tt_orbit'),
								'param_name'    => "border_width",
								'value'         => "2px",
								'description'   => __("The width of the circle border.", 'tt_orbit')
				              ),
					        array(
					        	'group'         => __('Design', 'tt_orbit'),
								'type'          => 'colorpicker',
								'heading'       => __("Border Color", 'tt_orbit'),
								'param_name'    => "border_color",
								'value'         => "#a2dce2",
								'description'   => __("The color of the circle border.", 'tt_orbit')
					        ),
					        array(
					        	'group'         => __('Design', 'tt_orbit'),
								'type'          => 'colorpicker',
								'heading'       => __("Background Color", 'tt_orbit'),
								'param_name'    => "bg_color",
								'value'         => "#fff",
								'description'   => __("The color of the circle.", 'tt_orbit')
					        ),
					        array(
					        	'group'         => __('Design', 'tt_orbit'),
								'type'          => 'colorpicker',
								'heading'       => __("Background Color (hover)", 'tt_orbit'),
								'param_name'    => "bg_color_hover",
								'value'         => "#a2dce2",
								'description'   => __("The color of the circle when hovered.", 'tt_orbit')
					        ),								        
							array(
							  'group'       => __('URL (link)', 'tt_orbit'),
			                  'type'        => 'vc_link',
			                  'holder'      => 'div',
			                  //'heading'   => ** this is empty for cleaner user-interface
			                  'param_name'  => 'url',
			                  'description' => __('Click "Select URL" to link this element. (optional)', 'tt_orbit')
			              ),
			              	array(
			              	  'group'       => __('Lightbox', 'tt_orbit'),
			                  'type'        => 'textfield',
			                  'holder'      => 'div',
			                  'heading'     => __('Lightbox', 'tt_orbit'),
			                  'param_name'  => "lightbox_content",
			                  'description' => __('Display content inside a lightbox by entering the URL here. This will override any URL (link) settings on the previous tab. <a href="https://s3.amazonaws.com/Plugin-Vision/lightbox-samples.html" target="_blank">Lightbox content samples &rarr;</a>', 'tt_orbit')
			              ),  
			              	array(
			              	  'group'       => __('Lightbox', 'tt_orbit'),
			                  'type'        => 'textfield',
			                  'holder'      => 'div',
			                  'heading'     => __("Lightbox text", 'tt_orbit'),
			                  'param_name'  => "lightbox_description",
			                  'description' => __('This text is displayed within the lightbox (optional)', 'tt_orbit')
			              ),
			              	array(
			               	  'group'       => __('CSS Class', 'tt_orbit'),
			                  'type'        => 'textfield',
			                  'holder'      => 'div',
			                  'heading'     => __('CSS class name', 'tt_orbit'),
			                  'param_name'  => 'custom_css_class',
			                  'description' => __('Give this element an extra CSS class name if you wish to refer to it in a CSS file. (optional)', 'tt_orbit')
			              ),
					    ),
     	   		) 
);// END vc_map

/*--------------------------------------------------------------
Orbit - Social Icons
--------------------------------------------------------------*/
vc_map(      
	array(
		'category'    => __('Orbit', 'tt_orbit'),
        'name'        => __("Social Icons", 'tt_orbit'),
        'description' => __("Stylish vector social icons", 'tt_orbit'),
        'base'        => "orbit_social",
        'controls'    => 'full',
        'class'       => 'orbit-social-icons',
        'js_view'     => 'OrbitSocialIcons',
        'icon'        => plugins_url('images/backend-editor/orbit-menu-social-icons.png', __FILE__),
        "params"      => array(
			              array(
			              	  'group'      => __('General', 'tt_orbit'),
			              	  'heading'    => __('Design Style', 'tt_orbit'),
			                  'type'       => 'dropdown',
			                  'holder'     => 'div',
			                  'param_name' => 'design',
			                  'value'      => array(
			                  					'Square'      => 'square',
												'Color'       => 'color',
												'Transparent' => 'png',
												'Default'     => 'default'
			                  	),
			                  'description' => __('
			                  	<strong>Square:</strong> white icons, color background<br />
			                  	<strong>Color:</strong> color icons, no background<br />
			                  	<strong>Transparent:</strong> transparent icons, no background<br />
			                  	<strong>Default:</strong> color icons, no background (uses theme’s default link color)', 'tt_orbit'),'save_always' => true,
			              ),
			              array(
			              	  'group'      => __('General', 'tt_orbit'),
			              	  'heading'    => __('Icon Type', 'tt_orbit'),
			                  'type'       => 'dropdown',
			                  'holder'     => 'div',
			                  'param_name' => 'icon_style',
			                  'value'      => array(
			                  					'Normal' => 'normal',
			                  					'Round'  => 'round',
			                  	),
			                  	'save_always' => true,
			              ),
			               array(
							  'group'       => __('Social Accounts', 'tt_orbit'),
							  'heading'     => __('Twitter', 'tt_orbit'),
			                  'type'        => 'vc_link',
			                  'holder'      => 'div',
			                  'param_name'  => 'twitter'
			              ),
			               array(
							  'group'       => __('Social Accounts', 'tt_orbit'),
							  'heading'     => __('Facebook', 'tt_orbit'),
			                  'type'        => 'vc_link',
			                  'holder'      => 'div',
			                  'param_name'  => 'facebook'
			              ),
			               array(
							  'group'       => __('Social Accounts', 'tt_orbit'),
							  'heading'     => __('Dribbble', 'tt_orbit'),
			                  'type'        => 'vc_link',
			                  'holder'      => 'div',
			                  'param_name'  => 'dribbble'
			              ),
			               array(
							  'group'       => __('Social Accounts', 'tt_orbit'),
							  'heading'     => __('Flickr', 'tt_orbit'),
			                  'type'        => 'vc_link',
			                  'holder'      => 'div',
			                  'param_name'  => 'flickr'
			              ),
			               array(
							  'group'       => __('Social Accounts', 'tt_orbit'),
							  'heading'     => __('Google +', 'tt_orbit'),
			                  'type'        => 'vc_link',
			                  'holder'      => 'div',
			                  'param_name'  => 'google'
			              ),
			               array(
							  'group'       => __('Social Accounts', 'tt_orbit'),
							  'heading'     => __('Instagram', 'tt_orbit'),
			                  'type'        => 'vc_link',
			                  'holder'      => 'div',
			                  'param_name'  => 'instagram'
			              ),
			               array(
							  'group'       => __('Social Accounts', 'tt_orbit'),
							  'heading'     => __('Linkedin', 'tt_orbit'),
			                  'type'        => 'vc_link',
			                  'holder'      => 'div',
			                  'param_name'  => 'linkedin'
			              ),
			               array(
							  'group'       => __('Social Accounts', 'tt_orbit'),
							  'heading'     => __('Pinterest', 'tt_orbit'),
			                  'type'        => 'vc_link',
			                  'holder'      => 'div',
			                  'param_name'  => 'pinterest'
			              ),
			               array(
							  'group'       => __('Social Accounts', 'tt_orbit'),
							  'heading'     => __('RSS', 'tt_orbit'),
			                  'type'        => 'vc_link',
			                  'holder'      => 'div',
			                  'param_name'  => 'rss'
			              ),
			               array(
							  'group'       => __('Social Accounts', 'tt_orbit'),
							  'heading'     => __('Skype', 'tt_orbit'),
			                  'type'        => 'vc_link',
			                  'holder'      => 'div',
			                  'param_name'  => 'skype'
			              ),
			               array(
							  'group'       => __('Social Accounts', 'tt_orbit'),
							  'heading'     => __('Vimeo', 'tt_orbit'),
			                  'type'        => 'vc_link',
			                  'holder'      => 'div',
			                  'param_name'  => 'vimeo'
			              ),
			               array(
							  'group'       => __('Social Accounts', 'tt_orbit'),
							  'heading'     => __('Wordpress', 'tt_orbit'),
			                  'type'        => 'vc_link',
			                  'holder'      => 'div',
			                  'param_name'  => 'wordpress'
			              ),
			               array(
							  'group'       => __('Social Accounts', 'tt_orbit'),
							  'heading'     => __('Youtube', 'tt_orbit'),
			                  'type'        => 'vc_link',
			                  'holder'      => 'div',
			                  'param_name'  => 'youtube'
			              ),
			               array(
			               	  'group'       => __('CSS Class', 'tt_orbit'),
			                  'type'        => 'textfield',
			                  'holder'      => 'div',
			                  'heading'     => __('CSS class name', 'tt_orbit'),
			                  'param_name'  => 'custom_css_class',
			                  'description' => __('Give this element an extra CSS class name if you wish to refer to it in a CSS file. (optional)', 'tt_orbit')
			              ),
		)
	) 
);// END vc_map

/*--------------------------------------------------------------
Orbit - Tab 1
--------------------------------------------------------------*/
   vc_map( array(
        'name'                    => __("Tabs 1", 'tt_orbit'),
        'description'             => __("Tabbed content", 'tt_orbit'),
        'base'                    => "orbit_tab_1",
        'controls'                => 'full',
        'content_element'         => true,
        'show_settings_on_create' => true,
		'js_view'                 => 'VcColumnView',
        'icon'        => plugins_url('images/backend-editor/orbit-menu-tabs-1.png', __FILE__),
        'category'                => __('Orbit', 'tt_orbit'),
        "as_parent"               => array('only' => 'orbit_tab_1_content'),
        "params"      => array(
					      array(
			               	  'heading'    => __("Color scheme", 'tt_orbit'),
			                  'type'       => 'dropdown',
			                  'holder'     => 'div',
			                  'param_name' => "color_scheme",
			                  'value' => array(
									'Autumn'     => 'autumn',
									'Black'      => 'black',
									'Black 2'    => 'black-2',
									'Blue'       => 'blue',
									'Blue Grey'  => 'blue-grey',
									'Cool Blue'  => 'cool-blue',
									'Coffee'     => 'coffee',
									'Fire'       => 'fire',
									'Golden'     => 'golden',
									'Green'      => 'green',
									'Green 2'    => 'green-2',
									'Grey'       => 'grey',
									'Lime Green' => 'lime-green',
									'Navy'       => 'navy',
									'Orange'     => 'orange',
									'Periwinkle' => 'periwinkle',
									'Pink'       => 'pink',
									'Purple'     => 'purple',
									'Purple 2'   => 'purple-2',
									'Red'        => 'red',
									'Red 2'      => 'red-2',
									'Royal Blue' => 'royal-blue',
									'Silver'     => 'silver',
									'Sky Blue'   => 'sky-blue',
									'Teal Grey'  => 'teal-grey',
									'Teal'       => 'teal',
									'Teal 2'     => 'teal-2',
									'White'      => 'white',
									),
									'save_always' => true,
			                  'description' => __('Select a color scheme for the active Tabs in this set. <a href="http://s3.truethemes.net/plugin-assets/shortcode-style-guide/style-guide.html" target="_blank">View available colors &rarr;</a>', 'tt_orbit')
			              ),
			              	array(
			               	  'group'       => __('CSS Class', 'tt_orbit'),
			                  'type'        => 'textfield',
			                  'holder'      => 'div',
			                  'heading'     => __('CSS class name', 'tt_orbit'),
			                  'param_name'  => 'custom_css_class',
			                  'description' => __('Give this element an extra CSS class name if you wish to refer to it in a CSS file. (optional)', 'tt_orbit')
			              ),
					    ),
     	   		) 
);// END vc_map

// Map the tab content
   vc_map( array(
   		'category'                => __('Orbit', 'tt_orbit'),
        'name'                    => __("Tab Section", 'tt_orbit'),
        'description'             => __("Add a tab section", 'tt_orbit'),
        'base'                    => "orbit_tab_1_content",
        'content_element'         => true,
        'show_settings_on_create' => true,
        'controls'                => 'full',
        'icon'        			  => plugins_url('images/backend-editor/orbit-menu-tabs-1.png', __FILE__),
        "as_child"                => array('only' => 'orbit_tab_1'), 
        "params"                  => array(
        					array(
					            'type'       => 'dropdown',
					            'heading'    => __("Active Tab?", 'tt_orbit'),
					            'param_name' => "tab_active",
					            'value'      => array(
											'No'  => 'no',
											'Yes' => 'yes',
											),
					            'description' => __("Should this tab be opened by default? (only one active tab is allowed for each set)", 'tt_orbit'),'save_always' => true,
					        ),
					        array(
					            'type'        => 'textfield',
					            'heading'     => __("Tab Title", 'tt_orbit'),
					            'param_name'  => "nav_tab_title",
					            'value'       => 'New Tab'
					        ),
				            array(
				    		 'type'          => "textarea_html",
				    		 'holder'        => 'div',
				    		 'heading'       => __('Tab Content', 'tt_orbit'),
				    		 'param_name'    => 'content',
				    		 'value'         => __("<h2>Heading</h2><p>Edit this text with custom content.</p>", 'tt_orbit')
			              ),
		        		//START icons
		        		array(
							'group'   => __('Icon', 'tt_orbit'),		        		
							'type'    => 'dropdown',
							'heading' => __( 'Icon library', 'tt_orbit' ),
							'value'   => array(
									__( 'Font Awesome', 'tt_orbit' )        => 'fontawesome',
									__( 'Open Iconic', 'tt_orbit' )         => 'openiconic',
									__( 'Typicons', 'tt_orbit' )            => 'typicons',
									__( 'Entypo', 'tt_orbit' )              => 'entypo',
									__( 'Linecons', 'tt_orbit' )            => 'linecons',
									__( 'Do not display icon', 'tt_orbit' ) => '',									
								),
							'admin_label' => true,
							'param_name'  => 'type',
							'description' => __( 'Select icon library.', 'tt_orbit' ),
							'std'         => array(__( 'Font Awesome', 'tt_orbit' ) => 'fontawesome'),
						),
						//fontawesome
						array(
							'group'        => __('Icon', 'tt_orbit'),					
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_fontawesome',
							'value'        => 'fa fa-adjust',
							'settings'     => array(
							'emptyIcon'    => false,
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'fontawesome',
							),
							'description'  => __( 'Select icon from library.', 'tt_orbit' ),
						),
						//openiconic
						array(
							'group'        => __('Icon', 'tt_orbit'),					
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_openiconic',
							'value'        => 'vc-oi vc-oi-dial',
							'settings'     => array(
							'emptyIcon'    => false,
							'type'         => 'openiconic',
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'openiconic',
							),
							'description'  => __( 'Select icon from library.', 'tt_orbit' ),
						),
						//typicons
						array(
							'group'        => __('Icon', 'tt_orbit'),					
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_typicons',
							'value'        => 'typcn typcn-adjust-brightness',
							'settings'     => array(
							'emptyIcon'    => false,
							'type'         => 'typicons',
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'typicons',
							),
							'description'  => __( 'Select icon from library.', 'tt_orbit' ),
						),
						//entypo
						array(
							'group'        => __('Icon', 'tt_orbit'),					
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_entypo',
							'value'        => 'entypo-icon entypo-icon-note',
							'settings'     => array(
							'emptyIcon'    => false,
							'type'         => 'entypo',
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'entypo',
							),
						),
						//linecons
						array(
							'group'        => __('Icon', 'tt_orbit'),
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_linecons',
							'value'        => 'vc_li vc_li-heart',
							'settings'     => array(
							'emptyIcon'    => false,
							'type'         => 'linecons',
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'linecons',
							),
							'description'  => __( 'Select icon from library.', 'tt_orbit' ),
						),
						//END icons

							array(
							'group'        => __('Icon', 'tt_orbit'),
								'type'        => 'textarea_raw_html',
								'heading'     => __('Custom Icon', 'tt_orbit'),
								'description' => __('Display your own custom icon by entering it\'s HTML code here. Give this HTML element an additional CSS class name of "orbit-custom-icon" for proper positioning.', 'tt_orbit'),
								'param_name'  => 'custom_icon',
								'value'=>'',
							),
			               array(
			               'group'        => __('Icon', 'tt_orbit'),
			                  'type'          => "attach_image",
			                  'holder'        => 'div',
			                  'heading'       => __("Custom Icon Upload", 'tt_orbit'),
			                  'param_name'    => "custom_icon_upload",
			                  'description'   => __('Upload a custom icon, this overwrites the Custom Icon and Icon settings.', 'tt_orbit')
			               ),								
								        								        
					    ),
     	   		) 
);// END vc_map

/*--------------------------------------------------------------
Orbit - Tab 2
--------------------------------------------------------------*/
   vc_map( array(
   		'category'                => __('Orbit', 'tt_orbit'),
        'name'                    => __("Tabs 2", 'tt_orbit'),
        'description'             => __("Tabbed content", 'tt_orbit'),
        'base'                    => "orbit_tab_2",
        'controls'                => 'full',
        'content_element'         => true,
        'show_settings_on_create' => true,
		'js_view'                 => 'VcColumnView',
        'icon'        => plugins_url('images/backend-editor/orbit-menu-tabs-2.png', __FILE__),
        "as_parent"               => array('only' => 'orbit_tab_2_content'),
        "params"      => array(
							array(
								'type'          => 'colorpicker',
								'heading'    => __('Active Tab Color', 'tt_orbit'),
								'param_name'    => "color_scheme",
								'value'         => "#3b86c4",
								'description'   => __('The font color of the active Tab in this set.', 'tt_orbit')
					        ),
			              	array(
			                  'type'        => 'textfield',
			                  'holder'      => 'div',
			                  'heading'     => __('CSS class name', 'tt_orbit'),
			                  'param_name'  => 'custom_css_class',
			                  'description' => __('Give this element an extra CSS class name if you wish to refer to it in a CSS file. (optional)', 'tt_orbit')
			              ),
					    ),
     	   		) 
);// END vc_map

// Map the tab content
   vc_map( array(
        'category'        => __('Orbit', 'tt_orbit'),
        'name'            => __("Tab Section", 'tt_orbit'),
        'description'     => __("Add a tab section", 'tt_orbit'),
        'base'            => "orbit_tab_2_content",
        'controls'        => 'full',
        'content_element' => true,
        'icon'        => plugins_url('images/backend-editor/orbit-menu-tabs-2.png', __FILE__),
        "as_child"        => array('only' => 'orbit_tab_2'),
        'show_settings_on_create' => true,
        "params"      => array(
        					array(
					            'type'       => 'dropdown',
					            'heading'    => __("Active Tab?", 'tt_orbit'),
					            'param_name' => "tab_active",
					            'value'      => array(
											'No'  => 'no',
											'Yes' => 'yes',
											),
								'save_always' => true,
					            'description' => __("Should this tab be opened by default? (only one active tab is allowed for each set)", 'tt_orbit')
					        ),
					        array(
					            'type'        => 'textfield',
					            'heading'     => __("Tab Title", 'tt_orbit'),
					            'param_name'  => "nav_tab_title",
					            'value'       => 'New Tab'
					        ),
				            array(
				    		 'type'          => "textarea_html",
				    		 'holder'        => 'div',
				    		 'heading'       => __('Tab Content', 'tt_orbit'),
				    		 'param_name'    => 'content',
				    		 'value'         => __("<h2>Heading</h2><p>Edit this text with custom content.</p>", 'tt_orbit')
			              ),								        								        
					    ),
     	   		) 
);// END vc_map

/*--------------------------------------------------------------
Orbit - Tab 3
--------------------------------------------------------------*/
   vc_map( array(
        'name'                    => __("Tabs 3", 'tt_orbit'),
        'description'             => __("Tabbed content (vertical)", 'tt_orbit'),
        'base'                    => "orbit_tab_3",
        'controls'                => 'full',
        'content_element'         => true,
        'show_settings_on_create' => true,
		'js_view'                 => 'VcColumnView',
        'icon'        => plugins_url('images/backend-editor/orbit-menu-tabs-3.png', __FILE__),
        'category'                => __('Orbit', 'tt_orbit'),
        "as_parent"               => array('only' => 'orbit_tab_3_content'),
        "params"      => array(
        					array(
								'group'      => __('Design', 'tt_orbit'),
								'type'       => 'orbit_note',
								'param_name' => 'orbit_note',
								'value'      => 'You can customize the design of this tabset using the options below. Upon completion simply click "Save changes" to begin adding tabs.'
								),
        					array(
								'group'      => __('Design', 'tt_orbit'),
								'type'       => 'checkbox',
								'heading'    => __( 'Disable Icons', 'tt_orbit' ),
								'param_name' => 'disable_icon',
								'value'      => array( __( 'Check this box to disable icons.', 'tt_orbit' ) => 'yes' )
								),
        					array(
					        	'group'       	=> __('Design', 'tt_orbit'),
								'type'          => 'colorpicker',
								'heading'    	=> __('Menu Background Color', 'tt_orbit'),
								'param_name'    => "menu_bg_color",
								'value'         => "#f6f6f6",
								'description'   => __('The background color of the menu.', 'tt_orbit')
					        ),
        					array(
        						'group'       	=> __('Design', 'tt_orbit'),
								'type'          => 'colorpicker',
								'heading'    	=> __('Link Color - Normal', 'tt_orbit'),
								'param_name'    => "link_color",
								'value'         => "#666",
								'description'   => __('The link color when a tab is not active.', 'tt_orbit')
					        ),
					        array(
					    		'group'       	=> __('Design', 'tt_orbit'),
								'type'          => 'colorpicker',
								'heading'    	=> __('Link Color - Hover', 'tt_orbit'),
								'param_name'    => "link_color_hover",
								'value'         => "#333",
								'description'   => __('The link color on hover.', 'tt_orbit')
					        ),
					        array(
					    		'group'       	=> __('Design', 'tt_orbit'),
								'type'       	=> 'colorpicker',
								'heading'    	=> __('Link Color - Active', 'tt_orbit'),
								'param_name'    => "link_color_active",
								'value'         => "#099",
								'description'   => __('The link color when a tab is active.', 'tt_orbit')
					        ),
					    	array(
					    		'group'       	=> __('Design', 'tt_orbit'),
								'type'          => 'colorpicker',
								'heading'    	=> __('Tab Background Color - Hover', 'tt_orbit'),
								'param_name'    => "tab_bgcolor_hover",
								'value'         => "#eee",
								'description'   => __('The background color of a tab on hover.', 'tt_orbit')
					        ),
					        array(
					    		'group'       	=> __('Design', 'tt_orbit'),
								'type'          => 'colorpicker',
								'heading'    	=> __('Tab Background Color - Active', 'tt_orbit'),
								'param_name'    => "tab_bgcolor_active",
								'value'         => "#fff",
								'description'   => __('The background color of a tab when active.', 'tt_orbit')
					        ),
					        array(
					    		'group'       	=> __('Design', 'tt_orbit'),
								'type'          => 'colorpicker',
								'heading'    	=> __('Tab - Bottom border', 'tt_orbit'),
								'param_name'    => "tab_border_color",
								'value'         => "#e1e1e1",
								'description'   => __('The 1px line that separates each tab.', 'tt_orbit')
					        ),
					        array(
			               	  'group'       => __('CSS Class', 'tt_orbit'),
			                  'type'        => 'textfield',
			                  'holder'      => 'div',
			                  'heading'     => __('CSS class name', 'tt_orbit'),
			                  'param_name'  => 'custom_css_class',
			                  'description' => __('Give this element an extra CSS class name if you wish to refer to it in a CSS file. (optional)', 'tt_orbit')
			              ),				        					        								
					    ),
     	   		) 
);// END vc_map

// Map the tab content
   vc_map( array(
   		'category'                => __('Orbit', 'tt_orbit'),
        'name'                    => __("Tab Section", 'tt_orbit'),
        'description'             => __("Add a tab section", 'tt_orbit'),
        'base'                    => "orbit_tab_3_content",
        'content_element'         => true,
        'show_settings_on_create' => true,
        'controls'                => 'full',
        'icon'                    => plugins_url('images/backend-editor/orbit-menu-tabs-3.png', __FILE__),
        "as_child"                => array('only' => 'orbit_tab_3'), 
        "params"                  => array(
        					array(
					            'type'       => 'dropdown',
					            'heading'    => __("Active Tab?", 'tt_orbit'),
					            'param_name' => "tab_active",
					            'value'      => array(
											'No'  => 'no',
											'Yes' => 'yes',
											),
					            'description' => __("Should this tab be opened by default? (only one active tab is allowed for each set)", 'tt_orbit'),'save_always' => true,
					        ),
					        array(
					            'type'        => 'textfield',
					            'heading'     => __("Tab Title", 'tt_orbit'),
					            'param_name'  => "nav_tab_title",
					            'value'       => 'New Tab'
					        ),
				            array(
				    		 'type'          => "textarea_html",
				    		 'holder'        => 'div',
				    		 'heading'       => __('Tab Content', 'tt_orbit'),
				    		 'param_name'    => 'content',
				    		 'value'         => __("<h2>Heading</h2><p>Edit this text with custom content.</p>", 'tt_orbit')
			              ),	
		        		//START icons
		        		array(
							'group'   => __('Icon', 'tt_orbit'),		        		
							'type'    => 'dropdown',
							'heading' => __( 'Icon library', 'tt_orbit' ),
							'value'   => array(
									__( 'Font Awesome', 'tt_orbit' )        => 'fontawesome',
									__( 'Open Iconic', 'tt_orbit' )         => 'openiconic',
									__( 'Typicons', 'tt_orbit' )            => 'typicons',
									__( 'Entypo', 'tt_orbit' )              => 'entypo',
									__( 'Linecons', 'tt_orbit' )            => 'linecons',
									__( 'Do not display icon', 'tt_orbit' ) => '',									
								),
							'admin_label' => true,
							'param_name'  => 'type',
							'description' => __( 'Select icon library.', 'tt_orbit' ),
							'std'         => array(__( 'Font Awesome', 'tt_orbit' ) => 'fontawesome'),
						),
						//fontawesome
						array(
							'group'        => __('Icon', 'tt_orbit'),					
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_fontawesome',
							'value'        => 'fa fa-adjust',
							'settings'     => array(
							'emptyIcon'    => false,
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'fontawesome',
							),
							'description'  => __( 'Select icon from library.', 'tt_orbit' ),
						),
						//openiconic
						array(
							'group'        => __('Icon', 'tt_orbit'),					
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_openiconic',
							'value'        => 'vc-oi vc-oi-dial',
							'settings'     => array(
							'emptyIcon'    => false,
							'type'         => 'openiconic',
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'openiconic',
							),
							'description'  => __( 'Select icon from library.', 'tt_orbit' ),
						),
						//typicons
						array(
							'group'        => __('Icon', 'tt_orbit'),					
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_typicons',
							'value'        => 'typcn typcn-adjust-brightness',
							'settings'     => array(
							'emptyIcon'    => false,
							'type'         => 'typicons',
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'typicons',
							),
							'description'  => __( 'Select icon from library.', 'tt_orbit' ),
						),
						//entypo
						array(
							'group'        => __('Icon', 'tt_orbit'),					
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_entypo',
							'value'        => 'entypo-icon entypo-icon-note',
							'settings'     => array(
							'emptyIcon'    => false,
							'type'         => 'entypo',
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'entypo',
							),
						),
						//linecons
						array(
							'group'        => __('Icon', 'tt_orbit'),
							'type'         => 'iconpicker',
							'heading'      => __( 'Icon', 'tt_orbit' ),
							'param_name'   => 'icon_linecons',
							'value'        => 'vc_li vc_li-heart',
							'settings'     => array(
							'emptyIcon'    => false,
							'type'         => 'linecons',
							'iconsPerPage' => 4000,
							),
							'dependency'   => array(
							'element'      => 'type',
							'value'        => 'linecons',
							),
							'description'  => __( 'Select icon from library.', 'tt_orbit' ),
						),
						//END icons

							array(
							'group'        => __('Icon', 'tt_orbit'),
								'type'        => 'textarea_raw_html',
								'heading'     => __('Custom Icon', 'tt_orbit'),
								'description' => __('Display your own custom icon by entering it\'s HTML code here. Give this HTML element an additional CSS class name of "orbit-custom-icon" for proper positioning.', 'tt_orbit'),
								'param_name'  => 'custom_icon',
								'value'=>'',
							),
			               array(
			               'group'        => __('Icon', 'tt_orbit'),
			                  'type'          => "attach_image",
			                  'holder'        => 'div',
			                  'heading'       => __("Custom Icon Upload", 'tt_orbit'),
			                  'param_name'    => "custom_icon_upload",
			                  'description'   => __('Upload a custom icon, this overwrites the Custom Icon and Icon settings.', 'tt_orbit')
			              ),								
							        								        
					    ),
     	   		) 
);// END vc_map

/*--------------------------------------------------------------
Orbit - Testimonial 1
--------------------------------------------------------------*/
   vc_map( array(
   		'category'        => __('Orbit', 'tt_orbit'),
   		'name'            => __("Testimonial 1", 'tt_orbit'),
   		'description'     => __("Stylish testimonial slider", 'tt_orbit'),
   		'base'            => "orbit_testimonial_1",
   		'controls'        => 'full',
   		'content_element' => true,
   		'show_settings_on_create' => true,
   		'js_view'         => 'VcColumnView',
        'icon'        => plugins_url('images/backend-editor/orbit-menu-testimonial-1.png', __FILE__),
        "as_parent"   => array('only' => 'orbit_testimonial_1_slide'), 
        "params"      => array(
			              	array(
			                  'type'        => 'textfield',
			                  'holder'      => 'div',
			                  'heading'     => __('CSS class name', 'tt_orbit'),
			                  'param_name'  => 'custom_css_class',
			                  'description' => __('Give this element an extra CSS class name if you wish to refer to it in a CSS file. (optional)', 'tt_orbit')
			              ),
					    ),
     	   		) 
);// END vc_map

// Heres the testimonial content slide
   vc_map( array(
        'name'            => __("Testimonial Slide", 'tt_orbit'),
        'description'     => __("Add a testimonial", 'tt_orbit'),
        'base'            => "orbit_testimonial_1_slide",
        'controls'        => 'full',
        'content_element' => true,
        'icon'            => plugins_url('images/backend-editor/orbit-menu-testimonial-1.png', __FILE__),
        'category'        => __('Orbit', 'tt_orbit'),
        "as_child"        => array('only' => 'orbit_testimonial_1'),
        "params"      => array(
			               array(
			                  'type'          => "attach_image",
			                  'holder'        => 'div',
			                  'heading'       => __("Banner Image", 'tt_orbit'),
			                  'param_name'    => "banner_image_attachment_id",
			                  'description'   => __('Upload a banner image.', 'tt_orbit')
			              ),
			               array(
			                  'type'          => "attach_image",
			                  'holder'        => 'div',
			                  'heading'       => __("Customer Photo", 'tt_orbit'),
			                  'param_name'    => "client_headshot_image_attachment_id",
			                  'description'   => __('Upload a photo of the customer.', 'tt_orbit')
			              ),
					       array(
					           'type'          => "textfield",
					           'heading'       => __("Client Name", 'tt_orbit'),
					           'param_name'    => "client_name"
					       ),
					       array(
					           'type'          => "textarea",
					           'heading'       => __("Testimonial Text", 'tt_orbit'),
					           'param_name'    => "testimonial_text"
					       ),							       
					       						              						              
					    ),
     	   		) 
	);// END vc_map

/*--------------------------------------------------------------
Orbit - Testimonial 2
--------------------------------------------------------------*/
   vc_map( array(
        'name'            => __("Testimonial 2", 'tt_orbit'),
        'description'     => __("Stylish testimonial slider", 'tt_orbit'),
        'base'            => "orbit_testimonial_2",
        'controls'        => 'full',
        'content_element' => true,
        'show_settings_on_create' => true,
        'icon'            => plugins_url('images/backend-editor/orbit-menu-testimonial-2.png', __FILE__),
        'category'        => __('Orbit', 'tt_orbit'),
        "as_parent"       => array('only' => 'orbit_testimonial_2_slide'),
        'js_view'         => 'VcColumnView',
        "params"          => array(
			              	array(
			                  'type'        => 'textfield',
			                  'holder'      => 'div',
			                  'heading'     => __('CSS class name', 'tt_orbit'),
			                  'param_name'  => 'custom_css_class',
			                  'description' => __('Give this element an extra CSS class name if you wish to refer to it in a CSS file. (optional)', 'tt_orbit')
			              ),
					    ),
     	   		) 
);// END vc_map

// Heres the testimonial content slide
   vc_map( array(
        'name'            => __("Testimonial Slide", 'tt_orbit'),
        'description'     => __("Add a testimonial", 'tt_orbit'),
        'base'            => "orbit_testimonial_2_slide",
        'controls'        => 'full',
        'content_element' => true,
        'icon'            => plugins_url('images/backend-editor/orbit-menu-testimonial-2.png', __FILE__),
        'category'        => __('Orbit', 'tt_orbit'),
        "as_child"        => array('only' => 'orbit_testimonial_2'),
        "params"      => array(
			               array(
			                  'type'          => 'colorpicker',
			                  'holder'        => 'div',
			                  'heading'       => __("Background Color", 'tt_orbit'),
			                  'param_name'    => "testimonial_bg_color",
			                  'value'         => "#372f2b",
			                  'description' => __('The background color of this testimonial slide.', 'tt_orbit')
			              ),
			               array(
			                  'type'          => "attach_image",
			                  'holder'        => 'div',
			                  'heading'       => __("Customer Photo", 'tt_orbit'),
			                  'param_name'    => "client_headshot_image_attachment_id",
			                  'description'   => __('Upload a photo of the customer.', 'tt_orbit')
			              ),
					       array(
					           'type'          => "textarea",
					           'heading'       => __("Testimonial Text", 'tt_orbit'),
					           'param_name'    => "testimonial_text"
					       ),
					    ),
     	   		)
);// END vc_map

/*
 * Disable elements below
 * until further development
 *
 
// Heres the Google Map
   vc_map( array(
        'name'            => __("Google Map", 'tt_orbit'),
        'description'     => __("Add a Google Map", 'tt_orbit'),
        'base'            => "orbit_google_map",
        'controls'        => 'full',
        'content_element' => true,
        'icon'            => plugins_url('images/backend-editor/orbit-menu-testimonial-2.png', __FILE__),
        'category'        => __('Orbit', 'tt_orbit'),
        'js_view'     => 'OrbitGoogleMap',
        "params"      => array(
					       array(
					           'type'          => "textfield",
					           'heading'       => __("API Key", 'tt_orbit'),
					           'param_name'    => "google_map_api_key",
                               'description'   => 'An API key allows you to use the google maps service. <a href="https://developers.google.com/maps/documentation/javascript/tutorial#api_key" target="_blank">Get an API Key</a>'
					       ),
					       array(
					           'type'          => "textfield",
					           'heading'       => __("Width", 'tt_orbit'),
					           'param_name'    => "google_map_width",
                               'description'   => 'The width of the map in pixels.',
                               'value'         => '600'
					       ),
					       array(
					           'type'          => "textfield",
					           'heading'       => __("Height", 'tt_orbit'),
					           'param_name'    => "google_map_height",
                               'description'   => 'The height of the map in pixels.',
                               'value'         => '500'
					       ),
					       array(
					           'type'          => "textfield",
					           'heading'       => __("Center (Lat)", 'tt_orbit'),
					           'param_name'    => "google_map_center_lat",
                               'description'   => '<a href="http://www.latlong.net/convert-address-to-lat-long.html" target="_blank">Address to Lat-Long Converter</a>'
					       ),
					       array(
					           'type'          => "textfield",
					           'heading'       => __("Center (Long)", 'tt_orbit'),
					           'param_name'    => "google_map_center_long",
                               'description'   => '<a href="http://www.latlong.net/convert-address-to-lat-long.html" target="_blank">Address to Lat-Long Converter</a>'
					       ),
					       array(
					           'type'          => "textfield",
					           'heading'       => __("Zoom", 'tt_orbit'),
					           'param_name'    => "google_map_zoom"
					       ),
					       array(
					           'type'          => "checkbox",
					           'heading'       => __("Add Marker at center point", 'tt_orbit'),
					           'param_name'    => "google_map_center_marker",
                               'value'         => array(__("Yes", 'tt_orbit') => 'yes')
					       ),
					       array(
					           'type'          => "textfield",
					           'heading'       => __("Marker custom icon", 'tt_orbit'),
					           'param_name'    => "google_map_center_marker_custom_icon",
                               'dependency'    => array(
                                    'element' => 'google_map_center_marker',
                                    'value'   => array('yes')
                               )
					       ),
					       array(
					           'type'          => "textarea_html",
					           'heading'       => __("Marker info window content", 'tt_orbit'),
					           'param_name'    => "google_map_center_marker_info_window_content",
                               'dependency'    => array(
                                    'element' => 'google_map_center_marker',
                                    'value'   => array('yes')
                               )
					       ),
					    ),
     	   		)
);// END vc_map

// Here is the GrowBox container
   vc_map( array(
        'name'            => __("GrowBox", 'tt_orbit'),
        'description'     => __("Set of GrowBoxes", 'tt_orbit'),
        'base'            => "orbit_grow_boxes",
        'controls'        => 'full',
        'content_element' => true,
        'show_settings_on_create' => true,
        'icon'            => plugins_url('images/backend-editor/orbit-menu-testimonial-2.png', __FILE__),
        'category'        => __('Orbit', 'tt_orbit'),
        "as_parent"       => array('only' => 'orbit_single_grow_box'),
        'js_view'         => 'VcColumnView',
        "params"          => array(
			              	array(
			                  'type'        => 'textfield',
			                  'holder'      => 'div',
			                  'heading'     => __('CSS class name', 'tt_orbit'),
			                  'param_name'  => 'custom_css_class',
			                  'description' => __('Give this element an extra CSS class name if you wish to refer to it in a CSS file. (optional)', 'tt_orbit')
			              ),
					    ),
     	   		) 
);// END vc_map

// Here is single grow box
   vc_map( array(
        'name'            => __("Single Grow Box", 'tt_orbit'),
        'description'     => __("Add a grow box", 'tt_orbit'),
        'base'            => "orbit_single_grow_box",
        'controls'        => 'full',
        'content_element' => true,
        'icon'            => plugins_url('images/backend-editor/orbit-menu-testimonial-2.png', __FILE__),
        'category'        => __('Orbit', 'tt_orbit'),
        "as_child"        => array('only' => 'orbit_grow_boxes'),
        "params"      => array(
			               array(
			                  'type'          => 'textarea_raw_html',
			                  'heading'       => __("Closed box content", 'tt_orbit'),
			                  'param_name'    => "orbit_grow_box_closed_content",
			                  'description' => __('The content that shows when the box is closed. It also works as the header when the box is open', 'tt_orbit'),
			                  'value' => '',
			              ),
			               array(
			                  'type'          => 'textarea_html',
			                  'heading'       => __("Open box content", 'tt_orbit'),
			                  'param_name'    => "orbit_grow_box_open_content",
			                  'description' => __('The content that shows when the box is open', 'tt_orbit'),
			              ),
			              	array(
			                  'type'        => 'textfield',
			                  'heading'     => __('CSS class name', 'tt_orbit'),
			                  'param_name'  => 'orbit_grow_box_custom_css_class',
			                  'description' => __('Give this element an extra CSS class name if you wish to refer to it in a CSS file. (optional)', 'tt_orbit')
			              ),
			              	array(
			                  'type'        => 'textfield',
			                  'heading'     => __('Grow Box width', 'tt_orbit'),
			                  'param_name'  => 'orbit_grow_box_width',
                              'value'       => '200',
			                  'description' => __('The width of the closed box.', 'tt_orbit')
			              ),
			              	array(
			                  'type'        => 'textfield',
			                  'heading'     => __('Grow Box height', 'tt_orbit'),
			                  'param_name'  => 'orbit_grow_box_height',
                              'value'       => '200',
			                  'description' => __('The height of the closed box.', 'tt_orbit')
			              ),
			              	array(
			                  'type'        => 'textfield',
			                  'heading'     => __('Open Grow Box width', 'tt_orbit'),
			                  'param_name'  => 'orbit_grow_box_open_width',
                              'value'       => '400',
			                  'description' => __('The width of the open box.', 'tt_orbit')
			              ),
			              	array(
			                  'type'        => 'textfield',
			                  'heading'     => __('Open Grow Box height', 'tt_orbit'),
			                  'param_name'  => 'orbit_grow_box_open_height',
                              'value'       => '400',
			                  'description' => __('The height of the open box.', 'tt_orbit')
			              ),
					    ),
     	   		)
);// END vc_map

END orbit disable */

}//END integrateWithVC()



/**
 * Build the Orbit Shortcodes
 *
 * Lets build out the custom shortcodes
 * This is the back-end stuff the controls how the shortcode
 * will be displayed on the front-end.
 *
 * Note: some of the shortcodes are pulled from our Vision Plugin
 * which you'll find reflected in CSS .class and #ID names
 *
 * @since Orbit 1.0
 */

/*--------------------------------------------------------------
Orbit - Accordion
--------------------------------------------------------------*/
public function render_orbit_accordion( $atts, $content = null ) {      
	extract(shortcode_atts(array(
	'gradient_top'       => '#fff',
	'gradient_bottom'    => '#efefef',
	'panel_border'       => '#e1e1e1',
	'panel_padding'      => '20px',
	'title_color'        => '#666',
	'title_color_active' => '#88BBC8',
	'custom_css_class'   => '',
	'unique_id'          => ''
	), $atts));

	// Visual Composer helper function
	if(function_exists('wpb_js_remove_wpautop')){ $content = wpb_js_remove_wpautop($content, false);
	} else { $content = do_shortcode(shortcode_unautop($content)); }

	// Ensure HTML tags get closed
	$content = force_balance_tags($content);

	// Generate Unique ID to be used for styling
	$unique_id = orbit_truethemes_random();

	// Dynamic CSS Function
	$style_code ='.vision-accordion.'.$unique_id.' dt {
	border: 1px solid '.$panel_border.';
	background-image: -webkit-linear-gradient(top, '.$gradient_top.' 0%, '.$gradient_bottom.' 100%);
	background-image: -moz-linear-gradient(top, '.$gradient_top.' 0%, '.$gradient_bottom.' 100%);
	background-image: -o-linear-gradient(top, '.$gradient_top.' 0%, '.$gradient_bottom.' 100%);
	background-image: linear-gradient(top, '.$gradient_top.' 0%, '.$gradient_bottom.' 100%);
	padding: '.$panel_padding.' 0;
	}
	.vision-accordion.'.$unique_id.' dt,
	.vision-accordion.'.$unique_id.' dt:before {
	    color: '.$title_color.';
	}
	.vision-accordion.'.$unique_id.' dt.current,
	.vision-accordion.'.$unique_id.' dt.current:before {
	    color: '.$title_color_active.';
	}';

	//must be called before return $output
	$this->orbit_dynamic_embed_css($style_code);

   return '<dl class="vision-accordion '.$custom_css_class.' '.$unique_id.'">'.$content.'</dl>';
}// END shortcode

/*--------------------------------------------------------------
Orbit - Accordion [slide]
--------------------------------------------------------------*/
public function render_orbit_accordion_panel($atts, $content = null) {
	extract(shortcode_atts(array(
	'title'        => '',
	'panel_active' => ''
	), $atts));

	// Visual Composer helper function
	if(function_exists('wpb_js_remove_wpautop')){ $content = wpb_js_remove_wpautop($content, false);
	} else { $content = do_shortcode(shortcode_unautop($content)); }

	// Ensure HTML tags get closed
	$content = force_balance_tags($content);

	if ($panel_active == 'true'){
	$output = '<dt class="current">'.$title.'</dt><dd class="current">'.$content.'</dd>';
	} else {
	$output = '<dt>'.$title.'</dt><dd>'.$content.'</dd>';
	}

	return $output;
}// END shortcode

/*--------------------------------------------------------------
Orbit - Alert
--------------------------------------------------------------*/ 
public function render_orbit_alert($atts, $content = null){
	extract(shortcode_atts(array(
	'style'     => '',
	'font_size' => '13px',
	'closeable' => '',
	'custom_css_class'   => ''
	), $atts));
	  
	// Visual Composer helper function
	if(function_exists('wpb_js_remove_wpautop')){ $content = wpb_js_remove_wpautop($content, false);
    } else { $content = do_shortcode(shortcode_unautop($content)); }
	
	// Ensure HTML tags get closed
	$content = force_balance_tags($content);
	  
   if ($closeable == 'true'){
   	$output = '<div class="vision-notification '.$custom_css_class.' '.$style.' closeable"><div class="closeable-x"><p style="font-size:'.$font_size.';">' .$content. '</p></div></div>';
   } else{
     $output = '<div class="vision-notification '.$custom_css_class.' '.$style.'"><p style="font-size:'.$font_size.';">' .$content. '</p></div>';
   }

return $output;
}// END shortcode
    
/*--------------------------------------------------------------
Orbit - Button
--------------------------------------------------------------*/
public function render_orbit_button( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'size'                 => '',
		'color'                => '',
		'url'                  => '',
		'lightbox_content'     => '',
		'lightbox_description' => '',
		'custom_css_class'   => ''
		), $atts));
							  
	//Visual Composer helper function to fix unclosed/unwanted paragraph tags in $content	
	//found in js_composer/include/helpers/helpers.php
	//it does do_shortcode and shortcode_unautop to content, therefore no need to do_shortcode in content below!
	if(function_exists('wpb_js_remove_wpautop')){					  
    $content = wpb_js_remove_wpautop($content, false);
    }else{
    $content = do_shortcode(shortcode_unautop($content));
    }

    // Required by param => 'vc_link' to parse the link
	$url = vc_build_link( $url );
	// grab the attributes
	$a_href   = $url['url'];
	$a_title  = $url['title'];
	$a_target = $url['target'];

	if(!empty($lightbox_content)) {
    	$output = '<a href="'.$lightbox_content.'" class="'.$size.' '.$color.' vision-button '.$custom_css_class.'" data-gal="prettyPhoto" title="'.$lightbox_description.'">' .$content. '</a>';  	
    } else {
  		$output = '<a href="'.$a_href.'" class="'.$size.' '.$color.' vision-button '.$custom_css_class.'" target="'.$a_target.'" title="'.$a_title.'">' .$content. '</a>';
    };
    
  return $output;
} // END shortcode

/*--------------------------------------------------------------
Orbit - Content Box
--------------------------------------------------------------*/
public function render_orbit_content_box( $atts, $content = null ) {      
	extract(shortcode_atts(array(
		'style'            => '',
		'title'            => 'Content Box',
		'icon'             => '',
		'custom_icon'      => '',
		'custom_css_class' => '',
		'custom_icon_upload' => '',
		'type' => '',
		'icon_fontawesome' => '',
		'icon_openiconic' => '',
		'icon_typicons' => '',
		'icon_entypoicons' => '',
		'icon_linecons' => '',
		'icon_entypo' => '',			
		), $atts));

	// Enqueue needed icon font.
	vc_icon_element_fonts_enqueue( $type );
	
	// Visual Composer helper function
	if(function_exists('wpb_js_remove_wpautop')){ $content = wpb_js_remove_wpautop($content, false);
    } else { $content = do_shortcode(shortcode_unautop($content)); }

		
	// Ensure HTML tags get closed
	$content = force_balance_tags($content);


	//added by denzel @since orbit-1.6-dev-5 this is default icon
	if(empty($icon) && empty(${"icon_" . $type})){
	${"icon_" . $type} = 'fa fa-adjust';
	}
		
	//Build output for icon
	if(!empty($icon)){
		$icon_output = '<i class="fa '.$icon.'"></i>';
	}

	if(!empty(${"icon_" . $type})){
		$icon_output = '<i class="fa '.${"icon_" . $type}.'"></i>';
	}
	
    /**
	 * Custom Icon
	 *
	 * $custom_icon is HTML code inputted from the user
	 * Visual composer uses base64_encode for a users HTML-input
	 * We're using base64_decode() to "un-encode" a users HTML input
	 * nothing malicious going on here :)
	 *
	 * @since Orbit 1.2
	 */
    if(!empty($custom_icon)){
    //custom icon will overwrite icon if there is any html entered by customer.
    	$icon_output = rawurldecode( base64_decode( strip_tags( $custom_icon) ) );
    }
    
    
    if(!empty($custom_icon_upload)){
    //custom icon upload will overwrite the above custom icon and icon.
        $uploaded_custom_icon_image = wp_get_attachment_image($custom_icon_upload,'full');
        $icon_output = '<span class="orbit-custom-icon-img">'.$uploaded_custom_icon_image.'</span>';
    }		

	    $output = '<div class="vision-contentbox '.$custom_css_class.'"><div class="vision-contentbox-title tt-cb-title-'.$style.'">'.$icon_output.' <span>'.$title.'</span></div><div class="vision-contentbox-content tt-content-style-'.$style.'">'.$content.'</div></div>'; 	 

	 return $output;
}// END shortcode

/*--------------------------------------------------------------
Orbit - Circle Loader
--------------------------------------------------------------*/
public function render_orbit_circle_loader($atts, $content = null) {
	extract(shortcode_atts(array(  
	'number'           => '50',
	'number_color'     => '',
	'symbol'           => '%',
	'width'            => '10',
	'style'            => 'square',
	'track_color'      => '#eeeeee',
	'bar_color'        => '#a0dbe1',
	'custom_css_class' => '',
	'unique_id'        => ''

	), $atts));
		  
	// Visual Composer helper function
	if(function_exists('wpb_js_remove_wpautop')){ $content = wpb_js_remove_wpautop($content, false);
    } else { $content = do_shortcode(shortcode_unautop($content)); }
	
	// Ensure HTML tags get closed
	$content = force_balance_tags($content);

	// Generate Unique ID to be used for styling
	$unique_id = orbit_truethemes_random();

	$output = '<div class="vision-circle-loader tt-orbit-montserrat '.$unique_id.' '.$custom_css_class.'">
      <div class="easyPieChart vision-circle-number" data-percent="'.$number.'" data-trackcolor="'.$track_color.'" data-barcolor="'.$bar_color.'" data-linewidth="'.$width.'" data-linecap="'.$style.'">
          <span class="vision-circle-number-wrap"><span class="vision-circle-number">'.$number.'</span>'.$symbol.'</span>
          <canvas></canvas>
      </div>
      <div class="loader-details">'.$content.'</div></div>';

    // Dynamic CSS Function
	$style_code ='.vision-circle-loader.'.$unique_id.' .vision-circle-number-wrap {color:'.$number_color.';}';

	//must be called before return $output
	$this->orbit_dynamic_embed_css($style_code);
		
	return $output;
 }// END shortcode

/*--------------------------------------------------------------
Orbit - Circle Loader (icon)
--------------------------------------------------------------*/
public function render_orbit_circle_loader_icon($atts, $content = null) {
	extract(shortcode_atts(array(  
	'number'           => '50',
	'icon'             => '',
	'custom_icon' => '',
	'icon_color'       => '#d3565a',
	'width'            => '10',
	'style'            => 'square',
	'track_color'      => '#eeeeee',
	'bar_color'        => '#a0dbe1',
	'custom_css_class' => '',
	'unique_id'        => '',
	'custom_icon_upload' => '',
	'type' => '',
	'icon_fontawesome' => '',
	'icon_openiconic' => '',
	'icon_typicons' => '',
	'icon_entypoicons' => '',
	'icon_linecons' => '',
	'icon_entypo' => '',	
	), $atts));
	
	// Enqueue needed icon font.
	vc_icon_element_fonts_enqueue( $type );

	// Visual Composer helper function
	if(function_exists('wpb_js_remove_wpautop')){ $content = wpb_js_remove_wpautop($content, false);
    } else { $content = do_shortcode(shortcode_unautop($content)); }

    // Ensure HTML tags get closed
	$content = force_balance_tags($content);

	// Generate Unique ID to be used for styling
	$unique_id = orbit_truethemes_random();


	//added by denzel @since orbit-1.6-dev-5 this is default icon
	if(empty($icon) && empty(${"icon_" . $type})){
	${"icon_" . $type} = 'fa fa-adjust';
	}

	//Build output for icon
	if(!empty($icon)){
		$icon_output = '<i class="fa '.$icon.'"></i>';
	}
	
	if(!empty(${"icon_" . $type})){
		$icon_output = '<i class="fa '.esc_attr( ${"icon_" . $type} ).'"></i>';
	}	

	 /**
	 * Custom Icon
	 *
	 * $custom_icon is HTML code inputted from the user
	 * Visual composer uses base64_encode for a users HTML-input
	 * We're using base64_decode() to "un-encode" a users HTML input
	 * nothing malicious going on here :)
	 *
	 * @since Orbit 1.2
	 */
    if(!empty($custom_icon)){
    //custom icon will overwrite icon if there is any html entered by customer.
    	$icon_output = rawurldecode( base64_decode( strip_tags( $custom_icon) ) );
    }
    
    if(!empty($custom_icon_upload)){
    //custom icon upload will overwrite the above custom icon and icon.
        $uploaded_custom_icon_image = wp_get_attachment_image($custom_icon_upload,'full');
        $icon_output = '<span class="orbit-custom-icon-img">'.$uploaded_custom_icon_image.'</span>';
    }	
	
	$output = '<div class="vision-circle-loader-icon tt-orbit-montserrat '.$custom_css_class.' '.$unique_id.'""><div class="easyPieChart vision-circle-icon" data-percent="'.$number.'" data-trackcolor="'.$track_color.'" data-barcolor="'.$bar_color.'" data-linewidth="'.$width.'" data-linecap="'.$style.'">'.$icon_output.'<canvas></canvas></div> <div class="loader-details">'.$content.'</div></div>';

	// Dynamic CSS Function
	$style_code ='.vision-circle-loader-icon.'.$unique_id.' .fa{color:'.$icon_color.';}';

	//must be called before return $output
	$this->orbit_dynamic_embed_css($style_code);
	
	return $output;
}// END shortcode

/*--------------------------------------------------------------
Orbit - Dropcap
--------------------------------------------------------------*/
public function render_orbit_dropcap( $atts, $content = null ) {
	extract(shortcode_atts(array(
	'style'    => '',
	'color'    => '',
	'dropcap'  => 'O',
	'custom_css_class'   => ''
	), $atts));

	// Visual Composer helper function
	if(function_exists('wpb_js_remove_wpautop')){ $content = wpb_js_remove_wpautop($content, false);
    } else { $content = do_shortcode(shortcode_unautop($content)); }
	
	// Ensure HTML tags get closed
	$content = force_balance_tags($content);

	$output = '<div class="tt-dropcap-wrap '.$custom_css_class.'"><span class="tt-dropcap-'.$color.'"><span class="tt-dropcap-'.$style.'">' .$dropcap. '</span></span>'.$content.'</div>';

	return $output;
}// END shortcode

/*--------------------------------------------------------------
Orbit - Feature List
--------------------------------------------------------------*/
public function render_orbit_features( $atts, $content = null ) {      
		  extract(shortcode_atts(array(  
		  'icon'                 => '',
		  'icon_color'           => '#d3565a',
		  'icon_color_hover'     => '#ffffff',
		  'bg_color_hover'       => '#a2dce2',
		  'bg_color'             => '#ffffff',
		  'border_color'         => '#a2dce2',
		  'border_width'         => '2px',
		  'animate'              => '',
		  'url'                  => '',
		  'lightbox_content'     => '',
		  'lightbox_description' => '',
		  'custom_css_class'     => '',
		  'unique_id'            => '',
		  'custom_icon'          => '',
		  'custom_icon_upload'   => '',
		  'type' => '',
		  'icon_fontawesome' => '',
		  'icon_openiconic' => '',
		  'icon_typicons' => '',
		  'icon_entypoicons' => '',
		  'icon_linecons' => '',
		  'icon_entypo' => '',
		
		  ), $atts));
	 	 
	// Visual Composer helper function
	if(function_exists('wpb_js_remove_wpautop')){ $content = wpb_js_remove_wpautop($content, false);
    } else { $content = do_shortcode(shortcode_unautop($content)); }
    
    
    // Enqueue needed icon font.
	vc_icon_element_fonts_enqueue( $type );
	
	// Ensure HTML tags get closed
	$content = force_balance_tags($content);

	// Generate Unique ID to be used for styling
	$unique_id = orbit_truethemes_random();

	// Required by param => 'vc_link' to parse the link
	$url = vc_build_link( $url );
	// grab the attributes
	$a_href   = $url['url'];
	$a_title  = $url['title'];
	$a_target = $url['target'];
	
    $output = '<div class="vision-features '.$custom_css_class.' '.$unique_id.' tt_'.$animate.'">';
  
    if(!empty($lightbox_content)){
      $output .= '<a href="'.$lightbox_content.'" data-gal="prettyPhoto" title="'.$lightbox_description.'">';
    } elseif(!empty($a_href)){
      $output .= '<a href="'.$a_href.'" target="'.$a_target.'" title="'.$a_title.'">';
    }
    
    
    //added by denzel @since orbit-1.6-dev-5 this is default icon
	if(empty($icon) && empty(${"icon_" . $type})){
	${"icon_" . $type} = 'fa fa-adjust';
	}


    /**
	 * Custom Icon
	 *
	 * $custom_icon is HTML code inputted from the user
	 * Visual composer uses base64_encode for a users HTML-input
	 * We're using base64_decode() to "un-encode" a users HTML input
	 * nothing malicious going on here :)
	 *
	 * @since Orbit 1.1
	 * added custom icon upload @since Orbit 1.2
	 */
	 
	//Build output for icon
	if(!empty($icon)){
		$icon_output = '<i class="tt-vision-custom-icon fa '.$icon.'"></i>';  
	}
	
	if(!empty(${"icon_" . $type})){
		$icon_output = '<i class="tt-vision-custom-icon fa '.${"icon_" . $type}.'"></i>';
	}	

	 /**
	 * Custom Icon
	 *
	 * $custom_icon is HTML code inputted from the user
	 * Visual composer uses base64_encode for a users HTML-input
	 * We're using base64_decode() to "un-encode" a users HTML input
	 * nothing malicious going on here :)
	 *
	 * @since Orbit 1.2
	 */
    if(!empty($custom_icon)){
    //custom icon will overwrite icon if there is any html entered by customer.
    	$icon_output = rawurldecode( base64_decode( strip_tags( $custom_icon) ) );
    }
    
    if(!empty($custom_icon_upload)){
    //custom icon upload will overwrite the above custom icon and icon.
        $uploaded_custom_icon_image = wp_get_attachment_image($custom_icon_upload,'full');
        $icon_output = '<span class="orbit-custom-icon-img">'.$uploaded_custom_icon_image.'</span>';
    }	
		 
	//add icon back to output.
	$output.= $icon_output;
  
    if(!empty($lightbox_content)){
      $output .= '</a>';
    } elseif(!empty($a_href)){
      $output .= '</a>';
    }
  
    $output .= '<div class="vision-description tt-orbit-montserrat">'.$content.'</div></div>';

    // Dynamic CSS Function
	$style_code ='
	.vision-features.'.$unique_id.' .tt-vision-custom-icon,
	.vision-features.'.$unique_id.' .tt-orbit-custom-icon{color:'.$icon_color.';border: '.$border_width.' solid '.$border_color.';background:'.$bg_color.';}
	.vision-features.'.$unique_id.' .tt-vision-custom-icon:hover,
	.vision-features.'.$unique_id.' .tt-orbit-custom-icon:hover{color:'.$icon_color_hover.';background-color:'.$bg_color_hover.';}';

	//must be called before return $output
	$this->orbit_dynamic_embed_css($style_code);
  
    return $output;
}// END shortcode

/*--------------------------------------------------------------
Orbit - Heading
--------------------------------------------------------------*/
public function render_orbit_heading($atts, $content = null) {
	extract(shortcode_atts(array(
	'heading_text'       => 'Hello',
	'heading_color'      => '#363636',
	'heading_size'       => '30px',
	'heading_type'       => '',
	'heading_font'       => '',
	'heading_weight'     => '',
	'heading_style'      => '',
	'sub_heading_text'   => '',
	'sub_heading_color'  => '#555',
	'sub_heading_weight' => '',
	'sub_heading_size'   => '16px',
	'custom_css_class'   => '',
	'unique_id'          => ''
	), $atts));

	// Generate Unique ID to be used for styling
	$unique_id = orbit_truethemes_random();

	// Ensure HTML tags get closed
	$sub_heading_text = force_balance_tags($sub_heading_text);

	$output = '<div class="tt-orbit-heading-wrap '.$unique_id.' '.$heading_font.' '.$custom_css_class.' '.$heading_weight.'"><'.$heading_type.'>'.$heading_text.'</'.$heading_type.'><p class="'.$sub_heading_weight.'">'.$sub_heading_text.'</p></div>';

	// Dynamic CSS Function
	$style_code ='.tt-orbit-heading-wrap.'.$heading_font.'.'.$unique_id.'.'.$heading_weight.' '.$heading_type.' {
		color: '.$heading_color.';font-size: '.$heading_size.';text-transform: '.$heading_style.';}
		.tt-orbit-heading-wrap.'.$unique_id.' p {
			color: '.$sub_heading_color.';
			font-size: '.$sub_heading_size.';
		}';

	//must be called before return $output
	$this->orbit_dynamic_embed_css($style_code);

	return $output;
}// END shortcode

/*--------------------------------------------------------------
Orbit - Icon Box
--------------------------------------------------------------*/
public function render_orbit_icon_box($atts, $content = null) {
	extract(shortcode_atts(array(  
	'icon'                 => '',
	'custom_icon' => '',
	'icon_size'            => 'fa-4x',
	'icon_color'           => '#fff',
	'icon_bg_color'        => '#87C442',
	'box_bg_color'         => '#fff',
	'lightbox_content'     => '',
	'lightbox_description' => '',
	'url'                  => '',
	'custom_css_class'     => '',
	'unique_id'            => '',
	'custom_icon_upload' => '',
	'type' => '',
	'icon_fontawesome' => '',
	'icon_openiconic' => '',
	'icon_typicons' => '',
	'icon_entypoicons' => '',
	'icon_linecons' => '',
	'icon_entypo' => '',	
	), $atts));
	
	// Enqueue needed icon font.
	vc_icon_element_fonts_enqueue( $type );

	// Visual Composer helper function
	if(function_exists('wpb_js_remove_wpautop')){ $content = wpb_js_remove_wpautop($content, false);
	} else { $content = do_shortcode(shortcode_unautop($content)); }

	// Ensure HTML tags get closed
	$content = force_balance_tags($content);

	// Generate Unique ID to be used for styling
	$unique_id = orbit_truethemes_random();

	// Required by param => 'vc_link' to parse the link
	$url = vc_build_link( $url );
	// grab the attributes
	$a_href   = $url['url'];
	$a_title  = $url['title'];
	$a_target = $url['target'];

	
	//added by denzel @since orbit-1.6-dev-5 this is default icon
	if(empty($icon) && empty(${"icon_" . $type})){
	${"icon_" . $type} = 'fa fa-adjust';
	}
	
	
	//Build output for icon
	if(!empty($icon)){
		$icon_output = '<span class="fa-stack '.$icon_size.'"><i class="fa fa-circle fa-stack-2x"></i><i class="fa '.$icon.' fa-stack-1x fa-inverse"></i></span>'; //leave this for backward compatibility
	}
		

	if(!empty(${"icon_" . $type})){
		$icon_output = '<span class="fa-stack '.$icon_size.'"><i class="fa fa-circle fa-stack-2x"></i><i class="'.esc_attr( ${"icon_" . $type} ).' fa-stack-1x fa-inverse"></i></span>'; //added by denzel @since orbit-1.6-dev-4
	}		
	


	 /**
	 * Custom Icon
	 *
	 * $custom_icon is HTML code inputted from the user
	 * Visual composer uses base64_encode for a users HTML-input
	 * We're using base64_decode() to "un-encode" a users HTML input
	 * nothing malicious going on here :)
	 *
	 * @since Orbit 1.2
	 */
    if(!empty($custom_icon)){
    //custom icon will overwrite icon if there is any html entered by customer.
    	$icon_output = rawurldecode( base64_decode( strip_tags( $custom_icon) ) );
    }
	

	if(!empty($custom_icon_upload)){
    //custom icon upload will overwrite the above custom icon and icon.
        $uploaded_custom_icon_image = wp_get_attachment_image($custom_icon_upload,'full');
        $icon_output = '<span class="orbit-custom-icon-img">'.$uploaded_custom_icon_image.'</span>';
    }	


	//build the shortcode
	$before = '';
	if(!empty($lightbox_content)){
	$before = '<a href="'.$lightbox_content.'" data-gal="prettyPhoto" title="'.$lightbox_description.'" class="vision-icon-box '.$custom_css_class.' '.$unique_id.'">';
	} elseif(!empty($a_href)){
	$before = '<a href="'.$a_href.'" target="'.$a_target.'" title="'.$a_title.'" class="vision-icon-box '.$custom_css_class.' '.$unique_id.'">';
	} else {
	$before = '<div class="vision-icon-box '.$custom_css_class.' '.$unique_id.'">';
	}

	$after = '';
	if(!empty($lightbox_content)){
		$after .= '</a>';
	} elseif(!empty($a_href)){
		$after .= '</a>';
	} else {
		$after .= '</div>'; 
	}

	// Dynamic CSS Function
	$style_code ='.vision-icon-box.'.$unique_id.'{background:'.$box_bg_color.';}
	.vision-icon-box.'.$unique_id.' .fa.fa-circle{color:'.$icon_bg_color.';}
	.vision-icon-box.'.$unique_id.' .fa-inverse{color:'.$icon_color.';}';

	//must be called before return $output
	$this->orbit_dynamic_embed_css($style_code);
	
	if(function_exists('truethemes_formatter')){ //We have to do this when customer use orbit with Karma Theme, or it will break.
	$output = "[raw]".$before.$icon_output."[/raw]".$content."[raw]".$after."[/raw]";
	}else{
	$output = $before.$icon_output.$content.$after;
	}
	
	return $output;

}// END shortcode

/*--------------------------------------------------------------
Orbit - Icon + Text
--------------------------------------------------------------*/
public function render_orbit_icon_content( $atts, $content = null ) {      
	extract(shortcode_atts(array(
		'icon'             => '',
		'custom_icon' => '',
		'icon_color'       => '#fff',
		'icon_bg_color'    => '#3b86c4',
		'custom_css_class' => '',
		'unique_id'        => '',
		'custom_icon_upload' => '',
		'type' => '',
		'icon_fontawesome' => '',
		'icon_openiconic' => '',
		'icon_typicons' => '',
		'icon_entypoicons' => '',
		'icon_linecons' => '',
		'icon_entypo' => '',		
	), $atts));

	// Enqueue needed icon font.
	vc_icon_element_fonts_enqueue( $type );
	 	 
	// Visual Composer helper function
	if(function_exists('wpb_js_remove_wpautop')){ $content = wpb_js_remove_wpautop($content, false);
    } else { $content = do_shortcode(shortcode_unautop($content)); }
	
	// Ensure HTML tags get closed
	$content = force_balance_tags($content);

	// Generate Unique ID to be used for styling
	$unique_id = orbit_truethemes_random();
	
	//added by denzel @since orbit-1.6-dev-5 this is default icon
	if(empty($icon) && empty(${"icon_" . $type})){
	${"icon_" . $type} = 'fa fa-adjust';
	}
	
	//Build output for icon
	if(!empty($icon)){
		$icon_output = '<span class="fa '.$icon.' orbit-icon"></span>';//keep for backward compatibility
	}
	
	if(!empty(${"icon_" . $type})){
		$icon_output = '<span class="fa '.esc_attr( ${"icon_" . $type} ).' orbit-icon"></span>';//@since orbit-1.6-dev-4
	}	

	 /**
	 * Custom Icon
	 *
	 * $custom_icon is HTML code inputted from the user
	 * Visual composer uses base64_encode for a users HTML-input
	 * We're using base64_decode() to "un-encode" a users HTML input
	 * nothing malicious going on here :)
	 *
	 * @since Orbit 1.2
	 */
    if(!empty($custom_icon)){
    //custom icon will overwrite icon if there is any html entered by customer.
    	$icon_output = rawurldecode( base64_decode( strip_tags( $custom_icon) ) );
    }
    
    
	if(!empty($custom_icon_upload)){
    //custom icon upload will overwrite the above custom icon and icon.
        $uploaded_custom_icon_image = wp_get_attachment_image($custom_icon_upload,'full');
        $icon_output = '<span class="orbit-custom-icon-img">'.$uploaded_custom_icon_image.'</span>';
    }	    
	

	$output  = '<div class="orbit-icon-wrap '.$custom_css_class.' '.$unique_id.'">'.$icon_output.'<div class="orbit-icon-text">'.$content.'</div></div>';

	// Dynamic CSS Function
	$style_code = '.orbit-icon-wrap.'.$unique_id.' .orbit-icon {color:'.$icon_color.';background-color:'.$icon_bg_color.';}';

	//must be called before return $output
	$this->orbit_dynamic_embed_css($style_code);

	return $output;
}// END shortcode

/*--------------------------------------------------------------
Orbit Icon (PNG)
--------------------------------------------------------------*/
public function render_orbit_icon_png($atts, $content = null) {
	extract(shortcode_atts(array(
	'url'                  => '',
	'icon'                 => '',
	'lightbox_content'     => '',
	'lightbox_description' => '',
	'custom_css_class'   => ''
  ), $atts));

	// Visual Composer helper function
	if(function_exists('wpb_js_remove_wpautop')){ $content = wpb_js_remove_wpautop($content, false);
    } else { $content = do_shortcode(shortcode_unautop($content)); }
	
	// Ensure HTML tags get closed
	$content = force_balance_tags($content);

	// Required by param => 'vc_link' to parse the link
	$url = vc_build_link( $url );
	// grab the attributes
	$a_href   = $url['url'];
	$a_title  = $url['title'];
	$a_target = $url['target'];
  
	if(!empty($a_href)){
		$output = '<a href="'.$a_href.'" class="tt-icon-link tt-icon orbit-'.$icon.'" target="'.$a_target.'" title="'.$a_title.'">'.$content.'</a>';
	}

	if(empty($a_href)){
		$output = '<p class="tt-icon orbit-'.$icon.'">'.$content.'</p>';
	}

	if(!empty($lightbox_content)){
		$output = '<a href="'.$lightbox_content.'" class="tt-icon-link tt-icon orbit-'.$icon.'" data-gal="prettyPhoto" title="'.$lightbox_description.'">'.$content.'</a>';
	}

	return $output;
}// END shortcode

/*--------------------------------------------------------------
Orbit - Image Box 1
--------------------------------------------------------------*/
public function render_orbit_imagebox_1( $atts, $content = null ) {      
	extract(shortcode_atts(array(
	'attachment_id'    => '',
	'img_border_color' => '#cf6e6e',
	'img_border_width' => '8px',
	'box_bg_color'     => '#fff',
	'sub_title'        => 'Sub Title',
	'main_title'       => 'Main Title',
	'main_title_color' => '#cf6e6e',
	'url'              => '',
	'image_html'       => '',		 	
	'overlay_link'     => '',
	'lightbox_content'     => '',
	'lightbox_description' => '',
	'custom_css_class'     => '',
	'unique_id'            => ''
	), $atts));
	 	 
	// Visual Composer helper function
	if(function_exists('wpb_js_remove_wpautop')){ $content = wpb_js_remove_wpautop($content, false);
    } else { $content = do_shortcode(shortcode_unautop($content)); }
	
	// Ensure HTML tags get closed
	$content = force_balance_tags($content);

	// Generate Unique ID to be used for styling
	$unique_id = orbit_truethemes_random();

	// Required by param => 'vc_link' to parse the link
	$url = vc_build_link( $url );
	// grab the attributes
	$a_href   = $url['url'];
	$a_title  = $url['title'];
	$a_target = $url['target'];
	
	// Let WordPress build the whole img tag so it includes ALT and other attributes.
	// Using large size here to prevent server crash from full-size images
	$image_html = wp_get_attachment_image( $attachment_id,'large' );
	
	if(!empty($a_href)){
	$overlay_link = '<a class="overlay-link" href="'.$a_href.'" target="'.$a_target.'" title="'.$a_title.'"></a>';
	}

	if(!empty($lightbox_content)){
	$overlay_link = '<a class="overlay-link" href="'.$lightbox_content.'" data-gal="prettyPhoto" title="'.$lightbox_description.'"></a>';
	}
	
	$output = '<div class="orbit-image-box-1 '.$custom_css_class.' '.$unique_id.'"><div class="orbit-img-wrap">'.$image_html.'</div><div class="orbit-text-wrap"><div class="callout-heading-wrap tt-orbit-montserrat"><h4>'.$sub_title.'</h4><h3>'.$main_title.'</h3></div><div class="callout-details-wrap">'.$content.'</div></div>'.$overlay_link.'</div>';

	// Dynamic CSS Function
	$style_code = '.orbit-image-box-1.'.$unique_id.' .orbit-img-wrap {border-bottom: '.$img_border_width.' solid '.$img_border_color.';}
	.orbit-image-box-1.'.$unique_id.' {background: '.$box_bg_color.';}
	.orbit-image-box-1.'.$unique_id.' .orbit-text-wrap .callout-heading-wrap h3 {color: '.$main_title_color.';}';

	//must be called before return $output
	$this->orbit_dynamic_embed_css($style_code);

	 return $output;
}// END shortcode

/*--------------------------------------------------------------
Orbit - Image Box 2
--------------------------------------------------------------*/
public function render_orbit_imagebox_2( $atts, $content = null ) {      
	extract(shortcode_atts(array(
	'attachment_id'        => '',
	'icon'                 => '',
	'box_bg_color'         => '#fff',
	'icon_bg_color'        => '#87C442',
	'icon_color'           => '#fff',
	'link_color'           => '#3b86c4',
	'link_text'            => '',
	'url'                  => '',
	'image_html'           => '',		 	
	'overlay_link'         => '',
	'lightbox_content'     => '',
	'lightbox_description' => '',
	'box_link'             => '', //this is made up variable....no input from user
	'custom_css_class'     => '',
	'unique_id'            => '',
	'custom_icon_upload' => '',
	'type' => '',
	'icon_fontawesome' => '',
	'icon_openiconic' => '',
	'icon_typicons' => '',
	'icon_entypoicons' => '',
	'icon_linecons' => '',
	'icon_entypo' => '',	
	), $atts));

	// Enqueue needed icon font.
	vc_icon_element_fonts_enqueue( $type );
	 	 
	// Visual Composer helper function
	if(function_exists('wpb_js_remove_wpautop')){ $content = wpb_js_remove_wpautop($content, false);
    } else { $content = do_shortcode(shortcode_unautop($content)); }
	
	// Ensure HTML tags get closed
	$content = force_balance_tags($content);

	// Generate Unique ID to be used for styling
	$unique_id = orbit_truethemes_random();

	// Required by param => 'vc_link' to parse the link
	$url = vc_build_link( $url );
	// grab the attributes
	$a_href   = $url['url'];
	$a_title  = $url['title'];
	$a_target = $url['target'];
	
	// Let WordPress build the whole img tag so it includes ALT and other attributes.
	// Using large size here to prevent server crash from full-size images
	$image_html = wp_get_attachment_image( $attachment_id,'large' );

	if(!empty($link_text)){ //this is centered text link at bottom of image-box
		$box_link = '<p class="orbit-image-box-2-link"><a href="'.$a_href.'" target="'.$a_target.'" title="'.$a_title.'">'.$link_text.'</a></p>';
	}

	if(!empty($a_href)){
	$overlay_link = '<a class="overlay-link" href="'.$a_href.'" target="'.$a_target.'" title="'.$a_title.'"></a>';
	}

	if(!empty($lightbox_content)){
	$overlay_link = '<a class="overlay-link" href="'.$lightbox_content.'" data-gal="prettyPhoto" title="'.$lightbox_description.'"></a>';
	}	
	
	//added by denzel @since orbit-1.6-dev-5 this is default icon
	if(empty($icon) && empty(${"icon_" . $type})){
	${"icon_" . $type} = 'fa fa-adjust';
	}
		
	
	//Build output for icon
	if(!empty($icon)){
		$icon_output = '<i class="fa '.$icon.'"></i>';//keep for backward compatibility
	}
	
	if(!empty(${"icon_" . $type})){
		$icon_output = '<i class="fa '.esc_attr( ${"icon_" . $type} ).'"></i>';//@since orbit-1.6-dev-4	
	}	

	 /**
	 * Custom Icon
	 *
	 * $custom_icon is HTML code inputted from the user
	 * Visual composer uses base64_encode for a users HTML-input
	 * We're using base64_decode() to "un-encode" a users HTML input
	 * nothing malicious going on here :)
	 *
	 * @since Orbit 1.2
	 */
    if(!empty($custom_icon)){
    //custom icon will overwrite icon if there is any html entered by customer.
    	$icon_output = rawurldecode( base64_decode( strip_tags( $custom_icon) ) );
    }	
    
    if(!empty($custom_icon_upload)){
    //custom icon upload will overwrite the above custom icon and icon.
        $uploaded_custom_icon_image = wp_get_attachment_image($custom_icon_upload,'full');
        $icon_output = '<span class="orbit-custom-icon-img">'.$uploaded_custom_icon_image.'</span>';
    }	
	
	
	$output = '<div class="orbit-image-box-2 '.$custom_css_class.' '.$unique_id.'"><div class="orbit-img-wrap">'.$image_html.'</div><div class="orbit-text-wrap"><span class="icon-circ-wrap">'.$icon_output.'</span><div class="callout-details-wrap tt-orbit-montserrat">'.$content.$box_link.'</div></div>'.$overlay_link.'</div>';

	// Dynamic CSS Function
	$style_code = '.orbit-image-box-2.'.$unique_id.' {background: '.$box_bg_color.';}
	.orbit-image-box-2.'.$unique_id.' .icon-circ-wrap {background: '.$icon_bg_color.';}
	.orbit-image-box-2.'.$unique_id.' .orbit-text-wrap .icon-circ-wrap i {color: '.$icon_color.';}
	.orbit-image-box-2.'.$unique_id.' .orbit-image-box-2-link a {color: '.$link_color.';border-bottom: 1px dotted '.$link_color.';}';

	//must be called before return $output
	$this->orbit_dynamic_embed_css($style_code);

	 return $output;
}// END shortcode

/*--------------------------------------------------------------
Orbit - Number Counter
--------------------------------------------------------------*/	
public function render_orbit_number_counter($atts, $content = null) {
	extract(shortcode_atts(array(
	'number'           => '125',
	'number_color'     => '#000',
	'title'            => 'Lorem Ipsum',
	'title_color'      => '#000',
	'divider_height'   => '4px',
	'divider_color'    => '#e1e1e1',
	'custom_css_class' => '',
	'unique_id'        => ''
	), $atts));
	  
	if(function_exists('wpb_js_remove_wpautop')){					  
    $title = wpb_js_remove_wpautop($title, false);
    }else{
    $title = do_shortcode(shortcode_unautop($title));
    }
	
	// Ensure HTML tags get closed
	$title = force_balance_tags($title);

	// Generate Unique ID to be used for styling
	$unique_id = orbit_truethemes_random();

	// Dynamic CSS Function
	$style_code = '.vision-counter-wrap.'.$unique_id.' h3.vision-counter {color: '.$number_color.';}
	.vision-counter-wrap.'.$unique_id.' h3:after {background: '.$divider_color.';height: '.$divider_height.';}
	.vision-counter-wrap.'.$unique_id.' h4 {color: '.$title_color.';}';

	//must be called before return $output
	$this->orbit_dynamic_embed_css($style_code);	  
	
	return '<div class="vision-counter-wrap '.$custom_css_class.' '.$unique_id.'"><h3 class="vision-counter vision-zero">'.$number.'</h3><h4>'.$title.'</h4></div>';
}// END shortcode

/*--------------------------------------------------------------
Orbit - Pricing Box 1
--------------------------------------------------------------*/
//styles: true-vision-pricing-style-1, true-vision-pricing-style-2
function render_orbit_pricing_box_1($atts, $content = null) {
	extract(shortcode_atts(array(
	'style'         => '',
	'color'         => '',
	'plan'          => 'Pro',
	'currency'      => '$',
	'price'         => '39',
	'term'          => 'per month',
	'button_label'  => 'Sign Up',
	'button_size'   => '',
	'button_color'  => '',
	'url'           => '',
	'custom_css_class'   => ''
	), $atts));

	// Visual Composer helper function
	if(function_exists('wpb_js_remove_wpautop')){ $content = wpb_js_remove_wpautop($content, false);
    } else { $content = do_shortcode(shortcode_unautop($content)); }
	
	// Ensure HTML tags get closed
	$content = force_balance_tags($content);

	// Required by param => 'vc_link' to parse the link
	$url = vc_build_link( $url );
	// grab the attributes
	$a_href   = $url['url'];
	$a_title  = $url['title'];
	$a_target = $url['target'];
	
	if ($style == 'style-1'){
	$output = '<div class="true-vision-pricing-column true-vision-pricing-'.$style.'"><div class="true-vision-pricing-top tt-cb-title-'.$color.'">
	<h2>'.$plan.'</h2>
	<h1><sup>'.$currency.'</sup>'.$price.'</h1>
	<p>'.$term.'</p>
	</div>'.$content.'<hr />
	<a href="'.$a_href.'" class="'.sanitize_html_class( $button_size ).' '.sanitize_html_class( $button_color ).' vision-button" target="'.$a_target.'" title="'.$a_title.'">' .$button_label. '</a></div>';
	}
	
	if ($style == 'style-2'){
	$output = '<div class="true-vision-pricing-column true-vision-pricing-'.$style.'"><div class="true-vision-pricing-top tt-cb-title-'.$color.'">
	<h2>'.$plan.'</h2>
	</div>'.$content.'<hr /><h1><sup>'.$currency.'</sup>'.$price.'</h1>
	<p>'.$term.'</p>
	<a href="'.$a_href.'" class="'.sanitize_html_class( $button_size ).' '.sanitize_html_class( $button_color ).' vision-button" target="'.$a_target.'" title="'.$a_title.'">' .$button_label. '</a></div>';
	}
	
  return $output;
} // END shortcode

/*--------------------------------------------------------------
Orbit - Progress Bar
--------------------------------------------------------------*/
public function render_orbit_progress_bar( $atts, $content = null ) {      
	  extract(shortcode_atts(array(  
	  'title'            => 'Lorem Ipsum',
	  'title_color'      => '#000',
	  'number'           => '50',
	  'number_color'     => '#000',
	  'track_color'      => '#e1e1e1',
	  'bar_color'        => '#a2dce2',
	  'symbol'           => '%',
	  'custom_css_class' => '',
	  'unique_id'        => ''
	  ), $atts));

	if(function_exists('wpb_js_remove_wpautop')){					  
    $title = wpb_js_remove_wpautop($title, false);
    }else{
    $title = do_shortcode(shortcode_unautop($title));
    }
	
	// Ensure HTML tags get closed
	$title = force_balance_tags($title);

	// Generate Unique ID to be used for styling
	$unique_id = orbit_truethemes_random();

	// Dynamic CSS Function
	$style_code = '.vision-progress-section.'.$unique_id.' h4.pull-left {color: '.$title_color.';}
	.vision-progress-section.'.$unique_id.' h4.pull-right {color: '.$number_color.';}
	.vision-progress-section.'.$unique_id.' .progress {background: '.$track_color.';}
	.vision-progress-section.'.$unique_id.' .progress-bar {background: '.$bar_color.';}';

	//must be called before return $output
	$this->orbit_dynamic_embed_css($style_code);
	
	 return '<div class="vision-progress-section '.$custom_css_class.' '.$unique_id.'"><div class="progress-title clearfix"><h4 class="pull-left">'.$title.'</h4><h4 class="pull-right"><span class="vision-progress-number"><span>'.$number.'</span></span>'.$symbol.'</h4></div><div class="progress"><div class="progress-bar" data-number="'.$number.'"></div></div></div>';

}// END shortcode

/*--------------------------------------------------------------
Orbit - Progress Bar (Vertical)
--------------------------------------------------------------*/
public function render_orbit_progress_bar_vertical($atts, $content = null) {
	extract(shortcode_atts(array(  
	'title'            => 'Lorem Ipsum',
    'title_color'      => '#000',
    'number'           => '50',
    'number_color'     => '#000',
    'track_color'      => '#e1e1e1',
    'bar_color'        => '#a2dce2',
    'symbol'           => '%',
    'custom_css_class' => '',
	'unique_id'        => ''
	), $atts));
	  
	if(function_exists('wpb_js_remove_wpautop')){					  
    $title = wpb_js_remove_wpautop($title, false);
    }else{
    $title = do_shortcode(shortcode_unautop($title));
    }
	
	// Ensure HTML tags get closed
	$title = force_balance_tags($title);

	// Generate Unique ID to be used for styling
	$unique_id = orbit_truethemes_random();

	// Dynamic CSS Function
	$style_code = '.vision-progress-section-vertical.'.$unique_id.' h4.vision-progress-title {color: '.$title_color.';}
	.vision-progress-section-vertical.'.$unique_id.' h4.vision-progress-text {color: '.$number_color.';}
	.vision-progress-section-vertical.'.$unique_id.' .progress-wrapper {background: '.$track_color.';}
	.vision-progress-section-vertical.'.$unique_id.' .progress-bar-vertical {background: '.$bar_color.';}';

	//must be called before return $output
	$this->orbit_dynamic_embed_css($style_code);
	
	return '<div class="vision-progress-section-vertical '.$custom_css_class.' '.$unique_id.'"><div class="progress-wrapper"><div class="progress-bar-vertical" data-number="'.$number.'"></div></div><h4 class="vision-progress-title">'.$title.'</h4><h4 class="vision-progress-text"><span class="vision-progress-number"><span>'.$number.'</span></span>'.$symbol.'</h4></div>';
}// END shortcode

/*--------------------------------------------------------------
Orbit - Service List
--------------------------------------------------------------*/
public function render_orbit_services( $atts, $content = null ) {      
	extract(shortcode_atts(array(
	'icon'                 => '',
	'icon_color'           => '#d3565a',
	'icon_color_hover'     => '#ffffff',
	'bg_color'             => '#fff',
	'bg_color_hover'       => '#a2dce2',
	'border_color'         => '#a2dce2',
	'border_width'         => '2px',
	'animate'              => '',
	'url'                  => '',
	'lightbox_content'     => '',
	'lightbox_description' => '',
	'custom_css_class'     => '',
	'unique_id'            => '',
	'custom_icon'          => '',
	'custom_icon_upload'   => '',
	'type' => '',
	'icon_fontawesome' => '',
	'icon_openiconic' => '',
	'icon_typicons' => '',
	'icon_entypoicons' => '',
	'icon_linecons' => '',
	'icon_entypo' => '',
	), $atts));

	// Enqueue needed icon font.
	vc_icon_element_fonts_enqueue( $type );
		 	 
	// Visual Composer helper function
	if(function_exists('wpb_js_remove_wpautop')){ $content = wpb_js_remove_wpautop($content, false);
    } else { $content = do_shortcode(shortcode_unautop($content)); }
	
	// Ensure HTML tags get closed
	$content = force_balance_tags($content);

	// Generate Unique ID to be used for styling
	$unique_id = orbit_truethemes_random();

	// Required by param => 'vc_link' to parse the link
	$url = vc_build_link( $url );
	// grab the attributes
	$a_href   = $url['url'];
	$a_title  = $url['title'];
	$a_target = $url['target'];
	
    $output = '<div class="vision-services '.$custom_css_class.' '.$unique_id.' tt_'.$animate.'">';
  
    if(!empty($lightbox_content)){
      $output .= '<a href="'.$lightbox_content.'" data-gal="prettyPhoto" title="'.$lightbox_description.'">';
    } elseif(!empty($a_href)){
      $output .= '<a href="'.$a_href.'" target="'.$a_target.'" title="'.$a_title.'">';
    }
    
	//added by denzel @since orbit-1.6-dev-5 this is default icon
	if(empty($icon) && empty(${"icon_" . $type})){
	${"icon_" . $type} = 'fa fa-adjust';
	}    
    
	//Build output for icon
	if(!empty($icon)){
		$icon_output = '<i class="tt-vision-custom-icon fa '.$icon.'"></i>';  
	}
	
	if(!empty(${"icon_" . $type})){
		$icon_output = '<i class="tt-vision-custom-icon fa '.esc_attr( ${"icon_" . $type} ).'"></i>';  	
	}	

	 /**
	 * Custom Icon
	 *
	 * $custom_icon is HTML code inputted from the user
	 * Visual composer uses base64_encode for a users HTML-input
	 * We're using base64_decode() to "un-encode" a users HTML input
	 * nothing malicious going on here :)
	 *
	 * @since Orbit 1.2
	 */
    if(!empty($custom_icon)){
    //custom icon will overwrite icon if there is any html entered by customer.
    	$icon_output = rawurldecode( base64_decode( strip_tags( $custom_icon) ) );
    }
    
    if(!empty($custom_icon_upload)){
    //custom icon upload will overwrite the above custom icon and icon.
        $uploaded_custom_icon_image = wp_get_attachment_image($custom_icon_upload,'full');
        $icon_output = '<span class="orbit-custom-icon-img">'.$uploaded_custom_icon_image.'</span>';
    }	
		 
	//add icon back to output.
	$output.= $icon_output;    
    
  
    if(!empty($lightbox_content)){
      $output .= '</a>';
    } elseif(!empty($a_href)){
      $output .= '</a>';
    }
  
    $output .= '<div class="vision-description tt-orbit-montserrat">'.$content.'</div></div>';

    // Dynamic CSS Function
	$style_code ='.vision-services.'.$unique_id.' .tt-vision-custom-icon,
	.vision-services.'.$unique_id.' .tt-orbit-custom-icon{color:'.$icon_color.';border: '.$border_width.' solid '.$border_color.';background:'.$bg_color.';}
	.vision-services.'.$unique_id.' .tt-vision-custom-icon:hover,
	.vision-services.'.$unique_id.' .tt-orbit-custom-icon:hover{color:'.$icon_color_hover.';background-color:'.$bg_color_hover.';}';

	//must be called before return $output
	$this->orbit_dynamic_embed_css($style_code);
  
    return $output;
}// END shortcode

/*--------------------------------------------------------------
Orbit - Social
--------------------------------------------------------------*/
public function render_orbit_social($atts, $content = null) {
	extract(shortcode_atts(array(
	'design'      => '',
	'icon_style'  => '',
	'round_class' => '',
	'twitter'     => '',
	'facebook'    => '',
	'dribbble'    => '',
	'flickr'      => '',
	'google'      => '',
	'instagram'   => '',
	'linkedin'    => '',
	'pinterest'   => '',
	'rss'         => '',
	'skype'       => '',
	'vimeo'       => '',
	'wordpress'   => '',
	'youtube'     => '',
	'custom_css_class'   => ''
	), $atts));

	// Required by param => 'vc_link' to parse the link
	$twitter = vc_build_link( $twitter );
	// grab the attributes
	$a_href_twitter   = $twitter['url']; $a_title_twitter  = $twitter['title']; $a_target_twitter = $twitter['target'];

	$facebook = vc_build_link( $facebook );
	$a_href_facebook   = $facebook['url']; $a_title_facebook  = $facebook['title']; $a_target_facebook = $facebook['target'];

	$dribbble = vc_build_link( $dribbble );
	$a_href_dribbble   = $dribbble['url']; $a_title_dribbble  = $dribbble['title']; $a_target_dribbble = $dribbble['target'];

	$flickr = vc_build_link( $flickr );
	$a_href_flickr   = $flickr['url']; $a_title_flickr  = $flickr['title']; $a_target_flickr = $flickr['target'];

	$google = vc_build_link( $google );
	$a_href_google   = $google['url']; $a_title_google  = $google['title']; $a_target_google = $google['target'];

	$instagram = vc_build_link( $instagram );
	$a_href_instagram   = $instagram['url']; $a_title_instagram  = $instagram['title']; $a_target_instagram = $instagram['target'];

	$linkedin = vc_build_link( $linkedin );
	$a_href_linkedin   = $linkedin['url']; $a_title_linkedin  = $linkedin['title']; $a_target_linkedin = $linkedin['target'];

	$pinterest = vc_build_link( $pinterest );
	$a_href_pinterest   = $pinterest['url']; $a_title_pinterest  = $pinterest['title']; $a_target_pinterest = $pinterest['target'];

	$rss = vc_build_link( $rss );
	$a_href_rss   = $rss['url']; $a_title_rss  = $rss['title']; $a_target_rss = $rss['target'];

	$skype = vc_build_link( $skype );
	$a_href_skype   = $skype['url']; $a_title_skype  = $skype['title']; $a_target_skype = $skype['target'];

	$vimeo = vc_build_link( $vimeo );
	$a_href_vimeo   = $vimeo['url']; $a_title_vimeo  = $vimeo['title']; $a_target_vimeo = $vimeo['target'];

	$wordpress = vc_build_link( $wordpress );
	$a_href_wordpress   = $wordpress['url']; $a_title_wordpress  = $wordpress['title']; $a_target_wordpress = $wordpress['target'];

	$youtube = vc_build_link( $youtube );
	$a_href_youtube   = $youtube['url']; $a_title_youtube  = $youtube['title']; $a_target_youtube = $youtube['target'];

	//check for round icons
	if ($icon_style == 'round') {$round_class = ' vs-round';}
	//round style + square design
	if (($icon_style == 'round') && ($design == 'square')) {$round_class = ' vs-fill';}

	$output = '<ul class="vision-social vs-'.$design.$round_class.'">';

	if (!empty($a_href_twitter)){  $output .= '<li><a href="'.$a_href_twitter.'" class="fa fa-twitter" title="'.$a_title_twitter.'" target="'.$a_target_twitter.'"></a></li>';}
	if (!empty($a_href_facebook)){ $output .= '<li><a href="'.$a_href_facebook.'" class="fa fa-facebook" title="'.$a_title_facebook.'" target="'.$a_target_facebook.'"></a></li>';}
	if (!empty($a_href_dribbble)){ $output .= '<li><a href="'.$a_href_dribbble.'" class="fa fa-dribbble" title="'.$a_title_dribbble.'" target="'.$a_target_dribbble.'"></a></li>';}
	if (!empty($a_href_flickr)){   $output .= '<li><a href="'.$a_href_flickr.'" class="fa fa-flickr" title="'.$a_title_flickr.'" target="'.$a_target_flickr.'"></a></li>';}
	if (!empty($a_href_google)){   $output .= '<li><a href="'.$a_href_google.'" class="fa fa-google-plus" title="'.$a_title_google.'" target="'.$a_target_google.'"></a></li>';}
	if (!empty($a_href_instagram)){$output .= '<li><a href="'.$a_href_instagram.'" class="fa fa-instagram" title="'.$a_title_instagram.'" target="'.$a_target_instagram.'"></a></li>';}
	if (!empty($a_href_linkedin)){ $output .= '<li><a href="'.$a_href_linkedin.'" class="fa fa-linkedin" title="'.$a_title_linkedin.'" target="'.$a_target_linkedin.'"></a></li>';}
	if (!empty($a_href_pinterest)){$output .= '<li><a href="'.$a_href_pinterest.'" class="fa fa-pinterest" title="'.$a_title_pinterest.'" target="'.$a_target_pinterest.'"></a></li>';}
	if (!empty($a_href_rss)){      $output .= '<li><a href="'.$a_href_rss.'" class="fa fa-rss" title="'.$a_title_rss.'" target="'.$a_target_rss.'"></a></li>';}
	if (!empty($a_href_skype)){    $output .= '<li><a href="'.$a_href_skype.'" class="fa fa-skype" title="'.$a_title_skype.'" target="'.$a_target_skype.'"></a></li>';}
	if (!empty($a_href_vimeo)){    $output .= '<li><a href="'.$a_href_vimeo.'" class="fa fa-vimeo-square" title="'.$a_title_vimeo.'" target="'.$a_target_vimeo.'"></a></li>';}
	if (!empty($a_href_wordpress)){$output .= '<li><a href="'.$a_href_wordpress.'" class="fa fa-wordpress" title="'.$a_title_wordpress.'" target="'.$a_target_wordpress.'"></a></li>';}
	if (!empty($a_href_youtube)){  $output .= '<li><a href="'.$a_href_youtube.'" class="fa fa-youtube-play" title="'.$a_title_youtube.'" target="'.$a_target_youtube.'"></a></li>';}

	$output .= '</ul>';

	return $output;
	}// END shortcode

/*--------------------------------------------------------------
Orbit - Tab 1
--------------------------------------------------------------*/ 
public function render_orbit_tab_1( $atts, $content = null ) {      
	extract(shortcode_atts(array(
	'tab_id'               => '',
	'color_scheme'         => '',
	'custom_css_class'     => ''
	), $atts));
	 	 
	// Visual Composer helper function
	if(function_exists('wpb_js_remove_wpautop')){ $content = wpb_js_remove_wpautop($content, false);
    } else { $content = do_shortcode(shortcode_unautop($content)); }
	
	// Ensure HTML tags get closed
	$content = force_balance_tags($content);

	// Generate Unique ID to be used for styling
	$unique_id = orbit_truethemes_random();
	
	// Generate Random ID
	$tab_id = orbit_truethemes_random();
	
	$output = '<div class="tt-tabcolor-'.$color_scheme.' orbit-tabs-style-1 '.$custom_css_class.'" id="'.$tab_id.'">
	<ul class="'.$tab_id.' orbit-nav nav-pills nav-justified" role="tablist"></ul><div class="orbit-tab-content">'.$content.'</div></div>';

	/**
	* javascript for auto generating the tab navigation <li>, 
	* because cannot use PHP due to the way html is nested.
	* Javascript function orbit_built_tab_nav() is found in orbit.js
	*/
	$output.= '<script type="text/javascript">jQuery(document).ready(function(){truethemes_orbit_tabs("'.$tab_id.'");});</script>';

	 return $output;
}// END shortcode

/*--------------------------------------------------------------
Orbit - Tab 1 [content]
--------------------------------------------------------------*/
public function render_orbit_tab_1_content( $atts, $content = null ) {      
	extract(shortcode_atts(array(
	'tab_content_id' => '',
	'tab_active'     => '',
	'nav_tab_title'  => 'New Tab',
	'icon'           => '',
	'custom_icon'    => '',
	'custom_icon_upload'=> '',
	'type' => '',
	'icon_fontawesome' => '',
	'icon_openiconic' => '',
	'icon_typicons' => '',
	'icon_entypoicons' => '',
	'icon_linecons' => '',
	'icon_entypo' => '',
	), $atts));
	
	// Enqueue needed icon font.
	vc_icon_element_fonts_enqueue( $type ); 	 
	 	 
	// Visual Composer helper function
	if(function_exists('wpb_js_remove_wpautop')){ $content = wpb_js_remove_wpautop($content, false);
    } else { $content = do_shortcode(shortcode_unautop($content)); }
	
	// Ensure HTML tags get closed
	$content = force_balance_tags($content);
	
	if($tab_active == 'yes'){ $active = "in active"; } else { $active = ''; }

	// Generate Random ID
	$tab_content_id = orbit_truethemes_random();

	//added by denzel @since orbit-1.6-dev-5 this is default icon
	if(empty($icon) && empty(${"icon_" . $type})){
	${"icon_" . $type} = 'fa fa-adjust';
	}
		
	if($icon !== ''){
	$icon = $icon; //backward compatibility
	}
	
	if(!empty(${"icon_" . $type})){
	$icon = esc_attr( ${"icon_" . $type} ); //@since orbit-1.6-dev-4
	}

	/**
	 * Custom Icon
	 *
	 * $custom_icon is HTML code inputted from the user
	 * Visual composer uses base64_encode for a users HTML-input
	 * We're using base64_decode() to "un-encode" a users HTML input
	 * nothing malicious going on here :)
	 *
	 * @since Orbit 1.2
	 */
    if(!empty($custom_icon)){
    //custom icon will overwrite icon if there is any html entered by customer.
    	$icon_output = rawurldecode( base64_decode( strip_tags( $custom_icon) ) );
        $icon = '';
    }
    
    if(!empty($custom_icon_upload)){
    //custom icon upload will overwrite the above custom icon and icon.
        $uploaded_custom_icon_image = wp_get_attachment_image($custom_icon_upload,'full');
        $icon_output = '<span class="orbit-custom-icon-img">'.$uploaded_custom_icon_image.'</span>';
        $icon = '';
    }    
	
	$output = '<div class="tab-pane fade '.$active.'" id="'.$tab_content_id.'" data-title="'.$nav_tab_title.'" data-icon="'.$icon.'">'.$icon_output.$content.'</div>';

	 return $output;
}// END shortcode

/*--------------------------------------------------------------
Orbit - Tab 2
--------------------------------------------------------------*/ 
public function render_orbit_tab_2( $atts, $content = null ) {      
	extract(shortcode_atts(array(
	'tab_id'           => '',
	'color_scheme'     => '#3b86c4',
	'custom_css_class' => '',
	'unique_id'        => ''
	), $atts));

	// Visual Composer helper function
	if(function_exists('wpb_js_remove_wpautop')){ $content = wpb_js_remove_wpautop($content, false);
	} else { $content = do_shortcode(shortcode_unautop($content)); }

	// Ensure HTML tags get closed
	$content = force_balance_tags($content);

	// Generate Unique ID to be used for styling
	$unique_id = orbit_truethemes_random();

	// Generate Random ID
	$tab_id = orbit_truethemes_random();

	$output = '<div class="orbit-tabs-style-2 '.$custom_css_class.' '.$unique_id.'" id="tab2-'.$tab_id.'"><ul class="'.$tab_id.' orbit-nav nav-tabs" role="tablist"></ul><div class="orbit-tab-content">'.$content.'</div></div>';

	// javascript for auto generating the tab navigation <li>	
	$output.="<script type='text/javascript'>jQuery(document).ready(function(){truethemes_orbit_tabs_2('{$tab_id}');});</script>";

	// Dynamic CSS Function
	$style_code ='.orbit-tabs-style-2.'.$unique_id.' .nav-tabs > li.active > a:active,
	.orbit-tabs-style-2.'.$unique_id.' .nav-tabs > li.active > a:focus,
	.orbit-tabs-style-2.'.$unique_id.' .nav-tabs > li.active > a {color: '.$color_scheme.' !important;}';

	//must be called before return $output
	$this->orbit_dynamic_embed_css($style_code);

	return $output;
	}// END shortcode

/*--------------------------------------------------------------
Orbit - Tab 2 [content]
--------------------------------------------------------------*/
public function render_orbit_tab_2_content( $atts, $content = null ) {      
	extract(shortcode_atts(array(
	'tab_content_id'         => '',
	'tab_active'             => '',
	'nav_tab_title'          => 'New Tab'
	), $atts));
	 	 
	// Visual Composer helper function
	if(function_exists('wpb_js_remove_wpautop')){ $content = wpb_js_remove_wpautop($content, false);
    } else { $content = do_shortcode(shortcode_unautop($content)); }
	
	// Ensure HTML tags get closed
	$content = force_balance_tags($content);
	
	if($tab_active == 'yes'){ $active = "in active"; } else { $active = ''; }
	
	// Generate Random ID
	$tab_content_id = orbit_truethemes_random();
	
	$output = '<div class="fade tab-pane '.$active.'" id="'.$tab_content_id.'" data-title="'.$nav_tab_title.'">'.$content.'</div>';

	return $output;
}// END shortcode

/*--------------------------------------------------------------
Orbit - Tab 3
--------------------------------------------------------------*/ 
public function render_orbit_tab_3( $atts, $content = null ) {      
	extract(shortcode_atts(array(
	'tab_id'             => '',
	'custom_css_class'   => '',
	'unique_id'          => '',
	'disable_icon'       => '',
	'menu_bg_color'      => '#f6f6f6',
	'link_color'         => '#666',
	'link_color_hover'   => '#333',
	'link_color_active'  => '#099',
	'tab_bgcolor_hover'  => '#eee',
	'tab_bgcolor_active' => '#fff',
	'tab_border_color'   => '#e1e1e1'
	), $atts));

	// Visual Composer helper function
	if(function_exists('wpb_js_remove_wpautop')){ $content = wpb_js_remove_wpautop($content, false);
	} else { $content = do_shortcode(shortcode_unautop($content)); }

	// Ensure HTML tags get closed
	$content = force_balance_tags($content);

	// Generate Unique ID to be used for styling
	$unique_id = orbit_truethemes_random();

	// Generate Random ID
	$tab_id = orbit_truethemes_random();

	$output = '<div class="tt-tabcolor-'.$color_scheme.' orbit-tabs-style-3 '.$custom_css_class.' '.$unique_id.'" id="'.$tab_id.'">
	<ul class="'.$tab_id.' orbit-nav nav-pills nav-stacked" role="tablist"></ul><div class="orbit-tab-content">'.$content.'</div></div>';

	/**
	* javascript for auto generating the tab navigation <li>, 
	* because cannot use PHP due to the way html is nested.
	* Javascript function orbit_built_tab_nav() is found in orbit.js
	*/
	$output.= '<script type="text/javascript">jQuery(document).ready(function(){truethemes_orbit_tabs("'.$tab_id.'");});</script>';

	// Dynamic CSS Function
	$style_code ='.orbit-tabs-style-3.'.$unique_id.' .nav-stacked > li.active > a,
	.orbit-tabs-style-3.'.$unique_id.' .nav-stacked > li.active > a:hover,
	.orbit-tabs-style-3.'.$unique_id.' .nav-stacked > li.active > a:focus,
	.orbit-tabs-style-3.'.$unique_id.' .nav-stacked > li.active > .fa {color: '.$link_color_active.';background-color: '.$tab_bgcolor_active.';}
	.orbit-tabs-style-3.'.$unique_id.' .nav-stacked > li > a:hover{ background:'.$tab_bgcolor_hover.'; color:'.$link_color_hover.'; }
	.orbit-tabs-style-3.'.$unique_id.' .nav-stacked li {border-color: '.$tab_border_color.';}
	.orbit-tabs-style-3.'.$unique_id.' .nav-stacked {background-color:'.$menu_bg_color.';}';

	/*
	* disable icon css
	* @since 1.2
	*/	
	if($disable_icon == 'yes'){
	$style_code.='.orbit-tabs-style-3 .fa{display:none !important;}.orbit-tabs-style-3 .nav-stacked > li > a {padding-left: 25px;}';
	}

	//must be called before return $output
	$this->orbit_dynamic_embed_css($style_code);

	 return $output;
}// END shortcode

/*--------------------------------------------------------------
Orbit - Tab 3 [content]
--------------------------------------------------------------*/
public function render_orbit_tab_3_content( $atts, $content = null ) {      
	extract(shortcode_atts(array(
	'tab_content_id' => '',
	'tab_active'     => '',
	'nav_tab_title'  => 'New Tab',
	'icon'           => '',
	'custom_icon'    => '',
	'custom_icon_upload' => '',
	'type' => '',
	'icon_fontawesome' => '',
	'icon_openiconic' => '',
	'icon_typicons' => '',
	'icon_entypoicons' => '',
	'icon_linecons' => '',
	'icon_entypo' => '',
	), $atts));

	// Enqueue needed icon font.
	vc_icon_element_fonts_enqueue( $type );
		 	 
	// Visual Composer helper function
	if(function_exists('wpb_js_remove_wpautop')){ $content = wpb_js_remove_wpautop($content, false);
    } else { $content = do_shortcode(shortcode_unautop($content)); }
	
	// Ensure HTML tags get closed
	$content = force_balance_tags($content);
	
	if($tab_active == 'yes'){ $active = "in active"; } else { $active = ''; }
	
	// Generate Random ID
	$tab_content_id = orbit_truethemes_random();


	//added by denzel @since orbit-1.6-dev-5 this is default icon
	if(empty($icon) && empty(${"icon_" . $type})){
	${"icon_" . $type} = 'fa fa-adjust';
	}
			
	if($icon != ''){
	$icon = $icon;
	}
	
	if(!empty(${"icon_" . $type})){
	$icon = esc_attr( ${"icon_" . $type} );
	}

	/**
	 * Custom Icon
	 *
	 * $custom_icon is HTML code inputted from the user
	 * Visual composer uses base64_encode for a users HTML-input
	 * We're using base64_decode() to "un-encode" a users HTML input
	 * nothing malicious going on here :)
	 *
	 * @since Orbit 1.2
	 */
    if(!empty($custom_icon)){
    //custom icon will overwrite icon if there is any html entered by customer.
    	$icon_output = rawurldecode( base64_decode( strip_tags( $custom_icon) ) );
        $icon = '';
    }
    
    
    if(!empty($custom_icon_upload)){
    //custom icon upload will overwrite the above custom icon and icon.
        $uploaded_custom_icon_image = wp_get_attachment_image($custom_icon_upload,'full');
        $icon_output = '<span class="orbit-custom-icon-img">'.$uploaded_custom_icon_image.'</span>';
        $icon = '';
    }    
    
	
	$output = '<div class="tab-pane fade '.$active.'" id="'.$tab_content_id.'" data-title="'.$nav_tab_title.'" data-icon="'.$icon.'">'.$icon_output.$content.'</div>';

	return $output;
}// END shortcode

/*--------------------------------------------------------------
Orbit - Testimonial 1
--------------------------------------------------------------*/ 
public function render_orbit_testimonial_1( $atts, $content = null ) {      
	extract(shortcode_atts(array(
	'custom_css_class' => ''
	), $atts));
	 	 
	// Visual Composer helper function
	if(function_exists('wpb_js_remove_wpautop')){ $content = wpb_js_remove_wpautop($content, false);
    } else { $content = do_shortcode(shortcode_unautop($content)); }
	
	$output = '<div class="orbit-testimonial-1 '.$custom_css_class.'"><div class="orbit-testimonial-1-flexslider"><ul class="slides clearfix">'.$content.'</ul></div></div>';

	 return $output;
}// END shortcode

/*--------------------------------------------------------------
Orbit - Testimonial 1 [slide]
--------------------------------------------------------------*/
public function render_orbit_testimonial_1_slide( $atts, $content = null ) {      
	extract(shortcode_atts(array(
	'banner_image_attachment_id'          => '',
	'client_headshot_image_attachment_id' => '',
	'client_name'                         => '',
	'testimonial_text'                    => '',
	'banner_image_html'                   => '',
	'client_headshot_image_html'          => '',
	), $atts));
	 	 
	// Visual Composer helper function
	if(function_exists('wpb_js_remove_wpautop')){ $content = wpb_js_remove_wpautop($content, false);
    } else { $content = do_shortcode(shortcode_unautop($content)); }
	
	// Ensure HTML tags get closed
	$content = force_balance_tags($content);
	
	// Let WordPress build the whole img tag so it includes ALT and other attributes.
	// Using large size here to prevent server crash from full-size images
	$banner_image_html = wp_get_attachment_image( $banner_image_attachment_id,'large' );	
	
	//see add_image_size 'testimonial-user' declared on the very top of this page.
	$client_headshot_image_html = wp_get_attachment_image( $client_headshot_image_attachment_id,'testimonial-user' );				
	
	$output = '<li>'.$banner_image_html.'<div class="orbit-slider-content"><div class="user-section">'.$client_headshot_image_html.'<p><strong>'.$client_name.'</strong></p></div><p>'.$testimonial_text.'</p></div></li>';

	 return $output;
}// END shortcode

/*--------------------------------------------------------------
Orbit - Testimonial 2
--------------------------------------------------------------*/
public function render_orbit_testimonial_2( $atts, $content = null ) {      
 	 extract(shortcode_atts(array(
 	 'custom_css_class'     => ''
 	 ), $atts));
	 	 
	// Visual Composer helper function
	if(function_exists('wpb_js_remove_wpautop')){ $content = wpb_js_remove_wpautop($content, false);
    } else { $content = do_shortcode(shortcode_unautop($content)); }
	
	$output = '<div class="orbit-testimonial-2 '.$custom_css_class.'"><div class="orbit-testimonial-2-flexslider"><ul class="slides clearfix">'.$content.'</ul></div></div>';

	 return $output;
}// END shortcode

/*--------------------------------------------------------------
Orbit - Testimonial 2 [slide]
--------------------------------------------------------------*/
public function render_orbit_testimonial_2_slide( $atts, $content = null ) {      
	extract(shortcode_atts(array(
	'testimonial_bg_color'                => '#372f2b',
	'client_headshot_image_attachment_id' => '',
	'testimonial_text'                    => '',
	'client_headshot_image_src'           => '',
	), $atts));
	
	//see add_image_size 'testimonial-user' declared on the very top of this page.
	$client_headshot_image_src = wp_get_attachment_image_src( $client_headshot_image_attachment_id,'testimonial-user-2' );				
	
	$output = '<li data-thumb="'.$client_headshot_image_src[0].'"><div class="testimonial-text" style="background-color:'.$testimonial_bg_color.' !important;"><p><span>'.$testimonial_text.'</span></p></div></li>';

	 return $output;
}// END shortcode

/*--------------------------------------------------------------
Orbit - Google Map
--------------------------------------------------------------*/
public function render_orbit_google_map( $atts, $content = null ) {      
	extract(shortcode_atts(array(
        'google_map_api_key'                           => '',
        'google_map_width'                             => '600',
        'google_map_height'                            => '500',
        'google_map_center_lat'                        => '37.803003',
        'google_map_center_long'                       => '-122.277719',
        'google_map_zoom'                              => '13',
        'google_map_center_marker'                     => '',
        'google_map_center_marker_custom_icon'         => '',
        'google_map_center_marker_info_window_content' => '',
	), $atts));

    $id = "orbit_map_" . rand(1000, 9999);
	
	$output = sprintf('<div id="%s" style="width: %dpx; height: %dpx"></div>', $id, $google_map_width, $google_map_height);

    if (!defined("GOOGLE_MAPS_SCRIPT_ADDED")) {
        define("GOOGLE_MAPS_SCRIPT_ADDED", true);
        wp_enqueue_script( 'google_maps', "https://maps.googleapis.com/maps/api/js?key=" . $google_map_api_key, '1.0', array(), $infooter = true );
    }

    ob_start();
    ?>
    <script type="text/javascript">
    function mapIt_<?php echo $id; ?>() {
        var center = {
                lat: <?php echo $google_map_center_lat; ?>,
                lng: <?php echo $google_map_center_long; ?>
            },
             map_options = {
                center: center,
                zoom: <?php echo $google_map_zoom; ?>
            },
            map = new google.maps.Map(document.getElementById("<?php echo $id; ?>"), map_options);

        if ("<?php echo $google_map_center_marker; ?>") {
            var marker_options = {
                    position: center,
                    map: map
                },
                info_window,
                center_marker;

            if ("<?php echo $google_map_center_marker_custom_icon; ?>") {
                marker_options.icon = "<?php echo $google_map_center_marker_custom_icon; ?>";
            }

            center_marker = new google.maps.Marker(marker_options);

            if ("<?php echo $google_map_center_marker_info_window_content; ?>") {
                info_window = new google.maps.InfoWindow({
                    content: '<?php echo $google_map_center_marker_info_window_content; ?>'
                });

                google.maps.event.addListener(center_marker, 'click', function () {
                    info_window.open(map, center_marker);
                });
            }
        }
    }
    jQuery(window).load(function () {
        mapIt_<?php echo $id; ?>();
    });
    </script>
    <?php
    $js_output = ob_get_contents();
    ob_end_clean();

	return $output . $js_output;
}// END shortcode

/*
 * Disable elements below
 * until further development
 *
/*--------------------------------------------------------------
Orbit - Grow Boxes
--------------------------------------------------------------
public function render_orbit_grow_boxes( $atts, $content = null ) {
 	 extract(shortcode_atts(array(
 	 'custom_css_class'     => ''
 	 ), $atts));
	 	 
	// Visual Composer helper function
	if(function_exists('wpb_js_remove_wpautop')){ $content = wpb_js_remove_wpautop($content, false);
    } else { $content = do_shortcode(shortcode_unautop($content)); }
	
	$output = '<div class="growboxes '.$custom_css_class.'">'.$content.'</div>';

	 return $output;
}// END shortcode

/*--------------------------------------------------------------
Orbit - Grow Boxes
--------------------------------------------------------------
public function render_orbit_single_grow_box( $atts, $content = null ) {
 	 extract(shortcode_atts(array(
 	 'orbit_grow_box_closed_content'     => '',
 	 'orbit_grow_box_open_content'       => '',
     'orbit_grow_box_custom_css_class'   => '',
     'orbit_grow_box_width'              => 200,
     'orbit_grow_box_height'             => 200,
     'orbit_grow_box_open_width'         => 400,
     'orbit_grow_box_open_height'        => 400
 	 ), $atts));

     $data = ' data-width="%d" data-height="%d" data-open_width="%d" data-open_height="%d" ';
     $data = sprintf($data, $orbit_grow_box_width, $orbit_grow_box_height, $orbit_grow_box_open_width, $orbit_grow_box_open_height);
	 	 
	
	$output = '<div class="growbox '
        . $orbit_grow_box_custom_css_class . '"'
        . $data . '><div class="growbox_closed_face">'
        . urldecode(base64_decode($orbit_grow_box_closed_content)) . '</div><div class="growbox_open_content">'
        . $orbit_grow_box_open_content . '</div></div>';

	 return $output;
}// END shortcode
*/


// Enqueue scripts and styles for the front end
public function orbit_enqueue_script() {
	//JavaScript
	wp_enqueue_script( 'masonry', plugins_url('js/masonry.js', __FILE__), '1.0', array(), $infooter = true );
	wp_enqueue_script( 'orbit-bootstrap-js', plugins_url('js/bootstrap.min.js', __FILE__), '1.0', array(), $infooter = true );
	wp_enqueue_script( 'appear', plugins_url('js/appear.min.js', __FILE__), '3.2', array('jquery'), $infooter = true );
	wp_enqueue_script( 'waypoints', plugins_url('js/waypoints.min.js', __FILE__), '2.0.3', array('jquery'), $infooter = true );
	wp_enqueue_script( 'easyCharts', plugins_url('js/easy-pie-chart.min.js', __FILE__), '1.0.1', array('jquery'), $infooter = true );
	wp_enqueue_script( 'flexslider', plugins_url('js/jquery.flexslider.js', __FILE__), '2.2.2', array('jquery'), $infooter = true );
	wp_enqueue_script( 'prettyPhoto', plugins_url('js/jquery.prettyPhoto.js', __FILE__), array(), '3.1.5', $infooter = true );
	wp_enqueue_script( 'orbit', plugins_url('js/orbit.js', __FILE__), '1.0', array('jquery'), $infooter = true );

	//CSS
	wp_enqueue_style( 'orbit', plugins_url('css/orbit.css', __FILE__) );
	wp_enqueue_style( 'orbit-bootstrap', plugins_url('css/orbit-bootstrap.css', __FILE__) );
	wp_enqueue_style( 'font-awesome', plugins_url('css/font-awesome.min.css', __FILE__) );
}

/*
 * Disable element below
 * until further development
 *
public function orbit_enqueue_admin_script() {
	wp_enqueue_script( 'google_maps', "https://maps.googleapis.com/maps/api/js?key=", '1.0', array(), $infooter = true );
}
*/

// Display a notice upon Orbit-Activation if plugin is activated but Visual Composer is not
public function showVcVersionNotice() {
    $plugin_data = get_plugin_data(__FILE__);
    echo '
    <div class="updated">
      <p>'.sprintf(__('Welcome. <strong>%s</strong> requires the <strong><a href="http://codecanyon.net/item/visual-composer-page-builder-for-wordpress/242431?ref=TrueThemes" target="_blank">Visual Composer Plugin</a></strong> to be installed and activated on your site.', 'tt_orbit'), $plugin_data['Name']).'</p>
    </div>';
}


}
// Finally initialize code
new OrbitVCExtendAddonClass();

function Orbit_extend_vc_class(){

	if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
	    class WPBakeryShortCode_orbit_testimonial_1 extends WPBakeryShortCodesContainer {
	    }
	    class WPBakeryShortCode_orbit_testimonial_2 extends WPBakeryShortCodesContainer {
	    }
	    class WPBakeryShortCode_orbit_tab_1 extends WPBakeryShortCodesContainer {
	    }
	    class WPBakeryShortCode_orbit_tab_2 extends WPBakeryShortCodesContainer {
	    }
	    class WPBakeryShortCode_orbit_tab_3 extends WPBakeryShortCodesContainer {
	    }	
	    class WPBakeryShortCode_orbit_accordion extends WPBakeryShortCodesContainer {
	    }      
        // Grow Boxes class
	    //class WPBakeryShortCode_orbit_grow_boxes extends WPBakeryShortCodesContainer {
	    //}      
	}

	if ( class_exists( 'WPBakeryShortCode' ) ) {
	    class WPBakeryShortCode_orbit_testimonial_1_slide extends WPBakeryShortCode {
	    }
	    class WPBakeryShortCode_orbit_testimonial_2_slide extends WPBakeryShortCode {
	    }
	    class WPBakeryShortCode_orbit_tab_1_content extends WPBakeryShortCode {
	    }	
	    class WPBakeryShortCode_orbit_tab_2_content extends WPBakeryShortCode {
	    }	   
	    class WPBakeryShortCode_orbit_tab_3_content extends WPBakeryShortCode {
	    }	
	    class WPBakeryShortCode_orbit_accordion_panel extends WPBakeryShortCode {
	    }          	    
        // Single grow box class
	    //class WPBakeryShortCode_orbit_single_grow_box extends WPBakeryShortCode {
	    //}          	    
	}		   		  		

}
add_action('after_setup_theme','Orbit_extend_vc_class');

// AJAX stuff for getting the url of an image based on its wp id.
add_action('admin_footer', 'Orbit_print_get_image_URL_function');

function Orbit_print_get_image_URL_function() {
    ?>
    <script>
    window.Orbit_get_image_url = function (id, cb) {
        var data = {
            'action': 'Orbit_get_image_url',
            'image_id': id
        };
        jQuery.get(ajaxurl, data, function(response) {
            cb(response);
        });
    }
    </script>
    <?php
}
add_action('wp_ajax_Orbit_get_image_url', 'Orbit_get_image_url_callback');
function Orbit_get_image_url_callback() {
    if (!isset($_GET['image_id'])) {
        echo "";
        return;
    }
    $id = $_GET['image_id'];
    $size = "large";
    $url = wp_get_attachment_image_src($id, $size);
    $url = $url[0];
    echo $url;
    die();
}
