<?php 
/**
 * Textarea Option
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
function option_tree_textarea( $value, $settings ) 
{ 
?>
  <div class="option option-textarea">
    <h3><?php echo htmlspecialchars_decode( $value[item_title] ); ?></h3>
    <div class="section">
      <div class="element">
        <textarea name="<?php echo $value[item_id]; ?>" rows="10"><?php 
          if ( isset( $settings[$value[item_id]] ) ) 
            echo $settings[$value[item_id]];
          ?></textarea>
      </div>
      <div class="description">
        <?php echo htmlspecialchars_decode( $value[item_desc] ); ?>
      </div>
    </div>
  </div>
<?php
}