/**
 * Show/Hide meta box
 */
jQuery.noConflict()(function($) {
  $(document).ready(function() {
    "use strict";

    function showMetaBox() {

      var
        $galleryBox = $('#bird_mb_gallery_post_format'),
        $videoBox = $('#bird_mb_video_post_format'),
        $quoteBox = $('#bird_mb_quote_post_format');

      // Gallery format box
      if ($('input#post-format-gallery').is(':checked')) {
        $galleryBox.show();
      } else {
        $galleryBox.hide();
      }

      // Video format box
      if ($('input#post-format-video').is(':checked')) {
        $videoBox.show();
      } else {
        $videoBox.hide();
      }

      // Quote format box
      if ($('input#post-format-quote').is(':checked')) {
        $quoteBox.show();
      } else {
        $quoteBox.hide();
      }

    }

    showMetaBox();

    $('#post-formats-select input').on('click', function() {
      showMetaBox();
    });

  });
});
