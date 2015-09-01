<?php get_header();
/*
Template Name: Property Comparison
*/

global $realty_theme_option;
$add_favorites_temporary = $realty_theme_option['property-favorites-temporary'];

$hide_sidebar = get_post_meta( $post->ID, 'estate_page_hide_sidebar', true );

while ( have_posts() ) : the_post(); 
?>
	</div><!-- .container -->
	<?php tt_page_banner();	?>	
	<div id="page-property-comparison" class="container">	

	<div class="row">
	
		<?php 
		// Check for Agent Sidebar
		if ( !$hide_sidebar && is_active_sidebar( 'sidebar_page' ) ) {
			echo '<div class="col-sm-8 col-md-9">';
		} else {
			echo '<div class="col-sm-12">';
		}
		?>
		
			<div id="main-content" class="content-box">
				<?php 
				the_content(); 
				echo '<p id="msg-no-properties" class="hide">' . __( 'You haven\'t added any properties to compare.', 'tt' ) . '</p>';
				?>
				<div id="property-comparison-table"></div>
			</div>
		
		</div><!-- .col-sm-9 -->
		
		<?php 
		// Check for Page Sidebar
		if ( !$hide_sidebar && is_active_sidebar( 'sidebar_page' ) ) : 
		?>
		<div class="col-sm-4 col-md-3">
			<ul id="sidebar">
				<?php dynamic_sidebar( 'sidebar_page' ); ?>
			</ul>
		</div>
		<?php endif; ?>
	
	
	</div><!-- .row -->
	
	<script>	
	jQuery(window).load(function() {

		var properties;
		properties = store.get('comparison');
						
		jQuery.ajax({
		
		  type: 'GET',
		  url: ajaxURL,
		  data: {
		    'action'          :   'tt_ajax_property_comparison', // WP Function
		    'properties'      :   properties
		  },
		  success: function (response) {
		  
		  	// If Temporary Favorites Found, Show Them
		  	if ( store.get('comparison') != "" ) {
		  	
		  		jQuery('#property-comparison-table').html(response);  
		  		
		  		if ( properties.length == 3 ) {
						jQuery('.comparison-data').addClass('columns-3');
					}
					else if ( properties.length == 4 ) {
						jQuery('.comparison-data').addClass('columns-4');
					}
					else {
						jQuery('.comparison-data').addClass('columns-2');
					}  
			    			    
			    jQuery('.compare-property').attr('title', 'Remove');
			    jQuery('.compare-property').toggleClass('fa-plus fa-minus');
			    jQuery('i').tooltip();
			    
			    jQuery('.compare-property').click(function() {
			        
						jQuery(this).closest('li').fadeOut(400, function() { 
							jQuery(this).remove();
							var numberOfFavorites = jQuery('.property-item').length;
							if ( numberOfFavorites == 0 ) {
								jQuery('#msg-no-properties').toggleClass('hide');
							}
						});
			
						// Check If Browser Supports LocalStorage			
						if (!store.enabled) {
					    throw new Error("<?php echo __( 'Local storage is not supported by your browser. Please disable \"Private Mode\", or upgrade to a modern browser.', 'tt' ); ?>");
					  }
					  
						// Check For Proeprties To Compare (store.js plugin)
						if ( store.get('comparison') ) {
						
							// Check If item Already In Comparison Array
							function inArray(needle, haystack) {
						    var length = haystack.length;
						    for( var i = 0; i < length; i++ ) {
					        if(haystack[i] == needle) return true;
						    }
						    return false;
							}
							
							var getComparisonAll = store.get('comparison');
							var propertyToCompare = jQuery(this).attr('data-compare-id');
				
							// Remove Property From Comparison
							if ( inArray( propertyToCompare, getComparisonAll ) ) {
								var index = getComparisonAll.indexOf(propertyToCompare);
								getComparisonAll.splice(index, 1);
								location.reload();
							}
							
							store.set( 'comparison', getComparisonAll );
							
						}
								
					});
				
				}
				
				else {
					jQuery('#msg-no-properties').toggleClass('hide');
				}
				
				console.log( store.get('comparison') );
		    
		  },
		  error: function () {
		    console.log("no properties to compare");
		  }
		  
		});
	
	});
	</script>
	
<?php
endwhile;

get_footer(); 
?>