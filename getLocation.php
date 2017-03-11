<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
	
	require_once 'include/DBFn.php';
	$db = new DBFn();
	$res = array("error"=> FALSE); //array that holds JSON response

	if(isset($_POST['email'])){
		$email = $_POST['email'];

		$userLocation = $db->getUserLocation($email);

		if($userLocation!=false){

			$res["error"] = FALSE;
			$res["uid"] = $userLocation["unique_id"];
            $res["userLocation"]["email"] = $userLocation["email"];
            $res["userLocation"]["latitude"] = $userLocation["latitude"];
            $res["userLocation"]["longitude"] = $userLocation["longitude"];
            $res["userLocation"]["timestamp"] = $userLocation["timestamp"];
            echo json_encode($res);
		}else{

			$res["error"] = TRUE;
			$res["error_msg"] = "No Tracking Details Available For User With Email ".$email;
			echo json_encode($res);
		}

	}else{
		$res["error"] = TRUE;
		$res["error_msg"] = "No Email Address Entered";
		echo json_encode($res);
	}
?>