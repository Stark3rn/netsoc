<?php
require_once "database.php";
$isLogged = check_session();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nubii</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="icon" href="./favicon.ico" type="image/x-icon">
</head>
<body>

<?php if ($isLogged): ?>
    <aside class="sidebar">
        <div class="sidebar-header">
            <h1 class="app-title">Nubii</h1>
            <p class="app-subtitle">Ton reseau prend vie</p>
        </div>

        <nav class="sidebar-nav">
            <ul>
                <li><a href="?page=accueil.php">Accueil</a></li>
                <li><a href="?page=profile.php">Mon profil</a></li>
            </ul>
        </nav>
    </aside>
<?php endif; ?>


<div class="app-container">
<div class="search-bar">
    <input type="text" id="searchInput" placeholder="Rechercher un profil..." autocomplete="off">
    <div class="search-suggestions" id="searchSuggestions"></div>
</div>

    <main class="content">
        <?php
        if (!$isLogged) {
            $page = $_GET["page"] ?? "login.php";
            if ($page !== "login.php" && $page !== "register.php") {
                $page = "login.php";
            }
            include "pages/$page";
        } else {
            $page = $_GET["page"] ?? "accueil.php";
            include "pages/$page";
        }
        ?>
    </main>
</div>

<script>
const searchInput = document.getElementById('searchInput');
const suggestions = document.getElementById('searchSuggestions');

// Fonction pour afficher les résultats
function renderSuggestions(users) {
    suggestions.innerHTML = '';
    users.forEach(user => {
        const a = document.createElement('a');
        a.href = `?page=profile.php&username=${encodeURIComponent(user.username)}`;
        a.classList.add('suggestion-item');
        a.innerHTML = `<img src="${user.profile_pic}" alt="${user.username}">${user.username}`;
        suggestions.appendChild(a);
    });
}

// Afficher les suggestions quand focus
searchInput.addEventListener('focus', () => {
    suggestions.style.display = 'flex';
});

// Masquer quand blur
searchInput.addEventListener('blur', () => {
    setTimeout(() => { suggestions.style.display = 'none'; }, 150);
});

// Mettre à jour les suggestions à chaque frappe
searchInput.addEventListener('input', async () => {
    const q = searchInput.value.trim();
    const res = await fetch(`api/search.php?q=${encodeURIComponent(q)}`);
    const users = await res.json();
    renderSuggestions(users);
});
</script>
</body>
</html>
