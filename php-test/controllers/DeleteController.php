<?php

	session_start();
	
	// requiring database class 
	require('DatabaseController.php');
	
	// checking if a user is currently logged in
	if(!isset($_SESSION['userid'])){
		header("location: index");
	}

	// check if an id is passed to this delete controller
	if(!empty($_GET['id'])){
		
		// Database object
		$db = new Database();
	
		// delete password entry
		$result = $db -> deletePassword($_SESSION['userid'] , $_GET['id'] );
		
		// redirect back to dashboard
		if($result){
		
			header("location: dashboard");
			
		}
	}
?>