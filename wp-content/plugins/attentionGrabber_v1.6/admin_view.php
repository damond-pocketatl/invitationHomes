<?php
global $attentionGrabber_core;
require_once( "functions/admin_helper.php" );
?>

<h2><!-- show wordpress native error message here --></h2>

<div id="attentionGrabberAdmin">

	<div class="attentionGrabberAdmin_Header cf">
		<div class="logo">
			<img src="<?php echo plugins_url( '/img/logo.png', __FILE__ ); ?>" alt="attentionGrabber" />
			<span class="version"><?php echo $attentionGrabber_core["version"]; ?></span>
		</div>
		
		<div id="attentionGrabberAdmin_Notification"></div>
		
		<div id="attentionGrabberAdmin_LoaderBg"><span id="attentionGrabberAdmin_Loader" class="loading"></span></div>
		
	</div><!-- attentionGrabberAdmin_Header close -->
	
	<div id="attentionGrabberAdmin_Message"></div>
	
	<div id="attentionGrabberAdmin_Content">
	
		<div id="attentionGrabberAdmin_Title" class="cf">
			<a href="#" id="attentionGrabberAdmin_Save" class="actionButton">Save Changes</a>
			<a href="#" id="attentionGrabberAdmin_CreateNew" class="actionButton" tabindex="1">Create New</a>
			<a href="#" id="attentionGrabberAdmin_ViewSettings" class="actionButton">Settings</a>
			<a href="#"	id="attentionGrabberAdmin_Back"></a>
			
			<h3 class="listTitle">My Attention Grabbers</h3>
			<h3 class="settingsTitle">General Settings</h3>
			<input type="text" id="grabber_name_placeholder" name="grabber_name_placeholder" value="" placeholder="Insert name here"/>
		</div>
		
		<div id="attentionGrabberAdmin_SliderContainer">
			<div id="attentionGrabberAdmin_Slider">
				
				<?php require_once( "inc/admin_settings_view.php" ); ?>
				
				<?php require_once( "inc/admin_list_view.php" ); ?>
				
				<?php require_once( "inc/admin_editor_view.php" ); ?>
			
			</div><!-- attentionGrabberAdmin_Slider -->
		</div><!-- attentionGrabberAdmin_SliderContainer -->

	</div><!-- attentionGrabberAdmin_Content -->
	
</div>