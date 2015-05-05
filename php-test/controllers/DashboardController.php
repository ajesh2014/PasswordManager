<?php

	session_start();
	
	// checking if a user is currently logged in
	if(!isset($_SESSION['userid'])){
		header("location: index");
	}

	// requiring database class 
	require('DatabaseController.php');
	
	// Database object
	$db = new Database();  
	
	// get passwords function
	return $rows = $db -> getPasswords( $_SESSION['userid'] );
	
	
?>