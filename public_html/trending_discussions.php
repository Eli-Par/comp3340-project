<?php
$pageDescription = "Read the trending travel discussions on Travel Tipia. A travel tip site and discussion board for all your travel needs";
$pageKeywords = "popular discussions, discussion board, travel tips, travel, explore, adventure, community, tip, hub";

session_start();

require_once '../private/discussion_list.php';

require '../private/dbConnection.php';
require '../private/trending_discussion_query.php';

$userId = $_SESSION['userId'] ?? 0;

//Get trending discussions
$query = trending_discussion_query();
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

<!-- show list of trending discussions -->
<main>
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1 style="margin-bottom: 10px;">Trending Discussions</h1>
        <button style="width: 16rem;" onclick="window.location.href='add_discussion.php'">Add Discussion</button>
    </div>
    <?php createDiscussionList($result) ?>
</main>

<?php include '../private/partial/footer.php'; ?>