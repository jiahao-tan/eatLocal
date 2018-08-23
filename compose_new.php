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
		echo "<h1>You must be logged in to send a new message.</h1>";
		exit;
	}

	$user_id = $_SESSION["user_id"];

	if(!isset($_GET["to_user_id"]))
	{
		echo "<h1>An error occurred - no recipient specified.</h1>";
		exit;
	}
	$to_user_id = $_GET["to_user_id"];

	if(!isset($_GET["subject"]))
	{
		$subject = "New enquiry from ".$user_id;
		if(isset($_GET["product_id"]))
			$subject = $subject." regarding product ".$_GET["product_id"];
	}
	else
		$subject = $_GET["subject"];

	echo "<form method=\"post\" action=\"index.php?page=send_new\">\n";
	echo "<div class=\"row\">\n";
	echo "<div class=\"col-md-1\">\n";
	echo "To:\n";
	echo "</div>\n";
	echo "<div class=\"col\">\n";
	echo "<input type=\"text\" name=\"to_user_id\" value=\"".$to_user_id."\">\n";
	echo "</div>\n";
	echo "</div>\n";

	echo "<div class=\"row\">\n";
	echo "<div class=\"col-md-1\">\n";
	echo "Subject:\n";
	echo "</div>\n";
	echo "<div class=\"col\">\n";
	echo "<input type=\"text\" name=\"subject\" value=\"".$subject."\"><br>\n";
	echo "</div>\n";
	echo "</div>\n";

	echo "<div class=\"row\">\n";
	echo "<div class=\"col-md-1\">\n";
	echo "Message:\n";
	echo "</div>\n";
	echo "<div class=\"col\">\n";
	echo "<input type=\"text\" name=\"message\"\"><br>\n";
	echo "</div>\n";
	echo "</div>\n";

	echo "<div class=\"row\">\n";
	echo "<div class=\"col\">\n";
	echo "<input type=\"Submit\" value=\"Send!\">\n";
	echo "</div>\n";
	echo "</div>\n";
	echo "</form>\n";
?>
