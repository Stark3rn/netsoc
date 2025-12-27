<?php
require_once __DIR__ . '/../database.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$id_user = check_session();
if ($method === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_picture') 
{
    if (!$id_user) 
    {
        echo json_encode(["success" => false]);
        exit;
    }
    $url = $_POST['profile_pic'] ?? '';
    $stmt = $Database->prepare("UPDATE users SET profile_pic = ? WHERE id_user = ?");
    $res = $stmt->execute([$url, $id_user]);
    echo json_encode(["success" => $res]);
}
if ($method === 'DELETE') 
{
    setcookie("usersession", "", time() - 3600, "/");
    echo json_encode(["status" => "deleted"]);
}
?>