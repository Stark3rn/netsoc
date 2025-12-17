<?php
function connect_db() {
	$data = file_get_contents("database.json");
	$conninfos = json_decode($data,true);
	$mysqlClient = new PDO('mysql:host='.$conninfos["hostname"].";dbname=".$conninfos["dbname"].";charset=utf8", $conninfos["login"], $conninfos["password"]);
	return($mysqlClient);
}

$Database = connect_db();

function select_fields($table, $id=-1) {
	global $Database;
	$id = (int) $id;
	if($id===-1) {
		$statement = $Database->prepare("SELECT * FROM `$table`");
		$statement->execute();		
	} else {
		$statement = $Database->prepare("SELECT * FROM `$table` WHERE id = :id");
		$statement->bindParam(':id', $id, PDO::PARAM_INT);
        	$statement->execute();
	}
	$results = $statement->fetchAll(PDO::FETCH_ASSOC);
	return $results;
}

function insert_fields($table, $fields) {
	global $Database;

	if(!is_array($fields)) {
		return;
	}

    	$rows = implode(", ", array_keys($fields));
    	$values = ":" . implode(", :", array_keys($fields));

    	$sqlQuery = "INSERT INTO $table ($rows) VALUES ($values)";

    	$stmt = $Database->prepare($sqlQuery);

   	foreach ($fields as $key => $value) {
        	$stmt->bindValue(":$key", $value);
    	}

    	return $stmt->execute();
}

function edit_fields($table, $fields, $id) {
	global $Database;
	$id = (int) $id;
	if ($id < 0) {
        	return false;
	}
	$set = [];
    	foreach ($fields as $key => $value) {
        	$set[] = "`$key` = :$key";
    	}
    	$setString = implode(", ", $set);

    	$sqlQuery = "UPDATE `$table` SET $setString WHERE id = :id";
    	$stmt = $Database->prepare($sqlQuery);

    	foreach ($fields as $key => $value) {
        	$stmt->bindValue(":$key", $value);
    	}
    	$stmt->bindValue(":id", $id, PDO::PARAM_INT);

    	return $stmt->execute();
}

function delete_fields($table, $id) {
    global $Database;

    $id = (int) $id;
    if ($id <= 0) {
        return false;
    }

    $sqlQuery = "UPDATE `$table` SET `deleted` = NOW() WHERE id = :id";
    $stmt = $Database->prepare($sqlQuery);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    return $stmt->execute();
}

/* 		Fonctions specifiques		*/

// Expressions regulieres (regex) avec preg_match()
// https://regex101.com/

function add_user($fields) {
    global $Database;

    if(!isset($fields["username"], $fields["email"], $fields["password"])) {
        return -1; // champs manquants
    }

    // Regex username (� 20 caractères, 0 à 3 _ ou -)
    $regex_username = "/^(?=(?:[^_-]*[_-]){0,3}[^_-]*$)[A-Za-z0-9_-]{5,20}$/";

    // Regex password (1 maj, 2 minuscules, 2 chiffres, 1 spécial, 10-20)
    $regex_password = "/^(?=(?:.*[A-Z]){1,})(?=(?:.*[a-z]){2,})(?=(?:.*\d){2,})(?=(?:.*[!@#$%^&*()\-_=+{};:,<.>]){1,})[A-Za-z0-9!@#$%^&*()\-_=+{};:,<.>]{10,20}$/";

    if(!preg_match($regex_username, $fields["username"])) {
        return -2;
    }

    if(!preg_match($regex_password, $fields["password"])) {
        return -4;
    }

    if(!filter_var($fields["email"], FILTER_VALIDATE_EMAIL)) {
        return -3;
    }

    $hashed_password = password_hash($fields["password"], PASSWORD_DEFAULT);

    $SQL = [
        "username" => $fields["username"],
        "email" => $fields["email"],
        "pass_hash" => $hashed_password
    ];

    $res = insert_fields("users", $SQL);

    return $res;
}

function validate_login($login, $pass) {
    global $Database;

    $stmt = $Database->prepare("SELECT id_user, username, email, pass_hash FROM users 
				WHERE username = :login OR email = :login
				AND deleted = NULL 
                                LIMIT 1");

    $stmt->execute([
        "login" => $login
    ]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$user || !password_verify($pass, $user["pass_hash"])) {
        return false;
    }

    return $user;
}

function generateToken() {
    return bin2hex(random_bytes(64));
}

function create_or_update_session($id_user) {
    global $Database;

    $token = bin2hex(random_bytes(64));
    $expiration = date("Y-m-d H:i:s", time() + 7200);
    $ip = $_SERVER['REMOTE_ADDR'];

    $stmt = $Database->prepare("
        INSERT INTO session (id_user, token, ip, expiration_date)
        VALUES (?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            token = VALUES(token),
            ip = VALUES(ip),
            expiration_date = VALUES(expiration_date)
    ");

    $stmt->execute([$id_user, $token, $ip, $expiration]);

    setcookie("usersession", $token, [
        "expires"  => time() + 3600,
        "path"     => "/",
        "secure"   => true,
        "httponly" => true,
        "samesite" => "Strict"
    ]);
}

function check_session() {
    global $Database;

    if (!isset($_COOKIE['usersession'])) {
        return null;
    }

    $token = $_COOKIE['usersession'];

    $stmt = $Database->prepare("
        SELECT id_user 
        FROM session 
        WHERE token = ?
        AND expiration_date > NOW()
    ");
    $stmt->execute([$token]);

    if($ret = $stmt->fetchColumn()) {
	return($ret);
    } else {
	return(null);
    }
}

?>
