<?php
function connect_db() 
{
    $path = __DIR__ . "/database.json";
    $data = file_get_contents($path);
    $conninfos = json_decode($data, true);
    return new PDO('mysql:host='.$conninfos["hostname"].";dbname=".$conninfos["dbname"].";charset=utf8", $conninfos["login"], $conninfos["password"], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
}

$Database = connect_db();

function check_session() 
{
    global $Database;
    if (!isset($_COOKIE['usersession'])) return null;
    $stmt = $Database->prepare("SELECT id_user FROM session WHERE token = ? AND expiration_date > NOW()");
    $stmt->execute([$_COOKIE['usersession']]);
    return $stmt->fetchColumn() ?: null;
}

function validate_login($login, $pass) 
{
    global $Database;
    $stmt = $Database->prepare("SELECT * FROM users WHERE username = ? AND deleted = 0");
    $stmt->execute([$login]);
    $user = $stmt->fetch();
    if ($user && password_verify($pass, $user['pass_hash'])) 
    {
        return $user;
    }
    return false;
}

function create_or_update_session($id_user) 
{
    global $Database;
    $token = bin2hex(random_bytes(32));
    $stmt = $Database->prepare("INSERT INTO session (id_user, token, expiration_date) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 1 DAY))");
    $stmt->execute([$id_user, $token]);
    setcookie("usersession", $token, time() + 86400, "/");
}

function getUserProfile($id) 
{
    global $Database;
    $stmt = $Database->prepare("SELECT id_user, username, email, created, profile_pic FROM users WHERE id_user = ? LIMIT 1");
    $stmt->execute([$id]);
    return $stmt->fetch();
}
?>