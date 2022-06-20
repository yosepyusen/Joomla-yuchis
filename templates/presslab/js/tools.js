var sticky_navigation = function(){
	if (jQuery('#mainnav .sticky_navigation_wrapper').length==0) return;
    var header = jQuery("#mainnav .sticky_navigation_wrapper");
    var contr = jQuery("#mainnav .sticky_navigation_wrapper .wrapmenu");
    var sticky_navigation_offset_top = jQuery('#mainnav .sticky_navigation_wrapper').parent().offset().top;
    var scroll_top = jQuery(window).scrollTop(); 
    if (jQuery('#mainnav .sticky_navigation_empty').css('height')=='0px') {
        jQuery('#mainnav .sticky_navigation_empty').css('height',jQuery('#mainnav .sticky_navigation_wrapper ul.menu').outerHeight());
    }
    if (scroll_top > sticky_navigation_offset_top) {                
        jQuery('#mainnav .sticky_navigation_wrapper').css({'position': 'fixed', 'top': '0', 'left': '0', 'right': '0', 'z-index': '900'});
        jQuery('#mainnav .sticky_navigation_empty').css('display','block');
        header.addClass('animated fadeInDown');
        contr.addClass('container');
    } else {
        jQuery('#mainnav .sticky_navigation_wrapper').css({'position': 'relative'});
        jQuery('#mainnav .sticky_navigation_empty').css('display','none');
        header.removeClass('animated fadeInDown');
        contr.removeClass('container');
    }  
}
linelab_parallax=function() {
    var winTop=jQuery(this).scrollTop();
    var winHeight=jQuery(window).height();
    var winBottom=winTop+winHeight;
    jQuery('.parallax-window').each(function() {
        elTop=jQuery(this).offset().top;
        elHeight=jQuery(this).outerHeight();
        elBottom=elTop+elHeight;
        if (
            (elTop>winTop && elTop<winBottom)
            || (elBottom>winTop && elBottom<winBottom)
            || (elTop<winTop && elBottom>winBottom)
            ) {
                var elImgWidthFull=jQuery(this).attr('data-image-width');
                var elImgHeightFull=jQuery(this).attr('data-image-height');
                if (typeof elImgHeightFull=='undefined' || typeof elImgWidthFull=='undefined')
                var elImgWidthShow=jQuery(this).outerWidth();
                var elImgHeightShow=jQuery(this).outerWidth()*parseInt(elImgHeightFull)/parseInt(elImgWidthFull);
                if (elImgHeightShow>elHeight) {
                    if (elImgHeightShow>winHeight) {
                        var posNumerator=elBottom-winTop;
                        var posDenominator=winHeight+elHeight;
                        jQuery(this).css('background-size', 'cover');
                    } else {
                        var posNumerator=elTop-winTop;
                        var posDenominator=winHeight-elHeight;
                        jQuery(this).css('background-size', 'contain');
                    }
                    var posRatio=elImgHeightShow-winHeight;
                    var elPos=Math.round(-((posNumerator)/(posDenominator))*posRatio);
                    if (elPos!=jQuery(this).data('img-position')) {
                        var elPosCss='center '+elPos+'px';
                        jQuery(this).data('img-position', elPos);
                        jQuery(this).css('background-position', elPosCss);
                    }
                } else {
                    var elPosCss='center 0px';
                    jQuery(this).data('img-position', false);
                    jQuery(this).css({'background-position':elPosCss,'background-size':'cover'});
                }
        }
    });
}
jQuery(document).ready (function () { 
    if (jQuery('#mainnav .sticky_navigation_wrapper').length>0) {
        jQuery('#mainnav .sticky_navigation_empty').css('display','none');
        sticky_navigation();
        jQuery(window).scroll(function() {
             sticky_navigation();
        }); 
    }
    if (jQuery('.parallax-window').length>0) {
        jQuery(window).scroll(function() {linelab_parallax()});
        jQuery(window).resize(function() {linelab_parallax()});
    }
    if (jQuery('.counter-count').length > 0) {
        jQuery(window).scroll(function() {
            var wH = jQuery(window).height(),
                wS = jQuery(window).scrollTop();
            jQuery('.counter-count').each(function () {
               var hT = jQuery(this).offset().top,
                   hH = jQuery(this).outerHeight();
                   if (wS > (hT+hH-wH) && (hT > wS) && (wS+wH > hT+hH)){
                        if (!jQuery(this).hasClass('view')) {
                            jQuery(this).addClass('view').data('val',jQuery(this).text());
                            jQuery(this).prop('Counter',0).animate({
                                Counter: jQuery(this).text()
                            }, {
                                duration: 5000,
                                easing: 'swing',
                                step: function (now) {
                                    jQuery(this).text(Math.ceil(now));
                                }
                            });
                        }
                   } else {
                      if (jQuery(this).hasClass('view')) {
                        jQuery(this).removeClass('view').stop().text(jQuery(this).data('val'));
                      }
                   }
            });
        });
    }
}); 
