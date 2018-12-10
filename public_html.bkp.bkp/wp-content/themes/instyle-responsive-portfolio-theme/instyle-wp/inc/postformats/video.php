<?php $themePath = get_template_directory_uri(); ?>
<article class="post format-video" id="post-<?php the_ID(); ?>">
	<?php $m4v = get_post_meta($post->ID, 'thb-video-m4v', TRUE);
          $ogv = get_post_meta($post->ID, 'thb-video-ogv', TRUE);
          $poster = get_post_meta($post->ID, 'thb-video-poster', TRUE);
		  $height = get_post_meta($post->ID, 'thb-video-height', TRUE); 
		  $embed = get_post_meta($post->ID, 'thb-video-embed', TRUE); ?>
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
					width: "520px",
					height: "<?php if($height == '') { echo "250px"; } else { echo $height."px";} ?>"
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