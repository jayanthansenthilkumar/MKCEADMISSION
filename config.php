<?php
// Database Configuration
$servername = "localhost";
$dbusername = "root"; // your DB username
$dbpassword = "";     // your DB password
$dbname = "krconnect";

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to UTF-8
$conn->set_charset("utf8");
?>
