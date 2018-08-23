<?php
	$currency = '$';
	$db_username = 'jc451631';
	$db_password = 'Password1';
	$db_name = 'jc451631_ecommerce';
	$db_host = 'localhost';
	$mysqli = new mysqli($db_host, $db_username, $db_password, $db_name);
	if($mysqli->connect_errno)
       	{
		die("(".$mysqli->connect_errno.") ".$mysqli->connect_error);
	}
?>
