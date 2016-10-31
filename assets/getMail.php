<?php
	include('../../../config/glancrConfig.php');

	$email = getConfigValue('email');
	if(empty($email)) {	$email = ''; }
	echo json_encode($email);

?>
