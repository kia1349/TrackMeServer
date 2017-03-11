<?php

	class DBFn{
		
		private $conn;
		/*
		 *	Constructor for database functions class that connects to database
		 */
		function __construct(){

			require_once 'DBConnect.php';
			$db = new DBConnect();
        	$this->conn = $db->connect();
		}
		/**/
		function __destruct(){
		}
		public function saveUser($fname, $surname, $email, $phno, $pw){
			$uuid = uniqid('', true); //Prefix + 'More Entropy = true' to increse likelyhood of uuid being unique
			$hash = $this->hash($pw);
        	$encrypted_pw = $hash["encrypted"];
        	$salt = $hash["salt"];
        	$stmt = $this->conn->prepare("INSERT INTO userDetails(unique_id, first_name, surname, email, phone_no, encrypted_password, salt, created_at) VALUES(?,?,?,?,?,?,?,NOW())");
        	if ($stmt === FALSE) {
    			die ("Mysql Error: " . $this->conn->error);
			}
        	$stmt->bind_param("sssssss", $uuid,
        		$fname, $surname, $email, $phno, $encrypted_pw, $salt);
        	$result = $stmt->execute();
        	$stmt->close();
        	if($result){
	        	$stmt = $this-> conn -> prepare("SELECT * FROM userDetails WHERE email =?"); //SQL Statment object
	        	$stmt->bind_param("s", $email);
	        	$stmt->execute();
	        	$user = $stmt->get_result()->fetch_assoc();
	        	return $user;
	        }else{
	        	return false;
	        }
		}

		public function saveUserLocation($uid, $email, $lat, $long, $ts){

			$stmt = $this->conn->prepare("INSERT INTO usersLocations(unique_id, email, latitude, longitude, 
				timestamp) VALUES(?,?,?,?,?)");

			if($stmt === false){
				die ("Mysql Error: " . $this->conn->error);
			}
			$stmt->bind_param("sssss", $uid, $email, $lat, $long, $ts);
			$result = $stmt->execute();
			$stmt->close();


			if(!$this->checkUserExists($email)){
				$stmt2 = $this->conn->prepare("INSERT INTO latestUserLocation(unique_id, email, latitude, longitude,timestamp) VALUES(?,?,?,?,?)");

				if($stmt2 === false){
					die ("Mysql Error: " . $this->conn->error);
				}

				$stmt2->bind_param("sssss", $uid, $email, $lat, $long, $ts);
				$result = $stmt2->execute();
				$stmt2->close();

			}
			else{
				$stmt2 = $this->conn->prepare("UPDATE latestUserLocation SET latitude = ?, longitude = ?, timestamp = ? WHERE email = ?");

				if($stmt2 === false){
					die ("Mysql Error: " . $this->conn->error);
				}

				$stmt2->bind_param("ssss", $lat, $long, $ts, $email);
				$result = $stmt2->execute();
				$stmt2->close();
			}

			return $result;
		}

		public function checkUserExists($em){

			$stmt = $this->conn->prepare("SELECT * FROM latestUserLocation WHERE email = ?");
			if ($stmt === FALSE) {
     			die ("Mysql Error: " . $this->conn->error);
			}
			$stmt->bind_param("s", $em);
			$stmt->execute();
			$user = $stmt->get_result()->fetch_assoc();
			$stmt->close();

			if($user){
				
				return $user;
			
			}else{

				return null;
			}
		}

		public function getUserLocation($em){

			$stmt = $this->conn->prepare("SELECT * FROM latestUserLocation WHERE email = ?");
			if ($stmt === FALSE) {
     			die ("Mysql Error: " . $this->conn->error);
			}
			$stmt->bind_param("s", $em);
			$stmt->execute();
			$userLocation = $stmt->get_result()->fetch_assoc();
			$stmt->close();

			if($userLocation){

				return $userLocation;
			}else{

				return null;
			}

		}
		public function getUser($em, $pw){

			$stmt = $this->conn->prepare("SELECT * FROM userDetails WHERE email = ?");
			if ($stmt === FALSE) {
     			die ("Mysql Error: " . $this->conn->error);
			}
			$stmt->bind_param("s", $em);
			$stmt->execute();
			$user = $stmt->get_result()->fetch_assoc();
			$stmt->close();

			if($user){

				$salt = $user['salt'];
				$encrypted_pw = $user['encrypted_password'];
			 	$hash = $this->checkHash($salt, $pw);
				if($encrypted_pw == $hash){
					return $user;
				}

			}else{

				return null;
			}
			
		}

		public function doesUserExist($email){
			$stmt = $this->conn->prepare("SELECT email from userDetails WHERE email = ?");
			if ($stmt === FALSE) {
    			die ("Mysql Error: " . $this->conn->error);
			}
			$stmt->bind_param("s", $email);
			$stmt->execute();
			$stmt->store_result();
			if($stmt -> num_rows>0){
				$stmt ->close();
				return true;
			}
			else{
				$stmt->close();
				return false;
			}
		}
		public function hash($pw){
			$salt = sha1(rand());
			$salt = substr($salt ,0,10);
			$encrypted = base64_encode(sha1($pw . $salt, true) . $salt);// COULD BE TYPO
			$hash = array("salt"=>$salt, "encrypted"=>$encrypted);
			return $hash;
		}
		public function checkHash($salt, $pass){
			$hash = base64_encode(sha1($pass . $salt,true). $salt);
			return $hash;
		}

		
	}
?>