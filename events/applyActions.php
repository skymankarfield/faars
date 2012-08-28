<?php
include_once 'faars/events/performActions.php';
include_once 'faars/misc/util.php';

function applyActions($gameKey,$gameInstanceKey,$gameObjectEventGenerator,$gameObjectEventRecipient,$ECArule_ID,$index,&$db,&$arrayResponse)
{
	$sql = "SELECT * FROM `faars-rocketfuel`.action WHERE action.ECArule_ID=:ECArule_ID AND action.active='1' ORDER BY action_ID ASC";
	$stmt = $db->prepare($sql);
	$stmt->bindParam(":ECArule_ID", $ECArule_ID);
	$stmt->execute();
	$arrayResponse["gameECARules"][$index]["actions"] = array();
	if($stmt->rowCount()==0)
	{
		//$arrayResponse["gameECARules"][$index]["status"]=0;
	   	return $arrayResponse;
	}
	
	$actions = $stmt->fetchAll(PDO::FETCH_OBJ);
	$actions = json_encode($actions);
	$actions = json_decode($actions,true);
	//$arrayResponse["gameECARules"][$index]["status"]=0;
	for($i=0;$i<count($actions);$i++)
	{
		$arrayResponse["gameECARules"][$index]["actions"][$i]["actionKey"]=$actions[$i]["actionKey"];
		switch ($actions[$i]["scope"]) {
			case 'object':
				
					switch ($actions[$i]["who"]) {
						case 'generator':
							
							$arrayResponse["gameECARules"][$index]["actions"][$i]["objectScope"] = "generator";
							$arrayResponse["gameECARules"][$index]["actions"][$i]["objectKey"] = $gameObjectEventGenerator["objectKey"];
							$value = replaceVariablesByValues($gameObjectEventGenerator,$gameObjectEventRecipient,
										$actions[$i]["value"]);
							if(!performActions($gameKey,$gameInstanceKey,$gameObjectEventGenerator,$actions[$i]["actionKey"],$actions[$i]["attributeKey"],
														$actions[$i]["operation"],$value,$index,$i,$db,$arrayResponse))
							{
								$arrayResponse["gameECARules"][$index]["actions"][$i]["status"]=1;
								//$arrayResponse["gameECARules"][$index]["status"]=1;
							}else
							{
								$arrayResponse["gameECARules"][$index]["actions"][$i]["status"]=0;
							}
							
							break;
						
						case 'recipient':
							
							$arrayResponse["gameECARules"][$index]["actions"][$i]["objectScope"] = "recipient";
							$arrayResponse["gameECARules"][$index]["actions"][$i]["objectKey"] = $gameObjectEventRecipient["objectKey"];
							$value = replaceVariablesByValues($gameObjectEventGenerator,$gameObjectEventRecipient,
										$actions[$i]["value"]);
							if(!performActions($gameKey,$gameInstanceKey,$gameObjectEventRecipient,$actions[$i]["actionKey"],$actions[$i]["attributeKey"],
														$actions[$i]["operation"],$value,$index,$i,$db,$arrayResponse))
							{
								$arrayResponse["gameECARules"][$index]["actions"][$i]["status"]=1;
								//$arrayResponse["gameECARules"][$index]["status"]=1;
							}else
							{
								$arrayResponse["gameECARules"][$index]["actions"][$i]["status"]=0;
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