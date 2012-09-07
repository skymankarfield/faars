<?php
function compareValues($value1,$operator,$value2)
{
	switch ($operator) {
		case '=':
			
				if($value1==$value2)
				{
					return true;
				}else
				{
					return false;
				}
			
			break;
	
		case '>':
			
				if($value1>$value2)
				{
					return true;
				}else
				{
					return false;
				}
			
			break;

		case '<':
			
				if($value1<$value2)
				{
					return true;
				}else
				{
					return false;
				}
			
			break;
	
		case '>=':
			
				if($value1>=$value2)
				{
					return true;
				}else
				{
					return false;
				}
			
			break;
	
		case '<=':
			
				if($value1<=$value2)
				{
					return true;
				}else
				{
					return false;
				}
			
			break;
	
		case '!=':
			
				if($value1!=$value2)
				{
					return true;
				}else
				{
					return false;
				}
			
			break;
	
		case 'valueInCollection':
			
			if(in_array($value2, $value1))
			{
				return true;
			
			}else
			{
				return false;
			}
		
			break;
		
		case 'valueNotInCollection':
			
			if(!in_array($value2, $value1))
			{
				return true;
			
			}else
			{
				return false;
			}
		
			break;
		
		default:
			
			break;
	}
}

function performConditionChecking($object,$conditionKey,$attributeKey,$checkFor,$value)
{
	switch ($conditionKey) {
		case 'checkPrimaryAttribute':
			
				switch ($attributeKey) {
					case 'currentStateKey':
						
						if(compareValues($object["currentStateKey"], $checkFor, $value))
						{
							return true;
						}else
						{
							return false;
						}
						
						break;
					
					case 'currentZone':
					
						if(compareValues($object["currentZone"], $checkFor, $value))
						{
							return true;
						}else
						{
							return false;
						}
					
						break;
					
					default:
						
						break;
				}
			
			break;
		
		case 'checkSecondaryAttribute':
			
			if(array_key_exists($attributeKey,$object["attributes"]))
			{
				if($object["attributes"][$attributeKey]["active"]=="1")
				{
					if(compareValues($object["attributes"][$attributeKey]["value"], $checkFor, $value))
					{
						return true;
					}else
					{
						return false;
					}
				}else
				{
					return false;
				}	
			}else
			{
				return false;
			}
			
			break;
		
		case 'checkCollection':
			
			if(array_key_exists($attributeKey,$object["collections"]))
			{
				if($object["collections"][$attributeKey]["active"]=="1")
				{
					if(compareValues($object["collections"][$attributeKey]["value"], $checkFor, $value))
					{
						return true;
					}else
					{
						return false;
					}
				}else
				{
					return false;
				}	
			}else
			{
				return false;
			}
			
			break;
			
		default:
			
			break;
	}
	return true;
}
?>