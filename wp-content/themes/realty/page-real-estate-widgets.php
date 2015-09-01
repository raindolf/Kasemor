<?php 
get_header();

$hide_sidebar = get_post_meta( $post->ID, 'estate_page_hide_sidebar', true );

while ( have_posts() ) : the_post(); 
?>
	</div><!-- .container -->
	<?php tt_page_banner();	?>	
	<div class="container">	

	<div class="row">
	
		<div class="col-sm-8 col-md-9">	
			<div id="main-content" class="content-box">
				<?php the_content(); ?>
			</div>		
		</div>

		<div class="col-sm-4 col-md-3">
			<ul id="sidebar">
				<li id="widget_property_search" class="widget">
					<?php the_widget( 'widget_property_search', 'title=Property+Search', 'before_title=<h5 class="widget-title">&after_title=</h5><div class="widget-content">' ); ?>
				</li>
				<li id="widget_featured_properties" class="widget">
					<?php the_widget( 'widget_featured_properties', 'title=Featured+Properties&amount=3&random=0', 'before_title=<h5 class="widget-title">&after_title=</h5><div class="widget-content">' ); ?>
				</li>
				<li id="widget_latest_posts" class="widget">
					<?php the_widget( 'widget_latest_posts', 'title=Latest+Posts&amount=2', 'before_title=<h5 class="widget-title">&after_title=</h5><div class="widget-content">' ); ?>
				</li>
				<li id="widget_testimonials" class="widget">
					<?php the_widget( 'widget_testimonials', 'title=Testimonials&amount=3&random=0', 'before_title=<h5 class="widget-title">&after_title=</h5><div class="widget-content">' ); ?>
				</li>
				<li id="widget_agents" class="widget">
					<?php the_widget( 'widget_agents', 'title=Featured+Agent&agent=12&random=0', 'before_title=<h5 class="widget-title">&after_title=</h5><div class="widget-content">' ); ?>
				</li>
			</ul>
		</div>
	
	
	</div><!-- .row -->
	
<?php
endwhile;

get_footer(); 
?>