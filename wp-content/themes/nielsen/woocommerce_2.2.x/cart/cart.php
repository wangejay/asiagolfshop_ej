<?php
/**
 * Cart Page
 *
 * @author        WooThemes
 * @package       WooCommerce/Templates
 * @version       2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

global $woocommerce;

wc_print_notices();
?>

<?php do_action( 'woocommerce_before_cart' ); ?>


    <div class="row">
        <form action="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" method="post" class="clearfix cart-page">

            <div class="col-sm-8 cart-list">


                <?php do_action( 'woocommerce_before_cart_table' ); ?>

                <table class="shop_table cart" cellspacing="0">
                    <thead class="border">
                    <tr>
                        <th class="product-remove"></th>
                        <th class="product-name"><?php _e( 'Description', 'yit' ); ?></th>
                        <th class="product-quantity"><?php _e( 'Quantity', 'yit' ); ?></th>
                        <th class="product-subtotal"><?php _e( 'Subtotal', 'yit' ); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php do_action( 'woocommerce_before_cart_contents' ); ?>

                    <?php
                    if ( sizeof( WC()->cart->get_cart() ) > 0 ) {
                        foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
                            $_product = apply_filters( 'woocommerce_cart_item_product', $values['data'], $values, $cart_item_key );
                            if ( $_product->exists() && $values['quantity'] > 0 ) {
                                ?>
                                <tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $values, $cart_item_key ) ); ?> border">

                                    <!-- Remove from cart link -->
                                    <td class="product-remove">
                                        <?php
                                        echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf( '<a href="%s" title="%s" class="remove a-style-2">X</a>', esc_url( WC()->cart->get_remove_url( $cart_item_key ) ), __( 'Remove this item', 'yit' ) ), $cart_item_key );
                                        ?>
                                    </td>
                                    <!-- Product Name -->
                                    <td class="product-name">
                                        <!-- The thumbnail -->
                                        <div class="product-thumbnail">
                                            <?php
                                            $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $values, $cart_item_key );

                                            if ( ! $_product->is_visible() || ( ! empty( $_product->variation_id ) && ! $_product->parent_is_visible() ) ) {
                                                echo $thumbnail;
                                            }
                                            else {
                                                printf( '<a href="%s">%s</a>', $_product->get_permalink(), $thumbnail );
                                            }
                                            ?>
                                        </div>

                                        <div class="product-name-price">
                                            <div class="product-name">
                                                <?php
                                                if ( ! $_product->is_visible() || ( ! empty( $_product->variation_id ) && ! $_product->parent_is_visible() ) ) {
                                                    echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $values, $cart_item_key );
                                                }
                                                else {
                                                    echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', $_product->get_permalink(), $_product->get_title() ), $values, $cart_item_key );
                                                }

                                                // Meta data
                                                echo WC()->cart->get_item_data( $values );

                                                // Backorder notification
                                                if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $values['quantity'] ) ) {
                                                    echo '<p class="backorder_notification">' . __( 'Available on backorder', 'yit' ) . '</p>';
                                                }
                                                ?>
                                            </div>


                                            <!-- Product price -->
                                            <div class="product-price">
                                                <?php
                                                echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $values, $cart_item_key );
                                                ?>
                                            </div>
                                        </div>
                                    </td>


                                    <!-- Quantity inputs -->
                                    <td class="product-quantity">
                                        <?php
                                        if ( $_product->is_sold_individually() ) {
                                            $product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
                                        }
                                        else {

                                            $product_quantity = woocommerce_quantity_input( array(
                                                'input_name'  => "cart[{$cart_item_key}][qty]",
                                                'input_value' => $values['quantity'],
                                                'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
                                                'min_value'   => '0'
                                            ), $_product, false );
                                        }

                                        echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key );
                                        ?>
                                    </td>

                                    <!-- Product subtotal -->
                                    <td class="product-subtotal">
                                        <?php
                                        echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $values['quantity'] ), $values, $cart_item_key );
                                        ?>
                                    </td>

                                </tr>
                            <?php
                            }
                        }
                    }

                    do_action( 'woocommerce_cart_contents' );
                    ?>

                    <?php do_action( 'woocommerce_after_cart_contents' ); ?>
                    </tbody>
                </table>

                <?php do_action( 'woocommerce_after_cart_table' ); ?>



                    <div class="row after-cart">

                        <div class="col-sm-7">

                            <?php woocommerce_shipping_calculator(); ?>

                        </div>

                        <?php if ( WC()->cart->coupons_enabled() ) : ?>
                            <div class="col-sm-5">
                                <h3 class="head"><?php _e( 'Promotional code', 'yit' ); ?></h3>
                                <table class="shop_table coupon" cellspacing="0">
                                    <tr>
                                        <td>
                                            <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php _e( 'Enter your promotional code', 'yit' ); ?>" />
                                            <input type="submit" class="btn btn-flat-red" name="apply_coupon" value="<?php _e( 'Apply', 'yit' ); ?>" />

                                            <?php do_action( 'woocommerce_cart_coupon' ); ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        <?php endif ?>
                    </div>


                <?php wp_nonce_field( 'woocommerce-cart' ) ?>
            </div>


            <div class="col-sm-4 cart-user-info">

                <div class="cart-collaterals border">
                    <?php do_action( 'woocommerce_cart_collaterals' ); ?>
                    <?php woocommerce_cart_totals(); ?>
                </div>

            </div>

        </form>
    </div>

<?php do_action( 'woocommerce_after_cart' ); ?>