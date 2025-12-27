<?php
require_once __DIR__ . '/../database.php';
header('Content-Type: application/json');

$id_user = check_session();
$method = $_SERVER['REQUEST_METHOD'];

// =========================
// UPDATE PROFILE PICTURE
// =========================
if ($method === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_picture') {
    if (!$id_user) {
        echo json_encode(["success" => false]);
        exit;
    }

    $url = $_POST['profile_pic'] ?? '';
    $stmt = $Database->prepare("UPDATE users SET profile_pic = ? WHERE id_user = ?");
    $res = $stmt->execute([$url, $id_user]);
    echo json_encode(["success" => $res]);
    exit;
}

// =========================
// TOGGLE FOLLOW / UNFOLLOW
// =========================
if ($method === 'POST' && isset($_POST['action']) && $_POST['action'] === 'toggle_follow') {
    if (!$id_user) {
        echo json_encode(["success" => false, "message" => "Non connecté"]);
        exit;
    }

    $id_followed = intval($_POST['id_followed']);
    if ($id_followed === $id_user) {
        echo json_encode(["success" => false, "message" => "Impossible de se follow soi-même"]);
        exit;
    }

    // Vérifier si déjà follow
    $stmt = $Database->prepare("SELECT 1 FROM follow WHERE id_follower = ? AND id_followed = ?");
    $stmt->execute([$id_user, $id_followed]);
    $already = $stmt->fetchColumn();

    if ($already) {
        // Unfollow
        $stmt = $Database->prepare("DELETE FROM follow WHERE id_follower = ? AND id_followed = ?");
        $stmt->execute([$id_user, $id_followed]);
        echo json_encode(["success" => true, "following" => false]);
    } else {
        // Follow
        $stmt = $Database->prepare("INSERT INTO follow (id_follower, id_followed) VALUES (?, ?)");
        $stmt->execute([$id_user, $id_followed]);
        echo json_encode(["success" => true, "following" => true]);
    }
    exit;
}

// =========================
// LOGOUT / DELETE SESSION
// =========================
if ($method === 'DELETE') {
    setcookie("usersession", "", time() - 3600, "/");
    echo json_encode(["status" => "deleted"]);
    exit;
}

// =========================
// DEFAULT RESPONSE
// =========================
echo json_encode(["success" => false, "message" => "Action inconnue"]);
