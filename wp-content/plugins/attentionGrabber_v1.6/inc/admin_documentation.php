<?php

// Show a simple documentation in the contextual help tab
function attentionGrabber_admin_documentation(){
	
	global $attentionGrabber_admin_page;
	
	// Make sure this is the right page
	$screen = get_current_screen();
	if ( is_object($screen) && $screen->id != $attentionGrabber_admin_page) return;
	
	
	// Sharing Buttons ----------------------------------------------------------------------------------------------------------------
	$sharing	= '<h3>[fblike] - Facebook Like Button</h3>';
	$sharing	.= '<ul>
					<li><strong>url</strong>: The URL you want to like. If not set, the home url of the current site will be used.</li>
					<li><strong>width</strong>: Specify the width of the button in pixels. Set to <em>90</em> by default.</li>
					<li><strong>colorscheme</strong>: Choose between the "<em>light</em>" and "<em>dark</em>" color schemes. Set to "<em>light</em>" by default.</li>
				</ul>';
	$sharing	.= '<em>Example:</em> <br />
	<code class="codeblock"><pre>[fblike width=60 url="http://google.com/"]</pre></code>';
	
	
	$sharing	.=  '<hr />';
	
	
	$sharing	.= '<h3>[twlike] - Twitter Sharing Button</h3>';
	$sharing	.= '<ul>
					<li><strong>url</strong>: The URL you want to share. If not set, the home url of the current site will be used.</li>
					<li><strong>user</strong>: The Twitter username that will be used as @mention. Empty by default.</li>
				</ul>';
	$sharing	.= '<em>Example:</em> <br />
	<code class="codeblock"><pre>[twlike user="mtdsgn" url="http://google.com/"]</pre></code>';
	
	
	$sharing	.= '<hr />';
	
	
	$sharing	.= '<h3>[golike] - Google PlusOne Button</h3>';
	$sharing	.='<ul>
					<li><strong>url</strong>: The URL you want to share. If not set, the home url of the current site will be used.</li>
					<li><strong>count</strong>: Show or hide the counter bubble on the right. Set to true by default.</li>
				</ul>';
	$sharing	.= '<em>Example:</em> <br />
	<code class="codeblock"><pre>[golike url="http://google.com/" count="false"]</pre></code>';
	
	
	// Multiple Messages ----------------------------------------------------------------------------------------------------------------
	$multimess	= '<h3>[multi_message] &amp; [msg] - Multiple Messages</h3>';
	$multimess	.= '<p>Basic usage:</p>';
	$multimess	.= '<ol>
						<li>
							Wrap the set of messages with the <strong>[multi_message]</strong> shortcode. <br />
							<code>[multi_message] the set of messages goes here [/multi_message]</code>
						</li>
						<li>
							Wrap each message with a <strong>[msg]</strong>. <br />
							<code class="codeblock"><pre>
[multi_message]
	[msg] This is the first message [/msg]
	[msg] This is the second message [/msg]
[/multi_message]</pre></code>
						</li>
					</ol>';
					
	$multimess	.= '<p>You can customize the fading effect by passing the following parameters to the <strong>[multi_message]</strong> shortcode:</p>';
	
	$multimess	.= '<ul>
						<li>
							<strong>pause</strong>:
							Indicates how much time (in milliseconds) should a single message stay visible before fading to the next one.
							Set to <strong>2000</strong> ( 2 seconds) by default
						</li>
						<li>
							<strong>speed</strong>:
							Indicates the duration of the fading animation (in milliseconds).
							Set to <strong>300</strong> milliseconds by default
						</li>
						<li>
							<strong>hover_pause</strong>: 
							When set to true the fading effect will stay paused as soon as the user keep the mouse over the attentionGrabber. Use it to enhance readability.
							Set to <strong>true</strong> by default
						</li>
						<li>
							<strong>loop</strong>: 
							When set to true, the fading effect will loop infinitely throughout all the messages.<br />
							When set to false, the fading will stop at the last message. It would be a smart idea to put the link in the last message.
							Set to <strong>true</strong> by default
						</li>
					</ul>';
					
	$multimess	.= '<em>Example:</em> <br />
	<code class="codeblock"><pre>
[multi_message pause="5000" speed="500" hover_pause="true" loop="false"]
	[msg] This is the first message [/msg]
	[msg] This one has a [link url="http://google.com"]link[/link] [/msg]
[/multi_message]</pre></code>';

	
	// Utils ----------------------------------------------------------------------------------------------------------------
	$utils	= '<h3>[post_title]</h3>';
	$utils	.= '<p>When used with no parameters, this shortcode will display the title of the latest blog post.<br /> When an ID is specified, it will display the title of that particular post or page. </p>';
	
	$utils	.= '<em>Example:</em> <br /> <code>[post_title id=27]</code> -> Will display the title of the post or page with ID 27.';

	$utils	.= '<p>When the ID is not set, the shortcode will support all the parameters of the <a href="http://codex.wordpress.org/Template_Tags/get_posts" target="_blank">get_posts</a> function. This will let you do more advanced query, like getting the title of the latest post that belongs to a particular category.</p>';


	$utils	.= '<hr />';

	
	$utils	.= '<h3>[post_url]</h3>';

	$utils	.= '<p>Does the same thing as the <strong>[post_title]</strong> shortcode, for the URLs. You should use it inside the <strong>Link URL field</strong>.</p>';

	$utils	.= '<h3>[link]</h3>';

	$utils	.= '<p>Easily display any link without using the raw html code. You can pass the following parameters to it:</p>';
	
	$utils	.='<ul>
					<li><strong>url</strong>: the complete url of the link</li>
					<li><strong>click_count</strong>: when set to true it will be used by the script to increment the click counter (<strong>true</strong> by default)</li>
					<li><strong>new_tab</strong>: when set to true, the link will be open in a new tab (<strong>true</strong> by default)</li>
				</ul>';
	
	$utils	.= '<em>Example:</em> <br /> <code>[link url="http://google.com" click_count="true" new_tab="false"]This is a link[/link]</code>';

	
	// Add Tabs to the contextual menu
	if( method_exists( $screen, 'add_help_tab' ) ) {
		
		$screen->add_help_tab( array(
			'id'      => 'ag-help-sharing',
			'title'   => 'Sharing Buttons',
			'content' => $sharing
		));

		$screen->add_help_tab( array(
			'id'      => 'ag-help-multimess',
			'title'   => 'Multiple Messages',
			'content' => $multimess
		));
		
		$screen->add_help_tab( array(
			'id'      => 'ag-help-utils',
			'title'   => 'Utils',
			'content' => $utils
		));
		
	}
}