<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
	
	require_once 'include/DBFn.php';
	$db = new DBFn();
	$res = array("error"=> FALSE); //array that holds JSON response

	if(isset($_POST['email'])&& isset($_POST['password'])){
		$email = $_POST['email'];
		$password = $_POST['password'];

		$user = $db->getUser($email,$password);

		if($user!=false){

			$res["error"] = FALSE;
			$res["uid"] = $user["unique_id"];
            $res["user"]["first_name"] = $user["first_name"];
            $res["user"]["surname"] = $user["surname"];
            $res["user"]["phone_no"] = $user["phone_no"];
            $res["user"]["email"] = $user["email"];
            $res["user"]["created_at"] = $user["created_at"];
            $res["user"]["updated_at"] = $user["updated_at"];
            echo json_encode($res);
		}else{

			$res["error"] = TRUE;
			$res["error_msg"] = "Invalid Login Credentials: ".$user;
			echo json_encode($res);
		}

	}else{
		$res["error"] = TRUE;
		$res["error_msg"] = "Missing Information";
		echo json_encode($res);
	}
?>