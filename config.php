<?php
$host = "localhost";        // Host
$db_name = "poultry_farm";  // Your database name
$username = "root";         // Default XAMPP user
$password = "";             // Leave empty unless you set a password

// Create connection
$conn = new mysqli($host, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
} 