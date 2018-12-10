<?php
/**
 * Initialize the options before anything else.
 */
add_action( 'admin_init', 'custom_theme_options', 1 );

/**
 * Build the custom settings & update OptionTree.
 */
function custom_theme_options() {
  /**
   * Get a copy of the saved settings array. 
   */
  $saved_settings = get_option( 'option_tree_settings', array() );
  
  /**
   * Custom settings array that will eventually be 
   * passes to the OptionTree Settings API Class.
   */
  $custom_settings = array( 
    'contextual_help' => array(
      
      'sidebar'       => ''
    ),
    'sections'        => array( 
      array(
        'id'          => 'general_default',
        'title'       => 'General Settings'
      ),
      array(
        'id'          => 'style_settings',
        'title'       => 'Style Settings'
      ),
      array(
        'id'          => 'rb_porto',
        'title'       => 'Portfolio Settings'
      ),
      array(
        'id'          => 'page_titles',
        'title'       => 'Blog Settings'
      ),
      array(
        'id'          => 'contact_page',
        'title'       => 'Contact Settings'
      ),
      array(
        'id'          => 'map_settings',
        'title'       => 'Map Settings'
      ),
      array(
        'id'          => 'backgrounds',
        'title'       => 'Backgrounds'
      )
    ),
    'settings'        => array( 
      array(
        'id'          => 'rb_logo_path',
        'label'       => 'Logo',
        'desc'        => 'Upload a logo for your site. It should be around 125x35, for the best results.',
        'std'         => '',
        'type'        => 'upload',
        'section'     => 'general_default',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'rb_favicon',
        'label'       => 'Favicon',
        'desc'        => 'Upload a 16x16 .ico image as your favicon.',
        'std'         => '',
        'type'        => 'upload',
        'section'     => 'general_default',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'rb_footer_text',
        'label'       => 'Sidebar copyright',
        'desc'        => 'Write a line of text to appear at the bottom of the sidebar.',
        'std'         => '',
        'type'        => 'text',
        'section'     => 'general_default',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'rb_tracking_code',
        'label'       => 'Tracking Code',
        'desc'        => 'Paste your Google Analytics or any other tracking code here.',
        'std'         => '',
        'type'        => 'textarea-simple',
        'section'     => 'general_default',
        'rows'        => '4',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'rb_ie7',
        'label'       => 'IE7 Error Message',
        'desc'        => 'Write a message to display when a user with an old browser opens your website.',
        'std'         => '',
        'type'        => 'textarea',
        'section'     => 'general_default',
        'rows'        => '4',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'rb_site_style',
        'label'       => 'Sidebar style',
        'desc'        => 'This controls the main style of the theme, given by the sidebar\'s color.',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'style_settings',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => 'Orange',
            'label'       => 'Orange',
            'src'         => ''
          ),
          array(
            'value'       => 'Light',
            'label'       => 'Light',
            'src'         => ''
          ),
          array(
            'value'       => 'Dark',
            'label'       => 'Dark',
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'rb_sidebar_autoclose2',
        'label'       => 'Sidebar behaviour',
        'desc'        => 'Choose whether you want the sidebar to auto close on mouse movements, or if you want it to stick on the page unless the user closes it manually.',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'style_settings',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => 'Autohide',
            'label'       => 'Autohide',
            'src'         => ''
          ),
          array(
            'value'       => 'Stick',
            'label'       => 'Stick',
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'rb_layout_center',
        'label'       => 'Layout align',
        'desc'        => 'Choose whether you want the pages &amp; posts to be left or centered aligned.',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'style_settings',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => 'Left',
            'label'       => 'Left',
            'src'         => ''
          ),
          array(
            'value'       => 'Center',
            'label'       => 'Center',
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'rb_images_fit',
        'label'       => 'Portrait images resizing',
        'desc'        => 'Choose the way how the portrait images from all fullscreen galleries will react to stage resizing.',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'style_settings',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => 'Fill',
            'label'       => 'Fill',
            'src'         => ''
          ),
          array(
            'value'       => 'Fit',
            'label'       => 'Fit',
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'rb_blog_layout',
        'label'       => 'Blog layout',
        'desc'        => 'Choose a layout for the blog listing page.',
        'std'         => '',
        'type'        => 'select',
        'section'     => 'style_settings',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => 'Default',
            'label'       => 'Default',
            'src'         => ''
          ),
          array(
            'value'       => 'Full',
            'label'       => 'Full',
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'rb_portfolio_page',
        'label'       => 'Portfolio Page',
        'desc'        => 'Please choose the page which you created as a portfolio holder(the portfolio page template).',
        'std'         => '',
        'type'        => 'page-select',
        'section'     => 'rb_porto',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'rb_gallery_page',
        'label'       => 'Gallery Page',
        'desc'        => 'Please choose the page which you created as a gallery holder(the gallery page template).',
        'std'         => '',
        'type'        => 'page-select',
        'section'     => 'rb_porto',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'rb_blog_link',
        'label'       => 'Blog page',
        'desc'        => 'Please choose the page which you created as a blog posts holder(the blog page template).',
        'std'         => '',
        'type'        => 'page-select',
        'section'     => 'page_titles',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'rb_search_tagline',
        'label'       => 'Posts Title',
        'desc'        => 'Write a title for the individual posts page. This will appear at the top and should fit your blog\'s title. It will also be used for categories.',
        'std'         => '',
        'type'        => 'text',
        'section'     => 'page_titles',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'rb_search2_tagline',
        'label'       => 'Search Title',
        'desc'        => 'Write a title for the search results page.',
        'std'         => '',
        'type'        => 'text',
        'section'     => 'page_titles',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'rb_404_tagline',
        'label'       => '404 Title',
        'desc'        => 'Write a title for the 404 error page.',
        'std'         => '',
        'type'        => 'text',
        'section'     => 'page_titles',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'rb_form_title',
        'label'       => 'Form Title',
        'desc'        => 'Write a title to appear above the form.',
        'std'         => '',
        'type'        => 'text',
        'section'     => 'contact_page',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'rb_form_number_input',
        'label'       => 'Phone Number(s)',
        'desc'        => 'Write your phone number(s) here. Each number needs to be on a new line.',
        'std'         => '',
        'type'        => 'textarea-simple',
        'section'     => 'contact_page',
        'rows'        => '3',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'rb_form_email_input',
        'label'       => 'Email(s)',
        'desc'        => 'Write your email address(es) here. Each email needs to be on a new line.',
        'std'         => '',
        'type'        => 'textarea-simple',
        'section'     => 'contact_page',
        'rows'        => '3',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'rb_form_address_input',
        'label'       => 'Address',
        'desc'        => 'Write your address here.',
        'std'         => '',
        'type'        => 'textarea-simple',
        'section'     => 'contact_page',
        'rows'        => '3',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'rb_form_your_email',
        'label'       => 'Your email address',
        'desc'        => 'In order to make the contact form work, you\'ll have to go to Appearance > Editor and select the contact-form.php file. Go inside it and you will see a field where you can input your email address.',
        'std'         => '',
        'type'        => 'textblock-titled',
        'section'     => 'contact_page',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'rb_form_name_label',
        'label'       => 'Form Name Label',
        'desc'        => 'Write a label for the form\'s name field.',
        'std'         => '',
        'type'        => 'text',
        'section'     => 'contact_page',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'rb_form_email_label',
        'label'       => 'Form Email Label',
        'desc'        => 'Write a label for the form\'s email field.',
        'std'         => '',
        'type'        => 'text',
        'section'     => 'contact_page',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'rb_form_message_label',
        'label'       => 'Form Message Label',
        'desc'        => 'Write a label for the form\'s message field.',
        'std'         => '',
        'type'        => 'text',
        'section'     => 'contact_page',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'rb_form_button_label',
        'label'       => 'Form Button Label',
        'desc'        => 'Write a label for the form\'s submit button.',
        'std'         => '',
        'type'        => 'text',
        'section'     => 'contact_page',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'rb_form_error',
        'label'       => 'Form Error Message',
        'desc'        => 'Write some text to show when a user doesn\'t fill all fields or the information is incorrectly written.',
        'std'         => '',
        'type'        => 'text',
        'section'     => 'contact_page',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'rb_form_success',
        'label'       => 'Form Success Message',
        'desc'        => 'Write some text to show when a user successfully sends you an email.',
        'std'         => '',
        'type'        => 'textarea',
        'section'     => 'contact_page',
        'rows'        => '6',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'rb_map_display',
        'label'       => 'Display Map',
        'desc'        => 'Check whether you want to display a map as a background for your contact page.',
        'std'         => '',
        'type'        => 'checkbox',
        'section'     => 'map_settings',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => '',
        'choices'     => array( 
          array(
            'value'       => 'Yes',
            'label'       => 'Yes',
            'src'         => ''
          )
        ),
      ),
      array(
        'id'          => 'rb_map_lat1',
        'label'       => 'Map Latitude',
        'desc'        => 'Enter a latitude coordinate for the map\'s center.',
        'std'         => '',
        'type'        => 'text',
        'section'     => 'map_settings',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'rb_map_long1',
        'label'       => 'Map Longitude',
        'desc'        => 'Enter a longitude coordinate for the map\'s center.',
        'std'         => '',
        'type'        => 'text',
        'section'     => 'map_settings',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'rb_map_pin',
        'label'       => 'Marker Image',
        'desc'        => 'Add an image if you want to display a custom maker on the map.',
        'std'         => '',
        'type'        => 'upload',
        'section'     => 'map_settings',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'rb_map_lat2',
        'label'       => 'Marker Latitude',
        'desc'        => 'Enter a latitude coordinate for the maker.',
        'std'         => '',
        'type'        => 'text',
        'section'     => 'map_settings',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'rb_map_long2',
        'label'       => 'Marker Longitude',
        'desc'        => 'Enter a latitude coordinate for the maker.',
        'std'         => '',
        'type'        => 'text',
        'section'     => 'map_settings',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'rb_backgrounds',
        'label'       => 'Website backgrounds',
        'desc'        => 'Create your custom backgrounds here.
In each page/post you\'ll be able to select a background from this list, or you can select a default one from here..',
        'std'         => '',
        'type'        => 'list-item',
        'section'     => 'backgrounds',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => '',
        'settings'    => array( 
          array(
            'id'          => 'image',
            'label'       => 'Image',
            'desc'        => 'Upload an image for this background.',
            'std'         => '',
            'type'        => 'upload',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'class'       => ''
          ),
          array(
            'id'          => 'default_posts',
            'label'       => 'Default for posts',
            'desc'        => 'Check if you want this background to be shown for all of your posts!',
            'std'         => '',
            'type'        => 'checkbox',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'class'       => '',
            'choices'     => array( 
              array(
                'value'       => 'yes',
                'label'       => 'Yes',
                'src'         => ''
              )
            ),
          ),
          array(
            'id'          => 'default_pages',
            'label'       => 'Default for pages',
            'desc'        => 'Check if you want this background to be shown for all of your pages!',
            'std'         => '',
            'type'        => 'checkbox',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'class'       => '',
            'choices'     => array( 
              array(
                'value'       => 'yes',
                'label'       => 'Yes',
                'src'         => ''
              )
            ),
          )
        )
      )
    )
  );
   
  /* settings are not the same update the DB */
  if ( $saved_settings !== $custom_settings ) {
    update_option( 'option_tree_settings', $custom_settings ); 
  }
  
}