<?php 
// Check for Blog Sidebar
if ( is_active_sidebar( 'sidebar_blog' ) ) : 
?>
<ul id="sidebar">
	<?php dynamic_sidebar( 'sidebar_blog' ); ?>
</ul>
<?php endif; ?>