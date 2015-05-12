<?php
/**
 * Add to wishlist template
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Wishlist
 * @version 2.0.0
 */

global $product;
?>

<div class="yith-wcwl-add-to-wishlist border add-to-wishlist-<?php echo $product_id ?>">
	<div class="yith-wcwl-add-button <?php echo ( $exists && ! $available_multi_wishlist ) ? 'hide': 'show' ?>" style="display:<?php echo ( $exists && ! $available_multi_wishlist ) ? 'none': 'block' ?>">

		<?php yith_wcwl_get_template( 'add-to-wishlist-' . $template_part . '.php', $atts ); ?>

	</div>

	<div class="yith-wcwl-wishlistaddedbrowse hide" style="display:none;">
		<a href="<?php echo esc_url( $wishlist_url )?>" class="with-tooltip" data-toggle="tooltip" data-placement="bottom" title="<?php echo apply_filters( 'yith-wcwl-browse-wishlist-label', __( 'Added to wishlist', 'yit' ) ) ?>">
			<span data-icon="&#xe3e9;" data-font="retinaicon-font"></span>
		</a>
	</div>

	<div class="yith-wcwl-wishlistexistsbrowse <?php echo ( $exists && ! $available_multi_wishlist ) ? 'show' : 'hide' ?>" style="display:<?php echo ( $exists && ! $available_multi_wishlist ) ? 'block' : 'none' ?>">
		<a href="<?php echo esc_url( $wishlist_url ) ?>" class="with-tooltip" data-toggle="tooltip" data-placement="bottom" title="<?php echo apply_filters( 'yith-wcwl-browse-wishlist-label', __( 'Added to wishlist', 'yit' ) ) ?>">
			<span data-icon="&#xe3e9;" data-font="retinaicon-font"></span>
		</a>
	</div>

	<div style="clear:both"></div>
	<div class="yith-wcwl-wishlistaddresponse"></div>

</div>

<div class="clear"></div>

<script type="text/javascript">
	if( jQuery( '#yith-wcwl-popup-message' ).length == 0 ) {
		var message_div = jQuery( '<div>' )
				.attr( 'id', 'yith-wcwl-message' ),
			popup_div = jQuery( '<div>' )
				.attr( 'id', 'yith-wcwl-popup-message' )
				.html( message_div )
				.hide();

		jQuery( 'body' ).prepend( popup_div );
	}
</script>