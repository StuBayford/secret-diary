<?php
	session_start();
	
	if (array_key_exists('email', $_POST) OR array_key_exists('password', $_POST)) {
		
		$link = mysqli_connect("shareddb1a.hosting.stackcp.net", "myDatabase-3485ce", "72+4Xy9xbpWc", "myDatabase-3485ce");
	
		if (mysqli_connect_error()) {
			//'die' stops the script
			die ("There was an error connecting to the database");
		}
		
		if ($_POST["email"] == "") {
			echo "<p>Email address is required.</p>";
		} else if ($_POST["password"] == "") {
			echo "<p>Password is required.</p>";
		} else {
			//Email and password have been entered
			$query = "SELECT `id` FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";
			$result = mysqli_query($link, $query);
			//Return number of rows from a query rathe rthan the results themselves for efficiency
			if (mysqli_num_rows($result) > 0) {
				echo "<p>That email address has already been taken</p>";
			} else {
				$query = "INSERT INTO `users` (`email`, `password`) VALUES ('".mysqli_real_escape_string($link, $_POST['email'])."', '".mysqli_real_escape_string($link, $_POST['password'])."')";
				if (mysqli_query($link, $query)) {
					//echo "<p>You have been signed up!</p>";
					$_SESSION['email'] = $_POST['email'];
					header("Location: session.php");
				} else {
					echo "<p>There was a problem signing you up - please try again later.</p>";
				}
			}
		}
	}
?>

<html>
	<body>
		<form method="post">
			<input name="email" type="text" placeholder="email address">
			<input name="password" type = "password" placeholder="password">
			<input type="submit" value="Sign Up">
		</form>
	</body>
</html>