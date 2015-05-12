<?php
/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * Template file for shows a box, with Title and icons on left and a text of section (you can use HTML tags)'
 *
 * @package Yithemes
 * @author Francesco Licandro <francesco.licandro@yithemes.com>
 * @since 1.0.0
 */

$a_before = $a_after = '';

$layout = isset( $layout ) ? esc_attr( $layout ) : '';

if ( ! isset( $title_size ) || $title_size == '' ) {
    $title_size = 'h4';
}

if ( ! empty( $link ) ) {
    $link = esc_url( $link );
    if ( ! empty( $link_title ) ) {
        $link_title = ' title="' . $link_title . '"';
    }
    $a_before = '<a href="' . $link . '"' . $link_title . '>';
    $a_after  = '</a>';
}

$icon_type = ( $icon_type == '' ) ? 'theme-icon' : $icon_type;

$animate_data   = ( $animate != '' ) ? 'data-animate="' . $animate . '"' : '';
$animate_data  .= ( $animation_delay != '' ) ? ' data-delay="' . $animation_delay . '"' : '';
$animate        = ( $animate != '' ) ? ' yit_animate' : '';

$margin_left = ( $layout == 'horizontal' ) ? 75: 0;


?>

<div class="clearfix box-sections <?php echo esc_attr( $class . $last_class . $animate ); ?> <?php echo $layout ?>" <?php echo $animate_data ?>>
    <?php

    echo $a_before;
    echo '<div class="box-icon">';

    if ( $icon_type == 'theme-icon' ) {

        $icon_data = YIT_Icon()->get_icon_data( $icon_theme );

        $margin_left = ( $circle_size != 0 && $layout == 'horizontal' ) ? ( $circle_size + 30 ) : $margin_left;

        echo '<span class="icon-circle" style="border-width:' . $border_size . 'px;width:' . $circle_size . 'px; height:' . $circle_size . 'px;border-color: ' . $color_circle . ';">';

        $color     = ( $color == '' ) ? '' : 'color:' . $color;
        $icon_size = ( $icon_size == '' ) ? '14' : $icon_size;
        echo '<span class="icon"><i ' . $icon_data. '" style="' . $color . '; font-size:' . $icon_size . 'px"></i></span>';
        echo '</span>';

    }elseif (  strcmp ( $icon_custom , '' ) != 0  ) {
        $image = yit_image( "echo=no&src=" . $icon_custom . "&getimagesize=1");
        echo '<span class="icon">' . $image . '</span>';
    }

    echo '</div><div class="box-content" style="margin-left:' . $margin_left . 'px">';
    if ( $title != '' ) : echo '<' . $title_size . '>' . $title . '</' . $title_size . '>'; endif;

    echo $a_after;

    ?>
    <?php echo wpautop(do_shortcode($content)); ?>
    <?php echo '</div>' ?>
</div>
