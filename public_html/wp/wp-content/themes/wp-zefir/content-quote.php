<?php
/**
 * The template for Quote Post Format
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

if (is_category()) {
  $page_type = $category_page_type;
} else if (is_tag()) {
  $page_type = $tag_page_type;
} else if (is_archive() || is_search()) {
  $page_type = $archive_page_type;
} else {
  $page_type = $homepage_type;
}

switch ($page_type) {
  case '4_col':
    $post_article_class = 'masonry-box post-col-4 is-article';
    $post_wrap_class = 'post-wrap';
    break;
  case '3_col':
  case '3_col_right_sidebar':
  case '3_col_left_sidebar':
    $post_article_class = 'masonry-box post-col-3 is-article';
    $post_wrap_class = 'post-wrap';
    break;
  case '2_col':
  case '2_col_right_sidebar':
  case '2_col_left_sidebar':
    $post_article_class = 'masonry-box post-col-2 is-article';
    $post_wrap_class = 'post-wrap';
    break;
  case '1_col':
  case '1_col_right_sidebar':
  case '1_col_left_sidebar':
  case '1_col_left_right_sidebar':
    $post_article_class = 'post-full-width is-article';
    $post_wrap_class = 'post-wrap-full';
    break;
}
?>

<!-- blog post -->
<article id="post-<?php the_ID(); ?>" <?php post_class($post_article_class); ?> itemscope itemtype="http://schema.org/BlogPosting">
  <div class="<?php echo esc_attr($post_wrap_class); ?> quote-type">

    <?php
    $bg_color = get_post_meta($post->ID, 'bird_mb_bg_color', true);
    $font_color = get_post_meta($post->ID, 'bird_mb_font_color', true);
    if ($bg_color == '') $bg_color = '#acd3e2';
    if ($font_color == '') $font_color = '#FFFFFF';
    ?>

    <?php
    $show_blog_author = (isset($bird_options['show_blog_author'])) ? $bird_options['show_blog_author'] : 1;
    $show_blog_date = (isset($bird_options['show_blog_date'])) ? $bird_options['show_blog_date'] : 1;

    if ($show_blog_author || $show_blog_date) { ?>

    <!-- sticky bookmark -->
    <div class="sticky-bookmark" style="border-right-color: <?php echo esc_attr($bg_color); ?>; border-top-color: <?php echo esc_attr($bg_color); ?>; border-left-color: <?php echo esc_attr($bg_color); ?>;"></div>
    <!-- end sticky bookmark -->

    <!-- top meta inf -->
    <div class="top-meta-inf-wrap">
      <ul class="list-unstyled meta-inf meta clearfix">
        <?php if ($show_blog_author) { ?>
          <li><i class="fa fa-user"></i><span class="vcard author post-author"><span class="fn"><?php echo the_author_posts_link(); ?></span></span></li>
        <?php } ?>
        <?php if ($show_blog_date) { ?>
          <li class="post-date date updated"><i class="fa fa-calendar-o"></i><?php the_time(get_option('date_format')); ?></li>
        <?php } ?>
      </ul>
    </div>
    <!-- end top meta inf -->
    <?php } ?>

    <!-- quote title - hide -->
    <h1 class="entry-title" style="display: none;"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
    <!-- end quote title - hide -->

    <!-- quote content -->
    <?php
    if (!$show_blog_author && !$show_blog_date) {
      $quote_sticky_class = 'quote-sticky';
    } else {
      $quote_sticky_class = '';
    }
    ?>
    <div class="quote-content <?php echo esc_attr($quote_sticky_class); ?>" itemprop="articleBody" style="background-color: <?php echo esc_attr($bg_color); ?>; color: <?php echo esc_attr($font_color); ?>;">
      <a href="<?php the_permalink(); ?>" style="color: <?php echo esc_attr($font_color); ?>;"><i class="fa fa-quote-left"></i></a>
      <?php echo bird_quote_content(); ?>
    </div>
    <!-- end quote content -->

  </div>
</article>
<!-- end blog post -->
