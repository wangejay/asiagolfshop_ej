<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

wp_enqueue_script('map-google');
$id = 'map_canvas_' .mt_rand();

$full_width = ( $full_width == "yes" ) ? true : false;
$css_width = "";

if( ! $full_width ){
    if( $width != '' && $width != 0 ){
        $css_width .= "width: " . $width . "px;";
    } else{
        $css_width .= "width: auto;";
    }
}else{
    $width = '';
}


$latitude = ( isset( $latitude ) && $latitude != '' ) ? ' data-lat="' . $latitude . '"' : '';
$longitude = ( isset( $longitude ) && $longitude != '' ) ? ' data-lng="' . $longitude . '"' : '';
$address = ( isset( $address ) && $address != '' ) ? ' data-address="' . $address . '"' : '';
$zoom = ( isset( $zoom ) && $zoom != '' ) ? ' data-zoom="' . $zoom . '"' : 'data-zoom="15"';
$marker = ( isset( $marker ) && $marker != '' ) ? ' data-marker="' . $marker . '"' : '';
$style = ( isset( $style ) && $style != '' ) ? ' data-style="' . $style . '"' : '';
$width_syle = ( $full_width ) ? "full-width section_fullwidth" : "" ;

?>

<div class="google-map">
    <div id="<?php echo $id ?>" class="map_canvas <?php echo $width_syle ?>" <?php echo $longitude.$latitude.$address.$zoom.$marker.$style ?> style="height:<?php echo $height ?>px;<?php echo $css_width ?>"></div>
</div>