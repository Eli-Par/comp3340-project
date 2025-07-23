<?php
session_start();

//Check if user logged in
if (!isset($_SESSION["userId"]) || !$_SESSION["userId"]) {
    http_response_code(403);
    echo "Not authenticated";
    exit();
}

//Check that discussionId provided
if (!isset($_POST['discussionId']) || !is_numeric($_POST['discussionId'])) {
    http_response_code(400);
    echo "Invald discussionId";
    exit();
}

$discussionId = intval($_POST['discussionId']);
$userId = $_SESSION['userId'];
$isAdmin = isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'];

require 'dbConnection.php';

if ($isAdmin) {
    // Admins can delete any discussion
    $stmt = $conn->prepare("DELETE FROM discussion WHERE discussionId = ?");
    $stmt->bind_param('i', $discussionId);
} else {
    //otherwise can only delete owned discussion (where author is user)
    $stmt = $conn->prepare("DELETE FROM discussion WHERE discussionId = ? AND authorId = ?");
    $stmt->bind_param('ii', $discussionId, $userId);
}

//Execute the statement and return a response for if it worked.
if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        http_response_code(200);
        echo "Discussion deleted successfully.";
    } else {
        http_response_code(403);
        echo "You are not authorized to delete this discussion.";
    }
} else {
    http_response_code(500);
    echo "Error deleting discussion.";
}
?>