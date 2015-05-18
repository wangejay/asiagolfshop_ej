<?php
/**
 * Include this file in your application. This file sets up the required classloader based on
 * whether you used composer or the custom installer.
 */

// Let the SDK know where the config file resides.
define('PP_CONFIG_PATH', dirname(__FILE__));

require 'PPAutoloader.php';
PPAutoloader::register();