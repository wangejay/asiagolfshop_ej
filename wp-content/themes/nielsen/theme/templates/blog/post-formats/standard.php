<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

$image_size = 'blog_' . $blog_type;

$col_class = 'col-sm-4 col-xs-5';

if( 'big' == $blog_type || is_singular( 'post' ) ){
    $col_class = '';
}

?>

<div class="<?php echo ( $show_thumbnail ) ? 'thumbnail ' . $col_class : 'no-thumbnail' ?> <?php echo esc_attr( $blog_type ) ?>">
    <?php if( $show_thumbnail ) : ?>
        <?php if( ! is_singular( 'post' ) ) : ?>
            <a href="<?php echo isset( $link ) ? esc_url( $link ) : get_the_permalink(); ?>">
        <?php endif; ?>
            <?php yit_image( 'size=' . $image_size . '&class=img-responsive' ); ?>
            <?php if( ! is_singular('post') && $show_post_format_icon ) : ?>
                <span class="yit_post_format_icon hidden-xs" data-icon="<?php yit_get_blog_post_format_icon( $post_format ) ?>;" data-font="retinaicon-font"></span>
            <?php endif; ?>
        <?php if( ! is_singular( 'post' ) ) : ?>
            </a>
        <?php endif; ?>
    <?php endif; ?>
</div>
