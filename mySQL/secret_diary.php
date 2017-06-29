<?php
	ob_start();
	session_start();
	$error = "";
	if (array_key_exists("logout", $_GET)) {
		unset($_SESSION["id"]);
		setcookie("id", "", time() - 60*60);
		$_COOKIE["id"] = "";
	} else if ((array_key_exists("id", $_SESSION) AND $_SESSION['id']) OR (array_key_exists("id", $_COOKIE) AND $_COOKIE['id'])) {
		header("Location: loggedinpage.php");
	}
	if (array_key_exists("submit", $_POST)) {
		include ("connection.php");
		if (!$_POST['email']) {
			$error .= "An email address is required<br>";
		}
		if (!$_POST['password']) {
			$error .= "A password is required<br>";
		}
		if ($error != "") {
			$error = "<p>There were errors in your form:</p>".$error;
		} else {
			if ($_POST['signUp'] == 1) {
				$query = "SELECT `id` FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";
				$result = mysqli_query($link, $query);
				if (mysqli_num_rows($result) > 0) {
					$error = "That email is taken.";
				} else {
					$query = "INSERT INTO `users` (`email`, `password`) VALUES ('".mysqli_real_escape_string($link, $_POST['email'])."', '".mysqli_real_escape_string($link, $_POST['password'])."')";
					if (!mysqli_query($link, $query)) {
						$error = "<p>Could not sign you up, please try again later.</p>";
					} else {
						//Take latest inserted row id, md5 it, append it to the password just posted, and md5 thata string
						$query = "UPDATE `users` SET password = '".md5(md5(mysqli_insert_id($link)).$_POST['password'])."' WHERE id = ".mysqli_insert_id($link)." LIMIT 1";
						mysqli_query($link, $query);
						$_SESSION['id'] = mysqli_insert_id($link);
						//The next 4 lines are used again later in the code so should really be put in a separate function
						if ($_POST['stayLoggedIn'] == '1') {
							setcookie("id", mysqli_insert_id($link), time() + 60*60*24*365);
						}
						header ("Location: loggedinpage.php");
					}
				}
			} else {
				$query = "SELECT * FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";
				$result = mysqli_query($link, $query);
				$row = mysqli_fetch_array($result);
				if (isset($row)) {
					$hashedPassword = md5(md5($row['id']).$_POST['password']);
					if ($hashedPassword == $row['password']) {
						$_SESSION['id'] = $row['id'];
						if ($_POST['stayLoggedIn'] == '1') {
							setcookie("id", $row['id'], time() + 60*60*24*365);
						}
						header ("Location: loggedinpage.php");
					} else {
						$error = "That email/password combination could not be found.";
					}
				} else {
					$error = "That email/password combination could not be found.";
				}
			}
		}
	}
/*
// Generate a hash of the password "mypassword"
$hash = password_hash("mypassword", PASSWORD_DEFAULT);
 
// Echoing it out, so we can see it:
echo $hash;
 
// Some line breaks for a cleaner output:
echo "<br><br>";
 
// Using password_verify() to check if "mypassword" matches the hash.
// Try changing "mypassword" below to something else and then refresh the page.
if (password_verify('mypassword', $hash)) {
    echo 'Password is valid!';
} else {
    echo 'Invalid password.';
}
*/
	ob_end_flush();
?>
<?php include("header.php"); ?>

	<div id="homePageContainer" class="container">
		<h1>Secret Diary</h1>
		<p><strong>Store your thoughts permanently and securely.</strong></p>
		<div id="error"><?php if($error != "") {
		echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';} 
		?></div>
		
		<!-- Sign up form -->
		<form method="post" id="signUpForm">
			<p>Interested? Sign up now.</p>
			<div class="form-group">
				<input class="form-control" type="email" name="email" aria-describedby="emailHelp" placeholder="Your Email">
			</div>
			<div class="form-group">
				<input class="form-control" type="password" name="password" placeholder="Password">
			</div>
			<div class="form-check">
				<label class="form-check-label">
					<input class="form-check-input" type="checkbox" name="stayLoggedIn" value=1>
					Stay Logged In
				</label>
			</div>
			<div class="form-group">
				<input class="form-control" type="hidden" name="signUp" value="1">
				<input class="btn btn-success" type="submit" name="submit" value="Sign Up">
			</div>
			<p><a class="toggleForms">Log In</a></p>
		</form>
		
		<!-- Log In form -->
		<form method="post" id="logInForm">
			<p>Login using your email and password.</p>
			<div class="form-group">
				<input class="form-control" type="email" name="email" placeholder="Your Email">
			</div>
			<div class="form-group">
				<input class="form-control" type="password" name="password" placeholder="Password">
			</div>
			<div class="form-check">
				<label class="form-check-label">
					<input class="form-check-input" type="checkbox" name="stayLoggedIn" value=1>
					Stay Logged In
				</label>
			</div>
			<div class="form-group">
				<input class="form-control" type="hidden" name="signUp" value="0">
				<button class="btn btn-success" type="submit" name="submit">Log In</button>
			</div>
			<p><a class="toggleForms">Sign Up</a></p>
		</form>
	</div>

   <?php include("footer.php"); ?>