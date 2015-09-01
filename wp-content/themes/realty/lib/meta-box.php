<?php
if ( !function_exists('estate_register_meta_boxes') ) {
	function estate_register_meta_boxes( $meta_boxes ) {
	            
		$prefix = 'estate_';
		
		$agents = array( '' => __( 'None', 'tt' ) );
		// Get all users with role "agent"
		$all_agents = get_users( array( 'role' => 'agent', 'fields' => 'ID' ) );
		foreach( $all_agents as $agent ) { 
			$agents[$agent] = get_user_meta($agent, 'first_name', true ) . ' ' . get_user_meta($agent, 'last_name', true );
		}
	
		/* PROPERTY
		============================== */
		$meta_boxes[] = array(		
			'id' 						=> 'property_settings',
			'title' 				=> __( 'Property Settings', 'tt' ),
			'pages' 				=> array( 'property' ),
			'context' 			=> 'normal',
			'priority' 			=> 'high',
			'autosave' 			=> true,
			'fields' 				=> array(
				array(
					'name' 					=> __( 'Property Layout', 'tt' ),
					'id'   					=> "{$prefix}property_layout",
					'desc'  				=> __( 'Choose Property Layout.', 'tt' ),
					'type' 					=> 'select',
					'options'  => array(
						'theme_option_setting' 	=> __( 'Theme Option Setting', 'tt' ),
						'full_width' 						=> __( 'Full Width', 'tt' ),
						'boxed' 								=> __( 'Boxed', 'tt' ),
					),
					'std'  					=> 'theme_option_setting',
				),
				array(
					'name' 					=> __( 'Property Status Update', 'tt' ),
					'id'   					=> "{$prefix}property_status_update",
					'desc'  				=> __( 'E.g. "Sold", "Rented Out" etc.', 'tt' ),
					'type' 					=> 'text',
					'std'   				=> __( '', 'tt' ),
				),
				array(
					'name' 					=> __( 'Featured Property', 'tt' ),
					'id'   					=> "{$prefix}property_featured",
					'type' 					=> 'checkbox',
					'std'  					=> 0,
				),
				array(
					'name' 					=> __( 'Property Video Provider', 'tt' ),
					'id'   					=> "{$prefix}property_video_provider",
					'desc'  				=> __( '', 'tt' ),
					'type' 					=> 'select',
					'options'				=> array(
						'none'						=> __( 'None', 'tt' ),
						'youtube'					=> __( 'YouTube', 'tt' ),
						'vimeo'						=> __( 'Vimeo', 'tt' )
					),
				),
				array(
					'name' 					=> __( 'Property Video ID', 'tt' ),
					'id'   					=> "{$prefix}property_video_id",
					'desc'  				=> __( '', 'tt' ),
					'type' 					=> 'text',
					'std'   				=> __( '', 'tt' ),
				),
				array(
					'name'             => __( 'Property Images', 'tt' ),
					'id'               => "{$prefix}property_images",
					'type'             => 'image_advanced',
					'max_file_uploads' => 100,
				),
				array(
					'name'  				=> __( 'Property ID', 'tt' ),
					'id'    				=> "{$prefix}property_id",
					'desc'  				=> __( '', 'tt' ),
					'type'  				=> 'text',
					'std'   				=> __( '', 'tt' ),
				),
				array(
					'name'  				=> __( 'Address', 'tt' ),
					'id'    				=> "{$prefix}property_address",
					'desc'  				=> __( '', 'tt' ),
					'type'  				=> 'text',
					'std'   				=> __( '', 'tt' ),
				),
				array(
	        'id'            => "{$prefix}property_location",
	        'name'          => __( 'Google Maps' , 'tt' ),
	        'desc'          => __( 'Enter Property Address Above, Then Click "Find Address" To Search For Exact Location On The Map. Drag & Drop Map Marker If Necessary.' , 'tt' ),
	        'type'          => 'map',
	        'std'           => '', // 'latitude,longitude[,zoom]' (zoom is optional)
	        'style'         => 'width: 400px; height: 200px; margin-bottom: 1em',
	        'address_field' => "{$prefix}property_address", // Name of text field where address is entered. Can be list of text fields, separated by commas (for ex. city, state)
	      ),
				array(
					'name' => __( 'Available From', 'tt' ),
					'id'   => "{$prefix}property_available_from",
					'type' => 'date',
					// jQuery date picker options. See here http://api.jqueryui.com/datepicker
					'js_options' => array(
						'appendText'      => __( '(YYYYMMDD)', 'tt' ),
						'dateFormat'      => __( 'yymmdd', 'tt' ),
						'changeMonth'     => true,
						'changeYear'      => true,
						'showButtonPanel' => false,
					),
				),
				/*
				array(
					'name' => __( 'Available Until', 'tt' ),
					'id'   => "{$prefix}property_available_until",
					'type' => 'date',
					// jQuery date picker options. See here http://api.jqueryui.com/datepicker
					'js_options' => array(
						'appendText'      => __( '(YYYYMMDD)', 'tt' ),
						'dateFormat'      => __( 'yymmdd', 'tt' ),
						'changeMonth'     => true,
						'changeYear'      => true,
						'showButtonPanel' => false,
					),
				),
				*/
				array(
					'name'  				=> __( 'Property Price Prefix', 'tt' ),
					'id'    				=> "{$prefix}property_price_prefix",
					'desc'  				=> __( 'Appears Before Property Price (i.e. "from")', 'tt' ),
					'type'  				=> 'text',
					'std'   				=> __( '', 'tt' ),
				),
				array(
					'name'  				=> __( 'Property Price', 'tt' ),
					'id'    				=> "{$prefix}property_price",
					'desc'  				=> __( 'Digits Only. Enter "-1" for "Price Upon Request"', 'tt' ),
					'type'  				=> 'number',
					'std'   				=> __( '1000', 'tt' ),
					'step'  				=> 0.01,
					'min'						=> '-1'
				),
				array(
					'name'  				=> __( 'Property Price Suffix', 'tt' ),
					'id'    				=> "{$prefix}property_price_text",
					'desc'  				=> __( 'Appears After Property Price (i.e. "per month")', 'tt' ),
					'type'  				=> 'text',
					'std'   				=> __( '', 'tt' ),
				),
				array(
					'name'  				=> __( 'Size', 'tt' ),
					'id'    				=> "{$prefix}property_size",
					'desc'  				=> __( 'Property Size (Digits Only, i.e. "250")', 'tt' ),
					//'type'  				=> 'text',
					'type'  				=> 'number',
					'std'   				=> __( '250', 'tt' ),
					'step'  				=> 0.01,
				),
				array(
					'name'  				=> __( 'Size Unit', 'tt' ),
					'id'    				=> "{$prefix}property_size_unit",
					'desc'  				=> __( 'Unit Appears After Property Size (i.e. "sq ft")', 'tt' ),
					'type'  				=> 'text',
					'std'   				=> __( 'sq ft', 'tt' ),
				),
				array(
					'name' 					=> __( 'Rooms', 'tt' ),
					'id'   					=> "{$prefix}property_rooms",
					'type' 					=> 'number',
					'prefix' 				=> __( '', 'tt' ),
					'suffix' 				=> __( '', 'tt' ),
					'min'   				=> 0,
					'step'  				=> 0.5
				),
				array(
					'name' 					=> __( 'Bedrooms', 'tt' ),
					'id'   					=> "{$prefix}property_bedrooms",
					'type' 					=> 'number',
					'prefix' 				=> __( '', 'tt' ),
					'suffix' 				=> __( '', 'tt' ),
					'min'   				=> 0,
					'step'  				=> 0.5
				),
				array(
					'name' 					=> __( 'Bathrooms', 'tt' ),
					'id'   					=> "{$prefix}property_bathrooms",
					'type' 					=> 'number',
					'prefix' 				=> __( '', 'tt' ),
					'suffix' 				=> __( '', 'tt' ),
					'min'   				=> 0,
					'step'  				=> 0.5
				),
				array(
					'name' 					=> __( 'Garages', 'tt' ),
					'id'   					=> "{$prefix}property_garages",
					'type' 					=> 'number',
					'prefix' 				=> __( '', 'tt' ),
					'suffix' 				=> __( '', 'tt' ),
					'min'   				=> 0,
					'step'  				=> 0.5
				),
				array(
					'name'     			=> __( 'Contact Information', 'tt' ),
					'id'       			=> "{$prefix}property_contact_information",
					'type'     			=> 'select',
					'options'  => array(
						'all' 						=> __( 'Profile Information & Contact Form', 'tt' ),
						'form' 						=> __( 'Contact Form Only', 'tt' ),
						'none' 						=> __( 'None', 'tt' ),
					),
					'std'  					=> 'all',
				),
				array(
					'name'     			=> __( 'Assign Agent', 'tt' ),
					'id'       			=> "{$prefix}property_custom_agent", // Until Realty 1.2 "property_agent"
					'desc'          => __( 'Selected agent will be able to edit this property.' , 'tt' ),
					'type'     			=> 'select',
					'options'  			=> $agents,
				),
				array(
					'name' 					=> __( 'Internal Note', 'tt' ),
					'id'   					=> "{$prefix}internal_note",
					'desc'          => __( 'Note for internal use. Won\'t appear on the frontend.' , 'tt' ),
					'type' 					=> 'textarea',
					'std'  					=> __( '', 'tt' ),
				),
				array(
					'name'  				=> __( 'Attachments', 'tt' ),
					'id'    				=> "{$prefix}property_attachments",
					'desc'  				=> __( '', 'tt' ),
					'type'  				=> 'file_advanced',
					'std'   				=> __( '', 'tt' ),
				),
			)
		);
		
		
		/* TESTIMONIAL
		============================== */
		$meta_boxes[] = array(		
			'id' 						=> 'testimonial_settings',
			'title' 				=> __( 'Testimonial', 'tt' ),
			'pages' 				=> array( 'testimonial' ),
			'context' 			=> 'normal',
			'priority' 			=> 'high',
			'autosave' 			=> true,
			'fields' 				=> array(
				array(
					'name' 					=> __( 'Testimonial Text', 'tt' ),
					'id'   					=> "{$prefix}testimonial_text",
					'type' 					=> 'textarea',
					'std'  					=> __( '', 'tt' ),
				),
			)
		);
		
		
		/* POST TYPE "GALLERY"
		============================== */
		$meta_boxes[] = array(		
			'id' 						=> 'post_type_gallery',
			'title' 				=> __( 'Gallery Settings', 'tt' ),
			'pages' 				=> array( 'post' ),
			'context' 			=> 'normal',
			'priority' 			=> 'high',
			'autosave' 			=> true,
			'fields' 				=> array(
				array(
					'name'             => __( 'Gallery Images', 'tt' ),
					'id'               => "{$prefix}post_gallery",
					'type'             => 'image_advanced',
					'max_file_uploads' => 100,
				),
			)
		);
		
		
		/* POST TYPE "VIDEO"
		============================== */
		$meta_boxes[] = array(		
			'id' 						=> 'post_type_video',
			'title' 				=> __( 'Video Settings', 'tt' ),
			'pages' 				=> array( 'post' ),
			'context' 			=> 'normal',
			'priority' 			=> 'high',
			'autosave' 			=> true,
			'fields' 				=> array(
				array(
				'name'	=> 'Full Video URL',
				'id'	=> "{$prefix}post_video_url",
				'desc'	=> 'Insert Full Video URL (i.e. <strong>http://vimeo.com/99370876</strong>)',
				'type' 	=> 'text',
				'std' 	=> ''
			)
			)
		);
		
		
		/* PAGE SETTINGS
		============================== */
		$meta_boxes[] = array(		
			'id' 						=> 'pages_settings',
			'title' 				=> __( 'Page Settings', 'tt' ),
			'pages' 				=> array( 'post', 'page', 'property', 'agent' ),
			'context' 			=> 'normal',
			'priority' 			=> 'high',
			'autosave' 			=> true,
			'fields' 				=> array(
				array(
					'name' 					=> __( 'Hide Sidebar', 'tt' ),
					'id'   					=> "{$prefix}page_hide_sidebar",
					'type' 					=> 'checkbox',
					'std'  					=> 0,
				),
				// Intro Page Only
				array(
					'name'             => __( 'Intro Fullscreen Background Slideshow Images', 'tt' ),
					'id'               => "{$prefix}intro_fullscreen_background_slideshow_images",
					'class'						 => 'intro-only',
					'type'             => 'image_advanced',
					'max_file_uploads' => 100,
				),
				/* XXX
				array(
					'name'             => __( 'Intro Fullscreen Background Video URL', 'tt' ),
					'id'               => "{$prefix}intro_fullscreen_background_video_url",
					'class'						 => 'intro-only',
					'type'             => 'text',
					'desc'						 => 'Insert Full Video URL (i.e. <strong>https://www.youtube.com/watch?v=0q_oXY0thxo</strong>)',
				),
				*/
			)
		);
		
		
		// Page Template "Property - Slideshow"
		$meta_boxes[] = array(		
			'id' 						=> 'slideshow_settings',
			'title' 				=> __( 'Slideshow Settings', 'tt' ),
			'pages' 				=> array( 'page' ),
			'context' 			=> 'normal',
			'priority' 			=> 'high',
			'autosave' 			=> true,
			'fields' 				=> array(
				array(
					'name' 					=> __( 'Type', 'tt' ),
					'id'   					=> "{$prefix}property_slideshow_type",
					'desc'  				=> __( '', 'tt' ),
					'type' 					=> 'select',
					'options'  			=> array(
						'featured' 				=> __( 'Featured Properties', 'tt' ),
						'latest' 					=> __( 'Latest Three Properties', 'tt' ),
						'selected' 				=> __( 'Selected Properties (choose below)', 'tt' ),
					),
					'std'  					=> 'latest',
				),
				array(
					'name'    			=> __( 'Selected Properties', 'tt' ),
					'id'      			=> "{$prefix}property_slideshow_selected_properties",
					'type'    			=> 'post',
					'post_type' 		=> 'property',
					'field_type' 		=> 'select_advanced',
					'multiple'    	=> true,
					// Query arguments (optional). No settings means get all published posts
					'query_args' 		=> array(
						'post_status' 		=> 'publish',
						'posts_per_page' 	=> '-1',
					)
				),
				array(
					'name' 					=> __( 'Property Search', 'tt' ),
					'id'   					=> "{$prefix}property_slideshow_search",
					'desc'  				=> __( 'Setup in Theme Options Panel', 'tt' ),
					'type' 					=> 'radio',
					'options'				=> array(
						'none'						=> __( 'No Search', 'tt' ),
						'custom'					=> __( 'Property Search', 'tt' ),
						'mini'						=> __( 'Property Search Mini', 'tt' ),
					)
				),
			)
		);
		
		
		// Page Template "Property - Map"
		$meta_boxes[] = array(		
			'id' 						=> 'property_map_settings',
			'title' 				=> __( 'Property Map Settings', 'tt' ),
			'pages' 				=> array( 'page' ),
			'context' 			=> 'normal',
			'priority' 			=> 'high',
			'autosave' 			=> true,
			'fields' 				=> array(
				array(
					'name' 					=> __( 'Property Location', 'tt' ),
					'id'   					=> "{$prefix}property_map_location",
					'desc'  				=> __( '', 'tt' ),
					'type'    			=> 'taxonomy_advanced',
					'options' 			=> array(
						'taxonomy' 				=> 'property-location', // Taxonomy name
						'type' 						=> 'select_advanced', // How to show taxonomy: 'checkbox_list' (default) or 'checkbox_tree', 'select_tree', select_advanced or 'select'. Optional
						'args' 						=> array() // Additional arguments for get_terms() function. Optional
					),
				),
				array(
					'name' 					=> __( 'Property Status', 'tt' ),
					'id'   					=> "{$prefix}property_map_status",
					'desc'  				=> __( '', 'tt' ),
					'type'    			=> 'taxonomy_advanced',
					'options' 			=> array(
						'taxonomy' 				=> 'property-status',
						'type' 						=> 'checkbox_list',
						'args' 						=> array()
					),
				),
				array(
					'name' 					=> __( 'Property Type', 'tt' ),
					'id'   					=> "{$prefix}property_map_type",
					'desc'  				=> __( '', 'tt' ),
					'type'    			=> 'taxonomy_advanced',
					'options' 			=> array(
						'taxonomy' 				=> 'property-type',
						'type' 						=> 'checkbox_list',
						'args' 						=> array()
					),
				),
				array(
					'name' 					=> __( 'Custom Zoom Level', 'tt' ),
					'id'   					=> "{$prefix}property_map_custom_zoom_level",
					'desc'  				=> __( 'Enter only, if your properties are located very closeby, and you would like to zoom closer. Zoom targets oldest property.', 'tt' ),
					'type' 					=> 'number',
					'step'  				=> 1,
					'min'						=> 0
				),
			)
		);
	
		return $meta_boxes;
	}
}
add_filter( 'rwmb_meta_boxes', 'estate_register_meta_boxes' );


// Floor Plan Meta Box

if ( !function_exists('tt_register_meta_box_floor_plan') ) {
	function tt_register_meta_box_floor_plan() {
		add_meta_box( 'estate_property_floor_plan', __( 'Property Floor Plans', 'tt' ), 'tt_property_floor_plan_cb', 'property', 'advanced', 'high', null );
	}
}
add_action( 'add_meta_boxes_property', 'tt_register_meta_box_floor_plan' );

if ( !function_exists('tt_property_floor_plan_cb') ) {
	function tt_property_floor_plan_cb( $post ) {
		
		$prefix = 'estate_';
		
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'tt_meta_box_floor_plan', 'tt_meta_box_floor_plan_nonce' );
		 
		$floor_plan_title = get_post_meta( $post->ID, "{$prefix}floor_plan_title", true );
		$floor_plan_price = get_post_meta( $post->ID, "{$prefix}floor_plan_price", true );
		$floor_plan_size = get_post_meta( $post->ID, "{$prefix}floor_plan_size", true );
		$floor_plan_rooms = get_post_meta( $post->ID, "{$prefix}floor_plan_rooms", true );
		$floor_plan_bedrooms = get_post_meta( $post->ID, "{$prefix}floor_plan_bedrooms", true );
		$floor_plan_bathrooms = get_post_meta( $post->ID, "{$prefix}floor_plan_bathrooms", true );
		$floor_plan_description = get_post_meta( $post->ID, "{$prefix}floor_plan_description", true );
		$floor_plan_image = get_post_meta( $post->ID, "{$prefix}floor_plan_image", true );
		
		$count = count( $floor_plan_image );
		
		echo '<div id="floor-plan-container">';	
		
		$i = 0;
		if ( empty( $floor_plan_image ) ) {
			
			print '
			<div class="floor-plan-item">
				
				<div>
					<label for="floor_plan_title">' . __( 'Floor Plan Title', 'tt' ) . '</label>
					<input type="text" id="' . $prefix . 'floor_plan_title" name="' . $prefix . 'floor_plan_title[]" value="" />
				</div>
				
				<div>
					<label for="floor_plan_price">' . __( 'Floor Plan Price', 'tt' ) . '</label>
					<input type="number" id="' . $prefix . 'floor_plan_price" name="' . $prefix . 'floor_plan_price[]" value="" step="0.01" min="0" />
				</div>
				
				<div>
					<label for="floor_plan_size">' . __( 'Floor Plan Size', 'tt' ) . '</label>
					<input type="number" id="' . $prefix . 'floor_plan_size" name="' . $prefix . 'floor_plan_size[]" value="" step="0.5" min="0" />
				</div>
				
				<div>
					<label for="floor_plan_rooms">' . __( 'Floor Plan Rooms', 'tt' ) . '</label>
					<input type="number" id="' . $prefix . 'floor_plan_rooms" name="' . $prefix . 'floor_plan_rooms[]" value="" step="0.5" min="0" />
				</div>
				
				<div>
					<label for="floor_plan_bedrooms">' . __( 'Floor Plan Bedrooms', 'tt' ) . '</label>
					<input type="number" id="' . $prefix . 'floor_plan_bedrooms" name="' . $prefix . 'floor_plan_bedrooms[]" value="" step="0.5" min="0" />
				</div>
				
				<div>
					<label for="floor_plan_bathrooms">' . __( 'Floor Plan Bathrooms', 'tt' ) . '</label>
					<input type="number" id="' . $prefix . 'floor_plan_bathrooms" name="' . $prefix . 'floor_plan_bathrooms[]" value="" step="0.5" min="0" />
				</div>
				
				<div>
					<label for="floor_plan_image">' . __( 'Floor Plan Image', 'tt' ) . ' (' .__( 'required', 'tt' ) . ')</label>
					<input type="hidden" id="floor_plan_image" name="' . $prefix . 'floor_plan_image[]" value="" />
					<img id="floor_plan_image_thumbnail" src="" /><br />
					<a id="floor_plan_image_button" class="upload button" name="floor_plan_image_button">' . __( 'Upload Image', 'tt' ) . '</a><br />
				</div>
				
				<div>
					<label for="floor_plan_description">' . __( 'Floor Plan Description', 'tt' ) . '</label>
					<textarea name="' . $prefix . 'floor_plan_description[]" rows="5"></textarea>
				</div>
				
				<div style="clear: both">
					<a class="delete-floor-plan button">' . __( 'Delete Plan','tt' ) . '</a>
				</div>
				
			</div>
			';
			
		}
		else {
			foreach ( $floor_plan_image as $image => $value ) {
				
				print '
				<div class="floor-plan-item">
					
					<div>
						<label for="floor_plan_title">' . __( 'Floor Plan Title', 'tt' ) . '</label>
						<input type="text" id="' . $prefix . 'floor_plan_title" name="' . $prefix . 'floor_plan_title[]" value="' . $floor_plan_title[$i] . '" />
					</div>
					
					<div>
						<label for="floor_plan_price">' . __( 'Floor Plan Price', 'tt' ) . '</label>
						<input type="number" id="' . $prefix . 'floor_plan_price" name="' . $prefix . 'floor_plan_price[]" value="' . $floor_plan_price[$i] . '" step="0.01" min="0" />
					</div>
					
					<div>
						<label for="floor_plan_size">' . __( 'Floor Plan Size', 'tt' ) . '</label>
						<input type="number" id="' . $prefix . 'floor_plan_size" name="' . $prefix . 'floor_plan_size[]" value="' . $floor_plan_size[$i] . '" step="0.5" min="0" />
					</div>
					
					<div>
						<label for="floor_plan_rooms">' . __( 'Floor Plan Rooms', 'tt' ) . '</label>
						<input type="number" id="' . $prefix . 'floor_plan_rooms" name="' . $prefix . 'floor_plan_rooms[]" value="' . $floor_plan_rooms[$i] . '" step="0.5" min="0" />
					</div>
					
					<div>
						<label for="floor_plan_bedrooms">' . __( 'Floor Plan Bedrooms', 'tt' ) . '</label>
						<input type="number" id="' . $prefix . 'floor_plan_bedrooms" name="' . $prefix . 'floor_plan_bedrooms[]" value="' . $floor_plan_bedrooms[$i] . '" step="0.5" min="0" />
					</div>
					
					<div>
						<label for="floor_plan_bathrooms">' . __( 'Floor Plan Bathrooms', 'tt' ) . '</label>
						<input type="number" id="' . $prefix . 'floor_plan_bathrooms" name="' . $prefix . 'floor_plan_bathrooms[]" value="' . $floor_plan_bathrooms[$i] . '" step="0.5" min="0" />
					</div>
					
					<div>
						<label for="floor_plan_image">' . __( 'Floor Plan Image', 'tt' ) . ' (' .__( 'required', 'tt' ) . ')</label>
						<input type="hidden" id="id_floor_plan_image" name="' . $prefix . 'floor_plan_image[]" value="' . $floor_plan_image[$i] . '" />
						<img id="thumbnail_floor_plan_image" src="' . wp_get_attachment_thumb_url( $floor_plan_image[$i] ) . '" width="150" /><br />
						<a id="button_floor_plan_image" class="upload button" name="floor_plan_image_button">' . __( 'Upload Image', 'tt' ) . '</a><br />
					</div>
					
					<div>
						<label for="floor_plan_description">' . __( 'Floor Plan Description', 'tt' ) . '</label>
						<textarea name="' . $prefix . 'floor_plan_description[]" rows="5">' . $floor_plan_description[$i] . '</textarea>
					</div>
					
					<div style="clear: both">
						<a class="delete-floor-plan button">' . __( 'Delete Plan','tt' ) . '</a>
					</div>
					
				</div>
				';
				
				$i++;
	
			}
		}
		
		echo '</div>';
		
		_e( '<a class="add-floor-plan button button-primary">' . __( 'Add New Plan','tt' ) . '</a>', 'tt' );
		
	}
}

if ( !function_exists('tt_save_meta_box_floor_plan') ) {
	function tt_save_meta_box_floor_plan( $post_id ) {
		
		$prefix = 'estate_';
		
		/*
		 * We need to verify this came from our screen and with proper authorization,
		 * because the save_post action can be triggered at other times.
		 */
	
		// Check if our nonce is set.
		if ( ! isset( $_POST['tt_meta_box_floor_plan_nonce'] ) ) {
			return;
		}
	
		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['tt_meta_box_floor_plan_nonce'], 'tt_meta_box_floor_plan' ) ) {
			return;
		}
	
		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
	
		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
	
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}
	
		} else {
	
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}
		
		/* OK, it's safe for us to save the data now. */
		
		if ( isset( $_POST["{$prefix}floor_plan_title"] ) ) {
			$floor_plan_title = $_POST["{$prefix}floor_plan_title"];
			foreach ( $floor_plan_title as $floor_plan_title ) {
				if ( ! isset( $floor_plan_title ) ) {
					return;
				}
				$floor_plan_title_array[] = sanitize_text_field( $floor_plan_title );
			}	
			update_post_meta( $post_id, "{$prefix}floor_plan_title", $floor_plan_title_array );
		}
		
		if ( isset( $_POST["{$prefix}floor_plan_price"] ) ) {
			$floor_plan_price = $_POST["{$prefix}floor_plan_price"];
			foreach ( $floor_plan_price as $floor_plan_price ) {
				if ( ! isset( $floor_plan_price ) ) {
					return;
				}
				$floor_plan_price_array[] = sanitize_text_field( $floor_plan_price );
			}	
			update_post_meta( $post_id, "{$prefix}floor_plan_price", $floor_plan_price_array );
		}
		
		if ( isset( $_POST["{$prefix}floor_plan_size"] ) ) {
			$floor_plan_size = $_POST["{$prefix}floor_plan_size"];
			foreach ( $floor_plan_size as $floor_plan_size ) {
				if ( ! isset( $floor_plan_size ) ) {
					return;
				}
				$floor_plan_size_array[] = sanitize_text_field( $floor_plan_size );
			}	
			update_post_meta( $post_id, "{$prefix}floor_plan_size", $floor_plan_size_array );
		}
		
		if ( isset( $_POST["{$prefix}floor_plan_rooms"] ) ) {
			$floor_plan_rooms = $_POST["{$prefix}floor_plan_rooms"];
			foreach ( $floor_plan_rooms as $floor_plan_rooms ) {
				if ( ! isset( $floor_plan_rooms ) ) {
					return;
				}
				$floor_plan_rooms_array[] = sanitize_text_field( $floor_plan_rooms );
			}	
			update_post_meta( $post_id, "{$prefix}floor_plan_rooms", $floor_plan_rooms_array );
		}
		
		if ( isset( $_POST["{$prefix}floor_plan_bedrooms"] ) ) {
			$floor_plan_bedrooms = $_POST["{$prefix}floor_plan_bedrooms"];
			foreach ( $floor_plan_bedrooms as $floor_plan_bedrooms ) {
				if ( ! isset( $floor_plan_bedrooms ) ) {
					return;
				}
				$floor_plan_bedrooms_array[] = sanitize_text_field( $floor_plan_bedrooms );
			}	
			update_post_meta( $post_id, "{$prefix}floor_plan_bedrooms", $floor_plan_bedrooms_array );
		}
		
		if ( isset( $_POST["{$prefix}floor_plan_bathrooms"] ) ) {
			$floor_plan_bathrooms = $_POST["{$prefix}floor_plan_bathrooms"];
			foreach ( $floor_plan_bathrooms as $floor_plan_bathrooms ) {
				if ( ! isset( $floor_plan_bathrooms ) ) {
					return;
				}
				$floor_plan_bathrooms_array[] = sanitize_text_field( $floor_plan_bathrooms );
			}	
			update_post_meta( $post_id, "{$prefix}floor_plan_bathrooms", $floor_plan_bathrooms_array );
		}
		
		if ( isset( $_POST["{$prefix}floor_plan_description"] ) ) {
			$floor_plan_description = $_POST["{$prefix}floor_plan_description"];	
			foreach ( $floor_plan_description as $floor_plan_description ) {
				if ( ! isset( $floor_plan_description ) ) {
					return;
				}
				$floor_plan_description_array[] = sanitize_text_field( $floor_plan_description );
			}	
			update_post_meta( $post_id, "{$prefix}floor_plan_description", $floor_plan_description_array );
		}
		
		if ( isset( $_POST["{$prefix}floor_plan_image"] ) ) {
			$floor_plan_image = $_POST["{$prefix}floor_plan_image"];
			foreach ( $floor_plan_image as $floor_plan_image ) {
				if ( ! isset( $floor_plan_image ) ) {
					return;
				}
				$floor_plan_image_array[] = sanitize_text_field( $floor_plan_image );
			}	
			update_post_meta( $post_id, "{$prefix}floor_plan_image", $floor_plan_image_array );
		}
		else {
			update_post_meta( $post_id, "{$prefix}floor_plan_image", '' );
		}
		
	}
}
add_action( 'save_post', 'tt_save_meta_box_floor_plan' );
