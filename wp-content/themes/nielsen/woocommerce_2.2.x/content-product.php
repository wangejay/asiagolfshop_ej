<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author        WooThemes
 * @package       WooCommerce/Templates
 * @version       1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

global $product, $woocommerce_loop;

// check if is mobile
$isMobile = YIT_Mobile()->isMobile();
$isPhone = $isMobile && ! YIT_Mobile()->isTablet();
$isIPad = wp_is_mobile() && preg_match( '/iPad/', $_SERVER['HTTP_USER_AGENT'] );

// Ensure visibility
if ( ! $product || ! $product->is_visible() ) {
    return;
}

$woocommerce_loop['shown_product'] = true;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) ) {
    $woocommerce_loop['loop'] = 0;
}

// Increase loop count
$woocommerce_loop['loop'] ++;

// Extra post classes
$woocommerce_loop['li_class'] = array();

// view
if ( ! isset( $woocommerce_loop['view'] ) ) {
    $woocommerce_loop['view'] = yit_get_option( 'shop-view-type', 'grid' );
}

$woocommerce_loop['li_class'][] = $woocommerce_loop['view'];

// Set column
if ( ( is_shop() || is_product_category() ) && ! $isMobile && yit_get_option( 'shop-custom-num-column' ) == 'yes' ) {
    $woocommerce_loop['li_class'][] = 'col-sm-' . intval( 12 / intval( yit_get_option( 'shop-num-column' ) ) );
    $woocommerce_loop['columns']    = intval( yit_get_option( 'shop-num-column' ) );
}
elseif ( isset( $product_in_a_row ) ){
    $woocommerce_loop['li_class'][] = 'col-sm-' . intval( 12 / intval( $product_in_a_row ) ) . ' col-xs-4';
    $woocommerce_loop['columns']    = intval( $product_in_a_row );
}
elseif( isset( $featured_widget ) ) {
    $woocommerce_loop['columns'] = '1';
}
else {

    $sidebar = YIT_Layout()->sidebars;

    if ( $sidebar['layout'] == 'sidebar-double' ) {
        $woocommerce_loop['li_class'][] = 'col-sm-4 col-xs-4';
        $woocommerce_loop['columns']    = '3';
    }
    elseif ( $sidebar['layout'] == 'sidebar-right' || $sidebar['layout'] == 'sidebar-left' ) {
        $woocommerce_loop['li_class'][] = 'col-sm-3 col-xs-4';
        $woocommerce_loop['columns']    = '4';
    }
    else {
        $woocommerce_loop['li_class'][] = 'col-sm-2 col-xs-4';
        $woocommerce_loop['columns']    = '6';
    }
}

//Set columns and class mobile phone
$row_mobile_value = yit_get_option( 'shop-products-per-row-mobile' );
$row_mobile = intval( ! empty( $row_mobile_value ) ? $row_mobile_value : 2 );


if( $isPhone && ! isset( $featured_widget ) ) {
    $woocommerce_loop['li_class'][]   = 'col-xxs-' . intval( 12 / $row_mobile );
    $woocommerce_loop['columns']      = $row_mobile;
}


// Add class first or last
if ( 0 == ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 == $woocommerce_loop['columns'] ) {
    $woocommerce_loop['li_class'][] = 'first';
} if ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns'] ) {
    $woocommerce_loop['li_class'][] = 'last';
}

?>

<li <?php post_class( $woocommerce_loop['li_class'] ); ?> >


    <div class="clearfix product-wrapper border">

        <?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

        <div class="thumb-wrapper">

            <?php
            /**
             * woocommerce_before_shop_loop_item_title hook
             *
             * @hooked woocommerce_show_product_loop_sale_flash - 10
             * @hooked woocommerce_template_loop_product_thumbnail - 10
             */
            do_action( 'woocommerce_before_shop_loop_item_title' );
            ?>

        </div>

        <?php if ( has_action('woocommerce_after_shop_loop_item_title') ) : ?>

        <div class="product-meta-wrapper border">

                 <?php
                /**
                 * woocommerce_after_shop_loop_item_title hook
                 *
                 * @hooked woocommerce_template_loop_rating - 5
                 * @hooked woocommerce_template_loop_price - 10
                 */
                do_action( 'woocommerce_after_shop_loop_item_title' ); ?>

        </div>

        <?php endif; ?>

        <div class="product_actions_container">
            <?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
        </div>

    </div>

</li>
