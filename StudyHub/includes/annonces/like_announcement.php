<?php
session_start();
include __DIR__ . '/../config/db_connect.php';

if (isset($_POST['annonce_id'])) {
    $userId = $_SESSION['user_id'];
    $annonceId = $_POST['annonce_id'];

    $check = $conn->prepare("SELECT * FROM likes WHERE user_id = ? AND annonce_id = ?");
    $check->bind_param("si", $userId, $annonceId);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $delete = $conn->prepare("DELETE FROM likes WHERE user_id = ? AND annonce_id = ?");
        $delete->bind_param("si", $userId, $annonceId);
        $delete->execute();
        echo json_encode(['liked' => false]);
    } else {
        $insert = $conn->prepare("INSERT INTO likes (user_id, annonce_id) VALUES (?, ?)");
        $insert->bind_param("si", $userId, $annonceId);
        $insert->execute();
        echo json_encode(['liked' => true]);
    }
}
?>
