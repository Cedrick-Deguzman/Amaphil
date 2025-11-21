<?php
$servername = "20.0.0.3";
$username = "radius";
$password = "Password123";
$dbname = "radius";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
