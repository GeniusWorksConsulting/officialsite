<?php $themePath = get_template_directory_uri(); ?>
<?php get_header(); ?>
<div class="span13 offset3" role="main">
    <div id="fullwidth">
        <?php if (have_posts()) :  while (have_posts()) : the_post(); ?>
        <?php $switch = get_post_meta($post->ID, 'thb-switch', TRUE); ?>
        <?php if($switch == '2') { ?>
        <article class="post format-video" id="post-<?php the_ID(); ?>">
			<?php $mp3 = get_post_meta($post->ID, 'thb-portfolio-audio-mp3', TRUE);
                  $ogg = get_post_meta($post->ID, 'thb-portfolio-audio-ogg', TRUE); ?>
            <div class="postheader">
                <div id="jquery_jplayer_<?php the_ID(); ?>" class="jp-jplayer"></div>
                <div class="jp-video-container">
                    <div class="jp-video">
                        <div class="jp-type-single">
                            <div id="jp_interface_<?php the_ID(); ?>" class="jp-interface">
                                <ul class="jp-controls">
                                    <li><a href="#" class="jp-play" tabindex="1">play</a></li>
                                    <li><a href="#" class="jp-pause" tabindex="1">pause</a></li>
                                    <li><a href="#" class="jp-mute" tabindex="1">mute</a></li>
                                    <li><a href="#" class="jp-unmute" tabindex="1">unmute</a></li>
                                </ul>
                                <div class="jp-progress-container">
                                    <div class="jp-progress">
                                        <div class="jp-seek-bar">
                                            <div class="jp-play-bar"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="jp-volume-bar-container">
                                    <div class="jp-volume-bar">
                                        <div class="jp-volume-bar-value"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                jQuery(document).ready(function(){
                    $("#jquery_jplayer_<?php the_ID(); ?>").jPlayer({
                        ready: function () {
                            $(this).jPlayer("setMedia", {
                                <?php if($mp3 != '') : ?>
                                mp3: "<?php echo $mp3; ?>",
                                <?php endif; ?>
                                <?php if($ogg != '') : ?>
                                oga: "<?php echo $ogg; ?>",
                                <?php endif; ?>
                                end: ""
                            });
                        },
                        swfPath: "<?php echo $themePath; ?>/js/plugins",
                        cssSelectorAncestor: "#jp_interface_<?php the_ID(); ?>",
                        supplied: "<?php if($ogg != '') : ?>oga,<?php endif; ?><?php if($mp3 != '') : ?>mp3, <?php endif; ?> all"
                    });
                });
                </script>
            </div>
            <h3 class="posttitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h3>
            <div class="entry-content"><?php if ( get_option_tree ('postview', '') == "Excerpts" ) { the_excerpt(''); } else { the_content('<span>Continue Reading →</span>'); } ?></div> 
        </article>
        <?php } elseif ($switch == '0') { ?>
        <article class="post format-gallery" id="post-<?php the_ID(); ?>" data-loader="<?php echo $themePath; ?>/images/preloader.gif">
        <?php $lightbox = get_post_meta($post->ID, 'thb-portfolio-lightbox', TRUE); ?>
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
                            $src = wp_get_attachment_image_src( $attachment->ID, 'portfolio-single'); 
                            $image_id = $attachment->ID;
                            $image_url = wp_get_attachment_image_src($image_id,'full'); $image_url = $image_url[0];
                        ?>
                        
                        <div>
                        <?php if ($lightbox == "Yes") { ?>
                        <a href="<?php echo $image_url; ?>" rel="prettyPhoto[<?php the_ID(); ?>]" title="<?php the_title(); ?>"><span class="overlay"></span>
                        <?php } ?>
                        <img 
                        height="<?php echo $src[2]; ?>"
                        width="<?php echo $src[1]; ?>"
                        alt="<?php echo apply_filters('the_title', $attachment->post_title); ?>" 
                        src="<?php echo $src[0]; ?>" 
                        />
                        <?php if ($lightbox == "Yes") { ?>
                        </a>
                        <?php } ?>
                        </div>
                    <?php endforeach; ?>
                    </div>
                    <script type="text/javascript">
                        $(document).ready(function(){
                            $("#post-<?php the_ID(); ?> .postheader").slides({
                                preload: true,
                                preloadImage: jQuery("#post-<?php the_ID(); ?>").attr('data-loader'), 
                                generatePagination: true,
                                paginationClass: 'slides-pagination',
                                effect: 'fade',
                                autoHeight: true,
								<?php if ($lightbox == "No") { ?>
								bigTarget: true
								<?php } ?>
                            });
                        });
                    </script>
                </div>
              <h3 class="posttitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h3>
              <div class="entry-content"><?php if ( get_option_tree ('postview', '') == "Excerpts" ) { the_excerpt(''); } else { the_content('<span>Continue Reading →</span>'); } ?></div> 
        </article>
        <?php } elseif($switch == '1') { ?>
        <article class="post format-video" id="post-<?php the_ID(); ?>">
		<?php $m4v = get_post_meta($post->ID, 'thb-portfolio-video-m4v', TRUE);
              $ogv = get_post_meta($post->ID, 'thb-portfolio-video-ogv', TRUE);
              $poster = get_post_meta($post->ID, 'thb-portfolio-video-poster', TRUE);
			  $height = get_post_meta($post->ID, 'thb-portfolio-video-height', TRUE);
              $embed = get_post_meta($post->ID, 'thb-portfolio-video-embed', TRUE); ?>
            <div class="postheader">
                <?php if ($embed !='') { ?>
                    <?php echo stripslashes(htmlspecialchars_decode($embed)); ?>
                <?php } else { ?>
                <div id="jquery_jplayer_<?php the_ID(); ?>" class="jp-jplayer"></div>
                <div class="jp-video-container">
                    <div class="jp-video">
                        <div class="jp-type-single">
                            <div id="jp_interface_<?php the_ID(); ?>" class="jp-interface">
                                <ul class="jp-controls">
                                    <li><a href="#" class="jp-play" tabindex="1">play</a></li>
                                    <li><a href="#" class="jp-pause" tabindex="1">pause</a></li>
                                    <li><a href="#" class="jp-mute" tabindex="1">mute</a></li>
                                    <li><a href="#" class="jp-unmute" tabindex="1">unmute</a></li>
                                </ul>
                                <div class="jp-progress-container">
                                    <div class="jp-progress">
                                        <div class="jp-seek-bar">
                                            <div class="jp-play-bar"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="jp-volume-bar-container">
                                    <div class="jp-volume-bar">
                                        <div class="jp-volume-bar-value"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                jQuery(document).ready(function(){
                    $("#jquery_jplayer_<?php the_ID(); ?>").jPlayer({
                        ready: function () {
                            $(this).jPlayer("setMedia", {
                                <?php if($m4v != '') : ?>
                                m4v: "<?php echo $m4v; ?>",
                                <?php endif; ?>
                                <?php if($ogv != '') : ?>
                                ogv: "<?php echo $ogv; ?>",
                                <?php endif; ?>
                                <?php if ($poster != '') : ?>
                                poster: "<?php echo $poster; ?>"
                                <?php endif; ?>
                            });
                        },
                        size: {
							width: "760px",
							height: "<?php if($height == '') { echo "440px"; } else { echo $height."px";} ?>"
						},
                        swfPath: "<?php echo $themePath; ?>/js/plugins",
                        cssSelectorAncestor: "#jp_interface_<?php the_ID(); ?>",
                        supplied: "<?php if($m4v != '') : ?>m4v, <?php endif; ?><?php if($ogv != '') : ?>ogv, <?php endif; ?> all"
                    });
                });
                </script>
                <?php } ?>
            </div>
            <h3 class="posttitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h3>
            <div class="entry-content"><?php if ( get_option_tree ('postview', '') == "Excerpts" ) { the_excerpt(''); } else { the_content('<span>Continue Reading →</span>'); } ?></div> 
        </article>
        <?php } else { ?>
        <article class="post format-image" id="post-<?php the_ID(); ?>">
        	<?php $lightbox = get_post_meta($post->ID, 'thb-portfolio-lightbox', TRUE); ?>
			<?php if ( has_post_thumbnail() ) { ?>
            <div class="postheader">
                <?php $image_id = get_post_thumbnail_id(); ?> 
                <?php $image_url = wp_get_attachment_image_src($image_id,'full'); $image_url = $image_url[0]; ?>
                <?php if ($lightbox == "Yes") { ?>
                <a href="<?php echo $image_url; ?>" rel="prettyPhoto" title="<?php the_title(); ?>"><span class="overlay"></span>
				<?php } ?>
				<?php the_post_thumbnail('portfolio-single'); ?>
                <?php if ($lightbox == "Yes") { ?>
                </a>
                <?php } ?>
            </div>
            <?php } ?>
          <h3 class="posttitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h3>
          <div class="entry-content"><?php if ( get_option_tree ('postview', '') == "Excerpts" ) { the_excerpt(''); } else { the_content('<span>Continue Reading →</span>'); } ?></div> 
        </article>
        <?php } ?>
        <?php if ( get_option_tree ('portfoliorelated', '') == "Active" ) { ?>
        	<h3 class="relatedh3"><?php echo get_option_tree ('portfoliorelatedtitle', 'Related Items'); ?></h3>
            <div class="row gallery">
            	<ul class="gallerycolumns">
					<?php global $post; 
                          $postId = $post->ID;
                          $query = get_posts_related_by_taxonomy($post->ID, 'skill-type'); ?>
                    <?php if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post();
                          $terms = get_the_terms( get_the_ID(), 'skill-type' ); ?>
                                 
                    <?php if(get_the_ID() != $postId) : ?>
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
                            <h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark"> <?php the_title(); ?></a></h3>
                            <div class="entry-content"><?php the_excerpt(); ?></div>
                        </article>
                    </li>
                    <?php endif; endwhile; endif; ?>
            	</ul>
            </div>
        <?php } ?>
        <?php endwhile; else : endif; ?> 
    </div>
</div>
<?php get_footer(); ?>