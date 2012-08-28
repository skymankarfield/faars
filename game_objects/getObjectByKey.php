<?php
//function addUser($fullname,$username,$email,$password1,$password2,$game_ID,$form_ID,$agree,$activate,&$link,&$error_code)
include_once 'faars/misc/getGameObjectID.php';
include_once 'faars/misc/getGameInstanceID.php';

function getObjectByKey($gameKey,$gameInstanceKey,$gameObjectKey,&$db,&$arrayResponse)
{
	$arrayResponse = array();
	$arrayDetails = array();
	$arrayDetails["action"] = "getObjectByKey";
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
		
		$sql = "SELECT object.fullObjectName,object.objectKey,object.username,object.password,object.currentStateKey,
				currentLat,currentLon,currentZone,allowLogin,transient,active FROM `faars-rocketfuel`.object WHERE 
				object.object_ID=:gameObject_ID AND object.gameInstance_ID=:gameInstance_ID";
		$stmt = $db->prepare($sql);
		$stmt->bindParam(":gameObject_ID", $gameObject_ID);
		$stmt->bindParam(":gameInstance_ID", $gameInstance_ID);
	    $stmt->execute();
	    
		if($stmt->rowCount()!=1)
		{
			$arrayResponse = array();
			$arrayResponse["status"] = 9;
			$arrayResponse["message"] = "No game object entry found in database with given keys";
			$arrayResponse["details"] = $arrayDetails;
	    	return $arrayResponse;
		}
		
		$gameObject = $stmt->fetchAll(PDO::FETCH_OBJ);
		
		$arrayResponse = array();
		$gameObject = json_encode($gameObject);
		$arrayResponse = json_decode($gameObject,true);
		$arrayResponse = $arrayResponse[0];
		
		$arrayResponse["currentGPSLocation"]["currentLat"] = $arrayResponse["currentLat"];
		$arrayResponse["currentGPSLocation"]["currentLon"] = $arrayResponse["currentLon"];
		unset($arrayResponse["currentLat"]);
		unset($arrayResponse["currentLon"]);
		$arrayResponse["loginInfo"]["username"] = $arrayResponse["username"];
		$arrayResponse["loginInfo"]["password"] = $arrayResponse["password"];
		unset($arrayResponse["username"]);
		unset($arrayResponse["password"]);
		
		$sql = "SELECT objectAttribute.attributeKey,objectAttribute.value,objectAttribute.active FROM `faars-rocketfuel`.objectAttribute WHERE 
				objectAttribute.object_ID=:gameObject_ID";
		$stmt = $db->prepare($sql);
		$stmt->bindParam(":gameObject_ID", $gameObject_ID);
	    $stmt->execute();
	    
		if($stmt->rowCount()==0)
		{
			$arrayResponse = array();
			$arrayResponse["status"] = 10;
			$arrayResponse["message"] = "No game object attributes entry found in database with given keys";
			$arrayResponse["details"] = $arrayDetails;
	    	return $arrayResponse;
		}
		
		$gameObjectAttributes = $stmt->fetchAll(PDO::FETCH_OBJ);
		
		$gameObjectAttributes = json_encode($gameObjectAttributes);
		$gameObjectAttributes = json_decode($gameObjectAttributes,true);
		for($i=0;$i<count($gameObjectAttributes);$i++)
		{
			$arrayResponse["attributes"][$gameObjectAttributes[$i]["attributeKey"]] = $gameObjectAttributes[$i];
		}
		//$arrayResponse = $gameObjectAttributes;
		
		$sql = "SELECT objectGroup.groupKey,objectGroup.active FROM `faars-rocketfuel`.objectGroup WHERE 
				objectGroup.object_ID=:gameObject_ID";
		$stmt = $db->prepare($sql);
		$stmt->bindParam(":gameObject_ID", $gameObject_ID);
	    $stmt->execute();
	    
		if($stmt->rowCount()==0)
		{
			$arrayResponse = array();
			$arrayResponse["status"] = 11;
			$arrayResponse["message"] = "No game object groups entry found in database with given keys";
			$arrayResponse["details"] = $arrayDetails;
	    	return $arrayResponse;
		}
		
		$gameObjectGroups = $stmt->fetchAll(PDO::FETCH_OBJ);
		
		$gameObjectGroups = json_encode($gameObjectGroups);
		$gameObjectGroups = json_decode($gameObjectGroups,true);
		for($i=0;$i<count($gameObjectGroups);$i++)
		{
			$arrayResponse["groups"][$gameObjectGroups[$i]["groupKey"]] = $gameObjectGroups[$i];
		}
		//$arrayResponse = $gameObjectAttributes;
		
		$sql = "SELECT objectCollection.collectionKey,objectCollection.value,objectCollection.active FROM `faars-rocketfuel`.objectCollection WHERE 
				objectCollection.object_ID=:gameObject_ID";
		$stmt = $db->prepare($sql);
		$stmt->bindParam(":gameObject_ID", $gameObject_ID);
	    $stmt->execute();
	    
		if($stmt->rowCount()==0)
		{
			$arrayResponse = array();
			$arrayResponse["status"] = 23;
			$arrayResponse["message"] = "No game object collections entry found in database with given keys";
			$arrayResponse["details"] = $arrayDetails;
	    	return $arrayResponse;
		}
		
		$gameObjectCollections = $stmt->fetchAll(PDO::FETCH_OBJ);
		
		$gameObjectCollections = json_encode($gameObjectCollections);
		$gameObjectCollections = json_decode($gameObjectCollections,true);
		for($i=0;$i<count($gameObjectCollections);$i++)
		{
			$arrayResponse["collections"][$gameObjectCollections[$i]["collectionKey"]] = $gameObjectCollections[$i];
			$arrayResponse["collections"][$gameObjectCollections[$i]["collectionKey"]]["value"] = explode("|",$arrayResponse["collections"][$gameObjectCollections[$i]["collectionKey"]]["value"]);
		}
		
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