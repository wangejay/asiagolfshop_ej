<?php
function wdm_my_watch()
{
$page_num = get_option('wdm_auc_num_per_page');
$page_num = (!empty($page_num) && $page_num > 0) ? $page_num : 20;

$wdm_auction_array = array();
$ua_auction_array = array();
$wdm_my_watch='';
if (get_query_var('paged')) { $paged = get_query_var('paged'); }
elseif (get_query_var('page')) { $paged = get_query_var('page'); }
else { $paged = 1; }

$watch_auctions = get_user_meta(get_current_user_id(),'wdm_watch_auctions', true);

if(isset($watch_auctions)){
  
  $auction_ids = explode(" ", $watch_auctions);

  $arg_data = array(
	        'posts_per_page'=> $page_num,
                'post__in'=>$auction_ids,
		'post_type'	=> 'ultimate-auction',
		'post_status' => 'publish',
		//'auction-status'=>array('live', 'expired'),
		'paged' => $paged,
		'order' => 'ASC'
		);
  $arg_data_c = array(
	        'posts_per_page'=> -1,
                'post__in'=>$auction_ids,
		'post_type'	=> 'ultimate-auction',
		'post_status' => 'publish',
                //'auction-status'=>array('live', 'expired'),
		'paged' => $paged,
		'order' => 'ASC'
		);
}
	global $wpdb;
	
	  $wdm_auction_array = get_posts($arg_data);
	  $count_pages = count(get_posts($arg_data_c));
		//auction listing page container
		if(empty($wdm_auction_array)){
		  $wdm_my_watch.="<li class='wdm-auction-single-item'> ".__('No Entries yet.','wdm-ultimate-auction')."</li>";
		    }
		else{
		    foreach($wdm_auction_array as $wdm_single_auction){
			
			$act_trm = wp_get_post_terms($wdm_single_auction->ID, 'auction-status',array("fields" => "names"));
			
			$query="SELECT MAX(bid) FROM ".$wpdb->prefix."wdm_bidders WHERE auction_id =".$wdm_single_auction->ID;
			$curr_price = $wpdb->get_var($query);
			 $vid_arr = array('mpg', 'mpeg', 'avi', 'mov', 'wmv', 'wma', 'mp4', '3gp', 'ogm', 'mkv', 'flv');
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
			$perma_type = get_option('permalink_structure');
			if(empty($perma_type))
			$set_char = "&";
			else
			$set_char = "?";
			$auc_url = get_option('wdm_auction_page_url');
   
			if(!empty($auc_url)){
			$link_title = $auc_url.$set_char."ult_auc_id=".$wdm_single_auction->ID;
			//$link_title = "<a href='".$link_title."' target='_blank'>".$title."</a>";
			$title = $link_title;
			}
			$wdm_my_watch.='<li class="wdm-auction-single-item">
				<a href="'.$title.'" class="wdm-auction-list-link">
				<div class="wdm-auction-listing-container">
				<ul class="wdm_all_li_content_auc">
			<li class="wdm-apn auc_single_list">
				<div  class="wdm_single_auction_thumb">
				<img src="'.$auc_thumb .'" width="100" height="80" alt="'.$wdm_single_auction->post_title.'" />
				</div>
			</li>
			
			<li class="wdm-apt auc_single_list">
				<div class="wdm-auction-title">'.$wdm_single_auction->post_title.'</div>
			</li>
			
			<li class="wdm-app auc_single_list auc_list_center">
			<span class="wdm-auction-price wdm-mark-green">';
			$cc = substr(get_option('wdm_currency'), -3);
			$ob = get_post_meta($wdm_single_auction->ID, 'wdm_opening_bid', true);
			$bnp = get_post_meta($wdm_single_auction->ID, 'wdm_buy_it_now', true);
			
			if(!in_array('expired',$act_trm)){
			  if((!empty($curr_price) || $curr_price > 0) && !empty($ob))
				$wdm_my_watch.=$cc ." ". sprintf("%.2f", $curr_price);
			  elseif(!empty($ob))
				$wdm_my_watch.=$cc ." ".sprintf("%.2f", $ob);
			  elseif(empty($ob) && !empty($bnp))
				$wdm_my_watch.=sprintf(__('Buy at %s %.2f', 'wdm-ultimate-auction'), $cc, $bnp);
			}	
			elseif(in_array('expired',$act_trm)){
			  
			$bought = get_post_meta($wdm_single_auction->ID, 'auction_bought_status', true);
	      
			if($bought === 'bought')
			  $wdm_my_watch.= $cc ." ". sprintf("%.2f", $bnp);
			elseif((!empty($curr_price) || $curr_price > 0) && !empty($ob))
			  $wdm_my_watch.= $cc ." ". sprintf("%.2f", $curr_price);
			elseif(!empty($ob))
			  $wdm_my_watch.= $cc ." ".sprintf("%.2f", $ob);
			elseif(empty($ob) && !empty($bnp))
			  $wdm_my_watch.= $cc ." ".sprintf("%.2f", $bnp);
			}
			
			$wdm_my_watch.='</span>
			</li>
			<li class="wdm-apb auc_single_list auc_list_center">';
			$get_bids = "SELECT COUNT(bid) FROM ".$wpdb->prefix."wdm_bidders WHERE auction_id =".$wdm_single_auction->ID;
			$bids_placed = $wpdb->get_var($get_bids);
			$auc_bstat = get_post_meta($wdm_single_auction->ID, 'auction_bought_status', true);
			
			if(in_array('expired',$act_trm) && $auc_bstat === 'bought'){
			      $wdm_my_watch.="<span class='wdm-bids-avail wdm-mark-normal'>".__("Sold at 'Buy Now' price", 'wdm-ultimate-auction')."</span>";
			}
			else{
			  if(!empty($bids_placed) || $bids_placed > 0)
				$wdm_my_watch.="<span class='wdm-bids-avail wdm-mark-normal'>".$bids_placed."</span>";
			  else
				$wdm_my_watch.="<span class='wdm-no-bids-avail wdm-mark-red'>".__('No bids placed', 'wdm-ultimate-auction')."</span>";
			}
			
			$wdm_my_watch.='</li>
			<li class="wdm-ape auc_single_list auc_list_center">';
			$now = time(); 
				$ending_date = strtotime(get_post_meta($wdm_single_auction->ID, 'wdm_listing_ends', true));
				
				$seconds = $ending_date - $now;
				
				if(in_array('expired',$act_trm))
				{
					$seconds = $now - $ending_date;
					$ending_tm = '';
					$ended_at = wdm_ending_time_calculator($seconds, $ending_tm);
					$wdm_my_watch.="<span class='wdm-mark-normal'>".sprintf(__('%s ago', 'wdm-ultimate-auction'), $ended_at)."</span>";
				}
				elseif($seconds > 0 && !in_array('expired',$act_trm))
				{
					$ending_tm = '';
					$ending_in = wdm_ending_time_calculator($seconds, $ending_tm);
					
					$wdm_my_watch.="<span class='wdm-mark-normal'>". $ending_in ."</span>";	
				}
				else
				{
					$seconds = $now - $ending_date;
					$ending_tm = '';
					$ended_at = wdm_ending_time_calculator($seconds, $ending_tm);
					$wdm_my_watch.="<span class='wdm-mark-normal'>".sprintf(__('%s ago', 'wdm-ultimate-auction'), $ended_at)."</span>";
				}
			$wdm_my_watch.='<br/>
			</li>
			<li class="wdm-apbid auc_single_list auc_list_center">';
			
			if(in_array('expired',$act_trm)){
			    $wdm_my_watch.='<span class="wdm-mark-red">'.__("Expired", "wdm-ultimate-auction").'</span>';
			}
			elseif(in_array('live',$act_trm)){
			    $wdm_my_watch.='<input class="wdm_bid_now_btn" type="button" value="'.__('Bid Now', 'wdm-ultimate-auction').'" />  ';
			}
			
			$wdm_my_watch.='</li>
			<li><div class="wdm-apd">'. $wdm_single_auction->post_excerpt.'</div></li>
			<li>
			<a id="wdm-rmv-frmwatch-'.$wdm_single_auction->ID.'" style="color:red;cursor:pointer;padding: 0 10px;text-decoration:none;" href="#">'.__('Remove From Watchlist', 'wdm-ultimate-auction').' <span class="auc-ajax-img"></span></a> 
			</li>
				</ul>
				</div>
				</a>
			</li>';
			?>
	    <script type="text/javascript">
            jQuery(document).ready(
			      function($){
			      $('#wdm-rmv-frmwatch-<?php echo $wdm_single_auction->ID;?>').click(function(e){
			      e.preventDefault();
			      var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
			      var cnf = confirm('<?php _e("Are you sure to remove this auction from your watchlist?", "wdm-ultimate-auction");?>');
				
			      if(cnf == true){
			      $(this).html("<?php _e('Removing', 'wdm-ultimate-auction'); echo ' ';?> <img src='<?php echo plugins_url('/img/ajax-loader.gif', __FILE__);?>' />");       
			      var data = {
				       action:'rmv_frm_watchlist',
				       rmv_id:'<?php echo $wdm_single_auction->ID;?>',
				       usr_id:'<?php echo get_current_user_id(); ?>',
				       auc_title: '<?php echo esc_js($wdm_single_auction->post_title);?>',
				       force_del:'yes'
				    };
				    $.post(ajaxurl, data, function(response) {
				    $('#wdm-rmv-frmwatch-<?php echo $wdm_single_auction->ID;?>').html('<?php _e("Remove From Watchlist", "wdm-ultimate-auction");?>');
				    alert(response);
				    window.location.reload();
				    });
				}
				return false;
				});
			      });
            </script>
			<?php
		}
        }
$wdm_my_watch.='<input type="hidden" id="wdm_ua_auc_avail" value="'.$count_pages.'" />';
//echo '<input type="hidden" id="wdm_ua_auc_avail" value="'.$count_pages.'" />';

$c=ceil($count_pages/$page_num);
wdm_auction_pagination($c, 1, $paged);
return $wdm_my_watch;
}
?>