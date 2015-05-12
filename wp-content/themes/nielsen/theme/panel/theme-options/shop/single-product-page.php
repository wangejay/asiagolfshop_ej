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
 * Return an array with the options for Theme Options > Shop > Single Product Page
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

    /* Shop > Single Product Page Settings */
    array(
        'type' => 'title',
        'name' => __( 'Single Product Page', 'yit' ),
        'desc' => ''
    ),

    array(
        'id' => 'shop-single-product-nav',
        'type' => 'onoff',
        'name' => __( 'Show nav prev/next link', 'yit' ),
        'desc' => __( 'Say if you want to show product navigation', 'yit' ),
        'std' => 'yes'
    ),

    array(
        'id' => 'shop-nav-in-category',
        'type' => 'onoff',
        'name' => __( 'Nav in same category', 'yit' ),
        'desc' => __( 'Select it to navigate in the same product category of the displayed product', 'yit' ),
        'std' => 'no',
        'deps' => array(
            'ids' => 'shop-single-product-nav',
            'values' => 'yes'
        )
    ),

    array(
        'id' => 'shop-single-product-name',
        'type' => 'onoff',
        'name' => __( 'Show name', 'yit' ),
        'desc' => __( 'Say if you want to show product name', 'yit' ),
        'std' => 'yes'
    ),

    array(
        'id' => 'shop-single-product-price',
        'type' => 'onoff',
        'name' => __( 'Show price', 'yit' ),
        'desc' => __( 'Select if you want to show price.', 'yit' ),
        'std' => 'yes'
    ),

    array(
        'id' => 'shop-single-add-to-cart',
        'type' => 'onoff',
        'name' => __( 'Show button add to cart', 'yit' ),
        'desc' => __( 'Select if you want to show purchase button.', 'yit' ),
        'std' => 'yes'
    ),

    array(
        'id' =>'shop-single-show-wishlist',
        'type' => 'onoff',
        'name' => __( 'Show wishlist button', 'yit' ),
        'desc' => __( 'Say if you want to show wishlist button.', 'yit' ),
        'std' => 'yes'
    ),

    array(
        'id' => 'shop-single-share',
        'type' => 'onoff',
        'name' => __( 'Show share link', 'yit' ),
        'desc' => __( 'Say if you want to show link for sharing product.', 'yit' ),
        'std' => 'yes'
    ),

    array(
        'id' => 'shop-single-compare',
        'type' => 'onoff',
        'name' => __( 'Show compare button', 'yit' ),
        'desc' => __( 'Say if you want to show compare button.', 'yit' ),
        'std' => 'yes'
    ),

    array(
        'id' => 'shop-single-metas',
        'type' => 'onoff',
        'name' => __( 'Show product metas (categories and tags)', 'yit' ),
        'desc' => __( 'Say if you want to show product metas in your single product page. It also remove Brands if you are using WooCommerce Brands Addon.', 'yit' ),
        'std' => 'yes'
    ),

    array(
        'id' => 'shop-remove-reviews',
        'type' => 'onoff',
        'name' => __( 'Remove reviews tab', 'yit' ),
        'desc' => __( 'Say if you want to remove reviews tab from all products', 'yit' ),
        'std' => 'no'
    ),

    array(
        'id' => 'shop-show-reviews-tab-opened',
        'type' => 'onoff',
        'name' => __( 'Show reviews tab opened', 'yit' ),
        'desc' => __( 'Say if you want to show reviews tab opened instead of description tab', 'yit' ),
        'std' => 'yes' ,
        'deps' => array(
            'ids' => 'shop-remove-reviews',
            'values' => 'no'
        )
    ),

    /* Shop > Related Products Settings */

    array(
        'type' => 'title',
        'name' => __( 'Related products', 'yit' ),
        'desc' => ''
    ),

    array(
        'id' => 'shop-show-related',
        'type' => 'onoff',
        'name' => __( 'Show related products', 'yit' ),
        'desc' => __( 'Select if you want to display Related Products.', 'yit' ),
        'std' => 'yes'
    ),

    array(
        'id' => 'shop-related-slider',
        'type' => 'onoff',
        'name' => __( 'Enable related slider', 'yit' ),
        'desc' => __( 'Select if you want to show related products as slider.' ,'yit' ),
        'std' => 'no',
        'deps' => array(
            'ids' => 'shop-show-related',
            'values' => 'yes'
        )
    ),

    array(
        'id' => 'shop-show-custom-related',
        'type' => 'onoff',
        'name' => __( 'Custom Related Products number', 'yit' ),
        'desc' => __( 'Select if you want to customize the number of Related Products. Note: if you are already using a custom filter to do that, please don\'t enable this option', 'yit' ),
        'std' => 'no',
        'deps' => array(
            'ids' => 'shop-show-related',
            'values' => 'yes'
        )
    ),

    array(
        'id' => 'shop-number-related',
        'type' => 'number',
        'name' => __( 'Number of Related Products', 'yit' ),
        'desc' => __( 'Set the total numbers of the related products displayed, on the product detail page. Note: related products are displayed randomly from Woocommerce. Sometimes the number of related products could be less than the number of items selected. This number depends from the query plugin, not from the theme.', 'yit' ),
        'std' => 3,
        'deps' => array(
            'ids' => 'shop-show-custom-related',
            'values' => 'yes'
        )
    ),

    ! function_exists( 'YIT_Contact_Form' ) ? false : array(
        'type' => 'title',
        'name' => __( 'Inquiry Form Options', 'yit' ),
        'desc' => ''
    ),

    ! function_exists( 'YIT_Contact_Form' ) ? false : array(
        'id' => 'shop-inquiry-title',
        'type' => 'text',
        'name' => __( 'Inquiry box title', 'yit' ),
        'desc' => __( 'Set inquiry box title', 'yit' ),
        'std' => __( 'Send an inquiry', 'yit' )
    ),

    ! function_exists( 'YIT_Contact_Form' ) ? false : array(
        'id'      => 'shop-inquiry-title-icon',
        'type'    => 'icon-list',
        'options' => array(
            'select' => array(
                'icon'   => __( 'Theme Icon', 'yit' ),
                'custom' => __( 'Custom Icon', 'yit' ),
                'none'   => __( 'None', 'yit' )
            ),
            'icon'   => YIT_Plugin_Common::get_icon_list(),
        ),
        'name'    => __( 'Show inquiry icon', 'yit' ),
        'desc'    => __( 'Select the icon for inquiry box title. Note: Custom icon size will be scaled to 25x25', 'yit' ),
        'std'     => array(
            'select' => 'icon',
            'icon'   => 'retinaicon-font:retina-the-essentials-082',
            'custom' => ''
        )
    ),

    ! function_exists( 'YIT_Contact_Form' ) ? false : array(
        'id' => 'shop-single-product-contact-form',
        'type' => 'select',
        'name' => __( 'Inquiry form', 'yit' ),
        'desc' => __( 'Select contact form type. Note: First you must create one contact form on plugin YIT Contact Form', 'yit' ),
        'options' => apply_filters( 'yit_get_contact_forms', array() ),
        'std' => false
    )

);

