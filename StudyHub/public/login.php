<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="css/login.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;700&display=swap" rel="stylesheet">
    <script src="js/login.js"></script>
</head>
<body>
    <div class="form-container">
        <h1 class="form-title">StudyHub</h1>
        <p class="form-description">Connectez-vous pour accéder à toutes nos fonctionnalités.</p>
        <form id="loginForm" action="/includes/auth/login_handler.php" method="POST" class="form">
        <div class="form-group">
                <label for="id">Identifiant</label>
                <input type="text" id="id" name="id" required class="form-control" placeholder="Nom d'utilisateur">
            </div>
            <div class="form-group">
                <label for="mdp">Mot de passe</label>
                <input type="password" id="mdp" name="mdp" required class="form-control" placeholder="Mot de passe">
            </div>
            <p id="error-message" class="error-message" style="display: none;"></p>
            <button type="submit" class="submit-btn">Se connecter</button>
        </form>
        <p class="redirect-text">Vous n'avez pas de compte ? <a href="register.php">S'inscrire</a></p>
    </div>
</body>
</html>

