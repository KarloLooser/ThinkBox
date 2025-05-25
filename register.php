<?php
require 'db.php';
header('Content-Type: application/json'); // Let JS know it's a JSON response

$response = ['success' => false, 'message' => 'Registration failed.'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['uname'] ?? '';
    $password = $_POST['psw'] ?? '';

    if (!empty($username) && !empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");

        try {
            $stmt->execute([$username, $hashedPassword]);
            $response['success'] = true;
            $response['message'] = 'Registration successful!';
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Unique constraint violation
                $response['message'] = 'Username already exists.';
            } else {
                $response['message'] = 'Database error: ' . $e->getMessage();
            }
        }
    } else {
        $response['message'] = 'Username and password are required.';
    }
}

echo json_encode($response);
?>