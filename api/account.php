<?php
// Sera appelée par requête POST
// /api/account
// "login" valant le pseudonyme à inscrire
// "pass" valant le mot de passe du compte
// "mail" valant le mail associé au compte
// Inscrit en base de donnée ces valeurs
// et connecte l’utilisateur
function register($login, $pass, $mail)
// Sera appelée par requête PUT
// /api/account
// "login" valant le pseudonyme à inscrire
// "pass" valant le mot de passe du compte
// Inscrit en cookie les valeurs de connexion
// Permet donc de se connecter si valeurs vide
function login($login, $pass)
// Exploite les cookies (variable $_COOKIE)
// Regarde si les valeurs de connexion sont dans les
// cookies. Renvoi les valeurs utilisateur si oui.
// La fonction est accessible via une requête GET
// /api/account
function is_logged()
// Sera appelée par requête DELETE. Détruit le compte
// actuellement connecté
// /api/account
function unregister()
}
?>

