<?php get_header(); ?>
<div class="row testimonial-item">
	<div class="col-sm-4 testimonial-thumbnail">
		<?php 
		if ( has_post_thumbnail() ) { 
			the_post_thumbnail( 'square-400' ); 
		}	
		else {
			echo '<img src ="//placehold.it/400x400/eee/ccc/&text=.." />';
		}
		?>
	</div>
	<div class="col-sm-8">
		<div class="arrow-left"></div>
		<div class="content">
			<blockquote>
				<p>
					<?php	echo $testimonial = get_post_meta( get_the_ID(), 'estate_testimonial_text', true ); ?>
				</p>
				<?php the_title( '<cite>', '</cite>' ); ?>
			</blockquote>
		</div>
	</div>
</div>
<?php get_footer(); ?>