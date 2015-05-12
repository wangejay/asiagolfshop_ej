<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>
<div class="submenu-group">
    <?php
    wp_nav_menu( array(
        'theme_location' => 'shop-by-category',
        'container' => 'div',
        'container_class' => 'submenu clearfix',
        'depth' => 3,
        'walker' => new YIT_Walker_Nav_Menu_Div()
    ));

    wp_nav_menu( array(
        'theme_location' => 'shop-by-category-2',
        'container' => 'div',
        'container_class' => 'submenu clearfix',
        'depth' => 3,
        'walker' => new YIT_Walker_Nav_Menu_Div()
    ));
    ?>
</div>