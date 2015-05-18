<?php $wp_upload_dir = wp_upload_dir(); ?>

<script type="text/javascript">
  jQuery(document).ready(function($){
    
    $('.wdm_image_<?php echo $p;?>_btn').click(function(){$('#auction_image_file_<?php echo $p;?>').trigger('click');});
    
      $('#auction_image_file_<?php echo $p; ?>').change(function(){
	 
	 var fn = $(this).val();
	 
	   if(null != fn && fn.length > 0 ){
	    
	    var endIndex = fn.lastIndexOf("\\") + 1;
	    
	    if(endIndex != -1)  {
	    var fn = fn.substring(endIndex, fn.length); 
	  }
	}

	$('.wdm_img_<?php echo $p;?>_name').html(fn);
	var temp_url = '<?php echo $wp_upload_dir['url'];?>/'+fn;
	$('#auction_image_<?php echo $p;?>').val(temp_url.replace(/\s/g, "-"));
	});
    });
</script>
<?php

if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );

if(isset($_FILES['auction_image_file_'.$p]))
  $uploadedfile = $_FILES['auction_image_file_'.$p];

if(!empty($_FILES['auction_image_file_'.$p]['name'])){
  
$upload_overrides = array( 'test_form' => false );

$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );

if ( $movefile ) {
    
    $wp_filetype = $movefile['type'];
      
      $filename = $movefile['file'];
      
      $attachment = array(
	  'guid' => $wp_upload_dir['url'] . '/' . basename( $filename ),
	  'post_mime_type' => $wp_filetype,
	  'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
	  'post_content' => '',
	  'post_status' => 'inherit'
      );
      
      $attach_id = wp_insert_attachment( $attachment, $filename);
      
      if(strstr($movefile['type'], "image/")){
	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
	wp_update_attachment_metadata( $attach_id, $attach_data );
      }
      
      $single_img = wp_get_attachment_url( $attach_id );
      
    
    update_post_meta($post_id, "wdm-image-".$p, $single_img);
    
    if(isset($_POST['auction_main_image']) &&  ($_POST['auction_main_image'] == 'main_image_'.$p))
      update_post_meta($post_id, 'wdm_auction_thumb', $single_img);
}
else {
      _e("Sorry, this file can not be uploaded", "wdm-ultimate-auction");
}

}
?>
