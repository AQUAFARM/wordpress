<?php
/**
 * Inline styles (custom styles from customizer)
 *
 * @package WordPress
 * @subpackage Zefir
 * @since Zefir 1.0
 */

if (!function_exists('bird_options_custom_style')) {
  function bird_options_custom_style() {
    global $bird_options;

    $inline_styles = '';

    /**
     * header
     */
    $header_bg_color = (isset($bird_options['header_bg_color'])) ? stripslashes($bird_options['header_bg_color']) : '';
    $menu_text_color = (isset($bird_options['menu_text_color'])) ? stripslashes($bird_options['menu_text_color']) : '';
    $menu_text_hover_color = (isset($bird_options['menu_text_hover_color'])) ? stripslashes($bird_options['menu_text_hover_color']) : '';
    $menu_font = (isset($bird_options['menu_font'])) ? $bird_options['menu_font'] : '';
    $menu_font_transform = (isset($bird_options['menu_font_transform'])) ? stripslashes($bird_options['menu_font_transform']) : '';
    $drop_menu_bg_color = (isset($bird_options['drop_menu_bg_color'])) ? stripslashes($bird_options['drop_menu_bg_color']) : '';
    $drop_menu_text_color = (isset($bird_options['drop_menu_text_color'])) ? stripslashes($bird_options['drop_menu_text_color']) : '';
    $drop_menu_text_hover_color = (isset($bird_options['drop_menu_text_hover_color'])) ? stripslashes($bird_options['drop_menu_text_hover_color']) : '';
    $drop_menu_border = (isset($bird_options['drop_menu_border'])) ? $bird_options['drop_menu_border'] : '';
    $drop_menu_font = (isset($bird_options['drop_menu_font'])) ? $bird_options['drop_menu_font'] : '';
    $drop_menu_font_transform = (isset($bird_options['drop_menu_font_transform'])) ? stripslashes($bird_options['drop_menu_font_transform']) : '';

    if ($header_bg_color) {
      $inline_styles .= '
      .main-navigation {
        background-color: '.$header_bg_color.';
      }';
    }
    if ($menu_text_color) {
      $inline_styles .= '
      ul.sf-menu a,
      .sf-arrows .sf-with-ul:after,
      ul.responsive-nav li a,
      ul.responsive-nav li ul li a,
      .main-nav-collapse-btn,
      .main-nav-collapse-btn:focus {
        color: '.$menu_text_color.';
      }';
    }
    if ($menu_text_hover_color) {
      $inline_styles .= '
      ul.sf-menu a:hover,
      ul.sf-menu > li:hover > a,
      .sf-arrows > li > .sf-with-ul:focus:after,
      .sf-arrows > li:hover > .sf-with-ul:after,
      .sf-arrows > .sfHover > .sf-with-ul:after,
      ul.responsive-nav li a:hover,
      .main-nav-collapse-btn:hover {
        color: '.$menu_text_hover_color.';
      }';
    }
    if ($menu_font) {
      if ($menu_font['style'] == 'light') { $menu_font_style = 'font-weight: 300; font-style: normal;'; } else
      if ($menu_font['style'] == 'light italic') { $menu_font_style = 'font-weight: 300; font-style: italic;'; } else
      if ($menu_font['style'] == 'normal') { $menu_font_style = 'font-weight: 400; font-style: normal;'; } else
      if ($menu_font['style'] == 'italic') { $menu_font_style = 'font-weight: 400; font-style: italic;'; } else
      if ($menu_font['style'] == 'semi bold') { $menu_font_style = 'font-weight: 600; font-style: normal;'; } else
      if ($menu_font['style'] == 'semi bold italic') { $menu_font_style = 'font-weight: 600; font-style: italic;'; } else
      if ($menu_font['style'] == 'bold') { $menu_font_style = 'font-weight: bold; font-style: normal;'; } else
      if ($menu_font['style'] == 'bold italic') { $menu_font_style = 'font-weight: bold; font-style: italic;'; }
      $inline_styles .= '
      ul.sf-menu a { font-size: '.$menu_font['size'].'; '.$menu_font_style.' }';
    }
    if ($menu_font_transform) {
      $inline_styles .= '
      ul.sf-menu a { text-transform: '.$menu_font_transform.'; }';
    }
    if ($drop_menu_bg_color) {
      $inline_styles .= '
      ul.sf-menu ul,
      .dropdown-search {
        background-color: '.$drop_menu_bg_color.';
      }';
    }
    if ($drop_menu_text_color) {
      $inline_styles .= '
      ul.sf-menu ul li a,
      .sf-arrows ul .sf-with-ul:after {
        color: '.$drop_menu_text_color.';
      }';
    }
    if ($drop_menu_text_hover_color) {
      $inline_styles .= '
      ul.sf-menu ul li a:hover,
      ul.sf-menu ul > li:hover > a,
      ul.sf-menu ul ul > li:hover > a,
      .sf-arrows ul li > .sf-with-ul:focus:after,
      .sf-arrows ul li:hover > .sf-with-ul:after,
      .sf-arrows ul .sfHover > .sf-with-ul:after {
        color:  '.$drop_menu_text_hover_color.';
      }
      .dropdown-search #searchform .search-field:active,
      .dropdown-search #searchform .search-field:focus {
        border-color: '.$drop_menu_text_hover_color.';
      }';
    }
    if ($drop_menu_border) {
      $inline_styles .= '
      ul.sf-menu ul,
      .dropdown-search {
        border-top-color: '.$drop_menu_border['color'].';
      }
      ul.sf-menu ul li a {
        border-top: '.$drop_menu_border['width'].'px '.$drop_menu_border['style'].' '.$drop_menu_border['color'].';
      }';
      if ($drop_menu_text_hover_color) {
        $inline_styles .= '
        .dropdown-search #searchform .search-field {
          border-color: '.$drop_menu_border['color'].';
        }';
      }
    }
    if ($drop_menu_font) {
      if ($drop_menu_font['style'] == 'light') { $drop_menu_font_style = 'font-weight: 300; font-style: normal;'; } else
      if ($drop_menu_font['style'] == 'light italic') { $drop_menu_font_style = 'font-weight: 300; font-style: italic;'; } else
      if ($drop_menu_font['style'] == 'normal') { $drop_menu_font_style = 'font-weight: 400; font-style: normal;'; } else
      if ($drop_menu_font['style'] == 'italic') { $drop_menu_font_style = 'font-weight: 400; font-style: italic;'; } else
      if ($drop_menu_font['style'] == 'semi bold') { $drop_menu_font_style = 'font-weight: 600; font-style: normal;'; } else
      if ($drop_menu_font['style'] == 'semi bold italic') { $drop_menu_font_style = 'font-weight: 600; font-style: italic;'; } else
      if ($drop_menu_font['style'] == 'bold') { $drop_menu_font_style = 'font-weight: bold; font-style: normal;'; } else
      if ($drop_menu_font['style'] == 'bold italic') { $drop_menu_font_style = 'font-weight: bold; font-style: italic;'; }
      $inline_styles .= '
      ul.sf-menu ul li a { font-size: '.$drop_menu_font['size'].'; '.$drop_menu_font_style.' }';
    }
    if ($drop_menu_font_transform) {
      $inline_styles .= '
      ul.sf-menu ul li a { text-transform: '.$drop_menu_font_transform.'; }';
    }

    /**
     * search icon
     */
    $search_icon_color = (isset($bird_options['search_icon_color'])) ? stripslashes($bird_options['search_icon_color']) : '';
    $search_icon_hover_color = (isset($bird_options['search_icon_hover_color'])) ? stripslashes($bird_options['search_icon_hover_color']) : '';

    if ($search_icon_color) {
      $inline_styles .= '
      .drop-search-wrap a.search-icon {
        color: '.$search_icon_color.';
      }';
    }
    if ($search_icon_hover_color) {
      $inline_styles .= '
      .drop-search-wrap a.search-icon:hover,
      .drop-search-wrap a.search-icon.active-icon {
        color: '.$search_icon_hover_color.';
      }';
    }

    /**
     * logo style
     */
    $logo_type = (isset($bird_options['logo_type'])) ? stripslashes($bird_options['logo_type']) : 'text';
    $logo_font = (isset($bird_options['logo_font'])) ? $bird_options['logo_font'] : '';
    $logo_font_transform = (isset($bird_options['logo_font_transform'])) ? stripslashes($bird_options['logo_font_transform']) : '';
    $logo_text_color = (isset($bird_options['logo_text_color'])) ? stripslashes($bird_options['logo_text_color']) : '';
    $logo_text_hover_color = (isset($bird_options['logo_text_hover_color'])) ? stripslashes($bird_options['logo_text_hover_color']) : '';
    $logo_image_margin_right = (isset($bird_options['logo_image_margin_right'])) ? $bird_options['logo_image_margin_right'] : 35;

    if ($logo_type == 'text') {
      if ($logo_font) {
        if ($logo_font['style'] == 'light') { $logo_font_style = 'font-weight: 300; font-style: normal;'; } else
        if ($logo_font['style'] == 'light italic') { $logo_font_style = 'font-weight: 300; font-style: italic;'; } else
        if ($logo_font['style'] == 'normal') { $logo_font_style = 'font-weight: 400; font-style: normal;'; } else
        if ($logo_font['style'] == 'italic') { $logo_font_style = 'font-weight: 400; font-style: italic;'; } else
        if ($logo_font['style'] == 'semi bold') { $logo_font_style = 'font-weight: 600; font-style: normal;'; } else
        if ($logo_font['style'] == 'semi bold italic') { $logo_font_style = 'font-weight: 600; font-style: italic;'; } else
        if ($logo_font['style'] == 'bold') { $logo_font_style = 'font-weight: bold; font-style: normal;'; } else
        if ($logo_font['style'] == 'bold italic') { $logo_font_style = 'font-weight: bold; font-style: italic;'; }
        $inline_styles .= '
        .logo { font-size: '.$logo_font['size'].'; '.$logo_font_style.' }';

        // custom logo google font ( add google font in the functions.php )
        $logo_google_font = $logo_font['face'];
        $inline_styles .= '
        .logo { font-family: '.$logo_google_font.', Arial, Helvetica, sans-serif; }';
      }
      if ($logo_font_transform) {
        $inline_styles .= '
        .logo { text-transform: '.$logo_font_transform.'; }';
      }
      if ($logo_text_color) {
        $inline_styles .= '
        .logo a {
          color: '.$logo_text_color.';
        }';
      }
      if ($logo_text_hover_color) {
        $inline_styles .= '
        .logo a:hover {
          color: '.$logo_text_hover_color.';
        }';
      }
    } else if ($logo_type == 'image') {
      if ($logo_image_margin_right) {
        $inline_styles .= '
        .logo-img {
          margin-right: '.$logo_image_margin_right.'px;
        }';
      }
    }

    /**
     * main theme color
     */
    $theme_main_color = (isset($bird_options['theme_main_color'])) ? stripslashes($bird_options['theme_main_color']) : '';
    $theme_main_hover_color = (isset($bird_options['theme_main_hover_color'])) ? stripslashes($bird_options['theme_main_hover_color']) : '';
    $theme_main_text_color = (isset($bird_options['theme_main_text_color'])) ? stripslashes($bird_options['theme_main_text_color']) : '';
    $theme_links_color = (isset($bird_options['theme_links_color'])) ? stripslashes($bird_options['theme_links_color']) : '';
    $theme_links_hover_color = (isset($bird_options['theme_links_hover_color'])) ? stripslashes($bird_options['theme_links_hover_color']) : '';

    if ($theme_main_color) {
      $inline_styles .= '
      .post-media-carousel .owl-theme .owl-controls .owl-buttons div,
      .read-more,
      .more-link,
      .pagination li.active a,
      .pagination li.active a:hover,
      .pagination li.active a:focus,
      #scroll-top,
      .single-pagination-wrap > span,
      .single-pagination-wrap a:hover {
        background: '.$theme_main_color.';
      }
      #searchform .search-submit {
        background-color: '.$theme_main_color.';
        border-color: '.$theme_main_color.';
      }
      li.share-icon > a.share-icon-active,
      li.single-share-icon > a.share-icon-active {
        color: '.$theme_main_color.';
      }';
    }
    if ($theme_main_hover_color) {
      $inline_styles .= '
      .post-media-carousel .owl-theme .owl-controls .owl-buttons div:hover,
      .read-more:hover,
      .more-link:hover,
      #scroll-top:hover,
      .single-pagination-wrap a {
        background-color: '.$theme_main_hover_color.';
      }
      .pagination li a:hover,
      .pagination li a:focus,
      .standard-wp-pagination a:hover,
      .standard-wp-pagination a:focus {
        color: '.$theme_main_hover_color.';
        background-color: #FFFFFF;
      }
      #searchform .search-submit:hover {
        background-color: '.$theme_main_hover_color.';
        border-color: '.$theme_main_hover_color.';
      }';
    }
    if ($theme_main_text_color) {
      $inline_styles .= '
      .post-media-carousel .owl-theme .owl-controls .owl-buttons div,
      .post-media-carousel .owl-theme .owl-controls .owl-buttons div:hover,
      .read-more,
      .more-link,
      .read-more:hover,
      .more-link:hover,
      .pagination li.active a,
      .pagination li.active a:hover,
      .pagination li.active a:focus,
      #scroll-top,
      #searchform .search-submit,
      .single-pagination-wrap a,
      .single-pagination-wrap > span {
        color: '.$theme_main_text_color.';
      }';
    }
    if ($theme_links_color) {
      $inline_styles .= '
      a,
      .content a {
        color: '.$theme_links_color.';
      }';
    }
    if ($theme_links_hover_color) {
      $inline_styles .= '
      a:hover,
      .content a:hover,
      #main-slider .mS-rwd-caption h1 a:hover,
      #main-slider .mS-rwd-caption span a:hover,
      ul.meta-inf li a:hover,
      .post-title a:hover,
      ul.bottom-meta-inf li a:hover,
      .edit-link:hover i,
      .edit-link:hover a,
      .edit-link-wrap a:hover,
      ul.single-meta-inf li a:hover,
      .prev-next-posts-nav a:hover,
      .comment-meta a:hover,
      .comment-content span.edit-link a:hover,
      .comment-content span.comment-reply-btn a:hover,
      ol.commentlist .pingback a:hover,
      #commentform p.logged-in-as a:hover,
      #cancel-comment-reply-link:hover,
      #comment-nav-below a:hover,
      .archive-section-title a:hover,
      .sitemap-list li a:hover,
      .archives-list li a:hover,
      .widget-title a:hover,
      .widget_archive ul li a:hover,
      .widget_pages ul li a:hover,
      .widget_categories a:hover,
      .widget_recent_entries ul li a:hover,
      .widget_nav_menu a:hover,
      .widget_meta ul li a:hover,
      #recentcomments li a:hover,
      .widget_rss ul li a:hover,
      .bird-widget-content h4 a:hover,
      ul.bird-widget-meta li a:hover,
      .jm-post-like a:hover,
      .jm-post-like a:hover .like.pastliked,
      .jm-post-like a:hover .count.liked,
      .jm-post-like a:hover .like.disliked,
      .jm-post-like a:hover .count.disliked,
      .jm-post-like a:hover .like.prevliked,
      .jm-post-like a:hover .count.alreadyliked,
      .footer-social-icons li a:hover,
      .about-author-desc-wrap h3 a:hover,
      .about-author-social ul li a:hover,
      .bsc-nav-tabs li a:focus,
      .bsc-nav-tabs li a:hover,
      .bsc-accordion-title a:hover,
      #wp-calendar #next a:hover,
      #wp-calendar #prev a:hover {
        color: '.$theme_links_hover_color.';
      }
      .bird-big-btn:hover,
      .bird-small-btn:hover,
      #main-slider .main-slider-caption .bird-big-btn:hover,
      .post-password-form input[type="submit"]:hover,
      .widget_tag_cloud a:hover {
        color: '.$theme_links_hover_color.';
        border-color: '.$theme_links_hover_color.';
      }';
    }

    /**
     * body bg styles
     */
    $body_bg_type = (isset($bird_options['body_bg_type'])) ? stripslashes($bird_options['body_bg_type']) : 'Predefined Image';
    $body_bg_color = (isset($bird_options['body_bg_color'])) ? stripslashes($bird_options['body_bg_color']) : '';
    $body_bg_image = (isset($bird_options['body_bg_image'])) ? stripslashes($bird_options['body_bg_image']) : '';
    $body_bg_image_repeat = (isset($bird_options['body_bg_image_repeat'])) ? stripslashes($bird_options['body_bg_image_repeat']) : '';
    $body_bg_image_attachment = (isset($bird_options['body_bg_image_attachment'])) ? stripslashes($bird_options['body_bg_image_attachment']) : '';
    $body_bg_image_position = (isset($bird_options['body_bg_image_position'])) ? stripslashes($bird_options['body_bg_image_position']) : '';
    $body_bg_predefined_image = (isset($bird_options['body_bg_predefined_image'])) ? stripslashes($bird_options['body_bg_predefined_image']) : '';
    $show_sidebar_bg_color = (isset($bird_options['show_sidebar_bg_color'])) ? $bird_options['show_sidebar_bg_color'] : 0;

    if ($body_bg_type == 'Color') {
      if ($body_bg_color) {
        $inline_styles .= '
        body {
          background: '.$body_bg_color.';
        }';
      }
    } else if ($body_bg_type == 'Image') {
      if ($body_bg_image) {
        if ($body_bg_color) {
          $body_bg_color_var = 'background-color: '.$body_bg_color.';';
        } else {
          $body_bg_color_var = '';
        }
        $inline_styles .= '
        body {
          background-image: url('.$body_bg_image.');
          '.$body_bg_color_var.'
          background-repeat: '.$body_bg_image_repeat.';
          background-attachment: '.$body_bg_image_attachment.';
          background-position: '.$body_bg_image_position.';
        }
        ';
      }
    } else if ($body_bg_type == 'Predefined Image') {
      if ($body_bg_predefined_image) {
        $inline_styles .= '
        body {
          background: url('.$body_bg_predefined_image.') fixed;
        }';
      }
    }
    if ($show_sidebar_bg_color) {
      $inline_styles .= '
      .sidebar-wrap {
        background-color: #FFFFFF;
        padding: 30px 20px;
        border-top: 4px solid #BFDEEA;
        -webkit-box-shadow: 0 2px 3px rgba(0,0,0, .1);
        -moz-box-shadow: 0 2px 3px rgba(0,0,0, .1);
        box-shadow: 0 2px 3px rgba(0,0,0, .1);
      }';
      if ($theme_main_color) {
        $inline_styles .= '
        .sidebar-wrap {
          border-top-color: '.$theme_main_color.';
        }';
      }
    }

    /**
     * font settings
     */
    $theme_main_font = (isset($bird_options['theme_main_font'])) ? $bird_options['theme_main_font'] : '';
    $h1_heading = (isset($bird_options['h1_heading'])) ? $bird_options['h1_heading'] : '';
    $h2_heading = (isset($bird_options['h2_heading'])) ? $bird_options['h2_heading'] : '';
    $h3_heading = (isset($bird_options['h3_heading'])) ? $bird_options['h3_heading'] : '';
    $h4_heading = (isset($bird_options['h4_heading'])) ? $bird_options['h4_heading'] : '';
    $h5_heading = (isset($bird_options['h5_heading'])) ? $bird_options['h5_heading'] : '';
    $h6_heading = (isset($bird_options['h6_heading'])) ? $bird_options['h6_heading'] : '';

    $body_font_face = (isset($theme_main_font['face'])) ? $theme_main_font['face'] : 'Open Sans';
    $menu_font_face = (isset($bird_options['bwp_menu_font']['face'])) ? $bird_options['bwp_menu_font']['face'] : 'Open Sans';
    $headings_font_face = (isset($bird_options['theme_headings_font']['face'])) ? $bird_options['theme_headings_font']['face'] : 'Raleway';
    $quote_font_face = (isset($bird_options['bwp_quote_font']['face'])) ? $bird_options['bwp_quote_font']['face'] : 'Lora';

    if ($theme_main_font) {
      if ($theme_main_font['style'] == 'light') { $theme_main_font_style = 'font-weight: 300; font-style: normal;'; } else
      if ($theme_main_font['style'] == 'light italic') { $theme_main_font_style = 'font-weight: 300; font-style: italic;'; } else
      if ($theme_main_font['style'] == 'normal') { $theme_main_font_style = 'font-weight: 400; font-style: normal;'; } else
      if ($theme_main_font['style'] == 'italic') { $theme_main_font_style = 'font-weight: 400; font-style: italic;'; } else
      if ($theme_main_font['style'] == 'semi bold') { $theme_main_font_style = 'font-weight: 600; font-style: normal;'; } else
      if ($theme_main_font['style'] == 'semi bold italic') { $theme_main_font_style = 'font-weight: 600; font-style: italic;'; } else
      if ($theme_main_font['style'] == 'bold') { $theme_main_font_style = 'font-weight: bold; font-style: normal;'; } else
      if ($theme_main_font['style'] == 'bold italic') { $theme_main_font_style = 'font-weight: bold; font-style: italic;'; }
      $inline_styles .= '
      body { font-size: '.$theme_main_font['size'].'; color: '.$theme_main_font['color'].'; '.$theme_main_font_style.' }';
    }
    if ($body_font_face) {
      $inline_styles .= '
      body,
      h1, h2, h3, h4, h5, h6,
      .h1, .h2, .h3, .h4, .h5, .h6,
      #main-slider .main-slider-caption p,
      .meta,
      .post-content,
      .content-none p,
      .pagination li a,
      .standard-wp-pagination a,
      .content,
      ul.single-meta-inf li,
      .post-comments-wrap,
      .category-description,
      .archive-section-title a,
      .content-none-404 p,
      .widget,
      .bird-widget-content,
      .tooltip-inner,
      .copyright-inf,
      .about-author-desc-wrap h3,
      .bsc-accordion-title a,
      #searchform .search-field {
        font-family: '.$body_font_face.', Arial, Helvetica, sans-serif;
      }';
    }
    if ($menu_font_face) {
      $inline_styles .= '
      ul.sf-menu a,
      .dropdown-search #searchform .search-field,
      ul.responsive-nav li a {
        font-family: '.$menu_font_face.', Arial, Helvetica, sans-serif;
      }';
    }
    if ($headings_font_face) {
      $inline_styles .= '
      #main-slider .main-slider-caption h1,
      #main-slider .mS-rwd-caption h1,
      .post-title,
      .content-none h1,
      .static-page-title,
      .single-title,
      h3.comment-reply-title,
      h2.comments-title,
      #comment-nav-below h1,
      .page-title,
      .archives-content h3,
      .widget-title,
      .bird-widget-content h4 {
        font-family: '.$headings_font_face.', Arial, Helvetica, sans-serif;
      }';
    }
    if ($quote_font_face) {
      $inline_styles .= '
      .quote-content,
      blockquote,
      blockquote p {
        font-family: '.$quote_font_face.', serif;
      }';
    }
    if ($h1_heading) {
      if ($h1_heading['style'] == 'light') { $h1_heading_style = 'font-weight: 300; font-style: normal;'; } else
      if ($h1_heading['style'] == 'light italic') { $h1_heading_style = 'font-weight: 300; font-style: italic;'; } else
      if ($h1_heading['style'] == 'normal') { $h1_heading_style = 'font-weight: 400; font-style: normal;'; } else
      if ($h1_heading['style'] == 'italic') { $h1_heading_style = 'font-weight: 400; font-style: italic;'; } else
      if ($h1_heading['style'] == 'semi bold') { $h1_heading_style = 'font-weight: 600; font-style: normal;'; } else
      if ($h1_heading['style'] == 'semi bold italic') { $h1_heading_style = 'font-weight: 600; font-style: italic;'; } else
      if ($h1_heading['style'] == 'bold') { $h1_heading_style = 'font-weight: bold; font-style: normal;'; } else
      if ($h1_heading['style'] == 'bold italic') { $h1_heading_style = 'font-weight: bold; font-style: italic;'; }
      $inline_styles .= '
      h1, .h1 { font-size: '.$h1_heading['size'].'; color: '.$h1_heading['color'].'; '.$h1_heading_style.' }';
    }
    if ($h2_heading) {
      if ($h2_heading['style'] == 'light') { $h2_heading_style = 'font-weight: 300; font-style: normal;'; } else
      if ($h2_heading['style'] == 'light italic') { $h2_heading_style = 'font-weight: 300; font-style: italic;'; } else
      if ($h2_heading['style'] == 'normal') { $h2_heading_style = 'font-weight: 400; font-style: normal;'; } else
      if ($h2_heading['style'] == 'italic') { $h2_heading_style = 'font-weight: 400; font-style: italic;'; } else
      if ($h2_heading['style'] == 'semi bold') { $h2_heading_style = 'font-weight: 600; font-style: normal;'; } else
      if ($h2_heading['style'] == 'semi bold italic') { $h2_heading_style = 'font-weight: 600; font-style: italic;'; } else
      if ($h2_heading['style'] == 'bold') { $h2_heading_style = 'font-weight: bold; font-style: normal;'; } else
      if ($h2_heading['style'] == 'bold italic') { $h2_heading_style = 'font-weight: bold; font-style: italic;'; }
      $inline_styles .= '
      h2, .h2 { font-size: '.$h2_heading['size'].'; color: '.$h2_heading['color'].'; '.$h2_heading_style.' }';
    }
    if ($h3_heading) {
      if ($h3_heading['style'] == 'light') { $h3_heading_style = 'font-weight: 300; font-style: normal;'; } else
      if ($h3_heading['style'] == 'light italic') { $h3_heading_style = 'font-weight: 300; font-style: italic;'; } else
      if ($h3_heading['style'] == 'normal') { $h3_heading_style = 'font-weight: 400; font-style: normal;'; } else
      if ($h3_heading['style'] == 'italic') { $h3_heading_style = 'font-weight: 400; font-style: italic;'; } else
      if ($h3_heading['style'] == 'semi bold') { $h3_heading_style = 'font-weight: 600; font-style: normal;'; } else
      if ($h3_heading['style'] == 'semi bold italic') { $h3_heading_style = 'font-weight: 600; font-style: italic;'; } else
      if ($h3_heading['style'] == 'bold') { $h3_heading_style = 'font-weight: bold; font-style: normal;'; } else
      if ($h3_heading['style'] == 'bold italic') { $h3_heading_style = 'font-weight: bold; font-style: italic;'; }
      $inline_styles .= '
      h3, .h3 { font-size: '.$h3_heading['size'].'; color: '.$h3_heading['color'].'; '.$h3_heading_style.' }';
    }
    if ($h4_heading) {
      if ($h4_heading['style'] == 'light') { $h4_heading_style = 'font-weight: 300; font-style: normal;'; } else
      if ($h4_heading['style'] == 'light italic') { $h4_heading_style = 'font-weight: 300; font-style: italic;'; } else
      if ($h4_heading['style'] == 'normal') { $h4_heading_style = 'font-weight: 400; font-style: normal;'; } else
      if ($h4_heading['style'] == 'italic') { $h4_heading_style = 'font-weight: 400; font-style: italic;'; } else
      if ($h4_heading['style'] == 'semi bold') { $h4_heading_style = 'font-weight: 600; font-style: normal;'; } else
      if ($h4_heading['style'] == 'semi bold italic') { $h4_heading_style = 'font-weight: 600; font-style: italic;'; } else
      if ($h4_heading['style'] == 'bold') { $h4_heading_style = 'font-weight: bold; font-style: normal;'; } else
      if ($h4_heading['style'] == 'bold italic') { $h4_heading_style = 'font-weight: bold; font-style: italic;'; }
      $inline_styles .= '
      h4, .h4 { font-size: '.$h4_heading['size'].'; color: '.$h4_heading['color'].'; '.$h4_heading_style.' }';
    }
    if ($h5_heading) {
      if ($h5_heading['style'] == 'light') { $h5_heading_style = 'font-weight: 300; font-style: normal;'; } else
      if ($h5_heading['style'] == 'light italic') { $h5_heading_style = 'font-weight: 300; font-style: italic;'; } else
      if ($h5_heading['style'] == 'normal') { $h5_heading_style = 'font-weight: 400; font-style: normal;'; } else
      if ($h5_heading['style'] == 'italic') { $h5_heading_style = 'font-weight: 400; font-style: italic;'; } else
      if ($h5_heading['style'] == 'semi bold') { $h5_heading_style = 'font-weight: 600; font-style: normal;'; } else
      if ($h5_heading['style'] == 'semi bold italic') { $h5_heading_style = 'font-weight: 600; font-style: italic;'; } else
      if ($h5_heading['style'] == 'bold') { $h5_heading_style = 'font-weight: bold; font-style: normal;'; } else
      if ($h5_heading['style'] == 'bold italic') { $h5_heading_style = 'font-weight: bold; font-style: italic;'; }
      $inline_styles .= '
      h5, .h5 { font-size: '.$h5_heading['size'].'; color: '.$h5_heading['color'].'; '.$h5_heading_style.' }';
    }
    if ($h6_heading) {
      if ($h6_heading['style'] == 'light') { $h6_heading_style = 'font-weight: 300; font-style: normal;'; } else
      if ($h6_heading['style'] == 'light italic') { $h6_heading_style = 'font-weight: 300; font-style: italic;'; } else
      if ($h6_heading['style'] == 'normal') { $h6_heading_style = 'font-weight: 400; font-style: normal;'; } else
      if ($h6_heading['style'] == 'italic') { $h6_heading_style = 'font-weight: 400; font-style: italic;'; } else
      if ($h6_heading['style'] == 'semi bold') { $h6_heading_style = 'font-weight: 600; font-style: normal;'; } else
      if ($h6_heading['style'] == 'semi bold italic') { $h6_heading_style = 'font-weight: 600; font-style: italic;'; } else
      if ($h6_heading['style'] == 'bold') { $h6_heading_style = 'font-weight: bold; font-style: normal;'; } else
      if ($h6_heading['style'] == 'bold italic') { $h6_heading_style = 'font-weight: bold; font-style: italic;'; }
      $inline_styles .= '
      h6, .h6 { font-size: '.$h6_heading['size'].'; color: '.$h6_heading['color'].'; '.$h6_heading_style.' }';
    }

    /**
     * blog styles
     */
    $blog_post_title_color = (isset($bird_options['blog_post_title_color'])) ? stripslashes($bird_options['blog_post_title_color']) : '';
    $blog_post_title_hover_color = (isset($bird_options['blog_post_title_hover_color'])) ? stripslashes($bird_options['blog_post_title_hover_color']) : '';

    if ($blog_post_title_color) {
      $inline_styles .= '
      .post-title a {
        color: '.$blog_post_title_color.';
      }';
    }
    if ($blog_post_title_hover_color) {
      $inline_styles .= '
      .post-title a:hover {
        color: '.$blog_post_title_hover_color.';
      }';
    }

    /**
     * pages style
     */
    $page_title_color = (isset($bird_options['static_page_title_color'])) ? stripslashes($bird_options['static_page_title_color']) : '';
    $page_desc_color = (isset($bird_options['category_page_desc_color'])) ? stripslashes($bird_options['category_page_desc_color']) : '';
    $single_postnav_color = (isset($bird_options['single_postnav_color'])) ? stripslashes($bird_options['single_postnav_color']) : '';
    $single_postnav_hover_color = (isset($bird_options['single_postnav_hover_color'])) ? stripslashes($bird_options['single_postnav_hover_color']) : '';
    $single_main_title_color = (isset($bird_options['single_main_title_color'])) ? stripslashes($bird_options['single_main_title_color']) : '';

    if ($page_title_color) {
      $inline_styles .= '
      .page-title,
      .comments-title,
      .nocomments,
      #comment-nav-below h1 {
        color: '.$page_title_color.' !important;
      }';
    }
    if ($page_desc_color) {
      $inline_styles .= '
      .category-description {
        color: '.$page_desc_color.' !important;
      }';
    }
    if ($single_postnav_color) {
      $inline_styles .= '
      .prev-next-posts-nav a,
      ol.commentlist .pingback,
      ol.commentlist .pingback a,
      #comment-nav-below a {
        color: '.$single_postnav_color.';
      }';
    }
    if ($single_postnav_hover_color) {
      $inline_styles .= '
      .prev-next-posts-nav a:hover,
      ol.commentlist .pingback a:hover,
      #comment-nav-below a:hover {
        color: '.$single_postnav_hover_color.';
      }';
    }
    if ($single_main_title_color) {
      $inline_styles .= '
      .single-title {
        color: '.$single_main_title_color.' !important;
      }';
    }

    /**
     * footer style
     */
    $footer_bg_color = (isset($bird_options['footer_bg_color'])) ? stripslashes($bird_options['footer_bg_color']) : '';
    $footer_headings_color = (isset($bird_options['footer_headings_color'])) ? stripslashes($bird_options['footer_headings_color']) : '';
    $footer_text_color = (isset($bird_options['footer_text_color'])) ? stripslashes($bird_options['footer_text_color']) : '';
    $footer_text_hover_color = (isset($bird_options['footer_text_hover_color'])) ? stripslashes($bird_options['footer_text_hover_color']) : '';
    $footer_widgets_form_bg = (isset($bird_options['bwp_footer_widgets_form_bg'])) ? stripslashes($bird_options['bwp_footer_widgets_form_bg']) : '';
    $footer_widgets_form_color = (isset($bird_options['bwp_footer_widgets_form_color'])) ? stripslashes($bird_options['bwp_footer_widgets_form_color']) : '';
    $copyright_font = (isset($bird_options['copyright_font'])) ? $bird_options['copyright_font'] : '';

    if ($footer_bg_color) {
      $inline_styles .= '
      #footer-1,
      #footer-2 {
        background-color: '.$footer_bg_color.';
      }
      .bird-widget-thumb-wrap a {
        background-color: '.$footer_bg_color.';
      }';
    }
    if ($footer_headings_color) {
      $inline_styles .= '
      #footer-1 .widget-title,
      #footer-1 .widget-title a,
      #footer-1 .bird-widget-content h4,
      #footer-1 .bird-widget-content h4 a {
        color: '.$footer_headings_color.';
      }';
    }
    if ($footer_text_color) {
      $inline_styles .= '
      #footer-1,
      #footer-1 ul li,
      #footer-1 ul li a,
      #footer-1 p,
      #footer-1 #wp-calendar,
      #footer-1 #wp-calendar caption,
      #footer-1 #wp-calendar th,
      #footer-1 #wp-calendar tbody td,
      #footer-1 #wp-calendar a,
      #footer-1 #wp-calendar #next a,
      #footer-1 #wp-calendar #prev a,
      #footer-1 .widget_rss ul li .rss-date,
      #footer-1 .widget_rss ul li cite,
      #footer-1 .bird-widget-content,
      #footer-1 .bird-widget-content p,
      #footer-1 ul.bird-widget-meta li,
      #footer-1 ul.bird-widget-meta li a,
      .copyright-inf,
      .copyright-inf a,
      .footer-social-icons li a,
      #footer-1 .jm-post-like a .like.pastliked,
      #footer-1 .jm-post-like a .count.liked,
      #footer-1 .jm-post-like a .like.disliked,
      #footer-1 .jm-post-like a .count.disliked,
      #footer-1 .jm-post-like a .like.prevliked,
      #footer-1 .jm-post-like a .count.alreadyliked,
      #footer-1 .widget_tag_cloud a,
      #footer-1 #mini-contact-submit {
        color: '.$footer_text_color.';
      }';
    }
    if ($footer_text_hover_color) {
      $inline_styles .= '
      #footer-1 a:hover,
      #footer-1 ul li a:hover,
      #footer-1 #wp-calendar a:hover,
      #footer-1 #wp-calendar #next a:hover,
      #footer-1 #wp-calendar #prev a:hover,
      #footer-1 .widget-title a:hover,
      .copyright-inf a:hover,
      .footer-social-icons li a:hover,
      #footer-1 .jm-post-like a:hover .like.pastliked,
      #footer-1 .jm-post-like a:hover .count.liked,
      #footer-1 .jm-post-like a:hover .like.disliked,
      #footer-1 .jm-post-like a:hover .count.disliked,
      #footer-1 .jm-post-like a:hover .like.prevliked,
      #footer-1 .jm-post-like a:hover .count.alreadyliked,
      #footer-1 .bird-widget-content h4 a:hover {
        color: '.$footer_text_hover_color.';
      }
      #footer-1 .widget_tag_cloud a:hover,
      #footer-1 #mini-contact-submit:hover {
        color: '.$footer_text_hover_color.';
        border-color: '.$footer_text_hover_color.';
      }
      #footer-1 #searchform .search-field:active,
      #footer-1 #searchform .search-field:focus,
      #footer-1 .mini-contact-form td.input-field input:active,
      #footer-1 .mini-contact-form td.input-field input:focus,
      #footer-1 .mini-contact-form td.input-field textarea:active,
      #footer-1 .mini-contact-form td.input-field textarea:focus {
        border-color: '.$footer_text_hover_color.';
      }';
    }
    if ($footer_widgets_form_bg) {
      $inline_styles .= '
      #footer-1 select,
      #footer-1 #searchform .search-field,
      #footer-1 .mini-contact-form td.input-field input,
      #footer-1 .mini-contact-form td.input-field textarea {
        background: '.$footer_widgets_form_bg.';
      }';
    }
    if ($footer_widgets_form_color) {
      $inline_styles .= '
      #footer-1 select,
      #footer-1 #searchform .search-field,
      #footer-1 .mini-contact-form td.input-field input,
      #footer-1 .mini-contact-form td.input-field textarea {
        color: '.$footer_widgets_form_color.';
      }
      #footer-1 #searchform .search-field::-webkit-input-placeholder {
        color: '.$footer_widgets_form_color.';
      }
      #footer-1 #searchform .search-field:-moz-placeholder {
        color: '.$footer_widgets_form_color.';
      }';
    }
    if ($copyright_font) {
      if ($copyright_font['style'] == 'light') { $copyright_font_style = 'font-weight: 300; font-style: normal;'; } else
      if ($copyright_font['style'] == 'light italic') { $copyright_font_style = 'font-weight: 300; font-style: italic;'; } else
      if ($copyright_font['style'] == 'normal') { $copyright_font_style = 'font-weight: 400; font-style: normal;'; } else
      if ($copyright_font['style'] == 'italic') { $copyright_font_style = 'font-weight: 400; font-style: italic;'; } else
      if ($copyright_font['style'] == 'semi bold') { $copyright_font_style = 'font-weight: 600; font-style: normal;'; } else
      if ($copyright_font['style'] == 'semi bold italic') { $copyright_font_style = 'font-weight: 600; font-style: italic;'; } else
      if ($copyright_font['style'] == 'bold') { $copyright_font_style = 'font-weight: bold; font-style: normal;'; } else
      if ($copyright_font['style'] == 'bold italic') { $copyright_font_style = 'font-weight: bold; font-style: italic;'; }
      $inline_styles .= '
      .copyright-inf { font-size: '.$copyright_font['size'].'; '.$copyright_font_style.' }';
    }

    // add inline styles (after the main style (style.css))
    wp_add_inline_style('main-style', $inline_styles);

    // add custom CSS code
    $custom_css_code = (isset($bird_options['custom_css_code'])) ? stripslashes($bird_options['custom_css_code']) : '';
    if ($custom_css_code) {
      wp_add_inline_style('main-style', $custom_css_code);
    }

  }
}
add_action('wp_enqueue_scripts', 'bird_options_custom_style');
