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

</body>
</html>