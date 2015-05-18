<?php
$page_num = get_option('wdm_auc_num_per_page');
$page_num = (!empty($page_num) && $page_num > 0) ? $page_num : 20;

$wdm_auction_array = array();
$ua_auction_array = array();

if (get_query_var('paged')) { $paged = get_query_var('paged'); }
elseif (get_query_var('page')) { $paged = get_query_var('page'); }
else { $paged = 1; }

$args = array(
		'posts_per_page'=> -1,
		'post_type'	=> 'ultimate-auction',
		'auction-status'  => 'live',
		'post_status' => 'publish'
		);

	$ua_auction_array = get_posts($args);
	
	global $wpdb;
	
	foreach($ua_auction_array as $ua_arr){
		$bid_qry = "SELECT COUNT(bid) FROM ".$wpdb->prefix."wdm_bidders WHERE auction_id =".$ua_arr->ID;
		$tot_bids = $wpdb->get_var($bid_qry);
		
		if($tot_bids > 0)
		    update_post_meta($ua_arr->ID, 'total_bids_count', $tot_bids);
		else
		    delete_post_meta($ua_arr->ID, 'total_bids_count');
		    
		
		$curr_time = time();
		$ending_time = strtotime(get_post_meta($ua_arr->ID, 'wdm_listing_ends', true));
		
		$tm_diff = $ending_time - $curr_time;
		
		if($tm_diff <= (60*60*24*7))
		    update_post_meta($ua_arr->ID, 'wdm_ending_soon', $ending_time);
		else
		    delete_post_meta($ua_arr->ID, 'wdm_ending_soon');
		    
	}
	
if(isset($_GET['listing']) && $_GET['listing'] == 'ending'){
$arg_data = array(
		'posts_per_page'=> $page_num,
		'post_type'	=> 'ultimate-auction',
		'auction-status'  => 'live',
		'post_status' => 'publish',
		'meta_key' => 'wdm_ending_soon',
		'orderby' => 'wdm_ending_soon',
		'order' => 'ASC',
		'paged' => $paged,
		'suppress_filters' => false
		);

		$arg_data_c = array(
		'posts_per_page'=> -1,
		'post_type'	=> 'ultimate-auction',
		'auction-status'  => 'live',
		'post_status' => 'publish',
		'meta_key' => 'wdm_ending_soon',
		'orderby' => 'wdm_ending_soon',
		'order' => 'ASC',
		'paged' => $paged,
		'suppress_filters' => false
		);
?>
<script type="text/javascript">
	jQuery(document).ready(
			       function(){
				jQuery("#ua-auctions-ending-soon").addClass("ua-active-tab");
			       }
			       );
</script>
<?php
}
elseif(isset($_GET['listing']) && $_GET['listing'] == 'active'){		
$arg_data = array(
		'posts_per_page'=> $page_num,
		'post_type'	=> 'ultimate-auction',
		'auction-status'  => 'live',
		'post_status' => 'publish',
		'meta_key' => 'total_bids_count',
		'orderby' => 'total_bids_count',
		'order' => 'DESC',
		'paged' => $paged,
		'suppress_filters' => false
		);

		$arg_data_c = array(
		'posts_per_page'=> -1,
		'post_type'	=> 'ultimate-auction',
		'auction-status'  => 'live',
		'post_status' => 'publish',
		'meta_key' => 'total_bids_count',
		'orderby' => 'total_bids_count',
		'order' => 'DESC',
		'paged' => $paged,
		'suppress_filters' => false
		);
?>
<script type="text/javascript">
	jQuery(document).ready(
			       function(){
				jQuery("#ua-auctions-most-active").addClass("ua-active-tab");
			       }
			       );
</script>
<?php
}
elseif(isset($_GET['listing']) && $_GET['listing'] == 'new'){
$arg_data = array(
		'posts_per_page'=> $page_num,
		'post_type'	=> 'ultimate-auction',
		'auction-status'  => 'live',
		'post_status' => 'publish',
		'orderby' => 'date',
		'order' => 'DESC',
		'paged' => $paged,
		'suppress_filters' => false
		);

		$arg_data_c = array(
		'posts_per_page'=> -1,
		'post_type'	=> 'ultimate-auction',
		'auction-status'  => 'live',
		'post_status' => 'publish',
		'orderby' => 'date',
		'order' => 'DESC',
		'paged' => $paged,
		'suppress_filters' => false
		);
		
     add_filter( 'posts_where', 'wdm_filter_where' ); 
?>
<script type="text/javascript">
	jQuery(document).ready(
			       function(){
				jQuery("#ua-auctions-new-listings").addClass("ua-active-tab");
			       }
			       );
</script>
<?php
}
elseif(isset($_GET['listing']) && $_GET['listing'] == 'sold'){
$arg_data = array(
		'posts_per_page'=> $page_num,
		'post_type'	=> 'ultimate-auction',
		'auction-status'  => 'expired',
		'post_status' => 'publish',
		'meta_key' => 'wdm_listing_ends',
		'orderby' => 'wdm_listing_ends',
		'order' => 'DESC',
		'paged' => $paged,
		'suppress_filters' => false
		);
$arg_data_c = array(
		'posts_per_page'=> -1,
		'post_type'	=> 'ultimate-auction',
		'auction-status'  => 'expired',
		'post_status' => 'publish',
		'meta_key' => 'wdm_listing_ends',
		'orderby' => 'wdm_listing_ends',
		'order' => 'DESC',
		'paged' => $paged,
		'suppress_filters' => false
		);
     add_filter( 'posts_where', 'wdm_filter_where_exp' );
?>
<script type="text/javascript">
	jQuery(document).ready(
			       function(){
				jQuery("#ua-auctions-just-sold").addClass("ua-active-tab");
			       }
			       );
</script>
<?php
}
else{
$arg_data = array(
		'posts_per_page'=> $page_num,
		'post_type'	=> 'ultimate-auction',
		'auction-status'  => 'live',
		'post_status' => 'publish',
		'orderby' => 'date',
		'order' => 'DESC',
		'paged' => $paged,
		'suppress_filters' => false
		);

		$arg_data_c = array(
		'posts_per_page'=> -1,
		'post_type'	=> 'ultimate-auction',
		'auction-status'  => 'live',
		'post_status' => 'publish',
		'orderby' => 'date',
		'order' => 'DESC',
		'paged' => $paged,
		'suppress_filters' => false
		);
?>
<script type="text/javascript">
	jQuery(document).ready(
			       function(){
				jQuery("#ua-auctions-all-listings").addClass("ua-active-tab");
			       }
			       );
</script>
<?php
}
	  
	  do_action('wdm_ua_before_get_auctions');
	  
	  $wdm_auction_array = get_posts($arg_data);
	  
	  $count_pages = count(get_posts($arg_data_c));
	  
	  do_action('wdm_ua_after_get_auctions');
	  
	  remove_filter( 'posts_where', 'wdm_filter_where_exp' );
	  
	  remove_filter( 'posts_where', 'wdm_filter_where' );
	  
	  $listing_heading = get_option('wdm_listing_heading');
	  
	  if(!empty($listing_heading)){
	       echo '<div class="wdm-ua-feeder-heading">'.$listing_heading.'</div>';
	  }
	   if( !isset( $wdm_cat_args['ua_cat'] ) &&  !isset( $wdm_cat_args['ua_scat'] ) ){
                $show_content = '';
                $show_content = apply_filters('wdm_ua_before_auctions_listing', $show_content);
                echo $show_content;
          }

		
		?>
		<div class="wdm-auction-listing-container">
		
		<?php 
		$show_ext_html = '';
		$show_ext_html = apply_filters('wdm_ua_add_html_feeder', $show_ext_html);
		echo $show_ext_html;
		
		$base_url = get_permalink();
		$base_url = apply_filters('wdm_modify_auctions_url', $base_url);
		?>
		
		<ul id="wdm-auction-sorting-tabs">
		        <li class="auc_single_list" id="ua-auctions-all-listings"><a href="<?php echo  add_query_arg('listing', 'all', $base_url);?>"><?php _e('Live Auctions', 'wdm-ultimate-auction');?></a></li>
			<li class="auc_single_list" id="ua-auctions-new-listings"><a href="<?php echo  add_query_arg('listing', 'new', $base_url);?>"><?php _e('New Listings', 'wdm-ultimate-auction');?></a></li>
			<li class="auc_single_list" id="ua-auctions-most-active"><a href="<?php echo  add_query_arg('listing', 'active', $base_url);?>"><?php _e('Most Active', 'wdm-ultimate-auction');?></a></li>
			<li class="auc_single_list" id="ua-auctions-ending-soon"><a href="<?php echo  add_query_arg('listing', 'ending', $base_url);?>"><?php _e('Ending Soon', 'wdm-ultimate-auction');?></a></li>
			<li class="auc_single_list" id="ua-auctions-just-sold"><a href="<?php echo  add_query_arg('listing', 'sold', $base_url);?>"><?php _e('Just Sold', 'wdm-ultimate-auction');?></a></li>
		</ul>
			<ul class="wdm_auctions_list">
			<li class="auction-list-menus">
				<ul class="wdm_all_li_content_auc">
					<li class="wdm-apn auc_single_list"><strong><?php _e('Product', 'wdm-ultimate-auction');?></strong></li>
					<li class="wdm-apt auc_single_list"><strong></strong></li>
					<li class="wdm-app auc_single_list"><strong><?php echo (isset($_GET['listing']) && $_GET['listing'] == 'sold') ? __('Final Price', 'wdm-ultimate-auction') : __('Current Price', 'wdm-ultimate-auction') ;?></strong></li>
					<li class="wdm-apb auc_single_list"><strong><?php _e('Bids Placed', 'wdm-ultimate-auction');?></strong></li>
					<li class="wdm-ape auc_single_list"><strong><?php echo (isset($_GET['listing']) && $_GET['listing'] == 'sold') ? __('Ended at', 'wdm-ultimate-auction') : __('Ending', 'wdm-ultimate-auction');?></strong></li>
				</ul>
			</li>
			
		<?php
		//auction listing page container
		$no_entry = "";
		if(empty($wdm_auction_array)){
		    if(isset($_GET['listing']) && $_GET['listing'] == 'active')
			 $no_entry = __('Start bidding now.','wdm-ultimate-auction');
		    elseif(isset($_GET['listing']) && $_GET['listing'] == 'ending')
			 $no_entry = __('Still some days left for expiration.','wdm-ultimate-auction');
		    elseif(isset($_GET['listing']) && $_GET['listing'] == 'new')
			 $no_entry = __('No new listings found.','wdm-ultimate-auction');
		    elseif(isset($_GET['listing']) && $_GET['listing'] == 'sold')
			 $no_entry = __('No auction is sold yet.','wdm-ultimate-auction');
			 
		    echo "<li class='wdm-auction-single-item'> ".__('No Entries yet.','wdm-ultimate-auction')." ".$no_entry."</li>";
		    }
		else{
		    foreach($wdm_auction_array as $wdm_single_auction){
			 
			global $wpdb;
			
			$act_trm = wp_get_post_terms($wdm_single_auction->ID, 'auction-status',array("fields" => "names"));
			
			$query="SELECT MAX(bid) FROM ".$wpdb->prefix."wdm_bidders WHERE auction_id =".$wdm_single_auction->ID;
			$curr_price = $wpdb->get_var($query);
			?>
			<li class="wdm-auction-single-item">
				<a href="<?php echo get_permalink().$set_char."ult_auc_id=".$wdm_single_auction->ID; ?>" class="wdm-auction-list-link">
				<ul class="wdm_all_li_content_auc">
			<li class="wdm-apn auc_single_list">
				<div  class="wdm_single_auction_thumb">
				<?php $vid_arr = array('mpg', 'mpeg', 'avi', 'mov', 'wmv', 'wma', 'mp4', '3gp', 'ogm', 'mkv', 'flv');
					$auc_thumb = get_post_meta($wdm_single_auction->ID, 'wdm_auction_thumb', true);
					$imgMime = wdm_get_mime_type($auc_thumb); 
					$img_ext = explode(".",$auc_thumb);
					$img_ext = end($img_ext);
					
					if(strpos($img_ext, '?') !== false)
						$img_ext = strtolower(strstr($img_ext, '?', true));
					
					if(strstr($imgMime, "video/") || in_array($img_ext, $vid_arr) || strstr($auc_thumb, "youtube.com") || strstr($auc_thumb, "vimeo.com")){
					$auc_thumb = plugins_url('img/film.png', __FILE__);	
				}
				if(empty($auc_thumb)){$auc_thumb = plugins_url('img/no-pic.jpg', __FILE__);}
				?>
				<img src="<?php echo $auc_thumb; ?>" alt="<?php echo $wdm_single_auction->post_title; ?>" />
				</div>
			</li>
			
			<li class="wdm-apt auc_single_list">
				<div class="wdm-auction-title wdm_auc_mid"><?php echo $wdm_single_auction->post_title; ?></div>
			</li>
			
			<li class="wdm-app auc_single_list auc_list_center">
			<div class="wdm-auction-price wdm-mark-green wdm_auc_mid">
			<?php
			//$cc = substr(get_option('wdm_currency'), -3);
			$ob = get_post_meta($wdm_single_auction->ID, 'wdm_opening_bid', true);
			$bnp = get_post_meta($wdm_single_auction->ID, 'wdm_buy_it_now', true);
			
			if((!empty($curr_price) || $curr_price > 0) && !empty($ob))
				echo $currency_symbol. number_format($curr_price, 2, '.', ',')." ".$currency_code_display;
			elseif(!empty($ob))
				echo $currency_symbol.number_format($ob, 2, '.', ',')." ".$currency_code_display;
			elseif(empty($ob) && !empty($bnp))
				printf(__('Buy at %s%s %s', 'wdm-ultimate-auction'), $currency_symbol, number_format($bnp, 2, '.', ','), $currency_code_display);
				?>
			</div>
			</li>
			
			<li class="wdm-apb auc_single_list auc_list_center">
			<?php
			$get_bids = "SELECT COUNT(bid) FROM ".$wpdb->prefix."wdm_bidders WHERE auction_id =".$wdm_single_auction->ID;
			$bids_placed = $wpdb->get_var($get_bids);
			$auc_bstat = get_post_meta($wdm_single_auction->ID, 'auction_bought_status', true);
			
			if(isset($_GET['listing']) && $_GET['listing'] == 'sold' && in_array('expired',$act_trm) && $auc_bstat === 'bought'){
			      echo "<span class='wdm-bids-avail wdm-mark-normal'>".__("Sold at 'Buy Now' price", 'wdm-ultimate-auction')."</span>";
			}
			elseif(!empty($bids_placed) || $bids_placed > 0)
				echo "<div class='wdm-bids-avail wdm-mark-normal'>".$bids_placed."</div>";
			else
				echo "<div class='wdm-no-bids-avail wdm-mark-red wdm_auc_mid'>".__('No bids placed', 'wdm-ultimate-auction')."</div>";
			?>
			</li>
			
			<li class="wdm-ape auc_single_list auc_list_center">
				<?php
				$now = time(); 
				$ending_date = strtotime(get_post_meta($wdm_single_auction->ID, 'wdm_listing_ends', true));
				
				$seconds = $ending_date - $now;
				
				if(in_array('expired',$act_trm))
				{
					$seconds = $now - $ending_date;
					$ending_tm = '';
					$ended_at = wdm_ending_time_calculator($seconds, $ending_tm);
					echo "<span class='wdm-mark-normal'>".sprintf(__('%s ago', 'wdm-ultimate-auction'), $ended_at)."</span>";
				}
				elseif($seconds > 0 && !in_array('expired',$act_trm))
				{
					$ending_tm = '';
					$ending_in = wdm_ending_time_calculator($seconds, $ending_tm);
					
					echo "<div class='wdm-mark-normal wdm_auc_mid'>". $ending_in ."</div>";	
				}
				else
				{
					$seconds = $now - $ending_date;
					$ending_tm = '';
					$ended_at = wdm_ending_time_calculator($seconds, $ending_tm);
					echo "<div class='wdm-mark-normal wdm_auc_mid'>".sprintf(__('%s ago', 'wdm-ultimate-auction'), $ended_at)."</div>";
				}

				?>
				
			</li>
			<li class="wdm-apbid auc_single_list auc_list_center">
			 <?php if(isset($_GET['listing']) && $_GET['listing'] == 'sold'){
			      echo "";
			 }
			 else{
			      ?>
			    <input class="wdm_bid_now_btn wdm_auc_mid" type="button" value="<?php _e('Bid Now', 'wdm-ultimate-auction');?>" />  
			 <?php
			 }
			 ?>
			</li>
			<li><div class="wdm-apd"><?php echo $wdm_single_auction->post_excerpt ; ?> </div></li>
				</ul>
				</a>
			</li>
			<?php
		}
        }
//global $wpdb;
//
//$live_posts = array();
//
//$comm_query = "SELECT object_id
//FROM ".$wpdb->prefix."term_relationships
//WHERE term_taxonomy_id = (SELECT term_id
//FROM ".$wpdb->prefix."terms
//WHERE slug = 'live')";
//
//$comm_query = apply_filters('wdm_ua_filtered_auctions', $comm_query);
//
//$live_posts = $wpdb->get_col($comm_query);
//
//$live_posts = implode("," , $live_posts);
//
//$comm_qry = "SELECT count(ID)
//          FROM ".$wpdb->prefix."posts
//          WHERE post_type = 'ultimate-auction'
//          AND ID IN($live_posts)
//          AND post_status = 'publish'";
//
//$comm_qry = apply_filters('wdm_ua_filtered_counts', $comm_qry);
//	  
//if(isset($_GET['listing']) && $_GET['listing'] == 'ending'){
//     $ending_posts = $wpdb->get_col("SELECT post_id
//          FROM ".$wpdb->prefix."postmeta
//          WHERE meta_key = 'wdm_ending_soon'");
//     
//     if(!empty($ending_posts)){
//	$ending_posts = implode("," , $ending_posts);
//	$count_pages = $wpdb->get_var($comm_qry." AND ID IN($ending_posts)");
//     }
//
//}
//elseif(isset($_GET['listing']) && $_GET['listing'] == 'active'){
//     
//      $active_posts = $wpdb->get_col("SELECT post_id
//          FROM ".$wpdb->prefix."postmeta
//          WHERE meta_key = 'total_bids_count'");
//      
//      if(!empty($active_posts)){
//	$active_posts = implode("," , $active_posts); 
//	$count_pages = $wpdb->get_var($comm_qry." AND ID IN($active_posts)");
//      }
//}
//elseif(isset($_GET['listing']) && $_GET['listing'] == 'new'){
//    
//     $count_pages = $wpdb->get_var($comm_qry."  AND post_date > '" . date('Y-m-d', strtotime('-7 days')) . "'");
//}
//else{
//    
//     $count_pages = $wpdb->get_var($comm_qry);
//}

echo '<input type="hidden" id="wdm_ua_auc_avail" value="'.$count_pages.'" />';

$c=ceil($count_pages/$page_num);
wdm_auction_pagination($c, 1, $paged);
?>
</ul>
</div>		
