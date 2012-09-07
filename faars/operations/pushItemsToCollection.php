<?php
include_once 'faars/misc/util.php';
include_once 'faars/misc/getGameObjectID.php';
include_once 'faars/misc/getGameInstanceID.php';

function checkJSONItemValue($gameObject,&$arrayResponse)
{
	$arrayResponse = array();
	$arrayDetails = array();
	$arrayDetails["action"] = "checkJSONItemValue";
	
	$gameObject = json_encode($gameObject);
	$gameObject = json_decode($gameObject,true);
	array_walk_recursive($gameObject, 'trim_value'); 
	
	$existsAndNonEmptyArray = array();
	array_push($existsAndNonEmptyArray,"value");
	
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

function pushItemsToCollection($gameKey,$gameInstanceKey,$gameObjectKey,$collectionKey,$gameObject,&$db,&$arrayResponse)
{
	$arrayResponse = array();
	//$gameInstance_ID = 0;
	$arrayDetails = array();
	$arrayDetails["action"] = "pushItemToCollection";
	$arrayDetails["objectKey"] = $gameObjectKey;
	$arrayDetails["gameKey"] = $gameKey;
	$arrayDetails["gameInstanceKey"] = $gameInstanceKey;
	$arrayDetails["collectionKey"] = $collectionKey;
	try{
	//perform all the checks here...
		checkJSONItemValue($gameObject,$arrayResponse);//if all checks were successful
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
		
		$sql = "SELECT objectCollection.collectionKey,objectCollection.value,objectCollection.active FROM `faars-rocketfuel`.objectCollection WHERE 
				objectCollection.object_ID=:gameObject_ID AND objectCollection.collectionKey=:collectionKey AND objectCollection.active='1'";
		$stmt = $db->prepare($sql);
		$stmt->bindParam(":gameObject_ID", $gameObject_ID);
		$stmt->bindParam(":collectionKey", $collectionKey);
	    $stmt->execute();
	    
		if($stmt->rowCount()!=1)
		{
			$arrayResponse = array();
			$arrayResponse["status"] = 23;
			$arrayResponse["message"] = "No game object collections entry found in database with given keys";
			$arrayResponse["details"] = $arrayDetails;
	    	return $arrayResponse;
		}
		
		$gameObjectCollection = $stmt->fetchAll(PDO::FETCH_OBJ);
		$gameObjectCollection = json_encode($gameObjectCollection);
		$gameObjectCollection = json_decode($gameObjectCollection,true);
		$finalArray = $gameObjectCollection[0]["value"];
		$finalArray = explode("|",$finalArray);
		$gameObject = json_encode($gameObject);
		$gameObject = json_decode($gameObject,true);
		
		for($i=0;$i<count($gameObject["value"]);$i++)
		{
			$finalArray[] = $gameObject["value"][$i];
		}
		
		$sql = "UPDATE `faars-rocketfuel`.objectCollection SET objectCollection.value=:value WHERE objectCollection.object_ID=:object_ID
					AND objectCollection.collectionKey=:collectionKey AND objectCollection.active='1'";
		$stmt = $db->prepare($sql);
		
		$valueArray = implode("|", $finalArray);
		
		$stmt->bindParam(":object_ID", $gameObject_ID);
		$stmt->bindParam(":collectionKey", $collectionKey);
		$stmt->bindParam(":value", $valueArray);
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