<?php

require_once 'include/DBFn.php';
$db = new DBFn();
$res = array("error"=> FALSE); //array that holds JSON response

if (isset($_POST['uid']) && isset($_POST['email'])&& isset($_POST['latitude'])&& isset($_POST['longitude'])&& isset($_POST['timestamp'])){

	$uid = $_POST['uid'];
	$email = $_POST['email'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $timestamp = $_POST['timestamp'];

    if(!$db->doesUserExist($email)){

    	$res["error"] = TRUE;
    	$res["error_msg"] = "Account already exists for email" . $email;
    	
    }else{
    	
		$updatedLocation = $db->saveUserLocation($first_name, $surname, $email, $phno, $password);

		if($updatedLocation){

			$res["error"] = FALSE;
            echo json_encode($res);
            
		}else{
			$res["error"] = TRUE;
			$res["error_msg"] = "Unknown Error Occurred";
			echo json_encode($res);

		}
    }
  
}else{
	$res["error"] = TRUE;
	$res["error_msg"] = "Missing Information";
	echo json_encode($res);

}

?>