<?php
	class DBconnect{
		private $conn;

		public function connect(){
			require_once 'DBConfig.php';

      	    $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

      	    if ($this->conn->connect_error) {


      	    	echo '<script>console.log("Connection Rejected")</script>';
    		 	
			}
			else{
				echo '<script>console.log("Conn Successful")</script>';
			} 
      	    return $this->conn;
		}
	}
?>
