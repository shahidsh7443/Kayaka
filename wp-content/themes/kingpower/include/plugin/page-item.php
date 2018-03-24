<?php

	/*
	*	Goodlayers Page Item File
	*	---------------------------------------------------------------------
	* 	@version	1.0
	* 	@author		Goodlayers
	* 	@link		http://goodlayers.com
	* 	@copyright	Copyright (c) Goodlayers
	*	---------------------------------------------------------------------
	*	This file contains the function that can print each page item due to
	*	different conditions.
	*	---------------------------------------------------------------------
	*/

	$gdl_div_size_num_class = array(
		"1/6" => "two columns ", "element1-6" => "two columns ",
		"1/5" => "one-fifth column ", "element1-5" => "one-fifth column ",
		"1/4" => "three columns ", "element1-4" => "three columns ",
		"1/3" => "four columns ", "element1-3" => "four columns ",
		"1/2" => "six columns ", "element1-2" => "six columns ",
		"2/3" => "eight columns ", "element2-3" => "eight columns ",
		"3/4" => "nine columns ", "element3-4" => "nine columns ",
		"1/1" => "twelve columns ", "element1-1" => "twelve columns " );
	$gdl_class_to_num = array(
		"element1-6" => 1/6, "1/6"=>1/6,
		"element1-5" => 1/5, "1/5"=>1/5,
		"element1-4" => 1/4, "1/4"=>1/4,
		"element1-3" => 1/3, "1/3"=>1/3,
		"element1-2" => 0.5, "1/2"=>0.5,
		"element2-3" => 2/3, "2/3"=>2/3,
		"element3-4" => 3/4, "3/4"=>3/4,
		"element1-1" => 1, "1/1" => 1 );

	// Print the item size <div> with it's class
	function print_item_size($item_size, $item_row_size = '0', $addition_class='',
		$html_row_tag = 'div', $html_col_tag = 'div', $additional_style=''){
		global $gdl_div_size_num_class, $gdl_class_to_num;

		// init the first row
		if( empty($item_row_size) ){
			echo '<' . $html_row_tag .' class="row">';
		}

		$gdl_row_class = $gdl_div_size_num_class[$item_size] . $addition_class;
		$gdl_item_size = $gdl_class_to_num[$item_size];
		$item_row_size = $item_row_size + $gdl_item_size;

		if($item_row_size > 1){
			$item_row_size = $gdl_item_size;
			echo '<div class="clear"></div>';
			echo '</div>'; // close row
			echo '<' . $html_row_tag . ' class="row">'; // open new row
		}

		if( empty($additional_style) ){
			echo '<' . $html_col_tag . ' class="' . $gdl_row_class . '">';
		}else{
			echo '<' . $html_col_tag . ' class="' . $gdl_row_class . '" style="' . $additional_style . '">';
		}

		return $item_row_size;
	}

	// Print the item size <div> with it's class
	function return_item_size($item_size, $item_row_size = '0', $addition_class='', $html_row_tag = 'div', $html_col_tag = 'div'){
		global $gdl_div_size_num_class, $gdl_class_to_num;

		$return = "";

		// init the first row
		if( empty($item_row_size) ){
			$return = $return . '<' . $html_row_tag .' class="row">';
		}

		$gdl_row_class = $gdl_div_size_num_class[$item_size] . $addition_class;
		$gdl_item_size = $gdl_class_to_num[$item_size];
		$item_row_size = $item_row_size + $gdl_item_size;

		if($item_row_size > 1){
			$item_row_size = $gdl_item_size;
			$return = $return . '<div class="clear"></div>';
			$return = $return . '</div>'; // close row
			$return = $return . '<' . $html_row_tag . ' class="row">'; // open new row
		}

		$return = $return . '<' . $html_col_tag . ' class="' . $gdl_row_class . '">';

		return array( 'row-size'=>$item_row_size, 'return'=>$return);
	}

	// Print page header
	function print_page_header( $header, $caption = '' ){
		echo '<div class="page-header-wrapper boxed-style gdl-border-x bottom">';

		echo '<div class="page-header-container container">';
		echo '<div class="page-header-inner-wrapper">';
		echo '<div class="page-header-left-bar"></div>';
		echo '<div class="page-header-left-bar"></div>';
		echo '<h1 class="page-header-title">' . $header . '</h1>';
		echo '</div>'; // page header inner wrapper
		echo '<span class="page-header-caption">' . $caption . '</span>';
		echo '</div>'; // page header container

		echo '<div class="page-title-top-shadow"></div>';
		echo '<div class="page-title-bottom-shadow"></div>';

		if( get_option(THEME_SHORT_NAME.'_enable_top_search', 'enable') == 'enable' ){
			get_search_form();
		}

		echo '</div>'; // page header wrapper
	}

	// Print header of each item
	function print_item_header( $header, $addition_class = '' ){
		if(!empty($header)){
			echo '<div class="gdl-header-wrapper ' . $addition_class . '">';
			echo '<div class="gdl-header-left-bar"></div>';
			echo '<div class="gdl-header-left-bar"></div>';
			echo '<h3 class="gdl-header-title">' . $header . '</h3>';
			echo '<div class="gdl-header-divider"></div>';
			echo '<div class="clear"></div>';
			echo '</div>';
		}
	}

	// Print about us item
	function print_about_us_item($item_xml){
		global $about_us_size;

		print_item_header( find_xml_value($item_xml, 'header') );

		$image_id = find_xml_value($item_xml, 'image');
		$content = find_xml_value($item_xml, 'content');
		$button_text = find_xml_value($item_xml, 'button-text');
		$button_link = find_xml_value($item_xml, 'button-link');
		$enable_pdf = (find_xml_value($item_xml, 'enable-pdf') == 'Yes')? 'pdf-icon-on': 'pdf-icon-off';

		echo '<div class="about-us-wrapper">';
		if( !empty($image_id) ){
			$image_src = wp_get_attachment_image_src( $image_id , $about_us_size );
			$alt_text = get_post_meta($image_id , '_wp_attachment_image_alt', true);

			echo '<img class="about-us-image" src="' . $image_src[0] . '" alt="' . $alt_text . '">';
		}

		echo '<div class="about-us-content-wrapper">';
		echo '<div class="about-us-content">' . do_shortcode($content) . '</div>';
		if(!empty($button_link)){
			echo '<div class="about-us-button-wrapper ' . $enable_pdf . '">';
			echo '<a class="about-us-button gdl-button large" target="_blank" href="' . $button_link . '">' . $button_text . '</a>';
			echo '</div>';
		}
		echo '</div>'; // about-us-content-wrapper
		echo '</div>'; // about-us-wrapper

	}

	// Print accordion
	function print_accordion_item($item_xml){
		print_item_header( find_xml_value($item_xml, 'header') );

		$gdl_active = 'active';
		$tab_xml = find_xml_node($item_xml, 'tab-item');
		echo "<ul class='gdl-accordion'>";
		foreach($tab_xml->childNodes as $accordion){
			echo '<li class="' . $gdl_active . '">';
			echo '<h2 class="accordion-title"><span class="accordion-icon"></span>' . find_xml_value($accordion, 'title') . '</h2>';
			echo '<div class="accordion-content" >';
			echo do_shortcode(find_xml_value($accordion, 'caption')) . '</div>';
			echo '</li>';

			if( !empty($gdl_active) ) $gdl_active = '';
		}
		echo "</ul>";
	}

	// Print column
	function print_column_item($item_xml){
		print_item_header( find_xml_value($item_xml, 'header') );

		echo '<div class="gdl-column-item">';
		echo do_shortcode(html_entity_decode(find_xml_value($item_xml,'column-text')));
		echo '</div>';
	}

	// Print column service
	function print_column_service($item_xml){
		echo '<div class="column-service-wrapper">';

		$column_service_icon = find_xml_value($item_xml, 'icon');
		$column_service_title = find_xml_value($item_xml, 'title');
		$column_service_caption = html_entity_decode(find_xml_value($item_xml, 'caption'));

		if( !empty($column_service_icon) ){
			$icon_url = wp_get_attachment_image_src( $column_service_icon , 'full' );
			$alt_text = get_post_meta($column_service_icon , '_wp_attachment_image_alt', true);

			echo '<img class="column-service-icon" src="' . $icon_url[0] . '" alt="' . $alt_text . '">';
		}else{
			$column_service_icon = find_xml_value($item_xml, 'icon-class');
			if( !empty($column_service_icon) ){
			echo '<i class="column-service-icon ' . $column_service_icon . '"></i>';
			}
		}

		echo '<div class="column-service-content-wrapper">';
		echo '<h2 class="column-service-title">' . $column_service_title . '</h2>';
		echo '<div class="column-service-caption">' . do_shortcode($column_service_caption) . '</div>';
		echo '</div>'; // column service content wrapper
		echo '</div>'; // column service wrapper
	}

	// Print Content Item
	function print_content_item($item_xml){
		print_item_header( find_xml_value($item_xml, 'header') );

		wp_reset_query();
		if(have_posts()){
			while(have_posts()){
				the_post();
				the_content();
			}
		}
	}

	// Print Divider
	function print_divider($item_xml){
		echo '<div class="gdl-divider gdl-border-x bottom"><div class="scroll-top">';
		echo find_xml_value($item_xml, 'text');
		echo '</div></div>';
	}

	// Print featured media item
	function print_featured_media_item($item_xml){
		global $featured_media_size;

		print_item_header( find_xml_value($item_xml, 'header') );

		$media_type = find_xml_value($item_xml, 'type');
		$header_icon = find_xml_value($item_xml, 'header-icon');
		$title = find_xml_value($item_xml, 'title');
		$caption = find_xml_value($item_xml, 'caption');
		$content = find_xml_value($item_xml, 'content');
		$button_text = find_xml_value($item_xml, 'button-text');
		$button_link = find_xml_value($item_xml, 'button-link');

		$social = find_xml_value($item_xml, 'social');

		echo '<div class="featured-media-wrapper">';
		if( $media_type == 'Image' ){
			$image_id = find_xml_value($item_xml, 'image');
			$image_src = wp_get_attachment_image_src( $image_id , $featured_media_size );
			$alt_text = get_post_meta($image_id , '_wp_attachment_image_alt', true);

			echo '<div class="featured-media-image">';
			echo '<img src="' . $image_src[0] . '" alt="' . $alt_text . '">';
			echo '</div>';
		}else if( $media_type == 'Video' ){
			$video_id = find_xml_value($item_xml, 'video');

			echo '<div class="featured-media-video">';
			get_video($video_id, 640, 370);
			echo '</div>';
		}

		echo '<div class="featured-media-content-wrapper">';

		if( !empty($header_icon) ){
			echo '<div class="featured-media-header-icon"><i class="' . $header_icon . '"></i></div>';
		}

		echo '<div class="featured-media-title-wrapper">';
		echo '<div class="featured-media-left-bar"></div>';
		echo '<div class="featured-media-left-bar"></div>';
		echo '<h4 class="featured-media-title">' . $title . '</h4>';
		echo '<div class="clear"></div>';
		echo '<div class="featured-media-caption">' . $caption . '</div>';
		echo '</div>';

		echo '<div class="clear"></div>';
		echo '<div class="featured-media-content">' . do_shortcode($content) . '</div>';
		if(!empty($button_link)){
			echo '<a class="featured-media-button gdl-button large" href="' . $button_link . '">' . $button_text . '</a>';
		}
		echo '</div>'; // feature media content wrapper


		if(!empty($social)){
			echo '<div class="featured-media-social-wrapper">' . do_shortcode($social) .  '</div>';
		}
		echo '<div class="clear"></div>';
		echo '</div>'; // feature media wrapper

	}

	// Print gallery
	function print_gallery_item($item_xml){
		print_item_header( find_xml_value($item_xml, 'header') );

		global $gallery_div_size_num_class, $sidebar_type, $gdl_element_id;

		$gallery_row_size = 0;
		$gallery_page = find_xml_value($item_xml, 'page');
		$gallery_size = find_xml_value($item_xml, 'item-size');
		$item_size = $gallery_div_size_num_class[$gallery_size][$sidebar_type];

		$gallery_post = get_posts(array('post_type' => 'gdl-gallery', 'name'=>$gallery_page, 'numberposts'=> 1));

		echo '<div class="gdl-gallery-item">';

		$slider_xml_string = get_post_meta($gallery_post[0]->ID,'post-option-gallery-xml', true);
		$slider_xml_dom = new DOMDocument();
		if( !empty( $slider_xml_string ) ){
			$slider_xml_dom->loadXML($slider_xml_string);
			foreach( $slider_xml_dom->documentElement->childNodes as $slider ){
				$gallery_row_size = print_item_size($gallery_size, $gallery_row_size, 'mb40');
				$link_type = find_xml_value($slider, 'linktype');
				$image_url = wp_get_attachment_image_src(find_xml_value($slider, 'image'), $item_size);
				$alt_text = get_post_meta(find_xml_value($slider, 'image') , '_wp_attachment_image_alt', true);

				echo '<div class="gdl-gallery-image">';
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
				echo '</div>'; // gallery-thumbnail-image

				echo '</div>'; // print item size
			}
			echo '<div class="clear"></div>';
			echo '</div>'; // end row
			$gdl_element_id++;
		}

		echo '</div>'; // gdl gallery item
	}

	// Print Message Box
	function print_message_box($item_xml){
		$box_color = find_xml_value($item_xml, 'color');
		$box_title = find_xml_value($item_xml, 'title');
		$box_content = html_entity_decode(find_xml_value($item_xml, 'content'));
		echo '<div class="message-box-wrapper ' . $box_color . '">';
		echo '<div class="message-box-title">' . $box_title . '</div>';
		echo '<div class="message-box-content">' . do_shortcode($box_content) . '</div>';
		echo '</div>';
	}

	// Print personnal item
	function print_personnal_item($item_xml){
		print_item_header( find_xml_value($item_xml, 'header') );

		global $personnal_div_size_num_class, $sidebar_type;

		$num_fetch = find_xml_value($item_xml, 'num-fetch');
		$category = find_xml_value($item_xml, 'category', false);
		$category = ( $category == 'All' )? '': $category;

		$personnal_size = find_xml_value($item_xml, 'item-size');
		$personnal_row_size = 0;
		$item_size = $personnal_div_size_num_class[$personnal_size][$sidebar_type];

		$post_temp = query_posts(array('post_type'=>'personnal',
			'personnal-category'=>$category, 'posts_per_page'=>$num_fetch));

		echo '<div class="personnal-item-holder">';
		while( have_posts() ){
			the_post();

			$personnal_row_size = print_item_size($personnal_size, $personnal_row_size, 'personnal-item-wrapper');

			echo '<div class="personnal-item">';

			echo '<div class="personnal-title">';
			the_title();
			echo '</div>';

			$position = get_post_meta( get_the_ID(), 'personnal-option-position', true );
			if( !empty($position) ){
				echo '<div class="personnal-position">' . $position . "</div>";
			}

			$thumbnail_id = get_post_thumbnail_id();
			$thumbnail = wp_get_attachment_image_src( $thumbnail_id , $item_size );
			$alt_text = get_post_meta($thumbnail_id , '_wp_attachment_image_alt', true);
			if( !empty($thumbnail) ){
				echo '<div class="personnal-thumbnail"><img src="' . $thumbnail[0] . '" alt="' . $alt_text . '"></div>';
			}

			echo '<div class="personnal-content">';
			the_content();
			echo '</div>';

			echo '<div class="clear"></div>';
			echo '</div>'; // personnal item
			echo '</div>'; //close print_item_size
		}
		echo '<div class="clear"></div>';
		echo '</div>'; //close row
		echo '</div>';
	}

	// Print price item
	function print_price_item($item_xml){
		global $gdl_admin_translator;

		if( $gdl_admin_translator == 'enable' ){
			$translator_read_more = get_option(THEME_SHORT_NAME.'_translator_read_more_price', 'Read More');
		}else{
			$translator_read_more = __('Read More','gdl_front_end');
		}

		$price_item_row_size = 0;
		$price_item_number = find_xml_value($item_xml, 'item-number');
		$price_item_category = find_xml_value($item_xml, 'category', false);
		$price_item_category = ($price_item_category == 'All')? '': $price_item_category;

		$price_posts = get_posts(array('post_type'=>'price_table', 'price-table-category'=>$price_item_category,
			'numberposts'=>$price_item_number));

		echo '<div class="price-table-wrapper">';
		foreach($price_posts as $price_post){
			$best_price = get_post_meta( $price_post->ID, 'price-table-best-price', true );
			$best_price = ($best_price == 'Yes')? ' best-price':'';

			$price_item_row_size = print_item_size('1/' . $price_item_number,
				$price_item_row_size, 'price-item-wrapper wrapper mb0' . $best_price);
			echo '<div class="price-item">';
			echo '<div class="price-title" >' . $price_post->post_title . '</div>';

			echo '<div class="price-tag">';
			_e(get_post_meta( $price_post->ID, 'price-table-price-tag', true ), 'gdl_front_end');
			$suffix = __(get_post_meta( $price_post->ID, 'price-table-price-suffix', true ), 'gdl_front_end');
			if( !empty($suffix) ){ echo '<span class="price-suffix">' . $suffix . '</span>'; }
			echo '</div>';

			echo '<div class="price-content">';
			echo apply_filters('the_content', $price_post->post_content);
			echo '</div>';

			$price_url = __(get_post_meta( $price_post->ID, 'price-table-option-url', true ), 'gdl_front_end');
			if( !empty($price_url) ){
				echo '<div class="price-button-wrapper">';
				echo '<a class="price-button" target="_blank" href="' . $price_url . '">' . $translator_read_more . '</a>';
				echo '</div>';
			}

			echo '<div class="clear"></div>';
			echo '</div>'; // price item
			echo '</div>'; // print item size

		}
		echo '<div class="clear"></div>';
		echo '</div>'; // end row
		echo '</div>'; // price table wrapper
	}

	// Print postslider item
	function print_post_slider_item($item_xml){

		$num_fetch = find_xml_value($item_xml, 'num-fetch');
		$num_excerpt = find_xml_value($item_xml, 'num-excerpt');
		$show_caption = find_xml_value($item_xml, 'show-caption');

		$category = find_xml_value($item_xml, 'category', false);
		$category = ($category == 'All')? '': $category;

		$postslider_xml = "<single-item><Post-Slider>";
		$postslider_xml = $postslider_xml . create_xml_tag('size', find_xml_value($item_xml, 'size'));
		$postslider_xml = $postslider_xml . create_xml_tag('width', find_xml_value($item_xml, 'width'));
		$postslider_xml = $postslider_xml . create_xml_tag('height', find_xml_value($item_xml, 'height'));
		$postslider_xml = $postslider_xml . create_xml_tag('slider-type', find_xml_value($item_xml, 'slider-type'));
		$postslider_xml = $postslider_xml . "<slider-item>";

		query_posts(array('post_type'=>'post', 'category_name'=>$category, 'posts_per_page'=>$num_fetch ));

		while( have_posts() ){
			the_post();

			$postslider_xml = $postslider_xml . "<slider>";
			$postslider_xml = $postslider_xml . create_xml_tag('image', get_post_thumbnail_id(get_the_ID()) );
			$postslider_xml = $postslider_xml . create_xml_tag('linktype', 'Link to URL' );
			$postslider_xml = $postslider_xml . create_xml_tag('link', htmlspecialchars(get_permalink()) );
			if( $show_caption == "Yes" ){
				$postslider_xml = $postslider_xml . create_xml_tag('title', htmlspecialchars(get_the_title()) );
				$postslider_xml = $postslider_xml . create_xml_tag('caption', htmlspecialchars(gdl_get_excerpt($num_excerpt)) );
			}
			$postslider_xml = $postslider_xml . "</slider>";

		}

		$postslider_xml = $postslider_xml . "</slider-item>";
		$postslider_xml = $postslider_xml . "</Post-Slider></single-item>";

		$slider_xml_val = new DOMDocument();
		$slider_xml_val->loadXML($postslider_xml);
		foreach( $slider_xml_val->documentElement->childNodes as $slider_item_xml){
			print_slider_item($slider_item_xml);
		}

		wp_reset_query();
	}

	// Print stunning text
	function print_stunning_text($item_xml){

		$title = find_xml_value($item_xml, 'title');
		$caption = html_entity_decode(find_xml_value($item_xml, 'caption'));
		$button_text =  find_xml_value($item_xml, 'button-text');
		$button_link =  find_xml_value($item_xml, 'button-link');


		if( !empty($button_link) ){
			echo '<div class="stunning-text-wrapper button-on gdl-border-x top">';
		}else{
			echo '<div class="stunning-text-wrapper gdl-border-x top">';
		}
		echo '<div class="stunning-text-inner-wrapper gdl-border-x bottom">';

		if( !empty($button_link) ){
			echo '<div class="stunning-text-button-wrapper" >';
			echo '<a class="stunning-text-button" href="' . $button_link . '">';
			echo '<i class="stunning-text-icon icon-hand-right" ></i>';
			echo $button_text;
			echo '</a>';
			echo '</div>';
		}

		echo '<div class="stunning-text-content">';
		echo '<h1 class="stunning-text-title">' . $title . '</h1>';
		echo '<div class="stunning-text-caption">' . do_shortcode($caption) . '</div>';
		echo '</div>'; // stunning text content

		if( !empty($button_link) ){
			echo '<a class="stunning-text-button-mobile gdl-button large" href="' . $button_link . '">';
			echo $button_text;
			echo '</a>';
		}

		echo '<div class="clear"></div>';
		echo '</div>'; //stunning-text-inner-wrapper
		echo '</div>';

	}

	// Print Tab
	function print_tab_item($item_xml){
		print_item_header( find_xml_value($item_xml, 'header') );

		$num = 0;
		$tab_title = array();
		$tab_content = array();

		$tab_xml = find_xml_node($item_xml, 'tab-item');
		foreach($tab_xml->childNodes as $toggle_box){
			$tab_title[$num] = find_xml_value($toggle_box, 'title');
			$tab_content[$num] = html_entity_decode(find_xml_value($toggle_box, 'caption'));
			$num++;
		}

		echo '<div class="gdl-tab">';

		// tab title
		echo '<ul class="gdl-tab-title">';
		for($i=0; $i<$num; $i++){
			echo '<li><a data-tab="tab-' . $i . '" ';
			echo ( $i == 0 )? 'class="active" ':'';
			echo ' >' . $tab_title[$i] . '</a></li>';
		}
		echo "</ul>";

		// tab content
		echo '<div class="clear"></div>';
		echo "<ul class='gdl-tab-content'>";
		for($i=0; $i<$num; $i++){
			echo '<li data-tab="tab-' . $i . '" ';
			echo ( $i == 0 )? 'class="active"':'';
			echo ' >' . do_shortcode($tab_content[$i]) . '</li>';
		}
		echo "</ul>";

		echo '</div>'; // gdl tab
	}

	// Print Testimonial
	function print_testimonial($item_xml){
		$display_type = find_xml_value($item_xml, 'display-type');
		if( $display_type == 'Carousel Testimonial' ){
			$additional = 'navigation-on';
		}else{
			$additional = '';
		}

		print_item_header( find_xml_value($item_xml, 'header'), $additional );

		$num_fetch = find_xml_value($item_xml, 'num-fetch');
		$orderby = find_xml_value($item_xml, 'orderby');
		$order = find_xml_value($item_xml, 'order');

		$category = find_xml_value($item_xml, 'category', false);
		$category = ( $category == 'All' )? '': $category;

		$item_size = find_xml_value($item_xml, 'item-size');
		$testimonial_row_size = 0;

		query_posts(array( 'post_type'=>'testimonial', 'orderby'=>$orderby, 'posts_per_page'=>$num_fetch,
			'order'=>$order, 'testimonial-category'=>$category ));

		if( $display_type == 'Static Testimonial' ){
			echo '<div class="gdl-static-testimonial">';
			if( have_posts() ){
				while( have_posts() ){
					the_post();

					$testimonial_row_size = print_item_size($item_size, $testimonial_row_size, 'mb20');
					echo '<div class="testimonial-item">';

					// testimonial content
					echo '<div class="testimonial-content">';
					the_content();
					echo '</div>';

					// testimonial author
					$author = get_the_title();
					$position = get_post_meta( get_the_ID(), "testimonial-option-author-position", true );
					echo '<div class="testimonial-info">';
					echo '<span class="testimonial-author">' . $author . '</span>';
					if( !empty($position) ){
						echo ',<span class="testimonial-position"> ' . $position . '</span>';
					}
					echo '</div>';

					echo '</div>'; // testimonial item
					echo '</div>'; // close print_item_size
				}
			}
			echo '<div class="clear"></div>';
			echo '</div>'; // close row
			echo '</div>'; // gdl static testimonial
		}else if( $display_type == 'Carousel Testimonial' ){
			echo '<div class="gdl-carousel-testimonial">';

			// navigation
			echo '<div class="testimonial-navigation">';
			echo '<a class="testimonial-prev"></a>';
			echo '<a class="testimonial-next"></a>';
			echo '</div>';

			// content
			echo '<div class="testimonial-item-wrapper">';
			if( have_posts() ){
				while( have_posts() ){
					the_post();

					echo '<div class="testimonial-item">';

					// testimonial content
					echo '<div class="testimonial-content"><div class="testimonial-icon"></div>';
					echo '<div class="testimonial-inner-content">';
					the_content();
					echo '</div>';
					echo '</div>';

					// testimonial author
					$author = get_the_title();
					$position = get_post_meta( get_the_ID(), "testimonial-option-author-position", true );
					echo '<div class="testimonial-info">';
					echo '<span class="testimonial-author">' . $author . '</span>';
					if( !empty($position) ){
						echo ',<span class="testimonial-position"> ' . $position . '</span>';
					}
					echo '</div>';

					echo '</div>'; // testimonial item

				}
			}
			echo '</div>'; //testimonial-item-wrapper
			echo '</div>';
		}

		wp_reset_query();
	}

	// Print Toggle Box
	function print_toggle_box_item($item_xml){
		print_item_header( find_xml_value($item_xml, 'header') );

		$tab_xml = find_xml_node($item_xml, 'tab-item');
		echo "<ul class='gdl-toggle-box'>";
		foreach($tab_xml->childNodes as $toggle_box){
			$active = ( find_xml_value($toggle_box, 'active') == 'Yes' )? 'active': '';

			echo '<li class="' . $active . '">';
			echo '<h2 class="toggle-box-title"><span class="toggle-box-icon"></span>' . find_xml_value($toggle_box, 'title') . '</h2>';
			echo '<div class="toggle-box-content">';
			echo do_shortcode(html_entity_decode(find_xml_value($toggle_box, 'caption'))) . '</div>';
			echo '</li>';
		}
		echo '</ul>';
	}

	// Print the slider item
	function print_slider_item($item_xml){

		$xml_size = find_xml_value($item_xml, 'size');
		if( $xml_size == 'full-width' ){
			echo '<div class="gdl-slider-wrapper fullwidth"><div class="animatedParent"><div id="ytube" class="animated growIn slower"><iframe width="460" height="315" src="https://www.youtube.com/embed/2fz7b5Q-UgU?autoplay=1" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe></div></div>';
		}else{
			echo '<div class="gdl-slider-wrapper">';
		}

		$slider_width = find_xml_value($item_xml, 'width');
		$slider_height = find_xml_value($item_xml, 'height');
		if( !empty($slider_width) && !empty($slider_height) ){
			$xml_size = $slider_width . 'x' . $slider_height;
		}else if( $xml_size == 'full-width' ){
			$xml_size = 'full';
		}else{
			$xml_size = '980x360';
		}

		switch(find_xml_value($item_xml,'slider-type')){

			case 'Anything Slider':
				print_anything_slider(find_xml_node($item_xml,'slider-item'), $xml_size);
				break;

			case 'Nivo Slider':
				print_nivo_slider(find_xml_node($item_xml,'slider-item'), $xml_size);
				break;

			case 'Flex Slider':
				print_flex_slider(find_xml_node($item_xml,'slider-item'), $xml_size);
				break;

			case 'Carousel Slider':
				print_carousel_slider(find_xml_node($item_xml,'slider-item'), $xml_size);
				break;

		}

		echo "</div>";
	}

	// Print contact form
	function print_contact_form($item_xml){
		global $gdl_admin_translator;

		if( $gdl_admin_translator == 'enable' ){
			$gdl_name_string = get_option(THEME_SHORT_NAME.'_translator_name_contact_form', 'Name');
			$gdl_name_error_string = get_option(THEME_SHORT_NAME.'_translator_name_error_contact_form', 'Please enter your name');
			$gdl_email_string = get_option(THEME_SHORT_NAME.'_translator_email_contact_form', 'Email');
			$gdl_email_error_string = get_option(THEME_SHORT_NAME.'_translator_email_error_contact_form', 'Please enter a valid email address');
			$gdl_message_string = get_option(THEME_SHORT_NAME.'_translator_message_contact_form', 'Message');
			$gdl_message_error_string = get_option(THEME_SHORT_NAME.'_translator_message_error_contact_form', 'Please enter message');
			$gdl_submit_button = get_option(THEME_SHORT_NAME.'_translator_submit_contact_form','Submit');
		}else{
			$gdl_name_string = __('Name','gdl_front_end');
			$gdl_name_error_string =  __('Please enter your name','gdl_front_end');
			$gdl_email_string =  __('Email','gdl_front_end');
			$gdl_email_error_string =  __('Please enter a valid email address','gdl_front_end');
			$gdl_message_string =  __('Message','gdl_front_end');
			$gdl_message_error_string = __('Please enter message','gdl_front_end');
			$gdl_submit_button = __('Submit','gdl_front_end');
		}

	?>
		<div class="contact-form-wrapper">
			<form class="gdl-contact-form">
				<ol class="forms">
					<li class="form-input">
						<strong><?php echo $gdl_name_string; ?> *</strong>
						<input type="text" name="name" class="require-field" />
						<div class="error">* <?php echo $gdl_name_error_string; ?></div>
					</li>
					<li class="form-input">
						<strong><?php echo $gdl_email_string; ?> *</strong>
						<input type="text" name="email" class="require-field email" />
						<div class="error">* <?php echo $gdl_email_error_string; ?></div>
					</li>
					<li class="form-textarea"><strong><?php echo $gdl_message_string; ?> *</strong>
						<textarea name="message" class="require-field"></textarea>
						<div class="error">* <?php echo $gdl_message_error_string; ?></div>
					</li>
					<li class="hidden"><input type="hidden" name="receiver" value="<?php echo find_xml_value($item_xml, 'email'); ?>"></li>
					<li class="sending-result" id="sending-result" ><div class="message-box-wrapper green"></div></li>
					<li class="buttons">
						<button type="submit" class="contact-submit button"><?php echo $gdl_submit_button; ?></button>
						<div class="contact-loading">
					</li>
				</ol>
			</form>
			<div class="clear"></div>
		</div>
	<?php
	}

?>
