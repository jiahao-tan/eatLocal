<div class="jumbotron">
  <div class="container text-center">
    <div class="search-container">
        <form class="example" action="index.php?page=browse" style="margin:auto;max-width:800px">
          <input type="hidden" name="page" value="browse">
          <input type="text" placeholder="Search.." name="search2">
          <button type="submit"><i class="fa fa-search"></i></button>
        </form>
    </div>
  </div>
</div>

<div class="container-fluid bg-3 text-center">
  <h3>Feature Ads</h3><br>
  <div class="d-flex flex-wrap">
	<?php
		include 'config.php';
		$result = $mysqli->query("SELECT id, product_name, price, unit, MIN(image_id) AS image_id
	FROM products, product_images
	WHERE products.id = product_images.product_id
	GROUP by id, product_name, price, unit
	ORDER BY RAND()
	LIMIT 8");
		if($result === FALSE){
			die(mysql_error());
		} else {
			while($obj = $result->fetch_object()) {
				echo '<div class="col-sm-3 col-md-3" style="padding-bottom: 1.5em">'."\n";
				#echo "<a href=\"index.php?page=individual_product&product_id=".$obj->id."\">";
				#echo $obj->product_name.' $'.$obj->price.' per '.$obj->unit.'<br/>'."\n";
				#echo "</a>\n";
				echo "<div style=\"width: 100%; padding-bottom: 100%; position: relative;\">\n";
				echo "<a href=\"index.php?page=individual_product&product_id=".$obj->id."\">";
				echo "<div class=\"border veg\" style=\"position: absolute; top: 0; bottom: 0; left: 0; right: 0;\">\n";
				#echo "</a>";
				#echo "<a href=\"index.php?page=compose_new&to_user_id=".$obj->seller_user_id."&product_id=".$obj->id."\">Contact seller</a><br/>\n";
				#echo "<a href=\"index.php?page=individual_product&product_id=".$obj->id."\">";
				echo "<img src=\"product_images/{$obj->image_id}\" style=\"object-fit: cover; width: 100%; height: 100%;\">\n";

				echo "<div class=\"overlay\">\n";
				echo $obj->product_name.' $'.$obj->price.' per '.$obj->unit.'<br/>'."\n";
				echo "</div>\n";



				echo '</div>'."\n";
				echo "</a>";
				echo '</div>'."\n";
				echo '</div>'."\n";
			}
		}
	?>
  </div>
</div>

<div style="padding-bottom: 1em"></div>

<!--
<footer class="container-fluid text-center">
  <p>Footer Text</p>
</footer>
-->
