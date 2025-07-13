<?php
session_start();
header('Content-Type: application/json');

// Error handling (optional but recommended)
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['userId']) || $_SESSION['userId'] == 0) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$userId = (int) $_SESSION['userId'];

if (!isset($_POST['discussionId'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$discussionId = (int) $_POST['discussionId'];

require 'dbConnection.php';

// Check if interaction already exists
$stmt = $conn->prepare("SELECT 1 FROM discussion_interactions WHERE discussionId = ? AND userId = ?");
$stmt->bind_param('ii', $discussionId, $userId);
$stmt->execute();
$result = $stmt->get_result();
$existingInteraction = $result->fetch_assoc();
$stmt->close();

if ($existingInteraction) {
    $stmt = $conn->prepare("DELETE FROM discussion_interactions WHERE discussionId = ? AND userId = ?");
    $stmt->bind_param('ii', $discussionId, $userId);
    $stmt->execute();
    $stmt->close();
} else {
    $stmt = $conn->prepare("INSERT INTO discussion_interactions (discussionId, userId) VALUES (?, ?)");
    $stmt->bind_param('ii', $discussionId, $userId);
    $stmt->execute();
    $stmt->close();
}

// Get updated heart count
$stmt = $conn->prepare("SELECT COUNT(*) AS heartCount FROM discussion_interactions WHERE discussionId = ?");
$stmt->bind_param('i', $discussionId);
$stmt->execute();
$result = $stmt->get_result();
$counts = $result->fetch_assoc();
$stmt->close();
$conn->close();

// Output correct JSON
echo json_encode([
    'success' => true,
    'heartCount' => (int) ($counts['heartCount'] ?? 0),
]);
?>