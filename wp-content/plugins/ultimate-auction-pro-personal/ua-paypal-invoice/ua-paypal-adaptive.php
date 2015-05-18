<?php
// Let the SDK know where the config file resides.
require_once('paypal-adaptive/PPBootStrap.php');

add_filter( 'ua_paypal_email_adaptive_content', 'wdm_paypal_email_adaptive_content', 99, 2 );

function wdm_paypal_email_adaptive_content($paypal_link, $auc_data){

$paypal_link = "";
$payKey = "";
$payPalURL = "";

$payKey = get_post_meta( $auc_data['auc_id'], 'paypal_invoice_id', true);

if(!empty($payKey) && get_post_meta( $auc_data['auc_id'], 'auction_email_sent', true ) == 'sent'){
	
//$payKey = get_post_meta( $auc_data['auc_id'], 'paypal_adaptive_paykey', true);
$payPalURL = get_post_meta( $auc_data['auc_id'], 'paypal_invoice_url', true);

if(!empty($payKey) && !empty($payPalURL)){
	//$paypal_link =  __("Here are your PayPal payment details", "wdm-ultimate-auction").": <br /><br />";
	//$paypal_link .= __("PayKey", "wdm-ultimate-auction")." : ". $payKey ." <br /><br />";
	$paypal_link = __("Payment URL", "wdm-ultimate-auction").": <a href=\"{$payPalURL}\">{$payPalURL}</a>";
}

}
else{

$send = true;

require_once('paypal-adaptive/UA-ParallelPay.php');

//end
        update_post_meta( $auc_data['auc_id'], 'auction_active_pay_method', 'adaptive' );
	update_post_meta( $auc_data['auc_id'], 'auction_invoice_prepared', 'yes' );
        
	if(strtoupper($pay_stat) == 'CREATED')
		update_post_meta( $auc_data['auc_id'], 'auction_invoice_status', 'Sent' );
	elseif(strtoupper($pay_stat) == 'COMPLETED')
		update_post_meta( $auc_data['auc_id'], 'auction_invoice_status', 'Paid' );
	else
		update_post_meta( $auc_data['auc_id'], 'auction_invoice_status', $pay_stat );

	update_post_meta( $auc_data['auc_id'], 'invoice_reciever_email', $auc_data['auc_payer'] );
	update_post_meta( $auc_data['auc_id'], 'invoice_reciever_bid_price', $auc_data['auc_bid'] );
	update_post_meta( $auc_data['auc_id'], 'invoice_reciever_currency', $auc_data['auc_currency'] );
	
	if(!empty($payKey))
	{
	    update_post_meta( $auc_data['auc_id'], 'paypal_invoice_id', $payKey);
	    update_post_meta( $auc_data['auc_id'], 'paypal_invoice_url', $payPalURL);
            
	    update_post_meta( $auc_data['auc_id'], 'paypal_invoice_data', $adaptive_data);
	    
            update_post_meta( $auc_data['auc_id'], 'paypal_trans_timestamp', $timestamp);
            
	    $pay_amt = "<strong>". $auc_data['auc_currency'] ." ". $auc_data['auc_bid']. "</strong>";
		    
	    update_post_meta( $auc_data['auc_id'], 'auction_pay_curr_amt', $pay_amt );
	    
	    //$paypal_link =  __("Here are your PayPal payment details", "wdm-ultimate-auction").": <br /><br />";
            
            //if($shipAmt > 0){
            //    $paypal_link .= __("Item Price", "wdm-ultimate-auction").": ".$pay_amt."<br /><br />";
            //    $paypal_link .= __("Shipping charge", "wdm-ultimate-auction").": <strong>". $auc_data['auc_currency'] ." ".$shipAmt. "</strong> <br /><br />";
            //}
	    //$paypal_link .= __("PayKey", "wdm-ultimate-auction")." : ". $payKey ." <br /><br />";
	    $paypal_link = __("Payment URL", "wdm-ultimate-auction").": <a href=\"{$payPalURL}\">{$payPalURL}</a>";
	}
} 

   return $paypal_link;
}

add_filter( 'ua_adaptive_buy_now_link', 'wdm_adaptive_buy_now_link', 99, 2 );

function wdm_adaptive_buy_now_link($paypal_link, $auc_data){
    
    $send = false;
    
    $bn_text = sprintf(__('Buy it now for %s %.2f', 'wdm-ultimate-auction'), $auc_data['auc_currency'], $auc_data['auc_bid']);
    
    $shipAmt = 0;
    $shipAmt = apply_filters('ua_shipping_data_invoice', $shipAmt, $auc_data['auc_id'], $auc_data['auc_payer']);
    
    if($shipAmt > 0){
        $bn_text = sprintf(__('Buy it now for %s %.2f + %.2f (shipping)', 'wdm-ultimate-auction'), $auc_data['auc_currency'], $auc_data['auc_bid'], $shipAmt);
    }
    
    $paypal_link = '<form id="adp_bin_frm" action="" method="post">
			<input name="adp_bin_btn" type="submit" value="'.$bn_text.'" id="wdm-buy-now-button" />
		    </form>';
                                
    require_once('paypal-adaptive/UA-ParallelPay.php');
    
    return $paypal_link;
}

add_action('wdm_ua_after_fees_settings', 'wdm_adp_payment_method_settings', 10, 2);

function wdm_adp_payment_method_settings( $id, $setting ){
    	    add_settings_field(
		'adp_method_field', 
		__('Which PayPal method should be used to split money?', 'wdm-ultimate-auction'), 
		'manage_adp_method_field', 
		$setting, 
		$id 			
	    );
            
    if(isset($_POST['wdm_adp_payment_method']))
	update_option('wdm_adp_payment_method',$_POST['wdm_adp_payment_method']);
}

function manage_adp_method_field(){
    
    add_option('wdm_adp_payment_method', 'parallel');

    $options = array('parallel', 'chained');
	
    foreach($options as $option) {
	    
		$checked = (get_option('wdm_adp_payment_method') == $option) ? ' checked="checked" ' : '';
		
		if($option == 'parallel')
		    $opt = __('Parallel', 'wdm-ultimate-auction');
		else
		    $opt = __('Chained', 'wdm-ultimate-auction');
		    
		echo "<input id='adp_method_field' ".$checked." value='$option' class='wdm_adp_mthd' name='wdm_adp_payment_method' type='radio' /> $opt &nbsp;&nbsp;";
    }
	
	?>
	<div class="ult-auc-settings-tip">
        <?php
        echo '<br /><a href="https://developer.paypal.com/docs/classic/adaptive-payments/integration-guide/APIntro/" target="_blank">'.__("What is parallel and chained adaptive payments?", "wdm-ultimate-auction").'</a>';?>
        </div>
	<?php
}
?>