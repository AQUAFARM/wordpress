<?php
/**
 * The template for Gallery Post Format
 *
 * Learn more: http://codex.wordpress.org/Post_Formats
 *
 * @package WordPress
 * @subpackage Zefir
 * @since Zefir 1.0
 */
global $bird_options;

// layout
$homepage_type = (isset($bird_options['homepage_type'])) ? stripslashes($bird_options['homepage_type']) : '4_col';
$category_page_type = (isset($bird_options['category_page_type'])) ? stripslashes($bird_options['category_page_type']) : '4_col';
$tag_page_type = (isset($bird_options['tag_page_type'])) ? stripslashes($bird_options['tag_page_type']) : '4_col';
$archive_page_type = (isset($bird_options['archive_page_type'])) ? stripslashes($bird_options['archive_page_type']) : '4_col';
// options
$custom_blog_thumb_type = (isset($bird_options['blog_thumb_type'])) ? stripslashes($bird_options['blog_thumb_type']) : 'Cropped';

// get page type
if (is_category()) {
  $page_type = $category_page_type;
} else if (is_tag()) {
  $page_type = $tag_page_type;
} else if (is_archive() || is_search()) {
  $page_type = $archive_page_type;
} else {
  $page_type = $homepage_type;
}

// featured image type
if ($custom_blog_thumb_type == 'Cropped') {
  if ($page_type == '1_col') {
    $func_thumb_type = 'blog-thumb-big-crop'; // 1200x400
  } else if ($page_type == '1_col_right_sidebar' || $page_type == '1_col_left_sidebar' || $page_type == '1_col_left_right_sidebar') {
    $func_thumb_type = 'single-post-thumb-crop'; // 1200x800
  } else {
    $func_thumb_type = 'blog-thumb-crop'; // 770x513
  }
} else {
  if ($page_type == '1_col') {
    $func_thumb_type = 'single-post-thumb-crop'; // // 1200x800
  } else if ($page_type == '1_col_right_sidebar' || $page_type == '1_col_left_sidebar' || $page_type == '1_col_left_right_sidebar') {
    $func_thumb_type = 'single-post-thumb'; // 1200xauto
  } else {
    $func_thumb_type = 'blog-thumb'; // 770xauto
  }
}

$sticky_post_class = (is_sticky()) ? ' bwp-sticky-post' : '';

switch ($page_type) {
  case '4_col':
    $post_article_class = 'masonry-box post-col-4 is-article'.$sticky_post_class;
    $post_wrap_class = 'post-wrap';
    $thumb_type = $func_thumb_type;
    break;
  case '3_col':
  case '3_col_right_sidebar':
  case '3_col_left_sidebar':
    $post_article_class = 'masonry-box post-col-3 is-article'.$sticky_post_class;
    $post_wrap_class = 'post-wrap';
    $thumb_type = $func_thumb_type;
    break;
  case '2_col':
  case '2_col_right_sidebar':
  case '2_col_left_sidebar':
    $post_article_class = 'masonry-box post-col-2 is-article'.$sticky_post_class;
    $post_wrap_class = 'post-wrap';
    $thumb_type = $func_thumb_type;
    break;
  case '1_col':
    $post_article_class = 'post-full-width is-article'.$sticky_post_class;
    $post_wrap_class = 'post-wrap-full';
    $thumb_type = $func_thumb_type;
    break;
  case '1_col_right_sidebar':
  case '1_col_left_sidebar':
  case '1_col_left_right_sidebar':
    $post_article_class = 'post-full-width is-article'.$sticky_post_class;
    $post_wrap_class = 'post-wrap-full';
    $thumb_type = $func_thumb_type;
    break;
}
?>

<!-- blog post -->
<?php
$border_top_color = get_post_meta($post->ID, 'bird_mb_blog_post_border_color', true);
if (!$border_top_color) $border_top_color = '#bfdeea';
?>
<article id="post-<?php the_ID(); ?>" <?php post_class($post_article_class); ?> itemscope itemtype="http://schema.org/BlogPosting">
  <div class="<?php echo esc_attr($post_wrap_class); ?>" style="border-top: 4px solid <?php echo esc_attr($border_top_color); ?>">

    <!-- sticky bookmark -->
    <div class="sticky-bookmark" style="border-right-color: <?php echo esc_attr($border_top_color); ?>; border-top-color: <?php echo esc_attr($border_top_color); ?>; border-left-color: <?php echo esc_attr($border_top_color); ?>;"></div>
    <!-- end sticky bookmark -->

    <?php
    // Author / category
    if (function_exists('bwp_zefir_post_details_top')) {
      bwp_zefir_post_details_top();
    }
    ?>

    <!-- post title box -->
    <div class="post-title-wrap">
      <h1 class="h3 post-title entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
    </div>
    <!-- end post title box -->

    <!-- post thumb -->
    <?php
    if (has_post_thumbnail()) {
      // image
      $contentClass = '';
      ?>

      <figure class="post-thumb-wrap">
        <div class="post-thumb">
          <a href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail($thumb_type); ?>
            <div class="post-thumb-mask-bg"></div>
            <i class="fa fa-share-square-o post-thumb-mask-icon link-icon"></i>
          </a>
        </div>
      </figure>

    <?php } else {
      // gallery
      $gallery_meta = get_post_meta(get_the_ID(), 'bird_mb_gallery', false);
      if (!is_array($gallery_meta)) $gallery_meta = (array)$gallery_meta;

      if (!empty($gallery_meta)) {
        $contentClass = '';
        $img_num = count($gallery_meta);

        if ($img_num > 1) {
          ?>

          <div class="post-media-carousel">
            <div id="owl-carousel-<?php the_ID(); ?>" class="owl-carousel blog-post-carousel">
              <?php
              $i = 1;
              foreach ($gallery_meta as $thumb_id) {
                $thumb_url = wp_get_attachment_image_src($thumb_id, $thumb_type);
                ?>
                <!-- slide <?php echo intval($i); ?> -->
                <figure class="post-carousel-item">
                  <a href="<?php the_permalink(); ?>">
                    <img src="<?php echo esc_url($thumb_url[0]); ?>" alt="<?php the_title_attribute(); ?> Slide <?php echo intval($i); ?>">
                    <div class="post-thumb-mask-bg"></div>
                    <i class="fa fa-share-square-o post-thumb-mask-icon link-icon"></i>
                  </a>
                </figure>
                <!-- end slide <?php echo intval($i); ?> -->
                <?php
                $i++;
              }
              ?>
            </div>
          </div>

        <?php
        } else {
          $thumb_url = wp_get_attachment_image_src($gallery_meta[0], $thumb_type);
          ?>

          <figure class="post-thumb-wrap">
            <div class="post-thumb">
              <a href="<?php the_permalink(); ?>">
                <img src="<?php echo esc_url($thumb_url[0]); ?>" alt="<?php the_title_attribute(); ?>">
                <div class="post-thumb-mask-bg"></div>
                <i class="fa fa-share-square-o post-thumb-mask-icon link-icon"></i>
              </a>
            </div>
          </figure>

          <?php
        }
      } else { $contentClass = 'padding-top-none'; }
    }
    ?>
    <!-- end post thumb -->

    <!-- post content -->
    <div class="post-content <?php echo esc_attr($contentClass); ?>" itemprop="articleBody">
      <?php
      if (function_exists('bwp_zefir_post_excerpt')) {
        bwp_zefir_post_excerpt();
      }
      ?>
    </div>
    <!-- end post content -->

    <?php
    // post counters
    if (function_exists('bwp_zefir_post_counters')) {
      bwp_zefir_post_counters();
    }
    ?>

  </div>
</article>
<!-- end blog post -->
