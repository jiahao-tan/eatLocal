<?php

//if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
if(session_id() == '' || !isset($_SESSION)){session_start();}

if(!isset($_SESSION["username"])){
  header("location:index.php");
}
include 'config.php';
?>




<div id="vegetables">
<center> <h3>Orders</h3> </center>
        <div class="container">
            <form method="post" name="update-quantity" action="admin-update.php">
            <div class="row">
            <?php
          $user = $_SESSION["username"];
          $result = $mysqli->query("SELECT * from orders where email='".$user."'");
          if($result) {
            while($obj = $result->fetch_object()) {
              echo '<div class="col-md-6 col-sm-2">';
              echo '<div class="veg img-border">';
              echo '<br>Order ID : ' . $obj->id;
              echo '<br>Price : ' . $obj->price;
              echo '<br>Quantity : ' . $obj->units;
              echo '<br>Date : ' . $obj->date_time;
              echo '<br>Total : ' . $currency.$obj->total;
              echo '</div>';
              echo '</div>';

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
              echo '<p><hr></p>';

            }
          }
        ?>

<div class="col-md-3 col-sm-2">
<br />


</div>
</form>        </div>
</div>
<br />
<br />
<br />
<br />