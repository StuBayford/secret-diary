<?php
	session_start();
	//$_SESSION['username'] = "stubayford";
	if ($_SESSION['email']) {
		echo "You are logged in!";
	} else {
		header("Location: register_user.php");
	}
?>
