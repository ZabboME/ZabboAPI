<?php
class Queries extends Database {
    public $sso;
    public function __construct($sso){
        $this->sso = $sso;
    }
    public function getUsername(){
        $pdo = new Database();
        $query = $pdo->prepare("SELECT username FROM users WHERE auth_ticket = :sso");
        $query->execute(array(":sso" => $this->sso));
        if($query->rowCount() != 0){
            while($row = $query->fetch(PDO::FETCH_ASSOC)){
                return $row["username"];
            }
        }
        else return null;
    }
    public function getId(){
        $pdo = new Database();
        $query = $pdo->prepare("SELECT id FROM users WHERE auth_ticket = :sso");
        $query->execute(array(":sso" => $this->sso));
        if($query->rowCount() != 0){
            while($row = $query->fetch(PDO::FETCH_ASSOC)){
                return $row["id"];
            }
        }
        else return null;
    }
    public function updateBanner($bannerId){
        $pdo = new Database();
        $query = $pdo->prepare("UPDATE users SET banner_id = :bannerId WHERE auth_ticket = :sso");
        $query->execute(array(":bannerId" => $bannerId, ":sso" => $this->sso));
    }
    public function getBannerByUsername($username){
        $pdo = new Database();
        $query = $pdo->prepare("SELECT banner_id FROM users WHERE username = :username");
        $query->execute(array(":username" => $username));
        if($query->rowCount() != 0){
            while($row = $query->fetch(PDO::FETCH_ASSOC)){
                return $row["banner_id"];
            }
        }
        else return 0;
    }
    public function updateBannerBySso($bannerId, $sso){
        $pdo = new Database();
        $query = $pdo->prepare("UPDATE users SET banner_id = :bannerId WHERE auth_ticket = :sso");
        $query->execute(array(":bannerId" => $bannerId, ":sso" => $sso));
    }
    public function getUsernameIconByUsername($userID){
        $pdo = new Database();
        $query = $pdo->prepare("SELECT value FROM users_custom_settings WHERE user_id = :userID AND `key` = 'cmd_setcolor.icon'");
        $query->execute(array(":userID" => $userID));
        if($query->rowCount() != 0){
            while($row = $query->fetch(PDO::FETCH_ASSOC)){
                return $row["value"];
            }
        }
        else return 0;
    }
    public function updateusernameiconById($usernameicon, $sso){
        $pdo = new Database();
        $query = $pdo->prepare("UPDATE users_custom_settings SET value = :usernameicon WHERE user_id IN (SELECT id FROM users WHERE auth_ticket = :sso) AND `key` = 'cmd_setcolor.icon'");
        $query->execute(array(":usernameicon" => $usernameicon, ":sso" => $sso));
    }
}
?>