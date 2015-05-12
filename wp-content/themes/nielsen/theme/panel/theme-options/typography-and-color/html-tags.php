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
 * Return an array with the options for Theme Options > Typography and Color > HTML Tags
 *
 * @package Yithemes
 * @author Andrea Grillo <andrea.grillo@yithemes.com>
 * @author Antonio La Rocca <antonio.larocca@yithemes.it>
 * @since 2.0.0
 * @return mixed array
 *
 */
return array(

    /* Typography and Color > HTML Tags General Settings */
    array(
        'type' => 'title',
        'name' => __( 'HTML Tags General Settings', 'yit' ),
        'desc' => ''
    ),

    array(
        'id' => 'typography-link-color',
        'type' => 'colorpicker',
        'variations' => array(
            'normal' => __( 'Links', 'yit' ),
            'hover'  => __( 'Links hover', 'yit' )
        ),
        'name' => __( 'Links Style 1 (Page and Text)', 'yit' ),
        'desc' => __( 'Select the colors to use for the links in normal state and on hover.', 'yit' ),
        'std'  => array(
            'color' => array(
                'normal' => '#a41103',
                'hover'  => '#ff1800'
            )
        ),
        'style' => array(
            'normal' => array(
                'selectors'   => $this->get_selectors( 'a-tag-style-1' ),
                'properties'  => 'color'
            ),
            'hover' => array(
                'selectors'   => $this->get_selectors( 'a-tag-style-1-hover' ),
                'properties'  => 'color'
            )
        ),
        'linked_to' => array(
            'normal' => 'theme-color-1',
            'hover'  => 'theme-color-2'
        )
    ),

    array(
        'id' => 'typography-link-color-2',
        'type' => 'colorpicker',
        'variations' => array(
            'normal' => __( 'Links', 'yit' ),
            'hover'  => __( 'Links hover', 'yit' )
        ),
        'name' => __( 'Links Style 2 (menu, footer, widget, sidebar, ecc.)', 'yit' ),
        'desc' => __( 'Select the colors to use for the links in normal state and on hover.', 'yit' ),
        'std'  => array(
            'color' => array(
                'normal' => '#6d6c6c',
                'hover'  => '#ba1707'
            )
        ),
        'style' => array(
            'normal' => array(
                'selectors'   => $this->get_selectors( 'a-tag-style-2' ),
                'properties'  => 'color'
            ),
            'hover' => array(
                'selectors'   => $this->get_selectors( 'a-tag-style-2-hover' ),
                'properties'  => 'color'
            )
        ),
        'linked_to' => array(
            'normal' => 'theme-color-3',
            'hover'  => 'theme-color-2'
        )
    ),


    array(
        'id' => 'typography-p-font',
        'type' => 'typography',
        'name' => __( 'Paragraphs', 'yit' ),
        'desc' => __( 'Select the type to use for the p.', 'yit' ),
        'min' => 1,
        'max' => 80,
        'default_font_id' => 'typography-website-paragraph',
        'std'   => array(
            'size'      => 13,
            'unit'      => 'px',
            'family'    => 'default',
            'style'     => 'regular',
            'color'     => '#6d6c6c',
            'align'     => 'left',
            'transform' => 'none',
        ),
        'linked_to'  => array(
	        'color' => 'theme-text-color',
        ),
        'style' => array(
            'selectors'   => $this->get_selectors('p-tag'),
            'properties'  => 'font-size,
                              font-family,
                              font-weight,
                              color,
                              text-transform,
                              text-align'
        )
    ),

    array(
        'id' => 'typography-h1-font',
        'type' => 'typography',
        'name' => __( 'Headings 1 font', 'yit' ),
        'desc' => __( 'Select the type to use for the h1.', 'yit' ),
        'min' => 1,
        'max' => 80,
        'default_font_id' => 'typography-website-title',
        'std'   => array(
            'size'      => 20,
            'unit'      => 'px',
            'family'    => 'default',
            'style'     => 'regular',
            'color'     => '#6d6c6c',
            'align'     => 'left',
            'transform' => 'uppercase',
        ),
        'linked_to'  => array(
	        'color' => 'theme-text-color',
        ),
        'style' => array(
            'selectors'   => 'h1,
                              .cta-phone .cta-phone-phone',
            'properties'  => 'font-size,
                              font-family,
                              font-weight,
                              color,
                              text-transform,
                              text-align'
        )
    ),

    array(
        'id' => 'typography-h2-font',
        'type' => 'typography',
        'name' => __( 'Headings 2 font', 'yit' ),
        'desc' => __( 'Select the type to use for the h2.', 'yit' ),
        'min' => 1,
        'max' => 80,
        'default_font_id' => 'typography-website-title',
        'std'   => array(
            'size'      => 18,
            'unit'      => 'px',
            'family'    => 'default',
            'style'     => '700',
            'color'     => '#6d6c6c',
            'align'     => 'left',
            'transform' => 'uppercase',
        ),
        'linked_to'  => array(
	        'color' => 'theme-text-color',
        ),
        'style' => array(
            'selectors'   => 'h2, .teaser-wrapper .image_banner_text p.title,
            .cta-phone.call-to-action h3',
            'properties'  => 'font-size,
                              font-family,
                              font-weight,
                              color,
                              text-transform,
                              text-align'
        )
    ),

    array(
        'id' => 'typography-h3-font',
        'type' => 'typography',
        'name' => __( 'Headings 3 font', 'yit' ),
        'desc' => __( 'Select the type to use for the h3.', 'yit' ),
        'min' => 1,
        'max' => 80,
        'default_font_id' => 'typography-website-title',
        'std'   => array(
            'size'      => 14,
            'unit'      => 'px',
            'family'    => 'default',
            'style'     => '700',
            'color'     => '#6d6c6c',
            'align'     => 'left',
            'transform' => 'uppercase',
        ),
        'linked_to'  => array(
	        'color' => 'theme-text-color',
        ),
        'style' => array(
            'selectors'   => 'h3,
                             .widget.yit-recent-posts .recent-post .hentry-post h3 a.title,
                             .testimonial-wrapper .testimonial-smallquote,
                             .show-category .category-title,
                             .category-meta a,
                             .testimonial-wrapper .testimonial-name .name',
            'properties'  => 'font-size,
                              font-family,
                              font-weight,
                              color,
                              text-transform,
                              text-align'
        )
    ),

    array(
        'id' => 'typography-h4-font',
        'type' => 'typography',
        'name' => __( 'Headings 4 font', 'yit' ),
        'desc' => __( 'Select the type to use for the h4.', 'yit' ),
        'min' => 1,
        'max' => 80,
        'default_font_id' => 'typography-website-title',
        'std'   => array(
            'size'      => 13,
            'unit'      => 'px',
            'family'    => 'default',
            'style'     => '700',
            'color'     => '#6d6c6c',
            'align'     => 'left',
            'transform' => 'uppercase',
        ),
        'linked_to'  => array(
	        'color' => 'theme-text-color',
        ),
        'style' => array(
            'selectors'   => 'h4,
                              .widget.yit-recent-comments .comments-info-wrapper .author .url,
                              .widget.yit-recent-comments .comments-info-wrapper .author .email,
                              .team-member-role,
                              .blog-section-wrapper ul.blog_posts li div.blog_post .yit_post_meta span.title,
                              .yit-vertical-megamenu h3,
                              .widget.yit_recent_reviews .review-meta-avatar .meta .author',

            'properties'  => 'font-size,
                              font-family,
                              font-weight,
                              color,
                              text-transform,
                              text-align'
        )
    ),

    array(
        'id' => 'typography-h5-font',
        'type' => 'typography',
        'name' => __( 'Headings 5 font', 'yit' ),
        'desc' => __( 'Select the type to use for the h5.', 'yit' ),
        'min' => 1,
        'max' => 80,
        'default_font_id' => 'typography-website-title',
        'std'   => array(
            'size'      => 12,
            'unit'      => 'px',
            'family'    => 'default',
            'style'     => 'regular',
            'color'     => '#6d6c6c',
            'align'     => 'left',
            'transform' => 'uppercase',
        ),
        'linked_to'  => array(
	        'color' => 'theme-text-color',
        ),
        'style' => array(
            'selectors'   => 'h5,
                              .widget.yit-recent-posts .recent-post.compact .hentry-post h3 a.title,
                              .widget_recent_entries > ul > li a',
            'properties'  => 'font-size,
                              font-family,
                              font-weight,
                              color,
                              text-transform,
                              text-align'
        )
    ),

	array(
		'id' => 'typography-h6-font',
		'type' => 'typography',
		'name' => __( 'Headings 6 font', 'yit' ),
		'desc' => __( 'Select the type to use for the h6.', 'yit' ),
		'min' => 1,
		'max' => 80,
		'default_font_id' => 'typography-website-title',
		'std'   => array(
			'size'      => 12,
			'unit'      => 'px',
			'family'    => 'default',
			'style'     => 'regular',
			'color'     => '#6d6c6c',
			'align'     => 'left',
			'transform' => 'none',
		),
		'linked_to'  => array(
			'color' => 'theme-text-color',
		),
		'style' => array(
			'selectors'   => 'h6',
			'properties'  => 'font-size,
                              font-family,
                              font-weight,
                              color,
                              text-transform,
                              text-align'
		)
	),

	array(
		'id' => 'typography-widgets-title',
		'type' => 'typography',
		'name' => __( 'Widgets title', 'yit' ),
		'desc' => __( 'Select the type to use for the h6.', 'yit' ),
		'min' => 1,
		'max' => 80,
		'default_font_id' => 'typography-website-title',
		'std'   => array(
			'size'      => 15,
			'unit'      => 'px',
			'family'    => 'default',
			'style'     => '700',
			'color'     => '#6d6c6c',
			'align'     => 'left',
			'transform' => 'uppercase',
		),
		'linked_to'  => array(
			'color' => 'theme-text-color',
		),
		'style' => array(
			'selectors'   => '.widget h3',
			'properties'  => 'font-size,
                              font-family,
                              font-weight,
                              color,
                              text-transform,
                              text-align'
		)
	)
);

