<?php $themePath = get_template_directory_uri(); ?>
<article class="post format-gallery" id="post-<?php the_ID(); ?>" data-loader="<?php echo $themePath; ?>/images/preloader.gif"> 
    <div class="postheader">
        <div class="slides_container">
        <?php 
            $args = array(
                'orderby'		 => 'menu_order',
                'post_type'      => 'attachment',
                'post_parent'    => get_the_ID(),
                'post_mime_type' => 'image',
                'post_status'    => null,
                'numberposts'    => -1,
            );
            $attachments = get_posts($args);
        ?>
        <?php foreach ($attachments as $attachment) : ?>
            
            <?php 
                $src = wp_get_attachment_image_src( $attachment->ID, 'post-image'); 
                $image_id = $attachment->ID;
                $image_url = wp_get_attachment_image_src($image_id,'full'); $image_url = $image_url[0];
            ?>
            
            <div>
            <a href="<?php echo $image_url; ?>" rel="prettyPhoto[<?php the_ID(); ?>]" title="<?php the_title(); ?>"><span class="overlay"></span>
            <img 
            height="<?php echo $src[2]; ?>"
            width="<?php echo $src[1]; ?>"
            alt="<?php echo apply_filters('the_title', $attachment->post_title); ?>" 
            src="<?php echo $src[0]; ?>" 
            />
            </a>
            </div>
        <?php endforeach; ?>
        </div>
        <script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery("#post-<?php the_ID(); ?> .postheader").slides({
					preload: true,
					preloadImage: jQuery("#post-<?php the_ID(); ?>").attr('data-loader'), 
					generatePagination: true,
					paginationClass: 'slides-pagination',
					effect: 'fade',
					autoHeight: true
				});
			});
		</script>
    </div>
    <h3 class="posttitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h3>
    <div class="entry-content"><?php if ( get_option_tree ('postview', '') == "Excerpts" ) { the_excerpt(''); } else { the_content('<span>Continue Reading →</span>'); } ?></div> 
</article>