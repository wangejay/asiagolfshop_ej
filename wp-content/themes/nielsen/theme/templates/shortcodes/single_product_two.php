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
 * Template file for show the products
 *
 * @package Yithemes
 * @author  Francesco Licandro <francesco.licandro@yithemes.com>
 * @since   1.0.0
 */

global $yit_products_layout, $woocommerce_loop;

$woocommerce_loop['view'] = 'list';
$product_in_a_row = 1;

$query_args = array(
    'posts_per_page' => 1,
    'p'              => $product_id,
    'post_type'      => 'product',
);

$products = new WP_Query( $query_args );
$border = ( isset($border) && $border != '' ) ?  $border : 'none';
$animate_data   = ( $animate != '' ) ? 'data-animate="' . $animate . '"' : '';
$animate_data  .= ( $animation_delay != '' ) ? ' data-delay="' . $animation_delay . '"' : '';
$animate        = ( $animate != '' ) ? ' yit_animate' : '';

if ( $products->have_posts() ) : ?>
    <div class="woocommerce show-single-product show-products-list <?php echo 'border-'. $border . ' ' . $animate ?>" <?php echo $animate_data ?>>
        <div class="row">
            <ul class="products">

                <?php while ( $products->have_posts() ) : $products->the_post(); ?>

                    <?php wc_get_template( 'content-product.php', array('product_in_a_row' => $product_in_a_row ) ); ?>

                <?php endwhile; // end of the loop. ?>

            </ul>
        </div>
    </div>
    <div class="clear"></div>
<?php endif;

wp_reset_query();

$woocommerce_loop['loop'] = 0;

?>
