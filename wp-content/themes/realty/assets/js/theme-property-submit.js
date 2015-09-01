/* If User Selects "Other", Show New Taxonomy Input
-------------------------*/
jQuery('select#property-location').change(function() {
	if ( jQuery(this).val() == "other" ) {
		jQuery('#property-location-other').fadeIn();
	}
	else {
		jQuery('#property-location-other').fadeOut();
	}
});

jQuery('select#property-type').change(function() {
	if ( jQuery(this).val() == "other" ) {
		jQuery('#property-type-other').fadeIn();
	}
	else {
		jQuery('#property-type-other').fadeOut();
	}
});

jQuery('select#property-status').change(function() {
	if ( jQuery(this).val() == "other" ) {
		jQuery('#property-status-other').fadeIn();
	}
	else {
		jQuery('#property-status-other').fadeOut();
	}
});


/* Property Submit Validation
-------------------------*/
if ( jQuery().validate && jQuery().ajaxSubmit ) {
	
	jQuery('#property-submit').validate({
	
		ignore: ":hidden:not(select)", // To work with chosen.js
		
		// If "Other" Selected, Validate The New Inputs
		rules: {
		
			property_location_other: {
		    required: {
		      depends: function() {
		      	return jQuery("#property-location").val() == "other"
		      }
		    }
			},
			property_type_other: {
		    required: {
		      depends: function() {
		      	return jQuery("#property-type").val() == "other"
		      }
		    }
			},
			property_status_other: {
		    required: {
		      depends: function() {
		      	return jQuery("#property-status").val() == "other"
		      }
		    }
			}
		
		}
	
	});
	
}


/* Chosen.js Select Field Settings
-------------------------*/
jQuery('#property-submit select').chosen({
	disable_search: false,
	disable_search_threshold: 10,
	width: '100%'
});


/* Chosen.js Select Field Settings
http://stackoverflow.com/questions/18457340/how-to-preview-selected-image-in-input-type-file-in-popup-using-jquery 
-------------------------*/
function previewFeaturedImage( input ) {
  if ( input.files && input.files[0] ) {
    var reader = new FileReader();
    reader.onload = function (e) {
        jQuery('#preview-featured-image img').attr( 'src', e.target.result );
    }
    reader.readAsDataURL( input.files[0] );
  }
}

jQuery('#property-featured-image').change(function() {
	previewFeaturedImage(this);
});


/* Ajax - Property Submit - Delete Uploaded Image
-------------------------*/
jQuery('.delete-uploaded-image').click(function() {

	var galleryListElement = jQuery(this).closest('li');
	
	jQuery.ajax({
    
    type: 'POST',
    url: ajaxURL,
    data: {
	    'action'          :   'tt_ajax_delete_uploaded_image_function', // WP Function
	    'property_id'    	:   jQuery(this).attr('data-property-id'),
	    'image_id'      	:   jQuery(this).attr('data-image-id'),
    },
    success: function (response) {
			galleryListElement.remove();
    },
    error: function (response) {
    	// Error Message
    }
    
  });
	
});


// Delete Floor Plan	
function deleteFloorPlan() {
	jQuery('.delete-floor-plan').click(function() {
		var countFloorPlans = jQuery('.floor-plan-item').length;
		//if ( countFloorPlans > 1 ) {
			jQuery(this).closest('.floor-plan-item').remove();
		//}
	});
}

deleteFloorPlan();

// Add Floor Plan
jQuery('.add-floor-plan').click(function() {
	floorPlanGroup = jQuery('.floor-plan-item').html();
	jQuery('#floor-plan').append('<div class="floor-plan-item row">'+floorPlanGroup+'</div>');
	deleteFloorPlan();
});