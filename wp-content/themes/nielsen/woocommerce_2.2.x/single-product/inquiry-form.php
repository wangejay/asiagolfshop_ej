<?php
/**
 * Inquiry form
 */

global $post;

if ( yit_get_post_meta( $post->ID, '_info_form') !== 'yes' || ! function_exists( 'YIT_Contact_Form' ) ) return;

$form_id = yit_get_option('shop-single-product-contact-form');
$icon = yit_get_option( 'shop-inquiry-title-icon' );

?>


<div id="inquiry-form">
<div class="product-inquiry">
    <?php if ( $icon['select'] != 'none' ) :
            if( $icon['select'] == 'icon' ) { ?>
                <span class="icon-form" <?php echo YIT_Icon()->get_icon_data( $icon['icon'] ) ?>></span>
            <?php } else{ ?>
                <span class="custom-icon"><img src="<?php echo esc_url( $icon['custom'] ); ?>"></span>
            <?php }
     endif; ?>
    <span class="inquiry-title"><?php echo yit_get_option( 'shop-inquiry-title' ) ?></span>
</div>
<?php echo do_shortcode( '[contact_form name="'. $form_id .'" ]' ) ?>
</div>