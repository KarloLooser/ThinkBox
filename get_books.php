<?php
header('Content-Type: application/json');
require 'db.php';
session_start();

$books = [];


if (isset($_GET['private']) && $_GET['private'] == '1') {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode([]); // Nije logged in
        exit;
    }
    $userId = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT * FROM uploads WHERE visibility = 'Private' AND user_id = ? ORDER BY uploaded_at ASC");
    $stmt->execute([$userId]);
}
 else {
    $stmt = $pdo->query("SELECT * FROM uploads WHERE visibility = 'public' ORDER BY uploaded_at DESC");
}

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $books[] = [
        'title' => $row['title'],
        'id' => $row['id'],
        'description' => $row['description'],
        'uploaded_at' => $row['uploaded_at'],
        'image_data' => base64_encode($row['image_data']),
        'epub_data' => base64_encode($row['epub_data']),
    ];
}

echo json_encode($books);