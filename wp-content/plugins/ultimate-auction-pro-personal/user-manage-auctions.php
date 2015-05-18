<?php
class Auctions_List_Table_Front {        
    var $allData;
    var $auction_type;
    
    function wdm_get_data($auc_type, $tab_char){
	
	$this->auction_type = $auc_type;
	
        if($this->auction_type == "expired")
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
	elseif($this->auction_type == "scheduled")
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
	$results = array();
	//$tab_char = wdm_current_perm_char();
	
        foreach($auction_item_array as $single_auction){
            $authorID = $single_auction->post_author;
            $userID = get_current_user_id();
	    $user_det = new WP_User($userID);
	    $user_auth_det = new WP_User($authorID);
	    $author_mail = $user_auth_det->user_email;
	     
            if((!in_array('administrator',$user_det->roles) && $authorID == $userID) || (in_array('administrator',$user_det->roles) && in_array('administrator',$user_auth_det->roles)))
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
	    
            $row = array();
            $row['ID']=$single_auction->ID;
            $row['title']='<input class="wdm_chk_auc_act" value='.$single_auction->ID.' type="checkbox" />'.prepare_single_auction_title($single_auction->ID, $single_auction->post_title);
	    
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
            $end_date = get_post_meta($single_auction->ID,'wdm_listing_ends', true);
	    
	    if($this->auction_type=="scheduled")
		$dt_lbl = __('Starting Date', 'wdm-ultimate-auction');
	    else
		$dt_lbl = __('Creation Date', 'wdm-ultimate-auction');
	    
            $row['date_created']= "<strong> ".$dt_lbl.":</strong> <br />".get_post_meta($single_auction->ID, 'wdm_creation_time', true)." <br /><br /> <strong> ".__('Ending Date', 'wdm-ultimate-auction').":</strong> <br />".$end_date;
            //$row['image_1']="<img src='".get_post_meta($single_auction->ID,'wdm_auction_thumb', true)."' width='90'";
            
            if($this->auction_type=="live" || $this->auction_type=="scheduled")
            {
                $row['action']="<a class='wdm_ua_act_links' href='".$tab_char."dashboard=add-auction&edit_auction=".$single_auction->ID."'>".__('Edit', 'wdm-ultimate-auction')."</a>
		<span class='wdm_ua_act_links'> | </span><div id='wdm-delete-auction-".$single_auction->ID."' class='wdm_ua_act_links' style='color:red;cursor:pointer;'>".__('Delete', 'wdm-ultimate-auction')." <span class='auc-ajax-img'></span></div>
		<span class='wdm_ua_act_links'> | </span><div id='wdm-end-auction-".$single_auction->ID."' class='wdm_ua_act_links' style='color:#21759B;cursor:pointer;'>".__('End Auction', 'wdm-ultimate-auction')."</div>";
                require('ajax-actions/end-auction.php');
            }
            else{
            $row['action']="<div id='wdm-delete-auction-".$single_auction->ID."' class='wdm_ua_act_links' style='color:red;cursor:pointer;'>".__('Delete', 'wdm-ultimate-auction')." <span class='auc-ajax-img'></span></div>
	    <span class='wdm_ua_act_links'> | </span><a class='wdm_ua_act_links' href='".$tab_char."dashboard=add-auction&edit_auction=".$single_auction->ID."&reactivate'>".__('Reactivate', 'wdm-ultimate-auction')."</a>";
			
			//if(get_post_meta($single_auction->ID,'auction_bought_status',true) === 'bought'){
			//	require('ajax-actions/send-download-link.php');
			//	
			//}
			
			}
            //for bidding logic
            $row["bidders"] = "";
	    
            $row_bidders = "";
            global $wpdb;
            $currency_code = substr(get_option('wdm_currency'), -3);
            $query = "SELECT * FROM ".$wpdb->prefix."wdm_bidders WHERE auction_id =".$single_auction->ID." ORDER BY id DESC LIMIT 5";
            $results = $wpdb->get_results($query);
            
	    $ship_amt = '';
	    $ship_amt =  get_post_meta($single_auction->ID, 'wdm_ua_shipping_amt', true);
	    
	    $sa = '';
	    if(!empty($ship_amt))
		$sa = "<br /><br />".__('Shipping amount', 'wdm-ultimate-auction').": <br />".$currency_code." ".$ship_amt;
			    
            if(!empty($results)){
                $cnt_bidder = 0;
                foreach($results as $result){
                    $row_bidders.="<li class='fe_bids_placed_links'><strong><a href='#'>".$result->name."</a></strong> - ".$currency_code." ".$result->bid."</li>";
                    if($cnt_bidder == 0 )
                    {
                        $bidder_id = $result->id;
                        $bidder_name = $result->name;
                    }
                    
                    $cnt_bidder++;
                }
		
		if(!in_array('administrator',$user_det->roles) && ($this->auction_type !="live" && $this->auction_type !="scheduled")){
		    if(get_post_meta($single_auction->ID,'auction_bought_status',true) === 'bought')
			$cp = get_post_meta($single_auction->ID,'wdm_buy_it_now',true);
			else{
				$q ="SELECT MAX(bid) FROM ".$wpdb->prefix."wdm_bidders WHERE auction_id =".$single_auction->ID;
				$cp = $wpdb->get_var($q);
			    }
			    
		    $commission = 0;
		    $commission = get_option('wdm_manage_cm_fees_data');
		    $cp = wdm_auction_commission_calculator($commission, $cp);
		    
		    $final_paid_amt = get_post_meta($single_auction->ID,'finally_paid_amount', true);
		    
		    if(!empty($final_paid_amt))
			$row["bidders"] = $final_paid_amt;
		    else
			$row["bidders"] = $currency_code." ".$cp;
			
			$row["bidders"] .= $sa;
		}
		else{
		    $row["bidders"] = "<div class='wdm-bidder-list-".$single_auction->ID."'><ul>".$row_bidders."</ul></div>";
		    
		    $qry = "SELECT * FROM ".$wpdb->prefix."wdm_bidders WHERE auction_id =".$single_auction->ID." ORDER BY id DESC";
		    $all_bids = $wpdb->get_results($qry);
		    if(count($all_bids) > 5)
		    $row["bidders"] .="<br />
		    <a href='#' class='see-more showing-top-5' rel='".$single_auction->ID."' >".__('See more', 'wdm-ultimate-auction')."</a>";
		    
		}
		
            }
	     elseif(get_post_meta($single_auction->ID,'auction_bought_status',true) === 'bought' && (!in_array('administrator',$user_det->roles) && ($this->auction_type !="live" && $this->auction_type !="scheduled"))){
		    $cp = get_post_meta($single_auction->ID,'wdm_buy_it_now',true);
		    $commission = 0;
		    $commission = get_option('wdm_manage_cm_fees_data');
		    $cp = wdm_auction_commission_calculator($commission, $cp);
		    
		    $final_paid_amt = get_post_meta($single_auction->ID,'finally_paid_amount', true);
		    
		    if(!empty($final_paid_amt))
			$row["bidders"] = $final_paid_amt;
		    else
			$row["bidders"] = $currency_code." ".$cp;
			
			$row["bidders"] .= $sa;
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
			    
			    $email_qry = "SELECT name FROM ".$wpdb->prefix."wdm_bidders WHERE bid =".$winner_bid." AND auction_id =".$single_auction->ID." ORDER BY id DESC";

			    $winner_name = $wpdb->get_var($email_qry);

			    $winner = get_user_by('login', $winner_name);

			    $winner_email = $winner->user_email;
			    
			    $email_sent = get_post_meta($single_auction->ID,'auction_email_sent',true);
			    
			    if(!empty($winner) && in_array('administrator', $winner->roles)){
				$row['email_payment'] = "<div class='wdm-margin-bottom wdm-mark-green'>".__('Won by Administrator', 'wdm-ultimate-auction')."</div><div>".apply_filters('ua_list_winner_info', $winner->user_login, $winner, $single_auction->ID, "e")."</div>";
				if($authorID == $userID)
				require('ajax-actions/send-download-link.php');
			    }
			    else{
				$row['email_payment'] = "<div class='wdm-margin-bottom wdm-mark-green'>".sprintf(__('Won by %s', 'wdm-ultimate-auction'), apply_filters('ua_list_winner_info', $winner->user_login, $winner, $single_auction->ID, "e"))."</div>";
				if($authorID == $userID)
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
		
		$row['invoice'] = "";
		
		$pay_method  = get_post_meta( $single_auction->ID, 'auction_active_pay_method', true );
		
		$invoiceStat = get_post_meta( $single_auction->ID, 'auction_invoice_status', true );
		
		$auctionID = $single_auction->ID;
	    
		if($invoiceStat !== 'Paid')
		    $inv_arr[] = $auctionID;
		    
		$invoiceStat = get_post_meta( $single_auction->ID, 'auction_invoice_status', true );    
		//$admin_email = get_option('wdm_auction_email');
		$payment_qry = get_post_meta($single_auction->ID,'wdm_payment_method',true);
		$payment_method = str_replace("method_"," ",$payment_qry);
		$payment_method = str_replace("_"," ",$payment_method);
		
		if($payment_method == 'mailing')
		    $payment_method = 'cheque';
		    
		$row['invoice'] = "<span>".sprintf(__('Method : %s', 'wdm-ultimate-auction'), $payment_method)."</span><br /><br />";		
		
		if(!empty($invoiceStat) || get_post_meta($single_auction->ID,'auction_bought_status',true) === 'bought' || (!empty($winner) && in_array('administrator', $winner->roles))){
		    
		    if($invoiceStat === 'Paid' || $invoiceStat === 'MarkedAsPaid' || get_post_meta($single_auction->ID,'auction_bought_status',true) === 'bought' || (!empty($winner) && in_array('administrator', $winner->roles))){
			
			if((!empty($winner) && in_array('administrator', $winner->roles))){
			    //$row['invoice'] .= "<div class='wdm-margin-bottom wdm-mark-green'>".__('Won by Administrator', 'wdm-ultimate-auction')."</div>".apply_filters('ua_list_winner_info', $winner->user_login, $winner, $single_auction->ID, "p");
			}
			else{
			    if($invoiceStat === 'Paid' || $invoiceStat === 'MarkedAsPaid')
			    $row['invoice'] .= "<a href='#wdm_ua_fe_single_".$single_auction->ID."' id='wdm_pay_inv_".$single_auction->ID."'>".__('Invoice Details', 'wdm-ultimate-auction')."</a><br /><br />";
			    
			    if(!empty($winner)){
				if(get_post_meta($single_auction->ID,'auction_bought_status',true) !== 'bought')
				    $row['invoice'] .= "<div class='wdm-margin-bottom wdm-mark-green'>".sprintf(__("Paid by %s", "wdm-ultimate-auction"), apply_filters('ua_list_winner_info', $winner->user_login, $winner, $single_auction->ID, "p"))."</div>";
			    }
			
			}
			
			$if_paid = get_post_meta($single_auction->ID, 'wdm_final_payment_to_user', true);
			
			if($if_paid === 'Paid' && $pay_method != 'adaptive'){
			    $row['invoice'] .= "<div class='wdm-margin-bottom wdm-mark-green'>".__("Paid by Administrator", "wdm-ultimate-auction")."</div>";
			}
			else{
			    $userData = new WP_User($userID);
			    if(!in_array('administrator', $userData->roles)){
				
				//$paid_to_seller = get_post_meta($single_auction->ID, 'ua_direct_pay_to_seller', true);
				
				if($paid_to_seller == 'pay' && get_post_meta($single_auction->ID,'auction_bought_status',true) === 'bought')
				{
				    if(!empty($winner))
					$row['invoice'] .= "<div class='wdm-mark-green'>".sprintf(__('Paid by %s', 'wdm-ultimate-auction'), apply_filters('ua_list_winner_info', $winner->user_login, $winner, $single_auction->ID, "p"))."</div>"; 
				}
				elseif($pay_method == 'adaptive'){
				    $row['invoice'] .= "";
				}
				else{
				    if(get_post_meta($single_auction->ID,'auction_bought_status',true) !== 'bought')
					$row['invoice'] .= "<div class='wdm-margin-bottom wdm-mark-red'>".__("Pending with Administrator", "wdm-ultimate-auction")."</div>";
				}
			    }
			}
		    }
		    elseif($invoiceStat === 'Sent'){
		    $row['invoice'] .= "<a href='#wdm_ua_fe_single_".$single_auction->ID."' id='wdm_pay_inv_".$single_auction->ID."'>".__('Invoice Details', 'wdm-ultimate-auction')."</a>";
		    if(!empty($winner))
			$row['invoice'] .= "<br /><br /><div class='wdm-margin-bottom wdm-mark-red'>".sprintf(__("Pending with %s", "wdm-ultimate-auction"), apply_filters('ua_list_winner_info', $winner->user_login, $winner, $single_auction->ID, "p"))."</div>";
		    }
		    else{
		    $row['invoice'] .= "<a href='#wdm_ua_fe_single_".$single_auction->ID."' id='wdm_pay_inv_".$single_auction->ID."'>".__('Invoice Details', 'wdm-ultimate-auction')."</a>";
		    $row['invoice'] .= "<br /><br /><span style='color:#FF4D00;'>".__("Invoice Status", "wdm-ultimate-auction")." - ".$invoiceStat."</span>";
		    }
		}
		else{
		    $row['invoice'] .= "<span class='wdm-mark-hover'>".__('No Invoice', 'wdm-ultimate-auction')."</span>";
		    
		    $sent_to_seller = get_post_meta($single_auction->ID, 'ua_direct_paymentlink_to_seller', true);
		
		    if($sent_to_seller == 'sent'){
			if(!empty($winner))
			    $row['invoice'] .= "<br /><br /><div class='wdm-margin-bottom wdm-mark-green'>".sprintf(__("Email with payment details sent to %s", "wdm-ultimate-auction"), apply_filters('ua_list_winner_info', $winner->user_login, $winner, $single_auction->ID, "p"))."</div>";
		    }  
		}
	    }
            
	    //best offers for live auctions only.

            if( $this->auction_type === "live" )
            {

                $auction_best_offers_arr = get_post_meta( $single_auction->ID, 'auction_best_offers' ,true );

                if( ! is_array( $auction_best_offers_arr ) || empty( $auction_best_offers_arr ) )

                    $auction_best_offers_arr = array();

                
                $row[ 'bidders' ] .= "<br /><br />";

		if(!empty($auction_best_offers_arr))

		    $row[ 'bidders' ] .= "<strong>".__("Best Offers", "wdm-ultimate-auction").":</strong>";

		    

		$row[ 'bidders' ] .= "<div class='wdm-ua-bst-offrs-box' data-bst-offer-uaid='".$single_auction->ID."'>";

                

                foreach( $auction_best_offers_arr as $offer_sender_id => $bst_offr_data ){

                    $offer_sender_name = "";

                    $offer_sender = new WP_User( $offer_sender_id );

                    $offer_sender_name = $offer_sender->user_login;

                    $offer_sender_email = $offer_sender->user_email;

                    $row[ 'bidders' ] .= "<br/><div class='wdm-ua-best-offer-row'><div class='wdm-ua-offer-details'><a href='#'><span class='wdm-mark-bold'>".$offer_sender_name.": "."</span></a><span>".$currency_code." ".$bst_offr_data['offer_val']." "."</span></div><br/>

                        <div class = 'wdm-ua-offer-approval'><span class='wdm-mark-green wdm-ua-bst-offr-accept' data-bst-offr-senderid='".$offer_sender_id."' data-bst-offr-sendername='".$offer_sender_name."' data-bst-offr-senderemail='".$offer_sender_email."' data-best-offer-val='".$bst_offr_data['offer_val']."'>".__("Accept","wdm-ultimate-auction")."</span> <span class='wdm-mark-red wdm-ua-bst-offr-reject' data-bst-offr-senderid='".$offer_sender_id."'>".__("Reject","wdm-ultimate-auction")."</span></div></div>";

                }

                $row[ 'bidders' ] .= "</div>";

            }
            
            $data_array[]=$row;
            
            require('ajax-actions/delete-auction.php');
	    require('ajax-actions/show_single_inv.php');
	     require_once('ajax-actions/best-offers-accept-reject.php');
	}
        }
	
	if($this->auction_type === "expired"){
	    $auctionID = implode(",", $inv_arr);
	    require_once('ua-paypal-invoice/ajax-actions/get-invoice-details.php');
	}
	
	require_once('ajax-actions/see-more-bidders.php');
	
	$this->allData=$data_array;
	
        return $data_array;          
    }               
        
    function get_columns($auc_type){
    
    $userID = get_current_user_id();
    $user_det = new WP_User($userID);
    
    if(in_array('administrator',$user_det->roles))
	$bid_col_name = __('Bids Placed', 'wdm-ultimate-auction');
    else
	$bid_col_name = __('Final Amount', 'wdm-ultimate-auction');
       
    if($auc_type == "live"){
	$columns =   array(
	//'image_1'   => __('Image', 'wdm-ultimate-auction'),
	'title' => '<input class="wdm_select_all_chk" type="checkbox" />'.__('Title', 'wdm-ultimate-auction'),
	'payment_made'        => __('Listing Fee Payment', 'wdm-ultimate-auction'),
	'date_created' => __('Creation / Ending Date', 'wdm-ultimate-auction'),
	'current_price' => __('Starting / Current Price', 'wdm-ultimate-auction'),
	'bidders'   => __('Bids Placed', 'wdm-ultimate-auction'),
	'action'    => ''/*__('Actions', 'wdm-ultimate-auction')*/
    );
    }
    elseif($auc_type == "scheduled"){
	$columns =   array(
	//'image_1'   => __('Image', 'wdm-ultimate-auction'),
	'title' => '<input class="wdm_select_all_chk" type="checkbox" />'.__('Title', 'wdm-ultimate-auction'),
	'payment_made'        => __('Listing Fee Payment', 'wdm-ultimate-auction'),
	'date_created' => __('Starting / Ending Date', 'wdm-ultimate-auction'),
	'current_price' => __('Starting / Current Price', 'wdm-ultimate-auction'),
	//'bidders'   => __('Bids Placed', 'wdm-ultimate-auction'),
	'action'    => ''/*__('Actions', 'wdm-ultimate-auction')*/
    );
    }
    else{
	$columns =   array(
	//'image_1'   => __('Image', 'wdm-ultimate-auction'),
	'title' => '<input class="wdm_select_all_chk" type="checkbox" />'.__('Title', 'wdm-ultimate-auction'),
	'payment_made'        => __('Listing Fee Payment', 'wdm-ultimate-auction'),
	'date_created' => __('Creation / Ending Date', 'wdm-ultimate-auction'),
	'final_price' => __('Starting / Final Price', 'wdm-ultimate-auction'),
	'bidders'   => $bid_col_name,
	'email_payment'   => __('Expiry Reason', 'wdm-ultimate-auction'),
	'invoice'   => __('Payment', 'wdm-ultimate-auction'),
	'action'    => ''/*__('Actions', 'wdm-ultimate-auction')*/
    );
    }
    
    if(in_array('administrator',$user_det->roles)){
	
	unset($columns['payment_made']);
    }
	
    return $columns;  
    }
}
?>