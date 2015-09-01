<?php
// Hooks your functions into the correct filters
function tt_add_mce_button() {

	// check user permissions
	if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
		return;
	}
	// check if WYSIWYG is enabled
	if ( 'true' == get_user_option( 'rich_editing' ) ) {
		add_filter( 'mce_external_plugins', 'tt_add_tinymce_plugin' );
		add_filter( 'mce_buttons', 'tt_register_mce_button' );
	}
	
}
add_action('admin_head', 'tt_add_mce_button');

// Declare script for new button
function tt_add_tinymce_plugin( $plugin_array ) {

	$version = get_bloginfo('version'); 
  
  // prior WP 3.9
  if( $version < 3.9 ) {
	  $plugin_array['tt_mce_button'] = TT_LIB_URI .'/tinymce/tinymce-3-plugin.js';
  }
  // WP 3.9+
  else {
  	$plugin_array['tt_mce_button'] = TT_LIB_URI .'/tinymce/tinymce-4-plugin.js';
  }
  
	return $plugin_array;

}

// Register new button in the editor
function tt_register_mce_button( $buttons ) {

	array_push( $buttons, 'tt_mce_button' );
	return $buttons;
	
}