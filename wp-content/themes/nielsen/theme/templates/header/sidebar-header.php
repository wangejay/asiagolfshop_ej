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

if ( ! function_exists('WC') || 'no' == yit_get_option('shop-mini-cart-show-in-header') ) {
	return;
}
?>

<!-- START HEADER SIDEBAR -->
<div id="header-sidebar" class="nav">
    <?php the_widget( 'YIT_Widget_Cart' ); ?>
</div>
<!-- END HEADER SIDEBAR -->
