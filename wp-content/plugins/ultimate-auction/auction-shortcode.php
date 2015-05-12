<?php
add_action('wp_head', 'wdm_enqueue_front_script');

function wdm_enqueue_front_script(){
	
	if(isset($_GET["ult_auc_id"]) && $_GET["ult_auc_id"]){
		wp_enqueue_style('wdm_lightbox_css',plugins_url('lightbox/jquery.fs.boxer.css', __FILE__));
		wp_enqueue_script('wdm-lightbox-js', plugins_url('lightbox/jquery.fs.boxer.js', __FILE__), array('jquery'));
		wp_enqueue_script('wdm-block-ui-js', plugins_url('js/wdm-jquery.blockUI.js', __FILE__), array('jquery'));
		wp_enqueue_script('wdm-custom-js', plugins_url('js/wdm-custom-js.js', __FILE__), array('jquery'));
	}
}

function wdm_auction_listing(){
	
	//enqueue css file for front end style
	wp_enqueue_style('wdm_auction_front_end_styling',plugins_url('css/ua-front-end.css', __FILE__));

	ob_start();
	//check the permalink from database and append variable to the auction single pages accordingly
	$perma_type = get_option('permalink_structure');
		
	//get currency code
	$currency_code			 = substr( get_option( 'wdm_currency' ), -3 );
	$currency_code_display	 = '';
	preg_match( '/-([^ ]+)/', get_option( 'wdm_currency' ), $matches );
	$currency_symbol = $matches[ 1 ];

	if(empty($currency_symbol)){
		$currency_symbol = $currency_code.' ';
	}
	else{
		if ( $currency_symbol == '$' || $currency_symbol == 'kr' ) {
			$currency_code_display = $currency_code;
		}	
	}
	
	//get Login url if set
	$wdm_login_url=get_option('wdm_login_page_url');
	if(empty($wdm_login_url))
	{
		$wdm_login_url=wp_login_url( $_SERVER['REQUEST_URI']);
	}
	
	if(is_front_page() || is_home())
	$set_char = "?";
	elseif(empty($perma_type))
	$set_char = "&";
	else
	$set_char = "?";
	
	$auc_time = '';
	
	if(is_user_logged_in() && isset($_GET["ult_auc_id"]) && !empty($_GET["ult_auc_id"]) && isset($_GET["mt"]) && !empty($_GET["mt"])){
		
		$wdm_auction=get_post($_GET["ult_auc_id"]);
		$curr_user = wp_get_current_user();
		$buyer_email = $curr_user->user_email;
		//$winner_name = $curr_user->user_login;
		$ret_url = get_permalink().$set_char."ult_auc_id=".$wdm_auction->ID;
		
		$check_method = get_post_meta($_GET["ult_auc_id"], 'wdm_payment_method', true);
		
		_e('Thank you for buying this product.', 'wdm-ultimate-auction');
		echo "<br /><br />";
		
		//$auc_post = get_post($_GET["ult_auc_id"]);
		//$auction_author_id = $auc_post->post_author;
		//$auction_author = new WP_User($auction_author_id);
		
		if($check_method === 'method_wire_transfer'){
			$mthd = __('Wire Transfer', 'wdm-ultimate-auction');
			
			//if(in_array('administrator', $auction_author->roles))
				$det = get_option('wdm_wire_transfer');
			//else
			//	$det = get_user_meta($auction_author_id, 'wdm_wire_transfer', true);
		}
		elseif($check_method === 'method_mailing'){
			$mthd = __('Cheque', 'wdm-ultimate-auction');
			
			//if(in_array('administrator', $auction_author->roles))
				$det = get_option('wdm_mailing_address');
			//else
			//	$det = get_user_meta($auction_author_id, 'wdm_mailing_address', true);
		}
		elseif($check_method === 'method_cash'){
			$mthd = __('Cash', 'wdm-ultimate-auction');
			
			//if(in_array('administrator', $auction_author->roles))
				$det = get_option('wdm_cash');
			//else
			//	$det = get_user_meta($auction_author_id, 'wdm_cash', true);
		}
		
		$mthd = "<strong>".$mthd."</strong>";
		
		printf(__('You can make the payment by %s', 'wdm-ultimate-auction'), $mthd);
		
		if(!empty($det))
			echo "<br /><br /><strong>".__('Details').":</strong> <br/>".$det;
		
		echo '<br /><br /><a href="'.get_permalink().$set_char.'ult_auc_id='.$_GET['ult_auc_id'].'">'.__('Go Back', 'wdm-ultimate-auction').'</a>';
		
		$buy_now_price = get_post_meta($wdm_auction->ID, 'wdm_buy_it_now', true);
		
		ultimate_auction_email_template($wdm_auction->post_title, $wdm_auction->ID, $wdm_auction->post_content, $buy_now_price, $buyer_email, $ret_url);
		
	}
	elseif(isset($_GET["ult_auc_id"]) && $_GET["ult_auc_id"]){
		
		//if single auction page is found do the following
		global $wpdb;
		$wpdb->hide_errors();
		$wdm_auction=get_post($_GET["ult_auc_id"]);
		if($wdm_auction){
			
		$auction_author_id = $wdm_auction->post_author;
		$auction_author = new WP_User($auction_author_id);
		//update single auction page url on single auction page visit - if the permalink type is updated we should have appropriate url to be sent in email 	
		update_post_meta($wdm_auction->ID, 'current_auction_permalink', get_permalink().$set_char."ult_auc_id=".$wdm_auction->ID);
		 
		 //check if start price/opening bid price is set
		$to_bid = get_post_meta($wdm_auction->ID, 'wdm_opening_bid', true);
		
		//check if buy now price is set
		$to_buy = get_post_meta($wdm_auction->ID, 'wdm_buy_it_now', true); 
		
		//latest highest/current price
		//$wdm_price_flag=false;
		$query="SELECT MAX(bid) FROM ".$wpdb->prefix."wdm_bidders WHERE auction_id =".$wdm_auction->ID;
		$curr_price = $wpdb->get_var($query);
		if(empty($curr_price))
			$curr_price = get_post_meta($wdm_auction->ID,'wdm_opening_bid',true);
			
		//total no. of bids	
		$qry="SELECT COUNT(bid) FROM ".$wpdb->prefix."wdm_bidders WHERE auction_id =".$wdm_auction->ID;
		$total_bids = $wpdb->get_var($qry);
		
		//buy now price
		$buy_now_price = get_post_meta($wdm_auction->ID,'wdm_buy_it_now',true);
		
		//get currency code
		$currency_code = substr(get_option('wdm_currency'), -3);
	        
		$bef_auc = '';
		$bef_auc = apply_filters('wdm_ua_before_single_auction', $bef_auc, $wdm_auction->ID);
		echo $bef_auc;
		?>
		
		<!--main forms container of single auction page-->
		 <div class="wdm-ultimate-auction-container">
						
			<div class="wdm-image-container">
				 <?php $images = '';
			    
			    $mnimg = get_post_meta($wdm_auction->ID,'wdm-main-image',true);
			    $img_arr = array('png', 'jpg', 'jpeg', 'gif', 'bmp', 'ico');
			    $vid_arr = array('mpg', 'mpeg', 'avi', 'mov', 'wmv', 'wma', 'mp4', '3gp', 'ogm', 'mkv', 'flv');
			    
			    $flg = 0;
			    
			    $images .= '<div class="auction-main-img-cont">';
			    
			    for($c=1; $c<=4; $c++){
				   if($mnimg === 'main_image_'.$c)
					  $img_show = "display: block";
				   else
					  $img_show = "display: none";
				   
				   $imgURL = get_post_meta($wdm_auction->ID,'wdm-image-'.$c,true);
				   $imgMime = wdm_get_mime_type($imgURL);
			           $img_ext = explode(".",$imgURL);
				   $img_ext = end($img_ext);
				   
				   if(strpos($img_ext, '?') !== false)
					$img_ext = strtolower(strstr($img_ext, '?', true));
				   
				    if(empty($imgURL)){
						$images .= '';
					}
			    else{
					$flg = 1;
					
				   $images .= '<a href="'.get_post_meta($wdm_auction->ID,'wdm-image-'.$c,true).'" class="auction-main-img-a auction-main-img'.$c.'" rel="gallery" style="'.$img_show.'">';
				   
				   if(strstr($imgMime, "image/") || in_array($img_ext, $img_arr))
					  $images .= '<img class="auction-main-img"  src="'.get_post_meta($wdm_auction->ID,'wdm-image-'.$c,true).'" />';
			    
				   elseif(strstr($imgMime, "video/") || in_array($img_ext, $vid_arr))
					  $images .= '<video class="auction-main-img" style="margin-bottom:0;" controls>
				   <source src="'.get_post_meta($wdm_auction->ID,'wdm-image-'.$c,true).'">
					  Your browser does not support the video tag.
				   </video>';
				   elseif(strstr($imgURL, "youtube.com") || strstr($imgURL, "vimeo.com"))
					  $images .= '<img class="auction-main-img"  src="'.plugins_url('img/film.png', __FILE__).'" />';
				   else
					  $images .= '<img class="auction-main-img"  src="'.wp_mime_type_icon( $imgMime ).'" />';
			    
					  $images .= '</a>';
				}
			    } 
			    
			    $images .= '</div>';
			
			if($flg == 0)
				echo '<style> .wdm-image-container{display: none;} </style>';
				
			    $images .= '<div class="auction-small-img-cont">';
			
			    for($c=1; $c<=4; $c++){
				   
			    $imgURL = get_post_meta($wdm_auction->ID,'wdm-image-'.$c,true);
			    $imgMime = wdm_get_mime_type($imgURL);
			    $img_ext = explode(".",$imgURL);
			    $img_ext = end($img_ext);
			    
			    if(strpos($img_ext, '?') !== false)
				$img_ext = strtolower(strstr($img_ext, '?', true));
			    
			    if(empty($imgURL)){
				$images .= '';
			    }
			    else{
					if(strstr($imgMime, "image/") || in_array($img_ext, $img_arr))
						$images .= '<img class="auction-small-img auction-small-img'.$c.'" src="'.$imgURL.'" />';
					elseif(strstr($imgMime, "video/") || in_array($img_ext, $vid_arr)  || strstr($imgURL, "youtube.com") || strstr($imgURL, "vimeo.com"))
						$images .= '<img class="auction-small-img auction-small-img'.$c.'"  src="'.plugins_url('img/film.png', __FILE__).'" />';
					else
						$images .= '<img class="auction-small-img auction-small-img'.$c.'" src="'.wp_mime_type_icon( $imgMime ).'" />';   
				}
			    }
			    $images .= '</div>';
			    
			    echo $images;
			    ?>
			</div> <!--wdm-image-container ends here-->
			
			<div class="wdm_single_prod_desc">
			    
			    <div class="wdm-single-auction-title">
				<?php echo $wdm_auction->post_title;?>
			    </div> <!--wdm-single-auction-title ends here-->
			    
			<?php
			
			$ext_html = '';
			$ext_html = apply_filters('wdm_ua_text_before_bid_section', $ext_html, $wdm_auction->ID);
			echo $ext_html;
			
			//get auction-status taxonomy value for the current post - live/expired
			$active_terms = wp_get_post_terms($wdm_auction->ID, 'auction-status',array("fields" => "names"));
			
			//incremented price value
			$inc_price = $curr_price + get_post_meta($wdm_auction->ID,'wdm_incremental_val',true);
			
			//if the auction has reached it's time limit, expire it
			if((time() >= strtotime(get_post_meta($wdm_auction->ID,'wdm_listing_ends',true)))){
				if(!in_array('expired',$active_terms))
				{
					$check_term = term_exists('expired', 'auction-status');
					wp_set_post_terms($wdm_auction->ID, $check_term["term_id"], 'auction-status');
				}
				
			}
			
			$now = time(); 
		        $ending_date = strtotime(get_post_meta($wdm_auction->ID, 'wdm_listing_ends', true));
			
			//display message for expired auction
			if((time() >= strtotime(get_post_meta($wdm_auction->ID,'wdm_listing_ends',true))) || in_array('expired',$active_terms)){
				
				$seconds =  $now - $ending_date;
				
				$rem_tm = wdm_ending_time_calculator($seconds);
				
				$auc_time = 'exp';
			    
			    ?>
			    <div class="wdm-auction-ending-time"><?php printf(__('Ended at', 'wdm-ultimate-auction').': '.__('%s ago', 'wdm-ultimate-auction'), '<span class="wdm-single-auction-ending">'.$rem_tm.'</span>');?></div>
			    
			    <?php
			    if(!empty($to_bid)){?>
				   
				   <div class="wdm_bidding_price" style="float:left;">
								<strong><?php echo $currency_symbol . number_format($curr_price, 2, '.', ','). " " . $currency_code_display; ?></strong>
				   </div>
				   <div id="wdm-auction-bids-placed" class="wdm_bids_placed" style="float:right;">
					<a href="#wdm-tab-anchor-id" id="wdm-total-bids-link"><?php echo $total_bids." "; echo ($total_bids == 1) ? __("Bid", "wdm-ultimate-auction") : __("Bids", "wdm-ultimate-auction"); ?></a>
				   </div>
			
				   <br />
				   
			<?php }
			    
			    $bought = get_post_meta($wdm_auction->ID, 'auction_bought_status', true);
			    
			    if($bought === 'bought'){
				$buyer_id = get_post_meta($wdm_auction->ID, 'wdm_auction_buyer', true);
				$buyer = get_user_by('id', $buyer_id);
				printf( '<div class="wdm-mark-red">' . __( 'This auction has been bought by %s at %s', 'wdm-ultimate-auction' ) . '</div>', $buyer->user_login, '[' . $currency_symbol . number_format($buy_now_price, 2, '.', ','). ' ' . $currency_code_display . ']' );
			    }
			    else{
				   $cnt_qry = "SELECT COUNT(bid) FROM ".$wpdb->prefix."wdm_bidders WHERE auction_id =".$wdm_auction->ID;
				   $cnt_bid = $wpdb->get_var($cnt_qry);
				   if($cnt_bid > 0)
				   {
					  $res_price_met = get_post_meta($wdm_auction->ID, 'wdm_lowest_bid',true);
				   
					  $win_bid = "";
					  $bid_q = "SELECT MAX(bid) FROM ".$wpdb->prefix."wdm_bidders WHERE auction_id =".$wdm_auction->ID;
					  $win_bid = $wpdb->get_var($bid_q);
				   
					  if($win_bid >= $res_price_met){
						 $winner_name  = "";
						 $name_qry = "SELECT name FROM ".$wpdb->prefix."wdm_bidders WHERE bid =".$win_bid." AND auction_id =".$wdm_auction->ID." ORDER BY id DESC";
						 $winner_name = $wpdb->get_var($name_qry);
						printf( '<div class="wdm-mark-red">' . __( 'This auction has been sold to %1$s at %2$s.', 'wdm-ultimate-auction' ) . '</div>', $winner_name, $currency_symbol . number_format($win_bid, 2, '.', ','). " " . $currency_code_display );
					  }
					  else
					  {
						 echo '<div class="wdm-mark-red">'.__('Auction has expired without reaching its reserve price.', 'wdm-ultimate-auction').'</div>';
					  }
				   }
				   else
				   {
					  if(empty($to_bid))
						 echo '<div class="wdm-mark-red">'.__('Auction has expired without buying.', 'wdm-ultimate-auction').'</div>';
					  else 	 
						 echo '<div class="wdm-mark-red">'.__('Auction has expired without any bids.', 'wdm-ultimate-auction').'</div>';	
				   }
			    }
			    
			}
			
			else{
				//prepare a format and display remaining time for current auction
				
				$seconds = $ending_date - $now;
				
				$rem_tm = wdm_ending_time_calculator($seconds);
				
				$auc_time = "live";
				
				if(is_user_logged_in()){
				   $curr_user = wp_get_current_user();
				   $auction_bidder_name = $curr_user->user_login;
				   $auction_bidder_email = $curr_user->user_email;
				}
				
			?>
			<!--form to place bids-->
				
				<div class="wdm-auction-ending-time"><?php printf(__('Ending in: %s', 'wdm-ultimate-auction'),'<span class="wdm-single-auction-ending">'.$rem_tm.'</span>');?></div>
				
				<?php if(!empty($to_bid)) {?>
				<div id="wdm_place_bid_section">
				<div class="wdm_bidding_price" style="float:left;">
									<strong><?php echo $currency_symbol . number_format($curr_price, 2, '.', ','). " " . $currency_code_display; ?></strong>
				</div>
				<div id="wdm-auction-bids-placed" class="wdm_bids_placed" style="float:right;">
					<a href="#wdm-tab-anchor-id" id="wdm-total-bids-link"><?php echo $total_bids." "; echo ($total_bids == 1) ? __("Bid", "wdm-ultimate-auction") : __("Bids", "wdm-ultimate-auction"); ?></a>
				</div>
				<?php 
				if($curr_price >= get_post_meta($wdm_auction->ID,'wdm_lowest_bid',true)){
				?>
				<br />
				<div class="wdm_reserved_note wdm-mark-green" style="float:left;">
					<em><?php _e('Reserve price has been met.', 'wdm-ultimate-auction');?></em>
				</div>
				<?php }
				else{
					?>
					<div class="wdm_reserved_note wdm-mark-red" style="float:left;">
					<em><?php _e('Reserve price has not been met by any bid.', 'wdm-ultimate-auction');?></em>
					</div>
					<?php
				}
				
				if(is_user_logged_in()) {
				   //$curr_user = wp_get_current_user();
				   //$auction_bidder_name = $curr_user->user_login;
				   //$auction_bidder_email = $curr_user->user_email;
				   
				   if($curr_user->ID != $wdm_auction->post_author){
				   
				?>
				<br />
				<form action="<?php echo dirname(__FILE__); ?>" style="margin-top:20px;">
					<div class="wdm_bid_val" style="">
						<label for="wdm-bidder-bidval"><?php _e('Bid Value', 'wdm-ultimate-auction');?>: </label>
						<input type="text" id="wdm-bidder-bidval" style="width:85px;" placeholder="<?php printf( __( 'in %s', 'wdm-ultimate-auction' ), $currency_symbol . $currency_code_display ); ?>" />
						<br /><span class="wdm_enter_val_text" style="float:left;">
						<small>(<?php printf(__('Enter %.2f or more', 'wdm-ultimate-auction'), $inc_price);?>)
						<?php
						
						$ehtml = '';
						$ehtml = apply_filters('wdm_ua_text_after_bid_form', $ehtml, $wdm_auction->ID);
						echo $ehtml;
						?>
						</small>
						</span>
					</div>
					<div class="wdm_place_bid" style="float:right;">
						<input type="submit" value="<?php _e('Place Bid', 'wdm-ultimate-auction');?>" id="wdm-place-bid-now" />
					</div>
					
				</form>
				<?php
				   require_once('ajax-actions/place-bid.php'); //file to handle ajax requests related to bid placing form
				   }
				}
				else{
				  ?>
				   <br />
					<div class="wdm_bid_val" style="float:left;">
										<label for="wdm-bidder-bidval"><?php _e( 'Bid Value', 'wdm-ultimate-auction' ); ?>: </label>
										<input type="text" id="wdm-bidder-bidval" style="width:85px;" placeholder="<?php printf( __( 'in %s', 'wdm-ultimate-auction' ), $currency_symbol . $currency_code_display ); ?>" />
						<br /><span class="wdm_enter_val_text" style="float:right;">
						<small>(<?php printf(__('Enter %.2f or more', 'wdm-ultimate-auction'), $inc_price);?>)</small>
						</span>
					</div>
					
				   <div class="wdm_place_bid" style="float:right;padding-top:6px;">
					  <a class="wdm-login-to-place-bid" href="<?php echo $wdm_login_url; ?>" title="<?php _e('Login', 'wdm-ultimate-auction');?>"><?php _e('Place Bid', 'wdm-ultimate-auction');?></a>
				   </div>
				<?php }?>
				</div> <!--wdm_place_bid_section ends here-->
				<?php
				}?>
				<br />
				<?php if(!empty($to_buy) || $to_buy > 0){
				   $a_key = get_post_meta($wdm_auction->ID, 'wdm-auth-key', true);
				   
				   $acc_mode = get_option('wdm_account_mode');
	
				   if($acc_mode == 'Sandbox')
					  $pp_link  = "https://sandbox.paypal.com/cgi-bin/webscr";
				   else
					  $pp_link  = "https://www.paypal.com/cgi-bin/webscr";
				   if(is_user_logged_in()){
						
					$check_method = get_post_meta($wdm_auction->ID, 'wdm_payment_method', true);
					
					if($check_method == 'method_paypal'){
				   ?>
				<!--buy now button-->
				<div id="wdm_buy_now_section">
					<?php if($curr_user->ID != $wdm_auction->post_author){ ?>
					<div id="wdm-buy-line-above" >
				<form action="<?php echo $pp_link; ?>" method="post" target="_top">
				<input type="hidden" name="cmd" value="_xclick">
				<input type="hidden" name="charset" value="utf-8" />
				<input type="hidden" name="business" value="<?php echo get_option('wdm_paypal_address');?>">
				<!--<input type="hidden" name="lc" value="US">-->
				<input type="hidden" name="item_name" value="<?php echo $wdm_auction->post_title;?>">
				<input type="hidden" name="amount" value="<?php echo $buy_now_price; ?>">
				<?php $shipping_field = '';
				      echo apply_filters('ua_product_shipping_cost_field', $shipping_field, $wdm_auction->ID);
				?>
				<input type="hidden" name="currency_code" value="<?php echo $currency_code; ?>">
				<input type="hidden" name="return" value="<?php echo get_permalink().$set_char."ult_auc_id=".$wdm_auction->ID; ?>">
				<input type="hidden" name="button_subtype" value="services">
				<input type="hidden" name="no_note" value="0">
				<input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHostedGuest">
				<input type="submit" value="<?php printf(__('Buy it now for %s%s %s', 'wdm-ultimate-auction'),  $currency_symbol, number_format($buy_now_price, 2, '.', ','), $currency_code_display);?>" id="wdm-buy-now-button">
				</form>
					</div>
					<?php }
					?>
			        </div> <!--wdm_buy_now_section ends here-->
				
				<script type="text/javascript">
				   jQuery(document).ready(function(){
				   jQuery("#wdm_buy_now_section form").click(function(){
				   var cur_val = jQuery("#wdm_buy_now_section form input[name='return']").val();
				   jQuery("#wdm_buy_now_section form input[name='return']").val(cur_val+"&wdm="+"<?php echo $a_key;?>");
				   });
		
			    });
			       </script>
				<?php
				
				   }
				   else{
					if($check_method === 'method_wire_transfer'){
						$mthd = __('Wire Transfer', 'wdm-ultimate-auction');
					}
					elseif($check_method === 'method_mailing'){
						$mthd = __('Cheque', 'wdm-ultimate-auction');
					}
					elseif($check_method === 'method_cash'){
						$mthd = __('Cash', 'wdm-ultimate-auction');
					}
		
					$bn_text = sprintf(__('Buy it now for %s%s %s', 'wdm-ultimate-auction'),  $currency_symbol, number_format($buy_now_price, 2, '.', ','), $currency_code_display);
    
					$shipAmt = 0;
					$shipAmt = apply_filters('ua_shipping_data_invoice', $shipAmt, $wdm_auction->ID, $auction_bidder_email);
    
									if ( $shipAmt > 0 ) {
										$bn_text = sprintf( __( 'Buy it now for %s%s %s + %s%s %s(shipping)', 'wdm-ultimate-auction' ), $currency_symbol, number_format($buy_now_price, 2, '.', ','), $currency_code_display, $currency_symbol, number_format($shipAmt, 2, '.', ','), $currency_code_display );
					}
					?>
					<div id="wdm_buy_now_section">
						<?php if($curr_user->ID != $wdm_auction->post_author){ ?>
						<div id="wdm-buy-line-above" >
						<form action="<?php echo add_query_arg(array('mt' => 'bn', 'wdm' => $a_key), get_permalink().$set_char."ult_auc_id=".$wdm_auction->ID);?>" method="post">
							<input type="submit" value="<?php echo $bn_text; ?>" id="wdm-buy-now-button">
						</form>
						</div>
						<?php } ?>
					</div>
					
					<script type="text/javascript">
						jQuery(document).ready(function($){
							$("#wdm-buy-now-button").click(function(){
								var bcnf = confirm('<?php printf(__("You need to pay %s %.2f amount via %s to %s. If you choose OK, you will receive an email with payment details and auction will expire. Choose Cancel to ignore this buy now transaction.", "wdm-ultimate-auction"), $currency_code, $buy_now_price+$shipAmt, $mthd, $auction_author->user_login);?>');
								if(bcnf == true){ return true; }
								return false;
							});
						});
					</script>
					<?php
				}
				}
				else{?>
				   <div id="wdm_buy_now_section">
					  <div id="wdm-buy-line-above" >
					  <a class="wdm-login-to-buy-now" href="<?php echo $wdm_login_url; ?>" title="<?php _e('Login', 'wdm-ultimate-auction');?>">
						 <?php printf( __( 'Buy it now for %s%s %s', 'wdm-ultimate-auction' ), $currency_symbol, number_format($buy_now_price, 2, '.', ','), $currency_code_display ); ?>
					  </a>
					  </div>
					  
				   </div>
				   <?php
				   }
				}
				
				if(is_user_logged_in() && $curr_user->ID == $wdm_auction->post_author){
					echo "<span style='float: left;'>".__('Sorry, you can not bid on your own item.', 'wdm-ultimate-auction')."</span>";
				}
				
				do_action('wdm_ua_ship_short_link', $wdm_auction->ID);
				
				//do_action('ua_add_shipping_cost_view_field', $wdm_auction->ID); //SHP-ADD hook to add new product data
				}
				?>
			    </div> <!--wdm_single_prod_desc ends here-->
			
		</div> <!--wdm-ultimate-auction-container ends here-->
		
		<!--<div id="wdm_auction_desc_section">
			<div class="wdm-single-auction-description">
				<strong><?php //_e('Description', 'wdm-ultimate-auction');?></strong>
				<br />
				<?php //echo apply_filters('the_content', $wdm_auction->post_content); ?>
			</div>
			
		</div>--> <!--wdm_auction_desc_section ends here-->
			
		<?php 	
			require_once('auction-description-tabs.php'); //file to display current auction description tabs section
		?>
		<!--script to show small images in main image container-->
		<script type="text/javascript">
		jQuery(document).ready(function($){
		
		$('.wdm-image-container .auction-small-img').each(function(i){
				  $('.auction-small-img'+(i+1)).click(function(){
					  $('.auction-main-img-a').css('display','none');
					  $('.auction-main-img'+(i+1)).css('display','block');
				   }); 
			    });
       
		//jQuery(".auction-main-img-a").boxer({'fixed': true});
		
        var eDays = jQuery('#wdm_days');
        var eHours = jQuery('#wdm_hours');
        var eMinutes = jQuery('#wdm_minutes');
        var eSeconds = jQuery('#wdm_seconds');
	
        var timer;
        timer = setInterval(function() {
            var vDays = parseInt(eDays.html(), 10);
            var vHours = parseInt(eHours.html(), 10);
            var vMinutes = parseInt(eMinutes.html(), 10);
            var vSeconds = parseInt(eSeconds.html(), 10);
	    
	    var ac_time = '<?php echo $auc_time;?>';
	    
	    if(ac_time == 'live'){
	    
	    vSeconds--;
		if(vSeconds < 0) {
                vSeconds = 59;
                vMinutes--;
                if(vMinutes < 0) {
                    vMinutes = 59;
                    vHours--;
                    if(vHours < 0) {
                        vHours = 23;
                        vDays--;
                    }
			}
		}
		else {
                if(vSeconds == 0 &&
                   vMinutes == 0 &&
                   vHours == 0 &&
                   vDays == 0) {
                    clearInterval(timer);
		    window.location.reload();
			}
		}
	    }
	    else if(ac_time == 'exp'){
	    vSeconds++;
            if(vSeconds > 59) {
                vSeconds = 0;
                vMinutes++;
                if(vMinutes > 59) {
                    vMinutes = 0;
                    vHours++;
                    if(vHours > 23) {
                        vHours = 0;
                        vDays++;
                    }
                }
            } else {
                if(vSeconds == 0 &&
                   vMinutes == 0 &&
                   vHours == 0 &&
                   vDays == 0) {
                    clearInterval(timer);
		    window.location.reload();
                }
            }
	    }
	    
            eSeconds.html(vSeconds);
            eMinutes.html(vMinutes);
            eHours.html(vHours);
            eDays.html(vDays);
	    
	    	if(vDays == 0){
		eDays.hide();
		jQuery('#wdm_days_text').html(' ');
	}
	else if(vDays == 1 || vDays == -1){
		eDays.show();
		jQuery('#wdm_days_text').html(' <?php _e("day", "wdm-ultimate-auction");?> ');
	}
	else{
		eDays.show();
		jQuery('#wdm_days_text').html(' <?php _e("days", "wdm-ultimate-auction");?> ');
	}
	    
	if(vHours == 0){
		eHours.hide();
		jQuery('#wdm_hrs_text').html(' ');
	}
	else if(vHours == 1 || vHours == -1){
		eHours.show();
		jQuery('#wdm_hrs_text').html(' <?php _e("hour", "wdm-ultimate-auction");?> ');
	}
	else{
		eHours.show();
		jQuery('#wdm_hrs_text').html(' <?php _e("hours", "wdm-ultimate-auction");?> ');
	}
	       
	if(vMinutes == 0){
		eMinutes.hide();
		jQuery('#wdm_mins_text').html(' ');
	}
	else if(vMinutes == 1 || vMinutes == -1){
		eMinutes.show();
		jQuery('#wdm_mins_text').html(' <?php _e("minute", "wdm-ultimate-auction");?> ');
	}
	else{
		eMinutes.show();
		jQuery('#wdm_mins_text').html(' <?php _e("minutes", "wdm-ultimate-auction");?> ');
	}
	       
	if(vSeconds == 0){
		eSeconds.hide();
		jQuery('#wdm_secs_text').html(' ');
	}
	else if(vSeconds == 1 || vSeconds == -1){
		eSeconds.show();
		jQuery('#wdm_secs_text').html(' <?php _e("second", "wdm-ultimate-auction");?>');
	}
	else{
		eSeconds.show();
		jQuery('#wdm_secs_text').html(' <?php _e("seconds", "wdm-ultimate-auction");?>');
	}
	
        }, 1000);
	
});
		</script>
		<?php
	}
	}
	else{
		//file auction listing page
		require_once('auction-feeder-page.php');	
	}
	
	$auc_sc = ob_get_contents();
	
	ob_end_clean();
	
	return $auc_sc;
}
//shortcode to display entire auction posts on the site
add_shortcode('wdm_auction_listing', 'wdm_auction_listing');

function wdm_get_mime_type($url){
       global $wpdb;
       
       $mime = $wpdb->get_var( $wpdb->prepare( 
	"
		SELECT post_mime_type 
		FROM $wpdb->posts 
		WHERE guid = %s
	", 
	$url
) );
       
       return $mime;
}
?>