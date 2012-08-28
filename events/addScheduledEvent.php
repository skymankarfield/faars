<?php
//function addUser($fullname,$username,$email,$password1,$password2,$game_ID,$form_ID,$agree,$activate,&$link,&$error_code)
include_once 'faars/misc/getGameInstanceID.php';
include_once 'faars/misc/util.php';
include_once 'faars/game_objects/createObject.php';

function checkJSONScheduledEvent($gameObject,&$arrayResponse)
{
	$arrayResponse = array();
	$arrayDetails = array();
	$arrayDetails["action"] = "checkJSONScheduledEvent";
	
	$gameObject = json_encode($gameObject);
	$gameObject = json_decode($gameObject,true);
	array_walk_recursive($gameObject, 'trim_value'); 
	
	$existsAndNonEmptyArray = array();
	array_push($existsAndNonEmptyArray,"eventGeneratorKey","eventRecipientKey","eventKey","year","month","day","hour","minute","second");
	
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

function addScheduledEvent($gameKey,$gameInstanceKey,$gameObject,&$db,&$arrayResponse)
{
	$arrayResponse = array();
	$gameInstance_ID = 0;
	$arrayDetails = array();
	$arrayDetails["action"] = "addScheduledEventByDate";
	$arrayDetails["objectKey"] = $gameObject->objectKey;
	$arrayDetails["eventGeneratorKey"] = $gameObject->eventGeneratorKey;
	$arrayDetails["eventRecipientKey"] = $gameObject->eventRecipientKey;
	$arrayDetails["eventKey"] = $gameObject->eventKey;
	$arrayDetails["gameKey"] = $gameKey;
	$arrayDetails["gameInstanceKey"] = $gameInstanceKey;
	try{
	//perform all the checks here...
		checkJSONScheduledEvent($gameObject,$arrayResponse);//if all checks were successful
		if($arrayResponse["status"]!=0){
			return $arrayResponse;
		}
		
		$arrayResponse = array();
		getGameInstanceID($gameInstanceKey,$gameKey,$db,$arrayResponse);
		
		if($arrayResponse["status"]!=0){
			return $arrayResponse;
		}
		
		$gameInstance_ID = $arrayResponse["gameInstance_ID"];
		
		$gameObject->hour = (($gameObject->hour > -1) ? $gameObject->hour : date("H"));
		$gameObject->minute = (($gameObject->minute > -1) ? $gameObject->minute : date("i"));
		$gameObject->second = (($gameObject->second > -1) ? $gameObject->second : date("s"));
		$gameObject->month = (($gameObject->month > 0) ? $gameObject->month : date("n"));
		$gameObject->day = (($gameObject->day > 0) ? $gameObject->day : date("j"));
		$gameObject->year = (($gameObject->year > 1970) ? $gameObject->year : date("Y"));
		
		$timeOfEvent = mktime(($gameObject->hour+$gameObject->plushours),($gameObject->minute+$gameObject->plusminutes),
						($gameObject->second+$gameObject->plusseconds),($gameObject->month+$gameObject->plusmonths),
						($gameObject->day+$gameObject->plusdays),($gameObject->year+$gameObject->plusyears));
		
		$gameObject->hour = date("H",$timeOfEvent);//$gameObject->hour+$gameObject->plushours;
		$gameObject->minute = date("i",$timeOfEvent);//$gameObject->minute+$gameObject->plusminutes;
		$gameObject->second = date("s",$timeOfEvent);//$gameObject->second+$gameObject->plusseconds;
		$gameObject->month = date("n",$timeOfEvent);//$gameObject->month+$gameObject->plusmonths;
		$gameObject->day = date("j",$timeOfEvent);//$gameObject->day+$gameObject->plusdays;
		$gameObject->year = date("Y",$timeOfEvent);//$gameObject->year+$gameObject->plusyears;
		
		if($timeOfEvent < time())
		{
			$arrayResponse = array();
			$arrayResponse["status"] = 24;
			$arrayResponse["message"] = "Error creating scheduled event. The time set is in the past";
			$arrayResponse["details"] = $arrayDetails;
		    return $arrayResponse;
		}
		//$localObjectKey = "".rand(0, getrandmax());
		$localGameObject = array();
		$localGameObject["fullObjectName"] = $gameObject->objectKey;
		$localGameObject["objectKey"] = $gameObject->objectKey;
		$localGameObject["loginInfo"][0]["username"] = "";
		$localGameObject["loginInfo"][0]["password"] = "";
		$localGameObject["gameInstance_ID"] = $gameInstance_ID;
		$localGameObject["currentStateKey"] = "scheduledEvent";
		$localGameObject["currentGPSLocation"][0]["currentLat"] = "1000000";
		$localGameObject["currentGPSLocation"][0]["currentLon"] = "1000000";
		$localGameObject["currentZone"] = "nozone";
		$localGameObject["allowLogin"] = "0";
		$localGameObject["transient"] = "1";
		$localGameObject["active"] = "1";
		
		$localGameObject["attributes"] = array();
		$gameObject = json_encode($gameObject);
		$gameObject = json_decode($gameObject,true);
		unset($gameObject["objectKey"]);
		$localArrayKeys = array_keys($gameObject);
		for($i=0;$i<count($localArrayKeys);$i++)
		{
			$localGameObject["attributes"][$i]["attributeKey"]=$localArrayKeys[$i];
			$localGameObject["attributes"][$i]["value"]=$gameObject[$localArrayKeys[$i]];
			$localGameObject["attributes"][$i]["active"]="1";
		}
		
		$localGameObject["groups"][0]["groupKey"] = "scheduledEvent";
		$localGameObject["groups"][0]["active"] = "1";
		
		$localGameObject["collections"][0]["collectionKey"] = "dummy";
		$localGameObject["collections"][0]["value"] = array("0");
		$localGameObject["collections"][0]["active"] = "1";
		
		$localGameObject = json_encode($localGameObject);
		$localGameObject = json_decode($localGameObject);
		
		$arrayResponse = array();
		createObject($gameKey,$gameInstanceKey,$localGameObject,$db,$arrayResponse);
		if($arrayResponse["status"]!=0){
			return $arrayResponse;
		}
		
		$arrayResponse = array();
		$arrayResponse["status"] = 0;
		$arrayResponse["details"] =$arrayDetails;
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