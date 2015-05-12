<?php
/**
 * Related Products
 *
 * @author        WooThemes
 * @package       WooCommerce/Templates
 * @version       1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

global $product, $woocommerce_loop;

$related = $product->get_related( $posts_per_page );

if ( sizeof( $related ) == 0 ) {
    return;
}

$args = apply_filters( 'woocommerce_related_products_args', array(
    'post_type'           => 'product',
    'ignore_sticky_posts' => 1,
    'no_found_rows'       => 1,
    'posts_per_page'      => $posts_per_page,
    'orderby'             => $orderby,
    'post__in'            => $related,
    'post__not_in'        => array( $product->id )
) );

$products = new WP_Query( $args );

$is_slider = ( count( $args['post__in'] ) >= 4 && yit_get_option( 'shop-related-slider' ) == 'yes' ) ? true : false;

//force grid view
$woocommerce_loop['view'] = 'grid';

if ( $products->have_posts() ) : ?>

    <div class="clearfix related products">

    <?php if ( shortcode_exists( 'box_title' ) ) {
            echo do_shortcode("[box_title class='releated-products-title' font_size='18' border_color='#f2f2f2' font_alignment='center' border='middle']" . __( 'Related Products', 'yit' ) . "[/box_title]");
        } else {
            echo "<h2>" . __( 'Related Products', 'yit' ) . "</h2>";
        }?>

    <?php if ( $is_slider ) : ?>
        <div class="products-slider-wrapper" data-autoplay="true" >
            <div class="products-slider">
                <div class="row">
                    <ul class="products">
    <?php else : ?>
        <?php woocommerce_product_loop_start(); ?>
    <?php endif; ?>

    <?php while ( $products->have_posts() ) : $products->the_post(); ?>

        <?php wc_get_template_part( 'content', 'product' ); ?>

    <?php endwhile; // end of the loop. ?>

    <?php if ( $is_slider ) : ?>
                    </ul>
                </div>
            </div>
            <div class="es-nav">
                <div class="es-nav-prev"><span class="fa fa-chevron-left"></span></div>
                <div class="es-nav-next"><span class="fa fa-chevron-right"></span></div>
            </div>
        </div>
    <?php else: ?>
        <?php woocommerce_product_loop_end(); ?>
    <?php endif; ?>
    </div>

<?php endif;

wp_reset_postdata();
