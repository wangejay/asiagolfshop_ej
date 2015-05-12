<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


echo '<div class="single-product-other-action border">';

    if ( yit_get_option( 'shop-single-share' ) == 'yes' ) {
    echo '<div class="share-link-wrapper"><span class="share-label">' . __( 'Share', 'yit' ) . '</span>';
        yit_get_social_share( 'square' );
        echo '</div>';
    }

    do_action( 'yit_wishlist_in_other_action' );

    if ( shortcode_exists( 'yith_compare_button' ) && get_option( 'yith_woocompare_compare_button_in_product_page' ) == 'yes' && yit_get_option( 'shop-single-compare' ) == 'yes' ) {
        echo do_shortcode( '[yith_compare_button type="link"]' );
    }

echo '</div>';