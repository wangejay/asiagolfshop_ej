jQuery(document).ready(function(){
      
      if(jQuery('#ua_shipping_cost').is(':checked')){
         jQuery('#wdm_shipping_sections').css("display","block");
      }
      
      jQuery('#ua_shipping_cost').change(function() {
      if(jQuery(this).is(':checked')){
         jQuery('#wdm_shipping_sections').css("display","block");
      }
      else{
         jQuery('#wdm_shipping_sections').css("display","none");
      }
      });
      
      if(jQuery('#ua_free_shipping_type').is(':checked')) {
         jQuery("#free_shipping_container").css("display","block");
         jQuery("#paid_shipping_container").css("display","none");
         }
   
      jQuery('#ua_free_shipping_type').click(function() {
         jQuery("#free_shipping_container").css("display","block");
         jQuery("#paid_shipping_container").css("display","none");
      });
      
      if(jQuery('#ua_paid_shipping_type').is(':checked')) {
         jQuery("#paid_shipping_container").css("display","block");
         jQuery("#free_shipping_container").css("display","none");
         }
   
      jQuery('#ua_paid_shipping_type').click(function() {
            jQuery("#paid_shipping_container").css("display","block");
            jQuery("#free_shipping_container").css("display","none");
      });
      
      if(jQuery('#ua_shipping_domestic').is(':checked')) {
         jQuery(".ua_dom_data_field").css("display","inline-block");
         }
   
      jQuery('#ua_shipping_domestic').change(function() {
      if(jQuery(this).is(':checked')){
         jQuery('.ua_dom_data_field').css("display","inline-block");
      }
      else{
         jQuery('.ua_dom_data_field').css("display","none");
      }
      });
      
      if(jQuery('#ua_shipping_international').is(':checked')) {
         jQuery(".ua_int_data_field").css("display","block");
         }
   
      jQuery('#ua_shipping_international').change(function() {
      if(jQuery(this).is(':checked')){
         jQuery('.ua_int_data_field').css("display","block");
      }
      else{
         jQuery('.ua_int_data_field').css("display","none");
      }
      });
});