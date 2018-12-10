<?php
/*
Template Name: Full Width
*/
?>
<?php $themePath = get_template_directory_uri(); ?>
<?php get_header(); ?>
<div class="span13 offset3" role="main">
    <div id="fullwidth">
        <?php if (have_posts()) :  while (have_posts()) : the_post(); ?>
        <article class="post">
              <h3 class="posttitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h3>
              <div class="entry-content"><?php if ( get_option_tree ('postview', '') == "Excerpts" ) { the_excerpt(''); } else { the_content('<span>Continue Reading →</span>'); } ?></div> 
        </article>
        <?php endwhile; else : endif; ?> 
    </div>
</div>
<?php get_footer(); ?>