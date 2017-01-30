<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <?php global $bird_options; ?>
  <?php $site_favicon = (isset($bird_options['site_favicon'])) ? stripslashes($bird_options['site_favicon']) : get_template_directory_uri().'/img/favicon.png'; ?>
  <link rel="shortcut icon" href="<?php echo esc_url($site_favicon); ?>">
  <link rel="profile" href="http://gmpg.org/xfn/11" />
  <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<!-- main navigation -->
<header id="main-navigation-wrap">
  <div class="navbar main-navigation" role="navigation">
    <div class="container">

      <!-- logo + collapse button -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle main-nav-collapse-btn" data-toggle="collapse" data-target="#main-navigation-collapse">
          <i class="fa fa-bars"></i>
        </button>
        <?php
        $logo_type = (isset($bird_options['logo_type'])) ? stripslashes($bird_options['logo_type']) : 'text';

        if ($logo_type == 'text') {
          $logo_text = (isset($bird_options['logo_text'])) ? stripslashes($bird_options['logo_text']) : 'Zefir';
          echo '<h1 class="logo"><a href="'.esc_url(home_url('/')).'" rel="home">'.esc_html($logo_text).'</a></h1>';
        } else if ($logo_type == 'image') {
          $logo_image = (isset($bird_options['logo_image'])) ? stripslashes($bird_options['logo_image']) : get_template_directory_uri().'/img/zefir-logo.png';
          $retina_logo_image = (isset($bird_options['retina_logo_image'])) ? stripslashes($bird_options['retina_logo_image']) : get_template_directory_uri().'/img/zefir-logo@2x.png';
          echo '<a href="'.esc_url(home_url('/')).'" class="logo-img" rel="home"><img src="'.esc_url($logo_image).'" data-at2x="'.esc_url($retina_logo_image).'" alt="logo"></a>';
        }
        ?>
      </div>
      <!-- end logo + collapse button -->

      <!-- menu and search form -->
      <div id="main-navigation-collapse" class="collapse navbar-collapse">

        <?php
        if (has_nav_menu('main_menu')) {
          // responsive menu
          wp_nav_menu(array(
            'theme_location' => 'main_menu',
            'container' => 'nav',
            'menu_class' => 'nav navbar-nav responsive-nav hidden-md hidden-lg'
          ));

          // superfish menu
          wp_nav_menu(array(
            'theme_location' => 'main_menu',
            'container' => 'nav',
            'menu_class' => 'sf-menu hidden-sm hidden-xs'
          ));
        }
        ?>

        <?php
        $show_search_icon = (isset($bird_options['show_header_search'])) ? stripslashes($bird_options['show_header_search']) : true;
        if ($show_search_icon) { ?>
          <!-- start dropdown search -->
          <div class="drop-search-wrap navbar-right">
            <a href="#" class="search-icon"><i class="fa fa-search"></i></a>
            <div class="dropdown-search search-hidden">
              <form id="searchform" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                <input type="text" name="s" id="s" class="search-field form-control" placeholder="<?php esc_html_e('Search', 'birdwp-theme'); ?>">
              </form>
            </div>
          </div>
          <!-- end dropdown search -->
        <?php } ?>

      </div>
      <!-- end menu + search form -->

    </div>
  </div>
</header>
<!-- end main navigation -->
