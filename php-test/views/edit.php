	<?php

	require_once("../config.php");
	
	include(CONTROLLERS_PATH . "/EditController.php");
?>
<!DOCTYPE html>
<html>
	<head>
	<title>Password Manager</title>
	</head>
	<body>
		<div id="login">
		<h2>Edit application Form</h2>
		
			<form action="" method="post">
				<div><?php if(isset($error['appname'])){echo $error_message['appname'];} ?></div>
				<label>Application Name :</label>
				<input id="appname" name="appname" placeholder="name" type="text" value='<?php if(!empty($row['application'])){echo $row['application'];} ?>'>
				<div><?php if(isset($error['password'])){echo $error_message['password'];} ?></div>
				<label>Password :</label>
				<input id="password" name="password" placeholder="**********" type="text"value='<?php if(!empty($row['password'])){ echo $row['password'];} ?>'>
				<div><?php if(isset($error['appname'])){echo $error_message['appname'];} ?></div>
				<label>Secret Phrase :</label>
				<input id="sphrase" name="sphrase" placeholder="" type="text" value=''>
				<div><?php if(isset($error['sphrase'])){echo $error_message['sphrase'];} ?></div>
				<input id="id" name="id"  type="hidden" value='<?php   if(!empty($row['id'])){ echo $row['id'];} ?>'>
				<input name="submit" type="submit" value=" save ">
			</form>
		</div>
	</body>
</html>