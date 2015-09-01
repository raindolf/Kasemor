<?php 
/*
Template Name: Home - Property Map
*/
get_header();

$hide_sidebar = get_post_meta( $post->ID, 'estate_page_hide_sidebar', true );

get_template_part( 'lib/inc/template/google-map-multiple-properties' );
?>

<div class="container">
	
	<div class="row">
	
		<?php 
		// Check for Page Sidebar
		if ( !$hide_sidebar && is_active_sidebar( 'sidebar_page' ) ) {
			echo '<div class="col-sm-8 col-md-9">';
		} else {
			echo '<div class="col-sm-12">';
		}
		
		the_content();
		?>
		
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
	
</div><!-- .container -->

<?php get_footer(); ?>