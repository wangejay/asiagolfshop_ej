<?php
/*
 * This file belongs to the YIT Framework.
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
 * @author  Emanuela Castorina <emanuela.castorina@yithemes.com>
 * @since   1.0.0
 */

$query_args = array(
    'posts_per_page' => 1,
    'p'              => $product_id,
    'post_type'      => 'product',
);

$products = new WP_Query( $query_args );
$animate_data   = ( $animate != '' ) ? 'data-animate="' . $animate . '"' : '';
$animate_data  .= ( $animation_delay != '' ) ? ' data-delay="' . $animation_delay . '"' : '';
$animate        = ( $animate != '' ) ? ' yit_animate' : '';
$size = ( isset( $size ) ) ? $size : 'small';

if ( $products->have_posts() ) : ?>
    <?php while( $products->have_posts() ): $products->the_post() ?>
        <?php global $product; ?>
        <div class="lookbook-wrapper">
            <div class="lookbook-listed-product">
                <a class="lookbook-thumb" href="<?php echo esc_url( get_permalink( $product->ID ) ); ?>" title="<?php echo esc_attr( $product->post_title ); ?>">
                    <?php echo $product->get_image( 'sc_single_product_' . $size ); ?>
                </a>

                <div class="lookbook-information">
                    <a href="<?php echo esc_url( get_permalink( $product->ID ) ); ?>" title="<?php echo esc_attr( $product->post_title ); ?>">
                        <?php echo $product->get_title(); ?>
                    </a>
                    <div class="lookbook-product-price">
                        <?php echo $product->get_price_html(); ?>
                    </div>
                    <?php if ( 'yes' == get_option( 'woocommerce_enable_review_rating' ) ) echo $product->get_rating_html(); ?>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
<?php endif;

wp_reset_query();
?>
