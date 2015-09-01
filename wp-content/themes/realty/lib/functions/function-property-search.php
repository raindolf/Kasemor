<?php
/* PROPERTY SEARCH QUERY ARGUMENTS
============================== */

function tt_property_search_args($search_results_args) {

	global $realty_theme_option; 
	
	/*if(isset($_POST['pagers'])){
	  $string_array=explode('/',$_POST['pagers']);
	  print_r($string_array);	  
	  $add_pagination_number=$string_array[5];
	}*/
	//print_r($get_array);
	$search_results_args['post_type'] = 'property';
	$search_results_args['post_status'] = 'publish';
	$search_results_args['paged'] = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
	/*if(isset($add_pagination_number)){
		$search_results_args['paged'] = $add_pagination_number;
	}*/
	$search_results_per_page = $realty_theme_option['search-results-per-page'];
	
	// Search Results Per Page: Check for Theme Option
	if ( $search_results_per_page ) {
		$search_results_args['posts_per_page'] = $search_results_per_page;
	}
	else {
		$search_results_args['posts_per_page'] = 10;
	}
	
	// Search Results Order
	if( !empty( $_GET[ 'orderby' ] ) ) {
		
		$orderby = $_GET[ 'orderby' ];
		// By Date (Newest First)
		if ( $orderby == 'date-new' ) {
			$search_results_args['orderby'] = 'date';
			$search_results_args['order'] = 'DESC';
		}
		
		// By Date (Oldest First)
		if ( $orderby == 'date-old' ) {
			$search_results_args['orderby'] = 'date';
			$search_results_args['order'] = 'ASC';
		}
		
		// By Price (Highest First)
		if ( $orderby == 'price-high' ) {
			$search_results_args['meta_key'] = 'estate_property_price';
			$search_results_args['orderby'] = 'meta_value_num';
			$search_results_args['order'] = 'DESC';
		}
		
		// By Price (Lowest First)
		if ( $orderby == 'price-low' ) {
			$search_results_args['meta_key'] = 'estate_property_price';
			$search_results_args['orderby'] = 'meta_value_num';
			$search_results_args['order'] = 'ASC';
		}
		
		// Random
		if ( $orderby == 'random' ) {
			$search_results_args['orderby'] = 'rand';
		}
		
	}
	else {
		$orderby = '';
	}
	
	/* META & TAX QUERIES: 
	============================== */
	
	$meta_query = array();
	$tax_query = array();
	
	$i = 0;
	
	foreach ( $_GET as $search_key => $search_value ) { 
	
		if ( $search_key == "orderby" || $search_key == "pageid" ) {
			break;
		}
		
		// Check If Key Has A Value
		if ( ( !empty( $search_value ) || $search_key == "price_range_min" ) && $search_key != "orderby" && $search_key != "pageid" ) {
			
			// Search Form Mini
			if ( isset ( $_GET['form'] ) && $_GET['form'] == "mini" ) {
				$search_parameters = $realty_theme_option['property-search-mini-parameter'];
				$search_fields = $realty_theme_option['property-search-mini-field'];
				$search_position = array_search( $search_key, $search_parameters );
				$search_compare = $realty_theme_option['property-search-mini-compare'][$search_position];
				$search_field = $search_fields[$search_position];	
			}
			// Default Search Form
			else {
				$search_parameters = $realty_theme_option['property-search-parameter'];
				$search_fields = $realty_theme_option['property-search-field'];
				$search_position = array_search( $search_key, $search_parameters );
				$search_compare = $realty_theme_option['property-search-compare'][$search_position];
				$search_field = $search_fields[$search_position];
			}
			
			switch ( $search_compare ) {				
				case 'greater_than' : case 'greather_than' 		: $search_compare = '>='; break; // Do NOT delete "greather_than" typo
				case 'less_than' 															: $search_compare = '<='; break;
				case 'like' 																	: $search_compare = 'LIKE'; break;	
				default 	 																		: $search_compare = '='; break;			
			}
			
			// Default Fields
			$default_search_fields_array = array( 
				'estate_search_by_keyword',
				'estate_property_id', 
				'estate_property_location', 
				'estate_property_type', 
				'estate_property_status', 
				'estate_property_price', 
				'estate_property_pricerange',
				'estate_property_size',
				'estate_property_rooms',
				'estate_property_bedrooms',
				'estate_property_bathrooms',
				'estate_property_garages',
				'estate_property_available_from'
			);

			// Default Fields
			if ( in_array( $search_fields[$i], $default_search_fields_array ) ) {
				
				switch ( $search_fields[$i] ) {
				
					// Keyword Search
					case 'estate_search_by_keyword' : 
					$search_results_args['s'] = $search_value;
					break;
					
					case 'estate_property_id' : 
					if ( $realty_theme_option['property-id-type'] == "post_id" ) {
						$search_results_args['p'] = $search_value; // Post ID = Default Property ID	
					}
					else {
						$meta_query[] = array(
							'key' 			=> 'estate_property_id',
							'value' 		=> $search_value
						);
					}								
					break;
	
					case 'estate_property_price' :
					$meta_query[] = array(
						'key' 			=> 'estate_property_price',
						'value' 		=> $search_value,
						'type' 			=> 'NUMERIC',
				    'compare' 	=> $search_compare
					);
					break;
					
					case 'estate_property_pricerange' :
					$meta_query[] = array(
						'key' 			=> 'estate_property_price',
						'value' 		=> array( $_GET['price_range_min'], $_GET['price_range_max'] ),
						'type' 			=> 'NUMERIC',
				    'compare' 	=> 'BETWEEN'
					);				
					break;
					
					case 'estate_property_size' :
					$meta_query[] = array(
						'key' 			=> 'estate_property_size',
						'value' 		=> $search_value,
						'type' 			=> 'NUMERIC',
				    'compare' 	=> $search_compare
					);				
					break;
					
					case 'estate_property_rooms' : 
					$meta_query[] = array(
						'key' 			=> 'estate_property_rooms',
						'value' 		=> $search_value,
						'type' 			=> 'NUMERIC',
				    'compare' 	=> $search_compare
					);				
					break;
					
					case 'estate_property_bedrooms' : 
					$meta_query[] = array(
						'key' 			=> 'estate_property_bedrooms',
						'value' 		=> $search_value,
						'type' 			=> 'NUMERIC',
				    'compare' 	=> $search_compare
					);				
					break;
					
					case 'estate_property_bathrooms' : 
					$meta_query[] = array(
						'key' 			=> 'estate_property_bathrooms',
						'value' 		=> $search_value,
						'type' 			=> 'NUMERIC',
				    'compare' 	=> $search_compare
					);				
					break;
					
					case 'estate_property_garages' : 
					$meta_query[] = array(
						'key' 			=> 'estate_property_garages',
						'value' 		=> $search_value,
						'type' 			=> 'NUMERIC',
				    'compare' 	=> $search_compare
					);				
					break;
					
					case 'estate_property_available_from' : 
					$meta_query[] = array(
						'key' 			=> 'estate_property_available_from',
						'value' 		=> $search_value,
						'type' 			=> 'DATE',
				    'compare' 	=> $search_compare
					);				
					break;
		
					case 'estate_property_location' : 
					if ( $search_value != "all" ) {
						$tax_query[] = array(
							'taxonomy' 	=> 'property-location',
							'field' 		=> 'slug',
							'terms'			=> $search_value
						);
					}
					break;
					
					case 'estate_property_type' : 
					if ( $search_value != "all" ) {
						$tax_query[] = array(
							'taxonomy' 	=> 'property-type',
							'field' 		=> 'slug',
							'terms'			=> $search_value
						);
					}
					break;
					
					case 'estate_property_status' : 
					if ( $search_value != "all" ) {
						$tax_query[] = array(
							'taxonomy' 	=> 'property-status',
							'field' 		=> 'slug',
							'terms'			=> $search_value
						);
					}
					break;
					
					case 'feature' : 
					if ( $search_value != "all" ) {
						$tax_query[] = array(
							'taxonomy' 	=> 'property-features',
							'field' 		=> 'slug',
							'terms'			=> $search_value
						);
					}
					break;
	
				} // switch

			} // if (Default Fields)
			
			// Advanced Custom Fields (ACF plugin)
			else if ( tt_acf_active() && in_array( $search_fields[$i], tt_acf_fields_name( tt_acf_group_id_property() ) ) ) {
				
				// Get Field Type	
				$acf_field_position = array_search( $search_fields[$i], tt_acf_fields_name( tt_acf_group_id_property() ) );
				$acf_field_type_key = tt_acf_fields_type( tt_acf_group_id_property() );
				$acf_field_type = $acf_field_type_key[$acf_field_position];
				
				$type = '';
				
				switch ( $acf_field_type ) {		
					case ( 'text' ) : $type = 'CHAR'; break;
					case ( 'number' ) : $type = 'NUMERIC'; break;
					case ( 'date_picker' ) : $type = 'DATE'; break;		
				}
			
				// ACF Type: Checkbox & Radio Buttons
				if ( $acf_field_type == 'checkbox' ) {
					$meta_query[] = array(
						'key' 			=> $search_key,
						'value' 		=> $search_value,
						'compare' 	=> 'LIKE'
					);
				}
				
				else if ( $acf_field_type == 'text' || $acf_field_type == 'number' || $acf_field_type == 'date_picker' ) {		
					$meta_query[] = array(
						'key' 			=> $search_fields[$i],
						'value' 		=> $search_value,
						'type' 			=> $type,
			    	'compare' 	=> $search_compare
					);				
				}
		
				// Type not supported, no comparison needed
				else {
					$meta_query[] = array(
						'key' 			=> $search_key,
						'value' 		=> $search_value
					);
				}
			
			} // endif ACF;
			
		} // endif !empty($search_value)
		
		// Dont increase $i for price range, as we are using two parameters (min & max) already
		if ( $search_key != "price_range_min" ) {
			$i++;
		}
		
	} // end foreach()
	
	// Count meta & tax querie, then set relation for search query
	$meta_count = count( $meta_query );
	
	if ( $meta_count > 1 ) {
	  $meta_query['relation'] = 'AND';
	}

	if ( $meta_count > 0 ) {
		$search_results_args['meta_query'] = $meta_query;
	}
	
	// Count taxonomy queries + set their relation for search query
	$tax_count = count( $tax_query );
	
	if ( $tax_count > 1 ) {
		$tax_query['relation'] = 'AND';
	}
	
	if ( $tax_count > 0 ) {
		$search_results_args['tax_query'] = $tax_query;
	}
	
	return $search_results_args;

}
add_filter( 'property_search_args', 'tt_property_search_args' );


/* AJAX - Search
============================== */
function tt_ajax_search() {
	
	// Build Property Search Query
	$search_results_args = array();
	$search_results_args = apply_filters( 'property_search_args', $search_results_args );
	
	// Query Only Property Owners' Properties On "My Properties" Page Template (Admins Can View Every Property)
	$page_template = get_page_template_slug($_GET['pageid']);
	if ( $page_template == 'template-property-submit-listing.php' && !current_user_can('manage_options') ) {
		global $current_user;
		get_currentuserinfo();
		$search_results_args['author'] = $current_user->ID;
	}
	
	$count_results = "0";
	
	$query_search_results = new WP_Query( $search_results_args );
	
	if ( !isset( $orderby ) || empty( $orderby ) ) {
		$orderby = "date-new";
	}
	
	if ( $query_search_results->have_posts() ) :
	
	$count_results = $query_search_results->found_posts;
	// template-property-search.php
    get_template_part( 'lib/inc/template/property', 'comparison' ); 
	?>
	<ul class="row list-unstyled">	
		<?php
		while ( $query_search_results->have_posts() ) : $query_search_results->the_post();
		global $realty_theme_option;
		$columns = $realty_theme_option['property-listing-columns'];
		if ( empty($columns) ) {
			$columns = "col-md-6";
		}
		?>
		<li class="<?php echo $columns; ?>">
			<?php get_template_part( 'lib/inc/template/property', 'item' );	?>
		</li>
		<?php endwhile; ?>
		
	</ul>
	<?php wp_reset_query(); ?>

	<div id="pagination" class="pagination-true">
	<?php
	// Built Property Pagination
	$big = 999999999; // need an unlikely integer
	$original_request_uri = $_SERVER['REQUEST_URI'];
	$base = $_GET['base'];
	$_SERVER['REQUEST_URI'] = $base;
	if($realty_theme_option['enable-rtl-support']){
		$rtl_pagination_right_nav='<i class="btn btn-default fa fa-angle-left"></i>';
		$rtl_pagination_left_nav='<i class="btn btn-default fa fa-angle-right"></i>';
	}else{
		$rtl_pagination_right_nav='<i class="btn btn-default fa fa-angle-right"></i>';
		$rtl_pagination_left_nav='<i class="btn btn-default fa fa-angle-left"></i>';
		
	}
	//echo $rtl_pagination_right_nav;
	
	echo paginate_links( array(
					//'base' 				=> trim(str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) )),
					'total' 			=> $query_search_results->max_num_pages,
					'show_all'		=> true,
					'type'				=> 'list',
					'current'     => $search_results_args['paged'],
					'prev_next'  => true,
					'prev_text' 	=> __( $rtl_pagination_left_nav, 'tt' ),
					'next_text' 	=> __( $rtl_pagination_right_nav, 'tt' ),
				) );
	
	$_SERVER['REQUEST_URI'] = $original_request_uri;
	?>
	</div>
	
	<?php
	else : ?>
	<p class="lead text-center text-muted"><?php _e( 'No Properties Match Your Search Criteria.', 'tt' ); ?></p>
	<?php
	endif;
	?>
	<script>
	jQuery( '.pagination-true a' ).each(function() {
        	this.href = this.href.replace(jQuery( this ).attr('href'), jQuery( this ).attr('href')+'#property-items');
	  
    });
	jQuery(function($) {
		$('.pagination-true').on('click','a', function(e){
			 e.preventDefault();
			 var link_page = $(this).attr('href');
			 
			$('#property-items').fadeOut(500, function(){
				
				$(this).load(link_page + ' #property-items', function() {
                          $(this).fadeIn(500);
						  window.history.pushState("#property-items", "Properties",link_page );
                });
				 /*jQuery.ajax({
					type: 'POST',
					url: link_page+'#property-items',//ajaxURL,
					//data: 'pagers='+encodeURIComponent(link_page)+'&action=tt_ajax_search',		
					success: function (response) {
						jQuery('#property-items').html(response);
						 jQuery('#property-items').fadeIn(500); // Show response from function tt_ajax_search()
					},
					error: function () {
					console.log( 'failed' );
					}	
		  
			       }); */
				   
	
		     });
	    
        });
    });
	jQuery('.search-results-header, #property-search-results').fadeOut(0);
	<?php 
	// No Results Found
	if ( $count_results == "0" ) { ?>
	jQuery('#map-overlay-no-results, #property-search-results').fadeIn();
	<?php }
	// Results Found
	else {
	// AJAX Refresh Property Map Markers
	$search_results_args['posts_per_page'] = -1;
	$query_search_results = new WP_Query( $search_results_args );
	
	if ( $query_search_results->have_posts() ) :
	
	$property_string = '';
	$i = 0;

	while ( $query_search_results->have_posts() ) : $query_search_results->the_post(); 
	global $post;
	$google_maps = get_post_meta( $post->ID, 'estate_property_location', true );
	
	// Check For Map Coordinates
	if ( $google_maps ) {	
		
		$coordinate = explode( ',', $google_maps );	
		$property_string .= "{ ";	
		$property_string .= 'permalink:"' . urlencode(get_permalink()) . '", ';
		$property_string .= 'title:"' . get_the_title() . '", ';
		$property_string .= 'price:"' . tt_property_price() . '", ';
		$property_string .= 'latLng: new google.maps.LatLng(' . $coordinate[0] . ', ' . $coordinate[1] . '), ';
		if ( has_post_thumbnail() ) { 
			$property_thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );
			$property_string .= 'thumbnail: "' . $property_thumbnail[0] . '"';
		}	
		else { 
			$property_string .= 'thumbnail: "//placehold.it/300x100/eee/ccc/&text=.."';
		}
		$property_string .= ' },' . "\n";
	}
	
	$i++;
	endwhile;
	wp_reset_query();
	endif;
	?>
	// Check: Do we show a map at all?
	if ( jQuery('#google-map').length > 0 ) {		
		bounds = new google.maps.LatLngBounds();
		
		initMarkers(map, [ <?php echo $property_string; ?> ]);
		markerCluster = new MarkerClusterer(map, newMarkers, markerClusterOptions);
		
		google.maps.event.addListenerOnce(map, 'bounds_changed', function(event) {
			map.fitBounds(bounds);
			if (this.getZoom() > 13) {
		    this.setZoom(13);
		  }
		});
		
		jQuery('#map-overlay-no-results').fadeOut();	
	}
	jQuery('.search-results-header, #property-search-results').fadeIn();
	jQuery('.page-title span').html(<?php echo $count_results; ?>);
	<?php } ?>
	
	</script>
	
	<?php
	
	die();
	
}
add_action('wp_ajax_tt_ajax_search', 'tt_ajax_search');
add_action('wp_ajax_nopriv_tt_ajax_search', 'tt_ajax_search');