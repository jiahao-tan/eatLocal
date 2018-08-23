<?php
	//if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
	if(session_id() == '' || !isset($_SESSION)){session_start();}

	include 'config.php';

	$username = $_POST["username"];
	$password = $_POST["pwd"];
	//$query = $mysqli->query("SELECT email, password from users");

	$result = $mysqli->query('SELECT user_id,email,password,fname,type from users order by user_id asc');

	if($result === FALSE){
	  die($mysqli->error);
	}

	$found = 'false';
	if($result){
		while($obj = $result->fetch_object()){
			if($obj->email === $username && $obj->password === $password) {
				$_SESSION['username'] = $username;
				$_SESSION['type'] = $obj->type;
				$_SESSION['user_id'] = $obj->user_id;
				$_SESSION['fname'] = $obj->fname;
				header("location:index.php");
				$found = 'true';
			}
		}

		if($found === 'false'){
			echo '<h1>Invalid Login! Redirecting...</h1>';
			header("Refresh: 3; url=index.php?page=login");
		}
	}
?>
