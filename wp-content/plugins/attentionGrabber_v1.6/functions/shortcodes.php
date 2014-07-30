<?php

	// - -- --- ---- ----- ---- --- -- -
	// - -- --- GET LATEST POST --- -- -
	// - -- --- ---- ----- ---- --- -- -

	$ag_post_results = false;
	
	// Get the latest post and store the information in the $ag_post_results variable
	function ag_get_latest_post_result( $args ){

		global $ag_post_results;
		$ag_post_results = get_posts( $args );
		$ag_post_results = $ag_post_results[0];
	
	}
	
	// Get the latest post and store the information in the $ag_post_results variable
	function ag_get_single_post_result( $id ){

		global $ag_post_results;
		$ag_post_results = get_post( $id );
	
	}
	
	// Get the title of the latest post
	function ag_get_post_title_shortcode($atts, $content = null){
		
		global $ag_post_results;
		
		$newAtts = shortcode_atts(array(
			'id'			=> false,
			'numberposts'	=> 1,
			'offset'		=> 0,
			'orderby'		=> 'post_date',
			'order'			=> 'DESC',
			'post_type'		=> 'post',
			'post_status'	=> 'publish',
			'p'				=> '',
			'name'			=> '',
			'page_id'		=> '',
			'pagename'		=> '',
			'cat'			=> '',
			'category'		=> '',
			'category_name'	=> '',
			'tag'			=> '',
			'tag_id'		=> '',
			'author_name'	=> '',
			'include'		=> '',
			'exclude'		=> '',
			'meta_key'		=> '',
			'meta_value'	=> '',
			'post_mime_type'=> '',
			'post_parent'	=> ''
		), $atts);
		
		if( !$ag_post_results ){
		
			// If the id was not specified, get the latest post
			if( $newAtts['id'] === false ){
				ag_get_latest_post_result( $newAtts );
			}else{
				ag_get_single_post_result( $atts['id'] );
			}
		
		}
		
		return $ag_post_results->post_title;

	}
	add_shortcode("post_title", "ag_get_post_title_shortcode");
	
	// Get the url of the latest post
	function ag_get_post_url_shortcode($atts, $content = null){
		
		global $ag_post_results;
		
		$newAtts = shortcode_atts(array(
			'id'			=> false,
			'numberposts'	=> 1,
			'offset'		=> 0,
			'orderby'		=> 'post_date',
			'order'			=> 'DESC',
			'post_type'		=> 'post',
			'post_status'	=> 'publish',
			'p'				=> '',
			'name'			=> '',
			'page_id'		=> '',
			'pagename'		=> '',
			'cat'			=> '',
			'category'		=> '',
			'category_name'	=> '',
			'tag'			=> '',
			'tag_id'		=> '',
			'author_name'	=> '',
			'include'		=> '',
			'exclude'		=> '',
			'meta_key'		=> '',
			'meta_value'	=> '',
			'post_mime_type'=> '',
			'post_parent'	=> ''
		), $atts);
		
		if( !$ag_post_results ){
		
			// If the id was not specified, get the latest post
			if( $newAtts['id'] === false ){
				ag_get_latest_post_result( $atts );
			}else{
				ag_get_single_post_result( $atts['id'] );
			}
		
		}
		
		return $ag_post_results->guid;
	
	}
	add_shortcode("post_url", "ag_get_post_url_shortcode");
	
	
	
	// - -- --- ---- ------- ---- --- -- -
	// - -- --- MULTIPLE MESSAGES --- -- -
	// - -- --- ---- ------- ---- --- -- -
	
	// Show multiple messages
	function ag_multi_message_shortcode($atts, $content = null){
		
		extract(shortcode_atts(array(
			'pause'			=> 2000,
			'speed'			=> 300,
			'hover_pause'	=> true,
			'loop'			=> true
		), $atts));
		
		$param = 'data-pause="'.$pause.'" ';
		$param .= 'data-speed="'.$speed.'" ';
		$param .= 'data-pauseOnHover="'.$hover_pause.'" ';
		$param .= 'data-loop="'.$loop.'" ';
		
		return '<span class="multiMessages" '.$param.'>'. do_shortcode($content) .'</span>';
	
	}
	add_shortcode("multi_message", "ag_multi_message_shortcode");
	
	// Single message
	function ag_msg_shortcode($atts, $content = null){
		
		return '<span class="singleMessage">'. do_shortcode($content) .'</span>';
	
	}
	add_shortcode("msg", "ag_msg_shortcode");
	
	
	
	// - -- --- ---- --- -- -
	// - -- --- LINK --- -- -
	// - -- --- ---- --- -- -
	
	// Show multiple messages
	function ag_link_shortcode($atts, $content = null){
		
		extract(shortcode_atts(array(
			'url'			=> '#',
			'click_count'	=> 'true',
			'new_tab'		=> 'true'
		), $atts));
		
		$class = ( $click_count == 'true' ) ? 'class="link"' : '';
		$param = ( $new_tab == 'true' ) ? 'target="_blank"' : '';
		
		return '<a href="'.$url.'" '.$class.' '.$param.'>'. do_shortcode($content) .'</a>';
		
	}
	add_shortcode("link", "ag_link_shortcode");
	
	
	
	// - -- --- --------------- --- -- -
	// - -- --- SHARING BUTTONS --- -- -
	// - -- --- --------------- --- -- -
	

	// Show Facebook Like Button
	function ag_fb_like_shortcode($atts, $content = null){
		
		extract(shortcode_atts(array(
			'url'			=> home_url(),		// URL to like
			'layout'		=> 'button_count',	// Select layout
			'send'			=> false,
			'show_faces'	=> false,
			'colorscheme'	=> 'light',
			'width'			=> 90,				// The width of the button
			'font'			=> 'arial'
		), $atts));
		
		$html  = '<span class="facebookBtn">';
			$html .= '<span id="fb-root"></span>';
			$html .= '<fb:like href="'.$url.'" send="'.$send.'" layout="'.$layout.'" width="'.$width.'" show_faces="'.$show_faces.'" font="'.$font.'" colorscheme="'.$colorscheme.'"></fb:like>';
		$html .= '</span>';
		
		return $html;
	}
	add_shortcode("fblike", "ag_fb_like_shortcode");
	
	
	
	// Show Twitter Share Button
	function ag_tw_like_shortcode($atts, $content = null){
		
		extract(shortcode_atts(array(
			'url'	=> home_url(),	// URL to share
			'user'	=> null,		// Twitter username
			'text'	=> 'Tweet'			
		), $atts));
		
		$html  = '<span class="twitterBtn">';
			$html .= '<a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" ';
			$html .= 'data-via="'.$user.'" data-url="'.$url.'">'.$text.'</a>';
		$html .= '</span>';
				
		return $html;
	}
	add_shortcode("twlike", "ag_tw_like_shortcode");
	
	
	
	// Show Google Plus One Button
	function ag_go_like_shortcode($atts, $content = null){
		
		extract(shortcode_atts(array(
			'url'	=> home_url(),	// URL to share
			'size'	=> 'medium',	// Button Size
			'count'	=> true			// Show Counter			
		), $atts));
		
		$html  = '<span class="plusoneBtn">';
			$html .= '<g:plusone size="'.$size.'" count="'.$count.'" href="'.$url.'"></g:plusone>';
		$html .= '</span>';
				
		return $html;
	}
	add_shortcode("golike", "ag_go_like_shortcode");
	
	
	
	// ---------------------------------------------------------
	// Append the required scripts when using certain shortcodes
	// ---------------------------------------------------------
	function ag_shortcode_scripts( $content = null ){
		
		$shortcodes = array(
			'fblike'	=> 'http://connect.facebook.net/en_US/all.js#xfbml=1',
			'twlike'	=> 'http://platform.twitter.com/widgets.js',
			'golike'	=> 'https://apis.google.com/js/plusone.js'
		);
		
		foreach( $shortcodes as $shortcode=>$script ){
		
			if( stripos( $content, '[' . $shortcode ) !== false ){
			
				wp_enqueue_script( $shortcode.'_script', $script, array(), false, true );
				
			}
		
		}
		
	}
	
	