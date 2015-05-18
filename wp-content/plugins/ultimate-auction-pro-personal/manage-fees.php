<form id="manage-fees-form" class="auction_settings_section_style" method="post" action="options.php">
	        <?php
		    settings_fields('manage_fees_option_group');//adds all the nonce/hidden fields and verifications	
		    do_settings_sections('manage-fees-setting-admin');
		    echo wp_nonce_field('ua_fees_wp_n_f','ua_wdm_fees_auc');
		?>
	        <?php submit_button(__('Save Changes', 'wdm-ultimate-auction')); ?>
</form>
<script type="text/javascript">
jQuery(document).ready(function(){
jQuery("#manage-fees-form").submit(
                              function(){
                                   var ret = new Boolean();
                                   ret = true;
                                   jQuery("#manage-fees-form .wdm_mng_fee").each(function(i){
                                        if(jQuery(this).val() == 'Active' && jQuery(this).is(':checked')){
                                             if(jQuery("#manage-fees-form #manage_fees_data").val() == ''){
                                                  alert('<?php _e("Status is Active. Please enter Fee Amount.", "wdm-ultimate-auction");?>');
                                                  ret = false;
                                             }
                                   }
				if(jQuery(this).val() == 'Yes' && jQuery(this).is(':checked')){
                                             if(jQuery("#manage-fees-form #manage_cm_fees_data").val() == ''){
                                                  alert('<?php _e("Please enter Commission Fee Amount.", "wdm-ultimate-auction");?>');
                                                  ret = false;
                                             }
                                   }
                                   
                                   });
                                return ret;   
                              }                               
                              );
jQuery("#manage-fees-form .wdm_mng_fee").bind('click',function(){
		
		if(jQuery(this).val() == 'Yes' && jQuery(this).is(':checked'))
		{
				jQuery("#manage_cm_fees_data").attr('disabled', false);
		}
		else if(jQuery(this).val() == 'No' && jQuery(this).is(':checked'))
		{
				jQuery("#manage_cm_fees_data").val('');
				jQuery("#manage_cm_fees_data").attr('disabled', true);
		}
		
		});
});
</script>