<?php
//function addUser($fullname,$username,$email,$password1,$password2,$game_ID,$form_ID,$agree,$activate,&$link,&$error_code)
include_once 'faars/game_objects/getObjectByKey.php';
include_once 'faars/misc/getGameInstanceID.php';

function getAllGameObjectsByGroupKey($gameKey,$gameInstanceKey,$groupKey,&$db,&$arrayResponse)
{
	$arrayResponse = array();
	$arrayDetails = array();
	$arrayDetails["action"] = "getAllGameObjectsByGroupKey";
	$arrayDetails["groupKey"] = $groupKey;
	$arrayDetails["gameKey"] = $gameKey;
	$arrayDetails["gameInstanceKey"] = $gameInstanceKey;
	//$gameObject_ID = 0;

	try{	
		
		getGameInstanceID($gameInstanceKey,$gameKey,$db,$arrayResponse);
		
		if($arrayResponse["status"]!=0){
			return $arrayResponse;
		}
		
		$gameInstance_ID = $arrayResponse["gameInstance_ID"];
		
		$sql = "SELECT object.objectKey FROM `faars-rocketfuel`.object,`faars-rocketfuel`.objectGroup WHERE 
				object.gameInstance_ID=:gameInstance_ID
				AND object.object_ID=objectGroup.object_ID
				AND objectGroup.groupKey=:groupKey GROUP BY object.objectKey";
		$stmt = $db->prepare($sql);
		$stmt->bindParam(":gameInstance_ID", $gameInstance_ID);
		$stmt->bindParam(":groupKey", $groupKey);
	    $stmt->execute();
	    
		if($stmt->rowCount()==0)
		{
			$arrayResponse = array();
			$arrayResponse["status"] = 13;
			$arrayResponse["message"] = "No game object entries found in database with given object group key";
			$arrayResponse["details"] = $arrayDetails;
	    	return $arrayResponse;
		}
		
		$gameObjects = $stmt->fetchAll(PDO::FETCH_OBJ);
		//var_dump($gameObjects);
		$gameObjects = json_encode($gameObjects);
		$gameObjects = json_decode($gameObjects,true);
		$gameObjectsArray = array();
		$gameObjectsArray["gameObjects"] = array();
		
		for($i=0;$i<count($gameObjects);$i++)
		{
			$arrayResponse = array();
			array_push($gameObjectsArray["gameObjects"],getObjectByKey($gameKey,$gameInstanceKey,$gameObjects[$i]["objectKey"],$db,$arrayResponse));
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