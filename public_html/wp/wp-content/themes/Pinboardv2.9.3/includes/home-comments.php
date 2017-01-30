<?php 
/**
 * Home Comments Template
 * Displays comments in home page only.
 * @since 1.0.0
 * @author Elio Rivero
 */

//Don't execute if we're in a single view 
if( is_single() || 'on' == themify_get('setting-nohomecomments') ) return;
?>

<?php if ( post_password_required() ) : ?>
	<p class="nopassword">
		<?php _e( 'This post is password protected. Enter the password to view any comments.', 'themify' ); ?>
	</p>
<?php
		return;
	endif;
?>

<?php if ( have_comments() || comments_open() ) :
		$comments_number = get_comments_number();
		$comments_limit = apply_filters('themify_comments_limit', 5);
	?>
		
	<?php if ( have_comments() && 0 != $comments_limit ) : ?>
				
		<ol class="commentlist">
			<?php
				wp_list_comments( array(
					'callback' => 'themify_home_comments',
					'page' => 1,
					'per_page' => $comments_limit
					)
				);
			?>
		</ol>
	
	<?php endif; // end have_comments() ?>
	
	<?php
	if( $comments_number > $comments_limit && 0 != $comments_limit ): ?>
		<p class="more-comments">
			<a href="<?php comments_link(); ?>"><?php _e('More comments', 'themify'); ?></a>
		</p>
	<?php endif; ?>

<?php endif; // end commentwrap ?>