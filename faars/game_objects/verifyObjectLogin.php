<?php
include_once 'faars/misc/util.php';
include_once 'faars/misc/getGameInstanceID.php';

function verifyObjectLogin($gameKey,$gameInstanceKey,$username,$password,&$db,&$arrayResponse)
{
	trim_value($username);
	trim_value($password);
	$arrayResponse = array();
	$arrayDetails = array();
	$arrayDetails["action"] = "verifyObjectLogin";
	$arrayDetails["username"] = $username;
	$arrayDetails["password"] = $password;
	$arrayDetails["gameKey"] = $gameKey;
	$arrayDetails["gameInstanceKey"] = $gameInstanceKey;
	//$gameObject_ID = 0;
	try{	
		
		getGameInstanceID($gameInstanceKey,$gameKey,$db,$arrayResponse);
		
		if($arrayResponse["status"]!=0){
			return $arrayResponse;
		}
		
		$gameInstance_ID = $arrayResponse["gameInstance_ID"];
		
		$sql = "SELECT object.objectKey,object.active,object.allowLogin FROM `faars-rocketfuel`.object WHERE object.username=:username 
				AND object.password=:password AND object.gameInstance_ID=:gameInstance_ID";
		$stmt = $db->prepare($sql);
		$stmt->bindParam(":username", $username);
		$stmt->bindParam(":password", $password);
		$stmt->bindParam(":gameInstance_ID", $gameInstance_ID);
	    $stmt->execute();
	    
		if($stmt->rowCount()!=1)
		{
			$arrayResponse = array();
			$arrayResponse["status"] = 18;
			$arrayResponse["message"] = "Game-Object not found with given username and password";
			$arrayResponse["details"] = $arrayDetails;
	    	return $arrayResponse;
		}
		
		$gameObject = $stmt->fetchAll(PDO::FETCH_OBJ);
		
		$arrayResponse = array();
		$gameObject = json_encode($gameObject);
		$arrayResponse = json_decode($gameObject,true);
		$arrayResponse = $arrayResponse[0];
		
		$arrayResponse["objectKey"] = $arrayResponse["objectKey"];
		$arrayResponse["active"] = $arrayResponse["active"];
		$arrayResponse["allowLogin"] = $arrayResponse["allowLogin"];
		
		$arrayResponse["status"] = 0;
		$arrayResponse["details"] = $arrayDetails;
	    return $arrayResponse;
	
	}catch(PDOException $e) {
		$arrayResponse = array();
		$arrayResponse["status"] = 1;
		$arrayResponse["message"] = $e->getMessage();
		$arrayResponse["details"] = $arrayDetails;
	    return $arrayResponse;
    }
	
}
?>