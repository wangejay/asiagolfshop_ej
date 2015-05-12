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
 * Template file for print share buttons
 *
 * @package Yithemes
 * @author Emanuela Castorina <emanuela.castorina@yithemes.com>
 * @since 1.0.0
 */

if ( !isset( $category ) ) {
    return;
}

$cat = get_term_by( 'slug', $category, 'product_cat' );

$thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true );
$image        = wp_get_attachment_image_src( $thumbnail_id, 'show_category_thumb' );

$prods = new WP_Query(
    array(
        'product_cat'    => $category,
        'posts_per_page' => 2,
        'post_type'      => 'product',
        'orderby'        => 'meta_value',
        'meta_key'       => '_featured'
    )
);

$animate_data   = ( $animate != '' ) ? 'data-animate="' . $animate . '"' : '';
$animate_data  .= ( $animation_delay != '' ) ? ' data-delay="' . $animation_delay . '"' : '';
$animate        = ( $animate != '' ) ? ' yit_animate' : '';

?>
<div class="show-category <?php echo $animate ?>" <?php echo $animate_data ?>>
    <div class="category-thumbs-wrapper row">
        <div class="category-thums col-sm-8 col-xs-8">
            <?php if( !empty( $image ) ): ?>
                <a href="<?php echo get_term_link( $cat, 'product_cat') ?>"><img src="<?php echo esc_url( $image[0] ) ?>" alt="<?php echo esc_attr($cat->name) ?>" /></a>
            <?php endif ?>
        </div>
        <div class="category-products col-sm-4 col-xs-4">
            <?php if( $prods->have_posts() ):
                    while ( $prods->have_posts() ):
                        $prods->the_post();
                        if ( has_post_thumbnail() ): ?>
                            <a href="<?php the_permalink() ?>" class="with-tooltip category-products-single" title="<?php echo the_title() ?>"><?php the_post_thumbnail('shop_catalog'); ?></a>
            <?php
                    endif;
                endwhile;
            endif ?>
        </div>
    </div>
    <div class="category-meta-wrapper row">
        <div class="category-meta-text col-sm-8">
            <div class="category-title"><?php echo $cat->name ?></div>
            <div class="category-description"><?php echo $cat->description ?></div>
        </div>
        <div class="category-meta col-sm-4">
            <?php echo '<div class="count">' . $cat->count . _n( " item", " items", $cat->count, "yit" ) . '</div>' ?>
            <a href="<?php echo get_term_link( $cat, 'product_cat') ?>"><?php _e('Shop now >', 'yit') ?></a>
        </div>
    </div>
</div>
