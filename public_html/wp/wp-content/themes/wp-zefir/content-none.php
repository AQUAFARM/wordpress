<?php
/**
 * The template for displaying a "No posts found" message.
 *
 * @package WordPress
 * @subpackage Zefir
 * @since Zefir 1.0
 */
?>

<article id="post-0" class="content-none">
  <h1 class="h2"><?php esc_html_e('Nothing found', 'birdwp-theme'); ?></h1>
  <p><?php esc_html_e('Apologies, but no results were found. Perhaps searching will help find a related post.', 'birdwp-theme'); ?></p>
  <?php get_search_form(); ?>
</article>
