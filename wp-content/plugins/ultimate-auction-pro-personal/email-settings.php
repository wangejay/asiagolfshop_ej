<?php
add_filter('wdm_email_form_fields', 'wdm_email_form_fields_callback', 10, 3);

function wdm_email_form_fields_callback($form_fields, $email_temp_data, $email_tab) {
	$slug = $email_tab['slug'];
	if ($slug == "auction_won_bidder") {
		unset($form_fields[$slug . "[wdm_ua_email_body]"]);
		$form_fields[$slug . "[wdm_ua_email_body_admin]"] = array(
			'title' => __('Email Body (Admin is Winner)', 'wdm-ultimate-auction'),
			'description' => isset($email_tab['body_admin_hint']) ? $email_tab['body_admin_hint'] : '',
			'id' => 'wdm_ua_email_body_admin',
			'type' => 'wp_editor',
			'css' => 'size:12px;row:4',
			'default' => '',
			//'placeholder' => __('Enter the email body footer', 'wdm-ultimate-auction'),
			'value' => $email_temp_data['body_admin'],
		);
		$form_fields[$slug . "[wdm_ua_email_body_header]"] = array(
			'title' => __('Email Body (Common Message)', 'wdm-ultimate-auction'),
			'description' => isset($email_tab['body_header_hint']) ? $email_tab['body_header_hint'] : '',
			'id' => 'wdm_ua_email_body_header',
			'type' => 'wp_editor',
			'css' => 'size:12px;row:4',
			'default' => '',
			//'placeholder' => __('Enter the email body header', 'wdm-ultimate-auction'),
			'value' => $email_temp_data['body_header'],
		);
		$form_fields[$slug . "[wdm_ua_email_body_paypal]"] = array(
			'title' => __('Email Body (PayPal)', 'wdm-ultimate-auction'),
			'description' => isset($email_tab['body_paypal_hint']) ? $email_tab['body_paypal_hint'] : '',
			'id' => 'wdm_ua_email_body_paypal',
			'type' => 'wp_editor',
			'css' => 'size:12px;row:4',
			'default' => '',
			//'placeholder' => __('Enter the email body for paypal', 'wdm-ultimate-auction'),
			'value' => $email_temp_data['body_paypal'],
		);
		$form_fields[$slug . "[wdm_ua_email_body_wire_transfer]"] = array(
			'title' => __('Email Body (Wire Transfer)', 'wdm-ultimate-auction'),
			'description' => isset($email_tab['body_wire_transfer_hint']) ? $email_tab['body_wire_transfer_hint'] : '',
			'id' => 'wdm_ua_email_body_wire_transfer',
			'type' => 'wp_editor',
			'css' => 'size:12px;row:4',
			'default' => '',
			//'placeholder' => __('Enter the email body for Wire Transfer', 'wdm-ultimate-auction'),
			'value' => $email_temp_data['body_wire_transfer'],
		);
		$form_fields[$slug . "[wdm_ua_email_body_mailing]"] = array(
			'title' => __('Email Body (Mailing/Cheque)', 'wdm-ultimate-auction'),
			'description' => isset($email_tab['body_mailing_hint']) ? $email_tab['body_mailing_hint'] : '',
			'id' => 'wdm_ua_email_body_mailing',
			'type' => 'wp_editor',
			'css' => 'size:12px;row:4',
			'default' => '',
			//'placeholder' => __('Enter the email body for Mailing', 'wdm-ultimate-auction'),
			'value' => $email_temp_data['body_mailing'],
		);
		$form_fields[$slug . "[wdm_ua_email_body_cash]"] = array(
			'title' => __('Email Body (Cash)', 'wdm-ultimate-auction'),
			'description' => isset($email_tab['body_cash_hint']) ? $email_tab['body_cash_hint'] : '',
			'id' => 'wdm_ua_email_body_cash',
			'type' => 'wp_editor',
			'css' => 'size:12px;row:4',
			'default' => '',
			//'placeholder' => __('Enter the email body for Cash', 'wdm-ultimate-auction'),
			'value' => $email_temp_data['body_cash'],
		);
		$form_fields[$slug . "[wdm_ua_email_body_footer]"] = array(
			'title' => __('Email Body (Footer)', 'wdm-ultimate-auction'),
			'description' => isset($email_tab['body_footer_hint']) ? $email_tab['body_footer_hint'] : '',
			'id' => 'wdm_ua_email_body_footer',
			'type' => 'wp_editor',
			'css' => 'size:12px;row:4',
			'default' => '',
			//'placeholder' => __('Enter the email body footer', 'wdm-ultimate-auction'),
			'value' => $email_temp_data['body_footer'],
		);
	}

	return $form_fields;
}

add_filter('wdm_email_template_details_save', 'wdm_email_template_details_save_callback', 10, 3);

function wdm_email_template_details_save_callback($email_template_details, $email_form_data, $slug) {
	if ($slug == "auction_won_bidder") {

		$email_template_details['body_header'] = stripslashes($email_form_data["wdm_ua_email_body_header"]);
		$email_template_details['body_paypal'] = stripslashes($email_form_data["wdm_ua_email_body_paypal"]);
		$email_template_details['body_wire_transfer'] = stripslashes($email_form_data["wdm_ua_email_body_wire_transfer"]);
		$email_template_details['body_mailing'] = stripslashes($email_form_data["wdm_ua_email_body_mailing"]);
		$email_template_details['body_cash'] = stripslashes($email_form_data["wdm_ua_email_body_cash"]);
		$email_template_details['body_footer'] = stripslashes($email_form_data["wdm_ua_email_body_footer"]);
		$email_template_details['body_admin'] = stripslashes($email_form_data["wdm_ua_email_body_admin"]);

	}

	return $email_template_details;
}

add_filter('ua_add_email_templates_tabs', 'wdm_ua_add_email_templates_tabs_function');

function wdm_ua_add_email_templates_tabs_function($email_tabs) {
	$return_email_tabs = array();
	// echo "<pre>";print_r( $email_tabs );echo "</pre>";
	if (is_array($email_tabs)) {
		foreach ($email_tabs as $email_tab) {
			if (isset($email_tab['slug']) && $email_tab['slug'] == 'auction_won_bidder') {
				$email_tab['body_admin_hint'] = __("Enter the email body of the email sent to the bidder after winning auction if the winner is Admin. HTML is accepted.", 'wdm-ultimate-auction');
				$email_tab['body_header_hint'] = __("Enter the header of the email body of the email sent to the bidder after winning auction. HTML is accepted. Available template tags:-\\n
								{winner_name} - The winner's name\\n
								{site_url} - A url of the site\\n
								{product_url} - Auction item page URL\\n
								{product_name} - Auction item name\\n
						    	{product_description} - Auction item description", 'wdm-ultimate-auction');
				$email_tab['body_paypal_hint'] = __("Enter the Paypal section of the email body of the email sent to the bidder after winning auction. HTML is accepted. Available template tags:-\\n
								{auction_email} - The auction's owner's email\\n
								{total_amount} - The total amount to pay\\n
								{payment_amount} - The item's paypal amount\\n
								{currency_code} - The currency code\\n
								{ship_amount} - The shipping amount\\n
								{paypal_link} - The link of the paypal to pay the amount", 'wdm-ultimate-auction');
				$email_tab['body_wire_transfer_hint'] = __("Enter the Wire Transfer section of the email body of the email sent to the bidder after winning auction . HTML is accepted. Available template tags:-\\n
								{payment_amount} - The amount to be paid\\n
								{wt_message} - The Wire Transfer message\\n
								{wt_details} - The Wire Transfer details", 'wdm-ultimate-auction');
				$email_tab['body_mailing_hint'] = __("Enter the Mailing section of the email body of the email sent to the bidder after winning auction. HTML is accepted. Available template tags:-\\n
								{payment_amount} - The amount to be paid\\n
								{m_message} - The Mailing message\\n
								{m_details} - The Mailing details", 'wdm-ultimate-auction');
				$email_tab['body_cash_hint'] = __("Enter the Cash section of the email body of the email sent to the bidder after winning auction. HTML is accepted. Available template tags:-\\n
								{payment_amount} - The amount to be paid\\n
								{c_message} - The Cash message\\n
								{c_details} - The Cash details", 'wdm-ultimate-auction');
				$email_tab['body_footer_hint'] = __("Enter the footer of the email body of the email sent to the bidder after winning auction. HTML is accepted.\\n", 'wdm-ultimate-auction');
			}
			$return_email_tabs[] = $email_tab;
		}
		return $return_email_tabs;
	}
	return $email_tabs;
}
//echo"<pre>";print_r($_POST);echo"</pre>";

wdm_ua_email_form_data_save();

function wdm_ua_email_form_data_save() {
	if (isset($_POST['wdm_ua_email_form_nonce_field']) && wp_verify_nonce($_POST['wdm_ua_email_form_nonce_field'], 'wdm_ua_email_form_nonce')) {
		if (is_array($_POST)) {
			foreach ($_POST as $email_type => $email_form_data) {
				if (is_array($email_form_data)) {
					if (isset($email_form_data['wdm_ua_email_hidden_field'])) {
						//echo"<pre>";print_r($email_form_data);echo"</pre>";
						$email_template_details = array(
							'template' => $email_form_data["wdm_ua_email_template"],
							'enable' => (isset($email_form_data["wdm_ua_email_enable"]) ? "yes" : "no"),
							'subject' => stripslashes($email_form_data["wdm_ua_email_subject"]),
							'body' => stripslashes($email_form_data["wdm_ua_email_body"]),
						);
						$email_template_details = apply_filters("wdm_email_template_details_save", $email_template_details, $email_form_data, $email_type);
						update_option("wdm_ua_email_template_" . $email_type, $email_template_details);
					}
				}
			}
		}
	}
}

$wdm_email_tabs = array(
	array('slug' => 'bid_place_bidder',
		'label' => __('Bid Place - Bidder', 'wdm-ultimate-auction'),
		'subject_hint' => __("Enter the subject of the email that is sent to the bidder after successfully placing a bid. Available template tags:-\\n
				  			{blog_name} - The name of the blog\\n
				  			{product_name} - Auction item name", 'wdm-ultimate-auction'),
		'body_hint' => __("Enter the email that is sent to the bidder after successfully placing a bid. HTML is accepted. Available template tags:-\\n
						    {product_url} - Auction item page URL\\n
						    {product_name} - Auction item name\\n
						    {currency_code} - The currency code\\n
						    {bid_value} - The value of the bid placed\\n
						    {product_description} - Auction item description", 'wdm-ultimate-auction'),
	),
	array('slug' => 'bid_place_seller',
		'label' => __('Bid Place - Seller', 'wdm-ultimate-auction'),
		'subject_hint' => __("Enter the subject of the email that is sent to the seller after successfully placing a bid. Available template tags:-\\n
				  			{blog_name} - The name of the blog\\n
				  			{product_name} - Auction item name", 'wdm-ultimate-auction'),
		'body_hint' => __("Enter the email that is sent to the seller after successfully placing a bid. HTML is accepted. Available template tags:-\\n
						    {bidder_name} - The bidder's name\\n
						    {bidder_email} - The bidder's email\\n
						    {currency_code}  - The currency code\\n
						    {bid_value} - The value of the bid placed\\n
						    {product_url} - Auction item page URL\\n
						    {product_name} - Auction item name\\n
						    {product_description} - Auction item description", 'wdm-ultimate-auction'),
	),
	array('slug' => 'auction_won_bidder',
		'label' => __('Auction Won - Bidder', 'wdm-ultimate-auction'),
		'subject_hint' => __("Enter the subject of the email that is sent to the bidder after winning an auction. Available template tags:-\\n
				  			{site_name} - The name of the site", 'wdm-ultimate-auction'),
	),
	array('slug' => 'auction_sold_seller',
		'label' => __('Auction Sold - Seller', 'wdm-ultimate-auction'),
		'subject_hint' => __("Enter the subject of the email that is sent to the seller after winning an auction by bidder. Available template tags:-\\n
				  			{site_name} - The name of the site", 'wdm-ultimate-auction'),
		'body_hint' => __("Enter the email that is sent to the seller after winning an auction by bidder. HTML is accepted. Available template tags:-\\n
				  			{site_url} - A url of the site\\n
						    {product_url} - Auction item page URL\\n
						    {product_name} - Auction item name\\n
						    {currency_code}  - The currency code\\n
						    {bid_value} - The value of the bid placed\\n
						    {winner_name} - The winner's name\\n
						    {winner_email} - The winner's email", 'wdm-ultimate-auction'),
	),
	//    array( 'slug' => 'buy_now_buyer',
	//	  'label' => __('Buy Now - Buyer', 'wdm-ultimate-auction'),
	//  'subject_hint' => __('Hint: [{{site_name}}] An auction has been sold - {product_name}', 'wdm-ultimate-auction'),
	//  'body_hint' => __("Hint:\\nAn auction has been sold on your site - {site_url}<br /><br />\\n
	//		    <strong>Product Details</strong> - <br /><br />\\n
	//		    &nbsp;&nbsp;Product URL: <a href='{product_url}'>{product_url}</a><br /><br />\\n
	//		    &nbsp;&nbsp;Product Name: {product_name}<br /><br />\\n
	//		    &nbsp;&nbsp;Product Price: {currency_code} {bid_value}<br /><br />\\n
	//		    <strong>Winner Details</strong> - <br /><br />\\n
	//		    &nbsp;&nbsp;Winner Name: {winner_name}<br /><br />\\n
	//		    &nbsp;&nbsp;Winner Email: {winner_email}<br /><br />", 'wdm-ultimate-auction')
	//    ),
	array('slug' => 'buy_now_seller',
		'label' => __('Buy Now - Seller', 'wdm-ultimate-auction'),
		'subject_hint' => __("Enter the subject of the email that is sent to the seller after the successful auction buy by buyer. Available template tags:-\\n
				  			{site_name} - The name of the site\\n
				  			{product_name} - Auction item name", 'wdm-ultimate-auction'),
		'body_hint' => __("Enter the email that is sent to the seller after the successful auction buy by buyer. HTML is accepted. Available template tags:-\\n
				  			{site_url} - The url of the site\\n
							{product_url} - Auction item page URL\\n
							{product_name} - Auction item name\\n
							{currency_code}  - The currency code\\n
						    {bid_value} - The value of the bid placed\\n
						    {winner_name} - The winner's name\\n
						    {winner_email} - The winner's email", 'wdm-ultimate-auction'),
	),
	array('slug' => 'outbid',
		'label' => __('Outbid', 'wdm-ultimate-auction'),
		'subject_hint' => __("Enter the subject of the email that is sent to the seller after the outbid. Available template tags:-\\n
				  			{blog_name} - The name of the blog\\n
				  			{product_name} - Auction item name", 'wdm-ultimate-auction'),
		'body_hint' => __("Enter the email that is sent to the seller after the outbid. HTML is accepted. Available template tags:-\\n
						    {product_url} - Auction item page URL\\n
						    {product_name} - Auction item name\\n
						    {currency_code} - The currency code\\n
						    {bid_value} - The value of the bid placed\\n
						    {product_description} - Auction item description", 'wdm-ultimate-auction'),
	),
	array('slug' => 'digital_auction',
		'label' => __('Digital Auction', 'wdm-ultimate-auction'),
		'subject_hint' => __("Enter the subject of the email that is sent to the winner for digital auction.", 'wdm-ultimate-auction'),
		'body_hint' => __("Enter the email that is sent to the winner for digital auction. HTML is accepted. Available template tags:-\\n
						    {download_url} - The url to download auction\\n
						    {product_url} - Auction item page URL", 'wdm-ultimate-auction'),
	),
	array('slug' => 'private_message',
		'label' => __('Private Message', 'wdm-ultimate-auction'),
		'subject_hint' => __("Enter the subject of the 'private message' email. Available template tags:-\\n
				  			{blog_name} - The name of the blog", 'wdm-ultimate-auction'),
		'body_hint' => __("Enter the 'private message' email. HTML is accepted. Available template tags:-\\n
				  			{p_name} - The name of the private message sender\\n
						    {p_email} - The email of the private message sender\\n
						    {p_message} - The private message\\n
						    {product_url} - Auction item page URL", 'wdm-ultimate-auction'),
	),
	array('slug' => 'resend_invoice',
		'label' => __('Resend Invoice', 'wdm-ultimate-auction'),
		'subject_hint' => __("Enter the subject of the 'Resend Invoice' email. Available template tags:-\\n
				  			{site_name} - The name of the site", 'wdm-ultimate-auction'),
		'body_hint' => __("Enter the 'Resend Invoice' email. Available template tags:-\\n
							{product_url} - Auction item page URL\\n
							{product_name} - The name of the product\\n
							{product_description} - Auction item description\\n
							{payment_amount} - The item's paypal amount\\n
							{currency_code} - The currency code\\n
							{ship_amount} - The shipping amount\\n
							{invoice_number} - The invoice_number\\n
							{invoice_url} - The link of the paypal to pay the amount", 'wdm-ultimate-auction'),
	),
	array('slug' => 'auction_sold_shipping',
		'label' => __('Auction Sold - Seller (with Shipping details)', 'wdm-ultimate-auction'),
		'subject_hint' => __("Enter the subject of the 'Auction Sold' email. Available template tags:-\\n
                        {site_name} - The name of the site\\n
                        {product_name} - Auction item name", 'wdm-ultimate-auction'),
		'body_hint' => __("Enter the 'Auction Sold' email. Available template tags:-\\n
                        {site_url} - The url of the site\\n
                        {product_url} - Auction item page URL\\n
                        {product_name} - Auction item name\\n
                        {currency_code} - The currency code\\n
                        {auction_price} - The product's price\\n
                        {ship_amount} - The shipping amount\\n
                        {winner_name} - The winner's name\\n
			{winner_email} - The winner's email\\n
                        {shipping_data} - The shipping details", 'wdm-ultimate-auction'),
	),
);

?>
<ul class="subsubsub">
    <?php

$link = '';

$email_tabs = apply_filters('ua_add_email_templates_tabs', $wdm_email_tabs);

if (isset($_GET['email_type'])) {
	$link = $_GET['email_type'];
}

foreach ($email_tabs as $email_tab) {
	if (empty($link)) {
		$link = 'bid_place_bidder';
	}

	?>
	    <li><a href="?page=email&email_type=<?php echo $email_tab['slug'];?>" class="<?php echo $link == $email_tab['slug'] ? 'current' : '';?>"><?php echo $email_tab['label'];?></a>|</li>
	    <?php
}
?>
</ul>
<br class="clear">

<?php
if (is_array($email_tabs)) {
	foreach ($email_tabs as $email_tab) {
		if ((!isset($_GET['email_type']) && $email_tab['slug'] == 'bid_place_bidder') || $_GET['email_type'] == $email_tab['slug']) {
			$get_email_template_details = get_option("wdm_ua_email_template_" . (isset($_GET['email_type']) ? $_GET['email_type'] : 'bid_place_bidder'), 1);
			$wdm_fields = array(
				$email_tab['slug'] . '[wdm_ua_email_template]' => array(
					'title' => __('Default Email Template', 'wdm-ultimate-auction'),
					'description' => sprintf(__('Select email template for the email type.', 'wdm-ultimate-auction')),
					'id' => 'wdm_ua_email_template',
					'type' => 'radio',
					'css' => '',
					'default' => 'ua_default',
					'options' => array(
						'ua_default' => __('Default', 'wdm-ultimate-auction'),
						'ua_custom' => __('Custom', 'wdm-ultimate-auction'),
					),
					'value' => $get_email_template_details['template'],
				),
				$email_tab['slug'] . '[wdm_ua_email_enable]' => array(
					'title' => __('Enable/Disable', 'wdm-ultimate-auction'),
					'description' => sprintf(__('Enable this email notification.', 'wdm-ultimate-auction')),
					'id' => 'wdm_ua_email_enable',
					'type' => 'checkbox',
					'css' => '',
					'default' => 1,
					'value' => $get_email_template_details['enable'],
				),
				$email_tab['slug'] . '[wdm_ua_email_subject]' => array(
					'title' => __('Email Subject', 'wdm-ultimate-auction'),
					'description' => isset($email_tab['subject_hint']) ? '<div class="wdm-ua-email-subject-hint">' . str_replace('\\n', '</br>', $email_tab['subject_hint']) . '</div>' : '',
					'id' => 'wdm_ua_email_subject',
					'type' => 'text',
					'css' => 'min-width:850px;',
					'default' => '',
					'placeholder' => __('Enter the email subject', 'wdm-ultimate-auction'),
					'value' => $get_email_template_details['subject'],
				),
				$email_tab['slug'] . '[wdm_ua_email_body]' => array(
					'title' => __('Email Body', 'wdm-ultimate-auction'),
					'description' => isset($email_tab['body_hint']) ? $email_tab['body_hint'] : '',
					'id' => 'wdm_ua_email_body',
					'type' => 'wp_editor',
					'css' => 'size:12px;row:4',
					'default' => '',
					'placeholder' => __('Enter the email body', 'wdm-ultimate-auction'),
					'value' => $get_email_template_details['body'],
				),
			);

			$wdm_fields = apply_filters('wdm_email_form_fields', $wdm_fields, $get_email_template_details, $email_tab);

			?>
            <form id="wdm-email-form" class="auction_settings_section_style" method="post" action="">
            <?php echo "<h3>" . $email_tab['label'] . "</h3>";?>
                <table class="form-table">
                <?php
foreach ($wdm_fields as $wdm_field => $wdm_data) {
				if (isset($wdm_data['type']) && in_array($wdm_data['type'], array("text", "number", "checkbox"))) {
					wdm_ua_get_text_field($wdm_field, $wdm_data['title'], $wdm_data['description'], $wdm_data['id'], $wdm_data['type'], $wdm_data['css'], $wdm_data['default'], $wdm_data['placeholder'], $wdm_data['value']);
				} else if (isset($wdm_data['type']) && $wdm_data['type'] == "radio") {
					wdm_ua_get_radio_field($wdm_field, $wdm_data['title'], $wdm_data['description'], $wdm_data['id'], $wdm_data['type'], $wdm_data['css'], $wdm_data['default'], $wdm_data['options'], $wdm_data['value']);
				} else if (isset($wdm_data['type']) && $wdm_data['type'] == "wp_editor") {
					wdm_ua_get_wp_editor_field($wdm_data['title'], $wdm_data['description'], $wdm_data['value'], $wdm_data['id'], $wdm_field);
				}
			}?>
		</table>
            <?php
echo '<input type = "hidden" name="' . $email_tab['slug'] . '[wdm_ua_email_hidden_field]">';
			wp_nonce_field('wdm_ua_email_form_nonce', 'wdm_ua_email_form_nonce_field');
			submit_button(__('Save Changes', 'wdm-ultimate-auction'));?>
            </form>
            <script type="text/javascript">
            jQuery(document).ready(function($){
            	//$('.wp-editor-tabs').remove();
            });
            </script>
            <?php
}
	}
}

function wdm_ua_get_text_field($wdm_field, $title, $desc, $id, $type, $css, $default, $placeholder, $value) {
	?>
    <tr valign="top">
        <th scope="row"><?php echo $title;?></th>
        <td>
            <input class="input-text regular-input <?php echo esc_attr($class);?>" type="<?php echo esc_attr($type);?>" name="<?php echo esc_attr($wdm_field);?>" id="<?php echo esc_attr($id);?>" style="<?php echo esc_attr($css);?>" value="<?php echo $value;?>" <?php echo (($value == "yes") ? 'checked="checked"' : "");?> placeholder="<?php echo esc_attr($placeholder);?>" />
            <?php echo (isset($desc) ? (($type != "checkbox") ? "<br>" : "") . $desc : "");?>
        </td>
    </tr>
    <?php
}

function wdm_ua_get_radio_field($wdm_field, $title, $desc, $id, $type, $css, $default, $options, $set_val) {
	?>
    <tr valign="top">
        <th scope="row"><?php echo $title;?></th>
        <td>
            <?php
$default = (isset($set_val) ? $set_val : $default);
	$i_count = 0;
	foreach ($options as $value => $display_name) {
		?>
            <input class="input-text regular-input <?php echo esc_attr($class);?>" type="<?php echo esc_attr($type);?>" name="<?php echo esc_attr($wdm_field);?>" id="<?php echo esc_attr($id);?>" style="<?php echo esc_attr($css);?>" value="<?php echo $value;?>" <?php echo (($default == $value) ? 'checked="checked"' : "");?> placeholder="<?php echo esc_attr($placeholder);?>" /> <?php echo $display_name;?>
            <?php echo ($i_count == 0 ? "<br>" : "");
		$i_count++;
	}?>
            <?php echo (isset($desc) ? "<br>" . $desc : "");?>
        </td>
    </tr>
    <?php
}

function wdm_ua_get_wp_editor_field($title, $desc, $content, $id, $name) {
	?>
    <tr valign="top">
        <th scope="row"><?php echo $title;?></th>
        <td>
            <?php
wp_editor($content, $id, array('media_buttons' => false, 'textarea_name' => $name, 'textarea_rows' => 10));
	$desc = str_replace('<', '&lt;', $desc);
	$desc = str_replace('>', '&gt;', $desc);
	$desc = str_replace('\n', '</br>', $desc);
	echo '<div class="wdm-ua-email-body-hint">' . $desc . '</div>';
	?>
        </td>
    </tr>
    <?php
}
