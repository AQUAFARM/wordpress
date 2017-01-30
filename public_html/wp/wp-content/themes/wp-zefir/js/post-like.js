jQuery.noConflict()(function($) {
  $(document).ready(function() {

    "use strict";
    
    $(".jm-post-like a").live('click', function() {
      var
        heart = $(this),
        post_id = heart.data("post_id");

      $.ajax({
        type: "POST",
        url: ajax_var.url,
        data: "action=jm-post-like&nonce="+ajax_var.nonce+"&jm_post_like=&post_id="+post_id,
        success: function(count) {
          if( count.indexOf( "already" ) !== -1 ) {
            var lecount = count.replace("already","");
            if (lecount == 0) {
              var lecount = "0";
            }
            heart.children(".like").removeClass("pastliked prevliked").addClass("disliked").html("<i class=\"fa fa-heart-o\"></i>");
            heart.children(".count").removeClass("liked alreadyliked").addClass("disliked").text(lecount);
          } else {
            heart.children(".like").addClass("pastliked").removeClass("disliked").html("<i class=\"fa fa-heart-o\"></i>");
            heart.children(".count").addClass("liked").removeClass("disliked").text(count);
          }
        }
      });

      return false;
    })
	
  });
});	
