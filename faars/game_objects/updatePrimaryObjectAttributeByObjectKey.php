<?php
include_once 'faars/misc/util.php';
include_once 'faars/misc/getGameObjectID.php';
include_once 'faars/misc/getGameInstanceID.php';
include_once 'faars/game_objects/getObjectByKey.php';

function checkJSONAttributeUpdate($gameObject,&$arrayResponse)
{
	$arrayResponse = array();
	$arrayDetails = array();
	$arrayDetails["action"] = "checkJSONAttributeUpdate";
	
	$gameObject = json_encode($gameObject);
	$gameObject = json_decode($gameObject,true);
	array_walk_recursive($gameObject, 'trim_value'); 
	
	$existsAndNonEmptyArray = array();
	array_push($existsAndNonEmptyArray,"value","operation","attributeKey");
	
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

function updatePrimaryObjectAttributeByObjectKey($gameKey,$gameInstanceKey,$gameObjectKey,$gameObject,&$db,&$arrayResponse)
{
	$arrayResponse = array();
	//$gameInstance_ID = 0;
	$arrayDetails = array();
	$arrayDetails["action"] = "updatePrimaryObjectAttributeByObjectKey";
	$arrayDetails["objectKey"] = $gameObjectKey;
	$arrayDetails["gameKey"] = $gameKey;
	$arrayDetails["gameInstanceKey"] = $gameInstanceKey;
	$arrayDetails["attributeKey"] = $gameObject->attributeKey;
	try{
	//perform all the checks here...
		checkJSONAttributeUpdate($gameObject,$arrayResponse);//if all checks were successful
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
		
		if($gameObject->operation!="+" && $gameObject->operation!="-" && $gameObject->operation!="/" && $gameObject->operation!="*"
			&& $gameObject->operation!="set")
		{
			$arrayResponse = array();
			$arrayResponse["status"] = 15;
			$arrayResponse["message"] = "No valid operation";
			$arrayResponse["details"] = $arrayDetails;
	    	return $arrayResponse;
		}
		
		$arrayResponse = array();
		$object = getObjectByKey($gameKey,$gameInstanceKey,$gameObjectKey,$db,$arrayResponse);
		if($arrayResponse["status"]!=0){
			return $arrayResponse;
		}
		
		if(!array_key_exists($gameObject->attributeKey, $object))
		{
			$arrayResponse = array();
			$arrayResponse["status"] = 14;
			$arrayResponse["message"] = "No game object attribute entry found in database with given keys";
			$arrayResponse["details"] = $arrayDetails;
	    	return $arrayResponse;
		}
		
		if((!is_numeric($gameObject->value) || !is_numeric($object[$gameObject->attributeKey])) && ($gameObject->operation=="+"
			|| $gameObject->operation=="-" || $gameObject->operation=="/" || $gameObject->operation=="*"))
		{
			$arrayResponse = array();
			$arrayResponse["status"] = 16;
			$arrayResponse["message"] = "Values are not permitted for the requested operation";
			$arrayResponse["details"] = $arrayDetails;
	    	return $arrayResponse;
		}
		
		$newValue = 0;
		
		switch($gameObject->operation)
		{
			case '+':
				
				$newValue = $object[$gameObject->attributeKey] + $gameObject->value;
				
				break;
				
			case '-':
				
				$newValue = $object[$gameObject->attributeKey] - $gameObject->value;
				
				break;
				
			case '/':
				
				$newValue = ($object[$gameObject->attributeKey] / $gameObject->value);//.".".($gameObjectAttribute[0]["value"] % $gameObject->value);
				
				break;
				
			case '*':
				
				$newValue = $object[$gameObject->attributeKey] * $gameObject->value;
				
				break;
				
			case 'set':
				
				$newValue = $gameObject->value; 
				
				break;
				
			default:
				
				break;
		}
		
		$sql = "UPDATE `faars-rocketfuel`.object SET object.".$gameObject->attributeKey."=:newValue WHERE 
				object.object_ID=:gameObject_ID AND object.gameInstance_ID=:gameInstance_ID";
		$stmt = $db->prepare($sql);
		$stmt->bindParam(":newValue", $newValue);
		$stmt->bindParam(":gameObject_ID", $gameObject_ID);
		$stmt->bindParam(":gameInstance_ID", $gameInstance_ID);
	    $stmt->execute();
	    
		if($stmt->rowCount()==0)
		{
			$arrayResponse = array();
			$arrayResponse["status"] = 17;
			$arrayResponse["message"] = "Update operation was not successful, or there is nothing to update";
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