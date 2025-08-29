<?php
$servername = "localhost";
$dbusername = "root"; // your DB username
$dbpassword = "";     // your DB password
$dbname = "krconnect";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
