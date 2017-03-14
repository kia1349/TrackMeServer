<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
	
	require_once 'include/DBFn.php';
	$db = new DBFn();
	$res = array("error"=> FALSE); //array that holds JSON response

	if(isset($_POST['username'])){
		$email = $_POST['username'];

		$userLocation = $db->getUserLocation($email);

		if($userLocation!=false){

			$res["error"] = FALSE;
			$res["uid"] = $userLocation["unique_id"];
            $res["userLocation"]["username"] = $userLocation["username"];
            $res["userLocation"]["latitude"] = $userLocation["latitude"];
            $res["userLocation"]["longitude"] = $userLocation["longitude"];
            $res["userLocation"]["timestamp"] = $userLocation["timestamp"];
            echo json_encode($res);
		}else{

			$res["error"] = TRUE;
			$res["error_msg"] = "No Tracking Details Available For User With Username ".$username;
			echo json_encode($res);
		}

	}else{
		$res["error"] = TRUE;
		$res["error_msg"] = "No Username Entered";
		echo json_encode($res);
	}
?>