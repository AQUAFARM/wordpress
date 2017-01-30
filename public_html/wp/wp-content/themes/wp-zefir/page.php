<?php
/**
 * The template for all pages
 *
 * @package WordPress
 * @subpackage Zefir
 * @since Zefir 1.0
 */
get_header();

// page sidebar position - left or right
$sidebar_position = get_post_meta($post->ID, 'bird_mb_page_sidebar_position', true);
if ($sidebar_position == 'right' || $sidebar_position == '') {
  $page_container_class = '';
  $sidebar_container_class = '';
} else {
  $page_container_class = 'col-md-push-4';
  $sidebar_container_class = 'col-md-pull-8';
}

// comments
$page_comments = get_post_meta($post->ID, 'bird_mb_page_comments', true);
if (!$page_comments) $page_comments = 'disable';
?>

<!-- page -->
<div id="page-wrap">
  <div class="container">
    <div class="row">

      <!-- page container -->
      <div class="col-md-8 <?php echo esc_attr($page_container_class); ?>" role="main">
        <?php if (have_posts()) : the_post(); ?>

          <div class="page-container">
            <!-- page title -->
            <div class="static-page-title-wrap">
              <h1 class="static-page-title h2"><?php the_title(); ?></h1>
            </div>
            <!-- end page title -->

            <!-- start content -->
            <div class="content clearfix">
              <?php the_content(); ?>
            </div>
            <!-- end content -->

            <!-- edit page link -->
            <?php edit_post_link(__( 'Edit page', 'birdwp-theme'), '<div class="edit-link-wrap"><span class="edit-link"><i class="fa fa-pencil-square-o"></i>', '</span></div>'); ?>
            <!-- end edit page link -->
          </div>

          <?php if ($page_comments == 'enable') { ?>
            <!-- comments -->
            <div class="page-comments-wrap">
              <?php comments_template('', true); ?>
            </div>
            <!-- end comments -->
          <?php } ?>

        <?php endif; ?>
      </div>
      <!-- end page container -->

      <!-- sidebar -->
      <div class="col-md-4 <?php echo esc_attr($sidebar_container_class); ?>">
        <div class="sidebar-wrap">
          <?php if (is_active_sidebar('bird_page_sidebar')): ?>
            <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('bird_page_sidebar')) : ?>
            <?php endif; ?>
          <?php endif; ?>
        </div>
      </div>
      <!-- end sidebar -->

    </div>
  </div>
</div>
<!-- end page -->

<?php get_footer();
