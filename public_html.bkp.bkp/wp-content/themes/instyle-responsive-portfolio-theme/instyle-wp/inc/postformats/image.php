<article class="post format-image" id="post-<?php the_ID(); ?>">
		<?php if ( has_post_thumbnail() ) { ?>
        <div class="postheader">
            <?php $image_id = get_post_thumbnail_id(); ?> 
            <?php $image_url = wp_get_attachment_image_src($image_id,'full'); $image_url = $image_url[0]; ?>
            <a href="<?php echo $image_url; ?>" rel="prettyPhoto" title="<?php the_title(); ?>"><span class="overlay"></span><?php the_post_thumbnail('post-image'); ?></a>
        </div>
        <?php } ?>
      <h3 class="posttitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h3>
      <div class="entry-content"><?php if ( get_option_tree ('postview', '') == "Excerpts" ) { the_excerpt(''); } else { the_content('<span>Continue Reading →</span>'); } ?></div> 
</article>