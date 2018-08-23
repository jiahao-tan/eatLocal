<?php
	if(session_id() == '' || !isset($_SESSION))
	{
		session_start();
	}

	include 'config.php';

	echo "<div class=\"d-sm-flex flex-row\">\n";
	echo "<div class=\"conv_list\">\n";

	$user_id = "";
	if(!isset($_SESSION["user_id"]))
	{
		echo "Log in to see your messages.";
		exit;
	}
	else
		$user_id = $_SESSION["user_id"];
	
	if(isset($_GET["mark_as_read"]))
	{
		$mark_as_read = $_GET["mark_as_read"];
		$mark_as_read_valid = false;

		// confirm message_id belongs to this user
		$result = $mysqli->query("SELECT message_id FROM messages m, conversations c WHERE m.conversation_id = c.conversation_id AND (c.to_user_id = '".$user_id."' OR c.from_user_id = '".$user_id."') order by message_id");
		if($result == false)
			die($mysqli->error);
		while($obj = $result->fetch_object())
		{
			if($mark_as_read == $obj->message_id)
			{
				$mark_as_read_valid = true;
				break;
			}
		}

		// attempt to mark message as read
		if($mark_as_read_valid)
		{
			$result = $mysqli->query("UPDATE messages SET isread = 1 where message_id = ".$mark_as_read);
			if($result == false)
				die($mysqli->error);
		}

		// update number of read messages
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

		$tmp = "";
		if($num_unread_msgs > 0)
			$tmp = " (".$num_unread_msgs." unread)";
		echo "<script>document.getElementById(\"num_unread\").innerHTML = \"".$tmp."\"</script>\n";
	}

	$conversation_id = 0;
	if(isset($_GET["conversation_id"]))
		$conversation_id = $_GET["conversation_id"];
	else
	{
		$result = $mysqli->query("SELECT * FROM conversations where from_user_id = '".$user_id."' OR to_user_id = '".$user_id."' order by conversation_id");
		if($result === FALSE)
		{
			die(mysql_error());
		}
		// get latest conversation_id
		while($obj = $result->fetch_object())
			$conversation_id = $obj->conversation_id;
	}

	$result = $mysqli->query("SELECT * FROM conversations where from_user_id = '".$user_id."' OR to_user_id = '".$user_id."' order by conversation_id");
	if($result === FALSE)
	{
		die(mysql_error());
	}

	$conv_count = 0;
	while($obj = $result->fetch_object())
	{
		$conv_count++;

		echo "<a class=\"nodecoration\" href=\"index.php?page=messages&conversation_id=".$obj->conversation_id."\">";
		echo "<div class=\"conv_item";
		if($conversation_id > 0 && $conversation_id == $obj->conversation_id)
			echo " active_conv_item";
		echo "\">\n";

		echo "<span class=\"conv_item_recipient\">\n";
		echo $obj->to_user_id.":\n";
		echo "</span>";
		echo "<span class=\"conv_item_subject\">\n";
		if($obj->subject == "")
			echo "&lt;No subject specified&gt;\n";
		else
			echo $obj->subject."\n";
		echo "<br/></span>";

		$query = $mysqli->query("SELECT * FROM messages where conversation_id = ".$obj->conversation_id." order by time_sent");
		if($result === FALSE)
		{
			die($mysqli->error);
		}

		$latest_msg = "";
		$latest_msg_date = "";
		while($msg = $query->fetch_object())
		{
			$latest_msg = $msg->message;
			$latest_msg_date =$msg->time_sent;
		}
		
		echo "<span class=\"conv_item_latest\">\n";
		echo $latest_msg."\n";
		echo "</span><br/>";
		echo "<span class=\"conv_item_latest_date\">\n";
		echo $latest_msg_date."\n";
		echo "</span>";

		echo "</div>"; // conv_item
		echo "</a>\n";
	}
	if($conv_count == 0)
	{
		echo "No conversations.\n";
	}

	// New conversation
	echo "<form class=\"messaging_form\" method=\"post\" action=\"index.php?page=send_new\">\n";
	echo "<div class=\"row\">\n";
	echo "<div class=\"col\">\n";
	echo "To:\n";
	echo "</div>\n";
	echo "<div class=\"col\">\n";
	echo "<input type=\"text\" name=\"to_user_id\">\n";
	echo "</div>\n";
	echo "</div>\n";

	echo "<div class=\"row\">\n";
	echo "<div class=\"col\">\n";
	echo "Subject:\n";
	echo "</div>\n";
	echo "<div class=\"col\">\n";
	echo "<input type=\"text\" name=\"subject\"><br/>\n";
	echo "</div>\n";
	echo "</div>\n";

	echo "<div class=\"row\">\n";
	echo "<div class=\"col\">\n";
	echo "Message:\n";
	echo "</div>\n";
	echo "<div class=\"col\">\n";
	echo "<input type=\"text\" name=\"message\"><br/>\n";
	echo "</div>\n";
	echo "</div>\n";

	echo "<div class=\"row\">\n";
	echo "<input type=\"submit\" value=\"New conversation\">\n";
	echo "</div>\n";
	echo "</form>\n";


	echo "</div>\n"; // conv_list

	// display active conversation
	echo "<div class=\"messages flex-fill\">\n";
	$query = $mysqli->query("SELECT * FROM messages m, conversations c where m.conversation_id = c.conversation_id and c.conversation_id = ".$conversation_id." order by time_sent");
	if($query === FALSE)
	{
		die($mysqli->error);
	}

	$msg_count = 0;
	while($msg = $query->fetch_object())
	{
		$msg_count++;

		echo "<div class=\"message row\">\n";

		$add_blank_col = false;
		if($msg->sender_is_from && $user_id == $msg->to_user_id || !$msg->sender_is_from && $user_id == $msg->from_user_id)
			$add_blank_col = true;
		else
			echo "<div class=\"col\"></div>\n";

		// display message sender & message
		echo "<div class=\"col\">\n";
		echo "<span class=\"message_sender\">\n";
		if($msg->sender_is_from != 0)
			echo $msg->from_user_id;
		else
			echo $msg->to_user_id;
		echo ": </span>\n";

		$msg_is_unread = false;
		if(!$msg->isread)
		{
			if(($user_id == $msg->to_user_id && $msg->sender_is_from == 1) || ($user_id == $msg->from_user_id && $msg->sender_is_from == 0))
				$msg_is_unread = true;
		}

		echo "<span class=\"message_message";
		if($msg_is_unread)
			echo " message_unread";
		echo "\">\n";
		echo $msg->message;
		echo "</span>\n";
		echo "<br/>\n";

		// display send time, and "mark as read" button, if applicable
		echo "<span class=\"message_time\">\n";
		echo $msg->time_sent;
		echo "</span>\n";

		if($msg_is_unread)
		{
			echo "<span class=\"mark_as_read flex-fill\">";
			echo "<a href=\"index.php?page=messages&mark_as_read=".$msg->message_id."\">Mark as read</a>";
			echo "</span>";
		}
		echo "</div>\n";

		if($add_blank_col)
		{
			echo "<div class=\"col\"></div>\n";
		}

		echo "</div>\n";
	}
	if($msg_count == 0)
		echo "No messages.";
	else
	{
		echo "<form class=\"messaging_form\" method=\"post\" action=\"index.php?page=send_new\">\n";
		echo "<input type=\"hidden\" name=\"conversation_id\" value=\"".$conversation_id."\">\n";
		echo "<input type=\"text\" name=\"message\">\n";
		echo "<input type=\"submit\" value=\"Send!\">\n";
		echo "</form>\n";
	}

	echo "</div>\n"; // messages

	echo "</div>\n"; // flex
?>
