<?php
$errUser = "";






if (isset($_POST["action"]) && $_POST["action"] === "add_user") 
{
    if (empty($_POST["username"])) 
    {
        $errUser .= "Username à renseigner<br>";
    }
    if (empty($_POST["email"])) 
    {
        $errUser .= "Email à renseigner<br>";
    }
    if (empty($_POST["pass"]) || empty($_POST["repeat_pass"])) 
    {
        $errUser .= "Mot de passe / confirmation manquants<br>";
    }
    if ($_POST["pass"] !== $_POST["repeat_pass"]) 
    {
        $errUser .= "Les mots de passe sont différents<br>";
    }
    if (empty($errUser)) 
    {
        include_once(__DIR__ . "/../database.php");
        $args = [
            "username" => $_POST["username"],
            "email" => $_POST["email"],
            "password" => $_POST["pass"]
        ];
        $ret = add_user($args);
        if ($ret !== true) 
        {
            $errUser .= "ERREUR : ";
            switch ($ret) 
            {
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
        } 
        else 
        {
            header("Location: index.php?page=login.php");
            exit;
        }
    }
}
?>





<link rel="stylesheet" href="../style.css">



<main>
    <h1>Créer un compte</h1>
    <div id="formdiv">
        <div class="commentbox" style="width: 100%; box-sizing: border-box;">
            <a href="index.php?page=login.php">Déjà inscrit ? Se connecter</a>
            
            <?php if ($errUser != ""): ?>
                <p class="errmsg"><?= $errUser ?></p>
            <?php endif; ?>

            <form method="POST" action="" style="margin-top: 20px;">
                <input type="hidden" name="action" value="add_user">
                
                <input type="text" name="username" placeholder="Nom d'utilisateur" 
                       style="width:100%; margin-bottom: 10px; padding: 8px;">
                
                <input type="password" name="pass" placeholder="Mot de Passe" 
                       style="width:100%; margin-bottom: 10px; padding: 8px;">
                
                <input type="password" name="repeat_pass" placeholder="Répéter Mot de Passe" 
                       style="width:100%; margin-bottom: 10px; padding: 8px;">
                
                <input type="text" name="email" placeholder="Adresse Mail" 
                       style="width:100%; margin-bottom: 20px; padding: 8px;">
                
                <button type="submit" style="width:100%; cursor:pointer; padding: 10px; background: var(--success-color); color: var(--nav-color); font-weight: bold; border-radius: 8px;">
                    S'enregistrer
                </button>
            </form>
        </div>
    </div>
</main>