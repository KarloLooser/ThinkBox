<?php
// delete_book.php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    http_response_code(403);
    echo json_encode(['error' => 'access_denied']);
    exit;
}

if (!isset($_POST['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'missing_id']);
    exit;
}

require_once 'db.php';
$id = (int)$_POST['id'];
$pdo->prepare('DELETE FROM uploads WHERE id = ?')->execute([$id]);
echo json_encode(['status' => 'ok']);