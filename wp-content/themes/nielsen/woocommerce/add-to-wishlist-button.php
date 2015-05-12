<?php
/**
 * Add to wishlist button template
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Wishlist
 * @version 2.0.0
 */

global $product;

$label_option = get_option( 'yith_wcwl_add_to_wishlist_text' );
$localize_label = function_exists( 'icl_translate' ) ? icl_translate( 'Plugins', 'plugin_yit_wishlist_button', $label_option ) : $label_option;
?>

<a href="<?php echo esc_url( add_query_arg( 'add_to_wishlist', $product_id ) )?>" data-product-id="<?php echo $product_id ?>" data-product-type="<?php echo $product_type?>" class="<?php echo $link_classes ?> with-tooltip add_to_wishlist" data-toggle="tooltip" data-placement="bottom" title="<?php echo $localize_label?>" >
	<span data-icon="&#xe3e9;" data-font="retinaicon-font"></span>
</a>