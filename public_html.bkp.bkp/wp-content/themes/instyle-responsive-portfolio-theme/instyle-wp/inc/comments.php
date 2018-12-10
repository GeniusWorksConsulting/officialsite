<?php
function enable_threaded_comments(){
	if (!is_admin()) {
		if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1))
			wp_enqueue_script('comment-reply');
		}
}
add_action('get_header', 'enable_threaded_comments');

function mytheme_comment($comment, $args, $depth) {
$GLOBALS['comment'] = $comment; ?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
	<div id="comment-<?php comment_ID(); ?>">
        <div class="comment-text">
        	<?php echo get_avatar($comment,$size='60',$default=get_template_directory_uri().'/images/small-avatar.png' ); ?> 
            <?php if ($comment->comment_approved == '0') : ?>
            <em class="awaiting_moderation"><?php _e('Your comment is awaiting moderation.', 'instyle') ?></em>
            <br />
            <?php endif; ?>
            <?php comment_text() ?>
        </div>
        <aside class="vcard">
        	<span class="authorname"><?php comment_author_link() ?></span><span class="date"><?php comment_date('M d, Y'); ?></span> 
            <?php comment_reply_link(array_merge( $args, array('reply_text' => 'reply', 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>         
        </aside>
	</div>
<?php } 
function list_pings($comment, $args, $depth) {
$GLOBALS['comment'] = $comment;
?>
<li id="comment-<?php comment_ID(); ?>"><?php comment_author_link(); ?>
<?php } ?>