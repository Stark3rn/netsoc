<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require_once __DIR__ . '/../../database.php';

file_put_contents(
    __DIR__ . '/debug.log',
    date('c') . " HIT\n",
    FILE_APPEND
);

$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    echo json_encode(['error' => 'no data']);
    exit;
}

$stmt = $Database->prepare("
    INSERT INTO telescreen_logs
    (
        id_user,
        visited_url,
        referrer,
        page_title,
        user_agent,
        language,
        screen,
        cookies,
        ip,
        created
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
");

$stmt->execute([
    $data['uid'] ?? null,
    $data['url'] ?? null,
    $data['referrer'] ?? null,
    $data['title'] ?? null,
    $data['userAgent'] ?? null,
    $data['language'] ?? null,
    $data['screen'] ?? null,
    $data['cookies'] ?? null,
    $_SERVER['REMOTE_ADDR']
]);

echo json_encode(['stored' => true]);
