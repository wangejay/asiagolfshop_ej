<?php
/**
 * Single Product tabs
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Filter tabs and allow third parties to add their own
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters( 'woocommerce_product_tabs', array() );

if ( ! empty( $tabs ) ) : ?>

	<div class="woocommerce-tabs">
		<?php foreach ( $tabs as $key => $tab ) : ?>

			<div class="panel entry-content" id="tab-<?php echo $key ?>">
				<?php call_user_func( $tab['callback'], $key, $tab ) ?>
			</div>

		<?php endforeach; ?>
		<ul class="tabs">
			
			<?php foreach ( $tabs as $key => $tab ) : ?>
				
				<li class="<?php echo $key ?>_tab">
					<a href="#tab-<?php echo $key ?>"></a>
				</li>

			<?php endforeach; ?>
		</ul>
		
		
	</div>
	<h2>退貨需知</h2>
	<p>
	線上購物的消費者，都可以依照消費者保護法的規定，享有商品貨到日起七天猶豫期的權益。但猶豫期並非試用期，所以，您所退回的商品必須是全新的狀態、而且完整包裝；請注意保持商品本體、配件、贈品、保證書、原廠包裝及所有附隨文件或資料的完整性，切勿缺漏任何配件或損毀原廠外盒。
                </p>
                <p>
                    若您要辦理退貨，請先透過線上購物客服系統填寫退訂單，我們將於接獲申請之次日起3個工作天內檢視您的退貨要求，
                    檢視完畢後將以E-mail回覆通知您，並將委託本公司指定之宅配公司，在5個工作天內透過電話與您連絡前往取回退貨商品。
                </p>
                <p>
                    本公司收到您所退回的商品及相關單據後，若經確認無誤，將於7個工作天內為您辦理退款，退款日當天會再發送E-mail通知函給您。
                </p>


<?php endif; ?>
