/* Ajax - Search Results
-------------------------*/
jQuery(window).load(function() {

	
	// Delete Property
	jQuery('.delete-property').click(function(e) {
		
		e.preventDefault();
		
	  jQuery.ajax({
	    
	    type: 'GET',
	    url: ajaxURL,
	    data: {
		    'action'          :   'tt_ajax_delete_property_function', // WP Function
		    'delete_property' : 	jQuery(this).attr('data-property-id')
	    },
	    success: function (response) {
		    //console.log('deleted');					
	    },
	    error: function () {
	    	//console.log('failed');
	    }
	    
	  });
	  
	  jQuery(this).closest('li').fadeOut(400, function() { 
			jQuery(this).remove();
		});
		
	});
	
});