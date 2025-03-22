<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Vous devez être connecté pour accéder à cette page.";
    header("Location: /index.php");
    exit; 
}

$user_id = $_SESSION['user_id'];

include __DIR__ . '/../config/db_connect.php';

if (!$conn) {
    echo json_encode(['error' => 'Erreur de connexion à la base de données']);
    exit;
}

$query = "SELECT id FROM utilisateurs WHERE id != ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode($users);

$conn->close();
?>
