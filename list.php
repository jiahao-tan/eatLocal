<?php
    //if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
    if(session_id() == '' || !isset($_SESSION)){session_start();}

	include 'config.php';

	if(!isset($_SESSION["user_id"]))
	{
		echo "<span class=\"error_msg\">You must be logged on to add a product.</span>\n";
		header("Refresh: 3; url=index.php");
	}
	else
		$user_id = $_SESSION["user_id"];

	$title = $_POST["title"];
	$category = $_POST["category"];
	$desc = $_POST["desc"];
	$price = $_POST["price"];
	$unit = $_POST["unit"];
	$qty = $_POST["qty"];

	if($title == "" || $category == "" || $desc == "" || $price == "" || $unit == "" || $qty == "")
	{
		echo "<span class=\"error_msg\">All fields must be specified.</span>\n";
		exit;
	}

	if(!($stmt = $mysqli->prepare("INSERT INTO products (seller_user_id, product_name, product_desc, qty, price, category, unit) VALUES (?, ?, ?, ?, ?, ?, ?)")))
		die($mysqli->error);

	if(!$stmt->bind_param("sssiiss", $user_id, $title, $desc, $qty, $price, $category, $unit))
		die($mysqli->error);

	if(!$stmt->execute())
		die($mysqli->error);

	$product_id = $mysqli->insert_id;

//print_r( $_FILES);

	if($_FILES["image"]["tmp_name"] != "" && $_FILES["image"]["size"] > 0)
	{
		// process uploaded image
		if($_FILES["image"]["size"] > 131072)
		{
			echo "Image is too large. Please limit images to 128 KiB.";
			exit;
		}

		// attempt to insert image id
		if(!($stmt = $mysqli->prepare("INSERT INTO product_images (product_id) VALUES (?)")))
			die($mysqli->error);
		if(!$stmt->bind_param("i", $product_id))
			die($mysqli->error);
		if(!$stmt->bind_execute())
			die($mysqli->error);
		
		$image_id = $mysqli->insert_id;

		if(!move_uploaded_file($_FILES["image"]["tmp_name"], "product_images/".$image_id))
		{
			echo "<span class=\"error_msg\">Failed to add image. Please wait for re-direct and try again via individual product page.</span>\n";
			header("Refresh: 3; url=index.php?page=individual_product&product_id=$product_id");
		}

		$stmt->close();
	}
	header("Refresh: 0; url=index.php?page=individual_product&product_id=$product_id");
?>
