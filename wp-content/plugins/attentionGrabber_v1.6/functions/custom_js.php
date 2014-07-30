<?php

// Get the cookie
function get_ag_cookie( $keepHidden ){
	global $the_attentionGrabber;

	$found_cookie = "false";
	
	if( $keepHidden ){
		$cookie = $_COOKIE['attentionGrabber_active'];
		$val = $the_attentionGrabber["id"]+123;
		
		if( isset($cookie) && ($cookie == $val) ){
			$found_cookie = "true";
		}
	}
	return $found_cookie;
}

// return the custom js code
function get_custom_ag_js()
{
	global $the_attentionGrabber;
	global $attentionGrabber_core;

	$params = array(
		'position'		=> $attentionGrabber_core["position"],
		'showAfter'		=> $attentionGrabber_core["showAfter"],
		'keepHidden'	=> ( $attentionGrabber_core["keepHidden"] ) ? "true" : "false",
		'duration'		=> $attentionGrabber_core["animationDuration"],
		'closeable'		=> ( $attentionGrabber_core["closeable"] ) ? "true" : "false",
		'height'		=> $the_attentionGrabber["style_height"],
		'borderSize'	=> $the_attentionGrabber["style_borderSize"],
		'easing'		=> $attentionGrabber_core["animationEffect"],
		'foundCookie'	=> get_ag_cookie( $attentionGrabber_core["keepHidden"] ),
		'ajaxUrl'		=> admin_url('admin-ajax.php')
	);
	
	return $params;
}