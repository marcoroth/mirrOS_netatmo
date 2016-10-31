<?php

	$email = getConfigValue('netatmo_email');
	$client_id = getConfigValue('netatmo_client_id');
	$client_secret = getConfigValue('netatmo_client_secret');
	$access_token = getConfigValue('netatmo_access_token');
	$refresh_token = getConfigValue('netatmo_refresh_token');
	$config = getConfigValue('netatmo_config');
	
	
	if (empty($config)){
		setConfigValue("netatmo_config", '{"id":"","modules":[],"icons":{} }');
	}

	if (empty($client_id) || empty($client_secret) || empty($refresh_token) || empty($access_token)) {

		if (empty($email)) { $email = ""; }
		if (empty($client_id)) { $client_id = ""; }
		if (empty($client_secret)) { $client_secret = ""; }
		if (empty($access_token)) { $access_token = ""; }
		if (empty($refresh_token)) { $refresh_token = ""; }

		include "_step1.php";

	} else {
		include "_step2.php";
	}

?>
