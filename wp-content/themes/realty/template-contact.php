<?php get_header();
/*
Template Name: Contact
*/
global $post, $realty_theme_option;

$hide_sidebar = get_post_meta( $post->ID, 'estate_page_hide_sidebar', true );

$address = $realty_theme_option['contact-address'];
$phone = $realty_theme_option['contact-phone'];
$mobile = $realty_theme_option['contact-mobile'];
$email = $realty_theme_option['contact-email'];
$logo = $realty_theme_option['contact-logo'];
$logo_src = '';
if ( !empty( $logo['url'] ) ) {
	$logo_array = wp_get_attachment_image_src( $logo['id'], 'medium' );
	$logo_src = $logo_array[0];
	$logo_img = '<img src="' . $logo_array[0] . '" />';
}

while ( have_posts() ) : the_post(); 
?>
	</div><!-- .container -->
	<?php tt_page_banner();	?>	
	<div class="container">
	
	<div class="row">
	
		<?php 
		// Check for Agent Sidebar
		if ( !$hide_sidebar && is_active_sidebar( 'sidebar_contact' ) ) {
			echo '<div class="col-sm-8 col-md-9">';
		} else {
			echo '<div class="col-sm-12">';
		}
		?>
		
			<div id="main-content" class="content-box template-contact">
			
				<?php 
				// Check Contact Theme Option for Googe Maps Visibility
				if ( $realty_theme_option['contact-google-map'] ) { 
				?>
				
				<script src="//maps.googleapis.com/maps/api/js?v=3.exp"></script>
				<script src="<?php echo get_template_directory_uri() . '/assets/js/google.maps.infobox.js'; ?>"></script>
				<script>
				var map;
				function initMap() {
				
					// https://developers.google.com/maps/documentation/javascript/examples/  
				  var mapOptions = {
				    zoom: 14,
				    center: new google.maps.LatLng(-34.397, 150.644),
				    scrollwheel: false,
				    streetViewControl: true,
						disableDefaultUI: true
				  };
				  
				  map = new google.maps.Map(document.getElementById('google-map'), mapOptions);
				  
				  <?php echo tt_mapMarkers(); ?>
  
					// https://developers.google.com/maps/documentation/javascript/geocoding
				  var address = '<?php echo $address; ?>';
				  geocoder = new google.maps.Geocoder();
				  
				  geocoder.geocode( { 'address': address}, function(results, status) {
				    if (status == google.maps.GeocoderStatus.OK) {
				     
				      map.setCenter(results[0].geometry.location);
				      var marker = new google.maps.Marker({
				          map: map,
				          position: results[0].geometry.location,
				          icon: customIcon
				      });
				      
				      var logo 		= '<?php echo $logo_src; ?>';
				      var address = '<?php echo $address; ?>';
				      var phone 	= '<?php echo $phone; ?>';
				      var mobile 	= '<?php echo $mobile; ?>';
				      var email 	= '<?php echo antispambot(  $email ); ?>';
				           
				      // http://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobox/docs/reference.html
							infobox = new InfoBox({
						  content: 	'<div class="map-marker-wrapper">'+
	    						'<div class="map-marker-container">'+
		    						'<div class="arrow-down"></div>'+
										<?php if ( $logo_src ) { ?>'<img src="'+logo+'" style="max-width:50%" />'+<?php } ?>
										'<div class="content">'+
										<?php if ( $address ) { ?>'<div class="contact-detail"><i class="fa fa-map-marker"></i>'+address+'</div>'+<?php } ?>
										<?php if ( $phone ) { ?>'<div class="contact-detail"><i class="fa fa-phone"></i>'+phone+'</div>'+<?php } ?>
										<?php if ( $mobile ) { ?>'<div class="contact-detail"><i class="fa fa-mobile"></i>'+mobile+'</div>'+<?php } ?>
										<?php if ( $email ) { ?>'<div class="contact-detail"><i class="fa fa-envelope"></i><a href="mailto:'+email+'">'+email+'</a></div>'+<?php } ?>
										'</div>'+
									'</div>'+
						    '</div>',
							  disableAutoPan: false,
							  pixelOffset: new google.maps.Size(-33, -90),
							  zIndex: null,
							  alignBottom: true,
							  closeBoxURL: "<?php echo TT_LIB_URI . '/images/close.png'; ?>",
							  infoBoxClearance: new google.maps.Size(60, 60)
							});
						
						  infobox.open(map, marker);
						  map.panTo(results[0].geometry.location);
						  
						  google.maps.event.addListener(marker, 'click', function() {					    	
					    	if ( infobox.getVisible() ) {
						    	infobox.hide();
					    	}
					    	else {
						    	infobox.show();
					    	}						    	
					    	infobox.open(map, marker);
								map.panTo(results[0].geometry.location);						      
							});
				      
				    } 
				    else {
				      alert("Geocode was not successful for the following reason: " + status);
				    }
				    
				  });
					
				}
				
				google.maps.event.addDomListener(window, 'load', initMap);
				google.maps.event.addDomListener(window, 'resize', initMap);
				
				</script>
				
				<div id="map-wrapper">
	
					<div class="container">
						
						<div id="map-controls">
							<a href="#" class="control" id="zoom-in" data-toggle="tooltip" title="<?php _e( 'Zoom In', 'tt' ); ?>"><i class="fa fa-plus"></i></a>
							<a href="#" class="control" id="zoom-out" data-toggle="tooltip" title="<?php _e( 'Zoom Out', 'tt' ); ?>"><i class="fa fa-minus"></i></a>
							<a href="#" class="control" id="map-type" data-toggle="tooltip" title="<?php _e( 'Map Type', 'tt' ); ?>">
								<i class="fa fa-image"></i>
								<ul class="list-unstyled">
									<li id="map-type-roadmap"><?php _e( 'Roadmap', 'tt' ); ?></li>
									<li id="map-type-satellite"><?php _e( 'Satellite', 'tt' ); ?></li>
									<li id="map-type-hybrid"><?php _e( 'Hybrid', 'tt' ); ?></li>
									<li id="map-type-terrain"><?php _e( 'Terrain', 'tt' ); ?></li>
								</ul>
							</a>
							<a href="#" class="control" id="current-location" data-toggle="tooltip" title="<?php _e( 'Radius: 1000m', 'tt' ); ?>"><i class="fa fa-crosshairs"></i><?php _e( 'Current Location', 'tt' ); ?></a>
						</div>
						
					</div>
						
					<div id="google-map">
						<div class="spinner">
						  <div class="bounce1"></div>
						  <div class="bounce2"></div>
						  <div class="bounce3"></div>
						</div>	
					</div>
					
				</div>
				
				<?php } // END IF Show Google Map
				else {
				?>
				
				<ul class="list-unstyled">
					<?php if ( $address ) { ?> <li class="contact-detail"><i class="fa fa-map-marker"></i><?php echo $address; } ?></li>
					<?php if ( $phone ) { ?> <li class="contact-detail"><i class="fa fa-phone"></i><?php echo $phone; } ?></li>
					<?php if ( $mobile ) { ?> <li class="contact-detail"><i class="fa fa-mobile"></i><?php echo $mobile; } ?></li>
					<?php if ( $email ) { ?> <li class="contact-detail"><i class="fa fa-envelope"></i><?php echo '<a href="mailto:' . antispambot( $email ) . '">' . antispambot( $email ) . '</a>'; } ?></li>
				</ul>
				
				<?php	} // END "Don't Show Noogle Map" ?>
				
				<?php the_content(); ?>
			</div>
		
		</div><!-- .col-sm-9 -->
		
		<?php 
		// Check for Page Sidebar
		if ( !$hide_sidebar && is_active_sidebar( 'sidebar_contact' ) ) : 
		?>
		<div class="col-sm-4 col-md-3">
			<ul id="sidebar">
				<?php dynamic_sidebar( 'sidebar_contact' ); ?>
			</ul>
		</div>
		<?php endif; ?>
	
	
	</div><!-- .row -->
	
<?php
endwhile;

get_footer(); 
?>