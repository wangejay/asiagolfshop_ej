<?php
$manage_payment = '';
$manage_payment .= '<div id="wdm_front_pay_settings" class="wdm_front_settings wdm_front_show wdm_all_lnk">';

if(in_array('administrator', $logged_user_role)){
    
    if(isset($_POST['ua_wdm_pmt_auc1'])){
        if(wp_verify_nonce($_POST['ua_wdm_pmt_auc1'],'ua_pmt_wp_n_f1')){  	
	update_option('ua_paypal_api_username',$_POST['ua_paypal_api_username']);
	update_option('ua_paypal_api_password',$_POST['ua_paypal_api_password']);
	update_option('ua_paypal_api_signature',$_POST['ua_paypal_api_signature']);
	update_option('ua_paypal_api_app_id',$_POST['ua_paypal_api_app_id']);
	$manage_payment .= "<div class='wdm_auc_user_notice_suc'>".__('Settings saved.', 'wdm-ultimate-auction')."</div>";
    }
    else{
	die(__('Sorry, your nonce did not verify.', 'wdm-ultimate-auction'));
    }
    }
    
        $merchant_email = get_option('wdm_paypal_address');
        if(!empty($merchant_email))
        {
           
	    $manage_payment .= '<form id="paypal-settings-form-front" class="auction_settings_section_style" method="post" action="">';
            $manage_payment .= '<label for="pp_inv_api_username">API Username </label><input type="text" id="pp_inv_api_username" name="ua_paypal_api_username" value="'.get_option('ua_paypal_api_username').'" /></br></br>';
	    $manage_payment .= '<label for="pp_inv_api_username">API Password </label><input type="text" id="pp_inv_api_password" name="ua_paypal_api_password" value="'.get_option('ua_paypal_api_password').'" /></br></br>';
            $manage_payment .= '<label for="pp_inv_api_username">API Signature </label><input type="text" id="pp_inv_api_signature" name="ua_paypal_api_signature" value="'.get_option('ua_paypal_api_signature').'" /></br></br>';
            $manage_payment .= '<label for="pp_inv_api_username">Application ID </label><input type="text" id="pp_inv_api_app_id" name="ua_paypal_api_app_id" value="'.get_option('ua_paypal_api_app_id').'" />
    <a class="pp-api-a-btn" href="https://apps.paypal.com/user/my-account/applications" target="_blank">'.__('Get Your Application ID', 'wdm-ultimate-auction').'</a>
    <a class="pp-api-a-btn" href="https://developer.paypal.com/webapps/developer/docs/classic/lifecycle/goingLive/#register" target="_blank">'.__('Help', 'wdm-ultimate-auction').'</a>';
            $manage_payment .= wp_nonce_field('ua_pmt_wp_n_f1','ua_wdm_pmt_auc1');
            $manage_payment .= '<p><input type="submit" id="wdmua-submit" class="wdm-ua-submit" value="'.__('Save Changes', "wdm-ultimate-auction").'" /></p>';
            $manage_payment .= '</form>';
            
        } 
        else
        {
            printf(__('Please enter your PayPal email address on Settings tab of Ultimate Auction plugin.', 'wdm-ultimate-auction'));
        }
}
else{
    if(!empty($_POST) && isset($_POST['auction_user_paypal_email'])){
        if(isset($_POST['ua_wdm_usr_pmt_auc1']) && wp_verify_nonce($_POST['ua_wdm_usr_pmt_auc1'],'ua_usr_pmt_wp_n_f1')){
            update_user_meta( $cu_id, 'auction_user_paypal_email', $_POST['auction_user_paypal_email']);
	    $manage_payment .= "<div class='wdm_auc_user_notice_suc'>".__('Settings saved.', 'wdm-ultimate-auction')."</div>";
        }
        else
            die(__('Sorry, your nonce did not verify.', 'wdm-ultimate-auction'));
    }
   
    $manage_payment .= '<form id="paypal-settings-form-front" class="auction_settings_section_style" method="post" action="">
	
                <label for="user_paypal_email_id">'.__('PayPal Email', 'wdm-ultimate-auction').'</label>
           
                <input name="auction_user_paypal_email" type="text" id="user_paypal_email_id" class="regular-text" value="'.get_user_meta($cu_id, 'auction_user_paypal_email', true).'"/>
        
	        '.wp_nonce_field('ua_usr_pmt_wp_n_f1','ua_wdm_usr_pmt_auc1').'
                
                <p><input type="submit" id="wdmua-submit" class="wdm-ua-submit" value="'.__('Save Changes', "wdm-ultimate-auction").'" /></p>
    </form>';
	 
}
    require_once('paypal-validation.php');
    
    $manage_payment .= '</div>';
?>