<?php
if( ! class_exists( 'WP_List_Table' ) ) {
require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
$this->bid_type = isset($_GET["bid_type"]) ? $_GET["bid_type"] : "won";

class User_Bids_List_Table extends WP_List_Table {        
    var $allData;
    var $bid_type;
         
    function wdm_get_data(){
        
        $args = array('role' => 'administrator');
        
        $all_users = get_users( $args );
        
        $all_usr = array();
        
        foreach($all_users as $au){
            $all_usr[] = "'".$au->user_login."'";
        }
        
        $usrs = implode(",", $all_usr);
    
        global $wpdb;
	
	$auct_users = get_option('wdm_usr_bids_list');
	
	if(!empty($auct_users))
	    $qry = "SELECT * FROM ".$wpdb->prefix."wdm_bidders WHERE name ='".$auct_users."' ORDER BY date DESC";
	    
	else
	    $qry = "SELECT * FROM ".$wpdb->prefix."wdm_bidders WHERE name NOT IN(".$usrs.") ORDER BY date DESC";
	    
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
	    $winner_name = $wpdb->get_var($name_qr);
	    
                $active_terms = wp_get_post_terms($bdata->auction_id, 'auction-status',array("fields" => "names"));
		
                if($bdata->name == $winner_name && $bdata->bid == $winner_bid && $winner_bid >= $reserve_pric_met && in_array('expired',$active_terms)){
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
        
        if(isset($_GET["bid_type"]) && $_GET["bid_type"]=="active")
        {
            $all_auctions = $active_bids_array;
        }
	elseif(isset($_GET["bid_type"]) && $_GET["bid_type"]=="not-won")
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
	    
	    $row['bidder'] = $single_auction->name;
	    
            $row['owner'] = $auction_post_author_data->user_login;
	    
            if(in_array('administrator', $auction_post_author_data->roles)){
                $row['paypal_email'] = get_option('wdm_paypal_address');
            }
            else{
                $row['paypal_email'] = get_user_meta( $auction_post_author, 'auction_user_paypal_email', true);
            }
            
            $row['title']=prepare_single_auction_title($single_auction->auction_id, get_the_title($single_auction->auction_id));
            $end_date = get_post_meta($single_auction->auction_id,'wdm_listing_ends', true);
            $row['date_created']= "<strong> ".__('Creation Date', 'wdm-ultimate-auction').":</strong> <br />".get_post_meta($single_auction->auction_id, 'wdm_creation_time', true)." <br /><br /> <strong> Ending Date:</strong> <br />".$end_date;
            
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
               
    function get_columns(){
    
    if($this->bid_type == "won")
    $columns =   array(
    'bidder' => __('Bidder', 'wdm-ultimate-auction'),
    'owner' => __('Auction Owner', 'wdm-ultimate-auction'),
    'paypal_email' => __("Owner's PayPal Email", "wdm-ultimate-auction"),    
    'title' => __('Auction Title', 'wdm-ultimate-auction'),
    'date_created' => __('Creation / Ending Date', 'wdm-ultimate-auction'),
    'final_price' => __('Starting / Final Price', 'wdm-ultimate-auction'),
    'payment' => __('Payment', 'wdm-ultimate-auction')
    );
    elseif($this->bid_type == "not-won")
    $columns =   array(
    'bidder' => __('Bidder', 'wdm-ultimate-auction'),
    'owner' => __('Auction Owner', 'wdm-ultimate-auction'),
    'paypal_email' => __("Owner's PayPal Email", "wdm-ultimate-auction"),    
    'title' => __('Auction Title', 'wdm-ultimate-auction'),
    'date_created' => __('Creation / Ending Date', 'wdm-ultimate-auction'),
    'final_price' => __('Starting / Final Price', 'wdm-ultimate-auction')
    );
    else
    $columns =   array(
    'bidder' => __('Bidder', 'wdm-ultimate-auction'),
    'owner' => __('Auction Owner', 'wdm-ultimate-auction'),
    'paypal_email' => __("Owner's PayPal Email", "wdm-ultimate-auction"), 
    'title' => __('Auction Title', 'wdm-ultimate-auction'),
    'date_created' => __('Creation / Ending Date', 'wdm-ultimate-auction'),
    'current_price' => __('Starting / Final Price', 'wdm-ultimate-auction'),
    'amount'    => __('Bid Amount', 'wdm-ultimate-auction')
    );
    return $columns;  
    }
    
    function get_sortable_columns(){
        $sortable_columns = array(
                        'title' => array('title',false),
			'owner' => array('owner',false),
			'bidder' => array('bidder',false)
                        );
        return $sortable_columns;
    }
    
    function prepare_items() {
    $this->bid_type = isset($_GET["bid_type"]) ? $_GET["bid_type"] : "won";
    $columns = $this->get_columns();
    $hidden = array();
    $sortable = $this->get_sortable_columns();
    $this->_column_headers = array($columns, $hidden, $sortable);
    $this->items = $this->wdm_sort_array($this->wdm_get_data());
    }
    
    function get_result_e(){
        return $this->allData;    
    }
      
    function wdm_sort_array($args){
        if(!empty($args))
        {
        $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'bidder';
	
	$order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
	
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
	    case 'bidder':
	    case 'owner':
            case 'paypal_email':
            case 'title':
            case 'date_created':
            case 'current_price':
            case 'final_price':
            case 'payment':
            case 'amount':
            return $item[ $column_name ];
            default:
            return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
        }
    }

}

if( isset( $_GET[ 'bid_type' ] ) ) {  
    $manage_bid_tab = $_GET[ 'bid_type' ];  
} 
else{
    $manage_bid_tab = 'won';
    update_option('wdm_usr_bids_list', '');
}

$user_args = array(
	'blog_id'      => $GLOBALS['blog_id'],
	'orderby'      => 'login',
	'order'        => 'ASC'
        );
        
$allUsers = get_users( $user_args );

if(isset($_POST['select-auction-users']))
    update_option('wdm_usr_bids_list', $_POST['select-auction-users']);
?>
<br>
<form id="users-bids-form" name="users-bids-form" method="post" action="">
<select id="select-auction-users" name="select-auction-users">
    <option id='all-au' value=''><?php _e('All', 'wdm-ultimate-auction');?></option>
    <?php
foreach($allUsers as $au){
    if(!in_array('administrator', $au->roles)){?>
    <option id='au-<?php echo $au->user_login;?>' value='<?php echo $au->user_login;?>' <?php if(isset($_POST['select-auction-users']) && $_POST['select-auction-users'] == $au->user_login) { echo "selected='selected'"; } elseif(get_option('wdm_usr_bids_list') == $au->user_login) { echo "selected='selected'"; }?>><?php echo $au->user_login;?></option>
<?php }
}?>
</select>
<input type="submit" value="<?php _e('Show Bids', 'wdm-ultimate-auction');?>" class="button-secondary" />
</form>
<br>
<ul class="subsubsub">
    <li><a href="?page=manage-user-bids&bid_type=won" class="<?php echo $manage_bid_tab == 'won' ? 'current' : ''; ?>"><?php _e('Bids Won', 'wdm-ultimate-auction');?></a>|</li>
    <li><a href="?page=manage-user-bids&bid_type=not-won" class="<?php echo $manage_bid_tab == 'not-won' ? 'current' : ''; ?>"><?php _e('Bids Lost', 'wdm-ultimate-auction');?></a>|</li>
    <li><a href="?page=manage-user-bids&bid_type=active" class="<?php echo $manage_bid_tab == 'active' ? 'current' : ''; ?>"><?php _e('Active Bids', 'wdm-ultimate-auction');?></a></li>
</ul>
<br class="clear">

<?php
$myListTable = new User_Bids_List_Table();
$myListTable->prepare_items();
$myListTable->display();
?>