<?php
	class DBconnect{
		private $conn;

		public function connect(){
			require_once 'DBConfig.php';

      	    $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

      	    return $this->conn;
		}
	}
?>
