<?php
include_once 'faars/operations/performOperationByObjectKey.php';
//include_once 'faars/misc/getGameInstanceID.php';
include_once 'faars/game_objects/getAllGameObjectsByGameInstanceKey.php';

function performOperationByGameInstanceKey($gameKey,$gameInstanceKey,$gameObject,&$db,&$arrayResponse)
{
	$arrayResponse = array();
	//$gameInstance_ID = 0;
	$arrayDetails = array();
	$arrayDetails["action"] = "performOperationByGameInstanceKey";
	$arrayDetails["gameKey"] = $gameKey;
	$arrayDetails["gameInstanceKey"] = $gameInstanceKey;
	$arrayDetails["attributeKey"] = $gameObject->attributeKey;
	try{
	//perform all the checks here...
		checkJSONOperation($gameObject,$arrayResponse);//if all checks were successful
		if($arrayResponse["status"]!=0){
			return $arrayResponse;
		}

		$gameObjectsArray = array();
		$gameObjectsArray["gameObjects"] = array();
		
		$gameObjects = getAllGameObjectsByGameInstanceKey($gameKey,$gameInstanceKey,$db,$arrayResponse);
		if($arrayResponse["status"]!=0){
			return $arrayResponse;
		}
		
		for($i=0;$i<count($gameObjects["gameObjects"]);$i++)
		{
			if($gameObjects["gameObjects"][$i]["status"]==0)
			{
				$arrayResponse = array();
				array_push($gameObjectsArray["gameObjects"],performOperationByObjectKey($gameKey,$gameInstanceKey,$gameObjects["gameObjects"][$i]["objectKey"],$gameObject,$db,$arrayResponse));
			}
			//if($arrayResponse["status"]!=0)
			//{
				//return $arrayResponse;
			//}
		}
		
		$arrayResponse = array();
		$arrayResponse = $gameObjectsArray;
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