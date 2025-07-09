<?php
$host = "localhost";
$db_name = "poultry_farm";
$username = "root"; 
$password = "123456";   

$conn = new mysqli($host, $username, $password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
