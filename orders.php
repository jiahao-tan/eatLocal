<?php

//if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
if(session_id() == '' || !isset($_SESSION)){session_start();}

if(!isset($_SESSION["username"])){
	echo "<center><b>You must be logged on to view current and previous orders.</b></center>";
	exit;
}

include 'config.php';
?>

<center> <h3>Orders</h3> </center>
<div class="container">
	<?php
		$user_id = $_SESSION["user_id"];
		$stmt = $mysqli->prepare("SELECT * FROM orders WHERE buyer_user_id = ?");
		$stmt->bind_param("s", $user_id);
		$stmt->execute();
		$result = $stmt->get_result();
		while($obj = $result->fetch_object()) {
			echo "<div style=\"padding-bottom: 1em\" class=\"row\">\n";
			echo "<table class=\"table\">\n";
			echo "<tbody>\n";
			echo "<tr>\n";
			echo "<th scope=\"row\">Order #</th>\n";
			echo "<td>".$obj->id."</td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "<th scope=\"row\">Status</th>\n";
			echo "<td>".$obj->status."&nbsp;";
			if($obj->status == "completed")
				echo "&check;";
			else if($obj->status == "canceled")
				echo "&cross;";
			else if($obj->status == "pending")
			{
				echo "?";
				echo "<form style=\"display: inline\" action=\"index.php\" method=\"GET\">\n";
				echo "<input type=\"hidden\" name=\"page\" value=\"paypal_status\"/>\n";
				echo "<input type=\"hidden\" name=\"status\" value=\"failure\"/>\n";
				echo "<input type=\"submit\" value=\"Cancel order ".$obj->id."\"/>\n";
				echo "</form>\n";
			}
			echo "</td>\n";
			echo "</tr>\n";
			echo "<tr>\n";
			echo "<th scope=\"row\">Last update</th>\n";
			echo "<td>".$obj->date_time."</td>\n";
			echo "</tr>\n";
			echo "</tbody>\n";
			echo "</table>\n";

			// list items from order
			$stmt = $mysqli->prepare("SELECT p.product_code as product_code, p.product_name as product_name, p.unit as unit, o.qty as qty, p.price as price FROM order_items o, products p WHERE o.product_id = p.id AND o.order_id = ?");
			$stmt->bind_param("i", $obj->id);
			$stmt->execute();
			$result2 = $stmt->get_result();
			$num_items = 0;
			while($obj2 = $result2->fetch_object())
			{
				if($num_items == 0)
				{
					echo "<table class=\"table\">\n";
					echo "<thead>\n";
					echo "<tr>\n";
					echo "<th scope=\"col\">Product code</th>\n";
					echo "<th scope=\"col\">Product name</th>\n";
					echo "<th scope=\"col\">Quantity</th>\n";
					echo "<th scope=\"col\">Unit</th>\n";
					echo "<th scope=\"col\">Price per unit</th>\n";
					echo "<th scope=\"col\">Total</th>\n";
					echo "</tr>\n";
					echo "</thead>\n";
					echo "<tbody>\n";
				}
				$num_items++;
				echo "<tr>\n";
				echo "<td>".$obj2->product_code."</td>\n";
				echo "<td>".$obj2->product_name."</td>\n";
				echo "<td>".$obj2->qty."</td>\n";
				echo "<td>".$obj2->unit."</td>\n";
				echo "<td>$".$obj2->price."</td>\n";
				echo "<td>$".$obj2->qty * $obj2->price."</td>\n";
				echo "</tr>\n";
			}
			if($num_items == 0)
				echo "No items in this order.";
			else
			{
				echo "</tbody>\n";
				echo "</table>\n";
			}

			if($obj->status == "pending") {
				echo "<form action=\"index.php\" method=\"GET\">\n";
				echo "<input type=\"hidden\" name=\"page\" value=\"paypal_status\"/>\n";
				echo "<input type=\"hidden\" name=\"status\" value=\"failure\"/>\n";
				echo "<input type=\"submit\" value=\"Cancel order ".$obj->id."\"/>\n";
				echo "</form><br/>\n";
			}

		  // echo '<p><h4>Order ID ->'.$obj->id.'</h4></p>';
		  // echo '<p><strong>Date of Purchase</strong>: '.$obj->date_time.'</p>';
		  // echo '<p><strong>Product Code</strong>: '.$obj->product_code.'</p>';
		  // echo '<p><strong>Product Name</strong>: '.$obj->product_name.'</p>';
		  // echo '<p><strong>Price Per Unit</strong>: '.$obj->price.'</p>';
		  // echo '<p><strong>Units Bought</strong>: '.$obj->units.'</p>';
		  // echo '<p><strong>Total Cost</strong>: '.$currency.$obj->total.'</p>';
		  //echo '</div>';
		  //echo '<div class="large-6">';
		  //echo '<img src="images/products/sports_band.jpg">';
		  //echo '</div>';
		  echo "</div>\n";
		}
	?>
</div>
