<?php
add_filter('wdm_ua_before_auctions_listing', 'wdm_category_menu_listing', 99, 1);
	
function wdm_category_menu_listing($show_data){
		
	wp_enqueue_style('wdm_cat_megamenu_css', plugins_url('css/dcmegamenu.css', __FILE__));
		
	wp_enqueue_script('wdm_cat_him_js', plugins_url('js/jquery.hoverIntent.minified.js', __FILE__), array('jquery'));
	wp_enqueue_script('wdm_cat_megamenu_js', plugins_url('js/jquery.dcmegamenu.1.3.3.js', __FILE__), array('jquery'));

	$cols = get_option('wdm_category_menu_col');
	$cat_url = get_option('wdm_category_page_url');
	
	$cat_url = !empty($cat_url) ? add_query_arg('all_cat', 'show', $cat_url) : '';
	$show_sub_cat = get_option('wdm_show_sub_cat');
	
	define('MAX_CAT_SHOW', 9);
	define('MAX_SUBCAT_SHOW', 3);
	?>
		
		<script type="text/javascript">
			jQuery(document).ready(function($){
				
			var cols = '<?php echo $cols;?>';
			var row_itm = '';
			
			if (cols != '' && cols != null) {
			    row_itm = cols;
			}
			else{
			    row_itm = '3';
			}
			
				$('#wdm-mega-menu').dcMegaMenu({
						rowItems: row_itm,
						speed: 'fast',
						effect: 'fade',
						event: 'click'
				});

			});
		</script>
		
	<?php
	//get categories
	$args = array(
	'parent'                   => 0,
	'orderby'                  => 'name',
	'order'                    => 'ASC',
	'hide_empty'               => 0,
	'pad_counts'               => false 
        );
	
	$categories = array();
	$sub_categories = array();

	$categories = get_terms('ua-auction-category', $args);
	$cat_count = count($categories);
	//if(empty($categories))
	//	return "";

	$list = '';
	$olist = '';
	$curr_url = get_permalink();
	$all_main_cat = array();
	
	if(isset($_REQUEST['listing']) && !empty($_REQUEST['listing'])) 
		$curr_url = add_query_arg('listing', $_REQUEST['listing'], $curr_url);
	 
	if($cat_count > 9)
		$cat_cnt = 1;
	else	
		$cat_cnt = 0;
	
	$sc_cnt = 0;
	foreach($categories as $cat){
		
		$url = add_query_arg('ua-cat', $cat->slug, $curr_url);
		
		if($cat_cnt < MAX_CAT_SHOW):
		
		$list .= '<li><a href="'.$url.'" class="wdm_cat_single" id="wdm_cat_'.$cat->term_id.'">'.$cat->name.'</a>';
		
		$list .= '<ul>';
		
		if($show_sub_cat !== 'No'):
		
			$arg = array(
	    'child_of'                 => $cat->term_id,
	    'orderby'                  => 'name',
	    'order'                    => 'ASC',
	    'hide_empty'               => 0,
	    'pad_counts'               => false 
	    );
		 
			$sub_categories = get_terms('ua-auction-category', $arg);
		
			$scat_cnt = 0;
			foreach($sub_categories as $scat){
				
					$surl = add_query_arg('ua-scat', $scat->slug, $url);
					$list .= '<li><a href="'.$surl.'" class="wdm_cat_single" id="wdm_cat_'.$scat->term_id.'">'.$scat->name.'</a></li>';
					$scat_cnt++;
					
					if($scat_cnt >= MAX_SUBCAT_SHOW)
						break;
			}
		
		endif;
		
		$list .= '</ul>';
		$list .= '</li>';
		
		else:
		
		//append other categories list
		if($sc_cnt < MAX_SUBCAT_SHOW){
			$olist .= '<ul><li>';
			$olist .= '<a href="'.$url.'" class="wdm_cat_single" id="wdm_cat_'.$cat->term_id.'">'.$cat->name.'</a>';
			$olist .= '</li>';
			$olist .= '</ul>';
		}
		$sc_cnt++;
		
		endif;
		
		$all_main_cat[] = array('name' => $cat->name, 'slug' => $cat->slug);
		
		$cat_cnt++;
	}
	
	if($cat_cnt > MAX_CAT_SHOW){
			
		$list .= '<li><a href="'.$cat_url.'" class="wdm_cat_single">'.__('Other Categories', 'wdm-ultimate-auction').'</a>';
		if($show_sub_cat !== 'No')
			$list .= $olist;
		$list .= '</li>';
			
	}
		
	$bottom_text = '';
	$bottom_text = '<div class="row wdm-bottom-border"></div>';
	//$cat_url = get_option('wdm_category_page_url');
	if(!empty($cat_url)){
		//$cat_url = add_query_arg('all_cat', 'show', $cat_url);
		$bottom_text .= '<div class="wdmua_all_cat_show"><a href="'.$cat_url.'">'.__('Show All Categories', 'wdm-ultimate-auction').'</a></div>';
	}
	
	if((isset($_REQUEST['ua-cat']) && !empty($_REQUEST['ua-cat'])) || (isset($_REQUEST['ua-scat']) && !empty($_REQUEST['ua-scat']))){
			$bottom_text .= '<div class="wdmua_all_auc_show"><a href="'.$curr_url.'">'.__('Show All Auctions', 'wdm-ultimate-auction').'</a></div>';
			
			//$bottom_text .= '<div class="wdm_clear"></div>';
			
			echo "<script type='text/javascript'>
			jQuery(document).ready(function($){
			$('html, body').animate({
				scrollTop: $('#wdmua_wrap_container').offset().top - ($('body').offset().top + 10)
			}, 'fast');
			});</script>";
		}
		
		if(isset($_REQUEST['ua-scat']) && !empty($_REQUEST['ua-scat'])){
			$drp_text = get_term_by('slug', $_REQUEST['ua-scat'], 'ua-auction-category');
			$drp_text = $drp_text->name;
		}
		elseif(isset($_REQUEST['ua-cat']) && !empty($_REQUEST['ua-cat'])){
			$drp_text = get_term_by('slug', $_REQUEST['ua-cat'], 'ua-auction-category');
			$drp_text = $drp_text->name;
		}
		else
			$drp_text = __('Shop by Category', 'wdm-ultimate-auction');
			
		$drp_text = apply_filters('wdm_ua_select_cat_text', $drp_text);		
		
		$bottom_text .= '<div class="wdm_clear"></div>';
		
		$show_data = '<div id="wdmua_wrap_container" class="wdmua_wrap">
<div class="demo-container">

<div class="wdm_ua_mgmn">  
<ul id="wdm-mega-menu" class="mega-menu">';

if(!empty($categories))
	$show_data .= '<li id="wdm_ua_s_catmenu"><a class="wdmua_select_cat" href="#">'.$drp_text.'</a>
		<ul>
			'.$list.' '.$bottom_text.'
		</ul>
	</li>';
	
	$show_data = apply_filters('wdm_ua_auctions_search', $show_data, $all_main_cat);
	
$show_data .= '</ul>
</div>
</div>
</div>';

return $show_data;
}

add_filter( 'pre_get_posts', 'wdm_ua_filter_auctions' );

function wdm_ua_filter_auctions($query){
	
	if(isset($query->query_vars['post_type']) && $query->query_vars['post_type'] === 'ultimate-auction'){
		
		$ucat = "";
		
		if(isset($_REQUEST['ua-scat']) && !empty($_REQUEST['ua-scat'])){
			$ucat = $_REQUEST['ua-scat'];
		}
		elseif(isset($_REQUEST['ua-cat']) && !empty($_REQUEST['ua-cat'])){
			$ucat = $_REQUEST['ua-cat'];
		}
		
		if(!empty($ucat)){
			$cat_query = array(array('taxonomy' => 'ua-auction-category','field' => 'slug','terms' => $ucat));
			$query->set('tax_query', $cat_query);
		}
		else{
                     if( isset( $GLOBALS['gb_cat_args'] ) ){
                            $gb_cat_args = $GLOBALS['gb_cat_args'];
                            if(isset($gb_cat_args['ua_scat']) && !empty($gb_cat_args['ua_scat'])){
                                $ucat = $gb_cat_args['ua_scat'];
                            }
                            elseif(isset($gb_cat_args['ua_cat']) && !empty($gb_cat_args['ua_cat'])){
                                    $ucat = $gb_cat_args['ua_cat'];
                            }
                            if(!empty($ucat)){
                                    $cat_query = array(array('taxonomy' => 'ua-auction-category','field' => 'slug','terms' => $ucat));
                                    $query->set('tax_query', $cat_query);
                            }
                     }
                }
	}
	
	return $query;
}

//add_filter('wdm_ua_filtered_auctions', 'wdm_prepare_filtered_auctions', 99, 1);

//function wdm_prepare_filtered_auctions($filter_query){
//	
//	global $wpdb;
//		
//	$ucat = "";
//	
//	if(isset($_REQUEST['ua-scat']) && !empty($_REQUEST['ua-scat'])){
//		$ucat = $_REQUEST['ua-scat'];
//	}
//	elseif(isset($_REQUEST['ua-cat']) && !empty($_REQUEST['ua-cat'])){
//		$ucat = $_REQUEST['ua-cat'];
//	}
//		
//	if(!empty($ucat)){
//		$filter_query = "SELECT a.object_id FROM
//		".$wpdb->prefix."term_relationships a JOIN ".$wpdb->prefix."term_relationships b
//		ON a.object_id = b.object_id
//		WHERE a.term_taxonomy_id = (SELECT term_id
//		FROM ".$wpdb->prefix."terms
//		WHERE slug = 'live')
//		AND b.term_taxonomy_id = (SELECT term_id
//		FROM ".$wpdb->prefix."terms
//		WHERE slug = '".$ucat."')";
//	}	
//	
//	return $filter_query;
//}

add_filter('wdm_modify_auctions_url', 'wdm_prepare_cat_url', 99, 1);

function wdm_prepare_cat_url($url){
	
	if(isset($_REQUEST['ua-cat']) && !empty($_REQUEST['ua-cat'])){
		$url = add_query_arg('ua-cat', $_REQUEST['ua-cat'], $url);
		
	}
	
	if(isset($_REQUEST['ua-scat']) && !empty($_REQUEST['ua-scat'])){
		$url = add_query_arg('ua-scat', $_REQUEST['ua-scat'], $url);
	}
	
	if(isset($_REQUEST['ua_s']) && !empty($_REQUEST['ua_s'])){
		$url = add_query_arg('ua_s', $_REQUEST['ua_s'], $url);
	}
	
	return $url;
}

require_once('ua-search.php');
require_once('ua-all-categories.php');
?>