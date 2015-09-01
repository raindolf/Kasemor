<?php
/* TESTIMONIALS
============================== */
$args = array(
	'post_type' 				=> 'testimonial',
	'posts_per_page' 		=> -1
);

$query_agents = new WP_Query( $args );

global $post;


if ( $query_agents->have_posts() ) : 

while ( $query_agents->have_posts() ) : $query_agents->the_post();
$testimonial = get_post_meta( $post->ID, 'estate_testimonial_text', true );
?>
<div class="testimonial-item">
	<div class="row">
		<div class="col-sm-5 testimonial-thumbnail">
			<?php 
			if ( has_post_thumbnail() ) { 
				the_post_thumbnail( 'square-400' ); 
			}	
			else {
				echo '<img src ="//placehold.it/400x400/eee/ccc/&text=.." />';
			}
			?>
		</div>
		<div class="col-sm-7">
			<div class="arrow-left"></div>
			<div class="content">
				<blockquote>
					<p>
						<?php	echo $testimonial; ?>
					</p>
					<?php the_title( '<cite>', '</cite>' ); ?>
				</blockquote>
			</div>
		</div>
	</div>
</div>
<?php
endwhile;
?>
</div><!-- .owl-carousel -->
<?php
wp_reset_query();
endif;