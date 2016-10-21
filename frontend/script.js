var data;
var params;
var outside_url;
var netatmo_url;
var new_token_requested;
var modules_sorted = new Array();

$(document).ready(function() {

	$.get("/modules/netatmo/assets/getAccessToken.php").done(function(access_token){
		url = "https://api.netatmo.com/api/getstationsdata";
		netatmo_url = url + "?" + "access_token" + "=" + access_token + "&get_favorites=true";

		$.get("/modules/netatmo/assets/newTokenRequested.php").done(function(body) {
			new_token_requested = body;
			netatmo();
		});
	});
});

function netatmo(){
	$.ajax({url: netatmo_url }).done(function( content ) {

			data = content;
			stations = content.body.devices;
			config = $.parseJSON('<?php echo getConfigValue('netatmo_config'); ?>');
			station = config["id"];
			base = null;

			// Selecting station from available stations (own + favorites) based on selected station
			$.each(stations, function(index, el) { if (el["_id"] == station){	base = el; }	});

			if (config.modules.length != 0){
				if (base == null) {
					$("#netatmo_table").empty();
					$("#netatmo_table").append("<?php echo _('netatmo_station_not_found'); ?>");
					$.each(stations, function(index, el) {
						$("#netatmo_table").append("- " + el["station_name"]+"  (" + el["_id"] + ")<br>");
					});
				} else {
					//$.post('setConfigValueAjax.php', {'key': 'netatmo_new_token_requested', 'value': 'false'});
					unix_last_update = base.dashboard_data.time_utc;
					unix_actual = Math.floor(Date.now() / 1000);
					unix_diff = unix_actual-unix_last_update;
					date = new Date(unix_last_update*1000);
					station_name = base.station_name;
					modules = base.modules;

					// adding base station to the modules array
					modules.push(base);
					minutes_ago = Math.ceil(unix_diff/60);
					language = "<?php echo _('language'); ?>";
					ago = "<?php echo _('netatmo_ago'); ?>";

					if (minutes_ago == 1) {	minutes = "<?php echo _('netatmo_minute'); ?>"; }
					else { minutes = "<?php echo _('netatmo_minutes'); ?>"; }

					if ( language == "de_DE" || language == "language") {minutes_ago_language = ago + " " + minutes_ago + " " + minutes; }
					else { minutes_ago_language =  minutes_ago + " " + minutes + " " + ago; }

					$("#netatmo_location").text(station_name + " - " + minutes_ago_language);
					$("#netatmo_table").empty();

					compass_points = [<?php echo _("netatmo_compass_points"); ?>];
					modules_sorted = new Array();

					// based on the module order from the configuration will the modules been sorted
					$.each(config["modules"], function(index, el) {
						$.each(modules, function(index2, el2) {
							if (el2._id == el.id) {
								modules_sorted.push(el2);
							}
						});
					});

					$.each(modules_sorted, function(index, el) {
						type = el.type;
						$("#netatmo_table").append("<tr></tr>");
						$("#netatmo_table tr:last").append("<td><img height='25px' src='/modules/netatmo/assets/icons/" + type + "/"+ config.icons[el._id] +"' /></td>");

						// Aussenmodul
						if (type == "NAModule1") {
							outdoor_pressure = base.dashboard_data.Pressure;
							outdoor_temperature = el.dashboard_data.Temperature;
							outdoor_humidity = el.dashboard_data.Humidity;
							temp_trend = el.dashboard_data.temp_trend;
							temp_trend_icons = ["↑", "↓", "↔"];
							co2 = el.dashboard_data.CO2;
							battery = el.battery_percent;
							battery_index = Math.ceil(battery/20);
							min_temperature = el.dashboard_data.min_temp;
							max_temperature = el.dashboard_data.max_temp;

							if (temp_trend == "up") {	temp_trend = 0; } else
							if (temp_trend == "down") { temp_trend = 1; } else
							if (temp_trend == "stable") { temp_trend = 2; }

							$("#netatmo_table tr:last").append("<td> Trend: " + temp_trend_icons[temp_trend] + "</td>");
							$("#netatmo_table tr:last").append("<td>" + outdoor_temperature + "°C</td>");
							$("#netatmo_table tr:last").append("<td>" + outdoor_humidity + "%</td>");
							$("#netatmo_table tr:last").append("<td colspan='2'>" + outdoor_pressure + " mbar</td>");
						}

						// Windmodul
						if (type == "NAModule2") {
							wind_strength = el.dashboard_data.WindStrength;
							wind_angle = Math.abs(el.dashboard_data.WindAngle);
							compass_point = compass_points[Math.ceil(wind_angle/22.5)-1];
							gust_strength = el.dashboard_data.GustStrength;
							battery = el.battery_percent;
							battery_index = Math.ceil(battery/20);

							angle = '<div style="height: 30px;"><svg id="windpath1" version="1.1" viewBox="0 0 28.35 28.35"><path d="M5.394,14.763c0,0.244,0.152,0.466,0.38,0.552l14.713,5.593c0.064,0.025,0.134,0.038,0.204,0.038	c0.19,0,0.376-0.094,0.483-0.244c0.152-0.21,0.144-0.518-0.02-0.717l-4.257-5.222l0.051-0.063l4.204-5.157c0.164-0.199,0.174-0.507,0.021-0.716c-0.11-0.153-0.289-0.245-0.478-0.245c-0.071,0-0.143,0.013-0.21,0.039L5.772,14.214C5.545,14.298,5.394,14.519,5.394,14.763z M7.704,14.74l11.146-4.236l-0.059,0.072l-3.11,3.816c-0.173,0.208-0.173,0.535-0.001,0.744l3.169,3.887l-0.087-0.033l-1.92-0.728l-9.199-3.499L7.704,14.74z" transform="rotate('+(wind_angle+90-180)+', 14.175, 14.175)"></path></svg></span>'
							$("#netatmo_table tr:last").append("<td>" + wind_strength + " km/h</td>");
							$("#netatmo_table tr:last").append("<td>" + gust_strength + " km/h</td>");
							$("#netatmo_table tr:last").append("<td>" + angle + "</td>");
							$("#netatmo_table tr:last").append("<td colspan='2'>" +" aus "+ compass_point + " " + wind_angle + "°</td>");
						}

						// Regenmodul
						if (type == "NAModule3") {
							last_hour = el.dashboard_data.sum_rain_1;
							today = el.dashboard_data.sum_rain_24;
							battery = el.battery_percent;
							battery_index = Math.ceil(battery/20);
							$("#netatmo_table tr:last").append("<td>"+last_hour+" mm</td>");
							$("#netatmo_table tr:last").append("<td colspan='4'>"+today+" mm</td>");
						}

						// Zusätzliches Innenmodul und Basisstation
						if (type == "NAModule4" || type == "NAMain") {
							pressure = el.dashboard_data.Pressure;
							co2 = el.dashboard_data.CO2;
							humidity = el.dashboard_data.Humidity;
							temperature = el.dashboard_data.Temperature;
							noise = el.dashboard_data.Noise;
							battery = el.battery_percent;
							battery_index = Math.ceil(battery/20);

							$("#netatmo_table tr:last").append("<td><div class='progress'><span style='width: " + ((100/1500)*co2) + "%'></span></div></td>");
							$("#netatmo_table tr:last").append("<td>"+temperature+"°C</td>");
							$("#netatmo_table tr:last").append("<td>"+humidity+"%</td>");
							$("#netatmo_table tr:last").append("<td>"+co2+" ppm</td>");

							if (type == "NAModule4") {
								$("#netatmo_table tr:last").append("<td></td>");
							} else {
								battery_index = 0;
								$("#netatmo_table tr:last").append("<td>"+noise+" dB</td>");
							}
						}

						if (!isNaN(battery_index)) {
							if (type == "NAMain") {
								$("#netatmo_table tr:last").append("<td><img height='20px' src='/modules/netatmo/assets/icons/batteries/stecker.svg' /></td>");
							} else {
								$("#netatmo_table tr:last").append("<td><img height='25px' src='/modules/netatmo/assets/icons/batteries/batterie" + battery_index + ".svg' /></td>");
							}
						}
					});

					if (modules_sorted.length == 7) {
						$("body").append("<style> #netatmo_table { margin-top: -12px; }</style>");
					}

					if (false){
						setNetatmoValuesInInfoModule();
					}
				}
			}

	}).fail(function(content){
		data = content;
		error = content.responseJSON["error"]["message"];

		$("#netatmo_table").empty();
		$("#netatmo_table").append("Error " + content.status + ": " + error);

		new_token_requested = (new_token_requested === "true");

		if (!new_token_requested) {
			$.get("/modules/netatmo/assets/getNewAccessToken.php");
			location.reload();
		} else {
			$("#netatmo_table").empty();
			$("#netatmo_table").append("Fehler: Es scheint, dass einige Einstellungen nicht korrekt sind. Bitte überprüfe deine Einstellungen.");
		}
	});

	window.setTimeout(function() {
		netatmo();
	}, 20000);
}

//////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////

function setNetatmoValuesInInfoModule() {
	// akt. Wetterzustand -> akt. Wetterzustand Netatmo
	if (typeof last_hour != 'undefined') {
		if (last_hour != 0){
			$(".infomodule__temperature svg").remove();
			$(".infomodule__temperature").prepend('<svg viewBox="25 25 60 60">' + svgTable[infoWeatherIcons]['10d'] + '</svg>');
		}
	}
	// akt. Temp -> akt. Temp Netatmo
	$(".infomodule__temperature span").text(outdoor_temperature + "°C");

	// min. Temp -> Luftfeutigkeit aussen Netatmo
	$(".infomodule__rainprob").text(outdoor_humidity+ "%");
	$("#umbrella").after("<img id='humidity' style='padding-top: 10px; margin-left: 10px' src='/modules/netatmo/assets/icons/NAModule3/luftfeuchtigkeit2.svg'>");
	$("#umbrella").remove();

	// min. Temp -> Luftdruck aussen Netatmo
	$(".infomodule__wind").text(outdoor_pressure + " mbar");
	$("#windpath").after("<img id='pressure' style='padding-top: 3px; margin-left: 10px' height='20px' src='/modules/netatmo/assets/icons/luftdruck.png'>");
	$("#windpath").remove();

	// min. Temp -> min. Temp Netatmo
	$(".infomodule__today--right span:first b").text(min_temperature + "°C");

	// max. Temp -> max. Temp Netatmo
	$(".infomodule__today--right span:last b").text(max_temperature + "°C");
}
