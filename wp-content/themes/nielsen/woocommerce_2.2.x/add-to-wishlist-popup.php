<?php
/**
 * Add to wishlist popup template
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Wishlist
 * @version 2.0.0
 */

$label_option = get_option( 'yith_wcwl_add_to_wishlist_text' );
$localize_label = function_exists( 'icl_translate' ) ? icl_translate( 'Plugins', 'plugin_yit_wishlist_button', $label_option ) : $label_option;
$unique_id = mt_rand();
?>

<!-- WISHLIST POPUP OPENER -->
<a href="#add_to_wishlist_popup_<?php echo $product_id ?>_<?php echo $unique_id?>" class="<?php echo $link_classes ?> open-pretty-photo with-tooltip add_to_wishlist" data-rel="prettyPhoto[add_to_wishlist_<?php echo $product_id ?>_<?php echo $unique_id?>]" data-toggle="tooltip" data-placement="bottom" title="<?php echo $localize_label?>" >
	<span data-icon="&#xe3e9;" data-font="retinaicon-font"></span>
</a>

<!-- WISHLIST POPUP -->
<div id="add_to_wishlist_popup_<?php echo $product_id ?>_<?php echo $unique_id?>" class="yith-wcwl-popup">
	<form class="yith-wcwl-popup-form" method="post" action="<?php echo esc_url( add_query_arg( array( 'add_to_wishlist' => $product_id ) ) )?>">
		<div class="yith-wcwl-popup-content">

			<div class="yith-wcwl-first-row">
				<div class="yith-wcwl-wishlist-select-container">
					<h3><?php echo $popup_title ?></h3>
					<select name="wishlist_id" class="wishlist-select">
						<option value="0" <?php selected( true ) ?> ><?php echo apply_filters( 'yith_wcwl_default_wishlist_name', __( 'My Wishlist', 'yit' ) )?></option>
						<?php if( ! empty( $lists ) ): ?>
							<?php foreach( $lists as $list ):?>
								<?php if( ! $list['is_default'] ): ?>
									<option value="<?php echo esc_attr( $list['ID'] ) ?>"><?php echo $list['wishlist_name'] ?></option>
								<?php endif; ?>
							<?php endforeach; ?>
						<?php endif; ?>

						<option value="new"><?php echo apply_filters( 'yith_wcwl_create_new_list_text', __( 'Create a new list', 'yit' ) ) ?></option>
					</select>
				</div>
				<div class="yith-wcwl-wishlist-thumb">
					<?php echo $product_image ?>
				</div>
			</div>

			<div class="yith-wcwl-second-row">
				<div class="yith-wcwl-popup-new">
					<label for="wishlist_name"><?php echo apply_filters( 'yith_wcwl_new_list_title_text', __( 'Wishlist name', 'yit' ) ) ?></label>
					<input name="wishlist_name" class="wishlist-name" type="text" class="wishlist-name" />
				</div>
				<div class="yith-wcwl-visibility">
					<select name="wishlist_visibility" class="wishlist-visibility">
						<option value="0" class="public-visibility"><?php echo apply_filters( 'yith_wcwl_public_wishlist_visibility', __( 'Public', 'yit' ) )?></option>
						<option value="1" class="shared-visibility"><?php echo apply_filters( 'yith_wcwl_shared_wishlist_visibility', __( 'Shared', 'yit' ) )?></option>
						<option value="2" class="private-visibility"><?php echo apply_filters( 'yith_wcwl_private_wishlist_visibility', __( 'Private', 'yit' ) )?></option>
					</select>
				</div>
			</div>
		</div>

		<div class="yith-wcwl-popup-footer">
			<div class="yith-wcwl-popup-button">
				<img src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ) ?>" class="ajax-loading" alt="loading" width="16" height="16" style="visibility:hidden" />
				<a class="wihslist-submit <?php echo $link_classes ?>" data-product-id="<?php echo $product_id ?>" data-product-type="<?php echo $product_type?>">
					<?php echo $label_popup ?>
				</a>
			</div>
		</div>
	</form>
</div>