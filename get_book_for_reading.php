<?php
require 'db.php'; // Include database connection

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo "Missing book ID";
    exit;
}

$bookId = intval($_GET['id']);

$stmt = $pdo->prepare("SELECT epub_data FROM uploads WHERE id = ?");
$stmt->execute([$bookId]);

$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    http_response_code(404);
    echo "Book not found";
    exit;
}

header("Content-Type: application/epub+zip");
header("Content-Disposition: inline; filename=\"book.epub\"");
echo $book['epub_data'];
?>