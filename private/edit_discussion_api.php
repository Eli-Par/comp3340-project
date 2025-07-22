<?php
session_start();
header('Content-Type: application/json');

require 'dbConnection.php';

$userId = $_SESSION['userId'] ?? 0;
$isAdmin = $_SESSION['isAdmin'] ?? 0;

if ($userId == 0) {
    header("Location: /comp3340-project/public_html/login.php");
    exit();
}

$discussionId = isset($_POST['discussionId']) ? (int) $_POST['discussionId'] : 0;
$title = $_POST['title'] ?? '';
$content = $_POST['content'] ?? '';

if ($discussionId <= 0) {
    header("Location: /comp3340-project/public_html/all_discussions.php");
    exit();
}

// Validate title length
if (strlen($title) < 4 || strlen($title) > 200) {
    header("Location: /comp3340-project/public_html/all_discussions.php");
    exit();
}
// Validate content not empty
if (empty($content)) {
    header("Location: /comp3340-project/public_html/all_discussions.php");
    exit();
}

// Check discussion exists and user permissions
$query = "SELECT authorId FROM discussion WHERE discussionId = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $discussionId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => 'Discussion not found']);
    exit();
}

$discussion = $result->fetch_assoc();

// Only allow if user is author or admin
if (!$isAdmin && $discussion['authorId'] != $userId) {
    header("Location: /comp3340-project/public_html/all_discussions.php");
    exit();
}

// update discussion
$updateQuery = "UPDATE discussion SET title = ?, content = ?, dateModified = NOW() WHERE discussionId = ?";
$updateStmt = $conn->prepare($updateQuery);
$updateStmt->bind_param('ssi', $title, $content, $discussionId);

if ($updateStmt->execute()) {
    header("Location: /comp3340-project/public_html/all_discussions.php");
    exit();
} else {
    exit();
}
