
<?php if ( post_password_required() ) : ?>
				<p class="nopassword"><?php _e("This post is password protected. Enter the password to view any comments.", "instyle"); ?></p>
			</div><!-- #comments -->
<?php
	return;
	endif;
?>
<?php if ( have_comments() ) : ?>
			<h3>
            <?php comments_number(__('No Responses', 'instyle'), __('One Response', 'instyle'), __('% Responses', 'instyle') ); ?> to <span><?php the_title(); ?></span>
            </h3>
			<ol class="commentlist">
				<?php wp_list_comments('type=comment&callback=mytheme_comment'); ?>
			</ol>

<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
			<div class="navigation">
				<div class="nav-previous"><?php previous_comments_link(); ?></div>
				<div class="nav-next"><?php next_comments_link(); ?></div>
			</div><!-- .navigation -->
<?php endif; ?>
<?php if ( ! empty($comments_by_type['pings']) ) : ?>
            <h3>Trackbacks/Pingbacks</h3>
            <ol class="pingslist">
                <?php wp_list_comments('type=pings&callback=list_pings'); ?>
            </ol>
<?php endif; ?>
<?php else : 
	if ( ! comments_open() ) :
?>
	<p class="nocomments"><?php _e("Comments are closed", "instyle"); ?></p>
<?php endif; // end ! comments_open() ?>

<?php endif; // end have_comments() ?>

<?php 
// Comment Form
if ('open' == $post->comment_status) : ?>
	
	<div id="respond">
    <h3  id="respond-title"><?php comment_form_title( __('Leave a Reply', 'instyle'), __('Leave a Reply to %s', 'instyle') ); ?></h3>
        <div class="cancel-comment-reply">
            <?php cancel_comment_reply_link(); ?>
        </div>
	<?php if ( get_option('comment_registration') && !$user_ID ) : ?>
	<p>You must be <a href="<?php echo get_option('siteurl'); ?>/wp-login.php?redirect_to=<?php echo urlencode(get_permalink()); ?>">logged in</a> to post a comment.</p>

    <?php else : ?>

   	<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

	<?php if ( $user_ID ) : ?>
            
    <p class="logged">Logged in as <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Log out of this account">Log out</a></p>
            
    <?php else : ?>
			
	<p><input type="text" name="author" id="author" size="22" tabindex="1" class="large" placeholder="<?php _e("Name", "instyle"); ?>"/>
    <label for="author"><small><?php _e("Name", "instyle"); ?> <span>*</span></small></label></p> 
			
	<p><input type="text" name="email" id="email" size="22" tabindex="2" class="large" placeholder="<?php _e("Email", "instyle"); ?>"/>
    <label for="email"><small><?php _e("Email", "instyle"); ?> <span>*</span> <span>(never pulished)</span> </small></label></p>
			
	<p><input type="text" name="url" id="url" size="22" tabindex="3" class="large" placeholder="<?php _e("Website", "instyle"); ?>"/>
    <label for="url"><small><?php _e("Website", "instyle"); ?></small></label></p>
			
	<?php endif; ?>
			
	<p><textarea name="comment" id="comment" cols="58" rows="10" tabindex="4" class="xxlarge"></textarea></p>

	<p><button name="submit" type="submit" id="submit" tabindex="5" class="btn small"><?php _e("Submit Comment", "instyle"); ?></button>
	<?php comment_id_fields(); ?></p>
		    
	<?php do_action('comment_form', $post->ID); ?>
	
	</form>
	
	<?php endif; // If registration required and not logged in ?>
	</div><!--/respond-->
<?php endif; ?>