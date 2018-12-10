<?php
// Related Posts
function related_posts($args) {
	global $post, $wpdb;
	$backup = $post;  // backup the current object
	$tags = wp_get_post_tags($post->ID);
	$tagIDs = array();
	if ($tags) {
	  $tagcount = count($tags);
	  for ($i = 0; $i < $tagcount; $i++) {
	    $tagIDs[$i] = $tags[$i]->term_id;
	  }
	  $arguments=array(
	    'tag__in' => $tagIDs,
	    'post__not_in' => array($post->ID),
	    'showposts'=> $args,
	    'ignore_sticky_posts'=>1
	  );
	  $my_query = new WP_Query($arguments);
	  if( $my_query->have_posts() ) { $related_post_found = true; ?>
		<h2><?php _e("Related Posts", "instyle"); ?></h2>
			<ul class="relatedposts">		
	    <?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
				<li>
				<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>">
					<?php the_title(); ?>
                </a>
				</li>				
	    <?php endwhile; ?>
			</ul>		
	  <?php }
	}
	$post = $backup;  // copy it back
	
	//show recent posts if no related found
	if(!$related_post_found){
		$posts = get_posts('numberposts='.$args.'&offset=0');
		if($posts){ ?>
		<h2><?php _e("Recent Posts", "instyle"); ?></h2>
		<ul class="relatedposts">
			<?php foreach($posts as $post){
					$post_title = stripslashes($post->post_title);
					$permalink = get_permalink($post->ID);	
					setup_postdata($post);
			?>
			<li>
				<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>">
					<?php the_title(); ?>
                </a>
			</li>
			<?php } ?>
		</ul>
		<?php }
	}
	wp_reset_query();
}

// Related Posts for portfolio
function get_posts_related_by_taxonomy($post_id,$taxonomy,$args=array()) {
  $query = new WP_Query();
  $terms = wp_get_object_terms($post_id,$taxonomy);
  if (count($terms)) {
    // Assumes only one term for per post in this taxonomy
    $post_ids = get_objects_in_term($terms[0]->term_id,$taxonomy);
    $post = get_post($post_id);
    $args = wp_parse_args($args,array(
      'post_type' => $post->post_type, // The assumes the post types match
      'post__in' => $post_ids,
      'post__not_in' => $post->ID,
      'taxonomy' => $taxonomy,
      'term' => $terms[0]->slug,
	  'showposts' => 4
    ));
    $query = new WP_Query($args);
  }
  return $query;
}
?>