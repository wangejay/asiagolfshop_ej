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

$sticky = ( yit_get_option('header-sticky') == 'yes' ) ? ' sticky-header' : '';
$skin = apply_filters( 'yit-header-skin', yit_get_option('header-skin'));
$opt_show_search = apply_filters('yit_header_show_search', yit_get_option( 'header-show-search' ));
$search = ( $opt_show_search != 'none' ) ? ' search-' . $opt_show_search : '';
?>

<!-- START HEADER -->
<header id="header" class="clearfix <?php echo esc_attr( $skin . $sticky . $search ) ?><?php if ( 'yes' != yit_get_option('show-dropdown-indicators') ) echo ' no-indicators' ?>">