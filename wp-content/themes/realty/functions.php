<?php

/* THEME CONTENT WIDTH
============================== */
if ( ! isset( $content_width ) ) {
	$content_width = 1170;
}

/* THEME VARIABLES
============================== */
define('TT_LIB', get_template_directory()  . '/lib');
define('TT_LIB_URI', get_template_directory_uri()  . '/lib');


/* METABOXES
 * https://github.com/rilwis/meta-box
============================== */
define( 'RWMB_URL', trailingslashit( TT_LIB_URI . '/meta-box' ) );
define( 'RWMB_DIR', trailingslashit( TT_LIB . '/meta-box' ) );
require_once TT_LIB . '/meta-box/meta-box.php';
include_once TT_LIB . '/meta-box.php';


/* REDUX FRAMEWORK - THEME OPTIONS
============================== */
if ( !class_exists( 'ReduxFramework' ) && file_exists( TT_LIB . '/redux/ReduxCore/framework.php' ) ) {
	require_once( TT_LIB . '/redux/ReduxCore/framework.php' );
}
if ( !isset( $redux_demo ) && file_exists( TT_LIB . '/redux/realty/realty-config.php' ) ) {
	require_once( TT_LIB . '/redux/realty/realty-config.php' );
	require_once( TT_LIB . '/redux/realty/loader.php' ); // Field - Repeater (for Property Search)
}


/* REDUX FRAMEWORK - ADD FONTAWESOME ICON FONT
============================== */
function tt_themeOptionsStyles($hook) {
	if( 'themes.php?page=_options' == $hook ) {
  	return;
  }
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/css/font-awesome.min.css', array(), time(), 'all' );  
	wp_enqueue_style( 'redux-custom', TT_LIB_URI . '/redux/realty/style.css', array( 'redux-fields-css' ), time(), 'all' );  
	wp_enqueue_style( 'custom-admin', get_template_directory_uri() . '/assets/css/admin.css', array(), time(), 'all' );  
}
add_action( 'admin_enqueue_scripts', 'tt_themeOptionsStyles' );


/* TGM PLUGIN ACTIVATION
============================== */
require_once TT_LIB . '/tgm/class-tgm-plugin-activation.php';
require_once TT_LIB . '/tgm/plugins.php';


/* CUSTOM POST TYPES
============================== */
require_once ( TT_LIB . '/shortcodes.php' );
require_once ( TT_LIB . '/inc/custom-post-type-property.php' );
require_once ( TT_LIB . '/inc/custom-post-type-testimonial.php' );


/* WIDGETS
============================== */
require_once ( TT_LIB . '/widgets/widget-agents.php' );
require_once ( TT_LIB . '/widgets/widget-featured-properties.php' );
require_once ( TT_LIB . '/widgets/widget-latest-posts.php' );
require_once ( TT_LIB . '/widgets/widget-property-search.php' );
require_once ( TT_LIB . '/widgets/widget-testimonials.php' );


/* OTHER FUNCTIONS
============================== */
require_once ( TT_LIB . '/advanced-custom-fields.php' );
require_once ( TT_LIB . '/functions/function-property-search.php' );
require_once ( TT_LIB . '/functions/function-single-property.php' );

/* OTHER INCLUDES
============================== */
require_once ( TT_LIB . '/tinymce/tinymce-buttons.php' );
require_once ( TT_LIB . '/add-to-favorites.php' );
require_once ( TT_LIB . '/compare-properties.php' );

/* LOAD ADMIN SCRIPT
============================== */
function tt_admin_scripts( $hook ) {	

	wp_enqueue_style('tinymce', get_template_directory_uri() . '/lib/tinymce/tinymce-style.css', null, null );
	
	// Absolute Template Path
  $tt_abs_path = array( 'template_url' => get_template_directory_uri() );
  wp_localize_script( 'jquery', 'abspath', $tt_abs_path );
  
  // Enqueue Only For Posts
	if ( 'post.php' != $hook ) {
  	return;
  }
	
	wp_enqueue_script('tt-admin-script', get_template_directory_uri() . '/assets/js/admin.js' );
	wp_enqueue_media();	
  
}
add_action('admin_enqueue_scripts', 'tt_admin_scripts');


/* ENQUEUES
============================== */
function tt_realty_scripts() {
	
	global $realty_theme_option;
	
	//wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css', null, '3.2.0' );
	//wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/css/font-awesome.min.css', null, '4.2.0' );
	wp_enqueue_style( 'bootstrap', '//netdna.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css', null, null );
	wp_enqueue_style( 'font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css', null, null );
	if ( $realty_theme_option['enable-rtl-support'] ) {
		//wp_enqueue_style( 'bootstrap-rtl', '//cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.2.0-rc2/css/bootstrap-rtl.min.css', null, null );
		wp_enqueue_style( 'bootstrap-rtl', '//cdn.rawgit.com/morteza/bootstrap-rtl/master/dist/cdnjs/3.3.1/css/bootstrap-rtl.min.css', null, null );
	}
	
	wp_enqueue_style( 'google-font', '//fonts.googleapis.com/css?family=Lato:100,100italic,300,300italic,regular,italic,700,700italic,900,900italic', null, null );
	wp_enqueue_style( 'chosen-select', get_template_directory_uri() . '/assets/chosen/chosen.min.css', null, null );
	wp_enqueue_style( 'main-style', get_template_directory_uri() . '/assets/css/main.css', null, null );
	
	wp_enqueue_style( 'style', get_stylesheet_uri(), null, null );
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if ( is_plugin_active( 'dsidxpress/dsidxpress.php' ) ) {
		wp_enqueue_style( 'tt-dsidxpress', get_template_directory_uri() . '/assets/css/idx.css', null, null );
	}
	wp_enqueue_style( 'print', get_template_directory_uri() . '/print.css', null, null, 'print' );
	
	if ( $realty_theme_option['enable-rtl-support'] ) {
		wp_enqueue_style( 'rtl', get_template_directory_uri() . '/rtl.css', null, null );
	}
	
	//wp_enqueue_style( 'leaflet', '//cdn.leafletjs.com/leaflet-0.7/leaflet.css', array( 'jquery'), null, null );
	//wp_enqueue_style( 'markercluster', get_template_directory_uri() . '/assets/css/MarkerCluster.css', null, null );
	//wp_enqueue_style( 'markercluster-default', get_template_directory_uri() . '/assets/css/MarkerCluster.Default.css', null, null );
	
	/* Move All Scripts in Footer
	remove_action('wp_head', 'wp_print_scripts');
	remove_action('wp_head', 'wp_print_head_scripts', 9);
	remove_action('wp_head', 'wp_enqueue_scripts', 1);
	*/
	
	wp_enqueue_script( 'jquery', null, null, false );
	wp_enqueue_script( 'bootstrap', '//netdna.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js', array( 'jquery' ), null, true );
	wp_enqueue_script( 'tt-theme-main', get_template_directory_uri() . '/assets/js/theme-main.min.js', array( 'jquery' ), null, true ); 
	
	// Check For User Property Submit & IDX Template Page & Theme Option for "Google Maps API" is on/off, to avoid duplicate API load
	$disableGoogleMapsApi = $realty_theme_option['disable-google-maps-api'];
	if ( !is_page_template( 'template-property-submit.php' ) && !is_page_template('template-idx.php') && $disableGoogleMapsApi == false ) {
		wp_enqueue_script( 'google-maps-api', '//maps.google.com/maps/api/js?sensor=false', array( 'jquery' ), null, false );
	}
	
	if ( is_page_template( 'template-property-submit.php' ) ) {
		wp_enqueue_script( 'google-maps-api', '//maps.google.com/maps/api/js?sensor=false&libraries=places', array( 'jquery' ), null, false );
	}
	
	if ( is_page_template( 'template-property-submit-listing.php' ) ) {
		wp_enqueue_script( 'paypal-button', TT_LIB_URI . '/paypal/paypal-button.min.js', array( 'jquery' ), null, false );
	}
	
	if ( !is_page_template('template-idx.php') ) {
		wp_enqueue_script( 'google-maps-min', get_template_directory_uri() . '/assets/js/google-maps.min.js', array( 'jquery' ), null, false );
	}
	
	if ( $realty_theme_option['datepicker-language'] && $realty_theme_option['datepicker-language'] != 'en' ) {
		wp_enqueue_script( 'datepicker-'. $realty_theme_option['datepicker-language'], get_template_directory_uri() . '/assets/js/bootstrap-datepicker/locales/bootstrap-datepicker.' . $realty_theme_option['datepicker-language'] . '.js', array( 'datepicker' ), null, true ); 
	}
	
	if ( $realty_theme_option['enable-rtl-support'] ) {
		wp_enqueue_script( 'theme-rtl', get_template_directory_uri() . '/assets/js/theme-rtl.js', array( 'jquery' ), null, true );
	}
	
	if ( !$realty_theme_option['property-favorites-disabled'] && $realty_theme_option['property-favorites-temporary'] ) {	
		wp_enqueue_script( 'ajax-favorites-temporary', get_template_directory_uri() . '/assets/js/ajax-favorites-temporary.js', array( 'store' ), null, false );
	}
	
	if ( is_page_template( 'template-property-submit.php' ) ) {
		wp_enqueue_script( 'property-submit', get_template_directory_uri() . '/assets/js/theme-property-submit.js', array( 'jquery' ), null, true );
	}
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	
}
add_action( 'wp_enqueue_scripts', 'tt_realty_scripts' );


/* CUSTOM LOGIN PAGE (wp-login.php)
============================== */
function tt_custom_login() {

	// Login Page Logo
	$output  = '<style type="text/css">';
	global $realty_theme_option;
	$login_logo = $realty_theme_option['logo-login']['url'];
	$background_login = $realty_theme_option['background-login'];
	if ( !empty( $login_logo ) ) {
		$output .= '.login h1 a { background: url(' . $login_logo . ') 50% 50% no-repeat !important; width: auto; }';
	}
	if ( $background_login ) {
		$output .= '.login { background-color: ' . $background_login . '; }';
	}
	else {
		$output .= '.login { background-color: #f8f8f8; }';
	}
	$output .= '.login form input[type="submit"] { border-radius: 0; border: none; -webkit-box-shadow: none; box-shadow: none; }';
	$output .= '.login form .input, .login .form input:focus { padding: 5px 10px; color: #666; -webkit-box-shadow: none; box-shadow: none; }';
	$output .= 'input[type=checkbox]:focus, input[type=email]:focus, input[type=number]:focus, input[type=password]:focus, input[type=radio]:focus, input[type=search]:focus, input[type=tel]:focus, input[type=text]:focus, input[type=url]:focus, select:focus, textarea:focus { -webkit-box-shadow: none; box-shadow: none; }';
	$output .= '</style>';
	
	echo $output;
	
	// Remove Login Shake
	remove_action('login_head', 'wp_shake_js', 12);

}
add_action('login_head', 'tt_custom_login');

// Login Logo Link
function tt_wp_login_url() {
	return home_url();
}
add_filter('login_headerurl', 'tt_wp_login_url');


/* SETUP THEME
============================== */
function tt_estate_setup() {
	
	// Make theme available for translation.
	load_theme_textdomain( 'tt', get_template_directory() . '/languages' );
	
	// This theme styles the visual editor to resemble the theme style.
	add_editor_style( 'assets/css/editor-styles.css' );

	// Add RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );
	
	// Enable support for Post Thumbnails and declare custom sizes.
	add_theme_support( 'post-thumbnails' );
	
	// Custom Image Sizes
	add_image_size( 'thumbnail-1600', 1600, 9999, false ); // NEW in v1.6.2 - Regenerate thumbnails: https://wordpress.org/plugins/regenerate-thumbnails/
	add_image_size( 'thumbnail-1200', 1200, 9999, false );
	add_image_size( 'thumbnail-16-9', 1200, 675, true );
	add_image_size( 'thumbnail-1200-400', 1200, 400, true );
	add_image_size( 'thumbnail-400-300', 400, 300, true );	
	add_image_size( 'property-thumb', 600, 300, true );
	add_image_size( 'square-400', 400, 400, true );
	
	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array( 'primary' => __( 'Main Menu', 'tt' ) ) );
	register_nav_menus( array( 'footer' => __( 'Footer Menu', 'tt' ) ) );

	// Switch default core markup for search form, comment form, and comments to output valid HTML5.
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', ) );

	// Enable support for Post Formats.
	add_theme_support( 'post-formats', array( 'image', 'gallery', 'video' ) );

}
add_action( 'after_setup_theme', 'tt_estate_setup' );


/* Sidebars
============================== */

// No Title > Add Widget Content Wrapper To Widget
// http://wordpress.stackexchange.com/questions/74732/adding-a-div-to-wrap-widget-content-after-the-widget-title
function tt_check_sidebar_params( $params ) {
  global $wp_registered_widgets;

  $settings_getter = $wp_registered_widgets[ $params[0]['widget_id'] ]['callback'][0];
  $settings = $settings_getter->get_settings();
  $settings = $settings[ $params[1]['number'] ];

  if ( $params[0][ 'after_widget' ] == '</div></li>' && isset( $settings[ 'title' ] ) && empty( $settings[ 'title' ] ) )
  	$params[0][ 'before_widget' ] .= '<div class="widget-content empty-title">';
		
  return $params;
}
add_filter( 'dynamic_sidebar_params', 'tt_check_sidebar_params' );

register_sidebar(
	array(
		'name'						=> __( 'Blog Sidebar', 'tt' ),
		'id'   						=> 'sidebar_blog',
		'before_widget' 	=> '<li id="%2$s" class="widget">',
		'after_widget' 		=> '</div></li>',
		'before_title' 		=> '<h5 class="widget-title">',
		'after_title' 		=> '</h5><div class="widget-content">'
	)
);

register_sidebar( 
	array(
		'name'						=> __( 'Property Sidebar', 'tt' ),
		'id'   						=> 'sidebar_property',
		'before_widget' 	=> '<li id="%2$s" class="widget">',
		'after_widget' 		=> '</div></li>',
		'before_title' 		=> '<h5 class="widget-title">',
		'after_title' 		=> '</h5><div class="widget-content">'
	)
);

register_sidebar( 
	array(
		'name'						=> __( 'Agent Sidebar', 'tt' ),
		'id'   						=> 'sidebar_agent',
		'before_widget' 	=> '<li id="%2$s" class="widget">',
		'after_widget' 		=> '</div></li>',
		'before_title' 		=> '<h5 class="widget-title">',
		'after_title' 		=> '</h5><div class="widget-content">'
	)
);

register_sidebar( 
	array(
		'name'						=> __( 'Page Sidebar', 'tt' ),
		'id'   						=> 'sidebar_page',
		'before_widget' 	=> '<li id="%2$s" class="widget">',
		'after_widget' 		=> '</div></li>',
		'before_title' 		=> '<h5 class="widget-title">',
		'after_title' 		=> '</h5><div class="widget-content">'
	)
);

register_sidebar( 
	array(
		'name'						=> __( 'Contact Sidebar', 'tt' ),
		'id'   						=> 'sidebar_contact',
		'before_widget' 	=> '<li id="%2$s" class="widget">',
		'after_widget' 		=> '</div></li>',
		'before_title' 		=> '<h5 class="widget-title">',
		'after_title' 		=> '</h5><div class="widget-content">'
	)
);

register_sidebar( 
	array(
		'name'						=> __( 'IDX Sidebar', 'tt' ),
		'id'   						=> 'sidebar_idx',
		'before_widget' 	=> '<li id="%2$s" class="widget">',
		'after_widget' 		=> '</div></li>',
		'before_title' 		=> '<h5 class="widget-title">',
		'after_title' 		=> '</h5><div class="widget-content">'
	)
);

register_sidebar( 
	array(
		'name'						=> __( 'Footer Column 1', 'tt' ),
		'id'   						=> 'sidebar_footer_1',
		'before_widget' 	=> '<li id="%2$s" class="widget">',
		'after_widget' 		=> '</div></li>',
		'before_title' 		=> '<h5 class="widget-title">',
		'after_title' 		=> '</h5><div class="widget-content">'
	)
);

register_sidebar( 
	array(
		'name'						=> __( 'Footer Column 2', 'tt' ),
		'id'   						=> 'sidebar_footer_2',
		'before_widget' 	=> '<li id="%2$s" class="widget">',
		'after_widget' 		=> '</div></li>',
		'before_title' 		=> '<h5 class="widget-title">',
		'after_title' 		=> '</h5><div class="widget-content">'
	)
);

register_sidebar( 
	array(
		'name'						=> __( 'Footer Column 3', 'tt' ),
		'id'   						=> 'sidebar_footer_3',
		'before_widget' 	=> '<li id="%2$s" class="widget">',
		'after_widget' 		=> '</div></li>',
		'before_title' 		=> '<h5 class="widget-title">',
		'after_title' 		=> '</h5><div class="widget-content">'
	)
);


/* AJAX - WordPress URL
============================== */
function tt_ajax_url() {
?>
<script type="text/javascript">
var ajaxURL = '<?php echo admin_url('admin-ajax.php'); ?>';
</script>
<?php 
}
add_action('wp_head','tt_ajax_url');


/* Add "Lost Password" link to wp_login_form()
============================== */	
function tt_add_lost_password_link() {
	return '<a href="' . wp_lostpassword_url(false) . '">Lost Password?</a>';
}
add_action( 'login_form_bottom', 'tt_add_lost_password_link' );


/* Login Failed - Username & Password Entered, But Incorrect
http://www.paulund.co.uk/create-your-own-wordpress-login-page
============================== */
function tt_login_failed( $user ) {

	$referrer = $_SERVER['HTTP_REFERER'];
	
  // Check that were not on the default login page
	if ( !empty( $referrer ) && !strstr( $referrer,'wp-login' ) && !strstr( $referrer,'wp-admin' ) && $user != null ) {
		// Make sure we don't already have a failed login attempt
		if ( !strstr($referrer, '?login=failed' ) ) {
			// Redirect to login page and append a querystring of login failed
	    wp_redirect( $referrer . '?login=failed');
	  } else {
	  	wp_redirect( $referrer );
	  }
	  exit;
	}
	
}
add_action( 'wp_login_failed', 'tt_login_failed' );


/* Login Form Bottom - Add Login Errors
============================== */
function tt_login_form_bottom() {
	return '<div id="login-errors"></div>';
}
//add_action( 'login_form_bottom', 'tt_login_form_bottom' );


/* Login Failed - No Username & Password Entered
http://www.paulund.co.uk/create-your-own-wordpress-login-page
============================== */
function tt_login_blank( $user ) {

	if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
  	$referrer = $_SERVER['HTTP_REFERER'];
  }
  else {
	 	$referrer = '';
  }
  
  $error = false;

	// Check Login
	if( isset($_POST['log']) && $_POST['log'] == '' || isset($_POST['pwd']) && $_POST['pwd'] == '' ) {
		$error = true;
	}

	// Check that were not on the default login page
	if ( !empty( $referrer ) && !strstr( $referrer,'wp-login' ) && !strstr( $referrer, 'wp-admin' ) && $error ) {
		// Make sure we don't already have a failed login attempt
  	if ( !strstr($referrer, '?login=failed') ) {
  		// Redirect to the login page and append a querystring of login failed
      wp_redirect( $referrer . '?login=failed' );
    } else {
      wp_redirect( $referrer );
    }
  exit;
	}
  	
}
add_action( 'authenticate', 'tt_login_blank' );


/* Disable WP Admin Bar For Non-Admins
============================== */
function tt_remove_admin_bar() {
	if ( !current_user_can('administrator') && !is_admin() ) {
	  show_admin_bar(false);
	}
} 
add_action( 'after_setup_theme', 'tt_remove_admin_bar' ); 


/* Disable wp-admin For Non-Admins, If Not Running Ajax OR Updating User Data
============================== */
function tt_disable_wp_admin_for_non_admins() {
  if ( !current_user_can('manage_options') &&  $_SERVER['DOING_AJAX'] != '/wp-admin/admin-ajax.php' && !tt_ajax_add_remove_favorites() && !tt_ajax_update_user_profile_function() ) {
  	wp_redirect( home_url() ); 
  	exit;
  }
}
//add_action('admin_init', 'tt_disable_wp_admin_for_non_admins');


/* Login with Username OR Email Address
http://en.bainternet.info/wordpress-allow-login-with-email/
============================== */
function tt_allow_email_login( $user, $username, $password ) {
	if ( is_email( $username ) ) {
		$user = get_user_by_email( $username );
		if ( $user ) {
			$username = $user->user_login;
		}
	}
	return wp_authenticate_username_password(null, $username, $password );
}
add_filter( 'authenticate', 'tt_allow_email_login', 20, 3 );


/* CUSTOM MAP MARKERS ICONS
============================== */
if ( !function_exists('tt_mapMarkers') ) {
	function tt_mapMarkers() {
	
		global $realty_theme_option;
		
		$default_marker_property = $realty_theme_option['map-marker-property-default'];	
		
		if ( !empty( $realty_theme_option['map-marker-property']['url'] ) ) {
			
			if ( is_ssl() ) {
		    $custom_marker_property_url = str_replace( 'http://', 'https://', $realty_theme_option['map-marker-property']['url'] );
		  }
		  else {
			  $custom_marker_property_url = $realty_theme_option['map-marker-property']['url'];
		  }
			
			$custom_marker_property = $realty_theme_option['map-marker-property'];
			$custom_marker_property_width_retina = $custom_marker_property['width'] / 2;
			$custom_marker_property_height_retina = $custom_marker_property['height'] / 2;
			
		}
		
		$default_marker_cluster = $realty_theme_option['map-marker-cluster-default'];	
		
		if ( !empty( $realty_theme_option['map-marker-cluster']['url'] ) ) {
			
			if ( is_ssl() ) {
		    $custom_marker_cluster_url = str_replace( 'http://', 'https://', $realty_theme_option['map-marker-cluster']['url'] );
		  }
		  else {
			  $custom_marker_cluster_url = $realty_theme_option['map-marker-cluster']['url'];
		  }
		  
			$custom_marker_cluster = $realty_theme_option['map-marker-cluster'];
			$custom_marker_cluster_width_retina = $custom_marker_cluster['width'] / 2;
			$custom_marker_cluster_height_retina = $custom_marker_cluster['height'] / 2;
			
		}
	
		// Check For Custom Marker Property
		if ( !empty( $realty_theme_option['map-marker-property']['url'] ) ) {
		?>
	var customIcon = new google.maps.MarkerImage(
		'<?php echo $custom_marker_property['url']; ?>',
		null, // size is determined at runtime
	  null, // origin is 0,0
	  null, // anchor is bottom center of the scaled image
	  new google.maps.Size(<?php echo $custom_marker_property_width_retina; ?>, <?php echo $custom_marker_property_height_retina; ?>)
	);			
		<?php
		}
		// No Custom Marker Property Found: Use Default
		else {
		?>
	var customIcon = new google.maps.MarkerImage(
		'<?php echo $default_marker_property; ?>',
		null, // size is determined at runtime
	  null, // origin is 0,0
	  null, // anchor is bottom center of the scaled image
	  new google.maps.Size(50, 69)
	);
		<?php }
		
		// Check For Custom Marker Cluster
		if ( !empty( $realty_theme_option['map-marker-cluster']['url'] ) ) {
		?>
	var markerClusterOptions = {
		gridSize: 60, // Default: 60
		maxZoom: 14,
		styles: [{
			width: <?php echo $custom_marker_cluster_width_retina; ?>,
			height: <?php echo $custom_marker_cluster_height_retina; ?>,
			url: "<?php echo $custom_marker_cluster['url']; ?>"
		}]
	};
		<?php
		}
		// No Custom Marker Cluster Found: Use Default
		else {
		?>
	var markerClusterOptions = {
		gridSize: 60, // Default: 60
		maxZoom: 14,
		styles: [{
			width: 50,
			height: 50,
			url: "<?php echo $default_marker_cluster; ?>"
		}]
	};
		<?php }
	
	}
}


/* Excerpt
============================== */
function tt_excerpt_more( $more ) {
	return ' ..';
}
add_filter('excerpt_more', 'tt_excerpt_more');

function tt_excerpt_length( $length ) {
	return 20;
}
add_filter( 'excerpt_length', 'tt_excerpt_length', 999 );


/* PROPERTY
============================== */

// Property Price
if ( !function_exists('tt_property_price') ) {
	function tt_property_price() {
		
		global $post, $realty_theme_option;
		
		$property_price = doubleval( get_post_meta( $post->ID, 'estate_property_price', true ) );
		$property_price_prefix = get_post_meta( $post->ID, 'estate_property_price_prefix', true );
		$property_price_suffix = get_post_meta( $post->ID, 'estate_property_price_text', true );
		
		$currency_sign = $realty_theme_option['currency-sign'];
		$currency_sign_position = $realty_theme_option['currency-sign-position'];
		$price_thousands_separator = $realty_theme_option['price-thousands-separator'];
		$price_prefix = $realty_theme_option['price-prefix'];
		$price_suffix = $realty_theme_option['price-suffix'];
		
		if ( $realty_theme_option['price-decimals'] ) {
			$decimals = $realty_theme_option['price-decimals'];
		}
		else {
			$decimals = 0;
		}
		$decimal_point = '.';
		
		// Default Currency Sign "$"
		if ( empty( $currency_sign ) ) {
	  	$currency_sign = __( '$', 'tt' );
	  }
	  
	  if ( !empty( $property_price ) ) {
		  
		  if ( $property_price == -1 ) {
				$output = __( 'Price Upon Request', 'tt' );  
			}
		    
			else if ( $property_price ) {
				
				$output = '';
				
				if ( $property_price_prefix ) {
					$output .= $property_price_prefix;
				}
				else if ( $price_prefix ) {
					$output .= $price_prefix;
				}
		
				$formatted_price = number_format( $property_price, $decimals, $decimal_point, $price_thousands_separator );
				
				if( $currency_sign_position == 'right' ) {
					$output .= $formatted_price . $currency_sign;
				}
				else {
					$output .= $currency_sign . $formatted_price;
				}
				
				if ( $property_price_suffix ) {
					$output .= '<span>' . $property_price_suffix . '</span>';
				}
				else if ( $price_suffix ) {
					$output .= '<span>' . $price_suffix . '</span>';
				}
				
			}
			
			else {
				$output = false;
			}
		
		return $output;
		
		}
		
	}
}

// New Property
if ( !function_exists('tt_icon_new_property') ) {
	function tt_icon_new_property() {
	
		// Current Date
		$today = date( 'r' );
		// Property Publishing Date
		$property_published = get_the_time( 'r' );
		// Property Age in Days
		$property_age = round( (strtotime( $today ) - strtotime( $property_published ) ) / ( 24 * 60 * 60 ),0 );
		
		// If Property Publishing Date is .. days or less, show New Icon
		global $realty_theme_option;
		$new_days_integer = $realty_theme_option['property-new-badge'];
		if ( $new_days_integer && $property_age <= $new_days_integer ) {
			return '<i class="fa fa-fire" data-toggle="tooltip" title="' . __( 'New Offer', 'tt' ) . '"></i>';
		}
		else {
			return false;
		}
	
	}
}

// Featured Property
if ( !function_exists('tt_icon_property_featured') ) {
	function tt_icon_property_featured() {
		
		$property_featured = get_post_meta( get_the_ID(), 'estate_property_featured', true );
		if ( $property_featured ) {
			echo '<i class="fa fa-star" data-toggle="tooltip" title="' . __( 'Featured Property', 'tt' ) . '"></i>';
		}
		else {
			return false;
		}
		
	}
}

// Property Address
if ( !function_exists('tt_icon_property_address') ) {
	function tt_icon_property_address() {
		
		$property_address = get_post_meta( get_the_ID(), 'estate_property_address', true ); 
		if ( $property_address ) {
			echo '<i class="fa fa-map-marker" data-toggle="tooltip" title="' . $property_address . '"></i>';
		}
		else {
			return false;
		}
		
	}
}

// Property Video
if ( !function_exists('tt_icon_property_video') ) {
	function tt_icon_property_video() {
		
		$property_video_provider = get_post_meta( get_the_ID(), 'estate_property_video_provider', true );
		$property_video_id = get_post_meta( get_the_ID(), 'estate_property_video_id', true );
		
		if ( $property_video_id && ( $property_video_provider == "youtube" || $property_video_provider == "vimeo" ) ) {
			if ( $property_video_provider == "youtube") {
				$video_url = '//www.youtube.com/watch?v=';
			}
			if ( $property_video_provider == "vimeo" ) {
				$video_url = '//vimeo.com/';
			}
			return '<a href="' . $video_url . $property_video_id . '" class="property-video-popup"><i class="fa fa-video-camera" data-toggle="tooltip" title="' . __( 'Watch Trailer', 'tt' ) . '"></i></a>';
		}
		else {
			return false;
		}
		
	}
}

// Delete Property
function tt_ajax_delete_property_function() {
	wp_trash_post( $_GET['delete_property'] );
	die;
}
add_action( 'wp_ajax_tt_ajax_delete_property_function', 'tt_ajax_delete_property_function' );


/* CONTACT FORM - Single Property
============================== */

function submit_property_contact_form() {

	if ( wp_verify_nonce( $_POST['nonce'] ) && !empty( $_POST['email'] ) && !empty( $_POST['message'] ) ) {
		
		$property_title = $_POST['property_title'];
		$property_url = $_POST['property_url'];
		
		$recipient = $_POST['agent_email'];
		$subject = __( 'Property', 'tt' ) . ' - '. $property_title;
		$name = $_POST['name'];
	  $email = $_POST['email'];
	  $phone = '-';
	  $phone = $_POST['phone'];
		$headers = array();
	  $headers[] = "From: $name <$email>";
	  
	  $message = __( 'Name:', 'tt' ) . ' ' . $name . "\r\n" . __( 'Phone:', 'tt' ) . ' ' . $phone . "\r\n" . __( 'Property:', 'tt' ) . ' ' . $property_title . "\r\n" . $property_url . "\r\n\n" . __( 'Message:', 'tt' ) . "\r\n\n" . $_POST['message'];
		
		wp_mail( $recipient, $subject, $message, $headers );
		
	}
	
	die;
	
}
add_action( 'wp_ajax_nopriv_submit_property_contact_form', 'submit_property_contact_form' );
add_action( 'wp_ajax_submit_property_contact_form', 'submit_property_contact_form' );


/* CONTACT FORM - Single Agent
============================== */

function submit_agent_contact_form() {

	if ( wp_verify_nonce( $_POST['nonce'] ) && !empty( $_POST['email'] ) && !empty( $_POST['message'] ) ) {
		
		$recipient = $_POST['agent_email'];
		$name = $_POST['name'];
		$subject = __( 'Message from', 'tt' ) . ' ' . $name;
	  $email = $_POST['email'];
	  $phone = '-';
	  $phone = $_POST['phone'];
		$headers = array(); 
	  $headers[] = "From: $name <$email>";
	  
	  $message = __( 'Name:', 'tt' ) . ' ' . $name . "\r\n" . __( 'Phone:', 'tt' ) . ' ' . $phone . "\r\n\n" . __( 'Message:', 'tt' ) . "\r\n\n" . $_POST['message'];
		
		wp_mail( $recipient, $subject, $message, $headers );
		
	}
	
	die;
	
}
add_action( 'wp_ajax_nopriv_submit_agent_contact_form', 'submit_agent_contact_form' );
add_action( 'wp_ajax_submit_agent_contact_form', 'submit_agent_contact_form' );


/* BLOG
============================== */

if ( !function_exists('tt_blog_pagination') ) {
	function tt_blog_pagination() {
		// Don't print empty markup if there's only one page.
		if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
			return;
		}
	
		$paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
		$pagenum_link = html_entity_decode( get_pagenum_link() );
		$query_args   = array();
		$url_parts    = explode( '?', $pagenum_link );
	
		if ( isset( $url_parts[1] ) ) {
			wp_parse_str( $url_parts[1], $query_args );
		}
	
		$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
		$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';
	
		$format  = $GLOBALS['wp_rewrite']->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
		$format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit( 'page/%#%', 'paged' ) : '?paged=%#%';
	
		// Set up paginated links.
		$links = paginate_links( array(
			'base'     		=> $pagenum_link,
			'format'   		=> $format,
			'total'    		=> $GLOBALS['wp_query']->max_num_pages,
			'current'  		=> $paged,
			'mid_size' 		=> 1,
			'type'				=> 'list',
			'add_args' 		=> array_map( 'urlencode', $query_args ),
			'prev_text' 	=> __( '<i class="btn btn-default fa fa-angle-left"></i>', 'tt' ),
			'next_text' 	=> __( '<i class="btn btn-default fa fa-angle-right"></i>', 'tt' ),
		) );
	
		if ( $links ) {
		?>
		<nav id="pagination" role="navigation">
			<?php echo $links; ?>
		</nav>
		<?php
		}
	}
}

// Post Content & Navigation
if ( !function_exists('tt_post_content_navigation') ) {
	function tt_post_content_navigation() {
	?>
			
		<div class="entry-content">
			<?php 
			the_content( __( 'Continue reading ..', 'tt' ) ); 
			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'tt' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
			) );
			
			tt_social_sharing();
			?>
		</div><!-- .entry-content -->
		
		<?php
			
	}
}

// Comments
if ( !function_exists('tt_list_comments') ) {
	function tt_list_comments($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment;
		$args = array(
			'walker'            => null,
			'max_depth'         => '10',
			'style'             => 'ul',
			'callback'          => null,
			'end-callback'      => null,
			'type'              => 'comment',
			'reply_text'        => __( 'Reply', 'tt' ),
			'page'              => '',
			'per_page'          => '',
			'avatar_size'       => 130,
			'reverse_top_level' => null,
			'reverse_children'  => '',
			'format'            => 'html5',
			'short_ping'        => true
		);
		
		if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
		} else {
		$tag = 'li';
		$add_below = 'div-comment';
		}
		?>
		<<?php echo $tag ?> <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
		<?php if ( 'div' != $args['style'] ) : ?>
		<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
		<?php endif; ?>
			<div class="comment-avatar">
				<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
			</div>
			<div class="comment-author vcard">
				<?php printf(__('<h5 class="fn">%s</h5>'), get_comment_author_link()) ?>
				
				<?php if ($comment->comment_approved == '0') : ?>
				<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'tt' ) ?></em>
				<br />
				<?php endif; ?>
				
				<div class="comment-meta">
				<span><?php echo human_time_diff( get_comment_time('U'), current_time('timestamp') ) . " " . __('ago', 'tt'); ?></span>
				</div>
			
			</div>
			
			<div class="comment-content">
				<?php comment_text() ?>
				<?php if( comments_open() ) { ?>
				<div class="reply btn btn-default btn-xs">
					<?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
				</div>
				<?php } ?>
			</div>
		<?php if ( 'div' != $args['style'] ) : ?>
		</div>
		<?php endif;
	}
}

// Social Sharing Buttons
if ( !function_exists('tt_social_sharing') ) {
	function tt_social_sharing() {
	?>
	  <div id="share-post" class="social-transparent primary-tooltips">
			<a href="http://www.facebook.com/share.php?u=<?php echo esc_url( get_permalink() ); ?>" target="_blank"><i class="fa fa-facebook" data-toggle="tooltip" title="<?php _e( 'Share on Facebook', 'tt' ); ?>"></i></a>
			<a href="http://twitter.com/share?text=<?php the_title(); ?>&url=<?php echo esc_url( get_permalink() ); ?>" target="_blank"><i class="fa fa-twitter" data-toggle="tooltip" title="<?php _e( 'Share on Twitter', 'tt' ); ?>"></i></a>
			<a href="https://plus.google.com/share?url=<?php echo esc_url( get_permalink() ); ?>" target="_blank"><i class="fa fa-google-plus" data-toggle="tooltip" title="<?php _e( 'Share on Google+', 'tt' ); ?>"></i></a>
			<?php 
			if ( has_post_thumbnail() ) {
				$attachment_id =  get_post_thumbnail_id();
				$attachment_array = wp_get_attachment_image_src( $attachment_id );
			?>
			<a href="http://pinterest.com/pin/create/button/?url=<?php echo esc_url( get_permalink() ); ?>&media=<?php echo $attachment_array[0]; ?>&description=<?php echo strip_tags( get_the_excerpt() ); ?>" target="_blank"><i class="fa fa-pinterest" data-toggle="tooltip" title="<?php _e( 'Share on Pinterest', 'tt' ); ?>"></i></a>
			<?php } ?>
		</div>
		<?php
	}
}


/* Page Banner
============================== */
if ( !function_exists('tt_page_banner') ) {
	function tt_page_banner() {
		if ( has_post_thumbnail( get_the_ID() ) ) {	
			$post_thumbnail_id = get_post_thumbnail_id();
			$post_thumbnail = wp_get_attachment_image_src( $post_thumbnail_id, 'full', true );
			?>
			<div id="page-banner" style="background-image: url(<?php echo $post_thumbnail[0]; ?>)">
				<div class="overlay"></div>
				<div class="container">
					<div class="banner-title">
						<?php the_title(); ?>
					</div>
				</div>
			</div>
			<?php 
		}
	}
}

/* Query - Taxonomy
============================== */
function tt_taxonomy_query( $query ) {
	if( is_tax() ) {
		// Search Results Per Page: Check for Theme Option
		global $realty_theme_option;
		$search_results_per_page = $realty_theme_option['search-results-per-page'];
		if ( $search_results_per_page ) {
			$query->set( 'posts_per_page', $search_results_per_page );
		}
  }
}
add_action( 'pre_get_posts', 'tt_taxonomy_query' );


/* User - Property Submit - Delete Images
============================== */
function tt_ajax_delete_uploaded_image_function() {
	delete_post_meta( $_POST['property_id'], 'estate_property_images', $_POST['image_id'] );
	die;
}
add_action( 'wp_ajax_tt_ajax_delete_uploaded_image_function', 'tt_ajax_delete_uploaded_image_function' );


/* Author Base 
http://wordpress.org/support/topic/how-to-change-author-base-without-front
============================== */
function tt_author_permalinks() {
	global $wp_rewrite;
	$wp_rewrite->author_base = 'agent';
	$wp_rewrite->author_structure = '/' . $wp_rewrite->author_base . '/%author%';
	
	// Assign User level "1" to agents, so they appear in author dropdown
	$all_agents = get_users( array( 'role' => 'agent', 'fields' => 'ID' ) );
	foreach( $all_agents as $agent_id ) {
		update_user_meta( $agent_id, 'wp_user_level', '1');	
	}
}
add_action( 'init', 'tt_author_permalinks' );


/* Add Role "agent". Run Once After Theme Activation.
http://advent.squareonemd.co.uk/controlling-wordpress-custom-post-types-capabilities-and-roles/
============================== */
function tt_add_role_agent() {
	global $wp_roles;
	$capabilities = array(
		'unfiltered_html' => true
	);
	//remove_role( 'agent' );
	add_role( 'agent', __( 'Agent', 'tt' ), $capabilities );
	// Remove "read" Capability For "subscriber" Role.
	$role_subscriber = get_role( 'subscriber' );
  $role_subscriber->remove_cap( 'read' );
}
add_action( 'after_switch_theme', 'tt_add_role_agent' );


/* Property Submit Listing - Edit link
============================== */
function tt_permalink_property_submit_edit($url) {
	global $post;	
	if ( is_page_template( 'template-property-submit-listing.php' ) ) {	
		// Get page that is using "Property Submit" Page Template
		$template_page_property_submit_array = get_pages( array (
			'meta_key' => '_wp_page_template',
			'meta_value' => 'template-property-submit.php'
			)
		);
		foreach( $template_page_property_submit_array as $template_page_property_submit ) {
			$submit_page = $template_page_property_submit->ID;
			break;
		}
		return get_permalink( $submit_page ) . '?edit=' . $post->ID;
	}
	else {
		return add_query_arg($_GET, $url);
	}
}
add_filter('the_permalink', 'tt_permalink_property_submit_edit');


/* PayPal Payment Button
============================== */
if ( !function_exists('tt_paypal_payment_button') ) {
	function tt_paypal_payment_button($property_id) {
		
		global $post, $realty_theme_option;
		
		if ( isset( $property_id ) ) {
			$property_id = $property_id;
		}
		else {
			$property_id = $post->ID;
		}
		
		$paypal_settings_enable = $realty_theme_option['paypal-enable'];
		$paypal_enable_subscription = $realty_theme_option['paypal-enable-subscription'];
		$paypal_subscription_recurrence = $realty_theme_option['paypal-subscription-recurrence'];
		$paypal_subscription_period = $realty_theme_option['paypal-subscription-period'];
		
		$paypal_settings_merchant_id = $realty_theme_option['paypal-merchant-id'];
		$paypal_settings_amount = $realty_theme_option['paypal-amount'];
		$paypal_featured_amount = $realty_theme_option['paypal-featured-amount'];
		
		$property_extra_featured = get_post_meta( $property_id, 'estate_property_featured', true );
		if ( $property_extra_featured ) {
			$paypal_settings_amount = $paypal_settings_amount + $paypal_featured_amount;
		}
		
		$paypal_settings_currency_code = $realty_theme_option['paypal-currency-code'];
		$paypal_settings_sandbox = $realty_theme_option['paypal-sandbox'];
		
		if ( $paypal_settings_enable && $paypal_settings_merchant_id && $paypal_settings_currency_code && $paypal_settings_amount ) {                                      
	
			$paypal_output  = '<div title="' . $paypal_settings_currency_code . ' ' . $paypal_settings_amount .'" data-toggle="tooltip" style="display:inline-block">';
			$paypal_output .= '<script async src= "' . TT_LIB_URI . '/paypal/paypal-button.min.js?merchant=' . $paypal_settings_merchant_id . '"';
			if ( $paypal_enable_subscription ) {
				$paypal_output .= 'data-button="subscribe"';
				$paypal_output .= 'data-recurrence="' . $paypal_subscription_recurrence . '"';
				$paypal_output .= 'data-period="' . $paypal_subscription_period . '"';
				$paypal_output .= 'data-src="1"'; // For Indefinite Cycles
			}
			else {
				$paypal_output .= 'data-button="buynow"';
			}
			if ( $paypal_settings_sandbox ) {
			$paypal_output .= 'data-env="sandbox"';
			}
			$paypal_output .= 'data-name="' . get_the_title() . '"';
			$paypal_output .= 'data-number="' . $property_id . '"';
			$paypal_output .= 'data-amount="' . $paypal_settings_amount . '"';
			$paypal_output .= 'data-currency="' . $paypal_settings_currency_code . '"';
			$paypal_output .= 'data-number="' . $property_id . '"';
			$paypal_output .= 'data-quantity="1"';
			$paypal_output .= 'data-shipping="0"';
			$paypal_output .= 'data-tax="0"';
			$paypal_output .= 'data-style="secondary"';
			$paypal_output .= 'data-size="small"';
			$paypal_output .= 'data-callback="' . TT_LIB_URI . '/paypal/ipn.php' . '"';
			$paypal_output .= '></script>';
			$paypal_output .= '</div>';
			
			return $paypal_output;
				
		}
	
	}
}

/* User Profile - Additional Fields
============================== */
function tt_custom_user_contact_methods( $user_contact ) {
	$user_contact['company_name'] 				= __( 'Company Name', 'tt' ); 
	$user_contact['office_phone_number'] 	= __( 'Office Phone Number', 'tt' ); 
	$user_contact['mobile_phone_number'] 	= __( 'Mobile Phone Number', 'tt' ); 
	$user_contact['fax_number'] 					= __( 'Fax Number', 'tt' );
	$user_contact['custom_facebook'] 			= __( 'Facebook', 'tt' );
	$user_contact['custom_twitter'] 			= __( 'Twitter', 'tt' );
	$user_contact['custom_google'] 				= __( 'Google+', 'tt' );
	$user_contact['custom_linkedin'] 			= __( 'Linkedin', 'tt' );
	return $user_contact;
}
add_filter('user_contactmethods', 'tt_custom_user_contact_methods');


/* User Profile - Additional Field "Image" 
http://www.flyinghippo.com/blog/adding-custom-fields-uploading-images-wordpress-users/ 
============================== */
function tt_user_profile_picture_field( $user ) { 
?>
<style type="text/css">
	.profile-upload-options th,
	.profile-upload-options td,
	.profile-upload-options input {
		margin: 0;
		vertical-align: top;
	}
	.user-preview-image {
		display: block;
		height: auto;
		width: 300px;
	}
</style>

<table class="form-table profile-upload-options">
	<tr>
		<th>
			<label for="user_image"><?php _e( 'Profile Picture', 'tt' ); ?></label>
		</th>
		<td>
			<img class="user-preview-image" src="<?php echo esc_attr( get_the_author_meta( 'user_image', $user->ID ) ); ?>"><br />
			<input type="text" name="user_image" id="user_image" value="<?php echo esc_attr( get_the_author_meta( 'user_image', $user->ID ) ); ?>" class="regular-text" />
			<input type='button' class="button-primary" value="Upload Image" id="uploadimage"/><br />
		</td>
	</tr>
</table>

<script type="text/javascript">
	(function($) {
		<?php add_thickbox(); ?>
		$( '#uploadimage' ).on( 'click', function() {
			tb_show( 'Profile Picture', 'media-upload.php?type=image&TB_iframe=1' );
			window.send_to_editor = function( html ) 
			{
				imgurl = $( 'img', html ).attr( 'src' );
				$( '#user_image' ).val(imgurl);
				tb_remove();
			}
			return false;
		});
	})(jQuery);
</script>
<?php 
}	
add_action( 'show_user_profile', 'tt_user_profile_picture_field' );
add_action( 'edit_user_profile', 'tt_user_profile_picture_field' );


/* User Profile - Save "Image" Field
============================== */
function tt_save_user_profile_picture_field( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}
	update_user_meta( $user_id, 'user_image', $_POST[ 'user_image' ] );
}
add_action( 'personal_options_update', 'tt_save_user_profile_picture_field' );
add_action( 'edit_user_profile_update', 'tt_save_user_profile_picture_field' );


/* User Profile - Delete User Image
============================== */
function tt_ajax_delete_user_profile_picture_function() {
	delete_user_meta( $_POST['user_id'], 'user_image' );
	die;
}
add_action( 'wp_ajax_tt_ajax_delete_user_profile_picture_function', 'tt_ajax_delete_user_profile_picture_function' );


/* Retrieve ttachment ID from file URL
https://pippinsplugins.com/retrieve-attachment-id-from-image-url/
============================== */
if ( !function_exists('tt_get_image_id') ) {
	function tt_get_image_id( $image_url ) {
		global $wpdb;
		$attachment = $wpdb->get_col( $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ) ); 
	  return $attachment[0]; 
	}
}

/* Theme Option - Body Classes
============================== */
function tt_body_classes( $classes ) {
	global $realty_theme_option;	
	if ( $realty_theme_option['enable-rtl-support'] ) {
		$classes[] = 'rtl';
	}
	if ( $realty_theme_option['site-header-position-fixed'] ) {
		$classes[] = 'fixed-header';
	}
	return $classes;
}
add_filter( 'body_class', 'tt_body_classes' );


/* Page ID of Page Template "Property Search"
============================== */
if ( !function_exists('tt_page_id_template_search') ) {
	function tt_page_id_template_search() {
		$template_page_property_search_array = get_pages( array (
			'meta_key' => '_wp_page_template',
			'meta_value' => 'template-property-search.php'
			)
		);
		foreach( $template_page_property_search_array as $template_page_property_search ) {
			return $template_page_property_search = $template_page_property_search->ID;
			break;
		}
	}
}

/* Open Graph Meta Tag (Facebook Sharer Thumbnail Assignment)
============================== */
function tt_meta_open_graph() {
	global $post;
	if ( is_singular() ) {
		if( has_post_thumbnail( $post->ID ) ) {
			$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
			echo '<meta property="og:image" content="' . esc_attr( $thumbnail_src[0] ) . '" />
';
		}
	}
}
add_action( 'wp_head', 'tt_meta_open_graph', 5 );

/* Custom Styles
============================== */
function tt_custom_styles() {
	
	global $realty_theme_option;
	$color_accent = $realty_theme_option['color-accent'];
	$color_header = $realty_theme_option['color-header'];
	$home_slideshow_custom_height = $realty_theme_option['home-slideshow-custom-height'];
	$map_custom_height = $realty_theme_option['map-height-custom-home-search'];
	$property_image_custom_height = $realty_theme_option['property-image-custom-height'];
	
	echo "\n<style>\n";
	
	if ( $realty_theme_option['map-height-type-home-search'] == "custom" && $map_custom_height ) {
	echo "body.page-template-template-home-properties-map-php #google-map, body.page-template-template-property-search-php #google-map { height: $map_custom_height"."px; }\n";
	}
	
	if ( $realty_theme_option['home-slideshow-height-type'] == "custom" && $home_slideshow_custom_height ) {
	echo "#home-slideshow, #property-slideshow { height: $home_slideshow_custom_height"."px; }\n";
	}
	
	if ( $realty_theme_option['property-image-height'] == "custom" && $property_image_custom_height ) {
	echo ".property-image-container { height: $property_image_custom_height"."px; }\n";
	}
	 
	echo "a, #footer h5.title, #map #map-marker-container .content .title, #map .map-marker-container .content .title, body.single-property #property-features li i.fa-check, ul#sidebar li.widget .widget-content table a { color: $color_accent; }\n";
	
	echo "input:focus, .form-control:focus, input:active, .form-control:active, ul#sidebar li.widget .wpcf7 textarea:focus, #footer li.widget .wpcf7 textarea:focus, ul#sidebar li.widget .wpcf7 input:not([type='submit']):focus, #footer li.widget .wpcf7 input:not([type='submit']):focus, .chosen-container.chosen-container-active .chosen-single, .chosen-container .chosen-drop { border-color: $color_accent }\n";
	
	echo ".primary-tooltips .tooltip.top .tooltip-arrow, .arrow-down, .sticky .entry-header { border-top-color: $color_accent }\n";
	echo ".primary-tooltips .tooltip.right .tooltip-arrow, .arrow-left { border-right-color: $color_accent }\n";
	echo ".primary-tooltips .tooltip.bottom .tooltip-arrow, .arrow-up { border-bottom-color: $color_accent }\n";
	echo ".primary-tooltips .tooltip.left .tooltip-arrow, .arrow-right, #home-slideshow .description .arrow-right, #property-slideshow .description .arrow-right { border-left-color: $color_accent }\n";
	echo "body.rtl #home-slideshow .description .arrow-right, body.rtl #property-slideshow .description .arrow-right { border-right-color: $color_accent; border-left-color: transparent !important; }\n";
	
	echo ".btn-primary, .btn-primary:focus, input[type='submit'], .primary-tooltips .tooltip-inner, .content-with-details > div .on-hover, #fixed-controls a:hover, header.navbar .navbar-nav > ul > li::after, header.navbar nav > div > ul > li::after, header.navbar .navbar-nav > ul > li.current-menu-item::after, header.navbar nav > div > ul > li.current-menu-item::after, header.navbar .navbar-nav > ul > li.current-menu-parent::after, header.navbar nav > div > ul > li.current-menu-parent::after, header.navbar .navbar-nav > ul > li.current-menu-ancestor::after, header.navbar nav > div > ul > li.current-menu-ancestor::after, #footer #footer-bottom .social-transparent a:hover, #footer #footer-bottom #up:hover, #home-slideshow .title, #property-slideshow .title, #pagination .page-numbers li .current, #pagination .page-numbers li .current:hover, #map-wrapper #map-controls .control.active, .owl-theme .owl-controls .owl-nav [class*='owl-'], .property-item.featured::after, .property-item .property-excerpt::after, .datepicker table tr td.active.active, .datepicker table tr td.active:hover.active, .noUi-connect, body.single-property #property-status-update span { background-color: $color_accent }\n";
	
	echo "header.navbar, header.navbar #login-bar-header a, header.navbar .navbar-brand a, header.navbar, header.navbar .navbar-nav > ul > li:hover, header.navbar nav > div > ul > li:hover, header.navbar .navbar-nav > ul > li a, header.navbar nav > div > ul > li a, header.navbar .navbar-nav > ul > li ul.sub-menu li:hover a, header.navbar nav > div > ul > li ul.sub-menu li:hover a { color: $color_header }\n";
	
	// Theme Options: Custom Styles
	echo $realty_theme_option['custom-styles']."\n";
	
	if ( $realty_theme_option['enable-rtl-support'] ) {
	echo "[class^='flexslider'] { direction: rtl; }";
	}
	
	echo "</style>\n";

}
add_action( 'wp_head', 'tt_custom_styles', 151 ); // Fire after Redux


/* Custom Scripts
============================== */
function tt_custom_scripts() {
global $realty_theme_option;
?>
<script>
jQuery(document).ready(function() {

<?php if ( $realty_theme_option['home-slideshow-speed'] ) { ?>

jQuery('#home-slideshow.flexslider, #property-slideshow.flexslider').flexslider({
smoothHeight: 	true,
animation: 			'fade',
slideshow: 			true,
slideshowSpeed: <?php echo $realty_theme_option['home-slideshow-speed']; ?>,
controlNav: 		false,
start: 					function() {
	                                
									jQuery('.flex-active-slide').find('.container').addClass('in');
									jQuery('.spinner').delay(400).fadeOut(400, function(){
								       jQuery(this).remove();
												
									});
									
									
									
								},
before: 				function() {
	                  
  									jQuery('.flex-active-slide').find('.container').removeClass('in');
  									jQuery('.flex-active-slide').find('.container').addClass('out');
								},
after: 					function() {
										
										jQuery('.slides').find('.container').removeClass('out');
										jQuery('.flex-active-slide').find('.container').addClass('in');
								},
});
<?php } ?>
}); // END document.ready
jQuery(window).load(function() {
jQuery(function() {
    jQuery("img.lazy, li.lazy").lazyload({
		effect: "fadeIn"
		
    });
});

<?php 
// Home & Search Results Map Height
if ( $realty_theme_option['map-height-type-home-search'] == "fullscreen" ) { ?>
if ( jQuery('body.page-template-template-home-properties-map-php #google-map, body.page-template-template-property-search-php #google-map').hasClass('fullscreen') ) {
	jQuery('body.page-template-template-home-properties-map-php #google-map, body.page-template-template-property-search-php #google-map').css( 'height', heightFullscreen );
}
<?php }

// Home Slideshow Height
if ( $realty_theme_option['home-slideshow-height-type'] == "fullscreen" ) { ?>
if ( jQuery('#home-slideshow, #property-slideshow').hasClass('fullscreen') ) {
	jQuery('#home-slideshow, #property-slideshow').css( 'height', heightFullscreen );
}
<?php } ?>
<?php
/*if ( $realty_theme_option['home-slideshow-height-type'] == "fullscreen-image" ) { ?>
if ( jQuery('#home-slideshow ul li, #property-slideshow ul li').hasClass('fullscreen-image') ) {
	jQuery(this).css( 'background-size', windowWidth+'px');
}
<?php }*/
// Property Image Height - Fullscreen
if ( $realty_theme_option['property-image-height'] == "fullscreen" ) { ?>

if ( isMobile ) {
	var heightFullscreenBoxed = heightFullscreen - 15; // margin-top to header
}
else {
	var heightFullscreenBoxed = heightFullscreen - 50; // margin-top to header
}

if ( jQuery('#property-layout-boxed .property-image-container').hasClass('cut') ) {
	jQuery('#property-layout-boxed .property-image').css( 'height', heightFullscreenBoxed );
}
else {
	jQuery('#property-layout-boxed .property-image').css( 'height', heightFullscreenBoxed );
}

if ( jQuery('#property-layout-full-width .property-image-container').hasClass('cut') ) {
	jQuery('#property-layout-full-width .property-image').css( 'height', heightFullscreen );
}

else {
	jQuery('#property-layout-full-width .property-image').css( 'height', heightFullscreen );
}
<?php 
}
if ( $realty_theme_option['slideshow-width-type'] == "full" ) { ?>
	jQuery('#home-slideshow ul.slides li').css( 'width', windowWidth+'px' );
<?php 
}
if ( $realty_theme_option['property-image-width'] == "full" ) { ?>
	jQuery('#property-slideshow ul.slides li').css( 'width', windowWidth+'px' );
<?php }
	
// Property Image Height - Custom
if ( $realty_theme_option['property-image-height'] == "custom" ) { ?>
if ( jQuery('.property-image-container').hasClass('cut') ) {
	jQuery('.property-image-container .property-image, .property-image-container iframe').css( 'height', <?php echo $realty_theme_option['property-image-custom-height']; ?> );
}
<?php }

// Property - Lightbox
if ( $realty_theme_option['property-lightbox'] == "magnific-popup" ) { ?>
jQuery('body.single-property .property-image').magnificPopup({ 
	type: 		'image',
	gallery: 	{
		enabled: 	true,
		tPrev: 		'',
		tNext: 		'',
		tCounter: '%curr% | %total%'
	}
});
<?php
}
else if ( $realty_theme_option['property-lightbox'] == "intense-images" ) {
?>
jQuery('body.single-property .property-image').each(function() {
	Intense( jQuery(this) );
});
/*
var elements = document.querySelectorAll( '.property-image' );
Intense( elements );
*/
<?php } ?>

// Datepicker
<?php
$datepicker_language = $realty_theme_option['datepicker-language'];
$price_thousands_separator = $realty_theme_option['price-thousands-separator'];

if ( isset( $datepicker_language ) ) { ?>
jQuery('.datepicker').datepicker({
language: '<?php echo $datepicker_language; ?>',
autoclose: true,
format: "yyyymmdd"
});
<?php } ?>

// Price Range
if ( jQuery('.price-range-slider').length ) {
	
var priceFormat;
jQuery('.price-range-slider').noUiSlider({
	
	start: [ <?php if ( isset( $_GET['price_range_min'] ) ) { echo $_GET['price_range_min']; } else if ( $realty_theme_option['property-search-price-range-min'] ) { echo $realty_theme_option['property-search-price-range-min']; } else { echo "0"; } ?>, <?php if ( isset( $_GET['price_range_max'] ) ) { echo $_GET['price_range_max']; } else if ( $realty_theme_option['property-search-price-range-max'] ) { echo $realty_theme_option['property-search-price-range-max']; } else { echo "0"; } ?> ],
	step: <?php if ( $realty_theme_option['property-search-price-range-step'] ) { echo $realty_theme_option['property-search-price-range-step']; } else {
		echo "0"; } ?>,
	range: {
		'min': [  <?php if ( $realty_theme_option['property-search-price-range-min']) { echo $realty_theme_option['property-search-price-range-min']; } else { echo "0"; } ?> ],
		'max': [  <?php if ( $realty_theme_option['property-search-price-range-max']) { echo $realty_theme_option['property-search-price-range-max']; } else { echo "0"; } ?> ]
	},
	format: wNumb({
		decimals: 0,
		<?php 
		if ( $price_thousands_separator ) { echo "thousand: '" . $price_thousands_separator . "',"; }
		if ( $realty_theme_option['currency-sign-position'] == 'left' ) { echo "prefix: '"; } else { echo "postfix: '"; } echo $realty_theme_option['currency-sign'] . "',"; 
		?>
	}),
	connect: true,
	<?php if ( $realty_theme_option['enable-rtl-support'] ) { ?>
	direction: 'rtl'
	<?php } ?>
	
});

priceFormat = wNumb({
	decimals: 0,
	<?php 
	if ( $price_thousands_separator ) { echo "thousand: '" . $price_thousands_separator . "',"; }
	if ( $realty_theme_option['currency-sign-position'] == 'left' ) { echo "prefix: '"; } else { echo "postfix: '"; } echo $realty_theme_option['currency-sign'] . "',"; 
	?>
});

jQuery('.price-range-slider').Link('lower').to(jQuery('.price-range-min'));
jQuery('.price-range-slider').Link('upper').to(jQuery('.price-range-max'));

}

// AJAX
function tt_ajax_search_results() {
	"use strict";
	
	if ( jQuery('.price-range-slider').length ) {
		
		var price_range, min_price, max_price;
		
		price_range			=   jQuery('.price-range-slider').val();
		min_price 			= 	priceFormat.from( price_range[0] );
		max_price 			= 	priceFormat.from( price_range[1] );
		
		jQuery('.property-search-price-range-min').val(min_price);
		jQuery('.property-search-price-range-max').val(max_price);
	
	}
	
	if ( jQuery('.property-search-feature') ) {	
		var feature = [];
		jQuery('.property-search-feature:checked').each(function() {
		  feature.push( jQuery(this).val() );
		});
	}
	
	var ajaxData = jQuery('.property-search-form').first().serialize() + "&action=tt_ajax_search&base=" + window.location.pathname;
	
	jQuery.ajax({
	  
	  type: 'GET',
	  url: ajaxURL,
	  data: ajaxData,
	  success: function (response) {
	    jQuery('#property-items').html(response); // Show response from function tt_ajax_search()
	  },
	  error: function () {
	  	console.log( 'failed' );
	  }
	  
	});

}

// Remove Map Markers & Marker Cluster
function removeMarkers() {
	// http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/examples/speed_test.js
  for( i = 0; i < newMarkers.length; i++ ) {
  	newMarkers[i].setMap(null);
		// Close Infoboxes
  	if ( newMarkers[i].infobox.getVisible() ) {
    	newMarkers[i].infobox.hide();
  	}
  }
  if ( markerCluster ) { 
  	markerCluster.clearMarkers();
  }
  markers = [];
  newMarkers = [];
  bounds = [];
}
	
// Fire Search Results Ajax On Search Field Change (Exclude Datepicker)
jQuery('.price-range-slider, .property-search-form select, .property-search-form input').not('.datepicker').change(function() {	
	tt_ajax_search_results();
	if ( jQuery('#google-map').length > 0 ) {
		removeMarkers();	
	}	
});

// Fire Search Results Ajax On Search Field "Datepicker" Change
jQuery('.property-search-form input.datepicker').on('changeDate', function() {	
	tt_ajax_search_results();	
	if ( jQuery('#google-map').length > 0 ) {
		removeMarkers();	
	}	
});
         /* RTL-Navigation for pagination-------------------------*/

<?php if ( $realty_theme_option['enable-rtl-support'] ) { ?>
		jQuery('.fa-angle-right').addClass('right-one').removeClass('fa-angle-right');
		jQuery('.fa-angle-left').addClass('left-one').removeClass('fa-angle-left');
		jQuery('.right-one').addClass('fa-angle-left').removeClass('right-one')
		jQuery('.left-one').addClass('fa-angle-right').removeClass('left-one')
<?php } ?>


}); // END window.load

/* Navigation-------------------------*/

<?php if(!$realty_theme_option['show-sub-menu-by-default-on-mobile']){ ?>
      
	
	jQuery('.menu-item-has-children, .menu-item-language').click(function() {
		if ( jQuery('body').hasClass('show-nav') ) {
			jQuery(this).find('.sub-menu').toggleClass('show');
		}
	});
<?php } else {?>
      jQuery('.navbar-toggle').click(function() {
      	jQuery('.sub-menu').toggleClass('show');
      });
	  jQuery('.menu-item-has-children, .menu-item-language').click(function() {
		 jQuery(this).find('.sub-menu').toggleClass('show');
	  });
<?php } ?>
<?php
// Theme Options: Custom Scripts
echo $realty_theme_option['custom-scripts']."\n";
?>
</script>	
<?php
}
add_action( 'wp_footer', 'tt_custom_scripts', 20 );

/*-------in case date formate is not trasnlsated...*/

function translate_date_format($format) {
        if (function_exists('icl_translate')){
          $format = icl_translate('Formats', $format, $format);
		}
		return $format;
}
add_filter('option_date_format', 'translate_date_format');