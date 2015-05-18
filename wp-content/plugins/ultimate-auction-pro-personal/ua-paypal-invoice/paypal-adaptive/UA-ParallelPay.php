<?php
if(isset($_POST['adp_bin_btn'])){
	$send = true;
}

if($send){

//adaptive payment
$method = "";
$method = get_option('wdm_adp_payment_method');

if(empty($method) || $method == NULL)
	$method = "parallel";

$commission = 0;
$commission = get_option('wdm_manage_cm_fees_data');

if($method === "chained"){
    $seller_amt = $auc_data['auc_bid'];
}
else{
    $seller_amt = wdm_auction_commission_calculator($commission, $auc_data['auc_bid']); 
}

$com_amt = $auc_data['auc_bid'] - wdm_auction_commission_calculator($commission, $auc_data['auc_bid']);

$currencyCode = "";
$memo = "";
$receiverEmail = array();
$receiverAmount = array();
$primaryReceiver = array();

//$auth_key = get_post_meta($auc_data['auc_id'], 'wdm-auth-key', true);

$returnUrl =  $auc_data['auc_url'];
$args = array('wdm', 'wdmpy');
$cancelUrl =  remove_query_arg($args, $auc_data['auc_url']); 

$actionType = "PAY";
$currencyCode = $auc_data['auc_currency'];

$receiverEmail[0] = $auc_data['auc_seller'];
//$receiverAmount[0] = $seller_amt;
$shippingAmount = "";
$shipAmt = 0;
$shipAmt = apply_filters('ua_shipping_data_invoice', $shippingAmount, $auc_data['auc_id'], $auc_data['auc_payer']);
$receiverAmount[0] = $seller_amt + $shipAmt; 

if($method === "chained")
    $primaryReceiver[0] = true;
else
    $primaryReceiver[0] = false;

$receiverEmail[1] = $auc_data['auc_merchant'];
$receiverAmount[1] = $com_amt;
$primaryReceiver[1] = false;

$pay_mode = get_option('wdm_account_mode');	
	
if($pay_mode == 'Sandbox'){
	define('PAYPAL_REDIRECT_URL', 'https://www.sandbox.paypal.com/webscr&cmd=');
}
else{
	define('PAYPAL_REDIRECT_URL', 'https://www.paypal.com/webscr&cmd=');
}
//define('DEVELOPER_PORTAL', 'https://developer.paypal.com');
//define("DEFAULT_SELECT", "- Select -");

if(isset($receiverEmail)) {
	$receiver = array();
	/*
	 * A receiver's email address 
	 */
	for($i=0; $i<count($receiverEmail); $i++) {
		$receiver[$i] = new Receiver();
		$receiver[$i]->email = $receiverEmail[$i];
		/*
		 *  	Amount to be credited to the receiver's account 
		 */
		$receiver[$i]->amount = $receiverAmount[$i];
		/*
		 * Set to true to indicate a chained payment; only one receiver can be a primary receiver. Omit this field, or set it to false for simple and parallel payments. 
		 */
		$receiver[$i]->primary = $primaryReceiver[$i];

	}
	$receiverList = new ReceiverList($receiver);
}

$payRequest = new PayRequest(new RequestEnvelope("en_US"), $actionType, $cancelUrl, $currencyCode, $receiverList, $returnUrl);
// Add optional params

if($memo != "") {
	$payRequest->memo = $memo;
}

if(!isset($_POST['adp_bin_btn']))
	$payRequest->payKeyDuration = "P29D";

/*
 * 	 ## Creating service wrapper object
Creating service wrapper object to make API call and loading
Configuration::getAcctAndConfig() returns array that contains credential and config parameters
 */
$service = new AdaptivePaymentsService(Configuration::getAcctAndConfig());
try {
	/* wrap API method calls on the service object with a try catch */
	$response = $service->Pay($payRequest);
} catch(Exception $ex) {
	$pay_stat = "failed_to_send";
}
/* Make the call to PayPal to get the Pay token
 If the API call succeded, then redirect the buyer to PayPal
to begin to authorize payment.  If an error occured, show the
resulting errors */
$adaptive_data = $response;
$timestamp = $response->responseEnvelope->timestamp;

$ack = strtoupper($response->responseEnvelope->ack);
if($ack != "SUCCESS") {
	$pay_stat = "failed_to_send";
	
	$err_msg = ' ';
	
	foreach($response->error as $err){
		$err_msg .= $err->message;
	}
	
	if(isset($_POST['adp_bin_btn'])){
		echo "<script type='text/javascript'> jQuery(document).ready(function($){ alert('".__('Failed to connect with PayPal.', 'wdm-ultimate-auction').$err_msg."'); }); </script>";
	}
} else {
	$pay_stat = $response->paymentExecStatus;
	$payKey = $response->payKey;
	$payPalURL = PAYPAL_REDIRECT_URL . '_ap-payment&paykey=' . $payKey;
	
	if($shipAmt > 0){
		$setPaymentOptionsRequest = new SetPaymentOptionsRequest(new RequestEnvelope("en_US"));
		$setPaymentOptionsRequest->payKey = $payKey;
		$receiverOptions = new ReceiverOptions();
		$setPaymentOptionsRequest->receiverOptions[] = $receiverOptions;
		$receiverId = new ReceiverIdentifier();
		$receiverId->email = $receiverEmail[0];
		$receiverOptions->receiver = $receiverId;
		$invoiceItems = array();
		$item = new InvoiceItem();
		$item->price = $receiverAmount[0]-$shipAmt;
		$item->itemPrice = $receiverAmount[0]-$shipAmt;
		$item->itemCount = 1;
		$invoiceItems[] = $item;
		$receiverOptions->invoiceData = new InvoiceData();
		$receiverOptions->invoiceData->totalShipping = $shipAmt;
		$receiverOptions->invoiceData->item = $invoiceItems;
		
		$service = new AdaptivePaymentsService(Configuration::getAcctAndConfig());
		$resp = $service->SetPaymentOptions($setPaymentOptionsRequest);
		
		$ack1 = strtoupper($resp->responseEnvelope->ack);
		
		if(isset($_POST['adp_bin_btn'])){
		if($ack1 == "SUCCESS") {
			echo "<script type='text/javascript'> jQuery(document).ready(function($){ $.blockUI({ message: null });  $('#adp_bin_frm').after('<span class=\"wdm-pp-redirect\">".__('Please wait, you will be redirected to PayPal now.', 'wdm-ultimate-auction')."</span>'); window.location.href= '".$payPalURL."'; }); </script>";
			}
		}
	}
	else{
		if(isset($_POST['adp_bin_btn'])){
			echo "<script type='text/javascript'> jQuery(document).ready(function($){ $.blockUI({ message: null }); $('#adp_bin_frm').after('<div class=\"wdm-pp-redirect\">".__('Please wait, you will be redirected to PayPal now.', 'wdm-ultimate-auction')."</div>'); window.location.href= '".$payPalURL."'; }); </script>";
		}
	}
}
}
?>