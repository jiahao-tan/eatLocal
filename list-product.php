<div class="bg">
	<section id="loginform" class="outer-wrapper">
		<div class="inner-wrapper">
			<div class="container-fluid">

<?php
	$title = "";
	$category = "";
	$desc = "";
	$price = "";
	$unit = "";
	$qty = "";
	
    //if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
    if(session_id() == '' || !isset($_SESSION)){session_start();}

	include 'config.php';

	if(!isset($_SESSION["user_id"]))
	{
		echo "<span class=\"error_msg\">You must be logged on to add a product.</span>\n";
		header("Refresh: 3; url=index.php");
	}
	else
		$user_id = $_SESSION["user_id"];

	if(count($_POST) == 0)
		goto skip_insert;

	$title = $_POST["title"];
	$category = $_POST["category"];
	$desc = $_POST["desc"];
	$price = $_POST["price"];
	$unit = $_POST["unit"];
	$qty = $_POST["qty"];

	$missing_fields = false;
	if(!isset($_POST["title"]) || $_POST["title"] == "")
	{
		echo "<div class=\"row justify-content-center\">\n";
		echo "<span class=\"error_msg\">Title must be specified.</span>\n";
		echo "</div>\n";
		$missing_fields = true;
	}
	if(!isset($_POST["category"]) || $_POST["category"] == "")
	{
		echo "<div class=\"row justify-content-center\">\n";
		echo "<span class=\"error_msg\">Category must be specified.</span>\n";
		echo "</div>\n";
		$missing_fields = true;
	}
	if(!isset($_POST["desc"]) || $_POST["desc"] == "")
	{
		echo "<div class=\"row justify-content-center\">\n";
		echo "<span class=\"error_msg\">Description must be specified.</span>\n";
		echo "</div>\n";
		$missing_fields = true;
	}
	if(!isset($_POST["price"]) || $_POST["price"] == "")
	{
		echo "<div class=\"row justify-content-center\">\n";
		echo "<span class=\"error_msg\">Price must be specified.</span>\n";
		echo "</div>\n";
		$missing_fields = true;
	}
	if(!isset($_POST["qty"]) || $_POST["qty"] == "")
	{
		echo "<div class=\"row justify-content-center\">\n";
		echo "<span class=\"error_msg\">\"Initial quantity available\" must be specified.</span>\n";
		echo "</div>\n";
		$missing_fields = true;
	}
	if(!isset($_POST["unit"]) || $_POST["unit"] == "")
	{
		echo "<div class=\"row justify-content-center\">\n";
		echo "<span class=\"error_msg\">Unit must be specified.</span>\n";
		echo "</div>\n";
		$missing_fields = true;
	}

	if($missing_fields)
		goto skip_insert;

	if(!($stmt = $mysqli->prepare("INSERT INTO products (product_img_name, product_code, seller_user_id, product_name, product_desc, qty, price, category, unit) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)")))
		die($mysqli->error);

	if(!$stmt->bind_param("sssssiiss", $title, $title, $user_id, $title, $desc, $qty, $price, $category, $unit))
		die($mysqli->error);

	if(!$stmt->execute())
		die($mysqli->error);

	$product_id = $mysqli->insert_id;

	//print_r( $_FILES);

	$file_error = false;
	foreach($_FILES as $file)
	{
		if($file["tmp_name"] != "" && $file["size"] > 0)
		{
			// process uploaded image
			if($file["size"] > 524288)
			{
				echo "<span class=\"error_msg\">Image {$file['name']} is too large. Please limit images to 512 KiB. Please wait for re-direct and try again via individual product page.</span>\n";
				$file_error = true;
				goto skip1;
			}

			// attempt to insert image id
			if(!($stmt = $mysqli->prepare("INSERT INTO product_images (product_id) VALUES (?)")))
				die($mysqli->error);
			if(!$stmt->bind_param("i", $product_id))
				die($mysqli->error);
			if(!$stmt->execute())
				die($mysqli->error);
			
			$image_id = $mysqli->insert_id;

			if(!move_uploaded_file($file["tmp_name"], "product_images/".$image_id))
			{
				echo "<span class=\"error_msg\">Failed to add image {$file['name']}. Please wait for re-direct and try again via individual product page.</span>\n";
				$file_error = true;
			}

			$stmt->close();
		skip1:
		}
	}
	if($file_error)
		header("Refresh: 3; url=index.php?page=individual_product&product_id=$product_id");
	else
		header("Refresh: 0; url=index.php?page=individual_product&product_id=$product_id");
skip_insert:
?>

				<div class="row">
					<div class="col-md-3"></div>
					<div class="col-md-6">
						<h2 class="text-center">List your Products</h2>
						<form method="POST" action="index.php?page=list-product" role="form" enctype="multipart/form-data">
							<div class="row">
								<div class="col">
									<label for="title">Title</label>
									<input type="text" class="form-control" id="title" name="title" placeholder="Enter Title" <?php echo "value=\"$title\""; ?>>
								</div>
							</div>
							<div class="row">
								<div class="col">
									<label for="category">Category</label>
									<select name="category" class="form-control" id="category" placeholder="Select a category">
										<?php
											include "config.php";
											$result = $mysqli->query("SELECT cat_title FROM categories order by cat_title");
											if($result == false)
												die($mysqli->error);
											while($obj = $result->fetch_object())
											{
												echo "<option value=\"$obj->cat_title\"";
												if($category == $obj->cat_title)
													echo " selected=\"selected\"";
												echo ">$obj->cat_title</option>\n";
											}
										?>
									</select>
								</div>
							</div>

							<div class="row">
								<div class="col">
									<label for="desc">Description</label>
									<textarea name="desc" class="form-control" id="$desc" rows="5" cols="30" placeholder="Describe your products" <?php echo "value=\"$desc\""; ?>></textarea>
								</div>
							</div>

							 <div class="row">
									<div class="col">
										<label for="price">Price per unit</label>
										<input name="price" type="text" class="form-control" id="price" placeholder="$" <?php echo "value=\"$price\""; ?>>
									</div>
                  <div class="col">
										<label for="unit">Unit</label>
										<!--
										<select name="unit" class="form-control" id="unit" holder="Select a unit">
    									<option value="kg">kg</option>
    									<option value="ml">ml</option>
    									<option value="each">each</option>
  										</select>
										-->
										<input name="unit" type="text" id="unit" class="form-control" placeholder="kg, ml, each, 250g bottle, etc." <?php echo "value=\"$unit\""; ?>>
									</div>
								</div>

							<div class="row">
								<div class="col">
									<label for="qty">Initial quantity available</label>
									<input name="qty" type="numeric" id="qty" class="form-control" <?php echo "value=\"$qty\""; ?>>
								</div>
							</div>


							<div class="row">
								<div class="col">
									<label for="file">Image:</label>
									<!-- <input type="hidden" name="MAX_FILE_SIZE" value="131072"> -->
									<input type="file" class="form-control" id="image" name="image"/>
									<input type="file" class="form-control" id="image" name="image2"/>
									<input type="file" class="form-control" id="image" name="image3"/>
								</div>
							</div>
							<br>

							<div class="row">
									<div class="col">
										<center><button type="submit" class="btn btn-default">Submit</button></center>
									</div>
							</div>
						</form>
					</div>
				</div>
				<div class="col-md-3"></div>
			</div>
		</div>
	</section>
</div>
