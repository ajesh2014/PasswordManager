<?php
class Validator
{
	// returns false if no input
	public function validateSubmit() {
	
		if (isset($_POST['submit']) && !empty($_POST)) {
		
			return true;
			
		}else{
		
			return false;
			
		}
	
	}

	// returns true if login input
	public function validateLoginInput() {
		
		if (empty($_POST['username']) || empty($_POST['password'])) {
		
			return false;
		
		} else if($_POST['username'] == " " || $_POST['password'] == " " ) {
		
			return false;
			
		}
		else {
		
			return true;
		
		}
		
	}
	
	// return login error
	public function getLoginErrors() {
	
		// creating assotive array for each field
		$error = array("loginError"=>'Incorrect Username or Password. Please try again.');
		
		// returning the result
		return $error;
	}
	
	//validate all input fields
	public function validateInput() {
	
		//Holder for value to return
		$returnValue = true;
		
		// loop through post array and fields checking if empty
		foreach( $_POST as $key => $value ) {
		
			if (empty($_POST[$key]) || $_POST[$key] == " " ) {
		
				$returnValue = false;
		
			}
		
		}

		return $returnValue;
	}
	
	//get errors 
	public function getErrors(){
	
		// intialise the array
		$errors = Array();
	
		// loop through post array and fields checking if empty
		foreach( $_POST as $key => $value ) {
		
			if (empty($_POST[$key]) || $_POST[$key] == " " ) {
		
				$errors[$key] = $key;
		
			}
		
		}
		
		return $errors;
		
	}
	
	

}


?>