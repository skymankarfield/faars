<?php

function performActions($gameKey,$gameInstanceKey,$object,$actionKey,$attributeKey,$operation,$value,$ECAindex,$ActionIndex,&$db,&$arrayResponse)
{
	switch ($actionKey) {
		case 'updatePrimaryObjectAttributeByObjectKey':
			
				include_once 'faars/game_objects/updatePrimaryObjectAttributeByObjectKey.php';
				$value = json_decode($value);
				$localArrayResponse = array();
				updatePrimaryObjectAttributeByObjectKey($gameKey,$gameInstanceKey,$object["objectKey"],$value,$db,$localArrayResponse);
				$arrayResponse["gameECARules"][$ECAindex]["actions"][$ActionIndex]["output"]=$localArrayResponse;
				if($localArrayResponse["status"]!=0){
					
					return false;
				}else
				{
					return true;
				}
			
			break;
		
		case 'performOperationByObjectKey':
			
				include_once 'faars/operations/performOperationByObjectKey.php';
				$value = json_decode($value);
				$localArrayResponse = array();
				performOperationByObjectKey($gameKey,$gameInstanceKey,$object["objectKey"],$value,$db,$localArrayResponse);
				$arrayResponse["gameECARules"][$ECAindex]["actions"][$ActionIndex]["output"]=$localArrayResponse;
				if($localArrayResponse["status"]!=0){
					
					return false;
				}else
				{
					return true;
				}
			
			break;
		
		case 'pushItemsToCollection':
		
				include_once 'faars/operations/pushItemsToCollection.php';
				$value = json_decode($value);
				$localArrayResponse = array();
				//performOperationByObjectKey($gameKey,$gameInstanceKey,$object["objectKey"],$value,$db,$localArrayResponse);
				pushItemsToCollection($gameKey,$gameInstanceKey,$object["objectKey"],$attributeKey,$value,$db,$localArrayResponse);
				$arrayResponse["gameECARules"][$ECAindex]["actions"][$ActionIndex]["output"]=$localArrayResponse;
				if($localArrayResponse["status"]!=0){
					
					return false;
				}else
				{
					return true;
				}
		
			break;
		
		case 'addScheduledEvent':
			
				include_once 'faars/events/addScheduledEvent.php';
				$value = json_decode($value);
				$localArrayResponse = array();
				addScheduledEvent($gameKey,$gameInstanceKey,$value,$db,$localArrayResponse);
				$arrayResponse["gameECARules"][$ECAindex]["actions"][$ActionIndex]["output"]=$localArrayResponse;
				if($localArrayResponse["status"]!=0){
					
					return false;
				}else
				{
					return true;
				}
			
			break;
		
		case 'notifyExternalSystem':
			
				include_once 'faars/misc/notifyExternalSystem.php';
				$value = json_decode($value);
				$localArrayResponse = array();
				notifyExternalSystem($value,$localArrayResponse);
				$arrayResponse["gameECARules"][$ECAindex]["actions"][$ActionIndex]["output"]=$localArrayResponse;
				if($localArrayResponse["status"]!=0){
					
					return false;
				}else
				{
					return true;
				}
			
			break;
		
		default:
			
			break;
	}
	return true;
}
?>