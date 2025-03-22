<?php

$servername = "YOUR_SERVER_NAME";
$username = "YOUR_USERNAME";
$password = "YOUR_USERNAME";
$dbname = "YOUR_DB_NAME";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?> 

