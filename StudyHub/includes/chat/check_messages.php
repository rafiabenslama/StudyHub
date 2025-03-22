<?php
session_start();
include __DIR__ . '/../config/db_connect.php';

$user_id = $_SESSION['user_id']; 

$query = "SELECT id, sender_id, content FROM messages WHERE receiver_id = ? AND notified = 0 ORDER BY timestamp DESC LIMIT 1";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$response = ['newMessage' => false];

if ($row = $result->fetch_assoc()) {
    $response = [
        'newMessage' => true,
        'sender' => $row['sender_id'],
        'message' => $row['content']
    ];

    $updateQuery = "UPDATE messages SET notified = 1 WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $messageId = $row['id'];
    $updateStmt->bind_param("i", $messageId);
    $updateStmt->execute();
    $updateStmt->close();
}

echo json_encode($response);

$stmt->close();
$conn->close();
?>
