<?php
/* Google Maps API - Multiple Properties
============================== */
function tt_google_maps_api_multiple_properties() {

// Check For Property Search Template 
if ( is_page_template( 'template-property-search.php' ) ) {
	// Build Property Search Query
	$query_properties_args = array();
	$query_properties_args = apply_filters( 'property_search_args', $query_properties_args );
	$query_properties_args['posts_per_page'] = -1;
}

else {

	global $post, $realty_theme_option;	
	$properties_homepage_quantity = intval( $realty_theme_option['map-properties-quantity'] );
	
	// Property Map Settings
	$property_map_location = get_post_meta( $post->ID, 'estate_property_map_location', false );
	$property_map_status = get_post_meta( $post->ID, 'estate_property_map_status', false );
	$property_map_type = get_post_meta( $post->ID, 'estate_property_map_type', false );
	$property_map_custom_zoom_level = get_post_meta( $post->ID, 'estate_property_map_custom_zoom_level', true );
	 
	if( !$properties_homepage_quantity ) {
		$properties_homepage_quantity = -1;
	}
		
	// Property Query
	if ( is_front_page() ) {
		$paged = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;
	}
	else {
		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	}
	
	$query_properties_args = array(
		'post_type' 			=> 'property',
		'posts_per_page' 	=> $properties_homepage_quantity,
		'paged' 					=> $paged
	);
	
	$tax_query = array();
	
	if ( $property_map_location ) {
		$tax_query[] = array(
			'taxonomy' => 'property-location',
			'field'    => 'id',
			'terms'			=> $property_map_location
		);
	}
	
	if ( $property_map_status ) {
		$tax_query[] = array(
			'taxonomy' => 'property-status',
			'field'    => 'id',
			'terms'			=> $property_map_status
		);
	}
	
	if ( $property_map_type ) {
		$tax_query[] = array(
			'taxonomy' => 'property-type',
			'field'    => 'id',
			'terms'			=> $property_map_type
		);
	}
	
	// Count Taxonomy Queries + set their relation for property query
	$tax_count = count( $tax_query );
	if ( $tax_count > 1 ) {
		$tax_query['relation'] = 'AND';
	}
	
	if ( $tax_count > 0 ) {
		$query_properties_args['tax_query'] = $tax_query;
	}
	
}

$query_properties = new WP_Query( $query_properties_args );

//if ( $query_properties->have_posts() ) : 

$property_string = '';
$i = 0;

while ( $query_properties->have_posts() ) : $query_properties->the_post();
global $post;
$google_maps = get_post_meta( $post->ID, 'estate_property_location', true );

// Check For Map Coordinates
if ( $google_maps ) {	
	
	$coordinate = explode( ',', $google_maps );	
	$property_string .= '{ ';	
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
?>
<script>
<?php
// No Properties Found - Hide Map
if ( $i == 0 ) { ?>
jQuery('#map-wrapper').hide();
<?php } ?>
var map = null, markers = [], newMarkers = [], markerCluster = null, bounds = [], infobox = [];

<?php echo tt_mapMarkers(); ?>

var	mapOptions = {
	center: new google.maps.LatLng(0, 0),
	zoom: 14,
	scrollwheel: false,
	streetViewControl: true,
	disableDefaultUI: true,
};

function initMap() {
	
	map = new google.maps.Map(document.getElementById("google-map"), mapOptions);
	
	bounds = new google.maps.LatLngBounds();
	
	markers = initMarkers(map, [ <?php echo $property_string; ?> ]);
	
	markerCluster = new MarkerClusterer(map, newMarkers, markerClusterOptions);

	// Maps Fully Loaded: Hide + Remove Spinner
	google.maps.event.addListenerOnce(map, 'idle', function() {
		jQuery('.spinner').fadeTo(800, 0.5);
		setTimeout(function() {
		  jQuery('.spinner').remove();
		}, 800);
	});
	
	// Spiderfier
	var oms = new OverlappingMarkerSpiderfier(map, { markersWontMove: true, markersWontHide: true, keepSpiderfied: true, legWeight: 5 });
	
	function omsMarkers( markers ) {
	  for ( var i = 0; i < markers.length; i++ ) {
	  	oms.addMarker( markers[i] );
	  }   
	}
	
	omsMarkers(markers);
		
}

google.maps.event.addDomListener(window, 'load', initMap);
//google.maps.event.addDomListener(window, 'resize', initMap);

function initMarkers(map, markerData) {
	    
	for( var i = 0; i < markerData.length; i++ ) {
		
		marker = new google.maps.Marker({
	    map: map,
	    position: markerData[i].latLng,
			icon: customIcon,
	    //animation: google.maps.Animation.DROP
		}),
		
		bounds.extend(markerData[i].latLng);
	  
		infoboxOptions = {
	    content: 	'<div class="map-marker-wrapper">'+
	    						'<div class="map-marker-container">'+
		    						'<div class="arrow-down"></div>'+
										'<img src="'+markerData[i].thumbnail+'" />'+
										'<div class="content">'+
										'<a href="'+markerData[i].permalink+'">'+
										'<h5 class="title">'+markerData[i].title+'</h5>'+
										'</a>'+
										markerData[i].price+
										'</div>'+
									'</div>'+
						    '</div>',
	    disableAutoPan: false,
		  pixelOffset: new google.maps.Size(-33, -90),
		  zIndex: null,
		  isHidden: true,
		  alignBottom: true,
		  closeBoxURL: "<?php echo TT_LIB_URI . '/images/close.png'; ?>",
		  infoBoxClearance: new google.maps.Size(25, 25)
		};
		
		newMarkers.push(marker);
	
		newMarkers[i].infobox = new InfoBox(infoboxOptions);
		newMarkers[i].infobox.open(map, marker);
	
		google.maps.event.addListener(marker, 'click', (function(marker, i) {
			
	    return function() {
				
	    	if ( newMarkers[i].infobox.getVisible() ) {
		    	newMarkers[i].infobox.setVisible(false);
	    	}
	    	else {
		    	jQuery('.infoBox').hide();
		    	newMarkers[i].infobox.setVisible(true);
	    	}	    	
	    	
	    	newMarkers[i].infobox.open(map, this);
	      map.panTo(markerData[i].latLng);
	      
	    }
	    
		})( marker, i ) );
		
		google.maps.event.addListener(map, 'click', function() {
	    jQuery('.infoBox').hide();
	  });
		
	}
	
	// Set Map Bounds And Max. Zoom Level
	google.maps.event.addListenerOnce(map, 'bounds_changed', function(event) {
		map.fitBounds(bounds);
		<?php if ( isset( $property_map_custom_zoom_level ) && !empty( $property_map_custom_zoom_level ) ) { ?>
		this.setZoom(<?php echo $property_map_custom_zoom_level; ?>);
		<?php 
		} 
		else { ?>
		if (this.getZoom() > 13) {
	    this.setZoom(13);
	  }
	  <?php } ?>
	});
	
	return newMarkers;
	
} // initMarkers();

</script>

<?php
}
add_action( 'wp_footer', 'tt_google_maps_api_multiple_properties', 20 );
?>

<div id="map-wrapper">		
	
	<div id="google-map" <?php global $realty_theme_option; if ( $realty_theme_option['map-height-type-home-search'] == "fullscreen" ) { echo 'class="fullscreen"'; } ?>></div>
	
	<div class="spinner">
	  <div class="bounce1"></div>
	  <div class="bounce2"></div>
	  <div class="bounce3"></div>
	</div>
	
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
	
	<div class="container">		
		<div id="map-overlay-no-results">
			<?php _e( 'No Properties Found.', 'tt' ); ?>
		</div>
	</div>
		
</div>
<?php 
wp_reset_query();
//else:
?>

<div id="map-no-properties-found" class="hide">
	<p class="lead text-center"><?php _e( 'No Properties Found.', 'tt' ) ?></p>
</div>
<?php
//endif;
?>