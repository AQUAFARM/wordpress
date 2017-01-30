<?php

add_action('init','of_options');

if (!function_exists('of_options'))
{
	function of_options()
	{
		//Access the WordPress Categories via an Array
		$of_categories 		= array();  
		$of_categories_obj 	= get_categories('hide_empty=0');
		foreach ($of_categories_obj as $of_cat) {
		    $of_categories[$of_cat->cat_ID] = $of_cat->cat_name;}
		$categories_tmp 	= array_unshift($of_categories, "Select a category:");    
	       
		//Access the WordPress Pages via an Array
		$of_pages 			= array();
		$of_pages_obj 		= get_pages('sort_column=post_parent,menu_order');    
		foreach ($of_pages_obj as $of_page) {
		    $of_pages[$of_page->ID] = $of_page->post_name; }
		$of_pages_tmp 		= array_unshift($of_pages, "Select a page:");       
	
		//Testing 
		$of_options_select 	= array("one","two","three","four","five"); 
		$of_options_radio 	= array("one" => "One","two" => "Two","three" => "Three","four" => "Four","five" => "Five");
		
		//Sample Homepage blocks for the layout manager (sorter)
		$of_options_homepage_blocks = array
		( 
			"disabled" => array (
				"placebo" 		=> "placebo", //REQUIRED!
				"block_one"		=> "Block One",
				"block_two"		=> "Block Two",
				"block_three"	=> "Block Three",
			), 
			"enabled" => array (
				"placebo" 		=> "placebo", //REQUIRED!
				"block_four"	=> "Block Four",
			),
		);


		//Stylesheets Reader
		$alt_stylesheet_path = LAYOUT_PATH;
		$alt_stylesheets = array();
		
		if ( is_dir($alt_stylesheet_path) ) 
		{
		    if ($alt_stylesheet_dir = opendir($alt_stylesheet_path) ) 
		    { 
		        while ( ($alt_stylesheet_file = readdir($alt_stylesheet_dir)) !== false ) 
		        {
		            if(stristr($alt_stylesheet_file, ".css") !== false)
		            {
		                $alt_stylesheets[] = $alt_stylesheet_file;
		            }
		        }    
		    }
		}


		//Background Images Reader
		$bg_images_path = get_stylesheet_directory(). '/img/bg/'; // change this to where you store your bg images
		$bg_images_url = get_template_directory_uri().'/img/bg/'; // change this to where you store your bg images
		$bg_images = array();
		
		if ( is_dir($bg_images_path) ) {
		    if ($bg_images_dir = opendir($bg_images_path) ) { 
		        while ( ($bg_images_file = readdir($bg_images_dir)) !== false ) {
		            if(stristr($bg_images_file, ".png") !== false || stristr($bg_images_file, ".jpg") !== false) {
		            	natsort($bg_images); //Sorts the array into a natural order
		                $bg_images[] = $bg_images_url . $bg_images_file;
		            }
		        }    
		    }
		}
		

		/*-----------------------------------------------------------------------------------*/
		/* TO DO: Add options/functions that use these */
		/*-----------------------------------------------------------------------------------*/
		
		//More Options
		$uploads_arr 		= wp_upload_dir();
		$all_uploads_path 	= $uploads_arr['path'];
		$all_uploads 		= get_option('of_uploads');
		$other_entries 		= array("Select a number:","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19");
		$body_repeat 		= array("no-repeat","repeat-x","repeat-y","repeat");
		$body_pos 			= array("top left","top center","top right","center left","center center","center right","bottom left","bottom center","bottom right");
		
		// Image Alignment radio box
		$of_options_thumb_align = array("alignleft" => "Left","alignright" => "Right","aligncenter" => "Center"); 
		
		// Image Links to Options
		$of_options_image_link_to = array("image" => "The Image","post" => "The Post"); 
		
		/*-----------------------------------------------------------------------------------*/
		/* Custom */
		/*-----------------------------------------------------------------------------------*/
		// default
		$default_favicon_url = get_template_directory_uri().'/img/favicon.png';
    $page_layout_array = array(
      "1_col" => "1 Column (Full Width)",
      "1_col_left_right_sidebar" => "1 Column + Left and Right Sidebar",
      "1_col_right_sidebar" => "1 Column + Right Sidebar",
      "1_col_left_sidebar" => "1 Column + Left Sidebar",
      "2_col" => "2 Columns",
      "2_col_right_sidebar" => "2 Columns + Right Sidebar",
      "2_col_left_sidebar" => "2 Columns + Left Sidebar",
      "3_col" => "3 Columns",
      "3_col_right_sidebar" => "3 Columns + Right Sidebar",
      "3_col_left_sidebar" => "3 Columns + Left Sidebar",
      "4_col" => "4 Columns"
    );
    $post_order_by = array("Date", "Date ASC", "Title", "Title ASC", "Random");
    $text_transform_array = array(
      "none"			  => "none",
      "capitalize"	=> "capitalize",
      "uppercase"		=> "uppercase",
      "lowercase"		=> "lowercase"
    );
    $logo_type = array("image", "text");
    $default_logo_url = get_template_directory_uri().'/img/zefir-logo.png';
    $default_retina_logo_url = get_template_directory_uri().'/img/zefir-logo@2x.png';
    $default_bg_image = get_template_directory_uri().'/img/bg-image.jpg';
    $background_type = array("Color", "Image", "Predefined Image");
    $background_type_widget = array("Color", "Predefined Image");
    $background_repeat = array ("no-repeat", "repeat", "repeat-x", "repeat-y");
    $background_attachment = array ("fixed", "scroll");
    $background_position = array("top center", "top left", "top right", "center left", "center center", "center right", "bottom left", "bottom center", "bottom right");
    $featured_img_type = array("Original", "Cropped");
    $excerpt_type = array("Excerpt", "More tag");

/*-----------------------------------------------------------------------------------*/
/* The Options Array */
/*-----------------------------------------------------------------------------------*/

// Set the Options Array
global $of_options;
$of_options = array();

/*-----------------------------------------------------------------------------------*/
/* General Settings */
/*-----------------------------------------------------------------------------------*/
$of_options[] = array(
  "name" 		=> __("General Settings", "birdwp-theme"),
  "type" 		=> "heading",
  "icon"		=> ADMIN_IMAGES . "icon-settings.png"
);

// favicon
$of_options[] = array(
  "name" 		=> __("Custom Favicon", "birdwp-theme"),
  "desc" 		=> __("Upload a 16px x 16px Png/Gif image, which will be an favicon on your website.", "birdwp-theme"),
  "id" 		  => "site_favicon",
  "std" 		=> $default_favicon_url,
  "type" 		=> "upload"
);

// home page layout
$of_options[] = array(
  "name" 		=> __("Layout for Home page", "birdwp-theme"),
  "desc" 		=> __("Select a Layout for Home page (default: 4 columns)", "birdwp-theme"),
  "id" 		=> "homepage_type",
  "std" 		=> "4_col",
  "type" 		=> "select",
  "options" 	=> $page_layout_array
);

// category page layout
$of_options[] = array(
  "name" 		=> __("Layout for Category page", "birdwp-theme"),
  "desc" 		=> __("Select a Layout for Category page (default: 4 columns)", "birdwp-theme"),
  "id" 		=> "category_page_type",
  "std" 		=> "4_col",
  "type" 		=> "select",
  "options" 	=> $page_layout_array
);

// tag page layout
$of_options[] = array(
  "name" 		=> __("Layout for Tag page", "birdwp-theme"),
  "desc" 		=> __("Select a Layout for Tag page (default: 4 columns)", "birdwp-theme"),
  "id" 		=> "tag_page_type",
  "std" 		=> "4_col",
  "type" 		=> "select",
  "options" 	=> $page_layout_array
);

// archive and search page layout
$of_options[] = array(
  "name" 		=> __("Layout for Archive and Search result page", "birdwp-theme"),
  "desc" 		=> __("Select a Layout for Archive and Search result page (default: 4 columns)", "birdwp-theme"),
  "id" 		=> "archive_page_type",
  "std" 		=> "4_col",
  "type" 		=> "select",
  "options" 	=> $page_layout_array
);

// sidebar position for 404 page
$of_options[] = array(
  "name" 		=> __("Sidebar position for 404 page", "birdwp-theme"),
  "desc" 		=> __("Select sidebar position for 404 page (default: Right)", "birdwp-theme"),
  "id" 		=> "page_404_sidebar_position",
  "std" 		=> "Right",
  "type" 		=> "select",
  "options" 	=> array('Left', 'Right')
);

// pagination type
$of_options[] = array(
  "name" 		=> __("Pagination type", "birdwp-theme"),
  "desc" 		=> __("Select Standard pagination, Next/Previous page links or Infinite scroll (default: Standard)", "birdwp-theme"),
  "id" 		=> "pagination_type",
  "std" 		=> "Standard",
  "type" 		=> "select",
  "options" 	=> array('Standard', 'Next Previous page links', 'Infinite scroll')
);

// links open in
$of_options[] = array(
  "name" 		=> __("Links open in..", "birdwp-theme"),
  "desc" 		=> __("Select where open links, which lead to a single post page. Useful for infinite scrolling (default: Current tab)", "birdwp-theme"),
  "id" 		=> "post_links_target",
  "std" 		=> "Current tab",
  "type" 		=> "select",
  "options" 	=> array('Current tab', 'New tab')
);

// exclude pages from search
$of_options[] = array(
  "name" 		=> __("Exclude pages from search?", "birdwp-theme"),
  "desc" 		=> __("If you want to exclude pages from search, select yes (default: Yes).", "birdwp-theme"),
  "id" 		  => "exclude_pages_search",
  "std" 		=> 1,
  "on" 		  => __("Yes", "birdwp-theme"),
  "off" 		=> __("No", "birdwp-theme"),
  "type" 		=> "switch"
);

// category for home page
$of_options[] = array(
  "name" 		=> __("Category for Home page", "birdwp-theme"),
  "desc" 		=> __("Select a category for Home page (default: All categories)", "birdwp-theme"),
  "id" 		=> "homepage_category",
  "std" 		=> "Select a category:",
  "type" 		=> "select",
  "options" 	=> $of_categories
);

// home page order type
$of_options[] = array(
  "name" 		=> __("Home page post Order type", "birdwp-theme"),
  "desc" 		=> __("Select post order for Home page (default: Date)", "birdwp-theme"),
  "id" 		=> "homepage_post_order",
  "std" 		=> "Date",
  "type" 		=> "select",
  "options" 	=> $post_order_by
);

// tracking Code
$of_options[] = array(
  "name" 		=> __("Tracking Code", "birdwp-theme"),
  "desc" 		=> __("Paste your Google Analytics (or other) tracking code here. This will be added into the footer template of your theme.", "birdwp-theme"),
  "id" 		=> "theme_tracking_code",
  "std" 		=> "",
  "type" 		=> "textarea"
);

/*-----------------------------------------------------------------------------------*/
/* Logo Settings */
/*-----------------------------------------------------------------------------------*/
$of_options[] = array(
  "name" 		=> __("Logo Settings", "birdwp-theme"),
  "type" 		=> "heading",
  "icon"		=> ADMIN_IMAGES . "logo_setings.png"
);

// logo type - image or text
$of_options[] = array(
  "name"		=> __("Type of Logo", "birdwp-theme"),
  "desc"		=> __("Select type of logo (image/text).", "birdwp-theme"),
  "id"		  => "logo_type",
  "std"		  => "text",
  "type"		=> "select",
  "options"	=> $logo_type
);

// text logo settings
$of_options[] = array(
  "name" 		=> __("Text logo Settings", "birdwp-theme"),
  "desc" 		=> "",
  "id" 		  => "text_logo_settings_info",
  "std" 		=> "<h3 style='margin: 0; text-align: right; font-weight: normal'>Text logo settings</h3>",
  "icon" 		=> true,
  "type" 		=> "info"
);

// text for logo
$of_options[] = array(
  "name" 		=> __("Logo Text", "birdwp-theme"),
  "desc" 		=> __("Enter text for logo.", "birdwp-theme"),
  "id" 		  => "logo_text",
  "std" 		=> "Zefir",
  "type" 		=> "text"
);

// logo font setings
$of_options[] = array(
  "name" 		=> __("Logo font", "birdwp-theme"),
  "desc" 		=> __("Choose parameters for Logo font (default google font: Raleway)", "birdwp-theme"),
  "id" 		  => "logo_font",
  "std" 		=> array(
    'size'  => '20px',
    'face'  => 'Raleway',
    'style' => 'light'
  ),
  "type" 		=> "typography"
);

// logo text transform
$of_options[] = array(
  "name" 		=> __("Logo Text-Transform", "birdwp-theme"),
  "desc" 		=> __("Choose Text-Transform for logo font (default: uppercase)", "birdwp-theme"),
  "id" 		  => "logo_font_transform",
  "std" 		=> "uppercase",
  "type" 		=> "select",
  "options" 	=> $text_transform_array
);

// logo text color
$of_options[] = array(
  "name" 		=> __("Logo text color", "birdwp-theme"),
  "desc" 		=> __("Choose a logo text color (default: #56686f).", "birdwp-theme"),
  "id" 		  => "logo_text_color",
  "std" 		=> "",
  "type" 		=> "color"
);

// logo text hover color
$of_options[] = array(
  "name" 		=> "",
  "desc" 		=> __("Choose a logo hover text color (default: #2eb3e5).", "birdwp-theme"),
  "id" 		  => "logo_text_hover_color",
  "std" 		=> "",
  "type" 		=> "color"
);

// image logo settings
$of_options[] = array(
  "name" 		=> __("Image logo Settings", "birdwp-theme"),
  "desc" 		=> "",
  "id" 		  => "image_logo_settings_info",
  "std" 		=> "<h3 style='margin: 0; text-align: right; font-weight: normal'>Image logo settings</h3>",
  "icon" 		=> true,
  "type" 		=> "info"
);

// upload logo image
$of_options[] = array(
  "name" 		=> __("Upload Logo Image", "birdwp-theme"),
  "desc" 		=> __("Upload image for the logo or enter URL directly.", "birdwp-theme"),
  "id" 		  => "logo_image",
  "std" 		=> $default_logo_url,
  "type" 		=> "upload"
);

// upload retina logo image
$of_options[] = array(
  "name" 		=> __("Upload Retina Logo Image", "birdwp-theme"),
  "desc" 		=> __("Retina logo image should be two times bigger than the custom logo (use this setting if you want to support retina devices).", "birdwp-theme"),
  "id" 		  => "retina_logo_image",
  "std" 		=> $default_retina_logo_url,
  "type" 		=> "upload"
);

// logo image margin right
$of_options[] = array(
  "name" 		=> __("Logo margin right", "birdwp-theme"),
  "desc" 		=> __("Select Logo image margin right (px). Min: 0, max: 140, step: 1, default value: 35", "birdwp-theme"),
  "id" 		  => "logo_image_margin_right",
  "std" 		=> "35",
  "min" 		=> "0",
  "step"		=> "1",
  "max" 		=> "140",
  "type" 		=> "sliderui"
);

/*-----------------------------------------------------------------------------------*/
/* Header Settings */
/*-----------------------------------------------------------------------------------*/
$of_options[] = array(
  "name" 		=> __("Header", "birdwp-theme"),
  "type" 		=> "heading",
  "icon"		=> ADMIN_IMAGES . "layout_header.png"
);

// header bg color
$of_options[] = array(
  "name" 		=> __("Header Background color", "birdwp-theme"),
  "desc" 		=> __("Choose a background color (default: #FFFFFF).", "birdwp-theme"),
  "id" 		  => "header_bg_color",
  "std" 		=> "",
  "type" 		=> "color"
);

// show search icon
$of_options[] = array(
  "name" 		=> __("Show search icon?", "birdwp-theme"),
  "desc" 		=> __("Show/Hide search icon in header or not.", "birdwp-theme"),
  "id" 		  => "show_header_search",
  "std" 		=> 1,
  "on" 		  => __("Show", "birdwp-theme"),
  "off" 		=> __("Hide", "birdwp-theme"),
  "type" 		=> "switch"
);

// search icon color
$of_options[] = array(
  "name" 		=> __("Search icon color", "birdwp-theme"),
  "desc" 		=> __("Choose a Search icon color (default: #86969c).", "birdwp-theme"),
  "id" 		  => "search_icon_color",
  "std" 		=> "",
  "type" 		=> "color"
);

// search icon hover color
$of_options[] = array(
  "name" 		=> "",
  "desc" 		=> __("Choose a Search icon hover color (default: #2eb3e5).", "birdwp-theme"),
  "id" 		  => "search_icon_hover_color",
  "std" 		=> "",
  "type" 		=> "color"
);

// menu style and font setings
$of_options[] = array(
  "name" 		=> __("Menu Settings", "birdwp-theme"),
  "desc" 		=> "",
  "id" 		  => "header_menu_settings_info",
  "std" 		=> "<h3 style='margin: 0; text-align: right; font-weight: normal'>Menu settings</h3>",
  "icon" 		=> true,
  "type" 		=> "info"
);

// links color
$of_options[] = array(
  "name" 		=> __("Menu item color", "birdwp-theme"),
  "desc" 		=> __("Choose a Menu item color (default: #56686f).", "birdwp-theme"),
  "id" 		  => "menu_text_color",
  "std" 		=> "",
  "type" 		=> "color"
);

// links hover color
$of_options[] = array(
  "name" 		=> "",
  "desc" 		=> __("Choose a Menu item Hover color (default: #2eb3e5).", "birdwp-theme"),
  "id" 		  => "menu_text_hover_color",
  "std" 		=> "",
  "type" 		=> "color"
);

// menu font setings
$of_options[] = array(
  "name" 		=> __("Menu font", "birdwp-theme"),
  "desc" 		=> __("Choose parameters for menu font", "birdwp-theme"),
  "id" 		  => "menu_font",
  "std" 		=> array(
    'size' => '13px',
    'style' => 'normal'
  ),
  "type" 		=> "typography"
);

// menu text transform
$of_options[] = array(
  "name" 		=> __("Menu item Text-Transform", "birdwp-theme"),
  "desc" 		=> __("Choose Text-Transform for menu font (default: uppercase)", "birdwp-theme"),
  "id" 		=> "menu_font_transform",
  "std" 		=> "uppercase",
  "type" 		=> "select",
  "options" 	=> $text_transform_array
);

// drop down menu style and font setings
$of_options[] = array(
  "name" 		=> __("Drop-Down Menu Settings", "birdwp-theme"),
  "desc" 		=> "",
  "id" 		  => "header_drop_menu_settings_info",
  "std" 		=> "<h3 style='margin: 0; text-align: right; font-weight: normal'>Drop-down menu settings</h3>",
  "icon" 		=> true,
  "type" 		=> "info"
);

// drop down menu bg color
$of_options[] = array(
  "name" 		=> __("Drop-down menu Background color", "birdwp-theme"),
  "desc" 		=> __("Choose a background color for drop-down menu (default: #FFFFFF).", "birdwp-theme"),
  "id" 		  => "drop_menu_bg_color",
  "std" 		=> "",
  "type" 		=> "color"
);

// drop down menu border color
$of_options[] = array(
  "name" 		=> __("Drop-down menu Border style", "birdwp-theme"),
  "desc" 		=> __("Drop-down menu border style settings (default color: #eff3f4).", "birdwp-theme"),
  "id" 		  => "drop_menu_border",
  "std" 		=> array(
    'width' => '1',
    'style' => 'solid',
    'color' => '#eff3f4'
  ),
  "type" 		=> "border"
);

// drop down menu links color
$of_options[] = array(
  "name" 		=> __("Drop-down menu item color", "birdwp-theme"),
  "desc" 		=> __("Choose a drop-down menu item color (default: #56686f).", "birdwp-theme"),
  "id" 		  => "drop_menu_text_color",
  "std" 		=> "",
  "type" 		=> "color"
);

// drop down menu links hover color
$of_options[] = array(
  "name" 		=> "",
  "desc" 		=> __("Choose a drop-down menu item Hover color (default: #2eb3e5).", "birdwp-theme"),
  "id" 		  => "drop_menu_text_hover_color",
  "std" 		=> "",
  "type" 		=> "color"
);

// drop down menu font setings
$of_options[] = array(
  "name" 		=> __("Drop-down menu font", "birdwp-theme"),
  "desc" 		=> __("Choose parameters for drop-down menu font", "birdwp-theme"),
  "id" 		  => "drop_menu_font",
  "std" 		=> array(
    'size' => '13px',
    'style' => 'normal'
  ),
  "type" 		=> "typography"
);

// drop down menu text transform
$of_options[] = array(
  "name" 		=> __("Drop-down menu item Text-Transform", "birdwp-theme"),
  "desc" 		=> __("Choose Text-Transform for drop-down menu font (default: uppercase)", "birdwp-theme"),
  "id" 		  => "drop_menu_font_transform",
  "std" 		=> "uppercase",
  "type" 		=> "select",
  "options" 	=> $text_transform_array
);

/*-----------------------------------------------------------------------------------*/
/* Home Page slider */
/*-----------------------------------------------------------------------------------*/
$of_options[] = array(
  "name" 		=> __("Homepage slider", "birdwp-theme"),
  "type" 		=> "heading",
  "icon"		=> ADMIN_IMAGES . "slider.png"
);

// show homepage slider or not
$of_options[] = array(
  "name" 		=> __("Show home page slider?", "birdwp-theme"),
  "desc" 		=> __("Show/Hide Home page slider.", "birdwp-theme"),
  "id" 		  => "show_homepage_slider",
  "std" 		=> 1,
  "on" 		  => __("Show", "birdwp-theme"),
  "off" 		=> __("Hide", "birdwp-theme"),
  "type" 		=> "switch"
);

// slideshow
$of_options[] = array(
  "name" 		=> __("Auto slideshow", "birdwp-theme"),
  "desc" 		=> __("Enable/Disable Auto slideshow.", "birdwp-theme"),
  "id" 		  => "auto_slideshow",
  "std" 		=> 1,
  "on" 		  => __("Enable", "birdwp-theme"),
  "off" 		=> __("Disable", "birdwp-theme"),
  "type" 		=> "switch"
);

// show captions to slides
$of_options[] = array(
  "name" 		=> __("Show captions to slides?", "birdwp-theme"),
  "desc" 		=> __("Show/Hide captions to slides (title, description and Learn more button).", "birdwp-theme"),
  "id" 		  => "show_slider_text",
  "std" 		=> 1,
  "on" 		  => __("Show", "birdwp-theme"),
  "off" 		=> __("Hide", "birdwp-theme"),
  "type" 		=> "switch"
);

// Learn more button text
$of_options[] = array(
  "name" 		=> __("Learn More button text", "birdwp-theme"),
  "desc" 		=> __("Enter text for Learn more button.", "birdwp-theme"),
  "id" 		  => "slide_learn_more_btn",
  "std" 		=> "Learn more",
  "type" 		=> "text"
);

// slider options
$of_options[] = array(
  "name" 		=> __("Slider Options", "birdwp-theme"),
  "desc" 		=> __("Add your own images for the slider (recommended image size for the slide: 1320 x 500 px; default slide description margin top: 60 px, default animation speed: 500).", "birdwp-theme"),
  "id" 		  => "homepage_slides",
  "std" 		=> "",
  "type" 		=> "slider"
);

/*-----------------------------------------------------------------------------------*/
/* Style Options */
/*-----------------------------------------------------------------------------------*/
$of_options[] = array(
  "name" 		=> __("Styling settings", "birdwp-theme"),
  "type" 		=> "heading",
  "icon"		=> ADMIN_IMAGES . "icon-paint.png"
);

// theme color
$of_options[] = array(
  "name" 		=> __("Theme color", "birdwp-theme"),
  "desc" 		=> __("Choose a theme color (background).", "birdwp-theme"),
  "id" 		  => "theme_main_color",
  "std" 		=> "",
  "type" 		=> "color"
);

// theme hover color
$of_options[] = array(
  "name" 		=> "",
  "desc" 		=> __("Choose a hover theme color (background).", "birdwp-theme"),
  "id" 		  => "theme_main_hover_color",
  "std" 		=> "",
  "type" 		=> "color"
);

// theme text color
$of_options[] = array(
  "name" 		=> "",
  "desc" 		=> __("Choose a theme text color.", "birdwp-theme"),
  "id" 		  => "theme_main_text_color",
  "std" 		=> "",
  "type" 		=> "color"
);

// links color
$of_options[] = array(
  "name" 		=> __("Links color", "birdwp-theme"),
  "desc" 		=> __("Choose a links color.", "birdwp-theme"),
  "id" 		  => "theme_links_color",
  "std" 		=> "",
  "type" 		=> "color"
);

// links hover color
$of_options[] = array(
  "name" 		=> "",
  "desc" 		=> __("Choose a hover links color.", "birdwp-theme"),
  "id" 		  => "theme_links_hover_color",
  "std" 		=> "",
  "type" 		=> "color"
);

// body background color
$of_options[] = array(
  "name" 		=> __("Body background color", "birdwp-theme"),
  "desc" 		=> __("Choose a body background color.", "birdwp-theme"),
  "id" 		  => "body_bg_color",
  "std" 		=> "",
  "type" 		=> "color"
);

// sidebar box
$of_options[] = array(
  "name" 		=> __("Sidebar with background color?", "birdwp-theme"),
  "desc" 		=> __("Sidebar with background color or not (transparent background).", "birdwp-theme"),
  "id" 		  => "show_sidebar_bg_color",
  "std" 		=> 0,
  "on" 		  => __("Yes", "birdwp-theme"),
  "off" 		=> __("No", "birdwp-theme"),
  "type" 		=> "switch"
);

// type of background
$of_options[] = array(
  "name" 		=> __("Type of Background", "birdwp-theme"),
  "desc" 		=> __("Select the type of background (Color/Image/Predefined Image).", "birdwp-theme"),
  "id" 		  => "body_bg_type",
  "std" 		=> "Predefined Image",
  "type" 		=> "select",
  "options" 	=> $background_type
);

// upload image for body bg
$of_options[] = array(
  "name" 		=> __("Upload background image", "birdwp-theme"),
  "desc" 		=> __("Upload image for background or enter URL directly.", "birdwp-theme"),
  "id" 		  => "body_bg_image",
  "std" 		=> $default_bg_image,
  "type" 		=> "upload"
);

// bg image repeat
$of_options[] = array(
  "name"		=> __("Background image Repeat", "birdwp-theme"),
  "desc"		=> __("Select repeat for the background image.", "birdwp-theme"),
  "id"		  => "body_bg_image_repeat",
  "std"		  => "no-repeat",
  "type"		=> "select",
  "options"	=> $background_repeat
);

// bg image attachment
$of_options[] = array(
  "name"		=> __("Background image Attachment", "birdwp-theme"),
  "desc"		=> __("Background image scrolls with the content of the site or will be fixed.", "birdwp-theme"),
  "id"		  => "body_bg_image_attachment",
  "std"		  => "fixed",
  "type"		=> "select",
  "options"	=> $background_attachment
);

// bg image position
$of_options[] = array(
  "name"		=> __("Background image Position", "birdwp-theme"),
  "desc"		=> __("Select position for the background image.", "birdwp-theme"),
  "id"		  => "body_bg_image_position",
  "std"		  => "top center",
  "type"		=> "select",
  "options"	=> $background_position
);

// patterns
$of_options[] = array(
  "name" 		=> __("Predefined background images", "birdwp-theme"),
  "desc" 		=> __("Select a background pattern.", "birdwp-theme"),
  "id" 		  => "body_bg_predefined_image",
  "std" 		=> $bg_images_url."bg44.png",
  "type" 		=> "tiles",
  "options" 	=> $bg_images,
);

// custom CSS code
$of_options[] = array(
  "name" 		=> __("Custom CSS", "birdwp-theme"),
  "desc" 		=> __("Insert in this block your css code.", "birdwp-theme"),
  "id" 		  => "custom_css_code",
  "std" 		=> "",
  "type" 		=> "textarea"
);

/*-----------------------------------------------------------------------------------*/
/* Font settings */
/*-----------------------------------------------------------------------------------*/
$of_options[] = array(
  "name" 		=> __("Font settings", "birdwp-theme"),
  "type" 		=> "heading",
  "icon"		=> ADMIN_IMAGES . "font_style.png"
);

// character sets
$of_options[] = array(
  "name" 		=> __("Choose the character sets", "birdwp-theme"),
  "desc" 		=> __("Choose the character sets you want. Remember that each font has a different character sets (default: latin).", "birdwp-theme"),
  "id" 		  => "bwp_google_character_sets",
  "std" 		=> array("latin"),
  "type" 		=> "multicheck",
  "options" => array(
    'cyrillic-ext' => 'Cyrillic Extended (cyrillic-ext)',
    'latin' => 'Latin (latin)',
    'greek-ext' => 'Greek Extended (greek-ext)',
    'greek' => 'Greek (greek)',
    'vietnamese' => 'Vietnamese (vietnamese)',
    'latin-ext' => 'Latin Extended (latin-ext)',
    'cyrillic' => 'Cyrillic (cyrillic)'
  ),
);

// main font setings
$of_options[] = array(
  "name" 		=> __("Main font (body)", "birdwp-theme"),
  "desc" 		=> __("Select the parameters for the Main font. (default font: Open Sans; color: #777777)", "birdwp-theme"),
  "id" 		  => "theme_main_font",
  "std" 		=> array(
    'size'  => '13px',
    'face'  => 'Open Sans',
    'style' => 'normal',
    'color' => '#777777'
  ),
  "type" 		=> "typography"
);

// menu font family
$of_options[] = array(
  "name" 		=> __("Menu: Font Family", "birdwp-theme"),
  "desc" 		=> __("Select the font for the Menu (default: Open Sans).", "birdwp-theme"),
  "id" 		  => "bwp_menu_font",
  "std" 		=> array('face' =>'Open Sans'),
  "type" 		=> "typography"
);

// headings font family
$of_options[] = array(
  "name" 		=> __("Headings: Font Family", "birdwp-theme"),
  "desc" 		=> __("Select the font of all Headings (default: Raleway).", "birdwp-theme"),
  "id" 		  => "theme_headings_font",
  "std" 		=> array('face' =>'Raleway'),
  "type" 		=> "typography"
);

// quote font family
$of_options[] = array(
  "name" 		=> __("Quote: Font Family", "birdwp-theme"),
  "desc" 		=> __("Select the font for a Quote (default: Lora).", "birdwp-theme"),
  "id" 		  => "bwp_quote_font",
  "std" 		=> array('face' =>'Lora'),
  "type" 		=> "typography"
);

// h1 font setings
$of_options[] = array(
  "name" 		=> __("H1 Heading", "birdwp-theme"),
  "desc" 		=> __("Choose parameters for H1 heading (default: 36px, normal, #445B63)", "birdwp-theme"),
  "id" 		  => "h1_heading",
  "std" 		=> array(
    'size'  => '36px',
    'style' => 'normal',
    'color' => '#445B63'
  ),
  "type" 		=> "typography"
);

// h2 font setings
$of_options[] = array(
  "name" 		=> __("H2 Heading", "birdwp-theme"),
  "desc" 		=> __("Choose parameters for H2 heading (default: 30px, normal, #445B63)", "birdwp-theme"),
  "id" 		  => "h2_heading",
  "std" 		=> array(
    'size'  => '30px',
    'style' => 'normal',
    'color' => '#445B63'
  ),
  "type" 		=> "typography"
);

// h3 font setings
$of_options[] = array(
  "name" 		=> __("H3 Heading", "birdwp-theme"),
  "desc" 		=> __("Choose parameters for H3 heading (default: 24px, normal, #445B63)", "birdwp-theme"),
  "id" 		  => "h3_heading",
  "std" 		=> array(
    'size'  => '24px',
    'style' => 'normal',
    'color' => '#445B63'
  ),
  "type" 		=> "typography"
);

// h4 font setings
$of_options[] = array(
  "name" 		=> __("H4 Heading", "birdwp-theme"),
  "desc" 		=> __("Choose parameters for H4 heading (default: 16px, normal, #445B63)", "birdwp-theme"),
  "id" 		  => "h4_heading",
  "std" 		=> array(
    'size'  => '16px',
    'style' => 'normal',
    'color' => '#445B63'
  ),
  "type" 		=> "typography"
);

// h5 font setings
$of_options[] = array(
  "name" 		=> __("H5 Heading", "birdwp-theme"),
  "desc" 		=> __("Choose parameters for H5 heading (default: 14px, normal, #445B63)", "birdwp-theme"),
  "id" 		  => "h5_heading",
  "std" 		=> array(
    'size'  => '14px',
    'style' => 'normal',
    'color' => '#445B63'
  ),
  "type" 		=> "typography"
);

// h6 font setings
$of_options[] = array(
  "name" 		=> __("H6 Heading", "birdwp-theme"),
  "desc" 		=> __("Choose parameters for H6 heading (default: 12px, normal, #445B63)", "birdwp-theme"),
  "id" 		  => "h6_heading",
  "std" 		=> array(
    'size'  => '12px',
    'style' => 'normal',
    'color' => '#445B63'
  ),
  "type" 		=> "typography"
);

/*-----------------------------------------------------------------------------------*/
/* Blog */
/*-----------------------------------------------------------------------------------*/
$of_options[] = array(
  "name" 		=> __("Blog settings", "birdwp-theme"),
  "type" 		=> "heading",
  "icon"		=> ADMIN_IMAGES . "blog-settings.png"
);

// post title color
$of_options[] = array(
  "name" 		=> __("Post title color", "birdwp-theme"),
  "desc" 		=> __("Choose a post title color.", "birdwp-theme"),
  "id" 		  => "blog_post_title_color",
  "std" 		=> "",
  "type" 		=> "color"
);

// post title hover color
$of_options[] = array(
  "name" 		=> "",
  "desc" 		=> __("Choose a hover post title color.", "birdwp-theme"),
  "id" 		  => "blog_post_title_hover_color",
  "std" 		=> "",
  "type" 		=> "color"
);

// featured image in the post box
$of_options[] = array(
  "name"		=> __("Featured image type", "birdwp-theme"),
  "desc"		=> __("Select type of featured image (default: Cropped).", "birdwp-theme"),
  "id"		  => "blog_thumb_type",
  "std"		  => "Cropped",
  "type"		=> "select",
  "options"	=> $featured_img_type
);

// excerpt content or use more tag
$of_options[] = array(
  "name"		=> __("Use Excerpt content or More tag", "birdwp-theme"),
  "desc"		=> __("Use Excerpt content or More tag (default: Excerpt).", "birdwp-theme"),
  "id"		  => "blog_excerpt_type",
  "std"		  => "Excerpt",
  "type"		=> "select",
  "options"	=> $excerpt_type
);

// excerpt length
$of_options[] = array(
  "name" 		=> __("Excerpt length", "birdwp-theme"),
  "desc" 		=> __("Set length of excerpt (chars). Min: 40, max: 800, step: 1, default value: 200", "birdwp-theme"),
  "id" 		  => "excerpt_length",
  "std" 		=> "200",
  "min" 		=> "40",
  "step"		=> "1",
  "max" 		=> "800",
  "type" 		=> "sliderui"
);

// show read more link
$of_options[] = array(
  "name" 		=> __("Show Read more link?", "birdwp-theme"),
  "desc" 		=> __("Show/Hide Read more link (only if you select 'Excerpt' content).", "birdwp-theme"),
  "id" 		  => "show_read_more",
  "std" 		=> 1,
  "on" 		  => __("Show", "birdwp-theme"),
  "off" 		=> __("Hide", "birdwp-theme"),
  "type" 		=> "switch"
);

// read more text
$of_options[] = array(
  "name" 		=> __("Read more text", "birdwp-theme"),
  "desc" 		=> __("Enter text for Read more link (default: read more).", "birdwp-theme"),
  "id" 		  => "read_more_text",
  "std" 		=> "read more",
  "type" 		=> "text"
);

// show author
$of_options[] = array(
  "name" 		=> __("Show blog post author?", "birdwp-theme"),
  "desc" 		=> __("Show/Hide blog post author.", "birdwp-theme"),
  "id" 		  => "show_blog_author",
  "std" 		=> 1,
  "on" 		  => __("Show", "birdwp-theme"),
  "off" 		=> __("Hide", "birdwp-theme"),
  "type" 		=> "switch"
);

// show category
$of_options[] = array(
  "name" 		=> __("Show blog post category?", "birdwp-theme"),
  "desc" 		=> __("Show/Hide blog post category.", "birdwp-theme"),
  "id" 		  => "show_blog_category",
  "std" 		=> 1,
  "on" 		  => __("Show", "birdwp-theme"),
  "off" 		=> __("Hide", "birdwp-theme"),
  "type" 		=> "switch"
);

// show date
$of_options[] = array(
  "name" 		=> __("Show blog post date?", "birdwp-theme"),
  "desc" 		=> __("Show/Hide blog post date.", "birdwp-theme"),
  "id" 		  => "show_blog_date",
  "std" 		=> 1,
  "on" 		  => __("Show", "birdwp-theme"),
  "off" 		=> __("Hide", "birdwp-theme"),
  "type" 		=> "switch"
);

// show views
$of_options[] = array(
  "name" 		=> __("Show blog post views?", "birdwp-theme"),
  "desc" 		=> __("Show/Hide blog post views.", "birdwp-theme"),
  "id" 		  => "show_blog_views",
  "std" 		=> 1,
  "on" 		  => __("Show", "birdwp-theme"),
  "off" 		=> __("Hide", "birdwp-theme"),
  "type" 		=> "switch"
);

// show comments count
$of_options[] = array(
  "name" 		=> __("Show blog post comments count?", "birdwp-theme"),
  "desc" 		=> __("Show/Hide blog post comments count.", "birdwp-theme"),
  "id" 		  => "show_blog_comments",
  "std" 		=> 1,
  "on" 		  => __("Show", "birdwp-theme"),
  "off" 		=> __("Hide", "birdwp-theme"),
  "type" 		=> "switch"
);

// show likes
$of_options[] = array(
  "name" 		=> __("Show blog post likes?", "birdwp-theme"),
  "desc" 		=> __("Show/Hide blog post likes.", "birdwp-theme"),
  "id" 		  => "show_blog_likes",
  "std" 		=> 1,
  "on" 		  => __("Show", "birdwp-theme"),
  "off" 		=> __("Hide", "birdwp-theme"),
  "type" 		=> "switch"
);

// enable social share icons
$of_options[] = array(
  "name" 		=> __("Social share", "birdwp-theme"),
  "desc" 		=> __("Enable/Disable social share", "birdwp-theme"),
  "id" 		  => "post_social_share",
  "std" 		=> 1,
  "on" 		  => __("Enable", "birdwp-theme"),
  "off" 		=> __("Disable", "birdwp-theme"),
  "type" 		=> "switch"
);

/*-----------------------------------------------------------------------------------*/
/* Single post page settings + static pages settings */
/*-----------------------------------------------------------------------------------*/
$of_options[] = array(
  "name" 		=> __("Pages settings", "birdwp-theme"),
  "type" 		=> "heading",
  "icon"		=> ADMIN_IMAGES . "single_page.png"
);

// single page settings
$of_options[] = array(
  "name" 		=> __("Single page settings", "birdwp-theme"),
  "desc" 		=> "",
  "id" 		  => "single_page_settings_info",
  "std" 		=> "<h3 style='margin: 0; text-align: right; font-weight: normal'>Single page settings</h3>",
  "icon" 		=> true,
  "type" 		=> "info"
);

// single post page main title color
$of_options[] = array(
  "name" 		=> __("Single post page title color", "birdwp-theme"),
  "desc" 		=> __("Choose a single post page title color.", "birdwp-theme"),
  "id" 		  => "single_main_title_color",
  "std" 		=> "",
  "type" 		=> "color"
);

// type of featured image
$of_options[] = array(
  "name"		=> __("Featured image type", "birdwp-theme"),
  "desc"		=> __("Select type of featured image (default: Cropped).", "birdwp-theme"),
  "id"		  => "single_thumb_type",
  "std"		  => "Cropped",
  "type"		=> "select",
  "options"	=> $featured_img_type
);

// show author
$of_options[] = array(
  "name" 		=> __("Show author?", "birdwp-theme"),
  "desc" 		=> __("Show/Hide author.", "birdwp-theme"),
  "id" 		  => "show_single_author",
  "std" 		=> 1,
  "on" 		  => __("Show", "birdwp-theme"),
  "off" 		=> __("Hide", "birdwp-theme"),
  "type" 		=> "switch"
);

// show date
$of_options[] = array(
  "name" 		=> __("Show date?", "birdwp-theme"),
  "desc" 		=> __("Show/Hide date.", "birdwp-theme"),
  "id" 		  => "show_single_date",
  "std" 		=> 1,
  "on" 		  => __("Show", "birdwp-theme"),
  "off" 		=> __("Hide", "birdwp-theme"),
  "type" 		=> "switch"
);

// show category
$of_options[] = array(
  "name" 		=> __("Show categories?", "birdwp-theme"),
  "desc" 		=> __("Show/Hide categories.", "birdwp-theme"),
  "id" 		  => "show_single_category",
  "std" 		=> 1,
  "on" 		  => __("Show", "birdwp-theme"),
  "off" 		=> __("Hide", "birdwp-theme"),
  "type" 		=> "switch"
);

// show tags
$of_options[] = array(
  "name" 		=> __("Show tags?", "birdwp-theme"),
  "desc" 		=> __("Show/Hide tags.", "birdwp-theme"),
  "id" 		  => "show_single_tags",
  "std" 		=> 1,
  "on" 		  => __("Show", "birdwp-theme"),
  "off" 		=> __("Hide", "birdwp-theme"),
  "type" 		=> "switch"
);

// show views count
$of_options[] = array(
  "name" 		=> __("Show views count?", "birdwp-theme"),
  "desc" 		=> __("Show/Hide views count.", "birdwp-theme"),
  "id" 		  => "show_single_views",
  "std" 		=> 1,
  "on" 		  => __("Show", "birdwp-theme"),
  "off" 		=> __("Hide", "birdwp-theme"),
  "type" 		=> "switch"
);

// show likes
$of_options[] = array(
  "name" 		=> __("Show likes?", "birdwp-theme"),
  "desc" 		=> __("Show/Hide likes.", "birdwp-theme"),
  "id" 		  => "show_single_likes",
  "std" 		=> 1,
  "on" 		  => __("Show", "birdwp-theme"),
  "off" 		=> __("Hide", "birdwp-theme"),
  "type" 		=> "switch"
);

// show share btn
$of_options[] = array(
  "name" 		=> __("Show social share button?", "birdwp-theme"),
  "desc" 		=> __("Show/Hide social share button.", "birdwp-theme"),
  "id" 		  => "show_single_social_share",
  "std" 		=> 1,
  "on" 		  => __("Show", "birdwp-theme"),
  "off" 		=> __("Hide", "birdwp-theme"),
  "type" 		=> "switch"
);

// show about author box
$of_options[] = array(
  "name" 		=> __("Show about author?", "birdwp-theme"),
  "desc" 		=> __("Show/Hide about author block.", "birdwp-theme"),
  "id" 		  => "show_about_author",
  "std" 		=> 1,
  "on" 		  => __("Show", "birdwp-theme"),
  "off" 		=> __("Hide", "birdwp-theme"),
  "type" 		=> "switch"
);

// show post nav
$of_options[] = array(
  "name" 		=> __("Show post navigation?", "birdwp-theme"),
  "desc" 		=> __("Show/Hide post navigation.", "birdwp-theme"),
  "id" 		  => "show_single_postnav",
  "std" 		=> 1,
  "on" 		  => __("Show", "birdwp-theme"),
  "off" 		=> __("Hide", "birdwp-theme"),
  "type" 		=> "switch"
);

// post nav links color
$of_options[] = array(
  "name" 		=> __("Post navigation links color", "birdwp-theme"),
  "desc" 		=> __("Choose a post navigation links color.", "birdwp-theme"),
  "id" 		  => "single_postnav_color",
  "std" 		=> "",
  "type" 		=> "color"
);

// post nav links hover color
$of_options[] = array(
  "name" 		=> "",
  "desc" 		=> __("Choose a post navigation hover links color.", "birdwp-theme"),
  "id" 		  => "single_postnav_hover_color",
  "std" 		=> "",
  "type" 		=> "color"
);

// pages settings
$of_options[] = array(
  "name" 		=> __("Pages settings", "birdwp-theme"),
  "desc" 		=> "",
  "id" 		  => "static_pages_settings_info",
  "std" 		=> "<h3 style='margin: 0; text-align: right; font-weight: normal'>Pages settings</h3>",
  "icon" 		=> true,
  "type" 		=> "info"
);

// show category desc
$of_options[] = array(
  "name" 		=> __("Show category description?", "birdwp-theme"),
  "desc" 		=> __("Show/Hide category description on the category page.", "birdwp-theme"),
  "id" 		  => "show_category_desc",
  "std" 		=> 1,
  "on" 		  => __("Show", "birdwp-theme"),
  "off" 		=> __("Hide", "birdwp-theme"),
  "type" 		=> "switch"
);

// page title color
$of_options[] = array(
  "name" 		=> __("Page title color", "birdwp-theme"),
  "desc" 		=> __("Choose a page title color.", "birdwp-theme"),
  "id" 		  => "static_page_title_color",
  "std" 		=> "",
  "type" 		=> "color"
);

// category page desc color
$of_options[] = array(
  "name" 		=> __("Category description text color", "birdwp-theme"),
  "desc" 		=> __("Choose a category description text color.", "birdwp-theme"),
  "id" 		  => "category_page_desc_color",
  "std" 		=> "",
  "type" 		=> "color"
);

/*-----------------------------------------------------------------------------------*/
/* Footer settings */
/*-----------------------------------------------------------------------------------*/
$of_options[] = array(
  "name" 		=> __("Footer settings", "birdwp-theme"),
  "type" 		=> "heading",
  "icon"		=> ADMIN_IMAGES . "footer_settings.png"
);

// footer bg color
$of_options[] = array(
  "name" 		=> __("Background color", "birdwp-theme"),
  "desc" 		=> __("Choose a background color.", "birdwp-theme"),
  "id" 		  => "footer_bg_color",
  "std" 		=> "",
  "type" 		=> "color"
);

// footer border color
/*
$of_options[] = array(
  "name" 		=> __("Footer border color", "birdwp-theme"),
  "desc" 		=> __("Choose a footer border color.", "birdwp-theme"),
  "id" 		  => "footer_border_color",
  "std" 		=> "",
  "type" 		=> "color"
);
*/

// footer headings color
$of_options[] = array(
  "name" 		=> __("Headings color (for widgets)", "birdwp-theme"),
  "desc" 		=> __("Choose a headings color (for widgets).", "birdwp-theme"),
  "id" 		  => "footer_headings_color",
  "std" 		=> "",
  "type" 		=> "color"
);

// footer text and links color
$of_options[] = array(
  "name" 		=> __("Color for Text and Links", "birdwp-theme"),
  "desc" 		=> __("Choose a color for Text and Links.", "birdwp-theme"),
  "id" 		  => "footer_text_color",
  "std" 		=> "",
  "type" 		=> "color"
);

// footer text and links hover color
$of_options[] = array(
  "name" 		=> "",
  "desc" 		=> __("Choose a Hover color for Links.", "birdwp-theme"),
  "id" 		  => "footer_text_hover_color",
  "std" 		=> "",
  "type" 		=> "color"
);

// the background color for forms
$of_options[] = array(
  "name" 		=> __("Background color for Forms", "birdwp-maren-theme"),
  "desc" 		=> __("Pick a background color for Forms (Search, Contact and Select forms).", "birdwp-theme"),
  "id" 		  => "bwp_footer_widgets_form_bg",
  "std" 		=> "",
  "type" 		=> "color"
);

// text color for forms
$of_options[] = array(
  "name" 		=> __("Text color for Forms", "birdwp-maren-theme"),
  "desc" 		=> __("Pick a text color for Forms (Search, Contact and Select forms).", "birdwp-theme"),
  "id" 		  => "bwp_footer_widgets_form_color",
  "std" 		=> "",
  "type" 		=> "color"
);

// copyright font setings
$of_options[] = array(
  "name" 		=> __("Copyright font", "birdwp-theme"),
  "desc" 		=> __("Choose parameters for Copyright font (default: 13px, Normal)", "birdwp-theme"),
  "id" 		  => "copyright_font",
  "std" 		=> array(
    'size'  => '13px',
    'style' => 'normal'
  ),
  "type" 		=> "typography"
);

// show social icons on the footer
$of_options[] = array(
  "name" 		=> __("Show social icons?", "birdwp-theme"),
  "desc" 		=> __("Show/Hide social icons on the footer.", "birdwp-theme"),
  "id" 		  => "show_footer_social",
  "std" 		=> 1,
  "on" 		  => __("Show", "birdwp-theme"),
  "off" 		=> __("Hide", "birdwp-theme"),
  "type" 		=> "switch"
);

// Facebook
$of_options[] = array(
  "name" 		=> __("Facebook URL", "birdwp-theme"),
  "desc" 		=> __("Enter your Facebook URL.", "birdwp-theme"),
  "id" 		  => "social_facebook",
  "std" 		=> "",
  "type" 		=> "text"
);

// Twitter
$of_options[] = array(
  "name" 		=> __("Twitter URL", "birdwp-theme"),
  "desc" 		=> __("Enter your Twitter URL.", "birdwp-theme"),
  "id" 		  => "social_twitter",
  "std" 		=> "",
  "type" 		=> "text"
);

// Google+
$of_options[] = array(
  "name" 		=> __("Google+ URL", "birdwp-theme"),
  "desc" 		=> __("Enter your Google+ URL.", "birdwp-theme"),
  "id" 		  => "social_google",
  "std" 		=> "",
  "type" 		=> "text"
);

// VK
$of_options[] = array(
  "name" 		=> __("VK URL", "birdwp-theme"),
  "desc" 		=> __("Enter your VK URL.", "birdwp-theme"),
  "id" 		  => "social_vk",
  "std" 		=> "",
  "type" 		=> "text"
);

// YouTube
$of_options[] = array(
  "name" 		=> __("YouTube URL", "birdwp-theme"),
  "desc" 		=> __("Enter your YouTube URL.", "birdwp-theme"),
  "id" 		  => "social_youtube",
  "std" 		=> "",
  "type" 		=> "text"
);

// Vimeo
$of_options[] = array(
  "name" 		=> __("Vimeo URL", "birdwp-theme"),
  "desc" 		=> __("Enter your Vimeo URL.", "birdwp-theme"),
  "id" 		  => "social_vimeo",
  "std" 		=> "",
  "type" 		=> "text"
);

// Flickr
$of_options[] = array(
  "name" 		=> __("Flickr URL", "birdwp-theme"),
  "desc" 		=> __("Enter your Flickr URL.", "birdwp-theme"),
  "id" 		  => "social_flickr",
  "std" 		=> "",
  "type" 		=> "text"
);

// Instagram
$of_options[] = array(
  "name" 		=> __("Instagram URL", "birdwp-theme"),
  "desc" 		=> __("Enter your Instagram URL.", "birdwp-theme"),
  "id" 		  => "social_instagram",
  "std" 		=> "",
  "type" 		=> "text"
);

// Dribbble
$of_options[] = array(
  "name" 		=> __("Dribbble URL", "birdwp-theme"),
  "desc" 		=> __("Enter your Dribbble URL.", "birdwp-theme"),
  "id" 		  => "social_dribbble",
  "std" 		=> "",
  "type" 		=> "text"
);

// copyright info
$of_options[] = array(
  "name" 		=> __("Footer text (copyright info)", "birdwp-theme"),
  "desc" 		=> __("Enter your footer text (copyright info)", "birdwp-theme"),
  "id" 		=> "copyright_info",
  "std" 		=> "Zefir &copy; 2014. All rights reserved",
  "type" 		=> "textarea"
);

/*-----------------------------------------------------------------------------------*/
/* Backup Options */
/*-----------------------------------------------------------------------------------*/
$of_options[] = array( 	"name" 		=> __("Backup Options", "birdwp-theme"),
						"type" 		=> "heading",
						"icon"		=> ADMIN_IMAGES . "icon-backup.png"
				);
				
$of_options[] = array( 	"name" 		=> __("Backup and Restore Options", "birdwp-theme"),
						"id" 		=> "of_backup",
						"std" 		=> "",
						"type" 		=> "backup",
						"desc" 		=> __("You can use the two buttons below to backup your current options, and then restore it back at a later time. This is useful if you want to experiment on the options but would like to keep the old settings in case you need it back.", "birdwp-theme"),
				);
				
$of_options[] = array( 	"name" 		=> __("Transfer Theme Options Data", "birdwp-theme"),
						"id" 		=> "of_transfer",
						"std" 		=> "",
						"type" 		=> "transfer",
						"desc" 		=> __("You can tranfer the saved options data between different installs by copying the text inside the text box. To import data from another install, replace the data in the text box with the one from another install and click \"Import Options\".", "birdwp-theme"),
				);

	}//End function: of_options()
}//End chack if function exists: of_options()
?>
