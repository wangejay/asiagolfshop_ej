<?php
if( ! class_exists( 'WP_List_Table' ) ) {
  require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class My_Watchlist_Table extends WP_List_Table {        
    
    var $allData;
    
    function wdm_get_data(){
      
      $watch_auctions = get_user_meta(get_current_user_id(), 'wdm_watch_auctions', true);
      
      if(isset($watch_auctions)){
	
	$auction_ids = explode(" ", $watch_auctions);

	$arg_data = array(
		      'post__in' => $auction_ids,
		      'posts_per_page'=> -1,
		      'post_type' => 'ultimate-auction',
		      'post_status' => 'publish',
		      //'auction-status'=> array('live', 'expired'),
		      'order' => 'DESC'
		      );
	
	global $wpdb;
	  
	$wdm_auction_array = get_posts($arg_data);
	$data_array = array();
	
	foreach($wdm_auction_array as $auction){
	  
	    $row = array();
	    
	    $act_trm = wp_get_post_terms($auction->ID, 'auction-status', array("fields" => "names"));
	    
	    $query = "SELECT MAX(bid) FROM ".$wpdb->prefix."wdm_bidders WHERE auction_id =".$auction->ID;
	    
	    $curr_price = $wpdb->get_var($query);
	    
	    $vid_arr = array('mpg', 'mpeg', 'avi', 'mov', 'wmv', 'wma', 'mp4', '3gp', 'ogm', 'mkv', 'flv');
			  $auc_thumb = get_post_meta($auction->ID, 'wdm_auction_thumb', true);
			  $imgMime = wdm_get_mime_type($auc_thumb); 
			  $img_ext = explode(".",$auc_thumb);
			  $img_ext = end($img_ext);
			  
			  if(strpos($img_ext, '?') !== false)
			    $img_ext = strtolower(strstr($img_ext, '?', true));
			  
			  if(strstr($imgMime, "video/") || in_array($img_ext, $vid_arr) || strstr($auc_thumb, "youtube.com") || strstr($auc_thumb, "vimeo.com")){
			  $auc_thumb = plugins_url('img/film.png', __FILE__);	
	    }
		  
	    if(empty($auc_thumb)){
		$auc_thumb = plugins_url('img/no-pic.jpg', __FILE__);
	    }
	    
	    $perma_type = get_option('permalink_structure');
	    
	    if(empty($perma_type))
	      $set_char = "&";
	    else
	      $set_char = "?";
	      
	    $auc_url = get_option('wdm_auction_page_url');
	    
	    if(!empty($auc_url)){
	      $link_title = $auc_url.$set_char."ult_auc_id=".$auction->ID;
	    }
	    
	    $row['image_1']="<input class='wdm_chk_auc_act' value=".$auction->ID." type='checkbox' style='margin: 0 5px 0 0;' />"."<img src='".$auc_thumb."' width='90'";
	    
	    $row['title'] = prepare_single_auction_title($auction->ID, $auction->post_title);
	    
	    $cc = substr(get_option('wdm_currency'), -3);
	    $ob = get_post_meta($auction->ID, 'wdm_opening_bid', true);
	    $bnp = get_post_meta($auction->ID, 'wdm_buy_it_now', true);
	    
	    if(!in_array('expired',$act_trm)){
	      if((!empty($curr_price) || $curr_price > 0) && !empty($ob))
		$row['current_price'] = $cc ." ". sprintf("%.2f", $curr_price);
	      elseif(!empty($ob))
		$row['current_price'] = $cc ." ".sprintf("%.2f", $ob);
	      elseif(empty($ob) && !empty($bnp))
		$row['current_price'] = sprintf(__('Buy at %s %.2f', 'wdm-ultimate-auction'), $cc, $bnp);
	    }
	    elseif(in_array('expired',$act_trm)){
	      $bought = get_post_meta($auction->ID, 'auction_bought_status', true);
	      
	      if($bought === 'bought')
		$row['current_price'] = $cc ." ". sprintf("%.2f", $bnp);
	      elseif((!empty($curr_price) || $curr_price > 0) && !empty($ob))
		$row['current_price'] = $cc ." ". sprintf("%.2f", $curr_price);
	      elseif(!empty($ob))
		$row['current_price'] = $cc ." ".sprintf("%.2f", $ob);
	      elseif(empty($ob) && !empty($bnp))
		$row['current_price'] = $cc ." ".sprintf("%.2f", $bnp);
	    }
	    
	    $get_bids = "SELECT COUNT(bid) FROM ".$wpdb->prefix."wdm_bidders WHERE auction_id =".$auction->ID;
	    $bids_placed = $wpdb->get_var($get_bids);
	    $auc_bstat = get_post_meta($auction->ID, 'auction_bought_status', true);
	    
	    if(in_array('expired',$act_trm) && $auc_bstat === 'bought'){
	      $row['bids_placed'] ="<span class='wdm-bids-avail wdm-mark-normal'>".__("Sold at 'Buy Now' price", 'wdm-ultimate-auction')."</span>";
	    }
	    else{
	      if(!empty($bids_placed) || $bids_placed > 0)
		  $row['bids_placed'] = "<span class='wdm-bids-avail'>".$bids_placed."</span>";
	      else
		  $row['bids_placed'] = "<span class='wdm-no-bids-avail'>".__('No bids placed', 'wdm-ultimate-auction')."</span>";
	    }
	    
	    $now = time();
	    
	    $ending_date = strtotime(get_post_meta($auction->ID, 'wdm_listing_ends', true));
	    
	    $seconds = $ending_date - $now;
	    
	    if(in_array('expired',$act_trm)){
	      $seconds = $now - $ending_date;
	      $ending_tm = '';
	      $ended_at = wdm_ending_time_calculator($seconds, $ending_tm);
	      $row['end_time'] = "<span class='wdm-mark-normal'>".sprintf(__('%s ago', 'wdm-ultimate-auction'), $ended_at)."</span>";
	    }
	    elseif($seconds > 0 && !in_array('expired',$act_trm)){
	      
	      $ending_tm = '';
	      $ending_in = wdm_ending_time_calculator($seconds, $ending_tm);
	      $row['end_time'] = "<span class='wdm-mark-normal'>". $ending_in ."</span>";	
	    }
	    else{
	      $seconds = $now - $ending_date;
	      $ending_tm = '';
	      $ended_at = wdm_ending_time_calculator($seconds, $ending_tm);
	      $row['end_time'] = "<span class='wdm-mark-normal'>".sprintf(__('%s ago', 'wdm-ultimate-auction'), $ended_at)."</span>";
	    }
	    
	    if(in_array('expired',$act_trm))
	      $row['place_bid'] = '<span class="wdm-mark-red">'.__("Expired", "wdm-ultimate-auction").'</span>';
	    elseif(in_array('live',$act_trm))
	      $row['place_bid'] = '<a href="'.$link_title.'" target="_blank"><input class="wdm_bid_now_btn" type="button" value="'.__('Bid Now', 'wdm-ultimate-auction').'" /></a>';
	    
	    $row['remove'] = '<a id="wdm-rmv-frmwatch-'.$auction->ID.'" style="color:red;cursor:pointer;text-decoration:none;" href="#">'.__('Remove', 'wdm-ultimate-auction').' <span class="auc-ajax-img"></span></a>';
	    ?>
	    <script type="text/javascript">
            jQuery(document).ready(function($){
		$('#wdm-rmv-frmwatch-<?php echo $auction->ID;?>').click(function(e){
		e.preventDefault();
		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		var cnf = confirm('<?php _e("Are you sure to remove this auction from your watchlist?", "wdm-ultimate-auction");?>');
		
		if(cnf == true){
		
		$(this).html("<?php _e('Removing', 'wdm-ultimate-auction'); echo ' ';?> <img src='<?php echo plugins_url('/img/ajax-loader.gif', __FILE__);?>' />");
		
		var data = {
			      action:'rmv_frm_watchlist',
			      rmv_id:'<?php echo $auction->ID;?>',
			      usr_id:'<?php echo get_current_user_id(); ?>',
			      auc_title: '<?php echo esc_js($auction->post_title);?>',
			      force_del:'yes'
			    };
		
		$.post(ajaxurl, data, function(response) {
		    $('#wdm-rmv-frmwatch-<?php echo $auction->ID;?>').html('<?php _e("Remove", "wdm-ultimate-auction");?>');
		    alert(response);
		    window.location.reload();
		 });
		}
		return false;
	      });
	    });
            </script>
      <?php
	    $data_array[] = $row;
	  }
	  
      }
      
      $this->allData = $data_array;
      
      return $data_array;            
    }               

    function get_columns(){
    
    $columns =   array(
    'image_1' => __('Image', 'wdm-ultimate-auction'),
    'title' => __('Auction Title', 'wdm-ultimate-auction'),
    'current_price' => __('Current/Final Price', 'wdm-ultimate-auction'),
    'bids_placed' => __('Bids Placed', 'wdm-ultimate-auction'),
    'end_time'    => __('Ending Time', 'wdm-ultimate-auction'),
    'place_bid'    => '',
    'remove'	=> __('Remove From Watchlist', 'wdm-ultimate-auction')
    );
    return $columns;  
    }
    
    function get_sortable_columns(){
        $sortable_columns = array('title' => array('title',false));
        return $sortable_columns;
    }
    
    function prepare_items() {
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
        $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'title';
	
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
            case 'image_1':
            case 'title':
            case 'current_price':
            case 'bids_placed':
            case 'end_time':
            case 'place_bid':
	    case 'remove':
            return $item[ $column_name ];
            default:
            return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
        }
    }

} 

$myListTable = new My_Watchlist_Table();
$myListTable->prepare_items();
$myListTable->display();
?>