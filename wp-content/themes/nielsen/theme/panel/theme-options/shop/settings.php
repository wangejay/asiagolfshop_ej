<?php
/**
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Return an array with the options for Theme Options > Shop > General Settings
 *
 * @package Yithemes
 * @author Andrea Grillo <andrea.grillo@yithemes.com>
 * @author Antonio La Rocca <antonio.larocca@yithemes.it>
 * @author Francesco Licandro <francesco.licandro@yithemes.it>
 * @since 2.0.0
 * @return mixed array
 *
 */
return array(

    /* Shop > General Settings */
    array(
        'type' => 'title',
        'name' => __( 'General Settings', 'yit' ),
        'desc' => ''
    ),

    array(
        'id' => 'shop-enable',
        'type' => 'onoff',
        'name' => __( 'Enable shop features', 'yit' ),
        'desc' => __( 'Say if you want to enable the shop features. If the option is disabled, products cannot be added to cart.', 'yit' ),
        'std' => 'yes'
    ),

    array(
        'id' => 'shop-products-per-page',
        'type' => 'number',
        'min' => 1,
        'max' => 50,
        'name' => __( 'Products per page', 'yit' ),
        'desc' => __( 'Say how many products to show per page, in the shop pages. ', 'yit' ),
        'std' => 12
    ),

    array(
        'id' => 'shop-products-per-row-mobile',
        'type' => 'select',
        'options' => array(
            '1' => __( 'One', 'yit' ),
            '2' => __( 'Two', 'yit' ),
        ),
        'name' => __( 'Products per row on mobile phone', 'yit' ),
        'desc' => __( 'Say how many products to show per row in the shop pages and in shortcodes on mobile phone.', 'yit' ),
        'std' => '2'
    ),

    array(
        'id' => 'shop-enable-vat',
        'type' => 'onoff',
        'name' => __( 'Enable VAT field', 'yit' ),
        'desc' => __( 'Choose if you want to enable VAT field for Customer.', 'yit' ),
        'std' => 'no'
    ),

    array(
        'id' => 'shop-enable-ssn',
        'type' => 'onoff',
        'name' => __( 'Enable SSN field', 'yit' ),
        'desc' => __( 'Choose if you want to enable SSN field for Customer.', 'yit' ),
        'std' => 'no'
    ),

    array(
        'id' => 'shop-buy-now-button',
        'type' => 'onoff',
        'name' => __( 'Enable "Buy Now" button', 'yit' ),
        'desc' => __( 'Choose if you want to go in checkout directly.', 'yit' ),
        'std' => 'no'
    ),

    array(
        'id' => 'shop-buy-now-text',
        'type' => 'text',
        'name' => __( 'Set "Buy Now" text', 'yit' ),
        'desc' => __( "Choose the text to display within the 'buy now' button. This will not work for external products. For them it's handled directly in the product admin page.", 'yit' ),
        'std' => 'Buy Now!',
        'deps' => array(
            'ids' => 'shop-buy-now-button',
            'values' => 'yes'
        )
    ),

    array(
        'id' => 'shop-add-to-cart-text',
        'type' => 'text',
        'name' => __( 'Set "Add to Cart" text', 'yit' ),
        'desc' => __( "Choose the text to display within the add to cart button. This will not work for external products. For them it's handled directly in the product admin page.", 'yit' ),
        'std' => 'Add to cart',
        'deps' => array(
            'ids' => 'shop-buy-now-button',
            'values' => 'no'
        )
    ),

    array(
        'id' => 'shop-enable-button-icon',
        'type' => 'onoff',
        'name' => __( 'Show icon in shop button', 'yit' ),
        'desc' => __( 'Enable icon for button in shop loop and shortcodes', 'yit' ),
        'std' => 'yes'
    ),

    array(
        'id' => 'shop-add-to-cart-icon',
        'type' => 'upload',
        'name' => __( 'Select "Add to cart" and "Buy Now" icon', 'yit' ),
        'desc' => __( 'Choose icon for "add to cart" and "buy now" button in shop loop and shortcodes', 'yit' ),
        'std' => YIT_THEME_ASSETS_URL . '/images/cart.png',
        'deps' => array(
            'ids' => 'shop-enable-button-icon',
            'values' => 'yes'
        )
    ),

    array(
        'id' => 'shop-set-options-icon',
        'type' => 'upload',
        'name' => __( 'Select "Set Options" and "View details" icon', 'yit' ),
        'desc' => __( 'Choose icon for "Set Options" and "View Details" button in shop loop and shortcodes', 'yit' ),
        'std' => YIT_THEME_ASSETS_URL . '/images/icon-set-options.png',
        'deps' => array(
            'ids' => 'shop-enable-button-icon',
            'values' => 'yes'
        )
    ),


    array(
        'type' => 'title',
        'name' => __( 'Mini Cart Settings', 'yit' ),
        'desc' => ''
    ),

	array(
		'id' => 'shop-mini-cart-show-in-header',
		'type' => 'onoff',
		'name' => __( 'Show mini cart in header', 'yit' ),
		'desc' => __( "Define if show the mini cart in header", 'yit' ),
		'std' => 'yes'
	),

    array(
        'id' => 'shop-mini-cart-icon',
        'type' => 'upload',
        'name' => __( 'Set Custom Mini Cart Icon', 'yit' ),
        'desc' => __( "Choose the image to display as minicart background", 'yit' ),
        'std' => YIT_THEME_ASSETS_URL . '/images/cart.png',
        'in_skin' => true
    ),

    array(
        'id' => 'shop-mini-cart-total-items',
        'type' => 'onoff',
        'name' => __( 'Count All Items in the cart', 'yit' ),
        'desc' => __( "It changes the way like the cart in the header count items. If ON, everytime you add an item to the cart (also if the item already is in the cart) the quantity will be increased. If OFF, multiple items of the same type will be counted only one time.", 'yit' ),
        'std' => 'no'
    ),

    array(
            'id' => 'shop-mini-cart-label-text',
            'type' => 'text',
            'name' => __( 'Label text in the cart', 'yit' ),
            'desc' => __( 'It changes the label text in mini cart widget header', 'yit' ),
            'std' =>  __('Cart', 'yit' )
    ),

    array(
        'id' => 'shop-mini-cart-scrollable',
        'type' => 'onoff',
        'name' => __( 'Make the minicart scrollable', 'yit' ),
        'desc' => __( "Define if the scrollbar appear when the cart have more than two products", 'yit' ),
        'std' => 'no'
    ),

    array(
        'type' => 'title',
        'name' => __( 'Checkout Page', 'yit' ),
        'desc' => ''
    ),

    array(
        'id' => 'shop-checkout-form-coupon',
        'type' => 'onoff',
        'name' => __( 'Enable Form Coupon', 'yit' ),
        'desc' => __( 'Choose if you want to show form coupon in checkout page', 'yit' ),
        'std' => 'yes'
    ),

    array(
        'type' => 'title',
        'name' => __( 'My Account Page', 'yit' ),
        'desc' => ''
    ),

    array(
        'id' => 'my-account-download',
        'type' => 'onoff',
        'name' => __( 'Enable Download Section', 'yit'),
        'desc' => __( 'Select if you want to enable download section on my-account page', 'yit'),
        'std' => 'yes',
    )
);


