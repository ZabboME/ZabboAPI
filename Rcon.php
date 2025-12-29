<?php
class Rcon{
    private $ip;
    private $port;
    private $password;
    public function __construct($i, $p, $pass = null){
        $this->ip = $i;
        $this->port = $p;
        $this->password = $pass;
    }
    public function openSocket(){
        $tcpsocket = stream_socket_client("tcp://$this->ip:$this->port", $errno, $errorMessage, 3);
        if ($tcpsocket === false) return null;
        else return $tcpsocket;
    }
    public function send($tcpsocket, $command){
        if(!$tcpsocket) return "Error";
        fwrite($tcpsocket, $command);
        $response = fread($tcpsocket, 3001);
        fclose($tcpsocket);
        return $response;
    }
    private function sendExcecuteCommand($UserID, $commandStr) {
        $tcpsocket = $this->openSocket();
        $payload = array(
            "key" => "executecommand",
            "data" => array(
                "user_id" => (string)$UserID,
                "command" => $commandStr
            )
        );
        $jsonPayload = json_encode($payload);
        if($this->send($tcpsocket, $jsonPayload) == "Error") return false;
        else return true;
    }
    public function sendAlert($UserID, $message){
        $tcpsocket = $this->openSocket();
        $payload = array(
            "key" => "alertuser",
            "data" => array(
                "user_id" => (string)$UserID,
                "message" => $message
            )
        );
        if($this->send($tcpsocket, json_encode($payload)) == "Error") return false;
        else return true;
    }
    public function talkUser($UserID, $chattype, $message){
        $tcpsocket = $this->openSocket();
        $payload = array(
            "key" => "talkuser",
            "data" => array(
                "user_id" => (string)$UserID,
                "bubble" => "-1",
                "type" => $chattype,
                "message" => $message
            )
        );
        if($this->send($tcpsocket, json_encode($payload)) == "Error") return false;
        else return true;
    } 
    public function newexecutecommand($UserID, $command){
        return $this->sendExcecuteCommand($UserID, $command);
    }
    public function executecommand($UserID){
        return $this->sendExcecuteCommand($UserID, ":about");
    }
    public function seticoncommand($UserID, $iconID){
        if ($iconID !== "none" && !is_numeric($iconID)) return false;
        if ($iconID !== "none") $iconID = (int)$iconID;
        return $this->sendExcecuteCommand($UserID, ":seticon " . $iconID);
    }
    public function setmodifiercommand($UserID, $modifierID){
        if ($modifierID !== "none" && !is_numeric($modifierID)) return false;
        if ($modifierID !== "none") $modifierID = (int)$modifierID;
        return $this->sendExcecuteCommand($UserID, ":setmodifier " . $modifierID);
    }
    public function setcolorscommand($UserID, $color){
        if (!preg_match('/^[a-zA-Z]{1,20}$/', $color)) return false;
        return $this->sendExcecuteCommand($UserID, ":setcolor " . $color);
    }
    public function setbordercolorscommand($UserID, $bordercolor){
        if (!preg_match('/^[a-zA-Z]{1,20}$/', $bordercolor)) return false;
        return $this->sendExcecuteCommand($UserID, ":setborder " . $bordercolor);
    }
    public function reloadRoom($username) {
        $tcpsocket = $this->openSocket();
        $payload = array(
            "key" => "reloadroom",
            "data" => array(
                "username" => $username
            )
        );
        if($this->send($tcpsocket, json_encode($payload)) == "Error") return false;
        else return true;
    }
    public function oldexecutecommand($UserID) {
        return $this->executecommand($UserID);
    }
}
?>