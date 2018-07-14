<?php
$currency = '$';
$db_username = 'jcubitgr_icteat';
$db_password = '123zxc';
$db_name = 'jcubitgr_eat';
$db_host = 'box648.bluehost.com';
$mysqli = new mysqli($db_host, $db_username, $db_password,$db_name);
?>

try {
	$conn = new PDO("mysql:host=$eat.jcubitgroup.com;dbname=jcubitgr_eat" $eat@jcubitgroup.com, $123zxcA!);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	echo "Connected successfully";
	}
catch(PDOException $e)
	{
	echo "Connection failed: " . $e->getMessage();
	}
?>
