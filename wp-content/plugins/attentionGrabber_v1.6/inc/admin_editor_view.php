<div id="attentionGrabberAdmin_Editor" class="attentionGrabberAdmin_Slide">

	<input type="hidden" id="attentionGrabberAdmin_SiteURL" value="<?php bloginfo("url"); ?>"/>
	
	<form action="#" id="grabberForm">
	
		<input type="hidden" id="grabber_new" name="grabber_new" value="0" />
		<input type="hidden" id="grabber_nextID" name="grabber_nextID" value="<?php echo $attentionGrabber_core["nextID"]; ?>" />
		
		<input type="hidden" id="grabber_name" name="grabber_name" value=""/>
		<input type="hidden" id="grabber_action" name="grabber_action" value="save" />
		<input type="hidden" id="grabber_id" name="grabber_id" value="0" />
		<input type="hidden" id="grabber_clickCount" name="grabber_clickCount" value="0" />
		
		<div class="tabContainer" id="grabberContentSelector">
			
			<input type="hidden" id="grabber_type" name="grabber_type" value="custom" />
			
			<ul class="tabList fourTabs cf">
				<li class="custom first selected" rel="custom"><a href="#">Custom</a></li>
				<li class="twitter" rel="twitter"><a href="#">Twitter</a></li>
				<li class="feedRss" rel="feedRss"><a href="#">Feed RSS</a></li>
				<li class="advanced last" rel="advanced"><a href="#">Advanced</a></li>
			</ul>
			
			<ul class="tabPanels" id="grabberContent">
				<li class="custom selected">
					
					<div class="contentField cf">
						<label for="grabber_custom_messageText" class="cf"><span class="description">Add a custom text. It can contain HTML code or shortcodes.</span>Message</label>
						<input type="text" id="grabber_custom_messageText" name="grabber_custom_messageText" class="textField" value="" />
						
						<div class="contentTwoColumn">
							<label for="grabber_custom_linkText" class="cf"><span class="description">The text of the "read more" link.</span>Link text</label>
							<input type="text" id="grabber_custom_linkText" name="grabber_custom_linkText" class="textField" value="" />
						</div>
						<div class="contentTwoColumn last">	
							<label for="grabber_custom_linkUrl" class="cf"><span class="description">Leave blank to hide it. (use http://)</span>Link URL</label>
							<input type="text" id="grabber_custom_linkUrl" name="grabber_custom_linkUrl" class="textField" value="" />
						</div>
					</div>
					
				</li>
				<li class="twitter">
					
					<div class="contentField cf">
						<div class="contentTwoColumn">
							<label for="grabber_twitter_username">Twitter Username</label>
							<input type="text" id="grabber_twitter_username"  name="grabber_twitter_username" class="textField" value="" />
						</div>
						<div class="contentTwoColumn last">
							<label for="grabber_twitter_linkText" class="cf"><span class="description">Leave blank to hide it.</span>Follow me text</label>
							<input type="text" id="grabber_twitter_linkText" name="grabber_twitter_linkText" class="textField" value="" />
						</div>
					</div>
					
				</li>
				<li class="feedRss">
				
					<div class="contentField cf">
						<label for="grabber_feed_feedURL" class="cf"><span class="description">The url to the RSS or ATOM feed</span>Feed URL</label>
						<input type="text" id="grabber_feed_feedURL" name="grabber_feed_feedURL" class="textField" value="" />
						<div class="contentTwoColumn">
							<label for="grabber_feed_linkText" class="cf"><span class="description">Leave blank to hide it.</span>Read More Text</label>
							<input type="text" id="grabber_feed_linkText" name="grabber_feed_linkText" class="textField" value="" />
						</div>
					</div>
					
				</li>
				<li class="advanced">
				
					<div class="contentField cf">
						<label for="grabber_advanced_content" class="cf"><span class="description">This field is best-suited for complex markup and shortcodes.</span>Advanced content</label>
						<textarea name="grabber_advanced_content" id="grabber_advanced_content" class="textField" cols="30" rows="10"></textarea>
					</div>
					
				</li>
			</ul>
			
		</div><!-- grabberContentSelector close -->
		
		
		<style type="text/css" id="attentionGrabber_PreviewCss"></style>
		
		<div id="grabberPreviewArea">
			<div id="attentionGrabber">
				<div id="attentionGrabberWrap">
					The quick brown fox jumps over the lazy dog <a href="#">Read More</a>
				</div>
			</div>
		</div><!-- grabberPreviewArea close -->
		<a href="#" id="attentionGrabber_LaunchPreview">Launch the preview</a>
		
		
		<div class="tabContainer" id="grabberCustomizer">
			
			<ul class="tabList threeTabs cf">
				<li class="customize first selected"><a href="#">Customize</a></li>
				<li class="themes"><a href="#">Themes</a></li>
				<li class="additionalCss last"><a href="#">Additional CSS</a></li>
			</ul>
			
			<ul class="tabPanels" id="grabberStyle">
				<li class="customize cf selected">
					
					<div class="customizationColumn">
						<div class="customizationField colorField cf">
							<label for="grabber_style_bgColor">Background color</label>
							<span class="colorInputHolder">
								<input type="text" class="colorInput" value="f4f4f4" id="grabber_style_bgColor" name="grabber_style_bgColor"/>
							</span>
							<span class="colorInputDetail"></span>
						</div>
						<div class="customizationField colorField cf">
							<label for="grabber_style_textColor">Text color</label>
							<span class="colorInputHolder">
								<input type="text" class="colorInput" value="666666" id="grabber_style_textColor" name="grabber_style_textColor" />
							</span>
							<span class="colorInputDetail"></span>
						</div>
						<div class="customizationField colorField cf">
							<label for="grabber_style_textShadowColor">Text Shadow color</label>
							<span class="colorInputHolder">
								<input type="text" class="colorInput" value="f5f5f5" id="grabber_style_textShadowColor" name="grabber_style_textShadowColor" />
							</span>
							<span class="colorInputDetail"></span>
						</div>
						<div class="customizationField colorField cf">
							<label for="grabber_style_linkColor">Link color</label>
							<span class="colorInputHolder">
								<input type="text" class="colorInput" value="444444" id="grabber_style_linkColor" name="grabber_style_linkColor"/>
							</span>
							<span class="colorInputDetail"></span>
						</div>
						<div class="customizationField colorField cf">
							<label for="grabber_style_linkHoverColor">Link hover color</label>
							<span class="colorInputHolder">
								<input type="text" class="colorInput" value="222222" id="grabber_style_linkHoverColor" name="grabber_style_linkHoverColor" />
							</span>
							<span class="colorInputDetail"></span>
						</div>
						<div class="customizationField colorField cf">
							<label for="grabber_style_linkShadowColor">Link Shadow color</label>
							<span class="colorInputHolder">
								<input type="text" class="colorInput" value="f5f5f5" id="grabber_style_linkShadowColor" name="grabber_style_linkShadowColor" />
							</span>
							<span class="colorInputDetail"></span>
						</div>
						<div class="customizationField colorField cf">
							<label for="grabber_style_borderColor">Border color</label>
							<span class="colorInputHolder">
								<input type="text" class="colorInput" value="cccccc" id="grabber_style_borderColor" name="grabber_style_borderColor" />
							</span>
							<span class="colorInputDetail"></span>
						</div>
					</div>
					
					<div class="customizationColumn">
					
						<div class="customizationField cf">
							<label for="grabber_style_fontFamily">Font family</label>
							<div class="selectHolder">
								<span></span>
								<select name="grabber_style_fontFamily" id="grabber_style_fontFamily" class="styleSelect">
									<option value="arial, sans-serif">Arial</option>
									<option value="Arial black, Gadget, sans-serif">Arial Black</option>
									<option value="Calibri, sans-serif">Calibri</option>
									<option value="Courier New, monospace">Courier New</option>
									<option value="Futura, sans-serif">Futura</option>
									<option value="georgia, serif">Georgia</option>
									<option value="helvetica, sans-serif">Helvetica</option>
									<option value="Lucida Console, Monaco, monospace">Lucida Console</option>
									<option value="Lucida Grande, Lucida Sans Unicode, sans-serif">Lucida Grande</option>
									<option value="Palatino, Palatino Linotype, Book Antiqua, serif">Palatino</option>
									<option value="Tahoma, Geneva, sans-serif">Tahoma</option>
									<option value="times new roman">Times New Roman</option>
									<option value="Trebuchet MS, sans-serif">Trebuchet</option>
									<option value="Verdana, Geneva">Verdana</option>
								</select>
							</div>
						</div>
						<div class="customizationField cf">
							<label for="grabber_style_fontSize">Font size</label>
							<div class="sizeHolder">
								<span class="sizeValue"></span>
								<div class="sizeSlider"></div>
								<input type="hidden" data-min="10" data-max="48" class="sizeField" id="grabber_style_fontSize" name="grabber_style_fontSize" value="13"/>
							</div>
						</div>
						<div class="customizationField cf">
							<label for="grabber_style_textShadowSize">Text Shadow Size</label>
							<div class="sizeHolder">
								<span class="sizeValue"></span>
								<div class="sizeSlider"></div>
								<input type="hidden" data-min="-3" data-max="3" class="sizeField" id="grabber_style_textShadowSize" name="grabber_style_textShadowSize" value="1"/>
							</div>
						</div>
						<div class="customizationField cf">
							<label for="grabber_style_fontStyle">Font Style</label>
							<div class="selectHolder">
								<span></span>
								<select name="grabber_style_fontStyle" id="grabber_style_fontStyle" class="styleSelect">
									<option value="normal">Normal</option>
									<option value="italic">Italic</option>
									<option value="bold">Bold</option>
									<option value="bold italic">Bold Italic</option>
								</select>
							</div>
						</div>
						<div class="customizationField cf">
							<label for="grabber_style_height">Height</label>
							<div class="sizeHolder">
								<span class="sizeValue"></span>
								<div class="sizeSlider"></div>
								<input type="hidden" data-min="15" data-max="100" class="sizeField" id="grabber_style_height" name="grabber_style_height" value="40"/>
							</div>
						</div>
						<div class="customizationField cf">
							<label for="grabber_style_linkShadowSize">Link Shadow Size</label>
							<div class="sizeHolder">
								<span class="sizeValue"></span>
								<div class="sizeSlider"></div>
								<input type="hidden" data-min="-3" data-max="3" class="sizeField" id="grabber_style_linkShadowSize" name="grabber_style_linkShadowSize" value="1"/>
							</div>
						</div>
						<div class="customizationField cf">
							<label for="grabber_style_borderSize">Border Size</label>
							<div class="sizeHolder">
								<span class="sizeValue"></span>
								<div class="sizeSlider"></div>
								<input type="hidden" data-min="0" data-max="10" class="sizeField" id="grabber_style_borderSize" name="grabber_style_borderSize" value="1"/>
							</div>
						</div>
						
					</div>
					
				</li><!-- customize close -->
				<li class="themes cf">
					
					<input type="hidden" id="grabber_theme" name="grabber_theme" value="custom" />
					
					<ul id="grabber_themesList" class="cf">
						<li id="grabberTheme_1">
							<div class="themeBorder">
								<div class="grabberThemeDemo">
									Sample text <a href="#">Link</a>
								</div>
							</div>
						</li>
						<li id="grabberTheme_2" class="even">
							<div class="themeBorder">
								<div class="grabberThemeDemo">
									Sample text <a href="#">Link</a>
								</div>
							</div>
						</li>
						<li id="grabberTheme_3">
							<div class="themeBorder">
								<div class="grabberThemeDemo">
									Sample text <a href="#">Link</a>
								</div>
							</div>
						</li>
						<li id="grabberTheme_4" class="even">
							<div class="themeBorder">
								<div class="grabberThemeDemo">
									Sample text <a href="#">Link</a>
								</div>
							</div>
						</li>
						<li id="grabberTheme_5">
							<div class="themeBorder">
								<div class="grabberThemeDemo">
									Sample text <a href="#">Link</a>
								</div>
							</div>
						</li>
						<li id="grabberTheme_6" class="even">
							<div class="themeBorder">
								<div class="grabberThemeDemo">
									Sample text <a href="#">Link</a>
								</div>
							</div>
						</li>
					</ul>
					
				</li><!-- themes close -->
				<li class="additionalCss cf">
					
					<label for="grabber_additionalCss" class="cf"><span class="description">This will override any other style. (won't be displayed in the live preview)</span>Additional CSS</label>
					<textarea name="grabber_additionalCss" id="grabber_additionalCss" cols="30" rows="10" class="textField"></textarea>
					
				</li><!-- additionalCss close -->
			</ul>
			
		</div><!-- grabberCustomizer close -->
		
	</form>

</div><!-- attentionGrabberAdmin_Editor close -->