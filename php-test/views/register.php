<?php

	session_start();

	// checking if a user is currently logged in
	if(isset($_SESSION['userid'])){
		header("location: ../views/dashboard.php");
	}
	
	require("../config.php");
	include(CONTROLLERS_PATH . "/RegController.php");
?>
<!DOCTYPE html>
<html>
	<head>
	<title>Password Manager</title>
	</head>
	<body>
		<div id="login">
		<h2>Register Form</h2>
			<form action="" method="post">
				<div><?php if(isset($error)){echo $error['username_error'];} ?></div>
				<label>UserName :</label>
				<input id="username" name="username" placeholder="username" type="text">
				<div><?php if(isset($error)){echo $error['password_error'];} ?></div>
				<label>Password :</label>
				<input id="password" name="password" placeholder="**********" type="password">
				<input name="submit" type="submit" value=" Login ">
			</form>
		</div>
	</body>
</html>