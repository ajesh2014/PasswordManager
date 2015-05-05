<?php
	// requiring database class 
	require('DatabaseController.php');
	if (isset($_POST['submit'])) {
			if (empty($_POST['username']) || empty($_POST['password'])) {
				// creating assotive array for each field
				$error = array("username_error"=>'', "password_error" => '');
				$error['username_error'] = 'Username is invalid';
				$error['password_error'] = 'Password is invalid';
				
				// returning the result
				return $error;
			}
		}
	if (!isset($_POST['submit'])) {
	
		return;
	}
	
	else{
		// Database object
		$db = new Database();  
		
		// authorization proccess
		$user_id = $db -> Reg($_POST['username'] , $_POST['password']);
		
		if($user_id !== 0){
			session_start();
			$_SESSION['userid']=$user_id;
			//var_dump($user_id);
			header("location: dashboard");
		}else{
			$error = array("username_error"=>'', "password_error" => '');
			$error['username_error'] = 'Username is invalid';
			$error['password_error'] = 'Password is invalid';
			
			// returning the result
			return $error;
		}
		
	}
?>