<?php
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);
ini_set('log_errors', '1');
header('Access-Control-Allow-Origin: *');
include_once "config.php";
include_once "Rcon.php";
include_once "Database.php";
include_once "User.php";
session_start();
if(isset($_GET["type"]) && $_GET["type"] == "getEmojis"){
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
if (isset($_GET["type"]) && $_GET["type"] == "getOnlineCounter") {
    if (file_exists('counter.php')) {
        ob_start();
        include 'counter.php';
        $onlineData = ob_get_clean();
    } else {
        $onlineData = false;
    }
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
    header('Content-Type: application/json');
    echo json_encode($online);
    exit;
}
if(isset($_GET["type"]) && isset($_GET["password"]) && isset($_GET["username"])){
    $rcon = new Rcon(RCON_HOST, RCON_PORT, $_GET["password"]);
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
    $rcon = new Rcon(RCON_HOST, RCON_PORT, RCON_PASS);
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
    function validateNumeric($param, $name) {
        if (!isset($param) || !is_numeric($param) || $param < 0) {
            http_response_code(400);
            die("Invalid $name parameter");
        }
        return (int)$param;
    }
    function validateColorName($color, $name) {
        if (!isset($color) || !preg_match('/^[a-zA-Z]{1,20}$/', $color)) {
            http_response_code(400);
            die("Invalid $name format (expected color name like Black, Silver, Red)");
        }
        return $color;
    }
    if($_GET["type"] == "changebanner"){
        $banner = $_GET["banner"] ?? null;
        if($banner !== "none") {
            $banner = validateNumeric($banner, "banner");
        }
        $user->updateBannerBySso($banner, $_GET["sso"]);
        exit();
    }
   if($_GET["type"] == "getbanner"){
        $username = $_GET["username"] ?? '';
        if(!preg_match('/^[a-zA-Z0-9\-\_\.]+$/', $username)) {
            http_response_code(400);
            die("Invalid username format");
        }
        echo $user->getBannerByUsername($username);
        exit();
    }
    if($_GET["type"] == "changeusernameicon"){
        $usernameicon = $_GET["usernameicon"] ?? null;
        if($usernameicon !== "none") {
             if (!is_numeric($usernameicon) || $usernameicon < 0) {
                 http_response_code(400);
                 echo "Invalid usernameicon"; 
                 exit();
             }
        }
        $user->updateusernameiconById($usernameicon, $_GET["sso"]);
        exit(); 
    }
    if($_GET["type"] == "getusernameicon"){
        $userID = validateNumeric($_GET["userID"] ?? null, "userID");
        echo $user->getUsernameIconByUsername($userID);
        exit();
    }
    if($_GET["type"] == "reloadRoom"){
        echo $rcon->reloadRoom($username);
        exit();
    }
    if($_GET["type"] == "sendalert"){
        $message = $_GET["message"] ?? '';
        if (strlen($message) > 200) {
            http_response_code(400);
            die("Message too long (max 200 characters)");
        }
        if (!preg_match('/^[a-zA-Z0-9\s\.\,\!\?\-\:]+$/', $message)) {
            http_response_code(400);
            die("Invalid message format (only letters, numbers, spaces, and basic punctuation)");
        }
        $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
        echo $rcon->sendAlert($UserID, $message);
        exit();
    }
    if($_GET["type"] == "executecommand"){
        $allowedCommands = [
            ':about',
            ':help',
            ':commands',
            ':pickall',
            ':empty',
        ];
        $command = $_GET["command"] ?? ':about';
        if (!in_array($command, $allowedCommands)) {
            http_response_code(400);
            die("Command not allowed. Allowed commands: " . implode(', ', $allowedCommands));
        }
        echo $rcon->newexecutecommand($UserID, $command);
        exit();
    }
    if($_GET["type"] == "talkuser"){
        $allowedChatTypes = ['0', '1'];
        $chattype = $_GET["chattype"] ?? '';
        if (!in_array($chattype, $allowedChatTypes)) {
            http_response_code(400);
            die("Invalid chat type");
        }
        $message = $_GET["message"] ?? '';
        if (!preg_match('/^[0-9a-zA-Z]{1,10}$/', $message)) {
            http_response_code(400);
            die("Invalid message format (only alphanumeric, max 10 chars)");
        }
        echo $rcon->talkUser($UserID, $chattype, $message);
        exit();
    }
    if($_GET["type"] == "seticoncommand"){
        $iconID = $_GET["iconID"] ?? null;
        if($iconID !== "none") {
            $iconID = validateNumeric($iconID, "iconID");
        }
        echo $rcon->seticoncommand($UserID, $iconID);
        exit();
    }
    if($_GET["type"] == "setmodifiercommand"){
        $modifierID = $_GET["modifierID"] ?? null;
        if($modifierID !== "none") {
            $modifierID = validateNumeric($modifierID, "modifierID");
        }
        echo $rcon->setmodifiercommand($UserID, $modifierID);
        exit();
    }
    if($_GET["type"] == "setcolorscommand"){
        $color = validateColorName($_GET["color"] ?? null, "color");
        echo $rcon->setcolorscommand($UserID, $color);
        exit();
    }
    if($_GET["type"] == "setbordercolorscommand"){
        $bordercolor = validateColorName($_GET["bordercolor"] ?? null, "bordercolor");
        echo $rcon->setbordercolorscommand($UserID, $bordercolor);
        exit();
    }
}
else echo "Missing parameters.";
?>