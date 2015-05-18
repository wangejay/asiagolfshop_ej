<?php 
class Configuration
{
	// For a full list of configuration parameters refer in wiki page (https://github.com/paypal/sdk-core-php/wiki/Configuring-the-SDK)
	public static function getConfig()
	{
		$pay_mode = get_option('wdm_account_mode');
		
		if($pay_mode == 'Sandbox'){
			$mode = "sandbox";
		}
		else{
			$mode = "live";
		}

		$config = array(
				// values: 'sandbox' for testing
				//		   'live' for production
				"mode" => $mode

				// These values are defaulted in SDK. If you want to override default values, uncomment it and add your value.
				// "http.ConnectionTimeOut" => "5000",
				// "http.Retry" => "2",
			);
		return $config;
	}

	// Creates a configuration array containing credentials and other required configuration parameters.
	public static function getAcctAndConfig()
	{
		$app_id = get_option('ua_paypal_api_app_id');
		
		if($app_id == 'sandbox'){
			$app_id = "APP-80W284485P519543T";
		}
		
		$config = array(
				// Signature Credential
				"acct1.UserName" => get_option('ua_paypal_api_username'),
				"acct1.Password" => get_option('ua_paypal_api_password'),
				"acct1.Signature" => get_option('ua_paypal_api_signature'),
				"acct1.AppId" => $app_id

				// Sample Certificate Credential
				// "acct1.UserName" => "certuser_biz_api1.paypal.com",
				// "acct1.Password" => "D6JNKKULHN3G5B8A",
				// Certificate path relative to config folder or absolute path in file system
				// "acct1.CertPath" => "cert_key.pem",
				// "acct1.AppId" => "APP-80W284485P519543T"
				);

		return array_merge($config, self::getConfig());;
	}

}