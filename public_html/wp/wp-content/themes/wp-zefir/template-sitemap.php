<?php
/**
 * Template Name: Sitemap
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

<!-- sitemap page -->
<div id="page-wrap">
  <div class="container">
    <div class="row">

      <!-- page container -->
      <div class="col-md-8 <?php echo esc_attr($page_container_class); ?>" role="main">
        <div class="page-container">

          <!-- page content -->
          <?php if (have_posts()) : the_post(); ?>
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
          <?php endif; rewind_posts(); ?>
          <!-- end page content -->

          <!-- sitemap -->
          <div class="sitemap-content clearfix">
            <!-- start accordion -->
            <div class="panel-group" id="archive-accordion">

              <!-- authors list -->
              <div class="panel archive-section">
                <div class="archive-section-heading">
                  <h4 class="archive-section-title">
                    <a data-toggle="collapse" data-parent="#archive-accordion" href="#archive-section-1">
                      <?php esc_html_e('Authors', 'birdwp-theme'); ?>
                      <span><i class="fa fa-bars"></i></span>
                    </a>
                  </h4>
                </div>
                <div id="archive-section-1" class="panel-collapse collapse">
                  <div class="archive-section-body">
                    <ul class="sitemap-list list-unstyled">
                      <?php
                      wp_list_authors(
                        array(
                          'exclude_admin' => false,
                        )
                      );
                      ?>
                    </ul>
                  </div>
                </div>
              </div>
              <!-- end authors list -->

              <!-- categories list -->
              <div class="panel archive-section">
                <div class="archive-section-heading">
                  <h4 class="archive-section-title">
                    <a data-toggle="collapse" data-parent="#archive-accordion" href="#archive-section-2">
                      <?php esc_html_e('Categories', 'birdwp-theme'); ?>
                      <span><i class="fa fa-bars"></i></span>
                    </a>
                  </h4>
                </div>
                <div id="archive-section-2" class="panel-collapse collapse">
                  <div class="archive-section-body">
                    <ul class="sitemap-list list-unstyled">
                      <?php wp_list_categories( 'title_li=' ); ?>
                    </ul>
                  </div>
                </div>
              </div>
              <!-- end categories list -->

              <!-- all posts -->
              <div class="panel archive-section">
                <div class="archive-section-heading">
                  <h4 class="archive-section-title">
                    <a data-toggle="collapse" data-parent="#archive-accordion" href="#archive-section-3">
                      <?php esc_html_e('Posts', 'birdwp-theme'); ?>
                      <span><i class="fa fa-bars"></i></span>
                    </a>
                  </h4>
                </div>
                <div id="archive-section-3" class="panel-collapse collapse">
                  <div class="archive-section-body">
                    <ul class="sitemap-list list-unstyled">
                      <?php $archive = get_posts('numberposts=-1');
                      foreach($archive as $post) { ?>
                        <li><a href="<?php the_permalink(); ?>"><?php the_title();?></a></li>
                      <?php } ?>
                    </ul>
                  </div>
                </div>
              </div>
              <!-- end all posts -->

              <!-- pages -->
              <div class="panel archive-section">
                <div class="archive-section-heading">
                  <h4 class="archive-section-title">
                    <a data-toggle="collapse" data-parent="#archive-accordion" href="#archive-section-4">
                      <?php esc_html_e('Pages', 'birdwp-theme'); ?>
                      <span><i class="fa fa-bars"></i></span>
                    </a>
                  </h4>
                </div>
                <div id="archive-section-4" class="panel-collapse collapse">
                  <div class="archive-section-body">
                    <ul class="sitemap-list list-unstyled">
                      <?php
                      wp_list_pages(
                        array(
                          'exclude' => '',
                          'title_li' => '',
                        )
                      );
                      ?>
                    </ul>
                  </div>
                </div>
              </div>
              <!-- end pages -->

            </div>
            <!-- end accordion -->
          </div>
          <!-- end sitemap -->
        </div>

        <?php if ($page_comments == 'enable') { ?>
          <!-- start comments -->
          <div class="page-comments-wrap">
            <?php
            if (have_posts()) : the_post();
              comments_template('', true);
            endif;
            ?>
          </div>
          <!-- end comments -->
        <?php } ?>

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
<!-- end sitemap page -->

<?php get_footer();
