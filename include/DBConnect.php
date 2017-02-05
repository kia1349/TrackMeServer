<?php
	class DBconnect{
		private $conn;

		public function connect(){
			require_once 'DBConfig.php';

      	    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

      	    if ($conn->connect_error) {
 
 
       	    	echo '<script>console.log("Unable to connect to database")</script>';
     		 	
 		}

      	    return $conn;
		}
	}
?>
