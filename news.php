<?php
require_once __DIR__ . '/../database.php';
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') 
{
    $uid = $_GET['user_id'] ?? null;
    $sql = "SELECT posts.*, users.username, users.profile_pic, 
            (SELECT COUNT(*) FROM likes WHERE likes.id_post = posts.id) as likes_count 
            FROM posts 
            LEFT JOIN users ON posts.id_user = users.id_user ";    
    if ($uid) 
    {
        $sql .= "WHERE posts.id_user = ? ORDER BY posts.created DESC";
        $stmt = $Database->prepare($sql);
        $stmt->execute([(int)$uid]);
    } 
    else 
    {
        $sql .= "ORDER BY posts.created DESC LIMIT 50";
        $stmt = $Database->prepare($sql);
        $stmt->execute();
    }
    echo json_encode($stmt->fetchAll() ?: []);
}
if ($method === 'POST') 
{
    $id = check_session();
    if ($id && !empty($_POST['content'])) 
    {
        $stmt = $Database->prepare("INSERT INTO posts (id_user, content, image_url) VALUES (?, ?, ?)");
        $res = $stmt->execute([$id, htmlspecialchars($_POST['content']), $_POST['image_url'] ?? null]);
        echo json_encode(["success" => $res]);
    }
}
?>