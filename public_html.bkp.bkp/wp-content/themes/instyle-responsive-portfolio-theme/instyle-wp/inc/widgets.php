<?php
// instyle Tag Cloud Size
function tag_cloud_filter($args = array()) {
   $args['smallest'] = 12;
   $args['largest'] = 12;
   $args['unit'] = 'px';
   $args['format']= 'list';
   return $args;
}

add_filter('widget_tag_cloud_args', 'tag_cloud_filter', 90);

// instyle Twitter Widget
class widget_instyletwitter extends WP_Widget { 
	function widget_instyletwitter() {
		/* Widget settings. */
		$widget_ops = array('description' => __('Display Your Tweets') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'instyletwitter' );

		/* Create the widget. */
		$this->WP_Widget( 'instyletwitter', __('instyle - Twitter'), $widget_ops, $control_ops );
	}
	function widget($args, $instance) {
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		$account = $instance['account'];
		$show = $instance['show'];
		
        // Output
		echo $before_widget;
		echo $before_title;
		echo $title;
		echo $after_title;
		echo '<ul id="twitter_update_list"><li>Oops Twitter isnt working at the moment</li></ul>';
		echo '<script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>';
		echo '<script type="text/javascript" src="http://twitter.com/statuses/user_timeline/'.$account.'.json?callback=twitterCallback2&amp;count='.$show.'"></script>';
		echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {  
		$instance = $old_instance; 
		
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['show'] = strip_tags( $new_instance['show'] );
		$instance['account'] = strip_tags( $new_instance['account'] );

		return $instance;
	}
	function form($instance) {
		$defaults = array( 'title' => 'Follow Us on Twitter', 'show' => '4', 'account' => 'anteksiler' );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
        
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Widget Title:</label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
        <p>
			<label for="<?php echo $this->get_field_id( 'account' ); ?>">Twitter Account:</label>
			<input id="<?php echo $this->get_field_id( 'account' ); ?>" name="<?php echo $this->get_field_name( 'account' ); ?>" value="<?php echo $instance['account']; ?>" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'show' ); ?>">Number of Tweets:</label>
			<input id="<?php echo $this->get_field_id( 'show' ); ?>" name="<?php echo $this->get_field_name( 'show' ); ?>" value="<?php echo $instance['show']; ?>" style="width:100%;" />
		</p>
    <?php
	}
}
function widget_instyletwitter_init()
{
	register_widget('widget_instyletwitter');
}
add_action('widgets_init', 'widget_instyletwitter_init');

// instyle Flickr Widget
class widget_instyleflickr extends WP_Widget { 
	function widget_instyleflickr() {
		/* Widget settings. */
		$widget_ops = array('description' => __('Display Your Flickr Images') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'flickr' );

		/* Create the widget. */
		$this->WP_Widget( 'flickr', __('instyle - Flickr'), $widget_ops, $control_ops );
	}
	
	function widget($args, $instance) {
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		$account = $instance['account'];
		$show = $instance['show'];
		
		// Output
		echo $before_widget;
		echo $before_title;
		echo $title;
		echo $after_title;

		echo '<script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count='.$show.'&amp;display=latest&amp;size=s&amp;layout=x&amp;source=user&amp;user='.$account.'"></script>';		
		echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {  
		$instance = $old_instance; 
		
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['show'] = strip_tags( $new_instance['show'] );
		$instance['account'] = strip_tags( $new_instance['account'] );

		return $instance;
	}
	// Settings form
	function form($instance) {
		$defaults = array( 'title' => 'Flickr', 'show' => '10', 'account' => '25062265@N06' );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
        
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Widget Title:</label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
        <p>
			<label for="<?php echo $this->get_field_id( 'account' ); ?>">Flickr Account:</label>
			<input id="<?php echo $this->get_field_id( 'account' ); ?>" name="<?php echo $this->get_field_name( 'account' ); ?>" value="<?php echo $instance['account']; ?>" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'show' ); ?>">Number of Images:</label>
			<input id="<?php echo $this->get_field_id( 'show' ); ?>" name="<?php echo $this->get_field_name( 'show' ); ?>" value="<?php echo $instance['show']; ?>" style="width:100%;" />
		</p>
    <?php
	}
}
function widget_instyleflickr_init()
{
	register_widget('widget_instyleflickr');
}
add_action('widgets_init', 'widget_instyleflickr_init');

// instyle Contact Info Widget
class widget_instylecontactinfo extends WP_Widget {

	function widget_instylecontactinfo() {
		/* Widget settings. */
		$widget_ops = array('description' => __('Display Your Contact Info') );
		
		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'contact_info' );
		/* Create the widget. */
		$this->WP_Widget('contact_info', __('instyle - Contact Info'), $widget_ops, $control_ops );
	}
	
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title']);
		$text = $instance['text'];
		$phone = $instance['phone'];
		$cellphone = $instance['cellphone'];
		$email= $instance['email'];
		$address = $instance['address'];
		$name = $instance['name'];
		
		// Output
		echo $before_widget;
		echo $before_title;
echo $title;
echo $after_title;
  
		
		?>
			<div class="contact-info">
			<?php if(!empty($text)):?><p><span class="text"><?php echo $text;?></span></p><?php endif;?>
			<?php if(!empty($phone)):?><p><span class="icon-phone"><?php echo $phone;?></span></p><?php endif;?>
			<?php if(!empty($cellphone)):?><p><span class="icon-cellphone"><?php echo $cellphone;?></span></p><?php endif;?>
			<?php if(!empty($email)):?><p><span class="icon-mail"><a href="mailto:<?php echo $email;?>"><?php echo $email;?></a></span></p><?php endif;?>
			<?php if(!empty($address)):?><p><span class="icon-address"><?php echo $address;?></span></p><?php endif;?>
			<?php if(!empty($name)):?><p><span class="icon-name"><?php echo $name;?></span></p><?php endif;?>
			</div>
		<?php
		echo $after_widget;

	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);
		$instance['text'] = strip_tags($new_instance['text']);
		$instance['phone'] = strip_tags($new_instance['phone']);
		$instance['cellphone'] = strip_tags($new_instance['cellphone']);
		$instance['email'] = strip_tags($new_instance['email']);
		$instance['address'] = strip_tags($new_instance['address']);
		$instance['name'] = strip_tags($new_instance['name']);
		
		return $instance;
	}

	function form( $instance ) {
		//Defaults
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$text = isset($instance['text']) ? esc_attr($instance['text']) : '';
		$phone = isset($instance['phone']) ? esc_attr($instance['phone']) : '';
		$cellphone = isset($instance['cellphone']) ? esc_attr($instance['cellphone']) : '';
		$email = isset($instance['email']) ? esc_attr($instance['email']) : '';
		$address = isset($instance['address']) ? esc_attr($instance['address']) : '';
		$name = isset($instance['name']) ? esc_attr($instance['name']) : '';
	?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Widget Title:</label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
        <p>
			<label for="<?php echo $this->get_field_id( 'text' ); ?>">Text:</label>
			<input id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" value="<?php echo $instance['text']; ?>" style="width:100%;" />
		</p>
        <p>
			<label for="<?php echo $this->get_field_id( 'phone' ); ?>">Phone:</label>
			<input id="<?php echo $this->get_field_id( 'phone' ); ?>" name="<?php echo $this->get_field_name( 'phone' ); ?>" value="<?php echo $instance['phone']; ?>" style="width:100%;" />
		</p>
        <p>
			<label for="<?php echo $this->get_field_id( 'cellphone' ); ?>">Cellular Phone:</label>
			<input id="<?php echo $this->get_field_id( 'cellphone' ); ?>" name="<?php echo $this->get_field_name( 'cellphone' ); ?>" value="<?php echo $instance['cellphone']; ?>" style="width:100%;" />
		</p>
        <p>
			<label for="<?php echo $this->get_field_id( 'email' ); ?>">Email:</label>
			<input id="<?php echo $this->get_field_id( 'email' ); ?>" name="<?php echo $this->get_field_name( 'email' ); ?>" value="<?php echo $instance['email']; ?>" style="width:100%;" />
		</p>
        <p>
			<label for="<?php echo $this->get_field_id( 'address' ); ?>">Address:</label>
			<input id="<?php echo $this->get_field_id( 'address' ); ?>" name="<?php echo $this->get_field_name( 'address' ); ?>" value="<?php echo $instance['address']; ?>" style="width:100%;" />
		</p>
        <p>
			<label for="<?php echo $this->get_field_id( 'name' ); ?>">Name:</label>
			<input id="<?php echo $this->get_field_id( 'name' ); ?>" name="<?php echo $this->get_field_name( 'name' ); ?>" value="<?php echo $instance['name']; ?>" style="width:100%;" />
		</p>
		
<?php
	}

}
function widget_instylecontactinfo_init()
{
	register_widget('widget_instylecontactinfo');
}
add_action('widgets_init', 'widget_instylecontactinfo_init');

// instyle Sponsor Widget
class widget_instylesponsor extends WP_Widget { 
	function widget_instylesponsor() {
		/* Widget settings. */
		$widget_ops = array( 'description' => __('Display 220x120 banners on your site') );
	
		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'sponsor' );
	
		/* Create the widget. */
		$this->WP_Widget( 'sponsor', __('instyle - Sponsor Banners'), $widget_ops, $control_ops );
	}
	
	function widget($args, $instance) {
		extract($args);
		
		$title = apply_filters('widget_title', $instance['title']);

		$soneurl = strip_tags( $instance['sponsoroneurl'] );
		$soneimg = strip_tags( $instance['sponsoroneimg'] );
		
		$stwourl = strip_tags( $instance['sponsortwourl'] );
		$stwoimg = strip_tags( $instance['sponsortwoimg'] );
		
		$sthreeurl = strip_tags( $instance['sponsorthreeurl'] );
		$sthreeimg = strip_tags( $instance['sponsorthreeimg'] );
		
		$sfoururl = strip_tags( $instance['sponsorfoururl'] );
		$sfourimg = strip_tags( $instance['sponsorfourimg'] );
		
		// Output
		echo $before_widget;
		echo $before_title;
		echo $title;
		echo $after_title;

			echo '<div class="sponsors">';
			if (!empty($soneimg)) { echo '<a href="'.$soneurl.'"><img src="'.$soneimg.'" /></a>';}
			if (!empty($stwoimg)) { echo '<a href="'.$stwourl.'"><img src="'.$stwoimg.'" /></a>';}
			if (!empty($sthreeimg)) { echo '<a href="'.$sthreeurl.'"><img src="'.$sthreeimg.'" /></a>';}
			if (!empty($sfourimg)) { echo '<a href="'.$sfoururl.'"><img src="'.$sfourimg.'" /></a>';}
			echo '</div>';
		echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {  
		$instance = $old_instance; 
		
		/* Strip tags (if needed) and update the widget settings. */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['sponsoroneurl'] = strip_tags( $new_instance['sponsoroneurl'] );
		$instance['sponsoroneimg'] = strip_tags( $new_instance['sponsoroneimg'] );
		
		$instance['sponsortwourl'] = strip_tags( $new_instance['sponsortwourl'] );
		$instance['sponsortwoimg'] = strip_tags( $new_instance['sponsortwoimg'] );
		
		$instance['sponsorthreeurl'] = strip_tags( $new_instance['sponsorthreeurl'] );
		$instance['sponsorthreeimg'] = strip_tags( $new_instance['sponsorthreeimg'] );
		
		$instance['sponsorfoururl'] = strip_tags( $new_instance['sponsorfoururl'] );
		$instance['sponsorfourimg'] = strip_tags( $new_instance['sponsorfourimg'] );

		return $instance;
	}
	function form($instance) {
		$img = 'http://placehold.it/220x120';
		$defaults = array('title' => 'Sponsors', 'sponsoroneurl' => '#', 'sponsortwourl' => '#', 'sponsorthreeurl' => '#', 'sponsorfoururl' => '#', 'sponsoroneimg' => $img, 'sponsortwoimg' => $img, 'sponsorthreeimg' => $img, 'sponsorfourimg' => $img);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
        
        <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>">Widget Title:</label>
        <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
        
		<p>
<label for="<?php echo $this->get_field_id( 'sponsoroneurl' ); ?>">Sponsor 1 URL:</label>
<input id="<?php echo $this->get_field_id( 'sponsoroneurl' ); ?>" name="<?php echo $this->get_field_name( 'sponsoroneurl' ); ?>" value="<?php echo $instance['sponsoroneurl']; ?>" style="width:100%;" />
<label for="<?php echo $this->get_field_id( 'sponsoroneimg' ); ?>">Sponsor 1 Image:</label>
<input id="<?php echo $this->get_field_id( 'sponsoroneimg' ); ?>" name="<?php echo $this->get_field_name( 'sponsoroneimg' ); ?>" value="<?php echo $instance['sponsoroneimg']; ?>" style="width:100%;" />
		</p>

		<p>
<label for="<?php echo $this->get_field_id( 'sponsortwourl' ); ?>">Sponsor 2:</label>
<input id="<?php echo $this->get_field_id( 'sponsortwourl' ); ?>" name="<?php echo $this->get_field_name( 'sponsortwourl' ); ?>" value="<?php echo $instance['sponsortwourl']; ?>" style="width:100%;" />
<label for="<?php echo $this->get_field_id( 'sponsortwoimg' ); ?>">Sponsor 2 Image:</label>
<input id="<?php echo $this->get_field_id( 'sponsortwoimg' ); ?>" name="<?php echo $this->get_field_name( 'sponsortwoimg' ); ?>" value="<?php echo $instance['sponsortwoimg']; ?>" style="width:100%;" />
		</p>
        
        <p>
<label for="<?php echo $this->get_field_id( 'sponsorthreeurl' ); ?>">Sponsor 3:</label>
<input id="<?php echo $this->get_field_id( 'sponsorthreeurl' ); ?>" name="<?php echo $this->get_field_name( 'sponsorthreeurl' ); ?>" value="<?php echo $instance['sponsorthreeurl']; ?>" style="width:100%;" />
<label for="<?php echo $this->get_field_id( 'sponsorthreeimg' ); ?>">Sponsor 3 Image:</label>
<input id="<?php echo $this->get_field_id( 'sponsorthreeimg' ); ?>" name="<?php echo $this->get_field_name( 'sponsorthreeimg' ); ?>" value="<?php echo $instance['sponsorthreeimg']; ?>" style="width:100%;" />
		</p>
        
        <p>
<label for="<?php echo $this->get_field_id( 'sponsorfoururl' ); ?>">Sponsor 4:</label>
<input id="<?php echo $this->get_field_id( 'sponsorfoururl' ); ?>" name="<?php echo $this->get_field_name( 'sponsorfoururl' ); ?>" value="<?php echo $instance['sponsorfoururl']; ?>" style="width:100%;" />
<label for="<?php echo $this->get_field_id( 'sponsorfourimg' ); ?>">Sponsor 4 Image:</label>
<input id="<?php echo $this->get_field_id( 'sponsorfourimg' ); ?>" name="<?php echo $this->get_field_name( 'sponsorfourimg' ); ?>" value="<?php echo $instance['sponsorfourimg']; ?>" style="width:100%;" />
		</p>
    <?php
	}
}
function widget_instylesponsor_init()
{
	register_widget('widget_instylesponsor');
}
add_action('widgets_init', 'widget_instylesponsor_init');

// instyle Post Format Widget
class instyle_Post_Formats extends WP_Widget {

	function instyle_Post_Formats() {
		/* Widget settings. */
		$widget_ops = array( 'description' => __('A list or dropdown of Post Formats') );
	
		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'post_formats' );
	
		/* Create the widget. */
		$this->WP_Widget( 'post_formats', __('instyle - Post Formats'), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'Post Formats' ) : $instance['title'], $instance, $this->id_base);
		$c = $instance['count'] ? '1' : '0';
		$d = $instance['dropdown'] ? '1' : '0';
		$f = $instance['format_id'] ? '1' : '0';

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;

		if ( $d ) { ?>

		<select id="post-format-dropdown" class="postform" name="post-format-dropdown">
		<option value="null">Select Post Format</option>
<?php			
		foreach ( get_post_format_strings() as $slug => $string ) {
			if ( get_post_format_link($slug) ) {
				$post_format = get_term_by( 'slug', 'post-format-' . $slug, 'post_format' );
				if ( $post_format->count > 0 ) {
					$count = $c ? ' (' . $post_format->count . ')' : '';
					$format_id = $f ? ' (ID: ' . $post_format->term_id . ')' : '';
					echo '<option class="level-0" value="' . $slug . '">' . $string . $count . $format_id . '</option>';
				}
			}
		} ?>
		</select>

<script type='text/javascript'>
/* <![CDATA[ */
	var pfDropdown = document.getElementById("post-format-dropdown");
	function onFormatChange() {
		if ( pfDropdown.options[pfDropdown.selectedIndex].value != 'null' ) {
			location.href = "<?php echo home_url(); ?>/?post_format="+pfDropdown.options[pfDropdown.selectedIndex].value;
		}
	}
	pfDropdown.onchange = onFormatChange;
/* ]]> */
</script>

<?php
		} else {
?>
		<ul>
<?php
		$tooltip = empty( $instance['tooltip'] ) ? __( 'View all %format posts' ) : esc_attr($instance['tooltip']);
		foreach ( get_post_format_strings() as $slug => $string ) {
			if ( get_post_format_link($slug) ) {
				$post_format = get_term_by( 'slug', 'post-format-' . $slug, 'post_format' );
				if ( $post_format->count > 0 ) {
					$count = $c ? ' (' . $post_format->count . ')' : '';
					$format_id = $f ? ' (ID: ' . $post_format->term_id . ')' : '';
					echo '<li class="post-format-item"><a title="' . str_replace('%format', $string, $tooltip) . '" href="' . get_post_format_link($slug) . '">' . $string . ' ' . $count . '</a></li>';
				}
			}
		}
?>
		</ul>
<?php
		}

		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['tooltip'] = strip_tags($new_instance['tooltip']);
		$instance['count'] = !empty($new_instance['count']) ? 1 : 0;
		$instance['dropdown'] = !empty($new_instance['dropdown']) ? 1 : 0;
		$instance['format_id'] = !empty($new_instance['format_id']) ? 1 : 0;

		return $instance;
	}

	function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = !empty($instance['title']) ? esc_attr( $instance['title'] ) : 'Post Formats';
		$tooltip = !empty($instance['tooltip']) ? esc_attr( $instance['tooltip'] ) : 'View all %format posts';
		$count = isset($instance['count']) ? (bool) $instance['count'] : false;
		$dropdown = isset( $instance['dropdown'] ) ? (bool) $instance['dropdown'] : false;
		$format_id = isset( $instance['format_id'] ) ? (bool) $instance['format_id'] : false;
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('tooltip'); ?>">Tooltip:</label>
		<input class="widefat" id="<?php echo $this->get_field_id('tooltip'); ?>" name="<?php echo $this->get_field_name('tooltip'); ?>" type="text" value="<?php echo $tooltip; ?>" /></p>

		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('dropdown'); ?>" name="<?php echo $this->get_field_name('dropdown'); ?>"<?php checked( $dropdown ); ?> />
		<label for="<?php echo $this->get_field_id('dropdown'); ?>">Display as dropdown</label><br />

		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>"<?php checked( $count ); ?> />
		<label for="<?php echo $this->get_field_id('count'); ?>">Show post counts</label></p>
<?php
	}

}

function instyle_Post_Formats_init() {
	register_widget('instyle_Post_Formats');
}

add_action('widgets_init', 'instyle_Post_Formats_init');
?>