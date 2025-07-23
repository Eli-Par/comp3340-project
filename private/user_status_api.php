<?php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);


session_start();

header('Content-Type: application/json');

$isAdmin = $_SESSION['isAdmin'] ?? 0;

// Check that user is admin
if (!$isAdmin) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Validate that POST input is valid
if ($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_POST['userId']) || !isset($_POST['isActive'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
    exit;
}

$userId = trim($_POST['userId']);
$isActive = trim($_POST['isActive']);

require_once 'dbConnection.php';

try {
    //update the users status and return result
    $stmt = $conn->prepare("UPDATE users SET isActive = ? WHERE userId = ?");
    $stmt->bind_param('si', $isActive, $userId);
    $stmt->execute();

    echo json_encode(['success' => true, 'isActive' => $isActive]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
    exit;
}
