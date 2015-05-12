<?php
/**
 * Empty cart page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

wc_print_notices();

?>

<p id="cart-empty"><?php _e( 'You have not added any items in your shopping bag.', 'yit' ) ?></p>

<?php do_action( 'woocommerce_cart_is_empty' ); ?>

<p id="return-to-shop"><a class="button wc-backward btn btn-ghost" href="<?php echo apply_filters( 'woocommerce_return_to_shop_redirect', get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"><?php _e( 'Back To Shop', 'yit' ) ?></a></p>

<?php if ( shortcode_exists( 'space' ) ) echo do_shortcode( '[space height="50" ]'); ?>