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
  <div class="row">
	<?php
		include 'config.php';
		$result = $mysqli->query('SELECT * FROM products');
		if($result === FALSE){
			die(mysql_error());
		} else {
			while($obj = $result->fetch_object()) {
				echo '<div class="col-sm-3">'."\n";
				echo '<p>'.$obj->product_name.'" $'.$obj->unit_price.' per kg</p>'."\n";
      			echo '<img src="images/'.$obj->product_img_name.'" class="img-responsive" style="width:100%" alt="Image">'."\n";
				echo '</div>'."\n";
			}
		}
	?>
  </div>
</div>

<footer class="container-fluid text-center">
  <p>Footer Text</p>
</footer>

</body>
</html>
