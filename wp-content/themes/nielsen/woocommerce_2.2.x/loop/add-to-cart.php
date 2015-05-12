<?php
/**
 * Loop Add to Cart
 *
 * @author        WooThemes
 * @package       WooCommerce/Templates
 * @version       2.1.0
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

global $product, $woocommerce_loop;

$has_wishlist = yit_get_option( 'shop-view-wishlist-button' ) == 'yes' && shortcode_exists( 'yith_wcwl_add_to_wishlist' ) && get_option( 'yith_wcwl_enabled' ) == 'yes';
$hide_button  = get_post_meta( $product->id, 'shop-single-add-to-cart', true ) == 'no' || yit_get_option( 'shop-add-to-cart-button' ) == 'no' || yit_get_option( 'shop-enable' ) == 'no';
$in_stock     = $product->is_in_stock();
$is_wishlist  = function_exists( 'yith_wcwl_is_wishlist' ) && yith_wcwl_is_wishlist();

?>
<div class="product-actions-wrapper <?php echo ( $has_wishlist ) ? 'with-wishlist' : '' ?> border">

    <div class="product-action-button">

        <?php
        if ( $hide_button ) : ?>

            <a href="<?php echo get_permalink( $product->id ); ?>" class="view-details">
                <?php
                if ( yit_get_option( 'shop-enable-button-icon' ) == 'yes' ) {
                    echo '<img class="icon-add-to-cart" src="' . yit_get_option( 'shop-set-options-icon' ) . '"/>';
                }
                echo '<span>' . apply_filters( 'yit_view_details_product_text', __( 'View Details', 'yit' ) ) . '</span>';
                ?>
            </a>

        <?php
        elseif ( ! $in_stock ) : ?>

            <span class="out-of-stock">
                <?php echo apply_filters( 'yit_out_of_stock_product_text', __( 'Out of stock', 'yit' ) ); ?>
            </span>

        <?php
        else :

            $link = array(
                'url'      => '',
                'label'    => '',
                'class'    => '',
                'quantity' => 1
            );


            $handler = apply_filters( 'woocommerce_add_to_cart_handler', $product->product_type, $product );

            if( yit_get_option( 'shop-enable-button-icon' ) == 'yes' && ! $is_wishlist ) {
                if ( $handler == 'simple' ) {
                    $link['label'] = yit_image( "echo=no&src=". yit_get_option( 'shop-add-to-cart-icon' ) ."&getimagesize=1&class=icon-add-to-cart&alt=" . __( 'Add to cart icon', 'yit' ) );
                    $link['class'] = 'add_to_cart_button';
                }
                else{
                    $link['label'] = yit_image( "echo=no&src=". yit_get_option( 'shop-set-options-icon' ) ."&getimagesize=1&class=icon-add-to-cart&alt=" . __( 'Set options icon', 'yit' ) );
                }
            }

            switch ( $handler ) {
                case "variable" :
                    $link['url'] = apply_filters( 'variable_add_to_cart_url', get_permalink( $product->id ) );
                    $link['label'] .= '<span>' . apply_filters( 'variable_add_to_cart_text', __( 'Set options', 'yit' ) ) . '</span>';
                    break;

                case "grouped" :
                    $link['url'] = apply_filters( 'grouped_add_to_cart_url', get_permalink( $product->id ) );
                    $link['label'] .= '<span>' . apply_filters( 'grouped_add_to_cart_text', __( 'View options', 'yit' ) ) . '</span>';
                    break;

                case "external" :
                    $link['url'] = apply_filters( 'external_add_to_cart_url', get_permalink( $product->id ) );
                    $link['label'] .= '<span>' . apply_filters( 'external_add_to_cart_text', __( 'Read More', 'yit' ) ) . '</span>';
                    break;

                default :
                    if ( $product->is_purchasable() ) {
                        $link['url'] = apply_filters( 'add_to_cart_url', esc_url( $product->add_to_cart_url() ) );
                        $link['label'] .= '<span>' . apply_filters( 'add_to_cart_text', __( 'Add to cart', 'yit' ) ) . '</span>';
                        $link['quantity'] = apply_filters( 'add_to_cart_quantity', ( get_post_meta( $product->id, 'minimum_allowed_quantity', true ) ? get_post_meta( $product->id, 'minimum_allowed_quantity', true ) : 1 ) );
                    }
                    else {
                        $link['url'] = apply_filters( 'not_purchasable_url', get_permalink( $product->id ) );
                        $link['label'] .= '<span>' . apply_filters( 'not_purchasable_text', __( 'Read More', 'yit' ) ) . '</span>';
                    }
                    break;
            }

            echo apply_filters( 'woocommerce_loop_add_to_cart_link', sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-quantity="%s" data-product_sku="%s" class="%s product_type_%s">%s</a>', esc_url( $link['url'] ), esc_attr( $product->id ), esc_attr( $link['quantity'] ), esc_attr( $product->get_sku() ), esc_attr( $link['class'] ), esc_attr( $product->product_type ), $link['label'] ), $product, $link );

        endif; ?>

    </div>

    <?php

    if ( yit_get_option( 'shop-view-wishlist-button' ) == 'yes' && shortcode_exists( 'yith_wcwl_add_to_wishlist' ) && get_option( 'yith_wcwl_enabled' ) == 'yes' && ! $is_wishlist ) {
        echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
    }

    ?>
</div>