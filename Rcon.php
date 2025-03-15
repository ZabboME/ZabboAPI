<?php
class Rcon{
    private $ip;
    private $port;

    public function __construct($i, $p){
        $this->ip = $i;
        $this->port = $p;
    }

    public function openSocket(){
        $tcpsocket = stream_socket_client("tcp://$this->ip:$this->port", $errno, $errorMessage);
        if ($tcpsocket === false) return null;
        else return $tcpsocket;
        
    }

    public function send($tcpsocket, $command){
        fwrite($tcpsocket, $command);
        $response = fread($tcpsocket, 3001);
        fclose($tcpsocket);
        return $response;
    }

    public function sendAlert($UserID, $message){
        $tcpsocket = $this->openSocket();
        if($this->send($tcpsocket, '{"key": "alertuser", "data": { "user_id" => "' .$UserID. '", "message" => "'.$message.'" }}') == "Error") return false;
        else return true;
    }

    public function talkUser($UserID, $chattype, $message){
        $tcpsocket = $this->openSocket();
        if($this->send($tcpsocket, '{"key": "talkuser", "data": { "user_id" => "' .$UserID. '", "bubble" => "-1", "type" => "'.$chattype.'", "message" => "'.$message.'" }}') == "Error") return false;
        else return true;
    } 

    public function newexecutecommand($UserID, $command){
        $tcpsocket = $this->openSocket();
        if($this->send($tcpsocket, '{"key": "executecommand", "data": { "user_id" => "' .$UserID. '", "command" => "'.$command.'" }}') == "Error") return false;
        else return true;
    }

    public function executecommand($UserID){
        $tcpsocket = $this->openSocket();
        if($this->send($tcpsocket, '{"key": "executecommand", "data": { "user_id" => "' .$UserID. '", "command" => ":about" }}') == "Error") return false;
        else return true;
    }

    public function seticoncommand($UserID, $iconID){
        $tcpsocket = $this->openSocket();
        if($this->send($tcpsocket, '{"key": "executecommand", "data": { "user_id" => "' .$UserID. '", "command" => ":seticon ' .$iconID. '" }}') == "Error") return false;
        else return true;
    }

    public function setmodifiercommand($UserID, $modifierID){
        $tcpsocket = $this->openSocket();
        if($this->send($tcpsocket, '{"key": "executecommand", "data": { "user_id" => "' .$UserID. '", "command" => ":setmodifier ' .$modifierID. '" }}') == "Error") return false;
        else return true;
    }

    public function setcolorscommand($UserID, $color){
        $tcpsocket = $this->openSocket();
        if($this->send($tcpsocket, '{"key": "executecommand", "data": { "user_id" => "' .$UserID. '", "command" => ":setcolor ' .$color. '" }}') == "Error") return false;
        else return true;
    }

    public function setbordercolorscommand($UserID, $bordercolor){
        $tcpsocket = $this->openSocket();
        if($this->send($tcpsocket, '{"key": "executecommand", "data": { "user_id" => "' .$UserID. '", "command" => ":setborder ' .$bordercolor. '" }}') == "Error") return false;
        else return true;
    }

    public function oldexecutecommand($UserID) {
        $tcpsocket = $this->openSocket();
        $data = array(
            "user_id" => "$UserID",
            "command" => ":about",
        );
        $jsonData = json_encode(array("key" => "executecommand", "data" => $data));
    
        if ($this->send($tcpsocket, $jsonData) == "Error") {
            return false;
        } else {
            return true;
        }
    }
}
       