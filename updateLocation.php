<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'include/DBFn.php';
$db = new DBFn();
$res = array("error"=> FALSE); //array that holds JSON response

if (isset($_POST['uid']) && isset($_POST['email'])&& isset($_POST['latitude'])&& isset($_POST['longitude'])&& isset($_POST['timestamp'])){

	$uid = $_POST['uid'];
	$email = $_POST['email'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $timestamp = $_POST['timestamp'];
    	
		$updatedLocation = $db->saveUserLocation($uid, $email, $latitude, $longitude, $timestamp);

		if($updatedLocation){
			$res["error"] = FALSE;
            echo json_encode($res);
		}else{
			$res["error"] = TRUE;
			$res["error_msg"] = "Unknown Error Occurred";
			echo json_encode($updatedLocation);
		}
}else{
	$res["error"] = TRUE;
	$res["error_msg"] = "Missing Information";
	echo json_encode($res);
}
?>