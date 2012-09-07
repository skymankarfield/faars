<?php

function trim_value(&$value) 
{ 
    $value = trim($value); 
}

function checkJSONByLevel($gameObjectFraction,$existsArray,$nonEmptyArray,&$arrayResponse)
{
	$arrayResponse = array();
	$arrayDetails = array();
	$arrayDetails["action"] = "checkJSONByLevel";
	
	for($i=0;$i<count($existsArray);$i++)
	{
		if(!array_key_exists($existsArray[$i], $gameObjectFraction))
		{
    		$arrayResponse = array();
			$arrayResponse["status"] = 6;
			$arrayResponse["message"] = "A game object attribute is missing: ".$existsArray[$i];
			$arrayResponse["details"] = $arrayDetails;
	    	return $arrayResponse;
		}
		
	}
	
	for($i=0;$i<count($nonEmptyArray);$i++)
	{
		if($gameObjectFraction[$nonEmptyArray[$i]]=="" || $gameObjectFraction[$nonEmptyArray[$i]]==NULL)
		{
			$arrayResponse = array();
			$arrayResponse["status"] = 7;
			$arrayResponse["message"] = "A required game object attribute is empty: ".$nonEmptyArray[$i];
			$arrayResponse["details"] = $arrayDetails;
	   		return $arrayResponse;
		}
	}
	
	$arrayResponse["status"] = 0;
	$arrayResponse["details"] = $arrayDetails;
	return $arrayResponse;
	
	
}

function replaceVariablesByValues($generatorObjectKey,$recipientObjectKey,$value)
{
	// :->recipient->objectKey<-:
	if(strpos($value,":->") === false || strpos($value,"<-:") === false)
	{
		return $value;
	
	}else
	{
		$substringToReplace = substr($value, strpos($value, ":->"), ((strpos($value, "<-:")-strpos($value, ":->"))+3));
		$substring = substr($substringToReplace, ((strpos($substringToReplace, ":->")+3)), (strpos($substringToReplace, "<-:")-3));
		$objectElements = explode("->",$substring);
		$temporalObject = "";
		switch($objectElements[0])
		{
			case 'generator':
				
					$temporalObject = $generatorObjectKey;
				
				break;
				
			case 'recipient':
				
					$temporalObject = $recipientObjectKey;
				
				break;
			
			case 'other':
				
				
				break;
				
			default:
				
				
				break;
		}
		
		for($i=1;$i<count($objectElements);$i++)
		{
			if(array_key_exists($objectElements[$i],$temporalObject))
			{
				$temporalObject = $temporalObject[$objectElements[$i]];
			}
		}
		
		$tmpvalue = str_replace($substringToReplace, $temporalObject, $value);
		//echo $tmpvalue;
		return $tmpvalue;
	}
	
}
//replaceVariablesByValues("","",'{"objectKey":"event5_:recipient->objectKey","eventKey":"unlock","eventGeneratorKey":"game_instance_1","eventRecipientKey":":->recipient->objectKey<-:","year":"0","month":"0","day":"0","hour":"-1","minute":"-1","second":"-1","plusyears":"0","plusmonths":"0","plusdays":"0","plushours":"0","plusminutes":"2","plusseconds":"0"}	');
?>