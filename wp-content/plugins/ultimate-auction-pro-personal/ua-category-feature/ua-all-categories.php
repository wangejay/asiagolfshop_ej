<?php
add_action('wdm_ua_after_auction_page_url_settings', 'wdm_ua_category_page_settings', 10, 2);

function wdm_ua_category_page_settings( $option, $id ){
    
    add_settings_field(
	'wdm_show_sub_id', 
	__('Do you want to show sub-categories inside 1st drop down?', 'wdm-ultimate-auction'), 
	'wdm_show_sub_field', 
	$option, 
	$id 			
    );
    
    add_settings_field(
	'wdm_category_url_id', 
	__('Category Page URL', 'wdm-ultimate-auction'), 
	'wdm_category_url_field', 
	$option, 
	$id 			
    );
	
    add_settings_field(
	'wdm_page_col_id', 
	__('No. of columns for category page (default is 3)', 'wdm-ultimate-auction'), 
	'wdm_page_col_field', 
	$option, 
	$id 			
    );
    
}

//All categories page URL
function wdm_category_url_field(){
	?>
        <input type="text" class="wdm_settings_input url" id="wdm_category_url_id" name="wdm_category_page_url" size="40" value="<?php echo get_option('wdm_category_page_url');?>" />
	<span class="ult-auc-settings-tip"><?php _e("Enter your category page URL.", "wdm-ultimate-auction");?></span>

	<a href="" class="auction_fields_tooltip"><strong><?php _e("?", "wdm-ultimate-auction");?></strong>
	    <span style="width: 370px;margin-left: -90px;">
	    <?php _e("To display 'Show all categories' link on auction feeder page, enter URL of the page where you have used shortcode for categories listing.", "wdm-ultimate-auction");?>
	    <br /><br />
	    <?php _e("NOTE: Whenever you change the permalink, do not forget to enter the modified URL here.", "wdm-ultimate-auction");?>
	    </span>
	</a>
	<br /><br />
	<span class="ult-auc-settings-tip"><?php _e("Use this shortcode in a page to make it category page:", "wdm-ultimate-auction");?></span>
	<?php echo "<code>[wdm_auction_categories]</code>";?>
	<br /><br />
	<span class="ult-auc-settings-tip"><?php _e("NOTE: You must fill 'Auction Page URL' field, in order to make the category page directly accessible via menu or URL.", "wdm-ultimate-auction"); ?></span>
    <?php
}

//show hide sub categories under category mega menu
function wdm_show_sub_field(){
    $options = array("Yes", "No");
	
    add_option('wdm_show_sub_cat','Yes');
	
    foreach($options as $option) {
	$checked = (get_option('wdm_show_sub_cat')== $option) ? ' checked="checked" ' : '';
	echo "<input ".$checked." value='$option' name='wdm_show_sub_cat' type='radio' /> $option <br />";
    }
    
    printf("<div class='ult-auc-settings-tip'>".__("Choose Yes if you want to show sub categories under main categories in category mega menu on feed page.", "wdm-ultimate-auction")."</div>");
}

//no. of columns for all categories page
function wdm_page_col_field(){
    ?>
    <input type="number" min="1" max="5" class="wdm_settings_input number" id="wdm_category_col_id" name="wdm_category_page_col" size="40" value="<?php echo get_option('wdm_category_page_col');?>" />
    <span class="ult-auc-settings-tip"><?php _e("Set number of columns layout to display on category page (range: 1-5).", "wdm-ultimate-auction");?></span>

    <a href="" class="auction_fields_tooltip"><strong><?php _e("?", "wdm-ultimate-auction");?></strong>
	<span style="width: 370px;margin-left: -90px;">
	    <?php _e("This allows you to adjust the layout of category page container according to the space available on it.", "wdm-ultimate-auction");?>
	</span>
    </a>
    <?php
}

add_action('wdm_ua_update_ext_settings', 'wdm_update_ext_settings', 10);

function wdm_update_ext_settings(){

    if(isset($_POST['wdm_category_page_url']))
	update_option('wdm_category_page_url', $_POST['wdm_category_page_url']);
	
    if(isset($_POST['wdm_category_page_col']))
	update_option('wdm_category_page_col', $_POST['wdm_category_page_col']);
	
    if(isset($_POST['wdm_show_sub_cat']))
	update_option('wdm_show_sub_cat', $_POST['wdm_show_sub_cat']);
}

add_shortcode('wdm_auction_categories', 'wdm_show_all_categories');

function wdm_show_all_categories(){

    wp_enqueue_style('wdm_cat_megamenu_css', plugins_url('css/dcmegamenu.css', __FILE__));
    wp_enqueue_script('wdm_cat_megamenu_js', plugins_url('js/jquery.dcmegamenu.1.3.3.js', __FILE__), array('jquery'));
    
    $cols = get_option('wdm_category_page_col');
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
					rowItems: row_itm//,
					//speed: 'fast',
					//effect: 'fade',
					//event: 'click'
			});

		});
	</script>
               
	<style type="text/css">
            
            .wdm-sub{
                display: block !important;
            }
            .wdm_ua_mgmn ul.mega-menu li .sub-container{
                position: static !important;
            }
        </style>
        
    <?php
    if(isset($_GET['all_cat']) && $_GET['all_cat'] == 'show')
        $curr_url = $_SERVER['HTTP_REFERER'];
    else    
        $curr_url = get_option('wdm_auction_page_url');
        
    if(!empty($curr_url)){
        
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
	$all_main_cat = array();
        $list = '';
        
	$categories = get_terms('ua-auction-category', $args);
	
	//if(empty($categories))
	//	return "";

	//if(isset($_REQUEST['listing']) && !empty($_REQUEST['listing'])) 
	//	$curr_url = add_query_arg('listing', $_REQUEST['listing'], $curr_url);
	if( !empty($categories) && is_array($categories) ){
            
            foreach($categories as $cat){
		
                    $url = add_query_arg('ua-cat', $cat->slug, $curr_url);
                    
                    $list .= '<li><a href="'.$url.'" class="wdm_cat_single" id="wdm_cat_'.$cat->term_id.'">'.$cat->name.'</a>';
                    $list .= '<ul>';
                    
                    $arg = array(
                    'child_of'                 => $cat->term_id,
                    'orderby'                  => 'name',
                    'order'                    => 'ASC',
                    'hide_empty'               => 0,
                    'pad_counts'               => false 
                );
		 
		$sub_categories = get_terms('ua-auction-category', $arg);
		
                if(!empty($sub_categories) && is_array($sub_categories)){
                    
                    foreach($sub_categories as $scat){
			$surl = add_query_arg('ua-scat', $scat->slug, $url);
			$list .= '<li><a href="'.$surl.'" class="wdm_cat_single" id="wdm_cat_'.$scat->term_id.'">'.$scat->name.'</a></li>';
                    }
                }
                
		$list .= '</ul></li>';
		
		$all_main_cat[] = array('name' => $cat->name, 'slug' => $cat->slug);
		
	}
    }
	
        $bottom_text = '<div class="wdmua_all_cat_show"><a href="'.$curr_url.'">'.__('Show All Auctions', 'wdm-ultimate-auction').'</a></div>';
	$bottom_text .= '<div class="wdm_clear"></div>';
	
	$categories = '<div id="wdmua_wrap_container" class="wdmua_wrap">';
        
        $categories .= '<div class="demo-container">';

        $categories .= '<div class="wdm_ua_mgmn">';
        
        $categories .= '<ul id="wdm-mega-menu" class="mega-menu">';

        if(!empty($categories))
        	$categories .= '<li id="wdm_ua_s_catmenu">
		<ul>
		    '.$list.' '.$bottom_text.'
		</ul>
        	</li>';
	
            $categories .= '</ul>
            </div>
        </div>
    </div>';
}
    return $categories;
}
?>