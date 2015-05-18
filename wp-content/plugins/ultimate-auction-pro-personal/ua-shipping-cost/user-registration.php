<?php
    //New user fields on the register form
    add_action('register_form','wdm_ua_register_form');
    function wdm_ua_register_form(){
        global $ua_all_countries;
        ?>
        <p>
	    <strong><?php _e('Mail Address','wdm-ultimate-auction');?></strong> 
	</p>
	<p class="shipping_reg_note message" style="margin: 10px 0 0;">
		<?php _e("This site works as an auction website.", "wdm-ultimate-auction");?> <br /><br />
		<?php _e("It is recommended to fill the following details. These details will be considered as shipping address whenever you win any auction.", "wdm-ultimate-auction");?> <br /><br />
		<?php _e("You will be able to change these details any time later on from your profile page.", "wdm-ultimate-auction");?>
	</p>
	<br />
        <?php
            $user_country = ( isset( $_POST['user_country'] ) ) ? $_POST['user_country']: '';
	    $user_street_add = ( isset( $_POST['user_street_add'] ) ) ? $_POST['user_street_add']: '';
	    $user_state = ( isset( $_POST['user_state'] ) ) ? $_POST['user_state']: '';
	    $user_pincode = ( isset( $_POST['user_pincode'] ) ) ? $_POST['user_pincode']: '';
	    $user_phno = ( isset($_POST['user_phno'] ) ) ? $_POST['user_phno']:'';
        ?>
        <p>
	    <label for='user_street_add'><?php _e('Street Address','wdm-ultimate-auction'); ?> *</label>
	    <textarea id='user_street_add' name='user_street_add' class='input'><?php echo $user_street_add;?></textarea>
	    <label for='user_state'><?php _e('State','wdm-ultimate-auction'); ?> *</label>
	    <input type='text' id='user_state' name='user_state' class='input' value='<?php echo $user_state;?>' />
	    <label for='user_pincode'><?php _e('Zip/Post Code','wdm-ultimate-auction'); ?> *</label>
	    <input type='text' id='user_pincode' name='user_pincode' class='input' value='<?php echo $user_pincode;?>' />
	    <label for='user_phno'><?php _e('Phone Number','wdm-ultimate-auction'); ?></label>
	    <input type='text' id='user_phno' name='user_phno' value='<?php echo $user_phno; ?>' />
	    <label for='user_country'><?php _e('Country','wdm-ultimate-auction'); ?> *</label>
            <?php
            echo "<select id='user_country' name='user_country' class='input'>";
            echo "<option value=''>".__('Select Country', 'wdm-ultimate-auction')."</option>";
                  
                        foreach($ua_all_countries as $ac_key => $ac){
                        $selected = ($user_country == $ac_key) ? 'selected="selected"' : '';
                        echo "<option value='".$ac_key."' $selected>$ac</option>";
                        }
                 
            echo "</select>";
             ?>
        </p>
	
	<script type="text/javascript">
	jQuery(document).ready(
			       function(){
				jQuery("#registerform").submit(
				    function(){
					if(jQuery("#user_street_add").val() == "" || jQuery("#user_state").val() == "" || jQuery("#user_pincode").val() == "")
					    alert("<?php _e('You have not filled all the details of your mail address. Do not forget to update remaining details in your profile page.', 'wdm-ultimate-auction');?>");
					}
				    );
			       });
	</script>
        <?php
    }

    //Validation
    add_filter('registration_errors', 'wdm_ua_registration_errors', 10, 3);
    function wdm_ua_registration_errors($errors, $sanitized_user_login, $user_email) {
        if ( empty( $_POST['user_country'] ) )
            $errors->add( 'user_country_error', '<strong>'.__('ERROR','wdm-ultimate-auction').'</strong>: '.__('You must choose the country.','wdm-ultimate-auction') );
	elseif(empty($_POST['user_street_add']))
	    $errors->add( 'user_streetadd_error', '<strong>'.__('ERROR','wdm-ultimate-auction').'</strong>: '.__('Please Enter street address.','wdm-ultimate-auction') );
	elseif(empty($_POST['user_state']))
	    $errors->add( 'user_state_error', '<strong>'.__('ERROR','wdm-ultimate-auction').'</strong>: '.__('Please Enter state.','wdm-ultimate-auction') );
	elseif(empty($_POST['user_pincode']))
	    $errors->add( 'user_pincode_error', '<strong>'.__('ERROR','wdm-ultimate-auction').'</strong>: '.__('Please Enter pincode.','wdm-ultimate-auction') );
	elseif(!empty($_POST['user_state']) && (ctype_digit($_POST['user_state']) || is_numeric($_POST['user_state'])))
	    $errors->add( 'user_state_error', '<strong>'.__('ERROR','wdm-ultimate-auction').'</strong>: '.__('Please enter a valid state name.','wdm-ultimate-auction') );
	elseif(!empty($_POST['user_pincode']) && !(preg_match('/^[a-zA-Z0-9 ]*$/', $_POST['user_pincode'])))
	    $errors->add( 'user_pincode_error', '<strong>'.__('ERROR','wdm-ultimate-auction').'</strong>: '.__('Please enter a valid zip code.','wdm-ultimate-auction') );
	elseif(!empty($_POST['user_phno']) && !(preg_match('/^[0-9 +-]{10,15}$/', $_POST['user_phno'])))
	    $errors->add('user_phno_error', '<strong>'.__('ERROR','wdm-ultimate-auction').'</strong>: '.__('Please enter valid phone number.','wdm-ultimate-auction'));
	    	    
        return $errors;
    }
    function wdm_ua_prof_update_errors($errors, $update, $user) {
	if(!empty($_POST['user_id'])) 
	{
	    if ( empty( $_POST['user_country'] ) )
            $errors->add( 'user_country_error', '<strong>'.__('ERROR','wdm-ultimate-auction').'</strong>: '.__('You must choose the country.','wdm-ultimate-auction') );
	elseif(empty($_POST['user_street_add']))
	    $errors->add( 'user_streetadd_error', '<strong>'.__('ERROR','wdm-ultimate-auction').'</strong>: '.__('Please Enter street address.','wdm-ultimate-auction') );
	elseif(empty($_POST['user_state']))
	    $errors->add( 'user_state_error', '<strong>'.__('ERROR','wdm-ultimate-auction').'</strong>: '.__('Please Enter state.','wdm-ultimate-auction') );
	elseif(empty($_POST['user_pincode']))
	    $errors->add( 'user_pincode_error', '<strong>'.__('ERROR','wdm-ultimate-auction').'</strong>: '.__('Please Enter pincode.','wdm-ultimate-auction') );
	elseif(!empty($_POST['user_state']) && (ctype_digit($_POST['user_state']) || is_numeric($_POST['user_state'])))
	    $errors->add( 'user_state_error', '<strong>'.__('ERROR','wdm-ultimate-auction').'</strong>: '.__('Please enter a valid state name.','wdm-ultimate-auction') );
	elseif(!empty($_POST['user_pincode']) && !(preg_match('/^[a-zA-Z0-9 ]*$/', $_POST['user_pincode'])))
	    $errors->add( 'user_pincode_error', '<strong>'.__('ERROR','wdm-ultimate-auction').'</strong>: '.__('Please enter a valid zip code.','wdm-ultimate-auction') );
	elseif(!empty($_POST['user_phno']) && !(preg_match('/^[0-9 +-]{10,15}$/', $_POST['user_phno'])))
	    $errors->add('user_phno_error', '<strong>'.__('ERROR','wdm-ultimate-auction').'</strong>: '.__('Please enter valid phone number.','wdm-ultimate-auction'));
	}    
        return $errors;
    }
    add_filter('user_profile_update_errors', 'wdm_ua_prof_update_errors', 10, 3);

    //Save user data
    add_action('user_register', 'wdm_ua_user_register');
    function wdm_ua_user_register($user_id) {
        if ( isset( $_POST['user_country'] ) )
            update_user_meta($user_id, 'ua_user_country', $_POST['user_country']);
	if ( isset( $_POST['user_street_add'] ) )
            update_user_meta($user_id, 'ua_user_street_add', $_POST['user_street_add']);
	if ( isset( $_POST['user_state'] ) )
            update_user_meta($user_id, 'ua_user_state', $_POST['user_state']);
	if ( isset( $_POST['user_pincode'] ) )
            update_user_meta($user_id, 'ua_user_pincode', $_POST['user_pincode']);
	if ( isset( $_POST['user_phno'] ) )
            update_user_meta($user_id, 'ua_user_phno', $_POST['user_phno']);
    }
    
    //add fields on user profile page
    add_action( 'show_user_profile', 'wdm_ua_mail_address' );
    add_action( 'edit_user_profile', 'wdm_ua_mail_address' );

    function wdm_ua_mail_address( $user ) {
        
        global $ua_all_countries;
        
        ?>

	<h2><?php _e('Mail Address','wdm-ultimate-auction');?></h2>

	<table class="form-table">
		<tr>
		    <th><label for="user_street_add"><?php _e('Street Address','wdm-ultimate-auction');?> *</label></th>
		    <td>
		    
			<?php
				$user_street_add = ( isset( $_POST['user_street_add'] ) ) ? $_POST['user_street_add']: esc_attr( get_the_author_meta( 'ua_user_street_add', $user->ID ) );
			?>
			<textarea id='user_street_add' name='user_street_add'><?php echo $user_street_add;?></textarea>
                        <span class="description"><?php _e('Enter your street address.','wdm-ultimate-auction');?></span>
		    </td>
		</tr>
		<tr>
			<th><label for="user_state"><?php _e('State','wdm-ultimate-auction');?> *</label></th>

			<td>
				<?php
                                        $user_state = ( isset( $_POST['user_state'] ) ) ? $_POST['user_state']: esc_attr( get_the_author_meta( 'ua_user_state', $user->ID ) );  
                                ?>
				 <input type='text' id='user_state' name='user_state' value='<?php echo $user_state;?>' />
                                <span class="description"><?php _e('Please enter state.','wdm-ultimate-auction');?></span>
			</td>
		</tr>
		<tr>
			<th><label for="user_pincode"><?php _e('Zip/Post Code','wdm-ultimate-auction');?> *</label></th>

			<td>
				<?php
                                        $user_pincode = ( isset( $_POST['user_pincode'] ) ) ? $_POST['user_pincode']: esc_attr( get_the_author_meta( 'ua_user_pincode', $user->ID ) );  
                                ?>
				<input type='text' id='user_pincode' name='user_pincode' value='<?php echo $user_pincode;?>' />
                                <span class="description"><?php _e('Please enter Zip code.','wdm-ultimate-auction');?></span>
			</td>
		</tr>
		<tr>
		    <th><label for="user_phno"><?php _e('Phone Number','wdm-ultimate-auction');?></label></th>
		    <td>
			<?php
				    $user_phno=(isset($_POST['user_phno'])) ? $_POST['user_phno']: esc_attr( get_the_author_meta( 'ua_user_phno', $user->ID ) );
			?>
			<input type='text' id='user_phno' name='user_phno' value='<?php echo $user_phno; ?>' />
                        <span class="description"><?php _e('Please enter Phone number.','wdm-ultimate-auction');?></span>
		    </td>
		</tr>
		<tr>
			<th><label for="user_country"><?php _e('Country','wdm-ultimate-auction');?> *</label></th>

			<td>
				<?php
                                        $user_country = ( isset( $_POST['user_country'] ) ) ? $_POST['user_country']: esc_attr( get_the_author_meta( 'ua_user_country', $user->ID ) );
                                  
                                        echo "<select id='user_country' name='user_country'>";
                                        echo "<option value=''>".__('Select Country','wdm-ultimate-auction')."</option>";
                  
                                        foreach($ua_all_countries as $ac_key => $ac){
                                        $selected = ($user_country == $ac_key) ? 'selected="selected"' : '';
                                        echo "<option value='$ac_key' $selected>$ac</option>";
                                        }
                 
                                        echo "</select>";
                                ?>
                                <span class="description"><?php _e('Please select country.','wdm-ultimate-auction');?></span>
			</td>
		</tr>
	</table>
<?php }

    //save user data from profile page
    //add_action( 'personal_options_update', 'wdm_ua_save_mail_address' );
    //add_action( 'edit_user_profile_update', 'wdm_ua_save_mail_address' );

    add_action('profile_update','wdm_ua_save_mail_address');
    function wdm_ua_save_mail_address( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;
	
	update_usermeta( $user_id, 'ua_user_country', $_POST['user_country'] );
	update_usermeta( $user_id, 'ua_user_street_add', $_POST['user_street_add'] );
	update_usermeta( $user_id, 'ua_user_state', $_POST['user_state'] );
	update_usermeta( $user_id, 'ua_user_pincode', $_POST['user_pincode'] );
	update_usermeta( $user_id, 'ua_user_phno', $_POST['user_phno']);
    }
?>