<?php get_header();
/*
Template Name: Property Submit
*/
        
// Check If User Wants To Edit Property
if ( isset( $_GET['edit'] ) && !empty( $_GET['edit'] ) ) {
	$update_property = true;
}
else {
	$update_property = false;
}

$submit_success = false;

if ( is_user_logged_in() ) {

	global $realty_theme_option, $current_user;
	get_currentuserinfo();
	$current_user_role = $current_user->roles[0];	
	// Check User Role -> Allow Agents To Publish
	if ( $current_user_role == "agent" || current_user_can( 'manage_options' ) ) {
		$agent_role = get_role('agent');
		if ( !$current_user->has_cap( 'unfiltered_html' ) ) {
	  	$agent_role = $agent_role->add_cap( 'unfiltered_html' );
	  }
		$allow_to_publish = true;
		$submit_button_text = 'Publish Property';
		$submit_result_text = 'Property has been published.';
		$save_button_text = 'Save Property As Draft';
		$save_result_text = 'Property has been saved as draft.';
	}
	else {
		$submit_button_text = 'Submit Property';
		$allow_to_publish = false;
		$submit_result_text = 'Property has been submitted.';
	}

}

// Check: User Logged-In, Form Submit/Save Clicked, Nonce Valid?
if ( is_user_logged_in() && ( isset( $_POST['publish'] ) || isset( $_POST['save'] ) || isset( $_POST['update'] ) ) ) {
	
	
	if ( wp_verify_nonce( $_POST['nonce_property_submit'], 'property_submit' ) ) {
	
	$found_posts = 0;
	  
  // Check For ID Uniqueness
  $args_property_id = array(
	  'post_type'				=> 'property',
	  'posts_per_page' 	=> 1,
	  'meta_query' 			=> array(
	    array(
	      'key' 	=> 'estate_property_id',
	      'value' => $_POST['property-id'],
	    )
	  )
  );
  
  $query_property_id = new WP_Query( $args_property_id );
  
  if ( $query_property_id->have_posts() ) : while ( $query_property_id->have_posts() ) : $query_property_id->the_post();
   $found_posts = $query_property_id->found_posts;
  endwhile;
  wp_reset_postdata();
  endif;
	  
	// Update Property
	if ( $update_property ) {
	  
	  $property['ID'] = intval( $_POST['property_id'] );
	  $property['post_title'] = sanitize_text_field( $_POST['property-title'] );
	  $property['post_content'] = $_POST['property-description'];
		// Publish Draft
		if ( $allow_to_publish && isset( $_POST['publish'] ) ) {
	  $property['post_status'] = 'publish';
		}
	  $property_id = wp_update_post( $property );

		// Property Update Result Message
	  if ( $property_id > 0 ) {
	  	$submit_success = true;
	  	$submit_result  = '<div class="alert alert-success alert-dismissable">';
	  	if ( get_post_status( $property_id ) == 'publish' ) {
	  		$submit_result .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . __( 'Property update successful.', 'tt' );
	  		$submit_result .= ' <strong><a href="' . get_permalink( $property_id ) . '" style="color:inherit">' . __( 'View Property', 'tt' ) . ' &raquo;</a></strong>';
	  	}
	  	else {
			  $submit_result .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . __( 'Property has been published.', 'tt' );
	  	}
	  	$submit_result .= '</div>';
	  }
	  else {
		  $submit_success = false;
		  $submit_result = '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . __( 'Property update failed. Please try again.', 'tt' ) . '</div>';
	  }
		
  }
  
  // Add New Property In Database Table "wp_posts"
  else {

		// Check User Capabilities (Agents Can Publish)
		if ( $allow_to_publish ) {
			
			// "Submit" clicked -> Publish
			if ( isset( $_POST['publish'] ) ) {	
				$property = array(
			  	'post_author' 		=> get_current_user_id(),
			  	'post_status' 		=> 'publish',
			  	'post_type' 			=> 'property',
			  	'post_title'			=> sanitize_text_field( $_POST['property-title'] ),
			  	'post_content' 		=> $_POST['property-description']
			  );		  
		  }
		  
		  // "Save" clicked -> Publish
			if ( isset( $_POST['save'] ) ) {	
				$property = array(
			  	'post_author' 		=> get_current_user_id(),
			  	'post_status' 		=> 'draft',
			  	'post_type' 			=> 'property',
			  	'post_title'			=> sanitize_text_field( $_POST['property-title'] ),
			  	'post_content' 		=> $_POST['property-description']
			  );		  
		  }
		  
		}
		// Normal User Submits Pending Property
		else {
			$property = array(
		  	'post_author' 		=> get_current_user_id(),
		  	'post_status' 		=> 'pending',
		  	'post_type' 			=> 'property',
		  	'post_title'			=> sanitize_text_field( $_POST['property-title'] ),
		  	'post_content' 		=> $_POST['property-description']
		  );	
		}
	  
	  $property_id = wp_insert_post( $property );

	  // New Property - Result Message
	  if( $property_id > 0 ) {
	  	$submit_success = true;
			$submit_result  = '<div class="alert alert-success alert-dismissable">';
			$submit_result .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
			if ( isset( $_POST['publish'] ) ) {
				$submit_result .= __( $submit_result_text, 'tt' );
			}
			else if ( isset( $_POST['save'] ) ) {
				$submit_result .= __( $save_result_text, 'tt' );
			}			
			// Show PayPal button
			if ( !$allow_to_publish ) {
				if ( $realty_theme_option['paypal-enable'] ) {
					$submit_result .= ' | ';
				}
				$submit_result .= tt_paypal_payment_button( $property_id );
			}
			$submit_result .= '</div>';
		}
		// Submit Failed
		else {
			$submit_success = false;
			$submit_result = '<div class="alert alert-info alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' . __( 'Property submit failed. Please try again.', 'tt' ) . '</div>';
		}
		
		// New Property - Notification Email
		$property_title = $_POST['property-title'];
		$property_edit_url = get_edit_post_link( $property_id, '' );
		
		if ( $realty_theme_option['property-submit-notification-email-recipient'] ) {
			$notification_recipient = $realty_theme_option['property-submit-notification-email-recipient'];
		}
		else {
			$notification_recipient = get_option( 'admin_email' );
		}
		
		$subject = __( 'New Property Submit', 'tt' ) . ' - '. $property_title;
	  $headers = "From: " . get_option( 'blogname' ) . "<$notification_recipient>";
	  $message  = __( 'A new property has been submitted.', 'tt' ) . "\r\n\n";
	  $message .= $property_title . " (" . $property_edit_url . ")\r\n\n";
	  if ( isset( $_POST['message'] ) && !empty( $_POST['message'] ) ) {
	  	$message .= __( 'Message:', 'tt' ) . "\r\n" . $_POST['message'];
	  }
	  	
		wp_mail( $notification_recipient, $subject, $message, $headers );
	  
  }
  
  // Add OR Update Post Meta Data After Submit
  if ( $submit_success ) {
	  
	  if ( isset( $_POST['feature'] ) && !empty( $_POST['feature'] ) ) {
    	$features = $_POST['feature'];
    }
    else {
	    $features = '';
    }
    wp_set_post_terms( $property_id, $features, 'property-features' );
    
    // Check If "OTHER" Location Is Selected
    $location = $_POST['property-location'];
    
	  if ( $location == "other" ) {
	    $location_other = $_POST['property_location_other'];			
			// Add New Taxonomy Term
			wp_insert_term( $location_other, 'property-location' );
			// Retrieve Term ID
			$new_location = get_term_by( 'name', $location_other, 'property-location' );
			$new_location_id = intval ( $new_location->term_id );
			// Assign New Term To Property
	    wp_set_post_terms( $property_id, $new_location_id, 'property-location' );
    }
    else {
	    wp_set_post_terms( $property_id, $location, 'property-location' );
    }
    
    // Check If "OTHER" Status Is Selected
	  $status = $_POST['property-status'];
	  
	  if ( $status == "other" ) {
	    $status_other = $_POST['property_status_other'];			
			// Add New Taxonomy Term
			wp_insert_term( $status_other, 'property-status' );
			// Retrieve Term ID
			$new_status = get_term_by( 'name', $status_other, 'property-status' );
			$new_status_id = intval ( $new_status->term_id );
			// Assign New Term To Property
	    wp_set_post_terms( $property_id, $new_status_id, 'property-status' );
    }
    else {
	    wp_set_post_terms( $property_id, $status, 'property-status' );
    }
    
    // Check If "OTHER" Type Is Selected
	  $type = $_POST['property-type'];
	  
	  if ( $type == "other" ) {
	    $type_other = $_POST['property_type_other'];			
			// Add New Taxonomy Term
			wp_insert_term( $type_other, 'property-type' );
			// Retrieve Term ID
			$new_type = get_term_by( 'name', $type_other, 'property-type' );
			$new_type_id = intval ( $new_type->term_id );
			// Assign New Term To Property
	    wp_set_post_terms( $property_id, $new_type_id, 'property-type' );
    }
    else {
	    wp_set_post_terms( $property_id, $type, 'property-type' );
    }
    
    $status_update = $_POST['property-status-update'];
    if ( isset( $status_update ) && !empty( $status_update ) ) {
			update_post_meta( $property_id, 'estate_property_status_update', $status_update );
    }
    
    $video_provider = $_POST['property-video-provider'];
    if ( isset( $video_provider ) && !empty( $video_provider ) ) {
			update_post_meta( $property_id, 'estate_property_video_provider', $video_provider );
    }
    
    $video_id = $_POST['property-video-id'];
    if ( isset( $video_id ) && !empty( $video_id ) ) {
			update_post_meta( $property_id, 'estate_property_video_id', $video_id );
    }
    
    $availability = $_POST['property-availability'];
    if ( isset( $availability ) && !empty( $availability ) ) {
			update_post_meta( $property_id, 'estate_property_available_from', $availability );
    }
    
    $id = $_POST['property-id'];
    $existing_property_id = get_post_meta( $property_id, 'estate_property_id', true );
    if ( $existing_property_id != $id && $found_posts == 0 && isset( $id ) && $id >= 0 ) {
			update_post_meta( $property_id, 'estate_property_id', $id );
    }
    
    $price = $_POST['property-price'];
    if ( isset( $price ) && !empty( $price ) ) {
			update_post_meta( $property_id, 'estate_property_price', $price );
    }
    
    $price_text = $_POST['property-price-text'];
    if ( isset( $price_text ) && !empty( $price_text ) ) {
			update_post_meta( $property_id, 'estate_property_price_text', $price_text );
    }
    
    $size = $_POST['property-size'];
    if ( isset( $size ) && !empty( $size ) ) {
			update_post_meta( $property_id, 'estate_property_size', $size );
    }
    
    $size_unit = $_POST['property-size-unit'];
    if ( isset( $size_unit ) && !empty( $size_unit ) ) {
			update_post_meta( $property_id, 'estate_property_size_unit', $size_unit );
    }
    
    $rooms = $_POST['property-rooms'];
    if ( isset( $rooms ) && $rooms >= 0 ) {
			update_post_meta( $property_id, 'estate_property_rooms', $rooms );
    }
    
    $bedrooms = $_POST['property-bedrooms'];
    if ( isset( $bedrooms ) && $bedrooms >= 0 ) {
			update_post_meta( $property_id, 'estate_property_bedrooms', $bedrooms );
    }
    
    $bathrooms = $_POST['property-bathrooms'];
    if ( isset( $bathrooms ) && $bathrooms >= 0 ) {
			update_post_meta( $property_id, 'estate_property_bathrooms', $bathrooms );
    }
    
    $garages = $_POST['property-garages'];
    if ( isset( $garages ) && $garages >= 0 ) {
			update_post_meta( $property_id, 'estate_property_garages', $garages );
    }

    $address = $_POST['property-address'];
    if ( isset( $address ) && !empty( $address ) ) {
			update_post_meta( $property_id, 'estate_property_address', $address );
    }
    
    $coordinates = $_POST['property-coordinates'];
    if ( isset( $coordinates ) && !empty( $coordinates ) ) {
			update_post_meta( $property_id, 'estate_property_location', $coordinates );
    }
    
    // ACF
    if ( tt_acf_active() && tt_acf_group_id_property() ) : // Check if ACF plugin is active & for post type "property" field group
    
    	$acf_fields_label = tt_acf_fields_label( tt_acf_group_id_property() );
			$acf_fields_name = tt_acf_fields_name( tt_acf_group_id_property() );
			$acf_fields_type = tt_acf_fields_type( tt_acf_group_id_property() );
			wp_reset_postdata();
			
			$acf_fields_count = count($acf_fields_name);
			$i = 0;
			
			while ( $acf_fields_count > $i ) :
					
				if ( isset( $_POST[ $acf_fields_name[$i] ] ) ) {
					update_post_meta( $property_id, $acf_fields_name[$i], $_POST[ $acf_fields_name[$i] ] );
				}
				else {
					if ( $acf_fields_type[$i] == "checkbox" && !isset( $_POST[ $acf_fields_name[$i] ] ) ) {
						update_post_meta( $property_id, $acf_fields_name[$i], '' );
					}
				}
				
				$i++;
				
			endwhile;
			
			wp_reset_postdata();
    
    endif;
    
    if ( isset( $_POST['property-featured'] ) && !empty( $_POST['property-featured'] ) ) {
    	$property_featured = 1;
    }
    else {
	    $property_featured = 0;
    }
    update_post_meta( $property_id, 'estate_property_featured', $property_featured );
    
    $contact_information = $_POST['contact_information'];
    if ( isset( $contact_information ) && !empty( $contact_information ) ) {
			update_post_meta( $property_id, 'estate_property_contact_information', $contact_information );
    }
    
    //if ( $current_user_role == "agent" || current_user_can( 'manage_options' ) ) {
    if ( current_user_can( 'manage_options' ) ) {
	    $assign_agent = $_POST['assign-agent'];
	    if ( isset( $assign_agent ) ) {
				update_post_meta( $property_id, 'estate_property_custom_agent', $assign_agent );
	    }
    }
    
    $internal_note = $_POST['internal-note'];
    if ( isset( $internal_note ) ) {
			update_post_meta( $property_id, 'estate_internal_note', $internal_note );
    }
    
    // Floor Plan
      
  	if ( isset ( $_POST['floor-plan-title'] ) ) {
	    $floor_plan_title = $_POST['floor-plan-title'];
	    if ( isset( $floor_plan_title ) ) {
				update_post_meta( $property_id, 'estate_floor_plan_title', $floor_plan_title );
	    }
    }
    
    if ( isset ( $_POST['floor-plan-price'] ) ) {
	    $floor_plan_price = $_POST['floor-plan-price'];
	    if ( isset( $floor_plan_price ) ) {
				update_post_meta( $property_id, 'estate_floor_plan_price', $floor_plan_price );
	    }
    }
    
    if ( isset ( $_POST['floor-plan-size'] ) ) {
	    $floor_plan_size = $_POST['floor-plan-size'];
	    if ( isset( $floor_plan_size ) ) {
				update_post_meta( $property_id, 'estate_floor_plan_size', $floor_plan_size );
	    }
    }
    
    if ( isset ( $_POST['floor-plan-rooms'] ) ) {
	    $floor_plan_rooms = $_POST['floor-plan-rooms'];
	    if ( isset( $floor_plan_rooms ) ) {
				update_post_meta( $property_id, 'estate_floor_plan_rooms', $floor_plan_rooms );
	    }
    }
    
    if ( isset ( $_POST['floor-plan-bedrooms'] ) ) {
	    $floor_plan_bedrooms = $_POST['floor-plan-bedrooms'];
	    if ( isset( $floor_plan_bedrooms ) ) {
				update_post_meta( $property_id, 'estate_floor_plan_bedrooms', $floor_plan_bedrooms );
	    }
    }
    
    if ( isset ( $_POST['floor-plan-bathrooms'] ) ) {
	    $floor_plan_bathrooms = $_POST['floor-plan-bathrooms'];
	    if ( isset( $floor_plan_bathrooms ) ) {
				update_post_meta( $property_id, 'estate_floor_plan_bathrooms', $floor_plan_bathrooms );
	    }
    }
    
    if ( isset ( $_POST['floor-plan-description'] ) ) {
	    $floor_plan_description = $_POST['floor-plan-description'];
	    if ( isset( $floor_plan_description ) ) {
				update_post_meta( $property_id, 'estate_floor_plan_description', $floor_plan_description );
	    }
		}
    
    // PROPERTY IMAGES
    // File Type Check
    $allowed_file_types = array( "image/gif", "image/jpeg", "image/jpg", "image/png" );
		$upload_errors = '';
					
    // Featured Image
    if ( !empty( $_FILES['property-featured-image']['name'] ) ) {

			// Min. dimension    
    	$featured_image_dimension = getimagesize( $_FILES['property-featured-image']['tmp_name'] );
			$featured_image_width = $featured_image_dimension[0];
			$featured_image_height = $featured_image_dimension[1];
			
			if ( $featured_image_width < 600 || $featured_image_height < 300 ) {
				$upload_errors .= '<p class="alert alert-danger">' . __( 'Featured image dimension of', 'tt' ) . ' ' . $featured_image_height . 'x' . $featured_image_width . ' ' . __( 'is too small', 'tt' ) . '. ' . __( 'Min. dimension is 600x300.', 'tt' ) . '</p>';
			}
			
    	if ( !in_array( $_FILES['property-featured-image']['type'], $allowed_file_types ) ) {
	    	$upload_errors .= '<p class="alert alert-danger">' . __( 'Invalid file type:', 'tt' ) . ' "' . $_FILES['property-featured-image']['type'] . '". ' . __( 'Supported file types: gif, jpg, jpeg, png.', 'tt' ) . '</p>';
    	}
    	
    	// Max. File Size 5 MB
			if ( $_FILES['property-featured-image']['size'] > 5000000 ) {
				$upload_errors .= '<p class="alert alert-danger">' . __( 'File is too large. Max. upload file size is 5 MB.', 'tt' ) . '</p>';
			}
			
			// No Erros -> Upload Image
			if ( empty( $upload_errors ) ) {
			
				// check to make sure its a successful upload
				if ( $_FILES['property-featured-image']['error'] !== UPLOAD_ERR_OK ) __return_false();
						  
				// These files need to be included as dependencies when on the front end.
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
				require_once( ABSPATH . 'wp-admin/includes/media.php' );
				
				$attachment_id = media_handle_upload( 'property-featured-image', $property_id );				
				
				if ( is_wp_error( $attachment_id ) ) {
					// There was an error uploading the image.
					echo '<p class="alert alert-danger">' . __( 'Upload failed. Please submit again.', 'tt' ) . '</p>';
				} else {
					update_post_meta( $property_id, '_thumbnail_id', $attachment_id ); // Set Image as Feaured
					// The image was uploaded successfully!
				}
			
			}
			
			// Upload Errors    
	    else {
				echo $upload_errors;
			}
    
    } // END If Featured Image
    
    // Gallery Images
    if ( !empty( $_FILES['property-featured-gallery'] ) ) {
			
			$gallery_files = $_FILES['property-featured-gallery'];
						
			foreach ( $gallery_files['name'] as $key => $value ) {
			
				if ( $gallery_files['name'][$key] ) {
					$file = array(
						'name'     => $gallery_files['name'][$key],
						'type'     => $gallery_files['type'][$key],
						'tmp_name' => $gallery_files['tmp_name'][$key],
						'error'    => $gallery_files['error'][$key],
						'size'     => $gallery_files['size'][$key]
					);
					
					if ( !in_array( $file['type'], $allowed_file_types ) ) {
			    	$upload_errors .= '<p class="alert alert-danger">' . __( 'Invalid file type:', 'tt' ) . ' "' . $file['type'] . '". ' . __( 'Supported file types: gif, jpg, jpeg, png.', 'tt' ) . '</p>';
		    	}
		    	
		    	// Max. File Size 5 MB
					if ( $file['size'] > 5000000 ) {
						$upload_errors .= '<p class="alert alert-danger">' . __( 'File is too large. Max. upload file size is 5 MB.', 'tt' ) . '</p>';
					}
					
					// No Erros -> Upload Image
					if ( empty( $upload_errors ) ) {
		 
						$_FILES = array( "property-featured-gallery" => $file );
						
						foreach ( $_FILES as $file => $array ) {
							// check to make sure its a successful upload
						  if ( $_FILES[$file]['error'] !== UPLOAD_ERR_OK ) __return_false();
						 
						  require_once(ABSPATH . "wp-admin" . '/includes/image.php');
						  require_once(ABSPATH . "wp-admin" . '/includes/file.php');
						  require_once(ABSPATH . "wp-admin" . '/includes/media.php');
						 
						  $attach_id = media_handle_upload( $file, $property_id );
						  add_post_meta( $property_id, 'estate_property_images', $attach_id );
						}
					
					}
					
					// Upload Errors    
			    else {
						echo $upload_errors;
					}
					
				}
				
			}
    
    }  // END If Gallery Images
    
    // Floor Plan Images
    if ( !empty( $_FILES['floor-plan-image'] ) ) {
			
			$floor_plan_files = $_FILES['floor-plan-image'];
			
			$attach_id = array();
					
			foreach ( $floor_plan_files['name'] as $key => $value ) {
			
				if ( $floor_plan_files['name'][$key] ) {
					$file = array(
						'name'     => $floor_plan_files['name'][$key],
						'type'     => $floor_plan_files['type'][$key],
						'tmp_name' => $floor_plan_files['tmp_name'][$key],
						'error'    => $floor_plan_files['error'][$key],
						'size'     => $floor_plan_files['size'][$key]
					);
					
					if ( !in_array( $file['type'], $allowed_file_types ) ) {
			    	$upload_errors .= '<p class="alert alert-danger">' . __( 'Invalid file type:', 'tt' ) . ' "' . $file['type'] . '". ' . __( 'Supported file types: gif, jpg, jpeg, png.', 'tt' ) . '</p>';
		    	}
		    	
		    	// Max. File Size 5 MB
					if ( $file['size'] > 5000000 ) {
						$upload_errors .= '<p class="alert alert-danger">' . __( 'File is too large. Max. upload file size is 5 MB.', 'tt' ) . '</p>';
					}
					
					// No Erros -> Upload Image
					if ( empty( $upload_errors ) ) {
		 
						$_FILES = array( "floor-plan-image" => $file );
						
						foreach ( $_FILES as $file => $array ) {
							// check to make sure its a successful upload
						  if ( $_FILES[$file]['error'] !== UPLOAD_ERR_OK ) __return_false();
						 
						  require_once(ABSPATH . "wp-admin" . '/includes/image.php');
						  require_once(ABSPATH . "wp-admin" . '/includes/file.php');
						  require_once(ABSPATH . "wp-admin" . '/includes/media.php');
						 
						  $attach_id[] = media_handle_upload( $file, $property_id );
						
						}
						
						update_post_meta( $property_id, 'estate_floor_plan_image', $attach_id );
					
					}
					
					// Upload Errors    
			    else {
						echo $upload_errors;
					}
					
				}
				
			}
    
    }
    else {
	    update_post_meta( $property_id, 'estate_floor_plan_image', '' );
    }
    // END Floor Plan Images
      
  } // END If Submitted
  
  }

}
?>

</div><!-- .container -->
<?php tt_page_banner();	?>
<div class="container">
	
<div id="main-content" class="content-box">

	<?php	
		
	// Check If User Is Logged-In
	if ( is_user_logged_in() ) {
	
		// Theme Option: Is Property Submit For Subscribers Disabled?
		if ( $current_user_role != "subscriber" || !$realty_theme_option['property-submit-disabled-for-subscriber'] ) {
		
			// Submit Result Message, If No Errors
			if ( $submit_success && empty( $upload_errors ) ) { 
				echo $submit_result; 
			}
			
			$is_assigned_agent = false;
			
			if ( $update_property ) {
				$property_id = intval( $_GET['edit'] );
				// Check if user has beeen seleted as "Assigned Agent" in Property Settings
				$assigned_agent = get_post_meta( $property_id, 'estate_property_custom_agent', true );
				if ( get_current_user_id() == $assigned_agent ) {
					$is_assigned_agent = true;
				}
			}
			else {
				$property_id = 0;
			}
			$property = get_post( $property_id );
			
			// 1. Check If We Are Updating ( And If So, Check If Property Belongs To Logged-In User OR Assigned Agent ) | 2. Are We Adding A New Property? | 3. Admin Role?
			if ( ( $update_property && ( get_current_user_id() == $property->post_author ) || $is_assigned_agent ) || !$update_property || current_user_can( 'manage_options' ) ) {
			?>
			
			<?php if ( $realty_theme_option['paypal-enable'] && !$realty_theme_option['paypal-alerts-hide'] && ( $current_user_role == "subscriber" && ( get_post_status( $property_id ) != 'publish' || !$property_id ) ) ) { ?>
			<p class="alert alert-info alert-dismissable property-payment-note">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<?php 
				echo __( 'Publishing fee', 'tt' ) . ': ' . $realty_theme_option['paypal-currency-code'] . ' ' . $realty_theme_option['paypal-amount'];
				if ( doubleval($realty_theme_option['paypal-featured-amount']) > 0 ) {
					echo ' | ' . __( '"Featured" upgrade', 'tt' ) . ': ' . $realty_theme_option['paypal-currency-code'] . ' ' . $realty_theme_option['paypal-featured-amount'];
				}
				?>
			</p>
			<p class="alert alert-info alert-dismissable property-payment-note-2">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<?php
				if ( $realty_theme_option['paypal-auto-publish'] ) {
					_e( 'Property will be published automatically after payment completion.', 'tt' );
				}
				else {
					_e( 'Property will be published manually after payment completion.', 'tt' );
				}
				?>
			</p>
			<?php } ?>
		
			<form id="property-submit" class="primary-tooltips" enctype="multipart/form-data" method="post">
			
				<?php if ( $update_property ) { ?>
				<div class="meta-data">
					<?php
					$property_data = get_post( $property_id, 'ARRAY_A' );
					echo __( 'Last edited:', 'tt' ) . ' ' . substr( $property_data['post_modified'], 0, 10 );
					if ( $property_data['post_status'] == "publish" ) {
					echo ' &middot; ' . __( 'Published:', 'tt' ) . ' ' . substr( $property_data['post_date'], 0, 10 );
					}
					echo ' &middot; ' . __( 'Post ID:', 'tt' ) . ' ' . substr( $property_data['ID'], 0, 10 );
					?>
				</div>
				<?php } ?>
			
				<div class="row">
					
					<div class="col-sm-6">
					
						<div class="form-group">
							<label for="property-title"><?php _e( 'Title', 'tt' ); ?></label>
							<input type="text" name="property-title" id="property-title" class="form-control required" value="<?php echo $property->post_title; ?>" title="<?php _e( 'Please enter a property title.', 'tt' ); ?>"  />
						</div>
						
						<div class="form-group">
							<label for="property-description"><?php _e( 'Description', 'tt' ); ?></label>
							<textarea name="property-description" id="property-description" class="form-control required" title="<?php _e( 'Please enter a property description.', 'tt' ); ?>" rows="8"><?php echo $property->post_content; ?></textarea>
						</div>
						
						<div class="form-group">
							<label for="property-id"><?php _e( 'Custom Property ID', 'tt' ); ?>  <i class="fa fa-info-circle" data-toggle="tooltip" title="<?php _e( 'Default: post ID', 'tt' ); ?>"></i></label>
							<input type="text" name="property-id" id="property-id" class="form-control" value="<?php echo get_post_meta( $property_id, 'estate_property_id', true ); ?>" title="<?php _e( 'Please enter a property ID.', 'tt' ); ?>" />
							<?php if ( isset( $found_posts ) && $found_posts > 0 && $existing_property_id != $_POST['property-id'] ) { echo '<label id="property-id-error" class="error" for="property-description">' . $_POST['property-id'] . ' ' .__( 'has already been taken. Please try another ID.', 'tt' ) . '</label>'; } ?>
						</div>
		
						<div class="row">
						
							<div class="col-sm-6">
							
								<div class="form-group">
									<label for="property-location"><?php _e( 'Location', 'tt' ); ?></label>
									<select name="property-location" id="property-location" class="form-control required" title="<?php _e( 'Please select the property location.', 'tt' ); ?>" data-placeholder="<?php _e( 'Choose a location', 'tt' ); ?>">
										
										<option value=""></option>
								    <?php 
								    $locations = get_terms('property-location', array( 'orderby' => 'slug', 'parent' => 0, 'hide_empty' => false) ); 
								    if ( isset( $_GET['estate_property_location'] ) ) {
											$get_location = $_GET['estate_property_location'];
										}
										else {
											$get_location = '';
										}
										$property_locations = get_the_terms( $property_id , 'property-location' );
										if ( !empty( $property_locations ) ) {
											foreach ( $property_locations as $property_location ) {
												$get_location = $property_location->term_id;
												break;
											}
										}
										?>
								    <?php foreach ( $locations as $key => $location ) : ?>
						        <option value="<?php echo $location->term_id; ?>" <?php selected( $location->term_id, $get_location ); ?>>
					            <?php 
					            echo $location->name;
					            $location2 = get_terms( 'property-location', array( 'orderby' => 'slug', 'parent' => $location->term_id ) );
					            if( $location2 ) : 
					            ?>
					            <optgroup>
					              <?php foreach( $location2 as $key => $location2 ) : ?>
					                  <option value="<?php echo $location2->term_id; ?>" class="level2" <?php selected( $location2->term_id, $get_location ); ?>>
					                  	<?php 
					                  	echo $location2->name;
					                  	$location3 = get_terms( 'property-location', array( 'orderby' => 'slug', 'parent' => $location2->term_id ) );
					                  	if( $location3 ) : ?>
					                  	<optgroup>
					                  		<?php foreach( $location3 as $key => $location3 ) : ?>
					                    		<option value="<?php echo $location3->term_id; ?>" class="level3" <?php selected( $location3->term_id, $get_location ); ?>>
					                    		<?php 
					                    		echo $location3->name;
						                    	$location4 = get_terms( 'property-location', array( 'orderby' => 'slug', 'parent' => $location3->term_id ) );
						                    	if( $location4 ) :
					                    		?>
					                    		<optgroup>
					                    			<?php foreach( $location4 as $key => $location4 ) : ?>
					                    			<option value="<?php echo $location4->term_id; ?>" class="level4" <?php selected( $location4->term_id, $get_location ); ?>>
																		<?php echo $location4->name; ?>
					                    			</option>
					                    			<?php endforeach; ?>
					                    		</optgroup>
					                    		<?php endif; ?>
					                    		</option>
					                  		<?php endforeach; ?>
					                  	</optgroup>
					                  	<?php endif; ?>
					                  </option>
					              <?php endforeach; ?>
					            </optgroup>
					            <?php endif; ?>
						        </option>
						        <?php endforeach; ?>
										
										<option value="other"><?php _e( 'Other', 'tt' ); ?></option>
									</select>
									<input type="text" name="property_location_other" id="property-location-other" class="form-control other" placeholder="<?php _e( 'Enter New Property Location', 'tt' ); ?>" />
								</div>
							
								<div class="form-group">
									<label for="property-status"><?php _e( 'Status', 'tt' ); ?></label>
									<select name="property-status" id="property-status" class="form-control required" title="<?php _e( 'Please select the property status.', 'tt' ); ?>" data-placeholder="<?php _e( 'Choose a status', 'tt' ); ?>">
										<option value=""></option>
										<?php
										// Current Propertys' Status
										$property_statuss = get_the_terms( $property_id , 'property-status' );
										
										if ( !empty( $property_statuss ) ) {
											foreach ( $property_statuss as $property_status ) {
												$status_id = $property_status->term_id;
												break;
											}
										}
										
										// Get All Status'
										$property_all_statuss = get_terms( 'property-status', array( 'hide_empty' => false ) );
										$all_status_id = array();
										
										foreach ( $property_all_statuss as $property_all_status ) {
											$all_status_id[] = $property_all_status->term_id; // Collect For Other Location Check
											$property_status_parent = $property_status->parent;
											if ( $property_status_parent ) {
												$property_status_parent_term = get_term_by( 'id', $property_status_parent, 'property-status' );
												$property_status_parent_text = $property_status_parent_term->name . ' - ';
											}
											else {
												$property_status_parent_text = false;
											}
											echo '<option value="' . $property_all_status->term_id . '" ' . selected( $property_all_status->term_id, $status_id ) . '>' . $property_status_parent_text . $property_all_status->name . '</option>';
										}
										// Check For Other Status
										$property_new_status = wp_get_post_terms( $property_id, 'property-status' );
										if ( !in_array( $property_new_status[0]->term_id, $all_statuss_id ) ) {
											echo '<option value="' . $property_new_status[0]->term_id . '" ' . selected( $property_new_status[0]->term_id, $status_id ) . '>' . $property_new_status[0]->name . '</option>';
										}
										?>
										<option value="other"><?php _e( 'Other', 'tt' ); ?></option>
									</select>
									<input type="text" name="property_status_other" id="property-status-other" class="form-control other" placeholder="<?php _e( 'Enter New Property Status', 'tt' ); ?>" />
								</div>
								
								<div class="form-group">
									<?php 
									$video_provider = get_post_meta( $property_id, 'estate_property_video_provider', true ); 
									?>
									<label for="property-video-provider"><?php _e( 'Video Provider', 'tt' ); ?></label>
									<select name="property-video-provider" id="property-video-provider" class="form-control" data-placeholder="<?php _e( 'Optional', 'tt' ); ?>">
										<option value="none">-</option>
										<option value="youtube" <?php selected( get_post_meta( $property_id, 'estate_property_video_provider', true ), 'youtube' ) ?>><?php _e( 'YouTube', 'tt' ); ?></option>
										<option value="vimeo" <?php selected( get_post_meta( $property_id, 'estate_property_video_provider', true ), 'vimeo' )?>><?php _e( 'Vimeo', 'tt' ); ?></option>
									</select>
								</div>
								
								<div class="form-group">
									<?php
									$currency = $realty_theme_option['currency-sign'];
									?>
									<label for="property-price"><?php echo __( 'Price', 'tt' ) . ' ' . __( 'in', 'tt' ) . ' ' . $currency; ?> <i class="fa fa-info-circle" data-toggle="tooltip" title="<?php _e( 'Enter &quot;-1&quot; for &quot;Price Upon Request&quot;. Leave empty to show no price at all.', 'tt' ); ?>"></i></label>
									<input type="number" name="property-price" id="property-price" class="form-control" value="<?php echo get_post_meta( $property_id, 'estate_property_price', true ); ?>" title="<?php _e( 'Please enter a property price.', 'tt' ); ?>" min="-1" />
								</div>
								
								<div class="form-group">
									<label for="property-size"><?php _e( 'Size', 'tt' ); ?></label>
									<input type="number" name="property-size" id="property-size" class="form-control required" value="<?php echo get_post_meta( $property_id, 'estate_property_size', true ); ?>" title="<?php _e( 'Please enter a property size.', 'tt' ); ?>" />
								</div>
								
								<div class="form-group">
									<label for="property-rooms"><?php _e( 'Rooms', 'tt' ); ?></label>
									<input type="number" name="property-rooms" id="property-rooms" class="form-control required" value="<?php echo get_post_meta( $property_id, 'estate_property_rooms', true ); ?>" title="<?php _e( 'Please enter a number of rooms.', 'tt' ); ?>" min="0" step="0.5" />
								</div>
								
								<div class="form-group">
									<label for="property-bathrooms"><?php _e( 'Bathrooms', 'tt' ); ?></label>
									<input type="number" name="property-bathrooms" id="property-bathrooms" class="form-control required" value="<?php echo get_post_meta( $property_id, 'estate_property_bathrooms', true ); ?>" title="<?php _e( 'Please enter a number of bathrooms.', 'tt' ); ?>" min="0" step="0.5" />
								</div>
														
							</div>
							
							<div class="col-sm-6">
							
								<div class="form-group">
									<label for="property-type"><?php _e( 'Type', 'tt' ); ?></label>
									<select name="property-type" id="property-type" class="form-control required" title="<?php _e( 'Please select the property type.', 'tt' ); ?>" data-placeholder="<?php _e( 'Choose a type', 'tt' ); ?>">
										<option value=""></option>
										<?php
										// Current Propertys' Type
										$property_types = get_the_terms( $property_id , 'property-type' );
										
										if ( !empty( $property_types ) ) {
											foreach ( $property_types as $property_type ) {
												$type_id = $property_type->term_id;
												break;
											}
										}
										
										// Get All Types
										$property_all_types = get_terms( 'property-type', array( 'hide_empty' => false ) );
										$all_types_id = array();
										
										foreach ( $property_all_types as $property_all_type ) {
											$all_types_id[] = $property_all_type->term_id; // Collect For Other Type Check
											$property_type_parent = $property_type->parent;
											if ( $property_type_parent ) {
												$property_type_parent_term = get_term_by( 'id', $property_type_parent, 'property-type' );
												$property_type_parent_text = $property_type_parent_term->name . ' - ';
											}
											else {
												$property_type_parent_text = false;
											}
											echo '<option value="' . $property_all_type->term_id . '" ' . selected( $property_all_type->term_id, $type_id ) . '>' . $property_type_parent_text . $property_all_type->name . '</option>';
										}
										// Check For Other Type
										$property_new_type = wp_get_post_terms( $property_id, 'property-type' );
										if ( !in_array( $property_new_type[0]->term_id, $all_types_id ) ) {
											echo '<option value="' . $property_new_type[0]->term_id . '" ' . selected( $property_new_type[0]->term_id, $type_id ) . '>' . $property_new_type[0]->name . '</option>';
										}
										?>
										<option value="other"><?php _e( 'Other', 'tt' ); ?></option>
									</select>
									<input type="text" name="property_type_other" id="property-type-other" class="form-control other" placeholder="<?php _e( 'Enter New Property Type', 'tt' ); ?>" />
								</div>			
								
								<div class="form-group">
									<label for="property-status-update"><?php _e( 'Status Update', 'tt' ); ?></label>
									<input type="text" name="property-status-update" id="property-status-update" class="form-control" value="<?php echo get_post_meta( $property_id, 'estate_property_status_update', true ); ?>" placeholder="<?php _e( 'e.g. &quot;Sold&quot;', 'tt' ); ?>" />
								</div>
								
								<div class="form-group">
									<label for="property-video-id"><?php _e( 'Video ID', 'tt' ); ?></label>
									<input type="text" name="property-video-id" id="property-video-id" class="form-control" value="<?php echo get_post_meta( $property_id, 'estate_property_video_id', true ); ?>" />
									<?php if ( isset( $found_posts ) && $found_posts > 0 && $existing_property_id != $_POST['property-id'] ) { echo '<label id="property-id-error" class="error" for="property-description">' . $_POST['property-id'] . ' ' .__( 'has already been taken. Please try another ID.', 'tt' ) . '</label>'; } ?>
								</div>
								
								<div class="form-group">
									<label for="property-price-text"><?php _e( 'Price Suffix', 'tt' ); ?></label>
									<select name="property-price-text" id="property-price-text" class="form-control" data-placeholder="<?php _e( 'Optional', 'tt' ); ?>">
										<option value="">-</option>
										<?php
										$price_suffixes = $realty_theme_option['property-submit-price-suffix'];
										if ( !empty( $price_suffixes ) ) {
											foreach ( $price_suffixes as $price_suffix ) {
												echo '<option value="' . $price_suffix . '" ' . selected( get_post_meta( $property_id, 'estate_property_price_text', true ), $price_suffix ) . '>' . $price_suffix . '</option>';
											}
										}
										?>
									</select>
								</div>
								
								<div class="form-group">
									<label for="property-size-unit"><?php _e( 'Size Unit', 'tt' ); ?></label>
									<select name="property-size-unit" id="property-size-unit" class="form-control">
										<?php
										$size_units = $realty_theme_option['property-submit-size-unit'];
										if ( !empty( $size_units ) ) {
											foreach ( $size_units as $size_unit ) {
												echo '<option value="' . $size_unit . '" ' . selected( get_post_meta( $property_id, 'estate_property_size_unit', true ), $size_unit ) . '>' . $size_unit . '</option>';
											}
										}
										?>
									</select>
								</div>
								
								<div class="form-group">
									<label for="property-bedrooms"><?php _e( 'Bedrooms', 'tt' ); ?></label>
									<input type="number" name="property-bedrooms" id="property-bedrooms" class="form-control required" value="<?php echo get_post_meta( $property_id, 'estate_property_bedrooms', true ); ?>" title="<?php _e( 'Please enter a number of bedrooms.', 'tt' ); ?>" min="0" step="0.5" />
								</div>
								
								<div class="form-group">
									<label for="property-garages"><?php _e( 'Garages', 'tt' ); ?></label>
									<input type="number" name="property-garages" id="property-garages" class="form-control required" value="<?php echo get_post_meta( $property_id, 'estate_property_garages', true ); ?>" title="<?php _e( 'Please enter a number of garages.', 'tt' ); ?>" min="0" step="0.5" />
								</div>
								
								<div class="form-group">
									<label for="property-availability"><?php _e( 'Available From', 'tt' ); ?></label>
									<div class="input-group">
										<input type="number" name="property-availability" id="property-availability" class="form-control datepicker" value="<?php echo get_post_meta( $property_id, 'estate_property_available_from', true ); ?>" placeholder="<?php _e( 'Available From:', 'tt' ); ?>" /><span class="input-group-addon"><i class="fa fa-calendar-o"></i></span>
									</div>
								</div>
								
							</div>
						
						</div><!-- .row -->
						
						<div id="property-features" class="form-group">
							<label for="property-features"><?php _e( 'Features', 'tt' ); ?></label>
							<ul name="property-features" class="list-unstyled">
								<?php
								// Current Propertys' Features
								$property_features = get_the_terms( $property_id , 'property-features' );
								
								$feature_id = array();
								
								if ( !empty( $property_features ) ) {
									foreach ( $property_features as $property_feature ) {
										$feature_id[] = $property_feature->term_id;
									}
								}
								
								// Get All Features
								$property_all_features = get_terms( 'property-features', array( 'hide_empty' => false ) );
								
								if ( !empty( $property_all_features ) ) {							
									$property_feature_count = 1;
									
									// List All Property Features
									foreach ( $property_all_features as $property_all_feature ) {
										echo '<li>';
										if ( $update_property && in_array( $property_all_feature->term_id, $feature_id ) ) {
											echo '<input type="checkbox" name="feature[]" id="feature-' . $property_feature_count . '" value="' . $property_all_feature->term_id . '" checked />';
										}
										else {
											echo '<input type="checkbox" name="feature[]" id="feature-' . $property_feature_count . '" value="' . $property_all_feature->term_id . '" />';
										}
										echo '<label for="feature-' . $property_feature_count . '">' . $property_all_feature->name . '</label>';
										echo '</li>';
										$property_feature_count++;
									}
								}
								?>
							</ul>
						</div>
						
					</div>	
					
					<div class="col-sm-6">
					
					<div class="form-group">
						<label for="property-address"><?php _e( 'Address', 'tt' ); ?></label>
						<input type="text" name="property-address" id="property-address" class="form-control required" value="<?php echo get_post_meta( $property_id, 'estate_property_address', true ); ?>" title="<?php _e( 'Please enter a property address.', 'tt' ); ?>" />
					</div>
					
					<div class="form-group">
						<label for="property-coordinates"><?php _e( 'Once Address Entered Above, Drag & Drop Map Marker To Exact Location.', 'tt' ); ?></label>
						<input type="hidden" name="property-coordinates" id="property-coordinates" value="" />
					<?php
					//if ( $address || $google_maps ) {
						get_template_part( 'lib/inc/template/google-map-single-property' ); 
					//}
					?>
					</div>
					
					<?php if ( $current_user_role != "subscriber" || ( $current_user_role == "subscriber" && doubleval($realty_theme_option['paypal-featured-amount']) > 0 ) ) { ?>
					<div class="form-group">
						<label for="property-featured">
						<?php 
							echo __( 'Set property "Featured"', 'tt' );
							if ( $current_user_role == "subscriber" && ( get_post_status( $property_id ) != 'publish' || !$property_id ) ) {
								echo ' ' . __( 'for an additional', 'tt' ) . ' ' . $realty_theme_option['paypal-currency-code'] . ' ' . $realty_theme_option['paypal-featured-amount'];
							}
							?>
						</label><br />
						<input type="checkbox" name="property-featured" id="property-featured" <?php checked( get_post_meta( $property_id, 'estate_property_featured', true ) ); ?> />
					</div>
					<?php } ?>
					
					<div class="form-group">
						<label for="property-featured-image"><?php _e( 'Featured Image', 'tt' ); ?> <i class="fa fa-info-circle" data-toggle="tooltip" title="<?php _e( 'Min. dimension 600x300px. Max. file size 5MB.', 'tt' ); ?>"></i></label>
						<div class="clearfix"></div>
						<div id="preview-featured-image">
						<?php
						if( has_post_thumbnail( $property_id ) ) {
						 echo get_the_post_thumbnail( $property_id, 'medium' );
						}
						else {
							echo '<img src ="//placehold.it/250x150/eee/ccc/&text=' . __( 'No Image Selected', 'tt' ) . '" />';
						}
						?>
						</div>
						<input type="file" name="property-featured-image" id="property-featured-image" class="form-control" value="<?php _e( 'Select Files..', 'tt' ); ?>" title="<?php _e( 'Please select a featured image.', 'tt' ); ?>" />
					</div>
					
					<div class="form-group">
						<label for="property-featured-gallery"><?php _e( 'Gallery Images', 'tt' ); ?></label>
						<div class="clearfix"></div>
						<?php
						$property_gallery_images = get_post_meta( $property_id, 'estate_property_images', false );
						if ( $property_gallery_images ) {
							echo '<ul class="gallery-images">';
							foreach ( $property_gallery_images as $gallery_image ) {
								echo '<li>';
								echo wp_get_attachment_image( $gallery_image, array( 120, 120 ), false, array( 'class' => 'gallery-image' ) );
								echo '<i class="fa fa-close delete-uploaded-image" data-property-id="' . $property_id . '" data-image-id="' . $gallery_image . '"></i>';
								echo '</li>';
							}
							echo '</ul>';
						}
						?>
						<p><small><?php _e( 'Hold down "Ctrl" or "Cmd" to select multiple images.', 'tt' ); ?></small></p>
						<input type="file" name="property-featured-gallery[]" id="property-featured-gallery" class="form-control" value="<?php _e( 'Select Files..', 'tt' ); ?>" multiple />
					</div>
					
					<div id="contact-information" class="form-group">
						<label for="contact_information"><?php _e( 'Contact Information', 'tt' ); ?></label><br />
						<?php if ( $update_property ) { ?>
						<input type="radio" name="contact_information" id="contact-information-all" value="all" <?php checked( 'all', get_post_meta( $property_id, 'estate_property_contact_information', true ) ); ?> />
						<?php } else { ?>
						<input type="radio" name="contact_information" id="contact-information-all" value="all" checked />
						<?php } ?>
						<label for="contact-information-all" class="default-label"><?php _e( 'Show Profile Information & Contact Form.', 'tt' ); ?></label><br />
						<input type="radio" name="contact_information" id="contact-information-form" value="form" <?php checked( 'form', get_post_meta( $property_id, 'estate_property_contact_information', true ) ); ?> />
						<label for="contact-information-form" class="default-label"><?php _e( 'Show Contact Form Only.', 'tt' ); ?></label><br />
						<input type="radio" name="contact_information" id="contact-information-none" value="none" <?php checked( 'none', get_post_meta( $property_id, 'estate_property_contact_information', true ) ); ?> />
						<label for="contact-information-none" class="default-label"><?php _e( 'None.', 'tt' ); ?></label>
					</div>
					
					<?php if ( current_user_can( 'manage_options' ) ) { ?>
					<div class="form-group">
						<label for="assign-agent"><?php _e( 'Assign Property To Agent', 'tt' ); ?></label>
						<select name="assign-agent" id="assign-agent">
							<option value="">-</option>
							<?php
							$agents = array( '' => __( 'None', 'tt' ) );
							// Get all users with role "agent"
							$all_agents = get_users( array( 'role' => 'agent', 'fields' => 'ID' ) );
							foreach( $all_agents as $agent ) { 
								echo '<option value="' . $agent . '"' . selected( get_post_meta( $property_id, 'estate_property_custom_agent', true ), $agent ) . '>' . get_user_meta( $agent, 'first_name', true ) . ' ' . get_user_meta( $agent, 'last_name', true ) . '</option>';
							}
							?>
						</select>
					</div>
					<?php } ?>
					
					<div class="form-group">
						<label for="internal-note"><?php _e( 'Internal Note', 'tt' ); ?></label>
						<textarea name="internal-note" id="internal-note" class="form-control" rows="3"><?php echo get_post_meta( $property_id, 'estate_internal_note', true ); ?></textarea>
					</div>
					
					<?php if ( !$update_property && $current_user_role == "subscriber" ) { ?>
					<div class="form-group">
						<label for="message"><?php _e( 'Message To Reviewer', 'tt' ); ?></label>
						<textarea name="message" id="message" class="form-control" rows="5"></textarea>
					</div>
					<?php } ?>
					
				</div><!-- .row -->
				
				<div class="clearfix"></div>
				
				<?php if ( tt_acf_active() && tt_acf_group_id_property() ) { // Check if ACF plugin is active & for post type "property" field group ?>
				
				<div id="custom-fields" class="form-group">
					<?php if ( $realty_theme_option['property-title-additional-details'] ) { echo '<h3 class="section-title"><span>' . $realty_theme_option['property-title-additional-details'] . '</span></h3>'; } ?>
					
					<ul class="list-unstyled row">
					<?php
					$acf_field_label = tt_acf_fields_label( tt_acf_group_id_property() );
					$acf_field_name = tt_acf_fields_name( tt_acf_group_id_property() );
					$acf_field_type = tt_acf_fields_type( tt_acf_group_id_property() );
					$acf_field_required = tt_acf_fields_required( tt_acf_group_id_property() );
					
					$acf_fields_count = count($acf_field_name);

					$i = 0;
					
					while ( $acf_fields_count > $i) :
					
						echo '<li class="form-group col-sm-6">';
						
						$custom_field_meta_value = get_post_meta( $property_id, $acf_field_name[$i], true );
						
						echo '<label for="property-custom-field_' . $acf_field_name[$i] . '">' . $acf_field_label[$i] . '</label>';
						
						if ( $acf_field_required[$i] == true ) { 
							$required = " required"; 
						}
						else {
							$required = "";
						}
							
						switch ( $acf_field_type[$i] ) {
							
							case ( 'text' ) : case ( 'number' ) : case ( 'email' ) :
							echo '<input type="' . $acf_field_type[$i] . '" name="'  . $acf_field_name[$i] . '" id="property-custom-field_' . $acf_field_name[$i] . '" value="' . $custom_field_meta_value . '" class="form-control' . $required . '" />';
							break;
							
							case ( 'textarea' ) : 
							echo '<textarea " name="'  . $acf_field_name[$i] . '" id="property-custom-field_' . $acf_field_name[$i] . '" class="form-control' . $required . '" rows="3" />' . $custom_field_meta_value . '</textarea>';
							break;
							
							case ( 'date_picker' ) : 
							echo '<input type="number" name="'  . $acf_field_name[$i] . '" id="property-custom-field_' . $acf_field_name[$i] . '" value="' . $custom_field_meta_value . '" class="form-control datepicker' . $required . '" />';
							break;

							case ( 'select' ) : case ( 'checkbox' ) : case ( 'radio' ) :
							
							$acf_custom_keys = get_post_custom_keys( tt_acf_group_id_property() );
				
							$acf_object = get_field_object($acf_field_name[$i]);
							
							// ACF: Loop through field keys, as we can't output choices by name, but only by their key
							foreach ( $acf_custom_keys as $key => $value ) {
						    if ( stristr( $value, 'field_' ) ) {
						      $acf_field = get_field_object( $value, tt_acf_group_id_property() );
						      if ( $acf_field['name'] == $acf_field_name[$i] ) {
										
										// Select
										if ( $acf_field_type[$i] == 'select' ) {
										
										echo '<select name="' . $acf_field['name'] . '" class="' . $required . '" data-placeholder="' . __( $acf_field_label[$i], 'tt' ) . '">';
										
											echo '<option value=""></option>';
											
											foreach( $acf_field['choices'] as $key => $value ) {
												$value_db = get_post_meta( $property_id, $acf_field_name[$i], true );
												echo '<option value="' . $key . '"' . selected( $value_db, $key, false ) . '>' . $value . '</option>';
											}
											
										echo '</select>';
										
										}				
										
										// Checkboxes & Radio Buttons
										if ( $acf_field_type[$i] == 'checkbox' || $acf_field_type[$i] == 'radio' ) {
											
											$acf_field_array = array();
										
											$output  = '<div class="row">';
											
											foreach( $acf_field['choices'] as $key => $value ) {
												
												$output .= '<div class="col-sm-12">';
												
												// Checkbox = array
												if ( $acf_field_type[$i] == 'checkbox' ) {
													if ( is_array( get_post_meta( $property_id, $acf_field_name[$i], true ) ) && in_array( $key, get_post_meta( $property_id, $acf_field_name[$i], true ) ) ) {
														$array_db = $key;
													}
													else {
														$array_db = "";
													}
													$output .= '<input type="' . $acf_field_type[$i] . '" name="' . $acf_field_name[$i] . '[]" id="' . $acf_field_name[$i] . '-' . $key . '" class="' . $required . '" value="' . $key . '" ' . checked( $array_db, $key, false ) . ' />';
												}
												// Radio = single value
												else {
													$value_db = get_post_meta( $property_id, $acf_field_name[$i], true );
													$output .= '<input type="' . $acf_field_type[$i] . '" name="' . $acf_field_name[$i] . '" id="' . $acf_field_name[$i] . '-' . $key . '" class="' . $required . '" value="' . $key . '" ' . checked( $value_db, $key, false ) . ' />';
												}
												$output .= '<label for="' . $acf_field_name[$i] . '-' . $key . '">' .  __( $value, 'tt' ) . '</label>';
												$output .= '</div>';
												
											}
											
											$output .= '</div>';
											
											echo $output;
											
										}
							
									}
						    }
							}
						
							break;
							
						}
						
						echo '</li>';
						
						$i++;
						
					endwhile;
					
					wp_reset_postdata();
					
					?>
					</ul>
											
				</div>
				<?php } ?>
				
				<?php if ( !$realty_theme_option['property-floor-plan-disable'] ) { ?>
				<div id="floor-plan">
						
					<?php
					if ( $realty_theme_option['property-title-floor-plan'] ) { echo '<h3 class="section-title"><span>' . $realty_theme_option['property-title-floor-plan'] . '</span></h3>'; }
					
					$property_floor_plan_title = get_post_meta( $property_id, 'estate_floor_plan_title', true );
					$property_floor_plan_price = get_post_meta( $property_id, 'estate_floor_plan_price', true );
					$property_floor_plan_size = get_post_meta( $property_id, 'estate_floor_plan_size', true );
					$property_floor_plan_rooms = get_post_meta( $property_id, 'estate_floor_plan_rooms', true );
					$property_floor_plan_bedrooms = get_post_meta( $property_id, 'estate_floor_plan_bedrooms', true );
					$property_floor_plan_bathrooms = get_post_meta( $property_id, 'estate_floor_plan_bathrooms', true );
					$property_floor_plan_description = get_post_meta( $property_id, 'estate_floor_plan_description', true );
					$property_floor_plan_image = get_post_meta( $property_id, 'estate_floor_plan_image', true );
					
					$i = 0;
					
					if ( is_array( $property_floor_plan_image ) ) {
						foreach ( $property_floor_plan_image as $image ) {
						?>
						<div class="floor-plan-item row">
							
							<div class="form-group col-sm-4">
								<label for="floor-plan-title"><?php _e( 'Title', 'tt' ); ?></label>
								<input type="text" name="floor-plan-title[]" id="floor-plan-title" class="form-control" value="<?php echo $property_floor_plan_title[$i]; ?>" />
							</div>
							
							<div class="form-group col-sm-4">
								<label for="floor-plan-price"><?php echo __( 'Price', 'tt' ) . ' ' . __( 'in', 'tt' ) . ' ' . $currency; ?></label>
								<input type="number" name="floor-plan-price[]" id="floor-plan-price" class="form-control" value="<?php echo $property_floor_plan_price[$i]; ?>" step="0.01" />
							</div>
							
							<div class="form-group col-sm-4">
								<label for="floor-plan-size"><?php _e( 'Size', 'tt' ); ?></label>
								<input type="number" name="floor-plan-size[]" id="floor-plan-size" class="form-control" value="<?php echo $property_floor_plan_size[$i]; ?>" step="0.01" />
							</div>
							
							<div class="form-group col-sm-4">
								<label for="floor-plan-rooms"><?php _e( 'Rooms', 'tt' ); ?></label>
								<input type="number" name="floor-plan-rooms[]" id="floor-plan-rooms" class="form-control" value="<?php echo $property_floor_plan_rooms[$i]; ?>" step="0.5" />
							</div>
							
							<div class="form-group col-sm-4">
								<label for="floor-plan-bedrooms"><?php _e( 'Bedrooms', 'tt' ); ?></label>
								<input type="number" name="floor-plan-bedrooms[]" id="floor-plan-bedrooms" class="form-control" value="<?php echo $property_floor_plan_bedrooms[$i]; ?>" step="0.5" />
							</div>
							
							<div class="form-group col-sm-4">
								<label for="floor-plan-bathrooms"><?php _e( 'Bathrooms', 'tt' ); ?></label>
								<input type="number" name="floor-plan-bathrooms[]" id="floor-plan-bathrooms" class="form-control" value="<?php echo $property_floor_plan_bathrooms[$i]; ?>" step="0.5" />
							</div>
							
							<div class="form-group col-sm-4">
								<label for="floor-plan-image"><?php echo __( 'Image', 'tt' ) . ' (' . __( 'required', 'tt' ) . ')'; ?></label>
								<?php echo wp_get_attachment_image( $property_floor_plan_image[$i], 'property-thumb' ); ?>
								<input type="file" name="floor-plan-image[]" id="floor-plan-image" class="form-control" value="<?php _e( 'Select File..', 'tt' ); ?>" title="" />
							</div>
							
							<div class="form-group col-sm-8">
								<label for="floor-plan-description"><?php _e( 'Description', 'tt' ); ?></label>
								<textarea type="text" name="floor-plan-description[]" id="floor-plan-description" class="form-control" rows="5"><?php echo $property_floor_plan_description[$i]; ?></textarea>
							</div>
							
							<div class="clearfix col-sm-12">
								<a class="delete-floor-plan btn btn-sm"><?php _e( 'Delete Plan','tt' ); ?></a>
							</div>
						
						</div>
						<?php	
						$i++;
						}
					}
					// No Floor Plan Added
					else {
					?>	
					<div class="floor-plan-item row">
						
						<div class="form-group col-sm-4">
							<label for="floor-plan-title"><?php _e( 'Title', 'tt' ); ?></label>
							<input type="text" name="floor-plan-title[]" id="floor-plan-title" class="form-control" value="" />
						</div>
						
						<div class="form-group col-sm-4">
							<label for="floor-plan-price"><?php _e( 'Price', 'tt' ); ?></label>
							<input type="number" name="floor-plan-price[]" id="floor-plan-price" class="form-control" value="" step="0.01" />
						</div>
						
						<div class="form-group col-sm-4">
							<label for="floor-plan-size"><?php _e( 'Size', 'tt' ); ?></label>
							<input type="number" name="floor-plan-size[]" id="floor-plan-size" class="form-control" value="" step="0.01" />
						</div>
						
						<div class="form-group col-sm-4">
							<label for="floor-plan-rooms"><?php _e( 'Rooms', 'tt' ); ?></label>
							<input type="number" name="floor-plan-rooms[]" id="floor-plan-rooms" class="form-control" value="" step="0.5" />
						</div>
						
						<div class="form-group col-sm-4">
							<label for="floor-plan-bedrooms"><?php _e( 'Bedrooms', 'tt' ); ?></label>
							<input type="number" name="floor-plan-bedrooms[]" id="floor-plan-bedrooms" class="form-control" value="" step="0.5" />
						</div>
						
						<div class="form-group col-sm-4">
							<label for="floor-plan-bathrooms"><?php _e( 'Bathrooms', 'tt' ); ?></label>
							<input type="number" name="floor-plan-bathrooms[]" id="floor-plan-bathrooms" class="form-control" value="" step="0.5" />
						</div>
						
						<div class="form-group col-sm-4">
							<label for="floor-plan-image"><?php echo __( 'Image', 'tt' ) . ' (' . __( 'required', 'tt' ) . ')'; ?></label>
							<input type="file" name="floor-plan-image[]" id="floor-plan-image" class="form-control" value="" title="" />
						</div>
						
						<div class="form-group col-sm-8">
							<label for="floor-plan-description"><?php _e( 'Description', 'tt' ); ?></label>
							<textarea type="text" name="floor-plan-description[]" id="floor-plan-description" class="form-control" rows="5"></textarea>
						</div>
						
						<div class="clearfix col-sm-12">
							<a class="delete-floor-plan btn btn-sm"><?php _e( 'Delete Plan','tt' ); ?></a>
						</div>
					
					</div>
					<?php	
					}
					?>
					
				</div>
				
				<?php 
				_e( '<a class="add-floor-plan btn btn-default">' . __( 'Add New Plan','tt' ) . '</a>', 'tt' );
				} 
				?>
				
				<div class="clearfix"></div><br /><br />
				
				<?php wp_nonce_field( 'property_submit', 'nonce_property_submit' ); ?>
				<input type="hidden" name="property_id" value="<?php echo $_GET['edit']; ?>" />
				<?php if ( $update_property ) { ?>
				<input type="submit" name="update" id="update" value="<?php _e( 'Update Property', 'tt' ); ?>" />
				<?php if ( get_post_status($property_id) == "draft" && $allow_to_publish ) { ?>
				<input type="submit" name="publish" id="publish" value="<?php _e( $submit_button_text, 'tt' ); ?>" />
				<?php }
				}
				else { ?>
				<?php if ( $allow_to_publish ) { ?>
				<input type="submit" name="save" id="save" value="<?php _e( $save_button_text, 'tt' );; ?>" />
				<?php } ?>
				<input type="submit" name="publish" id="publish" value="<?php _e( $submit_button_text, 'tt' ); ?>" />
				<?php } ?>	
				</div>
				
			</form>
			
			<?php 
			}
			else {
				echo '<p class="alert alert-danger">' . __( 'This property doesn\'t belong to you.', 'tt' ) . '</p>';
			}
	
		} // END If Property Submit For Subscribers Disabled	
		else {
			_e( 'Property submit is not allowed.', 'tt' );
		}
	
	} // END If Logged-In
	
	else {
		echo '<p class="alert alert-danger">' . __( 'You have to be logged-in to submit properties.', 'tt' ) . '</p>';
	}
	?>

</div>

<?php get_footer(); ?>	