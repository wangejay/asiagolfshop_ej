<?php
add_action( 'wp_enqueue_scripts', 'wdm_enqueue_search_ss' );

function wdm_enqueue_search_ss(){
    wp_register_script('wdmua_auto_suggest', plugins_url('/js/wdm-auto-suggest.js', __FILE__), array('jquery'));
    wp_enqueue_script('wdmua_auto_suggest');
    
    wp_register_script('wdmua_auto_suggestlib', plugins_url('/js/jquery.autocomplete.js', __FILE__), array('jquery'));
    wp_enqueue_script('wdmua_auto_suggestlib');
    
    wp_register_style('wdmua_auto_suggestcss', plugins_url('/css/styles.css', __FILE__));
    wp_enqueue_style('wdmua_auto_suggestcss');
    
    $trans_arr = array('ajxurl' => admin_url('admin-ajax.php'));
    wp_localize_script( 'wdmua_auto_suggest', 'wdm_ua_obj_sr', $trans_arr );
}

function wdm_search_tags_callback()
{	
    $args = array(
		'posts_per_page'=> -1,
		'post_type'	=> 'ultimate-auction',
		'auction-status'  => 'live',
		'post_status' => 'publish'
		);

    $warr = get_posts($args);
    
    $args_e = array(
		'posts_per_page'=> -1,
		'post_type'	=> 'ultimate-auction',
		'auction-status'  => 'expired',
		'post_status' => 'publish',
		'suppress_filters' => false
		);
    
    add_filter( 'posts_where', 'wdm_filter_where_exp' );
    $warr_e = get_posts($args_e);
    remove_filter( 'posts_where', 'wdm_filter_where_exp' );
    
    $mixed = array();
    
    $mixed = array_merge($warr, $warr_e);
	
    $tag = array();
    $tags = array();
    
    foreach($mixed as $wa)
    {
	if(!in_array(trim($wa->post_title), $tags))
	    $tags[] = trim($wa->post_title);
	
	$str = get_post_meta($wa->ID, 'wdm_ua_auction_tags', true);
	if(!empty($str)){
	    
	    $tag = explode(",", $str);
	    
		if(!empty($tag)){
		    foreach($tag as $t){
			//if((strpos($t, $_POST['st_txt']) === 0) || strpos($t, " ".$_POST['st_txt']) === 0)
			if(!in_array(trim($t), $tags))
			    $tags[] = trim($t);
		}
	    }
	}
    }

    echo json_encode($tags);
    
    die();
}

add_action('wp_ajax_auction_stags', 'wdm_search_tags_callback');
add_action('wp_ajax_nopriv_auction_stags', 'wdm_search_tags_callback');
?>