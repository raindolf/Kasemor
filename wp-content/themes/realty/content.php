<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

<div class="entry-header clearfix">
	
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