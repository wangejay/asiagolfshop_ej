<script type="text/javascript">
jQuery(document).ready(function($){
    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    
    $("#wdm_add_to_watch_lin").click(function(){
	 var data = {
		action: 'wdm_add_to_watchlist',
                auction_id: "<?php echo $wdm_auction->ID; ?>",
		user_id:"<?php echo $auction_bidder_id;?>"
	    };
	$.post(ajaxurl, data, function(response) {
		if (response.trim()=="true") {
		    $("#wdm_add_to_watch_div_lin").toggle();
		    $("#wdm_rmv_frm_watch_div_lin").toggle();
		}
	    }); 
        return false;
        });
    
   
    $("#wdm_rmv_frm_watch_lin").click(function(){
	
				var data = {
					action:'rmv_frm_watchlist',
					rmv_id:'<?php echo $wdm_auction->ID;?>',
					usr_id:'<?php echo $auction_bidder_id;?>',
					auc_title: '<?php echo esc_js($wdm_auction->post_title);?>',
					force_del:'yes'
				    };
				    $.post(ajaxurl, data, function(response) {
					if (response.trim()=="Auction removed from your watchlist successfully.") {
					    $("#wdm_add_to_watch_div_lin").toggle();
					    $("#wdm_rmv_frm_watch_div_lin").toggle();
					}
				    });
				
				return false;
				 
				});
     });
</script>