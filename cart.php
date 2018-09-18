<?php
if (session_id() == '' || !isset($_SESSION)) {session_start();}
include 'config.php';
?>
<div class="bg">
	<section id="registerform" class="outer-wrapper">
		<div class="inner-wrapper v-top">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-1"></div>
					<div class="col-md-10">
					<?php

echo '<center><p><h1>Your Shopping Cart</h1></p></center>';

	$stmt = $mysqli->prepare("SELECT * FROM orders WHERE buyer_user_id = ? AND status = 'pending'");
	$stmt->bind_param("s", $_SESSION["user_id"]);
	$stmt->execute();
	$result = $stmt->get_result();
	while($obj = $result->fetch_object()) {
		echo "<center><b>Cart is locked because a checkout operation is in progress.</b></center>";
		break;
	}

if (isset($_SESSION['cart'])) {

    $total = 0;
    echo '<table>';
    echo '<tr>';
    echo '<th>Code</th>';
    echo '<th>Name</th>';
    echo '<th>Quantity</th>';
    echo '<th>Cost</th>';
    echo '</tr>';
    foreach ($_SESSION['cart'] as $product_id => $quantity) {

        $result = $mysqli->query("SELECT * FROM products WHERE id = " . $product_id);

        if ($result) {

            while ($obj = $result->fetch_object()) {
                $cost = $obj->price * $quantity; //work out the line cost
                $total = $total + $cost; //add to the total cost

                echo '<tr>';
                echo '<td>' . $obj->product_code . '</td>';
                echo '<td>' . $obj->product_name . '</td>';
                echo '<td>' . $quantity . ' '.$obj->unit.'&nbsp;<a class="button [secondary success alert]" style="padding:5px;" href="update-cart.php?action=add&id=' . $product_id . '">+</a>&nbsp;<a class="button alert" style="padding:5px;" href="update-cart.php?action=remove&id=' . $product_id . '">-</a></td>';
                echo '<td>$' . $cost . '</td>';
                echo '</tr>';
            }
        }

    }

    echo '<tr>';
    echo '<td colspan="3" align="right">Total</td>';
    echo '<td>$' . $total . '</td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td colspan="4" align="right"><a href="update-cart.php?action=empty" class="btn btn-primary">Empty Cart</a>&nbsp;<a href="index.php?page=browse" class="btn btn-primary">Continue Shopping</a>';
    if (isset($_SESSION['username'])) {
        echo '<a href="orders-update.php"><button style="float:right;" class="btn btn-primary">Checkout with PayPal</button></a>';
    } else {
        echo '<a href="login.php"><button style="float:right;" class="btn btn-primary">Login</button></a>';
    }

    echo '</td>';

    echo '</tr>';
    echo '</table>';
} else {
    echo "<center><h2>You have no items in your shopping cart.</h2></center>";
}
?>

					</div>
					<div class="col-md-1"></div>
				</div>
			</div>
		</div>
	</section>
</div>
