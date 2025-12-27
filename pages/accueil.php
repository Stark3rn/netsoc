<?php
require_once(__DIR__ . "/../database.php");
$idUser = check_session();
?>
<link rel="stylesheet" href="../style.css">

<main>
    <h1>Accueil</h1>
    
    <?php if ($idUser): ?>
    <div id="formdiv">
        <textarea id="t" placeholder="Quoi de neuf ?" style="width:100%; height:80px;"></textarea>
        <input type="text" id="i" placeholder="URL image" style="width:100%; margin: 10px 0;">
        <button onclick="send()" style="width:100%; cursor:pointer;">Publier</button>
    </div>
    <?php endif; ?>

    <div id="f"></div>
</main>

<script>
async function load() 
{
    const r = await fetch('api/news.php');
    const posts = await r.json();
    document.getElementById('f').innerHTML = posts.map(p => `
        <div class="commentbox">
            <div style="display:flex; align-items:center;">
                <img src="${p.profile_pic || 'https://www.w3schools.com/howto/img_avatar.png'}" style="width:40px; height:40px; border-radius:50%; margin-right:10px;">
                <strong class="username">${p.username}</strong>
            </div>
            <p>${p.content}</p>
            ${p.image_url ? `<img src="${p.image_url}" style="width:100%; border-radius:8px; margin-top:10px;">` : ''}
            <div style="margin-top:10px;">
                <button onclick="like(${p.id})">👍 ${p.likes_count}</button>
            </div>
        </div>
    `).join('');
}

async function like(id) 
{
    const fd = new FormData();
    fd.append('id_post', id);
    await fetch('api/interactions.php', 
    { 
        method: 'POST', 
        body: fd 
    });
    load();
}

async function send() 
{
    const content = document.getElementById('t').value;
    const img = document.getElementById('i').value;
    if (!content && !img) return;

    const fd = new FormData();
    fd.append('content', content);
    fd.append('image_url', img);
    await fetch('api/news.php', 
    { 
        method: 'POST', 
        body: fd 
    });
    document.getElementById('t').value = '';
    document.getElementById('i').value = '';
    load();
}

load();
</script>