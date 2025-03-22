<?php
session_start();

include __DIR__ . '/../includes/config/db_connect.php';

$sender_id = $_POST['sender_id']; 
$receiver_id = $_POST['receiver_id']; 
$message = $_POST['message'];  

$stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, content) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $sender_id, $receiver_id, $message);

$stmt->execute();
$stmt->close();
$conn->close();
?>