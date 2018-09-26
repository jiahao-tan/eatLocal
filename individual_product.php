<?php
    if(session_id() == '' || !isset($_SESSION)){session_start();}
	include "config.php";

	$product_id = 0;
	if(!isset($_GET["product_id"]))
	{
		echo "No product selected.";
		exit;
	}
	else
		$product_id = $_GET["product_id"];

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

	// update fields, if requested
	$updated_fields = false;
	if(isset($_POST["desc"]) && $_POST["desc"] != "" && isset($_SESSION["user_id"]) && $seller_obj->user_id == $_SESSION["user_id"])
	{
		if(!($stmt = $mysqli->prepare("UPDATE products SET product_desc = ? WHERE id = ?")))
			die($mysqli->error);
		if(!$stmt->bind_param("si", $_POST["desc"], $product_id))
			die($mysqli->error);
		if(!$stmt->execute())
			die($mysqli->error);
		$updated_fields = true;
	}

	if(isset($_POST["product_name"]) && $_POST["product_name"] != "" && isset($_SESSION["user_id"]) && $seller_obj->user_id == $_SESSION["user_id"])
	{
		if(!($stmt = $mysqli->prepare("UPDATE products SET product_name = ? WHERE id = ?")))
			die($mysqli->error);
		if(!$stmt->bind_param("si", $_POST["product_name"], $product_id))
			die($mysqli->error);
		if(!$stmt->execute())
			die($mysqli->error);
		$updated_fields = true;
	}

	if(isset($_POST["price"]) && $_POST["price"] != "" && isset($_SESSION["user_id"]) && $seller_obj->user_id == $_SESSION["user_id"])
	{
		if(!($stmt = $mysqli->prepare("UPDATE products SET price = ? WHERE id = ?")))
			die($mysqli->error);
		if(!$stmt->bind_param("ii", $_POST["price"], $product_id))
			die($mysqli->error);
		if(!$stmt->execute())
			die($mysqli->error);
		$updated_fields = true;
	}

	if(isset($_POST["unit"]) && $_POST["unit"] != "" && isset($_SESSION["user_id"]) && $seller_obj->user_id == $_SESSION["user_id"])
	{
		if(!($stmt = $mysqli->prepare("UPDATE products SET unit = ? WHERE id = ?")))
			die($mysqli->error);
		if(!$stmt->bind_param("si", $_POST["unit"], $product_id))
			die($mysqli->error);
		if(!$stmt->execute())
			die($mysqli->error);
		$updated_fields = true;
	}

	if($updated_fields)
		header("Refresh: 0; url=index.php?page=individual_product&product_id=$product_id");


	// delete product, if requested
	if(isset($_POST["delete_product"]) && $_POST["delete_product"] == "true" && isset($_SESSION["user_id"]) && $seller_obj->user_id == $_SESSION["user_id"])
	{
		if(!($stmt = $mysqli->prepare("DELETE FROM product_images WHERE product_id = ?")))
			die($mysqli->error);
		if(!$stmt->bind_param("i", $product_id))
			die($mysqli->error);
		if(!$stmt->execute())
			die($mysqli->error);
		$stmt->close();
		if(!($stmt = $mysqli->prepare("DELETE FROM products WHERE id = ?")))
			die($mysqli->error);
		if(!$stmt->bind_param("i", $product_id))
			die($mysqli->error);
		if(!$stmt->execute())
			die($mysqli->error);
		$stmt->close();
		header("Refresh: 0; url=index.php?page=browse");
	}

	// delete image if requested
	if(isset($_GET["image_id"]) && isset($_SESSION["user_id"]) && $seller_obj->user_id == $_SESSION["user_id"])
	{
		$image_id = $_GET["image_id"];
		$user_id = $_SESSION["user_id"];
		// confirm that requested image belongs to a product of the user
		if($obj->seller_user_id == $user_id)
		{
			if(!($s = $mysqli->prepare("DELETE FROM product_images WHERE image_id = ? AND product_id = ?")))
				die($mysqli->error);
			if(!($s->bind_param("ii", $image_id, $product_id)))
				die($mysqli->error);
			if(!$s->execute())
			{
				echo "Could not delete image.\n";
				die($mysqli->error);
			}
			else
			{
				if(!unlink("product_images/".$image_id))
					echo "Could not delete image file from filesystem.\n";
				$s->close();
			}
		}
	}

	// add image if requested
	if(isset($_FILES["image"]) && $_FILES["image"]["tmp_name"] != "" && $_FILES["image"]["size"] > 0 && isset($_SESSION["user_id"]) && $seller_obj->user_id == $_SESSION["user_id"])
	{
		if($_FILES["image"]["size"] > 524288)
		{
			echo "Image is too large. Please limit images to 512 KiB.";
			header("Refresh: 3; url=index.php?page=individual_product&product_id=$product_id");
			exit;
		}

		$user_id = $_SESSION["user_id"];
		// confirm that product belongs of the user (and therefore can add an image)
		if($obj->seller_user_id == $user_id)
		{
			// insert new image			
			$s = $mysqli->prepare("INSERT INTO product_images (product_id) VALUES (?)");
			$s->bind_param("i", $product_id);
			$s->execute();
			if(!$s)
			{
				echo "Could not add image.\n";
				die($mysqli->error);
			}
			$image_id = $mysqli->insert_id;
			if(!move_uploaded_file($_FILES["image"]["tmp_name"], "product_images/".$image_id))
				echo "Could not upload image.\n";
			$s->close();
		}
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
	<?php
		if($obj->seller_user_id == $user_id)
		{
			echo "<form style=\"display: inline\" action=\"index.php?page=individual_product&product_id=$product_id\" method=\"post\">\n";
			echo "<h1 align=\"center\">\n";
			echo "<input type=\"text\" size=\"15\" name=\"product_name\" value=\"{$obj->product_name}\"/> in {$seller_obj->postcode}\n";
			echo "</h1>\n";
			echo "<center><input type=\"submit\" value=\"Update product name\"/></center>\n";
			echo "</form>\n";
		}
		else
			echo "<h1 align=\"center\">$obj->product_name in {$seller_obj->postcode}</h1>\n";
	?>
<h3 align="right"><?php echo $seller_obj->suburb; ?></h3>
</div>

<!-- <h2 style="text-align:center">Product</h2> -->
<div class="d-flex flex-wrap justify-content-center">
	<?php
		$result = $mysqli->query("SELECT * FROM product_images WHERE product_id = ".$product_id);
		if(!$result)
			die($mysqli->error);
		$i = 0;
		while($img = $result->fetch_object())
		{
			$i++;
			echo "<div class=\"col-sm-4 col-md-3\">\n";
			echo "<div style=\"width: 100%; padding-bottom: 100%; position: relative;\">\n";
			echo "<div class=\"border\" style=\"position: absolute; top: 0; bottom: 0; left: 0; right: 0;\">\n";
			$file = "product_images/".$img->image_id;
			if(!file_exists($file))
				$file = "product_images/no_image";
			echo "<img class=\"demo cursor\" src=\"".$file."\" style=\"margin: auto; object-fit: cover; width: 100%; height: 100%;\" onclick=\"currentSlide(".$i.")\">\n";
			echo "</div>\n";
			echo "</div>\n";
			echo "<div class=\"row justify-content-center\">\n";
			if($obj->seller_user_id == $user_id)
			{
				echo "<form action=\"index.php\">\n";
				echo "<input type=\"hidden\" name=\"product_id\" value=\"$product_id\"/>\n";
				echo "<input type=\"hidden\" name=\"image_id\" value=\"$img->image_id\"/>\n";
				echo "<input type=\"hidden\" name=\"page\" value=\"individual_product\"/>\n";
				echo "<input type=\"submit\" value=\"Delete image\"/>\n";
				echo "</form>\n";
			}
			echo "</div> <!-- row -->\n";
			echo "</div> <!-- col-sm-4 col-md-3 -->\n";
		}
	?>

</div>

<?php
	if($obj->seller_user_id == $user_id)
	{
		echo "<div class=\"row justify-content-center\">\n";
		echo "<form class=\"border\" method=\"post\" action=\"index.php?page=individual_product&product_id=$product_id\" enctype=\"multipart/form-data\">\n";
		echo "<input type=\"file\" name=\"image\"/>\n";
		echo "<input type=\"submit\" value=\"Add product image\"/>\n";
		echo "</form>\n";
		echo "</div>\n";
	}
?>

<?php
	if($obj->seller_user_id == $user_id)
	{
		echo "<div class=\"row justify-content-center\">\n";
		echo "<form class=\"border\" method=\"post\" action=\"index.php?page=individual_product&product_id=$product_id\">\n";
		echo "<input type=\"hidden\" name=\"delete_product\" value=\"true\"/>\n";
		echo "<input type=\"submit\" value=\"Delete product\"/>\n";
		echo "</form>\n";
		echo "</div>\n";
	}
?>

<div class="row">
<div class="col-12">

  <h2>Description</h2>
  <p>
	  <?php
		if($obj->seller_user_id == $user_id)
		{
			echo "<form method=\"post\" action=\"index.php?page=individual_product&product_id=$product_id\">\n";
			echo "<textarea name=\"desc\">$obj->product_desc</textarea>\n";
			echo "<br/>\n";
			echo "<input type=\"submit\" value=\"Update description\"/>\n";
			echo "</form>\n";
		}
		else
			echo $obj->product_desc;
	  ?>
  </p>


      <div class="member_frm">
		<?php
			if($obj->seller_user_id == $user_id)
			{
				echo "<form method=\"post\" action=\"index.php?page=individual_product&product_id=$product_id\">\n";
				echo "Price: $ <input type=\"text\" name=\"price\" value=\"$obj->price\"/> per <input type=\"text\" name=\"unit\" value=\"$obj->unit\"/>\n";
				echo "<br/>\n";
				echo "<input type=\"submit\" value=\"Update price & unit\"/>\n";
				echo "</form>\n";
			}
			else
				echo "Price: $".$obj->price." per ".$obj->unit;
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
