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

$opt_show_search = apply_filters('yit_header_show_search', yit_get_option( 'header-show-search' ));
$show_search = $opt_show_search != 'none' ? true : false;
$show_cat    = ( yit_get_option( 'header-show-cat' ) == 'yes' && $opt_show_search != 'small' ) ? true : false;

if ( ! $show_search && ! $show_cat ) {
	return;
}

$instance = array(
    'title' => ''
);

if ( $show_search ) : ?>
<div id="header-search">
	<div>
	    <?php

            if ( $show_cat ) {
                yit_shop_by_category();
            }

            if ( class_exists( 'YITH_WCAS' ) ) {
                the_widget( 'YITH_WCAS_Ajax_Search_Widget', $instance );
            } else {
                the_widget( 'WP_Widget_Search', $instance );
            }

	    ?>
	</div>
</div>
<?php endif;