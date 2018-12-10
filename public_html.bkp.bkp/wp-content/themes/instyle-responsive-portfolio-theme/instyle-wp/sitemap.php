<?php
/*
Template Name: Sitemap
*/
?>
<?php $themePath = get_template_directory_uri(); ?>
<?php get_header(); ?>
<div class="span13 offset3" role="main">
    <div id="fullwidth">
        <?php if (have_posts()) :  while (have_posts()) : the_post(); ?>
        <article class="post">
              <h3 class="posttitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h3>
              <div class="entry-content">
              	<?php the_content(); ?>   
                <div class="one-half">
                <h3>Pages</h3>
                    <ul>
                        <?php wp_list_pages("title_li=" ); ?>
                    </ul>
                <h3>Archives</h3>
                    <ul>
                        <?php wp_get_archives('type=monthly&show_post_count=true'); ?>
                    </ul>
                </div>
                <div class="one-half last">
                <h3>Categories</h3>
                    <ul>
                        <?php wp_list_categories('sort_column=name&optioncount=1&hierarchical=0&feed=RSS&title_li='); ?>
                    </ul>
                <h3>Feeds</h3>
                    <ul>
                        <li><a title="Full content" href="feed:<?php bloginfo('rss2_url'); ?>">Main RSS</a></li>
                        <li><a title="Comment Feed" href="feed:<?php bloginfo('comments_rss2_url'); ?>">Comment Feed</a></li>
                    </ul>
                <h3>Portfolio</h3>
                   <ul>
                        <?php $query = new WP_Query(); $query->query('post_type=portfolio&posts_per_page=-1'); ?>
                        <?php if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post(); ?>
                        <li><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></li>
                        <?php endwhile; endif; ?>
                   </ul>
                        <?php wp_reset_query(); ?> 
                
                </div> 
              </div> 
        </article>
        <?php endwhile; else : endif; ?> 
    </div>
</div>
<?php get_footer(); ?>