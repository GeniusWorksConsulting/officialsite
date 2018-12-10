<?php
// WP3 Custom Background Support
add_custom_background();

// Feed Links
add_theme_support('automatic-feed-links');

// Localization support
load_theme_textdomain('instyle'); 

// Remove Unneeded Stuff
require_once('inc/remove.php');

// Shortener
require_once('inc/excerpts.php');

// Activate WP3 Menu Support
require_once('inc/wp3menu.php');

// Enable Sidebars
require_once('inc/sidebar.php');

// Enable Featured Images
require_once('inc/postthumbs.php');

// Related / Popular Posts
require_once('inc/related.php');

// Post Formats
require_once('inc/post-formats.php');

// Create Custom Post Types
require_once('inc/customposttypes.php');

// Custom Post Type Category Walker
require_once('inc/posttypecategories.php');

// WP-Pagenavi
require_once('inc/wp-pagenavi.php');

// Custom Comments
require_once('inc/comments.php');

// Metaboxes for Pages
require_once('inc/metabox.php');

// Multiple Sidebars
require_once('inc/multiple-sidebars.php');

// Shortcodes
require_once('inc/shortcodes.php');

// Enable Shortcodes in Widgets
add_filter('widget_text', 'do_shortcode');

// Custom Widgets
require_once('inc/widgets.php');

// Admin Page
require_once('inc/admin.php');

// Post Types Re-Order
require_once('inc/post-types-order/post-types-order.php');

// WYSIWYG Button
require_once('inc/wysiwyg/wysiwyg.php');

// Script Calls
require_once('inc/script-calls.php');

// Enable Bit.ly
require_once('inc/bitly.php');
?>