<?php
/**
 * The Index template
 *
 * @package WordPress
 * @subpackage Zefir
 * @since Zefir 1.0
 */

get_header();
global $bird_options;

// homepage layout
$homepage_type = (isset($bird_options['homepage_type'])) ? stripslashes($bird_options['homepage_type']) : '4_col';

// category page layout
$category_page_type = (isset($bird_options['category_page_type'])) ? stripslashes($bird_options['category_page_type']) : '4_col';
$homepage_category = (isset($bird_options['homepage_category'])) ? stripslashes($bird_options['homepage_category']) : '';

// get cat id
$cat_id = get_cat_ID($homepage_category);
if (!$cat_id) $cat_id = 'all';

if ($cat_id == 'all') {
  $page_type = $homepage_type;
} else {
  $page_type = $category_page_type;
}

switch ($page_type) {
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

// homepage slider (mightySlider)
$show_homepage_slider = (isset($bird_options['show_homepage_slider'])) ? $bird_options['show_homepage_slider'] : 1;
if ($show_homepage_slider) {

  $homepage_slides = (isset($bird_options['homepage_slides'])) ? $bird_options['homepage_slides'] : ''; //get the slides array
  if ($homepage_slides) {

    foreach ($homepage_slides as $slide) {
      if ($slide['url']) {
        $slider_enable = true;
        break;
      } else {
        $slider_enable = false;
        break;
      }
    }

    if ($slider_enable) {
      $slides_num = count($homepage_slides);
      $auto_slideshow = (isset($bird_options['auto_slideshow'])) ? $bird_options['auto_slideshow'] : 1;
      $show_slider_text = (isset($bird_options['show_slider_text'])) ? $bird_options['show_slider_text'] : 1;
      $learn_more_btn_text = (isset($bird_options['slide_learn_more_btn'])) ? stripslashes($bird_options['slide_learn_more_btn']) : 'Learn more';
      ?>
      <!-- main slider - mightySlider -->
      <script>
      jQuery.noConflict()(function($) {
        $(document).ready(function() {
          "use strict";

          // main slider
          (function(){
            var
              $mainSlider = $('#main-slider'),
              $frame = $('.frame', $mainSlider);

            $frame.mightySlider({
              speed: 1000,
              easing: 'easeOutExpo',
              viewport: 'fill', // fill, fit, stretch, center
              // Navigation options
              navigation: {
                slideSize: '100%',
                keyboardNavBy: 'slides'
              },
              // Dragging
              dragging: {
                swingSpeed: 0.1,
                mouseDragging: 1,
                touchDragging: 0
              },
              // Pages
              pages: {
                activateOn: 'click'
              },
              <?php if ($slides_num > 1) { ?>
              // Commands
              commands: {
                pages: 1,
                buttons: 1
              },
              <?php } ?>
              <?php if ($auto_slideshow) { ?>
              // Cycling
              cycling: {
                cycleBy: 'pages',
                pauseOnHover: 1
              }
              <?php } ?>
            });
          })();
          // end main slider

        });
      });
      </script>

      <!-- main slider -->
      <div class="container">
        <div class="main-slider-wrap">
          <div id="main-slider" class="mightyslider_modern_skin">
            <div class="frame">
              <div class="slide_element">
                <?php
                $i = 1;
                foreach ($homepage_slides as $slide) {
                  if (!$slide['margintop']) $slide['margintop'] = 60;
                  if (!$slide['aspeed']) $slide['aspeed'] = 500;
                  ?>
                  <!-- slide <?php echo intval($i); ?> -->
                  <?php
                  if (!$show_slider_text) {
                    if ($slide['link']) {
                      $slide_link = ', link: { url: ';
                      $slide_link .= "'".esc_url($slide['link'])."'}";
                    } else {
                      $slide_link = '';
                    }
                  } else {
                    $slide_link = '';
                  }
                  ?>
                  <div class="slide" data-mightyslider="cover: '<?php echo esc_url($slide['url']); ?>' <?php echo esc_attr($slide_link); ?>">
                    <?php if ($show_slider_text) { ?>
                      <?php if ($slide['title'] || $slide['description'] || $slide['link']) { ?>
                        <div class="mSCaption slide-caption-bg" data-msanimation="{ speed: 1000, style: { opacity: 1 } }"><img src="<?php echo get_template_directory_uri(); ?>/img/main-slide-bg.png"></div>
                        <div class="mSCaption main-slider-caption" data-msanimation="{ delay: 600, speed: <?php echo intval($slide['aspeed']); ?>, style: { top: <?php echo intval($slide['margintop']); ?>, opacity: 1 } }">
                          <?php if ($slide['title']) { ?>
                            <h1><?php echo esc_html($slide['title']); ?></h1>
                          <?php } ?>
                          <?php if ($slide['description']) { ?>
                            <p><?php echo esc_html($slide['description']); ?></p>
                          <?php } ?>
                          <?php if ($slide['link']) { ?>
                            <a href="<?php echo esc_url($slide['link']); ?>" class="bird-big-btn"><?php echo esc_html($learn_more_btn_text); ?></a>
                          <?php } ?>
                        </div>
                      <?php } ?>
                      <?php if ($slide['link'] || $slide['title']) { ?>
                        <!-- responsive caption -->
                        <div class="mS-rwd-caption hidden-lg hidden-md hidden-sm">
                          <?php if ($slide['title']) { ?>
                            <h1>
                              <?php if ($slide['link']) { ?><a href="<?php echo esc_url($slide['link']); ?>"><?php } ?>
                                <?php echo esc_html($slide['title']); ?>
                              <?php if ($slide['link']) { echo '</a>'; } ?>
                            </h1>
                          <?php } ?>
                          <?php if ($slide['link']) { ?>
                            <span><a href="<?php echo esc_url($slide['link']); ?>"><i class="fa fa-link"></i></a></span>
                          <?php } ?>
                        </div>
                        <!-- end responsive caption -->
                      <?php } ?>
                    <?php } ?>
                  </div>
                  <!-- end slide <?php echo intval($i); ?> -->
                <?php $i++; } ?>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- end main slider -->
    <?php } ?>
  <?php } ?>
<?php } // end homepage slider ?>

<!-- blog -->
<div id="blog">
  <div class="container">
    <div class="row">

      <?php if ($page_type == '4_col' || $page_type == '3_col' || $page_type == '2_col' || $page_type == '1_col') { ?>
      <div class="col-md-12" role="main">
      <?php } else if ($page_type == '3_col_right_sidebar' || $page_type == '2_col_right_sidebar' || $page_type == '1_col_right_sidebar') { ?>
      <!-- blog -->
      <div class="col-md-8" role="main">
      <?php } else if ($page_type == '3_col_left_sidebar' || $page_type == '2_col_left_sidebar' || $page_type == '1_col_left_sidebar') { ?>
      <div class="col-md-8 col-md-push-4" role="main">
      <?php } else if ($page_type == '1_col_left_right_sidebar') { ?>
      <div class="col-md-6 col-md-push-3" role="main">
      <?php } ?>

        <!-- blog container -->
        <div class="<?php echo esc_attr($blog_container_class); ?>">

          <?php
          global $query_string;
          $homepage_order = (isset($bird_options['homepage_post_order'])) ? stripslashes($bird_options['homepage_post_order']) : 'Date';

          // post order type
          switch ($homepage_order) {
            case 'Date';
              $orderby = 'date';
              $order = 'DESC';
              break;
            case 'Date ASC';
              $orderby = 'date';
              $order = 'ASC';
              break;
            case 'Title';
              $orderby = 'title';
              $order = 'DESC';
              break;
            case 'Title ASC';
              $orderby = 'title';
              $order = 'ASC';
              break;
            case 'Random';
              $orderby = 'rand';
              $order = 'DESC';
              break;
          }

          // change query
          query_posts($query_string . '&cat=' . $cat_id . '&orderby=' . $orderby . '&order=' . $order);
          ?>

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

      <?php if ($page_type == '1_col_left_right_sidebar') { ?>
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

      <?php if ($page_type == '3_col_right_sidebar' || $page_type == '2_col_right_sidebar' || $page_type == '1_col_right_sidebar' || $page_type == '3_col_left_sidebar' || $page_type == '2_col_left_sidebar' || $page_type == '1_col_left_sidebar' || $page_type == '1_col_left_right_sidebar') { ?>
      <!-- right sidebar -->
      <?php if ($page_type == '3_col_right_sidebar' || $page_type == '2_col_right_sidebar' || $page_type == '1_col_right_sidebar') { ?>
      <div class="col-md-4">
      <?php } else if ($page_type == '3_col_left_sidebar' || $page_type == '2_col_left_sidebar' || $page_type == '1_col_left_sidebar') { ?>
      <div class="col-md-4 col-md-pull-8">
      <?php } else if ($page_type == '1_col_left_right_sidebar') { ?>
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

<?php
get_footer();
