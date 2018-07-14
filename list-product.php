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
									<label for="catogory">Category</label>
									<select name="catogory" class="form-control" id="catogory" placeholder="Select a category">
										<option value="Vagetable">Vagetable</option>
										<option value="Fruit">Fruit</option>
										<option value="Homemade">Homemade Food</option>
									</select>
								</div>
							</div>

							<div class="row">
								<div class="col">
									<label for="desc">Discription</label>
									<textarea name="desc" class="form-control" rows="5" cols="30" placeholder="Describe your products"></textarea>
								</div>
							</div>

							 <div class="row">
									<div class="col">
										<label for="price">Price/Unit</label>
										<input type="price" class="form-control" id="exampleInputPassword1" placeholder="$">
									</div>
                                    <div class="col">
										<label for="unit">Unit</label>
										<select name="cars" class="form-control" id="unit"holder="Select a unit">
    									<option value="kg">kg</option>
    									<option value="ml">ml</option>
    									<option value="each">each</option>
  										</select>
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