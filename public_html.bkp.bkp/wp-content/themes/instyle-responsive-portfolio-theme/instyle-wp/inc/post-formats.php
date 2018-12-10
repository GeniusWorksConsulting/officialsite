<?php
$formats = array( 
			'gallery', 
			'link', 
			'image', 
			'quote', 
			'audio',
			'video');

add_theme_support( 'post-formats', $formats ); 

add_post_type_support( 'post', 'post-formats' );
?>