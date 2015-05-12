<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

global $more, $wp_query;

$more                    = true;
$has_tags                = ( ! get_the_tags() ) ? false : true;
$hide_footer             = ( yit_get_option( 'blog-single-hide-footer' ) == 'yes' ) ? true : false;
$post_format             = ( ! get_post_format() ) ? 'standard' : get_post_format();
$is_quote                = ( $post_format == 'quote' ) ? true : false;
$show_thumbnail          = ( yit_get_option( 'blog-single-show-featured-image' ) == 'yes' && has_post_thumbnail() && $post_format == 'standard' ) ? true : false;
$show_title              = ( yit_get_option( 'blog-single-show-title' ) == 'yes' ) ? true : false;
$show_date               = ( yit_get_option( 'blog-single-show-date' ) == 'yes' ) ? true : false;
$show_author             = ( yit_get_option( 'blog-single-show-author' ) == 'yes'  && get_the_author() != false ) ? true : false;
$show_categories         = ( yit_get_option( 'blog-single-show-categories' ) == 'yes' ) ? true : false;
$show_tags               = ( yit_get_option( 'blog-single-show-tags' ) == 'yes' ) ? true : false;
$show_comments           = ( yit_get_option( 'blog-single-show-comments' ) == 'yes' ) ? true : false;
$show_read_more          = ( yit_get_option( 'blog-single-show-read-more' ) == 'yes' ) ? true : false;
$show_share              = ( yit_get_option( 'blog-single-show-share' ) == 'yes' ) ? true : false;
$show_meta_box_1         = ( $show_author || $show_date || $show_comments || $show_categories ) ? true : false;
$show_meta_box_2         = ( ( $show_tags && $has_tags ) || $show_share ) ? true : false;
$share_text              = yit_get_option( 'blog-single-share-text' );
$title                   = ( get_the_title() != '' ) ? get_the_title() : __( '(this post does not have a title)', 'yit' );
$post_meta_separator     = apply_filters( 'yit_blog_post_meta_separator', ' - ' );
$link                    = get_permalink();
$has_pagination          = ( $wp_query->max_num_pages > 1 ) ? true : false;
$image_size              = YIT_Registry::get_instance()->image->get_size( 'blog_' . $blog_type );
$date_style              = ( 'style-1' == yit_get_option( 'blog-date-style' ) ? 'normal border-2' : 'alternative' );

$args = array(
    'show_thumbnail'        => $show_thumbnail,
    'show_title'            => $show_title,
    'show_date'             => $show_date,
    'show_author'           => $show_author,
    'show_categories'       => $show_categories,
    'show_tags'             => $show_tags,
    'show_comments'         => $show_comments,
    'show_share'            => $show_share,
    'show_read_more'        => $show_read_more,
    'show_meta_box_1'       => $show_meta_box_1,
    'show_meta_box_2'       => $show_meta_box_2,
    'share_text'            => $share_text,
    'title'                 => $title,
    'post_meta_separator'   => $post_meta_separator,
    'blog_type'             => $blog_type,
    'link'                  => $link,
    'has_tags'              => $has_tags,
    'has_pagination'        => $has_pagination,
    'post_format'           => $post_format,
    'image_size'            => $image_size,
    'is_quote'              => $is_quote,
    'hide_footer'           => $hide_footer,
    'date_style'            => $date_style

);

yit_get_template( 'blog/' . $blog_type . '/single.php', $args );

if ( function_exists( 'yit_pagination' ) && $has_pagination ) {
    yit_pagination();
}

if( YIT_Request()->is_ajax ){
    yit_get_comments_template();
}

?>