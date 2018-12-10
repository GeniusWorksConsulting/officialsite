// Tabs
(function ($) {
  activateTabs = {
    init: function () {
      // Activate
      $("#options_tabs").tabs();
	  // Append Toggle Button
      $('.top-info').append('<span class="toggle_tabs">Follow me on <a href="http://twitter.com/anteksiler">Twitter</a> &amp; <a href="http://themeforest.net/user/turkhitbox?ref=turkhitbox">Themeforest</a></span>');
      // Toggle Tabs
      $('.toggle_tabs').toggle(function() {
        $("#options_tabs").tabs('destroy');
        $(this).addClass('off');
      }, function() {
        $("#options_tabs").tabs();
        $(this).removeClass('off');
      }); 
    }
  };
  $(document).ready(function () {
    activateTabs.init()
  })
})(jQuery);

//Style Select

(function ($) {
  styleSelect = {
    init: function () {
      $('.select_wrapper').each(function () {
        $(this).prepend('<span>' + $(this).find('.select option:selected').text() + '</span>');
      });
      $('.select').live('change', function () {
        $(this).prev('span').replaceWith('<span>' + $(this).find('option:selected').text() + '</span>');
      });
      $('.select').bind($.browser.msie ? 'click' : 'change', function(event) {
        $(this).prev('span').replaceWith('<span>' + $(this).find('option:selected').text() + '</span>');
      }); 
    }
  };
  $(document).ready(function () {
    styleSelect.init()
  })
})(jQuery);

// Upload Option

(function ($) {
  uploadOption = {
    init: function () {
      var formfield,
          formID,
          btnContent = true;
      // On Click
      $('.upload_button').live("click", function () {
        formfield = $(this).prev('input').attr('name');
        formID = $(this).attr('rel');
        tb_show('', 'media-upload.php?post_id='+formID+'&type=image&amp;TB_iframe=1');
        return false;
      });
            
      window.original_send_to_editor = window.send_to_editor;
      window.send_to_editor = function(html) {
        if (formfield) {
          itemurl = $(html).attr('href');
          var image = /(^.*\.jpg|jpeg|png|gif|ico*)/gi;
          var document = /(^.*\.pdf|doc|docx|ppt|pptx|odt*)/gi;
          var audio = /(^.*\.mp3|m4a|ogg|wav*)/gi;
          var video = /(^.*\.mp4|m4v|mov|wmv|avi|mpg|ogv|3gp|3g2*)/gi;
          if (itemurl.match(image)) {
            btnContent = '<img src="'+itemurl+'" alt="" /><a href="" class="remove">Remove Image</a>';
          } else {
            btnContent = '<div class="no_image">'+html+'<a href="" class="remove">Remove</a></div>';
          }
          $('#' + formfield).val(itemurl);
          $('#' + formfield).next().next('div').slideDown().html(btnContent);
          tb_remove();
        } else {
          window.original_send_to_editor(html);
        }
      }
    }
  };
  $(document).ready(function () {
    uploadOption.init()
  })
})(jQuery);

jQuery(document).ready(function() {


// Post Format Options

	var quoteOptions = jQuery('#thb-quote');
	var quoteTrigger = jQuery('#post-format-quote');
	
	quoteOptions.css('display', 'none');

	var linkOptions = jQuery('#thb-link-url');
	var linkTrigger = jQuery('#post-format-link');
	
	linkOptions.css('display', 'none');
	

	var audioOptions = jQuery('#thb-audio');
	var audioTrigger = jQuery('#post-format-audio');
	
	audioOptions.css('display', 'none');
	

	var videoOptions = jQuery('#thb-video');
	var videoTrigger = jQuery('#post-format-video');
	
	videoOptions.css('display', 'none');


	jQuery('#post-formats-select input').change( function() {
		
		if(jQuery(this).val() == 'quote') {
			quoteOptions.css('display', 'block');
			HideAll(quoteOptions);
			
		} else if(jQuery(this).val() == 'link') {
			linkOptions.css('display', 'block');
			HideAll(linkOptions);
			
		} else if(jQuery(this).val() == 'audio') {
			audioOptions.css('display', 'block');
			HideAll(audioOptions);
			
		} else if(jQuery(this).val() == 'video') {
			videoOptions.css('display', 'block');
			HideAll(videoOptions);
			
		} else {
			quoteOptions.css('display', 'none');
			videoOptions.css('display', 'none');
			linkOptions.css('display', 'none');
			audioOptions.css('display', 'none');

		}
		
	});
	
	if(quoteTrigger.is(':checked'))
		quoteOptions.css('display', 'block');
		
	if(linkTrigger.is(':checked'))
		linkOptions.css('display', 'block');
		
	if(audioTrigger.is(':checked'))
		audioOptions.css('display', 'block');
		
	if(videoTrigger.is(':checked'))
		videoOptions.css('display', 'block');
		
	function HideAll(data) {
		videoOptions.css('display', 'none');
		quoteOptions.css('display', 'none');
		linkOptions.css('display', 'none');
		audioOptions.css('display', 'none');
		data.css('display', 'block');
	}

// Portfolio Settings
	var audioOptionsPortfolio = jQuery('#thb-portfolio-audio');
	audioOptionsPortfolio.css('display', 'none');
	
	var videoOptionsPortfolio = jQuery('#thb-portfolio-video');
	videoOptionsPortfolio.css('display', 'none');
	
	jQuery('[name="thb-switch"]').change(function () {
	  jQuery('[name="thb-switch"] option:selected').each(function () {
			if (jQuery(this).text() == 'Video') {
				videoOptionsPortfolio.css('display', 'block');
				audioOptionsPortfolio.css('display', 'none');
			} 
			else if (jQuery(this).text() == 'Audio') {
				videoOptionsPortfolio.css('display', 'none');
				audioOptionsPortfolio.css('display', 'block');
			}
			else if (jQuery(this).text() == 'Slideshow') {
				videoOptionsPortfolio.css('display', 'none');
				audioOptionsPortfolio.css('display', 'none');
			}
		  });
	}).trigger('change');

});