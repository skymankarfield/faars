<?php
include_once 'faars/misc/util.php';
include_once 'faars/misc/getGameObjectID.php';
include_once 'faars/misc/getGameInstanceID.php';

function secondaryAttributeExistsInObjectByObjectKey($gameKey,$gameInstanceKey,$gameObjectKey,$attributeKey,&$db,&$arrayResponse)
{
	$arrayResponse = array();
	//$gameInstance_ID = 0;
	$arrayDetails = array();
	$arrayDetails["action"] = "secondaryAttributeExistsInObjectByObjectKey";
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
	    
		$arrayResponse = array();
	    $arrayResponse["status"] = 0;
		$arrayResponse["details"] = $arrayDetails;
		
		if($stmt->rowCount()!=1)
		{
			$arrayResponse["attributeExists"] = "false";
	    	
		}else
		{
			$arrayResponse["attributeExists"] = "true";
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