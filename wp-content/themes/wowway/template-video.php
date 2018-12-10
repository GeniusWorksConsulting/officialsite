<?php
/*---------------------------------
	Template Name: Fullscreen Video
------------------------------------*/
 
	get_header(); 
	
?>

    <video id="fullScreenVideo" class="projekktor default" poster="<?php echo get_post_meta($post->ID, 'rb_video_poster', true); ?>" title="<?php the_title(); ?>" width="<?php echo get_post_meta($post->ID, 'rb_video_width', true); ?>" height="<?php echo get_post_meta($post->ID, 'rb_video_height', true); ?>" controls>
        <source src="<?php echo get_post_meta($post->ID, 'rb_video_1', true); ?>" type="video/mp4" />
        <source src="<?php echo get_post_meta($post->ID, 'rb_video_2', true); ?>" type="video/ogg" />
        <source src="<?php echo get_post_meta($post->ID, 'rb_video_3', true); ?>" type="video/webm" />
    </video>

    <div id="playPause"><a class="hoverBack" href="#">Play/Pause</a></div>

    <div id="dummyAbove"></div>

	<?php get_footer(); ?>