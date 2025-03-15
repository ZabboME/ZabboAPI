<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');

include_once "Rcon.php";
include_once "Database.php";
include_once "User.php";

session_start();

if($_GET["type"] == "getEmojis"){
$emojisData = file_get_contents('emojis.json');

if ($emojisData === false) {
	http_response_code(500);
	echo 'Internal Server Error';
	exit;
}

$emojis = json_decode($emojisData, true);

if ($emojis === null) {
	http_response_code(500);
	echo 'Internal Server Error';
	exit;
}

	header('Content-Type: application/json');

	echo json_encode($emojis);
	exit;
} 

if ($_GET["type"] == "getOnlineCounter") {
    $onlineData = file_get_contents('counter.php');
    
    if ($onlineData === false) {
        http_response_code(500);
        echo 'Internal Server Error';
        exit;
    }
    
    $online = json_decode($onlineData, true);
    
    if ($online === null) {
        http_response_code(500);
        echo 'Internal Server Error';
        exit;
    }
    
    header('Content-Type: application/php');
    
    echo json_encode($online);
    exit;
}

if(isset($_GET["type"]) && isset($_GET["password"]) && isset($_GET["username"])){
    $rcon = new Rcon("127.0.0.1", 3001, $_GET["password"]);

    if(isset($_SESSION["security_cooldown"])){
        if($_SESSION["security_cooldown"] > time() - 2){
            echo "Security cooldown, try again.";
            exit();
        }
    }

    $_SESSION["security_cooldown"] = time();

    
}
else if(isset($_GET["type"]) && isset($_GET["sso"])){
    if(isset($_SESSION["security_cooldown"])){
        if($_SESSION["security_cooldown"] > time() - 1){
            echo "Security cooldown, try again.";
            exit();
        }
    }

    $_SESSION["security_cooldown"] = time();
    $rcon = new Rcon("127.0.0.1", 3001, "pass");
    $user = new Queries($_GET["sso"]);

    $username = $user->getUsername();
    if($username == null){
        echo "ERROR";
        exit();
    }

    $UserID = $user->getId();
    if($UserID == null){
        echo "ERROR";
        exit();
    }

    if($_GET["type"] == "changebanner"){
        $user->updateBannerBySso($_GET["banner"], $_GET["sso"]);
        exit();
    }
    
   if($_GET["type"] == "getbanner"){
        echo $user->getBannerByUsername($_GET["username"]);
        exit();
    }

    if($_GET["type"] == "changeusernameicon"){
        $user->updateusernameiconById($_GET["usernameicon"], $_GET["sso"]);
        exit(); 
    }
    
    if($_GET["type"] == "getusernameicon"){
        echo $user->getUsernameIconByUsername($_GET["userID"]);
        exit();
    }

    if($_GET["type"] == "reloadRoom"){
        
        echo $rcon->reloadRoom($username);
        exit();
    }

    if($_GET["type"] == "sendalert"){
        
        echo $rcon->sendAlert($UserID, $_GET["message"]);
        exit();
    }

    
    if($_GET["type"] == "talkuser"){

        echo $rcon->talkUser($UserID, $_GET["chattype"], $_GET["message"]);
        exit();
    }

    if($_GET["type"] == "executecommand"){
        
        echo $rcon->executecommand($UserID);
        exit();
    }

    if($_GET["type"] == "seticoncommand"){
        
        echo $rcon->seticoncommand($UserID, $_GET["iconID"]);
        exit();
    }

    if($_GET["type"] == "setmodifiercommand"){
        
        echo $rcon->setmodifiercommand($UserID, $_GET["modifierID"]);
        exit();
    }

    if($_GET["type"] == "setcolorscommand"){
        
        echo $rcon->setcolorscommand($UserID, $_GET["color"]);
        exit();
    }
    
    if($_GET["type"] == "setbordercolorscommand"){
        
        echo $rcon->setbordercolorscommand($UserID, $_GET["bordercolor"]);
        exit();
    }
     
}
else echo "Missing parameters.";

?>