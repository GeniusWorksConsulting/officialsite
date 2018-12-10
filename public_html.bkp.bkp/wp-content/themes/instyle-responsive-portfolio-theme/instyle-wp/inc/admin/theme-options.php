<div id="framework_wrap" class="wrap">
	
	<div id="header">
    <h1>OptionTree</h1>
    <span class="icon">&nbsp;</span>
	</div>
  
  <div id="content_wrap">
  	<?php $options = get_option('instyle_theme'); ?>
    <form method="post" id="the-theme-options" enctype="multipart/form-data">
      <input type="hidden" name="instyle_action" value="save">
      <div class="info top-info">
      
        <input type="submit" value="Save All Changes" class="button-framework save-options" name="save"/>
        
      </div>
      
      <div id="content">
      
        <div id="options_tabs">
        
          <ul class="options_tabs">
            <li><a href="#styling">Styling</a><span></span></li>
            <li><a href="#themecolors">Colors</a><span></span></li>          
            <li><a href="#themeportfolio">Galleries</a><span></span></li>
            <li><a href="#single">Single Pages</a><span></span></li>
            <li><a href="#miscoptions">Miscellaneous</a><span></span></li>
          </ul>
          <div id="styling" class="block">
          <?php option_tree_upload ( $value = array('item_id' => "logo", 'item_title' => "Logo Upload", 'item_desc' => "Upload your own logo using this tool. After you have uploaded your image, click on <strong>File URL</strong> for the Link URL and then click on <strong>Insert into Post</strong> button. You should see the file url inside the input. Click save. To delete, clear the url inside the input and click on save all changes. *The logo should be 400 x 100 in size."), $options ); ?>
          <?php option_tree_select ( $value = array('item_id' => "titlefont", 'item_title' => "Title Font Selection", 'item_desc' => "Which font would you like to use for Titles?", 'item_options' =>"Arial, Verdana, Trebuchet, Georgia, Times New Roman, Tahoma, Palatino, Helvetica, Helvetica Neue, Droid Sans, Droid Serif, Arvo, Lobster, Lobster Two, Vibur, Six Caps, Terminal Dosis Light, Michroma, Cabin Sketch, Oswald, Bevan, Anonymous Pro, Expletus Sans, Amaranth, Philosopher, Quattrocento, Radley, Merriweather, Cabin, Cherry Cream Soda, PT Sans, Crafty Girls, Pacifico, PT Serif, PT Serif Caption, Play, Maven Pro, Varela, Muli, Tenor Sans, Open Sans, Terminal Dosis, Istok Web"), $options ); ?>
          <?php option_tree_select ( $value = array('item_id' => "bodyfont", 'item_title' => "Body Font Selection", 'item_desc' => "Which font would you like to use for Body Text?", 'item_options' =>"Arial, Verdana, Trebuchet, Georgia, Times New Roman, Tahoma, Palatino, Helvetica, Helvetica Neue, Droid Sans, Droid Serif, Arvo, Lobster, Lobster Two, Vibur, Six Caps, Terminal Dosis Light, Michroma, Cabin Sketch, Oswald, Bevan, Anonymous Pro, Expletus Sans, Amaranth, Philosopher, Quattrocento, Radley, Merriweather, Cabin, Cherry Cream Soda, PT Sans, Crafty Girls, Pacifico, PT Serif, PT Serif Caption, Play, Maven Pro, Varela, Muli, Tenor Sans, Open Sans, Terminal Dosis, Istok Web"), $options ); ?>
          <?php option_tree_select ( $value = array('item_id' => "menufont", 'item_title' => "Menu Font Selection", 'item_desc' => "Which font would you like to use for the navigation Menu?", 'item_options' =>"Arial, Verdana, Trebuchet, Georgia, Times New Roman, Tahoma, Palatino, Helvetica, Helvetica Neue, Droid Sans, Droid Serif, Arvo, Lobster, Lobster Two, Vibur, Six Caps, Terminal Dosis Light, Michroma, Cabin Sketch, Oswald, Bevan, Anonymous Pro, Expletus Sans, Amaranth, Philosopher, Quattrocento, Radley, Merriweather, Cabin, Cherry Cream Soda, PT Sans, Crafty Girls, Pacifico, PT Serif, PT Serif Caption, Play, Maven Pro, Varela, Muli, Tenor Sans, Open Sans, Terminal Dosis, Istok Web"), $options ); ?>
          <?php option_tree_textarea ( $value = array('item_id' => "customcss", 'item_title' => "Custom CSS", 'item_desc' => "Enter your css code here. "), $options ); ?>
          </div>
          <div id="themecolors" class="block">
          <?php option_tree_colorpicker ( $value = array('item_id' => "bodytextcolor", 'item_title' => "Body Text Color", 'item_desc' => "General Body text color" ), $options ); ?>
          <?php option_tree_colorpicker ( $value = array('item_id' => "generallink", 'item_title' => "General Link Color", 'item_desc' => "Select your link color. Leave empty for default" ), $options ); ?>
          <?php option_tree_colorpicker ( $value = array('item_id' => "generallinkhover", 'item_title' => "General Link Hover Color", 'item_desc' => "Select your link hover color. Leave empty for default" ), $options ); ?>
          <?php option_tree_colorpicker ( $value = array('item_id' => "sidebarlink", 'item_title' => "Sidebar Link Color", 'item_desc' => "Select your link color for the sidebar. Leave empty for default" ), $options ); ?>
          <?php option_tree_colorpicker ( $value = array('item_id' => "sidebarlinkhover", 'item_title' => "Sidebar Link Hover Color", 'item_desc' => "Select your link hover color for the sidebar. Leave empty for default" ), $options ); ?>
          <?php option_tree_colorpicker ( $value = array('item_id' => "menulink", 'item_title' => "Navigation Menu Link Color", 'item_desc' => "Select your link color for the main navigation. Leave empty for default" ), $options ); ?>
          <?php option_tree_colorpicker ( $value = array('item_id' => "menulinkhover", 'item_title' => "Navigation Menu Link Hover Color", 'item_desc' => "Select your link hover color for the main navigation. Leave empty for default" ), $options ); ?>
          </div> 
          <div id="themeportfolio" class="block">
          <?php option_tree_input ( $value = array('item_id' => "portfolioclassiccount", 'item_title' => "Number of Items to Show on Each Page", 'item_desc' => "Enter a number for items to show on each <strong>Classic Portfolio</strong> page (default is -1/no limits)"), $options ); ?>
          <?php option_tree_checkbox ( $value = array('item_id' => "portfoliorelated", 'item_title' => "Enable Related Items", 'item_desc' => "Would you like to show related items on portfolio item pages?", 'item_options' =>"Active"), $options ); ?>
          <?php option_tree_input ( $value = array('item_id' => "portfoliorelatedtitle", 'item_title' => "Related Items Title", 'item_desc' => "Title of the related items on portfolio item pages"), $options ); ?>
          </div>
          <div id="single" class="block">
          <?php option_tree_input ( $value = array('item_id' => "bitly", 'item_title' => "Bit.ly Account ID", 'item_desc' => 'Bit.ly Account ID. You can get your Account ID and API key <a href="https://bitly.com/a/your_api_key">here</a>.'), $options ); ?>
          <?php option_tree_input ( $value = array('item_id' => "bitly-api", 'item_title' => "Bit.ly API code", 'item_desc' => "Bi.tly API Key"), $options ); ?>
          <?php option_tree_checkbox ( $value = array('item_id' => "relatedpopular", 'item_title' => "Related & Popular Posts", 'item_desc' => "Would you like to display related & popular posts in single post pages?", 'item_options' =>"Active"), $options ); ?>
          <?php option_tree_input ( $value = array('item_id' => "relatedpopularno", 'item_title' => "Related & Popular Count", 'item_desc' => "How many related & popular posts would you like to display?"), $options ); ?>
          </div> 
          <div id="miscoptions" class="block">
          <?php option_tree_textarea ( $value = array('item_id' => "ga", 'item_title' => "Google Analytics", 'item_desc' => "Enter your Google Analytics code here."), $options ); ?>
          <?php option_tree_checkbox ( $value = array('item_id' => "enable_gmap", 'item_title' => "Enable Gmap", 'item_desc' => "Check this to insert the necessary JavaScript to use Google Maps and its shortcode inside the theme.", 'item_options' =>"Active"), $options ); ?>
          </div>
          <br class="clear" />
          
        </div>
        
      </div>
      
      <div class="info bottom">
      
        <input type="submit" value="Save All Changes" class="button-framework save-options" name="save"/>
        
      </div>
      
    </form>
    
  </div>

</div>