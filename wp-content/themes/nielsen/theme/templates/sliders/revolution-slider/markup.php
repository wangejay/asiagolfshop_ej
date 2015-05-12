<?php
/**
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $is_primary;

if ( ! class_exists( 'RevSlider' ) )  return;

if( ! defined( 'YIT_SLIDER_USED' ) ){
    define( 'YIT_SLIDER_USED', true );
}

extract( array(
    'slider_id'  => 'slider-' . $this->index
) );


$sliderID = $slider->get('config-slider_name');
$the_slider = new RevSlider();
$the_slider->initByMixed($sliderID);

$slider_class = '';
//$slider_class .= yit_slide_get('align') != '' ? ' align' . yit_slide_get('align') : '';
$slider_class .= ' ' . $the_slider->getParam('slider_type');

$is_fixed = false;
if ( ( ! $is_primary && in_array( $the_slider->getParam('slider_type'), array( 'fixed', 'responsitive' ) ) ) || $slider->get('config-is_boxed') == 'yes' ){
    $is_fixed = true;
}

?>

<!-- START SLIDER -->
<div class="revolution-wrapper<?php if ( $is_fixed ) echo ' container'; ?><?php if ( 'yes' == yit_get_option('header-cat-dropdow-opened') ) echo ' shop-by-category-opened'; ?>">
    <div id="<?php echo esc_attr( $slider_id ) ?>" <?php $slider->item_class( $slider_class ) ?>>
        <div class="shadowWrapper">
            <?php echo do_shortcode('[rev_slider ' . $sliderID . ']'); ?>
        </div>
    </div>
</div>
<!-- END SLIDER -->