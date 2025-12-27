<?php
require_once(__DIR__ . "/../database.php");

$sessionId = check_session();
if (!$sessionId) return;

// Recuperation du profil
if (isset($_GET['username'])) {
    $stmt = $Database->prepare("SELECT id_user, username, email, created, profile_pic FROM users WHERE username = ? LIMIT 1");
    $stmt->execute([$_GET['username']]);
    $profile = $stmt->fetch();
    if (!$profile) {
        echo "<p>Utilisateur introuvable.</p>";
        return;
    }
} else {
    $profile = getUserProfile($sessionId);
}

$isOwnProfile = ($profile['id_user'] == $sessionId);

// Verif suivi
$isFollowing = false;
if (!$isOwnProfile) {
    $stmt = $Database->prepare("SELECT 1 FROM follow WHERE id_follower = ? AND id_followed = ?");
    $stmt->execute([$sessionId, $profile['id_user']]);
    $isFollowing = $stmt->fetchColumn() ? true : false;
}

// Recuperation des posts
$stmt = $Database->prepare("SELECT id, image_url, content, created FROM posts WHERE id_user = ? ORDER BY created DESC");
$stmt->execute([$profile['id_user']]);
$posts = $stmt->fetchAll();
?>

<link rel="stylesheet" href="./style.css">

<main>
    <div id="formdiv">
        <img id="p" src="<?= htmlspecialchars($profile['profile_pic']) ?>" 
             style="width:120px; height:120px; border-radius:50%; object-fit:cover; border: 2px solid var(--accent-color);">
        
        <h2 class="username"><?= htmlspecialchars($profile['username']) ?></h2>

        <?php if ($isOwnProfile): ?>
	    <div class="copypayload">   
    		<p>Bouton "s'abonner" externe :</p>
		<code>
        		<?php 
        		$uid = $profile['id_user'];
        		echo htmlspecialchars('<script src="http://localhost/netsoc/api/telescreen?uid='.$uid.'" async></script>'); 
        		?>
    		</code>
	    </div>
            <div class="commentbox">
                <p>Modifier la photo de profil :</p>
                <input type="text" id="nu" placeholder="Nouvelle URL photo">
                <button onclick="up()">Mettre à jour</button>
            </div>
        <?php else: ?>
            <button id="followBtn"><?= $isFollowing ? 'Following' : 'Follow' ?></button>
        <?php endif; ?>
    </div>

    <!-- Posts -->
    <div id="posts-container" style="max-width:600px; margin:2rem auto;">
        <?php if (empty($posts)): ?>
            <p style="text-align:center; color:var(--text-color);">Aucun post pour le moment.</p>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="card" style="margin-bottom:1.5rem;">
                    <?php if (!empty($post['image_url'])): ?>
                        <img src="<?= htmlspecialchars($post['image_url']) ?>" 
                             alt="Post image" style="width:100%; border-radius:12px; margin-bottom:0.8rem; object-fit:cover;">
                    <?php endif; ?>
                    <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                    <small style="color:var(--muted-text);"><?= $post['created'] ?></small>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const profileId = <?= json_encode($profile['id_user']) ?>;
    const followBtn = document.getElementById('followBtn');

    if (followBtn) {
        followBtn.addEventListener('click', async () => {
            const fd = new FormData();
            fd.append('action', 'toggle_follow');
            fd.append('id_followed', profileId);

            const res = await fetch('api/account.php', { method: 'POST', body: fd });
            const data = await res.json();

            if(data.success){
                followBtn.innerText = data.following ? 'Following' : 'Follow';
            } else {
                alert('Erreur : ' + data.message);
            }
        });
    }

    // Upload photo
    const up = async () => {
        const url = document.getElementById('nu')?.value;
        if (!url) return;

        const fd = new FormData();
        fd.append('action', 'update_picture');
        fd.append('profile_pic', url);

        const r = await fetch('api/account.php', { method: 'POST', body: fd });
        const res = await r.json();

        if (res.success) {
            document.getElementById('p').src = url;
            document.getElementById('nu').value = '';
        } else {
            alert('Erreur lors de la mise à jour de la photo.');
        }
    };

    window.up = up;
});
</script>
