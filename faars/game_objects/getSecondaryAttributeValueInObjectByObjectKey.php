<?php
include_once 'faars/misc/util.php';
include_once 'faars/misc/getGameObjectID.php';
include_once 'faars/misc/getGameInstanceID.php';

function getSecondaryAttributeValueInObjectByObjectKey($gameKey,$gameInstanceKey,$gameObjectKey,$attributeKey,&$db,&$arrayResponse)
{
	$arrayResponse = array();
	//$gameInstance_ID = 0;
	$arrayDetails = array();
	$arrayDetails["action"] = "getSecondaryAttributeValueInObjectByObjectKey";
	$arrayDetails["objectKey"] = $gameObjectKey;
	$arrayDetails["gameKey"] = $gameKey;
	$arrayDetails["gameInstanceKey"] = $gameInstanceKey;
	$arrayDetails["attributeKey"] = $attributeKey;
	
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
		
		$sql = "SELECT objectAttribute.attributeKey,objectAttribute.value,objectAttribute.active FROM `faars-rocketfuel`.objectAttribute WHERE 
				objectAttribute.object_ID=:gameObject_ID AND objectAttribute.attributeKey=:attributeKey AND objectAttribute.active='1'";
		$stmt = $db->prepare($sql);
		$stmt->bindParam(":gameObject_ID", $gameObject_ID);
		$stmt->bindParam(":attributeKey", $attributeKey);
	    $stmt->execute();
	    
		if($stmt->rowCount()==0)
		{
			$arrayResponse = array();
			$arrayResponse["status"] = 10;
			$arrayResponse["message"] = "No game object attributes entry found in database with given keys";
			$arrayResponse["details"] = $arrayDetails;
	    	return $arrayResponse;
		}
		
		$arrayResponse = array();
		$gameObjectAttribute= $stmt->fetchAll(PDO::FETCH_OBJ);
		$gameObjectAttribute = json_encode($gameObjectAttribute);
		$gameObjectAttribute = json_decode($gameObjectAttribute,true);
		
		$arrayResponse["value"] = $gameObjectAttribute[0]["value"];
		
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