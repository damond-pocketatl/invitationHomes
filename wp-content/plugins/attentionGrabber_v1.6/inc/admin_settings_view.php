<form action="#" id="grabberSettingsForm">
	<div id="attentionGrabberAdmin_Settings" class="attentionGrabberAdmin_Slide">
		
		<input type="hidden" id="grabber_settings_action" name="grabber_action" value="saveSettings" />
		
		<span id="grabber_core_settings" name="grabber_core_settings">
			<?php echo array2json( $attentionGrabber_core ); ?>
		</span>
		
		<?php
			// Add checked='checked' to the options that are true
			function is_checked( $option )
			{
				global $attentionGrabber_core;
				if( $attentionGrabber_core[$option] ){
					echo 'checked="checked"';
				}
			}
			// Build the select field
			function buildSelect( $name, $fields )
			{
				global $attentionGrabber_core;
				$selected = $attentionGrabber_core[$name];
				$html = '<select name="grabberSettings_'.$name.'" id="grabberSettings_'.$name.'">';
				foreach( $fields as $key=>$value )
				{
					$html .= '<option ';
					$html .= ( $selected == $value ) ? 'selected="selected" ' : '';
					$html .= 'value="'.$value.'">'.$key.'</option>';
				}
				$html .= '</select>';
				echo $html;
			}
		?>
		
		<div class="settingsField cf">
			<label for="grabberSettings_position">Position</label>
			<div class="selectHolder">
				<span></span>
				<?php 
					$positionArr = array(
						'Top' 			=> 'top',
						'Top Fixed' 	=> 'top_fixed',
						'Bottom Fixed' 	=> 'bottom_fixed'
					);
					buildSelect( 'position', $positionArr );
				?>
			</div>
			<div class="settingsDetail twoLines">The position where the Attention Grabber will appear. If set on "Fixed" will always stay visible.</div>
		</div>
		
		<div class="settingsField cf">
			<label for="grabberSettings_showAfter">Show After</label>
			<div class="selectHolder">
				<span></span>
				<?php 
					$showAfterArr = array(
						'Immediately' 	=> '0',
						'3 Seconds' 	=> '3',
						'5 Seconds' 	=> '5',
						'10 Seconds' 	=> '10',
						'15 Seconds' 	=> '15',
						'20 Seconds' 	=> '20'
					);
					buildSelect( 'showAfter', $showAfterArr );
				?>
			</div>
			<div class="settingsDetail">Delay showing the Attention Grabber</div>
		</div>
		
		<div class="settingsField cf">
			<label for="grabberSettings_animationDuration">Animation Duration</label>
			<div class="selectHolder">
				<span></span>
				<?php 
					$animationDurationArr = array(
						'200 Milliseconds' 	=> '200',
						'300 Milliseconds' 	=> '300',
						'400 Milliseconds' 	=> '400',
						'500 Milliseconds' 	=> '500',
						'600 Milliseconds' 	=> '600',
						'800 Milliseconds' 	=> '800',
						'1 Second'			=> '1000'
					);
					buildSelect( 'animationDuration', $animationDurationArr );
				?>
			</div>
			<div class="settingsDetail">Duration of the slide-up/slide-down animations.</div>
		</div>
		
		<div class="settingsField cf">
			<label for="grabberSettings_animationEffect">Animation Effect</label>
			<div class="selectHolder">
				<span></span>
				<?php 
					$animationEffectArr = array(
						'Swing' 		=> 'swing',
						'Linear' 		=> 'linear',
						'Bounce' 		=> 'easeOutBounce'
					);
					buildSelect( 'animationEffect', $animationEffectArr );
				?>
			</div>
			<div class="settingsDetail twoLines">The animation effect. The difference between "swing" and "linear" is very subtle.</div>
		</div>
		
		<div class="settingsField cf">
			<label for="grabberSettings_newTab">Open Link In New Window</label>
			<input type="checkbox" id="grabberSettings_newTab" name="grabberSettings_newTab" class="customCheckbox" data-on="Yes" data-off="No" <?php is_checked("newTab"); ?>/>
			<div class="settingsDetail">Should the link open in a new window?</div>
		</div>
		
		<div class="settingsField cf">
			<label for="grabberSettings_closeable">Show Close Button</label>
			<input type="checkbox" id="grabberSettings_closeable" name="grabberSettings_closeable" class="customCheckbox" data-on="Yes" data-off="No" <?php is_checked("closeable"); ?>/>
			<div class="settingsDetail twoLines">Can the Attention Grabber be closed? When "No" is selected, the next two options won't take effect.</div>
		</div>
		
		<div class="settingsField cf">
			<label for="grabberSettings_closeButtonStyle">Close Button Style</label>
			<div class="selectHolder">
				<span></span>
				<?php 
					$closeButtonStyleArr = array(
						'Light'				=> 'light',
						'Light-Transparent' => 'light-transparent',
						'Dark'				=> 'dark',
						'Dark-Transparent' 	=> 'dark-transparent'
					);
					buildSelect( 'closeButtonStyle', $closeButtonStyleArr );
				?>
			</div>
			<div class="settingsDetail">Choose the style for the opening/closing arrow.</div>
		</div>
		
		<div class="settingsField cf">
			<label for="grabberSettings_keepHidden">Keep hidden</label>
			<input type="checkbox" id="grabberSettings_keepHidden" name="grabberSettings_keepHidden" class="customCheckbox" data-on="Yes" data-off="No" <?php is_checked("keepHidden"); ?>/>
			<div class="settingsDetail twoLines">If the user close your Attention Grabber, it will stay hidden the next time he visits your site.</div>
		</div>
		
		<div class="settingsField colorField cf">
			<label for="grabberSettings_previewBg">Preview Background Color</label>
			<span class="colorInputHolder">
				<input type="text" class="colorInput settingsColor" value="<?php echo $attentionGrabber_core['previewBg']; ?>" id="grabberSettings_previewBg" name="grabberSettings_previewBg" />
			</span>
			<span class="colorInputDetail"></span>
			<div class="settingsDetail twoLines">For a better previewing experience, change the background to match the one on your site.</div>
		</div>
		
		<div class="settingsField cf">
			<label for="grabberSettings_includeJquery">Include jQuery</label>
			<input type="checkbox" id="grabberSettings_includeJquery" name="grabberSettings_includeJquery" class="customCheckbox" data-on="Yes" data-off="No" <?php is_checked("includeJquery"); ?>/>
			<div class="settingsDetail twoLines">You should try setting it to "No" only if the plugin is not working with your theme.</div>
		</div>
		
		<div class="settingsField cf">
			<label for="grabberSettings_checkUpdates">Check for updates</label>
			<input type="checkbox" id="grabberSettings_checkUpdates" name="grabberSettings_checkUpdates" class="customCheckbox" data-on="Yes" data-off="No" <?php is_checked("checkUpdates"); ?>/>
			<div class="settingsDetail">Get a notification when a new version of the plugin is available.</div>
		</div>
		
		<div class="settingsField cf">
			<label for="grabberSettings_resetAll">Reset settings</label>
			<a href="#" id="grabberSettings_resetAll">Reset everything</a>
			<div class="settingsDetail twoLines">This will reset the settings and delete any Attention Grabbers created until now.</div>
		</div>							
		
	</div><!-- attentionGrabberAdmin_Settings close -->
</form>