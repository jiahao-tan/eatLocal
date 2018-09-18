<?php
//if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
if(session_id() == '' || !isset($_SESSION)){session_start();}

include 'config.php';

if(isset($_SESSION['cart'])) {

  $total = 0;

	// add pending order
	$stmt = $mysqli->prepare("INSERT INTO orders (buyer_user_id, status) VALUES(?, 'pending')");
	$stmt->bind_param("s", $_SESSION['user_id']);
	if(!$stmt->execute())
		die($mysqli->error);
	$order_id = $mysqli->insert_id;

  foreach($_SESSION['cart'] as $product_id => $quantity) {

    $result = $mysqli->query("SELECT * FROM products WHERE id = ".$product_id);

    if($result){

      if($obj = $result->fetch_object()) {


        $cost = $obj->price * $quantity;
		$total += $cost;

        $user = $_SESSION["username"];

        $stmt = $mysqli->prepare("INSERT INTO order_items (order_id, product_id, qty) VALUES(?, ?, ?)");
		$stmt->bind_param("iii", $order_id, $product_id, $quantity);
		if(!$stmt->execute())
			die($mysqli->error);

		// decrease item counts in products table
		$newqty = $obj->qty - $quantity;
		if(!$mysqli->query("UPDATE products SET qty = ".$newqty." WHERE id = ".$product_id))
		{
			die($mysqli->error);
		}

        //send mail script
        /*$query = $mysqli->query("SELECT * from orders order by date desc");
        if($query){
          while ($obj = $query->fetch_object()){
            $subject = "Your Order ID ".$obj->id;
            $message = "<html><body>";
            $message .= '<p><h4>Order ID ->'.$obj->id.'</h4></p>';
            $message .= '<p><strong>Date of Purchase</strong>: '.$obj->date.'</p>';
            $message .= '<p><strong>Product Code</strong>: '.$obj->product_code.'</p>';
            $message .= '<p><strong>Product Name</strong>: '.$obj->product_name.'</p>';
            $message .= '<p><strong>Price Per Unit</strong>: '.$obj->price.'</p>';
            $message .= '<p><strong>Units Bought</strong>: '.$obj->units.'</p>';
            $message .= '<p><strong>Total Cost</strong>: '.$obj->total.'</p>';
            $message .= "</body></html>";
            $headers = "From: support@techbarrack.com";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

            $sent = mail($user, $subject, $message, $headers) ;
            if($sent){
              $message = "";
            }
            else {
              echo 'Failure';
            }
          }
        }*/

		unset($_SESSION['cart']);
		echo "<center><b>Order processed: currently pending</b></center>\n";

		// pass order to Paypal for payment
		echo "<form action=\"payments.php\" method=\"post\" id=\"paypal_form\">\n";
		echo "<input type=\"hidden\" name=\"cmd\" value=\"_xclick\" />\n";
		echo "<input type=\"hidden\" name=\"no_note\" value=\"1\" />\n";
		echo "<input type=\"hidden\" name=\"lc\" value=\"AU\" />\n";
		echo "<input type=\"hidden\" name=\"bn\" value=\"PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest\" />\n";
		echo "<input type=\"hidden\" name=\"first_name\" value=\"".$_SESSION["fname"]."\" />\n";
		echo "<input type=\"hidden\" name=\"last_name\" value=\"".$_SESSION["lname"]."\" />\n";
		echo "<input type=\"hidden\" name=\"payer_email\" value=\"customer@example.com\" />\n";
		echo "<input type=\"hidden\" name=\"item_number\" value=\"".$order_id."\" / >\n";
		echo "<input type=\"hidden\" name=\"item_cost\" value=\"".$total."\" / >\n";
		//echo "<input type=\"submit\" value=\"Click here if you are not automatically redirected.\">\n";
		echo "</form>\n";

		// submit form
		echo "<script>document.getElementById(\"paypal_form\").submit();</script>\n";

		exit;
      }
    }
  }
}

echo "<center><b>Cart is currently empty!</b></center>";
header("Refresh: 3; url=index.php?page=cart");

// function getUserIpAddr(){
//   if(!empty($_SERVER['HTTP_CLIENT_IP'])){
//       $ip = $_SERVER['HTTP_CLIENT_IP'];
//   }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
//       $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
//   }else{
//       $ip = $_SERVER['REMOTE_ADDR'];
//   }
//   return $ip;
// }

// echo 'User Real IP - '.getUserIpAddr();
?>
