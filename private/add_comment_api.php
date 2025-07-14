<?php
session_start();

if (!isset($_SESSION["userId"]) || !$_SESSION["userId"]) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$userId = $_SESSION['userId'];

if (!isset($_POST['content']) || strlen($_POST['content']) > 1000 || $_POST['content'] == '') {
    echo json_encode(['success' => false, 'message' => 'Content field invalid']);
    exit();
}

if (!isset($_POST['discussionId'])) {
    echo json_encode(['success' => false, 'message' => 'Content field invalid']);
    exit();
}

$discussionId = $_POST['discussionId'];

require 'dbConnection.php';

$content = $_POST['content'];

$preparedStatement = $conn->prepare('INSERT INTO discussion_comments (discussionId, authorId, content) VALUES (?, ?, ?)');
$preparedStatement->bind_param('iis', $discussionId, $userId, $content);
$preparedStatement->execute();

echo json_encode(['success' => true, 'message' => 'Comment added']);
exit();

?>