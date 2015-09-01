<?php get_header();
/*
Template Name: User - Favorites
*/

global $realty_theme_option;
$add_favorites_temporary = $realty_theme_option['property-favorites-temporary'];

$hide_sidebar = get_post_meta( $post->ID, 'estate_page_hide_sidebar', true );

while ( have_posts() ) : the_post(); 
?>
	</div><!-- .container -->
	<?php tt_page_banner();	?>	
	<div id="page-user-favorites" class="container">	

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
								
				if ( is_user_logged_in() ) {
				
					$user_id = get_current_user_id();
					
					$get_user_meta_favorites = get_user_meta( $user_id, 'realty_user_favorites', false ); // false = array()
					
					// Check For Favorites
					if ( !$get_user_meta_favorites ) {
						$number_of_favorites = 0;
					}
					else {
						$number_of_favorites = count( $get_user_meta_favorites[0] );
					}
										
					if ( $number_of_favorites > 0 ) {
	
						$favorites_args = array(
	            'post_type' => 'property',
	            'post__in' => $get_user_meta_favorites[0],
	            'posts_per_page' => $number_of_favorites,
	            'orderby' => 'post__in'
            );
            
            $favorites_query = new WP_Query( $favorites_args );
            
            
            if ( $favorites_query->have_posts() ) : ;
            
            echo '<div id="property-items"><ul class="row list-unstyled">';
            
            global $realty_theme_option;
						$columns_theme_option = $realty_theme_option['property-listing-columns'];
						
            while ( $favorites_query->have_posts() ) : $favorites_query->the_post();
            
						if ( empty($columns_theme_option) ) {
							echo '<li class="col-md-6">';
						}
						else {
							if ( $columns_theme_option == "col-md-6" ) {
								echo '<li class="col-md-6">';
							}
							else if ( $columns_theme_option == "col-lg-4 col-md-6" ) {
								echo '<li class="col-lg-4 col-md-6">';
							}
							else if ( $columns_theme_option == "col-lg-3 col-md-6" ) {
								echo '<li class="col-lg-3 col-md-6">';
							}
						}
            
	          	get_template_part( 'lib/inc/template/property', 'item' );
            
            echo '</li>';
            
            endwhile;
            wp_reset_query();
            
            echo '</ul></div>';
            endif;
						
					}
					// No Favorites Saved
					else {
						echo "<p>" . __( 'You haven\'t added any favorites.', 'tt' ) . "</p>";
					}
					
					echo '<p id="msg-no-favorites" class="hide">' . __( 'You haven\'t added any favorites.', 'tt' ) . '</p>';
					
				}
				else {
					// Temporary Favorites
					if ( $add_favorites_temporary ) {					
						echo '<div id="favorites-temporary"></div>';
					} else {
						_e( 'To view your favorites you have to login or create an account.', 'tt' );
					}
				}
				
				?>
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
	jQuery('.add-to-favorites').click(function() {
		jQuery(this).closest('li').fadeOut(400, function() { 
			jQuery(this).remove();
			var numberOfFavorites = jQuery('.property-item').length;
			if ( numberOfFavorites == 0 ) {
				jQuery('#msg-no-favorites').toggleClass('hide');
			}
		});
	});
	</script>
	
<?php
endwhile;

get_footer(); 
?>