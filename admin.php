<?php
if (session_id() == '' || !isset($_SESSION)) {session_start();}
if (!isset($_SESSION["username"])) {
    header("location:index.php");
}
if ($_SESSION["type"] != "admin") {
    header("location:index.php");
}
include 'config.php';

?>




<div id="vegetables">
        <div class="container">
            <form method="post" name="update-quantity" action="admin-update.php">
            <div class="row">
        <?php
$result = $mysqli->query("SELECT * from products order by id asc");
if ($result) {
    while ($obj = $result->fetch_object()) {

        echo '<div class="col-md-3 col-sm-2">';
        echo '<div class="veg img-border">';
        echo '<img src="images/' . $obj->product_img_name . '" class="veg-img">';
        echo '<div class="overlay">' . $obj->product_name . '</div>';
        echo '</div>';
        echo 'Old Qty : ' . $obj->qty;
        echo '<input style="display:none;" type="number" value="' . $obj->id . '" name="id[]"/>';
        echo '<br>New Qty : <input type="number" name="quantity[]"/>';
        echo '</div>';
    }
}
?>

<div class="col-md-3 col-sm-2">
<br />

<input type="submit" class="btn btn-primary" value="Update">
</div>
</form>        </div>
</div>
<br />
<br />
<br />
<br />







