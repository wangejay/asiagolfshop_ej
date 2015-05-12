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
 * Template file for show a title centered with line
 *
 * @package Yithemes
 * @author  Emanuela Castorina <emanuela.castorina@yithemes.com>
 * @since   1.0.0
 */

$class          = ( isset( $class ) ) ? $class : '';
$icon_theme     = ( isset( $icon_theme ) && $icon_theme!= '' ) ? $icon_theme : false;

?>
<div class="cta-phone <?php echo esc_attr( $class ) ?>">
    <?php if( isset($title) && $title !='' ): ?>
        <h3><?php echo $title ?></h3>
    <?php endif ?>

    <?php if( isset($phone) && $phone !='' ): ?>
        <div class="cta-phone-phone">
            <?php if( $icon_theme ): ?>
                <span  <?php echo YIT_Icon()->get_icon_data( $icon_theme ) ?>></span>
            <?php endif ?>

           <?php echo $phone ?>
        </div>
    <?php endif ?>

    <?php if( isset($content) && $content !='' ): ?>
        <div class="cta-phone-content"><?php echo $content ?></div>
    <?php endif ?>
</div>