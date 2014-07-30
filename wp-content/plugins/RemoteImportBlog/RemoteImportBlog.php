<?php

/*
Plugin Name: REMOTE IMPORT  BLOG
Description: REMOTE IMPORT XML IN WORDPRESS BLOG .
Version: 1.0
Author: YourDesignOnline
Author URI: http://www.yourdesignonline.com/
License: All Rights Reserved
*/
/*(c)2014 YourDesignOnline*/

register_activation_hook(__FILE__,'remote_import_blog_install');
//wp_clear_scheduled_hook( 'prefix_hourly_event_hook' );


 function remote_import_blog_install()
					{	
					
						//wp_schedule_event( time(), 'daily', 'prefix_hourly_event_hook' );		
							//prefix_do_this_hourly();					
							
					}

    //add_shortcode('imported_data_from_xml', 'display_imported_data_from_xml');							
	//add_action('prefix_hourly_event_hook', 'prefix_do_this_hourly');
	
	
	
	add_action('admin_menu','rentcafe_xml_cron_process');
	
	
	function rentcafe_xml_cron_process()
						{
							$hook =add_menu_page('XML  Remote Import ', 'XML  Remote Import', 'manage_options', 'xml_remote_import_process', 'xml_remote_import_process');
						}
						
		add_action('admin_head','addRemoteImportStyle');
	function addRemoteImportStyle()
					{
						?>
								<link rel="stylesheet" href="<?php echo plugins_url( 'css/remoteImportBlog.css' , __FILE__ ); ?>" type="text/css">
						<?php
					}
		
	function xml_remote_import_process()
					{
					
					
 						?>
						
								<div class="xmlRemoteImport" >
								<h3>XML REMOTE IMPORT </h3>
									<strong>To import the xml </strong><a href="?rentcafe_xml_cron_processes=1&page=xml_remote_import_process">click here</a>
									</div>
						<?php
					
					}
	
	if($_REQUEST['update_property_code']==1)
		{
			//add_action("wp_loaded","update_property_code",1);
			
		}
		if($_REQUEST['createPropertiesCsv']==1)
		{
			add_action("wp_loaded","createPropertiesCsv",1);
		}
	
		
		
	
		

	if(($_REQUEST['rentcafe_xml_cron_processes']==1) || ($_REQUEST['rentcafe_xml_cron_update_properties']==1) || ($_REQUEST['rentcafe_xml_cron_remove_ne_properties']==1) || ($_REQUEST['rentcafe_xml_cron_insert_properties']==1))
		{
			add_action("wp_loaded","rentcafe_xml_cron_processes",1);
		}	
		
	

	
		function rentcafe_xml_cron_processes()
						{
													
							global $wpdb;
							
							
							$dir=WP_CONTENT_DIR."/uploads/XML_file";
							ini_set("max_execution_time", 3000);
							
							if (!file_exists($dir) && !is_dir($dir)) 
								{
									@mkdir($dir);         
								}
							
							if(!is_writable($dir))
								{
									if (!chmod($dir, 777)) 
											{
												echo "Cannot change the mode of file ($dir)";
												exit;
											};
								}
							
							include 'RemoteImport-functions.php';
							
							$xml_file=WP_CONTENT_DIR."/uploads/XML_file/XMLData-Combinedprocesses".date("Y-m-d_H:i:s").".xml";
							//$xml_file=WP_CONTENT_DIR."/uploads/XML_file/XMLDATATESTING.xml";
							
							
							$remote_xml_file_url=get_remote_xml_file_url();
							$xml= file_get_contents($remote_xml_file_url);
							$fp = fopen($xml_file, 'w');
							if(!$fp)
								{
										echo "Cannot open  file ($xml_file)";
										exit;
								}
							fwrite($fp,$xml);
							fclose($fp);
							
							
							if(isset($_REQUEST['rentcafe_xml_cron_update_properties']) && ($_REQUEST['rentcafe_xml_cron_update_properties'] ==1))
								{
										prefix_do_this_update_hourly_cron($xml_file);
								}				
							else if(isset($_REQUEST['rentcafe_xml_cron_remove_ne_properties']) && ($_REQUEST['rentcafe_xml_cron_remove_ne_properties'] ==1))
								{
										remove_non_existing_properties($xml_file);
								}
							else if(isset($_REQUEST['rentcafe_xml_cron_insert_properties']) && ($_REQUEST['rentcafe_xml_cron_insert_properties'] ==1))
								{
										prefix_do_this_hourly_cron($xml_file);
								}
							else
								{
										remove_non_existing_properties($xml_file);
										prefix_do_this_update_hourly_cron($xml_file);
										prefix_do_this_hourly_cron($xml_file);	
								}
							
							
							
							
						    ?>
							             <div class="xml_import_success_msg"><strong>XML imported successfully.<strong></div>
							<?php
					
							
							
						}
		
		
		
		
		
		
		
		
		
		
		
function createPropertiesCsv()
							{
							
								include_once 'RemoteImport-functions.php';
								$args = array(
															'posts_per_page'   => -1,
															'offset'           => 0,
															'orderby'          => 'post_date',
															'order'            => 'DESC',
															'post_type'        => 'property',
															'post_status'      => 'publish'
														);
								
							
								$existing_active_properties = get_posts($args);
								$existing_primary_ids=array();
								
								$active_property_post_meta=array();
								
								foreach($existing_active_properties as $active_property)
													{
														$active_property_post_meta[$active_property->ID]=get_post_meta($active_property->ID);
													}
								$count = 0;					
								foreach($active_property_post_meta as $active_property_id=>$post_meta)
													{
														$count ++;
														//if($count >10) break;
														if(empty($post_meta['Property-code'][0]))
														{
															echo "<br>Id=".$active_property_id;
															//delete_associated_media($active_property_id);
															//wp_delete_post($active_property_id);															
														}
														
													}
													die ('end1');
								
								$date=date("Y-m-d_H_i_s");
								$csv_file=WP_CONTENT_DIR."/uploads/XML_file/Properties".$date.".csv";
								$fp = fopen($csv_file, 'w');
								if(!$fp)
									{
											echo "Cannot open  file ($xml_file)";
											exit;
									}
								
								fputcsv($fp,array( 
																"Address",
																'Property-code',
																'Location',
																'Price',
																'Beds',
																'Baths',
																'Square Footage',
																'unit status',
																'City','State',
																'Zip',
																'url',
																'Deposit',
																'general_amenities',
																'Latitude',
																'Longitude',
																'Listing_phone'
															)
											);
								$args = array(
															'posts_per_page'   => -1,
															'post_status' =>'publish',
															'post_type' => 'property'
														);
								$existing_properties = get_posts( $args );
								
								
								
								foreach($existing_properties as $active_property)
													{
													
																$Address='';
																$Property_code='';
																$Location='';
																$Price='';
																$Beds='';
																$Baths='';
																$SquareFootage='';
																$unit_status='';
																$City='';
																$State='';
																$Zip='';
																$url='';
																$Deposit='';
																$general_amenities='';
																$Latitude='';
																$Longitude='';
																$Listing_phone='';
																
															
																
														foreach(get_post_meta($active_property->ID) as $key=>$property_meta)
																			{
																						
																						
																					if($key == 'martygeocoderaddress')
																					$Address=$property_meta[0];
																					if($key == 'Property-code')
																					$Property_code=$property_meta[0];
																					if($key == 'Location')
																					$Location=$property_meta[0];
																					if($key == 'Price')
																					$Price=$property_meta[0];
																					if($key == 'Beds')
																					$Beds=$property_meta[0];
																					if($key == 'Baths')
																					$Baths=$property_meta[0];
																					if($key == 'Square Footage')
																					$SquareFootage=$property_meta[0];
																					if($key == 'unit status')
																					$unit_status=$property_meta[0];
																					if($key == 'City')
																					$City=$property_meta[0];
																					if($key == 'State')
																					$State=$property_meta[0];
																					if($key == 'Zip')
																					$Zip=$property_meta[0];
																					if($key == 'url')
																					$url=$property_meta[0];
																					if($key == 'Deposit')
																					$Deposit=$property_meta[0];
																					if($key == 'general_amenities')
																					$general_amenities=$property_meta[0];
																					if($key == 'Latitude')
																					$Latitude=$property_meta[0];
																					if($key == 'Longitude')
																					$Longitude=$property_meta[0];
																					if($key == 'Listing_phone')
																					$Listing_phone=$property_meta[0];
																					
																
																						
																						
																			}
															

															
														fputcsv($fp,array(
																						$Address,
																						$Property_code,
																						$Location,
																						$Price,
																						$Beds,
																						$Baths,
																						$SquareFootage,
																						$unit_status,
																						$City,
																						$State,
																						$Zip,
																						$url,
																						$Deposit,
																						$general_amenities,
																						$Latitude,
																						$Longitude,
																						$Listing_phone
																					)
																	);
													}
						
									die();
								
								fclose($fp);
								
							}
		
	function prefix_do_this_hourly_cron($xml_file) 		
						{
						/*  Method to insert new posts */
							
							
							global $wpdb;
							/*	
							$dir=WP_CONTENT_DIR."/uploads/XML_file";
							ini_set("max_execution_time", 3000);
							
							if (!file_exists($dir) && !is_dir($dir)) 
								{
									@mkdir($dir);         
								}
							
							if(!is_writable($dir))
								{
									if (!chmod($dir, 777)) 
											{
												echo "Cannot change the mode of file ($dir)";
												exit;
											};
								}
							
							include 'RemoteImport-functions.php';
							
							$xml_file=WP_CONTENT_DIR."/uploads/XML_file/XMLData-InsertingPost".date("Y-m-d_H:i:s").".xml";
							
							//$xml_file=WP_CONTENT_DIR."/uploads/XML_file/XMLDATATESTING.xml";
							
							
							$remote_xml_file_url=get_remote_xml_file_url();
							$xml= file_get_contents($remote_xml_file_url);
							$fp = fopen($xml_file, 'w');
							if(!$fp)
								{
										echo "Cannot open  file ($xml_file)";
										exit;
								}
							fwrite($fp,$xml);
							fclose($fp);
							*/
							
							$local_xml_file= simplexml_load_file($xml_file);
							
							
							
							$ImportReport=array();
							$newProperties=array();
							$updatedProperties=array();
							$emptyPropertyCode = array();
							
						/*		
							$properties_comparison=array();
							
								$args = array(
															'posts_per_page'   => 10,
															'offset'           => 0,
															'orderby'          => 'post_date',
															'order'            => 'DESC',
															'post_type'        => 'property',
															'post_status'      => 'publish'
														);
								$existing_properties = get_posts($args);
								foreach($existing_properties as $existing_property)
													{
														$properties_comparison[$existing_property->ID]['property_data_before_process']['ID']=$existing_property->ID;
														$properties_comparison[$existing_property->ID]['property_data_before_process']['Data']=get_post_meta($existing_property->ID);
														
													}
							*/
							$icount = 0;
							foreach($local_xml_file->Property as $property)
								{
								
								$icount++;
								//if($icount<1300) continue;
								//if($icount>1000) break;
							


																				
																				/*if(!isset($property->Identification->Address->City) || $property->Identification->Address->City == '');																			
																				$ImportReport[(string)$property->Identification->PrimaryID]['City']= 0;
																				if(!isset($property->Identification->Address->State) ||  $property->Identification->Address->State=='');											
																				$ImportReport[(string)$property->Identification->PrimaryID]['State']= 0;																				
																				if(!isset($property->Identification->Latitude) || $property->Identification->Latitude=='');														
																				$ImportReport[(string)$property->Identification->PrimaryID]['Latitude']=0;	
																				if(!isset($property->Identification->Longitude) || $property->Identification->Longitude=='');																			
																				$ImportReport[(string)$property->Identification->PrimaryID]['Longitude']=0;
																				*/
																				/*if((string)$property->Policy->Pet->Rent=='')
																				$ImportReport[(string)$property->Identification->PrimaryID]['Rent']=0;
																				
																				if(!isset($property->Amenities->Community->Garage) || $property->Amenities->Community->Garage=='');																			
																				$ImportReport[(string)$property->Identification->PrimaryID]['Garage']=0;
																				if(!isset($property->ILS_Unit->FloorplanID) || $property->ILS_Unit->FloorplanID=='');	
																				$ImportReport[(string)$property->Identification->PrimaryID]['FloorplanID']=0;
																				if(!isset($property->ILS_Unit->PropertyPrimaryID) || $property->ILS_Unit->PropertyPrimaryID=='');											
																				$ImportReport[(string)$property->Identification->PrimaryID]['PropertyPrimaryID']=0;																				
																				if(!isset($property->Amenities->Community->BusinessCenter) || $property->Amenities->Community->BusinessCenter=='');																			
																				$ImportReport[(string)$property->Identification->PrimaryID]['BusinessCenter']=0;
																				if(!isset($property->Amenities->Community->ChildCare) || (string)$property->Amenities->Community->ChildCare=='');									
																				$ImportReport[(string)$property->Identification->PrimaryID]['ChildCare']=0;		
																				if(!isset($property->Amenities->Community->ClubHouse) || (string)$property->Amenities->Community->ClubHouse=='');								
																				$ImportReport[(string)$property->Identification->PrimaryID]['ClubHouse']=0;	
																				if(!isset($property->Amenities->Community->CoverPark) || (string)$property->Amenities->Community->CoverPark=='');								
																				$ImportReport[(string)$property->Identification->PrimaryID]['CoverPark']=0;		
																				if(!isset($property->Amenities->Community->FitnessCenter) || (string)$property->Amenities->Community->FitnessCenter=='');							
																				$ImportReport[(string)$property->Identification->PrimaryID]['FitnessCenter']=0;		
																				if(!isset($property->Amenities->Community->Gate) || (string)$property->Amenities->Community->Gate=='');											
																				$ImportReport[(string)$property->Identification->PrimaryID]['Gate']=0;		
																				if(!isset($property->Amenities->Community->HighSpeed) || (string)$property->Amenities->Community->HighSpeed=='');								
																				$ImportReport[(string)$property->Identification->PrimaryID]['HighSpeed']=0;		
																				if(!isset($property->Amenities->Community->Laundry) || (string)$property->Amenities->Community->Laundry=='');										
																				$ImportReport[(string)$property->Identification->PrimaryID]['Laundry']=0;	
																				if(!isset($property->Amenities->Community->Pool) || (string)$property->Amenities->Community->Pool=='');											
																				$ImportReport[(string)$property->Identification->PrimaryID]['Pool']=0;	
																				if(!isset($property->Amenities->Community->PlayGround) || (string)$property->Amenities->Community->PlayGround=='');
																				$ImportReport[(string)$property->Identification->PrimaryID]['PlayGround']=0;
																				if(!isset($property->Amenities->Community->ShortTermLease) || (string)$property->Amenities->Community->ShortTermLease=='');						
																				$ImportReport[(string)$property->Identification->PrimaryID]['ShortTermLease']=0;		
																				if(!isset($property->Amenities->Community->Spa) || (string)$property->Amenities->Community->Spa=='');											
																				$ImportReport[(string)$property->Identification->PrimaryID]['Spa']=0;	
																				if(!isset($property->Amenities->Community->StorageSpace) || (string)$property->Amenities->Community->StorageSpace=='');							
																				$ImportReport[(string)$property->Identification->PrimaryID]['StorageSpace']=0;	
																				if(!isset($property->Amenities->Community->Transportation) || (string)$property->Amenities->Community->Transportation=='');							
																				$ImportReport[(string)$property->Identification->PrimaryID]['Transportation']=0;	
																				if(!isset($property->Amenities->Community->Availability24Hours) || (string)$property->Amenities->Community->Availability24Hours=='');					
																				$ImportReport[(string)$property->Identification->PrimaryID]['Availability24Hours']=0;	
																				if(!isset($property->Amenities->Community->BasketballCourt) || (string)$property->Amenities->Community->BasketballCourt=='');						
																				$ImportReport[(string)$property->Identification->PrimaryID]['BasketballCourt']=0;	
																				if(!isset($property->Amenities->Community->ClubDiscount) || (string)$property->Amenities->Community->ClubDiscount=='');							
																				$ImportReport[(string)$property->Identification->PrimaryID]['ClubDiscount']=0;	
																				if(!isset($property->Amenities->Community->Concierge) || (string)$property->Amenities->Community->Concierge=='');	
																				$ImportReport[(string)$property->Identification->PrimaryID]['Concierge']=0;
																				if(!isset($property->Amenities->Community->DoorAttendant) || (string)$property->Amenities->Community->DoorAttendant=='');							
																				$ImportReport[(string)$property->Identification->PrimaryID]['DoorAttendant']=0;	
																				if(!isset($property->Amenities->Community->Elevator) || (string)$property->Amenities->Community->Elevator=='');									
																				$ImportReport[(string)$property->Identification->PrimaryID]['Elevator']=0;	
																				if(!isset($property->Amenities->Community->FreeWeights) || (string)$property->Amenities->Community->FreeWeights=='');							
																				$ImportReport[(string)$property->Identification->PrimaryID]['FreeWeights']=0;	
																				if(!isset($property->Amenities->Community->HouseSitting) || (string)$property->Amenities->Community->HouseSitting=='');							
																				$ImportReport[(string)$property->Identification->PrimaryID]['HouseSitting']=0;	
																				if(!isset($property->Amenities->Community->GroupExcercise) || (string)$property->Amenities->Community->GroupExcercise=='');						
																				$ImportReport[(string)$property->Identification->PrimaryID]['GroupExcercise']=0;	
																				if(!isset($property->Amenities->Community->GuestRoom) || (string)$property->Amenities->Community->GuestRoom=='');								
																				$ImportReport[(string)$property->Identification->PrimaryID]['GuestRoom']=0;	
																				if(!isset($property->Amenities->Community->Housekeeping) || (string)$property->Amenities->Community->Housekeeping=='');							
																				$ImportReport[(string)$property->Identification->PrimaryID]['Housekeeping']=0;	
																				if(!isset($property->Amenities->Community->Library) || (string)$property->Amenities->Community->Library=='');										
																				$ImportReport[(string)$property->Identification->PrimaryID]['Library']=0;	
																				if(!isset($property->Amenities->Community->MealService) || (string)$property->Amenities->Community->MealService=='');								
																				$ImportReport[(string)$property->Identification->PrimaryID]['MealService']=0;	
																				if(!isset($property->Amenities->Community->NightPatrol) || (string)$property->Amenities->Community->NightPatrol=='');								
																				$ImportReport[(string)$property->Identification->PrimaryID]['NightPatrol']=0;	
																				if(!isset($property->Amenities->Community->OnSiteMaintenance) || (string)$property->Amenities->Community->OnSiteMaintenance=='');				
																				$ImportReport[(string)$property->Identification->PrimaryID]['OnSiteMaintenance']=0;	
																				if(!isset($property->Amenities->Community->OnSiteManagement) || (string)$property->Amenities->Community->OnSiteManagement=='');				
																				$ImportReport[(string)$property->Identification->PrimaryID]['OnSiteManagement']=0;	
																				if(!isset($property->Amenities->Community->PacakageReceiving) || (string)$property->Amenities->Community->PacakageReceiving=='');				
																				$ImportReport[(string)$property->Identification->PrimaryID]['PacakageReceiving']=0;	
																				if(!isset($property->Amenities->Community->Racquetball) || (string)$property->Amenities->Community->Racquetball=='');								
																				$ImportReport[(string)$property->Identification->PrimaryID]['Racquetball']=0;	
																				if(!isset($property->Amenities->Community->RecRoom) || (string)$property->Amenities->Community->RecRoom=='');									
																				$ImportReport[(string)$property->Identification->PrimaryID]['RecRoom']=0;	
																				if(!isset($property->Amenities->Community->Sauna) || (string)$property->Amenities->Community->Sauna=='');										
																				$ImportReport[(string)$property->Identification->PrimaryID]['Sauna']=0;	
																				if(!isset($property->Amenities->Community->Sundeck) || (string)$property->Amenities->Community->Sundeck=='');									
																				$ImportReport[(string)$property->Identification->PrimaryID]['Sundeck']=0;	
																				if(!isset($property->Amenities->Community->TennisCourt) || (string)$property->Amenities->Community->TennisCourt=='');								
																				$ImportReport[(string)$property->Identification->PrimaryID]['TennisCourt']=0;	
																				if(!isset($property->Amenities->Community->TVLounge) || (string)$property->Amenities->Community->TVLounge=='');									
																				$ImportReport[(string)$property->Identification->PrimaryID]['TVLounge']=0;	
																				if(!isset($property->Amenities->Community->Vintage) || (string)$property->Amenities->Community->Vintage=='');										
																				$ImportReport[(string)$property->Identification->PrimaryID]['Vintage']=0;	
																				if(!isset($property->Amenities->Community->VolleyballCourt) || (string)$property->Amenities->Community->VolleyballCourt=='');
																				$ImportReport[(string)$property->Identification->PrimaryID]['VolleyballCourt']=0;
																				if(!isset($property->Amenities->Floorplan->AdditionalStorage) || (string)$property->Amenities->Floorplan->AdditionalStorage=='');						
																				$ImportReport[(string)$property->Identification->PrimaryID]['AdditionalStorage']=0;																				
																				if(!isset($property->Amenities->Floorplan->AirConditioner) || (string)$property->Amenities->Floorplan->AirConditioner=='');																			
																				$ImportReport[(string)$property->Identification->PrimaryID]['AirConditioner']=0;
																				if(!isset($property->Amenities->Floorplan->Balcony) || (string)$property->Amenities->Floorplan->Balcony=='');										
																				$ImportReport[(string)$property->Identification->PrimaryID]['Balcony']=0;	
																				if(!isset($property->Amenities->Floorplan->DishWasher) || (string)$property->Amenities->Floorplan->DishWasher=='');								
																				$ImportReport[(string)$property->Identification->PrimaryID]['DishWasher']=0;	
																				if(!isset($property->Amenities->Floorplan->Disposal) || (string)$property->Amenities->Floorplan->Disposal=='');										
																				$ImportReport[(string)$property->Identification->PrimaryID]['Disposal']=0;	
																				if(!isset($property->Amenities->Floorplan->Fireplace) || (string)$property->Amenities->Floorplan->Fireplace=='');										
																				$ImportReport[(string)$property->Identification->PrimaryID]['Fireplace']=0;	
																				if(!isset($property->Amenities->Floorplan->Furnished) || (string)$property->Amenities->Floorplan->Furnished=='');										
																				$ImportReport[(string)$property->Identification->PrimaryID]['Furnished']=0;	
																				if(!isset($property->Amenities->Floorplan->IndividualClimateControl) || (string)$property->Amenities->Floorplan->IndividualClimateControl='');			
																				$ImportReport[(string)$property->Identification->PrimaryID]['IndividualClimateControl']=0;	
																				if(!isset($property->Amenities->Floorplan->LargeClosets) || (string)$property->Amenities->Floorplan->LargeClosets=='');								
																				$ImportReport[(string)$property->Identification->PrimaryID]['LargeClosets']=0;	
																				if(!isset($property->Amenities->Floorplan->Patio) || (string)$property->Amenities->Floorplan->Patio=='');												
																				$ImportReport[(string)$property->Identification->PrimaryID]['Patio']=0;	
																				if(!isset($property->Amenities->Floorplan->Washer) || (string)$property->Amenities->Floorplan->Washer='');										
																				$ImportReport[(string)$property->Identification->PrimaryID]['Washer']=0;	
																				if(!isset($property->Amenities->Floorplan->WheelChair) || (string)$property->Amenities->Floorplan->WheelChair=='');									
																				$ImportReport[(string)$property->Identification->PrimaryID]['WheelChair']=0;	
																				if(!isset($property->Amenities->Floorplan->WD_Hookup) || (string)$property->Amenities->Floorplan->WD_Hookup=='');								
																				$ImportReport[(string)$property->Identification->PrimaryID]['WD_Hookup']=0;	
																				if(!isset($property->Amenities->Floorplan->Alarm) || (string)$property->Amenities->Floorplan->Alarm=='');											
																				$ImportReport[(string)$property->Identification->PrimaryID]['Alarm']=0;	
																				if(!isset($property->Amenities->Floorplan->Carport) || (string)$property->Amenities->Floorplan->Carport=='');											
																				$ImportReport[(string)$property->Identification->PrimaryID]['Carport']=0;	
																				if(!isset($property->Amenities->Floorplan->CeilingFan) || (string)$property->Amenities->Floorplan->CeilingFan=='');									
																				$ImportReport[(string)$property->Identification->PrimaryID]['CeilingFan']=0;	
																				if(!isset($property->Amenities->Floorplan->ControlledAccess) || (string)$property->Amenities->Floorplan->ControlledAccess=='');						
																				$ImportReport[(string)$property->Identification->PrimaryID]['ControlledAccess']=0;	
																				if(!isset($property->Amenities->Floorplan->Courtyard) || (string)$property->Amenities->Floorplan->Courtyard=='');										
																				$ImportReport[(string)$property->Identification->PrimaryID]['Courtyard']=0;	
																				if(!isset($property->Amenities->Floorplan->Dryer) || (string)$property->Amenities->Floorplan->Dryer=='');											
																				$ImportReport[(string)$property->Identification->PrimaryID]['Dryer']=0;	
																				if(!isset($property->Amenities->Floorplan->Handrails) || (string)$property->Amenities->Floorplan->Handrails=='');										
																				$ImportReport[(string)$property->Identification->PrimaryID]['Handrails']=0;	
																				if(!isset($property->Amenities->Floorplan->Microwave) || (string)$property->Amenities->Floorplan->Microwave=='');									
																				$ImportReport[(string)$property->Identification->PrimaryID]['Microwave']=0;	
																				if(!isset($property->Amenities->Floorplan->PrivateBalcony) || (string)$property->Amenities->Floorplan->PrivateBalcony=='');							
																				$ImportReport[(string)$property->Identification->PrimaryID]['PrivateBalcony']=0;	
																				if(!isset($property->Amenities->Floorplan->PrivatePatio) || (string)$property->Amenities->Floorplan->PrivatePatio=='');								
																				$ImportReport[(string)$property->Identification->PrimaryID]['PrivatePatio']=0;	
																				if(!isset($property->Amenities->Floorplan->Refrigerator) || (string)$property->Amenities->Floorplan->Refrigerator=='');									
																				$ImportReport[(string)$property->Identification->PrimaryID]['Refrigerator']=0;	
																				if(!isset($property->Amenities->Floorplan->Skylight) || (string)$property->Amenities->Floorplan->Skylight=='');										
																				$ImportReport[(string)$property->Identification->PrimaryID]['Skylight']=0;	
																				if(!isset($property->Amenities->Floorplan->View) || (string)$property->Amenities->Floorplan->View=='');												
																				$ImportReport[(string)$property->Identification->PrimaryID]['View']=0;	
																				if(!isset($property->Amenities->Floorplan->WindowCoverings) || (string)$property->Amenities->Floorplan->WindowCoverings=='');	
																				$ImportReport[(string)$property->Identification->PrimaryID]['WindowCoverings']=0;
																				*/							
																				
																				//$zip=(string)$property->Identification->Address->Zip;
																				//$import_databases = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."xml_import_zipcodes where zip= $zip" );
																
																
																	//if(isset($import_databases[0]) && $import_databases[0] != '')
																	{
																				
																				$isNew = false;
																				$primary_id =(string)$property->Identification->PrimaryID;
																				$Property_code =(string)$property->ILS_Unit->UnitID;
																				if(empty($Property_code) || $Property_code=='') {
																					$emptyPropertyCode[$property->Identification->Address->Address1][]=1;
																				continue;
																				}
																				$args = array(
																										'post_type' => 'property',
																										'meta_query' => array(
																																				array(
																																							'key' => 'Property-code',
																																							'value' => $Property_code,
																																						)
																																			)
																										);
																				$postslist = get_posts( $args );
																									
																				$_email=(string)$property->Identification->Email;
																				if (!isset($postslist[0]))
																						{



																											if(!isset($property->Identification->Address->Zip) || $property->Identification->Address->Zip =='')
																											$ImportReport[$Property_code]['zip']=0;
																											if(!isset($property->Identification->Address->Address1) || $property->Identification->Address->Address1=='')
																											$ImportReport[$Property_code]['Address1']=0;
																											if(!isset($property->ILS_Unit->UnitBedrooms) || (string)$property->ILS_Unit->UnitBedrooms=='')
																											$ImportReport[$Property_code]['UnitBedrooms']=0;
																											if(!isset($property->ILS_Unit->UnitBathrooms) || (string)$property->ILS_Unit->UnitBathrooms=='')
																											$ImportReport[$Property_code]['UnitBathrooms']=0;
																											if(!isset($property->ILS_Unit->MinSquareFeet) || (string)$property->ILS_Unit->MinSquareFeet=='')
																											$ImportReport[$Property_code]['MinSquareFeet']=0;



																											
																						
																											$isNew = true;
																											$_p = array();
																											$_p['post_title'] = (string)$property->Identification->Address->Address1;
																											$_p['post_content'] = '';//print_r($property,true);
																											$_p['post_status'] = 'publish';
																											$_p['post_type'] = 'property';
																											$_p['comment_status'] = 'closed';
																											$_p['ping_status'] = 'closed';
																											$the_page_id = wp_insert_post( $_p );
																											$get_post_meta = get_post_meta($the_page_id,'Property-code');
																											if(empty($get_post_meta))
																											{
																												add_post_meta($the_page_id,'Property-code',$Property_code);
																											}						
																											$newProperties[$the_page_id]= (string)$property->Identification->Address->Address1;
																											$category = rm_find_categories($_email);
																											if(!$category)
																												$ImportReport[$Property_code]['Market']=0;
																											else
																											{																											
																												$term_taxonomy_ids = wp_set_object_terms( $the_page_id, $category, 'market', false );
																												update_post_meta($the_page_id,'_email',$_email); 
																											}	
																											
																					
																					//if(!$isNew) continue;
																				//Update Images
																				$imagesOrig =array();
																				$imagesOrigStr = '';
																				if(isset($property->File))
																				{
																					foreach($property->File as $img)
																									{
																										$imagesOrig[] =(string) $img->Src;		
																										break;		
																									}
																					if(count($imagesOrig))
																							$imagesOrigStr = implode(",", $imagesOrig);
																					//update_post_meta($the_page_id,'_rm_images1',$imagesOrigStr);	
																					if(update_post_meta($the_page_id,'_rm_images',$imagesOrigStr) )
																					{	
																							$images=array();
																							$imagesFile=array();
																							if(isset($imagesOrig[0]) )
																								{
																										$img_src= $imagesOrig[0];
																										if($img_src)
																										{
																											$src_img=rm_fetch_remote_file($the_page_id, $img_src);
																											if(isset($src_img['url']))
																											{
																												$images[]= (string)$src_img['url'];
																												$imagesFile[]= (string)$src_img['file'];
																											}
																										}
																								}
																							if(isset ($imagesFile[0] ))
																							{
																								$filename = $imagesFile[0];
																								// The ID of the post this attachment is for.
																								$parent_post_id = $the_page_id;
	
																								// Check the type of tile. We'll use this as the 'post_mime_type'.
																								$filetype = wp_check_filetype( basename( $filename ), null );
	
																								// Get the path to the upload directory.
																								$wp_upload_dir = wp_upload_dir();
	
																								// Prepare an array of post data for the attachment.
																								$attachment = array(
																									'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ), 
																									'post_mime_type' => $filetype['type'],
																									'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
																									'post_content'   => '',
																									'post_status'    => 'inherit'
																								);
	
																								// Insert the attachment.
																								$attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );
	
																								// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
																								require_once( ABSPATH . 'wp-admin/includes/image.php' );
	
																								// Generate the metadata for the attachment, and update the database record.
																								$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
																								wp_update_attachment_metadata( $attach_id, $attach_data );
																								// add featured image to post
																								//add_post_meta($the_page_id, '_thumbnail_id', $attach_id);
																								//delete_post_thumbnail ($the_page_id);
																								set_post_thumbnail( $the_page_id, $attach_id );
																								$imagesStr = implode(",", $images);
																								update_post_meta($the_page_id,'photos',$imagesStr);																							
																							}
																					}
																				}
																				else
																				{
																					$ImportReport[$Property_code]['image']=0;
																				}	
																				$address = (string)$property->Identification->Address->Address1;
																				$city = (string)$property->Identification->Address->City;
																				$state = (string)$property->Identification->Address->State;
																				$lat = $property->Identification->Latitude;
																				$long = $property->Identification->Longitude;
																				geoCoderUpdate($the_page_id, $address, $city, $state, $lat, $long );
																				update_post_meta($the_page_id,'primary_id',$primary_id);
																				//file_put_contents('XMLDATAIMPORTTEST.txt',(string)$property->ILS_Unit->UnitBedrooms,FILE_APPEND);
																				update_post_meta($the_page_id,'Location',(string)$property->Identification->Address->Address1);
																				update_post_meta($the_page_id,'Price',(string)$property->Policy->Pet->Rent);
																				update_post_meta($the_page_id,'Beds',(string)$property->ILS_Unit->UnitBedrooms);
																				update_post_meta($the_page_id,'Baths',(string)$property->ILS_Unit->UnitBathrooms);																			
																				update_post_meta($the_page_id,'Square Footage',(string)$property->ILS_Unit->MinSquareFeet);
																				update_post_meta($the_page_id,'unit status','UnitEconomicStatus='.(string)$property->ILS_Unit->UnitEconomicStatus.'&UnitOccupancyStatus='.(string)$property->ILS_Unit->UnitOccupancyStatus);																			
																				update_post_meta($the_page_id,'City',(string)$property->Identification->Address->City);																			
																				update_post_meta($the_page_id,'State',(string)$property->Identification->Address->State);																			
																				update_post_meta($the_page_id,'Zip',(string)$property->Identification->Address->Zip);
																				update_post_meta($the_page_id,'url',(string)$property->ILS_Unit->ApplyOnlineURL);	
																				update_post_meta($the_page_id,'Deposit',(string)$property->Floorplan->Deposit);																			
																				
																				$general_amenities='';
																				if(is_array($property->Amenities->General))
																							{
																								foreach($property->Amenities->General as $general)
																												{
																														$general_amenities.=$general->AmenityName.', ';
																												}
																												$general_amenities=substr($general_amenities, 0, -2);
																							
																							}
																							else
																								{
																									$general_amenities.=$property->Amenities->General->AmenityName;
																								}		
																				
																				update_post_meta($the_page_id,'general_amenities',$general_amenities);																			
																				update_post_meta($the_page_id,'Title',(string)$property->Identification->Address->Address1);																			
																				update_post_meta($the_page_id,'Latitude',(string)$property->Identification->Latitude);																			
																				update_post_meta($the_page_id,'Longitude',(string)$property->Identification->Longitude);	
																				update_post_meta($the_page_id,'Listing_phone',(string)$property->Identification->Phone);
																				update_post_meta($the_page_id,'Property Code',(string)$property->ILS_Unit->PropertyPrimaryID);
																				$valuesMeta = array();
																				$valuesMeta['property-code']= $Property_code;
																				$valuesMeta['unit-status']= (string)$property->ILS_Unit->UnitOccupancyStatus;
																				$valuesMeta['property-type']= 'Single Family Home';
																				$valuesMeta['amenities']=$general_amenities;
																				$valuesMeta['community-amenities']='';
																				$valuesMeta['family']='';
																				$valuesMeta['sustainable-living']='';
																				$valuesMeta['fitness']= '';
																				$valuesMeta['address']= (string)$property->Identification->Address->Address1;
																				$valuesMeta['city']= (string)$property->Identification->Address->City  ;
																				$valuesMeta['state']= (string)$property->Identification->Address->State;
																				$valuesMeta['zip-code']= (string)$property->Identification->Address->Zip;
																				$valuesMeta['bedrooms']= (string)$property->ILS_Unit->UnitBedrooms;
																				$valuesMeta['bathrooms']= (string)$property->ILS_Unit->UnitBathrooms;
																				$valuesMeta['square-footage']= (string)$property->ILS_Unit->MinSquareFeet;
																				$valuesMeta['rent']= (string)$property->ILS_Unit->UnitRent;
																				$valuesMeta['url-to-property-detail-page']= (string)$property->Identification->WebSite;
																				
																				update_property_meta_box($the_page_id, $valuesMeta);																				
																				/*
																				update_post_meta($the_page_id,'Property Code',(string)$property->ILS_Unit->PropertyPrimaryID);																			
																				update_post_meta($the_page_id,'property-type',(string)$property->ILS_Unit->UnitBathrooms);																			
																				update_post_meta($the_page_id,'market',(string)$property->ILS_Unit->UnitBathrooms);																			
																				update_post_meta($the_page_id,'long_description',(string)$property->Information->LongDescription);

																				$attributes=array();
																				$Cat_Allowed='';
																				$Dog_Allowed='';
																				foreach($property->Policy->Pet as $pet)
																						{
																								$attributes=$pet->attributes();
																									
																												if($attributes['type']=='Cat')
																												$Cat_Allowed=$attributes['allowed'];
																										
																												if($attributes['type']=='Dog')
																												$Dog_Allowed=$attributes['allowed'];	
																											
																						}
																				
																				update_post_meta($the_page_id,'Cat_Allowed',$Cat_Allowed);	
																				update_post_meta($the_page_id,'Dog_Allowed',$Dog_Allowed);	
																				update_post_meta($the_page_id,'garage',(string)$property->Amenities->Community->Garage);																			
																				update_post_meta($the_page_id,'floorplanid',(string)$property->ILS_Unit->FloorplanID);	
																				update_post_meta($the_page_id,'propertyprimaryid',(string)$property->ILS_Unit->PropertyPrimaryID);																			
																				update_post_meta($the_page_id,'businesscenter',(string)$property->Amenities->Community->BusinessCenter);																			
																				update_post_meta($the_page_id,'childcare',(string)$property->Amenities->Community->ChildCare);																			
																				update_post_meta($the_page_id,'clubhouse',(string)$property->Amenities->Community->ClubHouse);																			
																				update_post_meta($the_page_id,'coverpark',(string)$property->Amenities->Community->CoverPark);																			
																				update_post_meta($the_page_id,'fitnesscenter',(string)$property->Amenities->Community->FitnessCenter);																			
																				update_post_meta($the_page_id,'gate',(string)$property->Amenities->Community->Gate);																			
																				update_post_meta($the_page_id,'highspeed',(string)$property->Amenities->Community->HighSpeed);																			
																				update_post_meta($the_page_id,'laundry',(string)$property->Amenities->Community->Laundry);																			
																				update_post_meta($the_page_id,'pool',(string)$property->Amenities->Community->Pool);																			
																				update_post_meta($the_page_id,'playground',(string)$property->Amenities->Community->PlayGround);
																				
																				update_post_meta($the_page_id,'shorttermlease',(string)$property->Amenities->Community->ShortTermLease);																			
																				update_post_meta($the_page_id,'spa',(string)$property->Amenities->Community->Spa);																			
																				update_post_meta($the_page_id,'storagespace',(string)$property->Amenities->Community->StorageSpace);																			
																				update_post_meta($the_page_id,'transportation',(string)$property->Amenities->Community->Transportation);																			
																				update_post_meta($the_page_id,'availability24hours',(string)$property->Amenities->Community->Availability24Hours);																			
																				update_post_meta($the_page_id,'basketballcourt',(string)$property->Amenities->Community->BasketballCourt);																			
																				update_post_meta($the_page_id,'clubdiscount',(string)$property->Amenities->Community->ClubDiscount);																			
																				update_post_meta($the_page_id,'concierge',(string)$property->Amenities->Community->Concierge);	
																				update_post_meta($the_page_id,'doorattendent',(string)$property->Amenities->Community->DoorAttendant);																			
																				update_post_meta($the_page_id,'elevator',(string)$property->Amenities->Community->Elevator);																			
																				update_post_meta($the_page_id,'freeweights',(string)$property->Amenities->Community->FreeWeights);																			
																				update_post_meta($the_page_id,'housesitting',(string)$property->Amenities->Community->HouseSitting);																			
																				update_post_meta($the_page_id,'groupexcercise',(string)$property->Amenities->Community->GroupExcercise);																			
																				update_post_meta($the_page_id,'guestroom',(string)$property->Amenities->Community->GuestRoom);																			
																				update_post_meta($the_page_id,'housekeeping',(string)$property->Amenities->Community->Housekeeping);																			
																				update_post_meta($the_page_id,'library',(string)$property->Amenities->Community->Library);																			
																				update_post_meta($the_page_id,'mealservice',(string)$property->Amenities->Community->MealService);																			
																				update_post_meta($the_page_id,'nightpatrol',(string)$property->Amenities->Community->NightPatrol);																			
																				update_post_meta($the_page_id,'onsitemaintenance',(string)$property->Amenities->Community->OnSiteMaintenance);																			
																				update_post_meta($the_page_id,'onsitemanagement',(string)$property->Amenities->Community->OnSiteManagement);																			
																				update_post_meta($the_page_id,'packagereceiving',(string)$property->Amenities->Community->PacakageReceiving);																			
																				update_post_meta($the_page_id,'racquetball',(string)$property->Amenities->Community->Racquetball);																			
																				update_post_meta($the_page_id,'recroom',(string)$property->Amenities->Community->RecRoom);																			
																				update_post_meta($the_page_id,'sauna',(string)$property->Amenities->Community->Sauna);																			
																				update_post_meta($the_page_id,'sundeck',(string)$property->Amenities->Community->Sundeck);																			
																				update_post_meta($the_page_id,'tenniscourt',(string)$property->Amenities->Community->TennisCourt);																			
																				update_post_meta($the_page_id,'tvlounge',(string)$property->Amenities->Community->TVLounge);																			
																				update_post_meta($the_page_id,'vintage',(string)$property->Amenities->Community->Vintage);																			
																				update_post_meta($the_page_id,'volleyballcourt',(string)$property->Amenities->Community->VolleyballCourt);
																				update_post_meta($the_page_id,'addtionalstorage',(string)$property->Amenities->Floorplan->AdditionalStorage);																			
																				update_post_meta($the_page_id,'airconditioner',(string)$property->Amenities->Floorplan->AirConditioner);																			
																				update_post_meta($the_page_id,'balcony',(string)$property->Amenities->Floorplan->Balcony);																			
																				update_post_meta($the_page_id,'dishwasher',(string)$property->Amenities->Floorplan->DishWasher);																			
																				update_post_meta($the_page_id,'disposal',(string)$property->Amenities->Floorplan->Disposal);																			
																				update_post_meta($the_page_id,'fireplace',(string)$property->Amenities->Floorplan->Fireplace);																			
																				update_post_meta($the_page_id,'furnished',(string)$property->Amenities->Floorplan->Furnished);																			
																				update_post_meta($the_page_id,'individualclimatecontrol',(string)$property->Amenities->Floorplan->IndividualClimateControl);																			
																				update_post_meta($the_page_id,'largerclosets',(string)$property->Amenities->Floorplan->LargeClosets);																			
																				update_post_meta($the_page_id,'patio',(string)$property->Amenities->Floorplan->Patio);																			
																				update_post_meta($the_page_id,'washer',(string)$property->Amenities->Floorplan->Washer);																			
																				update_post_meta($the_page_id,'wheelchair',(string)$property->Amenities->Floorplan->WheelChair);																			
																				update_post_meta($the_page_id,'wd_hookup',(string)$property->Amenities->Floorplan->WD_Hookup);																			
																				update_post_meta($the_page_id,'alarm',(string)$property->Amenities->Floorplan->Alarm);																			
																				update_post_meta($the_page_id,'carport',(string)$property->Amenities->Floorplan->Carport);																			
																				update_post_meta($the_page_id,'ceilingfan',(string)$property->Amenities->Floorplan->CeilingFan);																			
																				update_post_meta($the_page_id,'controlledaccess',(string)$property->Amenities->Floorplan->ControlledAccess);																			
																				update_post_meta($the_page_id,'courtyard',(string)$property->Amenities->Floorplan->Courtyard);																			
																				update_post_meta($the_page_id,'dryer',(string)$property->Amenities->Floorplan->Dryer);																			
																				update_post_meta($the_page_id,'handrails',(string)$property->Amenities->Floorplan->Handrails);																			
																				update_post_meta($the_page_id,'microwave',(string)$property->Amenities->Floorplan->Microwave);																			
																				update_post_meta($the_page_id,'privatebalcony',(string)$property->Amenities->Floorplan->PrivateBalcony);																			
																				update_post_meta($the_page_id,'privatepatio',(string)$property->Amenities->Floorplan->PrivatePatio);																			
																				update_post_meta($the_page_id,'refridgerator',(string)$property->Amenities->Floorplan->Refrigerator);																			
																				update_post_meta($the_page_id,'skylight',(string)$property->Amenities->Floorplan->Skylight);																			
																				update_post_meta($the_page_id,'view',(string)$property->Amenities->Floorplan->View);																			
																				update_post_meta($the_page_id,'windowcoverings',(string)$property->Amenities->Floorplan->WindowCoverings);	
																				*/
																			}	
																				
																	}	
																	//echo "<br/>";echo "<br/>";
													
											
								}
								
								$report_message='<div><b>Errors or missing data from today&#39;s Rent Cafe feed: </b><br/>';
								
								foreach($ImportReport as $primaryId=>$emptyFields)
												{
													
													$report_message.='<br/><b>Errors or missing data with Property Code '.$primaryId.':</b>  ';
													foreach($emptyFields as $field=>$value)
																	{
																		$report_message.='      '.$field.'  , ';
																	}
																	$report_message=substr($report_message, 0, -2);
													
												}
								if(count($emptyPropertyCode))
								{				
									$report_message.='<br/><br/><b>Properties with empty Property Code : </b>'.count($emptyPropertyCode);
									foreach($emptyPropertyCode as $address)
									{
										$report_message.='<br/>Address:'.$address;
									}
								}								
								$report_message.='<br/><br/><b>New properties added to the database: </b>'.count($newProperties);
												
														//$report_message.='<h3>New Properties</h3><br/>';
								foreach($newProperties as $page_id=>$new_property)
													{
														$report_message.='<br/>ID='.$page_id.', Title='.$new_property;
													}
								
								
								
								/*$report_message.='<br/><br/><b>Updated properties Report : </b>'.count($updatedProperties);;
								foreach($updatedProperties as $page_id=>$updated_property)
													{
															$report_message.='<br/>ID='.$page_id.', Title='.$updated_property;
													}
								*/
								$args = array(
															'posts_per_page'   => -1,
															'offset'           => 0,
															'orderby'          => 'post_date',
															'order'            => 'DESC',
															'post_type'        => 'property',
															'post_status'      => 'publish'
														);
								
								
								$active_properties = get_posts($args);
								$total_active_properties=count($active_properties);
								
								
								
								
								
								
								
								
								
								

						/*			
								$report_message.='<br/><h3>Comparison of property data before and after the process</h3>';
		
								$args = array(
															'posts_per_page'   => 10,
															'offset'           => 0,
															'orderby'          => 'post_date',
															'order'            => 'DESC',
															'post_type'        => 'property',
															'post_status'      => 'publish'
														);
								
								
								$updated_properties = get_posts($args);
								foreach($updated_properties as $updated_property)
													{
														$properties_comparison[$updated_property->ID]['property_data_after_process']['ID']=$updated_property->ID;
														$properties_comparison[$updated_property->ID]['property_data_after_process']['Data']=get_post_meta($updated_property->ID);			
													}
								
								foreach($properties_comparison as $property_id=>$property_data)
													{
															$report_message.='<br/><h3>Property Id='.$property_id.'</h3><br/>';
															$report_message.='<br/> <h3>Before Process</h3><br/>';
															foreach($property_data as $property_data_type=>$propertyData)
																			{
																			
																				if($property_data_type == 'property_data_before_process')
																					foreach($propertyData['Data'] as $key=>$data)
																						{
																							$report_message.='    '.$key.' = '.$data[0].'  ,  ';
																						}
																			
																			}
															$report_message.='<br/><h3>After Process</h3><br/>';				
															foreach($property_data as $property_data_type=>$propertyData)
																			{
																				if($property_data_type == 'property_data_after_process')
																					foreach($propertyData['Data'] as $key=>$data)
																						{
																							$report_message.='    '.$key.' = '.$data[0].'  ,  ';
																						}
																			
																			}
															
													}					
											*/
											
														$report_message.='<br/><br/>';
														
														//echo $report_message;
								
											
													//$to='danish@yourdesignonline.com, rob@yourdesignonline.com';
													$to='danish@yourdesignonline.com,damond.farrar@yourdesignonline.com';
													$subject = 'IH Daily Property Insert Status '.date("Y-m-d H:i:s");
													$message=$report_message;
													notify_to($to,$subject,$message);
											
								
							
						}
	

	function prefix_do_this_update_hourly_cron($xml_file) 		
						{
							/*  Method to update existing posts */
							global $wpdb;
							
							$local_xml_file= simplexml_load_file($xml_file);
							
							
							
							$ImportReport=array();
							$newProperties=array();
							$updatedProperties=array();
							
							/*		
								$properties_comparison=array();
							
								$args = array(
															'posts_per_page'   => 10,
															'offset'           => 0,
															'orderby'          => 'post_date',
															'order'            => 'DESC',
															'post_type'        => 'property',
															'post_status'      => 'publish'
														);
								$existing_properties = get_posts($args);
								foreach($existing_properties as $existing_property)
													{
														$properties_comparison[$existing_property->ID]['property_data_before_process']['ID']=$existing_property->ID;
														$properties_comparison[$existing_property->ID]['property_data_before_process']['Data']=get_post_meta($existing_property->ID);
														
													}
							*/
							$icount = 0;
							foreach($local_xml_file->Property as $property)
								{
								$icount++;
								//if($icount<900) continue;
								//if($icount>1000) break;
							


																				
																				/*if(!isset($property->Identification->Address->City) || $property->Identification->Address->City == '');																			
																				$ImportReport[(string)$property->Identification->PrimaryID]['City']= 0;
																				if(!isset($property->Identification->Address->State) ||  $property->Identification->Address->State=='');											
																				$ImportReport[(string)$property->Identification->PrimaryID]['State']= 0;																				
																				if(!isset($property->Identification->Latitude) || $property->Identification->Latitude=='');														
																				$ImportReport[(string)$property->Identification->PrimaryID]['Latitude']=0;	
																				if(!isset($property->Identification->Longitude) || $property->Identification->Longitude=='');																			
																				$ImportReport[(string)$property->Identification->PrimaryID]['Longitude']=0;
																				*/
																				/*if((string)$property->Policy->Pet->Rent=='')
																				$ImportReport[(string)$property->Identification->PrimaryID]['Rent']=0;
																				
																				if(!isset($property->Amenities->Community->Garage) || $property->Amenities->Community->Garage=='');																			
																				$ImportReport[(string)$property->Identification->PrimaryID]['Garage']=0;
																				if(!isset($property->ILS_Unit->FloorplanID) || $property->ILS_Unit->FloorplanID=='');	
																				$ImportReport[(string)$property->Identification->PrimaryID]['FloorplanID']=0;
																				if(!isset($property->ILS_Unit->PropertyPrimaryID) || $property->ILS_Unit->PropertyPrimaryID=='');											
																				$ImportReport[(string)$property->Identification->PrimaryID]['PropertyPrimaryID']=0;																				
																				if(!isset($property->Amenities->Community->BusinessCenter) || $property->Amenities->Community->BusinessCenter=='');																			
																				$ImportReport[(string)$property->Identification->PrimaryID]['BusinessCenter']=0;
																				if(!isset($property->Amenities->Community->ChildCare) || (string)$property->Amenities->Community->ChildCare=='');									
																				$ImportReport[(string)$property->Identification->PrimaryID]['ChildCare']=0;		
																				if(!isset($property->Amenities->Community->ClubHouse) || (string)$property->Amenities->Community->ClubHouse=='');								
																				$ImportReport[(string)$property->Identification->PrimaryID]['ClubHouse']=0;	
																				if(!isset($property->Amenities->Community->CoverPark) || (string)$property->Amenities->Community->CoverPark=='');								
																				$ImportReport[(string)$property->Identification->PrimaryID]['CoverPark']=0;		
																				if(!isset($property->Amenities->Community->FitnessCenter) || (string)$property->Amenities->Community->FitnessCenter=='');							
																				$ImportReport[(string)$property->Identification->PrimaryID]['FitnessCenter']=0;		
																				if(!isset($property->Amenities->Community->Gate) || (string)$property->Amenities->Community->Gate=='');											
																				$ImportReport[(string)$property->Identification->PrimaryID]['Gate']=0;		
																				if(!isset($property->Amenities->Community->HighSpeed) || (string)$property->Amenities->Community->HighSpeed=='');								
																				$ImportReport[(string)$property->Identification->PrimaryID]['HighSpeed']=0;		
																				if(!isset($property->Amenities->Community->Laundry) || (string)$property->Amenities->Community->Laundry=='');										
																				$ImportReport[(string)$property->Identification->PrimaryID]['Laundry']=0;	
																				if(!isset($property->Amenities->Community->Pool) || (string)$property->Amenities->Community->Pool=='');											
																				$ImportReport[(string)$property->Identification->PrimaryID]['Pool']=0;	
																				if(!isset($property->Amenities->Community->PlayGround) || (string)$property->Amenities->Community->PlayGround=='');
																				$ImportReport[(string)$property->Identification->PrimaryID]['PlayGround']=0;
																				if(!isset($property->Amenities->Community->ShortTermLease) || (string)$property->Amenities->Community->ShortTermLease=='');						
																				$ImportReport[(string)$property->Identification->PrimaryID]['ShortTermLease']=0;		
																				if(!isset($property->Amenities->Community->Spa) || (string)$property->Amenities->Community->Spa=='');											
																				$ImportReport[(string)$property->Identification->PrimaryID]['Spa']=0;	
																				if(!isset($property->Amenities->Community->StorageSpace) || (string)$property->Amenities->Community->StorageSpace=='');							
																				$ImportReport[(string)$property->Identification->PrimaryID]['StorageSpace']=0;	
																				if(!isset($property->Amenities->Community->Transportation) || (string)$property->Amenities->Community->Transportation=='');							
																				$ImportReport[(string)$property->Identification->PrimaryID]['Transportation']=0;	
																				if(!isset($property->Amenities->Community->Availability24Hours) || (string)$property->Amenities->Community->Availability24Hours=='');					
																				$ImportReport[(string)$property->Identification->PrimaryID]['Availability24Hours']=0;	
																				if(!isset($property->Amenities->Community->BasketballCourt) || (string)$property->Amenities->Community->BasketballCourt=='');						
																				$ImportReport[(string)$property->Identification->PrimaryID]['BasketballCourt']=0;	
																				if(!isset($property->Amenities->Community->ClubDiscount) || (string)$property->Amenities->Community->ClubDiscount=='');							
																				$ImportReport[(string)$property->Identification->PrimaryID]['ClubDiscount']=0;	
																				if(!isset($property->Amenities->Community->Concierge) || (string)$property->Amenities->Community->Concierge=='');	
																				$ImportReport[(string)$property->Identification->PrimaryID]['Concierge']=0;
																				if(!isset($property->Amenities->Community->DoorAttendant) || (string)$property->Amenities->Community->DoorAttendant=='');							
																				$ImportReport[(string)$property->Identification->PrimaryID]['DoorAttendant']=0;	
																				if(!isset($property->Amenities->Community->Elevator) || (string)$property->Amenities->Community->Elevator=='');									
																				$ImportReport[(string)$property->Identification->PrimaryID]['Elevator']=0;	
																				if(!isset($property->Amenities->Community->FreeWeights) || (string)$property->Amenities->Community->FreeWeights=='');							
																				$ImportReport[(string)$property->Identification->PrimaryID]['FreeWeights']=0;	
																				if(!isset($property->Amenities->Community->HouseSitting) || (string)$property->Amenities->Community->HouseSitting=='');							
																				$ImportReport[(string)$property->Identification->PrimaryID]['HouseSitting']=0;	
																				if(!isset($property->Amenities->Community->GroupExcercise) || (string)$property->Amenities->Community->GroupExcercise=='');						
																				$ImportReport[(string)$property->Identification->PrimaryID]['GroupExcercise']=0;	
																				if(!isset($property->Amenities->Community->GuestRoom) || (string)$property->Amenities->Community->GuestRoom=='');								
																				$ImportReport[(string)$property->Identification->PrimaryID]['GuestRoom']=0;	
																				if(!isset($property->Amenities->Community->Housekeeping) || (string)$property->Amenities->Community->Housekeeping=='');							
																				$ImportReport[(string)$property->Identification->PrimaryID]['Housekeeping']=0;	
																				if(!isset($property->Amenities->Community->Library) || (string)$property->Amenities->Community->Library=='');										
																				$ImportReport[(string)$property->Identification->PrimaryID]['Library']=0;	
																				if(!isset($property->Amenities->Community->MealService) || (string)$property->Amenities->Community->MealService=='');								
																				$ImportReport[(string)$property->Identification->PrimaryID]['MealService']=0;	
																				if(!isset($property->Amenities->Community->NightPatrol) || (string)$property->Amenities->Community->NightPatrol=='');								
																				$ImportReport[(string)$property->Identification->PrimaryID]['NightPatrol']=0;	
																				if(!isset($property->Amenities->Community->OnSiteMaintenance) || (string)$property->Amenities->Community->OnSiteMaintenance=='');				
																				$ImportReport[(string)$property->Identification->PrimaryID]['OnSiteMaintenance']=0;	
																				if(!isset($property->Amenities->Community->OnSiteManagement) || (string)$property->Amenities->Community->OnSiteManagement=='');				
																				$ImportReport[(string)$property->Identification->PrimaryID]['OnSiteManagement']=0;	
																				if(!isset($property->Amenities->Community->PacakageReceiving) || (string)$property->Amenities->Community->PacakageReceiving=='');				
																				$ImportReport[(string)$property->Identification->PrimaryID]['PacakageReceiving']=0;	
																				if(!isset($property->Amenities->Community->Racquetball) || (string)$property->Amenities->Community->Racquetball=='');								
																				$ImportReport[(string)$property->Identification->PrimaryID]['Racquetball']=0;	
																				if(!isset($property->Amenities->Community->RecRoom) || (string)$property->Amenities->Community->RecRoom=='');									
																				$ImportReport[(string)$property->Identification->PrimaryID]['RecRoom']=0;	
																				if(!isset($property->Amenities->Community->Sauna) || (string)$property->Amenities->Community->Sauna=='');										
																				$ImportReport[(string)$property->Identification->PrimaryID]['Sauna']=0;	
																				if(!isset($property->Amenities->Community->Sundeck) || (string)$property->Amenities->Community->Sundeck=='');									
																				$ImportReport[(string)$property->Identification->PrimaryID]['Sundeck']=0;	
																				if(!isset($property->Amenities->Community->TennisCourt) || (string)$property->Amenities->Community->TennisCourt=='');								
																				$ImportReport[(string)$property->Identification->PrimaryID]['TennisCourt']=0;	
																				if(!isset($property->Amenities->Community->TVLounge) || (string)$property->Amenities->Community->TVLounge=='');									
																				$ImportReport[(string)$property->Identification->PrimaryID]['TVLounge']=0;	
																				if(!isset($property->Amenities->Community->Vintage) || (string)$property->Amenities->Community->Vintage=='');										
																				$ImportReport[(string)$property->Identification->PrimaryID]['Vintage']=0;	
																				if(!isset($property->Amenities->Community->VolleyballCourt) || (string)$property->Amenities->Community->VolleyballCourt=='');
																				$ImportReport[(string)$property->Identification->PrimaryID]['VolleyballCourt']=0;
																				if(!isset($property->Amenities->Floorplan->AdditionalStorage) || (string)$property->Amenities->Floorplan->AdditionalStorage=='');						
																				$ImportReport[(string)$property->Identification->PrimaryID]['AdditionalStorage']=0;																				
																				if(!isset($property->Amenities->Floorplan->AirConditioner) || (string)$property->Amenities->Floorplan->AirConditioner=='');																			
																				$ImportReport[(string)$property->Identification->PrimaryID]['AirConditioner']=0;
																				if(!isset($property->Amenities->Floorplan->Balcony) || (string)$property->Amenities->Floorplan->Balcony=='');										
																				$ImportReport[(string)$property->Identification->PrimaryID]['Balcony']=0;	
																				if(!isset($property->Amenities->Floorplan->DishWasher) || (string)$property->Amenities->Floorplan->DishWasher=='');								
																				$ImportReport[(string)$property->Identification->PrimaryID]['DishWasher']=0;	
																				if(!isset($property->Amenities->Floorplan->Disposal) || (string)$property->Amenities->Floorplan->Disposal=='');										
																				$ImportReport[(string)$property->Identification->PrimaryID]['Disposal']=0;	
																				if(!isset($property->Amenities->Floorplan->Fireplace) || (string)$property->Amenities->Floorplan->Fireplace=='');										
																				$ImportReport[(string)$property->Identification->PrimaryID]['Fireplace']=0;	
																				if(!isset($property->Amenities->Floorplan->Furnished) || (string)$property->Amenities->Floorplan->Furnished=='');										
																				$ImportReport[(string)$property->Identification->PrimaryID]['Furnished']=0;	
																				if(!isset($property->Amenities->Floorplan->IndividualClimateControl) || (string)$property->Amenities->Floorplan->IndividualClimateControl='');			
																				$ImportReport[(string)$property->Identification->PrimaryID]['IndividualClimateControl']=0;	
																				if(!isset($property->Amenities->Floorplan->LargeClosets) || (string)$property->Amenities->Floorplan->LargeClosets=='');								
																				$ImportReport[(string)$property->Identification->PrimaryID]['LargeClosets']=0;	
																				if(!isset($property->Amenities->Floorplan->Patio) || (string)$property->Amenities->Floorplan->Patio=='');												
																				$ImportReport[(string)$property->Identification->PrimaryID]['Patio']=0;	
																				if(!isset($property->Amenities->Floorplan->Washer) || (string)$property->Amenities->Floorplan->Washer='');										
																				$ImportReport[(string)$property->Identification->PrimaryID]['Washer']=0;	
																				if(!isset($property->Amenities->Floorplan->WheelChair) || (string)$property->Amenities->Floorplan->WheelChair=='');									
																				$ImportReport[(string)$property->Identification->PrimaryID]['WheelChair']=0;	
																				if(!isset($property->Amenities->Floorplan->WD_Hookup) || (string)$property->Amenities->Floorplan->WD_Hookup=='');								
																				$ImportReport[(string)$property->Identification->PrimaryID]['WD_Hookup']=0;	
																				if(!isset($property->Amenities->Floorplan->Alarm) || (string)$property->Amenities->Floorplan->Alarm=='');											
																				$ImportReport[(string)$property->Identification->PrimaryID]['Alarm']=0;	
																				if(!isset($property->Amenities->Floorplan->Carport) || (string)$property->Amenities->Floorplan->Carport=='');											
																				$ImportReport[(string)$property->Identification->PrimaryID]['Carport']=0;	
																				if(!isset($property->Amenities->Floorplan->CeilingFan) || (string)$property->Amenities->Floorplan->CeilingFan=='');									
																				$ImportReport[(string)$property->Identification->PrimaryID]['CeilingFan']=0;	
																				if(!isset($property->Amenities->Floorplan->ControlledAccess) || (string)$property->Amenities->Floorplan->ControlledAccess=='');						
																				$ImportReport[(string)$property->Identification->PrimaryID]['ControlledAccess']=0;	
																				if(!isset($property->Amenities->Floorplan->Courtyard) || (string)$property->Amenities->Floorplan->Courtyard=='');										
																				$ImportReport[(string)$property->Identification->PrimaryID]['Courtyard']=0;	
																				if(!isset($property->Amenities->Floorplan->Dryer) || (string)$property->Amenities->Floorplan->Dryer=='');											
																				$ImportReport[(string)$property->Identification->PrimaryID]['Dryer']=0;	
																				if(!isset($property->Amenities->Floorplan->Handrails) || (string)$property->Amenities->Floorplan->Handrails=='');										
																				$ImportReport[(string)$property->Identification->PrimaryID]['Handrails']=0;	
																				if(!isset($property->Amenities->Floorplan->Microwave) || (string)$property->Amenities->Floorplan->Microwave=='');									
																				$ImportReport[(string)$property->Identification->PrimaryID]['Microwave']=0;	
																				if(!isset($property->Amenities->Floorplan->PrivateBalcony) || (string)$property->Amenities->Floorplan->PrivateBalcony=='');							
																				$ImportReport[(string)$property->Identification->PrimaryID]['PrivateBalcony']=0;	
																				if(!isset($property->Amenities->Floorplan->PrivatePatio) || (string)$property->Amenities->Floorplan->PrivatePatio=='');								
																				$ImportReport[(string)$property->Identification->PrimaryID]['PrivatePatio']=0;	
																				if(!isset($property->Amenities->Floorplan->Refrigerator) || (string)$property->Amenities->Floorplan->Refrigerator=='');									
																				$ImportReport[(string)$property->Identification->PrimaryID]['Refrigerator']=0;	
																				if(!isset($property->Amenities->Floorplan->Skylight) || (string)$property->Amenities->Floorplan->Skylight=='');										
																				$ImportReport[(string)$property->Identification->PrimaryID]['Skylight']=0;	
																				if(!isset($property->Amenities->Floorplan->View) || (string)$property->Amenities->Floorplan->View=='');												
																				$ImportReport[(string)$property->Identification->PrimaryID]['View']=0;	
																				if(!isset($property->Amenities->Floorplan->WindowCoverings) || (string)$property->Amenities->Floorplan->WindowCoverings=='');	
																				$ImportReport[(string)$property->Identification->PrimaryID]['WindowCoverings']=0;
																				*/							
																				
																				//$zip=(string)$property->Identification->Address->Zip;
																				//$import_databases = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."xml_import_zipcodes where zip= $zip" );
																
																
																	//if(isset($import_databases[0]) && $import_databases[0] != '')
																	{
																				
																				$isNew = false;
																				$primary_id =(string)$property->Identification->PrimaryID;
																				$Property_code =(string)$property->ILS_Unit->UnitID;
																				if(empty($Property_code) || $Property_code=='') continue;
																				$args = array(
																										'post_type' => 'property',
																										'meta_query' => array(
																																				array(
																																							'key' => 'Property-code',
																																							'value' => $Property_code,
																																						)
																																			)
																										);
																				$postslist = get_posts( $args );
																									
																				$_email=(string)$property->Identification->Email;
																				if (!isset($postslist[0]))
																						{					
																											$isNew = true;
																											continue;					
																						}
																					else
																					{
																					
																						if(!isset($property->Identification->Address->Zip) || $property->Identification->Address->Zip =='')
																						$ImportReport[$Property_code]['zip']=0;
																						if(!isset($property->Identification->Address->Address1) || $property->Identification->Address->Address1=='')
																						$ImportReport[$Property_code]['Address1']=0;
																						if(!isset($property->ILS_Unit->UnitBedrooms) || (string)$property->ILS_Unit->UnitBedrooms=='')
																						$ImportReport[$Property_code]['UnitBedrooms']=0;
																						if(!isset($property->ILS_Unit->UnitBathrooms) || (string)$property->ILS_Unit->UnitBathrooms=='')
																						$ImportReport[$Property_code]['UnitBathrooms']=0;
																						if(!isset($property->ILS_Unit->MinSquareFeet) || (string)$property->ILS_Unit->MinSquareFeet=='')
																						$ImportReport[$Property_code]['MinSquareFeet']=0;
																						
																						$the_page_id = $postslist[0]->ID;
																						$update_post = array(
																																'ID'           => $the_page_id,
																																'post_title' => (string)$property->Identification->Address->Address1,
																																'post_content' => '',																						  
																															);

																						// Update the post into the database
																						  wp_update_post( $update_post );
																						  $updatedProperties[$the_page_id]=(string)$property->Identification->Address->Address1;
																						  if(update_post_meta($the_page_id,'_email',$_email) )
																						  {
																							$category = rm_find_categories($_email);
																							if(!$category)
																								$ImportReport[$Property_code]['Market']=0;
																							else	
																								$term_taxonomy_ids = wp_set_object_terms( $the_page_id, $category, 'market', false );
																						   }	
																					}
																					//if(!$isNew) continue;
																				//Update Images
																				$imagesOrig =array();
																				$imagesOrigStr = '';
																				if(isset($property->File))
																				{
																					foreach($property->File as $img)
																					{
																						$imagesOrig[] =(string) $img->Src;		
																						break;		
																					}
																					if(count($imagesOrig))
																							$imagesOrigStr = implode(",", $imagesOrig);
																					//update_post_meta($the_page_id,'_rm_images1',$imagesOrigStr);	
																					if(update_post_meta($the_page_id,'_rm_images',$imagesOrigStr) )
																					{	
																							$images=array();
																							$imagesFile=array();
																							if(isset($imagesOrig[0]) )
																								{
																										$img_src= $imagesOrig[0];
																										if($img_src)
																										{
																											$src_img=rm_fetch_remote_file($the_page_id, $img_src);
																											if(isset($src_img['url']))
																											{
																												$images[]= (string)$src_img['url'];
																												$imagesFile[]= (string)$src_img['file'];
																											}
																										}
																								}
																							if(isset ($imagesFile[0] ))
																							{
																								$filename = $imagesFile[0];
																								// The ID of the post this attachment is for.
																								$parent_post_id = $the_page_id;
	
																								// Check the type of tile. We'll use this as the 'post_mime_type'.
																								$filetype = wp_check_filetype( basename( $filename ), null );
	
																								// Get the path to the upload directory.
																								$wp_upload_dir = wp_upload_dir();
	
																								// Prepare an array of post data for the attachment.
																								$attachment = array(
																									'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ), 
																									'post_mime_type' => $filetype['type'],
																									'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
																									'post_content'   => '',
																									'post_status'    => 'inherit'
																								);
	
																								// Insert the attachment.
																								$attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );
	
																								// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
																								require_once( ABSPATH . 'wp-admin/includes/image.php' );
	
																								// Generate the metadata for the attachment, and update the database record.
																								$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
																								wp_update_attachment_metadata( $attach_id, $attach_data );
																								// add featured image to post
																								//add_post_meta($the_page_id, '_thumbnail_id', $attach_id);
																								//delete_post_thumbnail ($the_page_id);
																								set_post_thumbnail( $the_page_id, $attach_id );
																								$imagesStr = implode(",", $images);
																								update_post_meta($the_page_id,'photos',$imagesStr);																							
																							}
																					}
																				}
																				else
																				{
																					$ImportReport[$Property_code]['image']=0;
																				}	
																				//update_post_meta($the_page_id,'Property-code',(string)$property->ILS_Unit->UnitID);
																				$address = (string)$property->Identification->Address->Address1;
																				$city = (string)$property->Identification->Address->City;
																				$state = (string)$property->Identification->Address->State;
																				$lat = $property->Identification->Latitude;
																				$long = $property->Identification->Longitude;
																				geoCoderUpdate($the_page_id, $address, $city, $state, $lat, $long );
																				//file_put_contents('XMLDATAIMPORTTEST.txt',(string)$property->ILS_Unit->UnitBedrooms,FILE_APPEND);
																				update_post_meta($the_page_id,'Location',(string)$property->Identification->Address->Address1);
																				update_post_meta($the_page_id,'primary_id',$primary_id);
																				update_post_meta($the_page_id,'Price',(string)$property->Policy->Pet->Rent);
																				update_post_meta($the_page_id,'Beds',(string)$property->ILS_Unit->UnitBedrooms);
																				update_post_meta($the_page_id,'Baths',(string)$property->ILS_Unit->UnitBathrooms);																			
																				update_post_meta($the_page_id,'Square Footage',(string)$property->ILS_Unit->MinSquareFeet);
																				update_post_meta($the_page_id,'unit status','UnitEconomicStatus='.(string)$property->ILS_Unit->UnitEconomicStatus.'&UnitOccupancyStatus='.(string)$property->ILS_Unit->UnitOccupancyStatus);																			
																				update_post_meta($the_page_id,'City',(string)$property->Identification->Address->City);																			
																				update_post_meta($the_page_id,'State',(string)$property->Identification->Address->State);																			
																				update_post_meta($the_page_id,'Zip',(string)$property->Identification->Address->Zip);
																				update_post_meta($the_page_id,'url',(string)$property->ILS_Unit->ApplyOnlineURL);	
																				update_post_meta($the_page_id,'Deposit',(string)$property->Floorplan->Deposit);																			
																				
																				$general_amenities='';
																				if(is_array($property->Amenities->General))
																							{
																								foreach($property->Amenities->General as $general)
																												{
																														$general_amenities.=$general->AmenityName.', ';
																												}
																												$general_amenities=substr($general_amenities, 0, -2);
																							
																							}
																							else
																								{
																									$general_amenities.=$property->Amenities->General->AmenityName;
																								}		
																				
																				update_post_meta($the_page_id,'general_amenities',$general_amenities);																			
																				update_post_meta($the_page_id,'Title',(string)$property->Identification->Address->Address1);																			
																				update_post_meta($the_page_id,'Latitude',(string)$property->Identification->Latitude);																			
																				update_post_meta($the_page_id,'Longitude',(string)$property->Identification->Longitude);	
																				update_post_meta($the_page_id,'Listing_phone',(string)$property->Identification->Phone);
																				update_post_meta($the_page_id,'Property Code',(string)$property->ILS_Unit->PropertyPrimaryID);	
																				$valuesMeta = array();
																				$valuesMeta['property-code']= $Property_code;
																				$valuesMeta['unit-status']= (string)$property->ILS_Unit->UnitOccupancyStatus;
																				$valuesMeta['property-type']= 'Single Family Home';
																				$valuesMeta['amenities']=$general_amenities;
																				$valuesMeta['community-amenities']='';
																				$valuesMeta['family']='';
																				$valuesMeta['sustainable-living']='';
																				$valuesMeta['fitness']= '';
																				$valuesMeta['address']= (string)$property->Identification->Address->Address1;
																				$valuesMeta['city']= (string)$property->Identification->Address->City  ;
																				$valuesMeta['state']= (string)$property->Identification->Address->State;
																				$valuesMeta['zip-code']= (string)$property->Identification->Address->Zip;
																				$valuesMeta['bedrooms']= (string)$property->ILS_Unit->UnitBedrooms;
																				$valuesMeta['bathrooms']= (string)$property->ILS_Unit->UnitBathrooms;
																				$valuesMeta['square-footage']= (string)$property->ILS_Unit->MinSquareFeet;
																				$valuesMeta['rent']= (string)$property->ILS_Unit->UnitRent;
																				$valuesMeta['url-to-property-detail-page']= (string)$property->Identification->WebSite;
																				
																				update_property_meta_box($the_page_id, $valuesMeta);
																				/*
																				update_post_meta($the_page_id,'Property Code',(string)$property->ILS_Unit->PropertyPrimaryID);																			
																				update_post_meta($the_page_id,'property-type',(string)$property->ILS_Unit->UnitBathrooms);																			
																				update_post_meta($the_page_id,'market',(string)$property->ILS_Unit->UnitBathrooms);																			
																				update_post_meta($the_page_id,'long_description',(string)$property->Information->LongDescription);

																				$attributes=array();
																				$Cat_Allowed='';
																				$Dog_Allowed='';
																				foreach($property->Policy->Pet as $pet)
																						{
																								$attributes=$pet->attributes();
																									
																												if($attributes['type']=='Cat')
																												$Cat_Allowed=$attributes['allowed'];
																										
																												if($attributes['type']=='Dog')
																												$Dog_Allowed=$attributes['allowed'];	
																											
																						}
																				
																				update_post_meta($the_page_id,'Cat_Allowed',$Cat_Allowed);	
																				update_post_meta($the_page_id,'Dog_Allowed',$Dog_Allowed);	
																				update_post_meta($the_page_id,'garage',(string)$property->Amenities->Community->Garage);																			
																				update_post_meta($the_page_id,'floorplanid',(string)$property->ILS_Unit->FloorplanID);	
																				update_post_meta($the_page_id,'propertyprimaryid',(string)$property->ILS_Unit->PropertyPrimaryID);																			
																				update_post_meta($the_page_id,'businesscenter',(string)$property->Amenities->Community->BusinessCenter);																			
																				update_post_meta($the_page_id,'childcare',(string)$property->Amenities->Community->ChildCare);																			
																				update_post_meta($the_page_id,'clubhouse',(string)$property->Amenities->Community->ClubHouse);																			
																				update_post_meta($the_page_id,'coverpark',(string)$property->Amenities->Community->CoverPark);																			
																				update_post_meta($the_page_id,'fitnesscenter',(string)$property->Amenities->Community->FitnessCenter);																			
																				update_post_meta($the_page_id,'gate',(string)$property->Amenities->Community->Gate);																			
																				update_post_meta($the_page_id,'highspeed',(string)$property->Amenities->Community->HighSpeed);																			
																				update_post_meta($the_page_id,'laundry',(string)$property->Amenities->Community->Laundry);																			
																				update_post_meta($the_page_id,'pool',(string)$property->Amenities->Community->Pool);																			
																				update_post_meta($the_page_id,'playground',(string)$property->Amenities->Community->PlayGround);
																				
																				update_post_meta($the_page_id,'shorttermlease',(string)$property->Amenities->Community->ShortTermLease);																			
																				update_post_meta($the_page_id,'spa',(string)$property->Amenities->Community->Spa);																			
																				update_post_meta($the_page_id,'storagespace',(string)$property->Amenities->Community->StorageSpace);																			
																				update_post_meta($the_page_id,'transportation',(string)$property->Amenities->Community->Transportation);																			
																				update_post_meta($the_page_id,'availability24hours',(string)$property->Amenities->Community->Availability24Hours);																			
																				update_post_meta($the_page_id,'basketballcourt',(string)$property->Amenities->Community->BasketballCourt);																			
																				update_post_meta($the_page_id,'clubdiscount',(string)$property->Amenities->Community->ClubDiscount);																			
																				update_post_meta($the_page_id,'concierge',(string)$property->Amenities->Community->Concierge);	
																				update_post_meta($the_page_id,'doorattendent',(string)$property->Amenities->Community->DoorAttendant);																			
																				update_post_meta($the_page_id,'elevator',(string)$property->Amenities->Community->Elevator);																			
																				update_post_meta($the_page_id,'freeweights',(string)$property->Amenities->Community->FreeWeights);																			
																				update_post_meta($the_page_id,'housesitting',(string)$property->Amenities->Community->HouseSitting);																			
																				update_post_meta($the_page_id,'groupexcercise',(string)$property->Amenities->Community->GroupExcercise);																			
																				update_post_meta($the_page_id,'guestroom',(string)$property->Amenities->Community->GuestRoom);																			
																				update_post_meta($the_page_id,'housekeeping',(string)$property->Amenities->Community->Housekeeping);																			
																				update_post_meta($the_page_id,'library',(string)$property->Amenities->Community->Library);																			
																				update_post_meta($the_page_id,'mealservice',(string)$property->Amenities->Community->MealService);																			
																				update_post_meta($the_page_id,'nightpatrol',(string)$property->Amenities->Community->NightPatrol);																			
																				update_post_meta($the_page_id,'onsitemaintenance',(string)$property->Amenities->Community->OnSiteMaintenance);																			
																				update_post_meta($the_page_id,'onsitemanagement',(string)$property->Amenities->Community->OnSiteManagement);																			
																				update_post_meta($the_page_id,'packagereceiving',(string)$property->Amenities->Community->PacakageReceiving);																			
																				update_post_meta($the_page_id,'racquetball',(string)$property->Amenities->Community->Racquetball);																			
																				update_post_meta($the_page_id,'recroom',(string)$property->Amenities->Community->RecRoom);																			
																				update_post_meta($the_page_id,'sauna',(string)$property->Amenities->Community->Sauna);																			
																				update_post_meta($the_page_id,'sundeck',(string)$property->Amenities->Community->Sundeck);																			
																				update_post_meta($the_page_id,'tenniscourt',(string)$property->Amenities->Community->TennisCourt);																			
																				update_post_meta($the_page_id,'tvlounge',(string)$property->Amenities->Community->TVLounge);																			
																				update_post_meta($the_page_id,'vintage',(string)$property->Amenities->Community->Vintage);																			
																				update_post_meta($the_page_id,'volleyballcourt',(string)$property->Amenities->Community->VolleyballCourt);
																				update_post_meta($the_page_id,'addtionalstorage',(string)$property->Amenities->Floorplan->AdditionalStorage);																			
																				update_post_meta($the_page_id,'airconditioner',(string)$property->Amenities->Floorplan->AirConditioner);																			
																				update_post_meta($the_page_id,'balcony',(string)$property->Amenities->Floorplan->Balcony);																			
																				update_post_meta($the_page_id,'dishwasher',(string)$property->Amenities->Floorplan->DishWasher);																			
																				update_post_meta($the_page_id,'disposal',(string)$property->Amenities->Floorplan->Disposal);																			
																				update_post_meta($the_page_id,'fireplace',(string)$property->Amenities->Floorplan->Fireplace);																			
																				update_post_meta($the_page_id,'furnished',(string)$property->Amenities->Floorplan->Furnished);																			
																				update_post_meta($the_page_id,'individualclimatecontrol',(string)$property->Amenities->Floorplan->IndividualClimateControl);																			
																				update_post_meta($the_page_id,'largerclosets',(string)$property->Amenities->Floorplan->LargeClosets);																			
																				update_post_meta($the_page_id,'patio',(string)$property->Amenities->Floorplan->Patio);																			
																				update_post_meta($the_page_id,'washer',(string)$property->Amenities->Floorplan->Washer);																			
																				update_post_meta($the_page_id,'wheelchair',(string)$property->Amenities->Floorplan->WheelChair);																			
																				update_post_meta($the_page_id,'wd_hookup',(string)$property->Amenities->Floorplan->WD_Hookup);																			
																				update_post_meta($the_page_id,'alarm',(string)$property->Amenities->Floorplan->Alarm);																			
																				update_post_meta($the_page_id,'carport',(string)$property->Amenities->Floorplan->Carport);																			
																				update_post_meta($the_page_id,'ceilingfan',(string)$property->Amenities->Floorplan->CeilingFan);																			
																				update_post_meta($the_page_id,'controlledaccess',(string)$property->Amenities->Floorplan->ControlledAccess);																			
																				update_post_meta($the_page_id,'courtyard',(string)$property->Amenities->Floorplan->Courtyard);																			
																				update_post_meta($the_page_id,'dryer',(string)$property->Amenities->Floorplan->Dryer);																			
																				update_post_meta($the_page_id,'handrails',(string)$property->Amenities->Floorplan->Handrails);																			
																				update_post_meta($the_page_id,'microwave',(string)$property->Amenities->Floorplan->Microwave);																			
																				update_post_meta($the_page_id,'privatebalcony',(string)$property->Amenities->Floorplan->PrivateBalcony);																			
																				update_post_meta($the_page_id,'privatepatio',(string)$property->Amenities->Floorplan->PrivatePatio);																			
																				update_post_meta($the_page_id,'refridgerator',(string)$property->Amenities->Floorplan->Refrigerator);																			
																				update_post_meta($the_page_id,'skylight',(string)$property->Amenities->Floorplan->Skylight);																			
																				update_post_meta($the_page_id,'view',(string)$property->Amenities->Floorplan->View);																			
																				update_post_meta($the_page_id,'windowcoverings',(string)$property->Amenities->Floorplan->WindowCoverings);	
																				*/
																				
																	}	
																	//echo "<br/>";echo "<br/>";
													
											
								}
								
								$report_message='<div><b>Errors or missing data from today&#39;s Rent Cafe feed: </b><br/>';
								
								foreach($ImportReport as $primaryId=>$emptyFields)
												{
													
													$report_message.='<br/><b>Errors or missing data with Property code '.$primaryId.':</b>  ';
													foreach($emptyFields as $field=>$value)
																	{
																		$report_message.='      '.$field.'  , ';
																	}
																	$report_message=substr($report_message, 0, -2);
													
												}
								/*				
								$report_message.='<br/><br/><b>New Properties Report : </b>'.count($newProperties);
												
														//$report_message.='<h3>New Properties</h3><br/>';
								foreach($newProperties as $page_id=>$new_property)
													{
														$report_message.='<br/>ID='.$page_id.', Title='.$new_property;
													}
								
								*/
								
								$report_message.='<br/><br/><b>Total properties updated in today\'s Rent Cafe Feed'.':</b>'.count($updatedProperties).'<br/>';
								foreach($updatedProperties as $page_id=>$updated_property)
													{
															$report_message.='<br/>ID='.$page_id.', Title='.$updated_property;
													}
							
								$args = array(
															'posts_per_page'   => -1,
															'offset'           => 0,
															'orderby'          => 'post_date',
															'order'            => 'DESC',
															'post_type'        => 'property',
															'post_status'      => 'publish'
														);
								
								
								$active_properties = get_posts($args);
								$total_active_properties=count($active_properties);
								
								
								
								$report_message.='<br/><br/><b>Total properties updated in today&#39;s Rent Cafe feed:=</b>'.$total_active_properties;
								
								$report_message.=status_and_region_report();
								
								
								
								

						/*			
								$report_message.='<br/><h3>Comparison of property data before and after the process</h3>';
		
								$args = array(
															'posts_per_page'   => 10,
															'offset'           => 0,
															'orderby'          => 'post_date',
															'order'            => 'DESC',
															'post_type'        => 'property',
															'post_status'      => 'publish'
														);
								
								
								$updated_properties = get_posts($args);
								foreach($updated_properties as $updated_property)
													{
														$properties_comparison[$updated_property->ID]['property_data_after_process']['ID']=$updated_property->ID;
														$properties_comparison[$updated_property->ID]['property_data_after_process']['Data']=get_post_meta($updated_property->ID);			
													}
								
								foreach($properties_comparison as $property_id=>$property_data)
													{
															$report_message.='<br/><h3>Property Id='.$property_id.'</h3><br/>';
															$report_message.='<br/> <h3>Before Process</h3><br/>';
															foreach($property_data as $property_data_type=>$propertyData)
																			{
																			
																				if($property_data_type == 'property_data_before_process')
																					foreach($propertyData['Data'] as $key=>$data)
																						{
																							$report_message.='    '.$key.' = '.$data[0].'  ,  ';
																						}
																			
																			}
															$report_message.='<br/><h3>After Process</h3><br/>';				
															foreach($property_data as $property_data_type=>$propertyData)
																			{
																				if($property_data_type == 'property_data_after_process')
																					foreach($propertyData['Data'] as $key=>$data)
																						{
																							$report_message.='    '.$key.' = '.$data[0].'  ,  ';
																						}
																			
																			}
															
													}					
											*/
											
														$report_message.='<br/><br/>';
														
														//echo $report_message;
								
											
													
													$to='danish@yourdesignonline.com,damond.farrar@yourdesignonline.com';
													$subject = 'IH Daily Property Update Status '.date("Y-m-d_H:i:s");
													$message=$report_message;
													notify_to($to,$subject,$message);
											
								
							
						}
	
	
	
	
	
	
	
	
	function remove_non_existing_properties($xml_file)
							{
							
								global $wpdb;
								
								
								/*
								$dir=WP_CONTENT_DIR."/uploads/XML_file";
								ini_set("max_execution_time", 3000);
								if (!file_exists($dir) && !is_dir($dir)) 
									{

											@mkdir($dir);         
									}
							
							if(!is_writable($dir))
								{
									if (!chmod($dir, 777)) 
											{
												echo "Cannot change the mode of file ($dir)";
												exit;
											};
								}
							include 'RemoteImport-functions.php';
							
							$xml_file=WP_CONTENT_DIR."/uploads/XML_file/XMLData-RemovingNonExistingProperties".date("Y-m-d_H:i:s").".xml";
							//$xml_file=WP_CONTENT_DIR."/uploads/XML_file/XMLDATATESTING.xml";
							
							$remote_xml_file_url=get_remote_xml_file_url();
							$xml= file_get_contents($remote_xml_file_url);
							
							if(!$xml)
								{
									die('Cannot download  xml file ');
								}
								
							$fp = fopen($xml_file, 'w');
							if(!$fp)
								{
										echo "Cannot open  file ($xml_file)";
										exit;
								}
							fwrite($fp,$xml);
							fclose($fp);
							
							*/
							
							
							
							$local_xml_file= simplexml_load_file($xml_file);
							if(!$local_xml_file) die('Can not read file from rentcafe');		
								foreach($local_xml_file->Property as $property)
												{
															
														$xml_property_primary_ids[]=(string) $property->ILS_Unit->UnitID;
							
												}
							
									$args = array(
															'posts_per_page'   => -1,
															'offset'           => 0,
															'orderby'          => 'post_date',
															'order'            => 'DESC',
															'post_type'        => 'property',
															'post_status'      => 'publish'
														);
								
							
								$existing_active_properties = get_posts($args);
								$existing_primary_ids=array();
								
								$active_property_post_meta=array();
								
								foreach($existing_active_properties as $active_property)
													{
														$active_property_post_meta[$active_property->ID]=get_post_meta($active_property->ID);
													}
								foreach($active_property_post_meta as $active_property_id=>$post_meta)
													{
															$existing_primary_ids[$active_property_id]=$post_meta['Property-code'][0];
													}
													
								$total_deleted_properties=0;
								$deletedProperties=array();
								foreach($existing_primary_ids as $property_id=>$existing_primary_id)
												{
																				
													if(!in_array($existing_primary_id,$xml_property_primary_ids) || empty($existing_primary_id))
																		{
																			delete_associated_media($property_id);
																			wp_delete_post($property_id);
																			//if(wp_delete_post($property_id))
																			//if(1)
																					{
																						//delete_associated_media($property_id);
																						$deletedProperties[]=$property_id;
																						$total_deleted_properties++;
																					}
																		}
																		
												}
												
												$deleted_properties_report= '<h3>Total deleted properties='.$total_deleted_properties.'</h3><br/>';	
												if($total_deleted_properties !=0)
													{
														$deleted_properties_report.='<br>Deleted Property Ids:    ';
													}
												foreach($deletedProperties as $deletedProperty)
																	{
																		
																		$deleted_properties_report.='    '.$deletedProperty.'  , ';
																	}
																	
																	$deleted_properties_report=substr($deleted_properties_report, 0, -2);
												//notify_to('danish@yourdesignonline.com, rob@yourdesignonline.com','Deleted Properties Report',$deleted_properties_report);
												$subject = 'IH Daily Property Delete Status '.date("Y-m-d H:i:s");
												notify_to('danish@yourdesignonline.com, damond.farrar@yourdesignonline.com',$subject ,$deleted_properties_report);
												
												//echo $deleted_properties_report;
												
							}


	
	function status_and_region_report()
						{
							$args = array(
															'posts_per_page'   => -1,
															'offset'           => 0,
															'orderby'          => 'post_date',
															'order'            => 'DESC',
															'post_type'        => 'property',
															'post_status'      => 'publish'
														);
								
							
								$active_properties = get_posts($args);
								$total_active_properties=count($active_properties);
								$properties_status=array();
								if(is_array($active_properties))
								foreach($active_properties as $active_property)
													{
														$active_property_post_meta[$active_property->ID]=get_post_meta($active_property->ID);
														
													}
													$zip_types=array();
								if(is_array($active_property_post_meta))					
								foreach($active_property_post_meta as $active_property_id=>$post_meta)
													{
															$UnitEconomicStatus=explode('&',$post_meta['unit status'][0]);
															$Status=explode('=',$UnitEconomicStatus[0]);
															$properties_status[$active_property_id]['UnitEconomicStatus']=$Status[1];
															if(!in_array($post_meta['Zip'],$zip_types))
																$zip_types[]=$post_meta['Zip'];
													}
											
									
											
								foreach($zip_types as $zip)
												{
														$total_zip_count[$zip[0]]=0;
														foreach($active_property_post_meta as $active_property_id=>$post_meta)
																			{
																			
																						if($zip[0]==$post_meta['Zip'][0])
																								{
																										$total_zip_count[$zip[0]]++;
																								}
																			}
												
												}
								
								
													
								$status_types=array();
								foreach($properties_status as $status)		
													{
														if(!in_array($status['UnitEconomicStatus'],$status_types))
														$status_types[]=$status['UnitEconomicStatus'];
													}
									$total_status_type=array();				
								foreach($status_types as $status)
												{
														$total_status_type[$status]=0;
																			foreach($active_property_post_meta as $active_property_id=>$post_meta)
																							{
																								$UnitEconomicStatus=explode('&',$post_meta['unit status'][0]);
																								$Status=explode('=',$UnitEconomicStatus[0]);
																								if($status==$Status[1])
																									{
																										$total_status_type[$status]++;
																									}
																							}
												}
																							
											$report='';	
											$report.='<br/><br/><b>Total number of properties updated in WordPress (IH.com)</b>';
											
											foreach($total_status_type as $key=>$count)
																	{
																		if($key)
																			$report.='<br/>Status='.$key.' having Number of Properties='.$count;
																	}

											$report.='<br/><br/><b>Total number of properties updated in WordPress (IH.com)</b>';
											if(is_array($total_zip_count))
											foreach($total_zip_count as $zip=>$count)
																	{
																		$report.='<br/>Zip='.$zip.' having Number of Properties='.$count;
																	}

													return $report;
						}


function update_property_code() 		
{
		/*  Method to update existing posts */
		global $wpdb;
		$dir=WP_CONTENT_DIR."/uploads/XML_file";
		ini_set("max_execution_time", 3000);
		if (!file_exists($dir) && !is_dir($dir)) 
		{
			@mkdir($dir);         
		}

		if(!is_writable($dir))
		{
			if (!chmod($dir, 777)) 
					{
						echo "Cannot change the mode of file ($dir)";
						exit;
					};
		}

		include 'RemoteImport-functions.php';

		$xml_file=WP_CONTENT_DIR."/uploads/XML_file/XMLData-UpdatingPost".date("Y-m-d_H:i:s").".xml";
		//$xml_file=WP_CONTENT_DIR."/uploads/XML_file/XMLDATATESTING.xml";


		$remote_xml_file_url=get_remote_xml_file_url();
		$xml= file_get_contents($remote_xml_file_url);
		$fp = fopen($xml_file, 'w');
		if(!$fp)
		{
				echo "Cannot open  file ($xml_file)";
				exit;
		}
		fwrite($fp,$xml);
		fclose($fp);


		$local_xml_file= simplexml_load_file($xml_file);



		$ImportReport=array();
		$newProperties=array();
		$updatedProperties=array();

		$icount = 0;
		foreach($local_xml_file->Property as $property)
		{
			$icount++;
			//if($icount<900) continue;
			//if($icount>1000) break;
			$isNew = false;
			$primary_id =(string)$property->Identification->PrimaryID;
			$Property_code =(string)$property->ILS_Unit->UnitID;
			$title = trim($property->Identification->Address->Address1);
			$postslist = get_page_by_title($title, OBJECT, 'property');
								
			$_email=(string)$property->Identification->Email;
			if ($postslist->ID)
			{		
				$the_page_id = $postslist->ID;
				echo "<br>=".$the_page_id;
				$imagesOrig =array();
				$imagesOrigStr = '';
				if(isset($property->File))
					foreach($property->File as $img)
									{
										$imagesOrig[] =(string) $img->Src;		
										break;		
									}
				if(count($imagesOrig))
					$imagesOrigStr = implode(",", $imagesOrig);
					//update_post_meta($the_page_id,'_rm_images1',$imagesOrigStr);	
				update_post_meta($the_page_id,'_rm_images',$imagesOrigStr);
				$_email=(string)$property->Identification->Email;
				update_post_meta($the_page_id,'_email',$_email);
				
				update_post_meta($the_page_id,'Property-code',(string)$property->ILS_Unit->UnitID);
				update_post_meta($the_page_id,'Location',(string)$property->Identification->Address->Address1);
				update_post_meta($the_page_id,'Price',(string)$property->Policy->Pet->Rent);
				update_post_meta($the_page_id,'Beds',(string)$property->ILS_Unit->UnitBedrooms);
				update_post_meta($the_page_id,'Baths',(string)$property->ILS_Unit->UnitBathrooms);																			
				update_post_meta($the_page_id,'Square Footage',(string)$property->ILS_Unit->MinSquareFeet);
				update_post_meta($the_page_id,'unit status','UnitEconomicStatus='.(string)$property->ILS_Unit->UnitEconomicStatus.'&UnitOccupancyStatus='.(string)$property->ILS_Unit->UnitOccupancyStatus);																			
				update_post_meta($the_page_id,'City',(string)$property->Identification->Address->City);																			
				update_post_meta($the_page_id,'State',(string)$property->Identification->Address->State);																			
				update_post_meta($the_page_id,'Zip',(string)$property->Identification->Address->Zip);
				update_post_meta($the_page_id,'url',(string)$property->ILS_Unit->ApplyOnlineURL);	
				update_post_meta($the_page_id,'Deposit',(string)$property->Floorplan->Deposit);																			
				
				$general_amenities='';
				if(is_array($property->Amenities->General))
							{
								foreach($property->Amenities->General as $general)
												{
														$general_amenities.=$general->AmenityName.',';
												}
							
							}
							else
								{
									$general_amenities.=$property->Amenities->General->AmenityName;
								}		
				
				update_post_meta($the_page_id,'general_amenities',$general_amenities);																			
				update_post_meta($the_page_id,'Title',(string)$property->Identification->Address->Address1);																			
				update_post_meta($the_page_id,'Latitude',(string)$property->Identification->Latitude);																			
				update_post_meta($the_page_id,'Longitude',(string)$property->Identification->Longitude);	
				update_post_meta($the_page_id,'Listing_phone',(string)$property->Identification->Phone);
				update_post_meta($the_page_id,'Property Code',(string)$property->ILS_Unit->PropertyPrimaryID);	
				//update_post_meta($the_page_id,'Property-code',(string)$property->ILS_Unit->UnitID);
																				$address = (string)$property->Identification->Address->Address1;
																				$city = (string)$property->Identification->Address->City;
																				$state = (string)$property->Identification->Address->State;
																				$lat = $property->Identification->Latitude;
																				$long = $property->Identification->Longitude;
																				geoCoderUpdate($the_page_id, $address, $city, $state, $lat, $long );
																				$valuesMeta = array();
																				$valuesMeta['property-code']= $Property_code;
																				$valuesMeta['unit-status']= (string)$property->ILS_Unit->UnitOccupancyStatus;
																				$valuesMeta['property-type']= 'Single Family Home';
																				$valuesMeta['amenities']=$general_amenities;
																				$valuesMeta['community-amenities']='';
																				$valuesMeta['family']='';
																				$valuesMeta['sustainable-living']='';
																				$valuesMeta['fitness']= '';
																				$valuesMeta['address']= (string)$property->Identification->Address->Address1;
																				$valuesMeta['city']= (string)$property->Identification->Address->City  ;
																				$valuesMeta['state']= (string)$property->Identification->Address->State;
																				$valuesMeta['zip-code']= (string)$property->Identification->Address->Zip;
																				$valuesMeta['bedrooms']= (string)$property->ILS_Unit->UnitBedrooms;
																				$valuesMeta['bathrooms']= (string)$property->ILS_Unit->UnitBathrooms;
																				$valuesMeta['square-footage']= (string)$property->ILS_Unit->MinSquareFeet;
																				$valuesMeta['rent']= (string)$property->ILS_Unit->UnitRent;
																				$valuesMeta['url-to-property-detail-page']= (string)$property->Identification->WebSite;
																				
																				update_property_meta_box($the_page_id, $valuesMeta);
																			
			}
		}	
		die ('asd');
			
}								
																				
?> 