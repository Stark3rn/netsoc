<!DOCTYPE html>
<head>
<title>NetSoc</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<nav>
<ul>
<?php
include("utils.php");
$pages = get_all_pages("./pages");
foreach($pages as $page) {
        $name = str_replace(".php", "", $page);
?>
<li>
    <a href="index.php?page=<?= $page ?>"><?= $name ?></a>
</li>
<?php
}
?>
</ul>
</nav>
<?php
if(isset($_GET["page"])) {
        $arg = $_GET["page"];
        if(in_array($arg,$pages)) {
                include("pages/".$arg);
        } else {
                echo("Sorry... Anubis protects the deaths, but... This page doesn't exists.");
        }
} else {
        include("pages/accueil.php");
}
?>
</body>
