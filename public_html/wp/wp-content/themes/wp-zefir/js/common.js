jQuery.noConflict()(function($) {
  $(document).ready(function() {
    "use strict";

    /**
     * Tooltip
     * ----------------------------------------------------
     */
    $('.footer-social-link, .thumb-post-title, .author-social-link').tooltip();

    /**
     * Superfish menu
     * ----------------------------------------------------
     */
    $('ul.sf-menu').superfish({
      delay:400
    });

    /**
     * Dropdown search form
     * ----------------------------------------------------
     */
    var
      $searchIcon = $('.search-icon'),
      $dropSearchBox = $('.dropdown-search');

    $searchIcon.click(function() {
      if ($dropSearchBox.hasClass('search-hidden')) {
        $dropSearchBox.removeClass('search-hidden').fadeIn('fast').addClass('search-visible');
        $searchIcon.addClass('active-icon');
      } else {
        $dropSearchBox.removeClass('search-visible').fadeOut('fast').addClass('search-hidden');
        $searchIcon.removeClass('active-icon');
      }
      return false;
    });

    /**
     * To top button
     * ----------------------------------------------------
     */
    var $scrollTopBtn = $('<a rel="nofollow" href="#" id="scroll-top"><i class="fa fa-angle-up"></i></a>').appendTo('body');

    $scrollTopBtn.click(function() {
      $('html:not(:animated),body:not(:animated)').animate({scrollTop: 0}, 300);
      return false;
    });

    $(window).scroll(function() {
      if ($(window).scrollTop() > 100) {
        $scrollTopBtn.show();
      }	else {
        $scrollTopBtn.fadeOut(200);
      }
    });

    $scrollTopBtn.hide(); // first hide to top button

    /**
     * Social share button
     * ----------------------------------------------------
     */
    $('.share-icon > a, .single-share-icon > a').live('click', function() {
      var
        shareBlockId = $(this).data('share_id');

      if ($('#share-block-' + shareBlockId).hasClass('share-block-hidden')) {
        $('#share-block-' + shareBlockId).removeClass('share-block-hidden').fadeIn('fast');
        $(this).addClass('share-icon-active');
      } else {
        $(this).removeClass('share-icon-active');
        $('#share-block-' + shareBlockId).addClass('share-block-hidden').fadeOut('fast');
      }

      return false;
    });

    /**
     * owlCarousel - gallery post format
     * ----------------------------------------------------
     */
    $(".blog-post-carousel").owlCarousel({
      navigation : true,
      navigationText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
      slideSpeed : 300,
      pagination: false,
      singleItem: true,
      autoPlay: true,
      stopOnHover: true,
      afterInit: function() {
        $('.blog-container').masonry();
      },
      afterUpdate: function() {
        $('.blog-container').masonry();
      }
    });

    /**
     * prettyPhoto
     * ----------------------------------------------------
     */
    var $prettyPhotoImg = $("a[rel^='prettyPhoto']");
    $prettyPhotoImg.prettyPhoto({
      animation_speed: 'fast',
      slideshow: 5000,
      opacity: 0.6,
      autoplay_slideshow: false,
      show_title: false,
      allow_resize: true,
      theme: 'pp_default',
      social_tools: false
    });


    /**
     * Remove 'bwp-sticky-post' class
     * ----------------------------------------------------
     */
    var stickyPost = $('.sticky');
    if (stickyPost.length) {
      if (stickyPost.hasClass('bwp-sticky-post')) {
        stickyPost.removeClass('bwp-sticky-post');
      }
    }

  });
});
