<?php
//function addUser($fullname,$username,$email,$password1,$password2,$game_ID,$form_ID,$agree,$activate,&$link,&$error_code)
include_once 'faars/game_objects/deleteObjectByKey.php';
//include_once 'faars/misc/getGameInstanceID.php';
include_once 'faars/game_objects/getAllGameObjectsByGroupKey.php';

function deleteAllGameObjectsByGroupKey($gameKey,$gameInstanceKey,$groupKey,&$db,&$arrayResponse)
{
	$arrayResponse = array();
	$arrayDetails = array();
	$arrayDetails["action"] = "deleteAllGameObjectsByGroupKey";
	$arrayDetails["groupKey"] = $groupKey;
	$arrayDetails["gameKey"] = $gameKey;
	$arrayDetails["gameInstanceKey"] = $gameInstanceKey;
	//$gameObject_ID = 0;
	try{	
		
		$gameObjectsArray = array();
		$gameObjectsArray["gameObjects"] = array();
		
		$gameObjects = getAllGameObjectsByGroupKey($gameKey,$gameInstanceKey,$groupKey,$db,$arrayResponse);
		if($arrayResponse["status"]!=0){
			return $arrayResponse;
		}
		
		for($i=0;$i<count($gameObjects["gameObjects"]);$i++)
		{
			if($gameObjects["gameObjects"][$i]["status"]==0)
			{
				$arrayResponse = array();
				array_push($gameObjectsArray["gameObjects"],deleteObjectByKey($gameKey,$gameInstanceKey,
							$gameObjects["gameObjects"][$i]["objectKey"],$db,$arrayResponse));
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