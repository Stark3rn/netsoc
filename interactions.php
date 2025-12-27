<?php
require_once __DIR__ . '/../database.php';
header('Content-Type: application/json');

$id_user = check_session();
if (!$id_user) exit;
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $id_post = (int)$_POST['id_post'];
    $check = $Database->prepare("SELECT id FROM likes WHERE id_post = ? AND id_user = ?");
    $check->execute([$id_post, $id_user]);
    if ($check->fetch()) 
    {
        $stmt = $Database->prepare("DELETE FROM likes WHERE id_post = ? AND id_user = ?");
        $stmt->execute([$id_post, $id_user]);
    } 
    else 
    {
        $stmt = $Database->prepare("INSERT INTO likes (id_post, id_user) VALUES (?, ?)");
        $stmt->execute([$id_post, $id_user]);
    }
    echo json_encode(["success" => true]);
}
?>