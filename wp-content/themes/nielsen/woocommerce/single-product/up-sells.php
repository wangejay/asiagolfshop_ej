<?php
/**
 * Single Product Up-Sells
 *
 * @author        WooThemes
 * @package       WooCommerce/Templates
 * @version       1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

global $product, $woocommerce, $woocommerce_loop;

$upsells = $product->get_upsells();

if ( sizeof( $upsells ) == 0 ) {
    return;
}

$meta_query = WC()->query->get_meta_query();

$args = array(
    'post_type'           => 'product',
    'ignore_sticky_posts' => 1,
    'no_found_rows'       => 1,
    'posts_per_page'      => $posts_per_page,
    'orderby'             => $orderby,
    'post__in'            => $upsells,
    'post__not_in'        => array( $product->id ),
    'meta_query'          => $meta_query
);

//force grid view
$woocommerce_loop['view'] = 'grid';

$products = new WP_Query( $args );

if ( $products->have_posts() ) : ?>

    <div class="clearfix upsells products">

        <?php if ( shortcode_exists( 'box_title' ) ) {
            echo do_shortcode("[box_title class='up-sells-title' font_size='18' border_color='#f2f2f2' font_alignment='center' border='middle']" . __( 'Up Sells Products', 'yit' ) . "[/box_title]");
        } else {
            echo "<h3>" . __( 'Up Sells Products', 'yit' ) . "</h3>";
        }?>

        <?php woocommerce_product_loop_start(); ?>

            <?php while ( $products->have_posts() ) : $products->the_post(); ?>

                <?php wc_get_template_part( 'content', 'product' ); ?>

            <?php endwhile; // end of the loop. ?>

            <?php woocommerce_product_loop_end(); ?>

    </div>

<?php endif;

wp_reset_postdata();
