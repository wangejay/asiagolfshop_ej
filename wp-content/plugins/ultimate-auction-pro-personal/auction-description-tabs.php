<div id="wdm-tab-anchor-id"></div>
<div id="auction-desc-tabs">
  <ul id="auction-tab-titles">
    <li id="wdm-desc-aucdesc-link"><?php _e('Description','wdm-ultimate-auction');?></li>
    <?php do_action('wdm_ua_add_ship_tab', $wdm_auction->ID); ?>
    <?php if(get_option('wdm_comment_set')=="Yes"){?>
    <li id="wdm-desc-cmt-link"><?php _e('Comments', 'wdm-ultimate-auction');?></li>
    <?php } ?>
    <?php if(get_option('wdm_show_prvt_msg')=="Yes"){?>
    <li id="wdm-desc-msg-link"><?php _e('Send Private Message', 'wdm-ultimate-auction');?></li>
    <?php } ?>
    <?php if(get_option('wdm_show_total_bids_placed')=="Yes"){?>
    <li id="wdm-desc-bids-link"><?php _e('Total bids placed', 'wdm-ultimate-auction');?></li>
    <?php } ?>
    <?php //if(get_option('wdm_show_terms_and_conditions')=="Yes"){?>
    <!--<li id="wdm-desc-terms-link"><?php _e('Terms', 'wdm-ultimate-auction');?></li>-->
    <?php //} ?>
        <?php if( get_post_meta( $wdm_auction->ID, "wdm_enable_best_offers", true ) && ((is_user_logged_in() && $curr_user->ID != $wdm_auction->post_author) || !is_user_logged_in() )) {

	  $ua_active_terms = wp_get_post_terms($wdm_auction->ID, 'auction-status',array("fields" => "names"));

          if(!((time() >= strtotime(get_post_meta($wdm_auction->ID,'wdm_listing_ends',true))) || in_array('expired',$ua_active_terms))){ ?>

    <li id="wdm-best-offers-link"><?php _e('Best Offers', 'wdm-ultimate-auction'); ?></li>

    <?php }

	  } ?>

  </ul>
  
  <div id="wdm-desc-aucdesc-tab" class="auction-tab-container">
    <div class="wdm-single-auction-description">
      <?php
	  $ext_desc = "";
	  $ext_desc = apply_filters('wdm_single_auction_extra_desc', $ext_desc);
	  echo $ext_desc;
	  echo apply_filters('the_content', $wdm_auction->post_content);
	  
	  $custom_fields = array();	
	  $custom_fields = get_option('wdm_custom_field');	
	  $count = count($custom_fields);	
	  $custom_meta_field = get_post_meta( $wdm_auction->ID, 'wdm_custom_field', true );
	
	  for($init=0;$init<$count;$init++){
	    
	    if(!empty($custom_meta_field[$init])){	
	      echo '<strong>'.$custom_fields[$init]['label'].'</strong>';
	      if(!empty($custom_fields[$init]['label']))
	      echo '   : ';	
	      echo $custom_meta_field[$init];
	      echo '<br />';
	      ?>	
	      <?php	
	    }	
	  }
      ?>
    </div>
  </div>
  <?php if(get_post_meta($wdm_auction->ID, 'wdm_enable_shipping', true) == "1"){ ?>
  <div id="wdm-desc-ship-tab" class="auction-tab-container" style="display: none;overflow: hidden;">
      <div class="wdm-ship-info clear">
	<?php do_action('ua_add_shipping_cost_view_field', $wdm_auction->ID); //SHP-ADD hook to add new product data ?>
      </div>  
  </div>
  <?php } ?>
  
  <?php if(get_option('wdm_comment_set')=="Yes"){?>
  <div id="wdm-desc-cmt-tab" class="auction-tab-container" style="display: none;">
    <?php
    $comm_args = array(
    'post_id' => $wdm_auction->ID
    );
    $comment_details=get_comments($comm_args);
    $comment_count=get_comments(array('post_id'=>$wdm_auction->ID,'count'=>true));
    if(isset($comment_details) && $comment_count>0)
    {
        if($comment_count == 1)
	  echo $comment_count.__(' comment for this Auction', 'wdm-ultimate-auction');
        else
	  echo $comment_count.__(' comments for this Auction', 'wdm-ultimate-auction');
	  
        $i=0;
        $level=1;
        $top=0;
        $is_parent=false;
        $com_parent=array();
        $max_depth=get_option('thread_comments_depth');
        for($i=0;$i<$comment_count;$i++)
        {
            if($comment_details[$i]->comment_parent == 0)
            {
                ?>
                <div class='level<?= $level ?>'>
                    <div class='ua_gavatar'>
                        <?php echo get_avatar($comment_details[$i],44); ?>
                    </div>
                    <div class='ua_comment_author'>  
                        <?= $comment_details[$i]->comment_author; ?><br /> <span class='ua_comment_date'><?= comment_date('F j, Y g:i a', $comment_details[$i]->comment_ID); ?></span>
                    </div> 
                    <div class='ua_comment_content'><?= $comment_details[$i]->comment_content  ?> </div>
                    <br />
                    <span class='ua_comment_reply' style='padding-left: 50px;text-decoration: none'><?php comment_reply_link(array('add_below'=>'comment'.$comment_details[$i]->comment_ID,'depth'=>$level,'max_depth'=>5),$comment_details[$i]->comment_ID,$wdm_auction->ID );
                        if (current_user_can( 'edit_comment', $comment_details[$i]->comment_ID ) )
                        {
                        ?>                 
                        <a href="<?php echo get_bloginfo('url').'/wp-admin/comment.php?action=editcomment&c='.$comment_details[$i]->comment_ID ?>" title="<?php _e('Edit this comment', 'wdm-ultimate-auction');?>"><span><?php _e('edit comment', 'wdm-ultimate-auction');?></span></a>
                        <?php } ?>
                    </span>
                </div>
                <?php
                $com_parent[++$top]=$i;
                while($top >= 0)
                {
                    if(isset($com_parent[$top]))
                     $top_element=$com_parent[$top];
                     $is_parent=false;
                    for($k=0;$k < $comment_count;$k++)
                    {
                        if(isset($comment_details[$k]) && isset($comment_details[$top_element]) && ($comment_details[$k]->comment_parent===$comment_details[$top_element]->comment_ID))
                        {
                            $level++;  
                           ?>
                            <div class='level<?= $level ?>'>
                                <div class='ua_gavatar'>
                                    <?php echo get_avatar($comment_details[$k],44); ?>
                                </div>
                                <div class='ua_comment_author' >  
                                    <?= $comment_details[$k]->comment_author; ?><br /> <span class='ua_comment_date'><?= comment_date('F j, Y g:i a', $comment_details[$k]->comment_ID); ?></span>
                                </div> 
                                <div class='ua_comment_content'><?= $comment_details[$k]->comment_content ?> </div>
                                <br />
                                <span class='ua_comment_reply'><?php comment_reply_link(array('add_below'=>'comment'.$comment_details[$k]->comment_ID,'depth'=>$level,'max_depth'=>$max_depth),$comment_details[$k]->comment_ID,$wdm_auction->ID );
                                    if (current_user_can( 'edit_comment', $comment_details[$k]->comment_ID ) )
                                    {
                                    ?>                 
                                    <a href="<?php echo get_bloginfo('url').'/wp-admin/comment.php?action=editcomment&c='.$comment_details[$k]->comment_ID ?>" title="<?php _e('Edit this comment', 'wdm-ultimate-auction');?>"><?php _e('edit comment', 'wdm-ultimate-auction');?></a>
                                    <?php } ?>
                                </span>
                            </div>
                            <?php
                            $com_parent[++$top]=$k;
                            $comment_details[$k]->comment_parent=-1;
                            $is_parent=true;
                            break;
                        }
                        
                    }
                    if(!$is_parent)
                    {
                        $com_parent[$top]=-1;
                        $top--;
                        if($level>1)
                        $level--;
                    }
                    
                }
            }
            
        }
    }
    else
    {
        _e('No comments for this Auction', 'wdm-ultimate-auction');
    }

    comment_form('',$wdm_auction->ID); 
    
    ?>
  </div>
  <?php }?>
  
  <?php if(get_option('wdm_show_prvt_msg')=="Yes"){?>
  <div id="wdm-desc-msg-tab" class="auction-tab-container" style="display: none;">
    <form id="wdm-auction-private-form" action="">
					
      <label for="wdm-prv-bidder-name"> <?php _e('Name', 'wdm-ultimate-auction');?>: </label>
      <input type="text" id="wdm-prv-bidder-name" />
      <br />
      <label for="wdm-prv-bidder-email"> <?php _e('Email', 'wdm-ultimate-auction');?>: </label>
      <input type="text" id="wdm-prv-bidder-email" />
      <br />
      <label for="wdm-prv-bidder-msg"> <?php _e('Message', 'wdm-ultimate-auction');?>: </label>
      <textarea id="wdm-prv-bidder-msg"></textarea>
      <br />
      <input id="ult-auc-prv-msg" name="ult-auc-prv-msg" type="submit" value="<?php _e('Send', 'wdm-ultimate-auction');?>" />
					
    </form>
 </div>
  <?php require_once('ajax-actions/send-private-msg.php'); } ?>
  
  <?php if(get_option('wdm_show_total_bids_placed')=="Yes"){?>
  <div id="wdm-desc-bids-tab" class="auction-tab-container" style="display: none;">
				<?php
				$query = "SELECT * FROM ".$wpdb->prefix."wdm_bidders WHERE auction_id =".$wdm_auction->ID." ORDER BY id DESC";
				$results = $wpdb->get_results($query);
				if(!empty($results)){
				  ?>
				  <ul class="wdm-recent-bidders">
				    <li><h4><?php _e('Bidder Name', 'wdm-ultimate-auction');?></h4></li>
				    <li><h4><?php _e('Bid Price', 'wdm-ultimate-auction');?></h4></li>
				    <li><h4><?php _e('When', 'wdm-ultimate-auction');?></h4></li>
				  </ul>
				  <?php
					foreach($results as $result){
				$curr_time = time(); 
				$bid_time = strtotime($result->date);
				
				$seconds = $curr_time - $bid_time;
				
					$end_tim = '';
					
					$ago_time = wdm_ending_time_calculator($seconds, $end_tim);
					
						?>
						<ul class="wdm-recent-bidders">
						  <li>
							<?php echo $result->name; ?> 
						  </li>
						  <li>
						    <?php echo $currency_symbol.number_format($result->bid, 2, '.', ',')." ".$currency_code_display; ?>
						  </li>
						  <li>
							<?php printf(__('%s ago', 'wdm-ultimate-auction'), $ago_time); ?>
						  </li>
						</ul>
						<?php
						}	
				}
				
				?>
  </div>
    <div id="wdm-desc-offers-tab" class="auction-tab-container" style="display: none;">

     <?php if(is_user_logged_in()) {

            $ua_bst_ofrs_arr = get_post_meta( $wdm_auction->ID, 'auction_best_offers', true );

            //if( ! is_array( $ua_bst_ofrs_arr ) || empty( $ua_bst_ofrs_arr ) )

            //    $ua_bst_ofrs_arr = array();

            if( is_array($ua_bst_ofrs_arr) && isset( $ua_bst_ofrs_arr[ $auction_bidder_id ] ) ) { ?>

                <br />

                <div class="wdm_offers_sent_text">

                <p><?php _e('Your offer is pending for review by auction owner.', 'wdm-ultimate-auction'); ?></p>

		<p><?php printf(__('Offer amount: %s%s', 'wdm-ultimate-auction'), $currency_code." ", $ua_bst_ofrs_arr[ $auction_bidder_id ][ 'offer_val' ]); ?></p>

                </div>

                <br />

                <?php

            } else { ?>

            <form id="wdm-auction-offers-form" action="">

            <label for="wdm-bidder-offerval" style="width:131px"> <?php _e('Send your best offer', 'wdm-ultimate-auction');?>: </label>

            <input name="wdm-bidder-offerval" type="text" id="wdm-bidder-offerval" style="width:85px" placeholder="in <?php echo $currency_code; ?>"/>

            <br /><br />

            <input id="wdm-send-best-offers" type="submit" value="<?php _e('Send', 'wdm-ultimate-auction');?>" />

            </form>

            <?php } ?>

      

     <?php require_once('ajax-actions/send-best-offers.php'); //file to handle ajax requests related to best offers ?>

     <?php } else { ?>

      <a class="wdm-login-to-bst-ofr login_popup_boxer" href="#ua_login_popup" title="Login"><input type="submit" value="<?php _e('Login', 'wdm-ultimate-auction');?>"/></a>

      <br /><br />

     <?php 

     //echo wdm_ua_add_html_on_feed('offer');

     } ?>

  </div>

 <?php } ?>
 
<!-- <div id="wdm-desc-terms-tab" class="auction-tab-container" style="display: none;">
  
 </div> -->
 
</div>

<?php wp_enqueue_script('wdm_desc_tabs', plugins_url('/js/auction-desc-tabs.js', __FILE__ ), array('jquery'));?>
