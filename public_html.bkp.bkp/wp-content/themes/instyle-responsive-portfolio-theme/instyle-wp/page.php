<?php $themePath = get_template_directory_uri(); ?>
<?php get_header(); ?>
<div class="span13 offset3" role="main">
    <div class="postcontainer">
        <?php if (have_posts()) :  while (have_posts()) : the_post(); ?>
        <?php
			// The following determines what the post format is and shows the correct file accordingly
			$format = get_post_format();
			get_template_part( 'inc/postformats/'.$format );
			
			if($format == '')
			get_template_part( 'inc/postformats/standard' );
		
			
		?>
        <?php endwhile; endif; ?>
        <?php get_sidebar(); ?>
    </div>
</div>
<?php get_footer(); ?>