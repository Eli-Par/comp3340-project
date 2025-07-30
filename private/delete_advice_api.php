<?php
session_start();
require_once 'dbConnection.php';

$userId = $_SESSION['userId'] ?? 0;
$isAdmin = $_SESSION['isAdmin'] ?? 0;

$adviceId = $_POST['adviceId'] ?? 0;

//Check user is admin
if (!$userId || !$isAdmin || !$adviceId) {
    echo json_encode(['success' => false, 'message' => 'Not Authorized']);
    exit;
}

// Delete advice
$stmt = $conn->prepare("DELETE FROM advice WHERE adviceId = ?");
$stmt->bind_param("i", $adviceId);

$success = $stmt->execute();

// return result
if($success) echo json_encode(['success' => true, 'message' => 'Success']);
else echo json_encode(['success' => false, 'message' => 'Database Issue']);
?>