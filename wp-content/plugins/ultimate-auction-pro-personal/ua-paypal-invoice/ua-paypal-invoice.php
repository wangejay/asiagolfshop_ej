<?php
require_once 'paypal-settings.php';

add_filter('ua_paypal_email_content', 'paypal_invoice_content', 99, 2);

function paypal_invoice_content($paypal_link, $auction_data) {
	$paypal_link = "";
	$invoiceID = "";

	$inv_sent_id = get_post_meta($auction_data['auc_id'], 'paypal_invoice_id', true);

	if (!empty($inv_sent_id)) {

		$inv_sent_num = get_post_meta($auction_data['auc_id'], 'paypal_invoice_num', true);
		$inv_sent_url = get_post_meta($auction_data['auc_id'], 'paypal_invoice_url', true);

		if (!empty($inv_sent_num) && !empty($inv_sent_url)) {
			//$paypal_link =  __("Here are your PayPal invoice details", "wdm-ultimate-auction")." : <br /><br />";
			$paypal_link = __("Invoice number", "wdm-ultimate-auction") . " : " . $inv_sent_num . " <br /><br />";
			$paypal_link .= __("Invoice URL", "wdm-ultimate-auction") . " : <a href=\"{$inv_sent_url}\">{$inv_sent_url}</a>";
		}

	} else {
		require 'paypal-invoice/createandsendinvoice.php';
		update_post_meta($auction_data['auc_id'], 'auction_active_pay_method', 'invoice');
		update_post_meta($auction_data['auc_id'], 'auction_invoice_prepared', 'yes');
		update_post_meta($auction_data['auc_id'], 'auction_invoice_status', $inv_stat);
		update_post_meta($auction_data['auc_id'], 'invoice_reciever_email', $auction_data['auc_payer']);
		update_post_meta($auction_data['auc_id'], 'invoice_reciever_bid_price', $auction_data['auc_bid']);
		update_post_meta($auction_data['auc_id'], 'invoice_reciever_currency', $auction_data['auc_currency']);

		if (!empty($invoiceID)) {
			update_post_meta($auction_data['auc_id'], 'paypal_invoice_id', $invoiceID);
			update_post_meta($auction_data['auc_id'], 'paypal_invoice_num', $invoiceNumber);
			update_post_meta($auction_data['auc_id'], 'paypal_invoice_url', $inv_url);
			if (!is_serialized($invoice_data)) {
				serialize($invoice_data);
			}

			update_post_meta($auction_data['auc_id'], 'paypal_invoice_data', $invoice_data);

			$pay_amt = "<strong>" . $auction_data['auc_currency'] . " " . $auction_data['auc_bid'] . "</strong>";

			update_post_meta($auction_data['auc_id'], 'auction_pay_curr_amt', $pay_amt);

			//$paypal_link =  __("Here are your PayPal payment details", "wdm-ultimate-auction").": <br /><br />";
			if (!empty($invoiceNumber)) {
				$paypal_link = __("Invoice number", "wdm-ultimate-auction") . " : " . $invoiceNumber . " <br /><br />";
			}

			$paypal_link .= __("Payment URL", "wdm-ultimate-auction") . ": <a href=\"{$inv_url}\">{$inv_url}</a>";
		}
	}
	return $paypal_link;
}

add_action('ua_call_setting_file', 'pp_inv_setting_file', 10, 1);

function pp_inv_setting_file($active_tab) {
	if ($active_tab == 'invoices') {
		require_once 'invoices.php';
	}

}

//front end invoices Ajax callback
function show_front_end_user_pay_callback() {

	//if($_POST['fe_pay_type'] === 'settings'){
	//   $logged_user_id = wp_get_current_user();  //get user id
	//   $logged_user_role = $logged_user_id->roles; //get user role
	//   $cu_id = get_current_user_id();
	//   require_once('ua-paypal-invoice/payment_settings.php');
	//   echo $manage_payment;
	//}
	//else{
	require_once 'invoices_front.php';

	if (class_exists('Payment_List_Table_Front')) {
		$FrontPmtTable = new Payment_List_Table_Front();

		$col_array = $FrontPmtTable->get_columns();

		if ($_POST['fe_pay_type'] == 'past' || $_POST['fe_pay_type'] == 'outstanding') {
			$data_array = $FrontPmtTable->payment_get_data($_POST['fe_pay_type']);
			$manage_payment = wdm_prepare_front_items($data_array, $col_array, 'show', $_POST['fe_pay_type']);
		} else {
			$data_array = $FrontPmtTable->payment_get_inv_data($_POST['fe_pay_type']);
			$manage_payment = wdm_prepare_front_inv($data_array, $col_array, 'show', $_POST['fe_pay_type']);
		}
		echo $manage_payment;

	}
	//}
	die();
}

add_action('wp_ajax_show_front_end_user_pay', 'show_front_end_user_pay_callback');
add_action('wp_ajax_nopriv_show_front_end_user_pay', 'show_front_end_user_pay_callback');

//invoice details Ajax callback - Payment page
function invoice_details_callback() {
	if (isset($_POST['inv_id']) && !empty($_POST['inv_id'])) {

		$invid = explode(",", $_POST['inv_id']);

		require_once 'paypal-invoice/PayPalInvoiceAPI.php';
		require_once 'paypal-invoice/credentials.php'; //NOTE: edit this file with your info!

		$pAPI = new PayPalInvoiceAPI($api_username, $api_password, $api_signature, $app_id);

		foreach ($invid as $inv_id) {

			//update_post_meta( $inv_id, 'auction_active_pay_method', 'invoice' );

			$invoice_id = get_post_meta($inv_id, 'paypal_invoice_id', true);

			$invoice_data = $pAPI->getInvoiceDetails($invoice_id);

			$invoice_stat = '';
			if ($invoice_data['responseEnvelope.ack'] == "Success") {
				$invoice_stat = isset($invoice_data['invoiceDetails.status']) ? $invoice_data['invoiceDetails.status'] : '';
			} else {
				$invoice_stat = __('Failed to connect with PayPal account. Please verify your PayPal credentials or just reload this page.', 'wdm-ultimate-auction');
			}

			if (!empty($invoice_id)) {
				update_post_meta($inv_id, 'auction_invoice_status', $invoice_stat);
			}

		}
	}

	if (isset($_POST['adp_id']) && !empty($_POST['adp_id'])) {

		require_once 'paypal-adaptive/PPBootStrap.php';

		$adpid = explode(",", $_POST['adp_id']);

		foreach ($adpid as $adp_id) {

			//update_post_meta( $adp_id, 'auction_active_pay_method', 'adaptive' );

			$requestEnvelope = new RequestEnvelope("en_US");

			$paymentDetailsReq = new PaymentDetailsRequest($requestEnvelope);

			$payKey = get_post_meta($adp_id, 'paypal_invoice_id', true);

			if ($payKey != "") {
				$paymentDetailsReq->payKey = $payKey;
			}
			//if($_POST['transactionId'] != "") {
			//	$paymentDetailsReq->transactionId = $_POST['transactionId'];
			//}
			//if($_POST['trackingId'] != "") {
			//	$paymentDetailsReq->trackingId = $_POST['trackingId'];
			//}

			$service = new AdaptivePaymentsService(Configuration::getAcctAndConfig());
			try {
				/* wrap API method calls on the service object with a try catch */
				$response = $service->PaymentDetails($paymentDetailsReq);
			} catch (Exception $ex) {

			}

			update_post_meta($adp_id, 'paypal_trans_timestamp', $response->responseEnvelope->timestamp);

			$ack = strtoupper($response->responseEnvelope->ack);

			$pay_stat = '';
			if ($ack != "SUCCESS") {
				$pay_stat = __('Failed to connect with PayPal account. Please verify your PayPal credentials or just reload this page.', 'wdm-ultimate-auction');
			} else {
				$pay_stat = $response->status;
			}

			if (!empty($payKey)) {
				if (strtoupper($pay_stat) == 'CREATED') {
					update_post_meta($adp_id, 'auction_invoice_status', 'Sent');
				} elseif (strtoupper($pay_stat) == 'COMPLETED') {
					update_post_meta($adp_id, 'auction_invoice_status', 'Paid');
				} else {
					update_post_meta($adp_id, 'auction_invoice_status', $pay_stat);
				}

			}
		}
	}

	die();
}

add_action('wp_ajax_invoice_details', 'invoice_details_callback');
add_action('wp_ajax_nopriv_invoice_details', 'invoice_details_callback');

//resend invoice Ajax callback - Invoices page
function resend_invoice_callback() {
	$invoice_id = get_post_meta($_POST['auc_id'], 'paypal_invoice_id', true);
	$invoice_num = get_post_meta($_POST['auc_id'], 'paypal_invoice_num', true);
	$invoice_url = get_post_meta($_POST['auc_id'], 'paypal_invoice_url', true);
	$invoice_email = get_post_meta($_POST['auc_id'], 'invoice_reciever_email', true);
	$cur_code = substr(get_option('wdm_currency'), -3);
	$site_name = get_bloginfo('name');
	//$auction_email  = get_option('wdm_auction_email');
	$site_url = get_bloginfo('url');
	$product_url = get_post_meta($_POST['auc_id'], 'current_auction_permalink', true);
	$product_name = $_POST['auc_nm'];
	$product_desc = $_POST['auc_dsc'];
	$pay_amt = get_post_meta($_POST['auc_id'], 'auction_pay_curr_amt', true);

	$email_template_details = get_option("wdm_ua_email_template_resend_invoice", array());
	$ua_email_enable = true;
	if (isset($email_template_details['template']) && $email_template_details['template'] == "ua_custom") {
		$subject = str_replace('{site_name}', $site_name, $email_template_details['subject']);

		$message = str_replace('{product_url}', $product_url, wpautop(convert_chars(wptexturize($email_template_details['body']))));
		$message = str_replace('{product_name}', $product_name, $message);
		$message = str_replace('{product_description}', $product_desc, $message);
		$message = str_replace('{payment_amount}', $pay_amt, $message);
		$message = str_replace('{currency_code}', $cur_code, $message);
		
		$shipAmt = 0;
		$shipAmt = apply_filters('ua_shipping_data_invoice', $shipAmt, $_POST['auc_id'], $invoice_email);
		if(empty($shipAmt))
                    $shipAmt = 0;
		    
		$message = str_replace('{ship_amount}', $shipAmt, $message);
		$message = str_replace('{invoice_number}', $invoice_num, $message);
		$message = str_replace('{invoice_url}', $invoice_url, $message);

		if ($email_template_details['enable'] == "no") {
			$ua_email_enable = false;
		}
	} else {
		$subject = '[' . $site_name . '] ' . __('PayPal Invoice Reminder', 'wdm-ultimate-auction');

		$message = "";
		$message .= sprintf(__('This is to inform you that you have won the auction at WEBSITE URL %s. Here are the auction details', 'wdm-ultimate-auction'), $site_url) . ': <br /><br />';
		$message .= __('Product URL', 'wdm-ultimate-auction') . ': ' . $product_url . ' <br /><br />';
		$message .= __('Product Name', 'wdm-ultimate-auction') . ': ' . $product_name . ' <br /><br />';
		$message .= __('Description', 'wdm-ultimate-auction') . ': <br />' . $product_desc . '<br /><br />';
		$message .= sprintf(__('You can make your payment through PayPal', 'wdm-ultimate-auction'), $pay_amt) . ' - <br /><br />';
		$message .= __('Here are your PayPal payment details', 'wdm-ultimate-auction') . ' : <br /><br />';

		$message .= __("Item Price", "wdm-ultimate-auction") . ": " . $pay_amt . "<br /><br />";

		$shipAmt = 0;
		$shipAmt = apply_filters('ua_shipping_data_invoice', $shipAmt, $_POST['auc_id'], $invoice_email);
		if(empty($shipAmt))
                    $shipAmt = 0;
		    
		if ($shipAmt > 0) {
			$message .= __("Shipping Charge", "wdm-ultimate-auction") . ": <strong>" . $cur_code . " " . $shipAmt . "</strong>";
		}

		if (!empty($invoice_num)) {
			$message .= __('Invoice Number', 'wdm-ultimate-auction') . ': ' . $invoice_num . ' <br /><br />';
		}

		$message .= __("Payment URL", "wdm-ultimate-auction") . " : <a href=\"{$invoice_url}\">{$invoice_url}</a> <br /><br />";
		$message .= __('Kindly, click on above URL to make payment.', 'wdm-ultimate-auction') . '<br />';
	}

	$auc_post = get_post($_POST['auc_id']);
	$auction_author_id = $auc_post->post_author;
	//$auction_author = new WP_User($auction_author_id);
	$seller_email = get_the_author_meta('user_email', $auction_author_id);

	$headers = "";
	//$headers  = "From: ". $site_name ." <". $auction_email ."> \r\n";
	$headers .= "Reply-To: <" . $seller_email . "> \r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

	if ($ua_email_enable) {
		$sent = wp_mail($invoice_email, $subject, $message, $headers, '');
	}

	if ($sent) {
		_e("An email has been sent to buyer with invoice details.", "wdm-ultimate-auction");
	} else {
		_e("Sorry, the email could not be sent.", "wdm-ultimate-auction");
	}

	die();
}

add_action('wp_ajax_resend_invoice', 'resend_invoice_callback');
add_action('wp_ajax_nopriv_resend_invoice', 'resend_invoice_callback');

function delete_invoice_callback() {

	if (isset($_POST["del_id"])) {

		$delete_post_id = $_POST["del_id"];

		$del_auc = delete_post_meta($delete_post_id, 'auction_invoice_prepared');
		delete_post_meta($delete_post_id, 'auction_invoice_status');
		delete_post_meta($delete_post_id, 'invoice_reciever_email');
		delete_post_meta($delete_post_id, 'invoice_reciever_bid_price');
		delete_post_meta($delete_post_id, 'invoice_reciever_currency');
		delete_post_meta($delete_post_id, 'paypal_invoice_id');
		delete_post_meta($delete_post_id, 'paypal_invoice_num');
		delete_post_meta($delete_post_id, 'paypal_invoice_url');
		delete_post_meta($delete_post_id, 'paypal_invoice_data');
		delete_post_meta($delete_post_id, 'auction_pay_curr_amt');

		$apm = get_post_meta($delete_post_id, 'auction_active_pay_method', true);

		if (!empty($apm) && $apm != NULL) {
			delete_post_meta($delete_post_id, 'auction_active_pay_method');
		}

		$ptt = get_post_meta($delete_post_id, 'paypal_trans_timestamp', true);

		if (!empty($ptt) && $ptt != NULL) {
			delete_post_meta($delete_post_id, 'paypal_trans_timestamp');
		}

		if ($del_auc) {
			printf(__("Invoice entry %d of the Auction id %d is deleted successfully.", "wdm-ultimate-auction"), $_POST['inv_num'], $_POST['del_id']);
		} else {
			_e("Sorry, this invoice cannot be deleted.", "wdm-ultimate-auction");
		}

	}
	die();
}

add_action('wp_ajax_delete_invoice', 'delete_invoice_callback');
add_action('wp_ajax_nopriv_delete_invoice', 'delete_invoice_callback');

//multiple delete invoice Ajax callback
function multi_delete_invoice_callback() {

	if (isset($_POST["del_ids"])) {

		$all_aucs = explode(',', $_POST['del_ids']);

		foreach ($all_aucs as $aa) {

			$del_auc = delete_post_meta($aa, 'auction_invoice_prepared');
			delete_post_meta($aa, 'auction_invoice_status');
			delete_post_meta($aa, 'invoice_reciever_email');
			delete_post_meta($aa, 'invoice_reciever_bid_price');
			delete_post_meta($aa, 'invoice_reciever_currency');
			delete_post_meta($aa, 'paypal_invoice_id');
			delete_post_meta($aa, 'paypal_invoice_num');
			delete_post_meta($aa, 'paypal_invoice_url');
			delete_post_meta($aa, 'paypal_invoice_data');
			delete_post_meta($aa, 'auction_pay_curr_amt');

			$apm = get_post_meta($aa, 'auction_active_pay_method', true);

			if (!empty($apm) && $apm != NULL) {
				delete_post_meta($aa, 'auction_active_pay_method');
			}

			$ptt = get_post_meta($aa, 'paypal_trans_timestamp', true);

			if (!empty($ptt) && $ptt != NULL) {
				delete_post_meta($aa, 'paypal_trans_timestamp');
			}

		}

		if ($del_auc) {
			printf(__("Selected invoice entries are deleted successfully.", "wdm-ultimate-auction"));
		} else {
			_e("Sorry, the invoices cannot be deleted.", "wdm-ultimate-auction");
		}

	}
	die();
}

add_action('wp_ajax_multi_delete_invoice', 'multi_delete_invoice_callback');
add_action('wp_ajax_nopriv_multi_delete_invoice', 'multi_delete_invoice_callback');

require_once 'ua-paypal-adaptive.php';
?>