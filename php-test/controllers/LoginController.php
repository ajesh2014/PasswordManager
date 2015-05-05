<?php
	
	// start session
	session_start();
	
	// checking if a user is currently logged in
	if(isset($_SESSION['userid'])){
		header("location: dashboard.php");
	}
	
	// requiring database and validator class 
	require('DatabaseController.php');
	require('ValidatorController.php');
	
	// validator obj used for validation
	$validator = new Validator();
	// Database object
	$db = new Database();
	
	// check for submission made 
	if ($validator -> validateSubmit()) {
			
			// validate input, check if not empty
			if (!$validator -> validateLoginInput()) {
		
				// return validation errors
				return $error = $validator -> getLoginErrors();
				
			} else {
			
				// authorization proccess
				$userId = $db -> authorizeUser($_POST['username'] , 
												$_POST['password'] 
												);
				
				if($userId !== 0) {

					$_SESSION['userid']=$userId;
				
					header("location: dashboard");

				} else {
				
					return $validator -> getLoginErrors();
			
				}
			}
	}
	else if (!$validator -> validateSubmit() ){ return; }
	
?>