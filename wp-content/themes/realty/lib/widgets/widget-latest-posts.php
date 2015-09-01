<?php
// Credits: http://buffercode.com/simple-method-create-custom-wordpress-widget-admin-dashboard/
// REGISTER WIDGET
function widget_latest_posts() {
	register_widget( 'widget_latest_posts' );
}
add_action( 'widgets_init', 'widget_latest_posts' );

class widget_latest_posts extends WP_Widget {

	// CONSTRUCT WIDGET
	function widget_latest_posts() {
		$widget_ops = array( 'classname' => 'widget_latest_posts', 'description' => __( 'Posts', 'tt' ) );
		$this->WP_Widget( 'widget_latest_posts', __( 'Realty - Latest Posts', 'tt' ), $widget_ops );
	}
	
	// CREATE WIDGET FORM (WORDPRESS DASHBOARD)
  function form($instance) {
  
	  if ( isset( $instance[ 'title' ] ) && isset ( $instance[ 'amount' ] ) ) {
			$title = $instance[ 'title' ];
			$amount = $instance[ 'amount' ];
		}
		else {
			$title = __( 'Latest Posts', 'tt' );
			$amount = __( '3', 'tt' );
		}
		?>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'tt' ); ?></label> 
			<input name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title );?>" class="widefat" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'amount' ); ?>"><?php _e( 'Total Number of Posts to Display:', 'tt' ); ?></label> 
			<input name="<?php echo $this->get_field_name( 'amount' ); ?>" type="text" value="<?php echo esc_attr( $amount );?>" class="widefat" />
		</p>
		 
		<?php
		
  }

  // UPDATE WIDGET
  function update( $new_instance, $old_instance ) {
  	  
	  $instance = $old_instance;
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';		 
		$instance['amount'] = ( ! empty( $new_instance['amount'] ) ) ? strip_tags( $new_instance['amount'] ) : '';		 		 
		
		return $instance;
	  
  }

  // DISPLAY WIDGET ON FRONT END
  function widget( $args, $instance ) {
	  
	  extract( $args );
 
		// Widget starts to print information
		echo $before_widget;
		 
		$title = apply_filters( 'widget_title', $instance[ 'title' ] );	 
		$amount = empty( $instance[ 'amount' ] ) ? '3' : $instance[ 'amount' ];
		$amount = intval( $amount );
		 
		if ( !empty( $title ) ) { 
			echo $before_title . $title . $after_title; 
		};

		// Query Featured Properties
		$args_latest_posts = array(
			'post_type' 				=> 'post',
			'posts_per_page' 		=> $amount
		);
		
		$query_latest_posts = new WP_Query( $args_latest_posts );
		
		if ( $query_latest_posts->have_posts() ) :
		?>
		<div class="owl-carousel-1">
			<?php
			while ( $query_latest_posts->have_posts() ) : $query_latest_posts->the_post();
			?>
			<article>
				<?php 
				if ( get_post_format( get_the_ID() ) == "video" ) {
					$video_url = get_post_meta( get_the_ID(), "estate_post_video_url", true );
					echo wp_oembed_get( $video_url );
				}
				elseif ( ( get_post_format( get_the_ID() ) == "gallery" ) ) {
					$gallery = get_post_meta( get_the_ID(), "estate_post_gallery", false );
					?>
					<div class="flexslider-nav-off">
						<ul class="slides">
						<?php
						
						$args = array(
							'post_type' => 'attachment',
							'orderby' => 'menu_order',
							'order' => 'ASC',
							'post__in' => $gallery,
							'posts_per_page' => count($gallery) // If no gallery image selected, it will add all attachments
						);
						
						$gallery_array = get_posts( $args );
						
						foreach ($gallery_array as $slide) {
							$attachment = wp_get_attachment_image_src( $slide->ID, 'thumbnail-400-300' );
							$attachment_url = $attachment[0];
						?>
							<li><img src="<?php echo $attachment_url; ?>" alt="" /></li>
						<?php 
						}
						?>
						</ul>
					</div>
					<?php
				}
				else {
					if ( has_post_thumbnail() ) {
						the_post_thumbnail( 'thumbnail-400-300', array( 'alt' => '' ) );
					}
				}
				?>
			
				<div class="content">
					<a href="<?php the_permalink(); ?>"><?php the_title( '<h6 class="title">', '</h6>' ); ?></a>
					<?php	echo the_excerpt(); ?>
				</div>
				
			</article>
			<?php
			endwhile;
			?>
		</div>
		<?php
		wp_reset_query();
		endif;
		
		// Widget ends printing information
		echo $after_widget;
	  
  }

}