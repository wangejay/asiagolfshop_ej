<?php
/**
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

return array(

    array(
        'name'         => 'YIT Backup & Reset',
        'slug'         => 'yit-backup-reset',
        'repository'   => 'YIT Repository',
        'required'     => false,
        'version'      => '1.2.2',
    ),
    array(
        'name'         => 'YIT Contact Form',
        'slug'         => 'yit-contact-form',
        'repository'   => 'YIT Repository',
        'required'     => false,
        'version'      => '1.0.5',
    ),
    array(
        'name'         => 'YIT Faq',
        'slug' 		   => 'yit-faq',
        'repository'   => 'YIT Repository',
        'required' 	   => false,
        'version'      => '1.0.2',
    ),
    array(
        'name'         => 'YIT Sidebar',
        'slug' 		   => 'yit-sidebar',
        'repository'   => 'YIT Repository',
        'required' 	   => false,
        'version'      => '1.0.2',
    ),
    array(
        'name'         => 'YIT Logos',
        'slug' 		   => 'yit-logos',
        'repository'   => 'YIT Repository',
        'required' 	   => false,
        'version'      => '1.0.4',
    ),
    array(
        'name'         => 'YIT Newsletter',
        'slug' 		   => 'yit-newsletter',
        'repository'   => 'YIT Repository',
        'required' 	   => false,
        'version'      => '1.0.5',
    ),
    array(
        'name'         => 'YIT Shortcodes',
        'slug' 		   => 'yit-shortcodes',
        'repository'   => 'YIT Repository',
        'required' 	   => false,
        'version'      => '1.1.1',
    ),
    array(
        'name'         => 'YIT Sitemap',
        'slug' 		   => 'yit-sitemap',
        'repository'   => 'YIT Repository',
        'required' 	   => false,
        'version'      => '1.0.2',
    ),
    array(
        'name'         => 'YIT Slider',
        'slug' 		   => 'yit-slider',
        'repository'   => 'YIT Repository',
        'required' 	   => false,
        'version'      => '1.0.5',
    ),
    array(
        'name'         => 'YIT Team',
        'slug' 		   => 'yit-team',
        'repository'   => 'YIT Repository',
        'required' 	   => false,
        'version'      => '1.0.6',
    ),
    array(
        'name'         => 'YIT Testimonial',
        'slug' 		   => 'yit-testimonial',
        'repository'   => 'YIT Repository',
        'required' 	   => false,
        'version'      => '1.0.4',
    ),

    array(
        'name'      => 'YITH WooCommerce Colors and Labels Variations',
        'slug'      => 'yith-woocommerce-colors-labels-variations',
        'repository'   => 'YIT Repository',
        'required' 	=> false,
        'version' 	=> '1.1.2'
    ),

    array(
        'name' 		=> 'YITH WooCommerce Hide Price',
        'slug' 		=> 'yith-woocommerce-hide-price',
        'repository'   => 'YIT Repository',
        'required' 	=> false,
        'version' 	=> '1.1.1'
    ),

    array(
        'name'         => 'YITH Prelaunch',
        'slug' 		   => 'yith-pre-launch',
        'required' 	   => false,
        'version'      => '1.0.7',
    ),

    array(
        'name'         => 'YITH Woocommerce Advanced Reviews',
        'slug' 		   => 'yith-woocommerce-advanced-reviews',
        'required' 	   => false,
        'version'      => '1.0.6',
    ),

    array(
        'name'      => 'WordPress Social Login',
        'slug'      => 'wordpress-social-login',
        'required' 				=> false,
        'version' 				=> '2.1.6',
        'force_activation' 		=> false,
        'force_deactivation' 	=> true,
        'external_url' 			=> '',
    ) ,

    array(
        'name'               => 'Revolution Slider',
        'slug'               => 'revslider',
        'source'             => YIT_THEME_PLUGINS_PATH . '/revslider.zip',
        'required'           => false,
        'version'            => '4.6.5',
        'force_activation'   => false,
        'force_deactivation' => true,
    ),

	array(
		'name'               => 'WPBakery Visual Composer',
		'slug'               => 'js_composer',
		'source'             => YIT_THEME_PLUGINS_PATH . '/js_composer.zip',
		'required'           => true,
		'version'            => '4.4',
		'force_activation'   => false,
		'force_deactivation' => true,
	),

    array(
        'name'               => 'Essential_Grid',
        'slug'               => 'essential-grid',
        'source'             => YIT_THEME_PLUGINS_PATH . '/essential-grid.zip',
        'required'           => false,
        'version'            => '2.0.4',
        'force_activation'   => false,
        'force_deactivation' => true,
    ),
    array(
        'name'         => 'WooCommerce',
        'slug' 		   => 'woocommerce',
        'required' 	   => false,
        'version'      => '2.2.10',
    ),
    array(
        'name' 		=> 'YITH WooCommerce Zoom Magnifier',
        'slug' 		=> 'yith-woocommerce-zoom-magnifier',
        'required' 	=> false,
        'version'   => '1.1.5',
    ),
    array(
        'name' 		=> 'YITH WooCommerce Wishlist',
        'slug' 		=> 'yith-woocommerce-wishlist',
        'required' 	=> false,
        'version'   => '1.1.7',
    ),

    array(
        'name' 		=> 'YITH WooCommerce Ajax Navigation',
        'slug' 		=> 'yith-woocommerce-ajax-navigation',
        'required' 	=> false,
        'version'   => '1.4.1'
    ),

    array(
        'name' 		=> 'YITH WooCommerce Ajax Search',
        'slug' 		=> 'yith-woocommerce-ajax-search',
        'required' 	=> false,
        'version'   => '1.1.3'
    ),

    array(
        'name'      => 'YITH WooCommerce Request a Quote',
        'slug'      => 'yith-woocommerce-request-a-quote',
        'required'  => false,
        'version'   => '1.0.0'
    ),

    array(
        'name'      => 'YITH WooCommerce Catalog Mode',
        'slug'      => 'yith-woocommerce-catalog-mode',
        'required'  => false,
        'version'   => '1.0.0'
    ),

    array(
        'name'         => 'YITH Woocommerce Advanced Reviews',
        'slug' 		   => 'yith-woocommerce-advanced-reviews',
        'required' 	   => false,
        'version'      => '1.0.0',
    ),

    array(
        'name'         => 'YITH Woocommerce Order Traking',
        'slug' 		   => 'yith-woocommerce-order-tracking',
        'required' 	   => false,
        'version'      => '1.0.0',
    ),

    array(
        'name'         => 'YITH Woocommerce Review Reminder',
        'slug' 		   => 'yith-woocommerce-review-reminder',
        'required' 	   => false,
        'version'      => '1.0.0',
    ),

    array(
        'name'      => 'YITH WooCommerce Cart Message',
        'slug'      => 'yith-woocommerce-cart-messages',
        'required'  => false,
        'version'   => '1.0.0'
    ),

    array(
        'name'      => 'YITH WooCommerce PDF Invoice and Shipping List',
        'slug'      => 'yith-woocommerce-pdf-invoice',
        'required'  => false,
        'version'   => '1.0.0'
    ),

    array(
        'name'      => 'YITH WooCommerce Stripe',
        'slug'      => 'yith-woocommerce-stripe',
        'required'  => false,
        'version'   => '1.0.0'
    ),

    array(
        'name'      => 'YITH WooCommerce Authorize.net Payment Gateway',
        'slug'      => 'yith-woocommerce-authorizenet-payment-gateway',
        'required'  => false,
        'version'   => '1.0.0'
    )
);