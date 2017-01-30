<?php
/**
 * Template Name: Blog
 *
 * @package WordPress
 * @subpackage Zefir
 * @since Zefir 1.0
 */

get_header();
global $bird_options;

// homepage layout
$homepage_type = (isset($bird_options['homepage_type'])) ? stripslashes($bird_options['homepage_type']) : '4_col';

switch ($homepage_type) {
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
<div id="page-title-wrap">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h1 class="page-title h2"><?php the_title(); ?></h1>
      </div>
    </div>
  </div>
</div>
<!-- end page title -->

<!-- blog -->
<div id="archive-page">
  <div class="container">
    <div class="row">

      <?php if ($homepage_type == '4_col' || $homepage_type == '3_col' || $homepage_type == '2_col' || $homepage_type == '1_col') { ?>
      <div class="col-md-12" role="main">
      <?php } else if ($homepage_type == '3_col_right_sidebar' || $homepage_type == '2_col_right_sidebar' || $homepage_type == '1_col_right_sidebar') { ?>
      <!-- blog -->
      <div class="col-md-8" role="main">
      <?php } else if ($homepage_type == '3_col_left_sidebar' || $homepage_type == '2_col_left_sidebar' || $homepage_type == '1_col_left_sidebar') { ?>
      <div class="col-md-8 col-md-push-4" role="main">
      <?php } else if ($homepage_type == '1_col_left_right_sidebar') { ?>
      <div class="col-md-6 col-md-push-3" role="main">
      <?php } ?>

        <?php
        $current_page = (get_query_var('paged')) ? get_query_var('paged') : 1;

        $params = array(
          'post_type'       => 'post',
          'paged'           => $current_page,
          'cat'             => 'all'
        );
        query_posts($params);
        ?>

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

        wp_reset_query();
        ?>
        <!-- end pagination -->

      </div><!-- col -->

      <?php if ($homepage_type == '1_col_left_right_sidebar') { ?>
      <!-- left sidebar -->
      <div class="col-md-3 col-md-pull-6">
        <div class="sidebar-wrap">
          <?php if (is_active_sidebar('bird_home_2_sidebar')): ?>
            <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('bird_home_2_sidebar')) : ?>
            <?php endif; ?>
          <?php endif; ?>
        </div>
      </div>
      <!-- end left sidebar -->
      <?php } ?>

      <?php if ($homepage_type == '3_col_right_sidebar' || $homepage_type == '2_col_right_sidebar' || $homepage_type == '1_col_right_sidebar' || $homepage_type == '3_col_left_sidebar' || $homepage_type == '2_col_left_sidebar' || $homepage_type == '1_col_left_sidebar' || $homepage_type == '1_col_left_right_sidebar') { ?>
      <!-- right sidebar -->
      <?php if ($homepage_type == '3_col_right_sidebar' || $homepage_type == '2_col_right_sidebar' || $homepage_type == '1_col_right_sidebar') { ?>
      <div class="col-md-4">
      <?php } else if ($homepage_type == '3_col_left_sidebar' || $homepage_type == '2_col_left_sidebar' || $homepage_type == '1_col_left_sidebar') { ?>
      <div class="col-md-4 col-md-pull-8">
      <?php } else if ($homepage_type == '1_col_left_right_sidebar') { ?>
      <div class="col-md-3">
      <?php } ?>

        <div class="sidebar-wrap">
          <?php if (is_active_sidebar('bird_home_sidebar')): ?>
            <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('bird_home_sidebar')) : ?>
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
<!-- end blog -->

<?php get_footer();
