<article class="post format-quote" id="post-<?php the_ID(); ?>">
<?php $quote = get_post_meta($post->ID, 'thb-quote', true); ?>
	  <div class="postheader"><blockquote><p><?php echo $quote; ?></p></blockquote></div>
      <div class="entry-content"><?php the_content('<span>Continue Reading →</span>'); ?></div> 
</article>