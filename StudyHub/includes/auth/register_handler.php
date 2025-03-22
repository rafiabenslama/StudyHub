<?php
session_start();
header('Content-Type: application/json'); 
include __DIR__ . '/../config/db_connect.php';

$id = $_POST['id'];
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$mdp = $_POST['mdp'];

if (!$id || !$nom || !$prenom || !$mdp) {
    echo json_encode(["success" => false, "message" => "Tous les champs doivent être remplis."]);
    exit; 
}

if (strlen($mdp) < 8) {
    echo json_encode(["success" => false, "message" => "Le mot de passe doit contenir au moins 8 caractères."]);
    exit;
}

$hashed_mdp = password_hash($mdp, PASSWORD_DEFAULT);

$stmt = $conn->prepare("SELECT id FROM utilisateurs WHERE id = ?");
$stmt->bind_param("s", $id); 
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Cet ID est déjà utilisé. Veuillez en choisir un autre."]);
    $stmt->close();
} else {
    $stmt->close();
    $stmt = $conn->prepare("INSERT INTO utilisateurs (id, nom, prenom, mdp) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $id, $nom, $prenom, $hashed_mdp);
    if ($stmt->execute()) {
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO connexion (id, connecte) VALUES (?, 0)");
        $stmt->bind_param("s", $id);
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Compte créé et statut de connexion initialisé."]);
        } else {
            echo json_encode(["success" => false, "message" => "Erreur lors de la mise à jour du statut de connexion."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Erreur lors de la création du compte."]);
    }
    $stmt->close();
}

$conn->close();
?>

