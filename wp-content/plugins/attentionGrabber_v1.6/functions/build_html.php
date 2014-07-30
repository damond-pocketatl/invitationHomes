<?php

// Build the HTML markup
function build_attentionGrabber_html()
{
	global $the_attentionGrabber;
	global $attentionGrabber_core;
	
	// Get the content
	switch( $the_attentionGrabber["type"] )
	{
	
		case "custom" :
						
			$content 	= do_shortcode( $the_attentionGrabber["custom_messageText"] );
			$linkText 	= do_shortcode( $the_attentionGrabber["custom_linkText"] );
			$linkUrl 	= do_shortcode( $the_attentionGrabber["custom_linkUrl"] );
			
		break;
		
		case "twitter" :
			
			$username = $the_attentionGrabber["twitter_username"];
			$format = 'xml';
			$url = "http://api.twitter.com/1/statuses/user_timeline.".$format."?screen_name=".$username."&count=1";
			
			if( function_exists('simplexml_load_file') ){
			
				// Hide the php errors when the feed is not available
				libxml_use_internal_errors(true);
			
				$xml = simplexml_load_file( $url );
				
				if( $xml === false ){
					$content = "Couldn't get the user's tweets";
					$linkUrl = "";
				}else{
				
					$tweet = $xml->status->text;
					
					// Find the links inside the tweet
					$tweet = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\" >\\2</a>", $tweet);
					$tweet = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"http://\\2\" >\\2</a>", $tweet);
					$tweet = preg_replace("/@(\w+)/", "<a href=\"http://www.twitter.com/\\1\" >@\\1</a>", $tweet);
					$tweet = preg_replace("/#(\w+)/", "<a href=\"http://search.twitter.com/search?q=\\1\" >#\\1</a>", $tweet);
					
					$content 	= utf8_decode( $tweet );
					
					$content	= htmlspecialchars_decode( htmlentities( $content ) );
				}
				
				// Clear any XML errors
				libxml_clear_errors();
				
			}else{
				$content = "You need PHP 5 to use this feature";
			}
			
			$linkText 	= $the_attentionGrabber["twitter_linkText"];
			$linkUrl 	= "http://twitter.com/".$username;
		
		break;
		
		case "feedRss" :
			
			if( function_exists('simplexml_load_file') ){
				
				// Hide the php errors when the feed is not available
				libxml_use_internal_errors(true);
				
				$xml = simplexml_load_file( $the_attentionGrabber["feed_feedURL"] );
				
				if( $xml === false ){
					$content = "Couldn't read the feed url";
					$linkUrl = "";
				}else{
			
					// Get the feed format
					$feedType = $xml->getName();
					
					if($feedType == 'feed'){
					
						// If it is atom
						$entry		= $xml->entry[0];
						$content	= $entry->title;
						$linkUrl 	= $entry->link['href'];
						
					}else if($feedType == 'rss'){
						
						// If it is RSS
						$entry		= $xml->channel->item[0];
						$content	= $entry->title;
						$linkUrl 	= $entry->link;
						
					}else{
						$content = "Couldn't read the feed format";
						$linkUrl = "";
					}
				}
				
				// Clear any XML errors
				libxml_clear_errors();
			
			}else{
				$content = "You need PHP 5 to use this feature";
				$linkUrl = "";
			}

			$linkText 	= $the_attentionGrabber["feed_linkText"];
			
		break;
		
		case "advanced" :
			
			$content 	= do_shortcode( $the_attentionGrabber["advanced_content"] );
			$linkText 	= "";
			$linkUrl 	= "";
			
		break;
	
	}
	
	// Build the HTML output
	
	$bottomClass = ( preg_match( "/^bottom/", $attentionGrabber_core['position'] ) ) ? 'class="bottomPosition"' : '';
	$linkEnable = ( $linkText != "" && $linkUrl != "" ) ? true : false;
	$target = ( $attentionGrabber_core["newTab"] != null ) ? ' target="_blank"' : '';
	
	if( $attentionGrabber_core["closeable"] ){
		$closeBtn = '<span id="closeAttentionGrabber"></span>';
		$openBtn = '<span id="openAttentionGrabber"><span></span></span>';
	}else{
		$closeBtn = "";
		$openBtn = "";
	}
	
	$link = ( $linkEnable ) ? ' <a href="'.$linkUrl.'"'.$target.' class="link">'.$linkText.'</a>' : '';
	
	$html = '<div id="attentionGrabberWrap" '.$bottomClass.'>';
		$html  .= '<div id="attentionGrabber">';
			$html .= $content . $link . $closeBtn;
		$html .= '</div>';
		$html .= $openBtn;
	$html .= '</div>';
	
	echo $html;
}

// Load the markup only when there is an active attentionGrabber
function load_attentionGrabber_html(){
	add_action('wp_footer', 'build_attentionGrabber_html');
}

?>