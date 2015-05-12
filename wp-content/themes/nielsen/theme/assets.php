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
 * Return an array with the css and scripts to include
 *
 * @package Yithemes
 * @author Andrea Grillo <andrea.grillo@yithemes.com>
 * @since 2.0.0
 * @return mixed array
 *
 */

/**
 * Include responsive css file after all stylesheets loaded
 */

function yit_global_object() {

    wp_localize_script( 'jquery', 'yit', array(
        'isRtl' => is_rtl(),
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'responsive_menu_text' => __( 'Navigate to...', 'yit' ),
		'price_filter_slider' => yit_get_option( 'shop-price-filter-style' ),
        'added_to_cart_layout' => yit_get_option ( 'shop-added-to-cart-type' ),
        'added_to_cart_text' => __( 'Added', 'yit' ),
        'load_gif' => YIT_THEME_ASSETS_URL . '/images/search.gif',
        'search_button' =>  __( 'GO' , 'yit' )
    ));

}

add_action( 'wp_enqueue_scripts', 'yit_global_object', 100 );

function yit_dequeue_styles(){
    wp_dequeue_style( 'yit-faq' );
	wp_deregister_style( 'font-awesome' );
    wp_dequeue_script( 'yit-faq-frontend' );
}
add_action( 'wp_enqueue_scripts', 'yit_dequeue_styles', 90 );
add_action( 'wp_footer', 'yit_dequeue_styles', 2 );

/**
 * Include responsive css file after all stylesheets loaded
 */
function yit_responsive_and_custom_assets() {
    yit_get_option( 'general-activate-responsive' ) == 'yes' ? wp_enqueue_style( 'responsive' ) : wp_enqueue_style( 'non-responsive' );
    wp_enqueue_style( 'custom' );
}

if( ! yit_is_old_ie() ){
    add_action( 'wp_enqueue_scripts', 'yit_responsive_and_custom_assets', 100 );
}

return array(

    'style'  => array(
        'bootstrap-twitter' => array(
            'src'       => YIT_THEME_ASSETS_URL . '/bootstrap/css/bootstrap.min.css',
            'enqueue'   => true
        ),
        'font-awesome' => array(
            'src'       => YIT_CORE_ASSETS_URL . '/css/font-awesome.min.css',
            'enqueue'   => true
        ),
        'theme-stylesheet' => array(
            'src'       => get_stylesheet_uri(),
            'enqueue'   => true
        ),
        'shortcodes' => array(
            'src'       => YIT_THEME_ASSETS_URL . '/css/shortcodes.css',
            'enqueue'   => true
        ),
        'widgets' => array(
            'src'       => YIT_THEME_ASSETS_URL . '/css/widgets.css',
            'enqueue'   => true
        ),
        'comment-stylesheet' => array(
            'src'       => YIT_THEME_ASSETS_URL . '/css/comment.css',
            'enqueue'   => true
        ),
        'animate' => array(
            'src' => YIT_THEME_ASSETS_URL . '/css/animate.css',
            'enqueue'   => true,
			'use_in_mobile' => false
        ),
        'prettyPhoto' => array(
            'src' => YIT_THEME_ASSETS_URL . '/css/prettyPhoto.css',
            'enqueue'   => true
        ),
        'owl-slider' => array(
            'src'       => YIT_THEME_ASSETS_URL . '/css/owl.css',
            'enqueue'   => true
        ),
		'responsive' => array(
			'src'       => YIT_THEME_ASSETS_URL . '/css/responsive.css',
		),
        'non-responsive' => array(
			'src'       => YIT_THEME_ASSETS_URL . '/css/non-responsive.css',
		),
        'scrollbar' => array(
            'src'       => YIT_THEME_ASSETS_URL . '/css/scrollbar.css',
            'enqueue'   => true
        ),
        'custom' => array(
			'src'       => YIT_URL . '/custom.css',
		)
    ),

    'script' => array(
        'bootstrap-twitter' => array(
            'src'       => YIT_THEME_ASSETS_URL . '/bootstrap/js/bootstrap.js',
            'enqueue'   => true,
            'deps'      => array('jquery'),
        ),
        'jquery-commonlibraries' => array(
            'src'       => YIT_THEME_ASSETS_URL . '/js/jquery.commonlibraries.js',
            'enqueue'   => true,
            'deps'      => array('jquery'),
        ),
        'yit-internal' => array(
            'src'       => YIT_THEME_ASSETS_URL . '/js/internal.js',
            'enqueue'   => true,
            'deps'      => array('jquery'),
        ),
        'yit_woocommerce' => ! function_exists( 'WC' ) ? false : array(
            'src'       => YIT_THEME_ASSETS_URL . '/js/woocommerce.js',
            'enqueue'   => true,
            'deps'      => array('jquery'),
        ),
        'yit_woocommerce_2_3' => (! function_exists( 'WC' ) || version_compare( preg_replace( '/-beta-([0-9]+)/', '', WC()->version ), '2.3', '<' ) )  ? false : array(
            'src'       => YIT_THEME_ASSETS_URL . '/js/woocommerce_2.3.js',
            'enqueue'   => true,
            'deps'      => array('jquery'),
        ),
        'yit_scrollbar' => array(
            'src'       => YIT_THEME_ASSETS_URL . '/js/jquery.scrollbar.js',
            'enqueue'   => true,
            'deps'      => array('jquery'),
        ),
        'shortcodes' => array(
            'src'       => YIT_THEME_ASSETS_URL . '/js/shortcodes.js',
            'enqueue'   => true,
            'deps'      => array('jquery'),
        ),
        'yit-shortcodes-twitter' => array(
            'src'       => YIT_THEME_ASSETS_URL . '/js/twitter.min.js',
            'enqueue'   => false,
            'deps'      => array('jquery'),
        ),
        'owl-carousel'  => array(
            'src'       => YIT_THEME_ASSETS_URL . '/js/owl.carousel.js',
            'enqueue'   => true,
            'deps'      => array('jquery'),
        ),
		'yit-shortcodes-twitter-text' => array(
			'src'       => YIT_THEME_ASSETS_URL . '/js/twitter-text.min.js',
			'enqueue'   => false,
			'deps'      => array( 'jquery', 'yit-shortcodes-twitter' ),
		),

        'prettyPhoto' => array(
            'src'       => YIT_THEME_ASSETS_URL . '/js/jquery.prettyPhoto.min.js',
            'enqueue'   => false,
            'deps'      => array( 'jquery' ),
        ),
        'jquery-placeholder' => array(
            'src'       => YIT_THEME_ASSETS_URL. '/js/jquery.placeholder.js',
            'enqueue'   => true,
            'deps'      => array( 'jquery' ),
        ),
		'yit-common' => array(
			'src'       => YIT_THEME_ASSETS_URL . '/js/common.js',
			'enqueue'   => true,
			'deps'      => array( 'jquery', 'jquery-masonry' ),
			'localize' => array(
				'responsive_menu_text' => __( 'Navigate to...', 'yit' ),
				'responsive_menu_close' => __( 'Close', 'yit' )
			)
		),
        'map-google' => array(
            'src'       => '//maps.googleapis.com/maps/api/js?sensor=false',
            'enqueue'   => false,
            'deps'      => array( 'yit-common' ),
        ),
    ),

);