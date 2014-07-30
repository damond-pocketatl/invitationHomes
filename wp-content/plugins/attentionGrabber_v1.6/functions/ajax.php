<?php

class AttentionGrabber_Ajax
{
    public static function register_click(){
        global $the_attentionGrabber;

        $id = $the_attentionGrabber["id"];
        $count = $the_attentionGrabber["clickCount"];
        $the_attentionGrabber["clickCount"] = $count + 1;
        
        // Save changes to the DB
        update_option( "attentionGrabber_".$id, $the_attentionGrabber );

        self::response( array(), 'ok', 'click on '.$id.' registered' );
    }

    public static function unset_cookie(){
        // Delete cookie
        setcookie( 'attentionGrabber_active', null, -1, '/' );
        self::response( array(), 'ok', 'cookie removed' );
    }
    
    public static function set_cookie(){
        global $the_attentionGrabber;
        // Save cookie
        setcookie( 'attentionGrabber_active', $the_attentionGrabber["id"]+123, time()+(60*60*24*30), '/' );
        self::response( array(), 'ok', 'cookie saved for '.$the_attentionGrabber["id"] );
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

        if( isset( $_POST['function'] ) ) {
        
            switch ( $_POST['function'] ) {

                case 'register_click':
                    self::register_click();
                break;

                case 'unset_cookie':
                    self::unset_cookie();
                break;

                case 'set_cookie':
                    self::set_cookie();
                break;

            }
        }
    }
}
add_action('wp_ajax_ag_do_ajax', array( 'AttentionGrabber_Ajax', 'request' ));