<?php
	include 'config.php';

	if(
		!isset($_POST["user_id"]) ||
		!isset($_POST["fname"]) ||
		!isset($_POST["lname"]) ||
		!isset($_POST["email"]) ||
		!isset($_POST["pwd"])
	)
	{
		echo "User id, first name, last name, email, and password must be specified.";
		exit;
	}

	$user_id = $_POST["user_id"];
	$fname = $_POST["fname"];
	$lname = $_POST["lname"];
	$address = $_POST["address"];
	$suburb = $_POST["suburb"];
	$postcode = $_POST["postcode"];
	$email = $_POST["email"];
	$pwd = $_POST["pwd"];

	// Don't store password in plaintext, but instead create a secure hash of it.
	// The "password_hash" builtin includes the creation of a secure salt.
	$pwd = password_hash($pwd, PASSWORD_BCRYPT);

	if($stmt = $mysqli->prepare("INSERT INTO users (user_id, fname, lname, address, suburb, postcode, email, password) VALUES(?, ?, ?, ?, ?, ?, ?, ?)")) {
		$stmt->bind_param("ssssssss", $user_id, $fname, $lname, $address, $suburb, $postcode, $email, $pwd);
		if($stmt->execute()) {
			echo 'Data inserted';
			echo '<br/>';
		} else
		die(mysqli_error($mysqli));
	} else {
		die(mysqli_error($mysqli));
	}

	//print_r( $_FILES);

	if($_FILES["image"]["tmp_name"] != "" && $_FILES["image"]["size"] > 0)
	{
		// process uploaded image
		if($_FILES["image"]["size"] > 131072)
		{
			echo "Image is too large. Please limit images to 128 KiB.";
			exit;
		}

		if(!move_uploaded_file($_FILES["image"]["tmp_name"], "user_images/".$user_id))
		{
			echo "Could not upload image. Please use <a href=\"index.php?page=account\">My Account</a> page to try again.";
			exit;
		}
	}

	//header ("location:index.php?page=login");
    header("Refresh: 3; url=index.php?page=login");

?>
