<?php
include_once 'faars/game_objects/createObject.php';
include_once 'faars/game_objects/deleteObjectByKey.php';

function updateObjectByKey($gameKey,$gameInstanceKey,$gameObjectKey,$gameObject,&$db,&$arrayResponse)
{
	$arrayResponse = array();
	$arrayDetails = array();
	$arrayDetails["action"] = "updateObjectByKey";
	$arrayDetails["objectKey"] = $gameObjectKey;
	$arrayDetails["gameKey"] = $gameKey;
	$arrayDetails["gameInstanceKey"] = $gameInstanceKey;
	
	try{
	//perform all the checks here...
		checkJSONObject($gameObject,$arrayResponse);//if all checks were successful
		if($arrayResponse["status"]!=0){
			return $arrayResponse;
		}
		
		$arrayResponse = array();
		deleteObjectByKey($gameKey,$gameInstanceKey,$gameObjectKey,$db,$arrayResponse);
		if($arrayResponse["status"]!=0){
			return $arrayResponse;
		}
		
		$arrayResponse = array();
		createObject($gameKey,$gameInstanceKey,$gameObject,$db,$arrayResponse);
		if($arrayResponse["status"]!=0){
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