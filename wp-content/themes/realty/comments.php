<?php
if ( post_password_required() ) { 
	return;
}
?>

<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>

	<div class="comments-title custom-heading">
		<h3 class="title"><span><?php printf( _n( __( 'One Comment', 'tt' ), __( '%1$s Comments', 'tt' ), get_comments_number(), 'tt' ), number_format_i18n( get_comments_number() ), get_the_title() ); ?></span></h3>
	</div>

	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
	<?php endif; // Check for comment navigation. ?>

	<ul class="comment-list">
		<?php
			wp_list_comments( array( 'callback' => 'tt_list_comments' ) );
		?>
	</ul>

	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
	<nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
		<h1 class="sr-only"><?php _e( 'Comment navigation', 'tt' ); ?></h1>
		<div class="nav-previous"><?php previous_comments_link( __( '&laquo; Older Comments', 'tt' ) ); ?></div>
		<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &raquo;', 'tt' ) ); ?></div>
		<div class="clearfix"></div>
	</nav>
	<?php endif; // Check for comment navigation. ?>

	<?php if ( ! comments_open() ) : ?>
	<p class="no-comments"><?php _e( 'Comments are closed.', 'tt' ); ?></p>
	<?php endif; ?>

	<?php endif; // have_comments() ?>

	<div id="reply-title" class="clearfix">
		<h3 class="title"><span><?php _e( 'Leave a Reply', 'tt' ); ?></span></h3>
	</div>

	<?php
	
	$commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );

	$fields =  array(
	  'author' 	=> '<div class="row"><div class="col-sm-4 comment-form-author"><input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '"' . $aria_req . ' placeholder="' . __( 'Name', 'tt' ) . '" /></div>',
		'email' 	=> '<div class="col-sm-4 comment-form-email"><input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '"' . $aria_req . ' placeholder="' . __( 'Email', 'tt' ) . '" /></div>',	
	  'url' 		=> '<div class="col-sm-4 comment-form-url"><input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" placeholder="' . __( 'Website', 'tt' ) . '" /></div></div>',	
	);
	
	$custom_args = array (
		'title_reply'       	=> __( ' ', 'tt' ),
		'comment_notes_after' => '',
		'cancel_reply_link' 	=> __( 'Cancel Reply', 'tt' ),
		'label_submit'      	=> __( 'Submit Comment', 'tt' ),
		'comment_notes_before'=> '',
		'comment_field'				=> '<div class="comment-form-comment form-group"><textarea id="comment"  class="form-control" name="comment" cols="45" rows="8" aria-required="true" placeholder="' . _x( 'Comment', 'noun', 'tt' ) .'"></textarea></div>',
		'fields'							=> $fields
	);
		
	comment_form($custom_args); 
	
	?>

</div><!-- #comments -->