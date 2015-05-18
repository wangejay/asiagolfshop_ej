<?php
if( ! class_exists( 'WP_List_Table' ) ) {
require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
$this->auction_type = (isset($_GET["auction_type"]) && !empty($_GET["auction_type"])) ? $_GET["auction_type"] : "live";

class User_Auctions_List_Table extends WP_List_Table {        
    var $allData;
    var $auction_type;
         
    function wdm_get_data(){
        if(isset($_GET["auction_type"]) && $_GET["auction_type"]=="expired")
        {
            $args = array(
                'posts_per_page'  => -1,
                'post_type'       => 'ultimate-auction',
                'auction-status'  => 'expired',
		'orderby' => 'meta_value',
		'meta_key' => 'wdm_listing_ends',
		'order' => 'DESC'
                );
        }
	elseif(isset($_GET["auction_type"]) && $_GET["auction_type"]=="scheduled")
        {
            $args = array(
                'posts_per_page'  => -1,
                'post_type'       => 'ultimate-auction',
                'auction-status'  => 'scheduled' 
                );
        }
        else
        {
            $args = array(
                'posts_per_page'  => -1,
                'post_type'       => 'ultimate-auction',
                'auction-status'  => 'live' 
                );
        }
        
        $auction_item_array = get_posts( $args );
        $data_array=array();
	$inv_arr = array();
	$inv_arr1 = array();
	$results = array();
	
	$auction_user = get_option('wdm_usr_auctions_list');
	    
        foreach($auction_item_array as $single_auction){
            $userID = $single_auction->post_author;
            $userData = new WP_User($userID);
	    $author_mail = $userData->user_email;
	    
            if((!empty($auction_user) && $auction_user == $userData->user_login) || (empty($auction_user) && !in_array('administrator', $userData->roles)) )
            {
            $act_term = wp_get_post_terms($single_auction->ID, 'auction-status',array("fields" => "names"));
            if(time() >= strtotime(get_post_meta($single_auction->ID,'wdm_listing_ends',true))){
				if(!in_array('expired',$act_term))
				{
					$check_tm = term_exists('expired', 'auction-status');
					wp_set_post_terms($single_auction->ID, $check_tm["term_id"], 'auction-status');
				}
            }
            
	    $listing_trans_amt = get_post_meta($single_auction->ID, 'wdm_auction_listing_amt', true);    
	    $listing_trans_id = get_post_meta($single_auction->ID, 'wdm_auction_listing_transaction', true);
	    $user_paypal_id = get_user_meta( $userID, 'auction_user_paypal_email', true);
	    
            $row = array();
            $row['user'] = "<input class='wdm_chk_auc_act' value=".$single_auction->ID." type='checkbox' style='margin: 0 5px 0 0;' />".$userData->user_login;
	    $row['user'] .= "<br /><br /><a href='mailto:".$userData->user_email."'>".$userData->user_email."</a>";
	    
	    $row['payment_made'] = '';
	    
	    if(empty($listing_trans_amt) && empty($listing_trans_id)){
		$row['payment_made'] = __('Nil', 'wdm-ultimate-auction').' <br /><br />';
	    }
	    else{
		if(!empty($listing_trans_amt))
		    $row['payment_made'] = $listing_trans_amt.'<br /><br />';
		if(!empty($listing_trans_id))
		$row['payment_made'] .= __('Transaction ID', 'wdm-ultimate-auction').': <br /><span class="wdm-mark-hover">'.$listing_trans_id.'</span>';
	    }
	    
	    $row['ID']=$single_auction->ID;
	    $row['title']=prepare_single_auction_title($single_auction->ID, $single_auction->post_title);
            $end_date = get_post_meta($single_auction->ID,'wdm_listing_ends', true);
	    
	    if($this->auction_type=="scheduled")
		$dt_lbl = __('Starting Date', 'wdm-ultimate-auction');
	    else
		$dt_lbl = __('Creation Date', 'wdm-ultimate-auction');
		
            $row['date_created']= "<strong> ".$dt_lbl.":</strong> <br />".get_post_meta($single_auction->ID, 'wdm_creation_time', true)." <br /><br /> <strong> ".__('Ending Date', 'wdm-ultimate-auction').":</strong> <br />".$end_date;
	    
	    $thumb_img = get_post_meta($single_auction->ID,'wdm_auction_thumb', true);
	    if(empty($thumb_img) || $thumb_img == null)
	    {
		$thumb_img = plugins_url('img/no-pic.jpg', __FILE__);
	    }
	    
            $row['image_1']="<img src='".$thumb_img."' width='90'";
            
            if($this->auction_type=="live" || $this->auction_type=="scheduled")
            {
                $row['action']="<a href='?page=add-new-auction&edit_auction=".$single_auction->ID."'>".__('Edit', 'wdm-ultimate-auction')."</a> <br /><br />
		<div id='wdm-delete-auction-".$single_auction->ID."' style='color:red;cursor:pointer;'>".__('Delete', 'wdm-ultimate-auction')." <span class='auc-ajax-img'></span></div> <br />
		<div id='wdm-end-auction-".$single_auction->ID."' style='color:#21759B;cursor:pointer;'>".__('End Auction', 'wdm-ultimate-auction')."</div>";
                require('ajax-actions/end-auction.php');
            }
            else{
				
				$row['action']="<div id='wdm-delete-auction-".$single_auction->ID."' style='color:red;cursor:pointer;'>".__('Delete', 'wdm-ultimate-auction')." <span class='auc-ajax-img'></span></div>
				<br /><a href='?page=add-new-auction&edit_auction=".$single_auction->ID."&reactivate'>".__('Reactivate', 'wdm-ultimate-auction')."</a>";
				
			
			//if(get_post_meta($single_auction->ID,'auction_bought_status',true) === 'bought'){
			//	require('ajax-actions/send-download-link.php');
			//}
			
	    }
            //for bidding logic
            $row['bidders'] = "";
            $row_bidders = "";
            global $wpdb;
            $currency_code = substr(get_option('wdm_currency'), -3);
            $query = "SELECT * FROM ".$wpdb->prefix."wdm_bidders WHERE auction_id =".$single_auction->ID." ORDER BY id DESC LIMIT 5";
            $results = $wpdb->get_results($query);
            
            if(!empty($results)){
                $cnt_bidder = 0;
                foreach($results as $result){
                    $row_bidders.="<li><strong><a href='#'>".$result->name."</a></strong> - ".$currency_code." ".$result->bid."</li>";
                    if($cnt_bidder == 0 )
                    {
                        $bidder_id = $result->id;
                        $bidder_name = $result->name;
                    }
                    
                    $cnt_bidder++;
                }
                $row["bidders"] = "<div class='wdm-bidder-list-".$single_auction->ID."'><ul>".$row_bidders."</ul></div>";
		if($this->auction_type === "live")
		{
		$row["bidders"] .="<div id='wdm-cancel-bidder-".$bidder_id."' style='font-weight:bold;color:#21759B;cursor:pointer;'>".__('Cancel Last Bid', 'wdm-ultimate-auction')."</div>";
		require('ajax-actions/cancel-bid.php');
		}
		
	        $qry = "SELECT * FROM ".$wpdb->prefix."wdm_bidders WHERE auction_id =".$single_auction->ID." ORDER BY id DESC";
                $all_bids = $wpdb->get_results($qry);
                if(count($all_bids) > 5)
                $row["bidders"] .="<br />
                <a href='#' class='see-more showing-top-5' rel='".$single_auction->ID."' >".__('See more', 'wdm-ultimate-auction')."</a>";
                
            }
            else{
                $row["bidders"] = __('No bids placed', 'wdm-ultimate-auction');
            }
          
            $start_price = get_post_meta($single_auction->ID,'wdm_opening_bid', true);
            $buy_it_now_price = get_post_meta($single_auction->ID,'wdm_buy_it_now',true);
	    
	    $row['current_price']  = "";
	    $row['final_price']  = "";
	    if(empty($start_price) && !empty($buy_it_now_price))
	    {
		$row['current_price']  = "<strong>".__('Buy Now Price', 'wdm-ultimate-auction').":</strong> <br />".$currency_code." ".$buy_it_now_price;
		$row['final_price']  = "<strong>".__('Buy Now Price', 'wdm-ultimate-auction').":</strong> <br />".$currency_code." ".$buy_it_now_price;
	    }
	    elseif(!empty($start_price))
	    {
		$query="SELECT MAX(bid) FROM ".$wpdb->prefix."wdm_bidders WHERE auction_id =".$single_auction->ID;
		$curr_price = $wpdb->get_var($query);
		
		if(empty($curr_price))
			$curr_price = $start_price;
            
		$row['current_price']  = "<strong>".__('Starting Price', 'wdm-ultimate-auction').":</strong> <br />".$currency_code." ".$start_price;
		$row['current_price'] .= "<br /><br /> <strong>".__('Current Price', 'wdm-ultimate-auction').":</strong><br /> ".$currency_code." ".$curr_price;
		
		$row['final_price']  = "<strong>".__('Starting Price', 'wdm-ultimate-auction').":</strong> <br />".$currency_code." ".$start_price;
		$row['final_price'] .= "<br /><br /> <strong>".__('Final Price', 'wdm-ultimate-auction').":</strong><br /> ".$currency_code." ".$curr_price;
	    }
	    
            if($this->auction_type === "expired")
            {
		$winner_email = "";
		$buyer_id = "";
		$buyer_id = get_post_meta($single_auction->ID, 'wdm_auction_buyer', true);
		
                $row['email_payment'] = "";
                $amt_pay_to_user = 0;
		$paid_to_seller = get_post_meta($single_auction->ID, 'ua_direct_pay_to_seller', true);
		//$check_method = get_post_meta($single_auction->ID, 'wdm_payment_method', true);
			
                if(get_post_meta($single_auction->ID,'auction_bought_status',true) === 'bought' && $paid_to_seller != 'pay')
                {
                    if(empty($buyer_id)){
			$row['email_payment'] = "<div class='wdm-auction-bought wdm-mark-green'>".__("Expired via Buy Now", "wdm-ultimate-auction")."</div><div class='wdm-margin-bottom wdm-mark-green'>".__("Price", "wdm-ultimate-auction")."[".$currency_code." ".$buy_it_now_price."]</div>";
		    }
		    else{
			$buyer = get_user_by('id', $buyer_id);
			
			if(!empty($buyer) && in_array('administrator', $buyer->roles)){
			    $row['email_payment'] = "<div class='wdm-auction-bought wdm-mark-green'>".__("Bought by Administrator", "wdm-ultimate-auction")."</div><div>".apply_filters('ua_list_winner_info', $buyer->user_login, $buyer, $single_auction->ID, "e")."</div><div class='wdm-margin-bottom wdm-mark-green'>".__("Price", "wdm-ultimate-auction")."[".$currency_code." ".$buy_it_now_price."]</div>";
			}
			else
			    $row['email_payment'] = "<div class='wdm-auction-bought wdm-mark-green'>".sprintf(__("Bought by %s", "wdm-ultimate-auction"), apply_filters('ua_list_winner_info', $buyer->user_login, $buyer, $single_auction->ID, "e"))."</div><div class='wdm-margin-bottom wdm-mark-green'>".__("Price", "wdm-ultimate-auction")."[".$currency_code." ".$buy_it_now_price."]</div>";
		    }
		    
		    $amt_pay_to_user = $buy_it_now_price;
		}
                		else if( get_post_meta($single_auction->ID,'wdm_auction_expired_by',true) == 'ua_best_offers' ){

                    $bo_sender_data = get_post_meta($single_auction->ID,'auction_winner_by_best_offer',true);

                    if( is_array( $bo_sender_data ) ) {

                        reset( $bo_sender_data );

                        $bo_sender_id = key( $bo_sender_data );

                        $bo_sender = get_user_by( 'id', $bo_sender_id );

                        $best_offer_price_row = ( isset( $bo_sender_data[ $bo_sender_id ][ 'offer_val' ] ) ) ? "<div class='wdm-margin-bottom wdm-mark-green'>".__("Price", "wdm-ultimate-auction")."[".$currency_code." ".$bo_sender_data[ $bo_sender_id ][ 'offer_val' ]."]</div>" : "";

			$row['email_payment'] = "<div class='wdm-auction-bought wdm-mark-green'>".sprintf(__("Sold to %s", "wdm-ultimate-auction"), apply_filters('ua_best_offer_sender_info', $bo_sender->user_login, $bo_sender, $bo_sender_id, "e"))."</div>".$best_offer_price_row;

                    }

                }

                else
                {
		    $reserve_price_met = 0;
		    $bid_qry = "";
		    $email_qry = "";
		    $winner_bid = 0;
		    $winner_name = "";
		    $winner = array();
		    $email_sent = "";
		    
                    if(!empty($results))
                    {
			$reserve_price_met = get_post_meta($single_auction->ID, 'wdm_lowest_bid',true);
		    
			$bid_qry = "SELECT MAX(bid) FROM ".$wpdb->prefix."wdm_bidders WHERE auction_id =".$single_auction->ID;
			$winner_bid = $wpdb->get_var($bid_qry);
			
			if($winner_bid >= $reserve_price_met)
			{
			    $amt_pay_to_user = $winner_bid;
			    
			    $email_qry = "SELECT name FROM ".$wpdb->prefix."wdm_bidders WHERE bid =".$winner_bid." AND auction_id =".$single_auction->ID." ORDER BY id DESC";
			    
			    $winner_name = $wpdb->get_var($email_qry);
			    
			    $winner = get_user_by('login', $winner_name);
			    
			    $winner_email = $winner->user_email;
			    
			    $email_sent = get_post_meta($single_auction->ID,'auction_email_sent',true);
			    
			    $current_user_ID = get_current_user_id();
			    
			    if(!empty($winner) && in_array('administrator', $winner->roles)){
				$row['email_payment'] = "<div class='wdm-margin-bottom wdm-mark-green'>".__('Won by Administrator', 'wdm-ultimate-auction')."</div><div>".apply_filters('ua_list_winner_info', $winner->user_login, $winner, $single_auction->ID, "e")."</div>";
				if( $current_user_ID == $userID)
				require('ajax-actions/send-download-link.php');
			    }
			    else{
				$row['email_payment'] = "<div class='wdm-margin-bottom wdm-mark-green'>".sprintf(__('Won by %s', 'wdm-ultimate-auction'), apply_filters('ua_list_winner_info', $winner->user_login, $winner, $single_auction->ID, "e"))."</div>";
				if( $current_user_ID == $userID)
				require('ajax-actions/send-download-link.php');
			    }
			    
			    $row['email_payment']  .= "<strong>".__('Email Status', 'wdm-ultimate-auction').": </strong>";
			    if($email_sent === 'sent')
				$row['email_payment'] .= "<span class='wdm-mark-green'>".__('Yes', 'wdm-ultimate-auction')."</span>";
			    else
				$row['email_payment'] .= "<span class='wdm-mark-red'>".__('No', 'wdm-ultimate-auction')."</span>";
                            
				$row['email_payment'] .= "<br/><br/> <a href='' id='auction-resend-".$single_auction->ID."'>".__('Resend', 'wdm-ultimate-auction')."</a>";
                            
			    require('ajax-actions/resend-email.php');
			}
			else
			{
			    $row['email_payment'] = "<span class='wdm-mark-hover'>".__('Expired without reaching its reserve price', 'wdm-ultimate-auction')."</span>";
			}
                    }
		    else{
			if(empty($start_price))
			    $row['email_payment'] =  "<span class='wdm-mark-red'>".__('Expired without buying', 'wdm-ultimate-auction')."</span>";
			else{
			    if(get_post_meta($single_auction->ID,'auction_bought_status',true) === 'bought'){
				if(empty($buyer_id)){
				    $row['email_payment'] = "<div class='wdm-auction-bought wdm-mark-green'>".__("Expired via Buy Now", "wdm-ultimate-auction")."</div><div class='wdm-margin-bottom wdm-mark-green'>".__("Price", "wdm-ultimate-auction")."[".$currency_code." ".$buy_it_now_price."]</div>";
				}
				else{
				$buyer = get_user_by('id', $buyer_id);
				
				if(!empty($buyer) && in_array('administrator', $buyer->roles)){
				    $row['email_payment'] = "<div class='wdm-auction-bought wdm-mark-green'>".__("Bought by Administrator", "wdm-ultimate-auction")."</div><div>".apply_filters('ua_list_winner_info', $buyer->user_login, $buyer, $single_auction->ID, "e")."</div><div class='wdm-margin-bottom wdm-mark-green'>".__("Price", "wdm-ultimate-auction")."[".$currency_code." ".$buy_it_now_price."]</div>";
				}
				else
				    $row['email_payment'] = "<div class='wdm-auction-bought wdm-mark-green'>".sprintf(__("Bought by %s", "wdm-ultimate-auction"), apply_filters('ua_list_winner_info', $buyer->user_login, $buyer, $single_auction->ID, "e"))."</div><div class='wdm-margin-bottom wdm-mark-green'>".__("Price", "wdm-ultimate-auction")."[".$currency_code." ".$buy_it_now_price."]</div>";

		    }
			    }
			    else
				$row['email_payment'] = "<span class='wdm-mark-red'>".__('Expired without any bids', 'wdm-ultimate-auction')."</span>";}
			}
                }
		
		$row['payment'] = "";
		
		$pay_method = get_post_meta( $single_auction->ID, 'auction_active_pay_method', true );
		
		$invoiceStat = get_post_meta( $single_auction->ID, 'auction_invoice_status', true );
		
		$auctionID = $single_auction->ID;
	    
		if($invoiceStat !== 'Paid'){
		    
		    $pay_mthd = get_post_meta( $single_auction->ID, 'auction_active_pay_method', true );
		
		    if($pay_mthd == 'adaptive')
			$inv_arr1[] = $auctionID;
		    else
			$inv_arr[] = $auctionID;
		}
		
		$invoiceStat = get_post_meta( $single_auction->ID, 'auction_invoice_status', true );
		//$admin_email = get_option('wdm_auction_email');
		$payment_qry = get_post_meta($single_auction->ID,'wdm_payment_method',true);
		$payment_method = str_replace("method_"," ",$payment_qry);
		$payment_method = str_replace("_"," ",$payment_method);
		
		if($payment_method == 'mailing')
		    $payment_method = 'cheque';
		    
		$row['payment'] = "<span>".sprintf(__('Method : %s', 'wdm-ultimate-auction'), $payment_method)."</span><br /><br />";
	        
		
		if(!empty($invoiceStat) || get_post_meta($single_auction->ID,'auction_bought_status',true) === 'bought' || (!empty($winner) && in_array('administrator', $winner->roles))){
		    
		    if($invoiceStat === 'Paid' || $invoiceStat === 'MarkedAsPaid' || get_post_meta($single_auction->ID,'auction_bought_status',true) === 'bought' || (!empty($winner) && in_array('administrator', $winner->roles))){
		    
			if((!empty($winner) && in_array('administrator', $winner->roles))){
			    //$row['payment'] .= "<div class='wdm-margin-bottom wdm-mark-green'>".__('Won by Administrator', 'wdm-ultimate-auction')."</div>".apply_filters('ua_list_winner_info', $winner->user_login, $winner, $single_auction->ID, "p");
			    
			}
			else{    
			if($invoiceStat === 'Paid' || $invoiceStat === 'MarkedAsPaid')
			    $row['payment'] .= "<a href='".admin_url("admin.php?page=invoices&payment_type=past&auction=").$single_auction->ID."' target='_blank'>".__('Invoice Details', 'wdm-ultimate-auction')."</a><br /><br />";
			    
			     if(!empty($winner)){
				if(get_post_meta($single_auction->ID,'auction_bought_status',true) !== 'bought')
				    $row['payment'] .= "<div class='wdm-margin-bottom wdm-mark-green'>".sprintf(__("Paid by %s", "wdm-ultimate-auction"), apply_filters('ua_list_winner_info', $winner->user_login, $winner, $single_auction->ID, "p"))."</div>";
			    }
			}
			
		    $commission = 0;
		    $commission = get_option('wdm_manage_cm_fees_data');
		    $amt_pay_to_user = wdm_auction_commission_calculator($commission, $amt_pay_to_user);
		    
		    //if(isset($_GET["tx"]) && !empty($_GET["tx"])){
	
		    $auth = get_post_meta($single_auction->ID, 'wdm-auth-key', true);
	
		    if(isset($_GET["auc"]) && $_GET["auc"] === $auth){   
		    update_post_meta($single_auction->ID, 'wdm_final_payment_to_user', 'Paid');    
		    update_post_meta($single_auction->ID,'finally_paid_amount', $currency_code." ".$amt_pay_to_user);
		    
		    printf("<script type='text/javascript'>
		    var cur_url = window.location.href;
		    if(window.location.href.indexOf('ua_reloaded') == -1)
		    setTimeout(function() {alert('".__("Payment done to %s successfully.", "wdm-ultimate-auction")."');window.location.href=cur_url+'&ua_reloaded';}, 1000);
		    </script>", $userData->user_login);   
			}
		    //}
		    
		    $if_paid = get_post_meta($single_auction->ID, 'wdm_final_payment_to_user', true);
		    
		    if($if_paid === 'Paid' && $pay_method != 'adaptive'){
			$row['payment'] .= "<div class='wdm-mark-green'>".sprintf(__('Paid to %s', 'wdm-ultimate-auction'), apply_filters('ua_list_winner_info', $userData->user_login, $userData, $single_auction->ID, "p"))."</div>";
		    }
		    else{
			
		    //disable pay to user if payment sent directly to auction owner
		    
		    //$paid_to_seller = get_post_meta($single_auction->ID, 'ua_direct_pay_to_seller', true);
		    
		    if($paid_to_seller == 'pay' && get_post_meta($single_auction->ID,'auction_bought_status',true) === 'bought'){
			$row['payment'] .= "<div class='wdm-mark-hover'>".sprintf(__('Payment directly made to %s', 'wdm-ultimate-auction'), apply_filters('ua_list_winner_info', $userData->user_login, $userData, $single_auction->ID, "p"))."</div>";
		    }
		    else{
		    $shipping_amt = '';
		    $shipping_amt =  get_post_meta($single_auction->ID, 'wdm_ua_shipping_amt', true);
		    
		    $mode = get_option('wdm_account_mode');
	    
		    $paypal_link  = "";
	    
		    if($mode == 'Sandbox')
			$paypal_link  = "https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_xclick";
		    else
			$paypal_link  = "https://www.paypal.com/cgi-bin/webscr?cmd=_xclick";
      
		    $auth_key = get_post_meta($single_auction->ID, 'wdm-auth-key',true);
		    
		    $paypal_link .= "&business=".urlencode($user_paypal_id);
		    //$paypal_link .= "&lc=US";
		    $paypal_link .= "&item_name=".urlencode($single_auction->post_title);
		    $paypal_link .= "&amount=".urlencode($amt_pay_to_user);
		    
		    if(!empty($shipping_amt))
			$paypal_link .= "&shipping=".urlencode($shipping_amt);
		    
		    $paypal_link .= "&currency_code=".urlencode($currency_code);
		    $paypal_link .= "&return=".urlencode(admin_url('admin.php?page=manage-user-auctions&auction_type=expired&auc='.$auth_key));
		    $paypal_link .= "&button_subtype=services";
		    $paypal_link .= "&no_note=0";
		    $paypal_link .= "&bn=PP%2dBuyNowBF%3abtn_buynowCC_LG%2egif%3aNonHostedGuest";
			
		    $payment_link = "";
		    $comm_inv = get_option('wdm_manage_comm_invoice');
		    
		    if($pay_method != 'adaptive'){
			if((get_post_meta($single_auction->ID,'auction_bought_status',true) !== 'bought') || (get_post_meta($single_auction->ID,'auction_bought_status',true) === 'bought' && $comm_inv === 'Yes' && $payment_qry === 'method_paypal'))
			    $payment_link = "<a class='wdm-make-payment-link button-secondary wdm-mark-hover' style='height:auto !important;white-space:normal;' href='".$paypal_link."'>".sprintf(__('Pay %s to %s', 'wdm-ultimate-auction'), $currency_code." ".$amt_pay_to_user, $userData->user_login)."</a><br />".apply_filters('ua_list_winner_info', $userData->user_login, $userData, $single_auction->ID, "p");
		    }
		    
		    if(!empty($shipping_amt))
			$payment_link .= "<span style='font-size: 11px;color: #E76709;'>".__('Shipping amount', 'wdm-ultimate-auction').": ".$currency_code." ".$shipping_amt."</span>";
		    
		    $row['payment'] .= $payment_link;
		    }
		    }
		    }
		    elseif($invoiceStat === 'Sent'){
		    $row['payment'] .= "<a href='".admin_url("admin.php?page=invoices&payment_type=outstanding&auction=").$single_auction->ID."' target='_blank'>".__('Invoice Details', 'wdm-ultimate-auction')."</a>";
		    if(!empty($winner))
			$row['payment'] .= "<br /><br /><div class='wdm-margin-bottom wdm-mark-red'>".sprintf(__("Pending with %s", "wdm-ultimate-auction"), apply_filters('ua_list_winner_info', $winner->user_login, $winner, $single_auction->ID, "p"))."</div>";
		    }
		    else{
		    $row['payment'] .= "<a href='".admin_url("admin.php?page=invoices&payment_type=outstanding&auction=").$single_auction->ID."' target='_blank'>".__('Invoice Details', 'wdm-ultimate-auction')."</a>";
		    $row['payment'] .= "<br /><br /><span style='color:#FF4D00;'>".__("Invoice Status", "wdm-ultimate-auction")." - ".$invoiceStat."</span>";
		    }
		}
		else{
		    $row['payment'] .= "<span class='wdm-mark-hover'>".__('No Invoice', 'wdm-ultimate-auction');
		    $sent_to_seller = get_post_meta($single_auction->ID, 'ua_direct_paymentlink_to_seller', true);
		
		    if($sent_to_seller == 'sent'){
			if(!empty($winner))
			    $row['payment'] .= "<br /><br /><div class='wdm-margin-bottom wdm-mark-green'>".sprintf(__("Email with payment details sent to %s", "wdm-ultimate-auction"), apply_filters('ua_list_winner_info', $winner->user_login, $winner, $single_auction->ID, "p"))."</div>";
		    } 
		}
            }
            
            $data_array[]=$row;
            
            require('ajax-actions/delete-auction.php');
        }
        }
	
	if($this->auction_type === "expired"){
	    $auctionID = implode(",", $inv_arr);
	    $auctionID1 = implode(",", $inv_arr1);
	    require_once('ua-paypal-invoice/ajax-actions/get-invoice-details.php');
	}
	
	require_once('ajax-actions/see-more-bidders.php');
	require_once('ajax-actions/multi-delete.php');
	
        $this->allData=$data_array;
        return $data_array;            
    }               
               
    function get_columns(){
    if($this->auction_type=="live")
    $columns =   array(
    'user'        => __('User', 'wdm-ultimate-auction'),
    'payment_made'        => __('Listing Fee Payment', 'wdm-ultimate-auction'),
    'image_1'   => __('Image', 'wdm-ultimate-auction'),
    'title' => __('Title', 'wdm-ultimate-auction'),
    'date_created' => __('Creation / Ending Date', 'wdm-ultimate-auction'),
    'current_price' => __('Starting / Current Price', 'wdm-ultimate-auction'),
    'bidders'   => __('Bids Placed', 'wdm-ultimate-auction'),
    'action'    => __('Actions', 'wdm-ultimate-auction')
    );
    elseif($this->auction_type=="scheduled")
    $columns =   array(
    'user'        => __('User', 'wdm-ultimate-auction'),
    'payment_made'        => __('Listing Fee Payment', 'wdm-ultimate-auction'),
    'image_1'   => __('Image', 'wdm-ultimate-auction'),
    'title' => __('Title', 'wdm-ultimate-auction'),
    'date_created' => __('Starting / Ending Date', 'wdm-ultimate-auction'),
    'current_price' => __('Starting / Current Price', 'wdm-ultimate-auction'),
    //'bidders'   => __('Bids Placed', 'wdm-ultimate-auction'),
    'action'    => __('Actions', 'wdm-ultimate-auction')
    );
    else
    $columns =   array(
    'user'        => __('User', 'wdm-ultimate-auction'),
    'payment_made'        => __('Listing Fee Payment', 'wdm-ultimate-auction'),
    'title' => __('Title', 'wdm-ultimate-auction'),
    'date_created' => __('Creation / Ending Date', 'wdm-ultimate-auction'),
    'final_price' => __('Starting / Final Price', 'wdm-ultimate-auction'),
    'email_payment'   => __('Expiry Reason', 'wdm-ultimate-auction'),
    'payment'   => __('Payment', 'wdm-ultimate-auction'),
    'action'    => __('Actions', 'wdm-ultimate-auction')
    );
    return $columns;  
    }
    
    function get_sortable_columns(){
        $sortable_columns = array(
                        'ID' => array('ID',false),
                        'title' => array('title',false),
                        'user' => array('user',false)
                        );
        return $sortable_columns;
    }
    
    function prepare_items() {
    $this->auction_type = (isset($_GET["auction_type"]) && !empty($_GET["auction_type"])) ? $_GET["auction_type"] : "live";
    $columns = $this->get_columns();
    $hidden = array();
    $sortable = $this->get_sortable_columns();
    $this->_column_headers = array($columns, $hidden, $sortable);
    $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'ID';
    if($orderby === 'title')
    {
	$this->items = $this->wdm_sort_array($this->wdm_get_data());
    }
    else
    {
	$this->items = $this->wdm_get_data();
    }
    }
    
    function get_result_e(){
        return $this->allData;    
    }
      
    function wdm_sort_array($args){
        if(!empty($args))
        {
        $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'ID';
	
	if($orderby === 'title' || $orderby === 'user')
	    $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
	else
	    $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'desc';
	
        foreach ($args as $array) {
            $sort_key[] = $array[$orderby];
        }
        if($order=='asc')
            array_multisort($sort_key,SORT_ASC,$args);
        else
            array_multisort($sort_key,SORT_DESC,$args);
        } 
        return $args;
    }
    
    function column_default( $item, $column_name ) {
        switch( $column_name ) {
            case 'ID':
            case 'user':
            case 'payment_made':
            case 'image_1':
            case 'title':
            case 'date_created':
            case 'action':
            case 'bidders':
            case 'current_price':
            case 'final_price':
            case 'email_payment':
	    case 'payment':
            return $item[ $column_name ];
            default:
            return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
        }
    }

}

if( isset( $_GET[ 'auction_type' ] ) ) {  
    $manage_auction_tab = $_GET[ 'auction_type' ];  
} 
else{
    $manage_auction_tab = 'live';
    update_option('wdm_usr_auctions_list', '');
}
?>

<ul class="subsubsub">
    <li><a href="?page=manage-user-auctions&auction_type=live" class="<?php echo $manage_auction_tab == 'live' ? 'current' : ''; ?>"><?php _e('Live Auctions', 'wdm-ultimate-auction');?></a>|</li>
    <li><a href="?page=manage-user-auctions&auction_type=scheduled" class="<?php echo $manage_auction_tab == 'scheduled' ? 'current' : ''; ?>"><?php _e('Scheduled Auctions', 'wdm-ultimate-auction');?></a>|</li>
    <li><a href="?page=manage-user-auctions&auction_type=expired" class="<?php echo $manage_auction_tab == 'expired' ? 'current' : ''; ?>"><?php _e('Expired Auctions', 'wdm-ultimate-auction');?></a></li>
</ul>

<br class="clear"><br class="clear">

<?php
$user_args = array(
	'blog_id'      => $GLOBALS['blog_id'],
	'orderby'      => 'login',
	'order'        => 'ASC'
        );
        
$allUsers = get_users( $user_args );

if(isset($_POST['select-auction-users']))
    update_option('wdm_usr_auctions_list', $_POST['select-auction-users']);
?>
    
<form id="users-auctions-form" name="users-auctions-form" method="post" action="" style="float: left;">
<select id="select-auction-users" name="select-auction-users">
    <option id='all-au' value=''><?php _e('All', 'wdm-ultimate-auction');?></option>
    <?php
foreach($allUsers as $au){
    if($au->has_cap('handle_users_page')){?>
    <option id='au-<?php echo $au->user_login;?>' value='<?php echo $au->user_login;?>' <?php if(isset($_POST['select-auction-users']) && $_POST['select-auction-users'] == $au->user_login ) { echo "selected='selected'"; } elseif(get_option('wdm_usr_auctions_list') == $au->user_login) { echo "selected='selected'"; } ?>><?php echo $au->user_login;?></option>
<?php }
}
?>
</select>
<input type="submit" value="<?php _e('Show Auctions', 'wdm-ultimate-auction');?>" class="button-secondary" />
</form>

<div style="float:left;">
    <select id="wdmua_del_all" style="float:left;margin: 0 10px 0 20px;"><option value="del_all_wdm"><?php _e("Delete", "wdm-ultimate-auction");?></option></select>
    <input type="button" id="wdm_mult_chk_del" class="wdm_ua_act_links button-secondary" value="<?php _e("Apply", "wdm-ultimate-auction");?>" />
    <span class="wdmua_del_stats"></span>
</div>
<?php
$myListTable = new User_Auctions_List_Table();
$myListTable->prepare_items();
$myListTable->display();
?>