<?php

	include('../../../config/glancrConfig.php');

	setConfigValue("netatmo_client_id", "GLANCR_DEFAULT");
	setConfigValue("netatmo_client_secret", "GLANCR_DEFAULT");
	setConfigValue("netatmo_access_token", "GLANCR_DEFAULT");
	setConfigValue("netatmo_refresh_token", "GLANCR_DEFAULT");
	setConfigValue("netatmo_email", "GLANCR_DEFAULT");
	setConfigValue("netatmo_station", "GLANCR_DEFAULT");
	setConfigValue("netatmo_new_token_requested", "false");
	setConfigValue("netatmo_config", '{"id":"","modules":[],"icons":{} }');

	header("location: /config/");

?>
