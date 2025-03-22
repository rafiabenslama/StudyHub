<?php 
session_start(); 

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Vous devez être connecté pour accéder à cette page.";
    header("Location: /index.php");
    exit; 
}

require_once __DIR__ . '/../includes/config/api_keys.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>StudyHub</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script
        async defer
        src="https://maps.googleapis.com/maps/api/js?key=<?= GOOGLE_MAPS_API_KEY ?>&callback=initMap">
    </script>
    <script>
        var userId1 = <?= json_encode($_SESSION['user_id']); ?>;
    </script>
</head>
<body>
<div class="sidebar">
    <h1><span class="gradient-text">StudyHub</span></h1>
    <ul>
        <li><a id="openModal" class="sidebar-button" href="#">Ajouter une publication</a></li>
        <li><a id="profileBtn" class="sidebar-button"  href="#">Mon profil</a></li>
        <li><a id="Chat" class="sidebar-button"  href="chat.php">Messagerie</a></li>
        <li><a href="/includes/auth/logout.php" class="deconnect-button">Me déconnecter</a></li>
    </ul>
    <div class="connected-users">
    <p class="TitreUsers" >Utilisateurs connectés</p>
    <ul id="user-list" class="user-list"></ul> 
</div>
</div>
<div id="notification-container"></div>

<div class="content">
    <h2>Choisir un campus</h2>
    <div id="map"></div>
<h2>Voir les publications</h2>
    <section class="annonces" id="annonces">
    </section>
    <div id="result"></div> 
    <a href="#" id="resetFilterBtn">Voir toutes les publications</a>
</div>

<div id="annonceModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeAnnonce">&times;</span>
    <h1 class="form-title">Nouvelle publication</h1>
<form id="ajoutAnnonceForm">
        <div class="form-group">
    <label for="titre">Titre</label>
    <input type="text" id="titre" name="titre" placeholder="Titre de votre annonce" required>
    </div>
    <div class="form-group">
    <label for="categorieModal">Catégorie</label>
    <select  id="categorieModal" name="categorie">
        <option value="ateliers">Ateliers</option>
        <option value="cours">Cours</option>
        <option value="evenements">Événements</option>
    </select>
    </div>
    <div class="form-group">
    <label for="lieu">Lieu</label>    
    <select id="lieu" name="lieu" required>
    <option value="Campus Valrose">Campus Valrose</option>
    <option value="Campus Trotabas">Campus Trotabas</option>
    <option value="Campus Pasteur">Campus Pasteur</option>
    <option value="Campus Saint Jean d'Angély">Campus Saint Jean d'Angély</option>
    <option value="Campus Carlone">Campus Carlone</option>
</select>
    </div>
    <div class="form-group">
    <label for="date">Date</label>
    <input type="date" id="date" name="date" required>
    </div>
    <div class="form-group">
    <label for="description">Description</label>
    <textarea id="description" name="description" placeholder="Décrivez votre publication en quelques mots..." required></textarea>
    </div>
    <input type="submit" class="submit" value="Ajouter">
</form>
</div>
</div>

<div id="profileModal" class="profile-modal">
<div class="profile-content">
    <span class="close" id="closeProfile">&times;</span>
    <button id="messageBtn" class="message-button">Message</button>
    <div class="profile-header">
        <img src="images/default-profile.png" alt="Profil Picture">
        <h1><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?></h1>
        <p>@<?= htmlspecialchars($user['id']); ?></p> 
    </div>
    <div class="profile-posts">
        <div class="tab-container">
            <div class="tab" onclick="changeTab(event, 'publies')">Likes</div>
            <div class="tab" onclick="changeTab(event, 'likes')">Publications</div>
        </div>
        <div id="publies" class="tab-content active">
            <div class="post">
            </div>
        </div>
        <div id="likes" class="tab-content">
    <h2>Mes publications</h2>
    </div>
    </div>
    </div>
</div>
<script src="js/dashboard.js"></script>
</body>
</html>
