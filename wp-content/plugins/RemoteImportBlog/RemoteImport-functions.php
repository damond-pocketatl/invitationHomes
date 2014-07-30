<?php


function get_remote_xml_file_url()
					{
					
						$remote_xml_file_url="http://www.rentcafe.com/feeds/C00000003066.xml";
						return $remote_xml_file_url;
					}



function rm_fetch_remote_file($postId, $url) {
	// extract the file name and extension from the url
	$file_name = $postId.'_'.basename( $url );

	// get placeholder file in the upload dir with a unique, sanitized filename
	$upload = wp_upload_bits( $file_name, 0, '', $post['upload_date'] );
	if ( $upload['error'] )
	{
		//return new WP_Error( 'upload_dir_error', $upload['error'] );
		return '';
	}
	// fetch the remote url and write it to the placeholder file
	$headers = wp_get_http( $url, $upload['file'] );

	// request failed
	if ( ! $headers ) {
		@unlink( $upload['file'] );
		//return new WP_Error( 'import_file_error', __('Remote server did not respond', 'wordpress-importer') );
		return '';
	}

	// make sure the fetch was successful
	if ( $headers['response'] != '200' ) {
		@unlink( $upload['file'] );
		//return new WP_Error( 'import_file_error', sprintf( __('Remote server returned error response %1$d %2$s', 'wordpress-importer'), esc_html($headers['response']), get_status_header_desc($headers['response']) ) );
		return '';
	}

	$filesize = filesize( $upload['file'] );

	if ( isset( $headers['content-length'] ) && $filesize != $headers['content-length'] ) {
		@unlink( $upload['file'] );
		//return new WP_Error( 'import_file_error', __('Remote file is incorrect size', 'wordpress-importer') );
		return '';
	}

	if ( 0 == $filesize ) {
		@unlink( $upload['file'] );
		//return new WP_Error( 'import_file_error', __('Zero size file downloaded', 'wordpress-importer') );
		return '';
	}
	
	return $upload;
}

function rm_find_categories( $email) 
							{
								global $wpdb;
								$city = $wpdb->get_var( $wpdb->prepare( 
									"SELECT city
										FROM ".$wpdb->prefix."xml_import_lookup_email 
										WHERE email = %s
									", 
									trim($email)
								) );
								if($city)
								{
									$marketObj = get_term_by('name', esc_attr(trim($city)), 'market');
									if($marketObj->term_id)	
									return array((int)$marketObj->term_id);
								}
								return false;
							}
function geoCoderUpdate($postId, $address, $city, $state, $lat, $long )
						{
							update_post_meta($postId,'martygeocoderaddress',$address.' '.$city.', '.$state );
							update_post_meta($postId,'martygeocoderlatlng',"(".$lat.', '.$long." )");	
						}

//add_filter('is_protected_meta', 'rm_is_protected_meta_filter', 10, 2);
function rm_is_protected_meta_filter($protected, $meta_key) 
						{
							return $meta_key == '__rm_images' ? true : $protected;
						}

function notify_to($to, $subject,$message)
					{
							//$subject = 'XML Import Report'.date("Y-m-d_H:i:s");
							/*$logfile=WP_CONTENT_DIR."/uploads/XML_file/".$subject.'.txt';
							$fp = fopen($logfile, 'w');
							if(!$fp)
								{
										echo "Cannot open  file ($message)";
										exit;
								}
							fwrite($fp,$message);
							fclose($fp);
							*/
							
							
							
							
							$message_in_form = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head><body>';
							$message_in_form.=$message;
							$message_in_form.='</body></html>';
   



							
							
							
							
							
							
							
							$headers = "MIME-Version: 1.0\r\n";
							$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
							$headers .= 'From: Invitation Homes <info@invitationhomes.com>'. "\r\n";
							wp_mail($to, $subject, $message_in_form, $headers);							
					}
					
					
function update_property_meta_box($post_id, $values)
{
	$meta= 'propdetails';
	$element_id = 0 ;
	$results = get_post_meta($post_id, $meta, true);
	$results[$element_id] = $values;
	update_post_meta($post_id, $meta, $results);
}
function delete_associated_media($id) {
    // check if page
	if ('property' !== get_post_type($id)) return;
   	$media = get_posts( array(
        'post_type'      => 'attachment',
        'posts_per_page' => -1,
        'post_parent'    => $id
    ) );
	if (empty($media)) return;
	foreach ($media as $file) {
        // pick what you want to do
        wp_delete_attachment($file->ID);
		//echo "<br>".get_attached_file($file->ID);
	    unlink(get_attached_file($file->ID));
    }
}					
										