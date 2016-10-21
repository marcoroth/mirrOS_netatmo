<?php

	include ('../../../config/glancrConfig.php');

	$url = "https://api.netatmo.com/oauth2/token";
	$data = array(
		'client_id' => getConfigValue('netatmo_client_id'),
		'client_secret' => getConfigValue('netatmo_client_secret'),
		'grant_type' => 'refresh_token',
		'refresh_token' => getConfigValue('netatmo_refresh_token')
	);

	$options = array(
	    'http' => array(
	        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
	        'method'  => 'POST',
	        'content' => http_build_query($data)
	    )
	);

	$context  = stream_context_create($options);
	$result = file_get_contents($url, false, $context);

	$json = json_decode($result);
	$access_token = $json->{'access_token'};

	setConfigValue("netatmo_access_token", $access_token);
	setConfigValue("netatmo_new_token_requested", "true");

	echo json_encode(array('success' => 'true'));

?>
