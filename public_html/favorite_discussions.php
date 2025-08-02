<?php
$pageDescription = "Read all your favorite travel discussions on Travel Tipia. A travel tip site and discussion board for all your travel needs";
$pageKeywords = "hearted disucssions, discussion board, travel tips, travel, explore, adventure, community, tip, hub";

session_start();

require_once '../private/discussion_list.php';

require '../private/dbConnection.php';

$userId = $_SESSION['userId'] ?? 0;

//Get favorite discussions
$query = "SELECT 
    a.discussionId, 
    a.title, 
    a.content, 
    a.authorId,
    users.username,
    a.dateCreated,

     -- heart count queries
    (SELECT COUNT(*) FROM discussion_interactions a1 WHERE a1.discussionId = a.discussionId) AS heartCount,
    
    -- Is discussuon hearted by user
    EXISTS (
        SELECT 1 FROM discussion_interactions a4
        WHERE a4.discussionId = a.discussionId AND a4.userId = ?
    ) AS heartedByUser

FROM discussion a JOIN users ON a.authorId = users.userId 
WHERE EXISTS (
    SELECT 1 FROM discussion_interactions a4
    WHERE a4.discussionId = a.discussionId AND a4.userId = ?
) ORDER BY dateCreated DESC";
$preparedStatement = $conn->prepare($query);
$preparedStatement->bind_param("ii", $userId, $userId);
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

<!-- show discussion list and add discussion button or login prompt if logged out -->
<main>
<?php if ($userId != 0) { ?>
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1 style="margin-bottom: 10px;">Favorite Discussions</h1>
        <button style="width: 16rem;" onclick="window.location.href='add_discussion.php'">Add Discussion</button>
    </div>
    <?php createDiscussionList($result);
    } else { ?>
        <section class="card">
            <h2>Favorite Discussions</h2>
            <p style="text-align: center; margin-top: 10px;">You need to be logged in to see your favorite discussions<br /><a
                    href="login.php">Login here!</a></p>
        </section>
    <?php } ?>
</main>

<?php include '../private/partial/footer.php'; ?>