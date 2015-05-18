<script type="text/javascript">
    jQuery(document).ready(function(){
	
    jQuery('#wdm_auc_user_notice_vis').html(jQuery('#wdm_auc_user_notice_hid').html());
	
    var custom_uploader;

    jQuery('.wdm_auction_image_upload').click(function(e) {

     var target_input = jQuery(this).attr('id');

    e.preventDefault();

        //If the uploader object has already been created, reopen the dialog
        //if (custom_uploader) {
        //    custom_uploader.open();
        //    return;
        //}

        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });

        //When a file is selected, grab the URL and set it as the text field's value
    custom_uploader.on('select', function() {
    attachment = custom_uploader.state().get('selection').first().toJSON();
    //jQuery('.' + target_input).val(attachment.url);
//    if (target_input == "wdm_attachment_url") {	
//	jQuery('.' + target_input).val(attachment.url);	
//	//return;	
//    }	
//    else if (target_input == "digital_auction_file_url") {	
//	jQuery('.' + target_input).val(attachment.url);	
//	//return ;	
//    }
//    else
//    {
if (attachment.url != '') {
	jQuery('.' + target_input).val(attachment.url);
}
    //}
});

        //Open the uploader dialog
        custom_uploader.open();
    });

	
        //var xid;
        //jQuery(".wdm_auction_image_upload").click(function(){ 
        //    tb_show('', 'media-upload.php?type=image&TB_iframe=true');
        //    xid=jQuery(this).attr("id");
        //    return false;
        //    });
        //
        //window.send_to_editor = function(html) {
        //    imgurl = jQuery('img',html).attr('src');
        //    jQuery('.'+xid).val(imgurl);
        //    tb_remove();
        //    }
	    
	jQuery('#start_date').datetimepicker({
            timeFormat: "HH:mm:ss",
            dateFormat : 'yy-mm-dd',
	    minDateTime: 0,
	    beforeShow: function (input) {
	    setTimeout(function () {
            var buttonPane = jQuery(input)
                .datepicker("widget")
                .find(".ui-datepicker-buttonpane");

            var btn = jQuery('<button class="ui-datepicker-current ui-state-default ui-priority-secondary ui-corner-all" type="button"><?php _e("Clear", "wdm-ultimate-auction");?></button>');
            btn.unbind("click")
            .bind("click", function () {
                jQuery(input).datepicker("hide");
                jQuery(input).val("");
            });

            btn.appendTo(buttonPane);

        }, 1);
    }
            });
	
        jQuery('#end_date').datetimepicker({
            timeFormat: "HH:mm:ss",
            dateFormat : 'yy-mm-dd',
	    minDateTime: 0
            });
	
	jQuery("#wdm-add-auction-form").submit(function(){
           var bn = new Number;
           var ob = new Number;
           var lb = new Number;
           var inc = new Number;
           var tl,ds,edt;
           
           tl = jQuery("#wdm-add-auction-form #auction_title").val();
           //ds = jQuery("#wdm-add-auction-form #auction_description").val();
           bn = jQuery("#wdm-add-auction-form #buy_it_now_price").val();
           ob = jQuery("#wdm-add-auction-form #opening_bid").val();
           lb = jQuery("#wdm-add-auction-form #lowest_bid").val();
           inc = jQuery("#wdm-add-auction-form #incremental_value").val();
           edt = jQuery("#wdm-add-auction-form #end_date").val();
           sdt = jQuery("#wdm-add-auction-form #start_date").val();
	   
           //var pd = jQuery("#payment_method #wdm_method_paypal").attr("disabled");
           
           if(!tl)
           {
                   alert('<?php _e("Please enter Product Title.", "wdm-ultimate-auction");?>');
                   return false; 
           }
           
           //if(!ds)
           //{
           //        alert('<?php _e("Please enter Product Description.", "wdm-ultimate-auction");?>');
           //        return false; 
           //}
           
           if(!edt)
           {
                   alert('<?php _e("Please enter Ending Date/Time.", "wdm-ultimate-auction");?>');
                   return false; 
           }
        
	   if(sdt && (jQuery('#start_date').datepicker('getDate').getTime() >= jQuery('#end_date').datepicker('getDate').getTime())){
		alert('<?php _e("Ending date should be greater than Start date.", "wdm-ultimate-auction");?>');
		return false;
	   }
	   
           //if(pd == 'disabled')
           //{
           //    if(bn && Number(bn) > 0)
           //    {
           //        alert("<?php _e("PayPal email address should have been filled in administrator account to enable 'Buy Now' feature.", "wdm-ultimate-auction");?>");
           //        jQuery("#wdm-add-auction-form #buy_it_now_price").val("");
           //        return false;
           //    }
           //    
           //    if(!ob || 0 >= Number(ob))
           //    {
           //        alert('<?php _e("Please enter Opening Price.", "wdm-ultimate-auction");?>');
           //        return false;
           //    }
           //    
           //    if(!lb || Number(lb) < Number(ob))
           //    {
           //        alert('<?php _e("Please enter Lowest Price (Reserve Price).", "wdm-ultimate-auction");?>');
           //        return false;
           //    }
           //    
           //}
           //else
           //{
               if((!ob || 0 >= Number(ob)) && (!bn || 0 >= Number(bn)))
               {
                   alert('<?php _e("Please enter either Opening Price or Buy Now price.", "wdm-ultimate-auction");?>');
                   return false;
               }
               
               if((ob && Number(ob) > 0) && (!lb || 0 >= Number(lb)))
               {
                   alert('<?php _e("You have entered Opening Price. Please also enter Lowest Price (Reserve Price).", "wdm-ultimate-auction");?>');
                   return false;
               }
               
	       if((ob && Number(ob) > 0) && (!inc || 0 >= Number(inc)))
               {
                   alert('<?php _e("Incremental Value should be greater than 0.", "wdm-ultimate-auction");?>');
                   return false;
               }
	       
               if((lb && Number(lb) > 0) && (!ob || 0 >= Number(ob)))
               {
                   alert('<?php _e("You have entered Lowest Price. Please also enter Opening Price.", "wdm-ultimate-auction");?>');
                   return false;
               }
               
               if((inc && Number(inc) > 0) && (!ob || 0 >= Number(ob)))
               {
                   alert('<?php _e("You have entered Incremental Value. Please also enter Opening Price.", "wdm-ultimate-auction");?>');
                   return false;
               }
           //}
               if(Number(lb) < Number(ob))
               {
                   alert('<?php _e("Lowest/Reserve price should be more than or equal to Opening Price.", "wdm-ultimate-auction");?>');
                   return false;
               }
               
               if(bn && Number(bn) > 0)
               {
                  if(Number(bn) < Number(lb))
                  {
                       alert('<?php _e("Buy Now price should be more than or equal to Lowest/Reserve price.", "wdm-ultimate-auction");?>');
                       return false;
                  }
               }
               return true;
           }
           );
        });
</script>