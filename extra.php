<?php
header('Access-Control-Allow-Origin: https://demo.habzone.lol');

class Database {
    private $con;

    public function __construct($host, $user, $password, $database, $port = 3306) {
        $this->con = new mysqli($host, $user, $password, $database, $port);
    }

    public function getConnection() {
        return $this->con;
    }
}

$host = "127.0.0.1";
$user = "root";
$password = 'yourpass';
$database = "yourdb";

$db = new Database($host, $user, $password, $database);
$user = secure($db->getConnection(), $_GET['username']);

function secure($con, $var) {
    $var = trim($var);
    $var = htmlspecialchars($var, ENT_QUOTES, 'UTF-8');
    $var = mysqli_real_escape_string($con, $var);
    return $var;
}


function getUser($db, $username) {
    $con = $db->getConnection();

    $username = secure($con, $username);

    $query = "SELECT `username`, `motto`, `look` FROM `users` WHERE `username` = ?";
    $statement = $con->prepare($query);
    $statement->bind_param("s", $username);
    $statement->execute();
    $result = $statement->get_result();

    if ($result) {
        $row = $result->fetch_assoc();
        if ($row) {
            $response = $row; 
        } else {
            $response = array("error" => "No user found");
        }
    } else {
        $response = array("error" => "Error in query: " . $statement->error);
    }

    $statement->close();

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

function getOnlineUsers($db) {
    $con = $db->getConnection();
    
    $query = "SELECT COUNT(*) as online_count FROM `users` WHERE `online` = '1'";
    $result = $con->query($query);

    if ($result) {
        $row = $result->fetch_assoc();
        $onlineUsersCount = (int)$row['online_count'];
        $response = array("OnlineUsers" => $onlineUsersCount);
    } else {
        $response = array("error" => "Error fetching data");
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit; 
}


if (isset($_GET['action'])) {
    $action = $_GET['action'];
    if ($action == 'getOnlineUsers') {
        getOnlineUsers($db); 
    } 

    if ($action == 'getUser' && isset($_GET['username'])) {

        getUser($db, $_GET['username']);

    }
}

?>
