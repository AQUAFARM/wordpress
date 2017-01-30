<?php
/**
 * Template Name: Full Width
 *
 * @package WordPress
 * @subpackage Zefir
 * @since Zefir 1.0
 */
get_header();

// comments
$page_comments = get_post_meta($post->ID, 'bird_mb_page_comments', true);
if (!$page_comments) $page_comments = 'disable';
?>

<!-- full width page -->
<div id="page-wrap">
  <div class="container">
    <div class="row">

      <!-- page container -->
      <div class="col-md-12" role="main">
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
          </div><!-- .page-container -->

          <?php if ($page_comments == 'enable') { ?>
            <!-- start comments -->
            <div class="page-comments-wrap">
              <?php comments_template('', true); ?>
            </div>
            <!-- end comments -->
          <?php } ?>

        <?php endif; ?>
      </div>
      <!-- end page container -->

    </div>
  </div>
</div>
<!-- end full width page -->

<?php get_footer();
