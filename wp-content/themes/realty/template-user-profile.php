<?php
/*
Template Name: User - Profile
*/

if ( !empty( $_POST['submit'] ) ) {

	$allowed_file_types = array( "image/gif", "image/jpeg", "image/jpg", "image/png" );
	$upload_errors = '';
	
	// User Image Upload
	if ( !empty( $_FILES['user_image']['name'] ) ) {
	
		// check to make sure its a successful upload
		if ( $_FILES['user_image']['error'] !== UPLOAD_ERR_OK ) __return_false();
		
		if ( !in_array( $_FILES['user_image']['type'], $allowed_file_types ) ) {
	    	$upload_errors .= '<p class="alert alert-danger" role="alert" >' . __( 'Invalid file type:', 'tt' ) . ' "' . $_FILES['user_image']['type'] . '". ' . __( 'Supported file types: gif, jpg, jpeg, png.', 'tt' ) . '</p>';
  	}
  	
  	// Max. File Size 5 MB
		if ( $_FILES['user_image']['size'] > 5000000 ) {
			$upload_errors .= '<p class="alert alert-danger" role="alert" >' . __( 'File is too large. Max. upload file size is 5 MB.', 'tt' ) . '</p>';
		}
		
		if ( !$upload_errors ) {
		
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			require_once( ABSPATH . 'wp-admin/includes/media.php' );
			$attachment_id = media_handle_upload( 'user_image', 0 );				
			$attachment_url = wp_get_attachment_url( $attachment_id );
			// Upload Profile Picture	
			update_user_meta( get_current_user_id(), 'user_image', $attachment_url );
			
		}

	}

	if ( !$upload_errors ) {
	
		// Update User Profile Information
		wp_update_user( 
			array(
				'ID' 										=> get_current_user_id(),
				'company_name' 					=> $_POST['company_name'],
				'first_name' 						=> $_POST['first_name'],
				'last_name' 						=> $_POST['last_name'],
				'office_phone_number'		=> $_POST['office_phone_number'],
				'mobile_phone_number'		=> $_POST['mobile_phone_number'],
				'fax_number'						=> $_POST['fax_number'],
				'user_email' 						=> $_POST['user_email'],
				'user_url'	 						=> $_POST['user_url'], 
				'description' 					=> $_POST['description'],
				'custom_facebook' 			=> $_POST['custom_facebook'],
				'custom_twitter' 				=> $_POST['custom_twitter'],
				'custom_google' 				=> $_POST['custom_google'],
				'custom_linkedin' 			=> $_POST['custom_linkedin'],
			)
		);
		
		// Update Password, If Not Empty
		if ( $_POST['user_pass'] != '' ) {
			wp_update_user( 
				array(
					'ID' 								=> get_current_user_id(),
					'user_pass' 				=> $_POST['user_pass']
				) 
			);
		}
	
	}
	
}

$hide_sidebar = get_post_meta( $post->ID, 'estate_page_hide_sidebar', true );

get_header();

while ( have_posts() ) : the_post(); 
?>
	</div><!-- .container -->
	<?php tt_page_banner();	?>
	<div id="page-user-profile" class="container">

	<div class="row">
	
		<?php 
		// Check for Agent Sidebar
		if ( !$hide_sidebar && is_active_sidebar( 'sidebar_page' ) ) {
			echo '<div class="col-sm-8 col-md-9">';
		} else {
			echo '<div class="col-sm-12">';
		}
		?>
		
			<div id="main-content" class="content-box primary-tooltips">
				
				<?php
				
				the_content();
						
				if ( is_user_logged_in() ) {
				$user_id = get_current_user_id();
				$userdata = get_userdata( $user_id );
				
				// Submitted -> Check For Errors
				if ( !empty( $_POST['submit'] ) ) {
				
					if ( $upload_errors ) {
						echo $upload_errors;
					}
					else {
						echo '<p class="alert alert-success" role="alert">' . __( 'Profile successfully updated.', 'tt' ) . '</p>';
					} 
				
				}
				?>
				
				<form id="profile-edit" enctype="multipart/form-data" method="post">
				
					<div class="row">
					
						<div class="col-sm-4">
						
							<div class="form-group" style="margin-bottom:19px">
							<label for="user_image"><?php _e( 'Profile Picture', 'tt' ); ?> <i class="fa fa-info-circle" data-toggle="tooltip" title="<?php _e( '(JPEG, JPG, PNG, GIF. Max: 5 MB)', 'tt' ); ?>"></i></label>
								<p style="position: relative">
								<?php 
								if ( $userdata->user_image ) { 
									$profile_image_id = tt_get_image_id( $userdata->user_image );
									$profile_image_array = wp_get_attachment_image_src( $profile_image_id, 'square-400' );
								?>
								<img id="preview-user-image" src="<?php echo $profile_image_array[0]; ?>" alt="" />
								<i class="fa fa-close delete-uploaded-image" data-user-id="<?php echo get_current_user_id(); ?>"></i>
								<?php } else { ?>
								<img id="preview-user-image" src="//placehold.it/400x400/eee/ccc/&text=.." alt="" />
								<?php } ?>
								</p>
								<input type="file" name="user_image" id="user_image" />
							</div>
							
							<div class="form-group">
								<label for="custom_facebook"><?php _e( 'Facebook', 'tt' ); ?></label>
								<input type="url" name="custom_facebook" id="custom_facebook" class="form-control" value="<?php echo $userdata->custom_facebook; ?>" placeholder="http://facebook.com" />
							</div>
							
							<div class="form-group">
								<label for="custom_twitter"><?php _e( 'Twitter', 'tt' ); ?></label>
								<input type="url" name="custom_twitter" id="custom_twitter" class="form-control" value="<?php echo $userdata->custom_twitter; ?>" placeholder="http://twitter.com" />
							</div>
							
							<div class="form-group">
								<label for="custom_google"><?php _e( 'Google+', 'tt' ); ?></label>
								<input type="url" name="custom_google" id="custom_google" class="form-control" value="<?php echo $userdata->custom_google; ?>" placeholder="http://google.com" />
							</div>
							
							<div class="form-group">
								<label for="custom_linkedin"><?php _e( 'Linkedin', 'tt' ); ?></label>
								<input type="url" name="custom_linkedin" id="custom_linkedin" class="form-control" value="<?php echo $userdata->custom_linkedin; ?>" placeholder="http://linkedin.com" />
							</div>
						
						</div>
						
						<div class="col-sm-8">
						
							<div class="form-group">
								<label for="user_name"><?php _e( 'Username', 'tt' ); ?> <i class="fa fa-info-circle" data-toggle="tooltip" title="<?php _e( 'Usernames cannot be changed.', 'tt' ); ?>"></i></label>
								<input type="text" name="user_name" id="user_name" class="form-control text-muted" value="<?php echo $userdata->user_login; ?>" disabled />							
							</div>
						
							<div class="form-group">
								<label for="description"><?php _e( 'About', 'tt' ); ?></label>
								<textarea name="description" id="description" class="form-control text-muted" rows="5"><?php echo $userdata->description; ?></textarea>
							</div>
							
							<div class="form-group">
								<label for="company_name"><?php _e( 'Company Name', 'tt' ); ?></label>
								<input type="text" name="company_name" id="company_name" class="form-control" value="<?php echo $userdata->company_name; ?>" />
							</div>
							
							<div class="form-group">
								<label for="first_name"><?php _e( 'First Name', 'tt' ); ?></label>
								<input type="text" name="first_name" id="first_name" class="form-control" value="<?php echo $userdata->first_name; ?>" />
							</div>
							
							<div class="form-group">
								<label for="last_name"><?php _e( 'Last Name', 'tt' ); ?></label>
								<input type="text" name="last_name" id="last_name" class="form-control" value="<?php echo $userdata->last_name; ?>" />
							</div>
							
							<div class="form-group">
								<label for="office_phone_number"><?php _e( 'Office Phone Number', 'tt' ); ?></label>
								<input type="text" name="office_phone_number" id="office_phone_number" class="form-control" value="<?php echo $userdata->office_phone_number; ?>" />
							</div>
							
							<div class="form-group">
								<label for="mobile_phone_number"><?php _e( 'Mobile Phone Number', 'tt' ); ?></label>
								<input type="text" name="mobile_phone_number" id="mobile_phone_number" class="form-control" value="<?php echo $userdata->mobile_phone_number; ?>" />
							</div>
							
							<div class="form-group">
								<label for="fax_number"><?php _e( 'Fax Number', 'tt' ); ?></label>
								<input type="text" name="fax_number" id="fax_number" class="form-control" value="<?php echo $userdata->fax_number; ?>" />
							</div>
							
							<div class="form-group">
								<label for="user_url"><?php _e( 'Website', 'tt' ); ?></label>
								<input type="url" name="user_url" id="user_url" class="form-control" value="<?php echo $userdata->user_url; ?>" placeholder="http://yourcompany.com" />
							</div>
							
							<div class="form-group">
								<label for="user_email"><?php _e( 'Email Address', 'tt' ); ?> <i class="fa fa-info-circle" data-toggle="tooltip" title="<?php _e( 'Send property contact form messages to this address.', 'tt' ); ?>"></i></label>
								<input type="email" name="user_email" id="user_email" class="form-control" value="<?php echo $userdata->user_email; ?>" />
							</div>
							
							<div class="form-group">
								<label for="user_pass"><?php _e( 'Password', 'tt' ); ?></label>
								<input type="text" name="user_pass" id="user_pass" class="form-control" value="" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;" />
							</div>
							
							<input type="submit" name="submit" id="submit-profile-update" value="<?php _e( 'Save Changes', 'tt' ); ?>" />
							
						</div>
						
					</div>
				
				</form>
				
				<?php
				}
				else {
					_e( 'Login to view and edit your profile.', 'tt' );
				}
				
				?>
				
			</div>
		
		</div><!-- .col-sm-9 -->
		
		<?php 
		// Check for Page Sidebar
		if ( !$hide_sidebar && is_active_sidebar( 'sidebar_page' ) ) : 
		?>
		<div class="col-sm-4 col-md-3">
			<ul id="sidebar">
				<?php dynamic_sidebar( 'sidebar_page' ); ?>
			</ul>
		</div>
		<?php endif; ?>
	
	
	</div><!-- .row -->
	
	<script>
	function previewProfilePicture( input ) {
	  if ( input.files && input.files[0] ) {
	    var reader = new FileReader();
	    reader.onload = function (e) {
	        jQuery('#preview-user-image').attr( 'src', e.target.result );
	    }
	    reader.readAsDataURL( input.files[0] );
	  }
	}
	
	jQuery('#user_image').change(function() {
		previewProfilePicture(this);
	});
	
	/* Ajax - Property Submit - Delete Uploaded Image
	-------------------------*/
	jQuery('.delete-uploaded-image').click(function() {
		
		jQuery.ajax({
	    
	    type: 'POST',
	    url: ajaxURL,
	    data: {
		    'action'          :   'tt_ajax_delete_user_profile_picture_function', // WP Function
		    'user_id'    			:   jQuery(this).attr('data-user-id')
	    },
	    success: function (response) {
				jQuery('#preview-user-image').attr('src', '//placehold.it/400x400/eee/ccc/&text=..');
	    },
	    error: function (response) {
	    	// Error Message
	    }
	    
	  });
		
	});
	</script>
	
<?php
endwhile;

get_footer(); 
?>