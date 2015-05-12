<?php
/**
 * Single Product Image
 *
 * @author 		YIThemes
 * @package 	YITH_Magnifier/Templates
 * @version     1.0.0
 */

global $post, $woocommerce, $product, $yith_wcmg;

$columns = apply_filters( 'woocommerce_product_thumbnails_columns', get_option( 'yith_wcmg_slider_items', 4 ) );

$enable_slider = (bool) ( get_option('yith_wcmg_enableslider') == 'yes' && ( count( $product->get_gallery_attachment_ids() ) + 1 ) > $columns );

$size = yit_image_content_single_width();

$style = "";

if ( ! empty( $size ) ) {
    $style = 'width:' . $size['image'] . '%';
}

?>

    <div class="images" style="<?php echo esc_attr( $style ); ?>">

        <?php if( ! yith_wcmg_is_enabled() ): ?>

            <!-- Default Woocommerce Template -->
            <?php if ( has_post_thumbnail() ) : ?>

                <a itemprop="image" href="<?php echo esc_url( wp_get_attachment_url( get_post_thumbnail_id() ) ); ?>" class="zoom" rel="thumbnails[product-gallery]" title="<?php echo esc_attr( get_the_title( get_post_thumbnail_id() ) ); ?>"><?php yit_image( array( 'size' => apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ) ) ) ?></a>

            <?php else : ?>

                <img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" alt="Placeholder" />

            <?php endif; ?>
        <?php else: ?>

            <!-- YITH Magnifier Template -->
            <?php if ( has_post_thumbnail() ) : ?>

                <a itemprop="image" href="<?php esc_url( yit_image( 'size=shop_magnifier&output=url' ) ); ?>" class="yith_magnifier_zoom" rel="thumbnails"><?php yit_image( array( 'size' => apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ) ) ) ?></a>

            <?php else: ?>

                <img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" alt="Placeholder" />

            <?php endif ?>

        <?php endif ?>


        <?php do_action('woocommerce_product_thumbnails'); ?>

    </div>

<?php if( yith_wcmg_is_enabled() ): ?>
    <script type="text/javascript" charset="utf-8">
        var yith_magnifier_options = {
            enableSlider: <?php echo $enable_slider ? 'true' : 'false' ?>,
            slider: 'owlCarousel',

            <?php if( $enable_slider ): ?>
            sliderOptions: {
                items: <?php echo esc_js( apply_filters( 'woocommerce_product_thumbnails_columns', $columns ) ) ?>,
                nav: true,
                navText : ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>']
            },
            <?php endif ?>

            showTitle: false,
            zoomWidth: '<?php echo esc_js( get_option('yith_wcmg_zoom_width') )  ?>',
            zoomHeight: '<?php echo esc_js( get_option('yith_wcmg_zoom_height') ) ?>',
            position: '<?php echo esc_js( get_option('yith_wcmg_zoom_position') ) ?>',
            tint: <?php echo esc_js( get_option('yith_wcmg_tint') == '' ? 'false' : "'". get_option('yith_wcmg_tint')."'" ) ?>,
            lensOpacity: <?php echo esc_js( get_option('yith_wcmg_lens_opacity') ) ?>,
            softFocus: <?php echo esc_js( get_option('yith_wcmg_softfocus') == 'yes' ? 'true' : 'false' ) ?>,
            adjustY: 0,
            disableRightClick: false,
            phoneBehavior: '<?php echo esc_js( get_option('yith_wcmg_zoom_mobile_position') ) ?>',
            loadingLabel: '<?php echo esc_js( stripslashes(get_option('yith_wcmg_loading_label') ) ) ?>'
        };
    </script>
<?php endif ?>