<?php
require_once '../database.php';
require_once '../utils.php';
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') 
{
    $res = add_user($_POST);
    echo json_encode(["result" => $res]);
}
if ($method === 'PUT') 
{
    parse_str(file_get_contents("php://input"), $_PUT);
    $user = validate_login($_PUT['login'], $_PUT['pass']);
    
    if ($user) 
    {
        create_or_update_session($user['id_user']);
        echo json_encode(["status" => "connected"]);
    } 
    else 
    {
        echo json_encode(["status" => "error"]);
    }
}




if ($method === 'GET') 
{
    $id_user = check_session();
    echo json_encode(["id_user" => $id_user]);
}



if ($method === 'DELETE') 
{
    $id_user = check_session();
    if ($id_user) 
    {
        setcookie("usersession", "", time() - 3600, "/");
        echo json_encode(["status" => "deleted"]);
    }
    else 
    {
        echo json_encode(["status" => "error"]);
    }
}
?>