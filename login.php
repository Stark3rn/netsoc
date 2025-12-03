<?php
   session_start();
   $db = new PDO('sqlite:db.sqlite');

   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       $username = trim($_POST['username']);
       $password = $_POST['password'];

       $stmt = $db->prepare('SELECT * FROM users WHERE username = ?');
       $stmt->execute([$username]);
       $user = $stmt->fetch();

       if ($user && password_verify($password, $user['password'])) {
           $_SESSION['user'] = $username;
           header('Location: files.php');
           exit;
       } else {
           $error = "Identifiants invalides.";
       }
   }
   ?>
   <!DOCTYPE html>
   <html>
   <head>
       <link rel="stylesheet" href="style.css">
       <title>Connexion</title>
   </head>
   <body>
   <div class="container animate__animated animate__fadeInDown">
   <form method="post">
     <h2>Connexion</h2>
     <input name="username" placeholder="Nom d’utilisateur" required>
     <input name="password" type="password" placeholder="Mot de passe" required>
     <button type="submit">Connexion</button>
     <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
     <p>Pas encore inscrit ? <a href="register.php">Créer un compte</a></p>
   </form>
   </div>

   <!-- Animate.css CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/anima
   </body>
   </html>