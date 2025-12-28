<?php
require_once(__DIR__ . "/../database.php");
$idUser = check_session();

$stmt = $Database->prepare("SELECT promote FROM users WHERE id_user = ? LIMIT 1");
$stmt->execute([$idUser]);
$result = $stmt->fetch();
$promote = $result['promote'];
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
    <div id="ads-container" style="max-width:600px; margin:1rem auto; text-align:center;"></div>
    <div id="posts-container" style="max-width:600px; margin:2rem auto;"></div>
</main>

<script>
const PROMOTE = <?= (int)$promote ?>;
async function loadAd() {
    if (PROMOTE !== 1) return;

    const r = await fetch('http://localhost/netsoc/api/ads.php?get_ads=1');
    const data = await r.json();
    if (!data.ad_image) return;

    const adsContainer = document.getElementById('ads-container');
    adsContainer.innerHTML = `
        <img src="${data.ad_image}"
             alt="Publicité"
             style="max-width:100%; border-radius:12px;">
    `;
}

async function load() 
{
    const r = await fetch('api/news.php?following=1');
    const posts = await r.json();

    const container = document.getElementById('posts-container');
    if(posts.length === 0){
        container.innerHTML = `<p style="text-align:center; color:var(--muted-text);">Suivez des utilisateurs pour voir leurs publications.</p>`;
        return;
    }

    container.innerHTML = posts.map(p => `
        <div class="card" style="margin-bottom:1.5rem;">
            <div style="display:flex; align-items:center; margin-bottom:0.8rem;">
                <img src="${p.profile_pic || 'https://www.w3schools.com/howto/img_avatar.png'}" 
                     style="width:40px; height:40px; border-radius:50%; margin-right:10px; object-fit:cover;">
                <a href="index.php?page=profile.php&username=${encodeURIComponent(p.username)}" 
                   style="color:var(--title-color); font-weight:600; text-decoration:none;">
                    ${p.username}
                </a>
            </div>
            ${p.image_url ? `<img src="${p.image_url}" 
                 alt="Post image" style="width:100%; border-radius:12px; margin-bottom:0.8rem; object-fit:cover;">` : ''}
            <p>${p.content}</p>
            <div style="margin-top:10px;">
                <button onclick="like(${p.id})"> ❤️ ${p.likes_count}</button>
            </div>
            <small style="color:var(--muted-text);">${p.created}</small>
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

// Chargement initial
load();
loadAd();
</script>
