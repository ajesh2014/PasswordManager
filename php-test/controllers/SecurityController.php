<?php
	
	// start session
	session_start();
	
	// checking if a user is currently logged in
	if(!isset($_SESSION['userid'])){
		header("location: index.php");
	}
	
	// requiring database and validator class 
	require('DatabaseController.php');
	require('ValidatorController.php');
	
	// validator obj used for validation
	$validator = new Validator();
	// Database object
	$db = new Database();
	
	if ($validator -> validateSubmit()) {
	
		if ($validator -> validateInput() && isset($_GET['id'])) {
				
			
				
			// add the new password
			$result = $db -> spraseVerify(	$_SESSION['userid'] , 
											$_POST['id'],
											$_POST['sphrase']
										);
										
			
			
									
				
			if($result && isset($_GET['action'])){
				
				$db -> setRecordAuthorized($_SESSION['userid'] , $_GET['id']);
				
				
		
				if(strcmp($_GET['action'], 'Edit') == 0){
					
					header("location: edit");
					
				} else if (strcmp($_GET['action'], 'View')){
				
				
				
				} else if (strcmp($_GET['action'], 'Delete')) {
				
					
				
				}
					
			} else {
			
				$error_message = array("sphrase" => "Please enter the correct pass phrase");
				
				// send back current data changed with errors
				$row = array("sphrase" => $_POST['sphrase']);			
				
				// return validation errors if incorrect input
				return $error = ["sphrase" => 'sphrase'];
			
			}
			
				
				
		} else {
			
			// error messages if any needed
			$error_message = array("sphrase" => "Please enter pass phrase");
				
			// send back current data changed with errors
			$row = array("sphrase" => $_POST['sphrase']);
								
				
			// return validation errors if incorrect input
			return $error = $validator -> getErrors();
	
		}
		
	} else if (!$validator -> validateSubmit()) {
	
		return;
		
	}
	
?>