<?php

// Helper function to faster display the options
function get_ag_style( $key ){
    global $the_attentionGrabber;
    return $the_attentionGrabber["style_".$key];
}

// return the custom css styling
function get_custom_ag_css()
{
	global $the_attentionGrabber;
	global $attentionGrabber_core;
	global $attentionGrabber_root_url;

	$css = '';

	$positionY      = preg_replace( "/_fixed/", "", $attentionGrabber_core["position"] );
	$totalHeight    = ( $the_attentionGrabber["style_height"] + $the_attentionGrabber["style_borderSize"] );

	$css .= '#attentionGrabberWrap{';
	if( preg_match( "/_fixed/", $attentionGrabber_core["position"] ) ){
		$css .= 'position: fixed;';
		$css .= 'left: 0;';
		$css .= $positionY.':-'.$totalHeight.'px;';
	} else {
		$css .= 'position: relative;';
		$css .= 'margin-top:-'.$totalHeight.'px;';
	}
	$css .= 'height:'.$totalHeight.'px;';
	$css .= '}';

	$css .= '#attentionGrabber{';
	$css .= 'height:'.get_ag_style("height").'px;';
	$css .= 'font:'.get_ag_style("fontStyle").' '.get_ag_style("fontSize").'px/'.get_ag_style("height").'px '.get_ag_style("fontFamily").';';
	$css .= 'color:#'.get_ag_style("textColor").';';
	if( $the_attentionGrabber["style_textShadowSize"] ){
		$css .= 'text-shadow:0 '.get_ag_style("textShadowSize").'px 0 #'.get_ag_style("textShadowColor").';';
	}
	$css .= 'background:#'.get_ag_style("bgColor").';';
	$css .= 'border-'.$attentionGrabber_core["borderPosition"].':'.get_ag_style("borderSize").'px solid #'.get_ag_style("borderColor").';';
	$css .= '}';

	$css .= '#attentionGrabber a{';
	$css .= 'font: '.get_ag_style("fontStyle").' '.get_ag_style("fontSize").'px/'.get_ag_style("height").'px '.get_ag_style("fontFamily").';';
	if( $the_attentionGrabber["style_linkShadowSize"] ){
		$css .= 'text-shadow: 0 '.get_ag_style("linkShadowSize").'px 0 #'.get_ag_style("linkShadowColor").';';
	} else if( $the_attentionGrabber["style_textShadowSize"] ) {
		$css .= 'text-shadow: none;';
	}
	$css .= 'color:#'.get_ag_style("linkColor").';';
	$css .= '}';

	$css .= '#attentionGrabber a:hover{';
	$css .= 'color:#'.get_ag_style("linkHoverColor").';';
	$css .= '}';

	$css .= '#attentionGrabberWrap #closeAttentionGrabber{';
	$css .= 'right:'.( $the_attentionGrabber["style_borderSize"] + 7 + 15 ).'px;';
	$css .= 'height:'.get_ag_style("height").'px;';
	$css .= 'background:url('.$attentionGrabber_root_url.'img/buttons/' .$attentionGrabber_core["closeButtonStyle"].'.png) no-repeat 0 center;';
	$css .= '}';

	$css .= '#attentionGrabberWrap #openAttentionGrabber{';
	$css .= $positionY.': -'.abs( 34 - $the_attentionGrabber["style_height"] ).'px;';
	$css .= 'background:#'.get_ag_style("bgColor").';';
	$css .= 'border-left:'.get_ag_style("borderSize").'px solid #'.get_ag_style("borderColor").';';
	$css .= 'border-right:'.get_ag_style("borderSize").'px solid #'.get_ag_style("borderColor").';';
	$css .= 'border-'.$attentionGrabber_core["borderPosition"].':'.get_ag_style("borderSize").'px solid #'.get_ag_style("borderColor").';';
	$css .= 'border-'.$attentionGrabber_core["borderPosition"].'-right-radius: 5px;';
	$css .= 'border-'.$attentionGrabber_core["borderPosition"].'-left-radius: 5px;';
	$css .= '}';

	$css .= '#attentionGrabberWrap #openAttentionGrabber span{';
	$css .= 'background:url('.$attentionGrabber_root_url.'img/buttons/'.$attentionGrabber_core["closeButtonStyle"].'.png) no-repeat right 50%;';
	$css .= '}';

	$css .= $the_attentionGrabber["additionalCss"];

	return $css;
}