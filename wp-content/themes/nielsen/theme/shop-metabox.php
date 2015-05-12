<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! defined( 'YIT' ) ) {
    exit( 'Direct access forbidden.' );
}

return array(
    'myaccount' => array(
        'myaccount_sep'  => array(
             'type' => 'sep'
         ),
        'myaccount_show_slogan' => array(
            'label' => __( 'Show Regency Style Slogan', 'yit' ),
            'desc'  => __( 'Enable Regency Style Slogan in the header', 'yit' ),
            'type'  => 'onoff',
            'std'   => 'yes',
        ),
        'myaccount_show_user_name' => array(
            'label' => __( 'Show User Name (for logged in users)', 'yit' ),
            'desc'  => __( 'Enable to show the user name', 'yit' ),
            'type'  => 'onoff',
            'std'   => 'yes',
            'deps'  => array(
                'ids'       => '_myaccount_show_slogan',
                'values'    => 'yes'
            )
        ),
        'myaccount_welcome_text' => array(
            'label' => __( 'Welcome Text (for logged in users)', 'yit' ),
            'desc'  => __( 'Insert the myaccount welcome text', 'yit' ),
            'type'  => 'text',
            'std'   => __( 'Hi,', 'yit' ),
            'deps'  => array(
                'ids'       => '_myaccount_show_slogan',
                'values'    => 'yes'
            )
        ),
        'myaccount_login_text' => array(
            'label' => __( 'Login Text (for not logged in users)', 'yit' ),
            'desc'  => __( 'Insert the myaccount login text', 'yit' ),
            'type'  => 'text',
            'std'   => __( 'Login [Register]', 'yit' ),
            'deps'  => array(
                'ids'       => '_myaccount_show_slogan',
                'values'    => 'yes'
            )
        ),
        'myaccount_background_image' => array(
            'label' => __( 'Background Image', 'yit' ),
            'desc'  => __( 'Insert the myaccount background image', 'yit' ),
            'type'  => 'upload',
            'std'   => YIT_URL . '/woocommerce/images/my-account-slogan.jpg',
            'deps'  => array(
                'ids'       => '_myaccount_show_slogan',
                'values'    => 'yes'
            )
        ),
        'myaccount_text_color' => array(
            'label' => __( 'Text Color', 'yit' ),
            'desc'  => __( 'Insert the myaccount text color', 'yit' ),
            'type'  => 'colorpicker',
            'std'   => '#ffffff',
            'deps'  => array(
                'ids'       => '_myaccount_show_slogan',
                'values'    => 'yes'
            )
        ),
        'myaccount_text_background' => array(
            'label' => __( 'Text Background Color', 'yit' ),
            'desc'  => __( 'Insert the myaccount text background color', 'yit' ),
            'type'  => 'colorpicker',
            'std'   => '#000000',
            'deps'  => array(
                'ids'       => '_myaccount_show_slogan',
                'values'    => 'yes'
            )
        ),
        'myaccount_highlight_background' => array(
            'label' => __( 'User Background Color', 'yit' ),
            'desc'  => __( 'Insert the myaccount user background color', 'yit' ),
            'type'  => 'colorpicker',
            'std'   => '#c11200',
            'deps'  => array(
                'ids'       => '_myaccount_show_slogan',
                'values'    => 'yes'
            )
        ),
    ),

    'checkout'  => array(
        'checkout_sep'  => array(
             'type' => 'sep'
         ),
        'checkout_show_slogan' => array(
            'label' => __( 'Show Regency Style Slogan', 'yit' ),
            'desc'  => __( 'Enable Regency Style Slogan in the header', 'yit' ),
            'type'  => 'onoff',
            'std'   => 'yes',
        ),
        'checkout_cart_empty_text' => array(
            'label' => __( 'Cart Empty Text', 'yit' ),
            'desc'  => __( 'Insert the cart empty text.', 'yit' ),
            'type'  => 'text',
            'std'   => __( 'YOUR CART IS EMPTY', 'yit' ),
            'deps'  => array(
                'ids'       => '_checkout_show_slogan',
                'values'    => 'yes'
            )
        ),
        'checkout_cart_text' => array(
            'label' => __( 'Cart Breadcrumb Text', 'yit' ),
            'desc'  => __( 'Insert the cart breadcrumb text ', 'yit' ),
            'type'  => 'text',
            'std'   => __( 'SHOPPING BAG', 'yit' ),
            'deps'  => array(
                'ids'       => '_checkout_show_slogan',
                'values'    => 'yes'
            )
        ),
        'checkout_text' => array(
            'label' => __( 'Checkout Breadcrumb Text', 'yit' ),
            'desc'  => __( 'Insert the checkout breadcrumb text ', 'yit' ),
            'type'  => 'text',
            'std'   => __( 'CHECKOUT DETAILS', 'yit' ),
            'deps'  => array(
                'ids'       => '_checkout_show_slogan',
                'values'    => 'yes'
            )
        ),
        'checkout_order_complete_text' => array(
            'label' => __( 'Order Complete Breadcrumb Text', 'yit' ),
            'desc'  => __( 'Insert the order complete breadcrumb text ', 'yit' ),
            'type'  => 'text',
            'std'   => __( 'ORDER COMPLETE', 'yit' ),
            'deps'  => array(
                'ids'       => '_checkout_show_slogan',
                'values'    => 'yes'
            )
        ),
        'checkout_background_image' => array(
            'label' => __( 'Background Image', 'yit' ),
            'desc'  => __( 'Insert the Background image', 'yit' ),
            'type'  => 'upload',
            'std'   => YIT_URL . '/woocommerce/images/cart-checkout-slogan.jpg',
            'deps'  => array(
                'ids'       => '_checkout_show_slogan',
                'values'    => 'yes'
            )
        ),
        'checkout_text_color' => array(
            'label' => __( 'Breadcrumb Text Color', 'yit' ),
            'desc'  => __( 'Insert the breadcrumb text color', 'yit' ),
            'type'  => 'colorpicker',
            'std'   => '#fffff',
            'deps'  => array(
                'ids'       => '_checkout_show_slogan',
                'values'    => 'yes'
            )
        ),
        'checkout_text_background_color' => array(
            'label' => __( 'Breadcrumb background color', 'yit' ),
            'desc'  => __( 'Select a background color for the text of breadcrumb', 'yit' ),
            'type'  => 'colorpicker',
            'std'   => '#000000',
            'deps'  => array(
                'ids'       => '_checkout_show_slogan',
                'values'    => 'yes'
            )
        ),
        'checkout_active_text_color' => array(
            'label' => __( 'Active breadcrumb Text Color', 'yit' ),
            'desc'  => __( 'Insert the active breadcrumb text color', 'yit' ),
            'type'  => 'colorpicker',
            'std'   => '#ffffff',
            'deps'  => array(
                'ids'       => '_checkout_show_slogan',
                'values'    => 'yes'
            )
        ),
        'checkout_active_text_background_color' => array(
            'label' => __( 'Breadcrumb background color', 'yit' ),
            'desc'  => __( 'Select a background color for the text of active breadcrumb', 'yit' ),
            'type'  => 'colorpicker',
            'std'   => '#c11200',
            'deps'  => array(
                'ids'       => '_checkout_show_slogan',
                'values'    => 'yes'
            )
        ),
    ),

    'remove'    => array(
        'sep1',
        'show_slogan',
        'slogan',
        'slogan_color',
        'sub_slogan',
        'subslogan_color',
        'slogan_border',
        'slogan_border_color',
        'slogan_image_background',
        'slogan_image_height',
        'slogan_bg_color',
        'slogan_bg_image',
        'slogan_bg_repeat',
        'slogan_bg_position',
        'slogan_bg_attachment',
    )
);