<?php
/**
 * Partial template for pagination.
 * Creates numbered pagination or displays button for infinite scroll based on user selection
 * @since 1.0.0
 */
if ( 'infinite' == themify_get( 'setting-more_posts' ) || '' == themify_get( 'setting-more_posts' ) ) {
	global $wp_query, $themify;
	$total_pages = $wp_query->max_num_pages;
	$current_page = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
	if ( $total_pages > $current_page ) {
		if ( $themify->query_category != '' ) {
			//If it's a Query Category page, set the number of total pages
			echo '<script type="text/javascript">var qp_max_pages = ' . $total_pages . '</script>';
		}
		echo '<p data-current-page="'. $current_page .'" id="load-more"><a href="' . next_posts( $total_pages, false ) . '">' . __( 'Load More', 'themify' ) . '</a></p>';
	}
} else {
	if ( 'numbered' == themify_get( 'setting-entries_nav' ) || '' == themify_get( 'setting-entries_nav' ) ) {
		themify_pagenav();
	} else { ?>
		<div class="post-nav">
			<span class="prev"><?php next_posts_link(__('&laquo; Older Entries', 'themify')) ?></span>
			<span class="next"><?php previous_posts_link(__('Newer Entries &raquo;', 'themify')) ?></span>
		</div>
	<?php 
	}
} // infinite
?>