<?php
/**
 * Content wrappers
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$template = get_option( 'template' );

switch( $template ) {
	case 'twentyeleven' :
		echo '<div id="primary"><div id="content" role="main">';
		break;
	case 'twentytwelve' :
		echo '<div id="primary" class="site-content"><div id="content" role="main">';
		break;
	case 'twentythirteen' :
		echo '<div id="primary" class="site-content"><div id="content" role="main" class="entry-content twentythirteen">';
		break;
	case 'twentyfourteen' :
		echo '<div id="primary" class="content-area"><div id="content" role="main" class="site-content twentyfourteen"><div class="tfwc">';
		break;
	default :
		yit_get_template( 'primary/start-primary.php' );

        $sidebar = YIT_Layout()->sidebars;
        $sidebar = is_array( $sidebar ) ? $sidebar : array( 'layout' => $sidebar );
        $content_cols = 12;
        $content_order = '';


        if ( $sidebar['layout'] == 'sidebar-left' ) {
            $content_cols -= 3;
            $content_order = ' col-sm-push-3';
        }
        elseif ( $sidebar['layout'] == 'sidebar-right' ) {
            $content_cols -= 3;
        }
        elseif ( $sidebar['layout'] == 'sidebar-double' && $sidebar['sidebar-left'] != '-1' ) {
            $content_cols -= 6;
            $content_order = ' col-sm-push-3';
        }

        ?>

        <!-- START CONTENT -->
        <div class="content col-sm-<?php echo esc_attr( $content_cols ) ?><?php echo $content_order ?> clearfix" role="main">

        <?php
        if ( YIT_Layout()->show_breadcrumb == 1 ){
            do_action( 'yit_single_page_breadcrumb' );
        }
        ?>

        <?php
		break;
}