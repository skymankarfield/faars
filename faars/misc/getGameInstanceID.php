<?php

function getGameInstanceID($gameInstance_ID,$game_ID,&$db,&$arrayResponse)
{
	$arrayResponse = array();
	$arrayDetails = array();
	$arrayDetails["action"] = "getGameInstanceID";
	$arrayDetails["gameKey"] = $game_ID;
	$arrayDetails["gameInstanceKey"] = $gameInstance_ID;
	
	try{
	//$query = "SELECT game_ID FROM game WHERE gameKey='".mysqli_real_escape_string($link,$game_ID)."'";
		$sql = "SELECT gameInstance.gameInstance_ID FROM `faars-rocketfuel`.gameInstance,`faars-rocketfuel`.game WHERE
					gameInstance.gameInstanceKey=:gameInstance_ID
					AND game.gameKey=:game_ID
					AND gameInstance.game_ID=game.game_ID
					AND gameInstance.active='1'
					AND game.active='1'";
		
		$stmt = $db->prepare($sql);
	    $stmt->bindParam(":gameInstance_ID", $gameInstance_ID);
		$stmt->bindParam(":game_ID", $game_ID);
	    $stmt->execute();
		
		if($stmt->rowCount()==1)
		{
			$id = $stmt->fetch(PDO::FETCH_ASSOC);
			$arrayResponse["status"] = 0;
			$arrayResponse["gameInstance_ID"] = $id["gameInstance_ID"];
			$arrayResponse["details"] = $arrayDetails;
			return $arrayResponse;
		}else
		{
			$arrayResponse = array();
			$arrayResponse["status"] = 2;
			$arrayResponse["message"] = "No game ID or game instance ID found";
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