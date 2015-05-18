<?php

class Payment_List_Table_Front {        
    var $payData;
         
    function payment_get_data($p_type){
        
          $args = array(
                'posts_per_page'  => -1,
                'post_type'       => 'ultimate-auction',
                'meta_key'  => 'auction_invoice_prepared',
                'meta_value' => 'yes'
                );
       
        $payment_items = get_posts( $args );
        
        $data_arr = array();
        $comp_arr = array();
        $rem_arr = array();
        $inv_arr = array();
        $inv_arr1 = array();
	
        foreach($payment_items as $payment_auc){
            
	    $user = wp_get_current_user();
            
            if(in_array('administrator', $user->roles)){
            
            $invoice_data   = get_post_meta( $payment_auc->ID, 'paypal_invoice_data', true );
            if(is_serialized($invoice_data))
                $invoice_data   = unserialize($invoice_data);
                
            $inv_email = get_post_meta( $payment_auc->ID, 'invoice_reciever_email', true );
	    $inv_price = get_post_meta( $payment_auc->ID, 'invoice_reciever_bid_price', true );
            $inv_curr = get_post_meta( $payment_auc->ID, 'invoice_reciever_currency', true );
            
            $invoice_status     = get_post_meta( $payment_auc->ID, 'auction_invoice_status', true );
            $auctionID = $payment_auc->ID;
            
	    if($invoice_status !== 'Paid'){
		
		$pay_mthd = get_post_meta( $payment_auc->ID, 'auction_active_pay_method', true );
		
		if($pay_mthd == 'adaptive')
		    $inv_arr1[] = $auctionID;
		else
		    $inv_arr[] = $auctionID;
	    }
	    
            $invoice_stat = get_post_meta( $payment_auc->ID, 'auction_invoice_status', true );
            
            if($invoice_stat === 'Paid' || $invoice_stat === 'MarkedAsPaid')
                $comp_arr[] = array('auction' => $payment_auc, 'status' => $invoice_stat, 'data' => $invoice_data, 'email' => $inv_email, 'price' => $inv_price, 'currency' => $inv_curr);
                
            else
                $rem_arr[] = array('auction' => $payment_auc, 'status' => $invoice_stat,  'data' => $invoice_data, 'email' => $inv_email, 'price' => $inv_price, 'currency' => $inv_curr); 
        
        }
        
        else{
            
            $authorID = $payment_auc->post_author;
            $userID = get_current_user_id();
            
            if($authorID == $userID){
            
            $invoice_data   = get_post_meta( $payment_auc->ID, 'paypal_invoice_data', true );
            if(is_serialized($invoice_data))
                $invoice_data   = unserialize($invoice_data);
                
            $inv_email = get_post_meta( $payment_auc->ID, 'invoice_reciever_email', true );
	    $inv_price = get_post_meta( $payment_auc->ID, 'invoice_reciever_bid_price', true );
            $inv_curr = get_post_meta( $payment_auc->ID, 'invoice_reciever_currency', true );
            
            $invoice_status     = get_post_meta( $payment_auc->ID, 'auction_invoice_status', true );
            $auctionID = $payment_auc->ID;
            
            if($invoice_status !== 'Paid'){
		
		$pay_mthd = get_post_meta( $payment_auc->ID, 'auction_active_pay_method', true );
		
		if($pay_mthd == 'adaptive')
		    $inv_arr1[] = $auctionID;
		else
		    $inv_arr[] = $auctionID;
	    }
            
            $invoice_stat = get_post_meta( $payment_auc->ID, 'auction_invoice_status', true );
            
            if($invoice_stat === 'Paid' || $invoice_stat === 'MarkedAsPaid')
                $comp_arr[] = array('auction' => $payment_auc, 'status' => $invoice_stat, 'data' => $invoice_data, 'email' => $inv_email, 'price' => $inv_price, 'currency' => $inv_curr);
                
            else
                $rem_arr[] = array('auction' => $payment_auc, 'status' => $invoice_stat,  'data' => $invoice_data, 'email' => $inv_email, 'price' => $inv_price, 'currency' => $inv_curr);  
            
            }
        }
        }
       
            if(isset($p_type) && $p_type=="past")
            {
                foreach($comp_arr as $ca)
                    $data_arr[] = $this->get_payment_all_list($ca['auction'], $ca['status'], $ca['data'], $ca['email'], $ca['price'], $ca['currency']);  
            }
            elseif(isset($p_type) && $p_type=="outstanding")
            {   foreach($rem_arr as $ra)
                    $data_arr[] = $this->get_payment_all_list($ra['auction'], $ra['status'], $ra['data'], $ra['email'], $ra['price'], $ra['currency']);   
            }
            
        $auctionID = implode(",", $inv_arr);
	$auctionID1 = implode(",", $inv_arr1);
	
        require_once('ajax-actions/get-invoice-details.php');
        
	require_once('ajax-actions/multi-delete-invoice.php');
		    
        $this->payData=$data_arr;
        return $data_arr;            
    }               
        
        function payment_get_inv_data($p_id){
        $inv_arr = array();
        $payment_auc = get_post($p_id);
        
        $data_arr = array();
            
            $invoice_data   = get_post_meta( $p_id, 'paypal_invoice_data', true );
            if(is_serialized($invoice_data))
                $invoice_data   = unserialize($invoice_data);
                
            $inv_email = get_post_meta( $p_id, 'invoice_reciever_email', true );
	    $inv_price = get_post_meta( $p_id, 'invoice_reciever_bid_price', true );
            $inv_curr = get_post_meta( $p_id, 'invoice_reciever_currency', true );
            
            $invoice_status  = get_post_meta( $p_id, 'auction_invoice_status', true );
            $auctionID = $p_id;
            
            if($invoice_status !== 'Paid'){
		
		$pay_mthd = get_post_meta( $p_id, 'auction_active_pay_method', true );
		
		if($pay_mthd == 'adaptive')
		    $inv_arr1[] = $auctionID;
		else
		    $inv_arr[] = $auctionID;
	    }
            
            $invoice_stat = get_post_meta( $p_id, 'auction_invoice_status', true );
            
            $auc_arr = array('auction' => $payment_auc, 'status' => $invoice_stat,  'data' => $invoice_data, 'email' => $inv_email, 'price' => $inv_price, 'currency' => $inv_curr);  
            
            $data_arr = $this->get_payment_all_list($auc_arr['auction'], $auc_arr['status'], $auc_arr['data'], $auc_arr['email'], $auc_arr['price'], $auc_arr['currency']);
            
            $auctionID = implode(",", $inv_arr);
	    $auctionID1 = implode(",", $inv_arr1);
	    
            require_once('ajax-actions/get-invoice-details.php');
	    
            return $data_arr;  
            
        }
        
    function get_payment_all_list($payment_auc, $invoice_stat, $invoice_data, $invoice_email, $invoice_price, $invoice_currency)
    {
            $row = array();
            $row['ID']=$payment_auc->ID;
            $row['title']="<input class='wdm_chk_auc_act' value=".$payment_auc->ID." type='checkbox' style='margin: 0 5px 0 0;' />".$payment_auc->post_title;
            
            //$row['image_1']="<img src='".get_post_meta($payment_auc->ID,'wdm_auction_thumb', true)."' width='90'";
            
	    $inv_num 		= get_post_meta($payment_auc->ID, 'paypal_invoice_num', true);
            $row['inv_num']     = $inv_num;
	    if(is_array($invoice_data) && array_key_exists('invoice.invoiceDate', $invoice_data))
		$invoice_date   = !empty($invoice_data['invoice.invoiceDate']) ? $invoice_data['invoice.invoiceDate'] : '';
	    else{
		$invoice_date 	= get_post_meta($payment_auc->ID, 'paypal_trans_timestamp', true);
		$invoice_date 	= !empty($invoice_date) ? $invoice_date: '';
	    }
            //$invoice_date       = isset($invoice_data['invoice.invoiceDate']) ? $invoice_data['invoice.invoiceDate'] : '';
            $row['inv_date']    = substr($invoice_date, 0, 10);
            $row['bill_to']     = isset($invoice_email) ? $invoice_email : '';
            $row['amt']         = isset($invoice_currency) ? $invoice_currency : '';
            $row['amt']        .= ' ';
            $row['amt']        .= isset($invoice_price) ? $invoice_price : '';
            //$row['inv_url']     = isset($invoice_data['invoiceURL']) ? '<a href="'.$invoice_data['invoiceURL'].'" target="_blank"> Visit </a>' : '';
            $inv_url     	= get_post_meta($payment_auc->ID, 'paypal_invoice_url', true);
            $row['inv_url']     = !empty($inv_url) ? '<a href="'.$inv_url.'" target="_blank"> Visit </a>' : '';
	    
            if(isset($invoice_stat)){
                if($invoice_stat === 'Paid'){
                    $row['invoice_status'] =  '<span style="color:green;">'.__('Paid', 'wdm-ultimate-auction').'</span>';
                }
                elseif($invoice_stat === 'MarkedAsPaid'){
                    $row['invoice_status'] =  '<span style="color:green;">'.__('Marked As Paid', 'wdm-ultimate-auction').'</span>';
                }
                elseif($invoice_stat === 'Sent'){
                    $resend = '';
                    $resend = '<a id="resend-invoice-'.$payment_auc->ID.'" style="cursor:pointer;">'.__('Remind', 'wdm-ultimate-auction').'</a>';
                    $row['invoice_status'] =  '<span style="color:green;">'.__('Sent', 'wdm-ultimate-auction').'<br /><br />'.$resend.'</span>';
                    require('ajax-actions/resend-paypal-invoice.php');
                }
                elseif($invoice_stat === 'failed_to_send'){
                    $bidder_email = get_post_meta( $payment_auc->ID, 'invoice_reciever_email', true);
	            $bid_price    = get_post_meta( $payment_auc->ID, 'invoice_reciever_bid_price', true);
                     
                    $rsend_url = '<a href="" id="inv-resend-email-'.$payment_auc->ID.'">'.__('Resend', 'wdm-ultimate-auction').'</a>';
                    $row['invoice_status'] = '<span style="color:red;">'.sprintf(__('Failed to send email. Kindly, check your PayPal credentials and then try to %s email.', 'wdm-ultimate-auction'), $rsend_url).'</span>';
                    require('ajax-actions/inv-resend-email.php');
                }
                else{
                    $row['invoice_status'] =  '<span style="color:#FF4D00;">'.__('Invoice Status', 'wdm-ultimate-auction').' - '.$invoice_stat.'</span>';
		    
		    if(strtoupper($invoice_stat) === 'EXPIRED'){
			//update_post_meta( $payment_auc->ID, 'auction_email_sent', 'resend' );
			
		    $bidder_email = get_post_meta( $payment_auc->ID, 'invoice_reciever_email', true);
		    $bid_price    = get_post_meta( $payment_auc->ID, 'invoice_reciever_bid_price', true);
                     
                    $row['invoice_status'] .= '<br /><br /> <a href="" id="inv-resend-email-'.$payment_auc->ID.'">'.__('Resend', 'wdm-ultimate-auction').'</a>';
                    require('ajax-actions/inv-resend-email.php');
		    }
                }
		
		$row['invoice_status'] .= '<br /><br />';
            }
            else
                $row['invoice_status'] = '';
		
		$row['invoice_status'] .= "<div id='wdm-delete-invoice-".$payment_auc->ID."' style='color:red;cursor:pointer;'>".__('Delete', 'wdm-ultimate-auction')." <span class='auc-ajax-img'></span></div>";
			
		require('ajax-actions/delete-invoice.php');
            
            return $row;
                  
    }
    
    function get_columns(){
   
    $columns =   array(
    //'image_1'   => __('Image', 'wdm-ultimate-auction'),
    'title' => '<input class="wdm_select_all_chk" type="checkbox" style="margin: 0 5px 0 0;" />'.__('Title', 'wdm-ultimate-auction'),
    'inv_num' => __('Invoice Number', 'wdm-ultimate-auction'),
    'inv_date' => __('Invoice Date', 'wdm-ultimate-auction'),
    'bill_to' => __('Buyer Email', 'wdm-ultimate-auction'),
    'amt' => __('Amount', 'wdm-ultimate-auction'),
    'inv_url' => __('Invoice URL', 'wdm-ultimate-auction'),
    'invoice_status'   => __('Invoice Status', 'wdm-ultimate-auction')
    );
    
    return $columns;  
    }

}
?>