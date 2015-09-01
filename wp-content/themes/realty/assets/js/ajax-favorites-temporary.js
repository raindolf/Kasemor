/* Favorites Temporary
-------------------------*/ 
jQuery(window).load(function() {

	var favorites;
	favorites = store.get('favorites');
					
	jQuery.ajax({
	
	  type: 'GET',
	  url: ajaxURL,
	  data: {
	    'action'          :   'tt_ajax_favorites_temporary', // WP Function
	    'favorites'       :   favorites
	  },
	  success: function (response) {
	  
	  	// If Temporary Favorites Found, Show Them
	  	if ( store.get('favorites') != "" ) {
	  	
	  		jQuery('#favorites-temporary').html(response);    
		    
		    if ( jQuery('body').hasClass('page-template-template-user-favorites-php') ) {
		    
		    jQuery('.add-to-favorites').attr('title', '');
		    jQuery('.add-to-favorites').toggleClass('fa-heart fa-heart-o');
		    jQuery('i').tooltip();
		    
		    jQuery('.add-to-favorites').click(function() {
		        
					jQuery(this).closest('li').fadeOut(400, function() { 
						jQuery(this).remove();
						var numberOfFavorites = jQuery('.property-item').length;
						if ( numberOfFavorites == 0 ) {
							jQuery('#msg-no-favorites').toggleClass('hide');
						}
					});
		
					// Check If Browser Supports LocalStorage			
					if (!store.enabled) {
				    alert('Local storage is not supported by your browser. Please disable "Private Mode", or upgrade to a modern browser.');
						return;
				  }
					// Check For Temporary Favorites (store.js plugin)
					if ( store.get('favorites') ) {
					
						// Check If item Already In Favorites Array
						function inArray(needle, haystack) {
					    var length = haystack.length;
					    for( var i = 0; i < length; i++ ) {
				        if(haystack[i] == needle) return true;
					    }
					    return false;
						}
						
						var getFavs = store.get('favorites');
						var newFav = jQuery(this).attr('data-fav-id');
			
						// Remove Old Favorite
						if ( inArray( newFav, getFavs ) ) {
							var index = getFavs.indexOf(newFav);
							getFavs.splice(index, 1);
						}
						
						store.set( 'favorites', getFavs );
						
					}
							
				});
				
				}
			
			}
	    
	  },
	  error: function () {
	    console.log("no favorites");
	  }
	  
	});

});