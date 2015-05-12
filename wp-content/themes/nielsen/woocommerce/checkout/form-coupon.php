<?php
/**
 * Checkout coupon form
 *
 * @author        WooThemes
 * @package       WooCommerce/Templates
 * @version       2.2
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if ( ! WC()->cart->coupons_enabled() ) {
    return;
}

?>

<div class="coupon-form-checkout">

    <p class="coupon_link">
        <?php echo apply_filters( 'woocommerce_checkout_coupon_message', __( 'Have a coupon?', 'yit' ) . '<a href="#" class="showcoupon">' . __( 'Click here to enter your code', 'yit' ) . '</a>' ); ?>
    </p>

    <form class="checkout_coupon" method="post" style="display:none">

        <p class="form-row form-row-wide">
            <input type="text" name="coupon_code" class="input-text" placeholder="<?php _e( 'Coupon code', 'yit' ); ?>" id="coupon_code" value="" />
        </p>

        <p class="form-row form-row-wide input-button">
            <input type="submit" class="btn btn-flat-red" name="apply_coupon" value="<?php _e( 'Apply Coupon', 'yit' ); ?>" />
        </p>

        <div class="clear"></div>
    </form>

</div>