<article class="post" id="post-<?php the_ID(); ?>">
      <h3 class="posttitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h3>
      <div class="entry-content"><?php if ( get_option_tree ('postview', '') == "Excerpts" ) { the_excerpt(''); } else { the_content('<span>Continue Reading →</span>'); } ?></div> 
</article>