<?php

/* Portfolio Custom Post Type */

function create_post_type_portfolios() 
{
	$labels = array(
		'name' => __( 'Portfolio'),
		'singular_name' => __( 'Portfolio' ),
		'rewrite' => array('slug' => __( 'portfolios' )),
		'add_new' => _x('Add New', 'slide'),
		'add_new_item' => __('Add New Portfolio'),
		'edit_item' => __('Edit Portfolio'),
		'new_item' => __('New Portfolio'),
		'view_item' => __('View Portfolio'),
		'search_items' => __('Search Portfolio'),
		'not_found' =>  __('No portfolios found'),
		'not_found_in_trash' => __('No portfolios found in Trash'), 
		'parent_item_colon' => ''
	  );
	  
	  $args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true, 
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title','editor','thumbnail','excerpt')
	  ); 
	  
	  register_post_type(__( 'portfolio' ),$args);
}

function build_taxonomies(){
	register_taxonomy(__( "skill-type" ), array(__( "portfolio" )), array("hierarchical" => true, "label" => __( "Types" ), "singular_label" => __( "Type" ), "rewrite" => array('slug' => 'skill-type', 'hierarchical' => true)));  
}

  
function portfolio_edit_columns($columns){  

        $columns = array(  
            "cb" => "<input type=\"checkbox\" />",  
            "title" => __( 'Portfolio Item Title' ),
            "type" => __( 'Type' )
        );  
  
        return $columns;  
}  

function portfolio_custom_columns($column){  
        global $post;  
        switch ($column)  
        {    
            case __( 'type' ):  
                echo get_the_term_list($post->ID, __( 'skill-type' ), '', ', ','');  
                break;
        }  
} 

function slider_edit_columns($columns){  

        $columns = array(  
            "cb" => "<input type=\"checkbox\" />",  
            "title" => __( 'Slide Title' )
        );  
  
        return $columns;  
}  

add_action( 'init', 'create_post_type_portfolios' );
add_action( 'init', 'build_taxonomies', 0 );
add_filter("manage_edit-portfolio_columns", "portfolio_edit_columns"); 
add_action("manage_posts_custom_column",  "portfolio_custom_columns");  
?>