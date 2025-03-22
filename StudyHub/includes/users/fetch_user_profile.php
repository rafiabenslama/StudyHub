<?php
session_start();
include __DIR__ . '/../config/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Utilisateur non connecté']);
    exit;
}

$userId = $_SESSION['user_id'];
$response = [];

$stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmt->bind_param('s', $userId);
if ($stmt->execute()) {
    $result = $stmt->get_result();
    if ($user = $result->fetch_assoc()) {
        echo json_encode($user); 
    } else {
        echo json_encode(['error' => 'Aucun utilisateur trouvé avec cet ID']);
    }
} else {
    echo json_encode(['error' => 'Erreur d\'exécution de la requête']);
}
$stmt->close();
$conn->close();
?>
