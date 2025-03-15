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
           // "message" => "about"
        );
        $jsonData = json_encode(array("key" => "executecommand", "data" => $data));
    
        if ($this->send($tcpsocket, $jsonData) == "Error") {
            return false;
        } else {
            return true;
        }
    }


    /*{
        "key": "executecommand",
        "data": {
            "user_id": "2",
            "command": "about"
        }
    }*/


   /*public function mutePlayer($username, $time){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;

        if($this->send($tcpsocket, "MUTE|$username|$time") == "Error") return false;
        else return true;
    }

    public function sendGlobalAlert($alert){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;

        if($this->send($tcpsocket, "GLOBALALERT|$alert") == "Error") return false;
        else return true;
    }

    public function giveBadge($username, $badge){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;

        if($this->send($tcpsocket, "GIVEBADGE|$username|$badge") == "Error") return false;
        else return true;
    }

    public function giveCoin($username, $coin, $quantity){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;

        if($this->send($tcpsocket, "GIVECOIN|$username|$coin|$quantity") == "Error") return false;
        else return true;
    }

    public function refresh($type){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;

        if($this->send($tcpsocket, "REFRESH|$type") == "Error") return false;
        else return true;
    }

    public function goToRoom($username, $roomid){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;

        if($this->send($tcpsocket, "GOTOROOM|$username|$roomid") == "Error") return false;
        else return true;
    }

    public function needHelp($username){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;

        if($this->send($tcpsocket, "NEEDHELP|$username") == "Error") return false;
        else return true;
    }

    public function buyBadge($badge, $username){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;

        return $this->send($tcpsocket, "BUYBADGE|$username|$badge");
    }

    public function openFloor($username){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;

        return $this->send($tcpsocket, "OPENFLOOR|$username");
    }

    public function reloadRoom($username){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;

        return $this->send($tcpsocket, "RELOADROOM|$username");
    }

    public function autoFloor($username){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;

        return $this->send($tcpsocket, "AUTOFLOOR|$username");
    }

    public function maxFloor($username){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;

        return $this->send($tcpsocket, "MAXFLOOR|$username");
    }

    public function alturaFloor($username, $value){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;

        return $this->send($tcpsocket, "ALTURAFLOOR|$username|$value");
    }

    public function rotacionFloor($username, $value){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;
    
        return $this->send($tcpsocket, "ROTACIONFLOOR|$username|$value");
    }
    
    public function estadoFloor($username, $value){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;
    
        return $this->send($tcpsocket, "ESTADOFLOOR|$username|$value");
    }
    
    public function openBuilderTool($username){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;
    
        return $this->send($tcpsocket, "OPENBUILDERTOOL|$username");
    }
    
    public function buyGalaxyPass($username){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;
    
        return $this->send($tcpsocket, "BUYGALAXYPASS|$username");
    }
    
    public function openGalaxyPassEmu($username){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;
    
        return $this->send($tcpsocket, "OPENGALAXYPASS|$username");
    }
    
    public function getInfoOpenGalaxyPassEmu($username){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;
    
        return $this->send($tcpsocket, "GETINFOOPENGALAXYPASS|$username");
    }
    
    public function betDadosCasino($username, $quantity, $coin, $number){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;
    
        return $this->send($tcpsocket, "BETDADOSCASINO|$username|$quantity|$coin|$number");
    }
    
    public function betRuletaSuelo($username, $quantity, $coin, $number){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;
    
        return $this->send($tcpsocket, "BETRULETANORMAL|$username|$quantity|$coin|$number");
    }
    
    public function betRuletaPared($username, $quantity, $coin){
        $tcpsocket = $this->openSocket();
        if($tcpsocket == null) return false;
    
        return $this->send($tcpsocket, "BETRULETACOLORES|$username|$quantity|$coin");
    }*/
}
       