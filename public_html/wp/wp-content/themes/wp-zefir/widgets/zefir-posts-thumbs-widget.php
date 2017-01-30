<?php
/**
 * Thumbnails gallery widget
 *
 * @package WordPress
 * @subpackage Zefir
 * @since Zefir 1.0
 */

/**
 * Random Posts Thumbs Widget class
 */
class BIRD_Posts_Thumbs_Widget extends WP_Widget {

  function BIRD_Posts_Thumbs_Widget() {
    $widget_ops = array('classname' => 'bird_posts_thumbs_widget', 'description' => __('Widget shows a gallery of thumbnails.', 'birdwp-theme'));
    $this->WP_Widget('bird_posts_thumbs_widget', __('Zefir: Thumbnails gallery', 'birdwp-theme'), $widget_ops);
  }

  function widget($args, $instance) {
    extract($args);
	
    $title = $instance['title'];
    $num_posts = $instance['num_posts'];
    $categories = $instance['categories'];
    $show_random = isset($instance['show_random']) ? 'true' : 'false';
	
    if ($show_random == 'true') {
      $orderby = 'rand';
    } else {
      $orderby = 'date';
    }
	
    echo $before_widget;
    if ($title) {
      echo $before_title.$title.$after_title;
    }
	
    $posts_thumbs = new WP_Query(array(
      'orderby' => $orderby,
      'posts_per_page' => intval($num_posts),
      'post_type' => 'post',
      'cat' => $categories,
      'ignore_sticky_posts' => true
    ));
	
    if ($posts_thumbs->have_posts()) : ?>

      <div class="bird-widget-post-thumbs">
        <ul class="list-unstyled clearfix">
          <?php while($posts_thumbs->have_posts()): $posts_thumbs->the_post(); ?>
            <?php
            if (has_post_thumbnail()) { // image ???
              $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'thumbnail'); ?>
              <li>
                <figure class="bird-widget-thumb-wrap">
                  <a href="<?php the_permalink(); ?>" class="thumb-post-title" data-toggle="tooltip" data-placement="top" title="<?php the_title_attribute(); ?>">
                    <img src="<?php echo esc_url($image[0]); ?>" class="widget-thumb-img" alt="<?php the_title_attribute(); ?>">
                    <div class="widget-thumb-mask-bg">
                      <i class="fa fa-link widget-thumb-mask-icon widget-link-icon"></i>
                    </div>
                  </a>
                </figure>
              </li>
              <?php
            } else { // gallery ???
              $galleryMeta = get_post_meta(get_the_ID(), 'bird_mb_gallery', false);
              if (!is_array($galleryMeta)) $galleryMeta = (array)$galleryMeta;

              if (!empty($galleryMeta)) {
                $galleryThumbImg = wp_get_attachment_image_src($galleryMeta[0], 'thumbnail'); ?>
                <li>
                  <figure class="bird-widget-thumb-wrap">
                    <a href="<?php the_permalink(); ?>" class="thumb-post-title" data-toggle="tooltip" data-placement="top" title="<?php the_title_attribute(); ?>">
                      <img src="<?php echo esc_url($galleryThumbImg[0]); ?>" class="widget-thumb-img" alt="<?php the_title_attribute(); ?>">
                      <div class="widget-thumb-mask-bg">
                        <i class="fa fa-link widget-thumb-mask-icon widget-link-icon"></i>
                      </div>
                    </a>
                  </figure>
                </li>
                <?php
              }
            } ?>
          <?php endwhile; ?>
        </ul>
      </div>

    <?php endif;
	
	  wp_reset_postdata();
	  echo $after_widget;
  }

  function update($new_instance, $old_instance) {
    $instance = $old_instance;

    $instance['title'] = htmlspecialchars($new_instance['title'], ENT_QUOTES);
    $instance['num_posts'] = $new_instance['num_posts'];
    $instance['categories'] = $new_instance['categories'];
    $instance['show_random'] = $new_instance['show_random'];

    return $instance;
  }

  function form($instance) {
    $defaults = array(
      'title' 			  => __('Posts Thumbs' , 'birdwp-theme'),
      'num_posts'		  => 20,
      'categories'		=> 'all',
      'show_random'		=> 'on'
    );
	
	  $instance = wp_parse_args((array) $instance, $defaults); ?>
    
    <p>
      <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:' , 'birdwp-theme'); ?></label>
      <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
    </p>
    
    <p>
      <label for="<?php echo esc_attr($this->get_field_id('num_posts')); ?>"><?php esc_html_e('Number of posts:' , 'birdwp-theme'); ?></label>
      <input type="number" min="5" max="120" class="widefat" id="<?php echo esc_attr($this->get_field_id('num_posts')); ?>" name="<?php echo esc_attr($this->get_field_name('num_posts')); ?>" value="<?php echo esc_attr($instance['num_posts']); ?>" />
    </p>
    
    <p>
      <label for="<?php echo esc_attr($this->get_field_id('categories')); ?>"><?php esc_html_e('Filter by Category:' , 'birdwp-theme'); ?></label>
      <select id="<?php echo esc_attr($this->get_field_id('categories')); ?>" name="<?php echo esc_attr($this->get_field_name('categories')); ?>" class="widefat categories" style="width:100%;">
        <option value='all' <?php if ( 'all' == $instance['categories'] ) echo 'selected="selected"'; ?>>all categories</option>
        <?php $categories = get_categories( 'hide_empty=0&depth=1&type=post' ); ?>
        <?php foreach( $categories as $category ) { ?>
        <option value='<?php echo esc_attr($category->term_id); ?>' <?php if ($category->term_id == $instance['categories']) echo 'selected="selected"'; ?>><?php echo esc_html($category->cat_name); ?></option>
        <?php } ?>
      </select>
    </p>
    
    <p>
      <input class="checkbox" type="checkbox" <?php checked($instance['show_random'], 'on'); ?> id="<?php echo esc_attr($this->get_field_id('show_random')); ?>" name="<?php echo esc_attr($this->get_field_name('show_random')); ?>" />
      <label for="<?php echo esc_attr($this->get_field_id('show_random')); ?>"><?php esc_html_e('Random order' , 'birdwp-theme'); ?></label>
    </p>
  
  <?php  
  }
}

// init widget
function BIRD_init_posts_thumbs_widget() {
  register_widget('BIRD_Posts_Thumbs_Widget');
}

add_action('widgets_init', 'BIRD_init_posts_thumbs_widget');
