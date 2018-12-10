<?php
/*
Template Name: Sortable Portfolio Two Columns
*/
?>
<?php get_header(); ?>
<?php $themePath = get_template_directory_uri(); ?>
<div class="span13 offset3" role="main">
	<?php if (have_posts()) :  while (have_posts()) : the_post(); ?>
    <div class="pagination filter">
        <ul id="portfolio-control">
            <li class="prev disabled"><span><?php _e('Filter &rarr;', 'instyle') ?></span></li>
            <li class="segment-1"><a class="active all" data-value="all" href="#">All</a></li>
            <?php wp_list_categories(array('title_li' => '', 'taxonomy' => 'skill-type', 'walker' => new Walker_Category_Filter())); ?>
        </ul>
    </div>
    <?php endwhile; else : endif; ?>
    <div class="row gallery">
        <?php $query = new WP_Query(); $query->query('post_type=portfolio&posts_per_page=-1'); ?>
        <ul class="gallerycolumns">
            <?php $count = 1; ?>
			<?php if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post();
                  $terms = get_the_terms( get_the_ID(), 'skill-type' );  ?> 
            <li data-id="id-<?php echo $count; ?>" class="<?php foreach ($terms as $term) { echo strtolower(preg_replace('/\s+/', '-', $term->name)). ' '; } ?>">
                <article id="post-<?php the_ID(); ?>" class="galleryitem2">	
                    <?php
                    if ( (function_exists('has_post_thumbnail')) && (has_post_thumbnail()) ) { ?>
                    <div class="postheader">
                        <?php $image_id = get_post_thumbnail_id(); ?> 
                        <?php $image_url = wp_get_attachment_image_src($image_id,'full'); $image_url = $image_url[0]; ?>
                        <a href="<?php echo $image_url; ?>" rel="prettyPhoto[gallery]"><span class="overlay"></span><?php the_post_thumbnail('portfolio-medium', array('class' => 'portfolio')); ?></a>
                    </div>
                    <?php } ?>
                    <h3><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
                    <div class="entry-content"><?php the_excerpt(); ?></div>
                </article>
            </li>
            <?php $count++; ?>
            <?php endwhile; endif; ?>
        </ul>
		<?php wp_reset_query(); ?>
    </div>
</div>
<?php get_footer(); ?>