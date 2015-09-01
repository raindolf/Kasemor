<?php 
get_header(); 
global $wp_query, $realty_theme_option;

$listing_view = $realty_theme_option['property-listing-default-view'];

$grid_active = "active";
$list_active = "";

if ( $listing_view == "list-view" ) {
	$grid_active = "";
	$list_active = "active";
}
?>

<div class="taxonomy-results">

	<div class="search-results-header clearfix">
	
		<h2 class="page-title">
			<?php 
			if ( is_tax( 'property-location' ) ) {
				echo __( 'Location:', 'tt' ) . ' ' . str_replace( '-', ' ', get_queried_object()->name );
			}
			if ( is_tax( 'property-status' ) ) {
				echo __( 'Status:', 'tt' ) . ' ' . str_replace( '-', ' ', get_queried_object()->name );
			}
			if ( is_tax( 'property-type' ) ) {
				echo __( 'Type:', 'tt' ) . ' ' . str_replace( '-', ' ', get_queried_object()->name );
			}
			if ( is_tax( 'property-features' ) ) {
				echo __( 'Feature:', 'tt' ) . ' ' . str_replace( '-', ' ', get_queried_object()->name );
			}
			echo ' (' . $wp_query->found_posts . ')';
			?>
		</h2>
		
		<div class="taxonomy-description">
			<?php echo term_description(); ?>
		</div>
		
		<?php get_template_part( 'lib/inc/template/property', 'comparison' ); ?>
	
		<div class="search-results-view">
			<i class="fa fa-th-large <?php echo $grid_active; ?>" data-view="grid-view" data-toggle="tooltip" title="<?php _e( 'Grid View', 'tt' ); ?>"></i>
			<i class="fa fa-th-list <?php echo $list_active; ?>" data-view="list-view" data-toggle="tooltip" title="<?php _e( 'List View', 'tt' ); ?>"></i>
		</div>
		
	</div>
	
	<?php if ( have_posts() ) : ?>
	<div id="property-search-results" data-view="<?php echo $listing_view; ?>">
	
		<div id="property-items" class="show-compare">
			
			<ul class="row list-unstyled">
				<?php
				while ( have_posts() ) : the_post(); 
				
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
			
			<div id="pagination">
			<?php
			// Built Property Pagination
			$big = 999999999; // need an unlikely integer
			$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
			
			echo paginate_links( array(
				'base' 				=> str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format' 			=> '?page=%#%',
				'total' 			=> $wp_query->max_num_pages,
				'show_all'		=> true,
				'type'				=> 'list',
				'current'     => $paged,
				'prev_text' 	=> __( '<i class="btn btn-default fa fa-angle-left"></i>', 'tt' ),
				'next_text' 	=> __( '<i class="btn btn-default fa fa-angle-right"></i>', 'tt' ),
			) );
			?>
			</div>
			
			<?php
			else : ?>
			<p class="lead text-center text-muted"><?php _e( 'Nothing Matches Your Criteria.', 'tt' ); ?></p>
			<?php
			endif;
			?>
			
		</div>
	</div>
		
</div>

<?php get_footer(); ?>