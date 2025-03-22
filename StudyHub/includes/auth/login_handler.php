<?php
session_start();
header('Content-Type: application/json');

include __DIR__ . '/../config/db_connect.php';

$id = isset($_POST['id']) ? $_POST['id'] : null;
$mdp = isset($_POST['mdp']) ? $_POST['mdp'] : null;

if (is_null($id) || is_null($mdp)) {
    echo json_encode(["success" => false, "message" => "Identifiant et mot de passe sont requis."]);
    exit;
}

try {
    $stmt = $conn->prepare("SELECT mdp FROM utilisateurs WHERE id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($mdp, $row['mdp'])) {
            $_SESSION['user_id'] = $id;
            $stmt->close();

            $stmt = $conn->prepare("UPDATE connexion SET connecte = 1 WHERE id = ?");
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $stmt->close();

            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => "Identifiant ou mot de passe incorrect."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Identifiant ou mot de passe incorrect."]);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Erreur de serveur."]);
} finally {
    $conn->close();
}
?>
