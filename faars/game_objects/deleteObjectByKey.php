<?php
//function addUser($fullname,$username,$email,$password1,$password2,$game_ID,$form_ID,$agree,$activate,&$link,&$error_code)
include_once 'faars/misc/getGameObjectID.php';
include_once 'faars/misc/getGameInstanceID.php';

function deleteObjectByKey($gameKey,$gameInstanceKey,$gameObjectKey,&$db,&$arrayResponse)
{
	$arrayResponse = array();
	$arrayDetails = array();
	$arrayDetails["action"] = "deleteObjectByKey";
	$arrayDetails["objectKey"] = $gameObjectKey;
	$arrayDetails["gameKey"] = $gameKey;
	$arrayDetails["gameInstanceKey"] = $gameInstanceKey;
	//$gameObject_ID = 0;
	try{	
		
		getGameInstanceID($gameInstanceKey,$gameKey,$db,$arrayResponse);
		
		if($arrayResponse["status"]!=0){
			return $arrayResponse;
		}
		
		$gameInstance_ID = $arrayResponse["gameInstance_ID"];
		
		$arrayResponse = array();
		getGameObjectID($gameObjectKey,$gameInstance_ID,$db,$arrayResponse);
		
		if($arrayResponse["status"]!=0){
			return $arrayResponse;
		}
		
		$gameObject_ID = $arrayResponse["gameObject_ID"];
		
		$sql = "DELETE FROM `faars-rocketfuel`.object WHERE object.object_ID=:gameObject_ID AND object.gameInstance_ID=:gameInstance_ID";
		$stmt = $db->prepare($sql);
		$stmt->bindParam(":gameObject_ID", $gameObject_ID);
		$stmt->bindParam(":gameInstance_ID", $gameInstance_ID);
	    $stmt->execute();
	    
		if($stmt->rowCount()==0)
		{
			$arrayResponse = array();
			$arrayResponse["status"] = 9;
			$arrayResponse["message"] = "No game object entry found in database with given keys";
			$arrayResponse["details"] = $arrayDetails;
	    	return $arrayResponse;
		}
		
		$sql = "DELETE FROM `faars-rocketfuel`.objectAttribute WHERE objectAttribute.object_ID=:gameObject_ID";
		$stmt = $db->prepare($sql);
		$stmt->bindParam(":gameObject_ID", $gameObject_ID);
	    $stmt->execute();
		
		$sql = "DELETE FROM `faars-rocketfuel`.objectGroup WHERE objectGroup.object_ID=:gameObject_ID";
		$stmt = $db->prepare($sql);
		$stmt->bindParam(":gameObject_ID", $gameObject_ID);
	    $stmt->execute();
	    
	    $sql = "DELETE FROM `faars-rocketfuel`.objectCollection WHERE objectCollection.object_ID=:gameObject_ID";
		$stmt = $db->prepare($sql);
		$stmt->bindParam(":gameObject_ID", $gameObject_ID);
	    $stmt->execute();
		
		$arrayResponse = array();
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