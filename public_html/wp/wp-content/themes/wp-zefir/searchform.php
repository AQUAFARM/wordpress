<?php
/**
 * The template for displaying search forms in Theme
 *
 * @package WordPress
 * @subpackage Zefir
 * @since Zefir 1.0
 */
?>

<form id="searchform" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
  <div class="input-group">
    <input type="text" name="s" id="s" class="search-field form-control" placeholder="<?php esc_html_e('Search', 'birdwp-theme'); ?>">
    <span class="input-group-btn">
      <button type="submit" class="btn search-submit"><i class="fa fa-search"></i></button>
    </span>
  </div>
</form>
