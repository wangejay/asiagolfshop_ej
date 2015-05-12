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

$static_image = (bool)( YIT_Layout()->static_image == 'yes' || get_header_image() != '' );
$opened = 'yes' == yit_get_option('header-cat-dropdow-opened') && ( $static_image || ! in_array( YIT_Layout()->slider_name, array( '', 'none' ) ) ) ? true : false;
$can_close_class ='';

if ( $opened ) {
	add_action( 'yit_slider_append', 'yit_shop_by_category_nav_wrapper_start' );
	add_action( 'yit_slider_append', 'yit_shop_by_category_nav' );
	add_action( 'yit_slider_append', 'yit_shop_by_category_nav_wrapper_end' );

    if (yit_get_option('header-cat-dropdow-opened-can-close') == 'yes' ) {
        $can_close_class = 'can-close';
    }
}
?>

<div class="shop-by-category border-line-2 nav vertical<?php if ( $opened ) echo ' opened '.$can_close_class ?>">

	<a href="#" class="list-trigger"><?php echo yit_get_option('header-shop-cat-title') ?><span class="sbToggle"></span></a>

	<?php if ( ! $opened ) yit_shop_by_category_nav(); ?>

</div>