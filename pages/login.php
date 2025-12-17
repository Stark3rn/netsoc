<?php
$errUser = "";
if(isset($_POST["action"]) && $_POST["action"] === "auth_user") {

    if(empty($_POST["login"]) || empty($_POST["pass"])) {
        $errUser = "Tous les champs sont obligatoires";
    } else {

        require_once(__DIR__ . "/../database.php");

        $user = validate_login($_POST["login"], $_POST["pass"]);

        if($user === false) {
            $errUser = "Identifiants invalides";
        } else {
	    create_session($user["id_user"]);
            exit;
        }
    }
}

?>

<style>
.loginform {
	align-items: center;
	text-align: center;
	margin-left: 35%;
	margin-right: 35%;
	background: #52729C;
	border: 1px solid #E6E9EF;
	border-radius: 0.6rem;
	padding: 1rem;
}

.loginform a {
	text-decoration: none;
	color: #E6E9EF;
	transition: color 0.3s ease, transform 0.3s ease;
}

.loginform a:hover {
	transform: scale(1.15);
	color: #258C88;
}

.loginform input {
	width: 20rem;
	height: 2rem;
	margin: 0.3rem;
}

.loginform button {
        width: 15rem;
        height: 2.3rem;
        margin: 0.4rem;
        background: #70ff88;
        color: white;
        border: 1px solid #E6E9EF;
        border-radius: 0.7rem;
        transition: color 0.3s ease, transform 0.3s ease;
}

.loginform button:hover {
        transform: scale(1.15);
        cursor: pointer;
}

</style>
<h1>Se connecter</h1>
<div class="loginform">
<a href="index.php?page=register.php">Pas de compte ?</a>
<?php
if($errUser != "") {
?>
	<p class="errmsg"><?=$errUser?></p>
<?php
}
?>
<form method="POST" action="">
<input type="hidden" name="action" value="auth_user">
<input type="text" name="login" value="" placeholder="Nom d'utilisateur / Email"><br>
<input type="password" name="pass" value="" placeholder="Mot de Passe"><br>
<button type="submit">Se connecter</button>
</form>
</div>
