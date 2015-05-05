<?php
require("../config.php");

class Database 
{

    // The database connection object
    protected static $connection;

	// Function to connect to the database or return false if failure, private as only this class should use it.
    private function connect() {    
        //Connect to the database
        if(!isset(self::$connection)) {
			
            self::$connection = new mysqli(DB_HOST,
											DB_DATABASE,
											DB_PASSWORD,
											DB_DATABASE
											);
        }

        // If connection was not successful, handle the error
        if(self::$connection === false) {
            // show error
            return false;
        }
		
        return self::$connection;
    }
	
	public function authorizeUser($username_unescaped, $password_unescaped) {
        // Connect to the database
		
        $connection = $this -> connect();
		
		// Escaping stings for mysql injection
		$username = $connection -> real_escape_string($username_unescaped);
		$password = $connection -> real_escape_string($password_unescaped); 
		
		// prepair query string 
		if($stmt = $connection -> prepare("SELECT id, password FROM users WHERE username=?")){
		
			$stmt->bind_param('s', $username);
			
			$stmt->execute();
			
			$stmt->bind_result($user_id , $password_hash);
			
			while ($stmt->fetch()) {
			
				$password_hash;
			}
			
			$stmt -> close();
				
		}
		
		$connection -> close();
		
		if (password_verify($password, $password_hash)) {
			return $user_id;
		} else {
			return 0;
		}
		
	}
	

	public function registerUser($usernameUnescaped, $passwordUnescaped) {
        // Connect to the database
        $connection = $this -> connect();
		
		// Escaping stings for mysql injection
		$username = $connection -> real_escape_string($usernameUnescaped);
		$password = $connection -> real_escape_string($passwordUnescaped); 
		$passwordHashed= password_hash($password,  PASSWORD_BCRYPT);
		
		// prepair query string 
		if($stmt = $connection -> prepare("INSERT INTO users (username , password) VALUES ( ?, ?)")){
	
			$stmt->bind_param('ss', $username, $passwordHash);
			
			$stmt->execute();
			
			$userId = $stmt -> insert_id;
			
			$stmt->close();
				
		}
		
		$connection -> close();
		
		return $userId;
	}	
       
	
	public function addPassword($userId , $appnameUnescaped , $passwordUnescaped) {
        // Connect to the database
        $connection = $this -> connect();
		
		// Escaping stings for mysql injection
		$appname = $connection -> real_escape_string($appnameUnescaped);
		$password = $connection -> real_escape_string($passwordUnescaped);
		
		// row id
		//$pass_id = 0;
		
		// prepair query string 
		if($stmt = $connection -> prepare("INSERT INTO password_list (user_id , application , password) VALUES ( ?, ?, ?)")){
	
			$stmt->bind_param('iss', $userId, $appname , $password);
			
			$stmt->execute();
			
			$passId = $stmt -> insert_id;
			
			$stmt->close();
			
		}else{
			$connection -> close();
			
			return 0;
		}
		
		$connection -> close();
		
		return $passId;
	}
    
	/** get the list of passwords **/
	public function getPasswords( $userId ) {
	
        // Connect to the database
        $connection = $this -> connect();
		
		$rows = array();
		
		$result = $connection -> query("SELECT * FROM password_list WHERE user_id=".$userId);
		
		while ($row = $result -> fetch_assoc()) {
		
            $rows[] = $row;
			
        }
		$connection -> close();
		
		return $rows;
	}
	
	/** get the individual password **/
	public function getPassword( $userId, $recordId ) {
	
        // Connect to the database
        $connection = $this -> connect();
		
		$rows = array();
		
		$result = $connection -> query("SELECT * FROM password_list WHERE id=".$recordId." AND user_id = ".$userId);
		
		while ($row = $result -> fetch_assoc()) {
		
            $rows[] = $row;
			
        }
		$connection -> close();
		
		return $rows[0];
	}
	
	public function editPassword( $userId, $recordId, $appnameUnescaped , $passwordUnescaped ) {
	
        // Connect to the database
        $connection = $this -> connect();
		
		// Escaping stings for mysql injection
		$appname = $connection -> real_escape_string($appnameUnescaped);
		$password = $connection -> real_escape_string($passwordUnescaped);
		
		// prepair query string 
		if($stmt = $connection -> prepare("UPDATE password_list SET application = ? , password = ? WHERE user_id = ? AND id = ?")){
		
			$stmt->bind_param('ssii', $appname ,  $password, $userId , $recordId);
			
			if ($stmt->execute()) {
			
				$connection -> close();
				
				return true;
				
			}else{
			
				$connection -> close();
				
				return false;
				
			}

			$stmt->close();
			
		}else{
		
			$connection -> close();
			
			return false;
		}
		
	}
	
	public function deletePassword($userId, $recordId) {
	
        // Connect to the database
        $connection = $this -> connect();
		
		// prepair query string 
		if($stmt = $connection -> prepare("DELETE FROM password_list WHERE user_id = ? AND id = ?")){
		
			$stmt->bind_param('ii', $userId , $recordId);
			
			if ($stmt->execute()) {
			
				return true;
				
			}else{
		
				return false;
				
			}
			
			$stmt->close();
			
			$connection -> close();
			
		}else{
		
			$connection -> close();
			
			return false;
			
		}

		
	}
	
}
?>