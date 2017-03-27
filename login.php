<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
	
	require_once 'include/DBFn.php';
	$db = new DBFn();
	$res = array("error"=> FALSE); //array that holds JSON response

	if(isset($_POST['username'])&& isset($_POST['password'])){
		$username = $_POST['username'];
		$password = $_POST['password'];

		$user = $db->getUser($username,$password);

		if($user!=false){

			$res["error"] = FALSE;
			$res["uid"] = $user["unique_id"];
            $res["user"]["first_name"] = $user["first_name"];
            $res["user"]["surname"] = $user["surname"];
            $res["user"]["phone_no"] = $user["phone_no"];
            $res["user"]["email"] = $user["email"];
            $res["user"]["username"] = $user["username"];
            $res["user"]["type"] = $user["type"];           
            $res["user"]["created_at"] = $user["created_at"];
            $res["user"]["updated_at"] = $user["updated_at"];
            echo json_encode($res);
		}else{

			$res["error"] = TRUE;
			$res["error_msg"] = "Invalid Login Credentials.";
			echo json_encode($res);
		}

	}else{
		$res["error"] = TRUE;
		$res["error_msg"] = "Missing Information";
		echo json_encode($res);
	}
?>