<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
 * @package WordPress
 * @subpackage Zefir
 * @since Zefir 1.0
 */

get_header();
global $bird_options;

// sidebar position
$sidebar_position = (isset($bird_options['page_404_sidebar_position'])) ? stripslashes($bird_options['page_404_sidebar_position']) : 'Right';

if ($sidebar_position == 'Right') {
  $page_container_class = '';
  $sidebar_container_class = '';
} else {
  $page_container_class = 'col-md-push-4';
  $sidebar_container_class = 'col-md-pull-8';
}
?>

<!-- page 404 -->
<div id="page-wrap">
  <div class="container">
    <div class="row">

      <!-- 404 page container -->
      <div class="col-md-8 <?php echo esc_attr($page_container_class); ?>" role="main">
        <div class="page-container page-404-container">

            <!-- page title -->
            <div class="static-page-title-wrap">
              <h1 class="static-page-title h2">W-P-L-O-C-K-E-R-.-C-O-M<?php esc_html_e('This is somewhat embarrassing, isn&rsquo;t it?', 'birdwp-theme'); ?></h1>
            </div>
            <!-- end page title -->

            <!-- start content -->
            <div class="content clearfix">
              <!-- content none message -->
              <div class="content-none-404">
                <p><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'birdwp-theme'); ?></p>
                <?php get_search_form(); ?>
              </div>
              <!-- end content none message -->

              <!-- start archive block -->
              <div class="row">
                <div class="archives-content clearfix">
                  <div class="col-md-4">
                    <!-- 20 last posts -->
                    <h3><?php esc_html_e('Last Posts', 'birdwp-theme'); ?></h3>
                    <ul class="archives-list list-unstyled">
                      <?php $archive = get_posts('numberposts=20');
                      foreach($archive as $post) { ?>
                        <li><a href="<?php the_permalink(); ?>"><?php the_title();?></a></li>
                      <?php } ?>
                    </ul>
                    <!-- end 20 last posts -->
                  </div>
                  <div class="col-md-4">
                    <!-- list of categories -->
                    <h3><?php esc_html_e('Our categories', 'birdwp-theme'); ?></h3>
                    <ul class="archives-list list-unstyled">
                      <?php wp_list_categories( 'title_li=' ); ?>
                    </ul>
                    <!-- end list of categories -->
                  </div>
                  <div class="col-md-4">
                    <!-- archives by month -->
                    <h3><?php esc_html_e('Archives by Month', 'birdwp-theme'); ?></h3>
                    <ul class="archives-list list-unstyled">
                      <?php wp_get_archives('type=monthly'); ?>
                    </ul>
                    <!-- end archives by month -->
                  </div>
                </div>
              </div>
              <!-- end archive block -->
            </div>
            <!-- end content -->

        </div>
      </div>
      <!-- end 404 page container -->

      <!-- sidebar -->
      <div class="col-md-4 <?php echo esc_attr($sidebar_container_class); ?>">
        <div class="sidebar-wrap">
          <?php if (is_active_sidebar('bird_archive_sidebar')): ?>
            <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('bird_archive_sidebar')) : ?>
            <?php endif; ?>
          <?php endif; ?>
        </div>
      </div>
      <!-- end sidebar -->

    </div>
  </div>
</div>
<!-- end page 404 -->

<?php get_footer();
