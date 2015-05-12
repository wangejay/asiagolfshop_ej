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
 * Return an array with the options for Theme Options > Typography and Color > Content
 *
 * @package Yithemes
 * @author  Andrea Grillo <andrea.grillo@yithemes.com>
 * @author  Antonio La Rocca <antonio.larocca@yithemes.it>
 * @since   2.0.0
 * @return mixed array
 *
 */
return array(

    /* Typography and Color > Content > 404 Page */
    array(
        'type' => 'title',
        'name' => __( '404 Page', 'yit' ),
        'desc' => ''
    ),

    array(
        'id'              => 'content-not-found-general-font',
        'type'            => 'typography',
        'name'            => __( 'Custom 404 page general font', 'yit' ),
        'desc'            => __( 'Choose the font type, size and color.', 'yit' ),
        'min'             => 1,
        'max'             => 80,
        'default_font_id' => 'typography-website-paragraph',
        'std'             => array(
            'size'      => 14,
            'unit'      => 'px',
            'family'    => 'default',
            'style'     => 'regular',
            'color'     => '#6d6c6c',
            'align'     => 'center',
            'transform' => 'none',
        ),
        'linked_to'  => array(
	        'color' => 'theme-text-color',
        ),
        'style'           => array(
            'selectors'  => '.error-404-text',
            'properties' => 'font-size,
                              font-family,
                              font-weight,
                              color,
                              text-transform,
                              text-align'
        )
    ),

    /* Typography and Color > Content > FAQ */
    array(
        'type' => 'title',
        'name' => __( 'FAQ', 'yit' ),
        'desc' => '',
    ),

    array(
        'id'              => 'content-faq-title-font',
        'type'            => 'typography',
        'name'            => __( 'FAQ\'s title font', 'yit' ),
        'desc'            => __( 'Choose the font type, size, text transform and align for faq\'s.', 'yit' ),
        'min'             => 1,
        'max'             => 80,
        'default_font_id' => 'typography-website-title',
        'std'             => array(
            'size'      => 13,
            'unit'      => 'px',
            'family'    => 'default',
            'style'     => '600',
            'color'     => '#6d6c6c',
            'align'     => 'left',
            'transform' => 'uppercase',
        ),
        'linked_to'  => array(
	        'color' => 'theme-text-color',
        ),
        'style'           => array(
            'selectors'  => '#faqs-container .faq-wrapper .faq-title h4, .filters li a',
            'properties' => 'font-size,
                             font-family,
                             font-weight,
                             color,
                             text-transform,
                             text-align'
        ),
    ),



    /* Typography and Color > Content > Blog & Portfolios */
    array(
        'type' => 'title',
        'name' => __( 'Blog & Portfolios', 'yit' ),
        'desc' => '',
    ),

    array(
        'id'              => 'content-blog-portfolios-title-font',
        'type'            => 'typography',
        'name'            => __( 'Blog & Portfolios page title font', 'yit' ),
        'desc'            => __( 'Choose the font type, size, text transform and align for blog and portfolios page.', 'yit' ),
        'min'             => 1,
        'max'             => 80,
        'default_font_id' => 'typography-website-title',
        'std'             => array(
            'size'      => 18,
            'unit'      => 'px',
            'family'    => 'default',
            'style'     => '600',
            'align'     => 'center',
            'transform' => 'uppercase',
        ),
        'style'           => array(
            'selectors'  => '.blog h3.post-title a,
                            .share-container .share-text,
                            #portfolio_big li .info h3.title a',
            'properties' => 'font-size,
                             font-family,
                             font-weight,
                             text-transform,
                             text-align'
        ),
    ),

    array(
        'id'         => 'content-blog-portfolios-title-link-color',
        'type'       => 'colorpicker',
        'variations' => array(
            'normal' => __( 'Title Color', 'yit' ),
            'hover'  => __( 'Title Color Hover', 'yit' )
        ),
        'linked_to'  => array(
            'hover' => 'theme-color-2'
        ),
        'name'       => __( 'Blog & Portfolios Title Color', 'yit' ),
        'desc'       => __( 'Select the colors to use for the links title in normal state and on hover.', 'yit' ),
        'std'        => array(
            'color' => array(
                'normal' => '#454545',
                'hover'  => '#871818'
            )
        ),
        'style'      => array(
            'normal' => array(
                'selectors'  => '.blog h3.post-title a,
                                 .blog h2.quote-title a,
                                 .blog_section.post_meta .title a,
                                 #portfolio_big li .info h3.title a,
                                 .cta-phone.call-to-action .cta-phone-phone,
                                 #portfolio_nav .title h1.portfolios-title',
                'properties' => 'color'
            ),
            'hover'  => array(
                'selectors'  => '.blog h3.post-title a:hover,
                                 .blog h2.quote-title a:hover,
                                 .blog_section.post_meta .title a:hover,
                                 #portfolio_big li .info h3.title a:hover',
                'properties' => 'color'
            )
        ),
    ),

    array(
        'id'              => 'content-blog-meta-font',
        'type'            => 'typography',
        'name'            => __( 'Meta info box', 'yit' ),
        'desc'            => __( 'Choose the font type, size and color for meta info box.', 'yit' ),
        'min'             => 1,
        'max'             => 80,
        'default_font_id' => 'typography-website-paragraph',
        'std'             => array(
            'size'      => 9,
            'unit'      => 'px',
            'family'    => 'default',
            'color'     => '#5b5a5a',
            'align'     => 'left',
            'transform' => 'uppercase',
        ),
        'style'           => array(
            'selectors'  => '.blog .yit_post_meta,
                            .yit_shortcodes.recent-post .blog .yit_post_meta,
                            .yit_shortcodes.recent-post .yit_post_meta span.author,
                            .yit_shortcodes.recent-post span.author a,
                            .widget.yit-recent-posts .recent-post span.author,
                            .widget.yit-recent-posts .recent-post span.author a,
                            .widget.yit-recent-posts .recent-post span.num-comments,
                            .widget.yit-recent-posts .recent-post span.num-comments a,
                            .widget.yit-recent-posts .recent-post span.num-comments,
                            .blog_section.post_meta .info',
            'properties' => 'font-size,
                              font-family,
                              color,
                              text-transform,
                              text-align'
        )
    ),

    array(
        'id'         => 'content-blog-meta-link-hover-color',
        'type'       => 'colorpicker',
        'variations' => array(
            'normal' => __( 'Meta Link', 'yit' ),
            'hover'  => __( 'Meta Link Hover', 'yit' )
        ),
        'linked_to'  => array(
            'hover' => 'theme-color-2'
        ),
        'name'       => __( 'Blog & Portfolios Meta Links', 'yit' ),
        'desc'       => __( 'Select the colors to use for the links in normal state and on hover.', 'yit' ),
        'std'        => array(
            'color' => array(
                'normal' => '#6b6868',
                'hover'  => '#871818'
            )
        ),
        'style'      => array(
            'normal' => array(
                'selectors'  => '.blog .yit_post_meta a:not(.social-icon),
                                .blog .yit_post_meta a:visited:not(.social-icon),
                                #portfolio_small li .info .categories a[rel=tag],
                                .blog_section.post_meta .info a',
                'properties' => 'color'
            ),
            'hover'  => array(
                'selectors'  => '.blog .yit_post_meta a:hover:not(.social-icon),
                                 .blog .yit_post_meta a:active:not(.social-icon),
                                 #portfolio_small li .info .categories a[rel=tag]:hover,
                                 .blog_section.post_meta .info a:hover',
                'properties' => 'color'
            )
        ),
    ),

    array(
        'id'              => 'content-blog-font',
        'type'            => 'typography',
        'name'            => __( 'Content font', 'yit' ),
        'desc'            => __( 'Choose the font type, size and color for content.', 'yit' ),
        'min'             => 1,
        'max'             => 80,
        'default_font_id' => 'typography-website-paragraph',
        'std'             => array(
            'size'      => 13,
            'unit'      => 'px',
            'family'    => 'default',
            'style'     => '500',
            'color'     => '#7b7b7b',
            'align'     => 'left',
            'transform' => 'none',
        ),
        'style'           => array(
            'selectors'  => '.blog .yit_post_content p',
            'properties' => 'font-size,
                              font-family,
                              font-weight,
                              color,
                              text-align,
                              text-transform'
        ),
    ),

    array(
        'id'         => 'content-blog-date-background-color-s1',
        'type'       => 'colorpicker',
        'name'       => __( 'Blog: Post date background and text color (Style 1)', 'yit' ),
        'desc'       => __( 'Select the background color to use for the post date box.', 'yit' ),
        'variations' => array(
            'background'    => __( 'Background', 'yit' ),
            'day-color'     => __( 'Text Day', 'yit' ),
            'month-color'   => __( 'Text Month', 'yit' ),
        ),
        'std'        => array(
            'color' => array(
                'background'    => '#ffffff',
                'day-color'     => '#6d6c6c',
                'month-color'   => '#6d6c6c'
            )
        ),
        'linked_to'  => array(
            'background' => 'general-background-color',
            'color'      => 'theme-color-3'
        ),
        'style'      => array(
            'background' => array(
                'selectors'  => '.blog .yit_post_meta_date.normal,
                                .blog_section .yit_post_meta_date',
                'properties' => 'background-color'
            ),
            'day-color'      => array(
                'selectors'  => '.blog .yit_post_meta_date.normal .day,
                                 .widget.yit-recent-posts .recent-post .hentry-post p.post-date .day,
                                 .blog .yit_post_meta_date.normal .day,
                                 .blog_section .yit_post_meta_date .day',
                'properties' => 'color'
            ),
            'month-color'      => array(
                'selectors'  => '.blog .yit_post_meta_date.normal .month,
                                 .widget.yit-recent-posts .recent-post .hentry-post p.post-date .month,
                                 .blog .yit_post_meta_date.normal .month,
                                 .blog_section .yit_post_meta_date .month',
                'properties' => 'color'
            )
        ),
    ),

    array(
        'id'         => 'content-blog-date-background-color-s2',
        'type'       => 'colorpicker',
        'name'       => __( 'Blog: Post date background and text color (Style 2)', 'yit' ),
        'desc'       => __( 'Select the background color to use for the post date box.', 'yit' ),
        'variations' => array(
            'background-day'        => __( 'Background Day', 'yit' ),
            'background-month'      => __( 'Background Month', 'yit' ),
            'day-color'             => __( 'Text Day', 'yit' ),
            'month-color'           => __( 'Text Month', 'yit' ),
        ),
        'std'        => array(
            'color' => array(
                'background-day'        => '#040404',
                'background-month'      => '#c11200',
                'day-color'             => '#ffffff',
                'month-color'           => '#ffffff'
            )
        ),
        'style'      => array(
            'background-day' => array(
                'selectors'  => '.blog .yit_post_meta_date.alternative .day,
                                 .widget.yit-recent-posts .recent-post .hentry-post p.post-date.alternative .day,
                                 .blog_section .yit_post_meta_date.alternative .day',
                'properties' => 'background-color'
            ),
            'background-month' => array(
                'selectors'  => '.blog .yit_post_meta_date.alternative .month,
                                 .widget.yit-recent-posts .recent-post .hentry-post p.post-date.alternative .month,
                                 .blog_section  .yit_post_meta_date.alternative .month',
                'properties' => 'background-color'
            ),
            'day-color'      => array(
                'selectors'  => '.blog .yit_post_meta_date.alternative .day,
                                 .widget.yit-recent-posts .recent-post .hentry-post p.post-date.alternative .day,
                                 .blog_section  .yit_post_meta_date.alternative .day',
                'properties' => 'color'
            ),
            'month-color'      => array(
                'selectors'  => '.blog .yit_post_meta_date.alternative .month,
                                 .widget.yit-recent-posts .recent-post .hentry-post p.post-date.alternative .month,
                                 .blog_section  .yit_post_meta_date.alternative .month',
                'properties' => 'color'
            )
        ),
    ),


    /* Typography and Color > Content > Comments */
    array(
        'type' => 'title',
        'name' => __( 'Comments', 'yit' ),
        'desc' => ''
    ),

    array(
        'id'              => 'content-comments-font',
        'type'            => 'typography',
        'name'            => __( 'Comments Link font', 'yit' ),
        'desc'            => __( 'the font type, size, text transform and align.', 'yit' ),
        'min'             => 1,
        'max'             => 80,
        'default_font_id' => 'typography-website-title',
        'std'             => array(
            'size'      => 14,
            'unit'      => 'px',
            'family'    => 'default',
            'style'     => 'regular',
            'transform' => 'uppercase',
            'color'     => '#626262'
        ),
        'style'           => array(
            'selectors'  => '.reply_link, #commentform .logged-in-as a, .comment-navigation .nav-previous a, .comment-navigation .nav-next a',
            'properties' => 'font-size,
                             font-family,
                             font-weight,
                             text-transform,
                             color'
        )
    ),

    /* Typography and Color > Content > Pagination */
    array(
        'type' => 'title',
        'name' => __( 'Pagination', 'yit' ),
        'desc' => ''
    ),

    array(
        'id'              => 'content-pagination-font',
        'type'            => 'typography',
        'name'            => __( 'Pagination font', 'yit' ),
        'desc'            => __( 'the font type, size, text transform and align.', 'yit' ),
        'min'             => 1,
        'max'             => 80,
        'default_font_id' => 'typography-website-title',
        'std'             => array(
            'size'   => 14,
            'unit'   => 'px',
            'family' => 'default',
            'style'  => 'regular',
            'align'  => 'right',
        ),
        'style'           => array(
            'selectors'  => '.general-pagination, .woocommerce-pagination',
            'properties' => 'font-size,
                             font-family,
                             font-weight,
                             text-align'
        )
    ),

    array(
        'id'         => 'content-pagination-text-color',
        'type'       => 'colorpicker',
        'variations' => array(
            'normal'   => __( 'Normal Color', 'yit' ),
            'hover'    => __( 'Hover Color', 'yit' ),
            'selected' => __( 'Selected Color', 'yit' )
        ),
        'name'       => __( 'Pagination Number Color', 'yit' ),
        'desc'       => __( 'Select the colors to use for the pagination links.', 'yit' ),
        'std'        => array(
            'color' => array(
                'normal'   => '#6d6c6c',
                'hover'    => '#6d6c6c',
                'selected' => '#6d6c6c',
            )
        ),
        'linked_to'  => array(
            'normal'    => 'theme-color-3',
            'hover'     => 'theme-color-3',
            'selected'  => 'theme-color-3',
        ),
        'style'      => array(
            'normal'   => array(
                'selectors'  => '.general-pagination a, .woocommerce-pagination a',
                'properties' => 'color'
            ),
            'hover'    => array(
                'selectors'  => '.general-pagination a:hover, #commentform .logged-in-as a:hover, .comment-navigation .nav-previous a:hover, .comment-navigation .nav-next a:hover,
                                 .woocommerce-pagination a:hover',
                'properties' => 'color'
            ),
            'selected' => array(
                'selectors'  => '.general-pagination a.selected, .general-pagination a:hover.selected, .woocommerce-pagination span',
                'properties' => 'color'
            )
        )
    ),

    array(
        'id'         => 'content-pagination-background-color',
        'type'       => 'colorpicker',
        'variations' => array(
            'normal'   => __( 'Normal Color', 'yit' ),
            'hover'    => __( 'Hover Color', 'yit' ),
            'selected' => __( 'Selected Color', 'yit' )
        ),
        'name'       => __( 'Pagination Background Color', 'yit' ),
        'desc'       => __( 'Select the colors to use for the pagination links.', 'yit' ),
        'std'        => array(
            'color' => array(
                'normal'   => '#ffffff',
                'hover'    => '#ffffff',
                'selected' => '#ffffff',
            )
        ),
        'style'      => array(
            'normal'   => array(
                'selectors'  => '.general-pagination a',
                'properties' => 'background-color'
            ),
            'hover'    => array(
                'selectors'  => '.general-pagination a:hover',
                'properties' => 'background-color'
            ),
            'selected' => array(
                'selectors'  => '.general-pagination a.selected',
                'properties' => 'background-color'
            )
        )
    ),
);

