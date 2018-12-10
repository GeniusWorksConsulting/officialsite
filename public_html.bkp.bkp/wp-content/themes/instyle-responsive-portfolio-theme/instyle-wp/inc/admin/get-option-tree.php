<?php 
function get_option_tree( $item_id, $item_default ) 
{  
  // load saved options
  $options = get_option( 'instyle_theme' );

  // set the item
  if ( !isset( $options[$item_id] ) || $options[$item_id] == "" ) {	
	 return $item_default;
  }
  
  // single item value  
  $content = $options[$item_id];
  
  return $content;
}
?>