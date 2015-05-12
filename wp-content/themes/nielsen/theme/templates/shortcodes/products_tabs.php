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
 * Template file for list products in tabs
 *
 * @package Yithemes
 * @author Francesco Licandro <francesco.licandro@yithemes.com>
 * @since 1.0.0
 */

wp_enqueue_script('owl-carousel');

global $yit_products_tabs_index;
if ( ! isset( $yit_products_tabs_index )  ) $yit_products_tabs_index = 0;

$sc = array();
$animate_data   = ( $animate != '' ) ? 'data-animate="' . $animate . '"' : '';
$animate_data  .= ( $animation_delay != '' ) ? ' data-delay="' . $animation_delay . '"' : '';
$animate        = ( $animate != '' ) ? ' yit_animate' : '';

?>

<div class="tabs-container products_tabs <?php echo $animate ?>" <?php echo $animate_data ?>>
    <ul class="tabs">
        <?php for( $i = 1; isset( $atts['title_'.$i] ); $i++ ) :
            $title = ( !empty( $atts['title_'.$i] ) ) ? $atts['title_'.$i] : '' ;
            $product_in_a_row = ( ! empty( $atts['product_in_a_row_'.$i ] ) ) ? $atts['product_in_a_row_'.$i] : '4' ;
            $per_page = ( !empty( $atts['per_page_'.$i] ) ) ? $atts['per_page_'.$i] : '-1' ;
            $category = ( !empty( $atts['category_'.$i] ) ) ? $atts['category_'.$i] : '0' ;
            $show = ( !empty( $atts['show_'.$i] ) ) ? $atts['show_'.$i] : 'all' ;

            $product_type = '';

            if ( $show == 'featured' ) { $product_type = 'featured'; }
            elseif ( $show == 'onsale' ) { $product_type = 'on_sale'; }

            $layout = ( !empty( $atts['layout_'.$i] ) ) ? $atts['layout_' . $i] : 'default';
            $orderby = ( !empty( $atts['orderby_'.$i] ) ) ? $atts['orderby_'.$i] : 'rand' ;
            $order = ( !empty( $atts['order_'.$i] ) ) ? $atts['order_'.$i] : '0' ;

            echo '<li><h4><a href="#" data-tab="tab-' . $yit_products_tabs_index . '" title="' . $title . '">' . $title . '</a></h4></li>';
            $sc[$yit_products_tabs_index] = '[products_slider title="' . $title . '" per_page="' . $per_page . '" product_type="'.$product_type.'" category="' . $category . '" orderby="' . $orderby . '" order="' . $order . '" layout="'. $layout .'" product_in_a_row="'. $product_in_a_row .'" ]';
            $yit_products_tabs_index++;
        endfor ?>
    </ul>
    <div class="border-box group">
        <?php foreach ( $sc as $sc_key => $sc_value ) { ?>
            <div id="tab-<?php echo $sc_key ?>" class="panel group"><?php echo do_shortcode( $sc_value ); ?></div>
        <?php } ?>
    </div>
</div>

