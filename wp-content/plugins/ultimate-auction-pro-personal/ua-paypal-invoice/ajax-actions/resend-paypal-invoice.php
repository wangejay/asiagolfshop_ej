<script type="text/javascript">
jQuery(document).ready(function($){
    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    $('#resend-invoice-<?php echo $payment_auc->ID;?>').click(
        function(){
            $(this).html("<?php _e('Sending email', 'wdm-ultimate-auction'); echo ' ';?> <img src='<?php echo plugins_url('/img/ajax-loader.gif', dirname(__FILE__));?>' />"); 
	     var data = {
		action: 'resend_invoice',
                auc_id: '<?php echo $payment_auc->ID; ?>',
		auc_nm: '<?php echo esc_js($payment_auc->post_title); ?>',
		auc_dsc: '<?php echo esc_js($payment_auc->post_content); ?>'
	    };
            
	    $.post(ajaxurl, data, function(response) {
               $('#resend-invoice-<?php echo $payment_auc->ID;?>').html('<?php _e("Remind", "wdm-ultimate-auction");?>');
               alert(response);
	    });
            
            return false;
        }
        );
        
    });
</script>