<?php
	if(session_id() == '' || !isset($_SESSION)){session_start();}
	include 'config.php';
?>

<div class="all">
	<div class="container-fluid">
		<div class="row">
			<!-- browse menu-->
			<div class="col-sm-3">
				<div class="box">
					<h2>Browse</h2>
					<a href="index.php?page=browse&my_products=true">My products</a>
					<?php
						$prev_cat = '';
						$i = 0;

						$result = $mysqli->query('SELECT distinct category, product_name FROM products order by category, product_name asc');
						if($result === FALSE){
							die(mysql_error());
						} else {
							while($obj = $result->fetch_object()) {
								if($prev_cat != $obj->category) {

									if($prev_cat != '') {
										echo '</ul>'."\n";
										echo '</div>'."\n";
										echo '</div>'."\n";
									}

									$prev_cat = $obj->category;
									$i++;

									echo '<div class="panel-group'.$i.'">'."\n";
									echo '<div class="panel-heading">'."\n";
									echo '	<p class="panel-title">'."\n";
									echo '		<a data-toggle="collapse" href="#collapse'.$i.'">'.$obj->category.'</a>'."\n";
									echo '	</p>'."\n";
									echo '</div>'."\n";
									echo '<div id="collapse'.$i.'" class="panel-collapse collapse">'."\n";
									echo '	<ul class="list-group">'."\n";
								}

								echo '<li class="list-group-item"><a href="index.php?page=browse&product='.$obj->product_name.'">'.$obj->product_name.'</a></li>'."\n";
							}
							echo '</ul>'."\n";
							echo '</div>'."\n";
							echo '</div>'."\n";
						}
					?>
				</div> <!-- box -->
			</div> <!-- col-sm-3 -->
			<!--menu completed-->
			<!-- gallery-->
			<div class="col-sm-9">
				<div class="container-fluid">
					<center>
						<h2>
						<?php
							if(isset($_GET['search2']) && $_GET['search2'] != '') {
								echo 'Searching for string: '.$_GET['search2'];
							} elseif(isset($_GET['product'])) {
								echo 'Displaying product type: '.$_GET['product'];
							} else {
								echo 'Displaying all product types';
							}
						?>
						</h2>
					</center>
					<?php
						if(isset($_GET["my_products"]) && $_GET["my_products"] == "true" && $_SESSION["user_id"] == "")
						{
							echo "<div class=\"row justify-content-center\">\n";
							echo "<span class=\"error_msg\">You must be logged on to see your products.</span>\n";
							echo "</div>\n";
						}
					?>
					<?php
						$i=0;
						$product_id = array();
						$product_quantity = array();

						if(isset($_GET['my_products']) && $_GET['my_products'] == "true" )
						{
							$stmt = $mysqli->prepare("SELECT * FROM products where seller_user_id = ?");
							$stmt->bind_param("s", $_SESSION['user_id']);
							$stmt->execute();
							$result = $stmt->get_result();
						}
						elseif(isset($_GET['search2']))
						{
							$stmt = $mysqli->prepare("SELECT * FROM products where product_name regexp ?");
							$stmt->bind_param("s", $_GET['search2']);
							$stmt->execute();
							$result = $stmt->get_result();
						}
						elseif(isset($_GET['product']))
						{
							$stmt = $mysqli->prepare("SELECT * FROM products where product_name = ?");
							$stmt->bind_param("s", $_GET['product']);
							$stmt->execute();
							$result = $stmt->get_result();
						}
						else
						{
							$result = $mysqli->query('SELECT * FROM products');
						}
						if($result === FALSE){
							die(mysqli_error($mysqli));
						}

						echo "<div class=\"d-flex flex-wrap\">\n";
						while($obj = $result->fetch_object())
						{
						  echo '<div class="col-sm-4 col-md-3">';
						  echo "<div style=\"width: 100%; padding-bottom: 100%; position: relative;\">\n";
						  echo "<a href=\"index.php?page=individual_product&product_id=".$obj->id."\">";
						  echo '<div class="veg img-border" style="position: absolute; top: 0; bottom: 0; left: 0; right: 0;">';
							if(!($stmt_img = $mysqli->prepare("SELECT MIN(image_id) as image_id FROM product_images WHERE product_id = ?")))
								die($mysqli->error);
							if(!$stmt_img->bind_param("i", $obj->id))
								die($mysqli->error);
							if(!$stmt_img->execute())
								die($mysqli->error);
							$res_img = $stmt_img->get_result();
							$obj_img = $res_img->fetch_object();
						  echo '<img style="object-fit: cover; width: 100%; height: 100%; margin: auto;" src="product_images/'.$obj_img->image_id.'" class="veg-img">';

						  echo "<div class=\"overlay\">\n";
						  echo $obj->product_name." ".$currency.$obj->price." per ".$obj->unit."<br/>\n";
						  #echo "<a href=\"index.php?page=compose_new&to_user_id=".$obj->seller_user_id."&product_id=".$obj->id."\">Contact seller</a><br/>\n";
						  echo "</div>\n";
						  echo "</a>";

						  /*
						  if($_SESSION["user_id"] != "" && $obj->seller_user_id == $_SESSION["user_id"])
						  {
							echo "<form>\n";
							echo "<input type=\"hidden\" name=\"page\" value=\"browse\"/>\n";
							echo "<input type=\"hidden\" name=\"delete_product_id\" value=\"{$obj->id}\"/>\n";
							echo "<input type=\"submit\" value=\"Delete product\"/>\n";
							echo "</form>\n";
						  }
						  */

						  echo "</div>\n";
						  echo "</div>\n";

						  #if($obj->qty > 0){
							#echo'<div><a href="update-cart.php?action=add&id='.$obj->id.'"><input type="submit" value="Add To Cart" class="btn btn-primary col-md-6 offset-md-3" /></a></div>';
						  #}
						  #else {
							#echo '<div class="col-md-6 offset-md-3"> Out Of Stock! </div>';
						  #}
						  echo"</div>\n";
						  $i++;
						}
						echo "</div> <!-- d-flex flex-wrap -->\n";
						$_SESSION['product_id'] = $product_id;
					?>
			  </div> <!-- container-fluid -->
			</div> <!-- col-sm-9 -->
		</div> <!-- row -->
		<!--gallery completed-->
	</div> <!-- container-fluid -->
</div> <!-- all -->
