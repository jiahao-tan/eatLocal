<?php
	$profile_user_id = 0;
	if(!isset($_GET["user_id"]))
	{
		echo "No user selected.";
		exit;
	}
	else
		$profile_user_id = $_GET["user_id"];

	require_once "./config.php";

	$result = $mysqli->query("SELECT * FROM users WHERE user_id = '".$profile_user_id."'");
	if(!$result)
		die($mysqli->error);
	$obj = $result->fetch_object();
	if(!$obj)
	{
		echo "User id refers to non-existent user.";
		exit;
	}

	if(isset($_GET["comment"]) || isset($_GET["rating"]))
	{
		if(isset($_SESSION["user_id"]))
		{
			// validate the "rating" input
			$rating = $_GET["rating"];
			if($rating != "0" && $rating != "1" && $rating != "2" && $rating != "3" && $rating != "4" && $rating != "5")
			{
				echo "Rating must be a value between 0 and 5.";
				header("Refresh: 3; url=index.php?page=individual_user&user_id=".$profile_user_id);
				exit;
			}
			 
			// validate the "comment" input
			$comment = $_GET["comment"];
			if(!preg_match("/^[A-Za-z0-9\"'\?!\. ,;_-]*$/", $comment))
			{
				echo "Comment must only contain alphanumeric, punctuation, and whitespace characters.";
				header("Refresh: 3; url=index.php?page=individual_user&user_id=".$profile_user_id);
				exit;
			}

			$result = $mysqli->query("INSERT INTO user_reviews (user_id, message, rating, author_user_id) VALUES ('".$profile_user_id."', '".$_GET["comment"]."', ".$_GET["rating"].", '".$_SESSION["user_id"]."')");
			if(!$result)
				die($mysqli->error);
			echo "Review added.";
			header("Refresh: 3; url=index.php?page=individual_user&user_id=".$profile_user_id);
			exit;
		}
		else
		{
			echo "You must be logged on to write a review.";
			header("Refresh: 3; url=index.php?page=individual_user&user_id=".$profile_user_id);
			exit;
		}
	}
?>

<div class="d-sm-flex justify-content-center">
		<div class="p-4">
			<?php
				$file = "user_images/".$profile_user_id;
				if(!file_exists($file))
					$file = "user_images/no_image";
				echo "<img class=\"user_image\" src=\"".$file."\">\n";
			?>
		</div>
		<div class="p-4">
			<?php
				echo "<span class=\"name\">".$obj->fname."&nbsp;".$obj->lname."</span>\n";
				echo "<span class=\"user_id\"><i class=\"fas fa-user\"></i> ";
				echo "<a href=\"index.php?page=individual_user&user_id=".$profile_user_id."\">";
				echo $profile_user_id;
				echo "</a>";
				echo "</span>\n";
				echo "<span class=\"location\"><i class=\"fas fa-map-marker-alt\"></i> ".$obj->suburb.", ".$obj->city."</span>\n";

				$result = $mysqli->query("SELECT FLOOR(AVG(rating)) AS avg_rating FROM user_reviews WHERE user_id = '".$profile_user_id."'");
				if(!$result)
					die($mysqli->error);
				else
				{
					$ob = $result->fetch_object();
					if(!$ob)
						die($mysqli->error);
					echo "<span class=\"user_rating\">";
					for($i = 0; $i < $ob->avg_rating; $i++)
						echo "&star;&nbsp;";
					echo "</span>";
				}
			?>
		</div>
	</div>
</div>

<div class="d-sm-flex justify-content-center">
		<div class="p-3">
			<span class="user_description">
				<?php echo $obj->desc; ?>
			</span>
			<span class="contact_user">
				<?php
					echo "<i class=\"fas fa-envelope\"></i> ";
					echo "<a href=\"index.php?page=compose_new&to_user_id=".$profile_user_id."\">Contact Seller</a>\n";
				?>
			</span>
		</div>
		<div class="p-5">
			<div class="rate_form">
				<center>Rate this user</center>
				<form>
					<input type="hidden" name="page" value="individual_user">
					<?php
						echo "<input type=\"hidden\" name=\"user_id\" value=\"".$profile_user_id."\">";
					?>
					<div class="container">
						<div class="row">
							<div class="col">
								Rating:
							</div>
							<div class="col">
								<select name="rating">
									<option value="0">&#9734; &#9734; &#9734; &#9734; &#9734; (0/5)</option>
									<option value="1">&#9733; &#9734; &#9734; &#9734; &#9734; (1/5)</option>
									<option value="2">&#9733; &#9733; &#9734; &#9734; &#9734; (2/5)</option>
									<option value="3">&#9733; &#9733; &#9733; &#9734; &#9734; (3/5)</option>
									<option value="4">&#9733; &#9733; &#9733; &#9733; &#9734; (4/5)</option>
									<option value="5">&#9733; &#9733; &#9733; &#9733; &#9733; (5/5)</option>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="col">
								Comment:
							</div>
							<div class="col">
								<input type="text" name="comment">
							</div>
						</div>
						<div class="row justify-content-center">
							<input type="submit" value="Post review">
						</div>
					</div>
				</form>
			</div>
			<?php
				$result = $mysqli->query("SELECT * FROM user_reviews WHERE user_id = '".$profile_user_id."'");
				if(!$result)
					die($mysqli->error);
				while($rev = $result->fetch_object())
				{
					echo "<p>";
					$file = "user_images/".$rev->author_user_id;
					if(!file_exists($file))
						$file = "user_images/no_image";
					echo "<img src=\"".$file."\" width=\"100\" height=\"100\" alt=\"\"/>";
					echo "<a href=\"index.php?page=individual_user&user_id=".$rev->author_user_id."\">";
					echo $rev->author_user_id;
					echo "</a>";
					echo "</p>\n";
					echo "<p>".$rev->message."</p>\n";
					echo "<p>Rating:";
					for($i = 0; $i < $rev->rating; $i++)
						echo "&nbsp;&#9734;";
					echo "</p>\n";
					echo "<p>".$rev->time."</p>\n";
					echo "<p>&nbsp;</p>\n";
				}
			?>
		</div>
</div>
