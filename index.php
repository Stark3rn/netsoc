<!DOCTYPE html>
<head>
<title>NetSoc</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<?php
include_once("database.php");
if(!check_session()) { 	//	Ajouter && usersession in DB sessions
?>	
<main>
<?php

	if(isset($_GET["page"]) && @$_GET["page"] == "login.php") {
        	include("pages/login.php");	
	} else if (@$_GET["page"] == "register.php") {
		include("pages/register.php");
	} else {
		include("pages/login.php");
	}
?>

</main>

<?php
} else {
?>
<section class="navbar">
    <nav>
	<ul>
	    <li><a href="?page=accueil.php">Accueil</a></li>
	    <li><a href="?page=profile.php">Mon profil</a></li>
    	</ul>
    </nav>
</section>
<main>
<?php
	if(isset($_GET["page"]) && $_GET["page"] == "profile.php") {
		echo("informations de profil");
	} else {
		include_once("pages/accueil.php");
	}
?>
</main>
<?php
}
?>
</body>
