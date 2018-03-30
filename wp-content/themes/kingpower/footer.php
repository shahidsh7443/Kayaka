			</div> <!-- content wrapper -->
		</div> <!-- content outer wrapper -->

		<?php
			$gdl_show_footer_gallery = (get_option(THEME_SHORT_NAME.'_show_footer_gallery','enable') == 'enable')? true: false;
			$gdl_homepage_footer_gallery = (get_option(THEME_SHORT_NAME.'_show_footer_gallery_only_homepage','disable') == 'enable')? true: false;

			if( $gdl_show_footer_gallery && (($gdl_homepage_footer_gallery && is_front_page()) || !$gdl_homepage_footer_gallery )){

				global $footer_gallery_size;
				$gallery_page = get_option(THEME_SHORT_NAME.'_footer_gallery_id');
				if( !empty($gallery_page) ){
					$gallery_post = get_posts(array('post_type' => 'gdl-gallery', 'name'=>$gallery_page, 'numberposts'=> 1));

					echo '<div class="footer-gallery-wrapper boxed-style" >';
					echo '<div class="container footer-gallery-container" id="footer-gallery-container" >';

					echo '<div class="footer-gallery-nav-wrapper">';
					echo '<div class="footer-gallery-nav-left"></div>';
					echo '<div class="footer-gallery-title">' . get_option(THEME_SHORT_NAME.'_footer_gallery_title')  . '</div>';
					echo '<div class="footer-gallery-nav-right"></div>';
					echo '<div class="clear"></div>';
					echo '</div>'; // footer gallery nav wrapper

					echo '<div class="gallery-item-holder row" id="gallery-item-holder" data-index="0">';
						$slider_xml_string = get_post_meta($gallery_post[0]->ID,'post-option-gallery-xml', true);
						$slider_xml_dom = new DOMDocument();
						if( !empty( $slider_xml_string ) ){
							$slider_xml_dom->loadXML($slider_xml_string);
							foreach( $slider_xml_dom->documentElement->childNodes as $slider ){
								print_item_size('1/5', 0.1, 'footer-gallery-item');

								$link_type = find_xml_value($slider, 'linktype');
								$image_url = wp_get_attachment_image_src(find_xml_value($slider, 'image'), $footer_gallery_size);
								$alt_text = get_post_meta(find_xml_value($slider, 'image') , '_wp_attachment_image_alt', true);

								if( $link_type == 'Link to URL' ){
									$link = find_xml_value( $slider, 'link');
									echo '<a href="' . $link . '" title="' . $link . '" target="_blank">';
									echo '<img src="' . $image_url[0] . '" alt="' . $alt_text . '" />';
									echo '</a>';
								}else if( $link_type == 'Lightbox' ){
									$image_full = wp_get_attachment_image_src(find_xml_value($slider, 'image'), 'full');
									echo '<a data-rel="fancybox" data-fancybox-group="gal' . $gdl_element_id . '" href="' . $image_full[0] . '"  title="' . $alt_text . '">';
									echo '<img src="' . $image_url[0] . '" alt="' . $alt_text . '" />';
									echo '</a>';
								}else{
									echo '<img src="' . $image_url[0] . '" alt="' . $alt_text . '" />';
								}

								echo '</div>'; // print item size
							}
						}
					echo '<div class="clear"></div>';
					echo '</div>'; // row

					echo '</div>'; // footer gallery container

					echo '</div>'; // footer gallery wrapper

					wp_deregister_script('footer-gallery');
					wp_register_script('footer-gallery', GOODLAYERS_PATH.'/javascript/footer-gallery.js', false, '1.0', true);
					wp_enqueue_script('footer-gallery');
				}
			}
		?>

		<div class="footer-wrapper boxed-style">

		<!-- Get Footer Widget -->
		<?php $gdl_show_footer = get_option(THEME_SHORT_NAME.'_show_footer','enable'); ?>
		<?php if( $gdl_show_footer == 'enable' ){ ?>
			<div class="container footer-container">
				<div class="footer-widget-wrapper">
					<div class="row">
						<?php
							$gdl_footer_class = array(
								'footer-style1'=>array('1'=>'three columns', '2'=>'three columns', '3'=>'three columns', '4'=>'three columns'),
								'footer-style2'=>array('1'=>'six columns', '2'=>'three columns', '3'=>'three columns', '4'=>''),
								'footer-style3'=>array('1'=>'three columns', '2'=>'three columns', '3'=>'six columns', '4'=>''),
								'footer-style4'=>array('1'=>'four columns', '2'=>'four columns', '3'=>'four columns', '4'=>''),
								'footer-style5'=>array('1'=>'eight columns', '2'=>'four columns', '3'=>'', '4'=>''),
								'footer-style6'=>array('1'=>'four columns', '2'=>'eight columns', '3'=>'', '4'=>''),
								);
							$gdl_footer_style = get_option(THEME_SHORT_NAME.'_footer_style', 'footer-style1');

							for( $i=1 ; $i<=4; $i++ ){
								$footer_class = $gdl_footer_class[$gdl_footer_style][$i];
									if( !empty($footer_class) ){
									echo '<div class="' . $footer_class . ' gdl-footer-' . $i . ' mb0">';
									dynamic_sidebar('Footer ' . $i);
									echo '</div>';
								}
							}
						?>
						<div class="clear"></div>
					</div> <!-- close row -->

					<!-- Get Copyright Text -->
					<?php $gdl_show_copyright = get_option(THEME_SHORT_NAME.'_show_copyright','enable'); ?>
					<?php if( $gdl_show_copyright == 'enable' ){ ?>
						<div class="copyright-wrapper">
							<div class="copyright-border"></div>
							<div class="copyright-left">
								<?php echo do_shortcode( __(get_option(THEME_SHORT_NAME.'_copyright_left_area'), 'gdl_front_end') ); ?>
							</div>
							<div class="copyright-scroll-top scroll-top"></div>
						</div>
					<?php } ?>
				</div>
			</div>
		<?php } ?>

		</div><!-- footer wrapper -->
	</div> <!-- body wrapper -->
</div> <!-- body outer wrapper -->

<?php wp_footer(); ?>

<script>
/*
 * CSS3 Animate it
 * Copyright (c) 2014 Jack McCourt
 * https://github.com/kriegar/css3-animate-it
 * Version: 0.1.0
 *
 * I utilise the jQuery.appear plugin within this javascript file so here is a link to that too
 * https://github.com/morr/jquery.appear
 *
 * I also utilise the jQuery.doTimeout plugin for the data-sequence functionality so here is a link back to them.
 * http://benalman.com/projects/jquery-dotimeout-plugin/
 */
(function($) {
  var selectors = [];

  var check_binded = false;
  var check_lock = false;
  var defaults = {
    interval: 250,
    force_process: false
  }
  var $window = $(window);

  var $prior_appeared;

  function process() {
    check_lock = false;
    for (var index = 0; index < selectors.length; index++) {
      var $appeared = $(selectors[index]).filter(function() {
        return $(this).is(':appeared');
      });

      $appeared.trigger('appear', [$appeared]);

      if ($prior_appeared) {

        var $disappeared = $prior_appeared.not($appeared);
        $disappeared.trigger('disappear', [$disappeared]);
      }
      $prior_appeared = $appeared;
    }
  }

  // "appeared" custom filter
  $.expr[':']['appeared'] = function(element) {
    var $element = $(element);
    if (!$element.is(':visible')) {
      return false;
    }

    var window_left = $window.scrollLeft();
    var window_top = $window.scrollTop();
    var offset = $element.offset();
    var left = offset.left;
    var top = offset.top;

    if (top + $element.height() >= window_top &&
        top - ($element.data('appear-top-offset') || 0) <= window_top + $window.height() &&
        left + $element.width() >= window_left &&
        left - ($element.data('appear-left-offset') || 0) <= window_left + $window.width()) {
      return true;
    } else {
      return false;
    }
  }

  $.fn.extend({
    // watching for element's appearance in browser viewport
    appear: function(options) {
      var opts = $.extend({}, defaults, options || {});
      var selector = this.selector || this;
      if (!check_binded) {
        var on_check = function() {
          if (check_lock) {
            return;
          }
          check_lock = true;

          setTimeout(process, opts.interval);
        };

        $(window).scroll(on_check).resize(on_check);
        check_binded = true;
      }

      if (opts.force_process) {
        setTimeout(process, opts.interval);
      }
      selectors.push(selector);
      return $(selector);
    }
  });

  $.extend({
    // force elements's appearance check
    force_appear: function() {
      if (check_binded) {
        process();
        return true;
      };
      return false;
    }
  });
})(jQuery);



/*!
 * jQuery doTimeout: Like setTimeout, but better! - v1.0 - 3/3/2010
 * http://benalman.com/projects/jquery-dotimeout-plugin/
 *
 * Copyright (c) 2010 "Cowboy" Ben Alman
 * Dual licensed under the MIT and GPL licenses.
 * http://benalman.com/about/license/
 */

// Script: jQuery doTimeout: Like setTimeout, but better!
//
// *Version: 1.0, Last updated: 3/3/2010*
//
// Project Home - http://benalman.com/projects/jquery-dotimeout-plugin/
// GitHub       - http://github.com/cowboy/jquery-dotimeout/
// Source       - http://github.com/cowboy/jquery-dotimeout/raw/master/jquery.ba-dotimeout.js
// (Minified)   - http://github.com/cowboy/jquery-dotimeout/raw/master/jquery.ba-dotimeout.min.js (1.0kb)
//
// About: License
//
// Copyright (c) 2010 "Cowboy" Ben Alman,
// Dual licensed under the MIT and GPL licenses.
// http://benalman.com/about/license/
//
// About: Examples
//
// These working examples, complete with fully commented code, illustrate a few
// ways in which this plugin can be used.
//
// Debouncing      - http://benalman.com/code/projects/jquery-dotimeout/examples/debouncing/
// Delays, Polling - http://benalman.com/code/projects/jquery-dotimeout/examples/delay-poll/
// Hover Intent    - http://benalman.com/code/projects/jquery-dotimeout/examples/hoverintent/
//
// About: Support and Testing
//
// Information about what version or versions of jQuery this plugin has been
// tested with, what browsers it has been tested in, and where the unit tests
// reside (so you can test it yourself).
//
// jQuery Versions - 1.3.2, 1.4.2
// Browsers Tested - Internet Explorer 6-8, Firefox 2-3.6, Safari 3-4, Chrome 4-5, Opera 9.6-10.1.
// Unit Tests      - http://benalman.com/code/projects/jquery-dotimeout/unit/
//
// About: Release History
//
// 1.0 - (3/3/2010) Callback can now be a string, in which case it will call
//       the appropriate $.method or $.fn.method, depending on where .doTimeout
//       was called. Callback must now return `true` (not just a truthy value)
//       to poll.
// 0.4 - (7/15/2009) Made the "id" argument optional, some other minor tweaks
// 0.3 - (6/25/2009) Initial release

(function($){
  '$:nomunge'; // Used by YUI compressor.

  var cache = {},

    // Reused internal string.
    doTimeout = 'doTimeout',

    // A convenient shortcut.
    aps = Array.prototype.slice;

  // Method: jQuery.doTimeout
  //
  // Initialize, cancel, or force execution of a callback after a delay.
  //
  // If delay and callback are specified, a doTimeout is initialized. The
  // callback will execute, asynchronously, after the delay. If an id is
  // specified, this doTimeout will override and cancel any existing doTimeout
  // with the same id. Any additional arguments will be passed into callback
  // when it is executed.
  //
  // If the callback returns true, the doTimeout loop will execute again, after
  // the delay, creating a polling loop until the callback returns a non-true
  // value.
  //
  // Note that if an id is not passed as the first argument, this doTimeout will
  // NOT be able to be manually canceled or forced. (for debouncing, be sure to
  // specify an id).
  //
  // If id is specified, but delay and callback are not, the doTimeout will be
  // canceled without executing the callback. If force_mode is specified, the
  // callback will be executed, synchronously, but will only be allowed to
  // continue a polling loop if force_mode is true (provided the callback
  // returns true, of course). If force_mode is false, no polling loop will
  // continue, even if the callback returns true.
  //
  // Usage:
  //
  // > jQuery.doTimeout( [ id, ] delay, callback [, arg ... ] );
  // > jQuery.doTimeout( id [, force_mode ] );
  //
  // Arguments:
  //
  //  id - (String) An optional unique identifier for this doTimeout. If id is
  //    not specified, the doTimeout will NOT be able to be manually canceled or
  //    forced.
  //  delay - (Number) A zero-or-greater delay in milliseconds after which
  //    callback will be executed.
  //  callback - (Function) A function to be executed after delay milliseconds.
  //  callback - (String) A jQuery method to be executed after delay
  //    milliseconds. This method will only poll if it explicitly returns
  //    true.
  //  force_mode - (Boolean) If true, execute that id's doTimeout callback
  //    immediately and synchronously, continuing any callback return-true
  //    polling loop. If false, execute the callback immediately and
  //    synchronously but do NOT continue a callback return-true polling loop.
  //    If omitted, cancel that id's doTimeout.
  //
  // Returns:
  //
  //  If force_mode is true, false or undefined and there is a
  //  yet-to-be-executed callback to cancel, true is returned, but if no
  //  callback remains to be executed, undefined is returned.

  $[doTimeout] = function() {
    return p_doTimeout.apply( window, [ 0 ].concat( aps.call( arguments ) ) );
  };

  // Method: jQuery.fn.doTimeout
  //
  // Initialize, cancel, or force execution of a callback after a delay.
  // Operates like <jQuery.doTimeout>, but the passed callback executes in the
  // context of the jQuery collection of elements, and the id is stored as data
  // on the first element in that collection.
  //
  // If delay and callback are specified, a doTimeout is initialized. The
  // callback will execute, asynchronously, after the delay. If an id is
  // specified, this doTimeout will override and cancel any existing doTimeout
  // with the same id. Any additional arguments will be passed into callback
  // when it is executed.
  //
  // If the callback returns true, the doTimeout loop will execute again, after
  // the delay, creating a polling loop until the callback returns a non-true
  // value.
  //
  // Note that if an id is not passed as the first argument, this doTimeout will
  // NOT be able to be manually canceled or forced (for debouncing, be sure to
  // specify an id).
  //
  // If id is specified, but delay and callback are not, the doTimeout will be
  // canceled without executing the callback. If force_mode is specified, the
  // callback will be executed, synchronously, but will only be allowed to
  // continue a polling loop if force_mode is true (provided the callback
  // returns true, of course). If force_mode is false, no polling loop will
  // continue, even if the callback returns true.
  //
  // Usage:
  //
  // > jQuery('selector').doTimeout( [ id, ] delay, callback [, arg ... ] );
  // > jQuery('selector').doTimeout( id [, force_mode ] );
  //
  // Arguments:
  //
  //  id - (String) An optional unique identifier for this doTimeout, stored as
  //    jQuery data on the element. If id is not specified, the doTimeout will
  //    NOT be able to be manually canceled or forced.
  //  delay - (Number) A zero-or-greater delay in milliseconds after which
  //    callback will be executed.
  //  callback - (Function) A function to be executed after delay milliseconds.
  //  callback - (String) A jQuery.fn method to be executed after delay
  //    milliseconds. This method will only poll if it explicitly returns
  //    true (most jQuery.fn methods return a jQuery object, and not `true`,
  //    which allows them to be chained and prevents polling).
  //  force_mode - (Boolean) If true, execute that id's doTimeout callback
  //    immediately and synchronously, continuing any callback return-true
  //    polling loop. If false, execute the callback immediately and
  //    synchronously but do NOT continue a callback return-true polling loop.
  //    If omitted, cancel that id's doTimeout.
  //
  // Returns:
  //
  //  When creating a <jQuery.fn.doTimeout>, the initial jQuery collection of
  //  elements is returned. Otherwise, if force_mode is true, false or undefined
  //  and there is a yet-to-be-executed callback to cancel, true is returned,
  //  but if no callback remains to be executed, undefined is returned.

  $.fn[doTimeout] = function() {
    var args = aps.call( arguments ),
      result = p_doTimeout.apply( this, [ doTimeout + args[0] ].concat( args ) );

    return typeof args[0] === 'number' || typeof args[1] === 'number'
      ? this
      : result;
  };

  function p_doTimeout( jquery_data_key ) {
    var that = this,
      elem,
      data = {},

      // Allows the plugin to call a string callback method.
      method_base = jquery_data_key ? $.fn : $,

      // Any additional arguments will be passed to the callback.
      args = arguments,
      slice_args = 4,

      id        = args[1],
      delay     = args[2],
      callback  = args[3];

    if ( typeof id !== 'string' ) {
      slice_args--;

      id        = jquery_data_key = 0;
      delay     = args[1];
      callback  = args[2];
    }

    // If id is passed, store a data reference either as .data on the first
    // element in a jQuery collection, or in the internal cache.
    if ( jquery_data_key ) { // Note: key is 'doTimeout' + id

      // Get id-object from the first element's data, otherwise initialize it to {}.
      elem = that.eq(0);
      elem.data( jquery_data_key, data = elem.data( jquery_data_key ) || {} );

    } else if ( id ) {
      // Get id-object from the cache, otherwise initialize it to {}.
      data = cache[ id ] || ( cache[ id ] = {} );
    }

    // Clear any existing timeout for this id.
    data.id && clearTimeout( data.id );
    delete data.id;

    // Clean up when necessary.
    function cleanup() {
      if ( jquery_data_key ) {
        elem.removeData( jquery_data_key );
      } else if ( id ) {
        delete cache[ id ];
      }
    };

    // Yes, there actually is a setTimeout call in here!
    function actually_setTimeout() {
      data.id = setTimeout( function(){ data.fn(); }, delay );
    };

    if ( callback ) {
      // A callback (and delay) were specified. Store the callback reference for
      // possible later use, and then setTimeout.
      data.fn = function( no_polling_loop ) {

        // If the callback value is a string, it is assumed to be the name of a
        // method on $ or $.fn depending on where doTimeout was executed.
        if ( typeof callback === 'string' ) {
          callback = method_base[ callback ];
        }

        callback.apply( that, aps.call( args, slice_args ) ) === true && !no_polling_loop

          // Since the callback returned true, and we're not specifically
          // canceling a polling loop, do it again!
          ? actually_setTimeout()

          // Otherwise, clean up and quit.
          : cleanup();
      };

      // Set that timeout!
      actually_setTimeout();

    } else if ( data.fn ) {
      // No callback passed. If force_mode (delay) is true, execute the data.fn
      // callback immediately, continuing any callback return-true polling loop.
      // If force_mode is false, execute the data.fn callback immediately but do
      // NOT continue a callback return-true polling loop. If force_mode is
      // undefined, simply clean up. Since data.fn was still defined, whatever
      // was supposed to happen hadn't yet, so return true.
      delay === undefined ? cleanup() : data.fn( delay === false );
      return true;

    } else {
      // Since no callback was passed, and data.fn isn't defined, it looks like
      // whatever was supposed to happen already did. Clean up and quit!
      cleanup();
    }

  };

})(jQuery);



if(typeof($)=="undefined"){
	$ = jQuery;
}

//CSS3 Animate-it
$('.animatedParent').appear();
$('.animatedClick').click(function(){
  var target = $(this).attr('data-target');


  if($(this).attr('data-sequence') != undefined){
    var firstId = $("."+target+":first").attr('data-id');
    var lastId = $("."+target+":last").attr('data-id');
    var number = firstId;

    //Add or remove the class
    if($("."+target+"[data-id="+ number +"]").hasClass('go')){
      $("."+target+"[data-id="+ number +"]").addClass('goAway');
      $("."+target+"[data-id="+ number +"]").removeClass('go');
    }else{
      $("."+target+"[data-id="+ number +"]").addClass('go');
      $("."+target+"[data-id="+ number +"]").removeClass('goAway');
    }
    number ++;
    delay = Number($(this).attr('data-sequence'));
    $.doTimeout(delay, function(){
      console.log(lastId);

      //Add or remove the class
      if($("."+target+"[data-id="+ number +"]").hasClass('go')){
        $("."+target+"[data-id="+ number +"]").addClass('goAway');
        $("."+target+"[data-id="+ number +"]").removeClass('go');
      }else{
        $("."+target+"[data-id="+ number +"]").addClass('go');
        $("."+target+"[data-id="+ number +"]").removeClass('goAway');
      }

      //increment
      ++number;

      //continute looping till reached last ID
      if(number <= lastId){return true;}
    });
  }else{
    if($('.'+target).hasClass('go')){
      $('.'+target).addClass('goAway');
      $('.'+target).removeClass('go');
    }else{
      $('.'+target).addClass('go');
      $('.'+target).removeClass('goAway');
    }
  }
});

$(document.body).on('appear', '.animatedParent', function(e, $affected){
  var ele = $(this).find('.animated');
  var parent = $(this);


  if(parent.attr('data-sequence') != undefined){

    var firstId = $(this).find('.animated:first').attr('data-id');
    var number = firstId;
    var lastId = $(this).find('.animated:last').attr('data-id');

    $(parent).find(".animated[data-id="+ number +"]").addClass('go');
    number ++;
    delay = Number(parent.attr('data-sequence'));

    $.doTimeout(delay, function(){
      $(parent).find(".animated[data-id="+ number +"]").addClass('go');
      ++number;
      if(number <= lastId){return true;}
    });
  }else{
    ele.addClass('go');
  }

});

 $(document.body).on('disappear', '.animatedParent', function(e, $affected) {
  if(!$(this).hasClass('animateOnce')){
    $(this).find('.animated').removeClass('go');
   }
 });

 $(window).on('load',function(){
  $.force_appear();
 });

</script>
<style>
.applybs {
  /*! Source: https://github.com/h5bp/html5-boilerplate/blob/master/src/css/main.css */
}
.applybs html {
  font-family: sans-serif;
  -webkit-text-size-adjust: 100%;
  -ms-text-size-adjust: 100%;
}
.applybs body {
  margin: 0;
}
.applybs article, .applybs aside, .applybs details, .applybs figcaption, .applybs figure, .applybs footer, .applybs header, .applybs hgroup, .applybs main, .applybs menu, .applybs nav, .applybs section, .applybs summary {
  display: block;
}
.applybs audio, .applybs canvas, .applybs progress, .applybs video {
  display: inline-block;
  vertical-align: baseline;
}
.applybs audio:not([controls]) {
  display: none;
  height: 0;
}
.applybs [hidden], .applybs template {
  display: none;
}
.applybs a {
  background-color: transparent;
}
.applybs a:active, .applybs a:hover {
  outline: 0;
}
.applybs abbr[title] {
  border-bottom: 1px dotted;
}
.applybs b, .applybs strong {
  font-weight: 700;
}
.applybs dfn {
  font-style: italic;
}
.applybs h1 {
  margin: .67em 0;
  font-size: 2em;
}
.applybs mark {
  color: #000;
  background: #ff0;
}
.applybs small {
  font-size: 80%;
}
.applybs sub, .applybs sup {
  position: relative;
  font-size: 75%;
  line-height: 0;
  vertical-align: baseline;
}
.applybs sup {
  top: -.5em;
}
.applybs sub {
  bottom: -.25em;
}
.applybs img {
  border: 0;
}
.applybs svg:not(:root) {
  overflow: hidden;
}
.applybs figure {
  margin: 1em 40px;
}
.applybs hr {
  height: 0;
  -webkit-box-sizing: content-box;
  -moz-box-sizing: content-box;
  box-sizing: content-box;
}
.applybs pre {
  overflow: auto;
}
.applybs code, .applybs kbd, .applybs pre, .applybs samp {
  font-family: monospace,monospace;
  font-size: 1em;
}
.applybs button, .applybs input, .applybs optgroup, .applybs select, .applybs textarea {
  margin: 0;
  font: inherit;
  color: inherit;
}
.applybs button {
  overflow: visible;
}
.applybs button, .applybs select {
  text-transform: none;
}
.applybs button, .applybs html input[type=button], .applybs input[type=reset], .applybs input[type=submit] {
  -webkit-appearance: button;
  cursor: pointer;
}
.applybs button[disabled], .applybs html input[disabled] {
  cursor: default;
}
.applybs button::-moz-focus-inner, .applybs input::-moz-focus-inner {
  padding: 0;
  border: 0;
}
.applybs input {
  line-height: normal;
}
.applybs input[type=checkbox], .applybs input[type=radio] {
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
  padding: 0;
}
.applybs input[type=number]::-webkit-inner-spin-button, .applybs input[type=number]::-webkit-outer-spin-button {
  height: auto;
}
.applybs input[type=search] {
  -webkit-box-sizing: content-box;
  -moz-box-sizing: content-box;
  box-sizing: content-box;
  -webkit-appearance: textfield;
}
.applybs input[type=search]::-webkit-search-cancel-button, .applybs input[type=search]::-webkit-search-decoration {
  -webkit-appearance: none;
}
.applybs fieldset {
  padding: .35em .625em .75em;
  margin: 0 2px;
  border: 1px solid silver;
}
.applybs legend {
  padding: 0;
  border: 0;
}
.applybs textarea {
  overflow: auto;
}
.applybs optgroup {
  font-weight: 700;
}
.applybs table {
  border-spacing: 0;
  border-collapse: collapse;
}
.applybs td, .applybs th {
  padding: 0;
}
@media print {
  .applybs *, .applybs :after, .applybs :before {
    color: #000 !important;
    text-shadow: none !important;
    background: 0 0 !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
  }
  .applybs a, .applybs a:visited {
    text-decoration: underline;
  }
  .applybs a[href]:after {
    content: " (" attr(href) ")";
  }
  .applybs abbr[title]:after {
    content: " (" attr(title) ")";
  }
  .applybs a[href^="javascript:"]:after, .applybs a[href^="#"]:after {
    content: "";
  }
  .applybs blockquote, .applybs pre {
    border: 1px solid #999;
    page-break-inside: avoid;
  }
  .applybs thead {
    display: table-header-group;
  }
  .applybs img, .applybs tr {
    page-break-inside: avoid;
  }
  .applybs img {
    max-width: 100% !important;
  }
  .applybs h2, .applybs h3, .applybs p {
    orphans: 3;
    widows: 3;
  }
  .applybs h2, .applybs h3 {
    page-break-after: avoid;
  }
  .applybs .navbar {
    display: none;
  }
  .applybs .btn > .caret, .applybs .dropup > .btn > .caret {
    border-top-color: #000 !important;
  }
  .applybs .label {
    border: 1px solid #000;
  }
  .applybs .table {
    border-collapse: collapse !important;
  }
  .applybs .table td, .applybs .table th {
    background-color: #fff !important;
  }
  .applybs .table-bordered td, .applybs .table-bordered th {
    border: 1px solid #ddd !important;
  }
}
@font-face {
  .applybs {
    font-family: 'Glyphicons Halflings';
    src: url(../fonts/glyphicons-halflings-regular.eot);
    src: url(../fonts/glyphicons-halflings-regular.eot?#iefix) format("embedded-opentype"), url(../fonts/glyphicons-halflings-regular.woff2) format("woff2"), url(../fonts/glyphicons-halflings-regular.woff) format("woff"), url(../fonts/glyphicons-halflings-regular.ttf) format("truetype"), url(../fonts/glyphicons-halflings-regular.svg#glyphicons_halflingsregular) format("svg");
  }
}
.applybs .glyphicon {
  position: relative;
  top: 1px;
  display: inline-block;
  font-family: 'Glyphicons Halflings';
  font-style: normal;
  font-weight: 400;
  line-height: 1;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}
.applybs .glyphicon-asterisk:before {
  content: "\002a";
}
.applybs .glyphicon-plus:before {
  content: "\002b";
}
.applybs .glyphicon-eur:before, .applybs .glyphicon-euro:before {
  content: "\20ac";
}
.applybs .glyphicon-minus:before {
  content: "\2212";
}
.applybs .glyphicon-cloud:before {
  content: "\2601";
}
.applybs .glyphicon-envelope:before {
  content: "\2709";
}
.applybs .glyphicon-pencil:before {
  content: "\270f";
}
.applybs .glyphicon-glass:before {
  content: "\e001";
}
.applybs .glyphicon-music:before {
  content: "\e002";
}
.applybs .glyphicon-search:before {
  content: "\e003";
}
.applybs .glyphicon-heart:before {
  content: "\e005";
}
.applybs .glyphicon-star:before {
  content: "\e006";
}
.applybs .glyphicon-star-empty:before {
  content: "\e007";
}
.applybs .glyphicon-user:before {
  content: "\e008";
}
.applybs .glyphicon-film:before {
  content: "\e009";
}
.applybs .glyphicon-th-large:before {
  content: "\e010";
}
.applybs .glyphicon-th:before {
  content: "\e011";
}
.applybs .glyphicon-th-list:before {
  content: "\e012";
}
.applybs .glyphicon-ok:before {
  content: "\e013";
}
.applybs .glyphicon-remove:before {
  content: "\e014";
}
.applybs .glyphicon-zoom-in:before {
  content: "\e015";
}
.applybs .glyphicon-zoom-out:before {
  content: "\e016";
}
.applybs .glyphicon-off:before {
  content: "\e017";
}
.applybs .glyphicon-signal:before {
  content: "\e018";
}
.applybs .glyphicon-cog:before {
  content: "\e019";
}
.applybs .glyphicon-trash:before {
  content: "\e020";
}
.applybs .glyphicon-home:before {
  content: "\e021";
}
.applybs .glyphicon-file:before {
  content: "\e022";
}
.applybs .glyphicon-time:before {
  content: "\e023";
}
.applybs .glyphicon-road:before {
  content: "\e024";
}
.applybs .glyphicon-download-alt:before {
  content: "\e025";
}
.applybs .glyphicon-download:before {
  content: "\e026";
}
.applybs .glyphicon-upload:before {
  content: "\e027";
}
.applybs .glyphicon-inbox:before {
  content: "\e028";
}
.applybs .glyphicon-play-circle:before {
  content: "\e029";
}
.applybs .glyphicon-repeat:before {
  content: "\e030";
}
.applybs .glyphicon-refresh:before {
  content: "\e031";
}
.applybs .glyphicon-list-alt:before {
  content: "\e032";
}
.applybs .glyphicon-lock:before {
  content: "\e033";
}
.applybs .glyphicon-flag:before {
  content: "\e034";
}
.applybs .glyphicon-headphones:before {
  content: "\e035";
}
.applybs .glyphicon-volume-off:before {
  content: "\e036";
}
.applybs .glyphicon-volume-down:before {
  content: "\e037";
}
.applybs .glyphicon-volume-up:before {
  content: "\e038";
}
.applybs .glyphicon-qrcode:before {
  content: "\e039";
}
.applybs .glyphicon-barcode:before {
  content: "\e040";
}
.applybs .glyphicon-tag:before {
  content: "\e041";
}
.applybs .glyphicon-tags:before {
  content: "\e042";
}
.applybs .glyphicon-book:before {
  content: "\e043";
}
.applybs .glyphicon-bookmark:before {
  content: "\e044";
}
.applybs .glyphicon-print:before {
  content: "\e045";
}
.applybs .glyphicon-camera:before {
  content: "\e046";
}
.applybs .glyphicon-font:before {
  content: "\e047";
}
.applybs .glyphicon-bold:before {
  content: "\e048";
}
.applybs .glyphicon-italic:before {
  content: "\e049";
}
.applybs .glyphicon-text-height:before {
  content: "\e050";
}
.applybs .glyphicon-text-width:before {
  content: "\e051";
}
.applybs .glyphicon-align-left:before {
  content: "\e052";
}
.applybs .glyphicon-align-center:before {
  content: "\e053";
}
.applybs .glyphicon-align-right:before {
  content: "\e054";
}
.applybs .glyphicon-align-justify:before {
  content: "\e055";
}
.applybs .glyphicon-list:before {
  content: "\e056";
}
.applybs .glyphicon-indent-left:before {
  content: "\e057";
}
.applybs .glyphicon-indent-right:before {
  content: "\e058";
}
.applybs .glyphicon-facetime-video:before {
  content: "\e059";
}
.applybs .glyphicon-picture:before {
  content: "\e060";
}
.applybs .glyphicon-map-marker:before {
  content: "\e062";
}
.applybs .glyphicon-adjust:before {
  content: "\e063";
}
.applybs .glyphicon-tint:before {
  content: "\e064";
}
.applybs .glyphicon-edit:before {
  content: "\e065";
}
.applybs .glyphicon-share:before {
  content: "\e066";
}
.applybs .glyphicon-check:before {
  content: "\e067";
}
.applybs .glyphicon-move:before {
  content: "\e068";
}
.applybs .glyphicon-step-backward:before {
  content: "\e069";
}
.applybs .glyphicon-fast-backward:before {
  content: "\e070";
}
.applybs .glyphicon-backward:before {
  content: "\e071";
}
.applybs .glyphicon-play:before {
  content: "\e072";
}
.applybs .glyphicon-pause:before {
  content: "\e073";
}
.applybs .glyphicon-stop:before {
  content: "\e074";
}
.applybs .glyphicon-forward:before {
  content: "\e075";
}
.applybs .glyphicon-fast-forward:before {
  content: "\e076";
}
.applybs .glyphicon-step-forward:before {
  content: "\e077";
}
.applybs .glyphicon-eject:before {
  content: "\e078";
}
.applybs .glyphicon-chevron-left:before {
  content: "\e079";
}
.applybs .glyphicon-chevron-right:before {
  content: "\e080";
}
.applybs .glyphicon-plus-sign:before {
  content: "\e081";
}
.applybs .glyphicon-minus-sign:before {
  content: "\e082";
}
.applybs .glyphicon-remove-sign:before {
  content: "\e083";
}
.applybs .glyphicon-ok-sign:before {
  content: "\e084";
}
.applybs .glyphicon-question-sign:before {
  content: "\e085";
}
.applybs .glyphicon-info-sign:before {
  content: "\e086";
}
.applybs .glyphicon-screenshot:before {
  content: "\e087";
}
.applybs .glyphicon-remove-circle:before {
  content: "\e088";
}
.applybs .glyphicon-ok-circle:before {
  content: "\e089";
}
.applybs .glyphicon-ban-circle:before {
  content: "\e090";
}
.applybs .glyphicon-arrow-left:before {
  content: "\e091";
}
.applybs .glyphicon-arrow-right:before {
  content: "\e092";
}
.applybs .glyphicon-arrow-up:before {
  content: "\e093";
}
.applybs .glyphicon-arrow-down:before {
  content: "\e094";
}
.applybs .glyphicon-share-alt:before {
  content: "\e095";
}
.applybs .glyphicon-resize-full:before {
  content: "\e096";
}
.applybs .glyphicon-resize-small:before {
  content: "\e097";
}
.applybs .glyphicon-exclamation-sign:before {
  content: "\e101";
}
.applybs .glyphicon-gift:before {
  content: "\e102";
}
.applybs .glyphicon-leaf:before {
  content: "\e103";
}
.applybs .glyphicon-fire:before {
  content: "\e104";
}
.applybs .glyphicon-eye-open:before {
  content: "\e105";
}
.applybs .glyphicon-eye-close:before {
  content: "\e106";
}
.applybs .glyphicon-warning-sign:before {
  content: "\e107";
}
.applybs .glyphicon-plane:before {
  content: "\e108";
}
.applybs .glyphicon-calendar:before {
  content: "\e109";
}
.applybs .glyphicon-random:before {
  content: "\e110";
}
.applybs .glyphicon-comment:before {
  content: "\e111";
}
.applybs .glyphicon-magnet:before {
  content: "\e112";
}
.applybs .glyphicon-chevron-up:before {
  content: "\e113";
}
.applybs .glyphicon-chevron-down:before {
  content: "\e114";
}
.applybs .glyphicon-retweet:before {
  content: "\e115";
}
.applybs .glyphicon-shopping-cart:before {
  content: "\e116";
}
.applybs .glyphicon-folder-close:before {
  content: "\e117";
}
.applybs .glyphicon-folder-open:before {
  content: "\e118";
}
.applybs .glyphicon-resize-vertical:before {
  content: "\e119";
}
.applybs .glyphicon-resize-horizontal:before {
  content: "\e120";
}
.applybs .glyphicon-hdd:before {
  content: "\e121";
}
.applybs .glyphicon-bullhorn:before {
  content: "\e122";
}
.applybs .glyphicon-bell:before {
  content: "\e123";
}
.applybs .glyphicon-certificate:before {
  content: "\e124";
}
.applybs .glyphicon-thumbs-up:before {
  content: "\e125";
}
.applybs .glyphicon-thumbs-down:before {
  content: "\e126";
}
.applybs .glyphicon-hand-right:before {
  content: "\e127";
}
.applybs .glyphicon-hand-left:before {
  content: "\e128";
}
.applybs .glyphicon-hand-up:before {
  content: "\e129";
}
.applybs .glyphicon-hand-down:before {
  content: "\e130";
}
.applybs .glyphicon-circle-arrow-right:before {
  content: "\e131";
}
.applybs .glyphicon-circle-arrow-left:before {
  content: "\e132";
}
.applybs .glyphicon-circle-arrow-up:before {
  content: "\e133";
}
.applybs .glyphicon-circle-arrow-down:before {
  content: "\e134";
}
.applybs .glyphicon-globe:before {
  content: "\e135";
}
.applybs .glyphicon-wrench:before {
  content: "\e136";
}
.applybs .glyphicon-tasks:before {
  content: "\e137";
}
.applybs .glyphicon-filter:before {
  content: "\e138";
}
.applybs .glyphicon-briefcase:before {
  content: "\e139";
}
.applybs .glyphicon-fullscreen:before {
  content: "\e140";
}
.applybs .glyphicon-dashboard:before {
  content: "\e141";
}
.applybs .glyphicon-paperclip:before {
  content: "\e142";
}
.applybs .glyphicon-heart-empty:before {
  content: "\e143";
}
.applybs .glyphicon-link:before {
  content: "\e144";
}
.applybs .glyphicon-phone:before {
  content: "\e145";
}
.applybs .glyphicon-pushpin:before {
  content: "\e146";
}
.applybs .glyphicon-usd:before {
  content: "\e148";
}
.applybs .glyphicon-gbp:before {
  content: "\e149";
}
.applybs .glyphicon-sort:before {
  content: "\e150";
}
.applybs .glyphicon-sort-by-alphabet:before {
  content: "\e151";
}
.applybs .glyphicon-sort-by-alphabet-alt:before {
  content: "\e152";
}
.applybs .glyphicon-sort-by-order:before {
  content: "\e153";
}
.applybs .glyphicon-sort-by-order-alt:before {
  content: "\e154";
}
.applybs .glyphicon-sort-by-attributes:before {
  content: "\e155";
}
.applybs .glyphicon-sort-by-attributes-alt:before {
  content: "\e156";
}
.applybs .glyphicon-unchecked:before {
  content: "\e157";
}
.applybs .glyphicon-expand:before {
  content: "\e158";
}
.applybs .glyphicon-collapse-down:before {
  content: "\e159";
}
.applybs .glyphicon-collapse-up:before {
  content: "\e160";
}
.applybs .glyphicon-log-in:before {
  content: "\e161";
}
.applybs .glyphicon-flash:before {
  content: "\e162";
}
.applybs .glyphicon-log-out:before {
  content: "\e163";
}
.applybs .glyphicon-new-window:before {
  content: "\e164";
}
.applybs .glyphicon-record:before {
  content: "\e165";
}
.applybs .glyphicon-save:before {
  content: "\e166";
}
.applybs .glyphicon-open:before {
  content: "\e167";
}
.applybs .glyphicon-saved:before {
  content: "\e168";
}
.applybs .glyphicon-import:before {
  content: "\e169";
}
.applybs .glyphicon-export:before {
  content: "\e170";
}
.applybs .glyphicon-send:before {
  content: "\e171";
}
.applybs .glyphicon-floppy-disk:before {
  content: "\e172";
}
.applybs .glyphicon-floppy-saved:before {
  content: "\e173";
}
.applybs .glyphicon-floppy-remove:before {
  content: "\e174";
}
.applybs .glyphicon-floppy-save:before {
  content: "\e175";
}
.applybs .glyphicon-floppy-open:before {
  content: "\e176";
}
.applybs .glyphicon-credit-card:before {
  content: "\e177";
}
.applybs .glyphicon-transfer:before {
  content: "\e178";
}
.applybs .glyphicon-cutlery:before {
  content: "\e179";
}
.applybs .glyphicon-header:before {
  content: "\e180";
}
.applybs .glyphicon-compressed:before {
  content: "\e181";
}
.applybs .glyphicon-earphone:before {
  content: "\e182";
}
.applybs .glyphicon-phone-alt:before {
  content: "\e183";
}
.applybs .glyphicon-tower:before {
  content: "\e184";
}
.applybs .glyphicon-stats:before {
  content: "\e185";
}
.applybs .glyphicon-sd-video:before {
  content: "\e186";
}
.applybs .glyphicon-hd-video:before {
  content: "\e187";
}
.applybs .glyphicon-subtitles:before {
  content: "\e188";
}
.applybs .glyphicon-sound-stereo:before {
  content: "\e189";
}
.applybs .glyphicon-sound-dolby:before {
  content: "\e190";
}
.applybs .glyphicon-sound-5-1:before {
  content: "\e191";
}
.applybs .glyphicon-sound-6-1:before {
  content: "\e192";
}
.applybs .glyphicon-sound-7-1:before {
  content: "\e193";
}
.applybs .glyphicon-copyright-mark:before {
  content: "\e194";
}
.applybs .glyphicon-registration-mark:before {
  content: "\e195";
}
.applybs .glyphicon-cloud-download:before {
  content: "\e197";
}
.applybs .glyphicon-cloud-upload:before {
  content: "\e198";
}
.applybs .glyphicon-tree-conifer:before {
  content: "\e199";
}
.applybs .glyphicon-tree-deciduous:before {
  content: "\e200";
}
.applybs .glyphicon-cd:before {
  content: "\e201";
}
.applybs .glyphicon-save-file:before {
  content: "\e202";
}
.applybs .glyphicon-open-file:before {
  content: "\e203";
}
.applybs .glyphicon-level-up:before {
  content: "\e204";
}
.applybs .glyphicon-copy:before {
  content: "\e205";
}
.applybs .glyphicon-paste:before {
  content: "\e206";
}
.applybs .glyphicon-alert:before {
  content: "\e209";
}
.applybs .glyphicon-equalizer:before {
  content: "\e210";
}
.applybs .glyphicon-king:before {
  content: "\e211";
}
.applybs .glyphicon-queen:before {
  content: "\e212";
}
.applybs .glyphicon-pawn:before {
  content: "\e213";
}
.applybs .glyphicon-bishop:before {
  content: "\e214";
}
.applybs .glyphicon-knight:before {
  content: "\e215";
}
.applybs .glyphicon-baby-formula:before {
  content: "\e216";
}
.applybs .glyphicon-tent:before {
  content: "\26fa";
}
.applybs .glyphicon-blackboard:before {
  content: "\e218";
}
.applybs .glyphicon-bed:before {
  content: "\e219";
}
.applybs .glyphicon-apple:before {
  content: "\f8ff";
}
.applybs .glyphicon-erase:before {
  content: "\e221";
}
.applybs .glyphicon-hourglass:before {
  content: "\231b";
}
.applybs .glyphicon-lamp:before {
  content: "\e223";
}
.applybs .glyphicon-duplicate:before {
  content: "\e224";
}
.applybs .glyphicon-piggy-bank:before {
  content: "\e225";
}
.applybs .glyphicon-scissors:before {
  content: "\e226";
}
.applybs .glyphicon-bitcoin:before {
  content: "\e227";
}
.applybs .glyphicon-btc:before {
  content: "\e227";
}
.applybs .glyphicon-xbt:before {
  content: "\e227";
}
.applybs .glyphicon-yen:before {
  content: "\00a5";
}
.applybs .glyphicon-jpy:before {
  content: "\00a5";
}
.applybs .glyphicon-ruble:before {
  content: "\20bd";
}
.applybs .glyphicon-rub:before {
  content: "\20bd";
}
.applybs .glyphicon-scale:before {
  content: "\e230";
}
.applybs .glyphicon-ice-lolly:before {
  content: "\e231";
}
.applybs .glyphicon-ice-lolly-tasted:before {
  content: "\e232";
}
.applybs .glyphicon-education:before {
  content: "\e233";
}
.applybs .glyphicon-option-horizontal:before {
  content: "\e234";
}
.applybs .glyphicon-option-vertical:before {
  content: "\e235";
}
.applybs .glyphicon-menu-hamburger:before {
  content: "\e236";
}
.applybs .glyphicon-modal-window:before {
  content: "\e237";
}
.applybs .glyphicon-oil:before {
  content: "\e238";
}
.applybs .glyphicon-grain:before {
  content: "\e239";
}
.applybs .glyphicon-sunglasses:before {
  content: "\e240";
}
.applybs .glyphicon-text-size:before {
  content: "\e241";
}
.applybs .glyphicon-text-color:before {
  content: "\e242";
}
.applybs .glyphicon-text-background:before {
  content: "\e243";
}
.applybs .glyphicon-object-align-top:before {
  content: "\e244";
}
.applybs .glyphicon-object-align-bottom:before {
  content: "\e245";
}
.applybs .glyphicon-object-align-horizontal:before {
  content: "\e246";
}
.applybs .glyphicon-object-align-left:before {
  content: "\e247";
}
.applybs .glyphicon-object-align-vertical:before {
  content: "\e248";
}
.applybs .glyphicon-object-align-right:before {
  content: "\e249";
}
.applybs .glyphicon-triangle-right:before {
  content: "\e250";
}
.applybs .glyphicon-triangle-left:before {
  content: "\e251";
}
.applybs .glyphicon-triangle-bottom:before {
  content: "\e252";
}
.applybs .glyphicon-triangle-top:before {
  content: "\e253";
}
.applybs .glyphicon-console:before {
  content: "\e254";
}
.applybs .glyphicon-superscript:before {
  content: "\e255";
}
.applybs .glyphicon-subscript:before {
  content: "\e256";
}
.applybs .glyphicon-menu-left:before {
  content: "\e257";
}
.applybs .glyphicon-menu-right:before {
  content: "\e258";
}
.applybs .glyphicon-menu-down:before {
  content: "\e259";
}
.applybs .glyphicon-menu-up:before {
  content: "\e260";
}
.applybs * {
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
}
.applybs :after, .applybs :before {
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
}
.applybs html {
  font-size: 10px;
  -webkit-tap-highlight-color: transparent;
}
.applybs body {
  font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
  font-size: 14px;
  line-height: 1.42857143;
  color: #333;
  background-color: #fff;
}
.applybs button, .applybs input, .applybs select, .applybs textarea {
  font-family: inherit;
  font-size: inherit;
  line-height: inherit;
}
.applybs a {
  color: #337ab7;
  text-decoration: none;
}
.applybs a:focus, .applybs a:hover {
  color: #23527c;
  text-decoration: underline;
}
.applybs a:focus {
  outline: 5px auto -webkit-focus-ring-color;
  outline-offset: -2px;
}
.applybs figure {
  margin: 0;
}
.applybs img {
  vertical-align: middle;
}
.applybs .carousel-inner > .item > a > img, .applybs .carousel-inner > .item > img, .applybs .img-responsive, .applybs .thumbnail a > img, .applybs .thumbnail > img {
  display: block;
  max-width: 100%;
  height: auto;
}
.applybs .img-rounded {
  border-radius: 6px;
}
.applybs .img-thumbnail {
  display: inline-block;
  max-width: 100%;
  height: auto;
  padding: 4px;
  line-height: 1.42857143;
  background-color: #fff;
  border: 1px solid #ddd;
  border-radius: 4px;
  -webkit-transition: all .2s ease-in-out;
  -o-transition: all .2s ease-in-out;
  transition: all .2s ease-in-out;
}
.applybs .img-circle {
  border-radius: 50%;
}
.applybs hr {
  margin-top: 20px;
  margin-bottom: 20px;
  border: 0;
  border-top: 1px solid #eee;
}
.applybs .sr-only {
  position: absolute;
  width: 1px;
  height: 1px;
  padding: 0;
  margin: -1px;
  overflow: hidden;
  clip: rect(0, 0, 0, 0);
  border: 0;
}
.applybs .sr-only-focusable:active, .applybs .sr-only-focusable:focus {
  position: static;
  width: auto;
  height: auto;
  margin: 0;
  overflow: visible;
  clip: auto;
}
.applybs [role=button] {
  cursor: pointer;
}
.applybs .h1, .applybs .h2, .applybs .h3, .applybs .h4, .applybs .h5, .applybs .h6, .applybs h1, .applybs h2, .applybs h3, .applybs h4, .applybs h5, .applybs h6 {
  font-family: inherit;
  font-weight: 500;
  line-height: 1.1;
  color: inherit;
}
.applybs .h1 .small, .applybs .h1 small, .applybs .h2 .small, .applybs .h2 small, .applybs .h3 .small, .applybs .h3 small, .applybs .h4 .small, .applybs .h4 small, .applybs .h5 .small, .applybs .h5 small, .applybs .h6 .small, .applybs .h6 small, .applybs h1 .small, .applybs h1 small, .applybs h2 .small, .applybs h2 small, .applybs h3 .small, .applybs h3 small, .applybs h4 .small, .applybs h4 small, .applybs h5 .small, .applybs h5 small, .applybs h6 .small, .applybs h6 small {
  font-weight: 400;
  line-height: 1;
  color: #777;
}
.applybs .h1, .applybs .h2, .applybs .h3, .applybs h1, .applybs h2, .applybs h3 {
  margin-top: 20px;
  margin-bottom: 10px;
}
.applybs .h1 .small, .applybs .h1 small, .applybs .h2 .small, .applybs .h2 small, .applybs .h3 .small, .applybs .h3 small, .applybs h1 .small, .applybs h1 small, .applybs h2 .small, .applybs h2 small, .applybs h3 .small, .applybs h3 small {
  font-size: 65%;
}
.applybs .h4, .applybs .h5, .applybs .h6, .applybs h4, .applybs h5, .applybs h6 {
  margin-top: 10px;
  margin-bottom: 10px;
}
.applybs .h4 .small, .applybs .h4 small, .applybs .h5 .small, .applybs .h5 small, .applybs .h6 .small, .applybs .h6 small, .applybs h4 .small, .applybs h4 small, .applybs h5 .small, .applybs h5 small, .applybs h6 .small, .applybs h6 small {
  font-size: 75%;
}
.applybs .h1, .applybs h1 {
  font-size: 36px;
}
.applybs .h2, .applybs h2 {
  font-size: 30px;
}
.applybs .h3, .applybs h3 {
  font-size: 24px;
}
.applybs .h4, .applybs h4 {
  font-size: 18px;
}
.applybs .h5, .applybs h5 {
  font-size: 14px;
}
.applybs .h6, .applybs h6 {
  font-size: 12px;
}
.applybs p {
  margin: 0 0 10px;
	color:#333333;
	font-size: 16px;
}
.applybs .lead {
  margin-bottom: 20px;
  font-size: 16px;
  font-weight: 300;
  line-height: 1.4;
}
@media (min-width: 768px) {
  .applybs .lead {
    font-size: 21px;
  }
}
.applybs .small, .applybs small {
  font-size: 85%;
}
.applybs .mark, .applybs mark {
  padding: .2em;
  background-color: #fcf8e3;
}
.applybs .text-left {
  text-align: left;
}
.applybs .text-right {
  text-align: right;
}
.applybs .text-center {
  text-align: center;
}
.applybs .text-justify {
  text-align: justify;
}
.applybs .text-nowrap {
  white-space: nowrap;
}
.applybs .text-lowercase {
  text-transform: lowercase;
}
.applybs .text-uppercase {
  text-transform: uppercase;
}
.applybs .text-capitalize {
  text-transform: capitalize;
}
.applybs .text-muted {
  color: #777;
}
.applybs .text-primary {
  color: #337ab7;
}
.applybs a.text-primary:focus, .applybs a.text-primary:hover {
  color: #286090;
}
.applybs .text-success {
  color: #3c763d;
}
.applybs a.text-success:focus, .applybs a.text-success:hover {
  color: #2b542c;
}
.applybs .text-info {
  color: #31708f;
}
.applybs a.text-info:focus, .applybs a.text-info:hover {
  color: #245269;
}
.applybs .text-warning {
  color: #8a6d3b;
}
.applybs a.text-warning:focus, .applybs a.text-warning:hover {
  color: #66512c;
}
.applybs .text-danger {
  color: #a94442;
}
.applybs a.text-danger:focus, .applybs a.text-danger:hover {
  color: #843534;
}
.applybs .bg-primary {
  color: #fff;
  background-color: #337ab7;
}
.applybs a.bg-primary:focus, .applybs a.bg-primary:hover {
  background-color: #286090;
}
.applybs .bg-success {
  background-color: #dff0d8;
}
.applybs a.bg-success:focus, .applybs a.bg-success:hover {
  background-color: #c1e2b3;
}
.applybs .bg-info {
  background-color: #d9edf7;
}
.applybs a.bg-info:focus, .applybs a.bg-info:hover {
  background-color: #afd9ee;
}
.applybs .bg-warning {
  background-color: #fcf8e3;
}
.applybs a.bg-warning:focus, .applybs a.bg-warning:hover {
  background-color: #f7ecb5;
}
.applybs .bg-danger {
  background-color: #f2dede;
}
.applybs a.bg-danger:focus, .applybs a.bg-danger:hover {
  background-color: #e4b9b9;
}
.applybs .page-header {
  padding-bottom: 9px;
  margin: 40px 0 20px;
  border-bottom: 1px solid #eee;
}
.applybs ol, .applybs ul {
  margin-top: 0;
  margin-bottom: 10px;
}
.applybs ol ol, .applybs ol ul, .applybs ul ol, .applybs ul ul {
  margin-bottom: 0;
}
.applybs .list-unstyled {
  padding-left: 0;
  list-style: none;
}
.applybs .list-inline {
  padding-left: 0;
  margin-left: -5px;
  list-style: none;
}
.applybs .list-inline > li {
  display: inline-block;
  padding-right: 5px;
  padding-left: 5px;
}
.applybs dl {
  margin-top: 0;
  margin-bottom: 20px;
}
.applybs dd, .applybs dt {
  line-height: 1.42857143;
}
.applybs dt {
  font-weight: 700;
}
.applybs dd {
  margin-left: 0;
}
@media (min-width: 768px) {
  .applybs .dl-horizontal dt {
    float: left;
    width: 160px;
    overflow: hidden;
    clear: left;
    text-align: right;
    text-overflow: ellipsis;
    white-space: nowrap;
  }
  .applybs .dl-horizontal dd {
    margin-left: 180px;
  }
}
.applybs abbr[data-original-title], .applybs abbr[title] {
  cursor: help;
  border-bottom: 1px dotted #777;
}
.applybs .initialism {
  font-size: 90%;
  text-transform: uppercase;
}
.applybs blockquote {
  padding: 10px 20px;
  margin: 0 0 20px;
  font-size: 17.5px;
  border-left: 5px solid #eee;
}
.applybs blockquote ol:last-child, .applybs blockquote p:last-child, .applybs blockquote ul:last-child {
  margin-bottom: 0;
}
.applybs blockquote .small, .applybs blockquote footer, .applybs blockquote small {
  display: block;
  font-size: 80%;
  line-height: 1.42857143;
  color: #777;
}
.applybs blockquote .small:before, .applybs blockquote footer:before, .applybs blockquote small:before {
  content: '\2014 \00A0';
}
.applybs .blockquote-reverse, .applybs blockquote.pull-right {
  padding-right: 15px;
  padding-left: 0;
  text-align: right;
  border-right: 5px solid #eee;
  border-left: 0;
}
.applybs .blockquote-reverse .small:before, .applybs .blockquote-reverse footer:before, .applybs .blockquote-reverse small:before, .applybs blockquote.pull-right .small:before, .applybs blockquote.pull-right footer:before, .applybs blockquote.pull-right small:before {
  content: '';
}
.applybs .blockquote-reverse .small:after, .applybs .blockquote-reverse footer:after, .applybs .blockquote-reverse small:after, .applybs blockquote.pull-right .small:after, .applybs blockquote.pull-right footer:after, .applybs blockquote.pull-right small:after {
  content: '\00A0 \2014';
}
.applybs address {
  margin-bottom: 20px;
  font-style: normal;
  line-height: 1.42857143;
}
.applybs code, .applybs kbd, .applybs pre, .applybs samp {
  font-family: Menlo,Monaco,Consolas,"Courier New",monospace;
}
.applybs code {
  padding: 2px 4px;
  font-size: 90%;
  color: #c7254e;
  background-color: #f9f2f4;
  border-radius: 4px;
}
.applybs kbd {
  padding: 2px 4px;
  font-size: 90%;
  color: #fff;
  background-color: #333;
  border-radius: 3px;
  -webkit-box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.25);
  box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.25);
}
.applybs kbd kbd {
  padding: 0;
  font-size: 100%;
  font-weight: 700;
  -webkit-box-shadow: none;
  box-shadow: none;
}
.applybs pre {
  display: block;
  padding: 9.5px;
  margin: 0 0 10px;
  font-size: 13px;
  line-height: 1.42857143;
  color: #333;
  word-break: break-all;
  word-wrap: break-word;
  background-color: #f5f5f5;
  border: 1px solid #ccc;
  border-radius: 4px;
}
.applybs pre code {
  padding: 0;
  font-size: inherit;
  color: inherit;
  white-space: pre-wrap;
  background-color: transparent;
  border-radius: 0;
}
.applybs .pre-scrollable {
  max-height: 340px;
  overflow-y: scroll;
}
.applybs .container {
  padding-right: 15px;
  padding-left: 15px;
  margin-right: auto;
  margin-left: auto;
}
@media (min-width: 768px) {
  .applybs .container {
    width: 750px;
  }
}
@media (min-width: 992px) {
  .applybs .container {
    width: 970px;
  }
}
@media (min-width: 1200px) {
  .applybs .container {
    width: 1170px;
  }
}
.applybs .container-fluid {
  padding-right: 15px;
  padding-left: 15px;
  margin-right: auto;
  margin-left: auto;
}
.applybs .row {
  margin-right: -15px;
  margin-left: -15px;
}
.applybs .col-lg-1, .applybs .col-lg-10, .applybs .col-lg-11, .applybs .col-lg-12, .applybs .col-lg-2, .applybs .col-lg-3, .applybs .col-lg-4, .applybs .col-lg-5, .applybs .col-lg-6, .applybs .col-lg-7, .applybs .col-lg-8, .applybs .col-lg-9, .applybs .col-md-1, .applybs .col-md-10, .applybs .col-md-11, .applybs .col-md-12, .applybs .col-md-2, .applybs .col-md-3, .applybs .col-md-4, .applybs .col-md-5, .applybs .col-md-6, .applybs .col-md-7, .applybs .col-md-8, .applybs .col-md-9, .applybs .col-sm-1, .applybs .col-sm-10, .applybs .col-sm-11, .applybs .col-sm-12, .applybs .col-sm-2, .applybs .col-sm-3, .applybs .col-sm-4, .applybs .col-sm-5, .applybs .col-sm-6, .applybs .col-sm-7, .applybs .col-sm-8, .applybs .col-sm-9, .applybs .col-xs-1, .applybs .col-xs-10, .applybs .col-xs-11, .applybs .col-xs-12, .applybs .col-xs-2, .applybs .col-xs-3, .applybs .col-xs-4, .applybs .col-xs-5, .applybs .col-xs-6, .applybs .col-xs-7, .applybs .col-xs-8, .applybs .col-xs-9 {
  position: relative;
  min-height: 1px;
  padding-right: 15px;
  padding-left: 15px;
}
.applybs .col-xs-1, .applybs .col-xs-10, .applybs .col-xs-11, .applybs .col-xs-12, .applybs .col-xs-2, .applybs .col-xs-3, .applybs .col-xs-4, .applybs .col-xs-5, .applybs .col-xs-6, .applybs .col-xs-7, .applybs .col-xs-8, .applybs .col-xs-9 {
  float: left;
}
.applybs .col-xs-12 {
  width: 100%;
}
.applybs .col-xs-11 {
  width: 91.66666667%;
}
.applybs .col-xs-10 {
  width: 83.33333333%;
}
.applybs .col-xs-9 {
  width: 75%;
}
.applybs .col-xs-8 {
  width: 66.66666667%;
}
.applybs .col-xs-7 {
  width: 58.33333333%;
}
.applybs .col-xs-6 {
  width: 50%;
}
.applybs .col-xs-5 {
  width: 41.66666667%;
}
.applybs .col-xs-4 {
  width: 33.33333333%;
}
.applybs .col-xs-3 {
  width: 25%;
}
.applybs .col-xs-2 {
  width: 16.66666667%;
}
.applybs .col-xs-1 {
  width: 8.33333333%;
}
.applybs .col-xs-pull-12 {
  right: 100%;
}
.applybs .col-xs-pull-11 {
  right: 91.66666667%;
}
.applybs .col-xs-pull-10 {
  right: 83.33333333%;
}
.applybs .col-xs-pull-9 {
  right: 75%;
}
.applybs .col-xs-pull-8 {
  right: 66.66666667%;
}
.applybs .col-xs-pull-7 {
  right: 58.33333333%;
}
.applybs .col-xs-pull-6 {
  right: 50%;
}
.applybs .col-xs-pull-5 {
  right: 41.66666667%;
}
.applybs .col-xs-pull-4 {
  right: 33.33333333%;
}
.applybs .col-xs-pull-3 {
  right: 25%;
}
.applybs .col-xs-pull-2 {
  right: 16.66666667%;
}
.applybs .col-xs-pull-1 {
  right: 8.33333333%;
}
.applybs .col-xs-pull-0 {
  right: auto;
}
.applybs .col-xs-push-12 {
  left: 100%;
}
.applybs .col-xs-push-11 {
  left: 91.66666667%;
}
.applybs .col-xs-push-10 {
  left: 83.33333333%;
}
.applybs .col-xs-push-9 {
  left: 75%;
}
.applybs .col-xs-push-8 {
  left: 66.66666667%;
}
.applybs .col-xs-push-7 {
  left: 58.33333333%;
}
.applybs .col-xs-push-6 {
  left: 50%;
}
.applybs .col-xs-push-5 {
  left: 41.66666667%;
}
.applybs .col-xs-push-4 {
  left: 33.33333333%;
}
.applybs .col-xs-push-3 {
  left: 25%;
}
.applybs .col-xs-push-2 {
  left: 16.66666667%;
}
.applybs .col-xs-push-1 {
  left: 8.33333333%;
}
.applybs .col-xs-push-0 {
  left: auto;
}
.applybs .col-xs-offset-12 {
  margin-left: 100%;
}
.applybs .col-xs-offset-11 {
  margin-left: 91.66666667%;
}
.applybs .col-xs-offset-10 {
  margin-left: 83.33333333%;
}
.applybs .col-xs-offset-9 {
  margin-left: 75%;
}
.applybs .col-xs-offset-8 {
  margin-left: 66.66666667%;
}
.applybs .col-xs-offset-7 {
  margin-left: 58.33333333%;
}
.applybs .col-xs-offset-6 {
  margin-left: 50%;
}
.applybs .col-xs-offset-5 {
  margin-left: 41.66666667%;
}
.applybs .col-xs-offset-4 {
  margin-left: 33.33333333%;
}
.applybs .col-xs-offset-3 {
  margin-left: 25%;
}
.applybs .col-xs-offset-2 {
  margin-left: 16.66666667%;
}
.applybs .col-xs-offset-1 {
  margin-left: 8.33333333%;
}
.applybs .col-xs-offset-0 {
  margin-left: 0;
}
@media (min-width: 768px) {
  .applybs .col-sm-1, .applybs .col-sm-10, .applybs .col-sm-11, .applybs .col-sm-12, .applybs .col-sm-2, .applybs .col-sm-3, .applybs .col-sm-4, .applybs .col-sm-5, .applybs .col-sm-6, .applybs .col-sm-7, .applybs .col-sm-8, .applybs .col-sm-9 {
    float: left;
  }
  .applybs .col-sm-12 {
    width: 100%;
  }
  .applybs .col-sm-11 {
    width: 91.66666667%;
  }
  .applybs .col-sm-10 {
    width: 83.33333333%;
  }
  .applybs .col-sm-9 {
    width: 75%;
  }
  .applybs .col-sm-8 {
    width: 66.66666667%;
  }
  .applybs .col-sm-7 {
    width: 58.33333333%;
  }
  .applybs .col-sm-6 {
    width: 50%;
  }
  .applybs .col-sm-5 {
    width: 41.66666667%;
  }
  .applybs .col-sm-4 {
    width: 33.33333333%;
  }
  .applybs .col-sm-3 {
    width: 25%;
  }
  .applybs .col-sm-2 {
    width: 16.66666667%;
  }
  .applybs .col-sm-1 {
    width: 8.33333333%;
  }
  .applybs .col-sm-pull-12 {
    right: 100%;
  }
  .applybs .col-sm-pull-11 {
    right: 91.66666667%;
  }
  .applybs .col-sm-pull-10 {
    right: 83.33333333%;
  }
  .applybs .col-sm-pull-9 {
    right: 75%;
  }
  .applybs .col-sm-pull-8 {
    right: 66.66666667%;
  }
  .applybs .col-sm-pull-7 {
    right: 58.33333333%;
  }
  .applybs .col-sm-pull-6 {
    right: 50%;
  }
  .applybs .col-sm-pull-5 {
    right: 41.66666667%;
  }
  .applybs .col-sm-pull-4 {
    right: 33.33333333%;
  }
  .applybs .col-sm-pull-3 {
    right: 25%;
  }
  .applybs .col-sm-pull-2 {
    right: 16.66666667%;
  }
  .applybs .col-sm-pull-1 {
    right: 8.33333333%;
  }
  .applybs .col-sm-pull-0 {
    right: auto;
  }
  .applybs .col-sm-push-12 {
    left: 100%;
  }
  .applybs .col-sm-push-11 {
    left: 91.66666667%;
  }
  .applybs .col-sm-push-10 {
    left: 83.33333333%;
  }
  .applybs .col-sm-push-9 {
    left: 75%;
  }
  .applybs .col-sm-push-8 {
    left: 66.66666667%;
  }
  .applybs .col-sm-push-7 {
    left: 58.33333333%;
  }
  .applybs .col-sm-push-6 {
    left: 50%;
  }
  .applybs .col-sm-push-5 {
    left: 41.66666667%;
  }
  .applybs .col-sm-push-4 {
    left: 33.33333333%;
  }
  .applybs .col-sm-push-3 {
    left: 25%;
  }
  .applybs .col-sm-push-2 {
    left: 16.66666667%;
  }
  .applybs .col-sm-push-1 {
    left: 8.33333333%;
  }
  .applybs .col-sm-push-0 {
    left: auto;
  }
  .applybs .col-sm-offset-12 {
    margin-left: 100%;
  }
  .applybs .col-sm-offset-11 {
    margin-left: 91.66666667%;
  }
  .applybs .col-sm-offset-10 {
    margin-left: 83.33333333%;
  }
  .applybs .col-sm-offset-9 {
    margin-left: 75%;
  }
  .applybs .col-sm-offset-8 {
    margin-left: 66.66666667%;
  }
  .applybs .col-sm-offset-7 {
    margin-left: 58.33333333%;
  }
  .applybs .col-sm-offset-6 {
    margin-left: 50%;
  }
  .applybs .col-sm-offset-5 {
    margin-left: 41.66666667%;
  }
  .applybs .col-sm-offset-4 {
    margin-left: 33.33333333%;
  }
  .applybs .col-sm-offset-3 {
    margin-left: 25%;
  }
  .applybs .col-sm-offset-2 {
    margin-left: 16.66666667%;
  }
  .applybs .col-sm-offset-1 {
    margin-left: 8.33333333%;
  }
  .applybs .col-sm-offset-0 {
    margin-left: 0;
  }
}
@media (min-width: 992px) {
  .applybs .col-md-1, .applybs .col-md-10, .applybs .col-md-11, .applybs .col-md-12, .applybs .col-md-2, .applybs .col-md-3, .applybs .col-md-4, .applybs .col-md-5, .applybs .col-md-6, .applybs .col-md-7, .applybs .col-md-8, .applybs .col-md-9 {
    float: left;
  }
  .applybs .col-md-12 {
    width: 100%;
  }
  .applybs .col-md-11 {
    width: 91.66666667%;
  }
  .applybs .col-md-10 {
    width: 83.33333333%;
  }
  .applybs .col-md-9 {
    width: 75%;
  }
  .applybs .col-md-8 {
    width: 66.66666667%;
  }
  .applybs .col-md-7 {
    width: 58.33333333%;
  }
  .applybs .col-md-6 {
    width: 50%;
  }
  .applybs .col-md-5 {
    width: 41.66666667%;
  }
  .applybs .col-md-4 {
    width: 33.33333333%;
  }
  .applybs .col-md-3 {
    width: 25%;
  }
  .applybs .col-md-2 {
    width: 16.66666667%;
  }
  .applybs .col-md-1 {
    width: 8.33333333%;
  }
  .applybs .col-md-pull-12 {
    right: 100%;
  }
  .applybs .col-md-pull-11 {
    right: 91.66666667%;
  }
  .applybs .col-md-pull-10 {
    right: 83.33333333%;
  }
  .applybs .col-md-pull-9 {
    right: 75%;
  }
  .applybs .col-md-pull-8 {
    right: 66.66666667%;
  }
  .applybs .col-md-pull-7 {
    right: 58.33333333%;
  }
  .applybs .col-md-pull-6 {
    right: 50%;
  }
  .applybs .col-md-pull-5 {
    right: 41.66666667%;
  }
  .applybs .col-md-pull-4 {
    right: 33.33333333%;
  }
  .applybs .col-md-pull-3 {
    right: 25%;
  }
  .applybs .col-md-pull-2 {
    right: 16.66666667%;
  }
  .applybs .col-md-pull-1 {
    right: 8.33333333%;
  }
  .applybs .col-md-pull-0 {
    right: auto;
  }
  .applybs .col-md-push-12 {
    left: 100%;
  }
  .applybs .col-md-push-11 {
    left: 91.66666667%;
  }
  .applybs .col-md-push-10 {
    left: 83.33333333%;
  }
  .applybs .col-md-push-9 {
    left: 75%;
  }
  .applybs .col-md-push-8 {
    left: 66.66666667%;
  }
  .applybs .col-md-push-7 {
    left: 58.33333333%;
  }
  .applybs .col-md-push-6 {
    left: 50%;
  }
  .applybs .col-md-push-5 {
    left: 41.66666667%;
  }
  .applybs .col-md-push-4 {
    left: 33.33333333%;
  }
  .applybs .col-md-push-3 {
    left: 25%;
  }
  .applybs .col-md-push-2 {
    left: 16.66666667%;
  }
  .applybs .col-md-push-1 {
    left: 8.33333333%;
  }
  .applybs .col-md-push-0 {
    left: auto;
  }
  .applybs .col-md-offset-12 {
    margin-left: 100%;
  }
  .applybs .col-md-offset-11 {
    margin-left: 91.66666667%;
  }
  .applybs .col-md-offset-10 {
    margin-left: 83.33333333%;
  }
  .applybs .col-md-offset-9 {
    margin-left: 75%;
  }
  .applybs .col-md-offset-8 {
    margin-left: 66.66666667%;
  }
  .applybs .col-md-offset-7 {
    margin-left: 58.33333333%;
  }
  .applybs .col-md-offset-6 {
    margin-left: 50%;
  }
  .applybs .col-md-offset-5 {
    margin-left: 41.66666667%;
  }
  .applybs .col-md-offset-4 {
    margin-left: 33.33333333%;
  }
  .applybs .col-md-offset-3 {
    margin-left: 25%;
  }
  .applybs .col-md-offset-2 {
    margin-left: 16.66666667%;
  }
  .applybs .col-md-offset-1 {
    margin-left: 8.33333333%;
  }
  .applybs .col-md-offset-0 {
    margin-left: 0;
  }
}
@media (min-width: 1200px) {
  .applybs .col-lg-1, .applybs .col-lg-10, .applybs .col-lg-11, .applybs .col-lg-12, .applybs .col-lg-2, .applybs .col-lg-3, .applybs .col-lg-4, .applybs .col-lg-5, .applybs .col-lg-6, .applybs .col-lg-7, .applybs .col-lg-8, .applybs .col-lg-9 {
    float: left;
  }
  .applybs .col-lg-12 {
    width: 100%;
  }
  .applybs .col-lg-11 {
    width: 91.66666667%;
  }
  .applybs .col-lg-10 {
    width: 83.33333333%;
  }
  .applybs .col-lg-9 {
    width: 75%;
  }
  .applybs .col-lg-8 {
    width: 66.66666667%;
  }
  .applybs .col-lg-7 {
    width: 58.33333333%;
  }
  .applybs .col-lg-6 {
    width: 50%;
  }
  .applybs .col-lg-5 {
    width: 41.66666667%;
  }
  .applybs .col-lg-4 {
    width: 33.33333333%;
  }
  .applybs .col-lg-3 {
    width: 25%;
  }
  .applybs .col-lg-2 {
    width: 16.66666667%;
  }
  .applybs .col-lg-1 {
    width: 8.33333333%;
  }
  .applybs .col-lg-pull-12 {
    right: 100%;
  }
  .applybs .col-lg-pull-11 {
    right: 91.66666667%;
  }
  .applybs .col-lg-pull-10 {
    right: 83.33333333%;
  }
  .applybs .col-lg-pull-9 {
    right: 75%;
  }
  .applybs .col-lg-pull-8 {
    right: 66.66666667%;
  }
  .applybs .col-lg-pull-7 {
    right: 58.33333333%;
  }
  .applybs .col-lg-pull-6 {
    right: 50%;
  }
  .applybs .col-lg-pull-5 {
    right: 41.66666667%;
  }
  .applybs .col-lg-pull-4 {
    right: 33.33333333%;
  }
  .applybs .col-lg-pull-3 {
    right: 25%;
  }
  .applybs .col-lg-pull-2 {
    right: 16.66666667%;
  }
  .applybs .col-lg-pull-1 {
    right: 8.33333333%;
  }
  .applybs .col-lg-pull-0 {
    right: auto;
  }
  .applybs .col-lg-push-12 {
    left: 100%;
  }
  .applybs .col-lg-push-11 {
    left: 91.66666667%;
  }
  .applybs .col-lg-push-10 {
    left: 83.33333333%;
  }
  .applybs .col-lg-push-9 {
    left: 75%;
  }
  .applybs .col-lg-push-8 {
    left: 66.66666667%;
  }
  .applybs .col-lg-push-7 {
    left: 58.33333333%;
  }
  .applybs .col-lg-push-6 {
    left: 50%;
  }
  .applybs .col-lg-push-5 {
    left: 41.66666667%;
  }
  .applybs .col-lg-push-4 {
    left: 33.33333333%;
  }
  .applybs .col-lg-push-3 {
    left: 25%;
  }
  .applybs .col-lg-push-2 {
    left: 16.66666667%;
  }
  .applybs .col-lg-push-1 {
    left: 8.33333333%;
  }
  .applybs .col-lg-push-0 {
    left: auto;
  }
  .applybs .col-lg-offset-12 {
    margin-left: 100%;
  }
  .applybs .col-lg-offset-11 {
    margin-left: 91.66666667%;
  }
  .applybs .col-lg-offset-10 {
    margin-left: 83.33333333%;
  }
  .applybs .col-lg-offset-9 {
    margin-left: 75%;
  }
  .applybs .col-lg-offset-8 {
    margin-left: 66.66666667%;
  }
  .applybs .col-lg-offset-7 {
    margin-left: 58.33333333%;
  }
  .applybs .col-lg-offset-6 {
    margin-left: 50%;
  }
  .applybs .col-lg-offset-5 {
    margin-left: 41.66666667%;
  }
  .applybs .col-lg-offset-4 {
    margin-left: 33.33333333%;
  }
  .applybs .col-lg-offset-3 {
    margin-left: 25%;
  }
  .applybs .col-lg-offset-2 {
    margin-left: 16.66666667%;
  }
  .applybs .col-lg-offset-1 {
    margin-left: 8.33333333%;
  }
  .applybs .col-lg-offset-0 {
    margin-left: 0;
  }
}
.applybs table {
  background-color: transparent;
}
.applybs caption {
  padding-top: 8px;
  padding-bottom: 8px;
  color: #777;
  text-align: left;
}
.applybs th {
  text-align: left;
}
.applybs .table {
  width: 100%;
  max-width: 100%;
  margin-bottom: 20px;
}
.applybs .table > tbody > tr > td, .applybs .table > tbody > tr > th, .applybs .table > tfoot > tr > td, .applybs .table > tfoot > tr > th, .applybs .table > thead > tr > td, .applybs .table > thead > tr > th {
  padding: 8px;
  line-height: 1.42857143;
  vertical-align: top;
  border-top: 1px solid #ddd;
}
.applybs .table > thead > tr > th {
  vertical-align: bottom;
  border-bottom: 2px solid #ddd;
}
.applybs .table > caption + thead > tr:first-child > td, .applybs .table > caption + thead > tr:first-child > th, .applybs .table > colgroup + thead > tr:first-child > td, .applybs .table > colgroup + thead > tr:first-child > th, .applybs .table > thead:first-child > tr:first-child > td, .applybs .table > thead:first-child > tr:first-child > th {
  border-top: 0;
}
.applybs .table > tbody + tbody {
  border-top: 2px solid #ddd;
}
.applybs .table .table {
  background-color: #fff;
}
.applybs .table-condensed > tbody > tr > td, .applybs .table-condensed > tbody > tr > th, .applybs .table-condensed > tfoot > tr > td, .applybs .table-condensed > tfoot > tr > th, .applybs .table-condensed > thead > tr > td, .applybs .table-condensed > thead > tr > th {
  padding: 5px;
}
.applybs .table-bordered {
  border: 1px solid #ddd;
}
.applybs .table-bordered > tbody > tr > td, .applybs .table-bordered > tbody > tr > th, .applybs .table-bordered > tfoot > tr > td, .applybs .table-bordered > tfoot > tr > th, .applybs .table-bordered > thead > tr > td, .applybs .table-bordered > thead > tr > th {
  border: 1px solid #ddd;
}
.applybs .table-bordered > thead > tr > td, .applybs .table-bordered > thead > tr > th {
  border-bottom-width: 2px;
}
.applybs .table-striped > tbody > tr:nth-of-type(odd) {
  background-color: #f9f9f9;
}
.applybs .table-hover > tbody > tr:hover {
  background-color: #f5f5f5;
}
.applybs table col[class*=col-] {
  position: static;
  display: table-column;
  float: none;
}
.applybs table td[class*=col-], .applybs table th[class*=col-] {
  position: static;
  display: table-cell;
  float: none;
}
.applybs .table > tbody > tr.active > td, .applybs .table > tbody > tr.active > th, .applybs .table > tbody > tr > td.active, .applybs .table > tbody > tr > th.active, .applybs .table > tfoot > tr.active > td, .applybs .table > tfoot > tr.active > th, .applybs .table > tfoot > tr > td.active, .applybs .table > tfoot > tr > th.active, .applybs .table > thead > tr.active > td, .applybs .table > thead > tr.active > th, .applybs .table > thead > tr > td.active, .applybs .table > thead > tr > th.active {
  background-color: #f5f5f5;
}
.applybs .table-hover > tbody > tr.active:hover > td, .applybs .table-hover > tbody > tr.active:hover > th, .applybs .table-hover > tbody > tr:hover > .active, .applybs .table-hover > tbody > tr > td.active:hover, .applybs .table-hover > tbody > tr > th.active:hover {
  background-color: #e8e8e8;
}
.applybs .table > tbody > tr.success > td, .applybs .table > tbody > tr.success > th, .applybs .table > tbody > tr > td.success, .applybs .table > tbody > tr > th.success, .applybs .table > tfoot > tr.success > td, .applybs .table > tfoot > tr.success > th, .applybs .table > tfoot > tr > td.success, .applybs .table > tfoot > tr > th.success, .applybs .table > thead > tr.success > td, .applybs .table > thead > tr.success > th, .applybs .table > thead > tr > td.success, .applybs .table > thead > tr > th.success {
  background-color: #dff0d8;
}
.applybs .table-hover > tbody > tr.success:hover > td, .applybs .table-hover > tbody > tr.success:hover > th, .applybs .table-hover > tbody > tr:hover > .success, .applybs .table-hover > tbody > tr > td.success:hover, .applybs .table-hover > tbody > tr > th.success:hover {
  background-color: #d0e9c6;
}
.applybs .table > tbody > tr.info > td, .applybs .table > tbody > tr.info > th, .applybs .table > tbody > tr > td.info, .applybs .table > tbody > tr > th.info, .applybs .table > tfoot > tr.info > td, .applybs .table > tfoot > tr.info > th, .applybs .table > tfoot > tr > td.info, .applybs .table > tfoot > tr > th.info, .applybs .table > thead > tr.info > td, .applybs .table > thead > tr.info > th, .applybs .table > thead > tr > td.info, .applybs .table > thead > tr > th.info {
  background-color: #d9edf7;
}
.applybs .table-hover > tbody > tr.info:hover > td, .applybs .table-hover > tbody > tr.info:hover > th, .applybs .table-hover > tbody > tr:hover > .info, .applybs .table-hover > tbody > tr > td.info:hover, .applybs .table-hover > tbody > tr > th.info:hover {
  background-color: #c4e3f3;
}
.applybs .table > tbody > tr.warning > td, .applybs .table > tbody > tr.warning > th, .applybs .table > tbody > tr > td.warning, .applybs .table > tbody > tr > th.warning, .applybs .table > tfoot > tr.warning > td, .applybs .table > tfoot > tr.warning > th, .applybs .table > tfoot > tr > td.warning, .applybs .table > tfoot > tr > th.warning, .applybs .table > thead > tr.warning > td, .applybs .table > thead > tr.warning > th, .applybs .table > thead > tr > td.warning, .applybs .table > thead > tr > th.warning {
  background-color: #fcf8e3;
}
.applybs .table-hover > tbody > tr.warning:hover > td, .applybs .table-hover > tbody > tr.warning:hover > th, .applybs .table-hover > tbody > tr:hover > .warning, .applybs .table-hover > tbody > tr > td.warning:hover, .applybs .table-hover > tbody > tr > th.warning:hover {
  background-color: #faf2cc;
}
.applybs .table > tbody > tr.danger > td, .applybs .table > tbody > tr.danger > th, .applybs .table > tbody > tr > td.danger, .applybs .table > tbody > tr > th.danger, .applybs .table > tfoot > tr.danger > td, .applybs .table > tfoot > tr.danger > th, .applybs .table > tfoot > tr > td.danger, .applybs .table > tfoot > tr > th.danger, .applybs .table > thead > tr.danger > td, .applybs .table > thead > tr.danger > th, .applybs .table > thead > tr > td.danger, .applybs .table > thead > tr > th.danger {
  background-color: #f2dede;
}
.applybs .table-hover > tbody > tr.danger:hover > td, .applybs .table-hover > tbody > tr.danger:hover > th, .applybs .table-hover > tbody > tr:hover > .danger, .applybs .table-hover > tbody > tr > td.danger:hover, .applybs .table-hover > tbody > tr > th.danger:hover {
  background-color: #ebcccc;
}
.applybs .table-responsive {
  min-height: .01%;
  overflow-x: auto;
}
@media screen and (max-width: 767px) {
  .applybs .table-responsive {
    width: 100%;
    margin-bottom: 15px;
    overflow-y: hidden;
    -ms-overflow-style: -ms-autohiding-scrollbar;
    border: 1px solid #ddd;
  }
  .applybs .table-responsive > .table {
    margin-bottom: 0;
  }
  .applybs .table-responsive > .table > tbody > tr > td, .applybs .table-responsive > .table > tbody > tr > th, .applybs .table-responsive > .table > tfoot > tr > td, .applybs .table-responsive > .table > tfoot > tr > th, .applybs .table-responsive > .table > thead > tr > td, .applybs .table-responsive > .table > thead > tr > th {
    white-space: nowrap;
  }
  .applybs .table-responsive > .table-bordered {
    border: 0;
  }
  .applybs .table-responsive > .table-bordered > tbody > tr > td:first-child, .applybs .table-responsive > .table-bordered > tbody > tr > th:first-child, .applybs .table-responsive > .table-bordered > tfoot > tr > td:first-child, .applybs .table-responsive > .table-bordered > tfoot > tr > th:first-child, .applybs .table-responsive > .table-bordered > thead > tr > td:first-child, .applybs .table-responsive > .table-bordered > thead > tr > th:first-child {
    border-left: 0;
  }
  .applybs .table-responsive > .table-bordered > tbody > tr > td:last-child, .applybs .table-responsive > .table-bordered > tbody > tr > th:last-child, .applybs .table-responsive > .table-bordered > tfoot > tr > td:last-child, .applybs .table-responsive > .table-bordered > tfoot > tr > th:last-child, .applybs .table-responsive > .table-bordered > thead > tr > td:last-child, .applybs .table-responsive > .table-bordered > thead > tr > th:last-child {
    border-right: 0;
  }
  .applybs .table-responsive > .table-bordered > tbody > tr:last-child > td, .applybs .table-responsive > .table-bordered > tbody > tr:last-child > th, .applybs .table-responsive > .table-bordered > tfoot > tr:last-child > td, .applybs .table-responsive > .table-bordered > tfoot > tr:last-child > th {
    border-bottom: 0;
  }
}
.applybs fieldset {
  min-width: 0;
  padding: 0;
  margin: 0;
  border: 0;
}
.applybs legend {
  display: block;
  width: 100%;
  padding: 0;
  margin-bottom: 20px;
  font-size: 21px;
  line-height: inherit;
  color: #333;
  border: 0;
  border-bottom: 1px solid #e5e5e5;
}
.applybs label {
  display: inline-block;
  max-width: 100%;
  margin-bottom: 5px;
  font-weight: 700;
}
.applybs input[type=search] {
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
}
.applybs input[type=checkbox], .applybs input[type=radio] {
  margin: 4px 0 0;
  margin-top: 1px\9;
  line-height: normal;
}
.applybs input[type=file] {
  display: block;
}
.applybs input[type=range] {
  display: block;
  width: 100%;
}
.applybs select[multiple], .applybs select[size] {
  height: auto;
}
.applybs input[type=file]:focus, .applybs input[type=checkbox]:focus, .applybs input[type=radio]:focus {
  outline: 5px auto -webkit-focus-ring-color;
  outline-offset: -2px;
}
.applybs output {
  display: block;
  padding-top: 7px;
  font-size: 14px;
  line-height: 1.42857143;
  color: #555;
}
.applybs .form-control {
  display: block;
  width: 100%;
  height: 34px;
  padding: 6px 12px;
  font-size: 14px;
  line-height: 1.42857143;
  color: #555;
  background-color: #fff;
  background-image: none;
  border: 1px solid #ccc;
  border-radius: 4px;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
  box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
  -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
  -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
  transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}
.applybs .form-control:focus {
  border-color: #66afe9;
  outline: 0;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(102, 175, 233, 0.6);
  box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(102, 175, 233, 0.6);
}
.applybs .form-control::-moz-placeholder {
  color: #999;
  opacity: 1;
}
.applybs .form-control:-ms-input-placeholder {
  color: #999;
}
.applybs .form-control::-webkit-input-placeholder {
  color: #999;
}
.applybs .form-control::-ms-expand {
  background-color: transparent;
  border: 0;
}
.applybs .form-control[disabled], .applybs .form-control[readonly], .applybs fieldset[disabled] .form-control {
  background-color: #eee;
  opacity: 1;
}
.applybs .form-control[disabled], .applybs fieldset[disabled] .form-control {
  cursor: not-allowed;
}
.applybs textarea.form-control {
  height: auto;
}
.applybs input[type=search] {
  -webkit-appearance: none;
}
@media screen and (-webkit-min-device-pixel-ratio: 0) {
  .applybs input[type=date].form-control, .applybs input[type=time].form-control, .applybs input[type=datetime-local].form-control, .applybs input[type=month].form-control {
    line-height: 34px;
  }
  .applybs .input-group-sm input[type=date], .applybs .input-group-sm input[type=time], .applybs .input-group-sm input[type=datetime-local], .applybs .input-group-sm input[type=month], .applybs input[type=date].input-sm, .applybs input[type=time].input-sm, .applybs input[type=datetime-local].input-sm, .applybs input[type=month].input-sm {
    line-height: 30px;
  }
  .applybs .input-group-lg input[type=date], .applybs .input-group-lg input[type=time], .applybs .input-group-lg input[type=datetime-local], .applybs .input-group-lg input[type=month], .applybs input[type=date].input-lg, .applybs input[type=time].input-lg, .applybs input[type=datetime-local].input-lg, .applybs input[type=month].input-lg {
    line-height: 46px;
  }
}
.applybs .form-group {
  margin-bottom: 15px;
}
.applybs .checkbox, .applybs .radio {
  position: relative;
  display: block;
  margin-top: 10px;
  margin-bottom: 10px;
}
.applybs .checkbox label, .applybs .radio label {
  min-height: 20px;
  padding-left: 20px;
  margin-bottom: 0;
  font-weight: 400;
  cursor: pointer;
}
.applybs .checkbox input[type=checkbox], .applybs .checkbox-inline input[type=checkbox], .applybs .radio input[type=radio], .applybs .radio-inline input[type=radio] {
  position: absolute;
  margin-top: 4px\9;
  margin-left: -20px;
}
.applybs .checkbox + .checkbox, .applybs .radio + .radio {
  margin-top: -5px;
}
.applybs .checkbox-inline, .applybs .radio-inline {
  position: relative;
  display: inline-block;
  padding-left: 20px;
  margin-bottom: 0;
  font-weight: 400;
  vertical-align: middle;
  cursor: pointer;
}
.applybs .checkbox-inline + .checkbox-inline, .applybs .radio-inline + .radio-inline {
  margin-top: 0;
  margin-left: 10px;
}
.applybs fieldset[disabled] input[type=checkbox], .applybs fieldset[disabled] input[type=radio], .applybs input[type=checkbox].disabled, .applybs input[type=checkbox][disabled], .applybs input[type=radio].disabled, .applybs input[type=radio][disabled] {
  cursor: not-allowed;
}
.applybs .checkbox-inline.disabled, .applybs .radio-inline.disabled, .applybs fieldset[disabled] .checkbox-inline, .applybs fieldset[disabled] .radio-inline {
  cursor: not-allowed;
}
.applybs .checkbox.disabled label, .applybs .radio.disabled label, .applybs fieldset[disabled] .checkbox label, .applybs fieldset[disabled] .radio label {
  cursor: not-allowed;
}
.applybs .form-control-static {
  min-height: 34px;
  padding-top: 7px;
  padding-bottom: 7px;
  margin-bottom: 0;
}
.applybs .form-control-static.input-lg, .applybs .form-control-static.input-sm {
  padding-right: 0;
  padding-left: 0;
}
.applybs .input-sm {
  height: 30px;
  padding: 5px 10px;
  font-size: 12px;
  line-height: 1.5;
  border-radius: 3px;
}
.applybs select.input-sm {
  height: 30px;
  line-height: 30px;
}
.applybs select[multiple].input-sm, .applybs textarea.input-sm {
  height: auto;
}
.applybs .form-group-sm .form-control {
  height: 30px;
  padding: 5px 10px;
  font-size: 12px;
  line-height: 1.5;
  border-radius: 3px;
}
.applybs .form-group-sm select.form-control {
  height: 30px;
  line-height: 30px;
}
.applybs .form-group-sm select[multiple].form-control, .applybs .form-group-sm textarea.form-control {
  height: auto;
}
.applybs .form-group-sm .form-control-static {
  height: 30px;
  min-height: 32px;
  padding: 6px 10px;
  font-size: 12px;
  line-height: 1.5;
}
.applybs .input-lg {
  height: 46px;
  padding: 10px 16px;
  font-size: 18px;
  line-height: 1.3333333;
  border-radius: 6px;
}
.applybs select.input-lg {
  height: 46px;
  line-height: 46px;
}
.applybs select[multiple].input-lg, .applybs textarea.input-lg {
  height: auto;
}
.applybs .form-group-lg .form-control {
  height: 46px;
  padding: 10px 16px;
  font-size: 18px;
  line-height: 1.3333333;
  border-radius: 6px;
}
.applybs .form-group-lg select.form-control {
  height: 46px;
  line-height: 46px;
}
.applybs .form-group-lg select[multiple].form-control, .applybs .form-group-lg textarea.form-control {
  height: auto;
}
.applybs .form-group-lg .form-control-static {
  height: 46px;
  min-height: 38px;
  padding: 11px 16px;
  font-size: 18px;
  line-height: 1.3333333;
}
.applybs .has-feedback {
  position: relative;
}
.applybs .has-feedback .form-control {
  padding-right: 42.5px;
}
.applybs .form-control-feedback {
  position: absolute;
  top: 0;
  right: 0;
  z-index: 2;
  display: block;
  width: 34px;
  height: 34px;
  line-height: 34px;
  text-align: center;
  pointer-events: none;
}
.applybs .form-group-lg .form-control + .form-control-feedback, .applybs .input-group-lg + .form-control-feedback, .applybs .input-lg + .form-control-feedback {
  width: 46px;
  height: 46px;
  line-height: 46px;
}
.applybs .form-group-sm .form-control + .form-control-feedback, .applybs .input-group-sm + .form-control-feedback, .applybs .input-sm + .form-control-feedback {
  width: 30px;
  height: 30px;
  line-height: 30px;
}
.applybs .has-success .checkbox, .applybs .has-success .checkbox-inline, .applybs .has-success .control-label, .applybs .has-success .help-block, .applybs .has-success .radio, .applybs .has-success .radio-inline, .applybs .has-success.checkbox label, .applybs .has-success.checkbox-inline label, .applybs .has-success.radio label, .applybs .has-success.radio-inline label {
  color: #3c763d;
}
.applybs .has-success .form-control {
  border-color: #3c763d;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
  box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
}
.applybs .has-success .form-control:focus {
  border-color: #2b542c;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 6px #67b168;
  box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 6px #67b168;
}
.applybs .has-success .input-group-addon {
  color: #3c763d;
  background-color: #dff0d8;
  border-color: #3c763d;
}
.applybs .has-success .form-control-feedback {
  color: #3c763d;
}
.applybs .has-warning .checkbox, .applybs .has-warning .checkbox-inline, .applybs .has-warning .control-label, .applybs .has-warning .help-block, .applybs .has-warning .radio, .applybs .has-warning .radio-inline, .applybs .has-warning.checkbox label, .applybs .has-warning.checkbox-inline label, .applybs .has-warning.radio label, .applybs .has-warning.radio-inline label {
  color: #8a6d3b;
}
.applybs .has-warning .form-control {
  border-color: #8a6d3b;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
  box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
}
.applybs .has-warning .form-control:focus {
  border-color: #66512c;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 6px #c0a16b;
  box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 6px #c0a16b;
}
.applybs .has-warning .input-group-addon {
  color: #8a6d3b;
  background-color: #fcf8e3;
  border-color: #8a6d3b;
}
.applybs .has-warning .form-control-feedback {
  color: #8a6d3b;
}
.applybs .has-error .checkbox, .applybs .has-error .checkbox-inline, .applybs .has-error .control-label, .applybs .has-error .help-block, .applybs .has-error .radio, .applybs .has-error .radio-inline, .applybs .has-error.checkbox label, .applybs .has-error.checkbox-inline label, .applybs .has-error.radio label, .applybs .has-error.radio-inline label {
  color: #a94442;
}
.applybs .has-error .form-control {
  border-color: #a94442;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
  box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
}
.applybs .has-error .form-control:focus {
  border-color: #843534;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 6px #ce8483;
  box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 6px #ce8483;
}
.applybs .has-error .input-group-addon {
  color: #a94442;
  background-color: #f2dede;
  border-color: #a94442;
}
.applybs .has-error .form-control-feedback {
  color: #a94442;
}
.applybs .has-feedback label ~ .form-control-feedback {
  top: 25px;
}
.applybs .has-feedback label.sr-only ~ .form-control-feedback {
  top: 0;
}
.applybs .help-block {
  display: block;
  margin-top: 5px;
  margin-bottom: 10px;
  color: #737373;
}
@media (min-width: 768px) {
  .applybs .form-inline .form-group {
    display: inline-block;
    margin-bottom: 0;
    vertical-align: middle;
  }
  .applybs .form-inline .form-control {
    display: inline-block;
    width: auto;
    vertical-align: middle;
  }
  .applybs .form-inline .form-control-static {
    display: inline-block;
  }
  .applybs .form-inline .input-group {
    display: inline-table;
    vertical-align: middle;
  }
  .applybs .form-inline .input-group .form-control, .applybs .form-inline .input-group .input-group-addon, .applybs .form-inline .input-group .input-group-btn {
    width: auto;
  }
  .applybs .form-inline .input-group > .form-control {
    width: 100%;
  }
  .applybs .form-inline .control-label {
    margin-bottom: 0;
    vertical-align: middle;
  }
  .applybs .form-inline .checkbox, .applybs .form-inline .radio {
    display: inline-block;
    margin-top: 0;
    margin-bottom: 0;
    vertical-align: middle;
  }
  .applybs .form-inline .checkbox label, .applybs .form-inline .radio label {
    padding-left: 0;
  }
  .applybs .form-inline .checkbox input[type=checkbox], .applybs .form-inline .radio input[type=radio] {
    position: relative;
    margin-left: 0;
  }
  .applybs .form-inline .has-feedback .form-control-feedback {
    top: 0;
  }
}
.applybs .form-horizontal .checkbox, .applybs .form-horizontal .checkbox-inline, .applybs .form-horizontal .radio, .applybs .form-horizontal .radio-inline {
  padding-top: 7px;
  margin-top: 0;
  margin-bottom: 0;
}
.applybs .form-horizontal .checkbox, .applybs .form-horizontal .radio {
  min-height: 27px;
}
.applybs .form-horizontal .form-group {
  margin-right: -15px;
  margin-left: -15px;
}
@media (min-width: 768px) {
  .applybs .form-horizontal .control-label {
    padding-top: 7px;
    margin-bottom: 0;
    text-align: right;
  }
}
.applybs .form-horizontal .has-feedback .form-control-feedback {
  right: 15px;
}
@media (min-width: 768px) {
  .applybs .form-horizontal .form-group-lg .control-label {
    padding-top: 11px;
    font-size: 18px;
  }
}
@media (min-width: 768px) {
  .applybs .form-horizontal .form-group-sm .control-label {
    padding-top: 6px;
    font-size: 12px;
  }
}
.applybs .btn {
  display: inline-block;
  padding: 6px 12px;
  margin-bottom: 0;
  font-size: 14px;
  font-weight: 400;
  line-height: 1.42857143;
  text-align: center;
  white-space: nowrap;
  vertical-align: middle;
  -ms-touch-action: manipulation;
  touch-action: manipulation;
  cursor: pointer;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  background-image: none;
  border: 1px solid transparent;
  border-radius: 4px;
}
.applybs .btn.active.focus, .applybs .btn.active:focus, .applybs .btn.focus, .applybs .btn:active.focus, .applybs .btn:active:focus, .applybs .btn:focus {
  outline: 5px auto -webkit-focus-ring-color;
  outline-offset: -2px;
}
.applybs .btn.focus, .applybs .btn:focus, .applybs .btn:hover {
  color: #333;
  text-decoration: none;
}
.applybs .btn.active, .applybs .btn:active {
  background-image: none;
  outline: 0;
  -webkit-box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
  box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
}
.applybs .btn.disabled, .applybs .btn[disabled], .applybs fieldset[disabled] .btn {
  cursor: not-allowed;
  filter: alpha(opacity=65);
  -webkit-box-shadow: none;
  box-shadow: none;
  opacity: .65;
}
.applybs a.btn.disabled, .applybs fieldset[disabled] a.btn {
  pointer-events: none;
}
.applybs .btn-default {
  color: #333;
  background-color: #fff;
  border-color: #ccc;
}
.applybs .btn-default.focus, .applybs .btn-default:focus {
  color: #333;
  background-color: #e6e6e6;
  border-color: #8c8c8c;
}
.applybs .btn-default:hover {
  color: #333;
  background-color: #e6e6e6;
  border-color: #adadad;
}
.applybs .btn-default.active, .applybs .btn-default:active, .applybs .open > .dropdown-toggle.btn-default {
  color: #333;
  background-color: #e6e6e6;
  border-color: #adadad;
}
.applybs .btn-default.active.focus, .applybs .btn-default.active:focus, .applybs .btn-default.active:hover, .applybs .btn-default:active.focus, .applybs .btn-default:active:focus, .applybs .btn-default:active:hover, .applybs .open > .dropdown-toggle.btn-default.focus, .applybs .open > .dropdown-toggle.btn-default:focus, .applybs .open > .dropdown-toggle.btn-default:hover {
  color: #333;
  background-color: #d4d4d4;
  border-color: #8c8c8c;
}
.applybs .btn-default.active, .applybs .btn-default:active, .applybs .open > .dropdown-toggle.btn-default {
  background-image: none;
}
.applybs .btn-default.disabled.focus, .applybs .btn-default.disabled:focus, .applybs .btn-default.disabled:hover, .applybs .btn-default[disabled].focus, .applybs .btn-default[disabled]:focus, .applybs .btn-default[disabled]:hover, .applybs fieldset[disabled] .btn-default.focus, .applybs fieldset[disabled] .btn-default:focus, .applybs fieldset[disabled] .btn-default:hover {
  background-color: #fff;
  border-color: #ccc;
}
.applybs .btn-default .badge {
  color: #fff;
  background-color: #333;
}
.applybs .btn-primary {
  color: #fff;
  background-color: #337ab7;
  border-color: #2e6da4;
}
.applybs .btn-primary.focus, .applybs .btn-primary:focus {
  color: #fff;
  background-color: #286090;
  border-color: #122b40;
}
.applybs .btn-primary:hover {
  color: #fff;
  background-color: #286090;
  border-color: #204d74;
}
.applybs .btn-primary.active, .applybs .btn-primary:active, .applybs .open > .dropdown-toggle.btn-primary {
  color: #fff;
  background-color: #286090;
  border-color: #204d74;
}
.applybs .btn-primary.active.focus, .applybs .btn-primary.active:focus, .applybs .btn-primary.active:hover, .applybs .btn-primary:active.focus, .applybs .btn-primary:active:focus, .applybs .btn-primary:active:hover, .applybs .open > .dropdown-toggle.btn-primary.focus, .applybs .open > .dropdown-toggle.btn-primary:focus, .applybs .open > .dropdown-toggle.btn-primary:hover {
  color: #fff;
  background-color: #204d74;
  border-color: #122b40;
}
.applybs .btn-primary.active, .applybs .btn-primary:active, .applybs .open > .dropdown-toggle.btn-primary {
  background-image: none;
}
.applybs .btn-primary.disabled.focus, .applybs .btn-primary.disabled:focus, .applybs .btn-primary.disabled:hover, .applybs .btn-primary[disabled].focus, .applybs .btn-primary[disabled]:focus, .applybs .btn-primary[disabled]:hover, .applybs fieldset[disabled] .btn-primary.focus, .applybs fieldset[disabled] .btn-primary:focus, .applybs fieldset[disabled] .btn-primary:hover {
  background-color: #337ab7;
  border-color: #2e6da4;
}
.applybs .btn-primary .badge {
  color: #337ab7;
  background-color: #fff;
}
.applybs .btn-success {
  color: #fff;
  background-color: #5cb85c;
  border-color: #4cae4c;
}
.applybs .btn-success.focus, .applybs .btn-success:focus {
  color: #fff;
  background-color: #449d44;
  border-color: #255625;
}
.applybs .btn-success:hover {
  color: #fff;
  background-color: #449d44;
  border-color: #398439;
}
.applybs .btn-success.active, .applybs .btn-success:active, .applybs .open > .dropdown-toggle.btn-success {
  color: #fff;
  background-color: #449d44;
  border-color: #398439;
}
.applybs .btn-success.active.focus, .applybs .btn-success.active:focus, .applybs .btn-success.active:hover, .applybs .btn-success:active.focus, .applybs .btn-success:active:focus, .applybs .btn-success:active:hover, .applybs .open > .dropdown-toggle.btn-success.focus, .applybs .open > .dropdown-toggle.btn-success:focus, .applybs .open > .dropdown-toggle.btn-success:hover {
  color: #fff;
  background-color: #398439;
  border-color: #255625;
}
.applybs .btn-success.active, .applybs .btn-success:active, .applybs .open > .dropdown-toggle.btn-success {
  background-image: none;
}
.applybs .btn-success.disabled.focus, .applybs .btn-success.disabled:focus, .applybs .btn-success.disabled:hover, .applybs .btn-success[disabled].focus, .applybs .btn-success[disabled]:focus, .applybs .btn-success[disabled]:hover, .applybs fieldset[disabled] .btn-success.focus, .applybs fieldset[disabled] .btn-success:focus, .applybs fieldset[disabled] .btn-success:hover {
  background-color: #5cb85c;
  border-color: #4cae4c;
}
.applybs .btn-success .badge {
  color: #5cb85c;
  background-color: #fff;
}
.applybs .btn-info {
  color: #fff;
  background-color: #5bc0de;
  border-color: #46b8da;
}
.applybs .btn-info.focus, .applybs .btn-info:focus {
  color: #fff;
  background-color: #31b0d5;
  border-color: #1b6d85;
}
.applybs .btn-info:hover {
  color: #fff;
  background-color: #31b0d5;
  border-color: #269abc;
}
.applybs .btn-info.active, .applybs .btn-info:active, .applybs .open > .dropdown-toggle.btn-info {
  color: #fff;
  background-color: #31b0d5;
  border-color: #269abc;
}
.applybs .btn-info.active.focus, .applybs .btn-info.active:focus, .applybs .btn-info.active:hover, .applybs .btn-info:active.focus, .applybs .btn-info:active:focus, .applybs .btn-info:active:hover, .applybs .open > .dropdown-toggle.btn-info.focus, .applybs .open > .dropdown-toggle.btn-info:focus, .applybs .open > .dropdown-toggle.btn-info:hover {
  color: #fff;
  background-color: #269abc;
  border-color: #1b6d85;
}
.applybs .btn-info.active, .applybs .btn-info:active, .applybs .open > .dropdown-toggle.btn-info {
  background-image: none;
}
.applybs .btn-info.disabled.focus, .applybs .btn-info.disabled:focus, .applybs .btn-info.disabled:hover, .applybs .btn-info[disabled].focus, .applybs .btn-info[disabled]:focus, .applybs .btn-info[disabled]:hover, .applybs fieldset[disabled] .btn-info.focus, .applybs fieldset[disabled] .btn-info:focus, .applybs fieldset[disabled] .btn-info:hover {
  background-color: #5bc0de;
  border-color: #46b8da;
}
.applybs .btn-info .badge {
  color: #5bc0de;
  background-color: #fff;
}
.applybs .btn-warning {
  color: #fff;
  background-color: #f0ad4e;
  border-color: #eea236;
}
.applybs .btn-warning.focus, .applybs .btn-warning:focus {
  color: #fff;
  background-color: #ec971f;
  border-color: #985f0d;
}
.applybs .btn-warning:hover {
  color: #fff;
  background-color: #ec971f;
  border-color: #d58512;
}
.applybs .btn-warning.active, .applybs .btn-warning:active, .applybs .open > .dropdown-toggle.btn-warning {
  color: #fff;
  background-color: #ec971f;
  border-color: #d58512;
}
.applybs .btn-warning.active.focus, .applybs .btn-warning.active:focus, .applybs .btn-warning.active:hover, .applybs .btn-warning:active.focus, .applybs .btn-warning:active:focus, .applybs .btn-warning:active:hover, .applybs .open > .dropdown-toggle.btn-warning.focus, .applybs .open > .dropdown-toggle.btn-warning:focus, .applybs .open > .dropdown-toggle.btn-warning:hover {
  color: #fff;
  background-color: #d58512;
  border-color: #985f0d;
}
.applybs .btn-warning.active, .applybs .btn-warning:active, .applybs .open > .dropdown-toggle.btn-warning {
  background-image: none;
}
.applybs .btn-warning.disabled.focus, .applybs .btn-warning.disabled:focus, .applybs .btn-warning.disabled:hover, .applybs .btn-warning[disabled].focus, .applybs .btn-warning[disabled]:focus, .applybs .btn-warning[disabled]:hover, .applybs fieldset[disabled] .btn-warning.focus, .applybs fieldset[disabled] .btn-warning:focus, .applybs fieldset[disabled] .btn-warning:hover {
  background-color: #f0ad4e;
  border-color: #eea236;
}
.applybs .btn-warning .badge {
  color: #f0ad4e;
  background-color: #fff;
}
.applybs .btn-danger {
  color: #fff;
  background-color: #d9534f;
  border-color: #d43f3a;
}
.applybs .btn-danger.focus, .applybs .btn-danger:focus {
  color: #fff;
  background-color: #c9302c;
  border-color: #761c19;
}
.applybs .btn-danger:hover {
  color: #fff;
  background-color: #c9302c;
  border-color: #ac2925;
}
.applybs .btn-danger.active, .applybs .btn-danger:active, .applybs .open > .dropdown-toggle.btn-danger {
  color: #fff;
  background-color: #c9302c;
  border-color: #ac2925;
}
.applybs .btn-danger.active.focus, .applybs .btn-danger.active:focus, .applybs .btn-danger.active:hover, .applybs .btn-danger:active.focus, .applybs .btn-danger:active:focus, .applybs .btn-danger:active:hover, .applybs .open > .dropdown-toggle.btn-danger.focus, .applybs .open > .dropdown-toggle.btn-danger:focus, .applybs .open > .dropdown-toggle.btn-danger:hover {
  color: #fff;
  background-color: #ac2925;
  border-color: #761c19;
}
.applybs .btn-danger.active, .applybs .btn-danger:active, .applybs .open > .dropdown-toggle.btn-danger {
  background-image: none;
}
.applybs .btn-danger.disabled.focus, .applybs .btn-danger.disabled:focus, .applybs .btn-danger.disabled:hover, .applybs .btn-danger[disabled].focus, .applybs .btn-danger[disabled]:focus, .applybs .btn-danger[disabled]:hover, .applybs fieldset[disabled] .btn-danger.focus, .applybs fieldset[disabled] .btn-danger:focus, .applybs fieldset[disabled] .btn-danger:hover {
  background-color: #d9534f;
  border-color: #d43f3a;
}
.applybs .btn-danger .badge {
  color: #d9534f;
  background-color: #fff;
}
.applybs .btn-link {
  font-weight: 400;
  color: #337ab7;
  border-radius: 0;
}
.applybs .btn-link, .applybs .btn-link.active, .applybs .btn-link:active, .applybs .btn-link[disabled], .applybs fieldset[disabled] .btn-link {
  background-color: transparent;
  -webkit-box-shadow: none;
  box-shadow: none;
}
.applybs .btn-link, .applybs .btn-link:active, .applybs .btn-link:focus, .applybs .btn-link:hover {
  border-color: transparent;
}
.applybs .btn-link:focus, .applybs .btn-link:hover {
  color: #23527c;
  text-decoration: underline;
  background-color: transparent;
}
.applybs .btn-link[disabled]:focus, .applybs .btn-link[disabled]:hover, .applybs fieldset[disabled] .btn-link:focus, .applybs fieldset[disabled] .btn-link:hover {
  color: #777;
  text-decoration: none;
}
.applybs .btn-group-lg > .btn, .applybs .btn-lg {
  padding: 10px 16px;
  font-size: 18px;
  line-height: 1.3333333;
  border-radius: 6px;
}
.applybs .btn-group-sm > .btn, .applybs .btn-sm {
  padding: 5px 10px;
  font-size: 12px;
  line-height: 1.5;
  border-radius: 3px;
}
.applybs .btn-group-xs > .btn, .applybs .btn-xs {
  padding: 1px 5px;
  font-size: 12px;
  line-height: 1.5;
  border-radius: 3px;
}
.applybs .btn-block {
  display: block;
  width: 100%;
}
.applybs .btn-block + .btn-block {
  margin-top: 5px;
}
.applybs input[type=button].btn-block, .applybs input[type=reset].btn-block, .applybs input[type=submit].btn-block {
  width: 100%;
}
.applybs .fade {
  opacity: 0;
  -webkit-transition: opacity .15s linear;
  -o-transition: opacity .15s linear;
  transition: opacity .15s linear;
}
.applybs .fade.in {
  opacity: 1;
}
.applybs .collapse {
  display: none;
}
.applybs .collapse.in {
  display: block;
}
.applybs tr.collapse.in {
  display: table-row;
}
.applybs tbody.collapse.in {
  display: table-row-group;
}
.applybs .collapsing {
  position: relative;
  height: 0;
  overflow: hidden;
  -webkit-transition-timing-function: ease;
  -o-transition-timing-function: ease;
  transition-timing-function: ease;
  -webkit-transition-duration: .35s;
  -o-transition-duration: .35s;
  transition-duration: .35s;
  -webkit-transition-property: height,visibility;
  -o-transition-property: height,visibility;
  transition-property: height,visibility;
}
.applybs .caret {
  display: inline-block;
  width: 0;
  height: 0;
  margin-left: 2px;
  vertical-align: middle;
  border-top: 4px dashed;
  border-top: 4px solid\9;
  border-right: 4px solid transparent;
  border-left: 4px solid transparent;
}
.applybs .dropdown, .applybs .dropup {
  position: relative;
}
.applybs .dropdown-toggle:focus {
  outline: 0;
}
.applybs .dropdown-menu {
  position: absolute;
  top: 100%;
  left: 0;
  z-index: 1000;
  display: none;
  float: left;
  min-width: 160px;
  padding: 5px 0;
  margin: 2px 0 0;
  font-size: 14px;
  text-align: left;
  list-style: none;
  background-color: #fff;
  -webkit-background-clip: padding-box;
  background-clip: padding-box;
  border: 1px solid #ccc;
  border: 1px solid rgba(0, 0, 0, 0.15);
  border-radius: 4px;
  -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
}
.applybs .dropdown-menu.pull-right {
  right: 0;
  left: auto;
}
.applybs .dropdown-menu .divider {
  height: 1px;
  margin: 9px 0;
  overflow: hidden;
  background-color: #e5e5e5;
}
.applybs .dropdown-menu > li > a {
  display: block;
  padding: 3px 20px;
  clear: both;
  font-weight: 400;
  line-height: 1.42857143;
  color: #333;
  white-space: nowrap;
}
.applybs .dropdown-menu > li > a:focus, .applybs .dropdown-menu > li > a:hover {
  color: #262626;
  text-decoration: none;
  background-color: #f5f5f5;
}
.applybs .dropdown-menu > .active > a, .applybs .dropdown-menu > .active > a:focus, .applybs .dropdown-menu > .active > a:hover {
  color: #fff;
  text-decoration: none;
  background-color: #337ab7;
  outline: 0;
}
.applybs .dropdown-menu > .disabled > a, .applybs .dropdown-menu > .disabled > a:focus, .applybs .dropdown-menu > .disabled > a:hover {
  color: #777;
}
.applybs .dropdown-menu > .disabled > a:focus, .applybs .dropdown-menu > .disabled > a:hover {
  text-decoration: none;
  cursor: not-allowed;
  background-color: transparent;
  background-image: none;
  filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
}
.applybs .open > .dropdown-menu {
  display: block;
}
.applybs .open > a {
  outline: 0;
}
.applybs .dropdown-menu-right {
  right: 0;
  left: auto;
}
.applybs .dropdown-menu-left {
  right: auto;
  left: 0;
}
.applybs .dropdown-header {
  display: block;
  padding: 3px 20px;
  font-size: 12px;
  line-height: 1.42857143;
  color: #777;
  white-space: nowrap;
}
.applybs .dropdown-backdrop {
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  z-index: 990;
}
.applybs .pull-right > .dropdown-menu {
  right: 0;
  left: auto;
}
.applybs .dropup .caret, .applybs .navbar-fixed-bottom .dropdown .caret {
  content: "";
  border-top: 0;
  border-bottom: 4px dashed;
  border-bottom: 4px solid\9;
}
.applybs .dropup .dropdown-menu, .applybs .navbar-fixed-bottom .dropdown .dropdown-menu {
  top: auto;
  bottom: 100%;
  margin-bottom: 2px;
}
@media (min-width: 768px) {
  .applybs .navbar-right .dropdown-menu {
    right: 0;
    left: auto;
  }
  .applybs .navbar-right .dropdown-menu-left {
    right: auto;
    left: 0;
  }
}
.applybs .btn-group, .applybs .btn-group-vertical {
  position: relative;
  display: inline-block;
  vertical-align: middle;
}
.applybs .btn-group-vertical > .btn, .applybs .btn-group > .btn {
  position: relative;
  float: left;
}
.applybs .btn-group-vertical > .btn.active, .applybs .btn-group-vertical > .btn:active, .applybs .btn-group-vertical > .btn:focus, .applybs .btn-group-vertical > .btn:hover, .applybs .btn-group > .btn.active, .applybs .btn-group > .btn:active, .applybs .btn-group > .btn:focus, .applybs .btn-group > .btn:hover {
  z-index: 2;
}
.applybs .btn-group .btn + .btn, .applybs .btn-group .btn + .btn-group, .applybs .btn-group .btn-group + .btn, .applybs .btn-group .btn-group + .btn-group {
  margin-left: -1px;
}
.applybs .btn-toolbar {
  margin-left: -5px;
}
.applybs .btn-toolbar .btn, .applybs .btn-toolbar .btn-group, .applybs .btn-toolbar .input-group {
  float: left;
}
.applybs .btn-toolbar > .btn, .applybs .btn-toolbar > .btn-group, .applybs .btn-toolbar > .input-group {
  margin-left: 5px;
}
.applybs .btn-group > .btn:not(:first-child):not(:last-child):not(.dropdown-toggle) {
  border-radius: 0;
}
.applybs .btn-group > .btn:first-child {
  margin-left: 0;
}
.applybs .btn-group > .btn:first-child:not(:last-child):not(.dropdown-toggle) {
  border-top-right-radius: 0;
  border-bottom-right-radius: 0;
}
.applybs .btn-group > .btn:last-child:not(:first-child), .applybs .btn-group > .dropdown-toggle:not(:first-child) {
  border-top-left-radius: 0;
  border-bottom-left-radius: 0;
}
.applybs .btn-group > .btn-group {
  float: left;
}
.applybs .btn-group > .btn-group:not(:first-child):not(:last-child) > .btn {
  border-radius: 0;
}
.applybs .btn-group > .btn-group:first-child:not(:last-child) > .btn:last-child, .applybs .btn-group > .btn-group:first-child:not(:last-child) > .dropdown-toggle {
  border-top-right-radius: 0;
  border-bottom-right-radius: 0;
}
.applybs .btn-group > .btn-group:last-child:not(:first-child) > .btn:first-child {
  border-top-left-radius: 0;
  border-bottom-left-radius: 0;
}
.applybs .btn-group .dropdown-toggle:active, .applybs .btn-group.open .dropdown-toggle {
  outline: 0;
}
.applybs .btn-group > .btn + .dropdown-toggle {
  padding-right: 8px;
  padding-left: 8px;
}
.applybs .btn-group > .btn-lg + .dropdown-toggle {
  padding-right: 12px;
  padding-left: 12px;
}
.applybs .btn-group.open .dropdown-toggle {
  -webkit-box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
  box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
}
.applybs .btn-group.open .dropdown-toggle.btn-link {
  -webkit-box-shadow: none;
  box-shadow: none;
}
.applybs .btn .caret {
  margin-left: 0;
}
.applybs .btn-lg .caret {
  border-width: 5px 5px 0;
  border-bottom-width: 0;
}
.applybs .dropup .btn-lg .caret {
  border-width: 0 5px 5px;
}
.applybs .btn-group-vertical > .btn, .applybs .btn-group-vertical > .btn-group, .applybs .btn-group-vertical > .btn-group > .btn {
  display: block;
  float: none;
  width: 100%;
  max-width: 100%;
}
.applybs .btn-group-vertical > .btn-group > .btn {
  float: none;
}
.applybs .btn-group-vertical > .btn + .btn, .applybs .btn-group-vertical > .btn + .btn-group, .applybs .btn-group-vertical > .btn-group + .btn, .applybs .btn-group-vertical > .btn-group + .btn-group {
  margin-top: -1px;
  margin-left: 0;
}
.applybs .btn-group-vertical > .btn:not(:first-child):not(:last-child) {
  border-radius: 0;
}
.applybs .btn-group-vertical > .btn:first-child:not(:last-child) {
  border-top-left-radius: 4px;
  border-top-right-radius: 4px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}
.applybs .btn-group-vertical > .btn:last-child:not(:first-child) {
  border-top-left-radius: 0;
  border-top-right-radius: 0;
  border-bottom-right-radius: 4px;
  border-bottom-left-radius: 4px;
}
.applybs .btn-group-vertical > .btn-group:not(:first-child):not(:last-child) > .btn {
  border-radius: 0;
}
.applybs .btn-group-vertical > .btn-group:first-child:not(:last-child) > .btn:last-child, .applybs .btn-group-vertical > .btn-group:first-child:not(:last-child) > .dropdown-toggle {
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}
.applybs .btn-group-vertical > .btn-group:last-child:not(:first-child) > .btn:first-child {
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}
.applybs .btn-group-justified {
  display: table;
  width: 100%;
  table-layout: fixed;
  border-collapse: separate;
}
.applybs .btn-group-justified > .btn, .applybs .btn-group-justified > .btn-group {
  display: table-cell;
  float: none;
  width: 1%;
}
.applybs .btn-group-justified > .btn-group .btn {
  width: 100%;
}
.applybs .btn-group-justified > .btn-group .dropdown-menu {
  left: auto;
}
.applybs [data-toggle=buttons] > .btn input[type=checkbox], .applybs [data-toggle=buttons] > .btn input[type=radio], .applybs [data-toggle=buttons] > .btn-group > .btn input[type=checkbox], .applybs [data-toggle=buttons] > .btn-group > .btn input[type=radio] {
  position: absolute;
  clip: rect(0, 0, 0, 0);
  pointer-events: none;
}
.applybs .input-group {
  position: relative;
  display: table;
  border-collapse: separate;
}
.applybs .input-group[class*=col-] {
  float: none;
  padding-right: 0;
  padding-left: 0;
}
.applybs .input-group .form-control {
  position: relative;
  z-index: 2;
  float: left;
  width: 100%;
  margin-bottom: 0;
}
.applybs .input-group .form-control:focus {
  z-index: 3;
}
.applybs .input-group-lg > .form-control, .applybs .input-group-lg > .input-group-addon, .applybs .input-group-lg > .input-group-btn > .btn {
  height: 46px;
  padding: 10px 16px;
  font-size: 18px;
  line-height: 1.3333333;
  border-radius: 6px;
}
.applybs select.input-group-lg > .form-control, .applybs select.input-group-lg > .input-group-addon, .applybs select.input-group-lg > .input-group-btn > .btn {
  height: 46px;
  line-height: 46px;
}
.applybs select[multiple].input-group-lg > .form-control, .applybs select[multiple].input-group-lg > .input-group-addon, .applybs select[multiple].input-group-lg > .input-group-btn > .btn, .applybs textarea.input-group-lg > .form-control, .applybs textarea.input-group-lg > .input-group-addon, .applybs textarea.input-group-lg > .input-group-btn > .btn {
  height: auto;
}
.applybs .input-group-sm > .form-control, .applybs .input-group-sm > .input-group-addon, .applybs .input-group-sm > .input-group-btn > .btn {
  height: 30px;
  padding: 5px 10px;
  font-size: 12px;
  line-height: 1.5;
  border-radius: 3px;
}
.applybs select.input-group-sm > .form-control, .applybs select.input-group-sm > .input-group-addon, .applybs select.input-group-sm > .input-group-btn > .btn {
  height: 30px;
  line-height: 30px;
}
.applybs select[multiple].input-group-sm > .form-control, .applybs select[multiple].input-group-sm > .input-group-addon, .applybs select[multiple].input-group-sm > .input-group-btn > .btn, .applybs textarea.input-group-sm > .form-control, .applybs textarea.input-group-sm > .input-group-addon, .applybs textarea.input-group-sm > .input-group-btn > .btn {
  height: auto;
}
.applybs .input-group .form-control, .applybs .input-group-addon, .applybs .input-group-btn {
  display: table-cell;
}
.applybs .input-group .form-control:not(:first-child):not(:last-child), .applybs .input-group-addon:not(:first-child):not(:last-child), .applybs .input-group-btn:not(:first-child):not(:last-child) {
  border-radius: 0;
}
.applybs .input-group-addon, .applybs .input-group-btn {
  width: 1%;
  white-space: nowrap;
  vertical-align: middle;
}
.applybs .input-group-addon {
  padding: 6px 12px;
  font-size: 14px;
  font-weight: 400;
  line-height: 1;
  color: #555;
  text-align: center;
  background-color: #eee;
  border: 1px solid #ccc;
  border-radius: 4px;
}
.applybs .input-group-addon.input-sm {
  padding: 5px 10px;
  font-size: 12px;
  border-radius: 3px;
}
.applybs .input-group-addon.input-lg {
  padding: 10px 16px;
  font-size: 18px;
  border-radius: 6px;
}
.applybs .input-group-addon input[type=checkbox], .applybs .input-group-addon input[type=radio] {
  margin-top: 0;
}
.applybs .input-group .form-control:first-child, .applybs .input-group-addon:first-child, .applybs .input-group-btn:first-child > .btn, .applybs .input-group-btn:first-child > .btn-group > .btn, .applybs .input-group-btn:first-child > .dropdown-toggle, .applybs .input-group-btn:last-child > .btn-group:not(:last-child) > .btn, .applybs .input-group-btn:last-child > .btn:not(:last-child):not(.dropdown-toggle) {
  border-top-right-radius: 0;
  border-bottom-right-radius: 0;
}
.applybs .input-group-addon:first-child {
  border-right: 0;
}
.applybs .input-group .form-control:last-child, .applybs .input-group-addon:last-child, .applybs .input-group-btn:first-child > .btn-group:not(:first-child) > .btn, .applybs .input-group-btn:first-child > .btn:not(:first-child), .applybs .input-group-btn:last-child > .btn, .applybs .input-group-btn:last-child > .btn-group > .btn, .applybs .input-group-btn:last-child > .dropdown-toggle {
  border-top-left-radius: 0;
  border-bottom-left-radius: 0;
}
.applybs .input-group-addon:last-child {
  border-left: 0;
}
.applybs .input-group-btn {
  position: relative;
  font-size: 0;
  white-space: nowrap;
}
.applybs .input-group-btn > .btn {
  position: relative;
}
.applybs .input-group-btn > .btn + .btn {
  margin-left: -1px;
}
.applybs .input-group-btn > .btn:active, .applybs .input-group-btn > .btn:focus, .applybs .input-group-btn > .btn:hover {
  z-index: 2;
}
.applybs .input-group-btn:first-child > .btn, .applybs .input-group-btn:first-child > .btn-group {
  margin-right: -1px;
}
.applybs .input-group-btn:last-child > .btn, .applybs .input-group-btn:last-child > .btn-group {
  z-index: 2;
  margin-left: -1px;
}
.applybs .nav {
  padding-left: 0;
  margin-bottom: 0;
  list-style: none;
}
.applybs .nav > li {
  position: relative;
  display: block;
}
.applybs .nav > li > a {
  position: relative;
  display: block;
  padding: 10px 15px;
}
.applybs .nav > li > a:focus, .applybs .nav > li > a:hover {
  text-decoration: none;
  background-color: #eee;
}
.applybs .nav > li.disabled > a {
  color: #777;
}
.applybs .nav > li.disabled > a:focus, .applybs .nav > li.disabled > a:hover {
  color: #777;
  text-decoration: none;
  cursor: not-allowed;
  background-color: transparent;
}
.applybs .nav .open > a, .applybs .nav .open > a:focus, .applybs .nav .open > a:hover {
  background-color: #eee;
  border-color: #337ab7;
}
.applybs .nav .nav-divider {
  height: 1px;
  margin: 9px 0;
  overflow: hidden;
  background-color: #e5e5e5;
}
.applybs .nav > li > a > img {
  max-width: none;
}
.applybs .nav-tabs {
  border-bottom: 1px solid #ddd;
}
.applybs .nav-tabs > li {
  float: left;
  margin-bottom: -1px;
}
.applybs .nav-tabs > li > a {
  margin-right: 2px;
  line-height: 1.42857143;
  border: 1px solid transparent;
  border-radius: 4px 4px 0 0;
}
.applybs .nav-tabs > li > a:hover {
  border-color: #eee #eee #ddd;
}
.applybs .nav-tabs > li.active > a, .applybs .nav-tabs > li.active > a:focus, .applybs .nav-tabs > li.active > a:hover {
  color: #555;
  cursor: default;
  background-color: #fff;
  border: 1px solid #ddd;
  border-bottom-color: transparent;
}
.applybs .nav-tabs.nav-justified {
  width: 100%;
  border-bottom: 0;
}
.applybs .nav-tabs.nav-justified > li {
  float: none;
}
.applybs .nav-tabs.nav-justified > li > a {
  margin-bottom: 5px;
  text-align: center;
}
.applybs .nav-tabs.nav-justified > .dropdown .dropdown-menu {
  top: auto;
  left: auto;
}
@media (min-width: 768px) {
  .applybs .nav-tabs.nav-justified > li {
    display: table-cell;
    width: 1%;
  }
  .applybs .nav-tabs.nav-justified > li > a {
    margin-bottom: 0;
  }
}
.applybs .nav-tabs.nav-justified > li > a {
  margin-right: 0;
  border-radius: 4px;
}
.applybs .nav-tabs.nav-justified > .active > a, .applybs .nav-tabs.nav-justified > .active > a:focus, .applybs .nav-tabs.nav-justified > .active > a:hover {
  border: 1px solid #ddd;
}
@media (min-width: 768px) {
  .applybs .nav-tabs.nav-justified > li > a {
    border-bottom: 1px solid #ddd;
    border-radius: 4px 4px 0 0;
  }
  .applybs .nav-tabs.nav-justified > .active > a, .applybs .nav-tabs.nav-justified > .active > a:focus, .applybs .nav-tabs.nav-justified > .active > a:hover {
    border-bottom-color: #fff;
  }
}
.applybs .nav-pills > li {
  float: left;
}
.applybs .nav-pills > li > a {
  border-radius: 4px;
}
.applybs .nav-pills > li + li {
  margin-left: 2px;
}
.applybs .nav-pills > li.active > a, .applybs .nav-pills > li.active > a:focus, .applybs .nav-pills > li.active > a:hover {
  color: #fff;
  background-color: #337ab7;
}
.applybs .nav-stacked > li {
  float: none;
}
.applybs .nav-stacked > li + li {
  margin-top: 2px;
  margin-left: 0;
}
.applybs .nav-justified {
  width: 100%;
}
.applybs .nav-justified > li {
  float: none;
}
.applybs .nav-justified > li > a {
  margin-bottom: 5px;
  text-align: center;
}
.applybs .nav-justified > .dropdown .dropdown-menu {
  top: auto;
  left: auto;
}
@media (min-width: 768px) {
  .applybs .nav-justified > li {
    display: table-cell;
    width: 1%;
  }
  .applybs .nav-justified > li > a {
    margin-bottom: 0;
  }
}
.applybs .nav-tabs-justified {
  border-bottom: 0;
}
.applybs .nav-tabs-justified > li > a {
  margin-right: 0;
  border-radius: 4px;
}
.applybs .nav-tabs-justified > .active > a, .applybs .nav-tabs-justified > .active > a:focus, .applybs .nav-tabs-justified > .active > a:hover {
  border: 1px solid #ddd;
}
@media (min-width: 768px) {
  .applybs .nav-tabs-justified > li > a {
    border-bottom: 1px solid #ddd;
    border-radius: 4px 4px 0 0;
  }
  .applybs .nav-tabs-justified > .active > a, .applybs .nav-tabs-justified > .active > a:focus, .applybs .nav-tabs-justified > .active > a:hover {
    border-bottom-color: #fff;
  }
}
.applybs .tab-content > .tab-pane {
  display: none;
}
.applybs .tab-content > .active {
  display: block;
}
.applybs .nav-tabs .dropdown-menu {
  margin-top: -1px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}
.applybs .navbar {
  position: relative;
  min-height: 50px;
  margin-bottom: 20px;
  border: 1px solid transparent;
}
@media (min-width: 768px) {
  .applybs .navbar {
    border-radius: 4px;
  }
}
@media (min-width: 768px) {
  .applybs .navbar-header {
    float: left;
  }
}
.applybs .navbar-collapse {
  padding-right: 15px;
  padding-left: 15px;
  overflow-x: visible;
  -webkit-overflow-scrolling: touch;
  border-top: 1px solid transparent;
  -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.1);
  box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.1);
}
.applybs .navbar-collapse.in {
  overflow-y: auto;
}
@media (min-width: 768px) {
  .applybs .navbar-collapse {
    width: auto;
    border-top: 0;
    -webkit-box-shadow: none;
    box-shadow: none;
  }
  .applybs .navbar-collapse.collapse {
    display: block !important;
    height: auto !important;
    padding-bottom: 0;
    overflow: visible !important;
  }
  .applybs .navbar-collapse.in {
    overflow-y: visible;
  }
  .applybs .navbar-fixed-bottom .navbar-collapse, .applybs .navbar-fixed-top .navbar-collapse, .applybs .navbar-static-top .navbar-collapse {
    padding-right: 0;
    padding-left: 0;
  }
}
.applybs .navbar-fixed-bottom .navbar-collapse, .applybs .navbar-fixed-top .navbar-collapse {
  max-height: 340px;
}
@media (max-device-width: 480px) and (orientation: landscape) {
  .applybs .navbar-fixed-bottom .navbar-collapse, .applybs .navbar-fixed-top .navbar-collapse {
    max-height: 200px;
  }
}
.applybs .container-fluid > .navbar-collapse, .applybs .container-fluid > .navbar-header, .applybs .container > .navbar-collapse, .applybs .container > .navbar-header {
  margin-right: -15px;
  margin-left: -15px;
}
@media (min-width: 768px) {
  .applybs .container-fluid > .navbar-collapse, .applybs .container-fluid > .navbar-header, .applybs .container > .navbar-collapse, .applybs .container > .navbar-header {
    margin-right: 0;
    margin-left: 0;
  }
}
.applybs .navbar-static-top {
  z-index: 1000;
  border-width: 0 0 1px;
}
@media (min-width: 768px) {
  .applybs .navbar-static-top {
    border-radius: 0;
  }
}
.applybs .navbar-fixed-bottom, .applybs .navbar-fixed-top {
  position: fixed;
  right: 0;
  left: 0;
  z-index: 1030;
}
@media (min-width: 768px) {
  .applybs .navbar-fixed-bottom, .applybs .navbar-fixed-top {
    border-radius: 0;
  }
}
.applybs .navbar-fixed-top {
  top: 0;
  border-width: 0 0 1px;
}
.applybs .navbar-fixed-bottom {
  bottom: 0;
  margin-bottom: 0;
  border-width: 1px 0 0;
}
.applybs .navbar-brand {
  float: left;
  height: 50px;
  padding: 15px 15px;
  font-size: 18px;
  line-height: 20px;
}
.applybs .navbar-brand:focus, .applybs .navbar-brand:hover {
  text-decoration: none;
}
.applybs .navbar-brand > img {
  display: block;
}
@media (min-width: 768px) {
  .applybs .navbar > .container .navbar-brand, .applybs .navbar > .container-fluid .navbar-brand {
    margin-left: -15px;
  }
}
.applybs .navbar-toggle {
  position: relative;
  float: right;
  padding: 9px 10px;
  margin-top: 8px;
  margin-right: 15px;
  margin-bottom: 8px;
  background-color: transparent;
  background-image: none;
  border: 1px solid transparent;
  border-radius: 4px;
}
.applybs .navbar-toggle:focus {
  outline: 0;
}
.applybs .navbar-toggle .icon-bar {
  display: block;
  width: 22px;
  height: 2px;
  border-radius: 1px;
}
.applybs .navbar-toggle .icon-bar + .icon-bar {
  margin-top: 4px;
}
@media (min-width: 768px) {
  .applybs .navbar-toggle {
    display: none;
  }
}
.applybs .navbar-nav {
  margin: 7.5px -15px;
}
.applybs .navbar-nav > li > a {
  padding-top: 10px;
  padding-bottom: 10px;
  line-height: 20px;
}
@media (max-width: 767px) {
  .applybs .navbar-nav .open .dropdown-menu {
    position: static;
    float: none;
    width: auto;
    margin-top: 0;
    background-color: transparent;
    border: 0;
    -webkit-box-shadow: none;
    box-shadow: none;
  }
  .applybs .navbar-nav .open .dropdown-menu .dropdown-header, .applybs .navbar-nav .open .dropdown-menu > li > a {
    padding: 5px 15px 5px 25px;
  }
  .applybs .navbar-nav .open .dropdown-menu > li > a {
    line-height: 20px;
  }
  .applybs .navbar-nav .open .dropdown-menu > li > a:focus, .applybs .navbar-nav .open .dropdown-menu > li > a:hover {
    background-image: none;
  }
}
@media (min-width: 768px) {
  .applybs .navbar-nav {
    float: left;
    margin: 0;
  }
  .applybs .navbar-nav > li {
    float: left;
  }
  .applybs .navbar-nav > li > a {
    padding-top: 15px;
    padding-bottom: 15px;
  }
}
.applybs .navbar-form {
  padding: 10px 15px;
  margin-top: 8px;
  margin-right: -15px;
  margin-bottom: 8px;
  margin-left: -15px;
  border-top: 1px solid transparent;
  border-bottom: 1px solid transparent;
  -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.1), 0 1px 0 rgba(255, 255, 255, 0.1);
  box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.1), 0 1px 0 rgba(255, 255, 255, 0.1);
}
@media (min-width: 768px) {
  .applybs .navbar-form .form-group {
    display: inline-block;
    margin-bottom: 0;
    vertical-align: middle;
  }
  .applybs .navbar-form .form-control {
    display: inline-block;
    width: auto;
    vertical-align: middle;
  }
  .applybs .navbar-form .form-control-static {
    display: inline-block;
  }
  .applybs .navbar-form .input-group {
    display: inline-table;
    vertical-align: middle;
  }
  .applybs .navbar-form .input-group .form-control, .applybs .navbar-form .input-group .input-group-addon, .applybs .navbar-form .input-group .input-group-btn {
    width: auto;
  }
  .applybs .navbar-form .input-group > .form-control {
    width: 100%;
  }
  .applybs .navbar-form .control-label {
    margin-bottom: 0;
    vertical-align: middle;
  }
  .applybs .navbar-form .checkbox, .applybs .navbar-form .radio {
    display: inline-block;
    margin-top: 0;
    margin-bottom: 0;
    vertical-align: middle;
  }
  .applybs .navbar-form .checkbox label, .applybs .navbar-form .radio label {
    padding-left: 0;
  }
  .applybs .navbar-form .checkbox input[type=checkbox], .applybs .navbar-form .radio input[type=radio] {
    position: relative;
    margin-left: 0;
  }
  .applybs .navbar-form .has-feedback .form-control-feedback {
    top: 0;
  }
}
@media (max-width: 767px) {
  .applybs .navbar-form .form-group {
    margin-bottom: 5px;
  }
  .applybs .navbar-form .form-group:last-child {
    margin-bottom: 0;
  }
}
@media (min-width: 768px) {
  .applybs .navbar-form {
    width: auto;
    padding-top: 0;
    padding-bottom: 0;
    margin-right: 0;
    margin-left: 0;
    border: 0;
    -webkit-box-shadow: none;
    box-shadow: none;
  }
}
.applybs .navbar-nav > li > .dropdown-menu {
  margin-top: 0;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}
.applybs .navbar-fixed-bottom .navbar-nav > li > .dropdown-menu {
  margin-bottom: 0;
  border-top-left-radius: 4px;
  border-top-right-radius: 4px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}
.applybs .navbar-btn {
  margin-top: 8px;
  margin-bottom: 8px;
}
.applybs .navbar-btn.btn-sm {
  margin-top: 10px;
  margin-bottom: 10px;
}
.applybs .navbar-btn.btn-xs {
  margin-top: 14px;
  margin-bottom: 14px;
}
.applybs .navbar-text {
  margin-top: 15px;
  margin-bottom: 15px;
}
@media (min-width: 768px) {
  .applybs .navbar-text {
    float: left;
    margin-right: 15px;
    margin-left: 15px;
  }
}
@media (min-width: 768px) {
  .applybs .navbar-left {
    float: left !important;
  }
  .applybs .navbar-right {
    float: right !important;
    margin-right: -15px;
  }
  .applybs .navbar-right ~ .navbar-right {
    margin-right: 0;
  }
}
.applybs .navbar-default {
  background-color: #f8f8f8;
  border-color: #e7e7e7;
}
.applybs .navbar-default .navbar-brand {
  color: #777;
}
.applybs .navbar-default .navbar-brand:focus, .applybs .navbar-default .navbar-brand:hover {
  color: #5e5e5e;
  background-color: transparent;
}
.applybs .navbar-default .navbar-text {
  color: #777;
}
.applybs .navbar-default .navbar-nav > li > a {
  color: #777;
}
.applybs .navbar-default .navbar-nav > li > a:focus, .applybs .navbar-default .navbar-nav > li > a:hover {
  color: #333;
  background-color: transparent;
}
.applybs .navbar-default .navbar-nav > .active > a, .applybs .navbar-default .navbar-nav > .active > a:focus, .applybs .navbar-default .navbar-nav > .active > a:hover {
  color: #555;
  background-color: #e7e7e7;
}
.applybs .navbar-default .navbar-nav > .disabled > a, .applybs .navbar-default .navbar-nav > .disabled > a:focus, .applybs .navbar-default .navbar-nav > .disabled > a:hover {
  color: #ccc;
  background-color: transparent;
}
.applybs .navbar-default .navbar-toggle {
  border-color: #ddd;
}
.applybs .navbar-default .navbar-toggle:focus, .applybs .navbar-default .navbar-toggle:hover {
  background-color: #ddd;
}
.applybs .navbar-default .navbar-toggle .icon-bar {
  background-color: #888;
}
.applybs .navbar-default .navbar-collapse, .applybs .navbar-default .navbar-form {
  border-color: #e7e7e7;
}
.applybs .navbar-default .navbar-nav > .open > a, .applybs .navbar-default .navbar-nav > .open > a:focus, .applybs .navbar-default .navbar-nav > .open > a:hover {
  color: #555;
  background-color: #e7e7e7;
}
@media (max-width: 767px) {
  .applybs .navbar-default .navbar-nav .open .dropdown-menu > li > a {
    color: #777;
  }
  .applybs .navbar-default .navbar-nav .open .dropdown-menu > li > a:focus, .applybs .navbar-default .navbar-nav .open .dropdown-menu > li > a:hover {
    color: #333;
    background-color: transparent;
  }
  .applybs .navbar-default .navbar-nav .open .dropdown-menu > .active > a, .applybs .navbar-default .navbar-nav .open .dropdown-menu > .active > a:focus, .applybs .navbar-default .navbar-nav .open .dropdown-menu > .active > a:hover {
    color: #555;
    background-color: #e7e7e7;
  }
  .applybs .navbar-default .navbar-nav .open .dropdown-menu > .disabled > a, .applybs .navbar-default .navbar-nav .open .dropdown-menu > .disabled > a:focus, .applybs .navbar-default .navbar-nav .open .dropdown-menu > .disabled > a:hover {
    color: #ccc;
    background-color: transparent;
  }
}
.applybs .navbar-default .navbar-link {
  color: #777;
}
.applybs .navbar-default .navbar-link:hover {
  color: #333;
}
.applybs .navbar-default .btn-link {
  color: #777;
}
.applybs .navbar-default .btn-link:focus, .applybs .navbar-default .btn-link:hover {
  color: #333;
}
.applybs .navbar-default .btn-link[disabled]:focus, .applybs .navbar-default .btn-link[disabled]:hover, .applybs fieldset[disabled] .navbar-default .btn-link:focus, .applybs fieldset[disabled] .navbar-default .btn-link:hover {
  color: #ccc;
}
.applybs .navbar-inverse {
  background-color: #222;
  border-color: #080808;
}
.applybs .navbar-inverse .navbar-brand {
  color: #9d9d9d;
}
.applybs .navbar-inverse .navbar-brand:focus, .applybs .navbar-inverse .navbar-brand:hover {
  color: #fff;
  background-color: transparent;
}
.applybs .navbar-inverse .navbar-text {
  color: #9d9d9d;
}
.applybs .navbar-inverse .navbar-nav > li > a {
  color: #9d9d9d;
}
.applybs .navbar-inverse .navbar-nav > li > a:focus, .applybs .navbar-inverse .navbar-nav > li > a:hover {
  color: #fff;
  background-color: transparent;
}
.applybs .navbar-inverse .navbar-nav > .active > a, .applybs .navbar-inverse .navbar-nav > .active > a:focus, .applybs .navbar-inverse .navbar-nav > .active > a:hover {
  color: #fff;
  background-color: #080808;
}
.applybs .navbar-inverse .navbar-nav > .disabled > a, .applybs .navbar-inverse .navbar-nav > .disabled > a:focus, .applybs .navbar-inverse .navbar-nav > .disabled > a:hover {
  color: #444;
  background-color: transparent;
}
.applybs .navbar-inverse .navbar-toggle {
  border-color: #333;
}
.applybs .navbar-inverse .navbar-toggle:focus, .applybs .navbar-inverse .navbar-toggle:hover {
  background-color: #333;
}
.applybs .navbar-inverse .navbar-toggle .icon-bar {
  background-color: #fff;
}
.applybs .navbar-inverse .navbar-collapse, .applybs .navbar-inverse .navbar-form {
  border-color: #101010;
}
.applybs .navbar-inverse .navbar-nav > .open > a, .applybs .navbar-inverse .navbar-nav > .open > a:focus, .applybs .navbar-inverse .navbar-nav > .open > a:hover {
  color: #fff;
  background-color: #080808;
}
@media (max-width: 767px) {
  .applybs .navbar-inverse .navbar-nav .open .dropdown-menu > .dropdown-header {
    border-color: #080808;
  }
  .applybs .navbar-inverse .navbar-nav .open .dropdown-menu .divider {
    background-color: #080808;
  }
  .applybs .navbar-inverse .navbar-nav .open .dropdown-menu > li > a {
    color: #9d9d9d;
  }
  .applybs .navbar-inverse .navbar-nav .open .dropdown-menu > li > a:focus, .applybs .navbar-inverse .navbar-nav .open .dropdown-menu > li > a:hover {
    color: #fff;
    background-color: transparent;
  }
  .applybs .navbar-inverse .navbar-nav .open .dropdown-menu > .active > a, .applybs .navbar-inverse .navbar-nav .open .dropdown-menu > .active > a:focus, .applybs .navbar-inverse .navbar-nav .open .dropdown-menu > .active > a:hover {
    color: #fff;
    background-color: #080808;
  }
  .applybs .navbar-inverse .navbar-nav .open .dropdown-menu > .disabled > a, .applybs .navbar-inverse .navbar-nav .open .dropdown-menu > .disabled > a:focus, .applybs .navbar-inverse .navbar-nav .open .dropdown-menu > .disabled > a:hover {
    color: #444;
    background-color: transparent;
  }
}
.applybs .navbar-inverse .navbar-link {
  color: #9d9d9d;
}
.applybs .navbar-inverse .navbar-link:hover {
  color: #fff;
}
.applybs .navbar-inverse .btn-link {
  color: #9d9d9d;
}
.applybs .navbar-inverse .btn-link:focus, .applybs .navbar-inverse .btn-link:hover {
  color: #fff;
}
.applybs .navbar-inverse .btn-link[disabled]:focus, .applybs .navbar-inverse .btn-link[disabled]:hover, .applybs fieldset[disabled] .navbar-inverse .btn-link:focus, .applybs fieldset[disabled] .navbar-inverse .btn-link:hover {
  color: #444;
}
.applybs .breadcrumb {
  padding: 8px 15px;
  margin-bottom: 20px;
  list-style: none;
  background-color: #f5f5f5;
  border-radius: 4px;
}
.applybs .breadcrumb > li {
  display: inline-block;
}
.applybs .breadcrumb > li + li:before {
  padding: 0 5px;
  color: #ccc;
  content: "/\00a0";
}
.applybs .breadcrumb > .active {
  color: #777;
}
.applybs .pagination {
  display: inline-block;
  padding-left: 0;
  margin: 20px 0;
  border-radius: 4px;
}
.applybs .pagination > li {
  display: inline;
}
.applybs .pagination > li > a, .applybs .pagination > li > span {
  position: relative;
  float: left;
  padding: 6px 12px;
  margin-left: -1px;
  line-height: 1.42857143;
  color: #337ab7;
  text-decoration: none;
  background-color: #fff;
  border: 1px solid #ddd;
}
.applybs .pagination > li:first-child > a, .applybs .pagination > li:first-child > span {
  margin-left: 0;
  border-top-left-radius: 4px;
  border-bottom-left-radius: 4px;
}
.applybs .pagination > li:last-child > a, .applybs .pagination > li:last-child > span {
  border-top-right-radius: 4px;
  border-bottom-right-radius: 4px;
}
.applybs .pagination > li > a:focus, .applybs .pagination > li > a:hover, .applybs .pagination > li > span:focus, .applybs .pagination > li > span:hover {
  z-index: 2;
  color: #23527c;
  background-color: #eee;
  border-color: #ddd;
}
.applybs .pagination > .active > a, .applybs .pagination > .active > a:focus, .applybs .pagination > .active > a:hover, .applybs .pagination > .active > span, .applybs .pagination > .active > span:focus, .applybs .pagination > .active > span:hover {
  z-index: 3;
  color: #fff;
  cursor: default;
  background-color: #337ab7;
  border-color: #337ab7;
}
.applybs .pagination > .disabled > a, .applybs .pagination > .disabled > a:focus, .applybs .pagination > .disabled > a:hover, .applybs .pagination > .disabled > span, .applybs .pagination > .disabled > span:focus, .applybs .pagination > .disabled > span:hover {
  color: #777;
  cursor: not-allowed;
  background-color: #fff;
  border-color: #ddd;
}
.applybs .pagination-lg > li > a, .applybs .pagination-lg > li > span {
  padding: 10px 16px;
  font-size: 18px;
  line-height: 1.3333333;
}
.applybs .pagination-lg > li:first-child > a, .applybs .pagination-lg > li:first-child > span {
  border-top-left-radius: 6px;
  border-bottom-left-radius: 6px;
}
.applybs .pagination-lg > li:last-child > a, .applybs .pagination-lg > li:last-child > span {
  border-top-right-radius: 6px;
  border-bottom-right-radius: 6px;
}
.applybs .pagination-sm > li > a, .applybs .pagination-sm > li > span {
  padding: 5px 10px;
  font-size: 12px;
  line-height: 1.5;
}
.applybs .pagination-sm > li:first-child > a, .applybs .pagination-sm > li:first-child > span {
  border-top-left-radius: 3px;
  border-bottom-left-radius: 3px;
}
.applybs .pagination-sm > li:last-child > a, .applybs .pagination-sm > li:last-child > span {
  border-top-right-radius: 3px;
  border-bottom-right-radius: 3px;
}
.applybs .pager {
  padding-left: 0;
  margin: 20px 0;
  text-align: center;
  list-style: none;
}
.applybs .pager li {
  display: inline;
}
.applybs .pager li > a, .applybs .pager li > span {
  display: inline-block;
  padding: 5px 14px;
  background-color: #fff;
  border: 1px solid #ddd;
  border-radius: 15px;
}
.applybs .pager li > a:focus, .applybs .pager li > a:hover {
  text-decoration: none;
  background-color: #eee;
}
.applybs .pager .next > a, .applybs .pager .next > span {
  float: right;
}
.applybs .pager .previous > a, .applybs .pager .previous > span {
  float: left;
}
.applybs .pager .disabled > a, .applybs .pager .disabled > a:focus, .applybs .pager .disabled > a:hover, .applybs .pager .disabled > span {
  color: #777;
  cursor: not-allowed;
  background-color: #fff;
}
.applybs .label {
  display: inline;
  padding: .2em .6em .3em;
  font-size: 75%;
  font-weight: 700;
  line-height: 1;
  color: #fff;
  text-align: center;
  white-space: nowrap;
  vertical-align: baseline;
  border-radius: .25em;
}
.applybs a.label:focus, .applybs a.label:hover {
  color: #fff;
  text-decoration: none;
  cursor: pointer;
}
.applybs .label:empty {
  display: none;
}
.applybs .btn .label {
  position: relative;
  top: -1px;
}
.applybs .label-default {
  background-color: #777;
}
.applybs .label-default[href]:focus, .applybs .label-default[href]:hover {
  background-color: #5e5e5e;
}
.applybs .label-primary {
  background-color: #337ab7;
}
.applybs .label-primary[href]:focus, .applybs .label-primary[href]:hover {
  background-color: #286090;
}
.applybs .label-success {
  background-color: #5cb85c;
}
.applybs .label-success[href]:focus, .applybs .label-success[href]:hover {
  background-color: #449d44;
}
.applybs .label-info {
  background-color: #5bc0de;
}
.applybs .label-info[href]:focus, .applybs .label-info[href]:hover {
  background-color: #31b0d5;
}
.applybs .label-warning {
  background-color: #f0ad4e;
}
.applybs .label-warning[href]:focus, .applybs .label-warning[href]:hover {
  background-color: #ec971f;
}
.applybs .label-danger {
  background-color: #d9534f;
}
.applybs .label-danger[href]:focus, .applybs .label-danger[href]:hover {
  background-color: #c9302c;
}
.applybs .badge {
  display: inline-block;
  min-width: 10px;
  padding: 3px 7px;
  font-size: 12px;
  font-weight: 700;
  line-height: 1;
  color: #fff;
  text-align: center;
  white-space: nowrap;
  vertical-align: middle;
  background-color: #777;
  border-radius: 10px;
}
.applybs .badge:empty {
  display: none;
}
.applybs .btn .badge {
  position: relative;
  top: -1px;
}
.applybs .btn-group-xs > .btn .badge, .applybs .btn-xs .badge {
  top: 0;
  padding: 1px 5px;
}
.applybs a.badge:focus, .applybs a.badge:hover {
  color: #fff;
  text-decoration: none;
  cursor: pointer;
}
.applybs .list-group-item.active > .badge, .applybs .nav-pills > .active > a > .badge {
  color: #337ab7;
  background-color: #fff;
}
.applybs .list-group-item > .badge {
  float: right;
}
.applybs .list-group-item > .badge + .badge {
  margin-right: 5px;
}
.applybs .nav-pills > li > a > .badge {
  margin-left: 3px;
}
.applybs .jumbotron {
  padding-top: 30px;
  padding-bottom: 30px;
  margin-bottom: 30px;
  color: inherit;
  background-color: #eee;
}
.applybs .jumbotron .h1, .applybs .jumbotron h1 {
  color: inherit;
}
.applybs .jumbotron p {
  margin-bottom: 15px;
  font-size: 21px;
  font-weight: 200;
}
.applybs .jumbotron > hr {
  border-top-color: #d5d5d5;
}
.applybs .container .jumbotron, .applybs .container-fluid .jumbotron {
  padding-right: 15px;
  padding-left: 15px;
  border-radius: 6px;
}
.applybs .jumbotron .container {
  max-width: 100%;
}
@media screen and (min-width: 768px) {
  .applybs .jumbotron {
    padding-top: 48px;
    padding-bottom: 48px;
  }
  .applybs .container .jumbotron, .applybs .container-fluid .jumbotron {
    padding-right: 60px;
    padding-left: 60px;
  }
  .applybs .jumbotron .h1, .applybs .jumbotron h1 {
    font-size: 63px;
  }
}
.applybs .thumbnail {
  display: block;
  padding: 4px;
  margin-bottom: 20px;
  line-height: 1.42857143;
  background-color: #fff;
  border: 1px solid #ddd;
  border-radius: 4px;
  -webkit-transition: border .2s ease-in-out;
  -o-transition: border .2s ease-in-out;
  transition: border .2s ease-in-out;
}
.applybs .thumbnail a > img, .applybs .thumbnail > img {
  margin-right: auto;
  margin-left: auto;
}
.applybs a.thumbnail.active, .applybs a.thumbnail:focus, .applybs a.thumbnail:hover {
  border-color: #337ab7;
}
.applybs .thumbnail .caption {
  padding: 9px;
  color: #333;
}
.applybs .alert {
  padding: 15px;
  margin-bottom: 20px;
  border: 1px solid transparent;
  border-radius: 4px;
}
.applybs .alert h4 {
  margin-top: 0;
  color: inherit;
}
.applybs .alert .alert-link {
  font-weight: 700;
}
.applybs .alert > p, .applybs .alert > ul {
  margin-bottom: 0;
}
.applybs .alert > p + p {
  margin-top: 5px;
}
.applybs .alert-dismissable, .applybs .alert-dismissible {
  padding-right: 35px;
}
.applybs .alert-dismissable .close, .applybs .alert-dismissible .close {
  position: relative;
  top: -2px;
  right: -21px;
  color: inherit;
}
.applybs .alert-success {
  color: #3c763d;
  background-color: #dff0d8;
  border-color: #d6e9c6;
}
.applybs .alert-success hr {
  border-top-color: #c9e2b3;
}
.applybs .alert-success .alert-link {
  color: #2b542c;
}
.applybs .alert-info {
  color: #31708f;
  background-color: #d9edf7;
  border-color: #bce8f1;
}
.applybs .alert-info hr {
  border-top-color: #a6e1ec;
}
.applybs .alert-info .alert-link {
  color: #245269;
}
.applybs .alert-warning {
  color: #8a6d3b;
  background-color: #fcf8e3;
  border-color: #faebcc;
}
.applybs .alert-warning hr {
  border-top-color: #f7e1b5;
}
.applybs .alert-warning .alert-link {
  color: #66512c;
}
.applybs .alert-danger {
  color: #a94442;
  background-color: #f2dede;
  border-color: #ebccd1;
}
.applybs .alert-danger hr {
  border-top-color: #e4b9c0;
}
.applybs .alert-danger .alert-link {
  color: #843534;
}
@-webkit-keyframes progress-bar-stripes {
  from {
    background-position: 40px 0;
  }
  to {
    background-position: 0 0;
  }
}
@-o-keyframes progress-bar-stripes {
  from {
    background-position: 40px 0;
  }
  to {
    background-position: 0 0;
  }
}
@keyframes progress-bar-stripes {
  from {
    background-position: 40px 0;
  }
  to {
    background-position: 0 0;
  }
}
.applybs .progress {
  height: 20px;
  margin-bottom: 20px;
  overflow: hidden;
  background-color: #f5f5f5;
  border-radius: 4px;
  -webkit-box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
  box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
}
.applybs .progress-bar {
  float: left;
  width: 0;
  height: 100%;
  font-size: 12px;
  line-height: 20px;
  color: #fff;
  text-align: center;
  background-color: #337ab7;
  -webkit-box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.15);
  box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.15);
  -webkit-transition: width .6s ease;
  -o-transition: width .6s ease;
  transition: width .6s ease;
}
.applybs .progress-bar-striped, .applybs .progress-striped .progress-bar {
  background-image: -webkit-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
  background-image: -o-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
  background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
  -webkit-background-size: 40px 40px;
  background-size: 40px 40px;
}
.applybs .progress-bar.active, .applybs .progress.active .progress-bar {
  -webkit-animation: progress-bar-stripes 2s linear infinite;
  -o-animation: progress-bar-stripes 2s linear infinite;
  animation: progress-bar-stripes 2s linear infinite;
}
.applybs .progress-bar-success {
  background-color: #5cb85c;
}
.applybs .progress-striped .progress-bar-success {
  background-image: -webkit-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
  background-image: -o-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
  background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
}
.applybs .progress-bar-info {
  background-color: #5bc0de;
}
.applybs .progress-striped .progress-bar-info {
  background-image: -webkit-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
  background-image: -o-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
  background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
}
.applybs .progress-bar-warning {
  background-color: #f0ad4e;
}
.applybs .progress-striped .progress-bar-warning {
  background-image: -webkit-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
  background-image: -o-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
  background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
}
.applybs .progress-bar-danger {
  background-color: #d9534f;
}
.applybs .progress-striped .progress-bar-danger {
  background-image: -webkit-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
  background-image: -o-linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
  background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, 0.15) 50%, rgba(255, 255, 255, 0.15) 75%, transparent 75%, transparent);
}
.applybs .media {
  margin-top: 15px;
}
.applybs .media:first-child {
  margin-top: 0;
}
.applybs .media, .applybs .media-body {
  overflow: hidden;
  zoom: 1;
}
.applybs .media-body {
  width: 10000px;
}
.applybs .media-object {
  display: block;
}
.applybs .media-object.img-thumbnail {
  max-width: none;
}
.applybs .media-right, .applybs .media > .pull-right {
  padding-left: 10px;
}
.applybs .media-left, .applybs .media > .pull-left {
  padding-right: 10px;
}
.applybs .media-body, .applybs .media-left, .applybs .media-right {
  display: table-cell;
  vertical-align: top;
}
.applybs .media-middle {
  vertical-align: middle;
}
.applybs .media-bottom {
  vertical-align: bottom;
}
.applybs .media-heading {
  margin-top: 0;
  margin-bottom: 5px;
}
.applybs .media-list {
  padding-left: 0;
  list-style: none;
}
.applybs .list-group {
  padding-left: 0;
  margin-bottom: 20px;
}
.applybs .list-group-item {
  position: relative;
  display: block;
  padding: 10px 15px;
  margin-bottom: -1px;
  background-color: #fff;
  border: 1px solid #ddd;
}
.applybs .list-group-item:first-child {
  border-top-left-radius: 4px;
  border-top-right-radius: 4px;
}
.applybs .list-group-item:last-child {
  margin-bottom: 0;
  border-bottom-right-radius: 4px;
  border-bottom-left-radius: 4px;
}
.applybs a.list-group-item, .applybs button.list-group-item {
  color: #555;
}
.applybs a.list-group-item .list-group-item-heading, .applybs button.list-group-item .list-group-item-heading {
  color: #333;
}
.applybs a.list-group-item:focus, .applybs a.list-group-item:hover, .applybs button.list-group-item:focus, .applybs button.list-group-item:hover {
  color: #555;
  text-decoration: none;
  background-color: #f5f5f5;
}
.applybs button.list-group-item {
  width: 100%;
  text-align: left;
}
.applybs .list-group-item.disabled, .applybs .list-group-item.disabled:focus, .applybs .list-group-item.disabled:hover {
  color: #777;
  cursor: not-allowed;
  background-color: #eee;
}
.applybs .list-group-item.disabled .list-group-item-heading, .applybs .list-group-item.disabled:focus .list-group-item-heading, .applybs .list-group-item.disabled:hover .list-group-item-heading {
  color: inherit;
}
.applybs .list-group-item.disabled .list-group-item-text, .applybs .list-group-item.disabled:focus .list-group-item-text, .applybs .list-group-item.disabled:hover .list-group-item-text {
  color: #777;
}
.applybs .list-group-item.active, .applybs .list-group-item.active:focus, .applybs .list-group-item.active:hover {
  z-index: 2;
  color: #fff;
  background-color: #337ab7;
  border-color: #337ab7;
}
.applybs .list-group-item.active .list-group-item-heading, .applybs .list-group-item.active .list-group-item-heading > .small, .applybs .list-group-item.active .list-group-item-heading > small, .applybs .list-group-item.active:focus .list-group-item-heading, .applybs .list-group-item.active:focus .list-group-item-heading > .small, .applybs .list-group-item.active:focus .list-group-item-heading > small, .applybs .list-group-item.active:hover .list-group-item-heading, .applybs .list-group-item.active:hover .list-group-item-heading > .small, .applybs .list-group-item.active:hover .list-group-item-heading > small {
  color: inherit;
}
.applybs .list-group-item.active .list-group-item-text, .applybs .list-group-item.active:focus .list-group-item-text, .applybs .list-group-item.active:hover .list-group-item-text {
  color: #c7ddef;
}
.applybs .list-group-item-success {
  color: #3c763d;
  background-color: #dff0d8;
}
.applybs a.list-group-item-success, .applybs button.list-group-item-success {
  color: #3c763d;
}
.applybs a.list-group-item-success .list-group-item-heading, .applybs button.list-group-item-success .list-group-item-heading {
  color: inherit;
}
.applybs a.list-group-item-success:focus, .applybs a.list-group-item-success:hover, .applybs button.list-group-item-success:focus, .applybs button.list-group-item-success:hover {
  color: #3c763d;
  background-color: #d0e9c6;
}
.applybs a.list-group-item-success.active, .applybs a.list-group-item-success.active:focus, .applybs a.list-group-item-success.active:hover, .applybs button.list-group-item-success.active, .applybs button.list-group-item-success.active:focus, .applybs button.list-group-item-success.active:hover {
  color: #fff;
  background-color: #3c763d;
  border-color: #3c763d;
}
.applybs .list-group-item-info {
  color: #31708f;
  background-color: #d9edf7;
}
.applybs a.list-group-item-info, .applybs button.list-group-item-info {
  color: #31708f;
}
.applybs a.list-group-item-info .list-group-item-heading, .applybs button.list-group-item-info .list-group-item-heading {
  color: inherit;
}
.applybs a.list-group-item-info:focus, .applybs a.list-group-item-info:hover, .applybs button.list-group-item-info:focus, .applybs button.list-group-item-info:hover {
  color: #31708f;
  background-color: #c4e3f3;
}
.applybs a.list-group-item-info.active, .applybs a.list-group-item-info.active:focus, .applybs a.list-group-item-info.active:hover, .applybs button.list-group-item-info.active, .applybs button.list-group-item-info.active:focus, .applybs button.list-group-item-info.active:hover {
  color: #fff;
  background-color: #31708f;
  border-color: #31708f;
}
.applybs .list-group-item-warning {
  color: #8a6d3b;
  background-color: #fcf8e3;
}
.applybs a.list-group-item-warning, .applybs button.list-group-item-warning {
  color: #8a6d3b;
}
.applybs a.list-group-item-warning .list-group-item-heading, .applybs button.list-group-item-warning .list-group-item-heading {
  color: inherit;
}
.applybs a.list-group-item-warning:focus, .applybs a.list-group-item-warning:hover, .applybs button.list-group-item-warning:focus, .applybs button.list-group-item-warning:hover {
  color: #8a6d3b;
  background-color: #faf2cc;
}
.applybs a.list-group-item-warning.active, .applybs a.list-group-item-warning.active:focus, .applybs a.list-group-item-warning.active:hover, .applybs button.list-group-item-warning.active, .applybs button.list-group-item-warning.active:focus, .applybs button.list-group-item-warning.active:hover {
  color: #fff;
  background-color: #8a6d3b;
  border-color: #8a6d3b;
}
.applybs .list-group-item-danger {
  color: #a94442;
  background-color: #f2dede;
}
.applybs a.list-group-item-danger, .applybs button.list-group-item-danger {
  color: #a94442;
}
.applybs a.list-group-item-danger .list-group-item-heading, .applybs button.list-group-item-danger .list-group-item-heading {
  color: inherit;
}
.applybs a.list-group-item-danger:focus, .applybs a.list-group-item-danger:hover, .applybs button.list-group-item-danger:focus, .applybs button.list-group-item-danger:hover {
  color: #a94442;
  background-color: #ebcccc;
}
.applybs a.list-group-item-danger.active, .applybs a.list-group-item-danger.active:focus, .applybs a.list-group-item-danger.active:hover, .applybs button.list-group-item-danger.active, .applybs button.list-group-item-danger.active:focus, .applybs button.list-group-item-danger.active:hover {
  color: #fff;
  background-color: #a94442;
  border-color: #a94442;
}
.applybs .list-group-item-heading {
  margin-top: 0;
  margin-bottom: 5px;
}
.applybs .list-group-item-text {
  margin-bottom: 0;
  line-height: 1.3;
}
.applybs .panel {
  margin-bottom: 20px;
  background-color: #fff;
  border: 1px solid transparent;
  border-radius: 4px;
  -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
  box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
}
.applybs .panel-body {
  padding: 15px;
}
.applybs .panel-heading {
  padding: 10px 15px;
  border-bottom: 1px solid transparent;
  border-top-left-radius: 3px;
  border-top-right-radius: 3px;
}
.applybs .panel-heading > .dropdown .dropdown-toggle {
  color: inherit;
}
.applybs .panel-title {
  margin-top: 0;
  margin-bottom: 0;
  font-size: 16px;
  color: inherit;
}
.applybs .panel-title > .small, .applybs .panel-title > .small > a, .applybs .panel-title > a, .applybs .panel-title > small, .applybs .panel-title > small > a {
  color: inherit;
}
.applybs .panel-footer {
  padding: 10px 15px;
  background-color: #f5f5f5;
  border-top: 1px solid #ddd;
  border-bottom-right-radius: 3px;
  border-bottom-left-radius: 3px;
}
.applybs .panel > .list-group, .applybs .panel > .panel-collapse > .list-group {
  margin-bottom: 0;
}
.applybs .panel > .list-group .list-group-item, .applybs .panel > .panel-collapse > .list-group .list-group-item {
  border-width: 1px 0;
  border-radius: 0;
}
.applybs .panel > .list-group:first-child .list-group-item:first-child, .applybs .panel > .panel-collapse > .list-group:first-child .list-group-item:first-child {
  border-top: 0;
  border-top-left-radius: 3px;
  border-top-right-radius: 3px;
}
.applybs .panel > .list-group:last-child .list-group-item:last-child, .applybs .panel > .panel-collapse > .list-group:last-child .list-group-item:last-child {
  border-bottom: 0;
  border-bottom-right-radius: 3px;
  border-bottom-left-radius: 3px;
}
.applybs .panel > .panel-heading + .panel-collapse > .list-group .list-group-item:first-child {
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}
.applybs .panel-heading + .list-group .list-group-item:first-child {
  border-top-width: 0;
}
.applybs .list-group + .panel-footer {
  border-top-width: 0;
}
.applybs .panel > .panel-collapse > .table, .applybs .panel > .table, .applybs .panel > .table-responsive > .table {
  margin-bottom: 0;
}
.applybs .panel > .panel-collapse > .table caption, .applybs .panel > .table caption, .applybs .panel > .table-responsive > .table caption {
  padding-right: 15px;
  padding-left: 15px;
}
.applybs .panel > .table-responsive:first-child > .table:first-child, .applybs .panel > .table:first-child {
  border-top-left-radius: 3px;
  border-top-right-radius: 3px;
}
.applybs .panel > .table-responsive:first-child > .table:first-child > tbody:first-child > tr:first-child, .applybs .panel > .table-responsive:first-child > .table:first-child > thead:first-child > tr:first-child, .applybs .panel > .table:first-child > tbody:first-child > tr:first-child, .applybs .panel > .table:first-child > thead:first-child > tr:first-child {
  border-top-left-radius: 3px;
  border-top-right-radius: 3px;
}
.applybs .panel > .table-responsive:first-child > .table:first-child > tbody:first-child > tr:first-child td:first-child, .applybs .panel > .table-responsive:first-child > .table:first-child > tbody:first-child > tr:first-child th:first-child, .applybs .panel > .table-responsive:first-child > .table:first-child > thead:first-child > tr:first-child td:first-child, .applybs .panel > .table-responsive:first-child > .table:first-child > thead:first-child > tr:first-child th:first-child, .applybs .panel > .table:first-child > tbody:first-child > tr:first-child td:first-child, .applybs .panel > .table:first-child > tbody:first-child > tr:first-child th:first-child, .applybs .panel > .table:first-child > thead:first-child > tr:first-child td:first-child, .applybs .panel > .table:first-child > thead:first-child > tr:first-child th:first-child {
  border-top-left-radius: 3px;
}
.applybs .panel > .table-responsive:first-child > .table:first-child > tbody:first-child > tr:first-child td:last-child, .applybs .panel > .table-responsive:first-child > .table:first-child > tbody:first-child > tr:first-child th:last-child, .applybs .panel > .table-responsive:first-child > .table:first-child > thead:first-child > tr:first-child td:last-child, .applybs .panel > .table-responsive:first-child > .table:first-child > thead:first-child > tr:first-child th:last-child, .applybs .panel > .table:first-child > tbody:first-child > tr:first-child td:last-child, .applybs .panel > .table:first-child > tbody:first-child > tr:first-child th:last-child, .applybs .panel > .table:first-child > thead:first-child > tr:first-child td:last-child, .applybs .panel > .table:first-child > thead:first-child > tr:first-child th:last-child {
  border-top-right-radius: 3px;
}
.applybs .panel > .table-responsive:last-child > .table:last-child, .applybs .panel > .table:last-child {
  border-bottom-right-radius: 3px;
  border-bottom-left-radius: 3px;
}
.applybs .panel > .table-responsive:last-child > .table:last-child > tbody:last-child > tr:last-child, .applybs .panel > .table-responsive:last-child > .table:last-child > tfoot:last-child > tr:last-child, .applybs .panel > .table:last-child > tbody:last-child > tr:last-child, .applybs .panel > .table:last-child > tfoot:last-child > tr:last-child {
  border-bottom-right-radius: 3px;
  border-bottom-left-radius: 3px;
}
.applybs .panel > .table-responsive:last-child > .table:last-child > tbody:last-child > tr:last-child td:first-child, .applybs .panel > .table-responsive:last-child > .table:last-child > tbody:last-child > tr:last-child th:first-child, .applybs .panel > .table-responsive:last-child > .table:last-child > tfoot:last-child > tr:last-child td:first-child, .applybs .panel > .table-responsive:last-child > .table:last-child > tfoot:last-child > tr:last-child th:first-child, .applybs .panel > .table:last-child > tbody:last-child > tr:last-child td:first-child, .applybs .panel > .table:last-child > tbody:last-child > tr:last-child th:first-child, .applybs .panel > .table:last-child > tfoot:last-child > tr:last-child td:first-child, .applybs .panel > .table:last-child > tfoot:last-child > tr:last-child th:first-child {
  border-bottom-left-radius: 3px;
}
.applybs .panel > .table-responsive:last-child > .table:last-child > tbody:last-child > tr:last-child td:last-child, .applybs .panel > .table-responsive:last-child > .table:last-child > tbody:last-child > tr:last-child th:last-child, .applybs .panel > .table-responsive:last-child > .table:last-child > tfoot:last-child > tr:last-child td:last-child, .applybs .panel > .table-responsive:last-child > .table:last-child > tfoot:last-child > tr:last-child th:last-child, .applybs .panel > .table:last-child > tbody:last-child > tr:last-child td:last-child, .applybs .panel > .table:last-child > tbody:last-child > tr:last-child th:last-child, .applybs .panel > .table:last-child > tfoot:last-child > tr:last-child td:last-child, .applybs .panel > .table:last-child > tfoot:last-child > tr:last-child th:last-child {
  border-bottom-right-radius: 3px;
}
.applybs .panel > .panel-body + .table, .applybs .panel > .panel-body + .table-responsive, .applybs .panel > .table + .panel-body, .applybs .panel > .table-responsive + .panel-body {
  border-top: 1px solid #ddd;
}
.applybs .panel > .table > tbody:first-child > tr:first-child td, .applybs .panel > .table > tbody:first-child > tr:first-child th {
  border-top: 0;
}
.applybs .panel > .table-bordered, .applybs .panel > .table-responsive > .table-bordered {
  border: 0;
}
.applybs .panel > .table-bordered > tbody > tr > td:first-child, .applybs .panel > .table-bordered > tbody > tr > th:first-child, .applybs .panel > .table-bordered > tfoot > tr > td:first-child, .applybs .panel > .table-bordered > tfoot > tr > th:first-child, .applybs .panel > .table-bordered > thead > tr > td:first-child, .applybs .panel > .table-bordered > thead > tr > th:first-child, .applybs .panel > .table-responsive > .table-bordered > tbody > tr > td:first-child, .applybs .panel > .table-responsive > .table-bordered > tbody > tr > th:first-child, .applybs .panel > .table-responsive > .table-bordered > tfoot > tr > td:first-child, .applybs .panel > .table-responsive > .table-bordered > tfoot > tr > th:first-child, .applybs .panel > .table-responsive > .table-bordered > thead > tr > td:first-child, .applybs .panel > .table-responsive > .table-bordered > thead > tr > th:first-child {
  border-left: 0;
}
.applybs .panel > .table-bordered > tbody > tr > td:last-child, .applybs .panel > .table-bordered > tbody > tr > th:last-child, .applybs .panel > .table-bordered > tfoot > tr > td:last-child, .applybs .panel > .table-bordered > tfoot > tr > th:last-child, .applybs .panel > .table-bordered > thead > tr > td:last-child, .applybs .panel > .table-bordered > thead > tr > th:last-child, .applybs .panel > .table-responsive > .table-bordered > tbody > tr > td:last-child, .applybs .panel > .table-responsive > .table-bordered > tbody > tr > th:last-child, .applybs .panel > .table-responsive > .table-bordered > tfoot > tr > td:last-child, .applybs .panel > .table-responsive > .table-bordered > tfoot > tr > th:last-child, .applybs .panel > .table-responsive > .table-bordered > thead > tr > td:last-child, .applybs .panel > .table-responsive > .table-bordered > thead > tr > th:last-child {
  border-right: 0;
}
.applybs .panel > .table-bordered > tbody > tr:first-child > td, .applybs .panel > .table-bordered > tbody > tr:first-child > th, .applybs .panel > .table-bordered > thead > tr:first-child > td, .applybs .panel > .table-bordered > thead > tr:first-child > th, .applybs .panel > .table-responsive > .table-bordered > tbody > tr:first-child > td, .applybs .panel > .table-responsive > .table-bordered > tbody > tr:first-child > th, .applybs .panel > .table-responsive > .table-bordered > thead > tr:first-child > td, .applybs .panel > .table-responsive > .table-bordered > thead > tr:first-child > th {
  border-bottom: 0;
}
.applybs .panel > .table-bordered > tbody > tr:last-child > td, .applybs .panel > .table-bordered > tbody > tr:last-child > th, .applybs .panel > .table-bordered > tfoot > tr:last-child > td, .applybs .panel > .table-bordered > tfoot > tr:last-child > th, .applybs .panel > .table-responsive > .table-bordered > tbody > tr:last-child > td, .applybs .panel > .table-responsive > .table-bordered > tbody > tr:last-child > th, .applybs .panel > .table-responsive > .table-bordered > tfoot > tr:last-child > td, .applybs .panel > .table-responsive > .table-bordered > tfoot > tr:last-child > th {
  border-bottom: 0;
}
.applybs .panel > .table-responsive {
  margin-bottom: 0;
  border: 0;
}
.applybs .panel-group {
  margin-bottom: 20px;
}
.applybs .panel-group .panel {
  margin-bottom: 0;
  border-radius: 4px;
}
.applybs .panel-group .panel + .panel {
  margin-top: 5px;
}
.applybs .panel-group .panel-heading {
  border-bottom: 0;
}
.applybs .panel-group .panel-heading + .panel-collapse > .list-group, .applybs .panel-group .panel-heading + .panel-collapse > .panel-body {
  border-top: 1px solid #ddd;
}
.applybs .panel-group .panel-footer {
  border-top: 0;
}
.applybs .panel-group .panel-footer + .panel-collapse .panel-body {
  border-bottom: 1px solid #ddd;
}
.applybs .panel-default {
  border-color: #ddd;
}
.applybs .panel-default > .panel-heading {
  color: #333;
  background-color: #f5f5f5;
  border-color: #ddd;
}
.applybs .panel-default > .panel-heading + .panel-collapse > .panel-body {
  border-top-color: #ddd;
}
.applybs .panel-default > .panel-heading .badge {
  color: #f5f5f5;
  background-color: #333;
}
.applybs .panel-default > .panel-footer + .panel-collapse > .panel-body {
  border-bottom-color: #ddd;
}
.applybs .panel-primary {
  border-color: #337ab7;
}
.applybs .panel-primary > .panel-heading {
  color: #fff;
  background-color: #337ab7;
  border-color: #337ab7;
}
.applybs .panel-primary > .panel-heading + .panel-collapse > .panel-body {
  border-top-color: #337ab7;
}
.applybs .panel-primary > .panel-heading .badge {
  color: #337ab7;
  background-color: #fff;
}
.applybs .panel-primary > .panel-footer + .panel-collapse > .panel-body {
  border-bottom-color: #337ab7;
}
.applybs .panel-success {
  border-color: #d6e9c6;
}
.applybs .panel-success > .panel-heading {
  color: #3c763d;
  background-color: #dff0d8;
  border-color: #d6e9c6;
}
.applybs .panel-success > .panel-heading + .panel-collapse > .panel-body {
  border-top-color: #d6e9c6;
}
.applybs .panel-success > .panel-heading .badge {
  color: #dff0d8;
  background-color: #3c763d;
}
.applybs .panel-success > .panel-footer + .panel-collapse > .panel-body {
  border-bottom-color: #d6e9c6;
}
.applybs .panel-info {
  border-color: #bce8f1;
}
.applybs .panel-info > .panel-heading {
  color: #31708f;
  background-color: #d9edf7;
  border-color: #bce8f1;
}
.applybs .panel-info > .panel-heading + .panel-collapse > .panel-body {
  border-top-color: #bce8f1;
}
.applybs .panel-info > .panel-heading .badge {
  color: #d9edf7;
  background-color: #31708f;
}
.applybs .panel-info > .panel-footer + .panel-collapse > .panel-body {
  border-bottom-color: #bce8f1;
}
.applybs .panel-warning {
  border-color: #faebcc;
}
.applybs .panel-warning > .panel-heading {
  color: #8a6d3b;
  background-color: #fcf8e3;
  border-color: #faebcc;
}
.applybs .panel-warning > .panel-heading + .panel-collapse > .panel-body {
  border-top-color: #faebcc;
}
.applybs .panel-warning > .panel-heading .badge {
  color: #fcf8e3;
  background-color: #8a6d3b;
}
.applybs .panel-warning > .panel-footer + .panel-collapse > .panel-body {
  border-bottom-color: #faebcc;
}
.applybs .panel-danger {
  border-color: #ebccd1;
}
.applybs .panel-danger > .panel-heading {
  color: #a94442;
  background-color: #f2dede;
  border-color: #ebccd1;
}
.applybs .panel-danger > .panel-heading + .panel-collapse > .panel-body {
  border-top-color: #ebccd1;
}
.applybs .panel-danger > .panel-heading .badge {
  color: #f2dede;
  background-color: #a94442;
}
.applybs .panel-danger > .panel-footer + .panel-collapse > .panel-body {
  border-bottom-color: #ebccd1;
}
.applybs .embed-responsive {
  position: relative;
  display: block;
  height: 0;
  padding: 0;
  overflow: hidden;
}
.applybs .embed-responsive .embed-responsive-item, .applybs .embed-responsive embed, .applybs .embed-responsive iframe, .applybs .embed-responsive object, .applybs .embed-responsive video {
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 100%;
  border: 0;
}
.applybs .embed-responsive-16by9 {
  padding-bottom: 56.25%;
}
.applybs .embed-responsive-4by3 {
  padding-bottom: 75%;
}
.applybs .well {
  min-height: 20px;
  padding: 19px;
  margin-bottom: 20px;
  background-color: #f5f5f5;
  border: 1px solid #e3e3e3;
  border-radius: 4px;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);
  box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.05);
}
.applybs .well blockquote {
  border-color: #ddd;
  border-color: rgba(0, 0, 0, 0.15);
}
.applybs .well-lg {
  padding: 24px;
  border-radius: 6px;
}
.applybs .well-sm {
  padding: 9px;
  border-radius: 3px;
}
.applybs .close {
  float: right;
  font-size: 21px;
  font-weight: 700;
  line-height: 1;
  color: #000;
  text-shadow: 0 1px 0 #fff;
  filter: alpha(opacity=20);
  opacity: .2;
}
.applybs .close:focus, .applybs .close:hover {
  color: #000;
  text-decoration: none;
  cursor: pointer;
  filter: alpha(opacity=50);
  opacity: .5;
}
.applybs button.close {
  -webkit-appearance: none;
  padding: 0;
  cursor: pointer;
  background: 0 0;
  border: 0;
}
.applybs .modal-open {
  overflow: hidden;
}
.applybs .modal {
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  z-index: 1050;
  display: none;
  overflow: hidden;
  -webkit-overflow-scrolling: touch;
  outline: 0;
}
.applybs .modal.fade .modal-dialog {
  -webkit-transition: -webkit-transform .3s ease-out;
  -o-transition: -o-transform .3s ease-out;
  transition: transform .3s ease-out;
  -webkit-transform: translate(0, -25%);
  -ms-transform: translate(0, -25%);
  -o-transform: translate(0, -25%);
  transform: translate(0, -25%);
}
.applybs .modal.in .modal-dialog {
  -webkit-transform: translate(0, 0);
  -ms-transform: translate(0, 0);
  -o-transform: translate(0, 0);
  transform: translate(0, 0);
}
.applybs .modal-open .modal {
  overflow-x: hidden;
  overflow-y: auto;
}
.applybs .modal-dialog {
  position: relative;
  width: auto;
  margin: 10px;
}
.applybs .modal-content {
  position: relative;
  background-color: #fff;
  -webkit-background-clip: padding-box;
  background-clip: padding-box;
  border: 1px solid #999;
  border: 1px solid rgba(0, 0, 0, 0.2);
  border-radius: 6px;
  outline: 0;
  -webkit-box-shadow: 0 3px 9px rgba(0, 0, 0, 0.5);
  box-shadow: 0 3px 9px rgba(0, 0, 0, 0.5);
}
.applybs .modal-backdrop {
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  z-index: 1040;
  background-color: #000;
}
.applybs .modal-backdrop.fade {
  filter: alpha(opacity=0);
  opacity: 0;
}
.applybs .modal-backdrop.in {
  filter: alpha(opacity=50);
  opacity: .5;
}
.applybs .modal-header {
  padding: 15px;
  border-bottom: 1px solid #e5e5e5;
}
.applybs .modal-header .close {
  margin-top: -2px;
}
.applybs .modal-title {
  margin: 0;
  line-height: 1.42857143;
}
.applybs .modal-body {
  position: relative;
  padding: 15px;
}
.applybs .modal-footer {
  padding: 15px;
  text-align: right;
  border-top: 1px solid #e5e5e5;
}
.applybs .modal-footer .btn + .btn {
  margin-bottom: 0;
  margin-left: 5px;
}
.applybs .modal-footer .btn-group .btn + .btn {
  margin-left: -1px;
}
.applybs .modal-footer .btn-block + .btn-block {
  margin-left: 0;
}
.applybs .modal-scrollbar-measure {
  position: absolute;
  top: -9999px;
  width: 50px;
  height: 50px;
  overflow: scroll;
}
@media (min-width: 768px) {
  .applybs .modal-dialog {
    width: 600px;
    margin: 30px auto;
  }
  .applybs .modal-content {
    -webkit-box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
  }
  .applybs .modal-sm {
    width: 300px;
  }
}
@media (min-width: 992px) {
  .applybs .modal-lg {
    width: 900px;
  }
}
.applybs .tooltip {
  position: absolute;
  z-index: 1070;
  display: block;
  font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
  font-size: 12px;
  font-style: normal;
  font-weight: 400;
  line-height: 1.42857143;
  text-align: left;
  text-align: start;
  text-decoration: none;
  text-shadow: none;
  text-transform: none;
  letter-spacing: normal;
  word-break: normal;
  word-spacing: normal;
  word-wrap: normal;
  white-space: normal;
  filter: alpha(opacity=0);
  opacity: 0;
  line-break: auto;
}
.applybs .tooltip.in {
  filter: alpha(opacity=90);
  opacity: .9;
}
.applybs .tooltip.top {
  padding: 5px 0;
  margin-top: -3px;
}
.applybs .tooltip.right {
  padding: 0 5px;
  margin-left: 3px;
}
.applybs .tooltip.bottom {
  padding: 5px 0;
  margin-top: 3px;
}
.applybs .tooltip.left {
  padding: 0 5px;
  margin-left: -3px;
}
.applybs .tooltip-inner {
  max-width: 200px;
  padding: 3px 8px;
  color: #fff;
  text-align: center;
  background-color: #000;
  border-radius: 4px;
}
.applybs .tooltip-arrow {
  position: absolute;
  width: 0;
  height: 0;
  border-color: transparent;
  border-style: solid;
}
.applybs .tooltip.top .tooltip-arrow {
  bottom: 0;
  left: 50%;
  margin-left: -5px;
  border-width: 5px 5px 0;
  border-top-color: #000;
}
.applybs .tooltip.top-left .tooltip-arrow {
  right: 5px;
  bottom: 0;
  margin-bottom: -5px;
  border-width: 5px 5px 0;
  border-top-color: #000;
}
.applybs .tooltip.top-right .tooltip-arrow {
  bottom: 0;
  left: 5px;
  margin-bottom: -5px;
  border-width: 5px 5px 0;
  border-top-color: #000;
}
.applybs .tooltip.right .tooltip-arrow {
  top: 50%;
  left: 0;
  margin-top: -5px;
  border-width: 5px 5px 5px 0;
  border-right-color: #000;
}
.applybs .tooltip.left .tooltip-arrow {
  top: 50%;
  right: 0;
  margin-top: -5px;
  border-width: 5px 0 5px 5px;
  border-left-color: #000;
}
.applybs .tooltip.bottom .tooltip-arrow {
  top: 0;
  left: 50%;
  margin-left: -5px;
  border-width: 0 5px 5px;
  border-bottom-color: #000;
}
.applybs .tooltip.bottom-left .tooltip-arrow {
  top: 0;
  right: 5px;
  margin-top: -5px;
  border-width: 0 5px 5px;
  border-bottom-color: #000;
}
.applybs .tooltip.bottom-right .tooltip-arrow {
  top: 0;
  left: 5px;
  margin-top: -5px;
  border-width: 0 5px 5px;
  border-bottom-color: #000;
}
.applybs .popover {
  position: absolute;
  top: 0;
  left: 0;
  z-index: 1060;
  display: none;
  max-width: 276px;
  padding: 1px;
  font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
  font-size: 14px;
  font-style: normal;
  font-weight: 400;
  line-height: 1.42857143;
  text-align: left;
  text-align: start;
  text-decoration: none;
  text-shadow: none;
  text-transform: none;
  letter-spacing: normal;
  word-break: normal;
  word-spacing: normal;
  word-wrap: normal;
  white-space: normal;
  background-color: #fff;
  -webkit-background-clip: padding-box;
  background-clip: padding-box;
  border: 1px solid #ccc;
  border: 1px solid rgba(0, 0, 0, 0.2);
  border-radius: 6px;
  -webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
  line-break: auto;
}
.applybs .popover.top {
  margin-top: -10px;
}
.applybs .popover.right {
  margin-left: 10px;
}
.applybs .popover.bottom {
  margin-top: 10px;
}
.applybs .popover.left {
  margin-left: -10px;
}
.applybs .popover-title {
  padding: 8px 14px;
  margin: 0;
  font-size: 14px;
  background-color: #f7f7f7;
  border-bottom: 1px solid #ebebeb;
  border-radius: 5px 5px 0 0;
}
.applybs .popover-content {
  padding: 9px 14px;
}
.applybs .popover > .arrow, .applybs .popover > .arrow:after {
  position: absolute;
  display: block;
  width: 0;
  height: 0;
  border-color: transparent;
  border-style: solid;
}
.applybs .popover > .arrow {
  border-width: 11px;
}
.applybs .popover > .arrow:after {
  content: "";
  border-width: 10px;
}
.applybs .popover.top > .arrow {
  bottom: -11px;
  left: 50%;
  margin-left: -11px;
  border-top-color: #999;
  border-top-color: rgba(0, 0, 0, 0.25);
  border-bottom-width: 0;
}
.applybs .popover.top > .arrow:after {
  bottom: 1px;
  margin-left: -10px;
  content: " ";
  border-top-color: #fff;
  border-bottom-width: 0;
}
.applybs .popover.right > .arrow {
  top: 50%;
  left: -11px;
  margin-top: -11px;
  border-right-color: #999;
  border-right-color: rgba(0, 0, 0, 0.25);
  border-left-width: 0;
}
.applybs .popover.right > .arrow:after {
  bottom: -10px;
  left: 1px;
  content: " ";
  border-right-color: #fff;
  border-left-width: 0;
}
.applybs .popover.bottom > .arrow {
  top: -11px;
  left: 50%;
  margin-left: -11px;
  border-top-width: 0;
  border-bottom-color: #999;
  border-bottom-color: rgba(0, 0, 0, 0.25);
}
.applybs .popover.bottom > .arrow:after {
  top: 1px;
  margin-left: -10px;
  content: " ";
  border-top-width: 0;
  border-bottom-color: #fff;
}
.applybs .popover.left > .arrow {
  top: 50%;
  right: -11px;
  margin-top: -11px;
  border-right-width: 0;
  border-left-color: #999;
  border-left-color: rgba(0, 0, 0, 0.25);
}
.applybs .popover.left > .arrow:after {
  right: 1px;
  bottom: -10px;
  content: " ";
  border-right-width: 0;
  border-left-color: #fff;
}
.applybs .carousel {
  position: relative;
}
.applybs .carousel-inner {
  position: relative;
  width: 100%;
  overflow: hidden;
}
.applybs .carousel-inner > .item {
  position: relative;
  display: none;
  -webkit-transition: .6s ease-in-out left;
  -o-transition: .6s ease-in-out left;
  transition: .6s ease-in-out left;
}
.applybs .carousel-inner > .item > a > img, .applybs .carousel-inner > .item > img {
  line-height: 1;
}
@media all and (transform-3d), (-webkit-transform-3d) {
  .applybs .carousel-inner > .item {
    -webkit-transition: -webkit-transform .6s ease-in-out;
    -o-transition: -o-transform .6s ease-in-out;
    transition: transform .6s ease-in-out;
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
    -webkit-perspective: 1000px;
    perspective: 1000px;
  }
  .applybs .carousel-inner > .item.active.right, .applybs .carousel-inner > .item.next {
    left: 0;
    -webkit-transform: translate3d(100%, 0, 0);
    transform: translate3d(100%, 0, 0);
  }
  .applybs .carousel-inner > .item.active.left, .applybs .carousel-inner > .item.prev {
    left: 0;
    -webkit-transform: translate3d(-100%, 0, 0);
    transform: translate3d(-100%, 0, 0);
  }
  .applybs .carousel-inner > .item.active, .applybs .carousel-inner > .item.next.left, .applybs .carousel-inner > .item.prev.right {
    left: 0;
    -webkit-transform: translate3d(0, 0, 0);
    transform: translate3d(0, 0, 0);
  }
}
.applybs .carousel-inner > .active, .applybs .carousel-inner > .next, .applybs .carousel-inner > .prev {
  display: block;
}
.applybs .carousel-inner > .active {
  left: 0;
}
.applybs .carousel-inner > .next, .applybs .carousel-inner > .prev {
  position: absolute;
  top: 0;
  width: 100%;
}
.applybs .carousel-inner > .next {
  left: 100%;
}
.applybs .carousel-inner > .prev {
  left: -100%;
}
.applybs .carousel-inner > .next.left, .applybs .carousel-inner > .prev.right {
  left: 0;
}
.applybs .carousel-inner > .active.left {
  left: -100%;
}
.applybs .carousel-inner > .active.right {
  left: 100%;
}
.applybs .carousel-control {
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  width: 15%;
  font-size: 20px;
  color: #fff;
  text-align: center;
  text-shadow: 0 1px 2px rgba(0, 0, 0, 0.6);
  background-color: transparent;
  filter: alpha(opacity=50);
  opacity: .5;
}
.applybs .carousel-control.left {
  background-image: -webkit-linear-gradient(left, rgba(0, 0, 0, 0.5) 0, rgba(0, 0, 0, 0.0001) 100%);
  background-image: -o-linear-gradient(left, rgba(0, 0, 0, 0.5) 0, rgba(0, 0, 0, 0.0001) 100%);
  background-image: -webkit-gradient(linear, left top, right top, from(rgba(0, 0, 0, 0.5)), to(rgba(0, 0, 0, 0.0001)));
  background-image: linear-gradient(to right, rgba(0, 0, 0, 0.5) 0, rgba(0, 0, 0, 0.0001) 100%);
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#80000000', endColorstr='#00000000', GradientType=1);
  background-repeat: repeat-x;
}
.applybs .carousel-control.right {
  right: 0;
  left: auto;
  background-image: -webkit-linear-gradient(left, rgba(0, 0, 0, 0.0001) 0, rgba(0, 0, 0, 0.5) 100%);
  background-image: -o-linear-gradient(left, rgba(0, 0, 0, 0.0001) 0, rgba(0, 0, 0, 0.5) 100%);
  background-image: -webkit-gradient(linear, left top, right top, from(rgba(0, 0, 0, 0.0001)), to(rgba(0, 0, 0, 0.5)));
  background-image: linear-gradient(to right, rgba(0, 0, 0, 0.0001) 0, rgba(0, 0, 0, 0.5) 100%);
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#00000000', endColorstr='#80000000', GradientType=1);
  background-repeat: repeat-x;
}
.applybs .carousel-control:focus, .applybs .carousel-control:hover {
  color: #fff;
  text-decoration: none;
  filter: alpha(opacity=90);
  outline: 0;
  opacity: .9;
}
.applybs .carousel-control .glyphicon-chevron-left, .applybs .carousel-control .glyphicon-chevron-right, .applybs .carousel-control .icon-next, .applybs .carousel-control .icon-prev {
  position: absolute;
  top: 50%;
  z-index: 5;
  display: inline-block;
  margin-top: -10px;
}
.applybs .carousel-control .glyphicon-chevron-left, .applybs .carousel-control .icon-prev {
  left: 50%;
  margin-left: -10px;
}
.applybs .carousel-control .glyphicon-chevron-right, .applybs .carousel-control .icon-next {
  right: 50%;
  margin-right: -10px;
}
.applybs .carousel-control .icon-next, .applybs .carousel-control .icon-prev {
  width: 20px;
  height: 20px;
  font-family: serif;
  line-height: 1;
}
.applybs .carousel-control .icon-prev:before {
  content: '\2039';
}
.applybs .carousel-control .icon-next:before {
  content: '\203a';
}
.applybs .carousel-indicators {
  position: absolute;
  bottom: 10px;
  left: 50%;
  z-index: 15;
  width: 60%;
  padding-left: 0;
  margin-left: -30%;
  text-align: center;
  list-style: none;
}
.applybs .carousel-indicators li {
  display: inline-block;
  width: 10px;
  height: 10px;
  margin: 1px;
  text-indent: -999px;
  cursor: pointer;
  background-color: #000 \9;
  background-color: transparent;
  border: 1px solid #fff;
  border-radius: 10px;
}
.applybs .carousel-indicators .active {
  width: 12px;
  height: 12px;
  margin: 0;
  background-color: #fff;
}
.applybs .carousel-caption {
  position: absolute;
  right: 15%;
  bottom: 20px;
  left: 15%;
  z-index: 10;
  padding-top: 20px;
  padding-bottom: 20px;
  color: #fff;
  text-align: center;
  text-shadow: 0 1px 2px rgba(0, 0, 0, 0.6);
}
.applybs .carousel-caption .btn {
  text-shadow: none;
}
@media screen and (min-width: 768px) {
  .applybs .carousel-control .glyphicon-chevron-left, .applybs .carousel-control .glyphicon-chevron-right, .applybs .carousel-control .icon-next, .applybs .carousel-control .icon-prev {
    width: 30px;
    height: 30px;
    margin-top: -10px;
    font-size: 30px;
  }
  .applybs .carousel-control .glyphicon-chevron-left, .applybs .carousel-control .icon-prev {
    margin-left: -10px;
  }
  .applybs .carousel-control .glyphicon-chevron-right, .applybs .carousel-control .icon-next {
    margin-right: -10px;
  }
  .applybs .carousel-caption {
    right: 20%;
    left: 20%;
    padding-bottom: 30px;
  }
  .applybs .carousel-indicators {
    bottom: 20px;
  }
}
.applybs .btn-group-vertical > .btn-group:after, .applybs .btn-group-vertical > .btn-group:before, .applybs .btn-toolbar:after, .applybs .btn-toolbar:before, .applybs .clearfix:after, .applybs .clearfix:before, .applybs .container-fluid:after, .applybs .container-fluid:before, .applybs .container:after, .applybs .container:before, .applybs .dl-horizontal dd:after, .applybs .dl-horizontal dd:before, .applybs .form-horizontal .form-group:after, .applybs .form-horizontal .form-group:before, .applybs .modal-footer:after, .applybs .modal-footer:before, .applybs .modal-header:after, .applybs .modal-header:before, .applybs .nav:after, .applybs .nav:before, .applybs .navbar-collapse:after, .applybs .navbar-collapse:before, .applybs .navbar-header:after, .applybs .navbar-header:before, .applybs .navbar:after, .applybs .navbar:before, .applybs .pager:after, .applybs .pager:before, .applybs .panel-body:after, .applybs .panel-body:before, .applybs .row:after, .applybs .row:before {
  display: table;
  content: " ";
}
.applybs .btn-group-vertical > .btn-group:after, .applybs .btn-toolbar:after, .applybs .clearfix:after, .applybs .container-fluid:after, .applybs .container:after, .applybs .dl-horizontal dd:after, .applybs .form-horizontal .form-group:after, .applybs .modal-footer:after, .applybs .modal-header:after, .applybs .nav:after, .applybs .navbar-collapse:after, .applybs .navbar-header:after, .applybs .navbar:after, .applybs .pager:after, .applybs .panel-body:after, .applybs .row:after {
  clear: both;
}
.applybs .center-block {
  display: block;
  margin-right: auto;
  margin-left: auto;
}
.applybs .pull-right {
  float: right !important;
}
.applybs .pull-left {
  float: left !important;
}
.applybs .hide {
  display: none !important;
}
.applybs .show {
  display: block !important;
}
.applybs .invisible {
  visibility: hidden;
}
.applybs .text-hide {
  font: 0/0 a;
  color: transparent;
  text-shadow: none;
  background-color: transparent;
  border: 0;
}
.applybs .hidden {
  display: none !important;
}
.applybs .affix {
  position: fixed;
}
@-ms-viewport {
  .applybs {
    width: device-width;
  }
}
.applybs .visible-lg, .applybs .visible-md, .applybs .visible-sm, .applybs .visible-xs {
  display: none !important;
}
.applybs .visible-lg-block, .applybs .visible-lg-inline, .applybs .visible-lg-inline-block, .applybs .visible-md-block, .applybs .visible-md-inline, .applybs .visible-md-inline-block, .applybs .visible-sm-block, .applybs .visible-sm-inline, .applybs .visible-sm-inline-block, .applybs .visible-xs-block, .applybs .visible-xs-inline, .applybs .visible-xs-inline-block {
  display: none !important;
}
@media (max-width: 767px) {
  .applybs .visible-xs {
    display: block !important;
  }
  .applybs table.visible-xs {
    display: table !important;
  }
  .applybs tr.visible-xs {
    display: table-row !important;
  }
  .applybs td.visible-xs, .applybs th.visible-xs {
    display: table-cell !important;
  }
}
@media (max-width: 767px) {
  .applybs .visible-xs-block {
    display: block !important;
  }
}
@media (max-width: 767px) {
  .applybs .visible-xs-inline {
    display: inline !important;
  }
}
@media (max-width: 767px) {
  .applybs .visible-xs-inline-block {
    display: inline-block !important;
  }
}
@media (min-width: 768px) and (max-width: 991px) {
  .applybs .visible-sm {
    display: block !important;
  }
  .applybs table.visible-sm {
    display: table !important;
  }
  .applybs tr.visible-sm {
    display: table-row !important;
  }
  .applybs td.visible-sm, .applybs th.visible-sm {
    display: table-cell !important;
  }
}
@media (min-width: 768px) and (max-width: 991px) {
  .applybs .visible-sm-block {
    display: block !important;
  }
}
@media (min-width: 768px) and (max-width: 991px) {
  .applybs .visible-sm-inline {
    display: inline !important;
  }
}
@media (min-width: 768px) and (max-width: 991px) {
  .applybs .visible-sm-inline-block {
    display: inline-block !important;
  }
}
@media (min-width: 992px) and (max-width: 1199px) {
  .applybs .visible-md {
    display: block !important;
  }
  .applybs table.visible-md {
    display: table !important;
  }
  .applybs tr.visible-md {
    display: table-row !important;
  }
  .applybs td.visible-md, .applybs th.visible-md {
    display: table-cell !important;
  }
}
@media (min-width: 992px) and (max-width: 1199px) {
  .applybs .visible-md-block {
    display: block !important;
  }
}
@media (min-width: 992px) and (max-width: 1199px) {
  .applybs .visible-md-inline {
    display: inline !important;
  }
}
@media (min-width: 992px) and (max-width: 1199px) {
  .applybs .visible-md-inline-block {
    display: inline-block !important;
  }
}
@media (min-width: 1200px) {
  .applybs .visible-lg {
    display: block !important;
  }
  .applybs table.visible-lg {
    display: table !important;
  }
  .applybs tr.visible-lg {
    display: table-row !important;
  }
  .applybs td.visible-lg, .applybs th.visible-lg {
    display: table-cell !important;
  }
}
@media (min-width: 1200px) {
  .applybs .visible-lg-block {
    display: block !important;
  }
}
@media (min-width: 1200px) {
  .applybs .visible-lg-inline {
    display: inline !important;
  }
}
@media (min-width: 1200px) {
  .applybs .visible-lg-inline-block {
    display: inline-block !important;
  }
}
@media (max-width: 767px) {
  .applybs .hidden-xs {
    display: none !important;
  }
}
@media (min-width: 768px) and (max-width: 991px) {
  .applybs .hidden-sm {
    display: none !important;
  }
}
@media (min-width: 992px) and (max-width: 1199px) {
  .applybs .hidden-md {
    display: none !important;
  }
}
@media (min-width: 1200px) {
  .applybs .hidden-lg {
    display: none !important;
  }
}
.applybs .visible-print {
  display: none !important;
}
@media print {
  .applybs .visible-print {
    display: block !important;
  }
  .applybs table.visible-print {
    display: table !important;
  }
  .applybs tr.visible-print {
    display: table-row !important;
  }
  .applybs td.visible-print, .applybs th.visible-print {
    display: table-cell !important;
  }
}
.applybs .visible-print-block {
  display: none !important;
}
@media print {
  .applybs .visible-print-block {
    display: block !important;
  }
}
.applybs .visible-print-inline {
  display: none !important;
}
@media print {
  .applybs .visible-print-inline {
    display: inline !important;
  }
}
.applybs .visible-print-inline-block {
  display: none !important;
}
@media print {
  .applybs .visible-print-inline-block {
    display: inline-block !important;
  }
}
@media print {
  .applybs .hidden-print {
    display: none !important;
  }
}

</style>

<style>

/*animations*/

/******************
* Bounce in right *
*******************/


.animated {
    -webkit-animation-duration: 1s;
    animation-duration: 1s;
    -webkit-animation-fill-mode: both;
    animation-fill-mode: both;
}
.slow{
     -webkit-animation-duration: 1.5s;
    animation-duration: 1.5s;
    -webkit-animation-fill-mode: both;
    animation-fill-mode: both;
}
.slower{
     -webkit-animation-duration: 2s;
    animation-duration: 2s;
    -webkit-animation-fill-mode: both;
    animation-fill-mode: both;
}
.slowest{
     -webkit-animation-duration: 3s;
    animation-duration: 3s;
    -webkit-animation-fill-mode: both;
    animation-fill-mode: both;
}

/* Added by Andy Meetan */
.delay-250 {
    -webkit-animation-delay:0.25s;
    -moz-animation-delay:0.25s;
    -o-animation-delay:0.25s;
    animation-delay:0.25s;
}
.delay-500 {
    -webkit-animation-delay:0.5s;
    -moz-animation-delay:0.5s;
    -o-animation-delay:0.5s;
    animation-delay:0.5s;
}
.delay-750 {
    -webkit-animation-delay:0.75s;
    -moz-animation-delay:0.75s;
    -o-animation-delay:0.75s;
    animation-delay:0.75s;
}
.delay-1000 {
    -webkit-animation-delay:1.0s;
    -moz-animation-delay:1.0s;
    -o-animation-delay:1.0s;
    animation-delay:1.0s;
}
.delay-1250 {
    -webkit-animation-delay:1.25s;
    -moz-animation-delay:1.25s;
    -o-animation-delay:1.25s;
    animation-delay:1.25s;
}
.delay-1500 {
    -webkit-animation-delay:1.5s;
    -moz-animation-delay:1.5s;
    -o-animation-delay:1.5s;
    animation-delay:1.5s;
}
.delay-1750 {
    -webkit-animation-delay:1.75s;
    -moz-animation-delay:1.75s;
    -o-animation-delay:1.75s;
    animation-delay:1.75s;
}
.delay-2000 {
    -webkit-animation-delay:2.0s;
    -moz-animation-delay:2.0s;
    -o-animation-delay:2.0s;
    animation-delay:2.0s;
}
.delay-2500 {
    -webkit-animation-delay:2.5s;
    -moz-animation-delay:2.5s;
    -o-animation-delay:2.5s;
    animation-delay:2.5s;
}
.delay-2000 {
    -webkit-animation-delay:2.0s;
    -moz-animation-delay:2.0s;
    -o-animation-delay:2.0s;
    animation-delay:2.0s;
}
.delay-2500 {
    -webkit-animation-delay:2.5s;
    -moz-animation-delay:2.5s;
    -o-animation-delay:2.5s;
    animation-delay:2.5s;
}
.delay-3000 {
    -webkit-animation-delay:3.0s;
    -moz-animation-delay:3.0s;
    -o-animation-delay:3.0s;
    animation-delay:3.0s;
}
.delay-3500 {
    -webkit-animation-delay:3.5s;
    -moz-animation-delay:3.5s;
    -o-animation-delay:3.5s;
    animation-delay:3.5s;
}

.bounceInRight, .bounceInLeft, .bounceInUp, .bounceInDown{
    opacity:0;
    -webkit-transform: translateX(400px);
    transform: translateX(400px);
}
.fadeInRight, .fadeInLeft, .fadeInUp, .fadeInDown{
    opacity:0;
    -webkit-transform: translateX(400px);
    transform: translateX(400px);
}

.flipInX, .flipInY, .rotateIn, .rotateInUpLeft, .rotateInUpRight, .rotateInDownLeft, .rotateDownUpRight, .rollIn{
    opacity:0;
}

.lightSpeedInRight, .lightSpeedInLeft{
    opacity:0;
    -webkit-transform: translateX(400px);
    transform: translateX(400px);
}

/***********
* bounceIn *
************/
@-webkit-keyframes bounceIn {
    0% {
        opacity: 0;
        -webkit-transform: scale(.3);
    }

    50% {
        opacity: 1;
        -webkit-transform: scale(1.05);
    }

    70% {
        -webkit-transform: scale(.9);
    }

    100% {
         -webkit-transform: scale(1);
    }
}

@keyframes bounceIn {
    0% {
        opacity: 0;
        transform: scale(.3);
    }

    50% {
        opacity: 1;
        transform: scale(1.05);
    }

    70% {
        transform: scale(.9);
    }

    100% {
        transform: scale(1);
    }
}

.bounceIn.go {
    -webkit-animation-name: bounceIn;
    animation-name: bounceIn;
}

/****************
* bounceInRight *
****************/

@-webkit-keyframes bounceInRight {
    0% {
        opacity: 0;

        -webkit-transform: translateX(400px);
    }
    60% {

        -webkit-transform: translateX(-30px);
    }
    80% {
        -webkit-transform: translateX(10px);
    }
    100% {
    opacity: 1;

        -webkit-transform: translateX(0);
    }
}

@keyframes bounceInRight {
    0% {
        opacity: 0;

        transform: translateX(400px);
    }
    60% {

        transform: translateX(-30px);
    }
    80% {
        transform: translateX(10px);
    }
    100% {
    opacity: 1;

        transform: translateX(0);
    }
}


.bounceInRight.go {
    -webkit-animation-name: bounceInRight;
    animation-name: bounceInRight;
}

/******************
* Bounce in left *
*******************/

@-webkit-keyframes bounceInLeft {
    0% {
        opacity: 0;

        -webkit-transform: translateX(-400px);
    }
    60% {

        -webkit-transform: translateX(30px);
    }
    80% {
        -webkit-transform: translateX(-10px);
    }
    100% {
        opacity: 1;

        -webkit-transform: translateX(0);
    }
}

@keyframes bounceInLeft {
    0% {
        opacity: 0;

        transform: translateX(-400px);
    }
    60% {

        transform: translateX(30px);
    }
    80% {
        transform: translateX(-10px);
    }
    100% {
        opacity: 1;

        transform: translateX(0);
    }
}

.bounceInLeft.go {
    -webkit-animation-name: bounceInLeft;
    animation-name: bounceInLeft;
}

/******************
* Bounce in up *
*******************/

@-webkit-keyframes bounceInUp {
    0% {
        opacity: 0;

        -webkit-transform: translateY(400px);
    }
    60% {

        -webkit-transform: translateY(-30px);
    }
    80% {
        -webkit-transform: translateY(10px);
    }
    100% {
        opacity: 1;

        -webkit-transform: translateY(0);
    }
}

@keyframes bounceInUp {
    0% {
        opacity: 0;

        transform: translateY(400px);
    }
    60% {

        transform: translateY(-30px);
    }
    80% {
        transform: translateY(10px);
    }
    100% {
        opacity: 1;

        transform: translateY(0);
    }
}

.bounceInUp.go {
    -webkit-animation-name: bounceInUp;
    animation-name: bounceInUp;
}


/******************
* Bounce in down *
*******************/

@-webkit-keyframes bounceInDown {
    0% {
        opacity: 0;

        -webkit-transform: translateY(-400px);
    }
    60% {

        -webkit-transform: translateY(30px);
    }
    80% {
        -webkit-transform: translateY(-10px);
    }
    100% {
        opacity: 1;

        -webkit-transform: translateY(0);
    }
}

@keyframes bounceInDown {
    0% {
        opacity: 0;

        transform: translateY(-400px);
    }
    60% {

        transform: translateY(30px);
    }
    80% {
        transform: translateY(-10px);
    }
    100% {
        opacity: 1;

        transform: translateY(0);
    }
}

.bounceInDown.go {
    -webkit-animation-name: bounceInDown;
    animation-name: bounceInDown;
}


/**********
* Fade In *
**********/
@-webkit-keyframes fadeIn {
    0% {opacity: 0;}
    100% {opacity: 1;
        display:block;}
}
@keyframes fadeIn {
    0% {opacity: 0;}
    100% {opacity: 1;
        display:block;}
}
.fadeIn{
    opacity:0;
}
.fadeIn.go {
    -webkit-animation-name: fadeIn;
    animation-name: fadeIn;
}

/**********
* Grow in *
***********/

@-webkit-keyframes growIn {
    0% {
        -webkit-transform: scale(0.2);
        opacity:0;
    }
    50% {
        -webkit-transform: scale(1.2);

    }
    100% {
        -webkit-transform: scale(1);
        opacity:1;
    }
}
@keyframes growIn {
    0% {
        transform: scale(0.2);
        opacity:0;
    }
    50% {
        transform: scale(1.2);

    }
    100% {
        transform: scale(1);
        opacity:1;
    }
}
.growIn {

    -webkit-transform: scale(0.2);
    transform: scale(0.2);
    opacity:0;
}
.growIn.go{
    -webkit-animation-name: growIn;
    animation-name: growIn;
}

/********
* Shake *
********/
@-webkit-keyframes shake {
    0%, 100% {-webkit-transform: translateX(0);}
    10%, 30%, 50%, 70%, 90% {-webkit-transform: translateX(-10px);}
    20%, 40%, 60%, 80% {-webkit-transform: translateX(10px);}
}
@keyframes shake {
    0%, 100% {transform: translateX(0);}
    10%, 30%, 50%, 70%, 90% {transform: translateX(-10px);}
    20%, 40%, 60%, 80% {transform: translateX(10px);}
}
.shake.go {
    -webkit-animation-name: shake;
    animation-name: shake;
}

/********
* ShakeUp *
********/
@-webkit-keyframes shakeUp {
    0%, 100% {-webkit-transform: translateY(0);}
    10%, 30%, 50%, 70%, 90% {-webkit-transform: translateY(-10px);}
    20%, 40%, 60%, 80% {-webkit-transform: translateY(10px);}
}
@keyframes shakeUp {
    0%, 100% {transform: translateY(0);}
    10%, 30%, 50%, 70%, 90% {transform: translateY(-10px);}
    20%, 40%, 60%, 80% {transform: translateY(10px);}
}
.shakeUp.go {
    -webkit-animation-name: shakeUp;
    animation-name: shakeUp;
}

/*************
* FadeInLeft *
*************/

@-webkit-keyframes fadeInLeft {
    0% {
        opacity: 0;
        -webkit-transform: translateX(-400px);
    }
    50%{
       opacity: 0.3;
    }
    100% {
        opacity: 1;
        -webkit-transform: translateX(0);
    }
}
@keyframes fadeInLeft {
    0% {
        opacity: 0;
        transform: translateX(-400px);
    }
    50%{
       opacity: 0.3;
    }
    100% {
        opacity: 1;
        transform: translateX(0);
    }
}
.fadeInLeft{
    opacity: 0;
    -webkit-transform: translateX(-400px);
    transform: translateX(-400px);
}
.fadeInLeft.go {
    -webkit-animation-name: fadeInLeft;
    animation-name: fadeInLeft;
}


/*************
* FadeInRight *
*************/

@-webkit-keyframes fadeInRight {
    0% {
        opacity: 0;
        -webkit-transform: translateX(400px);
    }
    50%{
       opacity: 0.3;
    }
    100% {
        opacity: 1;
        -webkit-transform: translateX(0);
    }
}
@keyframes fadeInRight {
    0% {
        opacity: 0;
        transform: translateX(400px);
    }
    50%{
       opacity: 0.3;
    }
    100% {
        opacity: 1;
        transform: translateX(0);
    }
}
.fadeInRight{
    opacity: 0;
    -webkit-transform: translateX(400px);
    transform: translateX(400px);
}
.fadeInRight.go {
    -webkit-animation-name: fadeInRight;
    animation-name: fadeInRight;
}

/*************
* FadeInUp *
*************/

@-webkit-keyframes fadeInUp {
    0% {
        opacity: 0;
        -webkit-transform: translateY(400px);
    }
    50%{
       opacity: 0.3;
    }
    100% {
        opacity: 1;
        -webkit-transform: translateY(0);
    }
}
@keyframes fadeInUp {
    0% {
        opacity: 0;
        transform: translateY(400px);
    }
    50%{
       opacity: 0.3;
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}
.fadeInUp{
    opacity: 0;
    -webkit-transform: translateY(400px);
    transform: translateY(400px);
}
.fadeInUp.go {
    -webkit-animation-name: fadeInUp;
    animation-name: fadeInUp;
}

/*************
* FadeInDown *
*************/

@-webkit-keyframes fadeInDown {
    0% {
        opacity: 0;
        -webkit-transform: translateY(-400px);
    }
    50%{
       opacity: 0.3;
    }
    100% {
        opacity: 1;
        -webkit-transform: translateY(0);
    }
}
@keyframes fadeInDown {
    0% {
        opacity: 0;
        transform: translateY(-400px);
    }
    50%{
       opacity: 0.3;
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}
.fadeInDown{
    opacity: 0;
    -webkit-transform: translateY(-400px);
    transform: translateY(-400px);
}
.fadeInDown.go {
    -webkit-animation-name: fadeInDown;
    animation-name: fadeInDown;
}

/*****************
* rotateIn *
*****************/
@-webkit-keyframes rotateIn {
    0% {
        -webkit-transform-origin: center center;
        -webkit-transform: rotate(-200deg);
        opacity: 0;
    }
    100% {
        -webkit-transform-origin: center center;
        -webkit-transform: rotate(0);
        opacity: 1;
    }
}
@keyframes rotateIn {
    0% {
        transform-origin: center center;
        transform: rotate(-200deg);
        opacity: 0;
    }
    100% {
        transform-origin: center center;
        transform: rotate(0);
        opacity: 1;
    }
}
.rotateIn.go {
    -webkit-animation-name: rotateIn;
    animation-name: rotateIn;
}

/*****************
* rotateInUpLeft *
*****************/

@-webkit-keyframes rotateInUpLeft {
    0% {
        -webkit-transform-origin: left bottom;
        -webkit-transform: rotate(90deg);
        opacity: 0;
    }
    100% {
        -webkit-transform-origin: left bottom;
        -webkit-transform: rotate(0);
        opacity: 1;
    }
}
@keyframes rotateInUpLeft {
    0% {
        transform-origin: left bottom;
        transform: rotate(90deg);
        opacity: 0;
    }
    100% {
        transform-origin: left bottom;
        transform: rotate(0);
        opacity: 1;
    }
}
.rotateInUpLeft.go {
    -webkit-animation-name: rotateInUpLeft;
    animation-name: rotateInUpLeft;
}

/*******************
* rotateInDownLeft *
*******************/
@-webkit-keyframes rotateInDownLeft {
    0% {
        -webkit-transform-origin: left bottom;
        -webkit-transform: rotate(-90deg);
        opacity: 0;
    }
    100% {
        -webkit-transform-origin: left bottom;
        -webkit-transform: rotate(0);
        opacity: 1;
    }
}
@keyframes rotateInDownLeft {
    0% {
        transform-origin: left bottom;
        transform: rotate(-90deg);
        opacity: 0;
    }
    100% {
        transform-origin: left bottom;
        transform: rotate(0);
        opacity: 1;
    }
}
.rotateInDownLeft.go {
    -webkit-animation-name: rotateInDownLeft;
    animation-name: rotateInDownLeft;
}

/******************
* rotateInUpRight *
*******************/

@-webkit-keyframes rotateInUpRight {
    0% {
        -webkit-transform-origin: right bottom;
        -webkit-transform: rotate(-90deg);
        opacity: 0;
    }
    100% {
        -webkit-transform-origin: right bottom;
        -webkit-transform: rotate(0);
        opacity: 1;
    }
}
@keyframes rotateInUpRight {
    0% {
        transform-origin: right bottom;
        transform: rotate(-90deg);
        opacity: 0;
    }
    100% {
        transform-origin: right bottom;
        transform: rotate(0);
        opacity: 1;
    }
}
.rotateInUpRight.go {
    -webkit-animation-name: rotateInUpRight;
    animation-name: rotateInUpRight;
}

/********************
* rotateInDownRight *
********************/

@-webkit-keyframes rotateInDownRight {
    0% {
        -webkit-transform-origin: right bottom;
        -webkit-transform: rotate(90deg);
        opacity: 0;
    }
    100% {
        -webkit-transform-origin: right bottom;
        -webkit-transform: rotate(0);
        opacity: 1;
    }
}
@keyframes rotateInDownRight {
    0% {
        transform-origin: right bottom;
        transform: rotate(90deg);
        opacity: 0;
    }
    100% {
        transform-origin: right bottom;
        transform: rotate(0);
        opacity: 1;
    }
}
.rotateInDownRight.go {
    -webkit-animation-name: rotateInDownRight;
    animation-name: rotateInDownRight;
}

/*********
* rollIn *
**********/

@-webkit-keyframes rollIn {
    0% { opacity: 0; -webkit-transform: translateX(-100%) rotate(-120deg); }
    100% { opacity: 1; -webkit-transform: translateX(0px) rotate(0deg); }
}
@keyframes rollIn {
    0% { opacity: 0; transform: translateX(-100%) rotate(-120deg); }
    100% { opacity: 1; transform: translateX(0px) rotate(0deg); }
}
.rollIn.go {
    -webkit-animation-name: rollIn;
    animation-name: rollIn;
}

/*********
* wiggle *
**********/

@-webkit-keyframes wiggle {
    0% { -webkit-transform: skewX(9deg); }
    10% { -webkit-transform: skewX(-8deg); }
    20% { -webkit-transform: skewX(7deg); }
    30% { -webkit-transform: skewX(-6deg); }
    40% { -webkit-transform: skewX(5deg); }
    50% { -webkit-transform: skewX(-4deg); }
    60% { -webkit-transform: skewX(3deg); }
    70% { -webkit-transform: skewX(-2deg); }
    80% { -webkit-transform: skewX(1deg); }
    90% { -webkit-transform: skewX(0deg); }
    100% { -webkit-transform: skewX(0deg); }
}
@keyframes wiggle {
    0% { transform: skewX(9deg); }
    10% { transform: skewX(-8deg); }
    20% { transform: skewX(7deg); }
    30% { transform: skewX(-6deg); }
    40% { transform: skewX(5deg); }
    50% { transform: skewX(-4deg); }
    60% { transform: skewX(3deg); }
    70% { transform: skewX(-2deg); }
    80% { transform: skewX(1deg); }
    90% { transform: skewX(0deg); }
    100% { transform: skewX(0deg); }
}
.wiggle.go {
    -webkit-animation-name: wiggle;
    animation-name: wiggle;
    -webkit-animation-timing-function: ease-in;
    animation-timing-function: ease-in;
}

/********
* swing *
*********/

@-webkit-keyframes swing {
    20%, 40%, 60%, 80%, 100% { -webkit-transform-origin: top center; }
    20% { -webkit-transform: rotate(15deg); }
    40% { -webkit-transform: rotate(-10deg); }
    60% { -webkit-transform: rotate(5deg); }
    80% { -webkit-transform: rotate(-5deg); }
    100% { -webkit-transform: rotate(0deg); }
}
@keyframes swing {
    20% { transform: rotate(15deg); }
    40% { transform: rotate(-10deg); }
    60% { transform: rotate(5deg); }
    80% { transform: rotate(-5deg); }
    100% { transform: rotate(0deg); }
}
.swing.go {
    -webkit-transform-origin: top center;
    transform-origin: top center;
    -webkit-animation-name: swing;
    animation-name: swing;
}

/*******
* tada *
********/

@-webkit-keyframes tada {
    0% {-webkit-transform: scale(1);}
    10%, 20% {-webkit-transform: scale(0.9) rotate(-3deg);}
    30%, 50%, 70%, 90% {-webkit-transform: scale(1.1) rotate(3deg);}
    40%, 60%, 80% {-webkit-transform: scale(1.1) rotate(-3deg);}
    100% {-webkit-transform: scale(1) rotate(0);}
}
@keyframes tada {
    0% {transform: scale(1);}
    10%, 20% {transform: scale(0.9) rotate(-3deg);}
    30%, 50%, 70%, 90% {transform: scale(1.1) rotate(3deg);}
    40%, 60%, 80% {transform: scale(1.1) rotate(-3deg);}
    100% {transform: scale(1) rotate(0);}
}
.tada.go {
    -webkit-animation-name: tada;
    animation-name: tada;
}

/*********
* wobble *
**********/

@-webkit-keyframes wobble {
  0% { -webkit-transform: translateX(0%); }
  15% { -webkit-transform: translateX(-25%) rotate(-5deg); }
  30% { -webkit-transform: translateX(20%) rotate(3deg); }
  45% { -webkit-transform: translateX(-15%) rotate(-3deg); }
  60% { -webkit-transform: translateX(10%) rotate(2deg); }
  75% { -webkit-transform: translateX(-5%) rotate(-1deg); }
  100% { -webkit-transform: translateX(0%); }
}
@keyframes wobble {
  0% { transform: translateX(0%); }
  15% { transform: translateX(-25%) rotate(-5deg); }
  30% { transform: translateX(20%) rotate(3deg); }
  45% { transform: translateX(-15%) rotate(-3deg); }
  60% { transform: translateX(10%) rotate(2deg); }
  75% { transform: translateX(-5%) rotate(-1deg); }
  100% { transform: translateX(0%); }
}
.wobble.go {
    -webkit-animation-name: wobble;
    animation-name: wobble;
}

/********
* pulse *
*********/

@-webkit-keyframes pulse {
    0% { -webkit-transform: scale(1); }
    50% { -webkit-transform: scale(1.1); }
    100% { -webkit-transform: scale(1); }
}
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}
.pulse.go {
    -webkit-animation-name: pulse;
    animation-name: pulse;
}

/***************
* lightSpeedInRight *
****************/
@-webkit-keyframes lightSpeedInRight {
   0% { -webkit-transform: translateX(100%) skewX(-30deg); opacity: 0; }
    60% { -webkit-transform: translateX(-20%) skewX(30deg); opacity: 1; }
    80% { -webkit-transform: translateX(0%) skewX(-15deg); opacity: 1; }
    100% { -webkit-transform: translateX(0%) skewX(0deg); opacity: 1; }
}
@keyframes lightSpeedInRight {
    0% { transform: translateX(100%) skewX(-30deg); opacity: 0; }
    60% { transform: translateX(-20%) skewX(30deg); opacity: 1; }
    80% { transform: translateX(0%) skewX(-15deg); opacity: 1; }
    100% { transform: translateX(0%) skewX(0deg); opacity: 1; }
}
.lightSpeedInRight.go {
    -webkit-animation-name: lightSpeedInRight;
    animation-name: lightSpeedInRight;
    -webkit-animation-timing-function: ease-out;
    animation-timing-function: ease-out;
}

/***************
* lightSpeedInLeft *
****************/
@-webkit-keyframes lightSpeedInLeft {
   0% { -webkit-transform: translateX(-100%) skewX(30deg); opacity: 0; }
    60% { -webkit-transform: translateX(20%) skewX(-30deg); opacity: 1; }
    80% { -webkit-transform: translateX(0%) skewX(15deg); opacity: 1; }
    100% { -webkit-transform: translateX(0%) skewX(0deg); opacity: 1; }
}
@keyframes lightSpeedInLeft {
    0% { transform: translateX(-100%) skewX(30deg); opacity: 0; }
    60% { transform: translateX(20%) skewX(-30deg); opacity: 1; }
    80% { transform: translateX(0%) skewX(15deg); opacity: 1; }
    100% { transform: translateX(0%) skewX(0deg); opacity: 1; }
}
.lightSpeedInLeft.go {
    -webkit-animation-name: lightSpeedInLeft;
    animation-name: lightSpeedInLeft;
    -webkit-animation-timing-function: ease-out;
    animation-timing-function: ease-out;
}


/*******
* Flip *
*******/
@-webkit-keyframes flip {
    0% {
        -webkit-transform: perspective(400px) rotateY(0);
        -webkit-animation-timing-function: ease-out;
    }
    40% {
        -webkit-transform: perspective(400px) translateZ(150px) rotateY(170deg);
        -webkit-animation-timing-function: ease-out;
    }
    50% {
        -webkit-transform: perspective(400px) translateZ(150px) rotateY(190deg) scale(1);
        -webkit-animation-timing-function: ease-in;
    }
    80% {
        -webkit-transform: perspective(400px) rotateY(360deg) scale(.95);
        -webkit-animation-timing-function: ease-in;
    }
    100% {
        -webkit-transform: perspective(400px) scale(1);
        -webkit-animation-timing-function: ease-in;
    }
}
@keyframes flip {
    0% {
        transform: perspective(400px) rotateY(0);
        animation-timing-function: ease-out;
    }
    40% {
        transform: perspective(400px) translateZ(150px) rotateY(170deg);
        animation-timing-function: ease-out;
    }
    50% {
        transform: perspective(400px) translateZ(150px) rotateY(190deg) scale(1);
        animation-timing-function: ease-in;
    }
    80% {
        transform: perspective(400px) rotateY(360deg) scale(.95);
        animation-timing-function: ease-in;
    }
    100% {
        transform: perspective(400px) scale(1);
        animation-timing-function: ease-in;
    }
}
.flip.go {
    -webkit-backface-visibility: visible !important;
    -webkit-animation-name: flip;
    backface-visibility: visible !important;
    animation-name: flip;
}

/**********
* flipInX *
**********/
@-webkit-keyframes flipInX {
    0% {
        -webkit-transform: perspective(400px) rotateX(90deg);
        opacity: 0;
    }
    40% {
        -webkit-transform: perspective(400px) rotateX(-10deg);
    }
    70% {
        -webkit-transform: perspective(400px) rotateX(10deg);
    }
    100% {
        -webkit-transform: perspective(400px) rotateX(0deg);
        opacity: 1;
    }
}
@keyframes flipInX {
    0% {
        transform: perspective(400px) rotateX(90deg);
        opacity: 0;
    }
    40% {
        transform: perspective(400px) rotateX(-10deg);
    }
    70% {
        transform: perspective(400px) rotateX(10deg);
    }
    100% {
        transform: perspective(400px) rotateX(0deg);
        opacity: 1;
    }
}
.flipInX.go {
    -webkit-backface-visibility: visible !important;
    -webkit-animation-name: flipInX;
    backface-visibility: visible !important;
    animation-name: flipInX;
}

/**********
* flipInY *
**********/

@-webkit-keyframes flipInY {
    0% {
        -webkit-transform: perspective(400px) rotateY(90deg);
        opacity: 0;
    }
    40% {
        -webkit-transform: perspective(400px) rotateY(-10deg);
    }
    70% {
        -webkit-transform: perspective(400px) rotateY(10deg);
    }
    100% {
        -webkit-transform: perspective(400px) rotateY(0deg);
        opacity: 1;
    }
}
@keyframes flipInY {
    0% {
        transform: perspective(400px) rotateY(90deg);
        opacity: 0;
    }
    40% {
        transform: perspective(400px) rotateY(-10deg);
    }
    70% {
        transform: perspective(400px) rotateY(10deg);
    }
    100% {
        transform: perspective(400px) rotateY(0deg);
        opacity: 1;
    }
}
.flipInY.go {
    -webkit-backface-visibility: visible !important;
    -webkit-animation-name: flipInY;
    backface-visibility: visible !important;
    animation-name: flipInY;
}

/*****************
* Out animations *
*****************/


/************
* bounceOut *
*************/
@-webkit-keyframes bounceOut {
    0% {
        -webkit-transform: scale(1);
    }
    25% {
        -webkit-transform: scale(.95);
    }
    50% {
        opacity: 1;
        -webkit-transform: scale(1.1);
    }
    100% {
        opacity: 0;
        -webkit-transform: scale(.3);
    }
}
@keyframes bounceOut {
    0% {
        transform: scale(1);
    }
    25% {
        transform: scale(.95);
    }
    50% {
        opacity: 1;
        transform: scale(1.1);
    }
    100% {
        opacity: 0;
        transform: scale(.3);
    }
}
.bounceOut.goAway {
    -webkit-animation-name: bounceOut;
    animation-name: bounceOut;
}

/************
* bounceOutUp *
*************/
@-webkit-keyframes bounceOutUp {
    0% {
        -webkit-transform: translateY(0);
    }
    20% {
        opacity: 1;
        -webkit-transform: translateY(20px);
    }
    100% {
        opacity: 0;
        -webkit-transform: translateY(-2000px);
    }
}
@keyframes bounceOutUp {
    0% {
        transform: translateY(0);
    }
    20% {
        opacity: 1;
        transform: translateY(20px);
    }
    100% {
        opacity: 0;
        transform: translateY(-2000px);
    }
}
.bounceOutUp.goAway {
    -webkit-animation-name: bounceOutUp;
    animation-name: bounceOutUp;
}

/************
* bounceOutDown *
*************/
@-webkit-keyframes bounceOutDown {
    0% {
        -webkit-transform: translateY(0);
    }
    20% {
        opacity: 1;
        -webkit-transform: translateY(-20px);
    }
    100% {
        opacity: 0;
        -webkit-transform: translateY(2000px);
    }
}
@keyframes bounceOutDown {
    0% {
        transform: translateY(0);
    }
    20% {
        opacity: 1;
        transform: translateY(-20px);
    }
    100% {
        opacity: 0;
        transform: translateY(2000px);
    }
}
.bounceOutDown.goAway {
    -webkit-animation-name: bounceOutDown;
    animation-name: bounceOutDown;
}


/************
* bounceOutLeft *
*************/
@-webkit-keyframes bounceOutLeft {
    0% {
        -webkit-transform: translateX(0);
    }
    20% {
        opacity: 1;
        -webkit-transform: translateX(20px);
    }
    100% {
        opacity: 0;
        -webkit-transform: translateX(-2000px);
    }
}
@keyframes bounceOutLeft {
    0% {
        transform: translateX(0);
    }
    20% {
        opacity: 1;
        transform: translateX(20px);
    }
    100% {
        opacity: 0;
        transform: translateX(-2000px);
    }
}
.bounceOutLeft.goAway {
    -webkit-animation-name: bounceOutLeft;
    animation-name: bounceOutLeft;
}

/************
* bounceOutRight *
*************/
@-webkit-keyframes bounceOutRight {
    0% {
        -webkit-transform: translateX(0);
    }
    20% {
        opacity: 1;
        -webkit-transform: translateX(-20px);
    }
    100% {
        opacity: 0;
        -webkit-transform: translateX(2000px);
    }
}
@keyframes bounceOutRight {
    0% {
        transform: translateX(0);
    }
    20% {
        opacity: 1;
        transform: translateX(-20px);
    }
    100% {
        opacity: 0;
        transform: translateX(2000px);
    }
}
.bounceOutRight.goAway {
    -webkit-animation-name: bounceOutRight;
    animation-name: bounceOutRight;
}

/************
* fadeOut *
*************/
@-webkit-keyframes fadeOut {
    0% {opacity: 1;}
    100% {opacity: 0;}
}
@keyframes fadeOut {
    0% {opacity: 1;}
    100% {opacity: 0;}
}
.fadeOut.goAway {
    -webkit-animation-name: fadeOut;
    animation-name: fadeOut;
}

/************
* fadeOutUp *
*************/
@-webkit-keyframes fadeOutUp {
    0% {
        opacity: 1;
        -webkit-transform: translateY(0);
    }
    100% {
        opacity: 0;
        -webkit-transform: translateY(-2000px);
    }
}
@keyframes fadeOutUp {
    0% {
        opacity: 1;
        transform: translateY(0);
    }
    100% {
        opacity: 0;
        transform: translateY(-2000px);
    }
}
.fadeOutUp.goAway {
    -webkit-animation-name: fadeOutUp;
    animation-name: fadeOutUp;
}

/************
* fadeOutDown *
*************/
@-webkit-keyframes fadeOutDown {
    0% {
        opacity: 1;
        -webkit-transform: translateY(0);
    }
    100% {
        opacity: 0;
        -webkit-transform: translateY(2000px);
    }
}
@keyframes fadeOutDown {
    0% {
        opacity: 1;
        transform: translateY(0);
    }
    100% {
        opacity: 0;
        transform: translateY(2000px);
    }
}
.fadeOutDown.goAway {
    -webkit-animation-name: fadeOutDown;
    animation-name: fadeOutDown;
}

/************
* fadeOutLeft *
*************/
@-webkit-keyframes fadeOutLeft {
    0% {
        opacity: 1;
        -webkit-transform: translateX(0);
    }
    100% {
        opacity: 0;
        -webkit-transform: translateX(-2000px);
    }
}
@keyframes fadeOutLeft {
    0% {
        opacity: 1;
        transform: translateX(0);
    }
    100% {
        opacity: 0;
        transform: translateX(-2000px);
    }
}
.fadeOutLeft.goAway {
    -webkit-animation-name: fadeOutLeft;
    animation-name: fadeOutLeft;
}

/************
* fadeOutRight *
*************/
@-webkit-keyframes fadeOutRight {
    0% {
        opacity: 1;
        -webkit-transform: translateX(0);
    }
    100% {
        opacity: 0;
        -webkit-transform: translateX(2000px);
    }
}
@keyframes fadeOutRight {
    0% {
        opacity: 1;
        transform: translateX(0);
    }
    100% {
        opacity: 0;
        transform: translateX(2000px);
    }
}
.fadeOutRight.goAway {
    -webkit-animation-name: fadeOutRight;
    animation-name: fadeOutRight;
}
/************
* flipOutX *
*************/
@-webkit-keyframes flipOutX {
    0% {
        -webkit-transform: perspective(400px) rotateX(0deg);
        opacity: 1;
    }
    100% {
        -webkit-transform: perspective(400px) rotateX(90deg);
        opacity: 0;
    }
}
@keyframes flipOutX {
    0% {
        transform: perspective(400px) rotateX(0deg);
        opacity: 1;
    }
    100% {
        transform: perspective(400px) rotateX(90deg);
        opacity: 0;
    }
}
.flipOutX.goAway {
    -webkit-animation-name: flipOutX;
    -webkit-backface-visibility: visible !important;
    animation-name: flipOutX;
    backface-visibility: visible !important;
}

/************
* flipOutY *
*************/
@-webkit-keyframes flipOutY {
    0% {
        -webkit-transform: perspective(400px) rotateY(0deg);
        opacity: 1;
    }
    100% {
        -webkit-transform: perspective(400px) rotateY(90deg);
        opacity: 0;
    }
}
@keyframes flipOutY {
    0% {
        transform: perspective(400px) rotateY(0deg);
        opacity: 1;
    }
    100% {
        transform: perspective(400px) rotateY(90deg);
        opacity: 0;
    }
}
.flipOutY {
    -webkit-backface-visibility: visible !important;
    -webkit-animation-name: flipOutY;
    backface-visibility: visible !important;
    animation-name: flipOutY;
}

/************
* lightSpeedOutRight *
*************/
@-webkit-keyframes lightSpeedOutRight {
    0% { -webkit-transform: translateX(0%) skewX(0deg); opacity: 1; }
    100% { -webkit-transform: translateX(100%) skewX(-30deg); opacity: 0; }
}
@keyframes lightSpeedOutRight {
    0% { transform: translateX(0%) skewX(0deg); opacity: 1; }
    100% { transform: translateX(100%) skewX(-30deg); opacity: 0; }
}
.lightSpeedOutRight.goAway {
    -webkit-animation-name: lightSpeedOutRight;
    animation-name: lightSpeedOutRight;
    -webkit-animation-timing-function: ease-in;
    animation-timing-function: ease-in;
}


/************
* lightSpeedOutLeft *
*************/
@-webkit-keyframes lightSpeedOutLeft {
    0% { -webkit-transform: translateX(0%) skewX(0deg); opacity: 1; }
    100% { -webkit-transform: translateX(-100%) skewX(30deg); opacity: 0; }
}
@keyframes lightSpeedOutLeft {
    0% { transform: translateX(0%) skewX(0deg); opacity: 1; }
    100% { transform: translateX(-100%) skewX(30deg); opacity: 0; }
}
.lightSpeedOutLeft.goAway {
    -webkit-animation-name: lightSpeedOutLeft;
    animation-name: lightSpeedOutLeft;
    -webkit-animation-timing-function: ease-in;
    animation-timing-function: ease-in;

}

/************
* rotateOut *
*************/
@-webkit-keyframes rotateOut {
    0% {
        -webkit-transform-origin: center center;
        -webkit-transform: rotate(0);
        opacity: 1;
    }
    100% {
        -webkit-transform-origin: center center;
        -webkit-transform: rotate(200deg);
        opacity: 0;
    }
}
@keyframes rotateOut {
    0% {
        transform-origin: center center;
        transform: rotate(0);
        opacity: 1;
    }
    100% {
        transform-origin: center center;
        transform: rotate(200deg);
        opacity: 0;
    }
}
.rotateOut.goAway {
    -webkit-animation-name: rotateOut;
    animation-name: rotateOut;
}


/************
* rotateOutUpLeft *
*************/
@-webkit-keyframes rotateOutUpLeft {
    0% {
        -webkit-transform-origin: left bottom;
        -webkit-transform: rotate(0);
        opacity: 1;
    }
    100% {
        -webkit-transform-origin: left bottom;
        -webkit-transform: rotate(-90deg);
        opacity: 0;
    }
}
@keyframes rotateOutUpLeft {
    0% {
        transform-origin: left bottom;
        transform: rotate(0);
        opacity: 1;
    }
    100% {
        -transform-origin: left bottom;
        -transform: rotate(-90deg);
        opacity: 0;
    }
}
.rotateOutUpLeft.goAway {
    -webkit-animation-name: rotateOutUpLeft;
    animation-name: rotateOutUpLeft;
}

/************
* rotateOutDownLeft *
*************/

@-webkit-keyframes rotateOutDownLeft {
    0% {
        -webkit-transform-origin: left bottom;
        -webkit-transform: rotate(0);
        opacity: 1;
    }
    100% {
        -webkit-transform-origin: left bottom;
        -webkit-transform: rotate(90deg);
        opacity: 0;
    }
}
@keyframes rotateOutDownLeft {
    0% {
        transform-origin: left bottom;
        transform: rotate(0);
        opacity: 1;
    }
    100% {
        transform-origin: left bottom;
        transform: rotate(90deg);
        opacity: 0;
    }
}
.rotateOutDownLeft.goAway {
    -webkit-animation-name: rotateOutDownLeft;
    animation-name: rotateOutDownLeft;
}
/************
* rotateOutUpRight *
*************/

@-webkit-keyframes rotateOutUpRight {
    0% {
        -webkit-transform-origin: right bottom;
        -webkit-transform: rotate(0);
        opacity: 1;
    }
    100% {
        -webkit-transform-origin: right bottom;
        -webkit-transform: rotate(90deg);
        opacity: 0;
    }
}
@keyframes rotateOutUpRight {
    0% {
        transform-origin: right bottom;
        transform: rotate(0);
        opacity: 1;
    }
    100% {
        transform-origin: right bottom;
        transform: rotate(90deg);
        opacity: 0;
    }
}
.rotateOutUpRight.goAway {
    -webkit-animation-name: rotateOutUpRight;
    animation-name: rotateOutUpRight;
}

/************
* rollOut *
*************/
@-webkit-keyframes rollOut {
    0% {
        opacity: 1;
        -webkit-transform: translateX(0px) rotate(0deg);
    }
    100% {
        opacity: 0;
        -webkit-transform: translateX(100%) rotate(120deg);
    }
}
@keyframes rollOut {
    0% {
        opacity: 1;
        transform: translateX(0px) rotate(0deg);
    }
    100% {
        opacity: 0;
        transform: translateX(100%) rotate(120deg);
    }
}
.rollOut.goAway {
    -webkit-animation-name: rollOut;
    animation-name: rollOut;
}
/*****************
* Short Animations
*******************/

/*********************
* fadeInUpShort
*********************/
@-webkit-keyframes fadeInUpShort {
    0% {
        opacity: 0;
        -webkit-transform: translateY(20px);
    }
    100% {
        opacity: 1;
        -webkit-transform: translateY(0);
    }
}

@keyframes fadeInUpShort {
    0% {
        opacity: 0;
        transform: translateY(20px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}
.fadeInUpShort{
    opacity: 0;
    -webkit-transform: translateY(20px);
    transform: translateY(20px);
}
.fadeInUpShort.go {
    -webkit-animation-name: fadeInUpShort;
    animation-name: fadeInUpShort;
}

/*********************
* fadeInDownShort
*********************/
@-webkit-keyframes fadeInDownShort {
    0% {
        opacity: 0;
        -webkit-transform: translateY(-20px);
    }
    100% {
        opacity: 1;
        -webkit-transform: translateY(0);
    }
}

@keyframes fadeInDownShort {
    0% {
        opacity: 0;
        transform: translateY(-20px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}
.fadeInDownShort{
    opacity: 0;
    -webkit-transform: translateY(-20px);
    transform: translateY(-20px);
}
.fadeInDownShort.go {
    -webkit-animation-name: fadeInDownShort;
    animation-name: fadeInDownShort;
}

/*********************
* fadeInRightShort
*********************/
@-webkit-keyframes fadeInRightShort {
    0% {
        opacity: 0;
        -webkit-transform: translateX(20px);
    }
    100% {
        opacity: 1;
        -webkit-transform: translateX(0);
    }
}
@keyframes fadeInRightShort {
    0% {
        opacity: 0;
        transform: translateX(20px);
    }
    100% {
        opacity: 1;
        transform: translateX(0);
    }
}
.fadeInRightShort {
    opacity: 0;
    -webkit-transform: translateX(20px);
    transform: translateX(20px);
}
.fadeInRightShort.go {
    -webkit-animation-name: fadeInRightShort;
    animation-name: fadeInRightShort;
}

/*********************
* fadeInLeftShort
*********************/
@-webkit-keyframes fadeInLeftShort {
    0% {
        opacity: 0;
        -webkit-transform: translateX(-20px);
    }
    100% {
        opacity: 1;
        -webkit-transform: translateX(0);
    }
}
@keyframes fadeInLeftShort {
    0% {
        opacity: 0;
        transform: translateX(-20px);
    }
    100% {
        opacity: 1;
        transform: translateX(0);
    }
}
.fadeInLeftShort {
    opacity: 0;
    -webkit-transform: translateX(-20px);
    transform: translateX(-20px);
}
.fadeInLeftShort.go {
    -webkit-animation-name: fadeInLeftShort;
    animation-name: fadeInLeftShort;
}

</style>

</body>
</html>
