<div id="wdm-add-auction-form" class="auction_settings_section_style">
    <h3> <?php _e('Import', 'wdm-ultimate-auction');?> </h3>
    <table class="form-table">
      <tr valign="top">
        <td scope="row">
	   <?php
		$file_url = plugins_url( '/assets/sample_file.csv' , __FILE__ );
		$rm_url = plugins_url( '/assets/readme.txt' , __FILE__ );
	   ?>
    <a href = "<?php echo $file_url; ?>" class="button-primary button"><?php _e('Download Sample CSV', 'wdm-ultimate-auction');?> </a>
    <a href = "<?php echo $rm_url; ?>" class="button-secondary button"><?php _e('Download Readme', 'wdm-ultimate-auction');?> </a>
    <br/><br/>
    
    <ul style = "list-style-type: disc !important;margin-left: 20px;">
      <li><?php _e('Upload a filled CSV file from your computer.', 'wdm-ultimate-auction'); ?></li>
      <li><?php _e('Click on Import button to import your CSV as new auctions (existing auctions will be skipped).', 'wdm-ultimate-auction');?></li>
      <li><?php _e('Product Title, Product Description, Product Short Description columns are mandatory.', 'wdm-ultimate-auction');?></li>
    </ul>
     </td>
    
    <td>
      <form action="" method="POST" enctype="multipart/form-data" name="import_form" id="import_form"> 
	<?php _e("Choose file", "wdm-ultimate-auction");?>  : <br />
	<input name="upload" type="hidden" value="imported" id="upload_file" />
        <input name="import_csv" type="file" id="import_csv" />
         <?php submit_button(__('Import', 'wdm-ultimate-auction')); ?>
      </form>
    </td>
    </tr>
      <tr valign="top">
	<td scope="row">
	<?php

if(isset($_POST["upload"]) && !empty($_POST["upload"])) {
$csv = array();

// check there are no errors
    if($_FILES['import_csv']['error'] == 0){
        $name = $_FILES['import_csv']['name'];
        $ext = strtolower(end(explode('.', $_FILES['import_csv']['name'])));
        $type = $_FILES['import_csv']['type'];
        $tmpName = $_FILES['import_csv']['tmp_name'];

    // check the file is a csv
    if($ext === 'csv'){
        if(($handle = fopen($tmpName, 'r')) !== FALSE) {
            // necessary if a large csv file
            set_time_limit(0);

            $row = 0;

        while(($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                // number of fields in the csv
                $num = count($data);
                //$arr = json_encode($data);
                // get the values from the csv
		if($row == 0){
		  $cntr=0;
		foreach ($data as &$value) {
		$header[] = lcfirst($value);
		$cntr++;
		}
		}
		
		if($row!=0){

    for($t = 0;$t<$cntr;$t++){
      $title = $header[$t];
      $array[$title] = $data[$t];
    }

		
		$get_post_id = wdm_add_auction_ajx($array);
		$result[] = wdm_add_post_param($get_post_id, $array, $header, $cntr);
		  

		}
	        // inc the row
                $row++;
            }
	    
            fclose($handle);
	    $wdm_products = sizeof($result);
	    if($wdm_products == $row-1){
	      ?>
	      <div class="updated fade below-h2"><p>
	      <?php printf(_n('You have successfully imported one auction.','You have successfully imported %s auctions.',$wdm_products,'wdm-ultimate-auction'), $wdm_products); 
	      ?>
	      </p></div>
	      <br />
	      <?php
	      for($i=1; $i<=$wdm_products; $i++){
		_e('Auction ID','wdm-ultimate-auction'); echo ": <strong>".$result[$i-1]."</strong>";?>
		<br /> <br />
	      <?php
	      }
	    }
        }
    }

  else {
  ?>
  <div class="error below-h2"><p>
  <?php _e('There is problem with the file. Please re-import..', 'wdm-ultimate-auction'); ?>
  </p></div>
  <?php
}

}

}
	
	?>
	</td>
      </tr>
    </table>
</div>
  
  <style>
    .auction_settings_section_style #submit {
 min-height: 0px !important; 
min-width: 95px !important;
  }
  </style>

   </input>
  </div>

<?php

function wdm_add_auction_ajx($data) {

  $args = array(
            'post_title'    => wp_strip_all_tags( $data['post_title'] ),//except for title all other fields are sanitized by wordpress
            'post_content'  => $data['post_content'],
            'post_type'     => 'ultimate-auction',
            'post_status'   => 'publish',
	    'post_excerpt'  =>  $data['post_excerpt']
            );
	    
            $post_id = wp_insert_post($args);
	    return $post_id;
  
}


function wdm_add_post_param($post_id,$data,$header,$cntr){
  
  update_post_meta($post_id, 'wdm-auth-key',md5(time().rand()));
  add_post_meta($post_id, 'wdm_creation_time', date("Y-m-d H:i:s", time()));
  
//   $temp = term_exists($data['auction-status'], 'auction-status');
//	   
//	  // print_r($temp); echo "</br>";
//	    wp_set_post_terms($post_id, $temp["term_id"], 'auction-status');
	    
	    if(isset($data['wdm_listing_starts']) && !empty($data['wdm_listing_starts'])){
		$temp = term_exists('scheduled', 'auction-status');
		wp_set_post_terms($post_id, $temp["term_id"], 'auction-status');
		update_post_meta($post_id, 'wdm_creation_time', date('Y-m-d H:i:s', strtotime(str_replace("'", "", $data['wdm_listing_starts']))));
	    }
	    else{
		$temp = term_exists('live', 'auction-status');
		wp_set_post_terms($post_id, $temp["term_id"], 'auction-status');
		 update_post_meta($post_id, 'wdm_listing_starts',"");
	    }
	    $term_ids = array();
	    $auc_terms = array();
	    
  for($s=0; $s<=$cntr; $s++)
	    {
		$wdm_head = $header[$s];   		
		
		if(strpos($wdm_head, 'wdm')!==FALSE){
		
		
		
		if($wdm_head == 'wdm_listing_starts' && isset($data['wdm_listing_starts']) && !empty($data['wdm_listing_starts'])){
		    update_post_meta($post_id, 'wdm_listing_starts',date('Y-m-d H:i:s', strtotime(str_replace("'", "", $data[$wdm_head]))));
		}
		else if($wdm_head == 'wdm_listing_ends' && isset($data['wdm_listing_ends']) && !empty($data['wdm_listing_ends'])){
		    update_post_meta($post_id, 'wdm_listing_ends',date('Y-m-d H:i:s', strtotime(str_replace("'", "", $data[$wdm_head]))));
		}
		
		else if($wdm_head == 'wdm_opening_bid' || $wdm_head == 'wdm_lowest_bid' || $wdm_head == 'wdm_incremental_val' || $wdm_head == 'wdm_buy_it_now') {		  
		 update_post_meta($post_id, $wdm_head,round($data[$wdm_head], 2));
		}
		else if($wdm_head == 'wdm-main-image'){
		  update_post_meta($post_id, $wdm_head,'main_image_'.$data[$wdm_head]);
		}
		
		else if($wdm_head != 'wdm_product_image_gallery'){
		  update_post_meta($post_id, $wdm_head,$data[$wdm_head]);
		}
		
	      }
		//Auction category and subcategory
		elseif($wdm_head == 'ua_auction_category')
		{
		    $auc_term = term_exists($data[$wdm_head], 'ua-auction-category', 0);
		    $term_ids[] = $auc_term['term_id'];
		}
		elseif($wdm_head == 'ua_auction_subcategory')
		{
		   $auc_cterm = term_exists($data[$wdm_head], 'ua-auction-category', $data['ua_auction_category']);
		    $term_ids[] = $auc_cterm['term_id'];
		}
	    }
	    
	     wp_set_post_terms($post_id, $term_ids, 'ua-auction-category');
	     
  for($im=1; $im<=4; $im++)
			{
			      if(get_post_meta($post_id,'wdm-main-image',true) == 'main_image_'.$im)
			      {
						$main_image = get_post_meta($post_id,'wdm-image-'.$im,true);
						update_post_meta($post_id, 'wdm_auction_thumb', $main_image);
			      }  
			}
  
  $image_gallary = $data['wdm_product_image_gallery'];
  $wdm_more_images = explode(',',$image_gallary);
  $no_of_images = count($wdm_more_images);
  for($wdm_im=0; $wdm_im < $no_of_images; $wdm_im++)
			{
						$im_no = $wdm_im + 5;
						update_post_meta($post_id, 'wdm-image-'.$im_no, $wdm_more_images[$wdm_im]);
				  
			}
    
  
  return $post_id;
   

	  
	}
    

?>

