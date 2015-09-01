<?php 
get_header(); 
global $post;
$hide_sidebar = get_post_meta( $post->ID, 'estate_page_hide_sidebar', true );
?>
<div class="row">

	<?php 
	// Check for Property Sidebar
	if ( !$hide_sidebar && is_active_sidebar( 'sidebar_blog' ) ) {
		echo '<div class="col-sm-9">';
	} else {
		echo '<div class="col-sm-12">';
	}
	
	while ( have_posts() ) : the_post();				
		get_template_part( 'content', get_post_format() );
	endwhile;
	
	// Previous / Next Post Navigation
	$previousPost = get_adjacent_post();
	$nextPost = get_adjacent_post( false, "", false );	
	
	if ( $previousPost || $nextPost ) { ?>
	<div id="blog-prev-next-post">
		<div class="row">
			<?php	if ( $previousPost ) {
				$previous_attachment_id =  get_post_thumbnail_id( $previousPost->ID ) ;
				?>
		    <div class="prev-post <?php if ( $nextPost ) { echo "col-sm-6"; } else { echo "col-sm-12"; } ?>">
			    <a href="<?php echo get_permalink( $previousPost->ID )?>">
			    	
			    	<div class="text-muted"><?php _e( 'Previous read:', 'tt'); ?></div>
			    	<h5 class="title"><?php echo $previousPost->post_title; ?></h5>
			    	
			    </a>
		    </div>
				<?php }
					
				if ( $nextPost ) {
				$next_attachment_id =  get_post_thumbnail_id( $nextPost->ID );
				?>
		    <div class="next-post <?php if ( $previousPost ) { echo "col-sm-6 text-right"; } else { echo "col-sm-12"; } ?>">
			    <a href="<?php echo get_permalink( $nextPost->ID )?>">
			    	
			    	<div class="text-muted"><?php _e( 'Next read:', 'tt'); ?></div>
			    	<h5 class="title"><?php echo $nextPost->post_title; ?></h5>
						
			    </a>
		    </div>
				<?php } ?>	
		</div>
	</div><!-- #blog-prev-next-post -->
	<?php
	}
	
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
	?>
	</div>
	
	<?php 
	// Check for Property Sidebar
	if ( !$hide_sidebar && is_active_sidebar( 'sidebar_blog' ) ) : 
	?>
	<div class="col-sm-3">
	<?php get_sidebar(); ?>
	</div>
	<?php endif; ?>
	
</div>
<?php get_footer(); ?>