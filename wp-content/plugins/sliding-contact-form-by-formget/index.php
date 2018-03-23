<?php
/*
  Plugin Name: Sliding Contact Form By Formget
  Plugin URI: http://www.formget.com
  Description:  Sliding Contact Form By Formget is a advance form builder tools. This plugin let you manage your formget account from your wordpress dashboard.
  Version: 2.6
  Author: FormGet
  Author URI: http://www.formget.com
 */
function fg_admin_notice() {
    $fg_iframe_form = get_option('fg_iframe_embed_code');
    $string = "iframe";
    $pos = strpos($fg_iframe_form, $string);
    if ($pos == false) {
        global $current_user;
        $user_id = $current_user->ID;
        /* Check that the user hasn't already clicked to ignore the message */
        if (!get_user_meta($user_id, 'admin_ignore_notice')) {
            ?>
            <div class="fg_trial-notify">
                <p class="fg_division_note">
                    Welcome to FormGet - You're almost ready to create your form <div class="fg_button_bindup"><a class="fg_button_prime" href='<?php echo admin_url('admin.php?page=fg_page'); ?>'>Click to Create.</a><?php printf(__('<a class="fg_hide_notice fg_button_prime", href="%1$s">Hide Notice</a>'), '?admin_nag_ignore=0'); ?></div></p>
            </div>
            <?php
        }
    }
}

if (isset($_GET['page']) &&  $_GET['page'] != 'fg_page') {
    add_action('admin_notices', 'fg_admin_notice');
}

function fg_admin_nag_ignore() {
    global $current_user;
    $user_id = $current_user->ID;
    /* If user clicks to ignore the notice, add that to their user meta */
    if (isset($_GET['admin_nag_ignore']) && '0' == $_GET['admin_nag_ignore']) {
        add_user_meta($user_id, 'admin_ignore_notice', 'true', true);
    }
}

add_action('admin_init', 'fg_admin_nag_ignore');

function fg_delete_user_entry() {
    global $current_user;
    $user_id = $current_user->ID;
    delete_user_meta($user_id, 'admin_ignore_notice', 'true', true);
}

register_deactivation_hook(__FILE__, 'fg_delete_user_entry');

function fg_add_style() {
    wp_enqueue_style('form_style_sheet1', plugins_url('css/formstyle.css', __FILE__));
}

add_action("init", "fg_add_style");
if (is_admin()) {

    function fg_option_style() {
        wp_enqueue_style('form1_style1_sheet1', plugins_url('css/fgstyle.css', __FILE__));
    }

    add_action("init", "fg_option_style");
}
//setting page
add_action('admin_menu', 'fg_menu_page');

function fg_menu_page() {
    add_menu_page('fg', 'Contact Form', 'manage_options', 'fg_page', 'fg_setting_page', plugins_url('image/favicon.ico', __FILE__), 119);
}

function fg_setting_page() {
    $url = plugins_url();
    ?>
    <div id="fg_videoContainer" >

    </div>
    <?php
    global $wpdb;
    //$fg_video_code = get_option('sliding_fg_hide_video');
    ?>
  

    <div id="fg_of_container" class="fg_wrap" style="margin-top: 20px;">
        <form id="fg_ofform" action="" method="POST">
            <div id="fg_header">
                <div class="fg_logo">
                    <h2>Sliding Contact Form By FormGet</h2>                   
                </div>              
<!--                <div class="fg_icon-option"> 			
                    <a href="http://www.formget.com/app/pricing" target="_blank">  <div  class="bounce_to" > Upgrade to Pro</div> </a>							
                </div>              -->
                <div class="clear"></div>
            </div>
            <div id="fg_main">
                <div id="fg_of-nav">
                    <ul>
                        <li> <a class="pn-view-a" href="#pn_content" title="Form Builder"> Form Builder </a></li>
                        <li> <a class="pn-view-a" href="#pn_displaysetting" title="Embed Code">Embed Code</a></li>
                        <li> <a class="pn-view-a" href="#pn_example" title="Form Template" id="tooltip_target">Form Examples</a></li>
                        <li> <a class="pn-view-a" href="#pn_template" title="Help">Help</a></li>	
                        <li> <a class="pn-view-a" href="#pn_contactus" title="Plugin Support">Plugin Support</a></li>
						<li> <a class="pn-view-a" href="#pn_extension" title="FormGet Extensions">Extensions</a></li>
                       <!--
					   <li> <a class="pn-view-a" href="#pn_review" title="Help"> Review </a></li>
                      --->
					</ul>
                </div>
                <div id="fg_content">				
                    <div class="fg_group" id="pn_content">
                        <h2>Contact Form Builder</h2>
                        <div class="fg_section section-text">
                            <h3 class="fg_heading"> Create your custom form by just clicking the fields on left side of the panel. If visibility problem occurs then click here >> <a href="http://www.formget.com/app" target="_blank">Building Contact Form</a></h3>
                            <iframe src="http://www.formget.com/app" name="iframe" id="iframebox" style="width:100%; height:750px; border:1px solid #dfdfdf; align:center;">
                            </iframe>
                        </div>
                    </div>					
                    <div class="fg_group" id="pn_example">
                        <h2>Get a new experience of designing forms with FormGet</h2>
                        <div class="fg_section section-text">
                            <h3 class="fg_heading" style="border: 1px solid #41a2cd; background-color: #19A0DB; color: white; font-family: arial; padding-left: 4px;"> Create online forms, share it and take your marketing efforts to a whole new level by increasing your visibility online . More exposure means more leads. And more leads means better chance of finding a respondent.</a></h3>
                            <iframe src="http://www.formget.com/form-templates/" name="iframe" id="iframebox" style="width:100%; height:750px; border:1px solid #dfdfdf; align:center;">
                            </iframe>
                        </div>
                    </div>
                    <div class="fg_group" id="pn_displaysetting">
                        <h2>Embed Code</h2>
                        <div class="fg_section section-text">
                            <h3 class="fg_heading">To show sliding FormGet form, login to your FormGet Account, Click on Embed Form >> Embed Full Form >> Copy the 2nd code "Embed form 2 Plain Form" >> Paste the form iFrame code in this textarea.</h3>
                            <div class="option">
                                <div class="fg_controls">
								<div class="wrap_out" style="width:1000px;">
								 <textarea name="content[html]" cols="60" rows="10"   class="regular-text"  id="fg_content_html" style="width:550px"><?php embeded_code(); ?></textarea>
                                    <img id="help_gif" src="<?php echo plugins_url('image/formget.gif', __FILE__); ?>" title="Iframe Code">
                                </div>
							<h3 class="fg_heading">Choose the direction to display the tab on your page in the respective direction. </h3>
                                    <div class="direction_box" id="direction_box">
                                        <?php
                                        global $wpdb;
                                        $fg_form_direction = get_option('fg_form_direction');
                                        //echo $fg_form_direction;
                                        echo "</br>";
                                        if ($fg_form_direction == null || $fg_form_direction == "left_side") {
                                            ?>
                                            <label for="fg_leftside">
                                                <input class="directio_value" type="radio" id="fg_leftside" name="fg_form" value="left_side" checked="checked" />Left
                                            </label></br>
                                            <label for="fg_rightside">
                                                <input class="directio_value" type="radio" id="fg_rightside" name="fg_form" value="right_side" />Right
                                            </label></br>
                                            <label for="fg_bottomside">
                                                <input class="directio_value" type="radio" id="fg_bottomside" name="fg_form" value="bottom_side" />Bottom
                                            </label> 

                                            <?php
                                        }
                                        if ($fg_form_direction == "right_side") {
                                            ?>
                                            <label for="fg_leftside">
                                                <input class="directio_value" type="radio" id="fg_leftside" name="fg_form" value="left_side"  />Left
                                            </label></br>
                                            <label for="fg_rightside">
                                                <input class="directio_value" type="radio" id="fg_rightside" name="fg_form" value="right_side" checked="checked" />Right
                                            </label></br>
                                            <label for="fg_bottomside">
                                                <input class="directio_value" type="radio" id="fg_bottomside" name="fg_form" value="bottom_side" />Bottom
                                            </label> 
                                            <?php
                                        }
                                        if ($fg_form_direction == "bottom_side") {
                                            ?>

                                            <label for="fg_leftside">
                                                <input class="directio_value" type="radio" id="fg_leftside" name="fg_form" value="left_side"  />Left
                                            </label></br>
                                            <label for="fg_rightside">
                                                <input class="directio_value" type="radio" id="fg_rightside" name="fg_form" value="right_side"  />Right
                                            </label></br>
                                            <label for="fg_bottomside">
                                                <input class="directio_value" type="radio" id="fg_bottomside" name="fg_form" value="bottom_side" checked="checked" />Bottom
                                            </label> 		

                                        <?php } ?>
                                    </div></br> </br>                                    
                                    <div class="color_selector">
                                        <h3 class="fg_heading">Paste or Select the background color that will complement your form</h3>
                                        <?php
                                        global $wpdb;
                                        $bg_color_value = get_option('fg_form_bgcolor');
                                        ?>
                                        <input class="background_color color {hash:true,caps:false}" value="<?php echo $bg_color_value; ?>" >
                                    </div><br /><br />	    
                                    <div class="color_selector">
                                        <h3 class="fg_heading">Select the contact us button color that will complement your form as well as your website. </h3>
                                        <?php
                                        global $wpdb;
                                        $button_bg_color = get_option('fg_button_bgcolor');
                                        ?>
                                        <input class=" button_color color {hash:true,caps:false}" value="<?php
                                        if ($button_bg_color == "") {
                                            echo "#a2b23d";
                                        } else {
                                            echo $button_bg_color;
                                        }
                                        ?>" >
                                    </div><br /><br />								   
                                    <div class="button_text">
                                        <h3 class="fg_heading">The text to be displayed in place of Contact Us. </h3>
                                        <?php
                                        global $wpdb;
                                        $contact_button_value = get_option('fg_button_text');
                                        ?>
                                        <input class="button_text_to_display" value="<?php
                                        if ($contact_button_value == "") {
                                            echo "Contact Us";
                                        } else {
                                            echo $contact_button_value;
                                        }
                                        ?>" >
                                    </div><br /><br />

                                    <input id="submit-form" class="fg_embed_code_save " type="button" value="Save Changes" name="submit_form" style="display:none; border: 1px solid #36caf0;"><br />			
                                    <div id="loader_img" align="center" style="margin-left:460px; display:none;">
                                        <img src="<?php echo plugins_url('image/ajax-loader.gif', __FILE__); ?>">
                                    </div>                                      
                                </div>
                            </div>
                        </div>
                    </div>					
                    <div class="fg_group" id="pn_template">
                        <h2>Watch Sliding Contact Form Plugin's Video Tutorial</h2>
                        <div class="fg_section section-text">
                            <div id="help_txt" style="width:900px;">
                                <iframe class="video_tobe_shown" width="700" height="400" src="//player.vimeo.com/video/84850928?autoplay=0&amp;loop=1&amp;rel=0&amp;wmode=transparent" frameborder="0" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen="" __idm_id__="3954689"></iframe>                        
                            </div>
                        </div>
                    </div>
                    <div class="fg_group" id="pn_contactus">
                        <iframe height='570' allowTransparency='true' frameborder='0' scrolling='no' style='width:100%;border:none'  src='http://www.formget.com/app/embed/form/qQvs-639'>Your Contact </iframe>
                    </div>
					<div class="fg_group" id="pn_extension">
                       <?php echo extension_list(); ?>
                    </div>
					<!---
                    <div class="fg_group" id="pn_review">
                        <h2>Review Us, and grab a chance of getting free Pro Account.</h2>
                        <div class="fg_section section-text" id="review_message">
                            If you like the plugin, review us on WordPress.org <br><br>
                            One lucky person every month will get a chance to win 2 months of FormGet Pro Account absolutely FREE.						   
                            <br><br>  <div class="review_link" style="float: right;"> 
                                <a href="http://wordpress.org/support/view/plugin-reviews/sliding-contact-form-by-formget" target="_blank" style="color: white; text-decoration:none;"> Leave Review Here </a>
                            </div>
                        </div>
                    </div>	
					-->
					
                </div>
                <div class="clear"></div>
            </div>
            <div class="fg_save_bar_top">
            </div>
        </form>
    </div>
    <?php
}

function extension_list() {
    $extensions = array(
        array('img_path' => plugins_url("image/bundle.png", __FILE__),
            'ext_title' => 'FormGet Extensions Bundle',
            'ext_desc' => 'Extensions bundle gives you access to all FormGet extensions at one single price. This extension bundle includes more than 30 different extensions and several integration.',
            'ext_url' => 'http://www.formget.com/app/extension/fg_extension/all-1'),
        array('img_path' => plugins_url("image/custom.png", __FILE__),
            'ext_title' => 'Custom Branding',
            'ext_desc' => 'This extensions allows you to remove and edit any FormGet branding from your forms. It allows you to quickly transform your FormGet form into your own branded form.',
            'ext_url' => 'http://www.formget.com/app/single_extension/pricing/formcapabilities-custombranding'),
        array('img_path' => plugins_url("image/paypal.png", __FILE__),
            'ext_title' => 'PayPal',
            'ext_desc' => 'The PayPal Extension helps you to collect online payments through your forms. It accept credit card payments through forms instantly.',
            'ext_url' => 'http://www.formget.com/app/single_extension/pricing/payment-paypal'),
        array('img_path' => plugins_url("image/email.png", __FILE__),
            'ext_title' => 'Email Notification',
            'ext_desc' => 'With this extension whenever a user fills out the form you will recieve an email notification notifying you about the form submission. This way you wont miss any of the form entries.',
            'ext_url' => 'http://www.formget.com/app/single_extension/pricing/notification-emailnotification'),
        array('img_path' => plugins_url("image/mailc.jpg", __FILE__),
            'ext_title' => 'MailChimp',
            'ext_desc' => 'The MailChimp Extension helps you to store form messages on MailChimp mailing list. Thus help in collecting leads.',
            'ext_url' => 'http://www.formget.com/app/single_extension/pricing/thirdparty-mailchimp'),
        array('img_path' => plugins_url("image/aweber.jpg", __FILE__),
            'ext_title' => 'Aweber',
            'ext_desc' => 'The Aweber Extension helps you to store your collected leads through forms on your Aweber mailing list.',
            'ext_url' => 'http://www.formget.com/app/single_extension/pricing/thirdparty-aweber'),
        array('img_path' => plugins_url("image/logical.jpg", __FILE__),
            'ext_title' => 'Logical Forms',
            'ext_desc' => 'The Logical Forms Extension helps you to create form with condition logics that apply to form fields.',
            'ext_url' => 'http://www.formget.com/app/single_extension/pricing/formcapabilities-logicalforms')
    );
    $list = '';
	
	$list .= "<div class='ext_head'><h2>FormGet Powerful Extension</h2>";
    foreach ($extensions as $extension) {

        $list .="<div class='block-content'>
		<div class='block-left'><img src='" . $extension['img_path'] . "' class='entension-image'></div>
		<div class='block-right'>
			<h3>" . $extension['ext_title'] . "</h3>
			<a href='" . $extension['ext_url'] . "' target='_blank' class='view'>View Extension</a>
			<p class='details'>" . $extension['ext_desc'] . "</p>	
		</div>
	</div>";
    }
	$list .= "</div>";
    return $list;
}


function fg_colorpicker_script() {
    wp_enqueue_script('colorpicker_script', plugins_url('jscolor/jscolor.js', __FILE__), array('jquery'));
}

add_action('init', 'fg_colorpicker_script');

function fg_embeded_script() {
    wp_enqueue_script('embeded_script', plugins_url('js/fg_script.js', __FILE__), array('jquery'));
    wp_localize_script('embeded_script', 'script_call', array('ajaxurl' => admin_url('admin-ajax.php')));
}

add_action('init', 'fg_embeded_script');

function fg_text_ajax_process_request() {
    $text_value = $_POST['value'];
    $form_direction = $_POST['direction'];
    $form_background_color = $_POST['color'];
    $button_color = $_POST['button_color'];
    $button_text = $_POST['button_txt'];
    $val = $_POST['value_hide'];
    update_option('sliding_fg_hide_video', $val);
    update_option('fg_iframe_embed_code', $text_value);
    update_option('fg_form_direction', $form_direction);
    update_option('fg_form_bgcolor', $form_background_color);
    update_option('fg_button_bgcolor', $button_color);
    update_option('fg_button_text', $button_text);
    echo 1;
    die();
}

add_action('wp_ajax_master_response', 'fg_text_ajax_process_request');
//add_action('wp_ajax_nopriv_master_response', 'fg_text_ajax_process_request');

function fg_toggle_script() {
    wp_enqueue_script('toggle_script', plugins_url('js/toggle_script.js', __FILE__), array('jquery'));
}

add_action('init', 'fg_toggle_script');
if (!function_exists('embeded_code')) {

    function embeded_code() {
        global $wpdb;
        $fg_iframe_form = get_option('fg_iframe_embed_code');
        $string = "iframe";
        $pos = strpos($fg_iframe_form, $string);
        if ($pos == true) {
            echo stripslashes($fg_iframe_form);
        }
    }

}
if (!function_exists('display_iframe_form')) {

    function display_iframe_form() {
        global $wpdb;
        $fg_iframe_form = get_option('fg_iframe_embed_code');
        $fg_form_direction = get_option('fg_form_direction');
        $form_bg_color = get_option('fg_form_bgcolor');
        $contact_button_text = get_option('fg_button_text');
        $string = "iframe";
        $pos = strpos($fg_iframe_form, $string);
        if ($pos == true) {
            if ($fg_form_direction == "right_side") {
                ?>
                <div class="fg_left_form_container">
                    <div class="left_toggle_first">
                        <div class="fg_left_contact_button" id="fg_left_contact_button" style="background-color: <?php echo get_option('fg_button_bgcolor'); ?>">
                            <img src="http://www.formget.com/app/code/contact_tab?c=<?php echo get_option('fg_button_text'); ?> &amp;t_color=ffffff&amp;b_color=<?php echo str_replace("#", "", get_option('fg_button_bgcolor')); ?>&amp;f_size=18" alt="Feedback &amp; Support" >
                        </div>
                    </div>   
                    <div class="left_toggle_second">
                        <div class="fg_left_form_display" id="fg_left_form_display" style="overflow-y: scroll; background-color:<?php echo $form_bg_color; ?>">
                            <?php
                            echo stripslashes($fg_iframe_form);
                            ?>
                        </div>
                    </div>
                </div>
                <?php
            }
            if ($fg_form_direction == "left_side") {
                ?>
                <div class="fg_right_form_container">
                    <div class="right_toggle_first">
                        <div class="fg_right_contact_button" id="fg_right_contact_button" style="background-color: <?php echo get_option('fg_button_bgcolor'); ?>">
                            <img src="http://www.formget.com/app/code/contact_tab?c=<?php echo get_option('fg_button_text'); ?>&amp;t_color=ffffff&amp;b_color=7d9f2b&amp;f_size=18" alt="Feedback &amp; Support">
                        </div>
                    </div>   
                    <div class="right_toggle_second">
                        <div class="fg_right_form_display" id="fg_right_form_display" style="overflow-y: scroll; background-color:<?php echo $form_bg_color; ?>" >
                            <?php
                            echo stripslashes($fg_iframe_form);
                            ?>
                        </div>
                    </div>
                </div>
                <?php
            }
            if ($fg_form_direction == "bottom_side") {
                ?>
                <div class="fg_bottom_form_container">
                    <div class="bottom_toggle_first">
                        <div class="fg_bottom_contact_button" id="fg_bottom_contact_button" style="background-color: <?php echo get_option('fg_button_bgcolor'); ?>">
                            <p style="border:0; margin:0; padding-top: 8px; color: #ffffff; font-size:20px; "> <?php echo get_option('fg_button_text'); ?> </p>
                        </div>
                    </div>   
                    <div class="bottom_toggle_second">
                        <div class="fg_bottom_form_display" id="fg_bottom_form_display" style="overflow-y: scroll; background-color:<?php echo $form_bg_color; ?>" >
                            <?php
                            echo stripslashes($fg_iframe_form);
                            ?>                           
                        </div>
                    </div>
                </div> <?php
            }
        }
    }

}
add_action('wp_footer', 'display_iframe_form');
//schort code function
if (!function_exists('formget_shortcode')) {

    function formget_shortcode($atts, $content = null) {
        extract(shortcode_atts(array(
            'user' => '',
            'formcode' => '',
            'allowTransparency' => true,
            'height' => '500',
            'tab' => ''
                        ), $atts));
        $iframe_formget = '';
        $url = "http://www.formget.com/app/embed/form/" . $formcode;
        if ($tab == 'page') {
            $iframe_formget .="<iframe height='" . $height . "' allowTransparency='true' frameborder='0' scrolling='no' style='width:100%;border:none'  src='" . $url . "' >";
            $iframe_formget .="</iframe>";
            add_filter('widget_text', 'do_shortcode');
            return $iframe_formget;
        }
        if ($tab == 'tabbed') {
            $tabbed_formget = <<<EOD
<script type="text/javascript">
    var sideBar;
    (function(d, t) {
        var s = d.createElement(t),
                options = {
            'tabKey': '{$formcode}',
            'tabtext':'Contact Us',
            'height': '{$height}',
            'position': "",
            'textColor': 'ffffff',
            'tabColor': '7d9f2b',
            'fontSize': '16',
        };
        s.src = 'http://www.formget.com/app/app_data/user_js/widget.js';
        s.onload = s.onreadystatechange = function() {
            var rs = this.readyState;
            if (rs)
                if (rs != 'complete')
                    if (rs != 'loaded')
                        return;
            try {
                sideBar = new buildTabbed();
                sideBar.initializeOption(options);
                sideBar.loadContent();
                sideBar.buildHtml();
            } catch (e) {
            }
        };
        var scr = d.getElementsByTagName(t)[0], par = scr.parentNode;
        par.insertBefore(s, scr);
    })(document, 'script');
</script>
EOD;
            return $tabbed_formget;
        }
    }

}
add_shortcode('formget', 'formget_shortcode');


// FormGet Widget
if(! class_exists('Formget_Widget') ){
class Formget_Widget extends WP_Widget
{
      function __construct() {
parent::__construct(
'FormGet_widget', 

__('FormGet Widget', 'formget_widget'), 

array( 'description' => __( 'FormGet widget to show forms on sidebar and footer', 'formget_widget' ), ) 
);
}   

    public function widget( $args, $instance )
    {
	$text = format_to_edit($instance['textarea']);
	$pos = strpos($text, 'embed');
	
     ?>  
       <div class='formget_widget' style="<?php if($pos == True){ ?>  
-webkit-box-shadow: 1px 0px 11px rgba(50, 50, 50, 0.74);-moz-box-shadow: 1px 0px 11px rgba(50, 50, 50, 0.74);box-shadow: 1px 0px 11px rgba(50, 50, 50, 0.74); margin-bottom: 25px; padding-top: 15px; width:260px;  
	   <?php 	   
	   }?>">
	   <?php 
	
        echo $instance['textarea'];
		echo "</div>";
	  }

   
    public function update( $new_instance, $old_instance )
    {
        return $new_instance;
    }

    public function form( $instance )
    {
        $instance = wp_parse_args( (array) $instance, array( 'textarea' => '' ) );
        $text     = format_to_edit($instance['textarea']);
?>
      <p>
<label for="<?php echo $this->get_field_id('textarea'); ?>"><?php _e('Textarea:', 'wp_widget_plugin'); ?></label>
<textarea class="widefat" id="<?php echo $this->get_field_id('textarea'); ?>" name="<?php echo $this->get_field_name('textarea'); ?>" rows="7" cols="20" placeholder="Enter here your Iframe Code
"><?php echo $text ?></textarea>
</p>
<?php
        
        ! empty ( $text )
            and print '<h3>Preview</h3><div style="border:3px solid #369;padding:10px; margin-bottom: 5px;">'
                . $instance['textarea'] . '</div>';
              ?>
<?php
    }
}
}
add_action( 'widgets_init', 'register_formget_widget', 20 );
 
 if(! function_exists('register_formget_widget')){
function register_formget_widget()
{
    register_widget( 'Formget_Widget' );
}
}
?>