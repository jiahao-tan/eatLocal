<?php
	$product_id = 0;
	if(!isset($_GET["product_id"]))
	{
		echo "No product selected.";
		exit;
	}
	else
		$product_id = $_GET["product_id"];

	require_once "./config.php";

	$result = $mysqli->query("SELECT * FROM products WHERE id = ".$product_id);
	if(!$result)
		die($mysqli->error);
	$obj = $result->fetch_object();
	if(!$obj)
	{
		echo "Product id refers to non-existant product.";
		exit;
	}

	$result = $mysqli->query("SELECT * FROM users WHERE user_id = '".$obj->seller_user_id."'");
	if(!$result)
		die($mysqli->error);
	$seller_obj = $result->fetch_object();
	if(!$seller_obj)
	{
		echo "No seller associated with product.";
		exit;
	}

	if(isset($_GET["comment"]) || isset($_GET["rating"]))
	{
		if(isset($_SESSION["user_id"]))
		{
			$result = $mysqli->query("INSERT INTO product_reviews (product_id, message, rating, author_user_id) VALUES (".$product_id.", '".$_GET["comment"]."', ".$_GET["rating"].", '".$_SESSION["user_id"]."')");
			if(!$result)
				die($mysqli->error);
			echo "Review added.";
			header("Refresh: 3; url=index.php?page=individual_product&product_id=".$product_id);
			exit;
		}
		else
		{
			echo "You must be logged on to write a review.";
			header("Refresh: 3; url=index.php?page=individual_product&product_id=".$product_id);
			exit;
		}
	}
?>
<div class="container">
<div class="header">
  <h1 align="center"><?php echo $obj->product_name; ?> in <?php echo $seller_obj->postcode; ?></h1>
<h3 align="right"><?php echo $seller_obj->suburb; ?></h3>
</div>

<body>

<!-- <h2 style="text-align:center">Product</h2> -->
<div class="row">
	<?php
		$result = $mysqli->query("SELECT * FROM product_images WHERE product_id = ".$product_id);
		if(!$result)
			die($mysqli->error);
		$i = 0;
		while($img = $result->fetch_object())
		{
			$i++;
			echo "<div class=\"col\">\n";
			$file = "images/".$img->image_id;
			if(!file_exists($file))
				$file = "images/no_image";
			echo "<img class=\"demo cursor\" src=\"".$file."\" style=\"width: 100%\" onclick=\"currentSlide(".$i.")\">\n";
			echo "</div>\n";
		}
	?>
</div>
<div class="row">
<div class="col-12">

  <h2>Description</h2>
  <p><?php echo $obj->product_desc; ?></p>


      <div class="member_frm">
		<?php
			echo "Price: $".$obj->price." / ".$obj->unit;
		?>
		<form action="update-cart.php" method="get">
			<input type="hidden" name="action" value="add"/>
			<?php echo "<input type=\"hidden\" name=\"id\" value=\"".$product_id."\"/>"; ?>
			Quantity: <input type="numeric" name="qty" size="10" maxlength="30" required />
			 <?php echo $obj->unit; ?>
			<input type="submit" id="submit" value="Add to cart" onClick="return validateInfo(document)"/>
		</form>

		<?php
			$r = $mysqli->query("SELECT COUNT(*) AS cnt, FLOOR(AVG(rating)) AS avg_rating FROM product_reviews WHERE product_id = ".$product_id);
			if(!$r)
				die($mysqli->error);
			$o = $r->fetch_object();
			if(!$o)
				die($mysqli->error);
			echo "Product rating: ";
			for($i = 0; $i < $o->avg_rating; $i++)
				echo "&star;&nbsp;";
			echo " (".$o->cnt." ratings)";
		?>

      </div>
</div>
</div>

<div class="row">
<div class="col-3 usercol">
	<h2>Seller</h2>
   <p>
   	<?php
		$file = "user_images/".$seller_obj->user_id;
 		$alt = "Image of user ".$seller_obj->user_id;
 		if(!file_exists($file))
 		{
 			$file = "user_images/no_image";
 			$alt = "User has no supplied image.";
 		}
 		echo "<img class=\"user_image\" src=\"".$file."\" alt=\"".$alt."\"/>\n";

		echo "<div class=\"usercontact\">";
 		echo "<i class=\"fas fa-user\"></i> <span class=\"user_id\">";
 		echo "<a href=\"index.php?page=individual_user&user_id=".$seller_obj->user_id."\">";
 		echo $seller_obj->user_id;
 		echo "</a>";
 		echo "</span>\n";

 		echo "<i class=\"fas fa-envelope\"></i> <span class=\"contact_seller\"><a href=\"index.php?page=compose_new&to_user_id=".$seller_obj->user_id."&product_id=".$product_id."\">Contact Seller</a></span>\n";
		echo "</div>\n";

 		$r = $mysqli->query("SELECT FLOOR(AVG(rating)) AS avg_rating FROM user_reviews WHERE user_id = '".$seller_obj->user_id."'");
 		if(!$r)
 			die($mysqli->error);
 		$o = $r->fetch_object();
 		if(!$o)
 			die($mysqli->error);
 		echo "<span class=\"user_rating\">";
 		for($i = 0; $i < $o->avg_rating; $i++)
 			echo "&star;&nbsp;";
 		echo "</span>\n";

 	?>
 	<?php echo $seller_obj->desc; ?>
   </p>
</div>

<div class="col-1">
</div>

<div class="col-8">
  <h2>Product Review</h2>
  <p>Let everyone know what you think about this product.</p>
  <p>&nbsp;</p>
	<form action="index.php" method="get">
		<input type="hidden" name="page" value="individual_product">
		Message: <input type="text" name="comment">
		Rating: <input type="text" name="rating">
		<?php
			echo "<input type=\"hidden\" name=\"product_id\" value=\"".$product_id."\">";
		?>
		<input type="submit" name="Post review">
	</form>

	<?php
		$result = $mysqli->query("SELECT * FROM product_reviews WHERE product_id = ".$product_id);
		if(!$result)
			die($mysqli->error);
		while($rev = $result->fetch_object())
		{
			echo "<p>";
			$file = "user_images/".$rev->author_user_id;
			if(!file_exists($file))
				$file = "user_images/no_image";
			echo "<img src=\"".$file."\" width=\"100\" height=\"100\" alt=\"\"/>";

			echo "<i class=\"fas fa-user\"></i> <span class=\"user_id\">";
			echo "<a href=\"index.php?page=individual_user&user_id=".$rev->author_user_id."\">";
			echo $rev->author_user_id;
			echo "</a>";
			echo "</span>\n";

			echo "</p>\n";
			echo "<p>".$rev->message."</p>\n";
			echo "<p>Rating:";
			for($i = 0; $i < $rev->rating; $i++)
				echo "&nbsp;&#9734;";
			echo "</p>\n";
			echo "<p>".$rev->time."</p>\n";
			echo "<p>&nbsp;</p>\n";
		}
	?>
</div>

</div>
</div>
</div>
