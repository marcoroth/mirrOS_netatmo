<?php

	include('../../../config/glancrConfig.php');

	setConfigValue("netatmo_client_id", "");
	setConfigValue("netatmo_client_secret", "");
	setConfigValue("netatmo_access_token", "");
	setConfigValue("netatmo_refresh_token", "");
	setConfigValue("netatmo_email", "");
	setConfigValue("netatmo_station", "");
	setConfigValue("netatmo_new_token_requested", "false");
	setConfigValue("netatmo_config", '{"id":"","modules":[],"icons":{} }');
	setConfigValue("reload", "1");

	header("location: /config/");

?>
