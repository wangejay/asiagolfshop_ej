<?php
/**
 * Single Product title
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! is_product() ) {
    return;
}

$excluted_terms = '';
$in_category = false;

if( yit_get_option( 'shop-nav-in-category' ) == 'yes' ) {
    $in_category = true;
}

$prev_product = wc_get_product( get_previous_post( $in_category, $excluted_terms, 'product_cat' ) );
$next_product = wc_get_product( get_next_post( $in_category, $excluted_terms, 'product_cat' ) );

$prev_post_content = ( $prev_product != '' ) ? '<div class="prev-product">' . get_the_post_thumbnail( $prev_product->id, 'shop_thumbnail' ) . '<div class="product-info"><h5>' . $prev_product->get_title() . '</h5><p>' . $prev_product->get_price_html() . '</p></div></div>' : '';
$next_post_content = ( $next_product != '' ) ? '<div class="next-product">' . get_the_post_thumbnail( $next_product->id, 'shop_thumbnail' ) . '<div class="product-info"><h5>' . $next_product->get_title() . '</h5><p>' . $next_product->get_price_html() . '</p></div></div>' : '';

$prev = get_previous_post_link( '%link', '<span data-icon="&#xe012;" data-font="retinaicon-font"></span>' . $prev_post_content, $in_category, $excluted_terms, 'product_cat' );
$next = get_next_post_link( '%link', '<span data-icon="&#xe013;" data-font="retinaicon-font"></span>' . $next_post_content , $in_category, $excluted_terms, 'product_cat' );

?>

<div id="product-nav" class="border clearfix">

        <?php if ( $prev != '' ) :
                echo $prev;
        endif; ?>

        <?php if ( $next != '' ) :
                echo $next;
        endif; ?>
</div>
