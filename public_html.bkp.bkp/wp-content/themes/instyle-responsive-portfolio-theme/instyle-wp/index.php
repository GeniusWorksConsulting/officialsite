<?php
/*
Template Name: Blog Page
*/
?>
<?php $themePath = get_template_directory_uri(); ?>
<?php get_header(); ?>
<div class="span13 offset3" role="main">
    <div class="leftcontainer">
        <?php if (have_posts()) :  while (have_posts()) : the_post(); ?>
        <?php
			// The following determines what the post format is and shows the correct file accordingly
			$format = get_post_format();
			get_template_part( 'inc/postformats/'.$format );
			
			if($format == '')
			get_template_part( 'inc/postformats/standard' );
		
			
		?>
        <?php endwhile; ?>
            <?php theme_pagination($wp_query->max_num_pages); ?>
        <?php else : ?>
        <?php endif; ?>
            
    </div>
    <?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>