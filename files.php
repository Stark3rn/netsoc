<?php
   session_start();
   if (!isset($_SESSION['user'])) {
       header('Location: login.php');
       exit;
   }

   $username = $_SESSION['user'];
   $user_dir = "uploads/" . $username;

   if (!is_dir($user_dir)) {
       mkdir($user_dir, 0775, true);
       chown($user_dir, 'www-data');
       chgrp($user_dir, 'www-data');
   }

   if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
       $target = $user_dir . "/" . basename($_FILES['file']['name']);
       move_uploaded_file($_FILES['file']['tmp_name'], $target);
   }
   ?>
   <!DOCTYPE html>
   <html>
   <head>
       <link rel="stylesheet" href="style.css">
       <title>Mes fichiers</title>
   </head>
   <body>
   <div class="container animate__animated animate__fadeInDown">
   <h2>Bienvenue <?=htmlspecialchars($username)?></h2>

   <form method="post" enctype="multipart/form-data">
       <input type="file" name="file" required>
       <button type="submit">Uploader</button>
   </form>

   <h3>Fichiers :</h3>
   <ul>
   <?php
   foreach (glob($user_dir . "/*") as $f) {
       echo "<li><a href='$f'>" . basename($f) . "</a></li>";
   }
   ?>
   </ul>

   <a href="logout.php">Déconnexion</a>
   </div>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/anima
   </body>
   </html>