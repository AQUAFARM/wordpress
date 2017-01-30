<?php
/**
 * The Template for single posts page.
 *
 * @package WordPress
 * @subpackage Zefir
 * @since Zefir 1.0
 */

get_header();
global $bird_options;

// single post page type
$single_page_type = get_post_meta($post->ID, 'bird_mb_single_page_type', true);

// featured image type
$single_thumb_type = (isset($bird_options['single_thumb_type'])) ? stripslashes($bird_options['single_thumb_type']) : 'Cropped';
if ($single_thumb_type == 'Cropped') {
  $thumb_type = 'single-post-thumb-crop'; // 1200x800
} else {
  $thumb_type = 'single-post-thumb'; // 1200xauto
}

// show sidebar bg color
$show_sidebar_bg_color = (isset($bird_options['show_sidebar_bg_color'])) ? $bird_options['show_sidebar_bg_color'] : 0;

// single meta
$show_single_author = (isset($bird_options['show_single_author'])) ? $bird_options['show_single_author'] : 1;
$show_single_date = (isset($bird_options['show_single_date'])) ? $bird_options['show_single_date'] : 1;
$show_single_category = (isset($bird_options['show_single_category'])) ? $bird_options['show_single_category'] : 1;
$show_single_tags = (isset($bird_options['show_single_tags'])) ? $bird_options['show_single_tags'] : 1;
$show_single_views = (isset($bird_options['show_single_views'])) ? $bird_options['show_single_views'] : 1;
$show_single_likes = (isset($bird_options['show_single_likes'])) ? $bird_options['show_single_likes'] : 1;
$show_single_social_share = (isset($bird_options['show_single_social_share'])) ? $bird_options['show_single_social_share'] : 1;
$show_single_postnav = (isset($bird_options['show_single_postnav'])) ? $bird_options['show_single_postnav'] : 1;
?>

<!-- single post page -->
<div id="single-wrap">
  <div class="container">
    <div class="row">

      <!-- single container -->
      <?php if ($single_page_type == 'right_sidebar' || $single_page_type == '') { $single_container_class = ''; ?>
      <div class="col-md-8" role="main">
      <?php } else if ($single_page_type == 'left_sidebar') { $single_container_class = ''; ?>
      <div class="col-md-8 col-md-push-4" role="main">
      <?php } else if ($single_page_type == 'fullwidth_1') { $single_container_class = 'single-fullwidth'; ?>
      <div class="col-md-12" role="main">
      <?php } else if ($single_page_type == 'fullwidth_2') { $single_container_class = 'single-fullwidth-2'; ?>
      <div class="col-md-12" role="main">
      <?php } ?>

      <?php if (have_posts()) : the_post();
        setPostViews(get_the_ID());
        $format = get_post_format();
        if (false === $format) $format = 'standard';
        ?>

        <?php
        $border_top_color = get_post_meta($post->ID, 'bird_mb_blog_post_border_color', true);
        if (!$border_top_color) $border_top_color = '#bfdeea';
        ?>
        <div class="single-container hentry <?php echo esc_attr($single_container_class); ?>" style="border-top: 4px solid <?php echo esc_attr($border_top_color); ?>;">

          <?php if ($single_page_type == 'fullwidth_2') { ?>
          <!-- start columns -->
          <div class="row">
            <!-- media container -->
            <div class="col-md-6">
          <?php } ?>

          <!-- post title -->
          <div class="single-title-wrap">
            <h1 class="single-title h2 entry-title"><?php the_title(); ?></h1>
          </div>
          <!-- end post title -->

          <?php
          // standart or image post format with featured image
          if ($format == 'image' || $format == 'standard') {

            if (has_post_thumbnail()) {
              $thumb_url = wp_get_attachment_image_src(get_post_thumbnail_id(), $thumb_type);
              ?>

              <!-- media -->
              <figure class="single-thumb">
                <a href="<?php echo esc_url($thumb_url[0]); ?>" rel="prettyPhoto">
                  <?php the_post_thumbnail($thumb_type); ?>
                  <div class="post-thumb-mask-bg"></div>
                  <i class="fa fa-arrows-alt single-thumb-mask-icon arrows-icon"></i>
                </a>
              </figure>
              <!-- end media -->

              <?php
            }
          } // video post format (iframe player)
          else if ($format == 'video') {

            $video_type = get_post_meta($post->ID, 'bird_mb_video_type', true); // YouTube or Vimeo
            $video_id = get_post_meta($post->ID, 'bird_mb_video_id', true); // video Id

            if ($video_id != '' && $video_type != '') {
              if ($video_type == 'youtube') { ?>

                <!-- video -->
                <figure class="single-thumb">
                  <div class="iframe-video-wrap">
                    <iframe src="https://www.youtube.com/embed/<?php echo esc_attr($video_id); ?>"></iframe>
                  </div>
                </figure>
                <!-- end video -->

              <?php } else if ($video_type == 'vimeo') { ?>

                <!-- video -->
                <figure class="single-thumb">
                  <div class="iframe-video-wrap">
                    <iframe src="http://player.vimeo.com/video/<?php echo esc_attr($video_id); ?>"></iframe>
                  </div>
                </figure>
                <!-- end video -->

                <?php
              }
            }

          } // gallery post format
          else if ($format == 'gallery') {

            if (has_post_thumbnail()) {
              $thumb_url = wp_get_attachment_image_src(get_post_thumbnail_id(), $thumb_type);
              ?>

              <!-- media -->
              <figure class="single-thumb">
                <a href="<?php echo esc_url($thumb_url[0]); ?>" rel="prettyPhoto">
                  <?php the_post_thumbnail($thumb_type); ?>
                  <div class="post-thumb-mask-bg"></div>
                  <i class="fa fa-arrows-alt single-thumb-mask-icon arrows-icon"></i>
                </a>
              </figure>
              <!-- end media -->

            <?php
            } else { // gallery
              $gallery_meta = get_post_meta(get_the_ID(), 'bird_mb_gallery', false);
              if (!is_array($gallery_meta)) $gallery_meta = (array)$gallery_meta;

              if (!empty($gallery_meta)) {
                $img_num = count($gallery_meta);

                if ($img_num > 1) { ?>

                  <!-- media -->
                  <div class="post-media-carousel">
                    <div id="owl-carousel-<?php the_ID(); ?>" class="owl-carousel blog-post-carousel">
                      <?php
                      $i = 1;
                      foreach ($gallery_meta as $thumb_id) {
                        $thumb_url = wp_get_attachment_image_src($thumb_id, $thumb_type);
                        ?>
                        <!-- slide <?php echo intval($i); ?> -->
                        <figure class="post-carousel-item">
                          <a href="<?php echo esc_url($thumb_url[0]); ?>" rel="prettyPhoto[pp_gal]">
                            <img src="<?php echo esc_url($thumb_url[0]); ?>" alt="<?php the_title_attribute(); ?> Slide <?php echo intval($i); ?>">
                            <div class="post-thumb-mask-bg"></div>
                            <i class="fa fa-arrows-alt single-thumb-mask-icon arrows-icon"></i>
                          </a>
                        </figure>
                        <!-- end slide <?php echo intval($i); ?> -->
                        <?php
                        $i++;
                      }
                      ?>
                    </div>
                  </div>
                  <!-- end media -->

                <?php
                } else {
                  $thumb_url = wp_get_attachment_image_src($gallery_meta[0], $thumb_type);
                  ?>

                  <!-- media -->
                  <figure class="single-thumb">
                    <a href="<?php echo esc_url($thumb_url[0]); ?>" rel="prettyPhoto">
                      <img src="<?php echo esc_url($thumb_url[0]); ?>" alt="<?php the_title(); ?>" class="img-responsive">
                      <div class="post-thumb-mask-bg"></div>
                      <i class="fa fa-arrows-alt single-thumb-mask-icon arrows-icon"></i>
                    </a>
                  </figure>
                  <!-- end media -->

                <?php
                }
              }
            }

          }
          ?>

          <?php if ($single_page_type == 'fullwidth_2') { ?>
              <?php if ($show_single_author || $show_single_date || $show_single_category || $show_single_tags || $show_single_views || $show_single_likes || $show_single_social_share) { ?>
              <!-- start single meta -->
              <div class="single-meta-wrap">
                <ul class="list-unstyled single-meta-inf meta clearfix">
                  <?php if ($show_single_author) { ?>
                    <li><i class="fa fa-user"></i><span class="vcard author post-author"><span class="fn"><?php echo the_author_posts_link(); ?></span></span></li>
                  <?php } ?>
                  <?php if ($show_single_date) { ?>
                    <li class="post-date date updated"><i class="fa fa-calendar-o"></i><?php the_time(get_option('date_format')); ?></li>
                  <?php } ?>
                  <?php if ($show_single_category) { ?>
                    <li><i class="fa fa-bookmark-o"></i><?php the_category(', '); ?></li>
                  <?php } ?>
                  <?php if (has_term('', 'post_tag')) { ?>
                    <?php if ($show_single_tags) { ?>
                      <li><i class="fa fa-tags"></i><?php the_tags('', ', ', ''); ?></li>
                    <?php } ?>
                  <?php } ?>
                  <?php if ($show_single_views) { ?>
                    <li><i class="fa fa-eye"></i><?php echo getPostViews(get_the_ID()); ?></li>
                  <?php } ?>
                  <?php if ($show_single_likes) { ?>
                    <li><?php echo getPostLikeLink(get_the_ID()); ?></li>
                  <?php } ?>
                  <?php if ($show_single_social_share) { ?>
                    <li class="single-share-icon">
                      <a rel="nofollow" href="#" data-share_id="<?php the_ID(); ?>"><?php esc_html_e('Share', 'birdwp-theme'); ?><i class="fa fa-share-alt"></i></a>
                      <span id="share-block-<?php the_ID(); ?>" class="share-block-wrap single-share-block-wrap share-block-hidden">
                        <ul class="list-unstyled clearfix">
                          <li class="share-facebook">
                            <a rel="nofollow" href="#" onclick="window.open('http://www.facebook.com/sharer.php?u=<?php urlencode(the_permalink()); ?>', '_blank', 'scrollbars=0, resizable=1, menubar=0, left=100, top=100, width=550, height=440, toolbar=0, status=0'); return false" target="_blank"><i class="fa fa-facebook"></i></a>
                          </li>
                          <li class="share-twitter">
                            <a rel="nofollow" href="#" onclick="window.open('https://twitter.com/intent/tweet?text=<?php the_title(); ?>&url=<?php urlencode(the_permalink()); ?>', '_blank', 'scrollbars=0, resizable=1, menubar=0, left=100, top=100, width=550, height=440, toolbar=0, status=0'); return false" target="_blank"><i class="fa fa-twitter"></i></a>
                          </li>
                          <li class="share-google-plus">
                            <a rel="nofollow" href="#" onclick="window.open('https://plus.google.com/share?url=<?php urlencode(the_permalink()); ?>', '_blank', 'scrollbars=0, resizable=1, menubar=0, left=100, top=100, width=550, height=440, toolbar=0, status=0'); return false" target="_blank"><i class="fa fa-google-plus"></i></a>
                          </li>
                          <li class="share-linkedin">
                            <a rel="nofollow" href="#" onclick="window.open('http://www.linkedin.com/shareArticle?mini=true&url=<?php urlencode(the_permalink()); ?>&title=<?php the_title(); ?>', '_blank', 'scrollbars=0, resizable=1, menubar=0, left=100, top=100, width=600, height=400, toolbar=0, status=0'); return false" target="_blank"><i class="fa fa-linkedin"></i></a>
                          </li>
                          <li class="share-vk">
                            <a rel="nofollow" href="#" onclick="window.open('http://vkontakte.ru/share.php?url=<?php urlencode(the_permalink()); ?>', '_blank', 'scrollbars=0, resizable=1, menubar=0, left=100, top=100, width=600, height=300, toolbar=0, status=0'); return false" target="_blank"><i class="fa fa-vk"></i></a>
                          </li>
                        </ul>
                      </span>
                    </li>
                  <?php } ?>
                </ul>
              </div>
              <!-- end single meta -->
              <?php } ?>

            </div>
            <!-- end media container -->

            <!-- single page content -->
            <div class="col-md-6">
          <?php } ?>

          <!-- start content -->
          <div class="content clearfix">
            <?php
            the_content();
            wp_link_pages(array(
              'before' => '<div class="single-pagination-wrap">',
              'after' => '</div>',
              'link_before' => '<span>',
              'link_after'  => '</span>',
              'nextpagelink'  => '>',
              'previouspagelink' => '<'
            ));
            ?>
          </div>
          <!-- end content -->

          <!-- edit post link -->
          <?php edit_post_link(__( 'Edit post', 'birdwp-theme'), '<div class="edit-link-wrap"><span class="edit-link"><i class="fa fa-pencil-square-o"></i>', '</span></div>'); ?>
          <!-- end edit post link -->

          <?php if ($single_page_type == 'fullwidth_2') { ?>
            </div>
            <!-- end single page content -->
          </div>
          <!-- end columns -->
          <?php } ?>

          <?php if ($single_page_type == '' || $single_page_type == 'fullwidth_1' || $single_page_type == 'right_sidebar' || $single_page_type == 'left_sidebar') { ?>
            <?php if ($show_single_author || $show_single_date || $show_single_category || $show_single_tags || $show_single_views || $show_single_likes || $show_single_social_share) { ?>
            <!-- start single meta -->
            <div class="single-meta-wrap">
              <ul class="list-unstyled single-meta-inf meta clearfix">
                <?php if ($show_single_author) { ?>
                  <li><i class="fa fa-user"></i><span class="vcard author post-author"><span class="fn"><?php echo the_author_posts_link(); ?></span></span></li>
                <?php } ?>
                <?php if ($show_single_date) { ?>
                  <li class="post-date date updated"><i class="fa fa-calendar-o"></i><?php the_time(get_option('date_format')); ?></li>
                <?php } ?>
                <?php if ($show_single_category) { ?>
                  <li><i class="fa fa-bookmark-o"></i><?php the_category(', '); ?></li>
                <?php } ?>
                <?php if (has_term('', 'post_tag')) { ?>
                  <?php if ($show_single_tags) { ?>
                    <li><i class="fa fa-tags"></i><?php the_tags('', ', ', ''); ?></li>
                  <?php } ?>
                <?php } ?>
                <?php if ($show_single_views) { ?>
                  <li><i class="fa fa-eye"></i><?php echo getPostViews(get_the_ID()); ?></li>
                <?php } ?>
                <?php if ($show_single_likes) { ?>
                  <li><?php echo getPostLikeLink(get_the_ID()); ?></li>
                <?php } ?>
                <?php if ($show_single_social_share) { ?>
                  <li class="single-share-icon">
                    <a rel="nofollow" href="#" data-share_id="<?php the_ID(); ?>"><?php esc_html_e('Share', 'birdwp-theme'); ?><i class="fa fa-share-alt"></i></a>
                    <span id="share-block-<?php the_ID(); ?>" class="share-block-wrap single-share-block-wrap share-block-hidden">
                      <ul class="list-unstyled clearfix">
                        <li class="share-facebook">
                          <a rel="nofollow" href="#" onclick="window.open('http://www.facebook.com/sharer.php?u=<?php urlencode(the_permalink()); ?>', '_blank', 'scrollbars=0, resizable=1, menubar=0, left=100, top=100, width=550, height=440, toolbar=0, status=0'); return false" target="_blank"><i class="fa fa-facebook"></i></a>
                        </li>
                        <li class="share-twitter">
                          <a rel="nofollow" href="#" onclick="window.open('https://twitter.com/intent/tweet?text=<?php the_title(); ?>&url=<?php urlencode(the_permalink()); ?>', '_blank', 'scrollbars=0, resizable=1, menubar=0, left=100, top=100, width=550, height=440, toolbar=0, status=0'); return false" target="_blank"><i class="fa fa-twitter"></i></a>
                        </li>
                        <li class="share-google-plus">
                          <a rel="nofollow" href="#" onclick="window.open('https://plus.google.com/share?url=<?php urlencode(the_permalink()); ?>', '_blank', 'scrollbars=0, resizable=1, menubar=0, left=100, top=100, width=550, height=440, toolbar=0, status=0'); return false" target="_blank"><i class="fa fa-google-plus"></i></a>
                        </li>
                        <li class="share-linkedin">
                          <a rel="nofollow" href="#" onclick="window.open('http://www.linkedin.com/shareArticle?mini=true&url=<?php urlencode(the_permalink()); ?>&title=<?php the_title(); ?>', '_blank', 'scrollbars=0, resizable=1, menubar=0, left=100, top=100, width=600, height=400, toolbar=0, status=0'); return false" target="_blank"><i class="fa fa-linkedin"></i></a>
                        </li>
                        <li class="share-vk">
                          <a rel="nofollow" href="#" onclick="window.open('http://vkontakte.ru/share.php?url=<?php urlencode(the_permalink()); ?>', '_blank', 'scrollbars=0, resizable=1, menubar=0, left=100, top=100, width=600, height=300, toolbar=0, status=0'); return false" target="_blank"><i class="fa fa-vk"></i></a>
                        </li>
                      </ul>
                    </span>
                  </li>
                <?php } ?>
              </ul>
            </div>
            <!-- end single meta -->
            <?php } ?>
          <?php } ?>

        </div><!-- .single-container -->

        <?php
        $show_about_author = (isset($bird_options['show_about_author'])) ? $bird_options['show_about_author'] : 1;

        if ($show_about_author) { ?>
        <!-- start about author box -->
        <div class="about-author-wrap clearfix">
          <div class="about-author-avatar">
            <?php
            $post_author_email = get_the_author_meta('user_email');
            echo get_avatar($post_author_email, '90', ''); ?>
          </div>
          <div class="about-author-desc-wrap">
            <h3 class="h5"><?php the_author_posts_link(); ?><span>(<?php the_author_posts(); esc_html_e(' Posts', 'birdwp-theme'); ?>)</span></h3>
            <div class="about-author-desc"><?php the_author_meta('description'); ?></div>
            <div class="about-author-social">
              <ul class="list-unstyled clearfix">
                <?php if (get_the_author_meta('twitter')) { ?>
                  <li><a href="<?php echo esc_url(get_the_author_meta('twitter')); ?>" class="author-social-link" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e('I on Twitter', 'birdwp-theme'); ?>"><i class="fa fa-twitter"></i></a></li>
                <?php } ?>
                <?php if (get_the_author_meta('facebook')) { ?>
                  <li><a href="<?php echo esc_url(get_the_author_meta('facebook')); ?>" class="author-social-link" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e('I on Facebook', 'birdwp-theme'); ?>"><i class="fa fa-facebook"></i></a></li>
                <?php } ?>
                <?php if (get_the_author_meta('google')) { ?>
                  <li><a href="<?php echo esc_url(get_the_author_meta('google')); ?>" class="author-social-link" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e('I on Google+', 'birdwp-theme'); ?>"><i class="fa fa-google-plus"></i></a></li>
                <?php } ?>
                <?php if (get_the_author_meta('vk')) { ?>
                  <li><a href="<?php echo esc_url(get_the_author_meta('vk')); ?>" class="author-social-link" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e('I on VK', 'birdwp-theme'); ?>"><i class="fa fa-vk"></i></a></li>
                <?php } ?>
                <?php if (get_the_author_meta('youtube')) { ?>
                  <li><a href="<?php echo esc_url(get_the_author_meta('youtube')); ?>" class="author-social-link" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e('I on YouTube', 'birdwp-theme'); ?>"><i class="fa fa-youtube"></i></a></li>
                <?php } ?>
                <?php if (get_the_author_meta('flickr')) { ?>
                  <li><a href="<?php echo esc_url(get_the_author_meta('flickr')); ?>" class="author-social-link" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e('I on Flickr', 'birdwp-theme'); ?>"><i class="fa fa-flickr"></i></a></li>
                <?php } ?>
                <?php if (get_the_author_meta('instagram')) { ?>
                  <li><a href="<?php echo esc_url(get_the_author_meta('instagram')); ?>" class="author-social-link" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e('I on Instagram', 'birdwp-theme'); ?>"><i class="fa fa-instagram"></i></a></li>
                <?php } ?>
                <?php if (get_the_author_meta('dribbble')) { ?>
                  <li><a href="<?php echo esc_url(get_the_author_meta('dribbble')); ?>" class="author-social-link" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e('I on Dribbble', 'birdwp-theme'); ?>"><i class="fa fa-dribbble"></i></a></li>
                <?php } ?>
                <?php if (get_the_author_meta('user_url')) { ?>
                  <li><a href="<?php echo esc_url(get_the_author_meta('user_url')); ?>" target="_blank" class="author-social-link" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e('Go to My Site', 'birdwp-theme'); ?>"><i class="fa fa-link"></i></a></li>
                <?php } ?>
              </ul>
            </div>
          </div>
        </div>
        <!-- end about author box -->
        <?php } ?>

        <!-- start comments -->
        <div class="post-comments-wrap">
          <?php
          if (!post_password_required() && (comments_open() || get_comments_number())) {
            comments_template('', true);
          }
          ?>
        </div>
        <!-- end comments -->

        <?php if ($single_page_type == 'right_sidebar' || $single_page_type == '' || $single_page_type == 'left_sidebar') { ?>
        <?php if ($show_single_postnav) { ?>
        <!-- start post navigation -->
        <div class="prev-next-posts-nav clearfix">
          <div class="row">
            <div class="col-md-6">
              <?php if (get_previous_post()) { ?>
              <div class="previous-post-link">
                <?php previous_post_link('%link', '<i class="fa fa-caret-left"></i> %title'); ?>
              </div>
              <?php } ?>
            </div>
            <div class="col-md-6">
              <?php if (get_next_post()) { ?>
              <div class="next-post-link">
                <?php next_post_link('%link', '%title <i class="fa fa-caret-right"></i>'); ?>
              </div>
              <?php } ?>
            </div>
          </div>
        </div>
        <!-- end post navigation -->
        <?php } ?>
        <?php } ?>

      <?php endif; ?>
      </div>
      <!-- end single container -->

      <?php if ($single_page_type == 'right_sidebar' || $single_page_type == '') { ?>
      <!-- right sidebar -->
      <div class="col-md-4">
      <?php } else if ($single_page_type == 'left_sidebar') { ?>
      <!-- left sidebar -->
      <div class="col-md-4 col-md-pull-8">
      <?php } ?>

        <?php if ($single_page_type == 'right_sidebar' || $single_page_type == '' || $single_page_type == 'left_sidebar') { ?>
        <?php
        if ($show_sidebar_bg_color) {
          $sidebar_style = 'border-top: 4px solid '.$border_top_color.';';
        } else {
          $sidebar_style = '';
        }
        ?>
        <div class="sidebar-wrap" style="<?php echo esc_attr($sidebar_style); ?>">
          <?php if (is_active_sidebar('bird_single_sidebar')): ?>
            <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('bird_single_sidebar')) : ?>
            <?php endif; ?>
          <?php endif; ?>
        </div>
        <?php } ?>

      <?php if ($single_page_type == 'right_sidebar' || $single_page_type == '' || $single_page_type == 'left_sidebar') { ?>
      </div>
      <!-- end sidebar -->
      <?php } ?>

    </div>
  </div>

  <?php if ($single_page_type == 'fullwidth_1' || $single_page_type == 'fullwidth_2') { ?>
    <?php if ($show_single_postnav) { ?>
    <!-- start post navigation -->
    <div class="prev-next-posts-nav clearfix">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <?php if (get_previous_post()) { ?>
              <div class="previous-post-link">
                <?php previous_post_link('%link', '<i class="fa fa-caret-left"></i> %title'); ?>
              </div>
            <?php } ?>
          </div>
          <div class="col-md-6">
            <?php if (get_next_post()) { ?>
              <div class="next-post-link">
                <?php next_post_link('%link', '%title <i class="fa fa-caret-right"></i>'); ?>
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
    <!-- end post navigation -->
    <?php } ?>
  <?php } ?>

</div>
<!-- end single post page -->

<?php get_footer();
