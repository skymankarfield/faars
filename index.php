<?php
/**
 * Step 1: Require the Slim PHP 5 Framework
 *
 * If using the default file layout, the `Slim/` directory
 * will already be on your include path. If you move the `Slim/`
 * directory elsewhere, ensure that it is added to your include path
 * or update this file path as needed.
 */
require 'Slim/Slim.php';

/**
 * Step 2: Instantiate the Slim application
 *
 * Here we instantiate the Slim application with its default settings.
 * However, we could also pass a key-value array of settings.
 * Refer to the online documentation for available settings.
 */
$app = new Slim();

/**
 * Step 3: Define the Slim application routes
 *
 * Here we define several Slim application routes that respond
 * to appropriate HTTP request methods. In this example, the second
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, and `Slim::delete`
 * is an anonymous function. If you are using PHP < 5.3, the
 * second argument should be any variable that returns `true` for
 * `is_callable()`. An example GET route for PHP < 5.3 is:
 *
 * $app = new Slim();
 * $app->get('/hello/:name', 'myFunction');
 * function myFunction($name) { echo "Hello, $name"; }
 *
 * The routes below work with PHP >= 5.3.
 */
/*
//GET route
$app->get('/', function () { 
    $template = <<<EOT
<!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8"/>
            <title>Slim Framework for PHP 5</title>
            <style>
                html,body,div,span,object,iframe,
                h1,h2,h3,h4,h5,h6,p,blockquote,pre,
                abbr,address,cite,code,
                del,dfn,em,img,ins,kbd,q,samp,
                small,strong,sub,sup,var,
                b,i,
                dl,dt,dd,ol,ul,li,
                fieldset,form,label,legend,
                table,caption,tbody,tfoot,thead,tr,th,td,
                article,aside,canvas,details,figcaption,figure,
                footer,header,hgroup,menu,nav,section,summary,
                time,mark,audio,video{margin:0;padding:0;border:0;outline:0;font-size:100%;vertical-align:baseline;background:transparent;}
                body{line-height:1;}
                article,aside,details,figcaption,figure,
                footer,header,hgroup,menu,nav,section{display:block;}
                nav ul{list-style:none;}
                blockquote,q{quotes:none;}
                blockquote:before,blockquote:after,
                q:before,q:after{content:'';content:none;}
                a{margin:0;padding:0;font-size:100%;vertical-align:baseline;background:transparent;}
                ins{background-color:#ff9;color:#000;text-decoration:none;}
                mark{background-color:#ff9;color:#000;font-style:italic;font-weight:bold;}
                del{text-decoration:line-through;}
                abbr[title],dfn[title]{border-bottom:1px dotted;cursor:help;}
                table{border-collapse:collapse;border-spacing:0;}
                hr{display:block;height:1px;border:0;border-top:1px solid #cccccc;margin:1em 0;padding:0;}
                input,select{vertical-align:middle;}
                html{ background: #EDEDED; height: 100%; }
                body{background:#FFF;margin:0 auto;min-height:100%;padding:0 30px;width:440px;color:#666;font:14px/23px Arial,Verdana,sans-serif;}
                h1,h2,h3,p,ul,ol,form,section{margin:0 0 20px 0;}
                h1{color:#333;font-size:20px;}
                h2,h3{color:#333;font-size:14px;}
                h3{margin:0;font-size:12px;font-weight:bold;}
                ul,ol{list-style-position:inside;color:#999;}
                ul{list-style-type:square;}
                code,kbd{background:#EEE;border:1px solid #DDD;border:1px solid #DDD;border-radius:4px;-moz-border-radius:4px;-webkit-border-radius:4px;padding:0 4px;color:#666;font-size:12px;}
                pre{background:#EEE;border:1px solid #DDD;border-radius:4px;-moz-border-radius:4px;-webkit-border-radius:4px;padding:5px 10px;color:#666;font-size:12px;}
                pre code{background:transparent;border:none;padding:0;}
                a{color:#70a23e;}
                header{padding: 30px 0;text-align:center;}
            </style>
        </head>
        <body>
            <header>
                <a href="http://www.slimframework.com"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAHIAAAA6CAYAAABs1g18AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAABRhJREFUeNrsXY+VsjAMR98twAo6Ao4gI+gIOIKOgCPICDoCjCAjXFdgha+5C3dcv/QfFB5i8h5PD21Bfk3yS9L2VpGnlGW5kS9wJMTHNRxpmjYRy6SycgRvL18OeMQOTYQ8HvIoJKiiz43hgHkq1zvK/h6e/TyJQXeV/VyWBOSHA4C5RvtMAiCc4ZB9FPjgRI8+YuKcrySO515a1hoAY3nc4G2AH52BZsn+MjaAEwIJICKAIR889HljMCcyrR0QE4v/q/BVBQva7Q1tAczG18+x+PvIswHEAslLbfGrMZKiXEOMAMy6LwlisQCJLPFMfKdBtli5dIihRyH7A627Iaiq5sJ1ThP9xoIgSdWSNVIHYmrTQgOgRyRNqm/M5PnrFFopr3F6B41cd8whRUSufUBU5EL4U93AYRnIWimCIiSI1wAaAZpJ9bPnxx8eyI3Gt4QybwWa6T/BvbQECUMQFkhd3jSkPFgrxwcynuBaNT/u6eJIlbGOBWSNIUDFEIwPZFAtBfYrfeIOSRSXuUYCsprCXwUIZWYnmEhJFMIocMDWjn206c2EsGLCJd42aWSyBNMnHxLEq7niMrY2qyDbQUbqrrTbwUPtxN1ZZCitQV4ZSd6DyoxhmRD6OFjuRUS/KdLGRHYowJZaqYgjt9Lchmi3QYA/cXBsHK6VfWNR5jgA1DLhwfFe4HqfODBpINEECCLO47LT/+HSvSd/OCOgQ8qE0DbHQUBqpC4BkKMPYPkFY4iAJXhGAYr1qmaqQDbECCg5A2NMchzR567aA4xcRKclI405Bmt46vYD7/Gcjqfk6GP/kh1wovIDSHDfiAs/8bOCQ4cf4qMt7eH5Cucr3S0aWGFfjdLHD8EhCFvXQlSqRrY5UV2O9cfZtk77jUFMXeqzCEZqSK4ICkSin2tE12/3rbVcE41OBjBjBPSdJ1N5lfYQpIuhr8axnyIy5KvXmkYnw8VbcwtTNj7fDNCmT2kPQXA+bxpEXkB21HlnSQq0gD67jnfh5KavVJa/XQYEFSaagWwbgjNA+ywstLpEWTKgc5gwVpsyO1bTII+tA6B7BPS+0PiznuM9gPKsPVXbFdADMtwbJxSmkXWfRh6AZhyyzBjIHoDmnCGaMZAKjd5hyNJYCBGDOVcg28AXQ5atAVDO3c4dSALQnYblfa3M4kc/cyA7gMIUBQCTyl4kugIpy8yA7ACqK8Uwk30lIFGOEV3rPDAELwQkr/9YjkaCPDQhCcsrAYlF1v8W8jAEYeQDY7qn6tNGWudfq+YUEr6uq6FZzBpJMUfWFDatLHMCciw2mRC+k81qCCA1DzK4aUVfrJpxnloZWCPVnOgYy8L3GvKjE96HpweQoy7iwVQclVutLOEKJxA8gaRCjSzgNI2zhh3bQhzBCQQPIHGaHaUd96GJbZz3Smmjy16u6j3FuKyNxcBarxqWWfYFE0tVVO1Rl3t1Mb05V00MQCJ71YHpNaMcsjWAfkQvPPkaNC7LqTG7JAhGXTKYf+VDeXAX9IvURoAwtTFHvyYIxtnd5tPkywrPafcwbeSuGVwFau3b76NO7SHQrvqhfFE8kM0Wvpv8gVYiYBlxL+fW/34bgP6bIC7JR7YPDubcHCPzIp4+cum7U6NlhZgK7lua3KGLeFwE2m+HblDYWSHG2SAfINuwBBfxbJEIuWZbBH4fAExD7cvaGVyXyH0dhiAYc92z3ZDfUVv+jgb8HrHy7WVO/8BFcy9vuTz+nwADAGnOR39Yg/QkAAAAAElFTkSuQmCC" alt="Slim"/></a>
            </header>
            <h1>Welcome to Slim!</h1>
            <p>
                Congratulations! Your Slim application is running. If this is
                your first time using Slim, start with this <a href="http://www.slimframework.com/learn" target="_blank">"Hello World" Tutorial</a>.
            </p>
            <section>
                <h2>Get Started</h2>
                <ol>
                    <li>The application code is in <code>index.php</code></li>
                    <li>Read the <a href="http://www.slimframework.com/documentation/stable" target="_blank">online documentation</a></li>
                    <li>Follow <a href="http://www.twitter.com/slimphp" target="_blank">@slimphp</a> on Twitter</li>
                </ol>
            </section>
            <section>
                <h2>Slim Framework Community</h2>

                <h3>Support Forum and Knowledge Base</h3>
                <p>
                    Visit the <a href="http://help.slimframework.com" target="_blank">Slim support forum and knowledge base</a>
                    to read announcements, chat with fellow Slim users, ask questions, help others, or show off your cool
                    Slim Framework apps.
                </p>

                <h3>Twitter</h3>
                <p>
                    Follow <a href="http://www.twitter.com/slimphp" target="_blank">@slimphp</a> on Twitter to receive the very latest news
                    and updates about the framework.
                </p>

                <h3>IRC</h3>
                <p>
                    Find Josh Lockhart in the irc.freenode.net "##slim" IRC channel during the day. Say hi, ask questions,
                    or just hang out with fellow Slim users.
                </p>
            </section>
            <section style="padding-bottom: 20px">
                <h2>Slim Framework Extras</h2>
                <p>
                    Custom View classes for Smarty, Twig, Mustache, and other template
                    frameworks are available online in a separate repository.
                </p>
                <p><a href="https://github.com/codeguy/Slim-Extras" target="_blank">Browse the Extras Repository</a></p>
            </section>
        </body>
    </html>
EOT;
    echo $template;
});

//POST route
$app->post('/post', function () {
    echo 'This is a POST route';
});

//PUT route
$app->put('/put', function () {
    echo 'This is a PUT route';
});

//DELETE route
$app->delete('/delete', function () {
    echo 'This is a DELETE route';
});
*/



/**
 * Step 4: Run the Slim application
 *
 * This method should be called last. This is responsible for executing
 * the Slim application using the settings and routes defined above.
 */

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
    $dbhost="localhost";
    $dbuser="faars-rocketfuel";
    $dbpass="rocketfuelgames";
    $dbname="faars-rocketfuel";
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}

?>