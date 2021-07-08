<?php 

$servername = "localhost";
$dbusername = "root";
$dbpassword = "root";
$dbname = "loginflowDB";

function fetchUsers(){
    global $servername, $dbusername, $dbpassword, $dbname;
    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT * FROM users";
    try{
        $result = $conn->query($sql);
    }catch(Exception $e){
        echo "fetch failed : ".$e->getMessage();
        $conn->close();
        return;
    }
    $conn->close();
    return $result->fetch_all(MYSQLI_ASSOC);
}
?>