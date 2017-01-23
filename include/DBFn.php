<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

	class DBFn{
		private $conn;

		/*
		 *	Constructor for database functions class that connects to database
		 */
		function _construct(){
			require_once 'DBConnect.php';
			$db = new DBConnect();
        	$this->conn = $db->connect();
		}
		/**/
		function _destruct(){


		}

		public function saveUser($fname, $surname, $email, $phno, $pw){
			$uuid = uniqid('', true);
			$hash = $this->hash($pw);
        	$encrypted_pw = $hash["encrypted"];
        	$salt = $hash["salt"];

        	$stmt = $this-> conn -> prepare("INSERT INTO userDetails(unique_id, first_name, surname, email, phone_no, encrypted_password, salt, created_at) VALUES(?,?,?,?,?,?,?,?,NOW())");
        	$stmt-> bind_param("ssss", $uuid,
        		$fname, $surname, $email, $phno, $encrypted_pw, $salt);
        	$result = $stmt->execute();
        	$stmt->close();

        	if($result){
	        	$stmt = $this-> conn -> prepare("SELECT * FROM userDetails WHERE email =?"); //SQL Statment object
	        	$stmt -> bind_param("s", $email);
	        	$stmt->execute();
	        	$user = $stmt->get_result()->fetch_assoc();
	        	return $user;

	        }else{
	        	return false;
	        }


		}

		public function getUser($em, $pw){
			$stmt = $this->conn->prepare("SELECT * FROM userDetails WHERE email = ?");
			$stmt->bind_param("s",$em);

			if($stmt->execute()){
				$user = $stmt->get_result()->fetch_assoc();
				$stmt ->close();

				/*
				 * Running verification on user password
				*/

				$salt = $user['salt'];
				$encrypted_pw = $user['encrypted_password'];
				$hash = $this->chechHash($salt, $pw);

				if($encrypted_pw == $hash){
					return $user;
				}

			}else{

				return null;
			}
		}

		public function doesUserExist($em){

			$stmt = $this->conn->prepare("SELECT email from userDetails WHERE email = ?");
			$stmt->bind_param("s",$em);
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
			$encrypted = base64_encode(sha1($password . $salt, true));// COULD BE TYPO
			$hash = array("salt"=>$salt, "encrypted"=>$encrypted);
			return $hash;
		}

		public function checkHash($salt, $pass){
			$hash = base64_encode(sha1($pass . $salt,true). $salt);
			return $hash;
		}

	}


?>