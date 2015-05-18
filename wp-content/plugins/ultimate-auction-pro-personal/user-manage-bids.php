<?php
class Bids_List_Table_Front {        
    var $allData;
    var $bid_type;
         
    function wdm_get_data($bid_type){
        
        $this->bid_type = $bid_type;
        
        $userID = get_current_user_id();
	$user_det = new WP_User($userID);
//        if(in_array('administrator', $user_det->roles))
//	    $auction_auth_email = get_option("wdm_auction_email");
//	else    
	    $auction_auth = $user_det->user_login;
	
        global $wpdb;
        $qry = "SELECT id,auction_id,bid FROM ".$wpdb->prefix."wdm_bidders WHERE name ='".$auction_auth."' ORDER BY date DESC";
	$bids_data = $wpdb->get_results($qry);
        
        $all_auctions = array();
        $won_bids_array = array();
	$not_won_bids_array = array();
        $active_bids_array = array();
        $bid_data = array();
        $inv_arr = array();
	
        foreach($bids_data as $bdata){
            
	    $reserve_pric_met = get_post_meta($bdata->auction_id, 'wdm_lowest_bid',true);
	    $bid_qr = "SELECT MAX(bid) FROM ".$wpdb->prefix."wdm_bidders WHERE auction_id =".$bdata->auction_id;
	    $winner_bid = $wpdb->get_var($bid_qr);
	  
	    $name_qr = "SELECT name FROM ".$wpdb->prefix."wdm_bidders WHERE bid =".$winner_bid." AND auction_id =".$bdata->auction_id;
	    //$winner_email = $wpdb->get_var($email_qr);
            
	    $winner_name = $wpdb->get_var($name_qr);
			    
	    $winner = get_user_by('login', $winner_name);
			    
	    $winner_email = $winner->user_email;
	    
		$active_terms = wp_get_post_terms($bdata->auction_id, 'auction-status',array("fields" => "names"));
		
                if($auction_auth == $winner_name && $bdata->bid == $winner_bid && $winner_bid >= $reserve_pric_met && in_array('expired',$active_terms)){
                    $won_bids_array[] = $bdata;
                }
		else{
			if(in_array('expired',$active_terms)){
			    $not_won_bids_array[] = $bdata;
			}
			else{
			    $active_bids_array[] = $bdata;
			}
		}
        }
        
        if(isset($bid_type) && $bid_type=="active")
        {
            $all_auctions = $active_bids_array;
        }
	elseif(isset($bid_type) && $bid_type=="not_won")
        {
            $all_auctions = $not_won_bids_array;
        }
        else
        {
            $all_auctions = $won_bids_array;
        }
    
        $data_array=array();
        
        foreach($all_auctions as $single_auction){
         
            $currency_code = substr(get_option('wdm_currency'), -3);
            $auction_post = get_post($single_auction->auction_id);
            
            if($auction_post){
            $auction_post_author = $auction_post->post_author;
            $auction_post_author_data = new WP_User($auction_post_author);
            
            $row = array();
            $row['ID']=$single_auction->auction_id;
	    $row['owner'] = $auction_post_author_data->user_login;
	    
            if(in_array('administrator', $auction_post_author_data->roles)){
                $row['paypal_email'] = get_option('wdm_paypal_address');
            }
            else{
                $row['paypal_email'] = get_user_meta( $auction_post_author, 'auction_user_paypal_email', true);
            }
            
            $row['title']= prepare_single_auction_title($single_auction->auction_id, get_the_title($single_auction->auction_id));
            $end_date = get_post_meta($single_auction->auction_id,'wdm_listing_ends', true);
            $row['date_created']= "<strong> ".__('Creation Date', 'wdm-ultimate-auction').":</strong> <br />".get_post_meta($single_auction->auction_id, 'wdm_creation_time', true)." <br /><br /> <strong> ".__('Ending Date', 'wdm-ultimate-auction').":</strong> <br />".$end_date;
            
            $start_price = get_post_meta($single_auction->auction_id,'wdm_opening_bid', true);
            $buy_it_now_price = get_post_meta($single_auction->auction_id,'wdm_buy_it_now',true);
	    
	    $row['current_price']  = "";
	    $row['final_price']  = "";
	    if(empty($start_price) && !empty($buy_it_now_price))
	    {
		$row['current_price']  = "<strong>".__('Buy Now Price', 'wdm-ultimate-auction').":</strong> <br />".$currency_code." ".$buy_it_now_price;
		$row['final_price']  = "<strong>".__('Buy Now Price', 'wdm-ultimate-auction').":</strong> <br />".$currency_code." ".$buy_it_now_price;
	    }
	    elseif(!empty($start_price))
	    {
		$query="SELECT MAX(bid) FROM ".$wpdb->prefix."wdm_bidders WHERE auction_id =".$single_auction->auction_id;
		$curr_price = $wpdb->get_var($query);
		
		if(empty($curr_price))
			$curr_price = $start_price;
            
		$row['current_price']  = "<strong>".__('Starting Price', 'wdm-ultimate-auction').":</strong> <br />".$currency_code." ".$start_price;
		$row['current_price'] .= "<br /><br /> <strong>".__('Current Price', 'wdm-ultimate-auction').":</strong><br /> ".$currency_code." ".$curr_price;
		
		$row['final_price']  = "<strong>".__('Starting Price', 'wdm-ultimate-auction').":</strong> <br />".$currency_code." ".$start_price;
		$row['final_price'] .= "<br /><br /> <strong>".__('Final Price', 'wdm-ultimate-auction').":</strong><br /> ".$currency_code." ".$curr_price;
	    }
	    
	    $row['payment'] = '';
	    
	    $invoiceStat = get_post_meta( $single_auction->auction_id, 'auction_invoice_status', true );
	    
	    $auctionID = $single_auction->auction_id;
		
	    if($invoiceStat !== 'Paid')
		$inv_arr[] = $auctionID;
		
	    $invoiceStat = get_post_meta( $single_auction->auction_id, 'auction_invoice_status', true );
	    
	    if(!empty($invoiceStat)){
		if($invoiceStat === 'Paid' || $invoiceStat === 'MarkedAsPaid')
		    $row['payment'] = "<span class='wdm-mark-green'>".__('Paid', 'wdm-ultimate-auction')."</span><br /><br />";
		elseif($invoiceStat === 'Sent')
		    $row['payment'] = "<span class='wdm-mark-red'>".__('Pending', 'wdm-ultimate-auction')."</span><br /><br />";
		else
		    $row['payment'] = "<span class='wdm-mark-hover'>".__('Invoice Status', 'wdm-ultimate-auction')." - ".$invoiceStat."</span><br /><br />";
		    
		$inv_url = get_post_meta( $single_auction->auction_id, 'paypal_invoice_url', true );
	    
		$row['payment'].= !empty($inv_url) ? '<a href="'.$inv_url.'" target="_blank"> '.__("View Invoice", "wdm-ultimate-auction").' </a>' : '';
	    }
	    else
		$row['payment'] = "<span class='wdm-mark-hover'>".__('No Invoice', 'wdm-ultimate-auction');
		    
            $row['amount']= $currency_code." ".$single_auction->bid;
	    $bidder_id = $single_auction->id;
	    
            $data_array[]=$row;
            
        }
        }
	
	if($this->bid_type === "won"){
	    $auctionID = implode(",", $inv_arr);
	    require_once('ua-paypal-invoice/ajax-actions/get-invoice-details.php');
	}
	
        $this->allData=$data_array;
        return $data_array;            
    }               
               
    function get_columns($bid_type){
    
    if($bid_type == "won")
    $columns =   array(
    'owner' => __('Auction Owner', 'wdm-ultimate-auction'),	
    'paypal_email' => __("Owner's PayPal Email", "wdm-ultimate-auction"),    
    'title' => __('Auction Title', 'wdm-ultimate-auction'),
    'date_created' => __('Creation / Ending Date', 'wdm-ultimate-auction'),
    'current_price' => __('Starting / Current Price', 'wdm-ultimate-auction'),
    'payment' => __('Payment', 'wdm-ultimate-auction')
    );
    else
    $columns =   array(
    'owner' => __('Auction Owner', 'wdm-ultimate-auction'),
    'paypal_email' => __("Owner's PayPal Email", "wdm-ultimate-auction"), 
    'title' => __('Auction Title', 'wdm-ultimate-auction'),
    'date_created' => __('Creation / Ending Date', 'wdm-ultimate-auction'),
    'final_price' => __('Starting / Final Price', 'wdm-ultimate-auction'),
    'amount'    => __('Bid Amount', 'wdm-ultimate-auction')
    );
    return $columns;  
    }
    
}

?>