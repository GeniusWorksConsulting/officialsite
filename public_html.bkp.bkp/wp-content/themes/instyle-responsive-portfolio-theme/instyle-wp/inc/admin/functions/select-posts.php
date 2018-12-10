<?php 
/**
 * Select Option for slides
 *
 * @access public
 * @since 1.0.0
 *
 * @param array $value
 * @param array $settings
 * @param int $int
 *
 * @return string
 */
function option_tree_select_posts( $value, $settings ) 
{ 
?>
  <div class="option option-select">
    <h3><?php echo htmlspecialchars_decode( $value[item_title] ); ?></h3>
    <div class="section">
      <div class="element">
        <?php $int2 = $value[post_type]; ?>
        <?php 
        global $post;
		$args = array( 'numberposts' => -1, 'post_type' => $int2, 'post_status' => 'publish' );
		$int = get_posts( $args );
		?>
        <div class="select_wrapper">
          <select name="<?php echo $value[item_id]; ?>" id="<?php echo $value[item_id]; ?>" class="select">
          <?php
          echo '<option value="">-- Choose One --</option>';
          foreach ( $int as $option ) 
          {
			$option2 = $option->post_title;
            $selected = '';
  	        if ( $settings[$value[item_id]] == trim( $option2 ) ) 
  	        { 
              $selected = ' selected="selected"'; 
            }
  	        echo '<option'.$selected.'>'.trim( $option2 ).'</option>';
       	  } 
          ?>
          </select>
        </div>
      </div>
      <div class="description">
        <?php echo htmlspecialchars_decode( $value[item_desc] ); ?>
      </div>
    </div>
  </div>
<?php
}