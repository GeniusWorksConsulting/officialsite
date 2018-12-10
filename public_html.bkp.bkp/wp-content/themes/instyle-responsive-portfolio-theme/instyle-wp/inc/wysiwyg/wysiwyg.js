function embedshortcode() {
	
	var shortcodetext;
	var style = document.getElementById('shortcode_panel');
	

	if (style.className.indexOf('current') != -1) {
		var selected_shortcode = document.getElementById('style_shortcode').value;
		
		
		
// -----------------------------
// 	COLUMN SHORTCODES
// -----------------------------		
if (selected_shortcode == 'two_columns'){
	shortcodetext = "[one-half]<br />Content goes here...<br />[/one-half]<br /><br />[one-half-last]<br />Content goes here...<br />[/one-half-last]<br />";	
}

if (selected_shortcode == 'three_columns'){
	shortcodetext = "[one-third]<br />Content goes here...<br />[/one-third]<br /><br />[one-third]<br />Content goes here...<br />[/one-third]<br /><br />[one-third-last]<br />Content goes here...<br />[/one-third-last]<br />";	
}

if (selected_shortcode == 'two_third_one_third_columns'){
	shortcodetext = "[two-third]<br />Content goes here...<br />[/two-third]<br /><br />[one-third-last]<br />Content goes here...<br />[/one-third-last]<br />";	
}

if (selected_shortcode == 'one_third_two_third_columns'){
	shortcodetext = "[one-third]<br />Content goes here...<br />[/one-third]<br /><br />[two-third-last]<br />Content goes here...<br />[/two-third-last]<br />";	
}

// -----------------------------
// 	LIST SHORTCODES
// -----------------------------
if (selected_shortcode == 'list_tick'){
	shortcodetext = "[list_tick]<li>Item 1...</li><li>Item 2...</li><li>Item 3...</li>[/list_tick]<br />";	
}
if (selected_shortcode == 'list_folder'){
	shortcodetext = "[list_folder]<li>Item 1...</li><li>Item 2...</li><li>Item 3...</li>[/list_folder]<br />";	
}
if (selected_shortcode == 'list_note'){
	shortcodetext = "[list_note]<li>Item 1...</li><li>Item 2...</li><li>Item 3...</li>[/list_note]<br />";	
}
if (selected_shortcode == 'list_star'){
	shortcodetext = "[list_star]<li>Item 1...</li><li>Item 2...</li><li>Item 3...</li>[/list_star]<br />";	
}
if (selected_shortcode == 'list_arrow'){
	shortcodetext = "[list_arrow]<li>Item 1...</li><li>Item 2...</li><li>Item 3...</li>[/list_arrow]<br />";	
}
if (selected_shortcode == 'list_red'){
	shortcodetext = "[list_red]<li>Item 1...</li><li>Item 2...</li><li>Item 3...</li>[/list_red]<br />";	
}
if (selected_shortcode == 'list_black'){
	shortcodetext = "[list_black]<li>Item 1...</li><li>Item 2...</li><li>Item 3...</li>[/list_black]<br />";	
}
if (selected_shortcode == 'list_blue'){
	shortcodetext = "[list_blue]<li>Item 1...</li><li>Item 2...</li><li>Item 3...</li>[/list_blue]<br />";	
}
if (selected_shortcode == 'list_green'){
	shortcodetext = "[list_green]<li>Item 1...</li><li>Item 2...</li><li>Item 3...</li>[/list_green]<br />";	
}


// -----------------------------
// 	ALERT SHORTCODES
// -----------------------------
if (selected_shortcode == 'warning_alert'){
	shortcodetext = "[warning_alert]<strong>Holy guacamole!</strong> Best check yo self, you’re not looking too good.[/warning_alert]";	
}
if (selected_shortcode == 'error_alert'){
	shortcodetext = "[error_alert]<strong>Oh snap!</strong> Change this and that and try again.[/error_alert]";	
}
if (selected_shortcode == 'info_alert'){
	shortcodetext = "[info_alert]<strong>Heads up!</strong> This is an alert that needs your attention, but it’s not a huge priority just yet.[/info_alert]";	
}
if (selected_shortcode == 'success_alert'){
	shortcodetext = "[success_alert]<strong>Well done!</strong> You successfully read this alert message.[/success_alert]";	
}

// -----------------------------
// 	MESSAGE BOX SHORTCODES
// -----------------------------
if (selected_shortcode == 'warning_box'){
	shortcodetext = "[warning_box]<strong>Holy guacamole!</strong> Best check yo self, youre not looking too good. [/warning_box]";	
}
if (selected_shortcode == 'error_box'){
	shortcodetext = "[error_box]<strong>Oh snap!</strong> Change this and that and try again.[/error_box]";	
}
if (selected_shortcode == 'info_box'){
	shortcodetext = "[info_box]<strong>Heads up!</strong> This is an alert that needs your attention, but it’s not a huge priority just yet.[/info_box]";	
}
if (selected_shortcode == 'success_box'){
	shortcodetext = "[success_box]<strong>Well done!</strong> You successfully read this alert message.[/success_box]";	
}

// -----------------------------
// 	INTERFACE SHORTCODES
// -----------------------------
if (selected_shortcode == 'toggle'){
	shortcodetext = "[toggle title=\"Toggle Title\"]<br />Toggle Content<br />[/toggle]<br />";	
}

if (selected_shortcode == 'tabs'){
	shortcodetext = "[tabs]<br /> [tab title=\"tab 1 title\"] tab 1 content [/tab]<br /> [tab title=\"tab 2 title\"] tab 2 content [/tab]<br />[/tabs]<br />";	
}

if (selected_shortcode == 'accordion'){
	shortcodetext = "[accordion]<br /> [tab title=\"tab 1 title\"] tab 1 content [/tab]<br /> [tab title=\"tab 2 title\"] tab 2 content [/tab]<br />[/accordion]<br />";	
}

// -----------------------------
// 	VIDEO SHORTCODES
// -----------------------------
if (selected_shortcode == 'youtube'){
	shortcodetext = "[video type=\"youtube\" id=\"q3NwJF28wjU\" width=\"660\" height=\"400\" hd=\"yes\" autoplay=\"yes\"]";	
}

if (selected_shortcode == 'vimeo'){
	shortcodetext = "[video type=\"vimeo\" id=\"6731927\" width=\"660\" height=\"400\" hd=\"yes\" autoplay=\"no\"]";	
}


// -----------------------------
// 	DIVIDER SHORTCODES
// -----------------------------
if (selected_shortcode == 'divider'){
	shortcodetext = "[divider]";	
}

if (selected_shortcode == 'dividertop'){
	shortcodetext = "[dividertop]";	
}

// -----------------------------
// 	BUTTON SHORTCODES
// -----------------------------
if (selected_shortcode == 'primary_button'){
	shortcodetext = "[button color=\"primary\" size=\"small,large, normal\" link=\"http://www.\" align=\"alignleft\"]Content goes here...[/button]<br />";	
}
if (selected_shortcode == 'default_button'){
	shortcodetext = "[button color=\"default\" size=\"small,large, normal\" link=\"http://www.\" align=\"alignleft\"]Content goes here...[/button]<br />";	
}
if (selected_shortcode == 'info_button'){
	shortcodetext = "[button color=\"info\" size=\"small,large, normal\" link=\"http://www.\" align=\"alignleft\"]Content goes here...[/button]<br />";	
}
if (selected_shortcode == 'success_button'){
	shortcodetext = "[button color=\"success\" size=\"small,large, normal\" link=\"http://www.\" align=\"alignleft\"]Content goes here...[/button]<br />";	
}
if (selected_shortcode == 'danger_button'){
	shortcodetext = "[button color=\"danger\" size=\"small,large, normal\" link=\"http://www.\" align=\"alignleft\"]Content goes here...[/button]<br />";	
}


// -----------------------------
// 	LAYOUT ELEMENTS SHORTCODES
// -----------------------------
if (selected_shortcode == 'quote'){
	shortcodetext = "[quote]Content goes here...<small>Albert Einstein</small>[/quote]<br />";	
}
if (selected_shortcode == 'pullquote_left'){
	shortcodetext = "[pullquote_left]Content goes here...<small>Albert Einstein</small>[/pullquote_left]<br />";	
}

if (selected_shortcode == 'pullquote_right'){
	shortcodetext = "[pullquote_right]Content goes here...<small>Albert Einstein</small>[/pullquote_right]<br />";	
}

if (selected_shortcode == 'dropcap'){
	shortcodetext = "[dropcap]Content goes here...[/dropcap]";	
}

if (selected_shortcode == 'highlight_blue'){
	shortcodetext = "[highlight_blue]Content goes here...[/highlight_blue]";	
}

if (selected_shortcode == 'highlight_yellow'){
	shortcodetext = "[highlight_yellow]Content goes here...[/highlight_yellow]";	
}


// -----------------------------
// 	Chart SHORTCODES
// -----------------------------
if (selected_shortcode == 'chart3dpie'){
	shortcodetext = '[chart data="70,25,20.01,4.99" labels="Reffering+sites|Google|Yahoo|Other" colors="058DC7,50B432,ED561B,EDEF00" bg="bg,s,ffffff" size="460x250" title="3D Pie Chart Title" type="pie"]';	
}
if (selected_shortcode == 'chartline'){
	shortcodetext = '[chart data="70,25,20.01,4.99" labels="2010|2011|2012|2013" colors="058DC7,50B432,ED561B,EDEF00" bg="bg,s,ffffff" size="460x250" title="Line Chart Title" type="line"]';	
}
if (selected_shortcode == 'chartxyline'){
	shortcodetext = '[chart data="0,25,50,75,100|2,33,43,17,25|0,25,50,75,100|0,20,25,40,75" labels="Begin|25|50|75|End" colors="058DC7,50B432" bg="bg,s,ffffff" size="460x250" title="Line Chart Title 2" type="xyline"]';	
}
if (selected_shortcode == 'chartscatter'){
	shortcodetext = '[chart data="0,10,20,30,40,50,60,70,80,90,100|50,52,56,63,70,80,92,85,75,60,43" labels="1|2|3|4|5|6|7|8|9|10" colors="058DC7" bg="bg,s,ffffff" size="460x250" title="Scatter Chart Title" type="scatter"]';	
}
if (selected_shortcode == 'chartpie'){
	shortcodetext = '[chart data="70,25,20.01,4.99" labels="Reffering+sites|Google|Yahoo|Other" colors="058DC7,50B432,ED561B,EDEF00" bg="bg,s,ffffff" size="460x250" title="Pie Chart Title" type="pie2d"]';	
}

	if ( selected_shortcode == 0 ){tinyMCEPopup.close();}}
	if(window.tinyMCE) {
		window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, shortcodetext);
		tinyMCEPopup.editor.execCommand('mceRepaint');
		tinyMCEPopup.close();
	}return;
}