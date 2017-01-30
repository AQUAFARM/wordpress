<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author    WooThemes
 * @package   WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>
<?php get_header( 'shop' ); ?>

<div class="main-content-w">
  <?php os_the_primary_sidebar(); ?>
  <div class="main-content-i">

    <div class="content side-padded-content">
    <?php
      /**
       * woocommerce_before_main_content hook
       *
       * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
       * @hooked woocommerce_breadcrumb - 20
       */
      do_action( 'woocommerce_before_main_content' );
    ?>
    <div class="woo-shop-head">
      <div class="row">
        <div class="col-sm-3">
          <?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>

            <h1 class="page-title"><?php woocommerce_page_title(); ?></h1>

          <?php endif; ?>
          <?php do_action( 'woocommerce_archive_description' ); ?>
        </div>
        <div class="col-sm-8 col-sm-offset-1">
          <?php if ( is_active_sidebar( 'sidebar-3' ) ) : ?>
            <div class="top-sidebar-wrapper"><?php dynamic_sidebar( 'sidebar-3' ); ?></div>
          <?php endif; ?>
        </div>
      </div>

    </div>
    <?php if ( have_posts() ) : ?>
        <div class="shop-loop-results-meta-wrapper">
        <?php
          /**
           * woocommerce_before_shop_loop hook
           *
           * @hooked woocommerce_result_count - 20
           * @hooked woocommerce_catalog_ordering - 30
           */
          do_action( 'woocommerce_before_shop_loop' );
        ?>
        </div>
      <?php woocommerce_product_loop_start(); ?>

        <?php woocommerce_product_subcategories(); ?>

        <?php while ( have_posts() ) : the_post(); ?>

          <?php wc_get_template_part( 'content', 'product' ); ?>

        <?php endwhile; // end of the loop. ?>

      <?php woocommerce_product_loop_end(); ?>

      <?php
        /**
         * woocommerce_after_shop_loop hook
         *
         * @hooked woocommerce_pagination - 10
         */
        do_action( 'woocommerce_after_shop_loop' );
      ?>

    <?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

      <?php wc_get_template( 'loop/no-products-found.php' ); ?>

    <?php endif; ?>
  <?php
    /**
     * woocommerce_after_main_content hook
     *
     * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
     */
    do_action( 'woocommerce_after_main_content' );
  ?>
</div>


    <?php os_footer(); ?>
</div>
</div>
<?php get_footer( 'shop' ); ?>