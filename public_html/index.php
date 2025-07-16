<?php
session_start();

require_once '../private/advice_grid.php';
require_once '../private/discussion_list.php';

require '../private/dbConnection.php';
require '../private/trending_discussion_query.php';

$userId = $_SESSION['userId'] ?? 0;

//Get advice
$adviceQuery = "SELECT 
    adviceId, 
    title, 
    content, 
    username,
    dateCreated,
    (SELECT COUNT(*) FROM advice_interactions a1 WHERE a1.adviceId = a.adviceId AND a1.isLike = 1) AS likeCount,
    (SELECT COUNT(*) FROM advice_interactions a2 WHERE a2.adviceId = a.adviceId AND a2.isLike = 0) AS dislikeCount,
    EXISTS (
        SELECT 1 FROM advice_interactions a3 WHERE a3.adviceId = a.adviceId AND a3.userId = ? AND a3.isLike = 1
    ) AS likedByUser,
    EXISTS (
        SELECT 1 FROM advice_interactions a4 WHERE a4.adviceId = a.adviceId AND a4.userId = ? AND a4.isLike = 0
    ) AS dislikedByUser
FROM advice a JOIN users ON a.authorId = users.userId";
$stmtAdvice = $conn->prepare($adviceQuery);
$stmtAdvice->bind_param("ii", $userId, $userId);
$stmtAdvice->execute();
$resultAdvice = $stmtAdvice->get_result();

// Get trending discussions
$discussionQuery = trending_discussion_query(4);
$stmtDiscussion = $conn->prepare($discussionQuery);
$stmtDiscussion->bind_param("i", $userId);
$stmtDiscussion->execute();
$resultDiscussion = $stmtDiscussion->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Travel Tipia</title>

    <?php include '../private/partial/head.php'; ?>

    <link rel="stylesheet" href="advice_grid.css" />
    <link rel="stylesheet" href="discussion_list.css" />
    <link rel="stylesheet" href="interactions.css" />
    <link rel="stylesheet" href="index.css" />

    <script src="advice_interactions.js"></script>
    <script src="discussion_interactions.js"></script>
</head>

<?php include '../private/partial/header.php'; ?>

<main>
    <section class="left-column">
        <h2 style="font-size: 1.7rem">All Advice</h2>
        <?php createAdviceGrid($resultAdvice); ?>
    </section>

    <aside class="right-column card">
        <h2>Trending Discussions</h2>
        <?php createDiscussionList($resultDiscussion); 
        if($userId) { ?>
            <button onclick="window.location.href='add_discussion.php'">Add Discussion</button>
        <?php }
        ?>
    </aside>
</main>

<?php include '../private/partial/footer.php'; ?>

</html>
