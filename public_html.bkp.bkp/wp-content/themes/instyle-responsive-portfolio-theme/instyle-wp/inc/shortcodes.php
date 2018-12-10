<?php
/* Tabs Shortcode */
function shortcode_tabs($atts, $content = null, $code) {
	extract(shortcode_atts(array(
	), $atts));
	$content = remove_invalid_tags($content, array('p'));
	$content = remove_invalid_tags($content, array('br'));
	if (!preg_match_all("/(.?)\[(tab)\b(.*?)(?:(\/))?\](?:(.+?)\[\/tab\])?(.?)/s", $content, $matches)) {
		return do_shortcode($content);
	} else {
		for($i = 0; $i < count($matches[0]); $i++) {
			$matches[3][$i] = shortcode_parse_atts($matches[3][$i]);
		}
		$output = '<ul class="tabs">';
		
		for($i = 0; $i < count($matches[0]); $i++) {
			if($i == 0){$first = 'first';}else{$first = '';}
			$output .= '<li class="'.$first.'"><a title="' . $matches[3][$i]['title'] .  '" href="#tab-' . $i . '">' . $matches[3][$i]['title'] . '</a></li>';
		}
		$output .= '</ul>';
		for($i = 0; $i < count($matches[0]); $i++) {
			$output .= '<div id="tab-' . $i . '">' . do_shortcode(trim($matches[5][$i])) .'</div>';
		}
		
		return '<div class="tabcontainer">' . $output . '</div>';
	}
}
add_shortcode('tabs', 'shortcode_tabs');

/* Toggle Shortcode */
function shortcode_toggle( $atts, $content = null)
{
 extract(shortcode_atts(array(
        'title'      => '',
        ), $atts));
		$content = remove_invalid_tags($content, array('p'));
		$content = remove_invalid_tags($content, array('br'));
   return '<div class="toggle"><div class="title"><span></span>'.$title.'</div><div class="inner">'. do_shortcode($content) . '</div></div>';
}
add_shortcode('toggle', 'shortcode_toggle');

/* Accordion Shortcode */
function shortcode_accordion($atts, $content, $code) {
	extract(shortcode_atts(array(
		'style' => false
	), $atts));
	$output = "";
	$tab ="";
	if (!preg_match_all("/(.?)\[(tab)\b(.*?)(?:(\/))?\](?:(.+?)\[\/tab\])?(.?)/s", $content, $matches)) {
		return do_shortcode($content);
	} else {
		
		for($i = 0; $i < count($matches[0]); $i++) {
			$matches[3][$i] = shortcode_parse_atts($matches[3][$i]);
			if($i == 0){$first = 'class="firsttitle"';}else{$first = '';}
			$output .= '<div class="title"><a title="' .$tab. '" href="#acc-' . $i . '" '.$first.'>' . $matches[3][$i]['title'] . '</a></div><div class="inner" id="acc-' . $i . '">' . do_shortcode(trim($matches[5][$i])) .'</div>';
		}
		$output = remove_invalid_tags($output, array('p'));
		$output = remove_invalid_tags($output, array('br'));
		return '<div class="accordion">' . $output . '</div>';
		
	}
}
add_shortcode('accordion', 'shortcode_accordion');

/* Highlight Shortcode */

function highlight_blue( $atts, $content = null ) {
    extract(shortcode_atts(array(
       	
    ), $atts));

	$out = "<span class='hblue' >" .$content. "</span>";
    return $out;
}
add_shortcode('highlight_blue', 'highlight_blue');

function highlight_yellow( $atts, $content = null ) {
    extract(shortcode_atts(array(
       	
    ), $atts));

	$out = "<span class='hyellow' >" .$content. "</span>";
    return $out;
}
add_shortcode('highlight_yellow', 'highlight_yellow');


/* Dropcap Shortcode */

function dropcap( $atts, $content = null ) {
    extract(shortcode_atts(array(
       	
    ), $atts));

	$out = "<span class='dropcap' >" .$content. "</span>";
    return $out;
}
add_shortcode('dropcap', 'dropcap');

/* Button Shortcode */

function Instyle_button( $atts, $content = null ) {
    extract(shortcode_atts(array(
        'link'      => '#',
		'size'      => '',
		'color'      => 'default',
		'align'      => 'alignleft'
    ), $atts));

	$out = "<button class=\"btn ".$color." ".$size." ".$align."\" href=\"" .$link. "\">" .do_shortcode($content). "</button>";
    
    return $out;
}
add_shortcode('button', 'Instyle_button');
/* Pullquote Shortcode */
function Instyle_pullquote( $atts, $content = null ) {
   return '<blockquote><p>' . do_shortcode($content) . '</p></blockquote>';
}
add_shortcode('quote', 'Instyle_pullquote');
function Instyle_pullquote_left( $atts, $content = null ) {
   return '<blockquote class="pullquote_left"><p>' . do_shortcode($content) . '</p></blockquote>';
}
add_shortcode('pullquote_left', 'Instyle_pullquote_left');

function Instyle_pullquote_right( $atts, $content = null ) {
   return '<blockquote class="pullquote_right"><p>' . do_shortcode($content) . '</p></blockquote>';
}
add_shortcode('pullquote_right', 'Instyle_pullquote_right');

/* Callout Shortcode */

function callout( $atts, $content = null ) {
    extract(shortcode_atts(array(
		'color'      => '#edebec'
    ), $atts));
	$content = remove_invalid_tags($content, array('p'));
	$content = remove_invalid_tags($content, array('br'));
	$out = "<div class='callout' style='background-color:".$color."'>" . do_shortcode($content) . "</div>";
    return $out;
}
add_shortcode('callout', 'callout');

/* Video Shortcode */
function Instyle_video($atts, $content=null) {
	extract(shortcode_atts(array(
		'type' 	=> '',
		'id' 	=> '',
		'width' 	=> false,
		'height' 	=> false,
		'autoplay' 	=> '',
		'hd' 	=> '',
	), $atts));
	$content = remove_invalid_tags($content, array('p'));
	$content = remove_invalid_tags($content, array('br'));
	
	if ($height && !$width) $width = intval($height * 16 / 9);
	if (!$height && $width) $height = intval($width * 9 / 16) + 25;
	if (!$height && !$width){
		$height = 320;
		$width = 480;
	}
	
	$link = $link?' href="'.$link.'"':'';
	
	$autoplay = ($autoplay == 'yes' ? '1' : false);
	$hd = ($hd == 'yes' ? '1' : false);
	
	
	if($type == "vimeo") $return = "<div class='video_frame'><iframe src='http://player.vimeo.com/video/$id?autoplay=$autoplay' width='$width' height='$height' class='iframe'></iframe></div>";
	else if($type == "youtube") $return = "<div class='video_frame'><iframe src='http://www.youtube.com/embed/$id?hd=$hd' width='$width' height='$height' class='iframe'></iframe></div>";
	if (!empty($id)){
		return $return;
	}
}
add_shortcode('video', 'Instyle_video');

/* Columns Shortcode */
function shortcode_column($atts, $content = null, $code) {
	$content = remove_invalid_tags($content, array('p'));
	$content = remove_invalid_tags($content, array('br'));
	return '<div class="'.$code.'">' .  do_shortcode($content) . '</div>';
}
function shortcode_column_last($atts, $content = null, $code) {
	$content = remove_invalid_tags($content, array('p'));
	$content = remove_invalid_tags($content, array('br'));
	return '<div class="'.str_replace('-last','',$code).' last">' .  do_shortcode(trim($content)) . '</div><div class="clearfix"></div>';
}
add_shortcode('one-half-last', 'shortcode_column_last');
add_shortcode('one-third-last', 'shortcode_column_last');
add_shortcode('one-forth-last', 'shortcode_column_last');
add_shortcode('one-fifth-last', 'shortcode_column_last');

add_shortcode('two-third-last', 'shortcode_column_last');
add_shortcode('three-forth-last', 'shortcode_column_last');
add_shortcode('two-fifth-last', 'shortcode_column_last');
add_shortcode('three-fifth-last', 'shortcode_column_last');
add_shortcode('four-fifth-last', 'shortcode_column_last');

add_shortcode('one-half', 'shortcode_column');
add_shortcode('one-third', 'shortcode_column');
add_shortcode('one-forth', 'shortcode_column');
add_shortcode('one-fifth', 'shortcode_column');

add_shortcode('two-third', 'shortcode_column');
add_shortcode('three-forth', 'shortcode_column');
add_shortcode('two-fifth', 'shortcode_column');
add_shortcode('three-fifth', 'shortcode_column');
add_shortcode('four-fifth', 'shortcode_column');

/* Box Shortcode */

function Instyle_warning_box( $atts, $content = null ) {
	$content = remove_invalid_tags($content, array('p'));
	$content = remove_invalid_tags($content, array('br'));
   return '<div class="alert-message block-message warning"><p>' . do_shortcode($content) . '</p></div>';
}
add_shortcode('warning_box', 'Instyle_warning_box');


function Instyle_error_box( $atts, $content = null ) {
	$content = remove_invalid_tags($content, array('p'));
	$content = remove_invalid_tags($content, array('br'));
   return '<div class="alert-message block-message error"><p>' . do_shortcode($content) . '</p></div>';
}
add_shortcode('error_box', 'Instyle_error_box');


function Instyle_info_box( $atts, $content = null ) {
	$content = remove_invalid_tags($content, array('p'));
	$content = remove_invalid_tags($content, array('br'));
   return '<div class="alert-message block-message info"><p>' . do_shortcode($content) . '</p></div>';
}
add_shortcode('info_box', 'Instyle_info_box');


function Instyle_success_box( $atts, $content = null ) {
	$content = remove_invalid_tags($content, array('p'));
	$content = remove_invalid_tags($content, array('br'));
   return '<div class="alert-message block-message success"><p>' . do_shortcode($content) . '</p></div>';
}
add_shortcode('success_box', 'Instyle_success_box');

/* Alert Shortcode */

function Instyle_warning_alert( $atts, $content = null ) {
	$content = remove_invalid_tags($content, array('p'));
	$content = remove_invalid_tags($content, array('br'));
   return '<div class="alert-message warning"><a class="close" href="#">×</a><p>' . do_shortcode($content) . '</p></div>';
}
add_shortcode('warning_alert', 'Instyle_warning_alert');


function Instyle_error_alert( $atts, $content = null ) {
	$content = remove_invalid_tags($content, array('p'));
	$content = remove_invalid_tags($content, array('br'));
   return '<div class="alert-message error"><a class="close" href="#">×</a><p>' . do_shortcode($content) . '</p></div>';
}
add_shortcode('error_alert', 'Instyle_error_alert');


function Instyle_info_alert( $atts, $content = null ) {
	$content = remove_invalid_tags($content, array('p'));
	$content = remove_invalid_tags($content, array('br'));
   return '<div class="alert-message info"><a class="close" href="#">×</a><p>' . do_shortcode($content) . '</p></div>';
}
add_shortcode('info_alert', 'Instyle_info_alert');


function Instyle_success_alert( $atts, $content = null ) {
	$content = remove_invalid_tags($content, array('p'));
	$content = remove_invalid_tags($content, array('br'));
   return '<div class="alert-message success"><a class="close" href="#">×</a><p>' . do_shortcode($content) . '</p></div>';
}
add_shortcode('success_alert', 'Instyle_success_alert');

/* Divider Shortcodes */
function Instyle_divider( $atts, $content = null ) {
   return '<div class="divider"></div>';
}
add_shortcode('divider', 'Instyle_divider');

function Instyle_dividertop( $atts, $content = null ) {
   return '<div class="divider toplink"><a href="#bodytop">Top</a></div>';
}
add_shortcode('dividertop', 'Instyle_dividertop');


/* Google Chart Shortcodes */
function theme_shortcode_chart( $atts ) {
	extract(shortcode_atts(array(
	    'data' => '',
	    'colors' => '',
		'size' => '720x300',
	    'bg' => 'bg,s,ffffff',
	    'title' => '',
	    'labels' => '',
	    'advanced' => '',
	    'type' => 'pie'
	), $atts));
 
	switch ($type) {
		case 'line' :
			$charttype = 'lc'; break;
		case 'xyline' :
			$charttype = 'lxy'; break;
		case 'sparkline' :
			$charttype = 'ls'; break;
		case 'meter' :
			$charttype = 'gom'; break;
		case 'scatter' :
			$charttype = 's'; break;
		case 'venn' :
			$charttype = 'v'; break;
		case 'pie' :
			$charttype = 'p3'; break;
		case 'pie2d' :
			$charttype = 'p'; break;
		default :
			$charttype = $type;
		break;
	}
 	$string = "";
	if ($title) $string .= '&chtt='.$title.'';
	if ($labels) $string .= '&chl='.$labels.'';
	if ($colors) $string .= '&chco='.$colors.'';
	$string .= '&chs='.$size.'';
	$string .= '&chd=t:'.$data.'';
	$string .= '&chf='.$bg.'';
 
	return '<img title="'.$title.'" src="http://chart.apis.google.com/chart?cht='.$charttype.''.$string.$advanced.'" alt="'.$title.'" />';
}
add_shortcode('chart', 'theme_shortcode_chart');

/* Google Map Shortcodes */
function theme_shortcode_googlemap($atts, $content = null, $code) {
	extract(shortcode_atts(array(
		"width" => false,
		"height" => '400',
		"address" => '',
		"latitude" => 0,
		"longitude" => 0,
		"zoom" => 8,
		"html" => '',
		"controls" => 'true',
		"maptype" => 'TERRAIN', // 'HYBRID', 'SATELLITE', 'ROADMAP' or 'TERRAIN'
		"marker" => 'true',
		'align' => false,
	), $atts));
	
	if($width && is_numeric($width)){
		$width = 'width:'.$width.'px;';
	}else{
		$width = '';
	}
	if($height && is_numeric($height)){
		$height = 'height:'.$height.'px';
	}else{
		$height = '';
	}
	
	$align = $align?' align'.$align:'';
	$id = rand(100,1000);
	if($marker != 'false'){
		return <<<HTML
<div id="google_map_{$id}" class="google_map{$align}" style="{$width}{$height}"></div>
<script type="text/javascript">
jQuery(document).ready(function($) {
		jQuery("#google_map_{$id}").gMap({
			latitude: {$latitude},
			longitude: {$longitude},
			maptype: '{$maptype}', // 'HYBRID', 'SATELLITE', 'ROADMAP' or 'TERRAIN'
			zoom: {$zoom},
			markers: [
				{
					latitude: {$latitude},
					longitude: {$longitude},
					address: "{$address}",
					popup: true,
					html: "{$html}"
				}
			],
			controls: {
				panControl: true,
				zoomControl: {$controls},
				mapTypeControl: {$controls},
				scaleControl: {$controls},
				streetViewControl: false,
				overviewMapControl: false
			}
		});
});
</script>
HTML;
	}else{
return <<<HTML
<div id="google_map_{$id}" class="google_map{$align}" style="{$width}{$height}"></div>
[raw]
<script type="text/javascript">
jQuery(document).ready(function($) {
	var tabs = jQuery("#google_map_{$id}").parents('.tabs_container,.mini_tabs_container,.accordion');
	jQuery("#google_map_{$id}").bind('initGmap',function(){
		jQuery("#google_map_{$id}").gMap({
			zoom: {$zoom},
			latitude: {$latitude},
			longitude: {$longitude},
			address: "{$address}",
			controls: {$controls},
			maptype: {$maptype},
			scrollwheel:{$scrollwheel}
		});
		jQuery(this).data("gMapInited",true);
	}).data("gMapInited",false);
	if(tabs.size()!=0){
		tabs.find('ul.tabs,ul.mini_tabs,.accordion').data("tabs").onClick(function(index) {
			this.getCurrentPane().find('.google_map').each(function(){
				if(jQuery(this).data("gMapInited")==false){
					jQuery(this).trigger('initGmap');
				}
			});
		});
	}else{
		jQuery("#google_map_{$id}").trigger('initGmap');
	}
});
</script>
[/raw]
HTML;
	}
}

add_shortcode('gmap','theme_shortcode_googlemap');
?>