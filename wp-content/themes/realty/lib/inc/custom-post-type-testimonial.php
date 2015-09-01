<?php
/* CUSTOM POST TYPE: TESTIMONIALS
============================== */
function tt_register_custom_post_type_testimonial() {

	$labels = array(
    'name' 									=> __( 'Testimonials','tt' ),
    'singular_name' 				=> __( 'Testimonial','tt' ),
    'add_new' 							=> __( 'Add New','tt' ),
    'add_new_item' 					=> __( 'Add New Testimonial','tt' ),
    'edit_item' 						=> __( 'Edit Testimonial','tt' ),
    'new_item' 							=> __( 'New Testimonial','tt' ),
    'view_item' 						=> __( 'View Testimonial','tt' ),
    'search_items' 					=> __( 'Search Testimonial','tt' ),
    'not_found' 						=> __( 'No Testimonial found.','tt' ),
    'not_found_in_trash' 		=> __( 'No Testimonial found in Trash.','tt' )
  );

  $args = array(
	  'labels' 								=> $labels,
	  'public' 								=> true,
	  'show_ui' 							=> true,
	  'show_in_admin_bar' 		=> true,
	  'menu_position' 				=> 20,
	  'menu_icon' 						=> 'dashicons-format-chat',
	  'exclude_from_search' 	=> true,
	  'publicly_queryable' 		=> true,
	  'query_var' 						=> true,
	  'rewrite' 							=> true,
	  'hierarchical' 					=> true,
	  'supports' 							=> array( 'title', 'thumbnail' ),
	  'rewrite' 							=> array( 'slug' => __( 'testimonial', 'tt' ) )
  );
	
	register_post_type( 'testimonial', $args );

}
add_action( 'init', 'tt_register_custom_post_type_testimonial' );

// Custom Property Columns
function tt_testimonial_columns( $property_columns ) {
  $property_columns = array(
      'cb' 							=> '<input type=\'checkbox\' />',
      'thumbnail'				=> __( 'Thumbnail','tt' ),
      'title'						=> __( 'From','tt' ),
      'testimonial' 		=> __( 'Testimonial','tt' ),
      'date' 						=> __( 'Date','tt' )
  );
  return $property_columns;
}
add_filter('manage_edit-testimonial_columns', 'tt_testimonial_columns');

function tt_testimonial_custom_columns( $property_column ) {
  
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
    case 'testimonial' :
      echo get_post_meta( $post->ID, 'estate_testimonial_text', true ); 
      break;
  }
  
}
add_action('manage_testimonial_posts_custom_column', 'tt_testimonial_custom_columns');