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
 * Return an array with the options for Theme Options > Header > Logo
 *
 * @package Yithemes
 * @author Andrea Grillo <andrea.grillo@yithemes.com>
 * @author Antonio La Rocca <antonio.larocca@yithemes.it>
 * @since 2.0.0
 * @return mixed array
 *
 */
return array(

    /* Header > Logo Settings */
    array(
        'type' => 'title',
        'name' => __( 'General', 'yit' ),
        'desc' => ''
    ),

    array(
        'id' => 'header-skin',
        'type' => 'select',
        'name' => __('Header Skin', 'yit'),
        'desc' => __('Select the skin for the header', 'yit' ),
        'options' => array(
            'skin1' => __( 'Left logo and menu aside', 'yit' ),
            'skin2' => __( 'Centered logo and menu below', 'yit' )
        ),
        'std' => 'skin1',
        'in_skin'        => true
    ),

    array(
        'id' => 'header-show-search',
        'type' => 'select',
        'name' => __('Show Search', 'yit'),
        'desc' => __('Do you want to use the search on header?', 'yit' ),
        'options' => array(
            'none'  => __( 'None', 'yit' ),
            'big'   => __( 'Big Search', 'yit' ),
            'small'   => __( 'Small Search', 'yit' )
        ),
        'std' => 'big',
        'in_skin'        => true
    ),

    array(
        'id' => 'header-show-cat',
        'type' => 'onoff',
        'name' => __('Show "Shop by Category" menu', 'yit'),
        'desc' => __('Do you want to use the "Shop by Category" menu on header? (It is valid only for Skin 1 of header)', 'yit' ),
        'std' => 'yes',
        'in_skin'        => true,
        'deps'  => array(
            'ids'       => 'header-show-search',
            'values'    => 'big'
        )
    ),

    array(
        'id' => 'header-cat-dropdow-opened',
        'type' => 'onoff',
        'name' => __('Keep "Shop by Category" opened', 'yit'),
        'desc' => __('Do you want to always keep "Shop by Category" opened on sliders in header? (It is valid only for Skin 1 of header)', 'yit' ),
        'std' => 'yes',
        'deps' => array(
            'ids'       => 'header-show-search',
            'values'    => 'big'
        ),
        'in_skin' => true
    ),

    array(
        'id' => 'header-cat-dropdow-opened-can-close',
        'type' => 'onoff',
        'name' => __('Toggle "Shop by Category" opened', 'yit'),
        'desc' => __('Allow user to toggle "Shop by Category" opened on sliders or header', 'yit' ),
        'std' => 'no',
        'deps' => array(
            'ids'       => 'header-cat-dropdow-opened',
            'values'    => 'yes'
        ),
        'in_skin' => true
    ),

    array(
        'id' => 'header-shop-cat-title',
        'type' => 'text',
        'name' => __('Title for "Shop by category" menu in header', 'yit'),
        'desc' => __('Set the title for the "Shop by category" menu on header', 'yit' ),
        'std' => __( 'Shop by category', 'yit' ),
        'deps' => array(
            'ids'       => 'header-show-search',
            'values'    => 'big'
        ),
        'in_skin' => true
    ),

    /* Header > Logo Settings */
    array(
        'type' => 'title',
        'name' => __( 'Logo', 'yit' ),
        'desc' => ''
    ),

    array(
        'id' => 'header-custom-logo',
        'type' => 'onoff',
        'name' => __('Custom logo', 'yit'),
        'desc' => __('Want to use a custom image as logo?', 'yit' ),
        'std' => 'yes',
        'in_skin'        => true
    ),

    array(
        'id' => 'header-custom-logo-image',
        'type' => 'upload',
        'name' => __( 'Custom logo image', 'yit' ),
        'desc' => __( 'Select the custom image to use as logo', 'yit' ),
        'std' => YIT_THEME_ASSETS_URL . '/images/logo.png',
        'deps' => array(
            'ids' => 'header-custom-logo',
            'values' => 'yes'
         )
    ),

    array(
        'id' => 'header-logo-tagline',
        'type' => 'onoff',
        'name' => __('Logo Tagline', 'yit'),
        'desc' => __('Specify if you want the tagline to show below the logo. ', 'yit' ),
        'std' => 'no'
    ),

    array(
        'id' => 'header-logo-tagline-mobile',
        'type' => 'onoff',
        'name' => __('Show logo Tagline in mobile', 'yit'),
        'desc' => __('Specify if you want the tagline to show below the logo on mobile devices. ', 'yit' ),
        'std' => 'no',
        'deps' => array(
            'ids' => 'header-logo-tagline',
            'values' => 'yes'
        )
    ),

    /* Miscellaneous settings */
    array(
        'type' => 'title',
        'name' => __( 'Miscellaneous', 'yit' ),
        'desc' => ''
    ),

    array(
        'id' => 'header-enable-topbar',
        'type' => 'onoff',
        'name' => __( 'Show Top Bar', 'yit' ),
        'desc' => __( 'Select if you want to show the Top Bar above the header. ', 'yit' ),
        'std' => 'yes',
        'in_skin' => true
    ),

    array(
        'id' => 'header-enable-topbar-left-mobile',
        'type' => 'onoff',
        'name' => __( 'Show Left Top Bar on mobile', 'yit' ),
        'desc' => __( 'Select if you want to show the Left Top Bar on mobile devices. ', 'yit' ),
        'std' => 'no',
        'in_skin' => true,
        'deps' => array(
            'ids' => 'header-enable-topbar',
            'values' => 'yes'
        )
    ),

    array(
        'id' => 'header-enable-topbar-right-mobile',
        'type' => 'onoff',
        'name' => __( 'Show Right Top Bar on mobile', 'yit' ),
        'desc' => __( 'Select if you want to show the Right Top Bar on mobile devices. ', 'yit' ),
        'std' => 'yes',
        'in_skin' => true,
        'deps' => array(
            'ids' => 'header-enable-topbar',
            'values' => 'yes'
        )
    ),

    array(
        'id' => 'header-sticky',
        'type' => 'onoff',
        'name' => __('Header Sticky', 'yit'),
        'desc' => __('Want to use a sticky header?', 'yit' ),
        'std' => 'yes',
        'in_skin'        => true
    ),

    array(
        'id' => 'show-dropdown-indicators',
        'type' => 'onoff',
        'name' => __( 'Show Dropdown Indicators', 'yit' ),
        'desc' => __( 'Select if you want to show the arrow indicators on navigation. ', 'yit' ),
        'std' => 'yes',
        'in_skin' => true
    ),

    array(
        'id' => 'show-image-on-search',
        'type' => 'onoff',
        'name' => __( 'Show Image on AJAX Search', 'yit' ),
        'desc' => __( 'Select if you want to show the product thumbnail when you search with AJAX Search plugin. ', 'yit' ),
        'std' => 'yes',
        'in_skin' => true
    ),

    array(
        'id' => 'show-price-on-search',
        'type' => 'onoff',
        'name' => __( 'Show Price on AJAX Search', 'yit' ),
        'desc' => __( 'Select if you want to show the product price when you search with AJAX Search plugin. ', 'yit' ),
        'std' => 'yes',
        'in_skin' => true
    ),



);

