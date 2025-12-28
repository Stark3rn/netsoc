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

<main>
    <h1>Connexion</h1>
    <a href="index.php?page=register.php" style="color: var(--text-color);">Pas encore de compte ? S'inscrire</a>
    <div id="formdiv" class="commentbox">
        <?php if (isset($error)): ?>
            <p class="errmsg"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST" style="width: 100%;">
            <input type="text" name="login" placeholder="Pseudo" required 
                   style="width:100%; margin-bottom: 10px; padding: 8px;">
            
            <input type="password" name="pass" placeholder="Mot de passe" required 
                   style="width:100%; margin-bottom: 10px; padding: 8px;">
            
            <button type="submit" style="width:100%; cursor:pointer; padding: 10px; background: var(--success-color); color: var(--nav-color); font-weight: bold; border-radius: 8px;">
                Entrer
            </button>
        </form>
        
    </div>
</main>
