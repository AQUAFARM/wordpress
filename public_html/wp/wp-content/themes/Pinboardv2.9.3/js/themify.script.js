;// Themify Theme Scripts - http://themify.me/

// Initialize object literals
var FixedHeader = {};

(function($){

	// throttledresize
!function($){var e=$.event,t,n={_:0},r=0,s,i;t=e.special.throttledresize={setup:function(){$(this).on("resize",t.handler)},teardown:function(){$(this).off("resize",t.handler)},handler:function(h,o){var a=this,l=arguments;s=!0,i||(setInterval(function(){r++,(r>t.threshold&&s||o)&&(h.type="throttledresize",e.dispatch.apply(a,l),s=!1,r=0),r>9&&($(n).stop(),i=!1,r=0)},30),i=!0)},threshold:0}}(jQuery);

//////////////////////////////
// Test if touch event exists
//////////////////////////////
function is_touch_device() {
	return $('body').hasClass('touch');
}

/////////////////////////////////////////////
// Page Width Calculation
/////////////////////////////////////////////
var ItemBoard = {
	init : function(config) {
		this.config = config;
		this.bindEvents();
	},
	columns : 0,
	itemMargin : 20,
	itemPadding : 0,
	sidebarGap : 30,
	bindEvents : function() {
		var _self = this;
		jQuery(document).ready(function() {
			_self.elementSetup()
		});
		jQuery(window).resize(function() {
			_self.elementSetup()
		});
	},

	elementSetup : function() {
		var viewport_width = this.viewportWidth(),
                    fixwidth,
                    maxWidth,
                    content_w;

		this.itemWidthOuter = this.itemWidthInner() + this.itemMargin + this.itemPadding;
		this.columns = parseInt(viewport_width / this.itemWidthOuter);

		fixwidth = this.columns * this.itemWidthInner() + ((this.columns - 1) * this.itemMargin);
		maxWidth = '100%';

		// check if there sidebar
		if(jQuery('#sidebar').length > 0){
			content_w = fixwidth - jQuery('#sidebar').width() + 15;

			jQuery('#content').width(content_w);
			fixwidth = fixwidth + this.sidebarGap;

			if(viewport_width < 965){
				jQuery('#content').css({width : ''}); // reset width inline
				fixwidth = this.columns * this.itemWidthInner() + ((this.columns - 1) * this.itemMargin); // reset page width
			}
		}

		// make exception for width smaller than 480px then dont apply the inline width
		// assume 480 = 1 column item and apply to only viewport <= 505
		if ((this.columns <= 1 && viewport_width <= 505) || (jQuery('.list-post').length > 0)) {
			fixwidth = 978;
			maxWidth = '94%';
		}

		jQuery(this.config.appliedTo).each(function() {
			jQuery(this).css({
				'width' : fixwidth + 'px',
				'max-width' : maxWidth
			});
		});
		// Make header fluid fullwidth
		jQuery("#header").css({
			'width' : 'auto',
			'max-width' : 'none'
		});
	},

	itemWidthInner : function() {
		return jQuery(this.config.itemElement).width();
	},

	viewportWidth : function() {
		return jQuery(window).width();
	}
};

$.fn.bindWithDelay = function( type, data, fn, timeout, throttle ) {

	if ( $.isFunction( data ) ) {
		throttle = timeout;
		timeout = fn;
		fn = data;
		data = undefined;
	}

	// Allow delayed function to be removed with fn in unbind function
	fn.guid = fn.guid || ($.guid && $.guid++);

	// Bind each separately so that each element has its own delay
	return this.each(function() {

        var wait = null;

        function cb() {
            var e = $.extend(true, { }, arguments[0]);
            var ctx = this;
            var throttler = function() {
            	wait = null;
            	fn.apply(ctx, [e]);
            };

            if (!throttle) { clearTimeout(wait); wait = null; }
            if (!wait) { wait = setTimeout(throttler, timeout); }
        }

        cb.guid = fn.guid;

        $(this).bind(type, data, cb);
	});
};

//////////////////////////////
// Fixed Header
//////////////////////////////
FixedHeader = {
	init: function() {
		var cons = is_touch_device() ? 10 : 0;
		this.headerHeight = $('#headerwrap').height() - cons;
		this.descHeight = $('#site-description').outerHeight(true);
		$(window)
		.on('scroll', this.activate)
		.on('touchstart.touchScroll', this.activate)
		.on('touchmove.touchScroll', this.activate);
	},

	activate: function() {
		var $window = $(window),
			scrollTop = $window.scrollTop();

		if( scrollTop > FixedHeader.headerHeight ) {
			FixedHeader.scrollEnabled();
		} else {
			FixedHeader.scrollDisabled();
		}
	},

	scrollDisabled: function() {
		$('#headerwrap').removeClass('fixed-header');
		$('#header').removeClass('header-on-scroll');
		$('#pagewrap').css('padding-top', '');
		$('body').removeClass('fixed-header-on');
	},

	scrollEnabled: function() {
		$('#headerwrap').addClass('fixed-header');
		$('#header').addClass('header-on-scroll');
		$('#pagewrap').css('padding-top', FixedHeader.descHeight);
		$('body').addClass('fixed-header-on');
	}
};

$(document).ready(function() {

	var isTyping = null,
		delayCheck = 500;

	if ( is_touch_device() ) {
		$('body').addClass('is-touch');
	}

        /////////////////////////////////////////////
	// Initialize lightbox
	/////////////////////////////////////////////
	var extraLightboxArgs = {};
	if(!jQuery('body').hasClass('post-lightbox-iframe')){
            extraLightboxArgs = {'displayIframeContentsInParent': false};
	} else {
            extraLightboxArgs = {'displayIframeContentsInParent': true};
            // redirect to corresponding page
           jQuery('.post').contents().find("a:not([class='comment-reply-link'], [id='cancel-comment-reply-link'], .themify_lightbox, .post-content a[href$='jpg'], .post-content a[href$='gif'], .post-content a[href$='png'], .post-content a[href$='JPG'], .post-content a[href$='GIF'], .post-content a[href$='PNG'], .post-content a[target='_new'], .post-content a[target='_blank'])").click(function(){
                  var href = jQuery(this).attr('href');
                  window.parent.location.replace(href);
                  return false;
           });
	}
        themifyScript.extraLightboxArgs = extraLightboxArgs;

	// Fixed header
	if ( themifyScript.fixedHeader ) {
		FixedHeader.init();
	}

	// Check Site Path
	$('#blogname, #blog_title').on('keyup', function(){
		if( '' == $(this).val()) return;
		$sitenamecheck = $('#sitename_check');
		$sitetitlecheck = $('#sitetitle_check');
		$('#sitename_check, #sitetitle_check, .signup-messages').text('');

		clearTimeout(isTyping);
		isTyping = setTimeout(
			function(){
				clearTimeout(isTyping);
				if( '' != $('#blogname').val() ){

					$sitenamecheck.html(themifyScript.checking);

					$.post(themifyScript.ajax_url,
					{
						action: 'themify_check_site',
						nonce: themifyScript.ajax_nonce,
						blogname: $('#blogname').val(),
						blog_title: $('#blog_title').val()
					}
					, function(response, statusText) {
						if( 'success' != statusText ){
							$('.signup-messages').text(themifyScript.networkError);
							return;
						}
						json = jQuery.parseJSON(response);
						$sitetitlecheck.html('');
						$sitenamecheck.html('');

			    		if(json.errors){
			    			if(json.errors.blogname)
				    			for (var error in json.errors.blogname) {
									$sitenamecheck.append('<p class="error-check error-site_name">' + json.errors.blogname[error] + '</p>');
								}
								for (var error in json.errors.blog_title) {
									$sitetitlecheck.append('<p class="error-check error-site_title">' + json.errors.blog_title[error] + '</p>');
								}
			    		}
					});
				}
			}
			, delayCheck);
	});
	// Check User Name + Email
	$('#user_name, #user_email').on('keyup', function(){
		if( '' == $(this).val()) return;
		$usercheck = $('#username_check');
		$emailcheck = $('#email_check');
		$('#username_check, #email_check, .signup-messages').text('');

		clearTimeout(isTyping);
		isTyping = setTimeout(
			function(){
				clearTimeout(isTyping);
				if( '' == $('#user_email').val() ){
					return;
				} else {
					var emailOkPattern = /^([\w-\+\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
					if(!emailOkPattern.test($('#user_email').val())){
						$emailcheck.html('<p class="error-check error-user_email">' + themifyScript.invalidEmail + '</p>');
						return;
					}
				}
				if( '' != $('#user_name').val() ) {
					$usercheck.html(themifyScript.checking);
					$emailcheck.html(themifyScript.checking);

					$.post(themifyScript.ajax_url,
					{
						action: 'themify_check_user',
						nonce: themifyScript.ajax_nonce,
						user_name: $('#user_name').val(),
						user_email: $('#user_email').val()
					}
					,
					function(response, statusText) {
						if( 'success' != statusText ) {
							$('.signup-messages').text(themifyScript.networkError);
							return;
						}
						json = jQuery.parseJSON(response);
						$usercheck.html('');
						$emailcheck.html('');

			    		if(json.errors){
			    			if(json.errors.user_name)
				    			for (var error in json.errors.user_name) {
									$usercheck.append('<p class="error-check error-user_name">' + json.errors.user_name[error] + '</p>');
								}
							if(json.errors.user_email)
				    			for (var error in json.errors.user_email) {
				    				$emailcheck.html('<p class="error-check error-user_email">' + json.errors.user_email[error] + '</p>');
								}
			    			if(json.errors.user_email_used)
				    			for (var error in json.errors.user_email_used) {
									$emailcheck.html('<p class="error-check error-user_email_used">' + json.errors.user_email_used[error] + '</p>');
								}
			    		}
					});
				}
			}
			, delayCheck);
	});
	// Send Form
	if( $('#themify_setupform').length > 0 ) {
		$('#themify_setupform').ajaxForm({
		    data: {
				action: 'themify_add_user_and_site',
				nonce: themifyScript.ajax_nonce,
				url: themifyScript.ajax_url,
				dataType: 'json'
		    },
		    beforeSubmit: function(formData, jqForm, options) {
		    	if( '' == $('#user_name').val()  ||
		    		'' == $('#user_email').val() ||
		    		'' == $('#blogname').val()   ||
		    		'' == $('#blog_title').val()){
		    		$('.signup-messages').text(themifyScript.fillfields);
		    		return false;
		    	}
		    },
		    success : function(responseText, statusText, xhr, $form) {
		    	$msg = $('.signup-messages');
		    	$msg.text('');
		    	if( 'success' != statusText ){
		    		$msg.text(themifyScript.networkError);
		    		return;
		    	}

		    	$sitenamecheck = $('#sitename_check');
				$sitenamecheck.text('');

				$sitetitlecheck = $('#sitetitle_check');
				$sitetitlecheck.text('');

		    	$usercheck = $('#username_check');
				$usercheck.text('');

				$emailcheck = $('#email_check');
				$emailcheck.text('');

		    	json = jQuery.parseJSON(responseText);

		    	$sitenamecheck.html('');
				$sitetitlecheck.html('');
		    	$usercheck.html('');
				$emailcheck.html('');

		    	if(json.errors) {
	    			if(json.errors.blogname)
		    			for (var error in json.errors.blogname) {
							$sitenamecheck.append('<p class="error-check error-site_name">' + json.errors.blogname[error] + '</p>');
						}
					if(json.errors.user_name)
		    			for (var error in json.errors.user_name) {
							$usercheck.append('<p class="error-check error-user_name">' + json.errors.user_name[error] + '</p>');
						}
					if(json.errors.user_email)
		    			for (var error in json.errors.user_email) {
							$emailcheck.html('<p class="error-check error-user_email">' + json.errors.user_email[error] + '</p>');
						}
	    			if(json.errors.user_email_used)
		    			for (var error in json.errors.user_email_used) {
							$emailcheck.html('<p class="error-check error-user_email_used">' + json.errors.user_email_used[error] + '</p>');
						}
	    		} else {
	    			$('#themify_setupform').slideUp();
	    			$msg.text(themifyScript.creationOk);
	    		}
		    }
		});
	}


	/////////////////////////////////////////////
	// Scroll to top
	/////////////////////////////////////////////
	$('.back-top a').click(function() {
		$('body,html').animate({
			scrollTop : 0
		}, 800);
		return false;
	});


	/////////////////////////////////////////////
	// Toggle menu on mobile
	/////////////////////////////////////////////
	$("#menu-icon").click(function() {
		$("#headerwrap #main-nav").fadeToggle();
		$("#headerwrap #searchform").hide();
		$(this).toggleClass("active");
	});

	/////////////////////////////////////////////
	// Toggle searchform on mobile
	/////////////////////////////////////////////
	$("#search-icon").click(function() {
		$("#headerwrap #searchform").fadeToggle();
		$("#headerwrap #main-nav").hide();
		$('#headerwrap #s').focus();
		$(this).toggleClass("active");
	});

	// auto width
	if( 'yes' == themifyScript.itemBoard && $('#loops-wrapper').length > 0 && $('body.grid4, body.grid3, body.grid2').length > 0 ){
		ItemBoard.init({
			itemElement : '.AutoWidthElement .post',
			appliedTo   : '.pagewidth'
		});
	}

        InitLightbox();
	// Re-initialize post in lightbox after scroll to load more posts
	$( document ).ajaxComplete(themifyMagnificPopup);

});
var InitLightbox = function(){
    // Initialize post in lightbox /////////////////////////////////////////////
    if($('.themify_lightbox_post').length>0){
        Themify.LoadCss(themify_vars.url + '/css/lightbox.css', null);
        Themify.LoadAsync(themify_vars.url + '/js/lightbox.js', function () {
           themifyMagnificPopup( $(".themify_lightbox_post") );
           
        }, null, null, function () {
                return ('undefined' !== typeof $.fn.magnificPopup);
        });
    }
};
// Process lightbox request
function themifyMagnificPopup( $themifyLightbox ){
	if( $themifyLightbox.length > 0 && (typeof($.fn.magnificPopup) !== 'undefined') ){
		$themifyLightbox.each(function(){
			if( $(this).attr('href').indexOf("iframe") == -1 )
				$(this).attr('href', $(this).attr('href') + '&iframe=true&width=700&height=100%');
			$(this).magnificPopup({
				type: 'iframe',
				iframe: {
					markup: '<div class="mfp-iframe-scaler" style="max-width: 700px !important; height: 100%;">'+
					'<div class="mfp-close"></div>'+
					'<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>'+
					'</div>'
				},
				mainClass: 'standard-frame'
			});
		});
	}
}

function isotopeBuilder($container) {
	var isoCols = parseInt($container.width() / $('.post', $container).width()),
	isoColW = $container.width() / isoCols;

	$('.post', $container).wrapAll('<div class="new-items" />');

	$container.isotope({
		masonry: {
			columnWidth: isoColW
		},
		itemSelector : '.post',
		transformsEnabled : false,
		resizable: false,
		animationEngine: 'jquery',
		isOriginLeft : ! $( 'body' ).hasClass( 'rtl' )
	});
	$(window).on( 'throttledresize', function(){
		isoCols = parseInt($container.width() / $('.post', $container).width());
		isoColW = parseInt($container.width() / isoCols);
		$container.isotope({
			masonry: {
				columnWidth: isoColW
			}
		}).isotope('layout');
	});
}

$(window).load(function() {

	$(document).on('click', '.likeit', function(e) {
		e.preventDefault();
		var $self = $(this),
			$parent = $self.parent(),
			post_id = $self.data('postid'),
			$container = jQuery('#loops-wrapper');

		$.post(
			themifyScript.ajax_url,
			{
				action: 'themify_likeit',
				nonce : themifyScript.ajax_nonce,
				post_id: post_id
			},
			function(response) {
				data = jQuery.parseJSON(response);
				if( 'new' == data.status ) {
					$('.newliker', $parent).fadeIn();
					$('ins', $self).fadeOut('slow', function(){
						$(this).text(data.likers).fadeIn('slow');
					});
					$container.isotope('layout');
				}
				if( 'isliker' == data.status ) {
					$('.newliker', $parent).hide();
					$('.isliker', $parent).fadeIn();
					$container.isotope('layout');
				}
			}
		);
	});

	var $container = jQuery('#loops-wrapper');

	// isotope init - main theme layout
	if (jQuery('.list-post').length == 0) {
		$container.isotope({
			itemSelector : '#loops-wrapper > .post, .new-items > .post',
			transformsEnabled : false,
			isOriginLeft : ! $( 'body' ).hasClass( 'rtl' )
		});
		jQuery(window).resize();
	}
	// isotope init - builder isotope layout
	var builderContainer = ['.themify_builder_content .loops-wrapper.grid4', '.themify_builder_content .loops-wrapper.grid3', '.themify_builder_content .loops-wrapper.grid2'];
	$.each(builderContainer, function(i,v){
		$(v).each(function(){
			isotopeBuilder($(this));
		});
	});

	// Get max pages for regular category pages and home
	var scrollMaxPages = themifyScript.maxPages;
	// Get max pages for Query Category pages
	if( typeof qp_max_pages != 'undefined')
		scrollMaxPages = qp_max_pages;

	// infinite scroll
	$container.infinitescroll({
		navSelector  : '#load-more a', 		// selector for the paged navigation
		nextSelector : '#load-more a', 		// selector for the NEXT link (to page 2)
		itemSelector : '#content .post', 	// selector for all items you'll retrieve
		loadingText  : '',
		donetext     : '',
		loading 	 : { img: themifyScript.loadingImg },
		maxPage      : scrollMaxPages,
		behavior	 : 'auto' != themifyScript.autoInfinite? 'twitter' : '',
		pathParse 	 : function (path, nextPage) {
			return path.match(/^(.*?)\b\d\b(?!.*\b2\b)(.*?$)/).slice(1);
		},
		state: {
			currPage : $( '#load-more' ).data( 'current-page' )
		}
	}, function(newElements, instance, url) {
		// call Isotope for new elements
		var $newElems = jQuery(newElements).wrap('<div class="new-items" />').hide();
		$newElems.imagesLoaded(function(){
			$newElems.fadeIn();
			$container.isotope('appended', $newElems );

			if( history.pushState ) {
				history.pushState(null, null, url);
			}

			jQuery('#infscr-loading').fadeOut('normal');
			if( 1 == scrollMaxPages ){
				jQuery('#load-more, #infscr-loading').remove();
			}

			var extraLightboxArgs = {};
			if(!jQuery('body').hasClass('post-lightbox-iframe')){
				extraLightboxArgs = {'displayIframeContentsInParent': false};
			} else {
			  extraLightboxArgs = {'displayIframeContentsInParent': true};
			  // redirect to corresponding page
			 jQuery('.post').contents().find("a:not([class='comment-reply-link'], [id='cancel-comment-reply-link'], .themify_lightbox, .post-content a[href$='jpg'], .post-content a[href$='gif'], .post-content a[href$='png'], .post-content a[href$='JPG'], .post-content a[href$='GIF'], .post-content a[href$='PNG'], .post-content a[target='_new'], .post-content a[target='_blank'])").click(function(){
				var href = jQuery(this).attr('href');
				window.parent.location.replace(href);
				return false;
			 });
			}
			// Apply lightbox/fullscreen gallery to new items
			Themify.InitGallery(false,extraLightboxArgs);
			// Initialize post in lightbox /////////////////////////////////////////////
			var $themifyLightbox = $(".themify-lightbox", $newElems);
			if( $themifyLightbox.length > 0 && (typeof($.fn.magnificPopup) !== 'undefined') ){
				$themifyLightbox.each(function(){
					$(this).attr('href', $(this).attr('href') + '&iframe=true&width=700&height=90%').magnificPopup({
						type: 'iframe',
						iframe: {
							markup: '<div class="mfp-iframe-scaler" style="max-width: 700px !important; height: 90%;">'+
							'<div class="mfp-close"></div>'+
							'<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>'+
							'</div>'
						},
						mainClass: 'standard-frame'
					});
				});
			}

			// Fix twitter widgets
			if ( $newElems.find('.twitter-tweet').length ) {
				Themify.LoadAsync("https://platform.twitter.com/widgets.js", function(){
					twttr.widgets.load( $newElems );
				}, null, null, function(){
					return ('undefined' !== typeof twttr);
				});
			}

			// Tell everyone we have new elements
			$(document).trigger('newElements');

			/**
			 * Fires event after the elements and its images are loaded.
			 *
			 * @event infiniteloaded.themify
			 * @param {object} $newElems The elements that were loaded.
			 */
			$('body').trigger( 'infiniteloaded.themify', [$newElems, $container] );
		});
		//$container.isotope('insert', jQuery(newElements)).delay(400).isotope('reLayout');
		scrollMaxPages = scrollMaxPages - 1;
		if( 1 < scrollMaxPages && 'auto' != themifyScript.autoInfinite)
			jQuery('#load-more a').show();
	});

	// disable auto infinite scroll based on user selection
	if( 'auto' == themifyScript.autoInfinite ){
		jQuery('#load-more, #load-more a').hide();
	}

	// Force relayout after images are loaded, useful for sliders
	$('.isotope').imagesLoaded(function(){
		$('.isotope' ).isotope( "reLayout" );
	});

});

}(jQuery));
