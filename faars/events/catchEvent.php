<?php
include_once 'faars/game_objects/getObjectByKey.php';
include_once 'faars/misc/getGameInstanceID.php';
include_once 'faars/misc/util.php';
include_once 'faars/events/checkConditions.php';
include_once 'faars/events/applyActions.php';

function checkJSONEvent($gameObject,&$arrayResponse)
{
	$arrayResponse = array();
	$arrayDetails = array();
	$arrayDetails["action"] = "checkJSONEvent";
	
	$gameObject = json_encode($gameObject);
	$gameObject = json_decode($gameObject,true);
	array_walk_recursive($gameObject, 'trim_value'); 
	
	$existsAndNonEmptyArray = array();
	array_push($existsAndNonEmptyArray,"eventGeneratorKey","eventRecipientKey","eventKey");
	
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
	
function catchEvent($gameKey,$gameInstanceKey,$gameObject,&$db,&$arrayResponse)
{
	$arrayResponse = array();
	$arrayDetails = array();
	$arrayDetails["action"] = "catchEvent";
	$arrayDetails["eventGeneratorKey"] = $gameObject->eventGeneratorKey;
	$arrayDetails["eventRecipientKey"] = $gameObject->eventRecipientKey;
	$arrayDetails["eventKey"] = $gameObject->eventKey;
	$arrayDetails["gameKey"] = $gameKey;
	$arrayDetails["gameInstanceKey"] = $gameInstanceKey;
	
	try{	
		checkJSONEvent($gameObject,$arrayResponse);//if all checks were successful
		if($arrayResponse["status"]!=0){
			return $arrayResponse;
		}
		
		getGameInstanceID($gameInstanceKey,$gameKey,$db,$arrayResponse);
		
		if($arrayResponse["status"]!=0){
			return $arrayResponse;
		}
		
		$gameInstance_ID = $arrayResponse["gameInstance_ID"];
		
		$gameObjectEventGenerator = getObjectByKey($gameKey,$gameInstanceKey,$gameObject->eventGeneratorKey,$db,$arrayResponse);
		if($arrayResponse["status"]!=0){
			return $arrayResponse;
		}
		
		$gameObjectEventRecipient = getObjectByKey($gameKey,$gameInstanceKey,$gameObject->eventRecipientKey,$db,$arrayResponse);
		if($arrayResponse["status"]!=0){
			return $arrayResponse;
		}
		
		if($gameObjectEventGenerator["active"]=='0' || $gameObjectEventRecipient["active"]=='0')
		{
			$arrayResponse = array();
			$arrayResponse["status"] = 20;
			$arrayResponse["message"] = "Either the eventGenerator or eventRecipient is not activated";
			$arrayResponse["details"] = $arrayDetails;
	    	return $arrayResponse;
		}
		
		$keysEventGeneratorArray = array();
		$keysEventGeneratorArray[0] = $gameObjectEventGenerator["objectKey"];
		$groups_array = array();
		$groups_array = $gameObjectEventGenerator["groups"];
		reset($groups_array);
		$current_element = current($groups_array);
		for($i=0;$i<count($groups_array);$i++)
		{
			if($current_element["active"]==1)
			{
				array_push($keysEventGeneratorArray,$current_element["groupKey"]);
			}
			$current_element = next($groups_array);
		}
		
		$keysEventRecipientArray = array();
		$keysEventRecipientArray[0] = $gameObjectEventRecipient["objectKey"];
		$groups_array = array();
		$groups_array = $gameObjectEventRecipient["groups"];
		reset($groups_array);
		$current_element = current($groups_array);
		for($i=0;$i<count($groups_array);$i++)
		{
			if($current_element["active"]==1)
			{
				array_push($keysEventRecipientArray,$current_element["groupKey"]);
			}
			$current_element = next($groups_array);
		}
		
		$implodedKeysGenerator = "'".implode("','",$keysEventGeneratorArray)."'";
		$implodedKeysRecipient = "'".implode("','",$keysEventRecipientArray)."'";
		
		$sql = "SELECT * FROM `faars-rocketfuel`.ECArules,`faars-rocketfuel`.event WHERE ECArules.active='1'
				AND ECArules.gameInstance_ID=:gameInstance_ID AND event.ECArule_ID=ECArules.ECArule_ID 
				AND event.generatorKey IN (".$implodedKeysGenerator.") AND event.recipientKey IN (".$implodedKeysRecipient.")
				AND event.eventKey=:eventKey AND event.active='1' ORDER BY ECArules.ECArule_ID ASC";
		$stmt = $db->prepare($sql);
		$stmt->bindParam(":gameInstance_ID", $gameInstance_ID);
		//$stmt->bindParam(":implodedKeysGenerator", $implodedKeysGenerator);
		//$stmt->bindParam(":implodedKeysRecipient", $implodedKeysRecipient);
		$stmt->bindParam(":eventKey", $gameObject->eventKey);
	    $stmt->execute();
	    
		if($stmt->rowCount()==0)
		{
			$arrayResponse = array();
			$arrayResponse["status"] = 21;
			$arrayResponse["message"] = "No game ECA rules corresponding to this event were found";
			$arrayResponse["details"] = $arrayDetails;
	    	return $arrayResponse;
		}
		
		$events = $stmt->fetchAll(PDO::FETCH_OBJ);
		
		$arrayResponse = array();
		$events = json_encode($events);
		$events = json_decode($events,true);
		
		for($i=0;$i<count($events);$i++)
		{
			
			$arrayResponse["gameECARules"][$i]["gameECARuleKey"] = $events[$i]["ECAruleKey"];
			//$arrayResponse["gameECARules"][$i]["conditions"] = array(); this is where conditions are saved in checkConditions
			checkConditions($gameKey,$gameInstanceKey,$gameObjectEventGenerator,$gameObjectEventRecipient,$events[$i]["ECArule_ID"],$i,$db,$arrayResponse);
			if($arrayResponse["gameECARules"][$i]["status"]==0)
			{
				//$arrayResponse["gameECARules"][$i]["actions"] = array(); this is where actions taken are saved i applyActions
				applyActions($gameKey,$gameInstanceKey,$gameObjectEventGenerator,$gameObjectEventRecipient,$events[$i]["ECArule_ID"],$i,$db,$arrayResponse);
			}
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