<?php 
get_header();
$page_id = get_option('page_for_posts'); 
$hide_sidebar = get_post_meta( $page_id, 'estate_page_hide_sidebar', true );

$post_thumbnail_id = get_post_thumbnail_id( $page_id );
$post_thumbnail = wp_get_attachment_image_src( $post_thumbnail_id, 'full', true );
$post_thumbnail_url = $post_thumbnail[0];

if ( !empty($post_thumbnail_id) ) {
?>
</div><!-- .container -->
<div id="page-banner" style="background-image: url(<?php echo $post_thumbnail_url; ?>)">
	<div class="overlay"></div>
	<div class="container">
		<div class="banner-title">
			<?php echo get_the_title( $page_id ); ?>
		</div>
	</div>
</div>
<div class="container">
<?php }
// Page Content
if ( !is_front_page() ) {
	$get_page = get_page( $page_id );
	echo '<article class="blog-index-content">' . do_shortcode( $get_page->post_content ) . '</article>';
}
?>

<div class="row">

	<?php 
	// Check for Property Sidebar
	if ( !$hide_sidebar && is_active_sidebar( 'sidebar_blog' ) ) {
		echo '<div class="col-sm-8 col-md-9">';
	} else {
		echo '<div class="col-sm-12">';
	}
	?>
	
	<?php
	if ( have_posts() ) :
	
	if ( is_archive() ) { ?>
		<h2>
		<?php if ( is_author() ) :
		printf( __( 'All posts by: %s', 'tt' ), get_the_author() );
		elseif ( is_tag() ) : printf( __( 'Tag: %s', 'tt' ), single_tag_title( '', false ) );
		elseif (is_category() ) : printf( __( 'Category: %s', 'tt' ), single_cat_title( '', false ) );
		elseif ( is_date() ) :
			if ( is_day() ) :
				printf( __( 'Daily Archives: %s', 'tt' ), get_the_date() );
		
			elseif ( is_month() ) :
				printf( __( 'Monthly Archives: %s', 'tt' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'tt' ) ) );
		
			elseif ( is_year() ) :
				printf( __( 'Yearly Archives: %s', 'tt' ), get_the_date( _x( 'Y', 'yearly archives date format', 'tt' ) ) );
			else :
				_e( 'Archives', 'tt' );
			endif;
		endif;
	?>
	</h2>
	<?php } ?>
	
	<?php 
	if ( is_search() ) { ?>
		<h2 class="blog-page-title"><?php printf( __( 'Search Results for: %s', 'tt' ), get_search_query() ); ?></h2>
	<?php } ?>	
		
	<?php
	while ( have_posts() ) : the_post();		
		get_template_part( 'content', get_post_format() );
	endwhile;
	
	else :
	echo '<p class="lead text-muted">' . __( 'No posts found.', 'tt' ) . '</p>';
	endif;
	
	tt_blog_pagination();
	?>
	</div>
	
	<?php 
	// Check for Property Sidebar
	if ( !$hide_sidebar && is_active_sidebar( 'sidebar_blog' ) ) : 
	?>
	<div class="col-sm-4 col-md-3">
	<?php get_sidebar(); ?>
	</div>
	<?php endif; ?>
	
</div>
<?php get_footer(); ?>