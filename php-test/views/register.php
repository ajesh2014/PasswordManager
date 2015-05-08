<?php

	
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
				<div><?php if(isset($error)){echo $error_message['username'];} ?></div>
				<label>UserName :</label>
				<input id="username" name="username" placeholder="username" type="text">
				<div><?php if(isset($error)){echo $error_message['password'];} ?></div>
				<label>Password :</label>
				<input id="password" name="password" placeholder="**********" type="password">
				<input name="submit" type="submit" value=" Register ">
			</form>
		</div>
	</body>
</html>