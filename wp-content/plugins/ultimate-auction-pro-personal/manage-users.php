
	<form id="auction-users-form" class="auction_settings_section_style" method="post" action="options.php">
	        <?php
		    settings_fields('auction_users_option_group');//adds all the nonce/hidden fields and verifications	
		    do_settings_sections('auction-users-setting-admin');
		?>
	        <?php submit_button(__('Save Changes', 'wdm-ultimate-auction')); ?>
	    </form>