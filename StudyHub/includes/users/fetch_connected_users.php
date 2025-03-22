<?php
session_start();
include __DIR__ . '/../config/db_connect.php';

$stmt = $conn->prepare("SELECT u.id, u.nom, u.prenom FROM utilisateurs u JOIN connexion c ON u.id = c.id WHERE c.connecte = 1");

if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
    
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = [
            'id' => $row['id'],
            'nom' => $row['nom'],
            'prenom' => $row['prenom']
        ]; 
    }

    echo json_encode($users);
} else {
    echo json_encode(["error" => "Erreur lors de la préparation de la requête: " . $conn->error]);
}
$stmt->close();
$conn->close();
?>
