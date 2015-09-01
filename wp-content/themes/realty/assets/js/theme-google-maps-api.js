// Zoom In
if ( document.getElementById('zoom-in') ) {	
	google.maps.event.addDomListener(document.getElementById('zoom-in'), 'click', function () {      
	
		var currentZoom = map.getZoom();
		currentZoom++;
		if ( currentZoom > 19 ) {
			currentZoom = 19;
		}
		map.setZoom(currentZoom);
	
	}); 		 
}

// Zoom Out
if ( document.getElementById('zoom-out') ) {
	google.maps.event.addDomListener(document.getElementById('zoom-out'), 'click', function () {      
	
		var currentZoom = map.getZoom();
		currentZoom--;
		if ( currentZoom < 2 ) {
			currentZoom = 2;
		}
		map.setZoom(currentZoom);
	
	}); 		 
}

// Map Type: Roadmap
if ( document.getElementById('map-type-roadmap') ) {	
	google.maps.event.addDomListener(document.getElementById('map-type-roadmap'), 'click', function () {      
		map.setMapTypeId(google.maps.MapTypeId.ROADMAP);	
	}); 		 
}

// Map Type: Satellite
if ( document.getElementById('map-type-satellite') ) {	
	google.maps.event.addDomListener(document.getElementById('map-type-satellite'), 'click', function () {      
		map.setMapTypeId(google.maps.MapTypeId.SATELLITE);	
	}); 		 
}

// Map Type: Hybrid
if ( document.getElementById('map-type-hybrid') ) {	
	google.maps.event.addDomListener(document.getElementById('map-type-hybrid'), 'click', function () {      
		map.setMapTypeId(google.maps.MapTypeId.HYBRID);	
	}); 		 
}

// Map Type: Terrain
if ( document.getElementById('map-type-terrain') ) {	
	google.maps.event.addDomListener(document.getElementById('map-type-terrain'), 'click', function () {      
		map.setMapTypeId(google.maps.MapTypeId.TERRAIN);	
	}); 		 
}

jQuery('#map-type li').click(function() {
	jQuery('#map-type li').removeClass('active');
	jQuery(this).addClass('active');
});

// Geo Location: Current Location
function currentLocation() {
	
	// Geolocation - Current Location
	jQuery('#current-location').click(function() {
	
		// Current Location Marker
		var markerCurrent = new google.maps.Marker({
		  //clickable: false,
		  icon: new google.maps.MarkerImage('//maps.gstatic.com/mapfiles/mobile/mobileimgs2.png',
			  new google.maps.Size(22,22),
			  new google.maps.Point(0,18),
			  new google.maps.Point(11,11)),
		  shadow: null,
		  zIndex: null,
		  map: map
		});
	
		jQuery(this).toggleClass('active');
	
		if ( !jQuery('#current-location').hasClass('draw') ) {
		
			// Create Loading Element
		  var loading = document.createElement('div');
	
	    loading.setAttribute( 'id', 'loading' );
	
	    loading.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
	
	    document.getElementById('map-wrapper').appendChild(loading);
		    
		}
	
		if (navigator.geolocation) {
		
			navigator.geolocation.getCurrentPosition(function(current) {
		    var me = new google.maps.LatLng(current.coords.latitude, current.coords.longitude);
		    markerCurrent.setPosition(me);
				map.panTo(me);
				
				// Remove Loader
	    	loading.remove();
				
				// https://developers.google.com/maps/documentation/javascript/examples/circle-simple
				var currentRadiusCircleOptions = {
		      strokeColor: '#00CFF0',
		      strokeOpacity: 0.6,
		      strokeWeight: 2,
		      fillColor: '#00CFF0',
		      fillOpacity: 0.2,
		      map: map,
		      center: me,
		      visible: true,
		      radius: 1000 // Unit: meter
		    };
	    
	    // When Initializing
			if ( !jQuery('#current-location').hasClass('draw') ) {	
		    
		    // Create Circle
		    currentRadiusCircle = new google.maps.Circle(currentRadiusCircleOptions);
	  
			}
			
			jQuery('#current-location').addClass('draw');
			
			// Toggle Crrent Location Icon & Circle
			if ( jQuery('#current-location').hasClass('active') ) {
				markerCurrent.setMap(map);
				currentRadiusCircle.setMap(map);
			}
			else {
				markerCurrent.setMap(null);
				currentRadiusCircle.setMap(null);
			}
				
			});
			
		}
		// Error: Can't Retrieve Current Location
		else {
		  //alert("Current Position Not Found");
		}
		
		// Toggle Current Location Circle Visibility				
		google.maps.event.addListener(markerCurrent, 'click', (function() {		
			if ( currentRadiusCircle.getVisible() ) {
		  	currentRadiusCircle.set( 'visible', false );
			}
			else {
		  	currentRadiusCircle.set( 'visible', true );
			}		
		}));
		
	});

}

google.maps.event.addDomListener(window, 'load', currentLocation);