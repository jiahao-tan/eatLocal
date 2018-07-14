<?php

//if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
if (session_id() == '' || !isset($_SESSION)) {session_start();}

if ($_SESSION["type"] != "admin") {
    header("location:index.php");
}

include 'config.php';

$_SESSION["products_qty"] = array();
$_SESSION["products_qty"] = $_REQUEST['quantity'];

$_SESSION["products_id"] = array();
$_SESSION["products_id"] = $_REQUEST['id'];

while (list($key, $val) = each($_SESSION["products_qty"])) {
    if (!empty($_SESSION["products_qty"][$key])) {
        $update = $mysqli->query("UPDATE products SET qty =" . $_SESSION["products_qty"][$key] . " WHERE id =" . $_SESSION["products_id"][$key]);
        if ($update) {
            echo 'Data Updated';
        }
    }
}

header("location:index.php?page=success");
