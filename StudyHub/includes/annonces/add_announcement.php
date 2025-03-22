<?php
session_start();

include __DIR__ . '/../config/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = $_POST['titre'];
    $categorie = $_POST['categorie'];
    $lieu = $_POST['lieu'];
    $date = $_POST['date'];
    $description = $_POST['description'];
    $utilisateur = $_SESSION['user_id']; 

    $stmt = $conn->prepare("INSERT INTO annonces (utilisateur, date, categorie, lieu, description, titre) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $utilisateur, $date, $categorie, $lieu, $description, $titre);

    if ($stmt->execute()) {
        $response = ["success" => true, "result" => "Annonce ajoutée avec succès!"];
    } else {
        $response = ["success" => false, "result" => "Erreur : " . $stmt->error];
    }
    
    $stmt->close();
    $conn->close();
    
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
