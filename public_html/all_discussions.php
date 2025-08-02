<?php
$pageDescription = "Read all the travel discussions on Travel Tipia. A travel tip site and discussion board for all your travel needs";
$pageKeywords = "discussion board, travel tips, travel, explore, adventure, community, tip, hub";

session_start();

require_once '../private/discussion_list.php';

require '../private/dbConnection.php';

$userId = $_SESSION['userId'] ?? 0;

//Get discussions
$query = "SELECT 
    a.discussionId, 
    a.title, 
    a.content, 
    a.authorId,
    users.username,
    a.dateCreated,

     -- heart count count queries
    (SELECT COUNT(*) FROM discussion_interactions a1 WHERE a1.discussionId = a.discussionId) AS heartCount,
    
    -- Does current user heart discussion
    EXISTS (
        SELECT 1 FROM discussion_interactions a4
        WHERE a4.discussionId = a.discussionId AND a4.userId = ?
    ) AS heartedByUser

FROM discussion a JOIN users ON a.authorId = users.userId ORDER BY dateCreated DESC";
$preparedStatement = $conn->prepare($query);
$preparedStatement->bind_param("i", $userId);
$preparedStatement->execute();
$result = $preparedStatement->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../private/partial/head.php'; ?>

    <link rel="stylesheet" href="discussion_list.css" />
    <link rel="stylesheet" href="interactions.css" />
    

    <script src="discussion_interactions.js"></script>
    <script src="edit_discussion.js"></script>
</head>

<?php include '../private/partial/header.php'; ?>

<main>
    <!-- show title and put add discussion button on right. Discussion list under it -->
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1 style="margin-bottom: 10px;">All Discussions</h1>
        <button style="width: 16rem;" onclick="window.location.href='add_discussion.php'">Add Discussion</button>
    </div>
    <?php createDiscussionList($result) ?>
</main>

<?php include '../private/partial/footer.php'; ?>