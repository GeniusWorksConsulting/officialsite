<?php
/*
Template Name: Classic Portfolio Three Columns
*/
?>
<?php get_header(); ?>
<?php $themePath = get_template_directory_uri(); ?>
<div class="span13 offset3" role="main">
	<?php $activevar = get_query_var('skill-type'); ?>
	<?php if (have_posts()) :  while (have_posts()) : the_post(); ?>
    <div class="pagination filter">
        <ul>
            <li class="prev disabled"><span><?php _e('Filter &rarr;', 'instyle') ?></span></li>
            <?php $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1; ?>
            <li><a title="Show All" href="<?php echo add_query_arg(array ( 'page' => $paged, 'skill-type' => ''));?>" <?php if ( $activevar == "") { echo 'class="active"'; } ?>>All</a></li>
            <?php
			// $excludecat = get_post_meta($post->ID, 'thb-xclude', false);
			// $excludecat = implode(", ",$excludecat);
            if (!empty($xcludecat)) {$categories=  get_categories('taxonomy=skill-type&title_li=&exclude='.$excludecat);}
            else {$categories = get_categories('taxonomy=skill-type&title_li=');}
            
            foreach ($categories as $category){ ?>
            <li><a href="<?php echo add_query_arg(array ( 'page' => $paged, 'skill-type' => $category->category_nicename));?>" title="Filter by <?php echo $category->name;?>" <?php if ( $activevar == $category->category_nicename) { echo 'class="active"'; } ?>><?php echo $category->name;?></a></li>
            <?php } ?>
        </ul>
    </div>
    <?php endwhile; else : endif; ?>
    <div class="row gallery">
        <?php 
            $itemlimit 	=	get_option_tree ('portfolioclassiccount', '-1');
            
             $args = array(
                'post_type' => 'portfolio',
                'orderby'=>'menu_order',
                'order'     => 'ASC',
                'posts_per_page' => $itemlimit,
                'paged' => $paged,
                'skill-type' => get_query_var('skill-type'),
                'tax_query' => array(
                array(
                        'taxonomy' => 'skill-type',
                        'field' => 'id',
                        'terms' => $excludecat,
                        'operator' => 'NOT IN',
                        )
                    ) // end of tax_query
                );

            $query = new WP_Query( $args ); ?>
        <ul class="gallerycolumns">
            <?php $count = 1; ?>
            <?php if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post();
                  $terms = get_the_terms( get_the_ID(), 'skill-type' );  ?> 
            <li>
                <article id="post-<?php the_ID(); ?>" class="galleryitem3">	
                    <?php
                    if ( (function_exists('has_post_thumbnail')) && (has_post_thumbnail()) ) { ?>
                    <div class="postheader">
                        <?php $image_id = get_post_thumbnail_id(); ?> 
                        <?php $image_url = wp_get_attachment_image_src($image_id,'full'); $image_url = $image_url[0]; ?>
                        <a href="<?php echo $image_url; ?>" rel="prettyPhoto[gallery]"><span class="overlay"></span><?php the_post_thumbnail('portfolio-small', array('class' => 'portfolio')); ?></a>
                    </div>
                    <?php } ?>
                    <h3><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
                    <div class="entry-content"><?php the_excerpt(); ?></div>
                </article>
            </li>
            <?php $count++; ?>
            <?php endwhile; endif; ?>
        </ul>
        
    </div>
    <?php theme_pagination($query->max_num_pages); ?>
</div>
<?php get_footer(); ?>