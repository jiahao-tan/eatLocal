<?php

include 'config.php';

$title = $_POST["title"];
$category = $_POST["category"];
$desc = $_POST["desc"];
$price = $_POST["price"];
$unit = $_POST["unit"];
//$image = $_POST["image"];

echo 'Price = '.$price;

if($mysqli->query("INSERT INTO products (product_name, product_desc, price, category, unit)
VALUES('$title', '$desc', '$price', '$category', '$unit')")){
	echo 'Data inserted';
	echo '<br/>';
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

	if(!move_uploaded_file($_FILES["image"]["tmp_name"], "product_images/".$image_id))
	{
		echo "Could not upload image. Please use <a href=\"index.php?page=account\">My Account</a> page to try again.";
		exit;
	}
}

#header ("location:index.php?page=browse");
?>
