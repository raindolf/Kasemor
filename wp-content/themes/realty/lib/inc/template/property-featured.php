<?php
/* QUERY PROPERTIES
============================== */
if ( is_author() ) {
	$author = get_user_by( 'slug', get_query_var( 'author_name' ) );
	$property_args = array(
		'post_type' 				=> 'property',
		'posts_per_page' 		=> -1,
		'author'						=> $author->ID
	);
}
else {
	$property_args = array(
		'post_type' 				=> 'property',
		'posts_per_page' 		=> -1,
		'meta_query' 				=> array(
			array(
				'key' 	=> 'estate_property_featured',
				'value' => 1,
				'type'  => 'NUMERIC'
			)
		)
	);
}

$query_property = new WP_Query( $property_args );

if ( $query_property->have_posts() ) : 

// On author page use two column carousel. All other carousel column counts are set via shortcode.
if ( is_author() ) {
	echo '<h3 class="section-title"><span>' . __( 'My Listings', 'tt' ) . '</span></h3>';
	echo '<div class="owl-carousel-2">';
}

while ( $query_property->have_posts() ) : $query_property->the_post();
$property_location = get_the_terms( $post->ID, 'property-location' );
$property_status = get_the_terms( $post->ID, 'property-status' );
$property_type = get_the_terms( $post->ID, 'property-type' );
if ( $property_type || $property_status || $property_location ) {	
	$no_property_details = false;
}
else {
	$no_property_details = true;	
}

get_template_part( 'lib/inc/template/property-item' );

endwhile;
?>
</div><!-- .owl-carousel -->
<?php
wp_reset_query();
endif;