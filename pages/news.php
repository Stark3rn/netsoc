<?php
?>
<link rel="stylesheet" href="../style.css">




<main>
    <h2>Flux des nouveautés</h2>
    <div id="news-feed"></div>
</main>






<script>
async function loadGlobalNews() 
{
    const response = await fetch('api/news.php');
    const posts = await response.json();
    const container = document.getElementById('news-feed');   
    container.innerHTML = posts.map(post => 
    `
        <div class="commentbox">
            <div style="display:flex; justify-content: space-between; align-items: baseline;">
                <strong>${post.username}</strong> 
                <small>le ${post.created}</small>
            </div>
            <p>${post.content}</p>
        </div>
    `).join('');
}
loadGlobalNews();
</script>