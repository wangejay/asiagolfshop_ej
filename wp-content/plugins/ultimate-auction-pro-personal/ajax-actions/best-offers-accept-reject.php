<script type="text/javascript">
jQuery(document).ready(function($){
    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    
    $(".wdm-ua-bst-offr-reject").click( function(){
	
	var cnf = confirm('<?php _e("Are you sure to reject this offer?", "wdm-ultimate-auction");?>');
	
	if(cnf == true){
	    
	    $(this).html("<?php _e('Rejecting', 'wdm-ultimate-auction'); echo ' ';?> <img src='<?php echo plugins_url('/img/ajax-loader.gif', dirname(__FILE__) );?>' />");
	    
	    var $this = $( this );
        
	    var data = {
            action: 'wdm_best_offer_owner_action',
            owner_decision: 'reject',
            wdm_auction_id: $( this ).closest( ".wdm-ua-bst-offrs-box" ).data( "bst-offer-uaid" ),
            wdm_best_offer_sender_id: $( this ).data( "bst-offr-senderid" ) 
        }
	    $.post( ajaxurl, data, function( resp ){
	    var response = JSON.parse( resp );
            if ( response.status == "success" ) {
		
                $this.closest( ".wdm-ua-best-offer-row" ).remove();
		alert("<?php _e("This offer is rejected.", "wdm-ultimate-auction" ); ?>");
		$this.html("<?php _e('Reject', 'wdm-ultimate-auction');?>");
		window.location.reload();
            }
            else{
                alert("<?php _e("Issue occurred during rejecting the offer.", "wdm-ultimate-auction" ); ?>");
		$this.html("<?php _e('Reject', 'wdm-ultimate-auction');?>");
		window.location.reload();
            }
	    
        });
    }
	//window.location.reload();
	
	//return false;
    });
    
     $(".wdm-ua-bst-offr-accept").click( function(){
	
	var cnf = confirm('<?php _e("Do you want to accept this offer? This will expire this auction and make this user as winner.", "wdm-ultimate-auction");?>');
	
	if(cnf == true){
	
	$(this).html("<?php _e('Accepting', 'wdm-ultimate-auction'); echo ' ';?> <img src='<?php echo plugins_url('/img/ajax-loader.gif', dirname(__FILE__) );?>' />");
	
        var $this = $( this );
        
        var a_data = {
            action: 'wdm_best_offer_owner_action',
            owner_decision: 'accept',
            wdm_auction_id: $( this ).closest( ".wdm-ua-bst-offrs-box" ).data( "bst-offer-uaid" ),
            wdm_best_offer_sender_id: $( this ).data( "bst-offr-senderid" ),
	    wdm_best_offer_val: $( this ).data( "best-offer-val" ),
	    wdm_best_offer_sender_email: $( this ).data( "bst-offr-senderemail" ),
        }
        $.post( ajaxurl, a_data, function( resp ){
            var response = JSON.parse(resp );
            if ( response.accept_status == "success" ) {
                $this.closest( ".wdm-ua-bst-offrs-box" ).remove();
                alert("<?php _e("This offer is accepted.", "wdm-ultimate-auction" ); ?>"); 
            }
            else{
                 alert("<?php _e("Issue occurred during accepting the offer.", "wdm-ultimate-auction" ); ?>");
		 //window.location.reload();
            }
            $this.html("<?php _e('Accept', 'wdm-ultimate-auction');?>");
            if ( response.email_status ) {
                alert("<?php _e("Email sent successfully.", "wdm-ultimate-auction" ); ?>");
                window.location.reload();
            }
            else{
                 alert("<?php _e("Sorry, the email could not sent.", "wdm-ultimate-auction" ); ?>");
		 window.location.reload();
            }
        });
    }
    });

});
</script>