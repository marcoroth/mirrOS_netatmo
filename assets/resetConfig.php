<?php

	include('../../../config/glancrConfig.php');

	setConfigValue("netatmo_client_id", "GLANCR_DEFAULT");
	setConfigValue("netatmo_client_secret", "GLANCR_DEFAULT");
	setConfigValue("netatmo_access_token", "GLANCR_DEFAULT");
	setConfigValue("netatmo_refresh_token", "GLANCR_DEFAULT");
	setConfigValue("netatmo_email", "GLANCR_DEFAULT");
	setConfigValue("netatmo_station", "GLANCR_DEFAULT");
	setConfigValue("netatmo_new_token_requested", "false");
	setConfigValue("netatmo_config", '{"id":"","modules":[],"icons":{},"columns":{"rain":["Rain","sum_rain_1","sum_rain_24"],"wind":["GustAngle","GustStrength","WindAngle","WindStrength"],"base":["CO2","Humidity","Noise","Temperature","Humidity","Pressure","AbsolutePressure"],"outdoor":["Humidity","Temperature","temp_trend"],"indoor":["...","...","..."]}}');

	header("location: /config/");

?>
