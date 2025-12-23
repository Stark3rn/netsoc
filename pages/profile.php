<?php
require_once(__DIR__ . "/../database.php");

if (!isset($_COOKIE['usersession'])) {
    die("Vous n'êtes pas connecté.");
}

$sessionToken = $_COOKIE['usersession'];
$idUser = getUserIdFromSession($sessionToken);

if (!$idUser) {
    die("Session invalide.");
}

$user = getUserProfile($idUser);

if (!$user) {
    die("Utilisateur introuvable ou supprimé.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Profil de <?= htmlspecialchars($user['username']) ?></title>

    <style>
        .profile-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
        }

        .username {
            font-size: 2rem;
            font-weight: 600;
            margin: 0 0 6px 0;
            text-align: left;
        }

        .text-muted {
            font-size: 0.9rem;
            color: #a3acb4ff;
            margin: 0;
        }
    </style>
</head>

<div class="profile-container">
    <h2 class="username"><?= htmlspecialchars($user["username"]) ?></h2>
    <p class="text-muted">
        Membre depuis le : <?= htmlspecialchars($user["created"]) ?>
    </p>
</div>
</html>
