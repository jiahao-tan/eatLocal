<?php

//if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
if(session_id() == '' || !isset($_SESSION)){session_start();}

if(!isset($_SESSION["username"])){
	echo "<center><b>You must be logged on to view your sales history.</b></center>";
	exit;
}

include 'config.php';
?>


<center> <h3>Sales History</h3> </center>
<div class="container">
	<?php
		$timeperiod = 3;
		if(isset($_GET["timeperiod"]))
			$timeperiod = $_GET["timeperiod"];
		echo "<div class=\"row justify-content-center\">\n";
		echo "Sales history over last ".$timeperiod." month(s)";
		echo "</div>\n";
		echo "<div class=\"row\">\n";
		$user_id = $_SESSION["user_id"];
		$stmt = $mysqli->prepare("SELECT product_name, COUNT(distinct order_id) AS num_sales, COUNT(distinct buyer_user_id) AS num_cust, SUM(order_items.qty) AS quantity, price
	FROM orders, order_items, products
	WHERE orders.id = order_items.order_id
		AND order_items.product_id = products.id
		AND orders.status = 'completed'
		AND products.seller_user_id = ?
		AND orders.date_time >= curdate() - interval ? month
	GROUP BY product_id");
		if(!$stmt)
			die($mysqli->error);
		$stmt->bind_param("si", $user_id, $timeperiod);
		$stmt->execute();
		$result = $stmt->get_result();
		$num_items = 0;
		$total = 0;
		while($obj = $result->fetch_object())
		{
			if($num_items == 0)
			{
				echo "<table class=\"table\">\n";
				echo "<thead>\n";
				echo "<tr>\n";
				echo "<th scope=\"col\">Product</th>\n";
				echo "<th scope=\"col\">Num. sales</th>\n";
				echo "<th scope=\"col\">Total quantity sold</th>\n";
				echo "<th scope=\"col\">Num. customers</th>\n";
				echo "<th scope=\"col\">Total</th>\n";
				echo "</tr>\n";
				echo "</thead>\n";
				echo "<tbody>\n";
			}
			$num_items++;
			echo "<tr>\n";
			echo "<td>".$obj->product_name."</td>\n";
			echo "<td>".$obj->num_sales."</td>\n";
			echo "<td>".$obj->quantity."</td>\n";
			echo "<td>".$obj->num_cust."</td>\n";
			echo "<td>$".$obj->quantity * $obj->price."</td>\n";
			$total += $obj->quantity * $obj->price;
			echo "</tr>\n";
		}
		if($num_items == 0)
			echo "No sales during selected time period.";
		else
		{
			echo "</tbody>\n";
			echo "</table>\n";
			echo "</div>\n";
			echo "<div class=\"row justify-content-center\">\n";
			echo "Total amount made: $".$total."\n";
			echo "</div>\n";
		}
	?>
	<div class="row">
		<form action="index.php">
			<input type="hidden" name="page" value="sales_history"/>
			<input type="hidden" name="timeperiod" value="1"/>
			<input type="Submit" value="Last month"/>
		</form>
		<form action="index.php">
			<input type="hidden" name="page" value="sales_history"/>
			<input type="hidden" name="timeperiod" value="3"/>
			<input type="Submit" value="Last quarter"/>
		</form>
		<form action="index.php">
			<input type="hidden" name="page" value="sales_history"/>
			<input type="hidden" name="timeperiod" value="12"/>
			<input type="Submit" value="Last year"/>
		</form>
	</div>
</div>
