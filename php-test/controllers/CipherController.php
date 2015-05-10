<?php
class Cipher {
	private $iv_size = 32;
	
    public function encrypt($key, $input) {
		
		// Cutting key down to size
		$key = substr($key, 0 , 32);
		
		
		//Creating IV using size and random
		$iv = mcrypt_create_iv($this -> iv_size, MCRYPT_RAND);
		
		//Creating Cipher text
		$cipherPassword = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, 
										$key, $input, MCRYPT_MODE_CBC, $iv
										);
		
		// Conactinating IV for decryption
		$cipherPassword = $iv . $cipherPassword;
	
        return base64_encode($cipherPassword);
    }
    public function decrypt($key , $input) {
	
		// Cutting key down to size
		$key = substr($key, 0 , 32);
	
		$cipherPasswordDecyrpt = base64_decode($input);
		
		$ivDec = substr($cipherPasswordDecyrpt, 0, $this -> iv_size);
		
		$encPassword = substr($cipherPasswordDecyrpt, $this -> iv_size);
	
		$decPassword = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key,
										$encPassword, MCRYPT_MODE_CBC, $ivDec);
	
        return trim($decPassword);
    }
}


?>