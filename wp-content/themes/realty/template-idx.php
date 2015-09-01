<?php get_header();
/*
Template Name: IDX
*/

$hide_sidebar = get_post_meta( $post->ID, 'estate_page_hide_sidebar', true );

while ( have_posts() ) : the_post(); 
?>
	</div><!-- .container -->
	<?php tt_page_banner();	?>	
	<div class="container">	

	<div class="row">
	
		<?php 
		// Check for Agent Sidebar
		if ( !$hide_sidebar && is_active_sidebar( 'sidebar_idx' ) ) {
			echo '<div class="col-sm-8 col-md-9">';
		} else {
			echo '<div class="col-sm-12">';
		}
		?>
		
			<div id="main-content" class="content-box">
				<h3 class="section-title"><span><?php the_title(); ?></span></h3>
				<?php the_content(); ?>
			</div>
		
		</div><!-- .col-sm-9 -->
		
		<?php 
		// Check for Page Sidebar
		if ( !$hide_sidebar && is_active_sidebar( 'sidebar_idx' ) ) : 
		?>
		<div class="col-sm-4 col-md-3">
			<ul id="sidebar">
				<?php dynamic_sidebar( 'sidebar_idx' ); ?>
			</ul>
		</div>
		<?php endif; ?>
	
	
	</div><!-- .row -->
	
<?php
endwhile;

get_footer(); 
?>