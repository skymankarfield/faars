<?php
//function addUser($fullname,$username,$email,$password1,$password2,$game_ID,$form_ID,$agree,$activate,&$link,&$error_code)
include_once 'faars/misc/getGameInstanceID.php';
include_once 'faars/misc/util.php';
include_once 'faars/misc/verifyUserDoesNotExist.php';

function checkJSONObject($gameObject,&$arrayResponse)
{
	$arrayResponse = array();
	$arrayDetails = array();
	$arrayDetails["action"] = "checkJSONObject";
	$arrayDetails["objectKey"] = $gameObject->objectKey;
	
	$gameObject = json_encode($gameObject);
	$gameObject = json_decode($gameObject,true);
	array_walk_recursive($gameObject, 'trim_value'); 
	
	$existsAndNonEmptyArray = array();
	array_push($existsAndNonEmptyArray,"fullObjectName","objectKey","allowLogin","loginInfo","currentStateKey",
							"active","transient","currentGPSLocation","currentZone","attributes","groups","collections");
	
	checkJSONByLevel($gameObject, $existsAndNonEmptyArray, $existsAndNonEmptyArray, $arrayResponse);
	
	if($arrayResponse["status"]!=0)
	{
		return $arrayResponse;
	}
	
	$existsAndNonEmptyArray = array();
	array_push($existsAndNonEmptyArray,"attributeKey","value","active");
	
	for($i=0;$i<count($gameObject["attributes"]);$i++)
	{
		$arrayResponse = array();
		checkJSONByLevel($gameObject["attributes"][$i], $existsAndNonEmptyArray, $existsAndNonEmptyArray, $arrayResponse);
	
		if($arrayResponse["status"]!=0)
		{
			return $arrayResponse;
		}
	}
	
	$existsAndNonEmptyArray = array();
	array_push($existsAndNonEmptyArray,"groupKey","active");
	
	for($i=0;$i<count($gameObject["groups"]);$i++)
	{
		$arrayResponse = array();
		checkJSONByLevel($gameObject["groups"][$i], $existsAndNonEmptyArray, $existsAndNonEmptyArray, $arrayResponse);
	
		if($arrayResponse["status"]!=0)
		{
			return $arrayResponse;
		}
	}
	
	$existsArray = array();
	array_push($existsArray,"username","password");
	$nonEmptyArray = array();
	
	for($i=0;$i<count($gameObject["loginInfo"]);$i++)
	{
		$arrayResponse = array();
		checkJSONByLevel($gameObject["loginInfo"][$i], $existsArray, $nonEmptyArray, $arrayResponse);
	
		if($arrayResponse["status"]!=0)
		{
			return $arrayResponse;
		}
	}
	
	$existsArray = array();
	array_push($existsArray,"currentLat","currentLon");
	$nonEmptyArray = array();
	
	for($i=0;$i<count($gameObject["currentGPSLocation"]);$i++)
	{
		$arrayResponse = array();
		checkJSONByLevel($gameObject["currentGPSLocation"][$i], $existsArray, $nonEmptyArray, $arrayResponse);
	
		if($arrayResponse["status"]!=0)
		{
			return $arrayResponse;
		}
	}
	
	$existsAndNonEmptyArray = array();
	array_push($existsAndNonEmptyArray,"collectionKey","value","active");
	
	for($i=0;$i<count($gameObject["collections"]);$i++)
	{
		$arrayResponse = array();
		checkJSONByLevel($gameObject["collections"][$i], $existsAndNonEmptyArray, $existsAndNonEmptyArray, $arrayResponse);
	
		if($arrayResponse["status"]!=0)
		{
			return $arrayResponse;
		}
	}
	
	$arrayResponse = array();
	$arrayResponse["status"] = 0;
	$arrayResponse["details"] = $arrayDetails;
	return $arrayResponse;
}

function createObject($gameKey,$gameInstanceKey,$gameObject,&$db,&$arrayResponse)
{
	$arrayResponse = array();
	$gameInstance_ID = 0;
	$arrayDetails = array();
	$arrayDetails["action"] = "createObject";
	$arrayDetails["objectKey"] = $gameObject->objectKey;
	$arrayDetails["gameKey"] = $gameKey;
	$arrayDetails["gameInstanceKey"] = $gameInstanceKey;
	try{
	//perform all the checks here...
		checkJSONObject($gameObject,$arrayResponse);//if all checks were successful
		if($arrayResponse["status"]!=0){
			return $arrayResponse;
		}
		
		$arrayResponse = array();
		getGameInstanceID($gameInstanceKey,$gameKey,$db,$arrayResponse);
		
		if($arrayResponse["status"]!=0){
			return $arrayResponse;
		}
		
		$gameInstance_ID = $arrayResponse["gameInstance_ID"];
		
		if($gameObject->allowLogin==1)
		{
			verifyUserDoesNotExist($gameInstance_ID,$gameObject->loginInfo[0]->username,$gameObject->loginInfo[0]->password,$db,$arrayResponse);
			if($arrayResponse["status"]!=0){
				return $arrayResponse;
			}
		}
		
		$sql = "INSERT INTO `faars-rocketfuel`.object(fullObjectName,objectKey,username,password,gameInstance_ID,currentStateKey,currentLat,currentLon,
					currentZone,allowLogin,transient,active) VALUES(:fullObjectName,:objectKey,:username,:password,:gameInstance_ID,
					:currentStateKey,:currentLat,:currentLon,:currentZone,:allowLogin,:transient,:active)";
		$stmt = $db->prepare($sql);
		$stmt->bindParam(":fullObjectName", $gameObject->fullObjectName);
		$stmt->bindParam(":objectKey", $gameObject->objectKey);
		$stmt->bindParam(":username", $gameObject->loginInfo[0]->username);
		$stmt->bindParam(":password", $gameObject->loginInfo[0]->password);
	    $stmt->bindParam(":gameInstance_ID", $gameInstance_ID);
		$stmt->bindParam(":currentStateKey", $gameObject->currentStateKey);
		$stmt->bindParam(":currentLat", $gameObject->currentGPSLocation[0]->currentLat);
		$stmt->bindParam(":currentLon", $gameObject->currentGPSLocation[0]->currentLon);
		$stmt->bindParam(":currentZone", $gameObject->currentZone);
		$stmt->bindParam(":allowLogin", $gameObject->allowLogin);
		$stmt->bindParam(":transient", $gameObject->transient);
		$stmt->bindParam(":active", $gameObject->active);
	    $stmt->execute();
	    
		if($stmt->rowCount()!=1)
		{
			$arrayResponse = array();
			$arrayResponse["status"] = 3;
			$arrayResponse["message"] = "Error inserting game object into the database";
			$arrayResponse["details"] = $arrayDetails;
	    	return $arrayResponse;
		}
		
		$lastInsertedObjectID = $db->lastInsertId();
		
		$sql = "INSERT INTO `faars-rocketfuel`.objectAttribute(object_ID,attributeKey,value,active) VALUES(:object_ID,:attributeKey,:value,:active)";
		$stmt = $db->prepare($sql);
		
		for($i=0;$i<count($gameObject->attributes);$i++)
		{
			$stmt->execute(array(":object_ID"=>$lastInsertedObjectID,
								 ":attributeKey"=>$gameObject->attributes[$i]->attributeKey,
								 ":value"=>$gameObject->attributes[$i]->value,
								 ":active"=>$gameObject->attributes[$i]->active));
			if($stmt->rowCount()!=1)
			{
				$arrayResponse = array();
				$arrayResponse["status"] = 4;
				$arrayResponse["message"] = "Error inserting game object attributes into the database";
				$arrayResponse["details"] = $arrayDetails;
		    	return $arrayResponse;
			}
		}
		
		$sql = "INSERT INTO `faars-rocketfuel`.objectGroup(object_ID,groupKey,active) VALUES(:object_ID,:groupKey,:active)";
		$stmt = $db->prepare($sql);
		
		for($i=0;$i<count($gameObject->groups);$i++)
		{
			$stmt->execute(array(":object_ID"=>$lastInsertedObjectID,
								 ":groupKey"=>$gameObject->groups[$i]->groupKey,
								 ":active"=>$gameObject->groups[$i]->active));
			if($stmt->rowCount()!=1)
			{
				$arrayResponse = array();
				$arrayResponse["status"] = 5;
				$arrayResponse["message"] = "Error inserting game object groups into the database";
				$arrayResponse["details"] = $arrayDetails;
		    	return $arrayResponse;
			}
		}
		
		$sql = "INSERT INTO `faars-rocketfuel`.objectCollection(object_ID,collectionKey,value,active) VALUES(:object_ID,:collectionKey,:value,:active)";
		$stmt = $db->prepare($sql);
		
		for($i=0;$i<count($gameObject->collections);$i++)
		{
			$stmt->execute(array(":object_ID"=>$lastInsertedObjectID,
								 ":collectionKey"=>$gameObject->collections[$i]->collectionKey,
								 ":value"=> implode("|", $gameObject->collections[$i]->value),
								 ":active"=>$gameObject->collections[$i]->active));
			if($stmt->rowCount()!=1)
			{
				$arrayResponse = array();
				$arrayResponse["status"] = 22;
				$arrayResponse["message"] = "Error inserting game object collections into the database";
				$arrayResponse["details"] = $arrayDetails;
		    	return $arrayResponse;
			}
		}
		
		$arrayResponse = array();
		$arrayResponse["status"] = 0;
		$arrayResponse["details"] =$arrayDetails;
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