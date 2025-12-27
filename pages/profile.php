<?php
require_once(__DIR__ . "/../database.php");
$id = check_session();
if (!$id) 
{
    return;
}
$u = getUserProfile($id);
?>



<link rel="stylesheet" href="../style.css">






<main>
    <div id="formdiv">
        <img id="p" src="<?= $u['profile_pic'] ?>" 
             style="width:120px; height:120px; border-radius:50%; object-fit:cover; border: 2px solid var(--accent-color);">
        
        <h2 class="username"><?= htmlspecialchars($u['username']) ?></h2>
        
        <div class="commentbox" style="width: 100%; box-sizing: border-box;">
            <p>Modifier la photo de profil :</p>
            <input type="text" id="nu" placeholder="Nouvelle URL photo" style="width:100%; margin-bottom: 10px; padding: 5px;">
            <button onclick="up()" style="width:100%; cursor:pointer;">Mettre à jour</button>
        </div>
    </div>
</main>





<script>
async function up() 
{
    const url = document.getElementById('nu').value;
    if (!url) 
    {
        return;
    }
    const fd = new FormData();
    fd.append('action', 'update_picture');
    fd.append('profile_pic', url);
    
    const r = await fetch('api/account.php', 
    { 
        method: 'POST', 
        body: fd 
    });
    const res = await r.json();
    if (res.success) 
    {
        document.getElementById('p').src = url;
        document.getElementById('nu').value = '';
    }
}
</script>