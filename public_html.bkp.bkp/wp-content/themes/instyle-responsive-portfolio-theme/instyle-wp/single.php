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
         <aside class="span4 postmeta">	
            <ul class="postlinks">
                <li><a href="#" class="date"><?php the_date(); ?></a></li>
                <li><?php $bitly = get_permalink(); ?><a href="<?php bitly($bitly); ?>" class="permalink"><?php bitly($bitly); ?></a></li>
                <li><?php comments_popup_link('0 Comments', '1 Comment', '% Comments', 'commentcount', 'Comments Disabled'); ?></li>
                <li><a href="http://twitter.com/home?status=Currently reading <?php bitly(the_permalink()); ?>" class="twitter" title="Click to share this post on Twitter"><?php _e("Share on Twitter","instyle"); ?></a></li>
            </ul>
            <?php if ( get_option_tree ('relatedpopular', '') == "Active" ) { ?>
			<?php $relatedpopularno = get_option_tree ('relatedpopularno', '4'); ?>
                    <?php related_posts($relatedpopularno); ?>
            <?php } ?>
        </aside>   
    </div>
    <div id="comments">
    	<?php comments_template('', true ); ?>
    </div>
</div>
<?php get_footer(); ?>