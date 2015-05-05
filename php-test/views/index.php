<?php
	
	require("../config.php");
	include(CONTROLLERS_PATH ."/LoginController.php");

?>
<!DOCTYPE html>
<html>
	<head>
	<title>Password Manager</title>
	</head>
	<body>
		<div id="login">
		<h2>Login Form</h2>
			<form action="" method="post">
				<div><?php if(isset($error)){echo $error['loginError'];} ?></div>
				<label>UserName :</label>
				<input id="username" name="username" placeholder="username" type="text">
				<label>Password :</label>
				<input id="password" name="password" placeholder="**********" type="password">
				<input name="submit" type="submit" value=" Login ">
			</form>
			<a href="register">Register here</a>
		</div>
	</body>
</html>