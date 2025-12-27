<?php
require_once(__DIR__ . "/../database.php");






if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $u = validate_login($_POST['login'], $_POST['pass']);
    if ($u)
    {
        create_or_update_session($u['id_user']);
        header("Location: index.php?page=accueil.php");
        exit;
    }
    else
    {
        $error = "Pseudo ou mot de passe incorrect";
    }
}
?>
<link rel="stylesheet" href="../style.css">
<main>
    <div id="formdiv">
        <h2>Connexion</h2>

        <?php if (isset($error)): ?>
            <p class="errmsg"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST" style="width: 100%;">
            <input type="text" name="login" placeholder="Pseudo" required 
                   style="width:100%; margin-bottom:10px; padding:8px;">
            
            <input type="password" name="pass" placeholder="Mot de passe" required 
                   style="width:100%; margin-bottom:10px; padding:8px;">
            
            <button type="submit" style="width:100%; cursor:pointer; padding:10px;">
                Entrer
            </button>
        </form>
        
        <p>
            <a href="index.php?page=register.php">Pas encore de compte ? S'inscrire</a>
        </p>
    </div>
</main>