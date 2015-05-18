<?php
    $logged_user_id = wp_get_current_user();  //get user id
    $logged_user_role = $logged_user_id->roles; //get user role
    $cu_id = get_current_user_id();
    
    $manage_pmt = '';
    
    //if(in_array('administrator', $logged_user_role)){
	
    $default = array(	array( 'slug' => 'paypal', 'label' => __('PayPal', 'wdm-ultimate-auction')),
			array( 'slug' => 'wire_transfer', 'label' => __('Wire Transfer', 'wdm-ultimate-auction')),
			array( 'slug' => 'mailing_address', 'label' => __('Cheque', 'wdm-ultimate-auction')),
			array( 'slug' => 'cash', 'label' => __('Cash', 'wdm-ultimate-auction'))
		    );
	
    $manage_pmt .= '<ul class="wdm_front_links">';
	    
    $methods = apply_filters('ua_add_payment_header_link', $default);
	
    foreach( $methods as $list){

	    if($list['slug'] == 'paypal'){
		$cls = 'wdm_front_on';
	    }
	    else{
		$cls = '';
	    }
	    $manage_pmt .= '<li><span class="wdmauc_'.$list['slug'].' wdm_lspan '.$cls.'" data-pmt="'.$list['slug'].'">'.$list['label'].'</span>|</li>';
    }
    
    $manage_pmt .= '</ul>';
    //}
    
    $manage_payment = '';
    $manage_payment .= '<br class="clear">';
    $manage_payment .= '<div id="wdm_front_pay_settings" class="wdm_front_settings wdm_front_show wdm_all_lnk">';
    
    //$logged_user_id = wp_get_current_user();  //get user id
    //$logged_user_role = $logged_user_id->roles; //get user role
    //$cu_id = get_current_user_id();
    
    do_action('ua_update_payment_settings', $_POST, $cu_id, $logged_user_role );
    do_action('ua_payment_update_settings_paypal', $_POST );
    
    if(isset($_POST['ua_update']) && ($_POST['ua_update'] == 'update')){ 
	
     echo 'success';
	
	die();
    }
    
    if(!isset($_POST['method']) || empty($_POST['method']) || $_POST['method'] == 'paypal'){
    
    if(in_array('administrator', $logged_user_role)){
	
	$manage_payment .= '<form id="wdm-payment-form" class="ua_front_pay_form auction_settings_section_style" action="" method="POST">';
	
	$manage_payment .= '<label for="wdm_paypal_id">'.__("PayPal Email Address", "wdm-ultimate-auction").'</label>
<input class="wdm_settings_input email" type="text" id="wdm_paypal_id" name="wdm_paypal_address" value="'.get_option('wdm_paypal_address').'" /></br></br>';
		
	$manage_payment .= '<label for="wdm_account_mode_id">'.__("PayPal Account Type", "wdm-ultimate-auction").'</label> ';
		
			$options = array("Live", "Sandbox");
			add_option('wdm_account_mode','Live');
			foreach($options as $option) {
			    $checked = (get_option('wdm_account_mode')== $option) ? ' checked="checked" ' : '';
			    $manage_payment .= '<input '.$checked.' value="'.$option.'" name="wdm_account_mode" type="radio" /> '.$option.' ';
			}
			
			$manage_payment .= sprintf("<div class='ult-auc-settings-tip'>".__("Select 'Sandbox' option when testing with your %s email address.", "wdm-ultimate-auction")."</div>", "sandbox PayPal")."<br/>";
			
	    $manage_payment .= paypal_auto_return_url_notes();
	    $html = '';
	    $manage_payment .= apply_filters('ua_front_payment_register_settings_paypal', $html, $cu_id, $logged_user_role);
	    
	    $manage_payment .= '<p><input type="submit" id="wdmua-submit" class="wdm-ua-submit" value="'.__('Save Changes', "wdm-ultimate-auction").'" /></p>';
	    
	    $manage_payment .= '</form>';
	
    }
    else{

    $manage_payment .= '<form id="paypal-settings-form" class="auction_settings_section_style" method="post" action="">';
	
    $manage_payment .= '<label for="user_paypal_email_id">'.__('PayPal Email', 'wdm-ultimate-auction').'</label>';
    
    $manage_payment .= '<input name="auction_user_paypal_email" required type="text" id="user_paypal_email_id" class="regular-text" value="'. get_user_meta($cu_id, 'auction_user_paypal_email', true).'" />';
    
    $manage_payment .= paypal_auto_return_url_notes();
    
    $manage_payment .= wp_nonce_field('ua_usr_pmt_wp_n_f','ua_wdm_usr_pmt_auc');
                        
    $manage_payment .= '<p><input type="submit" id="wdmua-submit" class="wdm-ua-submit" value="'.__('Save Changes', "wdm-ultimate-auction").'" /></p>';
      
    $manage_payment .= '</form>';
     
    //require_once('paypal-validation.php');
    }
    }
    elseif(isset($_POST['method']) && ($_POST['method'] == 'wire_transfer')){

    if(in_array('administrator', $logged_user_role)){
	
    $manage_payment .= '<form id="wdm-payment-form" class="ua_front_pay_form auction_settings_section_style" action="" method="POST">';

    $manage_payment .= '<label for="wdm_wire_transfer_id">'.__("Wire Transfer Details", "wdm-ultimate-auction").'</label>';
		
    $manage_payment .= '<textarea class="wdm_settings_input" id="wdm_wire_transfer_id" name="wdm_wire_transfer">'.get_option('wdm_wire_transfer').'</textarea><br />';
	
    $manage_payment .= '<div class="ult-auc-settings-tip">'.__("Enter your wire transfer details. This will be sent to the highest bidder.", "wdm-ultimate-auction").'</div>';
    
    $manage_payment .= '<p><input type="submit" id="wdmua-submit" class="wdm-ua-submit" value="'.__('Save Changes', "wdm-ultimate-auction").'" /></p>';
    
    $manage_payment .= '</form>';
    
    }
    else{
    $manage_payment .= '<form id="wdm-payment-form" class="ua_front_pay_form auction_settings_section_style" action="" method="POST">';
    
    $manage_payment .= '<label for="wdm_wire_transfer_id">'.__("Wire Transfer Details", "wdm-ultimate-auction").'</label>';
    
    $manage_payment .= '<textarea class="wdm_settings_input" id="wdm_wire_transfer_id" name="wdm_wire_transfer">'.get_user_meta($cu_id,'wdm_wire_transfer', true).'</textarea><br />';
    
    $manage_payment .= '<div class="ult-auc-settings-tip">'.__("Enter your wire transfer details. This will be sent to the highest bidder.", "wdm-ultimate-auction").'</div>';
    
    $manage_payment .= '<p><input type="submit" id="wdmua-submit" class="wdm-ua-submit" value="'.__('Save Changes', "wdm-ultimate-auction").'" /></p>';
    
    $manage_payment .= '</form>';
    }
    }
    elseif(isset($_POST['method']) && ($_POST['method'] == 'mailing_address')){

    if(in_array('administrator', $logged_user_role)){
	
    $manage_payment .= '<form id="wdm-payment-form" class="ua_front_pay_form auction_settings_section_style" action="" method="POST">';

    $manage_payment .= '<label for="wdm_mailing_id">'.__("Mailing Address & Cheque Details", "wdm-ultimate-auction").'</label>';
		
    $manage_payment .= '<textarea class="wdm_settings_input" id="wdm_mailing_id" name="wdm_mailing_address">'.get_option('wdm_mailing_address').'</textarea>';
	
    $manage_payment .= '<div class="ult-auc-settings-tip">'.__("Enter your mailing address where you want to receive checks by mail. This will be sent to the highest bidder.", "wdm-ultimate-auction").'</div>';
		
    $manage_payment .= '<p><input type="submit" id="wdmua-submit" class="wdm-ua-submit" value="'.__('Save Changes', "wdm-ultimate-auction").'" /></p>';
    
    $manage_payment .= '</form>';
    
    }
    else{
    $manage_payment .= '<form id="wdm-payment-form" class="ua_front_pay_form auction_settings_section_style" action="" method="POST">';
    
    $manage_payment .= '<label for="wdm_mailing_id">'.__("Mailing Address", "wdm-ultimate-auction").'</label>';
    
    $manage_payment .= '<textarea class="wdm_settings_input" id="wdm_mailing_id" name="wdm_mailing_address">'.get_user_meta($cu_id,'wdm_mailing_address', true).'</textarea>';
    
    $manage_payment .= '<div class="ult-auc-settings-tip">'.__("Enter your mailing address where you want to receive checks by mail. This will be sent to the highest bidder.", "wdm-ultimate-auction").'</div>';
    
    $manage_payment .= '<p><input type="submit" id="wdmua-submit" class="wdm-ua-submit" value="'.__('Save Changes', "wdm-ultimate-auction").'" /></p>';
    
    $manage_payment .= '</form>';
    }
    }
    elseif(isset($_POST['method']) && ($_POST['method'] == 'cash')){

    if(in_array('administrator', $logged_user_role)){
	
    $manage_payment .= '<form id="wdm-payment-form" class="ua_front_pay_form auction_settings_section_style" action="" method="POST">';
	
    $manage_payment .= '<label for="wdm_cash_id">'.__("Customer Message (optional)", "wdm-ultimate-auction").'</label>';
	
    $manage_payment .= '<textarea class="wdm_settings_input" id="wdm_cash_id" name="wdm_cash">'.get_option('wdm_cash').'</textarea>';
	
    $manage_payment .= '<div class="ult-auc-settings-tip">'.__("By choosing this payment method, PRO would send a congratulatory email mentioning that final bidder should pay in cash the final bidding amount to auctioneer for the auctioned item.", "wdm-ultimate-auction").'</div>';	
    $manage_payment .= '<p><input type="submit" id="wdmua-submit" class="wdm-ua-submit" value="'.__('Save Changes', "wdm-ultimate-auction").'" /></p>';	
    $manage_payment .= '</form>';	
    }
    else{
	    $manage_payment .= '<form id="wdm-payment-form" class="ua_front_pay_form auction_settings_section_style" action="" method="POST">';
	
    $manage_payment .= '<label for="wdm_cash_id">'.__("Customer Message (optional)", "wdm-ultimate-auction").'</label>';
	
    $manage_payment .= '<textarea class="wdm_settings_input" id="wdm_cash_id" name="wdm_cash">'.get_user_meta($cu_id, 'wdm_cash', true).'</textarea>';
	
    $manage_payment .= '<div class="ult-auc-settings-tip">'.__("By choosing this payment method, PRO would send a congratulatory email mentioning that final bidder should pay in cash the final bidding amount to auctioneer for the auctioned item.", "wdm-ultimate-auction").'</div>';	
    $manage_payment .= '<p><input type="submit" id="wdmua-submit" class="wdm-ua-submit" value="'.__('Save Changes', "wdm-ultimate-auction").'" /></p>';	
    $manage_payment .= '</form>';
    }
    }
    elseif(isset($_POST['method'])){
	$html = '';
	$manage_payment .= apply_filters('ua_front_payment_register_settings', $html, $_POST['method'], $cu_id, $logged_user_role);
    }
    $manage_payment .= '</div>';
?>