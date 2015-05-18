<?php
$add_user_auction = '';
$post_id;

$logged_user_id = wp_get_current_user(); //get user id

$logged_user_role = $logged_user_id->roles; //get user role

$currency_code = substr(get_option('wdm_currency'), -3);

$check_fees_stat = get_option('wdm_manage_status_data');

$amt = get_option('wdm_manage_fees_data');

$can_add_directly = false;

if (in_array('administrator', $logged_user_role) || $check_fees_stat === 'Inactive' || $amt <= 0) {
	$can_add_directly = true;
}

if (!empty($_POST)) {

	if (isset($_POST['ua_wdm_add_auc_fe']) && wp_verify_nonce($_POST['ua_wdm_add_auc_fe'], 'ua_wp_n_f')) {

		$auction_title = (!empty($_POST["auction_title"])) ? ($_POST["auction_title"]) : '';
		$auction_content = (!empty($_POST["auction_description"])) ? ($_POST["auction_description"]) : '';
		$auction_excerpt = (!empty($_POST["auction_excerpt"])) ? ($_POST["auction_excerpt"]) : '';

		$auc_end_tm = isset($_POST["end_date"]) ? strtotime($_POST["end_date"]) : 0;
		$blog_curr_tm = strtotime(date("Y-m-d H:i:s", time()));

		if ($can_add_directly) {
			$auction_stat = 'publish';
		} else {
			$auction_stat = 'draft';
		}

		if (($auc_end_tm > $blog_curr_tm) && $auction_title != "" && $auction_content != "") {
			global $post_id;
			$is_update = false;
			$reactivate = false;

			//update auction mode
			if (isset($_POST["update_auction"]) && !empty($_POST["update_auction"]) /*&& !isset($_GET["reactivate"])*/) {
				$post_id = $_POST["update_auction"];

				$args = array(
					'ID' => $post_id,
					'post_title' => $auction_title,
					'post_content' => $auction_content,
					'post_excerpt' => $auction_excerpt,
				);
				wp_update_post($args);
				$is_update = true;

			}
			//reactivate auction mode
			elseif (/*isset($_POST["update_auction"]) && !empty($_POST["update_auction"]) &&*/isset($_GET["reactivate"])) {
				$args = array(
					'post_title' => wp_strip_all_tags($auction_title), //except for title all other fields are sanitized by wordpress
					'post_content' => $auction_content,
					'post_type' => 'ultimate-auction',
					'post_status' => $auction_stat,
					'post_excerpt' => $auction_excerpt,
				);

				$post_id = wp_insert_post($args);
				$auc_set->wdm_set_auction($post_id);
				$auc_set->auction_id = $post_id;
				$reactivate = true;
			}
			//create/add auction mode
			else {

				$args = array(
					'post_title' => wp_strip_all_tags($auction_title), //except for title all other fields are sanitized by wordpress
					'post_content' => $auction_content,
					'post_type' => 'ultimate-auction',
					'post_status' => $auction_stat,
					'post_excerpt' => $auction_excerpt,
				);
				$post_id = wp_insert_post($args);
				$auc_set->wdm_set_auction($post_id);
				$auc_set->auction_id = $post_id;
			}

			if ($post_id) {

				$get_default_timezone = get_option('wdm_time_zone');

				if (!empty($get_default_timezone)) {
					date_default_timezone_set($get_default_timezone);
				}

				if ($is_update) {

					if ($can_add_directly) {
						$add_user_auction .= '<div class="wdm_auc_user_notice_suc">';
						$add_user_auction .= __("Auction updated successfully.", "wdm-ultimate-auction");
						$add_user_auction .= '</div>';
					} else {
						$listing_fee = get_post_meta($post_id, 'wdm_auction_listing_fees', true);
						if ($listing_fee === 'Paid' || (isset($_GET['edit_auction']) && !empty($_GET['edit_auction']) && !isset($_GET["reactivate"]))) {
							$add_user_auction .= '<div class="wdm_auc_user_notice_suc">';
							$add_user_auction .= __("Auction updated successfully.", "wdm-ultimate-auction");
							$add_user_auction .= '</div>';
						} else {
							$add_user_auction .= '<div class="wdm_auc_user_notice_chg">';
							$add_user_auction .= wdm_auction_listing_charge($post_id, $auction_title, $amt, $currency_code, 'fe');
							$add_user_auction .= '</div>';
						}
					}
				} elseif ($reactivate) {
					update_post_meta($post_id, 'wdm-auth-key', md5(time() . rand()));

					if (isset($_POST["start_date"]) && !empty($_POST["start_date"])) {
						add_post_meta($post_id, 'wdm_creation_time', date($_POST["start_date"]));
					} else {
						add_post_meta($post_id, 'wdm_creation_time', date("Y-m-d H:i:s", time()));
					}

					if ($can_add_directly) {
						$add_user_auction .= '<div class="wdm_auc_user_notice_suc">';
						$add_user_auction .= sprintf(__("Auction reactivated successfully. Auction id is %d", "wdm-ultimate-auction"), $post_id);
						$add_user_auction .= '</div>';
					} else {
						$add_user_auction .= '<div class="wdm_auc_user_notice_chg">';
						$add_user_auction .= wdm_auction_listing_charge($post_id, $auction_title, $amt, $currency_code, 'fe');
						$add_user_auction .= '</div>';
					}

				} else {
					update_post_meta($post_id, 'wdm-auth-key', md5(time() . rand()));

					if (isset($_POST["start_date"]) && !empty($_POST["start_date"])) {
						add_post_meta($post_id, 'wdm_creation_time', date($_POST["start_date"]));
					} else {
						add_post_meta($post_id, 'wdm_creation_time', date("Y-m-d H:i:s", time()));
					}

					if ($can_add_directly) {
						$add_user_auction .= '<div class="wdm_auc_user_notice_suc">';
						$add_user_auction .= sprintf(__("Auction created successfully. Auction id is %d", "wdm-ultimate-auction"), $post_id);
						$add_user_auction .= '</div>';
					} else {
						$add_user_auction .= '<div class="wdm_auc_user_notice_chg">';
						$add_user_auction .= wdm_auction_listing_charge($post_id, $auction_title, $amt, $currency_code, 'fe');
						$add_user_auction .= '</div>';
					}

				}

				if (isset($_POST["start_date"]) && !empty($_POST["start_date"])) {
					$temp = term_exists('scheduled', 'auction-status');
					wp_set_post_terms($post_id, $temp["term_id"], 'auction-status');
					update_post_meta($post_id, 'wdm_creation_time', date($_POST["start_date"]));
				} else {
					$temp = term_exists('live', 'auction-status');
					wp_set_post_terms($post_id, $temp["term_id"], 'auction-status');
				}

				update_post_meta($post_id, 'wdm_product_image_gallery', $_POST["product_image_gallery"]);

				//update options
				for ($u = 1; $u <= 4; $u++) {
					update_post_meta($post_id, "wdm-image-" . $u, $_POST["auction_image_" . $u]);
				}

				$other_imgs = get_post_meta($post_id, 'wdm_product_image_gallery', true);

				//$ec = count(explode(',', $other_imgs));
				$oimg = explode(',', $other_imgs);

				foreach ($oimg as $oi) {
					$uinc = $u++;
					$s_img = wp_get_attachment_url($oi);
					update_post_meta($post_id, "wdm-image-" . $uinc, $s_img);

					if (isset($_POST['auction_main_image']) && ($_POST['auction_main_image'] == 'main_image_' . $uinc)) {
						update_post_meta($post_id, 'wdm_auction_thumb', $s_img);
					}

				}

				$openingBid = $_POST["opening_bid"];
				$lowestBid = $_POST["lowest_bid"];
				$incValue = $_POST["incremental_value"];

				if (!in_array('administrator', $logged_user_role)) {
					if (isset($_GET['edit_auction']) && !empty($_GET['edit_auction']) && !isset($_GET["reactivate"])) {
						$openingBid = get_post_meta($post_id, 'wdm_opening_bid', true);
						$lowestBid = get_post_meta($post_id, 'wdm_lowest_bid', true);
						$incValue = get_post_meta($post_id, 'wdm_incremental_val', true);
					}
				}

				update_post_meta($post_id, 'wdm-main-image', $_POST["auction_main_image"]);
				update_post_meta($post_id, 'wdm_listing_starts', $_POST["start_date"]);
				update_post_meta($post_id, 'wdm_listing_ends', $_POST["end_date"]);
				update_post_meta($post_id, 'wdm_opening_bid', round($openingBid, 2));
				update_post_meta($post_id, 'wdm_lowest_bid', round($lowestBid, 2));
				update_post_meta($post_id, 'wdm_incremental_val', round($incValue, 2));
				update_post_meta($post_id, 'wdm_product_attachment', $_POST["wdm_product_attachment"]);
				update_post_meta($post_id, 'product_attachment_label', $_POST["product_attachment_label"]);
				update_post_meta($post_id, 'wdm_buy_it_now', round($_POST["buy_it_now_price"], 2));
				if (isset($_POST["ua_best_offers"])) {
					update_post_meta($post_id, 'wdm_enable_best_offers', $_POST["ua_best_offers"]);
				} else {
					update_post_meta($post_id, 'wdm_enable_best_offers', "");
				}

				update_post_meta($post_id, 'wdm_payment_method', $_POST["payment_method"]);
				//if another bidding engine is active
				update_post_meta($post_id, 'wdm_bidding_engine', $_POST["bidding_engine"]);
				for ($im = 1; $im <= 4; $im++) {
					if (get_post_meta($post_id, 'wdm-main-image', true) == 'main_image_' . $im) {
						$main_image = get_post_meta($post_id, 'wdm-image-' . $im, true);
						update_post_meta($post_id, 'wdm_auction_thumb', $main_image);
					}
				}

			}
		} elseif ($auc_end_tm <= $blog_curr_tm) {
			$add_user_auction .= '<div class="wdm_auc_user_notice_err">' . __("Please enter a future date/time.", "wdm-ultimate-auction") . '</div>';

		} else {
			$add_user_auction .= '<div class="wdm_auc_user_notice_err">' . __("Auction title and Auction description cannot be left blank.", "wdm-ultimate-auction") . '</div>';
			//$add_user_auction .=  '<script type="text/javascript"> setTimeout(function() { alert("'.__("Auction title and Auction description cannot be left blank.", "wdm-ultimate-auction").'");});</script>';
		}
	} else {
		die(__('Sorry, your nonce did not verify.', 'wdm-ultimate-auction'));
	}
}

if (!in_array('administrator', $logged_user_role)) {
	if (isset($_GET["edit_auction"]) && !empty($_GET["edit_auction"])) {
		$auc_id = $_GET["edit_auction"];
		$auc_post = get_post($auc_id);
		$auc_auth = $auc_post->post_author;
		$auth_id = get_current_user_id();
		if ($auc_auth != $auth_id) {
			wp_die(__('You do not have sufficient permissions to access this page.', 'wdm-ultimate-auction'));
		}

	}
}

$wdm_post = $auc_set->wdm_get_post();

$add_user_auction .= '<div id="wdm_auc_user_notice_vis"></div>';

//form to add/update an auction
$add_user_auction .= '<form id="wdm-add-auction-form" class="auction_settings_section_style" action="" method="POST" enctype="multipart/form-data" >';

$add_user_auction .= '<div class="wdm-addauction-section">';
$add_user_auction .= '<ul class="wdm-addauction-list">';

$bidding_engine = array();

$bidding_engine = apply_filters('ua_add_bidding_engine', $bidding_engine);

if (!empty($bidding_engine)) {
	$add_user_auction .= '<li>
            <label for="bidding_engine">' . __("Bidding Engine", "wdm-ultimate-auction") . '</label>

            <select id="bidding_engine" name="bidding_engine">';
	if ((get_option("wdm_default_bidding_engine_set") == "Yes") || (get_option("wdm_default_bidding_engine_set") == "No" && get_option("wdm_bidding_engines") == "")) {
		$add_user_auction .= '<option value="">' . __("Simple Bidding", "wdm-ultimate-auction") . '</option>';
	}

	foreach ($bidding_engine as $be) {

		$opt = get_option("wdm_bidding_engines");

		$opt_new = $auc_set->wdm_post_meta("wdm_bidding_engine");

		if ((isset($_GET['edit_auction']) && !empty($_GET['edit_auction'])) || isset($post_id)) {
			$opt = $opt_new;
		}

		$select = $opt == $be["val"] ? "selected" : "";
		if ((get_option("wdm_default_bidding_engine_set") == "Yes") || (get_option("wdm_default_bidding_engine_set") == "No" && get_option("wdm_bidding_engines") == $be["val"])) {
			$add_user_auction .= '<option value="' . $be["val"] . '" ' . $select . '>' . $be["text"] . '</option>';
		}

	}

	$add_user_auction .= '</select>
	</li>';
}

//   $add_user_auction .= "<script>
//
//var displayFileInput = function(bool){
//	jQuery('#wdm_digital_product_file_li').attr('hidden',bool);
//}
//</script>";
/**
 * Settings for Product Type (Digital or Physical) displayed on frontend Add Auction from
 */

require_once 'ajax-actions/file-upload.php';

$single_img = $auc_set->wdm_post_meta("wdm-digital-product-file");

$product_type = '';

$default_product_type = get_option('wdm_auction_type');

if(empty($default_product_type))
    $default_product_type = 'physical';

//if($default_product_type != 'both')
    $product_type = $auc_set->wdm_post_meta('wdm_product_type');

if (empty($product_type)) {
	$product_type = $default_product_type;
}

if($default_product_type == 'both')
	{
	    
        	$physical_select = $product_type == "physical" ? "selected='selected'":"";
        	$digital_select = $product_type == "digital" ? "selected='selected'":"";
    	
		$add_user_auction .= '<li> <label for="wdm_product_type">'.__("Product Type", "wdm-ultimate-auction").'</label>';
		$add_user_auction .= "<select id='wdm_product_type' name='wdm_product_type'>";
		$add_user_auction .= "<option value='physical' $physical_select>".__('Physical', 'wdm-ultimate-auction')."</option>";
		$add_user_auction .= "<option value='digital' $digital_select>".__('Digital', 'wdm-ultimate-auction')."</option>";
		$add_user_auction .= "</select></li>";
//	$add_user_auction .= "<script>
//	jQuery('#product_type').on('change', function(){
//		var type = this.value;
//		if(type === 'digital'){
//			displayFileInput(false);
//		}
//		else{
//			displayFileInput(true);
//		}
//	});
//</script>";
	/**
	 * add file input if auction type is digital
	 */
	//$hidden = $product_type=="digital"?"":"hidden";
	//$single_img = $auc_set->wdm_post_meta("wdm-digital-product-file");
	}
	elseif($product_type=='physical' || $product_type=='digital')
	{
	    $add_user_auction.='<input type="hidden" id="wdm_product_type" value="'.$product_type.'" name="wdm_product_type"/>';
	}
	
	if($default_product_type == 'both' || $product_type === 'digital'){
	$add_user_auction .= '<li id="wdm_digital_product_file_li"> <label class="wdm_digital_product_file_lbl" for="wdm_digital_product_file_name">' . __("Product File", "wdm-ultimate-auction") . '</label>';

//require_once('ajax-actions/file-upload.php');

$add_user_auction .= '<input name="wdm_digital_product_file_name" type="text" class="url" id="wdm_digital_product_file_name" value="' . $single_img . '" />';

$add_user_auction .= '<input name="wdm_digital_product_file_btn" type="button" value="' . __("Upload File", "wdm-ultimate-auction") . '" class="regular-text wdm_img_btn wdm_digital_product_file_btn" />';

$add_user_auction .= '<span class="wdm_product_file_name">' . basename($single_img) . '</span>';

$add_user_auction .= '<input name="wdm_digital_product_file_url" type="file" id="wdm_digital_product_file" class="regular-text wdm_img_file wdm_digital_product_file_url" />';

$add_user_auction .= '</li>';

}

$add_user_auction .= '<li> <label for="auction_title">' . __("Product Title", "wdm-ultimate-auction") . '</label>';
$add_user_auction .= '<input name="auction_title" type="text" id="auction_title" class="regular-text" value="' . $wdm_post["title"] . '"/></li>';

$add_user_auction .= '<li> <label for="auction_description">' . __("Product Description", "wdm-ultimate-auction") . '</label>';
//$add_user_auction .= '<textarea name="auction_description" type="text" id="auction_description" cols="50" rows="10" class="large-text code">'
//.$wdm_post["content"].'</textarea> </li>';
ob_start();
$args = array(
	'media_buttons' => false,
	'textarea_name' => 'auction_description',
	'textarea_rows' => 10,
);
wp_editor($wdm_post["content"], 'auction_description', $args);
$wyswyg_container = ob_get_contents();
ob_end_clean();
$add_user_auction .= $wyswyg_container . '</li>';

$add_user_auction .= '<li><label for="auction_excerpt">' . __("Product Short Description", "wdm-ultimate-auction") . '</label>';
$add_user_auction .= '<textarea name="auction_excerpt" id="auction_excerpt" class="regular-text ua_thin_textarea_field">' . $wdm_post["excerpt"] . '</textarea>';
$add_user_auction .= '<div class="ult-auc-settings-tip">' . __("Enter short description (excerpt) for the product. This description is shown on the auctions listing page.", "wdm-ultimate-auction") . '</div></li>';
$after_desc = '';
$after_desc = apply_filters('wdm_ua_after_product_desc', $after_desc);
$add_user_auction .= '<li>' . $after_desc . '</li>';

for ($p = 1; $p <= 4; $p++) {
	$single_img = $auc_set->wdm_post_meta("wdm-image-" . $p);

	$add_user_auction .= '<li> <label class="wdm_image_lbl" for="auction_image_' . $p . '">' . __("Product Image/Video", "wdm-ultimate-auction") . ' ' . $p . '</label>';
	require 'ajax-actions/image-upload.php';

	$add_user_auction .= '<input name="auction_image_' . $p . '" type="text" class="url" id="auction_image_' . $p . '" value="' . $single_img . '" />';

	$add_user_auction .= '<input name="wdm_img_btn_nm" type="button" value="' . __("Upload File", "wdm-ultimate-auction") . '" class="regular-text wdm_img_btn wdm_image_' . $p . '_btn" />';

	$add_user_auction .= '<span class="wdm_imgs_name wdm_img_' . $p . '_name">' . basename($single_img) . '</span>';

	$add_user_auction .= '<input name="auction_image_file_' . $p . '" type="file" id="auction_image_file_' . $p . '" class="regular-text wdm_img_file wdm_image_' . $p . '_url" />';
	if ($p == 4) {
		$add_user_auction .= '<a href="" class="auction_fields_tooltip auction_fields_tooltip_atch"><strong>' . __("?", "wdm-ultimate-auction") . '</strong>
	    <span style="width: 172px; margin-left: -140px;"><strong>' . __("Please Note", "wdm-ultimate-auction") . ':</strong><br />'
		. __("The attachments chosen from here are not uploaded immediately to the site. They are uploaded only after saving the auction.", "wdm-ultimate-auction") . '
	    </span></a>';
	}

	$add_user_auction .= '</li>';

}

$add_user_auction .= '<li><label for="auction_image_gallery">' . __("More Images", "wdm-ultimate-auction") . '</label>';

$add_user_auction .= '<input type="file" class="wdm_img_file" name="wdm_auc_mult_upload[]" id="wdm_auc_mult_upload" multiple />';

require_once 'ajax-actions/multi-image-upload.php';

$med_up = '<div id="product_images_container">
		<ul class="product_images">';

$product_image_gallery = $auc_set->wdm_post_meta('wdm_product_image_gallery');

if ($product_image_gallery) {
	$attachments = explode(',', $product_image_gallery);
	foreach ($attachments as $attachment_id) {
		$med_up .= '<li class="wdmimage" data-attachment_id="' . $attachment_id . '">
							' . wp_get_attachment_image($attachment_id, 'thumbnail') . '
							<ul class="actions">
								<li><a href="#" class="delete" title="' . __('Delete image', 'wdm-ultimate-auction') . '">' . __('Delete', 'wdm-ultimate-auction') . '</a></li>
							</ul>
						</li>';
	}
}

$med_up .= '</ul>';

$med_up .= '<input type="hidden" id="product_image_gallery" name="product_image_gallery" value="' . esc_attr($product_image_gallery) . '" /></div>
	<p class="add_product_images hide-if-no-js">
		<a href="#">' . __("Add more images", "wdm-ultimate-auction") . '</a>
	</p>';

$add_user_auction .= ' <div id="auction_image_gallery">' . $med_up . '</div><div id="wdm_temp_flname"></div>';

$add_user_auction .= '</li>';

$add_user_auction .= '<li> <label for="auction_main_image">' . __("Thumbnail Image", "wdm-ultimate-auction") . '</label>
	<select id="auction_main_image" name="auction_main_image">';
for ($m = 1; $m <= 4; $m++) {
	$img_select = ($auc_set->wdm_post_meta("wdm-main-image") == "main_image_" . $m) ? "selected" : "";
	$add_user_auction .= '<option value="main_image_' . $m . '" ' . $img_select . '>' . __("Product Image/Video", "wdm-ultimate-auction") . ' ' . $m . '</option>';
}

$add_user_auction .= '</select> </li>';

$add_user_auction .= '<li class="standard_auction_section"> <label for="opening_bid">' . __("Opening Price", "wdm-ultimate-auction") . '</label> ' . $currency_code . '<input name="opening_bid" type="text" id="opening_bid" class="small-text number ua_auction_price_fields" value="' . $auc_set->wdm_post_meta('wdm_opening_bid') . '" /> </li>';

$add_user_auction .= '<li class="standard_auction_section">
            <label for="lowest_bid">' . __("Lowest Price to Accept", "wdm-ultimate-auction") . '</label> ' . $currency_code .
'<input name="lowest_bid" type="text" id="lowest_bid" class="small-text number ua_auction_price_fields" value="' . $auc_set->wdm_post_meta('wdm_lowest_bid') . '"/>
	    <a href="" class="auction_fields_tooltip"><strong>' . __("?", "wdm-ultimate-auction") . '</strong>
	    <span style="width: 404px; margin-left: -96px;">' . __("A reserve price is the lowest price at which you are willing to sell your item. If you don't want to sell your item below a certain price, you can a set a reserve price. The amount of your reserve price is not disclosed to your bidders, but they will see that your auction has a reserve price and whether or not the reserve has been met. If a bidder does not meet that price, you're not obligated to sell your item.", "wdm-ultimate-auction") . '
	    <br /><strong>' . __("Why have a reserve price?", "wdm-ultimate-auction") . '</strong><br />'
. __("Many sellers have found that too high a starting price discourages interest in their item, while an attractively low starting price makes them vulnerable to selling at an unsatisfactorily low price. A reserve price helps with this.", "wdm-ultimate-auction") . '
	    </span>
	    </a>
	    <div>
		<span class="ult-auc-settings-tip">' . __("Set Reserve price for your auction.", "wdm-ultimate-auction") . '</span>
	    </div>
    </li>';

$add_user_auction .= '<li class="standard_auction_section">
            <label for="incremental_value">' . __("Incremental Value", "wdm-ultimate-auction") . '</label>' . $currency_code . '
            <input name="incremental_value" type="text" id="incremental_value" class="small-text number ua_auction_price_fields" value="' . $auc_set->wdm_post_meta('wdm_incremental_val') . '" />
	    <div class="ult-auc-settings-tip">' . __("Set an amount from which next bid should start.", "wdm-ultimate-auction") . '</div>
    </li>';

$disb = "";
if (!empty($_GET['edit_auction'])) {
	$active_trm = wp_get_post_terms($_GET['edit_auction'], 'auction-status', array("fields" => "names"));
	if (in_array('live', $active_trm)) {
		$disb = "disabled='disabled'";
	}

}

$add_user_auction .= '<li> <label for="start_date">' . __("Start Date", "wdm-ultimate-auction") . '</label>
            <input name="start_date" type="text" id="start_date" class="regular-text" readOnly ' . $disb . ' value="' . $auc_set->wdm_post_meta('wdm_listing_starts') . '"/>';
$add_user_auction .= '</li>';

$add_user_auction .= '<li> <label for="end_date">' . __("Ending Date", "wdm-ultimate-auction") . '</label>
            <input name="end_date" type="text" id="end_date" class="regular-text" readOnly  value="' . $auc_set->wdm_post_meta('wdm_listing_ends') . '"/>';
$add_user_auction .= '<div class="ult-auc-settings-tip">';

$def_timezone = get_option('wdm_time_zone');
if (!empty($def_timezone)) {
	$add_user_auction .= sprintf(__('Current blog time is %s', 'wdm-ultimate-auction'), '<strong>' . date("Y-m-d H:i:s", time()) . '</strong> ');
	$add_user_auction .= __('Timezone:', 'wdm-ultimate-auction') . ' <strong>' . $def_timezone . '</strong>';
} else {
	if (in_array('administrator', $logged_user_role)) {
		$add_user_auction .= sprintf(__('Please select your Timezone at %s Tab of the plugin.', 'wdm-ultimate-auction'), '<a href="' . admin_url('admin.php?page=ultimate-auction') . '">' . __('Settings', 'wdm-ultimate-auction') . '</a>');
	} else {
		$add_user_auction .= __('Please contact your administrator to set Timezone for the plugin.', 'wdm-ultimate-auction');
	}

}

$add_user_auction .= '</div>';
$add_user_auction .= '</li>';

$add_user_auction .= '<li> <label for="buy_it_now_price">' . __("Buy Now Price", "wdm-ultimate-auction") . '</label> ' . $currency_code .
'<input name="buy_it_now_price" type="text" id="buy_it_now_price" class="small-text number" value="' . $auc_set->wdm_post_meta('wdm_buy_it_now') . '"/>
            <div class="ult-auc-settings-tip">' . sprintf(__("Visitors can buy your auction by making payments via PayPal.", "wdm-ultimate-auction")) . '</div>
    </li>';
$add_user_auction .= '<li> <label for="ua_best_offers">' . __("Best Offers", "wdm-ultimate-auction") . '</label>
            <input name="ua_best_offers" type="checkbox" id="ua_best_offers" value="1"' . checked('1', $auc_set->wdm_post_meta('wdm_enable_best_offers'), false) . '/>' . __(" Enable", "wdm-ultimate-auction") . '
</li>';

ob_start();
do_action('ua_add_shipping_cost_input_field'); //hook to add new price field
$add_user_ship = ob_get_contents();
ob_end_clean();
$add_user_auction .= '<li>' . $add_user_ship . '</li>';

$add_user_auction .= '<li> <label for="wdm_product_attachment">' . __("Product Attachment", "wdm-ultimate-auction") . '</label>';

require_once 'ajax-actions/auction-attachment.php';

$attach_label = $auc_set->wdm_post_meta('product_attachment_label');
$attach_file = $auc_set->wdm_post_meta('wdm_product_attachment');

$add_user_auction .= '<input name="product_attachment_label" class="small-text" type="text" id="product_attachment_label"  value="'.$attach_label.'"/>';
    
$add_user_auction .= '<input name="wdm_product_attachment" type="text" class="url" id="wdm_product_attachment" value="'. $attach_file .'" />';

$add_user_auction .= '<input name="wdm_product_attach_btn" type="button" value="' . __("Upload File", "wdm-ultimate-auction") . '" class="regular-text wdm_img_btn wdm_product_attach_btn" />';

$add_user_auction .= '<input name="wdm_product_attach_file" type="file" id="wdm_product_attach_file" class="regular-text wdm_img_file" />';

$add_user_auction .= '</li>';


$custom_fields = array();	
    $custom_fields = get_option('wdm_custom_field');	
    $count = count($custom_fields);
     if(!empty($custom_fields)){
	
	$custom_meta_field = $auc_set->wdm_post_meta('wdm_custom_field');
	$add_user_auction.='<li>';
	for($init=0; $init < $count; $init++){
	    
	    $field_type = "";
	    
	    if($custom_fields[$init]['required']==1)	
	    {	
		$field_type = "class='required regular-text'";
	    }
	    else
	    {
		$field_type="class='regular-text'";
	    }
	 	$add_user_auction.='<label>' . __($custom_fields[$init]['label'], "wdm-ultimate-auction") . '</label>';
	    	$add_user_auction.='<div><input type="text" '.$field_type.' id="wdm_custom_field_'.$init.'" name="wdm_custom_field[]" value="'.$custom_meta_field[$init].'" /></div>';
		
		$add_user_auction.='<br/>';
		
	}
	$add_user_auction.='</li>';
    }


$before_pay = '';
$before_pay = apply_filters('wdm_ua_add_before_payment', $before_pay);
$before_pay = str_replace("<tr", "<li><tr", $before_pay);
$before_pay = str_replace("/tr>", "/tr></li>", $before_pay);
$add_user_auction .= $before_pay;

$add_user_auction .= '<li> <label for="payment_method">' . __("Payment Method", "wdm-ultimate-auction") . '</label>';

//$paypal_enabled = get_option('wdm_paypal_address');
//$wire_enabled = get_option('wdm_wire_transfer');
//$mailing_enabled = get_option('wdm_mailing_address');

$comm_inv = get_option('wdm_manage_comm_invoice');
$comm = false;

if ($comm_inv == 'Yes' && (!in_array('administrator', $logged_user_role))) {
	$comm = true;
}

$pay_methods = array();
$selected1 = '';
$selected2 = '';
$selected3 = '';
$selected4 = '';
$disabled1 = '';
$disabled2 = '';
$disabled3 = '';
$disabled4 = '';

if (in_array('administrator', $logged_user_role)) {
	$pay_methods = get_option('payment_options_enabled');
} else {
	$pay_methods = get_user_meta($logged_user_id->ID, 'payment_options_enabled', true);
}

if (empty($pay_methods)) {
	$pay_methods = array();
	echo '<div class="wdm_auc_user_notice_err">' . __("Please select a payment method on Settings page before adding an auction.", "wdm-ultimate-auction") . '</div>';
}

$add_user_auction .= '<select id="payment_method" name="payment_method">';

if ($auc_set->wdm_post_meta('wdm_payment_method') == "method_paypal") {
	$selected1 = "selected";
}

if (!array_key_exists("method_paypal", $pay_methods)) {
	$disabled1 = "disabled='disabled'";
}

$add_user_auction .= '<option id="wdm_method_paypal" value="method_paypal" ' . $selected1 . ' ' . $disabled1 . '>' . __("PayPal", "wdm-ultimate-auction") . '</option>';

if (!$comm) {
	if ($auc_set->wdm_post_meta('wdm_payment_method') == "method_wire_transfer") {
		$selected2 = "selected";
	}

	if (!array_key_exists("method_wire_transfer", $pay_methods)) {
		$disabled2 = "disabled='disabled'";
	}

	$add_user_auction .= '<option id="wdm_method_wire_transfer" value="method_wire_transfer" ' . $selected2 . ' ' . $disabled2 . '>' . __("Wire Transfer", "wdm-ultimate-auction") . '</option>';

	if ($auc_set->wdm_post_meta('wdm_payment_method') == "method_mailing") {
		$selected3 = "selected";
	}

	if (!array_key_exists("method_mailing", $pay_methods)) {
		$disabled3 = "disabled='disabled'";
	}

	$add_user_auction .= '<option id="wdm_method_mailing" value="method_mailing" ' . $selected3 . ' ' . $disabled3 . '>' . __("By Cheque", "wdm-ultimate-auction") . '</option>';

	if ($auc_set->wdm_post_meta('wdm_payment_method') == "method_cash") {
		$selected4 = "selected";
	}

	if (!array_key_exists("method_cash", $pay_methods)) {
		$disabled4 = "disabled='disabled'";
	}

	$add_user_auction .= '<option id="wdm_method_cash" value="method_cash" ' . $selected4 . ' ' . $disabled4 . '>' . __("Cash", "wdm-ultimate-auction") . '</option>';

}

$add_user_auction .= '</select>';

if ($comm) {

	$add_user_auction .= "<span class='wdm-mark-red'>" . __("You'll be able to choose only PayPal for getting the payment as admin has activated commissions feature.", "wdm-ultimate-auction") . "</span>";

}

$add_user_auction .= '<div class="ult-auc-settings-tip">' . __("Only those methods will be active for which you've entered details inside plugin's settings page.", "wdm-ultimate-auction") . '</div>
    </li>';

$add_user_auction .= '</ul>';
$add_user_auction .= '</div>';

global $post_id;
if (isset($_GET["edit_auction"]) && !empty($_GET["edit_auction"]) && !isset($_GET["reactivate"])) {
	$add_user_auction .= "<input type='hidden' value='" . $_GET["edit_auction"] . "' name='update_auction'>";
} else if ($post_id != "") //user came here after clicking on submit button
{
	$add_user_auction .= "<input type='hidden' value='" . $post_id . "' name='update_auction'>";
}

$add_user_auction .= wp_nonce_field('ua_wp_n_f', 'ua_wdm_add_auc_fe');

if (in_array('administrator', $logged_user_role)) {
	$add_user_auction .= wdm_submit_button($wdm_post["title"]);
} else {
	if ($can_add_directly && !isset($_GET['edit_auction']) && !isset($_GET["reactivate"])) {
		$add_user_auction .= wdm_submit_button($wdm_post["title"]);

	} elseif ($can_add_directly && isset($_GET["reactivate"])) {$add_user_auction .= wdm_submit_button($wdm_post["title"]);} elseif (isset($_GET['edit_auction']) && !empty($_GET['edit_auction']) && !isset($_GET["reactivate"])) {

		if (isset($_GET["tx"]) && !empty($_GET["tx"])) {

			$auth = get_post_meta($_GET['edit_auction'], 'wdm-auth-key', true);

			if (isset($_GET["auc"]) && $_GET["auc"] === $auth) {

				//update_post_meta($_GET['edit_auction'], 'wdm_auction_listing_amt', $_GET['cc'].' '.$_GET['amt']);
				update_post_meta($_GET['edit_auction'], 'wdm_auction_listing_fees', 'Paid');
				update_post_meta($_GET['edit_auction'], 'wdm_auction_listing_transaction', $_GET["tx"]);

				$args = array(
					'ID' => $_GET['edit_auction'],
					'post_status' => 'publish',
				);
				wp_update_post($args);

				$add_user_auction .= sprintf("<script type='text/javascript'>
	   var cur_url = window.location.href;
	   if(window.location.href.indexOf('ua_reloaded') == -1)
	    setTimeout(function() {alert('" . __('Auction created successfully. Auction id is %d', 'wdm-ultimate-auction') . "');window.location.href=cur_url+'&ua_reloaded';}, 1000);
	   </script>", $_GET['edit_auction']);
			}
		}
		$add_user_auction .= "<script type='text/javascript'>
	 jQuery(document).ready(function(){
	   jQuery('.ua_auction_price_fields').attr('readOnly','readOnly');
	   jQuery('.ua_auction_price_fields').each(function(){
	   var num = jQuery(this).val();
	   jQuery(this).val(num); });
	   });
	   </script>";

		$add_user_auction .= wdm_submit_button($wdm_post["title"]);
	} else {

		$add_user_auction .= '<div id="wdm_auc_user_notice_hid" style="display:none;">
	<div class="wdm_auc_user_notice_hd">' . __("PLEASE NOTE: You won't be able to change these fields when auction is added - ", "wdm-ultimate-auction")
		. '<strong>' . __("Opening Price", "wdm-ultimate-auction") . '</strong>
		,<strong>' . __("Lowest Price to Accept", "wdm-ultimate-auction") . '</strong>
		,<strong>' . __("Incremental Value", "wdm-ultimate-auction") . '</strong>
    </div></div>';

		$add_user_auction .= '<input type="submit" id="ua-show-payment-btn" class="button-primary" value="' . __("Continue with Payment", "wdm-ultimate-auction") . " [" . $currency_code . " " . $amt . "]" . '" />';

		$add_user_auction .= '<span class="wdm-listing-charge-text wdm-mark-hover">[' . sprintf(__("We charge %s as auction listing fee", "wdm-ultimate-auction"), $currency_code . " " . $amt) . ']</span>';

	}
}

$add_user_auction .= '</form>';

$add_user_auction .= "<script type='text/javascript'>
jQuery(document).ready(function($){
		if ($('#wdm_product_type').val() === 'digital') {
		    $('#wdm_digital_product_file_li').show();
		}
		else{
		    $('#wdm_digital_product_file_li').hide();
		}
		$('#wdm_product_type').on('change', function(){

			if($(this).find('option:selected').val() === 'digital'){

				$('#wdm_digital_product_file_li').show();
			}
			else{
				$('#wdm_digital_product_file_li').hide();
			}
		});
    });
</script>";

require_once 'validate-auctions.php';
?>