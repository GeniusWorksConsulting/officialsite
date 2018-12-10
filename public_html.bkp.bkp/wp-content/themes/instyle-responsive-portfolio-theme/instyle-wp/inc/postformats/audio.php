<?php $themePath = get_template_directory_uri(); ?>
<article class="post format-video" id="post-<?php the_ID(); ?>">
	<?php $mp3 = get_post_meta($post->ID, 'thb-audio-mp3', TRUE);
		  $ogg = get_post_meta($post->ID, 'thb-audio-ogg', TRUE); ?>
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
				width: "520px",
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