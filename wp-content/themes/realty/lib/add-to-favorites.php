<?php
/* AJAX - Favorites
============================== */
function tt_ajax_add_remove_favorites() {
	$user_id = $_GET['user'];
	$property_id = $_GET['property'];
			
	// Get Favorites Meta Data		
	$get_user_meta_favorites = get_user_meta( $user_id, 'realty_user_favorites', false ); // false = array()

	// No User Meta Data Favorites Found -> Add Data
	if ( !$get_user_meta_favorites ) {
		$create_favorites = array($property_id);
		add_user_meta( $user_id, 'realty_user_favorites', $create_favorites );		
	}
	// Meta Data Found -> Update Data
	else {
		// Add New Favorite
		if ( !in_array( $property_id, $get_user_meta_favorites[0] ) ) {
			array_unshift( $get_user_meta_favorites[0], $property_id ); // Add To Beginning Of Favorites Array
			update_user_meta( $user_id, 'realty_user_favorites', $get_user_meta_favorites[0] );		
		}
		// Remove Favorite
		else {
			$removeFavoriteFromPosition = array_search( $property_id, $get_user_meta_favorites[0] );
			unset($get_user_meta_favorites[0][$removeFavoriteFromPosition]);
			update_user_meta( $user_id, 'realty_user_favorites', $get_user_meta_favorites[0] );		
		}
	}
}
add_action('wp_ajax_tt_ajax_add_remove_favorites', 'tt_ajax_add_remove_favorites');


/* Favorites - Click
============================== */
if ( !function_exists('tt_add_remove_favorites') ) {
	function tt_add_remove_favorites() {
		
		global $realty_theme_option;
		
		if ( $realty_theme_option['property-favorites-disabled'] ) 
		return;
		
		$add_favorites_temporary = $realty_theme_option['property-favorites-temporary'];
	
		$property_id = get_the_ID();
		
		// Logged-In User
		if ( is_user_logged_in() ) {		
			$user_id = get_current_user_id();		
			$get_user_meta_favorites = get_user_meta( $user_id, 'realty_user_favorites', false ); // false = array()					
			
			// Property Is Already In Favorites
			if ( !empty( $get_user_meta_favorites ) && in_array( $property_id, $get_user_meta_favorites[0] ) ) {
				$favicon = '<i class="add-to-favorites fa fa-heart" data-fav-id="' . $property_id . '" data-toggle="tooltip" title="' . __( 'Remove From Favorites', 'tt' ) . '"></i>';	
			}
			// Property Isn't In Favorites
			else {
				$favicon = '<i class="add-to-favorites fa fa-heart-o" data-fav-id="' . $property_id . '" data-toggle="tooltip" title="' . __( 'Add To Favorites', 'tt' ) . '"></i>';
			}	
		}
		// Not Logged-In Visitor
		else {
			$favicon = '<i class="add-to-favorites fa fa-heart-o" data-fav-id="' . $property_id . '" data-toggle="tooltip" title="' . __( 'Add To Favorites', 'tt' ) . '"></i>';
		}
		
		return $favicon;
		
	}
}

/* Favorites - Script
============================== */
function tt_favorites_script() {

	global $realty_theme_option;
	$add_favorites_temporary = $realty_theme_option['property-favorites-temporary'];
	?>
	
	<script>		
	<?php 
	// Temporary Favorites
	if ( !is_user_logged_in() && $realty_theme_option['property-favorites-temporary'] ) { 
	?>
	jQuery('.add-to-favorites').each(function() {
		
		// Check If item Already In Favorites Array
		function inArray(needle, haystack) {
	    if ( haystack ) {    
		    var length = haystack.length;
		    for( var i = 0; i < length; i++ ) {
		      if(haystack[i] == needle) return true;
		    }
		    return false;
	    }
		}
		
		// Check If Browser Supports LocalStorage		
		if (!store.enabled) {
	    alert('<?php echo __( 'Local storage is not supported by your browser. Please disable "Private Mode", or upgrade to a modern browser.', 'tt' ); ?>');
			return;
	  }
		// Toggle Heart Class
		if ( inArray( jQuery(this).attr('data-fav-id'), store.get('favorites') ) ) {
			
			jQuery(this).toggleClass('fa-heart fa-heart-o');
			
			if ( jQuery(this).hasClass('fa-heart') ) {
				jQuery(this).attr('data-original-title', '<?php _e( 'Remove From Favorites', 'tt' ); ?>');
			}
			
		}
		
	});
	<?php } ?>
		
	jQuery('.container').on("click",'.add-to-favorites',function() {
		
		<?php 
		// Logged-In User Or Temporary Favorites Enabled
		if ( is_user_logged_in() || $add_favorites_temporary ) {
		?>
		
			// Toggle Favorites Tooltips
			if ( jQuery(this).hasClass('fa-heart') ) {
				jQuery(this).attr('data-original-title', '<?php _e( 'Remove From Favorites', 'tt' ); ?>');
			}
			
			jQuery(this).find('i').toggleClass('fa-heart fa-heart-o');
			jQuery(this).closest('i').toggleClass('fa-heart fa-heart-o');
			
			<?php 
			if ( is_user_logged_in() ) {
				$user_id = get_current_user_id();
				?>
				jQuery.ajax({			
				  type: 'GET',
				  url: ajaxURL,
				  data: {
				    'action'        :   'tt_ajax_add_remove_favorites', // WP Function
				    'user'					: 	<?php echo $user_id; ?>,
				    'property'			: 	jQuery(this).attr('data-fav-id')
				  },
				  success: function (response) { },
				  error: function () { }			  
				});
				<?php
			}
			
			else if ( $add_favorites_temporary ) { ?>
	
				if (!store.enabled) {
			    alert('<?php echo __( 'Local storage is not supported by your browser. Please disable "Private Mode", or upgrade to a modern browser.', 'tt' ); ?>');
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
					// Add New Favorite
					else {
						getFavs.push( newFav );
					}
					store.set( 'favorites', getFavs );
					
				}
				else {				
					var arrayFav = [];
					arrayFav.push( jQuery(this).attr('data-fav-id') );				
					store.set( 'favorites', arrayFav );				
				}
				
				console.log( store.get('favorites') );
				
			<?php 
			}
		
		}
		// Not Logged-In Visitor - Show Modal
		else {		
			?>
			jQuery('a[href="#tab-login"]').tab('show');
			jQuery('#login-modal').modal();
			jQuery('#msg-login-to-add-favorites').removeClass('hide');
			jQuery('#msg-login-to-add-favorites').addClass('hide');
			<?php	
		}
		?>
		
	});
	</script>
	
<?php
}
add_action( 'wp_footer', 'tt_favorites_script', 21 );


/* Favorites Temporary
============================== */
function tt_ajax_favorites_temporary() {

	$favorites_temporary_args['post_type'] = 'property';
	$favorites_temporary_args['post_status'] = 'publish';
	$favorites_temporary_args['paged'] = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	
	global $realty_theme_option;
	$search_results_per_page = $realty_theme_option['search-results-per-page'];
	
	// Search Results Per Page: Check for Theme Option
	if ( $search_results_per_page ) {
		$favorites_temporary_args['posts_per_page'] = $search_results_per_page;
	}
	else {
		$favorites_temporary_args['posts_per_page'] = 10;
	}
	
	if ( isset( $_GET['favorites'] ) ) {
		$favorites_temporary_args['post__in'] = $_GET['favorites'];
	}
	else {
		$favorites_temporary_args['post__in'] = array( '0' );
	}
	
	$query_favorites_temporary = new WP_Query( $favorites_temporary_args );
	
	if ( $query_favorites_temporary->have_posts() ) :
	
	echo '<div id="property-items"><ul class="row list-unstyled">';
	$count_results = $query_favorites_temporary->found_posts;
	// template-property-search.php
	?>
	<ul class="row list-unstyled">	
		<?php
		while ( $query_favorites_temporary->have_posts() ) : $query_favorites_temporary->the_post();
		global $realty_theme_option;
		$columns = $realty_theme_option['property-listing-columns'];
		if ( empty($columns) ) {
			$columns = "col-md-6";
		}
		?>
		<li class="<?php echo $columns; ?>">
			<?php get_template_part( 'lib/inc/template/property', 'item' );	?>
		</li>
		<?php endwhile; ?>
		
	</ul>
	<?php 
	wp_reset_query();
	
	echo '</ul></div>';
	endif;
	
	die();
	
}
add_action('wp_ajax_tt_ajax_favorites_temporary', 'tt_ajax_favorites_temporary');
add_action('wp_ajax_nopriv_tt_ajax_favorites_temporary', 'tt_ajax_favorites_temporary');