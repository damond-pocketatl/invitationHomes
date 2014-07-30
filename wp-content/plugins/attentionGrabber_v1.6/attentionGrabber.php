<?php
/*
Plugin Name: Attention Grabber
Plugin URI: http://attentiongrabber.tommasoraspo.com/
Description: Grab the attention of your visitors by placing a simple, fully customizable banner on your site
Version: 1.6
Author: MTD
Author URI: http://codecanyon.net/user/MTD?ref=MTD
*/

include_once("functions/ajax.php");
include_once("functions/shortcodes.php");
require_once("functions/custom_js.php");
require_once("functions/custom_css.php");
require_once("functions/build_html.php");

$attentionGrabber_core = false;
$the_attentionGrabber = false;

// The current version
$attentionGrabber_version = "1.6";

$attentionGrabber_root_url = plugin_dir_url(__FILE__);

function attentionGrabber_init()
{
	
	global $attentionGrabber_core;
	global $the_attentionGrabber;
	global $attentionGrabber_version;
	
	// Get the core options
	$attentionGrabber_core = get_option("attentionGrabber_core");
	
	if( $attentionGrabber_core ){
		
		// Make sure to be using the latest version
		if( $attentionGrabber_core["version"] < $attentionGrabber_version ){
			attentionGrabber_update( $attentionGrabber_core["version"] );
		}
		
		// Should jquery be included by wordpress?
		$loadJquery = ( $attentionGrabber_core["includeJquery"] ) ? true : false; 
		
		// If this is a preview
		if( isset( $_GET["previewAttentionGrabber"] ) && is_user_logged_in() ){
		
			$grabberID = $_GET["previewAttentionGrabber"];
			
			$the_attentionGrabber = get_option( "attentionGrabber_".$grabberID );
			
			if( $the_attentionGrabber && !is_admin() ){
				// Load the plugin
				load_attentionGrabber( $loadJquery );
			}
		
		}else{
		
			// Check if there is an active attentionGrabber
			if( $attentionGrabber_core["active"] ){
				
				$the_attentionGrabber = get_option( "attentionGrabber_".$attentionGrabber_core["active"] );
				
				if( $the_attentionGrabber && !is_admin() ){
					// Load the plugin
					load_attentionGrabber( $loadJquery );
				}
			
			}
		
		}
		
	} else {
		save_attentionGrabber_defaults();
	}
}
add_action('init', 'attentionGrabber_init');


// Load attentionGrabber on the page
function load_attentionGrabber( $loadJquery )
{
	global $the_attentionGrabber;
	
	// Include jquery
	$dependences = ( $loadJquery ) ? array('jquery') : "";
	
	// Load the main css
	wp_enqueue_style( 'attentionGrabber_css', plugins_url( '/css/attentionGrabber.css', __FILE__ ) );
	wp_add_inline_style( 'attentionGrabber_css', get_custom_ag_css() );
	
	// Load the script
	wp_enqueue_script( 'attentionGrabber_script', plugins_url( '/js/attentionGrabber.js' , __FILE__ ), $dependences );
	wp_localize_script( 'attentionGrabber_script', 'attentionGrabber_params', get_custom_ag_js() );
	
	// Load the scripts required by certain plugins
	ag_shortcode_scripts( $the_attentionGrabber["custom_messageText"] );
	ag_shortcode_scripts( $the_attentionGrabber["advanced_content"] );
	
	// Load markup
	load_attentionGrabber_html();
}


// Include the scripts and CSS required in the admin area
function init_attentionGrabber_admin()
{
	require_once("functions/admin_ajax.php");

	if( isset($_GET["page"]) && $_GET["page"] == "attentionGrabber" ){
		// Load css and scripts
		wp_enqueue_style( 'attentionGrabber_admin_css', plugins_url( '/css/admin.css', __FILE__ ) );
		wp_enqueue_style( 'attentionGrabber_colorpicker_css', plugins_url( '/css/colorpicker.css', __FILE__ ) );
		wp_enqueue_script( 'attentionGrabber_colorpicker_script', plugins_url( '/js/colorpicker.js', __FILE__ ), array('jquery') );
		wp_enqueue_script( 'attentionGrabber_css_rule_script', plugins_url( '/js/jquery.rule.js', __FILE__ ), array('jquery') );
		wp_enqueue_script( 'attentionGrabber_admin_script', plugins_url( '/js/admin.js', __FILE__ ), array('jquery','jquery-ui-core','jquery-ui-slider') );
		wp_enqueue_style( 'thickbox' );
		wp_enqueue_script( 'thickbox' );
		
		
	}
}
add_action('admin_init', 'init_attentionGrabber_admin');

// Init scripts
function attentionGrabber_admin_head()
{
	global $attentionGrabber_version;
	global $attentionGrabber_core;
	
	if( isset($_GET["page"]) && $_GET["page"] == "attentionGrabber" ){
		
		// Check for updates when set to true
		if( $attentionGrabber_core['checkUpdates'] ){
		
			echo '<script>jQuery(document).ready(function($){ $.checkAttentionGrabberUpdates( '.$attentionGrabber_version.' ); });</script>';
		
		}
	}
}
add_action('admin_head', 'attentionGrabber_admin_head');


// Add the link in the dashboard menu
function add_attentionGrabber_to_menu()
{
	global $attentionGrabber_admin_page;
	$attentionGrabber_admin_page = add_menu_page("attentionGrabber", "attentionGrabber", "edit_pages", "attentionGrabber", "attentionGrabber_admin");

	// Include the contextual help
	global $attentionGrabber_admin_page;
	include_once( 'inc/admin_documentation.php' );
	add_action( 'load-' . $attentionGrabber_admin_page, 'attentionGrabber_admin_documentation' );
}
add_action('admin_menu', 'add_attentionGrabber_to_menu');


// Include the settings page
function attentionGrabber_admin()
{
	require_once("admin_view.php");
}

// Save the default settings on the DB
function save_attentionGrabber_defaults()
{
	global $attentionGrabber_version;
	
	$defaults_core = array(
		'version' 			=> $attentionGrabber_version,
		'active' 			=> false,
		'created' 			=> false,
		'nextID'			=> "1",
		
		'position'			=> "top",
		'borderPosition'	=> "bottom",
	
		'showAfter' 		=> "0",
		'animationDuration' => "300",
		'animationEffect'	=> "swing",
		
		'closeable'			=> true,
		'keepHidden'		=> true,
	
		'previewBg'			=> 'f7f7f7',
		'closeButtonStyle'	=> 'light',
	
		'newTab'			=> true,
		
		'includeJquery'		=> true
	);
	update_option( "attentionGrabber_core", $defaults_core );
}


// Update the plugin
function attentionGrabber_update( $current_version ){

	global $attentionGrabber_version;
	global $attentionGrabber_core;
	
	switch( $attentionGrabber_core['version'] )
	{
		case '1.0' :
			// Add the checkUpdates option to the DB
			$attentionGrabber_core['checkUpdates'] = true;
			
			$attentionGrabber_core['version'] = $attentionGrabber_version;
			update_option( "attentionGrabber_core", $attentionGrabber_core );	
		break;
		
		case '1.1' :
			$attentionGrabber_core['version'] = $attentionGrabber_version;
			update_option( "attentionGrabber_core", $attentionGrabber_core );	
		break;
		
		case '1.2' :
			$attentionGrabber_core['version'] = $attentionGrabber_version;
			update_option( "attentionGrabber_core", $attentionGrabber_core );	
		break;
		
		case '1.3' :
			$attentionGrabber_core['version'] = $attentionGrabber_version;
			update_option( "attentionGrabber_core", $attentionGrabber_core );	
		break;
		
		case '1.4' :
			$attentionGrabber_core['version'] = $attentionGrabber_version;
			update_option( "attentionGrabber_core", $attentionGrabber_core );	
		break;

		case '1.5' :
			$attentionGrabber_core['version'] = $attentionGrabber_version;
			update_option( "attentionGrabber_core", $attentionGrabber_core );	
		break;
		
		
		default :
			// Do nothing
		break;
	}
	
}
