<?php
// get_admin_data.php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    http_response_code(403);
    echo json_encode(['error' => 'access_denied']);
    exit;
}

require_once 'db.php';           // ← your PDO $pdo lives here

$sql = 'SELECT u.id   AS user_id,
               u.username,
               up.id  AS book_id,
               up.title,
               up.description,
               up.uploaded_at
        FROM users u
        LEFT JOIN uploads up ON up.user_id = u.id
        ORDER BY u.id, up.uploaded_at DESC';

$stmt = $pdo->query($sql);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$payload = [];
foreach ($rows as $r) {
    $uid = $r['user_id'];
    if (!isset($payload[$uid])) {
        $payload[$uid] = [
            'id'       => $uid,
            'username' => $r['username'],
            'books'    => []
        ];
    }
    if ($r['book_id']) {
        $payload[$uid]['books'][] = [
            'id'          => $r['book_id'],
            'title'       => $r['title'],
            'description' => $r['description'],
            'uploaded_at' => $r['uploaded_at']
        ];
    }
}
echo json_encode(array_values($payload));