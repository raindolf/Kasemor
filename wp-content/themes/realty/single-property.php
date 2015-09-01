<?php 
get_header(); 

$hide_sidebar = get_post_meta( $post->ID, 'estate_page_hide_sidebar', true );

$property_location = get_the_terms( $post->ID, 'property-location' );
$property_status = get_the_terms( $post->ID, 'property-status' );
$property_type = get_the_terms( $post->ID, 'property-type' );
$property_features = get_the_terms( $post->ID, 'property-features' );

// Get Date Format Settings from "Settings > General >Date Format"
$date_format = get_option( 'date_format' );

$available_from = get_post_meta( $post->ID, 'estate_property_available_from', true );
// Create Date from Post Meta Data
$available_from_create_date = date_create( $available_from );
$available_from_date = date_format( $available_from_create_date, $date_format );
$today = current_time( $date_format );

$single_property_layout = get_post_meta( $post->ID, 'estate_property_layout', true );
$property_status_update = get_post_meta( $post->ID, 'estate_property_status_update', true );
$property_video_provider = get_post_meta( $post->ID, 'estate_property_video_provider', true );
$property_video_id = get_post_meta( $post->ID, 'estate_property_video_id', true );
$property_images = get_post_meta( $post->ID, 'estate_property_images', false );
$featured = get_post_meta( $post->ID, 'estate_property_featured', true );
$property_id = get_post_meta( $post->ID, 'estate_property_id', true );
$address = get_post_meta( $post->ID, 'estate_property_address', true );
$google_maps = get_post_meta( $post->ID, 'estate_property_location', true );
$size = get_post_meta( $post->ID, 'estate_property_size', true );
$size_unit = get_post_meta( $post->ID, 'estate_property_size_unit', true );
$price = intval( get_post_meta( $post->ID, 'estate_property_price', true ) );
$rooms = get_post_meta( $post->ID, 'estate_property_rooms', true );
$bedrooms = get_post_meta( $post->ID, 'estate_property_bedrooms', true );
$bathrooms = get_post_meta( $post->ID, 'estate_property_bathrooms', true );
$garages = get_post_meta( $post->ID, 'estate_property_garages', true );
$agent = get_post_meta( $post->ID, 'estate_property_custom_agent', true );
$property_contact_information = get_post_meta( $post->ID, 'estate_property_contact_information', true );
$property_attachments = get_post_meta( $post->ID, 'estate_property_attachments', false );

$property_floor_plan_title = get_post_meta( $post->ID, 'estate_floor_plan_title', true );
$property_floor_plan_price = get_post_meta( $post->ID, 'estate_floor_plan_price', true );
$property_floor_plan_size = get_post_meta( $post->ID, 'estate_floor_plan_size', true );
$property_floor_plan_rooms = get_post_meta( $post->ID, 'estate_floor_plan_rooms', true );
$property_floor_plan_bedrooms = get_post_meta( $post->ID, 'estate_floor_plan_bedrooms', true );
$property_floor_plan_bathrooms = get_post_meta( $post->ID, 'estate_floor_plan_bathrooms', true );
$property_floor_plan_description = get_post_meta( $post->ID, 'estate_floor_plan_description', true );
$property_floor_plan_image = get_post_meta( $post->ID, 'estate_floor_plan_image', true );

global $realty_theme_option;
$property_layout = $realty_theme_option['property-layout'];
$property_meta_data_type = $realty_theme_option['property-meta-data-type'];
$property_title_details = $realty_theme_option['property-title-details'];
$property_title_additional_details = $realty_theme_option['property-title-additional-details'];
$property_title_features = $realty_theme_option['property-title-features'];
$property_title_attachments = $realty_theme_option['property-title-attachments'];
$property_title_floor_plan = $realty_theme_option['property-title-floor-plan'];
$property_title_agent = $realty_theme_option['property-title-agent'];
$social_sharing = $realty_theme_option['property-social-sharing'];
$show_agent_information = $realty_theme_option['property-agent-information'];
$show_property_contact_form = $realty_theme_option['property-contact-form'];
$property_contact_form_default_email = $realty_theme_option['property-contact-form-default-email'];
$property_image_height = $realty_theme_option['property-contact-form-default-email'];
$property_floor_plan_disable = $realty_theme_option['property-floor-plan-disable'];

$slideshow_width = $realty_theme_option['slideshow-width-type'];
if ( !isset( $slideshow_width ) ) {
	$slideshow_width = "full";
}


if ( $realty_theme_option['property-lightbox'] != "none" ) {
	$property_zoom = ' zoom';
}
else {
	$property_zoom = '';
}

if ( $realty_theme_option['property-image-height-fit-or-cut'] ) {
	$fit_or_cut = $realty_theme_option['property-image-height-fit-or-cut'];
}
else {
	$fit_or_cut = '';
}

if ( $single_property_layout == "theme_option_setting" || $single_property_layout == "" ) {
	if ( $property_layout == "layout-full-width" ) {
		$layout = "full-width";
	}
	else {
		$layout = "boxed";
	}
}
else {
	if ( $single_property_layout == "full_width" ) {
		$layout = "full-width";
	}
	else {
		$layout = "boxed";
	}
}

function wp_get_attachment_meta_data_title() {
	$attachment = get_post( get_post_thumbnail_id() );
	return $attachment->post_title;
}
?>

<?php if ( $layout == "full-width" ) { echo '</div>'; } // .container ?>

<div id="property-layout-<?php echo $layout; ?>">
	
	<div class="property-image-container <?php if ( !$property_images ) { echo 'single '; } echo $realty_theme_option['property-image-height'] . ' ' . $fit_or_cut; ?>">
	
		<div class="spinner">
		  <div class="bounce1"></div>
		  <div class="bounce2"></div>
		  <div class="bounce3"></div>
		</div>
	
		<div class="flexslider-thumbnail loading">
			<ul class="slides">					
			<?php
			
			if ( $property_video_id && ( $property_video_provider == "youtube" || $property_video_provider == "vimeo" ) ) {
				if ( $property_video_provider == "youtube" ) {
					if ( is_ssl() ) {
						$property_video_url = 'https://youtube.com/watch?v=' . $property_video_id;
					}
					else {
						$property_video_url = 'http://youtube.com/watch?v=' . $property_video_id;
					}
				}
				
				if ( $property_video_provider == "vimeo" ) {
					if ( is_ssl() ) {
						$property_video_url = 'https://player.vimeo.com/video/' . $property_video_id;
					}
					else {
						$property_video_url = 'http://player.vimeo.com/video/' . $property_video_id;
					}
				}
				echo '<li><div class="fluid-width-video-wrapper">' . wp_oembed_get( $property_video_url ) . '</div></li>';	
			}
			
			/* Featured Image Only
			============================== */
			if ( !$property_images ) {
				
				if ( has_post_thumbnail() ) {
			
					$thumbnail_url_array = wp_get_attachment_image_src(get_post_thumbnail_id(), $slideshow_width, true);
					$thumbnail_url = $thumbnail_url_array[0];
										
					if ( $realty_theme_option['property-image-height'] != "original" ) {
						echo '<li class="property-image' . $property_zoom . '" style="background-image:url(' . $thumbnail_url . ')" data-title="' . wp_get_attachment_meta_data_title() . '" data-image="' . wp_get_attachment_url( get_post_thumbnail_id() ) . '" data-mfp-src="' . wp_get_attachment_url( get_post_thumbnail_id() ) . '" title="' . wp_get_attachment_meta_data_title() . '"></li>';	
					}
					
					if ( $realty_theme_option['property-image-height'] == "original" ) {
						
						$thumbnail_attr = array( 
							'class' => 'property-image', 
							'title' => wp_get_attachment_meta_data_title(), 
							'data-image' => wp_get_attachment_url( get_post_thumbnail_id() ),
							'data-title' => wp_get_attachment_meta_data_title(), 
							'data-mfp-src' => wp_get_attachment_url( get_post_thumbnail_id() ) 
						);
						
						echo '<li>';
						the_post_thumbnail( $slideshow_width, $thumbnail_attr );
						echo '</li>';
						
					}
										
				}
				
			}
			
			/* Property Gallery
			============================== */
			else {
				
				$args = array(
					'post_type' => 'attachment',
					'orderby' => 'post__in',
					'post__in' => $property_images,
					'posts_per_page' => count($property_images)
				);
				
				$gallery_array = get_posts( $args );
				
				foreach ($gallery_array as $slide) {
					$attachment = wp_get_attachment_image_src( $slide->ID, $slideshow_width );
					$attachment_url = $attachment[0];
					
					if ( $realty_theme_option['property-image-height'] == "original" ) {
						echo '<li><img src="' . $attachment_url . '" alt="" data-mfp-src="' . $attachment_url . '" class="property-image' . $property_zoom . '" title="' . $slide->post_title . '" data-title="' . $slide->post_title . '" /></li>';
					}
					else {
						echo '<li style="background-image:url(' . $attachment_url . ')" data-image="' . $attachment_url . '" data-mfp-src="' . $attachment_url . '" class="property-image' . $property_zoom . '" title="' . $slide->post_title . '" data-title="' . $slide->post_title . '"></li>';
					}
					
				}
			
			}
			?>
			</ul>
		</div>
	
	</div>
	
	<?php 
	if ( $layout == "full-width" ) { echo '<div class="container">'; } 
	
	/* Show thumbnail navigation, if: gallery OR video + featured image
	============================== */
	if ( $property_images || ( $property_video_provider && $property_video_id && has_post_thumbnail() ) ) {
	?>
	<div class="flexslider-thumbnail-navigation">
		<ul class="slides">					
		<?php
		if ( $property_video_id && ( $property_video_provider == "youtube" || $property_video_provider == "vimeo" ) ) {
			
			if ( $property_video_provider == "youtube" ) {
				$video_thumbnail_output  = '<li class="property-video-thumbnail" style="background-image:url(//img.youtube.com/vi/' . $property_video_id . '/0.jpg)" />';
				$video_thumbnail_output .= '<img src="//img.youtube.com/vi/' . $property_video_id . '/0.jpg" />';
				$video_thumbnail_output .= '</li>';
			}
			
			if ( $property_video_provider == "vimeo" ) {
				
				function tt_get_vimeo_thumb( $id, $size = 'thumbnail_large' ) {
				  if( get_transient( 'vimeo_' . $size . '_' . $id ) ) {
				    $thumb_image = get_transient( 'vimeo_' . $size . '_' . $id );
				  } else {
					  if ( is_ssl() ) {
				    	$json = json_decode( file_get_contents( "https://vimeo.com/api/v2/video/" . $id . ".json" ) );
				    }
				    else {
					    $json = json_decode( file_get_contents( "http://vimeo.com/api/v2/video/" . $id . ".json" ) );
				    }
				    $thumb_image = $json[0]->$size;
				    set_transient('vimeo_' . $size . '_' . $id, $thumb_image, 2629743);
				  }
				  return $thumb_image;
				}
				
				$video_thumbnail_output  = '<li class="property-video-thumbnail" style="background-image:url(' . tt_get_vimeo_thumb( $property_video_id ) . ')" />';
				$video_thumbnail_output .= '<img src="' . tt_get_vimeo_thumb( $property_video_id ) . '" />';
				$video_thumbnail_output .= '</li>';
			}
			
			echo $video_thumbnail_output;
			
		}

		if ( !$property_images ) {
			
			$thumbnail_attr = array( 
				'title' => wp_get_attachment_meta_data_title(), 
				'data-title' => wp_get_attachment_meta_data_title(), 
				'data-mfp-src' => wp_get_attachment_url( get_post_thumbnail_id() ) 
			);
						
			echo '<li>';
			the_post_thumbnail( 'property-thumb', $thumbnail_attr );
			echo '</li>';
			
		} 
		
		else {			
			foreach ($gallery_array as $slide) {
				
				$attachment = wp_get_attachment_image_src( $slide->ID, 'property-thumb' );
				$attachment_url = $attachment[0];
				echo '<li><img src="' . $attachment_url . '" alt="" /></li>';
				
			}
		}
		?>
		</ul>
	</div>
	<?php
	}
	if ( $layout == "full-width" ) { echo '</div>'; }
	?>
	
	<div class="property-header-container<?php if ( !has_post_thumbnail() ) { echo " no-property-image"; } ?>">
		<?php if ( $layout == "full-width" ) { echo '<div class="container">'; } ?>
			<div class="property-header">
				<h1 class="title primary-tooltips">
					<span><?php echo get_the_title(); ?></span>
					<span><?php echo tt_add_remove_favorites(); ?></span>
					<?php if ( $address ) { ?>
					<div class="property-address"><?php echo $address; ?></div>
					<?php } ?>
				</h1>
				<div class="clearfix"></div>
				<div class="meta">
					<?php 
					echo '<span>' . tt_property_price() . '</span>';
					if ( $property_type || $property_status ) echo '<span class="separator">&middot;</span>';
					if ( $property_type ) { foreach ( $property_type as $type ) { echo ' <span class="property-type">' . $type->name . '</span>'; break; } }
					if ( $property_status ) { foreach ( $property_status as $status ) { echo ' <span class="property-status">(' . $status->name . ')</span>'; break; } }
					?>
				</div>
			</div>
			<?php if ( $property_status_update ) { echo '<div id="property-status-update"><span>' . $property_status_update . '</span></div>'; } ?>
		<?php if ( $layout == "full-width" ) { echo '</div>'; } ?>
	</div>
	
</div>

<?php if ( $layout == "full-width" ) { echo '<div class="container">'; } ?>

<?php wp_reset_postdata(); ?>
		
<div class="property-meta primary-tooltips">
	
	<div class="row">
		
		<?php 
		// Use Custom Meta Data
		if ( $property_meta_data_type == "custom" ) { 

			$property_meta_data_field = $realty_theme_option['property-custom-meta-data-field'];
			$property_meta_data_icon_class = $realty_theme_option['property-custom-meta-data-icon-class'];
			$property_meta_data_label = $realty_theme_option['property-custom-meta-data-label'];
			$property_meta_data_label_plural = $realty_theme_option['property-custom-meta-data-label-plural'];
			$property_meta_data_tooltip = $realty_theme_option['property-custom-meta-data-tooltip'];

			$i = 0;
			
			foreach ( $property_meta_data_field as $field_type ) {
				
				switch ( $field_type ) {
					
					case 'estate_property_id' :
					if ( $realty_theme_option['property-id-type'] == "custom_id" ) {
						$field = $property_id;
					}
					else {
						$field = $post->ID;
					}
					break;
					
					case 'estate_property_available_from' :
					$create_date = date_create( $field );
					$field = date_format( $create_date, $date_format );	
					break;
					
					case 'estate_property_size' :
					$size_unit = get_post_meta( $post->ID, 'estate_property_size_unit', true );
					$field = $field . ' ' . $size_unit;
					break;
					
					default :
					$field = get_post_meta( $post->ID, $field_type, true );
					break;
					
				}
				?>
				
				<div class="col-sm-4 col-md-3">
					<div class="meta-title"><i class="fa <?php echo $property_meta_data_icon_class[$i]; ?>"></i></div>
					<div class="meta-data" data-toggle="tooltip" title="<?php echo _n( __( $property_meta_data_label[$i], 'tt' ), __( $property_meta_data_label_plural[$i], 'tt' ), $field, 'tt' ); ?>">
						<?php
						echo $field;
						if ( $property_meta_data_tooltip[$i] == false ) {
							echo ' ' . _n( __( $property_meta_data_label[$i], 'tt' ), __( $property_meta_data_label_plural[$i], 'tt' ), $field, 'tt' );
						}
						?>
					</div>
				</div>
				
				<?php 
				$i++;
			}
		} 
		// Default Meta Data
		else {
		
			if ( $available_from ) { ?>
			<div class="col-sm-4 col-md-3">
				<div class="meta-title"><i class="fa fa-calendar-o"></i></div>
				<div class="meta-data" data-toggle="tooltip" title="<?php _e( 'Available From', 'tt' ); ?>"><?php echo $available_from_date; // if ( $available_from_date <= $today ) { echo '<i class="fa fa-check"></i>'; } ?></div>
			</div>
			<?php }
			if ( $size ) { ?>
			<div class="col-sm-4 col-md-3">
				<div class="meta-title"><i class="fa fa-expand"></i></div>
				<div class="meta-data" data-toggle="tooltip" title="<?php _e( 'Size', 'tt' ); ?>"><?php echo $size . ' ' . $size_unit; ?></div>
			</div>
			<?php }
			if ( $rooms ) { ?>
			<div class="col-sm-4 col-md-3">
				<div class="meta-title"><i class="fa fa-building-o"></i></div>
				<div class="meta-data" data-toggle="tooltip" title="<?php echo __( 'Rooms', 'tt' ); ?>"><?php echo $rooms . ' ' . _n( __( 'Room', 'tt' ), __( 'Rooms', 'tt' ), $rooms, 'tt' ); ?></div>
			</div>
			<?php }
			if ( $bedrooms ) { ?>
			<div class="col-sm-4 col-md-3">
				<div class="meta-title"><i class="fa fa-bed"></i></div>
				<div class="meta-data" data-toggle="tooltip" title="<?php echo __( 'Bedrooms', 'tt' ); ?>"><?php echo $bedrooms . ' ' . _n( __( 'Bedroom', 'tt' ), __( 'Bedrooms', 'tt' ), $bedrooms, 'tt' ); ?></div>
			</div>
			<?php }
			if ( $bathrooms ) { ?>
			<div class="col-sm-4 col-md-3">
				<div class="meta-title"><i class="fa fa-tint"></i></div>
				<div class="meta-data" data-toggle="tooltip" title="<?php echo __( 'Bathrooms', 'tt' ); ?>"><?php echo $bathrooms . ' ' . _n( __( 'Bathroom', 'tt' ), __( 'Bathrooms', 'tt' ), $bathrooms, 'tt' ); ?></div>
			</div>
			<?php }
			if ( $garages ) { ?>
			<div class="col-sm-4 col-md-3">
				<div class="meta-title"><i class="fa fa-car"></i></div>
				<div class="meta-data" data-toggle="tooltip" title="<?php echo __( 'Garages', 'tt' ); ?>"><?php echo $garages . ' '. _n( __( 'Garage', 'tt' ), __( 'Garages', 'tt' ), $garages, 'tt' ); ?></div>
			</div>
			<?php } ?>
			<div class="col-sm-4 col-md-3">
				<div class="meta-title"><i class="fa fa-slack"></i></div>
				<div class="meta-data" data-toggle="tooltip" title="<?php _e( 'Property ID', 'tt' ); ?>">
					<?php 
					if ( $realty_theme_option['property-id-type'] == "post_id" ) {
						echo $post->ID;
					}
					else {
						echo $property_id;
					}
					?>
				</div>
			</div>
		
		<?php 
		} 
		if ( !$realty_theme_option['property-meta-data-hide-print'] ) {
		?>
		<div class="col-sm-4 col-md-3">
			<a href="#" id="print">
				<div class="meta-title"><i class="fa fa-print"></i></div>
				<div class="meta-data"><?php _e( 'Print this page', 'tt' ); ?></div>
			</a>
		</div>
		<?php
		}
		?>
		
	</div>
	
</div>
		
<div class="row">

	<?php
	if ( !$hide_sidebar && is_active_sidebar( 'sidebar_property' ) ) {
		echo '<div class="col-sm-8 col-md-9">';
	} 
	else {
		echo '<div class="col-sm-12">';
	}
	?>
	
	<div id="main-content" class="content-box">

		<section>
			<?php 
			if ( $property_title_details ) { echo '<h3 class="section-title"><span>' . __( $property_title_details, 'tt' ) . '</span></h3>'; }
			the_content(); 
			?>
		</section>
		
		<?php if ( tt_acf_active() && tt_acf_group_id_property() ) : // Check if ACF plugin is active & for post type "property" field group ?>
		<section id="additional-details">
			<?php if ( $property_title_additional_details ) { echo '<h3 class="section-title"><span>' . __( $property_title_additional_details, 'tt' ) . '</span></h3>'; } ?>
			<ul class="list-unstyled row">
			<?php
			
			$acf_fields_name = tt_acf_fields_name( tt_acf_group_id_property() );
			$acf_fields_label = tt_acf_fields_label( tt_acf_group_id_property() );
			$acf_fields_type = tt_acf_fields_type( tt_acf_group_id_property() );
			
			$acf_fields_count = count($acf_fields_name);

			$i = 0;
			
			while ( $acf_fields_count > $i) :
			
				wp_reset_postdata();
				
				$field = get_field( $acf_fields_name[$i] );
				
				if ( $field == '' ) {
					$field = '-';
					$empty_field = true;
				}
				
				if ( !$hide_sidebar && is_active_sidebar( 'sidebar_property' ) ) {
					$output  = '<li class="col-sm-6 col-md-4">';
				} 
				else {
					$output  = '<li class="col-sm-4 col-md-3">';
				}
				
				$output .= '<strong>'	 . __( $acf_fields_label[$i], 'tt' ) . ':</strong> ';
				
				if ( is_array( $field ) ) {
					$output .= join( ', ', $field );
				}
				else {
					$output .= $field;
				}
				
				$output .= '</li>';
				
				if ( $realty_theme_option['property-additional-details-hide-empty'] && $empty_field ) {
					echo '';
				}
				else {
					echo $output;
				}
					
				$i++;
				
			endwhile;	
			
			wp_reset_postdata();
			?>
			</ul>
		</section>
		<?php endif; ?>
		
		<?php	if ( $property_features ) {	 ?>	
		<section id="property-features" class="primary-tooltips">
			<?php if ( $property_title_features	 ) { echo '<h3 class="section-title"><span>' . __( $property_title_features, 'tt' ) . '</span></h3>'; } ?>
			<ul class="list-unstyled row">
				<?php
				
				$property_features_all = get_terms( 'property-features', array( 'hide_empty' => false ) ); // Get All Property Features
				$property_features_slug = array();
		
				// Built Array With All Property Fe	atures
				foreach ( $property_features as $property_feature ) {
					$property_features_slug[] = $property_feature->slug;
				}
				
				// Loop Thorugh All Featur	es														
				foreach( $property_features_all as $property_feature_item ) {
				
					$property_feature_slug = $property_feature_item->slug;
			    $description = $property_feature_item->description;
			    $description = wp_trim_words( $description, 10, ' ..' ); 
	
					// Add Class "inactive" To Every F	eature, That This Property Doesn't Have
					if ( !in_array( $property_feature_slug, $property_features_slug ) ) { 
						$inactive = ' class="inactive"'; 	
					}
					else {
						$inactive = '';
						}
						
					if ( !$hide_sidebar && is_active_sidebar( 'sidebar_property' ) ) {
						$output  = '<li class="col-sm-6 col-md-4">';
					} else {
						$output  = '<li class="col-sm-4 col-md-3">';
					}
					
					$output .= '<a href="' . site_url() . '/property-feature/'. $property_feature_item->slug . '"' . $inactive . '>';
					if ( $inactive ) {
						$output .=	'<i class="fa fa-times"></i>';
					}
					else {
						$output .= '<i class="fa fa-check"></i>';
					}
					
					$output .=  $property_feature_item->name;
					
					if ( $description ) {
						$output	.= '<i class="fa fa-question-circle" data-toggle="tooltip" title="' . __( $description, 'tt' ) . '"></i>';
					}
					
					$output .= '</a>';
					$output .= 	'</li>';
					
					// Theme Option: Hide non applicable property features
					if ( $realty_theme_option['property-features-hide-non-applicable'] && $inactive ) {
						echo '';
					}
					else {
						echo $output;
					}
				 }
				
				?>
			</ul>
		</section>
		<?php 
		}
		
		if ( !$property_floor_plan_disable && !empty( $property_floor_plan_image[0] ) ) { ?>
		<section id="floor-plan" class="primary-tooltips">
			<?php if ( $property_title_floor_plan	 ) { echo '<h3 class="section-title"><span>' . __( $property_title_floor_plan, 'tt' ) . '</span></h3>'; } ?>

			<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
			<?php					
			$i = 0;
			
			foreach ( $property_floor_plan_image as $image ) {
			?>
			  <div class="panel panel-default">
			    <div class="panel-heading" data-toggle="collapse" data-target="#floor-plan-<?php echo $i; ?>">
			      <h4 class="panel-title"><?php echo $property_floor_plan_title[$i]; ?></h4>
		        <div class="details text-muted">
			        <small>
			        <?php 
							$currency_sign = $realty_theme_option['currency-sign'];
							$currency_sign_position = $realty_theme_option['currency-sign-position'];
							
							if ( $realty_theme_option['price-decimals'] ) {
								$decimals = $realty_theme_option['price-decimals'];
							}
							else {
								$decimals = 0;
							}
							
							$decimal_point = '.';
							
							if ( $property_floor_plan_price[$i] ) {
								$formatted_price = number_format( $property_floor_plan_price[$i], $decimals, $decimal_point, $realty_theme_option['price-thousands-separator'] );
							}
							else {
								$formatted_price = 0;
							}
							
							if( $currency_sign_position == 'right' ) {
								$price = $formatted_price . $currency_sign;
							}
							else {
								$price = $currency_sign . $formatted_price;
							}
							
							if ( $property_floor_plan_price[$i] ) { 
								echo '<span>' . __( 'Price', 'tt' ) . ': ' . $price . '</span>'; 
							}
							
							if ( $property_floor_plan_size[$i] ) { 
								echo '<span>' . __( 'Size', 'tt' ) . ': ' . $property_floor_plan_size[$i] . ' ' . $size_unit . '</span>'; 
							}
							
							if ( $property_floor_plan_rooms[$i] ) { echo '<span>' . __( 'Rooms', 'tt' ) . ': ' . $property_floor_plan_rooms[$i] . '</span>'; }
							if ( $property_floor_plan_bedrooms[$i] ) { echo '<span>' . __( 'Bedrooms', 'tt' ) . ': ' . $property_floor_plan_bedrooms[$i] . '</span>'; }
							if ( $property_floor_plan_bathrooms[$i] ) { echo '<span>' . __( 'Bathrooms', 'tt' ) . ': ' . $property_floor_plan_bathrooms[$i] . '</span>'; } 
							?>
			        </small>
		        </div>
			    </div>
			    <div id="floor-plan-<?php echo $i; ?>" class="panel-collapse collapse">
			      <img src="<?php echo wp_get_attachment_url( $image ); ?>" />
			      <?php if ( $property_floor_plan_description[$i] ) { ?>
			      <div class="panel-body"><?php echo $property_floor_plan_description[$i]; ?></div>
			      <?php } ?>
			    </div>
			  </div>
			<?php
			$i++;
			}
			?>
			</div>
		
		</section>
		<?php 
		}
		
		// Property Map
		if ( $address || $google_maps ) {
			get_template_part( 'lib/inc/template/google-map-single-property' ); 
		}

		// Property Attachments
		if ( $property_attachments ) {
			echo '<section id="attachments">';
			if ( $property_title_attachments ) { 
			echo '<h3 class="section-title"><span>' . __( $property_title_attachments, 'tt' ) . '</span></h3>'; 
			}
			echo '<ul class="list-unstyled row">';
			foreach ( $property_attachments as $attachment_id ) {
				$attachment_url = wp_get_attachment_url( $attachment_id );
				$attachment_file_type = wp_check_filetype( $attachment_url );
				$attachment_file_type = $attachment_file_type['ext'];
				$output  = '<li class="col-sm-4 col-md-3">';
				$output .= tt_icon_attachment($attachment_file_type) . ' <a href="' . $attachment_url . '" target="_blank">' . get_the_title( $attachment_id ) . '</a>';
				$output .= '</li>';
				echo $output;
			}
			echo '</ul>';
			echo '</section>';
		}
		
		// Property Social Sharing
		if ( $social_sharing ) { 
			echo '<div class="primary-tooltips">' . tt_social_sharing() . '</div>';
		}
		?>
		
	</div><!-- #main-container -->
	
	<?php if ( $show_agent_information || $show_property_contact_form ) { ?>
	<div id="agent" class="content-box">
			<?php

			// Property Settings: If Agent Selected, Show His/Her Details, Instead Of Post Auhor
			if ( $agent ) {
			
				if ( $property_title_agent ) { 
					echo '<h3 class="section-title"><span>' . __( $property_title_agent, 'tt' ) . '</span></h3>'; 
				}

				$company_name = get_user_meta( $agent, 'company_name', true );
				$first_name = get_user_meta( $agent, 'first_name', true );
				$last_name = get_user_meta( $agent, 'last_name', true );
				$email = get_userdata( $agent );
				$email = $email->user_email;
				$office = get_user_meta( $agent, 'office_phone_number', true );
				$mobile = get_user_meta( $agent, 'mobile_phone_number', true );
				$fax = get_user_meta( $agent, 'fax_number', true );
				$website = get_userdata( $agent );
				$website = $website->user_url;
				$website_clean = str_replace( array( 'http://', 'https://' ), '', $website );
				$bio = get_user_meta( $agent, 'description', true );
				$profile_image = get_user_meta( $agent, 'user_image', true );
				$author_profile_url = get_author_posts_url( $agent );
				
			}
			// Show Post Author Details
			else {
			
				$company_name = get_the_author_meta( 'company_name' );
				$first_name = get_the_author_meta( 'first_name' );
				$last_name = get_the_author_meta( 'last_name' );
				$email = get_the_author_meta('user_email');
				$office = get_the_author_meta('office_phone_number');
				$mobile = get_the_author_meta('mobile_phone_number');
				$fax = get_the_author_meta('fax_number');
				$website = get_the_author_meta('user_url');
				$website_clean = str_replace( array( 'http://', 'https://' ), '', $website );
				$bio = get_the_author_meta('description');
				$profile_image = get_the_author_meta('user_image');
				$author_profile_url = get_author_posts_url( $post->post_author );
				
			}
			
			// Check Theme Option - Display Agent Information
			if ( $show_agent_information && $property_contact_information == "all" || empty( $property_contact_information ) ) {
			?>			
	
			<section class="row">
				<?php
				if ( $profile_image ) {
					$profile_image_id = tt_get_image_id( $profile_image );
					$profile_image_array = wp_get_attachment_image_src( $profile_image_id, 'square-400' );
					echo '<div class="col-sm-4">';
					echo '<img src="' . $profile_image_array[0] . '" />';
					echo '</div>';
					echo '<div class="col-sm-8">';
				}	
				else {
					echo '<div class="col-sm-12">';
				}
					
				if ( $first_name && $last_name ) {
					echo '<h3 class="title">' . $first_name . ' ' . $last_name . '</h3>';
					if ( $company_name ) {
						echo '<p>' . $company_name . '</p>';
					}
				}
				else if ( $company_name ) {
					echo '<h3 class="title">' . $company_name . '</h3>';
				}
				 
				if ( $email ) { ?><div class="contact"><i class="fa fa-envelope-o"></i><a href="mailto:<?php echo antispambot( $email ); ?>"><?php echo antispambot( $email ); ?></a></div><?php }
				if ( $office ) { ?><div class="contact"><i class="fa fa-phone"></i><?php echo $office; ?></div><?php }
				if ( $mobile ) { ?><div class="contact"><i class="fa fa-mobile"></i><?php echo $mobile; ?></div><?php }
				if ( $fax ) { ?><div class="contact"><i class="fa fa-fax"></i><?php echo $fax; ?></div><?php }
				if ( $website ) { ?><div class="contact"><i class="fa fa-globe"></i><a href="<?php echo $website; ?>" target="_blank"><?php echo $website_clean; ?></a></div><?php } ?>
				<div class="description">
					<?php if ( $bio ) { ?><?php $trim = wp_trim_words( $bio, 40, '..' ); echo '<p>' . $trim . '</p>'; } ?>
				</div>
				<?php 
				echo '<div class="agent-more-link"><a href="'. $author_profile_url .'" class="btn btn-primary">' . __( 'Profile', 'tt' ) . '</a></div>';
				echo '</div>'; ?>
			</section>
			<?php
			}
			// Check Theme Option + Property Settings For Author Contact Form
			if ( $show_property_contact_form && $property_contact_information != "none" || empty( $property_contact_information ) ) {
			?>
			<h4 class="section-title"><span><?php _e( 'Contact', 'tt' ); ?></span></h4>
				<?php 
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
				if ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) && $realty_theme_option['property-contact-form-cf7-shortcode'] ) { 
					echo do_shortcode( '[contact-form-7 id="' . $realty_theme_option['property-contact-form-cf7-shortcode'] . '" title="Contact - 1 Column"]' );
				}
				else {
				?>
				<form id="contact-form" method="post" action="<?php echo admin_url( 'admin-ajax.php' ); ?>">

				<div class="row primary-tooltips">
				
					<div class="form-group col-sm-4">
          	<input type="text" name="name" id="name" class="form-control" placeholder="<?php _e( 'Name', 'tt' ); ?>" title="<?php _e( 'Please enter your name.', 'tt' ); ?>">
          	<input type="text" name="email" id="email" class="form-control" placeholder="<?php _e( 'Email', 'tt' ); ?>" title="<?php _e( 'Please enter your email.', 'tt' ); ?>">
          	<input type="text" name="phone" id="phone" class="form-control" placeholder="<?php _e( 'Phone', 'tt' ); ?>" title="<?php _e( 'Please enter only digits for your phone number.', 'tt' ); ?>">
					</div>
					
					<div class="form-group col-sm-8">
          	<textarea name="message" id="message" class="form-control" placeholder="<?php _e( 'Message', 'tt' ); ?>" title="<?php _e( 'Please enter your message.', 'tt' ); ?>"></textarea>
					</div>

				</div>
				
				<input type="submit" name="submit" value="<?php _e( 'Send Message', 'tt' ); ?>" >
				
        <input type="hidden" name="action" value="submit_property_contact_form" />
        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce(); ?>" />
        <?php 
        // Check If Agent Has An Email Address
        if ( isset( $email ) && !empty( $email ) ) {
        ?>
        	<input type="hidden" name="agent_email" value="<?php echo antispambot( $email ); ?>">
        <?php 
        } 
        // No Agent Email Address Found -> Send Email To Site Administrator
        else { ?>
	        <input type="hidden" name="agent_email" value="<?php echo antispambot( $property_contact_form_default_email ); ?>">
	      <?php } ?>
        <input type="hidden" name="property_title" value="<?php echo get_the_title( get_the_ID() ); ?>" />
        <input type="hidden" name="property_url" value="<?php echo get_permalink( get_the_ID() ); ?>" />

      </form>
	      
	      <div id="form-success" class="hide alert alert-success alert-dismissable">
	      	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	      	<?php _e( 'Message has been sent successfully.', 'tt' ); ?>
	      </div>
	      <div id="form-submitted"></div>
	      
	      <?php 
		    }
	    }
	    ?>
	
		</div><!-- #agent -->
	<?php }
	
	$criteria = $realty_theme_option['property-similar-properties-criteria'];
	$columns = $realty_theme_option['property-similar-properties-columns'];

	$args_similar_properties = array(
		'post_type'					=> 'property',
		'posts_per_page' 		=> -1,
		'post__not_in'			=> array( $post->ID )
	);
	
	// Theme Options: Similar Properties Criteria
	$tax_query = array();
	
	if ( $property_location ) {
		foreach ( $property_location as $location ) {
			$location = $location->slug; 
			break;
		}
	}
	else {
		$location = "";
	}
	
	if ( $criteria['location'] ) {
		$tax_query[] = array(
			'taxonomy' 	=> 'property-location',
			'field' 		=> 'slug',
			'terms'			=> $location
		);
	}
	
	if ( $criteria['status'] ) {
		$tax_query[] = array(
			'taxonomy' 	=> 'property-status',
			'field' 		=> 'slug',
			'terms'			=> $status
		);
	}
	
	if ( $criteria['type'] ) {
		$tax_query[] = array(
			'taxonomy' 	=> 'property-type',
			'field' 		=> 'slug',
			'terms'			=> $type
		);
	}
	
	$tax_count = count( $tax_query );
	if ( $tax_count > 1 ) {
		$tax_query['relation'] = 'AND';
	}
	
	if ( $tax_count > 0 ) {
		$args_similar_properties['tax_query'] = $tax_query;
	}
	
	$meta_query = array();

	if ( $criteria['min_rooms'] ) {
		$meta_query[] = array(
			'key' 			=> 'estate_property_rooms',
			'value' 		=> $rooms,
			'compare'   => '>=',
			'type'			=> 'NUMERIC'
		);
	}
	
	if ( $criteria['max_price'] ) {
		$meta_query[] = array(
			'key' 			=> 'estate_property_price',
			'value' 		=> $price,
			'compare'   => '<=',
			'type'			=> 'NUMERIC'
		);
	}
	
	if ( $criteria['available_from'] ) {
		$meta_query[] = array(
			'key' 			=> 'estate_property_available_from',
			'value' 		=> $available_from,
			'compare'   => '<=',
			'type'			=> 'DATE'
		);
	}
	
	$meta_count = count( $meta_query );
	if ( $meta_count > 1 ) {
	  $meta_query['relation'] = 'AND';
	}
	
	if ( $meta_count > 0 ) {
		$args_similar_properties['meta_query'] = $meta_query;
	}
	
	$query_similar_properties = new WP_Query( $args_similar_properties );
	
	if ( $query_similar_properties->have_posts() ) : 
	
		echo '<div id="similar-properties">';
		echo '<h4 class="section-title"><span>' . __( 'Similar Properties', 'tt' ) . ' (' . $query_similar_properties->found_posts . ')</span></h4>';
		echo '<div id="property-items">';
		if ( $columns ) {
			echo '<div class="owl-carousel-' . $columns . '">';
		}
		else {
			echo '<div class="owl-carousel-2">';
		}
		while ( $query_similar_properties->have_posts() ) : $query_similar_properties->the_post();
		get_template_part( 'lib/inc/template/property', 'item' );	
		endwhile;
		wp_reset_query();
		echo '</div>';
		echo '</div>';
		echo '</div>';
		
	endif; 
	
	if ( $realty_theme_option['property-comments'] && ( comments_open() || get_comments_number() ) ) {
		comments_template();
	}
	
	?>
	
	</div><!-- .col-sm-9 -->
	
	<?php 
	// Check for Property Sidebar
	if ( !$hide_sidebar && is_active_sidebar( 'sidebar_property' ) ) : 
	?>
	<div class="col-sm-4 col-md-3">
		<ul id="sidebar">
			<?php dynamic_sidebar( 'sidebar_property' ); ?>
		</ul>
	</div>
	<?php endif; ?>
	
</div><!-- .row -->

<?php get_footer(); ?>