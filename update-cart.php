<?php

if (session_id() == '' || !isset($_SESSION)) {session_start();}

include 'config.php';

$product_id = $_GET['id'];
$action = $_GET['action'];
if(!isset($_GET['qty']))
	$qty = 1;
else
	$qty = $_GET['qty'];

    $stmt = $mysqli->prepare("SELECT * FROM orders WHERE buyer_user_id = ? AND status = 'pending'");
    $stmt->bind_param("s", $_SESSION["user_id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    while($obj = $result->fetch_object()) {
        echo "<center><b>Cart is locked because a checkout operation is in progress.</b></center>";
		header("Refresh: 3; url=index.php?page=cart");
        exit;
    }

if ($action === 'empty') {
    unset($_SESSION['cart']);
	header("Refresh: 0; url=index.php?page=cart");
	exit;
}

$result = $mysqli->query("SELECT qty FROM products WHERE id = " . $product_id);

if ($result) {

    if ($obj = $result->fetch_object()) {

        switch ($action) {

            case "add":
				if(!isset($_SESSION['cart'][$product_id]))
					$_SESSION['cart'][$product_id] = 0;
                if ($_SESSION['cart'][$product_id] + $qty <= $obj->qty) {
                    $_SESSION['cart'][$product_id] += $qty;
                } else {
					echo "Insufficent stock left.";
					header("Refresh: 3; url=index.php?page=cart");
					exit;
				}

                break;

            case "remove":
                $_SESSION['cart'][$product_id] -= $qty;
                if ($_SESSION['cart'][$product_id] <= 0) {
                    unset($_SESSION['cart'][$product_id]);
                }

                break;

        }
    }
}

header("Refresh: 0; url=index.php?page=cart");
