<?php
require_once __DIR__ . '/../database.php';
header('Content-Type: application/json');

$idUser = check_session();
$method = $_SERVER['REQUEST_METHOD'];

// =========================
// GET POSTS
// =========================
if ($method === 'GET') {
    $followingOnly = isset($_GET['following']) && $_GET['following'] == 1;
    $userId = $_GET['user_id'] ?? null;

    if ($followingOnly && $idUser) {
        // Posts des utilisateurs suivis + les siens
        $stmt = $Database->prepare("
            SELECT posts.*, users.username, users.profile_pic,
                   (SELECT COUNT(*) FROM likes WHERE likes.id_post = posts.id) AS likes_count
            FROM posts
            JOIN users ON users.id_user = posts.id_user
            WHERE posts.id_user = ? OR posts.id_user IN (
                SELECT id_followed FROM follow WHERE id_follower = ?
            )
            ORDER BY posts.created DESC
        ");
        $stmt->execute([$idUser, $idUser]);
    } elseif ($userId) {
        // Posts d'un utilisateur spécifique
        $stmt = $Database->prepare("
            SELECT posts.*, users.username, users.profile_pic,
                   (SELECT COUNT(*) FROM likes WHERE likes.id_post = posts.id) AS likes_count
            FROM posts
            LEFT JOIN users ON posts.id_user = users.id_user
            WHERE posts.id_user = ?
            ORDER BY posts.created DESC
        ");
        $stmt->execute([(int)$userId]);
    } else {
        // Tous les posts (limité à 50)
        $stmt = $Database->prepare("
            SELECT posts.*, users.username, users.profile_pic,
                   (SELECT COUNT(*) FROM likes WHERE likes.id_post = posts.id) AS likes_count
            FROM posts
            LEFT JOIN users ON posts.id_user = users.id_user
            ORDER BY posts.created DESC
            LIMIT 50
        ");
        $stmt->execute();
    }

    $posts = $stmt->fetchAll() ?: [];
    echo json_encode($posts);
    exit;
}

// =========================
// POST NEW POST
// =========================
if ($method === 'POST') {
    if (!$idUser) {
        echo json_encode(["success" => false, "message" => "Non connecté"]);
        exit;
    }

    $content = $_POST['content'] ?? '';
    $image = $_POST['image_url'] ?? null;

    if (!$content && !$image) {
        echo json_encode(["success" => false, "message" => "Contenu vide"]);
        exit;
    }

    $stmt = $Database->prepare("INSERT INTO posts (id_user, content, image_url) VALUES (?, ?, ?)");
    $res = $stmt->execute([$idUser, htmlspecialchars($content), $image]);
    echo json_encode(["success" => $res]);
    exit;
}

// =========================
// DEFAULT RESPONSE
// =========================
echo json_encode([]);
