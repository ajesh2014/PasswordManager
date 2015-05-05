<?php
		session_start();
	
	// checking if a user is currently logged in
	if(!isset($_SESSION['userid'])){
		header("location: index");
	}

	// requiring database and validator classes
	require('DatabaseController.php');
	require('ValidatorController.php');
	
	// validator and database obj
	$validator = new Validator();
	$db = new Database();
	
	 
	if ($validator -> validateSubmit()) {
	
		if ($validator -> validateInput()) {
				
			// add the new password
			$passwordId = $db -> addPassword(	$_SESSION['userid'] , 
												$_POST['appname'] , 
												$_POST['password']
											);
				
			if($passwordId !== 0){
				
				header("location: dashboard");
					
			}
				
				
		} else {
			
			// error messages if any needed
			$error_message = array("appname" => "Please enter a Application name",
									"password" => "Please enter a Password"
									);
				
			// send back current data changed with errors
			$row = array("application" => $_POST['appname'],
						"password" => $_POST['password']
						);
								
				
			// return validation errors if incorrect input
			return $error = $validator -> getErrors();
	
		}
		
	} else if (!$validator -> validateSubmit()) {
	
		return;
		
	}
?>