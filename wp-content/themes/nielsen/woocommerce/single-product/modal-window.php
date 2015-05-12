<?php
/**
 * Size chart modal
 */
global $post;

if ( ! shortcode_exists( 'modal' ) ) return;

$show = yit_get_post_meta($post->ID, '_modal_window');
$img  = yit_get_post_meta($post->ID, '_modal_window_img');
$title = yit_get_post_meta($post->ID, '_modal_window_title');
$text = yit_get_post_meta($post->ID, '_modal_window_text');
$icon = yit_get_post_meta($post->ID, '_modal_window_icon');

$img_html = '<img src="' . $img . '">';

if( $show == 'yes' ) : ?>
    <div id="modal-window">
        <?php if ( $icon['select'] != 'none' ) :
            if( $icon['select'] == 'icon' ) { ?>
                <span class="icon-form" <?php echo YIT_Icon()->get_icon_data( $icon['icon'] ) ?>></span>
            <?php } else{ ?>
                <span class="custom-icon"><img src="<?php echo esc_url( $icon['custom'] ); ?>"></span>
            <?php }
        endif; ?>
        <?php echo do_shortcode( '[modal title="' . $title . '" link_text_opener="' . $text . '" link_text_size="13" opener="text"]' . $img_html . '[/modal]' ) ?>
    </div>
<?php endif ?>

<div class="clear"></div>