<?php
include_once 'faars/misc/util.php';

function verifyUserDoesNotExist($gameInstance_ID,$username,$password,&$db,&$arrayResponse)
{
	$arrayResponse = array();
	$arrayDetails = array();
	$arrayDetails["action"] = "verifyUserExists";
	$arrayDetails["username"] = $username;
	$arrayDetails["password"] = $password;
	trim_value($username);
	trim_value($password);
	$sql = "SELECT object.objectKey,object.active,object.allowLogin FROM `faars-rocketfuel`.object WHERE object.username=:username 
			AND object.password=:password AND object.gameInstance_ID=:gameInstance_ID";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(":username", $username);
	$stmt->bindParam(":password", $password);
	$stmt->bindParam(":gameInstance_ID", $gameInstance_ID);
	$stmt->execute();
		    
	if($stmt->rowCount()>0)
	{
		$arrayResponse = array();
		$arrayResponse["status"] = 19;
		$arrayResponse["message"] = "Provided username and password value pair already exists";
		$arrayResponse["details"] = $arrayDetails;
	   	return $arrayResponse;
	}
		
	$arrayResponse = array();
	$arrayResponse["status"] = 0;
	$arrayResponse["details"] =$arrayDetails;
	return $arrayResponse;
	
}	
?>