/*global jQuery */
/*!
* FitVids 1.0
*
* Copyright 2011, Chris Coyier - http://css-tricks.com + Dave Rupert - http://daverupert.com
* Credit to Thierry Koblentz - http://www.alistapart.com/articles/creating-intrinsic-ratios-for-video/
* Released under the WTFPL license - http://sam.zoy.org/wtfpl/
*
* Date: Thu Sept 01 18:00:00 2011 -0500
*/

(function( $ ){

  $.fn.fitVids = function( options ) {
    var settings = {
      customSelector: null
    }

    var div = document.createElement('div'),
        ref = document.getElementsByTagName('base')[0] || document.getElementsByTagName('script')[0];

  	div.className = 'fit-vids-style';
    div.innerHTML = '&shy;<style>         \
      .fluid-width-video-wrapper {        \
         width: 100%;                     \
         position: relative;              \
         padding: 0;                      \
      }                                   \
                                          \
      .fluid-width-video-wrapper iframe,  \
      .fluid-width-video-wrapper object,  \
      .fluid-width-video-wrapper embed {  \
         position: absolute;              \
         top: 0;                          \
         left: 0;                         \
         width: 100%;                     \
         height: 100%;                    \
      }                                   \
    </style>';

    ref.parentNode.insertBefore(div,ref);

    if ( options ) {
      $.extend( settings, options );
    }

    return this.each(function(){
      var selectors = [
        "iframe[src^='http://player.vimeo.com']",
        "iframe[src^='http://www.youtube.com']",
        "iframe[src^='http://www.kickstarter.com']"
      ];

      if (settings.customSelector) {
        selectors.push(settings.customSelector);
      }

      var $allVideos = $(this).find(selectors.join(','));

      $allVideos.each(function(){
        var $this = $(this);
        if (this.tagName.toLowerCase() == 'embed' && $this.parent('object').length || $this.parent('.fluid-width-video-wrapper').length) { return; }
        var height = this.tagName.toLowerCase() == 'object' ? $this.attr('height') : $this.height(),
            aspectRatio = height / $this.width();
		if(!$this.attr('id')){
			var videoID = 'fitvid' + Math.floor(Math.random()*999999);
			$this.attr('id', videoID);
		}
        $this.wrap('<div class="fluid-width-video-wrapper"></div>').parent('.fluid-width-video-wrapper').css('padding-top', (aspectRatio * 100)+"%");
        $this.removeAttr('height').removeAttr('width');
      });
    });

  }
})( jQuery );

jQuery(document).ready(function(){
	jQuery(".gdl-page-row-wrapper").fitVids();
});


jQuery(document).ready(function(){
  $=jQuery;
$('.mb41').addClass('');
$('.mb42').addClass('');
$('.about-us-wrapper').addClass('animatedParent');
$('.about-us-image').addClass('animated fadeInLeft delay-400');
$('.about-us-content-wrapper').addClass('animatedParent');
$('.about-us-content').addClass('animated fadeInRight delay-400');
$('.about-us-button-wrapper').addClass('animated fadeIn slowest');
$('.countrow .col-lg-2').addClass('animated growIn');
$('.abtcls').addClass('animatedParent');
$('.imgcls1').addClass('animated fadeInLeft');
$('.kaycls').addClass('animated fadeInRight');
$('#rid1').addClass('animatedParent');
$('.imgcls2').addClass('animated fadeInRight');
$('.kaycls2').addClass('animated fadeInLeft');
$('#rid2').addClass('animatedParent');
$('.imgcls3').addClass('animated fadeInLeft');
$('.kaycls3').addClass('animated fadeInRight');
$('#rid3').addClass('animatedParent');
$('.imgcls4').addClass('animated fadeInRight');
$('.kaycls4').addClass('animated fadeInLeft');
if($(window).width()<380)
{
   $('.trainimg').css({"top" : "-1230px","left" : "-10px"});
 }
var tt=$('.content-wrapper').innerWidth();
$('#galid').width(tt);
  if($(window).width() <= 1024){
    var wdth=$(window).width();
  $('#contid .wpb_wrapper').width(wdth);
  $('.about-us-wrapper').width(wdth-70);
  $('.vc_custom_1522842414274').attr('style','background-position:5% !important');
  $('.vc_custom_1522843801544').attr('style','background-position:15% !important');
  }
var _counterflag = false;
_counter();
$(window).scroll(function(){
if($('#countid').find('.go').length>0)
{
  if(!_counterflag){
    _counter();
    _counterflag = true;

  }
}
else {
  _counterflag = false;
}
});




});



function _counter(){
  $('.number').each(function () {
      $(this).prop('Counter',0).animate({
          Counter: $(this).attr("data-id")
      }, {
          duration: 4000,
          easing: 'swing',
          step: function (now) {
              $(this).text(Math.ceil(now));
          }
      });
  });

}
