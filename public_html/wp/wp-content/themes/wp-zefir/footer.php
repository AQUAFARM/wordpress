<?php
/**
 * The Footer template
 *
 * @package WordPress
 * @subpackage Zefir
 * @since Zefir 1.0
 */

global $bird_options;
?>

<?php if (is_active_sidebar('bird_footer_sidebar')): ?>
<!-- footer widgets area -->
<div id="footer-1">
  <div class="container">
    <div class="row">
      <div class="col-md-12 clearfix">
        <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('bird_footer_sidebar')) : ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<!-- end footer widgets area -->
<?php endif; ?>

<?php
$show_footer_social = (isset($bird_options['show_footer_social'])) ? $bird_options['show_footer_social'] : 1;
if ($show_footer_social) {
  $footer_class = 'col-md-6 col-md-pull-6 clearfix';
} else {
  $footer_class = 'col-md-12';
}

$facebook = (isset($bird_options['social_facebook'])) ? stripslashes($bird_options['social_facebook']) : '';
$twitter = (isset($bird_options['social_twitter'])) ? stripslashes($bird_options['social_twitter']) : '';
$google = (isset($bird_options['social_google'])) ? stripslashes($bird_options['social_google']) : '';
$vk = (isset($bird_options['social_vk'])) ? stripslashes($bird_options['social_vk']) : '';
$youtube = (isset($bird_options['social_youtube'])) ? stripslashes($bird_options['social_youtube']) : '';
$vimeo = (isset($bird_options['social_vimeo'])) ? stripslashes($bird_options['social_vimeo']) : '';
$flickr = (isset($bird_options['social_flickr'])) ? stripslashes($bird_options['social_flickr']) : '';
$instagram = (isset($bird_options['social_instagram'])) ? stripslashes($bird_options['social_instagram']) : '';
$dribbble = (isset($bird_options['social_dribbble'])) ? stripslashes($bird_options['social_dribbble']) : '';

$copyright_info = (isset($bird_options['copyright_info'])) ? stripslashes($bird_options['copyright_info']) : 'Zefir &copy; 2014. All rights reserved';

if ($facebook || $twitter || $google || $vk || $youtube || $vimeo || $flickr || $instagram || $dribbble || $copyright_info) {
  ?>
  <!-- footer -->
  <footer id="footer-2" class="clearfix">
    <div class="container">
      <div class="row">

        <?php if ($show_footer_social) { ?>
          <div class="col-md-6 col-md-push-6 clearfix">
            <ul class="list-unstyled footer-social-icons clearfix">
              <?php if ($facebook) { ?>
                <li><a href="<?php echo esc_url($facebook); ?>" class="footer-social-link" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e('Go to Facebook', 'birdwp-theme'); ?>"><i class="fa fa-facebook"></i></a></li>
              <?php } ?>
              <?php if ($twitter) { ?>
                <li><a href="<?php echo esc_url($twitter); ?>" class="footer-social-link" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e('Go to Twitter', 'birdwp-theme'); ?>"><i class="fa fa-twitter"></i></a></li>
              <?php } ?>
              <?php if ($google) { ?>
                <li><a href="<?php echo esc_url($google); ?>" class="footer-social-link" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e('Go to Google+', 'birdwp-theme'); ?>"><i class="fa fa-google-plus"></i></a></li>
              <?php } ?>
              <?php if ($vk) { ?>
                <li><a href="<?php echo esc_url($vk); ?>" class="footer-social-link" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e('Go to VK', 'birdwp-theme'); ?>"><i class="fa fa-vk"></i></a></li>
              <?php } ?>
              <?php if ($youtube) { ?>
                <li><a href="<?php echo esc_url($youtube); ?>" class="footer-social-link" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e('Go to YouTube', 'birdwp-theme'); ?>"><i class="fa fa-youtube"></i></a></li>
              <?php } ?>
              <?php if ($vimeo) { ?>
                <li><a href="<?php echo esc_url($vimeo); ?>" class="footer-social-link" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e('Go to Vimeo', 'birdwp-theme'); ?>"><i class="fa fa-vimeo-square"></i></a></li>
              <?php } ?>
              <?php if ($flickr) { ?>
                <li><a href="<?php echo esc_url($flickr); ?>" class="footer-social-link" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e('Go to Flickr', 'birdwp-theme'); ?>"><i class="fa fa-flickr"></i></a></li>
              <?php } ?>
              <?php if ($instagram) { ?>
                <li><a href="<?php echo esc_url($instagram); ?>" class="footer-social-link" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e('Go to Instagram', 'birdwp-theme'); ?>"><i class="fa fa-instagram"></i></a></li>
              <?php } ?>
              <?php if ($dribbble) { ?>
                <li><a href="<?php echo esc_url($dribbble); ?>" class="footer-social-link" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e('Go to Dribbble', 'birdwp-theme'); ?>"><i class="fa fa-dribbble"></i></a></li>
              <?php } ?>
            </ul>
          </div>
        <?php } ?>

        <div class="<?php echo esc_attr($footer_class); ?>">
          <div class="copyright-inf">
            <p>
              <?php
              if ($copyright_info) {
                echo force_balance_tags($copyright_info);
              }
              ?>
            </p>
          </div>
        </div>
      </div>
    </div>
  </footer>
  <!-- end footer -->
<?php } ?>

<?php wp_footer(); ?>
</body>
</html>
