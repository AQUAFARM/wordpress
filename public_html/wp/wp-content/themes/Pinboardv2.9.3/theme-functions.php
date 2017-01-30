<?php


/**
 * Initialize multisite routines if it's a network installation.
 * @since 2.0.0
 */
if( is_multisite() ) require_once('multisite.php');

/*
To add custom PHP functions to the theme, create a new 'custom-functions.php' file in the theme folder.
They will be added to the theme automatically.
*/

/* 	Enqueue Stylesheets and Scripts
/***************************************************************************/
add_action( 'wp_enqueue_scripts', 'themify_theme_enqueue_scripts', 11 );
function themify_theme_enqueue_scripts(){
	global $wp_query;

	///////////////////
	//Enqueue styles
	///////////////////

	// Get theme version for Themify theme scripts and styles
	$theme_version = wp_get_theme()->display('Version');

	//Themify base styling
	wp_enqueue_style( 'theme-style', get_stylesheet_uri(), array(), $theme_version);

	//Themify Media Queries CSS
	wp_enqueue_style( 'themify-media-queries', THEME_URI . '/media-queries.css');


	//Google Web Fonts embedding
	wp_enqueue_style( 'google-fonts', themify_https_esc('http://fonts.googleapis.com/css'). '?family=Damion&subset=latin,latin-ext');

	// Themify Icons
	wp_enqueue_style( 'themify-icons', THEME_URI . '/themify/themify-icons/themify-icons.css', array(), $theme_version);

	///////////////////
	//Enqueue scripts
	///////////////////

	//isotope, used to re-arrange blocks
	wp_enqueue_script( 'themify-isotope', THEME_URI . '/js/jquery.isotope.min.js', array('jquery'), false, true );

	//creates infinite scroll
	wp_enqueue_script( 'infinitescroll', THEME_URI . '/js/jquery.infinitescroll.min.js', array('jquery'), false, true );

	//Themify internal scripts
	wp_enqueue_script( 'theme-script',	THEME_URI . '/js/themify.script.js', array( 'jquery', 'infinitescroll', 'themify-isotope' ), false, true );


	// Get auto infinite scroll setting
	$autoinfinite = '';
	if ( ! themify_get( 'setting-autoinfinite' ) ) {
		$autoinfinite = 'auto';
	}

	wp_localize_script( 'theme-script', 'themifyScript', apply_filters('themify_script_vars',
		array(
			'loadingImg'	=> THEME_URI . '/images/loading.gif',
			'maxPages'		=> $wp_query->max_num_pages,
			'autoInfinite'	=> $autoinfinite,
			'lightbox' 		=> themify_lightbox_vars_init(),
			'lightboxContext' => apply_filters('themify_lightbox_context', '#pagewrap'),
			'sharrrephp'	=> THEME_URI . '/includes/sharrre.php',
			'sharehtml'		=> apply_filters('themify_share_html', '<a class="box" href="#"><div class="share"><span>' . __('share', 'themify') . '</span></div><div class="count" href="#">{total}</div></a>'),
			'fixedHeader' 	=> themify_check('setting-fixed_header_disabled')? '': 'fixed-header',
			'ajax_nonce'	=> wp_create_nonce('ajax_nonce'),
			'ajax_url'		=> admin_url( 'admin-ajax.php' ),
			'itemBoard'		=> 'yes',

			'site_taken'	=> __('Bummer. That site name is taken. Please, try again.', 'themify'),
			'email_taken'	=> __('That email address is taken.', 'themify'),
			'user_taken'	=> __('The user name is taken.', 'themify'),

			'site_avail'	=> __('Success! The site name is free so grab it now!', 'themify'),
			'user_avail'	=> __('Success! The user name is available.', 'themify'),
			'email_avail'	=> __('Success! The email address is available.', 'themify'),

			'checking'		=> __('Checking...', 'themify'),
			'networkError'	=> __('Unknown network error. Please try again later.', 'themify'),
			'fillthisfield'	=> __('Please complete this field.', 'themify'),
			'fillfields'	=> __('Please complete all fields.', 'themify'),
			'invalidEmail'	=> __('Enter a valid email address.', 'themify'),
			'creationOk'	=> __('Your site was successfully created. Check your email for the activation mail.', 'themify')
		)
	));

	//WordPress internal script to move the comment box to the right place when replying to a user
	if ( is_single() || is_page() ) wp_enqueue_script( 'comment-reply' );

}

/**
 * Add viewport tag for responsive layouts
 * @package themify
 */
function themify_viewport_tag(){
	echo "\n".'<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">'."\n";
}
add_action( 'wp_head', 'themify_viewport_tag' );

/**
 * Set Default post layout and sidebar
 */
add_filter('themify_default_post_layout', create_function('$class', "return 'grid4';"));
add_filter('themify_default_layout', create_function('$class', "return (is_single() || is_page()) ? 'sidebar1' : 'sidebar-none';"));

/* Custom Write Panels
/***************************************************************************/

	///////////////////////////////////////
	// Setup Write Panel Options
	///////////////////////////////////////

	// Post Meta Box Options
	$post_meta_box_options = array(
	// Layout
	array(
		  "name" 		=> "layout",
		  "title" 		=> __('Sidebar Option', 'themify'),
		  "description" => "",
		  "type" 		=> "layout",
		'show_title' => true,
		  "meta"		=> array(
		  						array("value" => "default", "img" => "images/layout-icons/default.png", "selected" => true, 'title' => __('Default', 'themify')),
								array('value' => 'sidebar1', 'img' => 'images/layout-icons/sidebar1.png', 'title' => __('Sidebar Right', 'themify')),
								array('value' => 'sidebar1 sidebar-left', 'img' => 'images/layout-icons/sidebar1-left.png', 'title' => __('Sidebar Left', 'themify')),
								array('value' => 'sidebar-none', 'img' => 'images/layout-icons/sidebar-none.png', 'title' => __('No Sidebar ', 'themify'))
							)
		),
		// Content Width
		array(
			'name'=> 'content_width',
			'title' => __('Content Width', 'themify'),
			'description' => '',
			'type' => 'layout',
			'show_title' => true,
			'meta' => array(
				array(
					'value' => 'default_width',
					'img' => 'themify/img/default.png',
					'selected' => true,
					'title' => __( 'Default', 'themify' )
				),
				array(
					'value' => 'full_width',
					'img' => 'themify/img/fullwidth.png',
					'title' => __( 'Fullwidth', 'themify' )
				)
			)
		),
	// Post Image
	array(
		  "name" 		=> "post_image",
		  "title" 		=> __('Featured Image', 'themify'),
		  "description" => "",
		  "type" 		=> "image",
		  "meta"		=> array()
		),
   	// Featured Image Size
	array(
		'name'	=>	'feature_size',
		'title'	=>	__('Image Size', 'themify'),
		'description' => __('Image sizes can be set at <a href="options-media.php">Media Settings</a> and <a href="https://wordpress.org/plugins/regenerate-thumbnails/" target="_blank">Regenerated</a>', 'themify'),
		'type'		 =>	'featimgdropdown'
		),
	// Image Width
	array(
		  "name" 		=> "image_width",
		  "title" 		=> __('Image Width', 'themify'),
		  "description" => "",
		  "type" 		=> "textbox",
		  "meta"		=> array("size"=>"small")
		),
	// Image Height
	array(
		  "name" 		=> "image_height",
		  "title" 		=> __('Image Height', 'themify'),
		  "description" => __('Enter height = 0 to disable vertical cropping with img.php enabled', 'themify'),
		  "type" 		=> "textbox",
		  "meta"		=> array("size"=>"small")
		),
	// Hide Post Title
	array(
		  "name" 		=> "hide_post_title",
		  "title" 		=> __('Hide Post Title', 'themify'),
		  "description" => "",
		  "type" 		=> "dropdown",
		  "meta"		=> array(
		  						array("value" => "default", "name" => "", "selected" => true),
								array("value" => "yes", 'name' => __('Yes', 'themify')),
								array("value" => "no",	'name' => __('No', 'themify'))
							)
		),
	// Unlink Post Title
	array(
		  "name" 		=> "unlink_post_title",
		  "title" 		=> __('Unlink Post Title', 'themify'),
		  "description" => __('Unlink post title (it will display the post title without link)', 'themify'),
		  "type" 		=> "dropdown",
		  "meta"		=> array(
		  						array("value" => "default", "name" => "", "selected" => true),
								array("value" => "yes", 'name' => __('Yes', 'themify')),
								array("value" => "no",	'name' => __('No', 'themify'))
							)
		),
	// Hide Post Meta
	array(
		  "name" 		=> "hide_post_meta",
		  "title" 		=> __('Hide Post Meta', 'themify'),
		  "description" => "",
		  "type" 		=> "dropdown",
		  "meta"		=> array(
		  						array("value" => "default", "name" => "", "selected" => true),
								array("value" => "yes", 'name' => __('Yes', 'themify')),
								array("value" => "no",	'name' => __('No', 'themify'))
							)
		),
	// Hide Post Date
	array(
		  "name" 		=> "hide_post_date",
		  "title" 		=> __('Hide Post Date', 'themify'),
		  "description" => "",
		  "type" 		=> "dropdown",
		  "meta"		=> array(
		  						array("value" => "default", "name" => "", "selected" => true),
								array("value" => "yes", 'name' => __('Yes', 'themify')),
								array("value" => "no",	'name' => __('No', 'themify'))
							)
		),
	// Hide Post Image
	array(
		  "name" 		=> "hide_post_image",
		  "title" 		=> __('Hide Featured Image', 'themify'),
		  "description" => "",
		  "type" 		=> "dropdown",
		  "meta"		=> array(
		  						array("value" => "default", "name" => "", "selected" => true),
								array("value" => "yes", 'name' => __('Yes', 'themify')),
								array("value" => "no",	'name' => __('No', 'themify'))
							)
		),
	// Unlink Post Image
	array(
		  "name" 		=> "unlink_post_image",
		  "title" 		=> __('Unlink Featured Image', 'themify'),
		  "description" => __('Display the Featured Image without link', 'themify'),
		  "type" 		=> "dropdown",
		  "meta"		=> array(
		  						array("value" => "default", "name" => "", "selected" => true),
								array("value" => "yes", 'name' => __('Yes', 'themify')),
								array("value" => "no",	'name' => __('No', 'themify'))
							)
		),
	// Video URL
	array(
		"name" 		=> 'video_url',
		"title" 	=> __('Video URL', 'themify'),
		"description" => __('Video embed URL such as YouTube or Vimeo video url (<a href="http://themify.me/docs/video-embeds">details</a>).', 'themify'),
		"type" 		=> 'textbox',
		"meta"		=> array()
	),
	// External Link
	array(
		  "name" 		=> "external_link",
		  "title" 		=> __('External Link', 'themify'),
		  "description" => __('Link Featured Image and Post Title to external URL', 'themify'),
		  "type" 		=> "textbox",
		  "meta"		=> array()
		),
	// Lightbox Link + Zoom icon
	themify_lightbox_link_field()
	);


	// Page Meta Box Options
	$page_meta_box_options = array(
  	// Page Layout
	array(
		  "name" 		=> "page_layout",
		  "title"		=> __('Sidebar Option', 'themify'),
		  "description"	=> "",
		  "type"		=> "layout",
			'show_title' => true,
		  "meta"		=> array(
				array("value" => "default", "img" => "images/layout-icons/default.png", "selected" => true, 'title' => __('Default', 'themify')),
				array('value' => 'sidebar1', 'img' => 'images/layout-icons/sidebar1.png', 'title' => __('Sidebar Right', 'themify')),
				array('value' => 'sidebar1 sidebar-left', 'img' => 'images/layout-icons/sidebar1-left.png', 'title' => __('Sidebar Left', 'themify')),
				array('value' => 'sidebar-none', 'img' => 'images/layout-icons/sidebar-none.png', 'title' => __('No Sidebar ', 'themify'))
			)
		),
	// Content Width
		array(
			'name'=> 'content_width',
			'title' => __('Content Width', 'themify'),
			'description' => '',
			'type' => 'layout',
			'show_title' => true,
			'meta' => array(
				array(
					'value' => 'default_width',
					'img' => 'themify/img/default.png',
					'selected' => true,
					'title' => __( 'Default', 'themify' )
				),
				array(
					'value' => 'full_width',
					'img' => 'themify/img/fullwidth.png',
					'title' => __( 'Fullwidth', 'themify' )
				)
			)
		),
		// Hide page title
	array(
		  "name" 		=> "hide_page_title",
		  "title"		=> __('Hide Page Title', 'themify'),
		  "description"	=> "",
		  "type" 		=> "dropdown",
		  "meta"		=> array(
		  						array("value" => "default", "name" => "", "selected" => true),
								array("value" => "yes", 'name' => __('Yes', 'themify')),
								array("value" => "no",	'name' => __('No', 'themify'))
							)
		),
		// Custom menu for page
        array(
            'name' 		=> 'custom_menu',
            'title'		=> __( 'Custom Menu', 'themify' ),
            'description'	=> '',
            'type'		=> 'dropdown',
            'meta'		=> themify_get_available_menus(),
        ),
	);

	// Query Post Meta Box Options
	$query_post_meta_box_options = array(
		// Notice
		array(
			'name' => '_query_posts_notice',
			'title' => '',
			'description' => '',
			'type' => 'separator',
			'meta' => array(
				'html' => '<div class="themify-info-link">' . sprintf( __( '<a href="%s">Query Posts</a> allows you to query WordPress posts from any category on the page. To use it, select a Query Category.', 'themify' ), 'http://themify.me/docs/query-posts' ) . '</div>'
			),
		),
 	// Query Category
	array(
		  "name" 		=> "query_category",
		  "title"		=> __('Query Category', 'themify'),
		  "description"	=> __('Select a category or enter multiple category IDs (eg. 2,5,6). Enter 0 to display all category.', 'themify'),
		  "type"		=> "query_category",
		  "meta"		=> array()
		),
	// Descending or Ascending Order for Posts
	array(
		'name' 		=> 'order',
		'title'		=> __('Order', 'themify'),
		'description'	=> '',
		'type'		=> 'dropdown',
		'meta'		=> array(
			array('name' => __('Descending', 'themify'), 'value' => 'desc', 'selected' => true),
			array('name' => __('Ascending', 'themify'), 'value' => 'asc')
		)
	),
	// Criteria to Order By
	array(
		'name' 		=> 'orderby',
		'title'		=> __('Order By', 'themify'),
		'description'	=> '',
		'type'		=> 'dropdown',
		'meta'		=> array(
			array('name' => __('Date', 'themify'), 'value' => 'date', 'selected' => true),
			array('name' => __('Likes Count', 'themify'), 'value' => 'likes'),
			array('name' => __('Random', 'themify'), 'value' => 'rand'),
			array('name' => __('Author', 'themify'), 'value' => 'author'),
			array('name' => __('Post Title', 'themify'), 'value' => 'title'),
			array('name' => __('Comments Number', 'themify'), 'value' => 'comment_count'),
			array('name' => __('Modified Date', 'themify'), 'value' => 'modified'),
			array('name' => __('Post Slug', 'themify'), 'value' => 'name'),
			array('name' => __('Post ID', 'themify'), 'value' => 'ID')
		)
	),
	// Post in lightbox
	array(
		  "name" 		=> 'post_in_lightbox',
		  "title"		=> __('Post Lightbox', 'themify'),
		  "description"	=> __('Open post in lightbox window', 'themify'),
		  "type" 		=> 'dropdown',
		  "meta"		=> array(
				array('value' => 'default', 'name' => '', 'selected' => true),
				array('value' => 'yes', 'name' => __('Yes', 'themify')),
				array('value' => 'no',	'name' => __('No', 'themify'))
			)
		),
	// Post Layout
	array(
		  "name" 		=> "layout",
		  "title"		=> __('Query Post Layout', 'themify'),
		  "description"	=> "",
		  "type"		=> "layout",
			'show_title' => true,
		  "meta"		=> array(
				array("value" => "list-post", "img" => "images/layout-icons/list-post.png", 'title' => __('List Post', 'themify')),
				array("value" => "grid4", "img" => "images/layout-icons/grid4.png", "selected" => true, 'title' => __('Grid 4', 'themify')),
				array('value' => 'grid3', 'img' => 'images/layout-icons/grid3.png', 'title' => __('Grid 3', 'themify')),
				array('value' => 'grid2', 'img' => 'images/layout-icons/grid2.png', 'title' => __('Grid 2', 'themify'))
			)
		),
	// Posts Per Page
	array(
		  "name" 		=> "posts_per_page",
		  "title"		=> __('Posts per page', 'themify'),
		  "description"	=> "",
		  "type"		=> "textbox",
		  "meta"		=> array("size" => "small")
		),

	// Display Content
	array(
		  "name" 		=> "display_content",
		  "title"		=> __('Display Content', 'themify'),
		  "description"	=> "",
		  "type"		=> "dropdown",
		  "meta"		=> array(
				array('name' => __('Full Content', 'themify'),"value"=>"content","selected"=>true),
				array('name' => __('Excerpt', 'themify'),"value"=>"excerpt"),
				array('name' => __('None', 'themify'),"value"=>"none")
			)
		),
	// Featured Image Size
	array(
		'name'	=>	'feature_size_page',
		'title'	=>	__('Image Size', 'themify'),
		'description' => __('Image sizes can be set at <a href="options-media.php">Media Settings</a> and <a href="https://wordpress.org/plugins/regenerate-thumbnails/" target="_blank">Regenerated</a>', 'themify'),
		'type'		 =>	'featimgdropdown'
		),
	// Image Width
	array(
		  "name" 		=> "image_width",
		  "title" 		=> __('Image Width', 'themify'),
		  "description" => "",
		  "type" 		=> "textbox",
		  "meta"		=> array("size"=>"small")
		),
	// Image Height
	array(
		  "name" 		=> "image_height",
		  "title" 		=> __('Image Height', 'themify'),
		  "description" => __('Enter height = 0 to disable vertical cropping with img.php enabled', 'themify'),
		  "type" 		=> "textbox",
		  "meta"		=> array("size"=>"small")
		),
	// Hide Title
	array(
		  "name" 		=> "hide_title",
		  "title"		=> __('Hide Post Title', 'themify'),
		  "description"	=> "",
		  "type" 		=> "dropdown",
		  "meta"		=> array(
				array("value" => "default", "name" => "", "selected" => true),
				array("value" => "yes", 'name' => __('Yes', 'themify')),
				array("value" => "no",	'name' => __('No', 'themify'))
			)
		),
	// Unlink Post Title
	array(
		  "name" 		=> "unlink_title",
		  "title" 		=> __('Unlink Post Title', 'themify'),
		  "description" => __('Unlink post title (it will display the post title without link)', 'themify'),
		  "type" 		=> "dropdown",
		  "meta"		=> array(
				array("value" => "default", "name" => "", "selected" => true),
				array("value" => "yes", 'name' => __('Yes', 'themify')),
				array("value" => "no",	'name' => __('No', 'themify'))
			)
		),
	// Hide Post Date
	array(
		  "name" 		=> "hide_date",
		  "title"		=> __('Hide Post Date', 'themify'),
		  "description"	=> "",
		  "type" 		=> "dropdown",
		  "meta"		=> array(
				array("value" => "default", "name" => "", "selected" => true),
				array("value" => "yes", 'name' => __('Yes', 'themify')),
				array("value" => "no",	'name' => __('No', 'themify'))
			)
		),
	// Hide Post Meta
	array(
		  "name" 		=> "hide_meta",
		  "title"		=> __('Hide Post Meta', 'themify'),
		  "description"	=> "",
		  "type" 		=> "dropdown",
		  "meta"		=> array(
				array("value" => "default", "name" => "", "selected" => true),
				array("value" => "yes", 'name' => __('Yes', 'themify')),
				array("value" => "no",	'name' => __('No', 'themify'))
			)
		),
	// Hide Post Image
	array(
		  "name" 		=> "hide_image",
		  "title" 		=> __('Hide Featured Image', 'themify'),
		  "description" => "",
		  "type" 		=> "dropdown",
		  "meta"		=> array(
				array("value" => "default", "name" => "", "selected" => true),
				array("value" => "yes", 'name' => __('Yes', 'themify')),
				array("value" => "no",	'name' => __('No', 'themify'))
			)
		),
	// Unlink Post Image
	array(
		  "name" 		=> "unlink_image",
		  "title" 		=> __('Unlink Featured Image', 'themify'),
		  "description" => __('Display the Featured Image without link', 'themify'),
		  "type" 		=> "dropdown",
		  "meta"		=> array(
				array("value" => "default", "name" => "", "selected" => true),
				array("value" => "yes", 'name' => __('Yes', 'themify')),
				array("value" => "no",	'name' => __('No', 'themify'))
			)
		),
	// Page Navigation Visibility
	array(
		  "name" 		=> "hide_navigation",
		  "title"		=> __('Hide Page Navigation', 'themify'),
		  "description"	=> "",
		  "type" 		=> "dropdown",
		  "meta"		=> array(
				array("value" => "default", "name" => "", "selected" => true),
				array("value" => "yes", 'name' => __('Yes', 'themify')),
				array("value" => "no",	'name' => __('No', 'themify'))
			)
		)

	);

	///////////////////////////////////////
	// Build Write Panels
	///////////////////////////////////////
	themify_build_write_panels(array(
		array(
			 "name"		=> __('Post Options', 'themify'), // Name displayed in box
			'id' => 'post-options',
			 "options"	=> $post_meta_box_options, 	// Field options
			 "pages"	=> "post"					// Pages to show write panel
			 ),
		array(
			 "name"		=> __('Page Options', 'themify'),
			'id' => 'page-options',
			 "options"	=> $page_meta_box_options,
			 "pages"	=> "page"
			 ),
		array(
			"name"		=> __('Query Posts', 'themify'),
			'id' => 'query-posts',
			"options"	=> $query_post_meta_box_options,
			"pages"	=> "page"
			)
  		)
	);

// Load Themify Social Share
require_once 'class-social-share.php';

/* 	Custom Functions
/***************************************************************************/

	///////////////////////////////////////
	// Enable WordPress feature image
	///////////////////////////////////////
	add_theme_support( 'post-thumbnails' );

	if(!function_exists('themify_theme_get_featured_image_link')) {
		/**
		 * Filters the link generation to include changes for this theme
		 * @param string $link Standard link generated in Themify framework
		 * @return string $link Filtered link
		 */
		function themify_theme_get_featured_image_link( $args ) {
			$defaults = array (
				'no_permalink' => false // if there is no lightbox link, don't return a link
			);
			$args = wp_parse_args( $args, $defaults );
			extract( $args, EXTR_SKIP );
			if ( themify_get('external_link') != '') {
				$link = esc_url(themify_get('external_link'));
			} elseif ( themify_get('lightbox_link') != '') {
				$link = esc_url(themify_get('lightbox_link'));
				if(themify_check('iframe_url')) {
					$do_iframe = '?iframe=true&width=100%&height=100%';
				} else {
					$do_iframe = '';
				}
				$link = $link . $do_iframe . '" class="themify_lightbox';
			} elseif($no_permalink) {
				$link = '';
			} else {
				$link = get_permalink();
				if( !is_single() && '' != themify_get('setting-open_inline') ){
					$link = get_permalink().'?post_in_lightbox=1" class="themify_lightbox_post';
				}
				if( themify_is_query_page() ){
					global $themify;
					$post_in_lightbox = get_post_meta($themify->query_page_id, 'post_in_lightbox', true);
					if( 'no' == $post_in_lightbox ){
						$link = get_permalink();
					} elseif( 'yes' == $post_in_lightbox ){
						$link = get_permalink().'?post_in_lightbox=1" class="themify_lightbox_post';
					} elseif('' != themify_get('setting-open_inline')) {
						$link = get_permalink().'?post_in_lightbox=1" class="themify_lightbox_post';
					}
				}
			}
			return $link;
		};
		add_filter('themify_get_featured_image_link', 'themify_theme_get_featured_image_link');
	}

/**
 * Checks liker's IP and saves it to the post if it's not already in likers list.
 * @since 2.2.6
 */
function themify_likeit() {
	check_ajax_referer( 'ajax_nonce', 'nonce' );

	$post_id = $_POST['post_id'];

	$ip = $_SERVER['REMOTE_ADDR'];

	$current_likers = trim( get_post_meta($post_id, 'likers', true) );

	if( isset( $current_likers ) && '' != $current_likers ) {
		$current_likers_count = count( explode( ',', $current_likers ) );
	} else {
		$current_likers_count = 0;
	}

	if( false === stripos( $current_likers, $ip ) ) {
		if( isset( $current_likers ) && '' != $current_likers )
			$save_likers = $current_likers . ',' . $ip;
		else
			$save_likers = $ip;

		$update_result = update_post_meta($post_id, 'likers', $save_likers);
		update_post_meta( $post_id, '_themify_likes_count', $current_likers_count + 1 );

		if( is_multisite() ) {
			$msblogid = get_post_meta($post_id, 'blogid', true);
			$mspostid = get_post_meta($post_id, 'postid', true);
			switch_to_blog($msblogid);
			update_post_meta( $mspostid, 'likers', $save_likers );
			update_post_meta( $mspostid, '_themify_likes_count', $current_likers_count + 1 );
			restore_current_blog();
		}

		if( $update_result ) {
			echo json_encode( array(
				'status' => 'new',
				'likers' => $current_likers_count + 1,
				'ip' => $ip
			) );
		} else {
			echo json_encode( array(
				'status' => 'failed',
				'ip' => $ip
			) );
		}
	} else {
		echo json_encode( array(
			'status' => 'isliker',
			'ip' => $ip
		) );
	}

	die();
}
add_action('wp_ajax_themify_likeit', 'themify_likeit');
add_action('wp_ajax_nopriv_themify_likeit', 'themify_likeit');

/**
* Return number of likers or 0
* @param bool $echo Whether to echo or just return
* @return string
* @since 2.2.6
*/
function themify_get_like( $echo = true ) {
	if( $current_likers = themify_get( '_themify_likes_count' ) ) {
		$count = $current_likers;
	} else {
		$count = '0';
	}
	if ( $echo ) echo $count;
	return $count;
}

	///////////////////////////////////////
	// Register Custom Menu Function
	///////////////////////////////////////
	function themify_register_custom_nav() {
		if (function_exists('register_nav_menus')) {
			register_nav_menus( array(
				'main-nav' => __( 'Main Navigation', 'themify' ),
			) );
		}
	}

	// Register Custom Menu Function - Action
	add_action('init', 'themify_register_custom_nav');

	///////////////////////////////////////
	// Default Main Nav Function
	///////////////////////////////////////
	function themify_default_main_nav() {
		echo '<ul id="main-nav" class="main-nav clearfix">';
		wp_list_pages('title_li=');
		echo '</ul>';
	}

	///////////////////////////////////////
	// Register Sidebars
	///////////////////////////////////////
	if ( function_exists('register_sidebar') ) {
		register_sidebar(array(
			'name' => __('Sidebar', 'themify'),
			'id' => 'sidebar-main',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widgettitle">',
			'after_title' => '</h4>',
		));
		register_sidebar(array(
			'name' => __('Social Widget', 'themify'),
			'id' => 'social-widget',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<strong class="widgettitle">',
			'after_title' => '</strong>',
		));
	}

	///////////////////////////////////////
	// Footer Sidebars
	///////////////////////////////////////
	themify_register_grouped_widgets();

if( ! function_exists('themify_theme_comment') ) {
	/**
	 * Custom Theme Comment
	 * @param object $comment Current comment.
	 * @param array $args Parameters for comment reply link.
	 * @param int $depth Maximum comment nesting depth.
	 * @since 1.0.0
	 */
	function themify_theme_comment($comment, $args, $depth) {
	   $GLOBALS['comment'] = $comment;
	   ?>

		<li id="comment-<?php comment_ID() ?>">
			<p class="comment-author"> <?php echo get_avatar($comment,$size='48'); ?> <?php printf('<cite>%s</cite>', get_comment_author_link()) ?><br />
				<small class="comment-time">
					<strong><?php comment_date( apply_filters( 'themify_comment_date', '' ) ); ?></strong> @
					<?php comment_time( apply_filters( 'themify_comment_time', '' ) ); ?>
					<?php edit_comment_link( __('Edit', 'themify'),' [',']') ?>
				</small>
			</p>
			<div class="commententry">
				<?php if ($comment->comment_approved == '0') : ?>
					<p>
						<em><?php _e('Your comment is awaiting moderation.', 'themify') ?></em>
					</p>
				<?php endif; ?>
				<?php comment_text() ?>
			</div>
			<p class="reply">
				<?php comment_reply_link(array_merge( $args, array(
						'add_below' => 'comment',
						'depth' => $depth,
						'reply_text' => __( 'Reply', 'themify' ),
						'max_depth' => $args['max_depth'])
				)) ?>
			</p>
	<?php
	}
}

///////////////////////////////////////
// Home Comment
///////////////////////////////////////
if( !function_exists('themify_home_comments') ){
	function themify_home_comments($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment;
		?>

		<li id="comment-<?php comment_ID() ?>">
			<p class="comment-author">
				<?php echo get_avatar($comment,$size='30'); ?>
			</p>
			<div class="commententry">
				<p>
					<?php printf('<cite>%s</cite>', get_comment_author_link()) ?>:
					<?php if ($comment->comment_approved == '0') : ?>

					<em><?php _e('Your comment is awaiting moderation.', 'themify') ?></em>

					<?php endif; ?>
					<?php
					echo wp_trim_words(
						apply_filters( 'get_comment_text', $comment->comment_content, $comment ),
						apply_filters( 'themify_home_comment_length', 20),
						apply_filters( 'themify_home_comment_more', '&hellip;')
					);
					?>
				</p>
			</div>
			<?php edit_comment_link( __('Edit', 'themify'),' [',']') ?>
	<?php
	}
}

/**
 * Template redirect function
 **/
function themify_do_theme_redirect($url) {
	global $post, $wp_query;

	if (have_posts()) {
		include($url);
		die();
	} else {
		$wp_query->is_404 = true;
	}
}

/**
 * Single post lightbox
 **/
function themify_single_post_lightbox() {
	global $wp;

	// locate template single page in lightbox
	if (is_single() && isset($_GET['post_in_lightbox']) && $_GET['post_in_lightbox'] == 1) {

		// remove admin bar inside iframe
		add_filter( 'show_admin_bar', '__return_false' );

		$templatefilename = 'single-lightbox.php';

		$return_template = locate_template( $templatefilename );

		themify_do_theme_redirect($return_template);

	}

}
add_action( 'template_redirect', 'themify_single_post_lightbox', 20 );

/**
 * Add sidebar layout and post layout classes to body tag.
 * @param Array
 * @return Array
 * @package themify
 * @since 1.0.0
 */
function themify_add_body_classes($classes) {
	// If it's post in lightbox, do nothing
	if( isset($_GET['post_in_lightbox']) && $_GET['post_in_lightbox'] == 1 ){
	    $classes[] = 'post-lightbox-iframe';
		return $classes;
	}

	// If fixed header option is enabled, add class
	if ( ! themify_check( 'setting-fixed_header_disabled' ) ) {
		$classes[] = 'has-fixed-header';
	} else {
		$classes[] = 'no-fixed-header';
	}
	return $classes;
}
add_filter('body_class', 'themify_add_body_classes');

///////////////////////////////////////
// WooCommerce Theme Support
///////////////////////////////////////
add_theme_support( 'woocommerce' );

/**
 * Runs only once after 2.7.1 update to add likes_count post meta to posts
 *
 * @since 2.7.1
 */
function themify_setup_likes_count_post_meta() {
	if( 'yes' == get_option( 'themify_setup_likes_count_post_meta' ) )
		return;

	$posts = get_posts( array(
		'posts_per_page' => -1,
		'meta_query' => array(
			array(
				'key' => 'likers',
				'valye' => '',
				'compare' => 'EXISTS'
			)
		)
	) );
	if( ! empty( $posts ) ) {
		foreach( $posts as $_post ) {
			$likes = get_post_meta( $_post->ID, 'likers', true );
			$likes = explode( ',', trim( $likes ) );
			$count = count( $likes );
			update_post_meta( $_post->ID, '_themify_likes_count', $count );
		}
	}
	update_option( 'themify_setup_likes_count_post_meta', 'yes' );
}
add_action( 'init', 'themify_setup_likes_count_post_meta' );

/**
 * Most Liked Posts widget
 * Display list of posts sorted by their popularity
 *
 * @since 2.7.1
 */
function themify_register_most_liked_posts() {
	class Themify_Most_liked_Posts extends WP_Widget {

		///////////////////////////////////////////
		// Most liked Posts
		///////////////////////////////////////////
		function __construct() {
			/* Widget settings. */
			$widget_ops = array( 'classname' => 'most-liked-posts', 'description' => __('A list of posts, optionally filter by category.', 'themify') );

			/* Widget control settings. */
			$control_ops = array( 'id_base' => 'themify-most-liked-posts' );

			/* Create the widget. */
			WP_Widget::__construct( 'themify-most-liked-posts', __('Themify - Most Liked Posts', 'themify'), $widget_ops, $control_ops );
		}

		///////////////////////////////////////////
		// Widget
		///////////////////////////////////////////
		function widget( $args, $instance ) {

			extract( $args );

			/* User-selected settings. */
			$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
			$category 		= isset( $instance['category'] ) ? $instance['category'] : 0;
			$show_count 	= isset( $instance['show_count'] ) ? $instance['show_count'] : 5;
			$show_date 		= isset( $instance['show_date'] ) ? true : false;
			$show_thumb 	= isset( $instance['show_thumb'] ) ? true : false;
			$show_likes_count 	= isset( $instance['show_likes_count'] ) ? true : false;
			$display 		= isset( $instance['display'] )? $instance['display'] : false;
			$show_excerpt 	= isset( $instance['show_excerpt'] ) && $instance['show_excerpt'] ? true : false;
			$excerpt_length = isset( $instance['excerpt_length'] ) ? $instance['excerpt_length'] : 55;
			$show_title 	= isset( $instance['hide_title'] ) ? false : true;

			$query_opts = apply_filters('themify_query', array(
				'posts_per_page' => $show_count,
				'post_type' => 'post',
				'orderby' => 'meta_value_num',
				'order' => 'DESC',
				'meta_key' => '_themify_likes_count',
				'suppress_filters' => false,
			));
			if ( $category ) $query_opts['cat'] = $category;

			$loop = get_posts($query_opts);

			if($loop) {

				/* Before widget (defined by themes). */
				echo $before_widget;

				/* Title of widget (before and after defined by themes). */
				if ( $title ) {
					echo $args['before_title'] . $title . $args['after_title'];
				}

				echo '<ul class="feature-posts-list">';

				global $post;
				foreach ($loop as $post) {
					setup_postdata($post);
					echo '<li>';

						$link = get_post_meta( $post->ID, 'external_link', true );
						if ( ! isset( $link ) || '' == $link ) {
							$link = get_permalink();
						}

						if ( $show_thumb ) {
							themify_image('ignore=true&w='.$instance['thumb_width'].'&h='.$instance['thumb_height'].'&before=<a href="' . esc_url( $link ) . '">&after=</a>&class=post-img');
						}

						if ( $show_title ) echo '<a href="' . esc_url( $link ) . '" class="feature-posts-title">' . get_the_title() . ( $show_likes_count ? ' <span class="likes-count">( ' . themify_get_like( false ) . ' )</span>' : '' ) . '</a> <br />';

						if ( $show_date ) echo '<small>' . get_the_date( apply_filters( 'themify_filter_widget_date', '' ) ) . '</small> <br />';

						if ( $show_excerpt || 'excerpt' == $display ) {
							$the_excerpt = get_the_excerpt();
							if($excerpt_length != '') {
								// cut to character limit
								$the_excerpt = substr( $the_excerpt, 0, $excerpt_length );
								// cut to last space
								$the_excerpt = substr( $the_excerpt, 0, strrpos( $the_excerpt, ' '));
							}
							echo '<span class="post-excerpt">' . wp_kses_post( $the_excerpt ) . '</span>';
						} elseif( 'content' == $display ) {
							$the_content = get_the_content();
							echo '<div class="post-content">' . wp_kses_post( $the_content ) . '</div>';
						}

					echo '</li>';
					wp_reset_postdata();
				}//end for each

				echo '</ul>';

				/* After widget (defined by themes). */
				echo $after_widget;

			}//end if $loop

		}

		///////////////////////////////////////////
		// Update
		///////////////////////////////////////////
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			/* Strip tags (if needed) and update the widget settings. */
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['category'] = $new_instance['category'];
			$instance['show_count'] = $new_instance['show_count'];
			$instance['show_date'] = $new_instance['show_date'];
			$instance['show_thumb'] = $new_instance['show_thumb'];
			$instance['show_likes_count'] = $new_instance['show_likes_count'];
			$instance['display'] = $new_instance['display'];
			$instance['hide_title'] = $new_instance['hide_title'];
			$instance['thumb_width'] = $new_instance['thumb_width'];
			$instance['thumb_height'] = $new_instance['thumb_height'];
			$instance['excerpt_length'] = $new_instance['excerpt_length'];
			$instance['orderby'] = $new_instance['orderby'];
			$instance['order'] = $new_instance['order'];

			return $instance;
		}

		///////////////////////////////////////////
		// Form
		///////////////////////////////////////////
		function form( $instance ) {

			/* Set up some default widget settings. */
			$defaults = array(
				'title'            => __( 'Popular Posts', 'themify' ),
				'category'         => 0,
				'show_count'       => 5,
				'show_date'        => false,
				'show_thumb'       => false,
				'show_likes_count' => false,
				'display'          => 'none',
				'hide_title'       => false,
				'thumb_width'      => 50,
				'thumb_height'     => 50,
				'excerpt_length'   => 55,
				'orderby'          => 'date',
				'order'            => 'DESC',
			);
			$instance = wp_parse_args( (array) $instance, $defaults ); ?>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e('Title:', 'themify'); ?></label><br />
				<input id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" width="100%" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"><?php _e('Category:', 'themify'); ?></label>
				<select id="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'category' ) ); ?>">
					<option value="0" <?php if ( !$instance['category'] ) echo 'selected="selected"'; ?>><?php _e('All', 'themify'); ?></option>
					<?php
					$categories = get_categories(array('type' => 'post'));

					foreach( $categories as $cat ) {
						echo '<option value="' . esc_attr( $cat->cat_ID ) . '"';

						if ( $cat->cat_ID == $instance['category'] ) echo  ' selected="selected"';

						echo '>' . esc_html( $cat->cat_name . ' (' . $cat->category_count . ')' );

						echo '</option>';
					}
					?>
				</select>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'show_count' ) ); ?>"><?php _e('Show:', 'themify'); ?></label>
				<input id="<?php echo esc_attr( $this->get_field_id( 'show_count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_count' ) ); ?>" value="<?php echo esc_attr( $instance['show_count'] ); ?>" size="2" /> <?php _e('posts', 'themify'); ?>
			</p>

			<p>
				<input class="checkbox" type="checkbox" <?php checked( $instance['hide_title'], 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'hide_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'hide_title' ) ); ?>" />
				<label for="<?php echo esc_attr( $this->get_field_id( 'hide_title' ) ); ?>"><?php _e('Hide post title', 'themify'); ?></label>
			</p>

			<p>
				<input class="checkbox" type="checkbox" <?php checked( $instance['show_date'], 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_date' ) ); ?>" />
				<label for="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>"><?php _e('Display post date', 'themify'); ?></label>
			</p>

			<p>
				<input class="checkbox" type="checkbox" <?php checked( $instance['show_thumb'], 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_thumb' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_thumb' ) ); ?>" />
				<label for="<?php echo esc_attr( $this->get_field_id( 'show_thumb' ) ); ?>"><?php _e('Display post thumbnail', 'themify'); ?></label>
			</p>

			<p>
				<input class="checkbox" type="checkbox" <?php checked( $instance['show_likes_count'], 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_likes_count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_likes_count' ) ); ?>" />
				<label for="<?php echo esc_attr( $this->get_field_id( 'show_likes_count' ) ); ?>"><?php _e('Display Likes Count', 'themify'); ?></label>
			</p>

			<?php
			// only allow thumbnail dimensions if GD library supported
			if ( function_exists('imagecreatetruecolor') ) {
			?>
			<p>
			   <label for="<?php echo esc_attr( $this->get_field_id( 'thumb_width' ) ); ?>"><?php _e('Thumbnail size', 'themify'); ?></label> <input type="text" id="<?php echo esc_attr( $this->get_field_id( 'thumb_width' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'thumb_width' ) ); ?>" value="<?php echo esc_attr( $instance['thumb_width'] ); ?>" size="3" /> x <input type="text" id="<?php echo esc_attr( $this->get_field_id( 'thumb_height' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'thumb_height' ) ); ?>" value="<?php echo esc_attr( $instance['thumb_height'] ); ?>" size="3" />
			</p>
			<?php
			}
			?>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'display' ) ); ?>"><?php _e('Display:', 'themify'); ?></label>
				<select id="<?php echo esc_attr( $this->get_field_id( 'display' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'display' ) ); ?>">
					<?php
					foreach( array(
						'none' => __('None', 'themify'),
						'content' => __('Content', 'themify'),
						'excerpt' => __('Excerpt', 'themify')
					) as $key => $title ) {
						echo '<option value="' . esc_attr( $key ) . '" '.selected($key, $instance['display'], false).' >' . esc_html( $title ) . '</option>';
					}
					?>
				</select>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'excerpt_length' ) ); ?>"><?php _e('Excerpt character limit:', 'themify'); ?></label>
				<input id="<?php echo esc_attr( $this->get_field_id( 'excerpt_length' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'excerpt_length' ) ); ?>" value="<?php echo esc_attr( $instance['excerpt_length'] ); ?>" size="5" /><br /><small><?php _e('(leave empty = full excerpt)', 'themify'); ?></small>
			</p>

			<?php
		}
	}
	register_widget( 'Themify_Most_liked_Posts' );
}
add_action( 'widgets_init', 'themify_register_most_liked_posts' );

/**
 * Configure social share items.
 *
 * @since 1.4.0
 *
 * @param array $settings
 *
 * @return array
 */
function themify_theme_social_share_settings( $settings ) {

	return $settings;
}
add_filter( 'themify_social_share_settings', 'themify_theme_social_share_settings' );

/**
 * Handle Builder's JavaScript fullwidth rows, forces fullwidth rows if sidebar is disabled
 *
 * @return bool
 */
function themify_theme_fullwidth_layout( $support ) {
	global $themify;

	/* if Content Width option is set to Fullwidth, do not use JavaScript */
	if( themify_get( 'content_width' ) == 'full_width' ) {
		return true;
	}

	/* using sidebar-none layout, force fullwidth rows using JavaScript */
	if( $themify->layout == 'sidebar-none' ) {
		return false;
	}

	return true;
}
add_filter( 'themify_builder_fullwidth_layout_support', 'themify_theme_fullwidth_layout' );
