<?php
	error_reporting(E_ALL|E_STRICT);
if (session_id() == '' || !isset($_SESSION)) {session_start();}
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="css/all.css" rel="stylesheet" type="text/css"> -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
	<link href="css/style.css" rel="stylesheet" type="text/css">

		<?php
			$page = isset($_GET['page']) ? $_GET['page'] : 'home3';
			echo '<link href="css/' . $page . '.css" rel="stylesheet" type="text/css">';
		?>



	<!-- <script src="js/slim.min.js"> </script>
	<script src="js/popper.min.js"> </script>
	<script src="js/bootstrap.min.js"> </script> -->
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
	<title>EatLocal</title>
</head>

<body>
	<!-- Navigation -->
	<nav class="navbar sticky-top navbar-expand-sm navbar-dark bg-clr">
		<a class="navbar-brand" href="index.php">
			<img src="images/logo.png" alt="eatLocal" width="130em">
		</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
		 aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">

				<?php
	if (isset($_SESSION["username"])) {
			echo '<li class="nav-item"><a class="nav-link" href="index.php?page=home3">Home</a></li>';
			echo '<li class="nav-item"><a class="nav-link" href="index.php?page=messages">Messages<span class="num_unread" id="num_unread"></span></a></li>';
			if(isset($_SESSION["user_id"]))
			{
				include "config.php";
				$user_id = $_SESSION["user_id"];
				$num_unread_msgs = 0;
				$result = $mysqli->query("SELECT * FROM messages m, conversations c WHERE m.conversation_id = c.conversation_id AND (c.from_user_id = '".$user_id."' OR c.to_user_id = '".$user_id."')");
				if($result == false)
				{
					die($mysqli->error);
				}
				while($obj = $result->fetch_object())
				{
					if(!$obj->isread)
					{
						if(($user_id == $obj->to_user_id && $obj->sender_is_from == 1) || ($user_id == $obj->from_user_id && $obj->sender_is_from == 0))
							$num_unread_msgs++;
					}
				}
				if($num_unread_msgs > 0)
					echo "<script>document.getElementById(\"num_unread\").innerHTML = \" (".$num_unread_msgs." unread)\";</script>";
			}

			echo '<li class="nav-item"><a class="nav-link" href="index.php?page=browse">Products</a></li>';
			echo '<li class="nav-item"><a class="nav-link" href="index.php?page=cart">Cart</a></li>';
			echo '<li class="nav-item"><a class="nav-link" href="index.php?page=list-product">Post</a></li>';
	} else {
			echo '<li class="nav-item"><a class="nav-link" href="index.php?page=home3">Home</a></li>';
			//echo '<li class="nav-item"><a class="nav-link" href="index.php?page=messages">Messages</a></li>';
			echo '<li class="nav-item"><a class="nav-link" href="index.php?page=browse">Products</a></li>';
	}
	?>

				<!-- <li class="nav-item">
					<a class="nav-link" href="index.php">Home</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="index.php?page=browse">Products</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="index.php?page=cart">Cart</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="index.php?page=orders">Orders</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="index.php?page=list-product">List Product</a>
				</li> -->
			</ul>

			<ul class="navbar-nav navbar-right">
			<?php
if (isset($_SESSION["username"])) {
    //echo '<li class="nav-item">'.$_SESSION["username"].'</li>';
    echo '<li class="nav-item"><a class="nav-link" href="index.php?page=account">My Account</a></li>';
    echo '<li class="nav-item"><a class="nav-link" href="index.php?page=logout"><span></span>Log Out</a></li>';
} else {
    echo '<li class="nav-item"><a class="nav-link" href="index.php?page=login"><span><img src="images/user.png" width="30em" style="padding-right:0.4em"></span>Log In</a></li>';
    echo '<li class="nav-item"><a class="nav-link" href="index.php?page=sign-up"><span></span>Sign Up</a></li>';
}
?>
			</ul>

		</div>
	</nav>

	<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'home3';
require "./".$page.".php";
?>

</body>

</html>
