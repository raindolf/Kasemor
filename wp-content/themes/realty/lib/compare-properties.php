<?php
/* Property Comparison - Script
============================== */
function tt_comparison_script() {
?>
	<script>
	// Check If item Already In Favorites Array
	function inArray(needle, haystack) {
    var length = haystack.length;
    for( var i = 0; i < length; i++ ) {
      if(haystack[i] == needle) return true;
    }
    return false;
	}
	
	if (!store.enabled) {
		throw new Error("<?php echo __( 'Local storage is not supported by your browser. Please disable \"Private Mode\", or upgrade to a modern browser.', 'tt' ); ?>");
  }
		
	jQuery('.container').on("click",".compare-property",function() {
		
		jQuery('#compare-properties-popup').show();
	  
	  // Check If Browser Supports LocalStorage			
		if (!store.enabled) {
	    throw new Error("<?php echo __( 'Local storage is not supported by your browser. Please disable \"Private Mode\", or upgrade to a modern browser.', 'tt' ); ?>");
	  }
	  
	  if ( store.get('comparison') ) {
			
			var getComparisonAll = store.get('comparison');
			var propertyToCompare = jQuery(this).attr('data-compare-id');
			
			// Add To Comparison, If Its Not Already In It
			if ( !inArray( propertyToCompare, getComparisonAll ) && getComparisonAll.length < 4 ) {
				getComparisonAll.push( propertyToCompare );
			}
			
			store.set( 'comparison', getComparisonAll );
			comparisonLength = getComparisonAll.length;
			
		}
		
		else {
			
			var arrayComparison = [];
			arrayComparison.push( jQuery(this).attr('data-compare-id') );				
			store.set( 'comparison', arrayComparison );
			var comparisonLength = store.get('comparison').length;
			
		}
		
		console.log( store.get('comparison') );

		// Update Comparison Popup Thumbnails
		var properties;
		properties = store.get('comparison');
						
		jQuery.ajax({		
		  type: 'GET',
		  url: ajaxURL,
		  data: {
		    'action'          :   'tt_ajax_property_comparison_thumbnails', // WP Function
		    'properties'      :   properties
		  },
		  success: function (response) {		  
		  	// If Temporary Favorites Found, Show Them
		  	if ( store.get('comparison') != "" ) {
		  		jQuery('#compare-properties-thumbnails').html(response);
		  		// Show Max. Message
		  		if ( comparisonLength == 4 ) {
						jQuery('#compare-properties-popup .alert').toggleClass('hide');
					}
		  	}
		  }
		});
	
	});		
	</script>
<?php
}
add_action( 'wp_footer', 'tt_comparison_script', 22 );


/* Property Comparison - Page Template Output
============================== */
function tt_ajax_property_comparison() {

	$property_comparison_args['post_type'] = 'property';
	$property_comparison_args['post_status'] = 'publish';
	$property_comparison_args['posts_per_page'] = '4';
	//$property_comparison_args['paged'] = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	
	if ( isset( $_GET['properties'] ) ) {
		$property_comparison_args['post__in'] = $_GET['properties'];
	}
	else {
		$property_comparison_args['post__in'] = array( '0' );
	}
	
	$query_property_comparison = new WP_Query( $property_comparison_args );
	
	if ( $query_property_comparison->have_posts() ) :
	
	$count_results = $query_property_comparison->found_posts;
	$properties = array();

	while ( $query_property_comparison->have_posts() ) : $query_property_comparison->the_post();

	
		global $post;
		
		$property_to_compare 									= array();
		$property['property_permalink']      	= get_permalink();
		$property['property_title']        		= get_the_title();
		$property['property_id']        			= get_the_ID();
		if ( has_post_thumbnail() ) { 
		$property['property_thumbnail'] 			= get_the_post_thumbnail( $post->ID, 'property-thumb' );
		}	
		else {
		$property['property_thumbnail'] 			= '<img src ="//placehold.it/600x300/eee/ccc/&text=.." />';
		}
		
		$property['property_price']        		= tt_property_price();
		
		//$property['property_features']				= get_the_term_list( $post->ID, 'property-features', '', ', ', '' );
		$property['property_features']				= get_the_terms( $post->ID, 'property-features');
		
		$property['property_type'] 						= get_the_term_list( $post->ID, 'property-type', '', ', ', '' );
		$property['property_status'] 					= get_the_term_list( $post->ID, 'property-status', '', ', ', '' );
		$property['property_location'] 				= get_the_term_list( $post->ID, 'property-location', '', ' <small><span class="text-muted">></span></small> ', '' );
		
		$property['property_address'] 				= get_post_meta( $post->ID, 'estate_property_address', true );
		$property['property_size'] 						= get_post_meta( $post->ID, 'estate_property_size', true );
		$property['property_size_unit']				= get_post_meta( $post->ID, 'estate_property_size_unit', true );
		$property['property_rooms']						= get_post_meta( $post->ID, 'estate_property_rooms', true );
		$property['property_bedrooms']				= get_post_meta( $post->ID, 'estate_property_bedrooms', true );
		$property['property_bathrooms']				= get_post_meta( $post->ID, 'estate_property_bathrooms', true );
		$property['property_garages']					= get_post_meta( $post->ID, 'estate_property_garages', true );
		
		$properties[] = $property;			

	endwhile; 
	
	// Comparison Main
	echo '<div class="comparison-main">';
	echo '<div class="comparison-row">';
	
	echo '<div class="comparison-header"></div>';
	
	for ( $i = 0; $i < $count_results; $i++ ) {
	
		echo '<div class="comparison-data primary-tooltips">';
		echo '<i class="fa fa-plus compare-property" data-compare-id="' . $properties[$i]['property_id'] . '" data-toggle="tooltip" title="' . __( 'Remove', 'tt' ) . '">&times;</i>';
		echo '<a href="' . $properties[$i]['property_permalink'] . '">';
		echo $properties[$i]['property_thumbnail'];
		echo '<h6 class="property-title">' . $properties[$i]['property_title'] . '</h6>';
		echo '</a>';
		echo '<div class="property-address">' . $properties[$i]['property_address'] . '</div>';
		echo '</div>';
	}
	
	echo '</div>';
	echo '</div>';
	
	// Property Attributes
	$property_attributes = array(
		'property_price'					=> __( 'Price', 'tt' ),
		'property_type'						=> __( 'Type', 'tt' ),
		'property_status'					=> __( 'Status', 'tt' ),
		'property_location'				=> __( 'Location', 'tt' ),
		'property_size'						=> __( 'Size', 'tt' ),
		'property_rooms'					=> __( 'Rooms', 'tt' ),
		'property_bedrooms'				=> __( 'Bedrooms', 'tt' ),
		'property_bathrooms'			=> __( 'Bathrooms', 'tt' ),
		'property_garages'				=> __( 'Garages', 'tt' ),
		'property_features'				=> __( 'Property Features', 'tt' ),
	);
	
	echo '<div class="comparison-attributes">';
	
	foreach ( $property_attributes as $attribute_key => $attribute_value ) {
		
		if ( $attribute_key == "property_features" ) {	
			for ( $i = 0; $i < $count_results; $i++ ) {
				
				// Get All Existing Property Features
				$all_property_features = get_terms( 'property-features', array( 'hide_empty=0' ) );
				
				foreach ( $all_property_features as $single_property_feature ) {
					echo '<div class="comparison-row">';
					echo '<div class="comparison-header">' . $single_property_feature->name . '</div>';				
					
					for ( $i = 0; $i < $count_results; $i++ ) {
						echo '<div class="comparison-data">';
						$current_property_features = $properties[$i][$attribute_key];
						
						// Has Current Property The Currently Queried Feature?
						if ( $current_property_features ) {
							foreach ( $current_property_features as $current_property_feature ) {
								if ( $single_property_feature->term_id == $current_property_feature->term_id ) {
									$has_feature = true;
									break;
								}
								else {
									$has_feature = false;
								}
							}
						}
						else {
							$has_feature = false;
						}
						
						if ( $has_feature ) {
							echo '<i class="fa fa-check text-success"></i>';
						}
						else {
							echo '&nbsp;-';
						}
						
						echo '</div>';
					}				
					echo '</div>';
				}

				
			}
		}
		
		else {
			echo '<div class="comparison-row">';
			echo '<div class="comparison-header">' . $attribute_value . '</div>';
			for ( $i = 0; $i < $count_results; $i++ ) {
				echo '<div class="comparison-data">';
				echo $properties[$i][$attribute_key];
				if ( $attribute_key == "property_size" ) {
				 echo ' ' . $properties[$i]['property_size_unit'];	
				}
				echo '</div>';
			}
			echo '</div>';
		}		
	}
	
	echo '</div>';

	wp_reset_query();
	
	endif;
	
	die();
	
}
add_action('wp_ajax_tt_ajax_property_comparison', 'tt_ajax_property_comparison');
add_action('wp_ajax_nopriv_tt_ajax_property_comparison', 'tt_ajax_property_comparison');


/* Property Comparison Thumbnails
============================== */
function tt_ajax_property_comparison_thumbnails() {

	$property_comparison_args['post_type'] = 'property';
	$property_comparison_args['post_status'] = 'publish';
	$property_comparison_args['posts_per_page'] = '4';
	
	if ( isset( $_GET['properties'] ) ) {
		$property_comparison_args['post__in'] = $_GET['properties'];
	}
	else {
		$property_comparison_args['post__in'] = array( '0' );
	}
	
	$query_property_comparison = new WP_Query( $property_comparison_args );
	
	if ( $query_property_comparison->have_posts() ) :
	
	$count_results = $query_property_comparison->found_posts;
	// template-property-search.php
	?>
	<ul class="row list-unstyled">	
		<?php while ( $query_property_comparison->have_posts() ) : $query_property_comparison->the_post(); ?>
		<li class="col-sm-4 col-md-2">
			<?php
			if ( has_post_thumbnail() ) { 
				the_post_thumbnail( 'property-thumb' );
			}	
			else {
				echo '<img src ="//placehold.it/600x300/eee/ccc/&text=.." />';
			}
			?>
		</li>
		<?php endwhile; ?>
	</ul>
	<?php 
	wp_reset_query();
	
	endif;
	
	die();
	
}
add_action('wp_ajax_tt_ajax_property_comparison_thumbnails', 'tt_ajax_property_comparison_thumbnails');
add_action('wp_ajax_nopriv_tt_ajax_property_comparison_thumbnails', 'tt_ajax_property_comparison_thumbnails');