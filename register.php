<?php
   session_start();
   $db = new PDO('sqlite:db.sqlite');

   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       $username = trim($_POST['username']);
       $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

       $stmt = $db->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
       try {
           $stmt->execute([$username, $password]);
           $user_dir = "uploads/" . $username;
           if (!is_dir($user_dir)) {
               mkdir($user_dir, 0775, true);
               chown($user_dir, 'www-data');
               chgrp($user_dir, 'www-data');
           }

           header('Location: login.php');
           exit;
       } catch (Exception $e) {
           $error = "Utilisateur déjà existant.";
       }
   }
   ?>
   <!DOCTYPE html>
   <html>
   <head>
       <link rel="stylesheet" href="style.css">
       <title>Inscription</title>
   </head>
   <body>
   <div class="container animate__animated animate__fadeInDown">
   <form method="post">
     <h2>Inscription</h2>
     <input name="username" placeholder="Nom d’utilisateur" required>
     <input name="password" type="password" placeholder="Mot de passe" required>
     <button type="submit">Créer un compte</button>
     <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
     <p>Déjà inscrit ? <a href="login.php">Se connecter</a></p>
   </form>
   </div>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/anima
   </body>
   </html>