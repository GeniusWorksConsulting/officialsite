<?php require_once('config.php');
if ( !is_user_logged_in() || !current_user_can('edit_posts') ) wp_die("You are not allowed to be here"); ?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Shortcodes</title>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>" />
<script language="javascript" type="text/javascript" src="<?php echo get_template_directory_uri() ?>/inc/wysiwyg/wysiwyg.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
<base target="_self" />
</head>
<body onLoad="tinyMCEPopup.executeOnLoad('init();');document.body.style.display='';" style="display: none" id="link">
<form name="shortcode_form" action="#">

	
<div style="height:80px;width:250px;margin:0 auto;padding-top:50px;text-align:center;" class="shortcode_wrap">
<div id="shortcode_panel" class="current" style="height:50px;">
<fieldset style="border:0;width:100%;text-align:center;">
<select id="style_shortcode" name="style_shortcode" style="width:250px; padding: 5px; font-size: 12px;">
<option value="0">Select a Shortcode...</option>
<option value="0" style="font-weight:bold;">Column Shortcodes</option>
     <option value="two_columns">2 Columns</option>
     <option value="three_columns">3 Columns</option>
     <option value="two_third_one_third_columns">2/3 Column + 1/3 Column</option>
     <option value="one_third_two_third_columns">1/3 Column + 2/3 Column</option>
     
<option value="0"> </option>  
<option value="0" style="font-weight:bold;">Typography Elements</option>
	 <option value="pullquote_left">Quote</option>
     <option value="pullquote_left">Pullquote (left)</option>
     <option value="pullquote_right">Pullquote (right)</option>
     <option value="dropcap">Dropcap</option>
     <option value="highlight_blue">Highlight Blue</option>
     <option value="highlight_yellow">Highlight Yellow</option>
     
<option value="0"> </option>
<option value="0" style="font-weight:bold;">Interface Shortcodes</option>
     <option value="toggle">Toggle</option>
     <option value="tabs">Tabs</option>
     <option value="accordion">Accordion</option>

<option value="0"> </option>
<option value="0" style="font-weight:bold;">Video Shortcodes</option>
     <option value="youtube">Youtube</option>
     <option value="vimeo">Vimeo</option>
       
<option value="0"> </option>
<option value="0" style="font-weight:bold;">Alert Boxes</option>
     <option value="warning_alert">Warning Alert</option>
     <option value="error_alert">Error Alert</option>
     <option value="info_alert">Info Alert</option>
     <option value="success_alert">Success Alert</option>
     
<option value="0"> </option>
<option value="0" style="font-weight:bold;">Message Boxes</option>
     <option value="warning_box">Warning Box</option>
     <option value="error_box">Error Box</option>
     <option value="info_box">Info Box</option>
     <option value="success_box">Success Box</option>
     
<option value="0"> </option>
<option value="0" style="font-weight:bold;">Button</option>
     <option value="primary_button">Primary</option>
     <option value="default_button">Default</option>
     <option value="info_button">Info</option>
     <option value="success_button">Success</option>
     <option value="danger_button">Danger</option>
 
<option value="0"> </option>
<option value="0" style="font-weight:bold;">Dividers</option>
     <option value="divider">Basic Divider</option> 
     <option value="dividertop">Basic Divider + Top Link</option>

<option value="0"> </option>
<option value="0" style="font-weight:bold;">Googlecharts</option>
     <option value="chart3dpie">3D Pie Chart</option> 
     <option value="chartline">Line Chart</option>
	 <option value="chartxyline">XY Line Chart</option> 
     <option value="chartscatter">Scatter Chart</option>
     <option value="chartpie">Pie Chart</option> 
</select>
</fieldset>
</div><!-- end shortcode_panel -->

<div style="float:left"><input type="button" id="cancel" name="cancel" value="<?php echo "Close"; ?>" onClick="tinyMCEPopup.close();" /></div>
<div style="float:right"><input type="submit" id="insert" name="insert" value="<?php echo "Insert"; ?>" onClick="embedshortcode();" /></div>

</div><!-- end shortcode_wrap -->




</form>
</body>
</html>
