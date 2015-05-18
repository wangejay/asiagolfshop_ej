<?php
//currency code
$ship_curr_code = substr(get_option('wdm_currency'), -3);

//all countries
$ua_all_countries = array(
	'AF' => 'Afghanistan',
	'AL' => 'Albania',
	'DZ' => 'Algeria',
	'AS' => 'American Samoa',
	'AD' => 'Andorra',
	'AO' => 'Angola',
	'AI' => 'Anguilla',
	'AQ' => 'Antarctica',
	'AG' => 'Antigua and Barbuda',
	'AR' => 'Argentina',
	'AM' => 'Armenia',
	'AW' => 'Aruba',
	'AU' => 'Australia',
	'AT' => 'Austria',
	'AZ' => 'Azerbaijan',
	'BS' => 'Bahamas',
	'BH' => 'Bahrain',
	'BD' => 'Bangladesh',
	'BB' => 'Barbados',
	'BY' => 'Belarus',
	'BE' => 'Belgium',
	'BZ' => 'Belize',
	'BJ' => 'Benin',
	'BM' => 'Bermuda',
	'BT' => 'Bhutan',
	'BO' => 'Bolivia',
	'BA' => 'Bosnia and Herzegovina',
	'BW' => 'Botswana',
	'BV' => 'Bouvet Island',
	'BR' => 'Brazil',
	'BQ' => 'British Antarctic Territory',
	'IO' => 'British Indian Ocean Territory',
	'VG' => 'British Virgin Islands',
	'BN' => 'Brunei',
	'BG' => 'Bulgaria',
	'BF' => 'Burkina Faso',
	'BI' => 'Burundi',
	'KH' => 'Cambodia',
	'CM' => 'Cameroon',
	'CA' => 'Canada',
	'CT' => 'Canton and Enderbury Islands',
	'CV' => 'Cape Verde',
	'KY' => 'Cayman Islands',
	'CF' => 'Central African Republic',
	'TD' => 'Chad',
	'CL' => 'Chile',
	'CN' => 'China',
	'CX' => 'Christmas Island',
	'CC' => 'Cocos [Keeling] Islands',
	'CO' => 'Colombia',
	'KM' => 'Comoros',
	'CG' => 'Congo - Brazzaville',
	'CD' => 'Congo - Kinshasa',
	'CK' => 'Cook Islands',
	'CR' => 'Costa Rica',
	'HR' => 'Croatia',
	'CU' => 'Cuba',
	'CY' => 'Cyprus',
	'CZ' => 'Czech Republic',
	'CI' => 'Côte d’Ivoire',
	'DK' => 'Denmark',
	'DJ' => 'Djibouti',
	'DM' => 'Dominica',
	'DO' => 'Dominican Republic',
	'NQ' => 'Dronning Maud Land',
	'DD' => 'East Germany',
	'EC' => 'Ecuador',
	'EG' => 'Egypt',
	'SV' => 'El Salvador',
	'GQ' => 'Equatorial Guinea',
	'ER' => 'Eritrea',
	'EE' => 'Estonia',
	'ET' => 'Ethiopia',
	'FK' => 'Falkland Islands',
	'FO' => 'Faroe Islands',
	'FJ' => 'Fiji',
	'FI' => 'Finland',
	'FR' => 'France',
	'GF' => 'French Guiana',
	'PF' => 'French Polynesia',
	'TF' => 'French Southern Territories',
	'FQ' => 'French Southern and Antarctic Territories',
	'GA' => 'Gabon',
	'GM' => 'Gambia',
	'GE' => 'Georgia',
	'DE' => 'Germany',
	'GH' => 'Ghana',
	'GI' => 'Gibraltar',
	'GR' => 'Greece',
	'GL' => 'Greenland',
	'GD' => 'Grenada',
	'GP' => 'Guadeloupe',
	'GU' => 'Guam',
	'GT' => 'Guatemala',
	'GG' => 'Guernsey',
	'GN' => 'Guinea',
	'GW' => 'Guinea-Bissau',
	'GY' => 'Guyana',
	'HT' => 'Haiti',
	'HM' => 'Heard Island and McDonald Islands',
	'HN' => 'Honduras',
	'HK' => 'Hong Kong SAR China',
	'HU' => 'Hungary',
	'IS' => 'Iceland',
	'IN' => 'India',
	'ID' => 'Indonesia',
	'IR' => 'Iran',
	'IQ' => 'Iraq',
	'IE' => 'Ireland',
	'IM' => 'Isle of Man',
	'IL' => 'Israel',
	'IT' => 'Italy',
	'JM' => 'Jamaica',
	'JP' => 'Japan',
	'JE' => 'Jersey',
	'JT' => 'Johnston Island',
	'JO' => 'Jordan',
	'KZ' => 'Kazakhstan',
	'KE' => 'Kenya',
	'KI' => 'Kiribati',
	'KW' => 'Kuwait',
	'KG' => 'Kyrgyzstan',
	'LA' => 'Laos',
	'LV' => 'Latvia',
	'LB' => 'Lebanon',
	'LS' => 'Lesotho',
	'LR' => 'Liberia',
	'LY' => 'Libya',
	'LI' => 'Liechtenstein',
	'LT' => 'Lithuania',
	'LU' => 'Luxembourg',
	'MO' => 'Macau SAR China',
	'MK' => 'Macedonia',
	'MG' => 'Madagascar',
	'MW' => 'Malawi',
	'MY' => 'Malaysia',
	'MV' => 'Maldives',
	'ML' => 'Mali',
	'MT' => 'Malta',
	'MH' => 'Marshall Islands',
	'MQ' => 'Martinique',
	'MR' => 'Mauritania',
	'MU' => 'Mauritius',
	'YT' => 'Mayotte',
	'FX' => 'Metropolitan France',
	'MX' => 'Mexico',
	'FM' => 'Micronesia',
	'MI' => 'Midway Islands',
	'MD' => 'Moldova',
	'MC' => 'Monaco',
	'MN' => 'Mongolia',
	'ME' => 'Montenegro',
	'MS' => 'Montserrat',
	'MA' => 'Morocco',
	'MZ' => 'Mozambique',
	'MM' => 'Myanmar [Burma]',
	'NA' => 'Namibia',
	'NR' => 'Nauru',
	'NP' => 'Nepal',
	'NL' => 'Netherlands',
	'AN' => 'Netherlands Antilles',
	'NT' => 'Neutral Zone',
	'NC' => 'New Caledonia',
	'NZ' => 'New Zealand',
	'NI' => 'Nicaragua',
	'NE' => 'Niger',
	'NG' => 'Nigeria',
	'NU' => 'Niue',
	'NF' => 'Norfolk Island',
	'KP' => 'North Korea',
	'VD' => 'North Vietnam',
	'MP' => 'Northern Mariana Islands',
	'NO' => 'Norway',
	'OM' => 'Oman',
	'PC' => 'Pacific Islands Trust Territory',
	'PK' => 'Pakistan',
	'PW' => 'Palau',
	'PS' => 'Palestinian Territories',
	'PA' => 'Panama',
	'PZ' => 'Panama Canal Zone',
	'PG' => 'Papua New Guinea',
	'PY' => 'Paraguay',
	'YD' => 'People\'s Democratic Republic of Yemen',
	'PE' => 'Peru',
	'PH' => 'Philippines',
	'PN' => 'Pitcairn Islands',
	'PL' => 'Poland',
	'PT' => 'Portugal',
	'PR' => 'Puerto Rico',
	'QA' => 'Qatar',
	'RO' => 'Romania',
	'RU' => 'Russia',
	'RW' => 'Rwanda',
	'RE' => 'Réunion',
	'BL' => 'Saint Barthélemy',
	'SH' => 'Saint Helena',
	'KN' => 'Saint Kitts and Nevis',
	'LC' => 'Saint Lucia',
	'MF' => 'Saint Martin',
	'PM' => 'Saint Pierre and Miquelon',
	'VC' => 'Saint Vincent and the Grenadines',
	'WS' => 'Samoa',
	'SM' => 'San Marino',
	'SA' => 'Saudi Arabia',
	'SN' => 'Senegal',
	'RS' => 'Serbia',
	'CS' => 'Serbia and Montenegro',
	'SC' => 'Seychelles',
	'SL' => 'Sierra Leone',
	'SG' => 'Singapore',
	'SK' => 'Slovakia',
	'SI' => 'Slovenia',
	'SB' => 'Solomon Islands',
	'SO' => 'Somalia',
	'ZA' => 'South Africa',
	'GS' => 'South Georgia and the South Sandwich Islands',
	'KR' => 'South Korea',
	'ES' => 'Spain',
	'LK' => 'Sri Lanka',
	'SD' => 'Sudan',
	'SR' => 'Suriname',
	'SJ' => 'Svalbard and Jan Mayen',
	'SZ' => 'Swaziland',
	'SE' => 'Sweden',
	'CH' => 'Switzerland',
	'SY' => 'Syria',
	'ST' => 'São Tomé and Príncipe',
	'TW' => 'Taiwan',
	'TJ' => 'Tajikistan',
	'TZ' => 'Tanzania',
	'TH' => 'Thailand',
	'TL' => 'Timor-Leste',
	'TG' => 'Togo',
	'TK' => 'Tokelau',
	'TO' => 'Tonga',
	'TT' => 'Trinidad and Tobago',
	'TN' => 'Tunisia',
	'TR' => 'Turkey',
	'TM' => 'Turkmenistan',
	'TC' => 'Turks and Caicos Islands',
	'TV' => 'Tuvalu',
	'UM' => 'U.S. Minor Outlying Islands',
	'PU' => 'U.S. Miscellaneous Pacific Islands',
	'VI' => 'U.S. Virgin Islands',
	'UG' => 'Uganda',
	'UA' => 'Ukraine',
	'SU' => 'Union of Soviet Socialist Republics',
	'AE' => 'United Arab Emirates',
	'GB' => 'United Kingdom',
	'US' => 'United States',
	'ZZ' => 'Unknown or Invalid Region',
	'UY' => 'Uruguay',
	'UZ' => 'Uzbekistan',
	'VU' => 'Vanuatu',
	'VA' => 'Vatican City',
	'VE' => 'Venezuela',
	'VN' => 'Vietnam',
	'WK' => 'Wake Island',
	'WF' => 'Wallis and Futuna',
	'EH' => 'Western Sahara',
	'YE' => 'Yemen',
	'ZM' => 'Zambia',
	'ZW' => 'Zimbabwe',
	'AX' => 'Åland Islands',
);

//add shipping fields on register page
require_once 'user-registration.php';

//include shipping amount in 'buy now' button
add_filter('ua_product_shipping_cost_field', 'wdm_ua_shipping_cost_field', 99, 2);

function wdm_ua_shipping_cost_field($shipping_field, $auc_id) {
	if (is_user_logged_in()) {
		$curr_user = wp_get_current_user();
		$buyer_email = $curr_user->user_email;
		$shipping_field = wdm_append_shipping_field($auc_id, $shipping_field, $buyer_email, 'button');
	}
	return $shipping_field;
}

//include shipping amount in PayPal payment link
add_filter('ua_product_shipping_cost_link', 'wdm_ua_shipping_cost_link', 99, 3);

function wdm_ua_shipping_cost_link($shipping_link, $auc_id, $buyer_email) {
	$shipping_link = wdm_append_shipping_field($auc_id, $shipping_link, $buyer_email, 'link');
	return $shipping_link;
}

//include shipping amount for wire transfer and cheque
add_filter('ua_product_shipping_cost_wire_cheque', 'wdm_ua_shipping_cost_wire_cheque', 99, 4);

function wdm_ua_shipping_cost_wire_cheque($shipping_link, $auc_id, $win_bid, $email_winner) {

	$buyer = get_user_by('email', $email_winner);
	$user_country = get_the_author_meta('ua_user_country', $buyer->ID);
	$dom_shipping = '';
	$int_shipping = '';
	$shipping_input = '';
	$shipping_link = '';

	$cur_code = substr(get_option('wdm_currency'), -3);

	if (get_post_meta($auc_id, 'wdm_enable_shipping', true) == "1") {

		if (get_post_meta($auc_id, 'wdm_shipping_type', true) == "paidship") {

			if (get_post_meta($auc_id, 'wdm_shipping_domestic', true) == "1") {

				$dom_amt = get_post_meta($auc_id, 'wdm_dom_shipping_price', true);

			}

			if (get_post_meta($auc_id, 'wdm_shipping_international', true) == "1") {

				$int_amt = get_post_meta($auc_id, 'wdm_int_shipping_price', true);

			}

			if ($user_country == get_post_meta($auc_id, 'wdm_shipping_country', true)) {
				$shipping_input = $dom_amt;
			} else {
				$shipping_input = $int_amt;
			}

		} else {
			$shipping_input = '';
		}

	}

	if ($shipping_input) {
		$wdm_total_cost = ($win_bid + $shipping_input);
		$pay_amt = "<strong>" . $cur_code . " " . $win_bid . "</strong>";
		$shipping_input = "<strong>" . $cur_code . " " . $shipping_input . "</strong>";
		$wdm_total_cost = "<strong>" . $cur_code . " " . $wdm_total_cost . "</strong>";
		$shipping_link = __('Product Price', 'wdm-ultimate-auction') . ": " . $pay_amt . "<br />" . __('Shipping Cost', 'wdm-ultimate-auction') . ": " . $shipping_input . "<br /><br />";
		$shipping_link .= sprintf(__('You can pay %s', 'wdm-ultimate-auction'), $wdm_total_cost);
	}
	return $shipping_link;
}
////shipping amonut for wire transfer and cheque
//add_filter('ua_product_shipping_cost_wire_cheque','',99,1);
//
//function wdm_ua_shipping_cost($org_amt)
//{
//   return $org_amt;
//}

//shipping fields in dashboard
add_action('ua_add_shipping_cost_input_field', 'wdm_add_shipping_cost_field');

function wdm_add_shipping_cost_field() {

	//wp_enqueue_script('wdm_ua_ui_js', plugins_url('js/jquery-ui.min.js', __FILE__), array('jquery'));
	wp_enqueue_script('wdm_multiselect_ui_js', plugins_url('js/jquery.multiselect.js', __FILE__), array('jquery'));
	wp_enqueue_style('wdm_multiselect_ui_css', plugins_url('css/jquery.multiselect.css', __FILE__));

	global $post_id;
	if ($post_id) {
		if (isset($_POST["ua_shipping_cost"])) {
			update_post_meta($post_id, 'wdm_enable_shipping', $_POST["ua_shipping_cost"]);
		} else {
			update_post_meta($post_id, 'wdm_enable_shipping', "");
		}

		update_post_meta($post_id, 'wdm_shipping_type', $_POST["ua_shipping_type"]);
		update_post_meta($post_id, 'wdm_free_shipping_text', $_POST["free_shipping_text"]);
		update_post_meta($post_id, 'wdm_shipping_country', $_POST["ua_item_location"]);

		if (isset($_POST["ua_shipping_domestic"])) {
			update_post_meta($post_id, 'wdm_shipping_domestic', $_POST["ua_shipping_domestic"]);
		} else {
			update_post_meta($post_id, 'wdm_shipping_domestic', "");
		}

		update_post_meta($post_id, 'wdm_dom_shipping_price', round($_POST["ua_dom_price"], 2));
		update_post_meta($post_id, 'wdm_dom_shipping_text', $_POST["dom_delivery_text"]);

		if (isset($_POST["ua_shipping_international"])) {
			update_post_meta($post_id, 'wdm_shipping_international', $_POST["ua_shipping_international"]);
		} else {
			update_post_meta($post_id, 'wdm_shipping_international', "");
		}

		update_post_meta($post_id, 'wdm_int_shipping_price', round($_POST["ua_int_price"], 2));
		update_post_meta($post_id, 'wdm_int_shipping_text', $_POST["int_delivery_text"]);

		if (isset($_POST["ua_ships_to_countries"])) {
			$country_arr = implode(",", $_POST["ua_ships_to_countries"]);
			update_post_meta($post_id, 'wdm_ships_to_countries', $country_arr);
		}

		if (isset($_POST["ua_free_ships_to_countries"])) {
			$count_arr = implode(",", $_POST["ua_free_ships_to_countries"]);
			update_post_meta($post_id, 'wdm_free_ships_to_countries', $count_arr);
		}

	}

	global $ship_curr_code;
	global $ua_all_countries;

	?>
 <tr valign="top">

        <th scope="row">
            <label for="ua_shipping_cost"><?php _e('Shipping Cost', 'wdm-ultimate-auction');?></label>
        </th>

        <td>
            <input id="ua_shipping_cost" name="ua_shipping_cost" type="checkbox" value="1" <?php checked('1', wdm_shipping_post_meta('wdm_enable_shipping'));?> /> <?php _e('Enable', 'wdm-ultimate-auction');?> <!-- Enable/Disable shipping cost -->

         <div id="wdm_shipping_sections" style="display: none;"> <!-- Shipping sections -->

            <br />
            <!-- Item Location : Country-->
             <?php
_e('Item Location', 'wdm-ultimate-auction');
	echo " <select id='ua_item_location' name='ua_item_location' class='required'>";
	echo "<option value=''>" . __('Select Country', 'wdm-ultimate-auction') . "</option>";
	foreach ($ua_all_countries as $ac_key => $ac) {
		$selected = (wdm_shipping_post_meta('wdm_shipping_country') == $ac_key) ? 'selected="selected"' : '';
		echo "<option value='" . $ac_key . "' $selected>$ac</option>";
	}
	echo "</select>";
	?>
            <br /><br />

            <!--Free shipping-->
            <input id="ua_free_shipping_type" name="ua_shipping_type" type="radio" value="freeship" <?php if (wdm_shipping_post_meta('wdm_shipping_type') == "") {
		echo "checked";
	} elseif (wdm_shipping_post_meta('wdm_shipping_type') == "freeship") {
		echo "checked";
	}
	?> /> <?php _e('Free Shipping', 'wdm-ultimate-auction');?>

            <span class="ua_shipping_cont_style" id="free_shipping_container" style="display: none;">

                <?php
_e('Where to ship', 'wdm-ultimate-auction');
	echo " <select multiple id='ua_free_ships_to_countries' name='ua_free_ships_to_countries[]' class='wdm_ships_to_countries'>";
	foreach ($ua_all_countries as $cntry) {
		echo "<option value='" . $cntry . "' >$cntry</option>";
	}

	echo "</select>";
	$all_country = wdm_shipping_post_meta('wdm_free_ships_to_countries');
	?>
        <div class="ult-auc-settings-tip" style="margin-left: 88px"><?php _e('Countries where this product can be shipped', 'wdm-ultimate-auction');?></div>
        <br />
            <?php _e('Delivery Text', 'wdm-ultimate-auction');?> &nbsp;&nbsp;<input class="regular-text ua_delivery_text" id="free_shipping_text" name="free_shipping_text" type="text" placeholder="<?php _e('Enter Your Text', 'wdm-ultimate-auction');?>" value="<?php echo wdm_shipping_post_meta('wdm_free_shipping_text');?>" />
            <div class="ult-auc-settings-tip" style="margin-left: 87px"><?php _e('Text to display business days for product delivery', 'wdm-ultimate-auction');?></div>
            </span> <!--End free_shipping_container-->
            <br />
            <!--Paid Shipping-->
            <input id="ua_paid_shipping_type" name="ua_shipping_type" type="radio" value="paidship" <?php if (wdm_shipping_post_meta('wdm_shipping_type') == "paidship") {
		echo "checked";
	}
	?> /> <?php _e('Paid Shipping', 'wdm-ultimate-auction');?> <br />
            <span class="ua_shipping_cont_style" id="paid_shipping_container" style="display: none;">

            <input id="ua_shipping_domestic" name="ua_shipping_domestic" type="checkbox" value="1" <?php checked('1', wdm_shipping_post_meta('wdm_shipping_domestic'));?> /> <?php _e('Domestic', 'wdm-ultimate-auction');?> <br />
            <span class="ua_dom_data_field" style="display: none;">
            <br />
            <?php echo $ship_curr_code;?> <input id="ua_dom_price" name="ua_dom_price" type="text" placeholder="<?php _e('Price', 'wdm-ultimate-auction');?>" class="small-text number required" value="<?php echo wdm_shipping_post_meta('wdm_dom_shipping_price');?>" />
            &nbsp;&nbsp;<input class="regular-text ua_delivery_text" id="dom_delivery_text" name="dom_delivery_text" type="text" placeholder="<?php _e('Enter Your Text', 'wdm-ultimate-auction');?>" value="<?php echo wdm_shipping_post_meta('wdm_dom_shipping_text');?>" /> <br />
            <div class="ult-auc-settings-tip fe_ship_set_tip"><span style="width: 92px;float: left;"><?php _e('Shipping Price', 'wdm-ultimate-auction');?></span>
               <span style="width: 298px;"><?php _e('Text to display business days for product delivery', 'wdm-ultimate-auction');?></span>
            </div>
            <br />
            </span>

            <input id="ua_shipping_international" name="ua_shipping_international" type="checkbox" value="1" <?php checked('1', wdm_shipping_post_meta('wdm_shipping_international'));?> /> <?php _e('International', 'wdm-ultimate-auction');?> <br />
            <span class="ua_int_data_field" style="display: none;"><br />
            <?php
_e('Where to ship', 'wdm-ultimate-auction');
	echo " <select multiple id='ua_ships_to_countries' name='ua_ships_to_countries[]' class='wdm_ships_to_countries'>";
	foreach ($ua_all_countries as $country) {
		echo "<option value='" . $country . "' >$country</option>";
	}

	echo "</select>";
	$all_count = wdm_shipping_post_meta('wdm_ships_to_countries');
	?>
            <div class="ult-auc-settings-tip" style="margin-left: 88px"><?php _e('Countries where this product can be shipped', 'wdm-ultimate-auction');?></div>
<br />
<?php echo $ship_curr_code;?> <input id="ua_int_price" name="ua_int_price" type="text" placeholder="<?php _e('Price', 'wdm-ultimate-auction');?>" class="small-text number required" value="<?php echo wdm_shipping_post_meta('wdm_int_shipping_price');?>" />
            &nbsp;&nbsp;<input class="regular-text ua_delivery_text" id="int_delivery_text" name="int_delivery_text" type="text" placeholder="<?php _e('Enter Your Text', 'wdm-ultimate-auction');?>" value="<?php echo wdm_shipping_post_meta('wdm_int_shipping_text');?>" /> <br />
            <div class="ult-auc-settings-tip fe_ship_set_tip"><span style="width: 92px;float: left;"><?php _e('Shipping Price', 'wdm-ultimate-auction');?></span>
               <span style="width: 298px;float: left;"><?php _e('Text to display business days for product delivery', 'wdm-ultimate-auction');?></span>
            </div>
            </span>
            </span> <!--End paid_shipping_container-->

         </div> <!--End wdm_shipping_sections-->

         </td>
    </tr>

 <?php wp_enqueue_script('ua_shipping_fields_js', plugins_url('js/shipping-fields.js', __FILE__), array('jquery'));?>

 <script type="text/javascript">
      jQuery(document).ready(function(){
      jQuery(".wdm_ships_to_countries").multiselect({
      //selectedText: "# of # countries selected",
      noneSelectedText: "<?php _e('Select Countries', 'wdm-ultimate-auction');?>",
      selectedList: 264,
      classes: "wdm_ua_multiselect_country"
   });

      var cnt_str1 = '<?php echo $all_country;?>';
      var cnt_arr1 = cnt_str1.split(',');
      var i;
      jQuery("#ua_free_ships_to_countries").multiselect("widget").find(":checkbox").each(function(){
         for(i=0;i<cnt_arr1.length;i++){
            if(jQuery(this).val() == cnt_arr1[i])
            this.click();
            }
      });

      var cnt_str2 = '<?php echo $all_count;?>';
      var cnt_arr2 = cnt_str2.split(',');
      var j;
      jQuery("#ua_ships_to_countries").multiselect("widget").find(":checkbox").each(function(){
         for(j=0;j<cnt_arr2.length;j++){
            if(jQuery(this).val() == cnt_arr2[j])
            this.click();
            }
      });

      jQuery("#wdm-add-auction-form").submit(function(){
         if(jQuery('#ua_paid_shipping_type').is(':checked')){
            if((!jQuery('#ua_shipping_domestic').is(':checked')) && (!jQuery('#ua_shipping_international').is(':checked'))){
               alert("<?php _e('Please select either or both of Domestic and International rate.', 'wdm-ultimate-auction');?>");
               return false;
            }
         }
         });
      });
 </script>

   <?php
wp_enqueue_style('ua_shipping_be_css', plugins_url('css/shipping-be.css', __FILE__));
}

//handle post meta keys for shipping fields
function wdm_shipping_post_meta($meta_key) {

	global $post_id;

	if ($post_id != "") {
		return get_post_meta($post_id, $meta_key, true);
	} else if (isset($_POST["update_auction"]) && !empty($_POST["update_auction"])) {
		return get_post_meta($_POST["update_auction"], $meta_key, true);
	} else if (isset($_GET["edit_auction"]) && !empty($_GET["edit_auction"])) {
		return get_post_meta($_GET["edit_auction"], $meta_key, true);
	} else {
		return "";
	}

}

//append shipping field to PayPal link and button
function wdm_append_shipping_field($auc_id, $shipping_input, $buyer_email, $type) {
	$buyer = get_user_by('email', $buyer_email);
	$user_country = get_the_author_meta('ua_user_country', $buyer->ID);
	$dom_shipping = '';
	$int_shipping = '';
	$dom_amt = '';
	$int_amt = '';

	if (get_post_meta($auc_id, 'wdm_enable_shipping', true) == "1") {

		if (get_post_meta($auc_id, 'wdm_shipping_type', true) == "paidship") {

			if (get_post_meta($auc_id, 'wdm_shipping_domestic', true) == "1") {

				$dom_amt = get_post_meta($auc_id, 'wdm_dom_shipping_price', true);

				if ($type == 'button') {
					$dom_shipping = '<input type="hidden" name="shipping" value="' . $dom_amt . '">';
				} elseif ($type == 'link') {
					$dom_shipping = "&shipping=" . urlencode($dom_amt);
				} elseif ($type == 'invoice') {
					$dom_shipping = $dom_amt;
				}

			}

			if (get_post_meta($auc_id, 'wdm_shipping_international', true) == "1") {

				$int_amt = get_post_meta($auc_id, 'wdm_int_shipping_price', true);

				if ($type == 'button') {
					$int_shipping = '<input type="hidden" name="shipping" value="' . $int_amt . '">';
				} elseif ($type == 'link') {
					$int_shipping = "&shipping=" . urlencode($int_amt);
				} elseif ($type == 'invoice') {
					$int_shipping = $int_amt;
				}

			}

			if ($user_country == get_post_meta($auc_id, 'wdm_shipping_country', true)) {
				$shipping_input = $dom_shipping;
				update_post_meta($auc_id, 'wdm_ua_shipping_amt', $dom_amt);
			} else {
				$shipping_input = $int_shipping;
				update_post_meta($auc_id, 'wdm_ua_shipping_amt', $int_amt);
			}
		} else {
			$shipping_input = '';
			update_post_meta($auc_id, 'wdm_ua_shipping_amt', '');
		}
	}

	return $shipping_input;
}

//shipping details on product page
add_action('ua_add_shipping_cost_view_field', 'wdm_show_shipping_cost_field');

function wdm_show_shipping_cost_field($auc_id) {

	//Get Currency Symbol if set and Code Display value if the currency symbol is '$' or 'kr'
	$ship_curr_code = substr(get_option('wdm_currency'), -3);
	$ship_curr_code_display = '';
	$ship_curr_symbol = '';

	preg_match('/-([^ ]+)/', get_option('wdm_currency'), $matches);

	if (isset($matches[1])) {
		$ship_curr_symbol = $matches[1];
	}

	if (empty($ship_curr_symbol)) {
		$ship_curr_symbol = $ship_curr_code . ' ';
	} else {
		if ($ship_curr_symbol == '$' || $ship_curr_symbol == 'kr') {
			$ship_curr_code_display = $ship_curr_code;
		}
	}
	$cc = get_post_meta($auc_id, 'wdm_shipping_country', true);

	$shipping_view = '';

	if (get_post_meta($auc_id, 'wdm_enable_shipping', true) == "1") {

		global $ua_all_countries;

		$shipping_view = '<span class="ua_ship_fe_menu_text"> ' . __("Shipping", "wdm-ultimate-auction") . ': </span>';
		$item_loc = '';
		$common_loc = '';
		if (!empty($cc)) {
			$item_loc = '<br /><span class="ua_shipping_small_text"> ' . __("Item Location", "wdm-ultimate-auction") . ': &nbsp;<strong>' . $ua_all_countries[$cc] . '</strong> </span><br />';
			$common_loc = '<span class="ua_common_country">' . sprintf(__("%s & many other countries", "wdm-ultimate-auction"), $ua_all_countries[$cc]) . '</span>';
		}

		if (get_post_meta($auc_id, 'wdm_shipping_type', true) == "paidship") {

			$shipping_view .= '<span class="ua_ship_fe_val_text">';
			$shipping_view .= '<span class="del_ship_type_text_ua">' . __("PAID", "wdm-ultimate-auction") . '</span>';
			$paid_ship_to = get_post_meta($auc_id, 'wdm_ships_to_countries', true);
			$paid_ship_to = str_replace(",", ",&nbsp;", $paid_ship_to);

			$shipping_view .= $item_loc;

			if (!empty($paid_ship_to)) {
				$shipping_view .= '<span class="ua_shipping_small_text">' . __("Ships to", "wdm-ultimate-auction") . ': &nbsp;' . $common_loc . '<span class="ua_hidden_countries" style="display:none;">' . $paid_ship_to . '</span> <br /><span class="ua_see_all_countries">' . __("Show Other countries", "wdm-ultimate-auction") . '</span></span><br /><br />';
			}

			$shipping_view .= '</span><br />';

			if (get_post_meta($auc_id, 'wdm_shipping_domestic', true) == "1") {
				$shipping_view .= '<span class="ua_paid_ship_menu_text">' . __("Domestic Rate", "wdm-ultimate-auction") . ': </span>&nbsp;';
				$shipping_view .= $ship_curr_symbol . sprintf("%s", number_format(get_post_meta($auc_id, 'wdm_dom_shipping_price', true), 2, '.', ',')) . ' ' . $ship_curr_code_display . '<br />';

				$dom_ship_text = get_post_meta($auc_id, 'wdm_dom_shipping_text', true);
				if (!empty($dom_ship_text)) {
					$shipping_view .= '<span class="ua_ship_fe_menu_text">' . __("Delivery", "wdm-ultimate-auction") . ':</span> <span class="ua_ship_fe_val_text"> ' . $dom_ship_text . '</span> <br />';
				}

			}

			if (get_post_meta($auc_id, 'wdm_shipping_international', true) == "1") {
				$shipping_view .= '<span class="ua_paid_ship_menu_text">' . __("International Rate", "wdm-ultimate-auction") . ': </span>&nbsp;';
				$shipping_view .= $ship_curr_symbol . sprintf("%s", number_format(get_post_meta($auc_id, 'wdm_int_shipping_price', true), 2, '.', ',')) . ' ' . $ship_curr_code_display . '<br />';

				$int_ship_text = get_post_meta($auc_id, 'wdm_int_shipping_text', true);
				if (!empty($int_ship_text)) {
					$shipping_view .= '<span class="ua_ship_fe_menu_text">' . __("Delivery", "wdm-ultimate-auction") . ':</span> <span class="ua_ship_fe_val_text"> ' . $int_ship_text . '</span> <br />';
				}

			}
		} else {
			$shipping_view .= '<span class="ua_ship_fe_val_text">';
			$shipping_view .= '<span class="del_ship_type_text_ua">' . __("FREE", "wdm-ultimate-auction") . '</span>';
			$free_ship_to = get_post_meta($auc_id, 'wdm_free_ships_to_countries', true);
			$free_ship_to = str_replace(",", ",&nbsp;", $free_ship_to);

			$shipping_view .= $item_loc;

			if (!empty($free_ship_to)) {
				$shipping_view .= '<span class="ua_shipping_small_text">' . __("Ships to", "wdm-ultimate-auction") . ': &nbsp;' . $common_loc . '<span class="ua_hidden_countries" style="display:none;">' . $free_ship_to . '</span><br /><span class="ua_see_all_countries">' . __("Show Other countries", "wdm-ultimate-auction") . '</span></span>';
			}

			$shipping_view .= '</span><br />';

			$free_ship_text = get_post_meta($auc_id, 'wdm_free_shipping_text', true);
			if (!empty($free_ship_text)) {
				$shipping_view .= '<span class="ua_ship_fe_menu_text">' . __("Delivery", "wdm-ultimate-auction") . ':</span> <span class="ua_ship_fe_val_text"> ' . get_post_meta($auc_id, 'wdm_free_shipping_text', true) . '</span>';
			}

		}
	}

	echo "<div id='ua_shipping_container'>" . $shipping_view . "</div>";

	wp_enqueue_style('ua_shipping_fe_css', plugins_url('css/shipping-fe.css', __FILE__));

	?>

 <script type="text/javascript">
 jQuery(document).ready(function(){
         jQuery(".ua_see_all_countries").click(function(){
         jQuery(".ua_hidden_countries").toggle();
         jQuery(".ua_common_country").toggle();
         if(jQuery(".ua_hidden_countries").css('display') == 'none')
            jQuery(".ua_see_all_countries").html("<?php _e('Show other countries', 'wdm-ultimate-auction');?>");
         else
            jQuery(".ua_see_all_countries").html("<?php _e('Hide other countries', 'wdm-ultimate-auction');?>");
         });
         });
 </script>
 <?php
}

//send email notification with shipping details to seller
add_action('ua_shipping_data_email', 'wdm_send_shipping_data_to_seller');

/* Array contents
$data = array('auc_id' => $auction_id,
'auc_name' => $auction_name,
'auc_desc' => $auction_desc,
'auc_price' => $winner_bid,
'auc_currency' => $cur_code,
'seller_paypal_email' => $rec_email,
'winner_email' => $winner_email,
'seller_email' => $auction_email,
'winner_name' => $winner_name,
'pay_method' => $check_method,
'site_name' => $site_name,
'site_url' => $site_url,
'product_url' => $return_url,
'header' => $headers
);*/

function wdm_send_shipping_data_to_seller($data) {

	global $ua_all_countries;

	$email_template_details = get_option("wdm_ua_email_template_auction_sold_shipping", array());
	$ua_email_enable = true;

	$winner = get_user_by('email', $data['winner_email']);

	$user_street = get_the_author_meta('ua_user_street_add', $winner->ID);
	$user_street = !empty($user_street) ? $user_street : '<span style="color: #555555;">' . __("Not Specified", "wdm-ultimate-auction") . '</span>';

	$user_country = get_the_author_meta('ua_user_country', $winner->ID);
	$user_country = $ua_all_countries[$user_country];
	$user_country = !empty($user_country) ? $user_country : '<span style="color: #555555;">' . __("Not Specified", "wdm-ultimate-auction") . '</span>';

	$user_state = get_the_author_meta('ua_user_state', $winner->ID);
	$user_state = !empty($user_state) ? $user_state : '<span style="color: #555555;">' . __("Not Specified", "wdm-ultimate-auction") . '</span>';

	$user_pincode = get_the_author_meta('ua_user_pincode', $winner->ID);
	$user_pincode = !empty($user_pincode) ? $user_pincode : '<span style="color: #555555;">' . __("Not Specified", "wdm-ultimate-auction") . '</span>';

	$user_phone = get_the_author_meta('ua_user_phno', $winner->ID);
	$user_phone = !empty($user_phone) ? $user_phone : '<span style="color: #555555;">Not Specified</span>';

	$shipping_data = "";
	$shipping_data = "<strong>" . __('Shipping Address', 'wdm-ultimate-auction') . ":</strong> <br /><br />";
	$shipping_data .= "&nbsp;&nbsp;" . __('Street Address', 'wdm-ultimate-auction') . ": " . $user_street . "<br /><br />";
	$shipping_data .= "&nbsp;&nbsp;" . __('Country', 'wdm-ultimate-auction') . ": " . $user_country . "<br /><br />";
	$shipping_data .= "&nbsp;&nbsp;" . __('State', 'wdm-ultimate-auction') . ": " . $user_state . "<br /><br />";
	$shipping_data .= "&nbsp;&nbsp;" . __('ZIP', 'wdm-ultimate-auction') . ": " . $user_pincode . "<br /><br />";
	$shipping_data .= "&nbsp;&nbsp;" . __('Phone', 'wdm-ultimate-auction') . ": " . $user_phone;

	if (isset($email_template_details['template']) && $email_template_details['template'] == "ua_custom") {
		$subject = str_replace('{site_name}', $data['site_name'], $email_template_details['subject']);
		$subject = str_replace('{product_name}', $data['auc_name'], $subject);

		$message = str_replace('{site_url}', $data['site_url'], wpautop(convert_chars(wptexturize($email_template_details['body']))));
		$message = str_replace('{product_url}', $data['product_url'], $message);
		$message = str_replace('{product_name}', $data['auc_name'], $message);
		$message = str_replace('{currency_code}', $data['auc_currency'], $message);
		$message = str_replace('{auction_price}', $data['auc_price'], $message);
                $shp = 0;
                $shp = wdm_append_shipping_field($data['auc_id'], $shp, $data['winner_email'], 'invoice');
                if(empty($shp))
                    $shp = 0;
		$message = str_replace('{ship_amount}', $shp, $message);
		$message = str_replace('{winner_name}', $data['winner_name'], $message);
		$message = str_replace('{winner_email}', $data['winner_email'], $message);
		$message = str_replace('{shipping_data}', $shipping_data, $message);

		if ($email_template_details['enable'] == "no") {
			$ua_email_enable = false;
		}
	} else {
		$subject = $data['site_name'] . ": " . __('An auction has been sold', 'wdm-ultimate-auction') . " - " . $data['auc_name'];
		$message = __('An auction has been sold on your site', 'wdm-ultimate-auction') . " - " . $data['site_url'] . "<br /><br />";

		$message .= "<strong>" . __('Product Details', 'wdm-ultimate-auction') . "</strong> - <br /><br />";
		$message .= "&nbsp;&nbsp;" . __('Product URL', 'wdm-ultimate-auction') . ": " . $data['product_url'] . "<br /><br />";
		$message .= "&nbsp;&nbsp;" . __('Product Name', 'wdm-ultimate-auction') . ": " . $data['auc_name'] . "<br /><br />";
		$message .= "&nbsp;&nbsp;" . __('Product Price', 'wdm-ultimate-auction') . ": " . $data['auc_currency'] . " " . sprintf("%.2f", $data['auc_price']) . "<br /><br />";
		$shp = 0;
		$shp = wdm_append_shipping_field($data['auc_id'], $shp, $data['winner_email'], 'invoice');
		if (!empty($shp) && $shp > 0) {
			$message .= "&nbsp;&nbsp;" . __('Shipping Price', 'wdm-ultimate-auction') . ": " . $data['auc_currency'] . " " . sprintf("%.2f", $shp) . "<br /><br />";
		}

		$message .= "<strong>" . __('Winner Details', 'wdm-ultimate-auction') . "</strong> - <br /><br />";
		$message .= "&nbsp;&nbsp;" . __('Winner Name', 'wdm-ultimate-auction') . ": " . $data['winner_name'] . "<br /><br />";
		$message .= "&nbsp;&nbsp;" . __('Winner Email', 'wdm-ultimate-auction') . ": " . $data['winner_email'] . "<br /><br />";

		if (!empty($user_country)) {
			$message .= $shipping_data;
		}

	}

	$send_to_seller = false;
	$sent_to_seller = get_post_meta($data['auc_id'], 'shipping_email_sent_to_seller', true);

	if ($sent_to_seller != 'sent') {
		if ($ua_email_enable) {
			$send_to_seller = wp_mail($data['seller_email'], $subject, $message, $data['header'], '');
		}
	}

	if ($send_to_seller) {
		update_post_meta($data['auc_id'], 'shipping_email_sent_to_seller', 'sent');
	} else {
		update_post_meta($data['auc_id'], 'shipping_email_sent_to_seller', '');
	}

}

//include shipping amount in PayPal invoice
add_filter('ua_shipping_data_invoice', 'wdm_shipping_amt_invoice', 99, 3);

function wdm_shipping_amt_invoice($shipping_amt, $auc_id, $buyer_email) {

	$shipping_amt = wdm_append_shipping_field($auc_id, $shipping_amt, $buyer_email, 'invoice');
	return $shipping_amt;
}

function wdm_show_ship_short_link($auc_id) {

	if (get_post_meta($auc_id, 'wdm_enable_shipping', true) == "1") {?>
				<div class="wdm_shipping-info-div">
				<a href="#wdm-tab-anchor-id" id="wdm-shipping-info-link"><?php _e('Shipping Info', 'wdm-ultimate-auction');?></a>
				</div>
    <?php }

}
add_action('wdm_ua_ship_short_link', 'wdm_show_ship_short_link', 10, 1);

function wdmua_add_ship_tab($auc_id) {
	if (get_post_meta($auc_id, 'wdm_enable_shipping', true) == "1") {?>
    <li id="wdm-desc-ship-link"><?php _e('Shipping', 'wdm-ultimate-auction');?></li>
    <?php
}
}

add_action('wdm_ua_add_ship_tab', 'wdmua_add_ship_tab', 10, 1);
?>
