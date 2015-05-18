<?php
    $logged_user_id = wp_get_current_user();  //get user id
    $logged_user_role = $logged_user_id->roles; //get user role
    $cu_id = get_current_user_id();
    
    //if(in_array('administrator', $logged_user_role)){
    $default = array(	array( 'slug' => 'paypal', 'label' => __('PayPal', 'wdm-ultimate-auction')),
			array( 'slug' => 'wire_transfer', 'label' => __('Wire Transfer', 'wdm-ultimate-auction')),
			array( 'slug' => 'mailing_address', 'label' => __('Cheque', 'wdm-ultimate-auction')),
			array( 'slug' => 'cash', 'label' => __('Cash', 'wdm-ultimate-auction'))
		    );
?>
<ul class="subsubsub">
    <?php
    
	$link = '';
	
	$methods = apply_filters('ua_add_payment_header_link', $default);
	
	if(isset($_GET['method'])){
	    $link = $_GET['method'];
	}
	
	foreach( $methods as $list){
	    if(empty($link)){
		$link = 'paypal';
	    }
	    
	    ?>
	    <li><a href="?page=payment&method=<?php echo $list['slug'];?>" class="<?php echo $link == $list['slug'] ? 'current' : ''; ?>"><?php echo $list['label'];?></a>|</li>
	    <?php
	}
    ?>
</ul>
<br class="clear">
    
<?php
    //}
    //$logged_user_id = wp_get_current_user();  //get user id
    //$logged_user_role = $logged_user_id->roles; //get user role
    //$cu_id = get_current_user_id();
    
    do_action('ua_update_payment_settings', $_POST, $cu_id, $logged_user_role );
    do_action('ua_payment_update_settings_paypal', $_POST );
    
    if(!isset($_GET['method']) || empty($_GET['method']) || $_GET['method'] == 'paypal'){
    
    if(in_array('administrator', $logged_user_role)){
	
	?>
	<form id="wdm-payment-form" class="auction_settings_section_style" action="" method="POST">
	    <?php  echo "<h3>".__("PayPal", "wdm-ultimate-auction")."</h3>"; ?>
	    <table class="form-table">
	    <tr valign="top">
		<th scope="row">
		    <label for="wdm_paypal_id"><?php _e("PayPal Email Address", "wdm-ultimate-auction"); ?></label>
		</th>
		<td>
		    <input class="wdm_settings_input email" type="text" id="wdm_paypal_id" name="wdm_paypal_address" value="<?php echo get_option('wdm_paypal_address');?>" />
		    <?php echo paypal_auto_return_url_notes(); ?>
		</td>
	    </tr>
	    <tr valign="top">
		<th scope="row">
		    <label for="wdm_account_mode_id"><?php _e("PayPal Account Type", "wdm-ultimate-auction"); ?></label>
		</th>
		<td>
		    <?php
			$options = array("Live", "Sandbox");
			add_option('wdm_account_mode','Live');
			foreach($options as $option) {
			    $checked = (get_option('wdm_account_mode')== $option) ? ' checked="checked" ' : '';
			    echo "<input ".$checked." value='$option' name='wdm_account_mode' type='radio' /> $option <br />";
			}
			printf("<div class='ult-auc-settings-tip'>".__("Select 'Sandbox' option when testing with your %s email address.", "wdm-ultimate-auction")."</div>", "sandbox PayPal");?>
		</td>
	    </tr>
	    </table>
	    <?php
	    do_action('ua_payment_register_settings_paypal');
	    submit_button(__('Save Changes', 'wdm-ultimate-auction')); ?>
	</form>
	<?php
    }
    else{

    ?>
    <form id="paypal-settings-form" class="auction_settings_section_style" method="post" action="">
        <h3><?php printf(__('PayPal Settings', 'wdm-ultimate-auction'));?></h3>
	<table class="form-table">
            <tr valign="top">
            <th scope="row">
                <label for="user_paypal_email_id"><?php printf(__('PayPal Email', 'wdm-ultimate-auction'));?></label>
            </th>
            <td>
                <input name="auction_user_paypal_email" required type="text" id="user_paypal_email_id" class="regular-text" value="<?php echo get_user_meta($cu_id, 'auction_user_paypal_email', true);?>"/>
		<?php echo paypal_auto_return_url_notes(); ?>
            </td>
            </tr>
        </table>
	        <?php echo wp_nonce_field('ua_usr_pmt_wp_n_f','ua_wdm_usr_pmt_auc');
                           submit_button(__('Save Changes', 'wdm-ultimate-auction'));
                ?>
    </form>
        <?php
	 
    //require_once('paypal-validation.php');
    }
    }
    elseif(isset($_GET['method']) && ($_GET['method'] == 'wire_transfer')){

    if(in_array('administrator', $logged_user_role)){
	?>
	<form id="wdm-payment-form" class="auction_settings_section_style" action="" method="POST">
	    <?php  echo "<h3>".__("Wire Transfer", "wdm-ultimate-auction")."</h3>"; ?>
	    <table class="form-table">
	    <tr valign="top">
		<th scope="row">
		    <label for="wdm_wire_transfer_id"><?php _e("Wire Transfer Details", "wdm-ultimate-auction"); ?></label>
		</th>
		<td>
		    <textarea class="wdm_settings_input" id="wdm_wire_transfer_id" name="wdm_wire_transfer"><?php echo get_option('wdm_wire_transfer');?></textarea>
    <br />
    <div class="ult-auc-settings-tip"><?php _e("Enter your wire transfer details. This will be sent to the highest bidder.", "wdm-ultimate-auction");?></div>
		</td>
	    </tr>
	    </table>
	    <?php submit_button(__('Save Changes', 'wdm-ultimate-auction')); ?>
	</form>
	<?php
    }
    else{
		?>
	<form id="wdm-payment-form" class="auction_settings_section_style" action="" method="POST">
	    <?php  echo "<h3>".__("Wire Transfer", "wdm-ultimate-auction")."</h3>"; ?>
	    <table class="form-table">
	    <tr valign="top">
		<th scope="row">
		    <label for="wdm_wire_transfer_id"><?php _e("Wire Transfer Details", "wdm-ultimate-auction"); ?></label>
		</th>
		<td>
		    <textarea class="wdm_settings_input" id="wdm_wire_transfer_id" name="wdm_wire_transfer"><?php echo get_user_meta($cu_id,'wdm_wire_transfer', true);?></textarea>
    <br />
    <div class="ult-auc-settings-tip"><?php _e("Enter your wire transfer details. This will be sent to the highest bidder.", "wdm-ultimate-auction");?></div>
		</td>
	    </tr>
	    </table>
	    <?php submit_button(__('Save Changes', 'wdm-ultimate-auction')); ?>
	</form>
	<?php
    }
    }
    elseif(isset($_GET['method']) && ($_GET['method'] == 'mailing_address')){

    if(in_array('administrator', $logged_user_role)){
	?>
	<form id="wdm-payment-form" class="auction_settings_section_style" action="" method="POST">
	    <?php  echo "<h3>".__("Cheque", "wdm-ultimate-auction")."</h3>"; ?>
	    <table class="form-table">
	    <tr valign="top">
		<th scope="row">
		    <label for="wdm_mailing_id"><?php _e("Mailing Address & Cheque Details", "wdm-ultimate-auction"); ?></label>
		</th>
		<td>
        <textarea class="wdm_settings_input" id="wdm_mailing_id" name="wdm_mailing_address"><?php echo get_option('wdm_mailing_address');?></textarea>
    <div class="ult-auc-settings-tip"><?php _e("Enter your mailing address where you want to receive checks by mail. This will be sent to the highest bidder.", "wdm-ultimate-auction");?></div>
		</td>
	    </tr>
	    </table>
	    <?php submit_button(__('Save Changes', 'wdm-ultimate-auction')); ?>
	</form>
	<?php
    }
    else{
	    ?>
	<form id="wdm-payment-form" class="auction_settings_section_style" action="" method="POST">
	    <?php  echo "<h3>".__("Mailing Address", "wdm-ultimate-auction")."</h3>"; ?>
	    <table class="form-table">
	    <tr valign="top">
		<th scope="row">
		    <label for="wdm_mailing_id"><?php _e("Mailing Address", "wdm-ultimate-auction"); ?></label>
		</th>
		<td>
        <textarea class="wdm_settings_input" id="wdm_mailing_id" name="wdm_mailing_address"><?php echo get_user_meta($cu_id, 'wdm_mailing_address', true);?></textarea>
    <div class="ult-auc-settings-tip"><?php _e("Enter your mailing address where you want to receive checks by mail. This will be sent to the highest bidder.", "wdm-ultimate-auction");?></div>
		</td>
	    </tr>
	    </table>
	    <?php submit_button(__('Save Changes', 'wdm-ultimate-auction')); ?>
	</form>
	<?php
    }
    }
    elseif(isset($_GET['method']) && ($_GET['method'] == 'cash')){
	
    if(in_array('administrator', $logged_user_role)){
	
	?>
	<form id="wdm-payment-form" class="auction_settings_section_style" action="" method="POST">
	
	    <?php  echo "<h3>".__("Cash", "wdm-ultimate-auction")."</h3>"; ?>
	
	    <table class="form-table">
	
	    <tr valign="top">
	
		<th scope="row">
	
		    <label for="wdm_cash_id"><?php _e("Customer Message (optional)", "wdm-ultimate-auction"); ?></label>	
		</th>
	
		<td>	
     <textarea class="wdm_settings_input" id="wdm_cash_id" name="wdm_cash"><?php echo get_option('wdm_cash');?></textarea>	
 <div class="ult-auc-settings-tip"><?php _e("By choosing this payment method, PRO would send a congratulatory email mentioning that final bidder should pay in cash the final bidding amount to auctioneer for the auctioned item.", "wdm-ultimate-auction");?></div>	
		</td>
	    </tr>
	    </table>
	    <?php submit_button(__('Save Changes', 'wdm-ultimate-auction')); ?>
	</form>	
	<?php	
    }
    else{
	    ?>
	<form id="wdm-payment-form" class="auction_settings_section_style" action="" method="POST">
	    <?php  echo "<h3>".__("Cash", "wdm-ultimate-auction")."</h3>"; ?>
	    <table class="form-table">
	    <tr valign="top">
		<th scope="row">
		    <label for="wdm_cash_id"><?php _e("Cash", "wdm-ultimate-auction"); ?></label>
		</th>
		<td>
        <textarea class="wdm_settings_input" id="wdm_cash_id" name="wdm_cash"><?php echo get_user_meta($cu_id, 'wdm_cash', true);?></textarea>
    <div class="ult-auc-settings-tip"><?php _e("By choosing this payment method, PRO would send a congratulatory email mentioning that final bidder should pay in cash the final bidding amount to auctioneer for the auctioned item.", "wdm-ultimate-auction");?></div>
		</td>
	    </tr>
	    </table>
	    <?php submit_button(__('Save Changes', 'wdm-ultimate-auction')); ?>
	</form>
	<?php
    }
    }
    elseif(isset($_GET['method'])){
	do_action('ua_payment_register_settings', $_GET['method'], $cu_id, $logged_user_role);
    }
?>