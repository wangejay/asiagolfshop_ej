<?php
    $user_settings = "";
    
    $user_id = get_current_user_id();
	
    if(isset($_POST['user_wdm_setting_auc']) ){
		
		if(wp_verify_nonce($_POST['user_wdm_setting_auc'],'user_setting_wp_n_f')){
		    if(isset($_POST['payment_options_enabled'])){
			if(in_array('administrator', $logged_user_role))
			    update_option( 'payment_options_enabled', $_POST['payment_options_enabled'] );
			else
			    update_user_meta( $user_id, 'payment_options_enabled', $_POST['payment_options_enabled'] );
		    }
		}
		else{
			die(__('Sorry, your nonce did not verify.', 'wdm-ultimate-auction'));
		}
    }
	
    $default = array("method_paypal" => __("PayPal", "wdm-ultimate-auction"), "method_wire_transfer" => __("Wire Transfer", "wdm-ultimate-auction"), "method_mailing" => __("Cheque", "wdm-ultimate-auction"), "method_cash" => __("Cash", "wdm-ultimate-auction"));
	
    $options = apply_filters('ua_add_new_payment_option', $default);
    
    if(in_array('administrator', $logged_user_role))
    	add_option('payment_options_enabled', array("method_paypal" => __("PayPal", "wdm-ultimate-auction")));
    else
    	add_user_meta($user_id, 'payment_options_enabled', array("method_paypal" => __("PayPal", "wdm-ultimate-auction")));
	
    $user_settings .= '<br class="clear">';
    
    $comm_inv = get_option('wdm_manage_comm_invoice');
    $comm = false;
    
    if($comm_inv == 'Yes' && (!in_array('administrator', $logged_user_role)) ){
	$comm = true;
    }
	
    if($comm){
	$wit = "<br/><br/>What is this <a href='' class='auction_fields_tooltip'><strong>".__('?', 'wdm-ultimate-auction')."</strong><span style='width: 370px;margin-left: -90px;'>".__("When there is a commission fee active, an amount from the total amount paid by the auction winner for your auction item will be supplied to the administrator when payment is made by winner.", "wdm-ultimate-auction")."</span></a>";
	
	$user_settings .= "<div class='wdm_auc_user_notice_err'>".__("Please Note: The administrator has enabled commission fee for the auctions. So in this case, you will not be able to use any other payment method except PayPal when adding an auction.", "wdm-ultimate-auction").$wit."</div>";
    }
    
    $user_settings .= '<form id="auction-settings-form" class="auction_settings_section_style" action="" method="POST" style="width:100%;">';
    $user_settings .= '<h3>'.__("Payment Settings", "wdm-ultimate-auction").'</h3>';
    //$user_settings .= '<table class="form-table"><tr valign="top"><th scope="row">';
    $user_settings .= '<br /><label style="width:175px; float:left;margin:5px;">'.__("Payment Methods", "wdm-ultimate-auction").'</label>';
    //$user_settings .= '</th><td>';
    
    $user_settings .= '<div style="float:left;">';
    
    $values = array();
    
    if(in_array('administrator', $logged_user_role))
	$values = get_option('payment_options_enabled');
    else
	$values = get_user_meta($user_id, 'payment_options_enabled', true);
	    
    foreach($options as $key => $option) {
	    
	$checked = (array_key_exists($key, $values)) ? ' checked="checked" ' : '';
		
	$user_settings .=  "<input $checked value='$option' name='payment_options_enabled[$key]' type='checkbox' /> $option <br />";
    }
    $user_settings .= '</div>';
    
    $user_settings .=  "<br /><br />";
		
    $user_settings .= '<div style="width:100%;float:left;margin:5px;">'.__("NOTE: If you choose to activate any payment method, please go to Payment tab and enter its details. For example: if you enable Wire Transfer, go to Payment -> Wire Transfer and enter its details. Same would apply to PayPal, Cheque and Cash.", "wdm-ultimate-auction").'</div>';
		 
    //$user_settings .= '</td></tr></table>';
	    
    $user_settings .= wp_nonce_field('user_setting_wp_n_f','user_wdm_setting_auc');
    $user_settings .= '<input style="float:left;margin:5px;" id="wdmua-submit" class="wdm-ua-submit" type="submit" value="'.__("Save Changes", "wdm-ultimate-auction").'" />';
	    
    $user_settings .= '</form>';