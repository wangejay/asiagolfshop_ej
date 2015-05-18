<?php
$app_id = get_option('ua_paypal_api_app_id'); //To use the live site instead, request your own APP ID from PayPal

// For sandbox credentials, sign up at developer.paypal.com
$api_username = get_option('ua_paypal_api_username');

$api_password = get_option('ua_paypal_api_password');

$api_signature = get_option('ua_paypal_api_signature');

// Also put your sandbox emails here for convenience
$sandbox_seller_email = get_option('wdm_paypal_address');
?>
