<?php
include_once 'faars/misc/util.php';
include_once 'faars/misc/getGameObjectID.php';
include_once 'faars/misc/getGameInstanceID.php';

function collectionExistsInObjectByObjectKey($gameKey,$gameInstanceKey,$gameObjectKey,$collectionKey,&$db,&$arrayResponse)
{
	$arrayResponse = array();
	//$gameInstance_ID = 0;
	$arrayDetails = array();
	$arrayDetails["action"] = "collectionExistsInObjectByObjectKey";
	$arrayDetails["objectKey"] = $gameObjectKey;
	$arrayDetails["gameKey"] = $gameKey;
	$arrayDetails["gameInstanceKey"] = $gameInstanceKey;
	$arrayDetails["collectionKey"] = $collectionKey;
	
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
		
		$sql = "SELECT objectCollection.collectionKey,objectCollection.value,objectCollection.active FROM `faars-rocketfuel`.objectCollection WHERE 
				objectCollection.object_ID=:gameObject_ID AND objectCollection.collectionKey=:collectionKey AND objectCollection.active='1'";
		$stmt = $db->prepare($sql);
		$stmt->bindParam(":gameObject_ID", $gameObject_ID);
		$stmt->bindParam(":collectionKey", $collectionKey);
	    $stmt->execute();
	    
		$arrayResponse = array();
	    $arrayResponse["status"] = 0;
		$arrayResponse["details"] = $arrayDetails;
		
		if($stmt->rowCount()!=1)
		{
			$arrayResponse["collectionExists"] = "false";
	    	
		}else
		{
			$arrayResponse["collectionExists"] = "true";
		}
		
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