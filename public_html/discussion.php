<!DOCTYPE html>
<html lang="en">

<head>
    <title>Travel Tipia</title>

    <?php include '../private/partial/head.php'; ?>

    <link rel="stylesheet" href="interactions.css" />
    

    <script src="discussion_interactions.js"></script>
</head>

<?php include '../private/partial/header.php'; ?>

<?php
require_once '../private/discussion_card.php';

require '../private/dbConnection.php';

$userId = $_SESSION['userId'] ?? 0;
$discussionId = $_GET['discussionId'] ?? 0;

//Get the advices details
$query = "SELECT 
    discussionId, 
    title, 
    content, 
    username,
    dateCreated, 
    dateModified,

    -- Heart count queries
    (SELECT COUNT(*) FROM discussion_interactions a1 WHERE a1.discussionId = a.discussionId) AS heartCount,
    
    -- Booleans for current user
    EXISTS (
        SELECT 1 FROM discussion_interactions a4
        WHERE a4.discussionId = a.discussionId AND a4.userId = ?
    ) AS heartedByUser
FROM discussion a JOIN users ON a.authorId = users.userId WHERE a.discussionId=?";

$preparedStatement = $conn->prepare($query);
$preparedStatement->bind_param("ii", $userId, $discussionId);
$preparedStatement->execute();

$result = $preparedStatement->get_result();

//If the advice exists get all its properties, otherwise set defaults
if ($result->num_rows > 0) {
    $advice = $result->fetch_assoc();

    $title = $advice["title"] ?? "";
    $content = $advice["content"] ?? "";
    $username = $advice["username"] ?? "";

    $hearts = $advice["heartCount"];
    $isHearted = $advice["heartedByUser"];
}
else {
    $title = "Advice not found";
    $content = "";
    $username = "Unknown";

    $hearts = 0;
    $isHearted = 0;
}

?>

<main>
    <section class="card" style="max-width: 90vw; margin: 0 auto;">
        <h2><?php echo htmlentities($title) ?></h2>
        <?php 
            $dateCreated = date("F j, Y", strtotime($advice["dateCreated"]));
            $dateModified = date("F j, Y", strtotime($advice["dateModified"]));

            // Show date modified if different
            $showModified = ($advice["dateCreated"] != $advice["dateModified"]);
        ?>
        <div style="display: flex; justify-content: center; align-items: center; gap: 10px; font-size: 1.1em;">
            <span>By <strong><?php echo htmlentities($username); ?></strong></span>
            <span>Created: <?php echo $dateCreated; ?></span>
            <?php if ($showModified) { ?>
                <span>Modified: <?php echo $dateModified; ?></span>
            <?php } ?>
        </div>

        <div style="display: flex; justify-content: center;">
            <?php echo createDiscussionInteractionButtons($discussionId, $hearts, $isHearted, true) ?>
        </div>

        <p style="text-align: center; margin-top: 10px;">
            <?php echo nl2br(htmlspecialchars($content)) ?>
        </p>
    </section>
</main>

<?php include '../private/partial/footer.php'; ?>