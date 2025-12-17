<?php
$errUser = "";

if(isset($_POST["action"]) && $_POST["action"] === "add_user") {

    if(empty($_POST["username"])) {
        $errUser .= "Username à renseigner<br>";
    }

    if(empty($_POST["email"])) {
        $errUser .= "Email à renseigner<br>";
    }

    if(empty($_POST["pass"]) || empty($_POST["repeat_pass"])) {
        $errUser .= "Mot de passe / confirmation manquants<br>";
    }

    if($_POST["pass"] !== $_POST["repeat_pass"]) {
        $errUser .= "Les mots de passe sont différents<br>";
    }

    if(empty($errUser)) {
	include_once(__DIR__ . "/../database.php");

        $args = [
            "username" => $_POST["username"],
            "email" => $_POST["email"],
            "password" => $_POST["pass"]
        ];

        $ret = add_user($args);

        if($ret !== true) {
            $errUser .= "ERREUR : ";
            switch($ret) {
                case -2:
                    $errUser .= "Username invalide (5-20 caractères, max 3 - ou _)";
                    break;
                case -3:
                    $errUser .= "Email invalide";
                    break;
                case -4:
                    $errUser .= "Mot de passe invalide (sécurité insuffisante)";
                    break;
                default:
                    $errUser .= "Erreur inconnue";
            }
        } else {
            header("Location: index.php?login.php");
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

<h1>Creer un compte</h1>
<div class="loginform">
<a href="index.php?page=login.php">Deja inscrit ?</a>
<?php
if($errUser != "") {
?>
	<p class="errmsg"><?=$errUser?></p>
<?php
}
?>
<form method="POST" action="">
<input type="hidden" name="action" value="add_user">
<input type="text" name="username" value="" placeholder="Nom d'utilisateur"><br>
<input type="password" name="pass" value="" placeholder="Mot de Passe"><br>
<input type="password" name="repeat_pass" value="" placeholder="Repeter Mot de Passe"><br>
<input type="text" name="email" value="" placeholder="Adresse Mail"><br>
<button type="submit">S'enregistrer</button>
</form>
</div>
