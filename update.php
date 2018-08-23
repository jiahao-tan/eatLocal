<?php
	//if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
	if(session_id() == '' || !isset($_SESSION)){session_start();}

	include 'config.php';

	$fname = $_POST["fname"];
	$lname = $_POST["lname"];
	$address = $_POST["address"];
	$city = $_POST["city"];
	$pin = $_POST["pin"];
	$email = $_POST["email"];
	$opwd = $_POST["opwd"];
	$pwd = $_POST["pwd"];


	if($fname!=""){
	  $result = $mysqli->query('UPDATE users SET fname ="'. $fname .'" WHERE user_id ="'.$_SESSION['user_id'].'"');
	  if(!$result){
	  	die($mysqli->error);
	  }
	}

	if($lname!=""){
	  $result = $mysqli->query('UPDATE users SET lname ="'. $lname .'" WHERE user_id ="'.$_SESSION['user_id'].'"');
	  if(!$result){
	  	die($mysqli->error);
	  }
	}

	if($address!=""){
	  $result = $mysqli->query('UPDATE users SET address ="'. $address .'" WHERE user_id ="'.$_SESSION['user_id'].'"');
	  if(!$result){
	  	die($mysqli->error);
	  }
	}

	if($city!=""){
	  $result = $mysqli->query('UPDATE users SET city ="'. $city .'" WHERE user_id ="'.$_SESSION['user_id'].'"');
	  if(!$result){
	  	die($mysqli->error);
	  }
	}

	if($pin!=""){
	  $result = $mysqli->query('UPDATE users SET pin ='. $pin .' WHERE user_id ="'.$_SESSION['user_id'].'"');
	  if(!$result){
	  	die($mysqli->error);
	  }
	}

	if($email!=""){
	  $result = $mysqli->query('UPDATE users SET email ="'. $email .'" WHERE user_id ="'.$_SESSION['user_id'].'"');
	  if(!$result) {
	  	die($mysqli->error);
	  }
	}

	//$result = $mysqli->query('Select password from users WHERE id ='.$_SESSION['id']);

	//$obj = $result->fetch_object();

	if(/*$opwd === $obj->password &&*/ $pwd!=""){
	  $query = $mysqli->query('UPDATE users SET password ="'. $pwd .'" WHERE user_id ="'.$_SESSION['user_id'].'"');
	  if(!$query){
	  	die($mysqli->error);
	  }
	}

	//else {
	//  echo 'Wrong Password. Please try again. <a href="account.php">Go Back</a>';
	//}


	echo "About to check for updated image...<br/>\n";
	if($_FILES["image"]["tmp_name"] != "" && $_FILES["image"]["size"] > 0)
	{
		echo "Processing updated image...<br/>\n";
		// process uploaded image
		if($_FILES["image"]["size"] > 131072)
		{
			echo "Image is too large. Please limit images to 128 KiB.";
			header("Refresh: 3; url=index.php?page=account");
			exit;
		}

		if(!move_uploaded_file($_FILES["image"]["tmp_name"], "user_images/".$user_id))
		{
			echo "Could not upload image. Please use <a href=\"index.php?page=account\">My Account</a> page to try again.";
			header("Refresh: 3; url=index.php?page=account");
			exit;
		}
		else
			echo "Image updated...<br/>\n";
	}

	//header("location:index.php?page=success");
	header("Refresh: 3; url=index.php?page=account");
?>
