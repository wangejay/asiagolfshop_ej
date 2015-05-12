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
 * Return an array with the options for Theme Options > Typography and Color > General Settings
 *
 * @package Yithemes
 * @author  Antonino Scarfi' <antonino.scarfi@yithemes.com>
 * @since   2.0.0
 * @return  mixed array
 *
 */
return array(
    /* Typography and Color > General Settings */
    array(
        'type' => 'title',
        'name' => __( 'Main general color scheme', 'yit' ),
        'desc' => __( "Set the different colors shades for the main theme's color", 'yit' )
    ),

	array(
		'id'             => 'theme-text-color',
		'type'           => 'colorpicker',
		'name'           => __( 'Main Text', 'yit' ),
		'desc'           => __( 'Set the color for the major of texts in the theme.', 'yit' ),
		'refresh_button' => true,
		'std'            => array(
			'color' => '#6d6c6c'
		),
		'style'          => array(
			'selectors'  => '.main-text-color',
			'properties' => 'color'
		)
	),

	array(
		'id'             => 'theme-color-1',
		'type'           => 'colorpicker',
		'name'           => __( 'Shade 1', 'yit' ),
		'desc'           => __( 'Set the first shade of main color.', 'yit' ),
		'refresh_button' => true,
		'std'            => array(
			'color' => '#a41103'
		),
		'style'          => array(
			array(
				'selectors'  => '.shade-1',
				'properties' => 'color'
			),
			array(
				'selectors'  => '#nav .tooltip .tooltip-inner,
								div.team-member-name span.team-member-name-highlight',
				'properties' => 'background-color'
			),
			array(
				'selectors'  => '#nav .tooltip .tooltip-arrow',
				'properties' => 'border-top-color'
			),
		)
	),

    array(
        'id'             => 'theme-color-2',
        'type'           => 'colorpicker',
        'name'           => __( 'Shade 2', 'yit' ),
        'desc'           => __( 'Set the second shade of main color.', 'yit' ),
        'refresh_button' => true,
        'std'            => array(
            'color' => '#ff1800'
        ),
        'style'          => array(
            'selectors'  => '.shade-2,
                             .images-slider-sc .flex-direction-nav li a:hover,
                             .logos-slider.wrapper .nav .prev:hover i:before,
                             .logos-slider.wrapper .nav .next:hover i:before,

                             .logos-slider .nav .next i:hover,
                             #buddypress div#message p, #sitewide-notice p,
                             #buddypress div#message.updated p',
            'properties' => 'color'
        )
    ),

	array(
        'id'             => 'theme-color-3',
        'type'           => 'colorpicker',
        'name'           => __( 'Shade 3', 'yit' ),
        'desc'           => __( 'Set the third shade of main color.', 'yit' ),
        'refresh_button' => true,
        'std'            => array(
            'color' => '#8c8c8c'
        ),
        'style'          => array(
            'selectors'  => '.shade-3, .images-slider-sc .flex-direction-nav li a,
                             .widget.featured-products .flex-direction-nav li a,
                             .logos-slider .nav .next i, .yit_post_quote .fa.shade-1',
            'properties' => 'color'
        )
    ),

    array(
        'id'             => 'general-background-color',
        'type'           => 'colorpicker',
        'name'           => __( 'General Background Color', 'yit' ),
        'desc'           => __( 'Set the general background color.', 'yit' ),
        'refresh_button' => true,
        'std'            => array(
            'color' => '#ffffff'
        ),
        'style'          => array(
            'selectors'  => '.general-background-color,
                            #comments ol li .information .user-info .is_author,
                            #review ol li .information .user-info .is_author,
                            ul.blog_posts li div.blog_post .yit_post_date,
                            #header-search,
                            .testimonials-slider .owl-carousel .owl-controls .owl-nav .owl-prev,
                            .owl-carousel .owl-controls .owl-nav .owl-next,
                            .testimonials-slider .owl-carousel .owl-controls .owl-nav .owl-prev,
                            .owl-carousel .owl-controls .owl-nav .owl-prev,
                            .logos-slider.wrapper .nav .prev,
                            .logos-slider.wrapper .nav .next',
            'properties' => 'background-color'
        )
    ),
    array(
        'id'    => 'color-website-border-style-1',
        'type'  => 'colorpicker',
        'name'  => __( 'General Border Color Style 1', 'yit' ),
        'desc'  => __( 'Select the color used in the theme for the border', 'yit' ),
        'std'   => array(
            'color' => '#dbd8d8'
        ),
        'style' => array(
            array(
                'selectors'  => $this->get_selectors( 'border-1-top' ),
                'properties' => 'border-top-color'
            ),

            array(
                'selectors'  => $this->get_selectors( 'border-1-bottom' ),
                'properties' => 'border-bottom-color'
            ),

            array(
                'selectors'  => $this->get_selectors( 'border-1-all' ),
                'properties' => 'border-color'
            ),
            array(
                'selectors'  => '.border-line-1',
                'properties' => 'background-color'
            )
        )
    ),

    array(
        'id'    => 'color-website-border-style-2',
        'type'  => 'colorpicker',
        'name'  => __( 'General Border Color Style 2', 'yit' ),
        'desc'  => __( 'Select the color used in the theme for the border', 'yit' ),
        'std'   => array(
            'color' => '#f2f2f2'
        ),
        'style' => array(
            array(
                'selectors'  => $this->get_selectors( 'border-2-top' ),
                'properties' => 'border-top-color'
            ),

            array(
                'selectors'  => $this->get_selectors( 'border-2-bottom' ),
                'properties' => 'border-bottom-color'
            ),

            array(
                'selectors'  => $this->get_selectors( 'border-2-all' ),
                'properties' => 'border-color'
            ),
            array(
                'selectors'  => '.border-line-2, .toggle .toggle-title span.fa.fa-minus.opened',
                'properties' => 'background-color'
            )
        )
    ),

    array(
        'id'        => 'color-theme-star',
        'type'      => 'colorpicker',
        'variations' => array(
            'normal' => __( 'Empty', 'yit' ),
            'hover'  => __( 'Full', 'yit' )
        ),
        'name'      => __( 'General Stars Color', 'yit' ),
        'desc'      => __( 'Select the color used in the theme for the theme stars.', 'yit' ),
        'std'  => array(
            'color' => array(
                'normal' => '#b5b4b4',
                'hover'  => '#ffd314'
            )
        ),
        'style'     => array(
            'normal' => array(
                'selectors'   => '#comments div.comment-meta .product-rating span.star-empty,
                                .woocommerce-product-rating .star-rating:before,
                                .widget.woocommerce .star-rating:before,
                                .testimonial-rating .star-rating:before,
                                .yit_recent_reviews .star-rating:before',
                'properties'  => 'color'
            ),
            'hover' => array(
                'selectors'   => '#comments div.comment-meta .product-rating span.star,
                                .woocommerce-tabs #review_form p.stars a,
                                .woocommerce-product-rating .star-rating span,
                                .widget.woocommerce .star-rating span,
                                .testimonial-rating .star-rating span,
                                .yit_recent_reviews .star-rating span',
                'properties'  => 'color'
            )
        )
    ),

    array(
        'id'        => 'color-theme-share',
        'type'      => 'colorpicker',
        'variations' => array(
            'normal' => __( 'Normal', 'yit' ),
            'hover'  => __( 'Hover', 'yit' )
        ),
        'name'      => __( 'General Share & Social Login Color', 'yit' ),
        'desc'      => __( 'Select the color used in the theme for the share and social login.', 'yit' ),
        'std'  => array(
            'color' => array(
                'normal' => '#b1b1b1',
                'hover'  => '#4b4b4b'
            )
        ),
        'style'     => array(
            'normal' => array(
                'selectors'   => '.color-theme-share, .color-theme-share:link, .color-theme-share:visited, .yith-wcwl-add-to-wishlist a > span,
                                  #list-or-grid a, #number-of-products a, #number-of-products a:after,
                                  #yith-wcwl-form .yith-wcwl-share ul li a,
                                  .single-product.woocommerce div.product div.summary .single-product-other-action .yith-wcwl-add-to-wishlist a > span',
                'properties'  => 'color'
            ),
            'hover' => array(
                'selectors'   => '.color-theme-share:hover,
                                  .color-theme-share:visited,
                                  #list-or-grid a.active, #list-or-grid a:hover,
                                  #number-of-products a.active, #number-of-products a:hover,
                                  #yith-wcwl-form .yith-wcwl-share ul li a:hover,
                                  .single-product.woocommerce div.product div.summary .single-product-other-action .yith-wcwl-add-to-wishlist a:hover > span,
                                  .single-product.woocommerce div.product div.summary .single-product-other-action .yith-wcwl-add-to-wishlist div.yith-wcwl-wishlistaddedbrowse a > span,
                                  .single-product.woocommerce div.product div.summary .single-product-other-action .yith-wcwl-add-to-wishlist div.yith-wcwl-wishlistexistsbrowse a > span',
                'properties'  => 'color'
            )
        )
    ),
);

