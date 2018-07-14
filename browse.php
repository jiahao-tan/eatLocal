<?php
if(session_id() == '' || !isset($_SESSION)){session_start();}
include 'config.php';
?>
<div class="all">
	<div class="container">
		<div class="row">
			<!-- browse menu-->
			<div class="col-sm-3">
				<div class="box">
<h2>Browse</h2>
           		
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
				</div>
			</div>
			<!--menu completed-->
			<!-- gallery-->
			<div class="col-md-9">
				<div class="container">
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<div class="input-group" id="">
								<!-- <input type="text" class="form-control" placeholder="I'm looking for" />
								<div class="input-group-btn">
									<div class="btn-group" role="group">
										<button type="button" class="btn btn-clr">
											<div>
												<i class="fa fa-search"></i>
											</div>
										</button>
									</div>
								</div> -->
							</div>
						</div>
					</div>
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
					<div class="row">
					<?php
          $i=0;
          $product_id = array();
          $product_quantity = array();

		  if(isset($_GET['search2'])) {
		  	$result = $mysqli->query('SELECT * FROM products where product_name regexp '."'".$_GET['search2']."'");
		  } elseif(isset($_GET['product'])) {
		  	$result = $mysqli->query('SELECT * FROM products where product_name = '."'".$_GET['product']."'");
		  } else {
          	$result = $mysqli->query('SELECT * FROM products');
          }
          if($result === FALSE){
            die(mysqli_error($mysqli));
          }

          if($result){

            while($obj = $result->fetch_object()) {

			  echo '<div class="col-md-4 col-sm-2">';
			  echo '<div class="veg img-border">';
			  echo '<img src="images/'.$obj->product_img_name.'" class="veg-img">';
			  echo'<div class="overlay">'.$obj->product_name.' '.$currency.$obj->price.'</div>';
			  echo '</div>';
			  
			  if($obj->qty > 0){
                echo'<div><a href="update-cart.php?action=add&id='.$obj->id.'"><input type="submit" value="Add To Cart" class="btn btn-primary col-md-6 offset-md-3" /></a></div>';
              }
              else {
                echo '<div class="col-md-6 offset-md-3"> Out Of Stock! </div>';
              }
			  echo'</div>';

              $i++;
            }

          }

          $_SESSION['product_id'] = $product_id;


        //   echo '</div>';
        //   echo '</div>';
          ?>
		  </div>
					
				</div>
			</div>
			<!--gallery completed-->
		</div>
	</div>
</div>
<br>