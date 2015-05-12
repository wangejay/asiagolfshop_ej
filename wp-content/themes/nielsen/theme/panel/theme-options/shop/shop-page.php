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
 * Return an array with the options for Theme Options > Shop > Shop Page
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

    /* Shop > Shop Page Settings */
    array(
        'type' => 'title',
        'name' => __( 'Shop Page', 'yit' ),
        'desc' => ''
    ),

    array(
        'id' => 'shop-show-page-title',
        'type' => 'onoff',
        'name' => __( 'Show page title', 'yit' ),
        'desc' => __( 'Activate/Deactivate the page title on shop page.', 'yit' ),
        'std' => 'no'
    ),

    array(
        'id' => 'shop-view-type',
        'type' => 'select',
        'options' => array(
            'list' => __( 'List View', 'yit'),
            'grid' => __( 'Grid View', 'yit'),
            'masonry_item' => __( 'Masonry View', 'yit')
        ),
        'name' => __( 'Shop layout', 'yit' ),
        'desc' => __( 'Select the default layout for the page shop.', 'yit' ),
        'std' => 'grid'
    ),

    array(
        'id' => 'shop-custom-num-column',
        'type' => 'onoff',
        'name' => __( 'Custom number of products per row', 'yit' ),
        'desc' => __( 'Say if you want to show custom number of products per row instead of default value. Not available on mobile devices', 'yit' ),
        'std' => 'no',
        'deps' => array(
            'ids' => 'shop-view-type',
            'values' => 'grid,masonry_item'
        ),
    ),

    array(
        'id' => 'shop-num-column',
        'type' => 'select',
        'options' => array(
            1 => __( 'One', 'yit'),
            2 => __( 'Two', 'yit'),
            3 => __( 'Three', 'yit'),
            4 => __( 'Four', 'yit'),
            6 => __( 'Six', 'yit'),
        ),
        'name' => __( 'Number of products per row', 'yit' ),
        'desc' => __( 'Select the number of items', 'yit' ),
        'std' => 4,
        'deps' => array(
            'ids' => 'shop-custom-num-column',
            'values' => 'yes'
        ),
    ),

    array(
        'id' => 'shop-grid-list-option',
        'type' => 'onoff',
        'name' => __( 'Show "Grid/List" view option', 'yit' ),
        'desc' => __( 'Say if you want to show the option to switch between "Grid" and "List" view. ', 'yit'),
        'std' => 'yes',
        'deps' => array(
            'ids' => 'shop-view-type',
            'values' => 'list,grid'
        )
    ),

    array(
        'id' => 'shop-products-per-page-option',
        'type' => 'onoff',
        'name' => __( 'Show "Products per Page" view option', 'yit' ),
        'desc' => __( 'Say if you want to show the option for select how many products show in single shop page.', 'yit'),
        'std' => 'yes'
    ),

    array(
        'id' => 'shop-quick-view-enable',
        'type' => 'onoff',
        'name' => __( 'Enable Quick View', 'yit' ),
        'desc' => __( 'Say if you want to enable quick view for products', 'yit'),
        'std' => 'yes'
    ),

    array(
        'id' => 'shop-quick-view-text',
        'type' => 'text',
        'name' => __( 'Set "Quick View" text', 'yit' ),
        'desc' => __( 'Choose the text to display within the quick view button.', 'yit'),
        'std' => 'Quick View',
        'deps' => array(
            'ids' => 'shop-quick-view-enable',
            'values' => 'yes'
        ),
    ),

    array(
        'id' => 'shop-product-title',
        'type' => 'onoff',
        'name' => __( 'Show product title', 'yit' ),
        'desc' => __( 'Say if you want to show the product title. ', 'yit'),
        'std' => 'yes'
    ),

    array(
        'id' => 'shop-product-price',
        'type' => 'onoff',
        'name' => __( 'Show product price', 'yit' ),
        'desc' => __( 'Say if you want to show the product price. ', 'yit'),
        'std' => 'yes'
    ),

    array(
        'id' => 'shop-product-rating',
        'type' => 'onoff',
        'name' => __( 'Show product rating', 'yit' ),
        'desc' => __( 'Say if you want to show the product rating', 'yit'),
        'std' => 'yes'
    ),

    array(
        'id' => 'shop-product-description',
        'type' => 'onoff',
        'name' => __( 'Show product description ( on list view )', 'yit' ),
        'desc' => __( 'Say if you want to show the product short description. Note: works only on list view.', 'yit'),
        'std' => 'yes'
    ),

    array(
        'id' => 'shop-add-to-cart-button',
        'type' => 'onoff',
        'name' => __( 'Show Add To Cart button', 'yit' ),
        'desc' => __( 'Say if you want to show the add to cart button', 'yit'),
        'std' => 'yes'
    ),

    array(
        'id' => 'shop-view-wishlist-button',
        'type' => 'onoff',
        'name' => __( 'Show wishlist button', 'yit' ),
        'desc' => __( 'Say if you want to show wishlist button.', 'yit'),
        'std' => 'yes'
    ),

    array(
        'id' => 'shop-added-to-cart-type',
        'type' => 'select',
        'options' => array(
            'popup' => __( 'Popup box', 'yit'),
            'label' => __( 'Label', 'yit')
        ),
        'name' => __( 'Added to cart layout', 'yit' ),
        'desc' => __( 'Select layout for added to cart message.', 'yit' ),
        'std' => 'label'
    ),

    array(
        'type' => 'title',
        'name' => __( 'Price Filter Settings', 'yit' ),
        'desc' => ''
    ),

    array(
        'id' => 'shop-price-filter-style',
        'type' => 'onoff',
        'name' => __( 'Enable Slider Price Filter', 'yit'),
        'desc' => __( 'Select if you want to enable slider style for price filter widget', 'yit'),
        'std' => 'yes',
    )
);

