/*----------------------------------------------------
 *
 * Obit - A Visual Composer Extension Plugin
 * by TrueThemes
 *
 * http://codecanyon.net/user/TrueThemes/portfolio
*
----------------------------------------------------*/
/*--------------------------------------------------------------
Fire up Functions on Page Load
--------------------------------------------------------------*/
jQuery(document).ready(function () {
    "use strict";
    truethemes_flex_testimonial();
    truethemes_animation_init();
    truethemes_counter();
    truethemes_progress_bar();
    truethemes_progress_bar_vertical();
    truethemes_circle_loader();
    truethemes_circle_loader_icon();
    truethemes_accordions();
    truethemes_notify_boxes();
    //truethemes_grow_boxes();
    truethemes_cleanUp();
    //prettyPhoto
    jQuery("a[data-gal^='prettyPhoto']").prettyPhoto({hook:"data-gal",social_tools: false});

    //prevents bootstrap-tabs from jumping when clicked
    jQuery('.nav-tabs li a, .nav-pills li a').click( function(e) {
        history.pushState( null, null, jQuery(this).attr('href') );
    });
});
/*--------------------------------------------------------------
Flexslider used for Testimonials
--------------------------------------------------------------*/
function truethemes_flex_testimonial() {
    jQuery('.orbit-testimonial-1-flexslider').flexslider({
            animation: "slide",
            namespace: "orbit-"
        });
    jQuery('.orbit-testimonial-2-flexslider').flexslider({
            animation: "slide",
            controlNav: "thumbnails",
            namespace: "orbit-"
        });
}
/*--------------------------------------------------------------
Functions used for "counter elements"
--------------------------------------------------------------*/
(function ($) {
    "use strict";
    $.fn.countTo = function (options) {
        options = $.extend({}, $.fn.countTo.defaults, options || {});
        var loops = Math.ceil(options.speed / options.refreshInterval),
            increment = (options.to - options.from) / loops;
        return $(this).each(function () {
            var _this = this,
                loopCount = 0,
                value = options.from,
                interval = setInterval(updateTimer, options.refreshInterval);

            function updateTimer() {
                value += increment;
                loopCount++;
                $(_this).html(value.toFixed(options.decimals));
                if (typeof (options.onUpdate) === 'function') {
                    options.onUpdate.call(_this, value)
                }
                if (loopCount >= loops) {
                    clearInterval(interval);
                    value = options.to;
                    if (typeof (options.onComplete) === 'function') {
                        options.onComplete.call(_this, value)
                    }
                }
            }
        })
    };
    $.fn.countTo.defaults = {
        from: 0,
        to: 100,
        speed: 1000,
        refreshInterval: 100,
        decimals: 0,
        onUpdate: null,
        onComplete: null
    }
})(jQuery);
/*--------------------------------------------------------------
Initialize waypoint() animation classes
--------------------------------------------------------------*/
function truethemes_animation_init() {
    jQuery(".tt_in_from_center, .tt_in_from_left, .tt_in_from_right, .tt_in_from_top, .tt_in_from_bottom").waypoint(function () {
        if (!jQuery(this).hasClass("tt_animate_start")) {
            var e = jQuery(this);
            setTimeout(function () {
                e.addClass("tt_animate_start")
            }, 20)
        }
    }, {
        offset: "85%",
        triggerOnce: !0
    })
}
/*--------------------------------------------------------------
Animated Number Counter
--------------------------------------------------------------*/
function truethemes_counter() {
    "use strict";
    if (jQuery('.vision-counter.vision-zero').length) {
        jQuery('.vision-counter.vision-zero').each(function () {
            if (!jQuery(this).hasClass('executed')) {
                jQuery(this).addClass('executed');
                jQuery(this).appear(function () {
                    jQuery(this).parent().css('opacity', '1');
                    var $max = parseFloat(jQuery(this).text());
                    jQuery(this).countTo({
                        from: 0,
                        to: $max,
                        speed: 1500,
                        refreshInterval: 100
                    })
                }, {
                    accX: 0,
                    accY: -200
                })
            }
        })
    }
}
/*--------------------------------------------------------------
Animated Progress Bar
--------------------------------------------------------------*/
function truethemes_progress_bar() {
    "use strict";
    if (jQuery('.vision-progress-section').length) {
        jQuery('.vision-progress-section').each(function () {
            jQuery(this).appear(function () {
                truethemes_progress_bar_counter(jQuery(this));
                var number = jQuery(this).find('.progress-bar').data('number');
                jQuery(this).find('.progress-bar').css('width', '0%');
                jQuery(this).find('.progress-bar').animate({
                    'width': number + '%'
                }, 1500);
                jQuery(this).find('.progress_number_wrapper').css('width', '0%');
                jQuery(this).find('.progress_number_wrapper').animate({
                    'width': number + '%'
                }, 1500)
            }, {
                accX: 0,
                accY: -200
            })
        })
    }
}
/*--------------------------------------------------------------
Animated Progress Bar Counter
--------------------------------------------------------------*/
function truethemes_progress_bar_counter($this) {
    "use strict";
    var number = parseFloat($this.find('.progress-bar').data('number'));
    if ($this.find('.vision-progress-number span').length) {
        $this.find('.vision-progress-number span').each(function () {
            jQuery(this).parents('.progress_number_wrapper').css('opacity', '1');
            jQuery(this).countTo({
                from: 0,
                to: number,
                speed: 1500,
                refreshInterval: 50
            })
        })
    }
}
/*--------------------------------------------------------------
Animated Progress Bar - Vertical
--------------------------------------------------------------*/
function truethemes_progress_bar_vertical() {
    "use strict";
    if (jQuery('.vision-progress-section-vertical').length) {
        jQuery('.vision-progress-section-vertical').each(function () {
            jQuery(this).appear(function () {
                truethemes_progress_bar_vertical_counter(jQuery(this));
                var number = jQuery(this).find('.progress-bar-vertical').data('number');
                jQuery(this).find('.progress-bar-vertical').css('height', '0%');
                jQuery(this).find('.progress-bar-vertical').animate({
                    height: number + '%'
                }, 1500)
            }, {
                accX: 0,
                accY: -200
            })
        })
    }
}
/*--------------------------------------------------------------
Animated Progress Bar Counter - Vertical
--------------------------------------------------------------*/
function truethemes_progress_bar_vertical_counter($this) {
    "use strict";
    if ($this.find('.vision-progress-number span').length) {
        $this.find('.vision-progress-number span').each(function () {
            var $max = parseFloat(jQuery(this).text());
            jQuery(this).countTo({
                from: 0,
                to: $max,
                speed: 1500,
                refreshInterval: 50
            })
        })
    }
}
/*--------------------------------------------------------------
Animated Circle Loader
--------------------------------------------------------------*/
function truethemes_circle_loader() {
    "use strict";
    if (jQuery('.vision-circle-number').length) {
        jQuery('.vision-circle-number').each(function () {
            //variables for easyPieChart
            var $trackColor = jQuery(this).data('trackcolor');
            var $barColor   = jQuery(this).data('barcolor');
            var $lineWidth  = jQuery(this).data('linewidth');
            var $lineCap    = jQuery(this).data('linecap');
  
            jQuery(this).appear(function () {
                truethemes_circle_loader_counter(jQuery(this));
                jQuery(this).parent().css('opacity', '1');
                jQuery(this).easyPieChart({
                    animate: 1500,
                    size: 174,
                    trackColor: $trackColor,
                    barColor: $barColor,
                    lineWidth: $lineWidth,
                    lineCap: $lineCap,
                    scaleColor: false
                })
            }, {
                accX: 0,
                accY: -200
            })
        })
    }
}
/*--------------------------------------------------------------
Animated Pie Chart (icon version)
--------------------------------------------------------------*/
function truethemes_circle_loader_icon() {
    "use strict";
    if (jQuery('.vision-circle-icon').length) {
        jQuery('.vision-circle-icon').each(function () {
            //variables for easyPieChart
            var $trackColor = jQuery(this).data('trackcolor');
            var $barColor   = jQuery(this).data('barcolor');
            var $lineWidth  = jQuery(this).data('linewidth');
            var $lineCap    = jQuery(this).data('linecap');
  
            jQuery(this).appear(function () {
                jQuery(this).parent().css('opacity', '1');
                jQuery(this).easyPieChart({
                    animate: 1500,
                    size: 174,
                    trackColor: $trackColor,
                    barColor: $barColor,
                    lineWidth: $lineWidth,
                    lineCap: $lineCap,
                    scaleColor: false
                })
            }, {
                accX: 0,
                accY: -200
            })
        })
    }
}
/*--------------------------------------------------------------
Animated Circle Loader (number counter)
--------------------------------------------------------------*/
function truethemes_circle_loader_counter($this) {
    "use strict";
    jQuery($this).css('opacity', '1');
    var $max = parseFloat(jQuery($this).find('.vision-circle-number').text());
    jQuery($this).find('.vision-circle-number').countTo({
        from: 0,
        to: $max,
        speed: 1500,
        refreshInterval: 50
    })
}
/*--------------------------------------------------------------
Accordions
--------------------------------------------------------------*/
function truethemes_accordions(){
    var accordions = jQuery('.vision-accordion');
    if(accordions.length < 1){
        return;
    }
    accordions.each(function(){
        var that = jQuery(this);
        var handlers = jQuery(this).children('dt');
        handlers.click(function(){
            // If statement added to allow closing all accordion elements.
            if(jQuery(this).hasClass('current')){
                jQuery(this).removeClass('current').next().slideUp();
                return;
            }
            that.children('dt.current').removeClass('current').next().slideUp();
            jQuery(this).toggleClass('current');
            jQuery(this).next('dd').slideToggle();
        });
    });
}
/*--------------------------------------------------------------
Notification Boxes
--------------------------------------------------------------*/
function truethemes_notify_boxes() {

jQuery(document).ready(function(){

    jQuery('.closeable').closeThis({
        animation: 'fadeAndSlide',  // set animation
        animationSpeed: 400         // set animation speed
    });
    
});

(function($)
{
    $.fn.closeThis = function(options)
    {
        var defaults = {
            animation: 'slide',
            animationSpeed: 300
        };
        
        var options = $.extend({}, defaults, options);
        
        return this.each(function()
        {
            var message = $(this);
            
            message.css({cursor: 'pointer'});
            
            message.click(function()
            {
                hideMessage(message);
            });
            
            function hideMessage(object)
            {
                switch(options.animation)
                {
                    case 'fade':
                        fadeAnimation(object);
                        break;
                    case 'slide':
                        slideAnimation(object);
                        break;
                    case 'size':
                        sizeAnimation(object);
                        break;
                    case 'fadeThenSlide':
                        fadeAndSlideAnimation(object);
                        break;
                    default:
                        fadeAndSlideAnimation(object);
                }
            }
            
            function fadeAnimation(object)
            {
                object.fadeOut(options.animationSpeed);
            }
            
            function slideAnimation(object)
            {
                object.slideUp(options.animationSpeed);
            }
            
            function sizeAnimation(object)
            {
                object.hide(options.animationSpeed);
            }
            
            function fadeAndSlideAnimation(object)
            {
                object.fadeTo(options.animationSpeed, 0, function() { slideAnimation(message) } );
            }
            
        });
    }
})(jQuery);

}
/*--------------------------------------------------------------
Cleanup
--------------------------------------------------------------*/
function truethemes_cleanUp() {
    jQuery(".tt-contentbox-content").find('br:first').remove();
    jQuery("body").find('p:empty').remove();
    jQuery(".vision-vector-list").find('br').remove();
}
/*--------------------------------------------------------------
Tabs 1 + Tabs 3
--------------------------------------------------------------*/
var tabset = 1;
function truethemes_orbit_tabs(tab_id){
     var tab_ids = new Array();
     var active_tab_id = new Array();
     var nav_tab_title = new Array();
     var fontawesome_icon_input = new Array();
     var Tab = jQuery('#'+tab_id+'');
     var active_tabset_idx;
     jQuery(Tab).children('.orbit-tab-content').children('div').each(function() {
       tab_ids.push(this.id);
     });
     jQuery(Tab).children('.orbit-tab-content').children('div').each(function() {
       var t = jQuery(this).attr('data-title');
       nav_tab_title.push(t);
     });  
     jQuery(Tab).children('.orbit-tab-content').children('div').each(function() {
       var t = jQuery(this).attr('data-icon');
       fontawesome_icon_input.push(t);
     });   
     jQuery(Tab).children('.orbit-tab-content').children('div').each(function() {
       if (jQuery(this).hasClass('active')) {
         active_tab_id.push(this.id);
       }
     });
     var hash = document.location.hash;
     hash = hash.replace('#', '');
     if (hash.match(/tab_\d+/)) {
        if (hash.match(/tab_\d+$/)) {
          active_tab_id = tab_ids[hash.replace('tab_', '')];
        } else if (hash.match(/tab_\d+_\d+$/)) {
          var numbers = hash.replace('tab_', '').split('_');
          active_tabset_idx = numbers[0];
          if (tabset == active_tabset_idx) {
            active_tab_id = tab_ids[numbers[1]];
          }
        }
     }
     var cList = jQuery('ul.'+tab_id+'');
     var original_active;
     jQuery.each(tab_ids, function(i) {
       if (tab_ids[i] != '') {
         if (tab_ids[i] == active_tab_id) {
           var li = jQuery('<li/>').addClass('active').appendTo(cList);
           original_active = li;
         } else {
           var li = jQuery('<li/>').appendTo(cList);
         }
         var a = jQuery('<a/>')
           .attr('href', '#' + tab_ids[i] + '')
           .attr('role', 'tab')
           .attr('data-toggle', 'tab')
           .text(''+ nav_tab_title[i] + '')
           .appendTo(li);
         var s = jQuery('<span/>')
           .addClass('fa '+ fontawesome_icon_input[i] +'')
           .prependTo(a);  

         var custom_icon = jQuery('#' + tab_ids[i]).find('.orbit-custom-icon');
         if (custom_icon.length) {
            a.find('span').replaceWith(custom_icon);
         }
         var custom_icon_image = jQuery('#' + tab_ids[i]).find('.orbit-custom-icon-img');
         if (custom_icon_image.length) {
            a.find('span').replaceWith(custom_icon_image);
         }
         
       }
     });
     cList.find('li').each(function () {
        if (!jQuery(this).hasClass('active')) {
            jQuery(this).find('a').click();
            return false;
        }
     });

     original_active && original_active.find('a').click();
     tabset ++;
}
/*--------------------------------------------------------------
Tabs 2
--------------------------------------------------------------*/
function truethemes_orbit_tabs_2(tab_id){
     var tab_ids = new Array();
     var active_tab_id = new Array();
     var nav_tab_title = new Array();
     var Tab = jQuery('#tab2-'+tab_id+'');
     jQuery(Tab).find('div').each(function() {
       tab_ids.push(this.id);
     });
     jQuery(Tab).find('div').each(function() {
       var t = jQuery(this).attr('data-title');
       nav_tab_title.push(t);
     });
     jQuery(Tab).find('div').each(function() {
       if (jQuery(this).hasClass('active')) {
         active_tab_id.push(this.id);
       }
     });
     var cList = jQuery('ul.'+tab_id+'');
     jQuery.each(tab_ids, function(i) {
       if (tab_ids[i] != '') {
         if (tab_ids[i] == active_tab_id) {
           var li = jQuery('<li/>').addClass('active').appendTo(cList);
         } else {
           var li = jQuery('<li/>').appendTo(cList);
         }
         var a = jQuery('<a/>')
           .attr('href', '#' + tab_ids[i] + '')
           .attr('role', 'tab')
           .attr('data-toggle', 'tab')
           .text(''+ nav_tab_title[i] + '')
           .appendTo(li);
       }
     });
}


/* COMMENT OUT for further development
function truethemes_grow_boxes() {
    var el = document.querySelectorAll('.growboxes'),
        $ = jQuery,
        msnrys = [];

    for (var i = 0; el[i]; i++) {
        msnrys.push(new Masonry(el[i],
            {
                columnWidth: 20,
                itemSelector: '.growbox'
            }
        ));
        el[i].msnry = msnrys[i];
    }

    $('.growbox_closed_face').each(function () {
        var p = $(this).parent(),
            w = p.data('width'),
            h = p.data('height');

        p.css({
            width: w + 'px',
            height: h + 'px'
        });
        $(this).css({
            top: ($(this).parent().height() / 2) - ($(this).height() / 2),
            left: ($(this).parent().width() / 2) - ($(this).width() / 2)
        });
    });

    $('.growbox').click(function (e) {
        var el = $(this).parent().get(0),
            face = $(this).find('.growbox_closed_face'),
            content = $(this).find('.growbox_open_content'),
            c_w = $(this).data('width'),
            c_h = $(this).data('height'),
            o_w = $(this).data('open_width'),
            o_h = $(this).data('open_height'),
            me = this;

        if ($(this).hasClass('open')) {
            content.fadeOut(function () {
                $(me).animate({
                    width: c_w + 'px',
                    height: c_h + 'px'
                }, 'fast', '', function() {
                    el.msnry.layout();
                    face.animate({
                        top: ($(me).height() / 2) - (face.height() / 2),
                        left: ($(me).width() / 2) - (face.width() / 2)
                    });
                }).removeClass('open');
            });
        } else {
            face.animate({
                top: 20,
                left: 20
            },'fast', '', function() {
                $(me).animate({
                    width: o_w + 'px',
                    height: o_h + 'px'
                }, 'fast', '', function() {
                    el.msnry.layout();
                    content.css({
                        marginTop: face.height() + 20,
                        height: $(me).height() - (face.height() + 20) - 60
                    }).fadeIn();
                }).addClass('open');
            });
        }
    });
    $('.growbox a').click(function (e) {
        e.stopPropagation();
    });
}
*/