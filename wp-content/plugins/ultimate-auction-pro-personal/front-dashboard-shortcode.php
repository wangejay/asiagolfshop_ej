<?php
function wdm_auction_front_dashboard(){
    wp_enqueue_style('wdm_auction_front_end_styling',plugins_url('css/ua-front-end.css', __FILE__));
    wp_enqueue_script('wdm_ua_ui_js', plugins_url('js/jquery-ui.min.js', __FILE__), array('jquery'));
    if(is_user_logged_in()){
    //if(current_user_can('add_ultimate_auction') || current_user_can('administrator')){
    wp_enqueue_script( 'wdm-pagn-js', plugins_url( '/js/jquery.pajinate.js', __FILE__ ), array('jquery') );
    
    $auc_set = new wdm_settings();
    
    //add_action('wp_enqueue_scripts', $auc_set->wdm_enqueue_scripts_styles());
    $auc_set->wdm_enqueue_scripts_styles();
    
    $tab_char = wdm_current_perm_char();
    
    $user_tabs = '';
    $manage_payment = '';
    
    $auc_url = get_option('wdm_auction_page_url');
    
    if(current_user_can('administrator')){
	$adm_nt = __('Please Note', 'wdm-ultimate-auction').':<br />'. __('You are detected as administrator of the site. The below dashboard has limited settings only (suitable for non admin sellers). It is recommended for you to use <a href="'.admin_url('admin.php?page=ultimate-auction').'" target="_blank"> admin dashboard </a> to access and manage all the settings.', 'wdm-ultimate-auction');
	
	echo "<script type='text/javascript'> jQuery(document).ready(function(){alert('".__("You are detected as administrator of the site. This dashboard has limited settings only (suitable for non admin sellers). It is recommended for you to use admin dashboard to access and manage all the settings.", "wdm-ultimate-auction")."'); }); </script>";
	
	echo '<div class="wdm_ua_admin_note_ud wdm-mark-red">'.$adm_nt.'</div>';
	
	echo "<br/>";
    }
    
    if(current_user_can('add_ultimate_auction') && !current_user_can('administrator') && isset($_GET['dashboard']) && ($_GET['dashboard'] == 'add-auction')){
	
	$comm_act = get_option('wdm_manage_comm_invoice');
	
	if($comm_act == 'Yes'){
		
	    $com_type = get_option('wdm_manage_comm_type');
	    $com_fee = get_option('wdm_manage_cm_fees_data');
	    $currency_code = substr(get_option('wdm_currency'), -3);
	    $charge = "";
	    
	    if( $com_type == 'Percentage' ){
		$charge = $com_fee.'%';
	    }
	    else{
		$charge = $currency_code." ".$com_fee;
	    }
	    
	    echo '<div class="wdm_auc_user_notice_err">';
	    printf(__("NOTE: Admin will charge %s fee as commission on final bidding amount/Buy now amount for this auction.", "wdm-ultimate-auction"), $charge);
	    echo '</div>';
	}
    }
    
    $user_tabs .= '<div class="wdm-db-listing-container"><a href="'.$auc_url.'" target="_blank" style="float: right;">'.__("Auctions", "wdm-ultimate-auction").'</a>';
    $user_tabs .= '<ul id="wdm-auction-sorting-tabs">';
	
    if(current_user_can('add_ultimate_auction') || current_user_can('administrator')){
	$user_tabs .= '<li class="auc_single_list" id="ua-db-settings"><a href="'.$tab_char.'dashboard=settings">'.__("Settings", "wdm-ultimate-auction").'</a></li>';
	
	$user_tabs .= '<li class="auc_single_list" id="ua-db-payment"><a href="'.$tab_char.'dashboard=payment">'.__("Payment", "wdm-ultimate-auction").'</a></li>';
	
	$user_tabs .= '<li class="auc_single_list" id="ua-db-add-new"><a href="'.$tab_char.'dashboard=add-auction">'.__("Add Auction", "wdm-ultimate-auction").'</a></li>';
	$user_tabs .= '<li class="auc_single_list" id="ua-db-manage-auctions"><a href="'.$tab_char.'dashboard=manage-auctions">'.__("Manage Auctions", "wdm-ultimate-auction").'</a></li>';
    }
	
    $user_tabs .= '<li class="auc_single_list" id="ua-db-manage-bids"><a href="'.$tab_char.'dashboard=manage-bids">'.__("Manage Bids", "wdm-ultimate-auction").'</a></li>';
    
    if(current_user_can('add_ultimate_auction') || current_user_can('administrator')){
	
    //$user_tabs .= '<li class="auc_single_list" id="ua-db-payment"><a href="'.$tab_char.'dashboard=payment">'.__("Payment", "wdm-ultimate-auction").'</a></li>';
	
    //if(current_user_can('add_ultimate_auction') || current_user_can('administrator')){
	$user_tabs .= '<li class="auc_single_list" id="ua-db-invoices"><a href="'.$tab_char.'dashboard=invoices">'.__("Invoices", "wdm-ultimate-auction").'</a></li>';
    }
    $user_tabs.='<li class="auc_single_list" id="ua-db-watchlist"><a href="'.$tab_char.'dashboard=watchlist">'.__("My Watchlist", "wdm-ultimate-auction").'</a></li>';
    
    $user_tabs .= '</ul>';
    $user_tabs .= '<ul class="wdm_auctions_list"><li>';
    
    $logged_user_id = wp_get_current_user();  //get user id
    $logged_user_role = $logged_user_id->roles; //get user role
    $cu_id = get_current_user_id();

    if(current_user_can('add_ultimate_auction') || current_user_can('administrator')){
    if(isset($_GET['dashboard']) && $_GET['dashboard'] == 'manage-auctions'){
            $manage_your_auction = '';
            
            require_once('user-manage-auctions.php');
            
            $manage_your_auction .= '<ul class="wdm_front_links">
            <li><span class="wdmauc_live wdm_lspan wdm_front_on">'.__("Live Auctions", "wdm-ultimate-auction").'</span>|</li>
	    <li><span class="wdmauc_scheduled wdm_lspan">'.__("Scheduled Auctions", "wdm-ultimate-auction").'</span>|</li>
            <li><span class="wdmauc_expired wdm_lspan">'.__("Expired Auctions", "wdm-ultimate-auction").'</span></li>
            <li style="float:right;"><span class="wdmua_del_stats" style="float: right;"></span><select id="wdmua_del_all" style="float:left;"><option value="del_all_wdm">'.__("Delete", "wdm-ultimate-auction").'</option></select><input type="button" id="wdm_mult_chk_del" class="wdm_ua_act_links" style="float:right;padding: 5px 10px;" value="'.__("Apply", "wdm-ultimate-auction").'" /></li></ul>';

            $manage_your_auction .= "<ul style='float:right;'></ul>";
            
            $FrontAuctionsList = new Auctions_List_Table_Front();

            //live auctions
            $data_array = $FrontAuctionsList->wdm_get_data('live', $tab_char);
            $col_array = $FrontAuctionsList->get_columns('live');

            $manage_your_auct = wdm_prepare_front_items($data_array, $col_array, 'show', 'live');
            
            $user_tabs .= $manage_your_auction;
            $user_tabs .= '<span class="wdm_ua_ajx_ld"></span><div id="wdm_ua_front_end_content" class="wdm_ua_front_end_mgauc">'.$manage_your_auct.'</div>';
        ?>
            <script type="text/javascript">
            jQuery(document).ready(
			       function($){
                                var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
				$("#ua-db-manage-auctions").addClass("ua-active-tab");
                                
                                $('.wdmauc_live').click(function(){
        
                                $('.wdm_ua_ajx_ld').html("Loading <img src='<?php echo plugins_url('/img/ajax-loader.gif', __FILE__ );?>' />");
                                
                                var data = {
                                        action:'show_front_end_user_auc',
                                        fe_auc_type: 'live',
                                        fe_auc_perm:'<?php echo $tab_char;?>'
                                    };
                                    $.post(ajaxurl, data, function(response) {
                                        
                                        $('#wdm_ua_front_end_content').html(response);
                                        $('.wdm_ua_ajx_ld').html('');
                                        
                                    });
                                
                                return false;
                                
                                });
                                        
                                $('.wdmauc_expired').click(function(){
                                
                                $('.wdm_ua_ajx_ld').html("Loading <img src='<?php echo plugins_url('/img/ajax-loader.gif', __FILE__ );?>' />");
                                
                                var data = {
                                        action:'show_front_end_user_auc',
                                        fe_auc_type: 'expired',
                                        fe_auc_perm:'<?php echo $tab_char;?>'
                                    };
                                    $.post(ajaxurl, data, function(response) {
                                        //alert(response);
                                        
                                        $('#wdm_ua_front_end_content').html(response);
                                        $('.wdm_ua_ajx_ld').html('');
                                        
                                    });
                                
                                return false;
                                
        });
                         
			        $('.wdmauc_scheduled').click(function(){
                                
                                $('.wdm_ua_ajx_ld').html("Loading <img src='<?php echo plugins_url('/img/ajax-loader.gif', __FILE__ );?>' />");
                                
                                var data = {
                                        action:'show_front_end_user_auc',
                                        fe_auc_type: 'scheduled',
                                        fe_auc_perm:'<?php echo $tab_char;?>'
                                    };
                                    $.post(ajaxurl, data, function(response) {
                                        //alert(response);
                                        
                                        $('#wdm_ua_front_end_content').html(response);
                                        $('.wdm_ua_ajx_ld').html('');
                                        
                                    });
                                
                                return false;
                                
        });
			        
        $('.wdm_select_all_chk').live("click", function(){ 
        if($(this).is(':checked')){
            var cnf1 = confirm('<?php _e("Please note: This option will select all the auctions even if there is pagination. If you are going to delete the auctions, it will delete all of your auctions.", "wdm-ultimate-auction");?>');
            
            if(cnf1 == true)
                $('.wdm_chk_auc_act').attr('checked','checked');
            else
                $('.wdm_select_all_chk').removeAttr('checked');
        }
        else
            $('.wdm_chk_auc_act').removeAttr('checked');
        });

       
       $('#wdm_mult_chk_del').live("click",function(){ 
        
        var all_auc = new Array();
        
        $('.wdm_chk_auc_act').each(function(){
            
            if($(this).is(':checked')){
                all_auc.push($(this).val());
            }
            
            });
        var aaucs = all_auc.join();
        if(aaucs == '' || aaucs == null){
            alert('<?php _e("Please select auction(s) to delete.", "wdm-ultimate-auction");?>');
            return false;
        }
        else
            var cnf = confirm('<?php _e("Are you sure to delete selected auctions? All data related to the auctions (including bids and attachments) will be deleted.", "wdm-ultimate-auction");?>');
        
        if(cnf == true){
        $('.wdmua_del_stats').html("<?php _e('Deleting', 'wdm-ultimate-auction'); echo ' ';?> <img src='<?php echo plugins_url('/img/ajax-loader.gif', __FILE__ );?>' />");       
	var data = {
		action:'multi_delete_auction',
                del_ids:aaucs,
                force_del:'yes'
	    };
	    $.post(ajaxurl, data, function(response) {
                $('.wdmua_del_stats').html('');
                alert(response);
                window.location.reload();
                $('.wdm_select_all_chk, .wdm_chk_auc_act').removeAttr('checked');
	    });
        }
        return false;
	 
        });
       
	});
            </script>
        <?php
    }
    elseif(isset($_GET['dashboard']) && $_GET['dashboard'] == 'payment'){
    
	require_once('payment_front.php');
	
	$user_tabs .= $manage_pmt;
	
	$user_tabs .= '<span class="wdm_ua_ajx_ld"></span><div id="wdm_ua_front_end_content" class="wdm_ua_front_end_mgpmt">'.$manage_payment.'</div>';

	?>
	<style type="text/css">
	    
	    .wdm_auctions_list .ult-auc-settings-tip{margin-left: 122px !important;}
	    
	</style>
	  <script type="text/javascript">
            jQuery(document).ready(
			       function($){
                                var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
				$("#ua-db-payment").addClass("ua-active-tab");
                                
                                $('.wdm_lspan').click(function(){
        
                                $('.wdm_ua_ajx_ld').html("Loading <img src='<?php echo plugins_url('/img/ajax-loader.gif', __FILE__ );?>' />");
                                
                                var data = {
                                        action:'front_end_user_pmt',
                                        method: $(this).attr('data-pmt')
                                    };
                                    $.post(ajaxurl, data, function(response) {
                                        
                                        $('#wdm_ua_front_end_content').html(response);
                                        $('.wdm_ua_ajx_ld').html('');
                                        
                                    });
                                
                                return false;
                                
                                });
				
				$(document).on('submit', '#wdm_front_pay_settings form', function(e){
					e.preventDefault();
					
					var data = $(this).serialize()+'&action=front_end_user_pmt&ua_update=update';
                                    $.post(ajaxurl, data, function(response) {
                                        
					if (response.indexOf("success") >= 0) {
					    alert("Settings updated.");
					}
                                       
                                        
                                    });
				    
				    //return false;
				});
				
	    $('.wdmauc_paypal').click(function(){
            $('#wdm_pp_api_refresh').css('display', 'none');
            $('#wdm_pp_api_key_help').css('display', 'block');
            });
				
			       });
	  </script>
	    
    <?php	    
    }
    elseif(isset($_GET['dashboard']) && $_GET['dashboard'] == 'invoices'){		
    $manage_pmt = '';
            
    $manage_pmt .= '<ul class="wdm_front_links">
    <li><span class="wdmauc_outstanding wdm_lspan">'.__('Outstanding Invoices', 'wdm-ultimate-auction').'</span>|</li>
    <li><span class="wdmauc_past wdm_lspan">'.__('Paid Invoices', 'wdm-ultimate-auction').'</span></li>';

$manage_pmt .= '<li id="wdm_pp_api_refresh" class="paypal-api-help-links" style="float: right;display:none;"><div style="float:left;">	
    <select id="wdmua_del_all" style="float:left;margin-right: 10px;"><option value="del_all_wdm">'.__("Delete", "wdm-ultimate-auction").'</option></select>
    <input type="button" id="wdm_mult_chk_del" class="wdm_ua_act_links button-secondary" value="'.__("Apply", "wdm-ultimate-auction").'" />
    <span class="wdmua_del_stats"></span>
</div>
<a class="pp-ref-a-btn" href="" onclick="window.location.reload();">'.__("Refresh", "wdm-ultimate-auction").'</a></li>';

$manage_pmt .= '</ul>';

$user_tabs .= $manage_pmt;

           //require_once('ua-paypal-invoice/payment_settings.php');
            $user_tabs .= '<span class="wdm_ua_ajx_ld"></span><div id="wdm_ua_front_end_content" class="wdm_ua_front_end_mginv">'.$manage_payment.'</div>';
            //$user_tabs .= apply_filters('ua_call_front_setting_file', $manage_payment, $_GET['dashboard']);
        ?>
        <script type="text/javascript">
            jQuery(document).ready(
			       function($){
                                var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
				$("#ua-db-invoices").addClass("ua-active-tab");
                                
                                var data = {
                                        action:'show_front_end_user_pay',
                                        fe_pay_type: 'outstanding'
                                    };
                                    $.post(ajaxurl, data, function(response) {
                                        
                                        $('#wdm_ua_front_end_content').html(response);
                                        $('.wdm_ua_ajx_ld').html('');
                                        
                                    });
				    
                                $('.wdmauc_outstanding').click(function(){
                                
                                $('.wdm_ua_ajx_ld').html("Loading <img src='<?php echo plugins_url('/img/ajax-loader.gif', __FILE__ );?>' />");
                                
                                var data = {
                                        action:'show_front_end_user_pay',
                                        fe_pay_type: 'outstanding'
                                    };
                                    $.post(ajaxurl, data, function(response) {
                                        
                                        $('#wdm_ua_front_end_content').html(response);
                                        $('.wdm_ua_ajx_ld').html('');
                                        
                                    });
                                
                                return false;
                                
                                });
                                
                                $('.wdmauc_past').click(function(){
                                
                                $('.wdm_ua_ajx_ld').html("Loading <img src='<?php echo plugins_url('/img/ajax-loader.gif', __FILE__ );?>' />");
                                
                                var data = {
                                        action:'show_front_end_user_pay',
                                        fe_pay_type: 'past'
                                    };
                                    $.post(ajaxurl, data, function(response) {
                           
                                        $('#wdm_ua_front_end_content').html(response);
                                        $('.wdm_ua_ajx_ld').html('');
                                        
                                    });
                                
                                return false;
                                
                                });
			       
    
        $('.wdmauc_outstanding, .wdmauc_past').click(function(){
            $('#wdm_pp_api_refresh').css('display', 'block');
            $('#wdm_pp_api_key_help').css('display', 'none');
            });
    });
                               
                              
            </script>
    
        <?php
    }
    elseif(isset($_GET['dashboard']) && ($_GET['dashboard'] == 'add-auction')){
	    require_once('user-add-new-auction.php');
           
            $user_tabs .= '<div class="add_auc_cnt_wdm">'.$add_user_auction.'</div>';
            
        ?>
            <script type="text/javascript">
            jQuery(document).ready(
			       function(){
				jQuery("#ua-db-add-new").addClass("ua-active-tab");
			       }
			       );
            </script>
        <?php
    }
    else{
	
        if(!isset($_GET['dashboard']) || (isset($_GET['dashboard']) && $_GET['dashboard'] != 'manage-bids' && $_GET['dashboard'] != 'watchlist')){
	    
	    require_once('user-settings-page.php');
	    
	    $user_tabs .= $user_settings;
	                
        ?>
            <script type="text/javascript">
            jQuery(document).ready(
			       function(){
				jQuery("#ua-db-settings").addClass("ua-active-tab");
			       }
			       );
            </script>
        <?php
    }
    }
    }
   
    if(isset($_GET['dashboard']) && $_GET['dashboard'] == 'watchlist')
    {
	    require_once('user-my-watchlist.php');
	     $user_tabs.='<li class="auction-list-menus">
			    <div class="wdm-auction-listing-container">
				<ul class="wdm_all_li_content_auc">
					<li class="wdm-apn auc_single_list"><strong>'.__('Product', 'wdm-ultimate-auction').'</strong></li>
					<li class="wdm-apt auc_single_list"><strong></strong></li>
					<li class="wdm-app auc_single_list"><strong>'.__('Current Price', 'wdm-ultimate-auction') .'</strong></li>
					<li class="wdm-apb auc_single_list"><strong>'.__('Bids Placed', 'wdm-ultimate-auction').'</strong></li>
					<li class="wdm-ape auc_single_list"><strong>'. __('Ending', 'wdm-ultimate-auction').'</strong></li>
				</ul>
			    </div>
			</li>';
	    $getlist=wdm_my_watch();
	    $user_tabs.=$getlist;
	    ?>
	    <script type="text/javascript">
            jQuery(document).ready(
			       function($){
                                var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
				$("#ua-db-watchlist").addClass("ua-active-tab");
				});
            </script>
	    <?php
    }
    elseif(((current_user_can('add_ultimate_auction') || current_user_can('administrator')) && isset($_GET['dashboard']) && $_GET['dashboard'] == 'manage-bids') || (!current_user_can('add_ultimate_auction') && !current_user_can('administrator'))){		
            $manage_your_bids = '';
            require_once('user-manage-bids.php');
            
            $manage_your_bids .= '<ul class="wdm_front_links">
    <li><span class="wdmauc_won wdm_lspan wdm_front_on">'.__('Bids Won', 'wdm-ultimate-auction').'</span>|</li>
    <li><span class="wdmauc_not_won wdm_lspan">'.__('Bids Lost', 'wdm-ultimate-auction').'</span>|</li>
    <li><span class="wdmauc_active wdm_lspan">'.__('Active Bids', 'wdm-ultimate-auction').'</span></li>
</ul>';

$FrontBidsList = new Bids_List_Table_Front();

$data_array = $FrontBidsList->wdm_get_data('won');
$col_array = $FrontBidsList->get_columns('won');

$manage_your_bid = wdm_prepare_front_items($data_array, $col_array, 'show', 'won');
            $user_tabs .= $manage_your_bids;
            $user_tabs .= '<span class="wdm_ua_ajx_ld"></span><div id="wdm_ua_front_end_content" class="wdm_ua_front_end_mgbid">'.$manage_your_bid.'</div>';
        ?>
            
             <script type="text/javascript">
            jQuery(document).ready(
			       function($){
                                var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
				$("#ua-db-manage-bids").addClass("ua-active-tab");
                                
                                $('.wdmauc_won').click(function(){
        
                                $('.wdm_ua_ajx_ld').html("Loading <img src='<?php echo plugins_url('/img/ajax-loader.gif', __FILE__ );?>' />");
                                
                                var data = {
                                        action:'show_front_end_user_bid',
                                        fe_bid_type: 'won'
                                    };
                                    $.post(ajaxurl, data, function(response) {
                                        
                                        $('#wdm_ua_front_end_content').html(response);
                                        $('.wdm_ua_ajx_ld').html('');
                                        
                                    });
                                
                                return false;
                                
                                });
                                        
                                $('.wdmauc_not_won').click(function(){
                                
                                $('.wdm_ua_ajx_ld').html("Loading <img src='<?php echo plugins_url('/img/ajax-loader.gif', __FILE__ );?>' />");
                                
                                var data = {
                                        action:'show_front_end_user_bid',
                                        fe_bid_type: 'not_won'
                                    };
                                    $.post(ajaxurl, data, function(response) {
                                        
                                        $('#wdm_ua_front_end_content').html(response);
                                        $('.wdm_ua_ajx_ld').html('');
                                        
                                    });
                                
                                return false;
                                
                                });
                                
                                $('.wdmauc_active').click(function(){
                                
                                $('.wdm_ua_ajx_ld').html("Loading <img src='<?php echo plugins_url('/img/ajax-loader.gif', __FILE__ );?>' />");
                                
                                var data = {
                                        action:'show_front_end_user_bid',
                                        fe_bid_type: 'active'
                                    };
                                    $.post(ajaxurl, data, function(response) {
                                        
                                        $('#wdm_ua_front_end_content').html(response);
                                        $('.wdm_ua_ajx_ld').html('');
                                        
                                    });
                                
                                return false;
                                
                                });
			       }
			       );
            </script>
        <?php
    }
    
    $user_tabs .= '</li></ul>';
    $user_tabs .= '</div>';
    
    ?>
         <script type="text/javascript">
            jQuery(document).ready(function($){
            
            $('.wdm_front_links .wdm_lspan').click(function(){
	 
                if(!$(this).hasClass('wdm_front_on')){
		$(this).addClass('wdm_front_on');
	       }
    
            $('.wdm_front_links .wdm_lspan').not(this).removeClass('wdm_front_on');
         });
        });
            </script>
    <?php
    
    return $user_tabs;
    //}
    //else{
    //    echo "<div class='wdm_no_auc_authcap'>".__("Sorry, you don't have auction author capability. Please contact your administrator to provide you auction author capability.")."</div>";
    //}
}
    else{
            $log_in = '';
            
            $log_in = wdm_auction_dashboard_login($ext_html = '');
            
            return $log_in;
        }
}

//shortcode to display front end dashboard on the site
add_shortcode('wdm_user_dashboard', 'wdm_auction_front_dashboard');

add_filter('wdm_ua_add_html_feeder', 'wdm_ua_add_html_on_feed', 99, 1);
?>