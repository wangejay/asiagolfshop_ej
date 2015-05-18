<?php $wp_upload_dir = wp_upload_dir();
$at_ids = array();
$all_files = array();

if ( ! function_exists( 'wp_handle_upload' ) ) require_once( ABSPATH . 'wp-admin/includes/file.php' );

if(!empty($_FILES)){
    
if(isset($_FILES['wdm_auc_mult_upload']) && !empty($_FILES['wdm_auc_mult_upload']['name'])){

$all_files = $_FILES['wdm_auc_mult_upload'];

$out = array();

    foreach ($all_files as  $rowkey => $row) {
        foreach($row as $colkey => $col){
            $out[$colkey][$rowkey]=$col;
        }
    }
  
foreach($out as $uploadedfile){
  $inc = $p++;

if(!empty($uploadedfile['name'])){

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
    
    if($attach_id)  
      $at_ids[] = $attach_id;
    
    update_post_meta($post_id, "wdm-image-".$inc, $single_img);
   
    if(isset($_POST['auction_main_image']) &&  ($_POST['auction_main_image'] == 'main_image_'.$inc))
      update_post_meta($post_id, 'wdm_auction_thumb', $single_img);
}
else {
      _e("Sorry, this file can not be uploaded", "wdm-ultimate-auction");
}

}
}
}
}

if(!empty($at_ids)){
  $ext_id = get_post_meta($post_id, 'wdm_product_image_gallery',true);
  
  if(!empty($ext_id)){
    $ext_id = explode(',', $ext_id);
    foreach($ext_id as $ed)
      $at_ids[] = $ed;
  }
  
  $allids = implode(',',$at_ids);
  update_post_meta($post_id, 'wdm_product_image_gallery',$allids);
}
?>
