<?php
/* AGENTS
============================== */
$all_agents = get_users( array( 'role' => 'agent', 'fields' => 'ID' ) );

foreach( $all_agents as $agent ) {

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
if ( $facebook || $twitter || $google || $linkedin ) {
	$no_socials = false;
}
else {
	$no_socials = true;
}
?>
<div>
	<div class="owl-thumbnail">
		<a href="<?php echo $author_profile_url; ?>">
			<?php 
			if ( $profile_image ) {
				$profile_image_id = tt_get_image_id( $profile_image );
				$profile_image_array = wp_get_attachment_image_src( $profile_image_id, 'square-400' );
				echo '<img src="' . $profile_image_array[0] . '" alt="" />';
			}
			else {
				echo '<img src="//placehold.it/400x400/eee/ccc/&text=.." alt="" />';
			} 
			?>
		</a>
	</div>
	<div class="content-with-details">
		<div class="agent-details<?php if ( $no_socials ) { echo " no-details"; } ?>">
			<?php 
			if ( $first_name && $last_name ) {
				echo '<h4 class="title">' . $first_name . ' ' . $last_name . '</h4>';	
			}
			?>
			<?php if ( $email ) { ?><div class="contact"><i class="fa fa-envelope-o"></i><a href="mailto:<?php echo antispambot( $email ); ?>"><?php echo antispambot( $email ); ?></a></div><?php } ?>
			<?php if ( $office ) { ?><div class="contact"><i class="fa fa-phone"></i><?php echo $office; ?></div><?php } ?>
			<?php if ( $mobile ) { ?><div class="contact"><i class="fa fa-mobile"></i><?php echo $mobile; ?></div><?php } ?>
			<?php if ( $facebook || $twitter || $google || $linkedin ) { ?>
			<div class="on-hover">
				<?php
				if ( $facebook ) { ?>
				<a href="<?php echo $facebook; ?>"><i class="fa fa-facebook"></i></a>
				<?php }
				if ( $twitter ) { ?>
				<a href="<?php echo $twitter; ?>"><i class="fa fa-twitter"></i></a>
				<?php }
				if ( $google ) { ?>
				<a href="<?php echo $google; ?>"><i class="fa fa-google-plus"></i></a>
				<?php }
				if ( $linkedin ) { ?>
				<a href="<?php echo $linkedin; ?>"><i class="fa fa-linkedin"></i></a>
				<?php }	?>
			</div>
			<?php }	?>
		</div>
	</div>
</div>
<?php } ?>
</div><!-- .owl-carousel -->