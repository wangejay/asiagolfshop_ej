<?php
/**
 * Number of products on shop page
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if ( is_single() || ! have_posts() ) return;

$num_prod = ( isset( $_GET['products-per-page'] ) ) ? $_GET['products-per-page'] : yit_get_option( 'shop-products-per-page' );

$num_prod_x1 = yit_get_option( 'shop-products-per-page' );
$num_prod_x2 = $num_prod_x1 * 2;
?>

<div id="number-of-products">
    <span class="view-title"><?php _e( 'View:', 'yit' ) ?> </span>
    <a class="view-12<?php if ( $num_prod == $num_prod_x1 ) echo ' active'; ?>" href="<?php echo esc_url( add_query_arg( 'products-per-page', $num_prod_x1, $link ) ) ?>"><?php echo $num_prod_x1 ?></a>
    <a class="view-24<?php if ( $num_prod == $num_prod_x2 ) echo ' active'; ?>" href="<?php echo esc_url( add_query_arg( 'products-per-page', $num_prod_x2, $link ) ) ?>"><?php echo $num_prod_x2 ?></a>
    <a class="view-all<?php if ( $num_prod == 'all' ) echo ' active'; ?>" href="<?php echo esc_url( add_query_arg( 'products-per-page', 'all', $link ) ) ?>"><?php _e( 'ALL', 'yit' ) ?></a>
</div>
