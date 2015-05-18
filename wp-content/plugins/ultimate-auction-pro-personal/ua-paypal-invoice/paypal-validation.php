<script type="text/javascript">
jQuery(document).ready(
                       function()
                       {
                            jQuery("#wdm-payment-form").submit(
                            function(){
                            
                            var email_pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
      
                            var usr     = jQuery("#pp_inv_api_username").val();
                            var pwd     = jQuery("#pp_inv_api_password").val();
                            var sign    = jQuery("#pp_inv_api_signature").val();
                            var app     = jQuery("#pp_inv_api_app_id").val();
                            
                            if(jQuery("#user_paypal_email_id").length > 0){
                         
                              var u_email = jQuery("#user_paypal_email_id").val();
       
                              if(u_email == '')
                              {
                                alert("<?php _e('Please enter Email address', 'wdm-ultimate-auction');?>");
                                return false;
                              }
                              else if(u_email != '' && !email_pattern.test(u_email))
                              {
                                alert("<?php _e('Please enter a valid Email address', 'wdm-ultimate-auction');?>");
                              }
                            }
                            if(usr == '')
                            {
                                alert("<?php _e('Please enter API Username', 'wdm-ultimate-auction');?>");
                                return false;
                            }
                            if(pwd == '')
                            {
                                alert("<?php _e('Please enter API Password', 'wdm-ultimate-auction');?>");
                                return false;
                            }
                            if(sign == '')
                            {
                                alert("<?php _e('Please enter API Signature', 'wdm-ultimate-auction');?>");
                                return false;
                            }
                            if(app == '')
                            {
                                alert("<?php _e('Please enter Application ID', 'wdm-ultimate-auction');?>");
                                return false;
                            }
                                return true;
                            }
                            );
                       });
</script>                           