<?php $gallery = get_post_meta( get_the_ID(), "estate_post_gallery", false ); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<div class="entry-header clearfix">
	
	<div class="header-media">
		<div class="flexslider-nav">
			<ul class="slides">
			<?php
			
			$args = array(
				'post_type' => 'attachment',
				'orderby' => 'menu_order',
				'order' => 'ASC',
				'post__in' => $gallery,
				'posts_per_page' => count($gallery)
			);
			
			$gallery_array = get_posts( $args );
			
			foreach ($gallery_array as $slide) {
				$attachment = wp_get_attachment_image_src( $slide->ID, 'thumbnail-16-9' );
				$attachment_url = $attachment[0];
			?>
				<li><img src="<?php echo $attachment_url; ?>" /></li>
			<?php 
			}
			?>
			</ul>
		</div>
	</div>
	
	<div class="header-content clearfix">
	
		<div class="header-meta">
			<div class="month"><?php echo get_the_date('M'); ?></div>
			<div class="day"><?php echo get_the_date('d'); ?></div>
		</div>
		
		<?php			
		if ( is_single() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;
		?>

	</div>
	
</div><!-- .entry-header -->

<?php echo tt_post_content_navigation(); ?>

</article>