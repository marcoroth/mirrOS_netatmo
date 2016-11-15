$(document).ready(function() {
	var url = "https://api.netatmo.com/oauth2/token";
	var grant_type = "password";
	var scope = "read_station read_thermostat write_thermostat";

	$(".netatmo_save_step1--button").click(function() {

		$.post('setConfigValueAjax.php', {'key': 'netatmo_new_token_requested', 'value': 'false'});

		client_id = $("#client_id").val();
		client_secret = $("#client_secret").val();
		username = $("#email").val();
		password = $("#password").val();

		$.post('setConfigValueAjax.php', {'key': 'netatmo_client_id', 'value': client_id}).fail(function(){ success = false });
		$.post('setConfigValueAjax.php', {'key': 'netatmo_client_secret', 'value': client_secret}).fail(function(){ success = false });
		$.post('setConfigValueAjax.php', {'key': 'netatmo_email', 'value': username}).fail(function(){ success = false });

		$.post(url, { grant_type: grant_type, scope: scope, client_id: client_id, client_secret: client_secret, username: username, password: password}).done(function(data){

			var success = true;

			$.post('setConfigValueAjax.php', {'key': 'netatmo_access_token', 'value': data["access_token"]}).fail(function(){ success = false });
			$.post('setConfigValueAjax.php', {'key': 'netatmo_refresh_token', 'value': data["refresh_token"]}).fail(function(){ success = false });

			if (success) {
				$('#ok').show(30, function() {
					$(this).hide('slow');
					location.reload();
				});
			}
		}).error(function(data) {
			$(".error").val(data.status + " - " + data.statusText + ": " + data.responseText);
			$(".error").show();
		});
	});

	var email_checked = '<?php echo $email; ?>';
	if (email_checked != ""){	$("#email_checked").click(); } else { $("#email").val(""); }

	$("#email_checked").change(function() {

		if (email_checked == true){
			email_checked = false;
			$("#email").val("");
			$("#email").attr("placeholder", "<?php echo _('netatmo_email');?>");
		} else if (email_checked == false) {
			email_checked = true;
			$("#email").attr("placeholder", "");
			$.getJSON("/modules/netatmo/assets/getMail.php").done(function(json){	$("#email").val(json); });
		}
	});
});
