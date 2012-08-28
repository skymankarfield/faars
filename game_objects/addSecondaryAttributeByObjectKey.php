<?php
include_once 'faars/misc/util.php';
include_once 'faars/misc/getGameObjectID.php';
include_once 'faars/misc/getGameInstanceID.php';

function checkJSONSecondaryAttribute($gameObject,&$arrayResponse)
{
	$arrayResponse = array();
	$arrayDetails = array();
	$arrayDetails["action"] = "checkJSONSecondaryAttribute";
	
	$gameObject = json_encode($gameObject);
	$gameObject = json_decode($gameObject,true);
	array_walk_recursive($gameObject, 'trim_value'); 
	
	$existsAndNonEmptyArray = array();
	array_push($existsAndNonEmptyArray,"value","active","attributeKey");
	
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

function addSecondaryAttributeByObjectKey($gameKey,$gameInstanceKey,$gameObjectKey,$gameObject,&$db,&$arrayResponse)
{
	$arrayResponse = array();
	//$gameInstance_ID = 0;
	$arrayDetails = array();
	$arrayDetails["action"] = "addSecondaryAttributeByObjectKey";
	$arrayDetails["objectKey"] = $gameObjectKey;
	$arrayDetails["gameKey"] = $gameKey;
	$arrayDetails["gameInstanceKey"] = $gameInstanceKey;
	$arrayDetails["attributeKey"] = $gameObject->attributeKey;
	try{
	//perform all the checks here...
		checkJSONSecondaryAttribute($gameObject,$arrayResponse);//if all checks were successful
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
		
		$sql = "INSERT INTO `faars-rocketfuel`.objectAttribute(object_ID,attributeKey,value,active) VALUES(:object_ID,:attributeKey,:value,:active)";
		$stmt = $db->prepare($sql);
		
		$stmt->bindParam(":object_ID", $gameObject_ID);
		$stmt->bindParam(":attributeKey", $gameObject->attributeKey);
		$stmt->bindParam(":value", $gameObject->value);
		$stmt->bindParam(":active", $gameObject->active);
		$stmt->execute();
	    
		if($stmt->rowCount()!=1)
		{
			$arrayResponse = array();
			$arrayResponse["status"] = 4;
			$arrayResponse["message"] = "Error inserting game object attributes into the database";
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