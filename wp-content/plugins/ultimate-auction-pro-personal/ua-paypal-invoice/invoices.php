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
	$inv_arr1 = array();
	
        foreach($payment_items as $payment_auc){
            
	    $user = wp_get_current_user();
            $invoice_data = array();
	    
            if(in_array('administrator', $user->roles)){
            
            $invoice_data   = get_post_meta( $payment_auc->ID, 'paypal_invoice_data', true );
            if(is_serialized($invoice_data))
                $invoice_data   = unserialize($invoice_data);
                
            $inv_email = get_post_meta( $payment_auc->ID, 'invoice_reciever_email', true );
	    $inv_price = get_post_meta( $payment_auc->ID, 'invoice_reciever_bid_price', true );
            $inv_curr = get_post_meta( $payment_auc->ID, 'invoice_reciever_currency', true );
            
            $invoice_status     = get_post_meta( $payment_auc->ID, 'auction_invoice_status', true );
            $auctionID = $payment_auc->ID;
            
            if($invoice_status !== 'Paid'){
		
		$pay_mthd = get_post_meta( $payment_auc->ID, 'auction_active_pay_method', true );
		
		if($pay_mthd == 'adaptive')
		    $inv_arr1[] = $auctionID;
		else
		    $inv_arr[] = $auctionID;
            }
            
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
            
            if($invoice_status !== 'Paid'){
		
		$pay_mthd = get_post_meta( $payment_auc->ID, 'auction_active_pay_method', true );
		
		if($pay_mthd == 'adaptive')
		    $inv_arr1[] = $auctionID;
		else
		    $inv_arr[] = $auctionID;
	    }
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
            else//if(isset($_GET["payment_type"]) && $_GET["payment_type"]=="outstanding")
            {   foreach($rem_arr as $ra)
                    $data_arr[] = $this->get_payment_all_list($ra['auction'], $ra['status'], $ra['data'], $ra['email'], $ra['price'], $ra['currency']);
                    
                if(isset($_GET["auction"])){
                    $data_arr = array();
                    if(!empty($auc_id) && $_GET["auction"] == $auc_id)
                        $data_arr[] = $this->get_payment_all_list($auc_arr['auction'], $auc_arr['status'], $auc_arr['data'], $auc_arr['email'], $auc_arr['price'], $auc_arr['currency']);
                    }    
            }
	    
        
	$auctionID = implode(",", $inv_arr);
	$auctionID1 = implode(",", $inv_arr1);
	
	require_once('ajax-actions/get-invoice-details.php');
	
	require_once('ajax-actions/multi-delete-invoice.php');
	
        $this->payData=$data_arr;
        return $data_arr;            
    }               
        
    function get_payment_all_list($payment_auc, $invoice_stat, $invoice_data, $invoice_email, $invoice_price, $invoice_currency)
    {
            $row = array();
           
            $row['title']=$payment_auc->post_title;
            
            $row['image_1']="<input class='wdm_chk_auc_act' value=".$payment_auc->ID." type='checkbox' style='margin: 0 5px 0 0;' /><img src='".get_post_meta($payment_auc->ID,'wdm_auction_thumb', true)."' width='90'";
            
            $inv_num     	= get_post_meta($payment_auc->ID, 'paypal_invoice_num', true);
            $row['inv_num']     = !empty($inv_num) ? $inv_num : '';
	    
	    if(is_array($invoice_data) && array_key_exists('invoice.invoiceDate', $invoice_data))
		$invoice_date   = !empty($invoice_data['invoice.invoiceDate']) ? $invoice_data['invoice.invoiceDate'] : '';
	    else{
		$invoice_date 	= get_post_meta($payment_auc->ID, 'paypal_trans_timestamp', true);
		$invoice_date 	= !empty($invoice_date) ? $invoice_date: '';
	    }
	    //print_r($invoice_data);
            $row['inv_date']    = substr($invoice_date, 0, 10);
            $row['bill_to']     = isset($invoice_email) ? "<a href='mailto:".$invoice_email."'>".$invoice_email."</a>" : '';
            $row['amt']         = isset($invoice_currency) ? $invoice_currency : '';
            $row['amt']        .= ' ';
            $row['amt']        .= isset($invoice_price) ? $invoice_price : '';
	    $inv_url     	= get_post_meta($payment_auc->ID, 'paypal_invoice_url', true);
            $row['inv_url']     = !empty($inv_url) ? '<a href="'.$inv_url.'" target="_blank"> Visit </a>' : '';
            
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
		    
		    if(strtoupper($invoice_stat) === 'EXPIRED'){
			//update_post_meta( $payment_auc->ID, 'auction_email_sent', 'resend' );
			
			$bidder_email = get_post_meta( $payment_auc->ID, 'invoice_reciever_email', true);
			$bid_price    = get_post_meta( $payment_auc->ID, 'invoice_reciever_bid_price', true);
                     
                    $row['invoice_status'] .= '<br /><br /> <a href="" id="inv-resend-email-'.$payment_auc->ID.'">'.__('Resend', 'wdm-ultimate-auction').'</a>';
                    require('ajax-actions/inv-resend-email.php');
		    }
                }
		
		$row['invoice_status'] .= '<br /><br />';
            }
            else
                $row['invoice_status'] = '';
		
		$row['invoice_status'] .= "<div id='wdm-delete-invoice-".$payment_auc->ID."' style='color:red;cursor:pointer;'>".__('Delete', 'wdm-ultimate-auction')." <span class='auc-ajax-img'></span></div>";	
			
		require('ajax-actions/delete-invoice.php');
            
            return $row;
                  
    }
    
    function get_columns(){
   
    $columns =   array(
    'image_1'   => '<input class="wdm_select_all_chk" type="checkbox" style="margin: 0 5px 0 0;" />'.__('Image', 'wdm-ultimate-auction'),
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
    else//if(isset($_GET["payment_type"]) && $_GET["payment_type"]=="outstanding")
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

//if(in_array('administrator', $logged_user_role))
//{
//    $payment_tab = 'outstanding';
//}
//else
//{
//    $payment_tab = 'settings';
//}
if(isset($_GET[ 'payment_type' ]) && $_GET[ 'payment_type' ] == 'past') {  
    $payment_tab = 'past';  
}
//elseif(isset($_GET[ 'payment_type' ]) && $_GET[ 'payment_type' ] == 'outstanding') {
else{
    $payment_tab = 'outstanding';  
}
?>
<ul class="subsubsub">
    <?php //if(!(in_array('administrator', $logged_user_role))) {?>
    <!--<li><a href="?page=invoices" class="<?php echo $payment_tab == 'settings' ? 'current' : ''; ?>"><?php _e('Settings', 'wdm-ultimate-auction');?></a>|</li>-->
    <?php
    //} ?>
    <li><a href="?page=invoices&payment_type=outstanding" class="<?php echo $payment_tab == 'outstanding' ? 'current' : ''; ?>"><?php _e('Outstanding Invoices', 'wdm-ultimate-auction');?></a>|</li>
    <li><a href="?page=invoices&payment_type=past" class="<?php echo $payment_tab == 'past' ? 'current' : ''; ?>"><?php _e('Paid Invoices', 'wdm-ultimate-auction');?></a></li>
</ul>

<?php
//if($payment_tab == 'past' || $payment_tab == 'outstanding'){
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
//} 
?>

<br class="clear">
<?php
//if($payment_tab == 'past' || $payment_tab == 'outstanding')
//{
    ?>
<br class="clear">	
<div style="float:left;">	
    <select id="wdmua_del_all" style="float:left;margin-right: 10px;"><option value="del_all_wdm"><?php _e("Delete", "wdm-ultimate-auction");?></option></select>
    <input type="button" id="wdm_mult_chk_del" class="wdm_ua_act_links button-secondary" value="<?php _e("Apply", "wdm-ultimate-auction");?>" />
    <span class="wdmua_del_stats"></span>
</div>
    
<?php
    $pmListTable = new Payment_List_Table();
    $pmListTable->prepare_items();
    $pmListTable->display();
//}
?>