<?php

include 'config.php';

$title = $_POST["title"];
$category = $_POST["category"];
$desc = $_POST["desc"];
$price = $_POST["price"];
$unit = $_POST["unit"];
$image = $_POST["image"];

if($mysqli->query("INSERT INTO products (product_name, product_desc,
product_img_name, unit_price, category)
VALUES('$title', '$desc', '$image', '$price', '$category')")){
	echo 'Data inserted';
	echo '<br/>';
}
header ("location:index.php?page=browse");
?>
