<!DOCTYPE html>
<html lang="en">

<head>
    <title>Travel Tipia</title>

    <?php include '../private/partial/head.php'; ?>

    <link rel="stylesheet" href="discussion_list.css" />
    <link rel="stylesheet" href="interactions.css" />
    

    <script src="discussion_interactions.js"></script>
</head>

<?php include '../private/partial/header.php'; ?>

<?php
require_once '../private/discussion_list.php';

require '../private/dbConnection.php';

$userId = $_SESSION['userId'] ?? 0;

$query = "SELECT 
    discussionId, 
    title, 
    content, 
    username,
    dateCreated,

     -- Like and dislike count queries
    (SELECT COUNT(*) FROM discussion_interactions a1 WHERE a1.discussionId = a.discussionId) AS heartCount,
    
    -- Booleans for current user
    EXISTS (
        SELECT 1 FROM discussion_interactions a4
        WHERE a4.discussionId = a.discussionId AND a4.userId = ?
    ) AS heartedByUser

FROM discussion a JOIN users ON a.authorId = users.userId WHERE a.authorId=? ORDER BY dateCreated DESC";
$preparedStatement = $conn->prepare($query);
$preparedStatement->bind_param("ii", $userId, $userId);
$preparedStatement->execute();
$result = $preparedStatement->get_result();
?>

<main>
<?php if ($userId != 0) { ?>
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1 style="margin-bottom: 10px;">My Discussions</h1>
        <button style="width: 16rem;" onclick="window.location.href='add_discussion.php'">Add Discussion</button>
    </div>
    <?php createDiscussionList($result);
    } else { ?>
        <section class="card">
            <h2>My Discussions</h2>
            <p style="text-align: center; margin-top: 10px;">You need to be logged in to see your discussions<br /><a
                    href="login.php">Login here!</a></p>
        </section>
    <?php } ?>
</main>

<?php include '../private/partial/footer.php'; ?>