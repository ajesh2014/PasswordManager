<?php

	session_start();

	// checking if a user is currently logged in
	if(isset($_SESSION['userid'])){
		header("location: ../views/dashboard.php");
	}

	// requiring database and validator classes
	require('DatabaseController.php');
	require('ValidatorController.php');
	
	// validator and database obj
	$validator = new Validator();
	$db = new Database();
	
	
	if ($validator -> validateSubmit()) {
	
		if ($validator -> validateInput()) {
			
			
			// authorization proccess
			$userId = $db -> registerUser($_POST['username'] , $_POST['password']);
		
			if($userId !== 0){
			
				session_start();
				
				$_SESSION['userid']=$userId;
				
				header("location: dashboard");
				
			}else{
			
				// error messages if any needed
				$error_message = array("username" => "Please enter a Application name",
										"password" => "Please enter a Password"
										);
					
				// send back current data changed with errors
				$row = array("username" => $_POST['username'],
								"password" => $_POST['password']
							);
									
					
				// return validation errors if incorrect input
				return $error = $validator -> getErrors();
			
			}
		} else {
				
			// error messages if any needed
			$error_message = array("username" => "Please enter a Application name",
									"password" => "Please enter a Password"
									);
				
			// send back current data changed with errors
			$row = array("username" => $_POST['username'],
							"password" => $_POST['password']
						);
								
				
			// return validation errors if incorrect input
			return $error = $validator -> getErrors();
				
		}
	}
	
	else if (!$validator -> validateSubmit()) {
	
		return;
	
	}
	
	
?>