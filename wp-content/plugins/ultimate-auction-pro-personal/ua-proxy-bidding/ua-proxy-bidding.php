<?php

add_filter('ua_add_bidding_engine_option', 'wdm_ua_proxy_bid_engines', 99, 1);

function wdm_ua_proxy_bid_engines($bidding_engine){
    $bidding_engine[] = array("val" => "proxy", "text" => __("Proxy Bidding", "wdm-ultimate-auction"));
    return $bidding_engine;
}

add_filter('ua_add_bidding_engine', 'wdm_ua_proxy_bid_engine', 99, 1);

function wdm_ua_proxy_bid_engine($bidding_engine){ 
    $bidding_engine[] = array("val" => "proxy", "text" => __("Proxy Bidding", "wdm-ultimate-auction"));
    return $bidding_engine;
}

add_filter('wdm_ua_modified_bid_amt', 'wdm_ua_proxy_bid_amt', 99, 3);

function wdm_ua_proxy_bid_amt($cbid, $hbid, $aid){
    
    $eng = get_post_meta($aid, 'wdm_bidding_engine', true);
        
    if($eng === 'proxy'){
        
        $rp = get_post_meta($aid,'wdm_lowest_bid',true);
        
        $inc = get_post_meta($aid,'wdm_incremental_val',true);
    
        $high_bid = get_post_meta($aid,'wdm_highest_bid_amt',true);
        
        $hbidder = get_post_meta($aid,'wdm_highest_bid_user',true);
        
        //$hbidder = unserialize($hbidder);
        
        if(empty($high_bid)){
            $high_bid = $hbid;
            
            global $wpdb;
            $wpdb->hide_errors();
            $q="SELECT name, email FROM ".$wpdb->prefix."wdm_bidders WHERE auction_id =".$aid." AND bid =".$high_bid;
            $hbidder = $wpdb->get_row($q);
        }   
        //$hbid = get_post_meta($aid,'wdm_highest_bid_amt',true);
        $curr_user = wp_get_current_user();
        
        $usr_high_bid = (double)get_user_meta($curr_user->ID, 'bidder_highest_bid_for_'.$aid ,true);
        
        if(empty($usr_high_bid) || ($cbid > $usr_high_bid))
            update_user_meta($curr_user->ID, 'bidder_highest_bid_for_'.$aid ,$cbid);
    
        if($hbid >= $rp){
                if($cbid <= ($high_bid-$inc)){
		    $ctemp = $cbid;
                    $cbid = $cbid + $inc;
                    return array('abid' => $cbid, 'cbid' => $ctemp, 'name' => $hbidder['name'], 'email' => $hbidder['email']);
                }
                elseif($cbid <= $high_bid){
                    $ctemp = $cbid;
                    $cbid = $high_bid;
                    return array('abid' => $cbid, 'cbid' => $ctemp, 'name' => $hbidder['name'], 'email' => $hbidder['email']);
                }
                elseif($cbid >= ($high_bid+$inc)){
                    update_post_meta($aid,'wdm_highest_bid_amt',$cbid);
                    update_post_meta($aid,'wdm_highest_bid_user', array('name' => $curr_user->user_login, 'email' => $curr_user->user_email));
                    $cbid = $high_bid+$inc;
                }
                elseif($cbid > $high_bid){
                    update_post_meta($aid,'wdm_highest_bid_amt',$cbid);
                    update_post_meta($aid,'wdm_highest_bid_user', array('name' => $curr_user->user_login, 'email' => $curr_user->user_email));
                    //return $cbid;
                }
        }
	elseif($cbid >= $rp){
	    update_post_meta($aid,'wdm_highest_bid_amt',$cbid);
            update_post_meta($aid,'wdm_highest_bid_user', array('name' => $curr_user->user_login, 'email' => $curr_user->user_email));
	    $cbid = $rp;
	}
    }   
    return $cbid;
}

add_action('wdm_ua_modified_bid_place', 'wdm_modified_bid_place');

function wdm_modified_bid_place($args){
    global $wpdb;
    $wpdb->show_errors();
        $wpdb->insert( 
	$wpdb->prefix.'wdm_bidders', 
	array( 
		'name' => $args['orig_name'], 
		'email' => $args['orig_email'],
                'auction_id' => $args['auc_id'],
                'bid' => $args['orig_bid'],
                'date' => date("Y-m-d H:i:s", time())
	), 
	array( 
		'%s', 
		'%s',
                '%d',
                '%f',
                '%s'
	) 
        );
         
        update_post_meta($args['auc_id'], 'wdm_previous_bid_value', $args['mod_bid']); //store bid value of the most recent bidder 

               $place_bid = $wpdb->insert( 
               $wpdb->prefix.'wdm_bidders', 
               array( 
		'name' => $args['mod_name'], 
		'email' => $args['mod_email'],
                'auction_id' => $args['auc_id'],
                'bid' => $args['mod_bid'],
                'date' => date("Y-m-d H:i:s", time())
            ), 
               array( 
		'%s', 
		'%s',
                '%d',
                '%f',
                '%s'
            ) 
            );
        
            if($place_bid){
               $c_code = substr(get_option('wdm_currency'), -3);
        
            $char = $args['site_char'];
                
            $ret_url = $args['auc_url'].$char."ult_auc_id=".$args['auc_id'];
            
            $adm_email = get_option("wdm_auction_email");
            
	    if($args['email_type'] === 'winner'){
		update_post_meta($args['auc_id'], 'wdm_listing_ends', date("Y-m-d H:i:s", time()));
		$check_term = term_exists('expired', 'auction-status');
		wp_set_post_terms($args['auc_id'], $check_term["term_id"], 'auction-status');
                update_post_meta($args['auc_id'], 'email_sent_imd', 'sent_imd');
		
                $args['stat'] = "Won";
	    }
	    else{
		$args['stat'] = "Placed";
	    }
            
            $args['adm_email'] = $adm_email;
            $args['ret_url'] = $ret_url;

            $args['type'] = 'proxy';
            
	    echo json_encode($args);
    }
}

add_filter('wdm_ua_text_before_bid_section', 'wdm_proxy_notification_text', 99, 2);

function wdm_proxy_notification_text($text, $id){
    
    $eng = get_post_meta($id, 'wdm_bidding_engine', true);
        
    if($eng === 'proxy'){
	$text = '<small style="color:#4169E1; float: left; margin-bottom: 10px;width:100%;">'.__('Please note: This auction is under proxy bidding.', 'wdm-ultimate-auction').'</small>';
    }
    
    return $text;
}

add_filter('wdm_ua_text_after_bid_form', 'wdm_user_maximum_bid_text', 99, 2);

function wdm_user_maximum_bid_text($text, $id){
    
    $eng = get_post_meta($id, 'wdm_bidding_engine', true);
    
    $curr_user = wp_get_current_user();
    
    if($eng === 'proxy'){
	$high_bid_usr = get_user_meta($curr_user->ID, 'bidder_highest_bid_for_'.$id , true);
	$text = __('Your Maximum Bid',  'wdm-ultimate-auction').': '.sprintf("%.2f", $high_bid_usr);
    }
    return $text;
}

function wdm_proxy_bid_notification(){
    $hdr = "";
           // $hdr  = "From: ". get_bloginfo('name') ." <". $adm_email ."> \r\n";
            $hdr .= "MIME-Version: 1.0\r\n";
            $hdr .= "Content-type:text/html;charset=UTF-8" . "\r\n";
 
                if(function_exists('wdm_ua_seller_notification_mail'))
                wdm_ua_seller_notification_mail($_POST['adm_email'], $_POST['mod_bid'], $_POST['ret_url'], $_POST['auc_name'], $_POST['auc_desc'], $_POST['mod_email'], $_POST['mod_name'], $hdr, '');
            
            if(function_exists('wdm_ua_bidder_notification_mail'))
                wdm_ua_bidder_notification_mail($_POST['orig_email'], $_POST['orig_bid'], $_POST['ret_url'], $_POST['auc_name'], $_POST['auc_desc'], $hdr, '');
	    if(function_exists('wdm_ua_outbid_notification_mail'))
                wdm_ua_outbid_notification_mail($_POST['orig_email'], $_POST['mod_bid'], $_POST['ret_url'], $_POST['auc_name'], $_POST['auc_desc'], $hdr, '');
	    
            if($_POST['stat'] === "Won"){
                if(function_exists('ultimate_auction_email_template'))
                    ultimate_auction_email_template($_POST['auc_name'], $_POST['auc_id'], $_POST['auc_desc'], $_POST['mod_bid'], $_POST['mod_email'], $_POST['ret_url']);
            }
            
    die();
}

add_action('wp_ajax_other_bid_notification', 'wdm_proxy_bid_notification');
add_action('wp_ajax_nopriv_other_bid_notification', 'wdm_proxy_bid_notification');

add_action('wdm_auto_extend_auction_endtime', 'wdm_auto_extend_end_time', 10, 2);

add_action('wdm_ua_update_ext_settings','wdm_save_valid_exttime', 10);

function wdm_save_valid_exttime(){
    
    if(isset($_POST['auto_extend_when']))
        update_option('wdm_auto_extend_when', $_POST['auto_extend_when']);
    
    if(isset($_POST['auto_extend_when_m']))
        update_option('wdm_auto_extend_when_m', $_POST['auto_extend_when_m']);
            
    if(isset($_POST['auto_extend_time']))
        update_option('wdm_auto_extend_time', $_POST['auto_extend_time']);
        
    if(isset($_POST['auto_extend_time_m']))
        update_option('wdm_auto_extend_time_m', $_POST['auto_extend_time_m']); 
}

function wdm_auto_extend_end_time($setting, $id){
    
    add_settings_field(
	    'auto_extend_when', 
	    __('Avoid Bid Snipping', 'wdm-ultimate-auction'), 
	    'auto_extend_end_time', 
	    $setting, 
	    $id 			
	);
}

function auto_extend_end_time(){
    printf(__("Extend when %s hours and %s minutes remaining", "wdm-ultimate-auction"),  '<input name="auto_extend_when" type="text" id="auto_extend_when" class="small-text number" value="'.get_option('wdm_auto_extend_when').'"/>', '<input name="auto_extend_when_m" type="text" id="auto_extend_when_m" class="small-text number" value="'.get_option('wdm_auto_extend_when_m').'"/>');
    
    echo '<br /><br />';
    
    printf(__("Extend by %s hours and %s minutes", "wdm-ultimate-auction"), '<input name="auto_extend_time" type="text" id="auto_extend_time" class="small-text number" value="'.get_option('wdm_auto_extend_time').'"/>', '<input name="auto_extend_time_m" type="text" id="auto_extend_time_m" class="small-text number" value="'.get_option('wdm_auto_extend_time_m').'"/>');

echo '<br /><br /><div class="ult-auc-settings-tip">• '.__("What is bid snipping - Bid sniping or auction sniping is the practice of bidding on an item in the last few seconds (or second!) to leave no time for other bidders to respond with a higher bid.", "wdm-ultimate-auction").'</div>';

echo '<br /><div class="ult-auc-settings-tip">• '.__("Avoiding snipping - If someone places bid in last few minutes or hours then you can extend end time by some hours/minutes to give all bidders chance to re-bid.", "wdm-ultimate-auction").'</div>';

echo '<br /><div class="ult-auc-settings-tip">'.__("NOTE: This applies only to Simple Bidding Engine", "wdm-ultimate-auction").'</div>';
}

function extend_auction_time($aucid){
    $ext_whn = get_option('wdm_auto_extend_when');
    $ext_whn = (double)$ext_whn;
    
    $ext_tm = get_option('wdm_auto_extend_time');
    $ext_tm = (double)$ext_tm;
    
    $ext_whnm = get_option('wdm_auto_extend_when_m');
    $ext_whnm = (double)$ext_whnm;
    
    $ext_tmm = get_option('wdm_auto_extend_time_m');
    $ext_tmm = (double)$ext_tmm;
    
    $eng = get_post_meta($aucid, 'wdm_bidding_engine', true);
    
    if(($ext_whn > 0 || $ext_whnm > 0) && ($ext_tm > 0 || $ext_tmm > 0) && $eng != 'proxy'){
        $le = get_post_meta($aucid, 'wdm_listing_ends', true);
    
        if((strtotime(date("Y-m-d H:i:s", time()))) >= (strtotime($le) - (($ext_whn*3600) + ($ext_whnm*60)))){
            $dt = strtotime($le)+(($ext_tm*3600) + ($ext_tmm*60));
            update_post_meta($aucid, 'wdm_listing_ends', date("Y-m-d H:i:s", $dt));
        }
    }
}

add_action('wdm_extend_auction_time', 'extend_auction_time', 10, 1);
?>