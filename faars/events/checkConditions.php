<?php
include_once 'faars/events/performConditionChecking.php';
include_once 'faars/misc/util.php';

function checkConditions($gameKey,$gameInstanceKey,$gameObjectEventGenerator,$gameObjectEventRecipient,$ECArule_ID,$index,&$db,&$arrayResponse)
{
	$sql = "SELECT * FROM `faars-rocketfuel`.condition WHERE condition.ECArule_ID=:ECArule_ID AND condition.active='1' ORDER BY condition_ID ASC";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(":ECArule_ID", $ECArule_ID);
	$stmt->execute();
	$arrayResponse["gameECARules"][$index]["conditions"] = array();
	if($stmt->rowCount()==0)
	{
		$arrayResponse["gameECARules"][$index]["status"]=0;
	   	return $arrayResponse;
	}
	
	$conditions = $stmt->fetchAll(PDO::FETCH_OBJ);
	$conditions = json_encode($conditions);
	$conditions = json_decode($conditions,true);
	$arrayResponse["gameECARules"][$index]["status"]=0;
	for($i=0;$i<count($conditions);$i++)
	{
		$arrayResponse["gameECARules"][$index]["conditions"][$i]["conditionKey"]=$conditions[$i]["conditionKey"];
		switch ($conditions[$i]["scope"]) {
			case 'object':
				
					switch ($conditions[$i]["who"]) {
						case 'generator':
							
							$arrayResponse["gameECARules"][$index]["conditions"][$i]["objectScope"] = "generator";
							$arrayResponse["gameECARules"][$index]["conditions"][$i]["objectKey"] = $gameObjectEventGenerator["objectKey"];
							$value = replaceVariablesByValues($gameObjectEventGenerator,$gameObjectEventRecipient,
										$conditions[$i]["value"]);
							if(!performConditionChecking($gameObjectEventGenerator,$conditions[$i]["conditionKey"],$conditions[$i]["attributeKey"],
														$conditions[$i]["checkFor"],$value))
							{
								$arrayResponse["gameECARules"][$index]["conditions"][$i]["status"]=1;
								$arrayResponse["gameECARules"][$index]["status"]=1;
							}else
							{
								$arrayResponse["gameECARules"][$index]["conditions"][$i]["status"]=0;
							}
							
							break;
						
						case 'recipient':
							
							$arrayResponse["gameECARules"][$index]["conditions"][$i]["objectScope"] = "recipient";
							$arrayResponse["gameECARules"][$index]["conditions"][$i]["objectKey"] = $gameObjectEventRecipient["objectKey"];
							$value = replaceVariablesByValues($gameObjectEventGenerator,$gameObjectEventRecipient,
										$conditions[$i]["value"]);
							if(!performConditionChecking($gameObjectEventRecipient,$conditions[$i]["conditionKey"],$conditions[$i]["attributeKey"],
														$conditions[$i]["checkFor"],$value))
							{
								$arrayResponse["gameECARules"][$index]["conditions"][$i]["status"]=1;
								$arrayResponse["gameECARules"][$index]["status"]=1;
							}else{
								$arrayResponse["gameECARules"][$index]["conditions"][$i]["status"]=0;
							}
							
							break;
						
						case 'other':
							
							break;
							
						default:
							
							break;
					}
				
				break;
		
			case 'group':
				
				break;
		
			case 'game':
				
				break;
		
			default:
				
				break;
		}
	}
}
?>