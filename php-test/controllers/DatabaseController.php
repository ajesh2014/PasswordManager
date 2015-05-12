<?php
require("../config.php");
require("CipherController.php");

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
		$passwordHashed = password_hash($password,  PASSWORD_BCRYPT);
		
		
		// prepair query string 	
		if($stmt = $connection -> prepare("INSERT INTO users (username , password) VALUES (? , ?)")){
		
			$stmt->bind_param('ss', $username, $passwordHashed);
			
			$stmt->execute();
			
			$userId = $stmt -> insert_id;
			
			$stmt->close();
				
		}
		
		$connection -> close();
		 
		return $userId;
	}	
       
	
	public function addPassword($userId , $appnameUnescaped , $passwordUnescaped, $sphraseUnescaped) {
        // Connect to the database
        $connection = $this -> connect();
		
		// Escaping stings for mysql injection
		$appname = $connection -> real_escape_string($appnameUnescaped);
		$password = $connection -> real_escape_string($passwordUnescaped);	
		$sphrase = $connection -> real_escape_string($sphraseUnescaped);
		
		// hashing secreate phase as encyrption key
		$sphraseHashed = password_hash($sphrase,  PASSWORD_BCRYPT);
		
		//encrypt the password
		$cipher = new Cipher();
		$encyptedPassword = $cipher -> encrypt($sphraseHashed, $password);

		// prepair query string 
		if($stmt = $connection -> prepare("INSERT INTO password_list (user_id , application , password, sphrase) VALUES ( ?, ?, ?, ?)")){
	
			$stmt->bind_param('isss', $userId, $appname , $encyptedPassword, $sphraseHashed);
			
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
	public function getPassword( $userId ) {
	
        // Connect to the database
        $connection = $this -> connect();
		
		$rows = array();
		
		$recordId = $this -> getOperationRecordId( $userId );
		
		var_dump($recordId);
		
		
		$result = $connection -> query("SELECT * FROM password_list WHERE user_id=".$userId." AND id = ".$recordId);
		echo "SELECT * FROM password_list WHERE user_id=".$userId." AND id = ".$recordId;
		
		while ($row = $result -> fetch_assoc()) {
		
            $rows[] = $row;
			
        }

		$cipher = new Cipher();
		//echo $cipher -> decrypt('$2y$10$Ca4bgD.DJgoEUyZks6xMzewDemay2ZkuUp7j1gpRQhJB5Uz8w.6iK','9ml3cMSdCR8EGlDpnWX0GPeIH3L7C3Y/EfQkSCDOztTtwMIgVGP2L4GmnaDWeGxcAjKEkMzJYj8x+wx2Pe8NLw==');
		$rows[0]['password'] = $cipher -> decrypt($rows[0]['sphrase'], $rows[0]['password']);
		
	
		
		$connection -> close();
		
		return $rows[0];
	}
	
	public function editPassword( $userId, $recordId, $appnameUnescaped , $passwordUnescaped , $sphraseUnescaped ) {

	
        // Connect to the database
        $connection = $this -> connect();
		
		// Escaping stings for mysql injection
		$appname = $connection -> real_escape_string($appnameUnescaped);
		$password = $connection -> real_escape_string($passwordUnescaped);
		$sphrase = $connection -> real_escape_string($sphraseUnescaped);
		
		
		// check if phrase is correct
		if(!$this -> spraseVerify($userId, $recordId, $sphraseUnescaped)){
			
			return false;
			
		}
		
		
		
		// hashing secreate phase as encyrption key
		$sphraseHashed = $this -> getSphrase($userId, $recordId);
		
		var_dump($sphraseHashed);
		//die();
		
		//encrypt the password
		$cipher = new Cipher();
		$encyptedPassword = $cipher -> encrypt($sphraseHashed, $password);
		
		
		// prepair query string 
		if($stmt = $connection -> prepare("UPDATE password_list SET application = ? , password = ? WHERE user_id = ? AND id = ?")){
		
			$stmt->bind_param('ssii', $appname ,  $encyptedPassword, $userId , $recordId);
			
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
	
	// Function used to check if the user is the correct person 
	public function spraseVerify($userId, $recordId, $sphraseUnescaped) {
        // Connect to the database
		
        $connection = $this -> connect();
		
		// Escaping stings for mysql injection
		$sphrase = $connection -> real_escape_string($sphraseUnescaped);
		 
		
		// prepair query string 
		if($stmt = $connection -> prepare("SELECT sphrase FROM password_list WHERE user_id=? AND id=?")){
		
			$stmt->bind_param('ii', $userId , $recordId);
			
			$stmt->execute();
			
			$stmt->bind_result($sphraseHashed);
			
			while ($stmt->fetch()) {
			
				$sphrase;
			}
			
			$stmt -> close();
				
		}
		
		//$connection -> close();
		
		if (password_verify($sphrase, $sphraseHashed)) {
			return true;
		} else {
			return false;
		}
		
	}
	
	// Function used to check if the user is the correct person 
	public function getSphrase($userId, $recordId) {
        // Connect to the database
		
        $connection = $this -> connect();
		 
		
		// prepair query string 
		if($stmt = $connection -> prepare("SELECT sphrase FROM password_list WHERE user_id=? AND id=?")){
		
			$stmt->bind_param('ii', $userId , $recordId);
			
			$stmt->execute();
			
			$stmt->bind_result($sphraseHashed);
			
			while ($stmt->fetch()) {
			
				$sphrase;
			}
			
			$stmt -> close();
				
		}
		
		//$connection -> close();
		
		return $sphraseHashed;
		
	}
	
	
		public function setRecordAuthorized( $userId, $recordId) {
		
		
		
			// Connect to the database
			$connection = $this -> connect();
			
			var_dump($this -> getOperationRecord($userId));
			
			
			if($this -> getOperationRecord($userId)){
	
				
				// prepair query string 
				if($stmt = $connection -> prepare("UPDATE operation SET pw_id = ? WHERE user_id = ? ")){
				
					$stmt->bind_param('ii', $recordId ,  $userId );
					
					if ($stmt->execute()) {
					
						$connection -> close();
					   
						return true;
						
					}else{
					
						$connection -> close();
						echo 'test';
						return false;
						
					}

					$stmt->close();
					
				}else{
				
					$connection -> close();
					echo 'test1';
					die();
					return false;
				}
			} else {
			
				
			
				// prepair query string 
				if($stmt = $connection -> prepare("INSERT INTO operation (user_id , pw_id ) VALUES ( ?, ? )")){
				
					$stmt->bind_param('ii', $userId , $recordId);
					
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
		
	}
	
	private function getOperationRecord( $userId ){
		
		 // Connect to the database
        $connection = $this -> connect();
		
		
		
		$stmt = $connection -> prepare("SELECT * FROM operation WHERE user_id=?  LIMIT 1");
		// check if record exists
		if($stmt){
		
			$stmt->bind_param('i', $userId );
			
			$stmt->execute();
			
			$result = $stmt->get_result();
			
			$row = $result->fetch_assoc();
			
			if (!$row) {  
				
				return false;
				
			}
			
		} 
		
		return true;
		
		$connection -> close();
	
	}
	
	public function getOperationRecordId( $userId ){
		
		 // Connect to the database
        $connection = $this -> connect();
		
		
		
		// check if record exists
		if($stmt = $connection -> prepare("SELECT pw_id FROM operation WHERE user_id=? LIMIT 1")){
		
			$stmt->bind_param('i', $userId );
			
			$stmt->execute();
			
			$stmt->bind_result($recordId);
			
			while ($stmt->fetch()) {
			
				$sphrase;
			}
				
			
			$stmt -> close();
			
		} 
		
		return $recordId;
		
		//$connection -> close();
	
	}
}
?>