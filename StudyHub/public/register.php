<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="css/register.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;700&display=swap" rel="stylesheet">
    <script src="js/register.js"></script>
</head>
<body>
    <div class="form-container">
        <h1 class="form-title">StudyHub</h1>
        <p>Inscrivez-vous pour profiter pleinement de l'expérience <strong>StudyHub</strong>. </p><br>
        <form id="registrationForm" action="/../includes/auth/register_handler.php" method="POST" class="form">
        <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" required placeholder="NOM">
            </div>
            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom" required placeholder="Prénom">
            </div>
            <div class="form-group">
                <label for="id">Identifiant</label>
                <input type="text" id="id" name="id" required placeholder="Nom d'utilisateur">
            </div>
            <div class="form-group">
                <label for="mdp">Mot de passe</label>
                <input type="password" id="mdp" name="mdp" required placeholder="Mot de passe">
            </div>
            <button type="submit" class="submit-btn">S'inscrire</button>
        </form>
        <p class="redirect-text">Vous avez déjà un compte ? <a href="login.php">Se connecter</a></p>
    </div>
</body>
</html>
