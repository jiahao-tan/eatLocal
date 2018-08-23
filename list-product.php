<div class="bg">
	<section id="loginform" class="outer-wrapper">
		<div class="inner-wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-3"></div>
					<div class="col-md-6">
						<h2 class="text-center">List your Products</h2>
						<form method="POST" action="list.php" role="form">
							<div class="row">
								<div class="col">
									<label for="title">Title</label>
									<input type="text" class="form-control" id="title" name="title" placeholder="Enter Title">
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
												echo "<option value=\"".$obj->cat_title."\">".$obj->cat_title."</option>\n";
										?>
									</select>
								</div>
							</div>

							<div class="row">
								<div class="col">
									<label for="desc">Description</label>
									<textarea name="desc" class="form-control" id="$desc" rows="5" cols="30" placeholder="Describe your products"></textarea>
								</div>
							</div>

							 <div class="row">
									<div class="col">
										<label for="price">Price/Unit</label>
										<input name="price" type="text" class="form-control" id="price" placeholder="$">
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
										<input name="unit" type="text" id="unit" class="form-control" placeholder="kg, ml, each, 250g bottle, etc.">
									</div>
								</div>


							<div class="row">
								<div class="col">
									<label for="image">Image Name</label>
									<input type="text" class="form-control" id="image" name="image" placeholder="Image">
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
