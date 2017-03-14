<?php

require_once 'include/DBFn.php';
$db = new DBFn();
$res = array("error"=> FALSE); //array that holds JSON response

if (isset($_POST['first_name']) && isset($_POST['surname'])&& isset($_POST['email'])&& isset($_POST['username'])&& isset($_POST['password'])){

	if(isset($_POST['phone_no'])){

		$phno = $_POST['phone_no'];
	}else{

		$phno = null;
	}

	if(isset($_POST['type'])){

		$type = $_POST['type'];
	}else{

		$type = null;
	}

	$first_name = $_POST['first_name'];
	$surname = $_POST['surname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    

    if($db->doesUserExist($email)){

    	$res["error"] = TRUE;
    	$res["error_msg"] = "Account already exists for email" . $email;
    	
    }else if($db->isUsernameUsed($username)){

    	$res["error"] = TRUE;
    	$res["error_msg"] = "Account already exists for username" . $username;
    	
    }
    else{
    	
		$user = $db->saveUser($first_name, $surname, $email, $username, $phno, $password, $type);

		if($user){

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