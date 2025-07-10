<?php
session_start();
header('Content-Type: application/json');

// Check user logged in
if (!isset($_SESSION['userId']) || $_SESSION['userId'] == 0) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

$userId = (int) $_SESSION['userId'];

if (
    !isset($_POST['adviceId']) ||
    !isset($_POST['action'])
) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$adviceId = (int) $_POST['adviceId'];
$action = $_POST['action'];

if (!in_array($action, ['like', 'dislike'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
    exit;
}

$isLike = $action == 'like' ? 1 : 0;

require 'dbConnection.php';

$stmt = $conn->prepare("SELECT isLike FROM advice_interactions WHERE adviceId = ? AND userId = ?");
$stmt->bind_param('ii', $adviceId, $userId);
$stmt->execute();
$result = $stmt->get_result();
$existingInteraction = $result->fetch_assoc();
$stmt->close();

if ($existingInteraction) {
    if ($existingInteraction['isLike'] == $isLike) {
        //Existing interaction is same type so delete
        $stmt = $conn->prepare("DELETE FROM advice_interactions WHERE adviceId = ? AND userId = ?");
        $stmt->bind_param('ii', $adviceId, $userId);
        $stmt->execute();
        $stmt->close();
    } else {
        //Existing interaction is different type so update
        $stmt = $conn->prepare("UPDATE advice_interactions SET isLike = ? WHERE adviceId = ? AND userId = ?");
        $stmt->bind_param('iii', $isLike, $adviceId, $userId);
        $stmt->execute();
        $stmt->close();
    }
} else {
    // No interaction yet so insert a new record
    $stmt = $conn->prepare("INSERT INTO advice_interactions (adviceId, userId, isLike) VALUES (?, ?, ?)");
    $stmt->bind_param('iii', $adviceId, $userId, $isLike);
    $stmt->execute();
    $stmt->close();
}

//Get the final like and dislike counts and return it as json
$stmt = $conn->prepare("SELECT
        COUNT(CASE WHEN isLike = 1 THEN 1 END) AS likeCount,
        COUNT(CASE WHEN isLike = 0 THEN 1 END) AS dislikeCount
    FROM advice_interactions
    WHERE adviceId = ?");
$stmt->bind_param('i', $adviceId);
$stmt->execute();
$result = $stmt->get_result();
$counts = $result->fetch_assoc();
$stmt->close();
$conn->close();

echo json_encode([
    'success' => true,
    'likeCount' => (int) ($counts['likeCount'] ?? 0),
    'dislikeCount' => (int) ($counts['dislikeCount'] ?? 0),
]);

?>