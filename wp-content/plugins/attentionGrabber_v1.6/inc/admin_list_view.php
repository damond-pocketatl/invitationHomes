<div id="attentionGrabberAdmin_Manage" class="attentionGrabberAdmin_Slide">
	<ul id="attentionGrabberAdmin_List">
		
		<?php
		// Get the list of the grabbers available
		$grabbers = $attentionGrabber_core["created"];
		$grabbers = explode(",", $grabbers);
		array_pop($grabbers);

		foreach( $grabbers as $grabber_id )
		{
			// Get the grabber infos from the DB
			$grabber = get_option( "attentionGrabber_".$grabber_id );
			
			$active = ( $attentionGrabber_core["active"] == $grabber["id"] ) ? ' active' : '';
			$counter = ( $grabber["clickCount"] == "" ) ? 0 : $grabber["clickCount"];

			echo '<li id="grabber_id_'.$grabber["id"].'" class="attentionGrabberListItem cf">';
				echo '<span class="name">'.$grabber["name"].'</span>';
				echo '<span class="clickCount"><strong>Clicks:</strong> '.$counter.'</span>';
				echo '<span class="switch'.$active.'"><span class="switchHandle"></span><span class="switchBg"></span></span>';
				echo '<span class="delete"></span>';
				echo '<span class="grabberData">'.escapeChars( array2json( $grabber ) ).'</span>';
			echo '</li>';
		}
		
		$noGrabbersClass = ( count($grabbers) ) ? " hidden" : "";
		
		echo '<li class="noGrabbers'.$noGrabbersClass.'">Create a new Attention Grabber!</li>';
		
		?>
		
	</ul>
	
</div><!-- attentionGrabberAdmin_Manage -->