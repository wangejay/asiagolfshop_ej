<script type="text/javascript">
jQuery(document).ready(function($){
    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    
    $("#wdm-send-best-offers").click(function(){
	
	$.blockUI({ message: null });
	
	var offer_val = new Number;
	offer_val = $("#wdm-bidder-offerval").val();
	
	if(!offer_val)
	{
	    $.unblockUI();
	    alert('<?php _e("Please enter your Offer Amount", "wdm-ultimate-auction");?>');
	}
	else if( offer_val && isNaN(offer_val))
	{
	    $.unblockUI();
	    alert('<?php _e("Please enter a numeric value", "wdm-ultimate-auction");?>');
	}
	else if( offer_val && offer_val <= 0)
	{
	    $.unblockUI();
	    alert('<?php _e("Please enter a value greater than 0.", "wdm-ultimate-auction");?>');
	}
	else
	{
            //send offers.
            var data = {
              action: 'send_best_offers_now',
              offer_val: $("#wdm-bidder-offerval").val(),
              auction_id: "<?php echo $wdm_auction->ID; ?>",
              os_id: "<?php echo $auction_bidder_id; ?>",  //os: offer sender
            };
            $.post(ajaxurl, data, function(res){
                //console.log(res);
                $("#wdm-desc-offers-tab").html(res);
                $.unblockUI();
            });
        }
        
        return false;
        
    });
    
});
</script>