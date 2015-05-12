<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product;

$size = yit_image_content_single_width();

$style = "";

if ( ! empty( $size ) && $size['content'] != 100 ) {
    $style = 'width:' . $size['content'] . '%; padding-left: 20px;';
}
elseif ( is_quick_view() ) {
    $style = 'width:50%;';
}
elseif ( ! empty( $size ) ) {
    $style = 'width:' . $size['content'] . '%;';
}

do_action( 'yit_check_single_product_layout' );

?>

<?php
/**
 * woocommerce_before_single_product hook
 *
 * @hooked wc_print_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
    echo get_the_password_form();
    return;
}

if ( yit_get_option( 'shop-single-add-to-cart' ) == 'no' || yit_get_option('shop-enable') == 'no' || ( ! $product->is_purchasable() && $product->product_type == 'simple' ) ) {
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
    add_action( 'yit_wishlist_in_other_action', 'yit_shop_wishlist_action' );
}
elseif ( ! $product->is_in_stock() ) {
    //remove_action( 'woocommerce_after_add_to_cart_button', 'yit_shop_wishlist_action' );
    add_action( 'yit_wishlist_after_add_to_cart', 'yit_shop_wishlist_action' );
}

?>

<div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php post_class('product'); ?>>

    <?php
    /**
     * woocommerce_before_single_product_summary hook
     *
     * @hooked woocommerce_show_product_sale_flash - 10
     * @hooked woocommerce_show_product_images - 20
     */
    do_action( 'woocommerce_before_single_product_summary' );
    ?>

    <div class="summary entry-summary" style="<?php echo esc_attr( $style ) ?>" >

        <?php
        /**
         * woocommerce_single_product_summary hook
         *
         * @hooked woocommerce_template_single_title - 5
         * @hooked woocommerce_template_single_rating - 10
         * @hooked woocommerce_template_single_price - 10
         * @hooked woocommerce_template_single_excerpt - 20
         * @hooked woocommerce_template_single_add_to_cart - 30
         * @hooked woocommerce_template_single_meta - 40
         * @hooked woocommerce_template_single_sharing - 50
         */
        do_action( 'woocommerce_single_product_summary' );
        ?>

    </div><!-- .summary -->

    <div class="clearfix"></div>

    <?php
    /**
     * woocommerce_after_single_product_summary hook
     *
     * @hooked woocommerce_output_product_data_tabs - 10
	 * @hooked woocommerce_upsell_display - 15
     * @hooked woocommerce_output_related_products - 20
     */
    do_action( 'woocommerce_after_single_product_summary' );
    ?>

    <meta itemprop="url" content="<?php the_permalink(); ?>" />

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>
