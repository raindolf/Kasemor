<form role="search" method="get" class="search-form" action="<?php esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php _x( 'Search for:', 'label', 'tt' ); ?></span>
		<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search for &hellip;', 'placeholder', 'tt' ); ?>" value="<?php get_search_query(); ?>" name="s" title="<?php esc_attr_x( 'Search for:', 'label', 'tt' ); ?>" />
	</label>
</form>