<!DOCTYPE html>
<html <?php language_attributes(); ?> class="post-lightbox-html">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">

<title><?php if (is_home() || is_front_page()) { bloginfo('name'); } else { echo wp_title(''); } ?></title>

<link rel="canonical" href="<?php the_permalink(); ?>" />

<!-- wp_header -->
<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
	<div id="pagewrap">
		<div id="body">

			<div class="lightbox-post lightbox-item">
		
				<?php if( have_posts() ) : while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'includes/loop' , 'single'); ?>

				<?php endwhile; endif; ?>

				<script type="text/javascript">
					jQuery(document).ready(function($){
						$('a').on('click', function(){
							history.pushState({}, $(this).parent().text(), window.location);
						});
					});
				</script>

			</div>
			<!-- /.lightbox-post -->
		</div>
		<!-- /#body -->
	</div>
	<!-- /#pagewrap -->
<?php wp_footer(); ?>
<!-- wp_footer -->

</body>
</html>