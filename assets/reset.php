<?php

	include('../../../config/glancrConfig.php');

	$language = getConfigValue('language');

	putenv("LANG=$language");
	setlocale(LC_ALL, "$language.UTF-8");
	bindtextdomain('netatmo', GLANCR_ROOT ."/modules/netatmo/locale");
	textdomain('netatmo');
	bind_textdomain_codeset('netatmo', 'UTF-8');

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<title><?php echo _('module overview');?></title>
	<link rel="stylesheet" type="text/css" href="/config/css/main.css">
	<link rel="stylesheet" href="/config/bower_components/foundation-icon-fonts/foundation-icons.css" media="screen" title="no title" charset="utf-8">
	<script type="text/javascript" src="/config/bower_components/jquery/dist/jquery.min.js"></script>
</head>

<body>
	<?php include "../../../includes/navigation.php"; ?>

	<main class="container">
		<section>
			<div class="row">
				<div class="small-12 columns">

					<h3><?php echo _("netatmo_title"); ?></h3>

					<p>
						<?php echo _("netatmo_reset_config_message"); ?>
					</p><br /><br />

					<a class="button expanded" href="/config/">No</a>
					<a class="button expanded" href="resetConfig.php">Yes</a>

				</div>
			</div>
		</section>
	</main>
</body>
</html>
