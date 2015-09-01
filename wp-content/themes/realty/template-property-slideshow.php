<?php get_header();
/*
Template Name: Property Slideshow
*/
global $post, $realty_theme_option;
$property_search = get_post_meta( $post->ID, 'estate_property_slideshow_search', true ); 
$slideshow_width = $realty_theme_option['slideshow-width-type'];
if ( !isset( $slideshow_width ) ) {
	$slideshow_width = "full";
}
?>

<div id="property-slideshow" class="flexslider <?php echo $realty_theme_option['home-slideshow-height-type']; ?>">
	
	<div class="spinner">
	  <div class="bounce1"></div>
	  <div class="bounce2"></div>
	  <div class="bounce3"></div>
	</div>
	
	<?php if ( $property_search != "none" ) { ?>
	<div class="property-mini-search">
		<div class="container">
		<?php 
		if ( $property_search == "custom" ) {
			get_template_part( 'lib/inc/template/search-form' );
		}
		else if ( $property_search == "mini" ) {
			get_template_part( 'lib/inc/template/search-form-mini' );
		}
		?>
		</div>
	</div>
	<?php } // END if Property Search ?>
	
	<ul class="slides">
		<?php
		$slideshow_type = get_post_meta( $post->ID, 'estate_property_slideshow_type', true );
	
		/* PROPERTY SLIDESHOW
		============================== */

		// Shwo Featured Properties
		if ( $slideshow_type == "featured" ) {
					
			$home_properties_slides_args = array(
				'post_type' 			=> 'property',
				'posts_per_page'	=> -1,
				'meta_query' 			=> array(
												       array(
												           'key' 			=> 'estate_property_featured',
												           'value' 		=> 1,
												           'compare' 	=> '=',
												       )
			  )
			);
						
		}
		
		// Show Latest Three Properties
		if ( $slideshow_type == "latest" ) {
		
				$home_properties_slides_args = array(
				'post_type' 			=> 'property',
				'posts_per_page' 	=> 3,
			);
			
		}
		
		// Show Selected Properties
		if ( $slideshow_type == "selected" ) {
	
			$home_properties_slides_id = get_post_meta( $post->ID, 'estate_property_slideshow_selected_properties', false );
			
			$home_properties_slides_args = array(
				'post_type' 			=> 'property',
				'post__in' 				=> $home_properties_slides_id,
				'posts_per_page' 	=> count($home_properties_slides_id),
				'orderby' 				=> 'post__in',
			);
		
		}
					
		$home_properties_slides = new WP_Query( $home_properties_slides_args );
		
		if ( $home_properties_slides->have_posts() ) : while ( $home_properties_slides->have_posts() ) : $home_properties_slides->the_post(); 
			
			$home_property_slide_thumbnail_id = get_post_thumbnail_id();
			
			$home_property_slide_thumbnail = wp_get_attachment_image_src( $home_property_slide_thumbnail_id, $slideshow_width , true );
			
			if ( $realty_theme_option['home-slideshow-height-type'] == "original" ) {
				echo '<li class="slide-' . $home_property_slide_thumbnail_id . '">';
				echo '<img class="lazy" src="'.get_bloginfo('template_directory').'/lib/images/placeholder.png" data-original="' . $home_property_slide_thumbnail[0] . '" />';
			}
			else {
				echo '<li class="lazy slide-' . $home_property_slide_thumbnail_id . '" data-original="' . $home_property_slide_thumbnail[0] . '" style="background-image: url('.get_bloginfo('template_directory').'/lib/images/placeholder.png)">';
			}	
			?>
				
			<div class="wrapper-out">
				<div class="wrapper">
					<div class="inner<?php if ( $property_search != "none" ) { echo ' bottom'; } ?>">
						<div class="container">
								<h3 class="title">
									<?php 
									echo '<a href="' . get_the_permalink() . '" class="slideshow-content-link">' . get_the_title() . '</a>';
									echo tt_icon_property_video(); 
									?>
								</h3>
								<?php if ( $property_search == "none" ) { ?>
								<div class="clearfix"></div>
								<div class="description">
									<?php 
									the_excerpt();
									if ( get_the_title() ) { ?>
									<div class="arrow-right"></div>
									<?php }
									
									$size = get_post_meta( $post->ID, 'estate_property_size', true );
									$size_unit = get_post_meta( $post->ID, 'estate_property_size_unit', true );
									$bedrooms = get_post_meta( $post->ID, 'estate_property_bedrooms', true );
									$bathrooms = get_post_meta( $post->ID, 'estate_property_bathrooms', true );
									
									echo '<div class="property-data">';
									echo '<div class="property-price">' . tt_property_price() . '</div>';
									
									echo '<div class="property-details">';
									if ( $bedrooms ) { echo '<i class="fa fa-bed"></i>' . $bedrooms . ' ' . _n( __( 'Bedroom', 'tt'), __( 'Bedrooms', 'tt'), $bedrooms, 'tt' ); }
									if ( $bathrooms ) { echo '<i class="fa fa-tint"></i>' . $bathrooms . ' ' . _n( __( 'Bathroom', 'tt'), __( 'Bathrooms', 'tt'), $bathrooms, 'tt' ); }
									if ( $size ) { echo '<i class="fa fa-expand"></i>' . $size . $size_unit; }
									echo '</div></div>';	
									?>
								</div>
								<?php } ?>
						</div>
					</div>
				</div>
			</div>
				
			</li>
		<?php
		endwhile;
		wp_reset_query();
		endif;
		?>
	</ul>
	
</div>

<div class="container">
	<?php 
	if ( have_posts() ) : while ( have_posts() ) : the_post();
		the_content(); 
	endwhile;
	endif;
	?>
</div><!-- .container -->

<?php get_footer(); ?>