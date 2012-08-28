<?php

function getGameObjectID($gameObject_ID,$gameInstance_ID,&$db,&$arrayResponse)
{
	$arrayResponse = array();
	$arrayDetails = array();
	$arrayDetails["action"] = "getGameObjectID";
	$arrayDetails["objectKey"] = $gameObject_ID;
	
	try{
	//$query = "SELECT game_ID FROM game WHERE gameKey='".mysqli_real_escape_string($link,$game_ID)."'";
		$sql = "SELECT object.object_ID FROM `faars-rocketfuel`.object WHERE
					object.objectKey=:gameObject_ID
					AND object.gameInstance_ID=:gameInstance_ID";
		
		$stmt = $db->prepare($sql);
	    $stmt->bindParam(":gameObject_ID", $gameObject_ID);
		$stmt->bindParam(":gameInstance_ID", $gameInstance_ID);
	    $stmt->execute();
		
		if($stmt->rowCount()==1)
		{
			$id = $stmt->fetch(PDO::FETCH_ASSOC);
			$arrayResponse["status"] = 0;
			$arrayResponse["gameObject_ID"] = $id["object_ID"];
			$arrayResponse["details"] = $arrayDetails;
			return $arrayResponse;
		}else
		{
			$arrayResponse = array();
			$arrayResponse["status"] = 8;
			$arrayResponse["message"] = "No game object ID found";
			$arrayResponse["details"] = $arrayDetails;
	    	return $arrayResponse;
		}
	}catch(PDOException $e) {
		$arrayResponse = array();
		$arrayResponse["status"] = 1;
		$arrayResponse["message"] = $e->getMessage();
		$arrayResponse["details"] = $arrayDetails;
	    return $arrayResponse;
    }
	
   
}


?>