<?php
require_once TEMPLATEPATH.'/inc/admin/functions.load.php';
$instyle_menu = new ControlPanel();
class ControlPanel {
	var $default_settings;
	function ControlPanel() {
		
		add_action('admin_menu', array(&$this, 'this_theme_menu'));
		add_action('admin_print_styles', array( $this, 'admin_head' ) );
		$setininstyle_array = $this->default_settings;
        if (!is_array(get_option('instyle_theme')))
            add_option('instyle_theme', $this->default_settings);
        
        $this->options = get_option('instyle_theme');
	}

    function this_theme_menu() {
		add_menu_page('Theme Options', 'instyle', 'edit_pages', 'theme-options', array(&$this, 'instyle_panel'), get_template_directory_uri().'/inc/admin/images/thememenuicon.png');
		add_submenu_page('theme-options', 'Theme Options', 'Theme Options', 'edit_pages',  'theme-options', array(&$this, 'instyle_panel'));
		add_submenu_page('theme-options', 'Documentation', 'Documentation', 'edit_pages',  'documentation', array(&$this, 'instyle_panel'));
	}
    function loadpage() {
		require_once TEMPLATEPATH.'/inc/admin/'. $_GET['page'] .'.php';
	}
	function admin_head() {
		// enqueue styles
		wp_enqueue_style( 'option-tree-style', get_template_directory_uri().'/inc/admin/style.css', false, '1.0', 'screen');
		
		// enqueue scripts
		add_thickbox();
		wp_enqueue_script( 'jquery-color-picker', get_template_directory_uri().'/inc/admin/js/jquery.color.picker.js', array('jquery'), '1.0' );
		wp_enqueue_script( 'jquery-option-tree', get_template_directory_uri().'/inc/admin/js/admin.js', array('jquery','media-upload','thickbox','jquery-ui-core','jquery-ui-tabs','jquery-color-picker'), '1.0' );
		
		// remove GD star rating conflicts
		wp_deregister_style( 'gdsr-jquery-ui-core' );
		wp_deregister_style( 'gdsr-jquery-ui-theme' );
	  }
    function instyle_panel() {
		
		$current_themestyle = $this->options["themestyle"];
         if ($_POST['instyle_action'] == 'save') {
			
			$this->options["logo"] = $_POST['logo'];
			$this->options["titlefont"] = $_POST['titlefont'];	
			$this->options["bodyfont"] = $_POST['bodyfont'];
			$this->options["menufont"] = $_POST['menufont'];
			$this->options["customcss"] = stripslashes($_POST['customcss']);
	
			$this->options["bodytextcolor"] = $_POST['bodytextcolor'];
			$this->options["headertextcolor"] = $_POST['headertextcolor'];
			$this->options["footertextcolor"] = $_POST['footertextcolor'];
			$this->options["subfootertextcolor"] = $_POST['subfootertextcolor'];
			
			$this->options["generallink"] = $_POST['generallink'];
			$this->options["generallinkhover"] = $_POST['generallinkhover'];
			$this->options["sidebarlink"] = $_POST['sidebarlink'];
			$this->options["sidebarlinkhover"] = $_POST['sidebarlinkhover'];
			$this->options["menulink"] = $_POST['menulink'];
			$this->options["menulinkhover"] = $_POST['menulinkhover'];
			
			$this->options["portfolioclassiccount"] = $_POST['portfolioclassiccount'];
			$this->options["portfoliorelated"] = $_POST['portfoliorelated'];
			$this->options["portfoliorelatedtitle"] = $_POST['portfoliorelatedtitle'];
			
			$this->options["bitly"] = $_POST['bitly'];
			$this->options["bitly-api"] = $_POST['bitly-api'];
			$this->options["relatedpopular"] = $_POST['relatedpopular'];
			$this->options["relatedpopularno"] = $_POST['relatedpopularno'];
			
			$this->options["mail"] = $_POST['mail'];
			$this->options["facebook"] = $_POST['facebook'];
			$this->options["flickr"] = $_POST['flickr'];
			$this->options["linkedin"] = $_POST['linkedin'];
			$this->options["twitter"] = $_POST['twitter'];
			
			$this->options["ga"] = stripslashes($_POST['ga']);
			$this->options["enable_gmap"] = $_POST['enable_gmap'];
			
        	update_option('instyle_theme', $this->options);
        }
		
		require_once TEMPLATEPATH.'/inc/admin/'. $_GET['page'] .'.php';   
	}
}
?>