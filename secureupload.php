<?php
require 'db.php'; // Include database connection
session_start();
$userId = $_SESSION['user_id']; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $epub = $_FILES['epub'];
    $image = $_FILES['image'];
    $visibility  = (isset($_POST['visibility']) && $_POST['visibility']==='private')
               ? 'private' : 'public';

    // Validate title and description
    if (empty($title) || empty($description)) {
        echo json_encode(["status" => "error", "message" => "Title and description cannot be empty."]);
        exit;
    }

    // Validate EPUB file
    if ($epub['size'] > 0 && $epub['size'] <= 10 * 1024 * 1024 && mime_content_type($epub['tmp_name']) == 'application/epub+zip') {
        $epubData = file_get_contents($epub['tmp_name']);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid EPUB file. It must be less than 5MB and in EPUB format."]);
        exit;
    }

    // Validate image file
    if ($image['size'] > 0 && $image['size'] <= 1024 * 1024 && in_array(mime_content_type($image['tmp_name']), ['image/jpeg', 'image/png', 'image/gif'])) {
        $imageData = file_get_contents($image['tmp_name']);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid image file. It must be less than 1MB and in JPG, PNG, or GIF format."]);
        exit;
    }



    // Insert into database
    $sql = 'INSERT INTO uploads
        (user_id, title, description, epub_data, image_data, visibility)
        VALUES (?,       ?,     ?,           ?,          ?,          ?)';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$userId, $title, $description, $epubData, $imageData, $visibility]);

    echo json_encode(["status" => "success", "message" => "Upload successful!"]);
    exit;
}
?>