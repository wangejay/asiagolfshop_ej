<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @see yit_slogan_background() For style css
 */

$show_slogan                = YIT_Layout()->show_slogan;
$slogan                     = YIT_Layout()->slogan;
$subslogan                  = YIT_Layout()->sub_slogan;

if ( ( empty( $show_slogan ) || $show_slogan == 'no' ) && function_exists( 'WC' ) ) {

    if ( ( is_cart() && ( sizeof( WC()->cart->get_cart() ) != 0 ) ) || is_checkout() || is_order_received_page() ) {

        $slogan_class           = 'yit-cart-checkout-slogan';
	    $show_slogan            = YIT_Layout()->checkout_show_slogan;
        $slogan_text            = array(
            'cart'              => YIT_Layout()->checkout_cart_text,
            'checkout'          => YIT_Layout()->checkout_text,
            'order_complete'    => YIT_Layout()->checkout_order_complete_text
        );

        ob_start();

        ?>
        <span class="slogan-cart"><?php echo $slogan_text['cart'] ?></span>
        <span class="slogan-checkout"><?php echo $slogan_text['checkout'] ?></span>
        <span class="slogan-complete"><?php echo $slogan_text['order_complete'] ?></span>
        <?php

        $slogan = ob_get_clean();

    }
    elseif( is_cart() && sizeof( WC()->cart->get_cart() ) == 0 ) {

        $slogan_class       = 'yit-cart-checkout-slogan';
        $show_slogan        = YIT_Layout()->checkout_show_slogan;
        $slogan_text        = YIT_Layout()->checkout_cart_empty_text;

        ob_start();

        ?>
        <span class="slogan-cart"><?php echo $slogan_text ?></span>
        <?php

        $slogan = ob_get_clean();

    }
    elseif( is_account_page() ) {

        $slogan_class           = 'yit-my-account-slogan';
        $show_slogan            = YIT_Layout()->myaccount_show_slogan;
        $slogan                 = is_user_logged_in() ? YIT_Layout()->myaccount_welcome_text : YIT_Layout()->myaccount_login_text;
        $show_user_name         = YIT_Layout()->myaccount_show_user_name;

        if( 'yes' == $show_user_name && is_user_logged_in() ){
	        $current_user = get_user_by( 'id', get_current_user_id() );
            $slogan .= '['. $current_user->display_name . ']';
        }
    }

}

if ( empty( $show_slogan ) || $show_slogan == 'no' ) {
	return;
}

// main text tag
$tag = 'h2';
if ( '0' == YIT_Layout()->show_title ) {
    $tag = 'h1';
}

if ( ! empty( $slogan ) ) {
	$slogan = sprintf( '<%s><span>%s</span></%s>', $tag, str_replace( array( '[', ']' ), array( '</span><span class="title-highlight">', '</span><span>' ), $slogan ), $tag );
	$slogan = str_replace( '<span></span>', '', $slogan );
}
?>

<!-- START SLOGAN -->
<div id="slogan" <?php if( isset( $slogan_class ) ): echo 'class="' .$slogan_class . '"'; endif; ?> >
    <div class="container">
        <div class="slogan-wrapper">
            <?php echo $slogan ?>
            <?php if( ! empty( $subslogan ) ) : ?>
                <p><?php echo do_shortcode( $subslogan ) ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- END SLOGAN -->
