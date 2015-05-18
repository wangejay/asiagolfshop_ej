<script type="text/javascript">
jQuery(document).ready(function($){
       var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
       $('#inv-resend-email-<?php echo $payment_auc->ID;?>').click(function(){
       
       $('#inv-resend-email-<?php echo $payment_auc->ID;?>').html("<?php _e('Sending', 'wdm-ultimate-auction'); echo ' ';?> <img src='<?php echo plugins_url('/img/ajax-loader.gif', dirname(__FILE__));?>' />");
	
	var data = {
		action:'resend_auction_email',
                a_em:'<?php echo $bidder_email;?>',
		a_bid:'<?php echo $bid_price;?>',
		a_id:'<?php echo $payment_auc->ID;?>',
		a_title:'<?php echo esc_js($payment_auc->post_title);?>',
		a_cont:'<?php echo esc_js($payment_auc->post_content);?>',
		a_url: '<?php echo get_post_meta($payment_auc->ID, 'current_auction_permalink',true);?>'
	    };
	    $.post(ajaxurl, data, function(response) {
	      $('#inv-resend-email-<?php echo $payment_auc->ID;?>').html('<?php _e("Resend", "wdm-ultimate-auction");?>');
	      alert(response);
	      window.location.reload();
	    });
	
        return false;
	 
        });
    });
</script>