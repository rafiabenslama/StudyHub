<?php
session_start();
include __DIR__ . '/../config/db_connect.php';

$lieu = isset($_GET['lieu']) ? $_GET['lieu'] : '';

$query = $lieu ? "SELECT * FROM annonces WHERE lieu = ? ORDER BY date DESC" : "SELECT * FROM annonces ORDER BY date DESC";
$stmt = $conn->prepare($query);

if ($lieu) {
    $stmt->bind_param("s", $lieu);
}

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
