<script type="text/javascript">
jQuery(document).ready(function($){
       var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
       $('#wdm-delete-invoice-<?php echo $payment_auc->ID;?>').click(function(){
        
        var cnf = confirm("<?php _e("Are you sure to delete this invoice entry?", "wdm-ultimate-auction");?>");
        
        if(cnf == true){
        $(this).html("<?php _e('Deleting', 'wdm-ultimate-auction'); echo ' ';?> <img src='<?php echo plugins_url('/img/ajax-loader.gif', dirname(__FILE__) );?>' />");       
	var data = {
		action:'delete_invoice',
                del_id:'<?php echo $payment_auc->ID;?>',
                inv_num: '<?php echo $inv_num;?>',
                force_del:'yes'
	    };
	    $.post(ajaxurl, data, function(response) {
                $('#wdm-delete-invoice-<?php echo $payment_auc->ID;?>').html('<?php _e("Delete", "wdm-ultimate-auction");?>');
                alert(response);
                window.location.reload();
	    });
        }
        return false;
	 
        });
       
    });
</script>