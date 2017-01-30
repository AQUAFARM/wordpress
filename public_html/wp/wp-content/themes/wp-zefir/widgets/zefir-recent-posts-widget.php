<?php
/**
 * Recent posts widget
 *
 * @package WordPress
 * @subpackage Zefir
 * @since Zefir 1.0
 */

/**
 * Recent posts widget class
 */
class BIRD_Recent_Posts_Widget extends WP_Widget {

  function BIRD_Recent_Posts_Widget() {
    $widget_ops = array('classname' => 'bird_recent_posts_widget', 'description' => __('Widget shows a list of recent posts.', 'birdwp-theme'));
    $this->WP_Widget('bird_recent_posts_widget', __('Zefir: Recent posts', 'birdwp-theme'), $widget_ops);
  }

  function widget($args, $instance) {
    extract($args);
	
    $title = $instance['title'];
    $num_posts = $instance['num_posts'];
    $show_content = isset($instance['show_content']) ? 'true' : 'false';
    $excerpt_length = intval($instance['excerpt_length']);
    $show_date = isset($instance['show_date']) ? 'true' : 'false';
    $show_author = isset($instance['show_author']) ? 'true' : 'false';
    $categories = $instance['categories'];
	
    echo $before_widget;
    if ($title) {
      echo $before_title.$title.$after_title;
    }
	
    $recent_posts = new WP_Query(array(
      'orderby'	=> 'date',
      'posts_per_page' => intval($num_posts),
      'post_type'	=> 'post',
      'cat' => $categories,
      'ignore_sticky_posts' => true
    ));
	
    if ($recent_posts->have_posts()) :
      while($recent_posts->have_posts()): $recent_posts->the_post(); ?>
        <div class="bw-recent-posts-wrap clearfix">

          <?php
          if (has_post_thumbnail()) { // image ???
            $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'thumbnail'); ?>
            <figure class="bird-widget-thumb-wrap recent-post-thumb-wrap">
              <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                <img src="<?php echo esc_url($image[0]); ?>" class="widget-thumb-img" alt="<?php the_title_attribute(); ?>">
                <div class="widget-thumb-mask-bg">
                  <i class="fa fa-link widget-thumb-mask-icon widget-link-icon"></i>
                </div>
              </a>
            </figure>
            <?php
          } else { // gallery ???
            $galleryMeta = get_post_meta(get_the_ID(), 'bird_mb_gallery', false);
            if (!is_array($galleryMeta)) $galleryMeta = (array)$galleryMeta;

            if (!empty($galleryMeta)) {
              $galleryThumbImg = wp_get_attachment_image_src($galleryMeta[0], 'thumbnail'); ?>
              <figure class="bird-widget-thumb-wrap recent-post-thumb-wrap">
                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                  <img src="<?php echo esc_url($galleryThumbImg[0]); ?>" class="widget-thumb-img" alt="<?php the_title_attribute(); ?>">
                  <div class="widget-thumb-mask-bg">
                    <i class="fa fa-link widget-thumb-mask-icon widget-link-icon"></i>
                  </div>
                </a>
              </figure>
              <?php
            }
          } ?>

          <div class="bird-widget-content">
            <h4><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h4>
            <?php if ($show_content == 'true') { ?>
              <p><?php echo bird_crop_content($excerpt_length); ?></p>
            <?php } ?>
            <?php if ($show_date == 'true' || $show_author == 'true') { ?>
            <ul class="list-unstyled bird-widget-meta clearfix">
              <?php if ($show_date == 'true') { ?>
                <li><i class="fa fa-calendar-o"></i>&nbsp;<?php the_time(get_option('date_format')); ?></li>
              <?php } ?>
              <?php if ($show_author == 'true') { ?>
                <li><i class="fa fa-user"></i>&nbsp;<?php echo the_author_posts_link(); ?></li>
              <?php } ?>
            </ul>
            <?php } ?>
          </div>

        </div>
      <?php
      endwhile;
    endif;
	
    wp_reset_postdata();
    echo $after_widget;
  }

  function update($new_instance, $old_instance) {
    $instance = $old_instance;

    $instance['title'] = htmlspecialchars($new_instance['title'], ENT_QUOTES);
    $instance['num_posts'] = $new_instance['num_posts'];
    $instance['show_content'] = $new_instance['show_content'];
    $instance['excerpt_length'] = intval($new_instance['excerpt_length']);
    $instance['show_date'] = $new_instance['show_date'];
    $instance['show_author'] = $new_instance['show_author'];
    $instance['categories'] = $new_instance['categories'];

    return $instance;
  }

  function form($instance) {
    $defaults = array(
      'title' 			  => __('Recent Posts' , 'birdwp-theme'),
      'num_posts'		  => 4,
      'show_content'	=> 'on',
      'excerpt_length'  => 100,
      'show_date'		  => 'on',
      'show_author'		=> 'on',
      'categories'		=> 'all'
    );
	
    $instance = wp_parse_args((array) $instance, $defaults); ?>
    
    <p>
      <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:' , 'birdwp-theme'); ?></label>
      <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
    </p>
    
    <p>
      <label for="<?php echo esc_attr($this->get_field_id('num_posts')); ?>"><?php esc_html_e('Number of posts:' , 'birdwp-theme'); ?></label>
      <input type="number" min="4" max="120" class="widefat" id="<?php echo esc_attr($this->get_field_id('num_posts')); ?>" name="<?php echo esc_attr($this->get_field_name('num_posts')); ?>" value="<?php echo esc_attr($instance['num_posts']); ?>" />
    </p>

    <p>
      <input class="checkbox" type="checkbox" <?php checked($instance['show_content'], 'on'); ?> id="<?php echo esc_attr($this->get_field_id('show_content')); ?>" name="<?php echo esc_attr($this->get_field_name('show_content')); ?>" />
      <label for="<?php echo esc_attr($this->get_field_id('show_content')); ?>"><?php esc_html_e('Show content' , 'birdwp-theme'); ?></label>
    </p>

    <p>
      <label for="<?php echo esc_attr($this->get_field_id('excerpt_length')); ?>"><?php esc_html_e('Excerpt length:' , 'birdwp-theme'); ?></label>
      <input class="widefat" style="width: 216px;" id="<?php echo esc_attr($this->get_field_id('excerpt_length')); ?>" name="<?php echo esc_attr($this->get_field_name('excerpt_length')); ?>" value="<?php echo intval($instance['excerpt_length']); ?>" />
    </p>

    <p>
      <input class="checkbox" type="checkbox" <?php checked($instance['show_date'], 'on'); ?> id="<?php echo esc_attr($this->get_field_id('show_date')); ?>" name="<?php echo esc_attr($this->get_field_name('show_date')); ?>" />
      <label for="<?php echo esc_attr($this->get_field_id('show_date')); ?>"><?php esc_html_e('Show date' , 'birdwp-theme'); ?></label>
    </p>

    <p>
      <input class="checkbox" type="checkbox" <?php checked($instance['show_author'], 'on'); ?> id="<?php echo esc_attr($this->get_field_id('show_author')); ?>" name="<?php echo esc_attr($this->get_field_name('show_author')); ?>" />
      <label for="<?php echo esc_attr($this->get_field_id('show_author')); ?>"><?php esc_html_e('Show author' , 'birdwp-theme'); ?></label>
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
  
  <?php  
  }
}

// init widget
function BIRD_init_recent_posts_widget() {
  register_widget('BIRD_Recent_Posts_Widget');
}

add_action('widgets_init', 'BIRD_init_recent_posts_widget');
