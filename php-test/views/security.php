<?php

	require_once("../config.php");
	
	include(CONTROLLERS_PATH . "/SecurityController.php");
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
				<div><?php if(isset($error['remphase'])){echo $error_message['remphase'];} ?></div>
				<label>Application Name :</label>
				<input id="remphase" name="remphase" placeholder="name" type="text" value='<?php if(!empty($row['remphase'])){echo $row['remphase'];} ?>'>
				
				<input id="id" name="id"  type="hidden" value='<?php  echo $_GET['id']; ?>'>
				<input name="submit" type="submit" value=" save ">
			</form>
		</div>
	</body>
</html>