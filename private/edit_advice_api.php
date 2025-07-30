<?php
session_start();

require 'dbConnection.php';

$userId = $_SESSION['userId'] ?? 0;
$isAdmin = $_SESSION['isAdmin'] ?? 0;

//Check if admin
if ($userId == 0 || !$isAdmin) {
    header("Location: /comp3340-project/public_html/index.php");
    exit();
}

//Get fields
$adviceId = isset($_POST['adviceId']) ? (int) $_POST['adviceId'] : 0;
$title = $_POST['title'] ?? '';
$imageLink = $_POST['imageLink'] ?? '';
$content = $_POST['content'] ?? '';
$summary = $_POST['summary'] ?? '';

//check fields valid
if ($adviceId <= 0 || strlen($title) < 4 || strlen($title) > 200 || empty($content)) {
    header("Location: /comp3340-project/public_html/all_advice.php");
    exit();
}

// check if advice exists
$query = "SELECT * FROM advice WHERE adviceId = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $adviceId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: /comp3340-project/public_html/all_discussions.php");
    exit();
}

// Update Advice
$updateQuery = "UPDATE advice SET title = ?, imageLink = ?, summary = ?, content = ? WHERE adviceId = ?";
$updateStmt = $conn->prepare($updateQuery);
$updateStmt->bind_param("ssssi", $title, $imageLink, $summary, $content, $adviceId);

if ($updateStmt->execute()) {
    header("Location: /comp3340-project/public_html/all_advice.php");
    exit();
} else {
    exit();
}
