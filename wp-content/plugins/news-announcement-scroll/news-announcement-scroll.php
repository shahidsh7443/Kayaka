<?php
/*
 * Plugin Name: News Announcement Scroll
 * Plugin URI: http://www.storeapps.org/product/news-announcement-scroll/
 * Description: A simple vertical scroll news widget for your WordPress website. Easy to use & no coding knowledge required.
 * Version: 8.8.4
 * Author: StoreApps
 * Author URI: http://www.storeapps.org/
 * Requires at least: 3.4
 * Tested up to: 4.7.1 
 * Text Domain:	news-announcement-scroll
 * Domain Path: /languages/
 * License: GPLv3
 */

if( preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'] ) ) { 
	die( 'You are not allowed to call this page directly.' ); 
}

global $wpdb, $wp_version, $gNews_db_version;
$gNews_db_version = "8.2";

define( "WP_G_NEWS_ANNOUNCEMENT", $wpdb->prefix . "news_announcement" );
define( 'WP_G_NEWS_HELP', 'https://wordpress.org/plugins/news-announcement-scroll/faq/' );

if ( ! defined( 'WP_G_NEWS_BASENAME' ) ) {
	define( 'WP_G_NEWS_BASENAME', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'WP_G_NEWS_PLUGIN_NAME' ) ) {
	define( 'WP_G_NEWS_PLUGIN_NAME', trim( dirname( WP_G_NEWS_BASENAME ), '/' ) );
}

if ( ! defined( 'WP_G_NEWS_PLUGIN_URL' ) ) {
	define( 'WP_G_NEWS_PLUGIN_URL', WP_PLUGIN_URL . '/' . WP_G_NEWS_PLUGIN_NAME );
}

if ( ! defined( 'WP_G_NEWS_ADMIN_URL' ) ) {
	define( 'WP_G_NEWS_ADMIN_URL', get_option( 'siteurl' ) . '/wp-admin/options-general.php?page=news-announcement-scroll' );
}

if ( ! defined( 'NAS_OFFICIAL' ) ) {
	define( 'NAS_OFFICIAL', 'If you like <strong>News Announcement Sroll</strong>, please consider leaving us a <a target="_blank" href="https://wordpress.org/support/plugin/news-announcement-scroll/reviews/?filter=5#new-post">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating. A huge thank you from StoreApps in advance!' );
}

if ( ! defined( 'NAS_URL' ) ) {
	define( 'NAS_URL', plugins_url().'/'.strtolower('news-announcement-scroll').'/' );
}

if ( ! defined( 'NAS_DONATE_URL' ) ) {
	define( 'NAS_DONATE_URL', 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=CPTHCDC382KVA' );
}


function news_announcement() {
	wp_register_script( 'nas_gannounce', NAS_URL . 'gAnnounce/gAnnounce.js', array('jquery') );
    wp_enqueue_script( 'nas_gannounce' );
	
	include_once( "gAnnounce/gAnnounce.php" );
}

function news_announcement_activation() {
	global $wpdb, $gNews_db_version;
	$gNews_pluginversion = "";
	$gNews_tableexists = "YES";
	$gNews_pluginversion = get_option( "gNews_pluginversion");

	if ( $wpdb->get_var( "show tables like '". WP_G_NEWS_ANNOUNCEMENT . "'" ) != WP_G_NEWS_ANNOUNCEMENT ) {
		$gNews_tableexists = "NO";
	}
	
	if ( ( $gNews_tableexists == "NO" ) || ( $gNews_pluginversion != $gNews_db_version ) ) {
		$sSql = "CREATE TABLE ". WP_G_NEWS_ANNOUNCEMENT . " (
				 gNews_id mediumint(9) NOT NULL AUTO_INCREMENT,
				 gNews_text text NOT NULL,
				 gNews_order int(11) NOT NULL default '0',
				 gNews_status char(3) NOT NULL default 'YES',
				 gnews_redirect_link VARCHAR(255),
				 gNews_date DATE DEFAULT '0000-00-00' NOT NULL,	
				 gNews_expiration DATE DEFAULT '0000-00-00' NOT NULL, 
				 gNews_type VARCHAR(100) DEFAULT 'GROUP1' NOT NULL,
				 UNIQUE KEY gNews_id (gNews_id)
			    )  ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  		dbDelta( $sSql );
		
		if ( $gNews_pluginversion == "" ) {
			add_option( 'gNews_pluginversion', "8.3" );
		} else {
			update_option( "gNews_pluginversion", $gNews_db_version );
		}
		
		if ( $gNews_tableexists == "NO" ) {
			$welcome_text = "This plug-in will create a vertical scrolling announcement news <br><br> This plug-in will create a vertical scrolling announcement news";	
			$welcome_text1 = "Dieses Plug-in wird ein vertikales Scrollen Ankündigung news";
			$rows_affected = $wpdb->insert( WP_G_NEWS_ANNOUNCEMENT, array( 'gNews_text' => $welcome_text, 'gNews_type' => 'WIDGET' ) );
			$rows_affected = $wpdb->insert( WP_G_NEWS_ANNOUNCEMENT, array( 'gNews_text' => $welcome_text1, 'gNews_type' => 'SAMPLE' ) );
		}
	}
	//die();
	add_option( 'gNewsAnnouncementtitle', "Announcement" );
	add_option( 'gNewsAnnouncementfont', 'verdana,arial,sans-serif' );
	add_option( 'gNewsAnnouncementfontsize', '11px' );
	add_option( 'gNewsAnnouncementfontweight', 'normal' );
	add_option( 'gNewsAnnouncementfontcolor', '#FF0000' );
	add_option( 'gNewsAnnouncementwidth', '180' );
	add_option( 'gNewsAnnouncementheight', '100' );
	add_option( 'gNewsAnnouncementslidedirection', '0' );
	add_option( 'gNewsAnnouncementslidetimeout', '3000' );
	add_option( 'gNewsAnnouncementtextalign', 'center' );
	add_option( 'gNewsAnnouncementtextvalign', 'middle' );
	add_option( 'gNewsAnnouncementnoannouncement ', 'No announcement available' );
	add_option( 'gNewsAnnouncementorder', '0' );
	add_option( 'gNewsAnnouncementtype', 'widget' );
	add_option( '_current_nas_db_version','8.8.3' );

	if ( ! is_network_admin() && ! isset( $_GET['activate-multi'] ) ) {
		set_transient( '_nas_activation_redirect', 1, 30 );
	}
}

function database_update_for_8_8_3() {
	global $wpdb;

    $sql = "ALTER TABLE ". WP_G_NEWS_ANNOUNCEMENT." ADD gnews_redirect_link VARCHAR(255) AFTER gNews_status";
	$result = $wpdb->query($sql);

	if ( true === $result ) {
		update_option( '_current_nas_db_version', '8.8.3' );
	}
}

function nas_database_update() {
	$nas_db_version = get_option( '_current_nas_db_version', 'no' );
	if ( $nas_db_version == 'no' ) {
	    database_update_for_8_8_3();		
	}
}

function nas_redirect_on_activation(){
    if ( ! get_transient( '_nas_activation_redirect' ) ) {
		return;
	}
		
	// Delete the redirect transient
	delete_transient( '_nas_activation_redirect' );

	wp_redirect( admin_url( 'options-general.php?page=news-announcement-scroll' ) );
	exit;
}

function news_announcement_admin_options() {
	global $wpdb;
	$current_page = isset( $_GET['ac'] ) ? $_GET['ac'] : '';
 	
    wp_register_script( 'nas_gAnnounceform', NAS_URL . 'gAnnounce/gAnnounceform.js', array('jquery') );
    wp_enqueue_script( 'nas_gAnnounceform' );
     
    wp_register_script( 'nas_noenter', NAS_URL . 'gAnnounce/noenter.js',array('jquery') );
    wp_enqueue_script( 'nas_noenter' );
	
	switch( $current_page ) {
        case 'edit':
			include( 'pages/content-management-edit.php' );
			break;
		case 'add':
			include( 'pages/content-management-add.php' );
			break;
		case 'set':
			include( 'pages/content-setting.php' );
			break;
		default:
			include( 'pages/content-management-show.php' );
			break;
	}
}

function widget_news_announcement( $args ) {
  extract( $args );
  echo $before_widget;
  echo $before_title;
  echo get_option( 'gNewsAnnouncementtitle' );
  echo $after_title;

  wp_register_script( 'nas_gannounce', NAS_URL . 'gAnnounce/gAnnounce.js', array('jquery') );
  wp_enqueue_script( 'nas_gannounce' );

  news_announcement();
  echo $after_widget;
}

function news_announcement_widget_control() {
 //No action required
}

function news_announcement_plugins_loaded() {
	if( function_exists( 'wp_register_sidebar_widget' ) ) {
		wp_register_sidebar_widget( __( 'News announcement scroll', 'news-announcement-scroll' ), __( 'News Announcement Scroll', 'news-announcement-scroll' ), 'widget_news_announcement' );
	}

	if(function_exists('wp_register_widget_control')) {
		wp_register_widget_control(__('News announcement scroll', 'news-announcement-scroll'), array(__('News announcement scroll', 'news-announcement-scroll'), 'widgets'), 'news_announcement_widget_control');
	}
}

function news_announcement_add_to_menu() {
	add_options_page( __( 'News announcement scroll', 'news-announcement-scroll' ), __( 'News Announcement Scroll', 'news-announcement-scroll' ), 'manage_options', 'news-announcement-scroll', 'news_announcement_admin_options' );
}

if ( is_admin() ) {
	add_action( 'admin_menu', 'news_announcement_add_to_menu' );
}

function news_shortcode( $atts ) {
	global $wpdb;

	wp_register_script( 'nas_gannounce', NAS_URL . 'gAnnounce/gAnnounce.js', array('jquery') );
    wp_enqueue_script( 'nas_gannounce' );
	
	$nas =  "";
	$Ann = "";
	
	if ( ! is_array( $atts ) ) {
		return '';
	}

	$gNewsAnnouncementtype = $atts['group'];
	
	$sSql = "SELECT * from ". WP_G_NEWS_ANNOUNCEMENT . " where gNews_status='YES'";
	$sSql = $sSql . " and (`gNews_date` <= NOW() or `gNews_date` = '0000-00-00')";
	$sSql = $sSql . " and (`gNews_expiration` >= NOW() or `gNews_expiration` = '0000-00-00')";
	
	if ( $gNewsAnnouncementtype != "" ) { 
		$sSql = $sSql . " and gNews_type='".$gNewsAnnouncementtype."'"; 
	}
	
	if ( get_option( 'gNewsAnnouncementorder' ) == "1" ) {
		$sSql = $sSql . " ORDER BY rand()";
	} else {
		$sSql = $sSql . " ORDER BY gNews_order";
	}
	
	$data = $wpdb->get_results( $sSql );
	
	$nas = $nas .'<script language="JavaScript" type="text/javascript">';
	$nas = $nas .'v_font="'.get_option( 'gNewsAnnouncementfont' ).'"; ';
	$nas = $nas .'v_fontSize="'.get_option( 'gNewsAnnouncementfontsize' ).'"; ';
	$nas = $nas .'v_fontSizeNS4="'.get_option( 'gNewsAnnouncementfontsize' ).'"; ';
	$nas = $nas .'v_fontWeight="'.get_option( 'gNewsAnnouncementfontweight' ).'"; ';
	$nas = $nas .'v_fontColor="'.get_option( 'gNewsAnnouncementfontcolor' ).'"; ';
	$nas = $nas .'v_textDecoration="none"; ';
	$nas = $nas .'v_fontColorHover="#FFFFFF"; ';
	$nas = $nas .'v_textDecorationHover="none"; ';
	$nas = $nas .'v_top=0;';
	$nas = $nas .'v_left=0;';
	$nas = $nas .'v_width='.get_option( 'gNewsAnnouncementwidth' ).'; ';
	$nas = $nas .'v_height='.get_option( 'gNewsAnnouncementheight' ).'; ';
	$nas = $nas .'v_paddingTop=0; ';
	$nas = $nas .'v_paddingLeft=0; ';
	$nas = $nas .'v_position="relative"; ';
	$nas = $nas .'v_timeout='.get_option( 'gNewsAnnouncementslidetimeout' ).'; ';
	$nas = $nas .'v_slideSpeed=1;';
	$nas = $nas .'v_slideDirection='.get_option( 'gNewsAnnouncementslidedirection' ).'; ';
	$nas = $nas .'v_pauseOnMouseOver=true; ';
	$nas = $nas .'v_slideStep=1; ';
	$nas = $nas .'v_textAlign="'.get_option( 'gNewsAnnouncementtextalign' ).'"; ';
	$nas = $nas .'v_textVAlign="'.get_option( 'gNewsAnnouncementtextvalign' ).'"; ';
	$nas = $nas .'v_bgColor="transparent"; ';
	$nas = $nas .'</script>';
	
	if ( ! empty( $data ) ) {

		foreach ( $data as $data ) { 
		
		if( ! empty( $data->gnews_redirect_link ) ) {
			$data->gNews_text = '<a href="'.$data->gnews_redirect_link.'" style="color:inherit;text-decoration:underline;font-weight:inherit" target="_blank">'.$data->gNews_text.'</a>';
		}
	    
	    $Ann = $Ann . "['','".$data->gNews_text."',''],";
	}
		
		$Ann = substr( $Ann, 0, ( strlen( $Ann )-1 ) );
		$nas = $nas .'<div id="display_news" style="padding-bottom:5px;padding-top:5px;">';
		$nas = $nas .'<script language="JavaScript" type="text/javascript">';
		$nas = $nas .'v_content=['.$Ann.']';
		$nas = $nas .'</script>';
        $nas = $nas .'</div>';
    }
    else {
		?>
	<div id="display_news">
	    <script language="JavaScript" type="text/javascript">
	        v_content=[['','<?php echo get_option('gNewsAnnouncementnoannouncement'); ?>',''],['','<?php echo get_option('gNewsAnnouncementnoannouncement'); ?>','']]
	    </script>
    </div>
	<?php 
}
	
	return $nas;
}

function news_announcement_deactivate() {
	// No action required.
}

function news_announcement_textdomain() {
	load_plugin_textdomain( 'news-announcement-scroll', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

function plugin_action_links( $links ) {
    $action_links = array(
            'docs' => '<a href="http://www.storeapps.org/knowledgebase_category/news-announcement-scroll/" target="_blank" title="' . __( 'Documentation', 'news-announcement-scroll' ) . '">' . __( 'Docs', 'news-announcement-scroll' ) . '</a>',
            'settings' => '<a href="' . admin_url( 'options-general.php?page=news-announcement-scroll' ) . '" title="' . esc_attr( __( 'View News Announcement Scroll Settings', 'news-announcement-scroll' ) ) . '">' . __( 'Settings', 'news-announcement-scroll' ) . '</a>'
        );
        
    return array_merge( $action_links, $links );
}

add_action( 'plugins_loaded', 'news_announcement_textdomain' );
add_action( 'plugins_loaded', 'news_announcement_plugins_loaded' );
add_action( 'admin_init', 'nas_redirect_on_activation' );
add_action( 'admin_init', 'nas_database_update' );
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'plugin_action_links' );
add_shortcode( 'news-announcement', 'news_shortcode' );
register_activation_hook( __FILE__, 'news_announcement_activation' );
register_deactivation_hook( __FILE__, 'news_announcement_deactivate' );

?>