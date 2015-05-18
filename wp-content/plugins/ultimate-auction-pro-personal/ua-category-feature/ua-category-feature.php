<?php
require_once('category-menu.php');
 
add_action('init', 'wdm_ua_register_category');
    
function wdm_ua_register_category(){
        $labels = array(
		'name'              => _x( 'Auction Categories', 'taxonomy general name' ),
		'singular_name'     => _x( 'Auction Category', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Auction Categories' ),
		'all_items'         => __( 'All Auction Categories' ),
		'parent_item'       => __( 'Parent Auction Category' ),
		'parent_item_colon' => __( 'Parent Auction Category:' ),
		'edit_item'         => __( 'Edit Auction Category' ),
		'update_item'       => __( 'Update Auction Category' ),
		'add_new_item'      => __( 'Add New Auction Category' ),
		'new_item_name'     => __( 'New Auction Category Name' ),
		'menu_name'         => __( 'Auction Category' ),
	);
    
        $args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'auctions-category' ),
	);
    
        register_taxonomy( 'ua-auction-category', 'ultimate-auction', $args );
}
    
    add_action('ua_add_submenu_after_setting', 'wdm_ua_show_category', 10, 3);
    
    function wdm_ua_show_category($parent, $cap, $function){

        add_submenu_page($parent, __('Categories','wdm-ultimate-auction'), __('Categories','wdm-ultimate-auction'), 'administrator',  'edit-tags.php?taxonomy=ua-auction-category&post_type=ultimate-auction');
    }
 
     add_action('ua_add_tab_after_setting', 'wdm_cat_section_new_tab', 10, 4);

    function wdm_cat_section_new_tab( $page, $class1, $class2, $active_tab)
    {
    
    if($active_tab == 'categories')
        $class = $class2;
    else
        $class = '';
        
    $tab  = '<a href="'.admin_url('edit-tags.php?taxonomy=ua-auction-category&post_type=ultimate-auction').'"';
    $tab .= ' class="'.$class1.' '.$class.'"';
    $tab .= '>'.__('Categories', 'wdm-ultimate-auction').'</a>';
    
    echo $tab;
    
    }
    
    add_filter('wdm_ua_after_product_desc', 'wdm_ua_category_list', 99, 1);
    
    function wdm_ua_category_list($after_thumb){
	
    //save categories
    global $post_id;
    $term_ids = array();
    $select_term = "";
    $select_cterm = "";
    $auc_terms = array();
    $categories = array();
    $sub_categories = array();
    
    if($post_id){ 
	
	if(isset($_POST['ua_auction_category'])){
	
		$auc_term = term_exists($_POST['ua_auction_category'], 'ua-auction-category', 0);
		$term_ids[] = $auc_term['term_id'];
		
	    if(isset($_POST['ua_auction_subcategory'])){
		$auc_cterm = term_exists($_POST['ua_auction_subcategory'], 'ua-auction-category', $_POST['ua_auction_category']);
		$term_ids[] = $auc_cterm['term_id'];
	    }
	    
	    wp_set_post_terms($post_id, $term_ids, 'ua-auction-category');
	}
	
    }
    
    if(!empty($post_id))
	$auc_terms = wp_get_post_terms( $post_id, 'ua-auction-category', array("fields" => "all"));
    elseif(isset($_POST["update_auction"]) && !empty($_POST["update_auction"]))
	$auc_terms = wp_get_post_terms( $_POST["update_auction"], 'ua-auction-category', array("fields" => "all"));
    elseif(isset($_GET["edit_auction"]) && !empty($_GET["edit_auction"]))
	$auc_terms = wp_get_post_terms( $_GET["edit_auction"], 'ua-auction-category', array("fields" => "all"));
	
	foreach($auc_terms as $auc_tm){
	    if($auc_tm->parent == 0)
		$select_term = $auc_tm->name;
	    else
		$select_cterm = $auc_tm->name;
	}
	
	//main category
        $args = array(
	'parent'                   => 0,
	'orderby'                  => 'name',
	'order'                    => 'ASC',
	'hide_empty'               => 0,
	'pad_counts'               => false 
        );
	
	$categories = get_terms('ua-auction-category', $args);
	
	if(empty($categories))
	    return "";
	
	//sub category
	if(!empty($select_term)){
	    $get_term = get_term_by('name', $select_term, 'ua-auction-category', ARRAY_A);
		
	    $arg = array(
	    'child_of'                 => $get_term['term_id'],
	    'orderby'                  => 'name',
	    'order'                    => 'ASC',
	    'hide_empty'               => 0,
	    'pad_counts'               => false 
	    );
	
	    $sub_categories = get_terms('ua-auction-category', $arg);
	    
	}
	
        $after_thumb = '<tr valign="top">';
        
        $after_thumb .= '<th scope="row">
            <label for="auction_category">'.__('Auction Category','wdm-ultimate-auction').'</label>
        </th>';
        
        $after_thumb .= '<td><select id="auction_category" name="ua_auction_category">';
        $option = '<option value="">'.esc_attr(__('Select Category', 'wdm-ultimate-auction')).'</option>';
                
        foreach ($categories as $category) {
            $selected = ($select_term === $category->name) ? 'selected="selected"' : '';
            $option .= '<option value="'.$category->name.'" data-id='.$category->term_id.' '.$selected.'>';
            $option .= esc_attr($category->name);
            $option .= '</option>';
        }
            
        $after_thumb .= $option;
        
        $after_thumb .= '</select>';
        
        $after_thumb .= '<select id="auction_subcategory" name="ua_auction_subcategory">';
	
	if(!empty($select_term) && empty($sub_categories))
	    $disp = __('No Sub Categories', 'wdm-ultimate-auction');
	else
	    $disp = __('Select a Sub Category', 'wdm-ultimate-auction');
    
        $option = '<option value="">'.esc_attr($disp).'</option>';
    
        foreach ($sub_categories as $sub_cat) {
	    
	    $selected = ($select_cterm === $sub_cat->name) ? 'selected="selected"' : '';
	    $option .= '<option value="'.$sub_cat->name.'" data-id='.$sub_cat->term_id.' '.$selected.'>';
	    $option .= esc_attr($sub_cat->name);
	    $option .= '</option>';
	}
            
        $after_thumb .= $option;
        
        $after_thumb .= '</select>';
            
	$after_thumb .= '<div class="ult-auc-settings-tip">'.__('Select auction categories.', 'wdm-ultimate-auction').'</div></td>';
            
        $after_thumb .= '</tr>';
        
     ?>   
        <script type="text/javascript">
        jQuery(document).ready(function($){
        var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
        
        $('#auction_category').change(function(){
        
        var data = {
		action:'auction_sub_cats',
                auc_cat_id: $('#auction_category option:selected').attr('data-id')
	    };
	    $.post(ajaxurl, data, function(response) {
		
		$('#auction_subcategory').html(response);
                //alert(response);
	    });
            
        return false;
        });
        });
        </script>
<?php
        return $after_thumb;
    }
 
 //fetch sub categories
function list_sub_cats_callback()
{
    if(empty($_POST['auc_cat_id']) || $_POST['auc_cat_id'] === ''){
	$option = '<option value="">'.esc_attr(__('Select a Sub Category', 'wdm-ultimate-auction')).'</option>';
    }
    else{
    $arg = array(
	'child_of'                 => $_POST['auc_cat_id'],
	'orderby'                  => 'name',
	'order'                    => 'ASC',
	'hide_empty'               => 0,
	'pad_counts'               => false 
        );
    
    $sub_categories = get_terms('ua-auction-category', $arg);
    
    if(empty($sub_categories))
	$disp = __('No Sub Categories', 'wdm-ultimate-auction');
    else
	$disp = __('Select a Sub Category', 'wdm-ultimate-auction');
	
    $option = '<option value="">'.esc_attr($disp).'</option>';
    
    foreach ($sub_categories as $sub_cat) {   
            $option .= '<option value="'.$sub_cat->name.'" data-id='.$sub_cat->term_id.'>';
            $option .= esc_attr($sub_cat->name);
            $option .= '</option>';
    } 
    }
    
    echo $option;	
    
    die();
}

add_action('wp_ajax_auction_sub_cats', 'list_sub_cats_callback');
add_action('wp_ajax_nopriv_auction_sub_cats', 'list_sub_cats_callback');

add_filter('wdm_ua_before_single_auction', 'wdm_ua_show_breadcrumb', 99, 2);

function wdm_ua_show_breadcrumb($brd_crmb, $auc_id){
	?>
	    <style type="text/css">
		/*breadcrumb styling*/
		a.ua_breadcrumb_link, .ua_breadcrumb_arr{text-decoration: none !important; display: inline-block !important;}
		a.ua_breadcrumb_link:hover{text-decoration: underline !important;}
		.wdm_clear{clear: both; display: block; height: 0; margin: 10px; overflow: hidden; visibility: hidden; width: 0;}
	    </style>
	<?php
	
	$brd_crmb = '';
	$brd_url = '';

	$symb = '<span class="ua_breadcrumb_arr">&nbsp;&raquo;&nbsp;</span>';
	
	$url = get_permalink();
	
	$brd_crmbs = get_the_terms($auc_id, 'ua-auction-category');
	
	if(!empty($brd_crmbs)){
	    $brd_slug = '';
	    $brd_name = '';
	    
	    foreach($brd_crmbs as $bcm){
		
		if($bcm->parent > 0){
		    $brd_slug = $bcm->slug;
		    $brd_name = $bcm->name;
		}
		else{
		    $brd_par = add_query_arg('ua-cat', $bcm->slug, $url);
		    $brd_url = $symb.'<a class="ua_breadcrumb_link" href="'.$brd_par.'">'.$bcm->name.'</a>';
		}
	    }
	    
	    if(!empty($brd_slug) && !empty($brd_name))
		$brd_url .= $symb.'<a class="ua_breadcrumb_link" href="'.add_query_arg('ua-scat', $brd_slug, $brd_par).'">'.$brd_name.'</a>';
	    
	    $brd_crmb = $brd_url;
	}
	
	$brd_crmb = '<a class="ua_breadcrumb_link" href="'.$url.'">'.get_the_title().'</a>'.$brd_crmb.$symb.'<span class="ua_breadcrumb_link">'.get_the_title($auc_id).'</span>';
	
	$brd_crmb = '<div id="wdm_ua_breadcrumb">'.$brd_crmb.'</div><div class="wdm_clear"></div>';
	
	return $brd_crmb;
}
?>