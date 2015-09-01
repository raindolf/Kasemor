jQuery(document).ready(function(jQuery) {
	
	// Toggle Metaboxes based on selected Post Format		
	jQuery('#post-formats-select input').change(changeFormat);
	
	function changeFormat() {
		var postFormat = jQuery('#post-formats-select input:checked').attr('value');		
		// Run Only On Posts
		if( typeof postFormat != 'undefined' ) {			
			// Hide all Post Metaboxes by Default
			jQuery('.postbox-container div[id^=post_type_]').fadeOut();
			// Show Metabox that matches the selected Post Format
			jQuery('.postbox-container #post_type_' + postFormat + '').fadeIn();					
		}
	}
	
	// Page Template Change
	jQuery('#page_template').change(changeTemplate);
	
	function changeTemplate() {
	
		var pageTemplate = jQuery('#page_template option:selected').attr('value');
		
		// Intro
		if( pageTemplate != 'template-intro.php' ) {			
			jQuery('.intro-only').fadeOut();
		}
		else {
			jQuery('.intro-only').fadeIn();
		}
		
		// Property - Slideshow
		if( pageTemplate != 'template-property-slideshow.php' ) {			
			jQuery('#slideshow_settings').fadeOut();
		}
		else {
			jQuery('#slideshow_settings').fadeIn();
		}
		
		// Property - Map
		if( pageTemplate != 'template-home-properties-map.php' ) {			
			jQuery('#property_map_settings').fadeOut();
		}
		else {
			jQuery('#property_map_settings').fadeIn();
		}
		
	}
	
	// Floor Plan Image Upload
	function uploadFloorPlan() {
		
		// https://codestag.com/how-to-use-wordpress-3-5-media-uploader-in-theme-options/
		var _custom_media = true,
		_orig_send_attachment = wp.media.editor.send.attachment;
	 
		jQuery('.floor-plan-item .button.upload').click(function(e) {
			var send_attachment_bkp = wp.media.editor.send.attachment;
			var button = jQuery(this);
			var id = button.attr('id').replace('button_', '');
			_custom_media = true;
			wp.media.editor.send.attachment = function(props, attachment) {
				if ( _custom_media ) {
					button.parent().find('[name*=estate_floor_plan_image]').val(attachment.id);
					button.parent().find('img').val( attachment.url );
					button.parent().find('img').attr( 'src', attachment.url );
				} else {
					return _orig_send_attachment.apply( this, [props, attachment] );
				};
			}
	 
			wp.media.editor.open(button);
			return false;
		});

		jQuery('.add_media').on('click', function(){
			_custom_media = false;
		});
	
	}
	
	// Delete Floor Plan	
	function deleteFloorPlan() {
		jQuery('.delete-floor-plan').click(function() {
			var countFloorPlans = jQuery('.floor-plan-item').length;
			//if ( countFloorPlans > 1 ) {
				jQuery(this).closest('.floor-plan-item').remove();
			//}
		});
	}
	
	uploadFloorPlan();
	deleteFloorPlan();
	
	// Add Floor Plan
	jQuery('.add-floor-plan').click(function() {
		floorPlanGroup = jQuery('.floor-plan-item').html();
		jQuery('#floor-plan-container').append('<div class="floor-plan-item">'+floorPlanGroup+'</div>');
		uploadFloorPlan();
		deleteFloorPlan();
	});
	
	// Window Load
	jQuery(window).load(function() {

		changeFormat();
		jQuery('.intro-only, #slideshow_settings, #property_map_settings').fadeOut();	// Hide Custom Meta Boxes Initially	
		changeTemplate();
		
	});
		    
});