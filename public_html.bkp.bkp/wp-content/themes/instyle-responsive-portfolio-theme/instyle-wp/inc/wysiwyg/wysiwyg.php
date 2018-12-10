<?php 

class thb_add_button {
	
	var $pluginname = 'shortcodes';
	var $path = '';
	var $internalVersion = 100;
	
	function thb_add_button()  {
		$this->path = get_template_directory_uri() . '/inc/wysiwyg/';	
		add_filter('tiny_mce_version', array (&$this, 'change_tinymce_version') );
		add_action('init', array (&$this, 'addbuttons') );
	}
	
	function addbuttons() {
		global $page_handle;

				add_filter("mce_external_plugins", array (&$this, 'add_tinymce_plugin' ), 5);
				add_filter('mce_buttons', array (&$this, 'register_button' ), 5);
				add_filter('mce_external_languages', array (&$this, 'add_tinymce_langs_path'));

	}

	
	function register_button($buttons) {
		array_push($buttons, 'separator', $this->pluginname );
		return $buttons;
	}

	
	function add_tinymce_plugin($plugin_array) {
		if(isset($_GET['post_type'])) {
			$post_type_get = $_GET['post_type'];
		}
		$post_id = $_GET['post'];
		$post = get_post($post_id);
		$post_type = $post->post_type;
		$plugin_array[$this->pluginname] =  $this->path . 'editor.js';
		return $plugin_array;
	}
	
	
	function add_tinymce_langs_path($plugin_array) {
		// Load the TinyMCE language file	
		$plugin_array[$this->pluginname] = get_template_directory_uri() . '/inc/wysiwyg/languages.php';
		return $plugin_array;
	}
	
	
	function change_tinymce_version($version) {
			$version = $version + $this->internalVersion;
		return $version;
	}
	
}
if (is_admin()){
	$tinymce_button = new thb_add_button();
}
?>