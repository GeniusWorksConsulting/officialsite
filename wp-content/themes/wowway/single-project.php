<!--///////////////////////////////
	Single Project Page Template
////////////////////////////////////////-->

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

		<h1 id="title" class="hidden"><?php wp_title( '|', true, 'right' ); ?></h1>
	
		<article id="post-<?php echo the_ID(); ?>" <?php post_class('project clearfix'); ?>>

			<section class="projectSlides">

			<?php

				if($slider_images) {
					echo '<div id="postSlider"><div class="slides_container">';
					foreach ($slider_images as $key => $value) 
						if (strpos($value, '.jpg') > 0 || strpos($value, '.jpeg') > 0 || strpos($value, '.JPG') > 0 || strpos($value, '.JPEG') > 0 || strpos($value, '.png') > 0 || strpos($value, '.PNG') > 0 || strpos($value, '.bmp') > 0 || strpos($value, '.BMP') > 0 || strpos($value, '.gif') > 0 || strpos($value, '.GIF') > 0 || is_numeric($value)) {
								if(is_numeric($value))
			        				echo '<div>' . wp_get_attachment_image($value, 'large', 0) . '</div>';
			        			else
			        				echo '<div><img src="' . $value . '" /></div>';
			        	} else {
			        		if(strpos($value, 'iframe') > 0)
				        		echo '<div>' . $value . '</div>';
				        	else
				        		echo '<div><video id="projectVideo" class="projekktor minimum" width="600" height="480" poster="' . $value . '.jpg"><source src="' . $value . '.ogv" type="video/ogg" /><source src="' . $value . '.mp4" type="video/mp4" /></video></div>';
						} 
					echo '</div>';
					if(sizeof($slider_images) > 1) {
						echo '<div class="sliderControls">
							<a href="#" class="sliderBtnPrev">Previous</a>
							<a href="#" class="sliderBtnNext">Next</a>
							<span class="sliderPagination">1 of 3</span>
						</div>';
					}
					echo '</div>';
				}

			?>

			</section>

			<section class="projectContent nano">

				<div class="content">
					<h4><?php the_title(); ?></h4>
					<hr />
					<span class="category">
						<?php $category = wp_get_object_terms($post->ID, 'portfolio_category'); 
						echo $category[0]->name; ?>
					</span>
					<hr class="second" />
					<?php the_content(); ?>
					<a class="actionButton close" href="#">Close</a>


					<?php //if($post->ID == '438') : ?>
					<!-- 

					<div id="shareLinks" class="shareLinks">
						<!-- links / scripts are in the footer -->
						<!--
						<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo get_permalink($post->ID); ?>" data-text="<?php wp_title( '|', true, 'right' ); ?>: ">Tweet</a>
						<div class="fb-like" data-send="false" data-layout="button_count" data-width="110" data-href="<?php echo get_permalink($post->ID); ?>" data-show-faces="false"></div>
					</div>
					-->

					<?php //endif; ?>

					<p id="pwd" class="hidden"><?php echo base64_encode(get_post_meta($post->ID, 'rb_post_pass', true)); ?></p>

				</div>

			</section>



			<div id="nextProject" class="hidden"><?php echo next_post_link('%link', get_next_post()->post_name, false, $exclude); ?></div>
			<div id="previousProject" class="hidden"><?php echo previous_post_link('%link', get_previous_post()->post_name, false, $exclude); ?></div>
		
		</article>

	<?php endwhile; ?>