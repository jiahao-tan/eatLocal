<?php
if(session_id() == '' || !isset($_SESSION)){session_start();}
if(isset($_SESSION["username"])){
        header("location:index.php");
}
?>


<div class="bg"> 
		<section id="loginform" class="outer-wrapper">
		<div class="inner-wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-4"></div>
					<div class="col-md-4">
						<h2 class="text-center">Welcome back.</h2>
						<form role="form" method="POST" action="verify.php">
								<div class="row">
									<div class="col">
										<label for="exampleInputEmail1">Email address</label>
										<input type="email" class="form-control" name="username" placeholder="Enter email">
									</div>
								</div>
								<div class="row">
									<div class="col">
										<label for="exampleInputPassword1">Password</label>
										<input type="password" class="form-control" name="pwd" placeholder="Password">
									</div>
								</div>
								<div class="row">
									<div class="col">
										<input type="checkbox"> Remember me
									</div>
								</div>
								<div class="row">
									<div class="col">
										<center><button type="submit" class="btn btn-default">Submit</button></center>
									</div>
								</div>
							</form>
						</div>
					</div>
					<div class="col-md-4"></div>
				</div>
			</div>
		</section>
    	</div>