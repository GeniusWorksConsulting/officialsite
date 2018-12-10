<?php
// Enable WP Post Thumbnails
if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 60, 60, true );
	add_image_size('portfolio-single', 760, 9999, true );
	add_image_size('portfolio-large', 520, 300, true );
	add_image_size('portfolio-medium', 370, 215, true );
	add_image_size('portfolio-small', 240, 150, true );
	add_image_size('post-image', 520, 300, true );
}
?>