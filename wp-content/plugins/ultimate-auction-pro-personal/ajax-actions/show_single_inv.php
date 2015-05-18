 <script type="text/javascript">
jQuery(document).ready(function($){
    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
 $('#wdm_pay_inv_<?php echo $single_auction->ID;?>').click(function(){
                                $('.wdm_fe_single_cls_<?php echo $single_auction->ID;?>').remove();
                                $('#wdm_sgl_inv_det_<?php echo $single_auction->ID;?>').remove();
                                $('#wdm_ua_fe_single_<?php echo $single_auction->ID;?>').append('<li class="wdm_fe_single_cls_<?php echo $single_auction->ID;?>" style="width:100%;">Loading ...</li><li id="wdm_sgl_inv_det_<?php echo $single_auction->ID;?>" style="width:100%;"></li>');
                                
                                var data = {
                                        action:'show_front_end_user_pay',
                                        fe_pay_type: '<?php echo $single_auction->ID;?>'
                                    };
                                    $.post(ajaxurl, data, function(response) {
                                       // alert(response);
                                       $('#wdm_sgl_inv_det_<?php echo $single_auction->ID;?>').addClass('wdm_sgl_inv_det_all');
                                        $('#wdm_sgl_inv_det_<?php echo $single_auction->ID;?>').html(response);
                                        $('.wdm_fe_single_cls_<?php echo $single_auction->ID;?>').html('');
                                        
                                    });
                                
                                return false;
                                
                                });
     });
</script>