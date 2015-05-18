<script type="text/javascript">
jQuery(document).ready(function($){
    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    
	     var data = {
		action: 'invoice_details',
                inv_id: '<?php echo $auctionID; ?>',
		adp_id: '<?php echo $auctionID1; ?>'
	    };
            
	    $.post(ajaxurl, data, function(response) {
               
	    });
        
    });
</script>