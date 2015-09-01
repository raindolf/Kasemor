<?php get_header();
/*
Template Name: Home - Slideshow
*/
global $post, $realty_theme_option;
$home_layout = $realty_theme_option['home-slideshow-type'];
$home_slideshow_properties_mode = $realty_theme_option['home-slideshow-properties-mode'];
$home_slideshow_search = $realty_theme_option['home-slideshow-search'];
$slideshow_width = $realty_theme_option['slideshow-width-type'];
if ( !isset( $slideshow_width ) ) {
	$slideshow_width = "full";
}
		
$no_search = false;
$mini_search = false;
$custom_property_search = false;

if ( $home_slideshow_search == "none" ) {
	$no_search = true;
}
if ( $home_slideshow_search == "mini" ) {
	$mini_search = true;
}
if ( $home_slideshow_search == "custom" ) {
	$custom_property_search = true;
}
?>

<div id="home-slideshow" class="flexslider <?php echo $realty_theme_option['home-slideshow-height-type']; ?>">
	
	<div class="spinner">
	  <div class="bounce1"></div>
	  <div class="bounce2"></div>
	  <div class="bounce3"></div>
	</div>
	
	<?php if ( $custom_property_search || $mini_search ) { ?>
	<div class="property-mini-search">
		<div class="container">
		<?php 
		if ( $custom_property_search ) {
			get_template_part( 'lib/inc/template/search-form' );
		}
		else if ( $mini_search ) {
			get_template_part( 'lib/inc/template/search-form-mini' );
		}
		?>
		</div>
	</div>
	<?php } // END if Property Search ?>
	
	<ul class="slides">
		<?php	

		/* SLIDESHOW - CUSTOM
		============================== */

		if ( $home_layout == "slideshow-custom" ) {
		
			$home_slideshow = $realty_theme_option['home-slides'];
			
			foreach ($home_slideshow as $home_slide) { 
				
				$attachment_array = wp_get_attachment_image_src( $home_slide['attachment_id'], $slideshow_width );
				$attachment_url_slide = $attachment_array[0];
				
				if ( $realty_theme_option['home-slideshow-height-type'] == "original" ) {
					echo '<li class="slide-' . $home_slide['attachment_id'] . '">';
					echo '<img class="lazy" src="'.get_bloginfo('template_directory').'/lib/images/placeholder.png" data-original="' . $attachment_array[0] . '" alt="" />';
					
				}
				else {
					echo '<li class="lazy fullscreen-image slide-' . $home_slide['attachment_id'] . '" data-original="'.$attachment_array[0].'" style="background-image: url('.get_bloginfo('template_directory').'/lib/images/placeholder.png)">';
				}						
				?>
				<div class="wrapper-out">
					<div class="wrapper">
						<div class="inner<?php if ( $custom_property_search || $mini_search ) { echo ' bottom'; } ?>">
							<div class="container">
									<?php if ( $home_slide['title'] ) { ?>
									<h3 class="title">
										<?php 
										if ( $home_slide['url'] ) { echo '<a href="' . $home_slide['url'] . '" class="slideshow-content-link">' . do_shortcode($home_slide['title']) . '</a>'; }
										else {
											echo do_shortcode($home_slide['title']);
										}
										echo tt_icon_property_video(); 
										?>
									</h3>
									<?php } if ( $no_search && $home_slide['description'] ) { ?>
									<div class="clearfix"></div>
									<div class="description">
										<?php echo do_shortcode($home_slide['description']); ?>
										<?php if ( $home_slide['title'] ) { ?><div class="arrow-right"></div><?php } ?>
									</div>
									<?php } ?>
							</div>
						</div>
					</div>
				</div>
				
				</li>
			<?php	
			} 
		
		} // END Slideshow Custom
	
	
		/* SLIDESHOW - PROPERTIES
		============================== */
		
		if ( $home_layout == "slideshow-properties" ) {	
			
			// Shwo Featured Properties
			if ( $home_slideshow_properties_mode == "slideshow-properties-show-featured" ) {
						
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
			if ( $home_slideshow_properties_mode == "slideshow-properties-show-latest" ) {
			
					$home_properties_slides_args = array(
					'post_type' 			=> 'property',
					'posts_per_page' 	=> 3,
				);
				
			}
			
			// Show Selected Properties
			if ( $home_slideshow_properties_mode == "slideshow-properties-show-selected" ) {
		
				$home_properties_slides_id = $realty_theme_option['home-property-slides'];
				
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
				$home_property_slide_thumbnail = wp_get_attachment_image_src( $home_property_slide_thumbnail_id, $slideshow_width, true );
				
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
						<div class="inner<?php if ( $custom_property_search || $mini_search ) { echo ' bottom'; } ?>">
							<div class="container">
								<h3 class="title">
									<?php 
									echo '<a href="' . get_the_permalink() . '" class="slideshow-content-link">' . get_the_title() . '</a>';
									echo tt_icon_property_video(); 
									?>
								</h3>
								<?php if ( $no_search && get_the_excerpt() ) { ?>
								<div class="clearfix"></div>
								<div class="description">
									<?php the_excerpt(); ?>
									<?php if ( get_the_title() ) { ?>
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
			
		}
		?>
	</ul>
	
</div>

<div class="container">
	
	<div class="row">
	
		<?php
		$hide_sidebar = get_post_meta( $post->ID, 'estate_page_hide_sidebar', true );
		// Check for Page Sidebar
		if ( !$hide_sidebar && is_active_sidebar( 'sidebar_page' ) ) {
			echo '<div class="col-sm-8 col-md-9">';
		} else {
			echo '<div class="col-sm-12">';
		}
		
		if ( have_posts() ) : while ( have_posts() ) : the_post();
			the_content(); 
		endwhile;
		endif;
		?>
		
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
	
</div><!-- .container -->

<?php get_footer(); ?>