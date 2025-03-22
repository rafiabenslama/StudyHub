<?php
session_start();
include __DIR__ . '/../config/db_connect.php';

$userProfileId = isset($_GET['user_id']) ? $_GET['user_id'] : $_SESSION['user_id'];

$query = "SELECT annonces.* FROM annonces JOIN likes ON annonces.id = likes.annonce_id WHERE likes.user_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $userProfileId);
$stmt->execute();
$result = $stmt->get_result();
$annonces = [];

while ($row = $result->fetch_assoc()) {
    $annonces[] = $row;
}

echo json_encode($annonces);
$stmt->close();
$conn->close();
?>

