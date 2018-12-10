$(document).ready(function() {
	/* Menu Animation */
	$('ul.sub-menu').parent('li').children('a').append('<span class="submenu-arrow"></span>');    
	$('.submenu-arrow').parent('a').addClass('toggle-closed').parents('li') .children('ul') .hide(); 
	$('.menu li a') .click(function(s){
		if ($(this).hasClass('toggle-open')) {
			$(this).removeClass('toggle-open') .addClass('toggle-closed').parent('li').children('ul').slideUp(250);
			s.preventDefault();
		}else if ($(this).hasClass('toggle-closed')){
			$(this).removeClass('toggle-closed') .addClass('toggle-open').parent('li').children('ul').slideDown(250);
			s.preventDefault();
	}
	});
	/* Pretty Photo */
	$("a[rel^='prettyPhoto']").prettyPhoto({ "overlay_gallery": false, "deeplinking": false, "show_title": false, "social_tools": false });
	/* Scroll To Top */
	var topLink = $('.scrolltotop');
	function backToTop(topLink) {	
		if(jQuery(window).scrollTop() > 0) {
			topLink.fadeIn(200);
		} else {
			topLink.fadeOut(200);
		}
	}
	$(window).scroll( function() {
		backToTop(topLink);
	});
	topLink.find('a').click( function() {
		jQuery('html, body').stop().animate({scrollTop:0}, 500);
		return false;
	});
	$('.divider a').click( function() {
		jQuery('html, body').stop().animate({scrollTop:0}, 500);
		return false;
	});
	/* Slides */
	$('.sidebarslides').slides({
		preload: true,
		generateNextPrev: false,
		autoHeight: true,
		preloadImage: '/images/preloader.gif',
		generatePagination: false
	});
	
	/* Tabs */
	$(".tabcontainer").tabs({ 
		fx: { opacity: 'toggle', height: 'toggle', duration: 200} 
	});
	
	/* Toggle */
	$(".toggle .title").toggle(function(){
		$(this).addClass("toggled").closest('.toggle').find('.inner').slideDown(400);
		}, function () {
		$(this).removeClass("toggled").closest('.toggle').find('.inner').slideUp(400);
	});
	
	/* Accordion */
	$(".accordion").accordion({ header: '.title', autoHeight: false });
	
	/* Alert Box Close */
	$('a.close').click(function() {
		$(this).parent('.alert-message').slideUp(400);
		return false;
	});
	
	/* Sortable Portfolio */
	$('#portfolio-control a').each( function () {
		$(this).click(
			function(e) {
				jQuery('#portfolio-control a').removeClass('active');
				jQuery(this).addClass('active');
				e.preventDefault();
			}
		);

	});
	$(function($) {
		$.fn.sorted = function(customOptions) {
			var options = {
				reversed: false,
				by: function(a) {
					return a.text();
				}
			};
			$.extend(options, customOptions);
		
			$data = $(this);
			arr = $data.get();
			arr.sort(function(a, b) {
				
				var valA = options.by($(a));
				var valB = options.by($(b));
				if (options.reversed) {
					return (valA < valB) ? 1 : (valA > valB) ? -1 : 0;				
				} else {		
					return (valA < valB) ? -1 : (valA > valB) ? 1 : 0;	
				}
			});
			return $(arr);
		};
	
	});
	$(function() {
	  var read_button = function(class_names) {
		var r = {
		  selected: false,
		  type: 0
		};
		for (var i=0; i < class_names.length; i++) {
		  if (class_names[i].indexOf('selected-') == 0) {
			r.selected = true;
		  }
		  if (class_names[i].indexOf('segment-') == 0) {
			r.segment = class_names[i].split('-')[1];
		  }
		};
		return r;
	  };
	  
	  var determine_sort = function($buttons) {
		var $selected = $buttons.parent().filter('[class*="selected-"]');
		return $selected.find('a').attr('data-value');
	  };
	  
	  var determine_kind = function($buttons) {
		var $selected = $buttons.parent().filter('[class*="selected-"]');
		return $selected.find('a').attr('data-value');
	  };
	  
	  var $preferences = {
		duration: 600,
		easing: 'easeInOutQuad',
		adjustHeight: 'dynamic'
	  };
	  
	  var $list = $('.gallerycolumns');
	  var $data = $list.clone();
	  
	  var $controls = $('#portfolio-control');
	  
	  $controls.each(function(i) {
		
		var $control = $(this);
		var $buttons = $control.find('a');
		
		$buttons.bind('click', function(e) {
		  
		  var $button = $(this);
		  var $button_container = $button.parent();
		  var button_properties = read_button($button_container.attr('class').split(' '));      
		  var selected = button_properties.selected;
		  var button_segment = button_properties.segment;
	
		  if (!selected) {
	
			$buttons.parent(':not(".disabled")').removeClass();
			$button_container.addClass('selected-' + button_segment);
			
			var sorting_type = determine_sort($controls.eq(1).find('a'));
			var sorting_kind = determine_kind($controls.eq(0).find('a'));
			
			if (sorting_kind == 'all') {
			  var $filtered_data = $data.find('li');
			} else {
			  var $filtered_data = $data.find('li.' + sorting_kind);
			}
			
			var $sorted_data = $filtered_data.sorted({
				by: function(v) {
					return $(v).find('strong').text().toLowerCase();
				}
			});
			
			$list.quicksand($sorted_data, $preferences, function () {
				$("a[rel^='prettyPhoto']").prettyPhoto({ "slideshow": 5000, "overlay_gallery": false, "deeplinking": false, "show_title": false, "social_tools": false });
			});
			
		  }
		  
		  e.preventDefault();
		});
		
	  }); 
	
	});
 });
