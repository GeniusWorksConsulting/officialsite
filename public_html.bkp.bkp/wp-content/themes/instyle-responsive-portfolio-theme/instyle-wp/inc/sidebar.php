<?php

if ( function_exists('register_sidebar') ){
register_sidebar(array('name' => 'Blog', 'id' => 'instyle-blog', 'description' => 'The sidebar that shows up in your blog post listings', 'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget' => '</div>', 'before_title' => '<h2>', 'after_title' => '</h2>'));
}
if ( function_exists('register_sidebar') ){
register_sidebar(array('name' => 'Pages', 'id' => 'instyle-page', 'description' => 'The sidebar that shows up in pages', 'before_widget' => '<div id="%1$s" class="widget %2$s">', 'after_widget' => '</div>', 'before_title' => '<h2>', 'after_title' => '</h2>'));
}
?>