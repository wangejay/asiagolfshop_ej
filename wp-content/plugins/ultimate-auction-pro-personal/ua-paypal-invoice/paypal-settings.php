<?php
//add_filter('ua_add_payment_header_link', 'wdm_add_paypal_header_link', 99, 1);
//
//function wdm_add_paypal_header_link($list){
//    
//    $list[] = array('slug' => 'paypal_invoice', 'label' => __('PayPal Invoice', 'wdm-ultimate-auction'));
//    
//    return $list;
//}

//add_filter('ua_add_new_payment_option', 'wdm_add_new_payment_option', 99, 1);
//
//function wdm_add_new_payment_option($options){
//    
//    $options[] = __("PayPal Invoice", "wdm-ultimate-auction");
//    
//    return $options;
//}

add_action('ua_payment_update_settings_paypal', 'wdm_update_paypal_invoice_settings', 10, 1);

function wdm_update_paypal_invoice_settings( $update ){
    
    //if($method == 'paypal_invoice'){
        
        if(isset($update['ua_paypal_api_username'])){
            update_option('ua_paypal_api_username',$update['ua_paypal_api_username']);
        }
        
        if(isset($update['ua_paypal_api_password'])){
            update_option('ua_paypal_api_password',$update['ua_paypal_api_password']);
        }
        
        if(isset($update['ua_paypal_api_signature'])){
            update_option('ua_paypal_api_signature',$update['ua_paypal_api_signature']);
        }
        
        if(isset($update['ua_paypal_api_app_id'])){
            update_option('ua_paypal_api_app_id',$update['ua_paypal_api_app_id']);
        }
        
	//require_once('paypal-validation.php');
	
        //wp_enqueue_script('wdm_paypal_valid', plugins_url('/js/paypal-validation.js', __FILE__ ), array('jquery'));
    //}
}

add_action('ua_payment_register_settings_paypal', 'wdm_add_paypal_invoice_settings', 10);

function wdm_add_paypal_invoice_settings(){
    ?>
    <br />
    <p class="clear"></p>
<?php
        $merchant_email = get_option('wdm_paypal_address');
           ?>
	    <!--<form id="paypal-settings-form" class="auction_settings_section_style" method="post" action="options.php">-->
            <?php  echo "<h3>".__("PayPal API Settings", "wdm-ultimate-auction")."</h3>"; ?>
            <table class="form-table">
            <?php if(!empty($merchant_email)) { ?>
	    <tr valign="top">
		<th scope="row">
		    <label for="pp_inv_api_username"><?php _e("API Username", "wdm-ultimate-auction"); ?></label>
		</th>
		<td>
<input class="required" type="text" id="pp_inv_api_username" name="ua_paypal_api_username" value="<?php if(!get_option('wdm_paypal_address')){echo '';}else{echo get_option('ua_paypal_api_username');}?>"  <?php if(!get_option('wdm_paypal_address')){ echo 'disabled="disabled"';} ?> />
    <!--<ul class="paypal-api-help-links" style="float: right;">-->
    <!--<li>-->
	<?php
    $pay_mode = get_option('wdm_account_mode');
	      
    if($pay_mode == 'Sandbox')
	$api_key_links = "https://sandbox.paypal.com/cgi-bin/customerprofileweb?cmd=_profile-api-access";
    else
	$api_key_links = "https://www.paypal.com/cgi-bin/customerprofileweb?cmd=_profile-api-access";
    ?>
    <a class="pp-api-a-btn" href="<?php echo $api_key_links;?>" target="_blank"><?php _e('Get Your PayPal API Keys', 'wdm-ultimate-auction');?></a>
        <a class="pp-api-a-btn" href="https://developer.paypal.com/webapps/developer/docs/classic/lifecycle/goingLive/#credentials" target="_blank"> <?php _e("Help", "wdm-ultimate-auction"); ?> </a>
	    <style type="text/css">
        a.pp-api-a-btn{
        text-decoration: none;
        color: #ffffff;
        border: 1px solid #606c88;
        padding: 4px 7px;
        border-radius:3px;
        background: #606c88; /* Old browsers */
        background: -moz-linear-gradient(top, #606c88 44%, #3f4c6b 76%); /* FF3.6+ */
        background: -webkit-gradient(linear, left top, left bottom, color-stop(44%,#606c88), color-stop(76%,#3f4c6b)); /* Chrome,Safari4+ */
        background: -webkit-linear-gradient(top, #606c88 44%,#3f4c6b 76%); /* Chrome10+,Safari5.1+ */
        background: -o-linear-gradient(top, #606c88 44%,#3f4c6b 76%); /* Opera 11.10+ */
        background: -ms-linear-gradient(top, #606c88 44%,#3f4c6b 76%); /* IE10+ */
        background: linear-gradient(to bottom, #606c88 44%,#3f4c6b 76%); /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#606c88', endColorstr='#3f4c6b',GradientType=0 ); /* IE6-9 */
       }
    </style>
    <!--</li>-->
    <!--</ul>-->
		</td>
	    </tr>
            <tr valign="top">
		<th scope="row">
		    <label for="pp_inv_api_password"><?php _e("API Password", "wdm-ultimate-auction"); ?></label>
		</th>
		<td>
<input class="required" type="text" id="pp_inv_api_password" name="ua_paypal_api_password" value="<?php if(!get_option('wdm_paypal_address')){echo '';}else{echo get_option('ua_paypal_api_password');}?>" <?php if(!get_option('wdm_paypal_address')){ echo 'disabled="disabled"';} ?>/>
		</td>
	    </tr>
            <tr valign="top">
		<th scope="row">
		    <label for="pp_inv_api_signature"><?php _e("API Signature", "wdm-ultimate-auction"); ?></label>
		</th>
		<td>
<input class="required" type="text" id="pp_inv_api_signature" name="ua_paypal_api_signature" value="<?php if(!get_option('wdm_paypal_address')){echo '';}else{echo get_option('ua_paypal_api_signature');}?>" <?php if(!get_option('wdm_paypal_address')){ echo 'disabled="disabled"';} ?> />
		</td>
	    </tr>
            <tr valign="top">
		<th scope="row">
		    <label for="pp_inv_api_app_id"><?php _e("Application ID", "wdm-ultimate-auction"); ?></label>
		</th>
		<td>
                    
    <input class="required" type="text" id="pp_inv_api_app_id" name="ua_paypal_api_app_id" value="<?php if(!get_option('wdm_paypal_address')){echo '';}else{echo get_option('ua_paypal_api_app_id');}?>" <?php if(!get_option('wdm_paypal_address')){ echo 'disabled="disabled"';} ?> />
    <a class="pp-api-a-btn" href="https://apps.paypal.com/user/my-account/applications" target="_blank"><?php _e('Get Your Application ID', 'wdm-ultimate-auction');?></a>
        <a class="pp-api-a-btn" href="https://developer.paypal.com/webapps/developer/docs/classic/lifecycle/goingLive/#register" target="_blank"><?php _e('Help', 'wdm-ultimate-auction');?></a>
		</td>
	    </tr>
            <?php }
	    else{
		echo '<tr valign="top" style="color:red;"><td>'.__("Please enter the PayPal Email Address first.", "wdm-ultimate-auction").'</td></tr>';
	    }
	    ?>
	    </table>
	    
            <?php
	    
            require_once('paypal-validation.php');
}

add_filter('ua_front_payment_register_settings_paypal', 'front_pp_api_settings', 99, 3);

function front_pp_api_settings($manage_payment, $cu_id, $logged_user_role){

$manage_payment = '';

if(in_array('administrator', $logged_user_role)){
    
    $pay_mode = get_option('wdm_account_mode');
	      
    if($pay_mode == 'Sandbox')
        $api_key_links = "https://sandbox.paypal.com/cgi-bin/customerprofileweb?cmd=_profile-api-access";
    else
	$api_key_links = "https://www.paypal.com/cgi-bin/customerprofileweb?cmd=_profile-api-access";
        
$api_key_links = '<div id="wdm_pp_api_key_help" class="paypal-api-help-links" style="float: right;">
        <a class="pp-api-a-btn" href="'.$api_key_links.'" target="_blank">'.__("Get Your PayPal API Keys", "wdm-ultimate-auction").'</a>
        <a class="pp-api-a-btn" href="https://developer.paypal.com/webapps/developer/docs/classic/lifecycle/goingLive/#credentials" target="_blank">'.__("Help", "wdm-ultimate-auction").'</a>
</div>';

	$manage_payment .= '<h3>'.__("PayPal API Settings", "wdm-ultimate-auction").'</h3><br />'.$api_key_links;
	
        $merchant_email = get_option('wdm_paypal_address');
        if(!empty($merchant_email))
        {
           
	    //$manage_payment .= '<form id="paypal-settings-form-front" class="auction_settings_section_style" method="post" action="">';
            $manage_payment .= '<label for="pp_inv_api_username">'.__('API Username', 'wdm-ultimate-auction').'</label><input type="text" id="pp_inv_api_username" name="ua_paypal_api_username" value="'.get_option('ua_paypal_api_username').'" /></br></br>';
	    $manage_payment .= '<label for="pp_inv_api_username">'.__('API Password', 'wdm-ultimate-auction').' </label><input type="text" id="pp_inv_api_password" name="ua_paypal_api_password" value="'.get_option('ua_paypal_api_password').'" /></br></br>';
            $manage_payment .= '<label for="pp_inv_api_username">'.__('API Signature', 'wdm-ultimate-auction').' </label><input type="text" id="pp_inv_api_signature" name="ua_paypal_api_signature" value="'.get_option('ua_paypal_api_signature').'" /></br></br>';
            $manage_payment .= '<label for="pp_inv_api_username">'.__('Application ID', 'wdm-ultimate-auction').' </label><input type="text" id="pp_inv_api_app_id" name="ua_paypal_api_app_id" value="'.get_option('ua_paypal_api_app_id').'" />
    <a class="pp-api-a-btn" href="https://apps.paypal.com/user/my-account/applications" target="_blank">'.__('Get Your Application ID', 'wdm-ultimate-auction').'</a>
    <a class="pp-api-a-btn" href="https://developer.paypal.com/webapps/developer/docs/classic/lifecycle/goingLive/#register" target="_blank">'.__('Help', 'wdm-ultimate-auction').'</a>';
            //$manage_payment .= wp_nonce_field('ua_pmt_wp_n_f1','ua_wdm_pmt_auc1');
            //$manage_payment .= '<p><input type="submit" id="wdmua-submit" class="wdm-ua-submit" value="'.__('Save Changes', "wdm-ultimate-auction").'" /></p>';
            //$manage_payment .= '</form>';
            
        } 
        else
        {
            $manage_payment .= __('Please enter the PayPal Email Address first.', 'wdm-ultimate-auction');
        }
}
else{
	
    $manage_payment .= '<label for="user_paypal_email_id">'.__('PayPal Email', 'wdm-ultimate-auction').'</label>';
           
    $manage_payment .= '<input name="auction_user_paypal_email" type="text" id="user_paypal_email_id" class="regular-text" value="'.get_user_meta($cu_id, 'auction_user_paypal_email', true).'"/>';
        
    //$manage_payment .= wp_nonce_field('ua_usr_pmt_wp_n_f1','ua_wdm_usr_pmt_auc1');
                
    $manage_payment .= '<p><input type="submit" id="wdmua-submit" class="wdm-ua-submit" value="'.__('Save Changes', "wdm-ultimate-auction").'" /></p>';
		
    //$manage_payment .= '</form>';
	 
}
    require_once('paypal-validation.php');
    
    return $manage_payment;
}
?>