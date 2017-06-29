<?php
	session_start();
	$diaryContent = "";
	if (array_key_exists("id", $_COOKIE)) {
		$_SESSION['id'] = $_COOKIE['id'];
	}
	if (array_key_exists("id", $_SESSION)) {
		include("connection.php");
		$query = "SELECT `diary` from `users` WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";
		$row = mysqli_fetch_array(mysqli_query($link, $query));
		$diaryContent = $row['diary'];
	} else {
		header ("Location: secret_diary.php");
	}
	
	include("header.php");
?>

	<nav class="navbar navbar-toggleable-md navbar-fixed-top navbar-light bg-faded">
	  <a class="navbar-brand" href="#">Secret Diary</a>
		<div class="form-inline my-2 my-lg-0">
		  <a href="secret_diary.php?logout=1"><button class="btn btn-outline-success my-2 my-sm-0" type="submit">Log Out</button></a>
		</form>
	  </div>
	</nav>

	<div class="container-fluid" id="containerLoggedInPage">
		<textarea id="diary" class="form-control"><?php echo $diaryContent; ?></textarea>
	</div>
	
<?php	
	include("footer.php");
?>