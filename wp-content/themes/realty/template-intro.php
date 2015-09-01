<?php get_header();
/*
Template Name: Intro
*/

while ( have_posts() ) : the_post();
$post_featured_image_id = get_post_thumbnail_id();
$post_featured_image = wp_get_attachment_image_src( $post_featured_image_id, 'full', true );
?>

<div id="intro-wrapper"<?php if ( has_post_thumbnail() ) { ?> style="background-image:url(<?php echo $post_featured_image[0]; ?>)"<?php } ?>>

	<?php 
	
	$intro_slideshow_images = get_post_meta( get_the_ID(), 'estate_intro_fullscreen_background_slideshow_images', false );
	//$video_url = get_post_meta( get_the_ID(), "estate_intro_fullscreen_background_video_url", true );
	
	if ( $intro_slideshow_images ) {	
	?>
	
	<div class="flexslider loading">
		<ul class="slides">					
		<?php
		$args = array(
			'post_type' => 'attachment',
			'orderby' => 'post__in',
			'post__in' => $intro_slideshow_images,
			'posts_per_page' => count($intro_slideshow_images)
		);
		
		$gallery_array = get_posts( $args );
		
		foreach ($gallery_array as $slide) {
			$attachment = wp_get_attachment_image_src( $slide->ID, 'full' );
		?>
			<li style="background-image:url(<?php echo $attachment[0]; ?>)"></li>
		<?php }	?>
		</ul>
	</div>
	<?php }	?>
	
	<div class="wrapper">
		<div class="inner">
			
			<div class="intro-bg"></div>
			<div class="intro-left-bg"></div>
			<div class="intro-right-bg"></div>
			
			<div class="container">
				
				<div class="col-sm-6 intro-left">
					<?php the_content(); ?>		
				</div>
				
				<div class="col-sm-6 intro-right">
					<div class="intro-search">
						<div class="intro-title">
							<h4 class="title"><?php _e( 'Property Search', 'tt' ); ?></h4>
							<a href="#" class="toggle"><?php _e( 'Show Map', 'tt' ); ?></a>
						</div>
						<?php get_template_part( 'lib/inc/template/search-form' ); ?>
					</div>
					<div class="intro-map transform">
						<div class="intro-title">
							<h4 class="title"><?php _e( 'Property Map', 'tt' ); ?></h4>
							<a href="#" class="toggle"><?php _e( 'Show Search', 'tt' ); ?></a>
						</div>
						<?php get_template_part( 'lib/inc/template/google-map-multiple-properties' ); ?>
					</div>
				</div>
				
			</div>
			
			<div class="social">
				<?php
				$facebook = $realty_theme_option['social-facebook'];
				$twitter = $realty_theme_option['social-twitter'];
				$google = $realty_theme_option['social-google'];
				$linkedin = $realty_theme_option['social-linkedin'];
				$pinterest = $realty_theme_option['social-pinterest'];
				$instagram = $realty_theme_option['social-instagram'];
				$youtube = $realty_theme_option['social-youtube'];
				$skype = $realty_theme_option['social-skype'];

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
				<?php }
				if ( $pinterest ) { ?>
				<a href="<?php echo $pinterest; ?>"><i class="fa fa-pinterest"></i></a>
				<?php }
				if ( $instagram ) { ?>
				<a href="<?php echo $instagram; ?>"><i class="fa fa-instagram"></i></a>
				<?php }
				if ( $youtube ) { ?>
				<a href="<?php echo $youtube; ?>"><i class="fa fa-youtube"></i></a>
				<?php }
				if ( $skype ) { ?>
				<a href="<?php echo $skype; ?>"><i class="fa fa-skype"></i></a>
				<?php }	?>
			</div>
					
		</div>
	</div>
	
</div>

<a href="#" id="toggle-intro-wrapper"><i class="fa fa-expand"></i></a>

<?php
endwhile;
get_footer(); 
?>