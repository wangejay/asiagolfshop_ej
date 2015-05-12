<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

$image_size['height'] = ( $blog_type == 'big' && is_singular( 'post' ) ) ? ( (int) $image_size['height'] * 1.5 )  : $image_size['height'];

$video = array(
    'id'     => preg_replace( '/[&|&amp;]feature=([\w\-]*)/', '', yit_get_post_meta( get_the_ID(), '_video-id' ) ),
    'host'   => yit_get_post_meta( get_the_ID(), '_video-host' ),
    'width'  => '100%',
    'height' => $image_size['height']
);

$layout_sidebar = YIT_Layout()->sidebars['layout'];

if( $layout_sidebar == 'sidebar-no' ){
    $bootstrap_cols = "col-sm-5 col-xs-12";
} elseif ( $layout_sidebar == 'sidebar-double' ) {
    $bootstrap_cols = "col-xs-12";
}else{
    $bootstrap_cols = "col-sm-6 col-xs-12";
}


extract( $video );

switch ( $video['host'] ) {

    case 'youtube':
        ?>
        <div class="post-format <?php echo esc_attr( $post_format ) ?>  <?php if( 'small' == $blog_type ) echo esc_attr( $bootstrap_cols ) ?>">
            <div class="post_video youtube">
                <iframe wmode="transparent" width="<?php echo esc_attr( $width ); ?>" height="<?php echo esc_attr( $height ); ?>" src="<?php echo esc_url( 'http://www.youtube.com/embed/' . $id . '?wmode=transparent' ) ?>" frameborder="0" allowfullscreen></iframe>
            </div>
        </div><?php
        break;

    case 'vimeo':
        ?>
        <div class="post-format <?php echo esc_attr( $post_format ) ?> <?php if( 'small' == $blog_type ) echo esc_attr( $bootstrap_cols ); ?>">
            <div class="post_video vimeo">
                <iframe wmode="transparent" src="<?php echo esc_url( 'http://player.vimeo.com/video/'. $id . '?title=0&amp;byline=0&amp;portrait=0' ) ?>" width="<?php echo esc_attr( $width ); ?>" height="<?php echo esc_attr( $height ); ?>" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
            </div>
        </div><?php
        break;
}



