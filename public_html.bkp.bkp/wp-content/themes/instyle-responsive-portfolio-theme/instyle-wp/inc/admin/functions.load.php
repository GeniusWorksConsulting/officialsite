<?php 
if ( is_admin() )
{
  include( 'functions/heading.php' );
  include( 'functions/input.php' );
  include( 'functions/checkbox.php' );
  include( 'functions/radio.php' );
  include( 'functions/select.php' );
  include( 'functions/select-posts.php' );
  include( 'functions/textarea.php' );
  include( 'functions/upload.php' );
  include( 'functions/colorpicker.php' );
  include( 'functions/textblock.php' );
  include( 'functions/post.php' );
  include( 'functions/page.php' );
  include( 'functions/category.php' );
  include( 'functions/tag.php' );
  include( 'functions/custom-post.php' );
}

  include( 'get-option-tree.php' );
?>