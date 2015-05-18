<?php
add_action('wp_footer', 'wdm_email_auction_winner');
add_action('admin_head', 'wdm_email_auction_winner');

function wdm_email_auction_winner(){

global $wpdb;

if(isset( $_GET[ "ult_auc_id" ] ) && !empty($_GET[ "ult_auc_id" ]) ){
 
 $single_auc_id = $_GET[ "ult_auc_id" ];
 
 $active_term = wp_get_post_terms($single_auc_id, 'auction-status',array("fields" => "names"));
 
 if(in_array('live', $active_term) && time() >= strtotime(get_post_meta($single_auc_id,'wdm_listing_ends',true))){
     if(!in_array('expired',$active_term))
     {
      $check_tm = term_exists('expired', 'auction-status');
      wp_set_post_terms($single_auc_id, $check_tm["term_id"], 'auction-status');
     }
  }
}
else{

$all_auc = array(
                'posts_per_page'  => -1,
                'post_type'       => 'ultimate-auction',
		'post_status' => 'publish',
		'fields'        => 'ids',
'tax_query' => array(
		array(
			'taxonomy' => 'auction-status',
			'field'    => 'slug',
			'terms'    => array( 'live', 'scheduled' ),
			'operator' => 'IN'
		)
		)
                );

 $all_auctions = get_posts( $all_auc);
 
 if(!empty($all_auctions)){

    foreach($all_auctions as $single_auc_id){

    $active_term = wp_get_post_terms($single_auc_id, 'auction-status',array("fields" => "names"));
		    
    if(in_array('scheduled',$active_term) && time() >= strtotime(get_post_meta($single_auc_id,'wdm_listing_starts',true)))
    {
				    $check_tm1 = term_exists('live', 'auction-status');
				    wp_set_post_terms($single_auc_id, $check_tm1["term_id"], 'auction-status');
				    update_post_meta($single_auc_id, 'wdm_listing_starts', '');
    }
    elseif(in_array('live',$active_term) && time() >= strtotime(get_post_meta($single_auc_id,'wdm_listing_ends',true))){
				    if(!in_array('expired',$active_term))
				    {
					    $check_tm = term_exists('expired', 'auction-status');
					    wp_set_post_terms($single_auc_id, $check_tm["term_id"], 'auction-status');
				    }
    }
    
    }
 }
}
//$comp_auc = array(
//                'posts_per_page'  => -1,
//                'post_type'       => 'ultimate-auction',
//		'post_status'	=> 'publish',
//                //'auction-status'  => 'expired',
//		'tax_query' => array(
//		array(
//			'taxonomy' => 'auction-status',
//			'field'    => 'slug',
//			'terms'    => array('expired'),
//			'operator' => 'IN'
//		)
//		),
//		'meta_query' => array(
//		array(
//			'key' => 'current_auction_permalink'
//		)
//		),
//		//'fields'        => 'ids'
//                );

//$completed_auctions = get_posts( $comp_auc );

$tax_query = "SELECT object_id
FROM ".$wpdb->prefix."term_relationships
WHERE term_taxonomy_id = (SELECT term_id
FROM ".$wpdb->prefix."terms
WHERE slug = 'expired')";

$meta_query = 'SELECT post_id FROM '.$wpdb->prefix.'postmeta WHERE meta_key = "current_auction_permalink"';

$completed_auctions = $wpdb->get_results("SELECT ID, post_title, post_content FROM $wpdb->posts WHERE post_type = 'ultimate-auction' AND post_status = 'publish' AND ID IN(".$tax_query.") AND ID IN(".$meta_query.") ORDER BY ID DESC");

//echo "<pre>";print_r($completed_auctions);echo "<pre>";

if(!empty($completed_auctions)){

foreach($completed_auctions as $ca){
    
    if(get_post_meta($ca->ID,'auction_email_sent',true) !== 'sent'){

    $bought = get_post_meta($ca->ID,'auction_bought_status',true);
    
    $count_qry = "SELECT COUNT(bid) FROM ".$wpdb->prefix."wdm_bidders WHERE auction_id =".$ca->ID;
    $count_bid = $wpdb->get_var($count_qry);
    
    $was_sent_imd = get_post_meta($ca->ID, 'email_sent_imd', true);
    $is_in_progress = get_post_meta($ca->ID,'wdm_to_be_sent',true);
    
    if($bought !== 'bought' && $count_bid > 0 && $was_sent_imd !== 'sent_imd' /*&& $is_in_progress !== 'in_progress'*/){
			
	  $reserve_price_met = get_post_meta($ca->ID, 'wdm_lowest_bid',true);
	  
	  $winner_bid = "";
          $bid_qry = "SELECT MAX(bid) FROM ".$wpdb->prefix."wdm_bidders WHERE auction_id =".$ca->ID;
          $winner_bid = $wpdb->get_var($bid_qry);
	  
	  if($winner_bid >= $reserve_price_met){
          update_post_meta($ca->ID, 'wdm_to_be_sent', 'in_progress');
          
	  $winner_email  = "";
	  
	  $name_qry = "SELECT name FROM ".$wpdb->prefix."wdm_bidders WHERE bid =".$winner_bid." AND auction_id =".$ca->ID." ORDER BY id DESC";
	  
	  $winner_name = $wpdb->get_var($name_qry);
			    
          $winner = get_user_by('login', $winner_name);
			    
          $winner_email = $winner->user_email;
	
          $return_url = get_post_meta($ca->ID, 'current_auction_permalink',true);
		 wp_enqueue_script('jquery');
		require('ajax-actions/send-email.php');
	  }
	
		}

	    }
	    
        }

     }
}
?>