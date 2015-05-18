<?php
$return_url = get_post_meta($single_auction->ID, 'current_auction_permalink',true);
$auction_type = get_post_meta($single_auction->ID,'wdm_product_type', true);

if($auction_type == 'digital'){
    
$link_html= "<div id='wdm-send-download-link-".$single_auction->ID."' class='wdm_ua_act_links' style='color:blue;cursor:pointer;'>".__('Send Download Link', 'wdm-ultimate-auction')."</div>";
$row['action'] .= "<br /><br />".$link_html;
?>

<script type="text/javascript">
    jQuery( document ).ready( function ( $ ) {
	
        var ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
	
        $( '#wdm-send-download-link-<?php echo $single_auction->ID; ?>' ).click( function () {

            var cnf = confirm( '<?php _e( "Are you sure you want to send the download link?", "wdm-ultimate-auction" ); ?>' );

            if ( cnf === true ) {
		$(this).html("<?php _e('Sending', 'wdm-ultimate-auction'); echo ' ';?> <img src='<?php echo plugins_url('/img/ajax-loader.gif', dirname(__FILE__) );?>' />");       
		var data = {
		action: 'wdm_digi_mail',
                auc_id: "<?php echo $single_auction->ID; ?>",
		p_url:"<?php echo $return_url;?>",
		s_email:"<?php echo $author_mail;?>"
	    };
	    
	    $.post(ajaxurl, data, function(response) {
		$('#wdm-send-download-link-<?php echo $single_auction->ID;?>').html("<?php echo $link_html;?>");
		alert(response);
	    }); 
	}

        return false;

        });

    } );
</script>
<?php }