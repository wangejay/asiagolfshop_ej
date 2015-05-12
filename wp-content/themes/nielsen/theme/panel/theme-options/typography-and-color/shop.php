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
 * Return an array with the options for Theme Options > Typography and Color > Shop
 *
 * @package Yithemes
 * @author  Andrea Grillo <andrea.grillo@yithemes.com>
 * @author  Antonio La Rocca <antonio.larocca@yithemes.it>
 * @author  Francesco Licandro <francesco.licandro@yithemes.it>
 * @since   2.0.0
 * @return mixed array
 *
 */
return array(

    /* Typography and Color > Shop > General Settings */
    array(
        'type' => 'title',
        'name' => __( 'General Settings', 'yit' ),
        'desc' => ''
    ),

    array(
        'id'    => 'shop-general-background-section',
        'type'  => 'colorpicker',
        'name'  => __( 'General section background color', 'yit' ),
        'desc'  => __( 'Choose background color for shop section like cart totals or add to cart form.', 'yit' ),
        'std'   => array(
            'color' => '#fafafa'
        ),
        'style' => array(
            'selectors'     => '.single-product.woocommerce div.product div.summary form.cart,
                                #review-order-wrapper,
                                .woocommerce .coupon-form-checkout,
                                .woocommerce .login-form-checkout,
                                .woocommerce ul.order_info,
                                #review_form_wrapper #review_form,
                                #customer_login form.login',
            'properties'    => 'background-color'
        )
    ),

    array(
        'id'    => 'shop-in-stock-color',
        'type'  => 'colorpicker',
        'name'  => __( 'Shop "Stock Quantity" text color', 'yit' ),
        'desc'  => __( 'Select a text color for the "Stock Quantity" label.', 'yit' ),
        'std'   => array(
            'color' => '#85ad74'
        ),
        'style' => array(
            'selectors'  => '.woocommerce div.product .stock,
                             .woocommerce-page div.product .stock,
                             .wishlist_table tr td.product-stock-status span.wishlist-in-stock',
            'properties' => 'color'
        )
    ),

    /* Typography and Color > Shop > Shop Page */
    array(
        'type' => 'title',
        'name' => __( 'Shop Page', 'yit' ),
        'desc' => ''
    ),

    array(
        'id'              => 'shop-page-product-name-font',
        'type'            => 'typography',
        'name'            => __( 'Product title font', 'yit' ),
        'desc'            => __( 'Choose the font type, size and color.', 'yit' ),
        'min'             => 1,
        'max'             => 80,
        'default_font_id' => 'typography-website-title',
        'std'             => array(
            'size'      => 12,
            'unit'      => 'px',
            'family'    => 'default',
            'style'     => '600',
            'color'     => '#6d6c6c',
            'align'     => 'center',
            'transform' => 'uppercase',
        ),
        'style'           => array(
            'selectors'  => '.product-meta-wrapper h3.product-name,
                             .product-meta-wrapper h3.product-name a,
                             #product-nav > a h5,
                             .woocommerce table.cart td.product-name div.product-name a,
                             .widget.woocommerce ul.product_list_widget a .product_title,
                             .widget.featured-products div.info-featured-product .product_name,
                             .wishlist_table tr td.product-name a,
                             .added-to-cart-popup .added_to_cart h3.product-name,
                             .widget.yit_products_category ul.product_list_widget a .product_title,
                             .lookbook-listed-product .lookbook-information a,
                             .single-product.woocommerce div.product div.summary form.cart table.group_table tr td.label a,
                             .widget.yith-woocompare-widget ul.products-list li a',
            'properties' => 'font-size,
                              font-family,
                              font-weight,
                              color,
                              text-transform,
                              text-align'
        )
    ),

    array(
        'id'              => 'shop-page-product-price-font',
        'type'            => 'typography',
        'name'            => __( 'Product price font', 'yit' ),
        'desc'            => __( 'Choose the font type, size and color.', 'yit' ),
        'min'             => 1,
        'max'             => 80,
        'default_font_id' => 'typography-website-title',
        'std'             => array(
            'size'      => 14,
            'unit'      => 'px',
            'family'    => 'default',
            'style'     => '800',
            'color'     => '#6d6c6c',
            'align'     => 'center',
            'transform' => 'none',
        ),
        'style'           => array(
            'selectors'  => '.product-meta-wrapper .price,
                             .widget.woocommerce ul.product_list_widget span.product_price,
                             .widget.featured-products div.info-featured-product .price,
                             #yith-wcwl-form table.shop_table td.product-price,
                             .lookbook-listed-product .lookbook-information .lookbook-product-price,
                             .single-product.woocommerce div.product div.summary table.group_table .price',
            'properties' => 'font-size,
                              font-family,
                              font-weight,
                              color,
                              text-transform,
                              text-align'
        )
    ),

    array(
        'id'              => 'shop-page-product-button-font',
        'type'            => 'typography',
        'name'            => __( 'Product "Add to cart" font', 'yit' ),
        'desc'            => __( 'Choose the font type and size.', 'yit' ),
        'min'             => 1,
        'max'             => 80,
        'default_font_id' => 'typography-website-title',
        'std'             => array(
            'size'      => 10,
            'unit'      => 'px',
            'family'    => 'default',
            'style'     => '700',
            'align'     => 'center',
            'transform' => 'uppercase',
        ),
        'style'           => array(
            'selectors'  => 'ul.products li.product .product-wrapper .product-actions-wrapper .product-action-button a,
                             ul.products li.product .product-wrapper .product-actions-wrapper .product-action-button span,
                             .woocommerce .yith-ywraq-add-button .add-request-quote-button.button,.yith_ywraq_add_item_response_message,.yith_ywraq_add_item_browse_message',
            'properties' => 'font-size,
                              font-family,
                              font-weight,
                              text-transform,
                              text-align'
        )
    ),

    array(
        'id'            => 'shop-page-product-button-color',
        'type'          => 'colorpicker',
        'variations'    => array(
            'normal' => __( 'Normal', 'yit' ),
            'hover'  => __( 'Hover', 'yit' )
        ),
        'name' => __( 'Product "Add to cart" color', 'yit' ),
        'desc' => __( 'Select the colors to use for the "Add to cart" button.', 'yit' ),
        'std'  => array(
            'color' => array(
                'normal' => '#6d6c6c',
                'hover'  => '#ba1707'
            )
        ),
        'style' => array(
            'normal' => array(
                'selectors'   => 'ul.products li.product .product-wrapper .product-actions-wrapper .product-action-button a,
                                 .woocommerce .yith-ywraq-add-button .add-request-quote-button.button,.yith_ywraq_add_item_response_message,.yith_ywraq_add_item_response_message',
                'properties'  => 'color'
            ),
            'hover' => array(
                'selectors'   => 'ul.products li.product .product-wrapper .product-actions-wrapper .product-action-button a:hover,
                                  .woocommerce .yith-ywraq-add-button .add-request-quote-button.button:hover,.yith_ywraq_add_item_response_message:hover,.yith_ywraq_add_item_response_message:hover',
                'properties'  => 'color'
            )
        )
    ),

    array(
        'id'            => 'shop-page-product-button-background-border',
        'type'          => 'colorpicker',
        'variations'    => array(
            'background' => __( 'Background', 'yit' ),
            'border'  => __( 'Border', 'yit' )
        ),
        'name' => __( 'Product "Add to cart" background and border', 'yit' ),
        'desc' => __( 'Select background and border color to use for the "Add to cart" button.', 'yit' ),
        'std'  => array(
            'color' => array(
                'background' => 'transparent',
                'border'  => '#f2f2f2'
            )
        ),
        'style' => array(
            'background' => array(
                'selectors'   => 'ul.products li.product .product-wrapper .product-actions-wrapper .product-action-button',
                'properties'  => 'background-color'
            ),
            'border' => array(
                'selectors'   => 'ul.products li.product .product-wrapper .product-actions-wrapper .product-action-button',
                'properties'  => 'border-color'
            )
        )
    ),


    array(
        'id'              => 'shop-page-layout-selector',
        'type'            => 'typography',
        'name'            => __( 'Page and Layout Selector font', 'yit' ),
        'desc'            => __( 'Choose the font type, size and color.', 'yit' ),
        'min'             => 1,
        'max'             => 80,
        'default_font_id' => 'typography-website-title',
        'std'             => array(
            'size'      => 11,
            'unit'      => 'px',
            'family'    => 'default',
            'style'     => '600',
            'color'     => '#6d6c6c',
            'align'     => 'left',
            'transform' => 'uppercase',
        ),
        'style'           => array(
            'selectors'  => '#list-or-grid span, #number-of-products span,
                            #page-meta form.woocommerce-ordering .sbHolder .sbSelector,
                            #header-search .search_categories,
                            #page-meta .woocommerce-ordering .sbHolder .sbOptions li a',
            'properties' => 'font-size,
                              font-family,
                              font-weight,
                              color,
                              text-transform,
                              text-align'
        )
    ),

    array(
        'id'              => 'shop-notice-font',
        'type'            => 'typography',
        'name'            => __( 'Woocommerce Notice font', 'yit' ),
        'desc'            => __( 'Choose the font type and size.', 'yit' ),
        'min'             => 1,
        'max'             => 80,
        'default_font_id' => 'typography-website-paragraph',
        'std'             => array(
            'size'      => 13,
            'unit'      => 'px',
            'family'    => 'default',
            'style'     => '600',
            'align'     => 'left',
            'transform' => 'none',
        ),
        'style'           => array(
            'selectors'  => '.woocommerce-info, .woocommerce-message, .woocommerce-error li, .login-form-checkout > p, .coupon-form-checkout > p',
            'properties' => 'font-size,
                              font-family,
                              font-weight,
                              text-transform,
                              text-align'
        )
    ),

    array(
        'id'    => 'shop-out-of-stock-color',
        'type'  => 'colorpicker',
        'name'  => __( 'Shop "Out of Stock" text color', 'yit' ),
        'desc'  => __( 'Select a text color for the "Out of Stock" label.', 'yit' ),
        'std'   => array(
            'color' => '#ff1800'
        ),
        'style' => array(
            'selectors'  => 'ul.products li.product .product-wrapper .product-actions-wrapper .product-action-button span.out-of-stock',
            'properties' => 'color'
        )
    ),

    /* Typography and Color > Shop > Product Detail Page */

    array(
        'type' => 'title',
        'name' => __( 'Single Product Page', 'yit' ),
        'desc' => ''
    ),

    array(
        'id'              => 'shop-single-product-name-font',
        'type'            => 'typography',
        'name'            => __( 'Product name font', 'yit' ),
        'desc'            => __( 'Choose the font type, size and color.', 'yit' ),
        'min'             => 1,
        'max'             => 80,
        'default_font_id' => 'typography-website-title',
        'std'             => array(
            'size'      => 22,
            'unit'      => 'px',
            'family'    => 'default',
            'style'     => '700',
            'color'     => '#6d6c6c',
            'align'     => 'left',
            'transform' => 'uppercase',
        ),
        'style'           => array(
            'selectors'  => '.single-product.woocommerce div.product div.summary h1,
                             .single-product.woocommerce div.product div.product-title-section h1',
            'properties' => 'font-size,
                              font-family,
                              font-weight,
                              color,
                              text-transform,
                              text-align'
        )
    ),

    array(
        'id'              => 'shop-single-product-price-font',
        'type'            => 'typography',
        'name'            => __( 'Product price font', 'yit' ),
        'desc'            => __( 'Choose the font type, size and color.', 'yit' ),
        'min'             => 1,
        'max'             => 80,
        'default_font_id' => 'typography-website-title',
        'std'             => array(
            'size'      => 18,
            'unit'      => 'px',
            'family'    => 'default',
            'style'     => '700',
            'color'     => '#6d6c6c',
            'align'     => 'left',
            'transform' => 'none',
        ),
        'style'           => array(
            'selectors'  => '.single-product.woocommerce div.product div.summary .price, .woocommerce.sc_add_to_cart .sc_add_to_cart_price .amount',
            'properties' => 'font-size,
                              font-family,
                              font-weight,
                              color,
                              text-transform,
                              text-align'
        )
    ),

    array(
        'id'              => 'shop-single-product-label-font',
        'type'            => 'typography',
        'name'            => __( 'Product page label font', 'yit' ),
        'desc'            => __( 'Choose the font type, size and color.', 'yit' ),
        'min'             => 1,
        'max'             => 80,
        'default_font_id' => 'typography-website-title',
        'std'             => array(
            'size'      => 13,
            'unit'      => 'px',
            'family'    => 'default',
            'style'     => '700',
            'color'     => '#5b5a5a',
            'align'     => 'left',
            'transform' => 'uppercase',
        ),
        'style'           => array(
            'selectors'  => '.single-product.woocommerce div.product div.summary form.cart h4,
                            .single-product.woocommerce div.product form.cart ul.variations label,
                            .share-link-wrapper .share-label,
                            div.summary.entry-summary form.variations_form.cart .single_variation_wrap h4,
                            div.product-inquiry span.inquiry-title,
                            #modal-window .modal-opener a',
            'properties' => 'font-size,
                              font-family,
                              font-weight,
                              color,
                              text-transform,
                              text-align'
        )
    ),

    array(
        'id'              => 'shop-single-product-tabs-font',
        'type'            => 'typography',
        'name'            => __( 'Product tabs title font', 'yit' ),
        'desc'            => __( 'Choose the font type, size and color.', 'yit' ),
        'min'             => 1,
        'max'             => 80,
        'default_font_id' => 'typography-website-title',
        'std'             => array(
            'size'      => 14,
            'unit'      => 'px',
            'family'    => 'default',
            'style'     => '600',
            'color'     => '#a5a5a5',
            'align'     => 'center',
            'transform' => 'uppercase',
        ),
        'style'           => array(
            'selectors'  => '.single-product.woocommerce div.woocommerce-tabs ul.tabs li a,
                             .tabs-container ul.tabs li a,
                             .wpb_content_element.wpb_tabs .ui-tabs > ul li a',
            'properties' => 'font-size,
                              font-family,
                              font-weight,
                              color,
                              text-transform,
                              text-align'
        )
    ),

    array(
        'id'    => 'single-out-of-stock-color',
        'type'  => 'colorpicker',
        'name'  => __( 'Single Page "Out of Stock" text color', 'yit' ),
        'desc'  => __( 'Select a text color for the "Out of Stock" label.', 'yit' ),
        'std'   => array(
            'color' => '#6d6c6c'
        ),
        'style' => array(
            'selectors'  => '.single-product.woocommerce div.product div.summary p.stock.out-of-stock',
            'properties' => 'color'
        )
    ),


    /* Typography and Color > Shop > My-Account page */
    array(
        'type' => 'title',
        'name' => __( 'My Account Page', 'yit' ),
        'desc' => ''
    ),

    array(
        'id'              => 'my-account-page-menu-font',
        'type'            => 'typography',
        'name'            => __( 'My Account sidebar menu font', 'yit' ),
        'desc'            => __( 'Choose the font type and size.', 'yit' ),
        'min'             => 1,
        'max'             => 80,
        'default_font_id' => 'typography-website-title',
        'std'             => array(
            'size'      => 13,
            'unit'      => 'px',
            'family'    => 'default',
            'style'     => '400',
            'align'     => 'left',
            'transform' => 'uppercase',
        ),
        'style'           => array(
            'selectors'  => '#my-account-sidebar ul li > a',
            'properties' => 'font-size,
                              font-family,
                              font-weight,
                              text-transform,
                              text-align'
        )
    ),

    array(
        'id'            => 'my-account-page-menu-color',
        'type'          => 'colorpicker',
        'variations'    => array(
            'normal' => __( 'Normal', 'yit' ),
            'hover'  => __( 'Hover', 'yit' )
        ),
        'name' => __( 'My Account sidebar menu color', 'yit' ),
        'desc' => __( 'Select the colors to use for the my account menu.', 'yit' ),
        'std'  => array(
            'color' => array(
                'normal' => '#9c9c9c',
                'hover'  => '#0e0d0d'
            )
        ),
        'style' => array(
            'normal' => array(
                'selectors'   => '#my-account-sidebar ul li > a',
                'properties'  => 'color'
            ),
            'hover' => array(
                'selectors'   => '#my-account-sidebar ul li > a:hover,
                                  #my-account-sidebar ul li > a.active',
                'properties'  => 'color'
            )
        )
    ),


    array(
        'id'              => 'my-account-content-title-font',
        'type'            => 'typography',
        'name'            => __( 'My Account content title font', 'yit' ),
        'desc'            => __( 'Choose the font type, size and color.', 'yit' ),
        'min'             => 1,
        'max'             => 80,
        'default_font_id' => 'typography-website-title',
        'std'             => array(
            'size'      => 15,
            'unit'      => 'px',
            'family'    => 'default',
            'style'     => '700',
            'color'     => '#6d6c6c',
            'align'     => 'left',
            'transform' => 'uppercase',
        ),
        'style'           => array(
            'selectors'  => '#my-account-content h2,
                             #my-account-sidebar .user-profile span.username',
            'properties' => 'font-size,
                              font-family,
                              font-weight,
                              color,
                              text-transform,
                              text-align'
        )
    ),


    array(
        'type' => 'title',
        'name' => __( 'Widget List Products', 'yit' ),
        'desc' => ''
    ),


    array(
        'id'              => 'shop-widget-price-font',
        'type'            => 'typography',
        'name'            => __( 'Widget Product price font', 'yit' ),
        'desc'            => __( 'Choose the font type, size and color.', 'yit' ),
        'min'             => 1,
        'max'             => 80,
        'default_font_id' => 'typography-website-title',
        'std'             => array(
            'size'      => 14,
            'unit'      => 'px',
            'family'    => 'default',
            'style'     => '400',
            'color'     => '#939393',
            'align'     => 'left',
            'transform' => 'none',
        ),
        'style'           => array(
            'selectors'  => '.widget.woocommerce ul.product_list_widget a span.product_price,
                            .single-product.woocommerce ul.product_list_widget a span.product_price,
                             .widget.yit_products_category a span.product_price',
            'properties' => 'font-size,
                              font-family,
                              font-weight,
                              color,
                              text-transform,
                              text-align'
        )
    ),

    /* Typography and Color > Shop > General Settings */
    array(
        'type' => 'title',
        'name' => __( 'Cart Header Widget', 'yit' ),
        'desc' => ''
    ),

    array(
        'id'              => 'shop-cart-header-widget-label-font',
        'type'            => 'typography',
        'name'            => __( 'Cart header title', 'yit' ),
        'desc'            => __( 'Select the font to use for the title before the products list.', 'yit' ),
        'min'             => 1,
        'max'             => 80,
        'default_font_id' => 'typography-website-title',
        'std'             => array(
            'size'      => 12,
            'unit'      => 'px',
            'family'    => 'default',
            'style'     => '700',
            'color'     => '#422d2d',
            'align'     => 'left',
            'transform' => 'uppercase',
        ),
        'style'           => array(
            'selectors'  => '#header .widget_shopping_cart .widget_shopping_cart_content h5.list-title, #header .widget_shopping_cart .widget_shopping_cart_content p.total,
            .widget_shopping_cart .widget_shopping_cart_content .total span',
            'properties' => 'font-size,
                              font-family,
                              font-weight,
                              color,
                              text-transform,
                              text-align'
        )
    ),

    array(
        'id'         => 'shop-cart-header-widget-link-colors',
        'type'       => 'colorpicker',
        'variations' => array(
            'normal'     => __( 'Link', 'yit' ),
            'hover' => __( 'Link hover', 'yit' ),
        ),
        'name'       => __( 'Cart Header Widget Link Color', 'yit' ),
        'desc'       => __( 'Select the colors to use for the header cart link', 'yit' ),
        'std'        => array(
            'color' => array(
                'normal'    => '#6d6c6c',
                'hover'     => '#ba1707',
            )
        ),
        'style'      => array(
            'normal'     => array(
                'selectors'  => '
                .woocommerce #header-sidebar > div.yit_cart_widget .product_list_widget .mini-cart-item-info a,
                #header-sidebar > div.yit_cart_widget .product_list_widget .mini-cart-item-info a,
                .widget_shopping_cart .widget_shopping_cart_content .mini-cart-item-info a:visited,
                div.yit_cart_widget .product_list_widget .mini-cart-item-info a,
                .widget_shopping_cart .widget_shopping_cart_content .mini-cart-item-info a,
                .woocommerce #header-sidebar > div.yit_cart_widget .product_list_widget .mini-cart-item-info .subtotal,
                #header .widget_shopping_cart .widget_shopping_cart_content .total span.amount,
                .widget_shopping_cart .widget_shopping_cart_content a.remove,
                .widget.woocommerce.widget_recent_reviews ul.product_list_widget li a,
                #header-sidebar > div.yit_cart_widget .product_list_widget .mini-cart-item-info .subtotal',
                'properties' => 'color'
            ),
            'hover' => array(
                'selectors'  => '
                .woocommerce #header-sidebar > div.yit_cart_widget .product_list_widget .mini-cart-item-info a:hover,
                #header-sidebar > div.yit_cart_widget .product_list_widget .mini-cart-item-info a:hover,
                div.yit_cart_widget .product_list_widget .mini-cart-item-info a:hover,
                .widget.woocommerce.widget_recent_reviews ul.product_list_widget li a:hover,
                .widget_shopping_cart .widget_shopping_cart_content a.remove:hover',
                'properties' => 'color'
            )
        )
    ),

    array(
        'id'         => 'shop-cart-header-widget-colors',
        'type'       => 'colorpicker',
        'variations' => array(
            'border'     => __( 'Border', 'yit' ),
            'background' => __( 'Background', 'yit' ),
        ),
        'name'       => __( 'Cart Header Widget Colors', 'yit' ),
        'desc'       => __( 'Select the colors to use for the header cart widget border and background', 'yit' ),
        'std'        => array(
            'color' => array(
                'border'     => '#dbd8d8',
                'background' => '#ffffff',
            )
        ),
        'style'      => array(
            'border'     => array(
                'selectors'  => '.woocommerce #header-sidebar .yit_cart_widget .cart_wrapper .widget_shopping_cart_content, #header-sidebar .yit_cart_widget .cart_wrapper .widget_shopping_cart_content',
                'properties' => 'border-color'
            ),
            'background' => array(
                'selectors'  => '.woocommerce #header-sidebar .yit_cart_widget .cart_wrapper .widget_shopping_cart_content, #header-sidebar .yit_cart_widget .cart_wrapper .widget_shopping_cart_content',
                'properties' => 'background'
            )
        )
    )
);

