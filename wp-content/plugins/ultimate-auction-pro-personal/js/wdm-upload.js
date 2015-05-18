jQuery(document).ready(function($){
	
			var $image_gallery_ids = $('#product_image_gallery');
			var $product_images = $('#product_images_container ul.product_images');

	
			// Image ordering
			$product_images.sortable({
				items: 'li.wdmimage',
				cursor: 'move',
				scrollSensitivity:40,
				forcePlaceholderSize: true,
				forceHelperSize: false,
				helper: 'clone',
				opacity: 0.65,
				placeholder: 'wc-metabox-sortable-placeholder',
				start:function(event,ui){
					ui.item.css('background-color','#f6f6f6');
				},
				stop:function(event,ui){
					ui.item.removeAttr('style');
				},
				update: function(event, ui) {
					var attachment_ids = '';
	
					$('#product_images_container ul li.wdmimage').css('cursor','default').each(function() {
						var attachment_id = jQuery(this).attr( 'data-attachment_id' );
                                                
                                                if ($(this).is(':last-child')) {
                                                  attachment_ids = attachment_ids + attachment_id;
                                                }
                                                else{
                                                  attachment_ids = attachment_ids + attachment_id + ',';
                                                }
					});
	
					$image_gallery_ids.val( attachment_ids );
				}
			});
	
			// Remove images
			$('#product_images_container').on( 'click', 'a.delete', function() {
	
				$(this).closest('li.wdmimage').remove();
	
				var attachment_ids = '';
	
				$('#product_images_container ul li.wdmimage').css('cursor','default').each(function() {
					var attachment_id = jQuery(this).attr( 'data-attachment_id' );
                                        if (attachment_id != "") {
                                             attachment_ids = attachment_ids + attachment_id + ',';
                                        }
				});
                                
                                var lastChar = attachment_ids.slice(-1);
                                   if(lastChar == ',') {
                                        attachment_ids = attachment_ids.slice(0, -1);
                                   }
        
				$image_gallery_ids.val( attachment_ids );
	
				return false;
			} );
			
			$('.wdmimage').mouseover(function(){
				$(this).find('ul.actions').show();
				});
			$('.wdmimage').mouseout(function(){
				$(this).find('ul.actions').hide();
				});

		$('.add_product_images').click(function(){$('#wdm_auc_mult_upload ').trigger('click');});
		
		$('#wdm_auc_mult_upload').change(function(){
			var files = $(this).prop("files");
			var file_obj = $.map(files, function(val) { return '<br /><span class="wdm_tmp_file_name">'+val.name+' </span> '+wdm_ua_obj_multup.si; });
			//var file_arr = new Array();
			//file_arr = $.makeArray(file_obj);
			//alert(typeof(file_arr));
			$('#wdm_temp_flname').html(file_obj);
			});
		});