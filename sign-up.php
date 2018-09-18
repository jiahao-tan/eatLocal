<div class="bg">
	<section id="registerform" class="outer-wrapper">
		<div class="inner-wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-3"></div>
					<div class="col-md-6">
						<h2 class="text-center">Register</h2>
						<center>
							<p>
								<i>Fields marked with an asterisk (*) must be entered.</i>
							</p>
						</center>
						<form method="POST" action="index.php?page=insert" role="form" enctype="multipart/form-data">
							<div class="row">
								<div class="col">
									<label for="user_id">*User id</label>
									<input type="text" class="form-control" id="user_id" name="user_id" size="32" maxlength="32" required />
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label for="fname">*First Name</label>
									<input type="text" class="form-control" id="fname" name="fname" size="15" maxlength="50" required />
								</div>
								<div class="col-md-6">
									<label for="lname">*Last Name</label>
									<input type="text" class="form-control" id="lname" name="lname" size="15" maxlength="50" required />
								</div>
							</div>
							<div class="row">
								<div class="col">
								<label for="address">Address</label>
									<input type="text" class="form-control" id="address" name="address" size="15" maxlength="50" required />
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<label for="city">Suburb</label>
									<input type="text" class="form-control" id="suburb" name="suburb" size="15" maxlength="50" required />
								</div>
								<div class="col-md-6">
								<label for="pin">Postcode:</label>
									<input type="text" class="form-control" id="postcode" name="postcode" size="30" maxlength="50" required />
								</div>
							</div>
							<div class="row">
								<div class="col">
									<label for="email">*E-Mail:</label>
									<input type="email" class="form-control" id="email" name="email"  required />
									<span id="pwd_msg" class="error_msg"></span>
								</div>
							</div>
							<div class="row">
								<div class="col">
									<label for="pwd">*Password:</label>
									<input type="password" class="form-control" id="pwd" name="pwd" size="20" maxlength="20" onChange="checkRePassword(document)"
									/>
								</div>
							</div>
							<div class="row">
								<div class="col">
									<label for="file">Image:</label>
									<!-- <input type="hidden" name="MAX_FILE_SIZE" value="131072"> -->
									<input type="file" class="form-control" id="image" name="image"/>
								</div>
							</div>
							<!-- <div class="row">
								<div class="col">
									<center>
										<input type="checkbox">By signing up I agree to eatLocal's Terms of Use and Privacy Policy</center>
								</div>
							</div> -->
							<br>
							<div class="row">
								<div class="col">
									<center>
										<button type="submit" class="btn btn-clr">Register</button>
									</center>
								</div>
							</div>
						</form>
					</div>
					<div class="col-md-3">
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
