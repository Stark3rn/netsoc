<?php
require_once __DIR__ . '/../database.php';
header('Content-Type: application/json');

$q = trim($_GET['q'] ?? '');

if (!$q) {
    // renvoyer quelques suggestions aléatoires si la recherche est vide
    $stmt = $Database->query("SELECT username, profile_pic FROM users ORDER BY RAND() LIMIT 5");
} else {
    // recherche par username partiel
    $stmt = $Database->prepare("SELECT username, profile_pic FROM users WHERE username LIKE ? LIMIT 10");
    $stmt->execute(["%$q%"]);
}

$results = $stmt->fetchAll();

echo json_encode($results);
