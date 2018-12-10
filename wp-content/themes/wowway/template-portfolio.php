<?php
/*---------------------------------
	Template Name: Portfolio
------------------------------------*/
 
	get_header(); 
	$k1 = 0;
	$k2 = 0;
	
?>

		<section id="portfolio" class="folioGrid clearfix">
			<?php while (have_posts()) : the_post(); 
			
				$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
				$args = array( 'posts_per_page' => 6, 
					   'offset'=> 0,
					   'post_type' => 'portfolio');

				$all_posts = new WP_Query($args);

				while($all_posts->have_posts()) : $all_posts->the_post();
				
				$portfolio_terms = wp_get_object_terms($post->ID, 'portfolio_category');
				$portfolio_class = "folioItem " . $portfolio_terms[0]->slug;
				$portfolio_sort = $portfolio_terms[0]->slug . '[1][0]';
				$portfolio_type = $portfolio_terms[0]->slug;

			?>

			<a id="post-<?php the_ID(); ?>" class="<?php echo $portfolio_class; ?>" data-sort="<?php echo $portfolio_sort; ?>" data-type="<?php echo $portfolio_type; ?>" href="<?php the_permalink(); ?>" data-name="<?php echo $post->post_name; ?>">

				<?php the_post_thumbnail('portfolio-thumb', array('class' => 'folioThumb'));?>

				<div class="folioTextHolder">
					<div class="folioText">
						<h3><?php the_title(); ?></h3>
						<p><?php rb_excerpt('rb_excerptlength_folio', 'rb_excerptmore'); ?></p>
					</div>
				</div>
				
			</a>
			<?php endwhile; ?>
		<?php endwhile; ?>

		<ul class="folioCategories hidden">
		<?php 

		$portfolio_categories = get_categories(array('taxonomy'=>'portfolio_category'));
		foreach($portfolio_categories as $portfolio_category)
			echo '<li>' . $portfolio_category->slug . '</li>';
		?>
		</ul>

		</section>

		<div id="projectHover" class="hidden hasButtons">
			<a href="#" class="btnNext hoverBack">Next</a>
			<a href="#" class="btnClose hoverBack">Close</a>
			<a href="#" class="btnPrev hoverBack">Prev</a>
		</div>

		<div class="intro_text">
<?php
	$pid = 24;
	$page = get_page($pid);
	$content = apply_filters( 'the_content', $page->post_content );
	echo $content;
?>

		</div>

	
	<?php get_footer(); ?>