<!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>

	<!-- Basic Page Needs
  ================================================== -->
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<title><?php bloginfo('name'); ?>  <?php wp_title(); ?></title>

	<!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- CSS
  ================================================== -->
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" />

	<?php global $gdl_is_responsive ?>
	<?php if( $gdl_is_responsive ){ ?>
		<meta name="viewport" content="width=device-width, user-scalable=no">
		<link rel="stylesheet" href="<?php echo GOODLAYERS_PATH; ?>/stylesheet/foundation-responsive.css">
	<?php }else{ ?>
		<link rel="stylesheet" href="<?php echo GOODLAYERS_PATH; ?>/stylesheet/foundation.css">
	<?php } ?>

	<!--[if IE 7]>
		<link rel="stylesheet" href="<?php echo GOODLAYERS_PATH; ?>/stylesheet/ie7-style.css" />
		<link rel="stylesheet" href="<?php echo GOODLAYERS_PATH; ?>/stylesheet/font-awesome/font-awesome-ie7.min.css" />
	<![endif]-->

	<?php

		// include favicon in the header
		if(get_option( THEME_SHORT_NAME.'_enable_favicon','disable') == "enable"){
			$gdl_favicon = get_option(THEME_SHORT_NAME.'_favicon_image');
			if( $gdl_favicon ){
				$gdl_favicon = wp_get_attachment_image_src($gdl_favicon, 'full');
				echo '<link rel="shortcut icon" href="' . $gdl_favicon[0] . '" type="image/x-icon" />';
			}
		}

		// add facebook thumbnail to this page
		$thumbnail_id = get_post_thumbnail_id();
		if( !empty($thumbnail_id) ){
			$thumbnail = wp_get_attachment_image_src( $thumbnail_id , '150x150' );
			echo '<meta property="og:image" content="' . $thumbnail[0] . '"/>';
		}

		// start calling header script
		wp_head();

	?>
	<script>
	(function(s) {
	var head = document.getElementsByTagName('HEAD').item(0);
	var s= document.createElement("script");
	s.type = "text/javascript";
	s.src="//s3-us-west-2.amazonaws.com/formget/js/popup.js";
	head.appendChild(s);
	var options = {
	            'tabKey': 'wADi-286496/t',
	            'tabtext':'Contact Us',
	            'height': '526',
	            'width':'350',
	            'tabPosition':'bottom',
	            'textColor': 'ffffff',
	            'fontSize': '16',
	            'tabBackground':'17B86F',
	            'tabbed':''
				};
	s.onload = s.onreadystatechange = function() {
	var rs = this.readyState;
			 if (rs)
	                if (rs != 'complete')
	                    if (rs != 'loaded')
	                        return;
	            try {
	                sideBar = new buildTabbed();
				    sideBar.loadContent();
					sideBar.initializeOption(options);
	  } catch (e) {
	                }
	        };
	})(document, 'script');
	</script>
	<script>
jQuery(document).ready(function() {
	$=jQuery;
//Preloader
$(window).on("load", function() {
preloaderFadeOutTime = 800;
function hidePreloader() {
var preloader = $('.sk-cube-grid');
preloader.fadeOut(preloaderFadeOutTime);
$('#preloader').css({"background-color":"rgb(255, 255, 255)","transition":"all 2s ease","display":"none"});
}
hidePreloader();
});
});
</script>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>
<body <?php echo body_class(); ?>>
	<div id="preloader">
	<div class="sk-cube-grid">
  <div class="sk-cube sk-cube1"></div>
  <div class="sk-cube sk-cube2"></div>
  <div class="sk-cube sk-cube3"></div>
  <div class="sk-cube sk-cube4"></div>
  <div class="sk-cube sk-cube5"></div>
  <div class="sk-cube sk-cube6"></div>
  <div class="sk-cube sk-cube7"></div>
  <div class="sk-cube sk-cube8"></div>
  <div class="sk-cube sk-cube9"></div>
</div>
</div>
<?php
	// print custom background
	$background_style = get_option(THEME_SHORT_NAME.'_background_style', 'Pattern');
	if($background_style == 'Custom Image'){
		$background_id = get_option(THEME_SHORT_NAME.'_background_custom');
		$alt_text = get_post_meta($background_id , '_wp_attachment_image_alt', true);

		if(!empty($background_id)){
			$background_image = wp_get_attachment_image_src( $background_id, 'full' );
			echo '<div class="gdl-custom-full-background">';
			echo '<img src="' . $background_image[0] . '" alt="' . $alt_text . '" />';
			echo '</div>';
		}
	}

	$header_outer_class = '';

	global $gdl_top_slider_type;
	if( is_page() && !empty($gdl_top_slider_type) && $gdl_top_slider_type != 'Title' && $gdl_top_slider_type != 'None'){
		if( get_post_meta($post->ID, 'page-option-enable-full-slider', true) == 'Yes' ){
			$header_outer_class = 'full-container top-slider-enabled';
		}else{
			$header_outer_class = 'boxed-container top-slider-enabled';
		}

		if( get_option(THEME_SHORT_NAME . '_enable_boxed_style', 'enable') == 'enable' ){
			$header_outer_class = $header_outer_class . ' boxed-style-enabled';
		}else{
			$header_outer_class = $header_outer_class . ' full-style-enabled';
		}
	}
?>
<div class="body-outer-wrapper">
	<div class="body-wrapper">
		<div class="header-outer-wrapper <?php echo $header_outer_class; ?>">
			<!-- top navigation -->
			<div class="top-navigation-wrapper boxed-style">
				<div class="top-navigation-container container">
					<?php
						$top_left_text = get_option(THEME_SHORT_NAME.'_top_navigation_left');
						if( !empty($top_left_text) ){
							echo '<div class="top-navigation-left">';
							echo do_shortcode( __( $top_left_text , 'gdl_front_end') );
							echo '</div>';
						}

						echo '<div class="top-navigation-right">';

						$top_right_text = get_option(THEME_SHORT_NAME.'_top_navigation_right');
						if( !empty($top_right_text) ){
							echo '<div class="top-navigation-right-text">';
							echo do_shortcode( __( $top_right_text , 'gdl_front_end') );
							echo '</div>';
						}

						// Get Social Icons
						$social_icon_type = get_option(THEME_SHORT_NAME.'_social_icon_type', 'light');
						$gdl_social_icon = array(
							'delicious'=> array('name'=>THEME_SHORT_NAME.'_delicious', 'url'=> GOODLAYERS_PATH.'/images/icon/' . $social_icon_type . '/social-icon/delicious.png'),
							'deviantart'=> array('name'=>THEME_SHORT_NAME.'_deviantart', 'url'=> GOODLAYERS_PATH.'/images/icon/' . $social_icon_type . '/social-icon/deviantart.png'),
							'digg'=> array('name'=>THEME_SHORT_NAME.'_digg', 'url'=> GOODLAYERS_PATH.'/images/icon/' . $social_icon_type . '/social-icon/digg.png'),
							'facebook' => array('name'=>THEME_SHORT_NAME.'_facebook', 'url'=> GOODLAYERS_PATH.'/images/icon/' . $social_icon_type . '/social-icon/facebook.png'),
							'flickr' => array('name'=>THEME_SHORT_NAME.'_flickr', 'url'=> GOODLAYERS_PATH.'/images/icon/' . $social_icon_type . '/social-icon/flickr.png'),
							'lastfm'=> array('name'=>THEME_SHORT_NAME.'_lastfm', 'url'=> GOODLAYERS_PATH.'/images/icon/' . $social_icon_type . '/social-icon/lastfm.png'),
							'linkedin' => array('name'=>THEME_SHORT_NAME.'_linkedin', 'url'=> GOODLAYERS_PATH.'/images/icon/' . $social_icon_type . '/social-icon/linkedin.png'),
							'picasa'=> array('name'=>THEME_SHORT_NAME.'_picasa', 'url'=> GOODLAYERS_PATH.'/images/icon/' . $social_icon_type . '/social-icon/picasa.png'),
							'rss'=> array('name'=>THEME_SHORT_NAME.'_rss', 'url'=> GOODLAYERS_PATH.'/images/icon/' . $social_icon_type . '/social-icon/rss.png'),
							'stumble-upon'=> array('name'=>THEME_SHORT_NAME.'_stumble_upon', 'url'=> GOODLAYERS_PATH.'/images/icon/' . $social_icon_type . '/social-icon/stumble-upon.png'),
							'tumblr'=> array('name'=>THEME_SHORT_NAME.'_tumblr', 'url'=> GOODLAYERS_PATH.'/images/icon/' . $social_icon_type . '/social-icon/tumblr.png'),
							'twitter' => array('name'=>THEME_SHORT_NAME.'_twitter', 'url'=> GOODLAYERS_PATH.'/images/icon/' . $social_icon_type . '/social-icon/twitter.png'),
							'vimeo' => array('name'=>THEME_SHORT_NAME.'_vimeo', 'url'=> GOODLAYERS_PATH.'/images/icon/' . $social_icon_type . '/social-icon/vimeo.png'),
							'youtube' => array('name'=>THEME_SHORT_NAME.'_youtube', 'url'=> GOODLAYERS_PATH.'/images/icon/' . $social_icon_type . '/social-icon/youtube.png'),
							'google_plus' => array('name'=>THEME_SHORT_NAME.'_google_plus', 'url'=> GOODLAYERS_PATH.'/images/icon/' . $social_icon_type . '/social-icon/google-plus.png'),
							'email' => array('name'=>THEME_SHORT_NAME.'_email', 'url'=> GOODLAYERS_PATH.'/images/icon/' . $social_icon_type . '/social-icon/email.png'),
							'pinterest' => array('name'=>THEME_SHORT_NAME.'_pinterest', 'url'=> GOODLAYERS_PATH.'/images/icon/' . $social_icon_type . '/social-icon/pinterest.png')
						);

						echo '<div id="gdl-social-icon" class="social-wrapper gdl-retina">';
						echo '<div class="social-icon-wrapper">';
						foreach( $gdl_social_icon as $social_name => $social_icon ){
							$social_link = get_option($social_icon['name']);

							if( !empty($social_link) ){
								echo '<div class="social-icon"><a target="_blank" href="' . $social_link . '">' ;
								echo '<img src="' . $social_icon['url'] . '" alt="' . $social_name . '" width="16" height="16" />';
								echo '</a></div>';
							}
						}
						echo '</div>'; // social icon wrapper
						echo '</div>'; // social wrapper
						echo '</div>';
					?>
					<div class="clear"></div>
				</div>
			</div> <!-- top navigation wrapper -->

			<div class="header-wrapper container main">

				<!-- Get Logo -->
				<div class="logo-wrapper">
					<?php
						$logo_id = get_option(THEME_SHORT_NAME.'_logo');
						if( empty($logo_id) ){
							$alt_text = 'default-logo';
							$logo_attachment = GOODLAYERS_PATH . '/images/default-logo.png';
						}else{
							$alt_text = get_post_meta($logo_id , '_wp_attachment_image_alt', true);
							$logo_attachment = wp_get_attachment_image_src($logo_id, 'full');
							$logo_attachment = $logo_attachment[0];
						}

						if( is_front_page() ){
							echo '<h1><a href="';
							echo home_url();
							echo '"><img src="' . $logo_attachment . '" alt="' . $alt_text . '"/></a></h1>';
						}else{
							echo '<a href="';
							echo home_url();
							echo '"><img src="' . $logo_attachment . '" alt="' . $alt_text . '"/></a>';
						}
					?>
				</div>

				<!-- Navigation -->
				<div class="gdl-navigation-wrapper">
					<?php
						// responsive menu
						if( $gdl_is_responsive && has_nav_menu('main_menu') ){
							dropdown_menu( array('dropdown_title' => '-- Main Menu --', 'indent_string' => '- ', 'indent_after' => '','container' => 'div', 'container_class' => 'responsive-menu-wrapper', 'theme_location'=>'main_menu') );
							echo '<div class="clear"></div>';
						}

						// main menu
						echo '<div class="navigation-wrapper">';
						if( has_nav_menu('main_menu') ){
							echo '<div class="gdl-current-menu" ></div>';
							wp_nav_menu( array('container' => 'div', 'container_class' => 'menu-wrapper', 'container_id' => 'main-superfish-wrapper',
								'menu_class'=> 'sf-menu',  'theme_location' => 'main_menu', 'walker' => new description_walker() ) );
						}
						echo '<div class="clear"></div>';
						echo '</div>'; // navigation-wrapper
					?>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
			</div> <!-- header wrapper container -->
			<?php
				if( is_page() && !empty($gdl_top_slider_type) && $gdl_top_slider_type != 'No Slider' &&
					$gdl_top_slider_type != 'Title' && $gdl_top_slider_type != 'None'){
					echo '<div class="header-top-bar-wrapper boxed-style">';
					echo '<div class="header-top-bar"></div>';
					echo '</div>';
				}
			?>
		</div> <!-- header outer wrapper -->
		<?php

			if( is_page() ){
				// Top Slider Part
				global $gdl_top_slider_xml;
				if( $gdl_top_slider_type == 'Layer Slider' ){
					$layer_slider_id = get_post_meta( $post->ID, 'page-option-layer-slider-id', true);
					echo '<div class="gdl-top-slider ' . $header_outer_class . '">';
					echo '<div class="gdl-top-layer-slider-wrapper">';
					echo do_shortcode('[layerslider id="' . $layer_slider_id . '"]');
					echo '<div class="clear"></div>';
					echo '</div>';
					echo '</div>';
				}else if( empty($gdl_top_slider_type) || $gdl_top_slider_type == 'Title' || $gdl_top_slider_type == 'No Slider' ){
					$page_caption = get_post_meta($post->ID, 'page-option-caption', true);
					print_page_header(get_the_title(), $page_caption);
				}else if ( $gdl_top_slider_type != "None"){
					echo '<div class="gdl-top-slider ' . $header_outer_class . '">';
					echo '<div class="gdl-top-slider-wrapper">';
					$slider_xml = "<Slider>" . create_xml_tag('size', 'full-width');
					$slider_xml = $slider_xml . create_xml_tag('slider-type', $gdl_top_slider_type);
					$slider_xml = $slider_xml . $gdl_top_slider_xml;
					$slider_xml = $slider_xml . "</Slider>";
					$slider_xml_dom = new DOMDocument();
					$slider_xml_dom->loadXML($slider_xml);
					print_slider_item($slider_xml_dom->documentElement);
					echo '<div class="clear"></div>';
					echo '</div>';
					echo '</div>';
				}
			}else if( is_single() ){
				$current_page_style = get_option(THEME_SHORT_NAME.'_use_portfolio_as', 'portfolio style');
				if( $post->post_type == 'portfolio' && $current_page_style == 'portfolio style' ){
					$single_title = get_the_title();
					$single_caption = get_post_meta( $post->ID, "post-option-blog-header-caption", true);
					print_page_header($single_title, $single_caption);
				}else if( $post->post_type == 'product' ){
					$single_title = __('Product', 'gdl_front_end');
					$single_caption = '';
					print_page_header($single_title, $single_caption);
				}else{
					$single_title = get_post_meta( $post->ID, "post-option-blog-header-title", true);
					$single_caption = get_post_meta( $post->ID, "post-option-blog-header-caption", true);
					if(empty( $single_title )){
						$single_title = get_option(THEME_SHORT_NAME . '_default_post_header','Blog Post');
						$single_caption = get_option(THEME_SHORT_NAME . '_default_post_caption');
					}
					print_page_header($single_title, $single_caption);
				}
			}else if( is_404() ){
				global $gdl_admin_translator;
				if( $gdl_admin_translator == 'enable' ){
					$translator_404_title = get_option(THEME_SHORT_NAME.'_404_title', 'Page Not Found');
				}else{
					$translator_404_title = __('Page Not Found','gdl_front_end');
				}
				print_page_header($translator_404_title);
			}else if( is_search() ){
				global $gdl_admin_translator;
				if( $gdl_admin_translator == 'enable' ){
					$title = get_option(THEME_SHORT_NAME.'_search_header_title', 'Search Results');
				}else{
					$title = __('Search Results', 'gdl_front_end');
				}

				$caption = get_search_query();
				print_page_header($title, $caption);
			}else if( is_archive() ){

				if( is_category() || is_tax('portfolio-category') || is_tax('product_cat') ){
					$title = __('Category','gdl_front_end');
					$caption = single_cat_title('', false);
				}else if( is_tag() || is_tax('portfolio-tag') || is_tax('product_tag') ){
					$title = __('Tag','gdl_front_end');
					$caption = single_cat_title('', false);
				}else if( is_day() ){
					$title = __('Day','gdl_front_end');
					$caption = get_the_date('F j, Y');
				}else if( is_month() ){
					$title = __('Month','gdl_front_end');
					$caption = get_the_date('F Y');
				}else if( is_year() ){
					$title = __('Year','gdl_front_end');
					$caption = get_the_date('Y');
				}else if( is_author() ){
					$title = __('By','gdl_front_end');

					$author_id = get_query_var('author');
					$author = get_user_by('id', $author_id);
					$caption = $author->display_name;
				}else{
					$title = __('Shop','gdl_front_end');
				}

				print_page_header($title, $caption);
			}
		?>
		<div class="content-outer-wrapper <?php echo $header_outer_class; ?>">
			<div class="header-bottom-bar-wrapper boxed-style">
				<div class="header-bottom-bar boxed-style"></div>
			</div>
			<?php
				$bottom_slider_column = intval(get_post_meta($post->ID, 'page-option-bottom-slider-column', true));
				if( !empty($bottom_slider_column) && $bottom_slider_column != 'None' ){
					echo '<div class="bottom-slider-wrapper boxed-container">';
					for( $i=1; $i<=$bottom_slider_column; $i++){
					$bottom_slider_content = get_post_meta($post->ID, 'page-option-bottom-slider-column' . $i, true);

					echo '<div class="bottom-slider-column' . $bottom_slider_column . '"  >';
					echo __(do_shortcode($bottom_slider_content));
					echo '</div>';
					}
					echo '<div class="clear"></div>';
					echo '</div>'; // bottom slider wrapper
				}
			?>
			<div class="content-wrapper container main ">
