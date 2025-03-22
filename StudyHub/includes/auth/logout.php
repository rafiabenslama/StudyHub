<?php 
session_start();

include __DIR__ . '/../config/db_connect.php';

$stmt = $conn->prepare("UPDATE connexion SET connecte = 0 WHERE id = ?");
$stmt->bind_param("s", $_SESSION['user_id']);
$stmt->execute();
$stmt->close();
$conn->close();

$_SESSION=array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_unset();
session_destroy();

header("Location:/index.php");
?> 