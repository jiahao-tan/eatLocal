<?php
	if(session_id() == '' || !isset($_SESSION))
	{
		session_start();
	}
	include 'config.php';
?>

<?php
	if(!isset($_SESSION["user_id"]))
	{
		echo "<h1>You must be logged in to send a message.</h1>";
		echo "<a href=\"index.php?page=messages\">Go to your messages.</a>";
		exit;
	}
	$user_id = $_SESSION["user_id"];

	// message must be supplied
	if(!isset($_POST["message"]))
	{
		echo "<h1>An error occurred: no message specified.</h1>";
		echo "<a href=\"index.php?page=messages\">Go to your messages.</a>";
		exit;
	}
	$message = $_POST["message"];
	if($message == "")
	{
		echo "<h1>Cannot send blank messages.</h1>";
		echo "<a href=\"index.php?page=messages\">Go to your messages.</a>";
		exit;
	}


	// check for other required parameters
	if(
		!(
		(isset($_POST["to_user_id"]) && isset($_POST["subject"]) && !isset($_POST["conversation_id"])) ||
		(!isset($_POST["to_user_id"]) && !isset($_POST["subject"]) && isset($_POST["conversation_id"]))
		)
	)
	{
		echo "<h1>Either conversation_id must be specified, or both to_user_id and subject must be specified</h1>";
		echo "<a href=\"index.php?page=messages\">Go to your messages.</a>";
		exit;
	}

	if(isset($_POST["to_user_id"]) && $user_id == $_POST["to_user_id"])
	{
		echo "<h1>Cannot send a message to yourself.</h1>";
		echo "<a href=\"index.php?page=messages\">Go to your messages.</a>";
		exit;
	}
	
	$mysqli->autocommit(FALSE);
	
	// do we need to create a conversation?
	$conversation_id = 0;
	if(!isset($_POST["conversation_id"]))
	{
		$to_user_id = $_POST["to_user_id"];
		$subject = $_POST["subject"];

		$result = $mysqli->query("INSERT INTO conversations(from_user_id, to_user_id, subject) VALUES('".$user_id."', '".$to_user_id."', '".$subject."')");
		if($result === FALSE)
		{
			die($mysqli->error);
		}

		// get conversation id
		$conversation_id = $mysqli->insert_id;
	}
	else
	{
		$conversation_id = $_POST["conversation_id"];
	}

	// insert message
	$sender_is_from = 1;
	if(isset($_POST["conversation_id"]))
	{
		$result = $mysqli->query("SELECT * FROM conversations WHERE conversation_id = ".$conversation_id);
		if($result === FALSE)
		{
			die($mysqli->error);
		}
		$obj = $result->fetch_object();
		if($user_id == $obj->from_user_id)
			$sender_is_from = 1;
		else if($user_id == $obj->to_user_id)
			$sender_is_from = 0;
		else
		{
			echo "<h1>Supplied conversation id does not belong to this user.</h1>";
			echo "<a href=\"index.php?page=messages\">Go to your messages.</a>";
			exit;
		}
	}
	$result = $mysqli->query("INSERT INTO messages(conversation_id, message, sender_is_from, isread) VALUES(".$conversation_id.", '".$message."', $sender_is_from, 0)");
	if($result === FALSE)
	{
		die($mysqli->error);
	}

	// commit changes
	if(!$mysqli->commit())
	{
		echo "<h1>Could not send message!</h1>";
		die($mysqli->error);
	}

	echo "<h1>Message sent!</h1>\n";
	header("Refresh: 3; url=index.php?page=messages");
?>
