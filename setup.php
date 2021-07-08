<?php 
include 'conn.php';
// Creating a connection
$conn = new mysqli($servername, $dbusername, $dbpassword);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
// Creating a database named newDB
$sql = "CREATE DATABASE loginflowDB;";
if ($conn->query($sql) === false) {
    echo "Error creating database: " . $conn->error;
    exit;
} 
$UseDB = "use loginflowDB;";
if ($conn->query($UseDB) === false) {
    echo "Error using database: " . $conn->error;
    exit;
} 
$tablesql = "CREATE TABLE users( 
                email VARCHAR(100) NOT NULL,
                fname VARCHAR(40) NOT NULL,
                lname VARCHAR(40),
                password VARCHAR(100) NOT NULL,
                auth TINYINT(1) NOT NULL DEFAULT 0,
                PRIMARY KEY ( email )
            );";
if ($conn->query($tablesql) === false) {
    echo "Error creating table: " . $conn->error;
    exit;
}
$addAdminUser = "INSERT INTO `users` (`email`, `fname`, `lname`, `password`, `auth`) VALUES ('admin@admin.co', 'admin', NULL, 'admin', '1');"; 
if ($conn->query($addAdminUser) === false) {
    echo "Error creating table entry: " . $conn->error;
    exit;
}

echo "Setup was Successful, you can use LoginFlow";

// closing connection
$conn->close();
?>