<?php
	include('../../../config/glancrConfig.php');

	$email = getConfigValue('email');
	if($email == 'GLANCR_DEFAULT') {	$email = ''; }
	echo json_encode($email);

?>
