<?php 
session_start(); 

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Vous devez être connecté pour accéder à cette page.";
    header("Location: /index.php");
    exit; 
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;700&display=swap" rel="stylesheet">
    <title>Messagerie</title>
    <link rel="stylesheet" href="css/chat.css">
    <script>
    var senderId = <?php echo json_encode($_SESSION['user_id']); ?>;
    var initialUserId = <?= json_encode($_GET['user_id'] ?? null); ?>;
</script>
</head>
<body>
<div id="user-list-container">
<a href="dashboard.php" class="return-button">Retour à l'accueil</a>
    <h2>Discussions</h2>
    <div id="user-list"></div>
</div>
    <div id="chat-container">
        <div id="chat-header"></div> 
        <div id="chat-box"></div> 
        <div class="EnvoyerButton">
        <input type="text" id="message-input" placeholder="Ecrire un message...">
        <button onclick="sendMessage()">Envoyer</button>
        </div>
        <input type="hidden" id="receiverId" value=""> 
    </div>
    <script src="js/chat.js"></script>
</body>
</html>

