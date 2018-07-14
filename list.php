<?php

include 'config.php';

$title = $_POST["title"];
$catogory = $_POST["catogory"];
$desc = $_POST["desc"];
$price = $_POST["price"];
$unit = $_POST["unit"];
$image = $_POST["image"];

if($mysqli->query("INSERT INTO products (product_code, product_name, product_desc, 
product_img_name, qty, price, category) 
VALUES('$image$price', '$title', '$desc', '$image', $unit, '$price', '$catogory')")){
	echo 'Data inserted';
	echo '<br/>';
}
header ("location:index.php?page=browse");
?>
