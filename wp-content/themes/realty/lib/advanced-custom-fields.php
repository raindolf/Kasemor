<?php
// ACF: Plugin active?
function tt_acf_active() {
	if( class_exists( 'acf' ) ) {
		return true;
	}
}
add_action( 'plugins_loaded', 'tt_acf_active' );

// ACF: Group IDs for post type "property"
if ( !function_exists('tt_acf_group_id_property') ) {
	function tt_acf_group_id_property() {
	
		$the_query = new WP_Query( array( 'post_type' => 'acf', 'posts_per_page' => -1 ) );
		if ( $the_query->have_posts() ) : 
			while ( $the_query->have_posts() ) : $the_query->the_post();
				return get_the_ID();
			endwhile;
			wp_reset_query();
			return false;
		endif;
	
	}
}

// ACF: Property Fields Name
if ( !function_exists('tt_acf_fields_name') ) {
	function tt_acf_fields_name( $group_id ) {
	
		$acf_field_keys = get_post_custom_keys( $group_id );
		$acf_field_name = array();
		
		if ( isset( $acf_field_keys ) && !empty( $acf_field_keys ) ) {
			foreach ( $acf_field_keys as $key => $value ) {
		    if ( stristr( $value, 'field_' ) ) {
		      $acf_field = get_field_object( $value, $group_id ); 
					$acf_field_name[] = $acf_field['name'];
		    }
			}
		}    
		return $acf_field_name;
	  
	}
}

// ACF: Property Fields Label
if ( !function_exists('tt_acf_fields_label') ) {
	function tt_acf_fields_label( $group_id ) {
		
		$acf_field_keys = get_post_custom_keys( $group_id );
		$acf_field_label = array();
		
		foreach ( $acf_field_keys as $key => $value ) {
	    if ( stristr( $value, 'field_' ) ) {
	      $acf_field = get_field_object( $value, $group_id ); 
				$acf_field_label[] = $acf_field['label'];
	    }
		}    
		return $acf_field_label;
	    
	}
}

// ACF: Property Fields Type
if ( !function_exists('tt_acf_fields_type') ) {
	function tt_acf_fields_type( $group_id ) {
		
		$acf_field_keys = get_post_custom_keys( $group_id );
		$acf_field_label = array();
		
		foreach ( $acf_field_keys as $key => $value ) {
	    if ( stristr( $value, 'field_' ) ) {
	      $acf_field = get_field_object( $value, $group_id ); 
				$acf_field_label[] = $acf_field['type'];
	    }
		}    
		return $acf_field_label;
	    
	}
}

// ACF: Property Fields "Required"
if ( !function_exists('tt_acf_fields_required') ) {
function tt_acf_fields_required( $group_id ) {
	
	$acf_field_keys = get_post_custom_keys( $group_id );
	$acf_field_label = array();
	
	foreach ( $acf_field_keys as $key => $value ) {
    if ( stristr( $value, 'field_' ) ) {
      $acf_field = get_field_object( $value, $group_id ); 
			$acf_field_label[] = $acf_field['required'];
    }
	}    
	return $acf_field_label;
    
}
}