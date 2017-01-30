<?php

/**
 * Slightly Modified Options Framework
 * ----------------------------------------------------------------------------
 */
require_once('admin/index.php');

/**
 * TGM Plugin activation
 * ----------------------------------------------------------------------------
 */
require_once('assets/class-tgm-plugin-activation.php');
require_once('assets/zefir-plugin-activation.php');

/**
 * Content width
 * ----------------------------------------------------------------------------
 */
if (!isset($content_width)) {
  $content_width = 1100;
}

/**
 * Default theme options
 * ----------------------------------------------------------------------------
 */
if (!function_exists('bird_theme_setup')) {
  function bird_theme_setup() {

    // theme translation
    load_theme_textdomain('birdwp-theme', get_template_directory() . '/languages');

    /*
     * Let WordPress manage the document title.
     * By adding theme support, we declare that this theme does not use a
     * hard-coded <title> tag in the document head, and expect WordPress to
     * provide it for us.
     */
    add_theme_support('title-tag');

    /*
     * Switch default core markup for search form, comment form and comments
     * to output valid HTML5.
     */
    add_theme_support('html5', array(
      'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
    ));

    // add posts format in the theme (gallery, video, image, quote)
    add_theme_support('post-formats', array('image', 'gallery', 'video', 'quote'));

    // add menu in the theme
    register_nav_menus(array(
      'main_menu' => __('main navigation (header)', 'birdwp-theme')
    ));

    // thumbnails
    add_theme_support('post-thumbnails');
    set_post_thumbnail_size(150, 150); // default Post Thumbnail dimensions

    // add feed links on the theme
    add_theme_support('automatic-feed-links');

    // registers a new image sizes.
    // add_image_size('homepage-slider', 1320, 500, true); // slider image crop
    add_image_size('blog-thumb', 770, 9999); // full size
    add_image_size('blog-thumb-crop', 770, 513, true); // blog crop thumb
    add_image_size('blog-thumb-big-crop', 1200, 400, true); // blog big crop thumb
    add_image_size('single-post-thumb', 1200, 9999); // full size
    add_image_size('single-post-thumb-crop', 1200, 800, true); // single crop thumb
    add_image_size('small-thumb', 100, 100, true); // small crop thumb

  }
}
add_action('after_setup_theme', 'bird_theme_setup');

/**
 * Show title tag (if WordPress does not support 'title-tag')
 * ----------------------------------------------------------------------------
 */
if (!function_exists('_wp_render_title_tag')) {
  function bird_render_title_tag() {
    ?>
    <title><?php wp_title('|', true, 'right'); ?></title>
    <?php
  }
  add_action('wp_head', 'bird_render_title_tag');
}

/**
 * Add style for TinyMCE editor (editor-style.css + font-awesome.css)
 * ----------------------------------------------------------------------------
 */
if (!function_exists('bwp_zefir_editor_style')) {
  function bwp_zefir_editor_style() {

    // add style
    add_editor_style(array(
      'css/editor-style.css',
      'css/font-awesome.min.css'
    ));

  }
}
add_action('init', 'bwp_zefir_editor_style');

/**
 * Meta-box
 * ----------------------------------------------------------------------------
 */
require_once('assets/zefir-meta-box.php');

/**
 * Add Google fonts
 * ----------------------------------------------------------------------------
 */
if (!function_exists('bwp_zefir_google_fonts')) {
  function bwp_zefir_google_fonts() {
    global $bird_options;

    // protocol
    $protocol = is_ssl() ? 'https' : 'http';

    // font url
    $font_url = '';

    // character sets (subset)
    $character_sets_array = (isset($bird_options['bwp_google_character_sets'])) ? $bird_options['bwp_google_character_sets'] : '';

    $subset_latin = (isset($character_sets_array['latin'])) ? $character_sets_array['latin'] : '';
    $subset_latin_ext = (isset($character_sets_array['latin-ext'])) ? $character_sets_array['latin-ext'] : '';
    $subset_cyrillic = (isset($character_sets_array['cyrillic'])) ? $character_sets_array['cyrillic'] : '';
    $subset_cyrillic_ext = (isset($character_sets_array['cyrillic-ext'])) ? $character_sets_array['cyrillic-ext'] : '';
    $subset_greek = (isset($character_sets_array['greek'])) ? $character_sets_array['greek'] : '';
    $subset_greek_ext = (isset($character_sets_array['greek-ext'])) ? $character_sets_array['greek-ext'] : '';
    $subset_vietnamese = (isset($character_sets_array['vietnamese'])) ? $character_sets_array['vietnamese'] : '';

    $subset = '';

    if ($character_sets_array) {
      // font subsets
      $subsets = array();
      if ($subset_latin) { $subsets[] = 'latin'; }
      if ($subset_latin_ext) { $subsets[] = 'latin-ext'; }
      if ($subset_cyrillic) { $subsets[] = 'cyrillic'; }
      if ($subset_cyrillic_ext) { $subsets[] = 'cyrillic-ext'; }
      if ($subset_greek) { $subsets[] = 'greek'; }
      if ($subset_greek_ext) { $subsets[] = 'greek-ext'; }
      if ($subset_vietnamese) { $subsets[] = 'vietnamese'; }

      $subsets_count = count($subsets);

      if ($subsets_count == 0) {
        $subset = ''; // default subset = latin (or '')
      } else if ($subsets_count == 1) {
        if ($subsets[0] != 'latin') {
          $subset = '&subset='.$subsets[0];
        }
      } else {
        $subset = '&subset='.implode(',', $subsets);
      }
    }

    // all fonts
    $custom_font_families = array();

    $logo_font = (isset($bird_options['logo_font']['face'])) ? $bird_options['logo_font']['face'] : 'Raleway';
    $body_font = (isset($bird_options['theme_main_font']['face'])) ? $bird_options['theme_main_font']['face'] : 'Open Sans';
    $menu_font = (isset($bird_options['bwp_menu_font']['face'])) ? $bird_options['bwp_menu_font']['face'] : 'Open Sans';
    $headings_font = (isset($bird_options['theme_headings_font']['face'])) ? $bird_options['theme_headings_font']['face'] : 'Raleway';
    $quote_font = (isset($bird_options['bwp_quote_font']['face'])) ? $bird_options['bwp_quote_font']['face'] : 'Lora';

    $custom_font_families[] = $logo_font;
    $custom_font_families[] = $body_font;
    $custom_font_families[] = $menu_font;
    $custom_font_families[] = $headings_font;
    $custom_font_families[] = $quote_font;

    // remove duplicates (unique array)
    $unique_custom_font_families = array_unique($custom_font_families);

    // register + enqueue styles
    if ($unique_custom_font_families) {
      foreach ($unique_custom_font_families as $custom_font_value) {

        // font style id (font family)
        $custom_font_id = mb_strtolower($custom_font_value, 'UTF-8');
        $custom_font_id = str_replace(" ", "-", $custom_font_id);

        // url
        $font_url = $protocol.'://fonts.googleapis.com/css?family='.str_replace(" ", "+", $custom_font_value).':300italic,400italic,600italic,700italic,400,300,600,700'.$subset;

        // register and enqueue style
        wp_register_style('zefir-'.$custom_font_id.'-font', $font_url, '', '', 'all');
        wp_enqueue_style('zefir-'.$custom_font_id.'-font');

      }
    }

  }
}
add_action('wp_enqueue_scripts', 'bwp_zefir_google_fonts');

/**
 * Add styles
 * ----------------------------------------------------------------------------
 */
if (!function_exists('bird_styles')) {
  function bird_styles() {
    wp_enqueue_style('bootstrap-style', get_template_directory_uri().'/css/bootstrap.min.css', '', '', 'all');
    wp_enqueue_style('font-awesome-style', get_template_directory_uri().'/css/font-awesome.min.css', '', '', 'all');
    wp_enqueue_style('prettyphoto-style', get_template_directory_uri().'/css/prettyPhoto.css', '', '', 'all');
    wp_enqueue_style('owl-carousel-style', get_template_directory_uri().'/assets/owl-carousel/owl.carousel.css', '', '', 'all');
    wp_enqueue_style('owl-theme-style', get_template_directory_uri().'/assets/owl-carousel/owl.theme.css', '', '', 'all');
    wp_enqueue_style('mightyslider-style', get_template_directory_uri().'/css/mightyslider.css', '', '', 'all');
    wp_enqueue_style('main-style', get_stylesheet_directory_uri().'/style.css', '', '', 'all');
  }
}
add_action('wp_enqueue_scripts', 'bird_styles');

/**
 * Options style
 * ----------------------------------------------------------------------------
 */
require_once('assets/options_style.php');

/**
 * HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries
 * ----------------------------------------------------------------------------
 */
if (!function_exists('bird_html5_respond_js')) {
  function bird_html5_respond_js() {
    echo "<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->\n";
    echo "<!--[if lt IE 9]>\n";
    echo "<script src=\"https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js\"></script>\n";
    echo "<script src=\"https://oss.maxcdn.com/respond/1.4.2/respond.min.js\"></script>\n";
    echo "<![endif]-->\n";
  }
}
add_action('wp_head', 'bird_html5_respond_js');

/**
 * Add scripts
 * ----------------------------------------------------------------------------
 */
if (!function_exists('bird_scripts_lib')) {
  function bird_scripts_lib() {
    wp_enqueue_script('jquery');

    global $bird_options;
    $pagination_type = (isset($bird_options['pagination_type'])) ? stripslashes($bird_options['pagination_type']) : 'Standard';

    /* bootstrap */
    wp_register_script('bootstrap-js', get_template_directory_uri().'/js/bootstrap.min.js', false, '3.3.4', true);
    wp_enqueue_script('bootstrap-js', array('jquery'));

    /* retina js */
    wp_register_script('retina-js', get_template_directory_uri().'/js/retina.min.js', false, '1.3.0', true);
    wp_enqueue_script('retina-js', array('jquery'));

    /* superfish */
    wp_register_script('superfish-js', get_template_directory_uri().'/js/superfish.min.js', false, '1.7.4', true);
    wp_enqueue_script('superfish-js', array('jquery'));

    // owl-carousel
    wp_register_script('owl-carousel-js', get_template_directory_uri().'/assets/owl-carousel/owl.carousel.min.js', false, '1.3.2', true);
    wp_enqueue_script('owl-carousel-js', array('jquery'));

    /* masonry */
    wp_register_script('masonry-js', get_template_directory_uri().'/js/masonry.pkgd.min.js', false, '3.3.0', true);
    wp_enqueue_script('masonry-js', array('jquery'));

    if ($pagination_type == 'Infinite scroll') {
      /* infinitescroll */
      wp_register_script('infinitescroll-js', get_template_directory_uri().'/js/jquery.infinitescroll.min.js', false, '2.1.0', true);
      wp_enqueue_script('infinitescroll-js', array('jquery'));
    }

    /* imagesloaded */
    wp_register_script('imagesloaded-js', get_template_directory_uri().'/js/jquery.imagesloaded.min.js', false, '3.1.8', true);
    wp_enqueue_script('imagesloaded-js', array('jquery'));

    /* mobile just-touch */
    wp_register_script('mobile-just-touch-js', get_template_directory_uri().'/js/jquery.mobile.just-touch.js', false, '1.0.0', true);
    wp_enqueue_script('mobile-just-touch-js', array('jquery'));

    /* easing */
    wp_register_script('easing-js', get_template_directory_uri().'/js/jquery.easing.1.3.js', false, '1.3.0', true);
    wp_enqueue_script('easing-js', array('jquery'));

    /* mightyslider */
    wp_register_script('mightyslider-js', get_template_directory_uri().'/js/mightyslider.min.js', false, '2.0.2', true);
    wp_enqueue_script('mightyslider-js', array('jquery'));

    // prettyPhoto
    wp_register_script('prettyphoto-js', get_template_directory_uri().'/js/jquery.prettyPhoto.js', false, '3.1.5' , true);
    wp_enqueue_script('prettyphoto-js', array('jquery'));

    /* common */
    wp_register_script('common-js', get_template_directory_uri().'/js/common.js', false, '2.0.0', true);
    wp_enqueue_script('common-js', array('jquery'));

    /* comments */
    if (is_singular() && comments_open() && get_option('thread_comments')) {
      wp_enqueue_script('comment-reply');
    }
  }

}
add_action('wp_enqueue_scripts', 'bird_scripts_lib');

/**
 * Admin scripts
 * ----------------------------------------------------------------------------
 */
if (!function_exists('bird_admin_scripts_lib')) {
  function bird_admin_scripts_lib() {

    // Show/Hide meta box
    wp_register_script('bwp-zefir-meta-box-js', get_template_directory_uri().'/assets/show-hide-meta-box.js', false, '1.0.0', true);
    wp_enqueue_script('bwp-zefir-meta-box-js', array('jquery'));

  }
}
add_action('admin_enqueue_scripts', 'bird_admin_scripts_lib', 9999);

/**
 * Add scripts in the footer
 * ----------------------------------------------------------------------------
 */
if (!function_exists('bird_scripts_custom')) {
  function bird_scripts_custom() {
    global $bird_options;
    $pagination_type = (isset($bird_options['pagination_type'])) ? stripslashes($bird_options['pagination_type']) : 'Standard';
    $post_links_target = (isset($bird_options['post_links_target'])) ? stripslashes($bird_options['post_links_target']) : 'Current tab';
    ?>

    <!-- masonry -->
    <script>
    jQuery.noConflict()(function($) {
      $(document).ready(function() {
        "use strict";

        // masonry
        var $container = $('.blog-container');
        $container.imagesLoaded(function(){
          $container.masonry({
            itemSelector: '.masonry-box',
            isAnimated: true
          });
        });

        <?php if ($post_links_target == 'New tab') { ?>
        // add target to links
        $('.is-article .post-title a, .is-article .post-thumb a, .is-article .post-carousel-item a, .is-article a.read-more, .is-article a.more-link, .is-article .bottom-meta-inf li a, .is-article .quote-content a').attr('target','_blank');
        <?php } ?>

        <?php if ($pagination_type == 'Infinite scroll') { ?>
        // infinitescroll
        if ($('#pagination-block').length > 0) {
          $('#pagination-block').css('display', 'none');
        }

        var $infiniteContainer = $('.infinite-container');

        $infiniteContainer.infinitescroll({
            navSelector  : '.pagination-wrap',
            nextSelector : '.pagination li a.infinite-next-page',
            itemSelector : '.is-article',
            loading: {
              msgText: '<?php esc_html_e('Loading new posts...', 'birdwp-theme'); ?>',
              finishedMsg: '<?php esc_html_e('No more posts to load', 'birdwp-theme'); ?>',
              img: '<?php echo esc_url(get_template_directory_uri().'/img/infinite_loader.GIF'); ?>',
            }
          },
          function(newElements) {
            var
              newElementsId_str,
              newElementsId,
              tempIdArr = [],
              $isSticky = false,
              stickyIdArr = [];

            // get new elements id
            for (var i=0; i < newElements.length; i++) {
              newElementsId_str = newElements[i].id;
              newElementsId = newElementsId_str.match(/(\d+)/i);

              // is gallery?
              if (newElements[i].className.split("format-gallery").length - 1) {
                tempIdArr.push(newElementsId[0]); // remember gallery post format id
                // is sticky?
                if (newElements[i].className.split('bwp-sticky-post').length - 1) {
                  $isSticky = true;
                  stickyIdArr.push(newElementsId[0]); // remember sticky post id
                }
              }
            }

            var $newElems = $(newElements).css({opacity: 0});

            $newElems.imagesLoaded(function() {

              // masonry
              $newElems.animate({opacity: 1});
              $container.masonry('appended', $newElems, true);

              // owlCarousel
              if (tempIdArr.length) {
                for (var i=0; i < tempIdArr.length; i++) {
                  $('#owl-carousel-' + tempIdArr[i]).owlCarousel({
                    navigation : true,
                    navigationText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
                    slideSpeed : 300,
                    pagination: false,
                    singleItem: true,
                    autoPlay: true,
                    stopOnHover: true,
                    afterInit: function() {
                      $container.masonry();
                    },
                    afterUpdate: function() {
                      $container.masonry();
                    }
                  });
                  // owlCarousel for sticky post
                  if ($isSticky) {
                    for (var j=0; j < stickyIdArr.length; j++) {
                      $('.bwp-sticky-post #owl-carousel-' + stickyIdArr[j]).owlCarousel({
                        navigation : true,
                        navigationText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
                        slideSpeed : 300,
                        pagination: false,
                        singleItem: true,
                        autoPlay: true,
                        stopOnHover: true,
                        afterInit: function() {
                          $container.masonry();
                        },
                        afterUpdate: function() {
                          $container.masonry();
                        }
                      });
                    }
                  }
                  stickyIdArr.length = 0;
                  // update masonry
                  $container.masonry();
                }
              }
              tempIdArr.length = 0;

            });

            <?php if ($post_links_target == 'New tab') { ?>
              // add target to links
              $('.is-article .post-title a, .is-article .post-thumb a, .is-article .post-carousel-item a, .is-article a.read-more, .is-article a.more-link, .is-article .bottom-meta-inf li a, .is-article .quote-content a').attr('target','_blank');
            <?php } ?>

          } // end callback
        ); // end infinitescroll
        <?php } ?>

      });
    });
    </script>

    <?php if (is_admin_bar_showing()) { ?>
      <script>
      jQuery.noConflict()(function($) {
        $(document).ready(function() {
          "use strict";
          $('body').css('padding-top', '0');
          $('#main-navigation-wrap').css({
            'position': 'relative',
            'top': 0,
            'left': 0
          });
        });
      });
      </script>
    <?php } else { ?>
      <script>
      jQuery.noConflict()(function($) {
        $(document).ready(function() {
          "use strict";

          var
            $menuContainer = $('#main-navigation-wrap'),
            scrollTopOffset = 400;

          $(window).scroll(function() {
            var docWidth = $(document).width();
            if (docWidth > 992) {
              if ($(window).scrollTop() > scrollTopOffset) {
                $menuContainer.addClass('animate-header');
              } else {
                $menuContainer.removeClass('animate-header');
              }
            } else {
              if ($menuContainer.hasClass('animate-header')) {
                $menuContainer.removeClass('animate-header');
              }
            }
          });

        });
      });
      </script>
    <?php } ?>

  <?php
  }
}
add_action('wp_footer', 'bird_scripts_custom');

/**
 * Add tracking code
 * ----------------------------------------------------------------------------
 */
if (!function_exists('bird_tracking_code')) {
  function bird_tracking_code() {
    global $bird_options;
    $tracking_code = (isset($bird_options['theme_tracking_code'])) ? stripslashes($bird_options['theme_tracking_code']) : '';
    if ($tracking_code) echo $tracking_code;
  }
}
add_action('wp_footer', 'bird_tracking_code');

/**
 * Show crop content on the post box
 * ----------------------------------------------------------------------------
 */
if (!function_exists('bird_show_crop_content')) {
  function bird_show_crop_content() {
    global $bird_options;
    $preview_length = (isset($bird_options['excerpt_length'])) ? $bird_options['excerpt_length'] : 200;

    echo bird_crop_content($preview_length);
  }
}

if (!function_exists('bird_crop_content')) {
  function bird_crop_content($maxLength) {
    $excerpt = get_the_excerpt();
    $maxLength = $maxLength + 6;

    if (mb_strlen($excerpt, 'UTF-8') >= $maxLength) {
      $resultCropPost = iconv_substr($excerpt, 0, $maxLength, 'UTF-8');
      return substr($resultCropPost, 0, strrpos($resultCropPost, ' ' )).' ...';
    } else {
      return $excerpt;
    }
  }
}

if (!function_exists('bird_quote_content')) {
  function bird_quote_content() {
    $excerpt = get_the_excerpt();
    return $excerpt;
  }
}

if (!function_exists('new_excerpt_length')) {
  function new_excerpt_length($length) {
    return 999;
  }
}
add_filter('excerpt_length', 'new_excerpt_length');

if (!function_exists('new_excerpt_more')) {
  function new_excerpt_more($more) {
    return '...';
  }
}
add_filter('excerpt_more', 'new_excerpt_more');

/**
 * Pagination function
 * ----------------------------------------------------------------------------
 */
if (!function_exists('bird_pagination')) {
  function bird_pagination($pages = '', $range = 3) {
    $showitems = ($range * 2)+1;

    global $paged;
    if(empty($paged)) $paged = 1;

    if($pages == '') {
      global $wp_query;
      $pages = $wp_query->max_num_pages;
      if(!$pages) {
        $pages = 1;
      }
    }

    if(1 != $pages) {
      echo '
      <div id="pagination-block">
        <div class="row">
          <div class="col-md-12">
            <div class="pagination-wrap">
              <ul class="pagination">';

              if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<li><a href='".get_pagenum_link(1)."'>&laquo;</a></li>";
              if($paged > 1 && $showitems < $pages) echo "<li><a href='".get_pagenum_link($paged - 1)."'>&lsaquo;</a></li>";

              for ($i=1; $i <= $pages; $i++) {
                if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )) {
                  echo ($paged == $i) ? "<li class=\"active\"><a href=\"#\">".$i."</a></li>" : "<li><a href='".get_pagenum_link($i)."' class=\"infinite-next-page\">".$i."</a></li>";
                }
              }

              if ($paged < $pages && $showitems < $pages) echo "<li><a href='".get_pagenum_link($paged + 1)."'>&rsaquo;</a></li>";
              if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<li><a href='".get_pagenum_link($pages)."'>&raquo;</a></li>";

              echo '
              </ul>
            </div>
          </div>
        </div>
      </div><!-- #pagination-block -->';
    }
  }
}

// standard wordpress pagination (next/previous page)
if (!function_exists('bird_pagination_nextprev')) {
  function bird_pagination_nextprev() {
    $next_page_link = __('Next page', 'birdwp-theme').'<i class="fa fa-angle-right"></i>';
    $previous_page_link = '<i class="fa fa-angle-left"></i>'.__('Previous page', 'birdwp-theme');

    echo '<div class="standard-wp-pagination clearfix">';
      echo '<span class="pg-previous-link">';
        previous_posts_link($previous_page_link);
      echo '</span>';
      echo '<span class="pg-next-link">';
        next_posts_link($next_page_link);
      echo '</span>';
    echo '</div>';
  }
}

/**
 * Exclude pages from search
 * ----------------------------------------------------------------------------
 */
global $bird_options;
$exclude_pages_search = (isset($bird_options['exclude_pages_search'])) ? $bird_options['exclude_pages_search'] : 1;
if ($exclude_pages_search) {

  if (!function_exists('bird_search_exclude_page')) {
    function bird_search_exclude_page($query) {
      if (!is_admin() && $query->is_main_query()) {
        if ($query->is_search) {
          $query->set('post_type', 'post');
        }
      }
    }
  }
  add_filter('pre_get_posts', 'bird_search_exclude_page');

}

/**
 * Template for comments in the theme
 * ----------------------------------------------------------------------------
 */
if (!function_exists('bird_comment')) {
  function bird_comment( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    switch ( $comment->comment_type ) :
      case 'pingback' :
      case 'trackback' :
        // Display trackbacks differently than normal comments.
        ?>
        <li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
        <p><?php esc_html_e( 'Pingback:', 'birdwp-theme' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( esc_html__( '(Edit)', 'birdwp-theme' ), '<span class="edit-link">', '</span>' ); ?></p>
        <?php
        break;
      default :
        // Proceed with normal comments.
        global $post;
        ?>
        <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
          <article id="comment-<?php comment_ID(); ?>" class="comment">
            <header class="comment-meta comment-author vcard">
              <?php
              echo get_avatar( $comment, 44 );
              printf( '<cite><b class="fn">%1$s</b> %2$s</cite>',
                get_comment_author_link(),
                // If current post author is also comment author, make it known visually.
                ( $comment->user_id === $post->post_author ) ? '<span>' . esc_html__( 'Post author', 'birdwp-theme' ) . '</span>' : ''
              );
              printf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
                esc_url(get_comment_link( $comment->comment_ID )),
                get_comment_time( 'c' ),
                /* translators: 1: date, 2: time */
                sprintf( esc_html__( '%1$s at %2$s', 'birdwp-theme' ), get_comment_date(), get_comment_time() )
              );
              ?>
            </header><!-- .comment-meta -->

            <?php if ( '0' == $comment->comment_approved ) : ?>
              <p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'birdwp-theme' ); ?></p>
            <?php endif; ?>

            <section class="comment-content comment">
              <?php comment_text(); ?>
              <?php edit_comment_link( esc_html__( 'Edit', 'birdwp-theme' ), '<span class="edit-link">', '</span>' ); ?>
              <span class="comment-reply-btn"><?php comment_reply_link( array_merge( $args, array( 'reply_text' => esc_html__( 'Reply', 'birdwp-theme' ), 'after' => ' <i class="fa fa-reply"></i>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?></span>
            </section><!-- .comment-content -->

          </article><!-- #comment-## -->
        <?php
        break;
    endswitch; // end comment_type check
  }
}

/**
 * Post views
 * ----------------------------------------------------------------------------
 */
if (!function_exists('getPostViews')) {
  function getPostViews($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
      delete_post_meta($postID, $count_key);
      add_post_meta($postID, $count_key, '0');
      return "0";
    }
    return $count;
  }
}

if (!function_exists('setPostViews')) {
  function setPostViews($postID) {
    if (!current_user_can('administrator') ) :
      $count_key = 'post_views_count';
      $count = get_post_meta($postID, $count_key, true);
      if($count=='') {
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
      } else {
        $count++;
        update_post_meta($postID, $count_key, $count);
      }
    endif;
  }
}

if (!function_exists('posts_column_views')) {
  function posts_column_views($defaults) {
    $defaults['post_views'] = __('Views', 'birdwp-theme');
    return $defaults;
  }
}

if (!function_exists('posts_custom_column_views' )) {
  function posts_custom_column_views($column_name, $id) {
    if( $column_name === 'post_views' ) {
      echo getPostViews(get_the_ID());
    }
  }
}
add_filter('manage_posts_columns', 'posts_column_views');
add_action('manage_posts_custom_column', 'posts_custom_column_views', 5, 2);

/**
 * Post likes
 * ----------------------------------------------------------------------------
 */

/*
Name:  WordPress Post Like System
Description:  A simple and efficient post like system for WordPress.
Version:      0.2
Author:       Jon Masterson
Author URI:   http://jonmasterson.com/
*/

/**
 * (1) Enqueue scripts for like system
 */
function like_scripts() {
  wp_enqueue_script( 'jm_like_post', get_template_directory_uri().'/js/post-like.js', array('jquery'), '1.0', 1 );
  wp_localize_script( 'jm_like_post', 'ajax_var', array(
      'url' => admin_url( 'admin-ajax.php' ),
      'nonce' => wp_create_nonce( 'ajax-nonce' )
    )
  );
}
add_action( 'wp_enqueue_scripts', 'like_scripts' );

/**
 * (2) Save like data
 */
add_action( 'wp_ajax_nopriv_jm-post-like', 'jm_post_like' );
add_action( 'wp_ajax_jm-post-like', 'jm_post_like' );
function jm_post_like() {
  $nonce = $_POST['nonce'];
  if ( ! wp_verify_nonce( $nonce, 'ajax-nonce' ) )
    die ( 'Nope!' );

  if ( isset( $_POST['jm_post_like'] ) ) {

    $post_id = $_POST['post_id']; // post id
    $post_like_count = get_post_meta( $post_id, "_post_like_count", true ); // post like count

    if ( is_user_logged_in() ) { // user is logged in
      global $current_user;
      $user_id = $current_user->ID; // current user
      $meta_POSTS = get_user_meta( $user_id, "_liked_posts" ); // post ids from user meta
      $meta_USERS = get_post_meta( $post_id, "_user_liked" ); // user ids from post meta
      $liked_POSTS = ""; // setup array variable
      $liked_USERS = ""; // setup array variable

      if ( count( $meta_POSTS ) != 0 ) { // meta exists, set up values
        $liked_POSTS = $meta_POSTS[0];
      }

      if ( !is_array( $liked_POSTS ) ) // make array just in case
        $liked_POSTS = array();

      if ( count( $meta_USERS ) != 0 ) { // meta exists, set up values
        $liked_USERS = $meta_USERS[0];
      }

      if ( !is_array( $liked_USERS ) ) // make array just in case
        $liked_USERS = array();

      $liked_POSTS['post-'.$post_id] = $post_id; // Add post id to user meta array
      $liked_USERS['user-'.$user_id] = $user_id; // add user id to post meta array
      $user_likes = count( $liked_POSTS ); // count user likes

      if ( !AlreadyLiked( $post_id ) ) { // like the post
        update_post_meta( $post_id, "_user_liked", $liked_USERS ); // Add user ID to post meta
        update_post_meta( $post_id, "_post_like_count", ++$post_like_count ); // +1 count post meta
        update_user_meta( $user_id, "_liked_posts", $liked_POSTS ); // Add post ID to user meta
        update_user_meta( $user_id, "_user_like_count", $user_likes ); // +1 count user meta
        echo esc_html($post_like_count); // update count on front end

      } else { // unlike the post
        $pid_key = array_search( $post_id, $liked_POSTS ); // find the key
        $uid_key = array_search( $user_id, $liked_USERS ); // find the key
        unset( $liked_POSTS[$pid_key] ); // remove from array
        unset( $liked_USERS[$uid_key] ); // remove from array
        $user_likes = count( $liked_POSTS ); // recount user likes
        update_post_meta( $post_id, "_user_liked", $liked_USERS ); // Remove user ID from post meta
        update_post_meta($post_id, "_post_like_count", --$post_like_count ); // -1 count post meta
        update_user_meta( $user_id, "_liked_posts", $liked_POSTS ); // Remove post ID from user meta
        update_user_meta( $user_id, "_user_like_count", $user_likes ); // -1 count user meta
        echo "already".esc_html($post_like_count); // update count on front end

      }

    } else { // user is not logged in (anonymous)
      $ip = $_SERVER['REMOTE_ADDR']; // user IP address
      $meta_IPS = get_post_meta( $post_id, "_user_IP" ); // stored IP addresses
      $liked_IPS = ""; // set up array variable

      if ( count( $meta_IPS ) != 0 ) { // meta exists, set up values
        $liked_IPS = $meta_IPS[0];
      }

      if ( !is_array( $liked_IPS ) ) // make array just in case
        $liked_IPS = array();

      if ( !in_array( $ip, $liked_IPS ) ) // if IP not in array
        $liked_IPS['ip-'.$ip] = $ip; // add IP to array

      if ( !AlreadyLiked( $post_id ) ) { // like the post

        update_post_meta( $post_id, "_user_IP", $liked_IPS ); // Add user IP to post meta
        update_post_meta( $post_id, "_post_like_count", ++$post_like_count ); // +1 count post meta
        echo esc_html($post_like_count); // update count on front end

      } else { // unlike the post

        $ip_key = array_search( $ip, $liked_IPS ); // find the key
        unset( $liked_IPS[$ip_key] ); // remove from array
        update_post_meta( $post_id, "_user_IP", $liked_IPS ); // Remove user IP from post meta
        update_post_meta( $post_id, "_post_like_count", --$post_like_count ); // -1 count post meta
        echo "already".esc_html($post_like_count); // update count on front end

      }
    }
  }

  exit;
}

/**
 * (3) Test if user already liked post
 */
function AlreadyLiked( $post_id ) { // test if user liked before

  if ( is_user_logged_in() ) { // user is logged in
    global $current_user;
    $user_id = $current_user->ID; // current user
    $meta_USERS = get_post_meta( $post_id, "_user_liked" ); // user ids from post meta
    $liked_USERS = ""; // set up array variable

    if ( count( $meta_USERS ) != 0 ) { // meta exists, set up values
      $liked_USERS = $meta_USERS[0];
    }

    if( !is_array( $liked_USERS ) ) // make array just in case
      $liked_USERS = array();

    if ( in_array( $user_id, $liked_USERS ) ) { // True if User ID in array
      return true;
    }
    return false;

  } else { // user is anonymous, use IP address for voting

    $meta_IPS = get_post_meta($post_id, "_user_IP"); // get previously voted IP address
    $ip = $_SERVER["REMOTE_ADDR"]; // Retrieve current user IP
    $liked_IPS = ""; // set up array variable

    if ( count( $meta_IPS ) != 0 ) { // meta exists, set up values
      $liked_IPS = $meta_IPS[0];
    }

    if ( !is_array( $liked_IPS ) ) // make array just in case
      $liked_IPS = array();

    if ( in_array( $ip, $liked_IPS ) ) { // True is IP in array
      return true;
    }
    return false;
  }

}

/**
 * (4) Front end button
 */
function getPostLikeLink( $post_id ) {
  $theme_object = wp_get_theme();
  $themename = $theme_object->Name; // the theme name
  $like_count = get_post_meta( $post_id, "_post_like_count", true ); // get post likes
  if ( ( !$like_count ) || ( $like_count && $like_count == "0" ) ) { // no votes, set up empty variable
    $likes = '0';
  } elseif ( $like_count && $like_count != "0" ) { // there are votes!
    $likes = $like_count;
  }
  $output = '<span class="jm-post-like">';
  $output .= '<a rel="nofollow" href="#" data-post_id="'.$post_id.'" onclick="return false;">';
  if ( AlreadyLiked( $post_id ) ) { // already liked, set up unlike addon
    $output .= '<span class="like prevliked"><i class="fa fa-heart-o"></i></span>';
    $output .= '<span class="count alreadyliked">'.$likes.'</span></a></span>';
  } else { // normal like button
    $output .= '<span class="like"><i class="fa fa-heart-o"></i></span>';
    $output .= '<span class="count">'.$likes.'</span></a></span>';
  }
  return $output;
}

/**
 * (5) Retrieve User Likes and Show on Profile
 */
add_action( 'show_user_profile', 'show_user_likes' );
add_action( 'edit_user_profile', 'show_user_likes' );
function show_user_likes( $user ) { ?>
  <table class="form-table">
    <tr>
      <th><label for="user_likes"><?php _e('You Like:', 'birdwp-theme'); ?></label></th>
      <td>
        <?php global $current_user;
        $user_likes = get_user_meta( $user->ID, "_liked_posts");
        if ( $user_likes && count( $user_likes ) > 0 ) {
          $the_likes = $user_likes[0];
        } else {
          $the_likes = '';
        }

        if ( !is_array( $the_likes ) )
          $the_likes = array();
        $count = count($the_likes); $i=0;
        if ( $count > 0 ) {
          $like_list = '';
          echo '<p>';
          foreach ( $the_likes as $the_like ) {
            $i++;
            $like_list .= '<a href="' . get_permalink( $the_like ) . '" title="' . get_the_title( $the_like ) . '">' . get_the_title( $the_like ) . '</a>';
            if ($count != $i) $like_list .= ' &middot; ';
            else $like_list .= '</p>';
          }
          echo !empty($like_list) ? $like_list : '';
        } else {
          echo '<p>'.esc_html__('You don\'t like anything yet.', 'birdwp-theme').'</p>';
        } ?>
      </td>
    </tr>
  </table>
<?php }

/**
 * Registers widget areas and sidebars
 * ----------------------------------------------------------------------------
 */
if (!function_exists('bird_init_widgets')) {
  function bird_init_widgets() {

    // homepage sidebar
    register_sidebar(array(
      'name' => 'Homepage sidebar',
      'id' => 'bird_home_sidebar',
      'description' => __('Appears on the Homepage', 'birdwp-theme'),
      'before_widget' => '<aside id="%1$s" class="widget %2$s clearfix">',
      'after_widget' => '</aside>',
      'before_title' => '<h3 class="widget-title">',
      'after_title' => '</h3>',
    ));

    // homepage second sidebar
    register_sidebar(array(
      'name' => 'Homepage second sidebar',
      'id' => 'bird_home_2_sidebar',
      'description' => __('Appears on the Homepage (second left sidebar)', 'birdwp-theme'),
      'before_widget' => '<aside id="%1$s" class="widget %2$s clearfix">',
      'after_widget' => '</aside>',
      'before_title' => '<h3 class="widget-title">',
      'after_title' => '</h3>',
    ));

    // single post page
    register_sidebar(array(
      'name' => 'Single post sidebar',
      'id' => 'bird_single_sidebar',
      'description' => __('Appears on the Single post page', 'birdwp-theme'),
      'before_widget' => '<aside id="%1$s" class="widget %2$s clearfix">',
      'after_widget' => '</aside>',
      'before_title' => '<h3 class="widget-title">',
      'after_title' => '</h3>',
    ));

    // category page sidebar
    register_sidebar(array(
      'name' => 'Category page sidebar',
      'id' => 'bird_category_sidebar',
      'description' => __('Appears on the Category page', 'birdwp-theme'),
      'before_widget' => '<aside id="%1$s" class="widget %2$s clearfix">',
      'after_widget' => '</aside>',
      'before_title' => '<h3 class="widget-title">',
      'after_title' => '</h3>',
    ));

    // category page second sidebar
    register_sidebar(array(
      'name' => 'Category page second sidebar',
      'id' => 'bird_category_2_sidebar',
      'description' => __('Appears on the Category page (second left sidebar)', 'birdwp-theme'),
      'before_widget' => '<aside id="%1$s" class="widget %2$s clearfix">',
      'after_widget' => '</aside>',
      'before_title' => '<h3 class="widget-title">',
      'after_title' => '</h3>',
    ));

    // archive page sidebar
    register_sidebar(array(
      'name' => 'Archive page sidebar',
      'id' => 'bird_archive_sidebar',
      'description' => __('Appears on the Archive, Search result and 404 page', 'birdwp-theme'),
      'before_widget' => '<aside id="%1$s" class="widget %2$s clearfix">',
      'after_widget' => '</aside>',
      'before_title' => '<h3 class="widget-title">',
      'after_title' => '</h3>',
    ));

    // archive page second sidebar
    register_sidebar(array(
      'name' => 'Archive page second sidebar',
      'id' => 'bird_archive_2_sidebar',
      'description' => __('Appears on the Archive, Search result and 404 page (second left sidebar)', 'birdwp-theme'),
      'before_widget' => '<aside id="%1$s" class="widget %2$s clearfix">',
      'after_widget' => '</aside>',
      'before_title' => '<h3 class="widget-title">',
      'after_title' => '</h3>',
    ));

    // tag page sidebar
    register_sidebar(array(
      'name' => 'Tag page sidebar',
      'id' => 'bird_tag_sidebar',
      'description' => __('Appears on the Tag page', 'birdwp-theme'),
      'before_widget' => '<aside id="%1$s" class="widget %2$s clearfix">',
      'after_widget' => '</aside>',
      'before_title' => '<h3 class="widget-title">',
      'after_title' => '</h3>',
    ));

    // tag page second sidebar
    register_sidebar(array(
      'name' => 'Tag page second sidebar',
      'id' => 'bird_tag_2_sidebar',
      'description' => __('Appears on the Tag page (second left sidebar)', 'birdwp-theme'),
      'before_widget' => '<aside id="%1$s" class="widget %2$s clearfix">',
      'after_widget' => '</aside>',
      'before_title' => '<h3 class="widget-title">',
      'after_title' => '</h3>',
    ));

    // page, template-archives, template-sitemap areas
    register_sidebar(array(
      'name' => 'Page sidebar',
      'id' => 'bird_page_sidebar',
      'description' => __('Appears on the Pages', 'birdwp-theme'),
      'before_widget' => '<aside id="%1$s" class="widget %2$s clearfix">',
      'after_widget' => '</aside>',
      'before_title' => '<h3 class="widget-title">',
      'after_title' => '</h3>',
    ));

    // footer area
    register_sidebar(array(
      'name' => 'Footer area',
      'id' => 'bird_footer_sidebar',
      'description' => __('Appears on the Footer', 'birdwp-theme'),
      'before_widget' => '<aside id="%1$s" class="widget %2$s clearfix">',
      'after_widget' => '</aside>',
      'before_title' => '<h3 class="widget-title">',
      'after_title' => '</h3>',
    ));

  }
}
add_action('widgets_init', 'bird_init_widgets');

/**
 * Add new widgets
 * ----------------------------------------------------------------------------
 */
require_once('widgets/zefir_contact_form_widget.php');
require_once('widgets/zefir-posts-thumbs-widget.php');
require_once('widgets/zefir-recent-posts-widget.php');
require_once('widgets/zefir-popular-posts-widget.php');

/**
 * Add new contact information fields in the Admin profile
 * ----------------------------------------------------------------------------
 */
if (!function_exists('bird_new_contact_fields')) {
  function bird_new_contact_fields($user_contact) {
    /* Add user contact methods */
    if (!isset($user_contact['twitter'])) {
      $user_contact['twitter'] = __('Your Twitter URL', 'birdwp-theme');
    }
    if (!isset($user_contact['facebook'])) {
      $user_contact['facebook'] = __('Your Facebook URL', 'birdwp-theme');
    }
    if (!isset($user_contact['google'])) {
      $user_contact['google'] = __('Your Google+ URL', 'birdwp-theme');
    }
    if (!isset($user_contact['vk'])) {
      $user_contact['vk'] = __('Your VK URL', 'birdwp-theme');
    }
    if (!isset($user_contact['youtube'])) {
      $user_contact['youtube'] = __('Your YouTube URL', 'birdwp-theme');
    }
    if (!isset($user_contact['flickr'])) {
      $user_contact['flickr'] = __('Your Flickr URL', 'birdwp-theme');
    }
    if (!isset($user_contact['instagram'])) {
      $user_contact['instagram'] = __('Your Instagram URL', 'birdwp-theme');
    }
    if (!isset($user_contact['dribbble'])) {
      $user_contact['dribbble'] = __('Your Dribbble URL', 'birdwp-theme');
    }

    return $user_contact;
  }
}
add_filter('user_contactmethods', 'bird_new_contact_fields');

/**
 * Blog post details - Author / category
 * ----------------------------------------------------------------------------
 */
if (!function_exists('bwp_zefir_post_details_top')) {
  function bwp_zefir_post_details_top() {
    global $bird_options;
    $show_blog_author = (isset($bird_options['show_blog_author'])) ? $bird_options['show_blog_author'] : 1;
    $show_blog_category = (isset($bird_options['show_blog_category'])) ? $bird_options['show_blog_category'] : 1;

    if ($show_blog_author || $show_blog_category) {
      ?>

      <!-- top meta inf -->
      <div class="top-meta-inf-wrap">
        <ul class="list-unstyled meta-inf meta clearfix">
          <?php if ($show_blog_author) { ?>
            <li><i class="fa fa-user"></i><span class="vcard author post-author"><span class="fn"><?php echo the_author_posts_link(); ?></span></span></li>
          <?php } ?>
          <?php if ($show_blog_category) { ?>
            <li><i class="fa fa-bookmark"></i><?php the_category(', '); ?></li>
          <?php } ?>
          <li class="post-date date updated" style="display: none;"><?php the_time(get_option('date_format')); ?></li>
        </ul>
      </div>
      <!-- end top meta inf -->

      <?php
    }
  }
}

/**
 * Post excerpt
 * ----------------------------------------------------------------------------
 */
if (!function_exists('bwp_zefir_post_excerpt')) {
  function bwp_zefir_post_excerpt() {
    global $bird_options;
    $blog_excerpt_type = (isset($bird_options['blog_excerpt_type'])) ? stripslashes($bird_options['blog_excerpt_type']) : 'Excerpt';
    $read_more_text = (isset($bird_options['read_more_text'])) ? stripslashes($bird_options['read_more_text']) : 'read more';

    if (!$read_more_text) {
      $read_more_text = 'read more';
    }

    if ($blog_excerpt_type == 'Excerpt') {
      bird_show_crop_content();
      $show_read_more = (isset($bird_options['show_read_more'])) ? $bird_options['show_read_more'] : 1;
      if ($show_read_more) {
        ?>
        <a href="<?php the_permalink(); ?>" class="read-more"><?php echo esc_html($read_more_text); ?></a>
        <?php
      }
    } else {
      $read_more_text = esc_html($read_more_text);
      the_content($read_more_text, false, '');
    }
  }
}

/**
 * Post counters (views, comments, likes) + social share buttons
 * ----------------------------------------------------------------------------
 */
if (!function_exists('bwp_zefir_post_counters')) {
  function bwp_zefir_post_counters() {
    global $bird_options;
    $show_blog_views = (isset($bird_options['show_blog_views'])) ? $bird_options['show_blog_views'] : 1;
    $show_blog_comments = (isset($bird_options['show_blog_comments'])) ? $bird_options['show_blog_comments'] : 1;
    $show_blog_likes = (isset($bird_options['show_blog_likes'])) ? $bird_options['show_blog_likes'] : 1;
    $post_social_share = (isset($bird_options['post_social_share'])) ? $bird_options['post_social_share'] : 1;

    if ($show_blog_views || $show_blog_comments || $show_blog_likes || $post_social_share) { ?>
      <!-- bottom meta inf -->
      <div class="bottom-meta-inf-wrap">
        <ul class="list-unstyled bottom-meta-inf meta clearfix">
          <?php if ($show_blog_views) { ?>
            <li><i class="fa fa-eye"></i><?php echo getPostViews(get_the_ID()); ?></li>
          <?php } ?>
          <?php if ($show_blog_comments && (comments_open() || get_comments_number())) { ?>
            <li><a href="<?php the_permalink(); ?>#comments"><i class="fa fa-comment-o"></i><?php comments_number('0', '1', '%'); ?></a></li>
          <?php } ?>
          <?php if ($show_blog_likes) { ?>
            <li><?php echo getPostLikeLink(get_the_ID()); ?></li>
          <?php } ?>
          <?php if ($post_social_share) { ?>
            <li class="share-icon">
              <a rel="nofollow" href="#" data-share_id="<?php the_ID(); ?>"><i class="fa fa-share-alt"></i></a>
              <span id="share-block-<?php the_ID(); ?>" class="share-block-wrap share-block-hidden">
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
      <!-- end bottom meta inf -->
      <?php
    }
  }
}
