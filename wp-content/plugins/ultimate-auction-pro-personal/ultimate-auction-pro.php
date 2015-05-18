<?php
/*
Plugin Name: Ultimate Auction Pro
Plugin URI: http://auctionplugin.net
Description: Awesome plugin to host auctions on your wordpress site and sell anything you want.
Author: Nitesh Singh
Author URI: http://auctionplugin.net
Version: 4.4.0
Copyright 2015 Nitesh Singh
 */

load_plugin_textdomain('wdm-ultimate-auction', false, dirname(plugin_basename(__FILE__)) . '/languages/');
define('UA_PRO_VER_ACTIVE', true);

//EDD Licensing start
// this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
define('EDD_UAUCTION_STORE_URL', 'http://auctionplugin.net'); // you should use your own CONSTANT name, and be sure to replace it throughout this file

// the name of your product. This should match the download name in EDD exactly
define('EDD_UAUCTION_ITEM_NAME', 'Personal'); // you should use your own CONSTANT name, and be sure to replace it throughout this file

if (!class_exists('EDD_SL_Plugin_Updater')) {
	// load our custom updater
	include dirname(__FILE__) . '/EDD_SL_Plugin_Updater.php';
}

// retrieve our license key from the DB
$license_key = trim(get_option('edd_uauction_license_key'));

// setup the updater
$edd_updater = new EDD_SL_Plugin_Updater(EDD_UAUCTION_STORE_URL, __FILE__, array(
	'version' => '4.4.0', // current version number
	'license' => $license_key, // license key (used get_option above to retrieve from DB)
	'item_name' => EDD_UAUCTION_ITEM_NAME, // name of this plugin
	'author' => 'Nitesh Singh', // author of this plugin
)
);

/************************************
 * the code below is just a standard
 * options page. Substitute with
 * your own.
 *************************************/

function edd_uauction_license_menu() {
	add_plugins_page('Ultimate Auction License', 'Ultimate Auction License', 'administrator', 'auction-license', 'edd_uauction_license_page');
}
add_action('admin_menu', 'edd_uauction_license_menu');

function edd_uauction_license_page() {
	$license = get_option('edd_uauction_license_key');
	$status = get_option('edd_uauction_license_status');
	?>
	<div class="wrap">
		<h2><?php _e('Ultimate Auction License Options');?></h2>
		<form method="post" action="options.php">

			<?php settings_fields('edd_uauction_license');?>

			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php _e('License Key');?>
						</th>
						<td>
							<input id="edd_uauction_license_key" name="edd_uauction_license_key" type="text" class="regular-text" value="<?php esc_attr_e($license);?>" />
							<label class="description" for="edd_uauction_license_key"><?php _e('Enter your license key');?></label>
						</td>
					</tr>
					<?php if (false !== $license) {
		?>
						<tr valign="top">
							<th scope="row" valign="top">
								<?php _e('Activate License');?>
							</th>
							<td>
								<?php if ($status !== false && $status == 'valid') {?>
									<span style="color:green;"><?php _e('active');?></span>
									<?php wp_nonce_field('edd_uauction_nonce', 'edd_uauction_nonce');?>
									<input type="submit" class="button-secondary" name="edd_license_deactivate" value="<?php _e('Deactivate License');?>"/>
								<?php } else {
			wp_nonce_field('edd_uauction_nonce', 'edd_uauction_nonce');?>
									<input type="submit" class="button-secondary" name="edd_license_activate" value="<?php _e('Activate License');?>"/>
								<?php }?>
							</td>
						</tr>
					<?php }?>
				</tbody>
			</table>
			<?php submit_button();?>

		</form>
	<?php
}

function edd_uauction_register_option() {
	register_setting('edd_uauction_license', 'edd_uauction_license_key', 'edd_sanitize_license');
}
add_action('admin_init', 'edd_uauction_register_option');

function edd_sanitize_license($new) {
	$old = get_option('edd_uauction_license_key');
	if ($old && $old != $new) {
		delete_option('edd_uauction_license_status');
	}
	return $new;
}

function edd_uauction_activate_license() {

	if (isset($_POST['edd_license_activate'])) {

		if (!check_admin_referer('edd_uauction_nonce', 'edd_uauction_nonce')) {
			return;
		}

		$license = trim(get_option('edd_uauction_license_key'));

		$api_params = array(
			'edd_action' => 'activate_license',
			'license' => $license,
			'item_name' => urlencode(EDD_UAUCTION_ITEM_NAME),
		);

		$response = wp_remote_get(add_query_arg($api_params, EDD_UAUCTION_STORE_URL), array('timeout' => 15, 'sslverify' => false));

		if (is_wp_error($response)) {
			return false;
		}

		$license_data = json_decode(wp_remote_retrieve_body($response));

		update_option('edd_uauction_license_status', $license_data->license);

	}
}
add_action('admin_init', 'edd_uauction_activate_license');

function edd_uauction_deactivate_license() {

	if (isset($_POST['edd_license_deactivate'])) {

		if (!check_admin_referer('edd_uauction_nonce', 'edd_uauction_nonce')) {
			return;
		}

		$license = trim(get_option('edd_uauction_license_key'));

		$api_params = array(
			'edd_action' => 'deactivate_license',
			'license' => $license,
			'item_name' => urlencode(EDD_UAUCTION_ITEM_NAME),
		);

		$response = wp_remote_get(add_query_arg($api_params, EDD_UAUCTION_STORE_URL), array('timeout' => 15, 'sslverify' => false));

		if (is_wp_error($response)) {
			return false;
		}

		$license_data = json_decode(wp_remote_retrieve_body($response));

		if ($license_data->license == 'deactivated') {
			delete_option('edd_uauction_license_status');
		}

	}
}
add_action('admin_init', 'edd_uauction_deactivate_license');

function edd_uauction_check_license() {

	$get_ua_trans = get_transient('wdm_ua_license_trans');

	if (!$get_ua_trans) {

		global $wp_version;

		$license = trim(get_option('edd_uauction_license_key'));

		$api_params = array(
			'edd_action' => 'check_license',
			'license' => $license,
			'item_name' => urlencode(EDD_UAUCTION_ITEM_NAME),
		);

		$response = wp_remote_get(add_query_arg($api_params, EDD_UAUCTION_STORE_URL), array('timeout' => 15, 'sslverify' => false));

		//print_r($response);

		if (is_wp_error($response)) {
			return false;
		}

		$license_data = json_decode(wp_remote_retrieve_body($response));

		update_option('edd_uauction_license_status', $license_data->license);

		set_transient('wdm_ua_license_trans', $license_data->license, 60 * 60 * 24 * 7);

	}
}

edd_uauction_check_license();

//add_action('init', 'edd_uauction_check_license');

function wdm_ua_check_for_license() {

	$lic_stat = get_option('edd_uauction_license_status');

	if( $lic_stat == 'valid' ) {
	require_once 'settings-page.php';
	require_once 'auction-shortcode.php';
	require_once 'send-auction-email.php';
	require_once 'ua-paypal-invoice/ua-paypal-invoice.php';

	$shipping_enabled = get_option("wdm_enable_shipping");
	if ($shipping_enabled == "1") {
		global $ship_curr_code, $ua_all_countries;
		require_once 'ua-shipping-cost/ua-shipping-cost.php';
	}

	require_once 'ua-category-feature/ua-category-feature.php';
	require_once 'front-dashboard-shortcode.php';
	require_once 'ua-proxy-bidding/ua-proxy-bidding.php';
	require_once 'ua-widget/sidebar-widget-for-ultimate-auction.php';
	}
}

wdm_ua_check_for_license();
//EDD Licensing end

//create a table for auction bidders on plugin activation
register_activation_hook(__FILE__, 'wdm_create_bidders_table');

function wdm_create_bidders_table() {
	require ABSPATH . 'wp-admin/includes/upgrade.php';
	global $wpdb;
	$data_table = $wpdb->prefix . "wdm_bidders";
	$sql = "CREATE TABLE IF NOT EXISTS $data_table
  (
   id MEDIUMINT(9) NOT NULL AUTO_INCREMENT,
   name VARCHAR(45),
   email VARCHAR(45),
   auction_id MEDIUMINT(9),
   bid DECIMAL(10,2),
   date datetime,
   PRIMARY KEY (id)
  );";

	dbDelta($sql);

	//for old table (till version 1.0.2) which had 'bid' column as integer(MEDIUMINT)
	$alt_sql = "ALTER TABLE $data_table MODIFY bid DECIMAL(10,2);";
	$wpdb->query($alt_sql);

	//for old table (till version 4.1.1) which had 'bid' column without index
	$alt_sql = "ALTER TABLE $data_table ADD INDEX (bid);";
	$wpdb->query($alt_sql);
}

//create pages along with shortcodes on plugin activation
register_activation_hook(__FILE__, 'wdm_create_shortcode_pages');

function wdm_create_shortcode_pages(){
	
   $option = 'ua_page_exists';
   $default = array();
   $default = get_option($option);
   
    if(!isset($default['listing']))
    {
        
        $feed_page = array(
            'post_type' => 'page',
            'post_title' => __("Auctions", "wdm-ultimate-auction"),
            'post_status' => 'publish',
            'post_content' => '[wdm_auction_listing]'
            );
	
        $id = wp_insert_post($feed_page);
	
	if(!empty($id)){
		$default['listing'] = $id;
		update_option( $option, $default );
	}
    }
    
    if(!isset($default['dashboard'])){
	
        $dashboard = array(
            'post_type' => 'page',
            'post_title' => __("Dashboard", "wdm-ultimate-auction"),
            'post_status' => 'publish',
            'post_content' => '[wdm_user_dashboard]'
            );
	   
         $id = wp_insert_post($dashboard);
	 
	 if(!empty($id)){
		$default['dashboard'] = $id;
		update_option( $option, $default );
	 }
    }
}

//send email Ajax callback - An automatic activity once an auction has expired
function send_auction_email_callback() {
	//require_once('email-template.php');

	$sent_email = ultimate_auction_email_template($_POST['auc_title'], $_POST['auc_id'], $_POST['auc_cont'], $_POST['auc_bid'], $_POST['auc_email'], $_POST['auc_url']);

	if (!$sent_email) {
		update_post_meta($_POST['auc_id'], 'wdm_to_be_sent', '');
	}

	die();
}

add_action('wp_ajax_send_auction_email', 'send_auction_email_callback');
add_action('wp_ajax_nopriv_send_auction_email', 'send_auction_email_callback');

//resend email Ajax callback - 'Resend' link on 'Manage Auctions' page
function resend_auction_email_callback() {
	//require_once('email-template.php');

	$res_email = ultimate_auction_email_template($_POST['a_title'], $_POST['a_id'], $_POST['a_cont'], $_POST['a_bid'], $_POST['a_em'], $_POST['a_url']);

	if ($res_email) {
		_e("Email sent successfully.", "wdm-ultimate-auction");
	} else {
		_e("Sorry, the email could not sent.", "wdm-ultimate-auction");
	}

	die();
}

add_action('wp_ajax_resend_auction_email', 'resend_auction_email_callback');
add_action('wp_ajax_nopriv_resend_auction_email', 'resend_auction_email_callback');

//delete auction Ajax callback - 'Delete' link on 'Manage Auctions' page
function delete_auction_callback() {
	global $wpdb;

	$delete_post_id = $_POST["del_id"];
	$delete_auction_array = $wpdb->get_col($wpdb->prepare("SELECT meta_value from $wpdb->postmeta WHERE meta_key LIKE %s AND post_id = %d", '%wdm-image-%', $delete_post_id));

	if ($_POST["force_del"] === 'yes') {
		$force = true;
	} else {
		$force = false;
	}

	//if(current_user_can('delete_posts'))
	//{
	$del_auc = wp_delete_post($_POST["del_id"], false);

	$wpdb->query(
		$wpdb->prepare(
			"
                DELETE FROM " . $wpdb->prefix . "wdm_bidders
		 WHERE auction_id = %d
		",
			$_POST["del_id"]
		)
	);
	//}

	if ($del_auc) {

		foreach ($delete_auction_array as $delete_image_url) {
			if (!empty($delete_image_url) && $delete_image_url !== null) {
				$auction_url_post_id = $wpdb->get_var("SELECT ID from $wpdb->posts WHERE guid = '$delete_image_url' AND post_type = 'attachment'");
				wp_delete_post($auction_url_post_id, true); //also delete images attached
			}
		}
		printf(__("Auction %s and its attachments are deleted successfully.", "wdm-ultimate-auction"), $_POST['auc_title']);
	} else {
		_e("Sorry, this auction cannot be deleted.", "wdm-ultimate-auction");
	}

	die();
}

add_action('wp_ajax_delete_auction', 'delete_auction_callback');
add_action('wp_ajax_nopriv_delete_auction', 'delete_auction_callback');

//multiple delete auction Ajax callback
function multi_delete_auction_callback() {
	global $wpdb;

	if ($_POST["force_del"] === 'yes') {
		$force = true;
	} else {
		$force = false;
	}

	$all_aucs = explode(',', $_POST['del_ids']);

	foreach ($all_aucs as $aa) {

		$delete_auction_array = $wpdb->get_col($wpdb->prepare("SELECT meta_value from $wpdb->postmeta WHERE meta_key LIKE %s AND post_id = %d", '%wdm-image-%', $aa));

		$del_auc = wp_delete_post($aa, false);
		if ($del_auc) {
			foreach ($delete_auction_array as $delete_image_url) {
				if (!empty($delete_image_url) && $delete_image_url !== null) {
					$auction_url_post_id = $wpdb->get_var("SELECT ID from $wpdb->posts WHERE guid = '$delete_image_url' AND post_type = 'attachment'");
					wp_delete_post($auction_url_post_id, true); //also delete images attached
				}
			}
		}

		$wpdb->query(
			$wpdb->prepare(
				"
                DELETE FROM " . $wpdb->prefix . "wdm_bidders
		 WHERE auction_id = %d
		",
				$aa
			)
		);
	}
	if ($del_auc) {
		printf(__("Auctions and their attachments are deleted successfully.", "wdm-ultimate-auction"));
	} else {
		_e("Sorry, the auctions cannot be deleted.", "wdm-ultimate-auction");
	}

	die();
}

add_action('wp_ajax_multi_delete_auction', 'multi_delete_auction_callback');
add_action('wp_ajax_nopriv_multi_delete_auction', 'multi_delete_auction_callback');

//end auction Ajax callback - 'End Auction' link on 'Manage Auctions' page
function end_auction_callback() {
	$end_auc = update_post_meta($_POST['end_id'], 'wdm_listing_ends', date("Y-m-d H:i:s", time()));

	$check_term = term_exists('expired', 'auction-status');
	wp_set_post_terms($_POST['end_id'], $check_term["term_id"], 'auction-status');

	if ($end_auc) {
		printf(__("Auction %s ended successfully.", "wdm-ultimate-auction"), $_POST['end_title']);
	} else {
		_e("Sorry, this auction cannot be ended.", "wdm-ultimate-auction");
	}

	die();
}

add_action('wp_ajax_end_auction', 'end_auction_callback');
add_action('wp_ajax_nopriv_end_auction', 'end_auction_callback');

//cancel bid entry Ajax callback - 'Cancel Last Bid' link on 'Manage Auctions' page
function delete_auction_bid_callback() {
	global $wpdb;

	$delete_bid = $wpdb->query(
		$wpdb->prepare(
			"
                DELETE FROM " . $wpdb->prefix . "wdm_bidders
		 WHERE id = %d
		",
			$_POST['del_id']
		)
	);

	if ($delete_bid) {
		_e("Bid has been deleted successfully.", "wdm-ultimate-auction");
	} else {
		_e("Sorry, this bid cannot be deleted.", "wdm-ultimate-auction");
	}

	die();
}

add_action('wp_ajax_delete_auction_bid', 'delete_auction_bid_callback');
add_action('wp_ajax_nopriv_delete_auction_bid', 'delete_auction_bid_callback');

//place bid Ajax callback - 'Place Bid' button on Single Auction page
function place_bid_now_callback() {
	$ab_bid = round((double) $_POST['ab_bid'], 2);
	if (is_user_logged_in()) {
		global $wpdb;
		$wpdb->hide_errors();

		$q = "SELECT MAX(bid) FROM " . $wpdb->prefix . "wdm_bidders WHERE auction_id =" . $_POST['auction_id'];
		$next_bid = $wpdb->get_var($q);

		if (!empty($next_bid)) {
			update_post_meta($_POST['auction_id'], 'wdm_previous_bid_value', $next_bid); //store bid value of the most recent bidder
		}

		if (empty($next_bid)) {
			$next_bid = get_post_meta($_POST['auction_id'], 'wdm_opening_bid', true);
		}

		$high_bid = $next_bid;

		$next_bid = $next_bid + get_post_meta($_POST['auction_id'], 'wdm_incremental_val', true);

		$terms = wp_get_post_terms($_POST['auction_id'], 'auction-status', array("fields" => "names"));

		$next_bid = round($next_bid, 2);
		if ($ab_bid < $next_bid) {
			echo json_encode(array('stat' => 'inv_bid', 'bid' => $next_bid));
		} elseif (in_array('expired', $terms)) {
			echo json_encode(array("stat" => "Expired"));
		} else {
			$ab_name = $_POST['ab_name'];
			$ab_email = $_POST['ab_email'];

			$ab_bid = apply_filters('wdm_ua_modified_bid_amt', $ab_bid, $high_bid, $_POST['auction_id']);

			$a_bid = array();

			if (is_array($ab_bid)) {
				$a_bid = $ab_bid;
				if (!empty($a_bid['abid'])) {
					$ab_bid = $a_bid['abid'];
				}

				if (!empty($a_bid['cbid'])) {
					$cu_bid = $a_bid['cbid'];
				}

				if (!empty($a_bid['name'])) {
					$ab_name = $a_bid['name'];
				}

				if (!empty($a_bid['email'])) {
					$ab_email = $a_bid['email'];
				}
			}

			$buy_price = round(get_post_meta($_POST['auction_id'], 'wdm_buy_it_now', true), 2);

			if (!empty($buy_price) && $ab_bid >= $buy_price) {
				add_post_meta($_POST['auction_id'], 'wdm_this_auction_winner', $ab_email, true);

				if (get_post_meta($_POST['auction_id'], 'wdm_this_auction_winner', true) === $ab_email) {
					if (!empty($a_bid)) {
						do_action('wdm_ua_modified_bid_place', array('email_type' => 'winner', 'mod_name' => $ab_name, 'mod_email' => $ab_email, 'mod_bid' => $ab_bid, 'orig_bid' => $cu_bid, 'orig_name' => $_POST['ab_name'], 'orig_email' => $_POST['ab_email'], 'auc_name' => $_POST['auc_name'], 'auc_desc' => $_POST['auc_desc'], 'auc_url' => $_POST['auc_url'], 'site_char' => $_POST['ab_char'], 'auc_id' => $_POST['auction_id']));
					} else {
						$place_bid = $wpdb->insert(
							$wpdb->prefix . 'wdm_bidders',
							array(
								'name' => $ab_name,
								'email' => $ab_email,
								'auction_id' => $_POST['auction_id'],
								'bid' => $ab_bid,
								'date' => date("Y-m-d H:i:s", time()),
							),
							array(
								'%s',
								'%s',
								'%d',
								'%f',
								'%s',
							)
						);

						if ($place_bid) {
							update_post_meta($_POST['auction_id'], 'wdm_listing_ends', date("Y-m-d H:i:s", time()));
							$check_term = term_exists('expired', 'auction-status');
							wp_set_post_terms($_POST['auction_id'], $check_term["term_id"], 'auction-status');
							update_post_meta($_POST['auction_id'], 'email_sent_imd', 'sent_imd');

							echo json_encode(array('type' => 'simple', 'stat' => 'Won', 'bid' => $ab_bid));
						}
					}
				} else {
					echo json_encode(array("stat" => "Sold"));
				}
			} else {
				if (!empty($a_bid)) {
					do_action('wdm_ua_modified_bid_place', array('mod_name' => $ab_name, 'mod_email' => $ab_email, 'mod_bid' => $ab_bid, 'orig_bid' => $cu_bid, 'orig_name' => $_POST['ab_name'], 'orig_email' => $_POST['ab_email'], 'auc_name' => $_POST['auc_name'], 'auc_desc' => $_POST['auc_desc'], 'auc_url' => $_POST['auc_url'], 'site_char' => $_POST['ab_char'], 'auc_id' => $_POST['auction_id']));
				} else {
					do_action('wdm_extend_auction_time', $_POST['auction_id']);

					$place_bid = $wpdb->insert(
						$wpdb->prefix . 'wdm_bidders',
						array(
							'name' => $ab_name,
							'email' => $ab_email,
							'auction_id' => $_POST['auction_id'],
							'bid' => $ab_bid,
							'date' => date("Y-m-d H:i:s", time()),
						),
						array(
							'%s',
							'%s',
							'%d',
							'%f',
							'%s',
						)
					);

					if ($place_bid) {
						echo json_encode(array('type' => 'simple', 'stat' => 'Placed', 'bid' => $ab_bid));
					}
				}
			}
		}

	} else {
		echo json_encode(array("stat" => "Please log in to place bid"));
	}
	die();
}

add_action('wp_ajax_place_bid_now', 'place_bid_now_callback');
add_action('wp_ajax_nopriv_place_bid_now', 'place_bid_now_callback');

//bid notification email Ajax callback - Single Auction page
function bid_notification_callback() {
	$char = $_POST['ab_char'];

	$ret_url = $_POST['auc_url'] . $char . "ult_auc_id=" . $_POST['auction_id'];

	//$adm_email = get_option("wdm_auction_email");
	$adm_email = $_POST['auth_email'];

	$hdr = "";
	//$hdr  = "From: ". get_bloginfo('name') ." <". $adm_email ."> \r\n";
	$hdr .= "MIME-Version: 1.0\r\n";
	$hdr .= "Content-type:text/html;charset=UTF-8" . "\r\n";

	wdm_ua_seller_notification_mail($adm_email, $_POST['md_bid'], $ret_url, $_POST['auc_name'], $_POST['auc_desc'], $_POST['ab_email'], $_POST['ab_name'], $hdr, '');

	wdm_ua_bidder_notification_mail($_POST['ab_email'], $_POST['ab_bid'], $ret_url, $_POST['auc_name'], $_POST['auc_desc'], $hdr, '');

	//outbid email
	global $wpdb;
	$wpdb->hide_errors();
	$prev_bid = get_post_meta($_POST['auction_id'], 'wdm_previous_bid_value', true);
	if (!empty($prev_bid) && ($_POST['ab_bid'] > $prev_bid)) {
		$bidder_email = "";
		$email_qry = "SELECT email FROM " . $wpdb->prefix . "wdm_bidders WHERE bid =" . $prev_bid . " AND auction_id =" . $_POST['auction_id'];
		$bidder_email = $wpdb->get_var($email_qry);
		if ($bidder_email != $_POST['ab_email']) {

			wdm_ua_outbid_notification_mail($bidder_email, $_POST['md_bid'], $ret_url, $_POST['auc_name'], $_POST['auc_desc'], $hdr, '');
		}
	}

	//auction won immediately
	if (isset($_POST['email_type']) && $_POST['email_type'] === 'winner_email') {
		//require_once('email-template.php');
		ultimate_auction_email_template($_POST['auc_name'], $_POST['auction_id'], $_POST['auc_desc'], round($_POST['md_bid'], 2), $_POST['ab_email'], $ret_url);
	}

	die();
}

add_action('wp_ajax_bid_notification', 'bid_notification_callback');
add_action('wp_ajax_nopriv_bid_notification', 'bid_notification_callback');

//private message Ajax callback - Single Auction page
function private_message_callback() {

	$char = $_POST['p_char'];

	$auc_url = $_POST['p_url'] . $char . "ult_auc_id=" . $_POST['p_auc_id'];

	//$adm_email = get_option('wdm_auction_email');
	$adm_email = $_POST['p_auth_email'];
	$email_template_details_p = get_option("wdm_ua_email_template_private_message", 1);
	$ua_email_enable_p = true;
	if (isset($email_template_details_p['template']) && $email_template_details_p['template'] == "ua_custom") {
		$p_sub = str_replace('{blog_name}', get_bloginfo('name'), $email_template_details_p['subject']);
		$msg = str_replace('{p_name}', $_POST['p_name'], wpautop(convert_chars(wptexturize($email_template_details_p['body']))));
		$msg = str_replace('{p_email}', $_POST['p_email'], $msg);
		$msg = str_replace('{p_message}', $_POST['p_msg'], $msg);
		$msg = str_replace('{product_url}', $auc_url, $msg);
		if ($email_template_details_p['enable'] == "no") {
			$ua_email_enable_p = false;
		}
	} else {
		$p_sub = get_bloginfo('name') . " " . __("You have a private message from a site visitor", "wdm-ultimate-auction");

		$msg = "";
		$msg = __('Name', 'wdm-ultimate-auction') . ": " . $_POST['p_name'] . "<br /><br />";
		$msg .= __('Email', 'wdm-ultimate-auction') . ": " . $_POST['p_email'] . "<br /><br />";
		$msg .= __('Message', 'wdm-ultimate-auction') . ": <br />" . $_POST['p_msg'] . "<br /><br />";
		$msg .= __('Product URL', 'wdm-ultimate-auction') . ": <a href='" . $auc_url . "'>" . $auc_url . "</a><br />";
	}
	$hdr = "";
	//$hdr  = "From: ". get_bloginfo('name') ." <". $adm_email ."> \r\n";
	$hdr .= "Reply-To: <" . $_POST['p_email'] . "> \r\n";
	$hdr .= "MIME-Version: 1.0\r\n";
	$hdr .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$sent = '';
	if ($ua_email_enable_p) {
		$sent = wp_mail($adm_email, $p_sub, $msg, $hdr, '');
	}

	if ($sent) {
		_e("Email sent successfully.", "wdm-ultimate-auction");
	} else {
		_e("Sorry, the email could not sent.", "wdm-ultimate-auction");
	}

	die();
}

add_action('wp_ajax_private_message', 'private_message_callback');
add_action('wp_ajax_nopriv_private_message', 'private_message_callback');

//add to watchlist ajax callaback
function wdm_add_to_watchlist_callback() {
	global $wpdb;
	$wpdb->hide_errors();
	$watch_auctions = get_user_meta($_POST['user_id'], 'wdm_watch_auctions');
	$watch_arr = explode(" ", $watch_auctions[0]);
	if (isset($watch_auctions)) {
		if (!in_array($_POST['auction_id'], $watch_arr)) {
			$arr = $watch_auctions[0] . " " . $_POST['auction_id'];
			update_user_meta($_POST['user_id'], 'wdm_watch_auctions', $arr);
		}
		echo "true";
	} else {
		update_user_meta($_POST['user_id'], 'wdm_watch_auctions', intval($_POST['auction_id']));
		echo "true";
	}
	die();
}
add_action('wp_ajax_wdm_add_to_watchlist', 'wdm_add_to_watchlist_callback');
add_action('wp_ajax_nopriv_wdm_add_to_watchlist', 'wdm_add_to_watchlist_callback');

//remove auction from watchlist Ajax callback - 'Remove From Wathclist' link on my watchlist page
function rmv_frm_watchlist_callback() {
	global $wpdb;

	if ($_POST["force_del"] === 'yes') {
		$force = true;
	} else {
		$force = false;
	}

	if (isset($_POST['usr_id'])) {
		$watch_auctions = get_user_meta($_POST['usr_id'], 'wdm_watch_auctions');
	}
	if (isset($watch_auctions[0])) {
		$watch_arr = explode(" ", $watch_auctions[0]);
	}
	if (isset($watch_arr)) {
		foreach ($watch_arr as $key => $value) {
			if (isset($_POST["rmv_id"])) {
				if ($value == $_POST["rmv_id"]) {
					unset($watch_arr[$key]);
				}

			}
		}
	}
	$wdm_watch_str = implode(" ", $watch_arr);
	if (isset($wdm_watch_str) && isset($_POST['usr_id'])) {
		update_user_meta($_POST['usr_id'], 'wdm_watch_auctions', $wdm_watch_str);
		echo "Auction removed from your watchlist successfully.";
	}
	die();
}

add_action('wp_ajax_rmv_frm_watchlist', 'rmv_frm_watchlist_callback');
add_action('wp_ajax_nopriv_rmv_frm_watchlist', 'rmv_frm_watchlist_callback');

//list bidders Ajax callback - 'See More' link on 'Your Auctions/User Auctions' pages
function see_more_ajax_callback() {

	global $wpdb;

	$currency_code = substr(get_option('wdm_currency'), -3);

	if ($_POST["show_rows"] == -1) {
		$query = "SELECT * FROM " . $wpdb->prefix . "wdm_bidders WHERE auction_id =" . $_POST["auction_id"] . " ORDER BY date DESC";
	} else {
		$query = "SELECT * FROM " . $wpdb->prefix . "wdm_bidders WHERE auction_id =" . $_POST["auction_id"] . " ORDER BY date DESC LIMIT 5";
	}

	$results = $wpdb->get_results($query);
	if (!empty($results)) {
		echo "<ul>";
		foreach ($results as $result) {
			?>
                <li><strong><a href='#'><?php echo $result->name?></a></strong> - <?php echo $currency_code . " " . $result->bid;?></li>
                <?php
}
		echo "</ul>";
	}
	die();
}
add_action('wp_ajax_see_more_ajax', 'see_more_ajax_callback');
add_action('wp_ajax_nopriv_see_more_ajax', 'see_more_ajax_callback');

//plugin credit link
add_action('wp_footer', 'wdm_plugin_credit_link');

function wdm_plugin_credit_link() {
	add_option('wdm_powered_by', "Yes");

	$check_credit = get_option('wdm_powered_by');

	if ($check_credit == "Yes") {
		if (!is_admin()) {
			echo "<center><div id='ult-auc-footer-credit'><a href='http://auctionplugin.net' target='_blank'>" . __("Powered By Ultimate Auction", "wdm-ultimate-auction") . "</a></div></center>";
		}

	}
}

add_action('init', 'wdm_set_auction_timezone');
function wdm_set_auction_timezone() {
	$get_default_timezone = get_option('wdm_time_zone');

	if (!empty($get_default_timezone)) {
		date_default_timezone_set($get_default_timezone);
	}

	if ((isset($_GET['tx']) && !empty($_GET['tx'])) || (isset($_GET['wdm']) && !empty($_GET['wdm'])) || (isset($_GET['wdmpy']) && !empty($_GET['wdmpy']))) {
		if (isset($_GET["ult_auc_id"]) && $_GET["ult_auc_id"]) {

			$single_auction = get_post($_GET["ult_auc_id"]);

			$auth_key = get_post_meta($single_auction->ID, 'wdm-auth-key', true);

			if (isset($_GET['wdm']) && $_GET['wdm'] === $auth_key) {
				$terms = wp_get_post_terms($single_auction->ID, 'auction-status', array("fields" => "names"));
				if (!in_array('expired', $terms)) {
					$chck_term = term_exists('expired', 'auction-status');
					wp_set_post_terms($single_auction->ID, $chck_term["term_id"], 'auction-status');
					update_post_meta($single_auction->ID, 'wdm_listing_ends', date("Y-m-d H:i:s", time()));
				}

				update_post_meta($single_auction->ID, 'auction_bought_status', 'bought');

				//winner
				update_post_meta($single_auction->ID, 'wdm_auction_buyer', get_current_user_id());

				if ((isset($_GET['wdmpy']) && !empty($_GET['wdmpy']))) {
					update_post_meta($single_auction->ID, 'auction_active_pay_method', 'adaptive');
				}

				echo '<script type="text/javascript">
                                          setTimeout(function() {
                                                                alert("' . __("Thank you for buying this product.", "wdm-ultimate-auction") . '");
                                                               }, 1000);
                                          </script>';

				//details of a product sold through buy now link
				if (is_user_logged_in()) {
					$curr_user = wp_get_current_user();
					$buyer_email = $curr_user->user_email;
					$winner_name = $curr_user->user_login;
				}

				$auction_email = get_option('wdm_auction_email');
				$site_name = get_bloginfo('name');
				$site_url = get_bloginfo('url');
				$c_code = substr(get_option('wdm_currency'), -3);
				//$rec_email = get_option('wdm_paypal_address');
				$buy_now_price = get_post_meta($single_auction->ID, 'wdm_buy_it_now', true);

				$return_url = "";
				$return_url = strstr($_SERVER['REQUEST_URI'], 'ult_auc_id', true);
				$return_url = $site_url . $return_url . "ult_auc_id=" . $_GET["ult_auc_id"];

				$auc_post = get_post($single_auction->ID);
				$auc_author_id = $auc_post->post_author;
				$auc_author = new WP_User($auc_author_id);
				$seller_email = get_the_author_meta('user_email', $auc_author_id);

				if (in_array('administrator', $auc_author->roles)) {
					$seller_email = $auction_email;
					$rec_email = get_option('wdm_paypal_address');
				} else {
					$rec_email = get_user_meta($auc_author_id, 'auction_user_paypal_email', true);
				}

				$headers = "";
				//$headers  = "From: ". $site_name ." <". $auction_email ."> \r\n";
				$headers .= "Reply-To: <" . $buyer_email . "> \r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

				$auction_data = array('auc_id' => $single_auction->ID,
					'auc_name' => $single_auction->post_title,
					'auc_desc' => $single_auction->post_content,
					'auc_price' => $buy_now_price,
					'auc_currency' => $c_code,
					'seller_paypal_email' => $rec_email,
					'winner_email' => $buyer_email,
					'seller_email' => $seller_email,
					'winner_name' => $winner_name,
					'pay_method' => 'method_paypal',
					'site_name' => $site_name,
					'site_url' => $site_url,
					'product_url' => $return_url,
					'header' => $headers,
				);

				$comm_fees = get_option('wdm_manage_cm_fees_data');
				$comm_inv = get_option('wdm_manage_comm_invoice');
				$s_email = $auc_author->user_email;
				//$a_email = get_option('wdm_auction_email');

				$check_method = get_post_meta($single_auction->ID, 'wdm_payment_method', true);

				//$payment_to_seller = false;

				//if($comm_inv == 'No' && !in_array('administrator', $auc_author->roles))
				//{
				//   //$payment_to_seller = true;
				//   update_post_meta($single_auction->ID, 'ua_direct_pay_to_seller', 'pay');
				//}

				if ($check_method === 'method_paypal') {

					if ($comm_inv == 'No' && !in_array('administrator', $auc_author->roles)) {
						//$payment_to_seller = true;
						update_post_meta($single_auction->ID, 'ua_direct_pay_to_seller', 'pay');
					}

					$ship_enabled = "";
					$ship_enabled = get_post_meta($single_auction->ID, 'wdm_enable_shipping', true);

					if ($ship_enabled == "1") {
						do_action('ua_shipping_data_email', $auction_data);
					} else {
						$email_template_details_s = get_option("wdm_ua_email_template_buy_now_seller", 1);
						$ua_email_enable_s = true;
						if (isset($email_template_details_s['template']) && $email_template_details_s['template'] == "ua_custom") {
							$sub = str_replace('{site_name}', $site_name, $email_template_details_s['subject']);
							$sub = str_replace('{product_name}', $single_auction->post_title, $sub);

							$msg = str_replace('{site_url}', $site_url, wpautop(convert_chars(wptexturize($email_template_details_s['body']))));
							$msg = str_replace('{product_url}', $return_url, $msg);
							$msg = str_replace('{product_name}', $single_auction->post_title, $msg);
							$msg = str_replace('{currency_code}', $c_code, $msg);
							$msg = str_replace('{bid_value}', $buy_now_price, $msg);
							$msg = str_replace('{winner_name}', $winner_name, $msg);
							$msg = str_replace('{winner_email}', $buyer_email, $msg);

							if ($email_template_details_s['enable'] == "no") {
								$ua_email_enable_s = false;
							}
						} else {
							//if( (empty($ship_enabled) || $ship_enabled == "") && $payment_to_seller )
							$sub = '';
							$sub = $site_name . ": " . __('An auction has been sold', 'wdm-ultimate-auction') . " - " . $single_auction->post_title;

							$msg = '';
							$msg = __('An auction has been sold on your site', 'wdm-ultimate-auction') . " - " . $site_url . "<br /><br />";
							$msg .= "<strong>" . __('Product Details', 'wdm-ultimate-auction') . "</strong> - <br /><br />";
							$msg .= "&nbsp;&nbsp;" . __('Product URL', 'wdm-ultimate-auction') . ": <a href='" . $return_url . "'>" . $return_url . "</a><br /><br />";
							$msg .= "&nbsp;&nbsp;" . __('Product Name', 'wdm-ultimate-auction') . ": " . $single_auction->post_title . "<br /><br />";
							$msg .= "&nbsp;&nbsp;" . __('Product Price', 'wdm-ultimate-auction') . ": " . $c_code . " " . $buy_now_price . "<br /><br />";
							$msg .= "<strong>" . __('Winner Details', 'wdm-ultimate-auction') . "</strong> - <br /><br />";
							$msg .= "&nbsp;&nbsp;" . __('Winner Name', 'wdm-ultimate-auction') . ": " . $winner_name . "<br /><br />";
							$msg .= "&nbsp;&nbsp;" . __('Winner Email', 'wdm-ultimate-auction') . ": " . $buyer_email . "<br /><br />";
						}
						if ($ua_email_enable_s) {
							wp_mail($seller_email, $sub, $msg, $headers, '');
						}
					}

					/**
					 * Send Download Link to Winner
					 */

					$product_type = get_post_meta($single_auction->ID, 'wdm_product_type', true);

					if ($product_type == 'digital') {
						$url = get_post_meta($single_auction->ID, 'wdm-digital-product-file', true);
						$url = md5($url);
						$email_template_details_d = get_option("wdm_ua_email_template_digital_auction", 1);
						$ua_email_enable_d = true;
						if (isset($email_template_details_d['template']) && $email_template_details_d['template'] == "ua_custom") {
							$sub_buyer = $email_template_details_d['subject'];
							$msg_sender = str_replace('{download_url}', add_query_arg(array('url' => $url), $return_url), wpautop(convert_chars(wptexturize($email_template_details_d['body']))));
							$msg_sender = str_replace('{product_url}', $return_url, $msg_sender);
							if ($email_template_details_d['enable'] == "no") {
								$ua_email_enable_d = false;
							}
						} else {

							$sub_buyer = __("Download your digital auction product", "wdm-ultimate-auction");

							$msg_sender = __("Please download the product using following link", "wdm-ultimate-auction");

							$msg_sender .= '<p><a href="' . add_query_arg(array('url' => $url), $return_url) . '">' . __("Click Here To Download", "wdm-ultimate-auction") . '</p>';
							$msg_sender .= '<p>' . __('Product URL', 'wdm-ultimate-auction') . ': <a href="' . $return_url . '">' . $return_url . '</p>';

						}

						$headers_buyer = " ";
						$headers_buyer .= "Reply-To: <" . $seller_email . "> \r\n";
						$headers_buyer .= "MIME-Version: 1.0\r\n";
						$headers_buyer .= "Content-type:text/html;charset=UTF-8" . "\r\n";
						if ($ua_email_enable_d) {
							wp_mail($buyer_email, $sub_buyer, $msg_sender, $headers_buyer, '');
						}
					}
				}
			}
		}

	}

}

add_action('wp_ajax_wdm_digi_mail', 'wdm_send_digital_download_email');
add_action('wp_ajax_nopriv_wdm_digi_mail', 'wdm_send_digital_download_email');

function wdm_send_digital_download_email() {

	$action = isset($_POST['action']) ? $_POST['action'] : '';
	$auction_id = isset($_POST['auc_id']) ? $_POST['auc_id'] : '';
	$return_url = isset($_POST['p_url']) ? $_POST['p_url'] : '';
	$seller_email = isset($_POST['s_email']) ? $_POST['s_email'] : '';
	//$buyer_email = isset($_POST['b_email']) ? $_POST['b_email'] : '';
	if ($action == 'wdm_digi_mail' && !empty($auction_id) && !empty($return_url) /*&& !empty($seller_email)*/) {
		$product_type = get_post_meta($auction_id, 'wdm_product_type', true);

		global $wpdb;

		if ($product_type == 'digital') {

			$reserve_price_met = get_post_meta($auction_id, 'wdm_lowest_bid', true);

			$bid_qry = "SELECT MAX(bid) FROM " . $wpdb->prefix . "wdm_bidders WHERE auction_id =" . $auction_id;
			$winner_bid = $wpdb->get_var($bid_qry);

			if ($winner_bid >= $reserve_price_met) {
				$email_qry = "SELECT name FROM " . $wpdb->prefix . "wdm_bidders WHERE bid =" . $winner_bid . " AND auction_id =" . $auction_id . " ORDER BY id DESC";

				$winner_name = $wpdb->get_var($email_qry);

				$winner = get_user_by('login', $winner_name);

				$winner_email = $winner->user_email;
			}

			$url = get_post_meta($auction_id, 'wdm-digital-product-file', true);
			$url = md5($url);
			$email_template_details_d = get_option("wdm_ua_email_template_digital_auction", 1);
			$ua_email_enable_d = true;
			if (isset($email_template_details_d['template']) && $email_template_details_d['template'] == "ua_custom") {
				$sub_buyer = $email_template_details_d['subject'];
				$msg_sender = str_replace('{download_url}', add_query_arg(array('url' => $url), $return_url), wpautop(convert_chars(wptexturize($email_template_details_d['body']))));
				$msg_sender = str_replace('{product_url}', $return_url, $msg_sender);
				if ($email_template_details_d['enable'] == "no") {
					$ua_email_enable_d = false;
				}
			} else {
				$sub_buyer = __("Download your digital auction product", "wdm-ultimate-auction");

				$msg_sender = __("Please download the product using following link", "wdm-ultimate-auction");

				$msg_sender .= '<p><a href="' . add_query_arg(array('url' => $url), $return_url) . '">' . __("Click Here To Download", "wdm-ultimate-auction") . '</p>';
				$msg_sender .= '<p>' . __('Product URL', 'wdm-ultimate-auction') . ': <a href="' . $return_url . '">' . $return_url . '</p>';
			}

			$headers_buyer = " ";
			$headers_buyer .= "Reply-To: <" . $seller_email . "> \r\n";
			$headers_buyer .= "MIME-Version: 1.0\r\n";
			$headers_buyer .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$sent = '';
			if ($ua_email_enable_d) {
				$sent = wp_mail($winner_email, $sub_buyer, $msg_sender, $headers_buyer, '');
			}
			if ($sent) {
				_e('Email sent successfully', 'wdm-ultimate-auction');
				die();
			}
		}
	}
	_e('Sorry, email could not be sent.', 'wdm-ultimate-auction');
	die();
}

function wdm_ending_time_calculator($seconds, $end_tm) {
	$days = floor($seconds / 86400);
	$seconds %= 86400;

	$hours = floor($seconds / 3600);
	$seconds %= 3600;

	$minutes = floor($seconds / 60);
	$seconds %= 60;

	$rem_tm = '';
	$rem_dy = '';
	$rem_hr = '';
	$rem_min = '';
	$rem_sec = '';

	if ($end_tm == 'end_time') {

		if ($days == 1 || $days == -1) {
			$rem_dy = "<span class='wdm_datetime' id='wdm_days'>" . $days . "</span><span id='wdm_days_text'> " . __('day', 'wdm-ultimate-auction') . " </span>";
		} elseif ($days == 0) {
			$rem_dy = "<span class='wdm_datetime' id='wdm_days' style='display:none;'>" . $days . "</span><span id='wdm_days_text'></span>";
		} else {
			$rem_dy = "<span class='wdm_datetime' id='wdm_days'>" . $days . "</span><span id='wdm_days_text'> " . __('days', 'wdm-ultimate-auction') . " </span>";
		}

		if ($hours == 1 || $hours == -1) {
			$rem_hr = "<span class='wdm_datetime' id='wdm_hours'>" . $hours . "</span><span id='wdm_hrs_text'> " . __('hour', 'wdm-ultimate-auction') . " </span>";
		} elseif ($hours == 0) {
			$rem_hr = "<span class='wdm_datetime' id='wdm_hours' style='display:none;'>" . $hours . "</span><span id='wdm_hrs_text'></span>";
		} else {
			$rem_hr = "<span class='wdm_datetime' id='wdm_hours'>" . $hours . "</span><span id='wdm_hrs_text'> " . __('hours', 'wdm-ultimate-auction') . " </span>";
		}

		if ($minutes == 1 || $minutes == -1) {
			$rem_min = "<span class='wdm_datetime' id='wdm_minutes'>" . $minutes . "</span><span id='wdm_mins_text'> " . __('minute', 'wdm-ultimate-auction') . " </span>";
		} elseif ($minutes == 0) {
			$rem_min = "<span class='wdm_datetime' id='wdm_minutes' style='display:none;'>" . $minutes . "</span><span id='wdm_mins_text'></span>";
		} else {
			$rem_min = "<span class='wdm_datetime' id='wdm_minutes'>" . $minutes . "</span><span id='wdm_mins_text'> " . __('minutes', 'wdm-ultimate-auction') . " </span>";
		}

		if ($seconds == 1 || $seconds == -1) {
			$rem_sec = "<span class='wdm_datetime' id='wdm_seconds'>" . $seconds . "</span><span id='wdm_secs_text'> " . __('second', 'wdm-ultimate-auction') . "</span>";
		} elseif ($seconds == 0) {
			$rem_sec = "<span class='wdm_datetime' id='wdm_seconds' style='display:none;'>" . $seconds . "</span><span id='wdm_secs_text'></span>";
		} else {
			$rem_sec = "<span class='wdm_datetime' id='wdm_seconds'>" . $seconds . "</span><span id='wdm_secs_text'> " . __('seconds', 'wdm-ultimate-auction') . "</span>";
		}

		$rem_tm = $rem_dy . ' ' . $rem_hr . ' ' . $rem_min . ' ' . $rem_sec;
	} else {

		if ($days > 1) {
			$rem_dy = sprintf(__('%s days', 'wdm-ultimate-auction'), $days) . ' ';
		} elseif ($days == 1) {
			$rem_dy = sprintf(__('%s day', 'wdm-ultimate-auction'), $days) . ' ';
		}

		if ($hours > 1) {
			$rem_hr = sprintf(__('%s hours', 'wdm-ultimate-auction'), $hours) . ' ';
		} elseif ($hours == 1) {
			$rem_hr = sprintf(__('%s hour', 'wdm-ultimate-auction'), $hours) . ' ';
		}

		if ($minutes > 1) {
			$rem_min = sprintf(__('%s minutes', 'wdm-ultimate-auction'), $minutes) . ' ';
		} elseif ($minutes == 1) {
			$rem_min = sprintf(__('%s minute', 'wdm-ultimate-auction'), $minutes) . ' ';
		}

		if ($seconds > 1) {
			$rem_sec = sprintf(__('%s seconds', 'wdm-ultimate-auction'), $seconds);
		} elseif ($seconds == 1) {
			$rem_sec = sprintf(__('%s second', 'wdm-ultimate-auction'), $seconds);
		}

		if ($days >= 1) {
			$rem_tm = $rem_dy;
		} elseif ($days < 1) {
			if ($hours >= 1) {
				$rem_tm = $rem_hr;
			} elseif ($hours < 1) {
				if ($minutes >= 1) {
					$rem_tm = $rem_min;
				} elseif ($minutes < 1) {
					if ($seconds >= 1) {
						$rem_tm = $rem_sec;
					}

				}
			}
		}
	}

	return $rem_tm;
}

add_filter('comment_post_redirect', 'redirect_after_comment');
function redirect_after_comment($location) {
	return $_SERVER["HTTP_REFERER"];
}

function wdm_auction_listing_charge($post_id, $auction_title, $amt, $currency_code, $view) {

	$dt = "";

	$mode = get_option('wdm_account_mode');

	$paypal_link = "";

	if ($mode == 'Sandbox') {
		$paypal_link = "https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_xclick";
	} else {
		$paypal_link = "https://www.paypal.com/cgi-bin/webscr?cmd=_xclick";
	}

	$auth_key = get_post_meta($post_id, 'wdm-auth-key', true);

	if ($view === 'fe') {
		$perma_type = get_option('permalink_structure');

		if (is_front_page() || is_home()) {
			$set_char = "?";
		} elseif (empty($perma_type)) {
			$set_char = "&";
		} else {
			$set_char = "?";
		}

		$rtn = get_permalink() . $set_char . 'dashboard=add-auction&edit_auction=' . $post_id . '&auc=' . $auth_key;
	} else {
		$rtn = admin_url('admin.php?page=add-new-auction&edit_auction=' . $post_id . '&auc=' . $auth_key);
	}
	$paypal_link .= "&business=" . urlencode(get_option('wdm_paypal_address'));
	//$paypal_link .= "&lc=US";
	$paypal_link .= "&item_name=" . urlencode($auction_title);
	$paypal_link .= "&amount=" . urlencode($amt);
	$paypal_link .= "&currency_code=" . urlencode($currency_code);
	$paypal_link .= "&return=" . urlencode($rtn);
	$paypal_link .= "&button_subtype=services";
	$paypal_link .= "&no_note=0";
	$paypal_link .= "&bn=PP%2dBuyNowBF%3abtn_buynowCC_LG%2egif%3aNonHostedGuest";

	$payment_link = "";
	$payment_link = "<span class='wdm_mk_pmt_usr_section'> " . __('We do charge %1$s %2$s as Auction Listing fee.', 'wdm-ultimate-auction') . " </span><br />";
	$payment_link .= "<span class='wdm_mk_pmt_usr_section'> " . __("This Auction can not be live until payment is done. Please make payment to make this auction live.", "wdm-ultimate-auction") . " </span><br />";
	$payment_link .= "<span style='color:#8B4513;' class='wdm_mk_pmt_usr_section'> " . __("NOTE: You will loose data of this auction post once you move away from here without paying.", "wdm-ultimate-auction") . " </span><br />";
	$pay_button = "<a class='wdm-make-payment-link button-secondary wdm-mark-hover' href='" . $paypal_link . "'>" . __("Make Payment", "wdm-ultimate-auction") . "</a>";

	?>
   <script type='text/javascript'>
        jQuery(document).ready(function(){
            apprise("<?php printf(($payment_link), $currency_code, $amt);
	echo $pay_button;?>");
            jQuery(".wdm-make-payment-link").css('margin-top','10px');
            jQuery(".aButtons").css('margin-top','5px');
            jQuery(".appriseInner").css('padding','5px 20px');
        });
   </script>
   <?php
$dt .= sprintf(($payment_link), $currency_code, $amt);
	$dt .= $pay_button;
	update_post_meta($post_id, 'wdm_auction_listing_amt', $currency_code . ' ' . $amt);

	return $dt;
}

function paypal_auto_return_url_notes() {

	$pp_ms = '<div class="paypal-config-note-text" style="float: right;width: 530px;">';

	$pp_ms .= '<span class="pp-please-note">' . __("Mandatory Settings:", "wdm-ultimate-auction") . '</span> <br />';

	$pp_ms .= '<span class="pp-url-notification">' . sprintf(__('It is mandatory to set %1$s (if not already set) and enable %2$s (if not already enabled) in your PayPal account for proper functioning of payment related features.', 'wdm-ultimate-auction'), "<strong>Auto Return URL</strong>", "<strong>Payment Data Transfer</strong>") . '</span>';

	if (current_user_can('administrator')) {

		$pp_ms .= '<a href="" class="auction_fields_tooltip"><strong>' . __("?", "wdm-ultimate-auction") . '</strong><span style="width: 370px;margin-left: -90px;">';

		$pp_ms .= sprintf(__("Whenever a visitor clicks on 'Buy it Now' button of a product/auction, he is redirected to PayPal where he can make payment for that product/auction.", "wdm-ultimate-auction")) . '<br />';

		$pp_ms .= sprintf(__("After making payment he is again redirected automatically (if the %s has been set) to this site and then the auction expires.", "wdm-ultimate-auction"), "Auto Return URL") . '<br />';

		$pp_ms .= sprintf(__('The %1$s and %2$s are also needed for the functionality of adding auctions by other auction users (non administrative users).', 'wdm-ultimate-auction'), "Auto Return URL", "PDT") . '</span></a>';
	}

	$pp_ms .= '<br /><a href="#" id="how-set-pp-auto-return">' . __("How to do these settings?", "wdm-ultimate-auction") . '</a><br />';

	$pp_ms .= '<div id="wdm-steps-to-be-followed" style="display:none;"><br />';

	$pp_ms .= sprintf(__("1. Log in to your PayPal account", "wdm-ultimate-auction")) . '- <a href="https://www.paypal.com/us/cgi-bin/webscr?cmd=_account" target="_blank">Live</a>/ <a href="https://www.sandbox.paypal.com/us/cgi-bin/webscr?cmd=_account" target="_blank">Sandbox</a><br />';

	$pp_ms .= sprintf(__('2. Under %2$s -> click %1$s.', 'wdm-ultimate-auction'), "<strong>Profile</strong>", "<strong>My Account</strong>") . '<br />';

	$pp_ms .= sprintf(__("3. Click %s (on LHS)", "wdm-ultimate-auction"), "<strong>My Selling Tools</strong>") . '<br />';

	$pp_ms .= sprintf(__('4. Go to %1$s (on RHS) -> %2$s -> Click %3$s link (next to %4$s)', 'wdm-ultimate-auction'), "<strong>Selling Online</strong>", "<strong>Website Preferences</strong>", "<strong>Update</strong>", "<strong>Website Preferences</strong>") . '<br />';

	$pp_ms .= sprintf(__('5. %s page will open.', 'wdm-ultimate-auction'), "<strong>Website Preferences</strong>") . '<br />';

	$pp_ms .= sprintf(__('6. Enable %s.', 'wdm-ultimate-auction'), "<strong>Auto Return</strong>") . '<br />';

	$pp_ms .= sprintf(__('7. Set a URL in %s box. Enter feed page URL.', 'wdm-ultimate-auction'), "<strong>Return URL</strong>") . '<br />';

	$pp_ms .= sprintf(__('8. Enable %1$s option (if the %2$s is not set, %3$s can not be enabled).', 'wdm-ultimate-auction'), "<strong>PDT (Payment Data Transfer)</strong>", "<strong>Return URL</strong>", "<strong>PDT</strong>") . " <br />";

	$pp_ms .= sprintf(__("9. Scroll down and click the %s button.", "wdm-ultimate-auction"), "<strong>Save</strong>");
	$pp_ms .= '</div></div>';

	$pp_ms .= '<script type="text/javascript">
	jQuery(document).ready(function(){
	jQuery("#how-set-pp-auto-return").click(
		function(){
		jQuery("#wdm-steps-to-be-followed").slideToggle("slow");
		jQuery("html, body").animate({scrollTop: jQuery(".paypal-config-note-text").offset().top - 50});
		return false;
		});
	});
      </script>';

	return $pp_ms;
}

function prepare_single_auction_title($id, $title) {

	$perma_type = get_option('permalink_structure');
	if (empty($perma_type)) {
		$set_char = "&";
	} else {
		$set_char = "?";
	}

	$auc_url = get_option('wdm_auction_page_url');

	if (!empty($auc_url)) {
		$link_title = $auc_url . $set_char . "ult_auc_id=" . $id;
		$link_title = "<a href='" . $link_title . "' target='_blank'>" . $title . "</a>";
		$title = $link_title;
	}

	return $title;
}

function wdm_auction_commission_calculator($com_amt, $auc_amt) {

	if (get_option('wdm_manage_comm_type') == 'Percentage') {
		$final_amt = ($auc_amt * $com_amt) / 100;
	} else {
		if (get_option('wdm_manage_comm_type') == 'Flat Fee') {
			$final_amt = $com_amt;
		}
	}

	$final_amt = $auc_amt - $final_amt;

	return round($final_amt, 2);
}

//auction listing page - pagination
function wdm_auction_pagination($pages = '', $range = 2, $paged) {
	$showitems = ($range * 2) + 1;

	if (empty($paged)) {
		$paged = 1;
	}

	if ($pages == '') {
		global $wp_query;
		$pages = $wp_query->max_num_pages;

		if (!$pages) {
			$pages = 1;
		}
	}

	if (1 != $pages) {
		echo "<div class='pagination'>";
		printf('<span>' . __('Page %1$s of %2$s', 'wdm-ultimate-auction') . '</span>', $paged, $pages);
		if ($paged > 2 && $paged > $range + 1 && $showitems < $pages) {
			echo "<a href='" . get_pagenum_link(1) . "'>&laquo;</a>";
		}

		if ($paged > 1 && $showitems < $pages) {
			echo "<a href='" . get_pagenum_link($paged - 1) . "'>&lsaquo;</a>";
		}

		for ($i = 1; $i <= $pages; $i++) {
			if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems)) {
				echo ($paged == $i) ? "<span class='current'>" . $i . "</span>" : "<a href='" . get_pagenum_link($i) . "' class='inactive' >" . $i . "</a>";
			}
		}

		if ($paged < $pages && $showitems < $pages) {
			echo "<a href='" . get_pagenum_link($paged + 1) . "'>&rsaquo;</a>";
		}

		if ($paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages) {
			echo "<a href='" . get_pagenum_link($pages) . "'>&raquo;</a>";
		}

		echo "</div>\n";
	}
}

function wdm_submit_button($title) {
	$title = ($title != "") ? __("Update Auction", "wdm-ultimate-auction") : __("Add Auction", "wdm-ultimate-auction");
	$submit = '<p><input type="submit" id="wdmua-submit" value="' . $title . '" /></p>';
	return $submit;
}

function wdm_prepare_front_items($data_array, $col_array, $sh, $on) {

	$data = '';

	$data .= '<ul class="wdm_front_' . $on . ' wdm_front_' . $sh . ' wdm_all_lnk front_usr_auc_hd">';
	$data .= '<li><ul class="front_usr_aucs">';

	foreach ($col_array as $cl_arr) {
		$data .= '<li>' . $cl_arr . '</li>';
	}
	$data .= '</ul></li>';
	$data .= '</ul>';

	$data .= '<div id="wdmPages_' . $on . '" class="wdm_front_' . $on . ' wdm_front_' . $sh . ' wdm_all_lnk">';

	$data .= '<ul class="wdmcontent">';

	foreach ($data_array as $dt_arr) {

		$data .= '<li class="front_usr_auclist"><ul id="wdm_ua_fe_single_' . $dt_arr["ID"] . '" class="front_usr_aucs front_usr_auc_dt">';

		foreach ($col_array as $ckey => $cl_arr) {

			$data .= '<li class="wdmua_' . $ckey . '">' . $dt_arr[$ckey] . '</li>';
		}

		$data .= '</ul></li>';

	}
	$data .= '</ul>';

	$data .= '<div class="page_navigation wdm_front_' . $on . ' wdm_front_' . $sh . ' wdm_all_lnk">
</div>';

	$data .= '</div>';
	?>
  <script type="text/javascript">
    jQuery(document).ready(function($){
       $('.wdm_front_live li, .wdm_front_expired li, .wdm_front_scheduled li').mouseover(function(){$(this).find('.wdmua_action').css('visibility', 'visible')});
       $('.wdm_front_live li, .wdm_front_expired li, .wdm_front_scheduled li').mouseout(function(){$(this).find('.wdmua_action').css('visibility', 'hidden')});

       });
   </script>

  <script type="text/javascript">
    jQuery(document).ready(function($){
	$('#wdmPages_<?php echo $on;?>').pajinate({
				       start_page : 0,
					items_per_page : 20,
					nav_label_first : '<<',
					nav_label_last : '>>',
					nav_label_prev : '<',
					nav_label_next : '>',
					abort_on_small_lists: true
				});

	});
</script>
<?php

	return $data;
}

function wdm_prepare_front_inv($data_array, $col_array, $sh, $on) {

	$data = '';

	$data .= '<ul class="wdm_front_' . $on . ' wdm_front_' . $sh . ' wdm_all_lnk front_usr_auc_hd" style="border-top: 1px solid #CCCCCC;">';
	$data .= '<li style="width:100%;"><ul class="front_usr_aucs">';

	foreach ($col_array as $cl_arr) {
		$data .= '<li>' . $cl_arr . '</li>';
	}
	$data .= '</ul></li>';
	$data .= '</ul>';

	$data .= '<div id="wdmInv_' . $on . '" class="wdm_front_' . $on . ' wdm_front_' . $sh . ' wdm_all_lnk">';

	$data .= '<ul class="wdmcontentinv">';

	$data .= '<li style="width:100%;" class="front_usr_auclist"><ul id="wdm_ua_fe_single_' . $data_array["ID"] . '" class="front_usr_aucs front_usr_auc_dt">';

	foreach ($col_array as $ckey => $cl_arr) {

		$data .= '<li class="wdmua_' . $ckey . '">' . $data_array[$ckey] . '</li>';
	}

	$data .= '</ul></li>';

	$data .= '</ul>';

	$data .= '</div>';

	return $data;
}

function wdm_current_perm_char() {
	$perma_type = get_option('permalink_structure');

	if (is_front_page() || is_home()) {
		$set_char = "?";
	} elseif (empty($perma_type)) {
		$set_char = "&";
	} else {
		$set_char = "?";
	}

	$tab_char = get_permalink() . $set_char;

	return $tab_char;
}

//front end manage auctions Ajax callback
function show_front_end_user_auc_callback() {
	require_once 'user-manage-auctions.php';

	if (class_exists('Auctions_List_Table_Front')) {
		$FrontAuctionsList = new Auctions_List_Table_Front();

		$data_array = $FrontAuctionsList->wdm_get_data($_POST['fe_auc_type'], $_POST['fe_auc_perm']);
		$col_array = $FrontAuctionsList->get_columns($_POST['fe_auc_type']);

		$manage_your_auction = wdm_prepare_front_items($data_array, $col_array, 'show', $_POST['fe_auc_type']);

		echo $manage_your_auction;

	}
	die();
}

add_action('wp_ajax_show_front_end_user_auc', 'show_front_end_user_auc_callback');
add_action('wp_ajax_nopriv_show_front_end_user_auc', 'show_front_end_user_auc_callback');

//front end manage bids Ajax callback
function show_front_end_user_bid_callback() {
	require_once 'user-manage-bids.php';

	if (class_exists('Bids_List_Table_Front')) {
		$FrontBidsList = new Bids_List_Table_Front();

		$data_array = $FrontBidsList->wdm_get_data($_POST['fe_bid_type']);
		$col_array = $FrontBidsList->get_columns($_POST['fe_bid_type']);

		$manage_your_bid = wdm_prepare_front_items($data_array, $col_array, 'show', $_POST['fe_bid_type']);

		echo $manage_your_bid;

	}
	die();
}

add_action('wp_ajax_show_front_end_user_bid', 'show_front_end_user_bid_callback');
add_action('wp_ajax_nopriv_show_front_end_user_bid', 'show_front_end_user_bid_callback');

add_action('ua_add_auction_submenu', 'pp_inv_new_submenu', 10, 3);

function pp_inv_new_submenu($parent, $capability, $function) {
	add_submenu_page($parent, __('Invoices', 'wdm-ultimate-auction'), __('Invoices', 'wdm-ultimate-auction'), $capability, 'invoices', $function);
}

add_action('ua_add_auction_tab', 'pp_inv_new_tab', 10, 4);

function pp_inv_new_tab($page, $class1, $class2, $active_tab) {
	$user1 = wp_get_current_user();
	if ($active_tab == 'invoices') {
		$class = $class2;
	} else {
		$class = '';
	}

//    if(in_array('administrator', $user1->roles)){
	//	$tab  = '<a href="?'.$page.'=invoices"';
	//    }
	//    else
	//    {
	$tab = '<a href="?' . $page . '=invoices"';
	//}
	$tab .= ' class="' . $class1 . ' ' . $class . '"';
	$tab .= '>' . __('Invoices', 'wdm-ultimate-auction') . '</a>';

	echo $tab;

}

//front end payment Ajax callback
function show_front_end_user_pmt_callback() {
	//if(is_user_logged_in()){
	//$logged_user_id = wp_get_current_user();  //get user id
	//$logged_user_role = $logged_user_id->roles; //get user role
	//$cu_id = get_current_user_id();

	require_once 'payment_front.php';
	echo $manage_payment;
	//}
	//    else{
	//	_e("Please log in first.", "wdm-ultimate-auction");
	//    }

	die();
}

add_action('wp_ajax_front_end_user_pmt', 'show_front_end_user_pmt_callback');
add_action('wp_ajax_nopriv_front_end_user_pmt', 'show_front_end_user_pmt_callback');
//Ajax callback to update best offers meta for auction.

function ua_bst_ofrs_user_meta_callback() {

	//update offers

	$currency_code = substr(get_option('wdm_currency'), -3);

	$wdm_ua_offers_arr = get_post_meta($_POST['auction_id'], 'auction_best_offers', true);

	if (!is_array($wdm_ua_offers_arr) || empty($wdm_ua_offers_arr)) {
		$wdm_ua_offers_arr = array();
	}

	//if( ! isset( $wdm_ua_offers_arr[ $_POST[ 'os_id' ] ] ) ){

	$wdm_ua_offers_arr[$_POST['os_id']] = array(

		'offer_val' => $_POST['offer_val'],

	);

	//}

	update_post_meta($_POST['auction_id'], 'auction_best_offers', $wdm_ua_offers_arr);

	echo '<br /><div class="wdm_offers_sent_text">

                <p>' . __('Your offer is pending for review by auction owner.', 'wdm-ultimate-auction') . '</p>

		<p>' . sprintf(__('Offer amount: %s%s', 'wdm-ultimate-auction'), $currency_code . " ", $_POST['offer_val']) . '</p>

                </div>

                <br />';

	die();

}

add_action('wp_ajax_send_best_offers_now', 'ua_bst_ofrs_user_meta_callback');

add_action('wp_ajax_nopriv_send_best_offers_now', 'ua_bst_ofrs_user_meta_callback');

//Ajax function callback to update auction on accept/reject best offer.

function wdm_best_offer_owner_action_callback() {

	$wdm_auction_id = $_POST['wdm_auction_id'];

	if ($_POST['owner_decision'] == "reject") {

		$auction_best_offers_arr = get_post_meta($wdm_auction_id, 'auction_best_offers', true);

		if (!is_array($auction_best_offers_arr) || empty($auction_best_offers_arr)) {
			$auction_best_offers_arr = array();
		}

		if (isset($auction_best_offers_arr[$_POST['wdm_best_offer_sender_id']])) {

			unset($auction_best_offers_arr[$_POST['wdm_best_offer_sender_id']]);

			update_post_meta($wdm_auction_id, 'auction_best_offers', $auction_best_offers_arr);

			echo json_encode(array('status' => 'success'));

		}

		die();

	}

	if ($_POST['owner_decision'] == 'accept') {

		update_post_meta($wdm_auction_id, 'wdm_listing_ends', date("Y-m-d H:i:s", time()));

		$check_term = term_exists('expired', 'auction-status');

		wp_set_post_terms($wdm_auction_id, $check_term["term_id"], 'auction-status');

		update_post_meta($wdm_auction_id, 'wdm_auction_expired_by', "ua_best_offers");

		update_post_meta($wdm_auction_id, 'auction_best_offers', array());

		update_post_meta($wdm_auction_id, 'auction_winner_by_best_offer', array(

			$_POST['wdm_best_offer_sender_id'] => array(

				'offer_val' => $_POST['wdm_best_offer_val'],

			),

		)

		);

		$wdm_accept_status = "success";

		$wdm_auction = get_post($wdm_auction_id);

		$ret_url = get_post_meta($wdm_auction_id, "current_auction_permalink", true);

		$wdm_email_sent = ultimate_auction_email_template(esc_js($wdm_auction->post_title), $wdm_auction_id, esc_js($wdm_auction->post_content), round($_POST['wdm_best_offer_val'], 2), $_POST['wdm_best_offer_sender_email'], $ret_url);

		echo json_encode(array('accept_status' => $wdm_accept_status,

			'email_status' => $wdm_email_sent,

		)

		);

	}

	die();

}

add_action('wp_ajax_wdm_best_offer_owner_action', 'wdm_best_offer_owner_action_callback');

add_action('wp_ajax_nopriv_wdm_best_offer_owner_action', 'wdm_best_offer_owner_action_callback');

function wdm_filter_where($where = '') {
	// posts in the last 7 days
	$where .= " AND post_date > '" . date('Y-m-d', strtotime('-7 days')) . "'";

	return $where;
}

function wdm_filter_where_exp($where = '') {
	// posts ended in the last 7 days
	global $wpdb;

	$where .= " AND ID IN(SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'auction_bought_status' AND meta_value = 'bought' UNION ALL SELECT s3.post_id
FROM " . $wpdb->prefix . "wdm_bidders s1, $wpdb->postmeta s3
WHERE s3.post_id = s1.auction_id AND s3.meta_value <= s1.bid AND s3.meta_key = 'wdm_lowest_bid' AND s1.bid=(SELECT MAX(s2.bid)
              FROM " . $wpdb->prefix . "wdm_bidders s2
              WHERE s1.auction_id = s2.auction_id))";

	return $where;
}

function ua_wp_media_upload_show() {
	//wp_enqueue_media();
	?>
	<script type="text/javascript">
		jQuery(document).ready(function($){
			var product_gallery_frame;
			var $image_gallery_ids = $('#product_image_gallery');
			var $product_images = $('#product_images_container ul.product_images');

			jQuery('.add_product_images').on( 'click', 'a', function( event ) {

				var $el = $(this);
				var attachment_ids = $image_gallery_ids.val();

				event.preventDefault();

				// If the media frame already exists, reopen it.
				if ( product_gallery_frame ) {
					product_gallery_frame.open();
					return;
				}

				// Create the media frame.
				product_gallery_frame = wp.media.frames.downloadable_file = wp.media({
					// Set the title of the modal.
					title: '<?php _e('Add Images to Product Gallery', 'wdm-ultimate-auction');?>',
					button: {
						text: '<?php _e('Add to gallery', 'wdm-ultimate-auction');?>',
					},
					multiple: true
				});

				// When an image is selected, run a callback.
				product_gallery_frame.on( 'select', function() {

					var selection = product_gallery_frame.state().get('selection');

					selection.map( function( attachment ) {

						attachment = attachment.toJSON();

						if ( attachment.id ) {
							attachment_ids = attachment_ids ? attachment_ids + "," + attachment.id : attachment.id;

							$product_images.append('\
								<li class="wdmimage" data-attachment_id="' + attachment.id + '">\
									<img src="' + attachment.url + '" />\
									<ul class="actions">\
										<li><a href="#" class="delete" title="<?php _e('Delete image', 'wdm-ultimate-auction');?>"><?php _e('Delete', 'wdm-ultimate-auction');?></a></li>\
									</ul>\
								</li>');

						}

					} );

					$image_gallery_ids.val( attachment_ids );
				});

				// Finally, open the modal.
				product_gallery_frame.open();
			});

			});

	</script>
	<?php
}

add_action('pre_get_posts', 'wdm_restrict_media_library');

function wdm_restrict_media_library($wp_query_obj) {
	global $current_user, $pagenow;
	if (!is_a($current_user, 'WP_User')) {
		return;
	}

	if ('admin-ajax.php' != $pagenow || $_REQUEST['action'] != 'query-attachments') {
		return;
	}

	if (!current_user_can('manage_media_library')) {
		$wp_query_obj->set('author', $current_user->ID);
	}

	return;
}
require_once 'email-template.php';

function wdm_get_mime_type($url) {
	global $wpdb;

	$mime = $wpdb->get_var($wpdb->prepare(
		"
		SELECT post_mime_type
		FROM $wpdb->posts
		WHERE guid = %s
	",
		$url
	));

	return $mime;
}

add_action('ua_update_payment_settings', 'wdm_update_payment_settings', 10, 3);

function wdm_update_payment_settings($update, $cu_id, $logged_user_role) {

	if (isset($update['wdm_paypal_address'])) {
		if (in_array('administrator', $logged_user_role)) {
			update_option('wdm_paypal_address', $update['wdm_paypal_address']);
		} else {
			update_user_meta($cu_id, 'wdm_paypal_address', $update['wdm_paypal_address']);
		}

	}

	if (isset($update['wdm_account_mode'])) {
		if (in_array('administrator', $logged_user_role)) {
			update_option('wdm_account_mode', $update['wdm_account_mode']);
		} else {
			update_user_meta($cu_id, 'wdm_account_mode', $update['wdm_account_mode']);
		}

	}

	if (isset($update['wdm_mailing_address'])) {
		if (in_array('administrator', $logged_user_role)) {
			update_option('wdm_mailing_address', $update['wdm_mailing_address']);
		} else {
			update_user_meta($cu_id, 'wdm_mailing_address', $update['wdm_mailing_address']);
		}

	}

	if (isset($update['wdm_wire_transfer'])) {
		if (in_array('administrator', $logged_user_role)) {
			update_option('wdm_wire_transfer', $update['wdm_wire_transfer']);
		} else {
			update_user_meta($cu_id, 'wdm_wire_transfer', $update['wdm_wire_transfer']);
		}

	}

	if (isset($update['wdm_cash'])) {
		if (in_array('administrator', $logged_user_role)) {
			update_option('wdm_cash', $update['wdm_cash']);
		} else {
			update_user_meta($cu_id, 'wdm_cash', $update['wdm_cash']);
		}

	}

	if (isset($update['auction_user_paypal_email'])) {
		if (isset($update['ua_wdm_usr_pmt_auc']) && wp_verify_nonce($update['ua_wdm_usr_pmt_auc'], 'ua_usr_pmt_wp_n_f')) {
			update_user_meta($cu_id, 'auction_user_paypal_email', $update['auction_user_paypal_email']);
		} else {
			die(__('Sorry, your nonce did not verify.', 'wdm-ultimate-auction'));
		}

	}
}

add_filter('ua_list_winner_info', 'wdm_list_winner_info', 99, 4);

function wdm_list_winner_info($info, $winner, $id, $col) {

	if (!empty($winner)) {

		$info = "<a href='#' class='wdm_winner_info wdm-margin-bottom' id='wdm_winner_info_" . $col . "_" . $id . "'>" . $winner->user_login . "</a>";
		$info .= "<div class='wdm-margin-bottom wdm_winner_info_" . $col . "_" . $id . "' style='display:none;'><div>";
		$info .= !empty($winner->first_name) ? $winner->first_name : "";
		$info .= !empty($winner->last_name) ? " " . $winner->last_name : "";
		$info .= "</div><div><a href='mailto:" . $winner->user_email . "'>" . $winner->user_email . "</a></div></div>";
	}

	return $info;
}
add_filter('ua_best_offer_sender_info', 'wdm_ua_best_offer_sender_info', 99, 4);

function wdm_ua_best_offer_sender_info($info, $winner, $id, $col) {

	if (!empty($winner)) {

		$info = "<a href='#' class='wdm_bo_winner_info wdm-margin-bottom' id='wdm_bo_winner_info_" . $col . "_" . $id . "'>" . $winner->user_login . "</a>";

		$info .= "<div class='wdm-margin-bottom wdm_bo_winner_info_" . $col . "_" . $id . "' style='display:none;'><div>";

		$info .= !empty($winner->first_name) ? $winner->first_name : "";

		$info .= !empty($winner->last_name) ? " " . $winner->last_name : "";

		$info .= "</div><div><a href='mailto:" . $winner->user_email . "'>" . $winner->user_email . "</a></div></div>";

	}

	return $info;

}

function wdm_download_digital_product_file() {
	if (isset($_GET["ult_auc_id"]) && !empty($_GET["ult_auc_id"]) && isset($_GET['url']) && !empty($_GET['url'])) {

		$auction_id = $_GET["ult_auc_id"];
		$product_type = get_post_meta($auction_id, 'wdm_product_type', true);
		$auction = get_post($auction_id);
		$auction_status = wp_get_post_terms($auction->ID, 'auction-status', array("fields" => "names"));
		if (in_array('expired', $auction_status) && $product_type == 'digital') {
			$url_key = $_GET['url'];
			$url = get_post_meta($auction_id, 'wdm-digital-product-file', true);
			$url_check = md5($url);
			if ($url_key == $url_check) {
				$file = basename($url);
				
				$arr = explode("wp-content", $url);
				$file_short_path = $arr[1];
				
				if(defined('ABSPATH'))
					$path = ABSPATH."wp-content".$file_short_path;
				else
					$path = $url;
				
				header('Content-Description: File Transfer');
				header('Content-Type: application/download');
				header('Content-Disposition: attachment; filename="' . $file . '";');
				//header('Content-Transfer-Encoding: binary');
				//header('Expires: 0');
				//header('Cache-Control: must-revalidate');
				//header('Pragma: public');
				//header('Content-Length: ' . filesize($file));
				readfile($path);
			}
		}
		die();
	}
}
add_action('init', 'wdm_download_digital_product_file');

function delete_auction_on_user_delete( $user_id ) {
	
	global $wpdb;

	$auction_ids = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_author = %d and post_type='ultimate-auction'", $user_id ) );
	
	$user_data = get_userdata( $user_id );
	
	$force = true;

	foreach ($auction_ids as $id) {

		$delete_auction_array = $wpdb->get_col($wpdb->prepare("SELECT meta_value from $wpdb->postmeta WHERE meta_key LIKE %s AND post_id = %d", '%wdm-image-%', $id));

		$del_auc = wp_delete_post($id, $force);
		
		if ($del_auc) {
			foreach ($delete_auction_array as $delete_image_url) {
				if (!empty($delete_image_url) && $delete_image_url !== null) {
					$auction_url_post_id = $wpdb->get_var("SELECT ID from $wpdb->posts WHERE guid = '$delete_image_url' AND post_type = 'attachment'");
					wp_delete_post($auction_url_post_id, $force); //also delete images attached
				}
			}
		
		$wpdb->query(
			$wpdb->prepare("DELETE FROM " . $wpdb->prefix . "wdm_bidders WHERE auction_id = %d",
			$id
			)
		);
		
		}
	}
	
	if ($del_auc) {
			$wpdb->query(
			$wpdb->prepare("DELETE FROM " . $wpdb->prefix . "wdm_bidders WHERE name = %s",
			$user_data->user_login
			)
		);
	}

}

add_action( 'delete_user', 'delete_auction_on_user_delete', 10, 2 );
?>