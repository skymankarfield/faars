<?php

function notifyExternalSystem($gameObject,&$arrayResponse)
{
	$arrayResponse = array();
	//$gameInstance_ID = 0;
	$arrayDetails = array();
	$arrayDetails["action"] = "notifyExternalSystem";
	$arrayDetails["method"] = $gameObject->method;
	$arrayDetails["url"] = $gameObject->url;
	$arrayDetails["data"] = $gameObject->data;
	
	$method = $gameObject->method;
	$url = $gameObject->url;
	$data = $gameObject->data;
	
	switch($method)
	{
		case 'get':
				$ch=curl_init();
				
				curl_setopt($ch,CURLOPT_URL,$url."?".$data);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$arrayDetails["call_return"] = curl_exec($ch);
				curl_close($ch);
			
			break;
			
			
		case 'post':
			
			
			break;
	}
	
	//$arrayResponse = array();
	//$arrayResponse = $gameObjectsArray;
	$arrayResponse["status"] = 0;
	$arrayResponse["details"] = $arrayDetails;
	return $arrayResponse;
}

?>