<?php

class AttentionGrabber_Admin_Ajax
{
    public static function saveChanges( $data ){
        global $attentionGrabber_core;

        $array = array(
            // General info
            'id'                    => $data['grabber_id'],
            'name'                  => stripslashes( $data['grabber_name'] ),
            'type'                  => $data['grabber_type'],
            // Custom text
            'custom_messageText'    => stripslashes( $data['grabber_custom_messageText'] ),
            'custom_linkText'       => stripslashes( $data['grabber_custom_linkText'] ),
            'custom_linkUrl'        => $data['grabber_custom_linkUrl'],
            // Twitter
            'twitter_username'      => $data['grabber_twitter_username'],
            'twitter_linkText'      => stripslashes( $data['grabber_twitter_linkText'] ),
            // Feed
            'feed_feedURL'          => $data['grabber_feed_feedURL'],
            'feed_linkText'         => $data['grabber_feed_linkText'],
            // Advanced
            'advanced_content'      => stripslashes( $data['grabber_advanced_content'] ),
            // Style info
            'theme'                 => $data['grabber_theme'],
            
            'style_bgColor'         => $data['grabber_style_bgColor'],
            'style_textColor'       => $data['grabber_style_textColor'],
            'style_linkColor'       => $data['grabber_style_linkColor'],
            'style_linkHoverColor'  => $data['grabber_style_linkHoverColor'],
            'style_borderColor'     => $data['grabber_style_borderColor'],
            
            'style_fontFamily'      => $data['grabber_style_fontFamily'],
            'style_fontSize'        => $data['grabber_style_fontSize'],
            'style_fontStyle'       => $data['grabber_style_fontStyle'],
            'style_borderSize'      => $data['grabber_style_borderSize'],
            
            'style_textShadowSize'  => $data['grabber_style_textShadowSize'],
            'style_textShadowColor' => $data['grabber_style_textShadowColor'],
            
            'style_linkShadowSize'  => $data['grabber_style_linkShadowSize'],
            'style_linkShadowColor' => $data['grabber_style_linkShadowColor'],
                    
            'style_height'          => $data['grabber_style_height'],
            
            'additionalCss'         => $data['grabber_additionalCss'],
            
            'clickCount'            => $data['grabber_clickCount']
        );

        // Save changes to the DB
        update_option( "attentionGrabber_".$data['grabber_id'], $array );
        
        $message = "Attention Grabber ".$data['grabber_id']." updated";
        
        if( $data['grabber_new'] ){
            // Update the nextID field
            $attentionGrabber_core["nextID"] = $data['grabber_nextID'];
            
            // Add grabber_id to the created list
            $attentionGrabber_core["created"] .= $data['grabber_id'].',';
            update_option( "attentionGrabber_core", $attentionGrabber_core );
            
            $message = "New Attention Grabber created";
        }

        self::response( array(), 'ok', $message );
    }

    public static function activate( $data ){
        global $attentionGrabber_core;

        $id =  $data['id'];
        $attentionGrabber_core["active"] = $id;
        update_option( "attentionGrabber_core", $attentionGrabber_core );
        
        $message = "Attention Grabber ".$id." activated";

        self::response( array(), 'ok', $message );
    }

    public static function deactivate( $data ){
        global $attentionGrabber_core;

        // Deactivate Grabber
        $id = $attentionGrabber_core["active"];
        $attentionGrabber_core["active"] = false;
        update_option( "attentionGrabber_core", $attentionGrabber_core );
        
        $message = "Attention Grabber ".$id." deactivated";

        self::response( array(), 'ok', $message );
    }

    public static function delete( $data ){
        global $attentionGrabber_core;

        // Delete Grabber
        $id =  $data['id'];
        
        // Delete grabber from the DB
        delete_option( "attentionGrabber_".$id );
        
        // Remove the grabber ID from the "created" string
        $grabbers = $attentionGrabber_core["created"];
        $regex = "/((?<=\D)|^)".$id."\D/";
        $grabbers = preg_replace( $regex, '', $grabbers );
        $attentionGrabber_core["created"] = $grabbers;
        
        $message = "Attention Grabber ".$id." deleted";
        
        if( $data['isActive'] ){
            // If this was the active grabber, it needs to be deactivated
            $attentionGrabber_core["active"] = false;
            
            $message .= " and deactivated";
        }
        
        // Update the core infos
        update_option( "attentionGrabber_core", $attentionGrabber_core );

        self::response( array(), 'ok', $message );
    }

    public static function saveSettings( $data ){
        global $attentionGrabber_core;

        $attentionGrabber_core['position']          = $data['grabberSettings_position'];
        $attentionGrabber_core['borderPosition']    = "bottom";
        
        $attentionGrabber_core['showAfter']         = $data['grabberSettings_showAfter'];
        $attentionGrabber_core['animationDuration'] = $data['grabberSettings_animationDuration'];
        $attentionGrabber_core['animationEffect']   = $data['grabberSettings_animationEffect'];
        
        $attentionGrabber_core['previewBg']         = $data['grabberSettings_previewBg'];
        $attentionGrabber_core['closeButtonStyle']  = $data['grabberSettings_closeButtonStyle'];
        
        // Set the checkboxes value to true or false
        $attentionGrabber_core['closeable']         = (bool)( $data['grabberSettings_closeable'] );
        $attentionGrabber_core['newTab']            = (bool)( $data['grabberSettings_newTab'] );
        $attentionGrabber_core['keepHidden']        = (bool)( $data['grabberSettings_keepHidden'] );
        $attentionGrabber_core["includeJquery"]     = (bool)( $data['grabberSettings_includeJquery'] );
        $attentionGrabber_core["checkUpdates"]      = (bool)( $data['grabberSettings_checkUpdates'] );
        
        // Adjust the position of the border
        if( preg_match( "/^bottom/", $attentionGrabber_core['position'] ) ){ 
            $attentionGrabber_core["borderPosition"] = "top";
        }
        
        // Save changes to the DB
        update_option( "attentionGrabber_core", $attentionGrabber_core );
        
        $message = "Settings saved";

        self::response( array(), 'ok', $message );
    }
    
    public static function resetAll(){
        global $attentionGrabber_core;

        // Get the list of the grabbers available
        $grabbers = $attentionGrabber_core["created"];
        $grabbers = explode(",", $grabbers);
        array_pop($grabbers);
        
        // Delete each grabber from the DB
        foreach( $grabbers as $grabber_id ){
            delete_option( "attentionGrabber_".$grabber_id );
        }
        
        // Reset the settings
        save_attentionGrabber_defaults();
        
        $message = 'All data deleted';

        self::response( array(), 'ok', $message );
    }
    
    // Create a proper well-structured response
    public static function response( $content, $status = 'ok', $description = '' ) {
        $response = array(
            'status'        => $status,
            'description'   => $description,
            'content'       => $content
        );
        echo json_encode( $response );
        exit();
    }

    // parse the ajax request
    public static function request() {

        if( isset( $_POST['function'] ) && isset( $_POST['data'] ) && is_array( $_POST['data'] ) ) {
        
            $dataArr    = $_POST['data'];

            switch ( $_POST['function'] ) {

                case 'saveChanges':
                    self::saveChanges( $dataArr );
                break;
                
                case 'activate':
                    self::activate( $dataArr );
                break;

                case 'deactivate':
                    self::deactivate( $dataArr );
                break;

                case 'delete':
                    self::delete( $dataArr );
                break;
                
                case 'saveSettings':
                    self::saveSettings( $dataArr );
                break;

                case 'resetAll':
                    self::resetAll();
                break;
                
                default :
                    self::response( array(), 'ko', 'function '.$_POST['function'].' not found' );
                break;

            }
        } else {
            self::response( array(), 'ko', 'invalid request' );
        }
    }
}
add_action('wp_ajax_ag_do_admin_ajax', array( 'AttentionGrabber_Admin_Ajax', 'request' ));