<?php
//handle basic plugin settings
if (!class_exists('wdm_settings')) {
	class wdm_settings {

		var $auction_id;
		var $auction_type;

		//constructor
		public function __construct() {
			if (is_admin()) {
				//call functions required only in admin section
				add_action('admin_menu', array($this, 'add_plugin_page'));
				add_action('admin_init', array($this, 'wdm_page_init'));
				add_action('admin_notices', array($this, 'wdm_admin_notices_action'));
				add_action('admin_enqueue_scripts', array($this, 'wdm_enqueue_scripts_styles'));

			}
			//register auction post
			add_action('init', array($this, 'wdm_reg_post_contents'));
			//add_filter( 'user_has_cap', array($this,'wdm_add_upload_file_cap'), 10, 3 );
		}

		public function wdm_reg_post_contents() {

			//register custom post ultimate-auction
			$args = array('public' => true, 'show_in_menu' => false, 'label' => 'Ultimate Auction');
			register_post_type('ultimate-auction', $args);

			//code for adding taxonomy auction-status
			$labels = array(
				'name' => 'Auction Status',
			);
			$args = array(
				'hierarchical' => true,
				'labels' => $labels,
				'show_ui' => true,
			);
			register_taxonomy(
				'auction-status',
				'ultimate-auction',
				$args
			);
			//code for adding taxonomy auction-status ends here

			//code for adding term live
			if (!term_exists('live', 'auction-status')) {
				$r = wp_insert_term(
					'live', // the term
					'auction-status' // the taxonomy
				);
			}

			//code for adding term expired
			if (!term_exists('expired', 'auction-status')) {
				$r = wp_insert_term(
					'expired', // the term
					'auction-status' // the taxonomy
				);
			}

			//code for adding term scheduled
			if (!term_exists('scheduled', 'auction-status')) {
				$r = wp_insert_term(
					'scheduled', // the term
					'auction-status' // the taxonomy
				);
			}

			wp_enqueue_script('jquery');
		}

		public function wdm_admin_notices_action() {
			settings_errors('test_option_group');
		}

		//register menus and submenus
		public function add_plugin_page() {
			global $wp_version;
			if ($wp_version >= '3.8') {
				$ua_icon_url = plugins_url('img/favicon.png', __FILE__);
			} else {
				$ua_icon_url = plugins_url('img/favicon_black.png', __FILE__);
			}

			$curr_user = wp_get_current_user();
			$curr_user_role = $curr_user->roles;

			if (current_user_can('administrator')) {
				$capability = 'administrator';
			} else {
				$capability = 'add_ultimate_auction';
			}

			add_menu_page(__('Ultimate Auction', 'wdm-ultimate-auction'), __('Ultimate Auction Pro', 'wdm-ultimate-auction'), $capability, 'ultimate-auction', array($this, 'create_admin_page'), $ua_icon_url);
			add_submenu_page('ultimate-auction', __('Settings', 'wdm-ultimate-auction'), __('Settings', 'wdm-ultimate-auction'), $capability, 'ultimate-auction', array($this, 'create_admin_page'));

			//if(in_array('administrator', $curr_user_role))
			add_submenu_page('ultimate-auction', __('Payment', 'wdm-ultimate-auction'), __('Payment', 'wdm-ultimate-auction'), $capability, 'payment', array($this, 'create_admin_page'));
			add_submenu_page('ultimate-auction', __('Email', 'wdm-ultimate-auction'), __('Email', 'wdm-ultimate-auction'), 'administrator', 'email', array($this, 'create_admin_page'));

			do_action('ua_add_submenu_after_setting', 'ultimate-auction', $capability, array($this, 'create_admin_page'));
			add_submenu_page('ultimate-auction', __('Add Auction', 'wdm-ultimate-auction'), __('Add Auction', 'wdm-ultimate-auction'), $capability, 'add-new-auction', array($this, 'create_admin_page'));
			add_submenu_page('ultimate-auction', __('Your Auctions', 'wdm-ultimate-auction'), __('Your Auctions', 'wdm-ultimate-auction'), $capability, 'manage_auctions', array($this, 'create_admin_page'));
			add_submenu_page('ultimate-auction', __('Your Bids', 'wdm-ultimate-auction'), __('Your Bids', 'wdm-ultimate-auction'), $capability, 'manage_bids', array($this, 'create_admin_page'));
			add_submenu_page('ultimate-auction', __('User Auctions', 'wdm-ultimate-auction'), __('User Auctions', 'wdm-ultimate-auction'), 'administrator', 'manage-user-auctions', array($this, 'create_admin_page'));
			add_submenu_page('ultimate-auction', __('User Bids', 'wdm-ultimate-auction'), __('User Bids', 'wdm-ultimate-auction'), 'administrator', 'manage-user-bids', array($this, 'create_admin_page'));
			add_submenu_page('ultimate-auction', __('Manage Users', 'wdm-ultimate-auction'), __('Manage Users', 'wdm-ultimate-auction'), 'administrator', 'manage-users', array($this, 'create_admin_page'));
			add_submenu_page('ultimate-auction', __('Fees', 'wdm-ultimate-auction'), __('Fees', 'wdm-ultimate-auction'), 'administrator', 'manage-fees', array($this, 'create_admin_page'));

			//if(!in_array('administrator', $curr_user_role))
			//    add_submenu_page( 'ultimate-auction', __('Payment', 'wdm-ultimate-auction'), __('Payment', 'wdm-ultimate-auction'), $capability, 'payment', array($this, 'create_admin_page') );

			do_action('ua_add_auction_submenu', 'ultimate-auction', $capability, array($this, 'create_admin_page'));
			if (!in_array('administrator', $curr_user_role)) {
				add_submenu_page('ultimate-auction', __('My Watchlist', 'wdm-ultimate-auction'), __('My Watchlist', 'wdm-ultimate-auction'), $capability, 'watchlist', array($this, 'create_admin_page'));
			}

			add_submenu_page('ultimate-auction', __('Import', 'wdm-ultimate-auction'), __('Import', 'wdm-ultimate-auction'), 'administrator', 'import', array($this, 'create_admin_page'));
			add_submenu_page('ultimate-auction', __('Support', 'wdm-ultimate-auction'), __('Support', 'wdm-ultimate-auction'), 'administrator', 'help-support', array($this, 'create_admin_page'));
		}

		public function wdm_enqueue_scripts_styles() {

			//enqueue validation files
			wp_enqueue_script('wdm_input_jq_validate', plugins_url('/js/wdm-jquery-validate.js', __FILE__), array('jquery'));
			$trans_arr = array('req' => __('This field is required.'),
				'eml' => __('Please enter a valid email address.'),
				'url' => __('Please enter a valid URL.'),
				'num' => __('Please enter a valid number.'),
				'min' => __('Please enter a value greater than or equal to 0'),
			);

			wp_localize_script('wdm_input_jq_validate', 'wdm_ua_obj_l10n', $trans_arr);
			wp_enqueue_script('wdm_input_jq_valid', plugins_url('/js/wdm-validate.js', __FILE__), array('jquery'));
			wp_enqueue_script('wdm_multi_upload', plugins_url('/js/wdm-upload.js', __FILE__), array('jquery'));
			wp_localize_script('wdm_multi_upload', 'wdm_ua_obj_multup', array('si' => __('selected', 'wdm-ultimate-auction')));

			wp_enqueue_script('wdm_ua_apprise_script', plugins_url('/js/apprise-1.5.full.js', __FILE__), array('jquery'));
			wp_enqueue_style('wdm_ua_apprise_style', plugins_url('/css/apprise.css', __FILE__));

			//enqueue css file for admin section style
			wp_enqueue_style('ult_auc_be_css', plugins_url('/css/ua-back-end.css', __FILE__));
			wp_enqueue_script('wdm_ua_ui_js', plugins_url('js/jquery-ui.min.js', __FILE__), array('jquery'));
			//enqueue js and style files to handle datetime picker and image upload
			wp_enqueue_style('thickbox');
			wp_enqueue_script('media-upload');
			wp_enqueue_script('thickbox');
			wp_enqueue_script('jquery-ui-datepicker');
			wp_enqueue_style('date-picker-style', plugins_url('/css/jquery-ui.css', __FILE__));
			wp_enqueue_script('jquery-timepicker-js', plugins_url('/js/date-picker.js', __FILE__), array('jquery', 'jquery-ui-datepicker'));
			wp_enqueue_style('jquery-timepicker-css', plugins_url('/css/jquery-time-picker.css', __FILE__));
		}

		public function create_admin_page() {
			?>
	<div class="wrap" id="wdm_auction_setID">
	    <?php screen_icon('options-general');?>
	    <h2><?php _e('Ultimate Auction Pro', 'wdm-ultimate-auction');?></h2>

            <!--code for displaying tabbed navigation-->
            <?php
if (isset($_GET['page'])) {
				$active_tab = $_GET['page'];
			} else {
				$active_tab = 'ultimate-auction';
			}

			$curr_user = wp_get_current_user();
			$curr_user_role = $curr_user->roles;

			?>
            <h2 class="nav-tab-wrapper">

                <a href="?page=ultimate-auction" class="ult-auc-settings-navtab nav-tab <?php echo $active_tab == 'ultimate-auction' ? 'nav-tab-active' : '';?>"><?php _e('Settings', 'wdm-ultimate-auction');?></a><!--
		--><?php //if(in_array('administrator', $curr_user_role)) {?><!--
		--><a href="?page=payment" class="ult-auc-settings-navtab nav-tab <?php echo $active_tab == 'payment' ? 'nav-tab-active' : '';?>"><?php _e('Payment', 'wdm-ultimate-auction');?></a><!--
		--><?php if (in_array('administrator', $curr_user_role)) {?><!-- 
		--><a href="?page=email" class="ult-auc-settings-navtab nav-tab <?php echo $active_tab == 'email' ? 'nav-tab-active' : '';?>"><?php _e('Email', 'wdm-ultimate-auction');?></a><?php }?><!--
		--><?php //}
		if (in_array('administrator', $curr_user_role)) {
		    do_action('ua_add_tab_after_setting', 'page', 'ult-auc-settings-navtab nav-tab', 'nav-tab-active', $active_tab);
		}
		?><!--
		--><a href="?page=add-new-auction" class="ult-auc-settings-navtab nav-tab <?php echo $active_tab == 'add-new-auction' ? 'nav-tab-active' : '';?>"><?php _e('Add Auction', 'wdm-ultimate-auction');?></a><!--
		--><a href="?page=manage_auctions" class="ult-auc-settings-navtab nav-tab <?php echo $active_tab == 'manage_auctions' ? 'nav-tab-active' : '';?>"><?php _e('Your Auctions', 'wdm-ultimate-auction');?></a><!--
		--><a href="?page=manage_bids" class="ult-auc-settings-navtab nav-tab <?php echo $active_tab == 'manage_bids' ? 'nav-tab-active' : '';?>"><?php _e('Your Bids', 'wdm-ultimate-auction');?></a><!--
		--><?php if (in_array('administrator', $curr_user_role)) {?><!--
		--><a href="?page=manage-user-auctions" class="ult-auc-settings-navtab nav-tab <?php echo $active_tab == 'manage-user-auctions' ? 'nav-tab-active' : '';?>"><?php _e('User Auctions', 'wdm-ultimate-auction');?></a><!--
		--><a href="?page=manage-user-bids" class="ult-auc-settings-navtab nav-tab <?php echo $active_tab == 'manage-user-bids' ? 'nav-tab-active' : '';?>"><?php _e('User Bids', 'wdm-ultimate-auction');?></a><!--
		--><a href="?page=manage-users" class="ult-auc-settings-navtab nav-tab <?php echo $active_tab == 'manage-users' ? 'nav-tab-active' : '';?>"><?php _e('Manage Users', 'wdm-ultimate-auction');?></a><!--
		--><a href="?page=manage-fees" class="ult-auc-settings-navtab nav-tab <?php echo $active_tab == 'manage-fees' ? 'nav-tab-active' : '';?>"><?php _e('Fees', 'wdm-ultimate-auction');?></a><!--
		--><?php
}

			//if(!in_array('administrator', $curr_user_role)){
			?><!--
--><!--		<a href="?page=payment" class="ult-auc-settings-navtab nav-tab <?php //echo $active_tab == 'payment' ? 'nav-tab-active' : ''; ?>"><?php //_e('Payment', 'wdm-ultimate-auction');?></a>--><!--
		--><?php
//}

			do_action('ua_add_auction_tab', 'page', 'ult-auc-settings-navtab nav-tab', 'nav-tab-active', $active_tab);

			if (!in_array('administrator', $curr_user_role)) {
				?><!--
		    --><a href="?page=watchlist" class="ult-auc-settings-navtab nav-tab <?php echo $active_tab == 'watchlist' ? 'nav-tab-active' : '';?>"><?php _e('My Watchlist', 'wdm-ultimate-auction');?></a><!--
		--><?php
}

			if (in_array('administrator', $curr_user_role)) {
				?><!--
		--><a href="?page=import" class="ult-auc-settings-navtab nav-tab <?php echo $active_tab == 'import' ? 'nav-tab-active' : '';?>"><?php _e('Import', 'wdm-ultimate-auction');?></a><!--
		--><a href="?page=help-support" class="ult-auc-settings-navtab nav-tab <?php echo $active_tab == 'help-support' ? 'nav-tab-active' : '';?>"><?php _e('Support', 'wdm-ultimate-auction');?></a>
            <?php }?>
	    </h2>
            <!--#code for displaying tabbed navigation-->

            <?php
if ($active_tab == 'ultimate-auction') {

				//if(current_user_can('administrator')){
				//    $capability = 'administrator';
				//}
				//else{
				//    $capability = 'add_ultimate_auction';
				//}

				if (in_array('administrator', $curr_user_role)) {
					if (isset($_GET['setting_section'])) {
						$manage_setting_tab = $_GET['setting_section'];
					} else {
						$manage_setting_tab = 'payment';
					}

					?>
	    <ul class="subsubsub">
		<li><a href="?page=ultimate-auction&setting_section=payment" class="<?php echo $manage_setting_tab == 'payment' ? 'current' : '';?>"><?php _e('Payment', 'wdm-ultimate-auction');?></a>|</li>
		<li><a href="?page=ultimate-auction&setting_section=auction" class="<?php echo $manage_setting_tab == 'auction' ? 'current' : '';?>"><?php _e('Auction', 'wdm-ultimate-auction');?></a>|</li>
		<li><a href="?page=ultimate-auction&setting_section=categories" class="<?php echo $manage_setting_tab == 'categories' ? 'current' : '';?>"><?php _e('Categories', 'wdm-ultimate-auction');?></a>|</li>
		<li><a href="?page=ultimate-auction&setting_section=email" class="<?php echo $manage_setting_tab == 'email' ? 'current' : '';?>"><?php _e('Email', 'wdm-ultimate-auction');?></a></li>
	    </ul><br class="clear">
	    <form id="auction-settings-form" class="auction_settings_section_style" method="post" action="options.php">
	        <?php
settings_fields('test_option_group'); //adds all the nonce/hidden fields and verifications
					do_settings_sections('test-setting-admin');
					echo wp_nonce_field('ua_setting_wp_n_f', 'ua_wdm_setting_auc');
					?>
	        <?php submit_button(__('Save Changes', 'wdm-ultimate-auction'));?>
	    </form>
            <?php
} elseif (current_user_can('add_ultimate_auction')) {

					$user_id = get_current_user_id();

					if (isset($_POST['user_wdm_setting_auc'])) {

						if (wp_verify_nonce($_POST['user_wdm_setting_auc'], 'user_setting_wp_n_f')) {
							if (isset($_POST['payment_options_enabled'])) {
								update_user_meta($user_id, 'payment_options_enabled', $_POST['payment_options_enabled']);
							}

						} else {
							die(__('Sorry, your nonce did not verify.', 'wdm-ultimate-auction'));
						}
					}
					?>
	    <?php

					$default = array("method_paypal" => __("PayPal", "wdm-ultimate-auction"), "method_wire_transfer" => __("Wire Transfer", "wdm-ultimate-auction"), "method_mailing" => __("Cheque", "wdm-ultimate-auction"), "method_cash" => __("Cash", "wdm-ultimate-auction"));

					$options = apply_filters('ua_add_new_payment_option', $default);

					add_user_meta($user_id, 'payment_options_enabled', array("method_paypal" => __("PayPal", "wdm-ultimate-auction")));

					?>
	<br class="clear">
	<?php

					$logged_user = wp_get_current_user(); //get user id
					$logged_user_role = $logged_user->roles; //get user role
					$comm_inv = get_option('wdm_manage_comm_invoice');
					$comm = false;

					if ($comm_inv == 'Yes' && (!in_array('administrator', $logged_user_role))) {
						$comm = true;
					}

					if ($comm) {
						$wit = "<br/><br/>What is this <a href='' class='auction_fields_tooltip'><strong>" . __('?', 'wdm-ultimate-auction') . "</strong><span style='width: 370px;margin-left: -90px;'>" . __("When there is a commission fee active, an amount from the total amount paid by the auction winner for your auction item will be supplied to the administrator when payment is made by winner.", "wdm-ultimate-auction") . "</span></a>";

						echo "<div class='error' style='padding:10px 5px;font-weight:bold;'>" . __("Please Note: The administrator has enabled commission fee for the auctions. So in this case, you will not be able to use any other payment method except PayPal when adding an auction.", "wdm-ultimate-auction") . $wit . "</div>";
					}
					?>
	<form id="auction-settings-form" class="auction_settings_section_style" action="" method="POST">
	    <?php echo "<h3>" . __("Payment Settings", "wdm-ultimate-auction") . "</h3>";?>
	    <table class="form-table">
	    <tr valign="top">
		<th scope="row">
		    <label><?php _e("Payment Methods", "wdm-ultimate-auction");?></label>
		</th>
		<td>
	    <?php

					$values = array();

					foreach ($options as $key => $option) {
						$values = get_user_meta($user_id, 'payment_options_enabled', true);
						$checked = (array_key_exists($key, $values)) ? ' checked="checked" ' : '';

						echo "<input $checked value='$option' name='payment_options_enabled[$key]' type='checkbox' /> $option <br />";
					}

					echo "<br /><br />";

					_e("NOTE: If you choose to activate any payment method, please go to Payment tab and enter its details. For example: if you enable Wire Transfer, go to Payment -> Wire Transfer and enter its details. Same would apply to PayPal, Cheque and Cash.", "wdm-ultimate-auction");

					?>
		</td>
	    </tr>
	    </table>
	    <?php

					echo wp_nonce_field('user_setting_wp_n_f', 'user_wdm_setting_auc');
					submit_button(__('Save Changes', 'wdm-ultimate-auction'));?>
	</form>
	<?php

				}
			} elseif ($active_tab == 'manage_auctions') {
				require_once 'manage-auctions.php';
			} elseif ($active_tab == 'manage_bids') {
				require_once 'manage-bids.php';
			} elseif ($active_tab == 'add-new-auction') {
				require_once 'add-new-auction.php';
			} elseif ($active_tab == 'manage-user-auctions') {
				require_once 'manage-user-auctions.php';
			} elseif ($active_tab == 'manage-user-bids') {
				require_once 'manage-user-bids.php';
			} elseif ($active_tab == 'manage-users') {
				require_once 'manage-users.php';
			} elseif ($active_tab == 'manage-fees') {
				require_once 'manage-fees.php';
			} elseif ($active_tab == 'help-support') {
				require_once 'help-and-support.php';
			} elseif ($active_tab == 'payment') {
				require_once 'payment.php';
			} elseif ($active_tab == 'email') {
				require_once 'email-settings.php';
			} elseif ($active_tab == 'import') {
				require_once 'ua-import.php';
			} elseif ($active_tab == 'watchlist') {
				require_once 'my-watchlist.php';
			}
			do_action('ua_call_setting_file', $active_tab);
			?>
	</div>

	<style type="text/css">
	    .ult-auc-settings-navtab{
		font-size:14px !important;
		font-weight:500 !important;
		padding: 4px !important;
		margin: 0 1px -1px 0;
	    }
	</style>

	<?php

			if (isset($_GET['settings-updated'])) {
				echo "<div class='updated'><p><strong>" . __('Settings saved.', 'wdm-ultimate-auction') . "</strong></p></div>";
			}
		}

		//create setting sections under 'Settings' tab to handle plugin configuration options
		public function wdm_page_init() {

			$curr_user = wp_get_current_user();
			$curr_user_role = $curr_user->roles;

			register_setting(
				'test_option_group', //this has to be same as the parameter in settings_fields
				'wdm_auc_settings_data', //The name of an option to sanitize and save, basically add_option('wdm_auc_settings_data')
				array($this, 'wdm_validate_save_data') //callback function for sanitizing data
			);

			//handle manage fees settings section

			register_setting('manage_fees_option_group', 'manage_fees_settings_data', array($this, 'manage_fees_save_data'));

			add_settings_section(
				'manage_fees_section_id',
				__('Listing Fee', 'wdm-ultimate-auction'),
				array($this, 'manage_fees_section_info'),
				'manage-fees-setting-admin'
			);

			add_settings_field(
				'manage_status_data',
				__('Activate', 'wdm-ultimate-auction'),
				array($this, 'manage_status_field'),
				'manage-fees-setting-admin',
				'manage_fees_section_id'
			);

			add_settings_field(
				'manage_fees_data',
				__('Fee Amount', 'wdm-ultimate-auction'),
				array($this, 'manage_fees_field'),
				'manage-fees-setting-admin',
				'manage_fees_section_id'
			);

			add_settings_section(
				'manage_com_fees_section_id',
				__('Commission Fee', 'wdm-ultimate-auction'),
				array($this, 'manage_com_fees_section_info'),
				'manage-fees-setting-admin'
			);

			add_settings_field(
				'manage_commission_invoice',
				__('Do you want to charge commission fee?', 'wdm-ultimate-auction'),
				array($this, 'manage_comm_invoice_field'),
				'manage-fees-setting-admin',
				'manage_com_fees_section_id'
			);

			do_action('wdm_ua_after_fees_settings', 'manage_com_fees_section_id', 'manage-fees-setting-admin');

			add_settings_field(
				'manage_commision_type',
				__('Commision Type', 'wdm-ultimate-auction'),
				array($this, 'manage_comm_type'),
				'manage-fees-setting-admin',
				'manage_com_fees_section_id'
			);

			add_settings_field(
				'manage_cm_fees_data',
				__('Fee Amount', 'wdm-ultimate-auction'),
				array($this, 'manage_cm_fees_field'),
				'manage-fees-setting-admin',
				'manage_com_fees_section_id'
			);

			//handle paypal payment information

			register_setting('paypal_option_group', 'paypal_inv_settings_data', 'paypal_validate_save_data');

			add_settings_section(
				'pp_setting_section_id',
				__('PayPal Settings', 'wdm-ultimate-auction'),
				'paypal_print_section_info',
				'paypal-setting-admin'
			);

			//add auction capability to other users

			register_setting('auction_users_option_group', 'auction_users_settings_data');

			add_settings_section(
				'auction_users_section_id',
				__('Which user role can post auctions', 'wdm-ultimate-auction'),
				array($this, 'auction_users_section_info'),
				'auction-users-setting-admin'
			);

			add_settings_field(
				'auction_users_data',
				__('Choose user roles who can post & manage their own auctions', 'wdm-ultimate-auction'),
				array($this, 'auction_users_field'),
				'auction-users-setting-admin',
				'auction_users_section_id'
			);

			//add_filter( 'user_has_cap', array($this,'wdm_add_upload_file_cap'), 10, 3 );

			if (isset($_GET["page"]) && $_GET["page"] == "ultimate-auction" && isset($_GET["setting_section"]) && $_GET["setting_section"] == "auction") {
				add_settings_section(
					'setting_section_id',
					__('General Settings', 'wdm-ultimate-auction'),
					array($this, 'print_section_info'),
					'test-setting-admin'
				);

				add_settings_field(
					'wdm_timezone_id',
					__('Timezone', 'wdm-ultimate-auction'),
					array($this, 'wdm_timezone_field'),
					'test-setting-admin',
					'setting_section_id'
				);

				$bidding_engine = array();

				$bidding_engine = apply_filters('ua_add_bidding_engine_option', $bidding_engine);

				if (!empty($bidding_engine)) {
					add_settings_field(
						'wdm_bidding_engines',
						__('Default Bidding Engine', 'wdm-ultimate-auction'),
						array($this, 'wdm_bid_engine_field'),
						'test-setting-admin',
						'setting_section_id',
						$bidding_engine
					);
				}

				/**
				 * Settings field for Auction Type ( Physical or Digital)
				 */

				add_settings_field(
					'wdm_auction_type',
					__('Do you want to auction Physical or Digital products?', 'wdm-ultimate-auction'),
					array($this, 'wdm_auction_type_field'),
					'test-setting-admin',
					'setting_section_id'
				);

				add_settings_field(
					'wdm_shipping_id',
					__('Collect Shipping During Registration', 'wdm-ultimate-auction'),
					array($this, 'wdm_shipping_field'),
					'test-setting-admin',
					'setting_section_id'
				);

				do_action('wdm_auto_extend_auction_endtime', 'test-setting-admin', 'setting_section_id');

				add_settings_field(
					'wdm_auctions_page_num_id',
					__('How many auctions should list on one feed page?', 'wdm-ultimate-auction'),
					array($this, 'wdm_auctions_num_per_page'),
					'test-setting-admin',
					'setting_section_id'
				);

				add_settings_field(
					'wdm_auction_url_id',
					__('Auction Page URL', 'wdm-ultimate-auction'),
					array($this, 'wdm_auction_url_field'),
					'test-setting-admin',
					'setting_section_id'
				);

				//do_action('wdm_ua_after_auction_page_url_settings', 'test-setting-admin', 'setting_section_id');

				add_settings_field(
					'wdm_dashboard_url_id',
					__('Front End Dashboard URL', 'wdm-ultimate-auction'),
					array($this, 'wdm_dashboard_url_field'),
					'test-setting-admin',
					'setting_section_id'
				);

				add_settings_field(
					'wdm_login_url_id',
					__('Login Page URL', 'wdm-ultimate-auction'),
					array($this, 'wdm_login_url_field'),
					'test-setting-admin',
					'setting_section_id'
				);

				add_settings_field(
					'wdm_register_url_id',
					__('Register Page URL', 'wdm-ultimate-auction'),
					array($this, 'wdm_register_url_field'),
					'test-setting-admin',
					'setting_section_id'
				);

				add_settings_field(
				'wdm_custom_field_data',
				__('Custom Fields', 'wdm-ultimate-auction'),
				array($this, 'wdm_custom_field'),
				'test-setting-admin',
				'setting_section_id'
				);
				
				add_settings_field(
					'wdm_listing_heading_id',
					__('Auction Listing Heading', 'wdm-ultimate-auction'),
					array($this, 'wdm_listing_heading_field'),
					'test-setting-admin',
					'setting_section_id'
				);

				add_settings_field(
					'wdm_comment_set_id',
					__('Show Comment Section', 'wdm-ultimate-auction'),
					array($this, 'wdm_comment_set_field'),
					'test-setting-admin',
					'setting_section_id'
				);

				add_settings_field(
					'wdm_show_prvt_msg_id',
					__('Show Send Private Message', 'wdm-ultimate-auction'),
					array($this, 'wdm_show_prvt_msg_field'),
					'test-setting-admin',
					'setting_section_id'
				);

				add_settings_field(
					'wdm_show_total_bids_placed',
					__('Show Total Bids Placed', 'wdm-ultimate-auction'),
					array($this, 'wdm_show_total_bids_placed_field'),
					'test-setting-admin',
					'setting_section_id'
				);

				//add_settings_field(
				//'wdm_show_terms_and_conditions',
				//__('Show Terms and Conditions', 'wdm-ultimate-auction'),
				//array($this, 'wdm_show_terms_and_conditions_field'),
				//'test-setting-admin',
				//'setting_section_id'
				//);

				add_settings_field(
					'wdm_powered_by_id',
					__('Powered By Ultimate Auction', 'wdm-ultimate-auction'),
					array($this, 'wdm_powered_by_field'),
					'test-setting-admin',
					'setting_section_id'
				);
			} elseif (isset($_GET["page"]) && $_GET["page"] == "ultimate-auction" && isset($_GET["setting_section"]) && $_GET["setting_section"] == "categories") {

				add_settings_section(
					'setting_section_id',
					__('Category Settings', 'wdm-ultimate-auction'),
					array($this, 'print_section_info'),
					'test-setting-admin'
				);

				do_action('wdm_ua_after_auction_page_url_settings', 'test-setting-admin', 'setting_section_id');
			} elseif (isset($_GET["page"]) && $_GET["page"] == "ultimate-auction" && isset($_GET["setting_section"]) && $_GET["setting_section"] == "email") {
				add_settings_section(
					'email_section_id', //this is the unique id for the section
					__('Email Settings', 'wdm-ultimate-auction'), //title or name of the section that appears on the page
					array($this, 'print_email_info'), //callback function
					'test-setting-admin' //the parameter in do_settings_sections
				);
				add_settings_field(
					'wdm_email_id',
					__('Admin Email', 'wdm-ultimate-auction'),
					array($this, 'wdm_email_field'),
					'test-setting-admin',
					'email_section_id'
				);
			} else {
				add_settings_section(
					'payment_section_id', //this is the unique id for the section
					__('Payment Settings', 'wdm-ultimate-auction'), //title or name of the section that appears on the page
					array($this, 'print_payment_info'), //callback function
					'test-setting-admin' //the parameter in do_settings_sections
				);

				add_settings_field(
					'wdm_currency_id', //unique id
					__('Currency', 'wdm-ultimate-auction'), //title of the field
					array($this, 'wdm_currency_field'), //callback function to display some html
					'test-setting-admin', //this has to be same as the parameter in do_settings_sections
					'payment_section_id' //id of the settings section that goes it into, so same as id of add_settings_section
				);

				add_settings_field(
					'wdm_payment_opt',
					__('Payment Methods', 'wdm-ultimate-auction'),
					array($this, 'wdm_set_payment_options'),
					'test-setting-admin',
					'payment_section_id'
				);

			}

		}

		//save/update fields under 'Settings tab'
		public function wdm_validate_save_data($input) {

			$mid = $input;

			if (isset($_POST['ua_wdm_setting_auc']) && wp_verify_nonce($_POST['ua_wdm_setting_auc'], 'ua_setting_wp_n_f')) {
				if (isset($mid['wdm_auction_email'])) {
					if (is_email($mid['wdm_auction_email'])) {
						update_option('wdm_auction_email', $mid['wdm_auction_email']);
					} else {
						add_settings_error(
							'test_option_group',
							'wdm-error',
							__('Please enter a valid Email address', 'wdm-ultimate-auction'),
							'error'
						);
						$mid['wdm_auction_email'] = "";
					}
				}
				if (isset($mid['wdm_time_zone'])) {
					update_option('wdm_time_zone', $mid['wdm_time_zone']);
				}

				/**
				 * Save Setting for Auction Type
				 */
				if (isset($mid['wdm_auction_type'])) {
					update_option('wdm_auction_type', $mid['wdm_auction_type']);
				}

				if (isset($mid['wdm_currency'])) {
					update_option('wdm_currency', $mid['wdm_currency']);
				}

				if (isset($mid['wdm_powered_by'])) {
					update_option('wdm_powered_by', $mid['wdm_powered_by']);
				}

				if (isset($mid['wdm_account_mode'])) {
					update_option('wdm_account_mode', $mid['wdm_account_mode']);
				}

				if (isset($mid['wdm_auction_page_url'])) {
					update_option('wdm_auction_page_url', $mid['wdm_auction_page_url']);
				}

				if (isset($mid['wdm_category_page_url'])) {
					update_option('wdm_category_page_url', $mid['wdm_category_page_url']);
				}

				if (isset($mid['wdm_dashboard_page_url'])) {
					update_option('wdm_dashboard_page_url', $mid['wdm_dashboard_page_url']);
				}

				if (isset($mid['wdm_login_page_url'])) {
					update_option('wdm_login_page_url', $mid['wdm_login_page_url']);
				}

				if (isset($mid['wdm_register_page_url'])) {
					update_option('wdm_register_page_url', $mid['wdm_register_page_url']);
				}

				if (isset($mid['wdm_listing_heading'])) {
					update_option('wdm_listing_heading', $mid['wdm_listing_heading']);
				}

				if(isset($mid['wdm_enable_shipping'])){
				    update_option('wdm_enable_shipping', $mid['wdm_enable_shipping']);
				}
				
				if (isset($mid['wdm_bidding_engines'])) {
					update_option('wdm_bidding_engines', $mid['wdm_bidding_engines']);
				}

				if (isset($mid['wdm_default_bidding_engine_set'])) {
					update_option('wdm_default_bidding_engine_set', $mid['wdm_default_bidding_engine_set']);
				}

				if (isset($mid['payment_options_enabled'])) {
					update_option('payment_options_enabled', $mid['payment_options_enabled']);
				}

				if (isset($mid['wdm_comment_set'])) {
					update_option('wdm_comment_set', $mid['wdm_comment_set']);
				}

				if (isset($mid['wdm_show_prvt_msg'])) {
					update_option('wdm_show_prvt_msg', $mid['wdm_show_prvt_msg']);
				}

				if (isset($mid['wdm_show_total_bids_placed'])) {
					update_option('wdm_show_total_bids_placed', $mid['wdm_show_total_bids_placed']);
				}

				//if(isset($mid['wdm_show_terms_and_conditions']))
				//update_option('wdm_show_terms_and_conditions',$mid['wdm_show_terms_and_conditions']);
				if (isset($mid['wdm_auc_num_per_page'])) {
					update_option('wdm_auc_num_per_page', $mid['wdm_auc_num_per_page']);
				}

				if(isset($_POST['wdm_custom_field'])){
				    
				    $merge = array();
				    
				    $array1 = array();
	
				    $array2 = array();
	
				    $array1 = $_POST['wdm_custom_field'];
	
				    $array2 = isset($_POST['wdm_custom_field_require']) ? $_POST['wdm_custom_field_require'] : array();
	
				    $count = count($array1) > count($array2) ? count($array1) : count($array2);
	
				    for($init=0; $init < $count; $init++){
	
					$c_data = array('label'=>$array1[$init],'required'=>$array2[$init]);
					
					array_push($merge, $c_data);
				    }
				    
				    update_option('wdm_custom_field', $merge);
				}
				elseif(isset($_POST['ua_wdm_setting_auc'])){
				    update_option('wdm_custom_field', array());
				}
				//do_action('wdm_save_valid_exttime',$mid['auto_extend_when'],$mid['auto_extend_time'], $mid['auto_extend_when_m'],$mid['auto_extend_time_m']);
				do_action('wdm_ua_update_ext_settings');
			} else {
				die(__('Sorry, your nonce did not verify.', 'wdm-ultimate-auction'));
			}

			return $mid;
		}

		//'General Settings' section
		public function print_section_info() {

		}

		//'Payment Settings' section
		public function print_payment_info() {
			wp_enqueue_script('curr-detect', plugins_url('/js/curr-detect.js', __FILE__), array('jquery'));
			wp_localize_script('curr-detect', 'curr_script_vars', array('ajax_url' => admin_url('admin-ajax.php')));
		}

		//'Email Settings' section
		public function print_email_info() {

		}

		//auctions per page
		public function wdm_auctions_num_per_page() {
			add_option('wdm_auc_num_per_page', 20);
			?>
	<input class="wdm_settings_input number required" min="1" type="number" size="5" id="wdm_auctions_page_num_id" name="wdm_auc_settings_data[wdm_auc_num_per_page]" value="<?php echo get_option('wdm_auc_num_per_page');?>" />
	<div class="ult-auc-settings-tip"><?php _e("Please enter a number greater than or equal to 1.", "wdm-ultimate-auction");?></div>
	<?php
}

		//admin email field
		public function wdm_email_field() {?>
        <input class="wdm_settings_input email required" type="text" id="wdm_email_id" name="wdm_auc_settings_data[wdm_auction_email]" value="<?php echo get_option('wdm_auction_email');?>" />
	<div class="ult-auc-settings-tip"><?php _e("You'll receive all bid notification for your auction + auction moderation emails.", "wdm-ultimate-auction");?></div>
    <?php }

		//timezone field
		public function wdm_timezone_field() {
			$timezone_identifiers = DateTimeZone::listIdentifiers();

			echo "<select class='wdm_settings_input required' id='wdm_timezone_id' name='wdm_auc_settings_data[wdm_time_zone]'>";
			echo "<option value=''>" . __('Select your Timezone', 'wdm-ultimate-auction') . "</option>";
			foreach ($timezone_identifiers as $time_ids) {
				$selected = (get_option('wdm_time_zone') == $time_ids) ? 'selected="selected"' : '';
				echo "<option value='$time_ids' $selected>$time_ids</option>";
			}
			echo "</select>";
			echo '<div class="ult-auc-settings-tip">' . __("Please select your local Timezone.", "wdm-ultimate-auction") . '</div>';
		}

		/**
		 * Adds field to setting Page for selection of Auction Type
		 */

		public function wdm_auction_type_field() {
			$default_product_type = get_option('wdm_auction_type');
			$physical_select = $default_product_type == "physical" ? "selected='selected'" : "";
			$digital_select = $default_product_type == "digital" ? "selected='selected'" : "";
			$both_select = $default_product_type == "both" ? "selected='selected'" : "";
			echo "<select class='wdm_settings_input' id='wdm_auction_type' name='wdm_auc_settings_data[wdm_auction_type]'>";
			echo "<option value='physical' $physical_select>" . __('Physical', 'wdm-ultimate-auction') . "</option>";
			echo "<option value='digital' $digital_select>" . __('Digital', 'wdm-ultimate-auction') . "</option>";
			echo "<option value='both' $both_select>" . __('Both', 'wdm-ultimate-auction') . "</option>";
			echo "</select>";
			echo '<div class="ult-auc-settings-tip">' . __("Please select Product type.", "wdm-ultimate-auction") . '</div>';
		}

		//shipping field
		public function wdm_shipping_field() {
			add_option('wdm_enable_shipping', "1");
			?>
	<input id="wdm_shipping_id" name="wdm_auc_settings_data[wdm_enable_shipping]" type="checkbox" value="1"  <?php checked("1", get_option("wdm_enable_shipping"));?> />
	<?php _e("Enable", "wdm-ultimate-auction");?>
	<?php
echo '<br /><br /><div class="ult-auc-settings-tip">' . __("PRO has ability to add shipping/postage cost to auctions. It requires to collect postal address for registered users. When you enable this option, PRO adds postal address fields like street address, city, country and phone number inside default WP register form. So, visitors will now register to your site by entering their postal address. When they bid and win the auction, PRO would automatically calculate postage cost based on their country and add that to final payable cost for winning the auction.", "wdm-ultimate-auction") . '</div>';

		}

		//extra bidding engine field
		public function wdm_bid_engine_field($bidding_engine) {

			add_option('wdm_bidding_engines', 'Simple Bidding');

			echo '<select id="bidding_engines" name="wdm_auc_settings_data[wdm_bidding_engines]">
	    <option value="">' . __("Simple Bidding", "wdm-ultimate-auction") . '</option>';

			foreach ($bidding_engine as $be) {
				$select = get_option("wdm_bidding_engines") == $be["val"] ? "selected" : "";
				echo '<option value="' . $be["val"] . '" ' . $select . '>' . $be["text"] . '</option>';
			}

			echo '</select>';
			echo '<div class="ult-auc-settings-tip">' . __("Please select a default bidding engine.", "wdm-ultimate-auction") . '</div>';

			echo "<br>";

			echo '<div class="ult-auc-settings-tip">• ' . __("Simple Bidding - User place a bid. Next bid starts from current bid plus increment value set by auctioneer. Each bidder needs to outbid higher bidder if they want to win the auction.", "wdm-ultimate-auction") . '</div>';

			echo "<br>";

			echo '<div class="ult-auc-settings-tip">• ' . __("Proxy Bidding (also known as Automatic Bidding) - Our automatic bidding system makes bidding convenient so you don't have to keep coming back to re-bid every time someone places another bid. When you place a bid, you enter the maximum amount you're willing to pay for the item. The seller and other bidders don't know your maximum bid. We'll place bids on your behalf using the automatic bid increment amount, which is based on the current high bid. We'll bid only as much as necessary to make sure that you remain the high bidder, or to meet the reserve price, up to your maximum amount.", "wdm-ultimate-auction") . '</div>';
			echo "<h4>" . __("Bidding Engine User Selection", "wdm-ultimate-auction") . "</h4>";
			$options = array("Yes", "No");
			add_option('wdm_default_bidding_engine_set', 'Yes');

			foreach ($options as $option) {
				$checked = (get_option('wdm_default_bidding_engine_set') == $option) ? ' checked="checked" ' : '';
				echo "<input " . $checked . " value='$option' name='wdm_auc_settings_data[wdm_default_bidding_engine_set]' type='radio' /> $option <br />";
			}
			printf("<div class='ult-auc-settings-tip'>" . __("Choose Yes if you want user to select bidding engine.", "wdm-ultimate-auction") . "</div>");
		}

		//plugin credit link field - (shown in footer of the site)
		function wdm_powered_by_field() {

			add_option('wdm_powered_by', 'Yes');

			$options = array('No', 'Yes');

			foreach ($options as $option) {

				$checked = (get_option('wdm_powered_by') == $option) ? ' checked="checked" ' : '';

				if ($option == 'No') {
					$opt = __('Nope - Got No Love', 'wdm-ultimate-auction');
				} else {
					$opt = __('Yep - I Love You Man', 'wdm-ultimate-auction');
				}

				echo "<input " . $checked . " value='$option' name='wdm_auc_settings_data[wdm_powered_by]' type='radio' /> $opt <br />";
			}
			echo '<div class="ult-auc-settings-tip">' . __("Can we show a cool stylish footer credit at the bottom the page?", "wdm-ultimate-auction") . '</div>';
		}

		//currency codes field
		public function wdm_currency_field() {

			$b = '$b';
			$U = '$U';

			$currencies = array("Albania Lek -Lek ALL",
				"Afghanistan Afghani -؋ AFN",
				"Argentina Peso -$ ARS",
				"Aruba Guilder -ƒ AWG",
				"Australian Dollar -$ AUD",
				"Azerbaijan Manat -ман AZN",
				"Bahamas Dollar -$ BSD",
				"Barbados Dollar -$ BBD",
				"Belarus Ruble -p. BYR",
				"Belize Dollar -BZ$ BZD",
				"Bermuda Dollar -$ BMD",
				"Bolivia Boliviano -$b BOB",
				"Bosnia and Herzegovina	Convertible Marka -KM BAM",
				"Botswana Pula -P BWP",
				"Bulgaria Lev -лв BGN",
				"Brazilian Real -R$ BRL",
				"Brunei Dollar -$ BND",
				"Cambodia Riel -៛ KHR",
				"Canadian Dollar -$ CAD",
				"Cayman Dollar -$ KYD",
				"Chile Peso -$ CLP",
				"China Yuan Renminbi -¥ CNY",
				"Colombia Peso -$ COP",
				"Costa Rica Colon -₡ CRC",
				"Croatia Kuna -kn HRK",
				"Cuba Peso -₱ CUP",
				"Czech Koruna -Kč CZK",
				"Danish Krone -kr DKK",
				"Dominican Republic Peso -RD$ DOP",
				"East Caribbean	Dollar -$ XCD",
				"Egypt Pound -£	EGP",
				"El Salvador Colon -$ SVC",
				"Estonia Kroon -kr EEK",
				"Euro -€ EUR",
				"Falkland Islands Pound	-£ FKP",
				"Fiji Dollar -$	FJD",
				"Ghana Cedis -¢	GHC",
				"Gibraltar Pound -£ GIP",
				"Guatemala Quetzal -Q GTQ",
				"Guernsey Pound	-£ GGP",
				"Guyana	Dollar -$ GYD",
				"Honduras Lempira -L HNL",
				"Hong Kong Dollar -$ HKD",
				"Hungarian Forint -kr HUF",
				"Iceland Krona -kr ISK",
				"Indian Rupee -₹ INR",
				"Indonesia Rupiah -Rp IDR",
				"Iran Rial -﷼ IRR",
				"Isle of Man Pound -£ IMP",
				"Israeli New Shekel -₪ ILS",
				"Jamaica Dollar	-J$ JMD",
				"Japanese Yen -¥ JPY",
				"Jersey	Pound -£ JEP",
				"Kazakhstan Tenge -лв KZT",
				"Korea (North) Won -₩ KPW",
				"Korea (South) Won -₩ KRW",
				"Kyrgyzstan Som	-лв KGS",
				"Laos Kip -₭ LAK",
				"Latvia	Lat -Ls	LVL",
				"Lebanon Pound -£ LBP",
				"Liberia Dollar	-$ LRD",
				"Lithuania Litas -Lt LTL",
				"Macedonia Denar -ден MKD",
				"Malaysian Ringgit -RM MYR",
				"Mauritius Rupee -₨ MUR",
				"Mexican Peso -$ MXN",
				"Mongolia Tughrik -₮ MNT",
				"Mozambique Metical -MT	MZN",
				"Namibia Dollar	-$ NAD",
				"Nepal	Rupee -₨ NPR",
				"Netherlands Antilles Guilder -ƒ ANG",
				"New Zealand Dollar -$ NZD",
				"Nicaragua Cordoba -C$	NIO",
				"Nigeria Naira	-₦ NGN",
				"Norwegian Krone -kr NOK",
				"Oman Rial -﷼ OMR",
				"Pakistan Rupee	-₨ PKR",
				"Panama	Balboa	-B/. PAB",
				"Paraguay Guarani -Gs PYG",
				"Peru Nuevo Sol	-S/. PEN",
				"Philippine Peso -₱ PHP",
				"Polish Zloty -zł PLN",
				"Qatar Riyal -﷼ QAR",
				"Romania New Leu -lei RON",
				"Russia	Ruble -руб RUB",
				"Saint Helena Pound -£ SHP",
				"Saudi Arabia Riyal -﷼	SAR",
				"Serbia	Dinar -Дин. RSD",
				"Seychelles Rupee -₨ SCR",
				"Singapore Dollar -$ SGD",
				"Solomon Islands Dollar	-$ SBD",
				"Somalia Shilling -S SOS",
				"South Africa Rand -R ZAR",
				"Sri Lanka Rupee -₨ LKR",
				"Swedish Krona -kr SEK",
				"Swiss Franc -CHF CHF",
				"Suriname Dollar -$ SRD",
				"Syria Pound -£	SYP",
				"New Taiwan Dollar -NT$ TWD",
				"Thai Baht -฿ THB",
				"Trinidad and Tobago Dollar -TT$ TTD",
				"Turkey	Lira -₤	TRL",
				"Tuvalu	Dollar	-$ TVD",
				"Ukraine Hryvna	-₴ UAH",
				"British Pound -£ GBP",
				"U.S. Dollar -$ USD",
				"Uruguay Peso $U UYU",
				"Uzbekistan Som -лв UZS",
				"Venezuela Bolivar Fuerte -Bs VEF",
				"Viet Nam Dong -₫ VND",
				"Yemen	Rial -﷼ YER",
				"Zimbabwe Dollar -Z$ ZWD",
				"Turkish Lira -₺ TRY",
			);
			$pp_currencies = array(
				"Australian Dollar -$ AUD",
				"Canadian Dollar -$ CAD",
				"Euro -€ EUR",
				"British Pound -£ GBP",
				"Japanese Yen -¥ JPY",
				"U.S. Dollar -$ USD",
				"New Zealand Dollar -$ NZD",
				"Swiss Franc -CHF CHF",
				"Hong Kong Dollar -$ HKD",
				"Singapore Dollar -$ SGD",
				"Swedish Krona -kr SEK",
				"Danish Krone -kr DKK",
				"Polish Zloty -zł PLN",
				"Norwegian Krone -kr NOK",
				"Hungarian Forint -kr HUF",
				"Czech Koruna -Kč CZK",
				"Israeli New Shekel -₪ ILS",
				"Mexican Peso -$ MXN",
				"Brazilian Real -R$ BRL",
				"Malaysian Ringgit -RM MYR",
				"Philippine Peso -₱ PHP",
				"New Taiwan Dollar -NT$ TWD",
				"Thai Baht -฿ THB",
				"Turkish Lira -₺ TRY",
			);

			echo "<select class='wdm_settings_input' id='wdm_currency_id' name='wdm_auc_settings_data[wdm_currency]'>";

			foreach ($currencies as $currency) {
				$selected = (substr(get_option('wdm_currency'), -3) == substr($currency, -3)) ? 'selected="selected"' : '';

				if (!in_array($currency, $pp_currencies)) {
					echo "<option data-curr='npl' value='$currency' $selected>$currency</option>";
				} else {
					echo "<option value='$currency' $selected>$currency</option>";
				}

			}

			echo "</select>";

			echo ' <div id="nonpaypal" style="display: none; color: red;">' . __("This currency is not available for PayPal.", "wdm-ultimate-auction") . '</div>';

		}

		public function wdm_set_payment_options() {
			$default = array("method_paypal" => __("PayPal", "wdm-ultimate-auction"), "method_wire_transfer" => __("Wire Transfer", "wdm-ultimate-auction"), "method_mailing" => __("Cheque", "wdm-ultimate-auction"), "method_cash" => __("Cash", "wdm-ultimate-auction"));

			$options = apply_filters('ua_add_new_payment_option', $default);

			add_option('payment_options_enabled', array("method_paypal" => __("PayPal", "wdm-ultimate-auction")));

			$values = array();

			foreach ($options as $key => $option) {
				$values = get_option('payment_options_enabled');
				$checked = (array_key_exists($key, $values)) ? ' checked="checked" ' : '';

				echo "<input $checked value='$option' name='wdm_auc_settings_data[payment_options_enabled][$key]' type='checkbox' class=wdm_$key /> $option <br />";
			}
			echo '<br/><br/>';

			_e("NOTE: If you choose to activate any payment method, please go to Payment tab and enter its details. For example: if you enable Wire Transfer, go to Payment -> Wire Transfer and enter its details. Same would apply to PayPal, Cheque and Cash.", "wdm-ultimate-auction");

		}

		//Auction feeder page URL
		public function wdm_auction_url_field() {
			?>
        <input type="text" class="wdm_settings_input url" id="wdm_auction_url_id" name="wdm_auc_settings_data[wdm_auction_page_url]" size="40" value="<?php echo get_option('wdm_auction_page_url');?>" />
    <div>
	<span class="ult-auc-settings-tip"><?php _e("Enter your auction feeder page URL.", "wdm-ultimate-auction");?></span>

	<a href="" class="auction_fields_tooltip"><strong><?php _e("?", "wdm-ultimate-auction");?></strong>
	    <span style="width: 370px;margin-left: -90px;">
	    <?php _e("If you want to make each auction title as a link to the front end single auction page in 'Title' columns of the plugin dashboard, you'll need to enter front end URL of the page where you have used shortcode for auctions listing.", "wdm-ultimate-auction");?>
	    <br /><br />
	    <?php _e("NOTE: Whenever you change the permalink, do not forget to enter the modified URL here. Also, if you select auction page as Home page, do not enter Home page URL, instead use actual full URL of the feeder page.", "wdm-ultimate-auction");?>
	    </span>
	</a>
	<br /><br />
	<span class="ult-auc-settings-tip"><?php _e("Use this shortcode in a page to make it auction feeder page:", "wdm-ultimate-auction");?></span>
	<?php echo "<code>[wdm_auction_listing]</code>";?>
    </div>
    <?php
}

		//Front end Dashboard page URL
		public function wdm_dashboard_url_field() {
			?>
        <input type="text" class="wdm_settings_input url" id="wdm_dashboard_url_id" name="wdm_auc_settings_data[wdm_dashboard_page_url]" size="40" value="<?php echo get_option('wdm_dashboard_page_url');?>" />
    <div>
	<span class="ult-auc-settings-tip"><?php _e("Enter your front end dashboard page URL.", "wdm-ultimate-auction");?></span>

	<a href="" class="auction_fields_tooltip"><strong><?php _e("?", "wdm-ultimate-auction");?></strong>
	    <span style="width: 370px;margin-left: -90px;">
	    <?php _e("If you want a non logged in user to be redirected on the front end dashboard after logging in, you'll need to specify URL of the page where you have used shortcode for front end dashboard.", "wdm-ultimate-auction");?>
	    <br /><br />
	    <?php _e("NOTE: Whenever you change the permalink, do not forget to enter the modified URL here.", "wdm-ultimate-auction");?>
	    </span>
	</a>
	<br /><br />
	<span class="ult-auc-settings-tip"><?php _e("Use this shortcode in a page to make it front end dashboard page:", "wdm-ultimate-auction");?></span>
	<?php echo "<code>[wdm_user_dashboard]</code>";?>
    </div>
    <?php
}

		//Front end Login page URL
		public function wdm_login_url_field() {
			?>
        <input type="text" class="wdm_settings_input url" id="wdm_login_url_id" name="wdm_auc_settings_data[wdm_login_page_url]" size="40" value="<?php echo get_option('wdm_login_page_url');?>" />
    <div>
	<span class="ult-auc-settings-tip"><?php _e("Enter Custom Login page URL (if have any).", "wdm-ultimate-auction");?></span>

	<a href="" class="auction_fields_tooltip"><strong><?php _e("?", "wdm-ultimate-auction");?></strong>
	    <span style="width: 370px;margin-left: -90px;">
	    <?php _e("If your site has a custom Login page and you want the bidders should log in through that page, you should set its URL here, so that while placing the bid, non logged in bidders should visit the custom Login page and not the default WordPress Login page. Please note, with the custom login URL the bidder will not be redirected automatically to the auction page where he/she was going to place the bid. As of now, this functionality works with the default WordPress Login page only. Also, whenever you change the permalink, do not forget to enter the modified URL over here.", "wdm-ultimate-auction");?>
	    <br /><br />
	    <?php _e("NOTE: If your site uses default WordPress Login page. You don't need to set it.", "wdm-ultimate-auction");?>
	    </span>
	</a>

    <br /><br />
    <span class="ult-auc-settings-tip"><?php _e("PRO shows pop-up having login and register buttons to visitors (non logged-in users). Enter your login url here to redirect login button to it. Leave it blank to redirect to default WP login page.", "wdm-ultimate-auction");?></span>

    </div>
    <?php
}

		//Front end Register page URL
		public function wdm_register_url_field() {
			?>
        <input type="text" class="wdm_settings_input url" id="wdm_register_url_id" name="wdm_auc_settings_data[wdm_register_page_url]" size="40" value="<?php echo get_option('wdm_register_page_url');?>" />
    <div>
	<span class="ult-auc-settings-tip"><?php _e("Enter Custom Registration page URL (if have any).", "wdm-ultimate-auction");?></span>

	<a href="" class="auction_fields_tooltip"><strong><?php _e("?", "wdm-ultimate-auction");?></strong>
	    <span style="width: 370px;margin-left: -90px;">
	    <?php _e("If your site has a custom Register page and you want the bidders should register through that page, you should set its URL here, so that while placing the bid, non registered bidders should visit the custom Register page and not the default WordPress Register page. Also, whenever you change the permalink, do not forget to enter the modified URL over here.", "wdm-ultimate-auction");?>
	    <br /><br />
	    <?php _e("NOTE: If your site uses default WordPress Register page. You don't need to set it.", "wdm-ultimate-auction");?>
	    </span>
	</a>

    <br /><br />
    <span class="ult-auc-settings-tip"><?php _e("PRO shows pop-up having login and register buttons to visitors (non logged-in users). Enter your Register url here to redirect register button to it. Leave it blank to redirect to default WP register page.", "wdm-ultimate-auction");?></span>

    </div>
    <?php
}

public function wdm_custom_field(){
	
    $custom_fields = array();

    $custom_fields = get_option('wdm_custom_field');

    $count = 0;
    
    $count = count($custom_fields);
    
    if(empty($count))
	$count = 0;

	?>
	<div id="custom_field_add_remove">
	    <div style="margin-bottom:5px;">
	    <input type="button" id="plus_field" class="button button-secondary" value="<?php _e('Add New', 'wdm-ultimate-auction');?>" />
	    </div>
	<?php
	
	if($count>0){
	    
	for($init=1;$init<=$count;$init++)
	{
	    if($custom_fields[$init-1]['required'] == 1)
		$selected='selected="selected"';
	    else
		$selected='';
	    
		    ?>
		    <div id="wdm_custom_field_<?php echo $init-1;?>">
		    <input type="text" class="wdm_custom_field_class" id="wdm_custom_field_0" size="40" name="wdm_custom_field[]" value="<?php echo $custom_fields[$init-1]['label'];?>"/>
		    <select id="require_<?php echo $init-1;?>" name="wdm_custom_field_require[]">
			<option value="0"><?php _e('Optional', 'wdm-ultimate-auction');?></option>
			<option value="1" <?php echo $selected;?>><?php _e('Required', 'wdm-ultimate-auction');?></option>
		    </select>
		    
		    <input type="button" class="button button-secondary minus_field" value="-" data-custom="<?php echo($init-1);?>" />
		    </div>
		    <?php
	}
?>
<?php
	}
    ?>
    </div>
	
    <script type="text/javascript">

	 var flag=<?php echo($count);?>;

	 var arr=[];

	jQuery('#plus_field').click(function(){

	  jQuery('#custom_field_add_remove').append('<div id="wdm_custom_field_'+flag+'"><input type="text" class="wdm_custom_field_class" size="40" name="wdm_custom_field[]" value="" /> <select id="require_'+flag+'" class="require_" class="require_" data-custom="'+flag+'" name="wdm_custom_field_require[]"><option value="0">Optional</option><option value="1">Required</option></select><input type="button" class="button button-secondary minus_field" value="-" data-custom="'+flag+'"></div>');

	  flag++;

	});

	jQuery(document).on('click', '.minus_field' ,function(){
	    var id=jQuery(this).attr('data-custom');
	    var id_name="wdm_custom_field_"+id+"";
	    jQuery('#'+id_name+'').remove();
	});

	jQuery(document).on('change', '.require_', function(){
	    var id=jQuery(this).attr('data-custom');
	    var id_name="require_"+id+"";
	    if(jQuery('#'+id_name+'').attr('checked')) 
		arr.push(a);	
	    else
	    {	
		var index = arr.indexOf(a);	
		arr.splice(index,1);
	    }
	});
    </script>

    <?php
    }
    //Heading before auctions listing
    public function wdm_listing_heading_field() {
			?>
        <textarea class="wdm_settings_input" id="wdm_listing_heading_id" name="wdm_auc_settings_data[wdm_listing_heading]"><?php echo get_option('wdm_listing_heading');?></textarea>
    <div class="ult-auc-settings-tip"><?php _e("Enter heading contents which will be shown on top of the auctions listing on feeder page.", "wdm-ultimate-auction");?></div>
    <?php
}

		//Comment set section
		public function wdm_comment_set_field() {
			$options = array("Yes", "No");

			add_option('wdm_comment_set', 'Yes');

			foreach ($options as $option) {
				$checked = (get_option('wdm_comment_set') == $option) ? ' checked="checked" ' : '';
				echo "<input " . $checked . " value='$option' name='wdm_auc_settings_data[wdm_comment_set]' type='radio' /> $option <br />";
			}
			printf("<div class='ult-auc-settings-tip'>" . __("Choose Yes if you want to display comments tab on the dedicated page.", "wdm-ultimate-auction") . "</div>");
		}

		public function wdm_show_prvt_msg_field() {
			$options = array("Yes", "No");

			add_option('wdm_show_prvt_msg', 'Yes');

			foreach ($options as $option) {
				$checked = (get_option('wdm_show_prvt_msg') == $option) ? ' checked="checked" ' : '';
				echo "<input " . $checked . " value='$option' name='wdm_auc_settings_data[wdm_show_prvt_msg]' type='radio' /> $option <br />";
			}
			printf("<div class='ult-auc-settings-tip'>" . __("Choose Yes if you want to display private message tab on the dedicated page.", "wdm-ultimate-auction") . "</div>");
		}

		public function wdm_show_total_bids_placed_field() {
			$options = array("Yes", "No");

			add_option('wdm_show_total_bids_placed', 'Yes');

			foreach ($options as $option) {
				$checked = (get_option('wdm_show_total_bids_placed') == $option) ? ' checked="checked" ' : '';
				echo "<input " . $checked . " value='$option' name='wdm_auc_settings_data[wdm_show_total_bids_placed]' type='radio' /> $option <br />";
			}
			printf("<div class='ult-auc-settings-tip'>" . __("Choose Yes if you want to display total bids tab on the dedicated page.", "wdm-ultimate-auction") . "</div>");
		}

//    public function wdm_show_terms_and_conditions_field()
		//    {
		//	$options = array("Yes", "No");
		//
		//	add_option('wdm_show_terms_and_conditions','Yes');
		//
		//	foreach($options as $option) {
		//		$checked = (get_option('wdm_show_terms_and_conditions')== $option) ? ' checked="checked" ' : '';
		//		echo "<input ".$checked." value='$option' name='wdm_auc_settings_data[wdm_show_terms_and_conditions]' type='radio' /> $option <br />";
		//	}
		//	printf("<div class='ult-auc-settings-tip'>".__("Choose Yes if you want to display terms and conditions tab on the dedicated page.", "wdm-ultimate-auction")."</div>");
		//    }

		//Manage Fees Section
		public function manage_fees_save_data($input) {

			if (isset($_POST['ua_wdm_fees_auc']) && wp_verify_nonce($_POST['ua_wdm_fees_auc'], 'ua_fees_wp_n_f')) {
				update_option('wdm_manage_status_data', $input['wdm_manage_status_data']);
				update_option('wdm_manage_fees_data', round($input['wdm_manage_fees_data'], 2));
				update_option('wdm_manage_cm_fees_data', $input['wdm_manage_cm_fees_data']);
				update_option('wdm_manage_comm_invoice', $input['wdm_manage_comm_invoice']);
				update_option('wdm_manage_comm_type', $input['wdm_manage_comm_type']);
			} else {
				die(__('Sorry, your nonce did not verify.', 'wdm-ultimate-auction'));
			}

			return $input;
		}

		public function manage_fees_section_info() {

		}

		public function manage_com_fees_section_info() {

		}

		public function manage_status_field() {

			add_option('wdm_manage_status_data', 'Inactive');

			$options = array('Active', 'Inactive');

			foreach ($options as $option) {

				$checked = (get_option('wdm_manage_status_data') == $option) ? ' checked="checked" ' : '';

				if ($option == 'Active') {
					$opt = __('Yes', 'wdm-ultimate-auction');
				} else {
					$opt = __('No', 'wdm-ultimate-auction');
				}

				echo "<input " . $checked . " value='$option' class='wdm_mng_fee' name='manage_fees_settings_data[wdm_manage_status_data]' type='radio' /> $opt &nbsp;&nbsp;";
			}

			?>
	<a href="" class="auction_fields_tooltip"><strong><?php _e("?", "wdm-ultimate-auction");?></strong>
	<span style="width: 370px;margin-left: -90px;">
		    <?php _e("When it is active, users who will add their auctions would be prompted 'Make Payment' alert window. If they pay the fee then their auction will be activated automatically else their auction will be discarded. When de-activated, users can add their auctions without any restriction.", "wdm-ultimate-auction");?>
	</span>
	</a>
	    <?php
}

		public function manage_fees_field() {
			$cc = substr(get_option('wdm_currency'), -3);
			echo $cc;
			?>
        <input class="small-text number" min="0" type="text" id="manage_fees_data" name="manage_fees_settings_data[wdm_manage_fees_data]" value="<?php echo get_option('wdm_manage_fees_data');?>" />
    <?php }

		public function manage_cm_fees_field() {
			?>
        <input class="small-text number" min="0" type="text" id="manage_cm_fees_data" name="manage_fees_settings_data[wdm_manage_cm_fees_data]" value="<?php echo get_option('wdm_manage_cm_fees_data');?>" />
	&nbsp;&nbsp;
	<a href="" class="auction_fields_tooltip"><strong><?php _e("?", "wdm-ultimate-auction");?></strong>
	<span style="width: 370px;margin-left: -90px;">
		    <?php _e("Admin is in full control of all the payment transaction on his site. Lets take an example: If user A has won auction of user B, then User A will make a payment. This payment will come to admin. Plugin deducts admin's commission automatically from it. Admin will then get an option to pay remaining amount to User B for his auction.", "wdm-ultimate-auction");?>
	</span>
	</a>
	<div class="ult-auc-settings-tip"><?php _e('This fee is applicable only when you have chosen YES in above setting.', 'wdm-ultimate-auction');?></div>
	<?php
}

		public function manage_comm_invoice_field() {
			add_option('wdm_manage_comm_invoice', 'Yes');

			$options = array('Yes', 'No');

			foreach ($options as $option) {

				$checked = (get_option('wdm_manage_comm_invoice') == $option) ? ' checked="checked" ' : '';

				if ($option == 'Yes') {
					$opt = __('Yes', 'wdm-ultimate-auction');
				} else {
					$opt = __('No', 'wdm-ultimate-auction');
				}

				echo "<input " . $checked . " value='$option' class='wdm_mng_fee' name='manage_fees_settings_data[wdm_manage_comm_invoice]' type='radio' /> $opt &nbsp;&nbsp;";
			}

			?>
		<a href="" class="auction_fields_tooltip"><strong><?php _e("?", "wdm-ultimate-auction");?></strong>
		    <span style="width: 370px;margin-left: -90px;">
			<?php //_e("If chosen 'Yes', then all payments for user's auctions would also come to you from the bidders & you would be needed to transfer them to actual auction owners. If chosen 'No', then plugin will send email alerts to bidder with paypal link to make the payment to auction owner and an email alert to owner mentioning bidders shipping address & other details.", "wdm-ultimate-auction");
			_e('If you choose YES, then final amount will be split into two parts. You will receive your commission amount and auction owner will receive remaining along with shipping amount (if shipping charge is applicable).', 'wdm-ultimate-auction');
			?>
		    </span>
		</a>
		<div class="ult-auc-settings-tip">
		<?php /*_e('NOTE: If you choose YES, then you will be needed to moderate payments for auction owner.', 'wdm-ultimate-auction');*/_e('NOTE: The commission fee is only applicable if payment method for the auction sold is PayPal.', 'wdm-ultimate-auction');?>
		</div>
	<?php

		}

		public function manage_comm_type() {
			add_option('wdm_manage_comm_type', 'Percentage');

			$options = array('Percentage', 'Flat Fee');

			foreach ($options as $option) {

				$checked = (get_option('wdm_manage_comm_type') == $option) ? ' checked="checked" ' : '';

				if ($option == 'Percentage') {
					$opt = __('Percentage', 'wdm-ultimate-auction');
				} else {
					$opt = __('Flat Fee', 'wdm-ultimate-auction');
				}

				echo "<input id='manage_commision_type' " . $checked . " value='$option' class='wdm_mng_fee' name='manage_fees_settings_data[wdm_manage_comm_type]' type='radio' /> $opt &nbsp;&nbsp;";
			}

			?>
	<div class="ult-auc-settings-tip"><?php _e("NOTE: If you choose 'Percentage' then the below entered value is treated as percentage of the total amount of the product, otherwise as fixed amount.", "wdm-ultimate-auction");?></div>
	<?php

		}

		//Users mapping section
		public function auction_users_section_info() {

		}

		public function auction_users_field() {

			$users_data = get_option('auction_users_settings_data');

			if (!isset($wp_roles)) {
				$wp_roles = new WP_Roles();
			}

			$all_roles = $wp_roles->roles;

			foreach ($all_roles as $role => $details) {
				if ($role !== 'administrator') {
					$name = translate_user_role($details['name']);
					//$user_role = isset($users_data[esc_attr($role)]) ? $users_data[esc_attr($role)] : '';
					if (isset($users_data[esc_attr($role)])) {
						$user_role = $users_data[esc_attr($role)];

						$user = get_role($role);
						$user->add_cap('add_ultimate_auction');
						$user->add_cap('handle_users_page');

						if (!isset($details['capabilities']['upload_files'])) {
							$user->add_cap('upload_files');
						}
					} else {
						$user_role = '';

						$user = get_role($role);
						$user->remove_cap('add_ultimate_auction');
						$user->remove_cap('handle_users_page');

						if (isset($details['capabilities']['upload_files']) && !(isset($details['capabilities']['level_2']) && $details['capabilities']['level_2'] == 1)) {
							$user->remove_cap('upload_files');
						}
					}
					?>
		    <input type="checkbox" name="auction_users_settings_data[<?php echo esc_attr($role);?>]" value="1" <?php checked('1', $user_role);?> />
		    <?php echo $name;?> <br />
		    <?php
}

			}

		}
		//handle post meta keys
		public function wdm_post_meta($meta_key) {
			if ($this->auction_id != "") {
				return get_post_meta($this->auction_id, $meta_key, true);
			} else if (isset($_POST["update_auction"]) && !empty($_POST["update_auction"])) {
				return get_post_meta($_POST["update_auction"], $meta_key, true);
			} else if (isset($_GET["edit_auction"]) && !empty($_GET["edit_auction"])) {
				return get_post_meta($_GET["edit_auction"], $meta_key, true);
			} else {
				return "";
			}

		}

		public function wdm_set_auction($args) {
			$this->auction_id = $args;
		}

		public function wdm_get_post() {
			if ($this->auction_id != "") {
				$auction = get_post($this->auction_id);
				$single_auction["title"] = $auction->post_title;
				$single_auction["content"] = $auction->post_content;
				$single_auction["excerpt"] = $auction->post_excerpt;
				return $single_auction;
			} elseif (isset($_POST["update_auction"]) && !empty($_POST["update_auction"])) {
				$auction = get_post($_POST["update_auction"]);
				$single_auction["title"] = $auction->post_title;
				$single_auction["content"] = $auction->post_content;
				$single_auction["excerpt"] = $auction->post_excerpt;
				return $single_auction;
			} elseif (isset($_GET["edit_auction"]) && !empty($_GET["edit_auction"])) {
				$auction = get_post($_GET["edit_auction"]);
				$single_auction["title"] = $auction->post_title;
				$single_auction["content"] = $auction->post_content;
				$single_auction["excerpt"] = $auction->post_excerpt;
				return $single_auction;
			}

			$this->auction_id = "";
			$single_auction["title"] = "";
			$single_auction["content"] = "";
			$single_auction["excerpt"] = "";
			return $single_auction;
		}

	}
}
$wctest = new wdm_settings();
?>