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
				<?php
					include 'config.php';

					$username = isset($_POST["username"]) ? $_POST["username"] : "";
					$password = isset($_POST["pwd"]) ? $_POST["pwd"] : "";


					if($username != "" || $password != "")
					{
						$result = $mysqli->query('SELECT * from users order by user_id asc');
						if($result === FALSE){
						  die($mysqli->error);
						}
						$found = 'false';
						if($result){
							while($obj = $result->fetch_object()){
								if($obj->email === $username && password_verify($password, $obj->password)) {
									$_SESSION['username'] = $username;
									$_SESSION['type'] = $obj->type;
									$_SESSION['user_id'] = $obj->user_id;
									$_SESSION['fname'] = $obj->fname;
									$_SESSION['lname'] = $obj->lname;
									header("location:index.php");
									$found = 'true';
								}
							}

							if($found === 'false'){
								echo "<div class=\"row justify-content-center\">\n";
								echo "<span class=\"error_msg\">Invalid login! Please try again.</span>\n";
								echo "</div>\n";
								$password = "";
							}
						}
					}
				?>
				<div class="row">
					<div class="col-md-4"></div>
					<div class="col-md-4">
						<h2 class="text-center">Welcome back.</h2>
						<form role="form" method="POST" action="index.php?page=login">
								<div class="row">
									<div class="col">
										<label for="exampleInputEmail1">Email address</label>
										<input type="email" class="form-control" name="username" placeholder="Enter email" <?php if($username == "") { echo "autofocus"; } else { echo "value=\"$username\""; } ?>>
									</div>
								</div>
								<div class="row">
									<div class="col">
										<label for="exampleInputPassword1">Password</label>
										<input type="password" class="form-control" name="pwd" placeholder="Password" <?php if($password == "" && $username != "") { echo "autofocus"; } ?>>
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
