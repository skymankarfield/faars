<?php
include_once 'faars/game_objects/getAllGameObjectsByGroupKey.php';
include_once 'faars/events/catchEvent.php';
include_once 'faars/game_objects/deleteObjectByKey.php';

function runScheduledEvent($gameKey,$gameInstanceKey,$groupKey,&$db,&$arrayResponse)
{
	$arrayResponse = array();
	//$gameInstance_ID = 0;
	$arrayDetails = array();
	$arrayDetails["action"] = "runScheduledEvent";
	$arrayDetails["groupKey"] = $groupKey;
	$arrayDetails["gameKey"] = $gameKey;
	$arrayDetails["gameInstanceKey"] = $gameInstanceKey;
	
	try{
		
		//$gameObjectsArray = array();
		//$gameObjectsArray["gameObjects"] = array();
		
		$gameObjects = getAllGameObjectsByGroupKey($gameKey,$gameInstanceKey,$groupKey,$db,$arrayResponse);
		if($arrayResponse["status"]!=0){
			return $arrayResponse;
		}
		
		$arrayResponse = array();
		for($i=0;$i<count($gameObjects["gameObjects"]);$i++)
		{
			if($gameObjects["gameObjects"][$i]["status"]==0)
			{
				$arrayResponse = array();
				//array_push($gameObjectsArray["gameObjects"],performOperationByObjectKey($gameKey,$gameInstanceKey,$gameObjects["gameObjects"][$i]["objectKey"],$gameObject,$db,$arrayResponse));
				if(($gameObjects["gameObjects"][$i]["attributes"]["hour"]["value"] == date("H")) && ($gameObjects["gameObjects"][$i]["attributes"]["minute"]["value"] == date("i"))
					&& ($gameObjects["gameObjects"][$i]["attributes"]["month"]["value"] == date("n"))
					&& ($gameObjects["gameObjects"][$i]["attributes"]["day"]["value"] == date("j"))
					&& ($gameObjects["gameObjects"][$i]["attributes"]["year"]["value"] == date("Y")))
				{
					$localGameObject = array();
					$localGameObject["eventKey"] = $gameObjects["gameObjects"][$i]["attributes"]["eventKey"]["value"];
					$localGameObject["eventGeneratorKey"] = $gameObjects["gameObjects"][$i]["attributes"]["eventGeneratorKey"]["value"];
					$localGameObject["eventRecipientKey"] = $gameObjects["gameObjects"][$i]["attributes"]["eventRecipientKey"]["value"];
					$localGameObject = json_encode($localGameObject);
					$localGameObject = json_decode($localGameObject);
					catchEvent($gameKey,$gameInstanceKey,$localGameObject,$db,$arrayResponse);
					deleteObjectByKey($gameKey,$gameInstanceKey,$gameObjects["gameObjects"][$i]["objectKey"],$db,$arrayResponse);
				}
			}
			
		}
		
		//$arrayResponse = array();
		//$arrayResponse = $gameObjectsArray;
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