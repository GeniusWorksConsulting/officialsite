<article class="post format-link" id="post-<?php the_ID(); ?>">
<?php $url = get_post_meta($post->ID, 'thb-link-url', true); ?>
<?php $urltitle = get_post_meta($post->ID, 'thb-link-url-title', true); ?>
	  <div class="postheader"><a href="<?php echo $url; ?>"><?php echo $urltitle; ?></a></div>
      <div class="entry-content"><?php the_content('<span>Continue Reading →</span>'); ?></div>
</article>