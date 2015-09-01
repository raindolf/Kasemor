<?php
/* CUSTOM POST TYPE: PROPERTY
============================== */

function tt_register_custom_post_type_property() {

  $labels = array(
    'name' 									=> __( 'Properties','tt' ),
    'singular_name' 				=> __( 'Property','tt' ),
    'add_new' 							=> __( 'Add New','tt' ),
    'add_new_item' 					=> __( 'Add New Property','tt' ),
    'edit_item' 						=> __( 'Edit Property','tt' ),
    'new_item' 							=> __( 'New Property','tt' ),
    'view_item' 						=> __( 'View Property','tt' ),
    'search_items' 					=> __( 'Search Property','tt' ),
    'not_found' 						=> __( 'No Property found.','tt' ),
    'not_found_in_trash' 		=> __( 'No Property found in Trash.','tt' )
  );

  $args = array(
	  'labels' 								=> $labels,
	  'public' 								=> true,
	  'show_ui' 							=> true,
	  'show_in_admin_bar' 		=> true,
	  'menu_position' 				=> 20,
	  'menu_icon' 						=> 'dashicons-admin-home',
	  'publicly_queryable' 		=> true,
	  'query_var' 						=> true,
	  'rewrite' 							=> true,
	  'hierarchical' 					=> true,
	  'supports' 							=> array( 'title', 'editor', 'thumbnail', 'author', 'page-attributes', 'comments' ),
	  'rewrite' 							=> array( 'slug' => __( 'property', 'tt' ) )
  );

  register_post_type( 'property', $args );

}
add_action( 'init', 'tt_register_custom_post_type_property' );


/* REGISTER PROPERTY TAXONOMIES
============================== */

function tt_register_taxonomy_property_location() {
	register_taxonomy( 'property-location', 'property', array(  
	    'labels' 						=> array(
	    	'name' 												=> __( 'Property Location', 'tt' ),
	    	'singular_name' 							=> __( 'Property Location', 'tt' ),
	    	'search_items' 								=> __( 'Search Property Location', 'tt' ),
	    	'popular_items' 							=> __( 'Popular Property Location', 'tt' ),
	    	'all_items' 									=> __( 'All Property Location', 'tt' ),
	    	'edit_item' 									=> __( 'Edit Property Location', 'tt' ),
	    	'update_item' 								=> __( 'Update Property Location', 'tt' ),
	    	'add_new_item' 								=> __( 'Add New Property Location', 'tt' ),
	    	'new_item_name' 							=> __( 'New Property Location Name', 'tt' ),
	    	'separate_items_with_commas' 	=> __( 'Separate Property Location With Commas', 'tt' ),
	    	'add_or_remove_items' 				=> __( 'Add or Remove Property Location', 'tt' ),
	    	'choose_from_most_used' 			=> __( 'Choose From Most Used Property Location', 'tt' ),  
	    	'parent' 											=> __( 'Parent Property Location', 'tt' )      	
	    	),
	    'hierarchical'			=> true,
	    'query_var' 				=> true,  
	    'rewrite' 					=> array( 'slug' => __( 'property-location', 'tt' ) ),
	    'show_ui'           => true, // Whether to generate a default UI for managing this taxonomy
			'show_admin_column' => true, // Whether to allow automatic creation of taxonomy columns on associated post-Locations table
		)  
	);
}
add_action( 'init', 'tt_register_taxonomy_property_location', 0 );

function tt_register_taxonomy_property_status() {
	register_taxonomy( 'property-status', 'property', array(  
	    'labels' 						=> array(
	    	'name' 												=> __( 'Property Status', 'tt' ),
	    	'singular_name' 							=> __( 'Property Status', 'tt' ),
	    	'search_items' 								=> __( 'Search Property Status', 'tt' ),
	    	'popular_items' 							=> __( 'Popular Property Status', 'tt' ),
	    	'all_items' 									=> __( 'All Property Status', 'tt' ),
	    	'edit_item' 									=> __( 'Edit Property Status', 'tt' ),
	    	'update_item' 								=> __( 'Update Property Status', 'tt' ),
	    	'add_new_item' 								=> __( 'Add New Property Status', 'tt' ),
	    	'new_item_name' 							=> __( 'New Property Status Name', 'tt' ),
	    	'separate_items_with_commas' 	=> __( 'Separate Property Status With Commas', 'tt' ),
	    	'add_or_remove_items' 				=> __( 'Add or Remove Property Status', 'tt' ),
	    	'choose_from_most_used' 			=> __( 'Choose From Most Used Property Status', 'tt' ),  
	    	'parent' 											=> __( 'Parent Property Status', 'tt' )      	
	    	),
	    'hierarchical'			=> true,
	    'query_var' 				=> true,  
	    'rewrite' 					=> array( 'slug' => __( 'property-status', 'tt' ) ),
	    'show_ui'           => true, // Whether to generate a default UI for managing this taxonomy
			'show_admin_column' => true, // Whether to allow automatic creation of taxonomy columns on associated post-Statuss table
		)  
	);
}
add_action( 'init', 'tt_register_taxonomy_property_status', 0 );

function tt_register_taxonomy_property_type() {
	register_taxonomy( 'property-type', 'property', array(  
	    'labels' 						=> array(
	    	'name' 												=> __( 'Property Type', 'tt' ),
	    	'singular_name' 							=> __( 'Property Type', 'tt' ),
	    	'search_items' 								=> __( 'Search Property Type', 'tt' ),
	    	'popular_items' 							=> __( 'Popular Property Type', 'tt' ),
	    	'all_items' 									=> __( 'All Property Type', 'tt' ),
	    	'edit_item' 									=> __( 'Edit Property Type', 'tt' ),
	    	'update_item' 								=> __( 'Update Property Type', 'tt' ),
	    	'add_new_item' 								=> __( 'Add New Property Type', 'tt' ),
	    	'new_item_name' 							=> __( 'New Property Type Name', 'tt' ),
	    	'separate_items_with_commas' 	=> __( 'Separate Property Type With Commas', 'tt' ),
	    	'add_or_remove_items' 				=> __( 'Add or Remove Property Type', 'tt' ),
	    	'choose_from_most_used' 			=> __( 'Choose From Most Used Property Type', 'tt' ),  
	    	'parent' 											=> __( 'Parent Property Type', 'tt' )      	
	    	),
	    'hierarchical'			=> true,
	    'query_var' 				=> true,  
	    'rewrite' 					=> array( 'slug' => __( 'property-type', 'tt' ) ),
	    'show_ui'           => true, // Whether to generate a default UI for managing this taxonomy
			'show_admin_column' => true, // Whether to allow automatic creation of taxonomy columns on associated post-types table
		)  
	);
}
add_action( 'init', 'tt_register_taxonomy_property_type', 0 );

function tt_register_taxonomy_property_features() {
	register_taxonomy( 'property-features', 'property', array(  
	    'labels' 						=> array(
	    	'name' 												=> __( 'Property Features', 'tt' ),
	    	'singular_name' 							=> __( 'Property Features', 'tt' ),
	    	'search_items' 								=> __( 'Search Property Features', 'tt' ),
	    	'popular_items' 							=> __( 'Popular Property Features', 'tt' ),
	    	'all_items' 									=> __( 'All Property Features', 'tt' ),
	    	'edit_item' 									=> __( 'Edit Property Features', 'tt' ),
	    	'update_item' 								=> __( 'Update Property Features', 'tt' ),
	    	'add_new_item' 								=> __( 'Add New Property Features', 'tt' ),
	    	'new_item_name' 							=> __( 'New Property Features Name', 'tt' ),
	    	'separate_items_with_commas' 	=> __( 'Separate Property Features With Commas', 'tt' ),
	    	'add_or_remove_items' 				=> __( 'Add or Remove Property Features', 'tt' ),
	    	'choose_from_most_used' 			=> __( 'Choose From Most Used Property Features', 'tt' ),  
	    	'parent' 											=> __( 'Parent Property Features', 'tt' )      	
	    	),
	    'hierarchical'			=> true,
	    'query_var' 				=> true,  
	    'rewrite' 					=> array( 'slug' => __( 'property-feature', 'tt' ) ),
	    'show_ui'           => true, // Whether to generate a default UI for managing this taxonomy
			'show_admin_column' => true, // Whether to allow automatic creation of taxonomy columns on associated post-types table
		)  
	);
}
add_action( 'init', 'tt_register_taxonomy_property_features', 0 );


/* CUSTOM PROPERTY COLUMNS
============================== */

function tt_property_columns( $property_columns ) {
  $property_columns = array(
      'cb' 				=> '<input type=\'checkbox\' />',
      'thumbnail'	=> __( 'Thumbnail','tt' ),
      'title' 		=> __( 'Property Name','tt' ),
      'featured' 	=> __( 'Featured','tt' ),
      'address' 	=> __( 'Address','tt' ),
      'location' 	=> __( 'Location','tt' ),
      'status' 		=> __( 'Status','tt' ),
      'type' 			=> __( 'Type','tt' ),
      'features' 	=> __( 'Features','tt' ),
      'price' 		=> __( 'Price','tt' ),      
      'author' 		=> __( 'Owner','tt' ),      
      'date' 			=> __( 'Published','tt' )
  );
  return $property_columns;
}
add_filter('manage_edit-property_columns', 'tt_property_columns');

function tt_property_custom_columns( $property_column ) {
  
  global $post;
  
  switch ( $property_column ) {
    case 'thumbnail' :
      if( has_post_thumbnail( $post->ID ) ) {
      	the_post_thumbnail( 'thumbnail' );
      }
      else{
      	_e( '-', 'tt' );
      }
      break;
    case 'featured' :
      if( get_post_meta( $post->ID, 'estate_property_featured', true ) ) {
      	_e( 'Yes', 'tt' );
      }
      else{
      	_e( 'No', 'tt' );
      }
      break;
    case 'address' :
      $address = get_post_meta( $post->ID, 'estate_property_address', true );
      if( !empty( $address ) ) {
      	echo $address;
      }
      else{
      	_e( 'No Address Provided', 'tt' );
      }
      break;
    case 'location' :
      echo get_the_term_list( $post->ID, 'property-location', '', ', ', '' );
      break;
    case 'status' :
      echo get_the_term_list( $post->ID, 'property-status', '', ', ', '' );
      break;      
    case 'type' :
      echo get_the_term_list( $post->ID, 'property-type', '', ', ', '' );
      break;
    case 'features' :
      echo get_the_term_list( $post->ID, 'property-features', '', ', ', '' );
      break;
		case 'price' :
			$properts_price = tt_property_price();
      if ( empty( $properts_price ) ) {
	      _e( '-', 'tt' );
      }
      else {
	      echo $properts_price;
      }
      break;      
  }
  
}
add_action('manage_property_posts_custom_column', 'tt_property_custom_columns');


/* META BOX PROPERTY PAYMENT
============================== */

function tt_add_meta_box_property_payment() {
  add_meta_box( 'meta-box', __('Property Payment Details', 'tt' ), 'tt_meta_box_property_payment', 'property', 'normal', 'core' );
}
add_action( 'add_meta_boxes', 'tt_add_meta_box_property_payment' );

if ( !function_exists('tt_meta_box_property_payment') ) {
	function tt_meta_box_property_payment( $post ) {
	
	  $payment_data = get_post_custom( $post->ID );
	  $empty  = '-';
	
	  $property_payment_txn_id = isset( $payment_data['property_payment_txn_id'] ) ? $payment_data['property_payment_txn_id'][0] : $empty;
	  $property_payment_date   = isset( $payment_data['property_payment_payment_date'] ) ? $payment_data['property_payment_payment_date'][0] : $empty;
	  $property_payment_payer_email = isset( $payment_data['property_payment_payer_email'] ) ? $payment_data['property_payment_payer_email'][0] : $empty;
	  $property_payment_first_name = isset( $payment_data['property_payment_first_name'] ) ? $payment_data['property_payment_first_name'][0] : $empty;
	  $property_payment_last_name = isset( $payment_data['property_payment_last_name'] ) ? $payment_data['property_payment_last_name'][0] : $empty;
	  $property_payment_status = isset( $payment_data['property_payment_status'] ) ? $payment_data['property_payment_status'][0] : $empty;
	  $property_payment_mc_gross = isset( $payment_data['property_payment_mc_gross'] ) ? $payment_data['property_payment_mc_gross'][0] : $empty;
	  $property_payment_mc_currency = isset( $payment_data['property_payment_mc_currency'] ) ? $payment_data['property_payment_mc_currency'][0] : $empty;
	
	  $output  = '<p>';
	  $output .=  '<span style="color:limegreen; font-weight:700; text-transform:uppercase">' . $property_payment_status . '</span><br />';
	  $output .= $property_payment_mc_currency . ' ' . $property_payment_mc_gross . '<br />';
	  $output .= $property_payment_first_name . ' ' . $property_payment_last_name . '<br />';
	  $output .= $property_payment_payer_email . '<hr>';
	  $output .= '</p>';
	  $output .= $property_payment_txn_id . '<br />';
	  $output .= $property_payment_date . '<br />';
	  
	  if ( $property_payment_status == 'Completed' ) {
	  	echo $output;
	  }
	  else {
		  echo '-';
	  }
	}
}