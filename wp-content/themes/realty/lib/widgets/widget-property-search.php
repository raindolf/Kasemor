<?php
// Credits: http://buffercode.com/simple-method-create-custom-wordpress-widget-admin-dashboard/
// REGISTER WIDGET
function widget_property_search() {
	register_widget( 'widget_property_search' );
}
add_action( 'widgets_init', 'widget_property_search' );

class widget_property_search extends WP_Widget {

	// CONSTRUCT WIDGET
	function widget_property_search() {
		$widget_ops = array( 'classname' => 'widget_property_search', 'description' => __( 'Property Search', 'tt' ) );
		$this->WP_Widget( 'widget_property_search', __( 'Realty - Property Search', 'tt' ), $widget_ops );
	}
	
	// CREATE WIDGET FORM (WORDPRESS DASHBOARD)
  function form($instance) {
  
	  if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Property Search', 'tt' );
			$agent = false;
		}
		?>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'tt' ); ?></label> 
			<input name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title );?>" class="widefat" />
		</p>
		 
		<?php
		
  }

  // UPDATE WIDGET
  function update( $new_instance, $old_instance ) {
  	  
	  $instance = $old_instance;
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';		 		 
		
		return $instance;
	  
  }

  // DISPLAY WIDGET ON FRONT END
  function widget( $args, $instance ) {
	  
	  extract( $args );
 
		// Widget starts to print information
		echo $before_widget;
		 
		$title = apply_filters( 'widget_title', $instance[ 'title' ] );
		 
		if ( !empty( $title ) ) { 
			echo $before_title . $title . $after_title; 
		};
		
		// Property Search Form
		get_template_part( 'lib/inc/template/search-form' );
		
		// Widget ends printing information
		echo $after_widget;
	  
  }
	
	

}