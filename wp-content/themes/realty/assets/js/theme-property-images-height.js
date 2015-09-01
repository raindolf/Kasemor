//jQuery(window).load(function() {

/* Layout "Full Width" - Property Image Height
-------------------------*/
function layoutFullWidth() {
	jQuery('#property-layout-full-width').each(function() {
		
		var singlePropertyImageContainerHeight = windowHeight - navHeight - 150;
		jQuery(this).find('.property-image-container').height( singlePropertyImageContainerHeight );
		jQuery('.flexslider-thumbnail').height( singlePropertyImageContainerHeight );
		
		// Set Property Image Offset To Vertically Center It
		jQuery(this).find('img.property-image').each(function() {
			var singlePropertyImageHeight = jQuery(this).height();
			var singlePropertyImageOffset = ( singlePropertyImageHeight - singlePropertyImageContainerHeight ) / 2;
			jQuery(this).css( 'bottom', singlePropertyImageOffset );

		});
		
	});

}
		
/* Layout "Boxed" - Property Image Height
-------------------------*/	
function layoutBoxed() {
	jQuery('#property-layout-boxed').each(function() {
	
		var singlePropertyImageContainerHeight = windowHeight - navHeight - 150;
		jQuery(this).find('.property-image-container').height( singlePropertyImageContainerHeight );
		
		var propertyHeaderHeight = jQuery('.property-header').height();
		var singlePropertyImageContainerBoxedHeight = windowHeight - navHeight -150;
		jQuery(this).find('.property-image-container').height( singlePropertyImageContainerBoxedHeight );			
		jQuery('.flexslider-thumbnail').height( singlePropertyImageContainerBoxedHeight );
		
		// Set Property Image Offset To Vertically Center It
		jQuery(this).find('img.property-image').each(function() {
			var singlePropertyImageHeight = jQuery(this).height();
			var singlePropertyImageOffset = ( singlePropertyImageHeight - singlePropertyImageContainerBoxedHeight ) / 2;
			jQuery(this).css( 'bottom', singlePropertyImageOffset );
			
		});   
		
	});
}

// Only Run Property Image Resize On A Min. Width of 1200px	& Desktops
if ( windowWidth >= 1200 && !isMobile ) {
	
	layoutFullWidth();
	layoutBoxed();
	
	
	jQuery(window).on("throttledresize", function( event ) {
  	layoutFullWidth();
		layoutBoxed();
	});
	
	jQuery(window).trigger( "throttledresize" );
	
}

//});