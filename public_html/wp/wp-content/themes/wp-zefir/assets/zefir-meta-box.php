<?php
/**
 * Registering meta boxes
 */
add_filter( 'rwmb_meta_boxes', 'bird_register_meta_boxes' );

/**
 * Register meta boxes
 *
 * @return void
 */
function bird_register_meta_boxes( $meta_boxes ) {
  /**
   * Prefix of meta keys (optional)
   * Use underscore (_) at the beginning to make keys hidden
   * Alt.: You also can make prefix empty to disable it
   */
  // Better has an underscore as last sign
  $prefix = 'bird_mb_';

  /**
   * Page metabox
   */
  // Page settings
  $meta_boxes[] = array(
    'id' => 'bird_mb_page_settings',
    'title' => __('Page settings', 'birdwp-theme'),
    'pages' => array('page'),
    'context' => 'normal',
    'priority' => 'high',

    'fields' => array(
      // Sidebar position
      array(
        'name' => __('Select sidebar position', 'birdwp-theme'),
        'id' => "{$prefix}page_sidebar_position",
        'type' => 'select_advanced',
        // Array of 'value' => 'Label' pairs for select box
        'options' => array(
          'left' => 'Left',
          'right' => 'Right'
        ),
        // Select multiple values, optional. Default is false.
        'multiple' => false,
        //'std'         => 'right', // Default value, optional
        'placeholder' => __('sidebar position', 'birdwp-theme'),
      ),

      // Comments on the page
      array(
        'name' => __('Comments on the page', 'birdwp-theme'),
        'id' => "{$prefix}page_comments",
        'type' => 'select_advanced',
        // Array of 'value' => 'Label' pairs for select box
        'options' => array(
          'enable' => 'Enable',
          'disable' => 'Disable'
        ),
        // Select multiple values, optional. Default is false.
        'multiple' => false,
        'std' => 'disable', // Default value, optional
        'placeholder' => __('comments', 'birdwp-theme'),
      ),
    )
  );

  /**
   * Single post meta box
   */
  // Single post page template + border top color
  $meta_boxes[] = array(
    'id' => 'bird_mb_single_page_type',
    'title' => __('Common post settings', 'birdwp-theme'),
    'pages' => array('post'),
    'context' => 'normal',
    'priority' => 'high',

    'fields' => array(
      // Single post page template
      array(
        'name' => __('Select type of single post page', 'birdwp-theme'),
        'id' => "{$prefix}single_page_type",
        'type' => 'select_advanced',
        // Array of 'value' => 'Label' pairs for select box
        'options' => array(
          'fullwidth_1' => 'Full Width',
          'fullwidth_2' => 'Full Width 2 column',
          'right_sidebar' => 'Right sidebar',
          'left_sidebar' => 'Left sidebar'
        ),
        // Select multiple values, optional. Default is false.
        'multiple' => false,
        //'std'         => 'right_sidebar', // Default value, optional
        'placeholder' => __('single page type', 'birdwp-theme'),
      ),

      // Border-top color
      array(
        'name' => __('Post block border top color', 'birdwp-theme'),
        'id'   => "{$prefix}blog_post_border_color",
        'type' => 'color',
        'std'  => '#bfdeea'
      ),
    )
  );

  // META BOX FOR GALLERY POST FORMAT
  $meta_boxes[] = array(
    'id' => 'bird_mb_gallery_post_format',
    'title' =>  __('Gallery post format', 'birdwp-theme'),
    'pages' => array('post'),
    'context' => 'normal',
    'priority' => 'high',

    'fields' => array(
      array(
        'name' => __('Add Images for Gallery', 'birdwp-theme'),
        'id' => "{$prefix}gallery",
        'type' => 'image_advanced',
        'max_file_uploads' => 50
      ),
    )
  );
  
  // META BOX FOR VIDEO POST FORMAT
  $meta_boxes[] = array(
    'id' => 'bird_mb_video_post_format',
    'title' => __('Video post format', 'birdwp-theme'),
    'pages' => array('post'),
    'context' => 'normal',
    'priority' => 'high',
	
    'fields' => array(

      // SELECT VIDEO TYPE (Youtube or Vimeo)
      array(
        'name' => __('Select type of Video', 'birdwp-theme'),
        'id' => "{$prefix}video_type",
        'type' => 'select_advanced',
        // Array of 'value' => 'Label' pairs for select box
        'options' => array(
          'youtube' => 'YouTube',
          'vimeo' => 'Vimeo'
        ),
        // Select multiple values, optional. Default is false.
        'multiple' => false,
        // 'std'         => 'value2', // Default value, optional
        'placeholder' => __('Type of Video', 'birdwp-theme'),
      ),
	  
      // ENTER VIDEO ID
      array(
        'name'  => __('Video ID', 'birdwp-theme'),
        'id'    => "{$prefix}video_id",
        'desc'  => __('Video ID example: 03jJHUQovp0 (YouTube) or 69445362 (Vimeo)', 'birdwp-theme'),
        'type'  => 'text',
        'std'   => '',
        'clone' => false,
      ),
	  
      // SELECT THUMBNAILS FOR VIDEO POST (Video Player or Featured image of Video)
      array(
        'name' => __('Type of Video Thumbnail', 'birdwp-theme'),
        'id' => "{$prefix}video_thumb",
        'type' => 'select_advanced',
        'options' => array(
          'player' => 'Video Player (Iframe)',
          'featured_img' => 'Image from Video'
        ),
        // Select multiple values, optional. Default is false.
        'multiple' => false,
        // 'std'         => 'value2', // Default value, optional
        'placeholder' => __('Type of Video thumbnail', 'birdwp-theme'),
      ),
	  
	  )
  );

  // META BOX FOR QUOTE POST FORMAT
  $meta_boxes[] = array(
    'id' => 'bird_mb_quote_post_format',
    'title' =>  __('Quote post format', 'birdwp-theme'),
    'pages' => array('post'),
    'context' => 'normal',
    'priority' => 'high',

    'fields' => array(

      // Background color
      array(
        'name' => __('Background Color', 'birdwp-theme'),
        'id'   => "{$prefix}bg_color",
        'type' => 'color',
        'std'  => '#acd3e2'
      ),

      // Font color
      array(
        'name' => __('Font color', 'birdwp-theme'),
        'id'   => "{$prefix}font_color",
        'type' => 'color',
        'std'  => '#FFFFFF'
      )
    )
  );

  return $meta_boxes;
}


