<?php

	$email = getConfigValue('netatmo_email');
	$client_id = getConfigValue('netatmo_client_id');
	$client_secret = getConfigValue('netatmo_client_secret');
	$access_token = getConfigValue('netatmo_access_token');
	$refresh_token = getConfigValue('netatmo_refresh_token');

	if ($client_id == "GLANCR_DEFAULT" || $client_secret == "GLANCR_DEFAULT" || $refresh_token == "GLANCR_DEFAULT" || $access_token == "GLANCR_DEFAULT") {

		if ($email == "GLANCR_DEFAULT") { $email = ""; }
		if ($client_id == "GLANCR_DEFAULT") { $client_id = ""; }
		if ($client_secret == "GLANCR_DEFAULT") { $client_secret = ""; }
		if ($access_token == "GLANCR_DEFAULT") { $access_token = ""; }
		if ($refresh_token == "GLANCR_DEFAULT") { $refresh_token = ""; }

		include "_step1.php";

	} else {
		include "_step2.php";
	}

?>
