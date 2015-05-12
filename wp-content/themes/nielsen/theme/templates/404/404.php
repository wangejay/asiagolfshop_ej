<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

$image_url = "";
$text = "";

$is_custom_content = yit_get_option( 'content-enable-404' );
$custom_text = yit_get_option( 'content-404-text' );
$custom_image = yit_get_option( 'content-404-image' );

$background_image = yit_get_option( 'content-404-background' );
$background_repeat = yit_get_option( 'content-404-background-repeat' );
$background_position = yit_get_option( 'content-404-background-position' );
$background_attachment = yit_get_option( 'content-404-background-attachment' );

//---------------------------------------------------------------------

$image_url = ( $is_custom_content == 'yes' && $custom_image != '' ) ? $custom_image : YIT_THEME_ASSETS_URL . "/images/backgrounds/404_text.jpg";
$text = ( $is_custom_content == 'yes' && $custom_text != '' ) ? yit_convert_tags( do_shortcode( stripslashes( $custom_text ) ) ) : __( 'IT SEEMS YOU ARE LOOKING FOR SOMETHING IS NOT HERE.', 'yit' );

?>
<div class="error-404-container">

    <div class="error-404-image-text">
        <?php if ( isset( $image_url ) && $image_url != '' ): ?>
            <img class="error-404-image" src="<?php echo esc_url( $image_url ) ?>" title="<?php _e( 'Error 404', 'yit' ); ?>" alt="404" />
        <?php endif; ?>

    </div>
    <div class="error-404-search">
        <p class="error-404-text"><?php printf( $text) ?></p>
        <?php if( yit_get_option( 'content-404-button-home' ) == 'yes' ): ?>
            <a class="btn btn-large btn-ghost" href="<?php echo home_url() ?>"><?php echo  yit_get_option('content-404-button-label') ?></a>
        <?php endif; ?>
    </div>

</div>

<style type="text/css">
    .error-404-container {
        background-image: url(<?php echo $background_image ?>);
        background-repeat: <?php echo $background_repeat ?>;
        background-position: <?php echo $background_position ?>;
        background-attachment: <?php echo $background_attachment ?>;
    }
</style>
