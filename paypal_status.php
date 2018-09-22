<?php
	if(!isset($_GET["status"]))
	{
		echo "<center><b>No status specified.</b></center>\n";
		exit;
	}
	
	$status = $_GET["status"];
	if($status != "success" && $status != "failure")
	{
		echo "<center><b>Invalid status specified.</b></center>\n";
		exit;
	}

	if($status == "success")
		echo "<center><b>PayPal transaction completed successfully.</b></center>";
	else
		echo "<center><b>PayPal transaction did not complete!</b></center>";

	if(isset($_SESSION["user_id"]))
	{
		include "config.php";
		if($status == "success")
			$stmt = $mysqli->prepare("UPDATE orders SET status = 'completed' WHERE buyer_user_id = ? AND status = 'pending'");
		else
			$stmt = $mysqli->prepare("UPDATE orders SET status = 'canceled' WHERE buyer_user_id = ? AND status = 'pending'");
		$stmt->bind_param("s", $user_id);
		if(!$stmt->execute())
			die($mysqli->error);
	}
?>
 
