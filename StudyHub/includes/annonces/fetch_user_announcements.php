<?php
session_start();
include __DIR__ . '/../config/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Utilisateur non connecté']);
    exit;  
}

$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : $_SESSION['user_id'];

$response = [];

$stmt = $conn->prepare("SELECT * FROM annonces WHERE utilisateur = ? ORDER BY date DESC");
if ($stmt) {
    $stmt->bind_param('s', $user_id); 
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
    } else {
        echo json_encode(['error' => 'Erreur d\'exécution de la requête']);
        exit;
    }
    $stmt->close();
} else {
    echo json_encode(['error' => 'Erreur de préparation de la requête']);
    exit;
}

$conn->close();
echo json_encode($response);
?>
