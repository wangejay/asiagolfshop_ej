<?php
//email template for auction winners
function ultimate_auction_email_template($auction_name, $auction_id, $auction_desc, $winner_bid, $winner_email, $return_url) {
	global $wpdb;
	$winner_user = get_user_by('email', $winner_email);
	//$name_qry = "SELECT name FROM ".$wpdb->prefix."wdm_bidders WHERE bid =".$winner_bid." AND auction_id =".$auction_id." ORDER BY id DESC";
	//$winner_name = $wpdb->get_var($name_qry);
	$winner_name = $winner_user->user_login;

	$email_template_details_w = get_option("wdm_ua_email_template_auction_won_bidder", 1);
	
	$rec_email = get_option('wdm_paypal_address');
	$cur_code = substr(get_option('wdm_currency'), -3);
	$site_name = get_bloginfo('name');
	if (isset($email_template_details_w['template']) && $email_template_details_w['template'] == "ua_custom") {
		$subject = str_replace('{site_name}', $site_name, $email_template_details_w['subject']);
		if ($email_template_details_w['enable'] == "no") {
			$ua_email_enable_w = false;
		}
	} else {
		$subject = $site_name . ': ' . __('Congratulations! You have won an auction', 'wdm-ultimate-auction');
	}

	$auction_email = get_option('wdm_auction_email');
	$site_url = get_bloginfo('url');

	$auc_post = get_post($auction_id);
	$auction_author_id = $auc_post->post_author;
	$auction_author = new WP_User($auction_author_id);
	$seller_email = get_the_author_meta('user_email', $auction_author_id);

	$ua_email_enable_w = true;
	//$winner_user = get_user_by('email', $winner_email);

	$auc_rec_email = get_user_meta($auction_author_id, 'auction_user_paypal_email', true);

	$comm_fees = get_option('wdm_manage_cm_fees_data');
	$comm_inv = get_option('wdm_manage_comm_invoice');

	$payment_to_seller = false;

	$auth_key = get_post_meta($auction_id, 'wdm-auth-key', true);

	if ($comm_inv == 'No' && !in_array('administrator', $auction_author->roles)) {
		$payment_to_seller = true;
		//$auc_rec_email = get_user_meta($auction_author_id, 'auction_user_paypal_email', true);
	}
	$message = "";
	$message_h = "";
	if (isset($email_template_details_w['template']) && $email_template_details_w['template'] == "ua_custom") {
		$message_h = str_replace('{winner_name}', $winner_name, wpautop(convert_chars(wptexturize($email_template_details_w['body_header']))));
		$message_h = str_replace('{site_url}', $site_url, $message_h);

		if ($email_template_details_w['enable'] == "no") {
			$ua_email_enable_w = false;
		}
	} else {

		$message = __('Hi', 'wdm-ultimate-auction') . " " . $winner_name . ", <br /><br />";
		$message .= sprintf(__('This is to inform you that you have won the auction at WEBSITE URL %s. Here are the auction details', 'wdm-ultimate-auction'), $site_url) . ": <br /><br />";
	}
	$mode = get_option('wdm_account_mode');

	$paypal_link = "";

	if ($mode == 'Sandbox') {
		$paypal_link = "https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_xclick";
	} else {
		$paypal_link = "https://www.paypal.com/cgi-bin/webscr?cmd=_xclick";
	}

	if ($payment_to_seller) {
		$paypal_link .= "&amp;business=" . urlencode($auc_rec_email);
	} else {
		$paypal_link .= "&amp;business=" . urlencode($rec_email);
	}

	//$paypal_link .= "&lc=US";
	$paypal_link .= "&amp;item_name=" . urlencode($auction_name);
	$paypal_link .= "&amp;amount=" . urlencode($winner_bid);

	//shipping field hooks
	$shipping_link = '';
	$paypal_link .= apply_filters('ua_product_shipping_cost_link', $shipping_link, $auction_id, $winner_email); //hook shipping cost link
	//end shipping

	$paypal_link .= "&amp;currency_code=" . urlencode($cur_code);
	$paypal_link .= "&amp;return=" . urlencode(add_query_arg(array('wdm' => $auth_key), $return_url));
	$paypal_link .= "&amp;button_subtype=services";
	$paypal_link .= "&amp;no_note=0";
	$paypal_link .= "&amp;bn=PP%2dBuyNowBF%3abtn_buynowCC_LG%2egif%3aNonHostedGuest";

	$paypal_link = "<a href='" . $paypal_link . "' target='_blank'>" . $paypal_link . "</a>";

	if (isset($email_template_details_w['template']) && $email_template_details_w['template'] == "ua_custom") {
		$message_h = str_replace('{product_url}', $return_url, $message_h);
		$message_h = str_replace('{product_name}', $auction_name, $message_h);
		$message_h = str_replace('{product_description}', $auction_desc, $message_h);
		$message .= $message_h;

		if ($email_template_details_w['enable'] == "no") {
			$ua_email_enable_w = false;
		}
	} else {
		$message .= __("Product URL", "wdm-ultimate-auction") . ": <a href='" . $return_url . "'>" . $return_url . "</a> <br />";
		$message .= "<br />" . __("Product Name", "wdm-ultimate-auction") . ": " . $auction_name . " <br />";
		$message .= "<br />" . __("Description", "wdm-ultimate-auction") . ": <br />" . $auction_desc . "<br /><br />";
	}
	$check_method = get_post_meta($auction_id, 'wdm_payment_method', true);

	$pay_amt = "<strong>" . $cur_code . " " . $winner_bid . "</strong>";

	$auction_data = array();
	if ($check_method === 'method_paypal') {
		//$auction_data = array();

		$auction_data = array('auc_id' => $auction_id,
			'auc_name' => $auction_name,
			'auc_desc' => $auction_desc,
			'auc_bid' => $winner_bid,
			'auc_merchant' => $rec_email,
			'auc_seller' => $auc_rec_email,
			'auc_payer' => $winner_email,
			'auc_currency' => $cur_code,
			'auc_url' => $return_url,
		);
		if (in_array('administrator', $winner_user->roles)) {
			if (isset($email_template_details_w['template']) && $email_template_details_w['template'] == "ua_custom") {
				$message .= wpautop(convert_chars(wptexturize($email_template_details_w['body_admin'])));
				if ($email_template_details_w['enable'] == "no") {
					$ua_email_enable_w = false;
				}
			} else {
				$message .= __("It seems that you are the administrator of the site. Kindly make payment to the owner of the auction from your dashboard.", "wdm-ultimate-auction");
			}
		} else {
			$shipAmt = 0;
			$msg_ship_pp = "";
			$tot_amt = $pay_amt;
			$shipAmt = apply_filters('ua_shipping_data_invoice', $shipAmt, $auction_id, $winner_email);

			if ($shipAmt > 0) {
				$tot_amt = "<strong>" . $cur_code . " " . ($winner_bid + $shipAmt) . "</strong>";
				$msg_ship_pp .= __("Shipping Charge", "wdm-ultimate-auction") . ": <strong>" . $cur_code . " " . $shipAmt . "</strong>";
			}

			$comm_fees = get_option('wdm_manage_cm_fees_data');
			$comm_inv = get_option('wdm_manage_comm_invoice');

			if ($payment_to_seller) {
				if (in_array('administrator', $auction_author->roles)) {
					$paypal_link = apply_filters('ua_paypal_email_content', $paypal_link, $auction_data);
				}
			} else {
				if ($comm_inv == 'Yes' && (!in_array('administrator', $auction_author->roles))) {
					//require_once('ua-paypal-invoice/ua-paypal-adaptive.php');
					$paypal_link = apply_filters('ua_paypal_email_adaptive_content', $paypal_link, $auction_data);
				} else {
					$paypal_link = apply_filters('ua_paypal_email_content', $paypal_link, $auction_data);
				}
			}

			if (isset($email_template_details_w['template']) && $email_template_details_w['template'] == "ua_custom") {
				$message_pp = str_replace('{auction_email}', $auction_email, wpautop(convert_chars(wptexturize($email_template_details_w['body_paypal']))));
				$message_pp = str_replace('{total_amount}', $tot_amt, $message_pp);
				$message_pp = str_replace('{payment_amount}', $pay_amt, $message_pp);
				$message_pp = str_replace('{currency_code}', $cur_code, $message_pp);
				$message_pp = str_replace('{ship_amount}', $shipAmt, $message_pp);
				$message_pp = str_replace('{paypal_link}', $paypal_link, $message_pp);

				$message .= $message_pp;

				if ($email_template_details_w['enable'] == "no") {
					$ua_email_enable_w = false;
				}
			} else {
				$message .= sprintf(__('You can contact ADMIN at %1$s for delivery of the item and pay %2$s through PayPal', 'wdm-ultimate-auction'), $auction_email, $tot_amt) . " - <br /><br />";

				$message .= __("Here are your PayPal payment details", "wdm-ultimate-auction") . ": <br /><br />";

				$message .= __("Item Price", "wdm-ultimate-auction") . ": " . $pay_amt . "<br /><br />";

				$message .= $msg_ship_pp;

				$message .= $paypal_link;

				$message .= "<br/><br /> " . __('Kindly, click on above URL to make payment.', 'wdm-ultimate-auction') . "<br />";
			}
		}
	} elseif ($check_method === 'method_wire_transfer') {
		$msg = apply_filters('ua_product_shipping_cost_wire_cheque', $shipping_link, $auction_id, $winner_bid, $winner_email);

		if (!empty($msg)) {
			$msg_wt = sprintf(__('%s by Wire Transfer', 'wdm-ultimate-auction'), $msg);
		} else {
			$msg_wt = sprintf(__('You can pay %s by Wire Transfer.', 'wdm-ultimate-auction'), $pay_amt);
		}

		if (in_array('administrator', $auction_author->roles)) {
			$det = get_option('wdm_wire_transfer');
		} else {
			$det = get_user_meta($auction_author_id, 'wdm_wire_transfer', true);
		}

		if (isset($email_template_details_w['template']) && $email_template_details_w['template'] == "ua_custom") {

			$message_wt = str_replace('{wt_message}', $msg_wt, wpautop(convert_chars(wptexturize($email_template_details_w['body_wire_transfer']))));
			$message_wt = str_replace('{wt_details}', $det, $message_wt);
			$message_wt = str_replace('{payment_amount}', $pay_amt, $message_wt);
			$message .= $message_wt;
			if ($email_template_details_w['enable'] == "no") {
				$ua_email_enable_w = false;
			}
		} else {
			$message .= $msg_wt.'<br /><br />';
			$message .= __("Wire Transfer Details", 'wdm-ultimate-auction') . ": <br />";
			$message .= $det;
		}
		//$message .= sprintf(__('You can pay %s by Wire Transfer.', 'wdm-ultimate-auction'), $pay_amt)."<br /><br />";
		//$message .= get_option('wdm_wire_transfer');
	} elseif ($check_method === 'method_mailing') {
		$msg = apply_filters('ua_product_shipping_cost_wire_cheque', $shipping_link, $auction_id, $winner_bid, $winner_email);

		if (!empty($msg)) {
			$msg_m = sprintf(__('%s by Cheque', 'wdm-ultimate-auction'), $msg);
		} else {
			$msg_m = sprintf(__('You can pay %s by Cheque.', 'wdm-ultimate-auction'), $pay_amt);
		}

		if (in_array('administrator', $auction_author->roles)) {
			$det = get_option('wdm_mailing_address');
		} else {
			$det = get_user_meta($auction_author_id, 'wdm_mailing_address', true);
		}

		if (isset($email_template_details_w['template']) && $email_template_details_w['template'] == "ua_custom") {
			$message_m = str_replace('{m_message}', $msg_m, wpautop(convert_chars(wptexturize($email_template_details_w['body_mailing']))));
			$message_m = str_replace('{m_details}', $det, $message_m);
			$message_m = str_replace('{payment_amount}', $pay_amt, $message_m);
			$message .= $message_m;
			if ($email_template_details_w['enable'] == "no") {
				$ua_email_enable_w = false;
			}
		} else {
			$message .= $msg_m. '<br /><br />';
			$message .= __("Mailing Address & Cheque Details", 'wdm-ultimate-auction') . ": <br />";
			$message .= $det;
		}

//	    $message .= sprintf(__('You can pay %s by Cheque.', 'wdm-ultimate-auction'), $pay_amt)."<br /><br />";
		//            $message .= get_option('wdm_mailing_address');
	} elseif ($check_method === 'method_cash') {
		$msg = apply_filters('ua_product_shipping_cost_wire_cheque', $shipping_link, $auction_id, $winner_bid, $winner_email);

		if (!empty($msg)) {
			$msg_c = sprintf(__('%s by Cash', 'wdm-ultimate-auction'), $msg);
		} else {
			$msg_c = sprintf(__('You can pay %s by Cash.', 'wdm-ultimate-auction'), $pay_amt);
		}

		if (in_array('administrator', $auction_author->roles)) {
			$det = get_option('wdm_cash');
		} else {
			$det = get_user_meta($auction_author_id, 'wdm_cash', true);
		}

		$cash_msg = $det;

		if (isset($email_template_details_w['template']) && $email_template_details_w['template'] == "ua_custom") {
			$message_c = str_replace('{c_message}', $msg_c, wpautop(convert_chars(wptexturize($email_template_details_w['body_cash']))));
			$message_c = str_replace('{c_details}', $det, $message_c);
			$message_c = str_replace('{payment_amount}', $pay_amt, $message_c);
			$message .= $message_c;
			if ($email_template_details_w['enable'] == "no") {
				$ua_email_enable_w = false;
			}
		} else {
			$message .= $msg_c. '<br /><br />';
			if (!empty($cash_msg)) {
				$message .= __("Cash Details", 'wdm-ultimate-auction') . ": <br />";
				$message .= $cash_msg;
			}
		}
	}

	if (isset($email_template_details_w['template']) && $email_template_details_w['template'] == "ua_custom") {
		$message .= wpautop(convert_chars(wptexturize($email_template_details_w['body_footer'])));
	}
	$hdr = "";
	//$headers  = "From: ". $site_name ." <". $auction_email ."> \r\n";
	$hdr .= "MIME-Version: 1.0\r\n";
	$hdr .= "Content-type:text/html;charset=UTF-8" . "\r\n";

	$email_sent = false;

	if (!empty($paypal_link)) {
		$headers = "";
		//$headers  = "From: ". get_bloginfo('name') ." <". $seller_email ."> \r\n";
		$headers .= "Reply-To: <" . $seller_email . "> \r\n";
		$headers .= $hdr;
		if ($ua_email_enable_w) {
			$email_sent = wp_mail($winner_email, $subject, $message, $headers, '');
		}
	}

	if ($email_sent) {
		update_post_meta($auction_id, 'auction_email_sent', 'sent');
		if ($payment_to_seller) {
			update_post_meta($auction_id, 'ua_direct_paymentlink_to_seller', 'sent');
		}

	}

	$headers = "";
	//$headers  = "From: ". get_bloginfo('name') ." <". $seller_email ."> \r\n";
	$headers .= "Reply-To: <" . $winner_email . "> \r\n";
	$headers .= $hdr;

	$data_to_seller = array();
	$data_to_seller = array('auc_id' => $auction_id,
		'auc_name' => $auction_name,
		'auc_desc' => $auction_desc,
		'auc_price' => $winner_bid,
		'auc_currency' => $cur_code,
		'seller_paypal_email' => $rec_email,
		'winner_email' => $winner_email,
		'seller_email' => $seller_email,
		'winner_name' => $winner_name,
		'pay_method' => $check_method,
		'site_name' => $site_name,
		'site_url' => $site_url,
		'product_url' => $return_url,
		'header' => $headers,
	);

	$ship_enabled = "";
	$ship_enabled = get_post_meta($auction_id, 'wdm_enable_shipping', true);

	//if($check_method === 'method_paypal'){

	if ($ship_enabled == "1") {
		if (!empty($paypal_link)) {
			do_action('ua_shipping_data_email', $data_to_seller);
		}

	} else {
		$email_template_details_s = get_option("wdm_ua_email_template_auction_sold_seller", 1);
		$ua_email_enable_s = true;
		if (isset($email_template_details_s['template']) && $email_template_details_s['template'] == "ua_custom") {
			$sub = str_replace('{site_name}', $site_name, $email_template_details_s['subject']);
			$sub = str_replace('{product_name}', $auction_name, $sub);

			$msg = str_replace('{product_url}', $return_url, wpautop(convert_chars(wptexturize($email_template_details_s['body']))));
			$msg = str_replace('{site_url}', $site_url, $msg);
			$msg = str_replace('{product_name}', $auction_name, $msg);
			$msg = str_replace('{currency_code}', $cur_code, $msg);
			$msg = str_replace('{bid_value}', $winner_bid, $msg);
			$msg = str_replace('{winner_name}', $winner_name, $msg);
			$msg = str_replace('{winner_email}', $winner_email, $msg);

			if ($email_template_details_s['enable'] == "no") {
				$ua_email_enable_s = false;
			}
		} else {
			//if( (empty($ship_enabled) || $ship_enabled == "") && $payment_to_seller ){
			$sub = '';
			$sub = $site_name . ": " . __('An auction has been sold', 'wdm-ultimate-auction') . " - " . $auction_name;

			$msg = '';
			$msg = __('An auction has been sold on your site', 'wdm-ultimate-auction') . " - " . $site_url . "<br /><br />";

			$msg .= "<strong>" . __('Product Details', 'wdm-ultimate-auction') . "</strong> - <br /><br />";
			$msg .= "&nbsp;&nbsp;" . __('Product URL', 'wdm-ultimate-auction') . ": <a href='" . $return_url . "'>" . $return_url . "</a><br /><br />";
			$msg .= "&nbsp;&nbsp;" . __('Product Name', 'wdm-ultimate-auction') . ": " . $auction_name . "<br /><br />";
			$msg .= "&nbsp;&nbsp;" . __('Product Price', 'wdm-ultimate-auction') . ": " . $cur_code . " " . $winner_bid . "<br /><br />";

			$msg .= "<strong>" . __('Winner Details', 'wdm-ultimate-auction') . "</strong> - <br /><br />";
			$msg .= "&nbsp;&nbsp;" . __('Winner Name', 'wdm-ultimate-auction') . ": " . $winner_name . "<br /><br />";
			$msg .= "&nbsp;&nbsp;" . __('Winner Email', 'wdm-ultimate-auction') . ": " . $winner_email . "<br /><br />";
		}
		if (!empty($paypal_link)) {
			if ($ua_email_enable_s) {
				wp_mail($seller_email, $sub, $msg, $headers, '');
			}
		}
	}
	//}

	return $email_sent;
}

//email template for seller
function wdm_ua_seller_notification_mail($email, $bid, $ret_url, $auc_name, $auc_desc, $mod_email, $mod_name, $hdr, $atch) {
	$email_template_details = get_option("wdm_ua_email_template_bid_place_seller", 1);
	$c_code = substr(get_option('wdm_currency'), -3);
	$ua_email_enable = true;
	if (isset($email_template_details['template']) && $email_template_details['template'] == "ua_custom") {
		$adm_sub = str_replace('{blog_name}', get_bloginfo('name'), $email_template_details['subject']);
		$adm_sub = str_replace('{product_name}', $auc_name, $adm_sub);

		$adm_msg = str_replace('{bidder_name}', $mod_name, wpautop(convert_chars(wptexturize($email_template_details['body']))));
		$adm_msg = str_replace('{bidder_email}', $mod_email, $adm_msg);
		$adm_msg = str_replace('{product_url}', $ret_url, $adm_msg);
		$adm_msg = str_replace('{product_name}', $auc_name, $adm_msg);
		$adm_msg = str_replace('{bid_value}', sprintf("%.2f", $bid), $adm_msg);
		$adm_msg = str_replace('{currency_code}', $c_code, $adm_msg);
		$adm_msg = str_replace('{product_description}', html_entity_decode(strip_tags($auc_desc)), $adm_msg);
		if ($email_template_details['enable'] == "no") {
			$ua_email_enable = false;
		}
	} else {
		$adm_sub = get_bloginfo('name') . ":  " . __("A bidder has placed a bid on the product", "wdm-ultimate-auction") . " - " . $auc_name;
		$adm_msg = "";
		$adm_msg = "<strong> " . __('Bidder Details', 'wdm-ultimate-auction') . " - </strong>";
		$adm_msg .= "<br /><br /> " . __('Bidder Name', 'wdm-ultimate-auction') . ": " . $mod_name;
		$adm_msg .= "<br /><br /> " . __('Bidder Email', 'wdm-ultimate-auction') . ": " . $mod_email;
		$adm_msg .= "<br /><br /> " . __('Bid Value', 'wdm-ultimate-auction') . ": " . $c_code . " " . sprintf("%.2f", $bid);
		$adm_msg .= "<br /><br /><strong>" . __('Product Details', 'wdm-ultimate-auction') . " - </strong>";
		$adm_msg .= "<br /><br /> " . __('Product URL', 'wdm-ultimate-auction') . ": <a href='" . $ret_url . "'>" . $ret_url . "</a>";
		$adm_msg .= "<br /><br /> " . __('Product Name', 'wdm-ultimate-auction') . ": " . $auc_name;
		$adm_msg .= "<br /><br /> " . __('Description', 'wdm-ultimate-auction') . ": <br />" . html_entity_decode(strip_tags($auc_desc)) . "<br />";
	}
	if ($ua_email_enable) {
		wp_mail($email, $adm_sub, $adm_msg, $hdr, $atch);
	}
}

//email template for bidders
function wdm_ua_bidder_notification_mail($email, $bid, $ret_url, $auc_name, $auc_desc, $hdr, $atch) {
	$email_template_details = get_option("wdm_ua_email_template_bid_place_bidder", 1);
	$c_code = substr(get_option('wdm_currency'), -3);
	$ua_email_enable = true;
	if (isset($email_template_details['template']) && $email_template_details['template'] == "ua_custom") {
		$bid_sub = str_replace('{blog_name}', get_bloginfo('name'), $email_template_details['subject']);
		$bid_sub = str_replace('{product_name}', $auc_name, $bid_sub);
		$bid_msg = str_replace('{product_url}', $ret_url, wpautop(convert_chars(wptexturize($email_template_details['body']))));
		$bid_msg = str_replace('{product_name}', $auc_name, $bid_msg);
		$bid_msg = str_replace('{bid_value}', sprintf("%.2f", $bid), $bid_msg);
		$bid_msg = str_replace('{currency_code}', $c_code, $bid_msg);
		$bid_msg = str_replace('{product_description}', html_entity_decode(strip_tags($auc_desc)), $bid_msg);
		if ($email_template_details['enable'] == "no") {
			$ua_email_enable = false;
		}
	} else {

		$bid_sub = get_bloginfo('name') . ": " . __('You recently placed a bid on the product', 'wdm-ultimate-auction') . " - " . $auc_name;
		$bid_msg = "";
		$bid_msg = __('Here are the details', 'wdm-ultimate-auction') . " - ";
		$bid_msg .= "<br /><br /> " . __('Product URL', 'wdm-ultimate-auction') . ": <a href='" . $ret_url . "'>" . $ret_url . "</a>";
		$bid_msg .= "<br /><br /> " . __('Product Name', 'wdm-ultimate-auction') . ": " . $auc_name;
		$bid_msg .= "<br /><br /> " . __('Bid Value', 'wdm-ultimate-auction') . ": " . $c_code . " " . sprintf("%.2f", $bid);
		$bid_msg .= "<br /><br /> " . __('Description', 'wdm-ultimate-auction') . ": <br />" . html_entity_decode(strip_tags($auc_desc)) . "<br />";
	}

	if ($ua_email_enable) {
		wp_mail($email, $bid_sub, $bid_msg, $hdr, $atch);
	}
}

//email template for outbid
function wdm_ua_outbid_notification_mail($email, $bid, $ret_url, $auc_name, $auc_desc, $hdr, $atch) {
	$email_template_details = get_option("wdm_ua_email_template_outbid", 1);
	global $wpdb;
	$wpdb->hide_errors();
	$c_code = substr(get_option('wdm_currency'), -3);
	$ua_email_enable = true;
	if (isset($email_template_details['template']) && $email_template_details['template'] == "ua_custom") {
		$outbid_sub = str_replace('{blog_name}', get_bloginfo('name'), $email_template_details['subject']);
		$outbid_sub = str_replace('{product_name}', $auc_name, $outbid_sub);
		$bid_msg = str_replace('{product_url}', $ret_url, wpautop(convert_chars(wptexturize($email_template_details['body']))));
		$bid_msg = str_replace('{product_name}', $auc_name, $bid_msg);
		$bid_msg = str_replace('{bid_value}', sprintf("%.2f", $bid), $bid_msg);
		$bid_msg = str_replace('{currency_code}', $c_code, $bid_msg);
		$bid_msg = str_replace('{product_description}', html_entity_decode(strip_tags($auc_desc)), $bid_msg);
		if ($email_template_details['enable'] == "no") {
			$ua_email_enable = false;
		}
	} else {
		$outbid_sub = get_bloginfo('name') . ": " . __('You have been outbid on the product', 'wdm-ultimate-auction') . " - " . $auc_name;
		$bid_msg = "";
		$bid_msg = __('Here are the details', 'wdm-ultimate-auction') . " - ";
		$bid_msg .= "<br /><br /> " . __('Product URL', 'wdm-ultimate-auction') . ": <a href='" . $ret_url . "'>" . $ret_url . "</a>";
		$bid_msg .= "<br /><br /> " . __('Product Name', 'wdm-ultimate-auction') . ": " . $auc_name;
		$bid_msg .= "<br /><br /> " . __('Bid Value', 'wdm-ultimate-auction') . ": " . $c_code . " " . sprintf("%.2f", $bid);
		$bid_msg .= "<br /><br /> " . __('Description', 'wdm-ultimate-auction') . ": <br />" . html_entity_decode(strip_tags($auc_desc)) . "<br />";
	}
	if ($ua_email_enable) {
		wp_mail($email, $outbid_sub, $bid_msg, $hdr, '');
	}
}
?>