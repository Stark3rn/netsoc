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



<?php
} else {
	echo("Session ok !");
}
?>
</main>
</body>
