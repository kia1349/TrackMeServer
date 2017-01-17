<?php

	class DBFn{
		private $conn;

		/*
		 *	Constructor for database functions class that connects to database
		 */
		function _construct(){
			require_once 'DBConnect.php';
			$db = new Db_Connect();
        	$this->conn = $db->connect();
		}


		/**/
		function _destruct(){


		}

		function saveUser($name, $email, $pw){
			$uuid = uniqid('', true);
			$hash = $this->hashSSHA($pw);
        	$encrypted_pw = $hash["encrypted"];
        	$salt = $hash["salt"];


		}

	}


?>