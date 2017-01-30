<?php
/**
 * Popular posts widget
 *
 * @package WordPress
 * @subpackage Zefir
 * @since Zefir 1.0
 */

/**
 * Popular posts widget class
 */
class BIRD_Popular_Posts_Widget extends WP_Widget {

  function BIRD_Popular_Posts_Widget() {
    $widget_ops = array('classname' => 'bird_popular_posts_widget', 'description' => __('Widget shows a list of popular posts.', 'birdwp-theme'));
    $this->WP_Widget('bird_popular_posts_widget', __('Zefir: Popular posts', 'birdwp-theme'), $widget_ops);
  }

  function widget($args, $instance) {
    extract($args);
	
    $title = $instance['title'];
    $num_posts = $instance['num_posts'];
    $categories = $instance['categories'];
    $show_content = isset($instance['show_content']) ? 'true' : 'false';
    $excerpt_length = intval($instance['excerpt_length']);
    $show_date = isset($instance['show_date']) ? 'true' : 'false';
    $show_views = isset($instance['show_views']) ? 'true' : 'false';
    $show_likes = isset($instance['show_likes']) ? 'true' : 'false';
    $show_author = isset($instance['show_author']) ? 'true' : 'false';
    $show_categories = isset($instance['show_categories']) ? 'true' : 'false';
    $theme_orderby = $instance['theme_orderby'];
	
    echo $before_widget;
    if ($title) {
      echo $before_title.$title.$after_title;
    }
	
    if ($theme_orderby == 'views') {
      $popular_posts = new WP_Query(array(
        'posts_per_page' => intval($num_posts),
        'cat'	=> $categories,
        'meta_key' => 'post_views_count',
        'orderby' => 'meta_value_num',
        'ignore_sticky_posts'	=> true
      ));
    } else if ($theme_orderby == 'likes') {
      $popular_posts = new WP_Query(array(
        'posts_per_page' => intval($num_posts),
        'cat'	=> $categories,
        'orderby'	=> 'meta_value_num',
        'meta_key' => '_post_like_count',
        'ignore_sticky_posts'	=> true
      ));
    } else if ($theme_orderby == 'comments') {
      $popular_posts = new WP_Query(array(
        'posts_per_page' => intval($num_posts),
        'cat' => $categories,
        'orderby' => 'comment_count',
        'ignore_sticky_posts'	=> true
      ));
    }
	
    if ($popular_posts->have_posts()) : ?>
      <?php while($popular_posts->have_posts()): $popular_posts->the_post(); ?>
      
        <div class="bird-widget-popular-wrap">

          <?php
          if (has_post_thumbnail()) { // image ???
            $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'thumbnail');
            ?>

            <figure class="bird-widget-thumb-wrap popular-post-thumb-wrap">
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
            if (!is_array($galleryMeta)) { $galleryMeta = (array)$galleryMeta; }
			
            if (!empty($galleryMeta)) {
              $galleryThumbImg = wp_get_attachment_image_src($galleryMeta[0], 'thumbnail');
              ?>

              <figure class="bird-widget-thumb-wrap popular-post-thumb-wrap">
                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                  <img src="<?php echo esc_url($galleryThumbImg[0]); ?>" class="widget-thumb-img" alt="<?php the_title_attribute(); ?>">
                  <div class="widget-thumb-mask-bg">
                    <i class="fa fa-link widget-thumb-mask-icon widget-link-icon"></i>
                  </div>
                </a>
              </figure>

              <?php
            }
		      }
          ?>
          
          <div class="bird-widget-content">
            <h4><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h4>
            <?php if ($show_content == 'true') { ?>
              <p><?php echo bird_crop_content($excerpt_length); ?></p>
            <?php } ?>
            <ul class="list-unstyled bird-widget-meta clearfix">
              <?php if ($show_views == 'true') { ?>
                <li><i class="fa fa-eye"></i>&nbsp;<?php echo getPostViews(get_the_ID()); ?></li>
              <?php } ?>
              <?php if ($show_likes == 'true') { ?>
                <li><?php echo getPostLikeLink(get_the_ID()); ?></li>
              <?php } ?>
              <?php if ($show_date == 'true') { ?>
                <li><i class="fa fa-calendar-o"></i>&nbsp;<?php the_time(get_option('date_format')); ?></li>
              <?php } ?>
              <?php if ($show_author == 'true') { ?>
                <li><i class="fa fa-user"></i>&nbsp;<?php echo the_author_posts_link(); ?></li>
              <?php } ?>
              <?php if ($show_categories == 'true') { ?>
                <li><i class="fa fa-bookmark-o"></i>&nbsp;<?php the_category(', '); ?></li>
              <?php } ?>
            </ul>
          </div>
        </div>
      
      <?php endwhile; ?>
	  <?php endif;

    wp_reset_postdata();
    echo $after_widget;
  }

  function update($new_instance, $old_instance) {
    $instance = $old_instance;

    $instance['title'] = htmlspecialchars($new_instance['title'], ENT_QUOTES);
    $instance['num_posts'] = $new_instance['num_posts'];
    $instance['categories'] = $new_instance['categories'];
    $instance['show_content'] = $new_instance['show_content'];
    $instance['excerpt_length'] = intval($new_instance['excerpt_length']);
    $instance['show_date'] = $new_instance['show_date'];
    $instance['show_views'] = $new_instance['show_views'];
    $instance['show_likes'] = $new_instance['show_likes'];
    $instance['show_author'] = $new_instance['show_author'];
    $instance['show_categories'] = $new_instance['show_categories'];
    $instance['theme_orderby'] = $new_instance['theme_orderby'];

    return $instance;
  }

  function form($instance) {
    $defaults = array(
      'title' 			  => __('Most Popular' , 'birdwp-theme'),
      'num_posts'		   => 4,
      'categories'		=> 'all',
      'show_content'	=> 'on',
      'excerpt_length'  => 100,
      'show_date'		  => 'on',
      'show_views'		=> 'on',
      'show_likes'		=> 'on',
      'show_author'		=> 'on',
      'show_categories' => 'on',
      'theme_orderby'	=> 'views'
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
      <label for="<?php echo esc_attr($this->get_field_id('categories')); ?>"><?php esc_html_e('Filter by Category:' , 'birdwp-theme'); ?></label>
      <select id="<?php echo esc_attr($this->get_field_id('categories')); ?>" name="<?php echo esc_attr($this->get_field_name('categories')); ?>" class="widefat categories" style="width:100%;">
        <option value='all' <?php if ( 'all' == $instance['categories'] ) echo 'selected="selected"'; ?>>all categories</option>
        <?php $categories = get_categories( 'hide_empty=0&depth=1&type=post' ); ?>
        <?php foreach( $categories as $category ) { ?>
        <option value='<?php echo esc_attr($category->term_id); ?>' <?php if ($category->term_id == $instance['categories']) echo 'selected="selected"'; ?>><?php echo esc_html($category->cat_name); ?></option>
        <?php } ?>
      </select>
    </p>
    
    <p style="margin-top: 20px;">
      <label style="font-weight: bold;"><?php esc_html_e('Content settings' , 'birdwp-theme'); ?></label>
    </p>

    <p>
      <input class="checkbox" type="checkbox" <?php checked($instance['show_content'], 'on'); ?> id="<?php echo esc_attr($this->get_field_id('show_content')); ?>" name="<?php echo esc_attr($this->get_field_name('show_content')); ?>" />
      <label for="<?php echo esc_attr($this->get_field_id('show_content')); ?>"><?php esc_html_e('Show content' , 'birdwp-theme'); ?></label>
    </p>

    <p>
      <label for="<?php echo esc_attr($this->get_field_id('excerpt_length')); ?>"><?php esc_html_e('Excerpt length:' , 'birdwp-theme'); ?></label>
      <input class="widefat" style="width: 216px;" id="<?php echo esc_attr($this->get_field_id('excerpt_length')); ?>" name="<?php echo esc_attr($this->get_field_name('excerpt_length')); ?>" value="<?php echo intval($instance['excerpt_length']); ?>" />
    </p>
    
    <p style="margin-top: 20px;">
      <label style="font-weight: bold;"><?php esc_html_e('Post meta info' , 'birdwp-theme'); ?></label>
    </p>
    
    <p>
      <input class="checkbox" type="checkbox" <?php checked($instance['show_date'], 'on'); ?> id="<?php echo esc_attr($this->get_field_id('show_date')); ?>" name="<?php echo esc_attr($this->get_field_name('show_date')); ?>" />
      <label for="<?php echo esc_attr($this->get_field_id('show_date')); ?>"><?php esc_html_e('Show date' , 'birdwp-theme'); ?></label>
    </p>

    <p>
      <input class="checkbox" type="checkbox" <?php checked($instance['show_views'], 'on'); ?> id="<?php echo esc_attr($this->get_field_id('show_views')); ?>" name="<?php echo esc_attr($this->get_field_name('show_views')); ?>" />
      <label for="<?php echo esc_attr($this->get_field_id('show_views')); ?>"><?php esc_html_e('Show views' , 'birdwp-theme'); ?></label>
    </p>
    
    <p>
      <input class="checkbox" type="checkbox" <?php checked($instance['show_likes'], 'on'); ?> id="<?php echo esc_attr($this->get_field_id('show_likes')); ?>" name="<?php echo esc_attr($this->get_field_name('show_likes')); ?>" />
      <label for="<?php echo esc_attr($this->get_field_id('show_likes')); ?>"><?php esc_html_e('Show likes' , 'birdwp-theme'); ?></label>
    </p>
    
    <p>
      <input class="checkbox" type="checkbox" <?php checked($instance['show_author'], 'on'); ?> id="<?php echo esc_attr($this->get_field_id('show_author')); ?>" name="<?php echo esc_attr($this->get_field_name('show_author')); ?>" />
      <label for="<?php echo esc_attr($this->get_field_id('show_author')); ?>"><?php esc_html_e('Show author' , 'birdwp-theme'); ?></label>
    </p>
    
    <p>
      <input class="checkbox" type="checkbox" <?php checked($instance['show_categories'], 'on'); ?> id="<?php echo esc_attr($this->get_field_id('show_categories')); ?>" name="<?php echo esc_attr($this->get_field_name('show_categories')); ?>" />
      <label for="<?php echo esc_attr($this->get_field_id('show_categories')); ?>"><?php esc_html_e('Show categories' , 'birdwp-theme'); ?></label>
    </p>
    
    <p>
      <label for="<?php echo esc_attr($this->get_field_id('theme_orderby')); ?>"><?php esc_html_e('Order by:', 'birdwp-theme'); ?></label>
      <select id="<?php echo esc_attr($this->get_field_id('theme_orderby')); ?>" name="<?php echo esc_attr($this->get_field_name('theme_orderby')); ?>" class="widefat" style="width:100%;">
        <option <?php if ('views' == $instance['theme_orderby']) echo 'selected="selected"'; ?>>views</option>
        <option <?php if ('likes' == $instance['theme_orderby']) echo 'selected="selected"'; ?>>likes</option>
        <option <?php if ('comments' == $instance['theme_orderby']) echo 'selected="selected"'; ?>>comments</option>
      </select>
    </p>
  
  <?php  
  }
}

// init widget
function BIRD_init_popular_posts_widget() {
  register_widget('BIRD_Popular_Posts_Widget');
}

add_action('widgets_init', 'BIRD_init_popular_posts_widget');
