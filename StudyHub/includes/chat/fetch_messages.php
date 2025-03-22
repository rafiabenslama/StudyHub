<?php
session_start();

include __DIR__ . '/../config/db_connect.php';

$sender_id = $_GET['sender_id'];  
$receiver_id = $_GET['receiver_id'];

$query = "SELECT sender_id, content, timestamp FROM messages WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY timestamp ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssss", $sender_id, $receiver_id, $receiver_id, $sender_id);

$stmt->execute();
$result = $stmt->get_result();
$messages = [];

while ($row = $result->fetch_assoc()) {
    $messages[] = [
        'sender_id' => $row['sender_id'],
        'content' => $row['content'],
        'timestamp' => $row['timestamp']
    ];
}

echo json_encode($messages);

$stmt->close();
$conn->close();
?>

