<?php
/**
 * The template for displaying product category thumbnails within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product_cat.php
 *
 * @author        WooThemes
 * @package       WooCommerce/Templates
 * @version       1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

global $woocommerce_loop;

// check if is mobile
$isMobile = YIT_Mobile()->isMobile();
$isPhone = $isMobile && ! YIT_Mobile()->isTablet();

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) ) {
    $woocommerce_loop['loop'] = 0;
}

$woocommerce_loop['li_class'] = array();

//standard li class
$woocommerce_loop['li_class'][] = 'product-category product';

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

//Set columns and class mobile phone
$row_mobile_value = yit_get_option( 'shop-products-per-row-mobile' );
$row_mobile = intval( ! empty( $row_mobile_value ) ? $row_mobile_value : 2 );

if( $isPhone ) {
    $woocommerce_loop['li_class'][]   = 'col-xxs-' . intval( 12 / $row_mobile );
    $woocommerce_loop['columns']      = $row_mobile;
}

// Increase loop count
$woocommerce_loop['loop'] ++;

// add class first/last
if ( ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] == 0 || $woocommerce_loop['columns'] == 1 ) {
    $woocommerce_loop['li_class'][] = 'first';
}
if ( $woocommerce_loop['loop'] % $woocommerce_loop['columns'] == 0 ) {
    $woocommerce_loop['li_class'][] = 'last';
}

?>

<li <?php post_class( $woocommerce_loop['li_class'] ) ?> >

    <?php do_action( 'woocommerce_before_subcategory', $category ); ?>

    <a href="<?php echo get_term_link( $category->slug, 'product_cat' ); ?>" class="product-category-link">

        <div class="category-thumb">
            <?php
            /**
             * woocommerce_before_subcategory_title hook
             *
             * @hooked woocommerce_subcategory_thumbnail - 10
             */
            do_action( 'woocommerce_before_subcategory_title', $category ); ?>

        </div>

        <div class="category-meta">
            <div class="category-name">
                <h4>
                    <?php echo $category->name; ?>
                </h4>
            </div>

            <?php
            if ( ( isset( $show_counter ) && $show_counter == 1 ) && $category->count > 0 ) : ?>

                <div class="category-count">
                    <div class="category-count-content">
                        <?php
                        echo apply_filters( 'woocommerce_subcategory_count_html', ' <span class="count">' . $category->count . _n( " product", " products", $category->count, "yit" ) . '</span>', $category );
                        ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <?php
        /**
         * woocommerce_after_subcategory_title hook
         */
        do_action( 'woocommerce_after_subcategory_title', $category );
        ?>

    </a>

    <?php do_action( 'woocommerce_after_subcategory', $category ); ?>

</li>