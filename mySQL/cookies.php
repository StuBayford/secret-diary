<?php
	//Keeps cookie for a day
	//setcookie("customerId", "1234", time() + 60*60*24);
	
	//Unsets cookie next time page is loaded
	setcookie("customerId", "", time() - 60*60);
	
	$_COOKIE["customerId"] = "test";
	echo $_COOKIE["customerId"];
?>