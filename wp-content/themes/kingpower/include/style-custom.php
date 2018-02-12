<?php
	/*	
	*	Goodlayers Custom Style File
	*	---------------------------------------------------------------------
	*	This file fetch all style options in admin panel to generate the 
	*	style-custom.css file
	*	---------------------------------------------------------------------
	*/
	
	// This function is called when user save the option ( admin panel )
	function gdl_generate_style_custom( $data = '' ){
		global $gdl_fh, $gdl_custom_stylesheet_name;
		
		$return_data = array('success'=>'-1', 'alert'=>'Cannot write ' . $gdl_custom_stylesheet_name . ' file, you may try setting the style-custom.css file permission to 755 or 777 to solved this. ( If file does not exists, you have to create it yourself )');
		
		// initial the value of the style
		$file_path = SERVER_PATH . "/" . $gdl_custom_stylesheet_name;
		$gdl_fh = @fopen($file_path, 'w');
		
		if( !$gdl_fh ){ die( json_encode($return_data) ); }
		
		gdl_get_style_custom_content();
		
		// close data
		fclose($gdl_fh);	
	}
	
	// This function write the files to the admin panel
	function gdl_write_data( $string ){
		global $gdl_fh;
		fwrite( $gdl_fh, $string . "\r\n" );
	}
	
	// help print the css easier
	function gdl_print_style( $selector, $content ){
		if( empty($content) ) return;
		gdl_write_data( $selector . '{ ' . $content  . '} ');
	}
	
	// help to print the attribute easier
	function gdl_style_att( $attribute, $value, $important = false ){
		if( $value == 'transparent' || $value == '"default -"' ) return '';
		if( $important ) return $attribute . ': ' . $value . ' !important; ';
		return $attribute . ': ' . $value . '; ';
	}
	
	function gdl_get_style_custom_content(){
		global $goodlayers_element, $goodlayers_menu;	
		
		$color_menus = $goodlayers_menu['Elements Color'];
		foreach( $color_menus as $color_menu_slug ){
			foreach( $goodlayers_element[$color_menu_slug] as $element ){
				$temp_att = '';
				if( !empty( $element['attr'] ) && !empty( $element['selector'] ) ){
					foreach( $element['attr'] as $attr ){
						$temp_att = $temp_att . gdl_style_att( $attr, get_option($element['name'], $element['default']) );
					}	
					gdl_print_style( $element['selector'], $temp_att );
				}
			}
		}
				
		// Background Style
		$background_style = get_option(THEME_SHORT_NAME.'_background_style', 'Pattern');
		if($background_style == 'Pattern'){
			$background_pattern = get_option(THEME_SHORT_NAME.'_background_pattern', '1');
			
			$temp_att = gdl_style_att( 'background-image', 'url(images/pattern/pattern-' . $background_pattern . '.png)' );
			gdl_print_style( 'html', $temp_att );
			
			if( $background_pattern == '1' ){
				gdl_write_data( 'body{ background: url("images/pattern/pattern-1-gimmick.png") center 0px repeat-y; } ');
			}
		}
		
		// Logo
		$temp_val = get_option(THEME_SHORT_NAME . "_logo_width", '');
		if( !empty($temp_val) ){
			$temp_att = gdl_style_att( 'max-width', $temp_val . 'px' );
			gdl_print_style( '.logo-wrapper a', $temp_att );		
		}
		$temp_att = gdl_style_att( 'padding-top', get_option(THEME_SHORT_NAME . "_logo_top_margin", '32') . 'px' );
		$temp_att = $temp_att . gdl_style_att( 'padding-bottom', get_option(THEME_SHORT_NAME . "_logo_bottom_margin", '32') . 'px' );
		gdl_print_style( '.logo-wrapper', $temp_att );
		$temp_att = gdl_style_att( 'padding-top', get_option(THEME_SHORT_NAME . "_navigation_top_margin", '35') . 'px' );
		gdl_print_style( 'ul.sf-menu li', $temp_att );	
		
		// Header Font	
		$temp_att = gdl_style_att( 'font-size', get_option(THEME_SHORT_NAME . "_header_title_size", '25') . 'px' );
		gdl_print_style( 'h1.gdl-header-title', $temp_att );			
		$temp_att = gdl_style_att( 'font-size', get_option(THEME_SHORT_NAME . "_content_size", '13') . 'px' );
		gdl_print_style( 'body', $temp_att );			
		$temp_att = gdl_style_att( 'font-size', get_option(THEME_SHORT_NAME . "_widget_title_size", '17') . 'px' );
		gdl_print_style( 'h3.custom-sidebar-title', $temp_att );			
		$temp_att = gdl_style_att( 'font-size', get_option(THEME_SHORT_NAME . "_h1_size", '30') . 'px' );		
		gdl_print_style( 'h1', $temp_att );	
		$temp_att = gdl_style_att( 'font-size', get_option(THEME_SHORT_NAME . "_h2_size", '25') . 'px' );
		gdl_print_style( 'h2', $temp_att );	
		$temp_att = gdl_style_att( 'font-size', get_option(THEME_SHORT_NAME . "_h3_size", '20') . 'px' );
		gdl_print_style( 'h3', $temp_att );	
		$temp_att = gdl_style_att( 'font-size', get_option(THEME_SHORT_NAME . "_h4_size", '18') . 'px' );
		gdl_print_style( 'h4', $temp_att );	
		$temp_att = gdl_style_att( 'font-size', get_option(THEME_SHORT_NAME . "_h5_size", '16') . 'px' );
		gdl_print_style( 'h5', $temp_att );	
		$temp_att = gdl_style_att( 'font-size', get_option(THEME_SHORT_NAME . "_h6_size", '15') . 'px' );
		gdl_print_style( 'h6', $temp_att );		
		 
		// Font Family
		$temp_att = gdl_style_att( 'font-family', '"' . substr(get_option(THEME_SHORT_NAME . "_content_font"), 2) . '"' );
		gdl_print_style( 'body', $temp_att );	
		$temp_att = gdl_style_att( 'font-family', '"' . substr(get_option(THEME_SHORT_NAME . "_header_font"), 2) . '"' );
		gdl_print_style( 'h1, h2, h3, h4, h5, h6', $temp_att );			
		$temp_att = gdl_style_att( 'font-family', '"' . substr(get_option(THEME_SHORT_NAME . "_slider_title_font"), 2) . '"' );
		gdl_print_style( '.gdl-slider-title', $temp_att );				
		$temp_att = gdl_style_att( 'font-family', '"' . substr(get_option(THEME_SHORT_NAME . "_stunning_text_font"), 2) . '"' );
		gdl_print_style( 'h1.stunning-text-title', $temp_att );		
		$temp_att = gdl_style_att( 'font-family', '"' . substr(get_option(THEME_SHORT_NAME . "_navigation_font"), 2) . '"' );
		gdl_print_style( 'div.navigation-wrapper', $temp_att );			
		
		// Icon Type
		$gdl_icon_type = get_option(THEME_SHORT_NAME.'_icon_type','dark');
			
			if( $gdl_icon_type == 'dark' ){
				gdl_write_data( '.blog-info-wrapper i, .gdl-blog-medium .blog-tag i{ color: #333333; }' );
			}else{
				gdl_write_data( '.blog-info-wrapper i{ color: #ffffff; }' ); 
			}
			
			$temp_att = gdl_style_att( 'background-image', 'url(images/icon/' . $gdl_icon_type . '/accordion-title-active.png)' );
			gdl_print_style( 'li.active span.accordion-icon, li.active span.toggle-box-icon', $temp_att );			
			$temp_att = gdl_style_att( 'background-image', 'url(images/icon/' . $gdl_icon_type . '/accordion-title.png)' );
			gdl_print_style( 'span.accordion-icon, span.toggle-box-icon', $temp_att );	
			$temp_att = gdl_style_att( 'background-image', 'url(images/icon/' . $gdl_icon_type . '/testimonial-quote.png)' );
			gdl_print_style( 'div.gdl-carousel-testimonial .testimonial-icon', $temp_att );		
			$temp_att = gdl_style_att( 'background-image', 'url(images/icon/' . $gdl_icon_type . '/nav-left.png)' );
			gdl_print_style( 'div.portfolio-carousel-wrapper .port-nav.left, .testimonial-navigation .testimonial-prev, div.single-portfolio .port-prev-nav a', $temp_att );				
			$temp_att = gdl_style_att( 'background-image', 'url(images/icon/' . $gdl_icon_type . '/nav-right.png)' );
			gdl_print_style( 'div.portfolio-carousel-wrapper .port-nav.right, .testimonial-navigation .testimonial-next, div.single-portfolio .port-next-nav a', $temp_att );	
			
			// Retina
			gdl_write_data( '@media only screen and (min--moz-device-pixel-ratio: 2), only screen and (-o-min-device-pixel-ratio: 2/1),' );
			gdl_write_data( 'only screen and (-webkit-min-device-pixel-ratio: 2), only screen and (min-device-pixel-ratio: 2) {' );
				$temp_att = gdl_style_att( 'background-image', 'url(images/icon/' . $gdl_icon_type . '/accordion-title-active@2x.png)' );
				gdl_print_style( 'li.active span.accordion-icon, li.active span.toggle-box-icon', $temp_att );			
				$temp_att = gdl_style_att( 'background-image', 'url(images/icon/' . $gdl_icon_type . '/accordion-title@2x.png)' );
				gdl_print_style( 'span.accordion-icon, span.toggle-box-icon', $temp_att );	
				$temp_att = gdl_style_att( 'background-image', 'url(images/icon/' . $gdl_icon_type . '/testimonial-quote@2x.png)' );
				gdl_print_style( 'div.gdl-carousel-testimonial .testimonial-icon', $temp_att );		
				$temp_att = gdl_style_att( 'background-image', 'url(images/icon/' . $gdl_icon_type . '/nav-left@2x.png)' );
				gdl_print_style( 'div.portfolio-carousel-wrapper .port-nav.left, .testimonial-navigation .testimonial-prev, div.single-portfolio .port-prev-nav a', $temp_att );				
				$temp_att = gdl_style_att( 'background-image', 'url(images/icon/' . $gdl_icon_type . '/nav-right@2x.png)' );
				gdl_print_style( 'div.portfolio-carousel-wrapper .port-nav.right, .testimonial-navigation .testimonial-next, div.single-portfolio .port-next-nav a', $temp_att );					
			gdl_write_data( '}' );

			// Personnal Widget
			$temp_att = gdl_style_att( 'background-image', 'url(images/icon/' . $gdl_icon_type . '/personnal-widget-left.png)' );
			gdl_print_style( 'div.personnal-widget-prev', $temp_att );	
			$temp_att = gdl_style_att( 'background-image', 'url(images/icon/' . $gdl_icon_type . '/personnal-widget-right.png)' );
			gdl_print_style( 'div.personnal-widget-next', $temp_att );				
			
			// Search Button
			$temp_att = gdl_style_att( 'background', 'url(images/icon/' . $gdl_icon_type . '/search-button.png) no-repeat center' );
			gdl_print_style( "div.gdl-search-button, div.custom-sidebar #searchsubmit", $temp_att );					
			$temp_att = gdl_style_att( 'background', 'url(images/icon/' . $gdl_icon_type . '/top-search-button.png) no-repeat right center;' );
			gdl_print_style( "div.top-search-wrapper input[type='submit']", $temp_att );
			
			// Sidebar bullet
			$temp_att = gdl_style_att( 'background', 'url(images/icon/' . $gdl_icon_type . '/li-arrow.png) no-repeat 0px center' );
			gdl_print_style( "div.custom-sidebar ul li", $temp_att );
		
		// Footer Icon Type
		$gdl_footer_icon_type = get_option(THEME_SHORT_NAME.'_footer_icon_type','light');
			
			// Footer Bullet
			$temp_att = gdl_style_att( 'background', 'url(images/icon/' . $gdl_footer_icon_type . '/li-arrow.png) no-repeat 0px center' );
			gdl_print_style( "div.footer-wrapper div.custom-sidebar ul li", $temp_att );
			
			// Search Icon
			$temp_att = gdl_style_att( 'background', 'url(images/icon/' . $gdl_footer_icon_type . '/search-button.png) no-repeat center' );
			gdl_print_style( "div.footer-wrapper div.custom-sidebar #searchsubmit", $temp_att );	

			// Personnal Widget
			$temp_att = gdl_style_att( 'background-image', 'url(images/icon/' . $gdl_footer_icon_type . '/personnal-widget-left.png)' );
			gdl_print_style( 'div.footer-wrapper div.personnal-widget-prev', $temp_att );	
			$temp_att = gdl_style_att( 'background-image', 'url(images/icon/' . $gdl_footer_icon_type . '/personnal-widget-right.png)' );
			gdl_print_style( 'div.footer-wrapper div.personnal-widget-next', $temp_att );	

			// copyright scroll top
			$temp_att = gdl_style_att( 'background-image', 'url(images/icon/' . $gdl_footer_icon_type . '/copyright-back-to-top.png)' );
			gdl_print_style( 'div.copyright-scroll-top', $temp_att );	
			
			// client section
			$temp_att = gdl_style_att( 'background-image', 'url(images/icon/' . $gdl_footer_icon_type . '/nav-left.png)' );
			gdl_print_style( 'div.footer-gallery-nav-left', $temp_att );				
			$temp_att = gdl_style_att( 'background-image', 'url(images/icon/' . $gdl_footer_icon_type . '/nav-right.png)' );
			gdl_print_style( 'div.footer-gallery-nav-right', $temp_att );			
			
			// Retina
			gdl_write_data( '@media only screen and (min--moz-device-pixel-ratio: 2), only screen and (-o-min-device-pixel-ratio: 2/1),' );
			gdl_write_data( 'only screen and (-webkit-min-device-pixel-ratio: 2), only screen and (min-device-pixel-ratio: 2) {' );
			$temp_att = gdl_style_att( 'background-image', 'url(images/icon/' . $gdl_footer_icon_type . '/copyright-back-to-top@2x.png)' );
			gdl_print_style( 'div.copyright-scroll-top', $temp_att );		
			$temp_att = gdl_style_att( 'background-image', 'url(images/icon/' . $gdl_footer_icon_type . '/nav-left@2x.png)' );
			gdl_print_style( 'div.footer-gallery-nav-left', $temp_att );				
			$temp_att = gdl_style_att( 'background-image', 'url(images/icon/' . $gdl_footer_icon_type . '/nav-right@2x.png)' );
			gdl_print_style( 'div.footer-gallery-nav-right', $temp_att );				
			gdl_write_data( '}' );
			
		// Carousel Icon Type
		$gdl_carousel_icon_type = get_option(THEME_SHORT_NAME.'_carousel_icon_type','light');	
		
		$temp_att = gdl_style_att( 'background', 'url(images/icon/' . $gdl_carousel_icon_type . '/carousel-nav-left.png) no-repeat' );
		gdl_print_style( ".flex-carousel .flex-direction-nav li a.flex-prev", $temp_att );
		$temp_att = gdl_style_att( 'background', 'url(images/icon/' . $gdl_carousel_icon_type . '/carousel-nav-right.png) no-repeat' );
		gdl_print_style( ".flex-carousel .flex-direction-nav li a.flex-next", $temp_att );		

		/*--- Custom value that can't resides in goodlayers option array ---*/
		
		// Contact Form 
		$temp_val_frame = get_option(THEME_SHORT_NAME.'_contact_form_frame_color', '#f7f7f7');
		$temp_val_shadow = get_option(THEME_SHORT_NAME.'_contact_form_inner_shadow', '#ececec');
		$temp_sel = 'div.contact-form-wrapper input[type="text"], div.contact-form-wrapper input[type="password"], div.contact-form-wrapper textarea, ';
		$temp_sel = $temp_sel . 'div.sidebar-wrapper #search-text input[type="text"], ';
		$temp_sel = $temp_sel . 'div.sidebar-wrapper .contact-widget input, div.custom-sidebar .contact-widget textarea, ';
		$temp_sel = $temp_sel . 'div.comment-wrapper input[type="text"], div.comment-wrapper input[type="password"], div.comment-wrapper textarea';
		//$temp_sel = $temp_sel . 'span.wpcf7-form-control-wrap input[type="text"], span.wpcf7-form-control-wrap input[type="password"], ';
		//$temp_sel = $temp_sel . 'span.wpcf7-form-control-wrap textarea';
		$temp_att = gdl_style_att( 'color', get_option(THEME_SHORT_NAME.'_contact_form_text_color', '#888888') );
		$temp_att = $temp_att . gdl_style_att( 'background-color', get_option(THEME_SHORT_NAME.'_contact_form_background_color', '#fff') );
		$temp_att = $temp_att . gdl_style_att( 'border-color', get_option(THEME_SHORT_NAME.'_contact_form_border_color', '#e3e3e3') );
		$temp_att = $temp_att . gdl_style_att( '-webkit-box-shadow', $temp_val_shadow . ' 0px 1px 4px inset, ' . $temp_val_frame . ' -5px -5px 0px 0px, ' . $temp_val_frame . ' 5px 5px 0px 0px, ' . $temp_val_frame . ' 5px 0px 0px 0px, ' . $temp_val_frame . ' 0px 5px 0px 0px, ' . $temp_val_frame . ' 5px -5px 0px 0px, ' . $temp_val_frame . ' -5px 5px 0px 0px ' );
		$temp_att = $temp_att . gdl_style_att( 'box-shadow', $temp_val_shadow . ' 0px 1px 4px inset, ' . $temp_val_frame . ' -5px -5px 0px 0px, ' . $temp_val_frame . ' 5px 5px 0px 0px, ' . $temp_val_frame . ' 5px 0px 0px 0px, ' . $temp_val_frame . ' 0px 5px 0px 0px, ' . $temp_val_frame . ' 5px -5px 0px 0px, ' . $temp_val_frame . ' -5px 5px 0px 0px ' );
		gdl_print_style( $temp_sel, $temp_att );
		
		// Additional Style From The admin panel > general > page style
		gdl_write_data(get_option(THEME_SHORT_NAME.'_additional_style', ''));

		// Show/Hide  Post/Portfolio Information
		$temp_val = get_option(THEME_SHORT_NAME.'_show_post_tag','Yes');
		if( $temp_val == 'No' ){ gdl_write_data( 'div.blog-tag{ display: none; }' ); }
		$temp_val = get_option(THEME_SHORT_NAME.'_show_post_comment_info','Yes');
		if( $temp_val == 'No' ){ gdl_write_data( 'div.blog-comment{ display: none; }' ); }
		$temp_val = get_option(THEME_SHORT_NAME.'_show_post_author_info','Yes');
		if( $temp_val == 'No' ){ gdl_write_data( 'div.blog-author{ display: none; }' ); }	
		
		// Stunning Text Button
		$temp_val = get_option(THEME_SHORT_NAME . '_stunning_text_top_border', '#f16337');
		gdl_write_data('div.stunning-text-wrapper .stunning-text-button-mobile, ');
		gdl_write_data('div.stunning-text-wrapper .stunning-text-button-wrapper{ ');
		gdl_write_data('background: ' . $temp_val . '; ');
		gdl_write_data('}');
		
		// Accordion Gradient
		$temp_val = get_option(THEME_SHORT_NAME.'_accordion_title_background','#eaeaea');
		$temp_val2 = gdl_hex_lighter( $temp_val );
		gdl_write_data( 'ul.gdl-accordion li .accordion-title, ul.gdl-toggle-box li .toggle-box-title{ ' );
		gdl_write_data( 'background: -ms-linear-gradient(top, ' . $temp_val2 . ',  ' . $temp_val . ');' );
		gdl_write_data( 'background: -moz-linear-gradient(top, ' . $temp_val2 . ',  ' . $temp_val . ');' );
		gdl_write_data( 'background: -webkit-gradient(linear, left top, left bottom, from(' . $temp_val2 . '), to(' . $temp_val . '));' );
		gdl_write_data( 'filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=' . $temp_val2 . ', endColorstr=' . $temp_val . ');' );
		gdl_write_data( '}');		
		
		// Testimonial Gradient
		$temp_val = get_option(THEME_SHORT_NAME.'_testimonial_slide_background','#eaeaea');
		$temp_val2 = gdl_hex_lighter( $temp_val );
		gdl_write_data( 'div.gdl-carousel-testimonial .testimonial-content{ ' );
		gdl_write_data( 'background: -ms-linear-gradient(top, ' . $temp_val2 . ',  ' . $temp_val . ');' );
		gdl_write_data( 'background: -moz-linear-gradient(top, ' . $temp_val2 . ',  ' . $temp_val . ');' );
		gdl_write_data( 'background: -webkit-gradient(linear, left top, left bottom, from(' . $temp_val2 . '), to(' . $temp_val . '));' );
		gdl_write_data( 'filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=' . $temp_val2 . ', endColorstr=' . $temp_val . ');' );
		gdl_write_data( '}');
		
		// Full slider top margin
		$temp_val = intval(get_option(THEME_SHORT_NAME.'_full_slider_content_margin','-130'));
		gdl_write_data( '@media only screen and (min-width: 1240px) {' );	
		gdl_write_data( 'div.content-outer-wrapper.full-container.boxed-style-enabled{ margin-top: ' . $temp_val . 'px; }' );	
		gdl_write_data( 'div.gdl-top-slider.full-container.boxed-style-enabled .ls-kingpower .ls-bottom-nav-wrapper,');
		gdl_write_data( 'div.gdl-top-slider.full-container.boxed-style-enabled .flex-control-nav-wrapper, div.gdl-top-slider.full-container.boxed-style-enabled .flex-caption,');
		gdl_write_data( 'div.gdl-top-slider.full-container.boxed-style-enabled .nivo-controlNav-wrapper, div.gdl-top-slider.full-container.boxed-style-enabled .nivo-caption,');
		gdl_write_data( 'div.gdl-top-slider.full-container.boxed-style-enabled .nivo-directionNav a.nivo-prevNav, div.gdl-top-slider.full-container.boxed-style-enabled .flex-direction-nav li .flex-prev, div.gdl-top-slider.full-container.boxed-style-enabled .ls-kingpower .ls-nav-prev,');
		gdl_write_data( 'div.gdl-top-slider.full-container.boxed-style-enabled .nivo-directionNav a.nivo-nextNav, div.gdl-top-slider.full-container.boxed-style-enabled .flex-direction-nav li .flex-next, div.gdl-top-slider.full-container.boxed-style-enabled .ls-kingpower .ls-nav-next{ margin-bottom: ' . (- $temp_val) . 'px; }');
		gdl_write_data( '}' );	
		
		// boxed Style
		global $gdl_is_responsive;
		$temp_val = get_option(THEME_SHORT_NAME.'_enable_boxed_style', 'enable');
		if( $temp_val == 'enable' ){
			gdl_write_data('div.boxed-style{ max-width: 1240px; margin-left: auto; margin-right: auto; }');		
			gdl_write_data('.content-outer-wrapper.boxed-container .header-bottom-bar,');			
			gdl_write_data('.gdl-top-slider.boxed-container,');			
			gdl_write_data('.bottom-slider-wrapper.boxed-container{ max-width: 1240px; margin: 0px auto; }');			
			
			$temp_val = get_option(THEME_SHORT_NAME.'_enable_responsive','enable');
			if( $temp_val == 'enable' ){
				gdl_write_data('@media only screen and (max-width: 767px) {');
				gdl_write_data('div.boxed-style{ max-width: 460px; }');
				gdl_write_data('div.boxed-container{ max-width: 460px; margin: 0px auto; }');
				gdl_write_data('}');
			}
		}else{
			$temp_val = get_option(THEME_SHORT_NAME.'_container_background', '#fff');
			
			gdl_write_data('html{ background-color: ' . $temp_val . '; }');
			gdl_write_data('div.container.main{ background: transparent !important; }');
			
		}
		
		// Default header title background
		$temp_val = get_option(THEME_SHORT_NAME.'_default_title_background_predefined', '1t');
		gdl_write_data('.page-header-wrapper{ background-image: url("images/title-background/title-bg-' . $temp_val . '.jpg"); }');
		
		$temp_val = get_option(THEME_SHORT_NAME.'_default_header_background', '');
		if( !empty($temp_val) ){
			$temp_val = wp_get_attachment_image_src( $temp_val , 'full' );
			gdl_write_data('.page-header-wrapper{ background-image: url("' . $temp_val[0] . '"); }');
		}
		
		$temp_val = get_option(THEME_SHORT_NAME.'_default_post_background', '');
		if( !empty($temp_val) ){
			$temp_val = wp_get_attachment_image_src( $temp_val , 'full' );
			gdl_write_data('body.single .page-header-wrapper{ background-image: url("' . $temp_val[0] . '"); }');
		}
		
		$temp_val = get_option(THEME_SHORT_NAME.'_default_search_background', '');
		if( !empty($temp_val) ){
			$temp_val = wp_get_attachment_image_src( $temp_val , 'full' );
			gdl_write_data('body.search .page-header-wrapper{ background-image: url("' . $temp_val[0] . '"); }');
		}
		
		$temp_val = get_option(THEME_SHORT_NAME.'_404_header_background', '');
		if( !empty($temp_val) ){
			$temp_val = wp_get_attachment_image_src( $temp_val , 'full' );
			gdl_write_data('body.error404 .page-header-wrapper{ background-image: url("' . $temp_val[0] . '"); }');
		}
		
		
		// hide cufon out
		global $all_font;
		
		$used_font = substr(get_option(THEME_SHORT_NAME.'_header_font'), 2);
		if($used_font != 'default -'){
			if($all_font[$used_font]['type'] == 'Cufon'){
				gdl_write_data('h1, h2, h3, h4, h5, h6{ visibility: hidden; }');
				gdl_write_data('.gdl-slider-title{ visibility: visible; }');
				gdl_write_data('.stunning-text-title{ visibility: visible; }');
			}
		}
		
		$used_font = substr(get_option(THEME_SHORT_NAME.'_navigation_font'), 2);
		if($used_font != 'default -'){
			if($all_font[$used_font]['type'] == 'Cufon'){
				gdl_write_data('div.navigation-wrapper{ visibility: hidden; }');
			}
		}		
		
		$used_font = substr(get_option(THEME_SHORT_NAME.'_slider_title_font'), 2);
		if($used_font != 'default -'){
			if($all_font[$used_font]['type'] == 'Cufon'){
				gdl_write_data('.gdl-slider-title{ visibility: hidden; }');
				gdl_write_data('.nivo-caption .gdl-slider-title{ visibility: visible; }');
			}
		}		
		
		$used_font = substr(get_option(THEME_SHORT_NAME.'_stunning_text_font'), 2);
		if($used_font != 'default -'){
			if($all_font[$used_font]['type'] == 'Cufon'){
				gdl_write_data('.stunning-text-title{ visibility: hidden; }');
			}
		}
	}
	
// -------------------------------------------------------------------------- //
// -------------------------------------------------------------------------- //
	
// function that print additional style for lt IE9
add_action('wp_head', 'gdl_ltie9_style');
function gdl_ltie9_style(){ 

// dropcap support	
?>	
<!--[if lt IE 9]>
<style type="text/css">
	div.gdl-search-form{ width: 205px; }
	div.shortcode-dropcap.circle,
	div.anythingSlider .anythingControls ul a, .flex-control-nav li a, 
	.nivo-controlNav a, ls-bottom-slidebuttons a{
		z-index: 1000;
		position: relative;
		behavior: url(<?php echo GOODLAYERS_PATH; ?>/stylesheet/ie-fix/PIE.php);
	}
	div.top-search-wrapper .search-text{ width: 185px; }
	div.top-search-wrapper .search-text input{ float: right; }
	div.logo-right-text-content { width: 400px !important; }
	
	span.portfolio-thumbnail-image-hover,
	span.hover-link, span.hover-video, span.hover-zoom{ display: none !important; }
	
	.portfolio-media-wrapper:hover span{ display: block !important; }
	.blog-media-wrapper:hover span{ display: block !important; }
	
	ul.gdl-accordion li, ul.gdl-toggle-box li{ overflow: hidden; }		
	
	div.logo-wrapper img{ float: left; }
	<?php
		$temp_val = get_option(THEME_SHORT_NAME . "_logo_width", '');
		if( !empty($temp_val) ){
			echo '.logo-wrapper{ overflow: hidden; width: ' . $temp_val . 'px !important; }';		
		}
	?>	
</style>
<![endif]-->
<?php 
}
?>
