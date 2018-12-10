<?php
/**
 * WPSC_GridView_Hook_Filter
 *
 * Class Function into WP e-Commerce plugin
 *
 * Table Of Contents
 *
 * wpsc_grid_view_styles();
 * wpsc_grid_custom_styles();
 * product_display_list();
 * product_display_grid();
 * wpsc_product_image();
 * wpsc_category_image();
 * wpsc_update_category_image();
 * plugin_extra_links();
 */
class WPSC_GridView_Hook_Filter
{
	public static function wpsc_grid_view_styles() {
		global $wp_query;
		$wpsc_gc_view_mode = get_option('product_view');
		if ( $wpsc_gc_view_mode == 'grid' ){
			wp_register_style( 'wpsc-grid-view', WPSC_GRID_VIEW_URL . '/assets/css/gridview.css' );
        	wp_enqueue_style( 'wpsc-grid-view' );
			
			// Masonry Script
			wp_enqueue_script( 'jquery-masonry');
			global $is_IE;
			if($is_IE){ wp_enqueue_script( 'respondjs', WPSC_GRID_VIEW_URL . '/assets/js/respond-ie.js' ); }
			
		}
	}
	
	public static function wpsc_grid_custom_styles() {
		global $wp_query;
		
		$items_per_row = get_option( 'grid_number_per_row', 0 );
		if ( $items_per_row < 1 || $items_per_row > 5 ) $items_per_row = 2;
		$wpsc_gc_view_mode = get_option('product_view');
		if ( $wpsc_gc_view_mode != 'grid' ) return;
		if ( $items_per_row > 0 ) {
			
			global $wp_version;
			$cur_wp_version = preg_replace('/-.*$/', '', $wp_version);
		
			// roughly calculate the percentage, this will be corrected with JS later
			//$percentage = floor( 100 / $items_per_row ) - 2;
			//$percentage = apply_filters( 'wpsc_grid_view_column_width', $percentage, $items_per_row ); // themes can override this calculation
			?>
			<style type="text/css">
				.grid_view_clearboth, .wpec-grid-sizer {
					position:absolute;	
					margin-right:2%;
				}
				<?php if ( $items_per_row == 2 ) { ?>
				.grid_view_clearboth, .wpec-grid-sizer {
					width:48%;
				}
				#grid_view_products_page_container .product_grid_display, .product_grid_display{width:102%;}
				#grid_view_products_page_container .product_grid_display .product_grid_item, .product_grid_display .product_grid_item{width:48%;}
				
				#grid_view_products_page_container .wpsc_category_grid, .wpsc_category_grid{width:102%;}
				#grid_view_products_page_container .wpsc_category_grid .wpsc_category_grid_item, .wpsc_category_grid .wpsc_category_grid_item{width:48%;}
				<?php } elseif ( $items_per_row == 3 ) { ?>
				.grid_view_clearboth, .wpec-grid-sizer {
					width:31%;
				}
				#grid_view_products_page_container .product_grid_display, .product_grid_display{width:103%;}
				#grid_view_products_page_container .product_grid_display .product_grid_item, .product_grid_display .product_grid_item{width:31%;}
				
				#grid_view_products_page_container .wpsc_category_grid, .wpsc_category_grid{width:103%;}
				#grid_view_products_page_container .wpsc_category_grid .wpsc_category_grid_item, .wpsc_category_grid .wpsc_category_grid_item{width:31%;}
				<?php } elseif ( $items_per_row == 4 ) { ?>
				.grid_view_clearboth, .wpec-grid-sizer {
					width:23%;
				}
				#grid_view_products_page_container .product_grid_display, .product_grid_display{width:102%;}
				#grid_view_products_page_container .product_grid_display .product_grid_item, .product_grid_display .product_grid_item{width:23%;}
				
				#grid_view_products_page_container .wpsc_category_grid, .wpsc_category_grid{width:102%;}
				#grid_view_products_page_container .wpsc_category_grid .wpsc_category_grid_item, .wpsc_category_grid .wpsc_category_grid_item{width:23%;}
				<?php } elseif ( $items_per_row == 5 ) { ?>
				.grid_view_clearboth, .wpec-grid-sizer {
					width:18%;
				}
				#grid_view_products_page_container .product_grid_display, .product_grid_display{width:102%;}
				#grid_view_products_page_container .product_grid_display .product_grid_item, .product_grid_display .product_grid_item{width:18%;}
				
				#grid_view_products_page_container .wpsc_category_grid, .wpsc_category_grid{width:102%;}
				#grid_view_products_page_container .wpsc_category_grid .wpsc_category_grid_item, .wpsc_category_grid .wpsc_category_grid_item{width:18%;}
				<?php } else { ?>
				#grid_view_products_page_container .product_grid_display .product_grid_item, .product_grid_display .product_grid_item{margin-right:0 !important;}
				#grid_view_products_page_container .wpsc_category_grid .wpsc_category_grid_item, .wpsc_category_grid .wpsc_category_grid_item{margin-right:0 !important;}
				<?php } ?>
				
				.product_grid_display .item_image a {
					height: <?php echo get_option( 'product_image_height' ); ?>px;
					/*width: <?php //echo get_option( 'product_image_width' ); ?>px;*/
					line-height: <?php echo (get_option('product_image_height') - 4); ?>px;
					text-align:center;
					vertical-align:middle;
				}
				#grid_view_products_page_container .item_image img, .item_image img{
					height:auto !important;	
					display:table-cell;
					vertical-align:middle;
					margin:auto !important;
					border:none;
				}
				#grid_view_products_page_container  .wpsc_category_grid_item, .wpsc_category_grid_item {
					display: block;
					line-height: <?php echo (get_option('category_image_width') - 4); ?>px;
					text-align:center;
					vertical-align:middle;
				}
				#grid_view_products_page_container img.wpsc_category_image, img.wpsc_category_image{
					width:auto !important;
					height:auto !important;	
					display:table-cell;
					vertical-align:middle;
					margin:auto !important;
					border:none;
				}
			</style>
            
			<script type="text/javascript">
            jQuery(window).load(function(){
                var grid_view_col_1 = <?php echo $items_per_row;?>;
                var screen_width = jQuery('body').width(); 
				if (screen_width <= 750 && screen_width >= 481 ) {
					grid_view_col_1 = 2;
				}
				jQuery('.product_grid_display').append('<div class="grid_view_clearboth"></div>');
                jQuery('.product_grid_display').imagesLoaded(function(){
                    jQuery('.product_grid_display').masonry({
                        itemSelector: '.product_grid_item',
						<?php if ( version_compare( $cur_wp_version, '3.9', '<' ) ) { ?>
						columnWidth: jQuery('.product_grid_display').width()/grid_view_col_1
						<?php } else { ?>
						columnWidth: '.grid_view_clearboth'
						<?php } ?>
                    });
                });
            });
            jQuery(window).resize(function() {
                var grid_view_col_2 = <?php echo $items_per_row;?>;
                var screen_width = jQuery('body').width(); 
				if (screen_width <= 750 && screen_width >= 481 ) {
					grid_view_col_2 = 2;
				}
                jQuery('.product_grid_display').imagesLoaded(function(){
                    jQuery('.product_grid_display').masonry({
                        itemSelector: '.product_grid_item',
						<?php if ( version_compare( $cur_wp_version, '3.9', '<' ) ) { ?>
						columnWidth: jQuery('.product_grid_display').width()/grid_view_col_2
						<?php } else { ?>
						columnWidth: '.grid_view_clearboth'
						<?php } ?>
                    });
                });
            });
			<?php if (get_option('wpsc_category_grid_view') == 1) { ?>
			jQuery(window).load(function(){
                var grid_view_col_3 = <?php echo $items_per_row;?>;
                var screen_width = jQuery('body').width(); 
				if (screen_width <= 750 && screen_width >= 481 ) {
					grid_view_col_3 = 2;
				}
				jQuery('.wpsc_category_grid').prepend('<div class="wpec-grid-sizer"></div>');
                jQuery('.wpsc_category_grid').imagesLoaded(function(){
                    jQuery('.wpsc_category_grid').masonry({
                        itemSelector: '.wpsc_category_grid_item',
						<?php if ( version_compare( $cur_wp_version, '3.9', '<' ) ) { ?>
						columnWidth: jQuery('.wpsc_category_grid').width()/grid_view_col_3
						<?php } else { ?>
						columnWidth: '.wpec-grid-sizer'
						<?php } ?>
                    });
                });
            });
			jQuery(window).resize(function() {
                var grid_view_col_4 = <?php echo $items_per_row;?>;
                var screen_width = jQuery('body').width(); 
				if (screen_width <= 750 && screen_width >= 481 ) {
					grid_view_col_4 = 2;
				}
                jQuery('.wpsc_category_grid').imagesLoaded(function(){
                    jQuery('.wpsc_category_grid').masonry({
                        itemSelector: '.wpsc_category_grid_item',
						<?php if ( version_compare( $cur_wp_version, '3.9', '<' ) ) { ?>
						columnWidth: jQuery('.wpsc_category_grid').width()/grid_view_col_4
						<?php } else { ?>
						columnWidth: '.wpec-grid-sizer'
						<?php } ?>
                    });
                });
            });
			<?php } ?>
            </script>
        <?php
		}
	}
	
	public static function browser_body_class( $classes, $class = '' ) {
		global $wp_query;
		if ( !is_array($classes) ) $classes = array();
		
		if ( 'wpsc-product' == $wp_query->post->post_type && !is_archive() && $wp_query->post_count <= 1 ) return $classes;
		if ( !is_products_page() && !is_tax( 'wpsc_product_category' ) && !is_tax('product_tag') ) return $classes;
		
		global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
 
		if($is_lynx) $classes[] = 'lynx';
		elseif($is_gecko) $classes[] = 'gecko';
		elseif($is_opera) $classes[] = 'opera';
		elseif($is_NS4) $classes[] = 'ns4';
		elseif($is_safari) $classes[] = 'safari';
		elseif($is_chrome) $classes[] = 'chrome';
		elseif($is_IE) {
			$browser = $_SERVER['HTTP_USER_AGENT'];
			$browser = substr( "$browser", 25, 8);
			if ($browser == "MSIE 7.0"  ) {
				$classes[] = 'ie7';
				$classes[] = 'ie';
			} elseif ($browser == "MSIE 6.0" ) {
				$classes[] = 'ie6';
				$classes[] = 'ie';
			} elseif ($browser == "MSIE 8.0" ) {
				$classes[] = 'ie8';
				$classes[] = 'ie';
			} elseif ($browser == "MSIE 9.0" ) {
				$classes[] = 'ie9';
				$classes[] = 'ie';
			} else {
				$classes[] = 'ie';
			}
		} else { $classes[] = 'unknown'; }
 
		if( $is_iphone ) $classes[] = 'iphone';
				
		return $classes;
	}
	
	public static function product_display_grid($product_list, $group_type, $group_sql = '', $search_sql = '') {
		global $wpdb;
		/*
		All this does is sit here so that it can be detected by the gold files to turn grid view on.
		*/  
	}  
	
	public static function product_display_list($product_list, $group_type, $group_sql = '', $search_sql = '') {
		global $wpdb;
		$siteurl = get_option('siteurl');
		
		if ( (float)WPSC_VERSION < 3.8 )
			$images_dir = 'images';
		else
			$images_dir = 'wpsc-core/images';
		  
		if(get_option('permalink_structure') != '') {
			$seperator ="?";
		} else {
			$seperator ="&amp;";
		}
		
		$product_listing_data = wpsc_get_product_listing($product_list, $group_type, $group_sql, $search_sql);
		
		$product_list = $product_listing_data['product_list'];
		
		$output .= $product_listing_data['page_listing'];
		if($product_listing_data['category_id']) {
			$category_nice_name = $wpdb->get_var("SELECT `nice-name` FROM `".WPSC_TABLE_PRODUCT_CATEGORIES."` WHERE `id` ='".(int)$product_listing_data['category_id']."' LIMIT 1");
		} else {
			$category_nice_name = '';
		}
		  
		if($product_list != null) {
			$output .= "<table class='list_productdisplay $category_nice_name'>";
			$i=0;
			foreach($product_list as $product) {
				$num++;
				if ($i%2 == 1) {
					$output .= "    <tr class='product_view_{$product['id']}'>";
				} else {
					$output .= "    <tr class='product_view_{$product['id']}' style='background-color:#EEEEEE'>";
				}
				$i++;
				$output .= "      <td style='width: 9px;'>";
				if($product['description'] != null) {
					$output .= "<a href='#' class='additional_description_link' onclick='return show_additional_description(\"list_description_".$product['id']."\",\"link_icon".$product['id']."\");'>";
					$output .= "<img style='margin-top:3px;' id='link_icon".$product['id']."' src='$siteurl/wp-content/plugins/".WPSC_DIR_NAME."/".$images_dir."/icon_window_expand.gif' title='".$product['name']."' alt='".$product['name']."' />";
					$output .= "</a>";
				}
				$output .= "      </td>\n\r";
				$output .= "      <td width='55%'>";
			
				if($product['special'] == 1) {
					$special = "<strong class='special'>".TXT_WPSC_SPECIAL." - </strong>";
				} else {
					$special = "";
				}
				$output .= "<a href='".wpsc_product_url($product['id'])."' class='wpsc_product_title' ><strong>" . stripslashes($product['name']) . "</strong></a>";
				$output .= "      </td>";
				$variations_procesor = new nzshpcrt_variations;
	
				$variations_output = $variations_procesor->display_product_variations($product['id'],false, false, true);
				if($variations_output[1] !== null) {
					$product['price'] = $variations_output[1];
				}
				$output .= "      <td width='10px' style='text-align: center;'>";
				if(($product['quantity'] < 1) && ($product['quantity_limited'] == 1)) {
					$output .= "<img style='margin-top:5px;' src='$siteurl/wp-content/plugins/".WPSC_DIR_NAME."/".$images_dir."/no_stock.gif' title='No' alt='No' />";
				} else {
					$output .= "<img style='margin-top:4px;' src='$siteurl/wp-content/plugins/".WPSC_DIR_NAME."/".$images_dir."/yes_stock.gif' title='Yes' alt='Yes' />";
				}
				$output .= "      </td>";
				$output .= "      <td width='10%'>";
				if(($product['special']==1) && ($variations_output[1] === null)) {
					$output .= nzshpcrt_currency_display(($product['price'] - $product['special_price']), $product['notax'],false,$product['id']) . "<br />";
				} else {
					$output .= "<span id='product_price_".$product['id']."'>".nzshpcrt_currency_display($product['price'], $product['notax'])."</span>";
				}
				$output .= "      </td>";
	
				$output .= "      <td width='20%'>";
				if (get_option('addtocart_or_buynow') == '0'){
					$output .= "<form name='$num'  id='product_".$product['id']."'  method='POST' action='".get_option('product_list_url').$seperator."category=".$_GET['category']."' onsubmit='submitform(this);return false;' >";
				}
				if(get_option('list_view_quantity') == 1) {
					$output .= "<input type='text' name='quantity' value='1' size='3' maxlength='3'>&nbsp;";
				}
				$output .= $variations_output[0];
				$output .= "<input type='hidden' name='item' value='".$product['id']."' />";
				$output .= "<input type='hidden' name='prodid' value='".$product['id']."'>";
				if (get_option('wpsc_selected_theme')=='iShop') {
					if (get_option('addtocart_or_buynow') == '0') {
						if(($product['quantity_limited'] == 1) && ($product['quantity'] < 1)) {
							$output .= "<input disabled='true' type='submit' value='' name='".__('Buy', 'wp-e-commerce-grid-view' )."' class='wpsc_buy_button'/>";
						} else {
							$output .= "<input type='submit' name='".__('Buy', 'wp-e-commerce-grid-view' )."' value='' class='wpsc_buy_button'/>";
						}
					} else {
						if(!(($product['quantity_limited'] == 1) && ($product['quantity'] < 1))){
							$output .= google_buynow($product['id']);
						}
					}
				} else {
					if (get_option('addtocart_or_buynow') == '0') {
						if(($product['quantity_limited'] == 1) && ($product['quantity'] < 1)) {
							$output .= "<input disabled='true' type='submit' name='".__('Buy', 'wp-e-commerce-grid-view' )."' class='wpsc_buy_button'  value='".TXT_WPSC_ADDTOCART."'  />";
						} else {
							$output .= "<input type='submit' name='".__('Buy', 'wp-e-commerce-grid-view' )."' class='wpsc_buy_button'  value='".TXT_WPSC_ADDTOCART."'  />";
						}
					} else {
						if(!(($product['quantity_limited'] == 1) && ($product['quantity'] < 1))){
							$output .= google_buynow($product['id']);
						}
					}
				}
				$output .= "</form>";
				$output .= "      </td>\n\r";
				$output .= "    </tr>\n\r";
				
				$output .= "    <tr class='list_view_description'>\n\r";
				$output .= "      <td colspan='5'>\n\r";
				$output .= "        <div id='list_description_".$product['id']."'>\n\r";
				$output .= $product['description'];
				$output .= "        </div>\n\r";
				$output .= "      </td>\n\r";
				$output .= "    </tr>\n\r";
			}
			$output .= "</table>";
		} else {
			$output .= "<p>".TXT_WPSC_NOITEMSINTHIS." ".$group_type.".</p>";
		}
		return $output;
	}
	
	public static function wpsc_product_image($image_url='') {
		global $wpsc_query;
		$display = wpsc_check_display_type();
		$product_id = get_the_ID();
		if ( 'grid' == $display && is_archive() ) {
			// Use product thumbnail
			if ( has_post_thumbnail( $product_id ) ) {
				$thumbnail_id = get_post_thumbnail_id( $product_id  );
			// Use first product image
			} else {
		
				// Get all attached images to this product
				$attached_images = (array)get_posts( array(
					'post_type'   => 'attachment',
					'numberposts' => 1,
					'post_status' => null,
					'post_parent' => $product_id ,
					'orderby'     => 'menu_order',
					'order'       => 'ASC'
				) );
		
				if ( !empty( $attached_images ) )
					$thumbnail_id = $attached_images[0]->ID;
			}
			
			$image_attribute = wp_get_attachment_image_src( $thumbnail_id, 'full');	
	
		
			$image_lager_default_url = $image_attribute[0];
			$width_old = $image_attribute[1];
			$height_old = $image_attribute[2];
			$g_thumb_width  = get_option( 'product_image_width' );
			$g_thumb_height = get_option( 'product_image_height' );
			$thumb_height = $g_thumb_height;
			$thumb_width = $g_thumb_width;
			if($width_old > $g_thumb_width || $height_old > $g_thumb_height){
				if($height_old > $g_thumb_height) {
					$factor = ($height_old / $g_thumb_height);
					$thumb_height = $g_thumb_height;
					$thumb_width = round($width_old / $factor);
				}
				if($thumb_width > $g_thumb_width){
					$factor = ($width_old / $g_thumb_width);
					$thumb_height = round($height_old / $factor);
					$thumb_width = $g_thumb_width;
				}elseif($thumb_width == $g_thumb_width && $width_old > $g_thumb_width){
					$factor = ($width_old / $g_thumb_width);
					$thumb_height = round($height_old / $factor);
					$thumb_width = $g_thumb_width;
				}						
			
				$intermediate_size = "wpsc-{$thumb_width}x{$thumb_height}";
				$image_meta = get_post_meta( $thumbnail_id, '' );
				
				// Clean up the meta array
				foreach ( $image_meta as $meta_name => $meta_value )
				$image_meta[$meta_name] = maybe_unserialize( array_pop( $meta_value ) );
				
				$attachment_metadata = $image_meta['_wp_attachment_metadata'];
				// Determine if we already have an image of this size
				if ( isset( $attachment_metadata['sizes'] ) && (count( $attachment_metadata['sizes'] ) > 0) && ( isset( $attachment_metadata['sizes'][$intermediate_size] ) ) ) {
					$intermediate_image_data = image_get_intermediate_size( $thumbnail_id, $intermediate_size );
					$uploads = wp_upload_dir();
					if ( $intermediate_image_data['path'] != '' && file_exists( $uploads['basedir'] . "/" .$intermediate_image_data['path'] ) ) {
						$image_url = $intermediate_image_data['url'];
					} else {
						$image_url = home_url( "index.php?wpsc_action=scale_image&amp;attachment_id={$thumbnail_id}&amp;width=$thumb_width&amp;height=$thumb_height" );
					}
				} else {
					$image_url = home_url( "index.php?wpsc_action=scale_image&amp;attachment_id={$thumbnail_id}&amp;width=$thumb_width&amp;height=$thumb_height" );
				}
			} else {
				$image_url = $image_lager_default_url;
			}
			
		}
		
		return $image_url;
	}
	
	public static function wpsc_category_image() {
		$custom_category_query = array('category_group'=> 1, 'show_thumbnails'=> get_option('show_category_thumbnails'));
		if(get_option('wpsc_category_grid_view') == 1) $custom_category_query['show_thumbnails'] = 1;
		if ($custom_category_query['show_thumbnails'] == 1) {
			WPSC_GridView_Hook_Filter::wpsc_update_category_image($custom_category_query);
		}
	}
	
	public static function wpsc_update_category_image($custom_category_query) {
		if( isset($custom_category_query['parent_category_id']) )		
			$category_id = absint($custom_category_query['parent_category_id']);
		else
			$category_id = 0;
		$category_data = get_terms('wpsc_product_category','hide_empty=0&parent='.$category_id, OBJECT, 'display');
		foreach((array)$category_data as $category_row) {
			$modified_query = $custom_category_query;
			$modified_query['parent_category_id'] = $category_row->term_id;
			
			$category_image = wpsc_get_categorymeta($category_row->term_id, 'image');
			if((!empty($category_image)) && is_file(WPSC_CATEGORY_DIR.$category_image)) {
				$g_thumb_width = 148;
				$g_thumb_height = 148;
				if(get_option('category_image_width') > 0) $g_thumb_width = get_option('category_image_width');
				if(get_option('category_image_height') > 0) $g_thumb_height = get_option('category_image_height');
				
				$info_image = getimagesize ( WPSC_CATEGORY_DIR.$category_image );
				list ( $width_old, $height_old ) = $info_image;
				$thumb_height = $g_thumb_height;
				$thumb_width = $g_thumb_width;
				if($width_old > $g_thumb_width || $height_old > $g_thumb_height){
					if($height_old > $g_thumb_height) {
						$factor = ($height_old / $g_thumb_height);
						$thumb_height = $g_thumb_height;
						$thumb_width = round($width_old / $factor);
					}
					if($thumb_width > $g_thumb_width){
						$factor = ($width_old / $g_thumb_width);
						$thumb_height = round($height_old / $factor);
						$thumb_width = $g_thumb_width;
					}elseif($thumb_width == $g_thumb_width && $width_old > $g_thumb_width){
						$factor = ($width_old / $g_thumb_width);
						$thumb_height = round($height_old / $factor);
						$thumb_width = $g_thumb_width;
					}	
					
					$intermediate_size_data = image_make_intermediate_size( WPSC_CATEGORY_DIR.$category_image, $thumb_width, $thumb_height, false );
					if ($intermediate_size_data != false) wpsc_update_categorymeta( $category_row->term_id, 'image', $intermediate_size_data['file'] );
				}
			}
			WPSC_GridView_Hook_Filter::wpsc_update_category_image($modified_query);
		}
	}
	
	public static function wpsc_admin_category_forms_add() {
	?>
    <script>
	jQuery(document).ready(function() {
		jQuery("select[name='display_type'] option[value='grid']").prop('selected', true);
	});
	</script>
    <?php
	}
		
	public static function plugin_extra_links($links, $plugin_name) {
		if ( $plugin_name != WPSC_GRID_VIEW_NAME) {
			return $links;
		}
		$links[] = '<a href="http://docs.a3rev.com/user-guides/wp-e-commerce/wpec-grid-view/" target="_blank">'.__('Documentation', 'wp-e-commerce-grid-view' ).'</a>';
		$links[] = '<a href="http://wordpress.org/support/plugin/wp-e-commerce-grid-view/" target="_blank">'.__('Support', 'wp-e-commerce-grid-view').'</a>';
		return $links;
	}
}
?>