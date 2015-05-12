<?php
/**
 * Your Inspiration Themes
 *
 * @package    WordPress
 * @subpackage Your Inspiration Themes
 * @author     Your Inspiration Themes Team <info@yithemes.com>
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */


global $post;
if ( !is_page() || !isset( $post->ID ) ) {
    return;
}

$address   = YIT_Layout()->google_map_overlay_address;
$latitude  = YIT_Layout()->google_map_overlay_latitude;
$longitude = YIT_Layout()->google_map_overlay_longitude;

if ( $address == '' && ( $latitude == '' && $longitude == '' ) ) {
    return;
}

$full_width = YIT_Layout()->google_map_full_width;
$width      = YIT_Layout()->google_map_width;
$height     = YIT_Layout()->google_map_height;
$zoom       = YIT_Layout()->google_map_overlay_zoom;
$marker     = YIT_Layout()->google_map_overlay_marker;
$style      = YIT_Layout()->google_map_overlay_style;


$google_map = '[googlemap full_width="' . $full_width . '" width="' . $width . '" height="' . $height . '" address="' . $address . '" latitude="' . $latitude . '" longitude="' . $longitude . '" zoom="' . $zoom . '" marker="' . $marker . '" style="' . $style . '"]';

?>
<!-- START MAP -->
<div id="map">
    <div class="container">
        <div class="border">
            <?php echo do_shortcode( $google_map ) ?>
        </div>
    </div>
</div>
<!-- END MAP -->