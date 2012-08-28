<?php
include_once 'faars/misc/util.php';
include_once 'faars/misc/getGameObjectID.php';
include_once 'faars/misc/getGameInstanceID.php';

function checkJSONCollection($gameObject,&$arrayResponse)
{
	$arrayResponse = array();
	$arrayDetails = array();
	$arrayDetails["action"] = "checkJSONCollection";
	
	$gameObject = json_encode($gameObject);
	$gameObject = json_decode($gameObject,true);
	array_walk_recursive($gameObject, 'trim_value'); 
	
	$existsAndNonEmptyArray = array();
	array_push($existsAndNonEmptyArray,"value","active","collectionKey");
	
	checkJSONByLevel($gameObject, $existsAndNonEmptyArray, $existsAndNonEmptyArray, $arrayResponse);
	
	if($arrayResponse["status"]!=0)
	{
		return $arrayResponse;
	}
	
	$arrayResponse = array();
	$arrayResponse["status"] = 0;
	$arrayResponse["details"] = $arrayDetails;
	return $arrayResponse;
}

function addCollectionByObjectKey($gameKey,$gameInstanceKey,$gameObjectKey,$gameObject,&$db,&$arrayResponse)
{
	$arrayResponse = array();
	//$gameInstance_ID = 0;
	$arrayDetails = array();
	$arrayDetails["action"] = "addCollectionByObjectKey";
	$arrayDetails["objectKey"] = $gameObjectKey;
	$arrayDetails["gameKey"] = $gameKey;
	$arrayDetails["gameInstanceKey"] = $gameInstanceKey;
	$arrayDetails["collectionKey"] = $gameObject->collectionKey;
	try{
	//perform all the checks here...
		checkJSONCollection($gameObject,$arrayResponse);//if all checks were successful
		if($arrayResponse["status"]!=0){
			return $arrayResponse;
		}
		
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
		
		$sql = "INSERT INTO `faars-rocketfuel`.objectCollection(object_ID,collectionKey,value,active) VALUES(:object_ID,:collectionKey,:value,:active)";
		$stmt = $db->prepare($sql);
		
		$valueArray = implode("|", $gameObject->value);
		
		$stmt->bindParam(":object_ID", $gameObject_ID);
		$stmt->bindParam(":collectionKey", $gameObject->collectionKey);
		$stmt->bindParam(":value", $valueArray);
		$stmt->bindParam(":active", $gameObject->active);
		$stmt->execute();
	    
		if($stmt->rowCount()!=1)
		{
			$arrayResponse = array();
			$arrayResponse["status"] = 22;
			$arrayResponse["message"] = "Error inserting game object collections into the database";
			$arrayResponse["details"] = $arrayDetails;
		    return $arrayResponse;
		}
		
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