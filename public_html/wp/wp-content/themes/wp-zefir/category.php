<?php
/**
 * The template for Category pages.
 *
 * @package WordPress
 * @subpackage Zefir
 * @since Zefir 1.0
 */

get_header();
global $bird_options;

// category page layout
$category_page_type = (isset($bird_options['category_page_type'])) ? stripslashes($bird_options['category_page_type']) : '4_col';

switch ($category_page_type) {
  case '4_col':
    $blog_container_class = 'blog-container blog-container-col-4 masonry infinite-container';
    break;
  case '3_col':
    $blog_container_class = 'blog-container blog-container-col-3 masonry infinite-container';
    break;
  case '2_col':
    $blog_container_class = 'blog-container blog-container-col-2 masonry infinite-container';
    break;
  case '1_col':
    $blog_container_class = 'blog-container-fullwidth infinite-container';
    break;
  case '3_col_right_sidebar':
  case '3_col_left_sidebar':
    $blog_container_class = 'blog-container blog-container-col-3 with-sidebar masonry infinite-container';
    break;
  case '2_col_right_sidebar':
  case '2_col_left_sidebar':
    $blog_container_class = 'blog-container blog-container-col-2 with-sidebar masonry infinite-container';
    break;
  case '1_col_right_sidebar':
  case '1_col_left_sidebar':
    $blog_container_class = 'blog-container-fullwidth with-sidebar infinite-container';
    break;
  case '1_col_left_right_sidebar':
    $blog_container_class = 'blog-container-fullwidth with-2-sidebar infinite-container';
    break;
}
?>

<!-- page title -->
<?php $show_category_desc = (isset($bird_options['show_category_desc'])) ? $bird_options['show_category_desc'] : 1; ?>
<div id="page-title-wrap">
  <div class="container">
    <div class="row">

      <!-- page title -->
      <?php if ($show_category_desc) { ?>
      <div class="col-md-8">
      <?php } else { ?>
      <div class="col-md-12">
      <?php } ?>
        <h1 class="page-title h2"><?php echo single_cat_title('', false); ?></h1>
      </div>
      <!-- end page title -->
      <?php if ($show_category_desc) { ?>
      <!-- category description -->
      <div class="col-md-4">
        <div class="category-description">
          <?php if (category_description()) echo category_description(); ?>
        </div>
      </div>
      <!-- end category description -->
      <?php } ?>

    </div>
  </div>
</div>
<!-- end page title -->

<!-- category -->
<div id="archive-page">
  <div class="container">
    <div class="row">

      <?php if ($category_page_type == '4_col' || $category_page_type == '3_col' || $category_page_type == '2_col' || $category_page_type == '1_col') { ?>
      <div class="col-md-12" role="main">
      <?php } else if ($category_page_type == '3_col_right_sidebar' || $category_page_type == '2_col_right_sidebar' || $category_page_type == '1_col_right_sidebar') { ?>
      <!-- blog -->
      <div class="col-md-8" role="main">
      <?php } else if ($category_page_type == '3_col_left_sidebar' || $category_page_type == '2_col_left_sidebar' || $category_page_type == '1_col_left_sidebar') { ?>
      <div class="col-md-8 col-md-push-4" role="main">
      <?php } else if ($category_page_type == '1_col_left_right_sidebar') { ?>
      <div class="col-md-6 col-md-push-3" role="main">
      <?php } ?>

        <!-- blog container -->
        <div class="<?php echo esc_attr($blog_container_class); ?>">
          <?php // start the loop
          if (have_posts()) :
            while (have_posts()) :
              the_post();
              $format = get_post_format();

              if (false === $format) {
                get_template_part('content', 'standard');
              } else {
                get_template_part('content', $format);
              }
            endwhile;
          endif;
          ?>
        </div>
        <!-- end blog container -->

        <?php
        if (!have_posts()) :
          // If no content, include the 'No post found' template
          get_template_part('content', 'none');
        endif;
        ?>

        <!-- pagination -->
        <?php
        $pagination_type = (isset($bird_options['pagination_type'])) ? stripslashes($bird_options['pagination_type']) : 'Standard';

        if ($pagination_type == 'Standard' || $pagination_type == 'Infinite scroll') {
          bird_pagination();
        } else {
          bird_pagination_nextprev();
        }
        ?>
        <!-- end pagination -->

      </div><!-- col -->

      <?php if ($category_page_type == '1_col_left_right_sidebar') { ?>
      <!-- left sidebar -->
      <div class="col-md-3 col-md-pull-6">
        <div class="sidebar-wrap">
          <?php if (is_active_sidebar('bird_category_2_sidebar')): ?>
            <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('bird_category_2_sidebar')) : ?>
            <?php endif; ?>
          <?php endif; ?>
        </div>
      </div>
      <!-- end left sidebar -->
      <?php } ?>

      <?php if ($category_page_type == '3_col_right_sidebar' || $category_page_type == '2_col_right_sidebar' || $category_page_type == '1_col_right_sidebar' || $category_page_type == '3_col_left_sidebar' || $category_page_type == '2_col_left_sidebar' || $category_page_type == '1_col_left_sidebar' || $category_page_type == '1_col_left_right_sidebar') { ?>
      <!-- right sidebar -->
      <?php if ($category_page_type == '3_col_right_sidebar' || $category_page_type == '2_col_right_sidebar' || $category_page_type == '1_col_right_sidebar') { ?>
      <div class="col-md-4">
      <?php } else if ($category_page_type == '3_col_left_sidebar' || $category_page_type == '2_col_left_sidebar' || $category_page_type == '1_col_left_sidebar') { ?>
      <div class="col-md-4 col-md-pull-8">
      <?php } else if ($category_page_type == '1_col_left_right_sidebar') { ?>
      <div class="col-md-3">
      <?php } ?>

        <div class="sidebar-wrap">
          <?php if (is_active_sidebar('bird_category_sidebar')): ?>
            <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('bird_category_sidebar')) : ?>
            <?php endif; ?>
          <?php endif; ?>
        </div>
      </div>
      <!-- end right sidebar -->
      <?php } ?>
      <!-- end col -->

    </div>
  </div>
</div>
<!-- end category -->

<?php get_footer();
