<?php

require 'Slim/Slim.php';

$app = new Slim();


$app->post('/:gameKeyParam/:gameInstanceKeyParam/gameObjects', function ($param1,$param2)
{
    $request = Slim::getInstance()->request();
    $gameObject = json_decode($request->getBody());
    try {
        $db = getConnection();
        include 'faars/game_objects/createObject.php';
		$arrayResponse = array();
		createObject($param1,$param2,$gameObject,$db,$arrayResponse);
		echo json_encode($arrayResponse);
    }catch(PDOException $e){
    	$arrayResponse = array();
		$arrayResponse["status"] = 1;
		$arrayResponse["message"] = $e->getMessage();
        echo json_encode($arrayResponse);
    }

});

$app->get('/:gameKeyParam/:gameInstanceKeyParam/gameObjects/:objectKeyParam', function ($param1,$param2,$param3)
{
    try {
        $db = getConnection();
        include 'faars/game_objects/getObjectByKey.php';
		$arrayResponse = array();
		getObjectByKey($param1,$param2,$param3,$db,$arrayResponse);
		echo json_encode($arrayResponse);
    }catch(PDOException $e){
    	$arrayResponse = array();
		$arrayResponse["status"] = 1;
		$arrayResponse["message"] = $e->getMessage();
        echo json_encode($arrayResponse);
    }

});

$app->get('/:gameKeyParam/:gameInstanceKeyParam/gameObjects', function ($param1,$param2)
{
    try {
        $db = getConnection();
        include 'faars/game_objects/getAllGameObjectsByGameInstanceKey.php';
		$arrayResponse = array();
		getAllGameObjectsByGameInstanceKey($param1,$param2,$db,$arrayResponse);
		echo json_encode($arrayResponse);
    }catch(PDOException $e){
    	$arrayResponse = array();
		$arrayResponse["status"] = 1;
		$arrayResponse["message"] = $e->getMessage();
        echo json_encode($arrayResponse);
    }

});

$app->get('/:gameKeyParam/:gameInstanceKeyParam/gameObjects/groups/:groupKeyParam', function ($param1,$param2,$param3)
{
    try {
        $db = getConnection();
        include 'faars/game_objects/getAllGameObjectsByGroupKey.php';
		$arrayResponse = array();
		getAllGameObjectsByGroupKey($param1,$param2,$param3,$db,$arrayResponse);
		echo json_encode($arrayResponse);
    }catch(PDOException $e){
    	$arrayResponse = array();
		$arrayResponse["status"] = 1;
		$arrayResponse["message"] = $e->getMessage();
        echo json_encode($arrayResponse);
    }

});

$app->delete('/:gameKeyParam/:gameInstanceKeyParam/gameObjects/:objectKeyParam', function ($param1,$param2,$param3)
{
    try {
        $db = getConnection();
        include 'faars/game_objects/deleteObjectByKey.php';
		$arrayResponse = array();
		deleteObjectByKey($param1,$param2,$param3,$db,$arrayResponse);
		echo json_encode($arrayResponse);
    }catch(PDOException $e){
    	$arrayResponse = array();
		$arrayResponse["status"] = 1;
		$arrayResponse["message"] = $e->getMessage();
        echo json_encode($arrayResponse);
    }

});

$app->delete('/:gameKeyParam/:gameInstanceKeyParam/gameObjects/groups/:groupKeyParam', function ($param1,$param2,$param3)
{
    try {
        $db = getConnection();
        include 'faars/game_objects/deleteAllGameObjectsByGroupKey.php';
		$arrayResponse = array();
		deleteAllGameObjectsByGroupKey($param1,$param2,$param3,$db,$arrayResponse);
		echo json_encode($arrayResponse);
    }catch(PDOException $e){
    	$arrayResponse = array();
		$arrayResponse["status"] = 1;
		$arrayResponse["message"] = $e->getMessage();
        echo json_encode($arrayResponse);
    }

});

$app->delete('/:gameKeyParam/:gameInstanceKeyParam/gameObjects', function ($param1,$param2)
{
    try {
        $db = getConnection();
        include 'faars/game_objects/deleteAllGameObjectsByGameInstanceKey.php';
		$arrayResponse = array();
		deleteAllGameObjectsByGameInstanceKey($param1,$param2,$db,$arrayResponse);
		echo json_encode($arrayResponse);
    }catch(PDOException $e){
    	$arrayResponse = array();
		$arrayResponse["status"] = 1;
		$arrayResponse["message"] = $e->getMessage();
        echo json_encode($arrayResponse);
    }

});

$app->put('/:gameKeyParam/:gameInstanceKeyParam/gameObjects/:objectKeyParam', function ($param1,$param2,$param3)
{
	$request = Slim::getInstance()->request();
    $gameObject = json_decode($request->getBody());
    try {
        $db = getConnection();
        include 'faars/game_objects/updateObjectByKey.php';
		$arrayResponse = array();
		updateObjectByKey($param1,$param2,$param3,$gameObject,$db,$arrayResponse);
		echo json_encode($arrayResponse);
    }catch(PDOException $e){
    	$arrayResponse = array();
		$arrayResponse["status"] = 1;
		$arrayResponse["message"] = $e->getMessage();
        echo json_encode($arrayResponse);
    }

});

$app->put('/:gameKeyParam/:gameInstanceKeyParam/operations/:objectKeyParam', function ($param1,$param2,$param3)
{
	$request = Slim::getInstance()->request();
    $gameObject = json_decode($request->getBody());
    try {
        $db = getConnection();
        include 'faars/operations/performOperationByObjectKey.php';
		$arrayResponse = array();
		performOperationByObjectKey($param1,$param2,$param3,$gameObject,$db,$arrayResponse);
		echo json_encode($arrayResponse);
    }catch(PDOException $e){
    	$arrayResponse = array();
		$arrayResponse["status"] = 1;
		$arrayResponse["message"] = $e->getMessage();
        echo json_encode($arrayResponse);
    }

});

$app->put('/:gameKeyParam/:gameInstanceKeyParam/operations/groups/:groupKeyParam', function ($param1,$param2,$param3)
{
	$request = Slim::getInstance()->request();
    $gameObject = json_decode($request->getBody());
    try {
        $db = getConnection();
        include 'faars/operations/performOperationByGroupKey.php';
		$arrayResponse = array();
		performOperationByGroupKey($param1,$param2,$param3,$gameObject,$db,$arrayResponse);
		echo json_encode($arrayResponse);
    }catch(PDOException $e){
    	$arrayResponse = array();
		$arrayResponse["status"] = 1;
		$arrayResponse["message"] = $e->getMessage();
        echo json_encode($arrayResponse);
    }

});

$app->put('/:gameKeyParam/:gameInstanceKeyParam/operations', function ($param1,$param2)
{
	$request = Slim::getInstance()->request();
    $gameObject = json_decode($request->getBody());
    try {
        $db = getConnection();
        include 'faars/operations/performOperationByGameInstanceKey.php';
		$arrayResponse = array();
		performOperationByGameInstanceKey($param1,$param2,$gameObject,$db,$arrayResponse);
		echo json_encode($arrayResponse);
    }catch(PDOException $e){
    	$arrayResponse = array();
		$arrayResponse["status"] = 1;
		$arrayResponse["message"] = $e->getMessage();
        echo json_encode($arrayResponse);
    }

});

$app->get('/:gameKeyParam/:gameInstanceKeyParam/gameObjects/verifyObjectLogin/:username/:password', function ($param1,$param2,$param3,$param4)
{
    try {
        $db = getConnection();
        include 'faars/game_objects/verifyObjectLogin.php';
		$arrayResponse = array();
		verifyObjectLogin($param1,$param2,$param3,$param4,$db,$arrayResponse);
		echo json_encode($arrayResponse);
    }catch(PDOException $e){
    	$arrayResponse = array();
		$arrayResponse["status"] = 1;
		$arrayResponse["message"] = $e->getMessage();
        echo json_encode($arrayResponse);
    }

});

$app->put('/:gameKeyParam/:gameInstanceKeyParam/events', function ($param1,$param2)
{
	$request = Slim::getInstance()->request();
    $gameObject = json_decode($request->getBody());
    try {
        $db = getConnection();
        include 'faars/events/catchEvent.php';
		$arrayResponse = array();
		catchEvent($param1,$param2,$gameObject,$db,$arrayResponse);
		echo json_encode($arrayResponse);
    }catch(PDOException $e){
    	$arrayResponse = array();
		$arrayResponse["status"] = 1;
		$arrayResponse["message"] = $e->getMessage();
        echo json_encode($arrayResponse);
    }

});

$app->put('/:gameKeyParam/:gameInstanceKeyParam/gameObjects/:objectKeyParam/primaryAttributes', function ($param1,$param2,$param3)
{
	$request = Slim::getInstance()->request();
    $gameObject = json_decode($request->getBody());
    try {
        $db = getConnection();
        include 'faars/game_objects/updatePrimaryObjectAttributeByObjectKey.php';
		$arrayResponse = array();
		updatePrimaryObjectAttributeByObjectKey($param1,$param2,$param3,$gameObject,$db,$arrayResponse);
		echo json_encode($arrayResponse);
    }catch(PDOException $e){
    	$arrayResponse = array();
		$arrayResponse["status"] = 1;
		$arrayResponse["message"] = $e->getMessage();
        echo json_encode($arrayResponse);
    }

});

$app->post('/:gameKeyParam/:gameInstanceKeyParam/gameObjects/:objectKeyParam/secondaryAttributes', function ($param1,$param2,$param3)
{
    $request = Slim::getInstance()->request();
    $gameObject = json_decode($request->getBody());
    try {
        $db = getConnection();
        include 'faars/game_objects/addSecondaryAttributeByObjectKey.php';
		$arrayResponse = array();
		addSecondaryAttributeByObjectKey($param1,$param2,$param3,$gameObject,$db,$arrayResponse);
		echo json_encode($arrayResponse);
    }catch(PDOException $e){
    	$arrayResponse = array();
		$arrayResponse["status"] = 1;
		$arrayResponse["message"] = $e->getMessage();
        echo json_encode($arrayResponse);
    }

});

$app->post('/:gameKeyParam/:gameInstanceKeyParam/gameObjects/:objectKeyParam/collections', function ($param1,$param2,$param3)
{
    $request = Slim::getInstance()->request();
    $gameObject = json_decode($request->getBody());
    try {
        $db = getConnection();
        include 'faars/game_objects/addCollectionByObjectKey.php';
		$arrayResponse = array();
		addCollectionByObjectKey($param1,$param2,$param3,$gameObject,$db,$arrayResponse);
		echo json_encode($arrayResponse);
    }catch(PDOException $e){
    	$arrayResponse = array();
		$arrayResponse["status"] = 1;
		$arrayResponse["message"] = $e->getMessage();
        echo json_encode($arrayResponse);
    }

});

$app->put('/:gameKeyParam/:gameInstanceKeyParam/operations/:objectKeyParam/collections/:collectionKey', function ($param1,$param2,$param3,$param4)
{
	$request = Slim::getInstance()->request();
    $gameObject = json_decode($request->getBody());
    try {
        $db = getConnection();
        include 'faars/operations/pushItemsToCollection.php';
		$arrayResponse = array();
		pushItemsToCollection($param1,$param2,$param3,$param4,$gameObject,$db,$arrayResponse);
		echo json_encode($arrayResponse);
    }catch(PDOException $e){
    	$arrayResponse = array();
		$arrayResponse["status"] = 1;
		$arrayResponse["message"] = $e->getMessage();
        echo json_encode($arrayResponse);
    }

});

$app->get('/:gameKeyParam/:gameInstanceKeyParam/operations/:objectKeyParam/collections/:collectionKey/itemExistsInCollection/:itemValue', function ($param1,$param2,$param3,$param4,$param5)
{
	try {
        $db = getConnection();
        include 'faars/operations/itemExistsInCollection.php';
		$arrayResponse = array();
		itemExistsInCollection($param1,$param2,$param3,$param4,$param5,$db,$arrayResponse);
		echo json_encode($arrayResponse);
    }catch(PDOException $e){
    	$arrayResponse = array();
		$arrayResponse["status"] = 1;
		$arrayResponse["message"] = $e->getMessage();
        echo json_encode($arrayResponse);
    }

});

$app->get('/:gameKeyParam/:gameInstanceKeyParam/operations/:objectKeyParam/collections/:collectionKey/itemPositionByValue/:itemValue', function ($param1,$param2,$param3,$param4,$param5)
{
	try {
        $db = getConnection();
        include 'faars/operations/getItemPositionInCollectionByValue.php';
		$arrayResponse = array();
		getItemPositionInCollectionByValue($param1,$param2,$param3,$param4,$param5,$db,$arrayResponse);
		echo json_encode($arrayResponse);
    }catch(PDOException $e){
    	$arrayResponse = array();
		$arrayResponse["status"] = 1;
		$arrayResponse["message"] = $e->getMessage();
        echo json_encode($arrayResponse);
    }

});

$app->delete('/:gameKeyParam/:gameInstanceKeyParam/operations/:objectKeyParam/collections/:collectionKey/deleteByValue/:itemValue', function ($param1,$param2,$param3,$param4,$param5)
{
	try {
        $db = getConnection();
        include 'faars/operations/deleteItemFromCollectionByValue.php';
		$arrayResponse = array();
		deleteItemFromCollectionByValue($param1,$param2,$param3,$param4,$param5,$db,$arrayResponse);
		echo json_encode($arrayResponse);
    }catch(PDOException $e){
    	$arrayResponse = array();
		$arrayResponse["status"] = 1;
		$arrayResponse["message"] = $e->getMessage();
        echo json_encode($arrayResponse);
    }

});

$app->delete('/:gameKeyParam/:gameInstanceKeyParam/operations/:objectKeyParam/collections/:collectionKey/deleteByPosition/:index', function ($param1,$param2,$param3,$param4,$param5)
{
	try {
        $db = getConnection();
        include 'faars/operations/deleteItemFromCollectionByPosition.php';
		$arrayResponse = array();
		deleteItemFromCollectionByPosition($param1,$param2,$param3,$param4,$param5,$db,$arrayResponse);
		echo json_encode($arrayResponse);
    }catch(PDOException $e){
    	$arrayResponse = array();
		$arrayResponse["status"] = 1;
		$arrayResponse["message"] = $e->getMessage();
        echo json_encode($arrayResponse);
    }

});

$app->get('/:gameKeyParam/:gameInstanceKeyParam/gameObjects/:objectKeyParam/collections/collectionExists/:collectionKey', function ($param1,$param2,$param3,$param4)
{
	try {
        $db = getConnection();
        include 'faars/game_objects/collectionExistsInObjectByObjectKey.php';
		$arrayResponse = array();
		collectionExistsInObjectByObjectKey($param1,$param2,$param3,$param4,$db,$arrayResponse);
		echo json_encode($arrayResponse);
    }catch(PDOException $e){
    	$arrayResponse = array();
		$arrayResponse["status"] = 1;
		$arrayResponse["message"] = $e->getMessage();
        echo json_encode($arrayResponse);
    }

});

$app->get('/:gameKeyParam/:gameInstanceKeyParam/gameObjects/:objectKeyParam/secondaryAttributes/attributeExists/:attributeKey', function ($param1,$param2,$param3,$param4)
{
	try {
        $db = getConnection();
        include 'faars/game_objects/secondaryAttributeExistsInObjectByObjectKey.php';
		$arrayResponse = array();
		secondaryAttributeExistsInObjectByObjectKey($param1,$param2,$param3,$param4,$db,$arrayResponse);
		echo json_encode($arrayResponse);
    }catch(PDOException $e){
    	$arrayResponse = array();
		$arrayResponse["status"] = 1;
		$arrayResponse["message"] = $e->getMessage();
        echo json_encode($arrayResponse);
    }

});

$app->get('/:gameKeyParam/:gameInstanceKeyParam/gameObjects/:objectKeyParam/secondaryAttributes/:attributeKey', function ($param1,$param2,$param3,$param4)
{
	try {
        $db = getConnection();
        include 'faars/game_objects/getSecondaryAttributeValueInObjectByObjectKey.php';
		$arrayResponse = array();
		getSecondaryAttributeValueInObjectByObjectKey($param1,$param2,$param3,$param4,$db,$arrayResponse);
		echo json_encode($arrayResponse);
    }catch(PDOException $e){
    	$arrayResponse = array();
		$arrayResponse["status"] = 1;
		$arrayResponse["message"] = $e->getMessage();
        echo json_encode($arrayResponse);
    }

});

$app->get('/:gameKeyParam/:gameInstanceKeyParam/gameObjects/:objectKeyParam/collections/:collectionKey', function ($param1,$param2,$param3,$param4)
{
	try {
        $db = getConnection();
        include 'faars/game_objects/getCollectionValueInObjectByObjectKey.php';
		$arrayResponse = array();
		getCollectionValueInObjectByObjectKey($param1,$param2,$param3,$param4,$db,$arrayResponse);
		echo json_encode($arrayResponse);
    }catch(PDOException $e){
    	$arrayResponse = array();
		$arrayResponse["status"] = 1;
		$arrayResponse["message"] = $e->getMessage();
        echo json_encode($arrayResponse);
    }

});

$app->post('/:gameKeyParam/:gameInstanceKeyParam/scheduledEvent', function ($param1,$param2)
{
	$request = Slim::getInstance()->request();
    $gameObject = json_decode($request->getBody());
    try {
        $db = getConnection();
        include 'faars/events/addScheduledEvent.php';
		$arrayResponse = array();
		addScheduledEvent($param1,$param2,$gameObject,$db,$arrayResponse);
		echo json_encode($arrayResponse);
    }catch(PDOException $e){
    	$arrayResponse = array();
		$arrayResponse["status"] = 1;
		$arrayResponse["message"] = $e->getMessage();
        echo json_encode($arrayResponse);
    }

});

$app->get('/:gameKeyParam/:gameInstanceKeyParam/runScheduledEvent', function ($param1,$param2)
{
    try {
        $db = getConnection();
        include 'faars/events/runScheduledEvent.php';
		$arrayResponse = array();
		runScheduledEvent($param1,$param2,"scheduledEvent",$db,$arrayResponse);
		echo json_encode($arrayResponse);
    }catch(PDOException $e){
    	$arrayResponse = array();
		$arrayResponse["status"] = 1;
		$arrayResponse["message"] = $e->getMessage();
        echo json_encode($arrayResponse);
    }

});

$app->post('/notifyExternalSystem', function ()
{
	$request = Slim::getInstance()->request();
    $gameObject = json_decode($request->getBody());
    try {
        $db = getConnection();
        include 'faars/misc/notifyExternalSystem.php';
		$arrayResponse = array();
		notifyExternalSystem($gameObject,$arrayResponse);
		echo json_encode($arrayResponse);
    }catch(PDOException $e){
    	$arrayResponse = array();
		$arrayResponse["status"] = 1;
		$arrayResponse["message"] = $e->getMessage();
        echo json_encode($arrayResponse);
    }

});

$app->run();

function getConnection() {
    $dbhost="";
    $dbuser="";
    $dbpass="";
    $dbname="";
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}

?>