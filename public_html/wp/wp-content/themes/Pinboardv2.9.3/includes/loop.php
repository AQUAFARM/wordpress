<?php if(!is_single()) { global $more; $more = 0; } //enable more link ?>
<?php
/** Themify Default Variables
 *  @var object */
global $themify; ?>

<article id="post-<?php the_id(); ?>" <?php post_class( 'post clearfix' ); ?>>
	<div class="post-inner">

<?php if($themify->hide_image != "yes"): ?>

	<?php
		//check if there is a video url in the custom field
		if( themify_has_post_video() ){
			echo themify_post_video();
		} else{ ?>
		<?php
		//otherwise display the featured image
		if( $post_image = themify_get_image($themify->auto_featured_image . $themify->image_setting . "w=".$themify->width."&h=".$themify->height) ){ ?>
			<?php themify_before_post_image(); // Hook ?>
			<figure class="post-image <?php echo $themify->image_align; ?>">
				<?php if( 'yes' == $themify->unlink_image): ?>
					<?php echo $post_image; ?>
				<?php else: ?>
					<a href="<?php echo themify_get_featured_image_link() ?>" ><?php echo $post_image; ?><?php themify_zoom_icon(); ?></a>
				<?php endif; ?>
			</figure>
			<?php themify_after_post_image(); // Hook ?>
		<?php } ?>

	<?php }// end if video/image ?>

<?php endif; //post image ?>

<?php themify_post_before(); //hook ?>
<div class="post-content">
	<?php themify_post_start(); //hook ?>

	<?php if($themify->hide_meta != 'yes'): ?>
		<p class="post-meta entry-meta">
			<span class="post-category">
				<?php
					if( themify_get('termscat') != '' ){
						echo themify_get('termscat');
					} else {
						the_category(', ');
					}
				?>
			</span>
			<?php the_tags(' <span class="post-tag">', ', ', ' </span>'); ?>
		</p>

	<?php endif; //post meta ?>

	<?php if($themify->hide_title != "yes"): ?>
		<?php themify_before_post_title(); // Hook ?>
		<?php if($themify->unlink_title == "yes"): ?>
			<h1 class="post-title entry-title"><?php the_title(); ?></h1>
		<?php else: ?>
			<h1 class="post-title entry-title"><a href="<?php echo themify_get_featured_image_link(); ?>" <?php if( isset($_GET['post_in_lightbox'] ) ) echo 'target="_top"'; ?> ><?php the_title(); ?></a></h1>
		<?php endif; //unlink post title ?>
		<?php themify_after_post_title(); // Hook ?>
	<?php endif; //post title ?>

	<?php if($themify->hide_meta != 'yes'): ?>
		<p class="author-pic">
			<?php echo get_avatar( get_the_author_meta('ID'), 40 ); ?>
		</p>

		<span class="post-author">
			<?php
				if( is_multisite() ){
					if( themify_get('authorname') != '' ){
						if( themify_get('authorlink') != '' )
							echo '<a href="' , themify_get('authorlink') , '">' , themify_get('authorname') , '</a>';
						else
							echo themify_get('authorname');
					} else {

						echo '<a href="' . site_url() . '">' . get_the_author_meta('display_name', $post->post_author) . '</a>';
					}
				} else {
					echo themify_get_author_link();
				}
			?> <em>&sdot;</em>

		</span>
	<?php endif; //post author ?>

	<?php if($themify->hide_date != "yes"): ?>
		<time datetime="<?php the_time('o-m-d') ?>" class="post-date entry-date updated"><?php echo get_the_date( apply_filters( 'themify_loop_date', '' ) ) ?></time>
	<?php endif; //post date ?>

	<div class="entry-content">

	<?php if ( 'excerpt' == $themify->display_content && ! is_attachment() ) : ?>

		<?php the_excerpt(); ?>

		<?php if( themify_check('setting-excerpt_more') ) : ?>
			<p class="more-link"><a title="<?php the_title_attribute('echo=0'); ?>" href="<?php echo get_permalink(); ?>"><?php echo themify_check('setting-default_more_text')? themify_get('setting-default_more_text') : __('More &rarr;', 'themify') ?></a></p>
		<?php endif; ?>

	<?php elseif ( 'none' == $themify->display_content && ! is_attachment() ) : ?>

	<?php else: ?>

		<?php the_content(themify_check('setting-default_more_text')? themify_get('setting-default_more_text') : __('More &rarr;', 'themify')); ?>

	<?php endif; //display content ?>

	</div><!-- /.entry-content -->

	<?php
		if( (is_singular() && '' != $themify->query_category && !themify_get('setting-hidelike_index')) ||
			(is_singular() && '' == $themify->query_category && !themify_get('setting-hidelike_single')) ||
			(!is_singular() && !themify_get('setting-hidelike_index'))){
			get_template_part('includes/post-like');
		}
	?>

	<?php  if( !themify_get('setting-comments_posts') && comments_open() ) : ?>
		<span class="post-comment">
			<?php comments_popup_link( __( 'No comments', 'themify' ), __( '1 comment', 'themify' ), __( '% comments', 'themify' ) ); ?>
		</span>
	<?php endif; //post comment ?>

	<?php
		if( (is_singular() && '' != $themify->query_category && !themify_get('setting-hidesocial_index')) ||
			(is_singular() && '' == $themify->query_category && !themify_get('setting-hidesocial_single')) ||
			(!is_singular() && !themify_get('setting-hidesocial_index'))){
		 	get_template_part('includes/social-share');
		}
	?>

	<?php
	if( ! is_single() && 'on' != themify_get( 'setting-nohomecomments' ) ) {
		global $withcomments;
		$withcomments = true; // enable comments in index
		comments_template('/includes/home-comments.php');
	}
	?>

	<?php edit_post_link(__('Edit', 'themify'), '<span class="edit-button">[', ']</span>'); ?>

    <?php themify_post_end(); //hook ?>
</div>
<!-- /.post-content -->
<?php themify_post_after(); //hook ?>


	<?php
	///////////////////////////////////////////////////
	//  Elements visible only in post single view
	///////////////////////////////////////////////////
	if ( is_single() && ( !isset( $themify->is_builder_loop ) ) ) : ?>
		<?php wp_link_pages( array( 'before' => '<p class="post-pagination"><strong>'.__( 'Pages:', 'themify' ).'</strong> ', 'after' => '</p>', 'next_or_number' => 'number' ) ); ?>

		<?php if ( ! isset( $_GET['post_in_lightbox'] ) ) : ?>

			<?php get_template_part( 'includes/author-box', 'single' ); ?>

			<?php get_template_part( 'includes/post-nav' ); ?>

		<?php endif; // end if not post in lightbox ?>

		<?php if ( ! themify_check( 'setting-comments_posts' ) ) : ?>
			<?php comments_template(); ?>
		<?php endif; ?>
	<?php endif; // end if single ?>

	</div>
	<!-- /.post-inner -->
</article>
<!-- /.post -->
