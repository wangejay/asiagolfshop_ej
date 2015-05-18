<?php
if( ! class_exists( 'WP_List_Table' ) ) {
require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Payment_List_Table extends WP_List_Table {        
    var $payData;
    var $payment_type;
         
    function payment_get_data(){
        
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
            
            if($invoice_status !== 'Paid')
		$inv_arr[] = $auctionID;
            
            $invoice_stat = get_post_meta( $payment_auc->ID, 'auction_invoice_status', true );
            
            if($invoice_stat === 'Paid' || $invoice_stat === 'MarkedAsPaid')
                $comp_arr[] = array('auction' => $payment_auc, 'status' => $invoice_stat, 'data' => $invoice_data, 'email' => $inv_email, 'price' => $inv_price, 'currency' => $inv_curr);
                
            else
                $rem_arr[] = array('auction' => $payment_auc, 'status' => $invoice_stat,  'data' => $invoice_data, 'email' => $inv_email, 'price' => $inv_price, 'currency' => $inv_curr);  
            
            if(isset($_GET["auction"]) && $_GET["auction"] == $payment_auc->ID){
                $auc_arr = array('auction' => $payment_auc, 'status' => $invoice_stat,  'data' => $invoice_data, 'email' => $inv_email, 'price' => $inv_price, 'currency' => $inv_curr);  
                $auc_id = $payment_auc->ID;
            }
        
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
            
            if($invoice_status !== 'Paid')
		$inv_arr[] = $auctionID;
            
            $invoice_stat = get_post_meta( $payment_auc->ID, 'auction_invoice_status', true );
            
            if($invoice_stat === 'Paid' || $invoice_stat === 'MarkedAsPaid')
                $comp_arr[] = array('auction' => $payment_auc, 'status' => $invoice_stat, 'data' => $invoice_data, 'email' => $inv_email, 'price' => $inv_price, 'currency' => $inv_curr);
                
            else
                $rem_arr[] = array('auction' => $payment_auc, 'status' => $invoice_stat,  'data' => $invoice_data, 'email' => $inv_email, 'price' => $inv_price, 'currency' => $inv_curr);  
            
            if(isset($_GET["auction"]) && $_GET["auction"] == $payment_auc->ID){
                $auc_arr = array('auction' => $payment_auc, 'status' => $invoice_stat,  'data' => $invoice_data, 'email' => $inv_email, 'price' => $inv_price, 'currency' => $inv_curr);  
                $auc_id = $payment_auc->ID;
                }
            }
        }
        }
       
            if(isset($_GET["payment_type"]) && $_GET["payment_type"]=="past")
            {
                foreach($comp_arr as $ca)
                    $data_arr[] = $this->get_payment_all_list($ca['auction'], $ca['status'], $ca['data'], $ca['email'], $ca['price'], $ca['currency']);
                
                if(isset($_GET["auction"])){
                    $data_arr = array();
                    if(!empty($auc_id) && $_GET["auction"] == $auc_id)
                        $data_arr[] = $this->get_payment_all_list($auc_arr['auction'], $auc_arr['status'], $auc_arr['data'], $auc_arr['email'], $auc_arr['price'], $auc_arr['currency']);
                    }   
            }
            elseif(isset($_GET["payment_type"]) && $_GET["payment_type"]=="outstanding")
            {   foreach($rem_arr as $ra)
                    $data_arr[] = $this->get_payment_all_list($ra['auction'], $ra['status'], $ra['data'], $ra['email'], $ra['price'], $ra['currency']);
                    
                if(isset($_GET["auction"])){
                    $data_arr = array();
                    if(!empty($auc_id) && $_GET["auction"] == $auc_id)
                        $data_arr[] = $this->get_payment_all_list($auc_arr['auction'], $auc_arr['status'], $auc_arr['data'], $auc_arr['email'], $auc_arr['price'], $auc_arr['currency']);
                    }    
            }
	    
        
	$auctionID = implode(",", $inv_arr);
	require_once('ajax-actions/get-invoice-details.php');
	
        $this->payData=$data_arr;
        return $data_arr;            
    }               
        
    function get_payment_all_list($payment_auc, $invoice_stat, $invoice_data, $invoice_email, $invoice_price, $invoice_currency)
    {
            $row = array();
           
            $row['title']=$payment_auc->post_title;
            
            $row['image_1']="<img src='".get_post_meta($payment_auc->ID,'wdm_auction_thumb', true)."' width='90'";
            
            $row['inv_num']     = get_post_meta($payment_auc->ID, 'paypal_invoice_num', true);
            $invoice_date       = isset($invoice_data['invoice.invoiceDate']) ? $invoice_data['invoice.invoiceDate'] : '';
            $row['inv_date']    = substr($invoice_date, 0, 10);
            $row['bill_to']     = isset($invoice_email) ? $invoice_email : '';
            $row['amt']         = isset($invoice_currency) ? $invoice_currency : '';
            $row['amt']        .= ' ';
            $row['amt']        .= isset($invoice_price) ? $invoice_price : '';
            $row['inv_url']     = isset($invoice_data['invoiceURL']) ? '<a href="'.$invoice_data['invoiceURL'].'" target="_blank"> Visit </a>' : '';
            
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
                }
            }
            else
                $row['invoice_status'] = '';
            
            return $row;
                  
    }
    
    function get_columns(){
   
    $columns =   array(
    'image_1'   => __('Image', 'wdm-ultimate-auction'),
    'title' => __('Title', 'wdm-ultimate-auction'),
    'inv_num' => __('Invoice Number', 'wdm-ultimate-auction'),
    'inv_date' => __('Invoice Date', 'wdm-ultimate-auction'),
    'bill_to' => __('Buyer Email', 'wdm-ultimate-auction'),
    'amt' => __('Amount', 'wdm-ultimate-auction'),
    'inv_url' => __('Invoice URL', 'wdm-ultimate-auction'),
    'invoice_status'   => __('Invoice Status', 'wdm-ultimate-auction')
    );
    
    return $columns;  
    }
    
    function get_sortable_columns(){
        $sortable_columns = array(
                        'inv_num' => array('inv_num',false),
                        'title' => array('title',false),
                        'inv_date' => array('inv_date',false)
                        );
        return $sortable_columns;
    }
    
    function prepare_items() {
    if(isset($_GET["payment_type"]) && $_GET["payment_type"]=="past")
    {
        $this->payment_type = "past";
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $this->wdm_sort_array($this->payment_get_data());
    }
    elseif(isset($_GET["payment_type"]) && $_GET["payment_type"]=="outstanding")
    {
        $this->payment_type = "outstanding";
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $this->wdm_sort_array($this->payment_get_data());
    }
    }
    function get_result_e(){
        return $this->payData;    
    }
      
    function wdm_sort_array($args){
        if(!empty($args))
        {
        
        $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'inv_num';
       
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
            case 'image_1':
            case 'title':
            case 'inv_num':
            case 'inv_date':
            case 'bill_to':
            case 'amt':
            case 'inv_url':
            case 'invoice_status':    
            return $item[ $column_name ];
            default:
            return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
        }
    }

}
 
?>
<?php
$logged_user_id = wp_get_current_user();  //get user id
$logged_user_role = $logged_user_id->roles; //get user role
$cu_id = get_current_user_id();

if(in_array('administrator', $logged_user_role))
{
    $payment_tab = 'outstanding';
}
else
{
    $payment_tab = 'settings';
}
if(isset($_GET[ 'payment_type' ]) && $_GET[ 'payment_type' ] == 'past') {  
    $payment_tab = 'past';  
}
elseif(isset($_GET[ 'payment_type' ]) && $_GET[ 'payment_type' ] == 'outstanding') {  
    $payment_tab = 'outstanding';  
}
?>
<ul class="subsubsub">
    <?php if(!(in_array('administrator', $logged_user_role))) {?><li><a href="?page=payment" class="<?php echo $payment_tab == 'settings' ? 'current' : ''; ?>"><?php _e('Settings', 'wdm-ultimate-auction');?></a>|</li><?php } ?>
    <li><a href="?page=payment&payment_type=outstanding" class="<?php echo $payment_tab == 'outstanding' ? 'current' : ''; ?>"><?php _e('Outstanding Invoices', 'wdm-ultimate-auction');?></a>|</li>
    <li><a href="?page=payment&payment_type=past" class="<?php echo $payment_tab == 'past' ? 'current' : ''; ?>"><?php _e('Paid Invoices', 'wdm-ultimate-auction');?></a></li>
</ul>

<?php
if($payment_tab == 'past' || $payment_tab == 'outstanding'){
?>
<ul class="paypal-api-help-links" style="float: right;">
<li><a class="pp-ref-a-btn" href="" onclick="window.location.reload();"><?php _e('Refresh', 'wdm-ultimate-auction');?></a></li>
</ul>
<style type="text/css">
a.pp-ref-a-btn{
text-decoration: none;
color: #ffffff;
border: 1px solid #3690f0;
padding: 4px 8px;
border-radius:3px;
background: #3690f0; /* Old browsers */
background: -moz-linear-gradient(top, #3690f0 36%, #1e69de 69%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(36%,#3690f0), color-stop(69%,#1e69de)); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top, #3690f0 36%,#1e69de 69%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top, #3690f0 36%,#1e69de 69%); /* Opera 11.10+ */
background: -ms-linear-gradient(top, #3690f0 36%,#1e69de 69%); /* IE10+ */
background: linear-gradient(to bottom, #3690f0 36%,#1e69de 69%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#3690f0', endColorstr='#1e69de',GradientType=0 ); /* IE6-9 */
       }
    </style>
<?php
} 
elseif($payment_tab == 'settings') {
    
//    $pay_mode = get_option('wdm_account_mode');
//	      
//    if($pay_mode == 'Sandbox')
//	$api_key_links = "https://sandbox.paypal.com/cgi-bin/customerprofileweb?cmd=_profile-api-access";
//    else
//	$api_key_links = "https://www.paypal.com/cgi-bin/customerprofileweb?cmd=_profile-api-access";
   
    if(in_array('administrator', $logged_user_role)){?>
<!--<ul class="paypal-api-help-links" style="float: right;">-->
<!--    <li>-->
<!--        <a class="pp-api-a-btn" href="<?php //echo $api_key_links;?>" target="_blank"><?php //_e('Get Your PayPal API Keys', 'wdm-ultimate-auction');?></a>-->
<!--        <a class="pp-api-a-btn" href="https://developer.paypal.com/webapps/developer/docs/classic/lifecycle/goingLive/#credentials" target="_blank"><?php //_e('Help', 'wdm-ultimate-auction');?></a>-->
<!--    </li>-->
<!--</ul>-->
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
<?php }}?>
<br class="clear">
<?php
if($payment_tab == 'past' || $payment_tab == 'outstanding')
{
    $pmListTable = new Payment_List_Table();
    $pmListTable->prepare_items();
    $pmListTable->display();
}
elseif($payment_tab == 'settings')
{
    if(in_array('administrator', $logged_user_role)){
        $merchant_email = get_option('wdm_paypal_address');
        if(!empty($merchant_email))
        {
           ?>
	    <form id="paypal-settings-form" class="auction_settings_section_style" method="post" action="options.php">
	        <?php
		    //settings_fields('paypal_option_group');//adds all the nonce/hidden fields and verifications	
		    //do_settings_sections('paypal-setting-admin');
		    //echo wp_nonce_field('ua_pmt_wp_n_f','ua_wdm_pmt_auc');
		?>
	        <?php //submit_button(__('Save Changes', 'wdm-ultimate-auction')); ?>
	    </form>
            <?php
            
        }
        else
        {
            printf(__('Please enter your PayPal email address on Settings tab of Ultimate Auction plugin.', 'wdm-ultimate-auction'));
        }
}
else{
    if(!empty($_POST) && isset($_POST['auction_user_paypal_email'])){
        if(isset($_POST['ua_wdm_usr_pmt_auc']) && wp_verify_nonce($_POST['ua_wdm_usr_pmt_auc'],'ua_usr_pmt_wp_n_f')){
            update_user_meta( $cu_id, 'auction_user_paypal_email', $_POST['auction_user_paypal_email']);
	    echo "<div class='updated'><p><strong>".__('Settings saved.', 'wdm-ultimate-auction')."</strong></p></div>";
        }
        else
            die(__('Sorry, your nonce did not verify.', 'wdm-ultimate-auction'));
    }
    ?>
    <form id="paypal-settings-form" class="auction_settings_section_style" method="post" action="">
        <h3><?php printf(__('PayPal Settings', 'wdm-ultimate-auction'));?></h3>
	<table class="form-table">
            <tr valign="top">
            <th scope="row">
                <label for="user_paypal_email_id"><?php printf(__('PayPal Email', 'wdm-ultimate-auction'));?></label>
            </th>
            <td>
                <input name="auction_user_paypal_email" type="text" id="user_paypal_email_id" class="regular-text" value="<?php echo get_user_meta($cu_id, 'auction_user_paypal_email', true);?>"/>
            </td>
            </tr>
        </table>
	        <?php echo wp_nonce_field('ua_usr_pmt_wp_n_f','ua_wdm_usr_pmt_auc');
                           submit_button(__('Save Changes', 'wdm-ultimate-auction'));
                ?>
    </form>
        <?php
	 
}
    require_once('paypal-validation.php');
}
?>