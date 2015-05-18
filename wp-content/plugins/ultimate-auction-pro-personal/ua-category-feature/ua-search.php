<?php
require_once('auto-suggest.php');
function wdmua_auctions_search($search_html, $cat){
    $search_html .= '<li id="wdm_ua_s_container"><form id="wdmua_search" name="wdmua_search" method="GET" action="">';
    
    if(isset($_GET['page_id']) && !empty($_GET['page_id']))
        $search_html .= '<input type="hidden" name="page_id" value="'. $_GET['page_id'] . '" />';
    
    $ua_s = isset($_GET['ua_s']) ? $_GET['ua_s'] : '';
    
    $search_html .= '<input type="text" name="ua_s" class="wdm_ua_s_elem" id="ua_wdm_auction_search" placeholder="'.__("Search", "wdm-ultimate-auction").'" value="'.$ua_s.'" />';
    
    if(!empty($cat)){
        $search_html .= '<select class="wdm_ua_s_elem" id="wdm-ua-cat-select" name="ua-cat">';
    
        $search_html .= '<option value="">'.__("All Categories", "wdm-ultimate-auction").'</option>';
    
        $s = '';
            foreach($cat as $amc){
                if(isset($_GET['ua-cat']) && !empty($_GET['ua-cat']))
                $s = ($_GET['ua-cat'] === $amc['slug']) ? "selected='selected'" : "";
            
            $search_html .= '<option value="'.$amc['slug'].'" '.$s.'>'.$amc['name'].'</option>';    
        }
    
        $search_html .= '</select>';
    }
    
    $listing = isset($_GET['listing']) ? $_GET['listing'] : '';
    
    $search_html .= '<input type="hidden" name="listing" class="wdm_ua_s_elem" id="ua_wdm_auctions_list" value="'.$listing.'" />';
    
    $search_html .= '<input type="submit" class="wdm_ua_s_elem" id="ua_wdm_search_btn" style="padding: 0;" value="'.__("Search", "wdm-ultimate-auction").'" />';
    $search_html .= '</form></li>';
    
    if(isset($_GET['ua-cat']) || isset($_GET['ua-scat']) || isset($_GET['ua_s']))
        $search_html .= '<li class="wdm_ua_search_res_text"><span></span> '.__('results found').'</li>';
    
    return $search_html;
}

add_filter('wdm_ua_auctions_search', 'wdmua_auctions_search', 99, 2);

function wdm_filter_where_custom( $where = '' ) {
    global $wpdb;
    
    if(isset($_GET['ua_s']) && !empty($_GET['ua_s']))
        $where .= " AND (post_title LIKE '%".$_GET['ua_s']."%' OR ID IN(SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'wdm_ua_auction_tags' AND meta_value LIKE '%".$_GET['ua_s']."%'))";
    
    return $where;
}

add_action('wdm_ua_before_get_auctions', 'wdm_before_get_auctions');

function wdm_before_get_auctions(){
    add_filter( 'posts_where' , 'wdm_filter_where_custom' );
}

add_action('wdm_ua_after_get_auctions', 'wdm_after_get_auctions');

function wdm_after_get_auctions(){
    remove_filter( 'posts_where' , 'wdm_filter_where_custom' );
}

add_filter('wdm_ua_select_cat_text', 'wdm_select_cat_text', 99, 1);

function wdm_select_cat_text($drp_text){
    $drp_text = __('Shop by Category', 'wdm-ultimate-auction');
    return $drp_text;
}

add_filter('wdm_ua_after_product_desc', 'wdm_ua_auction_tags', 100, 1);

function wdm_ua_auction_tags($tag_text){
    
    global $post_id;
    
    if($post_id){
        if(isset($_POST['ua_auction_tags'])){
            update_post_meta($post_id, 'wdm_ua_auction_tags', $_POST["ua_auction_tags"]);
        }
    }
    
    $stags =  ""; 
     
    if(!empty($post_id))
	$stags = get_post_meta($post_id, 'wdm_ua_auction_tags', true);
    elseif(isset($_POST["update_auction"]) && !empty($_POST["update_auction"]))
	$stags = get_post_meta($_POST["update_auction"], 'wdm_ua_auction_tags', true);
    elseif(isset($_GET["edit_auction"]) && !empty($_GET["edit_auction"]))
	$stags = get_post_meta($_GET["edit_auction"], 'wdm_ua_auction_tags', true);
        
     $tag_text .= '<tr valign="top">';
        
        $tag_text .= '<th scope="row">
            <label for="wdm_auction_tags">'.__('Search Tags','wdm-ultimate-auction').'</label>
        </th>';
        
        $tag_text .= '<td><textarea id="wdm_auction_tags" class="regular-text ua_thin_textarea_field" name="ua_auction_tags">';
           
        $tag_text .= $stags;   
           
        $tag_text .= '</textarea>';
           
	$tag_text .= '<div class="ult-auc-settings-tip">'.__('Enter comma separated search tags.', 'wdm-ultimate-auction').'</div></td>';
            
        $tag_text .= '</tr>';
        
    return $tag_text;
}

//add_filter('wdm_ua_filtered_counts', 'wdm_filtered_auc_counts', 99, 1);

//function wdm_filtered_auc_counts($count_query){
//    global $wpdb;
//    
//    if(isset($_GET['ua_s']) && !empty($_GET['ua_s']))
//        $count_query .= " AND (post_title LIKE '%".$_GET['ua_s']."%' OR ID IN(SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'wdm_ua_auction_tags' AND meta_value LIKE '%".$_GET['ua_s']."%'))";
//    
//    return $count_query;
//}
?>