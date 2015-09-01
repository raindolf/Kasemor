<?php 
get_header();

$hide_sidebar = get_post_meta( get_the_ID(), 'estate_page_hide_sidebar', true );
$author = get_user_by( 'slug', get_query_var( 'author_name' ) );
$agent = $author->ID;
$author_has_published_properties = true; // Always show public profile, even when user has no published properties

// Query: Has user published any properties?
$property_args = array(
	'post_type' 				=> 'property',
	'posts_per_page' 		=> -1,
	'author'						=> $agent,
);

// Query 2: Is agent assigned to any properties?
$property_args_agent_assigned = array(
	'post_type' 				=> 'property',
	'posts_per_page' 		=> -1,
	'author__not_in'		=> $agent,
	'meta_query' 				=> array(
		array(
			'key' 		=> 'estate_property_custom_agent',
			'value' 	=> $author->ID,
			'compare'	=> '='
		)
	)
);

// Create two queries
$query_property = new WP_Query( $property_args );
$query_property_assigned_agent = new WP_Query( $property_args_agent_assigned );
$query_combined_results = new WP_Query();

// Set posts and post_count
$query_combined_results->posts = array_merge( $query_property->posts, $query_property_assigned_agent->posts );
$query_combined_results->post_count = $query_property->post_count + $query_property_assigned_agent->post_count;

// Check if user has any published or assigned properties
if ( $query_combined_results->post_count ) {
	$author_has_published_properties = true;
}

$query_property = new WP_Query( $property_args );
if ( $query_property->have_posts() ) : $query_property->the_post();
	$author_has_published_properties = true;
wp_reset_query();
endif;

?>

<div class="row">
	
	<?php 
	// Check for Agent Sidebar
	if ( !$hide_sidebar && is_active_sidebar( 'sidebar_agent' ) ) {
		echo '<div class="col-sm-8 col-md-9">';
	} else {
		echo '<div class="col-sm-12">';
	}
	?>

	<div id="agent" class="content-box">
		<?php
		
		// Display author info only, if user has published properties
		if ( $author_has_published_properties ) {
		
			$company_name = get_user_meta( $agent, 'company_name', true );
			$first_name = get_user_meta( $agent, 'first_name', true );
			$last_name = get_user_meta( $agent, 'last_name', true );
			$email = get_userdata( $agent );
			$email = $email->user_email;
			$office = get_user_meta( $agent, 'office_phone_number', true );
			$mobile = get_user_meta( $agent, 'mobile_phone_number', true );
			$fax = get_user_meta( $agent, 'fax_number', true );
			$website = get_userdata( $agent );
			$website = $website->user_url;
			$website_clean = str_replace( array( 'http://', 'https://' ), '', $website );
			$bio = get_user_meta( $agent, 'description', true );
			$profile_image = get_user_meta( $agent, 'user_image', true );
			$author_profile_url = get_author_posts_url( $agent );
			$facebook = get_user_meta( $agent, 'custom_facebook', true );
			$twitter = get_user_meta( $agent, 'custom_twitter', true );
			$google = get_user_meta( $agent, 'custom_google', true );
			$linkedin = get_user_meta( $agent, 'custom_linkedin', true );
			
		?>

		<section class="row">
			<?php
			if ( $profile_image ) {
				$profile_image_id = tt_get_image_id( $profile_image );
				$profile_image_array = wp_get_attachment_image_src( $profile_image_id, 'square-400' );
				echo '<div class="col-sm-4">';
				echo '<img src="' . $profile_image_array[0] . '" />';
				echo '<div class="social-transparent">';
				if ( $facebook ) { echo '<a href="' . $facebook . '" target="_blank"><i class="fa fa-facebook"></i></a>'; }
				if ( $twitter ) { echo '<a href="' . $twitter . '" target="_blank"><i class="fa fa-twitter"></i></a>'; }
				if ( $google ) { echo '<a href="' . $google . '" target="_blank"><i class="fa fa-google"></i></a>'; }
				if ( $linkedin ) { echo '<a href="' . $linkedin . '" target="_blank"><i class="fa fa-linkedin"></i></a>'; }
				echo '</div>';
				echo '</div>';					
				echo '<div class="col-sm-8">';
			}	
			else {
				echo '<div class="col-sm-12">';
			}
				
			if ( $first_name && $last_name ) {
				echo '<h3 class="title">' . $first_name . ' ' . $last_name . '</h3>';
				if ( $company_name ) {
					echo '<p>' . $company_name . '</p>';
				}
			}
			else if ( $company_name ) {
				echo '<h3 class="title">' . $company_name . '</h3>';
			}
			?>
			
			<?php 
			if ( $email ) { ?><div class="contact"><i class="fa fa-envelope-o"></i><a href="mailto:<?php echo antispambot( $email ); ?>"><?php echo antispambot( $email ); ?></a></div><?php }
			if ( $office ) { ?><div class="contact"><i class="fa fa-phone"></i><?php echo $office; ?></div><?php }
			if ( $mobile ) { ?><div class="contact"><i class="fa fa-mobile"></i><?php echo $mobile; ?></div><?php }
			if ( $fax ) { ?><div class="contact"><i class="fa fa-fax"></i><?php echo $fax; ?></div><?php }
			if ( $website ) { ?><div class="contact"><i class="fa fa-globe"></i><a href="<?php echo $website; ?>" target="_blank"><?php echo $website_clean; ?></a></div><?php }
			if ( $bio ) { ?><div class="description"> <?php $trim = wp_trim_words( $bio, 40, '..' ); echo '<p>' . $bio . '</p>';	?></div><?php }
			?>				
			</div><!-- .col-sm-xx -->
		</section>

		<h4 class="section-title"><span><?php _e( 'Contact', 'tt' ); ?></span></h4>
		<form id="contact-form" method="post" action="<?php echo admin_url( 'admin-ajax.php' ); ?>">

				<div class="row primary-tooltips">
				
					<div class="form-group col-sm-4">
          	<input type="text" name="name" id="name" class="form-control" placeholder="<?php _e( 'Name', 'tt' ); ?>" title="<?php _e( 'Please enter your name.', 'tt' ); ?>">
          	<input type="text" name="email" id="email" class="form-control" placeholder="<?php _e( 'Email', 'tt' ); ?>" title="<?php _e( 'Please enter your email.', 'tt' ); ?>">
          	<input type="text" name="phone" id="phone" class="form-control" placeholder="<?php _e( 'Phone', 'tt' ); ?>" title="<?php _e( 'Please enter only digits for your phone number.', 'tt' ); ?>">
					</div>
					
					<div class="form-group col-sm-8">	
          	<textarea name="message" id="comment" class="form-control" placeholder="<?php _e( 'Message', 'tt' ); ?>" title="<?php _e( 'Please enter your message.', 'tt' ); ?>"></textarea>
					</div>

				</div>
				
				<input type="submit" name="submit" value="<?php _e( 'Send Message', 'tt' ); ?>" >
				
        <input type="hidden" name="action" value="submit_property_contact_form" />
        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce(); ?>" />
        <?php 
        // Check If Agent Has An Email Address
        if ( $email ) { 
        ?>
        	<input type="hidden" name="agent_email" value="<?php echo antispambot( $email ); ?>">
        <?php 
        } 
        // No Agent Email Address Found -> Send Email To Site Administrator
        else { ?>
	        <input type="hidden" name="agent_email" value="<?php echo antispambot( $property_contact_form_default_email ); ?>">
	      <?php } ?>
        <input type="hidden" name="property_title" value="<?php echo get_the_title( get_the_ID() ); ?>" />
        <input type="hidden" name="property_url" value="<?php echo get_permalink( get_the_ID() ); ?>" />

      </form>
    
    <div id="form-success" class="hide alert alert-success alert-dismissable">
    	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    	<?php _e( 'Message has been sent successfully.', 'tt' ); ?>
    </div>
    <div id="form-submitted"></div>

	</div><!-- #agent -->
	
	<?php 
	//get_template_part( 'lib/inc/template/property', 'featured' );
	if ( $query_combined_results->have_posts() ) : 
	
		echo '<div id="property-items"><div class="owl-carousel-2">';
		while ( $query_combined_results->have_posts() ) : $query_combined_results->the_post();
		get_template_part( 'lib/inc/template/property', 'item' );	
		endwhile;
		wp_reset_query();
		echo '</div></div>';
	
	endif;
	
	} // END if user has published properties
	else {
		echo '<p>' . __( 'Publish at least one property to enable your public user profile.', 'tt' ) . '</p>';
		echo '</div><!-- #agent -->';
	}
	?>
	
	</div><!-- .col-sm-8 -->
	
	<?php 
	// Check for Agent Sidebar
	if ( !$hide_sidebar && is_active_sidebar( 'sidebar_agent' ) ) : 
	?>
	<div class="col-sm-4 col-md-3">
		<ul id="sidebar">
			<?php dynamic_sidebar( 'sidebar_agent' ); ?>
		</ul>
	</div>
	<?php endif; ?>
	
</div><!-- .row -->

<?php get_footer(); ?>