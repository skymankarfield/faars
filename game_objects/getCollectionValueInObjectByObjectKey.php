<?php
include_once 'faars/misc/util.php';
include_once 'faars/misc/getGameObjectID.php';
include_once 'faars/misc/getGameInstanceID.php';

function getCollectionValueInObjectByObjectKey($gameKey,$gameInstanceKey,$gameObjectKey,$collectionKey,&$db,&$arrayResponse)
{
	$arrayResponse = array();
	//$gameInstance_ID = 0;
	$arrayDetails = array();
	$arrayDetails["action"] = "getCollectionValueInObjectByObjectKey";
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
	    
		if($stmt->rowCount()==0)
		{
			$arrayResponse = array();
			$arrayResponse["status"] = 23;
			$arrayResponse["message"] = "No game object collections entry found in database with given keys";
			$arrayResponse["details"] = $arrayDetails;
	    	return $arrayResponse;
		}
		
		$arrayResponse = array();
		$gameObjectCollection = $stmt->fetchAll(PDO::FETCH_OBJ);
		$gameObjectCollection = json_encode($gameObjectCollection);
		$gameObjectCollection = json_decode($gameObjectCollection,true);
		
		$arrayResponse["value"] = explode("|",$gameObjectCollection[0]["value"]);
		
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